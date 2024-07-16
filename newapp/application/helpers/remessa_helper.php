<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function remessa_salario($contas,$n_arquivo,$cnpj = '21698912000159',$pf_pj = '2',$convenio = '160992',$agencia = '200',$dv_agencia= '3',$conta_conv="39321",$dv_conta= '5',$n_empresa = 'NORIO MOMOI ME'){

    $remessa = "";
    $remessa .= header_file($cnpj,$pf_pj,$convenio,$agencia,$dv_agencia,$conta_conv,$dv_conta,$n_empresa,$n_arquivo);
    $remessa .= header_lote($cnpj,$pf_pj,$convenio,$agencia,$dv_agencia,$conta_conv,$dv_conta,$n_empresa,$n_arquivo);
    $contador = 1;
    $total = 0;
    foreach($contas as $conta){
        $data_pagamento = explode('-',$conta->data_vencimento);
        $data_pagamento = $data_pagamento[2].$data_pagamento[1].$data_pagamento[0];
        $total+=floatval($conta->valor);
        $remessa .= segmento_a(strval($contador),$conta->codigo_banco,$conta->agencia,$conta->dv_agencia,$conta->conta,$conta->dv_conta,$conta->fornecedor,$data_pagamento,$conta->valor,$conta->conta_id);
        $contador++;
        $remessa .= segmento_b(strval($contador),$conta->pf_pj,$conta->cpf_cnpj,$data_pagamento,$conta->valor);
        $contador++;
    }
    $remessa .=trailer_lote(strval($contador+1),number_format($total, 2,'',''));
    $remessa .= trailer_arquivo('1',strval($contador+3));
    return $remessa;
}

function remessa_instalador($contas,$n_arquivo,$data_pagamento_remessa,$cnpj = '09338999000158',$pf_pj = '2',$convenio = '164544',$agencia = '200',$dv_agencia= '3',$conta_conv="28629",$dv_conta= 'X',$n_empresa = 'SHOW PRESTADORA DE SERVICOS DO'){
    $num_lote = 0;
    $remessa = "";
    $remessa .= header_file($cnpj,$pf_pj,$convenio,$agencia,$dv_agencia,$conta_conv,$dv_conta,$n_empresa,$n_arquivo);
    $data_pagamento = explode('-',$data_pagamento_remessa);
    $data_pagamento = $data_pagamento[2].$data_pagamento[1].$data_pagamento[0];
    if(count($contas['corrente'])){
        $num_lote++;
        $remessa .= header_lote($cnpj,$pf_pj,$convenio,$agencia,$dv_agencia,$conta_conv,$dv_conta,$n_empresa,$n_arquivo,'98','01',$num_lote);
        $contador = 1;
        $total = 0;
        foreach($contas['corrente'] as $conta){
            $total+=floatval($conta->valor);
            $remessa .= segmento_a(strval($contador),$conta->codigo_banco,$conta->agencia,$conta->dv_agencia,$conta->conta,$conta->dv_conta,$conta->fornecedor,$data_pagamento,$conta->valor,$conta->conta_id,"000",$num_lote);
            $contador++;
            $remessa .= segmento_b(strval($contador),$conta->pf_pj,$conta->cpf_cnpj,$data_pagamento,$conta->valor);
            $contador++;
        }
        $remessa .=trailer_lote(strval($contador+1),number_format($total, 2,'',''));
    }
    if(count($contas['poupanca'])){
        $num_lote++;
        $remessa .= header_lote($cnpj,$pf_pj,$convenio,$agencia,$dv_agencia,$conta_conv,$dv_conta,$n_empresa,$n_arquivo,'98','05',$num_lote);
        $contador = 1;
        $total = 0;
        foreach($contas['poupanca'] as $conta){
            $total+=floatval($conta->valor);
            $remessa .= segmento_a(strval($contador),$conta->codigo_banco,$conta->agencia,$conta->dv_agencia,$conta->conta,$conta->dv_conta,$conta->fornecedor,$data_pagamento,$conta->valor,$conta->conta_id,"000",$num_lote);
            $contador++;
            $remessa .= segmento_b(strval($contador),$conta->pf_pj,$conta->cpf_cnpj,$data_pagamento,$conta->valor);
            $contador++;
        }
        $remessa .=trailer_lote(strval($contador+1),number_format($total, 2,'',''));
    }
    if(count($contas['ted'])){
        $num_lote++;
        $remessa .= header_lote($cnpj,$pf_pj,$convenio,$agencia,$dv_agencia,$conta_conv,$dv_conta,$n_empresa,$n_arquivo,'98','41',$num_lote);
        $contador = 1;
        $total = 0;
        foreach($contas['ted'] as $conta){
            $total+=floatval($conta->valor);
            $remessa .= segmento_a(strval($contador),$conta->codigo_banco,$conta->agencia,$conta->dv_agencia,$conta->conta,$conta->dv_conta,$conta->fornecedor,$data_pagamento,$conta->valor,$conta->conta_id,"018",$num_lote);
            $contador++;
            $remessa .= segmento_b(strval($contador),$conta->pf_pj,$conta->cpf_cnpj,$data_pagamento,$conta->valor);
            $contador++;
        }
        $remessa .=trailer_lote(strval($contador+1),number_format($total, 2,'',''));
    }
    $remessa .= trailer_arquivo(strval($num_lote),strval($contador+3));
    return $remessa;
}

