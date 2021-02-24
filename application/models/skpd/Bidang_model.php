<?php

class Bidang_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function all_data($tahun)
    {
        $this->db->order_by("urusan_id", "asc");
        $this->db->order_by("kd_bidang", "asc");
        $result = $this->db->get_where("v_bidang", array(
            "jenis_tahun" => $tahun,

            "is_deleted" => false,
            "urusan_deleted" => false,
            "jenis_deleted" => false,
        ));
        return $result->result_array();
    }

    function save($data)
    {
        $result = $this->db->insert("m_bidang", $data);
        return $result;
    }

    function get_data_byid($id)
    {
        $result = $this->db->get_where("v_bidang", array(
            "id" => $id,
        ));

        return $result->row_array();
    }

    function update($data, $id)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("m_bidang", $data);
        return $result;
    }

    function delete($id)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("m_bidang", array(
            "is_deleted" => true,
        ));
        return $result;
    }

    function get_data_byurusan($id_urusan)
    {
        $result = $this->db->get_where("v_bidang", array(
            "urusan_id" => $id_urusan,
            "is_deleted" => false,
        ));

        return $result->result_array();
    }

    function save_multiple($data)
    {
        $sql = "INSERT INTO m_bidang (id_urusan, kd_bidang, nm_bidang, created_date, created_by, is_deleted) VALUES ";
        $count = count($data) - 1;
        foreach ($data as $key => $val) {
            if ($key != $count) {
                $sql .= "('" . $val["id_urusan"] . "', '" . $val["kd_bidang"] . "', '";
                $sql .= $val["nm_bidang"] . "', '" . $val["created_date"] . "', '" . $val["created_by"] . "', '" . $val["is_deleted"] . "'), ";
            } else {
                $sql .= "('" . $val["id_urusan"] . "', '" . $val["kd_bidang"] . "', '";
                $sql .= $val["nm_bidang"] . "', '" . $val["created_date"] . "', '" . $val["created_by"] . "', '" . $val["is_deleted"] . "')";
            }
        }

        $this->db->query($sql);
    }
}
