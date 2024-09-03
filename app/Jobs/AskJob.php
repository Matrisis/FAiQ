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

class AskJob implements ShouldQueue
{
    use Queueable;

    private ChattingService $chatting_service;
    private string $channel;
    private Team $team;
    private string $question;
    private int $tries;
    private string $verification_prompt;


    /**
     * Create a new job instance.
     */
    public function __construct(string $channel, Team $team, string $question, int $tries, string $verification_prompt)
    {
        $this->chatting_service = new ChattingService();
        $this->channel = $channel;
        $this->team = $team;
        $this->question = $question;
        $this->tries = $tries;
        $this->verification_prompt = $verification_prompt;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = $this->chatting_service->ask(
            team: $this->team,
            question: $this->question,
            tries: $this->tries,
            verification_prompt: $this->verification_prompt
        );
        $data = [
            'question' => $this->question,
            'answer' => $response["answer"],
            'data' => json_encode($response),
        ];
        $data["channel"] = $this->channel;
        $data["team_id"] = $this->team->id;
        print("Broadcasting ask event...\n");
        broadcast(new Ask(answer: $data, channel: $this->channel));
        Answer::create($data);
    }
}
