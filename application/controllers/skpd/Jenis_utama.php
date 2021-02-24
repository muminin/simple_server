<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jenis_Utama extends WH_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->login_check();

        $this->load->model("skpd/Jenis_utama_model", "jen_model");
    }

    public function index()
    {
        $data["title"] = "Jenis Utama";
        $data["_view"] = "skpd/jenis_utama/index";
        $data["_js"] = "skpd/jenis_utama/index_js";
        $data["_plugin"] = array(
            PLUG_NOTIFICATION => "1",
            PLUG_DATATABLE => "1",
        );

        $data["jenis_utama"] = $this->jen_model->all_data($this->tahun_sess);

        // $this->print_out_data($this->tahun_sess);
        $this->show_layout($data);
    }

    public function tambah()
    {
        $post = $this->input->post();
        if (!empty($post)) {
            $save = array(
                "tahun" => $post["tahun"],
                "nama_jenis_utama" => $post["nama"],
                "is_deleted" => false,
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $this->user_id,
            );

            $result = $this->jen_model->save($save);
            echo $result;
        } else {
            $data["title"] = "Jenis Utama";
            $data["_view"] = "skpd/jenis_utama/tambah";
            $data["_js"] = "skpd/jenis_utama/tambah_js";

            $data["tahun"] = date("Y");
            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
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
                "nama_jenis_utama" => $post["nama"],
                "modified_date" => date("Y-m-d H:i:s"),
                "modified_by" => $this->user_id,
            );

            $result = $this->jen_model->update($save, $post["id"]);
            echo $result;
        } else {
            $data["title"] = "Jenis Utama";
            $data["_view"] = "skpd/jenis_utama/ubah";
            $data["_js"] = "skpd/jenis_utama/ubah_js";
            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
            );

            $data["jenis_utama"] = $this->jen_model->get_data_byid($get["id"]);
            $this->show_layout($data);
        }
    }

    public function hapus()
    {
        $get = $this->input->get();
        echo $this->jen_model->delete($get["id"]);
    }

    public function get_data_bytahun()
    {
        $post = $this->input->post();
        $result = $this->jen_model->get_data_bytahun($post["tahun"]);
        echo json_encode($result);
    }

    public function tambah_urusan()
    {
        $this->load->model("skpd/Urusan_model", "uru_model");

        $get = $this->input->get();
        $post = $this->input->post();
        if (!empty($post)) {

            $this->db->trans_begin();
            $save = array();
            foreach ($post["kode"] as $key => $val) {
                $save[] = array(
                    "id_jenis_utama" => $post["id"],
                    "kd_urusan" => $post["kode"][$key],
                    "nm_urusan" => $post["nama"][$key],
                    "is_deleted" => false,
                    "created_date" => date("Y-m-d H:i:s"),
                    "created_by" => $this->user_id,
                );
            }

            $this->uru_model->save_multiple($save);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo 0;
            } else {
                $this->db->trans_commit();
                echo 1;
            }
        } else {
            $data["jenis_utama"] = $this->jen_model->get_data_byid($get["id"]);
            $data["urusan"] = $this->uru_model->get_data_byjenis($get["id"]);

            $data["title"] = "Tambah Urusan <small>(pada " . $data["jenis_utama"]["nama_jenis_utama"] . ")</small>";
            $data["_view"] = "skpd/jenis_utama/tambah_urusan";
            $data["_js"] = "skpd/jenis_utama/tambah_urusan_js";

            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
            );

            $this->show_layout($data);
        }
    }
}
