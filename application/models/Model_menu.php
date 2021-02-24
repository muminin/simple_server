<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_menu extends CI_Model {

	public function get_parent($id_group)
	{
		$this->db->select('menu.menu,menu.id,menu.id_parent,menu.url,menu.icons');
		$this->db->join('menu', 'group_menu.id_menu = menu.id');
		$this->db->where('group_menu.id_group', $id_group);
		$this->db->where('menu.id_parent', 0);
		$query = $this->db->get('group_menu');
		return $query->result();
	}

	public function get_child($id_group,$id_parent)
	{
		$this->db->select('menu.menu,menu.id,menu.id_parent,menu.url,menu.icons');
		$this->db->join('menu', 'group_menu.id_menu = menu.id');
		$this->db->where('group_menu.id_group', $id_group);
		$this->db->where('menu.id_parent', $id_parent);
		$query = $this->db->get('group_menu');
		return $query->result();
	}

}

/* End of file model_menu.php */
/* Location: ./application/models/model_menu.php */