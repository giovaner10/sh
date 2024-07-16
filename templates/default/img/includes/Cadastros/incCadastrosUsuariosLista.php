<?php

	include_once '../incVerificaSessao.php';
	require_once '../../classes/Usuario.class.php';
	
	extract($_REQUEST);
	
//	$USU = new Usuario();
	$T = new Template($template_dir."/cadastros_usuarios.html");
	
	// Definindo tpldir
	$T -> Set("tpldir",substr($template_dir,6));
	
	// Colocando o titulo na pagina
	$T -> Set("Usuarios_Titulo","Cadastro de Usu&aacute;rios");
	$T -> Show("Usuarios_Titulo");

	// Definindo os parametros da acao do Flexigrid
	$T -> Set("Acao_tabela","usuario");
	$T -> Show("Usuarios_Acao");
	
	// Definindo Flexigrid
	$arrParametros = array ( "Grid_Id"        => "gridUsuarios", 
							 "Grid_Titulo"    => "Lista de Usu&aacute;rios", 
							 "Grid_Tabela"    => "usuario u, usuario_perfil up", 
							 "Grid_Campos"    => "u.id AS id, u.login AS login, u.nome AS nome, up.nome AS perfil, CASE u.status WHEN 0 THEN \'Bloqueado\' WHEN 1 THEN \'Ativo\' END AS status", 
							 "Grid_Condicao"  => "u.perfil = up.id", 
							 "Grid_SortName"  => "id", 
							 "Grid_SortOrder" => "asc",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "630",
							 "Grid_Height" 	  => "350" );
	
	$arrColunas[] = array ( "", "id", "28", "center" );
	$arrColunas[] = array ( "Login", "login", "80", "left" );
	$arrColunas[] = array ( "Nome", "nome", "270", "left" );
	$arrColunas[] = array ( "Perfil", "perfil", "100", "left" );
	$arrColunas[] = array ( "Status", "status", "100", "left" );
	
	$arrBusca[] = array ( "Login", "login", "true" );
	$arrBusca[] = array ( "Nome", "nome", "false" );
	$arrBusca[] = array ( "Perfil", "perfil", "false" );

	$arrBotoes = array("Cadastrar","Editar","Bloquear","Desbloquear");

	$verBotoes = TRUE;
	
	$HTML -> grid($arrBotoes, $arrParametros, $arrColunas, $arrBusca, $verBotoes);
	
?>