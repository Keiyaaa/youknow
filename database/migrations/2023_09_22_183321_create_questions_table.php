<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id')->default(null)->nullable()->index('member_id')->comment('議員ID');
            $table->date('date')->default(null)->nullable()->comment('議会だよりの日付');
            $table->string('title', 255)->default(null)->nullable()->comment('質問タイトル');
            $table->mediumText('content')->default(null)->nullable()->comment('質問内容');
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
        Schema::dropIfExists('questions');
    }
}
