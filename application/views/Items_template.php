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

<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" /> -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css" />
<link rel="stylesheet" href="https://code.jquery.com/qunit/qunit-1.18.0.css" />
<link href="{static_base_url}css/easyui-themes/metro/easyui.css" rel="stylesheet" />
<link href="{static_base_url}css/easyui-themes/icon.css" rel="stylesheet" />
<link href="{static_base_url}uploadify/uploadify.css" rel="stylesheet">
<link href="{static_base_url}css/croppic/croppic.css" rel="stylesheet">
<!-- <link href="./{static_base_url}css/easyui-themes/demo/demo.css" rel="stylesheet" /> -->
<body>
<!--  -->
    <div id="wrapper">

    <?php $this->load->view('common_navigation_template') ?>
        <div id="page-wrapper">
         <div class="panel panel-default">
          <div class="row">
           <div class="col-lg-12">
              <h1 class="page-header">商品列表</h1>
               <div class="panel-heading">
                 <form class="form-inline" method="get">
                   <input type="hidden" name="ac" value="search" >
                    <div class="form-group">
                    <label>搜索商品</label>
                      <!-- <select name="category_id" id="goods-category_id"  class="form-control">
                        <option value="0">全部</option>
                                    {make_form_select_shop_item_category}onchange="location='?ac=search&category_id='+this.options[this.selectedIndex].value;"
                      </select> -->
                      <input id='itemsCategory' class=" easyui-combobox" data-options="valueField:'category_pair',textField:'name',url:'items_test/get_items_category'" style="width: 75px;height:33px;">
                    </div>
                    <div class="form-group">
                     <input type="text" class="form-control" name="keyword" id="keyword"  placeholder="输入关键字或ID查询商品">
                    </div>
                    <button id="orderSearch" type="submit" class="btn btn-primary" onclick="searchItem();return false;">查询</button>
             </form>
            </div>
           </div>
          </div>
        <div class='row'>
        <div class='col-lg-12'>
        <table class="easyui-datagrid" title="商品列表"  id='itemsTable' url='items_test/items_list' style="height:600px;" method='get' data-options="singleSelect:true,pagination:'true',
      autoRowHeight:'false',fitColumns:'false',valueField:'text',
      onDblClickCell:onClickCell,sortName:'weight',sortOrder:'desc'" toolbar='#tb' emptyMsg='The shop currently does not have any products!'>
    <thead>
      <tr>
        <th data-options="field:'id',width:50" sortable ='true'>ID</th>
        <th data-options="field:'image_url',width:162,align:'center',resizable:true,formatter:formatImg,editor:{type:'upload'}">图片</th>
        <th data-options="field:'gmt_create',width:250,align:'center',sortable:'true',hidden:'true'">create date</th>
        <th class="easyui-validatebox" data-options="field:'name',width:80,align:'center',sortable:'true',
        editor:{type:'textbox',options:{required:true}}">名称</th>
        <th data-options="field:'seller_id',width:60,align:'center',hidden:'true'">seller ID</th>
        <th data-options="field:'category_pair',width:100,align:'center',formatter:formatCategory,sortable:true,       
                          editor:{
                            type:'combobox',
                            options:{
                                valueField:'category_pair',
                                textField:'name',
                                url:'items_test/get_items_category',
                                method:'get',
                                required:true
                            } }
                  ">类目</th>
        <th class="easyui-validatebox" data-options="field:'price',width:60,align:'center',sortable:'true',editor:{type:'numberbox',options:{precision:2,required:true}}">价格</th>
        <th data-options="field:'number',width:60,align:'center',sortable:'true',editor:{type:'numberbox'}">数量</th>
        <th data-options="field:'can_use_coupon',width:60,align:'center',formatter:formatCoupon,editor:{type:'checkbox',options:{on:'1',off:'0'}}">可用优惠券</th>
        <th data-options="field:'content',width:60,align:'center',editor:{type:'textbox'}">描述</th>
        <th class="easyui-validatebox" data-options="field:'weight',width:80,align:'center',sortable:'true',editor:{type:'numberbox',options:{}}">推荐值(0-999)</th>
        <th data-options="field:'gmt_modify',width:170,align:'center',sortable:'true'">最后修改</th>
      </tr>
    </thead>
  </table>
 
