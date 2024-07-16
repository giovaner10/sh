<style>
    .bord {
        border-left: 3px solid #03A9F4;
    }

    #loading {
        display: none;
        z-index: 9999;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
    }



    #loading::after {
        content: attr(data-content);
        color: white;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation: fade 2s infinite;
    }

    @keyframes fade {
        0% {
            opacity: 0;
        }

        50% {
            opacity: 1;
        }

        100% {
            opacity: 0;
        }
    }


    #tabelaContratos th:nth-child(1),
    #tabelaContratos td:nth-child(1) {
        width: 20%;
    }

    #tabelaContratos th:nth-child(2),
    #tabelaContratos td:nth-child(2) {
        width: 20%;
    }

    #tabelaContratos th:nth-child(3),
    #tabelaContratos td:nth-child(3) {
        width: 10%;
    }

    #tabelaContratos th:nth-child(4),
    #tabelaContratos td:nth-child(4) {
        width: 15%;
    }

    #tabelaContratos th:nth-child(5),
    #tabelaContratos td:nth-child(5) {
        width: 10%;
    }

    #tabelaContratos th:nth-child(6),
    #tabelaContratos td:nth-child(6) {
        width: 15%;
    }

    #tabelaContratos th:nth-child(7),
    #tabelaContratos td:nth-child(7) {
        width: 10%;
    }

    .input-container {
        margin: 5px 0px;
    }

    .input-container label {
        color: #7F7F7F !important;
        font-size: 14px !important;
        font-weight: lighter !important;
    }

    .header-layout {
        border-bottom: 2px solid #e5e5e5;
        margin-top: 0.8rem
    }

    h3.modal-title {
        color: #1C69AD !important;
        font-size: 22px !important;
        display: flex;
        justify-content: space-between;
    }

    .modal-content {
        border-radius: 25px;
        gap: 25px;
    }

    .footer-group {
        display: flex;
        justify-content: space-between;
        width: 100%;
        padding: 0 15px;
    }

    .footer-group .btn {
        border-radius: 7px !important;
    }

    .footer-group .btn:hover {
        border-radius: 7px !important;
    }

    .footer-group .btn-primary {
        background-color: #007BFF !important;
    }

    .footer-group .btn-primary:hover {
        background-color: #007bffcb !important;
    }

    .subtitle {
        color: #1C69AD !important;
        font-size: 20px !important;
        font-weight: bold !important;
    }

    .close {
        border-radius: 25px;
        background-color: #e5e5e5 !important;
        width: 30px;
        height: 30px;
        color: #7F7F7F;
        font-size: 32px;
    }
</style>

<!-- Modal Detalhes do Contrato -->
<div id="modalDetalhesDoContrato" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-x-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Contrato <span id="tituloDetalhesDoContrato"></span></h3>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <h3>Dados do Contrato</h3>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12" style="margin: 1.5rem 3rem;">
                        <div class="col-md-3 bord">
                            <label for="">Cod.</label>
                            <h4 id="codeDetalhesDoContrato">-</h4>
                        </div>
                        <div class="col-md-3 bord">
                            <label for="">Status do Item de Contrato</label>
                            <h4 id="statusDetalhesDoContrato">-</h4>
                        </div>
                        <div class="col-md-3 bord">
                            <label for="">Data de Ativação</label>
                            <h4 id="activationDateDetalhesDoContrato">-</h4>
                        </div>
                        <div class="col-md-3 bord">
                            <label for="">Data Fim</label>
                            <h4 id="endDateDetalhesDoContrato">-</h4>
                        </div>
                    </div>
                    <div class="col-md-12 m-2" style="margin: 1.5rem 3rem;">
                        <div class="col-md-3 bord">
                            <label for="">Tecnologia</label>
                            <h4 id="technologyDetalhesDoContrato">-</h4>
                        </div>
                        <div class="col-md-3 bord">
                            <label for="">Plano</label>
                            <h4 id="trackerPlanDetalhesDoContrato">-</h4>
                        </div>
                        <div class="col-md-3 bord">
                            <label for="">Serial</label>
                            <h4 id="trackerSerialNumberDetalhesDoContrato">-</h4>
                        </div>
                        <div class="col-md-3 bord">
                            <label for="">Veículo</label>
                            <h4 id="vehicleLicenseNumberDetalhesDoContrato">-</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h3>Itens</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table-responsive table-bordered table " id="tableItensDoContrato">
                            <thead>
                                <th>Cod.</th>
                                <th>Total</th>
                                <th>Qtd.</th>
                                <th>Início</th>
                                <th>Fim</th>
                                <th>Classificação</th>
                                <th>Receita</th>
                                <th>Serviço</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>

        </div>
    </div>
</div>

<!-- Modal Ocorrência -->
<div id="modalOcorrencia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel" class="myModalLabel">Cadastrar Ocorrência</h3>
            </div>
            <form id="form_ocorrencia" method="POST">
                <input type="hidden" name="Id" id="Id" type="text">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Assunto</label>
                                    <select name="Assunto" id="Assunto" class="form-control param selectPesquisar" data-placeholder="Selecione um assunto" style="width: 100%;" required>
                                        <option value="0" disabled selected>Selecione um assunto</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Tipo de ocorrência</label>
                                    <select name="TipoOcorrencia" id="TipoOcorrencia" class="form-control param selectPesquisar" data-placeholder="Selecione um tipo de ocorrência" style="width:100%" required>
                                        <option value="0" disabled selected>Selecione um tipo de ocorrência</option>
                                        <option value="1">Pergunta</option>
                                        <option value="2">Problema</option>
                                        <option value="3">Solicitação</option>
                                        <option value="4">Sugestão</option>
                                        <option value="5">Elogio</option>
                                        <option value="6">Sistema</option>
                                        <option value="200000">Reclamação</option>
                                        <option value="200001">Demanda Judicial/Extra judicial</option>
                                        <option value="100000000">Orientação</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Origem da ocorrência</label>
                                    <select name="OrigemOcorrencia" id="OrigemOcorrencia" class="form-control param selectPesquisar" data-placeholder="Selecione a origem da ocorrência" style="width:100%" required>
                                        <option value="0" disabled selected>Selecione a origem da ocorrência</option>
                                        <option value="1">Telefone</option>
                                        <option value="2">Email</option>
                                        <option value="3">Web</option>
                                        <option value="4">Sistema</option>
                                        <option value="5">Rechamada</option>
                                        <option value="10">Pesquisa de Satisfação</option>
                                        <option value="11">WhatsApp</option>
                                        <option value="12">Portal do Cliente</option>
                                        <option value="13">Ura</option>
                                        <option value="2483">Facebook</option>
                                        <option value="3986">Twitter</option>
                                        <option value="200000">Reclame Aqui</option>
                                        <option value="200001">Procon</option>
                                        <option value="200002">Ação Judicial</option>
                                        <option value="200003">Fale Conosco</option>
                                        <option value="200004">Retorno da Régua</option>
                                        <option value="100000000">Confissão de Dívida</option>
                                        <option value="100000001">Gestor</option>
                                        <option value="100000002">Chat</option>
                                        <option value="419400000">Tratamento de Eventos</option>
                                        <option value="419400001">Comercial</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Tecnologia</label>
                                    <select name="Tecnologia" id="Tecnologia" class="form-control param selectPesquisar" data-placeholder="Selecione a tecnologia" style="width:100%" required>
                                        <option value="0" disabled selected>Selecione uma tecnologia</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Descrição</label><br>
                                    <textarea id="Descricao" name="Descricao" class="param form-control" rows="6" autocomplete="off" style="margin: 0px; width: 567px; height: 125px;resize: none">Analista:&#10;Assunto / Problema:&#10;Nome/Telefone do solicitante:&#10;Realizado:&#10;Pendencia:&#10;Observações:&#10;ID da conversa:</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" id="btnSubmit" type="submit">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="novo_ticket" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form action="" id='ContactForm'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleBlacklist">Adicionar Ticket Fale Conosco</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class='row'>
                            <div class="col-md-12 input-container form-group">
                                <label for="id_usuario">Usuário: <span class="text-danger">*</span></label>
                                <select class="select_usuario form-control" name="id_usuario" data-placeholder="Selecione um Usuário" style="width: 100% !important;" id="id_usuario" required>
                                    <option value="" disabled selected></option>
                                </select>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="placa">Placa: <span class="text-danger">*</span></label>
                                <select id="placa" class="form-control" data-placeholder="Selecione a placa" name="placa" style="width: 100% !important;" required readonly>
                                    <option value="" disabled selected>Selecione uma placa</option>
                                </select>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="assunto">Assunto: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="assunto" id="assunto" placeholder="Assunto" style="width: 100% !important;" autocomplete="off" maxlength="100" required />
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="departamento">Categoria: <span class="text-danger">*</span></label>
                                <select id="departamento" class="form-control departamento" style="width: 100% !important;" name="departamento" required>
                                    <option value="" disabled selected>Selecione a Categoria</option>
                                    <option value="Suporte Técnico">Suporte Técnico</option>
                                    <option value="Atendimento ao Cliente">Atendimento ao Cliente</option>
                                    <option value="Financeiro / Cobrança">Financeiro</option>
                                    <option value="Vendas / Novos négocios">Vendas</option>
                                </select>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="prioridade">Prioridade: <span class="text-danger">*</span></label>
                                <select type="text" id="prioridade" name="prioridade" data-placeholder="Prioridade" class="form-control" style="width: 100% !important;" required>
                                    <option value="" disabled selected>Prioridade</option>
                                    <option value="1">Baixa</option>
                                    <option value="2">Média</option>
                                    <option value="3">Alta</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <div class="msg_caracter"></div>
                            </div>
                            <input type="hidden" name="id_cliente" id="input_id_cliente">
                            <input type="hidden" name="usuario" id="input_usuario">
                            <input type="hidden" name="nome_usuario" id="input_nome_usuario">

                            <div class="col-md-12 input-container form-group">
                                <label for="descricao">Descrição: <span class="text-danger">*</span></label>
                                <textarea name="descricao" rows="6" placeholder=" Descrição" id="descricao" class="form-control maxlength" style="resize: vertical;" required></textarea>
                                <span class="label" id="content-countdown" style="float:right; color:black;" title="500">0</span>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <span>Arquivo:</span>
                                <input type="file" name="arquivo" id="arquivo" class="form-control" style="word-wrap: break-word; max-width: 100%;">
                                <span class="help-block" style="font-size: 11px;">*Formatos suportados: pdf, jpg, png e jpeg</span>
                            </div>
                        </div>

                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id='btnSalvarTicket'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal funções programáveis -->
<div id="funcProgramaveis" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleFuncoes">Funções Programáveis</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="display table-responsive table-bordered table" id="tableFuncoesProgamaveisModal">
                                    <thead>
                                        <th>ID</th>
                                        <th>Descrição</th>
                                        <th>Data de Alteração</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Ocorrência -->
<div id="modalOcorrenciaEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Editar Ocorrência</h3>
            </div>
            <form id="form_ocorrenciaEdit" method="POST">
                <input type="hidden" name="Id" class="param2" id="IdEdit" name="Id" type="text">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Número do ticket:</label>
                                    <input name="TicketNumber" type="text" class="form-control param2" id="TicketNumberEdit" maxlength="255" />
                                </div>
                            </div>
                            <div id="assunto-row" class="row">
                                <div class="col-md-12 form-group">
                                    <label>Assunto:</label>
                                    <select name="Assunto" id="AssuntoEdit" class="form-control param2 selectPesquisar" data-placeholder="Selecione um assunto" style="width:100%">
                                        <option value="0" disabled selected>Selecione um assunto</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Tipo de ocorrência:</label>
                                    <select name="TipoOcorrencia" id="TipoOcorrenciaEdit" class="form-control param2 selectPesquisar" data-placeholder="Selecione um tipo de ocorrência" style="width:100%" required>
                                        <option disabled value="0">Selecione o tipo de ocorrência</option>
                                        <option value="14">Implantação</option>
                                        <option value="13">Reunião</option>
                                        <option value="12">Dúvidas Operacionais</option>
                                        <option value="9">Corretiva Aplicação</option>
                                        <option value="10">Backup</option>
                                        <option value="11">Treinamento</option>
                                        <option value="7">Preventiva</option>
                                        <option value="8">Corretiva Banco de Dados</option>
                                        <option value="1">Pergunta</option>
                                        <option value="2">Problema</option>
                                        <option value="3">Solicitação</option>
                                        <option value="200000">Reclamação</option>
                                        <option value="200001">Demanda Judicial/ Extra judicial</option>
                                        <option value="4">Sugestão</option>
                                        <option value="16">Incidente</option>
                                        <option value="5">Elogio</option>
                                        <option value="6">Sistema</option>
                                        <option value="100000000">Orientação</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Origem da ocorrência:</label>
                                    <select name="OrigemOcorrencia" id="OrigemOcorrenciaEdit" class="form-control param2 selectPesquisar" data-placeholder="Selecione a origem da ocorrência" style="width:100%" required>
                                        <option disabled value="0">Selecione a origem de ocorrência</option>
                                        <option value="419400011">Atendimento Remoto</option>
                                        <option value="419400010">Atendimento Local</option>
                                        <option value="200002">Ação Judicial</option>
                                        <option value="419400001">Ferramenta do Cliente</option>
                                        <option value="2">Email</option>
                                        <option value="419400009">Comercial</option>
                                        <option value="100000000">Confissão de Dívida</option>
                                        <option value="200003">Fale Conosco</option>
                                        <option value="2483">Facebook</option>
                                        <option value="10">Pesquisa de Satisfação</option>
                                        <option value="12">Portal do Cliente</option>
                                        <option value="200001">Procon</option>
                                        <option value="5">Rechamada</option>
                                        <option value="200000">Reclame Aqui</option>
                                        <option value="200004">Retorno da Régua</option>
                                        <option value="4">Sistema</option>
                                        <option value="1">Telefone</option>
                                        <option value="419400000">Tratamento de Eventos</option>
                                        <option value="3986">Twitter</option>
                                        <option value="13">Ura</option>
                                        <option value="100000001">Gestor</option>
                                        <option value="100000002">Chat</option>
                                        <option value="100000003">Google</option>
                                        <option value="100000004">Instagram</option>
                                        <option value="100000005">Google Avaliação</option>
                                        <option value="100000006">Instagram Feed</option>
                                        <option value="100000007">Facebook Feed</option>
                                        <option value="3">Web</option>
                                        <option value="11">WhatsApp</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Tecnologia</label>
                                    <select name="Tecnologia" id="TecnologiaEdit" class="form-control param2 selectPesquisar" data-placeholder="Selecione a tecnologia" style="width:100%" required>
                                        <option value="0" disabled selected>Selecione a tecnologia</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <input id="box-fila" type="checkbox" style="height: 12px !important;" /> <STRONG style="padding-bottom: 20%;"> Alterar fila </STRONG></input>
                                    <div class="collapse" id="filas-row" style="padding: 2%;">
                                        <p>NOTA: Se a fila for alterada, o assunto NÃO será alterado.
                                            Como uma fila está atrelada a um assunto,
                                            ele irá sobrepor a fila selecionada abaixo,
                                            então ao alterar a fila NÃO será alterado o assunto e vice-versa.</p>
                                        <div class="row">
                                            <label>Filas</label>
                                            <select name="filas" id="filas" class="form-control param filas" style="width:100%">
                                            </select>
                                            <input type="hidden" id="fila-nome" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Descrição</label><br>
                                    <textarea id="DescricaoEdit" name="Descricao" class="form-control param2" rows="6" autocomplete="off" style="margin: 0px; width: 567px; height: 125px;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-2">
                        <button id="btn-anotacao-ocorrencia" class="btn btn-primary" onclick="modalAnotacoes(this)" type="submit">Anotações</button>
                    </div>
                    <div class="col-md-10">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                        <button class="btn btn-primary" id="btnSubmitEdit" type="submit">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Contrato Atividades de Serviço -->
<div id="modalContratoAtividadesDeServico" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalContratoAtividadesDeServicoLabel" aria-hidden="true" style="overflow-y:auto;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Contrato <span id="tituloContratoModalAtividadesDeServico"></span></h3>
            </div>
            <div class="modal-body">
                <!-- IDENTIFICAÇÃO -->
                <div class="row">
                    <div class="col-md-12">
                        <h4>Identificação</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Código</label>
                        <h5 id="codeContratoNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Número da AF</label>
                        <h5 id="numeroAfNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Modelo</label>
                        <h5 id="modeloTipoInformadoAtivacaoNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Código Item do Contrato</label>
                        <h5 id="codigoItemContratoNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Status Item do Contrato</label>
                        <h5 id="statusItemContratoNA"></h5>
                    </div>
                </div>
                <!-- VEÍCULO -->
                <div class="row">
                    <div class="col-md-12">
                        <h5>Veículo</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Placa</label>
                        <h5 id="placaVeiculoNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Serial</label>
                        <h5 id="serialVeiculoNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Chassi</label>
                        <h5 id="chassiVeiculoNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Renavam</label>
                        <h5 id="renavamVeiculoNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Modelo Tipo Ativação</label>
                        <h5 id="modeloVeiculoNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Tipo Veículo</label>
                        <h5 id="tipoVeiculoNA"></h5>
                    </div>
                </div>
                <!-- VENDA -->
                <div class="row">
                    <div class="col-md-12">
                        <h5>Venda</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Integra Contrato</label>
                        <h5 id="integraContratoVendaNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Reenviar Apolice Graber</label>
                        <h5 id="reenviarApoliceGraberVendaNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Canal de Venda</label>
                        <h5 id="canalVendaNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Cenário de Venda</label>
                        <h5 id="cenarioVendaNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Tecnologia</label>
                        <h5 id="tecnologiaVendaNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Frota AF</label>
                        <h5 id="afVendaNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Valor da Mensalidade</label>
                        <h5 id="valorLicencaBaseVendaNA"></h5>
                    </div>
                </div>
                <!-- PRODUTO -->
                <div class="row">
                    <div class="col-md-12">
                        <h5>Produto</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Plano</label>
                        <h5 id="planoProdutoNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Rastreador</label>
                        <h5 id="rastreadorProdutoNA"></h5>
                    </div>
                </div>
                <!-- VALORES -->
                <div class="row">
                    <div class="col-md-12">
                        <h5>Valores</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Valor Deslocamento</label>
                        <h5 id="valorDeslocamentoNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Taxa Visita</label>
                        <h5 id="taxaVisitaNA"></h5>
                    </div>
                </div>
                <!-- DATAS -->
                <div class="row">
                    <div class="col-md-12">
                        <h5>Datas</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Data Ativação</label>
                        <h5 id="dataAtivacaoNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Data Aniversário Contrato</label>
                        <h5 id="dataAniversarioComodatoNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Data Vencimento Contrato</label>
                        <h5 id="dataVencimentoComodatoNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Data Término Fidelidade</label>
                        <h5 id="dataTerminoFidelidadeNA"></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table-responsive table-bordered table " id="tableOrdensServico">
                            <thead>
                                <th>Nº O.S.</th>
                                <th>Estado</th>
                                <th>Status</th>
                                <th>Tipo De Serviço</th>
                                <th>Observação</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>

        </div>
    </div>
