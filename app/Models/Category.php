<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'exam_type_id',
        'category_name',
    ];

    public function ExamType()
    {
        return $this->belongsTo(ExamType::class, 'exam_type_id');
    }
    
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'sub_category_id');
    }
}
