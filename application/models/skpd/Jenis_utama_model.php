<?php

class Jenis_utama_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function all_data($tahun)
    {
        $result = $this->db->get_where("m_jenis_utama", array(
            "is_deleted" => false,
            "tahun" => $tahun,
        ));

        return $result->result_array();
    }

    function save($data)
    {
        $result = $this->db->insert("m_jenis_utama", $data);
        return $result;
    }

    function get_data_byid($id)
    {
        $result = $this->db->get_where("m_jenis_utama", array(
            "id" => $id,
        ));

        return $result->row_array();
    }

    function update($data, $id)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("m_jenis_utama", $data);
        return $result;
    }

    function delete($id)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("m_jenis_utama", array(
            "is_deleted" => true,
        ));

        return $result;
    }

    function get_data_bytahun($tahun)
    {
        $result = $this->db->get_where("m_jenis_utama", array(
            "tahun" => $tahun,
            "is_deleted" => false,
        ));

        return $result->result_array();
    }
}
