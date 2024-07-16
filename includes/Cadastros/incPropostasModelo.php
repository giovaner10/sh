<?php
ob_start();

     	include_once '../incVerificaSessao.php';
     	require_once '../../classes/Util.class.php';
	require_once '../../classes/Cadastro.class.php';

	extract($_REQUEST);
	
	$CAD = new Cadastro();
	
	$cont = $CAD->coletaDados($id, 77);
	$cli = $CAD->coletaDados($cont['id_cliente'], 1);
        $ven = $CAD->coletaDados($cont['id_vendedor'], 16);
        	
	//$T = new Template($template_dir."/tcpdf/examples/contrato.php");
        $T = new Template($template_dir."/proposta_modelo0.html");
	$T -> Set("tpldir",substr($template_dir,0));
	
	// Array com os Estados
	$estados = Util::estados();
	
	// Definindo as variaveis
	$estado = $estados[$cli[uf]];
	$endereco = utf8_encode($cli['endereco'].", ".$cli['numero'].", ".$cli['complemento']);
        $tipo=  Util::tipo_proposta();
        
        $T -> Set("cliente",  utf8_encode($cli['nome']));
        
        $T -> Set("tipo",$tipo[$cont['tipo_proposta']]);
        $T -> Set("vencimento_proposta",$cont['vencimento_proposta']);
        $T -> Set("vendedor",$ven['nome']);
        $T -> Set("emailvendedor",$ven['email']);
        $T -> Set("fone_vendedor",$ven['fone']);
        $T -> Set("celu_vendedor",$ven['cel']);
        
        $T -> Set("meses",Util::zeros(2,$cont['meses']));
        $T -> Set("vencimento", Util::zeros(2, $cont['vencimento']));
        $T -> Set("primeira_mensalidade",Util::formataData($cont['primeira_mensalidade']));
        
	$T -> Set("qtd",$cont['quantidade_veiculos']);

        $T -> Set("valor_mes",'R$ '.Util::formataValor($cont['valor_mensal']));
        $T -> Set("total_mensal",'R$ '.Util::formataValor($cont['valor_mensal'] * $cont['quantidade_veiculos']));
	
        $T -> Set("adesao",'R$ '.Util::formataValor($cont['valor_instalacao']));
        $T -> Set("valor_entrada",'R$ '.Util::formataValor($cont['valor_entrada']));
        $T -> Set("total_instalacao",'R$ '.Util::formataValor($cont['valor_instalacao'] * $cont['quantidade_veiculos']));
        $T -> Set("divide",($cont['prestacoes']<2)?'A vista':$cont['prestacoes'].' parcelas');
        $T -> Set("divide",($cont['prestacoes']<2)?$cont['prestacoes'].' parcela':$cont['prestacoes'].' parcelas');
        $T -> Set("valor_parcela",'R$ '.Util::formataValor(($cont['prestacoes']!="")?(($cont['valor_instalacao'] * $cont['quantidade_veiculos'])-$cont['valor_entrada'])/$cont['prestacoes']:""));

        
	$T -> Set("primeira_prestacao",Util::formataData($cont['data_prestacao']));        
	
	
	$T -> Set("mesesPorExtenso", Util::valorPorExtenso($cont['meses']));
	$T -> Set("dataPorExtenso", Util::dataPorExtenso($cont['data_contrato']));

        
        if ($cont['multa']=="1"){
            $T -> Set("multa",'Multa Proporcional ao Contrato');
        }
        if ($cont['multa']=="2"){
            $T -> Set("multa",'Multa valor Negociado por Veiculo de R$ '.Util::formataValor($cont['multa_valor'])).' por veiculo';
        }
        if ($cont['multa']=="3"){
            $T -> Set("multa",'Sem Multa');
        }
        
	// Apresentando o INICIO da Pagina
        $T -> Show("InicioDaPagina");


        $id_cliente= $cont['id_cliente'];
        
$out2 = ob_get_contents();

ob_end_clean();

echo $out2;

//$aqui="<tr><td><br></br>Para aceitar <br><a href='http://187.95.236.236:85/sistema/aceite.php?cd=$id_cliente&nr=$id' class='classname'>Click Aqui</a><br></br></td></tr>";
$aqui="<tr><td><br></br>Para aceitar <br><a href='http://showtecnologia.com/aceito.php?cd=$id_cliente&nr=$id' class='classname'>Click Aqui</a><br></br></td></tr>";
$out2=str_replace('<tr><td id="aqui"></td></tr>',$aqui,$out2);
$out2=str_replace('./../../templates/default/img/','http://187.95.236.236:85/sistema/templates/default/img/',$out2);

$_SESSION['tipoproposta'] = $cont['tipo_proposta'];
$_SESSION['assunto'] = 'Proposta SHOW TECNOLOGIA';
$_SESSION['conteudo'] = utf8_decode($out2);
$_SESSION['email'] = $cli['email'];

//include_once 'tcpdf/examples/maladireta.php';
?>