<?php
function start_boleto($dadosboleto){
    $codigobanco = "748";
    
    //$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
    $nummoeda = "9";
    
    //$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);
    
    //valor tem 10 digitos, sem virgula
    $valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
    //agencia � 4 digitos
    $agencia = formata_numero($dadosboleto["agencia"],4,0);
    //posto da cooperativa de credito � dois digitos
    $posto = formata_numero($dadosboleto["posto"],2,0);
    //conta � 5 digitos
    $conta = formata_numero($dadosboleto["conta"],5,0);
    //dv da conta
    $conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);
    //carteira � 2 caracteres
    $carteira = $dadosboleto["carteira"];
    
    //fillers - zeros Obs: filler1 contera 1 quando houver valor expresso no campo valor
    $filler1 = 1;
    $filler2 = 0;

    // Byte de Identifica��o do cedente 1 - Cooperativa; 2 a 9 - Cedente
    $byteidt = $dadosboleto["byte_idt"];

    // Codigo referente ao tipo de cobran�a: "3" - SICREDI
    $tipo_cobranca = 3;

    // Codigo referente ao tipo de carteira: "1" - Carteira Simples 
    $tipo_carteira = 1;

    //nosso n�mero (sem dv) � 8 digitos
    $nnum = $dadosboleto["inicio_nosso_numero"] . $byteidt . formata_numero($dadosboleto["nosso_numero"],5,0);
    
    //calculo do DV do nosso n�mero
    $dv_nosso_numero = digitoVerificador_nossonumero("$agencia$posto$conta$nnum");
    
    $nossonumero_dv ="$nnum$dv_nosso_numero";
    return $nossonumero_dv;
    /*//forma��o do campo livre
    $campolivre = "$tipo_cobranca$tipo_carteira$nossonumero_dv$agencia$posto$conta$filler1$filler2";
    $campolivre_dv = $campolivre . digitoVerificador_campolivre($campolivre); 

    // 43 numeros para o calculo do digito verificador do codigo de barras
    $dv = digitoVerificador_barra("$codigobanco$nummoeda$fator_vencimento$valor$campolivre_dv", 9, 0);

    // Numero para o codigo de barras com 44 digitos
    $linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$campolivre_dv";

    // Formata strings para impressao no boleto
    $nossonumero = substr($nossonumero_dv,0,2).'/'.substr($nossonumero_dv,2,6).'-'.substr($nossonumero_dv,8,1);
    $agencia_codigo = $agencia.".". $posto.".".$conta;

    $dadosboleto["codigo_barras"] = $linha;
    $dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
    $dadosboleto["agencia_codigo"] = $agencia_codigo;
    $dadosboleto["nosso_numero"] = $nossonumero;
    $dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;*/

}
// FUN��ES
    // Algumas foram retiradas do Projeto PhpBoleto e modificadas para atender as particularidades de cada banco
    function modulo_11($num, $base=9, $r=0)  {
        /**
         *   Autor:
         *           Pablo Costa <pablo@users.sourceforge.net>
         *
         *   Fun��o:
         *    Calculo do Modulo 11 para geracao do digito verificador 
         *    de boletos bancarios conforme documentos obtidos 
         *    da Febraban - www.febraban.org.br 
         *
         *   Entrada:
         *     $num: string num�rica para a qual se deseja calcularo digito verificador;
         *     $base: valor maximo de multiplicacao [2-$base]
         *     $r: quando especificado um devolve somente o resto
         *
         *   Sa�da:
         *     Retorna o Digito verificador.
         *
         *   Observa��es:
         *     - Script desenvolvido sem nenhum reaproveitamento de c�digo pr� existente.
         *     - Assume-se que a verifica��o do formato das vari�veis de entrada � feita antes da execu��o deste script.
         */                                        
    
        $soma = 0;
        $fator = 2;
    
        /* Separacao dos numeros */
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num,$i-1,1);
            // Efetua multiplicacao do numero pelo falor
            $parcial[$i] = $numeros[$i] * $fator;
            // Soma dos digitos
            $soma += $parcial[$i];
            if ($fator == $base) {
                // restaura fator de multiplicacao para 2 
                $fator = 1;
            }
            $fator++;
        }
    
        /* Calculo do modulo 11 */
        if ($r == 0) {
            $soma *= 10;
            $digito = $soma % 11;
            return $digito;
        } elseif ($r == 1){
            // esta rotina sofrer algumas altera��es para ajustar no layout do SICREDI
            $r_div = (int)($soma/11);
            $digito = ($soma - ($r_div * 11));
            return $digito;
        }
    }
    function formata_numero($numero,$loop,$insert,$tipo = "geral") {
        if ($tipo == "geral") {
            $numero = str_replace(",","",$numero);
            while(strlen($numero)<$loop){
                $numero = $insert . $numero;
            }
        }
        if ($tipo == "valor") {
            /*
            retira as virgulas
            formata o numero
            preenche com zeros
            */
            $numero = str_replace(",","",$numero);
            while(strlen($numero)<$loop){
                $numero = $insert . $numero;
            }
        }
        if ($tipo == "convenio") {
            while(strlen($numero)<$loop){
                $numero = $numero . $insert;
            }
        }
        return $numero;
    }


    function fbarcode($valor){

    $fino = 1 ;
    $largo = 3 ;
    $altura = 50 ;

    $barcodes[0] = "00110" ;
    $barcodes[1] = "10001" ;
    $barcodes[2] = "01001" ;
    $barcodes[3] = "11000" ;
    $barcodes[4] = "00101" ;
    $barcodes[5] = "10100" ;
    $barcodes[6] = "01100" ;
    $barcodes[7] = "00011" ;
    $barcodes[8] = "10010" ;
    $barcodes[9] = "01010" ;
    for($f1=9;$f1>=0;$f1--){ 
        for($f2=9;$f2>=0;$f2--){  
        $f = ($f1 * 10) + $f2 ;
        $texto = "" ;
        for($i=1;$i<6;$i++){ 
            $texto .=  substr($barcodes[$f1],($i-1),1) . substr($barcodes[$f2],($i-1),1);
        }
        $barcodes[$f] = $texto;
        }
    }

    
//Desenho da barra
}
function digitoVerificador_nossonumero($numero) {
    $resto2 = modulo_11($numero, 9, 1);
    // esta rotina sofrer algumas altera��es para ajustar no layout do SICREDI
    $digito = 11 - $resto2;
    if ($digito > 9 ) {
        $dv = 0;
    } else {
        $dv = $digito;
    }
return $dv;
}

function digitoVerificador_campolivre($numero) {
    $resto2 = modulo_11($numero, 9, 1);
// esta rotina sofreu algumas altera��es para ajustar no layout do SICREDI
    if ($resto2 <=1){
        $dv = 0;
    }else{
        $dv = 11 - $resto2;
    }
    return $dv;
}


function digitoVerificador_barra($numero) {
    $resto2 = modulo_11($numero, 9, 1);
// esta rotina sofrer algumas altera��es para ajustar no layout do SICREDI
    $digito = 11 - $resto2;
    if ($digito <= 1 || $digito >= 10 ) {
        $dv = 1;
    } else {
        $dv = $digito;
    }
    return $dv;
}
?>