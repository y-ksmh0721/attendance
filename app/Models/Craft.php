<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Craft extends Model
{
    use HasFactory;

    protected $table = 'craft';
    protected $fillable = ['id','name','status','company_id'];

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }
}
