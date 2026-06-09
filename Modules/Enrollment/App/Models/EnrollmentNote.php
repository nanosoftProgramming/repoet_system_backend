<?php

namespace Modules\Enrollment\App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Modules\Enrollment\App\Models\Enrollment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnrollmentNote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['enrollment_id', 'note', 'file', 'status'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('EnrollmentNote')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    //Serializes
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    //Getters
    public function getFileAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/enrollment/student/notes/' . $value);
            }
        }
    }

    //Relations
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    //Scopes
    public function scopeAvailable($query)
    {
        $user = auth('user')->user();
        if ($user->hasRole('Super Admin')) {
        } elseif ($user->hasRole('Student')) {
            return $query->whereHas('enrollment', function ($q) use ($user) {
                $q->where('student_id', $user->id);
            });
        }
    }

}
