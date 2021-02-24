<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data extends WH_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->login_check();

        $this->load->model("skpd/Jenis_utama_model", "jen_model");
        $this->load->model("skpd/Data_model", "dat_model");
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

        $tahun = $this->tahun_sess;

        if (!empty($get)) {
            if (!empty($get["kegiatan"])) {
                $data["data"] = $this->dat_model->all_data($tahun, 0, 1, $get["kegiatan"]);
            } elseif (!empty($get["program"])) {
                $data["data"] = $this->dat_model->all_data($tahun, 0, 1, 0, $get["program"]);
            } elseif (!empty($get["bidang"])) {
                $data["data"] = $this->dat_model->all_data($tahun, 0, 1, 0, 0, $get["bidang"]);
            } elseif (!empty($get["urusan"])) {
                $data["data"] = $this->dat_model->all_data($tahun, 0, 1, 0, 0, 0, $get["urusan"]);
            } elseif (!empty($get["id_parent"]) && !empty($get["level"])) {
                $data["data"] = $this->dat_model->all_data($tahun, $get["id_parent"], $get["level"]);
            } else {
                $data["data"] = $this->dat_model->all_data($tahun);
            }
        } else {
            $data["data"] = $this->dat_model->all_data($tahun);
        }

        $kegiatan = $this->keg_model->all_data($tahun);

        $data["kegiatan"] = $kegiatan;
        $data["id_kegiatan"] = !empty($get["kegiatan"]) ? $get["kegiatan"] : 0;
        $data["level"] = !empty($get["level"]) ? $get["level"] : 1;

        $data["title"] = "Data";
        $data["_view"] = "skpd/data/index";
        $data["_js"] = "skpd/data/index_js";
        $data["_plugin"] = array(
            PLUG_NOTIFICATION => "1",
            PLUG_DATATABLE => "1",
            PLUG_SELECT2 => "1",
            PLUG_UPLOAD => "1",
        );

        // $this->print_out_data($data["data"]);
        $this->show_layout($data);
    }

    public function ubah()
    {
        $get = $this->input->get();
        $post = $this->input->post();
        if (!empty($post)) {
            // $this->print_out_data($post);

            if ($this->session->userdata("group") == 1) {
                if (isset($get["level"])) {
                    $save = array(
                        "id_parent" => $post["parent"],
                        "uraian" => $post["uraian"],
                        "satuan" => $post["satuan"],
                        "nilai" => str_replace(',', '.', $post["nilai"]),
                        "modified_date" => date("Y-m-d H:i:s"),
                        "modified_by" => $this->user_id,
                    );
                } else {
                    $last_uniq = $post["last_jenis"] . $post["last_urusan"] . $post["last_bidang"] . $post["last_program"] . $post["last_kegiatan"];
                    $for_uniq = $post["jenis_utama"] . $post["urusan"] . $post["bidang"] . $post["program"] . $post["kegiatan"];

                    $uniq = $post["uniq"];
                    if ($last_uniq != $for_uniq) {
                        $uniq_count = 1;
                        $uniq = $this->keg_model->get_last_uniq_bykegiatan($post["kegiatan"], $post["tahun"]);
                        if (!empty($uniq)) {
                            $uniq = explode("n", $uniq["uniq"]);
                            $uniq_count = (int) $uniq[1] + 1;
                        }

                        $uniq = $for_uniq . "n" . $uniq_count;
                    }

                    $save = array(
                        // "id_program" => $post["program"],
                        "id_kegiatan" => $post["kegiatan"],
                        "uniq" => $uniq,

                        "uraian" => $post["uraian"],
                        "satuan" => $post["satuan"],
                        "nilai" => str_replace(',', '.', $post["nilai"]),
                        "modified_date" => date("Y-m-d H:i:s"),
                        "modified_by" => $this->user_id,
                    );
                }
            } else {
                if (isset($get["level"])) {
                    $save = array(
                        "id_parent" => $post["parent"],
                        "uraian" => $post["uraian"],
                        "satuan" => $post["satuan"],
                        "nilai" => str_replace(',', '.', $post["nilai"]),
                        "modified_date" => date("Y-m-d H:i:s"),
                        "modified_by" => $this->user_id,
                    );
                } else {
                    $save = array(
                        "id_kegiatan" => $post["last_kegiatan"],
                        "uniq" => $post["uniq"],

                        "uraian" => $post["uraian"],
                        "satuan" => $post["satuan"],
                        "nilai" => str_replace(',', '.', $post["nilai"]),

                        "modified_date" => date("Y-m-d H:i:s"),
                        "modified_by" => $this->user_id,
                    );
                }
            }

            // $this->print_out_data($save);
            $result = $this->dat_model->update($save, $post["id"]);
            if (!empty($post["program"])) {
                echo $post["program"];
            } else {
                echo $result;
            }
        } else {
            $data["title"] = "Ubah Data";
            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
                PLUG_SELECT2 => "1",
            );

            if (isset($get["level"])) {
                $data["_view"] = "skpd/data/ubah_level";
                $data["_js"] = "skpd/data/ubah_level_js";

                $data["data"] = $this->dat_model->get_data_byid($get["id"]);
                $parent = $this->dat_model->get_data_byid($data["data"]["id_parent"]);
                $data["parent_list"] = $this->dat_model->get_parent_list($parent["level"], $parent["id_parent"]);
            } else {
                $data["_view"] = "skpd/data/ubah";
                $data["_js"] = "skpd/data/ubah_js";

                $data["data"] = $this->dat_model->get_data_byid($get["id"]);
                $data["jenis_utama"] = $this->jen_model->get_data_bytahun($data["data"]["jenis_tahun"]);
            }

            $data["tahun_list"] = $this->mas_model->get_tahun();

            // $this->print_out_data($data);
            $this->show_layout($data);
        }
    }

    public function tambah_data()
    {
        $get = $this->input->get();
        $post = $this->input->post();
        if (!empty($post)) {

            if (!empty($post["uraian_c"])) {
                $save = array();
                foreach ($post["uraian_c"] as $key => $val) {
                    if ($val != "") {
                        $save[] = array(
                            "id_kegiatan" => $post["kegiatan"],
                            "tahun" => $post["tahun"],
                            "level" => $post["level"] + 1,
                            "id_parent" => $post["id_parent"],
                            "uniq" => $post["uniq"],

                            "uraian" => $post["uraian_c"][$key],
                            "satuan" => $post["satuan"][$key],
                            "nilai" => str_replace(',', '.', $post["nilai"][$key]),

                            "is_deleted" => false,
                            "created_date" => date("Y-m-d H:i:s"),
                            "created_by" => $this->user_id,
                        );
                    }
                }

                // $this->print_out_data($save);
                $result = $this->dat_model->save_multiple($save);
                if ($result) {
                    echo $post["kegiatan"];
                } else {
                    echo 0;
                }
            } else {
                echo 1;
            }
        } else {
            $data["title"] = "Data";
            $data["_view"] = "skpd/data/tambah_data";
            $data["_js"] = "skpd/data/tambah_data_js";

            $data["_plugin"] = array(
                PLUG_VALIDATION => "1",
                PLUG_NOTIFICATION => "1",
            );

            $data["data"] = $this->dat_model->get_data_byid($get["id"]);
            $data["existed"] = $this->dat_model->get_data_byparent($get["id"]);
            $this->show_layout($data);
        }
    }

    public function mapping_data()
    {
        $get = $this->input->get();
        $post = $this->input->post();
        if (!empty($post)) {
            echo $this->dat_model->insert_data_lama($post);
        } else {
            $data["title"] = "Mapping Data Lama ke Kegiatan";
            $data["_view"] = "skpd/data/mapping";
            $data["_js"] = "skpd/data/mapping_js";

            $result = $this->dat_model->data_lama($get["kegiatan"]);
            $data["data"] = $result["data"];
            $data["kegiatan"] = $result["kegiatan"];
            $data["selected_data"] = $result["selected_data"];

            $data["_plugin"] = array(
                PLUG_DATATABLE => "1",
                PLUG_NOTIFICATION => "1",
            );

            // $this->print_out_data($data);
            $this->show_layout($data);
        }
    }

    public function hapus()
    {
        $get = $this->input->get();
        echo $this->dat_model->delete($get["id"]);
    }

    // public function index_old()
    // {
    //     $get = $this->input->get();
    //     $tahun = date("Y");
    //     if (!empty($get["tahun"])) {
    //         $tahun = $get["tahun"];
    //     }

    //     if (!empty($get)) {
    //         if (!empty($get["program"])) {
    //             $data["data"] = $this->dat_model->all_data($tahun, 0, 1, $get["program"]);
    //         } elseif (!empty($get["bidang"])) {
    //             $data["data"] = $this->dat_model->all_data($tahun, 0, 1, 0, $get["bidang"]);
    //         } elseif (!empty($get["urusan"])) {
    //             $data["data"] = $this->dat_model->all_data($tahun, 0, 1, 0, 0, $get["urusan"]);
    //         } elseif (!empty($get["id_parent"]) && !empty($get["level"])) {
    //             $data["data"] = $this->dat_model->all_data($tahun, $get["id_parent"], $get["level"]);
    //         } else {
    //             $data["data"] = $this->dat_model->all_data($tahun);
    //         }
    //     } else {
    //         $data["data"] = $this->dat_model->all_data($tahun);
    //     }


    //     $program = $this->pro_model->all_data($tahun);
    //     // $this->print_out_data($data);

    //     // foreach ($data["data"] as $key => $val) {
    //     //     $data["data"][$key]["jenis_utama"] = "(" . $val["tahun"] . ") " . $val["nama_jenis_utama"] . " - ";
    //     //     $data["data"][$key]["jenis_utama"] .= $val["nm_urusan"] . " - " . $val["nm_bidang"] . " - " . $val["nm_program"];
    //     // }

    //     $data["program"] = $program;
    //     $data["id_program"] = !empty($get["program"]) ? $get["program"] : 0;
    //     $data["level"] = !empty($get["level"]) ? $get["level"] : 1;
    //     $data["tahun"] = $tahun;

    //     $data["title"] = "Data";
    //     $data["_view"] = "skpd/data/index";
    //     $data["_js"] = "skpd/data/index_js";
    //     $data["_plugin"] = array(
    //         PLUG_NOTIFICATION => "1",
    //         PLUG_DATATABLE => "1",
    //         PLUG_SELECT2 => "1",
    //     );

    //     // $this->print_out_data($data);
    //     $this->show_layout($data);
    // }

    // public function ubah_old()
    // {
    //     $this->load->model("skpd/Jenis_utama_model", "jen_model");
    //     $get = $this->input->get();
    //     $post = $this->input->post();

    //     if (!empty($post)) {
    //         if (isset($get["level"])) {
    //             $save = array(
    //                 "id_parent" => $post["parent"],
    //                 "uraian" => $post["uraian"],
    //                 "satuan" => $post["satuan"],
    //                 "nilai" => str_replace(',', '.', $post["nilai"]),
    //                 "modified_date" => date("Y-m-d H:i:s"),
    //                 "modified_by" => $this->user_id,
    //             );
    //         } else {
    //             $save = array(
    //                 "id_program" => $post["program"],
    //                 "uraian" => $post["uraian"],
    //                 "satuan" => $post["satuan"],
    //                 "nilai" => str_replace(',', '.', $post["nilai"]),
    //                 "modified_date" => date("Y-m-d H:i:s"),
    //                 "modified_by" => $this->user_id,
    //             );
    //         }

    //         $result = $this->dat_model->update($save, $post["id"]);
    //         if (!empty($post["id_program"])) {
    //             echo $post["id_program"];
    //         } else {
    //             echo $result;
    //         }
    //     } else {
    //         $data["title"] = "Ubah Data";
    //         $data["_plugin"] = array(
    //             PLUG_VALIDATION => "1",
    //             PLUG_NOTIFICATION => "1",
    //             PLUG_SELECT2 => "1",
    //         );

    //         if (isset($get["level"])) {
    //             $data["_view"] = "skpd/data/ubah_level";
    //             $data["_js"] = "skpd/data/ubah_level_js";

    //             $data["data"] = $this->dat_model->get_data_byid($get["id"]);
    //             $parent = $this->dat_model->get_data_byid($data["data"]["id_parent"]);
    //             $data["parent_list"] = $this->dat_model->get_parent_list($parent["level"], $parent["id_parent"]);
    //         } else {
    //             $data["_view"] = "skpd/data/ubah";
    //             $data["_js"] = "skpd/data/ubah_js";

    //             $data["data"] = $this->dat_model->get_data_byid($get["id"]);
    //             $data["jenis_utama"] = $this->jen_model->get_data_bytahun($data["data"]["jenis_tahun"]);
    //         }

    //         // $this->print_out_data($data);
    //         $this->show_layout($data);
    //     }
    // }

    // public function hapus_old()
    // {
    //     $get = $this->input->get();
    //     echo $this->dat_model->delete($get["id"]);
    // }

    // public function tambah_data_old()
    // {
    //     $get = $this->input->get();
    //     $post = $this->input->post();
    //     if (!empty($post)) {
    //         if (!empty($post["uraian_c"])) {
    //             $this->load->model("skpd/Program_model", "pro_model");

    //             $this->db->trans_begin();
    //             $save = array();
    //             foreach ($post["uraian_c"] as $key => $val) {
    //                 if ($val != "") {
    //                     $save[] = array(
    //                         "id_program" => $post["id_program"],
    //                         "level" => $post["level"] + 1,
    //                         "id_parent" => $post["id_parent"],
    //                         "uraian" => $post["uraian_c"][$key],
    //                         "satuan" => $post["satuan"][$key],
    //                         "nilai" => str_replace(',', '.', $post["nilai"][$key]),
    //                         "is_deleted" => false,
    //                         "created_date" => date("Y-m-d H:i:s"),
    //                         "created_by" => $this->user_id,
    //                     );
    //                 }
    //             }

    //             $this->dat_model->save_multiple($save);

    //             if ($this->db->trans_status() === FALSE) {
    //                 $this->db->trans_rollback();
    //                 echo 0;
    //             } else {
    //                 $this->db->trans_commit();

    //                 // echo 1;
    //                 echo $post["id_program"];
    //             }
    //         } else {
    //             echo 1;
    //         }
    //     } else {
    //         $data["title"] = "Data";
    //         $data["_view"] = "skpd/data/tambah_data";
    //         $data["_js"] = "skpd/data/tambah_data_js";

    //         $data["_plugin"] = array(
    //             PLUG_VALIDATION => "1",
    //             PLUG_NOTIFICATION => "1",
    //         );

    //         $data["data"] = $this->dat_model->get_data_byid($get["id"]);
    //         $data["existed"] = $this->dat_model->get_data_byparent($get["id"]);
    //         $this->show_layout($data);
    //     }
    // }

    // public function tambah()
    // {
    //     $this->load->model("skpd/Jenis_utama_model", "jen_model");
    //     $post = $this->input->post();
    //     if (!empty($post)) {
    //         $save = array(
    //             "tahun" => $post["tahun"],
    //             "id_program" => $post["program"],

    //             "created_date" => date("Y-m-d H:i:s"),
    //             "created_by" => $this->user_id,
    //         );

    //         $result = $this->pro_model->save($save);
    //         echo $result;
    //     } else {
    //         $data["title"] = "Tambah Data";
    //         $data["_view"] = "skpd/data/tambah";
    //         $data["_js"] = "skpd/data/tambah_js";

    //         $data["tahun"] = date("Y");
    //         $data["jenis_utama"] = $this->jen_model->get_data_bytahun($data["tahun"]);
    //         $data["_plugin"] = array(
    //             PLUG_VALIDATION => "1",
    //             PLUG_NOTIFICATION => "1",
    //             PLUG_SELECT2 => "1",
    //         );

    //         $this->show_layout($data);
    //     }
    // }
}
