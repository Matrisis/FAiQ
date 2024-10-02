<?php

namespace App\Services;

use App\Events\Ask;
use App\Models\Answer;
use App\Models\Team;

class AnswerService
{

    private Team $team;

    public function __construct(Team $team) {
        $this->team = $team;
    }

    private function retrievePreviousAnswers(string $question) {
        $embedding_service = new EmbeddingService($this->team);
        return $embedding_service->retrieve(
            text: $question, limit: 1, model: Answer::class, column: "question", neighbor_distance: "0.98");
    }

    private function splitBroadcast(array $data, string $channel)
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

    public function ask(string $channel, string $question) {
        $job_service = new JobService();
        $previous_answers = $this->retrievePreviousAnswers($question);
        if($previous_answers->first()) {
            $this->splitBroadcast([
                'question' => $question, 'answer' => mb_convert_encoding($previous_answers->first()->answer, "UTF-8", 'UTF-8'),
            ], $previous_answers->first()->channel ?? $previous_answers->first(), $channel);
        } else {
            $job_service->askStream(
                channel: $channel,
                team: $this->team,
                question: $question
            );
        }
    }

}
