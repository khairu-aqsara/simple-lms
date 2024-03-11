<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string, true $true)
 */
class Organisation extends Model
{
    use HasFactory;
    protected  $table = "organisations";
    protected $fillable = ["name","is_active"];
    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function members(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function course_categories(): HasMany
    {
        return $this->hasMany(CourseCategory::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
