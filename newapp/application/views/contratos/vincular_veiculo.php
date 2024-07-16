<style>
.calendario{
	z-index: 1000000;
}
</style>
<?php
	if ($seriais) {
		$tamanho = count($seriais);
		if ( $tamanho == 1 ) {
			foreach ($seriais as $ser){
				$serial_1 = $ser->serial;
			}
		} else
			$serial_1 = 0;
	} else
		$serial_1 = 0;
 ?>

 <div class="pull-right btnusuarios">
	<a id="addusuario" class="btn btn-mini btn-success rmvusuario" title="Adicionar usuário"><i class="icon-plus-sign icon-white"></i> </a>
	<a id="rmvusuario" class="btn btn-mini btn-warning addusuario" title="Remover usuário"><i class="icon-minus-sign icon-white"></i> </a>
</div>


<!-- <div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div> -->
<h4>Placa - <?php echo $placa ?></h4>

<div class="informacao"></div>

<div class="rmvusuario">
	<form method="post" name="formUsuario" id="formUsuario" action="<?php echo site_url('veiculos/remover_usuario_veiculo/'.$placa)?>">
		<?php if ($usuarios):?>
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<label class="control-label">Usuário:</label>
						<select name="usuario" id="usuario">
							<?php foreach ($usuarios as $usuario): ?>
								<option value="<?php echo $usuario->code ?>"><?php echo $usuario->usuario ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
			</div>
		<?php else:?>
			<div class="alert alert-info">
				<button type="button" class="close" >&times;</button>
				<h4>Usuários não encontrados!</h4>
			</div>
		<?php endif;?>
		<div class="row-fluid">
			<div class="form-actions">
				<button type="submit" id="enviar" value="Remover" class="btn btn-primary btn" >Remover</button>
				<!-- <a onclick="fecharModal('#modal_serial');" class="btn fechar">Fechar</a> -->
			</div>
		</div>
	</form>
</div>

