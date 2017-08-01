<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="tangqiang">

    <title>订单管理 - 哈哈外卖商家后台</title>

    <?php $this->load->view('common_header_template') ?>
</head>

<body>

    <div id="wrapper">

        <?php $this->load->view('common_navigation_template') ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">订单列表</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default" id="seller-order">
                        <div class="panel-heading">
                            <form class="form-inline" method="get">
                            <input type="hidden" name="ac" value="search" >
                              <div class="form-group">
                                <label>订单状态</label>
                                <select name="status" id="status" onchange="location='?ac=search&status='+this.options[this.selectedIndex].value;" class="form-control">
                                    <option value="">全部</option>
                                    <option value="NEW"{if {get_status} ==NEW} selected{/if}>未处理</option>
                                    <option value="WAIT"{if {get_status} ==WAIT} selected{/if}>待配送</option>
                                    <option value="DELIVERY"{if {get_status} ==DELIVERY} selected{/if}>配送中</option>
                                    <option value="COMPLETE"{if {get_status} ==COMPLETE} selected{/if}>已完成</option>
                                    <option value="CANCEL"{if {get_status} ==CANCEL} selected{/if}>已取消</option>
                                </select>
                              </div>
                              <div class="form-group">
                                <label for="orderCode">订单编码</label>
                                <input type="text" class="form-control" name="order_id" id="order_id" value="{order_id}" placeholder="请输入订单编码">
                              </div>
                              <button id="orderSearch" type="submit" class="btn btn-primary">查询</button>
                            </form>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive" id="shopOrder">
                                <table class="table table-striped table-bordered table-hover my-table" id="data_list">
                                    <tr>
                                        <th>ID</th>
                                        <th>下单时间</th>
                                        <th>菜单概要</th>
                                        <th>订单总价</th>
                                        <th>顾客姓名/电话</th>
                                        <th>顾客地址</th>
                                        <th>配送员姓名/电话</th>
                                        <th>配送轨迹</th>
                                        <th>订单状态</th>
                                        <th>支付类型</th>
                                        <th>支付状态</th>
                                        <th>订单详情</th>
                                        <th>操作</th>
                                    </tr>
                                    {data_list}
                                    {if {has_push_sunmi} == 1}
                                    <tr>
                                        <td>{id}</td>
                                        <td>{gmt_create}</td>
                                        <td>{item_list}</td>
                                        <td>${total_price}</td>
                                        <td>{receiver_name}/{receiver_phone}</td>
                                        <td>{receiver_address_detail},{receiver_address}</td>
										<td>{deliver_name}/{deliver_phone}</td>
                                        <td>
                                        {if {show_path} == 1}
                                        <button type="button" class="btn btn-primary btn-look" id="delivery_path">配送轨迹</button>
                                        {/if}
                                        </td>
                                        <td>{str_status}</td>
                                        <td>{str_pay_type}</td>
                                        <td>{str_pay_status}</td>
                                       	<td><a href="view_order?id={id}&deliver_phone={deliver_phone}&deliver_name={deliver_name}">查看</a></td>
                                        <td>
                                        {switch {status}}
                                        {case NEW}
                                        <div>
                                        {if {is_receive} == 1}
                                        <button type="button"  class="btn btn-info get-order" onclick="operate({id},'{status}','')">接单</button>
                                        {/if}
                                        <button type="button"  class="btn btn-warning cancel-start" onclick="operate({id},'CANCEL','')">取消</button>
                                        </div>
                                        {break}
                                        {case WAIT}
                                        <div>                                        
                                        {if {delivery_type} == SELF}
                                        <button type="button"  class="btn btn-primary get-order" onclick="operate({id},'{status}','')">配送确认</button>
                                        {/if}
                                        </div>
                                      <!--   <button type="button"  class="btn btn-warning cancel-start" onclick="operate({id},'CANCEL','0')">取消</button> -->
                                        {break}
                                        {case DELIVERY}
                                        {if {delivery_type} == SELF}
                                        <button type="button"  class="btn btn-primary get-order" onclick="operate({id},'{status}','')">送达确认</button>
                                        {/if}
                                        <!-- <button type="button"  class="btn btn-danger cancel-ing" onclick="operate({id},'CANCEL','1')">取消</button> -->
                                        {break}
                                        {case CANCEL}
                                        <button type="button" disabled class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button>
                                        {break}
               							{case COMPLETE}
										<button type="button" disabled class="btn btn-info btn-circle"><i class="fa fa-check"></i></button>
										{break}
										{case DONE}
										<button type="button" disabled class="btn btn-success btn-circle"><i class="fa fa-check"></i></button>
										{break}
                                        {/switch}                                        
                                        </td>
                                    </tr>
                                    {/if}
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
    
    <!-- 选择google地图 -->
    <div id="map-wrap" class="map-wrap"></div>
    <div id="map-box" class="map-box">
        <div id="map" style="width:100%; height: 500px; border: 1px solid black;background:#efefef;"></div>
    </div>

    <!-- jQuery -->
    <script src="{static_base_url}js/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{static_base_url}js/bootstrap.min.js"></script>
    <script src="{static_base_url}js/layer/layer.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="{static_base_url}js/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{static_base_url}js/sb-admin-2.js"></script>
    <script>
        $(function(){
      //   		setTimeout("ajax_order_list()",120000);// was 120000
      //   		$("#seller-order").on("click","#delivery_path",function(){
						// 		$("#map-box,#map-wrap").show();
						// });
        });
        
        $("#map-wrap").click(function(){
                $("#map-box,#map-wrap").hide();
        });    
        
        function ajax_order_list() {
				    //ajax调用订单列表
				    var str_html = '',str_list = '';
						var strUrl = 'order{query_string}';
						$.ajax({
						  type: "GET",
						  url: strUrl,
						  data: {isajax: 1},
						  success: function(data){
						  	if (data.msg == 'ok')
						  	{
						  			for(var i=0, ien=data.data_list.length ; i<ien ; i++ )
						  			{
						  					str_list = '<tr><td>'+data.data_list[i]['id']+'</td><td>'+data.data_list[i]['gmt_create']+'</td><td>'+data.data_list[i]['item_list']+'</td><td>$'+data.data_list[i]['total_price']
                                                +'</td><td>'+data.data_list[i]['receiver_name']+'/'+data.data_list[i]['receiver_phone']+'</td>'
                                                +'</td><td>' + data.data_list[i]['receiver_address_detail'] + ',' + data.data_list[i]['receiver_address'] + '</td>' 
                                                +'</td><td>'+data.data_list[i]['deliver_name']+'/'+data.data_list[i]['deliver_phone']+'</td><td>';
						  					
											if (data.data_list[i]['show_path']) str_list += '<button type="button" class="btn btn-primary btn-look" id="delivery_path">配送轨迹</button>';
											//Joe
						  					str_list += '</td><td>'+data.data_list[i]['str_status']+'</td><td>'+data.data_list[i]['str_pay_type']+'</td><td>'+data.data_list[i]['str_pay_status']+'</td><td><a href="view_order?id='+data.data_list[i]['id']+'&deliver_phone='+data.data_list[i]['deliver_phone'] +'&deliver_name='+data.data_list[i]['deliver_phone'] +'">查看</a></td><td>';
											//
						  					switch(data.data_list[i]['status'])
						  					{
						  							case 'NEW':
						  								if (data.data_list[i]['is_receive'] == 1)
						  								{
								  								str_list += '<button type="button"  class="btn btn-primary get-order" onclick="operate('+data.data_list[i]['id']+',\'NEW\',\'\')">接单</button>';
						  								}
						  								str_list += '<button type="button"  class="btn btn-warning cancel-start" onclick="operate('+data.data_list[i]['id']+',\'CANCEL\',\'0\')">取消</button></td></tr>';
						  							break;
						  							case 'WAIT':
						  								if (data.data_list[i]['delivery_type'] == 'SELF')
						  								{
                              		str_list += '<button type="button"  class="btn btn-primary get-order" onclick="operate('+data.data_list[i]['id']+',\''+data.data_list[i]['status']+'\',\'\')">配送确认</button>';
                            	}
                            	// str_list += '<button type="button"  class="btn btn-warning cancel-start" onclick="operate('+data.data_list[i]['id']+',\'CANCEL\',\'0\')">取消</button>';
						  							break;
						  							case 'DELIVERY':
						  								 if (data.data_list[i]['delivery_type'] == 'SELF')
						  							   {
						  								str_list += '<button type="button"  class="btn btn-primary get-order" onclick="operate('+data.data_list[i]['id']+',\''+data.data_list[i]['status']+'\',\'\')">送达确认</button>';
						  								}
                            	// str_list += '<button type="button"  class="btn btn-danger cancel-ing" onclick="operate('+data.data_list[i]['id']+',\'CANCEL\',\'1\')">取消</button>';
						  							break;
						  							case 'CANCEL':
						  								str_list += '<button type="button" disabled class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button>';
						  							break;
						  							case 'COMPLETE':
						  								str_list += '<button type="button" disabled class="btn btn-info btn-circle"><i class="fa fa-check"></i></button>';
						  							break;
													case 'DONE':
						  								str_list += '<button type="button" disabled class="btn btn-success btn-circle"><i class="fa fa-check"></i></button>';
						  							break;
						  					}
						  					str_html +=  str_list;
						  			}
						  			$("#data_list  tr:not(:first)").html("");
						  			$('#data_list').append(str_html);
						  	}else if (data.msg == 'failed')
						  	{
						  			alert("请求数据异常");
						  	}
						  },
						  dataType: 'json'
						});
				    setTimeout("ajax_order_list()", 4000);
				}
        
        function view(id)
	  		{
	  				window.location.href='view_order?id='+id;
	  		}
	  		
	  		function operate(id,status,cont)
	  		{
		  			switch(status)
		  			{
		  					case 'NEW':
		  							window.location.href='operate_order?id='+id;
		  					break;
		  					case 'WAIT':
		  							operate_ajax_wait(id);
		  					break;
		  					case 'DELIVERY':
		  							operate_ajax_delivery(id);
		  					break;
		  					case 'CANCEL':
		  							operate_ajax_cancel(id,cont);
		  					break;
		  			}
	  		}
	  		
	  		function operate_ajax_wait(id)
	  		{
	  				var str_message = '';
	  				str_message = '<div>请确认餐品已打包好，确认配送？</div>';
	  				layer.confirm(str_message, {
              btn: ['确定','取消']
            }, function(){
							//ajax删除操作
							var strUrl = 'order';
							$.ajax({
							  type: "POST",
							  url: strUrl,
							  data: {ac: 'update', status: 'DELIVERY', id: id},
							  success: function(data){
							  	if (data.msg == 'ok')
							  	{
							  			layer.msg("配送确认成功！",{icon: 1,shade: 0.3,time:2000},function(){
								  					window.location.reload(true);
								  		});
							  	}else if (data.msg == 'failed')
							  	{							  			
							  			layer.msg('配送确认失败！', {icon: 2,shade: 0.3,time:1000});			  		
							  	}
							  },
							  dataType: 'json'
							});
		        });
	  		}
	  		
	  		function operate_ajax_cancel(id,cont)
	  		{
	  				var str_message = '';
	  				if (cont != '1')
	  				{
	  						str_message = '请先和顾客沟通，没有问题再取消，确认取消订单吗？';
	  				}else
	  				{
	  						str_message = '危险：请先和顾客、配送员沟通，没有问题再取消！确认取消订单吗？';
	  				}	  				
	  				layer.confirm(str_message, {
              btn: ['确定','取消']
            }, function(){
							//ajax删除操作
							var strUrl = 'order';
							$.ajax({
							  type: "POST",
							  url: strUrl,
							  data: {ac: 'update', status: 'CANCEL', id: id},
							  success: function(data){
							  	if (data.msg == 'ok')
							  	{
							  			//console.log(data);
							  			layer.msg("成功取消订单！",{icon: 1,shade: 0.3,time:2000},function(){
								  					//window.location.reload(true);
								  		});
							  	}else if (data.msg == 'failed')
							  	{
							  			layer.msg('取消订单失败', {icon: 2,shade: 0.3,time:1000});		  		
							  	}
							  },error:function(response,err){

							  	alert(err);
							  },
							  dataType: 'json'
							});
		        });
	  		}
	  		
	  		function operate_ajax_delivery(id)
	  		{
	  				var str_message = '订单ID:'+id+'<br/>请确认餐品已经送到，确认交易完成？';
	  				layer.confirm(str_message, {
              btn: ['确定','取消']
            }, function(){	  				
							//ajax删除操作
							var strUrl = 'order';
							$.ajax({
							  type: "POST",
							  url: strUrl,
							  data: {ac: 'update', status: 'DONE', id: id},
							  success: function(data){
							  	if (data.msg == 'ok')
							  	{
							  			layer.msg("送达确认成功！",{icon: 1,shade: 0.3,time:2000},function(){
								  					window.location.reload(true);
								  		});							  			
							  	}else if (data.msg == 'failed')
							  	{
							  			layer.msg('送达确认失败！', {icon: 2,shade: 0.3,time:1000});			  		
							  	}
							  },
							  dataType: 'json'
							});
		        });
	  		}
	  		
    </script>
   
</body>

</html>