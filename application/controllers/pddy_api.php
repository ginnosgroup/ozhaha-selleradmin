<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pddy_api extends CI_Controller {

public function index()
{
	$get_data = $this->input->get(NULL,true);
	//var_dump($post_data);
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
			if($out) $result = array('code'=> 0,'data' =>$out );
			else $result = array('code'=> 1,'data' =>'fail to update' );

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
		
		// $dm_uid = DB::result_first("SELECT uid FROM ".DB::table("common_member")." WHERE username='".$data['dm_username']."'");
		// if ($data['dispatchStatus'] == 'WORK')
		// {				
		// 		DB::query("UPDATE ".DB::table('dzapp_waimai_order')." SET dm_uid=".$dm_uid." WHERE panda_order_code='".$data['panda_order_code']."'");				
		// }else if ($data['dispatchStatus'] == 'WAIT')
		// {
		// 		DB::query("UPDATE ".DB::table('dzapp_waimai_order')." SET dm_uid=0 WHERE panda_order_code='".$data['panda_order_code']."'");
		// }else if ($data['dispatchStatus'] == 'FINISH')
		// {
		// 		DB::query("UPDATE ".DB::table('dzapp_waimai_order')." SET status=4,finish_time=".$_G['timestamp']." WHERE panda_order_code='".$data['panda_order_code']."'");
		// }
		// $result['code'] = 0;
		// $result['data'] = true;

        $this->db->close();
		return json_encode($result);
}
}
?>