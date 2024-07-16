<br>
<script src="<?=base_url('assets/js/jquery.mask.js')?>"></script>
<script src="<?=base_url('assets/js/jquery.mask.min.js')?>"></script>
<style>
    div#myModal {
        width: 90% !important;
        left: 25% !important;
    }
    .line-dados{
        display: inline-flex;
    }
    .box-input{
        padding-left: 15px;
    }
</style>
<?php $liberacao_admin = $this->auth->is_allowed_block('admin_permissoes'); ?>
<div class="row">
    <div class="btn-group col-md-12" >
        <a data-toggle="tab" href="#home" class="col-sm-3 btn btn-primary"><div style="text-align: center; font-size: 40px;"><i class="fa fa-address-card-o"></i></div><div style="text-align: center;">Dados Pessoais</div></a>
        <a data-toggle="tab" href="#prof" class="col-sm-3 btn btn-info "><div style="text-align: center; font-size: 40px;"><i class="fa fa-handshake-o"></i></div><div style="text-align: center;">Dados Profissionais</div></a>
        <a data-toggle="tab" href="#bank" class="col-sm-3 btn btn-warning"><div style="text-align: center; font-size: 40px;"><i class="fa fa-check-square-o"></i></div><div style="text-align: center;">Dados Bancários</div></a>
        <a <?= $liberacao_admin ? 'data-toggle="tab" href="#acess"' : 'disabled="disabled" title="Acesso Negado!"'?> class="col-sm-3 btn btn-success"><div style="text-align: center; font-size: 40px;"><i class="fa fa-check-square-o"></i></div><div style="text-align: center;">Acessos</div></a>
    </div>
