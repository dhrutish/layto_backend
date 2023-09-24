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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('industry_types_id')->nullable();
            $table->foreign('industry_types_id')->references('id')->on('industry_types')->onDelete('set null');
            $table->unsignedBigInteger('payment_types_id')->nullable();
            $table->foreign('payment_types_id')->references('id')->on('payment_types')->onDelete('set null');
            $table->unsignedBigInteger('education_id')->nullable();
            $table->foreign('education_id')->references('id')->on('education')->onDelete('set null');
            $table->unsignedBigInteger('availabilities_id')->nullable();
            $table->foreign('availabilities_id')->references('id')->on('availabilities')->onDelete('set null');
            
            $table->unsignedBigInteger('locations_id')->nullable();
            $table->foreign('locations_id')->references('id')->on('locations')->onDelete('set null');

            $table->string('title');
            $table->text('description')->nullable();
            $table->double('min_salary')->default(0);
            $table->double('max_salary')->default(0);
            $table->tinyInteger('gender')->default(1)->comment('1=Male,2=Female,3=Other');
            $table->integer('candidates')->default(0);
            $table->tinyInteger('experience_type')->default(1)->comment('1=Any,2=Fresher,3=Experienced');
            $table->integer('exp_years')->default(0)->nullable();

            $table->tinyInteger('is_female_required')->default(2)->comment('1=Yes,2=No');

            $table->tinyInteger('is_reposted')->default(2)->comment('1=Yes,2=No');
            $table->dateTime('reposted_on')->nullable();
            $table->integer('job_auto_close_days')->default(0)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Active,2=Closed,3=SpamClosed,4=AutoClosed,5=FemaleSecurityPending,6=SwitchProfileClosed');
            $table->dateTime('closed_at')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
