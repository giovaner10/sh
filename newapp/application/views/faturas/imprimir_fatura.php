<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Imprimir Fatura - Show Tecnologia</title>

        <?php if (get_boleto_default() == 1) : ?>
           <link href="<?php echo base_url('media') ?>/css/bootstrap.1.css" rel="stylesheet">
        <?php endif; ?>

        <!-- Jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>        
        <script type="text/javascript"src="<?= versionFile('media/js', 'admin.js') ?>"></script>
        <script type="text/javascript"src="<?= versionFile('assets/js', 'pdf.js') ?>"></script>
        <script type="text/javascript"src="<?= versionFile('assets/js', 'pdf.worker.js') ?>"></script>

        <style>
           @page {
              margin-top: 0mm;
              margin-right: 14mm;
              margin-bottom: 0mm;
              margin-left: 14mm;
           }
           @media print {
              @page {
                 margin-top: 0mm;
                 margin-right: 14mm;
                 margin-bottom: 0mm;
                 margin-left: 14mm;
              }

              .no-print,
              .no-print * {
                 display: none !important;
              }

              body {
                 -webkit-print-color-adjust: exact;
              }

           }
           @media print {
                .pagebreak {
                     page-break-before: always;
                }
                /* page-break-after works, as well */

                table {
                     page-break-inside: avoid;
                }
            }
           .tab {
              border-top-width: 1px;
              border-right-width: 1px;
              border-bottom-width: 1px;
              border-left-width: 1px;
              border-top-style: solid;
              border-right-style: solid;
              border-bottom-style: solid;
              border-left-style: solid;
              font-size: 12px;
              width: 780px
           }
           .endshow {
              font-family: "Times New Roman", Times, serif;
              font-size: 12px;
           }
           .fatura {
              background-color: #CCC;
              font-size: 12px;
           }
           .dcli {
              border-bottom-style: dotted;
              border-bottom-width: 1px;
              border-bottom-color: #000;
              height: 0;
              font-size: 12px;
           }
           .prod {
              border-top-width: 1px;
              border-right-width: 1px;
              border-bottom-width: 1px;
              border-left-width: 1px;
              border-top-style: groove;
              border-right-style: solid;
              border-bottom-style: solid;
              border-left-style: solid;
              height: 1;
           }
           .inform {
              font-family: Arial, Helvetica, sans-serif;
              font-size: 10px;
              border-top-width: 1px;
              border-right-width: 1px;
              border-bottom-width: 1px;
              border-left-width: 1px;
              border-top-style: solid;
              border-right-style: solid;
              border-bottom-style: solid;
              border-left-style: solid;
           }
           .total {
              border: 2px solid #000;
              width: 100px;
           }
           .thumbBoleto {
                position: relative;
                max-width: 900px;
                border: 0px solid #fff;
           }
           .center{
               text-align: center;
           }

           .thumbBoletoImg {
                position: relative;
                max-width: 900px;
                height: 710px;
                overflow: hidden;
                border: 0px solid #fff;
           }
           .thumbBoletoImg img {
                position: absolute;
                left: 52%;
                top: 12%;
                -webkit-transform: translate(-50%,-50%);
                    -ms-transform: translate(-50%,-50%);
                        transform: translate(-50%,-50%);
           }
       </style>
   </head>
    <?php if ($this->input->get('simples')) {
        echo "Carregando<body style='display:none;'>";
    } else {
        echo "<body>";
    } ?>
        
        <?php if ($faturas) : ?>
           <?php foreach ($faturas as $fatura) : ?>
               <?php
                    /*
                     * dados do boleto
                     */
                    $data['dias_de_prazo_para_pagamento'] = 5;
                    $data['taxa_boleto'] = 0;
                    $data['juros_mes'] = 2;
                    $data['data_venc'] = $fatura->dataatualizado_fatura == null ? data_for_humans($fatura->data_vencimento) : data_for_humans($fatura->dataatualizado_fatura);
                    $data['nosso_numero'] = $fatura->numero_fatura;
                    $data['data_processamento'] = data_for_humans($fatura->data_emissao);
                    $data['valor_boleto'] = $fatura->valor_total;
                    $data['sacado'] = $fatura->nome . ' - ' . $fatura->cnpj;
                    $data['endereco1'] = $fatura->endereco . ', ' . $fatura->numero . ' - ' . $fatura->bairro;
                    $data['endereco2'] = $fatura->cidade . ' / ' . $fatura->uf;
                    $valor_taxa = false;
                ?>
                <div>
                    <center>
                        <!-- <button id='baixarFatura_<?= $fatura->numero_fatura ?>' class='btn btn-default' >
                            Baixar Fatura
                        </button> -->
                        <div style="display: table;">
                            <div style="float: left;margin-top: 31px;">
                                <div style="float: left;padding-top: 31px;margin-right: 9px;">
                                    <?php if($logo): ?>
                                        <img src="<?= base_url('media') ?>/img/<?=$logo?>" style="width: 150px;">
                                    <?php else: ?>
                                        <img src="<?= base_url('media') ?>/img/Logo.png" style="width: 150px;">
                                    <?php endif; ?>
                                </div>
                                <div style=" float: left; border-left-width: 5px; margin-right: 10px; border-left: 2px solid #438dcd; padding-left: 5px; ">
                                    <?php if($logo): ?>
                                        <b style="color: #0071bc; font-size: 13px;">
                                            SIMM2M - SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA
                                        </b>
                                    <?php else: ?>
                                        <b style="color: #0071bc;">
                                            SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA
                                        </b>
                                    <?php endif; ?>
                                    <p style="text-align: left;">
                                        Rua Rui Barbosa, 104, Anexo 112 - Centro<br>
                                        Guarabira - PB - CEP: 58.200-000<br>
                                        CNPJ: 09.338.999/0001-58<br>
                                        www.showtecnologia.com<br>
                                        Contato: 4020-2472
                                    </p>
                                </div>
                            </div>
                            <div style=" float: left; background-image: url(<?= base_url('media') ?>/img/fatura.png); width: 220px; height: 186px; background-size: 230px 186px;">
                                <center style=" color: white; margin-top: 8px; font-size: 13px; ">
                                    <b> FATURA
                                        <?php
                                            if ($fatura->mes_referencia) {
                                                echo 'REFERENTE À ' . $fatura->mes_referencia;
                                            } elseif ($fatura->informacoes == "SIMM2M") {
                                                echo "SIMM2M";
                                            }else {
                                                echo "SHOW TECNOLOGIA";
                                            }
                                        ?>
                                    </b><br>
                                    Numero: <?= $fatura->numero_fatura ?><br>
                                    Pago Em: <?= $fatura->data_pagto != '' ? data_for_humans($fatura->data_pagto) : '-- -- --' ?><br>
                                    Emissão: <?= data_for_humans($fatura->data_emissao) ?><?php $data['data_documento'] = data_for_humans($fatura->data_emissao); ?><br>
                                    Vencimento da Fatura: <?= data_for_humans($fatura->data_vencimento) ?><br>
                                    Atualizado: <?= $fatura->dataatualizado_fatura == null ? data_for_humans($fatura->data_vencimento) : data_for_humans($fatura->dataatualizado_fatura);
                                                $data['data_vencimento'] = $fatura->dataatualizado_fatura == null ? data_for_humans($fatura->data_vencimento) : data_for_humans($fatura->dataatualizado_fatura); ?><br>
                                </center>
                            </div>
                        </div>

                        <div style="color: white; background-color: #1567a7; border-radius: 8px; padding-top: 1px; width: 800px; margin-top: 10px; padding-bottom: 1px; ">
                            <h4>Dados do Cliente</h4>
                        </div>
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <div style="width: 800px; padding-bottom: 1px; margin-top: 3px; color: white; ">
                                            <div class="span1 center" style="margin-left:1px; background-color:#167ec2; border-radius:5px; width:85px;">
                                                Nome:
                                            </div>
                                            <div class="span8" style=" background-color: #d6e5f7; margin-left: -8px; padding-left: 6px; color: black; width: 714px; text-align: left; ">
                                                <?= $fatura->nome; ?>
                                            </div>
                                        </div>
                                        <br>
                                        <div style="width: 800px; padding-bottom: 1px; margin-top: 3px; color: white; ">
                                            <div class="span1 center" style=" margin-left:  1px; background-color:  #167ec2; border-radius: 5px; width: 85px; ">
                                                <?= $fatura->cnpj ? "CNPJ:" : "CPF:" ?>
                                            </div>
                                            <div style=" margin-left:  1px; margin-left: -8px; width: 206px; background-color: #d6e5f7; color:black; width:184px; padding-left:6px; text-align:left;" class="span2">
                                                <?= $fatura->cnpj ? $fatura->cnpj : $fatura->cpf ?>
                                            </div>
                                            <div class="span1 center" style=" margin-left: 1px; background-color: #167ec2; border-radius: 5px; margin-left: -8px; width: 70px; ">
                                                Email:
                                            </div>
                                            <div class="span5" style=" background-color: #d6e5f7; margin-left: -8px; padding-left: 6px; color: black; text-align: left; width: 470px; ">
                                                <?= $fatura->email; ?>
                                            </div>
                                        </div>
                                        <br>
                                        <div style="width: 800px; padding-bottom: 1px; margin-top: 2px; color: white;">
                                            <div class="span1 center" style=" margin-left:  1px; background-color:  #167ec2; border-radius: 5px; width: 85px; ">
                                                Endereço:
                                            </div>
                                            <div class="span8" style=" background-color: #d6e5f7; margin-left: -8px; padding-left: 6px; color: black; width: 714px; text-align: left; ">
                                                <?= $fatura->endereco; ?> Nº <?= $fatura->numero_cliente; ?> - <?= $fatura->complemento; ?>
                                            </div>
                                        </div>
                                        <br>
                                        <div style="width: 800px; padding-bottom: 1px; margin-top: 2px; color: white; ">
                                            <div class="span1 center" style=" margin-left:  1px; background-color:  #167ec2; border-radius: 5px; width: 85px; ">
                                                Bairro:
                                            </div>
                                            <div class="span3" style=" background-color: #d6e5f7; margin-left: -8px; padding-left: 6px; color: black; text-align: left; width: 270px; ">
                                                <?= $fatura->bairro; ?>
                                            </div>
                                            <div class="span1 center" style=" margin-left:  1px; margin-left: -8px; background-color:  #167ec2; border-radius: 5px; width: 70px; ">
                                                Cidade:
                                            </div>
                                            <div style="margin-left: 1px; margin-left: -8px; width: 206px;  background-color: #d6e5f7; color: black; padding-left: 6px;text-align: left; width: 240px;" class="span6">
                                                <?= $fatura->cidade; ?>
                                            </div>
                                            <div class="span1 center" style=" margin-left:  1px; margin-left: -8px; background-color:  #167ec2; border-radius: 5px; width: 70px; ">
                                                CEP:
                                            </div>
                                            <div style=" margin-left:  1px; margin-left: -8px; width: 206px; background-color: #d6e5f7; color:  black; width: 84px; padding-left:  6px; text-align: left; " class="span2">
                                                <?= $fatura->cep; ?>
                                            </div>
                                        </div>
                                        <br>
                                        <div style="width: 800px; padding-bottom: 1px; margin-top: 3px; color: white; ">
                                            <div class="span1 center" style=" margin-left: 1px; background-color:  #167ec2; border-radius: 5px; width: 85px;">
                                                Fone:
                                            </div>
                                            <div style=" margin-left:  1px; margin-left: -8px; background-color: #d6e5f7; color:  black; padding-left: 6px; text-align: left; width: 270px; " class="span3">
                                                <?= $fatura->fone ?>
                                            </div>
                                            <div class="span1 center" style=" margin-left:  1px; background-color:  #167ec2; border-radius: 5px; margin-left: -8px; width: 70px; ">
                                                Contato:
                                            </div>
                                            <div class="span5" style=" background-color: #d6e5f7; margin-left: -8px; padding-left: 6px; color: black; text-align: left; width: 384px; ">
                                                <?= $fatura->contato ? $fatura->contato : "." ?>
                                            </div>
                                        </div>
                                        <br>
                                        <div style="width: 800px; padding-bottom: 1px; margin-top: 3px; color: white; ">
                                            <div class="span6" style=" margin-left: 1px; background-color: #167ec2; border-radius: 5px; width: 626px; text-align: center;">
                                                Descrição:
                                            </div>
                                            <div class="span2" style=" margin-left: 1px; background-color: #167ec2; border-radius: 5px; width: 170px;  text-align: center;">
                                                Valor Total:
                                            </div>
                                        </div>
                                        <?php $taxa_boleto = false; ?>
                                        <?php $itens = $this->fatura->get_items(array('id_fatura' => $fatura->Id, 'status' => 1)); ?>
                                        <?php if (count($itens) > 0) : ?>
                                            <?php $count = 1; ?>
                                            <?php foreach ($itens as $item) : ?>
                                                <br>
                                                <div style="width: 800px; padding-bottom: 1px; margin-top: 3px; color: white; ">
                                                    <div class="span6" style=" margin-left:  1px; background-color:  #d0e5f6; border-radius: 5px; width: 586px; font-size: 12px; color:black; padding-left: 20px; padding-right: 20px; text-align:  justify; border-bottom: solid 2px white; ">
                                                        <?php
                                                            if ($item->tipotaxa_item == "juros") {
                                                                $valor_taxa = $item->valor_item;
                                                            }
                                                            echo $count++ . ' - ' . $item->descricao_item;
                                                        ?>
                                                    </div>
                                                    <div class="span2" style=" margin-left:  1px; background-color:  #d0e5f6; border-radius: 5px; width: 170px; text-align: right; ">
                                                        <div class="span1" style=" color:  black; width: 166px; margin-left: 2px; ">R$
                                                            <?= number_format($item->valor_item, 2, ',', '.'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                        <?php if ($fatura->irpj) : ?>
                                            <br>
                                            <div style="width: 800px; padding-bottom: 1px; margin-top: 3px; color: white; ">
                                                <div class="span6" style=" margin-left:  1px; background-color:  #d0e5f6; border-radius: 5px; width: 586px; font-size: 12px; color:black; padding-left: 20px; padding-right: 20px; text-align:  justify; border-bottom: solid 2px white; ">IRPJ</div>
                                                <div class="span2" style=" margin-left:  1px; background-color:  #d0e5f6; border-radius: 5px; width: 170px; text-align: right; ">
                                                    <div class="span1 center" style=" color:  black; width: 166px; margin-left: 2px; "><?= $fatura->irpj ?> %</div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($fatura->pis) : ?>
                                            <br>
                                            <div style="width: 800px; padding-bottom: 1px; margin-top: 3px; color: white; ">
                                                <div class="span6" style=" margin-left:  1px; background-color:  #d0e5f6; border-radius: 5px; width: 586px; font-size: 12px; color:black; padding-left: 20px; padding-right: 20px; text-align:  justify; border-bottom: solid 2px white; ">PIS</div>
                                                <div class="span2" style=" margin-left:  1px; background-color:  #d0e5f6; border-radius: 5px; width: 170px; text-align: right; ">
                                                    <div class="span1 center" style=" color:  black; width: 166px; margin-left: 2px; "><?= $fatura->pis ?> %</div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($fatura->cofins) : ?>
                                            <br>
                                            <div style="width: 800px; padding-bottom: 1px; margin-top: 3px; color: white; ">
                                                <div class="span6" style=" margin-left:  1px; background-color:  #d0e5f6; border-radius: 5px; width: 586px; font-size: 12px; color:black; padding-left: 20px; padding-right: 20px; text-align:  justify; border-bottom: solid 2px white; ">COFINS</div>
                                                <div class="span2" style=" margin-left:  1px; background-color:  #d0e5f6; border-radius: 5px; width: 170px; text-align: right; ">
                                                    <div class="span1 center" style=" color:  black; width: 166px; margin-left: 2px; "><?= $fatura->cofins ?> %</div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($fatura->csll) : ?>
                                            <br>
                                            <div style="width: 800px; padding-bottom: 1px; margin-top: 3px; color: white; ">
                                                <div class="span6" style=" margin-left:  1px; background-color:  #d0e5f6; border-radius: 5px; width: 586px; font-size: 12px; color:black; padding-left: 20px; padding-right: 20px; text-align:  justify; border-bottom: solid 2px white; ">Contribuição Social</div>
                                                <div class="span2" style=" margin-left:  1px; background-color:  #d0e5f6; border-radius: 5px; width: 170px; text-align: right; ">
                                                    <div class="span1 center" style=" color:  black; width: 166px; margin-left: 2px; "><?= $fatura->csll ?> %</div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <br>
                                        <div style="width: 800px; padding-bottom: 1px; margin-top: 3px; color: white; ">
                                            <div class="span6" style=" margin-left:  1px; background-color:  #d0e5f6; border-radius: 5px; width: 586px; font-size: 12px; color:black; padding-left: 20px; padding-right: 20px; text-align: justify;">
                                                ISS isento conforme LEI COMPLEMENTAR Nº 116, DE 31 DE JULHO DE 2003, A locação de bens imóveis ou móveis não
                                                constitui uma prestação de serviços, mas disponibilização de um bem, seja ele imóvel ou móvel para utilização
                                                do locatário sem a prestação de um serviço
                                            </div>
                                            <div class="span2" style=" margin-left:  1px; background-color:  #d0e5f6; border-radius: 5px; height: 60px; width: 170px; ">
                                                <div class="span1 center" style=" margin-left:  1px; background-color:  #167ec2; border-radius: 5px; width: 70px; height: 40px; padding-top: 20px; text-align: center;">
                                                    Total:
                                                </div>
                                                <div class="span1 center" style=" color:  black; width: 95px; margin-left: 2px; padding-top: 20px; text-align: right;">R$
                                                    <?php
                                                        $fatura->valor_total = $fatura->valor_total - ($fatura->valor_total * ($fatura->iss / 100)) - ($fatura->valor_total * ($fatura->cofins / 100)) - ($fatura->valor_total * ($fatura->irpj / 100)) - ($fatura->valor_total * ($fatura->csll / 100)) - ($fatura->valor_total * ($fatura->pis / 100));
                                                        echo number_format($fatura->valor_total, 2, ',', '.');
                                                        $data['valor_cobrado'] = $fatura->valor_total;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </center>
                </div>
                <?php if ($this->input->get('formaPagamento') == 'paypal') : ?>
                    <?php if ($this->input->get('eua')) : ?>
                        <form action="https://www.paypal.com/cgi-bin/webscr" id="form_paypal" method="post" style="display: inline;margin-right: 15px;">
                        <input type="hidden" name="cmd" value="_xclick">
                        <input type="hidden" name="business" value="paypal@myshowtec.com">
                        <input type="hidden" name="item_name" id="numero_fatura_paypal" value="Invoice #<?= $fatura->numero_fatura; ?>.<?php if ($valor_taxa) {
                                                                                                                                                     echo " _______________________ Juros de R$ " . $valor_taxa;
                                                                                                                                                  } ?>">
                        <input type="hidden" name="item_number" id="numero_fatura1_paypal" value="<?= $fatura->numero_fatura; ?>">
                        <input type="hidden" name="amount" id="valor_paypal" value="<?= $data['valor_cobrado'] ?>">
                        <input type="hidden" name="lc" value="US">
                        <input type="hidden" name="button_subtype" value="services">
                        <input type="hidden" name="no_note" value="0">
                        <input type="hidden" name="cn" value="Add special instructions to the seller:">
                        <input type="hidden" name="no_shipping" value="2">
                        <input type="hidden" name="currency_code" value="USD">
                        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynow_SM.gif:NonHosted">
                        </form>
                    <?php else : ?>
                        <form action="https://www.paypal.com/cgi-bin/webscr" id="form_paypal" method="post" <?php if (!$this->input->get('simples')) {
                                                                                                                       echo 'target="_blank"';
                                                                                                                    } ?> style="display: inline;margin-right: 15px;">
                        <input type="hidden" name="cmd" value="_xclick">
                        <input type="hidden" name="business" value="QQEXD24HDN3JY">
                        <input type="hidden" name="lc" value="BR">
                        <input type="hidden" name="item_name" id="numero_fatura_paypal" value="Fatura #<?= $fatura->numero_fatura; ?>.<?php if ($valor_taxa) {
                                                                                                                                                  echo " _______________________ Juros de R$ " . $valor_taxa;
                                                                                                                                               } ?>">
                        <input type="hidden" name="item_number" id="numero_fatura1_paypal" value="<?= $fatura->numero_fatura; ?>">
                        <input type="hidden" name="amount" id="valor_paypal" value="<?= $data['valor_cobrado'] ?>">
                        <input type="hidden" name="currency_code" value="BRL">
                        <input type="hidden" name="button_subtype" value="services">
                        <input type="hidden" name="no_note" value="1">
                        <input type="hidden" name="no_shipping" value="2">
                        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHosted">
                        <!--<input type="image" src="<?= base_url() ?>media/img/paypal-cc.jpg" border="0" id="botao_paypal" onclick="confirmar_data(this,'<?= date('d/m/Y'); ?>')" style="width: 30%" alt="PayPal - A maneira fácil e segura de enviar pagamentos online!">-->
                        <img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
                        </form>
                    <?php endif; ?>
                        <script>
                            $("#form_paypal").submit();
                        </script>

                <?php else : ?>
                    <center>
                        <div id="loading_bb_<?= $fatura->numero_fatura ?>" style="text-align:center; margin-top: 50px;" class="divPDF">
                            <img width="90px" src='<?= base_url() ?>media/img/loading2.gif' alt="Carregando boleto">
                        </div>
                        <div id="loading_bb_img_<?= $fatura->numero_fatura ?>" style="text-align:center;" class="divIMG" hidden>
                        </div>
                        <canvas id="boleto_bb_<?= $fatura->numero_fatura ?>" width="900" style="position:relative; display:none;"></canvas>
                    </center>
                    <div style="display:none;">
                        <form id="form_bb_<?= $fatura->numero_fatura ?>" action="<?php if ($this->input->get('imprimir')) {
                                                                                            echo base_url() . "index.php/api/boleto_bb/" . $fatura->numero_fatura;
                                                                                        } else {
                                                                                            echo "https://mpag.bb.com.br/site/mpag/";
                                                                                        } ?>" method="post" name="pagamento">
                            <input type="hidden" name="idConv" value="317877">
                            <input type="hidden" name="refTran" value="2852865<?php for ($i = 10 - strlen($fatura->numero_fatura); $i > 0; $i--) {
                                                   echo '0';
                                                }
                                                echo $fatura->numero_fatura; ?>">
                            <input type="hidden" name="valor" value="<?= number_format($data['valor_cobrado'], 2, '', '') ?>">
                            <input type="hidden" name="qtdPontos" value="1">
                            <input type="hidden" name="dtVenc" value="<?= str_replace('/', '', $data['data_venc']) ?>">
                            <input type="hidden" id="tpPagamento" name="tpPagamento" value="2">
                            <?php if ($fatura->cpf) : ?>
                                <input type="hidden" name="cpfCnpj" value="<?= str_replace('.', '', str_replace('/', '', str_replace('-', '', $fatura->cpf))) ?>">
                                <input type="hidden" name="indicadorPessoa" value="1">
                            <?php elseif ($fatura->cnpj) : ?>
                                <input type="hidden" name="cpfCnpj" value="<?= str_replace('.', '', str_replace('/', '', str_replace('-', '', $fatura->cnpj))) ?>">
                                <?php if (strlen(str_replace('.', '', str_replace('/', '', str_replace('-', '', $fatura->cnpj)))) == 11) : ?>
                                    <input type="hidden" name="indicadorPessoa" value="1">
                                <?php else : ?>
                                    <input type="hidden" name="indicadorPessoa" value="2">
                                <?php endif; ?>
                            <?php endif; ?>

                            <input type="hidden" name="tpDuplicata" value="DS">
                            <input type="hidden" name="nome" value="<?= $fatura->nome ?>">
                            <input type="hidden" name="endereco" value="<?= $fatura->endereco ?>">
                            <input type="hidden" name="cidade" value="<?= $fatura->cidade ?>">
                            <input type="hidden" name="msgLoja" value="- Sr. Caixa, não receber após 3 dias de atraso<br>- Em caso de dúvidas entre em contato: 4020-2472<br>- E-mail: cobranca@showtecnologia.com">
                            <input type="hidden" name="uf" value="<?= $fatura->uf ?>">
                            <input type="hidden" name="cep" value="<?= str_replace('.', '', str_replace('-', '', $fatura->cep)) ?>">
                            <input type="hidden" name="urlRetorno" value="<?= base_url() ?>index.php/faturas/imprimir_fatura/<?= $fatura->numero_fatura ?>">
                        </form>
                    </div>
                    <script type="text/javascript" src="//mozilla.github.io/pdf.js/build/pdf.js"></script>
                    <script type="text/javascript"src="<?= versionFile('assets/js', 'pdf.js') ?>"></script>
                    <script type="text/javascript"src="<?= versionFile('assets/js', 'pdf.worker.js') ?>"></script>
                    <script type="text/javascript"src="<?= versionFile('assets/js', 'boleto_bb.js') ?>"></script>
                    <script type="text/javascript">
                    var site_url = "<?=site_url()?>";
                    var base_url = "<?=base_url()?>";
                    var faturas = "";
                    var doc, ctx;

                    window.onafterprint = function() {
                        $.ajax({
                            type: "POST",
                            url: '<?= base_url() ?>index.php/api/boleto_enviado',
                            data: faturas,
                            success: function(data) {
                            }
                        });
                    };

                    $('#boletoIframe').on('load', function() {
                        // Encontre o iframe pelo ID
                        var iframe = $('#boletoIframe');       
                        
                        // Acesse a janela do iframe
                        var iframeWindow = iframe[0].contentWindow;

                        iframe.width = iframeWindow.document.body.scrollWidth;
                        iframe.height = iframeWindow.document.body.scrollHeight;
                        // Role o conteúdo do iframe para o final
                        // iframeWindow.scrollTo(0, iframeWindow.document.body.scrollHeight)
                    })


                    //GERA O BOLETO E MOSTRA NA PAGINA
                    geraBoleto(<?= $fatura->numero_fatura ?>);

                    if (faturas == "") {
                        faturas += 'faturas[]=<?= $fatura->numero_fatura ?>';
                    } else {
                        faturas += '&faturas[]=<?= $fatura->numero_fatura ?>';
                    }
                    </script>

                    <div class="pagebreak"> </div>

                <?php endif;
                ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </body>
</html>
