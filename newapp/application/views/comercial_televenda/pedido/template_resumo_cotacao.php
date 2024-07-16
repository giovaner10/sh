<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?=base_url('media/img/favicon.png');?>">
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <meta name="msapplication-TileColor" content="#ffffff">	
</head>
<?php
    $servicosTotalValor = 0; 
    $licencasTotalValor = 0; 
    $hardwaresTotalValor = 0;
    
    if (isset($resumo["servicos"])){
        $servicosTotalValor = $servicosTotalValor = array_reduce($resumo["servicos"], function ($total, $servicos) {
            $total += str_replace('.', '', $servicos['valorTotal']);
            return $total;
        }, 0.0);
    }
    
    if (isset($resumo["hardwares"])){
        $hardwaresTotalValor = $hardwaresTotalValor = array_reduce($resumo["hardwares"], function ($total, $hardwares) {
            $total += str_replace('.', '', $hardwares['valorTotal']);
            return $total;
        }, 0.0);
    }

    if (isset($resumo["licencas"])){
        $licencasTotalValor = $licencasTotalValor = array_reduce($resumo["licencas"], function ($total, $licencas) {
            $total += str_replace('.', '', $licencas['valorTotal']);
            return $total;
        }, 0.0);
    }

    $ValorTotal =  $servicosTotalValor + $hardwaresTotalValor + $licencasTotalValor;
?>

