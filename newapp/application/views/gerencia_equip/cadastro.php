<link href="<?php echo base_url('newAssets') ?>/css/jquery-ui.css" rel="stylesheet">
<script defer src="<?php echo base_url('newAssets/js/jquery.maskedinput.min.js') ?>"></script>
<script defer src="<?php echo base_url('newAssets/js/ordem.js') ?>"></script>
<script defer src="<?php echo base_url('newAssets/js/jquery-ui.min.js') ?>"></script>
<section id="cad-chip" class="container-fluid">
<div id= "cadastro">
	<div id="mensagem">
		<?php if (isset($msg)){
			echo ('<div class="alert alert-success form-inline" role="alert" style="margin: 12px; padding-bottom: 26px;">'.$msg.'<a id="okay" class="pull-right btn btn-info" style="vertical-align: middle;" >OK! <i class="fa fa-thumbs-up" aria-hidden="true"></i></a></div>');
		} ?>
	</div>
	<br>
	<form method="post" id="formOrdem" class="form-horizontal">
	<div class="container-fluid">
		<div id="botoes" class="form-group">
			<div class="form-group form-inline row">
				<a class= "text-left btn btn-default" href="<?php echo site_url('gerencia_equipamentos') ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a>
				<button type="submit" class="pull-right btn btn-success" name="add_ordem" value="Adicionar" id="adicionar">Adicionar</button>
				<a id="limpar" class="pull-right btn btn-warning btnMarg">Limpar</a>
			</div>
		</div>
	</div>
		<hr class="divider" />
		<div id="dados" class="form-group">
			<div class="form-group">
				<div class="col-sm-6">
					<label for="serial" class="col-sm-4 control-label labelpad">Serial:* </label>
					<div id="divSerial" class="col-sm-8">
						<input class="form-control" type="text" name="serial" id="serial" placeholder="Serial do equipamento" required>
					</div>
				</div>
				<div class="col-sm-6">
					<label for="modelo" class="col-sm-4 control-label labelpad">Modelo: </label>
					<div class="col-sm-8">
						<input class="form-control" type="text" name="modelo" id="modelo" readonly>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<label for="recebimento" class="col-sm-4 control-label labelpad">Recebido em:</label>
					<div class="col-sm-8">
						<input class="form-control" type="text" name="recebimento" id="recebimento" readonly>
					</div>
				</div>
				<div class="col-sm-6">
					<label id="campo" for="dataConfig" class="col-sm-4 control-label labelpad">Configurado:</label>
					<div class="col-sm-8">
						<input class="form-control" type="text" name="dataConfig" id="dataConfig" readonly>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<label for="linha" class="col-sm-4 control-label labelpad">Linha:</label>
					<div class="col-sm-8">
						<input class="form-control" type="text" name="linha" id="linha" readonly>
					</div>
				</div>
				<div class="col-sm-6">
					<label for="operadora" class="col-sm-4 control-label labelpad">Operadora: </label>
					<div class="col-sm-8">
						<input  class="form-control" type="text" name="operadora" id="operadora" readonly>
					</div>
				</div>
			</div>
			<div id="problema" class="text-center form-group col-sm-7" hidden>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<label for="ping" class="col-sm-2 control-label labelpad">Ping:</label>
					<div class="col-sm-10">
						<input class="form-control" type="text" name="ping" id="ping" readonly>
					</div>
				</div>
			</div>			
			<div class="form-group">
				<div class="col-sm-6">
					<label for="solicitante" class="col-sm-4 control-label labelpad">Solicitante:*</label>
					<div class="col-sm-8">
						<input class="form-control" type="text" name="solicitante" id="solicitante" placeholder="Atendente do suporte" required>
					</div>
				</div>
				<div class="col-sm-6">
					<label for="placa" class="col-sm-4 control-label labelpad">Veículo:</label>
					<div class="col-sm-8">
						<input class="form-control" type="text" name="placa" id="placa" placeholder="Ex: ABC-0123" >
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<label for="destino" class="col-sm-4 control-label labelpad">Destino:* </label>
					<div class="col-sm-8">
						<select  class="form-control custom-select" name="destino" id="destino">
							<option selected value="0">opções</option>
						  	<option id="tec" value="1">Técnico</option>
							<option id="cli" value="2">Cliente</option>
						</select>
					</div>
				</div>
				<div class="col-sm-6">
					<label for="tipo" class="col-sm-4 control-label labelpad">Tipo da OS:* </label>
					<div class="col-sm-8">
						<select  class="form-control custom-select" name="tipo" id="tipo">
							<option selected value="0">opções</option>
						  	<option value="1">Manutenção</option>
							<option value="2">Instalação</option>
						</select>
					</div>
				</div>
			</div>
			<div id="input_devolucao" class="form-group" hidden>
				<div class="col-sm-12">
					<label for="serialAntigo" class="col-sm-2 control-label labelpad">Devolução:*</label>
					<div class="col-sm-10">
						<input class="form-control" type="text" name="serialAntigo" id="serialAntigo" placeholder="Número do serial do equipamento que voltará da manutenção">
					</div>
				</div>
			</div>
			<div id="input_cli" class="form-group" hidden>
				<div class="col-sm-6 ">
					<label for="cliente" class="col-sm-4 control-label labelpad">Cliente:* </label>
					<div id="divCli" class="col-sm-8">
						<select class="form-control pesqnome" id="pesqnome" name="cliente" type="text"></select>
						<!-- <input class="form-control" type="text" name="cliente" placeholder="Código do cliente"> -->
					</div>
				</div>
				<div class="col-sm-6">
					<label for="referencial" class="col-sm-4 control-label labelpad">Referência: </label>
					<div class="col-sm-8">
						<input class="form-control" type="text" name="referencialC" id="referencial" placeholder="Extras. Ex: matriz" >
					</div>
				</div>
			</div>
			<div id="input_tec" class="form-group" hidden>
				<div class="col-sm-6 ">
					<label for="instalador" class="col-sm-4 control-label labelpad">Instalador:* </label>
					<div id="divTec" class="col-sm-8">
					<select class="form-control pesqInstalador" id="pesqInstalador" name="instalador" type="text"></select>
						<!-- <input class="form-control" type="text" name="instalador" id="instalador" placeholder="Código do técnico"> -->
					</div>
				</div>
				<div class="col-sm-6">
					<label for="referencial" class="col-sm-4 control-label labelpad">Referência: </label>
					<div class="col-sm-8">
						<input class="form-control" type="text" name="referencialT" id="referencial" placeholder="Informações adicionais">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<hr class="divider" />
					<label>Informações de envio:</label>
					<hr class="divider" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<label for="tipoEnvio" class="col-sm-4 control-label">Tipo:* </label>
					<div class="col-sm-8">
						<select  class="form-control custom-select" name="tipoEnvio" id="tipoEnvio">
							<option selected value="0">opções</option>
						  	<option id="correio" value="1">Correios</option>
							<option id="empregado" value="2">Tam Cargo</option>
							<option id="outro" value="3">Outro...</option>
						</select>
					</div>
				</div>
				<div class="col-sm-6">
					<label id="rastreio" for="infoTipo" class="col-sm-4 control-label labelpad" hidden>Cód. rastreio: </label>
					<label id="complemento" for="infoTipo" class="col-sm-4 control-label labelpad">Complemento: </label>
					<div class="col-sm-8">
						<input class="form-control" type="text" name="infoTipo" id="infoTipo" placeholder="Outras infors Ex: cód. rastreio">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6 ">
					<label for="dataEnvio" class="col-sm-4 control-label labelpad">Data de Envio:* </label>
					<div id="campoData" class="col-sm-8">
						<input class="form-control dataJS" type="text" name="dataEnvio" id="dataEnvio" required>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="col-sm-2" hidden>
						<label><input id="checar" class="form-control" type="checkbox" name="dataHoje">Hoje</label>
					</div>
					<div class="col-sm-2">
					</div>
					<div id="tipoCargo" class="col-sm-8" hidden>
						<label class="col-sm-6 text-center"><input id="ra1" class="form-control" type="radio" name="encomendaT" value="1" checked>Pré-pago</label>
						<label class="col-sm-6 text-center"><input id="ra2" class="form-control" type="radio" name="encomendaT" value="2">Próximo voo</label>
					</div>
					<div id="tipoCorreio" class="col-sm-8" hidden>
						<label class="col-sm-6 text-center"><input id="ra1" class="form-control" type="radio" name="encomendaC" value="1" checked>P.A.C.</label>
						<label class="col-sm-6 text-center"><input id="ra2" class="form-control" type="radio" name="encomendaC" value="2">Sedex</label>
					</div>
				</div>
			</div>
			<div>
				<p>*Campos obrigatórios. </p>
			</div>
		</div>
	</form>
</div>
</section>
