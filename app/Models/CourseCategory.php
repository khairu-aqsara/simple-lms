<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string, mixed $getKey)
 */
class CourseCategory extends Model
{
    use HasFactory;

    protected $table="course_categories";
    protected $fillable = [
        'organisation_id', 'title', 'description'
    ];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
