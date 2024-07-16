<?php

	include_once '../incVerificaSessao.php';
	
	extract($_REQUEST);
	$filename = substr($template_dir,3)."/cadastros_veiculo.html";
	if (file_exists($filename)){
            $T = new Template(substr($template_dir,3)."/cadastros_veiculo.html");
        }
        else
        {
            $T = new Template(substr($template_dir,0)."/cadastros_veiculo.html");
        }
	
	// Definindo tpldir
	$T -> Set("tpldir",substr($template_dir,6));
	
	// Colocando o titulo na pagina
	$T -> Set("Veiculos_Titulo","Cadastro de Veiculos");
	//$T -> Show("Veiculos_Titulo");

	// Definindo os parametros da acao do Flexigrid
        $T -> Set("cont",$_SESSION['contrato']);
	$T -> Show("Veiculos_Acao");
	
	// Definindo Flexigrid
	$arrParametros = array ( "Grid_Id"        => "gridVeiculos", 
							 "Grid_Titulo"    => "Lista de Veículos", 
							 "Grid_Tabela"    => "contratos_veiculos e LEFT JOIN cad_equipamentos c ON e.id = c.integrado", 
							 "Grid_Campos"    => "e.id, e.placa, e.id_contrato, c.marca, c.serial, CASE e.status WHEN 0 THEN \'Bloqueado\' WHEN 1 THEN \'Cadastrado\' WHEN 2 THEN \'Em teste\' WHEN 3 THEN \'Em transito - OS\' WHEN 4 THEN \'Em transito - Instalador\' WHEN 5 THEN \'Em uso\' WHEN 6 THEN \'Em manutenção\' END AS status", 
							 "Grid_Condicao"  => "e.id_cliente=".$_SESSION['cliente'], 
							 "Grid_SortName"  => "id", 
							 "Grid_SortOrder" => "asc",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "630",
							 "Grid_Height" 	  => "350" );
	
	$arrColunas[] = array ( "", "id", "28", "center" );
	$arrColunas[] = array ( "Placa", "placa", "60", "left" );
	$arrColunas[] = array ( "Contrato", "id_contrato", "55", "left" );
	$arrColunas[] = array ( "Equipamento", "modelo", "100", "left" );
        $arrColunas[] = array ( "Serial", "serial", "100", "left" );
	$arrColunas[] = array ( "Status", "status", "115", "left" );
	
	
	$arrBusca[] = array ( "Placa", "e.placa", "true" );
	$arrBusca[] = array ( "Contrato", "e.id_contrato", "false" );
        $arrBusca[] = array ( "Serial", "c.serial", "true" );
        $arrBusca[] = array ( "Equipamento", "c.modelo", "true" );

	$arrBotoes = array("Cadastrar Veiculos","Editar","Voltar");

	$verBotoes = TRUES;
	
	$HTML -> grid($arrBotoes, $arrParametros, $arrColunas, $arrBusca, $verBotoes);
	
?>