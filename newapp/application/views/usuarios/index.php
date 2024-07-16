<?php
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $userIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $userIP = $_SERVER['REMOTE_ADDR'];
}
?>

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("funcionarios") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('gente_gestao') ?> >
        <?= lang('rh') ?> >
        <?= lang('funcionarios') ?>
    </h4>
</div>

<div class="funcionarios-alert alert" style="display:none;">
    <button type="button" class="close" onclick="fecharMensagem('funcionarios-alert')">
        <span aria-hidden="true">&times;</span>
    </button>
    <span id="msgFuncionarios"></span>
</div>

<div id="modalLoadingMessage" class="modalLoadingMessageAct" style="
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 100vw;
    background-color: rgba(15,15,15, 0.5);
    z-index: 10000050;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #FFFFFF;
">
    <div class="loader" style="font-size: 90px; color: #06a9f6; margin-top: 25%;"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px; ">
    <div class="col-md-3">
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container buscaCliente buscaAlertasEmail">
                        <label for="funcionarioBusca">Funcionário:</label>
                        <select class="form-control" name="funcionarioBusca" id="funcionarioBusca" type="text" style="width: 100%;">
                            <option value="" disabled selected>Buscando funcionários...</option>
                        </select>
                    </div>

                    <div class="input-container buscaAlertasEmail">
                        <label for="emailBusca">E-mail:</label>
                        <input type="email" name="emailBusca" class="form-control" placeholder="Digite o E-mail" id="emailBusca" />
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="button"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <!-- MENSAGEM DE RESULTADO DE REQUISICOES -->

        <div class="card-conteudo card-funcionarios" style="margin-bottom: 20px;">
            <div class="tablePageHeader">
                <h3>Gerenciamento de Funcionários: </h3>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center; margin-top:15px; margin-bottom:15px;">
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button onclick="$('.dropdown-menu-acoes').hide();" class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-bottom: 5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </div>

            <div class="registrosDiv">
                <div id="registros-div" style="display: flex; flex-direction: row; margin-bottom: 5px;">
                    <select id="select-quantidade-por-pagina-funcionarios" class="form-control" style="width: auto; height: 34px;">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
                </div>

            </div>
            <div style="position: relative;">
                <div id="loadingMessageFunc" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>
                <div class="wrapperFuncionarios">
                    <div id="tableFuncionarios" class="ag-theme-alpine my-grid-funcionarios" style="height: auto;">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- Modal Cadastrar Funcionario -->
