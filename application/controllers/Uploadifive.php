<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uploadifive extends CI_Controller {

	/**
	 * author:dmh describe:上传图片
	 * 
	 */
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
				$mod = $this->input->get('mod', true);
				switch($mod)
				{
						case 'add_item':
								$ac = $this->input->get('ac', true);
								$this->do_upload_add_item($ac);
						break;
				}							
		}else
		{
				echo 0; 	
		}
		
	}
	
	
	/**
	 * author:dmh describe:上传/删除商品图片
	 * 
	 */
	public function do_upload_add_item($ac)
	{
		if ($ac == 'upload')
		{
				$save_path = 'product_tmp/'.date('Ym',time()).'/'.date('d',time());
				$file = $this->waimai_seller->do_upload_shop_image('Filedata',$save_path,'gif|jpg|png',2048,1024,768);
				//var_dump($file);
				if ($file)
				{
						echo "1|".$file;
				}else
				{
						echo "0|";
				}
		}elseif ($ac == 'delete')
		{				
				$path = $this->input->get('path', true);
				$upload_path = $this->config->item('upload_root_path').$path;
				if ($path && file_exists('./'.$upload_path)) unlink('./'.$upload_path);
				$arr = array("msg" => 'ok',"content" => $upload_path);
				$return_str = json_encode($arr);
				exit($return_str);
		}
	}
}
