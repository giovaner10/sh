<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if (get_boleto_default() == 1) : ?>
    <link href="<?php echo base_url('media') ?>/css/bootstrap.1.css" rel="stylesheet">
<?php endif; ?>
<style>
    @page {
        margin-top: 0mm;
        margin-right: 14mm;
        margin-bottom: 0mm;
        margin-left: 14mm;
    }
    @media print
    {
        @page {
            margin-top: 0mm;
            margin-right: 14mm;
            margin-bottom: 0mm;
            margin-left: 14mm;
        }
        .no-print, .no-print *
        {
            display: none !important;
        }
        body {-webkit-print-color-adjust: exact;}

    }@media print {
        .pagebreak { page-break-before: always; } /* page-break-after works, as well */

        table {page-break-inside: avoid;}
    }
</style>

<!-- Jquery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>        
<script type="text/javascript"src="<?= versionFile('media/js', 'admin.js') ?>"></script>
<script type="text/javascript"src="<?= versionFile('assets/js', 'pdf.js') ?>"></script>
<script type="text/javascript"src="<?= versionFile('assets/js', 'pdf.worker.js') ?>"></script>

<style>
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
        width:780px
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
         height: 710px;
         overflow: hidden;
         border: 0px solid #fff;
    }
    .thumbBoleto img {
         position: absolute;
         left: 52%;
         top: 12%;
         -webkit-transform: translate(-50%,-50%);
             -ms-transform: translate(-50%,-50%);
                 transform: translate(-50%,-50%);
    }

</style>

