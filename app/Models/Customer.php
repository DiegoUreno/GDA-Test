<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $primaryKey = 'dni';
    public $incrementing = false;

    // Define las propiedades fillable
    protected $fillable = [
        'dni',
        'id_reg',
        'id_com',
        'email',
        'name',
        'last_name',
        'address',
        'date_reg',
        'status',
    ];
}
