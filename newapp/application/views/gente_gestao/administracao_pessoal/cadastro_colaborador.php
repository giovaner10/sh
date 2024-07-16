<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<form id="formularioColaborador" name="formularioColaborador">
	            	<div id="print"> <!----- print ------>

						<div class="form-group">
							<h4 class="page-header text-center">
								<strong><?=lang("termo_responsabilidade_revisao_cadastro")?></strong>
							</h4>
						</div>
						<div class="form-group">
							<div class="row" id="topo">
								<div class="col-xs-12">
									<input type="hidden" class="form-control" name="colaboradorId" value="<?=isset($colaborador->id) ? $colaborador->id : ''?>" />
									<input type="hidden" class="form-control" name="usuarioId"
										value="<?=isset($colaborador->id_usuario) ? $colaborador->id_usuario : $this->auth->get_login_dados()["user"]?>" />
									<?=str_replace("__cpf__", $colaborador->cpf,
										str_replace("__nome__", $colaborador->nome,
											lang("formulario_adp_descricao")
										)
									);?>
								</div>
							</div>
							<div class="row" id="topo">
								<div class="col-xs-12">
									<p>
										<span style="color: #F00;">(*) <?=lang("preenchimento_obrigatorio")?>.</span>
									</p>
								</div>
							</div>
						</div>
						<div class="form-group">
							<table class="table">
								<thead>
									<tr>
										<th style="background-color: #03A9F4; color: #FFF;"
											class="col-xs-1 text-center"><?=lang("aba")?></th>
										<th style="background-color: #03A9F4; color: #FFF;"
											class="col-xs-2 text-center"><?=lang("informacao")?></th>
										<th style="background-color: #03A9F4; color: #FFF;"
											class="col-xs-6 text-center"><?=lang("alterar_corrigir_para")?></th>
										<th style="background-color: #03A9F4; color: #FFF;"
											class="col-xs-3 text-center"><?=lang("comprovante_necessario")?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td rowspan="9" style="background-color: #03A9F4; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center"><div style="width: 150px;">INF. PESSOAIS</div></td>
										<td class="text-right">
											<h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("nome_completo")?>
											</h5></td>
										<td><input type="text" class="form-control letras" name="nome" value="<?=isset($colaborador->nome) ? $colaborador->nome : ''?>" required /></td>
										<td><?=lang("copia_rg_atualizado")?></td>
									</tr>
									<tr>
										<td class="text-right">
											<h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("sexo")?>
											</h5>
										</td>
										<td>
										<select class="form-control" name="sexo" required>
											<option value="" selected><?=lang("selecione")?></option>
											<option value="F" <?=isset($colaborador->sexo) && $colaborador->sexo == "F" ? "selected" : ''?> ><?=lang("feminino")?></option>
											<option value="M" <?=isset($colaborador->sexo) && $colaborador->sexo == "M" ? "selected" : ''?> ><?=lang("masculino")?></option>
										</select></td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class="text-right">
											<h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("data_nascimento")?>
											</h5></td>
										<td>
											<input type="date" id="data_nascimento" max="<?=date('Y-m-d');?>" class="form-control" name="data_nascimento" value="<?=isset($colaborador->data_nascimento) ? $colaborador->data_nascimento : ''?>" required /></td>
										<td></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("estado_civil")?>
											</h5>
										</td>
										<td>
											<select class="form-control" name="id_estado_civil" required>
												<option value=""><?=lang("selecione")?></option>
												<?php foreach ($estadosCivis as $estadoCivil) : ?>
													<option value="<?=$estadoCivil->id?>"
														<?=isset($colaborador->id_estado_civil)
															&& $colaborador->id_estado_civil == $estadoCivil->id
															? 'selected' : ''?>
													>
														<?=$estadoCivil->descricao?>
													</option>
												<?php endforeach; ?>
											</select>
										</td>
										<td><?=lang("documento_correspondente_certidao")?></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("grau_instrucao")?>
											</h5></td>
										<td>
										<select class="form-control" name="id_escolaridade" required >
											<option value=""><?=lang("selecione")?></option>
											<?php foreach ($escolaridades as $escolaridade) : ?>
												<option value="<?=$escolaridade->id?>"
													<?=isset($colaborador->id_escolaridade)
														&& $colaborador->id_escolaridade == $escolaridade->id
														? 'selected' : ''?>
												>
													<?=$escolaridade->descricao?>
												</option>
											<?php endforeach; ?>
										</select></td>
										<td><?=lang("comprovante_escolaridade")?></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("naturalidade")?>
											</h5></td>
										<td><input type="text" class="form-control letras"
											name="naturalidade" value="<?=isset($colaborador->naturalidade) ? $colaborador->naturalidade : ''?>" required /></td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
											<span style="color: #F00;">*</span><?=lang("nacionalidade")?>
											</h5></td>
										<td>
										<select class="form-control" name="nacionalidade" required >
											<option value="Brasileira" <?=isset($colaborador->nacionalidade) && $colaborador->nacionalidade == "Brasileira" ? "selected" : ''?> >Brasileira</option>
											<option value="Estrangeira" <?=isset($colaborador->nacionalidade) && $colaborador->nacionalidade == "Estrangeira" ? "selected" : ''?> >Estrangeira</option>
										</select></td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("nome_pai")?>
											</h5></td>
										<td>
										<input type="text" class="form-control letras" name="pai" value="<?=isset($colaborador->pai) ? $colaborador->pai : ''?>" required /></td>
										<td rowspan="2"><?=lang("copia_rg_atualizado")?></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("nome_mae")?>
											</h5></td>
										<td><input type="text" class="form-control letras" name="mae" value="<?=isset($colaborador->mae) ? $colaborador->mae : ''?>" required /></td>
									</tr>
									<tr>
										<td rowspan="22" style="background-color: #03A9F4; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;"
											align="center"><div style="width: 100%">DOC.</div></td>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span>CPF
											</h5></td>
										<td><input type="text" class="form-control" maxlength="11" onblur="verificaCPF(this)" name="cpf" id="cpf" value="<?=isset($colaborador->cpf) ? $colaborador->cpf : ''?>" required /></td>
										<td><?=lang("copia_documento_com_cpf")?></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("identidade")?>
											</h5></td>
										<td>
										<input type="text" class="form-control" id="rg" name="rg" value="<?=isset($colaborador->rg) ? $colaborador->rg : ''?>" required /></td>&nbsp;
										<td rowspan="4"><?=lang("copia_rg")?></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("orgao_emissor")?>
											</h5></td>
										<td><input type="text" class="form-control letras" name="rg_orgao"	value="<?=isset($colaborador->rg_orgao) ? $colaborador->rg_orgao : ''?>" required /></td>&nbsp;
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("uf")?>
											</h5></td>
										<td>
											<select type="text" class="form-control" name="rg_id_estado" required>
												<option value=""><?=lang("selecione")?></option>
												<?php foreach ($estados as $estado) : ?>
													<option value="<?=$estado->id?>"
														<?=isset($colaborador->rg_id_estado)
															&& $colaborador->rg_id_estado == $estado->id
															? 'selected' : ''?>
													>
														<?=$estado->sigla?>
													</option>
												<?php endforeach; ?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;"><?=lang("expedicao_rg")?></h5></td>
										<td><input type="date" id="rg_expedicao" min="1900-01-01" max="<?=date('Y-m-d');?>" class="form-control" name="rg_expedicao" value="<?=isset($colaborador->rg_expedicao) ? $colaborador->rg_expedicao : ''?>"/>
										</td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;"><?=lang("certificado_reservista")?>
												</h5></td>
										<td><input type="text" class="form-control" id="reservista" name="reservista"
											value="<?=isset($colaborador->reservista) ? $colaborador->reservista : ''?>" /></td>
										<td><?=lang("copia_reservista")?></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span>PIS/PASEP/NIT
											</h5></td>
										<td><input type="text" id="pis" class="form-control" name="pis" value="<?=isset($colaborador->pis) ? $colaborador->pis : ''?>" required /></td>
										<td rowspan="2"><?=lang("copia_cartao_pis")?></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;"><?=lang("expedicao")?>
												PIS/PASEP/NIT</h5></td>
										<td><input type="date" max="<?=date('Y-m-d');?>" class="form-control" name="pis_expedicao" value="<?=isset($colaborador->pis_expedicao) ? $colaborador->pis_expedicao : ''?>" maxlength="10" /></td>
									</tr>
									<tr>
										<td class="text-right">
											<h5 style="font-weight: bold;"><?=lang("habilitacao_categoria")?></h5>
										</td>
										<td><input type="text" class="form-control letras" name="cnh"
											value="<?=isset($colaborador->cnh) ? $colaborador->cnh : ''?>" /></td>
										<td rowspan="6">Cópia CNH</td>
									</tr>
									<tr>
										<td class="text-right">
											<h5 style="font-weight: bold;"><?=lang("validade_cnh")?></h5>
										</td>
										<td><input type="date" class="form-control" min="<?=date('Y-m-d');?>" max="2030-12-31" name="cnh_validade" value="<?=isset($colaborador->cnh_validade) ? $colaborador->cnh_validade : ''?>" maxlength="10" />
										</td>
									<tr>
										<td class="text-right">
											<h5 style="font-weight: bold;"><?=lang("expedicao_cnh")?></h5>
										</td>
										<td><input type="date" class="form-control" min="1900-01-01" max="<?=date('Y-m-d');?>" id="cnh_expedicao" name="cnh_expedicao" value="<?=isset($colaborador->cnh_expedicao) ? $colaborador->cnh_expedicao : ''?>" maxlength="10" />
										</td>
									<tr>
										<td class="text-right">
											<h5 style="font-weight: bold;"><?=lang("orgao_emissor_cnh")?></h5>
											</td>
										<td><input type="text" class="form-control letras" name="cnh_orgao"
											value="<?=isset($colaborador->cnh_orgao) ? $colaborador->cnh_orgao : ''?>" size="3" />
										</td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;"><?=lang("uf_expedidora_cnh")?></h5></td>
										<td>
											<select name="cnh_id_estado" class="form-control">
												<option value=""><?=lang("selecione")?></option>
												<?php foreach ($estados as $estado) : ?>
													<option value="<?=$estado->id?>"
														<?=isset($colaborador->cnh_id_estado)
															&& $colaborador->cnh_id_estado == $estado->id
															? 'selected' : ''?>
													>
														<?=$estado->sigla?>
													</option>
												<?php endforeach; ?>
											</select>
										</td>
									<tr>
										<td class="text-right">
											<h5 style="font-weight: bold;"><?=lang("data_primeira_habilitacao")?></h5>
										</td>
										<td>
											<input type="date" class="form-control" id="cnh_primeiro" min="1900-01-01" max="<?=date('Y-m-d');?>" name="cnh_primeiro"	value="<?=isset($colaborador->cnh_primeiro) ? $colaborador->cnh_primeiro : ''?>"  />
										</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span>CTPS Nº
											</h5></td>
										<td>
											<input type="text" class="form-control" id="ctps" name="ctps" value="<?=isset($colaborador->ctps) ? $colaborador->ctps : ''?>" required /></td>
										<td rowspan="4">
											<?=lang("copia_carteira_trabalho")?><?=lang("copia_carteira_trabalho")?>
										</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("serie")?>
											</h5></td>
										<td><input type="text" class="form-control" id="ctps_serie" name="ctps_serie" value="<?=isset($colaborador->ctps_serie) ? $colaborador->ctps_serie : ''?>" required /></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("uf_expedidora_ctps")?>
											</h5></td>
										<td>
											<select type="text" class="form-control" name="ctps_id_estado" required>
												<option value=""><?=lang("selecione")?></option>
												<?php foreach ($estados as $estado) : ?>
													<option value="<?=$estado->id?>"
														<?=isset($colaborador->ctps_id_estado)
															&& $colaborador->ctps_id_estado == $estado->id
															? 'selected' : ''?>
													>
														<?=$estado->sigla?>
													</option>
												<?php endforeach; ?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("data_expedicao_ctps")?>
											</h5></td>
										<td><input type="date" class="form-control" min="1900-01-01" max="<?=date('Y-m-d');?>" id="ctps_expedicao" name="ctps_expedicao" value="<?=isset($colaborador->ctps_expedicao) ? $colaborador->ctps_expedicao : ''?>" required />
										</td>
									</tr>
									<tr>
										<td class="text-right">
											<h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("titulo_eleitor")?>
											</h5>
										</td>
										<td><input type="text" class="form-control" id="titulo_eleitor" name="titulo_eleitor" value="<?=isset($colaborador->titulo_eleitor) ? $colaborador->titulo_eleitor : ''?>" required /></td>
										<td rowspan="4"><?=lang("copia_titulo_eleitor")?></td>
									</tr>
									<tr>
										<td class="text-right">
											<h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("zona")?>
											</h5>
										</td>
										<td><input type="text" class="form-control" id="titulo_zona" name="titulo_zona" value="<?=isset($colaborador->titulo_zona) ? $colaborador->titulo_zona : ''?>" required /></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("secao")?>
											</h5></td>
										<td><input type="text" class="form-control" id="titulo_secao" name="titulo_secao" value="<?=isset($colaborador->titulo_secao) ? $colaborador->titulo_secao : ''?>" required /></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("municipio_titulo")?>
											</h5></td>
										<td><input type="text" class="form-control letras" name="titulo_municipio" value="<?=isset($colaborador->titulo_municipio) ? $colaborador->titulo_municipio : ''?>" required /></td>
									</tr>
									<tr>
										<td rowspan="13"
											style="background-color: #03A9F4; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;"
											align="center"><div style="width: 100%">END.</div></td>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span>CEP
											</h5></td>
										<td><input type="text" onblur="buscarEnderecoViaCEP(this)" class="form-control" name="cep" id="cep" value="<?=isset($colaborador->cep) ? $colaborador->cep : ''?>" required /></td>
										<td rowspan="8"><?=lang("comp_residencia_atualizada_com_cep")?></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("endereco")?>
											</h5></td>
										<td><input type="text" class="form-control letras" name="endereco" id="rua" value="<?=isset($colaborador->endereco) ? $colaborador->endereco : ''?>" required />
										</td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span>Nº
											</h5></td>
										<td><input type="text" class="form-control" id="endereco_numero" name="endereco_numero" value = "<?=isset($colaborador->endereco_numero) ? $colaborador->endereco_numero : ''?>" required />
										</td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;"><?=lang("complemento")?></h5></td>
										<td><input type="text" class="form-control" name="endereco_complemento"
											value="<?=isset($colaborador->endereco_complemento) ? $colaborador->endereco_complemento : ''?>" /></td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("bairro")?>
											</h5></td>
										<td><input type="text" class="form-control letras" name="bairro" id="bairro" value="<?=isset($colaborador->bairro) ? $colaborador->bairro : ''?>" required /></td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("municipio")?>
											</h5></td>
										<td><input type="text" class="form-control letras" name="cidade" id="cidade" value="<?=isset($colaborador->cidade) ? $colaborador->cidade : ''?>" required /></td>
										<tr>
										<td class="text-right">
											<h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("estado")?>
											</h5>
										</td>
										<td>
											<input type="text" class="form-control letras" name="estado" id="uf" maxlength="2" value="<?=isset($colaborador->estado) ? $colaborador->estado : ''?>" required /></td>
										</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;"><?=lang("telefone_residencial_ddd")?></h5></td>
										<td><input type="text" class="form-control" name="telefone_residencial" id="telefone_residencial"
											value="<?=isset($colaborador->telefone_residencial) ? $colaborador->telefone_residencial : ''?>" /></td>
										<td></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("telefone_celular_ddd")?>
											</h5></td>
										<td>
										<input type="text" class="form-control" name="celular" id="celular" value="<?=isset($colaborador->celular) ? $colaborador->celular : ''?>" required /></td>
										<td></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("telefone_emergencia_ddd")?>
											</h5></td>
										<td><input type="text"	class="form-control" name="telefone_emergencia" id="telefone_emergencia" value="<?=isset($colaborador->telefone_emergencia) ? $colaborador->telefone_emergencia : ''?>" required /></td>
										<td></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("nome_contato_emergencia")?>
											</h5></td>
										<td><input type="text"	class="form-control letras" name="emergencia_contato" value="<?=isset($colaborador->emergencia_contato) ? $colaborador->emergencia_contato : ''?>" required /></td>
										<td></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("email_particular")?>
											</h5></td>
										<td><input type="email"	class="form-control" name="email_particular" value="<?=isset($colaborador->email_particular) ? $colaborador->email_particular : ''?>" required /></td>
										<td></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;"></span><?=lang("email_corporativo")?>
											</h5></td>
										<td><input type="email" class="form-control" name="email_corporativo" value="<?=isset($colaborador->email_corporativo) ? $colaborador->email_corporativo : ''?>" /></td>
										<td></td>
									</tr>
									<tr>
										<td rowspan="3" style="background-color: #03A9F4; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;"
											align="center"><div style="width: 100%"><?=lang("saude")?></div></td>
										<td class="text-right">
											<h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("cor_pele")?> 
											</h5>
										</td>
										<td>
											<select class="form-control" name="id_cor_pele" required >
												<option value=""><?=lang("selecione")?></option>
												<?php foreach ($corPeles as $corPele) : ?>
													<option value="<?=$corPele->id?>"
														<?=isset($colaborador->id_cor_pele)
															&& $colaborador->id_cor_pele == $corPele->id
															? 'selected' : ''?>
													>
														<?=$corPele->descricao?>
													</option>
												<?php endforeach; ?>
											</select>
										</td>
										<td></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span><?=lang("cartao_nacional_saude")?>
											</h5></td>
										<td><input type="text" class="form-control" id="cns" name="cns" value="<?=isset($colaborador->cns) ? $colaborador->cns : ''?>" required /></td>
										<td><?=lang("copia_cartao_sus")?></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;"><?=lang("pessoa_portadora_deficiencia")?></td></h5></td>
										<td><input type="text" class="form-control letras" name="ppd"
											value="<?=isset($colaborador->ppd) ? $colaborador->ppd : ''?>" /></td>
										<td><?=lang("laudo_medico")?></td>
									</tr>
									<tr>
										<td rowspan="3"
											style="background-color: #03A9F4; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;"
											align="center"><div style="width: 100%">COMPL.</div></td>
										<td class="text-right"><h5 style="font-weight: bold;"><?=lang("aposentado")?></h5></td>
										<td>
											<div class="radio">
												<label><input type="radio" name="aposentado" value="1" required <?=isset($colaborador->aposentado) && $colaborador->aposentado == "1" ? "checked" : ''?> ><?=lang("sim")?></label>&nbsp;&nbsp;&nbsp;
												<label><input type="radio" name="aposentado" value="0" <?=isset($colaborador->aposentado) && $colaborador->aposentado == "0" ? "checked" : ''?> ><?=lang("nao")?></label>
											</div>
										</td>
										<td rowspan="3" valign="bottom"><?=lang("informar_sim_ou_nao")?></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;"><?=lang("possui_filhos")?></h5></td>
										<td>
											<div class="radio">
												<label><input type="radio" name="filhos" value="1" required <?=isset($colaborador->filhos) && $colaborador->filhos == "1" ? "checked" : ''?> ><?=lang("sim")?></label>&nbsp;&nbsp;&nbsp;
												<label><input type="radio" name="filhos" value="0" <?=isset($colaborador->filhos) && $colaborador->filhos == "0" ? "checked" : ''?> ><?=lang("nao")?></label>
											</div>
										</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">Gestante</h5></td>
										<td>
											<div class="radio">
												<label><input type="radio" name="gestante" value="1" required <?=isset($colaborador->gestante) && $colaborador->gestante == "1" ? "checked" : ''?> ><?=lang("sim")?></label>&nbsp;&nbsp;&nbsp;
												<label><input type="radio" name="gestante" value="0" <?=isset($colaborador->gestante) && $colaborador->gestante == "0" ? "checked" : ''?> ><?=lang("nao")?></label>
											</div>
										</td>
									</tr>								
								</tbody>
							</table>
						</div>
						<div class="form-group">
							<h4 class="page-header text-center">
								<strong><?=lang("validacao_dependentes")?></strong>
							</h4>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-xs-12">
									<label><input type="radio" name="dependentes" id="possuoDependente" value="1" required <?=isset($colaborador->dependentes) && $colaborador->dependentes == "1" ? "checked" : ''?> >
										&nbsp;&nbsp;<?=lang("possuo_dependente_regulamento")?></label>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<label><input type="radio" name="dependentes" id="naoPossuoDependente" value="0" <?=isset($colaborador->dependentes) && $colaborador->dependentes == "0" ? "checked" : ''?> >
									&nbsp;&nbsp;<?=lang("nao_possuo_dependentes")?></label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-xs-12">
									<p>
										<b><?=lang("alteracoes_serao_aceitas_mediante_entrega_rh")?></b>
									</p>
								</div>
							</div>
						</div>
						<div class="divdependentes">
							<table class="table">
								<thead>
									<tr>
										<th style="background-color: #03A9F4; color: #FFF;"
											class="col-xs-1 text-center"><?=lang("aba")?></th>
										<th style="background-color: #03A9F4; color: #FFF;"
											class="col-xs-2 text-center"><?=lang("informacao")?></th>
										<th style="background-color: #03A9F4; color: #FFF;"
											class="col-xs-6 text-center"><?=lang("alterar_corrigir_para")?></th>
										<th style="background-color: #03A9F4; color: #FFF;"
											class="col-xs-3 text-center"><?=lang("comprovante_necessario")?>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="3" style="font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px;" align="right">
											<?=lang("adicionar_novo_dependente")?>
										</td>
										<td align="center"><button class="btn btn-primary"
												type="button" onclick="dependenteAdicionar();">
												<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
											</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div id="divDependentes"></div>
						<div class="clear"></div>
						<?php 
						
						$i = 1;
						foreach ($colaboradorDependentes as $dependente) :?>
							<div class="form-group" id="divDependente_<?=$i?>">
								<input type="hidden" class="form-control" name="dependentes_operacao[]" value="<?=$i?>" />
								<input type="hidden" class="form-control" name="dependente_id_<?=$i?>" value="<?=$dependente->id?>" />
								<table class="table tabelasDependentes">
									<thead>
										<tr>
										<th style="background-color:#03A9F4; color:#FFF;" class="col-xs-1 text-center"><?=lang("aba")?></th>
										<th style="background-color:#03A9F4; color:#FFF;" class="col-xs-2 text-center"><?=lang("informacao")?></th>
										<th style="background-color:#03A9F4; color:#FFF;" class="col-xs-6 text-center"><?=lang("alterar_corrigir_para")?></th>
										<th style="background-color:#03A9F4; color:#FFF;" class="col-xs-2 text-center"><?=lang("comprovante_necessario")?></th>
										<th style="background-color:#03A9F4; color:#FFF;" class="col-xs-1 text-center">
											<button class="btn btn-danger" type="button" onclick="modalDependenteExcluir(<?=$dependente->id?>, <?=$i?>)" >
												<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
											</button>
										</th>
										</tr>
									</thead>
									<tbody>                            
										<tr>
											<td rowspan="9" style="background-color:#03A9F4; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center"><div style="width:150px;">Dependente</div></td>
											<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span><?=lang("nome_completo")?></h5></td>
											<td>
												<input type="text" class="form-control letras" name="dependente_nome_<?=$i?>"
													value="<?=$dependente->nome?>" required />
											</td>
											<td rowspan="9" colspan="2" ><?=lang("certidao_nascimento_casamento_cpf_rg_do_conjugue")?><br> <span style="color:#A60000"><?=lang("importante")?>:</span> <?=lang("informar_cpf_filhos")?></td>
										</tr>                            
										<tr>
											<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span><?=lang("estado_civil")?></h5></td>
											<td>
											<select class="form-control" name="dependente_id_estado_civil_<?=$i?>" id="dependente_id_estado_civil_<?=$i?>" required >
												<option value=""><?=lang("selecione")?></option>
												<?php foreach ($estadosCivis as $estadoCivil) : ?>
													<option value="<?=$estadoCivil->id?>"
														<?= $dependente->id_estado_civil == $estadoCivil->id
															? 'selected' : ''?>
													>
														<?=$estadoCivil->descricao?>
													</option>
												<?php endforeach; ?>
											</select>	
											</td>
										</tr>
										<tr><td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span>CPF</h5></td>
										<td><input type="text" class="form-control" name="dependente_cpf_<?=$i?>" id="dependente_cpf_<?=$i?>"
											value="<?=$dependente->cpf?>" required /></td></tr>
										<tr>
										<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span><?=lang("parentesco")?></h5></td>
										<td>
										<select class="form-control" name="dependente_id_parentesco_<?=$i?>" id="dependente_id_parentesco_<?=$i?>" required >
											<option value=""><?=lang("selecione")?></option>
											<?php foreach ($parentescos as $parentesco) : ?>
												<option value="<?=$parentesco->id?>"
													<?= $dependente->id_parentesco == $parentesco->id
														? 'selected' : ''?>
												>
												<?=$parentesco->descricao?></option>
											<?php endforeach; ?>
										</select>	
										</td>                            
										</tr>                            
										<tr>
											<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span><?=lang("sexo")?></h5>
											<td>
											<select class="form-control" name="dependente_sexo_<?=$i?>" required >
												<option value="Feminino" <?=$dependente->sexo == "Feminino" ? 'selected' : ''?> ><?=lang("feminino")?></option>
												<option value="Masculino" <?=$dependente->sexo == "Masculino" ? 'selected' : ''?> ><?=lang("masculino")?></option>
											</select>
											</td>
										</tr>
										<tr>
											<td class="text-right">
												<h5 style="font-weight:bold;">
													<span style="color:#F00;">*</span> <?=lang("data_de_nascimento")?>
												</h5>
											</td>
											<td>
												<input type="date" class="form-control" id="dependente_data_nascimento" max="<?=date('Y-m-d');?>" name="dependente_data_nascimento_<?=$i?>"
													value="<?=$dependente->data_nascimento?>" required />
											</td>
										</tr>
										<tr>
											<td class="text-right">
												<h5 style="font-weight:bold;">
													<span style="color:#F00;">*</span><?=lang("nome_mae")?>
												</h5>
											</td>
											<td>
												<input type="text" class="form-control letras" name="dependente_mae_<?=$i?>"
													value="<?=$dependente->mae?>" required />
											</td>
										</tr>
										<tr>
											<td class="text-right">
												<h5 style="font-weight:bold;">
													<span style="color:#F00;"></span><?=lang("data_casamento_uniao")?>
												</h5>
											</td>
											<td>
												<input type="date" max="<?=date('Y-m-d');?>" class="form-control" name="dependente_data_casamento_<?=$i?>"
													value="<?=$dependente->data_casamento?>" />
											</td>
										</tr>
										<tr>
											<td class="text-right">
												<h5 style="font-weight:bold;">
													<span style="color:#F00;">*</span><?=lang("naturalidade")?>
												</h5>
											</td>
											<td>
												<input type="text" class="form-control letras" name="dependente_naturalidade_<?=$i?>"
													value="<?=$dependente->naturalidade?>" required />
											</td>
										</tr>
										<tr>
											<td rowspan="3" style="background-color:#03A9F4; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center"><div style="width:100%"><?=lang("saude")?> </div></td>
											<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span><?=lang("cor_pele")?></h5></td>
											<td>
												<select class="form-control" name="dependente_id_cor_pele_<?=$i?>" id="dependente_id_cor_pele_<?=$i?>" required >
													<option value=""><?=lang("selecione")?></option>
													<?php foreach ($corPeles as $corPele) : ?>
														<option value="<?=$corPele->id?>"
															<?= $dependente->id_cor_pele == $corPele->id
																? 'selected' : ''?>
														>
														<?=$corPele->descricao?></option>
													<?php endforeach; ?>
												</select>
											</td>                            
											<td colspan="2"></td>
										</tr>		
										<tr>
											<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span><?=lang("cartao_nacional_saude")?></h5></td>
											<td><input type="text" class="form-control" id="dependente_cns" name="dependente_cns_<?=$i?>"
												value="<?=$dependente->cns?>" required /></td>
											<td colspan="2"><?=lang("copia_cartao_sus")?></td>
										</tr>		
										<tr>
											<td class="text-right">
												<h5 style="font-weight:bold;">
													<span style="color:#F00;">*</span><?=lang("pessoa_potadora_deficiencia")?>
												</h5>
											</td>
											<td>
												<div class="radio">
													<label><input type="radio" name="dependente_ppd_<?=$i?>" value="1"
														<?= $dependente->ppd == "1" ? 'checked' : ''?> required ch><?=lang("sim")?></label>&nbsp;&nbsp;&nbsp;
													
													<label><input type="radio" name="dependente_ppd_<?=$i?>" value="0" 
														<?= $dependente->ppd == "0" ? 'checked' : ''?> required ><?=lang("nao")?></label>
												</div>
											</td>
											<td colspan="2"><?=lang("laudo_medico")?></td>
										</tr>                            
										<tr>
											<td rowspan="3" style="background-color:#03A9F4; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center">
												<div style="width:150px;"><?=lang("registro_nascimento")?></div>
											</td>
											<td class="text-right">
												<h5 style="font-weight:bold;"><?=lang("cartorio")?></h5>
											</td>
											<td>
												<input type="text" class="form-control" name="dependente_cartorio_<?=$i?>"
													value="<?=$dependente->cartorio?>" />
											</td>
											<td rowspan="3" colspan="2" ><?=lang("certidao_nascimento_caso_filho")?></td>
										</tr>
										<tr>
											<td class="text-right">
												<h5 style="font-weight:bold;"><?=lang("registro_livro_folha")?></h5>
											</td>
											<td>
												<input type="text" class="form-control" name="dependente_registro_<?=$i?>" value="<?=$dependente->registro?>" />
											</td>
										</tr>
										<tr>
											<td class="text-right">
												<h5 style="font-weight:bold;">
													<span style="color:#F00;">*</span><?=lang("num_declaracao_nascido")?>
												</h5>
											</td>
											<td>
												<input type="text" class="form-control" id="dependente_declar_vivo" name="dependente_declar_vivo_<?=$i?>"
													value="<?=$dependente->declar_vivo?>" required />
											</td>
										</tr>
										<tr>
											<td style="background-color:#03A9F4; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center">IRRF</td>
											<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span><?=lang("dependente_irrf")?></h5></td>
											<td>
												<div class="radio">
													<label>
														<input type="radio" name="dependente_irrf_<?=$i?>" value="1" required
															<?= $dependente->irrf == "1" ? 'checked' : '' ?> ><?=lang("sim")?>
													</label>&nbsp;&nbsp;&nbsp;
													<label>
													<input type="radio" name="dependente_irrf_<?=$i?>" value="0" required
															<?= $dependente->irrf == "0" ? 'checked' : '' ?> ><?=lang("nao")?>
													</label>
												</div>
											</td>
											<td  colspan="2" valign="bottom"><?=lang("informar_sim_ou_nao")?></td>
										</tr>
									</tbody>
								</table>
							</div>
						<?php $i++; endforeach; ?>
						<div class="form-group">
							<h4 class="page-header">&nbsp;</h4>
						</div>

					</div> <!---- print ----->
					
					<div class="row">
						<div class="col-md-offset-4 col-md-2">
							<div class="form-group">
								<input type="submit" class="btn btn-primary btn-lg btn-block" name="colaboradorAtualizar" id="colaboradorAtualizar" value="<?=lang('atualizar')?>" />
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<input type="submit" class="btn btn-outline-primary btn-lg btn-block" name="colaboradorImprimir" id="colaboradorImprimir" value="<?=lang('imprimir')?>">
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
</div>


