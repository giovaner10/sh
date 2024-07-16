<!-- InicioDaPagina -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<title>SIGA-ME - NORIO MOMOI - EPP | CONTRATO</title>
<link href="<?php echo base_url('media')?>/css/bootstrap.css"
    rel="stylesheet">
<link href="<?php echo base_url('media')?>/css/bootstrap-responsive.css"
    rel="stylesheet">
<link href="./{tpldir}/css/estilos.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.titulo {
    font-size: 12px;
    font-weight: bold;
}

.texto {
    font-size: 11px;
    text-align:justify;
}
.corpo {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 9px;
    width: 15cm;
    text-align: left;
    vertical-align: top;
}
p {
    font-size: 11px;
    text-align:justify;
}

#salto_pagina_despois {
 
page-break-after:always;
page-break-inside: auto;
 
}
 
#salto_pagina_antes {
 
page-break-before:always;
 
}

.saltopagina {
page-break-after: always;
}

@media print{

    #menu{
        display: none;
    }

    #box{
        border: none;
    }

}


</style>

</head>

<body>

<?php 
    function valorPorExtenso($valor) {
        $rt = "";
        $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
     
        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
     
        $z=0;
     
        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for($i=0;$i<count($inteiro);$i++)
            for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
                $inteiro[$i] = "0".$inteiro[$i];
     
        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;) 
        $fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
        for ($i=0;$i<count($inteiro);$i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
        
            $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
            $t = count($inteiro)-1-$i;
            $r .= $r ? "".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000")$z++; elseif ($z > 0) $z--;
            if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t]; 
            if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : "") . $r;
        }
     
        return($rt ? $rt : "zero");
    }

    function dataextenso($data) {

 

        $data = explode("-",$data);
        $dia = $data[2];
        $mes = $data[1];
        $ano = $data[0];

        switch ($mes){

            case 1: $mes = "JANEIRO"; break;
            case 2: $mes = "FEVEREIRO"; break;
            case 3: $mes = "MARÇO"; break;
            case 4: $mes = "ABRIL"; break;
            case 5: $mes = "MAIO"; break;
            case 6: $mes = "JUNHO"; break;
            case 7: $mes = "JULHO"; break;
            case 8: $mes = "AGOSTO"; break;
            case 9: $mes = "SETEMBRO"; break;
            case 10: $mes = "OUTUBRO"; break;
            case 11: $mes = "NOVEMBRO"; break;
            case 12: $mes = "DEZEMBRO"; break;
     
        }

        $mes=strtolower($mes);
        print ("$dia de $mes de $ano");

    }

?>

<?php 
    foreach ($contratos as $contrato):

        $id_contrato = $contrato->id;
        $quantidade_veiculos = $contrato->quantidade_veiculos;
        $valor_instalacao_real = $contrato->valor_instalacao;
        $valor_instalacao = number_format($contrato->valor_instalacao, 2, ',', '.');

        if ($valor_instalacao_real != 0) {
            $prestacoes = $contrato->prestacoes;
            $total_instalacao_real = $valor_instalacao_real*$quantidade_veiculos;
            $total_instalacao = number_format($total_instalacao_real, 2, ',', '.');
            $valor_parcelas_real = $total_instalacao_real/$prestacoes;
            $valor_parcelas = number_format($valor_parcelas_real, 2, ',', '.');
        }
        
        $valor_mensal_real = $contrato->valor_mensal;
        $valor_mensal = number_format($valor_mensal_real, 2, ',', '.');
        $total_mensalidade_real = $valor_mensal_real*$quantidade_veiculos;
        $total_mensalidade = number_format($total_mensalidade_real, 2, ',', '.');
        $vencimento = $contrato->vencimento;
        $primeira_mensalidade = dh_for_humans($contrato->primeira_mensalidade);
    
        $meses = $contrato->meses;
        $multa = $contrato->multa;
        $tipo_proposta = $contrato->tipo_proposta;
        $boleto = $contrato->boleto;
        $valor_prestacao = $contrato->valor_prestacao;
        $data_prestacao = dh_for_humans($contrato->data_prestacao);
        $data_contrato = $contrato->data_contrato;
         if ($multa == 2) {
                $multa_valor = $contrato->multa_valor;
                $total_multa_real = $multa_valor*$quantidade_veiculos;
                $total_multa = number_format($total_multa_real, 2, ',', '.');
            }

    endforeach 
