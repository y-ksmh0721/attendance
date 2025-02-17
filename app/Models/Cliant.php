<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliant extends Model
{
    use HasFactory;

    protected $table = 'cliants';
    protected $fillable = ['id','cliant_name'];
}
