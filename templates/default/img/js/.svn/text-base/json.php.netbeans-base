<?php

	// Verificando se a sessao esta aberta
// Caso a sessao esteja fechada,
// voltar a pagina de login.

session_start();

if(!isset($_SESSION['l0g4d0'])){
	
	// Direcionando para o LOGIN
	header("Location: ../login.php");
}
	
	// Incluindo a classe DB
	require_once '../classes/DB.class.php';
	$DB = new DB();


	// Extraindo as vari�veis do $_POST
	extract($_POST);

	// Verificando se existe solicita��o de ordena��o
	if ($sortname){
		if (!$sortorder) $sortorder = 'desc';
		$sort = "ORDER BY $sortname $sortorder";
	} else $sort = "";
	
	// Definindo qtdade de p�ginas
	if (!$page) $page = 1;
	// Definindo qtdade de registros por p�gina
	if (!$rp) $rp = 10;
	
	// Definindo o par�metro LIMIT da query
	$start = (($page-1) * $rp);
	$limit = "LIMIT $start, $rp";
	
	// Caso haja conte�do para pesquisa ($query)
	// definir a vair�vel $where
	$where = "";
	if ($query) {
		($condicao) ? $and = "AND": $and = "WHERE";
		
		if($qtype == "data"){
			$query = implode(preg_match("~\/~", $query) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $query) == 0 ? "-" : "/", $query)));
		}
		
		$where = " $and $qtype LIKE '%$query%' ";
	}
	
	// Definindo o a vari�vel $condicao
	if ($condicao) $condicao = " WHERE ". stripslashes($condicao);
	else $condicao = "";
	
	// Fazendo a consulta no BD SEM os limites (Total de Registros)
	$DB -> selectTab("$tabela",stripslashes($campos),"$condicao $where");
	$d = $DB -> resultado;
	
	// Verificando qtdade de registros encontrados
	$total = $DB -> numRows($d);
	
	// Fazendo a consulta no BD COM os limites
	$DB -> selectTab("$tabela",stripslashes($campos),"$condicao $where $sort $limit");
	$d = $DB -> resultado;	
	
	// Verificando qtdade de campos
	$qtdCampos = $DB -> numFields($d);
	
	// Identificando os campos da consulta
	for ($i = 0; $i < $qtdCampos; $i++) { 
		$fields[] = $DB->fieldName($d,$i);
	}


	// Preparando o arquivo JSon
	$json = "";
	$json .= "{\n";
	$json .= "\"page\": \"$page\",\n";
	$json .= "\"total\": \"$total\",\n";
	$json .= "\"rows\":	";

	while($dados = $DB -> fetchArray($d)){
		$linha = "";
		foreach ($fields as $field) { 
			$linha[] = htmlentities( $dados[ $field ] );
		}			
		$arr[] = array("cell" => $linha);
	}
	
	$json .= json_encode($arr);
	$json .= "\n}";
	
	// Criando o arquivo JSon
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
	header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
	header("Cache-Control: no-cache, must-revalidate" ); 
	header("Pragma: no-cache" );
	header("Content-type: text/x-json");
	echo $json;
?>