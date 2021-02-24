<?php

class Dhistory_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function all_data()
    {
        $data = array();
        if ($this->session->userdata("group") == 1) {
            $this->db->order_by("program_id", "asc");
            $this->db->order_by("created_date", "desc");

            // $this->db->order_by("id_data", "asc");
            // $this->db->order_by("id", "asc");

            $this->db->where("data_deleted", false);
            $data = $this->db->get("v_data_history")->result_array();
        } else {
            $bidang = $this->session->userdata("bidang");
            $this->db->order_by("program_id", "asc");
            $this->db->order_by("created_date", "desc");

            // $this->db->order_by("id_data", "asc");
            // $this->db->order_by("id", "asc");

            if (isset($bidang)) {
                $count = count($bidang) - 1;
                foreach ($bidang as $key => $val) {
                    if ($count == 0) {
                        $this->db->where("data_bidang", $val["bidang_id"]);
                    } else {
                        if ($key == 0) {
                            $this->db->where("(data_bidang", $val["bidang_id"]);
                        } elseif ($key == $count) {
                            $this->db->or_where("data_bidang = " . $val["bidang_id"] . ")", NULL);
                        } else {
                            $this->db->or_where("data_bidang", $val["bidang_id"]);
                        }
                    }
                }

                $this->db->where("data_deleted", false);
                $this->db->where("data_pro_deleted", false);
                $this->db->where("data_bid_deleted", false);
                $this->db->where("data_uru_deleted", false);
                $this->db->where("data_jen_deleted", false);
                $data = $this->db->get("v_data_history")->result_array();
            }
        }

        return $data;
    }

    function get_data_byid($id)
    {
        $result = $this->db->get_where("v_data", array(
            "id" => $id,
        ));

        return $result->row_array();
    }
}
