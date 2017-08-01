<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class add_item extends CI_Controller {

	/**
	 * author:dmh describe:添加商品
	 * 
	 */
	public function index()
	{
		$this->load->library('parser');				
		$this->load->library('session');
		$this->load->library('form_validation');		
		$this->load->helper('language');
		$this->load->helper('file');
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		$this->load->library('waimai_seller');
		$seller_id = $this->waimai_seller->check_login();
		$this->isloadtemplate = 0;		
		$data = array(
		    'static_base_url' => $this->config->item('static_base_url'),
		    'seller_name' => $this->session->seller_name,
		    'logo_url' => $this->cache->get('logo_url'.$seller_id)
		);
		$data['validation_errors'] = '';
		$data['result_success'] = '';
		
		$post_data = $this->input->post('data[]', true);
		$this->load->database();
		if (empty($post_data))
		{
				$data['make_form_select_shop_item_category'] = $this->waimai_seller->make_form_select_shop_item_category(0);
				$data['post_data[name]'] = '';
				$data['post_data[price]'] = '';
				$data['post_data[number]'] = '';
				$data['post_data[coupon]1'] = ' checked="checked"';				
				$data['post_data[content]'] = '';
				$data['post_data[file_list]'] = '';
				$data['pic_uploaded'] = 0;
				$data['file_list'] = '';
				$data['weight'] = 0;
				$this->isloadtemplate = 1;
		}else
		{
				$this->lang->load('add_item');
				$data['make_form_select_shop_item_category'] = $this->waimai_seller->make_form_select_shop_item_category($post_data['category_id']);
				foreach ($post_data as $k=>$v)
				{
						if ($k == 'coupon')
						{
								$data['post_data['.$k.']'.$v] = ' checked="checked"';
						}else
						{
							$data['post_data['.$k.']'] = $v;				 	
						}				
				}
				//上传的图片列表
				$flist = array();
				$file_list = $post_data['file_list'];
				if ($file_list != '')
				{
						$arr_files = explode(",",$file_list);
				}else
				{
						$arr_files = array();
				}
				$data['upload_root_path'] = $this->config->item('upload_root_path');
				//读取上传的图片
    		$data['post_data[file_list]'] = $file_list;
    		foreach ($arr_files as $k=>$v)
				{
						$arrtmp['image_url'] = $data['upload_root_path'].$v;
						$flist[] = $arrtmp;
				}
				unset($arrtmp);
    		$data['file_list'] = $flist;
    		if ($file_list)
    		{		    				
    				$data['pic_uploaded'] = 1; 		
    		}else
    		{
    				$data['pic_uploaded'] = 0;
    		}
				
		 		$this->form_validation->set_rules('data[name]', lang('add_item_name'), 'required');
		 		$this->form_validation->set_rules('data[category_id]', '', 'in_list['.$this->waimai_seller->str_shop_item_category_list.']',
						array('in_list' => lang('add_item_validation_2'))
				);
				$this->form_validation->set_rules('data[price]', lang('add_item_price'), 'required|numeric');
				$this->form_validation->set_rules('data[number]', lang('add_item_number'), 'required|integer');
						 				 				
		 		if ($this->form_validation->run() == FALSE)
		    {		    		
		    		$data['validation_errors'] = validation_errors();
						$this->isloadtemplate = 1;
		    }else
		    {
		    		//添加写入
		    		$query = $this->db->query("SELECT count(*) as total FROM ".$this->db->dbprefix('item')." WHERE seller_id='$seller_id' and name=".$this->db->escape($post_data['name']));
						$row = $query->row_array();
						if (!$row['total'])
						{								
								$d = array(
								    'gmt_create' => date('Y-m-d H:i:s',time()),
								    'name' => $post_data['name'],
								    'category_id' => $post_data['category_id'],
								    'price' => $post_data['price'],
								    'number' => $post_data['number'],
								    'can_use_coupon' => $post_data['coupon'],
								    'content' => $post_data['content'],
								    'seller_id' => $seller_id,
                                    'weight'	=>$post_data['weight'] 									
								);								
								$this->db->insert($this->db->dbprefix('item'),$d);
								$item_id = $this->db->insert_id();
								//对上传图片进行入库处理								
								foreach ($arr_files as $k=>$v)
								{
										//将上传的图片从tmp目录移动正式目录
										$tmp_path = $data['upload_root_path'].$arr_files[$k];
										$arr_files[$k] = str_replace("product_tmp","product",$arr_files[$k]);
										$new_path = $data['upload_root_path'].$arr_files[$k];
										$new_dir = dirname($new_path);										
										if(!is_dir($new_dir)) mkdir($new_dir,0777,true);
										rename($tmp_path, $new_path);
										$d = array(
										    'gmt_create' => date('Y-m-d H:i:s',time()),
										    'item_id' => $item_id,
										    'serial_number' => $k+1,									    
										    'url' => $arr_files[$k]		   						   
										);								
										$this->db->insert($this->db->dbprefix('item_images'),$d);
										//设置默认商品图片
										if ($k == 0)
										{
												$d = array(
												    'image_url' => $arr_files[$k]	   						   
												);
												$w = array(								    
												    'id' => $item_id,
												    'seller_id' => $seller_id
												);								
												$this->db->update($this->db->dbprefix('item'),$d,$w);
										}
								}				
								$data['make_form_select_shop_item_category'] = $this->waimai_seller->make_form_select_shop_item_category(0);
								$data['post_data[name]'] = '';
								$data['post_data[price]'] = '';
								$data['post_data[number]'] = '';
								$data['post_data[coupon]1'] = ' checked="checked"';				
								$data['post_data[content]'] = '';
								$data['post_data[file_list]'] = '';
								$data['pic_uploaded'] = 0;
								$data['file_list'] = '';
								$data['result_success'] = lang('add_item_success_1');
								$this->isloadtemplate = 1;
						}else
						{
								$data['validation_errors'] = lang('add_item_error_1');
								$this->isloadtemplate = 1;
						}
		    }
		}
		$this->db->close();
		if($this->isloadtemplate)
		{
				$this->parser->parse('add_item_template', $data);
		}
	}
	
}