<body>
<?php if($faturas):?>
    <?php foreach($faturas as $fatura):?>
        <?php
        /*
         * dados do boleto
         */
        $data['dias_de_prazo_para_pagamento'] = 5;
        $data['taxa_boleto'] = 0;
        $data['juros_mes'] = 2;
        $data['data_venc'] = $fatura->dataatualizado_fatura == NULL ? data_for_humans($fatura->data_vencimento) : data_for_humans($fatura->dataatualizado_fatura) ;
        $data['nosso_numero'] = $fatura->numero_fatura;
        $data['data_processamento'] = data_for_humans($fatura->data_emissao);
        $data['valor_boleto'] = $fatura->valor_total;
        $data['sacado'] = $fatura->nome.' - '.$fatura->cnpj;
        $data['endereco1'] = $fatura->endereco.', '.$fatura->numero.' - '.$fatura->bairro;
        $data['endereco2'] = $fatura->cidade.' / '.$fatura->uf;
        $valor_taxa = false;

        ?>
        <div align="center" >
            <table class="tab" align="center">
                <tr>
                    <td width="250"><img width="180" src="<?php echo base_url('media') ?>/img/norio.jpg" ></td>
                    <td width="350" valign="top" align="left" class="endshow"> NORIO MOMOI - EPP<br>
                        R NAPOLEAO LAUREANO , S/N - Bairro Novo<br>
                        Guarabira - PB - CEP: 58.200-000<br>
                        CNPJ: 21.698.912/0001-59<br>
                        www.showtecnologia.com<br>
                        Fone: (83) 3271-9604
                    </td>
                    <td align="right" valign="top">
                        <div class="fatura" align="center"><strong>FATURA</strong></div>
                        Numero:  <?php echo $fatura->numero_fatura?><?php $data['numero_documento'] = $fatura->numero_fatura; ?><br>
                        Pago Em: <?php echo $fatura->data_pagto != '' ? data_for_humans($fatura->data_pagto) : '-- -- --'?><br>
                        Emiss&atilde;o: <?php echo data_for_humans($fatura->data_emissao)?><?php $data['data_documento'] = data_for_humans($fatura->data_emissao); ?><br>
                        Data da Fatura: <?php echo data_for_humans($fatura->data_vencimento)?>
                        Venc. Atualizado: <?php echo $fatura->dataatualizado_fatura == NULL ? data_for_humans($fatura->data_vencimento) : data_for_humans($fatura->dataatualizado_fatura); $data['data_vencimento'] = $fatura->dataatualizado_fatura == NULL ? data_for_humans($fatura->data_vencimento) : data_for_humans($fatura->dataatualizado_fatura); ?>
                    </td>
                </tr>
            </table>

            <table class="tab" align="center">
                <tr>
                    <td colspan="4" class="fatura"><strong>Dados do Cliente</strong></td>
                </tr>
                <tr>
                    <td  align="right">Nome:</td>
                    <td  colspan="3" class="dcli"><?php
                        echo $fatura->nome;
                        ?></td>
                </tr>
                <tr>
                    <td align="right">Endere&ccedil;o:</td>
                    <td colspan="3" class="dcli">
                        <?php echo $fatura->endereco; ?> Nº <?php echo $fatura->numero_cliente; ?>
                        - <?php echo $fatura->complemento; ?>
                    </td>
                </tr>
                <tr>
                    <td align="right">Bairro:</td>
                    <td class="dcli"><?php echo $fatura->bairro?></td>
                    <td align="right">CNPJ:</td>
                    <td  class="dcli"><?php echo $fatura->cnpj?></td>
                </tr>
                <tr>
                    <td align="right">Cidade:</td>
                    <td class="dcli"><?php echo $fatura->cidade?> - <?php echo $fatura->uf?></td>
                    <td align="right">CEP:</td>
                    <td class="dcli"><?php echo $fatura->cep?></td>
                </tr>
                <tr>
                    <td align="right">Fone:</td>
                    <td class="dcli"><?php echo $fatura->fone?></td>
                    <td align="right">Contato:</td>
                    <td class="dcli"><?php echo $fatura->contato?></td>
                </tr>
                <tr>
                    <td align="right">Email:</td>
                    <td class="dcli"><?php echo $fatura->email?></td>
                    <?php if($fatura->id_contrato != ''): ?>
                        <td align="right">Prefixo:</td>
                        <td class="dcli"><?php echo $fatura->id_contrato?></td>
                    <?php endif; ?>
                </tr>
            </table>
            <table class="tab" align="center">
                <tr class="dcli">
                    <td colspan="6" class="fatura tab"><strong>Produtos ou Servi&ccedil;os</strong></td></tr>
                <tr>
                <tr>
                    <th width="400" class="prod">Descri&ccedil;&atilde;o</th>
                    <th width="80" class="prod" align="center">Valor Total</th>
                </tr>
                <?php $taxa_boleto = false;?>
                <?php $itens = $this->fatura->get_items(array('id_fatura' => $fatura->Id));?>
                <?php if (count($itens) > 0):?>
                    <?php foreach ($itens as $item):?>

                        <tr>
                            <td><?php if ($item->tipotaxa_item == "juros") {
                                              $valor_taxa = $item->valor_item;
                                           }
                                    echo $item->descricao_item; ?></td>
                            <td align="right"><?php echo $item->valor_item; ?></td>
                        </tr>
                        <?php if($item->taxa_item && $item->tipotaxa_item == 'boleto') $taxa_boleto = 4.5?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php $valor_b = substr(strrchr($fatura->descricao, "$"), 1); ?>
                    <td><?php echo $fatura->descricao."<br>KM's rodados:";?></td>
                    <td align="right"><?php echo $valor_b."<br>".$kms; ?></td>
                <?php endif; ?>
                <?php if ($fatura->irpj): ?>
                    <tr>
                        <td >IRPJ</td>
                        <td align="right"> <?php echo $fatura->irpj ?> %</td>
                    </tr>
                <?php endif; ?>
                <?php if ($fatura->irpj): ?>
                    <tr>
                        <td >PIS</td>
                        <td align="right"><?php echo $fatura->pis ?> %</td>
                    </tr>
                <?php endif; ?>
                <?php if ($fatura->irpj): ?>
                    <tr>
                        <td >COFINS</td>
                        <td align="right"><?php echo $fatura->cofins ?> %</td>
                    </tr>
                <?php endif; ?>
                <?php if ($fatura->irpj): ?>
                    <tr>
                        <td >Contribuição Social</td>
                        <td align="right"><?php echo $fatura->csll ?> %</td>
                    </tr>
                <?php endif; ?>
            </table>

            <table class="tab">
                <tr>
                    <td valign="top" class="inform">
                        ISS isento conforme LEI COMPLEMENTAR N&ordm; 116, DE 31 DE JULHO DE 2003, A loca&ccedil;&atilde;o de bens im&oacute;veis ou m&oacute;veis n&atilde;o constitui uma presta&ccedil;&atilde;o de servi&ccedil;os, mas disponibiliza&ccedil;&atilde;o de um bem, seja ele im&oacute;vel ou m&oacute;vel para utiliza&ccedil;&atilde;o do locat&aacute;rio sem a presta&ccedil;&atilde;o de um servi&ccedil;o.
                    </td>
                    <td align="center" class="total">
                        TOTAL FATURA R$
                    </td>
                    <td class="total" align="right">
                        <?php
                        $fatura->valor_total -= $fatura->valor_total * ($fatura->iss / 100);
                        $fatura->valor_total -= $fatura->valor_total * ($fatura->pis / 100);
                        $fatura->valor_total -= $fatura->valor_total * ($fatura->cofins / 100);
                        $fatura->valor_total -= $fatura->valor_total * ($fatura->csll / 100);
                        $fatura->valor_total -= $fatura->valor_total * ($fatura->irpj / 100);
                        echo number_format($fatura->valor_total,2, ',', '.');
                        $data['valor_cobrado'] = $fatura->valor_total;
                        ?>&nbsp;
                    </td>
                </tr>
            </table>
            <div>
				 <center>
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
                         <!-- EMISSÃO BB -->
                         <form id="form_bb_<?=$fatura->numero_fatura?>" action="<?php if($this->input->get('imprimir')){echo base_url()."index.php/api/boleto_bb/".$fatura->numero_fatura;}else{echo "https://mpag.bb.com.br/site/mpag/";}?>" method="post" name="pagamento">
                             <input type="hidden" name="idConv" value="323400">
                             <input type="hidden" name="refTran" value="3149704<?php for($i = 10-strlen($fatura->numero_fatura);$i>0;$i--){echo '0';}echo $fatura->numero_fatura;?>">
                             <input type="hidden" name="valor" value="<?=number_format($data['valor_cobrado'],2,'','')?>">
                             <input type="hidden" name="qtdPontos" value="1">
                             <input type="hidden" name="dtVenc" value="<?=str_replace('/','', $data['data_venc'])?>">
                             <input type="hidden" id="tpPagamento" name="tpPagamento" value= "2">
                             <?php if($fatura->cpf):?>
                                 <input type="hidden" name="cpfCnpj" value="<?=str_replace('.','',str_replace('/','',str_replace('-','',$fatura->cpf)))?>">
                                 <input type="hidden" name="indicadorPessoa" value="1">
                             <?php elseif($fatura->cnpj):?>
                                 <input type="hidden" name="cpfCnpj" value="<?=str_replace('.','',str_replace('/','',str_replace('-','',$fatura->cnpj)))?>">
                                 <?php if(strlen (str_replace('.','',str_replace('/','',str_replace('-','',$fatura->cnpj))))==11):?>
                                     <input type="hidden" name="indicadorPessoa" value="1">
                                 <?php else:?>
                                     <input type="hidden" name="indicadorPessoa" value="2">
                                 <?php endif; ?>
                             <?php endif; ?>

                             <input type="hidden" name="tpDuplicata" value="DS">
                             <input type="hidden" name="nome" value="<?=$fatura->nome?>">
                             <input type="hidden" name="endereco" value="<?=$fatura->endereco?>">
                             <input type="hidden" name="cidade" value="<?=$fatura->cidade?>">
                             <input type="hidden" name="msgLoja" value="- Sr. Caixa, não receber após 3 dias de atraso<br>- Em caso de dúvidas entre em contato: 4020-2472<br>- E-mail: cobranca@showtecnologia.com">
                             <input type="hidden" name="uf" value="<?=$fatura->uf?>">
                             <input type="hidden" name="cep" value="<?=str_replace('.','',str_replace('-','',$fatura->cep))?>">
                             <input type="hidden" name="urlRetorno" value="<?=base_url()?>index.php/faturas/imprimir_fatura/<?=$fatura->numero_fatura?>">
                         </form>
                         <div>
                             <center id="loading_bb_<?=$fatura->numero_fatura?>">
                                 <img width="90px" src='<?=base_url()?>media/img/loading2.gif' alt="Carregando boleto">
                                 <canvas id="boleto_bb_<?= $fatura->numero_fatura ?>" width="850" style="display:none;"></canvas>
                             </center>
                         </div>
                    <?php endif; ?>
				</center>
            </div>

        </div>
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
                        console.log("Imprimiu");
                    }
                });
            };

            //GERA O BOLETO E MOSTRA NA PAGINA
            geraBoleto(<?= $fatura->numero_fatura ?>);

            if (faturas == "") {
               faturas += 'faturas[]=<?= $fatura->numero_fatura ?>';
            } else {
               faturas += '&faturas[]=<?= $fatura->numero_fatura ?>';
            }

         </script>

        <div style="page-break-after: always"></div>
    <?php endforeach;?>
<?php endif;?>

</body>