<div id="modalCadFuncionario" class="modal fade" tabindex="-1" data-toggle="modal" aria-labelledby="myModalLabel1">
    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="tituloCadFuncionario"><?= lang('cadastrar_funcionario') ?></h3>
            </div>
            <ul class="nav nav-tabs" style="margin-left: 10px;">
                <li class="nav-item active">
                    <a class="nav-link active" id="aba1-tab" data-toggle="tab" href="#aba1">Dados pessoais</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="aba2-tab" data-toggle="tab" href="#aba2">Dados profissionais</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="aba3-tab" data-toggle="tab" href="#aba3">Acessos</a>
                </li>
            </ul>

            <div class="modal-body" style="flex-direction: column !important;">
                <div class="tab-content" id="myTabContent" style="max-height: 500px; overflow-y: auto;">

                    <div style="display: block;" id="aba1">

                        <form id="formNovoFuncionarioEtapa1" autocomplete="off" novalidate>
                            <div>
                                <div class="col-md-12">
                                    <h4 class="subtitle"><?= lang('dados_pessoais') ?></h4>
                                </div>
                                <div class="col-md-12" style="padding: 0 5px;">
                                    <div class="col-md-4 input-container form-group">
                                        <label for="nome" class="control-label">Nome:</label>
                                        <input type="text" maxlength="100" id="nome" name="nome" class="form-control" value="" placeholder="Nome Completo do Funcionário" />
                                    </div>
                                    <div class="col-md-4 input-container form-group">
                                        <label for="nacionalidade" class="control-label">Nacionalidade:</label>
                                        <input type="text" maxlength="50" id="nacionalidade" name="nacionalidade" class="form-control" value="" placeholder="Brasileiro(a)" />
                                    </div>
                                    <div class="col-md-4 input-container form-group">
                                        <label for="naturalidade" class="control-label">Naturalidade:</label>
                                        <input type="text" maxlength="50" id="naturalidade" name="naturalidade" class="form-control" value="" placeholder="Guarabira" />
                                    </div>
                                    <div class="col-md-4 input-container form-group">
                                        <label for="data_nasc" class="control-label">Data de Nascimento:</label>
                                        <input type="date" id="data_nasc" name="data_nasc" class="form-control" value="<?= date('Y-m-d') ?>">
                                    </div>

                                    <div class="col-md-4 input-container form-group">
                                        <label for="civil">Estado Civil:</label>
                                        <select class="form-control span12" name="civil" id="civil">
                                            <option>Solteiro(a)</option>
                                            <option>Casado(a)</option>
                                            <option>Viuvo(a)</option>
                                            <option>Divorciado(a)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 input-container form-group">
                                        <label for="formacao">Grau de formação:</label>
                                        <select class="form-control span12" id="formacao" name="formacao" id="formacao">
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
                                    <div class="col-md-4 input-container form-group">
                                        <label for="cpf" class="control-label">CPF:</label>
                                        <input id="cpf" name="cpf" type="text" class="form-control cpf" value="" placeholder="000.000.000-00">
                                    </div>
                                    <div class="col-md-4 input-container form-group">
                                        <label for="rg" class="control-label">RG:</label>
                                        <input id="rg" maxlength="20" name="rg" type="text" class="form-control" value="" placeholder="Numero do RG">
                                    </div>
                                    <div class="col-md-4 input-container form-group">
                                        <label for="emissor_rg" class="control-label">Orgão Emissor:</label>
                                        <input id="emissor_rg" maxlength="20" class="form-control" name="emissor_rg" type="text" value="" placeholder="SDS-PE">
                                    </div>
                                    <div class="col-md-4 input-container form-group">
                                        <label for="data_emissor" class="control-label">Data de Emissão:</label>
                                        <input id="data_emissor" type="date" name="data_emissor" class="form-control" value="<?= date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-md-4 input-container form-group">
                                        <label for="deficiencia">Deficiência:</label>
                                        <select class="form-control" name="deficiencia" id="deficiencia">
                                            <option>Nenhuma</option>
                                            <option>Visual</option>
                                            <option>Fisica</option>
                                            <option>Auditiva</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 input-container form-group">
                                        <label for="sexo" class="control-label">Sexo:</label>
                                        <select class="form-control" name="sexo" id="sexo">
                                            <option value="M">Masculino</option>
                                            <option value="F">Feminino</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h4 class="subtitle">Endereço</h4>
                                </div>
                                <div class="col-md-12" style="padding: 0 5px;">
                                    <div class="col-md-4 input-container form-group">
                                        <label for="endereco">Logradouro:</label>
                                        <input id="endereco" maxlength="200" class="form-control" type="text" name="endereco" value="" placeholder="Rua Exemplo, 22">
                                    </div>

                                    <div class="col-md-4 input-container form-group">
                                        <label for="bairro">Bairro:</label>
                                        <input id="bairro" maxlength="50" class="form-control" type="text" name="bairro" value="" placeholder="Centro">
                                    </div>

                                    <div class="col-md-4 input-container form-group">
                                        <label for="cidade">Cidade:</label>
                                        <input id="cidade" maxlength="50" class="form-control" type="text" name="cidade" value="" placeholder="Garanhuns">
                                    </div>

                                    <div class="col-md-4 input-container form-group">
                                        <label for="uf" class="control-label">UF:</label>
                                        <select class="form-control" name="UF" id="UF">
                                            <option value="" selected disabled><?= lang('selecione') ?></option>
                                            <option value="AC">Acre</option>
                                            <option value="AL">Alagoas</option>
                                            <option value="AP">Amapá</option>
                                            <option value="AM">Amazonas</option>
                                            <option value="BA">Bahia</option>
                                            <option value="CE">Ceará</option>
                                            <option value="DF">Distrito Federal</option>
                                            <option value="ES">Espírito Santo</option>
                                            <option value="GO">Goiás</option>
                                            <option value="MA">Maranhão</option>
                                            <option value="MT">Mato Grosso</option>
                                            <option value="MS">Mato Grosso do Sul</option>
                                            <option value="MG">Minas Gerais</option>
                                            <option value="PA">Pará</option>
                                            <option value="PB">Paraíba</option>
                                            <option value="PR">Paraná</option>
                                            <option value="PE">Pernambuco</option>
                                            <option value="PI">Piauí</option>
                                            <option value="RJ">Rio de Janeiro</option>
                                            <option value="RN">Rio Grande do Norte</option>
                                            <option value="RS">Rio Grande do Sul</option>
                                            <option value="RO">Rondônia</option>
                                            <option value="RR">Roraima</option>
                                            <option value="SC">Santa Catarina</option>
                                            <option value="SP">São Paulo</option>
                                            <option value="SE">Sergipe</option>
                                            <option value="TO">Tocantins</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 input-container form-group">
                                        <label for="cep">CEP:</label>
                                        <input type="text" id="cep" class="form-control cep" name="cep" value="" placeholder="58.200-000">
                                    </div>
                                </div>
                                <br />
                                <div class="col-md-12">
                                    <h4 class="subtitle">Contato</h4>
                                </div>
                                <div class="col-md-12" style="padding: 0 5px;">
                                    <div class="col-md-4 input-container form-group">
                                        <label for="contato">Nome do Contato:</label>
                                        <input type="text" maxlength="100" id="contato" name="contato" class="form-control" value="" placeholder="Nome do Contato">
                                    </div>
                                    <div class="col-md-4 input-container form-group">
                                        <label for="telefone">Telefone:</label>
                                        <input class="form-control fone" type="text" id="telefone" name="telefone" value="" placeholder="(00) 00000-0000">
                                    </div>

                                    <div class="col-md-4 input-container form-group">
                                        <label for="conta_skype">Skype:</label>
                                        <input maxlength="50" class="form-control" type="text" id="conta_skype" name="conta_skype" placeholder="Usuário" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <br>
                            </div>

                    </div>

                    <div style="display: none !important;" id="aba2">
                        <div>
                            <div class="col-md-12">
                                <h4 class="subtitle"><?= lang('dados_profissionais') ?></h4>
                            </div>
                            <div class="col-md-12" style="padding: 0 5px;">
                                <div class="col-md-4 input-container form-group">
                                    <label for="emp" class="control-label">Empresa:</label>
                                    <select class="span12 form-control" name="empresa" id="empresa">
                                        <option value="SHOW PRESTADORA DE SERVIÇOS DO">SHOW PRESTADORA DE SERVIÇOS DO
                                            BRASIL LTDA-ME</option>
                                        <option>NORIO MOMOI EPP</option>
                                        <option>SIMM2M</option>
                                        <option>SIGA ME - NORIO MOMOI EPP</option>
                                        <option> SHOW TECHNOLOGY EUA</option>
                                        <option>OMNILINK</option>
                                        <option>SIGAMY</option>
                                        <option>SHOW CURITIBA</option>
                                    </select>
                                </div>
                                <div class="col-md-4 input-container form-group">
                                    <label for="ocupacao" class="control-label">Ocupação:</label>
                                    <input id="ocupacao" maxlength="100" name="ocupacao" type="text" class="form-control" placeholder="Ex.: Desenvolvedor">
                                </div>
                                <div class="col-md-4 input-container form-group">
                                    <label for="data_adimissao" class="control-label">Data Admissão:</label>
                                    <input class="form-control" id="data_admissao" name="data_admissao" type="date" value="<?= date('Y-m-d') ?>" />
                                </div>
                                <div class="col-md-4 input-container form-group">
                                    <label for="num_contrato" class="control-label">N. Contrato:</label>
                                    <input id="num_contrato" min="0" max="999999999999999999999" name="num_contrato" type="number" class="form-control" placeholder="Ex.: 0001" />
                                </div>
                                <div class="col-md-4 input-container form-group">
                                    <label for="id_departamentos" class="control-label">Departamento:</label>
                                    <select class="span12 form-control" name="id_departamentos" id="id_departamentos">
                                        <option value="" selected="selected">Selecione o departamento</option>
                                        <?php foreach ($departamentos as $departamento) : ?>
                                            <option value="<?= $departamento->id ?>"><?= $departamento->nome ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="col-md-4 input-container form-group">
                                    <label for="chefia_imediata">Chefia Imediata:</label>
                                    <input id="chefia_imediata" maxlength="100" name="chefia_imediata" type="text" class="form-control" placeholder="Nome da Chefia" />
                                </div>

                                <div class="col-md-4 input-container form-group">
                                    <label for="diretoria">Diretoria:</label>
                                    <input id="diretoria" maxlength="45" name="diretoria" type="text" class="form-control" placeholder="Nome da diretoria" />
                                </div>

                                <div class="col-md-4 input-container form-group">
                                    <label for="unidade">Unidade:</label>
                                    <input id="unidade" maxlength="45" name="unidade" class="form-control" type="text" placeholder="Nome da unidade" />
                                </div>
                                <div class="col-md-4 input-container form-group">
                                    <label for="pis" class="control-label">PIS:</label>
                                    <input id="pis" name="pis" type="text" class="form-control" placeholder="Numero do PIS do Funcionário" />
                                </div>

                                <div class="col-md-4 input-container form-group">
                                    <label for="salario" class="control-label">Salário:</label>
                                    <input id="salario" name="salario" type="text" class="form-control money2" placeholder="Ex.: 1.050,00" />
                                </div>

                                <div class="col-md-4 input-container form-group">
                                    <label for="city_job" class="control-label">Cidade:</label>
                                    <input id="city_job" maxlength="50" name="city_job" type="text" class="form-control" placeholder="Cidade Filial da Empresa">
                                </div>

                                <div class="col-md-4 input-container form-group">
                                    <label for="ctps" class="control-label">Carteira Profissional:</label>
                                    <input id="ctps" name="ctps" maxlength="50" class="form-control" type="text" placeholder="Ex.: 735424-434">
                                </div>

                                <div class="col-md-4 input-container form-group">
                                    <label for="ramal_telefone">Ramal:</label>
                                    <input class="form-control" maxlength="10" type="text" id="ramal_telefone" name="ramal_telefone" placeholder="Ramal de Atendimento">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4 class="subtitle">Horário de Trabalho</h4>
                            </div>
                            <div class="col-md-12" style="padding: 0 5px;">
                                <div class="col-md-4 input-container form-group">
                                    <label for="tempo_logado" class="control-label">Carga Horária (Semanal):</label>
                                    <input class="form-control hora" type="text" id="tempo_logado" name="tempo_logado" placeholder="00:00:00" />
                                </div>
                                <div class="col-md-4 input-container form-group">
                                    <label for="inicio_job">Início da Jornada:</label>
                                    <input class="form-control hora" type="text" name="inicio_job" id="inicio_job" placeholder="00:00:00">
                                </div>
                                <div class="col-md-4 input-container form-group">
                                    <label for="fim_job">Fím da Jornada:</label>
                                    <input class="form-control hora" type="text" name="fim_job" id="fim_job" placeholder="00:00:00">
                                </div>
                                <div class="col-md-4 input-container form-group">
                                    <label for="intervalo_job">Intervalo Almoço:</label>
                                    <input class="form-control hora" type="text" name="intervalo_job" id="intervalo_job" placeholder="00:00:00">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <br>
                        </div>

                    </div>

                    <div style="display: none !important;" id="aba3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <strong>Informação!</strong> Por padrão o acesso do novo funcionário é bloqueado.
                                Devendo o responsável de cada setor, solicitar liberação.
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h4 class="subtitle"><?= lang('acessos') ?></h4>
                        </div>
                        <div>
                            <div class="col-md-12" style="padding: 0 5px;">
                                <div class="col-md-6" style="padding-bottom: 5px">
                                    <div class="control-group">
                                        <label class="control-label">Email:</label>
                                        <input maxlength="50" type="email" id="loginFunc" name="loginFunc" class="form-control" value="" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="control-group">
                                        <label>Senha:</label>
                                        <input type="password" maxlength="25" id="senhaFunc" name="senhaFunc" class="form-control senha" placeholder="<?= lang('deixe_branco_nao_alterar') ?>" />
                                        <div class="progress" style="margin-top: 5px;">
                                            <div class="progress-bar forca_senha bg-info" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding-bottom: 5px">
                                    <div class="control-group">
                                        <label for="cargo" class="control-label">Cargo:</label>
                                        <select name="cargo" id="cargo" class="form-control">
                                            <option value="" disabled selected><?= lang('selecione_um_cargo') ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <?php if ($this->auth->is_allowed_block('cad_funcao_portal_compras')) : ?>
                                    <div class="col-md-6">
                                        <div class="control-group">
                                            <label for="funcao_portal">Função no portal de compras:</label>
                                            <select name="funcao_portal" id="funcao_portal" class="form-control">
                                                <option value="" disabled selected>Selecione uma função</option>
                                                <option value="solicitante">Solicitante</option>
                                                <option value="aprovador">Aprovadores</option>
                                                <option value="area_compras">Área Compras</option>
                                                <option value="area_fiscal">Área Fiscal</option>
                                                <option value="area_financeira">Área Financeira</option>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" style="margin-top: 40px;" class="btn btn-primary" data-step="" data-acao="novo" id="btnNovoFuncionario3"><?= lang('salvar') ?></button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $('#aba2-tab').click(function(e) {
        e.preventDefault();

        $('#aba2').show();
        $('#aba1').hide();
        $('#aba3').hide();


    });

    $('#aba1-tab').click(function(e) {
        e.preventDefault();

        $('#aba1').show();
        $('#aba2').hide();
        $('#aba3').hide();


    });


    $('#aba3-tab').click(function(e) {
        e.preventDefault();

        $('#aba3').show();
        $('#aba1').hide();
        $('#aba2').hide();


    });
