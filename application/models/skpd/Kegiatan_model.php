<?php

class Kegiatan_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function all_data($tahun = NULL)
    {
        if ($this->session->userdata("group") == 1) {
        } else {
            $bidang_sess = $this->session->userdata("bidang");

            $count = count($bidang_sess) - 1;
            if (isset($bidang_sess)) {
                $where = "";
                foreach ($bidang_sess as $key => $val) {
                    $bidang_name = $this->get_bidang_name_byid($val["id_bidang"]);

                    if ($count == 0) {
                        $this->db->where("bidang_nama", $bidang_name);
                    } else {
                        if ($key == 0) {
                            $where .= "(bidang_nama = '$bidang_name' OR ";
                        } elseif ($key == $count) {
                            $where .= "bidang_nama = '$bidang_name')";
                        } else {
                            $where .= "bidang_nama = '$bidang_name' OR ";
                        }
                    }
                }
            }

            if ($count > 0) {
                $this->db->where($where, NULL);
            }
        }

        if (!empty($tahun)) {
            $this->db->where("jenis_tahun", $tahun);
        }

        $this->db->order_by("jenis_tahun", "asc");
        $this->db->order_by("jenis_id", "asc");
        $this->db->order_by("urusan_id", "asc");
        $this->db->order_by("bidang_id", "asc");
        $this->db->order_by("program_id", "asc");

        if ($this->session->userdata("group") == 1) {
            $result = $this->db->get_where("v_kegiatan", array(
                "is_deleted" => false,

                "program_deleted" => false,
                "bidang_deleted" => false,
                "urusan_deleted" => false,
                "jenis_deleted" => false,
            ));

            return $result->result_array();
        } else {
            $bidang = $this->session->userdata("bidang");
            if (isset($bidang)) {
                $result = $this->db->get_where("v_kegiatan", array(
                    "is_deleted" => false,

                    "program_deleted" => false,
                    "bidang_deleted" => false,
                    "urusan_deleted" => false,
                    "jenis_deleted" => false,
                ));

                return $result->result_array();
            }
        }
    }

    function get_kegiatan_byid($id)
    {
        $result = $this->db->get_where("v_kegiatan", array(
            "id" => $id,
        ));

        return $result->row_array();
    }

    function save($data)
    {
        $result = $this->db->insert("m_kegiatan", $data);
        return $result;
    }

    function update($data, $id)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("m_kegiatan", $data);
        return $result;
    }

    function delete($id)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("m_kegiatan", array(
            "is_deleted" => true,
        ));
        return $result;
    }

    function get_kegiatan_byprogram($program)
    {
        $result = $this->db->get_where("v_kegiatan", array(
            "program_id" => $program,
            "is_deleted" => false,
        ));

        return $result->result_array();
    }

    function get_data_bykegiatan($kegiatan, $level = NULL)
    {
        if (!empty($level)) {
            $this->db->where("level", $level);
        }

        $result = $this->db->get_where("v_data_kegiatan", array(
            "kegiatan_id" => $kegiatan,

            "is_deleted" => false,
            "kegiatan_deleted" => false,
            "program_deleted" => false,
            "bidang_deleted" => false,
            "urusan_deleted" => false,
            "jenis_deleted" => false,
        ));

        return $result->result_array();
    }

    function save_data_bykegiatan($data)
    {
        $this->db->trans_begin();
        $count = count($data) - 1;

        $save = "INSERT INTO t_data_kegiatan (id_kegiatan, level, id_parent, uniq, uraian, satuan, nilai, is_deleted, created_date, created_by) VALUES ";
        foreach ($data as $key => $val) {
            if ($key == $count) {
                $save .= "(" . $val["id_kegiatan"] . ", " . $val["level"] . ", " . $val["id_parent"] . ", '" . $val["uniq"] . "', '" . $val["uraian"];
                $save .= "', '" . $val["satuan"] . "', '" . $val["nilai"] . "', " . $val["is_deleted"] . ", '" . $val["created_date"] . "', " . $val["created_by"] . ")";
            } else {
                $save .= "(" . $val["id_kegiatan"] . ", " . $val["level"] . ", " . $val["id_parent"] . ", '" . $val["uniq"] . "', '" . $val["uraian"];
                $save .= "', '" . $val["satuan"] . "', '" . $val["nilai"] . "', " . $val["is_deleted"] . ", '" . $val["created_date"] . "', " . $val["created_by"] . "), ";
            }
        }

        $this->db->query($save);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo 0;
        } else {
            $this->db->trans_commit();
            echo 1;
        }
    }

    function get_last_uniq_bykegiatan($kegiatan, $tahun)
    {
        $this->db->order_by("id", "desc");
        $result = $this->db->get_where("t_data_kegiatan", array(
            "id_kegiatan" => $kegiatan,
            // "jenis_tahun" => $tahun,
        ));

        return $result->row_array();
    }

    function upload_kegiatan($tahun, $data)
    {
        $this->db->trans_begin();

        $jenis = $this->db->get_where("m_jenis_utama", array("tahun" => $tahun))->row_array();
        $jenis_id = $jenis["id"];

        $result = array();
        foreach ($data as $val) {
            $program = $this->db->get_where("v_program", array(
                "jenis_tahun" => $tahun,
                "kode_rekening_program" => $val["rekening_program"]
            ))->row_array();

            if (!empty($program)) {
                $check = $this->db->get_where("v_kegiatan", array(
                    "jenis_tahun" => $tahun,
                    "kode_rekening_program" => $val["rekening_program"],
                    "kode" => $val["kegiatan"],
                ))->row_array();

                if (empty($check)) {
                    $this->db->insert("m_kegiatan", array(
                        "id_program" => $program["id"],
                        "kode" => $val["kegiatan"],
                        "nama" => $val["nama_kegiatan"],

                        "is_deleted" => false,
                        "created_date" => date("Y-m-d H:i:s"),
                        "created_by" => $this->user_id,
                    ));
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo 0;
        } else {
            $this->db->trans_commit();
            echo 1;
        }
    }
}
