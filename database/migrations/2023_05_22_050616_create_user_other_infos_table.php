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
        Schema::create('user_other_infos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('industry_types_id')->nullable();
            $table->foreign('industry_types_id')->references('id')->on('industry_types')->onDelete('set null');
            $table->unsignedBigInteger('availabilities_id')->nullable();
            $table->foreign('availabilities_id')->references('id')->on('availabilities')->onDelete('set null');

            $table->double('exp_salary_from')->default(0)->nullable();
            $table->double('exp_salary_to')->default(0)->nullable();
            $table->tinyInteger('gender')->default(1)->nullable()->comment('1=Male,2=Female,3=Other');
            $table->date('dob')->nullable();

            $table->unsignedBigInteger('education_id')->nullable();
            $table->foreign('education_id')->references('id')->on('education')->onDelete('set null');

            $table->integer('experience_type')->nullable()->comment('1=Any,2=Fresher,3=Experienced');
            $table->integer('exp_years')->default(0)->nullable();
            $table->integer('exp_months')->default(0)->nullable();


            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_other_infos');
    }
};
