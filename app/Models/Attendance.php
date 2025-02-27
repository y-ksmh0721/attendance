<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';
    protected $fillable = ['id','date','user_id', 'morning_site', 'afternoon_site', 'overtime','created_at','updated_at'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function craft()
    {
        return $this->hasOne(Craft::class, 'name', 'name');
    }

    public function getDayOfWeekAttribute()
    {
        return Carbon::parse($this->date)->locale('ja')->isoFormat('ddd');
    }
}