</script>


<!-- MODAL CADASTRAR FUNCIONARIOS EM LOTE -->
<div id="modalCadFuncionarioLote" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog" style="width:95%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3><?= lang('multiplos_funcionarios') ?></span></h3>
                <p class="alert alert-warning">
                    <?= lang('msn_atencao_multiFuncionario') ?> <br>
                    <b><?= lang('modelo_arquivo') ?><b>
                            <a href="<?= base_url('uploads/users/base_funcionarios.xlsx') ?>" download="base_funcionarios.xlsx"><?= lang('baixe_aqui') ?></a>
                </p>
                <b>
                    <p class="alert alert-info"><?= lang('msn2_atencao_multiFuncionario') ?></p>
                </b>
            </div>
            <div class="modal-body">
                <div class="multiFuncionarioAlert alert" style="display:none; margin-bottom: 0px!important;">
                    <button type="button" class="close" onclick="fecharMensagem('multiFuncionarioAlert')">&times;</button>
                    <span id="msnMultiFuncionarios"></span>
                </div>

                <input class="form-control-file" type="file" name="file" id="fileUpload" />
                <div id="dvExcel" style="margin-top:10px; width: 100%;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="cadFuncionariosLote" class="btn btn-primary" disabled><?= lang('salvar') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Demitir Funcionario -->
