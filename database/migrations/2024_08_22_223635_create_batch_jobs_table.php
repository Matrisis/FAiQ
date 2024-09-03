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
        Schema::create('batch_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->nullable()->default(null);
            $table->date("finished_at")->nullable();
            $table->boolean("locked")->default(false);
            $table->enum("type", ["embedding", "chatting"]);
            $table->string("batch_file");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_jobs');
    }
};
