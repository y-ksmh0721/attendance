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
            $table->date('date'); // 出勤日
            $table->string('morning_site', 255); // 午前の現場名
            $table->string('afternoon_site', 255); // 午後の現場名
            $table->time('overtime')->default(0); // 残業時間（任意）
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
