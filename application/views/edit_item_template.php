<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="tangqiang">

    <title>编辑商品 - 哈哈外卖商家后台</title>

    <?php $this->load->view('common_header_template') ?>
    <link href="{static_base_url}uploadifive/uploadifive.css" rel="stylesheet">
        
</head>

<body>
    <div id="wrapper">

        <?php $this->load->view('common_navigation_template') ?>

         <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                      <li><a href="item">商品列表</a></li>
                      <li class="active">编辑商品</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                        		<div style="color:red;">{validation_errors}</div>
                    				<div style="color:#669933;">{result_success}</div>
                            <form id="add-goods" class="form-horizontal" method="post">
                            <input type="hidden" name="data[file_list]" id="file_list" value="{post_data[file_list]}">
                              <div class="form-group">
                                <label for="goods-name" class="col-lg-1 control-label">商品名称</label>
                                <div class="col-lg-4">
                                  <input type="text" name="data[name]" class="form-control" id="goods-name" value="{post_data[name]}" required placeholder="商品名称">
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="goods-type" class="col-lg-1 control-label">所属类目</label>
                                <div class="col-lg-2">
                                  <select name="data[category_id]" id="goods-category-id" class="form-control">
                                    <option value="0">全部</option>
                                    {make_form_select_shop_item_category}
                                  </select>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="goods-price" class="col-lg-1 control-label">商品单价</label>
                                <div class="col-lg-4">
                                  <input type="text" name="data[price]" class="form-control" id="goods-price" value="{post_data[price]}" required placeholder="商品单价">
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="goods-count" class="col-lg-1 control-label">商品数量</label>
                                <div class="col-lg-4">
                                  <input type="number" min="1" name="data[number]" class="form-control" id="goods-number" value="{post_data[number]}" required placeholder="商品数量">
                                </div>
                              </div>
							  <div class="form-group">
                                <label for="goods-name" class="col-lg-1 control-label">商品推荐值</label>
                                <div class="col-lg-4">
                                  <input type="text" name="data[weight]" class="form-control" id="goods-weight" value="{post_data[weight]}" required placeholder="商品推荐值">
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="col-lg-1 control-label">优惠券</label>
                                <div class="col-lg-4">
                                   <input type="radio"name="data[coupon]" id="coupon1"{post_data[coupon]1} value="1">&nbsp;&nbsp;<label for="coupon1">可用</label>&nbsp;&nbsp;&nbsp;&nbsp;
                                   <input type="radio" name="data[coupon]" id="coupon2"{post_data[coupon]0}  value="0">&nbsp;&nbsp;<label for="coupon2">不可用</label>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="goods-content" class="col-lg-1 control-label">商品介绍</label>
                                <div class="col-lg-4">
                                  <textarea name="data[content]" class="form-control" id="goods-content" placeholder="简要介绍本商品">{post_data[content]}</textarea>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="goods-pic" class="col-lg-1 control-label">商品图片</label>
                                <div class="col-lg-2">
                                  <input type="file" name="file_upload" id="file_upload" multiple="true">
                                  <div id="queue" style="margin-top:10px;width:300px;"></div>
                                </div>
                              </div>
                              {if {pic_uploaded} == true}
                              <div class="form-group">
                                <div class="col-lg-offset-1">
                                		{file_list}
                                    <div class="col-xs-6 col-md-4 col-lg-2">
                                        <a href="{image_url}" target="_blank" class="thumbnail">
                                            <img  data-src="{image_url}" alt="{image_url}"  src="{image_url}" data-holder-rendered="true">
                                        </a>
                                    </div>
                                    {/file_list}                               
                                </div>
                              </div>
                              {/if}
                              <div class="form-group">
                                <div class="col-lg-offset-1">
                                		{upload_file_list}
                                    <div class="col-xs-6 col-md-4 col-lg-2">
                                        <a href="{image_url}" target="_blank" class="thumbnail">
                                            <img  data-src="{image_url}" alt="{image_url}"  src="{image_url}" data-holder-rendered="true">
                                        </a>
                                    </div>
                                    {/upload_file_list}                               
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-lg-offset-1 col-lg-4">
                                  <button type="submit" id="goods-save" class="btn btn-primary">确认编辑</button>
                                </div>
                              </div>
                            </form>
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
    <script src="{static_base_url}uploadifive/jquery.uploadifive.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="{static_base_url}js/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{static_base_url}js/sb-admin-2.js"></script>
    <script>
    		Array.prototype.delete = function(v) 
				{ 
						var numDeleteIndex = -1; 
						for(var i=0;i < this.length;i++) 
						{ 
								if (this[i] === v) 
				        { 
						        this.splice(i,1); 
						        numDeleteIndex = i; 
						        break; 
				        }
						} 
						return numDeleteIndex; 
				}
				
				$(function() {
					var fileArray = Array();
					var fidArray = Array();
					var file_list = $("#file_list").val();
					if (file_list != '')
					{
							fileArray = file_list.split(',');
							for(i=0;i<fileArray.length;i++)
							{
									fidArray.push('');
							}
							console.log(fileArray);
							console.log(fidArray);
					}
					$('#file_upload').uploadifive({
						'auto'             : true,
						'queueID'					 : 'queue',
						'buttonText'   		 : '上传图片',		
						'uploadScript'     : 'uploadifive?mod=add_item&ac=upload',
						'onUploadComplete' : function(file, data){
							var result = data.split('|');
							if (result[0])
							{
									fileArray.push(result[1]);
									fidArray.push(file.name);
									$("#file_list").attr("value",fileArray.toString());
							}							
						},
						'onCancel'     		 : function(file){
								//数组删除操作		            
		            var fname = file.name;
		            var fidindex = fidArray.delete(fname);
		            var fpath = fileArray[fidindex];		            
		            var fileindex = fileArray.delete(fpath);
		            //删除文件操作
		            //ajax删除操作
								var strUrl = 'uploadifive?mod=add_item&ac=delete&path='+fpath;
								$.ajax({
								  type: "GET",
								  url: strUrl,
								  data: {},
								  success: function(data){
								  	if (data.msg == 'ok')
								  	{
								  			//alert("删除成功！");
								  	}
								  },
								  dataType: 'json'
								});
		            if (fileArray.toString() != '')
								{
										$("#file_list").attr("value",fileArray.toString());
								}else
								{
										$("#file_list").attr("value","");
								}
		        }
					});
				}); 
    </script>
   
</body>

</html>