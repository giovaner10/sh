<style>
    /* .select2-container .select2-selection--single .select2-selection__rendered {
        z-index:1;
    } */
</style>
<h3>Relatório de Contratos</h3>
<hr>
<div class="well well-small">
    <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo"><i class="fa fa-cogs"></i> Configurar Relatório</button>
    <div id="demo" class="collapse">
        <div class="row-fluid">
            <?php echo form_open('relatorios/ajax_contrato', array('id' => 'form-contrato'))?>
            <div class="span3 form-group">
                <label>Cliente:</label>
                <select type="text" name="cliente" id="cliente" class="span12">
                    <option value="" selected disabled> Selecione uma opção</option>
                </select>
            </div>

            <div class="span2 form-group">
                <label>Inicio:</label>
                <input type="text" name="dt_ini" id="dt_ini" class="datepicker span12"
                       placeholder="Data Início" autocomplete="off" id="dp1"
                       value="<?php echo $this->input->post('dt_ini') ?>" required />
            </div>

            <div class="span2 form-group">
                <label>Fim:</label>
                <input type="text" name="dt_fim" id="dt_fim" class="datepicker span12"
                       placeholder="Data Fim" autocomplete="off" id="dp2"
                       value="<?php echo $this->input->post('dt_fim') ?>" required />
            </div>

            <div class="span1 form-group">
                <label>UF:</label>
                <select class="span12" name="uf" id="uf">
                    <option value="" selected> Todos</option>
                    <option value="AC">AC</option>
                    <option value="AL">AL</option>
                    <option value="AP">AP</option>
                    <option value="AM">AM</option>
                    <option value="BA">BA</option>
                    <option value="CE">CE</option>
                    <option value="DF">DF</option>
                    <option value="ES">ES</option>
                    <option value="GO">GO</option>
                    <option value="MA">MA</option>
                    <option value="MS">MS</option>
                    <option value="MT">MT</option>
                    <option value="MG">MG</option>
                    <option value="PA">PA</option>
                    <option value="PB">PB</option>
                    <option value="PR">PR</option>
                    <option value="PE">PE</option>
                    <option value="PI">PI</option>
                    <option value="RJ">RJ</option>
                    <option value="RN">RN</option>
                    <option value="RS">RS</option>
                    <option value="RO">RO</option>
                    <option value="RR">RR</option>
                    <option value="SC">SC</option>
                    <option value="SP">SP</option>
                    <option value="SE">SE</option>
                    <option value="TO">TO</option>
                </select>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span2 form-group">
                <label>Cidade:</label>
                <select type="text" name="cidade" id="cidade" class="span12">
                    <option value="" selected disabled> Selecione uma opção</option>
                </select>
            </div>

            <div class="span2 form-group">
                <label>Status:</label>
                <select class="span12" name="status" id="status">
                    <option value="all"> Todos</option>
                    <option value="0"> Cadastrado</option>
                    <option value="1"> OS/Ativo</option>
                    <option value="3"> Cancelado</option>
                    <option value="4"> Em teste</option>
                    <option value="5"> Bloqueado</option>
                    <option value="6"> Encerrado</option>
                </select>
            </div>

            <div class="span2 form-group">
                <label>Prestadora:</label>
                <select class="span12" name="empresa" id="empresa">
                    <option value="TODOS"> Todos</option>
                    <option value="SIMM2M"> Sim M2m</option>
                    <option value="NORIO"> Norio EPP</option>
                    <option value="TRACKER"> Show Tecnologia</option>
                    <option value="EUA"> Show Technology - EUA</option>
                </select>
            </div>

            <div class="span3"></div>

            <div class="span3">
                <button type="submit" class="btn btn-primary">
                    <i class="icon-list-alt icon-white"></i> Gerar
                </button>
                <button type="button" class="btn btn-primary" id='Limpar'>
                    <i class="icon-leaf icon-white"></i> Limpar
                </button>
                <a href="javascript:window.print();" class="btn"
                   title="Imprimir"> <i class="icon-print"></i>
                </a>
            </div>
            <?php echo form_close()?>

        </div>
    </div>
</div>

<div class="row-fluid">
	<div id="conteudo"></div>
	<?php if ($msg): ?>
<div class="alert alert-error">
	<?php echo $msg ?>
</div>
<?php endif; ?>

