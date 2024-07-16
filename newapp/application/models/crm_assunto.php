<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class crm_assunto extends CI_Model {

	public function __construct() {
		parent::__construct();
        // $this->db = $this->load->database('default', TRUE);
	}

    public function list_assuntos(){
        $query = $this->db->select('id_assunto, assunto')
                ->order_by('assunto', 'ASC')
                ->get('systems.crm_assuntos');

        if($query->num_rows()){
            return $query->result();
        }
        return false;
    }

	public function get_assunto($id_assunto){
        $query = $this->db->select('assunto')
				->where(array('id_assunto' => $id_assunto))
                ->get('systems.crm_assuntos');

        if($query->num_rows()){
            return $query->row();
        }
        return false;
    }

}
