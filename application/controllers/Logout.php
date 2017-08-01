<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

	/**
	 * author:dmh describe:注销商家登录
	 * 
	 */
	public function index()
	{
		//注销登录
		$this->load->helper('url');
		$this->load->library('session');
		$this->session->sess_destroy();
		redirect('/login');
	}
}
