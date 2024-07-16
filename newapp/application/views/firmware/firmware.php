<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("firmware_lista") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('firmware_lista') ?>
    </h4>
</div>

<?php
$menu_firmware = $_SESSION['menu_firmware'];
?>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li>
                    <a class='menu-interno-link <?= $menu_firmware == 'historicoEnvio' ? 'selected' : '' ?>' id="menu-historico-envio"><?= lang("historio_envio_firmware") ?></a>
                </li>

                <?php if ($this->auth->is_allowed_block('vis_hardwares_cadastrado')) : ?>
                    <li>
                        <a class='menu-interno-link <?= $menu_firmware == 'tecnologiasCadastradas' ? 'selected' : '' ?>' id="menu-tecnologias-cadastradas"><?= lang("tecnologias_Cadastradas") ?></a>
                    </li>
                <?php endif; ?>

                <?php if ($this->auth->is_allowed_block('vis_firmware_cadastrado')) : ?>
                    <li>
                        <a class='menu-interno-link <?= $menu_firmware == 'firmwareCadastrados' ? 'selected' : '' ?>' id="menu-firmware-cadastrados"><?= lang("firmware_Cadastrado") ?></a>
                    </li>
                <?php endif; ?>

                <?php if ($this->auth->is_allowed_block('vis_cadastro_excecoes')) : ?>
                    <li>
                        <a class='menu-interno-link <?= $menu_firmware == 'regrasDeEnvio' ? 'selected' : '' ?>' id="menu-regras-envio"><?= lang("regras_envio_firmware") ?></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">

                    <div class="input-container modeloFirmwareBusca">
                        <label for="modeloFirmwareBusca">Versão:</label>
                        <input type="text" name="modeloFirmwareBusca" class="form-control" placeholder="Digite a versão..." id="modeloFirmwareBusca" autocomplete="off" />
                    </div>

                    <div class="input-container descricaoFirmwareBusca">
                        <label for="descricaoFirmwareBusca">Descrição:</label>
                        <input type="text" name="descricaoFirmwareBusca" class="form-control" placeholder="Digite a descrição..." id="descricaoFirmwareBusca" autocomplete="off" />
                    </div>

                    <div class="input-container nomeTecnologia">
                        <label for="nomeTecnologia">Nome do Hardware:</label>
                        <input type="text" name="nomeTecnologia" class="form-control" placeholder="Digite o nome do hardware..." id="nomeTecnologia" autocomplete="off" />
                    </div>

                    <div class="input-container form-group clienteBusca">
                        <label for="clienteBusca">Cliente:</label>
                        <select class="form-control" type="text" name="clienteBusca" id="clienteBusca" placeholder="Selecione o Cliente..." style="width: 100%;">
                            <option value="">Selecione o Cliente</option>
                        </select>
                    </div>

                    <div class="input-container nomeRegra">
                        <label for="nomeRegra">Descrição:</label>
                        <input type="text" name="nomeRegra" class="form-control" placeholder="Digite a descrição da exceção..." id="nomeRegra" autocomplete="off" />
                    </div>

                    <div class="input-container serial">
                        <label for="serial">Serial:</label>
                        <input type="text" name="serial" class="form-control" placeholder="Digite o Serial..." id="serial" autocomplete="off" />
                    </div>

                    <div class="input-container dataInicioBusca">
                        <label for="dataInicialBusca">Data Inicial:</label>
                        <input class="form-control" type="datetime-local" name="dataInicialBusca" id="dataInicialBusca" />
                    </div>

                    <div class="input-container dataFinalBusca">
                        <label for="dataFinalBusca">Data Final:</label>
                        <input class="form-control" type="datetime-local" name="dataFinalBusca" id="dataFinalBusca" />
                    </div>

                    <div class="button-container">
                        <button class="btn btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimpar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-dados-firmware" style='margin-bottom: 20px; position: relative; <?= $menu_firmware == 'firmwareCadastrados' ? '' : 'display: none;' ?>'>
            <h3>
                <b><?= lang("firmware_Cadastrado") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button class="btn btn-primary" id="BtnAdicionarFirmware" type="button" style="margin-right: 10px;" data-toggle="modal" data-target="#addFirmware"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar</button>
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-dados" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div style="position: relative;">
                <div class="wrapperFirmware">
                    <div id="tableFirmware" class="ag-theme-alpine my-grid-firmware" style="height: 519px">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-dados-tecnologias" style='margin-bottom: 20px; position: relative; <?= $menu_firmware == 'tecnologiasCadastradas' ? '' : 'display: none;' ?>'>
            <h3>
                <b><?= lang("tecnologias_Cadastradas") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">

                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonCadastrar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <i class="fa fa-plus" aria-hidden="true"></i> <?= lang('cadastrar') ?> <span class="caret"></span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-cadastro" aria-labelledby="dropdownMenuButtonCadastrar" id="opcoes_cadastro" style="margin-top: 30px;">
                            <h5 class="dropdown-title"> O que você deseja cadastrar? </h5>
                            <div class="dropdown-item opcao-cadastro" id="btnCadastroTecnologia">
                                <i class="fa fa-plus" aria-hidden="true" style="margin-right: 5px; color: #008000;"></i> Hardware
                            </div>

                            <div class="dropdown-item opcao-cadastro" id="btnCadastroModelo">
                                <i class="fa fa-plus" aria-hidden="true" style="margin-right: 5px; color: #008000;"></i> Modelo
                            </div>
                        </div>

                    </div>
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonTecnologias" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_tecnologias" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-dados-tecnologia" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div style="position: relative;">
                <div class="wrapperTecnologia">
                    <div id="tableTecnologia" class="ag-theme-alpine my-grid-tecnologia" style="height: 519px">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-dados-regras" style='margin-bottom: 20px; position: relative; <?= $menu_firmware == 'regrasDeEnvio' ? '' : 'display: none;' ?>'>
            <h3>
                <b><?= lang("regras_envio_firmware") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <button class="btn btn-primary" id="btnAddRegras" type="button" style="margin-right: 10px;" data-toggle="modal" data-target="#addRegras"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar</button>
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonRegras" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_regras" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-regras" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div style="position: relative;">
                <div class="wrapperRegras">
                    <div id="tableRegras" class="ag-theme-alpine my-grid-regras" style="height: 519px">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-conteudo card-historico-envio" style='margin-bottom: 20px; position: relative; <?= $menu_firmware == 'historicoEnvio' ? '' : 'display: none;' ?>'>
            <h3>
                <b><?= lang("historio_envio_firmware") ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button style="display: flex !important; align-items: center;" class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButtonAtualizar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <svg style="margin-right: 5px;" heigth="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="#f5f5f5" d="M463.5 224H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1c-87.5 87.5-87.5 229.3 0 316.8s229.3 87.5 316.8 0c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0c-62.5 62.5-163.8 62.5-226.3 0s-62.5-163.8 0-226.3c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5z" />
                            </svg> <span id="dropdownMenuButtonLabel">Atualizar</span> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-acoes opcoes_importacao dropdown-menu-atualizacao" aria-labelledby="dropdownMenuButtonAtualizar" id="opcoes_atualizar" style="width: 180px; height: 100px;">
                            <h5 class="dropdown-title"> Atualização automática </h5>
                            <div class="dropdown-item opcao_importacao" id="30seg">
                                A cada 30 Segundos
                            </div>
                            <div class="dropdown-item opcao_importacao" id="90seg">
                                A cada 90 Segundos
                            </div>
                            <div class="dropdown-item opcao_importacao" id="180seg">
                                A cada 180 Segundos
                            </div>
                            <div class="dropdown-item opcao_importacao" id="stopInterval">
                                Desativar
                            </div>
                        </div>
                    </div>
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonHistorico" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_historico" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="registrosDiv">
                <select id="select-quantidade-por-pagina-historico" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div style="position: relative;">
                <div class="wrapperHistorico">
                    <div id="tableHistorico" class="ag-theme-alpine my-grid-historico" style="height: 519px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE CADASTRO FIRMWARE -->
