<?php

namespace App\Services;

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

}
