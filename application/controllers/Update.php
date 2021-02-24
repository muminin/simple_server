<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class update extends CI_Controller {

    
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('curl');
        $this->load->library('rest');
        $config = array('server' 			=> 'http://192.168.1.240/api/api_element',
                        'api_key'			=> 'sgog0ss8k4wwwkwog8ssgw08w4080wck08s0gwsg',
                        'api_name'		    => 'X-API-KEY',
                        'http_user' 		=> 'afilaskpd',
                        'http_pass' 		=> '26afi1991',
                        'http_auth' 		=> 'basic'
				//'ssl_verify_peer' => TRUE,
				//'ssl_cainfo' 		=> '/certs/cert.pem'
				);
        $this->rest->initialize($config);
    }
    

    public function data()
    {
        $update['update'] =array(
                                    array("id"=>1851,'nilai'=>1500),
                                    array('id'=>1849,'nilai'=>1500)

                                    );
        //$this->rest->put('/element',$update,'json');
        $update = ($this->rest->put('/element',$update,'json'));
        echo json_encode($update);
    }

}

/* End of file update.php */
