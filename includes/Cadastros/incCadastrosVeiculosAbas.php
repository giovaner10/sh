<?php

	include_once '../incVerificaSessao.php';
	extract($_REQUEST);

	if($id){
		include_once '../../classes/Cadastro.class.php';
		
		$CAD = new Cadastro();		
		extract($CAD -> coletaDados($id,$tipo));
	}
	
	$T = new Template($template_dir."/cadastros_veiculo.html");
	$T -> Set("tpldir",substr($template_dir,6));	
	
	$T -> Set("Veiculos_Titulo","Cadastro de Veiculos");
	$T -> Show("Veiculos_Titulo");

	//if($id){
		$T -> Set("Veiculos_SubTitulo","Contrato -");
		$T -> Set("Veiculos_SubTitulo_Id",$_SESSION['contrato'].' - '.$_SESSION['cliente']);
		$T -> Set("Veiculos_SubTitulo_Nome",$_SESSION['nomcli']);
		$T -> Show("Veiculos_SubTitulo");
	//}
		
	$HTML -> inicializa("Tabs");
	$HTML -> inicializa("Dialog");
	$HTML -> inicializa("Functions");
	
	$T -> Set("Abas_Tipo","Veiculos");
	$T -> Set("id","$id");
	
	$T -> Set("Abas_Id","Veiculos");
	$T -> Show("Abas");
	$T -> Show("Abas_Cabecalho");

	$T -> Set("Abas_id","$id");
	
	$T -> Set("Abas_Titulos","Dados do Veiculos");
	$T -> Set("Abas_Titulo_Id","dados");
	$T -> Show("Abas_Cabecalho_LI");
	
	$T -> Set("Abas_Titulos","Histórico");
	$T -> Set("Abas_Titulo_Id","historico");
	$T -> Show("Abas_Cabecalho_LI");
		
	$T -> Show("Abas_Cabecalho_Fim");

	$T -> Set("Abas_Conteudo_Tipo","Veiculos");
	$T -> Set("Abas_Conteudo_Id","dados");
	$T -> Show("Abas_Conteudo_DIV");
	$T -> Show("Abas_Conteudo");

	$T -> Set("Abas_Conteudo_Tipo","Veiculos");
	$T -> Set("Abas_Conteudo_Id","historico");
	$T -> Show("Abas_Conteudo_DIV");
//	$T -> Show("Abas_Conteudo");
	
	$T -> Show("Abas_Fim");
	
	$T -> Show("Veiculos_Button_Dialog");
?>