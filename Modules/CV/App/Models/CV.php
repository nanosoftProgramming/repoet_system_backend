<?php

namespace Modules\CV\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\App\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CV extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'data',
        'is_completed',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected $table = 'cvs';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('CV')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
