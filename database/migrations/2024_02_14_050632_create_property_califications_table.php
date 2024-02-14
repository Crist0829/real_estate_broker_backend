<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyCalificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_califications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties', 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('immovable_califications');
    }
}
