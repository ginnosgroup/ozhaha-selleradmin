<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="tangqiang">

    <title>店铺设置 - 哈哈外卖商家后台</title>

    <?php $this->load->view('common_header_template') ?>
</head>

<link href="{static_base_url}css/datetimepicker.css" rel="stylesheet">
<link href="{static_base_url}css/easyui-themes/metro/easyui.css" rel="stylesheet" />
<link href="{static_base_url}css/easyui-themes/icon.css" rel="stylesheet" />

<body>

    <div id="wrapper">

        <?php $this->load->view('common_navigation_template') ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">店铺设置</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body" id="seller-shop">
                        		<div style="color:red;">{validation_errors}</div>
                        		<div style="color:#669933;">{result_success}</div>
                            <form id="shop-setting" class="form-horizontal" method="post" enctype="multipart/form-data">
                              <div class="form-group">
                                <label for="shop-name" class="col-lg-1 control-label">餐厅名称</label>
                                <div class="col-lg-4">
                                  <input type="text" name="data[name]" class="form-control" id="shop-name" value="{post_data[name]}" required placeholder="餐厅名称">
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="shop-logo" class="col-lg-1 control-label">餐厅LOGO</label>
                                <div class="col-lg-2">
                                	<input type="file" name="shop-logo" class="form-control" id="shop-logo">
                                	{post_data[shop-logo]}
                                	<div></div>
                                </div>                                
                              </div>
                              <div class="form-group">
                                <label for="shop-cover" class="col-lg-1 control-label">餐厅封面</label>
                                <div class="col-lg-2">
                                  <input type="file" name="shop-cover" class="form-control" id="shop-cover">
                                  {post_data[shop-cover]}
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="shop-type" class="col-lg-1 control-label">餐厅类型</label>
                                <div class="col-lg-6">
                                  {make_form_seller_category}                                  
                                </div>
                              </div>
                                <div class="form-group">
                                <label for="shop-region" class="col-lg-1 control-label">餐厅区域</label>
                                <div class="col-lg-4">
                                  <input id="shop-region" style="width:100%;max-width:100%;" class="easyui-combobox" name="data[seller_region_id]"  class="form-control" value="{post_data[seller_region_id]}" data-options="valueField:'id',textField:'name',editable:false, groupField:'group',url:'shop/seller_regions'"><!-- ,formatter:formatRegion -->
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="shop-address" class="col-lg-1 control-label">餐厅地址</label>
                                <div class="col-lg-4">
                                  <input type="text" name="data[address]" class="form-control" id="shop-address" value="{post_data[address]}" onFocus="geolocate()" required placeholder="餐厅地址">
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="shop-coordinate" class="col-lg-1 control-label">餐厅坐标</label>
                                <div class="col-lg-4">
                                  <input type="text" name="data[coordinate]" class="form-control" readonly id="shop-coordinate" value="{post_data[coordinate]}" placeholder="选择餐厅坐标">
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <label for="shop-phone" class="col-lg-1 control-label">餐厅电话</label>
                                <div class="col-lg-4">
                                  <input type="tel" name="data[phone]" class="form-control" id="shop-phone" value="{post_data[phone]}" placeholder="请输入联系电话">
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="shop-phone" class="col-lg-1 control-label">E-mail</label>
                                <div class="col-lg-4">
                                  <input type="tel" name="data[email]" class="form-control" id="shop-email" value="{post_data[email]}" placeholder="请输入E-mail地址">
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="shop-status" class="col-lg-1 control-label">餐厅状态</label>
                                <div class="col-lg-2">
                                  <select name="data[state]" id="shop-state" class="form-control">
                                    <option value="1"{post_data[state]1}>正在营业</option>
                                    <option value="0"{post_data[state]0}>打样</option>
                                  </select>
                                </div>
                              </div>
                               <div class="form-group">
                                <label for="shop-hours" class="col-lg-1 control-label">休息日选择</label>
                                <div class="col-lg-6">
                                    {make_rest_days_form}
                                </div>
                                </div>
                              <div class="form-group">
                                <label for="shop-hours" class="col-lg-1 control-label">营业时间</label>
                                <div class="col-lg-4">
                                <div><span class='col-lg-9' style='padding:0;'><input type="text" name="data[hours]" class="form-control" id="shop-hours" value="{post_data[hours]}" required placeholder="餐厅营业时间" readonly="readonly"></span><span><a class=' col-lg-3 btn btn-warning' id='tradeHoursCancel'>CANCEL</a></span></div>
                                <div id ="trade-hours" style='padding:0;'><span class="col-lg-4" style='padding:0;'><input class='form-control'  name='business-strtHrs' readonly="readonly" placeholder="开始时间"></span> <span class="col-lg-1" style="margin-top: 9px;text-align: center;">  - </span><span class="col-lg-4" style='padding:0;'><input class='form-control' name='business-endHrs' readonly="readonly" placeholder="结束时间"></span><span><a class ='btn btn-primary col-lg-3' id ='addTradeHours'>Add</a></span></div>
                                 <div class="col-lg-12" style='padding-left:0;'><label style ='font-size:8px;'>输入格式为'10:00-2:00'。如果多时段格式为'10:00-2:00,17:00-21:00'</label></div>
                                </div>

                              </div>
                              <div class="form-group">
                                <label for="shop-hours" class="col-lg-1 control-label">最低起送价</label>
                                <div class="col-lg-4">
                                  <input type="text" name="data[lowest_price]" class="form-control" id="shop-hours" value="{post_data[lowest_price]}" required placeholder="订单最低起送价">
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="shop-delivery-type" class="col-lg-1 control-label" >配送方式</label>
                                <div class="col-lg-2">
                                  <select name="data[delivery_type]" id="shop-delivery-type" class="form-control">
	                                   <option value="PANDA" selected="selected" {post_data[delivery_type]1}>哈哈网配送</option>
                                      <!-- <option value="SELF"{post_data[delivery_type]0}>商家自己配送</option> -->
                                  </select>
                                </div>
                              </div>                              
                              <div class="form-group">
                                <label for="shop-notice" class="col-lg-1 control-label">餐厅公告</label>
                                <div class="col-lg-4">
                                  <textarea name="data[notice]" class="form-control" id="shop-notice" placeholder="请输入餐厅公告">{post_data[notice]}</textarea>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-lg-offset-1 col-lg-4">
                                  <button type="submit" name="btn-submit" id="btn-submit" class="btn btn-primary">保存设置</button>
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
<style>  
  


  #trade-hours input {

    border-top:0;
    border-radius:0;
    margin:0;
  }

