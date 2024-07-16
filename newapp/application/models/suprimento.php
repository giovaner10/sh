<?php

date_default_timezone_set('America/Recife');

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Suprimento extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function getSuprimentos($where) {
		$this->db->where($where);
		return $this->db->order_by('data_cadastro', 'desc')->get('showtecsystem.cad_suprimentos')->result();
	}

	public function getSuprimento($id_suprimento) {
		$this->db->where(array('id' => $id_suprimento));
		$retorno  = $this->db->get('showtecsystem.cad_suprimentos')->row();

		return $retorno;
	}

	public function addSuprimento($dados)
	{
		$this->db->insert('showtecsystem.cad_suprimentos', $dados);
		return $this->db->insert_id();
	}

	public function listar_suprimentos_disponiveis($start = 0, $limit = 10, $camp_ord = 'id', $ordem = 'desc', $like=NULL) {
		$query = $this->db->select('serial as text, id')
        ->like('serial', $like)
        ->where('status', 1)
		->get('showtecsystem.cad_suprimentos', $limit, $start);
        return $query->result();
    }
}
