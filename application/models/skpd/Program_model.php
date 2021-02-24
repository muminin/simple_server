<?php

class Program_model extends MY_Model
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

        $this->db->order_by("bidang_id", "asc");
        $this->db->order_by("kd_program", "asc");
        if ($this->session->userdata("group") == 1) {
            $result = $this->db->get_where("v_program", array(
                "is_deleted" => false,

                "bidang_deleted" => false,
                "urusan_deleted" => false,
                "jenis_deleted" => false,
            ));

            return $result->result_array();
        } else {
            $bidang = $this->session->userdata("bidang");
            if (isset($bidang)) {
                // $count = count($bidang) - 1;
                // foreach ($bidang as $key => $val) {
                //     if ($count == 0) {
                //         $this->db->where("id_bidang", $val["id_bidang"]);
                //     } else {
                //         if ($key == 0) {
                //             $this->db->where("(id_bidang", $val["id_bidang"]);
                //         } elseif ($key == $count) {
                //             $this->db->or_where("id_bidang = " . $val["id_bidang"] . ")", NULL);
                //         } else {
                //             $this->db->or_where("id_bidang", $val["id_bidang"]);
                //         }
                //     }
                // }

                $result = $this->db->get_where("v_program", array(
                    "is_deleted" => false,

                    "bidang_deleted" => false,
                    "urusan_deleted" => false,
                    "jenis_deleted" => false,
                ));

                return $result->result_array();
            }
        }
    }

    function save($data)
    {
        $result = $this->db->insert("m_program", $data);
        return $result;
    }

    function get_data_byid($id)
    {
        $result = $this->db->get_where("v_program", array(
            "id" => $id,
        ));

        return $result->row_array();
    }

    function update($data, $id)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("m_program", $data);
        return $result;
    }

    function delete($id)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("m_program", array(
            "is_deleted" => true,
        ));
        return $result;
    }

    function get_program_bybidang($id_bidang)
    {
        $this->db->order_by("kd_program", "asc");
        $result = $this->db->get_where("v_program", array(
            "bidang_id" => $id_bidang,
            "is_deleted" => false,

            "bidang_deleted" => false,
            "urusan_deleted" => false,
            "jenis_deleted" => false,
        ));

        return $result->result_array();
    }

    function save_multiple($data)
    {
        $sql = "INSERT INTO m_program (id_bidang, kd_program, nm_program, created_date, created_by, is_deleted) VALUES ";
        $count = count($data) - 1;
        foreach ($data as $key => $val) {
            if ($key != $count) {
                $sql .= "('" . $val["id_bidang"] . "', '" . $val["kd_program"] . "', '";
                $sql .= $val["nm_program"] . "', '" . $val["created_date"] . "', '" . $val["created_by"] . "', '" . $val["is_deleted"] . "'), ";
            } else {
                $sql .= "('" . $val["id_bidang"] . "', '" . $val["kd_program"] . "', '";
                $sql .= $val["nm_program"] . "', '" . $val["created_date"] . "', '" . $val["created_by"] . "', '" . $val["is_deleted"] . "')";
            }
        }

        $this->db->query($sql);
    }

    function get_program_bytahun($tahun)
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

        $result = $this->db->get_where("v_program", array(
            "jenis_tahun" => $tahun,
        ));

        return $result->result_array();
    }

    function save_kegiatan_byprogram($data)
    {
        $this->db->trans_begin();
        $count = count($data) - 1;

        $save = "INSERT INTO m_kegiatan (id_program, kode, nama, is_deleted, created_date, created_by) VALUES ";
        foreach ($data as $key => $val) {
            if ($key == $count) {
                $save .= "(" . $val["program"] . ", '" . $val["kode"] . "', '" . $val["nama"] . "', " . $val["is_deleted"] . ", '" . $val["created_date"] . "', " . $val["created_by"] . ")";
            } else {
                $save .= "(" . $val["program"] . ", '" . $val["kode"] . "', '" . $val["nama"] . "', " . $val["is_deleted"] . ", '" . $val["created_date"] . "', " . $val["created_by"] . "), ";
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
}
