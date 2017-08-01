<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class change_passwd extends CI_Controller {

	/**
	 * author:dmh describe:ĞŞ¸ÄÃÜÂë
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
		    //Joe
			//'seller_name' => $this->session->seller_name,
			'seller_phone' => $this->session->seller_phone,
			//End
		    'logo_url' => ($this->cache->get('logo_url'.$seller_id)!='uploads/')?($this->cache->get('logo_url'.$seller_id)):"",
		);
		$data['validation_errors'] = '';
		$data['result_success'] = '';
		
		$post_data = $this->input->post('data[]', true);
		$this->load->database();
		if (empty($post_data))
		{
				$data['post_data_passwd'] = '';
				$data['post_data_passwd1'] = '';
				$data['post_data_passwd2'] = '';
				$this->isloadtemplate = 1;
		}else {				
		 		$this->lang->load('change_passwd');
		 		$this->form_validation->set_rules('data[passwd]', lang('change_passwd'), 'required|min_length[6]');
		 		$this->form_validation->set_rules('data[passwd1]', lang('change_passwd1'), 'required|min_length[6]|max_length[20]|matches[data[passwd2]]',
		 				array('matches' => lang('change_passwd_validation_1'))
		 		);
		 		$this->form_validation->set_rules('data[passwd2]', lang('change_passwd2'), 'required|min_length[6]|max_length[20]'); 		
		 		if ($this->form_validation->run() == FALSE)
		    {
		    		$data['post_data_passwd'] = $post_data['passwd'];
						$data['post_data_passwd1'] = $post_data['passwd1'];
						$data['post_data_passwd2'] = $post_data['passwd2'];
		    		$data['validation_errors'] = validation_errors();
						$this->isloadtemplate = 1;
				}else {
						$md5passwd =  md5($post_data['passwd']);
						$query = $this->db->query("SELECT count(*) as total FROM ".$this->db->dbprefix('seller')." WHERE id='$seller_id' and password=".$this->db->escape($md5passwd)." limit 1");
						$row = $query->row_array();
						if ($row['total'])
						{						
								$md5Passwd1 = md5($post_data['passwd1']);
								$d = array(
								    'gmt_modify' => date('Y-m-d H:i:s',time()),
								    'password' => $md5Passwd1						   						   
								);
								$w = array(
										'id' => $seller_id
								);
								$this->db->update($this->db->dbprefix('seller'),$d,$w);								
								$data['post_data_passwd'] = '';
								$data['post_data_passwd1'] = '';
								$data['post_data_passwd2'] = '';
								$data['result_success'] = lang('change_passwd_success_1');
								$this->isloadtemplate = 1;
						}else {
						 		$data['validation_errors'] = lang('change_passwd_error_1');
								$this->isloadtemplate = 1;
						}
				}				
		}
		$this->db->close();
		if($this->isloadtemplate)
		{
				$this->parser->parse('change_passwd_template', $data);	
		}
	}
	
}
