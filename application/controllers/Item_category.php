<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class item_category extends CI_Controller {

	/**
	 * author:dmh describe:
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
		);
		$data['validation_errors'] = '';
		$data['result_success'] = '';
		
		$post_data = $this->input->post();
		//var_dump($post_data);
		$this->load->database();
		if (empty($post_data))
		{
				$datalist = array();
				$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix('item_category')." WHERE seller_id=$seller_id and (parent_id=0 or isnull(parent_id)) ORDER BY id ASC");
				$query_array = $query->result_array();
				array_multisort(array_column($query_array, 'weight'), SORT_ASC, $query_array);
				//var_dump($query_array);

				foreach ($query_array as $row)
				{
						$datalist[] = $row;
				}
				$query_total = $this->db->query("SELECT count(*) as total FROM ".$this->db->dbprefix('item_category')." WHERE seller_id=$seller_id and (parent_id=0 or isnull(parent_id)) ORDER BY id ASC");
				 
				$data['data_list'] = $datalist;	
				$data['total']	= $query_total->row_array()['total'];
				//var_dump($data['total']);	
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
						$this->db->delete($this->db->dbprefix('item_category'),$w);
						$result = $this->db->affected_rows();
						if ($result)
						{
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
				$this->parser->parse('item_category_template', $data);
		}
	}
	
}
