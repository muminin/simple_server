<?php

class Data_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_parent_list($level, $parent)
    {
        $result = $this->db->get_where("v_data_kegiatan", array(
            "id_parent" => $parent,
            "level" => $level,
            "is_deleted" => 0,
        ));

        return $result->result_array();
    }

    function get_data_byparent($parent)
    {
        $result = $this->db->get_where("t_data_kegiatan", array(
            "id_parent" => $parent,
            "is_deleted" => 0,
        ));

        return $result->result_array();
    }

    function sum_nilai($data)
    {
        $this->db->select("id, id_parent, nilai");
        $list = $this->db->get_where("t_data_kegiatan", array(
            "id_parent" => $data,
            "is_deleted" => false,
        ))->result_array();

        $result = "";
        if (!empty($list)) {
            foreach ($list as $val) {
                $nilai = $this->sum_nilai($val["id"]);
                if (!empty($nilai)) {
                    $result += $nilai;
                }

                if (empty($this->get_data_byparent($val["id"]))) {
                    if (!preg_match("/[a-z]/i", $val["nilai"])) {
                        $nilai = $val["nilai"];

                        $check = substr($nilai, -1);
                        if ($check === ".00") {
                            $check = substr($nilai, 0, -3);
                            $check = str_replace(".", "", $check);

                            $nilai = $check;
                        }

                        $result += $nilai;
                    }
                }
            }
        }

        if ($result == "0") {
            $result = "0";
        }

        return $result;
    }

    function count_data_byparent($id_parent)
    {
        $this->db->select("id_parent, is_deleted, kegiatan_deleted, program_deleted, bidang_deleted, urusan_deleted, jenis_deleted");
        $result = $this->db->get_where("v_data_kegiatan", array(
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

    function all_data($tahun, $parent = 0, $level = 1, $kegiatan = 0, $program = 0, $bidang = 0, $urusan = 0)
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

        $result = array();

        $this->db->select("id, level, id_parent, uraian, satuan, nilai, is_deleted,
        kegiatan_id, kegiatan_nama, kegiatan_deleted,
        program_id, program_nama, program_deleted,
        bidang_id, bidang_nama, bidang_deleted,
        urusan_id, urusan_nama, urusan_deleted,
        jenis_id, jenis_nama, jenis_deleted, jenis_tahun");

        if ($kegiatan > 0) {
            $this->db->where("kegiatan_id", $kegiatan);
        } elseif ($program > 0) {
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
            $this->db->order_by("kegiatan_id", "asc");
            $this->db->order_by("id", "asc");

            $result = $this->db->get("v_data_kegiatan")->result_array();
        } else {
            $bidang = $this->session->userdata("bidang");
            $this->db->order_by("kegiatan_id", "asc");
            $this->db->order_by("id", "asc");

            if (isset($bidang)) {
                $result = $this->db->get("v_data_kegiatan")->result_array();
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

                        $sum = $this->sum_nilai($val["id"]);
                        if (empty($sum)) {
                            $sum = $parent_data["nilai"];
                        }

                        $result[$key]["parent_nilai_" . $i] = $sum;
                        $result[$key]["parent_total_data_" . $i] = $this->count_data_byparent($val["id_parent"]);

                        $result[$key]["parent_url_" . $i] = "skpd/Data?tahun=" . $parent_data["jenis_tahun"] . "&id_parent=" . $parent_data["id_parent"] . "&level=" . $parent_data["level"];
                        $temp_parent = $parent_data["id_parent"];
                    }

                    $nilai_sum = $this->sum_nilai($val["id"]);

                    // echo $nilai_sum;

                    $result[$key]["nilai_sum"] = ($nilai_sum != "") ? $nilai_sum : $val["nilai"];
                    $result[$key]["total_data"] = $this->count_data_byparent($val["id"]);
                }
            } else {
                foreach ($result as $key => $val) {
                    $nilai_sum = $this->sum_nilai($val["id"]);

                    $result[$key]["nilai_sum"] = ($nilai_sum != "") ? $nilai_sum : $val["nilai"];
                    $result[$key]["total_data"] = $this->count_data_byparent($val["id"]);
                }
            }

            // exit;
            return $result;
        }
    }

    function get_data_byid($id)
    {
        $result = $this->db->get_where("v_data_kegiatan", array(
            "id" => $id,
        ));

        return $result->row_array();
    }

    function update($data, $id)
    {
        $this->db->trans_begin();

        $data_exist = $this->db->get_where("t_data_kegiatan", array(
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
        $result = $this->db->update("t_data_kegiatan", $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return 0;
        } else {
            $this->db->trans_commit();

            return 1;
        }
        // return $result;
    }

    function save_multiple($data)
    {
        $this->db->trans_begin();

        $sql = "INSERT INTO t_data_kegiatan (id_kegiatan, level, uniq, uraian, satuan, nilai, created_date, created_by, id_parent, is_deleted) VALUES ";
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

        // $this->print_out_data($sql);
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

        $result = array();
        $this->db->select("id, level, id_parent, uraian, satuan, nilai, is_deleted,
        kegiatan_id, kegiatan_nama, kegiatan_deleted,
        program_id, program_nama, program_deleted,
        bidang_id, bidang_nama, bidang_deleted,
        urusan_id, urusan_nama, urusan_deleted,
        jenis_id, jenis_nama, jenis_deleted, jenis_tahun");

        $this->db->where("level", 1);
        $this->db->where("jenis_tahun", $tahun);

        $this->db->where("is_deleted", false);
        $this->db->where("kegiatan_deleted", false);
        $this->db->where("program_deleted", false);
        $this->db->where("bidang_deleted", false);
        $this->db->where("urusan_deleted", false);
        $this->db->where("jenis_deleted", false);
        if ($this->session->userdata("group") == 1) {
            $this->db->order_by("kegiatan_id", "asc");
            $this->db->order_by("id", "asc");

            $result = $this->db->get("v_data_kegiatan")->result_array();
        } else {
            $bidang = $this->session->userdata("bidang");
            $this->db->order_by("kegiatan_id", "asc");
            $this->db->order_by("id", "asc");

            if (isset($bidang)) {
                $result = $this->db->get("v_data_kegiatan")->result_array();
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
                        "jenis_utama" => $val["jenis_nama"],
                        "urusan" => $val["urusan_nama"],
                        "bidang" => $val["bidang_nama"],
                        "program" => $val["program_nama"],
                        "kegiatan" => $val["kegiatan_nama"],

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

        $result = array();

        $this->db->select("id, level, id_parent, uraian, satuan, nilai, is_deleted,
        kegiatan_id, kegiatan_nama, kegiatan_deleted,
        program_id, program_nama, program_deleted,
        bidang_id, bidang_nama, bidang_deleted,
        urusan_id, urusan_nama, urusan_deleted,
        jenis_id, jenis_nama, jenis_deleted, jenis_tahun");

        $this->db->where("id_parent", $parent);
        $this->db->where("is_deleted", false);
        $this->db->where("kegiatan_deleted", false);
        $this->db->where("program_deleted", false);
        $this->db->where("bidang_deleted", false);
        $this->db->where("urusan_deleted", false);
        $this->db->where("jenis_deleted", false);
        if ($this->session->userdata("group") == 1) {
            $this->db->order_by("kegiatan_id", "asc");
            $this->db->order_by("id", "asc");

            $result = $this->db->get("v_data_kegiatan")->result_array();
        } else {
            $bidang = $this->session->userdata("bidang");
            $this->db->order_by("kegiatan_id", "asc");
            $this->db->order_by("id", "asc");

            if (isset($bidang)) {
                $result = $this->db->get("v_data_kegiatan")->result_array();
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

    public function update_nilai($data)
    {
        // $update = "";
        $this->db->trans_begin();

        foreach ($data as $val) {
            if (!empty($val["id"])) {
                $nilai = NULL;
                if (!empty($val["nilai"])) {
                    $nilai = $val["nilai"];
                }

                $this->db->where("id", $val["id"]);
                $this->db->update("t_data_kegiatan", array(
                    "nilai" => $nilai,
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

    function data_lama($kegiatan, $parent = 0, $level = 1)
    {
        $kegiatan = $this->db->get_where("v_kegiatan", array("id" => $kegiatan))->row_array();

        $this->db->order_by("id", "desc");
        $this->db->where("uraian IS NOT NULL", NULL, FALSE);
        $this->db->where("uraian != '-'", NULL, FALSE);
        $this->db->where("uraian != ''", NULL, FALSE);

        if ($kegiatan["jenis_tahun"] == 2020) {
            $data = $this->db->get_where("t_data", array(
                "id_parent" => $parent,
                "level" => $level,

                "id_program" => $kegiatan["program_id"],
            ))->result_array();
        } else {
            $data = $this->db->get_where("t_data", array(
                "tahun" => $kegiatan["jenis_tahun"],

                "id_parent" => $parent,
                "level" => $level,
            ))->result_array();
        }

        $selected_data = array();
        foreach ($data as $key => $val) {
            $kegiatan_check = $this->db->get_where("v_data_kegiatan", array(
                "uraian" => $val["uraian"],
                "jenis_tahun" => $kegiatan["jenis_tahun"],
                "is_deleted" => false,

                "kegiatan_deleted" => false,
                "program_deleted" => false,
                "bidang_deleted" => false,
                "urusan_deleted" => false,
                "jenis_deleted" => false,
            ))->row_array();

            $data[$key]["kegiatan_check"] = "";
            if (!empty($kegiatan_check)) {
                $data[$key]["kegiatan_check"] = $kegiatan_check["kegiatan_nama"];

                if ($kegiatan_check["kegiatan_id"] == $kegiatan["id"]) {
                    $selected_data[] = $val["id"];
                }
            }
        }

        // $this->db->select("id");
        // $data_kegiatan = $this->db->get_where("v_data_kegiatan", array(
        //     "kegiatan_id" => $kegiatan["id"],
        //     "level" => 1,
        //     "is_deleted" => false,

        //     "kegiatan_deleted" => false,
        //     "program_deleted" => false,
        //     "bidang_deleted" => false,
        //     "urusan_deleted" => false,
        //     "jenis_deleted" => false,
        // ))->result_array();

        // $selected_data = array();
        // foreach ($data_kegiatan as $val) {
        //     $selected_data[] = $val["id"];
        // }

        $result["data"] = $data;
        $result["kegiatan"] = $kegiatan;
        $result["selected_data"] = $selected_data;

        // $this->print_out_data($result);
        return $result;
    }

    function get_last_uniq_bykegiatan($kegiatan)
    {
        $this->db->order_by("id", "desc");
        $result = $this->db->get_where("t_data_kegiatan", array(
            "id_kegiatan" => $kegiatan,
            "level" => 1,
        ));

        return $result->row_array();
    }

    function insert_data_lama($data)
    {
        $this->db->trans_begin();

        foreach ($data["data"] as $val) {
            $data_lama = $this->db->get_where("t_data", array("id" => $val))->row_array();

            $check = $this->db->get_where("v_data_kegiatan", array(
                "uraian" => $data_lama["uraian"],
                "satuan" => $data_lama["satuan"],
                "jenis_tahun" => $data_lama["tahun"],
            ))->result_array();

            if (empty($check)) {
                $uniq = $data["jenis"] . $data["urusan"] . $data["bidang"] . $data["program"] . $data["kegiatan"];
                $last_uniq = $this->get_last_uniq_bykegiatan($data["kegiatan"]);
                if (empty($last_uniq)) {
                    $uniq .= "n1";
                } else {
                    $last_uniq = explode("n", $last_uniq["uniq"]);
                    $last_uniq = (int) $last_uniq[1] + 1;
                    $uniq .= "n" . $last_uniq;
                }

                $insert = array(
                    "id_kegiatan" => $data["kegiatan"],
                    "level" => 1,
                    "id_parent" => 0,
                    "uniq" => $uniq,

                    "uraian" => $data_lama["uraian"],
                    "satuan" => $data_lama["satuan"],
                    "nilai" => $data_lama["nilai"],

                    "is_deleted" => false,
                    "created_date" => date("Y-m-d H:i:s"),
                    "created_by" => $this->user_id,
                );

                $this->db->insert("t_data_kegiatan", $insert);
                $insert_par = $this->db->insert_id();

                $child2 = $this->db->get_where("t_data", array(
                    "id_parent" => $data_lama["id"],
                    "is_deleted" => false,
                ))->result_array();

                foreach ($child2 as $val2) {
                    $insert = array(
                        "id_kegiatan" => $data["kegiatan"],
                        "level" => 2,
                        "id_parent" => $insert_par,
                        "uniq" => $uniq,

                        "uraian" => $val2["uraian"],
                        "satuan" => $val2["satuan"],
                        "nilai" => $val2["nilai"],

                        "is_deleted" => false,
                        "created_date" => date("Y-m-d H:i:s"),
                        "created_by" => $this->user_id,
                    );

                    $this->db->insert("t_data_kegiatan", $insert);
                    $insert2 = $this->db->insert_id();

                    $child3 = $this->db->get_where("t_data", array(
                        "id_parent" => $val2["id"],
                        "is_deleted" => false,
                    ))->result_array();

                    foreach ($child3 as $val3) {
                        $insert = array(
                            "id_kegiatan" => $data["kegiatan"],
                            "level" => 3,
                            "id_parent" => $insert2,
                            "uniq" => $uniq,

                            "uraian" => $val3["uraian"],
                            "satuan" => $val3["satuan"],
                            "nilai" => $val3["nilai"],

                            "is_deleted" => false,
                            "created_date" => date("Y-m-d H:i:s"),
                            "created_by" => $this->user_id,
                        );

                        $this->db->insert("t_data_kegiatan", $insert);
                        $insert3 = $this->db->insert_id();
                    }
                }
            } else {
                foreach ($check as $ch) {
                    $update2 = $this->db->get_where("t_data_kegiatan", array(
                        "id_parent" => $ch["id"],
                    ))->result_array();

                    $this->db->where("id", $ch["id"]);
                    $this->db->update("t_data_kegiatan", array(
                        "id_kegiatan" => $data["kegiatan"],
                        "is_deleted" => false,
                    ));

                    foreach ($update2 as $up2) {
                        $update3 = $this->db->get_where("t_data_kegiatan", array(
                            "id_parent" => $up2["id"],
                        ))->result_array();

                        $this->db->where("id_parent", $up2["id"]);
                        $this->db->update("t_data_kegiatan", array(
                            "id_kegiatan" => $data["kegiatan"],
                        ));

                        foreach ($update3 as $up3) {
                            $this->db->where("id_parent", $up3["id"]);
                            $this->db->update("t_data_kegiatan", array(
                                "id_kegiatan" => $data["kegiatan"],
                            ));
                        }
                    }
                }
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

    function delete($id)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("t_data_kegiatan", array(
            "is_deleted" => true,
        ));

        return $result;
    }
}