function remessa_boleto($contas,$n_arquivo,$data_pagamento_remessa,$cnpj = '09338999000158',$pf_pj = '2',$convenio = '164544',$agencia = '200',$dv_agencia= '3',$conta_conv="28629",$dv_conta= 'X',$n_empresa = 'SHOW PRESTADORA DE SERVICOS DO'){
    $num_lote = 0;
    $remessa = "";
    $remessa .= header_file($cnpj,$pf_pj,$convenio,$agencia,$dv_agencia,$conta_conv,$dv_conta,$n_empresa,$n_arquivo);
    $data_pagamento = explode('-',$data_pagamento_remessa);
    $data_pagamento = $data_pagamento[2].$data_pagamento[1].$data_pagamento[0];
    $boleto_outros = true;
    if(count($contas)){
        $num_lote++;
        $remessa .= header_lote($cnpj,$pf_pj,$convenio,$agencia,$dv_agencia,$conta_conv,$dv_conta,$n_empresa,$n_arquivo,'98','30',$num_lote,"  CLT40026");
        $contador = 1;
        $total = 0;
        foreach($contas as $conta){
            if(($conta->codigo_barra[0]!='0'||$conta->codigo_barra[1]!='0'||$conta->codigo_barra[2]!='1')&&$boleto_outros){
                $remessa .=trailer_lote(strval($contador+1),number_format($total, 2,'',''));
                $num_lote++;
                $remessa .= header_lote($cnpj,$pf_pj,$convenio,$agencia,$dv_agencia,$conta_conv,$dv_conta,$n_empresa,$n_arquivo,'98','31',$num_lote,"  CLT40026");
                $contador = 1;
                $total = 0;
                $boleto_outros=false;
            }
            $total+=floatval($conta->valor);
            $remessa .= segmento_j(strval($contador),$data_pagamento,$conta->valor,$num_lote,$conta->codigo_barra,$conta->fornecedor->nome,$conta->conta_id);
            $contador++;
            $remessa .= segmento_j52(strval($contador),$conta->fornecedor->pf_pj,$conta->fornecedor->cnpj,$data_pagamento,$conta->valor,$conta->fornecedor->nome);
            $contador++;
        }
        $remessa .=trailer_lote(strval($contador+1),number_format($total, 2,'',''));
    }
    $remessa .= trailer_arquivo(strval($num_lote),strval($contador+3));
    return $remessa;
}