</div>

<!-- Modal Ocorrência Atividades de Serviço -->
<div id="modalOcorrenciaAtividadesDeServico" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalOcorrenciaAtividadesDeServicoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Ocorrência <span id="tituloOcorrenciaModalAtividadesDeServico"></span></h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Ticket</label>
                        <h5 id="ticketnumberOcorrenciaNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Fila Atual</label>
                        <h5 id="filaAtualOcorrenciaNA"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Data Criação</label>
                        <h5 id="dataCriacaoOcorrenciaNA"></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="">Titulo</label>
                        <h5 id="tituloOcorrenciaNA"></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="">Descrição</label>
                        <h5 id="descricaoOcorrenciaNA"></h5>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div style="display: none;" class="statusOcorrenciaAuxDiv">
    <select name="statusOcorrenciaAux" class="statusOcorrenciaAux">
        <option value="1">Em Andamento</option>
        <option value="2">Suspenso</option>
        <option value="3">Aguardando Cliente</option>
        <option value="4">Pesquisando</option>
        <option value="5">Problema Resolvido</option>
        <option value="6">Cancelada</option>
        <option value="1000">Informações Fornecidas</option>
        <option value="2000">Mesclado</option>
        <option value="419400000">Cancelada - Logística Reversa</option>
        <option value="419400001">Enviado para operadora</option>
        <option value="419400002">Inconsistência nas informações</option>
        <option value="419400003">Ativação da(s) linha(s) concluída</option>
        <option value="419400004">Novo Contrato</option>
        <option value="419400005">Pendente Instalação / Manutenção</option>
        <option value="419400007">Implantação concluída</option>
        <option value="419400008">Não iniciado</option>
        <option value="419400009">Aguardando Operações</option>
        <option value="419400010">Aguardando Peças</option>
        <option value="419400011">Aguardando Comercial</option>
        <option value="419400012">Aguardando Outras Equipes</option>
        <option value="419400013">Falta de Informação</option>
        <option value="419400014">Não resolvido</option>
        <option value="419400015">Resolvido com pendências do dliente</option>
        <option value="419400016">Cancelado pelo cliente</option>
    </select>
</div>

<!-- Modal Buscar Contrato -->
<div id="modalBuscarContrato" class="modal fade" style="overflow-y: auto !important" tabindex="-1" role="dialog" aria-labelledby="myModalBuscarContratoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Buscar Contrato</h3>
            </div>
            <div class="modal-body">
                <div class="form-inline margin_bottom_20">
                </div>
                <label for="filtro" style="font-size: medium; margin-right: 10px" id="labelTipoBuscarContrato">Tipo de Busca</label>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <select class="form-control" name="tipoBuscaContrato" id="tipoBuscaContrato">
                            <option value="serial">Serial</option>
                            <option value="contrato">Codigo</option>
                            <option value="placa">Placa</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <select name="serialBuscarContrato" id="serialBuscarContrato" class="form-control"></select>
                        <input name="codigoBuscarContrato" id="codigoBuscarContrato" class="form-control" placeholder="Digite o código do contrato" style="display: none;width:100%">
                        <select class="form-control" name="placaBuscarContrato" id="placaBuscarContrato"></select>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary" id="btnBuscarContrato" aria-hidden="true">Buscar</button>
                    </div>
                    <div class="col-md-8">
                        <h5 style="display: inline-block; font-weight: bold;">Buscar apenas contratos ativos</h5>
                        <input id="switch-contrato-ativo" title="Buscar apenas contratos ativos" type="checkbox" style="display: inline-block;height: 11px !important">

                    </div>
                </div>
                <input type="hidden" id="idContratoModal" />
                <div class="row" id="rowInfoContrato" hidden>
                    <div class="row col-md-12">
                        <div class="col-md-4">
                            <label for="">Código</label>
                            <h5 id="codigoModalBuscarContrato"></h5>
                        </div>
                        <div class="col-md-4">
                            <label for="">Canal de Venda</label>
                            <h5 id="canalVendaModalBuscarContrato"></h5>
                        </div>
                        <div class="col-md-4">
                            <label for="">Cenário de Venda</label>
                            <h5 id="cenarioVendaModalBuscarContrato"></h5>
                        </div>
                    </div>

                    <div class="row col-md-12">
                        <div class="col-md-4">
                            <label for="">Placa</label>
                            <h5 id="placaModalBuscarContrato"></h5>
                        </div>
                        <div class="col-md-4">
                            <label for="">Plano</label>
                            <h5 id="planoModalBuscarContrato"></h5>
                        </div>
                        <div class="col-md-4">
                            <label for="">Rastreador</label>
                            <h5 id="rastreadorModalBuscarContrato"></h5>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="col-md-4">
                            <label for="">Serial</label>
                            <h5 id="serialModalBuscarContrato"></h5>
                        </div>
                        <div class="col-md-4">
                            <label for="">Descrição</label>
                            <h5 id="descricaoModalBuscarContrato"></h5>
                        </div>
                        <div class="col-md-4">
                            <label for="">Número AF</label>
                            <h5 id="numeroAfModalBuscarContrato"></h5>
                        </div>
                    </div>

                    <div class="row col-md-12">
                        <div class="col-md-4">
                            <label for="">Tecnologia</label>
                            <h5 id="tecnologiaModalBuscarContrato"></h5>
                        </div>
                        <div class="col-md-4">
                            <label for="">Valor Licença Base</label>
                            <h5 id="valorLicencaBaseModalBuscarContrato"></h5>
                        </div>
                        <div class="col-md-4">
                            <label for="">Valor Total Serviços Ativos</label>
                            <h5 id="valorTotalServicosAtivosModalBuscarContrato"></h5>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="col-md-4">
                            <label for="">Cliente</label>
                            <h5 id="nomeClienteModalBuscarContrato"></h5>
                        </div>
                        <div class="col-md-4">
                            <label for="">Nome Fantasia</label>
                            <h5 id="nomeFantasiaClienteModalBuscarContrato"></h5>
                        </div>
                        <div class="col-md-4">
                            <label for="">CNPJ / CPF</label>
                            <h5 id="cnpjCpfClienteModalBuscarContrato"></h5>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="col-md-6">
                            <label for="">E-mail</label>
                            <h5 id="emailClienteModalBuscarContrato"></h5>
                        </div>
                        <div class="col-md-3">
                            <label for="">Data de Entrada</label>
                            <h5 id="dataEntradaModalBuscarContrato"></h5>
                        </div>
                        <div class="col-md-3">
                            <label for="">Data Ativação</label>
                            <h5 id="dataAtivacaoModalBuscarContrato"></h5>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="col-md-6">
                            <label for="">Status</label>
                            <h5 id="statusModalBuscarContrato"></h5>
                        </div>
                        <div class="col-md-6">
                            <label for="">Motivo da Alteração</label>
                            <h5 id="motivoAlteracaoModalBuscarContrato"></h5>
                        </div>
                    </div>
                </div>
                <hr>
                <div id="abas-ic" style="display: none">
                    <ul class="nav nav-tabs">
                        <li><a id="os-ic" class="nav-item">Atividades de Serviço</a></li>
                        <li><a id="servicos-ic" class="nav-item">Serviços Contratados</a></li>
                    </ul>
                </div>
                <script>
                    $("#os-ic").click(function(e) {
                        e.preventDefault();
                        $(this).attr('style', 'color: #337ab7 !important')
                        $("#servicos-ic").attr('style', 'color: black !important')

                        $("#os-content").show();
                        $("#atividade-servico-content").hide();

                    })
                    $("#servicos-ic").click(function(e) {
                        e.preventDefault();
                        $(this).attr('style', 'color: #337ab7 !important')
                        $("#os-ic").attr('style', 'color: black !important')

                        $("#os-content").hide();
                        $("#atividade-servico-content").show();

                    })
                </script>
                <style>
                    #tableAtividadesDeServicoIC {
                        font-size: 0.9em !important;
                    }

                    #tableServicosContratadosBusca {
                        font-size: 0.9em !important;
                    }
                </style>

                <div id="os-content" style="display: none;">
                    <div class="col-md-12 table-responsive">
                        <table class="table-bordered table" id="tableAtividadesDeServicoIC">
                            <thead>
                                <th>Cod.</th>
                                <th>Fornecedor</th>
                                <th>Serviço</th>
                                <th>Complemento</th>
                                <th>Assunto</th>
                                <th>Inicio</th>
                                <th>Fim</th>
                                <th>Razão do Status</th>
                                <th>Número OS</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="atividade-servico-content" style="display: none;">
                    <div class="col-md-12 table-responsive" style="padding-top: 2%;">
                        <table id="tableServicosContratadosBusca" class=" table-bordered table tableServicosContratados buscaEspecificaContrato">
                            <thead>
                                <th>Nome</th>
                                <th>Item de Contrato de Venda</th>
                                <th>Serviço</th>
                                <th>Quantidade</th>
                                <th>Valor Contratado</th>
                                <th>Classificação do produto</th>
                                <th>Grupo de Receita</th>
                                <th>Data de Início</th>
                                <th>Data de Término</th>
                                <th>Data Fim Carência</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button id="btn-info-contrato" class="btn btn-primary" onclick="mostrarInformacoesDetalhadasContrado(this)" style="display:none;">Mostrar Informações Detalhadas</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Buscar OS -->
<div id="modalBuscarOS" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalBuscarOSLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header busca-header-os">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="busca-header-os">&nbsp;&nbsp;Buscar Ordem de Serviço</h3>
            </div>
            <div class="modal-body">
                <div id="header-busca-os" style="padding-left: 15px;">
                    <div class="form-inline margin_bottom_20">
                    </div>
                    <label for="filtro" style="font-size: medium; margin-right: 10px" id="labelTipoBuscarOS">Busca por número OS</label>
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <input class="form-control" name="codigoBuscaOS" id="codigoBuscaOS">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" id="btnBuscarOS" aria-hidden="true">Buscar</button>
                        </div>
                    </div>
                </div>
                <br>

                <input id="busca-id-os" hidden>

                <!-- form de os caso seja encontrada -->
                <div id="divFormOS" style="display:none">
                    <form id="formBuscarOS">
                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-6">
                                    <label>Data de Criação</label>
                                    <h5 id="busca-data-criacao-os" name=></h5>
                                </div>

                                <div class="col-md-6">
                                    <label>Data de Modificação</label>
                                    <h5 id="busca-data-modificacao-os"></h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Tipo de Serviço</label>
                                    <h5 id="busca-tipo-servico-os"></h5>
                                </div>

                                <div class="col-md-6">
                                    <label>Modificado Por</label>
                                    <h5 id="busca-modificado-por-os"></h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Valor Total</label>
                                    <h5 id="busca-valor-total-os"></h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Razão do Status</label>
                                    <select id="busca-razao-status-os" class="form-control" name="statusAprovacao">
                                        <option value="1">Em aberto</option>
                                        <option value="419400000">Fechado</option>
                                        <option value="2">Inativo(a)</option>
                                        <option value="419400001">Não realizado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label>Observações</label>
                                    <textarea id="busca-observacoes-os" class="form-control" name="observacoesOS"></textarea>
                                </div>
                            </div>

                            <?php if ($this->auth->is_allowed_block('edi_alteracaoos')) : ?>
                                <button id="btn-submit-form-os-busca" class="btn btn-primary" style="float:left; margin-top: 2%; margin-bottom: 2%;">Alterar OS</button>
                            <?php else : ?>
                                <button id="btn-submit-form-os-busca" class="btn btn-primary" style="float:left; margin-top: 2%; margin-bottom: 2%;" disabled>Alterar OS</button>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    <table class="table-bordered table " id="tabela-itens-os-busca">
                                        <thead>
                                            <th>Nome</th>
                                            <th>Item</th>
                                            <th>Quant.</th>
                                            <th>Valor Total</th>
                                            <th>Status Aprovação</th>
                                            <th id="dropdown-modal-os" class="dropdown">
                                                Ações
                                                <div class="dropdown-toggle-modal-os" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-align: center;">
                                                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                                </div>

                                                <div id="btn-modal-adicionar-item-os-busca" class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="min-width: 10%;">
                                                    <div data-toggle="modal" data-target="#modal-item-os" onclick="limparCamposCadastroItemOS()" style="color: black; font-weight: bold; cursor: pointer;">
                                                        Adicionar Item
                                                    </div>
                                                </div>
                                            </th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Buscar Ocorrência -->
<div id="modalBuscarOcorrencia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="header-buscar-ocorrencia" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document" data-backdrop="static">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="header-buscar-ocorrencia">Buscar Ocorrência</h3>
            </div>
            <div class="modal-body">
                <form id='form-buscar-ocorrencia'>
                    <label for="input-buscar-ocorrencia" style="padding-top: 20px; margin-left: 15px; margin-bottom: 5px;">Número da Ocorrência</label>
                    <div class='form-group row' style="margin-left: 0; margin-right: 0;">
                        <div class="form-group col-xs-9">
                            <select class="form-control" name="input-buscar-ocorrencia" id="input-buscar-ocorrencia" required></select>
                        </div>
                        <div class='form-group col-xs-3'>
                            <button type="submit" id='button-buscar-ocorrencia' class="btn btn-primary">Procurar</button>
                        </div>
                    </div>
                </form>
                <div class="container" id="containerBuscarOcorrencia" style='width: 100%' hidden>
                    <h4><strong>Cliente</strong></h4>
                    <div class="row" style="padding-bottom: 20px;">
                        <div class="col-xs-12">
                            <p><strong>Documento: <span id='documentoTicket'></span></strong></p>
                        </div>
                        <div class="col-xs-12">
                            <p><strong>Nome Fantasia: </strong><a id='nomeFantasiaTicket' style="cursor: pointer;"></a></p>
                        </div>
                        <div class="col-xs-12">
                            <p><strong>Placas: </strong><span id='placasTicket'></span></p>
                        </div>
                    </div>
                    <h4><strong>Detalhes</strong></h4>
                    <div class="row">
                        <div class="col-xs-6">
                            <p><strong>Nome: </strong><span id='tituloTicket'></span></p>
                        </div>
                        <div class="col-xs-6">
                            <p><strong>Fila atual: </strong><span id='filaAtualTicket'></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <p><strong>Data cadastro: </strong><span id='dataCriacaoTicket'></span></p>
                        </div>
                        <div class="col-xs-6">
                            <p><strong>Última modificação: </strong><span id='ultimaModificacaoTicket'></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <p><strong>Assunto: </strong><span id='assuntoTicket'></span></p>
                        </div>
                        <div class="col-xs-6">
                            <p><strong>Tipo: </strong><span id='tipoTicket'></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <p><strong>Origem: </strong><span id='origemTicket'></span></p>
                        </div>
                        <div class="col-xs-6">
                            <p><strong>O cliente foi contatado? </strong><span id='clienteContatado'></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <p><strong>Status: </strong><span id='statusTicket'></span></p>
                        </div>
                        <div class="col-xs-6">
                            <p><strong>Razão status: </strong><span id='razaoStatusTicket'></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <p><strong>Tecnologia: </strong><span id='tecnologiaTicket'></span></p>
                        </div>
                        <div class="col-xs-6">
                            <p><strong>Criado por: </strong><span id='tecnologiaCriadoPor'></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <p><strong id="modificado-por-avancado">Modificado por: </strong><span id='tecnologiaModificadoPor'></span></p>
                        </div>
                    </div>

                    <div class="row" style='padding-top: 20px; padding-bottom: 20px;'>
                        <div class="col-xs-12">
                            <h4><strong>Detalhamento do Atendimento </strong></h4>
                            <p id='detalhamentoTicket'></p>
                        </div>
                    </div>

                    <div class="row" style='padding-top: 20px; padding-bottom: 20px;'>
                        <div class="col-xs-12">
                            <h4><strong>Descrição </strong></h4>
                            <p id='observacoesTicket' style='min-height: 50px'></p>
                        </div>
                    </div>

                    <div class="row">
                        <table class="table-responsive table-bordered table " id="tabelaAnotacoes">
                            <thead>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Criado por</th>
                                <th>Anexo</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<!-- Modal Buscar Base Instalada -->
<div id="modalBuscarBaseInstalada" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalBuscarBaseInstaladaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3>Buscar Base Instalada</h3>
            </div>
            <div id="bodyModalBuscarBaseInstalada" class="modal-body">
                <div class="row">
                    <form id="formularioBuscarBaseInstalada">
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="baseInstaladaFiltro">Tipo de Busca</label>
                                <select id="baseInstaladaFiltro" class="form-control" name="baseInstaladaFiltro">
                                    <option value="serial" selected>Serial</option>
                                    <option value="placa">Placa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-9">
                            <div class="visible-sm-block visible-md-block visible-lg-block" style="height: 23.637px;"></div>
                            <div class="form-group">
                                <input id="baseInstaladaBusca" class="form-control" type="text" name="baseInstaladaBusca" placeholder="Digite o serial do equipamento" required>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12 text-right">
                            <div class="visible-md-block visible-lg-block" style="height: 23.567px;"></div>
                            <button id="botaoBuscaBaseInstalada" class="btn btn-primary" style="width: 119.66px">Procurar</button>
                        </div>
                    </form>
                    <div id="resultadosBuscaBaseInstalada" class="row" style="display: none !important">
                        <div class="col-md-12 col-sm-12" style="padding: 15px 30px 0 30px">
                            <table id="tabelaBuscaBaseInstalada" class="table table-striped table-bordered" style="width: 100%"></table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" type="button" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Anotacoes -->
