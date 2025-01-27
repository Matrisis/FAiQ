<?php

namespace App\Services;

use App\Events\Ask;
use App\Models\Answer;
use App\Models\Team;

/**
 * Service for managing answers and question-answering functionality
 * 
 * This service handles the creation, retrieval, and management of answers to user questions.
 * It integrates with the embedding service for semantic search and broadcasts answers via events.
 */
class AnswerService
{
    private Team $team;

    /**
     * Create a new AnswerService instance
     *
     * @param Team $team The team context for this service
     */
    public function __construct(Team $team) {
        $this->team = $team;
    }

    /**
     * Create a new answer record
     *
     * @param string $question The user's question
     * @param string $answer The generated answer
     * @param string $channel The broadcast channel
     * @param array $data Additional metadata
     * @param int $votes Initial vote count
     * @return Answer The created answer record
     */
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

    /**
     * Retrieve previous answers based on semantic similarity
     *
     * @param string $question The question to find similar answers for
     * @param int $limit Maximum number of answers to retrieve
     * @param string $column Vector column to compare ('question_vector' or 'answer_vector')
     * @param string $neighbor_distance Maximum distance for similarity matching
     * @return \Illuminate\Support\Collection Collection of similar answers
     */
    private function retrievePreviousAnswer(string $question, int $limit = 1,
                                            string $column = "question_vector", string $neighbor_distance = "0.15") {
        $embedding_service = new EmbeddingService($this->team);
        return $embedding_service->retrieve(
            text: $question, 
            limit: $limit, 
            model: Answer::class, 
            column: $column, 
            neighbor_distance: $neighbor_distance
        );
    }

    /**
     * Split and broadcast an answer in chunks
     *
     * @param array $data Answer data containing question and answer
     * @param string $channel Broadcast channel
     */
    private function splitBroadcast(array $data, string $channel)
    {
        if(strlen($data["answer"]) > 100) {
            foreach (str_split($data["answer"], 100) as $chunk) {
                broadcast(new Ask(answer: [
                    'question' => $data["question"],
                    'answer' => mb_convert_encoding($chunk, "UTF-8", 'UTF-8'),
                ], channel: $channel));
            }
        } else {
            broadcast(new Ask(answer: $data, channel: $channel));
        }
        broadcast(new Ask(answer: ["id" => $data["answer_id"]], channel: $channel));
    }

    /**
     * Public method to retrieve similar answers
     *
     * @param string $question Question to find similar answers for
     * @param int $limit Maximum number of answers to retrieve
     * @param string $column Vector column to compare
     * @param string $neighbor_distance Maximum distance for similarity matching
     * @return \Illuminate\Support\Collection Collection of similar answers
     */
    public function retrieve(string $question, int $limit = 1,
                             string $column = "question_vector", string $neighbor_distance = "0.15")
    {
        return $this->retrievePreviousAnswer(
            question: $question, 
            limit: $limit,
            column: $column, 
            neighbor_distance: $neighbor_distance
        );
    }

    /**
     * Process a question and provide an answer
     *
     * This method either retrieves a similar previous answer or generates a new one.
     * It also handles logging and broadcasting of the answer.
     *
     * @param string $channel Broadcast channel
     * @param string $question User's question
     * @param mixed $request HTTP request object
     */
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
            try {
                RequestLoggerService::create($this->team, $question, $request->ip(), false);
            } catch (\Exception $e) {
                print($e->getMessage() . "\n");
            }
        } else {
            $job_service->askStream(
                channel: $channel,
                team: $this->team,
                question: $question
            );
            try {
                RequestLoggerService::create($this->team, $question, $request->ip(), true);
            } catch (\Exception $e) {
                print($e->getMessage() . "\n");
            }
        }
    }
}
