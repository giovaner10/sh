<?php

	include_once '../incVerificaSessao.php';
	
	$T = new Template($template_dir."/os_troca.html");
	
	// Definindo tpldir
	$T -> Set("tpldir",substr($template_dir,6));
	
	// Colocando o titulo na pagina
	$T -> Set("OS_Titulo","Contratos INSTALADOS");
	$T -> Show("OS_Titulo");

	// Definindo os parametros da acao do Flexigrid
	$T -> Show("OS_Instalacao_Acao");
	
	// Definindo Flexigrid
	$arrParametros = array ( "Grid_Id"        => "gridContratos", 
							 "Grid_Titulo"    => "Lista de Contratos INSTALADOS", 
							 "Grid_Tabela"    => "contratos c, cad_clientes cl", 
							 "Grid_Campos"    => "c.id,cl.nome,c.quantidade_veiculos,date_format(c.data_cadastro, \'%d/%m/%Y %Hh%i\') AS data,CASE c.status WHEN 0 THEN \'Cadastrado\' WHEN 1 THEN \'OS\' WHEN 2 THEN \'Ativo\' WHEN 3 THEN \'Cancelado\' END AS status", 
							 "Grid_Condicao"  => "c.id_cliente = cl.id AND c.status = 2", 
							 "Grid_SortName"  => "c.id", 
							 "Grid_SortOrder" => "asc",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "630",
							 "Grid_Height" 	  => "350" );
	
	$arrColunas[] = array ( "Contrato", "c.id", "60", "center" );
	$arrColunas[] = array ( "Cliente", "cl.nome", "280", "left" );
	$arrColunas[] = array ( "Ve&iacute;culos", "c.quantidades_veiculos", "60", "center" );
	$arrColunas[] = array ( "Data Cadastro", "data", "100", "center" );
	$arrColunas[] = array ( "Status", "status", "60", "centar" );
	
	$arrBusca[] = array ( "Contrato", "id", "true" );
	$arrBusca[] = array ( "Cliente", "cl.nome", "false" );

	$arrBotoes = array("Gerar OS");

	$verBotoes = TRUE;
	
	$HTML -> grid($arrBotoes, $arrParametros, $arrColunas, $arrBusca, $verBotoes);

?>