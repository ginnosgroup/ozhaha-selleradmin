<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

	public $seller_id;
	
	/**
	 * author:dmh describe:ÉÌ¼ÒµêÆÌÉèÖÃ
	 * 
	 */
	public function index()
	{
		$this->load->library('parser');		
		$this->load->helper('language');
		$this->load->helper('file');
		$this->load->library('session');		
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		$this->load->library('waimai_seller');
		$seller_id = $this->waimai_seller->check_login();
		$this->seller_id = $seller_id;
		$this->isloadtemplate = 0;		
		$data = array(
		    'static_base_url' => $this->config->item('static_base_url'),
		    'seller_name' => $this->session->seller_name,
		    'logo_url' => ($this->cache->get('logo_url'.$seller_id)!='uploads/')?($this->cache->get('logo_url'.$seller_id)):"",
		);
		$data['validation_errors'] = '';
		$data['result_success'] = '';
		$post_data = $this->input->post('data[]', true);
		$this->load->database();
				
		$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix('seller')." WHERE id='$seller_id' limit 1");
		$row = $query->row_array();
		$curr_background_url = $row['background_url'];

		//var_dump($row);
		if ($row)
		{
				$upload_dir = $this->config->item('upload_root_path');
				$upload_dir = str_replace('./','', $upload_dir);
				if ($row['logo_url'])
				{
						$data['post_data[shop-logo]'] = $upload_dir.$row['logo_url'];
						$old['shop-logo'] = $data['post_data[shop-logo]'];
						$data['post_data[shop-logo]'] = '<a href="'.$data['post_data[shop-logo]'].'" target="_blank">'.$data['post_data[shop-logo]'].'</a>';
				}else
				{
				 		$data['post_data[shop-logo]'] = '';
				}
				if ($row['background_url'])
				{
						$data['post_data[shop-cover]'] = $upload_dir.$row['background_url'];
						$old['shop-cover'] = $data['post_data[shop-cover]'];
						$data['post_data[shop-cover]'] = '<a href="'.$data['post_data[shop-cover]'].'" target="_blank">'.$data['post_data[shop-cover]'].'</a>';
				}else
				{
				 		$data['post_data[shop-cover]'] = '';
				}
				if (empty($post_data))
				{
						$data['make_form_seller_category'] = $this->waimai_seller->make_form_seller_category($seller_id,array());
  						//Joe
						$data['make_rest_days_form'] = $this->waimai_seller->make_rest_days_form($seller_id);
						$data['post_data[seller_region_id]'] = $row['seller_region_id'];
						//End
						$data['post_data[name]'] = $row['name'];												
						$data['post_data[address]'] = $row['address'];
						$data['post_data[coordinate]'] = $row['address_lat'].','.$row['address_lon'];
						$data['post_data[phone]'] = $row['phone'];
						$data['post_data[email]'] = $row['email'];
						if ($row['state'] == 'WORK')
						{
								$int_state = 1;
						}else
						{
								$int_state = 0; 	
						}
						$data['post_data[state]'.$int_state] = $this->waimai_seller->set_form_selected($int_state,2);
						//$data['post_data[hours]'] = $row['business_start_time'].'-'.$row['business_end_time'];
						$data['post_data[hours]'] = $row['business_times'];
						$data['post_data[lowest_price]'] = $row['lowest_price'];
						if ($row['delivery_type'] == 'SELF')
						{
								$int_type = 0;
						}else
						{
								$int_type = 1; 	
						}
						$data['post_data[delivery_type]'.$int_type] = $this->waimai_seller->set_form_selected($int_type,2);
						$data['post_data[notice]'] = $row['notice'];
						//var_dump($data);				
						$this->isloadtemplate = 1;	
				}
				else
				{
						$data['make_form_seller_category'] = $this->waimai_seller->make_form_seller_category($seller_id,$post_data['category_id']);
						//JOe
						$data['make_rest_days_form'] = $this->waimai_seller->make_rest_days_form($seller_id);
						//End
						$this->load->library('form_validation');
						$this->lang->load('shop');
						
						foreach ($post_data as $k=>$v)
						{
								if ($k == 'state')
								{
									$data['post_data['.$k.']'.$v] = $this->waimai_seller->set_form_selected($v,2);
								}else
								{
									$data['post_data['.$k.']'] = $v;				 	
								}				
						}
						$this->form_validation->set_rules('data[name]', lang('shop_name'), 'required','max_length[32]');
						/*$this->form_validation->set_rules('data[category_id[]]', '', 'in_list['.$this->waimai_seller->str_shop_type_list.']',
								array('in_list' => lang('shop_validation_4'))
						);*/
						$this->form_validation->set_rules('data[category_id]', '', 'required',
								array('required' => lang('shop_validation_4'))
						);
						$this->form_validation->set_rules('data[address]', lang('shop_address'), 'required|max_length[128]');
						$this->form_validation->set_rules('data[coordinate]', lang('shop_coordinate'), 'required');
						$this->form_validation->set_rules('data[phone]', lang('shop_phone'), 'required','max_length[16]');
						$this->form_validation->set_rules('data[hours]', lang('shop_hours'), 'required');
						$this->form_validation->set_rules('data[lowest_price]', lang('shop_lowest_price'), 'required|numeric');

						$this->form_validation->set_rules('data[seller_region_id]', '', 'required');
						
						if ($this->form_validation->run() == FALSE)
				    	{		    		  		
				    		$data['validation_errors'] = validation_errors();
								$this->isloadtemplate = 1;
						}
						else
						{
								
								$query = $this->db->query("SELECT count(*) as total FROM ".$this->db->dbprefix('seller')." WHERE id!='$seller_id' and name=".$this->db->escape($post_data['name'])." limit 1");
								$row = $query->row_array();
								//var_dump($curr_background_url);
								var_dump($post_data['seller_region_id']);
								if (!$row['total'])
								{
																				
										$post_data['shop-logo'] = $this->waimai_seller->do_upload_shop_image('shop-logo','logo','gif|jpg|png',2048,1024,768);
										$post_data['shop-cover'] = $this->waimai_seller->do_upload_shop_image('shop-cover','cover','gif|jpg|png',2048,1024,768);
										//$this->cache->save('logo_url'.$seller_id, $upload_dir.$post_data['shop-logo'], 2592000);
										//É¾³ý¾ÉµÄshop-logo/shop-cover												
										if ($post_data['shop-logo'] && file_exists('./'.$old['shop-logo'])) unlink('./'.$old['shop-logo']);
										if ($post_data['shop-cover'] && file_exists('./'.$old['shop-cover'])) unlink('./'.$old['shop-cover']);
										//·Ö¸îlon/lat
										$lonlat = explode(",",$post_data['coordinate']);
										$post_data['address_lon'] = $lonlat[1];
										$post_data['address_lat'] = $lonlat[0];
										//·Ö¸îÓªÒµÊ±¼ä
										if ($post_data['state'] == 1)
										{
												$post_data['str_state'] = 'WORK';
										}else
										{
										 		$post_data['str_state'] = 'NO_WORK';
										}
										//here to code shop cover upload; 2017/06/21 Joe 
										
										//$cover_url = $this -> rand_seller_category_cover($seller_id);

										//End
										//¸üÐÂ²Ù×÷							
										$d = array(
										    'gmt_modify' => date('Y-m-d H:i:s',time()),
										    'name' => $post_data['name'],
										    'address' => $post_data['address'],
										    'address_lon' => $post_data['address_lon'],
										    'address_lat' => $post_data['address_lat'],
										    'phone' => $post_data['phone'],
										    'email' => $post_data['email'],
										    'state' => $post_data['str_state'],
										    // 'business_start_time' => $post_data['business_start_time'],
										    // 'business_end_time' => $post_data['business_end_time'],
											'business_times' =>$post_data['hours'],
											'seller_region_id' => $post_data['seller_region_id'],
										    'lowest_price' => $post_data['lowest_price'],
										    'delivery_type' => $post_data['delivery_type'],
										    'notice' => $post_data['notice']
										);
										if ($post_data['shop-logo']) $d['logo_url'] = $post_data['shop-logo'];
										//JOe
										if ($post_data['shop-cover']) $d['background_url'] = $post_data['shop-cover']; 
       								    elseif(empty($curr_background_url)) $d['background_url'] = $this -> rand_seller_category_cover($seller_id);
                                        $arr = array();
                                        if(isset($post_data['rest_days'])) $arr['rest_days'] = $post_data['rest_days'];
                                        //if(isset($post_data['rest_dates'])) $arr['rest_dates'] = $post_data['rest_dates'];

                                        $this->udpate_seller_rest_days($arr);
                                        //JOe
										$data['make_rest_days_form'] = $this->waimai_seller->make_rest_days_form($seller_id);
										    //End
										//End
										$w = array(
												'id' => $seller_id
										);								
										$this->db->update($this->db->dbprefix('seller'),$d,$w);
										//¸üÐÂÉÌ¼Ò·ÖÀà
										$result = $this->update_seller_category($post_data['category_id']);
										$data['result_success'] = lang('shop_success_1');
										$this->isloadtemplate = 1;										
								}else
								{
								 		$data['validation_errors'] = lang('shop_error_2');
										$this->isloadtemplate = 1;	
								}
						} 	
				}
		}else
		{
				$data['validation_errors'] = lang('shop_error_1');
				$this->isloadtemplate = 1;
		}
		$this->db->close();
		if($this->isloadtemplate)
		{
				$this->parser->parse('shop_template', $data);	
		}else
		{
				 	
		}
	}
	
	//Joe
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
   	$rest_days = isset($arr['rest_days'])?$arr['rest_days']:array();
   	$rest_dates = isset($arr['rest_dates'])?$arr['rest_dates']:array();
    $c_days =array();
    $c_dates = array();
   	$query = $this->db->query("SELECT id, type, value FROM ".$this->db->dbprefix('seller_restdate')." WHERE seller_id=".$seller_id);
	$curr_rests = $query->result_array();

    foreach($curr_rests as $rest)
    {
      if($rest['type'] == 'WEEK')
      	$c_days[] = $rest['value'];
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

   	$query = $this->db->query("SELECT id,type,value FROM ".$this->db->dbprefix('seller_restdate')." WHERE seller_id=".$seller_id.'');
    $curr_rests = $query->result_array();
    foreach($curr_rests as $rest)
    {
    	if($rest['type'] == 'WEEK')
        $c_days[] = $rest['value'];
    }   
    foreach($rest_days as $day)
   	{
  		//var_dump(!in_array($day, $c_days1));
        if(!in_array($day, $c_days))
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

   //End
	
	public function update_seller_category($arr)//¸üÐÂÉÌ¼ÒÀàÐÍ
	{
		$query = $this->db->query("SELECT id FROM ".$this->db->dbprefix('seller_category')." WHERE 1=1");
		$row = $query->row();
		foreach ($query->result_array() as $row)
		{
				$category_id = $row['id'];
				if (in_array($category_id,$arr))
				{
						$query1 = $this->db->query("SELECT count(*) as total FROM ".$this->db->dbprefix('seller_seller_category')." WHERE seller_id=".$this->seller_id." and category_id=".$category_id." LIMIT 1");
						$row1 = $query1->row_array();
						if (!$row1['total'])
						{
								$d = array(
								    'gmt_create' => date('Y-m-d H:i:s',time()),
								    'seller_id' => $this->seller_id,
								    'category_id' => $category_id
								);								
								$this->db->insert($this->db->dbprefix('seller_seller_category'),$d);
						}
				}else
				{
						$w = array(
								'seller_id' => $this->seller_id,
								'category_id' => $category_id
						);
						$this->db->delete($this->db->dbprefix('seller_seller_category'),$w);
				}
		}
		foreach($arr as $v)
		{				
				$query = $this->db->query("SELECT count(*) as total FROM ".$this->db->dbprefix('seller_seller_category')." WHERE seller_id=".$this->seller_id." and category_id=".$v." LIMIT 1");
				$row = $query->row_array();
				if (!$row['total'])
				{
						$d = array(
						    'gmt_create' => date('Y-m-d H:i:s',time()),
						    'seller_id' => $this->seller_id,
						    'category_id' => $v
						);								
						$this->db->insert($this->db->dbprefix('seller_seller_category'),$d);
				}
		}
	}


	public function seller_regions(){
		$post_data = $this->input->post('data[]', true);
		$this->load->database();
		$query = $this->db->query("SELECT id, name, parent_id  FROM ".$this->db->dbprefix('seller_region'));
		$rows = $query->result_array();
		$sub_regions = array();
		
		foreach($rows as $row)
		{
			if(!$row['parent_id'])
			{
				
				$main_regions_group[$row['name']] = $row['id'];
			}
			else{
				$sub_regions[] = $row;
			}

		}
		foreach($sub_regions as &$sub)
		{
			$sub["group"] = array_keys($main_regions_group,$sub['parent_id'])[0];
		}

		array_multisort(array_column($sub_regions, 'group'),$sub_regions);

		$this->db->close();
		exit(json_encode($sub_regions));
	}
	
}