<div id="addFirmware" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id='formAddFirmware'>
                <input type="hidden" name="idFirmware" id="idFirmware">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleAddFirmware">Cadastrar Firmware</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <h4 class="subtitle" id="dadosFirmwareModal" style="padding-left: 0; padding-right: 0;">Dados para Cadastro</h4>
                        <div class='row'>

                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="versaoFirmware">Versão: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="versaoFirmware" id="versaoFirmware" placeholder="Versão do Firmware" required autocomplete="off" />
                            </div>

                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="liberacaoFirmware">Liberação: <span class="text-danger">*</span></label>
                                <input class="form-control" type="datetime-local" name="liberacaoFirmware" id="liberacaoFirmware" placeholder="Descrição do Firmware" required autocomplete="off" />
                            </div>

                            <div class="col-md-12 input-container form-group" style="height: 59px !important;">
                                <label for="descricaoRegra">Descrição: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="descricaoFirmware" id="descricaoFirmware" placeholder="Descrição do Firmware" required autocomplete="off" />
                            </div>

                            <div class="col-md-6 input-container form-group hardware" style="height: 59px !important;">
                                <label for="hardwareFirmware">Hardware: <span class="text-danger">*</span></label>
                                <select class="form-control" type="text" name="hardwareFirmware" id="hardwareFirmware" placeholder="Selecione o Hardware..." style="width: 100%;" required>
                                    <option value="">Selecione o Hardware</option>
                                </select>
                            </div>

                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="modeloFirmware">Selecione o Modelo: <span class="text-danger">*</span></label>
                                <select class="form-control" type="text" name="modeloFirmware" id="modeloFirmware" placeholder="Digite o Perfil" style="width: 100%;" disabled required>
                                    <option value="">Selecione o modelo...</option>
                                </select>
                            </div>


                            <hr>
                            <div class="col-md-12">

                                <h4 class="subtitle">Arquivos:</h4>

                                <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                    <label for="arquivoFirmware">Arquivo:<span class="text-danger">*</span></label>
                                    <input type="file" name="arquivoFirmware" class="form-control-file" id="arquivoFirmware" required />
                                </div>

                                <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                    <label for="releaseNotes">Release notes:<span class="text-danger">*</span></label>
                                    <input type="file" name="releaseNotes" class="form-control-file" id="releaseNotes" required />
                                </div>
                            </div>

                            <hr>


                            <div class="col-md-12">
                                <h4 class="subtitle" style='text-align: left;'>Observações:</h4>
                                <ol style='display: flex; justify-content: left;'>
                                    <div style='text-align: left;'>
                                        <li>O Arquivo deve ser do tipo .bin;</li>
                                        <li>A Release Notes deve ser do tipo .pdf;</li>
                                        <li>Os arquivos não podem estar vazios;</li>
                                    </div>
                                </ol>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group" style="justify-content: flex-end">
                        <button type="button" class="btn btn-success" id='btnSalvarCadastro'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DE CADASTRO TECNOLOGIA -->
