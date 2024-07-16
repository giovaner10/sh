<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cotacao extends CI_Model {
	
	public function __construct(){
		parent::__construct(); 
	}
	
	public function buscar($id, $colunas = '*') {
		return $this->db
			->select($colunas)
			->where('id', $id)
			->get('portal_compras.cotacoes')
			->row();
	}

	public function buscarPorIds($ids, $colunas = '*') {
		return $this->db
			->select($colunas)
			->where_in('id', $ids)
			->get('portal_compras.cotacoes')
			->result();
	}

  public function buscarPeloFornecedor($colunas = '*', $termoConsulta) {
		$termoConsultaMinisculo = strtolower($termoConsulta);
		$termoConsultaMaisculo = strtoupper($termoConsulta);

    $sql = "SELECT {$colunas}
      FROM portal_compras.cotacoes
			WHERE JSON_SEARCH(fornecedor, 'one', '{$termoConsultaMinisculo}%') IS NOT NULL
			OR JSON_SEARCH(fornecedor, 'one', '{$termoConsultaMaisculo}%') IS NOT NULL";

		$query = $this->db->query($sql);

		return $query->result();
  }
	
	public function listar($condicao = [], $colunas = '*' ) {
    return $this->db
      ->select($colunas)
      ->where($condicao)
      ->get('portal_compras.cotacoes')
      ->result();
  }

	public function cadastrar($dados) {
		$this->db->insert('portal_compras.cotacoes', $dados);
		return $this->db->insert_id();
	}

	public function editar($id, $dados) {
		$this->db->where('id', $id);
		return $this->db->update('portal_compras.cotacoes', $dados);
	}

	public function alterarStatus($id, $status) {
		$this->db->where('id', $id);
		return $this->db->update('portal_compras.cotacoes', ['status' => $status]);
	}

}