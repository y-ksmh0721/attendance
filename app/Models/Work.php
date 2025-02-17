<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Work extends Model
{
    use HasFactory;

    protected $table = 'works';
    protected $fillable = ['id','name','status','cliant_id'];

    public function cliant(){
        return $this->belongsTo(Cliant::class, 'cliant_id');
    }
}
