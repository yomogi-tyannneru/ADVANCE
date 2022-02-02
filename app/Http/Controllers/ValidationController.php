<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Time;
use App\Common\validation;

class ValidationController extends Controller
{

    public function validation(Request $request)
    {
        $user = Auth::user();
        $today = new Carbon('today');
        $punch_in_data = User::find($user->id)->times
            ->where('date', $today)
            ->first();

        if ($punch_in_data) {
            $request->session()->flash('error_message', '既に勤務を開始しているため勤務開始出来ません');
            return redirect(route('index'));
        }
    }
}