function remessa_boleto_guia($contas,$n_arquivo,$data_pagamento_remessa,$cnpj = '09338999000158',$pf_pj = '2',$convenio = '164544',$agencia = '200',$dv_agencia= '3',$conta_conv="28629",$dv_conta= 'X',$n_empresa = 'SHOW PRESTADORA DE SERVICOS DO'){
    $num_lote = 0;
    $remessa = "";
    $remessa .= header_file($cnpj,$pf_pj,$convenio,$agencia,$dv_agencia,$conta_conv,$dv_conta,$n_empresa,$n_arquivo);
    $data_pagamento = explode('-',$data_pagamento_remessa);
    $data_pagamento = $data_pagamento[2].$data_pagamento[1].$data_pagamento[0];
    if(count($contas)){
        $num_lote++;
        $remessa .= header_lote($cnpj,$pf_pj,$convenio,$agencia,$dv_agencia,$conta_conv,$dv_conta,$n_empresa,$n_arquivo,'98','11',$num_lote,"  CLT40026","020");
        $contador = 1;
        $total = 0;
        foreach($contas as $conta){
            $total+=floatval($conta->valor);
            $remessa .= segmento_o(strval($contador),$data_pagamento,$conta->valor,$num_lote,$conta->codigo_barra,$conta->fornecedor->nome,$conta->conta_id);
            $contador++;
        }
        $remessa .=trailer_lote(strval($contador+1),number_format($total, 2,'',''));
    }
    $remessa .= trailer_arquivo(strval($num_lote),strval($contador+3));
    return $remessa;
}

function header_file($cnpj_in,$pf_pj,$convenio_in,$ag_in,$dv_ag,$conta_in,$dv_conta,$empresa,$n_arquivo_in){
    //preenchendo o cnpj
    $cnpj = "";
    for($i= 14-strlen($cnpj_in);$i>0;$i--){
        $cnpj .= "0";
    }
    $cnpj.=$cnpj_in;

    //preenchendo convenio
    $convenio="";
    for($i= 9-strlen($convenio_in);$i>0;$i--){
        $convenio .= "0";
    }
    $convenio.=$convenio_in;

    //preenchendo agencia
    $ag="";
    for($i= 5-strlen($ag_in);$i>0;$i--){
        $ag .= "0";
    }
    $ag.=$ag_in;

    //preenchendo conta
    $conta="";
    for($i= 12-strlen($conta_in);$i>0;$i--){
        $conta .= "0";
    }
    $conta.=$conta_in;

    //preenchendo empresa
    for($i= 30-strlen($empresa);$i>0;$i--){
        $empresa .= " ";
    }

    //preenchendo número arquivo
    $n_arquivo="";
    for($i= 6-strlen($n_arquivo_in);$i>0;$i--){
        $n_arquivo .= "0";
    }
    $n_arquivo.=$n_arquivo_in;

    $header_file = "";

    $header_file = "001"; //Código do Banco na Compensação  1 3 3
    $header_file .= "0000"; //Lote de Serviço  4 7 4
    $header_file .= "0"; //Tipo de Registro  8 8 1
    for ($i = 0; $i < 9; $i++) { //Uso Exclusivo FEBRABAN/CNAB 9 17 9
        $header_file .= " ";
    }
    $header_file .= $pf_pj; //CNPJ 18 18 1
    $header_file .= $cnpj; //CNPJ 19 32 14
    //$texto_retorno .= "09338999000158123456"; //Código do Convênio no Banco 33 52 20 
    $header_file .= $convenio; //Convênio de pagamento  33 41 9
    $header_file .= "0126"; //Código 42 45 4
    for ($i = 0; $i < 5; $i++) { //Uso Reservado do Banco  46 50 5
        $header_file .= " ";
    }
    for ($i = 0; $i < 2; $i++) { //Arquivo de teste 51 52 2
        $header_file .= " ";
    }
    $header_file .= $ag; //Agência Mantenedora da Conta 53 57 5
    $header_file .= $dv_ag; //Dígito Verificador da Agência 58 58 1
    $header_file .= $conta; //Número da Conta 59 70 12
    $header_file .= $dv_conta; //Dígito Verificador da conta  71 71 1
    $header_file .= "0"; //Dígito Verificador da Ag/Conta  72 72 1
    $header_file .= $empresa; //Número da Empresa 73 102 30
    $header_file .= "BANCO DO BRASIL S/A           "; //Nome do Banco  103 132 30
    for ($i = 0; $i < 10; $i++) { //Uso Exclusivo FEBRABAN/CNAB 133 142 10
        $header_file .= " ";
    }
    $header_file .= "1"; //Código Remessa / Retorno 143 143 1 
    $header_file .= date('dmY'); //Data de Geração do Arquivo DDMMAAAA 144 151 8
    $header_file .= "000000"; //Hora da Geração do Arquivo HHMMSS 152 157 6
    $header_file .= $n_arquivo; //Número Sequencial do Arquivo 158 163 6
    $header_file .= "050"; //Nº Versão do Layout do Arquivo  164 166 3 
    //$texto_retorno .= "     "; //Densidade de Gravação do Arquivo  167 171 5
    for ($i = 0; $i <74; $i++) { //Para Uso Reservado do Banco  172 191 19
        $header_file .= " ";
    }
    /*for ($i = 0; $i < 32; $i++) { //Para Uso Reservado da Empresa  192 211 20 + 12(faltando na documentação)
        $texto_retorno .= " ";
    }
    for ($i = 0; $i < 3; $i++) { //Identificação cobrança sem papel 223 225 3
        $texto_retorno .= " ";
    }
    $texto_retorno .= "000"; //Uso exclusivo das VANS 226 228 3
    $texto_retorno .= "00"; //Tipo de Serviço 229 230 2
    $texto_retorno .= "0000000000\n"; //Códigos de Ocorrências 231 240 10*/
    $header_file .= "\n";

    return $header_file;
}

