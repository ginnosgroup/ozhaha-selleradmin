<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seller_List extends CI_Controller {

	public $admin_id;
	
	/**
	 * author:dmh describe:商家管理
	 * 
	 */
	public function index()
	{
		$this->load->library('parser');				
		$this->load->library('session');
		$this->load->library('form_validation');		
		$this->load->helper('language');
		$this->load->helper('file');
		$this->load->library('waimai_admin');
		$admin_id = $this->waimai_admin->check_login();
		$this->isloadtemplate = 0;		
		$data = array(
		    'static_base_url' => $this->config->item('static_base_url')
		);
		$data['validation_errors'] = '';
		$data['result_success'] = '';
		
		$post_data = $this->input->post(NULL,true);
		$get_data = $this->input->get(NULL,true);
		$this->lang->load('seller_list');
		$this->load->database();				
		if (empty($post_data))
		{
				$isajax = 0;
				$status = 0;
				$page = 1;
				$where = "";
				$datalist = array();
				$data['default_rtime'] = date('Y-m-d',strtotime('+'.$this->config->item('seller_recommend_default_days').' day'));//默认推荐7天
				$data['select_seller_category'] = $this->select_seller_category(0);
				$data['category_id'] = 0;
				$data['state'] = '';
				$data['shop_name'] = '';
				$data['query_string'] = ($_SERVER["QUERY_STRING"] != '' ? '?'.$_SERVER["QUERY_STRING"]:'');
				if (!empty($get_data))
				{
						$isajax = (isset($get_data['isajax']) ? $get_data['isajax']:'0');
						if (isset($get_data['ac']) == 'search')
						{
								if (isset($get_data['category_id']))
								{
										$data['category_id'] = $get_data['category_id'];
										$data['select_seller_category'] = $this->select_seller_category($get_data['category_id']);	
										if ($get_data['category_id']) $where = " and s.category_id='".$get_data['category_id']."'";
								}
								
								if (isset($get_data['state'])) 
								{
										$data['state'] = $get_data['state'];
										if ($get_data['state']) $where .= " and s.state='".$get_data['state']."'";
								}
								
								if (isset($get_data['shop_name'])) 
								{
										$data['shop_name'] = $get_data['shop_name'];
										if ($get_data['shop_name']) $where .= " and s.name like '%".$get_data['shop_name']."%'";
								}								
						}						
						if (isset($get_data['page'])) $page = intval($get_data['page']);
				}
				/* 分页begin */
				$this->load->library('pagination');
				$this->load->helper('url');
				$config['base_url'] = 'seller_list';
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
				$query = $this->db->query("SELECT count(*) as total FROM ".$this->db->dbprefix('seller')." as s WHERE 1=1".$where);
				$row = $query->row_array();
				$config['total_rows'] = $row['total'];
				$config['num_links'] = 9;
				$this->pagination->initialize($config);
				$data['create_links'] = $this->pagination->create_links();
				/* 分页end */				
				$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix('seller')." as s WHERE 1=1".$where." ORDER BY s.id DESC LIMIT $start_limit,$tpp");
				foreach ($query->result_array() as $row)
				{
						if ($row['logo_url'])
						{
								$row['seller_logo'] = $this->config->item('server_upload_url').$row['logo_url'];
						}else
						{
								$row['seller_logo'] = '';
						}
						//商家类型列表						
						$row['seller_category_list'] = $this->seller_category_list($row['id']);
						$row['seller_state'] = $this->seller_state($row['state']);
						$datalist[] = $row;
				}
				$data['data_list'] = $datalist;				
				if ($isajax)
				{
						$msg = 'ok';
						$content = '';
						$arr = array("msg" => $msg,"data_list" => $datalist,'create_links' => $data['create_links'],"content" => $content);
						$return_str = json_encode($arr);
						exit($return_str);
				}else
				{
						$this->isloadtemplate = 1;
				}
		}else
		{
				$id = $post_data['id'];
				$result = $this->check_seller_id($id);
				if ($result)
				{						
						if ($post_data['ac'] == 'update')
						{
								$action = $post_data['action'];								
								switch($action)
								{
										case 'SELLER_LOGIN'://商家免密登录
												$passwd = $post_data['passwd'];
												$arr = $this->seller_login($id,$passwd);
										break;
										case 'RESET_PASSWD'://重置商家密码
												$mobile = $post_data['mobile'];
												$arr = $this->reset_passwd($id,$mobile);
										break;
										case 'RESET_STATUS'://更新商家账户状态
												$status = $post_data['status'];
												$arr = $this->reset_status($id,$status);
										break;
								}
								$result = $arr['result'];
								
								if ($result)
								{								
										$msg = 'ok';
										$content = '';
								}else
								{
										$msg = 'failed';
										$content = $arr['content'];
								}
								$arr = array("msg" => $msg,"content" => $content);
								$return_str = json_encode($arr);
								exit($return_str);
						}
				}else
				{
						$data['validation_errors'] = lang('seller_list_error_1');		
						$this->isloadtemplate = 2;
				}
		}
		$this->db->close();		
		if($this->isloadtemplate == 1)
		{
				$this->parser->parse('seller_list_template', $data);				
		}elseif ($this->isloadtemplate == 2)
		{
				$this->parser->parse('error_message_template', $data);
		}
		
	}
	
	public function check_seller_id($id)
	{
		$result = 0;
		$query = $this->db->query("SELECT count(*) as total FROM ".$this->db->dbprefix('seller')." WHERE id=$id LIMIT 1");
		$row = $query->row_array();
		if ($row['total']) $result = 1;		
		return $result;
	}
	
	public function reset_passwd($id,$mobile)
	{
		$arr = array();
		$arr['result'] = 0;
		$arr['content'] = '';
		
		$rand_passwd = $this->waimai_admin->strgetrand(6,6);
		//$rand_passwd = '123123BAK';//测试用
		$rand_md5passwd = md5($rand_passwd);
		$d = array(
		    'password' => $rand_md5passwd
		);
		$w = array(								    
		    'id' => $id
		);
		$this->db->update($this->db->dbprefix('seller'),$d,$w);
		$result = $this->db->affected_rows();
		if ($result)
		{
				$arr['result'] = 1;
				//下发新密码短信
				$message = lang('seller_list_reset_passwd_1');
				$message = str_replace("{password}",$rand_passwd,$message);
				$str_result = $this->waimai_admin->send_sms($mobile,$message);
		}else
		{
				$arr['content'] = lang('seller_list_error_3');
		}
		return $arr;
	}
	
	public function seller_login($id,$passwd)
	{
		$arr = array();
		$arr['result'] = 0;
		$arr['content'] = '';
		
		$md5_passwd = md5($passwd);
		$query = $this->db->query("SELECT id FROM ".$this->db->dbprefix('admin_user')." WHERE id='".$this->admin_id."' and password='".$md5_passwd."' and status=1 LIMIT 1");
		$row = $query->row_array();
		if ($row)
		{
				$query = $this->db->query("SELECT id,name,status,logo_url FROM ".$this->db->dbprefix('seller')." WHERE id=$id and status=1 LIMIT 1");
				$row = $query->row_array();
				if ($row)
				{
						//相关登录逻辑处理
						$arr['result'] = 1;
				}
		}else
		{
				$arr['content'] = lang('seller_list_error_2');
		}
		return $arr;
	}
	
	public function reset_status($id,$status)//更新商家状态
	{
		$arr = array();
		$arr['result'] = 0;
		$arr['content'] = '';
				
		$d = array(
		    'status' => $status
		);
		$w = array(								    
		    'id' => $id
		);
		$this->db->update($this->db->dbprefix('seller'),$d,$w);
		$result = $this->db->affected_rows();
		if ($result)
		{
				$arr['result'] = 1;
		}
		return $arr;
	}
	
	public function select_seller_category($v)
	{
		$strHtml = '';
		$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix('seller_category')." WHERE parent_id=0");
		$row = $query->row();
		foreach ($query->result_array() as $row)
		{				
				$strHtml .= '<option value="'.$row['id'].'"';
				if ($row['id'] == $v) $strHtml .= ' selected';
				$strHtml .= '>'.$row['name'].'</option>';
		}
		return $strHtml;		
	}
	
	public function seller_category_list($seller_id)
	{
		$str = '';
		$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix('seller_seller_category')." WHERE seller_id=".$seller_id);
		$row = $query->row();
		foreach ($query->result_array() as $row)
		{				
				if ($str == '')
				{
						$str = $this->seller_category_value($row['category_id']);
				}else
				{
						$str .= ','.$this->seller_category_value($row['category_id']);
				}
		}
		return $str;
	}
	
	public function seller_category_value($category_id)
	{
		$str = '';
		$query = $this->db->query("SELECT name FROM ".$this->db->dbprefix('seller_category')." WHERE id=$category_id LIMIT 1");
		$row = $query->row_array();
		$str = $row['name'];
		return $str;
	}
	
	public function seller_state($value)
	{
		$this->lang->load('seller_list');
		$str = '';
		switch($value)
		{
			case 'WORK':
				$str = lang('seller_list_state_1');
			break;
			case 'NO_WORK':
				$str = lang('seller_list_state_0');
			break;
		}
		return $str;
	}
	
}
 