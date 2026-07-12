<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Auto-generate slug dari 'name' saat creating & saving.
     * Pattern ini konsisten dengan Course model.
     */
    protected static function booted(): void
    {
        static::creating(function (self $category): void {
            if (blank($category->slug) || $category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::saving(function (self $category): void {
            if (blank($category->slug) || $category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Relasi: Category memiliki banyak Course.
     * Bisa digunakan dengan withCount('courses') di Controller.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