function header_lote($cnpj_in,$pf_pj,$convenio_in,$ag_in,$dv_ag,$conta_in,$dv_conta,$empresa,$n_arquivo_in,$tipo_servico="30",$forma_lancamento="01",$n_lote=1,$versão="V.PAG10136",$layout="031"){
    
    $n_lote1=strval($n_lote);
    $n_lote = "";
    for($i = 4-strlen($n_lote1);$i>0;$i--){
        $n_lote.="0";
    }
    $n_lote.=$n_lote1;

    //preenchendo o cnpj
    $cnpj = "";
    for($i= 14-strlen($cnpj_in);$i>0;$i--){
        $cnpj .= "0";
    }
    $cnpj.=$cnpj_in;

    //preenchendo convenio
    $convenio="";
    for($i= 9-strlen($convenio_in);$i>0;$i--){
        $convenio .= "0";
    }
    $convenio.=$convenio_in;

    //preenchendo agencia
    $ag="";
    for($i= 5-strlen($ag_in);$i>0;$i--){
        $ag .= "0";
    }
    $ag.=$ag_in;

    //preenchendo conta
    $conta="";
    for($i= 12-strlen($conta_in);$i>0;$i--){
        $conta .= "0";
    }
    $conta.=$conta_in;

    //preenchendo empresa
    for($i= 30-strlen($empresa);$i>0;$i--){
        $empresa .= " ";
    }

    //preenchendo número arquivo
    $n_arquivo="";
    for($i= 6-strlen($n_arquivo_in);$i>0;$i--){
        $n_arquivo .= "0";
    }
    $n_arquivo.=$n_arquivo_in;

    $header_lote = "";

    $header_lote .= "001";//Código do Banco na Compensação 1 3
    $header_lote .= $n_lote;//Lote de Serviço 4 7
    $header_lote .= "1";//Tipo de Registro 8 8
    $header_lote .= "C";//Tipo da Operação 9 9
    $header_lote .= $tipo_servico;//Tipo do Serviço 10 11
    $header_lote .= $forma_lancamento;//Forma de Lançamento 12 13
    $header_lote .= $layout;//Nº da Versão do Layout do Lote 14 16 3
    $header_lote .= " ";//Uso Exclusivo da FEBRABAN/CNAB 17 17
    $header_lote .= $pf_pj;//Tipo de Inscrição da Empresa 18 18
    $header_lote .= $cnpj;//Número de Inscrição da Empresa 19 32
    $header_lote .= $convenio;//Nº do Convênio 33 41
    $header_lote .= "0126";//Código 42 45
    $header_lote .= "     ";//Uso Reservado do Banco 46 50
    $header_lote .= "  ";//Número da Conta Corrente 59 70
    $header_lote .= $ag;
    $header_lote .= $dv_ag;
    $header_lote .= $conta;
    $header_lote .= $dv_conta;
    $header_lote .= "0";
    $header_lote .= $empresa;
    $header_lote .= "                                        ";
    $header_lote .= "                              ";
    $header_lote .= "     ";
    $header_lote .= "               ";
    $header_lote .= "                    ";
    $header_lote .= "     ";
    $header_lote .= "   ";
    $header_lote .= "  ";
    $header_lote .= "        ";
    $header_lote .= $versão."\n";
    return $header_lote;
}

