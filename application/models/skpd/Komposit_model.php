<?php

class Komposit_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function kom_filter_bidang($bidang_call)
    {
        if ($this->session->userdata("group") == 1) {
        } else {
            $bidang_sess = $this->session->userdata("bidang");

            $where = "";
            $count = count($bidang_sess) - 1;
            if (isset($bidang_sess)) {
                $where = "";
                foreach ($bidang_sess as $key => $val) {
                    $bidang_name = $this->get_bidang_name_byid($val["id_bidang"]);

                    if ($count == 0) {
                        $this->db->where("$bidang_call", $bidang_name);
                    } else {
                        if ($key == 0) {
                            $where .= "($bidang_call = '$bidang_name' OR ";
                        } elseif ($key == $count) {
                            $where .= "$bidang_call = '$bidang_name')";
                        } else {
                            $where .= "$bidang_call = '$bidang_name' OR ";
                        }
                    }
                }
            }

            if ($count > 0) {
                $this->db->where($where, NULL);
            }
        }
    }

    function get_all($tahun)
    {
        $this->kom_filter_bidang("bidang_nama");
        $result = $this->db->get_where("v_komposit_new", array(
            "jenis_tahun" => $tahun,
            "is_deleted" => false,
        ));

        return $result->result_array();
    }

    function get_byid($komposit)
    {
        $result = $this->db->get_where("v_komposit_new", array(
            "id" => $komposit,
        ));

        return $result->row_array();
    }

    function get_bidang_bytahun($tahun)
    {
        $this->kom_filter_bidang("nm_bidang");
        $result = $this->db->get_where("v_bidang", array(
            "jenis_tahun" => $tahun,
        ));

        return $result->result_array();
    }

    function get_data_bybidang($bidang)
    {
        $result = $this->db->get_where("v_bidang", array(
            "id" => $bidang,
        ));

        return $result->result_array();
    }

    function save($data)
    {
        $result = $this->db->insert("t_komposit_new", $data);

        return $result;
    }

    function update($id, $data)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("t_komposit_new", $data);

        return $result;
    }

    function delete($komposit)
    {
        $this->db->where("id", $komposit);
        $result = $this->db->update("t_komposit_new", array(
            "is_deleted" => true,
            "is_deleted" => true,

            "modified_date" => date("Y-m-d H:i:s"),
            "modified_by" => $this->user_id,
        ));

        return $result;
    }

    function isi_nilai($komposit, $nilai)
    {
        $this->db->where("id", $komposit);
        $result = $this->db->update("t_komposit_new", array(
            "nilai" => $nilai,

            "modified_date" => date("Y-m-d H:i:s"),
            "modified_by" => $this->user_id,
        ));

        return $result;
    }

    function copy_totahun($from, $tahun)
    {
        $this->db->trans_begin();
        $komposit = $this->db->get_where("v_komposit_new", array(
            "is_deleted" => false,
            "jenis_tahun" => $from,
        ))->result_array();

        foreach ($komposit as $kom) {
            $bidang = $this->db->get_where("v_bidang", array(
                "is_deleted" => false,
                "kd_bidang" => $kom["bidang_kode"],
                "urusan_kode" => $kom["urusan_kode"],
                "jenis_tahun" => $tahun,
            ))->row_array();

            $to_save =  array(
                "id_bidang" => $bidang["id"],
                "uraian" => $kom["uraian"],

                "keterangan" => $kom["keterangan"],
                "satuan" => $kom["satuan"],

                "is_deleted" => false,
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $this->user_id,
            );

            $this->db->insert("t_komposit_new", $to_save);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return false;
        } else {
            $this->db->trans_commit();

            return true;
        }
    }

    function get_data_byparent($parent)
    {
        $result = $this->db->get_where("t_data_kegiatan", array(
            "id_parent" => $parent,
            "is_deleted" => 0,
        ));

        return $result->result_array();
    }

    function sum_nilai_old($data)
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

    var $count = 0;
    function sum_nilai($parent, $level = 1, $data = NULL)
    {
        $this->db->select("id, id_parent, uraian, nilai");

        if ($level > 1) {
            $list = $this->db->get_where("t_data_kegiatan", array(
                "id_parent" => $parent,
                "uraian" => $data,
                "is_deleted" => false,
            ))->result_array();
        } else {
            $list = $this->db->get_where("t_data_kegiatan", array(
                "id_parent" => $parent,
                "is_deleted" => false,
            ))->result_array();
        }

        // VIEW TEMP RESULT
        // echo "<pre>";
        // var_dump($this->db->last_query());

        $result = "";
        if (!empty($list)) {
            foreach ($list as $val) {
                // VIEW TEMP RESULT
                // echo "<br>" . $val["uraian"];

                $nilai = $this->sum_nilai($val["id"], 1);
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

    function komposit_bidang($tahun, $kode = NULL)
    {
        if (!empty($kode)) {
            $this->db->where("kode_bidang", $kode);
        }

        $formula = $this->db->get("m_formula")->result_array();
        $result = array();

        foreach ($formula as $fkey => $for) {
            $res = array();
            $value = $this->formula($for["id"], $tahun);

            $urusan = explode(".", $for["kode_bidang"]);
            $bidang = $urusan[1];
            $urusan = $urusan[0];

            $res = array(
                "komposit" => $for["nama"],
                "keterangan" => $for["keterangan"],
                "urusan" => $urusan,
                "bidang" => $bidang,
                "number" => $value["value"],
                "satuan" => $for["satuan"],
                "rumus" => $value["formula"],
                "nilai" => number_format($value["value"], 2),
            );

            $result[] = $res;
        }

        $this->print_out_data($result);
    }

    function formula($formula, $tahun)
    {
        $formula = $this->db->get_where("m_formula", array(
            "id" => $formula,
        ))->row_array();

        if (!empty($formula["rumus"])) {
            $temp = explode("::", $formula["rumus"]);
            $operator = array();
            $variable = array();

            foreach ($temp as $val) {
                if (strlen($val) < 3) {
                    array_push($operator, $val);
                } elseif (
                    strlen($val) <= 3
                    and (strpos($val, "(") !== false
                        or strpos($val, ")") !== false)
                ) {
                    array_push($operator, $val);
                } elseif (strlen($val) > 1) {
                    switch ($val) {
                        case "one":
                            $val = 1;
                            break;
                        case "two":
                            $val = 2;
                            break;
                        case "three":
                            $val = 3;
                            break;
                    }

                    array_push($variable, $val);
                }
            }

            $value = array();
            foreach ($variable as $val) {
                $temp_value = array();

                $temp_a = explode("..", $val);
                if (count($temp_a) > 1) {
                    $temp_value = array(
                        "tahun" => $tahun,
                        "jenis" => $temp_a[0],
                        "urusan" => $temp_a[1],
                        "bidang" => $temp_a[2],
                        "program" => $temp_a[3],
                        "kegiatan" => $temp_a[4],
                        "data" => $temp_a[5],
                        "data2" => $temp_a[6],
                        "data3" => "",
                        "level" => "",
                        "math" => "",
                    );

                    if (count($temp_a) > 1) {
                        foreach ($temp_a as $val_a) {
                            if (strpos($val_a, "||") !== false) {
                                $temp_b = explode("||", $val_a);

                                $temp_value["data3"] = $temp_b[0];
                                $temp_value["level"] = $temp_b[1];
                            }
                        }
                    }
                } else {
                    $temp_value = array(
                        "tahun" => $tahun,
                        "jenis" => "",
                        "urusan" => "",
                        "bidang" => "",
                        "program" => "",
                        "kegiatan" => "",
                        "data" => "",
                        "data2" => "",
                        "data3" => "",
                        "level" => "",
                        "math" => $val,
                    );
                }

                $value[] = $temp_value;
            }

            foreach ($value as $key => $val) {

                // VIEW TEMP RESULT
                // echo "<br> next - " . $val["data2"];

                if (!empty($val["jenis"]) and $val["level"] > 1) {
                    $data = $this->db->get_where("v_data_kegiatan", array(
                        "jenis_tahun" => $val["tahun"],
                        "jenis_nama" => $val["jenis"],
                        "urusan_kode" => $val["urusan"],
                        "bidang_kode" => $val["bidang"],
                        "program_kode" => $val["program"],
                        "kegiatan_kode" => $val["kegiatan"],
                        "uraian" => $val["data"],
                        "level" => 1,
                    ))->row_array();

                    $nilai = $this->sum_nilai($data["id"], $val["level"], $val["data2"]);
                    $value[$key]["nilai"] = !empty($nilai) ? $nilai : 0;
                } elseif (!empty($val["jenis"]) and $val["level"] == 1) {
                    $data = $this->db->get_where("v_data_kegiatan", array(
                        "jenis_tahun" => $val["tahun"],
                        "jenis_nama" => $val["jenis"],
                        "urusan_kode" => $val["urusan"],
                        "bidang_kode" => $val["bidang"],
                        "program_kode" => $val["program"],
                        "kegiatan_kode" => $val["kegiatan"],
                        "uraian" => $val["data"],
                        "level" => $val["level"],
                    ))->row_array();

                    $nilai = $this->sum_nilai($data["id"]);
                    $value[$key]["nilai"] = !empty($nilai) ? $nilai : 0;
                } else {
                    $value[$key]["nilai"] = $val["math"];
                }
            }

            $result = '';
            $i = 0;
            $cont_here = false;
            for ($i; $i < count($operator); $i++) {
                if (isset($value[$i])) {
                    // masuk sini dulu, jika ada kurungnya, dan merubah tatanan formatnya
                    if (strpos($operator[$i], "(") !== false) {
                        $result .= $operator[$i];
                        $result .= $value[$i]["nilai"];

                        $cont_here = true;
                        continue;
                    }

                    if ($cont_here) {
                        $result .= $operator[$i];
                        $result .= $value[$i]["nilai"];
                    } else {
                        // echo "disini";
                        $result .= $value[$i]["nilai"];
                        $result .= $operator[$i];
                    }
                } else {
                    $result .= $operator[$i];
                }
            }

            if ($i < count($operator)) {
                $result .= $value[$i - 1]["nilai"];
            } elseif (!$cont_here) {
                $result .= $value[$i]["nilai"];
            }
            // echo $result . "<br>";

            $value = 0;
            if (
                strpos($result, "/0") !== false
                or strpos($result, "/(0+0)") !== false
                or strpos($result, "/(0+0+0)") !== false
                or strpos($result, "/(0+0+0+0)") !== false
                or strpos($result, "/(0+0+0+0+0)") !== false
                or strpos($result, "/(0+0+0+0+0+0)") !== false
                or strpos($result, "/(0+0+0+0+0+0+0)") !== false
                or strpos($result, "/(0+0+0+0+0+0+0+0)") !== false
            ) {
                $value = 0;
            } else {
                eval('$value = ' . $result . ';');
            }

            // echo $result . "<br><br>";
            return array(
                "formula" => $result,
                "value" => $value,
            );
        } else {
            // echo "- <br><br>";
            return array(
                "formula" => 0,
                "value" => 0,
            );
        }
    }
}
