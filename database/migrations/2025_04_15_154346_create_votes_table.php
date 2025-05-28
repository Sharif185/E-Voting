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
        Schema::create('votes', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('voter_profile_id')->constrained('voter_profiles')->onDelete('cascade');
                    $table->foreignId('election_id')->constrained()->onDelete('cascade');
                    $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
                    $table->enum('vote_type', ['Mayor', 'Councilor', 'MP']);
                    $table->string('constituency');
                    $table->boolean('is_verified')->default(false);
                    $table->ipAddress('ip_address')->nullable();
                    $table->macAddress('device_id')->nullable();
                    $table->timestamp('voted_at')->useCurrent();
                    $table->timestamps();

                   $table->unique(['voter_profile_id', 'election_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
