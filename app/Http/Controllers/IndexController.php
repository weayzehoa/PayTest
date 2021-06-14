<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use NewebPay;

class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function pay()
    {
        $pay = request()->pay;
        if(!empty($pay)){
            return view('pay', compact('pay'));
        }else{
            return view('pay');
        }
    }
}
