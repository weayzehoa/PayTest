<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NewebPay;
use View;

class PayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('pay');
    }


    public function store(Request $request)
    {
        return NewebPay::payment(
            time(), // 訂單編號
            1, // 交易金額
            '測試', // 交易描述
            'weayzehoa@gmail.com'// 付款人信箱
        )->submit();
    }

    public function newebpayReturn(Request $request)
    {
        $return = NewebPay::decode($request->input('TradeInfo'));
        return redirect('/');
    }

    public function newebpayNotify(Request $request)
    {
        $notify = $request;
    }
}
