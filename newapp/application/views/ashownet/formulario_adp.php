<div class="containner">
	<h5 style="font-weight: bold;">Gestão de Carreira</h5>
	<h2 class="TitPage">Formulário ADP</h2>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
                		<div class="col-md-12">
                            <?php echo $this->session->flashdata('sucesso');?>
                            <?php echo $this->session->flashdata('error');?>
                        </div>
                	</div>
                	<div id="print">
					<form method="post" name="frm" action="<?php echo site_url('cadastros/cad_formulario_adp')?>">
						<div class="form-group">
							<h4 class="page-header text-center">
								<strong>TERMO DE RESPONSABILIDADE - REVISÃO DE CADASTRO</strong>
							</h4>
						</div>
						<div class="form-group">
							<div class="row" id="topo">
								<div class="col-xs-12">
									<?php 
									$nome = $this->auth->get_login('admin', 'nome');
									$query_usuario = $this->db->query("SELECT c.*, u.nome AS nomeusuario, u.id AS idusuario, u.cpf AS cpfusuario  FROM usuario u LEFT JOIN cad_colaborador c ON u.id=c.id_usuario
                                    WHERE u.nome = '$nome'");
									
									$row_usuario = $query_usuario->row();
									$idfuncionario = $row_usuario->idusuario;
									?>
									<input type="hidden" class="form-control" name="id" value="<?=$row_usuario->id?>" />
									<input type="hidden" class="form-control" name="id_usuario" value="<?=$idfuncionario?>" />
									<p>
										Eu, &nbsp;<strong><?php echo $nome;?></strong>,
										CPF:&nbsp; <strong><?php echo $row_usuario->cpf;?></strong>, declaro ter conferido
										meus dados cadastrais e relaciono neste formulário todas as
										alterações necessárias e estou ciente que:
									</p>
									<p>
										• <b><u>A não conferência correta de todos os dados</u> </b>(meus
										e de meus dependentes) pode ocasionar problemas com liberação
										do FGTS, Benefícios Previdenciários, declaração de Imposto de
										Renda entre outros.
									</p>
									<p>• As alterações solicitadas serão realizadas somente quando
										eu entregar no RH o comprovante indicado (conforme instruções
										abaixo).</p>
									<br>
									<p>
										<strong>Instrução para preenchimento:</strong>
									</p>
									<p>
										<b>1</b> - confira todos os dados com atenção (tenha em mãos
										seus documentos).
									</p>
									<p>Pedimos atenção para atualização das seguintes informações:</p>
									<p>- Grau de Instrução (Escolaridade);</p>
									<p>- Contatos (telefone do colaborador; telefone para
										emergência, e-mail particular);</p>
									<p>- Dados de todos os dependentes (principalmente CPF para
										filhos maiores de 12 anos)</p>
									<p>
										<b>2</b> - escreva a informação correta no campo a ser
										corrigido;
									</p>
									<p>
										<b>3</b> - após a conferência, clique em “Salvar e Enviar” no
										final da tela, ou clique em “Salvar Alterações” para continuar
										a conferência em outro momento;
									</p>
									<p>
										<b>4</b> - caso altere alguma informação, providencie cópia do
										comprovante correspondente para entregar no RH (indicado na
										coluna “Comprovante Necessário”, ao lado da informação a ser
										corrigida);
									</p>
									<p>
										<b>5</b> - não deixe de conferir os dados de seus dependentes
										(se houver) no final deste formulário (se houver).
									</p>
									<p>
										<b>6</b> - caso não tenha o número do “Cartão Nacional de
										Saúde (CNS - SUS)” solicitado neste formulário (tanto do
										colaborador quanto dos dependentes), siga as orientações
										abaixo:
									<p>
										- acesse o link <a
											href="http://cartaosus.com.br/consulta-cartao-sus/"
											target="_blank">cartaosus.com.br/consulta-cartao-sus/</a>
									</p>
									<p>- desça a barra de rolagem até aparecer “Consulta à base de
										dados do Cartão Nacional de Saúde por Nome”</p>
									<p>- preencha as informações solicitadas (nome completo, CPF,
										Dt Nascimento, Município de Nascimento, Nome da mãe)</p>
									<p>- o número do cartão vai aparecer destacado na mesma tela</p>
									<br>
								</div>
							</div>
							<div class="row" id="topo">
								<div class="col-xs-12">
									<p>
										<span style="color: #F00;">* Preenchimento obrigatório.</span>
									</p>
								</div>
							</div>
							<div class="alert alert-danger text-center" role="alert"
								id="alert" style="display: none;">
								<h4>
									<strong>Por favor, responda todas os itens Obrigatórios(*)</strong>.
								</h4>
							</div>
						</div>
						<div class="form-group">
							<table class="table">
								<thead>
									<tr>
										<th style="background-color: #5a8cb7; color: #FFF;"
											class="col-xs-1 text-center">Aba</th>
										<th style="background-color: #5a8cb7; color: #FFF;"
											class="col-xs-2 text-center">Informação</th>
										<th style="background-color: #5a8cb7; color: #FFF;"
											class="col-xs-6 text-center">Alterar / Corrigir para</th>
										<th style="background-color: #5a8cb7; color: #FFF;"
											class="col-xs-3 text-center">Comprovante necessário</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td rowspan="9" style="background-color: #60a1d9; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center"><div style="width: 150px;">INF. PESSOAIS</div></td>
										<td class="text-right">
											<h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Nome Completo
											</h5></td>
											<?php 
											if($row_usuario->nome == "") { 
											    $nomeUsu = $nome;											    
											}else{
											    $nomeUsu  = $row_usuario->nome;
											}
											
											?>
										<td><input type="text" class="form-control letras" name="nome" value="<?=$nomeUsu?>" required /></td>
										<td>Cópia do RG atualizado</td>
									</tr>
									<tr>
										<td class="text-right">
											<h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Sexo
											</h5>
										</td>
										<td>
										<select class="form-control" name="sexo" required>
											<option value="" selected>Selecione</option>
											<option value="F" <?php if($row_usuario->sexo == "F"){ echo 'selected';}?>>Feminino</option>
											<option value="M" <?php if($row_usuario->sexo == "M"){ echo 'selected';}?>>Masculino</option>
										</select></td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class="text-right">
											<h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Data de Nascimento
											</h5></td>
										<td>
											<input type="date" id="dtNasc" max="2999-12-31" class="form-control" name="dtNasc" value="<?=$row_usuario->dtNasc?>" required /></td>
										<td></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Estado Civil
											</h5></td>
										<td>
										<select class="form-control" name="estado_civil" required>
											<option value="Não Informado" <?php if($row_usuario->estado_civil == "Não Informado"){ echo 'selected';}?>>Não Informado</option>
											<option value="Casado" <?php if($row_usuario->estado_civil == "Casado"){ echo 'selected';}?>>Casado(a)</option>
											<option value="Desquitado" <?php if($row_usuario->estado_civil == "Desquitado"){ echo 'selected';}?>>Desquitado(a)</option>
											<option value="Marital" <?php if($row_usuario->estado_civil == "Marital"){ echo 'selected';}?>>Marital</option>
											<option value="Divorciado" <?php if($row_usuario->estado_civil == "Divorciado"){ echo 'selected';}?>>Divorciado(a)</option>
											<option value="Solteiro" <?php if($row_usuario->estado_civil == "Solteiro"){ echo 'selected';}?>>Solteiro(a)</option>
											<option value="Separado Judicialmente" <?php if($row_usuario->estado_civil == "Separado Judicialmente"){ echo 'selected';}?>>Separado(a) Judicialmente</option>
											<option value="União Estável" <?php if($row_usuario->estado_civil == "União Estável"){ echo 'selected';}?>>União Estável</option>
											<option value="Viúvo" <?php if($row_usuario->estado_civil == "Viúvo"){ echo 'selected';}?>>Viúvo(a)</option>
											<option value="Outros" <?php if($row_usuario->estado_civil == "Outros"){ echo 'selected';}?>>Outros(a)</option>
										</select></td>
										<td>Documento correspondente (Certidão de Nascimento /
											Casamento / União Estável etc)</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Grau de Instrução
											</h5></td>
										<td>
										<select class="form-control" name="escolaridade" required >
												<option value="Ensino Fundamental - 4ª Série Incom" <?php if($row_usuario->escolaridade == "Ensino Fundamental - 4ª Série Incom"){ echo 'selected';}?>>Ensino
													Fundamental - 4ª Série Incom</option>
												<option value="Ensino Fundamental - 4ª Série Compl" <?php if($row_usuario->escolaridade == "Ensino Fundamental - 4ª Série Compl"){ echo 'selected';}?>>Ensino
													Fundamental - 4ª Série Compl</option>
												<option value="Ensino Fundamental - 5ª a 8ª Série" <?php if($row_usuario->escolaridade == "Ensino Fundamental - 5ª a 8ª Série"){ echo 'selected';}?>>Ensino
													Fundamental - 5ª a 8ª Série</option>
												<option value="Ensino Fundamental Completo" <?php if($row_usuario->escolaridade == "Ensino Fundamental Completo"){ echo 'selected';}?>>Ensino
													Fundamental Completo</option>
												<option value="Ensino Médio Incompleto" <?php if($row_usuario->escolaridade == "Ensino Médio Incompleto"){ echo 'selected';}?>>Ensino Médio
													Incompleto</option>
												<option value="Ensino Médio Completo" <?php if($row_usuario->escolaridade == "Ensino Médio Completo"){ echo 'selected';}?>>Ensino Médio Completo</option>
												<option value="Técnico Incompleto" <?php if($row_usuario->escolaridade == "Técnico Incompleto"){ echo 'selected';}?>>Técnico Incompleto</option>
												<option value="Técnico Completo" <?php if($row_usuario->escolaridade == "Técnico Completo"){ echo 'selected';}?>>Técnico Completo</option>
												<option value="Educação Superior Incompleto">Educação
													Superior Incompleto</option <?php if($row_usuario->escolaridade == "Educação Superior Incompleto"){ echo 'selected';}?>>
												<option value="Educação Superior" <?php if($row_usuario->escolaridade == "Educação Superior"){ echo 'selected';}?>>Educação Superior</option>
												<option value="Pós Graduação" <?php if($row_usuario->escolaridade == "Pós Graduação"){ echo 'selected';}?>>Pós Graduação</option>
												<option value="Mestrado" <?php if($row_usuario->escolaridade == "Mestrado"){ echo 'selected';}?>>Mestrado</option>
												<option value="Doutorado" <?php if($row_usuario->escolaridade == "Doutorado"){ echo 'selected';}?>>Doutorado</option>
												<option value="PHD" <?php if($row_usuario->escolaridade == "PHD"){ echo 'selected';}?>>PHD</option>
										</select></td>
										<td>Comprovante de Escolaridade</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Naturalidade
											</h5></td>
										<td><input type="text" class="form-control letras"
											name="naturalidade" value="<?=$row_usuario->naturalidade?>" required /></td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
											<span style="color: #F00;">*</span> Nacionalidade
											</h5></td>
										<td>
										<select class="form-control" name="nacionalidade" required >
											<option value="Brasileira" <?php if($row_usuario->nacionalidade == "Brasileira"){ echo 'selected';}?>>Brasileira</option>
											<option value="Estrangeira" <?php if($row_usuario->nacionalidade == "Estrangeira"){ echo 'selected';}?>>Estrangeira</option>
										</select></td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Nome do Pai
											</h5></td>
										<td>
										<input type="text" class="form-control letras" name="pai" value="<?=$row_usuario->pai?>" required /></td>
										<td rowspan="2">Cópia do RG atualizado</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Nome da Mãe
											</h5></td>
										<td><input type="text" class="form-control letras" name="mae"	value="<?=$row_usuario->mae?>" required /></td>
									</tr>
									<tr>
										<td rowspan="22" style="background-color: #9cc2e3; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;"
											align="center"><div style="width: 100%">DOC.</div></td>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> CPF
											</h5></td>
										<td><input type="text" class="form-control" maxlength="11" onblur="TestaCPF(this)" name="cpf" id="cpf" value="<?=$row_usuario->cpf?>" required /></td>
										<td>Cópia de documento com CPF (RG / CNH / CPF)</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Identidade
											</h5></td>
										<td>
										<input type="text" class="form-control" id="rg" name="rg" value="<?=$row_usuario->rg?>" required /></td>&nbsp;
										<td rowspan="4">Cópia do RG</td>
									</tr>

									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Orgão Emissor
											</h5></td>
										<td><input type="text" class="form-control letras" name="rg_orgao"	value="<?=$row_usuario->rg_orgao?>" required /></td>&nbsp;
									</tr>

									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> UF
											</h5></td>
										<td><select type="text" class="form-control" name="rg_uf" required />
										<option value="<?=$row_usuario->rg_uf?>"><?php echo $row_usuario->rg_uf?></option>
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
													</td>
												</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">Expedição RG</h5></td>
										<td><input type="date" id="rg_exp" min="1900-01-01" max="2999-12-31" class="form-control" name="rg_exp" value="<?=$row_usuario->rg_exp?>"/>
										</td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">Certificado
												de Reservista</h5></td>
										<td><input type="text" class="form-control" id="reservista" name="reservista"
											value="<?=$row_usuario->reservista?>" /></td>
										<td>Cópia da Reservista</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> PIS/PASEP/NIT
											</h5></td>
										<td><input type="text" id="pis" class="form-control" name="pis" value="<?=$row_usuario->pis?>"  required /></td>
										<td rowspan="2">Cópia do Cartão do PIS</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">Expedição
												PIS/PASEP/NIT</h5></td>
										<td><input type="date" class="form-control" name="pis_exp" value="<?=$row_usuario->pis_exp?>" maxlength="10" /></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">Habilitação
												/ Categoria</h5></td>
										<td><input type="text" class="form-control letras" name="cnh"
											value="<?=$row_usuario->cnh?>" /></td>
										<td rowspan="6">Cópia CNH</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">Validade
												CNH</h5></td>
										<td><input type="date" class="form-control" min="2010-01-01" max="2030-12-31" name="cnh_val"	value="<?=$row_usuario->cnh_val?>" maxlength="10" />
										</td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">Expedição
												CNH</h5></td>
										<td><input type="date" class="form-control" min="1900-01-01" id="cnh_exp" name="cnh_exp"	value="<?=$row_usuario->cnh_exp?>" maxlength="10" />
										</td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">Órgão
												Emissor CNH</h5></td>
										<td><input type="text" class="form-control letras" name="cnh_org"
											value="<?=$row_usuario->cnh_org?>" size="3" />
										</td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">UF expedidora da CNH</h5></td>
										<td><select name="cnh_uf" class="form-control" required="required" style="margin-top: 10px;"
										<option value="<?=$row_usuario->cnh_uf?>"<?php echo $row_usuario->cnh_uf?></option>
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
													</td>
												<tr>
										<td class="text-right"><h5 style="font-weight: bold;">Data da
												primeira habilitação</h5></td>
										<td><input type="date" class="form-control" id="cnh_prim" min="1900-01-01" name="cnh_prim"	value="<?=$row_usuario->cnh_prim?>"  />
										</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> CTPS Nº
											</h5></td>
										<td><input type="text" class="form-control" id="ctps" name="ctps" value="<?=$row_usuario->ctps?>" required /></td>
										<td rowspan="4">Cópia da Carteira de Trabalho - página da Foto
											e do Verso</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Série
											</h5></td>
										<td><input type="text" class="form-control" id="ctpf_serie" name="ctpf_serie" value="<?=$row_usuario->ctpf_serie?>" required /></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> UF
											</h5></td>
										<td><select type="text" class="form-control" name="ctpf_uf" required />
										<option value="<?=$row_usuario->ctpf_uf?>"><?php echo $row_usuario->ctpf_uf?></option>
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
													</td>
												</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Data de Expedição CTPS
											</h5></td>
										<td><input type="date" class="form-control" min="1900-01-01" id="ctps_exp" name="ctps_exp"	value="<?=$row_usuario->ctps_exp?>" required />
										</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Título Eleitor
											</h5></td>
										<td><input type="text" class="form-control" id="titulo_eleitor" name="titulo_eleitor" value="<?=$row_usuario->titulo_eleitor?>" required /></td>
										<td rowspan="4">Cópia do Título de Eleitor</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Zona
											</h5></td>
										<td><input type="text" class="form-control" id="titulo_zona" name="titulo_zona" value="<?=$row_usuario->titulo_zona?>" required /></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Seção
											</h5></td>
										<td><input type="text" class="form-control" id="titulo_secao" name="titulo_secao" value="<?=$row_usuario->titulo_secao?>" required /></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Município Título
											</h5></td>
										<td><input type="text" class="form-control letras" name="titulo_municipio" value="<?=$row_usuario->titulo_municipio?>" required /></td>
									</tr>
									<tr>
										<td rowspan="14"
											style="background-color: #60a1d9; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;"
											align="center"><div style="width: 100%">END.</div></td>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> CEP
											</h5></td>
										<td><input type="text" class="form-control" name="cep" id="cep" value="<?=$row_usuario->cep?>" required /></td>
										<td rowspan="8">Comp. Residência atualizado com CEP</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Tipo
											</h5></td>
										<td>
										<select class="form-control" name="end_tipo" required >
											<option value="Alameda" <?php if($row_usuario->end_tipo == "Alameda"){ echo 'selected';}?>>Alameda</option>
											<option value="Avenida" <?php if($row_usuario->end_tipo == "Avenida"){ echo 'selected';}?>>Avenida</option>
											<option value="Estrada" <?php if($row_usuario->end_tipo == "Estrada"){ echo 'selected';}?>>Estrada</option>
											<option value="PRAÇA" <?php if($row_usuario->end_tipo == "PRAÇA"){ echo 'selected';}?>>PRAÇA</option>
											<option value="QUADRA" <?php if($row_usuario->end_tipo == "QUADRA"){ echo 'selected';}?>>QUADRA</option>
											<option value="RODOVIA" <?php if($row_usuario->end_tipo == "RODOVIA"){ echo 'selected';}?>>RODOVIA</option>
											<option value="RUA" <?php if($row_usuario->end_tipo == "RUA"){ echo 'selected';}?>>RUA</option>
											<option value="Travessa" <?php if($row_usuario->end_tipo == "Travessa"){ echo 'selected';}?>>Travessa</option>
											<option value="Via" <?php if($row_usuario->end_tipo == "Via"){ echo 'selected';}?>>Via</option>
											<option value="Vila" <?php if($row_usuario->end_tipo == "Vila"){ echo 'selected';}?>>Vila</option>
										</select>
									</td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Endereço
											</h5></td>
										<td><input type="text" class="form-control letras" name="endereco" value="<?=$row_usuario->endereco?>" required />
										</td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Nº
											</h5></td>
										<td><input type="text" class="form-control" id="end_num" name="end_num" value = "<?=$row_usuario->end_num?>" required />
										</td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">Complemento</h5></td>
										<td><input type="text" class="form-control" name="end_compl"
											value="<?=$row_usuario->end_compl?>" /></td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Bairro
											</h5></td>
										<td><input type="text" class="form-control letras" name="bairro" value="<?=$row_usuario->bairro?>" required /></td>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Município
											</h5></td>
										<td><input type="text" class="form-control letras" name="cidade" value="<?=$row_usuario->cidade?>" required /></td>
										<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Estado
											</h5></td>
										<td>
										<select class="form-control" name="estado" required >
										<option value="<?=$row_usuario->estado?>"><?php echo $row_usuario->estado?></option>
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
													</td>

									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">Telefone
												Residencial com DDD</h5></td>
										<td><input type="text" class="form-control" name="tel_resid" id="tel_resid"
											value="<?=$row_usuario->tel_resid?>" /></td>
										<td></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Telefone Celular com DDD
											</h5></td>
										<td>
										<input type="text" class="form-control" name="tel_cel" id="tel_cel" value="<?=$row_usuario->tel_cel?>" required /></td>
										<td></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Telefone para emergência
												com DDD
											</h5></td>
										<td><input type="text"	class="form-control" name="tel_emerg" id="tel_emerg" value="<?=$row_usuario->tel_emerg?>" required /></td>
										<td></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Nome do contato para
												emergência
											</h5></td>
										<td><input type="text"	class="form-control letras" name="emerg_contato" value="<?=$row_usuario->emerg_contato?>" required /></td>
										<td></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> E-mail Particular
											</h5></td>
										<td><input type="email"	class="form-control" name="email_partic" value="<?=$row_usuario->email_partic?>" required /></td>
										<td></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;"></span>E-mail Corporativo
											</h5></td>
										<td><input type="email" class="form-control" name="email_corp" value="<?=$row_usuario->email_corp?>" /></td>
										<td></td>
									</tr>
									<tr>
										<td rowspan="3" style="background-color: #9cc2e3; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;"
											align="center"><div style="width: 100%">SAÚDE</div></td>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span> Raça/Cor
											</h5></td>
										<td>
										<select class="form-control" name="raca" required >
												<option value="Albino" <?php if($row_usuario->raca == "Albino"){ echo 'selected';}?>>Albino</option>
												<option value="Amarelo" <?php if($row_usuario->raca == "Amarelo"){ echo 'selected';}?>>Amarelo</option>
												<option value="Branco" <?php if($row_usuario->raca == "Branco"){ echo 'selected';}?>>Branco</option>
												<option value="Indígena" <?php if($row_usuario->raca == "Indígena"){ echo 'selected';}?>>Indígena</option>
												<option value="Mulato" <?php if($row_usuario->raca == "Mulato"){ echo 'selected';}?>>Mulato</option>
												<option value="Negro" <?php if($row_usuario->raca == "Negro"){ echo 'selected';}?>>Negro</option>
												<option value="Pardo" <?php if($row_usuario->raca == "Pardo"){ echo 'selected';}?>>Pardo</option>
												<option value="Vermelho" <?php if($row_usuario->raca == "Vermelho"){ echo 'selected';}?>>Vermelho</option>
												<option value="Não Informado" <?php if($row_usuario->raca == "Não Informado"){ echo 'selected';}?>>Não Informado</option>
										</select></td>
										<td></td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">
												<span style="color: #F00;">*</span>Cartão Nacional de
												Saúde(CNS)
											</h5></td>
										<td><input type="text" class="form-control" id="cns" name="cns" value="<?=$row_usuario->cns?>" required /></td>
										<td>Cópia do cartão SUS</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">Pessoa
												Portadora de Deficiência</h5></td>
										<td><input type="text" class="form-control letras" name="ppd"
											value="<?=$row_usuario->ppd?>" /></td>
										<td>Laudo médico</td>
									</tr>
									<tr>
										<td rowspan="3"
											style="background-color: #60a1d9; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;"
											align="center"><div style="width: 100%">COMPL.</div></td>
										<td class="text-right"><h5 style="font-weight: bold;">Aposentado</h5></td>
										<td>
											<div class="radio">
												<label><input type="radio" name="aposentado" value="1" <?php if($row_usuario->aposentado == "1"){ echo 'checked';}?>>Sim</label>&nbsp;&nbsp;&nbsp;
												<label><input type="radio" name="aposentado" value="0" checked <?php if($row_usuario->aposentado == "0"){ echo 'checked';}?>>Não</label>
											</div>
										</td>
										<td rowspan="3" valign="bottom">Informar "Sim" ou "Não"</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">Possui
												Filho(s)</h5></td>
										<td>
											<div class="radio">
												<label><input type="radio" name="filhos" value="1" <?php if($row_usuario->filhos == "1"){ echo 'checked';}?>>Sim</label>&nbsp;&nbsp;&nbsp;
												<label><input type="radio" name="filhos" value="0" checked <?php if($row_usuario->filhos == "0"){ echo 'checked';}?>>Não</label>
											</div>
										</td>
									</tr>
									<tr>
										<td class="text-right"><h5 style="font-weight: bold;">Gestante</h5></td>
										<td>
											<div class="radio">
												<label><input type="radio" name="gestante" value="1" <?php if($row_usuario->gestante == "1"){ echo 'checked';}?>>Sim</label>&nbsp;&nbsp;&nbsp;
												<label><input type="radio" name="gestante" value="0" checked <?php if($row_usuario->gestante == "0"){ echo 'checked';}?>>Não</label>
											</div>

										</td>
									</tr>								
								</tbody>
							</table>
						</div>
						<div class="form-group">
							<h4 class="page-header text-center">
								<strong>VALIDAÇÃO - DEPENDENTES</strong>
							</h4>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-xs-12">
									<label><input type="radio" name="dependentes" value="1" class="sim" <?php if($row_usuario->dependentes == "1"){ echo 'checked';}?>>&nbsp;&nbsp;Possuo
										dependentes e informo neste formulário as alterações
										necessárias, validando inclusive se são meus dependentes para
										IRRF.</label>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<label><input type="radio" name="dependentes" value="0" class="nao" <?php if($row_usuario->dependentes == "0"){ echo 'checked';}?> required>&nbsp;&nbsp;
										Não possuo dependentes</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-xs-12">
									<p>
										<b>Atenção: as alterações serão aceitas mediante entrega de
											cópia do comprovante no RH.</b>
									</p>
								</div>
							</div>
						</div>
						<div class="divdependentes">
    						<table class="table">
    							<thead>
    								<tr>
    									<th style="background-color: #5a8cb7; color: #FFF;"
    										class="col-xs-1 text-center">Aba</th>
    									<th style="background-color: #5a8cb7; color: #FFF;"
    										class="col-xs-2 text-center">Informação</th>
    									<th style="background-color: #5a8cb7; color: #FFF;"
    										class="col-xs-6 text-center">Alterar / Corrigir para</th>
    									<th style="background-color: #5a8cb7; color: #FFF;"
    										class="col-xs-3 text-center">Comprovante necessário
    									</th>
    								</tr>
    							</thead>
    							<tbody>
    								<tr>
    									<td colspan="3" style="font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px;" align="right">
    									<input type="hidden" id="sIdi" value="-1">
    									Adicionar Novo Dependente</td>
    									<td align="center"><button class="btn btn-success"
    											type="button" onclick="dep_fields();">
    											<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
    										</button>
    									</td>
    								</tr>
    							</tbody>
    						</table>
						</div>
						<div id="dep_fields"></div>
						<div class="clear"></div>
						<?php 
						$query_usuario_depen = $this->db->query("SELECT * from cad_colaborador_dependentes WHERE id_funcionario = '$idfuncionario'");			
						
						if($query_usuario_depen->num_rows() != 0 ){
						$i = 1;    
						foreach ($query_usuario_depen->result_array() as $row_usuario_depen){						    
						?>
						<input type="hidden" class="form-control" name="dep_id<?=$i?>" value="<?=$row_usuario_depen['id']?>" />
						<table class="table">
                            <thead>
                                <tr>
                                <th style="background-color:#5a8cb7; color:#FFF;" class="col-xs-1 text-center">Aba</th>
                                <th style="background-color:#5a8cb7; color:#FFF;" class="col-xs-2 text-center">Informação</th>
                                <th style="background-color:#5a8cb7; color:#FFF;" class="col-xs-6 text-center">Alterar / Corrigir para</th>
                                <th style="background-color:#5a8cb7; color:#FFF;" class="col-xs-2 text-center">Comprovante necessário</th>
                                <th style="background-color:#5a8cb7; color:#FFF;" class="col-xs-1 text-center"><button class="btn btn-danger" type="button" onclick="excluir(<?=$row_usuario_depen['id']?>)" ><span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button></th>
                                </tr>
                            </thead>
                            <tbody>                            
                                <tr>
                            	    <td rowspan="9" style="background-color:#60a1d9; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center"><div style="width:150px;">Dependente</div></td>
                                	<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Nome Completo</h5></td>
                                	<td><input type="text" class="form-control letras" name="dep_nome<?=$i?>" value="<?=$row_usuario_depen['dep_nome']?>" required /></td>
                                	<td rowspan="9" colspan="2" >Certidão de Nascimento/Casamento, CPF e RG do Conjuge<br> <span style="color:#A60000">IMPORTANTE:</span> Informar o CPF do filhos, principalmente maiores de 12 anos</td>
                            	</tr>                            
                                <tr>
                                	<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Estado Civil</h5></td>
                            	    <td>
                                    <select class="form-control" name="dep_estado_civil<?=$i?>" required >
                                        <option value="Casado" <?php if($row_usuario_depen['dep_estado_civil'] == "Casado"){ echo 'selected';}?>>Casado(a)</option>
                                        <option value="Divorciado" <?php if($row_usuario_depen['dep_estado_civil'] == "Divorciado"){ echo 'selected';}?>>Divorciado(a)</option>
                                        <option value="Marital" <?php if($row_usuario_depen['dep_estado_civil'] == "Marital"){ echo 'selected';}?>>Marital</option>
                                        <option value="Separado Judicialmente" <?php if($row_usuario_depen['dep_estado_civil'] == "Separado Judicialmente"){ echo 'selected';}?>>Separado(a) Judicialmente</option>
                                        <option value="Solteiro" <?php if($row_usuario_depen['dep_estado_civil'] == "Solteiro"){ echo 'selected';}?>>Solteiro(a)</option>
                                        <option value="União Estável" <?php if($row_usuario_depen['dep_estado_civil'] == "União Estável"){ echo 'selected';}?>>União Estável</option>
                                        <option value="Viúvo" <?php if($row_usuario_depen['dep_estado_civil'] == "Viúvo"){ echo 'selected';}?>>Viúvo(a)</option>
                                        <option value="Outros" <?php if($row_usuario_depen['dep_estado_civil'] == "Outros"){ echo 'selected';}?>>Outros(a)</option>
                                    </select>	
                                	</td>
                                </tr>
                                <tr><td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> CPF</h5></td>
                                <td><input type="text" class="form-control" name="dep_cpf<?=$i?>" id="dep_cpf<?=$i?>" value="<?=$row_usuario_depen['dep_cpf']?>" required /></td></tr>
                                <tr>
                                <td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Parentesco</h5></td>
                                <td>
                                <select class="form-control" name="dep_parentesco<?=$i?>" required >
                                    <option value="Companheiro(a)"	<?php if($row_usuario_depen['dep_parentesco'] == "Companheiro(a)"){ echo 'selected';}?>>Companheiro(a)</option>
                                    <option value="Cônjuge"	<?php if($row_usuario_depen['dep_parentesco'] == "Cônjuge"){ echo 'selected';}?>>Cônjuge</option>
                                    <option value="Enteado(a)"	<?php if($row_usuario_depen['dep_parentesco'] == "Enteado(a)"){ echo 'selected';}?>>Enteado(a)</option>
                                    <option value="Filho(a)" <?php if($row_usuario_depen['dep_parentesco'] == "Filho(a)"){ echo 'selected';}?> >Filho(a)</option>
                                    <option value="Guarda Judicial"	<?php if($row_usuario_depen['dep_parentesco'] == "Guarda Judicial"){ echo 'selected';}?>>Guarda Judicial</option>
                                    <option value="Tutela/Curatela"	<?php if($row_usuario_depen['dep_parentesco'] == "Tutela/Curatela"){ echo 'selected';}?>>Tutela/Curatela</option>
                                    <option value="Ex Cônjuge - Pensão Alimentícia"	<?php if($row_usuario_depen['dep_parentesco'] == "Ex Cônjuge - Pensão Alimentícia"){ echo 'selected';}?>>Ex Cônjuge - Pensão Alimentícia</option>
                                    <option value="Outros"	<?php if($row_usuario_depen['dep_parentesco'] == "Outros"){ echo 'selected';}?>>Outros</option>
                                </select>	
                            	</td>                            
                                </tr>                            
                                <tr>
                                	<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Sexo</h5>
                                	<td>
                                    <select class="form-control" name="dep_sexo<?=$i?>" required >
                                        <option value="Feminino" <?php if($row_usuario_depen['dep_sexo'] == "Feminino"){ echo 'selected';}?>>Feminino</option>
                                        <option value="Masculino" <?php if($row_usuario_depen['dep_sexo'] == "Masculino"){ echo 'selected';}?>>Masculino</option>
                                    </select>
                                	</td>
                                </tr>
                                <tr><td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Data de nascimento</h5></td>
                                <td><input type="date" class="form-control" id="dep_dtNasc" max="2999-12-31" name="dep_dtNasc<?=$i?>" value="<?=$row_usuario_depen['dep_dtNasc']?>" required /></td></tr>
                                <tr><td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Nome da mãe</h5></td>
                                <td><input type="text" class="form-control letras" name="dep_mae<?=$i?>" value="<?=$row_usuario_depen['dep_mae']?>" required /></td></tr>
                                <tr><td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;"></span> Data do Casamento / União</h5></td>
                                <td><input type="date" class="form-control" name="dep_dtCasam<?=$i?>" value="<?=$row_usuario_depen['dep_dtCasam']?>" /></td></tr>
                                <tr><td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Naturalidade</h5></td>
                                <td><input type="text" class="form-control letras" name="dep_naturalidade<?=$i?>" value="<?=$row_usuario_depen['dep_naturalidade']?>" required /></td></tr>
                                <tr>
                                	<td rowspan="3" style="background-color:#9cc2e3; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center"><div style="width:100%">SAÚDE </div></td>
                                	<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Raça/Cor</h5></td>
                                    <td>
                                    <select class="form-control" name="dep_raca<?=$i?>" required >
                                        <option value="Não Informado" <?php if($row_usuario_depen['dep_raca'] == "Não Informado"){ echo 'selected';}?>>Não Informado</option>
                                        <option value="Albino" <?php if($row_usuario_depen['dep_raca'] == "Albino"){ echo 'selected';}?>>Albino</option>                                        
                                        <option value="Amarelo"	<?php if($row_usuario_depen['dep_raca'] == "Amarelo"){ echo 'selected';}?>>Amarelo</option>                                        
                                        <option value="Branco" 	<?php if($row_usuario_depen['dep_raca'] == "Branco"){ echo 'selected';}?>>Branco</option>
                                        <option value="Indígena" <?php if($row_usuario_depen['dep_raca'] == "Indígena"){ echo 'selected';}?>>Indígena</option>                                        
                                        <option value="Mulato" <?php if($row_usuario_depen['dep_raca'] == "Mulato"){ echo 'selected';}?>>Mulato</option>                                       
                                        <option value="Negro" <?php if($row_usuario_depen['dep_raca'] == "Negro"){ echo 'selected';}?>>Negro</option>
                                        <option value="Pardo" <?php if($row_usuario_depen['dep_raca'] == "Pardo"){ echo 'selected';}?>>Pardo</option>                                        
                                        <option value="Vermelho" <?php if($row_usuario_depen['dep_raca'] == "Vermelho"){ echo 'selected';}?>>Vermelho</option>                                        
                                        </select>
                                    </td>                            
                                	<td colspan="2"></td>
                            	</tr>		
                                <tr>
                                	<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Cartão Nacional de Saúde(CNS)</h5></td>
                                    <td><input type="text" class="form-control" id="dep_cns" name="dep_cns<?=$i?>" value="<?=$row_usuario_depen['dep_cns']?>" required /></td>
                                	<td colspan="2">Cópia do cartão SUS</td>
                            	</tr>		
                                <tr>
                                	<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Pessoa Portadora de Deficiência</h5></td>
                                	<td>
                                        <div class="radio">
                                          <label><input type="radio" name="dep_ppd<?=$i?>" value="1" <?php if($row_usuario_depen['dep_ppd'] == "1"){ echo 'checked';}?> required ch>Sim</label>&nbsp;&nbsp;&nbsp;
                                          <label><input type="radio" name="dep_ppd<?=$i?>" value="0" <?php if($row_usuario_depen['dep_ppd'] == "0"){ echo 'checked';}?> required >Não</label>
                                        </div>
                                    </td>
                                    <td colspan="2">Laudo médico</td>
                            	</tr>                            
                                <tr>
                                	<td rowspan="3" style="background-color:#60a1d9; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center"><div style="width:150px;">Reg. Nasc</div></td>
                                	<td class="text-right"><h5 style="font-weight:bold;">Cartório</h5></td>
                                    <td><input type="text" class="form-control" name="dep_cartorio<?=$i?>" value="<?=$row_usuario_depen['dep_cartorio']?>" /></td>
                                	<td rowspan="3" colspan="2" >Certidão de Nascimento (somente em caso de filho(a))</td>
                            	</tr>
                                <tr><td class="text-right"><h5 style="font-weight:bold;">Registro(Livro/Folha)</h5>
                                <td><input type="text" class="form-control" name="dep_registro<?=$i?>" value="<?=$row_usuario_depen['dep_registro']?>" /></td></tr>
                                <tr><td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Nº da Declaração de Nascido Vivo</h5></td>
                                <td><input type="text" class="form-control" id="dep_declar_vivo" name="dep_declar_vivo<?=$i?>" value="<?=$row_usuario_depen['dep_declar_vivo']?>" required /></td></tr>
                                <tr>
                            	    <td style="background-color:#60a1d9; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center">IRRF</td>
                                	<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Dependente de IRRF </h5></td>
                                	<td>
                                        <div class="radio">
                                          <label><input type="radio" name="dep_irrf<?=$i?>" value="1"  <?php if($row_usuario_depen['dep_irrf'] == "1"){ echo 'checked';}?> required >Sim</label>&nbsp;&nbsp;&nbsp;
                                          <label><input type="radio" name="dep_irrf<?=$i?>"    value="0" <?php if($row_usuario_depen['dep_irrf'] == "0"){ echo 'checked';}?> required >Não</label>
                                        </div>
                            	    </td>
                                	<td  colspan="2" valign="bottom">Informar "Sim" ou "Não"</td>
                            	</tr>
                            </tbody>
                            </table>
						<?php $i++;}} ?>	
						<div class="form-group">
							<h4 class="page-header">&nbsp;</h4>
						</div>
						<br>
						<div class="form-group text-center">
							<input type="submit" id="enviar" class="btn btn-success btn-small" value="Atualizar" />
							<!--<a href="../espaco_rh/" class="btn btn-default" >&nbsp;&nbsp;Voltar&nbsp;&nbsp;</a>-->
							&nbsp;&nbsp;  
								<input type="button" class="btn btn-default" name="imprimir" value="Imprimir" onclick="printIt()">

						</div>
					</form>
					</div>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
	</div>
</div>
<script src="<?php echo base_url('assets/js/jquery.mask.js');?>"></script>
<script type="text/javascript">
$('.letras').mask('Z',{translation: {'Z': {pattern: /[a-zA-Z áéíóúâêôãõç]/, recursive: true}}});
$('#reservista').mask('00000000000000000000000000');
$('#pis').mask('000000000000');
$('#ctps').mask('000000000000000');
$('#ctpf_serie').mask('000000000000000');
$('#rg').mask('00.000.000-0');
$('#titulo_eleitor').mask('000000000000');
$('#titulo_zona').mask('000');
$('#titulo_secao').mask('0000');
$('#cns').mask('000000000000000');
$('#dep_cns').mask('000000000000000');
$('#end_num').mask('000000');

function TestaCPF(elemento) {
  cpf = elemento.value;
  cpf = cpf.replace(/[^\d]+/g, '');
  if (cpf == '') 
  return elemento.style.backgroundColor = alert("CPF Inválido");
  // Elimina CPFs invalidos conhecidos    
  if (cpf.length != 11 ||
    cpf == "00000000000" ||
    cpf == "11111111111" ||
    cpf == "22222222222" ||
    cpf == "33333333333" ||
    cpf == "44444444444" ||
    cpf == "55555555555" ||
    cpf == "66666666666" ||
    cpf == "77777777777" ||
    cpf == "88888888888" ||
    cpf == "99999999999")
    return elemento.style.backgroundColor = alert("CPF Inválido");
  // Valida 1o digito 
  add = 0;
  for (i = 0; i < 9; i++)
    add += parseInt(cpf.charAt(i)) * (10 - i);
  rev = 11 - (add % 11);
  if (rev == 10 || rev == 11)
    rev = 0;
  if (rev != parseInt(cpf.charAt(9)))
    return elemento.style.backgroundColor = alert("CPF Inválido");
  // Valida 2o digito 
  add = 0;
  for (i = 0; i < 10; i++)
    add += parseInt(cpf.charAt(i)) * (11 - i);
  rev = 11 - (add % 11);
  if (rev == 10 || rev == 11)
    rev = 0;
  if (rev != parseInt(cpf.charAt(10)))
   return elemento.style.backgroundColor = alert("CPF Inválido");
  return elemento.style.backgroundColor = alert("CPF Válido");
}

var input = document.getElementById('dtNasc');
input.addEventListener('change', function() {
  var agora = new Date();
  var escolhida = new Date(this.value);
  if (escolhida > agora) this.value = [agora.getFullYear(), agora.getMonth() + 1, agora.getDate()].map(v => v < 10 ? '0' + v : v).join('-');
});
var input = document.getElementById('rg_exp');
input.addEventListener('change', function() {
  var agora = new Date();
  var escolhida = new Date(this.value);
  if (escolhida > agora) this.value = [agora.getFullYear(), agora.getMonth() + 1, agora.getDate()].map(v => v < 10 ? '0' + v : v).join('-');
});
var input = document.getElementById('cnh_exp');
input.addEventListener('change', function() {
  var agora = new Date();
  var escolhida = new Date(this.value);
  if (escolhida > agora) this.value = [agora.getFullYear(), agora.getMonth() + 1, agora.getDate()].map(v => v < 10 ? '0' + v : v).join('-');
});
var input = document.getElementById('cnh_prim');
input.addEventListener('change', function() {
  var agora = new Date();
  var escolhida = new Date(this.value);
  if (escolhida > agora) this.value = [agora.getFullYear(), agora.getMonth() + 1, agora.getDate()].map(v => v < 10 ? '0' + v : v).join('-');
});
var input = document.getElementById('ctps_exp');
input.addEventListener('change', function() {
  var agora = new Date();
  var escolhida = new Date(this.value);
  if (escolhida > agora) this.value = [agora.getFullYear(), agora.getMonth() + 1, agora.getDate()].map(v => v < 10 ? '0' + v : v).join('-');
});
var input = document.getElementById('dep_dtNasc');
input.addEventListener('change', function() {
  var agora = new Date();
  var escolhida = new Date(this.value);
  if (escolhida > agora) this.value = [agora.getFullYear(), agora.getMonth() + 1, agora.getDate()].map(v => v < 10 ? '0' + v : v).join('-');
});

function printIt(printThis) {
	let divToPrint = document.getElementById('print');
    let htmlToPrint = 
        '<style type="text/css">' + 
        'table {'+'font-family: arial, sans-serif;'+ 
        'border-collapse: collapse;'+'width: 95%;'+ 
        'margin-left: 10px'+'}'+   
        'th, td {' +
        'border:1px solid #000;' +
        'padding: 20px;' +
        '}'+ 'tr:nth-child(even) {'+
        'background-color: #dddddd;'+'}'+
        '</style>';
    htmlToPrint += divToPrint.outerHTML;
    let windowToPrint = window.open("");    
    windowToPrint.document.write(htmlToPrint);
    windowToPrint.print();
    windowToPrint.close();
}


$('input:radio[name="dependentes"]').change(function() {
	var val = $(this).val();
	
    if (val == '1'){      
        $(".divdependentes").show();                         
    }else{  
    	$(".divdependentes").hide(); 
    }
});

<?php if($row_usuario->dependentes == "1"){ ?>
	$(".divdependentes").show(); 
<?php }else{?>
	$(".divdependentes").hide(); 
<?php }?>



function excluir(id) {

	var r = confirm("Desseja realmente excluir esse(a) dependente? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_dependente').'/' ?>"+id;
    
         $.ajax({
            url : url,
            type : 'POST',
            beforeSend: function(){
    			alert('Aguarde um momento por favor...');
            },
            success : function(data){
            	alert(data);
    	        window.location.reload();  
            },
            error : function () {
                alert('Error...');
            }
        });
	}
}

$('#cpf').mask('000.000.000-00');
$('#dep_cpf1').mask('000.000.000-00');
$('#dep_cpf2').mask('000.000.000-00');
$('#dep_cpf3').mask('000.000.000-00');
$('#dep_cpf4').mask('000.000.000-00');
$('#dep_cpf5').mask('000.000.000-00');
$('#dep_cpf6').mask('000.000.000-00');
$('#cep').mask('00.000-000');
$('#tel_resid').mask('(00) 0000-0000');
$('#tel_cel').mask('(00) 00000-0000');
$('#tel_emerg').mask('(00) 00000-0000');
$('#dep_declar_vivo').mask('00000000000000000000000000000000000');

var roomI = 0;

function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}

