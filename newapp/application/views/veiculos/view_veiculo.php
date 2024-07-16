<?php 

if(isset($erros)):?>
	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert">×</button>
	  	<h4>Erro!</h4>
	  	<?php echo $erros?>
	</div>
<?php endif;?>

<?php if(isset($sucesso)):?>
	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert">×</button>
	  	<h4>Sucesso!</h4>
	  	<?php echo $sucesso?>
	</div>
<?php endif;?>

<div id="mensagem-sucesso"></div>

<style type="text/css">
	h3.modal-title {
        color: #1C69AD !important;
        font-size: 22px !important;
        display: flex;
        justify-content: space-between;
    }

    .header-layout{
        border-bottom: 2px solid #e5e5e5;
        margin-top: 0.8rem
    }

    .close{
        border-radius: 25px;
        background-color: #e5e5e5!important;
        width: 30px;
        height: 30px;
        color: #7F7F7F;
        font-size: 32px;
    }

    .modal-content{
        border-radius: 25px;
        gap: 25px;
    }

	.footer-group{
        display: flex;
        justify-content: space-between;
        width: 100%;
        padding: 8px 0px;
    }

    .btn{
        border-radius: 7px !important;
    }

    .btn-success{
        background-color: #28A745 !important;
    }

    .btn-success:hover{
        background-color: #28a746e7 !important;
    }

	.veic-form-group {
        padding: 0 10px;
    }
</style>

