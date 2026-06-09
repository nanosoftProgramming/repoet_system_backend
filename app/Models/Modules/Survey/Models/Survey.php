<?php

namespace Modules\Survey\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = ['name', 'academy_id']; // أضيفي الأعمدة التي تحتاجينها
}