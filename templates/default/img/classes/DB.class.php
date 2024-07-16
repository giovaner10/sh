<?php

/*
 * Arquivo necess�rio com os dados
 * de acesso ao banco de dados, entre outras
 * informa��es 
 */
require_once 'cfg.php';

/**
 * 
 * @author ATM
 * Classe db.class.php
 *
 */
class DB{
	/**
	 * Atributos da Classe
	 */
	public $conexao;
	public $resultado;
	public $banco;
	public $sql;
	
	/**
	 * M�todo construtor
	 */
	function __construct($banco = 'mysql'){
		
		$this->banco = $banco;
		
		switch ($banco) {
			case "mysql":
				global $hst,$usr,$pwd,$dbs;
				$this->conexao = mysql_connect($hst,$usr,$pwd) or die ("ERRO AO CONECTAR NO MYSQL: ". mysql_error());
				mysql_select_db($dbs,$this->conexao);
			break;
			
			case "oracle":
				global $hst_o,$usr_o,$pwd_o,$dbs_o;
				$this->conexao = oci_connect($usr_o,$pwd_o,$dbs_o);
				if (!$this->conexao) die ("ERRO AO CONECTAR NO ORACLE: ". htmlentities(oci_error()));
			break;			
		}

	}
	
	/**
	 * M�todo para EXECUTAR uma QUERY ESPECIAL
	 */
	function query($query) {
		$this->execute("diversas tabelas", $query,"Executar Query Especial");
	}	
	
	/**
	 * M�todo para INSERIR dados
	 */
	function insertTab($tab, $campos) {
		$declar = "INSERT INTO $tab VALUES ($campos)";
		$this->execute($tab, $declar,"INSERIR");
	}
        
        	
	/**
	 * M�todo para INSERIR dados ou EDITAR, onDuplicate
	 */
	function onDuplicateKey($tab, $campos, $valuesIns, $camposUpdate) {
		$declar = "INSERT INTO $tab ($campos) VALUES ($valuesIns) ON DUPLICATE KEY UPDATE $camposUpdate";
		$this->execute($tab, $declar,"INSERIR/UPDATE (onDuplicateKey)");
	}
		
	/**
	 * M�todo para COLETAR dados
	 */
	function selectTab($tab, $campos, $condicao = "") {
		$declar = "SELECT $campos FROM $tab $condicao";
		$this->execute($tab, $declar,"COLETAR");
		//echo $declar."<br>";		
	}	
	
	/**
	 * M�todo para APAGAR dados
	 */
	function deleteTab($tab, $condicao) {
		$declar = "DELETE FROM $tab $condicao";
		$this->execute($tab, $declar,"APAGAR");
	}	
	
	/**
	 * M�todo para EDITAR dados
	 */
	function updateTab($tab, $campos, $condicao) {
		$declar = "UPDATE $tab SET $campos WHERE $condicao";
//		echo $declar;
		$this->execute($tab, $declar,"EDITAR");
	}

	/**
	 * M�todo utilizado pelos demais m�todos desta
	 * classe, que executa os comandos de acordo com
	 * o banco de dados escolhido
	 * @param $declar
	 * @return $this->resultado
	 */
	function execute($tab, $declar, $acao) {
		$this->sql=$declar;		
		switch ($this->banco) {
			case "mysql":
				$this->resultado = mysql_query($declar) or die ("Erro ao ". $acao ." dados em [". $tab ."]: ". mysql_error());
			break;
			case "oracle":
				$stmt = oci_parse($this->conexao, $declar);
				oci_execute($stmt, OCI_DEFAULT) or die ("Erro ao ". $acao ." dados em [". $tab ."]: ". oci_error());
				$this->resultado = $stmt;
			break;			
		}
	}
	
	/**
	 * M�todo que executa o Fetch Array, de acordo
	 * com o banco de dados escolhido
	 * @param $dados
	 * @return regitros separados em campos (fetch_array)
	 */
	function fetchArray($dados) {
		switch ($this->banco) {
			case "mysql":
				return mysql_fetch_array($dados);
			break;
			case "oracle":
				return oci_fetch_array($dados);
			break;			
		}
	}
	
	/**
	 * M�todo que executa o Num Rows, de acordo
	 * com o banco de dados escolhido
	 * @param $dados
	 * @return n�mero de registros (num_rows)
	 */
	function numRows($dados) {
		switch ($this->banco) {
			case "mysql":
				return mysql_num_rows($dados);
			break;
			case "oracle":
				return oci_num_rows($dados);
			break;			
		}
	}
	
	/**
	 * M�todo que executa o Num Fields, de acordo
	 * com o banco de dados escolhido
	 * @param $dados
	 * @return n�mero de registros (num_fields)
	 */
	function numFields($dados) {
		switch ($this->banco) {
			case "mysql":
				return mysql_num_fields($dados);
			break;
			case "oracle":
				return false;
			break;			
		}
	}	

		
	/**
	 * M�todo que executa o Field Name, de acordo
	 * com o banco de dados escolhido
	 * @param $dados
	 * @param $i - iterator
	 * @return nome do campo (field_name)
	 */
	function fieldName($dados, $i) {
		switch ($this->banco) {
			case "mysql":
				return mysql_field_name($dados,$i);
			break;
			case "oracle":
				return false;
			break;			
		}
	}	
	
	/**
	 * M�todo que executa o InsertId, de acordo
	 * com o banco de dados escolhido
	 * @return id gerado pelo �ltimo insert 
	 */
	function insertId() {
		switch ($this->banco) {
			case "mysql":
				return mysql_insert_id();
			break;
			case "oracle":
				return false;
			break;			
		}
	}
}
?>