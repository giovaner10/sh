<?php
     	include_once '../../includes/incVerificaSessao.php';
   	require_once '../../classes/Util.class.php';
	require_once '../../classes/Cadastro.class.php';
        
//require_once
        extract($_REQUEST);
	
	$CAD = new Cadastro();
	
	$cont = $CAD->coletaDados($id, 7);
        $cli  = $CAD->coletaDados($cont['id_cliente'], 1);

        // Array com os Estados
	$estados = Util::estados();        
        $nro = Util::zeros(6,$cont['id']);
        

        
        
        
        $arquivo  = 'contrato_'.$nro.'.pdf';
        $_SESSION['email'] = $cli['email'];
        $_SESSION['assunto'] = 'Contrato SHOW TECNOLOGIA';
        $_SESSION['email'] = 'lucianocomputador@ig.com.br';
        //$_SESSION['email'] = '';
        $_SESSION['arquivo'] = $arquivo;
        $cliente = $cli['contato'];
        $_SESSION['conteudo'] = utf8_decode($cli['email']."<br>
            <h2>Contrato de Serviço - SHOW TECNOLOGIA</h2>
            <br><br>
            Att. $cliente,<br><br> segue em anexo o CONTRATO DE PRESTAÇÃO DE SERVIÇOS
            <br><br><br>
            SHOW TECNOLOGIA<br>
            Rastreamento e Monitoramento Veícular<br>
            Fone (83) 3271.6559
            ");
//============================================================+
// File name   : example_003.php
// Begin       : 2008-03-04
// Last Update : 2010-08-08
//
// Description : Example 003 for TCPDF class
//               Custom Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               Manor Coach House, Church Hill
//               Aldershot, Hants, GU12 4RQ
//               UK
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Custom Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

require_once('../config/lang/eng.php');
require_once('../tcpdf.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	//Page header
/*
    public function Header() {
		// Logo
		//$image_file = K_PATH_IMAGES.'logo_example.jpg';
                $image_file = '../../templates/default/img/logo-showtecnologia.png';
		$this->Image($image_file, 5, 5, 40, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
                // Set font
//		$this->SetFont('helvetica', 'B', 20);
		// Title
//		$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	}
*/
	public function Header() {
		// get the current page break margin
		$bMargin = $this->getBreakMargin();
		// get current auto-page-break mode
		$auto_page_break = $this->AutoPageBreak;
		// disable auto-page-break
		$this->SetAutoPageBreak(false, 0);
		// set bacground image
		//$img_file = K_PATH_IMAGES.'image_demo.jpg';
                $img_file = '../../templates/default/img/marca-showtecnologia.png';
		$this->Image($img_file, 20, 70, 180, 270, 'PNG', '', '', false, 300, '', false, false, 0, 'M', false, false);
		// restore auto-page-break status
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		// set the starting point for the page content
		$this->setPageMark();
	}
        
	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);



// ---------------------------------------------------------

// set default font subsetting mode
///$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
///$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
///$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));


// Set some content to print
$html = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<title>SHOW TECNOLOGIA - CONTRATO</title>
<link href="../../templates/default/css/estilos.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.titulo {
	font-size: 42px;
	font-weight: bold;
}

.texto {
	font-size: 11px;
	text-align:justify;
}
.corpo {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 9px;
	width: 18cm;
	text-align: left;
	vertical-align: top;
}
p {
	font-size: 24px;
	text-align:justify;
}


.tablex {
	border-top:1px solid #cccccc;
	border-right:1px solid #cccccc;
	margin:1em auto;
	border-collapse:collapse;
	}
tr.odd td	{
	background:#f9f9f9;
	}
tr.odd .column1	{
	background:#f3f3f3;
	border-top:1px solid #cccccc;
	border-right:1px solid #cccccc;
	font-size: 22px;
	}	
.column1	{
	background:#f9f9f9;
	border-top:1px solid #cccccc;
	border-right:1px solid #cccccc;
	border-left:1px solid #cccccc;
	border-bottom: 1px solid #cccccc;
	font-size: 24px;
	}
