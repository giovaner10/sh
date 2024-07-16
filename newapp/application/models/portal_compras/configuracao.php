<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Configuracao extends CI_Model {
	
	public function __construct(){
		parent::__construct(); 
	}

	public function buscar() {
		return $this->db
			->select('*')
			->get('portal_compras.configuracoes')
			->row();
	}

	public function cadastrar($dados) {
		$this->db->insert('portal_compras.configuracoes', $dados);
		return $this->db->insert_id();
	}

	public function editar($id, $dados) {
		$this->db->where('id', $id);
		return $this->db->update('portal_compras.configuracoes', $dados);
	}

}