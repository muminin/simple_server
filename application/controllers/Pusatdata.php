
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pusatdata extends CI_Controller
{
	protected $data_api;
	protected $user;
	protected $group;
	protected $method;

	public function __construct()
	{
		parent::__construct();
		// if (!$this->input->is_ajax_request()) {
		//    exit('No direct script access allowed');
		// }
		$this->load->library(array('ion_auth'));
		$this->load->helper(array('loguser'));
		if (!$this->ion_auth->logged_in()) {
			return;
		}
		$this->user = $this->ion_auth->user()->row();
		$this->group = $this->ion_auth->get_users_groups($this->user->user_id)->row();
		$this->ion_auth->set_error_delimiters(' ', ' ');
		$this->ion_auth->set_message_delimiters(' ', ' ');
	}

	public function __destruct()
	{
		//add the header here
		header('Content-Type: application/json');
		echo json_encode($this->data_api);
	}


	public function index()
	{
		$this->data_api = $this->user;
	}

	protected function key_to($variable, $by)
	{
		// /$this->group
		foreach ($this->ion_auth->groups()->result() as $key => $value) {
			$data[$value->$by] = $value;
		}
		return $data;
	}

	public function active()
	{
		$this->method =  strtolower($this->input->server('REQUEST_METHOD'));
		if ($this->method != 'post') {
			exit();
		}
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$lock = (bool) $request->lock;
		$lock = !$lock;
		$lock = (int) $lock;
		$id = intval($request->id_el);
		$this->db->where('id', $id);
		$this->db->update('element', array('lock' => $lock));
		$this->data_api = array('status' => 'success');
	}

	public function activeall()
	{
		$this->method =  strtolower($this->input->server('REQUEST_METHOD'));
		if ($this->method != 'post') {
			exit();
		}
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$lock = (bool) $request->lock;
		$lock = !$lock;
		$lock = (int) $lock;
		$kat = intval($request->kat->id);
		$this->db->where('id_kat', $kat);
		$this->db->update('element', array('lock' => $lock));
		$this->data_api = array('status' => 'success');
	}


	//
	// Begin User Management
	//

	public function getlog()
	{
		$thisMonth = date('M');
		$year = date('Y');
		//$this->db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';");
		$query = $this->db->get('history', 30);
		$logs = $query->result();
		$datalog = array();

		//ambil categori
		$this->db->where('id_parent!=', 0);
		$qcategories = $this->db->get('kategori');
		$categories = $qcategories->result();
		foreach ($categories as $value) {
			$categorie[$value->id] = $value->nama;
		}

		//ambil groupname
		$this->db->select('groups.`name`,users.id');
		$this->db->join('users', 'users_groups.user_id =  users.id');
		$this->db->join('groups', 'users_groups.group_id = groups.id');
		$qgroup = $this->db->get('users_groups');
		foreach ($qgroup->result_array() as $rgroup) {
			$groups[$rgroup['id']] = $rgroup['name'];
		}

		// buat jaga2
		foreach ($logs as $key => $log) {
			$datalog[$key]['update'] = $valid_date = date('d/m/y', strtotime($log->tanggal));
			//var_dump($log->id_user);
			$datalog[$key]['skpd'] = $groups[$log->id_user];
			$explog = explode(',', $log->log);
			foreach ($explog as $value) {

				$dataexplode = explode(':',  $value);
				//var_dump($dataexplode);
				switch ($dataexplode[0]) {
					case 'nilai':
						$datalog[$key]['nilai'] = $dataexplode[1];
						break;
					case 'nama':
						$datalog[$key]['nama'] = $dataexplode[1];
						break;
						// case 'update':
						// 	$datalog[$key]['update'] = $dataexplode[1];
						// 	break;
					case 'id_kat':
						$datalog[$key]['kat'] = $categorie[$dataexplode[1]];
						break;
					case 'tahun':
						$datalog[$key]['tahun'] = $dataexplode[1];
						break;
				}
			}
		}
		//var_dump($datalog);
		$this->data_api = $datalog;


		// //$explog = explode(',', $log);



	}

	public function user()
	{
		$user = array(
			'id' 		=> $this->user->id,
			'email'	=> $this->user->email,
			'username'	=> $this->user->username,
			'last_login'	=> date('d-M-Y', $this->user->last_login),
			'first_name'	=> $this->user->first_name,
			'last_name'	=> $this->user->last_name,
			'company'		=> $this->user->company,
			'phone'		=> $this->user->phone,
			'picture' 	=> $this->user->picture,
			'group'		=> $this->group->name,
			'group_id'	=> $this->group->id,
			'Nip'			=> $this->user->NIP
		);
		$this->data_api = $user;
	}

	public function users()
	{
		$users = $this->ion_auth->users()->result();
		$groups =  $this->ion_auth->groups()->result();
		foreach ($users as $user) {
			if ($user->id != 1 || $user->id != $this->user->user_id) {
				// /var_dump($user->id);
				$list_user[] = array(
					'id' 		=> $user->id,
					'email'		=> $user->email,
					'username'	=> ($user->Api == 1) ? $user->username . '(api)' : $user->username,
					'last_login'	=> date('d-M-Y', $user->last_login),
					'first_name'	=> $user->first_name,
					'last_name'	=> $user->last_name,
					'company'		=> $user->company,
					'phone'		=> $user->phone,
					'img'			=> $user->picture,
					'group'		=> $this->ion_auth->get_users_groups($user->id)->row(),
					'group_name'	=> $this->ion_auth->get_users_groups($user->id)->row()->name,
					'api'			=> $user->Api
				);
			}
		}
		$this->data_api = $list_user;
	}

	public function api_users()
	{
		$users = $this->ion_auth->users()->result();
		$groups =  $this->ion_auth->groups()->result();
		$query = $this->db->get('keys');
		foreach ($query->result() as $api) {
			$apis[$api->user_id] =  $api;
		}

		foreach ($users as $user) {

			if ($user->id != 1 && $user->Api == 1 && array_key_exists($user->id, $apis)) {
				//var_dump($apis[$user->id]);
				//var_dump($this->ion_auth->get_users_groups($user->id)->row());
				$list_user[] = array(
					'id' 		=> $user->id,
					'email'		=> $user->email,
					'username'	=> $user->username,
					'last_login'	=> date('d-M-Y', $user->last_login),
					'first_name'	=> $user->first_name,
					'last_name'	=> $user->last_name,
					'company'		=> $user->company,
					'phone'		=> $user->phone,
					'img'			=> $user->picture,
					'group'		=> $this->ion_auth->get_users_groups($user->id)->row(),
					'group_name'	=> $this->ion_auth->get_users_groups($user->id)->row()->name,
					'key'			=> $apis[$user->id]->key,
					'ip'			=> $apis[$user->id]->ip_addresses
				);
			}
		}
		$this->data_api = $list_user;
	}

	public function groups()
	{
		$this->data_api = $this->key_to($this->ion_auth->groups()->result(), 'id');
	}

	public function group()
	{
		$this->data_api = $this->group;
	}

	public function update_user()
	{


		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		if ($request->change == true && $this->ion_auth->identity_check($request->username) === TRUE) {
			if ($this->ion_auth->email_check($email)) {
				$this->data_api = array(
					"status"	=> "taken",
					"message"	=> "Email Sudah Terpakai"
				);
				return;
			}
			$this->data_api = array(
				"status"	=> "taken",
				"message"	=> "Username Sudah Terpakai."
			);
			return;
		}
		$data = array(
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'username'	=> $request->username,
			'email'		=> isset($request->email) ? $request->email : '',
			'company'	=> isset($request->company) ? $request->company : '',
			'phone'		=> isset($request->phone) ? $request->phone : ''
		);
		$this->ion_auth->update($request->id, $data);
		if ($this->ion_auth->update($request->id, $data)) {
			$this->ion_auth->remove_from_group(NULL, $request->id);
			$this->ion_auth->add_to_group($request->group->id, $request->id);
			loguser('update', $request);
			$status = array('status'		=> 'success');
		} else {
			$status = array(
				'status'		=> 'error',
				'message'	=> 'Terdapat Kesalahan Pada Pengisian Form. Harap Cek Kembali'
			);
		}
		$this->data_api = $status;
	}




	public function add_user()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$username = $request->username;
		$password = $request->password;

		if (!isset($request->email)) {
			$email = $username . '@' . $username . '.com';
		} else {
			$email = $request->email;
		};
		$additional_data = array(
			'first_name' => $request->first_name,
			'last_name' => isset($request->last_name) ? $request->last_name : '',
			'company'	=> isset($request->company) ? $request->company : '',
			'phone'		=> isset($request->phone) ? $request->phone : '',
			'picture'	=> 'assets/pages/media/users/user.png',
			// 'Api'			=>intval($request->is_key)
		);

		//var_dump($additional_data);
		$group = array($request->group->id);
		//$mail = isset($request->email) ? $request->email : '';
		if ($this->ion_auth->identity_check($username) === FALSE && !$this->ion_auth->email_check($email)) {
			$id = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
			$user = $this->ion_auth->user($id)->row();
			// if (intval($request->is_key) == 1 ) {
			// 	$api = $this->add_api($id,$request);
			// }

			$data = array(
				'id' 		=> $user->id,
				'email'		=> $user->email,
				'username'	=> $user->username,
				'last_login'	=> date('d-M-Y', $user->last_login),
				'first_name'	=> $user->first_name,
				'last_name'	=> $user->last_name,
				'company'		=> $user->company,
				'phone'		=> $user->phone,
				'img'			=> $user->picture,
				'group'		=> $request->group,
				//   'Api'			=>intval($request->is_key)
			);

			loguser('tambah', $user);
			$data = array_merge($data, $additional_data, $api, array('group_name' => $request->group->name, 'ip' => (isset($request->ip) ? $data->ip : null)));
			$status = array('status'		=> 'success', 'data'	=> $data);
		} else {
			$status = array('status'		=> 'error');
		}
		$this->data_api = $status;
	}

	public function add_api($user_id, $data)
	{
		$date = new DateTime();
		$insert = array(
			'key'			=> $data->key,
			'level'			=> 1,
			'ignore_limits'	=> 1,
			'date_created' 	=> $date->getTimestamp(),
			'user_id'		=> $user_id,
			'ip_addresses'	=> (isset($data->ip) ? $data->ip : null)
		);
		$this->db->insert('keys', $insert);
		loguser('tambah', $insert);
		return $insert;
	}


	public function setdata($sheet = 0)
	{

		$this->load->library('Excel');
		$this->load->model('model_data');
		// masukkan angka romawi
		for ($i = 1; $i < 40; $i++) {
			$romawi = $this->romanic_number($i);
			$angka_titik[$i . '.'] = $i;
			$angka_kurung[$i . ').'] = $i;
			$angka_romawi[$romawi . '.'] = $romawi;
		}
		//var_dump($angka_romawi);
		$skpd = $this->skpd();
		$file = 'C:\\xampp\\htdocs\\2017\\pusat-data\\datin.xlsx';
		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		$objPHPExcel->setActiveSheetIndex($sheet);
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		foreach ($cell_collection as $cell) {
			$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
			$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
			$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
			$format = $objPHPExcel->getActiveSheet()->getStyle($cell)->getFill()->getStartColor()->getARGB();;
			$datas[$row][$cell]['value'] = $data_value;
			$datas[$row][$cell]['format'] = $format;
		}
		foreach ($datas as $row => $data) {
			//var_dump($data);
			// jika barislebih dari 3
			echo ($row . '<br>');
			if ($row > 2) {
				foreach ($data as $k_cell => $v_cell) {
					if ($v_cell['value'] == ' ' || $v_cell['value'] == '' || $v_cell['value'] == '  ') {
						$v_cell['value'] = NULL;
					}
					//var_dump($v_cell['value']);
					switch (substr($k_cell, 0, 1)) {
						case 'A':


							//var_dump($v_cell);
							if ($v_cell['value'] != NULL) {
								echo "string";
								$this->db->select('nama,id');
								$this->db->where('nama', $v_cell['value']);
								$q_jenis = $this->db->get('kategori');
								$jenis = $q_jenis->row();
								if ($jenis == null) {
									$new_kat = array('id_parent'	=> 0, 'nama'	=> $v_cell['value']);
									$this->db->insert('kategori', $new_kat);
									$kat_parent = $this->db->insert_id();
								} else {
									$kat_parent = $jenis->id;
								}
								//var_dump($kat_parent);
								//unset($jenis);
							}

							break;

						case 'B':
							if ($v_cell['value'] != NULL) {
								$this->db->select('nama,id');
								$this->db->where('nama', $v_cell['value']);
								$q_jenis = $this->db->get('kategori');
								$jenis = $q_jenis->row();

								if ($jenis == null) {

									$new_kat = array('id_parent'	=> $kat_parent, 'nama'	=> $v_cell['value']);
									$this->db->insert('kategori', $new_kat);
									$urusan = $this->db->insert_id();
								} else {
									//var_dump($urusan);
									$urusan = $jenis->id;
								}
								//unset($jenis);
							}
							//var_dump($urusan);
							break;
						case 'E':
							if ($v_cell['value'] != NULL) {
								$this->db->select('nama,id');
								$this->db->where('id', $urusan);
								$dump_q = $this->db->get('kategori');
								$hasildump = $dump_q->row();
								echo ('urusan =>' . $hasildump->nama . '  id -> ' . $hasildump->id . '<br>');
								//echo urlencode($v_cell['value']).'<br>';
								//var_dump(strpos(urlencode($v_cell['value']),'++++++++++'));
								if (strpos(urlencode($v_cell['value']), '+++++') === false) { //jika dia adalah sub = 0
									$this->db->select('id,id_parent,id_kat,nama');
									$this->db->where('id_kat', $urusan);
									$this->db->where('nama', $v_cell['value']);
									$q_element = $this->db->get('element');
									$element = $q_element->row();
									//echo $row;
									if ($element == null) {
										//var_dump($v_cell['value']);
										$exp_kata = explode(' ', $v_cell['value']);
										if (array_key_exists($exp_kata[0], $angka_romawi)) {
											unset($exp_kata[0]);
										}
										//var_dump($exp_kata);
										$nama = '';
										foreach ($exp_kata as $kata) {
											$nama .= $kata . ' ';
										}
										$data_elm = array(
											'id_parent'	=> 0,
											'id_kat'	=> $urusan,
											'nama'		=> $nama,
											'satuan'	=> $data['F' . $row]['value'],
											'peraturan' => $data['G' . $row]['value'],
											'id_group'	=> $skpd[$data['H' . $row]['value']]
										);
										$this->db->insert('element', $data_elm);
										$id_element1 = $this->db->insert_id();
									} else {
										$id_element1 = $element->id;
									}
								} elseif (strpos(urlencode($v_cell['value']), '++++++++++') === false) { //jika dia adalah sub = 0
									$v_cell['value'] = substr($v_cell['value'], 5);
									$this->db->select('id,id_parent,id_kat,nama');
									$this->db->where('id_kat', $urusan);
									$this->db->where('nama', $v_cell['value']);
									$q_element = $this->db->get('element');
									$element = $q_element->row();
									//echo $row;
									if ($element == null) {
										//var_dump($v_cell['value']);
										$exp_kata = explode(' ', $v_cell['value']);
										if (array_key_exists($exp_kata[0], $angka_titik)) {
											unset($exp_kata[0]);
										}
										//var_dump($exp_kata);
										$nama = '';
										foreach ($exp_kata as $kata) {
											$nama .= $kata . ' ';
										}
										$data_elm = array(
											'id_parent'	=> $id_element1,
											'id_kat'	=> $urusan,
											'nama'		=> $nama,
											'satuan'	=> $data['F' . $row]['value'],
											'peraturan' => $data['G' . $row]['value'],
											'id_group'	=> $skpd[$data['H' . $row]['value']]
										);
										$this->db->insert('element', $data_elm);
										$id_element2 = $this->db->insert_id();
									} else {
										$id_element2 = $element->id;
									}
								} elseif (strpos(urlencode($v_cell['value']), '++++++++++++++++++++') === false) {

									$v_cell['value'] = substr($v_cell['value'], 10);
									//var_dump(urlencode($v_cell['value']));
									$this->db->select('id,id_parent,id_kat,nama');
									$this->db->where('id_kat', $urusan);
									$this->db->where('nama', $v_cell['value']);
									$q_element = $this->db->get('element');
									$element = $q_element->row();
									//echo $row;
									if ($element == null) {
										//var_dump($v_cell['value']);
										$exp_kata = explode(' ', $v_cell['value']);
										if (array_key_exists($exp_kata[0], $angka_kurung)) {
											unset($exp_kata[0]);
										}
										//var_dump($exp_kata);
										$nama = '';
										foreach ($exp_kata as $kata) {
											$nama .= $kata . ' ';
										}
										$data_elm = array(
											'id_parent'	=> $id_element2,
											'id_kat'	=> $urusan,
											'nama'		=> $nama,
											'satuan'	=> $data['F' . $row]['value'],
											'peraturan' => $data['G' . $row]['value'],
											'id_group'	=> $skpd[$data['H' . $row]['value']]
										);
										$this->db->insert('element', $data_elm);
										$id_element3 = $this->db->insert_id();
									} else {
										$id_element3 = $element->id;
									}
								} else {
									$v_cell['value'] = substr($v_cell['value'], 20);
									//var_dump(urlencode($v_cell['value']));
									$this->db->select('id,id_parent,id_kat,nama');
									$this->db->where('id_kat', $urusan);
									$this->db->where('nama', $v_cell['value']);
									$q_element = $this->db->get('element');
									$element = $q_element->row();
									//echo $row;
									if ($element == null) {
										//var_dump($v_cell['value']);
										$exp_kata = explode(' ', $v_cell['value']);
										if (array_key_exists($exp_kata[0], $angka_titik)) {
											unset($exp_kata[0]);
										}
										//var_dump($exp_kata);
										$nama = '';
										foreach ($exp_kata as $kata) {
											$nama .= $kata . ' ';
										}
										$data_elm = array(
											'id_parent'	=> $id_element3,
											'id_kat'	=> $urusan,
											'nama'		=> $nama,
											'satuan'	=> $data['F' . $row]['value'],
											'peraturan' => $data['G' . $row]['value'],
											'id_group'	=> $skpd[$data['H' . $row]['value']]
										);
										$this->db->insert('element', $data_elm);
										$id_element4 = $this->db->insert_id();
									} else {
										$id_element4 = $element->id;
									}
								}
							}
							break;
					}
					//var_dump(substr($k_cell,0,1));
				}
			}
			// end jikabari lebih dari 3

		}
		//var_dump($data);
	}


	function romanic_number($integer, $upcase = true)
	{
		$table = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
		$return = '';
		while ($integer > 0) {
			foreach ($table as $rom => $arb) {
				if ($integer >= $arb) {
					$integer -= $arb;
					$return .= $rom;
					break;
				}
			}
		}

		return $return;
	}


	public function api_skpd()
	{
		$this->db->select('keys.ip_addresses,users_groups.group_id,	groups.name');
		$this->db->where('users.Api', 1);
		$this->db->where('users.active', 1);
		$this->db->join('keys', 'keys.user_id = users.id');
		$this->db->join('users_groups', 'users_groups.user_id = users.id');
		$this->db->join('groups', 'users_groups.group_id = groups.id');
		$this->db->group_by('group_id');
		$query = $this->db->get('users');
		//var_dump($query->result());
		$this->data_api = $query->result();
	}

	public function skpd()
	{
		$skpd = array(
			'tata'	=>  4011109,
			'esdm'	=> 4011110,
			'pertanian' => 20301,
			'kehutanan'	=> 4011111,
			'lh'		=> 20501,
			'pu'		=> 10301,
			'perumahan'	=> 10401,
			'kelautan'	=> 4011112,
			'perhub'	=> 20901,
			'kominfo'	=> 21001,
			'kesehatan'	=> 10201,
			'sosial'	=> 10601,
			'budaya'	=> 21301,
			'KUKM'		=> 21101,
			'Modal'		=> 21201,
			'perdagamgam'	=> 30601,
			'industri'		=> 30601,
			'pemerintahan'	=> 4010302,
			'pmdes'			=> 20201,
			'pariwisata'	=> 21301,
			'pendidikan'	=> 10101,
			'ketenagakerjaan'	=> 21101,
			'trans'			=> 4011113,
			'pemperem'		=> 20201,
			'pengen pendu KB'	=> 20201,
			'Olahraga'		=> 21301,
			'perpus'		=> 21701,
			'kearsipan'		=>	21701



		);

		return $skpd;
	}


	public function del_user()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		if ($request->id == 1 || $request->id == $this->user->user_id) {
			$this->data_api = array('status' => 'error', 'message' => 'User admin tidak bisa di hapus');
			return;
		}
		$this->ion_auth->delete_user($request->id);
		loguser('hapus', $request);
		$this->data_api = array('status' => 'success');
	}

	public function add_group()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$this->ion_auth->create_group($request->name, $request->description);
		if ($this->db->affected_rows() == 1) {
			$id_group = $this->db->insert_id();
			$status = array(
				'status'		=> 'success',
				'data'		=> array(
					'id'	=> $id_group,
					'name'	=> $request->name,
					'description'	=> $request->description
				)
			);
			$menu = array(
				array('id_group' => $id_group, 'id_menu' => 1),
				array('id_group' => $id_group, 'id_menu' => 2),
				array('id_group' => $id_group, 'id_menu' => 3),
				array('id_group' => $id_group, 'id_menu' => 6),
				array('id_group' => $id_group, 'id_menu' => 8)
			);
			$this->db->insert_batch('group_menu', $menu);
		} else {
			$status = array('status'		=> 'error');
		}
		loguser('tambah', array_merge($status['data'], array('tambah' => 'group')));
		$this->data_api = $status;
	}

	public function up_group()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		//var_dump($request);
		if (!$this->ion_auth->update_group($request->id, $request->name, $request->description)) {
			$status = array('status'		=> 'success');
		} else {
			$status = array('status'		=> 'error');
		}
		loguser('update', $request);
		$this->data_api = $status;
	}

	public function del_group()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$cek_sql = 'SELECT EXISTS(SELECT * FROM users_groups WHERE group_id =  ' . $request->id . ' LIMIT 1) as jumlah';
		$cek_query = $this->db->query($cek_sql);

		//var_dump($this->ion_auth->in_group($request->id));
		if ($cek_query->row()->jumlah != 0) {
			$this->data_api = array('status' => 'error', 'message' => 'hapus user terlebih dahulu');
			return;
		}
		if ($group_delete = $this->ion_auth->delete_group($request->id)) {
			$this->db->where('id_group', $request->id);
			$this->db->update('element', array('id_group' => 1));
			loguser('hapus', $request);
			$status = array('status'		=> 'success');
		} else {
			$status = array('status'		=> 'error', 'message' => 'uknown error');
		}
		$this->data_api = $status;
	}


	//
	// end User Management
	//


	//
	// Begin menu item
	//

	public function menu()
	{
		$this->load->model('model_menu');
		$menu = $this->model_menu->get_parent($this->group->id);
		foreach ($menu as $key => $parent) {
			if ($this->model_menu->get_child($this->group->id, $parent->id)) {
				$menu_user[$key] = $parent;
				$menu_user[$key]->child = $this->child($this->group->id, $parent->id);
				//array_merge ($menu_user[$key]['child'],$parent);
			} else {
				$menu_user[$key] = $parent;
			}
		}
		$this->data_api = $menu_user;
	}
	protected function child($user, $parent)
	{
		$menu = $this->model_menu->get_child($user, $parent);
		$menu_child = new stdClass();
		foreach ($menu as $key => $child) {
			if ($this->model_menu->get_child($user, $child->id)) {
				$menu_child->$key = $this->child($user->id, $child->id);
			} else {
				$menu_child->$key = $child;
			}
		}
		return $menu_child;
	}

	//
	// end menu item
	//

	public $pilih = array();

	//
	// Data
	//

	public function inject_data($id_kat = null, $tahun = null)
	{
		$this->id_kat = $id_kat;
		if ($tahun === null) {
			$tahun = date("Y");
		}

		if ($id_kat == null) {
			//exit();
		}

		$this->load->model('model_data');
		$this->id_group = 1;
		if ($this->ion_auth->logged_in()) {
			$user = $this->ion_auth->user()->row()->id;
			$this->id_group = $this->ion_auth->get_users_groups($user)->row()->id;
		}

		$elements = $this->model_data->inject_data($id_kat, $tahun);
		//var_dump($elements);
		//exit();
		foreach ($elements as $key => $element) {
			$element->no = ((string) $key + 1) . ').';
			$element->sub = 1;
			$element->tahun = $tahun;
			$element->ketersediaan = (int) $element->ketersediaan;
			$element->publish = (int) $element->publish;
			$sub = $this->model_data->inject_data($id_kat, $tahun, $element->id);
			if ($sub) {

				$element->parent = 1;
				$element->child = 1;
				$element->nilai = null;

				$this->data_api[] = $element;
				$this->sub_inject($sub, $tahun);
				if (array_key_exists($element->id, $this->pilih)) {
					$this->pilih[$element->id_parent] = 1;
				} else {
					array_pop($this->data_api);
				}
			} else {
				//var_dump($element->id_group);
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
					$this->data_api[] = $element;
				}
			}
		}
		//var_dump($this->data_api);
	}

	public function sub_inject($parent, $tahun, $sub = 1)
	{

		foreach ($parent as $key => $child) {
			$child->sub = $sub + 1;
			$child->no = $this->number($key, $sub);
			$child->tahun = $tahun;
			$child->ketersediaan = (int) $child->ketersediaan;
			$child->publish = (int) $child->publish;
			$sub_data = $this->model_data->inject_data($this->id_kat, $tahun, $child->id);
			//var_dump($child);
			if ($sub_data) {
				$child->child = $sub + 1;
				$child->nilai = null;
				$this->data_api[] = $child;
				//$sub_elements->parent =  $sub+1;
				$this->sub_inject($sub_data, $tahun, $sub + 1);
				if (array_key_exists($child->id, $this->pilih)) {
					$this->pilih[$child->id_parent] = 1;
				} else {
					array_pop($this->data_api);
				}
			} else {
				if ($child->id_group == 0) {
					$this->load->library('validation');
					$child->nilai = null;
					$url = $this->validation->url_exists($child->inject_data);

					if ($url != false) {
						$postdata = file_get_contents($url);
						$child->nilai = json_decode($postdata);
					}
					$this->data_api[] = $child;
					$this->pilih[$child->id_parent] = 1;
				}
			}
		}
	}

	public function simpan_inject()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$data = array('inject_data' => $request->inject_data, 'tahun' => $request->tahun, 'id_element' => $request->id);
		$this->db->replace('data_thn', $data);
		$update = array('satuan' => $request->satuan);
		$this->db->where('id', $request->id);
		$this->db->update('element', $update);
	}

	public function element($id_kat = null, $tahun = null)
	{
		if ($tahun === null) {
			$tahun = date("Y");
		}

		if ($id_kat == null) {
			exit();
		}
		$this->load->model('model_data');
		$elements = $this->model_data->element_parent($id_kat, $tahun);
		//var_dump($elements);
		foreach ($elements as $key => $element) {
			$element->no = ((string) $key + 1) . ').';
			$element->sub = 1;
			$element->tahun = $tahun;
			$element->ketersediaan = 0;
			if ($this->model_data->inject_data($element->id)) {
				$element->parent = 1;
				$element->child = 1;
				$element->nilai = array('nilai' => null);
				$this->data_api[] = $element;
				$this->sub_elements($element->id, $tahun);
			} else {
				$element->nilai = $this->model_data->nilai_element($element->id, $tahun);
				$this->data_api[] = $element;
			}
		}
	}


	protected function sub_elements($parent, $tahun, $sub = 1)
	{
		$menu = $this->model_data->element($parent);
		foreach ($menu as $key => $child) {
			$child->ketersediaan = (int) $element->ketersediaan;
			$child->sub = $sub + 1;
			$child->tahun = $tahun;
			$child->no = $this->number($key, $sub);
			if ($this->model_data->element($child->id)) {
				$child->child = $sub + 1;
				$child->nilai = array('nilai' => null);
				$this->data_api[] = $child;
				//$sub_elements->parent =  $sub+1;
				$this->sub_elements($child->id, $tahun, $sub + 1);
			} else {
				$child->nilai = $this->model_data->nilai_element($child->id, $tahun);
				$this->data_api[] = $child;
			}
		}
	}

	public function number($number, $sub)
	{
		switch ($sub) {
			case '2':
				$nomor = range('a', 'zz');
				$preffix = $nomor[$number] . '.';
				break;
			case '1':
				$nomor = range('1', '50');
				$preffix = $nomor[$number] . '.';
				break;
			default:
				$preffix = '          ';
				break;
		}
		return $preffix;
	}


	public function simpanelement()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		if (empty($request)) {
			$this->data_api = null;
			return;
		}
		$this->load->model('model_data');
		$this->model_data->id_user = $this->user->id;
		$status = array('status' => $this->model_data->upnilai_element($request));
		$this->data_api = $status;
	}

	public function tmb_el()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$this->load->model('model_data');
		$insert = $this->model_data->tmb_el($request);
		if ($insert['status'] == 'error') {
			$this->data_api = array('status' => 'error', 'error'	=> $insert['error']);
		} else {
			$this->data_api = array(
				'status' => ($this->db->affected_rows() != 1) ? 'error' : 'success',
				'id' => $this->db->insert_id(),
				'id_parent' => $request->parent,
				'nama' => $request->nama
			);
		}
	}

	public function up_el()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$this->load->model('model_data');
		$this->model_data->up_el($request);
		loguser('update', $request);
		$this->data_api = array('status' => 'success');
	}

	public function up_el_api()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		//var_dump($request);
		foreach ($request as $value) {
			$simpan = array('manual' => $value->manual, 'bridging' => $value->bridging);
			$this->db->where('id', $value->id);
			$this->db->update('element', $simpan);
		}
		loguser('update', $simpan);
		$this->data_api = array('status' => 'success');
	}



	public function del_el()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		loguser('hapus', array('id_element' => $request));
		$this->db->where('id', $request);
		$this->db->delete('element');
		$this->data_api = array('status' => ($this->db->affected_rows() != 1) ? 'error' : 'success');
	}

	public function get_skpd()
	{
		$this->load->model('model_data');
		$this->data_api = $this->model_data->get_group();
	}

	public function new_kat()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		//cek id
		if (property_exists($request, 'id') && $request->id != '') {
			$cek_sql = 'SELECT EXISTS(SELECT * FROM kategori WHERE id =  ' . $request->id . ' LIMIT 1) as jumlah';
			$cek_query = $this->db->query($cek_sql);
			$cek_result = $cek_query->row();

			if ($cek_result->jumlah != 0) {
				$this->data_api = array('status' => 'error', 'error' => 'Id data sudah ada');
				return;
			}
		}

		loguser('tambah', $request);
		//end cek id
		$this->db->insert('kategori', $request);
		$request->id = $this->db->insert_id();
		$this->data_api = array(
			'status' => ($this->db->affected_rows() != 1) ? 'error' : 'success',
			'id' => $request->id,
			'id_parent' => $request->id_parent,
			'nama' => $request->nama
		);
	}

	public function up_el2()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$cek_sql = 'SELECT EXISTS(SELECT * FROM element WHERE id =  ' . $request->id . ' LIMIT 1) as jumlah';
		$cek_query = $this->db->query($cek_sql);
		$cek_result = $cek_query->row();

		if ($cek_result->jumlah != 0 && $request->id != $request->awal) {
			$this->data_api = array('status' => 'error', 'error' => 'Id data sudah ada');
			return;
		}
		$this->load->model('model_data');
		$this->data_api = $this->model_data->up_el2($request);

		//var_dump($request);
		//exit();
	}

	public function up_kat()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		//cek id
		$cek_sql = 'SELECT EXISTS(SELECT * FROM kategori WHERE id =  ' . $request->id . ' LIMIT 1) as jumlah';
		$cek_query = $this->db->query($cek_sql);
		$cek_result = $cek_query->row();

		if ($cek_result->jumlah != 0 && $request->id != $request->awal) {
			$this->data_api = array('status' => 'error', 'error' => 'Id data sudah ada');
			return;
		}
		//end cek id
		$query = array('nama' => $request->nama, 'id' => $request->id);
		$this->db->where('id', $request->awal);
		$this->db->update('kategori', $query);

		$this->data_api = array('status' => ($this->db->affected_rows() != 1) ? 'error' : 'success');
		if ($this->data_api['status'] = 'success') {
			$updateid = array('id_parent' => $request->id);
			$this->db->where('id_parent', $request->awal);
			$this->db->update('kategori', $updateid);

			$updateid = array('id_kat' => $request->id);
			$this->db->where('id_kat', $request->awal);
			$this->db->update('element', $updateid);
		}
		loguser('update', $request);
	}

	public function hps_kat()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$this->db->where('id', $request);
		$this->db->delete('kategori');
		$this->data_api = array('status' => ($this->db->affected_rows() != 1) ? 'error' : 'success');
		loguser('hapus', array('hapus kategori' => $request));
	}

	public function get_all_element()
	{

		$query = $this->db->get('element');
		foreach ($query->result() as $element) {
			$elements[$element->level][$element->id_parent][$element->id] =  $element;
		}
		return $elements;
	}

	public function jmlhelem($tahun = null)
	{
		if ($tahun === null) {
			$tahun = date("Y");
		}

		$this->groups = $this->key_to($this->ion_auth->groups()->result(), 'id');

		//var_dump($this->groups);
		//exit();
		$elements = $this->get_all_element();
		$this->elements = $elements;
		//count(var)
		foreach ($elements[1][0] as $element) {
			//var_dump($element->id);
			//var_dump(array_key_exists($element->id, $elements[2]));
			if (array_key_exists($element->id, $elements[2])) {
				//var_dump($element->id);
				$this->sub_jmlh(1, $element->id);
			} else {


				if (isset($this->groups[$element->id_group])) {
					if (!property_exists($this->groups[$element->id_group], 'jmlh')) {
						$this->groups[$element->id_group]->jmlh = null;
						$this->groups[$element->id_group]->tak_tersedia = null;
					}
					$this->groups[$element->id_group]->jmlh = $this->groups[$element->id_group]->jmlh + 1;
					if ($element->ketersediaan == 1) {
						$this->groups[$element->id_group]->element[] = $element->id;
					} else {
						$this->groups[$element->id_group]->tak_tersedia = $this->groups[$element->id_group]->tak_tersedia + 1;
					}
				}
			}
			//$sub = $elements[2][$element->id];
			//var_dump($element);
		}
		//var_dump($this->elements[5]);

		$this->load->model('model_data');

		foreach ($this->groups as $k_group => $group) {
			if (property_exists($group, 'element')) {
				$terisi = $this->model_data->elem_terisi($group->element, $tahun);
				$this->groups[$k_group]->terisi = $terisi + $group->tak_tersedia;
				$update = $this->model_data->jmlh_updte($group->element, date('Y'), date('m'));
				$this->groups[$k_group]->updated = $update;
			}
		}
		//var_dump($this->db->queries);
		//var_dump($this->groups);
		$this->data_api = $this->groups;
	}

	public function sub_jmlh($level, $id_parent)
	{
		$level++;

		$elements = $this->elements;

		//var_dump($level);
		// //var_dump($elements[$level][$id_parent] );
		// if () {
		// 	return;
		// }

		foreach ($elements[$level][$id_parent] as $element) {
			if (array_key_exists($level + 1, $elements) && array_key_exists($element->id, $elements[$level + 1])) {
				$this->sub_jmlh($level, $element->id);
			} else {
				//var_dump($element->id);
				//var_dump($element);
				//var_dump(isset($this->groups[$element->id_group]));
				if (isset($this->groups[$element->id_group])) {
					if (!property_exists($this->groups[$element->id_group], 'jmlh')) {
						$this->groups[$element->id_group]->jmlh = null;
						$this->groups[$element->id_group]->tak_tersedia = null;
					}
					$this->groups[$element->id_group]->jmlh = $this->groups[$element->id_group]->jmlh + 1;
					if ($element->ketersediaan == 1) {
						$this->groups[$element->id_group]->element[] = $element->id;
					} else {
						$this->groups[$element->id_group]->tak_tersedia = $this->groups[$element->id_group]->tak_tersedia + 1;
					}
				}
			}
		}
		//exit();

	}

	public function jmlhelem_lama($tahun = null)
	{
		if ($tahun === null) {
			$tahun = date("Y");
		}

		$this->groups = $this->key_to($this->ion_auth->groups()->result(), 'id');

		//var_dump($this->groups);

		$this->load->model('model_data');
		$elements = $this->model_data->get_elem();
		//var_dump($elements);

		foreach ($elements as $key => $element) {

			$sub = $this->model_data->get_elem($element->id);
			if ($sub) {
				$this->sub_jmlhelem($sub, $tahun);
			} else {
				if (!property_exists($this->groups[$element->id_group], 'jmlh')) {
					$this->groups[$element->id_group]->jmlh = null;
					$this->groups[$element->id_group]->tak_tersedia = null;
				}
				$this->groups[$element->id_group]->jmlh = $this->groups[$element->id_group]->jmlh + 1;
				if ($element->ketersediaan == 1) {
					$this->groups[$element->id_group]->element[] = $element->id;
				} else {
					$this->groups[$element->id_group]->tak_tersedia = $this->groups[$element->id_group]->tak_tersedia + 1;
				}
			}
		}

		foreach ($this->groups as $k_group => $group) {
			if (property_exists($group, 'element')) {
				$terisi = $this->model_data->elem_terisi($group->element, $tahun);
				$this->groups[$k_group]->terisi = $terisi + $group->tak_tersedia;
				$update = $this->model_data->jmlh_updte($group->element, date('Y'), date('m'));
				$this->groups[$k_group]->updated = $update;
			}
		}
		$this->data_api = $this->groups;
	}

	protected function sub_jmlhelem_lama($parent, $tahun)
	{
		//var_dump($parent);
		foreach ($parent as $key => $child) {
			$sub = $this->model_data->get_elem($child->id);
			if ($sub) {
				$this->sub_jmlhelem($sub, $tahun);
			} else {
				if (!property_exists($this->groups[$child->id_group], 'jmlh')) {
					$this->groups[$child->id_group]->jmlh = null;
					$this->groups[$child->id_group]->tak_tersedia = null;
				}
				$this->groups[$child->id_group]->jmlh = $this->groups[$child->id_group]->jmlh + 1;
				if ($child->ketersediaan == 1) {
					$this->groups[$child->id_group]->element[] = $child->id;
				} else {
					$this->groups[$child->id_group]->tak_tersedia = $this->groups[$child->id_group]->tak_tersedia + 1;
				}
			}
		}
	}

	public function grfketersediaan($tahun)
	{
		$this->jmlhelem($tahun);
		// unset admin
		unset($this->data_api[1]);
		// rubah data ke bentuk data json yg di butuhkan
		//var_dump($this->data_api);
		foreach ($this->data_api as $key => $value) {
			if (property_exists($value, 'terisi')) {

				$data[] = array('skpd' => $value->name, 'persen' => (($value->terisi - $value->tak_tersedia) / ($value->jmlh - $value->tak_tersedia)) * 100, 'color' => '#' .	dechex(rand(0x000000, 0xFFFFFF)));
			}
		}

		$this->data_api = $data;
	}

	public function changeuser()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$id = $request->id;
		$data = array(
			'first_name' 	=> $request->first_name,
			'last_name' 	=> $request->last_name,
			'company' 		=> $request->company,
			'phone'			=> $request->phone,
			'NIP'			=> $request->Nip
		);
		$this->ion_auth->update($id, $data);
		loguser('update', $data);
		$this->data_api = array('status'	=> 'success');
	}

	public function changepass()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$identity = $this->session->userdata('identity');
		if ($request->new != $request->verif) {
			$this->data_api = array('status' => 'error', 'message' => 'Password baru yang anda masukan tidak sama');
			return;
		}
		$change = $this->ion_auth->change_password($identity, $request->old, $request->new);
		//var_dump($change);
		$this->ion_auth->set_error_delimiters(' ', ' ');
		$this->ion_auth->set_message_delimiters(' ', ' ');

		if ($change) {
			//if the password was successfully changed

			$this->data_api = array('status'	=> 'success');
			$this->data_api['message'] = $this->ion_auth->messages();
			loguser('update', array('password' => $identity));
		} else {
			//if unsuseccfull
			$this->data_api = array('status'	=> 'error');
			//var_dump($this->ion_auth->errors());
			$this->data_api['message'] = $this->ion_auth->errors();
		}
	}

	public function update_opd()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$opd = $this->ion_auth->get_users_groups($this->session->userdata('user_id'))->row();
		$update = array(
			'description'	=> $request->description,
			'alamat' 		=> $request->alamat,
			'kepala_opd'	=> $request->kepala_opd,
			'nip'			=> $request->nip,
			'kode_pos'		=> $request->kode_pos,
			'telepon'		=> $request->telepon,
			'fax'			=> $request->fax,
			'web'			=> $request->web,
			'email'			=> $request->email
		);

		$this->db->where('groups.id', $opd->id);
		$this->db->update('groups', $update);
		$this->data_api = array('status'	=> 'success');
	}

	public function resetpass()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		if (empty($request)) {
			$request = $postdata;
		}

		// echo $request;
		// var_dump($request);
		// exit;

		$this->ion_auth_model->forgotten_password($request);
		$identity_column = $this->config->item('identity', 'ion_auth');
		$identity = $this->ion_auth->where($identity_column, $request)->users()->row();

		$user = $this->ion_auth->forgotten_password_check($identity->forgotten_password_code);

		if ($user) {
			$identity = $user->{$this->config->item('identity', 'ion_auth')};

			$change = $this->ion_auth->reset_password($identity, $request);

			if ($change) {
				//berhasil
				$message = $this->ion_auth->messages();
				$this->data_api = array('status'	=> 'success', 'message' => $message);
			} else {
				//gagal
				$message = $this->ion_auth->errors();
				$this->data_api = array('status'	=> 'error', 'message' => $message);
			}
		} else {
			//jika gagal
			$this->data_api = array('status'	=> 'error', 'message' => $message);
		}
		//var_dump($message );
	}

	public function cekprofil()
	{
		//var_dump($this->group);
		$tahun = date("Y");
		$sql_kat = 'SELECT DISTINCT
					DISTINCT (kategori.nama) AS nama,kategori.id
				FROM
					element
				INNER JOIN kategori ON element.id_kat = kategori.id
				WHERE
					id_group = ' . $this->group->id;
		$q_kat = $this->db->query($sql_kat);
		$r_kat = $q_kat->result();
		foreach ($r_kat as $k_kat => $v_kat) {
			$sql_content = 'SELECT
								COUNT(id) as jmlh
							FROM
								element
							WHERE
								id_kat = ' . $v_kat->id . '
							AND id_group = ' . $this->group->id . '
							AND id NOT IN (
								SELECT
									id_parent
								FROM
									element
								WHERE
									id_kat = ' . $v_kat->id . '
								AND id_group = ' . $this->group->id . '
							)';

			$q_content =  $this->db->query($sql_content);
			$activity[$v_kat->nama]['jumlah'] = $q_content->row()->jmlh;

			$sql_terisi = 	'SELECT
								COUNT(id) as jmlh
							FROM
								element
							WHERE
								id_kat = ' . $v_kat->id . '
							AND id_group = ' . $this->group->id . '
							AND id NOT IN (SELECT id_parent FROM element WHERE id_kat = ' . $v_kat->id . ' AND id_group = ' . $v_kat->id . ')
							AND id IN (SELECT data_thn.id_element FROM	data_thn WHERE tahun = ' . $tahun . ' AND nilai IS NOT NULL
								AND id_element NOT IN (SELECT element.id_parent FROM element))';
			//var_dump($sql_terisi);
			$q_terisi =  $this->db->query($sql_terisi);
			$activity[$v_kat->nama]['terisi'] = $q_terisi->row()->jmlh;
		}

		$this->data_api = $activity;
	}

	public function kategori($id_parent = 0, $landing_page = false)
	{
		$this->load->model('model_data');
		//$this->data_api = $this->model_data->kategori($id_parent);
		$skpd = $this->input->get_request_header('skpd', true);

		//var_dump(!$this->ion_auth->is_admin());
		//var_dump($landing_page);
		//var_dump($this->ion_auth->is_admin());
		if ($this->ion_auth->is_admin() && $landing_page == false && !$skpd) {
			//echo "string";
			// $this->element();
			// $kat = array();
			// $parent_kat = array();
			// $element = $this->data_api;
			// //var_dump($element);
			// foreach ($element as $key => $value) {
			// 	//var_dump($value);
			// 	if (!array_key_exists($value->id_kat,$kat)) {
			// 		$kat[$value->id_kat]=$value->id_kat;
			// 		$this->get_parentkat($value->id_kat);
			// 		if (!array_key_exists($this->data_api->id_parent,$parent_kat)) {
			// 			$parent_kat[$this->data_api->id_parent]=$this->data_api->id_parent;
			// 		}
			// 	}
			// }
			//if ($id_parent==0) {

			// $this->db->where_in('id', $parent_kat);
			// $query = $this->db->get('kategori');
			// $this->data_api = $query->result();
			//}else{
			// $this->db->where_in('id', $kat);
			// $query = $this->db->get('kategori');
			// $this->data_api = $query->result();
			//}
			$this->data_api = $this->model_data->kategori($id_parent);
		} else if ($landing_page == true) {
			$this->data_api = $this->model_data->kategori($id_parent);
		} else {
			//var_dump(!$skpd);
			if ($id_parent == 0) {
				$this->data_api = $this->model_data->kategori($id_parent);
			} else if ($skpd) {
				//var_dump('expression');
				$this->data_api = $this->model_data->kategori($id_parent, $skpd);
			} else {
				//var_dump($this->group);
				$this->data_api = $this->model_data->kategori($id_parent, $this->group->id);
			}
		}
		//var_dump($this->db);
	}


	public function import()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$this->load->library('Excel');
		$this->load->model('model_data');
		// masukkan angka romawi
		$file = $request->location;
		//var_dump($request->location);
		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		$objPHPExcel->setActiveSheetIndex(0);
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		$datas = array();
		foreach ($cell_collection as $cell) {
			$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
			$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
			$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
			$datas[$row][$column] = $data_value;

			// $datas[$row][$cell]['format']=$format;
		}

		foreach ($datas as $key => $value) {
			if (!isset($value['A'])) {
				unset($datas[$key]);
			}
		}

		$this->data_api = $datas;
	}

	public function simpanImport()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$idimport;
		$this->load->library('Excel');
		$file = $request->location;
		$ketersediaan = array('Ada' => 1, 'tidak' => 0);

		$objPHPExcel = PHPExcel_IOFactory::load($file);
		$objPHPExcel->setActiveSheetIndex(0);
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		$datas = array();
		foreach ($cell_collection as $cell) {
			$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
			$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
			$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
			$datas[$row][$column] = $data_value;
			switch ($cell) {
				case 'C4':
					// idjenis
					break;
				case 'C5':
					$tahun = $data_value;
					break;

				default:
					if ($row > 7) {
						switch ($column) {
							case 'A':
								$idimport = $data_value;
								$insert[$idimport]['id_element'] = $data_value;
								$insert[$idimport]['tahun'] = $tahun;
								break;
							case 'B':
								$hapus = $data_value;
								break;
							case 'E':
								$insert[$idimport]['nilai'] = $data_value;
								break;
							case 'G':
								$insert[$idimport]['ketersediaan'] = $ketersediaan[$data_value];
								unset($insert[$hapus]);
								break;
						}
					}
					break;
			}
			// $datas[$row][$cell]['format']=$format;
		}

		//cek database sudah bener apa tidak
		//indikator jumlah yg tersedia berdasarkan sumber data.
		//jika hasil dari jumalh data di excel berbeda dengan jumlah hasil query. berarti data di excel sudah berubah dan di kembalikan segai error
		$this->db->select('id');
		$this->db->where_in('id', array_keys($insert));
		if ($this->group->id != 1) {
			$this->db->where('id_group', $this->group->id);
		}
		$cek = $this->db->get('element');
		$jumlah = $cek->num_rows();

		if (count($insert) != $jumlah) {
			$this->data_api = array('status'	=> 'error', 'error' => 'Mohon di cek Kembali file Excel yang anda upload. Error : jumlah element file excel tidak sama dengan jumlah element di Database');
			return;
		}

		foreach ($insert as $db_insert) {

			//update data nilai
			$replace =  $db_insert;
			unset($replace['ketersediaan']);
			$this->db->replace('data_thn', $replace);


			// update data ketersediaan
			$this->db->where('id', $db_insert['id_element']);
			$this->db->update('element', array('ketersediaan' => $db_insert['ketersediaan']));
		}
		$this->data_api = array('status'	=> 'success');
	}

	function do_upload()
	{
		//var_dump($this->action);
		//var_dump($this->input);
		//$this->cek_user->cek_upload_master($this->action->action);
		$tahun = date('Y');
		$bulan = date('m');
		if (!is_dir('uploads/data/' . $tahun . '/' . $bulan)) {
			mkdir('./uploads/data/' . $tahun . '/' . $bulan, 0777, TRUE);
		}

		$upload_path_url = 'uploads/data/' . $tahun . '/' . $bulan . '/';
		$config['upload_path'] = './uploads/data/' . $tahun . '/' . $bulan;
		$config['allowed_types'] = 'xlsx|xls';
		$config['max_size'] = '3000';
		//$this->input->is_ajax_request();
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('file')) {
			//$error = array('error' => $this->upload->display_errors());
			$this->data_api = array('status' => 'gagal', 'error' => $this->upload->display_errors('', ''));

			//$this->load->view('upload_form', $error);
		} else {

			$data = $this->upload->data();
			//var_dump($data);
			$info = new StdClass;
			$info->name = $data['file_name'];
			$info->file_name = $data['file_name'];
			$info->location = $upload_path_url . $data['file_name'];
			$info->file_type = $data['file_type'];
			$info->status = 'success';
			$this->data_api = $info;
		}
	}

	public function salinData()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		foreach ($request->data as $salinData) {
			$replace = array(
				'id_element'	=> $salinData->id,
				'tahun'			=> $request->tahun,
				'nilai'			=> $salinData->nilai,
				'publish'		=> $salinData->publish
			);
			$this->db->replace('data_thn', $replace);
		}
		$this->data_api = array('status'	=> 'success');
	}
}

/* End of file pusatdata.php */
/* Location: ./application/controllers/pusatdata.php */
