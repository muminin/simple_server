<?php

class Master_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function set_template_setting($id_user)
    {
        $user = $this->db->get_where("t_user_template", array(
            "id_user" => $id_user,
        ))->row_array();

        if (!empty($user)) {
            $sidebar = !empty($user["sidebar_color"]) ? $user["sidebar_color"] : "sidebar-light-theme";
            $navbar = !empty($user["navbar_color"]) ? $user["navbar_color"] : "";

            $sidebar = str_replace("-theme", "", $sidebar);
            $this->session->set_userdata("sidebar", $sidebar);
            $this->session->set_userdata("navbar", $navbar);
        }
    }

    function change_sidebar_color($sidebar, $id_user)
    {
        $user = $this->db->get_where("t_user_template", array(
            "id_user" => $id_user,
        ))->row_array();

        if (!empty($user)) {
            $this->db->where("id_user", $id_user);
            $result = $this->db->update("t_user_template", array(
                "sidebar_color" => $sidebar,
                "modified_date" => date("Y-m-d H:i:s"),
                "modified_by" => $id_user,
            ));
        } else {
            $result = $this->db->insert("t_user_template", array(
                "id_user" => $id_user,
                "sidebar_color" => $sidebar,
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $id_user,
            ));
        }

        $sidebar = str_replace("-theme", "", $sidebar);
        $this->session->set_userdata("sidebar", $sidebar);
        return $result;
    }

    function change_navbar_color($navbar, $id_user)
    {
        $user = $this->db->get_where("t_user_template", array(
            "id_user" => $id_user,
        ))->row_array();

        if (!empty($user)) {
            $this->db->where("id_user", $id_user);
            $result = $this->db->update("t_user_template", array(
                "navbar_color" => $navbar,
                "modified_date" => date("Y-m-d H:i:s"),
                "modified_by" => $id_user,
            ));
        } else {
            $result = $this->db->insert("t_user_template", array(
                "id_user" => $id_user,
                "navbar_color" => $navbar,
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $id_user,
            ));
        }

        $this->session->set_userdata("navbar", $navbar);
        return $result;
    }

    function get_tahun()
    {
        $result = $this->db->query("SELECT tahun FROM m_tahun WHERE is_deleted = false");

        return $result->result_array();
    }

    function initiate_tahun()
    {
        $tahun_awal = 2015;
        $tahun_akhir = date("Y");

        $this->db->trans_begin();

        for ($i = $tahun_awal; $i <= $tahun_akhir; $i++) {
            $check = $this->db->get_where("m_tahun", array("tahun" => $i))->row_array();
            if (empty($check)) {
                $this->db->insert("m_tahun", array("tahun" => $i));
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo 0;
        } else {
            $this->db->trans_commit();
            echo 1;
        }
    }
}
