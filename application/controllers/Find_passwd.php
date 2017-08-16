<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class find_passwd extends CI_Controller {

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
		    'logo_url' => ($this->session->seller_logo_url != 'uploads/')? $this->session->seller_logo_url :"",
		);
		$data['validation_errors'] = '';
		$data['result_success'] = '';
		
		$post_data = $this->input->post('data[]', true);
		$this->load->database();
		if (empty($post_data))
		{
				$data['post_data[name]'] = '';
				$data['post_data[email]'] = '';
				$this->isloadtemplate = 1;
		}else {				
		 		$this->lang->load('find_passwd');
		 		$this->form_validation->set_rules('data[name]', lang('find_passwd_name'), 'required');
		 		$this->form_validation->set_rules('data[email]', lang('find_passwd_email'), 'required|min_length[6]|max_length[128]');	
		 		if ($this->form_validation->run() == FALSE)
		    {
		    		$data['post_data[name]'] = $post_data['name'];					
		    		$data['post_data[email]'] = $post_data['email'];
		    		$data['validation_errors'] = validation_errors();
						$this->isloadtemplate = 1;
				}else {
						$md5passwd =  md5($post_data['passwd']);
						$query = $this->db->query("SELECT count(*) as total FROM ".$this->db->dbprefix('seller')." WHERE name=".$this->db->escape($post_data['name'])." and email=".$this->db->escape($post_data['email'])." limit 1");
						$row = $query->row_array();
						if ($row['total'])
						{
								//·¢ËÍÕÒ»ØÃÜÂëÓÊ¼ş
								$data['post_data[name]'] = '';
								$data['post_data[email]'] = '';
								$data['result_success'] = lang('find_passwd_success_1');								
								$this->isloadtemplate = 1;
						}else {
						 		$data['validation_errors'] = lang('find_passwd_error_1');
								$this->isloadtemplate = 1;
						}
				}				
		}
		$this->db->close();
		if($this->isloadtemplate)
		{
				$this->parser->parse('find_passwd_template', $data);	
		}
	}
	
}
