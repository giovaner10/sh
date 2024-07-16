<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profissional extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
    * LISTA OS DADOS DO POROFISSIONAL
    */
    public function get_profissional($condicao, $colunas='*'){
        return $query = $this->db->select($colunas)
        ->where($condicao)
        ->get('showtecsystem.profissionais')->row();
    }

}
