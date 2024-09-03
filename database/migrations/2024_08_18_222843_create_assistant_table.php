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
        Schema::create('assistant', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text("instruction");
            $table->string("assistant_id");
            $table->string("model")->default("gpt-4o-mini");
            $table->string("store_id");
            $table->foreignId("team_id")->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistant');
    }
};
