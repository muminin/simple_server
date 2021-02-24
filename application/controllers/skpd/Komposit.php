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
        $data["tahun"] =  $this->mas_model->get_tahun();
        $data["tahun_selected"] = $tahun;

        $data["title"] = "Komposit";
        $data["_view"] = "skpd/komposit/index";
        $data["_js"] = "skpd/komposit/index_js";
        $data["_plugin"] = array(
            PLUG_VALIDATION => "1",
            PLUG_NOTIFICATION => "1",
            PLUG_DATATABLE => "1",
            PLUG_SELECT2 => "1",
        );

        $this->show_layout($data);
    }

    public function tambah()
    {
        $post = $this->input->post();
        if (!empty($post)) {
            $save = array(
                "id_bidang" => $post["bidang"],
                "uraian" => $post["uraian"],

                "keterangan" => $post["keterangan"],
                "satuan" => $post["satuan"],
                // "nilai" => $post["nilai"],

                "is_deleted" => false,
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $this->user_id,
            );

            $result = $this->kom_model->save($save);
            echo $result;
        } else {
            $data["title"] = "Tambah Komposit";
            $data["_view"] = "skpd/komposit/tambah";
            $data["_js"] = "skpd/komposit/tambah_js";

            $data["tahun"] =  $this->mas_model->get_tahun();
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
                "id_bidang" => $post["bidang"],
                "uraian" => $post["uraian"],

                "keterangan" => $post["keterangan"],
                "satuan" => $post["satuan"],
                // "nilai" => $post["nilai"],

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

            $data["komposit"] = $komposit;
            $data["tahun"] =  $this->mas_model->get_tahun();

            $this->show_layout($data);
        }
    }

    public function hapus()
    {
        $get = $this->input->get();
        echo $this->kom_model->delete($get["komposit"]);
    }

    public function isi_nilai()
    {
        $post = $this->input->post();
        echo $this->kom_model->isi_nilai($post["komposit"], $post["nilai"]);
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

    public function copy_totahun()
    {
        $get = $this->input->get();
        echo $this->kom_model->copy_totahun($get["from"], $get["to"]);
    }




    // TEST
    public function formula()
    {
        $get = $this->input->get();
        $result = $this->kom_model->formula($get["formula"], $get["tahun"]);

        $this->print_out_data($result);
    }

    public function komposit_bidang()
    {
        $get = $this->input->get();

        $kode = "";
        if (isset($get["urusan"]) and isset($get["bidang"])) {
            $kode = $get["urusan"] . "." . $get["bidang"];
        }

        $result = $this->kom_model->komposit_bidang($get["tahun"], $kode);

        $this->print_out_data($result);
    }
}
