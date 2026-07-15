<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    public const STATUS_DRAFT          = 'draft';
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_PUBLISHED      = 'published';
    public const STATUS_REJECTED       = 'rejected';

    protected $fillable = [
        'course_id',
        'created_by',
        'title',
        'description',
        'passing_score',
        'time_limit_minutes',
        'is_published',
        'status',
        'rejection_note',
    ];

    protected $casts = [
        'passing_score'      => 'integer',
        'time_limit_minutes' => 'integer',
        'is_published'       => 'boolean',
    ];

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /** Instructor yang membuat quiz ini */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // ─── Helper Methods ───────────────────────────────────────────────────────

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPendingReview(): bool
    {
        return $this->status === self::STATUS_PENDING_REVIEW;
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /** Label badge untuk tampilan UI */
    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT          => 'Draft',
            self::STATUS_PENDING_REVIEW => 'Pending Review',
            self::STATUS_PUBLISHED      => 'Published',
            self::STATUS_REJECTED       => 'Rejected',
            default                     => ucfirst($this->status),
        };
    }

    /** Warna badge Bootstrap untuk tampilan UI */
    public function statusColor(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT          => 'secondary',
            self::STATUS_PENDING_REVIEW => 'warning',
            self::STATUS_PUBLISHED      => 'success',
            self::STATUS_REJECTED       => 'danger',
            default                     => 'secondary',
        };
    }
}
