<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

	/**
	 * author:dmh describe:订单管理
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
		
		$post_data = $this->input->post(NULL,true);
		$get_data = $this->input->get(NULL,true);
		$this->lang->load('order');
		$this->load->database();				
		if (empty($post_data))
		{
			$isajax = 0;
			$status = 0;
			$page = 1;
			$where = "";
			$datalist = array();
			$data['get_status'] = '';
			$data['order_id'] = '';
			$data['query_string'] = ($_SERVER["QUERY_STRING"] != '' ? '?'.$_SERVER["QUERY_STRING"]:'');
			if (!empty($get_data))
			{
				$isajax = (isset($get_data['isajax']) ? $get_data['isajax']:'0');
				if (isset($get_data['ac']) == 'search')
				{
					if (isset($get_data['status']))
					{
						$data['get_status'] = $get_data['status'];	
						$status = $get_data['status'];
						if ($get_data['status']) $where = " and status='".$status."'";
					}								
				}
				if (isset($get_data['order_id'])) 
				{
					$data['order_id'] = $get_data['order_id'];
					if ($get_data['order_id']) $where .= " and id='".$get_data['order_id']."'";
				}
				if (isset($get_data['page'])) $page = intval($get_data['page']);
			}
			/* 分页begin */
			$this->load->library('pagination');
			$this->load->helper('url');
			$config['base_url'] = 'order';
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
			$query = $this->db->query("SELECT count(*) as total FROM ".$this->db->dbprefix('order')." WHERE seller_id=$seller_id".$where);
			$row = $query->row_array();
			$config['total_rows'] = $row['total'];
			$config['num_links'] = 9;
			$this->pagination->initialize($config);
			$data['create_links'] = $this->pagination->create_links();
			/* 分页end */
			$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix('order')." WHERE seller_id=$seller_id".$where." ORDER BY id DESC LIMIT $start_limit,$tpp");
			foreach ($query->result_array() as $row)
			{
					  //Joe
				if(!empty($row['delivery_code'])) 
				{
					$deliver_info = $this->deliver_details($row['delivery_code']);
					if($deliver_info['msg'] == 'ok')
					{
						$row["deliver_name"] = (isset($deliver_info['content']['name']))?$deliver_info['content']['name']:'';
						$row["deliver_phone"] = (isset($deliver_info['content']['name']))?$deliver_info['content']['phone']:'';
					}
					else {
						$row["deliver_name"] = "not assigned";
						$row["deliver_phone"] = "-";
					}

				}
				else{

					$row["deliver_name"] = "";
					$row["deliver_phone"] = "";
				}
                         //End
				$row['show_path'] = 0;
				if ($row['delivery_type'] == 'PANDA')
				{
					if ($row['status'] == 'DELIVERY' || $row['status'] == 'COMPLETE') $row['show_path'] = 1;
				}
				$row['item_list'] = $this->order_itemlist($row['id']);
				$row['str_status'] = $this->waimai_seller->order_status($row['status']);
				$row['str_pay_status'] = lang('order_pay_status_0');
				if ($row['pay_type'] != 'OFFLINE' && $row['pay_type'] != 'OTHER')
				{
					if ($row['pay_code'] != '')
					{
						$row['is_receive'] = 1;
						$row['str_pay_status'] = lang('order_pay_status_1');
					}else
					{
						$row['is_receive'] = 0;
					}
						}//Joe
						elseif($row['pay_type'] == 'OTHER')
						{
							$row['is_receive'] = 0;
							$row['str_pay_status'] = lang('order_pay_status_0');
							
						}
						 //End
						else
						{
							$row['is_receive'] = 1;
							if ($row['status'] == 'COMPLETE') $row['str_pay_status'] = lang('order_pay_status_1');								
						}
						//Joe
						$row['str_pay_type'] = $this->waimai_seller->order_pay_type($row['pay_type']);
						//End
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
				//Joe
					$id = $post_data['id'];
				//$str_status = $this->check_order_id($seller_id,$id);
					$order_details = $this->order_details($seller_id,$id);
					$str_status = $order_details['str_status'];
					$logged = '';
					$refunded = '';
				//End
					if ($str_status == 'NEW' || $str_status == 'WAIT' || $str_status == 'DELIVERY')
					{
						$status = $post_data['status'];
						if ($post_data['ac'] == 'update')
						{
								//如果Panda配送，取消订单时需要同步取消		
							$d = array(
								'gmt_modify' => date('Y-m-d H:i:s',time()),
								'status' => $status
								);
								//Joe

							if($status=='CANCEL')
							{
								if($order_details['pay_type'] != 'OTHER' && $order_details['pay_type'] != 'OFFLINE')
								{
									if($order_details['pay_type'] == 'BALANCE')
									{
										try{
											$refunded = $this->refund_to_buyer($order_details['buyer_id'],$order_details['req_refund_money']);
											$d['refund_price'] = $order_details['req_refund_money'];
										}
										catch(Exception $e)
										{
											echo 'error: '.$e->getMessage();
										}
									}
									$logged = $this->write_to_balance_log($order_details, 'refund'). $this->waimai_seller->write_to_order_log('CANCEL',$order_details);
									
								}
							} 
                            	//End
							$w = array(								    
								'id' => $id,
								'seller_id' => $seller_id
								);						
							$this->db->update($this->db->dbprefix('order'),$d,$w);
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
								//JOe
							if($status == 'CANCEL' && $order_details['pay_type'] != 'OTHER' && $order_details['pay_type'] != 'OFFLINE')
							{
								if($order_details['pay_type'] == 'BALANCE') $arr['has_refunded'] = $refunded;
							}
							$arr['has_logged'] = $logged;	
								//End
							$return_str = json_encode($arr);
							exit($return_str);
						}
					}else
					{
						$data['validation_errors'] = lang('order_error_1');		
						$this->isloadtemplate = 2;
					}
				}
				$this->db->close();
				if($this->isloadtemplate == 1)
				{
					$this->parser->parse('order_template', $data);				
				}elseif ($this->isloadtemplate == 2)
				{
					$this->parser->parse('error_message_template', $data);
				}
				
			}

	//Joe 26/07/2017

			public function create_panda_order()
			{
				$list=array();
				$config['deliver_request_curl'] = $this->config->item('deliver_request').'/employee/get?orderCode='.$order_code;
				$config['curl_data'] = '';
				$config['curl_refer'] = $this->config->item('curl_refer');
				$config['curl_timeout'] = $this->config->item('curl_timeout'); 
				$header = $this->waimai_seller->request_api_header();
				$out =$this->waimai_seller->panda_submit_curl('get',$header,$config);
				
				if ($out)
				{
					$arr = json_decode($out,true);
					
					if ($arr['code'] == '0') {
						$list['msg']='ok'; 
						$list['content'] = 'create success';
					} 
					else { 
						
						$list['msg']='error'; 
						$list['content'] ='';
						$list['code'] = $arr['code'];
					}
				}	
				else 
				{
					$list['msg'] ='fail';
					$list['content'] ='';
					$list['code'] = $arr['code'];
				}			
				unset($out);
				
				return $list;

			}
			
			
	//Joe 2016/06/10
			function deliver_details($order_code){

				$list=array();
				$config['deliver_request_curl'] = $this->config->item('deliver_request').'/employee/get?orderCode='.$order_code;
				$config['curl_data'] = '';
				$config['curl_refer'] = $this->config->item('curl_refer');
				$config['curl_timeout'] = $this->config->item('curl_timeout'); 
				$header = $this->waimai_seller->request_api_header();
				$out =$this->waimai_seller->panda_submit_curl('get',$header,$config);
				
				if ($out)
				{
					$arr = json_decode($out,true);
					
					if ($arr['code'] == '0') {
						$list['msg']='ok'; 
						$list['content'] = $arr['data'];
					} 
					else { 
						
						$list['msg']='error'; 
						$list['content'] ='';
						$list['code'] = $arr['code'];
					}
				}	
				else 
				{
					$list['msg'] ='fail';
					$list['content'] ='';
					$list['code'] = $arr['code'];
				}			
				unset($out);
				
				return $list;
			}
			
	// function panda_submit_curl($method,$headers,$config){
			
 //       $ch = curl_init();
	// 	curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
	// 	curl_setopt($ch, CURLOPT_URL, $config['deliver_request_curl']);
	// 	curl_setopt($ch, CURLOPT_REFERER, $config['curl_refer']); //¹¹ÔìÀ´Â·
	// 	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	// 	if ($method == 'post')
	// 	{
	// 			curl_setopt($ch, CURLOPT_POST, 1);
			
	// 	}elseif ($method == 'put')
	// 	{
	// 			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	// 	}
	// 	if ($method == 'post' || $method == 'put') curl_setopt($ch, CURLOPT_POSTFIELDS, $config['curl_data']);
	// 	curl_setopt($ch, CURLOPT_HEADER, 0);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	// 	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	// 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	// 	curl_setopt($ch, CURLOPT_TIMEOUT,$config['curl_timeout']);
	// 	curl_setopt($ch, CURLOPT_ENCODING, "");
	// 	$out = curl_exec($ch);
	// 	$httpinfo = curl_getinfo($ch);
	// 	curl_close($ch);
	// 	if ($httpinfo['http_code'] != '200') $out = '300';
	// 	//var_dump($httpinfo['http_code']);
	// 	return $out;
			
	// }
			
 //    function request_api_header()//ÉèÖÃheaderÍ·
	// {
	// 	$headers = array();
	// 	$headers[] = 'X-Apple-Tz: 0';
	// 	$headers[] = 'X-Apple-Store-Front: 143444,12';
	// 	$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
	// 	$headers[] = 'Accept-Encoding: gzip, deflate, sdch';
	// 	$headers[] = 'Accept-Language: zh-CN,zh;q=0.8';
	// 	$headers[] = 'Cache-Control: no-cache';
	// 	$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36';
	// 	$headers[] = 'X-MicrosoftAjax: Delta=true';
	// 	$headers[] = 'Content-type: application/json';
	// 	return $headers;
	// }

			function order_details($seller_id,$id)
			{
				$query = $this->db->query('SELECT o.*,(s.name) as seller_name FROM '. $this->db->dbprefix('order') .' o INNER JOIN '. $this->db->dbprefix('seller') .' s ON o.seller_id = s.id'
					." WHERE o.seller_id='$seller_id'". ' AND o.id ='.$id);
				$row = $query->row_array();
				$order_details = array();
				if ($row['total_price']&&$row['delivery_price'])
				{
					$order_details['req_refund_money'] = $this->refund_money($row['total_price'],$row['delivery_price']);
					$order_details['str_status'] =  $this->check_order_status($row['status']);
					foreach ($row as $k => $v)
					{
						$order_details[$k] = $v;
					}
				}

				return $order_details;
			}

			function refund_money($total_price, $delivery_price)
			{
				return floatval($total_price) + floatval($delivery_price);
			}
			
			
			function check_order_status($status)
			{
				$str_status = '';
				if ($status)
				{
					$str_status = $status;
				}
				return $str_status;
			}


	 public function write_to_balance_log($order_details,$ac = 'refund')//
	 {
	 //$this->load->helper('language');
	 	$today = new DateTime();
	 	if($ac=='refund')
	 	{
	 		$note = $today->format('Y-m-d H:i:s').'商家操作退款[退款支付方式:'.$order_details['pay_type'].']#退款$'.number_format($order_details['req_refund_money'],2).'至余额成功';
	 		$type = 'REFUND';
	 	}

	 	$insert_data = array(
	 		'gmt_create' =>$today->format('Y-m-d H:i:s'),
	 		'gmt_modify' =>$today->format('Y-m-d H:i:s'),
	 		'buyer_id' =>$order_details['buyer_id'],
	 		'seller_id' =>$order_details['seller_id'],
	 		'order_id' =>$order_details['id'],
	 		'type' =>$type,
	 		'note' =>$note
	 		);
	 	if($ac=='refund') 
	 	{
	 		$insert_data['price'] = $order_details['req_refund_money'];
	 	}
	 	return $this->db->insert($this->db->dbprefix('balance_log'), $insert_data);
	 	
	 }

	 function refund_to_buyer($buyer_id,$refund_money)
	 {
	 	$today = new DateTime();
	 	$query = $this->db->query("SELECT balance FROM ".$this->db->dbprefix('buyer')." WHERE id='$buyer_id' LIMIT 1");
	 	$current_balance = $query->row_array()['balance'];
	 	$v = array(
	 		'balance' => floatval($current_balance) + floatval($refund_money),
	 		'gmt_modify' =>$today->format('Y-m-d H:i:s')
	 		);
	 	$this->db->where('id', $buyer_id);
	 	return  $this->db->update($this->db->dbprefix('buyer'),$v);   
	 }
	//End
	 
	 public function order_itemlist($id)
	 {
	 	$item_list='';
	 	$query = $this->db->query("SELECT* FROM ".$this->db->dbprefix('order_item')." WHERE order_id=$id");
	 	foreach($query->result_array() as $row)
	 	{				
	 		if ($item_list=='')
	 		{
	 			$item_list = '<div>'.$row['item_name']. '<span style ="color:#ccc">*</span>' . $row['number'].'</div>';
	 		}else
	 		{
	 			$item_list .='<div>'.$row['item_name'] . '<span style ="color:#ccc">*</span>' . $row['number'].'</div>';
	 		} 
	 	}
	 	return $item_list;
	 }	
	 
	}
	