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
        Schema::create('text', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->string("content_hash");
            $table->foreignId("embedding_id")->constrained("embedding")->cascadeOnDelete();
            $table->foreignId("team_id")->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('text');
    }
};
