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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->integer('sign_up_seeker')->default(0)->comment('Sign Up Job Seeker Coins');
            $table->integer('sign_up_provider')->default(0)->comment('Sign Up Job Provider Coins');
            $table->integer('job_post_coins')->default(0)->comment('Job Post Coins');
            $table->integer('apply_job_coins')->default(0)->comment('Apply Job Coins');
            $table->integer('seeker_connect_coins')->default(0)->comment('Proposal to Job seeker');
            $table->integer('referral_coins')->default(0)->comment('Referral Coins');

            $table->integer('profile_switch_coins')->default(0)->comment('Deduct coin at profile switch ');
            $table->integer('direct_contact_coins')->default(0)->comment('Deduct coin at Direct Contact ');

            $table->integer('job_auto_close_days')->default(0)->comment('Job Auto Close Days');
            $table->integer('coin_expire_days')->default(0)->comment('Coins auto expiry days');
            $table->tinyInteger('is_gst_included')->default(2)->comment('1=Yes,2=No');
            $table->double('gst')->default(0)->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
