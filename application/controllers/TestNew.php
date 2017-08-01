<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TestNew extends CI_Controller {

public $seller_id;

public function index()
{
  $this->load->library('parser');
  $this->load->library('waimai_seller');	
  $seller_id = $this->waimai_seller->check_login();	
  $date = new DateTime();
  $this->seller_id = $seller_id;
  $this->load->database();	
   //file_put_contents('./logs/test'.$date->format('Ymd').'.txt', "Im ur mother fuker;\r\n", FILE_APPEND | LOCK_EX);
  $post_data = $this->input->post('data[]', true);
   echo 'today:'. $date->format('Y/m/d H:i:s');
   //echo 'try:' $date('l');
   $rests = $this -> get_seller_rest_date($seller_id);
   $rest_days = array();
   $rest_dates = array();
   foreach($rests as $rest)
   {
     if($rest['type'] == 'WEEK')
     	$rest_days[] =$rest['value'];
     if($rest['type'] == 'DEFAULT')
     	$rest_dates[] =$rest['value'];
   }
   // var_dump($rest_days);
   echo '<br><br><br>';
  // echo $this->make_restdates_form($seller_id);

  // echo $this->waimai_seller->make_form_seller_category($seller_id,array());
  // var_dump($this->rand_seller_category_cover($seller_id));

   if($post_data['rest_days']||$post_data['rest_dates'])
   {
    	$arr = array();
     	if($post_data['rest_days']) $arr['rest_days'] = $post_data['rest_days'];
     	//if($post_data['rest_dates']) $arr['rest_dates'] = $post_data['rest_dates'];
     	$this->udpate_seller_rest_days($arr);
   }	 //echo $today = DaysOfWeek::;

  echo '<br><br><br>';
  echo '<form method="post" action="">';
  echo  $this ->make_restdates_form($seller_id);
  echo '<div><input type ="submit" name ="submit">submit</div>';
  echo '</form>';
  echo '<br><br><br>';

 $this->db->close();

}

function make_restdates_form($seller_id, $cur_status='')
{
   
   $main_str = '';
   $rests = $this -> get_seller_rest_date($seller_id);
   $rest_days = array();
   $rest_dates = array();
   foreach($rests as $rest)
   {
     if($rest['type'] == 'WEEK')
     	$rest_days[] =$rest['value'];
     if($rest['type'] == 'DEFAULT')
     	$rest_dates[] =$rest['value'];
   }
      //$rest_dates[] = '2017-06-25';
     // $rest_dates[] = '2017-07-04';
     $main_str .= $this -> regular_day_options($rest_days);
     //$main_str .= $this -> holiday_day_options($rest_dates,date('Y-m-d'),date('Y-m-d',strtotime("+6 days")));
    // $main_str .= $this -> holiday_day_options($rest_dates,date('Y-m-d',strtotime("+7 days")),date('Y-m-d',strtotime("+13 days")));
    return $main_str;
}
function holiday_day_options($rest_dates,$start_date,$end_date)
{
  $this->load->helper('date');
  $week_days = array(
      'Sun' =>0,
      'Mon' =>1,
      'Tue' =>2,
      'Wed' =>3,
      'Thur' =>4,
      'Fri' =>5,
      'Sat' =>6,

    );

  $str = '';
  $period_range = date_range($start_date , $end_date);
  //var_dump($period_range);
  
   $str .= '<div>';
   $str .= '<label> rest dates: </label>';
   foreach($period_range as $date)
  {
	 $str .= '<label class="checkbox-inline"><input type="checkbox" class="checkbox" id="restDates" name="data[rest_dates][]" value='.$date;
    if($this->is_rest_day($rest_dates,$date))
   {
   	$str .=' checked';
   } 
    $str .= '>'.$date;	
    
    $str .= '<span><label>'.array_search(date('w', strtotime($date)),$week_days).'</label></span></label>';
  }
  $str .= '</div>';
  return $str;
}

function regular_day_options($rest_days,$cur_days='')
{
  $str = '';
  $temp_day;
  $week_days = array(
    	'Sun' =>0,
    	'Mon' =>1,
    	'Tue' =>2,
    	'Wed' =>3,
    	'Thur' =>4,
    	'Fri' =>5,
    	'Sat' =>6,

    	);

  $str .= '<div>';
  $str .= '<labe>regular rest days: </label>';
  foreach($week_days as $k_d=>$v)
  {
	$str .= '<label class="checkbox-inline"><input type="checkbox" class="checkbox" id="restDays" name="data[rest_days][]" value='.$v;
    if($this->is_rest_day($rest_days,$v))
   {
   	$str .=' checked';
   } 
    $str .= '>'.$k_d;	
    
    $str .= '</label>';
  }
  $str .= '</div>';

  return $str;
}

function is_rest_day($rest_day,$day)
{
    if(in_array($day,$rest_day)) return 1;
    return 0;
}
 function get_seller_rest_date($seller_id)
 {
    	$query = $this->db->query("SELECT id, type, value FROM ".$this->db->dbprefix('seller_restdate')." WHERE seller_id=".$this->seller_id);
		$row = $query->result_array();

		return $row;
}

public function rand_seller_category_cover($seller_id)
   {
   	    $seller_category = $this->waimai_seller->get_seller_category($seller_id);
   	    if($seller_category)
   	    {
   	    	$rand_num = rand(0,count($seller_category)-1);
   	    	$rand_category_id = $seller_category[$rand_num]["category_id"];
    	    $query = $this->db->query("SELECT id, background_urls FROM ".$this->db->dbprefix('seller_category')." WHERE id=".$rand_category_id.' LIMIT 1');
			 $row = $query->row_array();
			 $cover_urls  = explode(',',$row['background_urls']);
			 $rand_url  = $cover_urls[rand(0,count($cover_urls)-1)];
			

			return $rand_url;
   	    }
   	    return '';
   }

     public function udpate_seller_rest_days($arr)
   {
   		$seller_id = $this->seller_id;
   		$rest_days =$arr['rest_days'];
      //$rest_dates =$arr['rest_dates'];
      $c_days =array();
      $c_dates = array();
   		//$rest_dates =$arr['rest_dates'];
   		//var_dump($rest_days);
   		$query = $this->db->query("SELECT id, type, value FROM ".$this->db->dbprefix('seller_restdate')." WHERE seller_id=".$seller_id);
		  $curr_rests = $query->result_array();

      foreach($curr_rests as $rest)
      {
        if($rest['type'] == 'WEEK')
        $c_days[] = $rest['value'];
       // if($curr_rests['type'] == 'DEFAULT')
       //  $c_dates =$curr_rests['value'];
     }
   		if($c_days)
   		{
   			foreach($c_days as $c_day)
   			{	
   				
   	 		 	  if(!in_array($c_day,$rest_days))
   	 		 		{
   	 		 			$w = array(
								        'seller_id' => $seller_id,
								         'value' => $c_day,
                         'type' => 'WEEK'
						            );
						  $this->db->delete($this->db->dbprefix('seller_restdate'),$w);
   	 		 		}
				  
   			}
   		}
   			 	$query1 = $this->db->query("SELECT id,type,value FROM ".$this->db->dbprefix('seller_restdate')." WHERE seller_id=".$seller_id.'');
          $curr_rests1 = $query1->result_array();

           foreach($curr_rests1 as $rest)
          {
            if($rest['type'] == 'WEEK')
            $c_days1[] = $rest['value'];
          }
          var_dump($c_days1);

          echo '<br><br><br>';

            foreach($rest_days as $day)
   					{
  						var_dump(!in_array($day, $c_days1));
              if(!in_array($day, $c_days1))
              {
                $d = array(
						    'gmt_create' => date('Y-m-d H:i:s',time()),
						    'seller_id' => $seller_id,
						    'type'      =>'WEEK',
						    'value' => $day
							);								
						  $this->db->insert($this->db->dbprefix('seller_restdate'),$d);
   					}
          }


   }

}
?>	

  

