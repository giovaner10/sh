<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Especie extends CI_Model {
	
	public function __construct(){
		parent::__construct(); 
	}

	public function listar($colunas = '*', $condicao) {
		return $this->db
			->select($colunas)
			->where($condicao)
			->get('portal_compras.especies')
			->result();
	}

}