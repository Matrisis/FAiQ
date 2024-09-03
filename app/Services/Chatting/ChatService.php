<?php

namespace App\Services\Chatting;

use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class ChatService
{

    /*
     * Chat Service
     * TODO :
     * - Allow to send and retrieve chat from OpenAI
     * - Must be Queueable
     */

    private string $model;

    public function __construct(string $model = "gpt-4o-mini")
    {
        $this->model = $model;
    }

    public function chat(array $messages, int $max_tokens = null) : ?array
    {
        $data = [
            'model' => $this->model,
            'messages' => $messages
        ];
        if ($max_tokens) $data['max_tokens'] = $max_tokens;
        try {
            $response = OpenAI::chat()->create($data);
            return [
                "response" => $response,
                "answer" => $response['choices'][0]['message']['content']
            ];
        } catch (\Exception $e) {
            print($this->model);
            print($e->getMessage());
            return null;
        }
    }

}
