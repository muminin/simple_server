<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	protected $data_api;
	protected $user;
	protected $group;

	public function __construct()
	{
		parent::__construct();
		// if (!$this->input->is_ajax_request()) {
		//    exit('No direct script access allowed');
		// }
		$this->load->library(array('ion_auth'));
		if (!$this->ion_auth->logged_in())
		{
			return;
		}
		$this->user = $this->ion_auth->user()->row();
		$this->group =$this->ion_auth->get_users_groups($this->user->user_id)->row();

	}

	public function __destruct()
	{   
	    echo json_encode($this->data_api);
	}

	public function index()
	{
			
	}

	public function get_user()
	{
		$user = array('id' 		=> $this->user->id,
					  'email'	=> $this->user->email,
					  'username'	=> $this->user->username,
					  'last_login'	=> date('d-M-Y', $this->user->last_login),
					  'first_name'	=> $this->user->first_name,
					  'last_name'	=> $this->user->last_name,
					  'company'		=> $this->user->company,
					  'phone'		=> $this->user->phone);
		$this->data_api = $user;
	}

	public function get_opd()
	{
		$user =$this->ion_auth->get_users_groups($this->session->userdata('user_id'))->row();
		
		$this->data_api = $user;
	}

	public function get_group()
	{
		$this->data_api = $this->group;
	}

	public function changepass()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
	}

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */