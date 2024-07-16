<?php
// +----------------------------------------------------------------------+
// | BoletoPhp - Versão Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo está disponível sob a Licença GPL disponível pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Você deve ter recebido uma cópia da GNU Public License junto com     |
// | esse pacote; se não, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colaborações de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de João Prado Maia e Pablo Martins F. Costa				  |
// | 																	  |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +-------------------------------------------------------------------------------------------------------------------------+
// | Equipe Coordenação Projeto BoletoPhp: <boletophp@boletophp.com.br>              					                               |
// | Desenvolvimento Boleto Banco do Brasil: Daniel William Schultz / Leandro Maniezo / Rogério Dias Pereira / Romeu Medeiros|
// +-------------------------------------------------------------------------------------------------------------------------+

include_once('funcao_bb_correcao.php');

$codigobanco = "001";
$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
$nummoeda = "9";
$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

//valor tem 10 digitos, sem virgula
$valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
//agencia é sempre 4 digitos
$agencia = formata_numero($dadosboleto["agencia"],4,0);
//conta é sempre 8 digitos
$conta = formata_numero($dadosboleto["conta"],8,0);
//carteira 18
$carteira = $dadosboleto["carteira"];
//agencia e conta
$agencia_codigo = $agencia."-". modulo_11($agencia) ." / ". $conta ."-". modulo_11($conta);
//Zeros: usado quando convenio de 7 digitos
$livre_zeros='000000';

// Carteira 18 com Convênio de 8 dígitos
if ($dadosboleto["formatacao_convenio"] == "8") {
	$convenio = formata_numero($dadosboleto["convenio"],8,0,"convenio");
	// Nosso número de até 9 dígitos
	$nossonumero = formata_numero($dadosboleto["nosso_numero"],9,0);
	$dv=modulo_11("$codigobanco$nummoeda$fator_vencimento$valor$livre_zeros$convenio$nossonumero$carteira");
	$linha="$codigobanco$nummoeda$dv$fator_vencimento$valor$livre_zeros$convenio$nossonumero$carteira";
	//montando o nosso numero que aparecerá no boleto
	$nossonumero = $convenio . $nossonumero ."-". modulo_11($convenio.$nossonumero);
}

// Carteira 18 com Convênio de 7 dígitos
if ($dadosboleto["formatacao_convenio"] == "7") {
	$convenio = formata_numero($dadosboleto["convenio"],7,0,"convenio");
	// Nosso número de até 10 dígitos
	$nossonumero = formata_numero($dadosboleto["nosso_numero"],10,0);
	$dv=modulo_11("$codigobanco$nummoeda$fator_vencimento$valor$livre_zeros$convenio$nossonumero$carteira");
	$linha="$codigobanco$nummoeda$dv$fator_vencimento$valor$livre_zeros$convenio$nossonumero$carteira";
  $nossonumero = $convenio.$nossonumero;
	//Não existe DV na composição do nosso-número para convênios de sete posições
}

// Carteira 18 com Convênio de 6 dígitos
if ($dadosboleto["formatacao_convenio"] == "6") {
	$convenio = formata_numero($dadosboleto["convenio"],6,0,"convenio");
	
	if ($dadosboleto["formatacao_nosso_numero"] == "1") {
		
		// Nosso número de até 5 dígitos
		$nossonumero = formata_numero($dadosboleto["nosso_numero"],5,0);
		$dv = modulo_11("$codigobanco$nummoeda$fator_vencimento$valor$convenio$nossonumero$agencia$conta$carteira");
		$linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$convenio$nossonumero$agencia$conta$carteira";
		//montando o nosso numero que aparecerá no boleto
		$nossonumero = $convenio . $nossonumero ."-". modulo_11($convenio.$nossonumero);
	}
	
	if ($dadosboleto["formatacao_nosso_numero"] == "2") {
		
		// Nosso número de até 17 dígitos
		$nservico = "21";
		$nossonumero = formata_numero($dadosboleto["nosso_numero"],17,0);
		$dv = modulo_11("$codigobanco$nummoeda$fator_vencimento$valor$convenio$nossonumero$nservico");
		$linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$convenio$nossonumero$nservico";
	}
}

$dadosboleto["codigo_barras"] = $linha;
$dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
$dadosboleto["agencia_codigo"] = $agencia_codigo;
$dadosboleto["nosso_numero"] = $nossonumero;
$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;


// FUNÇÕES
// Algumas foram retiradas do Projeto PhpBoleto e modificadas para atender as particularidades de cada banco


?>
