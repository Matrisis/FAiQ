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

class FileEmbeddingService
{
    private Team $team;
    private string $model;

    public function __construct(Team $team, $model = "gpt-4o-mini")
    {
        $this->team = $team;
        $this->model = $model;
    }

    public function getLines(File $file, int $tries = 2, string $file_type = null) : array
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
                "imported" => true
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * @throws \Exception
     */
    private function getPDF(File $file, int $tries) : array
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile(Storage::path($file->path));
        $text = $pdf->getText();
        $split_text = str_split($text, 50000);
        $cleaned_text = [];
        $verify_service = new VerifyingService();
        foreach ($split_text as $sp) {
            $file_question = $verify_service->questionsCreate(text: $sp, file: $file);
            foreach (str_split($this->cleanText($file_question, $sp, $tries), 2000) as $scp) {
                $cleaned_text[] = [
                    "cleaned" => $scp,
                    "file_id" => $file->id
                ];
            }
        }
        return $cleaned_text;
    }

    private function cleanText(FileQuestions $file_question, string $text, int $tries)
    {
        $chat_service = new ChatService();
        $prompt = "You are a text cleaner assistant. You must remove from the text useless or incomprehensible data.
        Remove useless characters.
        You will return the text cleaned, concisely without removing any details or information.";
        $messages = [
            ['role' => 'system', 'content' => $prompt],
            ['role' => 'user', 'content' => "Here is the text : \"\"\"" . $text . "\"\"\""],
        ];
        $result = $chat_service->chat($messages)["answer"];
        $custom_prompt = "Check if the RESULT matches the goal of prompt CONTEXT for the ORIGINAL text.";
        foreach (range(1, $tries) as $try ) {
            if ($this->verification(file_question: $file_question, original: $text, result: $result, context: $prompt, prompt: $custom_prompt))
                return $result;
            $messages[1]["content"] = $messages[1]["content"]
                . "You must return a cleaned version of the original text.";
            $result = $chat_service->chat($messages, 1536)["answer"];
        }
        return $result;
    }

    private function verification(FileQuestions $file_question, string $original, string $result, string $context, string $prompt = null) : bool
    {
        $verify_service = new VerifyingService($this->model);
        return ($verify_service->verify(original: $original, result: $result, context: $context, prompt: $prompt)
            && $verify_service->questionsCheck($file_question, $result)
        );
    }

}
