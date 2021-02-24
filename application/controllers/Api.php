<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

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
		$this->output->set_content_type('application/json');
		$this->output->set_header('HTTP/1.0 200 OK');
		$this->output->set_header('HTTP/1.1 200 OK');
		//$this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', $last_update).' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output
			->set_output(json_encode($this->data_api, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
		//echo json_encode($this->data_api);
		//echo json_last_error_msg();
	}


	public function index()
	{ }



	public function kategori($id_parent = 0, $landing_page = false)
	{
		$this->load->model('model_data');
		$skpd = $this->input->get_request_header('skpd', true);
		if (!$skpd) {
			$skpd = false;
		}

		//$this->data_api = $this->model_data->kategori($id_parent);
		if (!$this->ion_auth->is_admin() && $landing_page == false) {

			$this->element();
			$kat = array();
			$parent_kat = array();
			$element = $this->data_api;
			if (count($element) == 0) {
				$this->data_api = array();
				return;
			}

			foreach ($element as $key => $value) {
				//var_dump($value);
				if (!array_key_exists($value->id_kat, $kat)) {
					$kat[$value->id_kat] = $value->id_kat;
					$this->get_parentkat($value->id_kat);
					if (!array_key_exists($this->data_api->id_parent, $parent_kat)) {
						$parent_kat[$this->data_api->id_parent] = $this->data_api->id_parent;
					}
				}
			}
			if ($id_parent == 0) {
				$this->db->where_in('id', $parent_kat);
				$query = $this->db->get('kategori');
				$this->data_api = $query->result();
			} else {
				$this->db->where_in('id', $kat);
				$query = $this->db->get('kategori');
				$this->data_api = $query->result();
			}
		} else if ($landing_page = true) {
			$this->data_api = $this->model_data->kategori($id_parent, $skpd);
		} else {
			//var_dump('expression');
			$this->data_api = $this->model_data->kategori($id_parent);
		}
	}

	public function get_parentkat($id_kat)
	{
		$this->db->select('id,nama,id_parent');
		$this->db->where('id', $id_kat);
		$query = $this->db->get('kategori');
		$this->data_api = $query->row();
		//var_dump($this->data_api);
	}

	// cek dari element


	// end cek dari element

	public $pilih = array();

	public function rekap()
	{
		$this->db->select('id_kat');
		$this->db->group_by('id_kat');
		$query = $this->db->get('element');
		//var_dump($this->db);
		foreach ($query->result() as $kat) {
			$this->element($kat->id_kat);
			//var_dump($kat);
		}

		//var_dump(expression)
	}

	public function element($id_kat = null, $tahun = null, $landing_page = false)
	{
		$this->id_kat = $id_kat;
		if ($tahun === null) {
			$tahun = date("Y");
		}

		if ($id_kat == null) {
			//exit();
		}


		$skpd = $this->input->get_request_header('skpd', true);
		// $skpd = $this->session->userdata("group");
		// var_dump($skpd);
		// exit;


		$this->load->model('model_data');
		$this->id_group = 1;
		if ($this->ion_auth->logged_in() && $landing_page == false) {
			if ($skpd) {
				$this->id_group = $skpd;
			} else {
				$user = $this->ion_auth->user()->row()->id;
				$this->id_group = $this->ion_auth->get_users_groups($user)->row()->id;
			}
		} else if ($landing_page == false) {
			exit();
		}
		//echo "string";
		$elements = $this->model_data->element($id_kat, $tahun, 0, $landing_page);
		//var_dump($elements);
		foreach ($elements as $key => $element) {


			// pakai ini untuk update level
			$update_level = array('level' => 1);
			$this->db->where('id', $element->id);
			$this->db->update('element', $update_level);
			$element->no = ((string) $key + 1) . ').';
			$element->sub = 1;
			$element->tahun = $tahun;
			$element->ketersediaan = (int) $element->ketersediaan;
			$element->manual = (int) $element->manual;
			$element->lock = (bool) $element->lock;
			$element->bridging = (int) $element->bridging;
			$element->publish = (int) $element->publish;
			$sub = $this->model_data->element($id_kat, $tahun, $element->id, $landing_page);
			if ($sub) {
				$element->parent = 1;
				$element->child = 1;
				$element->nilai = null;

				$this->data_api[] = $element;
				$this->sub_elements($sub, $tahun, 1, $landing_page);
				if (array_key_exists($element->id, $this->pilih)) {
					$this->pilih[$element->id_parent] = 1;
				} else {
					array_pop($this->data_api);
				}
			} else {
				if ($element->id_group == $this->id_group || $this->id_group == 1) {
					if ($element->id_group == 0) {
						//lek error berarti gara2 link kosong utowo link api error;
						$this->load->library('validation');
						$element->nilai = null;
						$url = $this->validation->url_exists($child->inject_data);
						//var_dump($url);
						if ($url != false) {
							$postdata = file_get_contents($url);
							$element->nilai = json_decode($postdata);
						}
					}
					$this->data_api[] = $element;
				}
			}
		}
	}


	protected function sub_elements($parent, $tahun, $sub = 1, $landing_page = false)
	{

		foreach ($parent as $key => $child) {
			$child->sub = $sub + 1;

			// pakai ini untuk update level
			$update_level = array('level' => $child->sub);
			$this->db->where('id', $child->id);
			$this->db->update('element', $update_level);
			$child->no = $this->number($key, $sub);
			$child->tahun = $tahun;
			$child->ketersediaan = (int) $child->ketersediaan;
			$child->manual = (int) $child->manual;
			$child->lock = (bool) $child->lock;
			$child->bridging = (int) $child->bridging;
			$child->publish = (int) $child->publish;
			$sub_data = $this->model_data->element($this->id_kat, $tahun, $child->id, $landing_page);
			if ($sub_data) {
				$child->child = $sub + 1;

				//var_dump(expression)
				$child->nilai = null;
				$this->data_api[] = $child;
				//$sub_elements->parent =  $sub+1;
				$this->sub_elements($sub_data, $tahun, $sub + 1, $landing_page);
				if (array_key_exists($child->id, $this->pilih)) {
					$this->pilih[$child->id_parent] = 1;
				} else {
					array_pop($this->data_api);
				}
			} else {
				if ($child->id_group == $this->id_group || $this->id_group == 1) {
					if ($child->id_group == 0) {
						$this->load->library('validation');
						$child->nilai = null;
						$url = $this->validation->url_exists($child->inject_data);

						if ($url != false) {
							$postdata = file_get_contents($url);
							$child->nilai = json_decode($postdata);
						}
					}
					if ($child->ketersediaan == 0 && $landing_page == true) {
						$child->nilai = 0;
					}
					$this->data_api[] = $child;
					$this->pilih[$child->id_parent] = 1;
				}
			}
		}
	}



	public function number($number, $sub)
	{
		// var_dump($sub);
		// exit;

		switch ($sub) {
			case '2':
				$nomor = range('1', '250');
				$preffix = $nomor[$number] . '.';
				break;
			case '1':
				$nomor = range('1', '80');
				//var_dump($nomor);
				$preffix = $nomor[$number] . '.';
				break;
			default:
				$preffix = '          ';
				break;
		}
		return $preffix;
	}

	public $sub = array();


	// data untuk menampilkan grafik
	public function grafik_tahun()
	{
		// /$id,$thn_awl,$thn_khir
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$id 		= $request->pilSub;
		$thn_awl	= $request->awalThn;
		$thn_khir	= $request->akhirThn;
		$sub_nilai 	= $id;
		$this->id_kat = $request->id_kat;

		foreach ($sub_nilai as $key => $sub) {
			$nama[$sub->id] = $sub->nama;
			$sub2 = $this->get_Qsub($sub->id);
			if ($sub2) {
				$this->get_sub($sub2, $sub->id);
			} else {
				$this->sub[$sub->id] = $sub->id;
			}
		}
		$this->load->model('model_data');
		///$judul = $this->model_data->get_jdl_el($id);
		//var_dump($this->sub);
		for ($i = $thn_awl; $i <= $thn_khir; $i++) {
			foreach ($this->sub as $k_nilai => $nilai_tot) {

				$kirim[$i][$nama[$k_nilai]] =  $this->model_data->data_thn($nilai_tot, $i)->jumlah;
			}
			$kirim[$i]['tahun'] = $i;
			$this->data_api[] = $kirim[$i];
		}
	}

	public function get_sub($parent, $parent_id)
	{
		foreach ($parent as $key => $sub) {
			$sub2 = $this->get_Qsub($sub->id);
			if ($sub2) {
				$this->get_sub($sub2, $parent_id);
			} else {
				$this->sub[$parent_id][] = $sub->id;
			}
		}
	}


	private function get_Qsub($id_parent)
	{
		$detail = array(
			'id_kat' => $this->id_kat,
			'id_element'	=> $id_parent
		);
		$this->load->model('model_data');
		$list_child = $this->model_data->list_child($detail);

		return $list_child;
	}

	public function graph_batang($id = null)
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$this->load->model('model_data');
		foreach ($request->pilSub as $sub) {
			$judul = $this->model_data->get_jdl_el($sub->id);
			$balloonText = $judul->nama;
			$this->data_api[]  = array(
				"balloonText" => $balloonText . "Tahun ([[category]]): [[value]]",
				"fillAlphas" => 0.9,
				"lineAlpha" => 0.2,
				"title" => $balloonText,
				"type" => "column",
				"valueField" => $balloonText
			);
		}
	}
	public function graph_garis($id = null)
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$this->load->model('model_data');
		foreach ($request->pilSub as $sub) {
			$judul = $this->model_data->get_jdl_el($sub->id);
			$balloonText = $judul->nama;
			$this->data_api[]  = array(
				"balloonText" => $balloonText . "Tahun ([[category]]): [[value]]",
				"fillAlphas" => 0,
				"title" => $balloonText,
				"bullet" => "round",
				"valueField" => $balloonText
			);
		}
	}

	public function sel_grafik()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$this->load->model('model_data');
		$detail = array(
			'id_kat' => $request->id_kat,
			'id_element'	=> $request->id
		);
		$this->data_api = $this->model_data->list_child($detail);
	}
}

/* End of file api.php */
/* Location: ./application/controllers/api.php */
