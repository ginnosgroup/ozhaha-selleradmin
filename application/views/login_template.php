<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="tangqiang">

    <title>哈哈外卖商家后台 - 登录</title>

    <!-- Bootstrap Core CSS -->
    <link href="{static_base_url}css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{static_base_url}css/sb-admin-2.css" rel="stylesheet">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->    
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">哈哈外卖商家后台登录</h3>
                    </div>
                    <div class="panel-body">
                    		<div style="color:red;">{validation_errors}</div>
                        <form role="form" method="post">
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="请输入商家注册电话" name="phone" id="phone" value="{form_phone}"  autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" placeholder="请输入密码" name="password" id="password" value="{form_password}" >
                            </div>
                            <button type="submit" class="btn btn-lg btn-primary btn-block">登录</button>
                             <div class="checkbox">
                                <a href="find_passwd">忘记密码？</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</body>

</html>