</div>
<br>
<div class="tab-content">
    <div id="home" class="tab-pane fade in active well">
        <div class="dados-pessoais control-group">
            <label><h4><b>Dados Pessoais</b></h4></label>
            <div class="col-sm-2" style="float: right;">
                <img src="<?= base_url('media/img/userEdit_masc.png') ?>" style="width: 13em; height: 15em; position: absolute;"/>
            </div>
            <div class="form-group">
            	<div class="col-sm-12">
                    <div class="col-sm-6">
                        <label>Nome:</label>
                        <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $usuario->nome?>" placeholder="Nome Completo do Funcionário" required />
                    </div>
                    <div class="col-sm-2">
                        <label>Nacionalidade:</label>
                        <input type="text" id="nacionalidade" name="nacionalidade" class="form-control" value="<?= isset($usuario->nacionalidade) ? $usuario->nacionalidade : '' ?>" placeholder="Brasileiro(a)" required />
                    </div>
                    <div class="col-sm-2">
                        <label>Naturalidade:</label>
                        <input type="text" id="naturalidade" name="naturalidade" class="form-control" value="<?= isset($usuario->naturalidade) ? $usuario->naturalidade : ''?>" placeholder="Guarabira" required />
                    </div>
                </div>
            </div>
            <div class="form-group">
            	<div class="col-sm-12">
                    <div class="col-sm-2">
                        <label>Data de Nascimento:</label>
                        <input type="date" id="data_nasc" name="data_nasc" class="form-control" value="<?= isset($usuario->data_nasc) ? $usuario->data_nasc : date('Y-m-d') ?>" required>
                    </div>

                    <div class="col-sm-2">
                        <label for="civil">Estado Civil:</label>
                        <select class="form-control span12" name="estado_civil" id="civil">
                            <option <?= $usuario->civil == 'Solteiro(a)' ? 'selected' : '' ?>>Solteiro(a)</option>
                            <option <?= $usuario->civil == 'Casado(a)' ? 'selected' : '' ?>>Casado(a)</option>
                            <option <?= $usuario->civil == 'Viuvo(a)' ? 'selected' : '' ?>>Viuvo(a)</option>
                            <option <?= $usuario->civil == 'Divorciado(a)' ? 'selected' : '' ?>>Divorciado(a)</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label for="grau">Grau de formação:</label>
                        <select class="form-control span12" id="formacao" name="formacao" id="raca">
                            <option <?= $usuario->formacao == 'Ensino Fundamental Incompleto' ? 'selected' : '' ?>>Ensino Fundamental Incompleto</option>
                            <option <?= $usuario->formacao == 'Ensino Fundamental Completo' ? 'selected' : '' ?>>Ensino Fundamental Completo</option>
                            <option <?= $usuario->formacao == 'Ensino Médio Incompleto' ? 'selected' : '' ?>>Ensino Médio Incompleto</option>
                            <option <?= $usuario->formacao == 'Ensino Médio Completo' ? 'selected' : '' ?>>Ensino Médio Completo</option>
                            <option <?= $usuario->formacao == 'Ensino Superior Incompleto' ? 'selected' : '' ?>>Ensino Superior Incompleto</option>
                            <option <?= $usuario->formacao == 'Ensino Superior Completo' ? 'selected' : '' ?>>Ensino Superior Completo</option>
                            <option <?= $usuario->formacao == 'Pós-Graduação Incompleta' ? 'selected' : '' ?>>Pós-Graduação Incompleta</option>
                            <option <?= $usuario->formacao == 'Pós-Graduação Completa' ? 'selected' : '' ?>>Pós-Graduação Completa</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label>Sexo:</label>
                        <div class="form-group">
                        	<label class="radio-inline"><input type="radio" name="sexo" <?= $usuario->sexo == 'M' ? 'checked' : $usuario->sexo != 'F' ? 'checked' : ''; ?> value="M">Masculino</label>
                        	<label class="radio-inline"><input type="radio" name="sexo" <?= $usuario->sexo == 'F' ? 'checked' : '' ?> value="F">Feminino</label>
                   		</div>
                    </div>
            	</div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="col-sm-2">
                        <label>CPF:</label>
                        <input id="cpf" name="cpf" type="text" class="form-control" value="<?= isset($usuario->cpf) ? $usuario->cpf : '' ?>" placeholder="000.000.000-00">
                    </div>
                    <div class="col-sm-2">
                        <label>RG:</label>
                        <input id="rg" name="rg" type="text" class="form-control" value="<?= isset($usuario->rg) ? $usuario->rg : '' ?>" placeholder="Numero do RG">
                    </div>
                    <div class="col-sm-2">
                        <label>Orgão Emissor:</label>
                        <input id="emissor_rg" class="form-control" name="emissor_rg" type="text" value="<?= isset($usuario->emissor_rg) ? $usuario->emissor_rg : '' ?>" placeholder="SSP-PB">
                    </div>
                    <div class="col-sm-2">
                        <label>Data de Emissão:</label>
                        <input id="data_emissao" type="date" name="data_emissao" class="form-control" value="<?= isset($usuario->data_emissao) ? $usuario->data_emissao : date('Y-m-d') ?>">
                    </div>
                    <div class="col-sm-2">
                        <label for="def">Deficiência:</label>
                        <select class="form-control" name="deficiencia" id="def">
                            <option <?= $usuario->deficiencia == 'Nenhuma' ? 'selected' : '' ?>>Nenhuma</option>
                            <option <?= $usuario->deficiencia == 'Visual' ? 'selected' : '' ?>>Visual</option>
                            <option <?= $usuario->deficiencia == 'Fisica' ? 'selected' : '' ?>>Fisica</option>
                            <option <?= $usuario->deficiencia == 'Auditiva' ? 'selected' : '' ?>>Auditiva</option>
                        </select>
                    </div>
            	</div>
            </div>
			<br>
            <label><h4><b>Endereço</b></h4></label>
            <div class="form-group">
                <div class="col-sm-5">
                    <label>Logradouro:</label>
                    <input id="endereco" class="form-control" type="text" name="endereco" value="<?= isset($usuario->endereco) ? $usuario->endereco : ''?>" placeholder="Rua Exemplo, 22">
                </div>

                <div class="col-sm-2">
                    <label>Bairro:</label>
                    <input id="bairro" class="form-control" type="text" name="bairro" value="<?= isset($usuario->bairro) ? $usuario->bairro : ''?>" placeholder="Bela Vista">
                </div>

                <div class="col-sm-2">
                    <label>Cidade:</label>
                    <input id="cidade" class="form-control" type="text" name="cidade" value="<?= isset($usuario->cidade) ? $usuario->cidade : '' ?>" placeholder="Guarabira">
                </div>

                <div class="col-sm-1">
                    <label>UF:</label>
                    <input id="uf" class="form-control" type="text" name="uf" maxlength="2" value="<?= isset($usuario->UF) ? $usuario->UF : '' ?>" placeholder="PB">
                </div>

                <div class="col-sm-2">
                    <label>CEP:</label>
                    <input type="text" id="cep" class="form-control" name="cep" value="<?= isset($usuario->cep) ? $usuario->cep : '' ?>" placeholder="58.200-000">
                </div>
            </div>
            <br/>
            <label><h4><b>Contato</b></h4></label>
            <div class="form-group">
            	<div class="col-sm-12">
                    <div class="col-sm-4">
                        <label>Nome do Contato:</label>
                        <input type="text" id="contato" name="contato" class="form-control" value="<?= isset($usuario->contato) ? $usuario->contato : '' ?>" placeholder="Nome do Contato">
                    </div>
                    <div class="col-sm-2">
                        <label>Telefone:</label>
                        <input class="form-control" type="text" id="telefone" name="telefone" value="<?= isset($usuario->telefone) ? $usuario->telefone : ''?>" placeholder="Telefone do Contato">
                    </div>

                    <div class="col-sm-4">
                        <label>Skype:</label>
                        <input class="form-control" type="text" id="skype" name="skype" placeholder="Usuário" value="<?= $usuario->conta_skype ?>">
                    </div>
            	</div>
            </div>
            <br>
            <label><h4><b>Bloqueio ao sistema</b></h4></label>
            <div class="form-group">
            	<div class="col-sm-2">
                    <label class="checkbox-inline"><input type="checkbox" name="status_bloqueio[]" id="status_bloqueio" class="ferias" <?= $usuario->status_bloqueio == '1' ? 'checked' : ''; ?> value="1">Férias</label>
                    <label class="checkbox-inline"><input type="checkbox" name="status_bloqueio[]" id="status_bloqueio" class="demissao" <?= $usuario->status_bloqueio == '2' ? 'checked' : '' ?> value="2">Demissão</label>
                </div>
                <div class="dataferias">
                	<div class="col-sm-3">
                		<label>Data de Saída</label>
                        <input id="data_saida" type="date" name="data_saida" class="form-contro" value="<?= isset($usuario->data_saida_ferias) ? $usuario->data_saida_ferias : '' ?>">
                    </div>
                    <div class="col-sm-3">
                		<label>Data de Retorno</label>
                        <input id="data_retorno" type="date" name="data_retorno" class="form-contro" value="<?= isset($usuario->data_retorno_ferias) ? $usuario->data_retorno_ferias : '' ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="acess" class="tab-pane fade well">
        <label><h4><b>Permissões do Usuário</b></h4></label>
        <div class="dados-pessoais control-group">
            <div class="form-group">
                <div class="col-sm-6">
                    <div class="control-group">
                        <label>Email:</label>
                        <input type="email" id="login" name="login" class="form-control" value="<?php echo $usuario->login?>" required />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="control-group">
                        <label>Senha:</label>
                        <input type="password" id="senha" name="senha" class="form-control" placeholder="Deixe em branco para não alterar"/>
                        <div class="progress">
							<div class="bar" id="progress-bar" style="width: 0%;"></div>
						</div>
                    </div>
                </div>
            </div>
            <div class="form-group">
            	<div class="col-sm-12">
                    <div class="col-sm-2">
                        <div class="control-group">
                            <label>Status:</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="1" <?php echo set_selecionado(1, $usuario->status, 'selected')?>>Ativo</option>
                                <option value="0" <?php echo set_selecionado(0, $usuario->status, 'selected')?>>Inativo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="control-group">
                            <label>Função:</label>
                            <select name="funcao" id="funcao" class="form-control" required>
                                <option value="0" <?= $usuario->funcao == '0' ? 'selected' : '' ?>>Padrão</option>
                                <option value="dev" <?= $usuario->funcao == 'dev' ? 'selected' : '' ?>>Desenvolvedor</option>
                                <option value="ven" <?= $usuario->funcao == 'ven' ? 'selected' : '' ?>>Vendedor</option>
                                <option value="OMNILINK" <?= $usuario->funcao == 'OMNILINK' ? 'selected' : '' ?>>OMNILINK</option>
                            </select>
                        </div>
                    </div>
               </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <div class="control-group">
                        <label class="control-label"><b>Cadastros</b> </label>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="documentacoes"
                                    <?php echo set_selecionado('documentacoes', unserialize($usuario->permissoes), 'checked')?> />
                                Documentações
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="licitacoes"
                                    <?php echo set_selecionado('licitacoes', unserialize($usuario->permissoes), 'checked')?> />
                                Licitações
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="cad_veiculos"
                                    <?php echo set_selecionado('cad_veiculos', unserialize($usuario->permissoes), 'checked')?> />
                                Veículos
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="cad_permissoes"
                                    <?php echo set_selecionado('cad_permissoes', unserialize($usuario->permissoes), 'checked')?> />
                                Permissões (GESTOR)
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="cad_planos"
                                    <?php echo set_selecionado('cad_planos', unserialize($usuario->permissoes), 'checked')?> />
                                Planos (GESTOR)
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="cad_permissoes_funcionarios"
                                    <?php echo set_selecionado('cad_permissoes_funcionarios', unserialize($usuario->permissoes), 'checked')?> />
                                <?=lang('permissoes_usuarios_show')?>
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="administrar_veiculos"
                                    <?php echo set_selecionado('administrar_veiculos', unserialize($usuario->permissoes), 'checked')?> />
                                Administrar Veículos
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="cad_equipamento"
                                    <?php echo set_selecionado('cad_equipamento', unserialize($usuario->permissoes), 'checked')?> />
                                Equipamentos
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="cad_contratos_eptc"
                                    <?php echo set_selecionado('cad_contratos_eptc', unserialize($usuario->permissoes), 'checked')?> />
                                Contratos EPTC
                            </label>
                            <br>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="cad_agend_servico"
                                    <?php echo set_selecionado('cad_agend_servico', unserialize($usuario->permissoes), 'checked')?> />
                                Agendamento de Serviços
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="cad_instaladores"
                                    <?php echo set_selecionado('cad_instaladores', unserialize($usuario->permissoes), 'checked')?> />
                                Instaladores
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="cadastro_fornecedor"
                                    <?php echo set_selecionado('cadastro_fornecedor', unserialize($usuario->permissoes), 'checked')?> />
                                Fornecedores
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="cad_representantes"
                                    <?php echo set_selecionado('cad_representantes', unserialize($usuario->permissoes), 'checked')?> />
                                Representantes
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="cad_comandos"
                                    <?php echo set_selecionado('cad_comandos', unserialize($usuario->permissoes), 'checked')?> />
                                Comandos
                            </label>
                            <br><br>

                            <label class="control-label">Clientes</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="clientes_visualiza"
                                    <?php echo set_selecionado('clientes_visualiza', unserialize($usuario->permissoes), 'checked')?> />
                                Visualizar
                            </label> <label class="checkbox-inline"> <input type="checkbox"
                                                                            name="permissoes[]" value="clientes_add"
                                    <?php echo set_selecionado('clientes_add', unserialize($usuario->permissoes), 'checked')?> />
                                Adicionar
                            </label> <label class="checkbox-inline"> <input type="checkbox"
                                                                            name="permissoes[]" value="clientes_update"
                                    <?php echo set_selecionado('clientes_update', unserialize($usuario->permissoes), 'checked')?> />
                                Atualizar

                            </label> <label class="checkbox-inline"> <input type="checkbox"
                                                                                     name="permissoes[]" value="inativa_clie"
                                    <?php echo set_selecionado('inativa_clie', unserialize($usuario->permissoes), 'checked')?> />
                                Inativar
                            </label> <label class="checkbox-inline"> <input type="checkbox"
                                                                                    name="permissoes[]" value="clientes_bloqueio"
                                    <?php echo set_selecionado('clientes_bloqueio', unserialize($usuario->permissoes), 'checked')?> />
                                Bloqueio usuário
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="clientes_arquivo"
                                    <?php echo set_selecionado('clientes_arquivo', unserialize($usuario->permissoes), 'checked')?> />
                                Download Arquivos
                            </label>
                        </div>
                        <br>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="control-label">Funcionários</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="usuarios_visualiza"
                                    <?php echo set_selecionado('usuarios_visualiza', unserialize($usuario->permissoes), 'checked')?> />
                                Visualizar
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox"
                                                                   name="permissoes[]" value="usuarios_add"
                                    <?php echo set_selecionado('usuarios_add', unserialize($usuario->permissoes), 'checked')?> />
                                Adicionar
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="usuarios_update"
                                    <?php echo set_selecionado('usuarios_update', unserialize($usuario->permissoes), 'checked')?> />
                                Atualizar
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="status_funcionario"
                                    <?php echo set_selecionado('status_funcionario', unserialize($usuario->permissoes), 'checked')?> />
                                <?=lang('status_funcionario')?>
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="aplicar_ferias"
                                    <?php echo set_selecionado('aplicar_ferias', unserialize($usuario->permissoes), 'checked')?> />
                                <?=lang('aplicar_ferias')?>
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="demitir"
                                    <?php echo set_selecionado('demitir', unserialize($usuario->permissoes), 'checked')?> />
                                <?=lang('demitir')?>
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="m2m"
                                    <?php echo set_selecionado('m2m', unserialize($usuario->permissoes), 'checked')?> />
                                M2M
                            </label>
                        </div>
                        <br>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="control-label">CRM DE VENDAS</label>
                            <label style="margin-left: 10px;" class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="crm_vendas" <?php echo set_selecionado('crm_vendas', unserialize($usuario->permissoes), 'checked')?> />
                                 Vendedor
                            </label>
                            <label style="margin-left: 10px;" class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="crm_vendas_admin" <?php echo set_selecionado('crm_vendas_admin', unserialize($usuario->permissoes), 'checked')?> />
                                Administrador
                            </label>
                        </div>
                        <br>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="checkbox">
                                <input type="checkbox" name="permissoes[]" value="cad_linhas"
                                    <?php echo set_selecionado('cad_linhas', unserialize($usuario->permissoes), 'checked')?> />
                                Linhas
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="cad_mikrotik"
                                    <?php echo set_selecionado('cad_mikrotik', unserialize($usuario->permissoes), 'checked')?> />
                                MikroTik
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_chips"
                                    <?php echo set_selecionado('cad_chips', unserialize($usuario->permissoes), 'checked')?> />
                                Chips
                            </label>
                        </div>
                        <br>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="checkbox"> <input type="checkbox" name="permissoes[]" value="cad_rh" <?php echo set_selecionado('cad_rh', unserialize($usuario->permissoes), 'checked')?> />
                                RH
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_aniversariantes" <?php echo set_selecionado('cad_aniversariantes', unserialize($usuario->permissoes), 'checked')?>/>
                                Aniversariantes
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_apresentacoes" <?php echo set_selecionado('cad_apresentacoes', unserialize($usuario->permissoes), 'checked')?>/>
                                Apresentações
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_banner" <?php echo set_selecionado('cad_banner', unserialize($usuario->permissoes), 'checked')?>/>
                                Banner
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_comunicado" <?php echo set_selecionado('cad_comunicado', unserialize($usuario->permissoes), 'checked')?>/>
                                Comunicados
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_contatos_corporativos" <?php echo set_selecionado('cad_contatos_corporativos', unserialize($usuario->permissoes), 'checked')?>/>
                                Contatos Corporativos
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_folhetos" <?php echo set_selecionado('cad_folhetos', unserialize($usuario->permissoes), 'checked')?>/>
                                Folhetos
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_sobre_empresa" <?php echo set_selecionado('cad_sobre_empresa', unserialize($usuario->permissoes), 'checked')?>/>
                                Sobre a empresa
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_politicas_formularios" <?php echo set_selecionado('cad_politicas_formularios', unserialize($usuario->permissoes), 'checked')?>/>
                                Políticas, Formulários e Informações Gerais
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_produtos" <?php echo set_selecionado('cad_produtos', unserialize($usuario->permissoes), 'checked')?>/>
                                Produtos
                            </label>
                            <br><br>
                            <label class="checkbox"> Engenharia e Técnologia</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_suporte_tecnico" <?php echo set_selecionado('cad_suporte_tecnico', unserialize($usuario->permissoes), 'checked')?>/>
                               	Suporte Técnico
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_teste_homologacao" <?php echo set_selecionado('cad_teste_homologacao', unserialize($usuario->permissoes), 'checked')?>/>
                                Teste e Homologação
                            </label>
                            <br><br>
                            <label class="checkbox"> Espaço TI</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_espaco_ti" <?php echo set_selecionado('cad_espaco_ti', unserialize($usuario->permissoes), 'checked')?>/>
                               	Espaço TI
                            </label>
                            <br><br>
                            <label class="checkbox"> Marketing</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_marketing_briefing" <?php echo set_selecionado('cad_marketing_briefing', unserialize($usuario->permissoes), 'checked')?>/>
                               	Briefing
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_marketing_campanhas" <?php echo set_selecionado('cad_marketing_campanhas', unserialize($usuario->permissoes), 'checked')?>/>
                                Campanhas
                            </label>
                            <br><br>
                            <label class="checkbox"> Televendas</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_apresentacoes_comerciais" <?php echo set_selecionado('cad_apresentacoes_comerciais', unserialize($usuario->permissoes), 'checked')?>/>
                               	Apresentações Comerciais
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_comite_guerra" <?php echo set_selecionado('cad_comite_guerra', unserialize($usuario->permissoes), 'checked')?>/>
                                Comitê de Guerra
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_televendas_comunicados" <?php echo set_selecionado('cad_televendas_comunicados', unserialize($usuario->permissoes), 'checked')?>/>
                                Comunicados
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_propostas_comerciais" <?php echo set_selecionado('cad_propostas_comerciais', unserialize($usuario->permissoes), 'checked')?>/>
                                Propostas Comerciais
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_politicas_procedimentos" <?php echo set_selecionado('cad_politicas_procedimentos', unserialize($usuario->permissoes), 'checked')?>/>
                                Políticas e Procedimentos
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_guia_produtos" <?php echo set_selecionado('cad_guia_produtos', unserialize($usuario->permissoes), 'checked')?>/>
                                Guia de Produtos
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_folhetos" <?php echo set_selecionado('cad_folhetos', unserialize($usuario->permissoes), 'checked')?>/>
                                Folhetos
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_precos_acessorios" <?php echo set_selecionado('cad_precos_acessorios', unserialize($usuario->permissoes), 'checked')?>/>
                                Tabela de Preços e Acessórios
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_inteligencia_mercado" <?php echo set_selecionado('cad_inteligencia_mercado', unserialize($usuario->permissoes), 'checked')?>/>
                                Inteligência de Mercado
                            </label>
                            <br><br>
                            <label class="checkbox"> Governança Corporativa</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_governanca_corporativa" <?php echo set_selecionado('cad_governanca_corporativa', unserialize($usuario->permissoes), 'checked')?>/>
                               	Governança Corporativa
                            </label>
                            <br><br>
                            <label class="checkbox"> Espaço Gente & Gestao</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_gente_gestao" <?php echo set_selecionado('cad_gente_gestao', unserialize($usuario->permissoes), 'checked')?>/>
                              Gente e gestão
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_treinamentos" <?php echo set_selecionado('cad_treinamentos', unserialize($usuario->permissoes), 'checked')?>/>
                               	Desenvol. Organizacional -> Treinamentos EAD
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_atividades" <?php echo set_selecionado('cad_atividades', unserialize($usuario->permissoes), 'checked')?>/>
                               	Desenv. Organizacional -> Cad Treinamentos
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_parcerias" <?php echo set_selecionado('cad_parcerias', unserialize($usuario->permissoes), 'checked')?>/>
                               	Desenv. Organizacional -> Parcerias
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_plano_de_voo" <?php echo set_selecionado('cad_plano_de_voo', unserialize($usuario->permissoes), 'checked')?>/>
                               	Desenv. Organizacional -> Plano de Voo
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_correcao_irrf" <?php echo set_selecionado('cad_correcao_irrf', unserialize($usuario->permissoes), 'checked')?>/>
                               	Adm de Pessoal -> Correção de IRRF
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_docs_pendentes" <?php echo set_selecionado('cad_docs_pendentes', unserialize($usuario->permissoes), 'checked')?>/>
                               	Adm de Pessoal -> Documentos Pendentes
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_desconto_coparticipacao" <?php echo set_selecionado('cad_desconto_coparticipacao', unserialize($usuario->permissoes), 'checked')?>/>
                               	Adm de Pessoal -> Desconto de Coparticipação
                            </label>
                        </div>
                        <br>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label"><b>Relatórios</b></label>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_tipo_servico"
                                    <?php echo set_selecionado('rel_tipo_servico', unserialize($usuario->permissoes), 'checked')?> />
                                Tipo de Serviços
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_veic_tempo_contrato"
                                    <?php echo set_selecionado('rel_veic_tempo_contrato', unserialize($usuario->permissoes), 'checked')?> />
                                Veículo por Tempo de Contrato
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_clients_publicos"
                                    <?php echo set_selecionado('rel_clients_publicos', unserialize($usuario->permissoes), 'checked')?> />
                                Clientes Públicos
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_contas"
                                    <?php echo set_selecionado('rel_contas', unserialize($usuario->permissoes), 'checked')?> />
                                Contas a Pagar
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_contratos"
                                    <?php echo set_selecionado('rel_contratos', unserialize($usuario->permissoes), 'checked')?> />
                                Contratos
                            </label>
                             <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_placas_ativas_inativas"
                                    <?php echo set_selecionado('rel_placas_ativas_inativas', unserialize($usuario->permissoes), 'checked')?> />
                                Placas Ativas - Inativas
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_tempo_logado"
                                    <?php echo set_selecionado('rel_tempo_logado', unserialize($usuario->permissoes), 'checked')?> />
                                Tempo logado
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_envio_sms"
                                    <?php echo set_selecionado('rel_envio_sms', unserialize($usuario->permissoes), 'checked')?> />
                                Envio SMS
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_eqp_desat"
                                    <?php echo set_selecionado('rel_eqp_desat', unserialize($usuario->permissoes), 'checked')?> />
                                Equipamentos Desatualizados
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="analise_contaOp"
                                    <?php echo set_selecionado('analise_contaOp', unserialize($usuario->permissoes), 'checked')?> />
                                Analise de Fatura Operadora
                            </label>
                            <br><br>

                            <label class="control-label">Financeiro</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="rel_financeiro_faturas"
                                    <?php echo set_selecionado('rel_financeiro_faturas', unserialize($usuario->permissoes), 'checked')?> />
                                Faturas
                            </label>

                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="comissao"
                                    <?php echo set_selecionado('comissao', unserialize($usuario->permissoes), 'checked')?> />
                                Comissionamento de Vendedores
                            </label>

                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="comissao_showroutes"
                                    <?php echo set_selecionado('comissao_showroutes', unserialize($usuario->permissoes), 'checked')?> />
                                Comissionamento - ShowRoutes
                            </label>

                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="rel_adesao"
                                    <?php echo set_selecionado('rel_adesao', unserialize($usuario->permissoes), 'checked')?> />
                                <?=lang('rel_adesao')?>
                            </label>

                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_financeiro_fatenviadas"
                                    <?php echo set_selecionado('rel_financeiro_fatenviadas', unserialize($usuario->permissoes), 'checked')?> />
                                Faturas Enviadas
                            </label> <br/>

                            <label class="control-label">Clientes</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="rel_clientes_uf"
                                    <?php echo set_selecionado('rel_clientes_uf', unserialize($usuario->permissoes), 'checked')?> />
                                Cliente por UF
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_resumo_veic_disponiveis"
                                    <?php echo set_selecionado('rel_resumo_veic_disponiveis', unserialize($usuario->permissoes), 'checked')?> />
                                Resumo Veiculos Disponiveis
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_veic_disponiveis"
                                    <?php echo set_selecionado('rel_veic_disponiveis', unserialize($usuario->permissoes), 'checked')?> />
                                Veiculos Disponiveis
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_monitorados_dia_atividade"
                                    <?php echo set_selecionado('rel_monitorados_dia_atividade', unserialize($usuario->permissoes), 'checked')?> />
                                <?=lang('monitorados_dia_atualizacao')?>
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_dash_veic"
                                    <?php echo set_selecionado('rel_dash_veic', unserialize($usuario->permissoes), 'checked')?> />
                                Dashboard Veiculos
                            </label>
                        </div>
                        <br><br>
                    </div>
                </div>
                <div class="span6 ">
                    <div class="control-group">
                        <label class="control-label"><b>Administração</b> </label>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="control-label"><b>Contratos</b> </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="contratos_cancelar"
                                    <?php echo set_selecionado('contratos_cancelar', unserialize($usuario->permissoes), 'checked')?> />
                                Cancelar
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="edit_contrato"
                                    <?php echo set_selecionado('edit_contrato', unserialize($usuario->permissoes), 'checked')?> />
                                Editar Vendedor
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="downloads_contratos"
                                    <?php echo set_selecionado('downloads_contratos', unserialize($usuario->permissoes), 'checked')?> />
                                Downloads
                            </label> <br/><br/>

                            <label class="control-label"><b>Admin Permissões</b> </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="admin_permissoes"
                                    <?php echo set_selecionado('admin_permissoes', unserialize($usuario->permissoes), 'checked')?> />
                                Administrador
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="observador_permissoes"
                                    <?php echo set_selecionado('observador_permissoes', unserialize($usuario->permissoes), 'checked')?> />
                                Observador
                            </label>
                        </div>
                    </div>
                </div>
                <div class="span6 ">
                    <!-- ISCAS -->
                    <div class="control-group">
                        <label class="control-label"><b>Administrativo Iscas</b> </label>
                        <div class="controls" style="margin-left: 15px;">

                            <label class="checkbox-inline" style="margin-left: 25px;"> <input type="checkbox" name="permissoes[]" value="dashboard_iscas"
                                <?php echo set_selecionado('dashboard_iscas', unserialize($usuario->permissoes), 'checked')?> />
                            Dashboard
                            </label>
                            <label class="checkbox-inline" style="margin-left: 25px;"> <input type="checkbox" name="permissoes[]" value="equipamentos_iscas"
                                <?php echo set_selecionado('equipamentos_iscas', unserialize($usuario->permissoes), 'checked')?> />
                                Equipamentos
                            </label>
                            <label class="checkbox-inline" style="margin-left: 25px;"> <input type="checkbox" name="permissoes[]" value="relatorios_iscas"
                                <?php echo set_selecionado('relatorios_iscas', unserialize($usuario->permissoes), 'checked')?> />
                                Relatórios
                            </label>
                            <label class="checkbox-inline" style="margin-left: 25px;"> <input type="checkbox" name="permissoes[]" value="comandos_iscas"
                                <?php echo set_selecionado('comandos_iscas', unserialize($usuario->permissoes), 'checked')?> />
                                Comandos
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6 ">
                    <div class="control-group">
                        <label class="control-label"><b>Financeiro</b> </label>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="financeiro_acesso"
                                    <?php echo set_selecionado('financeiro_acesso', unserialize($usuario->permissoes), 'checked')?> />
                                Só Financeiro tem acesso
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="inadimplencias_faturas"
                                    <?php echo set_selecionado('inadimplencias_faturas', unserialize($usuario->permissoes), 'checked')?> />
                                Inadimplências
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="compras"
                                    <?php echo set_selecionado('compras', unserialize($usuario->permissoes), 'checked')?> />
                                Compras
                            </label>
                            <br><br>

                            <label>
                                <input type="checkbox" name="permissoes[]" value="faturas"
                                    <?php echo set_selecionado('faturas', unserialize($usuario->permissoes), 'checked')?> />
                                Faturas
                            </label>

                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="faturas_visualiza"
                                    <?php echo set_selecionado('faturas_visualiza', unserialize($usuario->permissoes), 'checked')?> />
                                Visualizar
                            </label> <label class="checkbox-inline"> <input type="checkbox"
                                                                            name="permissoes[]" value="faturas_add"
                                    <?php echo set_selecionado('faturas_add', unserialize($usuario->permissoes), 'checked')?> />
                                Adicionar
                            </label> <label class="checkbox-inline"> <input type="checkbox"
                                                                            name="permissoes[]" value="faturas_update"
                                    <?php echo set_selecionado('faturas_update', unserialize($usuario->permissoes), 'checked')?> />
                                Atualizar
                            </label> <label class="checkbox-inline"> <input type="checkbox"
                                                                            name="permissoes[]" value="faturaACancelar"
                                    <?php echo set_selecionado('faturaACancelar', unserialize($usuario->permissoes), 'checked')?> />
                                A Cancelar
                            </label> <label class="checkbox-inline"> <input type="checkbox"
                                                                            name="permissoes[]" value="faturas_delete"
                                    <?php echo set_selecionado('faturas_delete', unserialize($usuario->permissoes), 'checked')?> />
                                Cancelar
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox"
                                                                   name="permissoes[]" value="faturas_retorno"
                                    <?php echo set_selecionado('faturas_retorno', unserialize($usuario->permissoes), 'checked')?> />
                                Retorno
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox"
                                                                   name="permissoes[]" value="config_boleto"
                                    <?php echo set_selecionado('config_boleto', unserialize($usuario->permissoes), 'checked')?> />
                                Conf. boleto
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox"
                                                                   name="permissoes[]" value="edit_tipo_faturamento"
                                    <?php echo set_selecionado('edit_tipo_faturamento', unserialize($usuario->permissoes), 'checked')?> />
                                <?=lang('edit_tipo_faturamento')?>
                            </label>
                            <br><br>

                            <label class="checkbox">
                                <input type="checkbox" name="permissoes[]" value="chave_desconto"
                                    <?php echo set_selecionado('chave_desconto', unserialize($usuario->permissoes), 'checked')?> />
                                Chave de desconto
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="criar_chave_desconto"
                                    <?php echo set_selecionado('criar_chave_desconto', unserialize($usuario->permissoes), 'checked')?> />
                                Criar
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="listar_chave_desconto"
                                    <?php echo set_selecionado('listar_chave_desconto', unserialize($usuario->permissoes), 'checked')?> />
                                Listar
                            </label>
                            <br><br>

                            <label class="checkbox">
                                <input type="checkbox" name="permissoes[]" value="contas_a_pagar"
                                    <?php echo set_selecionado('contas_a_pagar', unserialize($usuario->permissoes), 'checked')?> />
                                Contas
                            </label>

                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="aprovador"
                                    <?php echo set_selecionado('aprovador', unserialize($usuario->permissoes), 'checked')?> />
                                Aprovador
                            </label>

                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="lancamentos"
                                    <?php echo set_selecionado('lancamentos', unserialize($usuario->permissoes), 'checked')?> />
                                Ordem de Pagamentos (OP)
                            </label>

                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="contas_showtecnologia"
                                    <?php echo set_selecionado('contas_showtecnologia', unserialize($usuario->permissoes), 'checked')?> />
                                ShowTecnologia
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="contas_eua"
                                    <?php echo set_selecionado('contas_eua', unserialize($usuario->permissoes), 'checked')?> />
                                ShowTechnology
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="contas_pneushow"
                                    <?php echo set_selecionado('contas_pneushow', unserialize($usuario->permissoes), 'checked')?> />
                                Pneu Show
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="valores"
                                    <?php echo set_selecionado('valores', unserialize($usuario->permissoes), 'checked')?> />
                                Valores
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="cancelar_conta"
                                    <?php echo set_selecionado('cancelar_conta', unserialize($usuario->permissoes), 'checked')?> />
                                Cancelar
                            </label>
                            <br><br>
                            <label class="control-label">Baixa por Extrato</label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="baixa_extrato_show"
                                    <?php echo set_selecionado('baixa_extrato_show', unserialize($usuario->permissoes), 'checked')?> />
                                Show Tecnologia
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="baixa_extrato_norio"
                                    <?php echo set_selecionado('baixa_extrato_norio', unserialize($usuario->permissoes), 'checked')?> />
                                Norio Momoi EPP
                            </label>
                        </div>
                    </div>
                </div>
                <div class="span6 ">
                    <div class="control-group">
                        <label class="control-label"><b>Suporte</b> </label>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="visualizar_tickets"
                                    <?php echo set_selecionado('visualizar_tickets', unserialize($usuario->permissoes), 'checked')?> />
                                Tickets
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="downloads_os"
                                    <?php echo set_selecionado('downloads_os', unserialize($usuario->permissoes), 'checked')?> />
                                OS
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="veiculos_desatualizados"
                                    <?php echo set_selecionado('veiculos_desatualizados', unserialize($usuario->permissoes), 'checked')?> />
                                Desatualizados
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="logs"
                                    <?php echo set_selecionado('logs', unserialize($usuario->permissoes), 'checked')?> />
                                Logs
                            </label>
                        </div>
                    </div>
                </div>
                <div class="span6 ">
                    <div class="control-group">
                        <label class="control-label"><b>Desenvolvimento</b> </label>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="api"
                                    <?php echo set_selecionado('api', unserialize($usuario->permissoes), 'checked')?> />
                                API
                            </label>
                        </div>
                    </div>
                </div>
                <div class="span6 ">
                    <div class="control-group">
                        <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                               value="monitoramento"
                                <?php echo set_selecionado('monitoramento', unserialize($usuario->permissoes), 'checked')?> />
                            <b>Monitoramento</b>
                        </label>
                        <div class="controls" style="margin-left: 15px;">
                            <label style="margin-left: 20px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="equipamentos_violados"
                                    <?php echo set_selecionado('equipamentos_violados', unserialize($usuario->permissoes), 'checked')?> />
                                Equipamentos violados
                            </label>

                            <label style="margin-left: 20px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="monitor_panico"
                                    <?php echo set_selecionado('monitor_panico', unserialize($usuario->permissoes), 'checked')?> />
                                Pânico
                            </label>

                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="gateways"
                                    <?php echo set_selecionado('gateways', unserialize($usuario->permissoes), 'checked')?> />
                                Gateways
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="monitor_contrato"
                                    <?php echo set_selecionado('monitor_contrato', unserialize($usuario->permissoes), 'checked')?> />
                                Monitoramento Contratos
                            </label>
                        </div>
                    </div>
                </div>
                <div class="span6 ">
                    <div class="control-group">
                        <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                               value="configuracoes"
                                <?php echo set_selecionado('configuracoes', unserialize($usuario->permissoes), 'checked')?> />
                            <b>Configuração</b>
                        </label>
                        <div class="controls" style="margin-left: 15px;">
                            <label style="margin-left: 20px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="mensagem_notificacao"
                                    <?php echo set_selecionado('mensagem_notificacao', unserialize($usuario->permissoes), 'checked')?> />
                                SMS
                            </label>
                        </div>
                    </div>
                </div>
                <div class="span6 ">
                    <div class="control-group">
                        <label class="control-label"><b>Termo de Adesão</b> </label>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="add_termo"
                                    <?php echo set_selecionado('add_termo', unserialize($usuario->permissoes), 'checked')?> />
                                Adicionar
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="edit_termo"
                                    <?php echo set_selecionado('edit_termo', unserialize($usuario->permissoes), 'checked')?> />
                                Editar
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="print_termo"
                                    <?php echo set_selecionado('print_termo', unserialize($usuario->permissoes), 'checked')?> />
                                Imprimir
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="admin_termo"
                                    <?php echo set_selecionado('admin_termo', unserialize($usuario->permissoes), 'checked')?> />
                                Admin
                            </label>
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label"><b>Atalhos</b> </label>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="zoiper"
                                    <?php echo set_selecionado('zoiper', unserialize($usuario->permissoes), 'checked')?> />
                                Zoiper
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="edools"
                                    <?php echo set_selecionado('edools', unserialize($usuario->permissoes), 'checked')?> />
                                Edools
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="pipedrive"
                                    <?php echo set_selecionado('pipedrive', unserialize($usuario->permissoes), 'checked')?> />
                                PipeDrive
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="trello"
                                    <?php echo set_selecionado('trello', unserialize($usuario->permissoes), 'checked')?> />
                                Trello
                            </label>

                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="crm_omnilink"
                                    <?php echo set_selecionado('crm_omnilink', unserialize($usuario->permissoes), 'checked')?> />
                                CRM OMNILINK
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="omniweb"
                                    <?php echo set_selecionado('omniweb', unserialize($usuario->permissoes), 'checked')?> />
                                OMNIWEB
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="iridium"
                                    <?php echo set_selecionado('iridium', unserialize($usuario->permissoes), 'checked')?> />
                                IRIDIUM
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="turbo"
                                    <?php echo set_selecionado('turbo', unserialize($usuario->permissoes), 'checked')?> />
                                TURBO - SAVERNET
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="omniturbo"
                                    <?php echo set_selecionado('omniturbo', unserialize($usuario->permissoes), 'checked')?> />
                                OMNITURBO - SAVERNET
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="painel_ativ"
                                    <?php echo set_selecionado('painel_ativ', unserialize($usuario->permissoes), 'checked')?> />
                                PAINEL DE ATIVAÇÃO
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="andromeda"
                                    <?php echo set_selecionado('andromeda', unserialize($usuario->permissoes), 'checked')?> />
                                ANDRÔMEDA - APN
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="mhs"
                                    <?php echo set_selecionado('mhs', unserialize($usuario->permissoes), 'checked')?> />
                                MHS - MR
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="telemetria"
                                    <?php echo set_selecionado('telemetria', unserialize($usuario->permissoes), 'checked')?> />
                                TELEMETRIA - CAN
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="telemetria"
                                    <?php echo set_selecionado('telemetria', unserialize($usuario->permissoes), 'checked')?> />
                                EAD - TREINAMENTOS
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="protheus"
                                    <?php echo set_selecionado('protheus', unserialize($usuario->permissoes), 'checked')?> />
                                WEB PROTHEUS
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="srv"
                                    <?php echo set_selecionado('srv', unserialize($usuario->permissoes), 'checked')?> />
                                SVR - WEBGRABER
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="omnitelemetria2"
                                    <?php echo set_selecionado('omnitelemetria2', unserialize($usuario->permissoes), 'checked')?> />
                                OMNITELEMETRIA 2.0
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="prof" class="tab-pane fade well">
        <label><h4><b>Dados Profissionais</b></h4></label>
        <div class="dados-pessoais">
        	<div class="form-group">
            	<div class="col-sm-12">
                    <div class="col-sm-6">
                        <label for="emp">Empresa:</label>
                    	<select class="span12 form-control" name="empresa" id="emp">
                        	<option <?= $usuario->empresa == 'SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA-ME' ? 'selected' : '' ?>>SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA-ME</option>
                        	<option <?= $usuario->empresa == 'NORIO MOMOI EPP' ? 'selected' : '' ?>>NORIO MOMOI EPP</option>
                    	</select>
                    </div>
                    <div class="col-sm-2">
                       	<label>Ocupação:</label>
                        <input id="ocupacao" name="ocupacao" type="text" class="form-control" value="<?= isset($usuario->ocupacao) ? $usuario->ocupacao : '' ?>" placeholder="Ex.: Desenvovedor">
                    </div>
                    <div class="col-sm-2">
                       	<label>Data Admissão:</label>
                        <input class="form-control" id="data_admissao" name="admissao" type="date" value="<?= isset($usuario->data_admissao) ? $usuario->data_admissao : date('Y-m-d') ?>" />
                  	</div>
                   	<div class="col-sm-2">
                        <label>N. Contrato:</label>
                        <input id="num_contrato" name="num_contrato" type="number" class="form-control" value="<?= isset($usuario->num_contrato) ? $usuario->num_contrato : '' ?>" placeholder="Ex.: 0001"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
            	<div class="col-sm-12">
                    <div class="col-sm-2">
                        <label>PIS:</label>
                        <input id="pis" name="pis" type="text" class="form-control" value="<?= isset($usuario->pis) ? $usuario->pis : '' ?>" placeholder="Numero do PIS do Funcionário"/>
                    </div>

                    <div class="col-sm-2">
                        <label>Salário:</label>
                        <input id="salario" name="salario" type="text" class="form-control" value="<?= isset($usuario->salario) ? $usuario->salario : '' ?>" placeholder="Ex.: 1.050,00" />
                    </div>

                    <div class="col-sm-2">
                        <label>Cidade:</label>
                        <input id="city_job" name="city_job" type="text" class="form-control" value="<?= isset($usuario->city_job) ? $usuario->city_job : '' ?>" placeholder="Cidade Filial da Empresa">
                    </div>

                    <div class="col-sm-2">
                        <label>Cateira Profissional:</label>
                        <input id="ctps" name="ctps" class="form-control" type="text" value="<?= isset($usuario->ctps) ? $usuario->ctps : '' ?>" placeholder="Ex.: 735424-434">
                    </div>

                    <div class="col-sm-2">
                        <label>Ramal:</label>
                        <input class="form-control" type="text" id="ramal" name="ramal" placeholder="Ramal de Atendimento" value="<?= $usuario->ramal_telefone ?>">
                    </div>
            	</div>
            </div>
            <br/>
            <label><h4><b>Horário de Trabalho</b></h4></label>
            <div class="form-group">
                <div class="col-sm-2">
                    <label>* Carga Horária (Semanal):</label>
                    <input class="form-control hora" type="text" id="tempo_logado" name="tempo_logado" placeholder="00:00:00" value="<?php echo $usuario->tempo_logado?>" required />
                </div>
                <div class="col-sm-2">
                    <label>
                        <label>Início da Jornada:</label>
                        <input class="form-control hora" type="text" name="inicio_job" id="inicio_job" placeholder="00:00:00" value="<?= isset($usuario->inicio_job) ? $usuario->inicio_job : '' ?>">
                    </label>
                </div>
                <div class="col-sm-2">
                    <label>Fím da Jornada:</label>
                    <input class="form-control hora" type="text" name="fim_job" id="fim_job" placeholder="00:00:00" value="<?= isset($usuario->fim_job) ? $usuario->fim_job : '' ?>">
                </div>
                <div class="col-sm-2">
                    <label>Intervalo Almoço:</label>
                    <input class="form-control hora" type="text" name="intervalo_job" id="intervalo_job" placeholder="00:00:00" value="<?= isset($usuario->intervalo_job) ? $usuario->intervalo_job : '' ?>">
                </div>
            </div>
            <br/><br/><br/>
        </div>
    </div>
    <div id="bank" class="tab-pane fade well">
        <a class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Nova Conta</a>
        <br>
        <label><h4><b>Contas Cadastradas</b></h4></label>
        <div class="container-fluid" style="background-color: white; padding: 10px;">
            <table id="table-contas" class="table table-bordered">
                <thead>
                <tr>
                    <th>Titular</th>
                    <th>CPF</th>
                    <th>Banco</th>
                    <th>Agência</th>
                    <th>Conta</th>
                    <th>Operação</th>
                    <th>Tipo</th>
                    <th>Data Cadastro</th>
                    <th>Status</th>
                    <th>Admin</th>
                </tr>
                </thead>
                <div class="panel-group" id="accordion">
                    <tbody id="tbody-contas">
                    <?php if (isset($contas) && count($contas) > 0): ?>
                        <?php foreach ($contas as $conta): ?>
                            <tr>
                                <td><?= $conta->titular ?></td>
                                <td><?= $conta->cpf ?></td>
                                <td><?= $conta->banco ?></td>
                                <td><?= $conta->agencia ?></td>
                                <td><?= $conta->conta ?></td>
                                <td><?= $conta->operacao ?></td>
                                <td><?= $conta->tipo == 'corrente' ? 'CORRENTE' : 'POUPANÇA'; ?></td>
                                <td><?= $conta->data_cad ?></td>
                                <td id="status<?= $conta->id ?>" class="<?= $conta->status == 1 ? 'alt' : '' ?> status">
                                    <?php
                                    if ($conta->status == 0) echo "<span class='label label-info'>Secundária</span>";
                                    elseif ($conta->status == 1) echo "<span class='label label-success'>Principal</span>";
                                    else echo "<span class='label label-danger'>Cancelada</span>";
                                    ?>
                                </td>
                                <td>
                                    <?php if ($conta->status == 0): ?>
                                        <a onclick="tonarPadrao(<?= $conta->id ?>)" id="iconUp<?= $conta->id ?>" class="btn btn-xs btn-info" title="Conta Padrão"><i class="fa fa-check"></i></a>
                                    <?php elseif ($conta->status == 1): ?>
                                        <a onclick="tonarPadrao(<?= $conta->id ?>)" id="iconUp<?= $conta->id ?>" class="btn btn-xs btn-success" title="Conta Padrão"><i class="fa fa-check"></i></a>
                                    <?php else: ?>
                                        <a class="btn btn-xs btn-info" title="Conta Padrão"><i class="fa fa-check"></i></a>
                                    <?php endif; ?>
                                    <a class="accordion-toggle btn btn-primary" data-toggle="collapse" data-parent="#accordion" data-target="#<?= $conta->id ?>" title="Editar Conta"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                            <div id="<?= $conta->id ?>" class="panel-collapse collapse">
                                <div class="panel-primary">
                                    <div class="panel-heading" style="padding-top: 20px; text-align: center;">Editar - <?= 'Ag.: '.$conta->agencia.' - Conta: '.$conta->conta.' | '.$conta->tipo ?></div>
                                    <form action="<?= site_url('usuarios/update_conta').'/'.$conta->id.'/'.$usuario->id ?>" method="post">
                                        <div class="panel-body">
                                            <div class="line-dados control-group">
                                                <div class="box-input">
                                                    <label class="con-label">Titular da Conta</label>
                                                    <div class="controls">
                                                        <input type="text" name="titular" class="span4 form-control" value="<?= $conta->titular ?>">
                                                    </div>
                                                </div>
                                                <div class="box-input">
                                                    <label class="control-label">Banco</label>
                                                    <div class="controls">
                                                        <div class="input-prepend">
                                                            <span class="add-on"><i class="fa fa-bank"></i></span>
                                                            <select type="text" name="banco" placeholder="Banco" class="form-control span2">
                                                                <option <?= $conta->banco == '001' ? 'selected' : '' ?>>001 - Banco do Brasil</option>
                                                                <option <?= $conta->banco == '004' ? 'selected' : '' ?>>004 - Banco do Nordeste</option>
                                                                <option <?= $conta->banco == '237' ? 'selected' : '' ?>>237 - Bradesco</option>
                                                                <option <?= $conta->banco == '104' ? 'selected' : '' ?>>104 - Caixa Econômica Federal</option>
                                                                <option <?= $conta->banco == '341' ? 'selected' : '' ?>>341 - Itaú</option>
                                                                <option <?= $conta->banco == '008' ? 'selected' : '' ?>>008 - Santander</option>
                                                                <option <?= $conta->banco == '021' ? 'selected' : '' ?>>021 - Banestes S.A</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box-input">
                                                    <label class="con-label">Agência</label>
                                                    <div class="controls">
                                                        <input type="text" name="agencia" class="span2 form-control" value="<?= $conta->agencia ?>">
                                                    </div>
                                                </div>
                                                <div class="box-input">
                                                    <label class="con-label">Conta</label>
                                                    <div class="controls">
                                                        <input type="text" name="conta" class="span2 form-control" value="<?= $conta->conta ?>">
                                                    </div>
                                                </div>
                                                <div class="box-input">
                                                    <label class="control-label">Tipo da Conta</label>
                                                    <div class="controls">
                                                        <div class="input-prepend">
                                                            <span class="add-on"><i class="fa fa-bank"></i></span>
                                                            <select type="text" name="tipo" class="span2 form-control">
                                                                <option value="corrente" <?= $conta->tipo == 'corrente' ? 'selected' : '' ?>>Conta Corrente</option>
                                                                <option value="poupanca" <?= $conta->tipo == 'poupanca' ? 'selected' : '' ?>>Poupança</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><br/>
                                            <div class="line-dados control-group">
                                                <div class="box-input">
                                                    <label class="con-label">Operaçao</label>
                                                    <div class="controls">
                                                        <input type="text" name="operacao" class="span2 form-control" value="<?= $conta->operacao ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-success" type="submit">Editar</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="info-contas">
                            <td colspan="9">
                                Nenhuma conta cadastrada.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </div>
            </table>
        </div>
    </div>
