<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('municipality_id')->default(null)->nullable()->index('municipality_id')->comment('市区町村ID');
            $table->string('name', 255)->default(null)->nullable()->comment('名前');
            $table->string('kana', 255)->default(null)->nullable()->comment('ふりがな');
            $table->string('image', 255)->default(null)->nullable()->comment('顔写真');
            $table->string('affiliation', 255)->default(null)->nullable()->comment('所属');
            $table->string('email', 255)->default(null)->nullable()->comment('メールアドレス');
            $table->integer('num')->default(null)->nullable()->comment('当選回数');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
