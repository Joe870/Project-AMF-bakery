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
        Schema::create('AlarmHistory', function (Blueprint $table) {
            $table->id();
            $table->timestamp('event_time')->nullable();
            $table->string('message')->nullable();
            $table->string('state_change_type')->nullable();
            $table->string('alarm_class')->nullable();
            $table->integer('alarm_count')->nullable();
            $table->string('alarm_group')->nullable();
            $table->string('name')->nullable();
            $table->string('alarm_state')->nullable();
            $table->string('condition')->nullable();
            $table->integer('current_value')->nullable();
            $table->string('inhibit_state')->nullable();
            $table->string('limit_value_exceeded')->nullable();
            $table->string('priority')->nullable();
            $table->string('severity')->nullable();
            $table->string('tag1_value')->nullable();
            $table->string('tag2_value')->nullable();
            $table->string('tag3_value')->nullable();
            $table->string('tag4_value')->nullable();
            $table->string('event_category')->nullable();
            $table->string('quality')->nullable();
            $table->string('expression')->nullable();
            $table->timestamps();
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
