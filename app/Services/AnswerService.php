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

    public function create(string $question, string $answer, string $channel, array $data, int $votes = 0) {
        $embedding_service = new EmbeddingService($this->team);
        return Answer::create([
            'team_id' => $this->team->id,
            'question' => $question,
            'answer' => $answer,
            'votes' => $votes,
            'channel' => $channel,
            'data' => json_encode($data),
            'question_vector' => $embedding_service->embed($question),
            'answer_vector' => $embedding_service->embed($answer),
        ]);
    }

    private function retrievePreviousAnswer(string $question, int $limit = 1,
                                            string $column = "question_vector", string $neighbor_distance = "0.15") {
        $embedding_service = new EmbeddingService($this->team);
        return $embedding_service->retrieve(
            text: $question, limit: $limit, model:
            Answer::class, column: $column, neighbor_distance: $neighbor_distance);
    }

    private function splitBroadcast(array $data, string $channel)
    {
        if(strlen($data["answer"]) > 100) {
            foreach (str_split($data["answer"], 100) as $chunk) {
                print "Chunk : " . $chunk . "\n";
                broadcast(new Ask(answer: [
                    'question' => $data["question"],
                    'answer' => mb_convert_encoding($chunk, "UTF-8", 'UTF-8'),
                ], channel: $channel));
            }
        } else
            broadcast(new Ask(answer: $data, channel: $channel));
        broadcast(new Ask(answer: ["id" => $data["answer_id"]], channel: $channel));
    }

    public function retrieve(string $question, int $limit = 1,
                             string $column = "question_vector", string $neighbor_distance = "0.15")
    {
        return $this->retrievePreviousAnswer(question: $question, limit: $limit,
            column: $column, neighbor_distance: $neighbor_distance);
    }

    public function ask(string $channel, string $question, $request) {
        $job_service = new JobService();
        $previous_answers = $this->retrievePreviousAnswer($question)->filter(function ($answer) {
            return $answer->answer != "I don't know" && $answer->answer != "'I don't know'";
        });
        if($previous_answers->first()) {
            $this->splitBroadcast([
                'question' =>  mb_convert_encoding($question,  "UTF-8", 'UTF-8'),
                'answer' => mb_convert_encoding($previous_answers->first()->answer, "UTF-8", 'UTF-8'),
                'answer_id' => $previous_answers->first()->id
            ], $channel);
            RequestLoggerService::create($this->team, $question, $request->ip, false);
        } else {
            $job_service->askStream(
                channel: $channel,
                team: $this->team,
                question: $question
            );
            RequestLoggerService::create($this->team, $question, $request->ip(), true);
        }
    }

}
