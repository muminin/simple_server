<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class model_api extends CI_Model {
    
    public function get_categori()
    {
        $query = $this->db->get('kategori');
        return $query->result();
    }

    public function get_element()
    {
        $query = $this->db->get('element');
        return $query->result();
    }

    public function get_element_skpd($idskpd)
    {
        
        $this->db->where('', $Value);
        
        $query = $this->db->get('element');
        return $query->result();
    }

    public function get_nilai($in)
    {
        
    }

    public function get_elem($id_parent=0)
	{
        $elements = array();
		$this->db->select('id,id_parent,id_group,nama,ketersediaan');
		$this->db->where('id_parent',$id_parent);
		$query = $this->db->get('element');
        // foreach ($query->result() as $element) {
        //     $elements[$element->id]=$element;
        // }
		// return  $elements;

        return $query->result();
	}

    public function get_elemen_in($where_in)
    {
        $this->db->select('id,id_parent,id_group,nama,ketersediaan');
        $this->db->where_in('id_parent',$where_in);
        $query = $this->db->get('element');
        foreach ($query->result() as $element) {
            $elements[$element->id]=$element;
        }
		return  $elements;
    }

    public function get_elementisi($skpd)
    {

        $sql='SELECT `element`.`nama`,`element`.`id_group`,`element`.`id`,`element`.`id_parent`,`element`.`id_kat`
              FROM
	            `element`
              WHERE
	            id_group = '.$skpd.'
                AND id NOT IN (SELECT id_parent FROM element WHERE id_parent != 0)';
        
        $query = $this->db->query($sql);
        
        foreach ($query->result() as $element) {
            if ($element->id_parent == 0) {
                if (!isset($elements[0][$element->id_kat]) ) {
                    $elements [0] [$element->id_kat][$element->nama]= $element;
                    //var_dump($elements [0]);
                    //echo ' masuk';
                }else{
                    //var_dump($elements [0]);
                    $merge[$element->id_kat][$element->nama] = $element ;
                    $merge_element = array_merge($elements[0][$element->id_kat],$merge[$element->id_kat]);
                    //var_dump( $merge_element);
                    $elements [0] [$element->id_kat]=  $merge_element;
                }
            }else{
                $elements [$element->id_parent] []= $element;
                //echo 'sdsfdsfs';

            }
        }
        //     var_dump($elements);
        // var_dump($this->db->queries);
        // exit();
        return $elements;
    }

    public function element_id_in($in)
    {
        $this->db->select('element.nama,element.id_group,element.id,element.id_parent,element.id_kat');
        $this->db->where_in('id',$in);
        $query = $this->db->get('element');
        foreach ($query->result() as $element) {
            $elements[$element->id_parent][]=$element;
        }
		return  $elements;
    }
    

}

/* End of file model_api.php */