.tdx {
	color:#666666;
	border-bottom:1px solid #cccccc;
	border-left:1px solid #cccccc;
	padding:.3em 1em;
	text-align:center;
	}				
.thx {
	font-weight:normal;
	color: #ff0000;
        text-align:center;
	border-bottom: 1px solid #cccccc;
	border-left:1px solid #cccccc;
	padding:.3em 1em;
        font-size: 24px;
	}							
.theadx th {
	background: #f0f0f0;
	text-align:center;
	font-family: "Trebuchet MS", Tahoma, Arial;
	font-size: 22px;
	color:#000000;
	}	

</style>

</head>

<body>

<table align="center" >
<tr>
<td class="corpo">

<div align="center" class="titulo"><center><img src="../../templates/default/img/logo-showtecnologia.png" height="50" /></center><br>CONTRATO DE LOCAÇÃO E PRESTAÇÃO DE SERVIÇOS <br>DE RASTREAMENTO E MONITORAMENTO VEICULAR</div>
<br><br>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Pelo presente instrumento particular, em que são partes, de um lado, SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA., 
sociedade com sede na Cidade de Guarabira, Estado da Paraíba, na Av. Ruy Barbosa, 104 - Centro - CEP: 58.200-000 - inscrita no CNPJ. sob nº 09.338.999/0001-58, 
neste ato representada na forma de seu Contrato Social, doravante denominada "<strong>Contratada</strong>"
; e, de outro lado, '.utf8_decode($cli['nome']).',  
inscrita no CNPJ '.  utf8_decode($cli['cnpj']).', endereço '.  utf8_decode($cli['endereco']).', bairro '.  utf8_decode($cli['bairro']).', cidade '.  utf8_decode($cli['cidade']).', estado '.$estados[$cli[uf]].', 
denominado "<strong>Contratante</strong>", têm entre si justa e <strong>Contratada</strong> 
a prestação de serviços de localização e monitoramento de veículos, 
que se regerá pelas seguintes cláusulas e condições:
</p>
        <br>
	
<p><strong>1. DO OBJETO</strong> - O objeto do presente contrato consiste na locação de '.$cont['quantidade_veiculos'].' módulos de rastreamento veicular;

podendo ser ampliando esta quantidade através solicitação por contato eletrônico, email, que servirá de anexo ao presente contrato, 
prestação de serviços de monitoramento, rastreamento digital on-line via web de localização de veículos denominado 
SHOWSYSTEMS E SHOWLINK pela <strong>Contratada</strong> ao <strong>Contratante</strong>, ou ao usuário por ele indicado.<br />
<br><strong>1.1</strong> - É de total responsabilidade do <strong>Contratante</strong> a conservação dos equipamentos instalados no veiculo.<br />
<br><strong>1.2</strong> - A INSTALAÇÃO, RETIRADA, REINSTALAÇÃO E TRANSFERÊNCIA só mediante comunicação e feita por técnicos da <strong>Contratada</strong>.
</p>
            <br>
<p><strong>2. SERVIÇOS</strong> - Os serviços pactuados compreendem exclusiva e restritivamente: 
    <br>(a) Monitoramento, rastreamento e localização do Veículo via web, nas hipóteses previstas na respectiva modalidade <strong>Contratada</strong>, somente em caso de roubo ou furto e demais de urgência; 
    <br>(b) Comunicação e colaboração com as autoridades competentes ou Prestadores de Serviços Autorizados sobre a localização do Veículo, excluídas todas e quaisquer situações não previstas expressamente neste instrumento.<br />
    <br><strong>2.1.</strong> A <strong>Contratada</strong> compromete-se a prestar os Serviços ora pactuados, de forma a disponibilizá-los por 24 (vinte e quatro) horas, 