<div id="modalAnotacoes" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 2000;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="nameModalAnotacoes">Anotações</h3>
            </div>
            <div class='modal-body'>
                <div class='row'>
                    <div class='col-md-12'>
                        <button class="btn btn-primary" id='novaAnotacao' onclick='clickButtonNovaAnotavao();' style="margin-right: 20px;">Nova Anotação</button>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12">
                        <table class="table-responsive table-bordered table " id="tabelaAnotacoesOcorrencia">
                            <thead>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Criado por</th>
                                <th>Data Criação</th>
                                <th>Anexo</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CADASTRAR ANOTACAO -->
<div id="modalCadastrarAnotacao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarAnotacao" aria-hidden="true" style="z-index: 2001; ">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="modalCadastrarAnotacaCadastrooHeader">Cadastrar Anotação</h3>
            </div>
            <input type="hidden" name="Id" id="Id" type="text">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" id="id-anotacao">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Nome</label><br>
                                <input id="nomeAnotacao" name="nomeAnotacao" class="paramAnotation form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Descrição</label><br>
                                <textarea id="descricaoAnotacao" name="descricaoAnotacao" class="paramAnotation form-control" rows="6" autocomplete="off" style="margin: 0px; width: 567px; height: 125px;"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Anexo</label><br>
                                <input type="file" id="anexoAnot" name="anexo" class="paramAnotation form-control" style="margin: 0px; width: 567px; height: 125px;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                <button class="btn btn-primary" id="btnCadastrarAnotacao" onclick="cadastrarAnotacoes();">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal novo contato associado -->
<div class="modal fade" tabindex="-1" id="modal-contato-associado" role="dialog" aria-labelledby="header-novo-contato-associado" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id='form-cadastro-contato-cliente'>
            <div class="modal-content">
                <div class="modal-header" style='color: #666'>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="header-contato-associado">Novo contato associado</h3>
                    <span style='font-size: 18px; font-weight: 500;'>Cliente: </span><span style='font-size: 16px;' id='info-cliente'></span>
                </div>
                <div class="modal-body">
                    <div class='form-group row'>
                        <div class="form-group col-xs-12">
                            <label for="input-nome-contato-associado">Nome</label>
                            <input type="text" class="form-control" required name="nome" id="input-nome-contato-associado" placeholder="Informe o nome do contato">
                        </div>
                        <div class="form-group col-xs-12">
                            <label for="input-funcao-contato-associado">Função</label>
                            <input type="text" class="form-control" required name="funcao" id="input-funcao-contato-associado" placeholder="Informe a função do contato">
                        </div>
                        <div class="form-group col-xs-12">
                            <label for="input-email-contato-associado">E-mail</label>
                            <input type="email" class="form-control" name="email" id="input-email-contato-associado" placeholder="Informe o email do contato">
                        </div>
                        <div class="form-group col-xs-12">
                            <label for="input-telefone-contato-associado">Telefone</label>
                            <input type="text" class="form-control celular" name="telefone" id="input-telefone-contato-associado" placeholder="Informe o telefone do contato" maxlength="11" value=''>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                    <button type="submit" id='button-salvar-contato-associado' class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- MODAL INFORMACOES OCORRENCIA -->
<div id="modalInformacoesOcorrencia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalBuscarContratoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="tituloInfomacaoOcorrencia">Informações ocorrência</h3>
            </div>
            <div class="modal-body">
                <div class="form-inline margin_bottom_20">
                </div>
                <form action="">
                    <div class="row">
                        <div class="col-md-12" style="margin: 1.5rem 3rem;">
                            <div class="col-md-4 bord">
                                <label for="">Proprietário:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaProprietario"></h5>
                            </div>
                            <div class="col-md-4 bord">
                                <label for="">Número da ocorrência:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaNumeroOcorrencia"></h5>
                            </div>
                            <div class="col-md-4 bord">
                                <label for="">Origem da ocorrência:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaOrigemOcorrencia"></h5>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin: 1.5rem 3rem;">
                            <div class="col-md-4 bord">
                                <label for="">Assunto:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaAssunto"></h5>
                            </div>
                            <div class="col-md-4 bord">
                                <label for="">Assunto Primário:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaAssuntoPrimario"></h5>
                            </div>
                            <div class="col-md-4 bord">
                                <label for="">Tipo de ocorrência:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaTipoOcorrencia"></h5>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin: 1.5rem 3rem;">
                            <div class="col-md-4 bord">
                                <label for="">Tipo de atendimento:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaTipoAtendimento"></h5>
                            </div>
                            <div class="col-md-4 bord">
                                <label for="">Tecnologia:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaTecnologia"></h5>
                            </div>
                            <div class="col-md-4 bord">
                                <label for="">Fila atual:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaFilaAtual"></h5>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin: 1.5rem 3rem;">
                            <div class="col-md-4 bord">
                                <label for="">Última fila:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaUltimaFila"></h5>
                            </div>
                            <div class="col-md-4 bord">
                                <label for="">Fila de atendimento:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaFilaAtendimento"></h5>
                            </div>
                            <div class="col-md-4 bord">
                                <label for="">Status:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaStatus">Status:</h5>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin: 1.5rem 3rem;">
                            <div class="col-md-4 bord">
                                <label for="">Razão do Status:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaRazaoStatus"></h5>
                            </div>
                            <div class="col-md-4 bord">
                                <label for="">Criador por:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaCriador"></h5>
                            </div>
                            <div class="col-md-4 bord">
                                <label for="">Data de criação:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaDataCriacao"></h5>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin: 1.5rem 3rem;">

                            <div class="col-md-4 bord">
                                <label id="modificado-por-ocorrencia" for="">Modificado por:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaModificador"></h5>
                            </div>
                            <div class="col-md-4 bord">
                                <label for="">Data de modificação:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaDataModificacao"></h5>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin: 1.5rem 3rem;">
                            <div class="col-md-12 bord">
                                <label for="">Descrição do Assunto:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaDescricaoAssunto"></h5>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin: 1.5rem 3rem;">
                            <div class="col-md-12 bord">
                                <label for="">Observações:</label>
                                <h5 class="inforOcorrenciaLabel" id="infoOcorrenciaObservacao"></h5>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>
        </div>
    </div>
</div>
<!-- MODAL ENCERRAMENTO OCORRENCIA -->
<div id="modalEncerramentoOcorrencia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarAnotacao" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Encerrar Ocorrência</h3>
            </div>
            <div class="modal-body">
                <form class="encerrarOcorrencia">
                    <input type="hidden" id="quantidadeAtividadesAbertas">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Tipo de Resolução*: </label><br>
                                <select name="selectIdTipoResolucao" id="selectIdTipoResolucao" class="selectPesquisar encerarOcorrencia" class="form-control" style="width: 100%;">
                                    <option value="0" title="Selecione um tipo de resolução" selected disabled>Selecione o tipo de resolução</option>
                                    <option value="419400007" title="Implantação concluída">Implantação concluída</option>
                                    <option value="5" title="Problema Resolvido">Problema Resolvido</option>
                                    <option value="1000" title="Informações Fornecidas">Informações Fornecidas</option>
                                    <option value="419400003" title="Ativação da(s) linha(s) concluída">Ativação da(s) linha(s) concluída</option>
                                    <option value="419400015" title="Resolvido com pendências do cliente">Resolvido com pendências do cliente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Resolução*: </label><br>
                                <input type="text" name="resolucaoOcorrencia" id="resolucaoOcorrencia" class="col-sm-12 encerarOcorrencia">
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Comentários: </label>
                            <textarea id="descricaoEncerrarOcorrencia" name="descricaoEncerrarOcorrencia" class="paramAnotation form-control encerarOcorrencia" rows="6" autocomplete="off" style="margin: 0px; width: 567px; height: 125px;"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                <button class="btn btn-primary" id="btnEncerrarOcorrencia" onclick="eventoEncerrarOcorrencia(this)">Encerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PROVIDENCIA -->
<div id="modalProvidencia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarAnotacao" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="formProvidencia" id="formProvidencia">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3>Providência <span id="tz_name"></span></h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5><b>
                                    Pergunta e Resposta
                                    <a class="btn btn-primary" id="btnEditarProvidencia" style="float: right;" hidden><i class='fa fa-edit' aria-hidden='true'></i></a>
                                </b></h5>
                        </div>
                    </div>
                    <div id="infoPessoasProvidencia"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="btnSalvarProvidencia" disabled>Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL ATIVIDADE DE SERVIÇO -->
<div id="modalAtividadeDeServico" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarAnotacao" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="formAtividadeDeServico" id="formAtividadeDeServico">
                <input type="hidden" id="na_activityid" name="na_activityid">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3>Atividade de Serviço <span id="atividade_servico_tz_name"></span></h3>
                </div>
                <div class="modal-body" id="bodyAtividadeDeServico">

                    <div id="div_atividade_servico">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Atividade de Serviço</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Número do Agendamento</label>
                                <input class="form-control" type="text" name="na_tz_id_agendamento" id="na_tz_id_agendamento">
                            </div>
                            <div class="col-md-6">
                                <label>Referente a</label>
                                <select class="form-control" name="na_regardingobjectid" id="na_regardingobjectid"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Resumo da Solicitação</label>
                                <input class="form-control" type="text" name="na_subject" id="na_subject" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Nome do Solicitante</label>
                                <input class="form-control" type="text" name="na_tz_nome_solicitante" id="na_tz_nome_solicitante" required>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Telefone do Solicitante</label>
                                <input class="form-control" type="text" name="na_tz_telefone_solicitante" id="na_tz_telefone_solicitante" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="control-label">Tipo de Serviço</label>
                                <select class="form-control" id="na_tz_tipo_servico" name="na_tz_tipo_servico">
                                    <option value="" selected="selected" title=""></option>
                                    <option value="1" title="Instalação">Instalação</option>
                                    <option value="2" title="Manutenção">Manutenção</option>
                                    <option value="3" title="Des/Reins Graber">Des/Reins Graber</option>
                                    <option value="4" title="Técnico Alocado">Técnico Alocado</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Item de Contrato</label>
                                <select class="form-control" name="na_tz_item_contratoid" id="na_tz_item_contratoid" required>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Agendar sem contrato</label>
                                <select class="form-control" name="na_tz_agendar_sem_contrato" id="na_tz_agendar_sem_contrato" required>
                                    <option value="false" selected>Não</option>
                                    <option value="true">Sim</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Serviço</label>
                                <select class="form-control" name="na_serviceid" id="na_serviceid" required></select>
                            </div>
                        </div>
                    </div>

                    <!-- Informações Preenchidas após a seleção do Item de contrato -->
                    <div id="div_informacoes_venda">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Informações da Venda</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Número de Série do Contrato</label>
                                <input class="form-control" type="text" id="na_tz_numero_serie_contrato" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Número de Série da antena do Contrato</label>
                                <input class="form-control" type="text" id="na_tz_numero_serie_antena_contrato" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Rastreador</label>
                                <select class="form-control" id="na_tz_rastreadorid" disabled></select>
                            </div>
                            <div class="col-md-6">
                                <label>Plano</label>
                                <select class="form-control" id="na_tz_planoid" disabled></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Veículo do Contrato</label>
                                <select class="form-control" id="na_tz_veiculo_contratoid" disabled></select>
                            </div>
                            <div class="col-md-6">
                                <label>Frota AF</label>
                                <select class="form-control" id="na_tz_frota_afid" disabled></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Plataforma</label>
                                <select class="form-control" id="na_tz_plataformaid" disabled></select>
                            </div>
                            <div class="col-md-6">
                                <label>Cenário de Venda</label>
                                <select class="form-control" id="na_tz_cenario_vendaid" disabled></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Modalidade de Venda</label>
                                <select class="form-control" id="na_tz_modalidade_venda" name="na_tz_modalidade_venda" disabled>
                                    <option value="" selected="selected" title=""></option>
                                    <option value="12" title="Adesão Volksnet">Adesão Volksnet</option>
                                    <option value="5" title="Antena Avulsa">Antena Avulsa</option>
                                    <option value="11" title="Degustação Volksnet">Degustação Volksnet</option>
                                    <option value="9" title="Omnicarga">Omnicarga</option>
                                    <option value="8" title="Venda de Serviços Adicionais">Venda de Serviços Adicionais</option>
                                    <option value="13" title="Reativação">Reativação</option>
                                    <option value="7" title="Telemetria/Jornada Avulsa">Telemetria/Jornada Avulsa</option>
                                    <option value="2" title="Trade In">Trade In</option>
                                    <option value="4" title="Troca de Chip">Troca de Chip</option>
                                    <option value="3" title="Upgrade">Upgrade</option>
                                    <option value="1" title="Veículo Novo">Veículo Novo</option>
                                    <option value="6" title="Venda avulsa de acessórios">Venda avulsa de acessórios</option>
                                    <option value="10" title="Venda de Serviço">Venda de Serviço</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="div_tipo_veiculo_informado_venda">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Tipo do Veículo Informado na Venda</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label>Marca (Inf. Venda)</label>
                                <select class="form-control" id="na_tz_marca_vendaid" disabled></select>
                            </div>
                            <div class="col-md-4">
                                <label>Modelo (Inf. Venda)</label>
                                <select class="form-control" id="na_tz_modelo_vendaid" disabled></select>
                            </div>
                            <div class="col-md-4">
                                <label>Tipo de Veículo (Inf. Venda)</label>
                                <select class="form-control" id="na_tz_tipo_veiculo_vendaid" disabled></select>
                            </div>
                        </div>
                    </div>

                    <div id="div_endereco_atendimento">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Endereço do Atendimento</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Local do Atendimento</label>
                                <select class="form-control" name="na_tz_local_atendimento" id="na_tz_local_atendimento">
                                    <option selected></option>
                                    <option value="1">Ponto Fixo RAZ</option>
                                    <option value="2">Externo</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Envia email de alteração</label>
                                <select class="form-control" name="na_tz_envia_email_alteracao" id="na_tz_envia_email_alteracao">
                                    <option value="false" selected>Não</option>
                                    <option value="true">Sim</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label>CEP</label>
                                <select class="form-control" name="na_tz_endereco_cepid" id="na_tz_endereco_cepid"></select>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Bairro</label>
                                <input type="text" class="form-control" name="na_tz_endereco_bairro" id="na_tz_endereco_bairro" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Rua</label>
                                <input type="text" class="form-control" name="na_tz_endereco_rua" id="na_tz_endereco_rua" required>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Estado</label>
                                <select class="form-control" name="na_tz_endereco_estadoid" id="na_tz_endereco_estadoid" required></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Número</label>
                                <input type="number" class="form-control" name="na_tz_endereco_numero" id="na_tz_endereco_numero" required>
                            </div>
                            <div class="col-md-6">
                                <label>Cidade</label>
                                <select class="form-control" name="na_tz_endereco_cidadeid" id="na_tz_endereco_cidadeid" required></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Complemento</label>
                                <input type="text" class="form-control" name="na_tz_endereco_complemento" id="na_tz_endereco_complemento">
                            </div>
                            <div class="col-md-6">
                                <label>País</label>
                                <input type="text" class="form-control" name="na_tz_endereco_pais" id="na_tz_endereco_pais">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Referência</label>
                                <textarea class="form-control" name="na_tz_referencia" id="na_tz_referencia" rows="5"></textarea>
                            </div>
                        </div>
                    </div>

                    <div id="div_confirmacao_agendamento">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Confirmação de Agendamento</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Emails para envio do agendamento</label>
                                <select class="form-control" name="na_tz_emails_envio_agendamentoid" id="na_tz_emails_envio_agendamentoid"></select>
                            </div>
                        </div>
                    </div>

                    <div id="div_envio_os">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Envio da OS</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Emails para Envio do Orçamento</label>
                                <select class="form-control" name="na_tz_emails_envio_orcamentoid" id="na_tz_emails_envio_orcamentoid"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Cliente PJ que pagará OS</label>
                                <select class="form-control" name="na_tz_cliente_pj_pagar_osid" id="na_tz_cliente_pj_pagar_osid"></select>
                            </div>
                            <div class="col-md-6">
                                <label>Cliente PF que pagará OS</label>
                                <select class="form-control" name="na_tz_cliente_pf_pagar_osid" id="na_tz_cliente_pf_pagar_osid"></select>
                            </div>
                        </div>
                    </div>

                    <div id="div_servico">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Serviço</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Técnico do Cliente</label>
                                <select class="form-control" name="na_tz_tcnicodocliente" id="na_tz_tcnicodocliente">
                                    <option value="false" selected>Não</option>
                                    <option value="true">Sim</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Prestador</label>
                                <select class="form-control" name="na_siteid" id="na_siteid" required></select>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Mostrar Período como</label>
                                <select class="form-control" id="na_statuscode" name="na_statuscode">
                                    <optgroup label="Aberta" id="" state="0">
                                        <option value="1" title="Solicitado">Solicitado</option>
                                        <option value="2" title="Provisório">Provisório</option>
                                    </optgroup>
                                    <optgroup label="Agendado" id="" state="3">
                                        <option value="3" selected="selected" title="Pendente">Pendente</option>
                                        <option value="4" title="Reservado">Reservado</option>
                                        <option value="6" title="Aguardando aprovação orçamento">Aguardando aprovação orçamento</option>
                                        <option value="7" title="Chegou">Chegou</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>É encaixe?</label>
                                <select class="form-control" name="na_tz_encaixe" id="na_tz_encaixe">
                                    <option value="false" selected>Não</option>
                                    <option value="true">Sim</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Data mínima para agendamento</label>
                                <input type="datetime-local" class="form-control" name="na_tz_data_minima_agendamento" id="na_tz_data_minima_agendamento">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Motivo do Encaixe</label>
                                <input type="text" class="form-control" name="na_tz_motivo_encaixe" id="na_tz_motivo_encaixe">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Prestador Possui Peça em Estoque</label>
                                <select class="form-control" name="na_tz_prestador_possui_peca_estoque" id="na_tz_prestador_possui_peca_estoque" required>
                                    <option value="false" selected>Não</option>
                                    <option value="true">Sim</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Reserva da Agenda</label>
                                <select class="form-control" name="na_tz_bloqueio_agendaid" id="na_tz_bloqueio_agendaid"></select>
                            </div>
                        </div>
                    </div>

                    <div id="div_deslocamento">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Deslocamento</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Distância Total</label>
                                <input type="nubmer" class="form-control" min="0" step="0.01" name="na_tz_distancia_total" id="na_tz_distancia_total">
                            </div>
                            <div class="col-md-6">
                                <label>Distância Bonificada</label>
                                <input type="number" class="form-control" min="0" step="0.01" name="na_tz_distancia_bonificada" id="na_tz_distancia_bonificada">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Distância Considerada</label>
                                <input type="number" class="form-control" min="0" step="0.01" name="na_tz_distancia_considerada" id="na_tz_distancia_considerada">
                            </div>
                            <div class="col-md-6">
                                <label>Valor por KM considerado</label>
                                <input type="number" class="form-control" min="0" step="0.01" name="na_tz_valor_km_considerado_cliente" id="na_tz_valor_km_considerado_cliente">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Valor total de deslocamento</label>
                                <input type="number" class="form-control" min="0" step="0.01" name="na_tz_valor_total_deslocamento" id="na_tz_valor_total_deslocamento">
                            </div>
                            <div class="col-md-6">
                                <label>Taxa de visita</label>
                                <input type="number" class="form-control" min="0" step="0.01" name="na_tz_taxa_visita" id="na_tz_taxa_visita">
                            </div>
                        </div>
                    </div>

                    <div id="div_informacoes_sobre_agendamento">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Informações sobre Agendamento</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Hora de Início</label>
                                <input type="datetime-local" class="form-control" name="na_scheduledstart" id="na_scheduledstart">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Hora de Término</label>
                                <input type="datetime-local" class="form-control" name="na_scheduledend" id="na_scheduledend">
                            </div>
                        </div>

                    </div>

                    <div id="div_anotacoes">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Anotações</h4>
                            </div>
                        </div>
                    </div>

                    <div id="div_motivo_agendamento">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Motivo do agendamento</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Motivo do agendamento tardio</label>
                                <select class="form-control" name="na_tz_motivo_agendamento_tardioid" id="na_tz_motivo_agendamento_tardioid"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="btnSalvarAtividadeDeServico">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL ALTERAR DATAS ATIVIDADE DE SERVIÇO -->
