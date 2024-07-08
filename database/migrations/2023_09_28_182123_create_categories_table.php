<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id')->default(null)->nullable()->index('member_id')->comment('議員ID');
            $table->string('name')->default(null)->nullable()->comment('カテゴリ名');
            $table->integer('persent')->default(null)->nullable()->comment('パーセント');
            $table->boolean('status')->default(1)->comment('ステータス 0:非表示 1:表示');
            $table->timestamps();
            $table->softDeletes()->comment('削除日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
