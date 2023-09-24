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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('job_id')->nullable();
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');

            $table->double('from_amount')->default(0);
            $table->double('to_amount')->default(0);
            $table->text('description')->nullable();

            $table->tinyInteger('status')->default(1)->comment('1=Pending,2=Accepted,3=Rejected');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
