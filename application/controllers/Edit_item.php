<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class edit_item extends CI_Controller {

	public $seller_id = 0;
	/**
	 * author:dmh describe:修改商品
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
		$id = intval($this->input->get('id', true));
		$this->lang->load('edit_item');
		$this->load->database();
		$item_id = $this->check_item_id($seller_id,$id,$data);		
		if ($item_id)
		{
				if (empty($post_data))
				{				
						$data = $this->init_item_id($seller_id,$id,$data);				
						$data['make_form_select_shop_item_category'] = $this->waimai_seller->make_form_select_shop_item_category($data['post_data[category_id]']);				
						$data['post_data[file_list]'] = '';
						$data['pic_uploaded'] = 0;
						$data['file_list'] = '';
						$this->isloadtemplate = 1;
				}else
				{									
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
						$file_list = trim($post_data['file_list']);
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
						
				 		$this->form_validation->set_rules('data[name]', lang('edit_item_name'), 'required');
				 		$this->form_validation->set_rules('data[category_id]', '', 'in_list['.$this->waimai_seller->str_shop_item_category_list.']',
								array('in_list' => lang('edit_item_validation_2'))
						);
						$this->form_validation->set_rules('data[price]', lang('edit_item_price'), 'required|numeric');
						$this->form_validation->set_rules('data[number]', lang('edit_item_number'), 'required|integer');
								 				 				
				 		if ($this->form_validation->run() == FALSE)
				    {		    		
				    		$data['validation_errors'] = validation_errors();
								$this->isloadtemplate = 1;
				    }else
				    {
				    		//编辑更新
				    		$query = $this->db->query("SELECT count(*) as total FROM ".$this->db->dbprefix('item')." WHERE seller_id='$seller_id' and id!='$id' and name=".$this->db->escape($post_data['name']));
								$row = $query->row_array();
								if (!$row['total'])
								{								
										$d = array(
										    'gmt_modify' => date('Y-m-d H:i:s',time()),
										    'name' => $post_data['name'],
										    'category_id' => $post_data['category_id'],
										    'price' => $post_data['price'],
										    'number' => $post_data['number'],
										    'can_use_coupon' => $post_data['coupon'],
										    'content' => $post_data['content'],
											'weight' =>  $post_data['weight']
											
										);
										$w = array(				    
										    'id' => $item_id,
										    'seller_id' => $seller_id
										);							
										$this->db->update($this->db->dbprefix('item'),$d,$w);										
										//对上传图片进行入库处理
										$sn = 1;
										$query1 = $this->db->query("SELECT max(serial_number) as max FROM ".$this->db->dbprefix('item_images')." WHERE item_id=$id");
										$row1 = $query1->row_array();										
										if ($row1['max']) $sn = $row1['max']+1;
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
												    'serial_number' => $sn,									    
												    'url' => $arr_files[$k]		   						   
												);	
												$this->db->insert($this->db->dbprefix('item_images'),$d);
												//设置默认商品图片
												if ($sn == 1)
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
												$sn++;										
										}				
										$data['make_form_select_shop_item_category'] = $this->waimai_seller->make_form_select_shop_item_category($data['post_data[category_id]']);
										$data['pic_uploaded'] = 0;
										$data['file_list'] = '';
										$data['result_success'] = lang('edit_item_success_1');
										$this->isloadtemplate = 1;
								}else
								{
										$data['validation_errors'] = lang('edit_item_error_1');
										$this->isloadtemplate = 1;
								}
				    }
				    //读取商品相关图片
				    $upload_flist = array();
						$query1 = $this->db->query("SELECT * FROM ".$this->db->dbprefix('item_images')." WHERE item_id=$item_id ORDER BY id ASC");
						foreach($query1->result_array() as $row1)
						{
								if ($row1['url'] == '')
								{
										$row1['image_url'] = $data['static_base_url'].'images/item_nopic.jpg';
								}else
								{
										$row1['image_url'] = $this->config->item('upload_root_path').$row1['url'];
								}
								$upload_flist[] = $row1;
						}
						$data['upload_file_list'] = $upload_flist;
				}
		}else
		{
				$data['validation_errors'] = lang('edit_item_error_2');			
				$this->isloadtemplate = 2;
		}
		$this->db->close();
		if($this->isloadtemplate == 1)
		{
				$this->parser->parse('edit_item_template', $data);				
		}elseif ($this->isloadtemplate == 2)
		{
				$this->parser->parse('error_message_template', $data);
		}
	}
	
	public function check_item_id($seller_id,$id,$data)
	{
		$item_id = 0;
		$query = $this->db->query("SELECT id FROM ".$this->db->dbprefix('item')." WHERE seller_id='$seller_id' and id=$id LIMIT 1");
		$row = $query->row_array();
		if ($row['id'])
		{
				$item_id = $row['id'];
		}
		return $item_id;
	}
	
	public function init_item_id($seller_id,$id,$data)
	{
		$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix('item')." WHERE seller_id='$seller_id' and id=$id");
		$row = $query->row_array();
		if ($row['id'])
		{
				foreach ($row as $key => $val)
				{
						if ($key == 'can_use_coupon')
						{
								if ($row['can_use_coupon'])
								{
										$data['post_data[coupon]1'] = ' checked="checked"';
								}else
								{
										$data['post_data[coupon]0'] = ' checked="checked"';
								}
						}else
						{
								$data['post_data['.$key.']'] = $val;
						}
				}
				//读取商品相关图片
				$upload_flist = array();
				$query1 = $this->db->query("SELECT * FROM ".$this->db->dbprefix('item_images')." WHERE item_id=$row[id] ORDER BY id ASC");
				foreach($query1->result_array() as $row1)
				{
						if ($row1['url'] == '')
						{
								$row1['image_url'] = $data['static_base_url'].'images/item_nopic.jpg';
						}else
						{
								$row1['image_url'] = $this->config->item('upload_root_path').$row1['url'];
						}
						$upload_flist[] = $row1;
				}
				$data['upload_file_list'] = $upload_flist;	
		}
		return $data;
	}
	
}