<div id="addTecnologia" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleAddTecnologia">Cadastrar Hardware</h3>
            </div>
            <div class="modal-body" style="height: auto;">

                <div class="col-md-12">
                    <div class="col-md-8 form-group" id="divNomeTecnologia" style="display: flex;">
                        <label for="descricaoTecnologia" style="display: flex; align-items: center;">
                            Nome:
                            <span class="text-danger" style="margin-left: 4px; margin-right: 8px;">*</span>
                        </label>
                        <input class="form-control input-sm" name="nomeTecnologiaCadastro" id="nomeTecnologiaCadastro" placeholder="Digite o nome do hardware" type="text" autocomplete="off">
                    </div>

                    <div class="col-md-4 form-group" style="margin-left: auto;">
                        <button class="btn btn-primary" type="button" id="botao-adicionar-tecnologia-arquivo" disabled>Adicionar</button>
                        <button class="btn" id="limparTabelaItens" style="background-color: red;color: white">Limpar Tabela</button>
                    </div>
                </div>

                <div class="wrapperAddTecnologia">
                    <div id="tableAddTecnologia" class="ag-theme-alpine my-grid-addTecnologia">
                    </div>
                </div>

            </div>
            <div class="modal-footer" style="margin-top: 30px;">
                <div class="footer-group" style="justify-content: flex-end">
                    <button type="button" class="btn btn-success" id='btnSalvarCadastroTecnologia'>Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- MODAL DE CADASTRO MODELO -->
<div id="addModelo" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id='formAddModelo'>
                <input type="hidden" name="idModelo" id="idModelo">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleAddModelo">Cadastrar Modelo</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Dados para Cadastro</h4>
                        <div class='row'>

                            <div class="col-md-6 input-container form-group" id="inputNomeModelo" style="height: 59px !important;">
                                <label for="descricaoModelo">Nome: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="descricaoModelo" id="descricaoModelo" placeholder="Nome da Modelo" required autocomplete="off" />
                            </div>

                            <div class="col-md-6 input-container form-group hardware" style="height: 59px !important;">
                                <label for="tecnologiaModelo">Hardware: <span class="text-danger">*</span></label>
                                <select class="form-control" type="text" name="tecnologiaModelo" id="tecnologiaModelo" placeholder="Carregando Hardwares..." style="width: 100%;">
                                    <option value="">Selecione o Hardware</option>
                                </select>
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="footer-group" style="justify-content: flex-end">
                            <button type="submit" class="btn btn-success" id='btnSalvarCadastroModelo'>Salvar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DE DETALHES TECNOLOGIA -->
