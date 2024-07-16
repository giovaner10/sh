    <?php

foreach ($cliente_dados as $cliente_dado) {

	if ($cliente_dado->cpf != "") {
		$cliente_pessoa = 1;
	} else {
		$cliente_pessoa = 0;
	}

	$cliente_id = $cliente_dado->id;
	$cliente_cnpj = $cliente_dado->cnpj;
	$cliente_ie = $cliente_dado->inscricao_estadual;
	$cliente_informacoes = $cliente_dado->informacoes;
	$cliente_rg = $cliente_dado->identidade;
	$cliente_rg_orgao = $cliente_dado->orgaoexp;
	$cliente_cpf = $cliente_dado->cpf;
	$cliente_nome = $cliente_dado->nome;
	$cliente_razao = $cliente_dado->razao_social;
	$cliente_consultor = $cliente_dado->consultor_m2m;
}

if ($cliente_informacoes == "SIMM2M"):
	?>
	<style> #sim{display: none} </style>
	<script>$('#divConsultor').removeClass('mostra-campo')</script>
<?php endif; ?>
<?php
if ($cliente_informacoes == "NORIO"):
	?>
	<style> #norio{display: none} </style>
	<script>$('#option').text('SIGA ME - NORIO MOMOI EPP')</script>
<?php endif; ?>
<?php
if ($cliente_informacoes == "TRACKER"):
	?>
	<style> #show{display: none} </style>
	<script>$('#option').text('SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA - ME')</script>
<!--    <style>#divConsultor{display: none}</style>-->

<?php endif; ?>
<?php if ($cliente_informacoes == "EUA"): ?>
    <style> #show{display: none} </style>
    <script>$('#option').text('SHOW TECHNOLOGY EUA')</script>
<?php endif; ?>

