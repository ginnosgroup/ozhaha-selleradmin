<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="tangqiang">

    <title>修改密码 - 哈哈外卖商家后台</title>

    <?php $this->load->view('common_header_template') ?>
</head>

<body>

    <div id="wrapper">

        <?php $this->load->view('common_navigation_template') ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                      <li><a href="welcome">后台首页</a></li>
                      <li class="active">修改密码</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                      <div class="panel-heading">修改密码</div>
                      <div class="panel-body">
                      	<div style="color:red;">{validation_errors}</div>
                      	<div style="color:#669933;">{result_success}</div>
                        <form class="form-horizontal" id="change_passwd" method="post">
                            <div class="form-group">
                                <label for="set-password" class="col-lg-1 control-label">原始密码</label>
                                <div class="col-lg-2">
                                  <input type="password" name="data[passwd]" class="form-control" id="set-passwd" value="{post_data_passwd}" required placeholder="原始密码">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="set-newpsw" class="col-lg-1 control-label">新密码</label>
                                <div class="col-lg-2">
                                  <input type="password" name="data[passwd1]" class="form-control" id="set-passwd1" value="{post_data_passwd1}" required placeholder="输入新密码">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="note-newpsw" class="col-lg-1 control-label">确认密码</label>
                                <div class="col-lg-2">
                                  <input type="password" name="data[passwd2]" class="form-control" id="set-passwd2" value="{post_data_passwd2}" required placeholder="再次输入密码">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-offset-1 col-lg-4">
                                  <button type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary">确认修改</button>
                                </div>
                            </div>
                        </form>
                      </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="{static_base_url}js/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{static_base_url}js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="{static_base_url}js/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{static_base_url}js/sb-admin-2.js"></script>
    <script>
				$(function() {						
				});
    
    </script>
   
</body>

</html>