durante os 07 (sete) dias da semana, 365 dias por ano, desde que o equipamento seja instalado por Centros de Atendimento Técnico ou Agentes Técnicos Autorizados, 
respeitados limites de áreas de cobertura e possíveis fatos oriundos de caso fortuito ou força maior.<br />
<br><strong>2.2.</strong> Código de Identificação<br />
<strong>2.2.1.</strong> O <strong>Contratante</strong> receberá seu Código de Identificação, com usuário e senha, ao término da instalação dos Equipamentos, 
que se dará num prazo de 15 dias úteis.<br />
<strong>2.2.2.</strong> O Código de Identificação é pessoal e secreto, sendo de exclusiva responsabilidade do <strong>Contratante</strong> 
a divulgação para pessoas não autorizadas.<br />
<strong>2.2.3.</strong> As pessoas autorizadas pela <strong>Contratante</strong> devera ser solicitado por escrito para uso do sistema e suas limitações de acesso.<br>
<strong>2.2.3.1.</strong> Acesso como bloqueio dos veículos ora relacionado neste contrato. A <strong>Contratante</strong> fica ciente que para uso do bloqueio o cliente deverá solicitar por escrito a liberação deste recurso, assim assumindo total responsabilidade de uso desse serviço.<br />
<strong>2.2.3.2.</strong> Relatórios periódicos de: Quilometragem do veiculo; Tempo motor ligado ou desligado com veiculo parado ou em movimento; Velocidade excedida; Trajeto da frota entre outros a serem imputados nas atualizações do sistema;<br />
<strong>2.2.3.3.</strong> Cria cerca eletrônica: Demarque no mapa  uma cerca, que lhe dará a segurança que o veículo não ultrapassará o local determinado pela empresa para o desenvolvimento do serviço designado.<br />
<strong>2.2.3.4.</strong> A <strong>Contratante</strong> tamb&eacute;m ter&aacute; acesso as seguintes informa&ccedil;&otilde;es:<br>
<strong>(a)</strong> Registro de velocidade, RPM, acelera&ccedil;&atilde;o e desacelera&ccedil;&atilde;o por segundo dos veiculos em determinado per&iacute;odo;<br>
<strong>(b)</strong> Leitura de coordenadas autom&aacute;tica via GPS, por transmiss&atilde;o local ou quaisquer outros meios que possibilitem a coleta de dados consistentes, fica a <strong>Contratante</strong> ciente do paragrafo &uacute;nico desta cl&aacute;usula;<br>
<strong>(c)</strong> Ainda no sistema informa&ccedil;&otilde;es dos registros das rotas percorridas pelos ve&iacute;culos;<br>
<strong>(d)</strong> Alarmes sonoros de: excesso de velocidade, rompimento de cerca eletrônica e desvio de rota com envio de informações via email e/ou software;<br>
<strong>(e)</strong> Registro de cada evento com a identificação do motorista, condicionando-o ao veículo por CNH ou com outros documentos vencidos;<br>
<strong>(f)</strong> Entre outras informações como Avaliação da jornada de trabalho; Identificação eletrônica do motorista/condutor; Estudo do perfil de cada condutor do veículo; Localização de veículos através de GPS (Global Positioning System)<br>
    <br>
<strong>Paragrafo único</strong> A <strong>Contratante</strong> está ciente e declara que a adequada execução dos Serviços está sujeita a interferência de fatores externos capazes de impedir o regular funcionamento do(s) equipamento(s) e a disponibilização das funcionalidades ora contratadas, independentemente 
das ações da <strong>Contratada</strong>. As limitações descritas clausula 6.<br />
<br>
<strong>2.3. Procedimento de Identificação</strong><br />
<strong>2.3.1.</strong> Para acionamento do sistema, <strong>Contratante</strong> ou Usuário do bem deverão dar conhecimento do fato a <strong>Contratada</strong> 
e informar o Código de Identificação correto do Veículo, ou a placa, chassi ou ainda o nome completo, ao operador da Central de Controle de Operações, 
identificando-se, indicando quaisquer informações de dados de confirmação solicitados.<br />
<strong>2.3.2.</strong> Em não havendo comunicação com a indicação de todos os dados solicitados, a <strong>Contratada</strong> 
fica desobrigada da prestação dos serviços, restando livre e indene de eventual dano.<br />
<br><strong>2.4. Serviço de localização em caso de notificação de furto ou roubo do Veículo via internet e online</strong><br />
<strong>2.4.1.</strong> Em caso de furto, roubo ou sequestro, o <strong>Contratante</strong> ou Usuários do bem acionará o sistema por meio do procedimento
 de identificação descrito na cláusula 2.2.<br />
