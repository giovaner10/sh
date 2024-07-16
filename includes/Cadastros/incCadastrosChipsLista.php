<?php

	include_once '../incVerificaSessao.php';
	
	extract($_REQUEST);
	
	$T = new Template($template_dir."/cadastros_chips.html");
	
	// Definindo tpldir
	$T -> Set("tpldir",substr($template_dir,6));
	
	// Colocando o titulo na pagina
	$T -> Set("Chips_Titulo","Cadastro de Chips");
	$T -> Show("Chips_Titulo");

	// Definindo os parametros da acao do Flexigrid
	$T -> Show("Chips_Acao");
	
	// Definindo Flexigrid
	$arrParametros = array ( "Grid_Id"        => "gridChips", 
							 "Grid_Titulo"    => "Lista de Chips", 
							 "Grid_Tabela"    => "cad_chips c LEFT JOIN cad_equipamentos e ON c.id_equipamento = e.id", 
							 "Grid_Campos"    => "c.id,c.numero,c.ccid,c.operadora,CASE c.status WHEN 0 THEN \'Cadastrado\' WHEN 1 THEN \'Habilitado\' WHEN 2 THEN \'Em uso\' WHEN 3 THEN \'Cancelado\' END AS status, e.serial AS modulo", 
							 "Grid_Condicao"  => "", 
							 "Grid_SortName"  => "id", 
							 "Grid_SortOrder" => "asc",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "630",
							 "Grid_Height" 	  => "350" );
	
	$arrColunas[] = array ( "", "id", "28", "center" );
	$arrColunas[] = array ( "N&uacute;mero", "numero", "80", "left" );
	$arrColunas[] = array ( "CCID", "ccid", "140", "left" );
	$arrColunas[] = array ( "Operadora", "operadora", "80", "left" );
	$arrColunas[] = array ( "Status", "status", "60", "left" );
	$arrColunas[] = array ( "M&oacute;dulo", "modulo", "100", "left" );
	
	$arrBusca[] = array ( "CCID", "ccid", "true" );
	$arrBusca[] = array ( "N&uacute;mero", "numero", "false" );
	$arrBusca[] = array ( "M&oacute;dulo", "e.serial", "false" );

	$arrBotoes = array("Cadastrar","Editar");

	$verBotoes = TRUE;
	
	$HTML -> grid($arrBotoes, $arrParametros, $arrColunas, $arrBusca, $verBotoes);
	
?>