<style>.mostra-campo{display: none}</style>
<form action="<?php echo site_url('cadastros/atualizar_cliente/'.$cliente_id) ?>" method="post" class="form-horizontal formulario" id="clientes">

	<!--  botoes de acoes  -->

	<div class="resultado span6" style="float: none;">
	</div>

	<div class="row-fluid">
		<div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="tab1 active"><a href="#tab1" data-toggle="tab">Dados</a></li>
				<li class="tab2"><a href="#tab2" data-toggle="tab">Cartões</a></li>
				<li class="tab3"><a href="#tab3" data-toggle="tab">Endereços</a></li>
				<li class="tab4"><a href="#tab4" data-toggle="tab">Contatos</a></li>
				<li class="tab5"><a href="#tab5" data-toggle="tab">Atendimento</a></li>
			</ul>
			<!--  tabbable nav  -->

			<div class="btn-group pull-right">
				<a id="editando" class="btn btn-success editando" title="Editar"><i class="icon-pencil icon-white"></i> Editar</a>
			</div>
			<div class="btn-group pull-right ">
				<a id="visualizando" class="btn btn-danger visualizando" title="Visualizar"><i class="icon-eye-open icon-white"></i> Visualizar</a>
				<button type="submit" class="salvar btn btn-primary salvando" title="Salvar alterações"><i class="icon-download-alt icon-white"></i> Salvar</button>
			</div>

			<div class="tab-content">
				<div class="tab-pane active" id="tab1">
					<div class="row dados">
						<div class="span8">
							<fieldset class="cliente" id="0">

								<div class="pessoa_fj">
									<div class="control-group">
										<label class="control-label">Pessoa:<sup>*</sup></label>
										<div class="controls controls-row">
											<?php if($cliente_pessoa == 1): ?>
												<label class="radio inline"><input type="radio" name="cliente[pessoa]" value="1" checked> Física</label>
												<label class="radio inline"><input type="radio" name="cliente[pessoa]" value="0"> Jurídica</label>
											<?php else: ?>
												<label class="radio inline"><input type="radio" name="cliente[pessoa]" value="1"> Física</label>
												<label class="radio inline"><input type="radio" name="cliente[pessoa]" value="0" checked> Jurídica</label>
											<?php endif ?>
											<span class="help help-block"></span>
										</div>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Nome:<sup>*</sup></label>
									<div class="controls controls-row">
										<input type="text" name="cliente[nome]" readonly="readonly" value="<?php echo $cliente_nome ?>" class="span12 upper adt">
										<span class="help help-block"></span>
									</div>
								</div>

								<div class="fisica">

									<div class="control-group">
										<label class="control-label">EIN:<sup>*</sup></label>
										<div class="controls controls-row">
											<input type="text" name="cliente[cpf]" readonly="readonly" value="<?php echo $cliente_cpf ?>" class="span12 cpf adt">
											<span class="help help-block"></span>
										</div>
									</div>
								</div>

								<div class="juridica">
									<div class="control-group">
										<label class="control-label">Trading name:<sup>*</sup></label>
										<div class="controls controls-row">
											<input type="text" name="cliente[razao_social]" readonly="readonly" value="<?php echo $cliente_razao ?>" class="span12 upper adt">
											<span class="help help-block"></span>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">Tax Id:<sup>*</sup></label>
										<div class="controls controls-row">
											<input type="text" name="cliente[cnpj]" readonly="readonly" value="<?php echo $cliente_cnpj ?>" class="span12 cnpj adt">
											<span class="help help-block"></span>
										</div>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Empresa:</label>
									<div class="controls controls-row">
										<select id="empresa" name="cliente[informacoes]" class="span12 upper adt" readonly="readonly">
											<option id="option" value="<?php echo $cliente_informacoes; ?>"><?php echo $cliente_informacoes; ?></option>
											<option id="show" value="TRACKER">SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA - ME</option>
											<option id="sim" value="SIMM2M">SIMM2M</option>
											<option id="norio" value="NORIO">SIGA ME - NORIO MOMOI EPP</option>
                                            <option id="eua" value="EUA">SHOW TECHNOLOGY EUA</option>
										</select>
										<!--										<input type="text" name="cliente[informacoes]" readonly="readonly" value="--><?php //echo $cliente_informacoes ?><!--" class="span12 upper adt">-->
										<span class="help help-block"></span>
									</div>
								</div>

                                <div id="vendedor" class="control-group">
                                    <label class="control-label">Vendedor:</label>
                                    <div class="controls controls-row">
                                        <select name="id_vendedor" class="span12 upper adt" readonly="readonly">
                                            <option><?php if ($cliente_vendedor) echo $cliente_vendedor?></option>
                                            <?php foreach ($consultores as $consultor): ?>
                                                <option value="<?php echo $consultor->id ?>">
                                                    <?php echo $consultor->nome ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
								</div>
								<div class="nota_fiscal">
									<div class="control-group br">
										<label class="control-label">Nota Fiscal:</label>
										<div class="controls controls-row">
											<input type="text" name="cliente[cod_servico]" readonly="readonly" value="<?php echo $cliente_dados[0]->cod_servico ?>" class="span3 adt" placeholder="Código do serviço">
											<input type="text" name="cliente[descriminacao_servico]" readonly="readonly" value="<?php echo $cliente_dados[0]->descriminacao_servico ?>" class="span9 adt" maxlength="100" placeholder="Descrição do serviço">
											<span class="help help-block"></span>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
					<!--   formulario coluna  -->
				</div>
				<!--   tabbable tab  -->

				<div class="tab-pane" id="tab2">
					<div class="row cartoes">
						<div class="span8 recipiente">
							<div class="control-group">
								<label class="control-label">Cartão:</label>
								<div class="controls controls-row">
									<a href="#" title="Adicionar" class="btn adicionar" data-campos="cartao"><i class="icon-plus-sign"></i> Adicionar</a>
								</div>
							</div>

							<?php $indice_cartao = 0 ?>
							<?php if ($cliente_cartoes): ?>
								<?php foreach ($cliente_cartoes as $cliente_cartao): ?>

									<fieldset class="cartao" id="0">
										<div class="control-group">
											<div class="controls controls-row">
												<input type="hidden" name="cartao[<?php echo $indice_cartao ?>][status]" value= '1'/>
												<input type="hidden" name="cartao[<?php echo $indice_cartao ?>][id]" value= '<?php echo $cliente_cartao->id ?>'/>
												<input type="text" name="cartao[<?php echo $indice_cartao ?>][numero]" readonly="readonly" value="<?php echo $cliente_cartao->numero ?>" class="span6 numero_cartao adt" placeholder="Número">
												<input type="text" name="cartao[<?php echo $indice_cartao ?>][bandeira]" readonly="readonly" value="<?php echo $cliente_cartao->bandeira ?>" class="span6 bandeira_cartao adt" placeholder="Bandeira">
												<span class="help help-block"></span>
											</div>
										</div>
										<div class="control-group">
											<div class="controls controls-row">
												<input type="text" name="cartao[<?php echo $indice_cartao ?>][vencimento]" readonly="readonly" value="<?php echo $cliente_cartao->vencimento ?>" class="span6 vencimento_cartao adt" placeholder="Vencimento">
												<input type="text" name="cartao[<?php echo $indice_cartao ?>][codigo]" readonly="readonly" value="<?php echo $cliente_cartao->codigo ?>" class="span6 codigo_cartao adt" placeholder="Código">
												<span class="help help-block"></span>
											</div>
										</div>
										<div class="control-group">
											<div class="controls controls-row">
												<input type="text" name="cartao[<?php echo $indice_cartao ?>][nome]" readonly="readonly" value="<?php echo $cliente_cartao->nome ?>" class="span12 upper adt" placeholder="Nome">
												<span class="help help-block"></span>
											</div>
										</div>
										<hr class="featurette-divider">
									</fieldset>

									<?php $indice_cartao = $indice_cartao + 1 ?>

								<?php endforeach ?>
							<?php else: ?>

								<fieldset class="cartao editando" id="0">
									<div class="controls controls-row">
										<div class="alert alert-block">
											<h4>Não há cartões cadastrados</h4>
										</div>
									</div>
								</fieldset>

							<?php endif; ?>

						</div>
					</div>
					<!--   formulario coluna  -->
				</div>
				<!--   tabbable tab  -->

				<div class="tab-pane" id="tab3">
					<div class="row enderecos">
						<div class="span8 recipiente">
							<div class="control-group">
								<label class="control-label">Endereço:</label>
								<div class="controls controls-row">
									<a href="#" title="Adicionar" class="btn adicionar adt" data-campos="endereco"><i class="icon-plus-sign"></i> Adicionar</a>
								</div>
							</div>

							<?php $indice_endereco = 0 ?>
							<?php if ($cliente_enderecos): ?>
								<?php foreach ($cliente_enderecos as $cliente_endereco): ?>

									<fieldset class="endereco" id="0">
										<div class="control-group">
											<div class="controls controls-row">
												<input type="hidden" name="endereco[<?php echo $indice_endereco ?>][status]" value= '1'/>
												<input type="hidden" name="endereco[<?php echo $indice_endereco ?>][id]" value= '<?php echo $cliente_endereco->id ?>'/>
												<input type="text" name="endereco[<?php echo $indice_endereco ?>][latitude]" readonly="readonly" value="<?php echo $cliente_endereco->latitude == 0 ? "" : $cliente_endereco->latitude ?>" class="span6 adt" placeholder="Latitude">
												<input type="text" name="endereco[<?php echo $indice_endereco ?>][longitude]" readonly="readonly" value="<?php echo $cliente_endereco->longitude == 0 ? "" : $cliente_endereco->longitude ?>" class="span6 adt" placeholder="Longitude">
												<span class="help help-block"></span>
											</div>
										</div>
										<div class="control-group">
											<div class="controls controls-row">
												<input type="text" name="endereco[<?php echo $indice_endereco ?>][cep]" readonly="readonly" value="<?php echo $cliente_endereco->cep ?>" class="span12 cep adt" placeholder="Zip code">
												<span class="help help-block"></span>
											</div>
										</div>
										<div class="control-group">
											<div class="controls controls-row">
												<input type="text" name="endereco[<?php echo $indice_endereco ?>][rua]" readonly="readonly" value="<?php echo $cliente_endereco->rua ?>" class="span9 upper adt" placeholder="Rua">
												<input type="text" name="endereco[<?php echo $indice_endereco ?>][numero]" readonly="readonly" value="<?php echo $cliente_endereco->numero ?>" class="span3 upper adt" placeholder="Número">
												<span class="help help-block"></span>
											</div>
										</div>
										<div class="control-group">
											<div class="controls controls-row">
												<select name="endereco[<?php echo $indice_endereco ?>][uf]" readonly="readonly" class="span4 adt">
													<option value="<?php echo $cliente_endereco->uf ?>"><?php echo $cliente_endereco->uf ?></option>
                                                    <option value="AL">AL</option>
                                                    <option value="AR">AR</option>
                                                    <option value="AZ">AZ</option>
                                                    <option value="CA">CA</option>
                                                    <option value="CO">CO</option>
                                                    <option value="CT">CT</option>
                                                    <option value="DE">DE</option>
                                                    <option value="FL">FL</option>
                                                    <option value="GA">GA</option>
                                                    <option value="HI">HI</option>
                                                    <option value="IA">IA</option>
                                                    <option value="ID">ID</option>
                                                    <option value="IL">IL</option>
                                                    <option value="IN">IN</option>
                                                    <option value="KS">KS</option>
                                                    <option value="KY">KY</option>
                                                    <option value="LA">LA</option>
                                                    <option value="MA">MA</option>
                                                    <option value="MD">MD</option>
                                                    <option value="ME">ME</option>
                                                    <option value="MI">MI</option>
                                                    <option value="MN">MN</option>
                                                    <option value="MO">MO</option>
                                                    <option value="MS">MS</option>
                                                    <option value="MT">MT</option>
                                                    <option value="NC">NC</option>
                                                    <option value="ND">ND</option>
                                                    <option value="NE">NE</option>
                                                    <option value="NH">NH</option>
                                                    <option value="NJ">NJ</option>
                                                    <option value="NM">NM</option>
                                                    <option value="NV">NV</option>
                                                    <option value="NY">NY</option>
                                                    <option value="OH">OH</option>
                                                    <option value="OK">OK</option>
                                                    <option value="OR">OR</option>
                                                    <option value="PA">PA</option>
                                                    <option value="RI">RI</option>
                                                    <option value="SC">SC</option>
                                                    <option value="SD">SD</option>
                                                    <option value="TN">TN</option>
                                                    <option value="TX">TX</option>
                                                    <option value="UT">UT</option>
                                                    <option value="VA">VA</option>
                                                    <option value="VT">VT</option>
                                                    <option value="WA">WA</option>
                                                    <option value="WI">WI</option>
                                                    <option value="WA">WA</option>
                                                    <option value="WI">WI</option>
                                                    <option value="WV">WV</option>
                                                    <option value="WY">WY</option>
												</select>
												<input type="text" name="endereco[<?php echo $indice_endereco ?>][cidade]" readonly="readonly" value="<?php echo $cliente_endereco->cidade ?>" class="span8 upper adt" placeholder="Cidade">
												<span class="help help-block"></span>
											</div>
										</div>
										<div class="control-group">
											<div class="controls controls-row">
												<input type="text" name="endereco[<?php echo $indice_endereco ?>][complemento]" readonly="readonly" value="<?php echo $cliente_endereco->complemento ?>" class="span12 upper adt" placeholder="Complemento">
												<span class="help help-block"></span>
											</div>
										</div>
										<hr class="featurette-divider">
									</fieldset>

									<?php $indice_endereco = $indice_endereco + 1 ?>

								<?php endforeach ?>
							<?php else: ?>

								<fieldset class="cartao editando" id="0">
									<div class="controls controls-row">
										<div class="alert alert-block">
											<h4>Não há endereços cadastrados</h4>
										</div>
									</div>
								</fieldset>

							<?php endif; ?>


						</div>
					</div>
					<!--   formulario coluna  -->
				</div>
				<!--   tabbable tab  -->

				<div class="tab-pane" id="tab4">

					<div class="row">
						<div class="span8 recipiente">
							<div class="control-group">
								<div class="controls controls-row">
									<div class="alert alert-info">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										Para envio de faturas favor utilizar no e-mail o <i><strong>Setor Financeiro.</strong></i>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row emails">
						<div class="span8 recipiente">
							<div class="control-group">
								<label class="control-label">E-mail:</label>
								<div class="controls controls-row">
									<a href="#" title="Adicionar" class="btn adicionar" data-campos="email"><i class="icon-plus-sign"></i> Adicionar</a>
								</div>
							</div>

							<?php $indice_email = 0 ?>
							<?php if ($cliente_emails): ?>
								<?php foreach ($cliente_emails as $cliente_email): ?>

									<fieldset class="email" id="0">
										<div class="control-group">
											<div class="controls controls-row">
												<input type="hidden" name="email[<?php echo $indice_email ?>][status]" value= '1'/>
												<input type="hidden" name="email[<?php echo $indice_email ?>][id]" value= '<?php echo $cliente_email->id ?>'/>
												<input type="text" name="email[<?php echo $indice_email ?>][emails]" readonly="readonly" value="<?php echo $cliente_email->email ?>" class="span5 lower adt" placeholder="E-mail">
												<select name="email[<?php echo $indice_email ?>][setor]" readonly="readonly" class="span3 adt">
													<option value="<?php echo $cliente_email->setor ?>"><?php echo $cliente_email->setor == 0 ? "Financeiro" : ($cliente_email->setor == 1 ? "Diretoria" : ($cliente_email->setor == 2 ? "Suporte" : "Pessoal")) ?></option>
													<option value="0">Financeiro</option>
													<option value="1">Diretoria</option>
													<option value="2">Suporte</option>
													<option value="3">Pessoal</option>
												</select>
												<input type="text" name="email[<?php echo $indice_email ?>][observacao]" readonly="readonly" value="<?php echo $cliente_email->observacao ?>" class="span4 upper adt" placeholder="Observação">
												<span class="help help-block"></span>
											</div>
										</div>
									</fieldset>

									<?php $indice_email = $indice_email + 1 ?>

								<?php endforeach ?>
							<?php else: ?>

								<fieldset class="cartao editando" id="0">
									<div class="controls controls-row">
										<div class="alert alert-block">
											<h4>Não há e-mails cadastrados</h4>
										</div>
									</div>
								</fieldset>

							<?php endif; ?>

						</div>
					</div><!-- /row emails -->

					<div class="row telefones">
						<div class="span8 recipiente">
							<div class="control-group">
								<label class="control-label">Telefone:</label>
								<div class="controls controls-row">
									<a href="#" title="Adicionar" class="btn adicionar" data-campos="telefone"><i class="icon-plus-sign"></i> Adicionar</a>
								</div>
							</div>

							<?php $indice_telefone = 0 ?>
							<?php if ($cliente_telefones): ?>
								<?php foreach ($cliente_telefones as $cliente_telefone): ?>

									<fieldset class="telefone" id="0">
										<div class="control-group">
											<div class="controls controls-row">
												<input type="hidden" name="telefone[<?php echo $indice_telefone ?>][status]" value= '1'/>
												<input type="hidden" name="telefone[<?php echo $indice_telefone ?>][id]" value= '<?php echo $cliente_telefone->id ?>'/>
												<input type="text" name="telefone[<?php echo $indice_telefone ?>][ddd]" readonly="readonly" value="<?php echo $cliente_telefone->ddd ?>" class="span2 ddd adt" placeholder="DDD">
												<input type="text" name="telefone[<?php echo $indice_telefone ?>][numero]" readonly="readonly" value="<?php echo $cliente_telefone->numero ?>" class="span3 adt" placeholder="Número">
												<select name="telefone[<?php echo $indice_telefone ?>][setor]" readonly="readonly" class="span3 adt">
													<option value="<?php echo $cliente_telefone->setor ?>"><?php echo $cliente_telefone->setor == 0 ? "Financeiro" : ($cliente_telefone->setor == 1 ? "Diretoria" : ($cliente_telefone->setor == 2 ? "Suporte" : "Pessoal")) ?></option>
													<option value="0">Financeiro</option>
													<option value="1">Diretoria</option>
													<option value="2">Suporte</option>
													<option value="3">Pessoal</option>
												</select>
												<input type="text" name="telefone[<?php echo $indice_telefone ?>][observacao]" readonly="readonly" value="<?php echo $cliente_telefone->observacao ?>" class="span4 upper adt" placeholder="Observação">
												<span class="help help-block"></span>
											</div>
										</div>
									</fieldset>

									<?php $indice_telefone = $indice_telefone + 1 ?>

								<?php endforeach ?>
							<?php else: ?>

								<fieldset class="cartao editando" id="0">
									<div class="controls controls-row">
										<div class="alert alert-block">
											<h4>Não há telefones cadastrados</h4>
										</div>
									</div>
								</fieldset>

							<?php endif; ?>
						</div>
					</div><!-- /row telefones -->
					<!--  / formulario coluna  -->
				</div>
				<!--  / tabbable tab  -->
				<dir id="tab5" class="tab-pane">

					<div class="row emailsAtendendte">
						<div class="span8 recipiente">
							<div class="control-group">
								<label class="control-label">Atendimento:</label>
								<div class="controls controls-row">
									<a href="#" title="Adicionar" class="btn adicionar" data-campos="emailAtendente"><i class="icon-plus-sign"></i> Adicionar</a>
								</div>
							</div>

							<?php $indice_email = 0 ?>
							<?php #pr($tandentes);die; ?>
							<?php if ($atendentes): ?>
								<?php foreach ($atendentes as $atendente): ?>

									<fieldset class="email" id="0">
										<div class="control-group">
											<div class="controls controls-row">
												<input type="hidden" name="id_cliente" value="<?php echo $cliente_id ?>" >
												<input type="text" name="emailAtendente[emails]" readonly="readonly" value="<?php echo $atendente->login ?>" class="span5 lower adt" placeholder="E-mail">
												<span class="help help-block"></span>
											</div>
										</div>
									</fieldset>

									<?php $indice_email = $indice_email + 1 ?>

								<?php endforeach ?>
							<?php else: ?>

								<fieldset class="cartao editando" id="0">
									<div class="controls controls-row">
										<div class="alert alert-block">
											<h4>Não há e-mails cadastrados</h4>
										</div>
									</div>
								</fieldset>

							<?php endif; ?>
						</div>
					</div><!-- /row emails -->

					<div class="row emailsVendedor">
						<div class="span8 recipiente">
							<div class="control-group">
								<label class="control-label">Vendedor:</label>
								<div class="controls controls-row">
									<a href="#" title="Adicionar" class="btn adicionar" data-campos="emailsVendedor"><i class="icon-plus-sign"></i> Adicionar</a>
								</div>
							</div>

							<?php $indice_email = 0 ?>
							<?php #pr($tandentes);die; ?>
							<?php if ($vendedores): ?>
								<?php foreach ($vendedores as $atendente): ?>

									<fieldset class="email" id="0">
										<div class="control-group">
											<div class="controls controls-row">
												<input type="hidden" name="id_cliente" value="<?php echo $cliente_id ?>" >
												<input type="text" name="emailsVendedor[emails]" readonly="readonly" value="<?php echo $atendente->login ?>" class="span5 lower adt" placeholder="E-mail">
												<span class="help help-block"></span>
											</div>
										</div>
									</fieldset>

									<?php $indice_email = $indice_email + 1 ?>

								<?php endforeach ?>
							<?php else: ?>

								<fieldset class="cartao editando" id="0">
									<div class="controls controls-row">
										<div class="alert alert-block">
											<h4>Não há e-mails cadastrados</h4>
										</div>
									</div>
								</fieldset>

							<?php endif; ?>
						</div>
					</div><!-- /row emails -->
				</dir>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">

	$('#empresa').change(function() {
		var empresa = $('#empresa option:selected').text();
		if (empresa === "SIMM2M"){
			$('#divConsultor').removeClass('mostra-campo');
		}else{
			$('#divConsultor').addClass('mostra-campo');
		}
	});

	function readURL(input) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();

	        reader.onload = function (e) {
	            $('#blah').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$("#imgInp").change(function(){
	    readURL(this);
	});

	$(function(){

		// exibe campos para cadastro de pessoa física ou jurídica conforme a pessoa selecionada

		if($('input[name="cliente[pessoa]"]:checked').val() == '1'){
			$('.fisica').show();
			$('.juridica').hide();
		}

		if($('input[name="cliente[pessoa]"]:checked').val() == '0'){
			$('.fisica').hide();
			$('.juridica').show();
		}

		$('input[name="cliente[pessoa]"]').on('click', function(){
			if($(this).val() == '1'){
				$('.fisica').show();
				$('.juridica').hide();
			}else{
				$('.fisica').hide();
				$('.juridica').show();
			}
		});

		// adiciona endereco
		$('.adicionar').on('click', function(e){
			e.preventDefault();

			if($(this).data('campos') == 'endereco'){

				var endereco_indice = <?php echo $indice_endereco ?>;

				var template = [
					'<fieldset class="endereco" id="',endereco_indice,'">',
					'<div class="control-group">',
					'<label class="control-label"><a href="#" title="Remover" class="remover"><i class="icon-minus-sign icon-red"></i></a></label>',
					'<div class="controls controls-row">',
					'<input type="hidden" name="endereco[',endereco_indice,'][status]" value= "0"/>',
					'<input type="text" name="endereco[',endereco_indice,'][latitude]" value="" class="span6 adt" placeholder="Latitude">',
					'<input type="text" name="endereco[',endereco_indice,'][longitude]" value="" class="span6 adt" placeholder="Longitude">',
					'<span class="help help-block"></span>',
					'</div>',
					'</div>',
					'<div class="control-group">',
					'<div class="controls controls-row">',
					'<input type="text" name="endereco[',endereco_indice,'][cep]" value="" class="span12 cep adt" placeholder="CEP">',
					'<span class="help help-block"></span>',
					'</div>',
					'</div>',
					'<div class="control-group">',
					'<div class="controls controls-row">',
					'<input type="text" name="endereco[',endereco_indice,'][rua]" value="" class="span9 upper adt" placeholder="Rua">',
					'<input type="text" name="endereco[',endereco_indice,'][numero]" value="" class="span3 upper adt" placeholder="Número">',
					'<span class="help help-block"></span>',
					'</div>',
					'</div>',
					'<div class="control-group">',
					'<div class="controls controls-row">',
					'<input type="text" name="endereco[',endereco_indice,'][bairro]" value="" class="span12 upper adt" placeholder="Bairro">',
					'<span class="help help-block"></span>',
					'</div>',
					'</div>',
					'<div class="control-group">',
					'<div class="controls controls-row">',
					'<select name="endereco[',endereco_indice,'][uf]" class="span4 adt">',
					'<option value="">UF</option>',
					'<option value="AC">AC</option>',
					'<option value="AL">AL</option>',
					'<option value="AP">AP</option>',
					'<option value="AM">AM</option>',
					'<option value="BA">BA</option>',
					'<option value="CE">CE</option>',
					'<option value="DF">DF</option>',
					'<option value="ES">ES</option>',
					'<option value="GO">GO</option>',
					'<option value="MA">MA</option>',
					'<option value="MT">MT</option>',
					'<option value="MS">MS</option>',
					'<option value="MG">MG</option>',
					'<option value="PA">PA</option>',
					'<option value="PB">PB</option>',
					'<option value="PR">PR</option>',
					'<option value="PE">PE</option>',
					'<option value="PI">PI</option>',
					'<option value="RJ">RJ</option>',
					'<option value="RN">RN</option>',
					'<option value="RS">RS</option>',
					'<option value="RO">RO</option>',
					'<option value="RR">RR</option>',
					'<option value="SC">SC</option>',
					'<option value="SP">SP</option>',
					'<option value="SE">SE</option>',
					'<option value="TO">TO</option>',
					'</select>',
					'<input type="text" name="endereco[',endereco_indice,'][cidade]" value="" class="span8 upper adt" placeholder="Cidade">',
					'<span class="help help-block"></span>',
					'</div>',
					'</div>',
					'<div class="control-group">',
					'<div class="controls controls-row">',
					'<input type="text" name="endereco[',endereco_indice,'][complemento]" value="" class="span12 upper adt" placeholder="Complemento">',
					'<span class="help help-block"></span>',
					'</div>',
					'</div>',
					'<hr class="featurette-divider">',
					'</fieldset>',
				].join('');

				$('.enderecos').find('.recipiente').append(template);

				endereco_indice++;

				// adiciona email
			}else if($(this).data('campos') == 'email'){

				var email_indice = <?php echo $indice_email ?>;
				console.log(email_indice);
				var template = [
					'<fieldset class="email"  id="',email_indice,'">',
					'<div class="control-group">',
					'<label class="control-label"><a href="#" title="Remover" class="remover"><i class="icon-minus-sign icon-red"></i></a></label>',
					'<div class="controls controls-row">',
					'<input type="hidden" name="email[',email_indice,'][status]" value= "0"/>',
					'<input type="text" name="email[',email_indice,'][emails]" value="" class="span5 lower adt" placeholder="E-mail">',
					'<select name="email[',email_indice,'][setor]" class="span3 adt">',
					'<option value="">Setor</option>',
					'<option value="0">Financeiro</option>',
					'<option value="1">Diretoria</option>',
					'<option value="2">Suporte</option>',
					'<option value="2">Pessoal</option>',
					'</select>',
					'<input type="text" name="email[',email_indice,'][observacao]" value="" class="span4 upper adt" placeholder="observação">',
					'<span class="help help-block"></span>',
					'</div>',
					'</div>',
					'</fieldset>'
				].join('');

				$('.emails').find('.recipiente').append(template);

				email_indice++;

			} else if($(this).data('campos') == 'emailAtendente') {
				var email_indice = <?php echo $indice_email ?>;
				console.log(email_indice);
				var template = [
					'<fieldset class="email"  id="',email_indice,'">',
					'<div class="control-group">',
					'<label class="control-label"><a href="#" title="Remover" class="remover"><i class="icon-minus-sign icon-red"></i></a></label>',
					'<div class="controls controls-row">',
					'<input type="hidden" name="id_cliente" value="<?php echo $cliente_id ?>" >',
					'<input type="text" name="emailAtendente[emails]" value="" class="span5 lower adt" placeholder="E-mail">',
					'</div>',
					'</div>',
					'</fieldset>'
				].join('');
				$('.emailsAtendendte').find('.recipiente').append(template);
				email_indice++;

			} else if($(this).data('campos') == 'emailsVendedor') {
				var email_indice = <?php echo $indice_email ?>;
				console.log(email_indice);
				var template = [
					'<fieldset class="email"  id="',email_indice,'">',
					'<div class="control-group">',
					'<label class="control-label"><a href="#" title="Remover" class="remover"><i class="icon-minus-sign icon-red"></i></a></label>',
					'<div class="controls controls-row">',
					'<input type="hidden" name="id_cliente" value="<?php echo $cliente_id ?>" >',
					'<input type="text" name="emailsVendedor[emails]" value="" class="span5 lower adt" placeholder="E-mail">',
					'</div>',
					'</div>',
					'</fieldset>'
				].join('');
				$('.emailsVendedor').find('.recipiente').append(template);
				email_indice++;
				// adiciona telefone
			} else if($(this).data('campos') == 'telefone') {

				var telefone_indice = <?php echo $indice_telefone ?>;

				var template = [
					'<fieldset class="telefone"  id="',telefone_indice,'">',
					'<div class="control-group">',
					'<label class="control-label"><a href="#" title="Remover" class="remover"><i class="icon-minus-sign icon-red"></i></a></label>',
					'<div class="controls controls-row" data="setor" data-numero="observacao">',
					'<input type="hidden" name="telefone[',telefone_indice,'][status]" value= "0"/>',
					'<input type="text" name="telefone[',telefone_indice,'][ddd]" value="" class="span2 ddd adt" placeholder="DDD">',
					'<input type="text" name="telefone[',telefone_indice,'][numero]" value="" class="span3 fone adt" placeholder="Número">',
					'<select name="telefone[',telefone_indice,'][setor]" class="span3 adt">',
					'<option value="0">Financeiro</option>',
					'<option value="1">Diretoria</option>',
					'<option value="2">Suporte</option>',
					'<option value="2">Pessoal</option>',
					'</select>',
					'<input type="text" name="telefone[',telefone_indice,'][observacao]" value="" class="span4 upper adt" placeholder="observação">',
					'<span class="help help-block"></span>',
					'</div>',
					'</div>',
					'</fieldset>',
				].join('');

				$('.telefones').find('.recipiente').append(template);

				telefone_indice++;

				// adiciona cartao
			}else if($(this).data('campos') == 'cartao'){

				var cartao_indice = <?php echo $indice_cartao ?>;

				var template = [
					'<fieldset class="cartao" id="',cartao_indice,'">',
					'<div class="control-group">',
					'<label class="control-label"><a href="#" title="Remover" class="remover"><i class="icon-minus-sign icon-red"></i></a></label>',
					'<div class="controls controls-row">',
					'<input type="hidden" name="cartao[',cartao_indice,'][status]" value= "0"/>',
					'<input type="text" name="cartao[',cartao_indice,'][numero]" value="" class="span6 numero_cartao adt" placeholder="Número">',
					'<input type="text" name="cartao[',cartao_indice,'][bandeira]" value="" class="span6 bandeira_cartao adt" placeholder="Bandeira">',
					'<span class="help help-block"></span>',
					'</div>',
					'</div>',
					'<div class="control-group">',
					'<div class="controls controls-row">',
					'<input type="text" name="cartao[',cartao_indice,'][vencimento]" value="" class="span6 vencimento_cartao adt" placeholder="Vencimento">',
					'<input type="text" name="cartao[',cartao_indice,'][codigo]" value="" class="span6 codigo_cartao adt" placeholder="Código">',
					'<span class="help help-block"></span>',
					'</div>',
					'</div>',
					'<div class="control-group">',
					'<div class="controls controls-row">',
					'<input type="text" name="cartao[',cartao_indice,'][nome]" value="" class="span12 upper adt" placeholder="Nome">',
					'<span class="help help-block"></span>',
					'</div>',
					'</div>',
					'<hr class="featurette-divider">',
					'</fieldset>',
				].join('');

				$('.cartoes').find('.recipiente').append(template);

				cartao_indice++;
			}
		});

		// remove um fiedset de campos dependendo do botão clicado dentro dele
		$(document).on('click', '.remover', function(e){
			e.preventDefault();

			$(this).closest('fieldset').remove();
		});

		// apaga um fieldset de campos dependendo do botão clicado dentro dele
		$(document).on('click', '.apagar', function(e){
			e.preventDefault();

			$(this).apagar({
				url: '<?php echo site_url('administrador/clientes/apagar') ?>'
			});
		})

	});

    $(document).on('focus', '.cpf', function(){ $('.cpf').mask('999.999.999-99'); });
    $(document).on('focus', '.cnpj', function(){ $('.cnpj').mask('99-9999999'); });
    $(document).on('focus', '.ie', function(){ $('.ie').mask('99.999.999-9'); });
    $(document).on('focus', '.numero_cartao', function(){ $('.numero_cartao').mask('9999-9999-9999-9999'); });
    $(document).on('focus', '.codigo_cartao', function(){ $('.codigo_cartao').mask('999'); });
    $(document).on('focus', '.vencimento_cartao', function(){ $('.vencimento_cartao').mask('99/99'); });
    $(document).on('focus', '.data', function(){ $('.data').mask('11/11/1111'); });
    $(document).on('focus', '.tempo', function(){ $('.tempo').mask('00:00:00'); });
    $(document).on('focus', '.datatempo', function(){ $('.datatempo').mask('99/99/9999 00:00:00'); });
    $(document).on('focus', '.cep', function(){ $('.cep').mask('99999-999'); });
    $(document).on('focus', '.ddd', function(){ $('.ddd').mask('99'); });
    $(document).on('focus', '.fone', function(){ $('.fone').mask('9999-9999'); });

    $('.cnpj').mask('99-9999999');
    $('.ie').mask('99.999.999-9');
    $('.numero_cartao').mask('9999-9999-9999-9999');
    $('.codigo_cartao').mask('999');
    $('.vencimento_cartao').mask('99/99');
    $('.tempo').mask('00:00:00');
    $('.datatempo').mask('99/99/9999 00:00:00');
    $('.ddd').mask('99');
    $('.fone').mask('9999-9999');
    $('.cpf').mask('999.999.999-99');
    $('.data').mask('99/99/9999');
    $('.cep').mask('99999-999');
    //-----------------------------------------//
    $('.ein').mask('99-9999999');

	$(document).ready(function(){
		$(".resultado").hide();
		$("#clientes").ajaxForm({
			target: '.resultado',
			dataType: 'json',
			success: function(retorno){
				$(".resultado").html(retorno.mensagem);
				$(".resultado").show();
			}
		});

		$('.pessoa_fj').hide();
		$('.salvando').hide();
		$('.visualizando').hide();
		$('.adicionar').hide();

		$("a#visualizando").click(function(){
			$(".adt").attr("readonly", true);
			$(".adt").attr("disabled", "disabled");
			$('.editando').show();
			$('.salvar').hide();
			$('.visualizando').hide();
			$('.adicionar').hide();
		});

		$("a#editando").click(function(){
			$(".adt").attr("readonly", false);
			$(".adt").removeAttr("disabled");
			$('.editando').hide();
			$('.salvar').show();
			$('.visualizando').show();
			$('.adicionar').show();
		});
	});
</script>
