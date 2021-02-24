<?php

class MY_Model extends CI_Model
{
    var $user_id;

    function __construct()
    {
        parent::__construct();
        $this->user_id = $this->session->userdata("user_id");
    }

    function print_out_data($data)
    {
        echo "<pre>";
        var_dump($data);
        exit;
    }

    function filter_bidang()
    {
        $bidang = $this->session->userdata("bidang");

        if (!empty($bidang)) {
            $count = count($bidang) - 1;
            foreach ($bidang as $key => $val) {
                if ($key == 0) {
                    $this->db->where("(id_bidang", $val["id_bidang"]);
                } elseif ($key == $count) {
                    $this->db->or_where("id_bidang = " . $val["id_bidang"], NULL);
                } else {
                    $this->db->or_where("id_bidang", $val["id_bidang"]);
                }
            }
        } else {
            return 0;
        }
    }

    function get_bidang_name_byid($bidang)
    {
        $bidang_name = $this->db->get_where("v_bidang", array(
            "id" => $bidang,
        ))->row_array()["nm_bidang"];

        return $bidang_name;
    }
}
