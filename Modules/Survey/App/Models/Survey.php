<?php

namespace Modules\Survey\App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = [
        'name',
        'academy_id',
        'created_by',
        'status'
    ];

    public function academy()
    {
        return $this->belongsTo(
            \Modules\User\App\Models\User::class,
            'academy_id'
        );
    }

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }
}