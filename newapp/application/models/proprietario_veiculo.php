<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proprietario_veiculo extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
    * LISTA OS PROPRIETARIOS
    */
    public function get_proprietario($condicao, $colunas='*'){
        return $query = $this->db->select($colunas)
        ->where($condicao)
        ->get('showtecsystem.proprietarios_veiculos')->row();
    }

}
