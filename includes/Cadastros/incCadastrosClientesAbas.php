<?php

	include_once '../incVerificaSessao.php';
	extract($_REQUEST);
        $_SESSION['passa_id'] = $id;
	if($id){
		include_once '../../classes/Cadastro.class.php';
		
		$CAD = new Cadastro();		
		extract($CAD -> coletaDados($id,$tipo));
	}
	
	$T = new Template($template_dir."/cadastros_clientes.html");
	$T -> Set("tpldir",substr($template_dir,6));	
	
	$T -> Set("Clientes_Titulo","Cadastro de Clientes");
	$T -> Show("Clientes_Titulo");

	if($id){
		$T -> Set("Clientes_SubTitulo","Cliente -");
		$T -> Set("Clientes_SubTitulo_Id",$id);
		$T -> Set("Clientes_SubTitulo_Nome",htmlentities($nome));
		$T -> Show("Clientes_SubTitulo");
	}
		
	$HTML -> inicializa("Tabs");
	$HTML -> inicializa("Dialog");
	$HTML -> inicializa("Functions");
	
	$T -> Set("Abas_Tipo","Clientes");
	$T -> Set("id","$id");
	
	$T -> Set("Abas_Id","Cliente");
	$T -> Show("Abas");
	$T -> Show("Abas_Cabecalho");

	$T -> Set("Abas_id","$id");
	
	$T -> Set("Abas_Titulos","Dados do Cliente");
	$T -> Set("Abas_Titulo_Id","dados");
	$T -> Show("Abas_Cabecalho_LI");
        
	$T -> Set("Abas_Titulos","Propostas");
	$T -> Set("Abas_Titulo_Id","propostas");
	$T -> Show("Abas_Cabecalho_LI");
	
	$T -> Set("Abas_Titulos","Contratos");
	$T -> Set("Abas_Titulo_Id","contratos");
	$T -> Show("Abas_Cabecalho_LI");
		
	$T -> Set("Abas_Titulos","Ordens de Serviços");
	$T -> Set("Abas_Titulo_Id","os");
	$T -> Show("Abas_Cabecalho_LI");
	
	$T -> Set("Abas_Titulos","Veículos");
	$T -> Set("Abas_Titulo_Id","veiculos");
	$T -> Show("Abas_Cabecalho_LI");	

	$T -> Set("Abas_Titulos","Modulos");
	$T -> Set("Abas_Titulo_Id","modulos");
	$T -> Show("Abas_Cabecalho_LI");	
        
	$T -> Show("Abas_Cabecalho_Fim");

	$T -> Set("Abas_Conteudo_Tipo","Clientes");
	$T -> Set("Abas_Conteudo_Id","dados");
	$T -> Show("Abas_Conteudo_DIV");
	$T -> Show("Abas_Conteudo");
        
	$T -> Set("Abas_Conteudo_Tipo","Clientes");
	$T -> Set("Abas_Conteudo_Id","propostas");
	$T -> Show("Abas_Conteudo_DIV");
//	$T -> Show("Abas_Conteudo");

	$T -> Set("Abas_Conteudo_Tipo","Clientes");
	$T -> Set("Abas_Conteudo_Id","contratos");
	$T -> Show("Abas_Conteudo_DIV");
//	$T -> Show("Abas_Conteudo");
	
	$T -> Set("Abas_Conteudo_Tipo","Clientes");
	$T -> Set("Abas_Conteudo_Id","os");
	$T -> Show("Abas_Conteudo_DIV");
//	$T -> Show("Abas_Conteudo");

	$T -> Set("Abas_Conteudo_Tipo","Clientes");
	$T -> Set("Abas_Conteudo_Id","veiculos");
	$T -> Show("Abas_Conteudo_DIV");
//	$T -> Show("Abas_Conteudo");	

	$T -> Set("Abas_Conteudo_Tipo","Clientes");
	$T -> Set("Abas_Conteudo_Id","modulos");
	$T -> Show("Abas_Conteudo_DIV");
	//	$T -> Show("Abas_Conteudo");	
        
	$T -> Show("Abas_Fim");
	
	$T -> Show("Clientes_Button_Dialog");
?>