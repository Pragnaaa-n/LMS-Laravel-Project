<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roleshavepermission extends Model
{
    use HasFactory;

    protected $table = 'role_have_permission';

    protected $fillable = [
        'role_id','permission_id'
    ];
}
