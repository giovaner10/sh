<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chip extends CI_Model {
	private $bLocal;
	function __construct(){
		parent::__construct();
		$this->bLocal = $this->load->database('default', true);
	}

	public function get_chips($coluna, $chave) {
		$this->bLocal->where($coluna, $chave);
		$this->bLocal->order_by('data_cadastro', 'desc');
		$query = $this->bLocal->get('cad_chips');
		if ($query->num_rows()) {
			return $query->result();
		}else{
			return FALSE;
		}
	}
}
