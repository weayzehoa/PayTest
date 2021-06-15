<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="Description" content="Enter your description here"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<title>付款測試</title>
</head>
<body>
    <div class="login-box">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center">金流測試</h3>
                <i class="fas fa-info text-primary"></i> 智付通(藍新)金流測試，信用卡號僅接受 4000-2211-1111-1111 ， 商店將於 2021/7/12 到期失效。<br>
                <div class="row mt-2 text-center">
                    <div class="mb-2 col-3">
                        <a href="{{ route('pay.index', ['pay_method' => '智付通信用卡']) }}" class="btn btn-primary btn-block">智付通信用卡</a>
                    </div>
                    <div class="mb-2 col-3">
                        <a href="{{ route('pay.index', ['pay_method' => '智付通ATM']) }}" class="btn btn-primary btn-block">智付通ATM轉帳</a>
                    </div>
                    <div class="mb-2 col-3">
                        <a href="{{ route('pay.index', ['pay_method' => '智付通CVS']) }}" class="btn btn-primary btn-block">智付通超商代碼繳款</a>
                    </div>
                    <div class="mb-2 col-3">
                        <a href="{{ route('pay.index', ['pay_method' => '智付通銀聯卡']) }}" class="btn btn-primary btn-block">智付通銀聯卡</a>
                    </div>
                    <div class="mb-2 col-3">
                        <a href="{{ route('pay.index', ['pay_method' => '付款方式']) }}" class="btn btn-danger btn-block">付款方式錯誤</a>
                    </div>
                    <div class="mb-2 col-3">
                        <form action="{{ route('pay.cancel') }}" method="GET">
                            <input class="form-control" type="text" name="order_number" placeholder="輸入訂單號碼">
                            <button type="submit" class="btn btn-danger">取消智付通信用卡交易</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
</body>
</html>
