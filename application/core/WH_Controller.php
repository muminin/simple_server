<?php

class WH_Controller extends CI_Controller
{
    var $user_id;
    var $tahun_sess;

    function __construct()
    {
        parent::__construct();

        $this->load->model('skpd/Master_model', 'mas_model');
        $this->load->model('Ion_auth_model', 'ion_model');

        $this->user_id = $this->session->userdata("user_id");
        $this->mas_model->set_template_setting($this->user_id);

        $this->tahun_sess = !empty($this->session->userdata("tahun_sess")) ? $this->session->userdata("tahun_sess") : date("Y");
    }

    public function login_check()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect("login");
        }
    }

    public function print_out_data($data)
    {
        echo "<pre>";
        var_dump($data);
        exit;
    }

    public function show_layout($data, $layout = LAYOUT_MAIN)
    {
        if ($layout == LAYOUT_MAIN) {
            $this->tahun_sess = !empty($this->session->userdata("tahun_sess")) ? $this->session->userdata("tahun_sess") : date("Y");
            $data["tahun_sess"] = $this->tahun_sess;

            $this->load->view('skpd/layout/main', $data);
        } elseif ($layout == LAYOUT_PROCESS) {
            $this->load->view('skpd/layout/process', $data);
        } elseif ($layout == LAYOUT_LANDING) {
            $this->load->view('skpd/layout/main_landing', $data);
        }
    }

    public function random_color_part()
    {
        return str_pad(mt_rand(0, 255), 2, '0', STR_PAD_LEFT);
    }

    public function random_color($transparent)
    {
        return "rgba(" . $this->random_color_part() . "," . $this->random_color_part() . "," . $this->random_color_part() . ", $transparent)";
    }

    public function random_color_string()
    {
        return "rgba(" . $this->random_color_part() . "," . $this->random_color_part() . "," . $this->random_color_part() . ", ";
    }
}
