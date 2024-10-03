<?php

namespace App\Services\Chatting;

use App\Events\Ask;
use App\Models\Answer;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use Psy\Readline\Hoa\Event;

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
            'messages' => mb_convert_encoding($messages, 'UTF-8', 'UTF-8')
        ];
        if ($max_tokens) $data['max_tokens'] = $max_tokens;
        try {
            $response = OpenAI::chat()->create($data);
            return [
                "response" => $response,
                "answer" => $response['choices'][0]['message']['content']
            ];
        } catch (\Exception $e) {
            print("ERROR CHAT". PHP_EOL);
            print($e->getMessage());
            return null;
        }
    }

    public function steam(string $channel, $messages, int $max_tokens = null) : ?array {
        $data = [
            'model' => $this->model,
            'messages' => $messages
        ];
        if ($max_tokens) $data['max_tokens'] = $max_tokens;
        try {
            $stream = OpenAI::chat()->createStreamed($data);
            $response_answer = "";
            $tmp_response = "";
            $iteration = false;
            foreach($stream as $response) {
                $answer = $response->choices[0]->toArray()["delta"];
                if (isset($answer["content"])) {
                    $answer = $answer["content"];
                    if (strlen($tmp_response) < 100) {
                        $tmp_response .= mb_convert_encoding($answer, "UTF-8", 'UTF-8');
                        $iteration = true;
                    }
                    else {
                        $response_answer .= $tmp_response;
                        broadcast(new Ask(answer: [
                            'answer' => $tmp_response
                        ], channel: $channel));
                        $tmp_response = "";
                        $iteration = false;
                    }
                    /*

                    if (strlen($answer) > 50) {
                        foreach (str_split($answer, 50) as $chunk) {
                            $chunk_answer = mb_convert_encoding($chunk, "UTF-8", 'UTF-8');
                            $response_answer .= $chunk_answer;
                            broadcast(new Ask(answer: [
                                'answer' => $chunk_answer,
                            ], channel: $channel));
                        }
                    }
                    else {
                        $chunk_answer = mb_convert_encoding($answer, "UTF-8", 'UTF-8');
                        $response_answer .= $chunk_answer;
                        broadcast(new Ask(answer: [
                            'answer' => $chunk_answer,
                        ], channel: $channel));
                    }

                    */
                }
            }
            if ($iteration) {
                $response_answer .= $tmp_response;
                broadcast(new Ask(answer: [
                    'answer' => $tmp_response
                ], channel: $channel));
            }
            return [
                "response" => $stream,
                "answer" => $response_answer
            ];
        } catch (\Exception $e) {
            print($e->getMessage());
            return null;
        }
    }

}