<div id="Modal-Demissao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id='formDemissaoFunc' method="POST">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleOmnisafe">Demitir</h3>
                    <h6 id="demitirnome"></h6>
                </div>
                <div class="modal-body modal-bodyDemissao">
                    <div class="col-md-12">
                        <div class='row'>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="tipoDemissao"><?= lang('tipo_demissao') ?>: <span class="text-danger">*</span></label>
                                <select class="form-control" name="tipoDemissao" id="tipoDemissao" required>
                                    <option value="" disabled selected>Selecione uma opção</option>
                                    <option value="0">Sem justa Causa</option>
                                    <option value="1">Com Justa Causa</option>
                                    <option value="2">Pedido de Demissão</option>
                                    <option value="3">Termino de Contrato</option>
                                    <option value="4">Tempo Determinado</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="recontratarFuturamente">Recontratar futuramente: <span class="text-danger">*</span></label>
                                <select class="form-control" id="recontratarFuturamente" name="recontratarFuturamente" required>
                                    <option value="" disabled selected>Selecione uma opção</option>
                                    <option value="0">Não Permitido Recontratar</option>
                                    <option value="1">Permitido Recontratar</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="dataDemissao"><?= "Data do desligamento" ?>: <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" name="dataDemissao" id="dataDemissao" value="" required>
                            </div>
                            <div class="col-md-12 input-container form-group" style="height: 59px !important;">
                                <label for="motivoDemissao"><?= "Motivo" ?>: <span class="text-danger">*</span></label>
                                <textarea style="resize: none;" class="form-control" name="motivoDemissao" id="motivoDemissao" required></textarea>
                            </div>
                        </div>

                        <input type="text" name="idUsuario" id="data-id" value="" hidden>
                        <input type="text" name="acao-demitir" id="data-acao" value="" hidden>
                        <input type="text" name="status-demitir" id="data-status" value="" hidden>
                        <hr style="margin: 0;">

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group" style="justify-content: end;">
                        <button type="submit" class="btn btn-danger" id="btnDemitirFunc"><?= 'Demitir' ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Modal Detalhes Funcionario-->
