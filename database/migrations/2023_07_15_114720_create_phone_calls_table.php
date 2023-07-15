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
        Schema::create('phone_calls', function (Blueprint $table) {
            $table->id(); // フレームワークの標準機能の範囲内の利用
            $table->foreignId('caller_user_id')->constrained('users');
            $table->foreignId('receiver_user_id')->constrained('users');
            $table->string('status')->comment('通話ステータス');
            $table->timestamp('called_at')->comment('かけた日時');
            $table->timestamp('talk_started_at')->nullable()->comment('通話開始日時');
            $table->timestamp('finished_at')->nullable()->comment('通話終了日時');
            $table->integer('call_charge')->nullable()->comment('通話料金');
            $table->timestamps();
        });
    }
};
