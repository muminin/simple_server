<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kegiatan extends WH_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->login_check();

        $this->load->model("skpd/Jenis_utama_model", "jen_model");
        $this->load->model("skpd/Kegiatan_model", "keg_model");
        $this->load->model("skpd/Program_model", "pro_model");
        $this->load->model("skpd/Master_model", "mas_model");
    }

    public function index()
    {
        // $get = $this->input->get();
        // $tahun = date("Y");
        // if (!empty($get["tahun"])) {
        //     $tahun = $get["tahun"];
        // }
        // $data["tahun"] = $tahun;

        $data["title"] = "Kegiatan";
        $data["_view"] = "skpd/kegiatan/index";
        $data["_js"] = "skpd/kegiatan/index_js";
        $data["_plugin"] = array(
            PLUG_NOTIFICATION => "1",
            PLUG_DATATABLE => "1",
            PLUG_SELECT2 => "1",
            PLUG_UPLOAD => "1",
        );

        $data["kegiatan"] = $this->keg_model->all_data($this->tahun_sess);

        // $this->print_out_data($data);
        $this->show_layout($data);
    }

    public function tambah()
    {
        $post = $this->input->post();
        // $this->print_out_data($post);
        if (!empty($post)) {
            $save = array(
                "tahun" => $post["tahun"],
                "id_bidang" => $post["bidang"],
                "kd_program" => $post["kode"],
                "nm_program" => $post["nama"],
                "is_deleted" => false,
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $this->user_id,
            );

            $result = $this->pro_model->save($save);
            echo $result;
        } else {
            $data["title"] = "Tambah Program";
            $data["_view"] = "skpd/program/tambah";
            $data["_js"] = "skpd/program/tambah_js";

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
        $get = $this->input->get();
        $post = $this->input->post();

        if (!empty($post)) {
            $save = array(
                "id_program" => $post["program"],

                "kode" => $post["kode"],
                "nama" => $post["nama"],
                "modified_date" => date("Y-m-d H:i:s"),
                "modified_by" => $this->user_id,
            );

            $result = $this->keg_model->update($save, $post["id"]);
            echo $result;
        } else {
            $data["title"] = "Ubah Kegiatan";
            $data["_view"] = "skpd/kegiatan/ubah";
            $data["_js"] = "skpd/kegiatan/ubah_js";
            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
                PLUG_SELECT2 => "1",
            );

            $data["kegiatan"] = $this->keg_model->get_kegiatan_byid($get["kegiatan"]);
            $data["program"] = $this->pro_model->get_program_bybidang($data["kegiatan"]["program_id"]);
            $data["jenis_utama"] = $this->jen_model->get_data_bytahun($data["kegiatan"]["jenis_tahun"]);
            $data["tahun_list"] = $this->mas_model->get_tahun();

            // $this->print_out_data($data);
            $this->show_layout($data);
        }
    }

    public function hapus()
    {
        $get = $this->input->get();
        echo $this->keg_model->delete($get["kegiatan"]);
    }

    public function get_program_bybidang()
    {
        $post = $this->input->post();
        $result = $this->pro_model->get_program_bybidang($post["bidang"]);
        // $this->print_out_data($this->db->last_query());

        echo json_encode($result);
    }

    public function tambah_data()
    {
        $this->load->model("skpd/Data_model", "dat_model");

        $get = $this->input->get();
        $post = $this->input->post();
        if (!empty($post)) {
            $save = array();
            $for_uniq = $post["jenis_id"] . $post["urusan_id"] . $post["bidang_id"] . $post["program_id"] . $post["kegiatan"];

            $uniq_count = 1;
            $uniq = $this->keg_model->get_last_uniq_bykegiatan($post["kegiatan"], $post["tahun"]);
            if (!empty($uniq)) {
                $uniq = explode("n", $uniq["uniq"]);
                $uniq_count = (int) $uniq[1] + 1;
            }

            foreach ($post["uraian"] as $key => $val) {
                if (!empty($post["uraian"][$key])) {

                    $save[] = array(
                        "id_kegiatan" => $post["kegiatan"],
                        "tahun" => $post["tahun"],

                        "uniq" => $for_uniq . "n" . $uniq_count,

                        "level" => 1,
                        "id_parent" => 0,
                        "uraian" => $post["uraian"][$key],
                        "satuan" => $post["satuan"][$key],
                        "nilai" => $post["nilai"][$key],

                        "is_deleted" => 0,
                        "created_date" => date("Y-m-d H:i:s"),
                        "created_by" => $this->user_id,
                    );

                    $uniq_count++;
                }
            }

            // $this->print_out_data($save);
            echo $this->keg_model->save_data_bykegiatan($save);
        } else {
            $data["kegiatan"] = $this->keg_model->get_kegiatan_byid($get["kegiatan"]);
            $data["data"] = $this->keg_model->get_data_bykegiatan($get["kegiatan"], 1);

            $data["title"] = "Tambah Data <small>(pada " . $data["kegiatan"]["nama"] . ")</small>";
            $data["_view"] = "skpd/kegiatan/tambah_data";
            $data["_js"] = "skpd/kegiatan/tambah_data_js";

            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
            );

            // $this->print_out_data($data);
            $this->show_layout($data);
        }
    }

    public function get_program_bytahun()
    {
        $post = $this->input->post();
        $result = $this->pro_model->get_program_bytahun($post["tahun"]);

        echo json_encode($result);
    }

    public function get_kegiatan_byprogram()
    {
        $post = $this->input->post();
        $result = $this->keg_model->get_kegiatan_byprogram($post["program"]);

        echo json_encode($result);
    }
}
