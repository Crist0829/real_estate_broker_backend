<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCalificationColumnToPropertyCalificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_califications', function (Blueprint $table) {
            $table->unsignedTinyInteger('calification')->after('property_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_califications', function (Blueprint $table) {
            $table->dropColumn('calification');
        });
    }
}
