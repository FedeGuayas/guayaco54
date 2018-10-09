<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{

    /**
     * PaymentController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


}
