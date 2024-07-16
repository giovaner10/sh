<?php

	include_once '../incVerificaSessao.php';
	
	extract($_REQUEST);
	
	$T = new Template($template_dir."/cadastros_clientes.html");
	
	// Definindo tpldir
	$T -> Set("tpldir",substr($template_dir,5));
	
	// Colocando o titulo na pagina
	$T -> Set("Clientes_Titulo","Cadastro de Clientes");
	$T -> Show("Clientes_Titulo");

	// Definindo os parametros da acao do Flexigrid
	$T -> Show("Clientes_Acao");
	
	// Definindo Flexigrid
	$arrParametros = array ( "Grid_Id"        => "gridClientes", 
							 "Grid_Titulo"    => "Lista de Clientes", 
							 "Grid_Tabela"    => "cad_clientes", 
							 "Grid_Campos"    => "id,nome,cpf,cnpj,fone,CASE status WHEN 0 THEN \'Bloqueado\' WHEN 1 THEN \'Ativo\' END AS status", 
							 "Grid_Condicao"  => "", 
							 "Grid_SortName"  => "id", 
							 "Grid_SortOrder" => "asc",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "630",
							 "Grid_Height" 	  => "350" );
	
	$arrColunas[] = array ( "", "id", "28", "center" );
	$arrColunas[] = array ( "Nome", "nome", "170", "left" );
	$arrColunas[] = array ( "CPF", "cpf", "90", "left" );
	$arrColunas[] = array ( "CNPJ", "cnpj", "90", "left" );
	$arrColunas[] = array ( "Telefone", "fone", "90", "left" );
	$arrColunas[] = array ( "Status", "status", "60", "left" );
	
	$arrBusca[] = array ( "Nome", "nome", "true" );
	$arrBusca[] = array ( "CPF", "cpf", "false" );
	$arrBusca[] = array ( "CNPJ", "cnpj", "false" );

	$arrBotoes = array("Cadastrar","Editar","Bloquear","Desbloquear");

	$verBotoes = TRUE;
	
	$HTML -> grid($arrBotoes, $arrParametros, $arrColunas, $arrBusca, $verBotoes);
	
?>