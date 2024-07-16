<style>
    .equal-height {
        display: table;
        table-layout: fixed;
        width: 100%;
    }

    .equal-height .form-group {
        display: table-row;
    }

    .acoes_botoes {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
</style>

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("envio_comandos_iscas_lote") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('iscas') ?> >
        <?= lang('comandos') ?> >
        <?= lang('envio_em_massa') ?>
    </h4>
</div>

<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-sm-12" id="conteudo">
        <div class="card-conteudo card-alertas-email" style='margin-bottom: 20px;'>
            <h3 class="col-sm-12">
                <?= lang('envio_comandos_iscas_lote') ?>
            </h3>
            <div>
                <div class="input-container form-group col-sm-6">
                    <label for="tipoComando" style="font-size: 16px !important; font-weight: bold;">Cliente <span id="load"></span></label>
                    <select class="form-control" name="cliente" id="cliente" style="width: 100%;" required>
                    </select>
                </div>
                <div class="input-container form-group col-sm-6" style="margin-bottom: 15px;">
                    <label for="tipoComando" style="font-size: 16px !important; font-weight: bold;">Comando</label>
                    <select class="form-control" name="tipoComando" id="tipoComando" required>
                        <option value="" selected disabled>Selecionar Comando</option>
                        <option value="CONFIG_CONEXAO">Configurar Conexão (Veicular)</option>
                        <option value="PARAM_ENVIO">Parâmetros de Envio - Rede Colaborativa</option>
                        <option value="START_REDE_COLAB">Iniciar Emergência</option>
                        <option value="STOP_REDE_COLAB">Parar Emergência</option>
                        <option value="SOLICITAR_ICCID">Solicitar ICCID</option>
                        <option value="SOLICITAR_CONFIG">Solicitar Configuração</option>
                        <option value="SOLICITAR_POSICAO">Solicitar Posição</option>
                        <option value="SOLICITAR_VERCAO_FIRMWARE">Solicitar Versão Firmware</option>
                    </select>
                </div>
                <div class="input-container form-group col-sm-12" id="checkboxesDispositivos">
                    <label for="tipoComando" style="font-size: 16px !important; font-weight: bold;">Dispositivos <span id="loadDisp"></span></label>
                    <select class="form-control" name="seriais" id="dispositivosId" data-placeholder="Selecione pelo menos um dispositivo" multiple="" required>
                    </select>
                </div>
                <div class="input-container form-group col-sm-6" id="checkboxesDispositivos">
                    <label for="arquivoItensComandos" style="font-size: 13px !important; font-weight: bold;">Carregar dispositivos via arquivo</label>
                    <div id="selecaoImportacao" style="display: flex; flex-wrap: wrap; margin-top: 5px;">
                        <input class="input-sm" style="border: 1px solid #a9a9a9; border-radius: 5px; flex: 1;" name="arquivoItensComandos" id="arquivoItensComandos" type="file" accept=".xls,.xlsx">
                        <div style="margin-top: 5px; margin-left: 10px;">
                            <i class="fa fa-info-circle" style="font-size: 18px;" id="info-icon-comandos" aria-hidden="true" title="Clique para saber mais"></i>
                        </div>
                    </div>
                </div>
                <div class="input-container form-group col-sm-6" id="checkboxesDispositivos">
                    <label for="selectAll" style="font-size: 13px !important; font-weight: bold;">Selecionar todos</label>
                    <div id="selecaoTodos" style="display: flex; flex-wrap: wrap; margin-top: 5px;">
                        <label class="switch">
                            <input type="checkbox" name="selectAll" id="selectAll">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- Comando Configurar Conexão -->
            <div id="CONFIG_CONEXAO" class="well" style="display: none; margin-right: 15px; margin-left: 15px;">
                <div class="equal-height">
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="auth">Autenticação</label>
                        <select class="form-control" id="auth" name="auth" required>
                            <option value="" selected disabled>Tipo de Autenticação</option>
                            <option value="0">Não (PAP)</option>
                            <option value="1">Sim (CHAP)</option>
                            <option value="A">Automático (GPRS)</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="apn">APN</label>
                        <input type="text" class="form-control" id="apn" name="apn" placeholder="APN">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="apn" class="col-form-label">Usuário</label>
                        <input type="text" class="form-control" id="user_id" name="user_id" placeholder="Usuário">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="password">Senha</label>
                        <input type="text" class="form-control" id="password" name="password" placeholder="Senha">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="apn">IP_Serv_1: </label>
                        <input type="text" class="form-control" id="server_ip_1" name="server_ip_1" placeholder="IP_Serv_1">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="apn">Porta_Serv_1: </label>
                        <input type="text" class="form-control" id="server_port_1" name="server_port_1" placeholder="Porta_Serv_1">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="server_ip_2">IP_Serv_2: </label>
                        <input type="text" class="form-control" id="server_ip_2" name="server_ip_2" placeholder="IP_Serv_2">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="server_port_2">Porta_Serv_2: </label>
                        <input type="text" class="form-control" id="server_port_2" name="server_port_2" placeholder="Porta_Serv_2">
                    </div>
                </div>
            </div>

            <div id="PARAM_ENVIO" class="well" style="display: none; margin-right: 15px; margin-left: 15px;">
                <div class="equal-height">
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="intervaloRX">Intervalo RX em modo normal(seg):</label>
                        <input type="text" class="form-control" name="intervaloRX" id="intervaloRX" value="10" disabled>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="periodoRXNormal">Período RX em modo normal(ms):</label>
                        <input type="text" class="form-control" name="periodoRXNormal" id="periodoRXNormal" value="300" disabled>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="intervaloGPRSNormal">Intervalo de envio GPRS em modo normal(min):</label>
                        <input type="number" class="form-control" name="intervaloGPRSNormal" min="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="intervaloGPRSNormal" value="10">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="numeroTransm">Numero de transmissões em emergência:</label>
                        <input type="text" class="form-control" name="numeroTransm" id="numeroTransm" value="1" disabled>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="intervaloTX">Intervalo TX em emergencia(seg):</label>
                        <input type="text" class="form-control" name="intervaloTX" id="intervaloTX" value="2" disabled>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="intervaloGPRSEmerg">Inter. de envio GPRS em modo emerg.(min):</label>
                        <input type="number" class="form-control" name="intervaloGPRSEmerg" min="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="intervaloGPRSEmerg" value="5">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="periodoRXEmerg">Periodo RX em emergência(ms):</label>
                        <input type="text" class="form-control" name="periodoRXEmerg" id="periodoRXEmerg" value="300" disabled>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="tempoSleep">Tempo para entrar em modo sleep(seg):</label>
                        <input type="number" class="form-control" name="tempoSleep" min="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="tempoSleep" value="60">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="label-equal-height" for="tempoJammer">Tempo para Confirmar Presença do Jammer(min):</label>
                        <input type="text" class="form-control" name="tempoJammer" id="tempoJammer" value="0" disabled>
                    </div>
                </div>
            </div>

            <div class="acoes_botoes col-sm-12">
                <button type="button" class="btn btn-default" id="limparDispositivosComandos" title="Limpar" style="margin-bottom: 5px;"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                <button type="button" class="btn btn-primary" id='enviarComando' title="Enviar Comando" style="margin-bottom: 5px;"><i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar</button>
            </div>
        </div>
    </div>
    <div id="tabelas" class="col-sm-12">
        <div class="card-conteudo" style='margin-bottom: 20px;'>
            <div class="col-sm-12">
                <h3>
                    <?= lang('resposta_comando') ?>
                    <div class="btn-div-responsive" id="btn-div">
                        <div class="dropdown" style="margin-right: 10px;">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                                <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
                            </div>
                        </div>
                    </div>
                </h3>
                <div class="registrosDiv">
                    <select id="select-quantidade-por-pagina-comandos" class="form-control" style="float: left; width: auto; height: 34px;">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
                </div>
                <div style="position: relative;">
                    <div id="loadingMessageComandos" class="loadingMessage" style="display: none;">
                        <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                    </div>
                    <div class="wrapperComandos">
                        <div id="tableComandos" class="ag-theme-alpine my-grid-comandos" style="height: 500px">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- MODAL MODELO -->
<div id="modalModeloItens" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="header-modal">Modelo de documento <span id="tituloDetalhesDoContrato"></span></h3>
            </div>
            <div class="modal-body scrollModal">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tab-content" style="padding: 0px 20px">
                            <div id="div_identificacao">
                                <div class="row">
                                    <div class="col-md-12" style="border-left: 3px solid #03A9F4; padding-bottom: 0px; margin-right: 0px">
                                        <p class="text-justify">
                                            A planilha deve conter a seguinte coluna:
                                            <ul>
                                             <li><strong>Dispositivo</strong> (obrigatória e representa o serial do dispositivo)</li>
                                            </ul>
                                            Formatos suportados: .xls e .xlsx.
                                        </p>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <img src="<?= versionFile('arq/iscas/comandos', 'modelo-dispositivo.png') ?>" alt="" class="img-responsive center-block" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                <button id="btnBaixarModelo" class="btn btn-primary" type="button" onclick="baixarModeloItens()">Baixar Modelo</button>
                <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= base_url("media/js/helpers/iscas-helper.js") ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/iscas/comandos', 'RespostaComando.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/iscas/comandos', 'EnvioMassaModal.js') ?>"></script>

<script>
    var Router = '<?= site_url('monitoramento_iscas') ?>';
    var BaseURL = '<?= base_url('') ?>';
    var SiteURL = '<?= site_url('') ?>';
</script>