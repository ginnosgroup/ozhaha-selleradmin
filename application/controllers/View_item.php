<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class view_item extends CI_Controller {

	/**
	 * author:dmh describe:商品详细页
	 * 
	 */
	public function index()
	{
		$this->load->library('parser');				
		$this->load->library('session');	
		$this->load->helper('language');		
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		$this->load->library('waimai_seller');
		$seller_id = $this->waimai_seller->check_login();
		$this->isloadtemplate = 0;		
		$data = array(
		    'static_base_url' => $this->config->item('static_base_url'),
		    'seller_name' => $this->session->seller_name,
		    'logo_url' => $this->cache->get('logo_url'.$seller_id),
		    'upload_root_path' => $this->config->item('upload_root_path')
		);
		$data['validation_errors'] = '';	
		
		$id = intval($this->input->get('id', true));
		$this->load->database();
		if ($id)
		{
				$this->lang->load('view_item');
				$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix('item')." WHERE seller_id='$seller_id' and id=$id");
				$row = $query->row_array();
				if ($row['id'])
				{
						foreach ($row as $key => $val)
						{
								if ($key == 'category_id')
								{
										$query1 = $this->db->query("SELECT name FROM ".$this->db->dbprefix('item_category')." WHERE seller_id='$seller_id' and id=$val");
										$row1 = $query1->row_array();
										$data['row[category_name]'] = $row1['name'];
								}elseif ($key == 'can_use_coupon')
								{
										if ($row['can_use_coupon'])
										{
												$data['row[use_coupon]'] = lang("view_item_can_use_coupon_1");
										}else
										{
												$data['row[use_coupon]'] = lang("view_item_can_use_coupon_0");
										}
								}else
								{
										$data['row['.$key.']'] = $val;
								}
						}
						//读取商品相关图片
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
								$flist[] = $row1;
						}
						$data['file_list'] = $flist;	
				}else
				{
						$data['validation_errors'] = lang('view_item_error_1');
				}
				$this->isloadtemplate = 1;
		}
		$this->db->close();
		if($this->isloadtemplate)
		{
				if ($data['validation_errors'] == '')
				{
						$this->parser->parse('view_item_template', $data);
				}else
				{
						$this->parser->parse('error_message_template', $data);
				}
		}
	}
	
}
