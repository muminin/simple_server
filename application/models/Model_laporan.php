<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_laporan extends CI_Model {

	public function kec($id_element)
	{
		$this->db->select('');
	}

	public function jdl_lap($id_judul)
	{
		$this->db->select('judul_tabel');
		$this->db->where_in('id_element', $id_judul);
		return $this->db->get('jdl_tbl_lap');
	}

	public function element($id_kat,$tahun,$id_parent=0)
	{
		
		$this->db->select('element.id,
							element.id_parent,
							element.nama,
							element.id_group,
							element.ketersediaan,
							element.`level`,
							element.satuan,
							(SELECT nilai from data_thn where data_thn.id_element = element.id and tahun='.$tahun.') as "'.$tahun.'",
							(SELECT nilai from data_thn where data_thn.id_element = element.id and tahun='.($tahun-1).') as "'.($tahun-1).'",
							(SELECT nilai from data_thn where data_thn.id_element = element.id and tahun='.($tahun-2).') as "'.($tahun-2).'",
							(SELECT nilai from data_thn where data_thn.id_element = element.id and tahun='.($tahun-3).') as "'.($tahun-3).'",
							(SELECT nilai from data_thn where data_thn.id_element = element.id and tahun='.($tahun-4).') as "'.($tahun-4).'"'		
							,false);
		$this->db->where('element.id_kat', $id_kat);
		$this->db->where('id_parent',$id_parent);
		$this->db->order_by('element.id', 'DESC');
		
		return $this->db->get('element')->result_array();
		
	}

	public function lap_header($id_kat=null,$id_parent=0)
	{
		$this->db->select('
			format_laporan.id_kat,
			format_laporan.id_element,
			format_laporan.id_parent,
			format_laporan.nama,
			format_laporan.format,
			format_laporan.kec,
			format_laporan.baris,
			format_laporan.height,
			format_laporan.sum,
			format_laporan.`order`,
			element.id_group
		');


		$this->db->where('format_laporan.id_parent', $id_parent);
		if ($id_kat) {
			$this->db->where('format_laporan.id_kat', $id_kat);
		}

		$this->db->join('element', 'format_laporan.id_element = element.id', 'left');
		$this->db->order_by('order', 'asc');
		
		//$this->db->order_by('id_element', 'ASC');
		return $this->db->get('format_laporan')->result();
		
	}

}

/* End of file Model_laporan.php */
/* Location: ./application/models/Model_laporan.php */