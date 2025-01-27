<?php

namespace App\Services;

use App\Models\Embedding\File;
use App\Models\FileQuestions;
use App\Services\VerifyingService\QuestionVerifyService;
use App\Services\VerifyingService\VerifyService;

/**
 * Service for verifying content and answer accuracy
 * 
 * This service coordinates verification operations including:
 * - Content accuracy verification
 * - Question generation and validation
 * - Answer quality checks
 */
class VerifyingService
{
    private string $model;

    /**
     * Create a new VerifyingService instance
     *
     * @param string $model AI model to use
     */
    public function __construct(string $model = "gpt-4o-mini") {
        $this->model = $model;
    }

    /**
     * Verify content accuracy
     *
     * @param string $original Original content
     * @param string $result Generated result
     * @param string $context Context information
     * @param string|null $prompt Custom verification prompt
     * @return bool Verification result
     */
    public function verify(string $original, string $result, string $context, string $prompt = null) : bool {
        print("Checking text...\n");
        $verify_service = new VerifyService($this->model);
        return $verify_service->verify($original, $result, $context, $prompt);
    }

    /**
     * Generate questions for content
     *
     * @param string $text Source text
     * @param File $file Associated file
     * @param string|null $custom_prompt Custom generation prompt
     * @param int $nb_questions Number of questions to generate
     * @param int|null $max_tokens Maximum response length
     * @return FileQuestions|null Generated questions
     */
    public function questionsCreate(string $text, File $file, string $custom_prompt = null, int $nb_questions = 3, int $max_tokens = null) : ?FileQuestions
    {
        $question_service = new QuestionVerifyService($this->model);
        return $question_service->create($text, $file, $custom_prompt, $nb_questions, $max_tokens);
    }

    /**
     * Verify questions against content
     *
     * @param FileQuestions $file_question Questions to verify
     * @param string $text Content to verify against
     * @return string Verification result
     */
    public function questionsCheck(FileQuestions $file_question, string $text) : string
    {
        print("Checking question...\n");
        $question_service = new QuestionVerifyService($this->model);
        return $question_service->check($file_question, $text);
    }
}
