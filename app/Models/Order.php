<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'date',
        'exam_type_id',
        'student_id',
        'email',
        'mobile',
        'start_validity_date',
        'expire_validity_date',
        'receipt',
        'status'
    ];

    public function examType()
    {
        return $this->belongsTo(ExamType::class, 'exam_type_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
