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
        Schema::create('candidates', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->integer('age');
                $table->enum('gender', ['Male', 'Female', 'Other']);
                $table->string('nationality');
                $table->string('national_id')->unique();
                $table->string('election_id');
                $table->enum('election_type', ['Mayor', 'Councilor', 'MP']);
                $table->enum('constituency', ['Mukono', 'Kampala', 'Wakiso', 'Masaka', 'General']);
                $table->string('photo_path');
                $table->string('party');
                $table->string('biography_path');
                $table->string('manifesto_path');
                $table->enum('status', ['Pending', 'Active', 'Withdrawn'])->default('Pending');
                $table->boolean('approved')->default(false);
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
