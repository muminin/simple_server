<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('loguser'))
{
    function loguser($tipe = "", $Str ) {
    	//var_dump($Str);
	     $CI =& get_instance();
 
	    if (strtolower($tipe) == "tambah"){
	        $log_tipe   = 'create';
	    }
	    elseif(strtolower($tipe) == "update")
	    {
	        $log_tipe   = 'update';
	    }
	    elseif(strtolower($tipe) == "hapus"){
	        $log_tipe   = 'delete';
	    }
	    else{
	        $log_tipe  = 'unknown';
	    }
	    $log = $log_tipe;
	    $Str= json_decode(json_encode($Str), True);
	    
	 	foreach ($Str as $key => $value) {
	 		if (!is_array($value)) {
	 			$log .= ','.$key.':'.$value;
	 		}
	 	}


	 	$CI->load->library('ion_auth');
	 	$user = $CI->ion_auth->user()->row();
	 	//log to database
	 	$CI ->db->insert('log', array('id_user'=>$user->id,'log'=>$log));
	}	
}