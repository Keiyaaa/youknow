<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddColorToSearchTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('search_tags', function (Blueprint $table) {
            $table->string('color', 255)->default('#FFFFFF')->comment('テーマカラー');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('search_tags', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
}
