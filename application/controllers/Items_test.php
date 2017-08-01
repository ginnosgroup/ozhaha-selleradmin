<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items_test extends CI_Controller {

public $seller_id;//1000732

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
	//$this->seller_id  = $seller_id;
	$this->isloadtemplate = 0;		
	$data = array(
		    'static_base_url' => $this->config->item('static_base_url'),
		    'seller_name' => $this->session->seller_name,
		    'logo_url' => ($this->cache->get('logo_url'.$seller_id)!='uploads/')?($this->cache->get('logo_url'.$seller_id)):"",
		    'upload_root_path' => $this->config->item('upload_root_path')
			);
	$data['validation_errors'] = '';
	$data['result_success'] = '';
		
	$post_data = $this->input->post(NULL,true);
	$get_data = $this->input->get(NULL,true);
	$this->load->database();

	$query = $this->db->query("SELECT count(*) as total FROM ".$this->db->dbprefix('item')." WHERE seller_id=$seller_id".$seller_id);
	$row = $query->row_array();

	$this->db->close();
	$this->isloadtemplate = 1;
	if($this->isloadtemplate)
	{
		$this->parser->parse('Items_template', $data);	
	}
}

public function items_list()
{
	$this->load->library('waimai_seller');
	$seller_id = $this->waimai_seller->check_login();
	$data = array();
	$where = '';	
	$get_data = $this->input->get(NULL,true);
	$page = isset($get_data['page'])?$get_data['page']:1;
	$page_rows = isset($get_data['rows'])?$get_data['rows']:10;
	$sort = isset($get_data['sort'])?$get_data['sort']:'id';
	if($sort == 'category_pair'){$sort ='category_id'; }
	$order = isset($get_data['order'])?$get_data['order']:'asc';
	$offset = ($page-1)*$page_rows;
	$this->load->database();

	$query = $this->db->query("SELECT count(*) as total FROM ".$this->db->dbprefix('item')." WHERE seller_id=".$seller_id);
	$total = $query->row()->total;

	if (!empty($get_data['category_id'])) $where .= " AND category_id like '%".$get_data['category_id']."%'";
	if (!empty($get_data['keyword'])) $where .= " AND name like '%".$get_data['keyword']."%' 
												 OR	 id   like '%".$get_data['keyword']."%'";
											
	$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix('item')." WHERE seller_id=".$seller_id .$where.' ORDER BY '.$sort.' '.$order.' LIMIT '.$offset.','.$page_rows);
	$rows = $query->result_array();//echo 'lalaalla';

	foreach($rows as $row)
	{
		foreach($row as $k=>$v)
		{
			$item[$k] = $v;
			if($k =='category_id')
			{
				$item['category_pair'] = $this->get_category_name($v,$seller_id);
		 	}
			//$item['image_url'] = $this->image_tag($row['image_url']);
			
		}
		$data[] = $item;
	}	
   // $data = array_multisort(array_column($data,'weight'),$data);
	$arr = array('total'=>$total,'rows'=>$data);
	$this->db->close();
	exit(json_encode($arr));
}

public function update_items()
{

	$post_data = $this->input->post(NULL,true);
	$this->load->database();
	$update_items = $post_data['data'];	
	$updated;
	$update_rows;
	if($update_items)
	{
		foreach($update_items as $item)
		{
			$item['gmt_modify'] = date('Y-m-d H:i:s');
		}
		$update_rows = $this->to_item_model($update_items);
	}

	if(!empty($update_rows))
	{
		try{
			$updated = $this->db->update_batch($this->db->dbprefix('item'),$update_rows,'id');
		}
		catch(Exception $ex)
		{
			echo 'eror message: ' . $ex->getMesage();
		}
	}
	if($updated)
	{
	  $msg = array('msg'=>'ok', 'update_rows'=>$updated,);
	}
	else
	{
	  $msg = array('msg'=>'fail', 'update_rows'=>$updated,'details'=>$this->db->error());
	}
	$this->db->close();
	exit(json_encode($msg));

}

