<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $table = 'tests';

    protected $fillable = [
        'test_type_id',
        'exam_type_id',
        'course_id',
        'vimeo_link',
        'youtube_link',
        'time_picker_start_date',
        'time_picker_end_date',
        'date_picker_start_time',
        'date_picker_end_time',
        'timer',
        'description',
        'status',
    ];

    public function testType()
    {
        return $this->belongsTo(TestType::class, 'test_type_id', 'id');
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class, 'exam_type_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