<strong>2.4.2.</strong> No caso do Veículo ser localizado, a <strong>Contratada</strong> 
avisará a Polícia sobre o evento, fornecendo-lhes informações sobre a localização estimada do Veículo e sua descrição, conforme evidenciado pelo Sistema.<br />
<strong>2.4.3.</strong> O <strong>Contratante</strong> reconhece e concorda que a notificação à <strong>Contratada</strong> sobre o furto ou roubo do Veículo
 poderá acarretar ações de Polícia, que podem ensejar a retenção do veículo ou sujeição de danos ao próprio bem, inclusive sua apreensão, 
 sobre o qual não se responsabilizará a <strong>Contratada</strong>, diante da ausência de vinculação.<br />
<strong>2.4.4.</strong> Em caso de falsa notificação, o <strong>Contratante</strong> compromete-se a reembolsar e/ou indenizar a <strong>Contratada</strong> 
em relação a todos e quaisquer gastos e/ou prejuízos que venha a sofrer em decorrência de uma notificação falsa e/ou enganosa, sem prejuízo da aplicação de 
multa de 20% (vinte por cento) sobre o valor proporcional a 3 mensalidades do serviço.<br />
</p>
                <br>
<p><strong>3.VEÍCULO</strong><br />
<strong>3.1.</strong> O <strong>Contratante</strong> declara ser o proprietário do Veículo, assumindo total responsabilidade, sob as penas da lei, 
pela veracidade da declaração ora prestada e consequências dela advindas.<br />
<strong>3.2.</strong> O <strong>Contratante</strong> se obriga e se compromete a informar à <strong>Contratada</strong>, antecipadamente, 
sobre toda e qualquer transferência do Veículo a terceiros, ficando esclarecido que, até que isto ocorra, o <strong>Contratante</strong> 
permanecerá responsável, obrigado e vinculado a todos os termos e condições deste Contrato.<br />
</p>
                    <br>
<p><strong>4.EQUIPAMENTOS</strong><br />
<strong>4.1.</strong> O <strong>Contratante</strong> se compromete a observar integralmente as instruções fornecidas pela <strong>Contratada</strong> 
conforme Manual do Cliente, bem como a somente submeter os Equipamentos aos cuidados do Centro de Atendimento Técnico e do Agente Técnico Autorizado, 
conforme venha a ser informado pela <strong>Contratada</strong> ou pela Central de Controle de Operações.<br />
<strong>4.2.</strong> O <strong>Contratante</strong> poderá solicitar a transferência dos Equipamentos para outro veículo, 
mediante o pagamento da respectiva taxa de transferência, e prévia notificação a <strong>Contratada</strong>, bem como ao Centro de Atendimento Técnico
 e o Agente Técnico Autorizado, únicos competentes para o ato, permanecendo em vigor os termos e condições deste Contrato em relação ao novo veículo, 
 observado o disposto no item 4.4.<br />

<strong>4.3.</strong> A <strong>Contratada</strong> ratificará a transferência dos Equipamentos ao novo veículo se, e somente se, 
tiverem sido preenchidas as seguintes condições: (a) Comunicação prévia por parte da <strong>Contratante</strong> sobre os dados do novo veículo; 
(b) Alegação expressa do <strong>Contratante</strong> de que é proprietário do novo veículo; (c) A transferência dos Equipamentos ao outro Veículo 
foi realizada pelo Centro de Atendimento Técnico ou pelo Agente Técnico Autorizado.<br />
<strong>4.4.</strong> A transferência e/ou manutenção dos Equipamentos, sem a estrita observância do disposto nesta Cláusula, 
desobrigará a <strong>Contratada</strong> da prestação dos Serviços, assim como isentará a mesma de qualquer responsabilidade sobre eventuais incidentes, 
danos morais e/ou pessoais causados a terceiros.<br />
<strong>4.5.</strong> A garantia do Equipamento existe em quanto durar o contrato, e tem por exclusiva finalidade, a substituição ou reparação, 
que deverão ser realizados exclusivamente no Centro de Atendimento Técnico ou no Agente Técnico Autorizado, 
referente a comprovados vícios de fabricação e demais condições contidas no "Certificado de Garantia", 
parte integrante do Manual do Cliente.<br />
</p>
                        <br>
