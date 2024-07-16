<?php
	// Classe necessaria
	include("../classes/Cadastro.class.php");
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
		
	if($id != ""){
		// Coletando os dados do Instalador
		extract($CAD -> coletaDados($id,3));
		// Coletando os Modulos Disponiveis
		$mDisp = $CAD -> coletaModulosDisponiveis();
		// Coletando os Modulos com o Instalador
		$mInst = $CAD -> coletaModulosEstoqueInstalador($id);
		$titulo = " - EDITAR";
		$oper = 1;
	} 
	
	// Template VENDEDORES
	$TInstal = new Template(substr($template_dir,3)."/cadastros_equipamentos_instalador.html");
	$TInstal -> Set("tpldir",substr($template_dir,6));
	
	// Definindo os campos
	$TInstal -> Set("oper",	$oper);
	$TInstal -> Set("id",	$id);
	
	// Colocando o titulo na pagina
	$TInstal -> Set("Equipamentos_Titulo","Estoque do Instalador". $titulo);
	$TInstal -> Show("Equipamentos_Titulo");
	
	if ($id != ""){
		// Colocando o sub-titulo na pagina
		$TInstal -> Set("Equipamentos_SubTitulo_Id",  $id);
		$TInstal -> Set("Equipamentos_SubTitulo_Nome", utf8_encode($nome));
		$TInstal -> Show("Equipamentos_SubTitulo");
	}
				
	// Apresentando o Formulario
	$TInstal -> Show("Equipamentos_Formulario");
	
	// Apresentando os Modulos que estao com o instalador
	foreach ($mInst as $key => $value) {
		$HTML->checkradio(0, "remover", $key); 
		echo $value."<br>";
	}
	
	// Continuacao do Formulario - REMOVER
	$TInstal -> Show("Equipamentos_Formulario_Continuacao_Remover");
	
	// Apresentando os Modulos que estao disponiveis
	foreach ($mDisp as $key => $value) {
		$HTML->checkradio(0, "adicionar", $key); 
		echo $value."<br>";
	}	
	// Continuacao do Formulario - ADICIONAR
	$TInstal -> Show("Equipamentos_Formulario_Continuacao_Adicionar");		

	
?>