public function add_items()
{

	$this->load->library('waimai_seller');
	$this->load->database();
	$seller_id = $this->waimai_seller->check_login();
	$post_data = $this->input->post(NULL,true);
	$add_items = $post_data['data'];	
	$added; $insert_rows;
	if($add_items)
	{
		foreach($add_items as $item)
		{

			foreach($item as $k =>$v)
			{
			 $tmp[$k] =$v;
			}
		    $tmp['gmt_modify'] = date('Y-m-d H:i:s');
			$tmp['gmt_create'] = date('Y-m-d H:i:s');
			$tmp['number'] = $tmp['number']?$tmp['number']:100;
			$tmp['weight'] = $tmp['weight']?$tmp['weight']:1;
 			$tmp['seller_id'] = $seller_id;
		    $insert_rows[] =$tmp;
		}
		$insert_rows = $this->to_item_model($insert_rows);
	}
	if(!empty($insert_rows))
	{
		try{
			$added = $this->db->insert_batch($this->db->dbprefix('item'),$insert_rows);
		}
		catch(Exception $ex)
		{
			echo 'eror message: ' . $ex->getMesage();
		}
	}
	if($added)
	{
	  $msg = array('msg'=>'ok', 'update_rows'=>$added,'added_id'=>$this->db->insert_id());
	}
	else
	{
	  $msg = array('msg'=>'fail', 'update_rows'=>$added,'details'=>$this->db->error());
	}
	$this->db->close();
	exit(json_encode($msg));

}

public function delete_item(){

	$post_data = $this->input->post(NULL,true);
	$delete_items = $post_data['data'];	
	$updated;
	$del_row;
	if($delete_items)
	{
		//$item['gmt_modify'] = date('Y-m-d H:i:s');
		//$item['number'] = 0;
		$tmp[] = $delete_items;
		$del_row = $this->to_item_model($tmp)[0];
	}
	$this->load->database();
	
	//var_dump($del_row);
	if(!empty($del_row))
	{
		try{
			$updated = $this->db->delete($this->db->dbprefix('item'),$del_row);
		}
		catch(Exception $ex)
		{
			echo 'eror message: ' . $ex->getMesage();
		}
	}
	//$updated = $this->db->delete($this->db->dbprefix('item'),$del_row);

	if($updated)
	{
	  $msg = array('msg'=>'ok', 'updated_rows'=>$updated);
	}
	else
	{
	  $msg = array('msg'=>'fail', 'updated_rows'=>$updated,'details'=>$this->db->error());
	}
	$this->db->close();
	exit(json_encode($msg));

}

public function get_items_category()
{
		
	$get_data = $this->input->get(NULL,true);
	$this->load->database();
	$this->load->library('waimai_seller');
	$seller_id = $this->waimai_seller->check_login();
	$query_str = "SELECT id,name FROM ".$this->db->dbprefix('item_category')." WHERE seller_id=".$seller_id .'';
	if($get_data)
	{
		$query_str .= ' AND id = '. $get_data['category_id'];
	}
	$query = $this->db->query($query_str);
	$rows = $query->result_array();
	$out = array(); 
	foreach($rows as $row){

		foreach($row as $k=>$v)
		{
			if($k == 'name')
			$item[$k] = $v;				
		}
		$item['category_pair'] = json_encode($row);
		$out[] = $item;
	}
	//var_dump($out);
	$this->db->close();
	exit(json_encode($out));

}

private function get_category_name($id,$seller_id)
{
	$query_str = "SELECT id,name FROM ".$this->db->dbprefix('item_category')." WHERE seller_id=".$seller_id .' AND id =' .$id;
	$query = $this->db->query($query_str);
	$row = $query->row();
	$name='';
	//var_dump($row)

	 return json_encode($row);
}

private function get_category_id($json)
{
	$category = json_decode($json);
	return $category->id;
}

private function to_item_model($update_items)
{
		
	$update_rows;
	foreach($update_items as $item)
	{
		foreach($item as $k=>$v)
		{
			if($k == 'category_pair')
			{$temp_item['category_id'] = $this->get_category_id($item['category_pair']);}	
			else
			{$temp_item[$k] = $v;}
		}
			$update_rows[] =$temp_item;

			// else
			// {
			// 	$item['gmt_create'] = date('Y-m-d H:i:s');
			// 	$item['gmt_modify'] = date('Y-m-d H:i:s');
			// 	$item['seller_id'] = $seller_id;
			// 	$insert_rows[] = $item;
			// }
	}
		return $update_rows;
}

	

}

?>