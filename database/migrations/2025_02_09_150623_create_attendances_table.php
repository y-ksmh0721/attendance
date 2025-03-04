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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id(); // 出勤記録ID
            $table->string('name',255);
            $table->string('work_type',255);
            $table->date('date'); // 出勤日
            $table->string('site', 255); // 現場名
            $table->string('work_content', 255)->nullable(); // 作業内容
            $table->time('end_time')->default('00:00:00'); //終了時間
            $table->decimal('overtime', 4, 1)->default(0); // 残業時間（小数対応）
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
