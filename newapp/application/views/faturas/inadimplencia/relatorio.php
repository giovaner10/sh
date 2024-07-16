
<style>
    @media print
    {
        a
        {
            display: none !important;
        }
    }
</style>

    <div class="container text-center">
        <div class="span12">
            <div class="span3 text-left" style="padding-top: 10px;">
                <img src="<?=base_url();?>assets/inadimplencia/img/logo.png" alt="">
            </div>
            <div class="span3"  style="padding-top: 30px;">
                <a class="btn btn-warning" href="<?=base_url();?>index.php/faturas/inadimplencia/">Dashboard de Inadimplência</a>&nbsp;
                <a class="btn btn-danger" name="imprimir" onclick="window.print();">Imprimir</a>
            </div>
        </div>
        <div>
            <div class="span12">
                <h4 style="color: #1a237e;" class="text-right"><strong>Empresa:</strong> Show Prestadora de Serviços do Brasil LTDA - ME</h4>
                <h4 style="color: #1a237e;" class="text-right"><strong>CNPJ:</strong> 09.338.999.0001/58</h4>
                <h4 style="color: #1a237e;" class="text-right"><strong>Período:</strong> <?=$inicio ?> <strong>até</strong> <?=$fim?></h4>
            </div>
        </div>
        <div class="span12">
            <h1 style="color: #0d47a1;"><strong>Relatorio - Monitoramento de Inadimplências</strong></h1><br />
        </div>

            <?php
                $total_calamp = 0;
                $total_maxtrack = 0;
                $total_continental = 0;

                $total_linhas = count($resultado);
                if ($total_linhas <= 0) {
            ?>
                <div style="color:#0d47a1;">
                    <h3>Não há ocorrencias de Inadimplência</h3>
                </div>
            <?php
                }else {

                foreach($resultado as $valor){
                    $total_calamp += $valor->mes_atual;
                    $total_maxtrack += $valor->mes_anterior;
                    $total_continental += $valor->doze_meses;
            ?>

                <table class="table table-striped table-bordered table-hover table-responsive table-condensed" id="tbExport">
                <thead>
                    <tr style="background-color: #01579b; color: #FFFFFF; font-size: 14px;">
                        <th class="text-center">Código</th>
                        <th class="text-center">Data / Hora</th>
                        <th class="text-center">Mês Atual</th>
                        <th class="text-center">Mês Anterior</th>
                        <th class="text-center">Ano</th>
                    </tr>
                </thead>
           
                <tbody>

                <tr style="font-style: italic;">
                    <td><?=$valor->id?></td>
                    <td><?=strftime( '%d/%m/%Y às %H:%M:%S', strtotime($valor->data))?></td>
                    <td><?=number_format($valor->mes_atual,2,',','') . ' <strong>%</strong>';?></td>
                    <td><?=number_format($valor->mes_anterior,2,',','') . ' <strong>%</strong>';?></td>
                    <td><?=number_format($valor->doze_meses,2,',','') . ' <strong>%</strong>';?></td>
                </tr>

                <?php
                    }

                    $media_calamp = $total_calamp / $total_linhas;
                    $media_maxtrack = $total_maxtrack / $total_linhas;
                    $media_continental = $total_continental / $total_linhas;
                ?>

            </tbody>
        </table>
        <br />
        <table class="table table-striped table-bordered table-hover table-responsive table-condensed">
            <thead>
                <tr style="background-color: #01579b; color: #FFFFFF; font-size: 14px;">
                    <th class="text-center" colspan="8">Estatística Geral </th>
                </tr>
                <tr>
                    <th class="text-center" colspan="2">Dados</th>
                    <th class="text-center">Mês Atual</th>
                    <th class="text-center">Mês Anterior</th>
                    <th class="text-center">Ano</th>
                </tr>
            </thead>
            <tbody>
                <tr style="font-style: italic;">
                    <td colspan="2">Total</td>
                    <td><?=round($total_calamp, 2) . ' <strong>%</strong>';?></td>
                    <td><?=round($total_maxtrack, 2) . ' <strong>%</strong>';?></td>
                    <td><?=round($total_continental, 2) . ' <strong>%</strong>';?></td>
                </tr>
                <tr style="font-style: italic;">
                    <td colspan="2">Média</td>
                    <td><?=round($media_calamp, 2) . ' <strong>%</strong>';?></td>
                    <td><?=round($media_maxtrack, 2) . ' <strong>%</strong>';?></td>
                    <td><?=round($media_continental, 2) . ' <strong>%</strong>';?></td>
                </tr>
            </tbody>
        </table>
    <?php } ?>
</div>
