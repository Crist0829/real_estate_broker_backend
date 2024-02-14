<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedInteger('floors')->default(0);
            $table->unsignedInteger('livingrooms')->default(0);
            $table->unsignedInteger('bedrooms')->default(0);
            $table->unsignedInteger('kitchens')->default(0);
            $table->unsignedInteger('bathrooms')->default(0);
            $table->foreignId('user_id')->constrained('users', 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('garage');
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
        Schema::dropIfExists('immovables');
    }
}
