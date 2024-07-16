<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Condicao_pagamento extends CI_Model {
	
	public function __construct(){
		parent::__construct(); 
	}

	public function listar($condicao = [], $colunas = '*' ) {
    return $this->db
      ->select($colunas)
      ->where($condicao)
      ->get('portal_compras.condicao_pagamento')
      ->result();
  }

}