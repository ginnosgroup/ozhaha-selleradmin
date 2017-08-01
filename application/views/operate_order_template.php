<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="tangqiang">

    <title>接单处理 - 哈哈外卖商家后台</title>

    <?php $this->load->view('common_header_template') ?>
    <link href="{static_base_url}css/datetimepicker.css" rel="stylesheet">
</head>

<body>

    <div id="wrapper">

        <?php $this->load->view('common_navigation_template') ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">接单处理</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                		<div style="color:red;">{validation_errors}</div>
                    <div style="color:#669933;">{result_success}</div>
                    <form class="form-horizontal" id="add_item_category" method="post">                        
                        <div class="form-group">
                          <label for="go-off" class="col-lg-2  control-label">预计出发时间</label>
                          <div class="col-lg-3">
                            <input type="text" name="data[delivery_time]" class="form-control form_datetime" id="delivery-time" value="{post_data[delivery_time]}" required readonly="readonly" placeholder="选择配送时间">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="shop-remarks" class="col-lg-2 control-label">商家备注</label>
                          <div class="col-lg-4">
                            <textarea name="data[delivery_note]" class="form-control" id="delivery-note" value="{post_data[delivery_note]}" placeholder="商家备注"></textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label">配送方式</label>
                          <div class="col-lg-4">
                          		<input id="delivery-type-1" type="radio" name="data[delivery_type]" value="PANDA"{post_data[delivery_type]1}> <label for="delivery-type-1">哈哈网配送</label> &nbsp;&nbsp;&nbsp;&nbsp;
                              <!-- <input id="delivery-type-0" type="radio" name="data[delivery_type]" value="SELF"{post_data[delivery_type]0}> <label for="delivery-type-0">商家配送</label> -->
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-lg-offset-2 col-lg-4">
                            <button type="submit" id="btnsubmit" class="btn btn-primary">接单</button>
                          </div>
                        </div>
                    </form>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="{static_base_url}js/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{static_base_url}js/bootstrap.min.js"></script>
    <script src="{static_base_url}js/datetimepicker.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="{static_base_url}js/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{static_base_url}js/sb-admin-2.js"></script>
    <script>
        $(function(){ 
        	//时间选择插件
          var today = new Date();
          var defaultDate =  today.getDate() + '/' + (today.getMonth()+1) + '/' + today.getFullYear();
          console.log(defaultDate);
          $(".form_datetime").datetimepicker({
              format: 'yyyy-mm-dd hh:ii:ss',
              autoclose: true,
              todayBtn: true,
              startView: 1
          });          
        })
        
    </script>
   
</body>

</html>