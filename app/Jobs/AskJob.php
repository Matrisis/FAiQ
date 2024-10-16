<?php

namespace App\Jobs;

use App\Events\Ask;
use App\Models\Answer;
use App\Models\Team;
use App\Services\AnswerService;
use App\Services\ChattingService;
use App\Services\EmbeddingService;
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
        $previous_question = Answer::where('question', $this->question)->first();
        if($previous_question)
            $this->splitBroadcast($previous_question->only(['question', 'answer']));

        $response = $this->chatting_service->ask(
            team: $this->team,
            question: $this->question,
            tries: $this->tries,
            verification_prompt: $this->verification_prompt
        );
        $data = [
            'question' => $this->question,
            'answer' => mb_convert_encoding($response["answer"], "UTF-8", 'UTF-8'),
        ];
        print("Broadcasting ask event...\n");

        $this->splitBroadcast($data);
        $answer_service = new AnswerService($this->team);
        $answer_service->create(
            question: $this->question,
            answer: $data["answer"],
            channel: $this->channel,
            data: $response);
    }

    private function splitBroadcast(array $data)
    {
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
