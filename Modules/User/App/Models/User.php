<?php

namespace Modules\User\App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Auth\MustVerifyEmail;
use Modules\Course\App\Models\Course;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Modules\Enrollment\App\Models\Enrollment;
use Illuminate\Contracts\Auth\CanResetPassword;
use Modules\Notification\App\Models\Notification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\User\App\Notifications\UserVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

class User extends Authenticatable implements JWTSubject, MustVerifyEmailContract, CanResetPassword
{
    use HasFactory, HasRoles, LogsActivity, MustVerifyEmail, CanResetPasswordTrait, Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'password', 'role', 'is_active', 'image', 'identity_number', 'username', 'national_number', 'birth_date', 'file', 'email_verified_at','academy_id',];
    protected $hidden = ['password', 'remember_token'];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('User')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }
    /**
     * The attributes that are mass assignable.
     */


    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
    public function academy()
{
    return $this->belongsTo(User::class, 'academy_id');
}
    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/user/' . $value);
            }
        }
    }

    public function getFileAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/user/file/' . $value);
            }
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    //Relation

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->latest();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'student_id')->latest();
    }



    //Trainer
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'trainer_id');
    }
    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new UserVerifyEmail);
    }
    //JWT

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * Get the e-mail address where password reset links are sent.
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }
}
