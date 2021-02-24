<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends WH_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("skpd/Home_model", "hom_model");
    }

    public function index()
    {
        $data["title"] = "Home";
        $data["_view"] = "skpd/home/main_new";
        $data["_js"] = "skpd/home/main_new_js";

        $this->show_layout($data, LAYOUT_LANDING);
    }

    public function get_jenistahun()
    {
        $post = $this->input->post();

        $result = $this->hom_model->get_jenis($post["tahun"], true);
        echo json_encode($result);
    }

    public function get_jenis()
    {
        $post = $this->input->post();

        $result = $this->hom_model->get_jenis($post["jenis"], false);
        echo json_encode($result);
    }

    public function get_urusan()
    {
        $post = $this->input->post();
        // $this->print_out_data($post);

        if (isset($post["jenis"])) {
            $result = $this->hom_model->get_urusan($post["jenis"], true);
        } elseif (isset($post["urusan"])) {
            $result = $this->hom_model->get_urusan($post["urusan"], false);
        }

        echo json_encode($result);
    }

    public function get_bidang()
    {
        $post = $this->input->post();

        if (isset($post["urusan"])) {
            $result = $this->hom_model->get_bidang($post["urusan"], true);
        } elseif (isset($post["bidang"])) {
            $result = $this->hom_model->get_bidang($post["bidang"], false);
        }

        echo json_encode($result);
    }

    public function get_program()
    {
        $post = $this->input->post();

        if (isset($post["bidang"])) {
            $result = $this->hom_model->get_program($post["bidang"], true);
        } elseif (isset($post["program"])) {
            $result = $this->hom_model->get_program($post["program"], false);
        }

        echo json_encode($result);
    }

    public function get_kegiatan()
    {
        $post = $this->input->post();

        if (isset($post["program"])) {
            $result = $this->hom_model->get_kegiatan($post["program"], true);
        } elseif (isset($post["kegiatan"])) {
            $result = $this->hom_model->get_kegiatan($post["kegiatan"], false);
        }

        echo json_encode($result);
    }

    public function get_data()
    {
        $post = $this->input->post();

        if (isset($post["program"])) {
            $data = $this->hom_model->get_data($post["program"], true);
            // $this->print_out_data($data);

            $result = array();
            if (!empty($data)) {
                $result["level"] = max(array_column($data, "level"));
                $result["data"] = $this->map_data($data);
            }
        } elseif (isset($post["data"])) {
            $result = $this->hom_model->get_data($post["data"], false);
        }

        echo json_encode($result);
    }

    public function map_data($result)
    {
        $parent = array();
        foreach ($result as $val_d) {
            if ($val_d["level"] == 1) {
                $parent["parent_" . $val_d["id"]][] = $val_d;
            } else {
                $id = $this->map_data2($val_d, $val_d["level"]);
                $parent["parent_" . $id][] = $val_d;
            }
        }

        return $parent;
    }

    public function map_data2($data, $level)
    {
        $parent = 0;
        for ($i = 1; $i < $level; $i++) {
            if ($parent == 0) {
                $parent = $data["id"];
            }

            $res = $this->hom_model->get_parent($parent);
            $parent = $res;
        }

        return $parent;
    }

    // INFINITE LOOP
    public function map_data3($input)
    {
        $max_level = max(array_column($input, "level"));
        $level_save = array();
        for ($i = 1; $i <= $max_level; $i++) {
            foreach ($input as $data) {
                if ($data["level"] == $i) {
                    $level_save["level_" . $data["id"]][] = $data;
                }

                if ($i > 1) {
                    $this->map_data3($input);
                }
            }
        }

        $this->print_out_data($level_save);
    }
}
