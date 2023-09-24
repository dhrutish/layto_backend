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
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('job_id')->nullable();
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->text('title');
            $table->longText('description');
            $table->tinyInteger('type')->default(1)->comment('1=ForAdmin,2=GeneralNotification,3=jobSpammedAndCoinsReturned,4=NewProposal,5=ProposalAccepted,6=ProposalRejected,7=TermsAndConditionUpdated,');
            $table->tinyInteger('is_read')->default(2)->comment('1=Yes,2=No');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