function segmento_a($n_linha_in,$banco,$ag,$digito_agencia,$conta,$digito_corrente,$nome,$data_pagamento,$valor,$cod_empresa_in,$c_camara="000",$n_lote=1){
    
    $n_lote1=strval($n_lote);
    $n_lote = "";
    for($i = 4-strlen($n_lote1);$i>0;$i--){
        $n_lote.="0";
    }
    $n_lote.=$n_lote1;

    $cod_empresa = "";
    for($i=20-strlen($cod_empresa_in);$i>0;$i--){
        $cod_empresa .= "0";
    }
    $cod_empresa .= $cod_empresa_in;
    $nome = substr($nome, 0,30);
    for($i=strlen($nome);$i<30;$i++){
        $nome .= " ";
    }
    $valor=number_format($valor, 2,'','');
    $valor_15="";
    for($i=15-strlen($valor);$i>0;$i--){
        $valor_15 .= "0";
    }
    $valor_15.=$valor;
    
    $ag_5="";
    for($i=5-strlen($ag);$i>0;$i--){
        $ag_5 .= "0";
    }
    $ag_5 .= substr($ag,0,5);

    $conta_12="";
    $conta_format = str_replace(' ', '', $conta);
    for($i=12-strlen($conta_format);$i>0;$i--){
        $conta_12 .= "0";
    }
    // Número da conta     
    $conta_12 .= substr($conta_format,0,12);

    $segmento_a = "";

    $n_linha="";
    for($i=5-strlen($n_linha_in);$i>0;$i--){
        $n_linha .= "0";
    }
    $n_linha.=$n_linha_in;
    if($digito_agencia==""){
        $digito_agencia=" ";
    }
    if($digito_corrente==""){
        $digito_corrente=" ";
    }
    $segmento_a .= "001"; //Código no Banco da Compensação 1 3 3
    $segmento_a .= $n_lote; //Lote de Serviço 4 7 4
    $segmento_a .= "3"; //Tipo de Registro 8 8 1
    $segmento_a .= $n_linha; //Nº Seqüencial do Registro no Lote 9 13 5
    $segmento_a .= "A"; //Código de Segmento no Reg. Detalhe 14 14 1
    $segmento_a .= "0"; //Tipo de Movimento 15 15 1
    $segmento_a .= "00"; //Código da Instrução p/ Movimento 16 17 2
    $segmento_a .= $c_camara; //Código da Câmara Centralizadora 18 20 3
    $segmento_a .= $banco; //Código do Banco Favorecido 21 23 3
    $segmento_a .= $ag_5; //Ag. Mantenedora da Conta Favorec. 24 28 5
    $segmento_a .= $digito_agencia; //Dígito Verificador da Agência 29 29 1
    $segmento_a .= $conta_12; //Número da Conta Corrente 30 41 12
    $segmento_a .= $digito_corrente; //Dígito Verificador da Conta Corren. 42 42 1
    $segmento_a .= " "; //Dígito Verificador Agência/Conta 43 43 1
    $segmento_a .= $nome;
    $segmento_a .= $cod_empresa; //N° do Docto Atribuído pela Empresa 74 93 20 
    $segmento_a .= $data_pagamento; //Data do Pagamento 94 101 8
    $segmento_a .= "BRL"; //Tipo da Moeda 102 104 3
    $segmento_a .= "000000000000000"; //Quantidade da Moeda 105 119 10
    $segmento_a .= $valor_15; //Valor do Pagamento 120 134 13 2
    for ($i = 0; $i < 20; $i++) { //N° do Docto Atribuído pelo Banco 135 154 20
        $segmento_a .= " ";
    }
    $segmento_a .= $data_pagamento; //Data Real da Efetivação do Pagto 155 162 8
    $segmento_a .= $valor_15; //Valor Real da Efetivação do Pagto 163 177 13                    
    $segmento_a .= "                                        "; //Outras Informações 178 217 40
    $segmento_a .= "  "; //Compl. Tipo Serviço 218 219 2
    $segmento_a .= "     "; //Código Finalidade da TED 220 224 5
    $segmento_a .= "  "; //Complemente de Finalidade Pagto 225 226 2
    $segmento_a .= "   "; //Uso Exclusivo Febraban 227 229 3
    $segmento_a .= "0"; //Aviso ao Fornecedor 230 230 1
    $segmento_a .= "          \n"; //Código das Ocorrências p/ Retorno 231 240 10
    return $segmento_a;
}
function segmento_b($qtd_linha_in,$pf_pj,$cnpj_cpf,$data_pagamento,$valor,$n_lote=1){

    $n_lote1=strval($n_lote);
    $n_lote = "";
    for($i = 4-strlen($n_lote1);$i>0;$i--){
        $n_lote.="0";
    }
    $n_lote.=$n_lote1;

    $cnpj_cpf_14="";
    for($i=14-strlen($cnpj_cpf);$i>0;$i--){
        $cnpj_cpf_14 .= "0";
    }
    $cnpj_cpf_14 .= $cnpj_cpf;

    $valor=number_format($valor, 2,'','');
    $valor_15="";
    for($i=15-strlen($valor);$i>0;$i--){
        $valor_15 .= "0";
    }
    
    $valor_15.=$valor;

    $qtd_linha="";
    for($i= 5-strlen($qtd_linha_in);$i>0;$i--){
        $qtd_linha .= "0";
    }
    $qtd_linha.=$qtd_linha_in;

    $segmento_b = "";
    $segmento_b .= "001";
    $segmento_b .= $n_lote;
    $segmento_b .= "3";
    $segmento_b .= $qtd_linha;
    $segmento_b .= "B";
    $segmento_b .= "   ";
    $segmento_b .=$pf_pj;
    $segmento_b .=$cnpj_cpf_14;
    $segmento_b .="                                                                                               ";
    $segmento_b .= $data_pagamento;
    $segmento_b .= $valor_15;
    $segmento_b .="                                                                                          \n";
    return $segmento_b;
}

