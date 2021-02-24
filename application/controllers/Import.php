<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends CI_Controller {

	public function index()
	{
		
	}

	function romanic_number($integer, $upcase = true) 
	{ 
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

	public function skpd()
	{
		$skpd = array(
						'tata'	=>  4011109,
						'esdm'	=> 4011110,
						'pertanian' => 20301,
						'kehutanan'	=> 4011111,
						'lh'		=> 20501,
						'pu'		=> 10301,
						'perumahan'	=>10401,
						'kelautan'	=> 4011112,
						'perhub'	=> 20901,
						'kominfo'	=> 21001,
						'kesehatan'	=> 10201,
						'sosial'	=> 10601,
						'budaya'	=> 21301,
						'KUKM'		=> 21101,
						'Modal'		=> 21201,
						'perdagamgam'	=>30601,
						'industri'		=> 30601,
						'pemerintahan'	=> 4010302,
						'pmdes'			=> 20201,
						'pariwisata'	=> 21301,
						'pendidikan'	=> 10101,
						'ketenagakerjaan'	=> 21101,
						'trans'			=> 4011113,
						'pemperem'		=> 20201,
						'pengen pendu KB'	=> 20201,
						'Olahraga'		=>21301,
						'perpus'		=> 21701,
						'kearsipan'		=>	21701



						);

		return $skpd;
	}

	public function element($id)
	{
		$this->load->library('Excel');
		$this->load->model('model_data');
		// masukkan angka romawi 
		for ($i=1; $i < 40; $i++) { 
			$romawi = $this->romanic_number($i);
			$angka_titik[$i.'.'] = $i;
			$angka_kurung[$i.').']=$i;
			$angka_romawi[$romawi.'.'] = $romawi;
		}
		//var_dump($angka_romawi);
		
		$file = 'C:\\xampp\\htdocs\\2017\\pusatdata\\import_data\\'.$id.'.xlsx';
		
		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		$objPHPExcel->setActiveSheetIndex(0) ;
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		foreach ($cell_collection as $cell) {
    		$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
		    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
		    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
		    $format = $objPHPExcel->getActiveSheet()->getStyle($cell)->getFill()->getStartColor()->getARGB();;
		    $datas[$row][$cell]['value']=$data_value;
		    //$datas[$row][$cell]['format']=$format;
		}
		//var_dump($datas[5]);
		$skpd =$datas[4]['I4']['value'];
		$id_kat = $datas[5]['I5']['value'];
		$before;
		foreach ($datas as $row => $data) {
			if (!array_key_exists('K'.$row, $data)) {
				$data['K'.+$row] ['value'] = NULL;
			}
			if (!array_key_exists('I'.$row, $data)) {
				$data['I'.+$row] ['value'] = NULL;
			}

			//var_dump($data);
			if ($row>5) {
				foreach ($data as $k_cell => $v_cell) {
					if (array_key_exists('J'.$row, $data)) {
						switch (substr($k_cell,0,1)) {
							case 'I':
									//var_dump($v_cell);
									//var_dump($v_cell == NULL);
									//echo $v_cell;
									if ($v_cell['value'] == NULL) {
										$level=$before+1;
										//var_dump($level);
									}else{
										$level = $v_cell['value'];
										$before =$v_cell['value'];
									}
									//var_dump($level);
									switch ($level) {
										case 2:
											$excel_element = ltrim($data['J'.$row]['value']," ");
											$bullet = explode(' ', $excel_element);
											if (array_key_exists($bullet[0], $angka_romawi) || array_key_exists($bullet[0], $angka_titik) || array_key_exists($bullet[0], $angka_kurung) ) {

												$excel_element='';
												unset($bullet[0]);
												foreach ($bullet as $k_bullet=> $v_bullet) {
													$excel_element .= $v_bullet.' ';
												}
												
											}

											$data_elm = array(
																'id_parent'	=>0,
																'id_kat'	=>$id_kat,
																'nama'		=>$excel_element,
																'satuan'	=>$data['K'.$row]['value'],
																'peraturan' =>' ',
																'id_group'	=>$skpd
																);
											$this->db->insert('element', $data_elm);
											$id_element1 = $this->db->insert_id();

											//var_dump($data_elm);

											break;

										case 3:
											$excel_element = ltrim($data['J'.$row]['value']," ");
											$bullet = explode(' ', $excel_element);
											if (array_key_exists($bullet[0], $angka_romawi) || array_key_exists($bullet[0], $angka_titik) || array_key_exists($bullet[0], $angka_kurung) ) {

												$excel_element='';
												unset($bullet[0]);
												foreach ($bullet as $k_bullet=> $v_bullet) {
													$excel_element .= $v_bullet.' ';
												}
												
											}

											$data_elm = array(
																'id_parent'	=>$id_element1,
																'id_kat'	=>$id_kat,
																'nama'		=>$excel_element,
																'satuan'	=>$data['K'.$row]['value'],
																'peraturan' =>' ',
																'id_group'	=>$skpd
																);
											$this->db->insert('element', $data_elm);
											$id_element2 = $this->db->insert_id();

											//var_dump($data_elm);

											break;
										case 4:
											$excel_element = ltrim($data['J'.$row]['value']," ");
											$bullet = explode(' ', $excel_element);
											if (array_key_exists($bullet[0], $angka_romawi) || array_key_exists($bullet[0], $angka_titik) || array_key_exists($bullet[0], $angka_kurung) ) {

												$excel_element='';
												unset($bullet[0]);
												foreach ($bullet as $k_bullet=> $v_bullet) {
													$excel_element .= $v_bullet.' ';
												}
												
											}

											$data_elm = array(
																'id_parent'	=>$id_element2,
																'id_kat'	=>$id_kat,
																'nama'		=>$excel_element,
																'satuan'	=>$data['K'.$row]['value'],
																'peraturan' =>' ',
																'id_group'	=>$skpd
																);
											$this->db->insert('element', $data_elm);
											$id_element3 = $this->db->insert_id();

											//var_dump($data_elm);

											break;

										case 5:
											$excel_element = ltrim($data['J'.$row]['value']," ");
											$bullet = explode(' ', $excel_element);
											if (array_key_exists($bullet[0], $angka_romawi) || array_key_exists($bullet[0], $angka_titik) || array_key_exists($bullet[0], $angka_kurung) ) {

												$excel_element='';
												unset($bullet[0]);
												foreach ($bullet as $k_bullet=> $v_bullet) {
													$excel_element .= $v_bullet.' ';
												}
												
											}

											$data_elm = array(
																'id_parent'	=>$id_element3,
																'id_kat'	=>$id_kat,
																'nama'		=>$excel_element,
																'satuan'	=>$data['K'.$row]['value'],
																'peraturan' =>' ',
																'id_group'	=>$skpd
																);
											$this->db->insert('element', $data_elm);
											$id_element4 = $this->db->insert_id();

											//var_dump($data_elm);

											break;

										case 6:
											$excel_element = ltrim($data['J'.$row]['value']," ");
											$bullet = explode(' ', $excel_element);
											if (array_key_exists($bullet[0], $angka_romawi) || array_key_exists($bullet[0], $angka_titik) || array_key_exists($bullet[0], $angka_kurung) ) {

												$excel_element='';
												unset($bullet[0]);
												foreach ($bullet as $k_bullet=> $v_bullet) {
													$excel_element .= $v_bullet.' ';
												}
												
											}

											$data_elm = array(
																'id_parent'	=>$id_element4,
																'id_kat'	=>$id_kat,
																'nama'		=>$excel_element,
																'satuan'	=>$data['K'.$row]['value'],
																'peraturan' =>' ',
																'id_group'	=>$skpd
																);
											$this->db->insert('element', $data_elm);
											$id_element5 = $this->db->insert_id();

											//var_dump($data_elm);

											break;
										
										
									}







								break;
							
							default:
								# code...
								break;
						}
					}

					
					//var_dump($k_cell);
					//var_dump($v_cell);
					//echo "\n";
				}
			}
		}
		$this->load->helper('url');
		$redirect = base_url().'import/element/'.((int)$id+1);
		

		redirect($redirect,'refresh');
	}

	public function lama($id)
	{
		$this->load->library('Excel');
		$this->load->model('model_data');
		// masukkan angka romawi 
		for ($i=1; $i < 26; $i++) { 
			$romawi = $this->romanic_number($i);
			$angka_titik[$i.'.'] = $i;
			$angka_kurung[$i.').']=$i;
			$angka_romawi[$romawi.'.'] = $romawi;
			$huruf =range('A', 'Z');
			$angka_huruf[strtolower($huruf[($i-1)].'.')] = $huruf[($i-1)];
		}
	
		//var_dump($angka_romawi);
		
		$file = 'C:\\xampp\\htdocs\\2017\\pusatdata\\data_lama\\'.$id.'.xlsx';
		
		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		$objPHPExcel->setActiveSheetIndex(0) ;
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		foreach ($cell_collection as $cell) {
    		$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
		    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
		    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
		    $format = $objPHPExcel->getActiveSheet()->getStyle($cell)->getFill()->getStartColor()->getARGB();;
		    $datas[$row][$cell]['value']=$data_value;
		    //$datas[$row][$cell]['format']=$format;
		}
		//var_dump($datas[5]);
		//$skpd =$datas[4]['I4']['value'];
		$id_kat = $datas[4]['C4']['value'];
		$this->db->select('id_group,id');
		$this->db->where('id_kat', $id_kat);
		$g_distribusi = $this->db->get('element');
		$r_distribusi = $g_distribusi->result();
		foreach ($r_distribusi as $v_distribusi) {
			$distribusi[$v_distribusi->id] = $v_distribusi->id_group;
		}



		foreach ($datas as $row => $data) {
			
			//var_dump($data);
			if ($row>7) {
				foreach ($data as $k_cell => $v_cell) {
					$this->db->last_query();
				
						switch (substr($k_cell,0,1)) {

							case 'C':
									//var_dump($v_cell);
									//var_dump($v_cell == NULL);
									//echo $v_cell;
									if ($v_cell['value'] == NULL) {
										$level=$before+1;
										//var_dump($level);
									}else{
										$level = $v_cell['value'];
										$before =$v_cell['value'];
									}
									//var_dump($level);
									switch ($level) {
										case 1:
											$excel_element = ltrim($data['D'.$row]['value']," ");
											$bullet = explode(' ', $excel_element);
											if (array_key_exists($bullet[0], $angka_romawi) || array_key_exists($bullet[0], $angka_titik) || array_key_exists($bullet[0], $angka_kurung )|| array_key_exists($bullet[0], $angka_huruf ) ) {

												$excel_element='';
												unset($bullet[0]);
												foreach ($bullet as $k_bullet=> $v_bullet) {
													$excel_element .= $v_bullet.' ';
												}
												
											}

											$data_elm = array(
																'id_parent'	=>0,
																'id_kat'	=>$id_kat,
																'nama'		=>$excel_element,
																'satuan'	=>$data['F'.$row]['value'],
																'peraturan' =>' ',
																'id_group'	=>$distribusi[$data['A'.$row]['value']]
																);
											$this->db->insert('element_new', $data_elm);
											$id_element1 = $this->db->insert_id();

											//var_dump($data_elm);

											break;

										case 2:
											$excel_element = ltrim($data['D'.$row]['value']," ");
											$bullet = explode(' ', $excel_element);
											if (array_key_exists($bullet[0], $angka_romawi) || array_key_exists($bullet[0], $angka_titik) || array_key_exists($bullet[0], $angka_kurung) || array_key_exists($bullet[0], $angka_huruf ) ) {

												$excel_element='';
												unset($bullet[0]);
												foreach ($bullet as $k_bullet=> $v_bullet) {
													$excel_element .= $v_bullet.' ';
												}
												
											}

											$data_elm = array(
																'id_parent'	=>$id_element1,
																'id_kat'	=>$id_kat,
																'nama'		=>$excel_element,
																'satuan'	=>$data['F'.$row]['value'],
																'peraturan' =>' ',
																'id_group'	=>$distribusi[$data['A'.$row]['value']]
																);
											$this->db->insert('element_new', $data_elm);
											$id_element2 = $this->db->insert_id();

											//var_dump($data_elm);

											break;
										case 3:
											$excel_element = ltrim($data['D'.$row]['value']," ");
											$bullet = explode(' ', $excel_element);
											if (array_key_exists($bullet[0], $angka_romawi) || array_key_exists($bullet[0], $angka_titik) || array_key_exists($bullet[0], $angka_kurung) || array_key_exists($bullet[0], $angka_huruf )) {

												$excel_element='';
												unset($bullet[0]);
												foreach ($bullet as $k_bullet=> $v_bullet) {
													$excel_element .= $v_bullet.' ';
												}
												
											}

											$data_elm = array(
																'id_parent'	=>$id_element2,
																'id_kat'	=>$id_kat,
																'nama'		=>$excel_element,
																'satuan'	=>$data['F'.$row]['value'],
																'peraturan' =>' ',
																'id_group'	=>$distribusi[$data['A'.$row]['value']]
																);
											$this->db->insert('element_new', $data_elm);
											$id_element3 = $this->db->insert_id();

											//var_dump($data_elm);

											break;

										case 4:
											$excel_element = ltrim($data['D'.$row]['value']," ");
											$bullet = explode(' ', $excel_element);
											if (array_key_exists($bullet[0], $angka_romawi) || array_key_exists($bullet[0], $angka_titik) || array_key_exists($bullet[0], $angka_kurung) || array_key_exists($bullet[0], $angka_huruf )) {

												$excel_element='';
												unset($bullet[0]);
												foreach ($bullet as $k_bullet=> $v_bullet) {
													$excel_element .= $v_bullet.' ';
												}
												
											}

											$data_elm = array(
																'id_parent'	=>$id_element3,
																'id_kat'	=>$id_kat,
																'nama'		=>$excel_element,
																'satuan'	=>$data['F'.$row]['value'],
																'peraturan' =>' ',
																'id_group'	=>$distribusi[$data['A'.$row]['value']]
																);
											$this->db->insert('element_new', $data_elm);
											$id_element4 = $this->db->insert_id();

											//var_dump($data_elm);

											break;

										case 5:
											$excel_element = ltrim($data['D'.$row]['value']," ");
											$bullet = explode(' ', $excel_element);
											if (array_key_exists($bullet[0], $angka_romawi) || array_key_exists($bullet[0], $angka_titik) || array_key_exists($bullet[0], $angka_kurung) || array_key_exists($bullet[0], $angka_huruf )) {

												$excel_element='';
												unset($bullet[0]);
												foreach ($bullet as $k_bullet=> $v_bullet) {
													$excel_element .= $v_bullet.' ';
												}
												
											}

											$data_elm = array(
																'id_parent'	=>$id_element4,
																'id_kat'	=>$id_kat,
																'nama'		=>$excel_element,
																'satuan'	=>$data['F'.$row]['value'],
																'peraturan' =>' ',
																'id_group'	=>$distribusi[$data['A'.$row]['value']]
																);
											$this->db->insert('element_new', $data_elm);
											$id_element5 = $this->db->insert_id();

											//var_dump($data_elm);

											break;
										
										
									}







								break;
							
							default:
								# code...
								break;
						}
					

					
					//var_dump($k_cell);
					//var_dump($v_cell);
					//echo "\n";
				}
			}
		}
		$this->load->helper('url');
		$redirect = base_url().'import/lama/'.((int)$id+1);
		
		redirect($redirect,'refresh');
	}
	public function administrasi()
	{
		$this->load->library('Excel');
		$this->load->model('model_data');
		// masukkan angka romawi 
		for ($i=1; $i < 40; $i++) { 
			$romawi = $this->romanic_number($i);
			$angka_titik[$i.'.'] = $i;
			$angka_kurung[$i.').']=$i;
			$angka_romawi[$romawi.'.'] = $romawi;
		}
		//var_dump($angka_romawi);
		$skpd ='1';
		$file = 'G:\www\2018\mojokerto\bappeko\simple_plan\import\PDRB.xlsx';
		$id_kat = 44;
		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		$objPHPExcel->setActiveSheetIndex(0) ;
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		foreach ($cell_collection as $cell) {
    		$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
		    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
		    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
		    $format = $objPHPExcel->getActiveSheet()->getStyle($cell)->getFill()->getStartColor()->getARGB();;
		    $datas[$row][$cell]['value']=$data_value;
		    $datas[$row][$cell]['format']=$format;
		}
		//var_dump($datas);
		// /exit();

		
		foreach ($datas as $row => $data) {
			//var_dump($data);
			// jika barislebih dari 3
			// var_dump($data['A'.$row] ['value']);
			// die();
			if ($row>1) {
				foreach ($data as $k_cell => $v_cell) {

					switch (substr($k_cell,0,1)) {
						case 'B':
								$nama = explode('. ', $v_cell['value'])[1];
								$satuan =(isset($data['A'.$row])) ?  $data['A'.$row] ['value'] : '' ;

								$data_elm = array(
									'id_parent'	=>0,
				 					'id_kat'	=>$id_kat,
				 					'nama'		=>$nama,
				 					'satuan'	=>$satuan,
				 					'peraturan' =>'',
				 					'id_group'	=>$skpd,
				 					'level'		=>1
				 					);
								$this->db->insert('element', $data_elm);
								$id_element1 = $this->db->insert_id();
							break;

						case 'C':
								$nama = explode('. ', $v_cell['value'])[1];
								$satuan = $data['A'.$row] ['value'];

								$data_elm = array(
									'id_parent'	=>$id_element1,
				 					'id_kat'	=>$id_kat,
				 					'nama'		=>$nama,
				 					'satuan'	=>$satuan,
				 					'peraturan' =>'',
				 					'id_group'	=>$skpd,
				 					'level'		=>2
				 					);
								$this->db->insert('element', $data_elm);
								$id_element2 = $this->db->insert_id();
							break;

						case 'D':
							$nama = explode('. ', $v_cell['value'])[1];
								$satuan = $data['A'.$row] ['value'];

								$data_elm = array(
									'id_parent'	=>$id_element2,
				 					'id_kat'	=>$id_kat,
				 					'nama'		=>$nama,
				 					'satuan'	=>$satuan,
				 					'peraturan' =>'',
				 					'id_group'	=>$skpd,
				 					'level'		=>3
				 					);
								$this->db->insert('element', $data_elm);
								$id_element3 = $this->db->insert_id();
							break;
						
						case 'E':
							$nama = explode('. ', $v_cell['value'])[1];
								$satuan = $data['A'.$row] ['value'];

								$data_elm = array(
									'id_parent'	=>$id_element3,
				 					'id_kat'	=>$id_kat,
				 					'nama'		=>$nama,
				 					'satuan'	=>$satuan,
				 					'peraturan' =>'',
				 					'id_group'	=>$skpd,
				 					'level'		=>4
				 					);
								$this->db->insert('element', $data_elm);
								$id_element4 = $this->db->insert_id();
							break;

						case 'F':
							$nama = explode('. ', $v_cell['value'])[1];
								$satuan = $data['A'.$row] ['value'];

								$data_elm = array(
									'id_parent'	=>$id_element4,
				 					'id_kat'	=>$id_kat,
				 					'nama'		=>$nama,
				 					'satuan'	=>$satuan,
				 					'peraturan' =>'',
				 					'id_group'	=>$skpd,
				 					'level'		=>5
				 					);
								$this->db->insert('element', $data_elm);
								$id_element5 = $this->db->insert_id();
							break;

						case 'G':
							$nama = explode('. ', $v_cell['value'])[1];
								$satuan = $data['A'.$row] ['value'];

								$data_elm = array(
									'id_parent'	=>$id_element5,
				 					'id_kat'	=>$id_kat,
				 					'nama'		=>$nama,
				 					'satuan'	=>$satuan,
				 					'peraturan' =>'',
				 					'id_group'	=>$skpd,
				 					'level'		=>6
				 					);
								$this->db->insert('element', $data_elm);
								$id_element6 = $this->db->insert_id();
							break;
					}
					//var_dump($v_cell['value']);
				// switch (substr($k_cell,0,1)) {

				// 		case 'B':
				// 			$data_elm = array(
				// 					'id_parent'	=>0,
				// 					'id_kat'	=>$id_kat,
				// 					'nama'		=>$nama,
				// 					'satuan'	=>$data['C'.$row]['value'],
				// 					'peraturan' =>'',
				// 					'id_group'	=>$skpd
				// 					);
				// 			break;

						/*case 'A':
							if (strpos(urlencode($v_cell['value']),'+++++')===false) {
								echo urlencode($v_cell['value']);
								$this->db->select('id,id_parent,id_kat,nama');
									$this->db->where('id_kat', $id_kat);
									$this->db->where('nama', $v_cell['value']);
									$q_element = $this->db->get('element');
									$element = $q_element->row();
									if ($element == null) {
										//var_dump($v_cell['value']);
										$exp_kata = explode(' ', $v_cell['value']);
										if (array_key_exists($exp_kata[0],$angka_romawi) ) {
											unset($exp_kata[0]);
										}
																				//var_dump($exp_kata);
										$nama ='';
										foreach ($exp_kata as $kata) {
											$nama .= $kata.' '; 
										}
										$data_elm = array(
															'id_parent'	=>0,
															'id_kat'	=>$id_kat,
															'nama'		=>$nama,
															'satuan'	=>$data['C'.$row]['value'],
															'peraturan' =>'',
															'id_group'	=>$skpd
															);
										$this->db->insert('element', $data_elm);
										$id_element1 = $this->db->insert_id();
									}else{
										$id_element1 = $element->id;
									}

							}else if (strpos(urlencode($v_cell['value']),'++++++++++')===false) {
								$v_cell['value'] = substr($v_cell['value'],5);
								$this->db->select('id,id_parent,id_kat,nama');
									$this->db->where('id_kat', $id_kat);
									$this->db->where('nama', $v_cell['value']);
									$q_element = $this->db->get('element');
									$element = $q_element->row();
									if ($element == null) {
										//var_dump($v_cell['value']);
										$exp_kata = explode(' ', $v_cell['value']);
										if (array_key_exists($exp_kata[0],$angka_titik) ) {
											unset($exp_kata[0]);
										}
																				//var_dump($exp_kata);
										$nama ='';
										foreach ($exp_kata as $kata) {
											$nama .= $kata.' '; 
										}
										$data_elm = array(
															'id_parent'	=>$id_element1,
															'id_kat'	=>$id_kat,
															'nama'		=>$nama,
															'satuan'	=>$data['C'.$row]['value'],
															'peraturan' =>'',
															'id_group'	=>$skpd
															);
										$this->db->insert('element', $data_elm);
										$id_element2 = $this->db->insert_id();
									}else{
										$id_element2 = $element->id;
									}
								echo urlencode($v_cell['value']);
							}else if (strpos(urlencode($v_cell['value']),'++++++++++++++++++++')===false) {
								$v_cell['value'] = substr($v_cell['value'],10);
								$this->db->select('id,id_parent,id_kat,nama');
									$this->db->where('id_kat', $id_kat);
									$this->db->where('nama', $v_cell['value']);
									if ($element == null) {
										//var_dump($v_cell['value']);
										$exp_kata = explode(' ', $v_cell['value']);
										if (array_key_exists($exp_kata[0],$angka_kurung) ) {
											unset($exp_kata[0]);
										}
																				//var_dump($exp_kata);
										$nama ='';
										foreach ($exp_kata as $kata) {
											$nama .= $kata.' '; 
										}
										$data_elm = array(
															'id_parent'	=>$id_element2,
															'id_kat'	=>$id_kat,
															'nama'		=>$nama,
															'satuan'	=>$data['C'.$row]['value'],
															'peraturan' =>'',
															'id_group'	=>$skpd
															);
										$this->db->insert('element', $data_elm);
										$id_element3 = $this->db->insert_id();
									}else{
										$id_element3 = $element->id;
									}
								echo urlencode($v_cell['value']);
							}else{
								$v_cell['value'] = substr($v_cell['value'],20);
								$this->db->select('id,id_parent,id_kat,nama');
									$this->db->where('id_kat', $id_kat);
									$this->db->where('nama', $v_cell['value']);
									if ($element == null) {
										//var_dump($v_cell['value']);
										$exp_kata = explode(' ', $v_cell['value']);
										if (array_key_exists($exp_kata[0],$angka_titik) ) {
											unset($exp_kata[0]);
										}
																				//var_dump($exp_kata);
										$nama ='';
										foreach ($exp_kata as $kata) {
											$nama .= $kata.' '; 
										}
										$data_elm = array(
															'id_parent'	=>$id_element3,
															'id_kat'	=>$id_kat,
															'nama'		=>$nama,
															'satuan'	=>$data['C'.$row]['value'],
															'peraturan' =>'',
															'id_group'	=>$skpd
															);
										$this->db->insert('element', $data_elm);
										$id_element4 = $this->db->insert_id();
									}else{
										$id_element4 = $element->id;
									}
								echo urlencode($v_cell['value']);
							}
							
							break;*/
						
					//}
					//var_dump(substr($k_cell,0,1));
				}
			}
			// end jikabari lebih dari 3
			
		}
	}

}

/* End of file import.php */
/* Location: ./application/controllers/import.php */