<p><strong>5. SIGILO DE DILIGÊNCIA</strong><br />
<strong>5.1.</strong> A <strong>Contratada</strong> se compromete a manter sempre em sigilo toda e qualquer informação 
relativa ao <strong>Contratante</strong>, Usuários Autorizados, e a localização do Veículo, exceto quando existir 
indícios de furto, roubo, ou as situações de emergência descritas nos itens 2.4.3, quando acionará, 
a Polícia e/ou os Prestadores de Serviços Autorizados, conforme a modalidade de Serviço escolhida nos termos deste Contrato.<br />
</p>
<br>
<p><strong>6. LIMITAÇÕES À PRESTAÇÃO DO SERVIÇO</strong><br />
<strong>6.1.</strong> A <strong>Contratante</strong> concorda e tem pleno conhecimento de que os serviços disponibilizados limitam-se, 
 única e exclusivamente, aos descritos na segunda cláusula da presente convenção, isentando-se de quaisquer danos ou fatos oriundos de 
caso fortuito ou força maior que impeçam ou dificultem a localização do veículo sinistrado, tais como: problemas de rede telefônica, 
condições climáticas e topográficas adversas, dentre outras situações alheias a esfera de responsabilidade contratual.<br />
<strong>Parágrafo 1&ordm;.</strong> Fica esclarecido que o vínculo ora estabelecido entre a <strong>Contratada</strong> e o <strong>Contratante</strong> 
não constitui e não representa, em hipótese alguma, um contrato de seguro, não havendo e/ou não implicando em qualquer cobertura, 
de qualquer natureza, para o <strong>Contratante</strong>, Usuários, Condutores, Veículo e/ou terceiros. Restando a obrigação da <strong>Contratada</strong>, 
quando localizado, o dever de comunicação às autoridades competentes. Consequentemente, a <strong>Contratada</strong> 
recomenda expressamente que o <strong>Contratante</strong> obtenha e mantenha sempre válida referida cobertura junto a uma companhia de seguros idônea.<br />
<strong>Parágrafo 2&ordm;.</strong> O <strong>Contratante</strong> declara que tem conhecimento de que poderáo ocorrer 
interferências na área de Cobertura, por motivos alheios à vontade da <strong>Contratada</strong>, causando eventuais falhas no 
recebimento e transmissão do sinal do Veículo. Está ciente, ainda, de que os Serviços poderáo ser temporariamente interrompidos ou restringidos: 
(i) se o <strong>Contratante</strong> viajar para fora da área de Cobertura; e/ou (ii) em decorrência de restrições operacionais, realocações, 
reparos e atividades similares, que se façam necessárias à apropriada operação e/ou à melhoria do Sistema; e/ou (iii) em decorrência de 
interferências de topografia, edificações, bloqueios, lugares fechados, condições atmosféricas, etc.; e/ou(iv) em decorrência de quedas e 
interrupções no fornecimento de energia e sinais de comunicação. Sendo que, na ocorrência das limitações acima mencionadas, a <strong>Contratada</strong> 
não poderá ser responsabilizada por quaisquer interrupções, atrasos ou defeitos nas transmissões.<br />
</p>
<br>
<p><strong>7. PREÇOS E CONDIÇÕES DE PAGAMENTO</strong><br />
<strong>7.1.</strong> A <strong>Contratante</strong> pagará a <strong>Contratada</strong> pelos serviços prestados da seguinte forma:</p>

