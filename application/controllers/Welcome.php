<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * author:dmh describe:�̼һ�ӭ��ҳ
	 * 
	 */
	public function index()
	{
		$this->load->library('parser');		
		$this->load->helper('language');
		$this->load->library('session');
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		$this->load->library('waimai_seller');
		$seller_id = $this->waimai_seller->check_login();		
		$data = array(
		    'static_base_url' => $this->config->item('static_base_url'),
		    'seller_name' => $this->session->seller_name
		    //'logo_url' => $this->cache->get('logo_url'.$seller_id )
		);
		//$this->cache->save('logo_url'.$seller_id, $upload_dir.$row->logo_url, 2592000);
		$this->load->database();
		$query = $this->db->query("SELECT count(id) as total_order_new FROM ".$this->db->dbprefix('order')." WHERE seller_id='$seller_id' and status='NEW' LIMIT 1");
		$row = $query->row();
		$data['total_order_new'] = $row->total_order_new;
		$query = $this->db->query("SELECT count(id) as total_item_today FROM ".$this->db->dbprefix('item')." WHERE seller_id='$seller_id' and TO_DAYS(gmt_create)=TO_DAYS(NOW()) LIMIT 1");
		$row = $query->row();
		$data['total_item_today'] = $row->total_item_today;
		$query = $this->db->query("SELECT count(id) as total_order_today FROM ".$this->db->dbprefix('order')." WHERE seller_id='$seller_id' and TO_DAYS(gmt_create)=TO_DAYS(NOW()) LIMIT 1");
		$row = $query->row();
		$data['total_order_today'] = $row->total_order_today;
		
		//Joe
		$query = $this->db->query("SELECT logo_url FROM ".$this->db->dbprefix('seller')." WHERE id='$seller_id'");
		$row = $query->row();
		if($row)
		{
			if(empty($data['logo_url']))$data['logo_url'] = 'uploads/'.$row->logo_url;
			//$this->cache->save('logo_url'.$seller_id, $data['logo_url'], 2592000);
		}
		//End
		$this->db->close();
		$this->parser->parse('welcome_template', $data);		
	}
}
