<?php

namespace App\Services;

use App\Models\Embedding\File;
use App\Models\FileQuestions;
use App\Services\VerifyingService\QuestionVerifyService;
use App\Services\VerifyingService\VerifyService;

class VerifyingService
{

    private string $model;

    public function __construct(string $model = "gpt-4o-mini") {
        $this->model = $model;
    }

    public function verify(string $original, string $result, string $context, string $prompt = null) : bool {
        $verify_service = new VerifyService($this->model);
        return $verify_service->verify($original, $result, $context, $prompt);
    }

    public function questionsCreate(string $text, File $file, string $custom_prompt = null, int $nb_questions = 10, int $max_tokens = null) : ?FileQuestions
    {
        $question_service = new QuestionVerifyService($this->model);
        return $question_service->create($text, $file, $custom_prompt, $nb_questions, $max_tokens);
    }

    public function questionsCheck(FileQuestions $file_question) : bool
    {
        $question_service = new QuestionVerifyService($this->model);
        return $question_service->check($file_question);
    }

}
