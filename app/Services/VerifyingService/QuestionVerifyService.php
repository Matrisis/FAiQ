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
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            $messages = [
                [
                    "role" => "system",
                    "content" => $custom_prompt ? : "
                    Your goal is to generate simple questions based on a text.
                    The questions must be answerable using a cleaned version of the text.
                    The questions must be answerable using a different version of the text.
                    The user will provide the text to generate questions from.
                 " . "Please provide" . 10 . "questions.
                 Each question should be at least 5 words.
                 Only the high confidence questions should be returned.
                 Separate each question with a comma.
                 "
                ],
                [
                    "role" => "user",
                    "content" => "Provide questions for this text : " . $text
                ]
            ];
            $response = $chat_service->chat(messages: $messages, max_tokens: $max_tokens);
            print("Questions : " . $response["answer"] . "\n");
            return FileQuestions::create([
                "file_id" => $file->id,
                "questions" => $response["answer"]
            ]);
        }
        catch (\Exception $e) {
            print($e->getMessage() . "\n");
            return null;
        }
    }

    public function check(FileQuestions $file_question, string $text) : bool
    {
        $questions = json_decode($file_question->questions);
        $chat_service = new ChatService($this->model);
        try {
                $messages = [
                    [
                        "role" => "system",
                        "content" => "
                            You goal is to check if the CONTEXT has data to answer the QUESTIONS.
                            Only answer with YES or NO.
                        "
                    ],
                    [
                        "role" => "user",
                        "content" => "QUESTIONS :  \"\"\"" . $questions . "\"\"\". TEXT : \"\"\"" . $text . "\"\"\""
                    ],
                ];

                $response = $chat_service->chat(messages: $messages);
                print("QUESTIONS VERIFICATION : " . $response["answer"] . "\n");
                return $response["answer"] === "YES";
        } catch (\Exception $e) {
            print($e->getMessage() . "\n");
            return false;
        }
    }

}
