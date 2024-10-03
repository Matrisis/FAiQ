<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->string("channel");
            $table->string("question");
            $table->vector("question_vector", 1536);
            $table->text("data");
            $table->text("answer");
            $table->vector("answer_vector", 1536);
            $table->enum("type", ["direct", "stream"])->default("direct");
            $table->integer("votes")->default(1);
            $table->foreignId("team_id")->constrained("teams");
            $table->timestamps();
        });

        DB::statement('CREATE INDEX question_vector_index ON answers USING hnsw (question_vector vector_cosine_ops)');
        DB::statement('CREATE INDEX answer_vector_index ON answers USING hnsw (answer_vector vector_cosine_ops)');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
