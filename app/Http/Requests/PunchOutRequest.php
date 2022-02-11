<?php

namespace App\Http\Requests;

use App\Models\Time;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PunchOutRequest extends FormRequest
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
return [

];
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
$punch_in = Time::Where('user_id', Auth::id())
->where('date', Carbon::today())
->whereNotNull('punch_in')
->first();
if (empty($punch_in)) {
$validator->errors()->add('punch_in', '勤怠開始が入力されてません。');
}

$punch_out = Time::Where('user_id', Auth::id())
->where('date', Carbon::today())
->whereNotNull('punch_out')
->first();
if (!empty($punch_out)) {
$validator->errors()->add('punch_out', '勤怠終了が打刻済です。');
}
});
}
}