<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="tangqiang">

    <title>查看订单 - 哈哈外卖商家后台</title>

    <?php $this->load->view('common_header_template') ?>
</head>

<body>

    <div id="wrapper">

        <?php $this->load->view('common_navigation_template') ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                      <li><a href="order">订单列表</a></li>
                      <li class="active">订单详情</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                      <div class="panel-heading">订单详情</div>
                      <div class="panel-body">
                          <div class="order-view" id="order-view">
                            <div style="padding-left:100px;">
                                    <div>订单ID：{row[id]}</div>
                                    <div>下单时间：{row[gmt_create]}</div>
                                    <div>订单状态：{row[str_status]}
                            {switch {row[status]}}
                            {case NEW}
                            {if {row[is_receive]} == 1}
                            <button type="button"  class="btn btn-primary get-order" onclick="operate({row[id]},'{row[status]}','')">接单</button>
                            {/if}
                            <button type="button"  class="btn btn-warning cancel-start" onclick="operate({row[id]},'CANCEL','0')">取消</button>
                            {break}
                            {case WAIT}                                        
                            {if {row[delivery_type]} == SELF}
                            <button type="button"  class="btn btn-primary get-order" onclick="operate({row[id]},'{row[status]}','')">配送确认</button>
                            {/if}
                            <!-- <button type="button"  class="btn btn-warning cancel-start" onclick="operate({row[id]},'CANCEL','0')">取消</button> -->
                            {break}
                            {case DELIVERY}
                            {if {row[delivery_type]} == SELF}
                            <button type="button"  class="btn btn-primary get-order" onclick="operate({row[id]},'{row[status]}','')">送达确认</button>
                            {/if}
                            <!-- <button type="button"  class="btn btn-danger cancel-ing" onclick="operate({row[id]},'CANCEL','1')">取消</button> -->
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
                            </div>
                            </div>
                            <div style="margin-top:20px;height:20px;border-top:1px solid #ccc;"></div>
                            <div style="color:#000;"><h4>支付信息</h4></div>
                            <div style="padding-left:100px;">
                                    <div>支付方式：{row[str_pay_type]}</div>
                                    <div>支付状态：{row[str_pay_status]}</div>
                                    <div></div>
                            </div>
                            <div style="margin-top:20px;height:20px;border-top:1px solid #ccc;"></div>
                            <div style="color:#000;"><h4>商家信息</h4></div>
                            <div style="padding-left:100px;">
                                    <div>商家名称：{row[seller_name]}</div>
                                    <div>电话：{row[seller_phone]}</div>
                                    <div>地址：{row[seller_address_detail]},{row[seller_address]}</div>
                            </div>
                            <div style="margin-top:20px;height:20px;border-top:1px solid #ccc;"></div>
                            <div style="color:#000;"><h4>顾客信息</h4></div>
                            <div style="padding-left:100px;">
                                    <div>收货地址：{row[receiver_address_detail]},{row[receiver_address]}</div>
                                    <div style="color:red;">Sydney&nbsp;&nbsp;City Zetland Waterloo</div>
                                    <div>顾客姓名：{row[receiver_name]}</div>
                                    <div>电话：{row[receiver_phone]}</div>
                            </div>
                            <div style="margin-top:20px;height:20px;border-top:1px solid #ccc;"></div>
                            <div style="color:#000;"><h4>餐品信息</h4></div>
                            <div style="padding-left:100px;">
                                    <div>商品总价(优惠后)：AUD{row[total_price]}</div>
                                    <div>配送费：AUD{row[delivery_price]}</div>
                                    <div>支付总额(包含配送费)：AUD{row[order_total_price]}</div>
                                    <div>优惠总额：AUD{row[discount_price]}</div>
                                    <div><label>餐品明细</label></div>
                                    <ul class="food-list">
                                        {data_list}
                                    <li style="font-weight:bold;">
                                        <span>{item_name}</span>
                                        <span style="color:blue;">x {number}</span>
                                        <span>AUD{item_price}</span>
                                        <span>= AUD{item_price_total}</span>
                                    </li>
                                    {/data_list}                                    
                                </ul>
                                <div>顾客备注：<span style="color:red;"></span></div>
                                <div>备餐时间：<span style="color:blue;">预留</span></div>
                                <div>打印状态：<span style="color:blue;">预留</span></div>
                            </div>
                            <div style="margin-top:20px;height:20px;border-top:1px solid #ccc;"></div>
                            <div style="color:#000;"><h4>配送信息</h4></div>
                            <div style="padding-left:100px;">
                                    <div>配送方式：{row[str_delivery_type]}</div>
                                    <div>配送编号：{row[delivery_code]}<!-- <span style="color:#ccc;">(编码：{row[delivery_code]})</span> --></div>
                                    <div>预计出发时间：{row[delivery_time]}</div>
                                    <div>配送员姓名/电话：{row[deliver_name]}/{row[deliver_phone]} </div>
                                    <div>配送轨迹：<button type="button" class="btn btn-primary btn-look">查看</button></div>
                                    <div>配送备注：{row[delivery_note]}</div>    
                                                                    
                            </div>
                            <div style="color:#000;"><h4>日志</h4>
                            </div>
                            <div style="padding-left:100px;">
                               <div style = "color:#DC143C;"> {row[balance_logs]} </div>
                            </div>                          
                          </div>
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
    <script src="{static_base_url}/js/layer/layer.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="{static_base_url}js/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{static_base_url}js/sb-admin-2.js"></script>
    <script>
        $(function(){            
        });
        
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
							  			layer.msg("成功取消订单！",{icon: 1,shade: 0.3,time:2000},function(){
								  					window.location.reload(true);
								  		});
							  	}else if (data.msg == 'failed')
							  	{
							  			layer.msg('取消订单失败', {icon: 2,shade: 0.3,time:1000});		  		
							  	}
							  },
                              error:function(err)
                              {
                                console.log(err.responseText);
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