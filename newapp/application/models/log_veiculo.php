<?php

date_default_timezone_set('America/Recife');

if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Log_veiculo extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		
	}

	public function get_veiculo_log($placa = false) {
		if(!$placa) {
			$this->db->select('*');
			$this->db->from('showtecsystem.veiculo_log');
			$query = $this->db->get();
		} else {
			$this->db->select('*');
			$this->db->from('showtecsystem.veiculo_log');
			$this->db->where('placa', $placa);
			$query = $this->db->get();
		}

		if ($query->num_rows()) 
			return $query->result();
		return false;
	}

	public function add($dados) {
		return $this->db->insert('showtecsystem.veiculo_log', $dados);
	}
}