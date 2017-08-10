<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="tangqiang">

    <title>修改分类 - 哈哈外卖商家后台</title>

    <?php $this->load->view('common_header_template') ?>
</head>

<body>

    <div id="wrapper">

        <?php $this->load->view('common_navigation_template') ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                      <li><a href="item_category">商品分类</a></li>
                      <li class="active">编辑分类</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                		<div style="color:red;">{validation_errors}</div>
                    <div style="color:#669933;">{result_success}</div>
                    <form class="form-horizontal" id="edit_item_category" method="post">
                        <div class="form-group">
                            <label for="classify-name" class="col-lg-1 control-label">分类名称</label>
                            <div class="col-lg-4">
                              <input type="text" name="data[name]" class="form-control" id="category-name" value="{post_data[name]}" required placeholder="分类名称">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="classify-name" class="col-lg-1 control-label">排序排序</label>
                            <div class="col-lg-4">
                              <input type="text" name="data[weight]" class="form-control" id="category-weight" value="{post_data[weight]}" required placeholder="0-{total}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-1 col-lg-4">
                              <button type="submit" id="btnsubmit" class="btn btn-primary">确认编辑</button>
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
    <!-- Metis Menu Plugin JavaScript -->
    <script src="{static_base_url}js/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{static_base_url}js/sb-admin-2.js"></script>
    <script>
        $(function(){            
        })
        
        function edit(id)
	  		{
	  			window.location.href='edit_item_category?id='+id;
	  		}
	  		
	  		function delete_item(id)
	  		{
					if(confirm("确定要删除本分类？"))
					{
						//ajax删除操作
						var strUrl = 'item_category';
						$.ajax({
						  type: "POST",
						  url: strUrl,
						  data: {ac: 'delete', id: id},
						  success: function(data){
						  	if (data.msg == 'ok')
						  	{
						  			alert("删除成功！");
						  			location.href = strUrl;
						  	}else if (data.msg == 'failed')
						  	{
						  			alert("删除失败！");				  		
						  	}
						  },
						  dataType: 'json'
						});
	        }
	  		}
    </script>
   
</body>

</html>