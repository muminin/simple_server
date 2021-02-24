<?php
ini_set('MAX_EXECUTION_TIME', '-1');
defined('BASEPATH') or exit('No direct script access allowed');

class Konfigurasi extends WH_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->login_check();

        $this->load->model("skpd/Bidang_model", "bid_model");
        $this->load->model("skpd/Konfigurasi_model", "kon_model");
    }

    public function index()
    {
        $data["title"] = "Konfigurasi OPD";
        $data["_view"] = "skpd/konfigurasi/index";
        $data["_js"] = "skpd/konfigurasi/index_js";
        $data["_plugin"] = array(
            PLUG_NOTIFICATION => "1",
            PLUG_DATATABLE => "1",
            PLUG_SELECT2 => "1",
        );

        $tahun = date("Y");
        $data["konfigurasi"] = $this->kon_model->all_data();
        $data["bidang"] = $this->bid_model->all_data($tahun);
        $data["group"] = $this->kon_model->all_opd();

        $this->show_layout($data);
    }

    public function save_bidang()
    {
        $post = $this->input->post();
        if (!empty($post["bidang"])) {
            $save = array();
            foreach ($post["bidang"] as $key => $val) {
                $save[] = array(
                    "id_group" => $post["group"],
                    "id_bidang" => $val,
                    "created_date" => date("Y-m-d H:i:s"),
                    "created_by" => $this->user_id,
                );
            }

            echo $this->kon_model->save_konfigurasi($save);
        } else {
            echo 2;
        }
    }

    public function bidang_bygroup($group)
    {
        $result = $this->kon_model->bidang_bygroup($group);
        echo json_encode($result);
    }

    public function copy_simple()
    {
        $this->load->model("skpd/Jenis_utama_model", "jen_model");

        $data["title"] = "Salin Data";
        $data["_view"] = "skpd/konfigurasi/salin";
        $data["_js"] = "skpd/konfigurasi/salin_js";
        $data["_plugin"] = array(
            PLUG_NOTIFICATION => "1",
            PLUG_DATATABLE => "1",
        );

        $tahun = date("Y");
        $data["jenis"] = $this->jen_model->all_data($tahun);
        $this->show_layout($data);
    }

    public function copy_simple_bytahun()
    {
        $post = $this->input->post();

        if ($this->kon_model->isjenis_existed($post["tahun_copy"])) {
            echo 2;
        } else {
            echo $this->kon_model->copy_simple_bytahun($post["jenis"], $post["tahun_copy"]);
        }
    }

    public function attach_to_pastdata()
    {
        $result = $this->kon_model->set_allgroup_tosee_data();
        echo $result;
    }

    public function copy_simple_bytahuntipe()
    {
        $get = $this->input->get();
        if ($get["tipe"] == HIE_DATA) {
            echo $this->kon_model->copy_simple_bytahuntipe($get["tahun_from"], $get["tahun_to"], $get["tipe"], $get["level"]);
        } else {
            echo $this->kon_model->copy_simple_bytahuntipe($get["tahun_from"], $get["tahun_to"], $get["tipe"]);
        }
    }
}
