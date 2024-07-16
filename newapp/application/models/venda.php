<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Venda extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function is_valid_key($key) {
        $query = $this->db->get_where('showtecsystem.chaves_desconto', array('chave' => $key));
        if($query->num_rows > 0)
            return $query->result();
        return false;
    }

    public function create_key($data) {
        $insert = $this->db->insert('showtecsystem.chaves_desconto', $data);
        return $insert;
    }

    public function get_cities($city) {
        $query = $this->db->select('nome, estado')
                ->where('estado', $city)
                ->order_by('nome')
                ->get('showtecsystem.tb_cidade');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

}
