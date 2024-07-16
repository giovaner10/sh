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
	$cliente_opentech = $cliente_dado->opentech;
	$cliente_orgao = $cliente_dado->orgao;
	$excessoVia = $cliente_dado->velocidade_via;
}

$msg = false;
if(isset($cliente_receita)&&$cliente_receita->status != "ERROR" ){
	$cliente_nome == $cliente_receita->fantasia ? '' : $msg = 'O nome do cliente foi atualizado no banco de dados da Receita Federal. Clique em atualizar cadastro e salve as alterações!';
	$cliente_razao == $cliente_receita->nome ? '' : $msg = 'A razão social do cliente foi atualizada no banco de dados da Receita Federal. Clique em atualizar cadastro e salve as alterações!';
	if ($cliente_enderecos && count($cliente_enderecos) > 0) {
		$cliente_enderecos[0]->rua == $cliente_receita->logradouro ? '' : $msg = 'O endereço do cliente foi atualizado no banco de dados da Receita Federal. Clique em atualizar cadastro e salve as alterações!';
		$cliente_enderecos[0]->cidade == $cliente_receita->municipio ? '' : $msg = 'O municipio do cliente foi atualizado no banco de dados da Receita Federal. Clique em atualizar cadastro e salve as alterações!';
	}
	if ($cliente_emails && count($cliente_emails) > 0 && $cliente_receita->email != '') {
		$cliente_emails[0]->email == $cliente_receita->email ? '' : $msg = 'O email de contato do cliente foi atualizado no banco de dados da Receita Federal. Clique em atualizar cadastro e salve as alterações!' ;
	}
	if ($msg) {
		echo "
			<div class='alert alert-warning' id='mens'>
				<strong>Atualização: </strong> ".$msg."
			</div>
		";
	}
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
<style>.mostra-campo{display: none}</style>

<div class="resultado span6" style="float: none;">
</div>

<div class="row-fluid">
	<form action="<?php echo site_url('cadastros/atualizar_cliente/'.$cliente_id) ?>" method="post" class="form-horizontal formulario" id="clientes">
		<div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="tab1 active"><a href="#tab1" data-toggle="tab">Dados</a></li>
				<li class="tab2"><a href="#tab2" data-toggle="tab">Cartões</a></li>
				<li class="tab3"><a href="#tab3" data-toggle="tab">Endereços</a></li>
				<li class="tab4"><a href="#tab4" data-toggle="tab">Contatos</a></li>
				<li class="tab5"><a href="#tab5" data-toggle="tab">Atendimento</a></li>
				<li class="tab6"><a href="#tab6" data-toggle="tab">Impostos</a></li>
				<li class="tab7"><a href="#tab7" data-toggle="tab">Plano e Permissões</a></li>
			</ul>
			<!--  tabbable nav  -->

			
			<div class="tab-content">
				<div class="btn-group pull-right">
					<a style="margin: 10px;" class="btn btn-info visualizando" title="Atualizar Cadastro RF" onclick="buscaCnpj()">Atualizar Cadastro</a>
					<a style="margin: 10px;" id="visualizando" class="btn btn-danger visualizando" title="Visualizar"><i class="icon-eye-open icon-white"></i> Visualizar</a>
					<button style="margin: 10px;" type="submit" class="salvar btn btn-primary salvando" title="Salvar alterações"><i class="icon-download-alt icon-white"></i> Salvar</button>
				</div>

				<div class="btn-group pull-right">
					<a id="editando" class="btn btn-success editando" title="Editar"><i class="icon-pencil icon-white"></i> Editar</a>
				</div>

				<br><br>

				<div class="tab-pane active" id="tab1">
					<div class="row dados">
						<div class="span8">
							<fieldset class="cliente">

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
										<input id="nome" type="text" name="cliente[nome]" readonly="readonly" value="<?php echo $cliente_nome ?>" class="span12 upper adt <?= $cliente_nome ?>">
										<span class="help help-block"></span>
									</div>
								</div>

								<div class="fisica">
									<div class="control-group">
										<label class="control-label">Identidade:</label>
										<div class="controls controls-row">
											<input type="text" name="cliente[rg]" readonly="readonly" value="<?php echo $cliente_rg ?>" class="span7 numero adt" placeholder="Número">
											<input type="text" name="cliente[rg_orgao]" readonly="readonly" value="<?php echo $cliente_rg_orgao ?>" class="span5 upper adt" placeholder="Orgão Expedidor">
											<span class="help help-block"></span>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">CPF:<sup>*</sup></label>
										<div class="controls controls-row">
											<input type="text" name="cliente[cpf]" readonly="readonly" value="<?php echo $cliente_cpf ?>" class="span12 cpf adt">
											<span class="help help-block"></span>
										</div>
									</div>
								</div>

								<div class="juridica">
									<div class="control-group">
										<label class="control-label">Razão Social:<sup>*</sup></label>
										<div class="controls controls-row">
											<input id="razao_social" type="text" name="cliente[razao_social]" readonly="readonly" value="<?php echo $cliente_razao ?>" class="span12 upper adt">
											<span class="help help-block"></span>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">CNPJ:<sup>*</sup></label>
										<div class="controls controls-row">
											<input type="text" id="cnpj" name="cliente[cnpj]" readonly="readonly" value="<?php echo $cliente_cnpj ?>" class="span12 cnpj">
											<span class="help help-block"></span>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">Inscrição Estadual:</label>
										<div class="controls controls-row">
											<input type="text" name="cliente[ie]" readonly="readonly" value="<?php echo $cliente_ie ?>" class="span12 ie adt">
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
											<option id="omnilink" value="OMNILINK">OMNILINK</option>
											<option id="sigamy" value="SIGAMY">SIGAMY</option>
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
								<div class="control-group">
									<label class="control-label">Opções:</label>
									<div class="controls controls-row">
										<label class="checkbox inline"><input type="checkbox" class="adt" readonly="readonly" <?php if($cliente_opentech){echo "checked";}?> name="opentech">Opentech</label>
										<label class="checkbox inline"><input type="checkbox" class="adt" readonly="readonly" <?= $excessoVia ? 'checked' : '' ?> name="excessoVia">Exc. Velocidade em Via</label>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Orgão:</label>
									<div class="controls controls-row">
										<select name="cliente[orgao]" class="span12 upper adt" readonly="readonly">
											<?php if ($cliente_orgao=='privado'):?>
												<option value="privado">Privado</option>
												<option value="publico">Público</option>
											<?php else:?>
												<option value="publico">Público</option>
												<option value="privado">Privado</option>
											<?php endif;?>
										</select>
										<span class="help help-block"></span>
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

									<fieldset class="cartao">
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

								<fieldset class="cartao editando">
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

									<fieldset class="endereco">
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
												<input id="cep" type="text" name="endereco[<?php echo $indice_endereco ?>][cep]" readonly="readonly" value="<?php echo $cliente_endereco->cep ?>" class="span12 cep adt" placeholder="CEP">
												<span class="help help-block"></span>
											</div>
										</div>
										<div class="control-group">
											<div class="controls controls-row">
												<input id="rua" type="text" name="endereco[<?php echo $indice_endereco ?>][rua]" readonly="readonly" value="<?php echo $cliente_endereco->rua ?>" class="span9 upper adt" placeholder="Rua">
												<input id="numero" type="text" name="endereco[<?php echo $indice_endereco ?>][numero]" readonly="readonly" value="<?php echo $cliente_endereco->numero ?>" class="span3 upper adt" placeholder="Número">
												<span class="help help-block"></span>
											</div>
										</div>
										<div class="control-group">
											<div class="controls controls-row">
												<input id="bairro" type="text" name="endereco[<?php echo $indice_endereco ?>][bairro]" readonly="readonly" value="<?php echo $cliente_endereco->bairro ?>" class="span12 upper adt" placeholder="Bairro">
												<span class="help help-block"></span>
											</div>
										</div>
										<div class="control-group">
											<div class="controls controls-row">
												<select id="uf" name="endereco[<?php echo $indice_endereco ?>][uf]" readonly="readonly" class="span4 adt">
													<option value="<?php echo $cliente_endereco->uf ?>"><?php echo $cliente_endereco->uf ?></option>
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
													<option value="MT">MT</option>
													<option value="MS">MS</option>
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
												<input id="cidade" type="text" name="endereco[<?php echo $indice_endereco ?>][cidade]" readonly="readonly" value="<?php echo $cliente_endereco->cidade ?>" class="span8 upper adt" placeholder="Cidade">
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

								<fieldset class="cartao editando">
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

									<fieldset class="email">
										<div class="control-group">
											<div class="controls controls-row">
												<input type="hidden" name="email[<?php echo $indice_email ?>][status]" value= '1'/>
												<input type="hidden" name="email[<?php echo $indice_email ?>][id]" value= '<?php echo $cliente_email->id ?>'/>
												<input id="e-mail" type="text" name="email[<?php echo $indice_email ?>][emails]" readonly="readonly" value="<?php echo $cliente_email->email ?>" class="span5 lower adt" placeholder="E-mail">
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

								<fieldset class="cartao editando">
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

									<fieldset class="telefone">
										<div class="control-group">
											<div class="controls controls-row">
												<input type="hidden" name="telefone[<?php echo $indice_telefone ?>][status]" value= '1'/>
												<input type="hidden" name="telefone[<?php echo $indice_telefone ?>][id]" value= '<?php echo $cliente_telefone->id ?>'/>
												<input id="ddd" type="text" name="telefone[<?php echo $indice_telefone ?>][ddd]" readonly="readonly" value="<?php echo $cliente_telefone->ddd ?>" class="span2 ddd adt" placeholder="DDD">
												<input id="tel" type="text" name="telefone[<?php echo $indice_telefone ?>][numero]" readonly="readonly" value="<?php echo $cliente_telefone->numero ?>" class="span3 adt" placeholder="Número">
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

								<fieldset class="cartao editando">
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
				<div id="tab5" class="tab-pane">

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

									<fieldset class="email">
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

								<fieldset class="cartao editando">
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
							<?php if ($vendedores): ?>
								<?php foreach ($vendedores as $atendente): ?>

									<fieldset class="email">
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

								<fieldset class="cartao editando">
									<div class="controls controls-row">
										<div class="alert alert-block">
											<h4>Não há e-mails cadastrados</h4>
										</div>
									</div>
								</fieldset>

							<?php endif; ?>
						</div>
					</div><!-- /row emails -->
				</div>

				<div  id="tab6" class="tab-pane">
					<div class="nota_fiscal">
						<div class="control-group br">
							<label class="control-label">Nota Fiscal:</label>
							<div class="controls controls-row">
								<input type="text" name="cliente[cod_servico]" readonly="readonly" value="<?php echo $cliente_dados[0]->cod_servico ?>" class="span3 adt" placeholder="Código do serviço">
								<input type="text" name="cliente[descriminacao_servico]" readonly="readonly" value="<?php echo $cliente_dados[0]->descriminacao_servico ?>" class="span6 adt" maxlength="100" placeholder="Descrição do serviço">
								<span class="help help-block"></span>
							</div>
						</div>
					</div>
					<div>
						<div class="control-group pull-left">
							<label class="control-label">IRPJ</label>
							<div class="controls controls-row">
								<input type="" value="<?= $cliente_dados[0]->IRPJ ? $cliente_dados[0]->IRPJ : 0 ?>" name="irpj" style="size: 5px;">
							</div>
							<label class="control-label">Contribuição Social</label>
							<div class="controls controls-row">
								<input type="" value="<?= $cliente_dados[0]->Cont_Social ? $cliente_dados[0]->Cont_Social : 0 ?>" name="csll" style="size: 5px;">
							</div>
							<label class="control-label">PIS</label>
							<div class="controls controls-row">
								<input type="" value="<?= $cliente_dados[0]->PIS ? $cliente_dados[0]->PIS : 0 ?>" name="pis" style="size: 5px;">
							</div>
							<label class="control-label">COFINS</label>
							<div class="controls controls-row">
								<input type="" value="<?= $cliente_dados[0]->COFINS ? $cliente_dados[0]->COFINS : 0 ?>" name="cofins" style="size: 5px;">
							</div>
							<label class="control-label">ISS</label>
							<div class="controls controls-row">
								<input type="" value="<?= $cliente_dados[0]->ISS ? $cliente_dados[0]->ISS : 0 ?>" name="iss" style="size: 5px;">
							</div>
						</div>

						<div style="width: 350px; margin-left: 5px; float: left;">
							<div class="panel panel-default">
								<div class="panel-body" style="text-align: center;">
									<div>
										<img id="blah" src="#" style="height: 80px;"/>
									</div>
									<div>
										<input type='file' id="imgInp" name="image"/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div  id="tab7" class="tab-pane">
					<label>Observações:</label>

					<div style="padding: 10px 0px 10px 0px">
						<textarea class="form-control adt" readonly="readonly" disabled="true" style="width: 100%" placeholder="Digite aqui as observações" rows="5" name="observacoes"><?= $cliente_dados[0]->observacoes ?></textarea>
					</div>

					<div>
						<label>Produto:</label>

						<select class="form-control select_produto adt" readonly="readonly" disabled="true" name="id_produto">							
							<option value="" selected disabled hidden>Selecione um produto</option>
							<?php foreach ($produtos as $produto): ?>
								<option value="<?= $produto->id ?>" <?= $cliente_dados[0]->id_produto == $produto->id ? 'selected' : '' ?>><?= $produto->nome ?></option>
							<?php endforeach; ?>
						</select>

						<span id='msgPermissoes'></span>
					</div>

					<br>

					<input type="hidden" id="planos_nomes" name="planos_nomes"/>

					<div class="span6">
						<label for="permissoes">Permissões do Produto Selecionado:</label>

						<select id="permissoes" name="permissoes[]" class="adt" readonly="readonly" multiple="multiple">
							<?php if ($cliente_dados[0]->id_produto): ?>
								<?php
									foreach ($permissoes as $permissao):
										echo $permissao;
									endforeach;
								?>
							<?php endif; ?>

						</select>
					</div>

					<div class="span5">
						<label for="permissoesExtras">Permissões Extras:</label>

						<select id="permissoesExtras" name="permissoes[]" class="adt" readonly="readonly" multiple="multiple">
							<?php
								foreach ($permissoesExtras as $permissao):
									echo $permissao;
								endforeach;
							?>
						</select>
					</div>

				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">

	// Permissões atuais do cliente
	var permissoes_client = JSON.parse('<?= $cliente_dados[0]->permissoes ? $cliente_dados[0]->permissoes : json_encode(array()) ?>');
	
	var id_produto_cliente = '<?= $cliente_dados[0]->id_produto ?>';

	var planos_escolhidos = JSON.parse('<?= $planos_cliente ? $planos_cliente : json_encode(array()) ?>');
	
	var treePermissoes = $("#permissoes").treeMultiselect({
		searchable: true,
		startCollapsed: true,
		onChange: treeOnChange,
	});

	var treePermissoesExtras = $("#permissoesExtras").treeMultiselect({
		searchable: true,
		startCollapsed: true,
	});

	function treeOnChange(allSelectedItems, addedItems, removedItems) {
		if(addedItems[0]){
			editavel = $("#permissoes option[data-key='"+addedItems[0].id+"']").data("editavel");
		}else if(removedItems[0]){
			editavel = $("#permissoes option[data-key='"+removedItems[0].id+"']").data("editavel");
		}
		
		if(typeof editavel !== 'undefined' && editavel == '0'){
			//deixar todas as permissoes do plano marcadas
			if(addedItems[0]){
				secao_adicionada = addedItems[0].section.split('/')[0];
				
				addedItems.forEach(item => {
					$("#permissoes option[data-key='"+item.id+"']").attr('selected','selected');
					$("#permissoesExtras option[value='"+item.value+"']").removeAttr('selected');
					$("#permissoesExtras option[value='"+item.value+"']").attr('readonly','readonly');
				});

				//seleciona seções da primeira arvore
				$('.selections').first().children('.section').each(function () {
					filho = $(this).children('.title')[0];
					secao_nome = $(filho).contents()[2].nodeValue;

					if(secao_adicionada == secao_nome){
						let options = $("#permissoes option[data-section*='"+secao_nome+"']").attr('selected','selected');

						options.each(function(){
							value = $(this).val();
							$("#permissoesExtras option[value='"+value+"']").removeAttr('selected');
							$("#permissoesExtras option[value='"+value+"']").attr('readonly','readonly');
						});

						let firstTree = treePermissoes[0];
						firstTree.remove();
			
						treePermissoes = $("#permissoes").treeMultiselect({
							searchable: true,
							startCollapsed: true,
							onChange: treeOnChange,
						});
					}
				});

				let secondTree = treePermissoesExtras[0];
				secondTree.remove();
	
				treePermissoesExtras = $("#permissoesExtras").treeMultiselect({
					searchable: true,
					startCollapsed: true,
				});
			}

			//deixar nenhuma permissao do plano marcada
			if(removedItems[0]){
				secao_removida = removedItems[0].section.split('/')[0];
				
				removedItems.forEach(item => {
					$("#permissoes option[data-key='"+item.id+"']").removeAttr('selected');
					$("#permissoesExtras option[value='"+item.value+"']").removeAttr('readonly');
				});

				//seleciona seções da primeira arvore
				$('.selections').first().children('.section').each(function () {
					filho = $(this).children('.title')[0];
					secao_nome = $(filho).contents()[2].nodeValue;

					if(secao_removida == secao_nome){

						let options = $("#permissoes option[data-section*='"+secao_nome+"']").removeAttr('selected');

						options.each(function(){
							value = $(this).val();
							$("#permissoesExtras option[value='"+value+"']").removeAttr('readonly');
						});

						let firstTree = treePermissoes[0];
						firstTree.remove();
			
						treePermissoes = $("#permissoes").treeMultiselect({
							searchable: true,
							startCollapsed: true,
							onChange: treeOnChange,
						});
					}
				});

				let secondTree = treePermissoesExtras[0];
				secondTree.remove();
	
				treePermissoesExtras = $("#permissoesExtras").treeMultiselect({
					searchable: true,
					startCollapsed: true,
				});
			}
		}else if(typeof editavel !== 'undefined' && editavel == '1'){
			//adicionar selected das opcoes adicionadas no select permissoes
			if(addedItems[0]){
				secao_adicionada = addedItems[0].section.split('/')[0];
				
				addedItems.forEach(item => {
					$("#permissoes option[data-key='"+item.id+"']").attr('selected','selected');
					$("#permissoesExtras option[value='"+item.value+"']").removeAttr('selected');
					$("#permissoesExtras option[value='"+item.value+"']").attr('readonly','readonly');
				});

				let secondTree = treePermissoesExtras[0];
				secondTree.remove();
	
				treePermissoesExtras = $("#permissoesExtras").treeMultiselect({
					searchable: true,
					startCollapsed: true,
				});
			}

			//remover selected das opcoes removidas no select permissoes
			if(removedItems[0]){
				secao_removida = removedItems[0].section.split('/')[0];
				
				removedItems.forEach(item => {
					$("#permissoes option[data-key='"+item.id+"']").removeAttr('selected');
					$("#permissoesExtras option[value='"+item.value+"']").removeAttr('readonly');
				});

				let secondTree = treePermissoesExtras[0];
				secondTree.remove();
	
				treePermissoesExtras = $("#permissoesExtras").treeMultiselect({
					searchable: true,
					startCollapsed: true,
				});
			}
		}

		//salvar nomes dos planos marcados
		if(addedItems[0]){
			secao_adicionada = addedItems[0].section.split('/')[0];
			index = planos_escolhidos.indexOf(secao_adicionada);

			if(index == -1){
				planos_escolhidos.push(secao_adicionada);
				$('#planos_nomes').val(JSON.stringify(planos_escolhidos));
			}
		}else if (removedItems[0] && typeof editavel !== 'undefined' && editavel == '0'){
			secao_removida = removedItems[0].section.split('/')[0];
			index = planos_escolhidos.indexOf(secao_removida);
			planos_escolhidos.splice(index, 1 );
			$('#planos_nomes').val(JSON.stringify(planos_escolhidos));
		}else if(removedItems[0]){
			secao_removida = removedItems[0].section.split('/')[0];
			
			planos_selecionados = [];
			allSelectedItems.forEach(item => {
				secao = item.section.split('/')[0];
				index = planos_selecionados.indexOf(secao);

				if(index == -1){
					planos_selecionados.push(secao);
				}
			});

			if(planos_selecionados.includes(secao_removida) == false){
				index = planos_escolhidos.indexOf(secao_removida);
				planos_escolhidos.splice(index, 1 );
				$('#planos_nomes').val(JSON.stringify(planos_escolhidos));
			}

		}
	}
	
	/**
	 * Função Seleciona todas as opções de permissão do produto selecionado
	 */
	$(document).on('change', '.select_produto', function() {
		if (typeof $(this).val() !== 'undefined' && $(this).val() !== '') {
			let id_produto = $(this).val();

			$('#msgPermissoes').html(' <i class="fa fa-spinner fa-spin"></i> Carregando...');

			$.ajax({
                url: '<?= site_url('clientes/get_permissoes_produto/'); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
					id_produto: id_produto,
					id_cliente: '<?= $cliente_dados[0]->id ?>',
					permissoes: permissoes_client
				},
                success: function (callback)
                {
					$("#permissoes").empty();
					$("#permissoesExtras").empty();

					if(callback.status == true){
						$.each(callback.permissoes, function(i, option) {
							$("#permissoes").append(option);
						});

						$('#msgPermissoes').html('');
					}else{
						$('#msgPermissoes').html(' Esse produto não possui planos vinculados, mas você pode selecionar permissões extras dos módulos cadastrados.');
					}

					//permissoes do produto
					let firstTree = treePermissoes[0];
					firstTree.remove();

					treePermissoes = $("#permissoes").treeMultiselect({
						searchable: true,
						startCollapsed: true,
						onChange: treeOnChange,
					});

					//permissoes extras
					$.each(callback.permissoesExtras, function(i, option) {
						$("#permissoesExtras").append(option);
					});

					let secondTree = treePermissoesExtras[0];
					secondTree.remove();

					treePermissoesExtras = $("#permissoesExtras").treeMultiselect({
						searchable: true,
						startCollapsed: true,
					});
                }
            })
		}
	});
	
    function buscaCnpj() {
        var cnpj = document.getElementById("cnpj").value.replace('.', '').replace('/', '').replace('-', '').replace('.', '');
        var url = '../../cadastros/consulta_cnpj/' + cnpj;
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            contentType: 'application/json',
            success: function(data) {
                if (data.status == "OK") {
                    data.nome != '' ? document.getElementById("razao_social").value = data.nome : '';
                    data.fantasia != ''? document.getElementById("nome").value = data.fantasia : '';
                    data.cep != '' ? document.getElementById("cep").value = data.cep : '';
                    data.logradouro != '' ? document.getElementById("rua").value = data.logradouro : '';
                    data.numero != '' ? document.getElementById("numero").value = data.numero : '';
                    data.bairro != '' ? document.getElementById("bairro").value = data.bairro : '';
                    data.municipio != '' ? document.getElementById("cidade").value = data.municipio : '';
                    $("#estado").val(data.uf);

                    var telefone = data.telefone;
                    if (telefone != '') {
                        var removeParentesesTelefone = telefone.replace(/\(|\)/g, '');
                        var removeSeparadorTelefone = removeParentesesTelefone.replace("-", "");
                        var removeEspacoTelefone = removeSeparadorTelefone.replace(" ", "");
                        var dddTelefone = removeParentesesTelefone.substring(0, 2);
                        var numeroTelefone = removeEspacoTelefone.substring(2, 10);
                    } else {
                        var dddTelefone = '';
                        var numeroTelefone = '';
                    }

                    dddTelefone != '' ? document.getElementById("ddd").value = dddTelefone : '';
                    numeroTelefone != '' ? document.getElementById("tel").value = numeroTelefone : '';
                    data.email != '' ? document.getElementById("e-mail").value = data.email : '';
                }
            }
        });
    }

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
					'<input type="text" id="cidade" name="endereco[',endereco_indice,'][cidade]" value="" class="span8 upper adt" placeholder="Cidade">',
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
				var template = [
					'<fieldset class="email"  id="',email_indice,'">',
					'<div class="control-group">',
					'<label class="control-label"><a href="#" title="Remover" class="remover"><i class="icon-minus-sign icon-red"></i></a></label>',
					'<div class="controls controls-row">',
					'<input type="hidden" name="email[',email_indice,'][status]" value= "0"/>',
					'<input id ="email" type="text" name="email[',email_indice,'][emails]" value="" class="span5 lower adt" placeholder="E-mail">',
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
	$(document).on('focus', '.cnpj', function(){ $('.cnpj').mask('99.999.999/9999-99'); });
	$(document).on('focus', '.ie', function(){ $('.ie').mask('99.999.99-9'); });
	$(document).on('focus', '.numero_cartao', function(){ $('.numero_cartao').mask('9999-9999-9999-9999'); });
	$(document).on('focus', '.codigo_cartao', function(){ $('.codigo_cartao').mask('999'); });
	$(document).on('focus', '.vencimento_cartao', function(){ $('.vencimento_cartao').mask('99/99'); });
	$(document).on('focus', '.data', function(){ $('.data').mask('11/11/1111'); });
	$(document).on('focus', '.tempo', function(){ $('.tempo').mask('00:00:00'); });
	$(document).on('focus', '.datatempo', function(){ $('.datatempo').mask('99/99/9999 00:00:00'); });
	$(document).on('focus', '.cep', function(){ $('.cep').mask('99.999-999'); });
	$(document).on('focus', '.ddd', function(){ $('.ddd').mask('99'); });
	$(document).on('focus', '.fone', function(){ $('.fone').mask('9999-9999'); });
	$(document).on('focus', '.ip', function(){ $('.ip').mask('999.999.999.999'); });
	$(document).on('focus', '.porta', function(){ $('.porta').mask('9999'); });

	$('.cnpj').mask('99.999.999/9999-99');
	$('.ie').mask('99.999.99-9');
	$('.numero_cartao').mask('9999-9999-9999-9999');
	$('.codigo_cartao').mask('999');
	$('.vencimento_cartao').mask('99/99');
	$('.tempo').mask('00:00:00');
	$('.datatempo').mask('99/99/9999 00:00:00');
	$('.ddd').mask('99');
	$('.fone').mask('9999-9999');
	$('.cpf').mask('999.999.999-99');
	$('.data').mask('99/99/9999');
	$('.cep').mask('99.999-999');
	$('.ip').mask('999.999.999.999');
	$('.porta').mask('9999');

	$(document).ready(function(){
		$('#planos_nomes').val(JSON.stringify(planos_escolhidos));

		$(".resultado").hide();
		
		$("#clientes").ajaxForm({
			target: '.resultado',
			dataType: 'json',
			success: function(retorno){
				$(".resultado").html(retorno.mensagem);
				$("#mens").hide();
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
			let firstTree = treePermissoes[0];
			firstTree.remove();

			treePermissoes = $("#permissoes").treeMultiselect({
				searchable: true,
				startCollapsed: true,
				onChange: treeOnChange,
			});

			firstTree = treePermissoesExtras[0];
			firstTree.reload();

			$('.editando').show();
			$('.salvar').hide();
			$('.visualizando').hide();
			$('.adicionar').hide();
		});

		$("a#editando").click(function(){
			
			$(".adt").attr("readonly", false);
			$(".adt").removeAttr("disabled");
			
			let firstTree = treePermissoes[0];
			firstTree.remove();

			treePermissoes = $("#permissoes").treeMultiselect({
				searchable: true,
				startCollapsed: true,
				onChange: treeOnChange,
			});

			firstTree = treePermissoesExtras[0];
			firstTree.reload();

			$('.editando').hide();
			$('.salvar').show();
			$('.visualizando').show();
			$('.adicionar').show();
		});
	});
</script>
