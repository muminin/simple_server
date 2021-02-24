<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Program extends WH_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->login_check();

        $this->load->model("skpd/Program_model", "pro_model");
        $this->load->model("skpd/Kegiatan_model", "keg_model");
    }

    public function index()
    {
        // $get = $this->input->get();
        // $tahun = date("Y");
        // if (!empty($get["tahun"])) {
        //     $tahun = $get["tahun"];
        // }
        // $data["tahun"] = $tahun;

        $data["title"] = "Program";
        $data["_view"] = "skpd/program/index";
        $data["_js"] = "skpd/program/index_js";
        $data["_plugin"] = array(
            PLUG_NOTIFICATION => "1",
            PLUG_DATATABLE => "1",
            PLUG_SELECT2 => "1",
        );

        $data["program"] = $this->pro_model->all_data($this->tahun_sess);
        $this->show_layout($data);
    }

    public function tambah()
    {
        $this->load->model("skpd/Jenis_utama_model", "jen_model");
        $post = $this->input->post();

        if (!empty($post)) {
            $save = array(
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
        $get = $this->input->get();
        $post = $this->input->post();

        if (!empty($post)) {
            $save = array(
                "id_bidang" => $post["bidang"],
                "kd_program" => $post["kode"],
                "nm_program" => $post["nama"],
                "modified_date" => date("Y-m-d H:i:s"),
                "modified_by" => $this->user_id,
            );

            $result = $this->pro_model->update($save, $post["id"]);
            echo $result;
        } else {
            $data["title"] = "Ubah Program";
            $data["_view"] = "skpd/program/ubah";
            $data["_js"] = "skpd/program/ubah_js";
            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
                PLUG_SELECT2 => "1",
            );

            $data["program"] = $this->pro_model->get_data_byid($get["id"]);
            $data["jenis_utama"] = $this->jen_model->get_data_bytahun($data["program"]["jenis_tahun"]);

            // $this->print_out_data($data);
            $this->show_layout($data);
        }
    }

    public function hapus()
    {
        $get = $this->input->get();
        echo $this->pro_model->delete($get["id"]);
    }

    public function get_program_bybidang()
    {
        $post = $this->input->post();
        $result = $this->pro_model->get_program_bybidang($post["bidang"]);

        echo json_encode($result);
    }

    public function tambah_data()
    {
        $get = $this->input->get();
        $post = $this->input->post();
        if (!empty($post)) {
            $save = array();
            foreach ($post["kode"] as $key => $val) {
                if (!empty($post["kode"][$key])) {
                    $save[] = array(
                        "program" => $post["program"],
                        "kode" => $post["kode"][$key],
                        "nama" => $post["nama"][$key],

                        "is_deleted" => 0,
                        "created_date" => date("Y-m-d H:i:s"),
                        "created_by" => $this->user_id,
                    );
                }
            }

            $this->pro_model->save_kegiatan_byprogram($save);
        } else {
            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
            );

            $data["program"] = $this->pro_model->get_data_byid($get["program"]);
            $data["data"] = $this->keg_model->get_kegiatan_byprogram($get["program"]);

            $data["title"] = "Tambah Kegiatan <small>(pada " . $data["program"]["nm_program"] . ")</small>";
            $data["_view"] = "skpd/program/tambah_data";
            $data["_js"] = "skpd/program/tambah_data_js";

            $this->show_layout($data);
        }
    }

    public function get_program_bytahun()
    {
        $post = $this->input->post();
        $result = $this->pro_model->get_program_bytahun($post["tahun"]);

        echo json_encode($result);
    }

    // public function tambah_data_old()
    // {
    //     $get = $this->input->get();
    //     $post = $this->input->post();
    //     if (!empty($post)) {
    //         $this->print_out_data($post);

    //         $this->db->trans_begin();
    //         $save = array();
    //         foreach ($post["uraian"] as $key => $val) {
    //             if (!empty($post["uraian"][$key])) {
    //                 $save[] = array(
    //                     "id_program" => $post["id"],
    //                     "tahun" => $post["tahun"],
    //                     "level" => 1,
    //                     "id_parent" => 0,
    //                     "uraian" => $post["uraian"][$key],
    //                     "satuan" => $post["satuan"][$key],
    //                     "nilai" => $post["nilai"][$key],
    //                     "is_deleted" => false,
    //                     "created_date" => date("Y-m-d H:i:s"),
    //                     "created_by" => $this->user_id,
    //                 );
    //             }
    //         }

    //         $this->dat_model->save_multiple($save);

    //         if ($this->db->trans_status() === FALSE) {
    //             $this->db->trans_rollback();
    //             echo 0;
    //         } else {
    //             $this->db->trans_commit();
    //             echo 1;
    //         }
    //     } else {
    //         $data["_plugin"] = array(
    //             PLUG_VALIDATION => "1",
    //             PLUG_NOTIFICATION => "1",
    //         );

    //         // $data["data"] = $this->dat_model->get_data_byprogram($get["program"]);
    //         $data["program"] = $this->pro_model->get_data_byid($get["program"]);
    //         $data["data"] = $this->keg_model->get_kegiatan_byprogram($get["program"]);

    //         $data["title"] = "Tambah Kegiatan <small>(pada " . $data["program"]["nm_program"] . ")</small>";
    //         $data["_view"] = "skpd/program/tambah_data";
    //         $data["_js"] = "skpd/program/tambah_data_js";

    //         $this->show_layout($data);
    //     }
    // }
}