</div>

<div>
    <div class="pull-left">
        <button id="salvarUpdate" class="btn btn-primary" style="padding: 10px 30px 10px 30px; font-size: x-large;border-radius: 15px 50px !important;"> Salvar</button>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cadastrar Conta</h4>
            </div>
            <form id="cad_conta" action="" method="post">
                <div class="modal-body">
                    <div class="line-dados control-group">
                        <div class="box-input">
                            <label class="con-label">Titular da Conta</label>
                            <div class="controls">
                                <input type="text" id="titular" name="titular" class="span4 form-control" placeholder="Nome completo do titular da conta">
                            </div>
                        </div>
                        <div class="box-input">
                            <label class="control-label">Banco</label>
                            <div class="controls">
                                <div class="input-prepend">
                                    <span class="add-on"><i class="fa fa-bank"></i></span>
                                    <select type="text" id="banco" name="banco" placeholder="Banco" class="form-control span2">
                                        <option value="001">001 - Banco do Brasil</option>
                                        <option value="004">004 - Banco do Nordeste</option>
                                        <option value="237">237 - Bradesco</option>
                                        <option value="104">104 - Caixa Econômica Federal</option>
                                        <option value="341">341 - Itaú</option>
                                        <option value="008">008 - Santander</option>
                                        <option value="021">021 - Banestes S.A</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="box-input">
                            <label class="con-label">Agência</label>
                            <div class="controls">
                                <input type="text" style="text-transform: uppercase;" minlength="2" maxlength="6" id="agenciadig" name="agencia" class="span2 form-control" placeholder="Ex.: 0042">
                            </div>
                        </div>
                        <div class="box-input">
                            <label class="con-label">Conta</label>
                            <div class="controls">
                                <input type="text" style="text-transform: uppercase;" id="contaDig" name="conta" class="span2 form-control" placeholder="Ex.: 138765-5">
                            </div>
                        </div>
                        <div class="box-input">
                            <label class="control-label">Tipo da Conta</label>
                            <div class="controls">
                                <div class="input-prepend">
                                    <span class="add-on"><i class="fa fa-bank"></i></span>
                                    <select type="text" id="tipo_conta" name="tipo" class="span2 form-control">
                                        <option value="corrente">Conta Corrente</option>
                                        <option value="poupanca">Poupança</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div><br/>
                    <div class="line-dados control-group">
                        <div class="box-input">
                            <label class="con-label">Operaçao</label>
                            <div class="controls">
                                <input type="text" id="oper" maxlength="4" name="operacao" class="span2 form-control" placeholder="Ex.: 013">
                            </div>
                        </div>
                        <div class="box-input">
                            <label class="con-label">CPF/CNPJ do Titular da Conta</label>
                            <div class="controls">
                                <input type="text" name="cpf" class="span3 form-control cpfCnpj" placeholder="000.000.000-00">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="buttonCadContas" type="submit" class="btn btn-success">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModal_digitalizar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-header" style="text-align: center;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Digitalizar Documentos</h3>
    </div>
    <div class="modal-body">
    </div>