<div id="modalAlterarDatasAtividadeDeServico" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarAnotacao" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="formAlterarDatasAtividadeDeServico" id="formAlterarDatasAtividadeDeServico">
                <input type="hidden" id="na_alterar_data_activityid" name="na_alterar_data_activityid">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4>Atividade de Serviço <span id="atividade_servico_tz_name"></span></h4>
                </div>
                <div class="modal-body">
                    <div id="div_informacoes_sobre_agendamento">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Informações sobre Agendamento</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Hora de Início</label>
                                <input type="datetime-local" class="form-control" name="na_alterar_data_scheduledstart" id="na_alterar_data_scheduledstart" max="2999-12-31T23:59:59">
                            </div>
                            <div class="col-md-12">
                                <label class="control-label">Hora de Término</label>
                                <input type="datetime-local" class="form-control" name="na_alterar_data_scheduledend" id="na_alterar_data_scheduledend" max="2999-12-31T23:59:59">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="btnSalvarAlterarDatasAtividadeDeServico">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL ITEM DE CONTRATO -->
<div id="modalItemDeContrato" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalItemDeContrato" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formItemDeContrato" name="formItemDeContrato">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3>Item de Contrato</h3>
                </div>
                <!-- TABS -->
                <div id="header_tabs_item_contrato" class="modal-header header-tabs">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="nav-tab-item_contrato nav_tab_modal_item_contrato active" id="nav_item_contrato">
                                    <a href="#tab_item_contrato" aria-controls="tab_item_contrato" role="tab" data-toggle="tab">
                                        <h4>Item de Contrato</h4>
                                    </a>
                                </li>
                                <li role="presentation" class="nav-tab-alteracao_contrato nav_tab_modal_item_contrato" id="nav_alteracao_contrato">
                                    <a href="#tab_alteracao_contrato" aria-controls="tab_alteracao_contrato" role="tab" data-toggle="tab">
                                        <h4>Alteração de Contrato</h4>
                                    </a>
                                </li>
                                <li role="presentation" class="nav-tab-servico_contratado nav_tab_modal_item_contrato" id="nav_servico_contratado">
                                    <a href="#tab_servico_contratado" aria-controls="tab_servico_contratado" role="tab" data-toggle="tab">
                                        <h4>Serviços Contratados</h4>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="bodyModalItemDeContrato" class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <!-- ITEM DE CONTRATO -->
                                <div role="tabpanel" class="tab-pane  tab-pane-item_contrato active tab_pane_modal_item_contrato" id="tab_item_contrato" data-nav-tab="nav_item_contrato">
                                    <!-- ID ITEM DE CONTRATO -->
                                    <input type="hidden" name="id_item_de_contrato" id="id_item_de_contrato">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Item de Contrato</h3>
                                        </div>
                                    </div>
                                    <!-- IDENTIFICAÇÃO -->
                                    <div id="div_identificacao">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Identificação</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label>Nome</label>
                                                <input type="text" class="form-control" id="tz_name" name="tz_name" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Número da AF</label>
                                                <input type="text" class="form-control" id="tz_numero_af" name="tz_numero_af">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Pedido de Venda</label>
                                                <select type="text" class="form-control tz_afs" id="tz_afid" name="tz_afid"></select>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Código do Item de Contrato</label>
                                                <input type="text" class="form-control" id="tz_codigo_item_contrato" name="tz_codigo_item_contrato">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label">Tipo de Contrato</label>
                                                <!-- required -->
                                                <select class="form-control" id="tz_tipo_contrato" name="tz_tipo_contrato" required>
                                                    <option title=""></option>
                                                    <option value="1" title="Venda">Venda</option>
                                                    <option value="2" title="Comodato">Comodato</option>
                                                    <option value="3" title="Locação">Locação</option>
                                                    <option value="4" title="Demonstração">Demonstração</option>
                                                    <option value="5" title="Fidelidade">Fidelidade</option>
                                                    <option value="6" title="Bonificado">Bonificado</option>
                                                    <option value="7" title="Pré-contrato">Pré-contrato</option>
                                                    <option value="8" title="Degustação">Degustação</option>
                                                    <option value="9" title="Fleetboard">Fleetboard</option>
                                                    <option value="10" title="Omnicarga">Omnicarga</option>
                                                    <option value="11" title="Venda Graber">Venda Graber</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Integrar com Gestor</label>
                                                <select class="form-control" id="tz_integrar_gestor" name="tz_integrar_gestor">
                                                    <option value="false">Não</option>
                                                    <option value="true">Sim</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Código Gestor</label>
                                                <input type="number" class="form-control" id="tz_codigo_gestor" name="tz_codigo_gestor" min="0">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- DADOS DO PRODUTO -->
                                    <div id="div_dados_produto">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Dados do Produto</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">Rastreador</label>
                                                <select class="form-control" name="tz_rastreadorid" id="tz_rastreadorid" required></select>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Veículo</label>
                                                <select class="form-control" name="tz_veiculoid" id="tz_veiculoid"></select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label">Plano</label>
                                                <select class="form-control" name="tz_plano_linkerid" id="tz_plano_linkerid" required></select>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Providência</label>
                                                <select class="form-control" name="tz_providenciasid" id="tz_providenciasid"></select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Número de série do módulo principal</label>
                                                <input type="text" class="form-control" name="tz_numero_serie_modulo_principal" id="tz_numero_serie_modulo_principal">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Número de série da antena satelital</label>
                                                <input type="text" class="form-control" name="tz_numero_serie_antena_satelital" id="tz_numero_serie_antena_satelital">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Modelo/Tipo informado na Ativação</label>
                                                <input type="text" class="form-control" id="tz_modelo_tipo_informado_ativacao" name="tz_modelo_tipo_informado_ativacao" disabled>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- SITUAÇÃO -->
                                    <div id="div_situacao">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Situação</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="">Origem do Contrato</label>
                                                <input type="text" class="form-control" id="tz_entrada_devido_a" name="tz_entrada_devido_a" disabled>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="">Data da alteração de status</label>
                                                <input type="date" class="form-control" id="tz_data_alteracao_status" name="tz_data_alteracao_status" disabled>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">Status do item de contrato</label>
                                                <select type="text" class="form-control" id="tz_status_item_contrato" name="tz_status_item_contrato" required>
                                                    <option></option>
                                                    <option value="1">Ativo</option>
                                                    <option value="2">Aguardando Ativação</option>
                                                    <option value="3">Cancelado</option>
                                                    <option value="4">Suspenso</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="">Alterado Devido A</label>
                                                <select class="form-control" id="tz_cancelado_devido_a" name="tz_cancelado_devido_a">
                                                    <option title=""></option>
                                                    <option value="24" title="Renovação Omnicarga">Renovação Omnicarga</option>
                                                    <option value="21" title="Downgrade">Downgrade</option>
                                                    <option value="16" title="Reativação">Reativação</option>
                                                    <option value="1" title="Encerramento de Contrato">Encerramento de Contrato</option>
                                                    <option value="2" title="Troca de Titularidade">Troca de Titularidade</option>
                                                    <option value="3" title="Des/Reins">Des/Reins</option>
                                                    <option value="4" title="Desinstalação">Desinstalação</option>
                                                    <option value="5" title="Alteração de Grupo de Receita">Alteração de Grupo de Receita</option>
                                                    <option value="6" title="Upgrade">Upgrade</option>
                                                    <option value="7" title="Venda adicional de serviços">Venda adicional de serviços</option>
                                                    <option value="8" title="Suspensão Administrativa">Suspensão Administrativa</option>
                                                    <option value="9" title="Redução de Mensalidade">Redução de Mensalidade</option>
                                                    <option value="10" title="Cancelamento Programado">Cancelamento Programado</option>
                                                    <option value="11" title="Cancelamento sem pro-rata">Cancelamento sem pro-rata</option>
                                                    <option value="12" title="Ativação Volksnet">Ativação Volksnet</option>
                                                    <option value="22" title="Degustação Volksnet">Degustação Volksnet</option>
                                                    <option value="13" title="Alteração Pontual de Contrato">Alteração Pontual de Contrato</option>
                                                    <option value="14" title="Suspensão devido a sinistro">Suspensão devido a sinistro</option>
                                                    <option value="15" title="Ajuste de Base">Ajuste de Base</option>
                                                    <option value="17" title="Cancelamento de Serviço Opcional">Cancelamento de Serviço Opcional</option>
                                                    <option value="18" title="Ativação de Serviços Opcionais">Ativação de Serviços Opcionais</option>
                                                    <option value="20" title="Upgrade - Migração Linker">Upgrade - Migração Linker</option>
                                                    <option value="19" title="Retorno de Suspensão">Retorno de Suspensão</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Item de contrato original</label>
                                                <select type="text" class="form-control" id="tz_item_contrato_originalid" name="tz_item_contrato_originalid" disabled></select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Motivo da Alteração</label>
                                                <select type="text" class="form-control" id="tz_motivo_alteracao" name="tz_motivo_alteracao"></select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- DATAS -->
                                    <div id="div_datas">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Datas</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Data da Ativação</label>
                                                <input type="date" class="form-control" id="tz_data_ativacao" name="tz_data_ativacao">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Data do 1° Venc. do Contrato de Serviço</label>
                                                <input type="date" class="form-control" id="tz_data_vencimento_comodato" name="tz_data_vencimento_comodato">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Data fim de Contrato</label>
                                                <input type="date" class="form-control" id="tz_data_termino" name="tz_data_termino">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Data Vencimento Fidelidade</label>
                                                <input type="date" class="form-control" id="tz_data_termino_fidelidade" name="tz_data_termino_fidelidade">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Duração do Comodato</label>
                                                <input type="number" class="form-control" id="tz_duracao_comodato" name="tz_duracao_comodato" min="0">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Data Desinstalação</label>
                                                <input type="date" class="form-control" id="tz_data_desinstalacao" name="tz_data_desinstalacao">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Data de Ínicio da Suspensão</label>
                                                <input type="date" class="form-control" id="tz_data_inicio_suspensao" name="tz_data_inicio_suspensao">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Data de Término da Suspensão</label>
                                                <input type="date" class="form-control" id="tz_data_termino_suspensao" name="tz_data_termino_suspensao">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Data de Expedição</label>
                                                <input type="date" class="form-control" id="tz_data_expedicao" name="tz_data_expedicao">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label">Data de Criação</label>
                                                <input type="date" class="form-control" id="tz_data_entrada" name="tz_data_entrada" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label">Data de Aniversário</label>
                                                <input type="date" class="form-control" id="tz_data_aniversario_contrato" name="tz_data_aniversario_contrato" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- GARANTIA DO EQUIPAMENTO -->
                                    <div id="div_garantia_equipamento">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Garantia do Equipamento</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Data Início Garantia</label>
                                                <input type="date" class="form-control" id="tz_data_inicio_garantia" name="tz_data_inicio_garantia">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Data Fim Garantia</label>
                                                <input type="date" class="form-control" id="tz_data_fim_garantia" name="tz_data_fim_garantia">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- DEMOSTRAÇÃO E CARÊNCIA -->
                                    <div id="div_demostracao_carencia">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Demonstração e Carência</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Data de vencimento demonstração/degustação</label>
                                                <input type="date" class="form-control" id="tz_data_vencimento_demonstracao" name="tz_data_vencimento_demonstracao">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Data Fim de Carência</label>
                                                <input type="date" class="form-control" id="tz_data_fim_carencia" name="tz_data_fim_carencia">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- REAJUSTE -->
                                    <div id="div_reajuste">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Reajuste</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label">Índice de Reajuste</label>
                                                <select class="form-control" id="tz_indice_reajusteid" name="tz_indice_reajusteid" required></select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Data da Próxima Renovação</label>
                                                <input type="date" class="form-control" id="tz_data_proxima_renovacao" name="tz_data_proxima_renovacao">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Data do Último Reajuste</label>
                                                <input type="date" class="form-control" id="tz_data_ultimo_reajuste" name="tz_data_ultimo_reajuste">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Percentual do Último Reajuste</label>
                                                <input type="number" step="0.001" min="0" class="form-control" id="tz_percentual_ultimo_reajuste" name="tz_percentual_ultimo_reajuste">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ISENÇÃO DE FATURAMENTO E REAJUSTE -->
                                    <div id="div_isencao_faturamento_reajuste">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Isenção de Faturamento e Reajuste</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Meses Isento de Reajuste</label>
                                                <input type="number" min="0" class="form-control" id="tz_qtde_meses_isento_reajuste" name="tz_qtde_meses_isento_reajuste" />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Data de Carência de Reajuste</label>
                                                <input type="date" class="form-control" id="tz_data_carencia_reajuste" name="tz_data_carencia_reajuste">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- VALORES -->
                                    <div id="div_valores">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Valores</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="">Valor de Deslocamento</label>
                                                <input type="number" step="0.01" min="0" class="form-control" id="tz_valor_deslocamento_km" name="tz_valor_deslocamento_km" disabled />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="">Taxa de Visita</label>
                                                <input type="number" step="0.01" min="0" class="form-control" id="tz_taxa_visita" name="tz_taxa_visita" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- DESCRIÇÃO -->
                                    <div id="div_descricao">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Descrição</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <textarea class="form-control" name="tz_descricao" id="tz_descricao" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- INFORMAÇÕES DA VENDA -->
                                    <div id="div_informacoes_venda">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Informações da Venda</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label">Plataforma</label>
                                                <select class="form-control" name="tz_plataformaid" id="tz_plataformaid" required></select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label">Cenário de Venda</label>
                                                <select class="form-control" name="tz_cenario_vendaid" id="tz_cenario_vendaid" required></select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Modalidade de Venda</label>
                                                <select class="form-control" name="tz_modalidade_venda" id="tz_modalidade_venda" disabled></select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Local da Instalação</label>
                                                <select class="form-control" name="tz_local_instalacao" id="tz_local_instalacao" disabled></select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Marca</label>
                                                <select class="form-control" name="tz_marcaid" id="tz_marcaid" disabled></select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Modelo</label>
                                                <select class="form-control" name="tz_modeloid" id="tz_modeloid" disabled></select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Tipo de Veículo</label>
                                                <select class="form-control" name="tz_tipo_veiculoid" id="tz_tipo_veiculoid" disabled></select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label">Tecnologia</label>
                                                <select class="form-control" name="tz_tecnologiaid" id="tz_tecnologiaid" required></select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Frota AF</label>
                                                <select class="form-control" name="tz_frota_afid" id="tz_frota_afid" disabled></select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="">Canal de Venda</label>
                                                <select class="form-control" name="tz_canal_vendaid" id="tz_canal_vendaid" disabled></select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Chassi (Volksnet)</label>
                                                <input type="text" class="form-control" name="tz_chassi" id="tz_chassi" disabled />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="">Venda Efetuada</label>
                                                <input type="text" class="form-control" name="tz_venda_efetuada" id="tz_venda_efetuada" disabled />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Período de Degustação (Volksnet)</label>
                                                <input type="text" class="form-control" name="tz_periodo_degustacao" id="tz_periodo_degustacao" disabled />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="">Contrato (Agrupador)</label>
                                                <input type="text" class="form-control" name="tz_contrato_agrupador" id="tz_contrato_agrupador" disabled />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Integra Contrato</label>
                                                <select class="form-control" name="tz_integra_contrato" id="tz_integra_contrato">
                                                    <option value="false">Não</option>
                                                    <option value="true">Sim</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="">Sequencial Item Contrato</label>
                                                <input type="number" min="0" class="form-control" name="tz_sequencial_item_contrato" id="tz_sequencial_item_contrato">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Valor da Mensalidade (R$)</label>
                                                <input type="number" min="0" step="0.01" class="form-control" name="tz_valor_licenca" id="tz_valor_licenca" />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Reenviar Apolice Graber</label>
                                                <select class="form-control" name="tz_reenviar_apolice_graber" id="tz_reenviar_apolice_graber">
                                                    <option value="false">Não</option>
                                                    <option value="true">Sim</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- ALTERACAO DE CONTRATO -->
                                <div role="tabpanel" class="tab-pane  tab-pane-alteracao_contrato tab_pane_modal_item_contrato" id="tab_alteracao_contrato" data-nav-tab="nav_alteracao_contrato">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if ($this->auth->is_allowed_block('out_alterarInfoItensContratoOmnilink')) : ?>
                                                <h3>
                                                    Alteração de Contrato
                                                    <button type="button" class="btn btn-primary" id="btnAbrirModalCadastrarAlteracaoDeContrato" style="float: right;">Cadastrar Alteração de Contrato</button>

                                                </h3>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table-responsive table-bordered table " id="tableAlteracaoDeContrato">
                                                <thead>
                                                    <th>Nome</th>
                                                    <th>Veículo</th>
                                                    <th>Item de Contrato</th>
                                                    <th>Modelo/Tipo informado na ativação</th>
                                                    <th>Status da Alteração</th>
                                                    <th>Data de Criação</th>
                                                    <th>Ações</th>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- SERVICOS CONTRATADOS -->
                                <div role="tabpanel" class="tab-pane  tab-pane-servico_contratado tab_pane_modal_item_contrato" id="tab_servico_contratado" data-nav-tab="nav_servico_contratado">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if ($this->auth->is_allowed_block('out_alterarInfoItensContratoOmnilink')) : ?>
                                                <h3>
                                                    Serviços Contratados
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalServicosContratados" style="float: right;">Cadastrar Serviço Contratado</button>
                                                </h3>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <style>
                                        #tableServicosContratados tr {
                                            font-size: 0.9em !important;
                                        }
                                    </style>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="tableServicosContratados" style="width: 100%" class="table-responsive table-bordered table tableServicosContratados">
                                                <thead>
                                                    <th>Nome</th>
                                                    <th>Item de Contrato de Venda</th>
                                                    <th>Serviço</th>
                                                    <th>Quantidade</th>
                                                    <th>Valor Contratado</th>
                                                    <th>Classificação do produto</th>
                                                    <th>Grupo de Receita</th>
                                                    <th>Data de Início</th>
                                                    <th>Data de Término</th>
                                                    <th>Data Fim Carência</th>
                                                    <th>Ações</th>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <!-- Permissão para salvar item de contrato -->
                    <?php if ($this->auth->is_allowed_block('out_alterarInfoItensContratoOmnilink')) : ?>
                        <button type="submit" class="btn btn-primary" id="btnSalvarItemDeContrato">Salvar</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Base Instalada -->
