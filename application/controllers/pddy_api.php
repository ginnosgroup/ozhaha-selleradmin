<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pddy_api extends CI_Controller {

	public function index()
	{
		$get_data = $this->input->get(NULL,true);
		$this->load->library('waimai_seller');
		if(!$get_data)
		{   
			$result =array(
				'code' => 1,
				'data' => false
				);
			echo json_encode($result) ;
		}
		else
		{

			switch($get_data['ac'])
			{
				case 'waimai_deliver_update_status':
				$result = $this->waimai_deliver_update_status($get_data);
				echo $result;
				break;
			}
		}
	}

	function waimai_deliver_update_status($data)
	{
		
		$result = array();
		$this->load->database();	
		switch(strtoupper($data['status']))
		{
			case 'DELIVERY':
			$update_data = array(
				'status' => strtoupper($data['status'])

				);
			$this->db->where('delivery_code', $data['order_code']);
			$out = $this->db->update('ozhaha_wm_order', $update_data);
			if($out) $result = array('code'=> 0,'data' =>$out );
			else $result = array('code'=> 1,'data' =>'fail to update' );
			break;
			case 'DONE':
			$update_data = array(
				'status' => strtoupper($data['status']),
				'done_order_time' => $data['done_order_time'],
				'pay_code' => $data['pay_code']

				);
			$this->db->where('delivery_code', $data['order_code']);
			$out = $this->db->update('ozhaha_wm_order', $update_data);
			if($out) {
				$result = array('code'=> 0,'data' =>$out );
				$order = $this->order_details($data['order_code']);
				if($order)
				{
					$logged = $this->waimai_seller->write_to_order_log('DONE',$order);

				}
			}
			else $result = array('code'=> 1,'data' =>'fail to update' ,'logged'=>$logged);

			break;
			case 'WAIT':
			$update_data = array(
				'status' => strtoupper($data['status']),

				);
			$this->db->where('delivery_code', $data['order_code']);
			$out = $this->db->update('ozhaha_wm_order', $update_data);
			if($out) $result = array('code'=> 0,'data' =>$out );
			else $result = array('code'=> 1,'data' =>'fail to update' );
			break;


		}

		$this->db->close();
		return json_encode($result);
	}

	private function order_details($order_code)
	{

		$query_str = 'SELECT o.*,(s.name) as seller_name FROM '. $this->db->dbprefix('order') .' o INNER JOIN '. $this->db->dbprefix('seller') .' s ON o.seller_id = s.id'
		." WHERE o.delivery_code ='". $order_code."'";
		$query = $this->db->query($query_str);
		$row = $query->row_array();
		return $row;
	}
}
?>