<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class bridging extends REST_Controller {
	public $pilih = array();

	function __construct()
    {
        // Construct the parent class
        parent::__construct();
		$this->load->library('ion_auth');
		$this->user = $this->ion_auth->user($this->authentication->user->id)->row();
		$this->skpd = $this->ion_auth->get_users_groups($this->user->id)->row();
		$this->id_group = $this->skpd->id;
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

  //   	if (!$this->input->is_ajax_request()) {
  //   		show_404();
		//    exit();
		// }

    }


	public function sipd_get()
	{
		if ($this->get('tahun')==null) {
			$tahun = date("Y");
		}else {
			$tahun = $this->get('tahun');
		}
		
		$element = $this->parent_element($tahun);
		if ($element==null) {
			// Set the response and exit
			$this->response([
				'status' => FALSE,
				'message' => 'No users were found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
		$this->set_response($element, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
	}

	public function sipd_put()
	{
	

		if ($this->put('update')!=NULL) {
			$update_element=$this->put('batch');
		}else if($this->put('id_element') && $this->put('nilai')) {
			$tahun = ($this->put('tahun')) ? $this->put('tahun') :date("Y");
			$update_element[]=array('id_element'	=> $this->put('id_element'),
								  'nilai'		=> $this->put('nilai')
									);
		}else{
			$this->response([
                    'status' => FALSE,
                    'message' => 'No Data for Imput'
            ], REST_Controller::HTTP_NOT_FOUND); 
		}


		
		if (count($update_element)<1) {
			$this->response([
                    'status' => FALSE,
                    'message' => $update_element
                ], REST_Controller::HTTP_NOT_FOUND);
		}
		foreach ($update_element as $update_data) {
			$id[]= $update_data['id_element'];
			$tahun = (array_key_exists('tahun',$update_data)) ?   $update_data['tahun'] :date("Y");
			$update_nilai[] =array(
							  'id_element'	=> intval($update_data['id_element']),
							  'tahun'		=>  $tahun,
							  'nilai'		=> floatval($update_data['nilai'])
								);
		}
		// cek parent
		$this->db->select('id');
		$this->db->where_in('id_parent',$id);
		$this->db->group_by('id_parent');
		
		$get_id_parent = $this->db->get('element');
		$id_parent = $get_id_parent->result();
		if (count($id_parent)>0) {
			$response ='';
			foreach ($id_parent as $id_parent_for) {
				$response.= $id_parent_for->id_parent.','; 
			}
			//var_dump($this->db->queries);
			$this->response([
                    'status' => FALSE,
                    'message' => 'Terdapat id yang tidak bisa di isi karena masih mempunyai sub.(id:'.$response.')'
                ], REST_Controller::HTTP_NOT_FOUND);
		}

		//cek autentication group
		$this->db->distinct('id_group');
		$this->db->where_in('id',$id);
		$get_id_group = $this->db->get('element');
		foreach ($get_id_group->result() as  $id_group) {
			if ($id_group->id_group != $this->id_group) {
				$this->response([
                    'status' => FALSE,
                    'message' => 'Data yang anda masukkan bukan merupakan data anda.'
                ], REST_Controller::HTTP_NOT_FOUND);
			}
		}
		foreach ($update_nilai as $update) {
			$this->db->replace('data_thn', $update);
		}
		$this->set_response($update_nilai, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

	}

    protected function parent_element($tahun)
    {
     
     $this->db->select('nama,id');
     $this->db->where('id_parent !=', 0);
     $q_kat= $this->db->get('kategori');
     foreach ($q_kat->result() as $category) {
         $categories[$category->id]=$category->nama;
     }
      $this->db->select('DISTINCT id_kat',false);
      $this->db->where('id_group',  $this->id_group);

      $query = $this->db->get('element');
      $elemen = array();
      foreach ($query->result() as $kat) {
          $element[ $categories[$kat->id_kat]] = $this->element($kat->id_kat,$tahun);
      }
      return $element;
      // $this->output->set_content_type('application/json');
      // $this->output->set_header('HTTP/1.0 200 OK');
      // $this->output->set_header('HTTP/1.1 200 OK');
      // //$this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', $last_update).' GMT');
      // $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
      // $this->output->set_header('Cache-Control: post-check=0, pre-check=0');
      // $this->output->set_header('Pragma: no-cache');
      // $this->output->set_output(json_encode($element, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
      // $this->output->_display();
    
    }

    protected function element($id_kat=null,$tahun=null,$landing_page = false)
	{
        unset ($this->data_api);
		$this->id_kat = $id_kat;
		// if ($tahun===null) {
		// 	$tahun = date("Y");
		// }

		if ($id_kat == null) {
			exit();
		}

		$this->load->model('model_data');
		
		$elements = $this->model_data->element($id_kat,$tahun,0,$landing_page);
		foreach ($elements as $key => $element) {
			//$element->no = ((string) $key+1).').';
			$element->level = 1;
			$element->tahun = $tahun;
			$element->ketersediaan = (int)$element->ketersediaan;
			$element->publish = (int)$element->publish;
			$sub = $this->model_data->element($id_kat,$tahun,$element->id,$landing_page);
			if ($sub) {
				$element->parent = 1;
				$element->parent = true;
				$element->nilai =null;

				$this->data_api[] = $element;
				$this->sub_elements($sub,$tahun,1,$landing_page);
				if (array_key_exists($element->id,$this->pilih)) {
					$this->pilih[$element->id_parent]= 1;
				}else{
					array_pop($this->data_api);
				}
			}else{
				if ($element->id_group == $this->id_group || $this->id_group==1) {
					if ($element->bridging == 1) {
						$this->data_api[] = $element;
					}
					

				}
				
			}
		}
        return $this->data_api;
	}

    protected function sub_elements($parent,$tahun,$sub=1,$landing_page=false)
	{
		
		foreach ($parent as $key => $child) {
            
			$child->level = $sub+1;
			//$child->no = $this->number($key,$sub);
			$child->tahun = $tahun;
			$child->ketersediaan =(int)$child->ketersediaan;
			$child->publish = (int)$child->publish;
        //    / echo '2';
			$sub_data = $this->model_data->element($this->id_kat,$tahun,$child->id,$landing_page);
			if ($sub_data) {
				$child->parent = true;
				$child->nilai = null;
				$this->data_api [] = $child;
				//$sub_elements->parent =  $sub+1;
				$this->sub_elements($sub_data,$tahun,$sub+1,$landing_page);
				//var_dump(array_key_exists($child->id,$this->pilih));
				if (array_key_exists($child->id,$this->pilih)) {
					$this->pilih[$child->id_parent]= 1;
				}else{
					array_pop($this->data_api);
				}
			}else{
				if ($child->id_group == $this->id_group) {
					if ($child->id_group == 0) {
						$this->load->library('validation');
						$child->nilai = null;
						$url = $this->validation->url_exists($child->inject_data);

						if ($url != false) {
							$postdata = file_get_contents($url);
							$child->nilai = json_decode($postdata);
						}
					}
					if ($child->bridging == 1) {
						$this->data_api [] = $child;
						$this->pilih[$child->id_parent] = 1; 
					}
				}
			}
		}

	}

}

/* End of file api_element.php */
