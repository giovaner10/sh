<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contrato_eptc extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_total_contratos()
	{
		$query = $this->db->get('showtecsystem.contratos_eptc');
		return $query->num_rows();
	}

	public function get_contratos($limit, $offset){

		$this->db->limit($offset,$limit);
		$this->db->order_by('id', 'asc');

		$query = $this->db->get('showtecsystem.contratos_eptc');

		if ($query->num_rows()) {
			
			return $query->result();

		}

		return false;
	}

	public function get_contratos_paginated($limit, $offset, $coluna = null, $valor = null){

		if ($coluna) {
			$this->db->like($coluna, $valor);
		}

		$this->db->limit($limit, $offset);
		$this->db->order_by('id', 'asc');

		$query = $this->db->get('showtecsystem.contratos_eptc');

		if ($query->num_rows()) {
			
			return $query->result();

		}

		return false;
	}

	public function get_numero_contratos($coluna = null, $valor = null)
	{
		if ($coluna) {
			$this->db->like($coluna, $valor);
		}
		
		$query = $this->db->get('showtecsystem.contratos_eptc');
		return $query->num_rows();
	}

	public function get_contrato($id_contrato){

		$this->db->where('id', $id_contrato);

		$query = $this->db->get('showtecsystem.contratos_eptc');

		if ($query->num_rows()) {
			
			return $query->result();

		}

		return false;
	}

	public function listar_pesquisa_contratos($pesquisa){

		$this->db->like($pesquisa['coluna'], $pesquisa['palavra']);
		
		$query = $this->db->get('showtecsystem.contratos_eptc');

		if ($query->num_rows()) {
		
			return $query->result();
		}
		return false;
		
	}

	public function get_placa($prefixo)
	{
		$this->db->select('placa');
		$this->db->where('prefixo', $prefixo);

		$query = $this->db->get('showtecsystem.contratos_eptc');

		if ($query->num_rows()) {
			foreach ($query->result() as $contrato) {
				$placa = $contrato->placa;
			}
			return $placa;
		}

		return false;
	}

	public function salvar_arquivo($dados)
	{
		if($this->db->insert('showtecsystem.arquivos',$dados)) {
			return true;
		}else{
			return false;
		}
		
	}

	public function buscar_contratos()
	{
		$query = $this->db->get('showtecsystem.contratos_eptc');

		if ($query->num_rows()) {
			
			return $query->result();

		}

		return false;
	}

	public function corrigir_contrato($prefixo, $placa)
	{

		$dados = array(
			'ndoc' => $prefixo
		);

		$this->db->where('ndoc', $placa);

		if ($this->db->update('showtecsystem.arquivos', $dados)) {
			return true;
		}

		return false;
	}
	
}


