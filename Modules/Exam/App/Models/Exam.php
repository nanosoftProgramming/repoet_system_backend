<?php

namespace Modules\Exam\App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Modules\Enrollment\App\Models\Enrollment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exam extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['enrollment_id', 'title', 'score', 'total', 'is_active'];
    protected $hidden = ['enrollment_id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Exam')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    //Serializes
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
    //Relations
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    //Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeAvailable($query)
    {
        $user = auth('user')->user();
        if ($user->hasRole('Trainer')) {
            return $query->whereHas('enrollment', function ($q) use ($user) {
                $q->whereHas('course', function ($sq) use ($user) {
                    $sq->where('trainer_id', $user->id);
                });
            });
        } elseif ($user->hasRole('Student')) {
            return $query->active()->whereHas('enrollment', function ($q) use ($user) {
                $q->where('student_id', $user->id);
            });
        }
        return $query;
    }
}