?>

<?php
    foreach ($clientes as $cliente):
        $nome_cliente = $cliente->nome;
        $cnpj_cliente = $cliente->cnpj;
        $inscricao_cliente = $cliente->inscricao_estadual;

        

        $endereco_cliente = $cliente->endereco;
        $bairro_cliente = $cliente->bairro;
        $numero_cliente = $cliente->numero;
        $complemento_cliente = $cliente->complemento;
        $cidade_cliente = $cliente->cidade;
        $uf = $cliente->uf;

        switch ($uf){

            case 'PB': $uf_cliente = "PARAIBA"; break;
            case 'PE': $uf_cliente = "PERNAMBUCO"; break;
            case 'RN': $uf_cliente = "RIO GRANDE DO NORTE"; break;
            case 'PI': $uf_cliente = "PIAUÍ"; break;
            case 'AC': $uf_cliente = "ACRE"; break;
            case 'AL': $uf_cliente = "ALAGOAS"; break;
            case 'AP': $uf_cliente = "AMAPÁ"; break;
            case 'AM': $uf_cliente = "MANAUS"; break;
            case 'BA': $uf_cliente = "BAHIA"; break;
            case 'CE': $uf_cliente = "CEARÁ"; break;
            case 'DF': $uf_cliente = "DISTRITO FEDERAL"; break;
            case 'GO': $uf_cliente = "GOIÁS"; break;
            case 'MA': $uf_cliente = "MARANHÃO"; break;
            case 'MT': $uf_cliente = "MATO GROSSO"; break;
            case 'MS': $uf_cliente = "MATO GROSSO DO SUL"; break;
            case 'PA': $uf_cliente = "PARÁ"; break;
            case 'RJ': $uf_cliente = "RIO DE JANEIRO"; break;
            case 'RS': $uf_cliente = "RIO GRANDE DO SUL"; break;
            case 'RO': $uf_cliente = "RODONIA"; break;
            case 'RR': $uf_cliente = "RORAIMA"; break;
            case 'TO': $uf_cliente = "TOCANTINS"; break;
            case 'SP': $uf_cliente = "SÃO PAULO"; break;
            case 'PR': $uf_cliente = "PARANÁ"; break;
            case 'ES': $uf_cliente = "ESPÍRITO SANTO"; break;
            case 'MG': $uf_cliente = "MINAS GERAIS"; break;
            case 'SE': $uf_cliente = "SERGIPE"; break;
            case 'SC': $uf_cliente = "SANTA CATARINA"; break;
     
        }

    endforeach 
?>

 <div class="well well-small" id = "menu">

    <a href="<?php echo site_url('clientes/view/'.$cliente->id)?>" title="" class="btn"><i class="icon-arrow-left"></i> Voltar</a>
    <a href="javascript:window.print()" title="" class="btn btn-primary"><i class="icon-print icon-white"></i> Imprimir</a>

</div>

<!-- <center><img src="<?php echo base_url('media')?>/img/logo_topo.png"/></center> -->
<table align="center" >
<tr>
    <td style="text-align: center; font-size: 1.5em;"><h4>Nº do Contrato: <b><?php echo $id_contrato ?></h4></td>
</tr>
<tr>
<td class="corpo">

<div align="center" class="titulo">
    
    CONTRATO DE LOCAÇÃO E PRESTAÇÃO DE SERVIÇOS <br>DE RASTREAMENTO E MONITORAMENTO VEICULAR</div>