<div id="modalBaseInstalada" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="myModalLabel">Base Instalada</h3>
            </div>
            <form id="formBaseInstalada">
                <input type="hidden" id="base_instalada_tz_base_instalada_clienteid">
                <div class="modal-body max_height_modal" style="padding: 0 30px;">
                    <!-- GERAL -->
                    <div class="div_row_geral">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="subtitle">Geral</h4>
                            </div>
                        </div>
                        <!-- CLIENTE -->
                        <div class="div_row_cliente">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5><b>Cliente</b></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label>Cliente PJ (Contrato)</label>
                                    <select type="text" class="form-control accounts" id="base_instalada_tz_cliente_pjid" name="base_instalada_tz_cliente_pjid"></select>
                                </div>
                                <div class="col-md-6 input-container">
                                    <label>Cliente PF (Contrato)</label>
                                    <select type="text" class="form-control contacts" id="base_instalada_tz_cliente_pfid" name="base_instalada_tz_cliente_pfid"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label>Cliente PJ (Matriz)</label>
                                    <select type="text" class="form-control accounts" id="base_instalada_tz_cliente_pj_matrizid" name="base_instalada_tz_cliente_pj_matrizid"></select>
                                </div>
                                <div class="col-md-6 input-container">
                                    <label>Cliente PF (Matriz)</label>
                                    <select type="text" class="form-control contacts" id="base_instalada_tz_cliente_pf_matrizid" name="base_instalada_tz_cliente_pf_matrizid"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label>Cliente PJ (Instalado)</label>
                                    <select type="text" class="form-control accounts" id="base_instalada_tz_cliente_pj_instaladoid" name="base_instalada_tz_cliente_pj_instaladoid"></select>
                                </div>
                                <div class="col-md-6 input-container">
                                    <label>Cliente PF (Instalado)</label>
                                    <select type="text" class="form-control contacts" id="base_instalada_tz_cliente_pf_instaladoid" name="base_instalada_tz_cliente_pf_instaladoid"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label>Cliente Anterior PJ</label>
                                    <select type="text" class="form-control accounts" id="base_instalada_tz_cliente_anterior_pj" name="base_instalada_tz_cliente_anterior_pj"></select>
                                </div>
                                <div class="col-md-6 input-container">
                                    <label>Cliente Anterior PF</label>
                                    <select type="text" class="form-control contacts" id="base_instalada_tz_cliente_anterior_pf" name="base_instalada_tz_cliente_anterior_pf"></select>
                                </div>
                            </div>
                        </div>

                        <!-- VEICULO -->
                        <div class="div_row_veiculo">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5><b>Veículo</b></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label>Veículo</label>
                                    <select class="form-control tz_veiculos" name="base_instalada_tz_veiculoid" id="base_instalada_tz_veiculoid"></select>
                                </div>
                                <div class="col-md-6 input-container">
                                    <label>Item de Contrato</label>
                                    <select class="form-control tz_item_contrato_vendas" name="base_instalada_tz_item_contratoid" id="base_instalada_tz_item_contratoid"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label>Marca</label>
                                    <select class="form-control tz_marcas" name="base_instalada_tz_marcaid" id="base_instalada_tz_marcaid"></select>
                                </div>
                                <div class="col-md-6 input-container">
                                    <label>Modelo</label>
                                    <select class="form-control tz_modelos" name="base_instalada_tz_modelo_ativacao" id="base_instalada_tz_modelo_ativacao"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label>Chassi</label>
                                    <input type="text" class="form-control" name="base_instalada_tz_chassi" id="base_instalada_tz_chassi" />
                                </div>
                                <div class="col-md-6 input-container">
                                    <label>Cor</label>
                                    <input type="text" class="form-control" name="base_instalada_tz_cor" id="base_instalada_tz_cor" />
                                </div>
                            </div>
                        </div>

                        <!-- PRODUTO -->
                        <div class="div_row_produto">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5><b>Produto</b></h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label class="control-label">Produto</label>
                                    <select class="form-control products" name="base_instalada_tz_produtoid" id="base_instalada_tz_produtoid" required></select>
                                </div>
                                <div class="col-md-6 input-container">
                                    <label class="control-label">Número de Série</label>
                                    <input type="text" class="form-control" id="base_instalada_tz_numero_serie" name="base_instalada_tz_numero_serie" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label class="control-label">Tipo de Produto</label>
                                    <select class="form-control" name="base_instalada_tz_tipo_produto" id="base_instalada_tz_tipo_produto" required>
                                        <option title=""></option>
                                        <option value="1" title="Rastreador">Rastreador</option>
                                        <option value="6" title="Rastreador Telemetria">Rastreador Telemetria</option>
                                        <option value="2" title="Antena Satelital">Antena Satelital</option>
                                        <option value="5" title="Acessórios">Acessórios</option>
                                        <option value="4" title="Terminal">Terminal</option>
                                        <option value="3" title="Trava">Trava</option>
                                    </select>
                                </div>
                                <div class="col-md-6 input-container">
                                    <label>Data de Desinstalação</label>
                                    <input type="date" class="form-control" id="base_instalada_tz_data_desinstalacao" name="base_instalada_tz_data_desinstalacao">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label>Data de Instalação</label>
                                    <input type="date" class="form-control" id="base_instalada_tz_data_instalacao" name="base_instalada_tz_data_instalacao">
                                </div>
                            </div>
                        </div>

                        <!-- RASTREADOR -->
                        <div class="div_row_rastreador">
                            <div class="col-md-12" style="padding: 0;">
                                <h5><b>Rastreador</b></h5>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label class="control-label">Plataforma</label>
                                    <select class="form-control tz_plataformas" name="base_instalada_tz_plataformaid" id="base_instalada_tz_plataformaid" required></select>
                                </div>
                                <div class="col-md-6 input-container">
                                    <label class="control-label">Tecnologia</label>
                                    <select class="form-control tz_tecnologias" name="base_instalada_tz_tecnologiaid" id="base_instalada_tz_tecnologiaid" required></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label>Local do Rastreador</label>
                                    <input type="text" class="form-control" name="base_instalada_tz_local_rastreador" id="base_instalada_tz_local_rastreador">
                                </div>
                            </div>
                        </div>

                        <!-- CHIP 1 -->
                        <div class="div_row_chip1">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5><b>Chip 1</b></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label>Simcard</label>
                                    <input type="text" class="form-control chip" name="base_instalada_tz_simcard1" id="base_instalada_tz_simcard1">
                                </div>
                                <div class="col-md-6 input-container">
                                    <label>Linha</label>
                                    <input type="tel" class="form-control phone" name="base_instalada_tz_linha1" id="base_instalada_tz_linha1" placeholder="(xx) 9xxxx-xxxx">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label>Operadora</label>
                                    <input type="text" class="form-control" name="base_instalada_tz_operadora1" id="base_instalada_tz_operadora1">
                                </div>
                            </div>
                        </div>
                        <!-- CHIP 2 -->
                        <div class="div_row_chip2">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5><b>Chip 2</b></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label>Simcard</label>
                                    <input type="text" class="form-control" name="base_instalada_tz_simcard2" id="base_instalada_tz_simcard2">
                                </div>
                                <div class="col-md-6 input-container">
                                    <label>Linha</label>
                                    <input type="tel" class="form-control phone" name="base_instalada_tz_linha2" id="base_instalada_tz_linha2" placeholder="(xx) 9xxxx-xxxx">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label>Operadora</label>
                                    <input type="text" class="form-control" name="base_instalada_tz_operadora2" id="base_instalada_tz_operadora2">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FICHA DE ATIVAÇÃO -->
                    <div class="div_row_ficha_ativacao">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Ficha de Ativação</h4>
                            </div>
                        </div>

                        <div class="div_row_informacoes">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5><b>Informações</b></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-container">
                                    <label>Grupo de Emails do Cliente</label>
                                    <select class="form-control tz_grupo_emails_clientes" name="base_instalada_tz_grupo_emails_clienteid" id="base_instalada_tz_grupo_emails_clienteid"></select>
                                </div>
                                <div class="col-md-6 input-container">
                                    <label>Versão do Firmware</label>
                                    <input type="text" class="form-control" name="base_instalada_tz_versao_firmware" id="base_instalada_tz_versao_firmware" maxlength="6">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Observações</label>
                                    <textarea class="form-control" name="base_instalada_tz_observacoes" id="base_instalada_tz_observacoes" rows="5"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="div_row_sensores_atuadores" id="div_row_sensores_atuadores" hidden>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5><b>Sensores e Atuadores</b></h5>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Insere selects dinamicamente -->
                                <div id="selects_sensores_atuadores_base_estalada"></div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                        <button class="btn btn-primary" id="btnSubmitBaseInstalada" type="submit">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Funções Programáveis -->
<div id="modalFuncoesProgramaveis" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="myModalLabel">Associar Grupo de Funções Programáveis</h3>
            </div>
            <form id="formFuncoesProgramaveis">
                <input type="hidden" name="funcao_programavel_cliente_id" id="funcao_programavel_cliente_id">
                <div class="modal-body" style="padding: 0 30px;">
                    <!-- GERAL -->
                    <div class="div_row_geral">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="subtitle">Dados</h4>
                            </div>
                        </div>
                        <!-- Função Programável -->
                        <div class="div_row_funcao">
                            <div class="row">
                                <div class="col-md-12 input-container">
                                    <label for="funcao_programavel_id" class="control-label">Grupo de Função Programável</label>
                                    <select type="text" class="form-control" id="funcao_programavel_id" name="funcao_programavel_id" style="width: 100% !important;" required></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 input-container">
                                    <label for="funcao_programavel_descricao" class="control-label">Descrição</label>
                                    <textarea maxlength="200" cols="3" style="resize: vertical;" class="form-control" id="funcao_programavel_descricao" name="funcao_programavel_descricao" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                        <button class="btn btn-primary" id="btnSubmitFuncaoProgramavel" type="submit">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL ALTERACAO DE CONTRATO -->
<div id="modalAlteracaoDeContrato" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalAlteracaoDeContrato" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formAlteracaoDeContrato" name="formAlteracaoDeContrato">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h3>Alteração de Contrato</h3>
                </div>
                <div class="modal-body scrollModal" style="min-height: 300px;">
                    <!-- ALTERAÇÃO DE CONTRATO -->

                    <!-- ID ITEM DE CONTRATO -->
                    <input type="hidden" name="id_alteracao_de_contrato" id="id_alteracao_de_contrato">
                    <!-- VALOR DA TAXA -->
                    <input type="hidden" id="alteracao_contrato_tz_valortaxa">

                    <div class="row">
                        <div class="col-md-12">
                            <span id="itemContratoAlteracaoContrato"></span>
                        </div>
                    </div>
                    <!-- GERAL -->
                    <div id="div_geral">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Geral</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Motivo</label>
                                <select class="form-control" name="alteracao_contrato_tz_motivoid" id="alteracao_contrato_tz_motivoid" required></select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Nome</label>
                                <select id="alteracao_contrato_statuscode" name="alteracao_contrato_statuscode" class="form-control" disabled>
                                    <option value="1" title="Rascunho" selected>Rascunho</option>
                                    <option value="419400001" title="Recusado">Recusado</option>
                                    <option value="419400000" title="Implantado">Implantado</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Ocorrência</label>
                                <select class="form-control" id="alteracao_contrato_tz_incidentid" name="alteracao_contrato_tz_incidentid"></select>
                            </div>
                        </div>
                    </div>

                    <!-- DETALHES -->
                    <div id="div_detalhes_alteracao_contrato"></div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="btnSalvarAlteracaoDeContrato">Salvar</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- MODAL SERVICOS CONTRATADOS -->
<div id="modalServicosContratados" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarAnotacao" aria-hidden="true" data-backdrop="static" style="overflow: auto !important; ">
    <div class="modal-dialog modal-lg" role="document">
        <!-- SERVIÇOS CONTRATADOS -->
        <form id="formServicoContratado" name="formServicoContratado">

            <input type="hidden" id="id_servico_contratado" name="id_servico_contratado">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h3>Serviço Contratado</h3>
                </div>
                <div class="modal-body" style="min-height: 300px;">

                    <div class="row">
                        <div class="col-md-12">
                        </div>
                    </div>

                    <!-- CONTRATO -->
                    <div id="div_contrato">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Contrato</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Nome</label>
                                <input type="text" class="form-control" id="servico_contratado_tz_name" name="servico_contratado_tz_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="">Grupo de Receita</label>
                                <select class="form-control" name="servico_contratado_tz_grupo_receitaid" id="servico_contratado_tz_grupo_receitaid"></select>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Classificação de Produto</label>
                                <select class="form-control" name="servico_contratado_tz_classificacao_produtoid" id="servico_contratado_tz_classificacao_produtoid"></select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Moeda</label>
                                <select class="form-control" name="servico_contratado_transactioncurrencyid" id="servico_contratado_transactioncurrencyid"></select>
                            </div>
                        </div>
                    </div>
                    <!-- IDENTIFICAÇÃO DO SERVIÇO -->
                    <div id="div_identificacao_do_servico">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Identificação do Serviço</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Serviço</label>
                                <select class="form-control" name="servico_contratado_tz_produtoid" id="servico_contratado_tz_produtoid"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Quantidade</label>
                                <input type="number" min="0" class="form-control" id="servico_contratado_tz_quantidade" name="servico_contratado_tz_quantidade" required>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Valor Contratado</label>
                                <input type="number" min="0" class="form-control" step="0.001" pattern="^\d+(?:\.\d{1,2})?$" id="servico_contratado_tz_valor_contratado" name="servico_contratado_tz_valor_contratado" required>
                            </div>
                        </div>
                    </div>

                    <!-- DATAS -->
                    <div id="div_datas">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Datas</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Data de início</label>
                                <input type="date" class="form-control" id="servico_contratado_tz_data_inicio" name="servico_contratado_tz_data_inicio">
                            </div>
                            <div class="col-md-6">
                                <label for="">Data do término</label>
                                <input type="date" class="form-control" id="servico_contratado_tz_data_termino" name="servico_contratado_tz_data_termino">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Data fim carência</label>
                                <input type="date" class="form-control" id="servico_contratado_tz_data_fim_carencia" name="servico_contratado_tz_data_fim_carencia">
                            </div>
                        </div>
                    </div>

                    <!-- DESCRIÇÃO -->
                    <div id="div_descricao">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Descrição</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <textarea class="form-control" name="servico_contratado_tz_descricao" id="servico_contratado_tz_descricao" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="btnSalvarServicoContratado">Salvar</button>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- Modal Buscar NA -->
<div id="modal-busca-na" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalBuscaNA" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h2>Buscar NA</h2>
            </div>
            <div class="modal-body">
                <div class="form-inline margin_bottom_20">
                </div>
                <label for="filtro" style="font-size: medium; margin-right: 10px" id="label-tipo-na">Tipo de Busca</label>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <select class="form-control" name="tipo-busca-na" id="tipo-busca-na">
                            <option value="codigo">Código</option>
                            <option value="serial">Serial</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="text" class="form-control" name="busca-na-modal" id="busca-na-modal" placeholder="Digite o valor para a busca" style="width:100%">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary" id="btn-busca-na" aria-hidden="true">Buscar</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Busca Atividades de Serviço -->
