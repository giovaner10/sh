<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Centro_custo extends CI_Model {
	
	public function __construct(){
		parent::__construct(); 
	}
	
	public function buscar($id, $colunas = '*') {
		return $this->db
			->select($colunas)
			->where('id', $id)
			->get('portal_compras.centro_custo')
			->row();
	}

	public function buscarPeloCodigo($codigo, $colunas = '*') {
		return $this->db
			->select($colunas)
			->where('codigo', $codigo)
			->get('portal_compras.centro_custo')
			->row();
	}
	
	public function listar($condicao = [], $colunas = '*' ) {
    return $this->db
      ->select($colunas)
      ->where($condicao)
      ->get('portal_compras.centro_custo')
      ->result();
  }

	public function listarPorIds($ids, $colunas = '*') {
		return $this->db
			->select($colunas)
			->where_in('id', $ids)
			->get('portal_compras.centro_custo')
			->result();
	}

	public function buscarPeloCodigoOuDescricao($colunas = '*', $termoConsulta, $codigoEmpresa) {
		$sql = "SELECT {$colunas}
      FROM portal_compras.centro_custo
			WHERE status = 'ativo' AND codigo_empresa = '{$codigoEmpresa}' AND (descricao LIKE '%" . $termoConsulta . "%' OR codigo LIKE '%" . $termoConsulta . "%')
			ORDER BY descricao ASC";

		$query = $this->db->query($sql);
		return $query->result();
	}

}