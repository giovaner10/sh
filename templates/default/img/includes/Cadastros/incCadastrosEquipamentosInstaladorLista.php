<?php

	include_once '../incVerificaSessao.php';
	
	extract($_REQUEST);
	
	$T = new Template($template_dir."/cadastros_equipamentos_instalador.html");
	
	// Definindo tpldir
	$T -> Set("tpldir",$template_dir);
	
	// Colocando o titulo na pagina
	$T -> Set("Equipamentos_Titulo","Estoque do Instalador");
	$T -> Show("Equipamentos_Titulo");

	// Definindo os parametros da acao do Flexigrid
	$T -> Show("Equipamentos_Acao");
	
	// Definindo Flexigrid
	$arrParametros = array ( "Grid_Id"        => "gridEquipamentosInstalador", 
							 "Grid_Titulo"    => "Estoque do Instalador", 
							 "Grid_Tabela"    => "cad_equipamentos_instalador ei RIGHT JOIN cad_instaladores i ON ei.id_instalador = i.id AND ei.status = 1", 
							 "Grid_Campos"    => "i.id, i.nome AS instalador, COUNT(ei.id) AS modulos", 
							 "Grid_Condicao"  => "i.id IS NOT NULL GROUP BY ei.id_instalador", 
							 "Grid_SortName"  => "i.id", 
							 "Grid_SortOrder" => "asc",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "630",
							 "Grid_Height" 	  => "350" );
	
	$arrColunas[] = array ( "", "i.id", "28", "center" );
	$arrColunas[] = array ( "Instalador", "instalador", "250", "left" );
	$arrColunas[] = array ( "M&oacute;dulos", "modulos", "100", "center" );
	
	$arrBusca[] = array ( "Instalador", "instalador", "true" );

	$arrBotoes = array("Editar");

	$verBotoes = TRUE;
	
	$HTML -> grid($arrBotoes, $arrParametros, $arrColunas, $arrBusca, $verBotoes);
	
?>