<table class="tablex" width="90%" align="center"> 
  <thead  class="theadx"> 
  <tr class="odd">

    <th scope="col" abbr="Home" class="thx">Mensalidade por Veículo</th> 

    <th scope="col" abbr="Home" class="thx">Total Mensalidade</th> 

    <th scope="col" abbr="Home" class="thx">Vencimento</th> 

    <th scope="col" abbr="Home" class="thx">1ª Mensalidade</th> 

  </tr>	
  </thead>
<tbody>

  <tr class=""> 
    <th scope="row" class="column1">R$ '.Util::formataValor($cont['valor_mensal']).'</th> 

    <td class="tdx">R$ '.Util::formataValor($v_mens * $cont['quantidade_veiculos']).'</td> 
	
    <td class="tdx"> '.$cont['vencimento'].'</td> 
	
    <td class="tdx"> '.Util::formataData($cont['primeira_mensalidade']).'</td> 
	
  </tr>
  
</tbody> 
</table>



<p>
    com primeiro vencimento para o dia {primeira_mensalidade} das mensalidade e para o dia {primeira_prestacao} da instala&ccedil;&atilde;o, devendo para tanto, ser, emitidos os respectivos boletos de cobrança, sendo que, em caso do
 <strong>Contratante</strong> não recebê-los, deverá contatar a <strong>Contratada</strong>, solicitando instruções de como proceder ao pagamento.<br />
      <strong>7.2.</strong> Fica, definido como data de pagamento o dia {vencimento} de cada mês, nestes casos o vencimento se dará após 30 (trinta) dias da data, 
      computando-se o acréscimo proporcional correspondente aos dias passados entre a data da adesão e data base do pagamento.<br />
      <strong>7.3.</strong> O não pagamento dos valores pactuados no seu respectivo vencimento, implicará na cobrança de multa de 2% acrescida de
       juros de mora de 1% ao mês, além dos custos com serviços de cobrança, correspondentes a 10% para os casos de cobrança amigável e 20%, para
        casos de cobrança judicial, além das custas e despesas processuais, tudo acrescido de correção monetária.<br />
      <strong>7.3.1.</strong> Não havendo o pagamento por um período superior a 15 (quinze) dias, a SHOW PRESTARODA DE SERVIÇOS DO BRASIL LTDA 
      poderá tomar todas as providências cabíveis para recuperação de seu crédito, inclusive a promoção de negativação do usuário perante os 
      órgãos de proteção do crédito.<br />
      <strong>7.3.2.</strong> após o período informado na cláusula 7.3.1 os serviços prestados pela <strong>Contratada</strong> 
      serão suspensos até que haja o pagamento devido dos valores vencidos.<br />
      <strong>7.4.</strong> O preço dos Serviços será reajustado de acordo com a variação do Índice Geral de Preços-Série M (IGP- M/FGV), 
      ou outro índice que venha a substituí-lo, na menor periodicidade permitida por lei.<br />
      <strong>7.5.</strong> Na hipótese de serem criados novos tributos que incidam sobre o objeto deste Contrato, ou modificadas as atuais 
      alíquotas de impostos, de forma a majorar o custo da <strong>Contratada</strong>, os preços sofrerão reajuste para refletir a respectiva mudança.<br />
</p>
<br>
<p><strong>8. PRAZO E VIGÊNCIA</strong><br />
<strong>8.1.</strong> A vigência do presente contrato é de {meses} ({mesesPorExtenso}) meses, sendo renovado automaticamente por iguais e sucessivos períodos, 
desde que nenhuma das partes expresse manifestação em contrário, mediante prévia solicitação escrita com antecedência mínima de 30 (trinta) dias da data de 
término de cada período.<br />
<strong>8.2.</strong> Na hipótese de renovação deste Contrato: (i) os preços serão reajustados e revisados, conforme estabelecidos nas Cláusulas 7.4. e 7.5.; 
ou (ii) serão aplicados os novos preços para a prestação dos Serviços, vigentes na data da mencionada renovação, sendo que o pagamento pelo
 <strong>Contratante</strong> do valor estabelecido nos termos deste item representa aceitação tácita do <strong>Contratante</strong> do mesmo.<br />
