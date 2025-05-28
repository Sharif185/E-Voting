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
        Schema::create('voter_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('firstname');
                $table->string('lastname');
                $table->integer('age');
                $table->enum('gender', ['male', 'female', 'other']);
                $table->string('national_id')->unique();
                $table->enum('constituency', ['Mukono', 'Kampala', 'Masaka', 'Wakiso', 'General']);
                $table->enum('election_type', ['Mayor', 'Councillor', 'MP']);
                $table->boolean('is_approved')->default(false);
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voter_profiles');
    }
};
