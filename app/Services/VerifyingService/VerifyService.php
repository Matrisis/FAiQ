<?php

namespace App\Services\VerifyingService;

use App\Services\Chatting\ChatService;

class VerifyService
{

    private string $model;

    public function __construct(string $model = "gpt-4o-mini") {
        $this->model = $model;
    }

    public function verify(string $original, string $result, string $context, string $prompt = null) : bool
    {
        $custom_prompt = $prompt ?: $this->getPrompt();
        $messages = [
            ['role' => 'system', 'content' => $custom_prompt
                . " If yes, say : \"\"\"YES\"\"\", if no, say : \"\"\"NO\"\"\"."],
            ['role' => 'user', 'content' =>
                "ORIGINAL : \"\"\"" . $original
                . "\"\"\" CONTEXT : \"\"\"" . $context . "\"\"\""
                . " RESULT : \"\"\"" . $result . "\"\"\""
            ],
        ];
        $chat_service = new ChatService($this->model);
        return $chat_service->chat($messages)["answer"] === "\"\"\"YES\"\"\"";
    }

    private function getPrompt() : string
    {
        return "Your goal is to check if the ORIGINAL matches the RESULT, given the CONTEXT.";
    }

}