<div id="modal-resultado-busca-na" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-busca-na" aria-hidden="true" style="overflow-y: initial;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Atividade de Serviço (NA) <span id="codigo-na-modal"></span></h3>
            </div>
            <div class="modal-body" style="height: 80vh;overflow-y: auto;">
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Serial</label>
                        <h5 id="serial-modal-na"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Cliente</label>
                        <h5 id="cliente-modal-na"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Serviço</label>
                        <h5 id="servico-modal-na"></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Complemento</label>
                        <h5 id="complemento-modal-na"></h5>
                    </div>

                    <div class="col-md-4">
                        <label for="">Início</label>
                        <h5 id="inicio-modal-na"></h5>
                    </div>

                    <div class="col-md-4">
                        <label for="">Fim</label>
                        <h5 id="fim-modal-na"></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Razão do Status</label>
                        <h5 id="razao-modal-na"></h5>
                    </div>

                    <div class="col-md-4">
                        <label for="">Fornecedor</label>
                        <h5 id="fornecedor-modal-na"></h5>
                    </div>
                </div>
                <hr />

                <div class="row">

                    <div class="col-md-4">
                        <label for="">Assunto</label>
                        <h5 id="assunto-modal-na"></h5>
                    </div>
                    <div class="col-md-4">
                        <label for="">Nome Solicitante</label>
                        <h5 id="nome-solicitante-modal-na"></h5>
                    </div>

                    <div class="col-md-4">
                        <label for="">Número Solicitante</label>
                        <h5 id="telefone-solicitante-modal-na"></h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label for="">Item de Contrato</label>
                        <h5 id="item-contrato-modal-na"></h5>
                    </div>

                    <div class="col-md-4">
                        <label for="">Recurso</label>
                        <h5 id="recurso-modal-na"></h5>
                    </div>

                    <div class="col-md-4">
                        <label for="">Data Mínima de Agendamento</label>
                        <h5 id="data-minima-modal-na"></h5>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-4">
                        <label for="">Local Atendimento</label>
                        <h5 id="local-atendimento-modal-na"></h5>
                    </div>

                    <div id="endereco-externo-modal-na" style="display: none">
                        <div class="col-md-4">
                            <label for="">Rua</label>
                            <h5 id="rua-endereco-externo-modal-na"></h5>
                        </div>
                        <div class="col-md-4">
                            <label for="">Número</label>
                            <h5 id="numero-endereco-externo-modal-na"></h5>
                        </div>
                    </div>

                </div>

                <hr />

                <div id="anotacao-modal-na" class="row" style="display: none;">

                    <div class="col-md-4">
                        <label for="">Titulo</label>
                        <h5 id="titulo-modal-na"></h5>
                    </div>

                    <div class="col-md-4">
                        <label for="">Descrição</label>
                        <h5 id="descricao-modal-na"></h5>
                    </div>

                    <div class="col-md-4">
                        <label for="">Arquivo</label>
                        <h5 id="arquivo-modal-na"></h5>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-contrato-modal-na" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Visualizar Contrato</button>
                <button id="btn-ocorrencia-modal-na" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Visualizar Ocorrência</button>
                <button id="btn-os-modal-na" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Visualizar OS</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal da OS -->
<div id="modal-os" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabelOs" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 15px">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="header-os"></h3>
            </div>

            <form id="form-os">
                <div class="col-md-12">
                    <div class="row">

                        <div class="col-md-6">
                            <label>Data de Criação</label>
                            <h4 id="data-criacao-os"></h4>
                        </div>

                        <div class="col-md-6">
                            <label>Data de Modificação</label>
                            <h4 id="data-modificacao-os"></h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Tipo de Serviço</label>
                            <h4 id="tipo-servico-os"></h4>
                        </div>

                        <div class="col-md-6">
                            <label>Modificado Por</label>
                            <h4 id="modificado-por-os"></h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Valor Total</label>
                            <h4 id="valor-total-os"></h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Razão do Status</label>
                            <select id="razao-status-os" class="form-control" name="statusAprovacao">
                                <option value="1">Em aberto</option>
                                <option value="419400000">Fechado</option>
                                <option value="2">Inativo(a)</option>
                                <option value="419400001">Não realizado</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Observações</label>
                            <textarea id="observacoes-os" class="form-control" name="observacoesOS"></textarea>
                        </div>
                    </div>

                    <button id="btn-submit-form-os" class="btn btn-primary" style="float:left; margin-top: 2%; margin-bottom: 2%;">Alterar OS</button>

                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table-bordered table " id="tabela-itens-os">
                                <thead>
                                    <th>Nome</th>
                                    <th>Item</th>
                                    <th>Quantidade</th>
                                    <th>Valor Total</th>
                                    <th>Status Aprovação</th>
                                    <th id="dropdown-modal-os" class="dropdown">
                                        Ações
                                        <div class="dropdown-toggle-modal-os" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-align: center;">
                                            <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                        </div>

                                        <div id="btn-modal-adicionar-item-os" class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="min-width: 10%;">
                                            <div data-toggle="modal" data-target="#modal-item-os" onclick="limparCamposCadastroItemOS()" style="color: black; font-weight: bold; cursor: pointer;">
                                                Adicionar Item
                                            </div>
                                        </div>
                                    </th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true" style="margin-top: 2%; margin-bottom: 2%;">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-info-na" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-x-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="h3-busca-na">Propriedades da Atividade de Serviço</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div id="informacoes-na">
                        <div class="row">
                            <h5>Detalhes</h5>
                            <div class="col-md-6">
                                <p>Criado por:</p>
                                <p>Data de Criação:</p>
                                <p>Última modificação por:</p>
                                <p>Última modificação em:</p>
                            </div>
                            <div class="col-md-6">
                                <p id="infoNa_criadoPor"></p>
                                <p id="infoNa_criadoEm"></p>
                                <p id="infoNa_modificadoPor"></p>
                                <p id="infoNa_modificadoEm"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal comunicação chip -->
<div id="modal-comunicacao-chip" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-x-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="h3-busca-chip">Buscar Chip</h3>
            </div>
            <div class="modal-body">
                <div id="div-busca-chip">
                    <label for="filtro" style="font-size: medium; margin-right: 10px">Digite o Serial a Ser Buscado</label>
                    <form id='form-buscar-chip'>
                        <div class='form-group row '>
                            <div class="form-group col-xs-9">
                                <input type="text" class="form-control" placeholder="Digite o Serial do equipamento" id="input-buscar-chip" required></input>
                            </div>
                            <div class='form-group'>
                                <button type="submit" id='btn-buscar-chip' onclick="abrirModalComunicacaoChip( this, null, true )" class="btn btn-primary">Procurar</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="informacoes-chip" style="display: none">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Dados do(s) Equipamento(s)</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table-bordered table " id="tabela-equipamento-comunicacao" style="width: 100%;">
                                <thead>
                                    <th>Nome Tecnologia</th>
                                    <th>Nome Modelo</th>
                                    <th>Status</th>
                                    <th>Fone</th>
                                    <th>Operadora</th>
                                    <th>Data da Última Comunicação</th>
                                    <th>Ações</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h3>Antena Iridium</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table-bordered table " id="tabela-equipamento-iridium">
                                <thead>
                                    <th>Imei</th>
                                    <th>Status</th>
                                    <th>Criado em</th>
                                    <th>Atualizado em</th>
                                    <th>Destino</th>
                                    <th>Metódo de Entrega</th>
                                    <th>Include Geo Data</th>
                                    <th>MO ACK</th>
                                    <th>Ações</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal informações MHS -->
<div id="modal-informações-mhs" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-x-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="h3-modal-informações-mhs">Informações MHS</h3>
            </div>
            <div class="modal-body">
                <div id="informações-mhs" style="display: none">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Informações da MHS</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table-bordered table " id="tabela-informações-mhs">
                                <thead>
                                    <th>ID</th>
                                    <th>Data de Suspensão</th>
                                    <th>Data de Cancelamento</th>
                                    <th>ID da Antena</th>
                                    <th>Código da Operadora</th>
                                    <th>Nome da Operadora</th>
                                    <th>Data da Última Comunicação</th>
                                    <th>Código do Tipo da Última Comunicação</th>
                                    <th>Tipo da Última Comunicação</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="titulo-mhs-antena">Antena Iridium</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table-bordered table " id="tabela-equipamento-mhs">
                                <thead>
                                    <th>Imei</th>
                                    <th>Status</th>
                                    <th>Criado em</th>
                                    <th>Atualizado em</th>
                                    <th>Destino</th>
                                    <th>Metódo de Entrega</th>
                                    <th>Include Geo Data</th>
                                    <th>MO ACK</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>

                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal do item de OS -->
<div id="modal-item-os" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabelItemOs" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 15px">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Item da OS nº <STRONG>
                        <p id="numero-os" style="display: inline-block"></p>
                    </STRONG>
                    <p id="modificado-por-item-os" style="display: inline-block"></p>
                </h3>
            </div>
            <form id="form-item-os" name="form-item-os">
                <div class="col-md-12">
                    <div class="row">
                        <input id="id-os" name="idOS" type="hidden"></input>
                        <input id="id-item-os" name="idItemOS" type="hidden"></input>

                        <div class="col-md-6">
                            <label>Item</label>
                            <select id="produto-item-os" class="form-control" name="produtoItemOS"></select>
                        </div>

                        <div class="col-md-6">
                            <label>Status Aprovação</label>
                            <select class="form-control" name="statusAprovacao" id="status-aprovacao">
                                <option value="0" selected disabled>Selecione um Status</option>
                                <option value="1">Aguardando Aprovação</option>
                                <option value="2">Aprovado</option>
                                <option value="3">Recusado</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <label>Quantidade</label>
                            <input id="quantidade-itens-os" class="form-control altera-valor-total" name="quantidadeItensOS" type="number" min="1"></input>
                        </div>

                        <div class="col-md-6">
                            <label>Valor Unitário</label>
                            <input id="valor-unitario-item-os" class="form-control altera-valor-total" name="valorUnitario" readonly style="cursor:not-allowed" />
                        </div>
                    </div>

                    <hr />
                    <div class="row">
                        <div class="col-md-6">
                            <label>Percentual de Desconto</label>
                            <input id="percentual-desconto-item-os" type="number" class="form-control" name="percentualDesconto" />
                        </div>

                        <div class="col-md-6">
                            <label>Valor Desconto</label>
                            <input id="valor-desconto-itens-os" class="form-control altera-valor-total" name="valorDesconto" type="number" /></input>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <label>Motivo Desconto</label>
                            <select id="motivo-desconto-item-os" class="form-control" name="motivoDescontoItensOS">
                                <option value="0" disabled selected>Selecione o motivo de desconto</option>
                                <option value="1">NÃO COMPROVAÇÃO DO MAU USO</option>
                                <option value="2">REINCIDÊNCIA DE MANUTENÇÃO</option>
                                <option value="3">ACORDO OPERACIONAL</option>
                                <option value="4">GARANTIA</option>
                                <option value="5">SISTÊMICO</option>
                                <option value="6">ACORDO COMERCIAL</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Valor Total</label>
                            <input id="valor-total-itens-os" class="form-control" name="valorTotal" type="number" readonly style="cursor:not-allowed" />
                        </div>
                    </div>
                    <div class="row">
                        <div id="div-aprovador-itens-os" hidden class="col-md-6">
                            <label>Aprovador do Desconto</label>
                            <select id="aprovador-itens-os" class="form-control" name="aprovadorDesconto"></select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" type="submit" id="btn-adicionar-item-os">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cadastro de NA -->
<div id="modalCadNAAvulsa" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadNAAvulsa" id="formCadNAAvulsa">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="nomeHeaderModal"><?= lang("cadastrar_atividade_servico") ?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="col-md-12">
                        <div id="div_identificacao">
                            <div class="row">
                                <div class="col-md-12 form-group bord">
                                    <label class="control-label">Cliente: </label>
                                    <select class="form-control input-sm" id="cliente-NaAvulsa" name="cliente-NaAvulsa" style="width: 100%" required></select>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 form-group bord">
                                    <label class="control-label">Resumo da Solicitação: </label>
                                    <textarea type="text" class="form-control input-sm" name="resumo-solicitacao-NaAvulsa" id="nomeCenariosDeVendas" placeholder="Descreva o resumo" maxlength="200" rows="2" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Nome do Solicitante: </label>
                                    <input class="form-control input-sm" id="nome-solicitante-NaAvulsa" name="nome-solicitante-NaAvulsa" type="text" placeholder="Digite o nome do solicitante" required>
                                </div>
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Telefone do Solicitante: </label>
                                    <input class="form-control input-sm" id="telefone-solicitante-NaAvulsa" name="telefone-solicitante-NaAvulsa" placeholder="Digite o telefone" type="text" minlength="15" required>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4 form-group bord">
                                    <label class="control-label">Item de Contrato: </label>
                                    <select class="form-control input-sm" id="item-contrato-NaAvulsa" name="item-contrato-NaAvulsa" required disabled>
                                        <option value="" selected disabled>Aguardando seleção de cliente</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group bord">
                                    <label>Número de Série do Contrato: </label><i class="fa fa-refresh" id="recarregarSeries" style="margin-left: 5px;" title="Recarregar todas as séries"></i>
                                    <select class="form-control input-sm" id="numero-serie-NaAvulsa" name="numero-serie-NaAvulsa" disabled>
                                        <option value="" selected disabled>Aguardando seleção de cliente</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group bord">
                                    <label class="control-label">Serviço: </label>
                                    <select class="form-control input-sm" id="servico-NaAvulsa" name="servico-NaAvulsa" required disabled>
                                        <option value="" selected disabled>Aguardando seleção de item de contrato</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4 form-group bord">
                                    <label class="control-label">Data Mínima Agendamento: </label>
                                    <input class="form-control input-sm" id="data-minima-NaAvulsa" name="data-minima-NaAvulsa" type="datetime-local" placeholder="Selecione uma data" required>
                                </div>
                                <div class="col-md-4 form-group bord">
                                    <label class="control-label">Local do Atendimento: </label>
                                    <select class="form-control input-sm" id="local-atendimento-NaAvulsa" name="local-atendimento-NaAvulsa" required>
                                        <option value="1">Ponto Fixo RAZ</option>
                                        <option value="2" selected>Externo</option>
                                    </select>
                                </div>
                                <div id="divCepNaAvulsa" class="col-md-4 form-group bord">
                                    <label class="control-label">CEP: </label>
                                    <input class="form-control input-sm" id="cep-NaAvulsa" name="cep-NaAvulsa" type="text" placeholder="Digite o Cep" required>
                                </div>
                            </div>
                            <div id="divEndereco1NaAvulsa" class="row">
                                <div class="col-md-4 form-group bord">
                                    <label class="control-label">Estado: </label>
                                    <select class="form-control input-sm" id="estado-NaAvulsa" name="estado-NaAvulsa" required disabled></select>
                                </div>
                                <div class="col-md-4 form-group bord">
                                    <label class="control-label">Cidade: </label>
                                    <select class="form-control input-sm" id="cidade-NaAvulsa" name="cidade-NaAvulsa" required disabled>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group bord">
                                    <label class="control-label">Bairro: </label>
                                    <input class="form-control input-sm" id="bairro-NaAvulsa" name="bairro-NaAvulsa" type="text" placeholder="Digite o bairro ou aguarde o preenchimento automático" required>
                                </div>
                            </div>
                            <div id="divEndereco2NaAvulsa" class="row">
                                <div class="col-md-4 form-group bord">
                                    <label class="control-label">Rua: </label>
                                    <input class="form-control input-sm" id="rua-NaAvulsa" name="rua-NaAvulsa" placeholder="Digite a rua ou aguarde o preenchimento automático" required>
                                </div>
                                <div class="col-md-4 form-group bord">
                                    <label class="control-label">Número: </label>
                                    <input class="form-control input-sm" id="numero-NaAvulsa" name="numero-NaAvulsa" placeholder="Digite o número" required>
                                </div>
                                <div class="col-md-4 form-group bord">
                                    <label>Complemento: </label>
                                    <input class="form-control input-sm" id="complemento-NaAvulsa" name="complemento-NaAvulsa" type="text" placeholder="Digite o complemento">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Prestador: </label>
                                    <select class="form-control input-sm" id="prestador-NaAvulsa" name="prestador-NaAvulsa" required disabled>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Recurso: </label>
                                    <select class="form-control input-sm" id="recurso-NaAvulsa" name="recurso-NaAvulsa" required disabled>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div id="divDistancia1NaAvulsa" class="row">
                                <div class="col-md-4 form-group bord">
                                    <label class="control-label">Distância Total:</label>
                                    <input id="distancia-total-NaAvulsa" type="number" value='0' class="form-control input-sm" min="0" name="distancia-total-NaAvulsa" required>
                                </div>
                                <div class="col-md-4 form-group bord">
                                    <label class="control-label">Distância Bonificada:</label>
                                    <input id="distancia-bonificada-NaAvulsa" type="number" value='50' class="form-control input-sm" min="0" name="distancia-bonificada-NaAvulsa" required>
                                </div>
                                <div class="col-md-4 form-group bord">
                                    <label>Distância considerada:</label>
                                    <input id="distancia-considerada-NaAvulsa" type="number" value='0' class="form-control input-sm" min="0" name="distancia-considerada-NaAvulsa" readonly>
                                </div>
                            </div>
                            <div id="divDistancia2NaAvulsa" class="row">
                                <div class="col-md-4 form-group bord">
                                    <label>Valor por KM considerado:</label>
                                    <input id="valor-km-considerado-NaAvulsa" type="number" value='2.50' class="form-control input-sm" min="0" name="valor-km-considerado-NaAvulsa" readonly>
                                </div>
                                <div class="col-md-4 form-group bord">
                                    <label>Valor total (deslocamento):</label>
                                    <input id="valor-total-deslocamento-NaAvulsa" type="number" value='0' class="form-control input-sm" min="0" name="valor-total-deslocamento-NaAvulsa" readonly>
                                </div>
                                <div class="col-md-4 form-group bord">
                                    <label>Taxa de visita:</label>
                                    <input id="taxa-visita-NaAvulsa" type="number" class="form-control input-sm" name="taxa-visita-NaAvulsa">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 form-group bord">
                                    <label class="control-label">Anotações: </label>
                                    <textarea type="text" class="form-control input-sm" name="anotacoes-NaAvulsa" id="anotacoes-NaAvulsa" placeholder="Descreva a anotação" maxlength="200" rows="2" required></textarea>
                                </div>
                                <div class="col-md-12 form-group bord">
                                    <input id="anexo-NaAvulsa" name="anexo-NaAvulsa" type="file" class="form-control input-sm" style="margin-top: 1%;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" data-botao='' data-id='' type="submit" id="btnSalvarCadastroNaAvulsa" style="margin-right: 15px;">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal do item de OS -->
