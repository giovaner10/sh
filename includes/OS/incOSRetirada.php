<?php

	// Classe necessaria
	include_once '../incVerificaSessao.php';
	include("../classes/Cadastro.class.php");
	include("../classes/Util.class.php");
	
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
	
	// Coletando os dados do CONTRATO
	extract($CAD -> coletaDados($id,7));
	// Coletando os dados do CLIENTE
	$cliente = $CAD -> coletaDados($id_cliente,1);

	$T = new Template(substr($template_dir,3)."/os_retirada.html");
	$T -> Set("tpldir",substr($template_dir,6));
	
	// Colocando o titulo na pagina
	$T -> Set("OS_Titulo","OS - Retirada");
	$T -> Show("OS_Titulo");
	
	// Colocando o sub-titulo na pagina
	$T -> Set("OS_SubTitulo_Id","Contrato ".$id);
	$T -> Set("OS_SubTitulo_Nome",utf8_encode($nome));
	$T -> Show("OS_SubTitulo");
	
	$T -> Set("id_contrato",$id);
	$T -> Set("id_cliente",$id_cliente);
	$T -> Set("nome",utf8_encode($nome));
	$T -> Set("quantidade_equipamentos",$quantidade_veiculos);
	
	$endereco  = $cliente['endereco'].", ".$cliente['numero']."\n";
	if ($cliente['complemento']) $endereco .= $cliente['complemento'].", ";
	$endereco .= $cliente['bairro']."\n";
	$endereco .= $cliente['cidade']." - ".$cliente['uf']."\n";
	if ($cliente['ponto_de_referencia']) $endereco .= "Ponto de Referência: ".$cliente['ponto_de_referencia'];
	
	$T -> Set("endereco_destino",utf8_encode($endereco));
	
	$T -> Show("OS_Instalacao_Formulario");
	
	// Apresentando o SELECT de Instaladores
	$HTML -> select("id_instalador",Util::arraySelectInstaladores($DB,$id_instalador));
	
	$T -> Show("OS_Instalacao_Formulario_Continuacao_Instalador");
	
	// Coletando os Modulos Disponiveis
	$mDisp = $CAD -> coletaModulosDisponiveis();	
	
	// Apresentando os Modulos que estao disponiveis
	foreach ($mDisp as $key => $value) {
		$HTML->checkradio(0, "modulos", $key);
		echo $value."<br>";
	}	
	$T -> Show("OS_Instalacao_Formulario_Continuacao_Modulos_Disponiveis");	

	
	
?>