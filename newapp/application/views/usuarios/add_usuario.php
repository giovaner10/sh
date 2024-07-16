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
<div class="row">
    <div class="btn-group col-md-12" >
        <a data-toggle="tab" href="#home" class="col-sm-3 btn btn-primary"><div style="text-align: center; font-size: 40px;"><i class="fa fa-address-card-o"></i></div><div style="text-align: center;">Dados Pessoais</div></a>
        <a data-toggle="tab" href="#prof" class="col-sm-3 btn btn-info "><div style="text-align: center; font-size: 40px;"><i class="fa fa-handshake-o"></i></div><div style="text-align: center;">Dados Profissionais</div></a>
        <a data-toggle="tab" href="#bank" class="col-sm-3 btn btn-warning"><div style="text-align: center; font-size: 40px;"><i class="fa fa-check-square-o"></i></div><div style="text-align: center;">Dados Bancários</div></a>
        <a data-toggle="tab" href="#acess" class="col-sm-3 btn btn-success"><div style="text-align: center; font-size: 40px;"><i class="fa fa-check-square-o"></i></div><div style="text-align: center;">Acessos</div></a>
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
                        <input type="text" id="nome" name="nome" class="form-control" value="" placeholder="Nome Completo do Funcionário" required />
                    </div>
                    <div class="col-sm-2">
                        <label>Nacionalidade:</label>
                        <input type="text" id="nacionalidade" name="nacionalidade" class="form-control" value="" placeholder="Brasileiro(a)" required />
                    </div>
                    <div class="col-sm-2">
                        <label>Naturalidade:</label>
                        <input type="text" id="naturalidade" name="naturalidade" class="form-control" value="" placeholder="Guarabira" required />
                    </div>
                </div>
            </div>
            <div class="form-group">
            	<div class="col-sm-12">
                    <div class="col-sm-2">
                        <label>Data de Nascimento:</label>
                        <input type="date" id="data_nasc" name="data_nasc" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="col-sm-2">
                        <label for="civil">Estado Civil:</label>
                        <select class="form-control span12" name="estado_civil" id="civil">
                            <option>Solteiro(a)</option>
                            <option>Casado(a)</option>
                            <option>Viuvo(a)</option>
                            <option>Divorciado(a)</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label for="grau">Grau de formação:</label>
                        <select class="form-control span12" id="formacao" name="formacao" id="raca">
                            <option>Ensino Fundamental Incompleto</option>
                            <option>Ensino Fundamental Completo</option>
                            <option>Ensino Médio Incompleto</option>
                            <option>Ensino Médio Completo</option>
                            <option>Ensino Superior Incompleto</option>
                            <option>Ensino Superior Completo</option>
                            <option>Pós-Graduação Incompleta</option>
                            <option>Pós-Graduação Completa</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label>Sexo:</label>
                        <div class="form-group">
                        	<label class="radio-inline"><input type="radio" name="sexo" value="M">Masculino</label>
                        	<label class="radio-inline"><input type="radio" name="sexo" value="F">Feminino</label>
                   		</div>
                    </div>
            	</div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="col-sm-2">
                        <label>* CPF:</label>
                        <input id="cpf" name="cpf" type="text" class="form-control" value="" placeholder="000.000.000-00" required>
                    </div>
                    <div class="col-sm-2">
                        <label>* RG:</label>
                        <input id="rg" name="rg" type="text" class="form-control" value="" placeholder="Numero do RG" required>
                    </div>
                    <div class="col-sm-2">
                        <label>* Orgão Emissor:</label>
                        <input id="emissor_rg" class="form-control" name="emissor_rg" type="text" value="" placeholder="SSP-PB" required>
                    </div>
                    <div class="col-sm-2">
                        <label>* Data de Emissão:</label>
                        <input id="data_emissao" type="date" name="data_emissao" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-sm-2">
                        <label for="def">Deficiência:</label>
                        <select class="form-control" name="deficiencia" id="def">
                            <option>Nenhuma</option>
                            <option>Visual</option>
                            <option>Fisica</option>
                            <option>Auditiva</option>
                        </select>
                    </div>
            	</div>
            </div>
			<br>
            <label><h4><b>Endereço</b></h4></label>
            <div class="form-group">
                <div class="col-sm-5">
                    <label>Logradouro:</label>
                    <input id="endereco" class="form-control" type="text" name="endereco" value="" placeholder="Rua Exemplo, 22">
                </div>

                <div class="col-sm-2">
                    <label>Bairro:</label>
                    <input id="bairro" class="form-control" type="text" name="bairro" value="" placeholder="Bela Vista">
                </div>

                <div class="col-sm-2">
                    <label>Cidade:</label>
                    <input id="cidade" class="form-control" type="text" name="cidade" value="" placeholder="Guarabira">
                </div>

                <div class="col-sm-1">
                    <label>UF:</label>
                    <input id="uf" class="form-control" type="text" name="uf" maxlength="2" value="" placeholder="PB">
                </div>

                <div class="col-sm-2">
                    <label>CEP:</label>
                    <input type="text" id="cep" class="form-control" name="cep" value="" placeholder="58.200-000">
                </div>
            </div>
            <br/>
            <label><h4><b>Contato</b></h4></label>
            <div class="form-group">
            	<div class="col-sm-12">
                    <div class="col-sm-4">
                        <label>Nome do Contato:</label>
                        <input type="text" id="contato" name="contato" class="form-control" value="" placeholder="Nome do Contato">
                    </div>
                    <div class="col-sm-2">
                        <label>Telefone:</label>
                        <input class="form-control" type="text" id="telefone" name="telefone" value="" placeholder="Telefone do Contato">
                    </div>

                    <div class="col-sm-4">
                        <label>Skype:</label>
                        <input class="form-control" type="text" id="skype" name="skype" placeholder="Usuário" value="">
                    </div>
            	</div>
            </div>
            <br><br><br><br>
        </div>
    </div>
    <div id="acess" class="tab-pane fade well">
        <div class="alert alert-info">
            <strong>Informação!</strong> Por padrão o acesso do novo funcionário é bloqueado. Devendo o responsável de cada setor, solicitar liberação.
        </div>
        <label><h4><b>Permissões do Usuário</b></h4></label>
        <div class="dados-pessoais control-group">
            <div class="form-group">
                <div class="col-sm-6">
                    <div class="control-group">
                        <label>Email:</label>
                        <input type="email" id="login" name="login" class="form-control" value="" required />
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
                                <option value="1" selected=>Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="control-group">
                            <label>Função:</label>
                            <select name="funcao" id="funcao" class="form-control" required>
                                <option value="0" selected>Padrão</option>
                                <option value="dev">Desenvolvedor</option>
                                <option value="ven">Vendedor</option>
                                <option value="OMNILINK">OMNILINK</option>
                            </select>
                        </div>
                    </div>
               </div>
            </div>
            <?php if ($this->auth->is_allowed_block('admin_permissoes')): ?>
            <div class="form-group">
                <div class="col-sm-6">
                    <div class="control-group">
                        <label class="control-label"><b>Cadastros</b> </label>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="documentacoes" />
                                Documentações
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="licitacoes" />
                                Licitações
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_veiculos"/>
                                Veículos
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_permissoes"/>
                                Permissões (GESTOR)
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_planos"/>
                                Planos (GESTOR)
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_permissoes_funcionarios"/>
                                <?=lang('permissoes_usuarios_show')?>
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_equipamento"/>
                                Equipamentos
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_contratos_eptc"/>
                                Contratos EPTC
                            </label>
                            <br>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_agend_servico" />
                                Agendamento de Serviços
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_instaladores" />
                                Instaladores
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_representantes" />
                                Representantes
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_comandos" />
                                Comandos
                            </label>
                            <br><br>

                            <label class="control-label">Clientes</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"  value="clientes_visualiza" />
                                Visualizar
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="clientes_add" />
                                Adicionar
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="clientes_update" />
                                Atualizar
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="inativa_clie" />
                                Inativar
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="clientes_bloqueio" />
                                Bloqueio usuário
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="clientes_arquivo"/>
                                Download Arquivos
                            </label>
                        </div>
                        <br>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="control-label">Funcionários</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="usuarios_visualiza" />
                                Visualizar
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="usuarios_add" />
                                Adicionar
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="usuarios_update" />
                                Atualizar
                            </label>
                            <label class="checkbox-inline"><input type="checkbox" name="permissoes[]" value="status_funcionario" />
                                <?=lang('status_funcionario')?>
                            </label>
                            <label class="checkbox-inline"><input type="checkbox" name="permissoes[]" value="aplicar_ferias" />
                                <?=lang('aplicar_ferias')?>
                            </label>
                            <label class="checkbox-inline"><input type="checkbox" name="permissoes[]" value="demitir" />
                                <?=lang('demitir')?>
                            </label>
                            <label class="checkbox-inline"><input type="checkbox" name="permissoes[]" value="m2m" />
                                M2M
                            </label>
                        </div>
                        <br>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="control-label">CRM DE VENDAS</label>
                            <label style="margin-left: 10px;" class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="crm_vendas"  />
                                 Vendedor
                            </label>
                            <label style="margin-left: 10px;" class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="crm_vendas_admin" />
                                Administrador
                            </label>
                        </div>
                        <br>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="checkbox">
                                <input type="checkbox" name="permissoes[]" value="cad_linhas" />
                                Linhas
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_mikrotik" />
                                MikroTik
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_chips" />
                                Chips
                            </label>
                        </div>
                        <br>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="checkbox"> <input type="checkbox" name="permissoes[]" value="cad_rh" />
                                RH
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_aniversariantes" />
                                Aniversariantes
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_apresentacoes" />
                                Apresentações
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_banner" />
                                Banner
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_comunicado" />
                                Comunicados
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_contatos_corporativos" />
                                Contatos Corporativos
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_folhetos" />
                                Folhetos
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_sobre_empresa" />
                                Sobre a empresa
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_politicas_formularios" />
                                Políticas, Formulários e Informações Gerais
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_produtos" />
                                Produtos
                            </label>
                            <br><br>
                            <label class="checkbox"> Engenharia e Técnologia</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_suporte_tecnico" />
                               	Suporte Técnico
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_teste_homologacao" />
                                Teste e Homologação
                            </label>
                            <br><br>
                            <label class="checkbox"> Espaço TI</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_espaco_ti" />
                               	Espaço TI
                            </label>
                            <br><br>
                            <label class="checkbox"> Marketing</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_marketing_briefing" />
                               	Briefing
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_marketing_campanhas" />
                                Campanhas
                            </label>
                            <br><br>
                            <label class="checkbox"> Televendas</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_apresentacoes_comerciais" />
                               	Apresentações Comerciais
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_comite_guerra" />
                                Comitê de Guerra
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_televendas_comunicados" />
                                Comunicados
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_propostas_comerciais" />
                                Propostas Comerciais
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_politicas_procedimentos" />
                                Políticas e Procedimentos
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_guia_produtos" />
                                Guia de Produtos
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_folhetos" />
                                Folhetos
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_precos_acessorios" />
                                Tabela de Preços e Acessórios
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_inteligencia_mercado" />
                                Inteligência de Mercado
                            </label>
                            <br><br>
                            <label class="checkbox"> Governança Corporativa</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_governanca_corporativa" />
                               	Governança Corporativa
                            </label>
                            <br><br>
                            <label class="checkbox"> Espaço Gente & Gestao</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_gente_gestao" />
                              Gente e gestão
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_treinamentos" />
                               	Desenvol. Organizacional -> Treinamentos EAD
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_atividades" />
                               	Desenv. Organizacional -> Cad Treinamentos
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_parcerias" />
                               	Desenv. Organizacional -> Parcerias
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_plano_de_voo" />
                               	Desenv. Organizacional -> Plano de Voo
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_correcao_irrf" />
                               	Adm de Pessoal -> Correção de IRRF
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_docs_pendentes" />
                               	Adm de Pessoal -> Documentos Pendentes
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]" value="cad_desconto_coparticipacao" />
                               	Adm de Pessoal -> Desconto de Coparticipação
                            </label>
                        </div>
                        <br><br><br>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label"><b>Relatórios</b></label>
                        <div class="controls" style="margin-left: 15px;">
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_tipo_servico" />
                                Tipo de Serviços
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_veic_tempo_contrato" />
                                Veículo por Tempo de Contrato
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_clients_publicos" />
                                Clientes Públicos
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_contas" />
                                Contas a Pagar
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_contratos" />
                                Contratos
                            </label>
                             <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_placas_ativas_inativas" />
                                Placas Ativas - Inativas
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_tempo_logado" />
                                Tempo logado
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_envio_sms" />
                                Envio SMS
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_eqp_desat" />
                                Equipamentos Desatualizados
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="analise_contaOp" />
                                Analise de Fatura Operadora
                            </label>
                            <br><br>

                            <label class="control-label">Financeiro</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="rel_financeiro_faturas" />
                                Faturas
                            </label>

                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="comissao" />
                                Comissionamento de Vendedores
                            </label>

                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="comissao_showroutes" />
                                Comissionamento - ShowRoutes
                            </label>

                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="rel_adesao" />
                                <?=lang('rel_adesao')?>
                            </label>

                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_financeiro_fatenviadas" />
                                Faturas Enviadas
                            </label> <br/>

                            <label class="control-label">Clientes</label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="rel_clientes_uf" />
                                Cliente por UF
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_resumo_veic_disponiveis" />
                                Resumo Veiculos Disponiveis
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_veic_disponiveis" />
                                Veiculos Disponiveis
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_monitorados_dia_atividade" />
                                <?=lang('monitorados_dia_atualizacao')?>
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="rel_dash_veic" />
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
                                                                   value="contratos_cancelar" />
                                Cancelar
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="edit_contrato" />
                                Editar Vendedor
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="downloads_contratos" />
                                Downloads
                            </label> <br/><br/>

                            <label class="control-label"><b>Admin Permissões</b> </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="admin_permissoes"/>
                                Administrador
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="observador_permissoes"/>
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

                            <label class="checkbox inline"> <input type="checkbox" name="permissoes[]" value="dashboard_iscas" />
                            Dashboard
                            </label>
                            <label class="checkbox inline"> <input type="checkbox" name="permissoes[]" value="equipamentos_iscas" />
                                Equipamentos
                            </label>
                            <label class="checkbox inline"> <input type="checkbox" name="permissoes[]" value="relatorios_iscas" />
                                Relatórios
                            </label>
                            <label class="checkbox inline"> <input type="checkbox" name="permissoes[]" value="comandos_iscas" />
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
                                <input type="checkbox" name="permissoes[]" value="financeiro_acesso" />
                                Só Financeiro tem acesso
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="inadimplencias_faturas" />
                                Inadimplências
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="compras" />
                                Compras
                            </label>
                            <br><br>

                            <label>
                                <input type="checkbox" name="permissoes[]" value="faturas" />
                                Faturas
                            </label>

                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="faturas_visualiza" />
                                Visualizar
                            </label> <label class="checkbox-inline"> <input type="checkbox"
                                                                            name="permissoes[]" value="faturas_add" />
                                Adicionar
                            </label> <label class="checkbox-inline"> <input type="checkbox"
                                                                            name="permissoes[]" value="faturas_update" />
                                Atualizar
                            </label> <label class="checkbox-inline"> <input type="checkbox"
                                                                            name="permissoes[]" value="faturaACancelar" />
                                A Cancelar
                            </label> <label class="checkbox-inline"> <input type="checkbox"
                                                                            name="permissoes[]" value="faturas_delete" />
                                Cancelar
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox"
                                                                   name="permissoes[]" value="faturas_retorno" />
                                Retorno
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox"
                                                                   name="permissoes[]" value="config_boleto" />
                                Conf. boleto
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox"
                                                                   name="permissoes[]" value="edit_tipo_faturamento" />
                                <?=lang('edit_tipo_faturamento')?>
                            </label>
                            <br><br>

                            <label class="checkbox">
                                <input type="checkbox" name="permissoes[]" value="chave_desconto" />
                                Chave de desconto
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="criar_chave_desconto" />
                                Criar
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="listar_chave_desconto" />
                                Listar
                            </label>
                            <br><br>

                            <label class="checkbox">
                                <input type="checkbox" name="permissoes[]" value="contas_a_pagar" />
                                Contas
                            </label>

                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="aprovador" />
                                Aprovador
                            </label>

                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="lancamentos"/>
                                Ordem de Pagamentos (OP)
                            </label>

                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="contas_showtecnologia"/>
                                ShowTecnologia
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="contas_eua" />
                                ShowTechnology
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="contas_pneushow" />
                                Pneu Show
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="valores" />
                                Valores
                            </label>
                            <label style="margin-left: 15px;" class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="cancelar_conta" />
                                Cancelar
                            </label>
                            <br><br>
                            <label class="control-label">Baixa por Extrato</label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="baixa_extrato_show" />
                                Show Tecnologia
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="permissoes[]" value="baixa_extrato_norio" />
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
                                                                   value="visualizar_tickets" />
                                Tickets
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="downloads_os" />
                                OS
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="veiculos_desatualizados" />
                                Desatualizados
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="logs" />
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
                                                                   value="api" />
                                API
                            </label>
                        </div>
                    </div>
                </div>
                <div class="span6 ">
                    <div class="control-group">
                        <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                               value="monitoramento" />
                            <b>Monitoramento</b>
                        </label>
                        <div class="controls" style="margin-left: 15px;">
                            <label style="margin-left: 20px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="equipamentos_violados" />
                                Equipamentos violados
                            </label>

                            <label style="margin-left: 20px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="monitor_panico" />
                                Pânico
                            </label>

                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="gateways" />
                                Gateways
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                   value="monitor_contrato" />
                                Monitoramento Contratos
                            </label>
                        </div>
                    </div>
                </div>
                <div class="span6 ">
                    <div class="control-group">
                        <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                               value="configuracoes" />
                            <b>Configuração</b>
                        </label>
                        <div class="controls" style="margin-left: 15px;">
                            <label style="margin-left: 20px;" class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                                              value="mensagem_notificacao" />
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
                                                                   value="add_termo" />
                                Adicionar
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="edit_termo" />
                                Editar
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="print_termo" />
                                Imprimir
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="admin_termo" />
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
                                                                   value="zoiper" />
                                Zoiper
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="edools" />
                                Edools
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="pipedrive" />
                                PipeDrive
                            </label> <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="trello" />
                                Trello
                            </label>

                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="crm_omnilink" />
                                CRM OMNILINK
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="omniweb" />
                                OMNIWEB
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="iridium" />
                                IRIDIUM
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="turbo" />
                                TURBO - SAVERNET
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="omniturbo" />
                                OMNITURBO - SAVERNET
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="painel_ativ" />
                                PAINEL DE ATIVAÇÃO
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="andromeda" />
                                ANDRÔMEDA - APN
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="mhs"  />
                                MHS - MR
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="telemetria" />
                                TELEMETRIA - CAN
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="telemetria" />
                                EAD - TREINAMENTOS
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="protheus" />
                                WEB PROTHEUS
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="srv" />
                                SVR - WEBGRABER
                            </label>
                            <label class="checkbox-inline"> <input type="checkbox" name="permissoes[]"
                                                                            value="omnitelemetria2" />
                                OMNITELEMETRIA 2.0
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
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
                        	<option>SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA-ME</option>
                        	<option>NORIO MOMOI EPP</option>
                    	</select>
                    </div>
                    <div class="col-sm-2">
                       	<label>Ocupação:</label>
                        <input id="ocupacao" name="ocupacao" type="text" class="form-control"  placeholder="Ex.: Desenvovedor">
                    </div>
                    <div class="col-sm-2">
                       	<label>Data Admissão:</label>
                        <input class="form-control" id="data_admissao" name="admissao" type="date"  required/>
                  	</div>
                   	<div class="col-sm-2">
                        <label>N. Contrato:</label>
                        <input id="num_contrato" name="num_contrato" type="number" class="form-control" placeholder="Ex.: 0001"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
            	<div class="col-sm-12">
                    <div class="col-sm-2">
                        <label>PIS:</label>
                        <input id="pis" name="pis" type="text" class="form-control" placeholder="Numero do PIS do Funcionário"/>
                    </div>

                    <div class="col-sm-2">
                        <label>Salário:</label>
                        <input id="salario" name="salario" type="text" class="form-control"  placeholder="Ex.: 1.050,00" />
                    </div>

                    <div class="col-sm-2">
                        <label>Cidade:</label>
                        <input id="city_job" name="city_job" type="text" class="form-control" placeholder="Cidade Filial da Empresa">
                    </div>

                    <div class="col-sm-2">
                        <label>Carteira Profissional:</label>
                        <input id="ctps" name="ctps" class="form-control" type="text" placeholder="Ex.: 735424-434">
                    </div>

                    <div class="col-sm-2">
                        <label>Ramal:</label>
                        <input class="form-control" type="text" id="ramal" name="ramal" placeholder="Ramal de Atendimento" >
                    </div>
            	</div>
            </div>
            <br/>
            <label><h4><b>Horário de Trabalho</b></h4></label>
            <div class="form-group">
                <div class="col-sm-2">
                    <label>* Carga Horária (Semanal):</label>
                    <input class="form-control hora" type="text" id="tempo_logado" name="tempo_logado" placeholder="00:00:00"  required />
                </div>
                <div class="col-sm-2">
                    <label>
                        <label>Início da Jornada:</label>
                        <input class="form-control hora" type="text" name="inicio_job" id="inicio_job" placeholder="00:00:00" >
                    </label>
                </div>
                <div class="col-sm-2">
                    <label>Fím da Jornada:</label>
                    <input class="form-control hora" type="text" name="fim_job" id="fim_job" placeholder="00:00:00" >
                </div>
                <div class="col-sm-2">
                    <label>Intervalo Almoço:</label>
                    <input class="form-control hora" type="text" name="intervalo_job" id="intervalo_job" placeholder="00:00:00">
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

    $('#salvarUpdate').on('click', function () {
    	if (forca >= 75) {
            var permissoes = new Array();
            $("input[type=checkbox][name='permissoes[]']:checked").each(function(){
                permissoes.push($(this).val());
            });

            var url = "<?= site_url('usuarios/add_usuario') ?>";
            $.ajax({
                url : url,
                type : 'POST',
                dataType : 'json',
                data : {
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
                    'permissoes': permissoes
                },
                success : function(data){
                    if (data.status == 'OK') {
                        alert(data.mensagem);
                        window.location.href = "<?= site_url('usuarios/visualizar').'/' ?>"+data.id_user;
                    } else {
                        alert(data.mensagem);
                    }
                },
                error : function () {
                    alert('Sistema temporariamente indisponível, tente novamente mais tarde!');
                }
            });
    	} else {
            alert('A senha do usuário deve conter no mínimo 7 caracteres, uma letra maiúscula, um caractere especial e um número. Ex.: Ex&mpl@20 ');
        }
    });
</script>
