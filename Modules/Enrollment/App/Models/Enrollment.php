<?php

namespace Modules\Enrollment\App\Models;

use Modules\Exam\App\Models\Exam;
use Modules\User\App\Models\User;
use Spatie\Activitylog\LogOptions;
use Modules\Course\App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Modules\Attendance\App\Models\Attendance;
use Modules\Enrollment\App\Models\EnrollmentNote;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['student_id', 'course_id', 'is_completed', 'is_paid', 'student_notes', 'student_notes_file'];

    protected $with = ['course.trainer'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Enrollment')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    //Serializes
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
    //Getters
    public function getStudentNotesFileAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/enrollment/student/notes/' . $value);
            }
        }
    }
    //Scopes
    public function scopeAvailable($query)
    {
        $user = auth('user')->user();
        if ($user->hasRole('Trainer')) {
            return $query->whereHas('course', function ($q) use ($user) {
                $q->where('trainer_id', $user->id);
            });
        } elseif ($user->hasRole('Student')) {
            return $query->where('student_id', $user->id)->where('is_paid', 1);
        }

        return $query;
    }

    public function scopeFilter($query, $data)
    {
        $query->when($data['course_id'] ?? null, function ($q) use ($data) {
            $q->where('course_id', $data['course_id']);
        });
    }


    //Relations
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(EnrollmentNote::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function activeExams(): HasMany
    {
        return $this->hasMany(Exam::class)->where('is_active', 1);
    }
}
