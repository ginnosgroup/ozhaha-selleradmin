<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class view_order extends CI_Controller {

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
		//Joe
		$deliver_name = $this->input->get('deliver_name', true);
		$deliver_phone =$this->input->get('deliver_phone', true);
		//End
		$data['validation_errors'] = '';
		$data['result_success'] = '';
		
		$post_data = $this->input->post('data[]', true);
		$id = intval($this->input->get('id', true));
		$this->lang->load('view_order');
		$this->load->database();
		if (empty($post_data))
		{				
				if ($id)
				{
						//Joe
						$query = $this->db->query("SELECT o.*, s.name as seller_name,s.phone as seller_phone,s.address as seller_address,s.address_detail as seller_address_detail FROM ".$this->db->dbprefix('order').' o'.' INNER JOIN '.$this->db->dbprefix('seller').' s'. ' ON o.seller_id = s.id '." WHERE o.seller_id='$seller_id' AND o.id=$id");
						//$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix('order')." WHERE seller_id='$seller_id' and id=$id");
						$row = $query->row_array();
						
						//$seller_query  =  $this->db->query("SELECT * FROM ".$this->db->dbprefix('seller')." WHERE id='$seller_id'");
						//$seller_row = $seller_query->first_row();
						//End
						if ($row['id'])
						{
								foreach ($row as $key => $val)
								{
										if ($key == 'status')
										{
												$data['row[str_status]'] = $this->waimai_seller->order_status($val);
										}elseif ($key == 'delivery_type')
										{
												$data['row[str_delivery_type]'] = $this->waimai_seller->order_delivery_type($val);
										}elseif ($key == 'pay_type')
										{
												$data['row[str_pay_status]'] = lang('view_order_pay_status_0');
												if ($row['pay_type'] != 'OFFLINE' && $row['pay_type'] != 'OTHER')
												{
														if ($row['pay_code'] != '')
														{
																$data['row[is_receive]'] = 1;
																$data['row[str_pay_status]'] = lang('view_order_pay_status_1');
														}else
														{
																$data['row[is_receive]'] = 0;
														}
												}//Joe
										elseif($row['pay_type'] == 'OTHER'){
												$data['row[is_receive]'] = 0;
												$data['row[str_pay_status]'] = lang('view_order_pay_status_0');
  
										}
										//End
										else
												{
														$data['row[is_receive]'] = 1;
														if ($row['status'] == 'COMPLETE') $data['row[str_pay_status]'] = lang('view_order_pay_status_1');								
												}
										  //Joe
                                          $data['row[str_pay_type]'] = $this->waimai_seller->order_pay_type($val);
										  //End	
										}
										$data['row['.$key.']'] = $val;
								}
                                       //Joe 
										$data['row['.'order_total_price'.']'] = number_format(floatval($data['row['.'total_price'.']']) + floatval($data['row['.'delivery_price'.']']),2); 									   
									    $data['row['.'deliver_name'.']'] = $deliver_name;
										$data['row['.'deliver_phone'.']'] = $deliver_phone;	
                                        //End										
								//¶ÁÈ¡¶©²ÍÁÐ±í
								$query1 = $this->db->query("SELECT * FROM ".$this->db->dbprefix('order_item')." WHERE order_id=$id ORDER BY gmt_create ASC");
								foreach ($query1->result_array() as $row1)
								{
										$row1['item_price_total'] = $row1['item_price']*$row1['number'];										
										$datalist[] = $row1;
								}
								$data['data_list'] = $datalist;
                              	//Joe
								$data['row['.'seller_phone'.']'] = $row['seller_phone'];
								$data['row['.'seller_name'.']'] = $row['seller_name'];
								$data['row['.'seller_address'.']'] = $row['seller_address'];
								$data['row['.'seller_address_detail'.']'] = $row['seller_address_detail'];
								$balance_logs = $this-> balance_log($id,$seller_id);
								$data['row['.'balance_logs'.']'] = isset($balance_logs)?$balance_logs:'';
								//End
						}else
						{
								$data['validation_errors'] = lang('view_order_error_1');
						}
				}				
				$this->isloadtemplate = 1;
		}
		$this->db->close();
		if($this->isloadtemplate)
		{
				
			$this->parser->parse('view_order_template', $data);
		}
	}
	
	function balance_log($order_id, $seller_id)
	{
      $query =  $this->db->query('SELECT * FROM '. $this->db->dbprefix('balance_log'). " WHERE order_id = '$order_id' AND seller_id ='$seller_id' LIMIT 10");
      $rows =  $query->result_array();
      $logs_str = '';
     if($rows)
     {
      foreach($rows as $row)
      {
      	if($row['note'])
      	$logs_str .= '<div>'.$row['note'].'</div>';
      } 
     }
      return $logs_str;

	}
}