<br><br>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Pelo presente instrumento particular, em que são partes, de um lado, SIGA-ME - NORIO MOMOI EPP., 
sociedade com sede na Cidade de Guarabira, Estado da Paraíba, na Rua Augusto Almeida, S/N - Novo - CEP: 58.200-000 - inscrita no CNPJ. sob nº 21.698.912/0001-59, 
neste ato representada na forma de seu Contrato Social, doravante denominada "<strong>Contratada</strong>"; e, de outro lado, <?php echo $nome_cliente ?>,  
inscrita no <?php echo $cnpj_cliente ?>, <?php echo $inscricao_cliente ?> endereço <?php echo $endereco_cliente ?>, bairro <?php echo $bairro_cliente ?>, cidade <?php echo $cidade_cliente ?>, estado <?php echo $uf_cliente ?>, 
denominado "<strong>Contratante</strong>", têm entre si justa e <strong>Contratada</strong> 
a prestação de serviços de localização e monitoramento de veículos, 
que se regerá pelas seguintes cláusulas e condições:
</p>

    
<p><strong>1. DO OBJETO</strong> - O objeto do presente contrato consiste na locação de <?php echo $quantidade_veiculos ?> módulos de rastreamento veicular; podendo ser ampliando esta quantidade através solicitação por contato eletrônico, email, que servirá de anexo ao presente contrato, prestação de serviços de monitoramento, rastreamento digital on-line via web de localização de veículos denominado GESTOR pela Contratada ao Contratante, ou ao usuário por ele indicado.

<br />
<br><strong>1.1</strong> - É de total responsabilidade do Contratante a conservação dos equipamentos instalados no veiculo, tendo a obrigação de devolver o equipamento, em perfeito estado, caso o equipamento seja extraviado ou danificado, estará assumindo a multa por equipamento no valor de um mil Reais.<br />
<br><strong>1.2</strong> - A instalação, retirada, reinstalação ou correção só mediante comunicação ao nosso suporte e feita por técnicos da Contratada, caso seja realizado por outro técnico não autorizado será cobrado uma multa de quinhentos reais por equipamento retirado.
<br><br>
<strong>1.3</strong> -Caso necessite de manutenção, posterior a instalação, será cobrado o valor de cinquenta reais por serviço realizado, seja ele, uma correção, retirada ou reinstalação, sendo cobrado também o deslocamento do técnico para realização do serviço, contando como ponto inicial a localização do técnico mais próximo, no valor de cinquenta centavos por KM rodado.
</p>

<p><strong>2. SERVIÇOS</strong> - Os serviços pactuados compreendem exclusiva e restritivamente: 
(a) Monitoramento, rastreamento e localização do Veículo via web, 
nas hipóteses previstas na respectiva modalidade <strong>Contratada</strong>, somente em caso de roubo ou furto e demais de urgência; 
(b) Comunicação e colaboração com as autoridades competentes ou Prestadores de Serviços Autorizados sobre a localização do Veículo, 
excluídas todas e quaisquer situações não previstas expressamente neste instrumento.<br />
<strong>2.1.</strong> A <strong>Contratada</strong> compromete-se a prestar os Serviços ora pactuados, de forma a disponibilizá-los por 24 (vinte e quatro) horas, 
durante os 07 (sete) dias da semana, 365 dias por ano, desde que o equipamento seja instalado por Centros de Atendimento Técnico ou Agentes Técnicos Autorizados, 
respeitados limites de áreas de cobertura e possíveis fatos oriundos de caso fortuito ou força maior.<br />
<strong>2.2.</strong> Código de Identificação<br />
<strong>2.2.1.</strong> O <strong>Contratante</strong> receberá seu Código de Identificação, com usuário e senha, ao término da instalação dos Equipamentos, 
que se dará num prazo de 15 dias úteis.<br />
<strong>2.2.2.</strong> O Código de Identificação é pessoal e secreto, sendo de exclusiva responsabilidade do <strong>Contratante</strong> 
a divulgação para pessoas não autorizadas.<br />
<strong>2.2.3.</strong> As pessoas autorizadas pela <strong>Contratante</strong> devera ser solicitado por escrito para uso do sistema e suas limitações de acesso.<br>
<strong>2.2.3.1.</strong> Acesso como bloqueio dos veículos ora relacionado neste contrato. A <strong>Contratante</strong> fica ciente que para uso do bloqueio o cliente deverá solicitar por escrito a liberação deste recurso, assim assumindo total responsabilidade de uso desse serviço.<br />
<strong>2.2.3.2.</strong> Relatórios periódicos de: Quilometragem do veiculo; Tempo motor ligado ou desligado com veiculo parado ou em movimento; Velocidade excedida; Trajeto da frota entre outros a serem imputados nas atualizações do sistema;<br />
<strong>2.2.3.3.</strong> Cria cerca eletrônica: Demarque no mapa  uma cerca, que lhe dará a segurança que o veículo não ultrapassará o local determinado pela empresa para o desenvolvimento do serviço designado.<br />
<strong>Paragrafo único</strong> A <strong>Contratante</strong> está ciente e declara que a adequada execução dos Serviços está sujeita a interferência de fatores externos capazes de impedir o regular funcionamento do(s) equipamento(s) e a disponibilização das funcionalidades ora contratadas, independentemente 
das ações da <strong>Contratada</strong>. As limitações descritas clausula 6.<br />

