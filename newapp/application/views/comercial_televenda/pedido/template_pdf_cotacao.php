<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?=base_url('media/img/favicon.png');?>">
    <meta name="msapplication-TileColor" content="#ffffff">	
    <title>PDF Resumo</title>
    <style>
        *{margin:0;padding:0}
    </style>
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

<body style="width: 100%;">
    <div style="width: 100%; padding:0 !important; margin:0 !important;">
        <img src="<?= base_url('media/img/header-omni-pdf.jpg') ?>" style="padding:0 !important; margin:0 !important; border: 0; width: 100%;" height="120" alt="" />
    </div>

    <div style="background-color: #f1f2f2; width: 100%;">
        <div style="width: 90%; margin-left: auto; margin-right: auto;">
            <div style="background-color: #fff; padding: 0px; font-family: sans-serif; font-size: 12px; color: #676767;">&nbsp;</div>
            <div style="background-color: #fff; padding: 10px 32px; font-family: sans-serif; font-size: 18px; color: #676767;">
                <h2 style="color: #336285; font-family: sans-serif; font-size: 18px; line-height: 30px; margin: 0; padding: 0; text-align: center"><strong>Dados Gerais</strong></h2>
            </div>
            <div height="158" style="background-color: #fff; padding: 0px">
                <div width="433" border-="0" style="text-align: center; text-align: center; font-family: arial; font-size: 16px; color: #3F3F3F; border-collapse: collapse">
                    <div id="dadosGerais">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="" style="width: 100%;">
                                    <label style="font-weight: 700;" for="">Proposta Número: </label>    
                                    <span id="numeroCotacao"><?= !empty($resumo["numeroCotacao"]) ? $resumo["numeroCotacao"] : '-'; ?></span>
                                </div>
                                <div class="" style="width: 100%;">
                                    <label style="font-weight: 700;">Preparada para:</label>    
                                    <span id="documento_signatario_software"><?= !empty($resumo["documento_signatario_software"]) ? $resumo["documento_signatario_software"] : '-'; ?></span>
                                    <span id="signatario_software"><?= !empty($resumo["signatario_software"]) ? $resumo["signatario_software"] : '-'; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>       
            <div colspan="5" style="background-color: #f1f2f2; text-align: center; font-family: sans-serif; font-weight: bold; font-size: 14px;">&nbsp;</div>
            <div style="background-color: #fff; padding: 0px; font-family: sans-serif; font-size: 12px; color: #676767;">&nbsp;</div>
            <div style="background-color: #fff; padding: 10px 32px; font-family: sans-serif; font-size: 12px; color: #676767;">
                <h2 style="color: #336285; font-family: sans-serif; font-size: 18px; line-height: 30px; margin: 0; padding: 0; text-align: center"><strong>Hardwares</strong></h2>
            </div>
            
            <div height="158" style="background-color: #fff; padding: 0px;">
                <div width="433" border-="0" style="font-family: arial; font-size: 12px; color: #3F3F3F; border-collapse: collapse">
                    <div id="hardwares">
                        <table class="table table-striped table-bordered table-hover" id="tableHardwaresCotacao" style="width: 95%; border-collapse: collapse; text-align: left; margin-left: auto; margin-right: auto;">
                            <thead style="color: #03A9F4;">
                                <tr class="tableheader">
                                    <th style="border: 2px solid #ddd;padding: 8px 16px;">Descrição de Equipamentos</th>
                                    <th style="border: 2px solid #ddd;padding: 8px 16px;">Valor Unit.</th>
                                    <th style="border: 2px solid #ddd;padding: 8px 16px;">Quant.</th>
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
                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($hardwares['percentualDesconto']) ? $hardwares['percentualDesconto'].'%' : '-') . ' </td>';
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
                    <p>
                        <br>
                    </p>
                </div>
            </div>            

            <div colspan="5" style="background-color: #f1f2f2; text-align: center; font-family: sans-serif; font-weight: bold; font-size: 14px;">&nbsp;</div>
            <div style="background-color: #fff; padding: 0px; font-family: sans-serif; font-size: 12px; color: #676767;">&nbsp;</div>
            <div style="background-color: #fff; padding: 10px 32px; font-family: sans-serif; font-size: 12px; color: #676767;">
                <h2 style="color: #336285; font-family: sans-serif; font-size: 18px; line-height: 30px; margin: 0; padding: 0; text-align: center"><strong>Licenças</strong></h2>
            </div>

            <div height="158" style="background-color: #fff; padding: 0px">
                <div width="433" border-="0" style="text-align: center; font-family: arial; font-size: 12px; color: #3F3F3F; border-collapse: collapse">
                    <div id="licencas">
                        <table class="table table-striped table-bordered table-hover" id="tableLicencasCotacao"  style="width: 95%;border-collapse: collapse; text-align: left; margin-left: auto; margin-right: auto;">
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
                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($licencas['planoSatelital']) ? $licencas['planoSatelital'] : '-') . ' </td>';
                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($licencas['valorUnitario']) ?'R$ ' .   $licencas['valorUnitario']  : '-') . ' </td>';
                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($licencas['quantidade']) ? $licencas['quantidade'] : '-') . ' </td>';
                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($licencas['valorTotal']) ? 'R$ ' .  $licencas['valorTotal'] : '-') . ' </td>';
                                                echo '<td style="border: 2px solid #ddd;padding: 8px 16px;">' . (!empty($licencas['percentualDesconto']) ? $licencas['percentualDesconto'].'%' : '-') . ' </td>';
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
                                    <td style="border: 2px solid #ddd;padding: 8px 16px;">-</td>
                                    <td style="border: 2px solid #ddd;padding: 8px 16px;" id="LicencasValorTotalOportunidade">R$ <?=number_format($licencasTotalValor,2,",",".")?></td>
                                    <td style="border: 2px solid #ddd;padding: 8px 16px;">-</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <p>
                        <br>
                    </p>
                </div>
            </div>
            
            <div colspan="5" style="background-color: #f1f2f2; text-align: center; font-family: sans-serif; font-weight: bold; font-size: 14px;">&nbsp;</div>
            <div style="background-color: #fff; padding: 0px; font-family: sans-serif; font-size: 12px; color: #676767;">&nbsp;</div>

            <div height="158" style="background-color: #fff; padding: 0px">
                <div width="433" border-="0" style="text-align: center; font-family: arial; font-size: 16px; color: #3F3F3F; border-collapse: collapse">
                    <div id="dadosGerais">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center;">
                                <div class="" style="width: 100%;">
                                    <label style="font-weight: 700;" for="">Condição de Pagamento: </label>    
                                    <span id="condicaoPagamento"><?= !empty($resumo["condicaoPagamento"]) ? $resumo["condicaoPagamento"] : '-'; ?></span>
                                </div>
                                <div class="" style="width: 100%;">
                                    <label style="font-weight: 700;">Tipo de Pagamento:</label>    
                                    <span id="tipoPagamento"><?= !empty($resumo["tipoPagamento"]) ? $resumo["tipoPagamento"] : '-'; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <p>
                        <br>
                    </p>
                </div>
            </div>

            <div colspan="5" style="background-color: #f1f2f2; text-align: center; font-family: sans-serif; font-weight: bold; font-size: 14px;">&nbsp;</div>
        </div>
    </div>  
   
</body>

</html>