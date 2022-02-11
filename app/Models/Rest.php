<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rest extends Model
{
  /**
   *　勤怠の勤怠を取得
   */
  public function times()
  {
    return $this->hasMany(Time::class);
  }
}
