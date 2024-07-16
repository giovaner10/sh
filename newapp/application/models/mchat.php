<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Mchat extends CI_Model {

    public function __construct() {
		parent::__construct();
    }
    
    public function save($dados) {
        // pr($dados);die;
        $this->db->insert('chat_show', $dados);
        if($this->db->affected_rows() > 0)
            return true;
        return false;
    }


}

?>