<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NewebPay;

class orderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return NewebPay::payment(
            time(), // 訂單編號
            1, // 交易金額
            '測試', // 交易描述
            'weayzehoa@gmail.com'// 付款人信箱
        )->submit();
    }

    public function return(Request $request)
    {
        $return = NewebPay::decode($request->input('TradeInfo'));
    }

    public function notify(Request $request)
    {
        $notify = $request;
    }
}
