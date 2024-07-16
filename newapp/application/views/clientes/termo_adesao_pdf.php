<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?=base_url('media/img/favicon.png');?>">
        <title>PDF Termo Adesão</title>
    </head>
    <link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/errors', '403.css') ?>">
    <style>
        body {
            font-family: Arial;
            font-size: 10px;
            text-align: left;
            vertical-align: top;
            width: 22cm;
            margin: 30px auto; 
        }

        th {
            text-align: left;
            font-size: 12px;
            padding: 10px;
            background-color: #80a1eb;
            color: white;
            border: 2px solid lightgray;
            vertical-align: top;
        }

        td {
            word-break: break-all;
            padding: 10px;
            border-bottom: 1px solid lightgray;
            vertical-align: top;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border: 2px solid lightgray;
            margin-bottom: 20px;
        }

        .header {
            margin-bottom: 20px;
            display: flex !important;
            justify-content: space-between;
        }

        p {
            word-break: normal;
            text-align: justify;
            margin: 0;
            line-height: 1.5;
        }

        .assinaturas {
            display: flex;
            width: 100%;
        }

        .assinatura {
            margin-top: 40px;
            flex: 1;
        }

        .assinatura p {
            text-align: center;
        }

        .forbidden-page #error-message {
            font-size: 14px;
            text-align: center;
            margin: 20px;
            color: #7f7f7f;
        }

        .go-back-btn {
            text-decoration: none;
        }

        @media print {
            @page {
                margin-top: 0mm;
                margin-right: 14mm;
                margin-bottom: 0mm;
                margin-left: 14mm;
            }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
    <?php
        function obterDataAtual($date) {
            $daysOfWeek = [
                "DOMINGO", "SEGUNDA-FEIRA", "TERÇA-FEIRA", "QUARTA-FEIRA",
                "QUINTA-FEIRA", "SEXTA-FEIRA", "SÁBADO"
            ];
            
            $monthsOfYear = [
                "JANEIRO", "FEVEREIRO", "MARÇO", "ABRIL",
                "MAIO", "JUNHO", "JULHO", "AGOSTO",
                "SETEMBRO", "OUTUBRO", "NOVEMBRO", "DEZEMBRO"
            ];
            
            $dayOfWeek = $daysOfWeek[date('w', strtotime($date))];
            $day = date('d', strtotime($date));
            $month = $monthsOfYear[date('n', strtotime($date)) - 1];
            $year = date('Y', strtotime($date));
            
            return "$dayOfWeek, $day DE $month DE $year";
        }
    ?>
    <?php if ($termo): ?>
        <body style="background-color: white;" onload="javascript:window.print();">
            <div class="header">
                <img style="width: 150px;" src="<?= versionFile('assets/images', 'Show-logo.png')?>"></img>
                <h2 style="font-size: 19px; font-weight: normal;">TERMO DE ADESÃO A PRESTAÇÃO DE SERVIÇO</h2>
            </div>

            <table>
                <tr>
                    <th colspan="10">DADOS DA CONTRATADA:</th>
                </tr>
                <tr>
                    <td colspan="4">Nome: <strong>SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA</strong> </td>
                    <td colspan="2">CNPJ: <strong>09.338.999/0001-58</strong> </td>
                    <td colspan="2">Inscrição Estadual: <strong>ISENTA</strong> </td>
                    <td colspan="2">Telefone: <strong>4020-2472</strong></td>
                </tr>
                <tr>
                    <td colspan="6">Endereço: <strong>RUA RUI BARBOSA, 104, ANEXO 112 - CENTRO - GUARABIRA/PB - CEP: 58.200-000</strong> </td>
                    <td colspan="4">Executivo de Vendas: <strong>MARIA EDUARDA MARTINS DO NASCIMENTO</strong></td>
                </tr>
                <tr>
                    <th colspan="10">DADOS DO CONTRATANTE: <span style="font-size: 9px;">(Anexar cópia do comprovante de endereço, RG do assinante e contrato social se pessoa jurídica)</span> </th>
                </tr>
                <tr>
                    <td colspan="4">Razão Social / Nome: <strong><?= $termo["nome_cliente"] ?: ""  ?></strong> </td>
                    <td colspan="3">CNPJ / CPF: <strong><?= $termo["documento_cliente"] ?: ""  ?></strong> </td>
                    <td colspan="3">Inscrição Estadual: <strong><?= $termo["inscricao_estadual"] ?: ""  ?></strong> </td>
                </tr>
                <tr>
                    <td colspan="4">E-mail: <strong><?= $termo["email_cliente"] ?: ""  ?></strong> </td>
                    <td colspan="3">Telefone Fixo: <strong><?= $termo["telefone_cliente"] ?: ""  ?></strong> </td>
                    <td colspan="3">Telefone Celular: <strong><?= $termo["celular_cliente"]  ?: ""  ?></strong> </td>
                </tr>
                <tr>
                    <td colspan="4">Endereço: <strong><?= $termo["endereco_cliente"] ?: ""  ?></strong> </td>
                    <td colspan="3">Bairro: <strong><?= $termo["bairro_cliente"] ?: ""  ?></strong> </td>
                    <td colspan="3">Cidade: <strong><?= $termo["cidade_cliente"] ?: ""  ?></strong> </td>
                </tr>
                <tr>
                    <td colspan="4">Complemento: <strong><?= $termo["complemento_cliente"] ?: ""  ?></strong> </td>
                    <td colspan="3">Estado: <strong><?= strtoupper($termo["estado_cliente"]) ?: ""  ?></strong> </td>
                    <td colspan="3">Cep: <strong><?= $termo["cep_cliente"] ?: ""  ?></strong> </td>
                </tr>
                <tr>
                    <th colspan="10">CONDIÇÕES COMERCIAIS: </th>
                </tr>
                <tr>
                    <td colspan="4">Tipo de Contrato: <strong><?= $termo["tipo_contrato"] ?: ""  ?></strong> </td>
                    <td colspan="3">Pessoa do Contas à Pagar: <strong><?= $termo["pessoa_contas"] ?: ""  ?></strong> </td>
                    <td colspan="3">Contato do Contas à Pagar: <strong><?= $termo["contato_contas"] ?: ""  ?></strong> </td>
                </tr>
                <tr>
                    <td colspan="4">E-mail Financeiro: <strong><?= $termo["email_contas"] ?: ""  ?></strong> </td>
                    <td colspan="3">Quantidade de Equipamentos: <strong><?= $termo["qtd_equipamentos"] ?: ""  ?></strong> </td>
                    <td colspan="3">Bloqueio: <strong><?= $termo["bloqueio"] ?: ""  ?></strong> </td>
                </tr>
                <tr>
                    <th colspan="10">CONDIÇÕES DE PAGAMENTO: </th>
                </tr>
                <tr>
                    <td colspan="3">Contato do Contas à Pagar: <strong><?= $termo["contato_pagamento"] ?: ""  ?></strong> </td>
                    <td colspan="4">E-mail: <strong><?= $termo["email_pagamento"] ?: ""  ?> </strong> </td>
                    <td colspan="3">Instalação em Parcelas: <strong><?= $termo["parcelas_pagamento"] ?: ""  ?></strong> </td>
                </tr>
                <tr>
                    <td colspan="3">Pacote de Serviços: <strong><?= $termo["pacote_servico"] ?: ""  ?></strong> </td>
                    <td colspan="4">Periodo de Contrato: <strong><?= $termo["periodo_contrato"] ? ($termo["periodo_contrato"]."MESES"): ""  ?></strong> </td>
                    <td colspan="3">Dia de Vencimento: <strong><?= $termo["dia_vencimento"] ?: ""  ?></strong> </td>
                </tr>
                <tr>
                    <td colspan="3">Valor de Instalação por Veículo: <strong><?= $termo["valor_instalacao"] ?: ""  ?></strong> </td>
                    <td colspan="4">1º Vencimento da Adesão: <strong><?= $termo["vencimento_adesao"] ? data_for_humans($termo["vencimento_adesao"]) : ""  ?></strong> </td>
                    <td colspan="3">1º Vencimento da Mensalidade: <strong><?= $termo["vencimento_mensalidade"] ? data_for_humans($termo["vencimento_mensalidade"]) : ""  ?></strong> </td>
                </tr>
                <tr>
                    <td colspan="10">Valor de Mensalidade por Veículo: <strong><?= $termo["valor_mensalidade"] ?: ""  ?></strong> </td>
                </tr>
                <tr>
                    <td colspan="3">Produto Adicional: <strong><?= $termo["produto_adicional"] ?: ""  ?></strong> </td>
                    <td colspan="4">Quantidade: <strong><?= $termo["qtd_adicional"] ?: ""  ?></strong> </td>
                    <td colspan="3">Valor Unitário: <strong><?= $termo["valor_unt_adicional"] ?: ""  ?></strong> </td>
                </tr>
                <tr>
                    <td colspan="3">Adicional em Parcelas: <strong><?= $termo["adicional_parcelas"] ?: ""  ?></strong> </td>
                    <td colspan="4">1º Vencimento de Adicional: <strong><?= $termo["vencimento_adicional"] ?  data_for_humans($termo["vencimento_adicional"]) : ""  ?></strong> </td>
                    <td colspan="3">Total: <strong><?= $termo["total_adicional"] ?: ""  ?></strong> </td>
                </tr>
                <tr>
                    <td colspan="3">Instalação Sigilosa: <strong><?= $termo["instalacao_sigilosa"] ?: ""  ?></strong> </td>
                    <td colspan="7">Observação: <strong><?= $termo["observacao_termo"] ?: ""  ?></strong> </td>
                </tr>
                <tr>
                    <th colspan="10"> </th>
                </tr>
                <tr>
                    <td colspan="10">
                        <p>
                            Declaro que as informações acima, descritas por mim, são verdadeiras, concordando com os termos e valores expressos neste documento, ciente das recomendações e condições gerais abaixo: <br><br>
                            1.	Para a instalação e correto funcionamento do equipamento é fundamental o recebimento, pela SHOW TECNOLOGIA, do Termo de Adesão preenchido e assinado, bem como a prévia confirmação de dados cadastrais do usuário e senha de acesso junto à central de atendimento da SHOW TECNOLOGIA.<br>
                            2.	Mantenha sempre em perfeitas condições o sistema elétrico de seu veículo e o(s) EQUIPAMENTO(S) descritos acima, não permitindo que seja(m) manuseado(s), removido(s), reparado(s) ou desinstalado(s) por pessoas não autorizadas pela SHOW TECNOLOGIA, comunicando imediatamente qualquer defeito no EQUIPAMENTO ou no siste ma elétrico do veículo, a fim de evitar o mau funcionamento e danos ou prejuízos a si próprio, à SHOW TECNOLOGIA ou a terceiros. Afinal, NÃO SENDO ESTE DOCUMENTO UMA APÓLICE DE SEGUROS, não há garantia de recuperação do veículo.<br>
                            3.	Mantenha o sigilo de sua senha de ativação e acesso à Central de Atendimento; ela é pessoal e intransferível.<br>
                            4.	Em caso de venda, troca ou cessão a terceiros de seu veículo, comunique imediatamente a SHOW TECNOLOGIA para que seja retirado/transferido o EQUIPAMENTO por profissionais credenciados, bem como para correta atualização dos dados cadastrais do veículo e demais informações registradas na CENTRAL DE ATENDIMENTO, para viabilizar a continuidade da correta prestação de serviços.<br>
                            5.	Recomendamos confirmar a operacionalidade do EQUIPAMENTO a cada 30 dias, em caráter de teste, sem custo adicional. IMPORTANTE: O teste poderá ser feito através da abertura de "ticket" no GESTOR ONLINE através do site www.showtecnologia.com.<br>
                            6.	Existindo defeito no equipamento, os quais não sejam causados por falha de comunicação, deverá encaminhar o veículo a autorizada da SHOW TECNOLOGIA, para que possa substituir imediatamente o equipamento, sem qualquer custo adicional. Entretanto, caso seja identificado que o problema seja decorrente do uso, ou motivos outrem, a contratante arcará com o serviço solicitado.<br>
                            7.	Os serviços contratados poderão ser renovados por igual período, com reajuste anual, ou no menor prazo permitido em lei, pelo IGPM. Caso promovi das alterações nas caracte rísticas/pacote de serviços, será necessária assinatura de ADENDO CONTRATUAL, com automática renovação pelo prazo mínimo de 12 mese s, caso o tempo restante para o término do contrato original ou imediatamente anterior à renovação seja inferior a 1(um) ano.<br>
                            8.	O contrato poderá ser cancelado por qualquer uma das partes, mediante comunicação à outra de sua intenção com a antecedência mínima de 30(trinta) dias e sempre por escrito, por qualquer meio idôneo, considerando-se válida a enviada ao endereço constante no presente e no cadastro, mesmo que recebida por terceiros e não tenha havido comu nicação de qualquer alteração de seus dados cadastrais, em especial, seu endereço, telefone e/ou e-mail. Evite o cancelamento antes do término do período contratado, para não incidir na multa compensatória de até 100%(cem por cento) do valor das parcelas vincendas, conforme previsto no contrato de prestação de serviços.<br>
                            9.	Mantenha os pagamentos em dia, evite multas, juros de mora, a suspensão ou até mesmo o cancelamento dos serviços. Caso não receba o boleto de cobrança em até 2(dois) dias úteis anteriores à data de seu vencimento entre em contato com a Central de Atendimento da SHOW TECNOLOGIA - 4020-2472.<br>
                            10.	Leia atentamente as demais condições previsto em contrato, tais como hipóteses de rescisão contratual, responsabilidades, prazo de devolução de equipamentos e outros, devidamente registrado perante o Cartório Toscano de Sales - 2º Ofício de Notas - Av. Dom Pedro II, nº 43- Centro - Guarabira - PB, CEP: 58.200-000, protocolado no livro A-0005, registrado no livro B-0263 sob nº 035596.<br>
                            11.	A instalação, retirada, reinstalação ou correção só mediante comunicação ao nosso suporte e feita por técnicos da Contratada, caso seja realizado por outro técnico não autorizado será cobrado uma multa de quinhentos reais por equipamento retirado.<br>
                            12.	Caso necessite de manutenção, posterior a instalação, será cobrado o valor de cento e cinquenta reais por serviço realizado, seja ele, uma retirada ou reinstalação, sendo cobrado também o deslocamento do técnico para realização do serviço, contando como ponto inicial a localização do técnico mais próximo, no valor de cinquenta centavos por KM rodado, com exceção de correção ou falha de equipamento. <br>
                            13.	É de total responsabilidade do Contratante a conservação dos equipamentos instalados no veiculo, tendo a obrigação de devolver o equipamento, em perfeito estado, caso o equipamento seja extraviado ou danificado, estará assumindo a multa por equipamento no valor de um mil Reais. <br>
                            14.	Não havendo o pagamento por um período superior a 15 (quinze) dias, a SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA poderá tomar todas as providências cabíveis para recuperação de seu crédito, inclusive a promoção de negativação do usuário perante os órgãos de proteção do crédito. <br>
                            15.	Após o período informado na cláusula 14 os serviços prestados pela Contratada serão suspensos até que haja o pagamento devido dos valores vencidos.
                        </p>
                    </td>
                </tr>
            </table>

            <div class="assinaturas">
                <div class="assinatura">
                    <p>___________________________________________________________________</p>
                    <p>SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA-ME </p>
                    <p>CNPJ: 09.338.999/0001-58 - INSC EST: ISENTA </p>
                    <p><?= obterDataAtual(date('Y-m-d')); ?></p>
                </div>
                <div class="assinatura">
                    <p>___________________________________________________________________</p>
                    <p><?= strtoupper($termo["nome_cliente"]) ?: ""  ?></p>
                    <p>CPF/CNPJ: <?= $termo["documento_cliente"] ?: ""  ?> <?= $termo["inscricao_estadual"] ? (" - INSC EST: " . $termo["inscricao_estadual"]) : ""  ?></p>
                    <p><?= obterDataAtual(date('Y-m-d')); ?></p>
                </div>
            </div>

            <div class="assinaturas">
                <div class="assinatura">
                    <p>___________________________________________________________________</p>
                    <p>TESTEMUNHA</p>
                </div>
                <div class="assinatura">
                    <p>___________________________________________________________________</p>
                    <p>TESTEMUNHA</p>
                </div>
            </div>
        </body>
    <?php else: ?>
        <body style="background-color: white;">
            <div class="forbidden-page">
                <div id="error-container">
                    <img class="img-403" src="<?= base_url('assets/images/icon_aviso.svg') ?>" />
                    <h1 id="error-code">Não foi possível gerar o documento!</h1>
                    <p id="error-message">Os dados do cliente não foram carregados. <br> Tente realizar o procedimento novamente ou entre em contanto com o suporte.</p>
                    <a class="go-back-btn" href="javascript:window.close();">Voltar</a>
                </div>
            </div>
        </body>
    <?php endif; ?>
</html>