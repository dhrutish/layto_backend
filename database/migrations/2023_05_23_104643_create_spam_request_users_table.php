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
        Schema::create('spam_request_users', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('spam_request_id')->nullable();
            $table->foreign('spam_request_id')->references('id')->on('spam_requests')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('notes_id')->nullable();
            $table->foreign('notes_id')->references('id')->on('other_notes')->onDelete('set null');

            $table->text('note')->nullable();
            $table->longText('description');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spam_request_users');
    }
};
