<?php

use App\Models\Embedding\File;
use App\Models\Team;
use App\Services\JobService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Emebdding hourly
Schedule::call(function () {

    foreach (File::doesntHave('embeddings')->get()->pluck("team")->unique() as $team) {
        (new JobService())->batchPublish(Team::find($team->id));
        (new JobService())->batchRetrieve(Team::find($team->id));
    }

})->hourly();
