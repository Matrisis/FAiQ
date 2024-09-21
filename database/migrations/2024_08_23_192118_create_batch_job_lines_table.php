<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('batch_job_lines', function (Blueprint $table) {
            $table->id();
            $table->string('custom_id');
            $table->enum("type", ["chatting", "embedding"]);
            $table->json('body');
            $table->text("original_text");
            $table->foreignId("batch_job_id")->constrained("batch_jobs")->cascadeOnDelete();
            $table->foreignId("file_id")->nullable()->default(null)
                ->constrained()->cascadeOnDelete();
            $table->date("finished_at")->nullable();
            $table->date("batch_finished_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_job_lines');
    }
};
