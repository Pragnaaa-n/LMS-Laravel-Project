<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    use HasFactory;

    protected $table = 'exam_types';

    protected $fillable = [

        'exam_name',
        'photo'
    ];
    

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'exam_type_id');
    }
}