<body>
    <!-- <h2 style="color: #336285; font-family: sans-serif; font-size: 18px; line-height: 30px; margin: 0; padding: 0; text-align: center"><strong><?= $servicosTotalValor; ?></strong></h2>
    <h2 style="color: #336285; font-family: sans-serif; font-size: 18px; line-height: 30px; margin: 0; padding: 0; text-align: center"><strong><?= $hardwaresTotalValor; ?></strong></h2>
    <h2 style="color: #336285; font-family: sans-serif; font-size: 18px; line-height: 30px; margin: 0; padding: 0; text-align: center"><strong><?= $licencasTotalValor; ?></strong></h2> -->
    <center>
        <table cellpadding="0" cellspacing="0" border="0" width="640">
            <tr>
                <td valign="top">
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; margin: 0px;">
                        <tr>
                            <td><img src="http://app.omnilink.com.br/mkt/2018/base_externa/header-omnilink.jpg" width="257" height="105" alt="" /></td>
                            <td><img src="http://app.omnilink.com.br/mkt/2018/base_externa/header-wave.jpg" width="392" height="105" alt="" /></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top" height="400" style="background-color: #f1f2f2;">
                    <br>
                    <table cellspacing="0" cellpadding="0" style="width: 90%; margin-left: auto; margin-right: auto;">

                        <!-- Dados Gerais -->
                        <tr>
                            <td style="background-color: #fff; padding: 0px; font-family: sans-serif; font-size: 12px; color: #676767;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="background-color: #fff; padding: 10px 32px; font-family: sans-serif; font-size: 12px; color: #676767;">
                                <h2 style="color: #336285; font-family: sans-serif; font-size: 18px; line-height: 30px; margin: 0; padding: 0; text-align: center"><strong>Dados Gerais</strong></h2>
                                
                                <h2 style="color: #336285; font-family: sans-serif; font-size: 15px; line-height: 30px; margin: 0; padding: 0; text-align: center">
                                    <?= 'Valor Total: R$ ' . number_format(str_replace('.', '', $ValorTotal) ,2,",","."); ?>
                                </h2>
                                
                                <p align="center">
                                    <br>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td height="158" style="background-color: #fff; padding: 0px">
                                <div width="433" border-="0" align="center" style="font-family: arial; font-size: 12px; color: #3F3F3F; border-collapse: collapse">
                                    <div id="dadosGerais">
                                        <div class="row">
                                            <div class="col-md-12" style="display: grid; grid-template-columns: 1fr 1fr;grid-gap: 20px;">
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;" for="">Número da cotação:</label>    
                                                    <p id="numeroCotacao"><?= !empty($resumo["numeroCotacao"]) ? $resumo["numeroCotacao"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Versão da cotação:</label>    
                                                    <p id="versaoCotacao"><?= !empty($resumo["versaoCotacao"]) ? $resumo["versaoCotacao"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Cliente:</label>    
                                                    <p id="clienteCotacao"><?= !empty($resumo["cliente"]) ? $resumo["cliente"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Início da vigência:</label>    
                                                    <p id="inicioVigenciaCotacao"><?= !empty($resumo["inicioVigencia"]) ? $resumo["inicioVigencia"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Término da vigência:</label>    
                                                    <p id="terminoVigenciaCotacao"><?= !empty($resumo["terminoVigencia"]) ? $resumo["terminoVigencia"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Plataforma:</label>    
                                                    <p id="plataformaCotacao"><?= !empty($resumo["plataforma"]) ? $resumo["plataforma"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Cenário da venda:</label>    
                                                    <p id="cenarioVendaCotacao"><?= !empty($resumo["cenarioVenda"]) ? $resumo["cenarioVenda"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Tipo de pagamento:</label>    
                                                    <p id="tipoPagamentoCotacao"><?= !empty($resumo["tipoPagamento"]) ? $resumo["tipoPagamento"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Condição de pagamento:</label>    
                                                    <p id="condicaoPagamentoCotacao"><?= !empty($resumo["condicaoPagamento"]) ? $resumo["condicaoPagamento"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Modalidade de venda:</label>    
                                                    <p id="modalidadeVendaCotacao"><?= !empty($resumo["modalidadeVenda"]) ? $resumo["modalidadeVenda"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Canal de venda:</label>    
                                                    <p id="canalVendaCotacao"><?= !empty($resumo["canalVenda"]) ? $resumo["canalVenda"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Tempo de contrato:</label>    
                                                    <p id="tempoContratoCotacao"><?= !empty($resumo["tempoContrato"]) ? $resumo["tempoContrato"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Tipo de frete:</label>    
                                                    <p id="tipoFreteCotacao"><?= !empty($resumo["tipoFrete"]) ? $resumo["tipoFrete"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Signatário do software:</label>    
                                                    <p id="signatarioSoftwareCotacao"><?= !empty($resumo["signatario_software"]) ? $resumo["signatario_software"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Email do signatário do software:</label>    
                                                    <p id="emailSignatarioSoftwareCotacao"><?= !empty($resumo["email_signatario_software"]) ? $resumo["email_signatario_software"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Documento do signatário do software:</label>    
                                                    <p id="documentoSignatarioSoftwareCotacao"><?= !empty($resumo["documento_signatario_software"]) ? $resumo["documento_signatario_software"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Signatário do hardware:</label>    
                                                    <p id="signatarioHardwareCotacao"><?= !empty($resumo["signatario_hardware"]) ? $resumo["signatario_hardware"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Email do signatário do hardware:</label>    
                                                    <p id="emailSignatarioHardwareCotacao"><?= !empty($resumo["email_signatario_hardware"]) ? $resumo["email_signatario_hardware"] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Documento do signatário do hardware:</label>    
                                                    <p id="documentoSignatarioHardwareCotacao"><?= !empty($resumo["documento_signatario_hardware"]) ? $resumo["documento_signatario_hardware"] : '-'; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <p align="center">
                                        <br>
                                    </p>
                                </div>
                            </td>
                        </tr>

                        <!-- Composição -->
                        <tr>
                            <td colspan="5" bgcolor="#DDDDDD" style="text-align: center; font; font-weight: bold; font-size: 14px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="background-color: #fff; padding: 0px; font-family: sans-serif; font-size: 12px; color: #676767;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="background-color: #fff; padding: 10px 32px; font-family: sans-serif; font-size: 12px; color: #676767;">
                                <h2 style="color: #336285; font-family: sans-serif; font-size: 18px; line-height: 30px; margin: 0; padding: 0; text-align: center"><strong>Composição</strong></h2>
                                <p align="center">
                                    <br>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td height="158" style="background-color: #fff; padding: 0px">
                                <div width="433" border-="0" align="center" style="font-family: arial; font-size: 12px; color: #3F3F3F; border-collapse: collapse">
                                    <div id="composicao">
                                        <div class="row">
                                            <div class="col-md-12"  style="display: grid; grid-template-columns: 1fr 1fr;grid-gap: 20px;">
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Sistema:</label>    
                                                    <p id="sistemaCotacao"><?= !empty($resumo['composicao']['sistema']) ? $resumo['composicao']['sistema'] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Tipo de comunicação:</label>    
                                                    <p id="tipoComunicacaoCotacao"><?= !empty($resumo['composicao']['tipoComunicacao']) ? $resumo['composicao']['tipoComunicacao'] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Família produto:</label>    
                                                    <p id="familiaProdutoCotacao"><?= !empty($resumo['composicao']['familiaProduto']) ? $resumo['composicao']['familiaProduto'] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Produto:</label>    
                                                    <p id="produtoCotacao"><?= !empty($resumo['composicao']['produto']) ? $resumo['composicao']['produto'] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Categoria:</label>    
                                                    <p id="categoriaCotacao"><?= !empty($resumo['composicao']['categoria']) ? $resumo['composicao']['categoria'] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Tipo de veículo:</label>    
                                                    <p id="tipoVeiculoCotacao"><?= !empty($resumo['composicao']['tipoVeiculo']) ? $resumo['composicao']['tipoVeiculo'] : '-'; ?></p>
                                                </div>
                                                <div class="" style="width: 100%;">
                                                    <label style="font-weight: 700;">Quantidade:</label>    
                                                    <p id="quantidadeCotacao"><?= !empty($resumo['composicao']['quantidade']) ? $resumo['composicao']['quantidade'] : '-'; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p align="center">
                                        <br>
                                    </p>
                                </div>
                            </td>
                        </tr>

                        <!-- Hardwares -->
                        <tr>
                            <td colspan="5" bgcolor="#DDDDDD" style="text-align: center; font; font-weight: bold; font-size: 14px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="background-color: #fff; padding: 0px; font-family: sans-serif; font-size: 12px; color: #676767;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="background-color: #fff; padding: 10px 32px; font-family: sans-serif; font-size: 12px; color: #676767;">
                                <h2 style="color: #336285; font-family: sans-serif; font-size: 18px; line-height: 30px; margin: 0; padding: 0; text-align: center"><strong>Hardwares</strong></h2>
                            </td>
                        </tr>
                        <tr>
                            <td height="158" style="background-color: #fff; padding: 0px;">
                                <div width="433" border-="0" align="center" style="font-family: arial; font-size: 12px; color: #3F3F3F; border-collapse: collapse">
                                    <div id="hardwares">
                                        <table class="table table-striped table-bordered table-hover" id="tableHardwaresCotacao" style="width: 95%;border-collapse: collapse; text-align: left;">
                                            <thead style="color: #03A9F4;">
                                                <tr class="tableheader">
                                                    <th style="border: 2px solid #ddd;padding: 8px 16px;">Produto</th>
                                                    <th style="border: 2px solid #ddd;padding: 8px 16px;">Valor Unitário</th>
                                                    <th style="border: 2px solid #ddd;padding: 8px 16px;">Quantidade</th>
                                                    <th style="border: 2px solid #ddd;padding: 8px 16px;">Valor Total</th>
                                                    <th style="border: 2px solid #ddd;padding: 8px 16px;">% de Desconto</th>
                                                </tr>
                                            </thead>
                                            <tbody style="color: #777;">
                                                <?php
                                                    if (!empty($resumo["hardwares"])) {
                                                        foreach ($resumo["hardwares"] as $hardwares ) {
                                                            echo '<tr>';                    
                                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($hardwares['produto']) ? $hardwares['produto'] : '-') . ' </td>';
                                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($hardwares['valorUnitario']) ? 'R$ ' .  $hardwares['valorUnitario'] : '-') . ' </td>';
                                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($hardwares['quantidade']) ? $hardwares['quantidade'] : '-') . ' </td>';
                                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($hardwares['valorTotal']) ? 'R$ ' .  $hardwares['valorTotal'] : '-') . ' </td>';
                                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($hardwares['percentualDesconto']) ? $hardwares['percentualDesconto'] : '-') . ' </td>';
                                                            echo ' </tr>';
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                            <tfoot style="color: #777;">
                                                <tr>
                                                    <td style="border: 2px solid #ddd;padding: 8px 16px;">Total</td>
                                                    <td style="border: 2px solid #ddd;padding: 8px 16px;">-</td>
                                                    <td style="border: 2px solid #ddd;padding: 8px 16px;">-</td>
                                                    <td style="border: 2px solid #ddd;padding: 8px 16px;" id="HardwaresValorTotalOportunidade">R$ <?=number_format($hardwaresTotalValor,2,",",".")?></td>
                                                    <td style="border: 2px solid #ddd;padding: 8px 16px;">-</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <p align="center">
                                        <br>
                                    </p>
                                </div>
                            </td>
                        </tr>

                        <!-- Lincenças -->
                        <tr>
                            <td colspan="5" bgcolor="#DDDDDD" style="text-align: center; font; font-weight: bold; font-size: 14px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="background-color: #fff; padding: 0px; font-family: sans-serif; font-size: 12px; color: #676767;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="background-color: #fff; padding: 10px 32px; font-family: sans-serif; font-size: 12px; color: #676767;">
                                <h2 style="color: #336285; font-family: sans-serif; font-size: 18px; line-height: 30px; margin: 0; padding: 0; text-align: center"><strong>Lincenças</strong></h2>
                            </td>
                        </tr>
                        <tr>
                            <td height="158" style="background-color: #fff; padding: 0px">
                                <div width="433" border-="0" align="center" style="font-family: arial; font-size: 12px; color: #3F3F3F; border-collapse: collapse">
                                    <div id="licencas">
                                        <table class="table table-striped table-bordered table-hover" id="tableLicencasCotacao"  style="width: 95%;border-collapse: collapse; text-align: left;">
                                            <thead style="color: #03A9F4;">
                                                <tr class="tableheader">
                                                    <th style="border: 2px solid #ddd;padding: 8px 16px;">Produto</th>
                                                    <th style="border: 2px solid #ddd;padding: 8px 16px;">Valor Unitário</th>
                                                    <th style="border: 2px solid #ddd;padding: 8px 16px;">Quantidade</th>
                                                    <th style="border: 2px solid #ddd;padding: 8px 16px;">Valor Total</th>
                                                    <th style="border: 2px solid #ddd;padding: 8px 16px;">Plano Satelital</th>
                                                    <th style="border: 2px solid #ddd;padding: 8px 16px;">% de Desconto</th>
                                                </tr>
                                            </thead>
                                            <tbody style="color: #777;">
                                                <?php
                                                    if (!empty($resumo["licencas"])) {
                                                        foreach ($resumo["licencas"] as $licencas ) {
                                                            echo '<tr>';                    
                                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($licencas['produto']) ? $licencas['produto'] : '-') . ' </td>';
                                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($licencas['valorUnitario']) ?'R$ ' .   $licencas['valorUnitario']  : '-') . ' </td>';
                                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($licencas['quantidade']) ? $licencas['quantidade'] : '-') . ' </td>';
                                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($licencas['valorTotal']) ? 'R$ ' .  $licencas['valorTotal'] : '-') . ' </td>';
                                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($licencas['percentualDesconto']) ? $licencas['percentualDesconto'] : '-') . ' </td>';
                                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($licencas['planoSatelital']) ? $licencas['planoSatelital'] : '-') . ' </td>';
                                                            echo ' </tr>';
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                            <tfoot style="color: #777;">
                                                <tr>
                                                    <td style="border: 2px solid #ddd;padding: 8px 16px;">Total</td>
                                                    <td style="border: 2px solid #ddd;padding: 8px 16px;">-</td>
                                                    <td style="border: 2px solid #ddd;padding: 8px 16px;">-</td>
                                                    <td style="border: 2px solid #ddd;padding: 8px 16px;" id="LicencasValorTotalOportunidade">R$ <?=number_format($licencasTotalValor,2,",",".")?></td>
                                                    <td style="border: 2px solid #ddd;padding: 8px 16px;">-</td>
                                                    <td style="border: 2px solid #ddd;padding: 8px 16px;">-</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <p align="center">
                                        <br>
                                    </p>
                                </div>
                            </td>
                        </tr>

                        <!-- Serviços -->
                        <tr>
                            <td colspan="5" bgcolor="#DDDDDD" style="text-align: center; font; font-weight: bold; font-size: 14px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="background-color: #fff; padding: 0px; font-family: sans-serif; font-size: 12px; color: #676767;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="background-color: #fff; padding: 10px 32px; font-family: sans-serif; font-size: 12px; color: #676767;">
                                <h2 style="color: #336285; font-family: sans-serif; font-size: 18px; line-height: 30px; margin: 0; padding: 0; text-align: center"><strong>Serviços</strong></h2>
                            </td>
                        </tr>
                        <tr>
                            <td height="158" style="background-color: #fff; padding: 0px">
                                <div width="433" border-="0" align="center" style="font-family: arial; font-size: 12px; color: #3F3F3F; border-collapse: collapse">
                                    <div id="servicos">
                                        <table class="table table-striped table-bordered table-hover" id="tableServicosCotacao"  style="width: 95%;border-collapse: collapse; text-align: left;">
                                            <thead style="color: #03A9F4;">
                                                <tr class="tableheader">
                                                    <th style="border: 2px solid #ddd;padding: 10px 18px;">Produto</th>
                                                    <th style="border: 2px solid #ddd;padding: 10px 18px;">Valor Unitário</th>
                                                    <th style="border: 2px solid #ddd;padding: 10px 18px;">Quantidade</th>
                                                    <th style="border: 2px solid #ddd;padding: 10px 18px;">Valor Total</th>
                                                    <th style="border: 2px solid #ddd;padding: 10px 18px;">% de Desconto</th>
                                                </tr>
                                            </thead>
                                            <tbody style="color: #777;">
                                                <?php
                                                    if (!empty($resumo["servicos"])) {
                                                        foreach ($resumo["servicos"] as $servicos ) {
                                                            echo '<tr>';                    
                                                                echo '<td style="border: 2px solid #ddd;padding: 10px 18px;">' . (!empty($servicos['produto']) ? $servicos['produto'] : '-') . ' </td>';
                                                                echo '<td style="border: 2px solid #ddd;padding: 10px 18px;">' . (!empty($servicos['valorUnitario']) ? 'R$ ' .$servicos['valorUnitario']  : '-') . ' </td>';
                                                                echo '<td style="border: 2px solid #ddd;padding: 10px 18px;">' . (!empty($servicos['quantidade']) ? $servicos['quantidade'] : '-') . ' </td>';
                                                                echo '<td style="border: 2px solid #ddd;padding: 10px 18px;">' . (!empty($servicos['valorTotal']) ? 'R$ ' .  $servicos['valorTotal']: '-') . ' </td>';
                                                                echo '<td style="border: 2px solid #ddd;padding: 10px 18px;">' . (!empty($servicos['percentualDesconto']) ? $servicos['percentualDesconto'] : '-') . ' </td>';
                                                            echo ' </tr>';
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                            <tfoot style="color: #777;">
                                                <tr>
                                                    <td style="border: 2px solid #ddd;padding: 10px 18px;">Total</td>
                                                    <td style="border: 2px solid #ddd;padding: 10px 18px;">-</td>
                                                    <td style="border: 2px solid #ddd;padding: 10px 18px;">-</td>
                                                    <td style="border: 2px solid #ddd;padding: 10px 18px;" id="ServicoValorTotalOportunidade">R$ <?=number_format($servicosTotalValor,2,",",".")?></td>
                                                    <td style="border: 2px solid #ddd;padding: 10px 18px;">-</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <p align="center">
                                        <br>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td style="background-color: #2ea2d7">
                                <img style="display: block" src="http://app.omnilink.com.br/mkt/_2017/omniturbo/01/images/footer-logo.jpg" alt="Omnilink" />
                            </td>
                            <td>
                                <a href="http://omnilink.com.br/" title="">
                                    <img style="display: block" src="http://app.omnilink.com.br/mkt/_2017/omniturbo/01/images/footer-site.jpg" alt="Acesse o site: www.omnilink.com.br" />
                                </a>
                            </td>
                            <td>
                                <img style="display: block" src="http://app.omnilink.com.br/mkt/_2017/omniturbo/01/images/footer-call.jpg" alt="Acesse o facebook" />
                            </td>
                            <td>
                                <a href="https://www.facebook.com/omnilinktecnologia" title="">
                                    <img style="display: block" src="http://app.omnilink.com.br/mkt/_2017/omniturbo/01/images/footer-fb.jpg" alt="Acesse o facebook" />
                                </a>
                            </td>
                            <td>
                                <a href="https://www.linkedin.com/company/omnilinkbr" title="">
                                    <img style="display: block" src="http://app.omnilink.com.br/mkt/_2017/omniturbo/01/images/footer-lk.jpg" alt="Acesse o linkedin" />
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
    </center>
</body>

