<?php if($erro):?>
<div class="alert">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?= $erro ?>
</div>
<?php endif;?>

<h3>Relatório de Tickets</h3>
<hr>

<div class="well well-small">
	<?php echo form_open('')?>
	<div class="input-group pull-left">
        <span id="label_tipo_data" class="input-group-addon" style="margin-left: 10px;">Tipo de Pesquisa</span>
		<select name="tipo_data" id="tipo_data" style="margin-bottom: 5px;">
            <option value="0" <?= $this->input->post('tipo_data') == '0' ? 'selected' : '' ?>>Data Inicial e Final</option>
            <option value="1" <?= $this->input->post('tipo_data') == '1' ? 'selected' : '' ?>>Mês e Ano</option>
        </select>
            
        <span id="label_dp1" class="input-group-addon" style="margin-left: 10px;">Data Inicial <i class="fa fa-calendar" style="font-size: 20px;"></i> </span>
		<input type="date" name="data_ini" id="dp1" style="margin-bottom: 5px;" required
			value="<?= ($this->input->post('tipo_data') == '0' && $this->input->post('data_ini')) ? $this->input->post('data_ini') : '' ?>" />

        <span id="label_dp2" class="input-group-addon" style="margin-left: 10px;">Data Final <i class="fa fa-calendar" style="font-size: 20px;"></i> </span>
		<input type="date" name="data_fim" id="dp2" style="margin-bottom: 5px;" required
			value="<?= ($this->input->post('tipo_data') == '0' && $this->input->post('data_fim')) ? $this->input->post('data_fim') : '' ?>" />

        <span id="label_mes_ano" class="input-group-addon" style="margin-left: 10px;">Mês e Ano <i class="fa fa-calendar" style="font-size: 20px;"></i> </span>
		<input type="text" name="mes_ano" id="mes_ano" style="margin-bottom: 5px;" required
			value="<?= ($this->input->post('tipo_data') == '1' && $this->input->post('mes_ano')) ? $this->input->post('mes_ano') : '' ?>" />
	</div>
	<div class="pull-right" style="margin-left: 20px;">
		<button type="submit" class="btn">
			<i class="icon-list-alt"></i> Gerar
		</button>
	</div>
	<?php echo form_close()?>
	<div class="clearfix"></div>
</div>

<?php if ($this->input->post()): ?>
<div>
    <span><b>Total abertos:</b> <?= $total ?></span>
    <span style="margin-left: 10px;"><b>Total concluidos:</b> <?= $concluidos ?></span>
</div></br>

<table class="table">
	<thead>
		<th class="span1">ID</th>
		<th class="span4">Cliente</th>
		<th class="span2">Data Abertura</th>
		<th class="span2">Assunto</th>
        <th class="span2">Departamento</th>
		<th class="span2">Última Int.</th>
		<th class="span2">Status</th>
		<th class="span1">Visualizar</th>
	</thead>
	<tbody>
    <?php if ($relatorio): ?>
        <?php foreach ($relatorio as $rel): ?>
        <tr>
            <td><?= $rel['id'] ?></td>
            <td><?= $rel['cliente'] ?></td>
            <td><?= dh_for_humans($rel['data_abertura']) ?></td>
            <td><?= $rel['assunto'] ?></td>
            <td><?= $rel['departamento'] ?></td>
            <td><?= dh_for_humans($rel['ultima_interacao']) ?></td>
            <td>
                <?php
                if ($rel['status'] == 3)
                    echo '<span class="label label-success">Concluido</span>';
                elseif ($rel['status'] == 2)
                    echo '<span class="label label-default" style="background-color: #0b51c5">Respondido</span>';
                elseif ($rel['status'] == 1)
                    echo '<span class="label label-warning">Aguardando Resp.</span>';
                ?>
            </td>
            <td><a class="btn btn-primary" href="<?= site_url('webdesk/ticket/'.$rel['id']) ?>" target="_blank"><i class="fa fa-eye"></i></a></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7" class="dt-center">Nenhum Ticket encontrado.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<?php endif; ?>
<script src="<?php echo base_url('assets/js/jquery.mask.js');?>"></script>
<script>
    $('#mes_ano').mask('00/0000', {placeholder: 'mm/aaaa'});

    $(document).ready(function (){
        let tipo = $('#tipo_data').val();
        mudarPesquisa(tipo);
    });

    $('#tipo_data').on('change', function (e) {
        e.preventDefault();
        let tipo = $(this).val();
        $('#dp1').val('');
        $('#dp2').val('');
        $('#mes_ano').val('');

        mudarPesquisa(tipo);
    });

    function mudarPesquisa(tipo) {
        if (tipo == 1) {
            $('#dp1').prop('disabled', true);
            $('#dp1').hide();
            $('#label_dp1').hide();
            $('#dp2').prop('disabled', true);
            $('#dp2').hide();
            $('#label_dp2').hide();
            $('#mes_ano').prop('disabled', false);
            $('#mes_ano').show();
            $('#label_mes_ano').show();
        } else {
            $('#dp1').prop('disabled', false);
            $('#dp1').show();
            $('#label_dp1').show();
            $('#dp2').prop('disabled', false);
            $('#dp2').show();
            $('#label_dp2').show();
            $('#mes_ano').prop('disabled', true);
            $('#mes_ano').hide();
            $('#label_mes_ano').hide();
        }
    }
</script>