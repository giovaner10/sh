<style>
    @page{
        size: auto;
        margin: 0mm;    
    }
    @media print {
        .container {
            page-break-after: always !important;
        }

        .quebrapagina {
            page-break-after: always !important;
        }

        .no-print, .no-print *
            {
                display: none !important;
            }
        .clausulas
            {
                font-size: 10px;
                line-height: 12px;
            }
        .lineText
            {
                font-size: 9px;
            }

        .span12 {
            width: 370px !important;
        }

        .esquerda {
            float: left !important;
        }

        .direita {
            float: right !important;
        }

    }

    .lineText {
        margin-top: 55px;
    }
    #nameFun, #nameCargo, .nameCargo, .nameFun{
        text-transform: uppercase;
    }
    #salarioExtenso, .salarioExtenso{
        text-transform: capitalize;
    }
    .quebrapagina {
        page-break-after: always !important;
    }
    .etiqueta {
        border: 2px solid;   
        padding: 5px;     
        list-style: none;
    }    
    .etiqueta li {
        margin-left: 5px;
    }
    .dividers {
        border: 1px solid black;        
    }
    .row3 {
        border: 2px solid;
    }
    .row1 {
        border: 2px solid;
        margin-left: 0!important;
        margin-top: 5px;
    }
    li {
        list-style: none;
    }
    .row1 ul, .row3 ul {
        padding-left: 10px;
    }
    .border {
        border: 2px double;
        padding: 5px;
    }
