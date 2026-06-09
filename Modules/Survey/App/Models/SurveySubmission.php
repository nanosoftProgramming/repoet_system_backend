<?php

namespace Modules\Survey\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Survey\Database\factories\SurveySubmissionFactory;

class SurveySubmission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_id',
        'answerable_type',
          'student_name',

        'answerable_id',
        'academy_id',
    ];

    public function academy()
{
    // الطالب يتبع لأكاديمية، والتقييم يتبع لنفس الأكاديمية
    return $this->belongsTo(\Modules\User\App\Models\User::class, 'academy_id');
}

// تأكدي أيضاً من إضافة academy_id إلى مصفوفة fillable إذا لم تكن موجودة

public function student()
{
    return $this->belongsTo(\Modules\User\App\Models\User::class, 'student_id')
        ->select('id', 'name', 'email', 'phone', 'academy_id', 'username', 'identity_number');
}

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }

    public function answerable()
    {
        return $this->morphTo();
    }
}