<div id="detalhesTecnologia" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <input type="hidden" name="idDetalhesTecnologia" id="idDetalhesTecnologia">
            <div class="modal-header header-layout">
                <div class="dropdown" style="margin-right: 10px;">
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleDetalhesTecnologia">Detalhes do Hardware</h3>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">

                        <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Informações do Hardware</h4>
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonDetalhes" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonDetalhes" id="opcoes_exportacao_detalhes" style="min-width: 100px; top: 77px; right: 13px; height: 91px;">
                        </div>

                    </div>


                    <div class='row'>

                        <div class="col-md-12 input-container form-group" style="height: 59px !important;">
                            <label for="modalDetalhesNome">Nome:</label>
                            <input class="form-control" type="text" name="modalDetalhesNome" id="modalDetalhesNome" placeholder="Nome do Hardware" required disabled />
                        </div>

                        <hr>

                        <div style="position: relative;">
                            <div class="wrapperDetalhesTecnologia">
                                <div id="tableDetalhesTecnologia" class="ag-theme-alpine my-grid-detalhes-tecnologia" style="height: 519px">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group" style="justify-content: flex-end">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE CADASTRO REGRAS -->
<div id="addRegra" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id='formAddRegras'>
                <input type="hidden" name="idRegra" id="idRegra">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleAddRegra">Cadastrar Regra</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <h4 class="subtitle" id="dadosRegraModal" style="padding-left: 0; padding-right: 0;">Dados para Cadastro</h4>
                        <div class='row'>

                            <div class="col-md-6 input-container form-group cliente" style="height: 59px !important;">
                                <label for="clienteRegra">Cliente: <span class="text-danger">*</span></label>
                                <select class="form-control" type="text" name="clienteRegra" id="clienteRegra" placeholder="Selecione o Cliente..." style="width: 100%;" required>
                                    <option value="">Selecione o Cliente</option>
                                </select>
                            </div>

                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="descricaoRegra">Descrição: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="descricaoRegra" id="descricaoRegra" placeholder="Descrição da Exceção" required autocomplete="off" />
                            </div>

                            <hr>
                            <div class="col-md-12">
                                <h4 class="subtitle">Dados da Exceção:</h4>

                                <div class="row">
                                    <div class="col-md-4 input-container form-group" style="display: flex; flex-direction: column; align-items: flex-start;">
                                        <label for="enviarAtualizacao">Desabilitar atualização automática:</label>
                                        <label class="switch">
                                            <input type="checkbox" name="enviarAtualizacao" id="enviarAtualizacao">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>

                                    <div class="col-md-4 input-container form-group" style="display: flex; flex-direction: column; align-items: flex-start;">
                                        <label for="atualizarDia">Atualizar em dias específicos:</label>
                                        <label class="switch">
                                            <input type="checkbox" name="atualizarDia" id="atualizarDia">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>

                                    <div class="col-md-4 input-container form-group" style="display: flex; flex-direction: column; align-items: flex-start;">
                                        <label for="atualizarHora">Atualizar em um intervalo de horas:</label>
                                        <label class="switch">
                                            <input type="checkbox" name="atualizarHora" id="atualizarHora">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>

                                <hr id="tagHr">

                                <div class="col-md-6 input-container form-group horaInicio" style="height: 59px !important;">
                                    <label for="horaAtualizacaoInicio">Hora Inicial: <span class="text-danger">*</span></label>
                                    <input type="time" name="horaAtualizacao" class="form-control" id="horaAtualizacao">
                                </div>
                                <div class="col-md-6 input-container form-group horaFim" style="height: 59px !important;">
                                    <label for="horaAtualizacaoFim">Hora Final: <span class="text-danger">*</span></label>
                                    <input type="time" name="horaAtualizacaoFim" class="form-control" id="horaAtualizacaoFim">
                                </div>

                                <div class="col-md-4 input-container form-group diaAtualizacao" style="height: 59px !important;">
                                    <label for="diaAtualizacao">Dia da Atualização: <span class="text-danger">*</span></label>
                                    <select class="form-control" name="diaAtualizacao" id="diaAtualizacao" style="width: 100%;">
                                    </select>
                                </div>

                                <div class="col-md-2 input-container form-group diaHoraInicio" style="height: 59px !important;">
                                    <label for="diaAtualizarHoraInicio">Hora Inicial: <span class="text-danger">*</span></label>
                                    <input type="time" name="diaAtualizarHoraInicio" class="form-control" id="diaAtualizarHoraInicio">
                                </div>

                                <div class="col-md-2 input-container form-group diaHoraFim" style="height: 59px !important;">
                                    <label for="diaAtualizarHoraFim">Hora Final: <span class="text-danger">*</span></label>
                                    <input type="time" name="diaAtualizarHoraFim" class="form-control" id="diaAtualizarHoraFim">
                                </div>

                                <div class="col-md-4 input-container form-group btnTabela" style="align-items: end; height: 59px !important; display: flex; justify-content: flex-start; gap: 10px;">
                                    <button type="button" class="btn btn-primary" id='btnAddTabela'>Adicionar</button>
                                    <button type="button" class="btn btn-danger" id='btnLimparTabela'>Limpar Tabela</button>
                                </div>
                            </div>

                            <hr>

                        </div>

                        <div class="wrapperProgramacao">
                            <div id="tableProgramacao" class="ag-theme-alpine my-grid-detalhes-programacao" style="height: 350px">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="footer-group" style="justify-content: flex-end">
                            <button type="button" class="btn btn-success" id='btnSalvarRegra'>Salvar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DE VISUALIZAÇÃO DE ENVIO -->
