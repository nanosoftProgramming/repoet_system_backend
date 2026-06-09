<?php

namespace Modules\Survey\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Survey\App\Models\Survey;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SurveyQuestion extends Model
{
    use HasFactory, LogsActivity;

protected $fillable = [
    'survey_id',
    'survey_question_category_id',
    'question',
    'type',
    'order'
];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('SurveyQuestion')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestionCategory::class);
    }
public function survey()
{
    return $this->belongsTo(Survey::class);
}
    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}
