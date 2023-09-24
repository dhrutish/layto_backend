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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->string('title')->nullable();
            $table->string('address');

            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('area_id');

            $table->string('pincode');
            $table->string('url')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