<table class="table">
	<tbody>
		<?php if (count($relatorio)): $total_contratos = 0; $total_veiculos = 0; $total_mensalidades = 0;?>
			<?php foreach ($relatorio as $cliente => $contratos) : ?>
				<?php 
					$totais = 
						function($contratos, $tipo) { 
							$total = array('veiculos' => 0, 'contratos' => 0);
							foreach ($contratos as $contrato) 
								{
									$total['contratos'] += ($contrato->valor_mensal * 
											   $contrato->quantidade_veiculos);

									$total['veiculos'] += $contrato->quantidade_veiculos;
									$total['id_cliente'] = $contrato->id_cliente;

								}

							return $total[$tipo];
						}
				?>
				<?php
					$total_contratos += count($contratos); 
					$total_mensalidades += $totais($contratos, 'contratos');
					$total_veiculos += $totais($contratos, 'veiculos');
				?>
				<tr style="background-color: #f5f5f5">
					<td class="span6"><b>Cliente:</b> <?php echo $cliente ?></td>
					<td><b>Contratos:</b> <?php echo count($contratos) ?></td>
					<td><b>Veículos no Contrato:</b> <?php echo $totais($contratos, 'veiculos'); ?> &nbsp&nbsp&nbsp&nbsp&nbsp <b>Veículos no Gestor:</b> <button class="btn btn-mini btn-info" data-id="<?php echo $totais($contratos, 'id_cliente');?>"><i class="fa fa-car"></i></button></td>
					<td><b>Total Mensalidades:</b> R$ <?php echo number_format($totais($contratos, 'contratos'), 2, ',', '.') ?></td>
				</tr>
				<?php if (count($contratos)) : ?>
				<tr>
					<td colspan="4">
						<div class="span12">
							<table class="table">
								<thead>
									<th class="span1">ID</th>
									<th class="span1">Veículos</th>
									<th class="span2">Mens. veículo</th>
									<th class="span2">Valor Total Mensal.</th>
									<th class="span2">Adesão</th>
									<th class="span1">Data Contrato</th>
									<th class="span2">Prazo</th>
									<th class="span1">Status</th>
									<th class="span2">Valor Total Contrato.</th>
									
								</thead>
								<tbody>
								<?php foreach ($contratos as $ctr) : ?>
									<tr>
										<td><?php echo $ctr->id ?></td>
										<td><?php echo $ctr->quantidade_veiculos ?></td>
										<td>R$ <?php echo number_format($ctr->valor_mensal, 2, ',', '.') ?></td>
										<td>R$ <?php echo number_format(
													($ctr->valor_mensal * $ctr->quantidade_veiculos), 2, ',', '.') ?></td>
										<td>R$ <?php echo number_format($ctr->valor_instalacao, 2, ',', '.') ?></td>
										<td><?php echo data_for_humans($ctr->data_contrato) ?></td>
										<td><?php echo $ctr->meses ?> meses</td>
										<td><?php echo show_status_contrato($ctr->status) ?></td>
										<td>R$ <?php echo number_format(
													($ctr->valor_instalacao +
													(($ctr->valor_mensal * $ctr->quantidade_veiculos) *
													  $ctr->meses)), 2, ',', '.') ?></td>
										
									</tr>
								<?php endforeach; ?>
								</tbody>	
							</table>
						</div>
					</td>
				</tr>
				<?php endif; ?>
			<?php endforeach ?>
		<?php endif; ?>
	</tbody>
</table>
<table class="table">
	<tr style="background-color: #f5f5f5">
		<td><b>Total de Contratos:</b> <?php echo $total_contratos; ?></td>
		<td><b>Total de Veículos:</b> <?php echo $total_veiculos; ?></td>
		<td><b>Total de Mensalidades:</b> R$ <?php echo number_format($total_mensalidades, 2, ',', '.') ?></td>
		<td><b>Ticket Médio:</b> R$ <?php $ticket = $total_mensalidades/$total_veiculos; echo number_format($ticket, 2, ',', '.') ?></td>
	</tr>
</table>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript" src="<?php echo base_url('assets/js/modules/relatorio.js') ?>"></script>


<script type="text/javascript">
	$(document).ready(function(){

		var relatorio = new Relatorio();
		relatorio.init();

		$('.btn-mini').on('click', function(e) {
			var id = $(this).data('id');
			var text = $(this);
			var url = "<?php echo base_url('index.php/relatorios/veiculos_gestor');?>";
			$(text).find('i').removeClass('fa fa-car').addClass('fa fa-spinner fa-spin');
			$.post(url, {id: id}, function(resultado) {
				$(text).removeClass('fa fa-spinner fa-spin').html(resultado);
			});
		});

        $("#cliente").select2({
            ajax: {
                url:  `<?= site_url('Relatorios/ajax_cliente')?>`,
                dataType: 'json',
                delay: 2000,
                data: function(params){
                    return {
                        q: params.term,
                    }
                }
            },
            width: '100%',
            placeholder: 'Selecione o Cliente',
            allowClear: false,
            minimumInputLength: 3,
            debug: true
        });

        $("#cidade").select2({
            ajax: {
                url: `<?= site_url('Relatorios/ajax_cidade')?>`,
                dataType: 'json',
                delay: 2000,
                data: function(params){
                    return {
                        q: params.term,
                    }
                }
            },
            width: '100%',
            placeholder: 'Selecione a Cidade',
            allowClear: false,
            minimumInputLength: 3,
            debug: true
        });
        
		$('#Limpar').on('click', function(e) {
            $('#cliente').val("").trigger("change");
            $('#cidade').val("").trigger("change");
            $('#dt_ini').val("")
            $('#dt_fim').val("")
            $('#uf').val("")
            $('#status').val("")
            $('#empresa').val("")
		});

	});

</script>