<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

	public $jumlah = array();
	public $hitung =1;
	public $poisis = array();
	public $leters = array();

	public $kec = array('Kec. Kranggan','Kec. Magersari','Kec. Prajurit Kulon');
	public $kel = array(
		array('Kelurahan Kauman','Kelurahan Surodinawan','Kelurahan Mentikan','Kelurahan Pulorejo','Kelurahan Blooto','Kelurahan Prajuritkulon'),
		array('Kelurahan Gunung Gedangan','Kelurahan Kedundung','Kelurahan Balongsari','Kelurahan Gedongan','Kelurahan Magersari','Kelurahan Wates'),
		array('Kelurahan Purwotengah','Kelurahan Sentanan','Kelurahan Jagalan','Kelurahan Meri','Kelurahan Miji','Kelurahan Kranggan')
	);

	public $sung = array('Brantas','Ngrayung','Cemporat','Sadar','Brangkal','Ngotok');
	
	public function __construct()
	{
		parent::__construct();

		$this->load->library('Excel');
		$this->load->library('session');
		$this->load->library('ion_auth');
		$this->kolom();

		$this->tabel = array(
				'borders' => array(
		          	'allborders' => array(
		              	'style' => PHPExcel_Style_Border::BORDER_THIN
		          	)
		        ),
		         'alignment' => array(
			        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			        'vertical'	=> PHPExcel_Style_Alignment::VERTICAL_CENTER
			    ),
		         "font" => array(
		        		"bold" => true
		        )
		);

		$this->ttd = array(
				'alignment' => array(
			        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			        'vertical'	=> PHPExcel_Style_Alignment::VERTICAL_CENTER
			    )
		);

		$this->data_header = array(
			'borders' => array(
	          	'allborders' => array(
	              	'style' => PHPExcel_Style_Border::BORDER_THIN
	          	)
	        ),
	         'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		        'vertical'	=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
		        'wrap'  => true
		    ),
	         "font" => array(
	        		"bold" => true
	        )
		);


		$this->data_tabel = array(
				'borders' => array(
		          	'allborders' => array(
		              	'style' => PHPExcel_Style_Border::BORDER_THIN
		          	)
		        )
		);

		$this->judul = array(
				 "font" => array(
		        	"bold" => true,
		        	"size" => 14
		        ),
				'alignment' => array(
			        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			        'vertical'	=> PHPExcel_Style_Alignment::VERTICAL_CENTER
			    )
		);

		$this->data_no= array(
			'borders' => array(
	          	'allborders' => array(
	              	'style' => PHPExcel_Style_Border::BORDER_THIN
	          	)
	        ),
	         'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		        'vertical'	=> PHPExcel_Style_Alignment::VERTICAL_CENTER
		    )
		);

		$this->warp = array(
				'borders' => array(
		          	'allborders' => array(
		              	'style' => PHPExcel_Style_Border::BORDER_THIN
		          	)
		        )
		        , 
		        'alignment' => array(
			      'wrap'  => true
			    )
		);

		$this->data_table_merge = array(
				'borders' => array(
		          	'allborders' => array(
		              	'style' => PHPExcel_Style_Border::BORDER_THIN
		          	)
		         ),
				 'alignment' => array(
			        'vertical'	=> PHPExcel_Style_Alignment::VERTICAL_CENTER
			    ),
		);

		

	}

	public function kolom()
	{
		$letters = range('A', 'Z');

		 // Iterate over 26 letters.
	    foreach ($letters as $letter) {
	      $letters[]='A'.$letter;
	    }

	    foreach ($letters as $letter) {
	      $letters[]='AA'.$letter;
	    }
	    $this->letters = $letters;

	}

	public function index()
	{
		
	}

	public function urusan()
	{
		$this->load->model('model_laporan');
		$tahun = $this->input->get('tahun');
		$this->tahun = $tahun;
		$id_element = $this->input->get('id');

		// $this->load->library('session');
		$element =array();
		$this->ambil_data = $this->get_header($id_element);
			
		foreach ($this->ambil_data as $key => $value) {
			$this->element($id_element,$tahun,$value->id_element);
		}
		

		
		$this->to_excel();
	}

	public function to_excel()
	{
		$this->load->library('Excel');
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('worksheet');
		$this->excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// set variable
		$groups = $this->ion_auth->get_users_groups($this->session->userdata('user_id'))->row();
		$style_kop = array(
	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        )
    	);

		// set image logo first 
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo');
		$logo = FCPATH . 'assets/images/logo_kota_mojokerto.png'; // Provide path to your logo file
		$objDrawing->setPath($logo);
		$objDrawing->setCoordinates('A2');
		$objDrawing->setHeight(120); // logo height
		$objDrawing->setWorksheet($this->excel->getActiveSheet());

		$format['header'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		$format['format'] = 'Excel2007';
		

		// set kopsurat
		$this->excel->getActiveSheet()->SetCellValue('B2', 'PEMERINTAH KOTA MOJOKERTO');
		$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true)->setSize(16);
		$this->excel->getActiveSheet()->mergeCells('B2:J2');

		$this->excel->getActiveSheet()->SetCellValue('B3', $groups->description);
		$this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true)->setSize(20);
		$this->excel->getActiveSheet()->mergeCells('B3:J3');

		// $this->excel->getActiveSheet()->SetCellValue('B4', $groups->alamat);
		// $this->excel->getActiveSheet()->mergeCells('B4:J4');
		$kol3= $groups->alamat;
		if (isset($groups->alamat)) {
			$kol3 .= ' Telepon (0321) '. $groups->telepon;
		}

		if (isset($groups->fax)) {
			$kol3 .= ' Fax (0321) '. $groups->fax;
		}

		$this->excel->getActiveSheet()->SetCellValue('B4', $kol3);
		$this->excel->getActiveSheet()->mergeCells('B4:J4');


		$kol4 = '';
		if (isset($groups->web)) {
			$kol4 .= 'Web : '.$groups->web.'.';
		}
		if (isset($groups->email)) {
			$kol4 .= 'E-mail : '.$groups->email.'.';
		}
		$row = 5;
		if ($kol4!= '') {
			$this->excel->getActiveSheet()->SetCellValue('B'.$row, $kol4);
			$this->excel->getActiveSheet()->mergeCells('B'.$row.':J'.$row);
			$row ++;
		}

		

		$this->excel->getActiveSheet()->SetCellValue('B'.$row,'MOJOKERTO'.$groups->kode_pos);
		$this->excel->getActiveSheet()->getStyle('B'.$row)->getFont()->setSize(16);
		$this->excel->getActiveSheet()->mergeCells('B'.$row.':J'.$row);


		$this->excel->getActiveSheet()->getStyle("B2:J6")->applyFromArray($style_kop);

		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
		$this->cur_row = 10;
		foreach ($this->ambil_data as $key => $value) {
			if (isset($this->jumlah)) {
				$col_judul = $this->cur_row;
				//var_dump($this->cur_row);
				if ($value->nama != Null) {
					$this->excel->getActiveSheet()->SetCellValue('A'.$this->cur_row, $value->nama);
					$this->excel->getActiveSheet()->getStyle('A'.$this->cur_row)->applyFromArray($this->judul);
					
					$this->cur_row++;
					$this->excel->getActiveSheet()->SetCellValue('A'.$this->cur_row, $this->tahun);
					$this->excel->getActiveSheet()->getStyle('A'.$this->cur_row)->applyFromArray($this->judul);

					$this->cur_row=$this->cur_row+2;
				}else{
					$this->cur_row=$this->cur_row-2;
				}
				
				$awal = $this->cur_row;
				//var_dump($value);
				$this->{'format_'.$value->format}($value->detail);
			//	var_dump($awal);
				$this->excel->getActiveSheet()->SetCellValue('A'.$awal, 'NO');
				$this->excel->getActiveSheet()->getStyle('A'.$awal)->getFont()->setBold(true);

				// SET MERGE DAN STYLE HEADER
				$this->excel->getActiveSheet()->mergeCells('B'.$awal.':B'.$this->cur_row);
				$this->excel->getActiveSheet()->getStyle('B'.$awal.':B'.$this->cur_row)->applyFromArray($this->tabel);
				$this->excel->getActiveSheet()->mergeCells('A'.$awal.':A'.$this->cur_row);
				$this->excel->getActiveSheet()->getStyle('A'.$awal.':A'.$this->cur_row)->applyFromArray($this->tabel);

					//die();

				if ($value->kec == 1 && $value->format ==1) {
					
					$this->excel->getActiveSheet()->SetCellValue('B'.$awal, 'KECAMATAN');
					//var_dump($awal);
					$this->excel->getActiveSheet()->getStyle('B'.$awal)->getFont()->setBold(true);
					// echo "\n";
					if ($value->nama != Null) {
						$kol_maxjdl = $this->excel->getActiveSheet()->getHighestDataColumn($this->cur_row);
						$this->excel->getActiveSheet()->mergeCells('A'.$col_judul.':'.$kol_maxjdl.$col_judul);
						$this->excel->getActiveSheet()->mergeCells('A'.$col_judul.':'.$kol_maxjdl.$col_judul);

						$this->excel->getActiveSheet()->mergeCells('A'.($col_judul+1).':'.$kol_maxjdl.($col_judul+1));
						$this->excel->getActiveSheet()->mergeCells('A'.($col_judul+1).':'.$kol_maxjdl.($col_judul+1));
					}
					
					$this->kec($value->detail,$value->id_element);

				}else if ($value->kec == 2 && $value->format ==1) {
					$this->excel->getActiveSheet()->SetCellValue('B'.$awal, 'Nama Sungai');
					$this->excel->getActiveSheet()->getStyle('B'.$awal)->getFont()->setBold(true);
					// echo "\n";
					if ($value->nama != Null) {
						$kol_maxjdl = $this->excel->getActiveSheet()->getHighestDataColumn($this->cur_row);
						$this->excel->getActiveSheet()->mergeCells('A'.$col_judul.':'.$kol_maxjdl.$col_judul);
						$this->excel->getActiveSheet()->mergeCells('A'.$col_judul.':'.$kol_maxjdl.$col_judul);

						$this->excel->getActiveSheet()->mergeCells('A'.($col_judul+1).':'.$kol_maxjdl.($col_judul+1));
						$this->excel->getActiveSheet()->mergeCells('A'.($col_judul+1).':'.$kol_maxjdl.($col_judul+1));
					}
					
					$this->sungai($value->detail,$value->id_element);

				}else if ($value->kec == 3 && $value->format ==1) {
					$this->excel->getActiveSheet()->SetCellValue('B'.$awal, 'Kecamatan/Kelurahan');
					$this->excel->getActiveSheet()->getStyle('B'.$awal)->getFont()->setBold(true);
					// echo "\n";
					if ($value->nama != Null) {
						$kol_maxjdl = $this->excel->getActiveSheet()->getHighestDataColumn($this->cur_row);
						$this->excel->getActiveSheet()->mergeCells('A'.$col_judul.':'.$kol_maxjdl.$col_judul);
						$this->excel->getActiveSheet()->mergeCells('A'.$col_judul.':'.$kol_maxjdl.$col_judul);

						$this->excel->getActiveSheet()->mergeCells('A'.($col_judul+1).':'.$kol_maxjdl.($col_judul+1));
						$this->excel->getActiveSheet()->mergeCells('A'.($col_judul+1).':'.$kol_maxjdl.($col_judul+1));
					}
					
					$this->kel($value->detail,$value->id_element);
				
				}else if ($value->format == 3) {
					// 	echo "\n";
					// var_dump($this->cur_row);
					if ($value->nama != Null) {
						$kol_maxjdl = $this->excel->getActiveSheet()->getHighestDataColumn($this->cur_row);
						$this->excel->getActiveSheet()->mergeCells('A'.$col_judul.':'.$kol_maxjdl.$col_judul);
						$this->excel->getActiveSheet()->mergeCells('A'.$col_judul.':'.$kol_maxjdl.$col_judul);

						$this->excel->getActiveSheet()->mergeCells('A'.($col_judul+1).':'.$kol_maxjdl.($col_judul+1));
						$this->excel->getActiveSheet()->mergeCells('A'.($col_judul+1).':'.$kol_maxjdl.($col_judul+1));
					}

					$this->cur_row = $this->cur_row +9;
				}else if ($value->format == 2){
					if ($value->nama != Null) {
						$kol_maxjdl = $this->excel->getActiveSheet()->getHighestDataColumn($this->cur_row);
						$this->excel->getActiveSheet()->mergeCells('A'.$col_judul.':'.$kol_maxjdl.$col_judul);
						$this->excel->getActiveSheet()->mergeCells('A'.$col_judul.':'.$kol_maxjdl.$col_judul);

						$this->excel->getActiveSheet()->mergeCells('A'.($col_judul+1).':'.$kol_maxjdl.($col_judul+1));
						$this->excel->getActiveSheet()->mergeCells('A'.($col_judul+1).':'.$kol_maxjdl.($col_judul+1));
					}

					$this->cur_row = $this->cur_row + (count($value->detail) + 5);
					
				}


				
			}
			
		}
		// tanda tangan
		$last_kolom = $this->excel->getActiveSheet()->getHighestDataColumn($this->cur_row-6);
		$last_kolom_num = intval(array_search($last_kolom,  $this->letters));
		$start_kolom = $this->letters[$last_kolom_num-2];
		$user =$this->ion_auth->get_users_groups($this->session->userdata('user_id'))->row();
			
		$this->excel->getActiveSheet()->SetCellValue($start_kolom.$this->cur_row , 'Kota Mojokerto, '.date("d-m-Y"));
		$this->excel->getActiveSheet()->mergeCells($start_kolom.$this->cur_row .':'.$last_kolom.$this->cur_row );
		$this->excel->getActiveSheet()->getStyle($start_kolom.$this->cur_row .':'.$last_kolom.$this->cur_row)->applyFromArray($this->ttd);
		
		$this->excel->getActiveSheet()->SetCellValue($start_kolom.($this->cur_row +1), 'Kepala '.$user->description);
		$this->excel->getActiveSheet()->mergeCells($start_kolom.($this->cur_row +1) .':'.$last_kolom.($this->cur_row +1) );
		$this->excel->getActiveSheet()->getStyle($start_kolom.($this->cur_row +1) .':'.$last_kolom.($this->cur_row +1))->applyFromArray($this->ttd);

		$this->excel->getActiveSheet()->SetCellValue($start_kolom.($this->cur_row +5), $user->kepala_opd);
		$this->excel->getActiveSheet()->mergeCells($start_kolom.($this->cur_row +5) .':'.$last_kolom.($this->cur_row +5) );
		$this->excel->getActiveSheet()->getStyle($start_kolom.($this->cur_row +5) .':'.$last_kolom.($this->cur_row +5))->applyFromArray($this->ttd);

		$this->excel->getActiveSheet()->SetCellValue($start_kolom.($this->cur_row+6), $user->nip);
		$this->excel->getActiveSheet()->mergeCells($start_kolom.($this->cur_row +6) .':'.$last_kolom.($this->cur_row +6) );
		$this->excel->getActiveSheet()->getStyle($start_kolom.($this->cur_row +6) .':'.$last_kolom.($this->cur_row +6))->applyFromArray($this->ttd);


		$format['header'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		$format['format'] = 'Excel2007';
		//var_dump($this->jumlah);
		//die();
		header('Content-Type:'.$format['header']); //mime type
		header('Content-Disposition: attachment;filename="laporan-.xlsx"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		//var_dump($objWriter) ;
		$objWriter->save('php://output');
		//$//this->load->model('');



	}

	public function format_1($data)
	{
		$this->cek_data($data);
//var_dump($data);
		$this->excel_format1($data,$this->cur_row);
	}

	public function excel_format1($data,$row,$kolom=2)
	{
		// var_dump ($data);
		// die();
		foreach ($data as $key => $value) {
			//$pos_baris = $this->posisi[$value->id_element]['row'];
			$awal = $kolom;
			$pos_kolom = $this->letters[$awal];

			$this->excel->getActiveSheet()->getColumnDimension($pos_kolom)->setWidth(17);
			
			if (isset($this->jumlah[$value->id_element]['satuan'])) {
				$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$row , $value->nama. "\r\n" .'('.$this->jumlah[$value->id_element]['satuan'].')');
			}else{
				$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$row , $value->nama);
			}

			if (isset($value->detail)) {
				$jmlh_detail=count($value->detail);

				$last_kolom = $this->excel_format1($value->detail, ($row+1),($kolom));
				$this->excel->getActiveSheet()->mergeCells($pos_kolom.$row.':'.$this->letters[$last_kolom-1].($row+$value->baris));
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$row.':'.$this->letters[$last_kolom-1].($row+$value->baris))->applyFromArray($this->data_header);
				$kolom =$last_kolom;
			}else{

				if ($value->baris != 0) {
					$this->excel->getActiveSheet()->mergeCells($pos_kolom.$row.':'.$pos_kolom.($row+$value->baris));
				}
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$row.':'.$pos_kolom.($row+$value->baris))->applyFromArray($this->data_header);
				$kolom++;
			}

			
		}
		if ($this->cur_row < $row) {
			$this->cur_row=$row;
		}
		return $kolom;
		/*
		foreach ($data as $key => $value) {
			$this->posisi[$value->id_element] = array(
				'kolom'=>$this->letters[$kolom],
				'row'	=> $this->cur_row
			);
			
			$kolom = (isset($value->detail)) ? $kolom +count($value->detail) : $kolom+1 ;
			$this->posisi[$value->id_element] ['merge'] = $this->letters[$kolom-1];
			if (isset($value->detail)) {

				
			}
			// var_dump($kolom);
			// var_dump ($value);

			
			$pos_kolom = $this->posisi[$value->id_element]['kolom'];
			$pos_baris = $this->posisi[$value->id_element]['row'];
			$pos_merge = $this->posisi[$value->id_element]['merge'];
			//$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $value->nama);
			$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris)->applyFromArray($this->tabel);
			$this->excel->getActiveSheet()->getColumnDimension($pos_kolom)->setWidth(17);
			
			if (isset($pos_merge) && $pos_merge != $pos_kolom) {
			
				$this->excel->getActiveSheet()->mergeCells($pos_kolom.$pos_baris.':'.$pos_merge.$pos_baris);
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris.':'.$pos_merge.$pos_baris)->applyFromArray($this->tabel);
			}
		}

		

		foreach ($data as $key => $value) {
			$pos_kolom = $this->posisi[$value->id_element]['kolom'];
			$pos_baris = $this->posisi[$value->id_element]['row'];
			

			
		
			if (isset($value->detail)) {
				$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $value->nama);
				$this->cur_row++;

				$this->excel_format1($value->detail, array_search($pos_kolom, $this->letters));

			}else{
				$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $value->nama);
				
				if (isset($this->jumlah[$value->id_element]['satuan'])) {
					$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $value->nama. "\r\n" .'('.$this->jumlah[$value->id_element]['satuan'].')');
				}else{
					$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $value->nama);
				}
				
				$this->excel->getActiveSheet()->getRowDimension($pos_baris)->setRowHeight($value->height);
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris)->getAlignment()->setWrapText(true);
				if ($value->baris != '0') {
					$this->excel->getActiveSheet()->mergeCells($pos_kolom.$pos_baris.':'.$pos_kolom.($pos_baris+$value->baris));
					$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris.':'.$pos_kolom.($pos_baris+$value->baris))->applyFromArray($this->tabel);

				}
				

			}
		}*/
	}



	public function format_3($data)
	{
		//unset($this->data);
		$this->data_laporan=array();
		//
		$this->cek_data($data);
		$this->excel_format3($data,$this->cur_row);

		// isi data untukformat_3
		// var_dump ($this->cur_row);
		// var_dump($this->jumlah);
		// var_dump($this->data_laporan);
		//  die();

		for ($i=1; $i < 6; $i++) { 
			$this->excel->getActiveSheet()->SetCellValue('A'.($this->cur_row+$i), $i);
			
			$this->excel->getActiveSheet()->getStyle('A'.($this->cur_row+$i))->applyFromArray($this->data_tabel);

		}
		// var_dump($this->data_laporan);
		// die();
		foreach ($this->data_laporan as $key => $value) {
			$kolom = $this->letters[$key+2];
			
			$jumlah = $this->jumlah[$value];
			//var_dump ($jumlah);
			//die();
			for ($i=0; $i < 5; $i++) { 
				$tahun = ($this->tahun-$i);
				$this->excel->getActiveSheet()->SetCellValue('B'.($this->cur_row+$i+1), 'Tahun '.$tahun);
				$this->excel->getActiveSheet()->getStyle('B'.($this->cur_row+$i+1))->applyFromArray($this->data_tabel);

				$this->excel->getActiveSheet()->SetCellValue($kolom.($this->cur_row+$i+1), $jumlah[$tahun]);
				$this->excel->getActiveSheet()->getStyle($kolom.($this->cur_row+$i+1))->applyFromArray($this->data_tabel);


			}
			
			

		}

		
		
	}

	

	public function cek_data($data)
	{
		$this->excel->getActiveSheet()->SetCellValue('B'.($this->cur_row),'Tahun');
		foreach ($data as $key => $value) {
			if ($value->id_element > 0) {
				if (!isset($this->jumlah[$value->id_element])) {
					$this->element($this->id_kat,$this->tahun,$value->id_element);
				}
				
			}
			if (isset($value->detail)) {
				$this->cek_data($value->detail);
			}
		}
	}

	public function excel_format3($data,$row,$kolom=2)
	{
		//var_dump($data);

		foreach ($data as $key => $value) {
			//$pos_baris = $this->posisi[$value->id_element]['row'];
			$awal = $kolom;
			
			//
			$pos_kolom = $this->letters[$awal];

			$this->excel->getActiveSheet()->getColumnDimension($pos_kolom)->setWidth(17);
			//var_dump($row);
			if (isset($this->jumlah[$value->id_element]['satuan'])) {
				$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$row , $value->nama. "\r\n" .'('.$this->jumlah[$value->id_element]['satuan'].')');

			}else{
				$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$row , $value->nama);

			}

			
			
			//var_dump ($value);
			//die();
			/*if ($value->baris != 0) {
				$this->excel->getActiveSheet()->mergeCells($pos_kolom.$row.':'.$pos_kolom.($row+$value->baris));
			}
			$this->excel->getActiveSheet()->getStyle($pos_kolom.$row.':'.$pos_kolom.($row+$value->baris))->applyFromArray($this->data_header);
			$kolom++;*/
			//var_dump($value->detail);
			if (isset($value->detail) ) {
				$jmlh_detail=count($value->detail);

				$last_kolom = $this->excel_format3($value->detail, ($row+1),($kolom));
			//	var_dump($last_kolom );
				$this->excel->getActiveSheet()->mergeCells($pos_kolom.$row.':'.$this->letters[$last_kolom-1].($row+$value->baris));
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$row.':'.$this->letters[$last_kolom-1].($row+$value->baris))->applyFromArray($this->data_header);
				$kolom =$last_kolom;
				//$this->data_laporan[]= $value->id_element;
			}else{

				if ($value->baris != 0) {
					$this->excel->getActiveSheet()->mergeCells($pos_kolom.$row.':'.$pos_kolom.($row+$value->baris));
				}
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$row.':'.$pos_kolom.($row+$value->baris))->applyFromArray($this->data_header);
				$this->data_laporan[]= $value->id_element;
				//var_dump($this->data_laporan);
				$kolom++;
			}

		

			
		}
		if ($this->cur_row < $row) {
			$this->cur_row=$row;
		}

		return $kolom;
		/* if ($row==null) {
			$row = $this->cur_row;
		}
		

		foreach ($data as $key => $value) {
			$this->posisi[$value->id_element] = array(
				'kolom'=>$this->letters[$kolom],
				'row'	=> $this->cur_row
			);
			$kolom = (isset($value->detail)) ? $kolom +count($value->detail) : $kolom+1 ;
			$this->posisi[$value->id_element] ['merge'] = $this->letters[$kolom-1];
			
			$pos_kolom = $this->posisi[$value->id_element]['kolom'];
			$pos_baris = $this->posisi[$value->id_element]['row'];
			$pos_merge = $this->posisi[$value->id_element]['merge'];
			//$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $value->nama);
			$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris)->applyFromArray($this->tabel);
			$this->excel->getActiveSheet()->getColumnDimension($pos_kolom)->setWidth(17);
			
			if (isset($pos_merge) && $pos_merge != $pos_kolom) {
			
				$this->excel->getActiveSheet()->mergeCells($pos_kolom.$pos_baris.':'.$pos_merge.$pos_baris);
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris.':'.$pos_merge.$pos_baris)->applyFromArray($this->tabel);
			}
		}

		
		foreach ($data as $key => $value) {
			$pos_kolom = $this->posisi[$value->id_element]['kolom'];
			$pos_baris = $this->posisi[$value->id_element]['row'];
			
			
		
			if (isset($value->detail)) {
				$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $value->nama);
				
				$this->excel_format3($value->detail, array_search($pos_kolom, $this->letters),$row+1);

			}else{
				$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $value->nama);
				if (isset($this->jumlah[$value->id_element]['satuan'])) {
					$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $value->nama. "\r\n" .'('.$this->jumlah[$value->id_element]['satuan'].')');
				}
				
				$this->excel->getActiveSheet()->getRowDimension($pos_baris)->setRowHeight($value->height);
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris)->getAlignment()->setWrapText(true);
				if ($value->baris != '0') {
					$this->excel->getActiveSheet()->mergeCells($pos_kolom.$pos_baris.':'.$pos_kolom.($pos_baris+$value->baris));
					$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris.':'.$pos_kolom.($pos_baris+$value->baris))->applyFromArray($this->tabel);

				}
				//var_dump($value);
				$this->data_laporan[]= $value->id_element;
				

			}
		}
		if ($this->cur_row < $row) {
			$this->cur_row=$row;
		}*/
		//var_dump($this->data_laporan);
	}

	public function sungai($data,$id_element)
	{
		$this->cur_row++;
		foreach ($data as $key => $value) {
			$this->sub_terkecil($value,$id_element);
		}

		foreach ($this->sung as  $key => $left) {
			$this->excel->getActiveSheet()->SetCellValue('B'.($this->cur_row+$key) , $left);
			$this->excel->getActiveSheet()->SetCellValue('A'.($this->cur_row+$key) , $key+1	);

			$this->excel->getActiveSheet()->getStyle('A'.($this->cur_row+$key).':B'.($this->cur_row+$key))->applyFromArray($this->data_tabel);
		}

		$this->db->select('id,id_parent');
		$this->db->where_in('id_parent', $this->sungai[$id_element]);
		foreach ($this->db->get('element')->result() as $k_elem => $kec) {
			$sub_kec [$kec->id_parent] []= $kec->id;
		}
		$kolom =2;
		foreach ($this->detail[$id_element] as $c_value) {
			$pos_kolom  = $this->letters[$kolom];
			$pos_baris  = $this->cur_row; 
			
			if ($c_value->kec ==2) {
				foreach ($sub_kec [$c_value->id_element] as $key => $v_kec) {
					$this->excel->getActiveSheet()->SetCellValue($pos_kolom.($pos_baris+$key) , $this->jumlah[$v_kec][$this->tahun]);

				}
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris.':'.$pos_kolom.($pos_baris+5))->applyFromArray($this->data_table_merge);

			
			}else{
				$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $this->jumlah[$c_value->id_element][$this->tahun]);
				$this->excel->getActiveSheet()->mergeCells($pos_kolom.$pos_baris.':'.$pos_kolom.($pos_baris+5));
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris.':'.$pos_kolom.($pos_baris+5))->applyFromArray($this->data_table_merge);

			}
			$kolom++;
		}

		// total
		$this->cur_row =$this->cur_row+6;
		$this->excel->getActiveSheet()->SetCellValue('A'.($this->cur_row) ,'Total');
		$this->excel->getActiveSheet()->mergeCells('A'.$this->cur_row.':B'.$this->cur_row);
		$this->excel->getActiveSheet()->getStyle('A'.$this->cur_row.':B'.$this->cur_row)->applyFromArray($this->data_table_merge);

		$kolom =2;
		foreach ($this->detail[$id_element] as $c_value) {
			$pos_kolom  = $this->letters[$kolom];
			$pos_baris  = $this->cur_row; 

			$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $this->jumlah[$c_value->id_element][$this->tahun]);
			$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris.':'.$pos_kolom.$pos_baris)->applyFromArray($this->tabel);

			$kolom++;
		}
		
		// TOTAL PERTAHUN
		$this->cur_row= $this->cur_row +2;

		for ($i=($this->tahun-1); $i > ($this->tahun-5); $i--) { 
			$kolom =2;
			foreach ($this->detail[$id_element] as $c_value) {
				$pos_kolom  = $this->letters[$kolom];
				$pos_baris  = $this->cur_row; 

				// tahun ke 
				$this->excel->getActiveSheet()->SetCellValue('A'.$pos_baris , 'Tahun '.$i);
				$this->excel->getActiveSheet()->mergeCells('A'.$pos_baris.':B'.$pos_baris);
				$this->excel->getActiveSheet()->getStyle('A'.$pos_baris.':B'.$pos_baris)->applyFromArray($this->tabel);
				//value
				$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $this->jumlah[$c_value->id_element][$i]);
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris.':'.$pos_kolom.$pos_baris)->applyFromArray($this->data_table_merge);

				$kolom++;
			}
			$this->cur_row++;
		}
		$this->cur_row=$this->cur_row+4;
	}

	public function kec($data,$id_element)
	{

		$this->cur_row++;
		foreach ($data as $key => $value) {
			$this->sub_terkecil($value,$id_element);
			//	var_dump($value);
		}
		//var_dump($this->kecamatan);
		foreach ($this->kec as  $key => $left) {
			$this->excel->getActiveSheet()->SetCellValue('B'.($this->cur_row+$key) , $left);
			$this->excel->getActiveSheet()->SetCellValue('A'.($this->cur_row+$key) , $key+1	);

			$this->excel->getActiveSheet()->getStyle('A'.($this->cur_row+$key).':B'.($this->cur_row+$key))->applyFromArray($this->data_tabel);
		}

		$this->db->select('id,id_parent');
		$this->db->where_in('id_parent', $this->kecamatan[$id_element]);
		foreach ($this->db->get('element')->result() as $k_elem => $kec) {
			$sub_kec [$kec->id_parent] []= $kec->id;
		}
		$kolom =2;
		foreach ($this->detail[$id_element] as $c_value) {
			$pos_kolom  = $this->letters[$kolom];
			$pos_baris  = $this->cur_row; 
			//var_dump($pos_baris);
			
			if ($c_value->kec & $c_value->sum ==0) {
				foreach ($sub_kec [$c_value->id_element] as $key => $v_kec) {
					$this->excel->getActiveSheet()->SetCellValue($pos_kolom.($pos_baris+$key) , $this->jumlah[$v_kec][$this->tahun]);
					//var_dump($pos_baris+$key);
					//var_dump($c_value->id_element);


				}
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris.':'.$pos_kolom.($pos_baris+2))->applyFromArray($this->data_table_merge);

			}else if ($c_value->kec & $c_value->sum !=0) {
				
			}else{
				$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $this->jumlah[$c_value->id_element][$this->tahun]);
				$this->excel->getActiveSheet()->mergeCells($pos_kolom.$pos_baris.':'.$pos_kolom.($pos_baris+2));
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris.':'.$pos_kolom.($pos_baris+2))->applyFromArray($this->data_table_merge);

			}
			$kolom++;
		}

		// total
		$this->cur_row =$this->cur_row+3;
		$this->excel->getActiveSheet()->SetCellValue('A'.($this->cur_row) ,'Total');
		$this->excel->getActiveSheet()->mergeCells('A'.$this->cur_row.':B'.$this->cur_row);
		$this->excel->getActiveSheet()->getStyle('A'.$this->cur_row.':B'.$this->cur_row)->applyFromArray($this->data_table_merge);

		$kolom =2;
		foreach ($this->detail[$id_element] as $c_value) {
			$pos_kolom  = $this->letters[$kolom];
			$pos_baris  = $this->cur_row; 

			$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $this->jumlah[$c_value->id_element][$this->tahun]);
			$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris.':'.$pos_kolom.$pos_baris)->applyFromArray($this->tabel);

			$kolom++;
		}
		
		// TOTAL PERTAHUN
		$this->cur_row= $this->cur_row +2;

		for ($i=($this->tahun-1); $i > ($this->tahun-5); $i--) { 
			$kolom =2;
			foreach ($this->detail[$id_element] as $c_value) {
				$pos_kolom  = $this->letters[$kolom];
				$pos_baris  = $this->cur_row; 

				// tahun ke 
				$this->excel->getActiveSheet()->SetCellValue('A'.$pos_baris , 'Tahun '.$i);
				$this->excel->getActiveSheet()->mergeCells('A'.$pos_baris.':B'.$pos_baris);
				$this->excel->getActiveSheet()->getStyle('A'.$pos_baris.':B'.$pos_baris)->applyFromArray($this->tabel);
				//value
				$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $this->jumlah[$c_value->id_element][$i]);
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris.':'.$pos_kolom.$pos_baris)->applyFromArray($this->data_table_merge);

				$kolom++;
			}
			$this->cur_row++;
		}
		$this->cur_row=$this->cur_row+4;

	}

	public function kel($data,$id_element)
	{
		// get kecamatan 
		$this->cur_row++;
		foreach ($data as $key => $value) {
			$this->sub_terkecil($value,$id_element);
			//	var_dump($value);
		}
		$val_kel	= array();
		$val_kec 	= array();
		$pos_baris  = $this->cur_row; 
		
		$this->db->select('id,id_parent,nama');
		$this->db->where_in('id_parent', $this->kecamatan[$id_element]);
		foreach ($this->db->get('element')->result() as $k_elem => $kec) {
			//$this->excel->getActiveSheet()->SetCellValue('B'.$pos_baris , $kec->nama);

 			$this->db->select('id,id_parent,nama');
			$this->db->where_in('id_parent',$kec->id);
			foreach ($this->db->get('element')->result() as $kel) {
				//$this->excel->getActiveSheet()->SetCellValue('B'.$pos_baris , $kel->nama);
				// if (isset($val_kec[$kec->id])) {
					
				// }else{

					$val_kel[$kel->id_parent] []=$kel;
				//}
				
			}
			$val_kec [$kec->id_parent] []= $kec;
		}
		
		$kolom =2;
		$no = 1;
		foreach ($this->kecamatan[$id_element] as  $key => $c_value) {
			$pos_kolom  = $this->letters[$kolom+$key];
			$pos_baris  = $this->cur_row; 
			
			foreach ($val_kec[$c_value] as $baris => $tabel_kec) {
				//$pos_baris++;
				if ($key ==0) {
					$this->excel->getActiveSheet()->SetCellValue('B'.$pos_baris, $tabel_kec->nama);
					$this->excel->getActiveSheet()->SetCellValue('A'.$pos_baris, $no);
					$this->excel->getActiveSheet()->getStyle('A'.$pos_baris.':'.'B'.$pos_baris)->applyFromArray($this->warp);
					$no++;
				}
				$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $this->jumlah[$tabel_kec->id][$this->tahun]);
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris )->applyFromArray($this->data_tabel);
				$pos_baris++;
				foreach ($val_kel[$tabel_kec->id] as $baris_kel => $tabel_kel) {
					
					if ($key==0) {
						$this->excel->getActiveSheet()->SetCellValue('B'.$pos_baris, $tabel_kel->nama);
						$this->excel->getActiveSheet()->SetCellValue('A'.$pos_baris, $no);
						$this->excel->getActiveSheet()->getStyle('A'.$pos_baris.':'.'B'.$pos_baris)->applyFromArray($this->warp);
						$this->excel->getActiveSheet()->getStyle('B'.$pos_baris)->getAlignment()->setIndent(2);
						$no++;
					}
					$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $this->jumlah[$tabel_kel->id][$this->tahun]);
					$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris )->applyFromArray($this->data_tabel);
					$pos_baris++;
				}
			}
		}

		$this->cur_row = $this->cur_row+$no;
		for ($i=($this->tahun-1); $i > ($this->tahun-5); $i--) { 
			$kolom =2;
			foreach ($this->detail[$id_element] as $c_value) {
				$pos_kolom  = $this->letters[$kolom];
				$pos_baris  = $this->cur_row; 

				// tahun ke 
				$this->excel->getActiveSheet()->SetCellValue('A'.$pos_baris , 'Tahun '.$i);
				$this->excel->getActiveSheet()->mergeCells('A'.$pos_baris.':B'.$pos_baris);
				$this->excel->getActiveSheet()->getStyle('A'.$pos_baris.':B'.$pos_baris)->applyFromArray($this->tabel);
				//value
				$this->excel->getActiveSheet()->SetCellValue($pos_kolom.$pos_baris , $this->jumlah[$c_value->id_element][$i]);
				$this->excel->getActiveSheet()->getStyle($pos_kolom.$pos_baris.':'.$pos_kolom.$pos_baris)->applyFromArray($this->data_table_merge);

				$kolom++;
			}
			$this->cur_row++;
		}
		$this->cur_row=$this->cur_row+4;
		
		//die();
	}

	public function non_kec($data,$id_element)
	{
		
	}

	public function sub_terkecil($data,$id_element)
	{
		// var_dump($id_element);
		// var_dump($data->detail)
		if (!isset($data->detail)) {
			$this->detail[$id_element] []= $data;
			$this->kecamatan[$id_element][] =$data->id_element;
			$this->sungai[$id_element][] =$data->id_element;
			return;
		}
		foreach ($data->detail as $key => $value) {
			$this->sub_terkecil($value,$id_element);
		}
		
	}



	public function format_2($data)
	{
		$this->excel->getActiveSheet()->SetCellValue('C'.$this->cur_row,'Tahun');
		$this->excel->getActiveSheet()->mergeCells('C'.$this->cur_row.':'.'G'.$this->cur_row);
		$this->excel->getActiveSheet()->getStyle('A'.$this->cur_row.':'.'G'.($this->cur_row+1))->applyFromArray($this->tabel);

		$this->excel->getActiveSheet()->SetCellValue('B'.$this->cur_row,'Uraian');
		$this->cur_row ++;
		for ($i=1; $i < 6; $i++) { 
			$kolom = $this->letters[$i+1];
			$tahun = $this->tahun-($i-1);
			$this->excel->getActiveSheet()->SetCellValue($kolom.$this->cur_row,$tahun );
		}
		foreach ($data as $key => $value) {
			$baris = $this->cur_row +($key+1);
			$this->excel->getActiveSheet()->SetCellValue('B'.$baris,$value->nama);
			$this->excel->getActiveSheet()->getStyle('B'.$baris)->applyFromArray($this->warp);

			$this->excel->getActiveSheet()->SetCellValue('A'.$baris,$key+1);
			$this->excel->getActiveSheet()->getStyle('A'.$baris)->applyFromArray($this->data_no);

			if (!isset($this->jumlah[$value->id_element])) {
				$this->element($this->id_kat,$this->tahun,$value->id_element);
			}
		
			// var_dump ($this->jumlah[$value->id_element]);
			// die();
			for ($i=1; $i < 6; $i++) { 
				$kolom = $this->letters[$i+1];
				$tahun = $this->tahun-($i-1);
				$this->excel->getActiveSheet()->SetCellValue($kolom.$baris,$this->jumlah[$value->id_element][$tahun]);
				$this->excel->getActiveSheet()->getStyle($kolom.$baris)->applyFromArray($this->data_tabel);
				
				
			}
		}
		
	}

	

	protected function element($id_kat=null,$tahun=null,$id_element)
	{
		
		$this->id_kat = $id_kat;

		$this->id_group =1;
		if ($this->ion_auth->logged_in())
		{
			$user =$this->session->userdata('user_id');
			$this->id_group =$this->ion_auth->get_users_groups($user)->row()->id;
		}

		$this->db->select('element.id,
							element.id_parent,
							element.nama,
							element.id_group,
							element.ketersediaan,
							element.`level`,
							element.satuan,
							(SELECT nilai from data_thn where data_thn.id_element = element.id and tahun='.$tahun.') as "'.$tahun.'",
							(SELECT nilai from data_thn where data_thn.id_element = element.id and tahun='.($tahun-1).') as "'.($tahun-1).'",
							(SELECT nilai from data_thn where data_thn.id_element = element.id and tahun='.($tahun-2).') as "'.($tahun-2).'",
							(SELECT nilai from data_thn where data_thn.id_element = element.id and tahun='.($tahun-3).') as "'.($tahun-3).'",
							(SELECT nilai from data_thn where data_thn.id_element = element.id and tahun='.($tahun-4).') as "'.($tahun-4).'"'		
							,false);
		$this->db->where('element.id_kat', $id_kat);
		$this->db->where('element.id',$id_element);
		$this->db->order_by('element.id', 'ASC');

		$elements = $this->db->get('element')->result_array();
		
		//if ($elements) {
		foreach ($elements as $key => $element) {
			$element['sub'] = 1;
			
			$sub = $this->model_laporan->element($id_kat,$tahun,$element['id']);

			if ($sub) {
				$element['parent'] = 1;

				$this->data_api[] = $element;
				$this->sub_elements($sub,$tahun);
				if (array_key_exists($element['id'],$this->pilih)) {
					$this->pilih[$element['id_parent']]= 1;

					for ($i=$tahun; $i > ($tahun-5); $i--) { 
						$this->jumlah[$element['id_parent']][$i] = (isset($this->jumlah[$element['id_parent']][$i])) ? $this->jumlah[$element['id_parent']][$i] + floatval($this->jumlah[$element['id']][$i]) : floatval($this->jumlah[$element['id']][$i]);
					}
					$this->jumlah[$element['id']]['satuan']=$element['satuan'];
				}else{
					array_pop($this->data_api);
				}
			}else{
				for ($i=$tahun; $i > ($tahun-5); $i--) { 
					$this->jumlah[$element['id']][$i] = floatval($element[$i]) ;
				}
			}
		}
		//}
		
	}

	protected function sub_elements($parent,$tahun,$sub=1)
	{
		foreach ($parent as $key => $child) {
			$child['sub'] = $sub+1;
			$this->jumlah[$child['id']]['satuan']=$child['satuan'];

			
			$sub_data = $this->model_laporan->element($this->id_kat,$tahun,$child['id']);
			if ($sub_data) {
				$child['child'] = $sub+1;
				$this->data[] = $child;
				$this->sub_elements($sub_data,$tahun,$sub+1);
				if (array_key_exists($child['id'],$this->pilih)) {
					$this->pilih[$child['id_parent']]= 1;
					for ($i=$tahun; $i > ($tahun-5); $i--) { 
						$this->jumlah[$child['id_parent']][$i] = (isset($this->jumlah[$child['id_parent']][$i])) ? $this->jumlah[$child['id_parent']][$i] + floatval($this->jumlah[$child['id']][$i]) : floatval($this->jumlah[$child['id']][$i]);
					}
					
					$this->jumlah[$child['id']]['satuan']=$child['satuan'];
				}else{
					array_pop($this->data_api);
				}
			}else{
				if ($child['id_group'] == $this->id_group || $this->id_group==1) {
					$this->data[] = $child;
					$this->pilih[$child['id_parent']] = 1; 
					for ($i=$tahun; $i > ($tahun-5); $i--) { 
						$this->jumlah[$child['id']][$i] = floatval($child[$i]) ;
						$this->jumlah[$child['id_parent']][$i] = (isset($this->jumlah[$child['id_parent']][$i])) ? $this->jumlah[$child['id_parent']][$i] + floatval($child[$i]) : floatval($child[$i]) ;
					}

					//$this->jumlah[$child['id']]['satuan']=$child['satuan'];
					
				}
			}
		}
	}

	public function FunctionName($value='')
	{
		
	}

	public function get_header($id_kat)
	{
		$this->load->model('model_laporan');
		$judul = $this->model_laporan->lap_header($id_kat);
	

		if ($judul) {
			foreach ($judul as $value) {
				$this->hitung=1;
				$details = $this->detail_header($value,$id_kat);
				$form_lap[] = $details;
			}
		}else{
			
			die('laporan tidak ada');
		}
		
	
		return $form_lap;
	}

	public function detail_header($details)
	{	
		$sub_headers = $this->model_laporan->lap_header(null,$details->id_element);
		
		
		if ($sub_headers) {
			$return = $details;

			foreach ($sub_headers as $value) {
				$return->detail[] = $this->detail_header($value);
			}
			

			return $return;
		}else{
			
			return $details;
		}
		
	}
	

}

/* End of file Laporan.php */
/* Location: ./application/controllers/Laporan.php */