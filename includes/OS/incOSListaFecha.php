<?php

	include_once '../incVerificaSessao.php';
	
	$T = new Template($template_dir."/os.html");
	$_SESSION['nom_cli'] = '';
        $_SESSION['tipo'] = '';
	// Definindo tpldir
	$T -> Set("tpldir",substr($template_dir,6));
	
	// Colocando o titulo na pagina
	$T -> Set("OS_Titulo","Fechar Ordens de Servi&ccedil;os");
	$T -> Show("OS_Titulo");

	// Definindo os parametros da acao do Flexigrid
	$T -> Show("OS_Instalacao_Acao");
	
	// Definindo Flexigrid
	$arrParametros = array ( "Grid_Id"        => "gridContratos", 
							 "Grid_Titulo"    => "Lista de Ordens de Servi&ccedil;os", 
							 "Grid_Tabela"    => "os o, cad_clientes c",
							 "Grid_Campos"    => "o.id, CASE o.tipo_os WHEN 1 THEN \'Instalacao\' WHEN 2 THEN \'Manutencao\' WHEN 3 THEN \'Troca\' WHEN 4 THEN \'Retirada\' END AS tipo, c.nome, o.id_contrato, o.quantidade_equipamentos, date_format(o.data_cadastro, \'%d/%m/%Y %Hh%i\') AS data, CASE o.status WHEN 0 THEN \'Cadastrado\' WHEN 1 THEN \'Fechado\' END AS status", 
							 "Grid_Condicao"  => "o.id_cliente = c.id AND o.status=0", 
							 "Grid_SortName"  => "o.id", 
							 "Grid_SortOrder" => "asc",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "730",
							 "Grid_Height" 	  => "350" );
	
	$arrColunas[] = array ( "OS", "o.id", "20", "center" );
        $arrColunas[] = array ( "Tipo", "tipo", "55", "center" );
	$arrColunas[] = array ( "Cliente", "c.nome", "260", "left" );
        $arrColunas[] = array ( "Contrato", "o.id_contrato", "60", "center" );
	$arrColunas[] = array ( "Ve&iacute;culos", "c.quantidade_equipamentos", "55", "center" );
	$arrColunas[] = array ( "Data Cadastro", "data", "100", "center" );
	$arrColunas[] = array ( "Status", "status", "60", "center" );
	
	$arrBusca[] = array ( "OS", "o.id", "true" );
	$arrBusca[] = array ( "Cliente", "c.nome", "false" );

	$arrBotoes = array("Fechar");

	$verBotoes = TRUE;
	
	$HTML -> grid($arrBotoes, $arrParametros, $arrColunas, $arrBusca, $verBotoes);

?>