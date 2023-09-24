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
        Schema::create('industry_types', function (Blueprint $table) {
            $table->id();

            $table->string('title_en');
            $table->string('title_hi');
            $table->string('title_gj');

            $table->tinyInteger('is_available')->default(1)->comment('1=yes,2=no');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industry_types');
    }
};
