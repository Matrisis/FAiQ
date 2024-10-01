<?php

namespace App\Services;

use App\Jobs\AskJob;
use App\Jobs\AskStreamJob;
use App\Jobs\Batch\BatchEmbedFile;
use App\Jobs\Batch\BatchPublish;
use App\Jobs\Batch\BatchRetrieve;
use App\Jobs\ProcessFile;
use App\Models\Embedding\File;
use App\Models\FileQuestions;
use App\Models\Team;
use Illuminate\Support\Facades\Bus;

class JobService
{

    public function __construct() {

    }

    public function ask(string $channel, Team $team, string $question, int $tries, string $verification_prompt)
    {
        Bus::chain([
            new AskJob(
                channel: $channel,
                team: $team,
                question: $question,
                tries: $tries,
                verification_prompt: $verification_prompt
            )
        ])->onConnection('redis')->onQueue('ask')->dispatch();
    }

    public function askStream(string $channel, Team $team, string $question, int $max_tokens = null)
    {
        Bus::chain([
            new AskStreamJob(
                channel: $channel,
                team: $team,
                question: $question,
                max_tokens: $max_tokens
            )
        ])->onConnection('redis')->onQueue('ask')->dispatch();
    }

    public function batchPublish(Team $team) {
        Bus::chain([
            new BatchPublish(team: $team)
        ])->onConnection('redis')->onQueue('batch')->dispatch();
    }

    public function batchRetrieve(Team $team) {
        Bus::chain([
            new BatchRetrieve(team: $team)
        ])->onConnection('redis')->onQueue('batch')->dispatch();
    }

    public function batchEmbedFile(File $file) {
        print("Batching file : " . $file->path . "\n");
        Bus::chain([
            new BatchEmbedFile(file: $file)
        ])->onConnection('redis')->onQueue('batch')->dispatch();
    }

    public function importFIle(Team $team, File $file) {
        print("Processing file : " . $file->path . "\n");
        Bus::batch([
            new ProcessFile(team: $team, file: $file)
        ])->catch(function (Batch $batch, Throwable $e) use ($file) {
            $file->importing = false;
            $file->save();
            FileQuestions::where('file_id', $file->id)->delete();
        })->onConnection('redis')->onQueue('batch')->dispatch();
    }

}
