<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_data extends CI_Model
{

	public $id_user;

	public function kategori($id_parent, $user = false)
	{
		$this->db->where('id_parent', $id_parent);
		if ($user != false) {
			if ($id_parent == 0) {
				$this->db->where_in('id', 'SELECT id_parent FROM kategori WHERE	id IN (SELECT DISTINCT (element.id_kat)	FROM element WHERE	element.id_group = ' . $user . ')', false);
			} else {
				$this->db->where_in('id', 'SELECT DISTINCT (element.id_kat)	FROM element WHERE	element.id_group = ' . $user, false);
			}
		}
		$this->db->order_by('id', 'asc');
		$query = $this->db->get('kategori');
		return $query->result();
	}

	public function get_kategori($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('kategori');
		return $query->row();
	}

	public function get_group()
	{
		$this->db->order_by('name', 'asc');
		$query = $this->db->get('groups');
		$result = $query->result();
		foreach ($result as $value) {
			$return[$value->id] = $value;
		}
		return $return;
	}

	public function cek_elem($id_parent, $tahun)
	{
		$SQL = "SELECT EXISTS (SELECT data_pusat.id FROM data_pusat WHERE	element.id_parent = " . $id_parent . " AND data_pusat.tahun = " . $tahun . ") AS status";
		$query = $this->db->query($SQL);
		return $query->result();
	}

	public function element($id_kat, $tahun, $id_parent = 0, $publish = false)
	{
		if ($id_kat == null) {
			$id_kat = "";
		} else {
			$id_kat = 'AND element.id_kat = ' . $id_kat;
		}

		if ($publish == true) {
			$publish = 'AND publish = 1';
		} else {
			$publish = '';
		}

		$SQL = "SELECT
					element.id,
					element.id_parent,
					element.nama,
					element.satuan,
					element.ketersediaan,
					element.id_group,
					element.id_kat,
					element.publish,
					element.manual,
					element.bridging,
					element.lock,
					(SELECT tahun from data_thn where id_element = element.id and tahun=" . $tahun . ") as tahun,
					(SELECT nilai from data_thn where id_element = element.id and tahun=" . $tahun . ") as nilai,
					(SELECT `update` from data_thn where id_element = element.id and tahun=" . $tahun . ") as `update`
				FROM
					element
				WHERE
				 element.id_parent = " . $id_parent . " "
			. $id_kat . " "
			. $publish . "
				ORDER BY
					element.id ASC";
		//var_dump($SQL);
		$query = $this->db->query($SQL);
		return $query->result();
	}

	public function inject_data($id_kat, $tahun, $id_parent = 0)
	{
		if ($id_kat == null) {
			$id_kat = "";
		} else {
			$id_kat = 'AND element.id_kat = ' . $id_kat;
		}
		$SQL = "SELECT
					element.id,
					element.id_parent,
					element.nama,
					element.satuan,
					element.ketersediaan,
					element.id_group,
					element.id_kat,
					element.publish,

					(SELECT tahun from data_thn where id_element = element.id and tahun=" . $tahun . ") as tahun,
					(SELECT nilai from data_thn where id_element = element.id and tahun=" . $tahun . ") as nilai,
					(SELECT `update` from data_thn where id_element = element.id and tahun=" . $tahun . ") as `update`,
					(SELECT `inject_data` from data_thn where id_element = element.id and tahun=" . $tahun . ") as `inject_data`
				FROM
					element
				WHERE
				 element.id_parent = " . $id_parent . " "
			. $id_kat . "
				ORDER BY
					element.nama ASC";
		//var_dump($SQL);
		$query = $this->db->query($SQL);
		return $query->result();
	}



	public function get_elem($id_parent = 0)
	{
		$this->db->select('id,id_parent,id_group,nama,ketersediaan');
		$this->db->where('id_parent', $id_parent);
		$query = $this->db->get('element');
		return $query->result();
	}

	public function elem_terisi($id_elem, $tahun)
	{
		$this->db->select('count(data_thn.id_element) as jumlah');
		$this->db->where_in('data_thn.id_element', $id_elem);
		$this->db->where('tahun', $tahun);
		$this->db->where('nilai !=', null);
		$query = $this->db->get('data_thn');
		return $query->row()->jumlah;
	}

	public function jmlh_updte($id_elem, $tahun, $bulan)
	{
		$this->db->select('count(data_thn.id_element) as jumlah');
		$this->db->where_in('data_thn.id_element', $id_elem);
		$this->db->where('YEAR (`update`) =', $tahun);
		$this->db->where('MONTH (`update`) =', $bulan);
		$this->db->where('nilai !=', null);
		$query = $this->db->get('data_thn');
		return $query->row()->jumlah;
	}

	public function data_thn($id_element, $tahun)
	{
		$this->db->select('SUM(nilai) as jumlah');
		$this->db->where_in('id_element', $id_element);
		$this->db->where('tahun', $tahun);
		$query = $this->db->get('data_thn');
		$jmlh = $query->row();
		if ($jmlh->jumlah == null) {
			$jmlh->jumlah = 0;
		}
		return $jmlh;
	}

	public function get_jdl_el($id)
	{
		$this->db->select('nama');
		$this->db->where('id', $id);
		$query = $this->db->get('element');
		return $query->row();
	}


	public function list_child($detail)
	{
		$this->db->select('id,nama');
		$this->db->where('id_parent', $detail['id_element']);
		$this->db->where('id_kat', $detail['id_kat']);
		$query = $this->db->get('element');
		return $query->result();
	}

	public function element_parent($id_kat, $tahun)
	{
		$cek = $this->cek_elem(0, $tahun);
		$SQL = "SELECT
					element.id,
					element.id_parent,
					element.nama,
					element.satuan,
					element.ketersediaan,
					element.id_group,
					element.id_kat,
					data_thn.tahun,
					data_thn.nilai,
					data_thn.publish,
					data_thn.update,
					data_thn.id_element
				FROM
					element
				LEFT JOIN data_thn ON data_thn.id_element = element.id
				WHERE
					(data_thn.tahun = " . $tahun . " OR data_thn.tahun IS NULL)
					AND element.id_parent = 0
				ORDER BY
					element.nama ASC";
		echo "$SQL";

		$query = $this->db->query($SQL);
		return $query->result();
	}



	public function get_publish($id)
	{
		$this->db->select('publish');
		$this->db->where('id_element', $id);
		$query = $this->db->get('data_thn');
		$result = $query->row();
		if ($result == null) {
			return null;
		}
		return (int) $result->publish;
	}

	public function nilai_element($id_el, $tahun)
	{
		$this->db->where('id_element', $id_el);
		$this->db->where('tahun', $tahun);
		$query = $this->db->get('data_thn');
		$result =  $query->row();
		if ($result === null) {
			$result = new stdClass();
			$result->nilai = null;
		}
		return $result;
	}

	public function upnilai_element($data)
	{

		foreach ($data as $simpan) {

			$element = array(
				'satuan' => $simpan->satuan,
				'ketersediaan' => $simpan->ketersediaan,
				'publish'	=> $simpan->publish
			);
			$nilai = array(
				'id_element' => $simpan->id,
				'nilai'		=> $simpan->nilai,
				'tahun'		=> $simpan->tahun
			);
			$this->db->where('id', $simpan->id);
			$this->db->update('element', $element);
			//tambah data element
			$this->db->replace('data_thn', $nilai);

			//make log to database
			$log = array('nilai : ' . $simpan->nilai . ', satuan : ' . $simpan->satuan . ', ketersediaan : ' . $simpan->ketersediaan . ', tahun : ' . $simpan->tahun . ', publish : ' . $simpan->publish);
			loguser('update', $simpan);
		}

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function tmb_el($data)
	{

		$query = array(
			'id_parent' => $data->parent,
			'id_kat'	=> $data->kat,
			'nama'	=> $data->nama,
			'satuan'	=> (isset($data->satuan)) ? $data->satuan : Null
		);
		if (property_exists($data, 'id') && $data->id != "") {
			$SQL = "SELECT EXISTS (SELECT 1 FROM element WHERE	id = " . $data->id . ") AS jmlh";
			$cek = $this->db->query($SQL);
			$cekid = $cek->row();
			if ($cekid->jmlh == 1) {
				$status = array('status' => 'error', 'error' => 'ID $data->id sudah terpakai');
				return $status;
			}
			$query['id'] = $data->id;
		}
		if (property_exists($data, 'id_skpd')) {
			$query['id_group'] = $data->id_skpd->id;
		}
		if ($data->parent != 0) {
			$this->db->select('level');
			$this->db->where('id', $data->parent);
			$get_level = $this->db->get('element', 1);
			$query['level'] = ($get_level->row()->level) + 1;
		}
		loguser('tambah', $query);
		$this->db->insert('element', $query);
	}

	public function up_el($data)
	{
		$query = array(
			'nama' => $data->nama,
			'id_group' => $data->id_group,
			'satuan' => $data->satuan,
			'id_group' => $data->id_group
		);
		$this->db->where('id', $data->id);
		$this->db->update('element', $query);
	}

	public function up_el2($data)
	{
		//var_dump($data);
		$query = array(
			'nama' 	=> $data->nama,
			'id_group' 	=> $data->id_group,
			'satuan' 	=> $data->satuan,
			'id_group' 	=> $data->id_group,
			'id'			=> $data->id
		);
		$this->db->where('id', $data->awal);
		$this->db->update('element', $query);
		///($this->db);

		$return = array('status' => ($this->db->affected_rows() != 1) ? 'error' : 'success');
		if ($return['status'] = 'success') {
			$updateid = array('id_parent' => $data->id);
			$this->db->where('id_parent', $data->awal);
			$this->db->update('element', $updateid);
		}
		return $return;
	}

	public function up_kat($id_group)
	{
		$this->db->where('id_group', $$id_group);
		$this->db->select('DISTINCT(id_kat) as jenis');
		$query = $this->db->get('element');
		$jenis = $query->result();
	}
}

/* End of file model_data.php */
/* Location: ./application/models/model_data.php */
