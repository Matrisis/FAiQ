<?php

namespace App\Jobs;

use App\Events\Ask;
use App\Models\Answer;
use App\Models\Team;
use App\Services\ChattingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AskStreamJob implements ShouldQueue
{
    use Queueable;

    private ChattingService $chatting_service;
    private string $channel;
    private Team $team;
    private string $question;
    private int|null $max_tokens;

    /**
     * Create a new job instance.
     */
    public function __construct(string $channel, Team $team, string $question, int $max_tokens = null)
    {
        $this->chatting_service = new ChattingService();
        $this->channel = $channel;
        $this->team = $team;
        $this->question = $question;
        $this->max_tokens = $max_tokens;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $previous_question = Answer::where('question', $this->question)->first();
        if($previous_question) {
            $this->splitBroadcast($previous_question->only(['id', 'question', 'answer']));
        } else {
            $response = $this->chatting_service->stream(
                channel: $this->channel,
                team: $this->team,
                question: $this->question,
                max_tokens: $this->max_tokens
            );

            $data = [
                'question' => $this->question,
                'answer' => mb_convert_encoding($response["answer"], "UTF-8", 'UTF-8'),
                'data' => json_encode($response),
                'channel' => $this->channel,
                'team_id' => $this->team->id
            ];
            $answer = Answer::create($data);
            broadcast(new Ask(answer: $answer->only(['id']), channel: $this->channel));
        }
    }

    private function splitBroadcast(array $data) {
        if(strlen($data["answer"]) > 100) {
            foreach (str_split($data["answer"], 100) as $chunk) {
                print "Chunk : " . $chunk . "\n";
                broadcast(new Ask(answer: [
                    'question' => $this->question,
                    'answer' => mb_convert_encoding($chunk, "UTF-8", 'UTF-8'),
                ], channel: $this->channel));
            }
        } else
            broadcast(new Ask(answer: $data, channel: $this->channel));
    }
}