#trade-hours input[name='business-strtHrs']
{
   border-radius:0 0 0 4px;

}

#trade-hours a.btn-primary {
  
  border-radius:0 0 4px 0;
  margin:0;

}
a.btn-warning#tradeHoursCancel {

  border-radius:0 4px 0 0;
  margin:0;

}

#shop-hours {
border-radius:4px 0 0 0;

}
/*.textbox.easyui-fluid.combo{

  width: 100%;!important;
}*/

</style>  
    <!-- /#wrapper -->
    
    <!-- 选择google地图 -->
    <div id="map-wrap" class="map-wrap"></div>
    <div id="map-box" class="map-box">
        <div id="map" style="width:100%; height: 500px; border: 1px solid black;"></div>
    </div>

    <!-- jQuery -->
    <script src="{static_base_url}js/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{static_base_url}js/bootstrap.min.js"></script>
    <script src="{static_base_url}js/datetimepicker.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="{static_base_url}js/metisMenu.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{static_base_url}js/jquery.easyui.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{static_base_url}js/sb-admin-2.js"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key=AIzaSyB6sJQ9An1LGwKG_1YAv7x-Ke6N71CgAXU&callback=initAutocomplete" async defer></script> 
 
    <script>
	$(function() {

		      // $('# shop-region').combobox({
        //           formatter:function(row){

        //           }

        //   });
						
						$("#seller-shop").on("click","#shop-coordinate",function(){
								$("#map-box,#map-wrap").show();
								var map;
						    var marker;
						    var infowindow;
						    var geocoder;
						    var markersArray = [];
						    var strlng = '';
						    var strlat = '';
						    var currentLat = '';
								var currentLng = '';
                
														    
						    function initialize()
						    {
						        //设置中心点
						        var latlng ;
                    var currPos = shopCoodinates();
                    if(currPos)
                    {
                      latlng = currPos;
                    }
                    else{
                    latlng = new google.maps.LatLng(currentLat, currentLng);
                    }
						        var myOptions = {
						            zoom: 13,
						            center: latlng,
						            mapTypeId: google.maps.MapTypeId.ROADMAP
						        };
						        map = new google.maps.Map(document.getElementById("map"), myOptions);
                    var marker = new google.maps.Marker({
                                 position: latlng,
                                 map: map,
                                 title: 'Current Position',
                                 icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
                                });
						        geocoder = new google.maps.Geocoder();
						        //监听点击地图事件
						        google.maps.event.addListener(map, 'click', function (event) {
                       placeMarker(event.latLng);
						        });

						    }
						    
						    function placeMarker(location) {
						       clearOverlays(infowindow);//清除地图中的标记
						        marker = new google.maps.Marker({
						            position: location,
						            map: map
						        });
						        markersArray.push(marker);
						        //根据经纬度获取地址
						        if (geocoder) {
						            geocoder.geocode({ 'location': location }, function (results, status) {
						                if (status == google.maps.GeocoderStatus.OK) {
						                    if (results[0]) {
						                        attachSecretMessage(marker, results[0].geometry.location, results[0].formatted_address);
						                    }
						                } else {
						                    alert("Geocoder failed due to: " + status);
						                }
						            });
						        }
						    }
						    //在地图上显示经纬度地址
						    function attachSecretMessage(marker, piont, address) {
						        var message = "<b>座標:</b>" + piont.lat() + " , " + piont.lng() + "<br />" + "<b>地址:</b>" + address;
						        var infowindow = new google.maps.InfoWindow(
						            {
						                content: message,
						                size: new google.maps.Size(50, 50)
						            });
						        infowindow.open(map, marker);
						        if (typeof (mapClick) == "function") mapClick(piont.lng(), piont.lat(), address);
						    }
						    //删除所有标记阵列中消除对它们的引用
						    function clearOverlays(infowindow) {
						        if (markersArray && markersArray.length > 0) {
						            for (var i = 0; i < markersArray.length; i++) {
						                markersArray[i].setMap(null);
						            }
						            markersArray.length = 0;
						        }
						        if (infowindow) {
						            infowindow.close();
						        }
						    }
						    
						    function setiInit() {
						        // 页面加载显示默认lng,lat
						        var lattxt = currentLat;
						        var lngtxt = currentLng;
						        var addresstxt = ' ';
						        if (lattxt != '' && lngtxt != '' && addresstxt != '') {
						            var latlng = new google.maps.LatLng(lattxt, lngtxt);
						            marker = new google.maps.Marker({
						                position: latlng,
						                map: map
						            });
						            markersArray.push(marker);
						            attachSecretMessage(marker, latlng, addresstxt);
						        }
						    }
						    function mapClick(lng, lat, address)
						    {
						        currentLng = lng;
						        currentLat = lat;
						        currentAdd = address;
						        $("#shop-coordinate").val(lat+','+lng);
						        $("#shop-address").val(address);
						    }
						    
						    if (navigator.geolocation) {
								    navigator.geolocation.getCurrentPosition(locationSuccess, locationError,{
								        // 指示浏览器获取高精度的位置，默认为false
								        enableHighAcuracy: true,
								        // 指定获取地理位置的超时时间，默认不限时，单位为毫秒
								        timeout: 5000,
								        // 最长有效期，在重复获取地理位置时，此参数指定多久再次获取位置。
								        maximumAge: 3000
								    });
								}else{
								    alert("Your browser does not support Geolocation!");
								}

								function locationError(error){
								    switch(error.code) {
								        case error.TIMEOUT:
								            showError("A timeout occured! Please try again!");
								            break;
								        case error.POSITION_UNAVAILABLE:
								            showError('We can\'t detect your location. Sorry!');
								            break;
								        case error.PERMISSION_DENIED:
								            showError('Please allow geolocation access for this to work.');
								            break;
								        case error.UNKNOWN_ERROR:
								            showError('An unknown error occured!');
								            break;
								    }
								}

								function locationSuccess(position)
								{
								    var coords = position.coords;    								    
								    currentLat = coords.latitude;
								    currentLng = coords.longitude;
								    initialize();
						    		setiInit();
								}

								function showError(info)
								{
										alert(info);
								}
                
						    
						
			       $("#map-wrap").click(function(){
                $("#map-box,#map-wrap").hide();
            });
      				});
           //End

           //Joe 
           // to standardise trading hours format 
            $('#tradeHoursCancel').on('click',function(){
              $('#tradeHoursCancel').prop('disabled', true);
              var cancelStr ='';
              var crrtSttHrs = $('#shop-hours').val();
              var hoursArray = crrtSttHrs.split(',');
              for(var i=0;i<hoursArray.length-1;i++)
              {
                cancelStr += hoursArray[i];
              }
              $('#shop-hours').val(cancelStr);
              $('#tradeHoursCancel').prop('disabled', false);
            });

            $('#addTradeHours').on('click', function(){
             var strhrs ='';
             var crrtSttHrs= $('#shop-hours').val();
             var strtHrs = $('input[name = "business-strtHrs"]').val();
             var endHrs = $('input[name = "business-endHrs"]').val();
             
             if(crrtSttHrs.indexOf('-') == -1)
             {
                crrtSttHrs = '';
             }

             if(strtHrs&&endHrs)
              { 
                if(crrtSttHrs) strhrs += crrtSttHrs + ',';
                strhrs += strtHrs + '-' + endHrs;
              } 

             if(strhrs)
             $('#shop-hours').val(strhrs);
             else
             alert('added failed, you must input both start time and end time to add');
             $('input[name = "business-strtHrs"]').val('');
             $('input[name = "business-endHrs"]').val('');
            });

            $('input[name = "business-strtHrs"]').datetimepicker({
              format: 'hh:ii',
              autoclose: true,
              startView: 1
          }); 

            $('input[name = "business-endHrs"]').datetimepicker({
              format: 'hh:ii',
              autoclose: true,
              startView: 1
          }); 
       //End

});
    
	
			// This example displays an address form, using the autocomplete feature
      // of the Google Places API to help users fill in the information.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      var autocomplete;

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('shop-address')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        if(place)
        {
          var currPos = place.geometry.location;
           writeToField(currPos.lat(), currPos.lng());
        }
      }
	  
      function writeToField(lat, log)
	  {
		    var coordStr = lat + ',' +log ;
        //console.log(coordStr);
		    $('#shop-coordinate').val(coordStr);
		  
	  }
      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }

     function shopCoodinates()
     {  
        var shopPos = $('#shop-coordinate').val();
        var lat,lng;
        if(shopPos)
        {
          var tempStr = shopPos.split(',');
          lat = tempStr[0];
          lng = tempStr[1];
        }
        return {'lat':Number(lat),'lng':Number(lng)};

     } 

     // function formatRegion(row){
     //  //console.log(row);
     //  var s = '';
     //  if(!row.parent_id)
     //    s += '<div><label>'+ row.name +'</label></div>';
     //  else{

     //    s += '<div value='+ row.id+'>'+ row.name+'</div>';
     //  }

     //  return s;


     // }

	</script>

</body>

</html>