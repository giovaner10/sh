<?php
	// Classe necessaria
	include("../classes/Util.class.php");
	include("../classes/Cadastro.class.php");
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
		
	if($id != ""){
		// Coletando os dados do Usuario
		extract($CAD -> coletaDados($id,7));
		$titulo = " - EDITAR";
		$oper = 1;
		
		// corrigindo pontuacao dos valores
		$valor_mensal 		= Util::formataValor($valor_mensal);
		$valor_instalacao 	= Util::formataValor($valor_instalacao);
		$valor_prestacao 	= Util::formataValor($valor_prestacao);
		
		// Corrigindo Data
		$primeira_mensalidade = Util::formataData($primeira_mensalidade);
		
	} else {
		$valor_mensal = $valor_instalacao = $valor_prestacao = 
		$id_cliente = $id_vendedor = $quantidade_veiculos = 
		$meses = $prestacoes = $vencimento = $primeira_mensalidade = "";
		$titulo = " - CADASTRAR";
		$oper = 2;
	}
	
	// Template VENDEDORES
	$TCont = new Template(substr($template_dir,3)."/contratos.html");
	$TCont -> Set("tpldir",substr($template_dir,6));
	
	// Definindo os campos
	$TCont -> Set("id",		$id);
	$TCont -> Set("oper",		$oper);
// 	$TCont -> Set("id_cliente",	$id_cliente);
// 	$TCont -> Set("id_vendedor",$id_vendedor);
	$TCont -> Set("quantidade_veiculos", $quantidade_veiculos);
	$TCont -> Set("meses",		$meses);
	$TCont -> Set("prestacoes",	$prestacoes);
	$TCont -> Set("vencimento",	$vencimento);
	$TCont -> Set("primeira_mensalidade",	$primeira_mensalidade);
	$TCont -> Set("valor_prestacao",	$valor_prestacao);
	$TCont -> Set("valor_instalacao",	$valor_instalacao);
	$TCont -> Set("valor_mensal",		$valor_mensal);
	
	// Apresentando o Formulario
	$TCont -> Show("Contratos_Formulario");
	
	// Coletando lista de Clientes
	$clientes = Util::arraySelectClientes($DB,$id_cliente);
	// Apresentando o SELECT de Clientes
	$HTML -> select("id_cliente",$clientes);
	
	// Continuando o Formulario
	$TCont -> Show("Contratos_Formulario_Continuacao_Cliente");
		
	// Coletando lista de Vendedores
	$vendedores = Util::arraySelectVendedores($DB,$id_vendedor);
	// Apresentando o SELECT de Vendedores
	$HTML -> select("id_vendedor",$vendedores);	
	
	// Continuando o Formulario
	$TCont -> Show("Contratos_Formulario_Continuacao_Vendedor");	
?>