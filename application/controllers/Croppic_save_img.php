<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Croppic_save_img extends CI_Controller {

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
	// if(!is_writable('/uploads/'.$save_path .'/')){
	// 	$response = Array(
	// 		"status" => 'error',
	// 		"message" => 'Can`t upload File; no write Access'
	// 	);
	// 	exit(json_encode($response));
	// 	//return;
	// }
	//var_dump($_FILES["img"]["name"]);

	//var_dump($file);
	
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
	     $filename = $this->waimai_seller->do_upload_shop_image('img', $save_path,'gif|jpg|png',2048,1024,768);
	     if($filename)
	     {
		 	
		 	list($width, $height) = getimagesize($_FILES["img"]["tmp_name"]);
		 	 $response = array(
				"status" => 'success',
				"url" =>   'uploads/'.$filename,
				"width" => $width,
				"height" => $height
		  	);
		 }
		 else
		 {
		 	 $response = array(
				"status" => 'error',
				"message" => 'ERROR Return Code: '. $_FILES["img"]["error"],
				'content' => 'size too large'
			);	
		 }
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

public function del_image()
{

	$path = $this->input->post(NULL, true)['path'];
	//var_dump($path);
	$upload_path = $this->config->item('upload_root_path').$path;
	if ($path && file_exists('./'.$upload_path)) unlink('./'.$upload_path);
	$arr = array("msg" => 'ok',"content" => $upload_path);
	$return_str = json_encode($arr);
	exit($return_str);
	
}

}
?>