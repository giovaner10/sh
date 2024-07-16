<?php

	include_once '../incVerificaSessao.php';
	
	extract($_REQUEST);
	
	$T = new Template($template_dir."/cadastros_chips.html");
	
	// Definindo tpldir
	$T -> Set("tpldir",substr($template_dir,6));
	
	// Colocando o titulo na pagina
	$T -> Set("Chips_Titulo","Importar Lista de Chips");
	$T -> Show("Chips_Titulo");
        $Id = 'chips';
        include_once '../Documentos/incUpload.php';
?>