<div id="Modal-Detalhes" class="modal fade" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 60%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="col-md-6 form-group">
                    <h3 class="modal-title"><?= 'Detalhes da Demissão' ?></h3>
                </div>
            </div>
            <div class="modal-body">
                <div class="modal-body-container">
                    <form id="formDetalhesFuncionario" method="post">

                        <div class="col-md-12 form-group">

                            <div class="col-md-6 form-group">
                                <h5 for="titular">Nome</h5>
                                <h6 id="detalhe-nome"></h6>
                            </div>

                            <div class="col-md-6 form-group">
                                <h5 for="titular">Empresa</h5>
                                <h6 id="detalhe-empresa"></h6>
                            </div>

                            <div class="col-md-6 form-group">
                                <h5 for="titular">Formação</h5>
                                <h6 id="detalhe-formacao"></h6>
                            </div>

                            <div class="col-md-6 form-group">
                                <h5 for="titular">Estado</h5>
                                <h6 id="detalhe-uf"></h6>
                            </div>

                            <div class="col-md-6 form-group">
                                <h5 for="titular">Deficiência</h5>
                                <h6 id="detalhe-deficiencia"></h6>
                            </div>

                            <div class="col-md-6 form-group">
                                <h5 for="titular">Email</h5>
                                <h6 id="detalhe-email"></h6>
                            </div>

                            <div class="col-md-6 form-group">
                                <h5 for="titular">Estado Civil</h5>
                                <h6 id="detalhe-civil"></h6>
                            </div>

                            <div class="col-md-6 form-group">
                                <h5 for="titular">Chefia Imediata</h5>
                                <h6 id="detalhe-chefia"></h6>
                            </div>


                        </div>
                        <input id="idUsuario" type="text" hidden>
                        <hr>

                        <div id="demissoes" class="tab-pane fade in active" style="margin-top: 20px">

                            <div class="wrapperDemissoes">
                                <div id="agGridDemissoes" class="ag-theme-alpine"></div>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
            <div class="modal-footer" style="border-top:0px;">

            </div>
        </div>
    </div>
