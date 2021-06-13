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
        $pay = NewebPay::payment(
            time(), // 訂單編號
            1, // 交易金額
            '測試', // 交易描述
            'weayzehoa@gmail.com'// 付款人信箱
        )->submit();
        return view('pay',compact('pay'));
    }

}
