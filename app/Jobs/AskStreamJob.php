<?php

namespace App\Jobs;

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
        $response = $this->chatting_service->steam(
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
        Answer::create($data);
    }
}
