<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="tangqiang">

    <title>商品分类管理 - 哈哈外卖商家后台</title>

    <?php $this->load->view('common_header_template') ?>
</head>

<body>

    <div id="wrapper">

        <?php $this->load->view('common_navigation_template') ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">商品分类</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive" id="goods-list">
                        <table class="table table-striped table-bordered table-hover my-table">
                            <tr>
                                <th>编号</th>
                                <th>分类名称</th>
                                <th>添加时间</th>
                                <th style='width:10%'>排序（顺序权重 数值越大,排名越靠前）</th>
                                <th>操作</th>
                            </tr>
                            {data_list}
                            <tr>
                                <td>{id}</td>
                                <td>{name}</td>
                                <td>{gmt_create}</td>
                                <td >{weight}</td>
                                <td>
                                		{if {parent_id} == null}
                                		默认分类
                                		{else}                    		
                                    <button type="button" class="btn btn-circle btn-info btn-edit" onclick="edit({id})"><i class="fa fa-edit fa-fw"></i></button>
                                    <button type="button" class="btn btn-circle btn-danger btn-delete" onclick="delete_item({id})"><i class="fa fa-trash-o fa-fw"></i></button>
                                    {/if}
                                </td>
                            </tr>
                            {/data_list}                                                    
                        </table>
                    </div>
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
						  	}else if (data.msg == 'error')
						  	{
						  			alert("删除失败！" + data.content);				  		
						  	}
						  },
						  dataType: 'json'
						});
	        }
	  		}
    </script>
   
</body>

</html>