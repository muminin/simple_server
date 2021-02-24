<?php

class Data_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
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

                if (empty($this->get_data_byparent($val["id"]))) {
                    if (!preg_match("/[a-z]/i", $val["nilai"])) {
                        $result += floatval($val["nilai"]);
                    }
                }
            }
        }

        return $result;
    }

    function all_data($tahun, $parent = 0, $level = 1, $program = 0, $bidang = 0, $urusan = 0)
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
                        $this->db->where("nm_bidang", $bidang_name);
                    } else {
                        if ($key == 0) {
                            $where .= "(nm_bidang = '$bidang_name' OR ";
                        } elseif ($key == $count) {
                            $where .= "nm_bidang = '$bidang_name')";
                        } else {
                            $where .= "nm_bidang = '$bidang_name' OR ";
                        }
                    }
                }
            }

            if ($count > 0) {
                $this->db->where($where, NULL);
            }
        }

        $result = array();

        $this->db->select("id, level, id_parent, uraian, satuan, nilai, is_deleted,
        kegiatan_id, kegiatan_nama, kegiatan_deleted,
        program_id, program_nama, program_deleted,
        bidang_id, bidang_nama, bidang_deleted,
        urusan_id, urusan_nama, urusan_deleted,
        jenis_id, jenis_nama, jenis_deleted, jenis_tahun");

        if ($program > 0) {
            $this->db->where("program_id", $program);
        } elseif ($bidang > 0) {
            $this->db->where("bidang_id", $bidang);
        } elseif ($urusan > 0) {
            $this->db->where("urusan_id", $urusan);
        } elseif ($parent) {
            $this->db->where("id_parent", $parent);
        }

        $this->db->where("level", $level);
        $this->db->where("jenis_tahun", $tahun);

        $this->db->where("is_deleted", false);
        $this->db->where("kegiatan_deleted", false);
        $this->db->where("program_deleted", false);
        $this->db->where("bidang_deleted", false);
        $this->db->where("urusan_deleted", false);
        $this->db->where("jenis_deleted", false);
        if ($this->session->userdata("group") == 1) {
            $this->db->order_by("program_id", "asc");
            $this->db->order_by("id", "asc");

            $result = $this->db->get("v_data_new")->result_array();
        } else {
            $bidang = $this->session->userdata("bidang");
            $this->db->order_by("program_id", "asc");
            $this->db->order_by("id", "asc");

            if (isset($bidang)) {
                $result = $this->db->get("v_data_new")->result_array();
            }
        }
        // $this->print_out_data($this->db->last_query());

        if (!empty($result)) {
            if ($level > 1) {
                foreach ($result as $key => $val) {
                    $temp_parent = 0;
                    for ($i = ($level - 1); $i >= 1; $i--) {
                        if ($temp_parent == 0) {
                            $temp_parent = $val["id_parent"];
                        }

                        $parent_data = $this->get_data_byid($temp_parent);
                        $result[$key]["parent_uraian_" . $i] = $parent_data["uraian"];
                        $result[$key]["parent_satuan_" . $i] = $parent_data["satuan"];
                        // $result[$key]["parent_nilai_" . $i] = !empty($parent_data["nilai"]) ? $parent_data["nilai"] : $this->sum_nilai($val["id"]);
                        $result[$key]["parent_nilai_" . $i] = !empty($this->sum_nilai($val["id"])) ? $this->sum_nilai($val["id"]) : $parent_data["nilai"];
                        $result[$key]["parent_total_data_" . $i] = $this->count_data_byparent($val["id_parent"]);

                        $result[$key]["parent_url_" . $i] = "skpd/Data?tahun=" . $parent_data["jenis_tahun"] . "&id_parent=" . $parent_data["id_parent"] . "&level=" . $parent_data["level"];
                        $temp_parent = $parent_data["id_parent"];
                    }

                    $result[$key]["nilai_sum"] = !empty($this->sum_nilai($val["id"])) ? $this->sum_nilai($val["id"]) : $val["nilai"];
                    $result[$key]["total_data"] = $this->count_data_byparent($val["id"]);
                }
            } else {
                foreach ($result as $key => $val) {
                    $result[$key]["nilai_sum"] = !empty($this->sum_nilai($val["id"])) ? $this->sum_nilai($val["id"]) : $val["nilai"];
                    $result[$key]["total_data"] = $this->count_data_byparent($val["id"]);
                }
            }

            return $result;
        }
    }

    function get_data_byid($id)
    {
        $result = $this->db->get_where("v_data_new", array(
            "id" => $id,
        ));

        return $result->row_array();
    }

    function count_data_byparent($id_parent)
    {
        $this->db->select("id_parent, is_deleted, kegiatan_deleted, program_deleted, bidang_deleted, urusan_deleted, jenis_deleted");
        $result = $this->db->get_where("v_data_new", array(
            "id_parent" => $id_parent,

            "is_deleted" => false,
            "kegiatan_deleted" => false,
            "program_deleted" => false,
            "bidang_deleted" => false,
            "urusan_deleted" => false,
            "jenis_deleted" => false,
        ));

        if (!empty($result)) {
            return $result->num_rows();
        } else {
            return 0;
        }
    }

    function update($data, $id)
    {
        $this->db->trans_begin();

        $data_exist = $this->db->get_where("t_data_new", array(
            "id" => $id,
        ))->row_array();

        $this->db->insert("t_data_history", array(
            "id_data" => $id,
            "uraian" => $data_exist["uraian"],
            "satuan" => $data_exist["satuan"],
            "nilai" => $data_exist["nilai"],
            "created_date" => $data["modified_date"],
            "created_by" => $data["modified_by"],
        ));

        $this->db->where("id", $id);
        $result = $this->db->update("t_data_new", $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return 0;
        } else {
            $this->db->trans_commit();

            return 1;
        }
        // return $result;
    }

    function get_data_byparent($parent)
    {
        $result = $this->db->get_where("v_data_new", array(
            "id_parent" => $parent,
            "is_deleted" => 0,
        ));

        return $result->result_array();
    }

    function save_multiple($data)
    {
        $this->db->trans_begin();

        $sql = "INSERT INTO t_data_new (id_kegiatan, level, uniq, uraian, satuan, nilai, created_date, created_by, id_parent, is_deleted) VALUES ";
        $count = count($data) - 1;
        foreach ($data as $key => $val) {
            if ($key != $count) {
                $sql .= "('" . $val["id_kegiatan"] . "', '" . $val["level"] . "', '";
                $sql .= $val["uniq"] . "', '" . $val["uraian"] . "', '" . $val["satuan"] . "', '" . $val["nilai"] . "', '";
                $sql .= $val["created_date"] . "', '" . $val["created_by"] . "', '" . $val["id_parent"] . "', '" . $val["is_deleted"] . "'), ";
            } else {
                $sql .= "('" . $val["id_kegiatan"] . "', '" . $val["level"] . "', '";
                $sql .= $val["uniq"] . "', '" . $val["uraian"] . "', '" . $val["satuan"] . "', '" . $val["nilai"] . "', '";
                $sql .= $val["created_date"] . "', '" . $val["created_by"] . "', '" . $val["id_parent"] . "', '" . $val["is_deleted"] . "')";
            }
        }

        $this->db->query($sql);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo 0;
        } else {
            $this->db->trans_commit();

            echo 1;
        }
    }

    function all_data_export($tahun)
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
                        $this->db->where("nm_bidang", $bidang_name);
                    } else {
                        if ($key == 0) {
                            $where .= "(nm_bidang = '$bidang_name' OR ";
                        } elseif ($key == $count) {
                            $where .= "nm_bidang = '$bidang_name')";
                        } else {
                            $where .= "nm_bidang = '$bidang_name' OR ";
                        }
                    }
                }
            }

            if ($count > 0) {
                $this->db->where($where, NULL);
            }
        }

        $result = array();

        $this->db->select("id, jenis_tahun, id_program, level, id_parent, uraian, satuan, nilai, is_deleted,
        nm_program, is_program_deleted, nm_bidang, is_bidang_deleted, nm_urusan, is_urusan_deleted,
        nama_jenis_utama, is_jenis_deleted, id_bidang, id_urusan, id_jenis_utama");

        $this->db->where("level", 1);
        $this->db->where("jenis_tahun", $tahun);
        if ($this->session->userdata("group") == 1) {
            $this->db->order_by("id_program", "asc");
            $this->db->order_by("id", "asc");

            $this->db->where("is_deleted", false);
            $result = $this->db->get("v_data")->result_array();
        } else {
            $bidang = $this->session->userdata("bidang");
            $this->db->order_by("id_program", "asc");
            $this->db->order_by("id", "asc");

            if (isset($bidang)) {

                $this->db->where("is_deleted", false);
                $this->db->where("is_program_deleted", false);
                $this->db->where("is_bidang_deleted", false);
                $this->db->where("is_urusan_deleted", false);
                $this->db->where("is_jenis_deleted", false);
                $result = $this->db->get("v_data")->result_array();
            }
        }

        $final = array();
        if (!empty($result)) {
            $temp_id = 0;
            foreach ($result as $key => $val) {
                if ($temp_id == 0 or $temp_id != $val["id"]) {
                    $temp_id = $val["id"];

                    $final[] = array(
                        "id" => "N" . $val["id"] . "N",
                        "level" => $val["level"],
                        "jenis_utama" => $val["nama_jenis_utama"],
                        "urusan" => $val["nm_urusan"],
                        "bidang" => $val["nm_bidang"],
                        "program" => $val["nm_program"],

                        "uraian" => $val["uraian"],
                        "satuan" => $val["satuan"],
                        "nilai" => $val["nilai"],
                    );

                    $child = $this->get_child_byparent($val["id"]);
                    if (!empty($child)) {
                        foreach ($child as $val2) {
                            $final[] = array(
                                "id" => $val2["id"],
                                "level" => $val2["level"],
                                // "jenis_utama" => $val2["nama_jenis_utama"],
                                // "urusan" => $val2["nm_urusan"],
                                // "bidang" => $val2["nm_bidang"],
                                // "program" => $val2["nm_program"],

                                "uraian" => $val2["uraian"],
                                "satuan" => $val["satuan"],
                                "nilai" => $val2["nilai"],
                            );
                        }
                    }
                }
            }
        }

        return $final;
    }

    function get_child_byparent($parent)
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
                        $this->db->where("nm_bidang", $bidang_name);
                    } else {
                        if ($key == 0) {
                            $where .= "(nm_bidang = '$bidang_name' OR ";
                        } elseif ($key == $count) {
                            $where .= "nm_bidang = '$bidang_name')";
                        } else {
                            $where .= "nm_bidang = '$bidang_name' OR ";
                        }
                    }
                }
            }

            if ($count > 0) {
                $this->db->where($where, NULL);
            }
        }

        $result = array();

        $this->db->select("id, jenis_tahun, id_program, level, id_parent, uraian, satuan, nilai, is_deleted,
        nm_program, is_program_deleted, nm_bidang, is_bidang_deleted, nm_urusan, is_urusan_deleted,
        nama_jenis_utama, is_jenis_deleted, id_bidang, id_urusan, id_jenis_utama");

        $this->db->where("id_parent", $parent);
        if ($this->session->userdata("group") == 1) {
            $this->db->order_by("id_program", "asc");
            $this->db->order_by("id", "asc");

            $this->db->where("is_deleted", false);
            $result = $this->db->get("v_data")->result_array();
        } else {
            $bidang = $this->session->userdata("bidang");
            $this->db->order_by("id_program", "asc");
            $this->db->order_by("id", "asc");

            if (isset($bidang)) {

                $this->db->where("is_deleted", false);
                $this->db->where("is_program_deleted", false);
                $this->db->where("is_bidang_deleted", false);
                $this->db->where("is_urusan_deleted", false);
                $this->db->where("is_jenis_deleted", false);
                $result = $this->db->get("v_data")->result_array();
            }
        }

        $final = array();
        if (!empty($result)) {
            $temp_id = 0;
            foreach ($result as $key => $val) {
                if ($temp_id == 0 or $temp_id != $val["id"]) {
                    $temp_id = $val["id"];

                    $final[] = array(
                        "id" => "N" . $val["id"] . "N",
                        "level" => $val["level"],

                        "uraian" => $val["uraian"],
                        "satuan" => $val["satuan"],
                        "nilai" => $val["nilai"],
                    );

                    $child = $this->get_child_byparent($val["id"]);
                    if (!empty($child)) {
                        foreach ($child as $val2) {
                            $final[] = array(
                                "id" => $val2["id"],
                                "level" => $val2["level"],

                                "uraian" => $val2["uraian"],
                                "satuan" => $val2["satuan"],
                                "nilai" => $val2["nilai"],
                            );
                        }
                    }
                }
            }
        }

        return $final;
    }

    // OLD
    function get_bidang_name_byid_old($bidang)
    {
        $bidang_name = $this->db->get_where("v_bidang", array(
            "id" => $bidang,
        ))->row_array()["nm_bidang"];

        return $bidang_name;
    }

    function all_data_old($tahun, $parent = 0, $level = 1, $program = 0, $bidang = 0, $urusan = 0)
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
                        $this->db->where("nm_bidang", $bidang_name);
                    } else {
                        if ($key == 0) {
                            $where .= "(nm_bidang = '$bidang_name' OR ";
                        } elseif ($key == $count) {
                            $where .= "nm_bidang = '$bidang_name')";
                        } else {
                            $where .= "nm_bidang = '$bidang_name' OR ";
                        }
                    }
                }
            }

            if ($count > 0) {
                $this->db->where($where, NULL);
            }
        }

        $result = array();

        $this->db->select("id, jenis_tahun, id_program, level, id_parent, uraian, satuan, nilai, is_deleted,
        nm_program, is_program_deleted, nm_bidang, is_bidang_deleted, nm_urusan, is_urusan_deleted, nama_jenis_utama, is_jenis_deleted,
        id_bidang, id_urusan, id_jenis_utama");

        if ($program > 0) {
            $this->db->where("id_program", $program);
        } elseif ($bidang > 0) {
            $this->db->where("id_bidang", $bidang);
        } elseif ($urusan > 0) {
            $this->db->where("id_urusan", $urusan);
        } else {
            $this->db->where("id_parent", $parent);
        }

        $this->db->where("level", $level);
        $this->db->where("jenis_tahun", $tahun);
        if ($this->session->userdata("group") == 1) {
            $this->db->order_by("id_program", "asc");
            $this->db->order_by("id", "asc");

            $this->db->where("is_deleted", false);
            $result = $this->db->get("v_data")->result_array();
        } else {
            $bidang = $this->session->userdata("bidang");
            $this->db->order_by("id_program", "asc");
            $this->db->order_by("id", "asc");

            if (isset($bidang)) {

                $this->db->where("is_deleted", false);
                $this->db->where("is_program_deleted", false);
                $this->db->where("is_bidang_deleted", false);
                $this->db->where("is_urusan_deleted", false);
                $this->db->where("is_jenis_deleted", false);
                $result = $this->db->get("v_data")->result_array();
            }
        }

        if (!empty($result)) {
            if ($level > 1) {
                foreach ($result as $key => $val) {
                    $temp_parent = 0;
                    for ($i = ($level - 1); $i >= 1; $i--) {
                        if ($temp_parent == 0) {
                            $temp_parent = $val["id_parent"];
                        }

                        $parent_data = $this->get_data_byid($temp_parent);
                        $result[$key]["parent_uraian_" . $i] = $parent_data["uraian"];
                        $result[$key]["parent_satuan_" . $i] = $parent_data["satuan"];
                        $result[$key]["parent_nilai_" . $i] = !empty($parent_data["nilai"]) ? $parent_data["nilai"] : $this->sum_nilai($val["id"]);
                        $result[$key]["parent_total_data_" . $i] = $this->count_data_byparent($val["id_parent"]);

                        $result[$key]["parent_url_" . $i] = "skpd/Data?tahun=" . $parent_data["jenis_tahun"] . "&id_parent=" . $parent_data["id_parent"] . "&level=" . $parent_data["level"];
                        $temp_parent = $parent_data["id_parent"];
                    }

                    $result[$key]["nilai"] = !empty($val["nilai"]) ? $val["nilai"] : $this->sum_nilai($val["id"]);
                    $result[$key]["total_data"] = $this->count_data_byparent($val["id"]);
                }
            } else {
                foreach ($result as $key => $val) {
                    $result[$key]["nilai"] = !empty($val["nilai"]) ? $val["nilai"] : $this->sum_nilai($val["id"]);
                    $result[$key]["total_data"] = $this->count_data_byparent($val["id"]);
                }
            }

            return $result;
        }
    }

    function sum_nilai_old($data)
    {
        $this->db->select("id, id_parent, nilai");
        $list = $this->db->get_where("t_data", array(
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

    function count_data_byparent_old($id_parent)
    {
        $this->db->select("id_parent, is_deleted, is_program_deleted, is_bidang_deleted, is_urusan_deleted, is_jenis_deleted");
        $result = $this->db->get_where("v_data", array(
            "id_parent" => $id_parent,
            "is_deleted" => false,
            "is_program_deleted" => false,
            "is_bidang_deleted" => false,
            "is_urusan_deleted" => false,
            "is_jenis_deleted" => false,
        ));

        if (!empty($result)) {
            return $result->num_rows();
        } else {
            return 0;
        }
    }

    function get_data_byprogramlevel_old($id_program, $level)
    {
        $result = $this->db->get_where("v_data", array(
            "id_program" => $id_program,
            "level" => $level,
            "is_deleted" => false,
        ));

        return $result->row_array();
    }

    function save_old($data)
    {
        $result = $this->db->insert("t_data", $data);
        return $result;
    }

    function get_data_byid_old($id)
    {
        // $this->db->select("id, tahun, id_program, level, id_parent, uraian, satuan, nilai, is_deleted,
        // nm_program, is_program_deleted, nm_bidang, is_bidang_deleted, nm_urusan, is_urusan_deleted, nama_jenis_utama, is_jenis_deleted,
        // id_bidang, id_urusan, id_jenis_utama");
        $result = $this->db->get_where("v_data", array(
            "id" => $id,
        ));

        return $result->row_array();
    }

    function update_old($data, $id)
    {
        $this->db->trans_begin();

        $data_exist = $this->db->get_where("t_data", array(
            "id" => $id,
        ))->row_array();

        $this->db->insert("t_data_history", array(
            "id_data" => $id,
            "uraian" => $data_exist["uraian"],
            "satuan" => $data_exist["satuan"],
            "nilai" => $data_exist["nilai"],
            "created_date" => $data["modified_date"],
            "created_by" => $data["modified_by"],
        ));

        $this->db->where("id", $id);
        $result = $this->db->update("t_data", $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return 0;
        } else {
            $this->db->trans_commit();

            return 1;
        }
        // return $result;
    }

    function delete_old($id)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("t_data", array(
            "is_deleted" => true,
        ));
        return $result;
    }

    function get_data_byprogram_old($id_program)
    {
        $result = $this->db->get_where("v_data", array(
            "id_program" => $id_program,
            "level" => 1,
            "id_parent" => 0,
            "is_deleted" => false,
        ));

        return $result->result_array();
    }

    function save_multiple_old($data)
    {
        $sql = "INSERT INTO t_data (id_program, tahun, level, uraian, satuan, nilai, created_date, created_by, id_parent, is_deleted) VALUES ";
        $count = count($data) - 1;
        foreach ($data as $key => $val) {
            if ($key != $count) {
                $sql .= "('" . $val["id_program"] . "', '" . $val["tahun"] . "', '" . $val["level"] . "', '";
                $sql .= $val["uraian"] . "', '" . $val["satuan"] . "', '" . $val["nilai"] . "', '";
                $sql .= $val["created_date"] . "', '" . $val["created_by"] . "', '" . $val["id_parent"] . "', '" . $val["is_deleted"] . "'), ";
            } else {
                $sql .= "('" . $val["id_program"] . "', '" . $val["tahun"] . "', '" . $val["level"] . "', '";
                $sql .= $val["uraian"] . "', '" . $val["satuan"] . "', '" . $val["nilai"] . "', '";
                $sql .= $val["created_date"] . "', '" . $val["created_by"] . "', '" . $val["id_parent"] . "', '" . $val["is_deleted"] . "')";
            }
        }

        $this->db->query($sql);
    }

    function get_data_byparent_old($parent)
    {
        $result = $this->db->get_where("v_data", array(
            "id_parent" => $parent,
            "is_deleted" => 0,
        ));

        return $result->result_array();
    }

    function get_parent_list_old($level, $parent)
    {
        $result = $this->db->get_where("v_data", array(
            "id_parent" => $parent,
            "level" => $level,
            "is_deleted" => 0,
        ));

        return $result->result_array();
    }

    function get_history_byprogram_old($id_program)
    {
        $this->db->order_by("created_date", "asc");
        $result = $this->db->get_where("v_data_history", array(
            "program_id" => $id_program,
        ));

        return $result->result_array();
    }

    function get_data_bytahun_old($id_program, $tahun)
    {
        $result = $this->db->get_where("v_data", array(
            "id_program" => $id_program,
            "tahun" => $tahun,

            "is_deleted" => false,
            "is_program_deleted" => false,
            "is_bidang_deleted" => false,
            "is_urusan_deleted" => false,
            "is_jenis_deleted" => false,
        ));

        return $result->result_array();
    }
}
