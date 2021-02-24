<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_view extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->layout = false;
		$this->load->library(array('ion_auth','form_validation'));

	}

	public function index()
	{
		
	}

	public function data()
	{
		
	}

	public function element()
	{
		
	}

	public function editelement()
	{
		if (!$this->ion_auth->is_admin()){
			$this->view = 'template/just_admin';
		}
	}

	public function profile()
	{
		
	}

	public function overview()
	{

	}

	public function accountsetting()
	{
		
	}

	public function usermanagement()
	{
		if (!$this->ion_auth->is_admin()){
			$this->view = 'template/just_admin';
		}
	}

	public function grafik()
	{
		
	}

	public function inject_data()
	{
		
	}

	public function inputdata()
	{
		
	}

	public function bridging_aplikasi()
	{
		
	}

	public function api_element()
	{
		
	}

	public function profile_opd()
	{
		
	}

}

/* End of file admin_view.php */
/* Location: ./application/controllers/admin_view.php */