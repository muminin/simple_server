<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dhistory extends WH_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->login_check();

        $this->load->model("skpd/Dhistory_model", "dhi_model");
    }

    public function index()
    {
        $data["title"] = "History Data";
        $data["_js"] = "skpd/dhistory/index_new_js";
        $data["_plugin"] = array(
            PLUG_NOTIFICATION => "1",
            PLUG_DATATABLE => "1",
        );

        $data["_view"] = "skpd/dhistory/index";
        $data["history"] = $this->dhi_model->all_data();

        $data["_view"] = "skpd/dhistory/index_new";
        $temp_program = 0;
        $result_history = array();
        foreach ($data["history"] as $val) {
            if ($temp_program == 0 || $temp_program != $val["program_id"]) {
                $temp_program = $val["program_id"];
                $result_history[] = $val;
            }
        }

        $data["new_history"] = $result_history;

        // $this->print_out_data($result_history);
        $this->show_layout($data);
    }

    public function get_data()
    {
        $get = $this->input->get();
        $data = $this->dhi_model->get_data_byid($get["data"]);

        echo json_encode($data);
    }
}
