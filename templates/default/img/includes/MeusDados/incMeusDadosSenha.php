<?php

	include_once '../incVerificaSessao.php';	
	require_once '../../classes/Usuario.class.php';
	
	$USU = new Usuario();
	
	// Verifica se h� algum conte�do no m�todo POST
	if(count($_POST) > 0){
		
		// Extrai o conteudo para as suas vari�veis
		extract($_POST);
		
		$verifica = $USU -> verifica_senha($DB,$antiga_senha);
		
		if($verifica > 0){
			$USU -> altera_senha($DB,$LOG,$nova_senha);
			echo "alterado";
			exit;
		}
		
	}
	
	// Criando o viewer
	$T = new Template($template_dir."/meusdados_senha.html");
	
	// Apresentando o formul�rio de mudan�a de senha


	$tpl = substr($template_dir,6);
	$T -> Set("template_dir",$tpl);
	
	$T -> Show("MudarSenha");
	
?>