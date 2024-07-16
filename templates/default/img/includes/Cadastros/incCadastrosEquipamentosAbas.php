<?php

	include_once '../incVerificaSessao.php';
	extract($_REQUEST);

	if($id){
		include_once '../../classes/Cadastro.class.php';
		
		$CAD = new Cadastro();		
		extract($CAD -> coletaDados($id,$tipo));
	}
	
	$T = new Template($template_dir."/cadastros_equipamentos.html");
	$T -> Set("tpldir",substr($template_dir,6));	
	
	$T -> Set("Equipamentos_Titulo","Cadastro de M&oacute;dulos");
	$T -> Show("Equipamentos_Titulo");

	if($id){
		$T -> Set("Equipamentos_SubTitulo","M&oacute;dulo -");
		$T -> Set("Equipamentos_SubTitulo_Id",$id);
		$T -> Set("Equipamentos_SubTitulo_Nome",$serial);
		$T -> Show("Equipamentos_SubTitulo");
	}
		
	$HTML -> inicializa("Tabs");
	$HTML -> inicializa("Dialog");
	$HTML -> inicializa("Functions");
	
	$T -> Set("Abas_Tipo","Equipamentos");
	$T -> Set("id","$id");
	
	$T -> Set("Abas_Id","Equipamento");
	$T -> Show("Abas");
	$T -> Show("Abas_Cabecalho");

	$T -> Set("Abas_id","$id");
	
	$T -> Set("Abas_Titulos","Dados do M&oacute;dulo");
	$T -> Set("Abas_Titulo_Id","dados");
	$T -> Show("Abas_Cabecalho_LI");
	
	$T -> Set("Abas_Titulos","Histórico");
	$T -> Set("Abas_Titulo_Id","historico");
	$T -> Show("Abas_Cabecalho_LI");
		
	$T -> Show("Abas_Cabecalho_Fim");

	$T -> Set("Abas_Conteudo_Tipo","Equipamentos");
	$T -> Set("Abas_Conteudo_Id","dados");
	$T -> Show("Abas_Conteudo_DIV");
	$T -> Show("Abas_Conteudo");

	$T -> Set("Abas_Conteudo_Tipo","Equipamentos");
	$T -> Set("Abas_Conteudo_Id","historico");
	$T -> Show("Abas_Conteudo_DIV");
//	$T -> Show("Abas_Conteudo");
	
	$T -> Show("Abas_Fim");
	
	$T -> Show("Equipamentos_Button_Dialog");
?>