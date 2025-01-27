<?php

namespace App\Services\Batching;

use App\Models\BatchJob;
use App\Models\BatchJobLine;
use App\Models\Team;
use App\Services\EmbeddingService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;

/**
 * Backend service for managing batch processing operations
 * 
 * This service handles the low-level batch processing operations including:
 * - Creating and managing batch jobs
 * - Processing batch lines
 * - Handling OpenAI batch API interactions
 * - Managing file operations for batch processing
 */
class BackendBatchingService
{
    private const METHOD_POST = 'POST';
    private const EMBEDDING_ENDPOINT = "/v1/embeddings";
    private const CHAT_ENDPOINT = "/v1/chat/completions";

    private const MODELS = ["chatting" => "gpt-4o-mini", "embedding" => "text-embedding-3-small"];

    private Team $team;
    private string $duration;

    /**
     * Create a new BackendBatchingService instance
     *
     * @param Team $team Team context for batch operations
     * @param string $duration Duration window for batch processing
     */
    public function __construct(Team $team, string $duration = "24h")
    {
        $this->team = $team;
        $this->duration = $duration;
    }

    /**
     * Create a new batch processing request
     *
     * @param string $action Type of action ('embedding', 'chatting')
     * @param string $type Content type ('text', 'file')
     * @param array|string $content Content to process
     * @return bool Success status
     * @throws \Exception If action or type not supported
     */
    public function create(string $action, string $type, array|string $content) : bool
    {
        throw_if(!in_array($action, ["embedding", "chatting"]), new \Exception("Action not supported"));
        throw_if(!in_array($type, ["text", "file"]), new \Exception("Type not supported"));
        $batch = $this->getCurrentBatch($action);
        if ($type === "text") {
            $line = $this->getRequest($action, self::MODELS[$action], $content);
            return $this->createLines($batch, $line, $content);
        } else {
            foreach ($content["lines"] as $line) {
                $req = $this->getRequest("embedding", self::MODELS["embedding"], $line["cleaned"]);
                $this->createLines($batch, $req, $line["cleaned"], $line["file_id"]);
            }
            return true;
        }
    }

