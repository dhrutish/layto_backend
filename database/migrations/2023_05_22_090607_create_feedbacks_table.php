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
        // JobSeeker --> Job
        // Job Provider -> Job Seeker
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('job_id')->nullable();
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');

            $table->float('rating');
            $table->text('comment');

            $table->tinyInteger('type')->comment('1=byJobProvider,2=byJobSeeker');
            $table->tinyInteger('is_dispute_created')->default(2)->comment('1=yes,2=no');
            $table->string('dispute_description')->nullable();
            $table->tinyInteger('dispute_status')->nullable()->comment('1=Pending,2=Accepted,3=Declined');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
