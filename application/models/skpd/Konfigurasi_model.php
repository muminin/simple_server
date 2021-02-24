<?php
ini_set('MAX_EXECUTION_TIME', '-1');

class Konfigurasi_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function print_out_data($data)
    {
        echo "<pre>";
        var_dump($data);
        exit;
    }

    function all_data()
    {
        $result = $this->db->get_where("v_opd_group", array(
            "bidang_deleted" => false,
            "urusan_deleted" => false,
            "jenis_deleted" => false,
        ));

        return $result->result_array();
    }

    function all_opd()
    {
        $result = $this->db->get_where("groups", array(
            "id !=" => 1,
        ));

        return $result->result_array();
    }

    function save_konfigurasi($data)
    {
        $this->db->where("id_group", $data[0]["id_group"]);
        $this->db->delete("t_group_bidang");

        $sql = "INSERT INTO t_group_bidang (id_group, id_bidang, created_date, created_by) VALUES ";
        $count = count($data) - 1;
        foreach ($data as $key => $val) {
            if ($key != $count) {
                $sql .= "('" . $val["id_group"] . "', '" . $val["id_bidang"] . "', '";
                $sql .= $val["created_date"] . "', '" . $val["created_by"] . "'), ";
            } else {
                $sql .= "('" . $val["id_group"] . "', '" . $val["id_bidang"] . "', '";
                $sql .= $val["created_date"] . "', '" . $val["created_by"] . "')";
            }
        }

        return $this->db->query($sql);
    }

    function bidang_bygroup($group)
    {
        $this->db->select("id, id_group, id_bidang");
        return $this->db->get_where("t_group_bidang", array(
            "id_group" => $group,
        ))->result_array();
    }

    function get_user_group($id_user)
    {
        $group = $this->db->get_where("users_groups", array(
            "user_id" => $id_user
        ))->row_array();

        return $group["group_id"];
    }

    function get_user_bidang($id_user)
    {
        $group = $this->db->get_where("users_groups", array(
            "user_id" => $id_user
        ))->row_array();

        $result = $this->db->get_where("t_group_bidang", array(
            "id_group" => $group["group_id"],
        ));

        return $result->result_array();
    }

    private function get_nmurusan($id_urusan)
    {
        $this->db->select("nm_urusan");
        return $this->db->get_where("m_urusan", array(
            "id" => $id_urusan,
        ))->row_array()["nm_urusan"];
    }

    private function get_rek_bidang($id_bidang)
    {
        return $this->db->get_where("v_bidang", array(
            "id" => $id_bidang,
            "is_deleted" => false,
        ))->row_array()["kode_rekening_bidang"];
    }

    private function get_rek_program($id_program)
    {
        return $this->db->get_where("v_program", array(
            "id" => $id_program,
            "is_deleted" => false,
        ))->row_array()["kode_rekening_program"];
    }

    private function get_data($id_data)
    {
        return $this->db->get_where("t_data", array(
            "id" => $id_data,
        ))->row_array();
    }

    function copy_simple_bytahun($jenis_utama, $tahun_copy)
    {
        $this->db->trans_begin();
        $user_id = $this->session->userdata("user_id");

        // JENIS UTAMA
        $jenis_utama = $this->db->get_where("m_jenis_utama", array(
            "id" => $jenis_utama,
            "is_deleted" => false,
        ))->row_array();

        $sql = "INSERT INTO m_jenis_utama (tahun, nama_jenis_utama, is_deleted, created_date, created_by) VALUES ";
        $sql .= "(" . $tahun_copy . ", '" . $jenis_utama["nama_jenis_utama"] . "', " . $jenis_utama["is_deleted"] . ", '";
        $sql .= date("Y-m-d H:i:s") . "', " . $user_id . ")";

        $this->db->query($sql);
        $jenis_utama_to = $this->db->insert_id();
        // JENIS UTAMA

        // URUSAN
        $urusan = $this->db->get_where("m_urusan", array(
            "id_jenis_utama" => $jenis_utama["id"],
            "is_deleted" => false,
        ))->result_array();

        $sql = "INSERT INTO m_urusan (id_jenis_utama, kd_urusan, nm_urusan, is_deleted, created_date, created_by) VALUES ";
        $count = count($urusan) - 1;
        foreach ($urusan as $key => $val) {
            if ($key != $count) {
                $sql .= "(" . $jenis_utama_to . ", " . $val["kd_urusan"] . ", '";
                $sql .= $val["nm_urusan"] . "', " . $val["is_deleted"] . ", '" . date("Y-m-d H:i:s") . "', " . $user_id . "), ";
            } else {
                $sql .= "(" . $jenis_utama_to . ", " . $val["kd_urusan"] . ", '";
                $sql .= $val["nm_urusan"] . "', " . $val["is_deleted"] . ", '" . date("Y-m-d H:i:s") . "', " . $user_id . ")";
            }
        }

        $this->db->query($sql);
        $urusan_to = $this->db->get_where("m_urusan", array(
            "id_jenis_utama" => $jenis_utama_to,
            "is_deleted" => false,
        ))->result_array();
        // URUSAN

        // BIDANG
        $count = count($urusan) - 1;
        foreach ($urusan as $key => $val) {
            if ($key == 0) {
                $this->db->where("(urusan_id", $val["id"]);
            } elseif ($key == $count) {
                $this->db->or_where("urusan_id = '" . $val["id"] . "')", NULL);
            } else {
                $this->db->or_where("urusan_id", $val["id"]);
            }
        }
        $bidang_from = $this->db->get_where("v_bidang", array(
            "is_deleted" => false,
        ))->result_array();

        $sql = "INSERT INTO m_bidang (id_urusan, kd_bidang, nm_bidang, is_deleted, created_date, created_by) VALUES ";
        $count = count($bidang_from) - 1;
        foreach ($bidang_from as $key_from => $val_from) {
            $id_urusan = 0;
            foreach ($urusan_to as $key_urus => $val_urus) {
                if ($this->get_nmurusan($val_from["urusan_id"]) == $val_urus["nm_urusan"]) {
                    $id_urusan = $val_urus["id"];
                }
            }

            if ($key_from != $count) {
                $sql .= "(" . $id_urusan . ", " . $val_from["kd_bidang"] . ", '";
                $sql .= $val_from["nm_bidang"] . "', " . $val_from["is_deleted"] . ", '" . date("Y-m-d H:i:s") . "', " . $user_id . "), ";
            } else {
                $sql .= "(" . $id_urusan . ", " . $val_from["kd_bidang"] . ", '";
                $sql .= $val_from["nm_bidang"] . "', " . $val_from["is_deleted"] . ", '" . date("Y-m-d H:i:s") . "', " . $user_id . ")";
            }
        }

        $this->db->query($sql);
        $count = count($urusan_to) - 1;
        foreach ($urusan_to as $key => $val) {
            if ($key == 0) {
                $this->db->where("(urusan_id", $val["id"]);
            } elseif ($key == $count) {
                $this->db->or_where("urusan_id = '" . $val["id"] . "')", NULL);
            } else {
                $this->db->or_where("urusan_id", $val["id"]);
            }
        }
        $bidang_to = $this->db->get_where("v_bidang", array(
            "is_deleted" => false,
        ))->result_array();
        // BIDANG

        // PROGRAM
        $count = count($bidang_from) - 1;
        foreach ($bidang_from as $key => $val) {
            if ($key == 0) {
                $this->db->where("(bidang_id", $val["id"]);
            } elseif ($key == $count) {
                $this->db->or_where("bidang_id = '" . $val["id"] . "')", NULL);
            } else {
                $this->db->or_where("bidang_id", $val["id"]);
            }
        }
        $program_from = $this->db->get_where("v_program", array(
            "is_deleted" => false,
        ))->result_array();

        $sql = "INSERT INTO m_program (id_bidang, kd_program, nm_program, is_deleted, created_date, created_by) VALUES ";
        $count = count($program_from) - 1;
        foreach ($program_from as $key_from => $val_from) {
            $id_bidang = 0;
            foreach ($bidang_to as $key_bid => $val_bid) {
                if ($this->get_rek_bidang($val_from["bidang_id"]) == $val_bid["kode_rekening_bidang"]) {
                    $id_bidang = $val_bid["id"];
                }
            }

            if ($key_from != $count) {
                $sql .= "(" . $id_bidang . ", " . $val_from["kd_program"] . ", '";
                $sql .= $val_from["nm_program"] . "', " . $val_from["is_deleted"] . ", '" . date("Y-m-d H:i:s") . "', " . $user_id . "), ";
            } else {
                $sql .= "(" . $id_bidang . ", " . $val_from["kd_program"] . ", '";
                $sql .= $val_from["nm_program"] . "', " . $val_from["is_deleted"] . ", '" . date("Y-m-d H:i:s") . "', " . $user_id . ")";
            }
        }

        $this->db->query($sql);
        $count = count($bidang_to) - 1;
        foreach ($bidang_to as $key => $val) {
            if ($key == 0) {
                $this->db->where("(bidang_id", $val["id"]);
            } elseif ($key == $count) {
                $this->db->or_where("bidang_id = '" . $val["id"] . "')", NULL);
            } else {
                $this->db->or_where("bidang_id", $val["id"]);
            }
        }
        $program_to = $this->db->get_where("v_program", array(
            "is_deleted" => false,
        ))->result_array();
        // PROGRAM

        // KEGIATAN
        $count = count($program_from) - 1;
        foreach ($program_from as $key => $val) {
            if ($key == 0) {
                $this->db->where("(program_id", $val["id"]);
            } elseif ($key == $count) {
                $this->db->or_where("program_id = '" . $val["id"] . "')", NULL);
            } else {
                $this->db->or_where("program_id", $val["id"]);
            }
        }
        $kegiatan_from = $this->db->get_where("v_kegiatan", array(
            "is_deleted" => false,
        ))->result_array();

        $sql = "INSERT INTO m_kegiatan (id_program, kode, nama, is_deleted, created_date, created_by) VALUES ";
        $count = count($kegiatan_from) - 1;
        foreach ($kegiatan_from as $key_from => $val_from) {
            $id_program = 0;
            foreach ($program_to as $key_pro => $val_pro) {
                if ($this->get_rek_program($val_from["program_id"]) == $val_pro["kode_rekening_program"]) {
                    $id_program = $val_pro["id"];
                }
            }

            if ($key_from != $count) {
                $sql .= '(' . $id_program . ', ' . $val_from["kode"] . ', "';
                $sql .= $val_from["nama"] . '", ' . $val_from["is_deleted"] . ', "' . date("Y-m-d H:i:s") . '", ' . $user_id . '), ';
            } else {
                $sql .= '(' . $id_program . ', ' . $val_from["kode"] . ', "';
                $sql .= $val_from["nama"] . '", ' . $val_from["is_deleted"] . ', "' . date("Y-m-d H:i:s") . '", ' . $user_id . ')';
            }
        }

        $this->db->query($sql);
        $count = count($program_to) - 1;
        foreach ($program_to as $key => $val) {
            if ($key == 0) {
                $this->db->where("(id_program", $val["id"]);
            } elseif ($key == $count) {
                $this->db->or_where("id_program = '" . $val["id"] . "')", NULL);
            } else {
                $this->db->or_where("id_program", $val["id"]);
            }
        }
        $kegiatan_to = $this->db->get_where("m_kegiatan", array(
            "is_deleted" => false,
        ))->result_array();
        // KEGIATAN

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return 0;
        } else {
            $this->db->trans_commit();

            return 1;
        }
    }

    function isjenis_existed($tahun)
    {
        $check = $this->db->get_where("m_jenis_utama", array(
            "tahun" => $tahun,
        ))->result_array();

        if (!empty($check)) {
            return true;
        } else {
            return false;
        }
    }

    function set_allgroup_tosee_data()
    {
        $id_menu = 13;
        $this->db->trans_begin();

        $this->db->distinct();
        $this->db->select("id_group");
        $groups = $this->db->get("group_menu")->result_array();
        foreach ($groups as $key => $val) {
            $check = $this->db->get_where("group_menu", array(
                "id_group" => $val["id_group"],
                "id_menu" => $id_menu,
            ))->row_array();

            if (empty($check)) {
                $this->db->insert("group_menu", array(
                    "id_group" => $val["id_group"],
                    "id_menu" => $id_menu,
                ));
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return 0;
        } else {
            $this->db->trans_commit();

            return 1;
        }
    }

    function copy_jenis($tahun_from, $tahun_to)
    {
        $jenis_utama = $this->db->get_where("m_jenis_utama", array(
            "tahun" => $tahun_from,
        ))->row_array();

        $jenis_utama_to = $this->db->get_where("m_jenis_utama", array(
            "tahun" => $tahun_to,
        ))->row_array();

        if (empty($jenis_utama_to)) {
            $this->db->insert("m_jenis_utama", array(
                "tahun" => $tahun_to,
                "nama_jenis_utama" => $jenis_utama["nama_jenis_utama"],
                "is_deleted" => false,
                "created_date" => $jenis_utama["created_date"],
                "created_by" => $jenis_utama["created_by"],
            ));
        }
    }

    function copy_urusan($tahun_from, $tahun_to)
    {
        $user_id = $this->session->userdata("user_id");
        $jenis_utama = $this->db->get_where("m_jenis_utama", array(
            "tahun" => $tahun_to,
        ))->row_array();

        $urusan = $this->db->get_where("m_urusan", array(
            "tahun" => $tahun_from,
        ))->result_array();

        $urusan_to = $this->db->get_where("m_urusan", array(
            "tahun" => $tahun_to,
        ))->result_array();

        if (empty($urusan_to)) {
            $sql = "INSERT INTO m_urusan (id_jenis_utama, tahun, kd_urusan, nm_urusan, is_deleted, created_date, created_by) VALUES ";
            $count = count($urusan) - 1;
            foreach ($urusan as $key => $val) {
                if (!$val["is_deleted"]) {
                    $sql .= "(" . $jenis_utama["id"] . ", " . $tahun_to . ", " . $val["kd_urusan"] . ", '";
                    $sql .= $val["nm_urusan"] . "', " . $val["is_deleted"] . ", '" . date("Y-m-d H:i:s") . "', " . $user_id . "),";
                }
            }

            $sql = substr($sql, 0, -1);
            $this->db->query($sql);
        }
    }

    function copy_bidang($tahun_from, $tahun_to)
    {
        $user_id = $this->session->userdata("user_id");
        $urusan = $this->db->get_where("m_urusan", array(
            "tahun" => $tahun_to,
        ))->result_array();

        $bidang = $this->db->get_where("v_bidang", array(
            "tahun" => $tahun_from,
        ))->result_array();

        $bidang_to = $this->db->get_where("v_bidang", array(
            "tahun" => $tahun_to,
        ))->result_array();

        if (empty($bidang_to)) {
            $sql = "INSERT INTO m_bidang (id_urusan, tahun, kd_bidang, nm_bidang, is_deleted, created_date, created_by) VALUES ";
            $count = count($urusan) - 1;
            foreach ($urusan as $kke => $kva) {
                $countb = count($bidang) - 1;
                foreach ($bidang as $bke => $bva) {
                    if (!$bva["is_deleted"]) {
                        if ($bva["nm_urusan"] == $kva["nm_urusan"]) {
                            $sql .= "(" . $kva["id"] . ", " . $tahun_to . ", " . $bva["kd_bidang"] . ", '";
                            $sql .= $bva["nm_bidang"] . "', " . $bva["is_deleted"] . ", '" . date("Y-m-d H:i:s") . "', " . $user_id . "),";
                        }
                    }
                }
            }

            $sql = substr($sql, 0, -1);
            $this->db->query($sql);
        }
    }

    function copy_program($tahun_from, $tahun_to)
    {
        $user_id = $this->session->userdata("user_id");
        $bidang = $this->db->get_where("m_bidang", array(
            "tahun" => $tahun_to,
        ))->result_array();

        $program = $this->db->get_where("v_program", array(
            "tahun" => $tahun_from,
        ))->result_array();

        $program_to = $this->db->get_where("v_program", array(
            "tahun" => $tahun_to,
        ))->result_array();

        if (empty($program_to)) {
            $sql = "INSERT INTO m_program (id_bidang, tahun, kd_program, nm_program, is_deleted, created_date, created_by) VALUES ";
            $count = count($bidang) - 1;
            foreach ($bidang as $bke => $bva) {
                $countp = count($program) - 1;
                foreach ($program as $pke => $pva) {
                    if (!$pva["is_deleted"]) {
                        if ($bva["nm_bidang"] == $pva["nm_bidang"]) {
                            $sql .= "(" . $bva["id"] . ", " . $tahun_to . ", " . $pva["kd_program"] . ", '";
                            $sql .= $pva["nm_program"] . "', " . $pva["is_deleted"] . ", '" . date("Y-m-d H:i:s") . "', " . $user_id . "),";
                        }
                    }
                }
            }

            $sql = substr($sql, 0, -1);
            $this->db->query($sql);
        }
    }

    function copy_data($tahun_from, $tahun_to, $level)
    {
        $user_id = $this->session->userdata("user_id");
        $parent = array();
        $child = array();
        $child_to = array();
        if ($level == 1) {
            $parent = $this->db->get_where("v_program", array(
                "tahun" => $tahun_to,
            ))->result_array();

            $child = $this->db->get_where("v_data", array(
                "tahun" => $tahun_from,
                "level" => $level,
            ))->result_array();

            $child_to = $this->db->get_where("v_data", array(
                "tahun" => $tahun_to,
                "level" => $level,
            ))->result_array();
        } else {
            $parent = $this->db->get_where("v_data", array(
                "tahun" => $tahun_to,
                "level" => ($level - 1),
            ))->result_array();

            $child = $this->db->get_where("v_data_2", array(
                "tahun" => $tahun_from,
                "level" => $level,
            ))->result_array();

            $child_to = $this->db->get_where("v_data_2", array(
                "tahun" => $tahun_to,
                "level" => $level,
            ))->result_array();

            if ($level > 2) {
                $parent = $this->db->get_where("v_data_2", array(
                    "tahun" => $tahun_to,
                    "level" => ($level - 1),
                ))->result_array();

                $child = $this->db->get_where("v_data_3", array(
                    "tahun" => $tahun_from,
                    "level" => $level,
                ))->result_array();
            }
        }

        // $this->print_out_data($parent);

        if (empty($child_to)) {
            $sql = "INSERT INTO t_data (tahun, id_program, level, id_parent, uraian, satuan, nilai, is_deleted, created_date, created_by) VALUES ";
            foreach ($parent as $pke => $pva) {
                foreach ($child as $cke => $cva) {
                    if (!$cva["is_deleted"]) {
                        if ($level == 1) {
                            if ($pva["nm_program"] == $cva["nm_program"] && $pva["nm_bidang"] == $cva["nm_bidang"] && $pva["nm_urusan"] == $cva["nm_urusan"]) {
                                $sql .= "(" . $tahun_to . ", " . $pva["id"] . ", " . $level . ", 0, '" . $cva["uraian"] . "', '";
                                $sql .= $cva["satuan"] . "', '" . $cva["nilai"] . "', " . $cva["is_deleted"] . ", '" . date("Y-m-d H:i:s") . "', " . $user_id . "),";
                            }
                        } elseif ($level > 2) {
                            if (
                                $pva["uraian"] == $cva["nm_parent"]
                                && $pva["nm_program"] == $cva["nm_program"]
                                && $pva["nm_bidang"] == $cva["nm_bidang"]
                                && $pva["nm_urusan"] == $cva["nm_urusan"]
                                && $pva["nm_parent"] == $cva["nm_par_parent"]
                            ) {
                                $sql .= "(" . $tahun_to . ", " . $pva["id_program"] . ", " . $level . ", " . $pva["id"] . ", '" . $cva["uraian"] . "', '";
                                $sql .= $cva["satuan"] . "', '" . $cva["nilai"] . "', " . $cva["is_deleted"] . ", '" . date("Y-m-d H:i:s") . "', " . $user_id . "),";
                            }
                        } else {
                            if (
                                $pva["uraian"] == $cva["nm_parent"]
                                && $pva["nm_program"] == $cva["nm_program"]
                                && $pva["nm_bidang"] == $cva["nm_bidang"]
                                && $pva["nm_urusan"] == $cva["nm_urusan"]
                            ) {
                                $sql .= "(" . $tahun_to . ", " . $pva["id_program"] . ", " . $level . ", " . $pva["id"] . ", '" . $cva["uraian"] . "', '";
                                $sql .= $cva["satuan"] . "', '" . $cva["nilai"] . "', " . $cva["is_deleted"] . ", '" . date("Y-m-d H:i:s") . "', " . $user_id . "),";
                            }
                        }
                    }
                }
            }

            // $this->print_out_data($sql);
            $sql = substr($sql, 0, -1);
            $this->db->query($sql);
        }
    }

    function copy_simple_bytahuntipe($tahun_from, $tahun_to, $tipe, $level = null)
    {
        $this->db->trans_begin();
        $user_id = $this->session->userdata("user_id");

        if ($tipe == HIE_JENIS) {
            $this->copy_jenis($tahun_from, $tahun_to);
        } elseif ($tipe == HIE_URUSAN) {
            $this->copy_urusan($tahun_from, $tahun_to);
        } elseif ($tipe == HIE_BIDANG) {
            $this->copy_bidang($tahun_from, $tahun_to);
        } elseif ($tipe == HIE_PROGRAM) {
            $this->copy_program($tahun_from, $tahun_to);
        } elseif ($tipe == HIE_DATA) {
            $this->copy_data($tahun_from, $tahun_to, $level);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return 0;
        } else {
            $this->db->trans_commit();

            return 1;
        }
    }

    function get_opd_inbidang()
    {
        $this->db->group_by("id_group");
        $this->db->order_by("id_group");
        $result = $this->db->get("v_opd_group")->result_array();

        return $result;
    }

    function get_bidang_bygroup($group, $tahun)
    {
        $group_bidang = $this->db->get_where("v_opd_group", array(
            "id_group" => $group,
        ))->result_array();

        // $this->print_out_data($group_bidang);

        $result = array();
        foreach ($group_bidang as $val) {
            $group = $this->db->get_where("v_bidang", array(
                "nm_bidang" => $val["nm_bidang"],
                "jenis_tahun" => $tahun,

                "is_deleted" => false,
            ))->result_array();

            foreach ($group as $gro) {
                $result[] = array(
                    "id_bidang" => $gro["id"],
                    "nm_bidang" => $gro["nm_bidang"],
                );
            }
        }

        return $result;
    }

    function get_counter_data_bybidangtahun($bidang, $tahun)
    {
        $max = $this->db->query("SELECT MAX(level) AS max_level FROM v_data_kegiatan")->row_array();

        $result = array();
        $jumlah_data = 0;
        $jumlah_input = 0;
        for ($level = 1; $level <= $max["max_level"]; $level++) {
            $parent = $this->db->get_where("v_data_kegiatan", array(
                "level" => $level,
                "bidang_id" => $bidang,
                "jenis_tahun" => $tahun,

                "is_deleted" => false,
                "kegiatan_deleted" => false,
                "program_deleted" => false,
                "bidang_deleted" => false,
                "urusan_deleted" => false,
                "jenis_deleted" => false,
            ))->result_array();
            // $this->print_out_data($parent);

            $child_level = $level + 1;
            foreach ($parent as $val) {
                $child = $this->db->get_where("v_data_kegiatan", array(
                    "level" => $child_level,
                    "id_parent" => $val["id"],
                    "bidang_id" => $bidang,
                    "jenis_tahun" => $tahun,

                    "is_deleted" => false,
                    "kegiatan_deleted" => false,
                    "program_deleted" => false,
                    "bidang_deleted" => false,
                    "urusan_deleted" => false,
                    "jenis_deleted" => false,
                ))->result_array();

                if (empty($child)) {
                    $jumlah_data++;

                    if ($val["nilai"] === NULL or $val["nilai"] === "") {
                    } else {
                        $jumlah_input++;
                    }
                }
            }
        }

        $persentase = 0;
        if ($jumlah_input != 0) {
            $persentase = ($jumlah_input / $jumlah_data) * 100;
        }

        $result = array(
            "jumlah_data" => $jumlah_data,
            "jumlah_input" => $jumlah_input,
            "persentase" => $persentase,
        );

        return $result;
    }

    function save_counter($data)
    {
        $this->db->where("tahun", $data[0]["tahun"]);
        $this->db->delete("t_counter_data");

        $count = count($data) - 1;
        $save = "INSERT INTO t_counter_data VALUES ";
        foreach ($data as $key => $val) {
            $persentase = ($val["jumlah_input"] / $val["jumlah_data"]) * 100;

            if ($key == $count) {
                $save .= "(" . $val["id_group"] . ", " . $val["tahun"] . ", '" . $val["nm_group"] . "', " . $val["jumlah_data"] . ", " . $val["jumlah_input"] . ", " . $persentase . ")";
            } else {
                $save .= "(" . $val["id_group"] . ", " . $val["tahun"] . ", '" . $val["nm_group"] . "', " . $val["jumlah_data"] . ", " . $val["jumlah_input"] . ", " . $persentase . "), ";
            }
        }

        $save = $this->db->query($save);
        return $save;
    }

    function get_counter($tahun)
    {
        $result = $this->db->get_where("t_counter_data", array(
            "tahun" => $tahun,
        ));

        return $result->result_array();
    }

    function check_counter_bytahun($tahun)
    {
        $result = $this->db->get_where("t_counter_data", array(
            "tahun" => $tahun,
        ));

        return $result->result_array();
    }
}
