<?php

	include_once '../incVerificaSessao.php';
	
	extract($_REQUEST);
	
	$T = new Template($template_dir."/cadastros_instaladores.html");
	
	// Definindo tpldir
	$T -> Set("tpldir",substr($template_dir,6));
	
	// Colocando o titulo na pagina
	$T -> Set("Instaladores_Titulo","Cadastro de Instaladores");
	$T -> Show("Instaladores_Titulo");

	// Definindo os parametros da acao do Flexigrid
	$T -> Show("Instaladores_Acao");
	
	// Definindo Flexigrid
	$arrParametros = array ( "Grid_Id"        => "gridInstaladores", 
							 "Grid_Titulo"    => "Lista de Instaladores", 
							 "Grid_Tabela"    => "cad_instaladores", 
							 "Grid_Campos"    => "id,nome,fone,cel,email,CASE status WHEN 0 THEN \'Bloqueado\' WHEN 1 THEN \'Ativo\' END AS status", 
							 "Grid_Condicao"  => "", 
							 "Grid_SortName"  => "id", 
							 "Grid_SortOrder" => "asc",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "630",
							 "Grid_Height" 	  => "350" );
	
	$arrColunas[] = array ( "", "id", "28", "center" );
	$arrColunas[] = array ( "Nome", "nome", "140", "left" );
	$arrColunas[] = array ( "Telefone", "fone", "80", "left" );
	$arrColunas[] = array ( "Celular", "cel", "80", "left" );
	$arrColunas[] = array ( "E-mail", "email", "160", "left" );
	$arrColunas[] = array ( "Status", "status", "60", "left" );
	
	$arrBusca[] = array ( "Nome", "nome", "true" );

	$arrBotoes = array("Cadastrar","Editar","Bloquear","Desbloquear");

	$verBotoes = TRUE;
	
	$HTML -> grid($arrBotoes, $arrParametros, $arrColunas, $arrBusca, $verBotoes);
	
?>