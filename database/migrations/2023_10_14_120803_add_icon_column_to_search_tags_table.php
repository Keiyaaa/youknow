<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconColumnToSearchTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('search_tags', function (Blueprint $table) {
            $table->string('icon')->nullable()->default(null)->after('sort_order')->comment('アイコン画像');
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
            $table->dropColumn('icon');
        });
    }
}