function segmento_j($qtd_linha_in,$data_pagamento,$valor,$n_lote=1,$codigo_barras,$beneficiario,$id_conta){
    $n_lote1=strval($n_lote);
    $n_lote = "";
    for($i = 4-strlen($n_lote1);$i>0;$i--){
        $n_lote.="0";
    }
    $n_lote.=$n_lote1;

    $beneficiario = substr($beneficiario, 0,30);
    for($i=strlen($beneficiario);$i<30;$i++){
        $beneficiario .= " ";
    }

    $id_conta = substr($id_conta, 0,20);
    for($i=strlen($id_conta);$i<20;$i++){
        $id_conta .= " ";
    }

    $valor=number_format(floatval($valor), 2,'','');

    $valor_15="";
    for($i=15-strlen($valor);$i>0;$i--){
        $valor_15 .= "0";
    }
    
    $valor_15.=$valor;

    $qtd_linha="";
    for($i= 5-strlen($qtd_linha_in);$i>0;$i--){
        $qtd_linha .= "0";
    }
    $qtd_linha.=$qtd_linha_in;
    $vencimento_nominal = date('dmY', strtotime("+".substr($codigo_barras,5,4)." days", strtotime(date('1997-10-07'))));
    $segmento_j = "";
    $segmento_j .= "001";
    $segmento_j .= $n_lote;
    $segmento_j .= "3";
    $segmento_j .= $qtd_linha;
    $segmento_j .= "J";
    $segmento_j .= "0";
    $segmento_j .= "00";
    $segmento_j .=$codigo_barras;
    $segmento_j .=$beneficiario;
    $segmento_j .= $vencimento_nominal;
    $segmento_j .= "00000".substr($codigo_barras,9,10);
    $segmento_j .= "000000000000000000000000000000";
    $segmento_j .= $data_pagamento;
    $segmento_j .= $valor_15;
    $segmento_j .= "000000000000000";
    $segmento_j .= $id_conta;
    $segmento_j .="                                      \n";
    return $segmento_j;
}