<!-- Modal de confirmacao de exclusao -->
<div id="modalDependenteConfirmarExcluir" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true">
    
    <input type="hidden" id="modalConfirmarDependenteId">
    <input type="hidden" id="modalConfirmarDependentePosicao">
    <div class="modal-dialog" role="document" style="width: 30%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalLabel"><?=lang("modal_confirmacao_titulo")?></h4>
            </div>

            <div class="modal-body">
                <?=lang("confirmacao_exclusao_dependente")?>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="dependenteExcluir()" id="buttonDependenteExcluir" class="btn btn-warning"><?=lang("excluir")?></button>
                <button type="button" class="btn" data-dismiss="modal"><?=lang("cancelar")?></button>
            </div>

        </div>
    </div>
</div>


<script type="text/javascript">

	var dependentePosicao = $(".tabelasDependentes")?.length ? $(".tabelasDependentes").length : 0;
	var estadosCivis = JSON.parse('<?=json_encode($estadosCivis)?>');
	var corPeles = JSON.parse('<?=json_encode($corPeles)?>');
	var parentescos = JSON.parse('<?=json_encode($parentescos)?>');

	$(document).ready(function()
	{
		$('.letras').mask('Z',{translation: {'Z': {pattern: /[a-zA-Z áéíóúâêôãõç]/, recursive: true}}});
		$('#reservista').mask('00000000000000000000000000');
		$('#pis').mask('000000000000');
		$('#ctps').mask('000000000000000');
		$('#ctps_serie').mask('000000000000000');
		$('#rg').mask('00.000.000-0');
		$('#titulo_eleitor').mask('000000000000');
		$('#titulo_zona').mask('000');
		$('#titulo_secao').mask('0000');
		$('#cns').mask('000000000000000');
		$('#dependente_cns').mask('000000000000000');
		$('#endereco_numero').mask('000000');
		$('#cpf').mask('000.000.000-00');
		$('#dependente_cpf1').mask('000.000.000-00');
		$('#dependente_cpf2').mask('000.000.000-00');
		$('#dependente_cpf3').mask('000.000.000-00');
		$('#dependente_cpf4').mask('000.000.000-00');
		$('#dependente_cpf5').mask('000.000.000-00');
		$('#dependente_cpf6').mask('000.000.000-00');
		$('#cep').mask('00.000-000');
		$('#telefone_residencial').mask('(00) 0000-0000');
		$('#celular').mask('(00) 00000-0000');
		$('#telefone_emergencia').mask('(00) 00000-0000');
		$('#dependente_declar_vivo').mask('00000000000000000000000000000000000');

		// Esconde div se nao houver dependente
		if ($("#naoPossuoDependente").is(":checked"))
			$(".divdependentes").hide();
			
		$('input:radio[name="dependentes"]').change(function()
		{
			var val = $(this).val();
			
			if (val == '1')
				$(".divdependentes").show();                         
			else
				$(".divdependentes").hide(); 
		});

		// Submit Colaborador
        $("#formularioColaborador").on("submit", function (evento)
        {
			evento.preventDefault();
			let btn = $(document.activeElement).attr("name");
			
			if (btn == "colaboradorImprimir")
				imprimirFormulario();
			else if (btn == "colaboradorAtualizar")
				colaboradorAtualizar();
		});

	});

	function imprimirFormulario()
	{
		let divToPrint = document.getElementById('print').innerHTML;

		let htmlToPrint = `
			<style type="text/css">
				table {
					font-family: arial, sans-serif;
					border-collapse: collapse;
					width: 95%;
					margin-left: 10px
				}
				th, td {
					border:1px solid #000;
					padding: 20px;
				}
				tr:nth-child(even) {
					background-color: #dddddd;
				}
			</style>
			<html>
				<body>
		`;

		htmlToPrint += divToPrint;
		htmlToPrint += `</body></html>`;

		let windowToPrint = window.open("");    
		windowToPrint.document.write(htmlToPrint);
		windowToPrint.document.close();
		windowToPrint.print();
	}

	function colaboradorAtualizar()
	{
		// Carregando
		$('#colaboradorAtualizar')
                .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('atualizando')?>')
                .attr('disabled', true);

        let data = new FormData($("#formularioColaborador")[0]);
        let url = "<?=site_url('GentesGestoes/AdministracoesPessoais/colaboradorAtualizar')?>";
        
		$.ajax({
            url,
            data,
            type: "POST",
            dataType: "JSON",
			cache: false,
            contentType: false,
            processData: false,
            success: function (retorno)
            {
                // Mensagem de retorno
                toastr.success(retorno.mensagem, retorno.status == 1
					? '<?=lang("sucesso")?>'
					: '<?=lang("atencao")?>'
				);

				window.location.href = "<?=site_url('GentesGestoes/AdministracoesPessoais')?>";
            },
            error: function (xhr, textStatus, errorThrown)
            {
                // Mensagem de erro
                toastr.warning('<?=lang("mensagem_erro")?>', '<?=lang("atencao")?>');
            },
            complete: function ()
            {
                // Carregado
                $('#colaboradorAtualizar')
                    .html('<?=lang('atualizar')?>')
                    .attr('disabled', false);
            }
        });
	}

	function modalDependenteExcluir(dependenteId, posicao)
    {
        $("#modalConfirmarDependenteId").val(dependenteId);
        $("#modalConfirmarDependentePosicao").val(posicao);
        $('#modalDependenteConfirmarExcluir').modal();
    }

    function dependenteExcluir()
    {
        let dependenteId = $("#modalConfirmarDependenteId").val();
        
        // Carregando
        $('#buttonDependenteExcluir')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);

        // Deleta Treinamento
        $.ajax({
            url: '<?=site_url("GentesGestoes/AdministracoesPessoais/dependenteExcluir")?>/'+dependenteId,
            type: "POST",
            dataType: "JSON",
            success: function (retorno)
            {
                if (retorno.status == 1)
                {
                    // Mensagem de retorno
                    toastr.success(retorno.mensagem, '<?=lang("sucesso")?>');

                    // Fecha modal
                    $("#modalDependenteConfirmarExcluir").modal('hide');
					// Remove div
                    dependenteRemoverDiv($("#modalConfirmarDependentePosicao").val());
                }
                else
                {
                    // Mensagem de retorno
                    toastr.warning(retorno.mensagem, '<?=lang("atencao")?>');
                }
            },
            error: function (xhr, textStatus, errorThrown)
            {
                // Mensagem de retorno
                toastr.warning('<?=lang("mensagem_erro")?>', '<?=lang("atencao")?>');
            },
            complete: function ()
            {
                // Carregado
                $('#buttonDependenteExcluir')
                    .html('<?=lang("excluir")?>')
                    .attr('disabled', false);
            }
        });
    }

	function formatarCpf(v)
	{
		v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
		v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
		v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
												//de novo (para o segundo bloco de números)
		v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2") //Coloca um hífen entre o terceiro e o quarto dígitos
		return v
	}
	
	function verificaCPF(elemento)
	{
		if (!validarCPF(elemento.value))
			alert("CPF Inválido!");
	}
	
	function mascara(o,f)
	{
		v_obj=o;
		v_fun=f;
		setTimeout("execMascara()", 1);
	}

	function execMascara()
	{
		v_obj.value=v_fun(v_obj.value)
	}

	function dependenteAdicionar()
	{
		dependentePosicao++;

		if(dependentePosicao > 10 )
		{
			alert('Número máximo de campos excedido!')	
			return false;
		}	        

		$('#divDependentes').append(`
			<div class="form-group" id="divDependente_${dependentePosicao}">
				<input type="hidden" class="form-control" name="dependentes_operacao[]" value="${dependentePosicao}" />
				<table class="table tabelasDependentes">
					<thead>
						<tr>
							<th style="background-color:#03A9F4; color:#FFF;" class="col-xs-1 text-center">${lang.aba}</th>
							<th style="background-color:#03A9F4; color:#FFF;" class="col-xs-2 text-center">${lang.informacao}</th>
							<th style="background-color:#03A9F4; color:#FFF;" class="col-xs-6 text-center">${lang.alterar_corrigir_para}</th>
							<th style="background-color:#03A9F4; color:#FFF;" class="col-xs-2 text-center">${lang.comprovante_necessario}</th>
							<th style="background-color:#03A9F4; color:#FFF;" class="col-xs-1 text-center">
								<button class="btn btn-danger" type="button" onclick="dependenteRemoverDiv(${dependentePosicao});">
									<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
								</button>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td rowspan="9" style="background-color:#03A9F4; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center">
								<div style="width:150px;">${lang.dependente}</div>
							</td>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									<span style="color:#F00;">*</span>${lang.nome_completo}
								</h5>
							</td>
							<td>
								<input type="text" class="form-control letras" name="dependente_nome_${dependentePosicao}" required/>
							</td>
							<td rowspan="9" colspan="2">
								${lang.certidao_nascimento_casamento_cpf_rg_do_conjugue}<br>
								<span style="color:#A60000">${lang.importante}:</span>
								${lang.informar_cpf_filhos}
							</td>
						</tr>
						<tr>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									<span style="color:#F00;">*</span>${lang.estado_civil}
								</h5>
							</td>
						    <td>
								<select class="form-control" id="dependente_id_estado_civil_${dependentePosicao}" name="dependente_id_estado_civil_${dependentePosicao}" required>
									<option value="">${lang.selecione}</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									<span style="color:#F00;">*</span>CPF
								</h5>
							</td>
							<td>
								<input type="text" class="form-control" name="dependente_cpf_${dependentePosicao}" onKeyPress="mascara(this, formatarCpf)" maxlength="14" required/>
							</td>
						</tr>
						<tr>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									<span style="color:#F00;">*</span>${lang.parentesco}
								</h5>
							</td>
							<td>
								<select class="form-control" id="dependente_id_parentesco_${dependentePosicao}" name="dependente_id_parentesco_${dependentePosicao}" required>
									<option value="">${lang.selecione}</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									<span style="color:#F00;">*</span>${lang.sexo}
								</h5>
							</td>
							<td>
								<select class="form-control" name="dependente_sexo_${dependentePosicao}" required>
									<option value="Feminino">${lang.masculino}</option>
									<option value="Masculino">${lang.feminino}</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									<span style="color:#F00;">*</span>${lang.data_de_nascimento}
								</h5>
							</td>
							<td>
								<input type="date" max="<?=date('Y-m-d');?>" class="form-control" id="data_nascimento" name="dependente_data_nascimento_${dependentePosicao}" size="10" maxlength="10" >
							</td>
						</tr>
						<tr>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									<span style="color:#F00;">*</span>${lang.nome_mae}
								</h5>
							</td>
							<td>
								<input type="text" class="form-control letras" name="dependente_mae_${dependentePosicao}" />
							</td>
						</tr>
						<tr>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									<span style="color:#F00;"></span>${lang.data_casamento_uniao}
								</h5>
							</td>
							<td>
								<input type="date" max="<?=date('Y-m-d');?>" class="form-control" name="dependente_data_casamento_${dependentePosicao}" size="10" maxlength="10" />
							</td>
						</tr>
						<tr>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									<span style="color:#F00;">*</span>${lang.naturalidade}
								</h5>
							</td>
							<td>
								<input type="text" class="form-control letras" name="dependente_naturalidade_${dependentePosicao}" />
							</td>
						</tr>
						<tr>
							<td rowspan="3" style="background-color:#03A9F4; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center">
								<div style="width:100%">
									${lang.saude}
								</div>
							</td>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									<span style="color:#F00;">*</span>${lang.cor_pele}
								</h5>
							</td>
						    <td>
								<select class="form-control" id="dependente_id_cor_pele_${dependentePosicao}" name="dependente_id_cor_pele_${dependentePosicao}" required>
									<option value="">${lang.selecione}</option>
						        </select>
						    </td>
							<td colspan="2"></td>
						</tr>
						<tr>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									<span style="color:#F00;"></span>${lang.cartao_nacional_saude}
								</h5>
							</td>
						    <td>
								<input type="text" class="form-control" id="dependente_cns" name="dependente_cns_${dependentePosicao}" />
							</td>
							<td colspan="2">Cópia do cartão SUS</td>
						</tr>
						<tr>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									<span style="color:#F00;">*</span>${lang.pessoa_portadora_deficiencia}
								</h5>
							</td>
							<td>
						        <div class="radio">
						          	<label><input type="radio" name="dependente_ppd_${dependentePosicao}" value="1">${lang.sim}</label>&nbsp;&nbsp;&nbsp;
						          	<label><input type="radio" name="dependente_ppd_${dependentePosicao}" value="0" required>${lang.nao}</label>
						        </div>
						    </td>
						    <td colspan="2">${lang.laudo_medico}</td>
						</tr>
						<tr>
							<td rowspan="3" style="background-color:#03A9F4; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center">
								<div style="width:150px;">
									${lang.registro_nascimento}
								</div>
							</td>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									${lang.cartorio}
								</h5>
							</td>
						    <td>
								<input type="text" class="form-control" name="dependente_cartorio_${dependentePosicao}" />
							</td>
							<td rowspan="3" colspan="2">
								${lang.certidao_nascimento_caso_filho}
							</td>
						</tr>
						<tr>
							<td class="text-right">
								<h5 style="font-weight:bold;">${lang.registro_livro_folha}</h5>
							<td>
								<input type="text" class="form-control" name="dependente_registro_${dependentePosicao}" />
							</td>
						</tr>
						<tr>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									<span style="color:#F00;"></span>${lang.num_declaracao_nascido}
								</h5>
							</td>
							<td>
								<input type="text" class="form-control" name="dependente_declar_vivo_${dependentePosicao}" />
							</td>
						</tr>
						<tr>
						    <td style="background-color:#03A9F4; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center">
								IRRF
							</td>
							<td class="text-right">
								<h5 style="font-weight:bold;">
									<span style="color:#F00;">*</span>${lang.dependente_irrf}
								</h5>
							</td>
							<td>
						        <div class="radio">
						          <label><input type="radio" name="dependente_irrf_${dependentePosicao}" value="1">${lang.sim}</label>&nbsp;&nbsp;&nbsp;
						          <label><input type="radio" name="dependente_irrf_${dependentePosicao}" checked value="0" required>${lang.nao}</label>
						        </div>
						    </td>
							<td colspan="2" valign="bottom">
								${lang.informar_sim_ou_nao}
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		`);

		// Carrega selects 
		carregarSelectDependenteEstadoCivil(dependentePosicao);
		carregarSelectDependenteParentesco(dependentePosicao);
		carregarSelectDependenteCorPele(dependentePosicao);

		// Bug mask
		$('.letras').mask('Z',{translation: {'Z': {pattern: /[a-zA-Z áéíóúâêôãõç]/, recursive: true}}});
	}

	function dependenteRemoverDiv(posicao)
	{
		$("#divDependente_"+posicao).remove();

		if (posicao == dependentePosicao)
			dependentePosicao--;
	}

	function carregarSelectDependenteEstadoCivil(posicao)
	{
		let options = "";
		estadosCivis.forEach(function (estadoCivil)
		{
			options += `
				<option value="${estadoCivil.id}">
					${estadoCivil.descricao}
				</option>
			`;
		});

		$("#dependente_id_estado_civil_"+posicao).append(options);
	}

	function carregarSelectDependenteParentesco(posicao)
	{
		let options = "";
		parentescos.forEach(function (parentesco)
		{
			options += `
				<option value="${parentesco.id}">
					${parentesco.descricao}
				</option>
			`;
		});

		$("#dependente_id_parentesco_"+posicao).append(options);
	}

	function carregarSelectDependenteCorPele(posicao)
	{
		let options = "";
		corPeles.forEach(function (corPele)
		{
			options += `
				<option value="${corPele.id}">
					${corPele.descricao}
				</option>
			`;
		});

		$("#dependente_id_cor_pele_"+posicao).append(options);
	}

</script>