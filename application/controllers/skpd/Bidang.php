<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bidang extends WH_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->login_check();

        $this->load->model("skpd/Bidang_model", "bid_model");
    }

    public function index()
    {
        $data["title"] = "Bidang";
        $data["_view"] = "skpd/bidang/index";
        $data["_js"] = "skpd/bidang/index_js";
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
        $data["bidang"] = $this->bid_model->all_data($this->tahun_sess);
        $this->show_layout($data);
    }

    public function tambah()
    {
        $this->load->model("skpd/Jenis_utama_model", "jen_model");
        $post = $this->input->post();
        if (!empty($post)) {
            $save = array(
                "id_urusan" => $post["urusan"],
                "kd_bidang" => $post["kode"],
                "nm_bidang" => $post["nama"],
                "is_deleted" => false,
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $this->user_id,
            );

            $result = $this->bid_model->save($save);
            echo $result;
        } else {
            $data["title"] = "Tambah Bidang";
            $data["_view"] = "skpd/bidang/tambah";
            $data["_js"] = "skpd/bidang/tambah_js";

            $data["tahun"] = date("Y");
            $data["jenis_utama"] = $this->jen_model->get_data_bytahun($data["jenis_tahun"]);
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
        $this->load->model("skpd/Urusan_model", "jen_model");
        $get = $this->input->get();
        $post = $this->input->post();

        if (!empty($post)) {
            $save = array(
                "id_urusan" => $post["urusan"],
                "kd_bidang" => $post["kode"],
                "nm_bidang" => $post["nama"],
                "modified_date" => date("Y-m-d H:i:s"),
                "modified_by" => $this->user_id,
            );

            $result = $this->bid_model->update($save, $post["id"]);
            echo $result;
        } else {
            $data["title"] = "Ubah Urusan";
            $data["_view"] = "skpd/bidang/ubah";
            $data["_js"] = "skpd/bidang/ubah_js";
            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
                PLUG_SELECT2 => "1",
            );

            $data["bidang"] = $this->bid_model->get_data_byid($get["id"]);
            $data["jenis_utama"] = $this->jen_model->get_data_bytahun($data["bidang"]["jenis_tahun"]);

            // $this->print_out_data($data);
            $this->show_layout($data);
        }
    }

    public function hapus()
    {
        $get = $this->input->get();
        echo $this->bid_model->delete($get["id"]);
    }

    public function get_bidang_byurusan()
    {
        $post = $this->input->post();
        $result = $this->bid_model->get_data_byurusan($post["urusan"]);

        echo json_encode($result);
    }

    public function tambah_program()
    {
        $this->load->model("skpd/Program_model", "pro_model");

        $get = $this->input->get();
        $post = $this->input->post();
        if (!empty($post)) {
            $this->db->trans_begin();
            $save = array();
            foreach ($post["kode"] as $key => $val) {
                $save[] = array(
                    "id_bidang" => $post["id"],
                    "kd_program" => $post["kode"][$key],
                    "nm_program" => $post["nama"][$key],
                    "is_deleted" => false,
                    "created_date" => date("Y-m-d H:i:s"),
                    "created_by" => $this->user_id,
                );
            }

            $this->pro_model->save_multiple($save);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo 0;
            } else {
                $this->db->trans_commit();
                echo 1;
            }
        } else {
            $data["bidang"] = $this->bid_model->get_data_byid($get["id"]);
            $data["program"] = $this->pro_model->get_program_bybidang($get["id"]);

            $data["title"] = "Tambah Program <small>(pada " . $data["bidang"]["nm_bidang"] . ")</small>";
            $data["_view"] = "skpd/bidang/tambah_program";
            $data["_js"] = "skpd/bidang/tambah_program_js";

            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
            );

            $this->show_layout($data);
        }
    }
}
