<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 0;">
    <div class="col-md-3" style="padding-left: 0;">

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">

                    <div class="input-container">
                        <label for="filtrar-atributos-ocorr">Ocorrência:</label>
                        <input type="text" name="filtrar-atributos-ocorr" class="form-control" placeholder="Filtrar Ocorrência" id="filtrar-atributos-ocorr" />
                    </div>

                    <div class="input-container status" id="statusContainer" style='margin-bottom: 20px; position: relative;'>
                        <label for="informacoesStatus">Status Ocorrência:</label>
                        <select name="informacoesStatus" id="informacoesStatus" style="width: 100%;" class="form-control">
                            <option value="0" selected>Abertas</option>
                            <option value="1">Resolvidas</option>
                            <option value="2">Canceladas</option>
                        </select>
                    </div>


                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltroOcorrencias" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo" style="padding: 0;">

        <div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>
            <h3>
                <b id="titulo-card" style="margin-left: 5px;">Ocorrências: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">

                    <button style="margin-right: 10px; margin-bottom: 5px;" type="button" data-toggle="modal" data-target="#modalOcorrencia" id="modalOcorrenciaBt" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar Ocorrência</button>

                    <button class="btn btn-primary" id="btnTicketCliente" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i> Novo Ticket</button>

                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
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
                <select id="select-quantidade-por-contatos-corporativos" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value=10 selected>10</option>
                    <option value=25>25</option>
                    <option value=50>50</option>
                    <option value=100>100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div id="emptyMessageCadastroClientes" style="display: none;">
                <h4 style="padding-left: 0; padding-right: 0;"><b>Nenhum dado a ser listado.</b></h4>
            </div>
            <div style="position: relative;">
                <div id="loadingMessageTecnologias" class="loadingMessage" style="display: none;">
                    <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
                </div>

                <div class="wrapperContatos">
                    <div id="tableContatos" class="ag-theme-alpine my-grid-contatos" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAlterarStatusOcorrencia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="myModalLabel">Alterar Status da Ocorrência</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_modalAlterarStatusOcorrencia" method="POST">
                <input type="hidden" name="Id" class="param2" id="IdEdit">
                <div class="modal-body">
                    <div class="pos-venda-container" style="padding: 0">

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Status Atual</div>
                            <div class="pos-venda-info-group">
                                <input name="statusAtualOcorrencia" type="text" class="form-control param2" id="statusAtualOcorrencia" maxlength="255" disabled>
                            </div>
                        </div>

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Tipo de ocorrência</div>
                            <div class="pos-venda-info-group">
                                <select name="modalAlterarStatusOcorrencia_select" id="modalAlterarStatusOcorrencia_select" class="form-control param2 selectPesquisar" data-placeholder="Selecione um tipo de ocorrência" style="width:100%" required>
                                    <option value="419400008">Não iniciado</option>
                                    <option value="419400004">Novo Contrato</option>
                                    <option value="3">Aguardando Cliente</option>
                                    <option value="419400011">Aguardando Comercial</option>
                                    <option value="419400009">Aguardando Operações</option>
                                    <option value="419400012">Aguardando Outras Equipes</option>
                                    <option value="419400010">Aguardando Peças</option>
                                    <option value="419400005">Pendente Instalação / Manutenção</option>
                                    <option value="1">Em Andamento</option>
                                    <option value="2">Suspenso</option>
                                    <option value="4">Pesquisando</option>
                                    <option value="419400001">Enviado para operadora</option>
                                    <option value="419400002">Inconsistência nas informações</option>
                                    <option value="419400013">Falta de Informação</option>
                                    <option value="419400014">Não resolvido</option>
                                    <option value="419400007">Implantação concluída</option>
                                    <option value="5">Problema Resolvido</option>
                                    <option value="1000">Informações Fornecidas</option>
                                    <option value="419400003">Ativação da(s) linha(s) concluída</option>
                                    <option value="419400015">Resolvido com pendências do cliente</option>
                                    <option value="2000">Mesclado</option>
                                    <option value="6">Cancelada</option>
                                    <option value="419400000">Cancelada - Logística Reversa</option>
                                    <option value="419400016">Cancelado pelo cliente</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex;">
                    <button class="btn btn-secondary" data-dismiss="modal" style="margin-right: auto;" aria-hidden="true">Fechar</button>
                    <button class="btn btn-success" id="btnSubmitEditStatusOcorrencia" type="submit">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="novo_ticket" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="infoModalLabel">Adicionar Ticket Fale Conosco</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="ContactForm" method="POST">
                <div class="modal-body">
                    <div class="pos-venda-container" style="padding: 0">
                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Usuário <span class="text-danger">*</span></div>
                            <div class="pos-venda-info-group">
                                <select class="select_usuario form-control param2 selectPesquisar" name="id_usuario" data-placeholder="Selecione um Usuário" style="width: 100% !important;" id="id_usuario">
                                    <option value="" disabled selected></option>
                                </select>
                            </div>
                        </div>

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Placa <span class="text-danger">*</span></div>
                            <div class="pos-venda-info-group">
                                <select id="placa" class="form-control param2 selectPesquisar" data-placeholder="Selecione a placa" name="placa" style="width: 100% !important;">
                                    <option value="" disabled selected>Selecione uma placa</option>
                                </select>
                            </div>
                        </div>

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Assunto <span class="text-danger">*</span></div>
                            <div class="pos-venda-info-group">
                                <input type="text" class="form-control param2" name="assunto" id="assunto" placeholder="Assunto" style="width: 100% !important;" autocomplete="off" maxlength="100" />
                            </div>
                        </div>

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Categoria <span class="text-danger">*</span></div>
                            <div class="pos-venda-info-group">
                                <select id="departamento" class="form-control param2 selectPesquisar" style="width: 100% !important;" name="departamento">
                                    <option value="" disabled selected>Selecione a Categoria</option>
                                    <option value="Suporte Técnico">Suporte Técnico</option>
                                    <option value="Atendimento ao Cliente">Atendimento ao Cliente</option>
                                    <option value="Financeiro / Cobrança">Financeiro</option>
                                    <option value="Vendas / Novos negócios">Vendas</option>
                                </select>
                            </div>
                        </div>

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Prioridade <span class="text-danger">*</span></div>
                            <div class="pos-venda-info-group">
                                <select type="text" id="prioridade" name="prioridade" data-placeholder="Prioridade" class="form-control param2 selectPesquisar" style="width: 100% !important;">
                                    <option value="" disabled selected>Prioridade</option>
                                    <option value="1">Baixa</option>
                                    <option value="2">Média</option>
                                    <option value="3">Alta</option>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="id_cliente" id="input_id_cliente">
                        <input type="hidden" name="usuario" id="input_usuario">
                        <input type="hidden" name="nome_usuario" id="input_nome_usuario">

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Descrição <span class="text-danger">*</span></div>
                            <div class="pos-venda-info-group">
                                <textarea name="descricao" rows="6" placeholder="Descrição" id="descricao" class="form-control param2" style="resize: vertical;"></textarea>
                                <span class="label" id="content-countdown" style="margin-left: auto; color:black;" title="500">0</span>
                            </div>
                        </div>

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Arquivo</div>
                            <div class="pos-venda-info-group">
                                <input type="file" name="arquivo" id="arquivo" class="form-control param2" style="word-wrap: break-word; max-width: 100%;">
                                <span class="help-block" style="font-size: 11px;">*Formatos suportados: pdf, jpg, png e jpeg</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex;">
                    <button class="btn btn-secondary" data-dismiss="modal" style="margin-right: auto;" aria-hidden="true">Fechar</button>
                    <button class="btn btn-success" id="btnSalvarTicket" type="submit">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEncerramentoOcorrencia" tabindex="-1" role="dialog" aria-labelledby="modalEncerramentoOcorrenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalEncerramentoOcorrenciaLabel">Encerrar Ocorrência</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_encerrarOcorrencia" method="POST">
                <div class="modal-body">
                    <div class="pos-venda-container" style="padding: 0">
                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Tipo de Resolução*</div>
                            <div class="pos-venda-info-group">
                                <select name="selectIdTipoResolucao" id="selectIdTipoResolucao" class="form-control selectPesquisar encerarOcorrencia" data-placeholder="Selecione o tipo de resolução" style="width: 100%;" required>
                                    <option value="0" title="Selecione um tipo de resolução" disabled>Selecione o tipo de resolução</option>
                                    <option value="419400007" title="Implantação concluída">Implantação concluída</option>
                                    <option value="5" title="Problema Resolvido">Problema Resolvido</option>
                                    <option value="1000" title="Informações Fornecidas">Informações Fornecidas</option>
                                    <option value="419400003" title="Ativação da(s) linha(s) concluída">Ativação da(s) linha(s) concluída</option>
                                    <option value="419400015" title="Resolvido com pendências do cliente">Resolvido com pendências do cliente</option>
                                </select>
                            </div>
                        </div>
                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Resolução</div>
                            <div class="pos-venda-info-group">
                                <input type="text" name="resolucaoOcorrencia" id="resolucaoOcorrencia" class="form-control encerarOcorrencia" required>
                            </div>
                        </div>
                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Comentários</div>
                            <div class="pos-venda-info-group">
                                <textarea id="descricaoEncerrarOcorrencia" name="descricaoEncerrarOcorrencia" class="form-control paramAnotation encerarOcorrencia" rows="6" autocomplete="off"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex;">
                    <button class="btn btn-secondary" data-dismiss="modal" style="margin-right: auto;" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" id="eventoEncerrarOcorrencia_bt" type="button">Encerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalOcorrenciaEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="myModalLabel">Editar Ocorrência</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_ocorrenciaEdit" method="POST">
                <input type="hidden" name="Id" class="param2" id="IdEdit">
                <div class="modal-body">
                    <div class="pos-venda-container" style="padding: 0">
                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Número do ticket</div>
                            <div class="pos-venda-info-group">
                                <input name="TicketNumber" type="text" class="form-control param2" id="TicketNumberEdit" maxlength="255">
                            </div>
                        </div>

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Assunto</div>
                            <div class="pos-venda-info-group">
                                <select name="Assunto" id="AssuntoEdit" class="form-control param2 selectPesquisar" data-placeholder="Selecione um assunto" style="width:100%">
                                    <option value="0" disabled selected>Selecione um assunto</option>
                                </select>
                            </div>
                        </div>

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Tipo de ocorrência</div>
                            <div class="pos-venda-info-group">
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

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Origem da ocorrência</div>
                            <div class="pos-venda-info-group">
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

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Tecnologia</div>
                            <div class="pos-venda-info-group">
                                <select name="Tecnologia" id="TecnologiaEdit" class="form-control param2 selectPesquisar" data-placeholder="Selecione a tecnologia" style="width:100%" required>
                                    <option value="0" disabled selected>Selecione a tecnologia</option>
                                </select>
                            </div>
                        </div>

                        <div class="pos-venda-section">
                            <div class="pos-venda-info-group" style="display: block">
                                <input id="box-fila" type="checkbox" style="height: 12px !important;" /> <strong style="padding-bottom: 20%;"> Alterar fila </strong>
                                <div class="collapse" id="filas-row" style="padding: 2%;">
                                    <p>NOTA: Se a fila for alterada, o assunto NÃO será alterado.
                                        Como uma fila está atrelada a um assunto,
                                        ele irá sobrepor a fila selecionada abaixo,
                                        então ao alterar a fila NÃO será alterado o assunto e vice-versa.</p>
                                    <div class="row">
                                        <label>Filas</label>
                                        <select name="filas" id="filas" class="form-control param filas" style="width:100%">
                                        </select>
                                        <input type="hidden" id="fila-nome">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pos-venda-section">
                            <div class="pos-venda-section-title">Descrição</div>
                            <div class="pos-venda-info-group">
                                <textarea id="DescricaoEdit" name="Descricao" class="form-control param2" rows="6" autocomplete="off"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex;">
                    <button class="btn btn-secondary" data-dismiss="modal" style="margin-right: auto;" aria-hidden="true">Fechar</button>
                    <button class="btn btn-success" id="btnSubmitEdit" type="submit">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalInformacoesOcorrencia" tabindex="-1" aria-labelledby="myModalBuscarContratoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="tituloInfomacaoOcorrencia">Informações ocorrência</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="pos-venda-container">
                    <div class="pos-venda-section">
                        <div class="pos-venda-section-title">Informações Básicas</div>
                        <div class="pos-venda-info-group">
                            <div class="pos-venda-info"><strong>Proprietário:</strong> <span id="infoOcorrenciaProprietario"></span></div>
                            <div class="pos-venda-info"><strong>Número da ocorrência:</strong> <span id="infoOcorrenciaNumeroOcorrencia"></span></div>
                            <div class="pos-venda-info"><strong>Origem da ocorrência:</strong> <span id="infoOcorrenciaOrigemOcorrencia"></span></div>
                        </div>
                    </div>

                    <div class="pos-venda-section">
                        <div class="pos-venda-section-title">Detalhes da Ocorrência</div>
                        <div class="pos-venda-info-group">
                            <div class="pos-venda-info"><strong>Assunto:</strong> <span id="infoOcorrenciaAssunto"></span></div>
                            <div class="pos-venda-info"><strong>Assunto Primário:</strong> <span id="infoOcorrenciaAssuntoPrimario"></span></div>
                            <div class="pos-venda-info"><strong>Tipo de ocorrência:</strong> <span id="infoOcorrenciaTipoOcorrencia"></span></div>
                        </div>
                    </div>

                    <div class="pos-venda-section">
                        <div class="pos-venda-section-title">Atendimento</div>
                        <div class="pos-venda-info-group">
                            <div class="pos-venda-info"><strong>Tipo de atendimento:</strong> <span id="infoOcorrenciaTipoAtendimento"></span></div>
                            <div class="pos-venda-info"><strong>Tecnologia:</strong> <span id="infoOcorrenciaTecnologia"></span></div>
                            <div class="pos-venda-info"><strong>Fila atual:</strong> <span id="infoOcorrenciaFilaAtual"></span></div>
                            <div class="pos-venda-info"><strong>Última fila:</strong> <span id="infoOcorrenciaUltimaFila"></span></div>
                            <div class="pos-venda-info"><strong>Fila de atendimento:</strong> <span id="infoOcorrenciaFilaAtendimento"></span></div>
                            <div class="pos-venda-info"><strong>Status:</strong> <span id="infoOcorrenciaStatus"></span></div>
                            <div class="pos-venda-info"><strong>Razão do Status:</strong> <span id="infoOcorrenciaRazaoStatus"></span></div>
                        </div>
                    </div>

                    <div class="pos-venda-section">
                        <div class="pos-venda-section-title">Informações Adicionais</div>
                        <div class="pos-venda-info-group">
                            <div class="pos-venda-info"><strong>Criado por:</strong> <span id="infoOcorrenciaCriador"></span></div>
                            <div class="pos-venda-info"><strong>Data de criação:</strong> <span id="infoOcorrenciaDataCriacao"></span></div>
                            <div class="pos-venda-info"><strong>Modificado por:</strong> <span id="infoOcorrenciaModificador"></span></div>
                            <div class="pos-venda-info"><strong>Data de modificação:</strong> <span id="infoOcorrenciaDataModificacao"></span></div>
                        </div>
                    </div>

                    <div class="pos-venda-section">
                        <div class="pos-venda-section-title">Descrição e Observações</div>
                        <div class="pos-venda-info-group">
                            <div class="pos-venda-info"><strong>Descrição do Assunto:</strong> <span id="infoOcorrenciaDescricaoAssunto"></span></div>
                            <div class="pos-venda-info"><strong>Observações:</strong> <span id="infoOcorrenciaObservacao"></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div id="modalOcorrencia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" style="margin-top: 0px;" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel" class="modal-title">Cadastrar Ocorrência</h3>
            </div>
            <form id="form_ocorrencia" method="POST">
                <input type="hidden" name="Id" id="Id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="pos-venda-section">
                                <div class="pos-venda-info-group" style="display: block">
                                    <input id="box-fila-suporte" type="checkbox" style="height: 12px !important;" /> <strong style="padding-bottom: 20%;"> Cadastrar Ocorrência como Suporte</strong>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 form-group input-container">
                                    <label for="Assunto">Assunto</label>
                                    <select name="Assunto" id="Assunto" class="form-control param selectPesquisar" data-placeholder="Selecione um assunto" style="width: 100%;" required>
                                        <option value="0" disabled selected>Selecione um assunto</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 form-group input-container">
                                    <label for="TipoOcorrencia">Tipo de ocorrência</label>
                                    <select name="TipoOcorrencia" id="TipoOcorrencia" class="form-control param selectPesquisar" data-placeholder="Selecione um tipo de ocorrência" style="width: 100%;" required>
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
                                <div class="col-md-12 form-group input-container">
                                    <label for="OrigemOcorrencia">Origem da ocorrência</label>
                                    <select name="OrigemOcorrencia" id="OrigemOcorrencia" class="form-control param selectPesquisar" data-placeholder="Selecione a origem da ocorrência" style="width: 100%;" required>
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
                                <div class="col-md-12 form-group input-container">
                                    <label for="Tecnologia">Tecnologia</label>
                                    <select name="Tecnologia" id="Tecnologia" class="form-control param selectPesquisar" data-placeholder="Selecione a tecnologia" style="width: 100%;" required>
                                        <option value="0" disabled selected>Selecione uma tecnologia</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group input-container">
                                    <label for="Descricao">Descrição</label><br>
                                    <textarea id="Descricao" name="Descricao" class="param form-control" rows="6" autocomplete="off" style="resize: none">Analista:&#10;Assunto / Problema:&#10;Nome/Telefone do solicitante:&#10;Realizado:&#10;Pendência:&#10;Observações:&#10;ID da conversa:</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer footer-group">
                    <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-success" id="btnSubmit" style="margin-left: auto;">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="addClientes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" id='formAddClientes' data-edicao="nao" data-edicao-id-usuario="0">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="status" id="status">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleTecnologias">Cadastrar Analista de Suporte</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class='row'>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="idCrm">CRM: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="idCrm" id="idCrm" placeholder="Digite o CRM" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="nomeUsuario">Nome de Usuário: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="nomeUsuario" id="nomeUsuario" placeholder="Digite o nome de usuário" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="usuarioSistema">Usuário do Sistema:</label>
                                <input class="form-control" type="text" name="usuarioSistema" id="usuarioSistema" placeholder="Digite o usuário do sistema" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="nomeCompleto">Nome Completo: <span class="text-danger">*</label>
                                <input class="form-control" type="text" name="nomeCompleto" id="nomeCompleto" placeholder="Digite o nome completo" required minlength="3" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="apelido">Apelido:</label>
                                <input class="form-control" type="text" name="apelido" id="apelido" placeholder="Digite o apelido" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="titulo">Título:</label>
                                <input class="form-control" type="text" name="titulo" id="titulo" placeholder="Digite o título" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="emailPrimario">Email Primário: <span class="text-danger">*</span></label>
                                <input class="form-control" type="email" name="emailPrimario" id="emailPrimario" placeholder="Digite o email primário" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="email2">Email Secundário:</label>
                                <input class="form-control" type="email" name="email2" id="email2" placeholder="Digite o email secundário" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="emailAlertaMovel">Email de Alerta Móvel:</label>
                                <input class="form-control" type="email" name="emailAlertaMovel" id="emailAlertaMovel" placeholder="Digite o email de alerta móvel" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="emailYammer">Email Yammer:</label>
                                <input class="form-control" type="email" name="emailYammer" id="emailYammer" placeholder="Digite o email Yammer" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="telefoneCelular">Telefone Celular: <span class="text-danger">*</span></label>
                                <input class="form-control" type="tel" name="telefoneCelular" id="telefoneCelular" placeholder="Digite o telefone celular" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="telefonePrincipal">Telefone Principal: <span class="text-danger">*</span></label>
                                <input class="form-control" type="tel" name="telefonePrincipal" id="telefonePrincipal" placeholder="Digite o telefone principal" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="statusEmailPrincipal">Status do Email Principal: <span class="text-danger">*</span></label>
                                <select class="form-control" name="statusEmailPrincipal" id="statusEmailPrincipal" required>
                                    <option value="0">Aprovação Pendente</option>
                                    <option value="1" selected>Aprovado</option>
                                    <option value="2">Rejeitado</option>
                                    <option value="3">Vazio</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="statusConvite">Status do Convite: <span class="text-danger">*</span></label>
                                <select class="form-control" name="statusConvite" id="statusConvite" required>
                                    <option value="0" selected>Convidado</option>
                                    <option value="1">Convite Aceito</option>
                                    <option value="2">Convite não Enviado</option>
                                    <option value="3">Convite Quase Vencido</option>
                                    <option value="4">Convite Recusado</option>
                                    <option value="5">Convite Revogado</option>
                                    <option value="6">Convite Vencido</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="unidadeNegocios">Unidade de Negócios: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="unidadeNegocios" id="unidadeNegocios" placeholder="Digite a unidade de negócios" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="cargo">Cargo: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="cargo" id="cargo" placeholder="Digite o cargo" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="departamento">Departamento: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="departamento" id="departamento" placeholder="Digite o departamento" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="aprovadorDescontoReembolso">Aprovador de Desconto/Reembolso: <span class="text-danger">*</span></label>
                                <select class="form-control" name="aprovadorDescontoReembolso" id="aprovadorDescontoReembolso" required>
                                    <option value="true">Sim</option>
                                    <option value="false">Não</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="caixaCorreio">Caixa de Correio:</label>
                                <input class="form-control" type="email" name="caixaCorreio" id="caixaCorreio" placeholder="Digite o caixa de correio" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="endereco">Endereço: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="endereco" id="endereco" placeholder="Digite o endereço" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="estado">Estado: <span class="text-danger">*</span></label>
                                <select class="form-control" name="estado" id="estado" required>
                                    <option value="" selected disabled>Selecione o estado</option>
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
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="cidade">Cidade: <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="cidade" id="cidade" placeholder="Digite a cidade" required />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="taxaCambio">Taxa de Câmbio:</label>
                                <input class="form-control" type="number" step="0.01" name="taxaCambio" id="taxaCambio" placeholder="Digite a taxa de câmbio" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="vendedor">Vendedor:</label>
                                <input class="form-control" type="text" name="vendedor" id="vendedor" placeholder="Digite o vendedor" />
                            </div>
                            <div class="col-md-6 input-container form-group" style="height: 59px !important;">
                                <label for="gerente">Gerente:</label>
                                <input class="form-control" type="text" name="gerente" id="gerente" placeholder="Digite o gerente" />
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id='btnSalvarCliente'>Salvar</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<style>
    .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left-color: #7983ff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">

