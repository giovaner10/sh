<?php

	include_once '../incVerificaSessao.php';
	
	$T = new Template($template_dir."/meusdados_acessos.html");
	
	$T -> Set("Acessos_Titulo","Meus passos...");
	$T -> Set("template_dir",substr($template_dir,6));
	$T -> Show("Acessos_Titulo");

	// Definindo os ARRAYs para a Grid
	$arrParametros = array ( "Grid_Id"        => "gridAcesso", 
							 "Grid_Titulo"    => "Relat&oacute;rio de Atividades", 
							 "Grid_Tabela"    => "log", 
							 "Grid_Campos"    => "id,login,date_format(data, \'%d/%m/%Y\') AS data, date_format(data, \'%H:%i:%s\') AS horario,acao", 
							 "Grid_Condicao"  => "login = \'".$_SESSION['usuario_login']."\'", 
							 "Grid_SortName"  => "id", 
							 "Grid_SortOrder" => "desc",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "630",
							 "Grid_Height" 	  => "370" );
	
	$arrColunas[] = array ( "", "id", "20", "center" );
	$arrColunas[] = array ( "Login", "login", "80", "center" );
	$arrColunas[] = array ( "Data", "data", "70", "center" );
	$arrColunas[] = array ( "Hor&aacute;rio", "horario", "65", "center" );
	$arrColunas[] = array ( "A&ccedil;&atilde;o", "acao", "385", "left" );
	
	$arrBusca[] = array ( "Data", "data", "true" );
	$arrBusca[] = array ( "Hor&aacute;rio", "horario", "false" );
	$arrBusca[] = array ( "A&ccedil;&atilde;o", "acao", "false" );

	$botoes = "";
	
	// Apresentando o FlexiGrid
	$HTML -> grid($botoes, $arrParametros, $arrColunas, $arrBusca);
	
?>