<?php

namespace App\Services\VerifyingService;

use App\Models\Embedding\File;
use App\Models\FileQuestions;
use App\Services\Chatting\ChatService;

/**
 * Service for managing question generation and verification
 * 
 * This service handles:
 * - Generating questions from content
 * - Verifying question quality
 * - Validating question answerability
 */
class QuestionVerifyService
{
    private string $model;

    /**
     * Create a new QuestionVerifyService instance
     *
     * @param string $model AI model to use
     */
    public function __construct(string $model = "gpt-4o-mini")
    {
        $this->model = $model;
    }

    /**
     * Generate questions from text
     *
     * @param string $text Source text
     * @param File $file Associated file
     * @param string|null $custom_prompt Custom generation prompt
     * @param int $nb_questions Number of questions to generate
     * @param int|null $max_tokens Maximum response length
     * @return FileQuestions|null Generated questions
     */
    public function create(string $text, File $file, string $custom_prompt = null, int $nb_questions = 3, int $max_tokens = null) : ?FileQuestions
    {
        try {
            $chat_service = new ChatService($this->model);
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            $messages = [
                [
                    "role" => "system",
                    "content" => $custom_prompt ? : "
                        Your goal is to generate " . $nb_questions . " simple questions based on the text.
                        All the questions must be easily answerable with data from the text only.
                        Each question should be at least 5 words.
                        Separate each question with a line break.
                        The user will provide the text to generate questions from.
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

    /**
     * Verify questions against text
     *
     * @param FileQuestions $file_question Questions to verify
     * @param string $text Content to verify against
     * @return string Verification result
     */
    public function check(FileQuestions $file_question, string $text) : string
    {
        $questions = $file_question->questions;
        $chat_service = new ChatService($this->model);
        try {
                $messages = [
                    [
                        "role" => "system",
                        "content" => "
                            You goal is to check if the QUESTIONS can be answered using data in CONTEXT.
                            Only answer with YES if the questions can be answered, otherwise answer with the reason why.
                        "
                    ],
                    [
                        "role" => "user",
                        "content" => "QUESTIONS :  \"\"\"" . $questions . "\"\"\". TEXT : \"\"\"" . $text . "\"\"\""
                    ],
                ];

                $response = $chat_service->chat(messages: $messages);
                print("QUESTIONS VERIFICATION : " . $response["answer"] . "\n");
                return $response["answer"];
        } catch (\Exception $e) {
            print($e->getMessage() . "\n");
            return false;
        }
    }

}
