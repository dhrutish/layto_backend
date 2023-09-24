<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // $databaseName = env('DB_DATABASE');
        // DB::statement("DROP DATABASE IF EXISTS $databaseName");
        // DB::statement("CREATE DATABASE $databaseName");

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type')->comment('1=SuperAdmin,2=SubAdmin,3=JobProvider,4=JobSeeker');
            $table->string('name');
            $table->string('country_code')->nullable();
            $table->string('mobile')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->longText('about')->nullable();
            $table->string('image')->default('default.png');
            $table->tinyInteger('is_email_verified')->default(2)->comment('1=yes,2=no');
            $table->tinyInteger('is_mobile_verified')->default(2)->comment('1=yes,2=no');
            $table->string('fcm_token')->nullable();
            $table->tinyInteger('login_type')->default(5)->comment('1=Mobile,2=Google,3=facebook,4=Apple,5=Email');
            $table->string('social_login_id')->nullable();
            $table->string('referral_code')->nullable();

            $table->tinyInteger('is_available')->default(1)->comment('1=yes,2=no');

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
