<?php

class Urusan_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function all_data($tahun)
    {
        $result = $this->db->get_where("v_urusan", array(
            "jenis_tahun" => $tahun,

            "is_deleted" => false,
            "jenis_deleted" => false,
        ));

        return $result->result_array();
    }

    function save($data)
    {
        $result = $this->db->insert("m_urusan", $data);
        return $result;
    }

    function get_data_byid($id)
    {
        $result = $this->db->get_where("v_urusan", array(
            "id" => $id,
        ));

        return $result->row_array();
    }

    function update($data, $id)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("m_urusan", $data);
        return $result;
    }

    function delete($id)
    {
        $this->db->where("id", $id);
        $result = $this->db->udpate("m_urusan", array(
            "is_deleted" => true,
        ));

        return $result;
    }

    function get_data_byjenis($id_jenis)
    {
        $result = $this->db->get_where("v_urusan", array(
            "jenis_id" => $id_jenis,
            "is_deleted" => false,
        ));

        return $result->result_array();
    }

    function save_multiple($data)
    {
        $sql = "INSERT INTO m_urusan (id_jenis_utama, kd_urusan, nm_urusan, created_date, created_by, is_deleted) VALUES ";
        $count = count($data) - 1;
        foreach ($data as $key => $val) {
            if ($key != $count) {
                $sql .= "('" . $val["id_jenis_utama"] . "', '" . $val["kd_urusan"] . "', '";
                $sql .= $val["nm_urusan"] . "', '" . $val["created_date"] . "', '" . $val["created_by"] . "', '" . $val["is_deleted"] . "'), ";
            } else {
                $sql .= "('" . $val["id_jenis_utama"] . "', '" . $val["kd_urusan"] . "', '";
                $sql .= $val["nm_urusan"] . "', '" . $val["created_date"] . "', '" . $val["created_by"] . "', '" . $val["is_deleted"] . "')";
            }
        }

        $this->db->query($sql);
    }
}