function formatarCpf(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
                                             //de novo (para o segundo bloco de números)
    v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2") //Coloca um hífen entre o terceiro e o quarto dígitos
    return v
}

function dep_fields() {
	
	sIdi = parseInt(document.getElementById('sIdi').value, 10);

	roomI++;

	if(roomI > 10 ){
		alert('Número máximo de campos excedido!')	
		return false;
	}	
	
	var objTo = document.getElementById('dep_fields')
	var divtest = document.createElement("div");
	divtest.setAttribute("class", "form-group removeclass"+roomI);
	var rdiv = 'removeclass'+roomI;
	var form = '';


form += '	<table class="table">'
form += '	<thead>'
form += '	    <tr>'
form += '	    <th style="background-color:#5a8cb7; color:#FFF;" class="col-xs-1 text-center">Aba</th>'
form += '	    <th style="background-color:#5a8cb7; color:#FFF;" class="col-xs-2 text-center">Informação</th>'
form += '	    <th style="background-color:#5a8cb7; color:#FFF;" class="col-xs-6 text-center">Alterar / Corrigir para</th>'
form += '	    <th style="background-color:#5a8cb7; color:#FFF;" class="col-xs-2 text-center">Comprovante necessário</th>'
form += '	    <th style="background-color:#5a8cb7; color:#FFF;" class="col-xs-1 text-center"><button class="btn btn-danger" type="button" onclick="remove_dep_fields('+ roomI +');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button></th>'
form += '	    </tr>'
form += '	</thead>'
form += '	<tbody>'

form += '	    <tr>'
form += '		    <td rowspan="9" style="background-color:#60a1d9; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center"><div style="width:150px;">Dependente '+ roomI +'</div></td>'
form += '	    	<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Nome Completo</h5></td><td><input type="text" class="form-control letras" name="dep_nome'+ roomI +'" required/></td>'
form += '	    	<td rowspan="9" colspan="2" >Certidão de Nascimento/Casamento, CPF e RG do Conjuge<br> <span style="color:#A60000">IMPORTANTE:</span> Informar o CPF do filhos, principalmente maiores de 12 anos</td>'
form += '		</tr>'

form += '	    <tr>'
form += '	    	<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Estado Civil</h5></td>'
form += '		    <td>'
form += '	        <select class="form-control" name="dep_estado_civil'+ roomI +'" required>'
form += '	            <option value="Casado" 					>Casado(a)</option>'
form += '	            <option value="Divorciado" 				>Divorciado(a)</option>'
form += '	            <option value="Marital" 				>Marital</option>'
form += '	            <option value="Separado Judicialmente" 	>Separado(a) Judicialmente</option>'
form += '	            <option value="Solteiro" 				>Solteiro(a)</option>'
form += '	            <option value="União Estável" 			>União Estável</option>'
form += '	            <option value="Viúvo" 					>Viúvo(a)</option>'
form += '	            <option value="Outros" 					>Outros(a)</option>'
form += '	        </select>'	
form += '	    	</td>'
form += '	    </tr>'
form += '	    <tr><td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> CPF</h5></td><td><input type="text" class="form-control" name="dep_cpf'+ roomI +'" onKeyPress="mascara(this, formatarCpf)" maxlength="14" required/></td></tr>'
form += '	    <tr>'
form += '	    <td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Parentesco</h5></td>'
form += '	    <td>'
form += '	    <select class="form-control" name="dep_parentesco'+ roomI +'" required>'
form += '	        <option value="Companheiro(a)"					>Companheiro(a)</option>'
form += '	        <option value="Cônjuge"							>Cônjuge</option>'
form += '	        <option value="Enteado(a)"						>Enteado(a)</option>'
form += '	        <option value="Filho(a)"						>Filho(a)</option>'
form += '	        <option value="Guarda Judicial"					>Guarda Judicial</option>'
form += '	        <option value="Tutela/Curatela"					>Tutela/Curatela</option>'
form += '	        <option value="Ex Cônjuge - Pensão Alimentícia"	>Ex Cônjuge - Pensão Alimentícia</option>'
form += '	        <option value="Outros"							>Outros</option>'
form += '	    </select>'	
form += '		</td>'

form += '	    </tr>'

form += '	    <tr>'
form += '	    	<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Sexo</h5>'
form += '	    	<td>'
form += '	        <select class="form-control" name="dep_sexo'+ roomI +'" required>'
form += '	            <option value="Feminino ">Feminino</option>'
form += '	            <option value="Masculino">Masculino</option>'
form += '	        </select>'
form += '	    	</td>'
form += '	    </tr>'
form += '	    <tr><td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Data de nascimento</h5></td><td><input type="date" class="form-control" id="dtNasc" name="dep_dtNasc'+ roomI +'" size="10"   maxlength="10"  /></td></tr>'
form += '	    <tr><td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Nome da mãe</h5></td><td><input type="text" class="form-control letras" name="dep_mae'+ roomI +'" /></td></tr>'
form += '	    <tr><td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;"></span> Data do Casamento / União</h5></td><td><input type="date" class="form-control" name="dep_dtCasam'+ roomI +'" size="10"   maxlength="10"  /></td></tr>'
form += '	    <tr><td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Naturalidade</h5></td><td><input type="text" class="form-control letras" name="dep_naturalidade'+ roomI +'" /></td></tr>'

form += '	    <tr>'
form += '	    	<td rowspan="3" style="background-color:#9cc2e3; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center"><div style="width:100%">SAÚDE '+ roomI +' </div></td>'
form += '	    	<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Raça/Cor</h5></td>'
form += '	        <td>'
form += '	        <select class="form-control" name="dep_raca'+ roomI +'" required>'
form += '	            <option value="Não Informado"	>Não Informado</option>'
form += '	            <option value="Albino"			>Albino</option>'                                        
form += '	            <option value="Amarelo"			>Amarelo</option>'                                        
form += '	            <option value="Branco" 			>Branco</option>'
form += '	            <option value="Indígena"		>Indígena</option>'                                        
form += '	            <option value="Mulato"			>Mulato</option>'                                       
form += '	            <option value="Negro" 			>Negro</option>'
form += '	            <option value="Pardo" 			>Pardo</option>'                                        
form += '	            <option value="Vermelho"		>Vermelho</option>'                                        
form += '	            </select>'
form += '	        </td>'

form += '	    	<td colspan="2"></td>'
form += '		</tr>'		
form += '	    <tr>'
form += '	    	<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;"></span> Cartão Nacional de Saúde(CNS)</h5></td>'
form += '	        <td><input type="text" class="form-control" name="dep_cns'+ roomI +'" /></td>'
form += '	    	<td colspan="2">Cópia do cartão SUS</td>'
form += '		</tr>'		
form += '	    <tr>'
form += '	    	<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Pessoa Portadora de Deficiência</h5></td>'
form += '	    	<td>'
form += '	            <div class="radio">'
form += '	              <label><input type="radio" name="dep_ppd'+ roomI +'" value="1">Sim</label>&nbsp;&nbsp;&nbsp;'
form += '	              <label><input type="radio" name="dep_ppd'+ roomI +'" value="0" required>Não</label>'
form += '	            </div>'
form += '	        </td>'
form += '	        <td colspan="2">Laudo médico</td>'
form += '		</tr>'

form += '	    <tr>'
form += '	    	<td rowspan="3" style="background-color:#60a1d9; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center"><div style="width:150px;">Reg. Nasc '+ roomI +'</div></td>'
form += '	    	<td class="text-right"><h5 style="font-weight:bold;">Cartório</h5></td>'
form += '	        <td><input type="text" class="form-control" name="dep_cartorio'+ roomI +'" /></td>'
form += '	    	<td rowspan="3" colspan="2" >Certidão de Nascimento (somente em caso de filho(a))</td>'
form += '		</tr>'
form += '	    <tr><td class="text-right"><h5 style="font-weight:bold;">Registro(Livro/Folha)</h5><td><input type="text" class="form-control" name="dep_registro'+ roomI +'" /></td></tr>'
form += '	    <tr><td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;"></span> Nº da Declaração de Nascido Vivo</h5></td><td><input type="text" class="form-control" name="dep_declar_vivo'+ roomI +'" /></td></tr>'

form += '	    <tr>'
form += '		    <td style="background-color:#60a1d9; font-family: Gotham, Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: lighter; font-size: 20px; color: #FFFFFF;" align="center">IRRF '+ roomI +'</td>'
form += '	    	<td class="text-right"><h5 style="font-weight:bold;"><span style="color:#F00;">*</span> Dependente de IRRF </h5></td>'
form += '	    	<td>'
form += '	            <div class="radio">'
form += '	              <label><input type="radio" name="dep_irrf'+ roomI +'"  value="1">Sim</label>&nbsp;&nbsp;&nbsp;'
form += '	              <label><input type="radio" name="dep_irrf'+ roomI +'"  checked  value="0" required>Não</label>'
form += '	            </div>'
form += '		    </td>'
form += '	    	<td  colspan="2" valign="bottom">Informar "Sim" ou "Não"</td>'
form += '		</tr>'

form += '	</tbody>'
form += '	</table>'
                 

	divtest.innerHTML = form;
	objTo.appendChild(divtest)
	Objx('sIdi').value=roomI;
}

function remove_dep_fields(rid) {
	
		document.getElementById('sIdi').value = rid-1;
	
	   $('.removeclass'+rid).remove();
}  
</script>