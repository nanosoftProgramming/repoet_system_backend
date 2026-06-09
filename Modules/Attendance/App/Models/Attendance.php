<?php

namespace Modules\Attendance\App\Models;

use Modules\User\App\Models\User;
use Spatie\Activitylog\LogOptions;
use Modules\Course\App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Modules\Enrollment\App\Models\Enrollment;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['enrollment_id', 'is_present', 'student_id', 'course_id', 'session_no'];
    protected $with = ['enrollment'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Attendance')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    //Serializes
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeAvailable($query)
    {
        $user = auth('user')->user();
        if ($user->hasRole('Trainer')) {
            return $query->whereHas('course', function ($q) use ($user) {
                $q->where('trainer_id', $user->id);
            });
        }
        return $query;
    }


    public function scopeFilter($query, $data)
    {
        $query->when($data['course_id'] ?? null, function ($q) use ($data) {
            $q->where('course_id', $data['course_id']);
        });
    }
}
