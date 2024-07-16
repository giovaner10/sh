<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_logistica extends CI_Model {
	private $bLocal;
	function __construct(){
		parent::__construct();
		$this->bLocal = $this->load->database('default', true);
	}

	public function inserirLog($dados) {
		$this->bLocal->insert('log_logistica', $dados);
		return $this->bLocal->affected_rows();
	}
}
