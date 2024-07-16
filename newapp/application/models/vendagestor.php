<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Vendagestor extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @author Rento Silva
	 *  Lista as licenças ativas dos produtos
	*/
	public function listar_licencas_produtos() {
		return $this->db->select('id, produto_id as id_licenca, nome, classificacao_produto')
			->where('status', 'Ativo')
			->get('showtecsystem.cadastro_produtos_licencas')
			->result_array();
	}

}
