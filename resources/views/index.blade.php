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
            <div class="card-body login-card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('order.store') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-block btn-submit">藍新測試</button>
                        </form>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-primary btn-block btn-submit">藍新信用卡</button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-danger btn-block btn-submit" value="藍新ATM轉帳">藍新ATM轉帳</button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-success btn-block btn-submit" value="藍新超商付款">藍新超商付款</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="newebpay">
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
</body>
</html>

<script>
    $('.btn-submit').click(function(){
        let method = $(this).val();
        let token = '{{ csrf_token() }}';
        $.ajax({
            type: "post",
            url: 'api/order',
            data: { useId: 84533, pay_method: method , _token: token },
            success: function(data) {
                console.log(data);
                $('#newebpay').html(data);
                // alert(data);
            }
        });
    });
</script>