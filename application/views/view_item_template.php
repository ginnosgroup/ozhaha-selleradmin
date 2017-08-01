<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="tangqiang">

    <title>{row[name]} - 哈哈外卖商家后台</title>

    <?php $this->load->view('common_header_template') ?>
</head>

<body>

    <div id="wrapper">

        <?php $this->load->view('common_navigation_template') ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                      <li><a href="item">商品列表</a></li>
                      <li class="active">商品详情</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                      <div class="panel-heading"><h3>{row[name]}</h3></div>
                      <div class="panel-body">
                        <div class="goods-info">
                            <h4>所属类目：{row[category_name]} </h4>
                            <h4>单价：${row[price]} </h4>
                            <h4>剩余数量：10 </h4>
                            <h4>可否用优惠券：{row[use_coupon]}</h4>
                            <h4>商品介绍：{row[content]}</h4>
                        </div>
                        <div class="goods-imgs" id="goods-imgs">
                            <div class="row">
                             	{file_list}
															<div class="col-xs-6 col-md-4 col-lg-2">
															    <a href="{image_url}" target="_blank" class="thumbnail">
															        <img  data-src="{image_url}" alt="{upload_root}{image_url}"  src="{image_url}" data-holder-rendered="true">
															    </a>
															</div>
															{/file_list}
                            </div>
                        </div>
                      </div>
                      <div class="panel-footer">注意：该商品太畅销了，请及时增加数量哟~~~</div>
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
        $(function(){            
        })                
    </script>
   
</body>

</html>