<?php
	// Classe necessaria
	include("../classes/Cadastro.class.php");
	include("../classes/Util.class.php");
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
		
	if($id != ""){
		// Coletando os dados do Instalador
		extract($CAD -> coletaDados($id,3));
		$titulo = " - EDITAR";
		$oper = 1;
		
		// corrigindo moeda
		$valor_veiculo = number_format($valor_veiculo,2,",",".");
	} else {
		$nome = $endereco = $numero = $bairro = $cep =
		$cidade = $uf = $fone = $cel = $email = 
		$valor_veiculo = $cpf = $salario = $complemento ="";
		$titulo = " - CADASTRAR";
		$oper = 2;
	}
	
	// Template VENDEDORES
	$TInstal = new Template(substr($template_dir,3)."/cadastros_instaladores.html");
	$TInstal -> Set("tpldir",substr($template_dir,6));
	
	// Definindo os campos
	$TInstal -> Set("oper",			$oper);
	$TInstal -> Set("id",			$id);
	$TInstal -> Set("nome",			utf8_encode($nome));
	$TInstal -> Set("endereco",		utf8_encode($endereco));
	$TInstal -> Set("numero",		$numero);
	$TInstal -> Set("complemento",	utf8_encode($complemento));
	$TInstal -> Set("bairro",		utf8_encode($bairro));
	$TInstal -> Set("cidade",		utf8_encode($cidade));
	$TInstal -> Set("fone",			$fone);
	$TInstal -> Set("cel",			$cel);
	$TInstal -> Set("email",		$email);
	$TInstal -> Set("valor_veiculo",$valor_veiculo);
	$TInstal -> Set("salario",		$salario);
	$TInstal -> Set("cpf",		$cpf);
	
	// Colocando o titulo na pagina
	$TInstal -> Set("Instaladores_Titulo","Cadastro de Instaladores". $titulo);
	$TInstal -> Show("Instaladores_Titulo");
	
	if ($id != ""){
		// Colocando o sub-titulo na pagina
		$TInstal -> Set("Instaladores_SubTitulo_Id",  $id);
		$TInstal -> Set("Instaladores_SubTitulo_Nome", $nome);
		$TInstal -> Show("Instaladores_SubTitulo");
	}
				
	// Apresentando o Formulario
	$TInstal -> Show("Instaladores_Formulario_Cadastro_INICIO");
	
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
	$TInstal -> Show("Instaladores_Formulario_Cadastro_UF_FIM");	
	
	// Finalizando o Formulario
	$TInstal -> Show("Instaladores_Formulario_Cadastro_FIM");
	
	
?>