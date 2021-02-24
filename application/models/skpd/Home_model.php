<?php

class Home_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    private function print_out_data($data)
    {
        echo "<pre>";
        var_dump($data);
        exit;
    }

    function get_jenis($data, $is_tahun)
    {
        $this->db->select("id, tahun, nama_jenis_utama");
        if ($is_tahun) {
            $result = $this->db->get_where("m_jenis_utama", array(
                "tahun" => $data,
                "is_deleted" => 0,
            ));
        } else {
            $result = $this->db->get_where("m_jenis_utama", array(
                "id" => $data,
                "is_deleted" => 0,
            ));
        }

        return $result->result_array();
    }

    function get_urusan($data, $is_jenis)
    {
        $this->db->select("id, jenis_id, jenis_tahun, kd_urusan, nm_urusan");
        if ($is_jenis) {
            $result = $this->db->get_where("v_urusan", array(
                "jenis_id" => $data,
                "is_deleted" => 0,
            ));
        } else {
            $result = $this->db->get_where("v_urusan", array(
                "id" => $data,
                "is_deleted" => 0,
            ));
        }

        return $result->result_array();
    }

    function get_bidang($data, $is_urusan)
    {
        $this->db->select("id, urusan_id, jenis_tahun, kd_bidang, nm_bidang");
        if ($is_urusan) {
            $result = $this->db->get_where("v_bidang", array(
                "urusan_id" => $data,
                "is_deleted" => 0,
            ));
        } else {
            $result = $this->db->get_where("v_bidang", array(
                "id" => $data,
                "is_deleted" => 0,
            ));
        }

        return $result->result_array();
    }

    function get_program($data, $is_bidang)
    {
        $this->db->select("id, bidang_id, jenis_tahun, kd_program, nm_program");
        if ($is_bidang) {
            $result = $this->db->get_where("v_program", array(
                "bidang_id" => $data,
                "is_deleted" => 0,
            ));
        } else {
            $result = $this->db->get_where("v_program", array(
                "id" => $data,
                "is_deleted" => 0,
            ));
        }

        return $result->result_array();
    }

    function get_kegiatan($data, $is_program)
    {
        $this->db->select("id, program_id, jenis_tahun, kode, nama");
        if ($is_program) {
            $result = $this->db->get_where("v_kegiatan", array(
                "program_id" => $data,
                "is_deleted" => 0,
            ));
        } else {
            $result = $this->db->get_where("v_kegiatan", array(
                "id" => $data,
                "is_deleted" => 0,
            ));
        }

        return $result->result_array();
    }

    function get_data($data, $is_data)
    {
        $this->db->order_by("id", "asc");
        $this->db->order_by("level", "asc");

        if ($is_data) {
            $result = $this->db->get_where("v_data_kegiatan", array(
                "program_id" => $data,
                "is_deleted" => 0,
            ));
        } else {
            $result = $this->db->get_where("v_data_kegiatan", array(
                "id" => $data,
                "is_deleted" => 0,
            ));
        }

        $result = $result->result_array();

        foreach ($result as $resk => $resv) {
            $history = $this->db->query("SELECT created_date FROM t_data_history WHERE id_data = " . $resv["id"] . " ORDER BY created_date DESC")->row_array();

            // $tanggal_update = $resv["created_date"];
            // if (!empty($history)) {
            //     $tanggal_update = $history["created_date"];
            // }

            $result[$resk]["tanggal_update"] = "";
            if (!empty($history)) {
                // $tanggal_update = $history["created_date"];
                $result[$resk]["tanggal_update"] = date("d-m-Y", strtotime($history["created_date"]));
            }
        }

        // $this->print_out_data($result);
        return $result;
    }

    function get_parent($id)
    {
        return $this->db->get_where("t_data", array(
            "id" => $id,
            "is_deleted" => 0,
        ))->row_array()["id_parent"];
    }
}
