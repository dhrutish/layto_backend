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
        // $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        // $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->comment('
                1=SignUpJobProvider(UP),
                2=SignUpJobSeeker(UP),
                3=CreditedByAdmin(UP),
                4=DeductedByAdmin(DOWN),
                5=JobPosted(DOWN),
                6=Plan/Coins-Purchased(UP),
                7=SwitchProfileToJobSeeker(DOWN),
                8=SwitchProfileToJobProvider(DOWN),
                9=Referral(UP),
                10=ProposalToJobSeeker(DOWN),
                11=JobClosed/Spammed(UP),
                12=JobApplied(DOWN),
            ');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');

            $table->unsignedBigInteger('refer_user_id')->nullable();
            $table->foreign('refer_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('job_id')->nullable();
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('restrict');

            $table->unsignedBigInteger('plan_id')->nullable();
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('set null');

            $table->integer('plan_from_coins')->nullable();
            $table->integer('plan_to_coins')->nullable();
            $table->double('plan_additional_coins_pr')->nullable();
            $table->integer('purchase_coins')->nullable();
            $table->integer('purchase_additional_coins')->nullable();

            $table->integer('final_coins')->nullable();
            $table->double('amount')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('description')->nullable();

            $table->tinyInteger('is_coins_used')->default(2)->comment('1=Yes,2=No,3=QuaterlyUsed,4=Expired');
            $table->integer('used_coins')->default(0)->nullable();
            $table->date('coins_used_at')->nullable();

            $table->integer('coin_expire_days')->default(0)->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