<!--  iconCls="icon-add" -->
  <div id="tb">
    <a href="#" class="btn btn-success" plain="true" onclick="addItem()"><i class="fa fa-plus fa-fw"></i></a>

    <a href="#" class="btn btn-danger"  plain="true" onclick="deleteBtn()"><i class="fa fa-trash-o fa-fw"></i></a>
    <span  style="margin:0 5px;border-right:solid 1px #ccc;"></span>   
   <!--  <a href="#" class="btn btn-secondary" style="border: solid 0.5px #31b0d5;" plain="true" onclick="finshChanges()"><i class="fa fa-check fa-fw"></i></a> -->
    <a href="#" class="btn btn-warning"  plain="true" onclick="javascript:cancelChanges()"><i class="fa fa-reply fa-fw"></i></a>
    <a href="#" class="btn btn-info" iconCls="icon-save" plain="true" onclick="saveChanges()"><i class="fa fa-floppy-o fa-fw"></i></a>
</div>
</div>
</div>
<div hidden id ="confirmDeleteItems" class="easyui-dialog" style="width:400px;height:200px;" data-options='buttons:"#deleteItemBtns",closed:true,title:"删除？"'> 
<div><p class='col-md-5' style ="margin-top: 10px">Are you sure to delete</p></div>
<div id='deleteItemBtns'>
  <a href="#" class="btn btn-danger"  onclick="deleteItem()">确认删除</a>
  <a href="#" class="btn btn-default" onclick="$('#confirmDeleteItems').dialog('close')">取消</a> 
</div>
</div>

 <div hidden id="uploadWindow" class="easyui-dialog" title="Upload Window" data-options='buttons:"#uploadSaveBtns",closed:true' style="width:450px;height:200px;padding:10px;">
 <div hidden id ="file_list"></div>
    <div class="form-group">
<!--        <label for="goods-pic" class="col-lg-1 control-label">商品图片</label> -->
         <div class="col-md-5" style='margin-left: 31%' >
        <div><input type="file" id='uploadBtn' name="file_upload" id="file_upload"></div>
          <div id="queue" style="width:180px;">
        </div>
        <div id='uploadPath'></div>
        </div>
      
      
     </div>
    <div id="uploadSaveBtns">
    <a href="#" class="btn btn-success"  onclick="savePathToSlot()" style="
    width:100%;">save</a>
   </div>
  </div>
 



 
<style>
#uploadWindow{
  border: 1px solid #ccc;
  width:300px;
  height:200px;
  position:relative;

}
  #itemsTable {
    width:960px;
    margin: 0 auto;
  }
  .products-img {
    max-width:100px;

  }
  .fixedColumn{
    margin:30px auto;
  }

 .form-control#keyword {

   border-radius: 0;
  }

 </style>
      </div>
    </div>
  </div>


    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <!-- <script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> -->
     <script type="text/javascript" src="{static_base_url}uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript" src="{static_base_url}js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="{static_base_url}js/croppic.min.js"></script>
<script>
$(function(){

  // $('#itemsCategory').on('change',function(){

  //   if($('#itemsCategory').val()=='')
  //   {
  //     $('#itemsTable').datagrid('reload');
  //   }
  // })
// var cropperOptions = {
//       uploadUrl:'Croppic',
//       modal:true,
//       imgEyecandyOpacity:0.4,
//       loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
//       onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
//       zoomFactor:10,
//       doubleZoomControls:true,
//       rotateFactor:10,
//       rotateControls:true     
//     }
// var cropperHeader = new Croppic('uploadWindow',cropperOptions);
 
  //$('#w').dialog('close');
  // var fileArray = Array();
  // var fidArray = Array();
  // var file_list = $("input[type='file']").val();
  // if (file_list != '')
  // {
  //   fileArray = file_list.split(',');
  //   for(i=0;i<fileArray.length;i++)
  //   {
  //     fidArray.push('');
  //   }
  //   console.log(fileArray);
  //   console.log(fidArray);
  // }
  $('#itemsTable').datagrid({
  onBeforeEdit: function(index,row){
    row.number = row.number||100;
    row.weight = row.weight||999;
    row.can_use_coupon = row.can_use_coupon||1;
  }
})
});
// Acoording to various types display value with proper formats   
function formatImg(val,row)
{
  if(val) return '<img class="products-img" src="'+'uploads/' + val +'" width:100">';
  else return  '<img class="products-img" src="'+'static/images/item_nopic.jpg' + '" width:100">';

  return str;
}

