<?php
	// Classe necessaria
	include("../classes/Cadastro.class.php");
	include("../classes/Util.class.php");
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
		
	if($id != ""){
		// Coletando os dados do Usuario
		extract($CAD -> coletaDados($id,2));
		$titulo = " - EDITAR";
		$oper = 1;
		
		// corrigindo moeda
		$salario = number_format($salario,2,",",".");
		$comissao_veiculo = number_format($comissao_veiculo,2,",",".");
	} else {
		$nome = $endereco = $numero = $bairro = $cep =
		$cidade = $uf = $fone = $cel = $email = $comissao_veiculo =
		$salario = $complemento = $cpf = "";
		$titulo = " - CADASTRAR";
		$oper = 2;
	}
	
	// Template VENDEDORES
	$TVEND = new Template(substr($template_dir,3)."/cadastros_vendedores.html");
	$TVEND -> Set("tpldir",substr($template_dir,6));
	
	// Definindo os campos
	$TVEND -> Set("oper",				$oper);
	$TVEND -> Set("id",					$id);
	$TVEND -> Set("nome",				utf8_encode($nome));
	$TVEND -> Set("endereco",			utf8_encode($endereco));
	$TVEND -> Set("numero",				$numero);
	$TVEND -> Set("complemento",		utf8_encode($complemento));
	$TVEND -> Set("bairro",				utf8_encode($bairro));
	$TVEND -> Set("cidade",				utf8_encode($cidade));
	$TVEND -> Set("fone",				$fone);
	$TVEND -> Set("cel",				$cel);
	$TVEND -> Set("email",				$email);
	$TVEND -> Set("comissao_veiculo",	$comissao_veiculo);
	$TVEND -> Set("salario",			$salario);
	$TVEND -> Set("cpf",			$cpf);
	
	// Colocando o titulo na pagina
	$TVEND -> Set("Vendedores_Titulo","Cadastro de Vendedores". $titulo);
	$TVEND -> Show("Vendedores_Titulo");
	
	if ($id != ""){
		// Colocando o sub-titulo na pagina
		$TVEND -> Set("Vendedores_SubTitulo_Id",  $id);
		$TVEND -> Set("Vendedores_SubTitulo_Nome", $nome);
		$TVEND -> Show("Vendedores_SubTitulo");
	}
				
	// Apresentando o Formulario
	$TVEND -> Show("Vendedores_Formulario_Cadastro_INICIO");
	
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
	$TVEND -> Show("Vendedores_Formulario_Cadastro_UF_FIM");	
	
	// Finalizando o Formulario
	$TVEND -> Show("Vendedores_Formulario_Cadastro_FIM");
	
	
?>