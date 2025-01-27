<?php

namespace App\Services;

use App\Jobs\AskJob;
use App\Jobs\AskStreamJob;
use App\Jobs\Batch\BatchEmbedFile;
use App\Jobs\Batch\BatchPublish;
use App\Jobs\Batch\BatchRetrieve;
use App\Jobs\ProcessFile;
use App\Models\Embedding\File;
use App\Models\FileQuestions;
use App\Models\Team;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

/**
 * Service for managing background jobs and queues
 * 
 * This service handles:
 * - Job dispatching and coordination
 * - Batch processing management
 * - File processing jobs
 * - Question answering jobs
 */
class JobService
{
    /**
     * Create a new JobService instance
     */
    public function __construct() {

    }

    /**
     * Queue a question for processing
     *
     * @param string $channel Broadcast channel
     * @param Team $team Team context
     * @param string $question Question to process
     * @param int $tries Number of retry attempts
     * @param string $verification_prompt Verification prompt
     */
    public function ask(string $channel, Team $team, string $question, int $tries, string $verification_prompt)
    {
        Bus::chain([
            new AskJob(
                channel: $channel,
                team: $team,
                question: $question,
                tries: $tries,
                verification_prompt: $verification_prompt
            )
        ])->onConnection('redis')->onQueue('ask')->dispatch();
    }

    /**
     * Queue a streaming question response
     *
     * @param string $channel Broadcast channel
     * @param Team $team Team context
     * @param string $question Question to process
     * @param int|null $max_tokens Maximum response length
     */
    public function askStream(string $channel, Team $team, string $question, int $max_tokens = null)
    {
        Bus::chain([
            new AskStreamJob(
                channel: $channel,
                team: $team,
                question: $question,
                max_tokens: $max_tokens
            )
        ])->onConnection('redis')->onQueue('ask')->dispatch();
    }

    /**
     * Queue batch publishing job
     *
     * @param Team $team Team context
     */
    public function batchPublish(Team $team) {
        Bus::chain([
            new BatchPublish(team: $team)
        ])->onConnection('redis')->onQueue('batch')->dispatch();
    }

    /**
     * Queue batch retrieval job
     *
     * @param Team $team Team context
     */
    public function batchRetrieve(Team $team) {
        Bus::chain([
            new BatchRetrieve(team: $team)
        ])->onConnection('redis')->onQueue('batch')->dispatch();
    }

    /**
     * Queue file embedding job
     *
     * @param File $file File to process
     */
    public function batchEmbedFile(File $file) {
        print("Batching file : " . $file->path . "\n");
        Bus::chain([
            new BatchEmbedFile(file: $file)
        ])->onConnection('redis')->onQueue('batch')->dispatch();
    }

    /**
     * Queue file import processing
     *
     * @param Team $team Team context
     * @param File $file File to import
     */
    public function importFIle(Team $team, File $file) {
        print("Processing file : " . $file->path . "\n");
        Bus::batch([
            new ProcessFile(team: $team, file: $file)
        ])->catch(function (Batch $batch, Throwable $e) use ($file) {
            print("Error processing file : " . $file->path . "\n");
            print($e->getMessage() . "\n");
            $file->importing = false;
            FileQuestions::where('file_id', $file->id)->delete();
            $file->save();
        })->onConnection('redis')->onQueue('batch')->dispatch();
    }

}