<strong>2.3. Procedimento de Identificação</strong><br />
<strong>2.3.1.</strong> Para acionamento do sistema, <strong>Contratante</strong> ou Usuário do bem deverão dar conhecimento do fato a <strong>Contratada</strong> 
e informar o Código de Identificação correto do Veículo, ou a placa, chassi ou ainda o nome completo, ao operador da Central de Controle de Operações, 
identificando-se, indicando quaisquer informações de dados de confirmação solicitados.<br />
<strong>2.3.2.</strong> Em não havendo comunicação com a indicação de todos os dados solicitados, a <strong>Contratada</strong> 
fica desobrigada da prestação dos serviços, restando livre e indene de eventual dano.<br />
<strong>2.4. Serviço de localização em caso de notificação de furto ou roubo do Veículo via internet e online</strong><br />
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

<p><strong>3.VEÍCULO</strong><br />
<strong>3.1.</strong> O <strong>Contratante</strong> declara ser o proprietário do Veículo, assumindo total responsabilidade, sob as penas da lei, 
pela veracidade da declaração ora prestada e consequências dela advindas.<br />
<strong>3.2.</strong> O <strong>Contratante</strong> se obriga e se compromete a informar à <strong>Contratada</strong>, antecipadamente, 
sobre toda e qualquer transferência do Veículo a terceiros, ficando esclarecido que, até que isto ocorra, o <strong>Contratante</strong> 
permanecerá responsável, obrigado e vinculado a todos os termos e condições deste Contrato.<br />
</p>

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

<p><strong>5. SIGILO DE DILIGÊNCIA</strong><br />
<strong>5.1.</strong> A <strong>Contratada</strong> se compromete a manter sempre em sigilo toda e qualquer informação 
relativa ao <strong>Contratante</strong>, Usuários Autorizados, e a localização do Veículo, exceto quando existir 
indícios de furto, roubo, ou as situações de emergência descritas nos itens 2.4.3, quando acionará, 
a Polícia e/ou os Prestadores de Serviços Autorizados, conforme a modalidade de Serviço escolhida nos termos deste Contrato.<br />
</p>

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

<p><strong>7. PREÇOS E CONDIÇÕES DE PAGAMENTO</strong><br />
<strong>7.1.</strong> A <strong>Contratante</strong> pagará a <strong>Contratada</strong> pelos serviços prestados da seguinte forma:</p>

<!-- ContinuacaoPrecos -->
<?php if ($valor_instalacao != 0): ?>

<table class= "dados" width="500" bordercolor = "#CCCCCC" border="1" cellpadding="3" cellspacing="0" style="text-align:center">

<tr>
    <td><p><b>Instalação por Veículo</b></p></td>
    <td><p><b>Total Instalação</b></p></td>
    <td><p><b>Dividida em</b></p></td>
    <td><p><b>Valor das Parcelas</b></p></td>
</tr>

