<?php
class Log {
	
	public $log_id;
	public $log_ip;
	public $log_usuario;
	public $log_data;
	public $log_acao;
	
	/**
	 * Metodo construtor
	 */
	function __construct(){
		global $_SESSION;
		$this->log_ip = $_SERVER['REMOTE_ADDR'];
		$this->log_usuario = $_SESSION["usuario_login"];
		$this->log_data = date('Y-m-d H:i:s');
	}
	
	/**
	 * Metodo para INSERIR dados
	 * @param $db - banco a ser utilizado
	 * @param $acao - acao a ser efetuado
	 */
	function insertLog($db,$acao) {
		// Insere dados do log
		$sql = "NULL, '".$this->log_ip."', '".$this->log_usuario."', '".$this->log_data."', '".addslashes(utf8_decode($acao))."'";
		// Inserindo dados na tabela log
		$db->insertTab('log',$sql);
	}
	
        	public static function insertLogP($db,$acao) {
		// Insere dados do log
		$sql = "NULL, '".$this->log_ip."', '".$this->log_usuario."', '".$this->log_data."', '".addslashes(utf8_decode($acao))."'";
		// Inserindo dados na tabela log
		$db->insertTab('log',$sql);
	}
}
?>