</div>


<!--Modal Readmitir Funcionario-->
<div id="Modal-Readmissao" class="modal fade" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3><?= 'Readmitir' ?></h3>

            </div>
            <div class="modal-body">
                <div class="col-md-12 form-group">
                    <form id="formReadmissaoFunc" method="post">
                        <div class="col-md-12 form-group">
                            <h4 id="readmitirnome"></h4>


                        </div>

                        <hr>
                        <input type="text" name="id-demitir" id="rdata-id" value="" hidden>
                        <input type="text" name="acao-demitir" id="rdata-acao" value="" hidden>
                        <input type="text" name="status-demitir" id="rdata-status" value="" hidden>
                </div>
            </div>


            <div class="modal-footer" style="border-top:0px;">
                <div class="col-md-12 form-group" style="float: right;">
                    <button type="submit" class="btn btn-primary" id="btnReademitirFunc"> <?= 'Readmitir' ?></button>
                </div>
            </div>
            </form>


        </div>
    </div>
</div>


<!-- Modal Cadastrar Conta Bancaria -->
<div id="modalCadContaBancaria" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1">
    <div class="modal-dialog modal-lg" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="tituloCadContaBancaria"></h3>
            </div>
            <div class="modal-body">
                <div class="modal-body-container">
                    <div class="col-md-12">
                        <div style="display: flex;flex-wrap: wrap; justify-content: space-between; gap: 10px; align-items: center; margin-top:15px; margin-bottom:15px;">
                            <div style="display: flex; flex-direction: row;">
                                <select id="paginationSelectContasBancarias" class="form-control" style="width: auto; height: 34px;">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">
                                    Registros por página
                                </h6>
                            </div>
                            <button type="button" data-acao="mostrarForm" class="btn btn-primary addContaBancaria"><i class="fa fa-plus icon-white"></i> <?= lang('nova_conta') ?></button>
                        </div>

                        <div style="position: relative;">
                            <div class="wrapperContasBancariasList">
                                <div id="tableContasBancariasList" class="ag-theme-alpine" style="height: 250px !important">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 0px solid #fff;">
            </div>
        </div>
    </div>
</div>

<div id="modalAddContaBancaria" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="title-conta-bancaria-modal">Cadastrar Conta Bancária</h3>
            </div>
            <div class="modal-body">
                <div class="col justify-content-center" style="width: 100%;">
                    <form id="formCadContaBancaria">
                        <div class="col-md-12 form-group">
                            <label for="titular" class="control-label">Titular da Conta</label>
                            <input type="text" id="titular" name="titular" class="form-control" placeholder="Nome completo" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="cpf" class="control-label">CPF/CNPJ do Titular</label>
                            <input type="text" name="cpf" id="cpfCnpj" class="form-control cpfCnpj" placeholder="000.000.000-00" maxlength="18" required>
                        </div>
                        <div class="col-md-6 form-group default-column">
                            <label for="banco" class="control-label">Banco</label>
                            <select type="text" id="banco" name="banco" class="form-control" required>
                                <option value="001">001 - Banco do Brasil</option>
                                <option value="004">004 - Banco do Nordeste</option>
                                <option value="237">237 - Bradesco</option>
                                <option value="104">104 - Caixa Econômica Federal</option>
                                <option value="341">341 - Itaú</option>
                                <option value="008">008 - Santander</option>
                                <option value="021">021 - Banestes S.A</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">Agência</label>
                            <input type="number" style="text-transform: uppercase;" minlength="2" maxlength="6" id="agencia" name="agencia" class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ex.: 0042" required>
                        </div>
                        <div class="col-md-9 form-group">
                            <label class="control-label">Conta</label>
                            <input type="text" style="text-transform: uppercase;" minlength="4" id="conta" name="conta" class="form-control" placeholder="Ex.: 138765-5" required>
                        </div>
                        <div class="col-md-6 form-group default-column">
                            <label class="control-label">Tipo da Conta</label>
                            <select type="text" id="tipo_conta" name="tipo" class="form-control" required>
                                <option value="corrente">Conta Corrente</option>
                                <option value="poupanca">Poupança</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Operação</label>
                            <input type="number" id="oper" maxlength="4" name="operacao" class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ex.: 013">
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" id="formActionURL" value="addContaBancaria">
                            <button id="btnCadContaBancaria" type="submit" class="btn btn-primary" style="float: right; margin-top:15px;">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer" style="margin-right: 5px; padding-top: 0px"></div>
        </div>
    </div>
