<?php

namespace App\Services;

use App\Models\Team;
use App\Services\Chatting\AskService;
use App\Services\Chatting\ChatService;

class ChattingService
{

    private string $model;

    public function __construct(string $model = "gpt-4o-mini") {
        $this->model = $model;
    }

    public function chat(array $messages, int $max_tokens = null) : ?array {
        $chat_service = new ChatService($this->model);
        return $chat_service->chat($messages, $max_tokens);
    }

    public function ask(Team $team, string $question, int $tries = 1, string $verification_prompt = null, int $max_tokens = null): ?array
    {
        print("Asking: " . $question . "\n");
        $ask_service = new AskService(team: $team, model: $this->model);
        $answer = $ask_service->ask($question, $max_tokens);
        while ($tries > 1) {
            if ($this->verification($question, $answer["answer"], $answer["clean_context"], $this->getVerificationPrompt())) {
                return $answer;
            } else {
                print("Retrying asking question: " . $question . "\n");
                $question = $question . ". " . $verification_prompt;
                $answer = $ask_service->ask($question, $max_tokens);
            }
            $tries--;
        }
        return $answer;
    }

    private function getVerificationPrompt() : string
    {
        return "Check if the RESULT answers the QUESTION, given the CONTEXT.";
    }

    private function verification(string $original, string $result, string $context, string $prompt = null) : bool
    {
        $verify_service = new VerifyingService($this->model);
        return $verify_service->verify(original: $original, result: $result, context: $context, prompt: $prompt);
    }

}
