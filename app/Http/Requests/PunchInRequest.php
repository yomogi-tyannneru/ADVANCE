<?php

namespace App\Http\Requests;

use App\Models\Time;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PunchInRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [];
  }

  /**
   * バリデータインスタンスの設定
   *
   * @param \Illuminate\Validation\Validator $validator
   * @return void
   */
  public function withValidator($validator)
  {
    $validator->after(function ($validator) {
      $time = Time::Where('user_id', Auth::id())
        ->where('date', Carbon::today())
        ->whereNotNull('punch_in')
        ->first();
      if (!empty($time)) {
        $validator->errors()->add('user_id', '勤怠開始が打刻済です。');
      }
    });
  }
}
