<?php

namespace App\Services\Chatting;

use App\Models\Team;
use App\Models\TeamPrompt;
use App\Services\EmbeddingService;
use Illuminate\Support\Facades\Event;

class AskService
{

    private string $model;
    private Team $team;

    public function __construct(Team $team, string $model = "gpt-4o-mini") {
        $this->model = $model;
        $this->team = $team;
    }


    public function ask(string $question, int $max_tokens = null): array
    {
        $custom_prompt = $this->retrieveCustomPrompt();
        $embedding = $this->retrieveEmbedding($custom_prompt);
        $clean_embedding = $embedding->pluck("content")->join(", ");
        $messages = [
            ['role' => 'system', 'content' =>
                $this->createSystemPrompt(custom_prompt: $custom_prompt, embedding: $clean_embedding)],
            ['role' => 'user', 'content' => $question],
        ];
        $chat_service = new ChatService();
        $chat_response = $chat_service->chat(messages: $messages, max_tokens: $max_tokens);
        return [
            "response" => $chat_response["response"],
            "answer" => $chat_response["answer"],
            "context" => $embedding->toArray(),
            "clean_context" => $clean_embedding,
            "prompt" => $custom_prompt
        ];
    }

    public function stream(string $channel, string $question, int $max_tokens = null) : array
    {
        $custom_prompt = $this->retrieveCustomPrompt();
        $embedding = $this->retrieveEmbedding($custom_prompt);
        $clean_embedding = $embedding->pluck("content")->join(", ");
        $messages = [
            ['role' => 'system', 'content' =>
                $this->createSystemPrompt(custom_prompt: $custom_prompt, embedding: $clean_embedding)],
            ['role' => 'user', 'content' => $question],
        ];
        $chat_service = new ChatService();
        $response = $chat_service->steam($channel, messages: $messages, max_tokens: $max_tokens);
        return  [
            "response" => $response["response"],
            "answer" => $response["answer"],
            "context" => $embedding->toArray(),
            "clean_context" => $clean_embedding,
            "prompt" => $custom_prompt
        ];
    }

    private function createSystemPrompt(string $custom_prompt, string $embedding) : ?string
    {
        return $custom_prompt
            . "Context : \"\"\""
            . $embedding . "\"\"\"";
    }

    private function retrieveEmbedding(string $text)
    {
        $embedding_service = new EmbeddingService(team: $this->team);
        return $embedding_service->retrieve(text: $text, limit: 5);
    }

    private function retrieveCustomPrompt() : string
    {
        $team_prompt = TeamPrompt::where('team_id', $this->team->id)->first();
        $prompt =  $team_prompt?->prompt ?:
            "You are a Support assistant for " . $this->team->name . " team. Your name is FAQ Assistant
            You are helpful, very concise, and friendly.
            You must answer users questions like an FAQ, based on the following context.";
        return $prompt . "Format as markdown. Only return the answer.
        If you cannot define a good and reliable answer to the question, say no matter the language exactly : I don't know.";
    }

}
