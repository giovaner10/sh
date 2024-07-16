<?php

	include_once '../incVerificaSessao.php';
	
	extract($_REQUEST);
        
	$T = new Template($template_dir."/upload.php");
	$ida = "Relatorios";
	// Definindo tpldir
	$T -> Set("tpldir",$template_dir);
	
	// Colocando o titulo na pagina
	$T -> Set("Upload_Titulo","Biblioteca Virtual");
	$T -> Show("Upload_Titulo");
        
	// Definindo os parametros da acao do Flexigrid
        $T -> Set("ida",$ida);
        $T -> Show("Upload_ID");
        $T -> Show("Upload_Acao");
	
	// Definindo Flexigrid
	$arrParametros = array ( "Grid_Id"        => "gridUpload", 
							 "Grid_Titulo"    => "Lista da Pasta > $ida <", 
							 "Grid_Tabela"    => "arquivos", 
							 "Grid_Campos"    => "id,arquivo,descricao,pasta", 
							 "Grid_Condicao"  => "pasta=$ida", 
							 "Grid_SortName"  => "id", 
							 "Grid_SortOrder" => "asc",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "630",
							 "Grid_Height" 	  => "350" );
	
	$arrColunas[] = array ( "", "id", "28", "center" );
	$arrColunas[] = array ( "Arquivo", "arquivo", "170", "left" );
	$arrColunas[] = array ( "Descrição", "descricao", "390", "left" );
	
	$arrBusca[] = array ( "Arquivo", "arquivo", "true" );
	$arrBusca[] = array ( "Descição", "descricao", "true" );

	$arrBotoes = array("Enviar","Baixar");

	$verBotoes = TRUE;
	
	$HTML -> grid($arrBotoes, $arrParametros, $arrColunas, $arrBusca, $verBotoes);
	
?>