<!-- <?php echo form_open('', array('class' => 'form-horizontal'))?> -->
	<!-- <?php if($veiculo):?> -->

	<div class="modal-content" style="box-shadow: none;">
		<div class="modal-header header-layout" style="text-align: center;">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 class="modal-title" id="titleWhitelist">Cadastro de Veículo</h3>
		</div>
		<div class="modal-body">
			<input type="hidden" name="codeVeiculo" id="codeVeiculo">
			<form id=form-cad-veiculo>
				<div id="usuario-form" class="row veic-form-group">
					<?php if (isset($usuarios) && is_array($usuarios) && count($usuarios) > 0): ?>
						<div class="form-group col-sm-12" for="clientes">
							<label class="control-label">Usuário</label>
							<select name="CNPJ_" id="clientes" class="form-control">
								<?php foreach ($usuarios as $usuario): ?>
									<option value="<?php echo $usuario->usuario ?>"><?php echo $usuario->usuario ?></option>
								<?php endforeach ?>
							</select>
						</div>
					<?php else: ?>
						<!-- <script>
							alert("Não há usuários vinculados a esse veículo");
						</script> -->
					<?php endif ?>
				</div>
				<div class="row veic-form-group">
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">Placa</label>
						<input type="hidden" name="placa" value="<?php echo $veiculo->placa?>"/>
						<input type="text" name="placa" value="<?php echo $veiculo->placa?>" class="form-control" disabled/>
					</div>
					<span class="help help-block"></span>
					<div  class="form-group col-sm-4">
						<label class="control-label">Serial</label>
						<input type="hidden" name="serial" value="<?php echo $veiculo->serial?>"/>
						<input type="text" name="serial" maxlength="50" value="<?php echo $veiculo->serial?>" class="form-control" disabled/>
					</div>
					<div class="form-group col-sm-4">
						<label class="control-label">Veículo</label>
						<input type="text" name="veiculo" maxlength="50" value="<?php echo $veiculo->veiculo ?>" class="form-control" required />
					</div>
				</div>
				<div class="row veic-form-group">
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">Marca</label>
						<input type="text" name="marca" maxlength="45" value="<?php echo $veiculo->marca?>" class="form-control" required/>
					</div>
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">Modelo</label>
						<input type="text" name="modelo" maxlength="45" value="<?php echo $veiculo->modelo?>" class="form-control" required/>
					</div>
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">Prefixo</label>
						<input type="text" name="prefixo_veiculo" maxlength="10" value="<?php echo $veiculo->prefixo_veiculo?>"  class="form-control" required/>
					</div>
				</div>
				<div class="row veic-form-group">
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">Base Veículo</label>
						<input type="text" name="base_veiculo" maxlength="100" value="<?php echo $veiculo->base_veiculo?>" class="form-control" required/>
					</div>
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">Data Instalação</label>
						<?php if ($veiculo->data_instalacao): ?>
							<?php if ($veiculo->data_instalacao == "0000-00-00"): ?>
								<input type="date" class="calendarioos" name="data_instalacao" value="<?php echo date('Y-m-d') ?>" class="form-control" required/>
							<?php else: ?>
								<input type="date" class="form-control calendarioos" name="data_instalacao" value="<?php echo $veiculo->data_instalacao ?>" class="form-control" required/>
							<?php endif; ?>
						<?php else: ?>
							<input type="date" class="form-control calendarioos" name="data_instalacao" value="<?php echo date('Y-m-d') ?>" required/>
						<?php endif; ?>
					</div>
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">Lic. Vencimento</label>
						<input type="date" name="vencimento" value="<?php echo $veiculo->vencimento?>" class="form-control" required/>
					</div>
				</div>
				<div class="row veic-form-group">
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">Contrato</label>
						<input type="text" name="contrato_veiculo" value="<?php echo $veiculo->contrato_veiculo?>" maxlength="17" class="form-control" required/>
					</div>
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">UF</label>
						<input type="text" name="uf" value="<?php echo $veiculo->uf?>" class="form-control" maxlength="2" required/>
					</div>
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">Hodômetro (Km)</label>
						<input type="number" min="0" name="hodometro" class="form-control" value="<?php echo $this->input->post('hodometro') ? $this->input->post('hodometro') : round($veiculo->hodometro) ?>" required/> 
					</div>
				</div>
				<div class="row veic-form-group">
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">Horímetro (Hrs)</label>
						<input type="number" min="0" name="horimetro" class="form-control" value="<?= $this->input->post('horimetro') ? $this->input->post('horimetro') : round($veiculo->horimetro) ?>" required>
					</div>
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">Ano</label>
						<input type="number" min="0" max="9999" name="ano" value="<?php echo $veiculo->ano?>"  class="form-control" required/>
					</div>
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">RPM Máximo</label>
						<input type="number" min="0" max="99999" name="rpm_max" value="<?php echo $veiculo->rpm_max?>"  class="form-control" required/>
					</div>
				</div>
				<div class="row veic-form-group">
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">Limite Velocidade (Km/h)</label>
						<input type="number" min="0" max="999" name="limite_velocidade" value="<?php echo $veiculo->limite_velocidade?>" class="form-control" required/> 
					</div>
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">Regime</label>
						<input type="text" name="regime_veiculo" maxlength="10" value="<?php echo $veiculo->regime_veiculo?>" class="form-control" required/>
					</div>
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">IMEI</label>
						<input type="text" name="imei" maxlength="20" value="<?php echo $veiculo->imei?>" class="form-control" required/>
					</div>	
				</div>
				<hr>
				<div class="row veic-form-group">
					<span class="help help-block"></span>
					<div class="form-group col-sm-4">
						<label class="control-label">Motivo</label>
						<select name="motivo" class="form-control" required>
							<option value="Instalação">Instalação</option>
							<option value="Manutenção">Manutenção</option>
						</select>
					</div>
				</div>
				<div class="row veic-form-group">
					<span class="help help-block"></span>
					<div class="form-group col-sm-12">
						<label class="control-label">Observação</label>
						<textarea name="observacao" maxlength="500" rows="5" style="resize: vertical;" class="form-control" required></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<div class="footer-group">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
						<button type="submit" class="btn btn-success" id="btn-salvar-veiculo"  data-tipo="atualizar">Salvar</button>
					</div>
				</div>
			</form>
		</div>
	</div>	
	
<!-- <?php endif;?> -->

<!-- <?php echo form_close()?> -->

<script>
	$('#modalVeiculoCadastro').on('shown.bs.modal', function () {

		$('#form-cad-veiculo').submit(function (e) {
			e.preventDefault();
			var formDataArray = $(this).serializeArray();
			var formDataObject = {};
			var code = $("#codeVeiculo").val();
			
			$(formDataArray).each(function(index, obj){
				formDataObject[obj.name] = obj.value;
			});

			showLoadingSalvarButton();

			$.ajax({
				url: BASE_URL + 'cadastros/atualizar_veiculo/' + code,
				type: 'POST',
				data: formDataObject,
				dataType: 'json',
				success: function (response) {
					if ('mensagem' in response) {
						alert(response.mensagem);
					} else {
						alert("Erro com a API!")
					}
					resetSalvarButton();
					$("#modalVeiculoCadastro").modal("hide");
				},
				error: function (xhr, status, error) {
					alert("Erro ao enviar a requisição. Tente novamente!");
					resetSalvarButton();
				}
			});

		})

		function showLoadingSalvarButton() {
			$('#btn-salvar-veiculo').html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
		}

		function resetSalvarButton() {
			$('#btn-salvar-veiculo').html('Salvar').attr('disabled', false);
		}
	});

</script>