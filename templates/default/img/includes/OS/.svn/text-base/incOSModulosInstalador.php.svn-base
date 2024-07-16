<?php
	// Classes necessarias
	include_once '../incVerificaSessao.php';
	include("../../classes/Cadastro.class.php");
	
	extract($_REQUEST);
	
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
	
	if($id != ""){
		// Coletando os Modulos com o Instalador
		$mInst = $CAD -> coletaModulosEstoqueInstalador($id);
		
		// Apresentando os Modulos que estao com o instalador
		foreach ($mInst as $key => $value) {
			$HTML->checkradio(0, "modulos", $key);
			echo $value."<br>";
		}		
		
	}
?>