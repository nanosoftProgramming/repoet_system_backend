<?php

namespace Modules\Course\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Attendance\App\Models\Attendance;
use Modules\Enrollment\App\Models\Enrollment;
use Modules\User\App\Models\User;
use Spatie\Activitylog\LogOptions;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['title', 'description', 'image', 'trainer_id', 'sessions_no', 'price', 'date', 'survey_file', 'details_file', 'is_active', 'is_accepted', 'student', 'instructor', 'trainer'];

    protected $appends = ['current_session', 'is_completed'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Course')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    // Serializes
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    // Scopes
    public function scopeActive($query)
    {
        $query->where('is_active', 1);
    }

    public function scopeFilter($query, $data)
    {
        $query->when($data['trainer_id'] ?? null, function ($q) use ($data) {
            $q->where('trainer_id', $data['trainer_id']);
        })
            ->when($data['min_price'] ?? null, function ($q) use ($data) {
                $q->where('price', '>=', $data['min_price']);
            })
            ->when($data['max_price'] ?? null, function ($q) use ($data) {
                $q->where('price', '<=', $data['max_price']);
            });
    }

    public function scopeAvailable($query)
    {
        if (auth('user')->check()) {
            $user = auth('user')->user();
            if ($user->hasRole('Trainer')) {
                return $query->active()->where('trainer_id', $user->id);
            }elseif($user->hasRole('Instructor')){
                return $query->active();
            }
        }

        return $query;
    }

    // Getters
    private ?int $maxSessionNo = null;

    private function getMaxSessionNo(): int
    {
        if ($this->maxSessionNo === null) {
            $this->maxSessionNo = (int) ($this->attendances()->max('session_no') ?? 0);
        }

        return $this->maxSessionNo;
    }

    public function getCurrentSessionAttribute(): mixed
    {
        return $this->getMaxSessionNo();
    }

    public function getIsCompletedAttribute(): mixed
    {
        return $this->getMaxSessionNo() >= $this->sessions_no ? 1 : 0;
    }

    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/course/' . $value);
            }
        }
    }

    public function getSurveyFileAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/course/survey/' . $value);
            }
        }
    }

    public function getDetailsFileAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/course/details/' . $value);
            }
        }
    }

    public function getTotalSessionsAttribute()
    {
        return $this->attendances()->count();
    }

    // Relations
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(CourseNote::class);
    }
}
