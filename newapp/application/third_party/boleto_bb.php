<?php
// +----------------------------------------------------------------------+
// | BoletoPhp - Vers�o Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo est� dispon�vel sob a Licen�a GPL dispon�vel pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Voc� deve ter recebido uma c�pia da GNU Public License junto com     |
// | esse pacote; se n�o, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colabora��es de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de Jo�o Prado Maia e Pablo Martins F. Costa				        |
// | 														                                   			  |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +--------------------------------------------------------------------------------------------------------+
// | Equipe Coordena��o Projeto BoletoPhp: <boletophp@boletophp.com.br>              		             				|
// | Desenvolvimento Boleto Banco do Brasil: Daniel William Schultz / Leandro Maniezo / Rog�rio Dias Pereira|
// +--------------------------------------------------------------------------------------------------------+


// ------------------------- DADOS DIN�MICOS DO SEU CLIENTE PARA A GERA��O DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formul�rio c/ POST, GET ou de BD (MySql,Postgre,etc)	//
include '/classes/Util.class.php';
//echo 'okokkoookoko';
//
// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = 5;
$taxa_boleto = $boleto;
$data_venc = $venc;
//$data_venc = $boleto_vencimento;
$juros_mes = 8;

        //date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
$valor_cobrado = $soma;
if ($status == 0){
    $valor_cobrado = $valor_boleto;
    $data_venc = $boleto_vencimento;
}

//$diferenca = Util::geraTimestamp(Util::data_for_humans(date('Y-m-d'))) - Util::geraTimestamp(Util::data_for_humans($data_venc));
//$dias = (int)floor( $diferenca / (60 * 60 * 24));
//$valor = $valor_cobrado;
//$dias>0? $data_venc=date('d/m/Y'): $data_venc =  $data_venc;
//$dias>0?$valor_cobrado=Util::corrigir_valor($valor,$dias):$valor_cobrado=$valor_cobrado;


                        
//"2950,00"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
$Mvalor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '.'); // para mostrar

$dadosboleto["nosso_numero"] = $fatura;
//"87654";
$dadosboleto["numero_documento"] = $fatura;
//"27.030195.10";	// Num do pedido ou do documento
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = $emis;
//date("d/m/Y"); // Data de emiss�o do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com v�rgula e sempre com duas casas depois da virgula
$dadosboleto["juros"] = (($valor_cobrado * $juros_mes)/100)/30;
$dadosboleto["Mvalor_boleto"] = $Mvalor_boleto; 	// Valor do Boleto - REGRA: Com v�rgula e sempre com duas casas depois da virgula
//
// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] = $clibol; //"Nome do seu Cliente");
$dadosboleto["endereco1"] = utf8_decode("Endereço do seu Cliente");
$dadosboleto["endereco2"] = utf8_decode("Cidade - Estado -  CEP: 00000-000");

// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = utf8_encode("Pagamento referente fatura: $fatura");
if ($taxa_boleto>0){
$dadosboleto["demonstrativo2"] = "<br>Taxa bancária - R$ ".number_format($taxa_boleto, 2, ',', '');
}
if ($boleto == 0){
    //$dadosboleto["demonstrativo2"] = utf8_decode("");
}
$dadosboleto["demonstrativo3"] = utf8_encode("2a. via - http://www.showtecnologia.com/financeiro");

// INSTRU��ES PARA O CAIXA
$dadosboleto["instrucoes1"] = "<br>- Sr. Caixa, cobrar multa de 2% após o vencimento e juros de R$ ".number_format($dadosboleto["juros"], 2, ',', '')." ao dia.";
$dadosboleto["instrucoes2"] = "- Receber até ".$dias_de_prazo_para_pagamento." dias após o vencimento";
$dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: (83) 3271-6559";
$dadosboleto["instrucoes4"] = "- Email: financeiro@showtecnologia.com";

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = ''; //$Mvalor_boleto;
$dadosboleto["aceite"] = 'N';  //$aceite;	// indicando se o comprador  'aceitou'  o título 
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DM"; //Duplicata Mercantil


// ---------------------- DADOS FIXOS DE CONFIGURA��O DO SEU BOLETO --------------- //


// DADOS DA SUA CONTA - BANCO DO BRASIL
$dadosboleto["agencia"] = "0200"; // Num da agencia, sem digito
$dadosboleto["conta"] = "28629"; 	// Num da conta, sem digito

// DADOS PERSONALIZADOS - BANCO DO BRASIL
$dadosboleto["convenio"] = "2409314";  // Num do conv�nio - REGRA: 6 ou 7 ou 8 d�gitos
//$dadosboleto["convenio"] = "2325061";  // Num do conv�nio - REGRA: 6 ou 7 ou 8 d�gitos
//CONVENIO:  2409314 - sem registro    --    2325061 - com registro
$dadosboleto["contrato"] = "018899626"; // Num do seu contrato
//$dadosboleto["contrato"] = "018810720"; // Num do seu contrato
//CONTRATO:  018899626 - sem registro  --    018810720 - com registro
$dadosboleto["carteira"] = "18";    // 18 - sem registro   --   17 - com registro
//$dadosboleto["carteira"] = "17";    // 18 - sem registro   --   17 - com registro
$dadosboleto["variacao_carteira"] = "-019";  // Varia��o da Carteira, com tra�o (opcional)

// TIPO DO BOLETO
$dadosboleto["formatacao_convenio"] = "7"; // REGRA: 8 p/ Conv�nio c/ 8 d�gitos, 7 p/ Conv�nio c/ 7 d�gitos, ou 6 se Conv�nio c/ 6 d�gitos
$dadosboleto["formatacao_nosso_numero"] = "2"; // REGRA: Usado apenas p/ Conv�nio c/ 6 d�gitos: informe 1 se for NossoN�mero de at� 5 d�gitos ou 2 para op��o de at� 17 d�gitos

/*
#################################################
DESENVOLVIDO PARA CARTEIRA 18

- Carteira 18 com Convenio de 8 digitos
  Nosso n�mero: pode ser at� 9 d�gitos

- Carteira 18 com Convenio de 7 digitos
  Nosso n�mero: pode ser at� 10 d�gitos

- Carteira 18 com Convenio de 6 digitos
  Nosso n�mero:
  de 1 a 99999 para op��o de at� 5 d�gitos
  de 1 a 99999999999999999 para op��o de at� 17 d�gitos

#################################################
*/


// SEUS DADOS
$dadosboleto["identificacao"] = "SHOW PRESTADORA DE SERVICOS DO BRASIL LTDA";
$dadosboleto["cpf_cnpj"] = "09.338.999/0001-58";
$dadosboleto["endereco"] = "Av. Ruy Barbosa, 104 - Centro";
$dadosboleto["cidade_uf"] = "Guarabira - PB - CEP: 58.200-000";
$dadosboleto["cedente"] = "SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA";

// N�O ALTERAR!
include("funcoes_bb.php"); 
//include("layout_bb.php");
?>
