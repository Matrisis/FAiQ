<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('team_parameter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained();

            $table->string('title')->default("Trouvez la réponse qui vous correspond");
            $table->string('background_color')->default("#ffffff");
            $table->string('question_background_color')->default("#e50914");
            $table->string('text_color')->default("#000000");
            $table->string('title_color')->default("#ffffff");
            $table->boolean('accessible')->default(false);
            $table->string('logo_path')->default(Storage::url("resources/default/logo.png"));
            $table->string('icon_path')->default(Storage::url("resources/default/icon.png"));
            $table->string('neighbor_distance')->default("0.95");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_parameter');
    }
};
