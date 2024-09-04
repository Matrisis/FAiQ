<?php

namespace App\Services\VerifyingService;

class QuestionVerifyService
{

    private string $model;

    public function __construct(string $model = "gpt-4o-mini")
    {
        $this->model = $model;
    }

    public function create()
    {

    }

    public function check() : bool
    {

    }

}
