<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * author:dmh describe:商家登录
	 * 
	 */
	public function index()
	{
		$this->load->library('parser');
		$this->load->helper(array('form', 'url'));
		$this->load->helper('language');
		$this->load->library('form_validation');
		$this->isloadtemplate = 0;
		$data = array(
		    'static_base_url' => $this->config->item('static_base_url'),
		    //Joe
			//'form_name' =>  $this->input->post('name', true),
			'form_phone' =>  $this->input->post('phone', true),
			//End
		    'form_password' => $this->input->post('password', true)
		);
		//Joe
		//$this->form_validation->set_rules('name', 'name', 'required');
		$this->form_validation->set_rules('phone', 'phone', 'trim|required|callback_valid_phone_number_or_empty');//is_unique[ozhaha_wm_seller.phone].callback_valid_phone_number_or_empty
		//End
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == FALSE)
    {
    		$data['validation_errors'] = validation_errors();    		
				$this->isloadtemplate = 1;
		}else
		{				
				$this->lang->load('login');
				$md5passwd = md5($data['form_password']);
				$this->load->database();				
				//Joe
				//$query = $this->db->query("SELECT id,status,logo_url FROM ".$this->db->dbprefix('seller')." WHERE name=".$this->db->escape($data['form_name'])." and password='$md5passwd'");
				$query = $this->db->query("SELECT id,status,logo_url FROM ".$this->db->dbprefix('seller')." WHERE phone=".$this->db->escape($data['form_phone'])." and password='$md5passwd'");
				//End
				$row = $query->row();
				if ($row)
				{
						if (!$row->status)
						{
								//状态异常
								$data['validation_errors'] = lang('error_login_submit_2'); 		
								$this->isloadtemplate = 1;
						}else
						{
								$this->load->library('session');
								$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
								$this->session->set_userdata('seller_id', $row->id);
								//Joe
								//$this->session->set_userdata('seller_name', $data['form_name']);
								//$this->input->set_cookie("seller_name", $data['form_name'], 86500);
								$this->session->set_userdata('seller_phone', $data['form_phone']);
								$this->input->set_cookie("seller_phone", $data['form_phone'], 86500);
								//End
								$upload_dir = $this->config->item('upload_root_path');
								$upload_dir = str_replace('./','', $upload_dir);							
								$this->cache->save('logo_url'.$row->id, $upload_dir.$row->logo_url, 2592000);
								//登录成功并跳转						
						}						
				}else
				{
						$data['validation_errors'] = lang('error_login_submit_1'); 		
						$this->isloadtemplate = 1;
				}
				$this->db->close();				
		}
		if ($this->isloadtemplate)
		{
				$this->parser->parse('login_template', $data);
		}else
		{
				redirect('/welcome');
		}
	}
	
	//Joe 2017/06/13
	// To set rules for phone#
	function valid_phone_number_or_empty($value)
	{
    $value = trim($value);
    if ($value == '') {
        return TRUE;
    }
    else
    {
        if (preg_match('/^\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}$/', $value))
        {
            return preg_replace('/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/', '($1) $2-$3', $value);
        }
        else
        {
			$this->form_validation->set_message('valid_phone_number_or_empty', 'The {field} field must be all number');
            return FALSE;
        }
    }
	}
//End
}
