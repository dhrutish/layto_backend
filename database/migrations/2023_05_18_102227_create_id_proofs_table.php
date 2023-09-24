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
        Schema::create('id_proofs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->bigInteger('id_number');
            $table->string('front_image');
            $table->string('back_image');

            $table->tinyInteger('status')->default(1)->comment('1=Pending/Unverified,2=Verified,3=rejected');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('id_proofs');
    }
};
