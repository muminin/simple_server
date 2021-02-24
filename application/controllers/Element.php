<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//require APPPATH . '/libraries/REST_Controller.php';

/**
 * Keys Controller
 * This is a basic Key Management REST controller to make and delete keys
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
  */

class element extends CI_Controller{

    protected $categories;
    protected $elements;

    function __construct()
    {
        ini_set('memory_limit', '6000M');
        // Construct the parent class
        parent::__construct();

        // // Configure limits on our controller methods
        // // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        // $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        // $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        // $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }


    public function atas()
    {
        $skpd = 10101;
        $this->load->model('model_api');
        $all_element = array();
        $elements = $this->model_api->get_elementisi($skpd);
        $id_parents = array_keys( $elements);
       
        $i = 1;
        $z = 1;
        while ($i == 1) {
            
             $parents = $this->parent_element($id_parents);
           
             if (count($parents)==1) {
                 $i =0;
                 
             }else{
                foreach ($parents as $parent) {
                    // echo $parent->id;
                    //  echo'<br>';
                    //   echo'<br>';
                    foreach ($parent as $element) {
                        //echo'1';
                        //var_dump($elements[0]);
                        if ($element->id_parent != 0) {
                            //  /var_dump(array_key_exists($element->id_parent,$elements));
                            if (array_key_exists($element->id_parent,$elements)) {
                                $merge[$element->nama] = $elements[$element->id];
                                $merge_element = array_merge($merge,$elements[$element->id_parent]);
                                $elements[$element->id_parent][$element->nama] =  $merge_element;
                            }else{
                                $elements[$element->id_parent][$element->nama]  = $elements[$element->id];
                            }
                        }else{
                             if (isset($elements[0])) {
                                //var_dump($elements[$element->id]);
                                $merge[$element->nama] = $elements[$element->id];

                                //var_dump( $merge);
                                $merge_element = array_merge($merge,$elements[$element->id_parent]);

                                $elements[0][$element->nama] =  $merge_element;
                                //var_dump($elements[0]);


                                $z++;
                                if ($z==2) {
                                    var_dump( $elements[0]);
                                    // var_dump($this->db);
                                    exit();
                                }
                               // exit();
                            }else{
                                //$elements[0] = array();
                               // $elements[0][$element->id_kat]=array();
                                $elements[0][$element->nama] =$elements[$element->id];
                            }
                        }
                        unset($elements[$element->id]);

                    }
                    
                  
                }
                $id_parents = array_keys($parents);
             }
             $z++;
             if ($z==1) {
                 print_r( $elements);
                exit();
             }
        }
        var_dump($elements);

        


    }

    public function parent_element($id_parent)
    {
       $elements =  $this->model_api->element_id_in($id_parent);
       return $elements;
    }
    

    public function coba()
    {
        $this->idskp =10101;
        $this->load->model('model_api');
        $element = array();
        $elem_parent = $this->model_api->get_elem();
        
        foreach ($elem_parent as $v_elem_parent) {
            $cek_sub = $this->subElement($v_elem_parent->id);
            if ($cek_sub) {
                $element[$v_elem_parent->nama]=$cek_sub;
            }else {
                $element[$v_elem_parent->nama][]=$v_elem_parent;
            }
        }

        var_dump($element);
    }

    public function subElement($parent)
    {
        $element = $this->model_api->get_elem($parent);
        $sub = array();
        foreach ($element as $v_element) {
            $cek_sub = $this->subElement($v_element->id);
            if ($cek_sub) {
                 
            }else{
                if ($v_element->id_group == $this->idskp) {
                    $sub[] =  $v_element;
                }
            }
        }
        
        return  $sub;
    }


    public function element_get()
    {
         $this->load->model('model_api');
         $this->categories = $this->model_api->get_categori();
         $this->elements = $this->model_api->get_element();
         $data = array();
         foreach ($this->categories as $categori) {
             if ($categori->id_parent == 0  ) {

                 $getsub = $this->get_sub_categorie($categori->id);

                 if (  empty($getsub)) {
                     $data[$categori->nama][] = $getsub;
                }
             }
             echo "real: ".(memory_get_peak_usage(true)/1024/1024)." MiB\n\n";
         }
         //var_dump($data);
    }


    protected function get_sub_categorie($id_parent)
    {
       // echo $id_parent;
        $sub_categori=array();
       
        foreach ($this->categories as $categori) {
            if ($categori->id_parent ===  $id_parent) {

                $element = $this->element($categori->id);

               if (!empty($element)) {
                  $element[$element->nama][] = $element;
               }
            }
        }
        //var_dump($sub_categori);
        
        return $sub_categori;
    }

    public function element($id_category)
    {
        $time1 = microtime(true);

        //script code
        //...

        $time2 = microtime(true);
// code

    //    / var_dump($this->elements);
        $sub=array();
        $this->load->model('model_api');
        $this->elements = $this->model_api->get_element();
        foreach ($this->elements as $element) {
            if ($element->id_parent == 0) {
                $sub[$element->nama] = $this->sub_element($element->id,$element->nama);
            }else{
                //$sub[$element->nama][] = $element;
            }
           
        }
        echo "real: ".(memory_get_peak_usage(true)/1024/1024)." MiB\n\n";
        // /var_dump($sub);
        echo 'script execution time: ' . ($time2 - $time1); //value in seconds
        exit();
        return $elements;
    }

    protected function sub_element($id_parent,$nama_parent)
    {
        $sub= array();
        foreach ($this->elements as $element) {
            if ($element->id_parent == $id_parent) {
                $cek_sub = $this->sub_element($element->id,$element->nama);
                //$sub[$nama_parent]=$this->sub_element($element->id,$element->nama);
                if($cek_sub){
                    $sub = $cek_sub;
                }else {
                    $sub[$nama_parent][] = $element;
                }
            }
        }  
        return  $sub;
    }











}

/* End of file element.php */
