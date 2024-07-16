<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Produto extends CI_Model {
	
	public function __construct(){
		parent::__construct(); 
	}
	
	public function buscar($id, $colunas = '*') {
		return $this->db
			->select($colunas)
			->where('id', $id)
			->get('portal_compras.produtos')
			->row();
	}
	
	public function listar($condicao = [], $colunas = '*' ) {
    return $this->db
      ->select($colunas)
      ->where($condicao)
      ->get('portal_compras.produtos')
      ->result();
  }

	public function buscarPelaDescricao($colunas = '*', $termoConsulta, $codigoEmpresa) {
		$sql = "SELECT {$colunas}
      FROM portal_compras.produtos
			WHERE status = 'ativo' AND codigo_empresa = '{$codigoEmpresa}' AND descricao LIKE '%".$termoConsulta."%'
			ORDER BY descricao ASC";

		$query = $this->db->query($sql);
		return $query->result();
	}

}