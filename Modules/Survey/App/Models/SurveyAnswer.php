<?php

namespace Modules\Survey\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\User\App\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SurveyAnswer extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'survey_submission_id',
        'survey_question_id',
        'answer',
    ];

    protected $casts = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('SurveyAnswer')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    // public function submission(): BelongsTo
    // {
    //     return $this->belongsTo(SurveySubmission::class, 'survey_submission_id');
    // }
    public function submission()
{
    return $this->belongsTo(
        SurveySubmission::class,
        'survey_submission_id'
    );
}
public function question()
{
    return $this->belongsTo(
        SurveyQuestion::class,
        'survey_question_id'
    );
}
    // public function question(): BelongsTo
    // {
    //     return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    // }
}
