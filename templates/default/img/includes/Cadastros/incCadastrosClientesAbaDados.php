<?php
	// Classe necessaria
	include("../classes/Cadastro.class.php");
	include("../classes/Util.class.php");
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
	
	if($id != ""){
		// Coletando os dados do Usuario
		extract($CAD -> coletaDados($id,1));
		$titulo = " - EDITAR";
		$oper = 1;
	} else {
		$nome = $endereco = $numero = $bairro = $cep =
		$cidade = $uf = $fone = $cel = $fax = $email =
		$informacoes = $cpf = $latitude = $longitude = 
		$cnpj = $complemento = $ponto_de_referencia = 
		$razao_social = $inscricao_estadual = $contato = "";
		$titulo = " - CADASTRAR";
		$oper = 2;
	}
	
	// Template VENDEDORES
	$TCli = new Template(substr($template_dir,3)."/cadastros_clientes.html");
	$TCli -> Set("tpldir",substr($template_dir,6));
	
	// Definindo os campos
	$TCli -> Set("pj_checked",	($cnpj!=""||$cpf=="")?"checked":"");
	$TCli -> Set("pj_display",	($cnpj!=""||$cpf=="")?"":"none");
	$TCli -> Set("pf_checked",	($cpf!="")?"checked":"");
	$TCli -> Set("pf_display",	($cpf!="")?"":"none");
	$TCli -> Set("oper",		$oper);
	$TCli -> Set("id",			$id);
	$TCli -> Set("nome",		utf8_encode($nome));
	$TCli -> Set("endereco",	utf8_encode($endereco));
	$TCli -> Set("numero",		$numero);
	$TCli -> Set("complemento",	utf8_encode($complemento));
	$TCli -> Set("ponto_de_referencia",	utf8_encode($ponto_de_referencia));
	$TCli -> Set("bairro",		utf8_encode($bairro));
	$TCli -> Set("cidade",		utf8_encode($cidade));
	$TCli -> Set("fone",		$fone);
	$TCli -> Set("cel",			$cel);
	$TCli -> Set("fax",			$fax);
	$TCli -> Set("email",		$email);
	$TCli -> Set("informacoes",	utf8_encode($informacoes));
	$TCli -> Set("cpf",			$cpf);
	$TCli -> Set("cnpj",		$cnpj);
	$TCli -> Set("inscricao_estadual", $inscricao_estadual);
	$TCli -> Set("contato",		utf8_encode($contato));
	$TCli -> Set("razao_social", utf8_encode($razao_social));
	$TCli -> Set("latitude",	$latitude);
	$TCli -> Set("longitude",	$longitude);
	
	// Apresentando o Formulario
	$TCli -> Show("Clientes_Formulario_Cadastro_INICIO");
	
	// Array com os Estados
	$estados = Util::estados();
	
	// Construindo ARRAY do Select
	foreach ($estados as $key => $value) {
		$selected = ($key == $uf)?1:0;
		$arrUf[] = array($key,$value,$selected);
	}
	// Apresentando o SELECT de ESTADOS
	$HTML -> select("uf",$arrUf);
	
	// Finalizando a apresentacao dos ESTADOS
	$TCli -> Show("Clientes_Formulario_Cadastro_UF_FIM");
	
	// Finalizando o Formulario
	$TCli -> Show("Clientes_Formulario_Cadastro_FIM");

?>