</style>
<?php
if ($dados->empresa == 'SHOW PRESTADORA DE SERVIÇOS DO') {
    $name_empresa = 'SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA - ME';
    $end_empresa = 'AV RUI BARBOSA, 104 - CENTRO';
    $cnpj_empresa = '09.338.999/0001-58';
} else {
    $name_empresa = 'NORIO MOMOI EPP';
    $end_empresa = 'RUA NAPOLEÃO LAUREANO, S/N - NOVO';
    $cnpj_empresa = '21.698.912/0001-59';
}
?>
<div class="no-print"><button class="btn btn-primary " onclick="print();"><i class="fa fa-print"></i> Imprimir</button><br/><br/></div>
<div id="print">
    <hr class="no-print">
    <div class="container">
        <div class="clausulas">
            <p style="text-align: center">Contrato de Trabalho</p>
            <p>Entre a firma <?= $name_empresa ?> estabelecida em Guarabira à <?= $end_empresa ?> CNPJ/CEI <?= $cnpj_empresa ?> doravante designada simplesmente EMPREGADORA e, <strong id="nameFun"></strong> portador da Carteira Profissional No. <strong id="numCtps"></strong> a seguir chamado apenas de EMPREGADO, é celebrado o presente CONTRATO DE EXPERIÊNCIA, que terá vigência a partir da data de início da prestação de serviços, de acordo com as condições a seguir especificadas:</p>
            <p>1 - Fica o EMPREGADO admitido no Quadro de functionários da EMPREGADORA para exercer a função de <strong id="nameCargo"></strong>, Mediante a Remuneração de R$ <strong id="valSalario"></strong> (<b id="salarioExtenso"></b>) por mês.</p>
            <p>Em circunstância, porém, de ser a função especificada não importa na intransferibilidade do EMPREGADO para outro serviço no qual demonstre melhor capacidade de adaptação desde que compatível com a sua condição pessoal.</p>
            <p>2 - O horário de trabalho será anotado na sua ficha de registro e a eventual redução da jornada, por determinação da EMPREGADORA, não inovará este ajuste, permanecendo sempre integra a obrigação do EMPREGADO de cumprir o horário que lhe for determinado, observando o limíte legal.</p>
            <p>3 - Obriga-se também o EMPREGADO a prestar serviços em horas extraordinárias, sempre que lhe for determinado pela EMPREGADORA, na forma prevista em lei. Na hipotese desta faculdade pela EMPREGADORA, o empregado receberá as horas extraordinárias com o acréscimo legal, salvo a ocorrência de compensação, com a consequente redução da compensação, com a consequente redução da jornada de trabalho em outro dia.</p>
            <p>4 - Aceita o EMPREGADO, expressamente, a condição de prestar serviços em qualquer dos turnos de trabalho, isto é, tanto durante o dia como a noite, desde que sem simultaneidade, observadas as prescrições legais reguladoras do assunto, quanto a remuneração.</p>
            <p>5 - Fica ajustado nos termos de que dispõe o parágrafo 1º do artigo 469, da Consolidação das Leis do Trabalho, que o EMPREGADO acatará ordem emanada da EMPREGADORA para a prestação de serviços tanto na localidade de celebração do Contrato de Trabalho, como em qualquer outra cidade, capital ou vila do território nacional, quer essa transferência seja transitória, quer seja definitiva.</p>
            <p>6 - No ato da assinatura deste contrato, o EMPREGADO recebe o regulamento interno da empresa cujas cláusulas fazem parte do contrato de trabalho, e a violação de qualquer delas implicará em sanção, cuja graduação dependerá da gravidade da mesma, culminando com a rescisão do contrato.</p>
            <p>7 - Me caso de dano causado pelo EMPREGADO, fica a EMPREGADORA, autorizada a efetivar o desconto da importância correspondente ao prejuízo, o qual fara, com fundamentos no parágrafo 1º do artigo 462 da Consolidação das Leis do Trabalho, já que essa possibilidade fica expressamente prevista em Contrato.</p>
            <p>8 - Aos contratos por prazo determinado, que contiverem cláusula assecuratória do direito recíproco de rescisão antes de expirado o termo ajustado, aplicam-se, caso seja exercido tal direito por qualquer das Partes, os princípios que regem a rescisão dos contratos por prazo indeterminado (Art. 481, da CLT).</p>
            <p>9 - O presente contrato, vigerá durante 45 dias , sendo celebrado para as partes verificarem reciprocamente, a conveniência ou não de se vincularem em caráter definitivo a Contrato de Trabalho. A Empresa passando a conhecer as aptidões do EMPREGADO e suas qualidades pessoais e morais; o EMPREGADO verificando se o ambiente e os métodos de trabalho atendem a sua conveniência.</p>
            <p>10 - Na Hipótese deste ajuste transformar-se em Contrato por Prazo Indeterminado, pelo decurso do tempo, continuarão em plena vigência as cláusulas de 1(um) a 7(sete), enquanto durarem as relações do EMPREGADO com a EMPREGADORA. E por estarem de pleno acordo, as partes contratantes, assinam o presente Contrato de Experiência em duas vias, ficando a primera em poder da EMPREGADORA, e a segunda com o EMPREGADO, que dela dará o competente recibo.</p>
            <br><br><br><br>
            <p>Guarabira, <i id="dateNow"></i></p>
        </div>
        <div class="lineText">
            <div class="esquerda span12">
                <span class="span5">
                    <p>Testemunha</p>
                    <p>________________________________________</p>
                    <p>Testemunha</p>
                    <p>________________________________________</p>
                </span>
                <span class="span5">
                    <p>Empregado ou Responsável quando menor</p>
                    <p>________________________________________</p>
                    <p>Empregador</p>
                    <p>________________________________________</p>
                </span>
            </div>
            <div class="direita span12">
                <span class="span12">
                    <p>Termo de Prorrogação</p>
                    <p>Por mútuo acordo entre as partes, fica o presente contrato de experiência, que deveria vencer
                        <p> nesta data prorrogado até ___/___/_____</p></p>
                    <p>Guarabira,_____de_________________________de_____________</p>
                </span>
                <span class="span5">
                    <p>Testemunha</p>
                    <p>________________________________________</p>
                    <p>Testemunha</p>
                    <p>________________________________________</p>
                </span>
                <span class="span5">
                    <p>Empregado ou Responsável quando menor</p>
                    <p>________________________________________</p>
                    <p>Empregador</p>
                    <p>________________________________________</p>
                </span>
            </div>
        </div>


    </div>
    <br class="quebrapagina">
    <div class="container">
        <h3>Etiquetas</h3>

        <span class="span5">
            <ul class="etiqueta">
                <li>CNPJ: 21.698.912/0001-59</li>
                <li>Empregador: <?= $name_empresa ?></li>
                <li>Endereço: <?= $end_empresa ?></li>
                <li>Cidade: Guarabira</li>
                <li>UF: PB</li>
                <li>Cargo: <b class="nameCargo"></b> / CBO: <b class="nameCbo"></b></li>
                <li>Data de Admissão: <b class="dateNow"></b></li>
                <li>Nº Reg.:</li>
                <li>Salário: R$ <b class="valSalario"></b> (<b class="salarioExtenso"></b>)</li>
                <hr class="dividers">
                <li>
                   <b><?= $name_empresa ?></b>
                </li>
            </ul>
        </span>

        <span class="span5">
        <ul class="etiqueta">
                <li>
                    O portador da presente CTPS: foi admitido em caráter de experiência pelo prazo de 45 dias,
                    podendo ser prorrogado com acordo das partes por mais 45 dias.
                </li>
                <hr class="dividers">
                <li>
                   <b><?= $name_empresa ?></b>
                </li>
            </ul>
        </span>

    </div>
    <br class="quebrapagina">
    <br>
    <h3>Comprovante de Recebimento de CTPS</h3>
    <br>
        <div class="row-fluid border">
        <div class="span4 row3"><b>&nbsp;Número da CTPS</b>
            <ul>
                <li class="numCtps"></li>
            </ul>
          </div>
          <div class="span4 row3"><b>&nbsp;Data de Expedição</b>
            <ul>
                <li class="dateNow"></li>
            </ul>
          </div>

          <div class="span12 row1">
              <ul class="span6"><b>Razão Social</b>
                  <li>&nbsp;<?= $name_empresa ?></li>
              </ul>
              <ul class="span6"><b>Estabelecimento</b>
                  <li>&nbsp;<?= $name_empresa ?> - <?= $cnpj_empresa ?></li>
              </ul>
          </div>
          <div class="span12 row1">
              <ul class="span12"><b>Nome do Empregado</b>
                  <li class="nameFun"></li>
              </ul>
          </div>
          <div class="span12 row1">
              <ul class="span12"><b></b>
                  <li>&nbsp;<b>Recebi(emos) a Carteira Profissional acima, para as anotações de ADMISSÃO a qual será devolvida dentro de 48 horas de acordo com a lei em vigor</b></li><br>
                  <li>Guarabira, <b class="dateExtenso"></b></li>
                  <li style="float: right; text-align: center">
                      _____________________________________________
                      <p>(Carimbo e visto da empresa)</p>
                  </li>
              </ul>
          </div>
        </div>
        <hr style="border: 2px dashed">
        <div class="row-fluid border">
          <div class="span4 row3"><b>&nbsp;Número da CTPS</b>
            <ul>
                <li class="numCtps"></li>
            </ul>
          </div>
          <div class="span4 row3"><b>&nbsp;Data de Expedição</b>
            <ul>
                <li class="dateNow"></li>
            </ul>
          </div>

          <div class="span12 row1">
              <ul class="span6"><b>Razão Social</b>
                  <li>&nbsp;<?= $name_empresa ?></li>
              </ul>
              <ul class="span6"><b>Estabelecimento</b>
                  <li>&nbsp;<?= $name_empresa ?> - <?= $cnpj_empresa ?></li>
              </ul>
          </div>
          <div class="span12 row1">
              <ul class="span12"><b>Nome do Empregado</b>
                  <li class="nameFun"></li>
              </ul>
          </div>
          <div class="span12 row1">
              <ul class="span12"><b></b>
                  <li>&nbsp;<b>Recebi(emos) a Carteira Profissional acima, para as anotações de ADMISSÃO a qual será devolvida dentro de 48 horas de acordo com a lei em vigor</b></li><br>
                  <li>Guarabira, <b class="dateExtenso"></b></li>
                  <li style="float: right; text-align: center">
                      _____________________________________________
                      <p>(Assinatura do Empregado)</p>
                  </li>
              </ul>
          </div>
        </div>
        <i style="float:right">1ª via empresa - 2ª via empregado</i>
