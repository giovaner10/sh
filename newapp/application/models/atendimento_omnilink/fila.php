<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fila extends CI_Model {
	
	public function __construct(){
		parent::__construct(); 
	}
	
	public function listar($condicao = [], $colunas = '*') {
    return $this->db
      ->select($colunas)
      ->where($condicao)
			->get('atendimento_omnilink.filas')
			->result();
  }

	public function buscar($id, $colunas = '*') {
		return $this->db
			->select($colunas)
			->where('id', $id)
			->get('atendimento_omnilink.filas')
			->row();
	}

	public function cadastrar($dados) {
		$this->db->insert('atendimento_omnilink.filas', $dados);
		return $this->db->insert_id();
	}

	public function editar($id, $dados) {
		return $this->db->update('atendimento_omnilink.filas', $dados, ['id' => $id]);
	}

	public function alterarStatus($id, $status) {
		$this->db->where('id', $id);
		return $this->db->update('atendimento_omnilink.filas', ['status' => $status]);
	}

}