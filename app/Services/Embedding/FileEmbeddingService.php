<?php

namespace App\Services\Embedding;

use App\Enum\FileTypeEnum;
use App\Models\Embedding\Embedding;
use App\Models\Embedding\File;
use App\Models\FileQuestions;
use App\Models\Team;
use App\Services\Chatting\ChatService;
use App\Services\VerifyingService;
use http\Encoding\Stream\Inflate;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;

class FileEmbeddingService
{
    private Team $team;
    private string $model;

    public function __construct(Team $team, $model = "gpt-4o-mini")
    {
        $this->team = $team;
        $this->model = $model;
    }

    public function getLines(File $file, int $tries = 5, string $file_type = null) : array
    {
        $file_path = Storage::path($file->path);
        $file_type = FileTypeEnum::from($file_type ?: mime_content_type($file_path))->name;
        $function = "get{$file_type}";
        return $this->$function($file, $tries);
    }

    public function import(File $file, array $content): bool
    {
        try {
            foreach ($content as $part) {
                Embedding::insertGetId([
                    'type' => 'file',
                    'content' => $part["original"],
                    'embedding' => $part["embedded"],
                    'file_id' => $file->id,
                    'team_id' => $this->team->id
                ]);
            }
            $file->update([
                "imported" => true,
                "importing" => false
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    private function fileUpload(File $file, int $tries) : array {
        $response = OpenAI::files()->upload([
            'purpose' => 'search',
            'file' =>  fopen('$file->pathmy-file.jsonl', 'r'),
        ]);

    }

    /**
     * @throws \Exception
     */
    private function getPDF(File $file, int $tries) : array
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile(Storage::path($file->path));
        $text = mb_convert_encoding($pdf->getText(), 'UTF-8', 'UTF-8');
        $split_text = str_split($text, 50000);
        $cleaned_text = [];
        $verify_service = new VerifyingService();
        foreach ($split_text as $sp) {
            $file_question = $verify_service->questionsCreate(text: $sp, file: $file);
            $cleaned_text[] = [
                "cleaned" => $this->cleanText($file_question, $sp, $tries),
                "file_id" => $file->id
            ];
            sleep(30);
        }
        return $cleaned_text;
    }

    private function cleanText(FileQuestions $file_question, string $text, int $tries)
    {
        print("Cleaning text..." . PHP_EOL);
        $chat_service = new ChatService();
        $prompt = "You are a text cleaner assistant.
        You must remove from the text useless characters and duplicates.
        You will return the text cleaned, concisely without removing any details or information.";
        $messages = [
            ['role' => 'system', 'content' => $prompt],
            ['role' => 'user', 'content' => "Here is the text : \"\"\"" . $text . "\"\"\""],
        ];
        $result = $chat_service->chat($messages)["answer"];
        $custom_prompt = "Check if the RESULT matches the goal of prompt CONTEXT for the ORIGINAL text.";
        foreach (range(1, $tries) as $try ) {
            $verify_service = new VerifyingService($this->model);
            $text_verify = $verify_service->verify(original: $text, result: $result, context: $prompt, prompt: $custom_prompt);
            $question_verification = $verify_service->questionsCheck($file_question, $result);
            if ($text_verify && $question_verification === "YES")
                return $result;
            $messages[1]["content"] = $messages[1]["content"]
                . "Please provide a better cleaned version of the text. Use only data form original text.
                This new version must fix this issues :" . $question_verification;
            $result = $chat_service->chat($messages)["answer"];
        }
        return $result;
    }

}