function formatCoupon(val,row)
{
  if(val != 0) return '<div class="btn btn-circle btn-success"><i class="fa fa-check fa-fw"></i></div>';
  else return '<div class="btn btn-circle btn-danger"><i class="fa fa-times"></i></div>';
}

function formatCategory(val, row)
{

  var json = eval('(' + val+ ')');
  var name =''; 

  if(json) return  '<div id='+ json.id + ">" + json.name + '</div>';
  else  return '';
}

// function categoryBox(row){
 
//   var json = eval('(' + row.category_pair+ ')');
//   var  id = json.id;
//   var name = json.name;
//   var opts = $(this).combobox('options');
//   console.log(opts);

// }

//End 
//Table functions, including basic CRUD
var editIndex = undefined;
function endEditing(){
  if (editIndex == undefined){return true}
  if ($('#itemsTable').datagrid('validateRow', editIndex)){
      $('#itemsTable').datagrid('endEdit', editIndex);
      editIndex = undefined;
      return true;
      } 
  else{
      return false;
  }
}

function onClickCell(index, field){
  
   // editIndex = undefined;
  if (editIndex != index){
      if (endEditing()){
        $('#itemsTable').datagrid('selectRow', index)
                .datagrid('beginEdit', index);
        var ed = $('#itemsTable').datagrid('getEditor', {index:index,field:field});
        if (ed){
           ($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
        }
         editIndex = index;
      } else {
          setTimeout(function(){
           $('#itemsTable').datagrid('selectRow', editIndex);
                    },0);
        }
  }
}
function cancelChanges(){
 
   if(endEditing())
  {
    var changedRows = $('#itemsTable').datagrid('getChanges');
    alert(changedRows.length + ' rows changed have been undo');
    //$('#itemsTable').datagrid('rejectChanges');
    //editIndex = undefined;
  }
  $('#itemsTable').datagrid('rejectChanges');
  editIndex = undefined;
}

function saveChanges()
{
    if(endEditing())
    {
      var changedRows = $('#itemsTable').datagrid('getChanges');
      //console.log(changedRows);
      var addRows=[];
      var updateRows=[];
      $.each(changedRows,function(index,value){

        if(value.id)
        {
          updateRows.push(value);
        }
        else
        {
          addRows.push(value);
        }

      });
       //console.log(addRows,updateRows);
      if((addRows.length>0)&&(updateRows.length>0))
      {
        alert('forbidden!!');
        $('#itemsTable').datagrid('rejectChanges');
        $('#itemsTable').datagrid('reload');
      }
      else
      {
        if(updateRows.length>0)
        {
          $.post('items_test/update_items',{data:changedRows},function(data){
          if(data.msg =='ok')
          {
            alert('upadate successed, ' + data.update_rows + ' has changed');
            //if(data.added_id)
            $('#itemsTable').datagrid('acceptChanges');
            $('#itemsTable').datagrid('load');
          }
          else{
            alert('error message' + data.details);
          }},'json');
        }
        if(addRows.length>0){
          $.post('items_test/add_items',{data:changedRows},function(data){
          if(data.msg =='ok')
          {
            alert('add successed, ' + data.update_rows + ' has changed, '+ 'new item id:' + data.added_id);
            $('#itemsTable').datagrid('acceptChanges');
            $('#itemsTable').datagrid('load');
          }
          else{
            alert('error message' + data.details);
          }},'json');
        }
      }
    }

}
function deleteBtn(){

 if($('#itemsTable').datagrid('getSelected'))
{
  $('#confirmDeleteItems').dialog('open');
}
}
function finshChanges()
{
  endEditing();

}

function deleteItem()
{
  var changedRows = $('#itemsTable').datagrid('getSelected');
   $.post('items_test/delete_item',{data:changedRows},function(data){
       if(data.msg =='ok')
        {
          alert('delete successed');
          $('#confirmDeleteItems').dialog('close');
          $('#itemsTable').datagrid('acceptChanges');
          $('#itemsTable').datagrid('load');

        }
        else{
          $('#confirmDeleteItems').dialog('close');
          alert('error message' + data.details);
        }
      },'json');
}



function addItem()
{
  //editIndex =undefined;
  if (endEditing()){
 // $('#itemsTable').datagrid('appendRow',{});
 $('#itemsTable').datagrid('insertRow',{
                            index:0,
                            row:{}});
                //editIndex = $('#itemsTable').datagrid('getRows').length-1;
                editIndex = 0;
  $('#itemsTable').datagrid('selectRow', editIndex)
                  .datagrid('beginEdit', editIndex);
   }

}
function searchItem(){

  var keyword = $('#keyword').val();
  var category_pair = $('#itemsCategory').val();
  var category_id ='';
  if(category_pair)
  {
    category_id = JSON.parse(category_pair).id;
  }
  $('#itemsTable').datagrid('reload',{'keyword':keyword,'category_id':category_id});


}
  //functions to upload images for items
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
            }
            $("#uploadBtn").uploadify({
            'auto'             : true,
            'queueID'          : 'queue',
            'buttonText'       : '上传图片',
            'swf'              : 'static/uploadify/uploadify.swf',  
            //'uploadScript'     : 'uploadifive?mod=add_item&ac=upload',
            'uploader'     : 'uploadifive?mod=add_item&ac=upload',
            'onUploadSuccess' : function(file, data, response){
            var result = data.split('|');
            //console.log(data);
            if (result[0] == '1')
            {
                  fileArray.push(result[1]);
                  fidArray.push(file.name);
                  $('#uploadPath').val(fileArray.toString());
                  $('#file_list').val(fileArray.toString());
            }
            else {

                alert('upload failed!! ');
                alert('make sure the the image size is 2048KB with width and height: 1024*768, data type: JPG|PNG|GIF');
            }

            },
            'onSelectError': function(file, code,msg){

              alert('error occurred, error code: ' + code + ', msg: '+ msg);
            },
            'onUploadError':function(file, code, msg, errStr)
            {
              alert('error! code:' + code + ';  ' + errStr);
            },
           'onCancel' : function(file){
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

var str = '<div data-type="upload" href="" class="fixedColumn"' + 'onclick="$(\'#uploadWindow\').dialog(\'open\').center;">click to open</div>';
//var str = "<script>$(\'#w\').dialog(\'open\');" + "
$.extend($.fn.datagrid.defaults.editors, {
    upload: {
        init: function(container, options){
            
            var input = $(str).appendTo(container);
          
            return input;
        },
        destroy: function(target){
            $(target).remove();
        },
        getValue: function(target){
            return $(target).val();
        },
        setValue: function(target, value){
            $(target).val(value);


        },
        resize: function(target, width){
            $(target)._outerWidth(width);
        }
    }
});


function savePathToSlot()
{
    var path = $('#uploadPath').val();
    //$('div[data-type ="upload"]').text(path);
    $('div[data-type ="upload"]').val(path);
    fileArray.length = 0;
    fidArray.length = 0;

   $("#uploadBtn").uploadify('cancel','*');
    //$("#input[type='file']").uploadifive('debug');
    $('#uploadWindow').dialog('close');
}
// $('#myTree').treegrid({
//   onBeforeEdit:function(row){
//     var col = $(this).treegrid('getColumnOption','description');
//     if (row.type == 'text'){
//       col.editor = 'text';
//     } else if (row.type == 'select'){
//       col.editor = {
//         type: 'combobox',
//         options:{
//           //...
//         }
//       }
//     }
//   }
// });
//End
</script>
</body>
    
</html>
