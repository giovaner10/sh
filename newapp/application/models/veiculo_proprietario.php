<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Veiculo_proprietario extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
    * LISTA OS VEICULOS
    */
    public function get_veiculo($condicao, $colunas='*'){
        return $query = $this->db->select($colunas)
        ->where($condicao)
        ->get('showtecsystem.veiculos_proprietarios')->row();
    }  

}
