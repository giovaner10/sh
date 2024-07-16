<?php

	include_once '../incVerificaSessao.php';
	
	extract($_REQUEST);
	
	//$T = new Template(substr($template_dir,3)."/os.html");
        $filename = substr($template_dir,3)."/os.html";
        if (file_exists($filename)){
            $T = new Template(substr($template_dir,3)."/os.html");
            $T -> Set("tpldir",substr($template_dir,6));
        }else{
        $T = new Template("../../templates/default/os.html");
        $T -> Set("tpldir",substr($template_dir,0));
        }
	
	// Definindo tpldir
	//$T -> Set("tpldir",substr($template_dir,6));
	
	// Colocando o titulo na pagina
	$T -> Set("OS_Titulo","Cliente - ".$_SESSION['nom_cli']);
        $T -> Set("OS_Cliente",$_SESSION['nom_cli']);
        $T -> Show("OS_Titulo");

        // Colocando o sub-titulo na pagina
	$T -> Set("OS_SubTitulo_Id",$id);
        $T -> Set("OS_SubTitulo_Nome",$_SESSION['tipo']);
        $T -> Show("OS_SubTitulo");

	// Definindo os parametros da acao do Flexigrid
        $T -> Set("os",$id);
	$T -> Show("OS_Instalacao_Acao");
	
	// Definindo Flexigrid
	$arrParametros = array ( "Grid_Id"        => "gridEquipamentos", 
							 "Grid_Titulo"    => "Lista de M&oacute;dulos", 
							 "Grid_Tabela"    => "cad_equipamentos a, os_equipamentos e", 
							 "Grid_Campos"    => "e.id,a.marca,a.serial,a.placa, CASE a.status WHEN 1 THEN \'Devolvido Empresa\' WHEN 2 THEN \'Devolvido Teste\' WHEN 3 THEN \'Em transito - OS\' WHEN 4 THEN \'Devolvido Instalador\' WHEN 5 THEN \'Em Uso\' END AS status,e.id_os", 
							 "Grid_Condicao"  => "e.id_equipamento=a.id AND e.id_os=$id", 
							 "Grid_SortName"  => "a.serial",
							 "Grid_SortOrder" => "asc",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "630",
							 "Grid_Height" 	  => "350" );
	
	$arrColunas[] = array ( "", "id", "28", "center" );
        $arrColunas[] = array ( "Marca", "marca", "80", "left" );
	$arrColunas[] = array ( "Serial", "serial", "150", "left" );
	$arrColunas[] = array ( "Placa", "placa", "80", "left" );
        $arrColunas[] = array ( "Status", "status", "115", "left" );
        
	$arrBusca[] = array ( "Serial", "serial", "true" );
        $arrBusca[] = array ( "Placa", "placa", "false" );

	$arrBotoes = array("Instalado","Devolver Instalador","Devolver Empresa","Devolver Teste","Digitaliza");

	$verBotoes = TRUE;
	
	$HTML -> grid($arrBotoes, $arrParametros, $arrColunas, $arrBusca, $verBotoes);
	
?>