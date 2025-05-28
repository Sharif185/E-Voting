<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('election_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->integer('votes_count')->default(0);
            $table->integer('position')->nullable();
            $table->boolean('is_winner')->default(false);
            $table->timestamps();

            $table->unique(['election_id', 'candidate_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('election_results');
    }
};
