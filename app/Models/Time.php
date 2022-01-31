<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    /**
     *　勤怠の休憩を取得
     */
    public function rests()
    {
        return $this->hasMany(Rest::class);
    }
}
