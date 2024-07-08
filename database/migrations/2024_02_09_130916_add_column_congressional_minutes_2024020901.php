<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCongressionalMinutes2024020901 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('congressional_minutes', function (Blueprint $table) {
            $table->mediumText('summary')->nullable()->comment('要約')->after('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('congressional_minutes', function (Blueprint $table) {
            //
        });
    }
}