</div>

<script>
    var senha, mostra;
	var forca = 0;

	$('#senha').on('keyup', function () {
		senha = document.getElementById("senha").value;
		forca = 0;
		mostra = document.getElementById("progress-bar");

		if((senha.length >= 4) && (senha.length <= 7)) {
			forca += 10;
		}else if(senha.length > 7) {
			//console.log('Maior que 7')
			forca += 15;
		}
		if(senha.match(/[a-z]+/)) {
			//console.log('Tem letras minusculas');
			forca += 15;
		}
		if(senha.match(/[A-Z]+/)) {
			//console.log('Tem letras maisculas');
			forca += 20;
		}
		if(senha.match(/[0-9]+/)){
			//console.log('Tem numeros');
			forca += 30;
		}
		if(senha.match(/[^A-Za-z0-9_]/)){
			//console.log('Tem Caracter');
			forca += 20;
		}

		return mostra_res();
	});

	function mostra_res(){
		//console.log('Força = '+forca);
		if(forca < 30){
			$(mostra).css('width', forca + '%').html('<span>Fraca</span>');
		}else if((forca >= 30) && (forca < 60)){
			$(mostra).css('width', forca + '%').html('<span>Média</span>');
		}else if((forca >= 60) && (forca < 85)){
			$(mostra).css('width', forca + '%').html('<span>Forte</span>');
		}else{
			$(mostra).css('width', '100%').html('<span>Excelente<n/span>');
		}
    }

    function tonarPadrao(id) {
        var url = "<?= site_url('usuarios/upgrade_contaById') ?>";
        $.ajax({
            type: "post",
            url: url,
            data: { id: id },
            success : function () {
                // TROCA CLASS BTN
                $('.btn-xs').removeClass('btn-success');
                $('.btn-xs').addClass('btn-info');
                // INSERE CLASSE SUCCESS NO BTN SELECIONADO
                $('#iconUp'+id).removeClass('btn-info');
                $('#iconUp'+id).addClass('btn-success');
                // TROCA STATUS DE STATUS 1 PARA 0
                $('.alt').html("<span class='label label-info'>Secundária</span>");
                // TROCA STATUS DE STATUS 0 PARA 1
                $('#status'+id).html("<span class='label label-success'>Principal</span>");
                // ADICIONA CLASS ALT
                $('#status'+id).addClass('alt');
                alert('Upgrade de conta realizada com sucesso');
            }

        })
    }

    jQuery(document).ready(function(){
        jQuery('#cad_conta').submit(function(){
            document.getElementById('buttonCadContas').setAttribute('disabled', 'disabled');
            var dados = jQuery( this ).serialize();
            var url = "<?= site_url('usuarios/cad_conta').'/'.$usuario->id ?>";
            jQuery.ajax({
                type: "POST",
                url: url,
                data: dados,
                dataType: 'json',
                success: function(data)
                {
                    if (data.status == 'OK') {
                        // VERIFICA SE LINHA DE INFORMAÇÃO EXISTE, SE SIM EXCLUI
                        if (document.getElementById('info-contas'))
                            document.getElementById('tbody-contas').innerHTML = '';

                        $('.status').each(function(){
                            $(this).html("<span class='label label-info'>Secundária</span>");
                        });

                        // ADICIONA CONTA A TABELA
                        var newRow = $("<tr class='status'>");
                        var cols = "";

                        cols += '<td>'+data.retorno.titular+'</td>';
                        cols += '<td>'+data.retorno.cpf+'</td>';
                        cols += '<td>'+data.retorno.banco+'</td>';
                        cols += '<td>'+data.retorno.agencia+'</td>';
                        cols += '<td>'+data.retorno.conta+'</td>';
                        cols += '<td>'+data.retorno.operacao+'</td>';
                        cols += '<td>'+data.retorno.tipo+'</td>';
                        cols += '<td>'+"<?= date('d/m/Y') ?>"+'</td>';
                        cols += '<td><span class="label label-success">Principal</span></td>';
                        cols += '<td></td>';

                        newRow.append(cols);
                        $("#table-contas").append(newRow);

                        // ATIVA BOTÃO DE SALVAR
                        document.getElementById('buttonCadContas').removeAttribute('disabled');

                        alert(data.mensagem);
                    } else {
                        // ATIVA BOTÃO DE SALVAR
                        document.getElementById('buttonCadContas').removeAttribute('disabled');

                        alert(data.mensagem);
                    }
                }
            });

            return false;
        });
    });

    $(document).ready(function () {

        var options = {
            onKeyPress: function (cpf, ev, el, op) {
                var masks = ['000.000.000-000', '00.000.000/0000-00'],
                    mask = (cpf.length > 14) ? masks[1] : masks[0];
                el.mask(mask, op);
            }
        };

        $('.cpfCnpj').mask('000.000.000-000', options);
        $("#contaDig").mask("#000-A", {reverse: true});
        $("#agenciadig").mask("#000-A", {reverse: true});
        $('#cpf').mask('000.000.000-00');
        $('#cep').mask('00.000-000');
        $('#telefone').mask('(00) 00000-0000');
        $('#salario').mask("#.##0,00", {reverse: true});
        $('.hora').mask('00:00:00');
    });

    var ferias = $(".ferias");
	var demissao = $(".demissao");
	var status_bloqueio;

    $('input:checkbox[name="status_bloqueio[]"]').change(function() {

    	status_bloqueio = $(this).val();

        if ($('.ferias').is(':checked')){
           	demissao.prop('disabled', true);
            $(".dataferias").show();

        }else{
        	demissao.prop('disabled', false);
        	$(".dataferias").hide();
        }


        if ($('.demissao').is(':checked')){
        	ferias.prop('disabled', true);
        }else{
        	ferias.prop('disabled', false);
        }

    });

    <?php if($usuario->status_bloqueio == '1'){ ?>
    	$(".dataferias").show();
    	demissao.prop('disabled', true);
    <?php }else{?>
    	$(".dataferias").hide();
    	demissao.prop('disabled', false);
    <?php }?>


    $('#salvarUpdate').on('click', function () {

        var permissoes = new Array();
        $("input[type=checkbox][name='permissoes[]']:checked").each(function(){
            permissoes.push($(this).val());
        });

        var data_saida_ferias = $('#data_saida').val();
        var data_retorno_ferias = $('#data_retorno').val();


        if(status_bloqueio == '1' && ($('.ferias').is(':checked'))){


        	if(data_saida_ferias == ""){
            	alert('favor seleiona a data de saída do funcionário');
        		$("#data_saida").focus();
        		return false;
        	}

        	if(data_retorno_ferias == ""){
            	alert('favor seleiona a data de retorno do funcionário');
        		$("#data_retorno").focus();
        		return false;
        	}

        }else if(status_bloqueio == '2'){
        	data_saida_ferias = "0000-00-00";
        	data_retorno_ferias = "0000-00-00";
        }else{
        	status_bloqueio = '0';
        	data_saida_ferias = "0000-00-00";
        	data_retorno_ferias = "0000-00-00";
        }

        var url = "<?= site_url('usuarios/edit_usuario') ?>";
        $.ajax({
            url : url,
            type : 'POST',
            dataType : 'json',
            data : {
                'id': "<?= $usuario->id ?>",
                'nome' : $('#nome').val(),
                'nacionalidade': $('#nacionalidade').val(),
                'naturalidade': $('#naturalidade').val(),
                'data_nasc': $('#data_nasc').val(),
                'civil': $('#civil option:selected').val(),
                'formacao': $('#formacao option:selected').val(),
                'sexo': $("input[name='sexo']:checked").val(),
                'cpf': $('#cpf').val(),
                'rg': $('#rg').val(),
                'data_emissor': $('#data_emissao').val(),
                'emissor_rg': $('#emissor_rg').val(),
                'deficiencia': $('#def option:selected').val(),
                'endereco': $('#endereco').val(),
                'bairro': $('#bairro').val(),
                'cidade': $('#cidade').val(),
                'UF': $('#uf').val(),
                'cep': $('#cep').val(),
                'contato': $('#contato').val(),
                'telefone': $('#telefone').val(),
                'login': $('#login').val(),
                'senha': $('#senha').val(),
                'tempo_logado': $('#tempo_logado').val(),
                'conta_skype': $('#skype').val(),
                'inicio_job': $('#inicio_job').val(),
                'fim_job': $('#fim_job').val(),
                'intervalo_job': $('#intervalo_job').val(),
                'ramal_telefone': $('#ramal').val(),
                'status': $('#status option:selected').val(),
                'funcao': $('#funcao option:selected').val(),
                'empresa': $('#emp option:selected').val(),
                'ocupacao': $('#ocupacao').val(),
                'data_admissao': $('#data_admissao').val(),
                'num_contrato': $('#num_contrato').val(),
                'pis': $('#pis').val(),
                'salario': $('#salario').val().replace('.', '').replace(',', '.'),
                'city_job': $('#city_job').val(),
                'ctps': $('#ctps').val(),
                'status_bloqueio': status_bloqueio,
                'data_saida_ferias': data_saida_ferias,
                'data_retorno_ferias': data_retorno_ferias,
                'permissoes': permissoes
            },
            success : function(data){
                alert(data.mensagem);
            },
            error : function () {
                alert('Sistema temporariamente indisponível, tente novamente mais tarde!');
            }
        });
    });
</script>
