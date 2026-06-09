<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Academy extends Model
{
    use HasFactory;

    // التصحيح: مصفوفة واحدة تحتوي على كل الحقول المسموح بتعبئتها
    protected $fillable = [
        'name', 
        'username', 
        'password'
    ];

    // نصيحة إضافية: لتشفير كلمة المرور تلقائياً عند حفظها
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}