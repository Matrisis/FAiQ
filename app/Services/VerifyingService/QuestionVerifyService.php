<?php

namespace App\Services\VerifyingService;

use App\Models\Embedding\File;
use App\Models\FileQuestions;
use App\Services\Chatting\ChatService;

class QuestionVerifyService
{
    private string $model;

    public function __construct(string $model = "gpt-4o-mini")
    {
        $this->model = $model;
    }

    public function create(string $text, File $file, string $custom_prompt = null, int $nb_questions = 10, int $max_tokens = null) : ?FileQuestions
    {
        try {
            $chat_service = new ChatService($this->model);
            $messages = [
                [
                    "role" => "system",
                    "content" => $custom_prompt ?? "
                    You are an assistant that generates question based on a text content.
                    The questions must check knowledge of the text subject and information.
                    The user will provide the text to generate questions from.
                 " . "Please provide" . $nb_questions . "questions.
                 Each question should be at least 5 words.
                 Only questions with high confidence should be returned.
                 Separate each question with a newline.
                 "
                ],
                [
                    "role" => "user",
                    "content" => $text
                ]
            ];
            $response = $chat_service->chat(messages: $messages, max_tokens: $max_tokens);
            $questions = json_encode(explode($response["answer"], "\n"));

            return FileQuestions::create([
                "file_id" => $file->id,
                "questions" => $questions
            ]);
        }
        catch (\Exception $e) {
            return null;
        }
    }

    public function check(FileQuestions $file_question, string $text) : bool
    {
        $questions = json_decode($file_question->questions);
        $chat_service = new ChatService($this->model);
        try {
            foreach ($questions as $question) {
                $messages = [
                    [
                        "role" => "system",
                        "content" => "
                    You goal is to check if the QUESTION give per the user can be answered with data from the TEXT.
                    Only answer with YES or NO.
                "
                    ],
                    [
                        "role" => "user",
                        "content" => "QUESTIONS :  \"\"\"" . $question . "\"\"\". TEXT : \"\"\"" . $text . "\"\"\""
                    ],
                ];

                $response = $chat_service->chat(messages: $messages);
                if ($response["answer"] !== "YES") {
                    return false;
                }
            }
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

}
