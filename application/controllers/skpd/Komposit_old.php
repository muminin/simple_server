<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Komposit extends WH_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->login_check();

        $this->load->model("skpd/Master_model", "mas_model");
        $this->load->model("skpd/Komposit_model", "kom_model");
    }

    public function index()
    {
        $get = $this->input->get();
        $tahun = date("Y");
        if (!empty($get["tahun"])) {
            $tahun = $get["tahun"];
        }

        $data["komposit"] = $this->kom_model->get_all($tahun);
        $data["tahun"] = $this->mas_model->get_tahun_from_urusan();
        $data["tahun_selected"] = $tahun;

        $data["title"] = "Komposit";
        $data["_view"] = "skpd/komposit/index";
        $data["_js"] = "skpd/komposit/index_js";
        $data["_plugin"] = array(
            PLUG_NOTIFICATION => "1",
            PLUG_DATATABLE => "1",
            PLUG_SELECT2 => "1",
        );

        // $this->print_out_data($data);
        $this->show_layout($data);
    }

    public function tambah()
    {
        $post = $this->input->post();
        if (!empty($post)) {
            $save = array(
                "tahun" => $post["tahun"],
                "id_bidang" => $post["bidang"],

                "nama" => $post["nama"],
                "satuan" => $post["satuan"],
                "rumus" => $post["rumus"],

                "is_deleted" => false,
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $this->user_id,
            );

            // $this->print_out_data($save);
            $result = $this->kom_model->save($save);
            echo $result;
        } else {
            $data["title"] = "Tambah Komposit";
            $data["_view"] = "skpd/komposit/tambah";
            $data["_js"] = "skpd/komposit/tambah_js";

            $data["tahun"] = $this->mas_model->get_tahun_from_urusan();
            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
                PLUG_SELECT2 => "1",
            );

            $this->show_layout($data);
        }
    }

    public function ubah()
    {
        $get = $this->input->get();
        $post = $this->input->post();

        if (!empty($post)) {
            $save = array(
                "tahun" => $post["tahun"],
                "id_bidang" => $post["bidang"],

                "nama" => $post["nama"],
                "satuan" => $post["satuan"],
                "rumus" => $post["rumus"],

                "modified_date" => date("Y-m-d H:i:s"),
                "modified_by" => $this->user_id,
            );

            $result = $this->kom_model->update($post["id"], $save);
            echo $result;
        } else {
            $data["title"] = "Ubah Komposit";
            $data["_view"] = "skpd/komposit/ubah";
            $data["_js"] = "skpd/komposit/ubah_js";
            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
                PLUG_SELECT2 => "1",
            );

            $komposit = $this->kom_model->get_byid($get["komposit"]);
            $rumus = explode("||", $komposit["rumus"]);

            $rumus_result = "";
            foreach ($rumus as $val) {

                // $this->print_out_data($komposit);

                // if (strlen($val) > 1) {
                if (strpos($val, 'n') !== false) {
                    $uraian = $this->kom_model->get_data_byuniq($val, $komposit["jenis_tahun"]);
                    // echo var_dump($uraian) . "<br>";
                    if (!empty($uraian)) {
                        $rumus_result .= " " . $uraian["uraian"];
                    } else {
                        $rumus_result .= " " . $val;
                    }
                } else {
                    $rumus_result .= " <strong>" . $val . "</strong>";
                }
            }
            // $this->print_out_data($komposit);

            $komposit["rumus_view"] = $rumus_result;

            $data["komposit"] = $komposit;
            $data["tahun"] = $this->mas_model->get_tahun_from_urusan();

            // $this->print_out_data($data);
            $this->show_layout($data);
        }
    }

    public function hapus()
    {
        $get = $this->input->get();
        echo $this->kom_model->delete($get["kegiatan"]);
    }

    public function get_bidang_bytahun()
    {
        $get = $this->input->get();
        $result = $this->kom_model->get_bidang_bytahun($get["tahun"]);

        echo json_encode($result);
    }

    public function get_data_bybidang()
    {
        $get = $this->input->get();
        $result = $this->kom_model->get_data_bybidang($get["bidang"]);

        echo json_encode($result);
    }

    public function get_rumus_byid()
    {
        $get = $this->input->get();
        $komposit = $this->kom_model->get_byid($get["komposit"]);
        $rumus = explode("||", $komposit["rumus"]);

        $rumus_result = "";
        $value_result = "";
        $value_calculate = "";
        foreach ($rumus as $val) {
            if (strlen($val) > 1) {
                // echo $val;
                $uraian = $this->kom_model->get_data_byuniq($val, $komposit["jenis_tahun"]);
                // echo $uraian["uraian"];

                if (!empty($uraian["uraian"])) {
                    $rumus_result .= " " . $uraian["uraian"];

                    if (!empty($uraian["nilai"])) {
                        $value_result .= " " . $uraian["nilai"];

                        $value_calculate .= $uraian["nilai"];
                    } else {
                        $value_result .= " (Belum ada nilai)";

                        $value_calculate .= "0";
                    }
                } else {
                    $rumus_result .= " " . $val;
                    $value_result .= " " . $val;

                    $value_calculate .= $val;
                }
            } else {
                $value_calculate .= $val;

                if ($val == "*") {
                    $val = "x";
                }

                $rumus_result .= " <span style='font-weight: 700; font-size: 1.3rem;'>" . $val . "</span>";
                $value_result .= " <span style='font-weight: 700; font-size: 1.3rem;'>" . $val . "</span>";
            }
        }

        $komposit["rumus_view"] = $rumus_result;
        $komposit["nilai_view"] = $value_result;

        $result = 0;
        if (strpos($value_result, 'Belum ada nilai') !== false) {
            $value_result = "$value_result = (Belum ada hasil)";
        } else {
            eval('$result = (' . $value_calculate . ');');
            $value_result = "$value_result = $result";
        }

        $komposit["value_result"] = $value_result;

        // $this->print_out_data($komposit);
        echo json_encode($komposit);
    }
}