<tr>
    <td><p><b>R$ <?php echo $valor_instalacao ?></b></p></td>
    <td><p>R$ <?php echo $total_instalacao ?></p></td>
    <td><p><?php echo $prestacoes ?></p></td>
    <td><p>R$ <?php echo $valor_parcelas ?></p></td>
</tr>

</table>

<?php endif ?>

<table class= "dados" width="500" bordercolor = "#CCCCCC" border="1" cellpadding="3" cellspacing="0" style="text-align:center">

<tr>
    <td><p><b>Mensalidade por Veículo</b></p></td>
    <td><p><b>Total Mensalidade</b></p></td>
    <td><p><b>Vencimento</b></p></td>
    <td><p><b>1º Mensalidade</b></p></td>
</tr>

<tr>
    <td><p><b>R$ <?php echo $valor_mensal ?></b></p></td>
    <td><p>R$ <?php echo $total_mensalidade ?></p></td>
    <td><p><?php echo $vencimento ?></p></td>
    <td><p><?php echo $primeira_mensalidade ?></p></td>
</tr>

</table>

<p>
com primeiro vencimento para o dia <?php echo $primeira_mensalidade ?> das mensalidade e para o dia <?php echo $data_prestacao ?> da instala&ccedil;&atilde;o, devendo para tanto, ser, emitidos os respectivos boletos de cobrança, sendo que, em caso do
 <strong>Contratante</strong> não recebê-los, deverá contatar a <strong>Contratada</strong>, solicitando instruções de como proceder ao pagamento.<br />
      <strong>7.2.</strong> Fica, definido como data de pagamento o dia <?php echo $vencimento ?> de cada mês, nestes casos o vencimento se dará após 30 (trinta) dias da data, 
      computando-se o acréscimo proporcional correspondente aos dias passados entre a data da adesão e data base do pagamento.<br />
      <strong>7.3.</strong> O não pagamento dos valores pactuados no seu respectivo vencimento, implicará na cobrança de multa de 2% acrescida de
       juros de mora de 1% ao mês, além dos custos com serviços de cobrança, correspondentes a 10% para os casos de cobrança amigável e 20%, para
        casos de cobrança judicial, além das custas e despesas processuais, tudo acrescido de correção monetária.<br />
      <strong>7.3.1.</strong> Não havendo o pagamento por um período superior a 15 (quinze) dias, a SIGA-ME - NORIO MOMOI - EPP 
      poderá tomar todas as providências cabíveis para recuperação de seu crédito, inclusive a promoção de negativação do usuário perante os 
      órgãos de proteção do crédito.<br />
      <strong>7.3.2.</strong> após o período informado na cláusula 7.3.1 os serviços prestados pela <strong>Contratada</strong> 
      serão suspensos até que haja o pagamento devido dos valores vencidos.<br />
      <strong>7.4.</strong> O preço dos Serviços será reajustado de acordo com a variação do Índice Geral de Preços-Série M (IGP- M/FGV), 
      ou outro índice que venha a substituí-lo, na menor periodicidade permitida por lei.<br />
      <strong>7.5.</strong> Na hipótese de serem criados novos tributos que incidam sobre o objeto deste Contrato, ou modificadas as atuais 
      alíquotas de impostos, de forma a majorar o custo da <strong>Contratada</strong>, os preços sofrerão reajuste para refletir a respectiva mudança.<br />
</p>
<p><strong>8. PRAZO E VIGÊNCIA</strong><br />
<strong>8.1.</strong> A vigência do presente contrato é de <?php echo $meses ?> (<?php echo valorPorExtenso($meses)?>) meses, sendo renovado automaticamente por iguais e sucessivos períodos, 
desde que nenhuma das partes expresse manifestação em contrário, mediante prévia solicitação escrita com antecedência mínima de 30 (trinta) dias da data de 
término de cada período.<br />
<strong>8.2.</strong> Na hipótese de renovação deste Contrato: (i) os preços serão reajustados e revisados, conforme estabelecidos nas Cláusulas 7.4. e 7.5.; 
ou (ii) serão aplicados os novos preços para a prestação dos Serviços, vigentes na data da mencionada renovação, sendo que o pagamento pelo
 <strong>Contratante</strong> do valor estabelecido nos termos deste item representa aceitação tácita do <strong>Contratante</strong> do mesmo.<br />
