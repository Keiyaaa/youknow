<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('congressional_minutes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('municipality_id')->nullable()->comment('市町村ID');
            $table->date('date')->nullable()->comment('議事録の日付');
            $table->string('url')->nullable()->comment('議事録のURL');
            $table->string('title')->nullable()->comment('議事録タイトル');
            $table->longText('content')->nullable()->comment('議事録の内容');
            $table->integer('sort_order')->default(0)->comment('並び順');
            $table->timestamps();
            $table->softDeletes()->comment('削除日時');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('congressional_minutes');
    }
};
