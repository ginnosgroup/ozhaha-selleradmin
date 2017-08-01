<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class item extends CI_Controller {

	/**
	 * author:dmh describe:商品列表
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
		    'logo_url' => ($this->cache->get('logo_url'.$seller_id)!='uploads/')?($this->cache->get('logo_url'.$seller_id)):"",
		    'upload_root_path' => $this->config->item('upload_root_path')
		);
		$data['validation_errors'] = '';
		$data['result_success'] = '';
		
		$post_data = $this->input->post(NULL,true);
		$get_data = $this->input->get(NULL,true);
		$this->load->database();				
		if (empty($post_data))
		{				
				$category_id = 0;
				$page = 1;
				$where = "";
				$datalist = array();
				$data['keyword'] = '';
				if (!empty($get_data))
				{
						$data['keyword'] = (isset($get_data['keyword']) ? $get_data['keyword']:'');
						if (isset($get_data['ac']) == 'search')
						{
								if (isset($get_data['category_id']))
								{										
										$category_id = $get_data['category_id'];
										if ($get_data['category_id']) $where = " and category_id=".$category_id;
								}								
						}
						if (isset($get_data['keyword'])) 
						{
								$data['keyword'] = $get_data['keyword'];
								if ($get_data['keyword']) $where .= " and name like '%".$get_data['keyword']."%'";
						}
						if (isset($get_data['page'])) $page = intval($get_data['page']);
				}
				/* 分页begin */
				$this->load->library('pagination');
				$this->load->helper('url');
				$config['base_url'] = 'item';
				$config['per_page'] = 10;
				$config['use_page_numbers'] = TRUE;			
				$config['page_query_string'] = TRUE;
				$config['query_string_segment'] = 'page';
				$config['reuse_query_string'] = TRUE;
				$config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
				$config['full_tag_close'] = '</ul></nav>';
				$config['first_tag_open'] = '<li class="active">';
				$config['first_tag_close'] = '</li>';
				$config['prev_link'] = '«';
				$config['prev_tag_open'] = '<li>';
				$config['prev_tag_close'] = '</li>';
				$config['next_link'] = '»';
				$config['next_tag_open'] = '<li>';
				$config['next_tag_close'] = '</li>';
				$config['cur_tag_open'] = '<li class="active"><a href="#">';
				$config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
				$config['num_tag_open'] = '<li>';
				$config['num_tag_close'] = '</li>';
				$start_limit = ($page == 1 ? 0:intval(($page-1)*$config['per_page']));
				$tpp = $config['per_page'];
				$query = $this->db->query("SELECT count(*) as total FROM ".$this->db->dbprefix('item')." WHERE seller_id=$seller_id".$where);
				$row = $query->row_array();
				$config['total_rows'] = $row['total'];
				$config['num_links'] = 9;
				$this->pagination->initialize($config);
				$data['create_links'] = $this->pagination->create_links();
				/* 分页end */
												
				$data['make_form_select_shop_item_category'] = $this->waimai_seller->make_form_select_shop_item_category($category_id);
				$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix('item')." WHERE seller_id=$seller_id".$where." ORDER BY id DESC LIMIT $start_limit,$tpp");
				foreach ($query->result_array() as $row)
				{
						if ($row['image_url'] == '')
						{
								$row['image_url'] = $data['static_base_url'].'images/item_nopic.jpg';
						}else
						{
								$row['image_url'] = $this->config->item('upload_root_path').$row['image_url'];
						}
						$datalist[] = $row;
				}
				$data['data_list'] = $datalist;						
				$this->isloadtemplate = 1;
		}else
		{
				if ($post_data['ac'] == 'delete')
				{
						$id = $post_data['id'];						
						$w = array(
								'seller_id' => $seller_id,
								'id' => $id,
						);						
						$this->db->delete($this->db->dbprefix('item'),$w);
						$result = $this->db->affected_rows();
						if ($result)
						{
								//删除商品图片
								$this->delete_images($seller_id,$id);								
								$msg = 'ok';
								$content = '';
						}else
						{
								$msg = 'error';
								$content = '';
						}
						$arr = array("msg" => $msg,"content" => $content);
						$return_str = json_encode($arr);
						exit($return_str);
				}
		}
		$this->db->close();
		if($this->isloadtemplate)
		{
				$this->parser->parse('item_template', $data);
		}
	}
	
	public function delete_images($seller_id,$id)
	{
		$upload_flist = array();
		$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix('item_images')." WHERE item_id=$id");
		foreach($query->result_array() as $row)
		{
				if ($row['url'] != '')
				{
						$row['image_url'] = $this->config->item('upload_root_path').$row['url'];
						//删除文件
						if(file_exists($row['image_url'])) unlink($row['image_url']);
						$this->db->delete($this->db->dbprefix('item_images'),array("id" => $row['id']));
				}
		}
	}
	
}
 