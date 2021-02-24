<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends WH_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model("skpd/Konfigurasi_model", "kon_model");
    }

    public function index()
    {
        $get = $this->input->get();
        $this->login_check();

        $data["title"] = "Dashboard";
        $data["_view"] = "skpd/dashboard/main";
        $data["_js"] = "skpd/dashboard/main_js";

        $group = $this->kon_model->get_user_group($this->user_id);
        $this->session->set_userdata("group", $group);

        $bidangs = $this->kon_model->get_user_bidang($this->user_id);
        if (!empty($bidangs)) {
            $bidang = array();
            foreach ($bidangs as $key => $val) {
                $bidang[] = array(
                    "id_bidang" => $val["id_bidang"],
                );
            }

            $this->session->set_userdata("bidang", $bidang);
        }

        $tahun = !empty($get["tahun"]) ? $get["tahun"] : $this->session->userdata("tahun_sess");
        $this->session->set_userdata("tahun_sess", $tahun);

        $counter = $this->kon_model->get_counter($tahun);
        $tahun_list = $this->mas_model->get_tahun();

        $diagram = array();
        foreach ($counter as $val) {
            $warna = $this->random_color_string();
            $diagram[] = array(
                "nama" => $val["group_name"],
                "persentase" => number_format($val["persentase"], 2),
                "jumlah_data" => $val["jumlah_data"],
                "jumlah_input" => $val["jumlah_input"],

                "warna" => $warna . "0.5)",
                "border" => $warna . "1)",
            );
        }

        $data["diagram"] = $diagram;
        $data["tahun_list"] = $tahun_list;
        $data["_plugin"] = array(
            PLUG_NOTIFICATION => "1",
        );

        // $this->print_out_data($data);
        $this->show_layout($data);
    }

    public function counter_data()
    {
        $get = $this->input->get();

        // if (empty($this->kon_model->check_counter_bytahun($get["tahun"]))) {
        $group = $this->kon_model->get_opd_inbidang();
        $result = array();
        foreach ($group as $val) {
            $group_bidang = $this->kon_model->get_bidang_bygroup($val["id_group"], $get["tahun"]);
            $nama_group = $val["name"];
            if ($val["id_group"] == 1020201 || $val["id_group"] == 1020202) {
                $nama_group = $val["description"];
            }

            foreach ($group_bidang as $gro_bid) {
                $counter = $this->kon_model->get_counter_data_bybidangtahun($gro_bid["id_bidang"], $get["tahun"]);
                $result[] = array(
                    "id_group" => $val["id_group"],
                    "tahun" => $get["tahun"],
                    "nm_group" => $nama_group,
                    "id_bidang" => $gro_bid["id_bidang"],
                    "nm_bidang" => $gro_bid["nm_bidang"],

                    "jumlah_data" => $counter["jumlah_data"],
                    "jumlah_input" => $counter["jumlah_input"],
                );
            }
        }

        // $this->print_out_data($result);

        $save = array();
        foreach ($result as $val) {
            if (empty($save)) {
                $save[] = $val;
            } else {
                if (array_search($val["id_group"], array_column($save, "id_group")) !== false) {
                    foreach ($save as $ksav => $vsav) {
                        if ($vsav["id_group"] == $val["id_group"]) {
                            $save[$ksav]["jumlah_data"] += $val["jumlah_data"];
                            $save[$ksav]["jumlah_input"] += $val["jumlah_input"];
                        }
                    }
                } else {
                    $save[] = $val;
                }
            }
        }

        echo $this->kon_model->save_counter($save);

        // }else{
        // echo "exist";
        // }
    }

    public function login()
    {
        if ($this->ion_auth->logged_in()) {
            // sudah login, redirect ke halaman dashboard
            redirect('Dashboard', 'refresh');
        }

        $data["title"] = "";
        $data["_view"] = "skpd/dashboard/login";
        $data["_js"] = "skpd/dashboard/login_js";
        $this->show_layout($data, LAYOUT_PROCESS);
    }

    public function login_process()
    {
        $post = $this->input->post();
        if (!empty($post)) {
            if ($this->ion_model->login($this->input->post('username'), $this->input->post('password'))) {
                // jika login sukses, redirect ke dashboard
                $pesan = $this->ion_model->messages();

                echo base_url("");
            } else {
                // jika login gagal, redirect kembali ke halaman login

                $err_message = str_replace("<p>", "", $this->ion_model->errors());
                $err_message = str_replace("</p>", "", $err_message);

                // echo 'gagal,Username atau password salah';
                echo 'gagal,' . $err_message;
            }
        } else {
            // user tidak login, tampilkan halaman login
            $this->load->view('login');
        }
    }

    public function logout()
    {
        $logout = $this->ion_auth->logout();
        redirect('login');
    }

    public function change_sidebar_color()
    {
        $post = $this->input->post();
        echo $this->mas_model->change_sidebar_color($post["sidebar_color"], $this->user_id);
    }

    public function change_navbar_color()
    {
        $post = $this->input->post();
        echo $this->mas_model->change_navbar_color($post["navbar_color"], $this->user_id);
    }

    public function initiate_tahun()
    {
        echo $this->mas_model->initiate_tahun();
    }
}