</div>
<script>
    //ESCREVE UM VALOR MOEDA POR EXTENSO
    String.prototype.extenso = function(c){
        var ex = [
            ["zero", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove", "dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove"],
            ["dez", "vinte", "trinta", "quarenta", "cinqüenta", "sessenta", "setenta", "oitenta", "noventa"],
            ["cem", "cento", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"],
            ["mil", "milhão", "bilhão", "trilhão", "quadrilhão", "quintilhão", "sextilhão", "setilhão", "octilhão", "nonilhão", "decilhão", "undecilhão", "dodecilhão", "tredecilhão", "quatrodecilhão", "quindecilhão", "sedecilhão", "septendecilhão", "octencilhão", "nonencilhão"]
        ];
        var a, n, v, i, n = this.replace(c ? /[^,\d]/g : /\D/g, "").split(","), e = " e ", $ = "real", d = "centavo", sl;
        for(var f = n.length - 1, l, j = -1, r = [], s = [], t = ""; ++j <= f; s = []){
            j && (n[j] = (("." + n[j]) * 1).toFixed(2).slice(2));
            if(!(a = (v = n[j]).slice((l = v.length) % 3).match(/\d{3}/g), v = l % 3 ? [v.slice(0, l % 3)] : [], v = a ? v.concat(a) : v).length) continue;
            for(a = -1, l = v.length; ++a < l; t = ""){
                if(!(i = v[a] * 1)) continue;
                i % 100 < 20 && (t += ex[0][i % 100]) ||
                i % 100 + 1 && (t += ex[1][(i % 100 / 10 >> 0) - 1] + (i % 10 ? e + ex[0][i % 10] : ""));
                s.push((i < 100 ? t : !(i % 100) ? ex[2][i == 100 ? 0 : i / 100 >> 0] : (ex[2][i / 100 >> 0] + e + t)) +
                ((t = l - a - 2) > -1 ? " " + (i > 1 && t > 0 ? ex[3][t].replace("ão", "ões") : ex[3][t]) : ""));
            }
            a = ((sl = s.length) > 1 ? (a = s.pop(), s.join(" ") + e + a) : s.join("") || ((!j && (n[j + 1] * 1 > 0) || r.length) ? "" : ex[0][0]));
            a && r.push(a + (c ? (" " + (v.join("") * 1 > 1 ? j ? d + "s" : (/0{6,}$/.test(n[0]) ? "de " : "") + $.replace("l", "is") : j ? d : $)) : ""));
        }
        return r.join(e);
    }



    var name = "<?= $dados->nome ?>",
        ctps = "<?= $dados->ctps ?>",
        cargo = "<?= $dados->ocupacao ?>",
        cbo = "",
        salario_ext = "<?= $dados->salario ?>",
        salario = "<?= number_format($dados->salario, 2, ',', '.') ?>",
        admissao = "<?= $dados->data_admissao ?>";

    $('#nameFun').text(name);
    $('.nameFun').text(name);
    $('#numCtps').text(ctps);
    $('.numCtps').text(ctps);
    $('#nameCargo').text(cargo);
    $('.nameCargo').text(cargo);
    $('.nameCbo').text(cbo);

    var decimalSalario = salario_ext;
    var extensoSalario = (parseInt(decimalSalario).toString()).extenso(true);
    $('#salarioExtenso').text(extensoSalario);
    $('#valSalario').text(salario);
    $('.valSalario').text( salario );
    $('.salarioExtenso').text( extensoSalario );

    var fullNow = null;
    var data = admissao.split('-');
    console.log(data);
    var meses = [];
    var dia = null;
    var mes = null;
    var ano = null;
    if (data.length === 3) {
        dia = data[2];
        mes = data[1];
        ano = data[0];
        var objMes = {
             '01': 'Janeiro',
             '02': 'Fevereiro',
             '03': 'Março',
             '04': 'Abril',
             '05': 'Maio',
             '06': 'Junho',
             '07': 'Julho',
             '08': 'Agosto',
             '09': 'Setembro',
             '10': 'Outubro',
             '11': 'Novembro',
             '12': 'Dezembro'
             };
        meses.push(objMes);
        fullNow = dia+' de '+meses[0][mes]+' de '+ano;

    }

    $('#dateNow').text(fullNow);
    $('.dateExtenso').text(fullNow);
    $('.dateNow').text(dia+'/'+mes+'/'+ano);

</script>


    