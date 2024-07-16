<?php
	include_once '../incVerificaSessao.php';
	require_once '../../classes/Util.class.php';
	require_once '../../classes/Cadastro.class.php';
	
	extract($_REQUEST);
	
	$CAD = new Cadastro();
	
	$cont = $CAD->coletaDados($id, 7);
	$cli = $CAD->coletaDados($cont['id_cliente'], 1);
        //$cli = $CAD->coletaDados($ID, 8);
	
	
	$T = new Template($template_dir."/contrato_modelo.html");
	$T -> Set("tpldir",$template_dir);
	
	// Array com os Estados
	$estados = Util::estados();
	
	// Definindo as variaveis
	$estado = $estados[$cli[uf]];
	$endereco = utf8_encode($cli['endereco'].", ".$cli['numero'].", ".$cli['complemento']);
	$T -> Set("cliente",utf8_encode($cli['nome']));
	$T -> Set("endereco",$endereco);
	$T -> Set("bairro",utf8_encode($cli['bairro']));
	$T -> Set("cidade",utf8_encode($cli['cidade']));
	$T -> Set("estado",$estado);
	$T -> Set("cnpj",$cli['cnpj']);
	$T -> Set("qtd",$cont['quantidade_veiculos']);
	$T -> Set("primeira_mensalidade",Util::formataData($cont['primeira_mensalidade']));
	$T -> Set("vencimento",$cont['vencimento']);
	$T -> Set("meses",$cont['meses']);
	$T -> Set("mesesPorExtenso", Util::valorPorExtenso($cont['meses']));
	$T -> Set("dataPorExtenso", Util::dataPorExtenso($cont['data_cadastro']));
	
	// Apresentando o INICIO da Pagina
	$T -> Show("InicioDaPagina");
	
// 	// Coletando os dados
// 	$dados = $R -> coletaUsuarios();
// 	$num = sizeOf($dados);
	
// 	// Apresentando os Dados
// 	$HTML -> tabelaCabecalho(array("Login","Nome","Perfil","Situa&ccedil;&atilde;o"));
// 	for ($i = 0; $i < $num; $i++) {
// 		$sel = ($i%2==0)?"":1;
// 		$HTML -> tabelaLinha(array($dados[$i]['login'],$dados[$i]['nome'],$dados[$i]['perfil'],$dados[$i]['status']),$sel);
// 	}	
// 	$HTML -> tabelaRodape();
	
	// Continuando apresentacao
	$T -> Show("ContinuacaoObjeto");	
	
	if($cont['valor_instalacao'] > 0){
		
		$v_inst = $cont['valor_instalacao'];
		$t_inst = $cont['valor_instalacao'] * $cont['quantidade_veiculos'];
		$p_inst = $cont['prestacoes'];
		$v_parc = ($p_inst!="")?$t_inst/$p_inst:"";
	
		// Apresentando os Valores da INSTALAÇÃO
		$HTML -> tabelaCabecalho(array("Instalação por Veículo","Total Instalação","Dividida em","Valor das Parcelas"));
		$HTML -> tabelaLinha(array("R$ ".Util::formataValor($v_inst), "R$ ".Util::formataValor($t_inst), $p_inst, "R$ ".Util::formataValor($v_parc)));
		$HTML -> tabelaRodape();

	}
	
	// Apresentando os Valores da MENSALIDADE
	$v_mens = $cont['valor_mensal'];
	$t_mens = $v_mens * $cont['quantidade_veiculos'];
	$HTML -> tabelaCabecalho(array("Mensalidade por Veículo","Total Mensalidade","Vencimento","1ª Mensalidade"));
	$HTML -> tabelaLinha(array("R$ ".Util::formataValor($v_mens), "R$ ".Util::formataValor($t_mens), $cont['vencimento'], Util::formataData($cont['primeira_mensalidade'])));
	$HTML -> tabelaRodape();	
	
	// Continuando apresentacao
	$T -> Show("ContinuacaoPrecos");
	
?>