<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class authentication
{
    protected $ci; 
    public $user;
    public function get_hashed_password($username,$password)
    {
        
        $this->ci = &get_instance();
      
        $this->ci->session->sess_destroy();
        $this->ci->load->model('ion_auth_model');
        if (empty($password))
		{
            //echo 'sdfsdfsdf';
			$this->set_error('login_unsuccessful');
			return FALSE;
		}
        $query = $this->ci->db->select('username, email, id, password, active, last_login')
		                  ->where('username', $username)
		                  ->limit(1)
		    			  ->order_by('id', 'desc')
		                  ->get('users');

       // var_dump($query);
        if($this->ci->ion_auth_model->is_time_locked_out($username))
		{
			return FALSE;
		}
        if ($query->num_rows() === 1)
		{
            $user = $query->row();

            $password = $this->ci->ion_auth_model->hash_password_db($user->id, $password);
            if ($password === TRUE)
			{
                if ($user->active == 0 && $user->system == 0 )
				{
    				return FALSE;
				}
                $this->user=$user; 
                return TRUE;
            }
            return false;
            

        }
        return false;

    }
    
}
