<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Empresa extends CI_Model {
	
	public function __construct(){
		parent::__construct(); 
	}
	
	public function buscar($id, $colunas = '*') {
		return $this->db
			->select($colunas)
			->where('id', $id)
			->get('portal_compras.empresas')
			->row();
	}
	
	public function listar($condicao = [], $colunas = '*' ) {
    return $this->db
      ->select($colunas)
      ->where($condicao)
      ->get('portal_compras.empresas')
      ->result();
  }

	public function listarPorIds($ids, $colunas = '*') {
		return $this->db
			->select($colunas)
			->where_in('id', $ids)
			->get('portal_compras.empresas')
			->result();
	}

}