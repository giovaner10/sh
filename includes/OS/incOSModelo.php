<?php
	include_once '../incVerificaSessao.php';
	require_once '../../classes/Util.class.php';
	require_once '../../classes/Cadastro.class.php';
	
	extract($_REQUEST);
	
	$CAD = new Cadastro();
        
	$cont = $CAD->coletaDados($id, 8);
	$cli = $CAD->coletaDados($cont['id_cliente'], 1);
        
	
	$T = new Template($template_dir."/os_modelo.html");
	$T -> Set("tpldir",substr($template_dir,0));
	
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
        $T -> Set("solicitante",$cont['solicitante']);
        $T -> Set("data_solicitacao",Util::formataData($cont['data_solicitacao']));
        $T -> Set("contato",$cont['contato']);
        $T -> Set("telefone",$cont['telefone']);
        $T -> Set("endereco_destino",utf8_encode($cont['endereco_destino']));
        $T -> Set("instalador",utf8_encode($cont['nome']));
        
        if ($cont['data_inicial']=='0000-00-00'){
            $T -> Set("data_inicial",'____/____/______');
        }else
        {
            $T -> Set("data_inicial",Util::formataData($cont['data_inicial']));
        }
        
        if ($cont['data_final']=='0000-00-00'){
            $T -> Set("data_final",'____/____/______');
        }else
        {
            $T -> Set("data_final",Util::formataData($cont['data_final']));
        }
        
        if ($cont['hora_inicial']=='00:00:00'){
            $T -> Set("hora_inicial",'____:____');
        }else
        {
            $T -> Set("hora_inicial",$cont['hora_inicial']);
        }
        if ($cont['hora_final']=='00:00:00'){
            $T -> Set("hora_final",'____:____');
        }else
        {
            $T -> Set("hora_final",$cont['hora_final']);
        }
        //0000-00-00	00:00:00
        $T -> Set("OS",Util::zeros(6, $cont['id']));
        $T -> Set("tipo_OS",Util::zeros(6, $cont['id']));
        $T -> Set("observacoes",  nl2br(utf8_encode($cont['observacoes'])));
	$T -> Set("qtd",$cont['quantidade_veiculos']);
	$T -> Set("primeira_mensalidade",Util::formataData($cont['primeira_mensalidade']));
	$T -> Set("vencimento",$cont['vencimento']);
	$T -> Set("meses",$cont['meses']);
	$T -> Set("mesesPorExtenso", Util::valorPorExtenso($cont['meses']));
	$T -> Set("dataPorExtenso", Util::dataPorExtenso($cont['data_cadastro']));
	
        
	// Apresentando o INICIO da Pagina
	$T -> Show("InicioDaPagina");
	
        
        // Apresentando o SELECT de Instaladores
	//$HTML -> select("id_instalador",Util::arraySelectInstaladores($DB,$id_instalador));
	
	$T -> Show("OS_Instalacao_Formulario_Continuacao_Instalador");
        
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
//	$T -> Show("ContinuacaoObjeto");	
	
//	if($cont['valor_instalacao'] > 0){
		
//		$v_inst = $cont['valor_instalacao'];
//		$t_inst = $cont['valor_instalacao'] * $cont['quantidade_veiculos'];
//		$p_inst = $cont['prestacoes'];
//		$v_parc = ($p_inst!="")?$t_inst/$p_inst:"";
	
		// Apresentando os Valores da INSTALAÇÃO
//		$HTML -> tabelaCabecalho(array("Instalação por Veículo","Total Instalação","Dividida em","Valor das Parcelas"));
//		$HTML -> tabelaLinha(array("R$ ".Util::formataValor($v_inst), "R$ ".Util::formataValor($t_inst), $p_inst, "R$ ".Util::formataValor($v_parc)));
//		$HTML -> tabelaRodape();

//	}
	
	// Apresentando os Valores da MENSALIDADE
//	$v_mens = $cont['valor_mensal'];
//	$t_mens = $v_mens * $cont['quantidade_veiculos'];
//	$HTML -> tabelaCabecalho(array("Mensalidade por Veículo","Total Mensalidade","Vencimento","1ª Mensalidade"));
//	$HTML -> tabelaLinha(array("R$ ".Util::formataValor($v_mens), "R$ ".Util::formataValor($t_mens), $cont['vencimento'], Util::formataData($cont['primeira_mensalidade'])));
//	$HTML -> tabelaRodape();	
	
	// Continuando apresentacao
//	$T -> Show("ContinuacaoPrecos");

	// Apresentando dos equipamento
        
	// Coletando os Modulos OS
	$mDisp = $CAD -> coletaModulosOS($id);	
	$HTML -> tabelaCabecalho(array("Equipamentos para ()","Placa do Veiculo"));
	
	// Apresentando os Modulos que estao disponiveis
	foreach ($mDisp as $key => $value) {
		//$HTML->checkradio(0, "modulos", $key);
                $HTML -> tabelaLinha(array($value, " "));
		//echo $value."<br>";
	}	
	$HTML -> tabelaRodape();	
	
	// Continuando apresentacao
	$T -> Show("ContinuacaoEquipamentos");

?>