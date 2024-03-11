<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;
    protected $table = "courses";
    protected $fillable = [
        'organisation_id','category_id','title','shortname','description','image','is_published'
    ];
    protected $casts = [
        'is_published' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class);
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(CourseSection::class);
    }

    public function enrolments(): HasMany
    {
        return $this->hasMany(CourseEnrolment::class);
    }
}
