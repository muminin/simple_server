<?php

class Komposit_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_all($tahun)
    {
        $result = $this->db->get_where("v_komposit", array(
            "jenis_tahun" => $tahun,
            "is_deleted" => false,
        ));

        return $result->result_array();
    }

    function get_byid($komposit)
    {
        $result = $this->db->get_where("v_komposit", array(
            "id" => $komposit,
        ));

        return $result->row_array();
    }

    function get_bidang_bytahun($tahun)
    {
        $result = $this->db->get_where("m_bidang", array(
            "tahun" => $tahun,
        ));

        return $result->result_array();
    }

    function get_data_bybidang($bidang)
    {
        $result = $this->db->get_where("v_data_new", array(
            "bidang_id" => $bidang,
        ));

        return $result->result_array();
    }

    function save($data)
    {
        $result = $this->db->insert("t_komposit", $data);

        return $result;
    }

    function update($id, $data)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("t_komposit", $data);

        return $result;
    }

    function delete($komposit)
    {
        $this->db->where("id", $komposit);
        $result = $this->db->update("t_komposit", array(
            "is_deleted" => true,
        ));

        return $result;
    }

    function sum_nilai($data)
    {
        $this->db->select("id, id_parent, nilai");
        $list = $this->db->get_where("t_data_new", array(
            "id_parent" => $data,
        ))->result_array();

        $result = 0;
        if (!empty($list)) {
            foreach ($list as $val) {
                $nilai = $this->sum_nilai($val["id"]);
                if (!empty($nilai)) {
                    $result += $nilai;
                }

                if (!preg_match("/[a-z]/i", $val["nilai"])) {
                    $result += floatval($val["nilai"]);
                }
            }
        }

        return $result;
    }

    function get_data_byuniq($uniq, $tahun)
    {
        $result = $this->db->get_where("t_data_new", array(
            "uniq" => $uniq,
            "tahun" => $tahun,
        ))->row_array();

        if (empty($result["nilai"])) {
            $result["nilai"] = $this->sum_nilai($result["id"]);
        }

        return $result;
    }
}
