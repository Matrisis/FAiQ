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
        Schema::create('embedding', function (Blueprint $table) {
            $table->id();
            $table->enum("type", ["text", "file", "url"])->default("text");
            $table->text("content")->nullable()->default(null);
            $table->vector("embedding", 1536)->nullable()->default(null);
            $table->string("file_id")->nullable()->default(null);
            $table->string("url")->nullable()->default(null);
            $table->foreignId("team_id")->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });

        DB::statement('CREATE INDEX embedding_index ON embedding USING hnsw (embedding vector_cosine_ops)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('embedding');
    }
};
