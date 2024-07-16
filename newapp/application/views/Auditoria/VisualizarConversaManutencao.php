<div class="modal-body scrollModal">

    <ul id="navs" class="nav nav-tabs">
        <li class="nav-item"><a class="nav-item active" href="#tab_conversas" data-toggle="tab" id="tab-conversas">Conversas</a></li>
        <li class="nav-item"><a class="nav-item" href="#tab_agenda" data-toggle="tab" id="tab-agenda" style="display: none !important;">Dados do Agendamento</a></li>
        <li class="nav-item"><a class="nav-item" href="#tab_sms" data-toggle="tab" id="tab-sms" style="display: none !important;">Dados do Mantenedor</a></li>
        <!-- <li class="nav-item"><a class="nav-item" href="#tab_audit_track" data-toggle="tab" id="tab-audit_track" style="display: none !important;" >Audit Track</a></li>  -->
    </ul>

    <span class="help help-block"></span>
    <div class="container-fluid my-1">
        <div class="col-sm-12">
            <div class="tab-content" style="padding: 0 10px;">
                <div id="tab_conversas">
                    <div class="row">
                        <div id="alertMensagens" class=' col-md-12' style="display: none !important;">
                            <div class="alert alert-danger col-md-12">
                                <p>Mensagens não encontradas</p>
                            </div>
                            <span class="help help-block"></span>
                            <span class="help help-block"></span>
                            <span class="help help-block"></span>
                        </div>
                        <div class="chat-container col-md-12" style="background-color: #f7f7f7;">
                            <ul id="listaMensagens">
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="tab_agenda">
                    <div class="row">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <h4>Dados Cliente: </h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 input-container" >
                                <label>Cliente: </label>
                                <input type="text" class="form-control" id="ClienteAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label>Celular: </label>
                                <input type="text" class="form-control" id="CelularAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label>Email: </label>
                                <input type="text" class="form-control" id="EmailAgendamento" disabled>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-4 input-container">
                                <label>Documento: </label>
                                <input type="text" class="form-control" id="DocumentoAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label>Serial: </label>
                                <input type="text" class="form-control" id="SerialAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label>Qtd: </label>
                                <input type="text" class="form-control" id="QtdAgendamento" disabled>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-4 input-container">
                                <label>Cep: </label>
                                <input type="text" class="form-control" id="CepAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label>Logradouro: </label>
                                <input type="text" class="form-control" id="LogradouroAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label>Numero: </label>
                                <input type="text" class="form-control" id="NumeroAgendamento" disabled>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-4 input-container">
                                <label>Complemento: </label>
                                <input type="text" class="form-control" id="ComplementoAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label>Bairro: </label>
                                <input type="text" class="form-control" id="BairroAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label>Cidade: </label>
                                <input type="text" class="form-control" id="CidadeAgendamento" disabled>
                            </div>
                        </div>

                        <div class="row " style="margin-top: 15px;">
                            <div class="col-md-4 input-container">
                                <label>Estado: </label>
                                <input type="text" class="form-control" id="EstadoAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label>Data Solicitação: </label>
                                <input type="text" class="form-control" id="DataSolicitacaoAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label>Data/Hora Manutenção: </label>
                                <input type="text" class="form-control" id="DataHoraInstalacaoAgendamento" disabled>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-4 input-container">
                                <label>Status: </label>
                                <input type="text" class="form-control" id="Status" disabled>
                            </div>
                        </div>

                        <span class="help help-block"></span>

                        <div class="row">
                            <div class="col-md-12 input-container">
                                <h4>Dados Manutenção: </h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 input-container">
                                <label>NA: </label>
                                <input type="text" class="form-control" id="NAAgendamento" disabled>
                            </div>

                            <div class="col-md-6 input-container">
                                <label>Telefone Mantenedor: </label>
                                <input type="text" class="form-control" id="TelefoneMantenedorAgendamento" disabled>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-12 input-container">
                                <label>Técnico Mantenedor: </label>
                                <input type="text" class="form-control" id="MantenedorAgendamento" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tab_sms">
                    <div class="container-fluid" style="display: flex; flex-direction: column;">
                        <div>
                            <input type="text" id="search-input-sms" placeholder="Buscar" style="float: right; margin: 10px; height:30px; padding-left: 10px;">
                        </div>
                        <div class="wrapperSms">
                            <div id="tableSms" class="ag-theme-alpine my-grid-sms" style="height: 500px">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/agendamento', 'layout.css') ?>">

