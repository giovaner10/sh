<?php
	// Classe necessaria
	include("../classes/Usuario.class.php");
	// Criando objeto do tipo USUARIO
	$USU = new Usuario();
		
	if($id != ""){
		// Coletando os dados do Usuario
		extract($USU -> dadosDoUsuario($DB,$id));	
		$titulo = " - EDITAR";
	} else {
		$nome = $login = $perfil = "";
		$titulo = " - CADASTRAR";
	}
	
	// Template USUARIO
	$TUSU = new Template(substr($template_dir,3)."/cadastros_usuarios.html");
	$TUSU -> Set("tpldir",substr($template_dir,6));
	
	// Definindo os campos
	$TUSU -> Set("USUFORMCAD_Id",    $id);
	$TUSU -> Set("USUFORMCAD_Nome",  utf8_encode($nome));
	$TUSU -> Set("USUFORMCAD_Login", $login);
	$TUSU -> Set("USUFORMCAD_Perfil",$perfil);
	
	// Colocando o titulo na pagina
	$TUSU -> Set("Usuarios_Titulo","Cadastro de Usu&aacute;rios". $titulo);
	$TUSU -> Show("Usuarios_Titulo");
	
	if ($id != ""){
		// Colocando o sub-titulo na pagina
		$TUSU -> Set("Usuarios_SubTitulo_Id",  $id);
		$TUSU -> Set("Usuarios_SubTitulo_Nome",utf8_encode($nome));
		$TUSU -> Show("Usuarios_SubTitulo");
	}
				
	// Apresentando o Formulario
	$TUSU -> Show("Usuario_Formulario_Cadastro_INICIO");
	
	// Coleta os PERFIS cadastrados no BD
	$perfis = $USU -> coletaPerfis($DB);
	// Construindo ARRAY do Select
	foreach ($perfis as $key => $value) {
		$selected = ($key == $perfil)?1:0;
		$arrPerfil[] = array($key,$value,$selected);  
	}
	// Apresentando o SELECT de PERFIS
	$HTML -> select("perfil",$arrPerfil);
	
	// Finalizando a apresentacao do PERFIL
	$TUSU -> Show("Usuario_Formulario_Cadastro_FIMPERFIL");	
	
	// Se for EDITAR, apresentar aviso da SENHA
	if ($id != "") $TUSU -> Show("Usuario_Formulario_Cadastro_AVISO");
	
	// Finalizando o Formulario
	$TUSU -> Show("Usuario_Formulario_Cadastro_FIM");
	
	
?>