<div id="visualizarEnvio" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <input type="hidden" name="idDetalhesEnvio" id="idDetalhesEnvio">
            <div class="modal-header header-layout">
                <div class="dropdown" style="margin-right: 10px;">
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleHistoricoEnvio">Detalhes de Envio</h3>
            </div>
            <div class="modal-body modal-bodyEnvio">
                <div class="col-lg-12">
                    <h4 class="subtitle" id="dadosEnvioModal" style="padding-left: 0; padding-right: 0;">Dados do Envio</h4>
                    <div class='row'>

                        <div class="col-lg-6 input-container form-group cliente" style="height: 59px !important;">
                            <label for="clienteEnvio">Cliente:</label>
                            <select class="form-control" type="text" name="clienteEnvio" id="clienteEnvio" placeholder="Selecione o Cliente..." style="width: 100%;">
                                <option value="">Selecione o Cliente</option>
                            </select>
                        </div>
                        <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                            <label for="idComando">ID Comando:</label>
                            <input class="form-control" type="text" name="idComando" id="idComando" placeholder="ID Comando..." />
                        </div>
                        <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                            <label for="serialEnvio">Serial:</label>
                            <input class="form-control" type="text" name="serialEnvio" id="serialEnvio" placeholder="Digite o serial..." />
                        </div>
                        <div class="col-lg-6 input-container form-group horaEnvio" style="height: 59px !important;">
                            <label for="horaEnvio">Hora de Envio:</label>
                            <input type="datetime-local" name="horaEnvio" class="form-control" id="horaEnvio">
                        </div>
                        <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                            <label for="statusModal">Status:</label>
                            <input class="form-control" type="text" name="statusModal" id="statusModal" placeholder="Status..." />
                        </div>
                        <div class="col-lg-6 input-container form-group" style="height: 59px !important;">
                            <label for="statusEnvioModal">Status do Envio:</label>
                            <input class="form-control" type="text" name="statusEnvioModal" id="statusEnvioModal" placeholder="Status do Envio..." />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ASSOCIAR FIRMWARE -->
<div id="associarFirmware" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="infoAssociarFirmware" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id='formAssociarFirmware'>
                <input type="hidden" name="idFirmwareAssociar" id="idFirmwareAssociar">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleAssociacao">Associar Firmware</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Associação Existente</h4>

                        <div class="col-md-12 input-container form-group hardware" style=" margin-left: -15px; height: 59px !important;">
                            <label for="firmwareAssociadoExistente">Firmware Associado: </label>
                            <input class="form-control" type="text" name="firmwareAssociadoExistente" id="firmwareAssociadoExistente" placeholder="Não há firmware Associados" autocomplete="off" disabled />
                        </div>

                        <hr id='quebraLinha'>

                        <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Dados para Associação</h4>
                        <div class='row'>

                            <div class="col-md-12 input-container form-group hardware" style="height: 59px !important;">
                                <label for="firmwareAssociar">Firmware: <span class="text-danger">*</span></label>
                                <select class="form-control" type="text" name="firmwareAssociar" id="idAssociacao" placeholder="Carregando Firmwares..." style="width: 100%;" required>
                                    <option value="" selected disabled>Selecione o Firmware</option>
                                </select>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group" style="justify-content: flex-end">
                        <button type="submit" class="btn btn-success" id='btnSalvarAssociacao'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>




<script>
    var Router = '<?= site_url('Firmware/Firmware') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'C.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/firmware', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/firmware', 'Exportacoes.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/firmware', 'Firmware.js') ?>"></script>