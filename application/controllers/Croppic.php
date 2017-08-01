<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Croppic extends CI_Controller {

public function __construct()
{
      parent::__construct();
      $this->load->helper(array('form', 'url'));
}

public function index()
{
	$this->load->library('session');
	$this->load->library('waimai_seller');
	$seller_id = $this->waimai_seller->check_login();
	if ($seller_id)
	{
		//$mod = $this->input->get('mod', true);
		$this->upload_image();
	}

}

public function upload_image()
{
	$save_path = 'product_tmp/'.date('Ym',time()).'/'.date('d',time());
	$allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPEG", "JPG", "PNG");
	$temp = explode(".", $_FILES["img"]["name"]);
	$extension = end($temp);
	//var_dump(expression)
	if(!is_writable($save_path)){
		$response = Array(
			"status" => 'error',
			"message" => 'Can`t upload File; no write Access'
		);
		exit(json_encode($response));
		//return;
	}
	
	if (in_array($extension, $allowedExts))
	  {
	  if ($_FILES["img"]["error"] > 0)
		{
			 $response = array(
				"status" => 'error',
				"message" => 'ERROR Return Code: '. $_FILES["img"]["error"],
			);			
		}
	  else
		{
			
	      $filename = $_FILES["img"]["tmp_name"];
		  list($width, $height) = getimagesize( $filename );

		  move_uploaded_file($filename,  $save_path . $_FILES["img"]["name"]);

		  $response = array(
			"status" => 'success',
			"url" => $save_path.$_FILES["img"]["name"],
			"width" => $width,
			"height" => $height
		  );
		  
		}
	  }
	else
	  {
	   $response = array(
			"status" => 'error',
			"message" => 'something went wrong, most likely file is to large for upload. check upload_max_filesize, post_max_size and memory_limit in you php.ini',
		);
	  }
	  
	  exit(json_encode($response));
	
}

public function crop_image()
{

$file = $this->waimai_seller->do_upload_shop_image('Filedata',$save_path,'gif|jpg|png',2048,1024,768);

	//var_dump($file);
	if (1)
	{
		$arr =array('status'=>'success','url'=>$file,'width'=>1024,'height'=>768);
		exit(json_encode($arr));
	}else
	{	
		$arr =array('status'=>'error','message'=>'fail to upload');
		exit(json_encode($arr));
	}
}

}
?>