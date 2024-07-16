<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Forma_pagamento extends CI_Model {
	
	public function __construct(){
		parent::__construct(); 
	}
	
	public function buscar($id, $colunas = '*') {
		return $this->db
			->select($colunas)
			->where('id', $id)
			->get('portal_compras.forma_pagamento')
			->row();
	}
	
	public function listar($condicao = [], $colunas = '*' ) {
    return $this->db
      ->select($colunas)
      ->where($condicao)
      ->get('portal_compras.forma_pagamento')
      ->result();
  }

	public function cadastrar($dados) {
		$this->db->insert('portal_compras.forma_pagamento', $dados);
		return $this->db->insert_id();
	}

	public function editar($id, $dados) {
		$this->db->where('id', $id);
		return $this->db->update('portal_compras.forma_pagamento', $dados);
	}

	public function alterarStatus($id, $status) {
		$this->db->where('id', $id);
		return $this->db->update('portal_compras.forma_pagamento', ['status' => $status]);
	}

}