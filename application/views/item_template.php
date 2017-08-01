<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="tangqiang">

    <title>商品管理 - 哈哈外卖商家后台</title>

    <?php $this->load->view('common_header_template') ?>
</head>

<body>

    <div id="wrapper">

        <?php $this->load->view('common_navigation_template') ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">商品列表</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <form class="form-inline" method="get">
                            <input type="hidden" name="ac" value="search" >
                              <div class="form-group">
                                <label>商品类目</label>
                                <select name="category_id" id="goods-category_id" onchange="location='?ac=search&category_id='+this.options[this.selectedIndex].value;" class="form-control">
                                    <option value="0">全部</option>
                                    {make_form_select_shop_item_category}
                                </select>
                              </div>
                              <div class="form-group">
                                <!-- <label for="orderCode">搜索商品</label> -->
                                <input type="text" class="form-control" name="keyword" id="keyword" value="{keyword}" placeholder="输入关键字查询商品">
                              </div>
                              <button id="orderSearch" type="submit" class="btn btn-primary">查询</button>
                            </form>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive" id="goods-list">
                                <table class="table table-striped table-bordered table-hover my-table">
                                    <tr>
									    <th>商品ID</th>
                                        <th>商品缩略图</th>
                                        <th>商品名称</th>
                                        <th>商品价格</th>
                                        <th>添加时间</th>
                                        <th>操作</th>
                                    </tr>
                                    {data_list}
                                    <tr>
									    <td>{id}</td>
                                        <td class="goods-img"><img src="{image_url}" alt=""></td>
                                        <td>{name}</td>
                                        <td>${price}</td>
                                        <td>{gmt_create}</td>
                                        <td>
                                            <button type="button" class="btn btn-circle btn-primary btn-eye" onclick="view({id})"><i class="fa fa-eye fa-fw"></i></button>
                                            <button type="button" class="btn btn-circle btn-info btn-edit" onclick="edit({id})"><i class="fa fa-edit fa-fw"></i></button>
                                            <button type="button" class="btn btn-circle btn-danger btn-delete" onclick="delete_item({id})"><i class="fa fa-trash-o fa-fw"></i></button>
                                        </td>
                                    </tr>
                                    {/data_list}                                    
                                </table>
                                <!-- /.table-responsive -->
                            </div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                        		{create_links}                            
                        </div>
                    </div>
                    <!-- /.panel -->
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
        
        function view(id)
	  		{
	  			window.location.href='view_item?id='+id;
	  		}
	  		
        function edit(id)
	  		{
	  			window.location.href='edit_item?id='+id;
	  		}
	  		
	  		function delete_item(id)
	  		{
					if(confirm("确定要删除本商品？"))
					{
						//ajax删除操作
						var strUrl = 'item';
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