</div>

<!-- MODAL DE FÉRIAS -->
<div id="modalFeriasFunc" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id='formFeriasFunc'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title"><?= lang('aplicar_ferias') ?></h3>
                </div>
                <div class="modal-body modal-bodyFerias">
                    <div class="col-md-12">
                        <div class="col-md-12 form-group">
                            <label class="radio-inline"><input type="radio" name="acaoFerias" value="aplicar_ferias" class="addFerias" required checked><?= lang('adicionar_ferias') ?></label>
                            <label class="radio-inline"><input type="radio" name="acaoFerias" value="remover_ferias" class="removeFerias" required><?= lang('remover_ferias') ?></label>
                        </div>
                        <hr>
                        <div class='row'>
                            <div class="col-md-12 form-group" id="divAddFerias">
                                <div class="col-md-6 form-group">
                                    <label><?= lang('data_saida') ?>: <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="data_saida_ferias" id="data_saida_ferias" value="" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label><?= lang('data_retorno') ?>: <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="data_retorno_ferias" id="data_retorno_ferias" value="" required>
                                </div>
                            </div>
                            <div class="col-md-12 form-group" id="divRemoveFerias" style="display:none">
                                <div class="label label-info" style="font-size:12px;"><?= lang('confirme_remocao_ferias') ?>
                                </div>
                            </div>
                        </div>

                        <hr style="margin: 0;">
                    </div>
                </div>
                <div class="modal-footer" style="padding: 8px 18px 8px 18px;">
                    <div class="footer-group" style="justify-content: end;">
                        <button type="submit" class="btn btn-success" id="btnFeriasFunc"><?= lang('salvar') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DE CONFIRMAÇÃO -->
<div id="modalConfirmacaoMudancaStatus" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Confirmação</h4>
            </div>
            <div class="modal-body">
                <p style="text-align: center;" id='mensagemConfirmacao'></p>
            </div>
            <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                <button id="btnConfirmarAcaoUsuario" class="btn btn-success" type="button">Confirmar</button>
                <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE GERENCIAMENTO DE DOCUMENTOS DO FUNCIONÁRIO -->
<div id="modalGerenciamentoDeDocumentoDoFuncionario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Gerenciar Documentos do Funcionário</h3>
            </div>
            <div class="modal-body">
                <div class="modal-body-container">
                    <h4 class="subtitle">Documentos do Funcionário</h4>
                    <div class="col-md-12" style="margin-bottom: 20px;">
                        <div style="display: flex; flex-direction: row; justify-content: space-between;">
                            <div style="display: flex; flex-direction: row; margin-bottom: 5px;">
                                <select id="select-quantidade-por-pagina-documentos-funcionario" class="form-control" style="width: auto; height: 34px;">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por
                                    página</h6>
                            </div>
                            <div style="display: flex; flex-wrap: wrap; align-items: center;">
                                <button class="btn btn-primary" type="button" id="btnModalAddDocumento" style="margin-bottom: 5px;">Adicionar</button>
                            </div>
                        </div>

                        <div style="position: relative;">
                            <div class="wrapperDocumentosFuncionario">
                                <div id="tableDocumentosFuncionario" class="ag-theme-alpine my-grid-documentos-funcionario" style="height: auto;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE GERENCIAMENTO DE DOCUMENTOS DO FUNCIONÁRIO -->