<script>
    //var RouterOCR = '';
    // var Router = '<?= site_url('Empresas/ContatosCorporativos') ?>';
    var BaseURL = '<?= base_url('') ?>';

    $(document).ready(async function() {

        var result = [];

        let edicaoModal = 'nao';
        let id_usuario = 0;

        //cliente_selecionado_atual.clienteAuxiliarModel.idCrm = '6bc95e40-41b4-de11-9386-00237de5099c' ///lembrar de apagar

        let usuario_logado = '<?= site_url("PaineisOmnilink") ?>'

        async function buscarDadosAgGrid() {
            $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
            await $.ajax({
                url: '<?= site_url("PaineisOmnilink") ?>' + '/listar_ocorrencias/' + cliente_selecionado_atual?.clienteAuxiliarModel?.idCrm + '/' + $('#informacoesStatus').val(),
                type: 'POST',
                data: {
                    draw: "1",
                    length: 100,
                    start: 0,
                    'search[value]': $('#filtrar-atributos-ocorr').val()
                },
                success: function(response) {
                    result = response.data;
                    updateData(result)
                },
                error: function(xhr, status, error) {
                    showAlert("error", "Erro ao receber dados, contate o suporte técnico!");
                }
            });
        }

        const gridOptions = {
            columnDefs: [{
                    headerName: "ID",
                    field: "ticketnumber",
                    width: 90,
                    valueGetter: function(params) {
                        return params.data.ticketnumber !== null ? params.data.ticketnumber : "";
                    }
                },
                {
                    headerName: "Assunto",
                    field: "subject",
                    valueGetter: function(params) {
                        return params.data.subject !== null ? params.data.subject : "";
                    }
                },
                {
                    headerName: "Tipo de Ocorrência",
                    field: "type",
                    valueGetter: function(params) {
                        return params.data.type !== null ? params.data.type : "";
                    }
                },
                {
                    headerName: "Origem de Ocorrência",
                    field: "origin",
                    valueGetter: function(params) {
                        return params.data.origin !== null ? params.data.origin : "";
                    }
                },
                {
                    headerName: "Tecnologia",
                    field: "technology",
                    valueGetter: function(params) {
                        return params.data.technology !== null ? params.data.technology : "";
                    }
                },
                {
                    headerName: "Fila Atual",
                    field: "actualqueue",
                    valueGetter: function(params) {
                        return params.data.actualqueue !== null ? params.data.actualqueue : "";
                    }
                },
                {
                    headerName: "Razão Status",
                    field: "cause.text",
                    valueGetter: function(params) {
                        return params.data.cause.text !== null ? params.data.cause.text : "";
                    }
                },
                {
                    headerName: "Tipo de Serviço",
                    field: "servicetype",
                    valueGetter: function(params) {
                        return params.data.servicetype !== null ? params.data.servicetype : "";
                    }
                },
                {
                    headerName: "Descrição",
                    field: "description",
                    valueGetter: function(params) {
                        return params.data.description !== null ? params.data.description : "";
                    }
                },
                {
                    headerName: "Criado Por",
                    field: "created.by",
                    valueGetter: function(params) {
                        return params.data.created.by !== null ? params.data.created.by : "";
                    }
                },
                {
                    headerName: "Criado em",
                    field: "created.on",
                    valueGetter: function(params) {
                        return params.data.created.on !== null ? params.data.created.on : "";
                    }
                },
                {
                    headerName: "Modificado Por",
                    field: "modified.by",
                    valueGetter: function(params) {
                        return params.data.modified.by !== null ? params.data.modified.by : "";
                    }
                },
                {
                    headerName: "Modificado em",
                    field: "modified.on",
                    valueGetter: function(params) {
                        return params.data.modified.on !== null ? params.data.modified.on : "";
                    }
                },
                {
                    headerName: 'Ações',
                    width: 80,
                    pinned: 'right',
                    cellClass: "actions-button-cell",
                    suppressMenu: true,
                    sortable: false,
                    filter: false,
                    resizable: false,
                    cellRenderer: function(options) {
                        let data = options.data;

                        let tableId = "tableContatos";
                        let dropdownId = "dropdown-menu" + data.id;
                        let buttonId = "dropdownMenuButton_" + data.id;
                        let i = options.rowIndex;
                        let ajusteAltura = 0;
                        let paginaAtual = gridOptions.api.paginationGetCurrentPage();
                        let qtd = $('#select-quantidade-por-contatos-corporativos').val()


                        if (paginaAtual > 0) {
                            i = i - (paginaAtual) * qtd
                        }

                        if (i > 9) {
                            i = 9;
                        }

                        if (i > 4) {
                            ajusteAltura = 530;
                        } else if (i > 2) {
                            ajusteAltura = 40;
                        } else {
                            ajusteAltura = 0;
                        }
                        return `
                            <div class="dropdown dropdown-table" data-tableId=${tableId}>
                                <button class="btn btn-dropdown dropdown-toggle menu-drop-abrir-fechar" type="button" onclick=fecharDrop() id="${buttonId}" row_id="${data.id}" nome="${data.placa_lida}" id="${data.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                                    <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute; top: calc(${90 - ajusteAltura}%);" aria-labelledby="${buttonId}">
                                   
                                    <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                                        <a style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" data-toggle="modal" data-id=${data.id} class="modalEditarOcorrencia_acoes" title="Editar"> Editar</a>
                                    </div>

                                    <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                                        <a style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" data-toggle="modal" ticket=${data.ticketnumber} data-id=${data.id} class="modalInformacoesOcorrencia_acoes" title="Informações">Informações</a>
                                    </div>

                                    <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                                        <a style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" data-toggle="modal" data-id=${data.id} class="modalResolverOcorrencia_acoes" title="Resolver Ocorrência">Resolver Ocorrência</a>
                                    </div>

                                    <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                                        <a style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" data-id=${data.id}  data-atual_text=${JSON.stringify(data.cause.text)} data-atual_value=${data.cause.value} class="mudarStatusOcorrencia_acoes" title='Alterar status'>Alterar status</a>                                   
                                    </div>

                                    <div class="dropdown-item dropdown-item-acoes dropdown-item-acoes-editar" style="cursor: pointer;">
                                        <a style="cursor: pointer; color: black; text-decoration: none !important; width: 100%; display: block;" data-id=${data.id} class="mudarCancelarOcorrencia_acoes" title='Cancelar Ocorrência'>Cancelar Ocorrência</a>                                   
                                    </div>

                                </div>
                            </div>`;
                    },
                },
            ],
            rowData: [],
            getRowId: params => params.data.id,
            pagination: true,
            defaultColDef: {
                resizable: true,
            },
            sideBar: {
                toolPanels: [{
                    id: "columns",
                    labelDefault: "Colunas",
                    iconKey: "columns",
                    toolPanel: "agColumnsToolPanel",
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                        width: 100,
                    },
                }, ],
                defaultToolPanel: false,
            },
            paginationPageSize: $("#select-quantidade-por-contatos-corporativos").val(),
            localeText: AG_GRID_LOCALE_PT_BR,
        };
        var gridDiv = document.querySelector("#tableContatos");
        AgGrid = new agGrid.Grid(gridDiv, gridOptions);
        $(".ag-overlay-no-rows-center").html('<div class="spinner"></div>');
        preencherExportacoes(gridOptions, "RelatorioDeOcorrencias", ['ticketnumber', 'subject', 'type', 'origin', 'technology', 'cause.text', 'description']);

        function updateData(newData = []) {
            gridOptions.api.setRowData(newData);
        }

        await buscarDadosAgGrid();

        $("#select-quantidade-por-contatos-corporativos").change(function() {
            gridOptions.api.paginationSetPageSize($(this).val());
        });

        $("#select-quantidade-por-contatos-corporativos").change(function() {
            gridOptions.api.paginationSetPageSize($(this).val());
            gridOptions.api.refreshCells({
                force: true
            });
        });

        $(document).on('click', '.btn-expandir', async function(e) {
            e.preventDefault();
            menuAberto = !menuAberto;

            if (menuAberto) {
                $(".img-expandir").attr(
                    "src",
                    `${BaseURL}/assets/images/icon-filter-show.svg`
                );
                $("#filtroBusca").hide();
                $(".menu-interno").hide();
                $("#conteudo").removeClass("col-md-9");
                $("#conteudo").addClass("col-md-12");
                $('.tab-acoes-pesquisa').hide()
            } else {
                $(".img-expandir").attr(
                    "src",
                    `${BaseURL}/assets/images/icon-filter-hide.svg`
                );
                $("#filtroBusca").show();
                $(".menu-interno").show();
                $("#conteudo").css("margin-left", "0px");
                $("#conteudo").removeClass("col-md-12");
                $("#conteudo").addClass("col-md-9");
                $('.tab-acoes-pesquisa').show()

            }
        });

        $("#BtnLimparFiltroOcorrencias").on("click", async function() {
            if ($('#filtrar-atributos-ocorr').val().trim().length > 0) {
                $('#BtnPesquisar').html('<i class="fa fa-spinner fa-spin"></i> Carregando');
                $('#BtnPesquisar').prop('disabled', true);
                $('#BtnLimparFiltroOcorrencias').prop('disabled', true);
                $("#filtrar-atributos-ocorr").val("");
                updateData()
                await buscarDadosAgGrid()
                $('#BtnPesquisar').html('<i class="fa fa-search"></i> Buscar');
                $('#BtnPesquisar').prop('disabled', false);
                $('#BtnLimparFiltroOcorrencias').prop('disabled', false);
            }
        });


        $('#BtnPesquisar').click(async function(e) {
            e.preventDefault()
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando');
            $(this).prop('disabled', true);
            $('#BtnLimparFiltroOcorrencias').prop('disabled', true);
            updateData()
            await buscarDadosAgGrid()
            $(this).html('<i class="fa fa-search"></i> Buscar');
            $(this).prop('disabled', false);
            $('#BtnLimparFiltroOcorrencias').prop('disabled', false);

            gridOptions.api.refreshCells({
                force: true
            })
        })

        $('#formAddClientes').on('submit', async function(event) {
            event.preventDefault();
            ShowLoadingScreen()

            var formData = $(this).serialize();

            await $.ajax({
                type: 'POST',
                url: '<?= site_url("PosVenda/Gestao/criar_analista_suporte") ?>' + '/?edicao=' + edicaoModal + '&idEdicao=' + id_usuario,
                data: formData,
                success: function(response) {
                    response = JSON.parse(response)
                    if (response.status == 200) {
                        formData = convertToJSONObject(formData)

                        let valorEdicao = edicaoModal == 'sim'

                        showAlert("success", "Analista de Suporte " + (valorEdicao ? 'editado' : 'cadastrado') + " com sucesso!");

                        var newClient = {
                            id: valorEdicao ? id_usuario : response.resultado.mensagem,
                            nomeCompleto: formData.nomeCompleto,
                            idCrm: formData.idCrm,
                            usuarioSistema: formData.usuarioSistema,
                            emailPrimario: formData.emailPrimario,
                            telefoneCelular: formData.telefoneCelular,
                            estado: formData.estado,
                            gerente: formData.gerente
                        };
                        addRowFromGrid(newClient, valorEdicao)
                        limparForm()
                        $('#addClientes').modal('hide');
                    } else {
                        if (response.resultado?.errors) {
                            response.resultado?.errors.forEach((item) => {
                                showAlert("warning", item);
                            });
                        } else {
                            showAlert("warning", response.resultado?.mensagem);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    showAlert("error", "Falha ao salvar Analista de Suporte.");
                }
            });
            HideLoadingScreen();
        });

        function removeRowFromGrid(rowId) {
            gridOptions.api.applyTransaction({
                remove: [{
                    id: rowId
                }]

            });
        }

        function convertToJSONObject(queryString) {
            const pairs = queryString.split('&');
            const result = {};

            pairs.forEach(pair => {
                const [key, value] = pair.split('=');
                result[decodeURIComponent(key)] = decodeURIComponent(value || '');
            });

            return result;
        }

        function addRowFromGrid(rowId, edicao) {

            if (edicao) {
                gridOptions.api.applyTransaction({
                    update: [rowId]
                });
            } else {
                gridOptions.api.applyTransaction({
                    add: [rowId]
                });
            }

            gridOptions.api.refreshCells({
                force: true
            });
        }

        $('#nomeUsuario').on('input', function() {
            var nomeUsuario = $(this).val();
            var sanitizedNomeUsuario = nomeUsuario.replace(/\s/g, '');
            $(this).val(sanitizedNomeUsuario);
        })

        $(document).on('click', '.editar_id_usuario', async function() {
            limparForm()
            id_usuario = $(this).data('id-user');
            edicaoModal = 'sim'
            ShowLoadingScreen();
            $("#titleTecnologias").text('Editar Analista de Suporte')
            $('#formAddClientes').attr('data-edicao-id-usuario', id_usuario);


            await $.ajax({
                url: '<?php echo base_url("PosVenda/Gestao/buscar_analista_id"); ?>' + '/?id=' + id_usuario,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        if (response?.resultado.length > 0) {
                            var data = response?.resultado[0];

                            $.each(data, function(key, value) {
                                var campo = $('#' + key);
                                if (campo.length) {
                                    if (campo.is('select') && typeof value === 'boolean') {
                                        value = value.toString();
                                    }
                                    campo.val(value);
                                }
                            });
                        }
                        $('#addClientes').modal('show');

                    } else {
                        showAlert("error", response.mensagem);
                    }
                    HideLoadingScreen();
                },
                error: function(error) {
                    showAlert("error", "Erro na solicitação ao servidor");
                    HideLoadingScreen();
                }
            })
        })

        function limparForm() {
            $('#formAddClientes').find('input, select').val('');
        }

        $(document).on('click', '.deletar_id_usuario', async function() {
            let id_usuario = $(this).data('id-user');
            Swal.fire({
                title: "Atenção!",
                text: "Deseja realmente inativar o analista de suporte?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#007BFF",
                cancelButtonColor: "#d33",
                confirmButtonText: "Continuar",
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    ShowLoadingScreen();
                    $.ajax({
                        url: '<?php echo base_url("PosVenda/Gestao/inativar_analistas_suporte"); ?>' + '/?id=' + id_usuario,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response['status'] == 200) {
                                removeRowFromGrid(id_usuario)
                                showAlert("success", "Status alterado com sucesso!")
                            } else if (response['status'] == 400) {
                                showAlert("error", response['resultado']['mensagem']);
                            } else {
                                showAlert("error", response['resultado']['mensagem']);
                            }

                            HideLoadingScreen();
                        },
                        error: function(error) {
                            showAlert("error", "Erro na solicitação ao servidor");
                            HideLoadingScreen();
                        }
                    })


                }
            });
        })

        $('#BtnAdicionarCliente').on('click', function() {
            limparForm()
            edicaoModal = 'nao'
            id_usuario = 0
            $("#titleTecnologias").text('Cadastrar Analista de Suporte')
            $('#formAddClientes').attr('data-edicao-id-usuario', 0);
            $('#addClientes').modal('show');
        })



        //// Infos ocorrencia

        function getInformacoesOcorrencia(incidentId) {
            return new Promise((resolve, reject) => {
                $.getJSON(`${URL_PAINEL_OMNILINK}/ajax_informacoes_ocorrencia?incidentId=${incidentId}`, function(data) {
                    resolve(data);
                }).fail(err => {
                    reject(err);
                });
            });
        }

        $(document).on('click', '.modalInformacoesOcorrencia_acoes', async function() {

            let incidentId = $(this).attr('data-id');

            ShowLoadingScreen()

            await getInformacoesOcorrencia(incidentId)
                .then(informacoesOcorrencia => {
                    informacoesOcorrencia = informacoesOcorrencia.ocorrencia;

                    $('.inforOcorrenciaLabel').text(' - ');
                    $('#infoOcorrenciaAssunto').text(informacoesOcorrencia.assunto || " - ");
                    $('#infoOcorrenciaAssuntoPrimario').text(informacoesOcorrencia.assunto_primario || " - ");
                    $('#infoOcorrenciaDescricaoAssunto').text(informacoesOcorrencia.assunto_descricao || " - ");
                    $('#infoOcorrenciaNumeroOcorrencia').text(informacoesOcorrencia.numero_ocorrencia || " - ");
                    $('#infoOcorrenciaOrigemOcorrencia').text(informacoesOcorrencia.origem_ocorrencia || " - ");
                    $('#infoOcorrenciaTipoOcorrencia').text(informacoesOcorrencia.tipo_ocorrencia || " - ");
                    $('#infoOcorrenciaTipoAtendimento').text(informacoesOcorrencia.tipo_atendimento || " - ");
                    $('#infoOcorrenciaTecnologia').text(informacoesOcorrencia.tecnologia || " - ");
                    $('#infoOcorrenciaFilaAtual').text(informacoesOcorrencia.fila_atual || " - ");
                    $('#infoOcorrenciaFilaAtendimento').text(informacoesOcorrencia.fila_atendimento || " - ");
                    $('#infoOcorrenciaUltimaFila').text(informacoesOcorrencia.ultima_fila || " - ");
                    $('#infoOcorrenciaProprietario').text(informacoesOcorrencia.proprietario || " - ");
                    $('#infoOcorrenciaObservacao').text(informacoesOcorrencia.description || " - ");
                    $('#infoOcorrenciaCriadoPor').text(informacoesOcorrencia.description || " - ");
                    $('#infoOcorrenciaStatus').text(informacoesOcorrencia.status || " - ");
                    $('#infoOcorrenciaRazaoStatus').text(informacoesOcorrencia.razao_status || " - ");
                    $('#infoOcorrenciaDataCriacao').text(informacoesOcorrencia.data_criacao || " - ");
                    $('#infoOcorrenciaDataModificacao').text(informacoesOcorrencia.data_modificacao || " - ");
                    $('#infoOcorrenciaCriador').text(informacoesOcorrencia.criado_por || " - ");
                    $('#infoOcorrenciaModificador').text(informacoesOcorrencia.modificado_por || " - ");

                    if (informacoesOcorrencia.status === "Resolvida") {
                        $('#modificado-por-ocorrencia').html("Resolvido Por:")
                    } else if (informacoesOcorrencia.status === "Cancelada") {
                        $('#modificado-por-ocorrencia').html("Cancelado Por:")
                    } else {
                        $('#modificado-por-ocorrencia').html("Modificado Por:")
                    }

                    $('#modalInformacoesOcorrencia').modal('show');

                }).catch(err => {
                    showAlert("error", "Não foi possível carregar as informações da ocorrência.");
                }).finally(_ => {
                    HideLoadingScreen()
                });
        })

        /// EDITAR OCORRENCIA 

        let valores_antigos_ocorrencia = null;
        let valores_novos_ocorrencia = null;

        function ajaxGetOcorrencia(id) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `${URL_PAINEL_OMNILINK}/carregarOcorrencia/${id}`,
                    success: (data) => resolve(data),
                    error: (error) => reject(error),
                })
            });
        }


        async function popularSelects() {
            let tecnologias = await $.ajax({
                url: `${URL_PAINEL_OMNILINK}/listar_tecnologias/`,
                type: 'GET',
                success: function(data) {
                    return data.data.value;
                },
                error: function(error) {
                    showAlert("error", "Ocorreu um problema ao buscar as tecnologias, a base de dados pode estar apresentando instabilidade.");
                }
            });

            let assuntos = await $.ajax({
                url: `${URL_PAINEL_OMNILINK}/ajax_get_assuntos`,
                type: 'GET',
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.code == 200) {
                        return (data.values);
                    } else if (data.code == 0) {
                        showAlert("error", "A base de dados está apresentando instabilidade, não foi possível buscar os assuntos.");
                    } else {
                        showAlert("error", "Ocorreu um problema ao buscar os assuntos, a base de dados pode estar apresentando instabilidade.");
                    }
                },
                error: function(error) {
                    showAlert("error", "Ocorreu um problema ao buscar os assuntos, a base de dados pode estar apresentando instabilidade.");
                }
            });

            assuntos = JSON.parse(assuntos);

            tecnologias.data.value.forEach(element => {
                $("#TecnologiaEdit").append('<option value="' + element.tz_tecnologiaid + '">' + element.tz_name + '</option>');
            });

            assuntos.values.forEach(element => {
                if (typeof element.visualizarSinistro != "undefined") {
                    if (element.visualizarSinistro == true) {
                        $("#AssuntoEdit").append('<option value="' + element.subjectid + '">' + element.title + '</option>')
                    }
                } else {
                    $("#AssuntoEdit").append('<option value="' + element.subjectid + '">' + element.title + '</option>')
                }
            });

            $('#TecnologiaEdit').select2();
            $('#AssuntoEdit').select2();
            $('#TipoOcorrenciaEdit').select2();
            $('#OrigemOcorrenciaEdit').select2();
        }

        $(document).on('click', '.modalEditarOcorrencia_acoes', async function() {

            ShowLoadingScreen()
            $("#AssuntoEdit option:not([value='0'][disabled][selected])").remove();
            $("#TecnologiaEdit option:not([value='0'][disabled][selected])").remove();
            await popularSelects();
            let id = $(this).attr('data-id');
            let ocorrencia = await ajaxGetOcorrencia(id);

            if (ocorrencia) {
                let TicketNumber = ocorrencia.ticketnumber;
                let Assunto = ocorrencia._subjectid_value;
                let TipoOcorrencia = ocorrencia.casetypecode;
                let OrigemOcorrencia = ocorrencia.caseorigincode;
                let Tecnologia = ocorrencia._tz_tecnologia_value;
                let Descricao = ocorrencia.description;

                $('#IdEdit').val(id);
                $("#btn-anotacao-ocorrencia").attr("data-id", id);
                $("#btn-anotacao-ocorrencia").attr("ticket", TicketNumber);
                $('#TicketNumberEdit').val(TicketNumber);
                $('#TicketNumberEdit').prop("disabled", true);
                $('#AssuntoEdit').val(Assunto).trigger('change');
                $('#TipoOcorrenciaEdit').val(TipoOcorrencia).trigger('change');
                $('#OrigemOcorrenciaEdit').val(OrigemOcorrencia).trigger('change');
                $('#TecnologiaEdit').val(Tecnologia).trigger('change');
                $('#DescricaoEdit').val(Descricao);

                $("#modalOcorrenciaEdit").modal('show');
                HideLoadingScreen()

                if (document.getElementById("filas-row").style.display == "block") {
                    $('#assunto-row').toggle("fast");
                    $("#filas-row").toggle("fast");
                    $('#filas').val("");
                    $('#fila-nome').val("");
                    $("#box-fila").prop("checked", false);
                }
            } else {
                $(element).attr('disabled', false).html("<i style='font-size: 20px; position: relative; top: 2px; right: 6px color:blue' class='fa fa-edit' aria-hidden='true'></i>");
                showAlert("error", 'Não foi possível carregar os dados da ocorrencia');
                HideLoadingScreen()
            }

            let data = {
                Cliente: cliente_selecionado_atual?.clienteAuxiliarModel?.idCrm,
                Id: $("#IdEdit").val(),
                TicketNumber: $("#TicketNumberEdit").val(),
                Assunto: $("#AssuntoEdit option:selected").val(),
                TipoOcorrencia: $("#TipoOcorrenciaEdit option:selected").val(),
                OrigemOcorrencia: $("#OrigemOcorrenciaEdit option:selected").val(),
                Tecnologia: $("#TecnologiaEdit option:selected").val(),
                Descricao: $("#DescricaoEdit").val(),
                Fila: $("#filas").val(),
                NomeFila: $("#fila-nome").val(),
            };

            valores_antigos_ocorrencia = data;

        })

        $('#form_ocorrenciaEdit').submit(async function(e) {
            e.preventDefault();
            ShowLoadingScreen()
            let data = {
                Cliente: cliente_selecionado_atual?.clienteAuxiliarModel?.idCrm,
                Id: $("#IdEdit").val(),
                TicketNumber: $("#TicketNumberEdit").val(),
                Assunto: $("#AssuntoEdit option:selected").val(),
                TipoOcorrencia: $("#TipoOcorrenciaEdit option:selected").val(),
                OrigemOcorrencia: $("#OrigemOcorrenciaEdit option:selected").val(),
                Tecnologia: $("#TecnologiaEdit option:selected").val(),
                Descricao: $("#DescricaoEdit").val(),
                Fila: $("#filas").val(),
                NomeFila: $("#fila-nome").val(),
            };

            valores_novos_ocorrencia = data;
            salvar_auditoria(`${URL_PAINEL_OMNILINK}/editar_ocorrencias`, 'update', valores_antigos_ocorrencia, valores_novos_ocorrencia)
                .then(async () => {
                    await ajaxEditarOcorrencia(data);
                })
                .catch(error => {
                }).finally(_ => {
                    HideLoadingScreen()
                })
        });


        async function ajaxEditarOcorrencia(data) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `${URL_PAINEL_OMNILINK}/editar_ocorrencias`,
                    type: "POST",
                    data: data,
                    dataType: 'json',
                    success: function(callback) {
                        if (callback.code == 200) {
                            showAlert("success", "Edição de ocorrência realizado com sucesso.");
                            $('#form_ocorrenciaEdit')[0].reset();
                            $("#modalOcorrenciaEdit").modal("hide");
                            resolve(true);
                        } else {
                            showAlert("error", 'Erro ao editar ocorrência!');
                            reject(false);
                        }
                    },
                    error: function(XMLHttpRequest, erro) {
                        showAlert("error", "Erro ao editar ocorrência");
                        reject(false);
                    }
                });
            });
        }

        async function salvar_auditoria(url_api, clause, valores_antigos, valores_novos) {
            if (valores_antigos && typeof(valores_antigos) === 'object') {
                valoresAuditoriaAntigo = [];
                Object.keys(valores_antigos).forEach(i => {
                    valoresAuditoriaAntigo.push(i)
                });
            } else {
                valoresAuditoriaAntigo = valores_antigos;
            }
            return new Promise((resolve, reject) => {
                const cpf_cpnj_cliente = (cliente_selecionado_atual.cnpj ? cliente_selecionado_atual.cnpj : cliente_selecionado_atual.cpf).replace(/[^0-9]/g, '');
                $.ajax({
                    url: URL_PAINEL_OMNILINK + '/ajax_salvar_auditoria',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        url_api,
                        clause,
                        valoresAuditoriaAntigo,
                        valores_novos,
                        cpf_cpnj_cliente
                    },
                    success: function(callback) {
                        if (callback.status) {
                            resolve(callback);
                        } else {
                            showAlert("error", 'Erro ao salvar auditoria! Não foi possível completar a operação.');
                            reject(callback);
                        }
                    },
                    error: function(error) {
                        showAlert("error", 'Erro ao salvar auditoria! Não foi possível completar a operação.');
                        reject(error);
                    }

                });
            });
        }

        /// modal alterar status

        let atualValue_alterarStatus = 0;
        let atualText_alterarStatus = 0;
        let atualId_alterarStatus = 0;

        $(document).on('click', '.mudarStatusOcorrencia_acoes', async function() {
            atualValue_alterarStatus = $(this).data('atual_value');
            atualText_alterarStatus = $(this).data('atual_text');
            atualId_alterarStatus = $(this).data('id');

            $('#statusAtualOcorrencia').val(atualText_alterarStatus)
            $('#modalAlterarStatusOcorrencia_select').val(atualValue_alterarStatus);
            $('#modalAlterarStatusOcorrencia_select').select2()
            $("#modalAlterarStatusOcorrencia").modal('show');
        })

        $('#btnSubmitEditStatusOcorrencia').click(async function(e) {
            e.preventDefault()
            let novosStatusEditar = $('#modalAlterarStatusOcorrencia_select').val();

            if (atualValue_alterarStatus == novosStatusEditar) {
                showAlert("warning", 'Status não alterado, não houveram mudanças!');
                return;
            }

            ShowLoadingScreen()
            salvar_auditoria(`${URL_PAINEL_OMNILINK}/alterarStatus`, 'update', atualValue_alterarStatus, {
                "razao_status": novosStatusEditar
            }).then(async () => {
                let novosStatus = {
                    razao_status: novosStatusEditar
                };

                await ajaxAlterarStatus(atualId_alterarStatus, novosStatus);
                updateCause(atualId_alterarStatus, $('#modalAlterarStatusOcorrencia_select option:selected').text(), novosStatusEditar)
                $("#modalAlterarStatusOcorrencia").modal('hide');
                showAlert("success", "Status alterado com sucesso!")


            }).catch(error => {
                showAlert("error", 'Status não alterado, Falha ao salvar!');
            }).finally(_ => {
                HideLoadingScreen()
            });

        })

        function ajaxAlterarStatus(idIncident, novosStatus) {
            novosStatus['idIncident'] = idIncident;
            return new Promise((resolve, reject) => {
                novosStatus['idIncident'] = idIncident;
                $.ajax({
                    url: `${URL_PAINEL_OMNILINK}/alterarStatus`,
                    type: 'POST',
                    dataType: 'json',
                    data: novosStatus,
                    success: function(callback) {
                        if (callback.code == 200) {
                            resolve(true);
                        } else {
                            reject(false);
                            showAlert("error", 'Não foi possível atualizar o status, por favor tente mais tarde.');
                        }
                    },
                    error: function(error) {
                        showAlert("error", 'Erro ao salvar auditoria! Não foi possível completar a operação.');
                        reject(error);
                    }
                });
            });
        }

        function updateCause(rowId, newText, newValue) {

            var rowNode = gridOptions.api.getRowNode(rowId);

            if (rowNode) {
                var updatedData = {
                    ...rowNode.data
                };
                updatedData.cause.text = newText;
                updatedData.cause.value = newValue;
                rowNode.setData(updatedData);
            }
        }


        /// modal resolver ocorrencia 

        let id_ocorr_resolver = 0;
        let q_atividades_aberto = 0;

        $(document).on('click', '.modalResolverOcorrencia_acoes', async function() {

            id_ocorr_resolver = $(this).data('id');

            ShowLoadingScreen()
            await solicitarAtividadesAbertas(id_ocorr_resolver).then(response => {
                if (response.code == 200) {
                    return response.atividades;
                } else {
                    throw response.error;
                }
            }).then(async atividadesAberto => {

                q_atividades_aberto = atividadesAberto.length;
                atividadesAberto.length > 0 ? statusOption = true : statusOption = false
                $('#selectIdTipoResolucao option[value="419400007"]').prop('disabled', statusOption);

            }).catch(err => {
                showAlert("error", `Não foi possível realizar o encerramento da ocorrência, por favor tente mais tarde`);
            }).finally(_ => {
                $('#modalEncerramentoOcorrencia').modal('show');
                HideLoadingScreen()
            })


        })

        $('#eventoEncerrarOcorrencia_bt').click(async function() {

            let textConfirmacao = q_atividades_aberto && q_atividades_aberto > 0 ? "A atividade possui " + q_atividades_aberto + " atividade(s) aberta(s), ao encerrar a ocorrência esta(s) atividade(s) será(ão) cancelada(s) ou excluída(s), deseja continuar ?" : "Deseja continuar com o encerramento da ocorrência?"

            let data = {
                'Id': id_ocorr_resolver,
                'TipoResolucao': $("#selectIdTipoResolucao").val(),
                'Subject': $("#resolucaoOcorrencia").val(),
                'Descricao': $("#descricaoEncerrarOcorrencia").val(),
                'TimeSpent': $("#periodoFaturavel").val()
            };

            Swal.fire({
                title: "Atenção!",
                text: textConfirmacao,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#007BFF",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim",
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    ShowLoadingScreen()
                    salvar_auditoria(`${URL_API}incident/closeIncident`, 'insert', null, data).then(async response => {
                        await ajaxEncerarOcorrencia(data).then(async response => {
                            if (response.Status == 200 && response?.Message == "Ocorrência fechada com sucesso") {
                                showAlert("success", response.Message);
                                q_atividades_aberto = 0
                                $("#modalEncerramentoOcorrencia").modal('hide');
                                removeRowFromGrid(id_ocorr_resolver)
                            } else {
                                showAlert("error", response.Message);
                            }
                        }).catch(err => {
                            showAlert("error", "Não foi possível encerrar a ocorrência.");
                            $("#modalEncerramentoOcorrencia").modal('hide');
                        });
                    }).catch(err => {
                        showAlert("error", "Não foi possível encerrar a ocorrência.");
                        $("#modalEncerramentoOcorrencia").modal('hide');
                    }).finally(_ => HideLoadingScreen());
                }
            })
        })

        async function solicitarAtividadesAbertas(incidentId) {
            return new Promise((resolve, reject) => {
                $.get(`${URL_PAINEL_OMNILINK}/solicitarAtividadesAbertas?incidentId=${incidentId}`, function(response) {
                    resolve(response);
                }, "json").fail(err => {
                    reject(err);
                });
            });
        }

        async function ajaxEncerarOcorrencia(data) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `${URL_API}incident/closeIncident`,
                    type: "PUT",
                    dataType: 'json',
                    data: data,
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(XMLHttpRequest, erro) {
                        reject(erro);
                    }
                })
            })
        }

        /// encerrar ocorrencia

        $(document).on('click', '.mudarCancelarOcorrencia_acoes', async function() {

            let incidentId_cancelar = $(this).data('id');

            Swal.fire({
                title: "Atenção!",
                text: "Deseja continuar com o cancelamento da ocorrência?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#007BFF",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim",
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    ShowLoadingScreen()
                    salvar_auditoria(
                            `${URL_PAINEL_OMNILINK}/cancelar_ocorrencia`,
                            'update', {
                                statecode: 0
                            }, {
                                statecode: 2
                            }
                        )
                        .then(async response => {
                            await $.ajax({
                                url: `${URL_PAINEL_OMNILINK}/cancelar_ocorrencia`,
                                type: "POST",
                                dataType: 'json',
                                data: {
                                    'incident_id': incidentId_cancelar,
                                },
                                success: function(response) {
                                    if (response.status == 1) {
                                        showAlert("success", "Ocorrência cancelada com sucesso.");
                                        removeRowFromGrid(incidentId_cancelar)
                                    } else {
                                        showAlert("error", "Não foi possível cancelar a ocorrência.");
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    showAlert("error", "Ocorreu um erro ao cancelar a ocorrência. Tente novamente em alguns minutos.")
                                }
                            });

                        }).catch(err => {
                            showAlert("error", "Não foi possível cancelar a ocorrência.");
                        }).finally(_ => HideLoadingScreen());
                }
            })
        })


        // aba novo ticket 

        $(document).on('click', '#btnTicketCliente', async function() {

            $('#placa').select2({
                language: 'pt-BR',

            })
            $('#prioridade').select2({
                language: 'pt-BR',
            })
            $('#placa').select2({
                language: 'pt-BR',
            })
            $('#departamento').select2({
                language: 'pt-BR',
            })

            $('#id_usuario').val('').trigger('change');
            $('#placa').val('').trigger('change');
            $('#assunto').val('');
            $('#departamento').val('').trigger('change');
            $('#prioridade').val('').trigger('change');
            $('#input_id_cliente').val('');
            $('#input_usuario').val('');
            $('#input_nome_usuario').val('');
            $('#descricao').val('');
            $('#arquivo').val('');

            $('#novo_ticket').modal('show');

            $('.select_usuario').select2({
                ajax: {
                    url: RouterUsuariosGestor + '/get_ajax_usuarios_gestores',
                    dataType: 'json'
                },
                placeholder: "Selecione o Usuario",
                allowClear: true,
                language: 'pt-BR',

            });

        })

        $(document).on('change', '.select_usuario', function() {
            var id_usuario = $(this).val();
            var str = "";
            if (id_usuario) {
                document.getElementById('placa').readonly = true;
                $.ajax({
                    url: SITE_URL + '/veiculos/lista_placas_usuario/' + id_usuario,
                    datatype: 'json',
                    success: function(data) {
                        var res = JSON.parse(data);
                        if (res.status == 'OK') {
                            $("#input_id_cliente")[0].value = res.results[0].id_cliente;
                            $("#input_usuario")[0].value = res.results[0].usuario;
                            $("#input_nome_usuario")[0].value = res.results[0].nome_usuario;
                            str += '<option value="" disabled selected>Selecione uma Placa</option>';
                            $(res.results).each(function(index, value) {
                                str += '<option value=' + value.placa + '>' + value.placa + '</option>';
                            })
                            $('#placa').html(str);
                            $('#placa').select2({
                                language: 'pt-BR',
                            })
                        }
                        document.getElementById('placa').readonly = false;
                    },
                    error: function(error) {
                        document.getElementById('placa').readonly = false;
                    },
                });
            }

        });

        $("#ContactForm").submit(async function(e) {
            e.preventDefault();

            if (!validateForm()) return;
            ShowLoadingScreen()

            var formdata = new FormData($("#ContactForm")[0]);

            await $.ajax({
                cache: false,
                url: RouterWebdesk + "/new_ticket",
                type: 'POST',
                dataType: 'json',
                data: formdata,
                contentType: false,
                processData: false,
                success: function(callback) {
                    if (callback.success == true) {
                        $("#ContactForm").trigger('reset');
                        $('#placa').val(null).trigger('change');
                        showAlert('success', 'Ticket criado com sucesso!')
                        $('#novo_ticket').modal('hide');
                        HideLoadingScreen()
                    } else {
                        showAlert('error', callback.mensagem);
                        HideLoadingScreen()
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    HideLoadingScreen()
                    showAlert('error', 'Não é possível salvar o ticket no momento. Tente novamente mais tarde!');
                }
            });
            HideLoadingScreen()
        });

        function validateForm() {
            if (!$('#id_usuario').val()) {
                showAlert("warning", 'campo Usuário inválido');
                return false;
            }

            if (!$('#placa').val()) {
                showAlert("warning", 'campo Placa inválido');
                return false;
            }
            if ($('#assunto').val().trim() === "") {
                showAlert("warning", 'campo Assunto inválido');
                return false;
            }

            if (!$('#departamento').val()) {
                showAlert("warning", 'campo Categoria inválido');
                return false;
            }

            if (!$('#prioridade').val()) {
                showAlert("warning", 'campo Prioridade inválido');
                return false;
            }

            if ($('#descricao').val().trim() === "") {
                showAlert("warning", 'campo Descrição inválido');
                return false;
            }

            return true;
        }


        $('#descricao').on('input', function() {
            $(this).val($(this).val().substring(0, 500));
            $('#content-countdown').text(500 - $(this).val().length);
        }).trigger('input')


        /// cadastrar ocorrencias

        $("#btnDropdownOcorrencias").click(function(e) {
            e.preventDefault();
            if ($('#myDropdownOcorrencias').is(':visible')) {
                $('#myDropdownOcorrencias').hide();
            } else {
                $('#myDropdownOcorrencias').show();
            }
        })


        async function popularSelectsOcorrencia() {
            tecnologias = await $.ajax({
                url: `${URL_PAINEL_OMNILINK}/listar_tecnologias/`,
                type: 'GET',
                success: function(data) {
                    return data.data.value;
                },
                error: function(error) {
                    showAlert("error", "Ocorreu um problema ao buscar as tecnologias, a base de dados pode estar apresentando instabilidade.");
                }
            });

            assuntos = await $.ajax({
                url: `${URL_PAINEL_OMNILINK}/ajax_get_assuntos`,
                type: 'GET',
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.code == 200) {
                        return (data.values);
                    } else if (data.code == 0) {
                        showAlert("error", "A base de dados está apresentando instabilidade, não foi possível buscar os assuntos.");
                    } else {
                        showAlert("error", "Ocorreu um problema ao buscar os assuntos, a base de dados pode estar apresentando instabilidade.");
                    }
                },
                error: function(error) {
                    showAlert("error", "Ocorreu um problema ao buscar os assuntos, a base de dados pode estar apresentando instabilidade.");
                }
            });

            assuntos = JSON.parse(assuntos);
            tecnologias.data.value.forEach(element => {
                $("#Tecnologia").append('<option value="' + element.tz_tecnologiaid + '">' + element.tz_name + '</option>');
            });
            assuntos.values.forEach(element => {
                if (typeof element.visualizarSinistro != "undefined") {
                    if (element.visualizarSinistro == true) {
                        $("#Assunto").append('<option value="' + element.subjectid + '">' + element.title + '</option>')
                    }
                } else {
                    $("#Assunto").append('<option value="' + element.subjectid + '">' + element.title + '</option>')
                }
            });

        }


        $(document).on('click', '#modalOcorrenciaBt', async function() {
            $('#TipoOcorrencia, #Assunto, #OrigemOcorrencia, #Tecnologia').select2()
            $('#Assunto').append(`<option selected disabled value="1">Buscando assuntos...</option>`)
            $('#Assunto').attr("disabled", true)
            $('#Tecnologia').append(`<option selected disabled value="1">Buscando tecnologias...</option>`)
            $('#Tecnologia').attr("disabled", true)
            ShowLoadingScreen()
            await popularSelectsOcorrencia();
            $('#Assunto').find('option').get(1).remove();
            $('#Assunto').attr("disabled", false)
            $('#Tecnologia').find('option').get(1).remove();
            $('#Tecnologia').attr("disabled", false)
            $('#Assunto').val(0).trigger('change');
            $('#Tecnologia').val(0).trigger('change');
            HideLoadingScreen()
        });


        $('#form_ocorrencia').submit(async function(e) {
            e.preventDefault();
            let data = {
                Cliente: cliente_selecionado_atual?.clienteAuxiliarModel?.idCrm,
                Assunto: $("#Assunto option:selected").val(),
                nomeAssunto: $("#Assunto option:selected").text(),
                TipoOcorrencia: $("#TipoOcorrencia option:selected").val(),
                OrigemOcorrencia: $("#OrigemOcorrencia option:selected").val(),
                Tecnologia: $("#Tecnologia option:selected").val(),
                Descricao: $("#Descricao").val(),
                isEmpresa: cliente_selecionado_atual.cnpj ? true : false
            };

            if (!validateFormOcorrencia()) return;

            ShowLoadingScreen()

            await salvar_auditoria(`${URL_PAINEL_OMNILINK}/cadastrar_ocorrencias`, 'insert', null, data)
                .then(async () => {
                    await ajaxCadatrarOcorrencia(data);
                })
                .catch(error => {
                })
                .finally(_ => HideLoadingScreen());

        });

        async function ajaxCadatrarOcorrencia(data, retorno = true) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `${URL_PAINEL_OMNILINK}/cadastrar_ocorrencias`,
                    type: "POST",
                    data: data,
                    dataType: 'json',
                    success: async function(callback) {
                        if (callback.code == 201) {

                            let isChecked = $("#box-fila-suporte").is(":checked")
                            if (isChecked) {
                                await $.ajax({
                                    url: URL_PAINEL_OMNILINK + '/auditoriaCadastroOcorrencia',
                                    type: 'POST',
                                    data: {
                                        idCliente: cliente_selecionado_atual?.id,
                                        assunto: $('#Assunto').find(":selected").text()
                                    },
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.status != 200) {
                                            showAlert("error", 'Erro ao realizar a requisição');
                                        }
                                    },
                                    error: function(error) {
                                        showAlert("error", 'Erro ao realizar a requisição');
                                        reject('Erro ao realizar a requisição');
                                    }
                                });
                            }

                            $('#form_ocorrencia')[0].reset();
                            $("#modalOcorrencia").modal("hide");
                            showAlert("success", "Cadastro de ocorrência realizado com sucesso.");
                            resolve(true);
                        } else {
                            showAlert("error", 'Erro ao cadastrar ocorrência!');
                            reject(false);
                        }
                    },
                    error: function(XMLHttpRequest, erro) {
                        showAlert("error", "Erro ao cadastrar ocorrência!");
                        reject(false);
                    }
                });
            });
        }


        function validateFormOcorrencia() {
            if (!$('#Assunto').val()) {
                showAlert("warning", 'campo Assunto inválido');
                return false;
            }

            if (!$('#TipoOcorrencia').val()) {
                showAlert("warning", 'campo Tipo de Ocorrência inválido');
                return false;
            }
          
            if (!$('#OrigemOcorrencia').val()) {
                showAlert("warning", 'campo Origem da Ocorrência inválido');
                return false;
            }

            if (!$('#Tecnologia').val()) {
                showAlert("warning", 'campo Tecnologia inválido');
                return false;
            }
         

            return true;
        }

    })

    function fecharDrop() {
        $('#opcoes_exportacao').hide();
    }
</script>

<style>
    .modal-header .close {
        margin-top: -27px;
    }
</style>