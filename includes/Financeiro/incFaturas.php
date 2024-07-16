<?php
        include("../../classes/Util.class.php");
	include_once '../incVerificaSessao.php';
	extract($_REQUEST);
	
	
	$T = new Template($template_dir."/faturas.html");
	$T -> Set("tpldir",substr($template_dir,6));
	
	$T -> Set("Faturas_Titulo","Gerando Faturas");
	$T -> Show("Faturas_Titulo");

        $T -> Show("Faturas_Formulario");

        // Coletando lista de Clientes
	$clientes = Util::arraySelectClientes($DB,$id_cliente);
	// Apresentando o SELECT de Clientes
	$HTML -> select("id_cliente",$clientes);
        // Continuando o Formulario
        
        $T -> Set('mes',  date('m'));
        $T -> Set('ano',  date('Y'));

	$T -> Show("Contratos_Formulario_Continuacao_Cli");
        
        
/*
	if($id){
		include_once '../../classes/Cadastro.class.php';
		
		$CAD = new Cadastro();		
		extract($CAD -> coletaDados($id,$tipo));
                $_SESSION['nomcli'] = utf8_encode($nome);
		
		$T -> Set("Contratos_SubTitulo","Contrato -");
		$T -> Set("Contratos_SubTitulo_Id",$id);
		$T -> Set("Contratos_SubTitulo_Nome",utf8_encode($nome));
		$T -> Show("Contratos_SubTitulo");
	}

	$HTML -> inicializa("Tabs");
	$HTML -> inicializa("Dialog");
	$HTML -> inicializa("Functions");
	
	$T -> Set("Abas_Tipo","Contratos");
	$T -> Set("id","$id");
	
	$T -> Set("Abas_Id","Contrato");
	$T -> Show("Abas");
	$T -> Show("Abas_Cabecalho");

	$T -> Set("Abas_id","$id");
	
	$T -> Set("Abas_Titulos","Dados do Contrato");
	$T -> Set("Abas_Titulo_Id","dados");
	$T -> Show("Abas_Cabecalho_LI");
	
	$T -> Set("Abas_Titulos","Histórico");
	$T -> Set("Abas_Titulo_Id","historico");
	$T -> Show("Abas_Cabecalho_LI");
	
	$T -> Set("Abas_Titulos","Veículos");
	$T -> Set("Abas_Titulo_Id","veiculos");
	$T -> Show("Abas_Cabecalho_LI");
	
	$T -> Set("Abas_Titulos","OS");
	$T -> Set("Abas_Titulo_Id","os");
	$T -> Show("Abas_Cabecalho_LI");	
			
	$T -> Show("Abas_Cabecalho_Fim");

	$T -> Set("Abas_Conteudo_Tipo","Contratos");
	$T -> Set("Abas_Conteudo_Id","dados");
	$T -> Show("Abas_Conteudo_DIV");
	$T -> Show("Abas_Conteudo");

	$T -> Set("Abas_Conteudo_Tipo","Contratos");
	$T -> Set("Abas_Conteudo_Id","historico");
	$T -> Show("Abas_Conteudo_DIV");
//	$T -> Show("Abas_Conteudo");
	
	$T -> Set("Abas_Conteudo_Tipo","Contratos");
	$T -> Set("Abas_Conteudo_Id","veiculos");
	$T -> Show("Abas_Conteudo_DIV");
//	$T -> Show("Abas_Conteudo");
	
	$T -> Set("Abas_Conteudo_Tipo","Contratos");
	$T -> Set("Abas_Conteudo_Id","os");
	$T -> Show("Abas_Conteudo_DIV");
//	$T -> Show("Abas_Conteudo");
			
	$T -> Show("Abas_Fim");
 * 
 */	
	$T -> Show("Contratos_Button_Dialog");

?>