    /**
     * Publish a batch for processing
     *
     * @param string $type Type of batch to publish
     * @return bool Success status
     */
    public function publish(string $type) : bool
    {
        try {
            $current_batch = $this->getCurrentBatch($type);
            if(!BatchJobLine::where('batch_job_id', $current_batch->id)->whereNull("finished_at")->first()) {
                return false;
            }
            $filePath = Storage::path($current_batch->batch_file);
            $previousBatchId = $current_batch->id;
            $this->getCurrentBatch($type, true);
            BatchJob::where('id', $previousBatchId)->update(['locked' => true]);

            $uploadedFile = OpenAI::files()->upload([
                'purpose' => 'batch',
                'file' =>  fopen($filePath, 'r'),
            ]);

            $batch = OpenAI::batches()->create([
                'input_file_id' => $uploadedFile->id,
                'endpoint' => $type === "embedding" ? self::EMBEDDING_ENDPOINT : self::CHAT_ENDPOINT,
                'completion_window' => $this->duration,
            ]);

            return BatchJob::where('id', $previousBatchId)->update(['batch_id' => $batch->id]);
        } catch (\Exception $e) {
            print_r($e->getMessage());
            Log::error('Error during batch publish: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieve and process completed batches
     *
     * @return bool Success status
     */
    public function retrieve() : bool
    {
        $jobs = BatchJob::whereNull("finished_at")->whereNotNull("batch_id")->get();
        foreach ($jobs as $job) {
            $retrieved_job = OpenAI::batches()->retrieve($job->batch_id);
            if ($retrieved_job->status === "completed") {
                $downloaded_file = $this->download($retrieved_job->outputFileId);
                foreach (File::lines(Storage::path($downloaded_file)) as $line) {
                    $line = json_decode($line);
                    if($line) {
                        $custom_id = $line->custom_id;
                        $batch_job_line = BatchJobLine::where("custom_id", $custom_id)->first();
                        if ($batch_job_line->type === "embedding") {
                            $team = $batch_job_line->file->team;
                            print("Embedding for team : " . $batch_job_line->file->team->name . "\n");
                            $embedding_service = new EmbeddingService(team: $team);
                            if ($batch_job_line->file_id) {
                                print("File: " . $batch_job_line->file->name . "\n");
                                $embedding_service->create("file", "import",
                                    [
                                        "file" => $batch_job_line->file,
                                        "lines" => [[
                                            "original" => $batch_job_line->original_text,
                                            "embedded" => json_encode($line->response->body->data[0]->embedding)
                                        ]]
                                    ]
                                );
                            } else {
                                $embedding_service->create("text", "import", [
                                    "original" => BatchJobLine::where("custom_id", $custom_id)->first()->original_text,
                                    "embedded" => json_encode($line->response->body->data[0]->embedding)
                                ]);
                            }
                            $batch_job_line->update(['finished_at' => now()]);
                        } else {
                            // Here deal with chatting ?
                        }
                    }
                }
                BatchJob::where('id', $job->id)->update(['finished_at' => now()]);
            } elseif ($retrieved_job->status === "failed") {
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    /**
     * Generate API request for batch processing
     *
     * @param string $action Action type
     * @param string $model AI model to use
     * @param array|string $content Content to process
     * @return array Request data
     */
    private function getRequest(string $action, string $model, array|string $content) : array {
        $endpoint = $action === "embedding" ? self::EMBEDDING_ENDPOINT : self::CHAT_ENDPOINT;
        $line = [
            "custom_id" => $action . "_" . $this->team->id . "_" . Str::uuid()->toString(),
            "method" => self::METHOD_POST,
            "url" => $endpoint,
            "body" => ["model" => $model],
        ];
        $line["body"][$action === "embedding" ? "input" : "messages"] = $content;
        return $line;
    }

    /**
     * Generate multiple requests for batch processing
     *
     * @param array $content Content array with lines
     * @return array Array of request data
     * @throws \Exception If lines not provided
     */
    private function getRequests(array $content) : array {
        throw_if(!array_key_exists("lines", $content), new \Exception("Must provide lines"));
        $lines = [];
        foreach ($content["lines"] as $line) {
            $lines[] = $this->getRequest("embedding", self::MODELS["embedding"], $line);
        }
        return $lines;
    }

    /**
     * Append line to batch file
     *
     * @param string $file_path Path to batch file
     * @param string $line Line to append
     */
    private function appendToFile(string $file_path, string $line) : void
    {
        $file = Storage::path($file_path);
        if (!Storage::exists($file_path)) {
            Storage::put($file_path, "");
        }
        file_put_contents($file, $line . "\n", FILE_APPEND);
    }

    /**
     * Generate unique file path for batch
     *
     * @return string Generated file path
     */
    private function generateFilePath() : string
    {
        return  "batch/" . $this->team->id . "/" . Str::uuid() . ".jsonl";
    }

    /**
     * Get or create current batch job
     *
     * @param string $type Batch type
     * @param bool $new Force create new batch
     * @return BatchJob
     */
    private function getCurrentBatch(string $type, bool $new = false) : BatchJob {
        if ($new) {
            return BatchJob::find(
                BatchJob::insertGetId(["batch_file" => $this->generateFilePath(), "type" => $type])
            );
        }
        return BatchJob::where("locked", false)->where("type", $type)
            ->latest()
            ->firstOr(function () use ($type) {
                return BatchJob::find(
                    BatchJob::insertGetId(["batch_file" => $this->generateFilePath(), "type" => $type])
                );
            });
    }

    /**
     * Download batch results file
     *
     * @param string $fileId OpenAI file ID
     * @return string Local file path
     */
    private function download(string $fileId) : string {
        $filePath = "batch/". $this->team->id ."/retrieved-" . $fileId . ".jsonl";
        $content = OpenAI::files()->download($fileId);
        Storage::put($filePath, $content);
        return $filePath;
    }

    /**
     * Create batch job lines
     *
     * @param BatchJob $batch Batch job
     * @param array $line Line data
     * @param array|string $content Original content
     * @param int|null $file_id Associated file ID
     * @return bool Success status
     */
    private function createLines(BatchJob $batch, array $line, array|string $content, int $file_id = null) : bool
    {
        $this->appendToFile($batch->batch_file, json_encode($line));
        $batch_job = [
            "custom_id" => $line["custom_id"],
            "type" => $batch->type,
            "body" => json_encode($line["body"]),
            "original_text" => is_array($content) ? json_encode($content) : $content,
            "batch_job_id" => $batch->id
        ];
        if ($file_id) $batch_job["file_id"] = $file_id;
        return BatchJobLine::insert($batch_job);
    }
}
