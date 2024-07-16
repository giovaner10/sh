<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fornecedor extends CI_Model {
	
	public function __construct(){
		parent::__construct(); 
	}
	
	public function buscar($id, $colunas = '*') {
		return $this->db
			->select($colunas)
			->where('id', $id)
			->get('portal_compras.fornecedores')
			->row();
	}

	public function buscarPeloDocumentoOuNome($colunas = '*', $termoConsulta, $codigoEmpresa) {
		$sql = "SELECT {$colunas}
      FROM portal_compras.fornecedores
			WHERE status = 'ativo' AND codigo_empresa = '{$codigoEmpresa}' AND (nome LIKE '%" . $termoConsulta . "%' OR documento LIKE '%" . $termoConsulta . "%')
			ORDER BY nome ASC";

		$query = $this->db->query($sql);
		return $query->result();
	}

}