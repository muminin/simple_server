<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Urusan extends WH_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->login_check();

        $this->load->model("skpd/Urusan_model", "uru_model");
    }

    public function index()
    {
        $data["title"] = "Urusan";
        $data["_view"] = "skpd/urusan/index";
        $data["_js"] = "skpd/urusan/index_js";
        $data["_plugin"] = array(
            PLUG_NOTIFICATION => "1",
            PLUG_DATATABLE => "1",
            PLUG_SELECT2 => "1",
        );

        // $get = $this->input->get();
        // $tahun = date("Y");
        // if (!empty($get["tahun"])) {
        //     $tahun = $get["tahun"];
        // }

        // $data["tahun"] = $tahun;
        $data["urusan"] = $this->uru_model->all_data($this->tahun_sess);
        $this->show_layout($data);
    }

    public function tambah()
    {
        $this->load->model("skpd/Jenis_utama_model", "jen_model");
        $post = $this->input->post();
        if (!empty($post)) {
            $save = array(
                "id_jenis_utama" => $post["jenis_utama"],
                "kd_urusan" => $post["kode"],
                "nm_urusan" => $post["nama"],
                "is_deleted" => false,
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $this->user_id,
            );

            $result = $this->uru_model->save($save);
            echo $result;
        } else {
            $data["title"] = "Tambah Urusan";
            $data["_view"] = "skpd/urusan/tambah";
            $data["_js"] = "skpd/urusan/tambah_js";

            $data["tahun"] = date("Y");
            $data["jenis_utama"] = $this->jen_model->get_data_bytahun($data["tahun"]);
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
        $this->load->model("skpd/Jenis_utama_model", "jen_model");
        $get = $this->input->get();
        $post = $this->input->post();

        if (!empty($post)) {
            $save = array(
                "id_jenis_utama" => $post["jenis_utama"],
                "kd_urusan" => $post["kode"],
                "nm_urusan" => $post["nama"],

                "modified_date" => date("Y-m-d H:i:s"),
                "modified_by" => $this->user_id,
            );

            $result = $this->uru_model->update($save, $post["id"]);
            echo $result;
        } else {
            $data["title"] = "Ubah Urusan";
            $data["_view"] = "skpd/urusan/ubah";
            $data["_js"] = "skpd/urusan/ubah_js";
            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
                PLUG_SELECT2 => "1",
            );

            $data["urusan"] = $this->uru_model->get_data_byid($get["id"]);
            $data["jenis_utama"] = $this->jen_model->get_data_bytahun($data["urusan"]["jenis_tahun"]);

            // $this->print_out_data($data);
            $this->show_layout($data);
        }
    }

    public function hapus()
    {
        $get = $this->input->get();
        echo $this->uru_model->delete($get["id"]);
    }

    public function get_urusan_byjenis()
    {
        $post = $this->input->post();
        $result = $this->uru_model->get_data_byjenis($post["jenis"]);

        echo json_encode($result);
    }

    public function tambah_bidang()
    {
        $this->load->model("skpd/Bidang_model", "bid_model");

        $get = $this->input->get();
        $post = $this->input->post();
        if (!empty($post)) {
            $this->db->trans_begin();
            $save = array();
            foreach ($post["kode"] as $key => $val) {
                $save[] = array(
                    "id_urusan" => $post["id"],
                    "kd_bidang" => $post["kode"][$key],
                    "nm_bidang" => $post["nama"][$key],
                    "is_deleted" => false,
                    "created_date" => date("Y-m-d H:i:s"),
                    "created_by" => $this->user_id,
                );
            }

            $this->bid_model->save_multiple($save);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo 0;
            } else {
                $this->db->trans_commit();
                echo 1;
            }
        } else {
            $data["urusan"] = $this->uru_model->get_data_byid($get["id"]);
            $data["bidang"] = $this->bid_model->get_data_byurusan($get["id"]);

            $data["title"] = "Tambah Bidang <small>(pada " . $data["urusan"]["nm_urusan"] . ")</small>";
            $data["_view"] = "skpd/urusan/tambah_bidang";
            $data["_js"] = "skpd/urusan/tambah_bidang_js";

            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
            );

            $this->show_layout($data);
        }
    }
}
