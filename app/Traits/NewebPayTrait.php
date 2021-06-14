<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait NewebPayTrait
{
    protected $payMethod = ['智付通信用卡' => 'CREDIT', '智付通ATM' => 'VACC', '智付通CVS' => 'CVS', '智付通銀聯卡' => 'UNIONPAY'];

    public function newebPay($method, $orderNumber, $amount, $email, $memo = '')
    {
        $paymentType = $this->payMethod[$method];

        $array = [
            'MerchantOrderNo' => $orderNumber,  //商店訂單編號V
            'Amt' => $amount,                   //訂單金額V
            'OrderComment' => $memo,            //商店備註V
            'Email' => $email,                  //付款人電子信箱 = 於交易完成或付款完成時，通知付款人使用。
            $paymentType => 1,
        ];
        $spgateway = array_merge($this->setting(), $array);

        $form = $this->make($spgateway);
        return $form;
    }

    private function make($spgateway)
    {
        $form='<html><meta charset="UTF-8"><head></head><body>
        <form id="spgateway_form" name="form1" method="post" action="'.$spgateway["API"].'">';
        foreach ($spgateway as $k=>$v) {
            if ($k!='HashKey' && $k!='HashIV') {
                $form.='<input type="hidden" name="'.$k.'" value="'.$v.'">';
            }
        }
        $form.='</form>
        <script>document.getElementById("spgateway_form").submit();</script>
        </body></html>';
        return $form;
    }

    private function setting()
    {
        return [
            'HashKey' => env('NEWEBPAY_HASH_KEY'),
            'HashIV' => env('NEWEBPAY_HASH_IV'),
            'MerchantID' => env('NEWEBPAY_MERCHANT_ID'),
            'LoginType' => 0,//1 = 須要登入智付通會員
            'Version' => env('NEWEBPAY_VERSION'),
            'RespondType' => 'JSON',
            'TimeStamp' => time(),
            'LangType' => 'zh-tw',//英文en
            'ReturnURL' => env('NEWEBPAY_RETURN_URL'),//支付完成 返回商店網址
            'NotifyURL' => env('NEWEBPAY_NOTIFY_URL'),//支付通知網址
            'CustomerURL' => env('NEWEBPAY_CUSTOMER_URL'),//商店取號網址
            'ClientBackURL' => '',//支付取消 返回商店網址 V
            'TokenTerm' => '',//會員編號 = 可對應付款人之資料，用於綁定付款人與信用卡卡號時使用
            'CheckValue' => '',
            'ItemDesc' => 'iCarry我來寄 訂單',//商品資訊V
            "ExpireDate"=>date('Ymd', time()+3600),
            'API' => env('NEWEBPAY_API_URL'),
        ];
    }

    //官方說明文件提供 加密
    private function encrypt($parameter = "", $key = "", $iv = "")
    {
        $returnStr = '';
        if (!empty($parameter)) {
            //將參數經過 URL ENCODED QUERY STRING
            $returnStr = http_build_query($parameter);
        }
        return trim(bin2hex(openssl_encrypt(addPadding($returnStr), 'aes-256-
       cbc', $key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $iv)));
    }

    //官方說明文件提供 加密副程式
    private function addPadding($string, $blocksize = 32)
    {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);
        return $string;
    }

    //官方說明文件提供 解密
    private function decrypt($parameter = "", $key = "", $iv = "")
    {
        return stripPadding(openssl_decrypt(
            hex2bin($parameter),
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING,
            $iv
        ));
    }
    //官方說明文件提供 加密副程式
    private function stripPadding($string)
    {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        } else {
            return false;
        }
    }
}