<div id="modal-na" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabelCadastroNa" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="container">
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <ul class='nav nav-tabs' role="tablist" id="abas-na">
                        <li role="presentation" class="nav-tab-atividade-servico" id="nav-tab-atividade-servico"><a id="tab-na" class="nav-link active" href="#">Atividade de Serviço (NA)</a></li>
                        <li role="presentation" class="nav-tab-os" id="nav-tab-os"><a class="nav-link" id="tab-os" href="#">Ordem de Serviço</a></li>
                    </ul>
                    <div id="na">
                        <h4>
                            <p id="tipo-form-modal-na" style="display: inline-block;">Cadastrar</p> Atividade de Serviço
                        </h4>
                        <hr>
                        <div id="aviso-ocorrencia" class="alert alert-warning" role="alert">
                            <strong>Atenção!</strong> Essa Atividade de Serviço não foi originada por uma ocorrência. Se
                            prosseguir será criada uma ocorrência automaticamente.
                        </div>

                        <ul class="nav nav-tabs">
                            <li><a id="a-modal-na-1" href="#modal-na-1" class="nav-item active">1</a></li>
                            <li><a id="a-modal-na-2" href="#modal-na-2" class="nav-item">2</a></li>
                            <li><a id="a-modal-na-3" href="#modal-na-3" class="nav-item">3</a></li>
                            <li><a id="a-modal-na-4" href="#modal-na-4" class="nav-item">4</a></li>
                            <li><a id="a-modal-na-5" href="#modal-na-5" class="nav-item">5</a></li>
                        </ul>

                        <style>
                            div.selectize-input {
                                width: 100% !important;
                                height: 28px !important;
                                padding: 0px 8px !important;
                            }

                            .selectize-dropdown .selected {
                                background-color: #5897fb !important;
                                color: #fff;
                            }
                        </style>

                        <div id="modal-na-1">
                            <br>
                            <form id="form-cadastro-na" name="form-cadastro-na">
                                <div class="row" id="divNumeroNA-1" hidden>
                                    <div class="form-group col-sm-12">
                                        <h4 id="numeroNA-1"></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <input id="id-ocorrencia-na" type="hidden" name="idOcorrencia" />
                                    <div class="form-group col-sm-12">
                                        <label for="resumo-solicitacao">Resumo da Solicitação <span class="text-danger">*</span></label>
                                        <textarea type="text" id="resumo-solicitacao" class="form-control" name="resumoSolicitacao" maxlength="200" rows="2" required></textarea>
                                    </div>

                                    <input id="id-na" name="idNa" type="hidden" />
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="nome-solicitante">Nome do Solicitante <span class="text-danger">*</span></label>
                                        <input type="text" id="nome-solicitante" class="form-control" name="nomeSolicitante" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="telefone-solicitante">Telefone do Solicitante <span class="text-danger">*</span></label>
                                        <input type="tel" id="telefone-solicitante" class="form-control celular" name="telefoneSolicitante" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="itemContratoIdSelect">Item de Contrato <span class="text-danger">*</span></label>
                                        <select id="item-contrato-na" name="itemDeContrato" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um item de contrato" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando itens de contrato...</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="serialItemContratoIdSelect">Número de Série do Contrato</label>
                                        <!-- <select id="serial-item-contrato-na" name="serialItemDeContrato" placeholder="Selecione um serial" class="selectized" style="width: 100%; heigth: 28px !important;">
                                        </select> -->
                                        <select id="serial-item-contrato-na" name="serialItemDeContrato" placeholder="Selecione um serial" class="form-control param selectPesquisar" data-placeholder="Selecione um Número de Série" style="width: 100%;">
                                            <option value="0" disabled selected>Selecione um contrato para buscar os Números de Séries</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group col-sm-6" style="margin-left: -14px;">
                                    <label for="">Local do Atendimento</label>
                                    <select id="local-atendimento-na" name="localAtendimento" class="form-control endereco-modal-na">
                                        <option value="1">Ponto Fixo RAZ</option>
                                        <option value="2" selected>Externo</option>
                                    </select>
                                </div>
                                <div id="endereco-na">
                                    <div class="row">
                                        <div class="form-group col-sm-6" style="margin-left: 14px;">
                                            <label for="">CEP</label>
                                            <input id="cep-na" class="form-control cep endereco-modal-na" name="cep" placeholder="Digite o CEP p/ preenchimento automático" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="estadoSelect">Estado</label>
                                            <select id="estado-na" name="estado" class="form-control endereco-modal-na param selectPesquisar" data-placeholder="Buscando estados..." style="width: 100%;">
                                                <option value="0" disabled selected>Buscando estados...</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Ordenar Prestadores mais próximo</label>
                                            <br>
                                            <input id="filtrar-na" type="checkbox" class="endereco-modal-na" name="filtra">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="cidadeEstado">Cidade</label>
                                            <select id="cidade-na" name="cidade" class="form-control endereco-modal-na param selectPesquisar" data-placeholder="Selecione um estado para filtrar as cidades" style="width: 100%;">
                                                <option value="0" disabled selected>Selecione um estado para filtrar as cidades</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Bairro</label>
                                            <input id="bairro-na" type="text" class="form-control endereco-modal-na" name="bairro">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="">Rua</label>
                                            <input id="rua-na" type="text" class="form-control endereco-modal-na" name="rua">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Número</label>
                                            <input id="numero-na" type="number" class="form-control endereco-modal-na" name="numero">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label for="">Complemento</label>
                                            <textarea id="complemento-na" class="form-control" name="complemento"></textarea>
                                        </div>
                                    </div>
                                    <hr />
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="">Serviço <span class="text-danger">*</span></label>
                                        <select id="servico-na" name="servico" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um serviço" style="width: 100%;" required>
                                            <option value="0" disabled selected>Selecione um contrato para buscar os serviços</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="">Prestador <span class="text-danger">*</span></label>
                                        <select id="prestador-na" name="prestador" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um prestador" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando prestadores...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="">Recurso <span class="text-danger">*</span></label>
                                        <select id="recurso-na" name="recurso" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um recurso" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando recursos...</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="">Data Mínima Agendamento <span class="text-danger">*</span></label>
                                        <input id="data-minima" name="dataMinima" class="form-control param" type="text" style="cursor:pointer" title="Clique para selecionar a data" readonly required>
                                    </div>

                                </div>
                                <hr>

                                </br>
                                <div id="distancia-na">
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label>Distância Total</label>
                                            <input id="distancia-total-na" type="number" value='0' class="form-control" min="0" name="distanciaTotal">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Distância Bonificada</label>
                                            <input id="distancia-bonificada-na" type="number" value='50' class="form-control" min="0" name="distanciaBonificada" readonly>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Distância considerada</label>
                                            <input id="distancia-considerada-na" type="number" value='0' class="form-control" min="0" name="distanciaConsiderada" readonly>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Valor por KM considerado</label>
                                            <input id="valor-km-considerado-na" type="number" class="form-control" name="tz_valor_km_considerado_cliente" value="2.50">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Valor total (deslocamento)</label>
                                            <input id="valor-total-deslocamento-na" type="number" value='0' min="0" class="form-control" min="0" name="valorTotalDeslocamento">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Taxa de visita</label>
                                            <input id="taxa-visita-na" type="number" class="form-control" name="taxaVisita" disabled>
                                        </div>
                                    </div>
                                </div>
                                </br>

                                <link-entity name="tz_base_instalada_cliente" from="tz_base_instalada_clienteid" to="tz_baseinstaladarastreador" />

                                <hr />
                                <div id="anotacao-na" class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="">Anotações</label>
                                        <textarea id="anotacoes-na" class="form-control" name="anotacoes"></textarea>
                                        <input id="anexo-na" type="file" name="anexo" style="margin-top: 3%;" />
                                        <input id="id-anexo" type="hidden" name="idAnexo" />
                                    </div>
                                </div>
                                <div id="arquivo-download-na" style="display:none;" class="btn btn-primary">Anotações</div>

                                <div class="modal-footer">
                                    <button class="btn" id="btn-fechar-modal-na" data-dismiss="modal" aria-hidden="true" enabled="enabled">Fechar</button>
                                    <button class="btn btn-primary" type="submit" id="btn-adicionar-na">Salvar NA1</button>
                                </div>
                            </form>
                        </div>


                        <div id="modal-na-2" hidden>
                            <br>

                            <form id="form-cadastro-na-2" name="form-cadastro-na">
                                <div class="row" id="divNumeroNA-2" hidden>
                                    <div class="form-group col-sm-12">
                                        <h4 id="numeroNA-2"></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <input id="id-ocorrencia-na-2" type="hidden" name="idOcorrencia" />
                                    <div class="form-group col-sm-12">
                                        <label for="resumo-solicitacao">Resumo da Solicitação <span class="text-danger">*</span></label>
                                        <textarea type="text" id="resumo-solicitacao" class="form-control" name="resumoSolicitacao" maxlength="200" rows="2" required></textarea>
                                    </div>

                                    <input id="id-na-2" name="idNa" type="hidden" />
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="nome-solicitante">Nome do Solicitante <span class="text-danger">*</span></label>
                                        <input type="text" id="nome-solicitante-2" class="form-control" name="nomeSolicitante" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="telefone-solicitante">Telefone do Solicitante <span class="text-danger">*</span></label>
                                        <input type="tel" id="telefone-solicitante-2" class="form-control celular" name="telefoneSolicitante" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="itemContratoIdSelect">Item de Contrato <span class="text-danger">*</span></label>
                                        <select id="item-contrato-na-2" name="itemDeContrato2" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um item de contrato" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando itens de contrato...</option>
                                        </select>
                                    </div>
                                    <div id="serial-na-2" class="form-group col-sm-6">
                                        <label for="serialItemContratoIdSelect">Número de Série do Contrato</label>
                                        <select id="serial-item-contrato-na-2" name="serialItemDeContrato" placeholder="Selecione um serial" class="form-control param selectPesquisar" data-placeholder="Selecione um Número de Série" style="width: 100%;">
                                            <option value="0" disabled selected>Selecione um contrato para buscar os Números de Séries</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group col-sm-6" style="margin-left: -14px;">
                                    <label for="">Local do Atendimento</label>
                                    <select id="local-atendimento-na-2" name="localAtendimento" class="form-control endereco-modal-na">
                                        <option value="1">Ponto Fixo RAZ</option>
                                        <option value="2" selected>Externo</option>
                                    </select>
                                </div>
                                <div id="endereco-na-2">
                                    <div class="row">
                                        <div class="form-group col-sm-6" style="margin-left: 14px;">
                                            <label for="">CEP</label>
                                            <input id="cep-na-2" class="form-control cep endereco-modal-na" name="cep" placeholder="Digite o CEP p/ preenchimento automático" disabled></input>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="estadoSelect">Estado</label>
                                            <select id="estado-na-2" name="estado" class="form-control endereco-modal-na param selectPesquisar" data-placeholder="Buscando estados..." style="width: 100%;">
                                                <option value="0" disabled selected>Buscando estados...</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Ordenar Prestadores mais próximo</label>
                                            <br>
                                            <input id="filtrar-na-2" type="checkbox" class="endereco-modal-na-2" name="filtra">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="cidadeEstado">Cidade</label>
                                            <select id="cidade-na-2" name="cidade" class="form-control endereco-modal-na param selectPesquisar" data-placeholder="Selecione um estado para filtrar as cidades" style="width: 100%;">
                                                <option value="0" disabled selected>Selecione um estado para filtrar as cidades</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Bairro</label>
                                            <input id="bairro-na-2" type="text" class="form-control endereco-modal-na" name="bairro">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="">Rua</label>
                                            <input id="rua-na-2" type="text" class="form-control endereco-modal-na" name="rua">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Número</label>
                                            <input id="numero-na-2" type="number" class="form-control endereco-modal-na" name="numero">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label for="">Complemento</label>
                                            <textarea id="complemento-na-2" class="form-control" name="complemento"></textarea>
                                        </div>
                                    </div>
                                    <hr />
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="">Serviço <span class="text-danger">*</span></label>
                                        <select id="servico-na-2" name="servico" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um serviço" style="width: 100%;" required>
                                            <option value="0" disabled selected>Selecione um contrato para buscar os serviços</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="">Prestador <span class="text-danger">*</span></label>
                                        <select id="prestador-na-2" name="prestador" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um prestador" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando prestadores...</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="">Recurso <span class="text-danger">*</span></label>
                                        <select id="recurso-na-2" name="recurso" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um recurso" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando recursos...</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="">Data Mínima Agendamento <span class="text-danger">*</span></label>
                                        <input id="data-minima-2" name="dataMinima" class="form-control param" type="text" style="cursor:pointer" title="Clique para selecionar a data" readonly required>
                                    </div>
                                </div>
                                <hr>

                                </br>
                                <div id="distancia-na-2">
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label>Distância Total</label>
                                            <input id="distancia-total-na-2" type="number" value='0' class="form-control" min="0" name="distanciaTotal">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Distância Bonificada</label>
                                            <input id="distancia-bonificada-na-2" type="number" value='50' class="form-control" min="0" name="distanciaBonificada" readonly>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Distância considerada</label>
                                            <input id="distancia-considerada-na-2" type="number" value='0' class="form-control" min="0" name="distanciaConsiderada" readonly>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Valor por KM considerado</label>
                                            <input id="valor-km-considerado-na-2" type="number" class="form-control" readonly name="tz_valor_km_considerado_cliente" value="2.50">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Valor total (deslocamento)</label>
                                            <input id="valor-total-deslocamento-na-2" type="number" value='0' min="0" class="form-control" min="0" name="valorTotalDeslocamento">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Taxa de visita</label>
                                            <input id="taxa-visita-na-2" type="number" class="form-control" name="taxaVisita">
                                        </div>
                                    </div>
                                </div>
                                </br>

                                <link-entity name="tz_base_instalada_cliente" from="tz_base_instalada_clienteid" to="tz_baseinstaladarastreador" />

                                <hr />
                                <div id="anotacao-na-2" class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="">Anotações</label>
                                        <textarea id="anotacoes-na-2" class="form-control" name="anotacoes"></textarea>
                                        <input id="anexo-na-2" type="file" name="anexo" style="margin-top: 3%;" />
                                        <input id="id-anexo-2" type="hidden" name="idAnexo" />
                                    </div>
                                </div>
                                <div id="arquivo-download-na-2" style="display:none;" class="btn btn-primary">Anotacoções</div>

                                <div class="modal-footer">
                                    <button class="btn" id="btn-fechar-modal-na-2" data-dismiss="modal" aria-hidden="true">Fechar</button>
                                    <button class="btn btn-primary" type="submit" id="btn-adicionar-na-2">Salvar NA2</button>
                                </div>
                            </form>
                        </div>

                        <div id="modal-na-3" hidden>
                            <br>

                            <form id="form-cadastro-na-3" name="form-cadastro-na">
                                <div class="row" id="divNumeroNA-3" hidden>
                                    <div class="form-group col-sm-12">
                                        <h4 id="numeroNA-3"></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <input id="id-ocorrencia-na-3" type="hidden" name="idOcorrencia" />
                                    <div class="form-group col-sm-12">
                                        <label for="resumo-solicitacao">Resumo da Solicitação <span class="text-danger">*</span></label>
                                        <textarea type="text" id="resumo-solicitacao" class="form-control" name="resumoSolicitacao" maxlength="200" rows="2" required></textarea>
                                    </div>

                                    <input id="id-na-3" name="idNa" type="hidden" />
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="nome-solicitante">Nome do Solicitante <span class="text-danger">*</span></label>
                                        <input type="text" id="nome-solicitante-3" class="form-control" name="nomeSolicitante" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="telefone-solicitante">Telefone do Solicitante <span class="text-danger">*</span></label>
                                        <input type="tel" id="telefone-solicitante-3" class="form-control celular" name="telefoneSolicitante" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="itemContratoIdSelect">Item de Contrato <span class="text-danger">*</span></label>
                                        <select id="item-contrato-na-3" name="itemDeContrato3" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um item de contrato" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando itens de contrato...</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="serialItemContratoIdSelect">Número de Série do Contrato</label>
                                        <select id="serial-item-contrato-na-3" name="serialItemDeContrato" placeholder="Selecione um serial" class="form-control param selectPesquisar" data-placeholder="Selecione um Número de Série" style="width: 100%;">
                                            <option value="0" disabled selected>Selecione um contrato para buscar os Números de Séries</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group col-sm-6" style="margin-left: -14px;">
                                    <label for="">Local do Atendimento</label>
                                    <select id="local-atendimento-na-3" name="localAtendimento" class="form-control endereco-modal-na">
                                        <option value="1">Ponto Fixo RAZ</option>
                                        <option value="2" selected>Externo</option>
                                    </select>
                                </div>
                                <div id="endereco-na-3">
                                    <div class="row">
                                        <div class="form-group col-sm-6" style="margin-left: 14px;">
                                            <label for="">CEP</label>
                                            <input id="cep-na-3" class="form-control cep endereco-modal-na" name="cep" placeholder="Digite o CEP p/ preenchimento automático" disabled></input>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="estadoSelect">Estado</label>
                                            <select id="estado-na-3" name="estado" class="form-control endereco-modal-na param selectPesquisar" data-placeholder="Buscando estados..." style="width: 100%;">
                                                <option value="0" disabled selected>Buscando estados...</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Ordenar Prestadores mais próximo</label>
                                            <br>
                                            <input id="filtrar-na-3" type="checkbox" class="endereco-modal-na-3" name="filtra">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="cidadeEstado">Cidade</label>
                                            <select id="cidade-na-3" name="cidade" class="form-control endereco-modal-na param selectPesquisar" data-placeholder="Selecione um estado para filtrar as cidades" style="width: 100%;">
                                                <option value="0" disabled selected>Selecione um estado para filtrar as cidades</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Bairro</label>
                                            <input id="bairro-na-3" type="text" class="form-control endereco-modal-na" name="bairro">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="">Rua</label>
                                            <input id="rua-na-3" type="text" class="form-control endereco-modal-na" name="rua">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Número</label>
                                            <input id="numero-na-3" type="number" class="form-control endereco-modal-na" name="numero">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label for="">Complemento</label>
                                            <textarea id="complemento-na-3" class="form-control" name="complemento"></textarea>
                                        </div>
                                    </div>
                                    <hr />
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="">Serviço <span class="text-danger">*</span></label>
                                        <select id="servico-na-3" name="servico" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um serviço" style="width: 100%;" required>
                                            <option value="0" disabled selected>Selecione um contrato para buscar os serviços</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="">Prestador <span class="text-danger">*</span></label>
                                        <select id="prestador-na-3" name="prestador" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um prestador" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando prestadores...</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="">Recurso <span class="text-danger">*</span></label>
                                        <select id="recurso-na-3" name="recurso" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um recurso" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando recursos...</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="">Data Mínima Agendamento <span class="text-danger">*</span></label>
                                        <input id="data-minima-3" name="dataMinima" class="form-control param" type="text" style="cursor:pointer" title="Clique para selecionar a data" readonly required>
                                    </div>
                                </div>
                                <hr>

                                </br>
                                <div id="distancia-na-3">

                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label>Distância Total</label>
                                            <input id="distancia-total-na-3" type="number" value='0' class="form-control" min="0" name="distanciaTotal">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Distância Bonificada</label>
                                            <input id="distancia-bonificada-na-3" type="number" value='50' class="form-control" min="0" name="distanciaBonificada" readonly>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Distância considerada</label>
                                            <input id="distancia-considerada-na-3" type="number" value='0' class="form-control" min="0" name="distanciaConsiderada" readonly>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Valor por KM considerado</label>
                                            <input id="valor-km-considerado-na-3" type="number" class="form-control" readonly name="tz_valor_km_considerado_cliente" value="2.50">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Valor total (deslocamento)</label>
                                            <input id="valor-total-deslocamento-na-3" type="number" value='0' min="0" class="form-control" min="0" name="valorTotalDeslocamento">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Taxa de visita</label>
                                            <input id="taxa-visita-na-3" type="number" class="form-control" name="taxaVisita">
                                        </div>
                                    </div>
                                </div>
                                </br>

                                <link-entity name="tz_base_instalada_cliente" from="tz_base_instalada_clienteid" to="tz_baseinstaladarastreador" />

                                <hr />
                                <div id="anotacao-na-3" class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="">Anotações</label>
                                        <textarea id="anotacoes-na-3" class="form-control" name="anotacoes"></textarea>
                                        <input id="anexo-na-3" type="file" name="anexo" style="margin-top: 3%;" />
                                        <input id="id-anexo-3" type="hidden" name="idAnexo" />
                                    </div>
                                </div>
                                <div id="arquivo-download-na-3" style="display:none;" class="btn btn-primary">Anotacoções</div>

                                <div class="modal-footer">
                                    <button class="btn" id="btn-fechar-modal-na-3" data-dismiss="modal" aria-hidden="true">Fechar</button>
                                    <button class="btn btn-primary" type="submit" id="btn-adicionar-na-3">Salvar NA3</button>
                                </div>
                            </form>
                        </div>

                        <div id="modal-na-4" hidden>
                            <br>

                            <form id="form-cadastro-na-4" name="form-cadastro-na">
                                <div class="row" id="divNumeroNA-4" hidden>
                                    <div class="form-group col-sm-12">
                                        <h4 id="numeroNA-4"></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <input id="id-ocorrencia-na-4" type="hidden" name="idOcorrencia" />
                                    <div class="form-group col-sm-12">
                                        <label for="resumo-solicitacao">Resumo da Solicitação <span class="text-danger">*</span></label>
                                        <textarea type="text" id="resumo-solicitacao" class="form-control" name="resumoSolicitacao" maxlength="200" rows="2" required></textarea>
                                    </div>

                                    <input id="id-na-4" name="idNa" type="hidden" />
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="nome-solicitante">Nome do Solicitante <span class="text-danger">*</span></label>
                                        <input type="text" id="nome-solicitante-4" class="form-control" name="nomeSolicitante" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="telefone-solicitante">Telefone do Solicitante <span class="text-danger">*</span></label>
                                        <input type="tel" id="telefone-solicitante-4" class="form-control celular" name="telefoneSolicitante" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="itemContratoIdSelect">Item de Contrato <span class="text-danger">*</span></label>
                                        <select id="item-contrato-na-4" name="itemDeContrato4" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um item de contrato" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando itens de contrato...</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="serialItemContratoIdSelect">Número de Série do Contrato</label>
                                        <select id="serial-item-contrato-na-4" name="serialItemDeContrato" placeholder="Selecione um serial" class="form-control param selectPesquisar" data-placeholder="Selecione um Número de Série" style="width: 100%;">
                                            <option value="0" disabled selected>Selecione um contrato para buscar os Números de Séries</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group col-sm-6" style="margin-left: -14px;">
                                    <label for="">Local do Atendimento</label>
                                    <select id="local-atendimento-na-4" name="localAtendimento" class="form-control endereco-modal-na">
                                        <option value="1">Ponto Fixo RAZ</option>
                                        <option value="2" selected>Externo</option>
                                    </select>
                                </div>
                                <div id="endereco-na-4">
                                    <div class="row">
                                        <div class="form-group col-sm-6" style="margin-left: 14px;">
                                            <label for="">CEP</label>
                                            <input id="cep-na-4" class="form-control cep endereco-modal-na" name="cep" placeholder="Digite o CEP p/ preenchimento automático" disabled></input>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="estadoSelect">Estado</label>
                                            <select id="estado-na-4" name="estado" class="form-control endereco-modal-na param selectPesquisar" data-placeholder="Buscando estados..." style="width: 100%;">
                                                <option value="0" disabled selected>Buscando estados...</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Ordenar Prestadores mais próximo</label>
                                            <br>
                                            <input id="filtrar-na-4" type="checkbox" class="endereco-modal-na" name="filtra">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="cidadeEstado">Cidade</label>
                                            <select id="cidade-na-4" name="cidade" class="form-control endereco-modal-na param selectPesquisar" data-placeholder="Selecione um estado para filtrar as cidades" style="width: 100%;">
                                                <option value="0" disabled selected>Selecione um estado para filtrar as cidades</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Bairro</label>
                                            <input id="bairro-na-4" type="text" class="form-control endereco-modal-na" name="bairro">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="">Rua</label>
                                            <input id="rua-na-4" type="text" class="form-control endereco-modal-na" name="rua">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Número</label>
                                            <input id="numero-na-4" type="number" class="form-control endereco-modal-na" name="numero">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label for="">Complemento</label>
                                            <textarea id="complemento-na-4" class="form-control" name="complemento"></textarea>
                                        </div>
                                    </div>
                                    <hr />
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="">Serviço <span class="text-danger">*</span></label>
                                        <select id="servico-na-4" name="servico" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um serviço" style="width: 100%;" required>
                                            <option value="0" disabled selected>Selecione um contrato para buscar os serviços</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="">Prestador <span class="text-danger">*</span></label>
                                        <select id="prestador-na-4" name="prestador" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um prestador" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando prestadores...</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="">Recurso <span class="text-danger">*</span></label>
                                        <select id="recurso-na-4" name="recurso" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um recurso" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando recursos...</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="">Data Mínima Agendamento <span class="text-danger">*</span></label>
                                        <input id="data-minima-4" name="dataMinima" class="form-control param" type="text" style="cursor:pointer" title="Clique para selecionar a data" readonly required>
                                    </div>
                                </div>
                                <hr>

                                </br>
                                <div id="distancia-na-4">
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label>Distância Total</label>
                                            <input id="distancia-total-na-4" type="number" value='0' class="form-control" min="0" name="distanciaTotal">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Distância Bonificada</label>
                                            <input id="distancia-bonificada-na-4" type="number" value='50' class="form-control" min="0" name="distanciaBonificada" readonly>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Distância considerada</label>
                                            <input id="distancia-considerada-na-4" type="number" value='0' class="form-control" min="0" name="distanciaConsiderada" readonly>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Valor por KM considerado</label>
                                            <input id="valor-km-considerado-na-4" type="number" class="form-control" readonly name="tz_valor_km_considerado_cliente" value="2.50">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Valor total (deslocamento)</label>
                                            <input id="valor-total-deslocamento-na-4" type="number" value='0' min="0" class="form-control" min="0" name="valorTotalDeslocamento">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Taxa de visita</label>
                                            <input id="taxa-visita-na-4" type="number" class="form-control" name="taxaVisita">
                                        </div>
                                    </div>
                                </div>
                                </br>

                                <link-entity name="tz_base_instalada_cliente" from="tz_base_instalada_clienteid" to="tz_baseinstaladarastreador" />

                                <hr />
                                <div id="anotacao-na-4" class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="">Anotações</label>
                                        <textarea id="anotacoes-na-4" class="form-control" name="anotacoes"></textarea>
                                        <input id="anexo-na-4" type="file" name="anexo" style="margin-top: 3%;" />
                                        <input id="id-anexo-4" type="hidden" name="idAnexo" />
                                    </div>
                                </div>
                                <div id="arquivo-download-na-4" style="display:none;" class="btn btn-primary">Anotacoções</div>

                                <div class="modal-footer">
                                    <button class="btn" id="btn-fechar-modal-na-4" data-dismiss="modal" aria-hidden="true">Fechar</button>
                                    <button class="btn btn-primary" type="submit" id="btn-adicionar-na-4">Salvar NA4</button>
                                </div>
                            </form>
                        </div>

                        <div id="modal-na-5" hidden>
                            <br>

                            <form id="form-cadastro-na-5" name="form-cadastro-na">
                                <div class="row" id="divNumeroNA-5" hidden>
                                    <div class="form-group col-sm-12">
                                        <h4 id="numeroNA-5"></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <input id="id-ocorrencia-na-5" type="hidden" name="idOcorrencia" />
                                    <div class="form-group col-sm-12">
                                        <label for="resumo-solicitacao">Resumo da Solicitação <span class="text-danger">*</span></label>
                                        <textarea type="text" id="resumo-solicitacao-5" class="form-control" name="resumoSolicitacao" maxlength="200" rows="2" required></textarea>
                                    </div>

                                    <input id="id-na-5" name="idNa" type="hidden" />
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="nome-solicitante">Nome do Solicitante <span class="text-danger">*</span></label>
                                        <input type="text" id="nome-solicitante-5" class="form-control" name="nomeSolicitante" required>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="telefone-solicitante">Telefone do Solicitante <span class="text-danger">*</span></label>
                                        <input type="tel" id="telefone-solicitante-5" class="form-control celular" name="telefoneSolicitante" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="itemContratoIdSelect">Item de Contrato <span class="text-danger">*</span></label>
                                        <select id="item-contrato-na-5" name="itemDeContrato5" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um item de contrato" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando itens de contrato...</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="serialItemContratoIdSelect">Número de Série do Contrato</label>
                                        <select id="serial-item-contrato-na-5" name="serialItemDeContrato" placeholder="Selecione um serial" class="form-control param selectPesquisar" data-placeholder="Selecione um Número de Série" style="width: 100%;">
                                            <option value="0" disabled selected>Selecione um contrato para buscar os Números de Séries</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group col-sm-6" style="margin-left: -14px;">
                                    <label for="">Local do Atendimento</label>
                                    <select id="local-atendimento-na-5" name="localAtendimento" class="form-control endereco-modal-na">
                                        <option value="1">Ponto Fixo RAZ</option>
                                        <option value="2" selected>Externo</option>
                                    </select>
                                </div>
                                <div id="endereco-na-5">
                                    <div class="row">

                                        <div class="form-group col-sm-6" style="margin-left: 14px;">
                                            <label for="">CEP</label>
                                            <input id="cep-na-5" class="form-control cep endereco-modal-na" name="cep" placeholder="Digite o CEP p/ preenchimento automático" disabled></input>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="estadoSelect">Estado</label>
                                            <select id="estado-na-5" name="estado" class="form-control endereco-modal-na param selectPesquisar" data-placeholder="Buscando estados..." style="width: 100%;">
                                                <option value="0" disabled selected>Buscando estados...</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Ordenar Prestadores mais próximo</label>
                                            <br>
                                            <input id="filtrar-na-5" type="checkbox" class="endereco-modal-na" name="filtra">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="cidadeEstado">Cidade</label>
                                            <select id="cidade-na-5" name="cidade" class="form-control endereco-modal-na param selectPesquisar" data-placeholder="Selecione um estado para filtrar as cidades" style="width: 100%;">
                                                <option value="0" disabled selected>Selecione um estado para filtrar as cidades</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Bairro</label>
                                            <input id="bairro-na-5" type="text" class="form-control endereco-modal-na" name="bairro">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="">Rua</label>
                                            <input id="rua-na-5" type="text" class="form-control endereco-modal-na" name="rua">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Número</label>
                                            <input id="numero-na-5" type="number" class="form-control endereco-modal-na" name="numero">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label for="">Complemento</label>
                                            <textarea id="complemento-na-5" class="form-control" name="complemento"></textarea>
                                        </div>
                                    </div>
                                    <hr />
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="">Serviço <span class="text-danger">*</span></label>
                                        <select id="servico-na-5" name="servico" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um serviço" style="width: 100%;" required>
                                            <option value="0" disabled selected>Selecione um contrato para buscar os serviços</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="">Prestador <span class="text-danger">*</span></label>
                                        <select id="prestador-na-5" name="prestador" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um prestador" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando prestadores...</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="">Recurso <span class="text-danger">*</span></label>
                                        <select id="recurso-na-5" name="recurso" class="form-control" class="form-control param selectPesquisar" data-placeholder="Selecione um recurso" style="width: 100%;" required>
                                            <option value="0" disabled selected>Buscando recursos...</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="">Data Mínima Agendamento <span class="text-danger">*</span></label>
                                        <input id="data-minima-5" name="dataMinima" class="form-control param" type="text" style="cursor:pointer" title="Clique para selecionar a data" readonly required>
                                    </div>
                                </div>
                                <hr>

                                </br>
                                <div id="distancia-na-5">

                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label>Distância Total</label>
                                            <input id="distancia-total-na-5" type="number" value='0' class="form-control" min="0" name="distanciaTotal">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Distância Bonificada</label>
                                            <input id="distancia-bonificada-na-5" type="number" value='50' class="form-control" min="0" name="distanciaBonificada" readonly>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Distância considerada</label>
                                            <input id="distancia-considerada-na-5" type="number" value='0' class="form-control" min="0" name="distanciaConsiderada" readonly>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Valor por KM considerado</label>
                                            <input id="valor-km-considerado-na-5" type="number" class="form-control" readonly name="tz_valor_km_considerado_cliente" value="2.50">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Valor total (deslocamento)</label>
                                            <input id="valor-total-deslocamento-na-5" type="number" value='0' min="0" class="form-control" min="0" name="valorTotalDeslocamento">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Taxa de visita</label>
                                            <input id="taxa-visita-na-5" type="number" class="form-control" name="taxaVisita">
                                        </div>
                                    </div>
                                </div>
                                </br>

                                <link-entity name="tz_base_instalada_cliente" from="tz_base_instalada_clienteid" to="tz_baseinstaladarastreador" />

                                <hr />
                                <div id="anotacao-na-5" class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="">Anotações</label>
                                        <textarea id="anotacoes-na-5" class="form-control" name="anotacoes"></textarea>
                                        <input id="anexo-na-5" type="file" name="anexo" style="margin-top: 3%;" />
                                        <input id="id-anexo-5" type="hidden" name="idAnexo" />
                                    </div>
                                </div>
                                <div id="arquivo-download-na-5" style="display:none;" class="btn btn-primary">Anotacoções</div>

                                <div class="modal-footer">
                                    <button class="btn" id="btn-fechar-modal-na-5" data-dismiss="modal" aria-hidden="true">Fechar</button>
                                    <button class="btn btn-primary" type="submit" id="btn-adicionar-na-5">Salvar NA5</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="os" hidden>
                        <h3 id="header-os-na"></h3>
                        <form id="form-os-na">
                            <input id="id-os-na" name="idOS" type="hidden"></input>
                            <input id="id-item-os-na" name="idItemOS" type="hidden"></input>
                            <div class="col-md-12">
                                <div class="row">

                                    <div class="col-md-6">
                                        <label>Data de Criação</label>
                                        <h4 id="data-criacao-os-na"></h4>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Data de Modificação</label>
                                        <h4 id="data-modificacao-os-na"></h4>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Tipo de Serviço</label>
                                        <h4 id="tipo-servico-os-na"></h4>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Modificado Por</label>
                                        <h4 id="modificado-por-os-na"></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Valor Total</label>
                                        <h4 id="valor-total-os-na"></h4>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Razão do Status</label>
                                        <select id="razao-status-os-na" class="form-control" name="statusAprovacao">
                                            <option value="1">Em aberto</option>
                                            <option value="419400000">Fechado</option>
                                            <option value="2">Inativo(a)</option>
                                            <option value="419400001">Não realizado</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Observações</label>
                                        <textarea id="observacoes-os-na" class="form-control" name="observacoesOS"></textarea>
                                    </div>
                                </div>

                                <button id="btn-submit-form-os-na" class="btn btn-primary" style="float:left; margin-top: 2%; margin-bottom: 2%;">Alterar OS</button>

                                <div class="row">
                                    <div class="col-md-12 table-responsive">
                                        <table class="table-bordered table " id="tabela-itens-os-na">
                                            <thead>
                                                <th>Nome</th>
                                                <th>Item</th>
                                                <th>Quant.</th>
                                                <th>Valor Total</th>
                                                <th>Status Aprovação</th>
                                                <th id="dropdown-modal-os" class="dropdown">
                                                    Ações
                                                    <div class="dropdown-toggle-modal-os" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-align: center;">
                                                        <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                                    </div>
                                                    <div id="btn-modal-adicionar-item-os" class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="min-width: 10%;">
                                                        <div data-toggle="modal" data-target="#modal-item-os" onclick="limparCamposCadastroItemOS()" style="color: black; font-weight: bold; cursor: pointer;">
                                                            Adicionar Item
                                                        </div>
                                                    </div>
                                                </th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row"></div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modalHistoricoStatusAF" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 2000;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Histórico Status AF</h3>
            </div>
            <div class='modal-body'>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12">
                        <table class="table-responsive table-bordered table " id="tabelaHistoricoStatusAF">
                            <thead>
                                <th>Data do evento</th>
                                <th>Observações</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="loading" style="display: none;">
    <div class="loader"></div>
</div>

<?php $this->load->view('comercial_televenda/pedido/resumoCotacaoSAC'); ?>
<script src="http://maps.google.com/maps/api/js"></script>

<script>
    $('.phone').on('input', function() {
        var telefone = $(this).val().replace(/\D/g, '');
        if (telefone.length === 11) {
            $(this).val('(' + telefone.substring(0, 2) + ') ' + telefone.substring(2, 3) + telefone.substring(3, 7) + '-' + telefone.substring(7, 11));
        } else if (telefone.length > 11) {
            $(this).val('(' + telefone.substring(0, 2) + ') ' + telefone.substring(2, 3) + telefone.substring(3, 7) + '-' + telefone.substring(7, 11));
        } else if (telefone.length > 7) {
            $(this).val('(' + telefone.substring(0, 2) + ') ' + telefone.substring(2, 3) + telefone.substring(3, 7) + '-' + telefone.substring(7, telefone.length));
        } else if (telefone.length > 3) {
            $(this).val('(' + telefone.substring(0, 2) + ') ' + telefone.substring(2, 3) + telefone.substring(3, telefone.length));
        } else {
            $(this).val('(' + telefone);
        }
    });
</script>