<!-- <div id="modalVisualizarDocumentoFuncionarioTeste" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <div>
                    <h3 class="modal-title">Visualização de Documento</h3>
                </div>
            </div>

            <div class="modal-body" style="position: relative;">
            <div class="modal-body-container" style="margin-bottom: 20px;">
                        <div class="container imgContent">
                            <h4 id="nomeDocumento">Nome do Documento</h4>
                            <div id="pdfContainer"></div>
                            <div id="div-img-mensagem" class="div-img-mensagem" style="display: none;">
                                <h4 class="subtitle">Documento não encontrado</h4>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success" id="documentDownloadBtn">Baixar</button>
                </div>
            </div>

        </div>
    </div>
</div> -->

<div id="modalVisualizarDocumentoFuncionario" class="custom-modal-fullscreen" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="custom-modal-dialog" role="document">
        <div class="custom-modal-content">

            <div class="custom-modal-header">
                <h3 class="modal-title">Visualização de Documento</h3>
                <button type="button" class="custom-close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <div class="custom-modal-body">
                <div class="modal-body-container">
                    <div class="container imgContent">
                        <h4 id="nomeDocumento">Nome do Documento</h4>
                        <div id="pdfContainer"></div>
                        <div id="div-img-mensagem" class="div-img-mensagem" style="display: none;">
                            <h4 class="subtitle">Documento não encontrado</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="custom-modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success" id="documentDownloadBtn">Baixar</button>
            </div>

        </div>
    </div>
</div>

<!-- MODAL DE GERENCIAMENTO DE DOCUMENTOS DO FUNCIONÁRIO -->
<div id="modalEnviarDocumentoFuncionario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <div>
                    <h3 class="modal-title titleDocumento">Adicionar Documento</h3><br>
                </div>
            </div>

            <div class="modal-body">

                <div class="container col-md-12">
                    <form method="post" id="documentoFuncionarioForm">
                        <div class="form-group" style="gap: 5px;">
                            <div class="input-container input-documento">
                                <label for="tipoDoc">Tipo de Documento:</label>
                                <select class="form-control" name="tipoDoc" id="tipoDoc" type="text" style="width: 100%;">
                                    <option value="" disabled selected>Buscando Tipos de Documento...</option>
                                </select>
                            </div>

                            <div class="input-container input-documento">
                                <label for="arquivoItens">Anexo do Documento <span class="text-danger">*</span></label>
                                <input class="form-control" name="arquivoItens" id="arquivoItens" type="file" accept="application/pdf">
                            </div>
                        </div>
                    </form>
                </div>

            </div>

            <div class="modal-footer" style="padding: 8px 18px 8px 18px;">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button class="btn btn-success" id="btnSendDocumento" type="button">Enviar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var Router = '<?= site_url('usuarios') ?>';
    var BaseURL = '<?= base_url('') ?>';
    var SiteURL = '<?= site_url('') ?>';

    <?php
    $this->load->model('auth');
    ?>

    var userDataOverlay = "Email: <?php print_r($_SESSION['shownet']['log_admin']['email']) ?> Nome: <?php print_r($_SESSION['shownet']['log_admin']['nome']) ?> | IP: <?= $userIP ?> Data: <?= date("Y-m-d h:i:sa") ?>";

    var permissaoUsuario = [
        <?= $this->auth->is_allowed_block('status_funcionario') ?>,
        <?= $this->auth->is_allowed_block('aplicar_ferias') ?>,
        <?= $this->auth->is_allowed_block('demitir') ?>,
        <?= $this->auth->is_allowed_block('vis_detalhes') ?>
    ];
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script src="https://unpkg.com/pdf-lib/dist/pdf-lib.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/js/libraries/imageViewer/dist', 'photoswipe.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/libraries/imageViewer/dist', 'photoswipe-lightbox.esm.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/util/Files', 'PDFViewer.js') ?>"></script>

<script type="text/javascript" src="<?= versionFile('assets/js/helpers/EzMock', 'app.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/aggrid', 'agGridTable.js') ?>"></script>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/usuarios', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/usuarios', 'detailCellRenderer.js') ?>"></script>

<script type="text/javascript" src="<?= versionFile('assets/js/usuarios', 'Constants.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/usuarios/Funcionarios', 'modalDocumentos.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/usuarios/Funcionarios', 'modalContasBancarias.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/usuarios', 'Funcionarios.js') ?>"></script>
<style>
    .ag-overlay-no-rows-wrapper p,
    .ag-paging-description span,
    .ag-paging-row-summary-panel span {
        font-style: normal;
        font-weight: 400
    }
</style>