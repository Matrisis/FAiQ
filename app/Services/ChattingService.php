<?php

namespace App\Services;

use App\Models\Team;
use App\Services\Chatting\AskService;
use App\Services\Chatting\ChatService;

/**
 * Service for managing AI chat interactions
 * 
 * This service coordinates chat interactions with AI models, handling both
 * direct chat and question-answering with verification capabilities.
 */
class ChattingService
{
    private string $model;

    /**
     * Create a new ChattingService instance
     *
     * @param string $model AI model to use for chat
     */
    public function __construct(string $model = "gpt-4o-mini") {
        $this->model = $model;
    }

    /**
     * Process a chat interaction
     *
     * @param array $messages Array of chat messages
     * @param int|null $max_tokens Maximum response length
     * @return array|null Chat response data
     */
    public function chat(array $messages, int $max_tokens = null) : ?array {
        $chat_service = new ChatService($this->model);
        return $chat_service->chat($messages, $max_tokens);
    }

    /**
     * Process a question with verification
     *
     * @param Team $team Team context
     * @param string $question User's question
     * @param int $tries Number of retry attempts
     * @param string|null $verification_prompt Custom verification prompt
     * @param int|null $max_tokens Maximum response length
     * @return array|null Answer data
     */
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

    /**
     * Stream a chat response
     *
     * @param string $channel Broadcast channel
     * @param Team $team Team context
     * @param string $question User's question
     * @param int|null $max_tokens Maximum response length
     * @return array Response data
     */
    public function stream(string $channel, Team $team, string $question, int $max_tokens = null) : array
    {
        print("Asking stream: " . $question . "\n");
        $ask_service = new AskService(team: $team, model: $this->model);
        return $ask_service->stream($channel, $question, $max_tokens);
    }

    /**
     * Get default verification prompt
     */
    private function getVerificationPrompt() : string
    {
        return "Check if the RESULT answers the QUESTION, given the CONTEXT.";
    }

    /**
     * Verify answer accuracy
     */
    private function verification(string $original, string $result, string $context, string $prompt = null) : bool
    {
        $verify_service = new VerifyingService($this->model);
        return $verify_service->verify(original: $original, result: $result, context: $context, prompt: $prompt);
    }
}
