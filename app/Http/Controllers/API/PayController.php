<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NewebPay;
use App\Models\Spgateway as SpgatewayDB;
use App\Traits\NewebpayTrait;

class PayController extends Controller
{
    use NewebPayTrait;

    protected $form;
    protected $payProvider = ['智付通信用卡' => 'spgateways', '智付通ATM' => 'spgateways', '智付通CVS' => 'spgateways', '智付通銀聯卡' => 'spgateways'];

    public function __construct()
    {
        $this->pay_method = request()->pay_method;
        $this->orderNumber = time().'TS';
        $this->totalAmount = 1000;
        $this->description = 'iCarry我來寄 訂單';
        $this->buyerEmail = 'roger@icarry.me';
    }

    public function index()
    {
        $provider = $this->payProvider[$this->pay_method];

        if($provider == 'spgateways'){
            $form = $this->newebPay($this->pay_method,$this->orderNumber,$this->totalAmount,$this->buyerEmail);
        }
        return $form;


        //     $neWebPay = NewebPay::payment(
        //                 $this->orderNumber,     // 訂單編號
        //                 $this->totalAmount,     // 交易金額
        //                 $this->description,     // 商品描述
        //                 $this->buyerEmail,      // 付款人信箱
        //             );

        // if(!empty($this->pay_method)){
        //     $this->type = $payMethod[$this->pay_method];
        //     $this->pay_method == '智付通信用卡' ? $this->PaymentType = 'CREDIT' : '';
        //     $this->pay_method == '智付通ATM' ? $this->PaymentType = 'VACC' : '';
        //     $this->pay_method == '智付通CVS' ? $this->PaymentType = 'CVS' : '';
        //     $this->pay_method == '智付通銀聯卡' ? $this->PaymentType = 'UNIONPAY' : '';
        //     $this->pay_method == '智付通信用卡' ? $this->form = $neWebPay->submit() : '';
        //     $this->pay_method == '智付通ATM' ? $this->form = $neWebPay->setPaymentMethod(['CREDIT'=> false, 'VACC' => true])->submit() : '';
        //     $this->pay_method == '智付通CVS' ? $this->form = $neWebPay->setPaymentMethod(['CREDIT'=> false, 'CVS' => true])->submit() : '';
        //     $this->pay_method == '智付通銀聯卡' ? $this->form = $neWebPay->setPaymentMethod(['CREDIT'=> false, 'UNIONPAY' => true])->submit() : '';
        //     //紀錄
        //     $this->log();
            //使用 form 表單觸發轉向
            if(!empty($this->form)){
                return redirect()->route('pay', ['pay' => $this->form]);
            }
        // }
        return '付款方式錯誤。';
    }

    public function newebpayReturn(Request $request)
    {
        $tradInfo = NewebPay::decode($request->input('TradeInfo'));
        if($request->Status == 'SUCCESS'){
            //交易成功
            // {"Status":"SUCCESS","Message":"\u6388\u6b0a\u6210\u529f","Result":{"MerchantID":"MS120845008","Amt":1000,"TradeNo":"21061419120578047","MerchantOrderNo":"TEST1623669113TEST","RespondType":"JSON","IP":"103.224.201.242","EscrowBank":"HNCB","PaymentType":"CREDIT","PayTime":"2021-06-14 19:12:05","RespondCode":"00","Auth":"341542","Card6No":"400022","Card4No":"1111","Exp":"2504","TokenUseStatus":0,"InstFirst":0,"InstEach":0,"Inst":0,"ECI":"","PaymentMethod":"CREDIT"}}
            return $tradInfo;
        }else{
            // 交易失敗
            // {"Status":"MPG03009","Message":"\u62d2\u7d55\u4ea4\u6613_\u62d2\u7d55\u5361\u865f","Result":{"MerchantID":"MS120845008","Amt":1000,"TradeNo":null,"MerchantOrderNo":"TEST1623669315TEST","RespondType":"JSON","IP":null,"EscrowBank":"-","PaymentType":"CREDIT","PayTime":"2021-06-14 19:15:33","RespondCode":"-","Auth":null,"Card6No":null,"Card4No":null,"Exp":null,"TokenUseStatus":0,"InstFirst":0,"InstEach":0,"Inst":0,"ECI":null,"PaymentMethod":null}}
            $message = $this->newebPayCode($request->Status);
            return $message;
        }

        return $request;

    }

    public function newebpayNotify(Request $request)
    {
        return 'Notify';
        $result = json_decode($request,true);
        if($result['Status'] == 'SUCCESS'){
            $tradInfo = NewebPay::decode($request->input('TradeInfo'));
            return $tradInfo;
        }
        return $request;
    }

    private function newebPayCode($s)
    {
        switch($s){
            case 'MPG01001': return '會員參數 不可空白/設定錯誤'; break;
            case 'MPG01002': return '時間戳記不可空白'; break;
            case 'MPG01005': return 'TokenTerm 不可空白/設定錯誤'; break;
            case 'MPG01008': return '分期參數設定錯誤'; break;
            case 'MPG01009': return '商店代號不可空白'; break;
            case 'MPG01010': return '程式版本設定錯誤'; break;
            case 'MPG01011': return '回傳規格設定錯誤'; break;
            case 'MPG01012': return '商店訂單編號不可空白/設定錯誤'; break;
            case 'MPG01013': return '付款人電子信箱設定錯誤'; break;
            case 'MPG01014': return '網址設定錯誤'; break;
            case 'MPG01015': return '訂單金額不可空白/設定錯誤'; break;
            case 'MPG01016': return '檢查碼不可空白'; break;
            case 'MPG01017': return '商品資訊不可空白'; break;
            case 'MPG01018': return '繳費有效期限設定錯誤'; break;
            case 'MPG02001': return '檢查碼錯誤'; break;
            case 'MPG02002': return '查無商店開啟任何金流服務'; break;
            case 'MPG02003': return '支付方式未啟用，請洽客服中心'; break;
            case 'MPG02004': return '送出後檢查，超過交易限制秒數'; break;
            case 'MPG02005': return '送出後檢查，驗證資料錯誤'; break;
            case 'MPG02006': return '系統發生異常，請洽客服中心'; break;
            case 'MPG03001': return 'FormPost 加密失敗'; break;
            case 'MPG03002': return '拒絕交易 IP'; break;
            case 'MPG03003': return 'IP 交易次數限制 N 分鐘內不可交易達 M 次'; break;
            case 'MPG03004': return '商店狀態已被暫停或是關閉，無法進行交易'; break;
            case 'MPG03007': return '查無此商店代號'; break;
            case 'MPG03008': return '已存在相同的商店訂單編號'; break;
            case 'MPG03009': return '交易失敗'; break;
            default : return '未知的錯誤(如您使用銀聯卡，可於五到十分鐘後至歷史訂單確認是否支付成功)'; break;
        }
    }

    private function log(){
        if($this->type == 'spgateways'){
            $data = ['post_json' => $this->form, 'order_number' => $this->orderNumber, 'pay_status' => 2, 'amount' => $this->totalAmount, 'PaymentType' => $this->PaymentType];
            SpgatewayDB::create($data);
        }
    }
}
