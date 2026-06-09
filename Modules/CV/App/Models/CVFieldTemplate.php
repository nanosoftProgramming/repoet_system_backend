<?php

namespace Modules\CV\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CVFieldTemplate extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'role',
        'label',
        'field_key',
        'is_required',
        'order',
        'placeholder',
        'is_active',
    ];

    protected $table = 'cv_field_templates';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('CVFieldTemplate')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function scopeForRole($query, string $role)
    {
        return $query->where('role', $role)->where('is_active', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('id');
    }
}
