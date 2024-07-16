<?php

	include_once '../incVerificaSessao.php';
	        
	extract($_REQUEST);
        $ida = "Usuarios";
	$T = new Template($template_dir."/cadastros_documentos.html");
	
        $T -> Set("ida",$ida);
	// Definindo tpldir
	$T -> Set("tpldir",substr($template_dir,6));
	
	// Colocando o titulo na pagina
	$T -> Set("Documento_Titulo","Biblioteca Virtual");
	$T -> Show("Documento_Titulo");
        
	// Definindo os parametros da acao do Flexigrid
        $T -> Show("Documento_ID");
        $T -> Show("Documento_Acao");
	
	// Definindo Flexigrid
        
        //pasta=$ida
	$arrParametros = array ( "Grid_Id"        => "gridUpload", 
							 "Grid_Titulo"    => "Lista da Pasta > $ida <", 
							 "Grid_Tabela"    => "arquivos", 
							 "Grid_Campos"    => "id,file,descricao", 
							 "Grid_Condicao"  => "pasta=\"$ida\"", 
							 "Grid_SortName"  => "id", 
							 "Grid_SortOrder" => "asc",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "630",
							 "Grid_Height" 	  => "350" );
	
	$arrColunas[] = array ( "", "id", "28", "center" );
	$arrColunas[] = array ( "Arquivo", "file", "170", "left" );
	$arrColunas[] = array ( "Descrição", "descricao", "390", "left" );
	
	$arrBusca[] = array ( "Arquivo", "file", "true" );
	$arrBusca[] = array ( "Descição", "descricao", "true" );

	$arrBotoes = array("Enviar","Baixar");

	$verBotoes = TRUE;
	
	$HTML -> grid($arrBotoes, $arrParametros, $arrColunas, $arrBusca, $verBotoes);
	
?>