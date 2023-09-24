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
        Schema::create('news_feeds', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('industry_types_id');
            $table->foreign('industry_types_id')->references('id')->on('industry_types')->onDelete('cascade');

            $table->string('title');
            $table->longText('description');
            $table->string('image');

            $table->tinyInteger('is_featured')->default(2)->comment('1=Yes,2=No');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_feeds');
    }
};
