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
        Schema::create('other_notes', function (Blueprint $table) {
            $table->id();

            $table->text('title_en');
            $table->text('title_hi');
            $table->text('title_gj');

            $table->tinyInteger('type')->comment('1=SubscriptionPlanNotes,2=ReportSpamJobNotes');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_notes');
    }
};
