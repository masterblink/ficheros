<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichero extends Model
{
    protected $table = 'ficheros';
    
    use HasFactory;

    protected $fillable = [
        'name', 'file', 'user_id'
    ];
}
