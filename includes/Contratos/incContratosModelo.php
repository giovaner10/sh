<?php
ob_start();

     	include_once '../incVerificaSessao.php';
     	require_once '../../classes/Util.class.php';
	require_once '../../classes/Cadastro.class.php';

	extract($_REQUEST);
	
	$CAD = new Cadastro();
	
	$cont = $CAD->coletaDados($id, 7);
	$cli = $CAD->coletaDados($cont['id_cliente'], 1);
        $ven = $CAD->coletaDados($cont['id_vendedor'], 16);
        //$cli = $CAD->coletaDados($ID, 8);
	
	
	//$T = new Template($template_dir."/tcpdf/examples/contrato.php");
        $T = new Template($template_dir."/contrato_modelo".$cont['tipo_proposta'].".html");
	$T -> Set("tpldir",substr($template_dir,0));
	
	// Array com os Estados
	$estados = Util::estados();
	
	// Definindo as variaveis
	$estado = $estados[$cli[uf]];
	$endereco = utf8_encode($cli['endereco'].", ".$cli['numero'].", ".$cli['complemento']);
        //$T -> Set("vendedor",utf8_encode($ven['nome']));
        
	$T -> Set("cliente",utf8_encode($cli['nome']));
	$T -> Set("endereco",$endereco);
	$T -> Set("bairro",utf8_encode($cli['bairro']));
	$T -> Set("cidade",utf8_encode($cli['cidade']));
	$T -> Set("estado",$estado);
	if (!empty($cli['cnpj'])){
            $T -> Set("cnpj",'CNPJ '.$cli['cnpj']);
        }
        else
        {
            $T -> Set("cnpj",'CPF '.$cli['cpf']);
        }
	$T -> Set("qtd",$cont['quantidade_veiculos']);
	$T -> Set("primeira_mensalidade",Util::formataData($cont['primeira_mensalidade']));
	$T -> Set("primeira_prestacao",Util::formataData($cont['data_prestacao']));        
	$T -> Set("vencimento",$cont['vencimento']);
	$T -> Set("meses",$cont['meses']);
	$T -> Set("mesesPorExtenso", Util::valorPorExtenso($cont['meses']));
	$T -> Set("dataPorExtenso", Util::dataPorExtenso($cont['data_contrato']));
	
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
        if ($cont['multa']=="1"){
            $T -> Show("continua_92_1");
        }
        if ($cont['multa']=="2"){
            $T -> Set("valor_multa", Util::formataValor($cont['multa_valor']));
            $T -> Set("total_multa", Util::formataValor($cont['multa_valor'] * $cont['quantidade_veiculos']));
            $T -> Show("continua_92_2");
            
        }
        if ($cont['multa']=="3"){
            $T -> Show("continua_92_3");
        }
        
	$T -> Show("continua");

$out2 = ob_get_contents();

ob_end_clean();

echo $out2;
extract($ven);

$email = <<<EDO
Prezado cliente,
    <br>
    <br>
    <br>
Segue em anexo nosso contrato de prestação de serviços.
    <br>
    <br>
Show Tecnologia 
    <br>
    $nome
    <br>
    $email
    <br>
    $cel
    <br>
    $fone
    <br>
+ 55 83 32714060
    <br>
EDO;
$_SESSION['assunto'] = 'Contrato SHOW TECNOLOGIA';
$_SESSION['conteudo'] = utf8_decode($email);
$_SESSION['email'] = utf8_decode($cli['email']);
$_SESSION['htmlpdf'] =  $out2;

//include_once '../../tcpdf/examples/maladireta.php';
?>