<strong>8.3.</strong> O <strong>Contratante</strong> terá o prazo de 7 (sete) dias para desistir da contratação dos serviços devendo comunicar 
a <strong>Contratada</strong> por escrito até o sétimo dia da contratação.<br />
</p>
<p><strong>9. RESCISÃO</strong><br />
<strong>9.1.</strong> Reservam-se, ainda, as partes o direito de declararem antecipadamente rescindido o presente Contrato, independente de interpelação, 
notificação ou aviso prévio, podendo a parte inocente exigir imediatamente o cumprimento das obrigações contratuais assumidas pela parte infratora nas 
hipóteses de: (a) decretação de falência, requerimento de recuperação judicial, dissolução judicial, protesto legítimo de título de crédito, liquidação 
ou dissolução extrajudicial de qualquer das partes. (b) descumprimento de qualquer obrigação assumida em decorrência deste Contrato. (c) na hipótese do
 <strong>Contratante</strong> transferir os direitos e obrigações decorrentes do presente Contrato a terceiros, sem a prévia notificação e anuência da
  <strong>Contratada</strong>, ou do <strong>Contratante</strong> deixar de manter atualizados seus dados cadastrais, de maneira a permitir sua imediata localização. 
  (d) na hipótese do <strong>Contratante</strong> utilizar os Serviços em desacordo com o Contrato, ou omitir informações que visem obter vantagens ilícitas.<br />
<strong>9.2.</strong>
<?php if ($multa == 1): ?>

    Caso o contratante der causa a rescisão do presente contrato, estará automaticamente assumindo a multa contratual correspondente ao do 
    pagamento de todos os meses vincendos pertinentes ao presente contrato.
   
<?php elseif ($multa == 2): ?>

    Caso o contratante der causa a rescisão do presente contrato, estará automaticamente assumindo a multa contratual correspondente ao  
    pagamento no valor de R$ <?php echo $multa_valor ?> por veiculo no presente contrato, totalizando o valor da multa rescisória em R$ <?php echo $total_multa ?>.
     
<?php elseif ($multa == 3): ?>

    Caso o contratante der causa a rescisão do presente contrato, estará isento a multa contratual.

<?php endif ?>
</p>
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
<br>
<strong>10.10</strong> - O Contratante reconhece e aceita os riscos da instalação. Riscos estes, que poderá ocorrer a perda da garantia de fábrica do veiculo, que será alterado as configurações de instalação originais do veículo, e qua a contratada não se responsabiliza por danos causados referente as modificações efetuadas ao veiculo.
</p>
<p><strong>11. SUBORDINAÇÃO LEGAL, FORO E JURISDIÇÃO</strong><br />
</p>
<p>Os termos e condições deste Contrato estão subordinados e serão interpretados de acordo com as leis brasileiras, 
e as partes elegem o Foro do <strong>Contratante</strong>, com exclusão de qualquer outro, por mais privilegiado que seja, 
para dirimir todas e quaisquer questões ou conflitos oriundos deste cumprimento.<br />
</p>
<br />
<br />
<br />
<br />  
<br />
<div align="right"> <?php echo $cidade_cliente ?>, <?php echo dataextenso($data_contrato) ?>.</div>
<br />  
<br />
<br />
<br />  
<div align="right">_______________________________________________<br />SIGA-ME - NORIO MOMOI - EPP</div>
<br />
<div align="right">_______________________________________________<br /><?php echo $nome_cliente ?></div>
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
<div align="center">
<span style="font-size: 1em"><b>Nº do Contrato: <?php echo $id_contrato ?></b></span>
    <br>
Rua Augusto Almeida, Nº S/N - CEP: 58200-000 - Novo - Guarabira/PB<br />www.noriomomoi.com | (83) 99337-4612</div>
  
  </td>
  </tr>
  </table>

  
</body>
</html>
