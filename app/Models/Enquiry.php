<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enquiry extends Model
{
    use HasFactory;

    protected $table = 'enquiries';

    protected $fillable = [
        'name',
        'email',
        'mobile_number',
    ];
}