<strong>8.3.</strong> O <strong>Contratante</strong> terá o prazo de 7 (sete) dias para desistir da contratação dos serviços devendo comunicar 
a <strong>Contratada</strong> por escrito até o sétimo dia da contratação.<br />
</p>
<br>
<p><strong>9. RESCISÃO</strong><br />
<strong>9.1.</strong> Reservam-se, ainda, as partes o direito de declararem antecipadamente rescindido o presente Contrato, independente de interpelação, 
notificação ou aviso prévio, podendo a parte inocente exigir imediatamente o cumprimento das obrigações contratuais assumidas pela parte infratora nas 
hipóteses de: (a) decretação de falência, requerimento de recuperação judicial, dissolução judicial, protesto legítimo de título de crédito, liquidação 
ou dissolução extrajudicial de qualquer das partes. (b) descumprimento de qualquer obrigação assumida em decorrência deste Contrato. (c) na hipótese do
 <strong>Contratante</strong> transferir os direitos e obrigações decorrentes do presente Contrato a terceiros, sem a prévia notificação e anuência da
  <strong>Contratada</strong>, ou do <strong>Contratante</strong> deixar de manter atualizados seus dados cadastrais, de maneira a permitir sua imediata localização. 
  (d) na hipótese do <strong>Contratante</strong> utilizar os Serviços em desacordo com o Contrato, ou omitir informações que visem obter vantagens ilícitas.<br />
