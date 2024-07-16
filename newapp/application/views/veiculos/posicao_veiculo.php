

<div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div>


<?php if ($seriais):?>

    <?php if ($tamanho_seriais == 1):?>

        <?php if ($posicao):?>
            <div class="row-fluid">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Serial</th>
                        <th>Ignição</th>
                        <th>GPS</th>
                        <th>GPRS</th>
                        <th>Vel</th>
                        <th>Atualização</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><h4><span class="label label-default"><?php echo $placa ?></span></h4></td>
                        <td><h4><span class="label label-default"><?php echo $serial ?></span></h4></td>
                        <td>
                            <?php if ($ignicao == 1):?>
                                <h4><span title="Ignição ligada" class="label label-success"><i class="fa fa-power-off fa-lg"></i></span></h4>
                            <?php else:?>
                                <h4><span title="Ignição desligada" class="label label-danger"><i class="fa fa-power-off fa-lg"></i></span></h4>
                            <?php endif;?>
                        </td>
                        <td>
                            <?php if ($gps == 1):?>
                                <h4><span title="GPS com sinal" class="label label-success"><i class="fa fa-map-marker fa-lg"></i></span></h4>
                            <?php else:?>
                                <h4><span title="GPS sem sinal" class="label label-danger"><i class="fa fa-ban fa-lg"></i></span></h4>
                            <?php endif;?>
                        </td>
                        <td>
                            <?php if ($gprs == 1):?>
                                <h4><span title="GPRS com sinal" class="label label-success"><i class="fa fa-signal"></i></span></h4>
                            <?php else:?>
                                <h4><span title="GPRS sem sinal " class="label label-danger"><i class="fa fa-ban fa-lg"></i></span></h4>
                            <?php endif;?>
                        </td>
                        <td><h4><span class="label label-default"><?php echo $velocidade ?> Km</span></h4></td>
                        <td><?php echo dh_for_humans($data) ?></td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>
                            <h4>Ref.: <span class="label label-info"><?php echo $endereco ?></span></h4>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        <?php else:?>

            <div class="alert alert-block">
                <button id="atencao" type="button" class="close" data-dismiss="alert">&times;</button>
                <h4>Atenção!</h4>
                Veículo sem informação de posição!
            </div>

        <?php endif;?>

    <?php else:?>

        <div class="alert alert-block">
            <button id="atencao" type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Atenção!</h4>
            A placa <b><?php echo $placa ?></b> está relacionada a mais de <b>1</b> serial: <b><?php foreach ($seriais as $ser) { echo $ser->serial." "; } ?></b>
        </div>

    <?php endif;?>

<?php else:?>

    <div class="alert alert-block">
        <button id="atencao" type="button" class="close" data-dismiss="alert">&times;</button>
        <h4>Atenção!</h4>
        Veículo não vinculado a um serial!
    </div>

<?php endif;?>

<!-- <div class="row-fluid">
	<div class="form-actions">
		<a class="btn fecharPosicao">Fechar</a>
	</div>
</div> -->

</form>
