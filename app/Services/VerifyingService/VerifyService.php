<?php

namespace App\Services\VerifyingService;

use App\Services\Chatting\ChatService;

/**
 * Service for verifying content accuracy
 * 
 * This service handles:
 * - Content accuracy verification
 * - Context-based validation
 * - Result quality checks
 */
class VerifyService
{
    private string $model;

    /**
     * Create a new VerifyService instance
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
        print("ORIGINAL VERIFICATION : " . $chat_service->chat($messages)["answer"] . "\n");
        return $chat_service->chat($messages)["answer"] === "\"\"\"YES\"\"\"";
    }

    /**
     * Get default verification prompt
     *
     * @return string Default prompt
     */
    private function getPrompt() : string
    {
        return "Your goal is to check if the ORIGINAL matches the RESULT, given the CONTEXT.";
    }
}