<strong>9.2.</strong>
<!-- continua_92_1 -->
A <strong>Contratante</strong> em caso de requerer a rescisão do presente contrato, estará automaticamente assumindo a multa contratual correspondente ao do 
pagamento de todos os meses vincendos pertinentes ao presente contrato.<br />
<!-- continua_92_2 -->
A <strong>Contratante</strong> em caso de requerer a rescisão do presente contrato, estará automaticamente assumindo a multa contratual correspondente ao  
pagamento no valor de R$ {valor_multa} por veiculo no presente contrato, totalizando o valor da multa rescisória em R$ {total_multa}.<br />
<!-- continua_92_3 -->
A <strong>Contratante</strong> em caso de requerer a rescisão do presente contrato, estará isento a multa contratual.<br />
<!-- continua -->
</p>
<br>
<p><strong>10. DISPOSIÇÕES GERAIS</strong><br />
<strong>10.1.</strong> Em qualquer ponto de conflito entre os termos de adesão, ou qualquer outro documento, além de acertos verbais e o presente instrumento, 
o CONTRATO prevalecerá.<br />
<strong>10.2.</strong> O <strong>Contratante</strong> informará à <strong>Contratada</strong>, por escrito, sobre qualquer caso de alteração nas 
informações prestadas. As eventuais alterações somente obrigarão a <strong>Contratada</strong>, caso sejam entregues, e o <strong>Contratante</strong> 
isenta a <strong>Contratada</strong> de qualquer prejuízo que venha ocorrer em função da falta de atualização ou da omissão na entrega das informações 
atualizadas pelo <strong>Contratante</strong>.<br />
<strong>10.3.</strong> O <strong>Contratante</strong> declara que leu todas as Cláusulas do presente Contrato e que conhece e entende o seu conteúdo, 
inclusive a essência e os pormenores dos Serviços contemplados neste Contrato, e que atesta serem compatíveis com a sua necessidade sob o aspecto do tipo, 
especificação, qualidade e característica dos Serviços, ora renunciando expressamente à alegação de incompatibilidade, de qualquer natureza ou espécie, 
conforme legislação aplicável.<br />
<strong>10.4.</strong> A tolerância ou omissão das partes do exercício do direito que lhes assista sob o presente Contrato, representará mera liberalidade, 
não podendo ser considerada como novação contratual ou renúncia aos mesmos, os quais poderáo ser exercidos pela parte que se sentir prejudicada, a qualquer tempo.<br />
<strong>10.5.</strong> Se qualquer das cláusulas ou disposições do presente Contrato for considerada ilegal ou inválida, em qualquer procedimento 
administrativo ou judicial, a ilegalidade, invalidade ou ineficêcia então verificada deverá ser interpretada restritivamente, não afetando as 
demais cláusulas do Contrato, as quais permaneceráo válidas e em pleno vigor.<br />
<strong>10.6.</strong> O Contrato obriga as partes <strong>Contratante</strong>s e seus sucessores a qualquer título.<br />
<strong>10.7.</strong> Qualquer alteração e/ou aditamento do presente Contrato somente produzirá efeitos legais quando feitos por escrito e 
devidamente assinado pelas partes.<br />
<strong>10.8.</strong> A <strong>Contratada</strong> poderá ceder e transferir os direitos e obrigações decorrentes deste Contrato, integral ou 
parcialmente, a seu exclusivo critério, comunicando previamente o <strong>Contratante</strong>, assumindo o cessionário todas as obrigações 
perante o <strong>Contratante</strong> por força deste Contrato. Entretanto, a <strong>Contratada</strong> permanecerá obrigada, individual e 
solidariamente com o cessionário, durante o primeiro período de vigência deste Contrato. Toda e qualquer transferência deverá ser comunicada 
ao <strong>Contratante</strong> com antecedência de 10 (dez) dias e por escrito, para a atualização dos dados cadastrais.<br />
<strong>10.9.</strong> As partes pactuam que todas as comunicações formais entre elas, inclusive, mas não limitadamente, para fins de pedido 
de devolução dos Equipamentos, dever-se-ão dar por meio escrito por carta registrada, via fac-símile com comprovante de transmissão, via correio 
eletrônico conforme dados constantes na primeira página deste Contrato, ou por via de telegrama com cópia e aviso de recepção, aos endereços 
apontados na qualificação delas neste instrumento, sendo ajustado também que a simples entrega da comunicação nestes endereços, independentemente 
da pessoa receptora ou, ainda mesmo, a não entrega desde que por motivo de recusa ou de endereço inexistente, serão consideradas como tendo sido 
eficazmente realizada a comunicação.<br />
<strong>10.9.1.</strong> Qualquer parte que vier alterar seu endereço deverá dar notícia à outra pela via escrita e na forma acima apontada, 
sob pena de não poder arguir a ineficêcia de qualquer comunicação enviada ao endereço até então declarado.<br />
</p>
<br>
<p><strong>11. SUBORDINAÇÃO LEGAL, FORO E JURISDIÇÃO</strong><br />
</p>
<br>
<p>Os termos e condições deste Contrato estão subordinados e serão interpretados de acordo com as leis brasileiras, 
e as partes elegem o Foro do <strong>Contratante</strong>, com exclusão de qualquer outro, por mais privilegiado que seja, 
para dirimir todas e quaisquer questões ou conflitos oriundos deste cumprimento.<br />
</p>
<br />
<br />
<br />
<br />  
<br />
<div align="right"> {cidade}, {dataPorExtenso}.</div>
<br />  
<br />
<br />
<br />  
<div align="right">_______________________________________________<br />Show Prestadora de Serviços do Brasil Ltda</div>
<br />
<div align="right">_______________________________________________<br />{cliente}</div>
<br />
<br />
<div align="right">_______________________________________________<br />
1&ordf; Testemunha</div>
<br />
<div align="right">_______________________________________________<br />
2&ordf; Testemunha</div>
<br />
<br />
<br />
<br />
<br />
<br />
<div align="center">Av.... Rui Barbosa, Nº 104 - CEP: 58200-000 - Centro - Guarabira/PB<br />www.showtecnologia.com | (83) 3271-6559</div>
  
  </td>
  </tr>
  </table>

  
</body>
</html>


';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.

   $pdf->Output('example_001.pdf', 'I');
   
$_SESSION['docpdf'] = $pdf->Output($arquivo, 'S');

//============================================================+
// END OF FILE
//============================================================+


?> 