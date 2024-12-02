<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('alarm_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamp('EventTime')->nullable();
            $table->text('Message')->nullable();
            $table->string('StateChangeType')->nullable();
            $table->string('AlarmClass')->nullable();
            $table->integer('AlarmCount')->nullable();
            $table->string('AlarmGroup')->nullable();
            $table->string('Name')->nullable();
            $table->string('AlarmState')->nullable();
            $table->string('Condition')->nullable();
            $table->string('CurrentValue')->nullable();
            $table->string('InhibitState')->nullable();
            $table->string('LimitValueExceeded')->nullable();
            $table->string('Priority')->nullable();
            $table->string('Severity')->nullable();
            $table->string('Tag1Value')->nullable();
            $table->string('Tag2Value')->nullable();
            $table->string('Tag3Value')->nullable();
            $table->string('Tag4Value')->nullable();
            $table->string('EventCategory')->nullable();
            $table->string('Quality')->nullable();
            $table->text('Expression')->nullable();
            $table->timestamps(); // Adds `created_at` and `updated_at`
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alarm_histories');
    }
};
