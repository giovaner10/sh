<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Historico_chamadas extends CI_Model {
	
	public function __construct(){
		parent::__construct(); 
	}
	
	public function listar($condicao = [], $colunas = '*') {
    return $this->db
      ->select($colunas)
      ->where($condicao)
			->get('atendimento_omnilink.historico_chamadas')
			->result();
  }

}