<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\sayHelloClass;

class TopController extends Controller
{

    public function show()
    {
        sayHelloClass::sayHello();

        return view(
            'topShow'
        );
    }
}
