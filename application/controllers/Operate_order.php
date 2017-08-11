<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class operate_order extends CI_Controller {

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
		
		$post_data = $this->input->post('data[]', true);
		$id = intval($this->input->get('id', true));
		$this->lang->load('operate_order');
		$this->load->database();
		$str_status = $this->check_order_id($seller_id,$id,$data);		
		if ($str_status == 'NEW')
		{
				if (empty($post_data))
				{
						$query = $this->db->query("SELECT delivery_type FROM ".$this->db->dbprefix('seller')." WHERE id='$seller_id'");
						$row = $query->row_array();
						if ($row['delivery_type'] == 'SELF')
						{
								$data['post_data[delivery_type]0'] = ' checked="checked"';
						}else
						{
								$data['post_data[delivery_type]1'] = ' checked="checked"';
						}
						$data['post_data[delivery_time]'] = '';
						$data['post_data[delivery_note]'] = '';
						$this->isloadtemplate = 1;
				}else
				{						
				 		$this->form_validation->set_rules('data[delivery_time]', lang('operate_order_delivery_time'), 'required');
				 				 				
				 		if ($this->form_validation->run() == FALSE)
				    {
				    		$data['validation_errors'] = validation_errors();
								$this->isloadtemplate = 1;
				    }else
				    {
				    		//接单处理 		
								$d = array(
								    'gmt_modify' => date('Y-m-d H:i:s',time()),
								    'status' => 'WAIT',
									'accept_order_time' => date('Y-m-d H:i:s',time()),
								    'delivery_time' => $post_data['delivery_time'],
								    'delivery_note' => $post_data['delivery_note'],
								    'delivery_type' => $post_data['delivery_type']
								);
								
								$w = array(								    
								    'id' => $id,
								    'seller_id' => $seller_id
								);						

								//Joe
								$query_str = 'SELECT o.*,(s.name) as seller_name FROM '. $this->db->dbprefix('order') .' o INNER JOIN '. $this->db->dbprefix('seller') .' s ON o.seller_id = s.id'
											." WHERE o.seller_id='$seller_id'". ' AND o.id ='.$id;
								$query = $this->db->query($query_str);
								$row = $query->row_array();

								if(!$row['delivery_code'])
								{
									$create_panda_order = $this->create_panda_order($id);
									if($create_panda_order['msg']=='ok')
									{
										$d['delivery_code']	=$create_panda_order['data'];
									}
								}

								try{
									
									$logged = $this ->write_to_order_log($row);
									$this->db->update($this->db->dbprefix('order'),$d,$w);
								}
								catch(Exception $e)
								{
									echo 'Caught exception : '. $e->getMessage().'\n';
								}
								
								//End
								
								//如果是Panda配送，同步订单信息到Panda Delivery API
								
								if($create_panda_order['msg']=='ok')
								{	
									$data['result_success'] = 'OPERATE SUCCESS, CODE: '.$row['delivery_code'];
								}
								else
								{
									$data['validation_errors'] = 'fail to create panda order';
								}
								redirect('/order');
								
				    }
				}
		}elseif ($str_status != 'NEW' && $str_status)
		{
				$data['validation_errors'] = lang('operate_order_error_2');			
				$this->isloadtemplate = 2;
		}else
		{
				$data['validation_errors'] = lang('operate_order_error_1');			
				$this->isloadtemplate = 2;
		}
		$this->db->close();
		if($this->isloadtemplate == 1)
		{
				$this->parser->parse('operate_order_template', $data);				
		}elseif ($this->isloadtemplate == 2)
		{
				$this->parser->parse('error_message_template', $data);
		}
	}
	
	public function check_order_id($seller_id,$id,$data)
	{
		$str_status = '';
		$query = $this->db->query("SELECT status FROM ".$this->db->dbprefix('order')." WHERE seller_id='$seller_id' and id=$id LIMIT 1");
		$row = $query->row_array();
		if ($row['status'])
		{
				$str_status = $row['status'];
		}
		return $str_status;
	}

	 public function write_to_order_log($order_details)//
	{
	 $today = new DateTime();
	 $operator_type = 'SELLER';
	 $operator = 'WAIT'; 
	 $insert_data = array(
                     'gmt_create' =>$today->format('Y-m-d H:i:s'),
                     'gmt_modify' =>$today->format('Y-m-d H:i:s'),
                     'order_id' =>$order_details['id'],
                     'operator_id' => $order_details['seller_id'],
                     'operator_name' => $order_details['seller_name'],
                     'operator_type' =>$operator_type,
                     'operator' => $operator
	  	);
	  return $this->db->insert($this->db->dbprefix('order_log'), $insert_data);
       
	}

	public function create_panda_order($id)
	{
		$list=array();
        $config['deliver_request_curl'] = $this->config->item('ozhaha_backend_url').'/backend/Pandadelivery_api?mod=order_create_panda&order_id='.$id;
        $config['curl_data'] = '';
        $config['curl_refer'] = $this->config->item('curl_refer');
        $config['curl_timeout'] = $this->config->item('curl_timeout'); 
        $header = $this->waimai_seller->request_api_header();
        $out = $this->waimai_seller->panda_submit_curl('get',$header,$config);
		if ($out)
		{				
				$arr = json_decode(substr($out, 3),true);
				
			   if ($arr['code'] == 0) {
			   	                         $list['msg']='ok'; 
			   	                         $list['data'] = $arr['data'];
			   	                     	} 
			   else { 
			   	                                  
			   	    $list['msg']='fail'; 
			   	    $list['content'] ='';
					$list['code'] = $arr['code'];
					$list['data'] = '';
			        }
		}	
        else 
		{
			$list['msg'] ='error';
			$list['content'] ='';
			$list['code'] = $arr['code'];
		}			
		unset($out);
       
		return $list;

	}

}
