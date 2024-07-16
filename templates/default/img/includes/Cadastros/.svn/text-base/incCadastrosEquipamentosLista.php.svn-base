<?php

	include_once '../incVerificaSessao.php';
	
	extract($_REQUEST);
	
	$T = new Template($template_dir."/cadastros_equipamentos.html");
	
	// Definindo tpldir
	$T -> Set("tpldir",$template_dir);
	
	// Colocando o titulo na pagina
	$T -> Set("Equipamentos_Titulo","Cadastro de M&oacute;dulos");
	$T -> Show("Equipamentos_Titulo");

	// Definindo os parametros da acao do Flexigrid
	$T -> Show("Equipamentos_Acao");
	
	// Definindo Flexigrid
	$arrParametros = array ( "Grid_Id"        => "gridEquipamentos", 
							 "Grid_Titulo"    => "Lista de M&oacute;dulos", 
							 "Grid_Tabela"    => "cad_equipamentos e LEFT JOIN cad_chips c ON e.id_chip = c.id", 
							 "Grid_Campos"    => "e.id,e.serial,e.modelo,e.marca,CASE e.status WHEN 0 THEN \'Bloqueado\' WHEN 1 THEN \'Cadastrado\' WHEN 2 THEN \'Em teste\' WHEN 3 THEN \'Em transito - OS\' WHEN 4 THEN \'Em transito - Instalador\' WHEN 5 THEN \'Em uso\' WHEN 6 THEN \'Em manutenção\' END AS status, c.ccid AS chip", 
							 "Grid_Condicao"  => "", 
							 "Grid_SortName"  => "id", 
							 "Grid_SortOrder" => "asc",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "630",
							 "Grid_Height" 	  => "350" );
	
	$arrColunas[] = array ( "", "id", "28", "center" );
	$arrColunas[] = array ( "Serial", "serial", "80", "left" );
	$arrColunas[] = array ( "Modelo", "modelo", "110", "left" );
	$arrColunas[] = array ( "Marca", "marca", "80", "left" );
	$arrColunas[] = array ( "Status", "status", "115", "left" );
	$arrColunas[] = array ( "Chip", "chips", "125", "left" );
	
	$arrBusca[] = array ( "Serial", "serial", "true" );
	$arrBusca[] = array ( "Modelo", "modelo", "false" );
	$arrBusca[] = array ( "Marca", "marca", "false" );
	$arrBusca[] = array ( "Chip", "chips", "false" );

	$arrBotoes = array("Cadastrar","Editar");

	$verBotoes = TRUE;
	
	$HTML -> grid($arrBotoes, $arrParametros, $arrColunas, $arrBusca, $verBotoes);
	
?>