function segmento_j52($qtd_linha_in,$pf_pj,$cnpj_cpf,$data_pagamento,$valor,$beneficiario,$n_lote=1){

    $n_lote1=strval($n_lote);
    $n_lote = "";
    for($i = 4-strlen($n_lote1);$i>0;$i--){
        $n_lote.="0";
    }
    $n_lote.=$n_lote1;

    $cnpj_cpf_15="";
    for($i=15-strlen($cnpj_cpf);$i>0;$i--){
        $cnpj_cpf_15 .= "0";
    }
    $cnpj_cpf_15 .= $cnpj_cpf;
    $valor=number_format(floatval($valor), 2,'','');

    $valor_15="";
    for($i=15-strlen($valor);$i>0;$i--){
        $valor_15 .= "0";
    }
    $beneficiario = substr($beneficiario, 0,40);
    for($i=strlen($beneficiario);$i<40;$i++){
        $beneficiario .= " ";
    }
    $valor_15.=$valor;

    $qtd_linha="";
    for($i= 5-strlen($qtd_linha_in);$i>0;$i--){
        $qtd_linha .= "0";
    }
    $qtd_linha.=$qtd_linha_in;

    $segmento_j = "";
    $segmento_j .= "001";
    $segmento_j .= $n_lote;
    $segmento_j .= "3";
    $segmento_j .= $qtd_linha;
    $segmento_j .= "J";
    $segmento_j .= " 0052";
    $segmento_j .= "2";
    $segmento_j .= "009338999000158";
    $segmento_j .= "SHOW PRESTADORA DE SERVICOS DO          ";
    $segmento_j .= $pf_pj;
    $segmento_j .= $cnpj_cpf_15;
    $segmento_j .= $beneficiario;
    $segmento_j .="0000000000000000                                                                                             \n";
    return $segmento_j;
}