<div class="addusuario">
	<div class="vincular">
		<form method="post" name="vincularVeiculo" id="vincularVeiculo"  autocomplete="off" enctype="multipart/form-data" action="<?php echo site_url('contratos/validar_vinculo_new/'.$id_placa.'/'.$placa.'/'.$id_cliente.'/'.$id_contrato)?>">
			<input type="hidden" name="ser" value="<?php echo $serial_1 ?>"/>
			<?php if ($seriais):?>
				<?php if ($tamanho == 1):?>
					<?php if ($usuarios):?>
						<input type="hidden" name="status" value="edicao"/>
							<div class="row-fluid">
								<div class="span4">
									<div class="control-group">
										Veículo:
										<input type="text" name="veiculo" minlength="4" placeholder="Veículo" value="<?= $dados_veic->veiculo; ?>" required />
									</div>
								</div>
								<div class="span4">
									<div class="control-group">
										Táxi:
										<select name="taxi" required>
											<option value="0">Não</option>
											<option value="1">Sim</option>
										</select>
									</div>
								</div>
								<div class="span4">
									<div class="control-group">
										Prefixo:
										<input type="text" name="prefixo" placeholder="Prefixo" value="<?= $dados_veic->prefixo_veiculo; ?>" />
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span4">
									<div class="control-group">
										Serial:
										<input type="text" name="serial" value="<?php echo $serial_1?>" readonly="readonly" class="adt span6" data-provide="typeahead" data-source='<?php echo $equipamentos ?>' data-items="20" required/>
										<a id="visualizando" class="btn btn-mini btn-danger visualizando" title="Visualizar"><i class="icon-eye-open icon-white"></i> Visualizar</a>
										<a id="editando" class="btn btn-mini btn-success editando" title="Editar"><i class="icon-pencil icon-white"></i> Editar</a>
									</div>
								</div>
								<div class="span4">
									<div class="control-group">
										Usuário:
										<select name="usuario" id="usuario">
											<?php foreach ($usuarios as $usuario): ?>
												<option value="<?php echo $usuario->code ?>"><?php echo $usuario->usuario ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								<div class="span4">
									<div class="control-group">
										Data Instalação:
										<div class="wrapper input-prepend">
											<input id="data_instalacao" name="data_instalacao" type="text" value="<?php echo date('d/m/Y', strtotime($dados_veic->data_instalacao)); ?>" readonly="readonly" required/>
										</div>
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span6">
									<div class="control-group">
										Marca:
										<input type="text" name="marca" placeholder="Marca" value="<?= $dados_veic->marca; ?>" required/>
									</div>
								</div>
								<div class="span6">
									<div class="control-group">
										Modelo:
										<input type="text" name="modelo" placeholder="Modelo" value="<?= $dados_veic->modelo; ?>" required/>
									</div>
								</div>
							</div>
					<?php else:?>
						<div class="alert alert-info">
							<button type="button" class="close" >&times;</button>
							<h4>Placa e serial ja cadastrado em todos usuários!</h4>
						</div>
					<?php endif;?>
				<?php else:?>
					<input type="hidden" name="status" value= "correcao"/>
					<div class="alert alert-block">
					  	<button id="atencao" type="button" class="close" >&times;</button>
					  	<h4>Atenção!</h4>
					  	A placa <b><?php echo $placa ?></b> está relacionada a mais de <b>1</b> serial: <b><?php foreach ($seriais as $ser) { echo $ser->serial." "; } ?></b>
					  	- Para corrigir, identifique e informe o serial correto!
					</div>

					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label>Informe Serial:</label>
								<input type="text" name="serial" placeholder="Serial" data-provide="typeahead" data-source='<?php echo $equipamentos ?>' data-items="20" required />
							</div>
						</div>
					</div>
				<?php endif;?>
			<?php else:?>
				<?php if ($usuarios):?>
					<input type="hidden" name="status" value= "novo"/>
					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								Veículo:
								<input type="text" name="veiculo" minlength="4" placeholder="Veículo" required />
							</div>
						</div>
						<div class="span3">
							<div class="control-group">
								Táxi:
								<select name="taxi" required>
									<option value="0">Não</option>
									<option value="1">Sim</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								Prefixo:
								<input type="text" name="prefixo" placeholder="Prefixo" />
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								Serial:
									<input class="span6" type="text" name="serial" placeholder="Serial" data-provide="typeahead" data-source='<?php echo $equipamentos ?>' data-items="20" required />
									<a id="visualizando" class="btn btn-mini btn-danger visualizando" title="Visualizar"><i class="icon-eye-open icon-white"></i> Visualizar</a>
									<a id="editando" class="btn btn-mini btn-success editando" title="Editar"><i class="icon-pencil icon-white"></i> Editar</a>
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								Usuário:
								<select name="usuario" id="usuario">
									<?php foreach ($usuarios as $usuario): ?>
										<option value="<?php echo $usuario->code ?>"><?php echo $usuario->usuario ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								Data Instalação:
								<div>
									<input class="calendarios" type="text" name="data_instalacao" value="<?php echo date('d/m/Y') ?>" />
								</div>
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								Marca:
								<input type="text" name="marca" placeholder="Marca" />
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								Modelo:
								<input type="text" name="modelo" placeholder="Modelo" />
							</div>
						</div>
					</div>
				<?php else:?>
					<div class="alert alert-info">
						<button type="button" class="close" >&times;</button>
					  <h4>Placa e serial ja cadastrado em todos usuários!</h4>
					</div>
				<?php endif;?>
			<?php endif;?>
			<div class="row-fluid">
				<div class="form-actions">
					<button type="submit" id="enviar" name="Salvar" class="btn btn-primary btn_vinc" >Salvar</button>
					<!-- <button type="button" class="btn btn-secundary hide_row">Fechar</button> -->
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">

