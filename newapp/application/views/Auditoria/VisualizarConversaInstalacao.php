<div class="modal-body scrollModal">

    <ul id="navs" class="nav nav-tabs">
        <li class="nav-item"><a class="nav-item active" href="#tab_conversas" data-toggle="tab" id="tab-conversas">Conversas</a></li>
        <li class="nav-item"><a class="nav-item" href="#tab_agenda" data-toggle="tab" id="tab-agenda" style="display: none !important;">Dados do Agendamento</a></li>
        <li class="nav-item"><a class="nav-item" href="#tab_sms" data-toggle="tab" id="tab-sms" style="display: none !important;">Dados do Instalador</a></li>
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
                        <div class="jumbotron col-md-12">
                            <ul id="listaMensagens">
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="tab_agenda">
                    <div class="row">
                        <div class="row">
                            <div class="col-md-12 input-container">
                                <h4>Dados Cliente: </h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 input-container">
                                <label class="control-label">Cliente: </label>
                                <input type="text" class="form-control" id="ClienteAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label class="control-label">Celular: </label>
                                <input type="text" class="form-control" id="CelularAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label class="control-label">Email: </label>
                                <input type="text" class="form-control" id="EmailAgendamento" disabled>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-4 input-container">
                                <label class="control-label">Documento: </label>
                                <input type="text" class="form-control" id="DocumentoAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label class="control-label">Serial: </label>
                                <input type="text" class="form-control" id="SerialAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label class="control-label">Qtd: </label>
                                <input type="text" class="form-control" id="QtdAgendamento" disabled>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-4 input-container">
                                <label class="control-label">Cep: </label>
                                <input type="text" class="form-control" id="CepAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label class="control-label">Logradouro: </label>
                                <input type="text" class="form-control" id="LogradouroAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label class="control-label">Numero: </label>
                                <input type="text" class="form-control" id="NumeroAgendamento" disabled>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-4 input-container">
                                <label class="control-label">Complemento: </label>
                                <input type="text" class="form-control" id="ComplementoAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label class="control-label">Bairro: </label>
                                <input type="text" class="form-control" id="BairroAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label class="control-label">Cidade: </label>
                                <input type="text" class="form-control" id="CidadeAgendamento" disabled>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-4 input-container">
                                <label class="control-label">Estado: </label>
                                <input type="text" class="form-control" id="EstadoAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label class="control-label">Data Solicitação: </label>
                                <input type="text" class="form-control" id="DataSolicitacaoAgendamento" disabled>
                            </div>
                            <div class="col-md-4 input-container">
                                <label class="control-label">Data/Hora Instalação: </label>
                                <input type="text" class="form-control" id="DataHoraInstalacaoAgendamento" disabled>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-4 input-container">
                                <label class="control-label">Status: </label>
                                <input type="text" class="form-control" id="Status" disabled>
                            </div>
                        </div>

                        <span class="help help-block"></span>

                        <div class="row">
                            <div class="col-md-12 input-container">
                                <h4>Dados Instalação: </h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 input-container">
                                <label class="control-label">NA: </label>
                                <input type="text" class="form-control" id="NAAgendamento" disabled>
                            </div>

                            <div class="col-md-6 input-container">
                                <label class="control-label">Telefone Instalador: </label>
                                <input type="text" class="form-control" id="TelefoneInstaladadorAgendamento" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 input-container">
                                <label class="control-label">Técnico Instalador: </label>
                                <input type="text" class="form-control" id="InstaladorAgendamento" disabled>
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