function segmento_o($qtd_linha_in,$data_pagamento,$valor,$n_lote=1,$codigo_barras,$beneficiario,$id_conta){
    $n_lote1=strval($n_lote);
    $n_lote = "";
    for($i = 4-strlen($n_lote1);$i>0;$i--){
        $n_lote.="0";
    }
    $n_lote.=$n_lote1;

    $beneficiario = substr($beneficiario, 0,30);
    for($i=strlen($beneficiario);$i<30;$i++){
        $beneficiario .= " ";
    }

    $id_conta = substr($id_conta, 0,20);
    for($i=strlen($id_conta);$i<20;$i++){
        $id_conta .= " ";
    }
    $valor=number_format(floatval($valor), 2,'','');
    $valor_15="";
    for($i=15-strlen($valor);$i>0;$i--){
        $valor_15 .= "0";
    }
    
    $valor_15.=$valor;

    $qtd_linha="";
    for($i= 5-strlen($qtd_linha_in);$i>0;$i--){
        $qtd_linha .= "0";
    }
    $qtd_linha.=$qtd_linha_in;
    $segmento_j = "";
    $segmento_j .= "001";
    $segmento_j .= $n_lote;
    $segmento_j .= "3";
    $segmento_j .= $qtd_linha;
    $segmento_j .= "O";
    $segmento_j .= "0";
    $segmento_j .= "00";
    $segmento_j .=$codigo_barras;
    $segmento_j .=$beneficiario;
    $segmento_j .= $data_pagamento;
    $segmento_j .= $data_pagamento;
    $segmento_j .= $valor_15;
    $segmento_j .= $id_conta;
    $segmento_j .= "                                                                                                  \n";
    return $segmento_j;
}

function trailer_lote($qtd_linha_in,$soma_valor_in,$n_lote=1){
    
    $n_lote1=strval($n_lote);
    $n_lote = "";
    for($i = 4-strlen($n_lote1);$i>0;$i--){
        $n_lote.="0";
    }
    $n_lote.=$n_lote1;
    
    $qtd_linha="";
    for($i= 6-strlen($qtd_linha_in);$i>0;$i--){
        $qtd_linha .= "0";
    }
    $qtd_linha.=$qtd_linha_in;

    $soma_valor="";
    for($i= 18-strlen($soma_valor_in);$i>0;$i--){
        $soma_valor .= "0";
    }
    $soma_valor.=$soma_valor_in;

    $trailer_lote = "";
    $trailer_lote .="001";
    $trailer_lote .=$n_lote;
    $trailer_lote .="5";
    $trailer_lote .="         ";
    $trailer_lote .=$qtd_linha;
    $trailer_lote .=$soma_valor;
    $trailer_lote .="                  ";//
    $trailer_lote .="      ";//Número Aviso de Débito 60 65
    $trailer_lote .="                                                                                                                                                                     ";//Uso Exclusivo FEBRABAN/CNAB 66 230
    $trailer_lote .="          \n";//Códigos das Ocorrências para Retorno 231 240 10
    return $trailer_lote;
}

function trailer_arquivo($qtd_lote_in,$qtd_linha_in){

    $qtd_lote="";
    for($i= 6-strlen($qtd_lote_in);$i>0;$i--){
        $qtd_lote .= "0";
    }
    $qtd_lote.=$qtd_lote_in;

    $qtd_linha="";
    for($i= 6-strlen($qtd_linha_in);$i>0;$i--){
        $qtd_linha .= "0";
    }
    $qtd_linha.=$qtd_linha_in;
    
    $trailer_arquivo ="";

    $trailer_arquivo .="001"; //Código do Banco na Compensação 1 3
    $trailer_arquivo .="9999";//Lote de Serviço 4 7 
    $trailer_arquivo .="9";//Tipo de Registro 8 8
    $trailer_arquivo .="         ";//Uso Exclusivo BANCO 9 17
    $trailer_arquivo .=$qtd_lote;//Quantidade de Lotes do Arquivo 18 23
    $trailer_arquivo .=$qtd_linha;//Quantidade de Registros do Arquivo 24 29
    for ($i = 0; $i < 211; $i++) { //Qtde de Contas p/ Conc. (Lotes) 30 35 Uso Exclusivo BANCO 36 240 205
        $trailer_arquivo .= " ";
    }
    return $trailer_arquivo;
}