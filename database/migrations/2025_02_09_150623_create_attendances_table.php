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
            $table->string('name',255);                      //名前
            $table->string('work_type',255);                 //種別
            $table->date('date');                            // 出勤日
            // $table->unsignedBigInteger('site_id');          //worksテーブルのid
            $table->string('site', 255);                     // 現場名
            $table->string('work_content', 255)->nullable(); // 作業内容
            $table->time('start_time')->default('00:00:00'); //開始時間
            $table->time('end_time')->default('00:00:00');   //終了時間
            $table->decimal('work_time', 4, 2)
                    ->default(0)
                    ->check('work_time >= 0 AND work_time <= 24 AND work_time = FLOOR(work_time)'); // 作業時間
            $table->decimal('human_role', 4, 2)->default(0);     //人役
            $table->string('time_type')->default('終日');     //時間
            $table->decimal('overtime', 4, 1)->default(0);   // 残業時間（小数対応）
            $table->string('write',255);                     //書き込みアカウントID
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
