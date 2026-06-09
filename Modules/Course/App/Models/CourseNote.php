<?php

namespace Modules\Course\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\App\Models\User;
use Spatie\Activitylog\LogOptions;

class CourseNote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['course_id', 'trainer_id', 'note', 'file', 'status'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('CourseNote')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    // Serializes
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    // Getters
    public function getFileAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/course/notes/' . $value);
            }
        }
    }

    // Relations
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        $user = auth('user')->user();
        if ($user->hasRole('Super Admin')) {
        } elseif ($user->hasRole('Trainer')) {
            return $query->where('trainer_id', $user->id);
        }
    }
}
