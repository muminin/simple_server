<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Export extends WH_Controller
{
    private $filename = "nilai"; // Kita tentukan nama filenya

    function __construct()
    {
        parent::__construct();
        $this->login_check();

        $this->load->model("skpd/Konfigurasi_model", "kon_model");
        $this->load->model("skpd/Data_model", "dat_model");
        $this->load->model("skpd/Kegiatan_model", "keg_model");
    }

    public function history_byprogram()
    {
        $this->load->library("Excel");

        $get = $this->input->get();
        $history = $this->dat_model->get_history_byprogram($get["program"]);

        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);

        $object->getActiveSheet()->mergeCells("A1:G1");
        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "Rekap Perubahan Data Program");

        $object->getActiveSheet()->mergeCells("A2:G2");
        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 2, ucwords($history[0]["program_nama"]));

        // HEADER
        $table_columns = array("#", "DATA URAIAN", "SATUAN", "NILAI AWAL", "NILAI SAAT INI", "TANGGAL UPDATE", "OPD");
        $column = 0;
        $excel_head = 4;
        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, $excel_head, $field);
            $column++;
        }
        // HEADER

        // BODY
        $excel_row = $excel_head + 1;
        $no = 0;
        foreach ($history as $row) {
            $no++;

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row["uraian"]);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row["satuan"]);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row["nilai"]);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row["data_nilai"]);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, date("d/m/Y", strtotime($row["created_date"])));
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row["name"]);

            $object->getActiveSheet()
                ->getStyle("A$excel_row:G$excel_row")
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel_row++;
        }
        // BODY

        // STYLING
        $border = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        for ($col = 'A'; $col !== 'H'; $col++) {
            $object->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);

            for ($row = $excel_head; $row < $excel_row; $row++) {
                $object->getActiveSheet()
                    ->getStyle($col . $row)
                    ->applyFromArray($border);
            }
        }

        $object->getActiveSheet()
            ->getStyle("A$excel_head:G$excel_head")
            ->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'BCD6EE')
                    )
                )
            );

        for ($row = 1; $row <= $excel_head; $row++) {
            $object->getActiveSheet()
                ->getStyle("A$row:G$row")
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        // STYLING

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $history[0]["program_nama"] . '.xls"');

        $object_writer->save('php://output');
    }

    public function data_byopd()
    {
        $this->load->library("Excel");

        $get = $this->input->get();
        $data = $this->dat_model->all_data_export($get["tahun"]);


        // INITIATION
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        // INITIATION

        // HEADER
        $table_columns = array("#", "JENIS", "URUSAN", "BIDANG", "PROGRAM", "KEGIATAN", "SIMPLE", "SATUAN", "TURUNAN I", "TURUNAN II", "NILAI");
        $column = 0;
        $excel_head = 1;
        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, $excel_head, $field);
            $column++;
        }
        // HEADER

        // BODY
        $excel_row = $excel_head + 1;
        $no = 0;
        foreach ($data as $row) {
            $no++;

            if ($row["level"] == 1) {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row["id"]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row["jenis_utama"]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row["urusan"]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row["bidang"]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row["program"]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row["kegiatan"]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row["uraian"]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row["satuan"]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row["nilai"]);
            } elseif ($row["level"] == 2) {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row["id"]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row["satuan"]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row["uraian"]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row["nilai"]);
            } elseif ($row["level"] == 3) {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row["id"]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row["satuan"]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, "");
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row["uraian"]);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row["nilai"]);
            }

            $object->getActiveSheet()
                ->getStyle("A$excel_row:K$excel_row")
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel_row++;
        }
        // BODY

        // STYLING
        $border = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        for ($col = 'A'; $col !== 'L'; $col++) {
            $object->getActiveSheet()
                ->getColumnDimension($col);
            // ->setAutoSize(true);

            for ($row = $excel_head; $row < $excel_row; $row++) {
                $object->getActiveSheet()
                    ->getStyle($col . $row)
                    ->applyFromArray($border);
            }
        }

        $object->getActiveSheet()
            ->getStyle("A$excel_head:K$excel_head")
            ->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'BCD6EE')
                    )
                )
            );

        for ($row = 1; $row <= $excel_head; $row++) {
            $object->getActiveSheet()
                ->getStyle("A$row:K$row")
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        $object->getActiveSheet()->freezePane('A2');
        for ($row = 1; $row <= $excel_row; $row++) {
            if ($row % 2 == 0) {
                $object->getActiveSheet()->getStyle("A$row:K$row")->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('argb' => 'FFF3F3F3')
                        ),
                    )
                );
            }
        }
        // STYLING

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $get["tahun"] . '.xlsx"');

        // $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        // $object_writer->save('php://output');

        $objWriter = new PHPExcel_Writer_Excel2007($object);
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    public function upload_file($filename, $destinasi = "uploads/nilai")
    {
        $config['file_name']        = $filename;
        $config['upload_path']      = FCPATH . $destinasi;
        $config['allowed_types']    = '*';
        $config['max_size']         = '30000'; // Added Max Size
        $config['overwrite']        = TRUE;

        if (!is_dir($config['upload_path'])) {
            @mkdir($config['upload_path'], 0755, true);
        }

        $this->load->library('upload', $config);
        if ($this->upload->do_upload($this->filename)) { // Lakukan upload dan Cek jika proses upload berhasil
            // Jika berhasil :
            $return = array(
                'result' => 'success',
                'file' => $this->upload->data(),
                'error' => '',
                'status' => 1
            );

            return $return;
        } else {
            // Jika gagal :
            $return = array(
                'result' => 'failed',
                'file' => '',
                'error' => $this->upload->display_errors(),
                'status' => 0
            );

            return $return;
        }
    }

    public function import_nilai()
    {
        $this->load->library("Excel");

        $post = $this->input->post();

        $upload = $this->upload_file($post["tahun"]);
        if ($upload['result'] == "success") {
            $excelreader = new PHPExcel_Reader_Excel2007();
            $loadexcel = $excelreader->load(FCPATH . 'uploads/nilai/' . $post["tahun"] . '.xlsx'); // Load file yang tadi diupload ke folder excel
            $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);

            $data = array();
            $numrow = 1;
            foreach ($sheet as $row) {
                if ($numrow > 1) {
                    array_push($data, array(
                        "id" => str_replace("N", "", $row["A"]),
                        "nilai" => $row['K'],
                    ));
                }

                $numrow++; // Tambah 1 setiap kali looping
            }

            $result = $this->dat_model->update_nilai($data);
            echo $result;
        } else {
            // Jika proses upload gagal

            $data['upload_error'] = $upload; // Ambil pesan error uploadnya untuk dikirim ke file form dan ditampilkan
            echo $upload["status"];
        }
    }

    public function import_kegiatan()
    {
        $this->load->library("Excel");

        $post = $this->input->post();

        $upload = $this->upload_file($post["tahun"], "uploads/kegiatan");
        if ($upload['result'] == "success") {
            $excelreader = new PHPExcel_Reader_Excel2007();
            $loadexcel = $excelreader->load(FCPATH . 'uploads/kegiatan/' . $post["tahun"] . '.xlsx'); // Load file yang tadi diupload ke folder excel
            $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);

            $data = array();
            $numrow = 1;
            foreach ($sheet as $row) {
                if ($numrow > 1) {
                    if (!empty($row["B"]) && !empty($row["C"]) && !empty($row["D"]) && !empty($row["E"])) {
                        $urusan = $row["B"];
                        $bidang = (int) substr($row["C"], 1);
                        $program = $row["D"];
                        $kegiatan = $row["E"];
                        $rekening_program = $urusan . $bidang . $program;

                        array_push($data, array(
                            "tahun" => $row["A"],
                            "urusan" => $urusan,
                            "bidang" => $bidang,
                            "program" => $program,
                            "kegiatan" => $kegiatan,
                            "nama_kegiatan" => $row["F"],
                            "rekening_program" => $rekening_program,
                        ));
                    }
                }

                $numrow++; // Tambah 1 setiap kali looping
            }

            // $this->print_out_data($data);

            $result = $this->keg_model->upload_kegiatan($data[0]["tahun"], $data);
            echo $result;
        } else {
            // Jika proses upload gagal

            $data['upload_error'] = $upload; // Ambil pesan error uploadnya untuk dikirim ke file form dan ditampilkan
            echo $upload["status"];
        }
    }
}
