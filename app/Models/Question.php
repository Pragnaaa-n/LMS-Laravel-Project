<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'course_id',
        'exam_id',
        'test_type_id',
        'question',
        'option1',
        'option2',
        'option3',
        'option4',
        'option5',
        'option6',
        'correct_answer',
        'description'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class, 'exam_id', 'id');
    }

    public function testType()
    {
        return $this->belongsTo(TestType::class, 'test_type_id', 'id');
    }
}
