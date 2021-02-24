<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_back extends CI_Controller {

	protected $data_api;

	public function __construct()
	{
		parent::__construct();
		// if (!$this->input->is_ajax_request()) {
		//    exit('No direct script access allowed');
		// }
		$this->load->library(array('ion_auth'));

	}

	public function __destruct()
	{
	    echo json_encode($this->data_api);
	}


	public function index()
	{
		
	}

	public function kategori($id_parent=0)
	{
		$this->load->model('model_data');
		$this->data_api = $this->model_data->kategori($id_parent);
		//var_dump($this->data_api);
	}

	public function element($id_kat=null)
	{
		if ($id_kat == null) {
			exit();
		}
		$this->load->model('model_data');
		$elements = $this->model_data->element_parent($id_kat);
		foreach ($elements as $key => $element) {
			//var_dump($element);
			if ($this->model_data->element($element->id)) {
				$data[$key] = $element;
				$data[$key]->child = $this->sub_elements($element->id);
			}else{
				$data[$key] = $element;
			}
		}
		$this->data_api = $data;
	}


	protected function sub_elements($parent)
	{
		$menu = $this->model_data->element($parent);
		$menu_child = new stdClass();
		foreach ($menu as $key => $child) {
			if ($this->model_data->element($child->id)) {
				$menu_child->$key = $child;
				$menu_child->$key->child = $this->sub_elements($child->id);
			}else{
				$menu_child->$key = $child;
			}
		}
		//var_dump($menu_child);
		return $menu_child;
	}

}

/* End of file api.php */
/* Location: ./application/controllers/api.php */