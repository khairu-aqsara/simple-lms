<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseSection extends Model
{
    use HasFactory;
    protected $table = "course_sections";
    protected $fillable = [
        'course_id','title','description','image','scorm_file','scorm_version','scorm_path'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
