<?php
	// Classe necessaria
	include("../classes/Cadastro.class.php");
	include("../classes/Util.class.php");
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
		
	if($id != ""){
		// Coletando os dados do Usuario
		extract($CAD -> coletaDados($id,4));
		$titulo = " - EDITAR";
		$oper = 1;
	} else {
		$nome = $endereco = $numero = $bairro = $cep =
		$cidade = $uf = $fone = $cel = $email = 
		$produto = $cpf_cnpj = "";
		$titulo = " - CADASTRAR";
		$oper = 2;
	}
	
	// Template VENDEDORES
	$TForn = new Template(substr($template_dir,3)."/cadastros_fornecedores.html");
	$TForn -> Set("tpldir",substr($template_dir,6));
	
	// Definindo os campos
	$TForn -> Set("oper",		$oper);
	$TForn -> Set("id",			$id);
	$TForn -> Set("nome",		utf8_encode($nome));
	$TForn -> Set("endereco",	utf8_encode($endereco));
	$TForn -> Set("numero",		$numero);
	$TForn -> Set("bairro",		utf8_encode($bairro));
	$TForn -> Set("cidade",		utf8_encode($cidade));
	$TForn -> Set("fone",		$fone);
	$TForn -> Set("cel",		$cel);
	$TForn -> Set("email",		$email);
	$TForn -> Set("produto",	utf8_encode($produto));
	$TForn -> Set("cpf_cnpj",	$cpf_cnpj);
	
	// Colocando o titulo na pagina
	$TForn -> Set("Fornecedores_Titulo","Cadastro de Fornecedores". $titulo);
	$TForn -> Show("Fornecedores_Titulo");
	
	if ($id != ""){
		// Colocando o sub-titulo na pagina
		$TForn -> Set("Fornecedores_SubTitulo_Id",  $id);
		$TForn -> Set("Fornecedores_SubTitulo_Nome", $nome);
		$TForn -> Show("Fornecedores_SubTitulo");
	}
				
	// Apresentando o Formulario
	$TForn -> Show("Fornecedores_Formulario_Cadastro_INICIO");
	
	// Coleta os PERFIS cadastrados no BD
	$estados = Util::estados();

	// Construindo ARRAY do Select
	foreach ($estados as $key => $value) {
		$selected = ($key == $uf)?1:0;
		$arrUf[] = array($key,$value,$selected);  
	}
	// Apresentando o SELECT de ESTADOS
	$HTML -> select("uf",$arrUf);
	
	// Finalizando a apresentacao dos ESTADOS
	$TForn -> Show("Fornecedores_Formulario_Cadastro_UF_FIM");	
	
	// Finalizando o Formulario
	$TForn -> Show("Fornecedores_Formulario_Cadastro_FIM");
	
	
?>