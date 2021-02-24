<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends CI_Controller {
	protected $request;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Excel');
		$this->load->model('model_data');
			$this->load->library(array('ion_auth'));
		
	}

	public function index()
	{
		//var_dump($this->request);
	}

	public function excel($kategori,$tahun=null)
	{
		if ($tahun==null) {
			$tahun = date('Y');
		}
		$judul = $this->model_data->get_kategori($kategori);



		$this->load->library('Excel');
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('worksheet');

		// set kolom judul
		$this->excel->getActiveSheet()->SetCellValue('A1', 'Sistem Informasi Pembangunan Daerah');
		$this->excel->getActiveSheet()->mergeCells('A1:E1');
		$this->excel->getActiveSheet()->SetCellValue('A2', 'Lokasi');
		$this->excel->getActiveSheet()->SetCellValue('C2', 'Kota Mojokerto');
		$this->excel->getActiveSheet()->mergeCells('A2:B2');
		$this->excel->getActiveSheet()->SetCellValue('A3', 'Jenis Data');
		$this->excel->getActiveSheet()->mergeCells('A3:B3');
		$this->excel->getActiveSheet()->SetCellValue('C3', $judul->nama);
		$this->excel->getActiveSheet()->SetCellValue('A4', 'id Jenis Data');
		$this->excel->getActiveSheet()->mergeCells('A4:B4');
		$this->excel->getActiveSheet()->SetCellValue('C4', $judul->id);
		$this->excel->getActiveSheet() ->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->SetCellValue('A5', 'Tahun');
		$this->excel->getActiveSheet()->mergeCells('A5:B5');
		$this->excel->getActiveSheet()->SetCellValue('C5', $tahun);
		$this->excel->getActiveSheet() ->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);




		// set head kolom

		$this->excel->getActiveSheet()->SetCellValue('A7', 'ID');
		$this->excel->getActiveSheet()->SetCellValue('B7', 'PARENT');
		$this->excel->getActiveSheet()->SetCellValue('C7', 'Level');
		$this->excel->getActiveSheet()->SetCellValue('D7', 'Nama');
		$this->excel->getActiveSheet()->SetCellValue('E7', 'Nilai');

		$this->excel->getActiveSheet()->SetCellValue('F7', 'Satuan');
		$this->excel->getActiveSheet()->SetCellValue('G7', 'ketersediaan');

		
		 //$this->excel->getActiveSheet()->getColumnDimension($col)->setAutoSize(TRUE);
		//set body excel
		$this->element($kategori,$tahun);
		$headestyle = array(
				'borders' => array(
		          	'allborders' => array(
		              	'style' => PHPExcel_Style_Border::BORDER_THIN
		          	)
		        )
	         	,'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array('rgb' => '31869B')
		        ),
		        "font" => array(
		        		"bold" => true,
		        		"color" => array(
		        			"rgb" => "ffffff"
		        	)
		        )
			);
		$color = array(
					1 => '92CDDC',
					2 => 'B7DEE8',
					3 => 'DAEEF3',
					4 => 'ffffff',
					5 => 'ffffff',
					6 => 'ffffff',
					7 => 'ffffff',
					8 => 'ffffff',
					9 => 'ffffff',
					10 => 'ffffff'
				);

		$style1 = array(
		      'borders' => array(
		          'allborders' => array(
		              'style' => PHPExcel_Style_Border::BORDER_THIN
		          )
		      )
		);

		$ketersediaan = array(1=> 'Ada',0 => 'Tidak');
		$rumus = array();
		foreach ($this->data_api as $k_jmlh => $v_jmlh) {
			//var_dump(property_exists($v_jmlh,'parent'),$k_jmlh );
			if (property_exists($v_jmlh,'parent')) {
				foreach ($this->data_api as $k_rumus => $v_rumus) {
					if ($v_jmlh->id == $v_rumus->id_parent) {
						if (!array_key_exists($k_jmlh,$rumus)) {
							$rumus[$k_jmlh] = $v_rumus->nilai;
						}else{
							$rumus[$k_jmlh] = $v_rumus->nilai+$rumus[$k_jmlh];
						}
					}
				}
			}
		}
		//var_dump($rumus);
		//var_dump($this->data_api);
		//exit();
		foreach ($this->data_api as $key => $v_element) {
			$cell =$key+8;
			$this->excel->getActiveSheet()->SetCellValue('A'.$cell, $v_element->id);
			$this->excel->getActiveSheet()->SetCellValue('B'.$cell, $v_element->id_parent);
			$this->excel->getActiveSheet()->SetCellValue('c'.$cell, $v_element->sub);
			$this->excel->getActiveSheet()->SetCellValue('D'.$cell, $v_element->no.' '.$v_element->nama);
			$this->excel->getActiveSheet()->getStyle('D'.$cell)->getAlignment()->setIndent($v_element->sub*2);
			if (property_exists($v_element,'parent')) {
				$this->excel->getActiveSheet()->SetCellValue('E'.$cell, $rumus[$key]);
				//var_dump($rumus[$key]);
			}else{
				//echo "string";
				$this->excel->getActiveSheet()->SetCellValue('E'.$cell, $v_element->nilai);
			}

			$this->excel->getActiveSheet()->SetCellValue('F'.$cell, $v_element->satuan);
			$this->excel->getActiveSheet()->SetCellValue('G'.$cell, $ketersediaan[$v_element->ketersediaan]);
			$baris = 'A'.$cell.':G'.$cell;
			$rowColor = array(
				'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array(
		            	'rgb' => $color[$v_element->sub]
		            )
		        )
		    );
			if (!property_exists($v_element,'child')) {
				$rowColor = array(
				'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array(
		            	'rgb' => 'ffffff'
		            )
		        	)
		    	);# code...
			}
			
			$this->excel->getActiveSheet()->getStyle($baris)->applyFromArray($rowColor);

			//Drop down list excel
			$objValidation = $this->excel->getActiveSheet()->getCell('G'.$cell)->getDataValidation();
			$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation->setAllowBlank(false);
			$objValidation->setShowInputMessage(true);
			$objValidation->setShowErrorMessage(true);
			$objValidation->setShowDropDown(true);
			$objValidation->setErrorTitle('Input error');
			$objValidation->setError('Value is not in list.');
			$objValidation->setPromptTitle('Pick from list');
			$objValidation->setPrompt('Please pick a value from the drop-down list.');
			$objValidation->setFormula1('"Ada,Tidak"');  // Make sure to put the list items between " and "  !!!

			//end drop

		}
		//var_dump($this->excel);
		//set style tabel data
		$this->excel->getActiveSheet()->getStyle('A7:G7')->applyFromArray($headestyle);
		$this->excel->getActiveSheet()->getStyle('A8:G'.$cell)->applyFromArray($style1);

		//set autosize kolom c
		$this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

		//protect cell id jek gagal
		//$this->excel->getActiveSheet()->getProtection()->setSheet(true);    
		//$this->excel->getActiveSheet()->getStyle('A8:G'.$cell)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);


		// /var_dump($this->data_api);
		//exit();	

		$format['header'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		$format['format'] = 'Excel2007';

		header('Content-Type:'.$format['header']); //mime type
		header('Content-Disposition: attachment;filename="'.$judul->nama.'('.$tahun.')'.'.xlsx"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		//var_dump($objWriter) ;
		$objWriter->save('php://output');
		//$//this->load->model('');
	}




	protected function element($id_kat=null,$tahun=null)
	{
		$this->id_kat = $id_kat;
		if ($tahun===null) {
			$tahun = date("Y");
		}

		if ($id_kat == null) {
			//exit();
		}

		$this->load->model('model_data');
		$this->id_group =1;
		if ($this->ion_auth->logged_in())
		{
			$user = $this->ion_auth->user()->row()->id;
			$this->id_group =$this->ion_auth->get_users_groups($user)->row()->id;
		}
		
		$elements = $this->model_data->element($id_kat,$tahun);
		foreach ($elements as $key => $element) {
			$element->sub = 1;
			//var_dump($key);
			$element->no = $this->number($key,$element->sub);
			//var_dump($element->no);

			$element->tahun = $tahun;
			$element->ketersediaan = (int)$element->ketersediaan;
			$element->publish = (int)$element->publish;
			$sub = $this->model_data->element($id_kat,$tahun,$element->id);
			if ($sub) {
				$element->parent = 1;
				$element->child = 1;
				$element->nilai =null;

				$this->data_api[] = $element;
				$this->sub_elements($sub,$tahun);
				if (array_key_exists($element->id,$this->pilih)) {
					$this->pilih[$element->id_parent]= 1;
				}else{
					array_pop($this->data_api);
				}
			}else{
				if ($element->id_group == $this->id_group || $this->id_group==1) {
					$this->data_api[] = $element;

				}
				
			}
		}
	}


	protected function sub_elements($parent,$tahun,$sub=1)
	{
		foreach ($parent as $key => $child) {
			$child->sub = $sub+1;
			//var_dump($element->no);

			$child->no = $this->number($key,$child->sub);
			$child->tahun = $tahun;
			$child->ketersediaan =(int)$child->ketersediaan;
			$child->publish = (int)$child->publish;
			$sub_data = $this->model_data->element($this->id_kat,$tahun,$child->id);
			if ($sub_data) {
				$child->child = $sub+1;
				$child->nilai = null;
				$child->parent = $child->sub;
				$this->data_api [] = $child;
				//$sub_elements->parent =  $sub+1;
				$this->sub_elements($sub_data,$tahun,$sub+1);
				if (array_key_exists($child->id,$this->pilih)) {
					$this->pilih[$child->id_parent]= 1;
				}else{
					array_pop($this->data_api);
				}
			}else{
				if ($child->id_group == $this->id_group || $this->id_group==1) {
					$this->data_api [] = $child;
					$this->pilih[$child->id_parent] = 1; 
				}
			}
		}
	}


	public function number($number,$sub)
	{
		//($number.','.$sub.'<br>');
		switch ($sub) {
			case 3:
				
				$nomor = range('a', 'z');
				//var_dump($nomor);
				$preffix = $nomor[$number].'.';
				// $preffix." = ini 3 <br>";
				break;
			case 2:
				//echo ' ini 2 <br>';
				$nomor = range('1', '50');
				$preffix = $nomor[$number].'.';
				//echo $preffix." = ini 2 <br>";
				break;
			case 1:
				$nomor = $this->romanic_number($number);
				$preffix = $nomor.'.';
				break;
			default:
				$preffix = ' ';
				break;
		}
		return $preffix;
	}
	function romanic_number($integer, $upcase = true) 
	{ 
		$integer = $integer +1;
	    $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
	    $return = ''; 
	    while($integer > 0) 
	    { 
	        foreach($table as $rom=>$arb) 
	        { 
	            if($integer >= $arb) 
	            { 
	                $integer -= $arb; 
	                $return .= $rom; 
	                break; 
	            } 
	        } 
	    } 

	    return $return; 
	} 

	public function grafik_excel($tahun)
	{
		//$json = file_get_contents('url_here');
		$grafik_json = file_get_contents(base_url('/pusatdata/jmlhelem/'.$tahun));
		$data_grafik = json_decode($grafik_json);

		$this->load->library('Excel');
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('worksheet');
		$start_row = 3;
		unset ($data_grafik->{'1'});
		unset ($data_grafik->{'0'});



		$style1 = array(
		      'borders' => array(
		          'allborders' => array(
		              'style' => PHPExcel_Style_Border::BORDER_THIN
		          )
		      )
		);

		$style2 = array(
							      'borders' => array(
							          	'allborders' => array(
							              	'style' => PHPExcel_Style_Border::BORDER_THIN
							          	)
							      	),
							      	'alignment' => array(
							      		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
							      	),
						      	  	"font" => array(
		        						"bold" => true,
		        						"color" => array(
		        							"rgb" => "ffffff"
		        						)
		        					),
		        					'fill' => array(
			            				'type' => PHPExcel_Style_Fill::FILL_SOLID,
			            				'color' => array('rgb' => '31869B')
			        				)
							);


		$headerstyle = array(
					'borders' => array(
			          	'allborders' => array(
			              	'style' => PHPExcel_Style_Border::BORDER_THIN
			          	)
			        )
		         	,'fill' => array(
			            'type' => PHPExcel_Style_Fill::FILL_SOLID,
			            'color' => array('rgb' => '31869B')
			        ),
			        "font" => array(
			        		"bold" => true,
			        		"color" => array(
			        			"rgb" => "ffffff"
			        	)
			        )
				);




		//var_dump($data_grafik);
		// // foreach ($data_grafik as $key => $value) {
		// // 	var_dump( $key);
		// // }
		//exit();
		foreach (range('A', 'G') as $key => $coloumn) {
			$this->excel->getActiveSheet()->getColumnDimension($coloumn)->setAutoSize(true);
		}

		$this->excel->getActiveSheet()->SetCellValue('A'.$start_row, 'No');
		$this->excel->getActiveSheet()->SetCellValue('B'.$start_row, 'Nama OPD');
		$this->excel->getActiveSheet()->SetCellValue('C'.$start_row, 'Jumlah Data');
		$this->excel->getActiveSheet()->SetCellValue('D'.$start_row, 'Data Tersedia');
		$this->excel->getActiveSheet()->SetCellValue('E'.$start_row, 'Data Tidak Tersedia');
		$this->excel->getActiveSheet()->SetCellValue('F'.$start_row, 'Data Terisi');
		$this->excel->getActiveSheet()->SetCellValue('G'.$start_row, 'Perosentase keterisian');
		$this->excel->getActiveSheet()->getStyle('A'.$start_row.':G'.$start_row)->applyFromArray($headerstyle);

		

		$start_row ++;
		$this->excel->getActiveSheet()->SetCellValue('A'.$start_row, '1');
		$this->excel->getActiveSheet()->SetCellValue('B'.$start_row, '2');
		$this->excel->getActiveSheet()->SetCellValue('C'.$start_row, '3');
		$this->excel->getActiveSheet()->SetCellValue('D'.$start_row, '4');
		$this->excel->getActiveSheet()->SetCellValue('E'.$start_row, '5=(3-4)');
		$this->excel->getActiveSheet()->SetCellValue('F'.$start_row, '6');
		$this->excel->getActiveSheet()->SetCellValue('G'.$start_row, '7=(6/4)*100');
		$this->excel->getActiveSheet()->getStyle('A'.$start_row.':G'.$start_row)->applyFromArray($style2);


		$no = 0;
		foreach ($data_grafik as $row_data => $data) {

			//var_dump($row_data);
			if (isset($data->jmlh)) {
				$no ++;
				$start_row ++;
				$Perosentase = (($data->terisi - $data->tak_tersedia)/($data->jmlh - $data->tak_tersedia))*100;
				if ($data->tak_tersedia == null) {
					$data->tak_tersedia =0;
				}
				$this->excel->getActiveSheet()->SetCellValue('A'.$start_row, $no);
				$this->excel->getActiveSheet()->SetCellValue('B'.$start_row, $data->description);
				$this->excel->getActiveSheet()->SetCellValue('C'.$start_row, $data->jmlh);
				$this->excel->getActiveSheet()->SetCellValue('D'.$start_row, ($data->jmlh - $data->tak_tersedia));
				$this->excel->getActiveSheet()->SetCellValue('E'.$start_row, $data->tak_tersedia);
				$this->excel->getActiveSheet()->SetCellValue('F'.$start_row, ($data->terisi - $data->tak_tersedia));
				$this->excel->getActiveSheet()->SetCellValue('G'.$start_row, round($Perosentase,2));
				$this->excel->getActiveSheet()->getStyle('A'.$start_row.':G'.$start_row)->applyFromArray($style1);

			}
			
		}
		$format['header'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		$format['format'] = 'Excel2007';

		header('Content-Type:'.$format['header']); //mime type
		header('Content-Disposition: attachment;filename="evaluasi simple('.$tahun.')'.'.xlsx"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		//var_dump($objWriter) ;
		$objWriter->save('php://output');
	}

}

/* End of file Export.php */
/* Location: ./application/controllers/Export.php */