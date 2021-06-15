<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NewebPay;

use App\Traits\NewebpayTrait;

class PayController extends Controller
{
    use NewebPayTrait;

    protected $form;
    protected $payProvider = ['智付通信用卡' => 'spgateways', '智付通ATM' => 'spgateways', '智付通CVS' => 'spgateways', '智付通銀聯卡' => 'spgateways'];

    public function __construct()
    {
        $this->pay_method = request()->pay_method;
        $this->order_number = request()->order_number;
        $this->orderNumber = time().'TS';
        $this->totalAmount = 1000;
        $this->description = 'iCarry我來寄 訂單';
        $this->buyerEmail = 'roger@icarry.me';
    }
    //後台用
    public function newebpayCancel()
    {
        if(!empty($this->order_number)){
            $cancel = $this->newebpayCreditCancel($this->order_number);
            return $cancel;
        }else{
            return '輸入訂單號碼';
        }
    }

    public function index()
    {
        $provider = $this->payProvider[$this->pay_method];

        if($provider == 'spgateways'){
            $this->form = $this->newebPay($this->pay_method,$this->orderNumber,$this->totalAmount,$this->buyerEmail);
        }
        if(!empty($this->form)){
            return redirect()->route('pay', ['pay' => $this->form]);
        }
        return '付款方式錯誤。';
    }

    //即時交易返回
    public function newebpayReturn(Request $request)
    {
        $tradInfo = $this->newebDecrypt($request->input('TradeInfo'));
        $resultJson = json_decode($tradInfo,true);
        $result = $resultJson['Result'];
        $orderNumber = $result['MerchantOrderNo'];
        if($request->Status == 'SUCCESS'){
            $memo = '交易成功';
            $payStatus = 1;
        }else{
            $memo = $this->newebPayCode($request->Status);
            $payStatus = -1;
        }
        //紀錄
        $log = $this->newebLog($orderNumber,$payStatus,$tradInfo,$resultJson,$memo,$type = 'update');
        if($payStatus == 1){
            //更新訂單狀態
            //檢查使用者是否第一次訂購,推薦者獲得購物金
            //通知前台
            return $memo;
        }else{
            //付款失敗不更新訂單通知前台
            return $memo;
        }
    }

    //非即時交易取號
    public function newebpayGetCode(Request $request)
    {
        $tradInfo = $this->newebDecrypt($request->input('TradeInfo'));
        $resultJson = json_decode($tradInfo,true);
        $result = $resultJson['Result'];
        $orderNumber = $result['MerchantOrderNo'];
        //只需要紀錄起來, 然後返回訂單資訊給前端
        $payStatus = 2;
        $log = $this->newebLog($orderNumber,$payStatus,$tradInfo,$resultJson,$memo,$type = 'update');
    }

    //非即時交易返回
    public function newebpayNotify(Request $request)
    {
        if(!empty($request)){
            $tradInfo = $this->newebDecrypt($request->input('TradeInfo'));
            $resultJson = json_decode($tradInfo,true);
            $result = $resultJson['Result'];
            $orderNumber = $result['MerchantOrderNo'];
            if($resultJson['Status'] == 'SUCCESS'){
                $memo = null;
                $payStatus = 1;
            }else{
                $memo = $this->newebPayCode($resultJson['Status']);
                $payStatus = -1;
            }
            //紀錄
            $log = $this->newebLog($orderNumber,$payStatus,$tradInfo,$resultJson,$memo,$type = 'update');
            //更新訂單狀態
            //檢查使用者是否第一次訂購,推薦者獲得購物金
            //不通知前台
            //返回 true 給智付通代表接收完成
            return true;
        }
        return null;
    }

}