$(document).ready(function(){

	//$(".informacao").hide();
	$(".desvincular").hide();

	$("#vincularVeiculo").ajaxForm({
		dataType: 'json',
		beforeSend: settings => {
			//animacao do botao clicado
			$('.btn_vinc').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
		},
		success: function(retorno){
			$("#atencao").click();
			if (retorno.success) {
				if (retorno.operacao == 'novo') {
					//muda o status após uma nova vinculacao
					$(".status_"+list_id_placa).removeClass('label-info')
					.addClass('label-success').text('ativo');

					//muda o botao de acao ativar/desativar
					$(".btn_"+list_id_placa).html('<button type="button" href_ativar="'+list_href_ativar+'" href_inativar="" class="btn btn-small btn-success ativar_placa btn_ativo_'+list_id_placa+'" data-placa_id="'+list_id_placa+'">Editar</button>'+
								   				  '<button type="button" href="'+list_href_inativar+'" class="btn btn-small btn-secundary inativar_placa btn_inativo_'+list_id_placa+'" data-placa_id="'+list_id_placa+'">Inativar</button> ');


				}else if (retorno.operacao == 'edicao') {
					//muda o status após uma nova vinculacao
					$(".status_"+list_id_placa).removeClass('label-info')
					.addClass('label-success').text('ativo');

					//muda o botao de acao para ativar
					$(".btn_ativo_"+list_id_placa).text('Editar');
				}

				//atualiza data-status do botao de posição
				$(".btnPosicao_"+list_id_placa).attr('data-status', 'ativo');

				$(".informacao").html(retorno.mensagem);
				$(".informacao").show();
				$('#des').show();
			};
			// if (retorno.placas) {
			// 	$(".vincular").hide();
			// 	$(".desvincular").show();
			// 	$('.placasdesv').val(retorno.placas);
			// 	$('.serialdesv').val(retorno.serial);
			// 	$(".btnusuarios").hide();
			// };
			$('.adt').val(retorno.serial);
			$('.btn_vinc').attr('disabled', false).html('Salvar');
		}
	});

	$("#formUsuario").ajaxForm({
		dataType: 'json',
		success: function(retorno){
			if (retorno.certo) {
				$(".informacao").html(retorno.msg);
				$(".informacao").show();
			};
		}
	});

	$('.visualizando').hide();
	$('#des').hide();

	$("a#visualizando").click(function(){
		$(".adt").attr("readonly", true);
		$(".adt").attr("disabled", "disabled");
		$('.editando').show();
		$('.visualizando').hide();
		//$('button#enviar').show();

		// $.ajax({
		// 	url: "<?php echo site_url('contratos/validarAjax')?>",
		// 	type: 'POST',
		// 	data: {placa: <?php echo json_encode($placa) ?>, id_cliente: <?php echo $id_cliente ?>, id_contrato: <?php echo $id_contrato ?>},
		// 	dataType: 'html',
		// 	success: function(endereco){
		// 		console.log('ok!');
		// 		//$(obj).find('td').eq(TDEndereco).html('OK!!!!');
		// 	},
		// 	error: function(){
		// 		//$(obj).find('td').eq(TDEndereco).text('Endereço não encontrado!');
		// 	}
		// });
	});

	$("a#editando").click(function(){
		$(".adt").attr("readonly", false);
		$(".adt").removeAttr("disabled");
		$('.editando').hide();
		$('.visualizando').show();
		//$('button#enviar').hide();
	});

	$('.rmvusuario').hide();

	$("a#rmvusuario").click(function(){
		$('.rmvusuario').show();
		$('.addusuario').hide();
		$(".informacao").hide();
	});

	$("a#addusuario").click(function(){
		$('.rmvusuario').hide();
		$('.addusuario').show();
		$(".informacao").hide();
	});

	$('select[name=taxi]').on('click', function(){
		if($(this).val() == '1')
			$("input[name=prefixo]").prop('required',true);
		else
			$("input[name=prefixo]").prop('required',false);
	});
});
</script>
<script>
	$(document).ready(function(){
		$('.calendarios').focus(function(){
			$(this).calendario({target: $(this)});
		});
	});
</script>
<script type="text/javascript" src="<?php echo base_url('media')?>/js/calendario.js"></script>
