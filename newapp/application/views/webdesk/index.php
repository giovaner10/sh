<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/firmware', 'layout.css') ?>">

<?php 
    $possui_permissao_visualizar_registro_chamadas = $this->auth->is_allowed_block('vis_registro_chamadas_atendimento_omnilink');
    $omnilink = $this->auth->get_login_dados('funcao');

    $url = $this->router->fetch_class();
?>

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?="Gerenciador de Tickets"?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('suporte') ?> >
        <?= "Gerenciador de Tickets" ?>
    </h4>
</div>

<div id="loading">
    <div class="loader"></div>
</div>

<div style="margin: 15px 0 0 15px;">
    <div class="col-md-3" id="menu-lateral">
    <div class="card menu-interno"> 
        <?php include_once('application/views/webdesk/menu.php'); ?>    
    </div>

    <?php include_once('application/views/webdesk/registro_chamadas/filtro.php'); ?>

    <?php include_once('application/views/webdesk/registro_chamadas/filtro.php'); ?>

        <div id="filtroBusca" class="card" style="margin-bottom: 20px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">
                    <input type="hidden" name="prestadoraBusca" id="prestadoraBusca" value="<?= $omnilink == 'OMNILINK' ? '0' : '1' ?>" />
                    <div class="input-container">
                        <label for="clienteBusca">Cliente:</label>
                        <select class="form-control" name="clienteBusca" id="clienteBusca" type="text" style="width: 100%;">
                        </select>
                    </div>
                    
                    <div class="input-container">
                        <label for="departamentoBusca">Categoria:</label>
                        <select class="form-control departamento" name="departamento" id="departamentoBusca" type="text" style="width: 100%;">
                            <option value="" selected>Todas</option>
                            <option value="Suporte Técnico">Suporte Técnico</option>
                            <option value="Atendimento ao Cliente">Atendimento ao Cliente</option>
                            <option value="Financeiro / Cobrança">Financeiro</option>
                            <option value="Vendas/Novos négocios">Vendas</option>
                        </select>
                    </div>

                    <div class="input-container">
                        <label for="tagBuscar">Tag:</label>
                        <select class="form-control" name="tag" id="tagBuscar" type="text" style="width: 100%;">
                            <option value="" selected>Todas</option>
                            <option value="Incidente">Incidente</option>
                            <option value="Requisicao">Requisicao</option>
                            <option value="Orientacao">Orientacao</option>
                            <option value="Reclamacao">Reclamacao</option>
                            <option value="Encerrado_Inatividade">Encerrado Inatividade</option>
                            <option value="Gestor">Gestor</option>
                            <option value="OmniTurbo">OmniTurbo</option>
                            <option value="OmniDual">OmniDual</option>
                            <option value="OmniTab">OmniTab</option>
                            <option value="OmniMob">OmniMob</option>
                            <option value="Telemetria_ME">Telemetria ME</option>
                            <option value="OmniLoRa">OmniLoRa</option>
                            <option value="OmniFrota">OmniFrota</option>
                            <option value="OmniCarreta">OmniCarreta</option>
                            <option value="OmniCarga">OmniCarga</option>
                            <option value="OmniSafe">OmniSafe</option>
                            <option value="OmniTelemetria">OmniTelemetria</option>
                            <option value="OmniJornada">OmniJornada</option>
                            <option value="OmniLog">OmniLog</option>
                            <option value="OmniScore">OmniScore</option>
                            <option value="OmniCom">OmniCom</option>
                            <option value="ERP_Simplificado">ERP Simplificado</option>
                            <option value="SDK">SDK</option>
                            <option value="Acessorios">Acessorios</option>
                            <option value="Saver">Saver</option>
                            <option value="Segunda_Via_Boleto">Segunda Via Boleto</option>
                            <option value="Aviso_Pagamento">Aviso Pagamento</option>
                            <option value="Contestacao_Fatura">Contestacao Fatura</option>
                            <option value="Excendente_Satelite">Excendente Satelite</option>
                            <option value="Reset_Chip">Reset Chip</option>
                            <option value="Liberacao_Sinal">Liberacao Sinal</option>
                            <option value="Firmware">Firmware</option>
                            <option value="Cancelamento">Cancelamento</option>
                            <option value="Espelhamento_Sinal">Espelhamento Sinal</option>
                            <option value="Ficha_Ativacao">Ficha Ativacao</option>
                            <option value="Relatorio">Relatorio</option>
                            <option value="Recuperacao_Senha">Recuperacao Senha</option>
                            <option value="Venda_PJ">Venda PJ</option>
                            <option value="Venda_PF">Venda PF</option>
                            <option value="Agendamento">Agendamento</option>
                            <option value="Treinamento">Treinamento</option>
                            <option value="Desbloqueio_Emergencial">Desbloqueio Emergencial</option>
                            <option value="Troca_Titularidade">Troca Titularidade</option>
                            <option value="OmniWeb">OmniWeb</option>
                            <option value="OmniID">OmniID</option>
                        </select>
                    </div>

                    <div class="input-container" id="statusContainer">
                        <label for="statusBusca">Status:</label>
                        <select class="form-control" name="statusBusca" id="statusBusca" type="text" style="width: 100%;">
                            <option value="" selected>Todos</option>
                            <option value="1">Em Andamento</option>
                            <option value="3">Concluidos</option>
                        </select>
                    </div>

                    <div class="input-container">
						<label for="dataInicial">Data Inicial:</label>
						<input type="date" name="dataInicial" id="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off"  />
					</div>
					<div class="input-container">
						<label for="dataFinal">Data Final:</label>
						<input type="date" name="dataFinal" id="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off"  />
					</div>

                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                    </div>

                    <div class="button-container">
                        <button class="btn btn-default" style='width:100%' id="BtnLimpar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="abas">

        <div id="aba-show-norio">
            <?php include_once('application/views/webdesk/show_norio.php'); ?>
        </div>
        
        <div id="aba-omnilink" style="display: none">
            <?php include_once('application/views/webdesk/omnilink.php'); ?>
        </div>
        
        <div id="aba-registro-chamadas" style="display: none">
            <?php include_once('application/views/webdesk/registro_chamadas/index.php'); ?>
        </div>

        <div id="aba-fila-ligacoes" style="display: none">
            <?php include_once('application/views/webdesk/fila_ligacoes/index.php'); ?>
        </div>
        
	</div>

</div>

<div id="novo_ticket" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form action="" id='ContactForm'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleBlacklist">Adicionar Ticket</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class='row'>
                            <div class="col-md-12 input-container form-group">
                                <label for="id_usuario">Usuário: <span class="text-danger">*</span></label>
                                <select class="select_usuario form-control" name="id_usuario" data-placeholder="Selecione um Usuário" style="width: 100% !important;" id="id_usuario" required >
                                    <option value="" disabled selected></option>
                                </select>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="placa">Placa: <span class="text-danger">*</span></label>
                                <select id="placa" class="form-control" data-placeholder="Selecione as placas" name="placa" style="width: 100% !important;" required readonly>
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
                                <label for="descricao">Descricao: <span class="text-danger">*</span></label>
                                <textarea name="descricao" rows="6" placeholder=" Descrição" id="descricao" class="form-control maxlength" style="resize: vertical;" required></textarea>
                                <span class="label" id="content-countdown" style="float:right; color:black;" title="500">0</span>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="arquivo">Arquivo:</label>
                                <input type="file" name="arquivo" id="arquivo" class="filestyle" data-buttonText="Carregar arquivo" style="white-space: normal; word-wrap: break-word; max-width: 100%;">
            					<span class="help-block" style="font-size: 11px;">*Formatos suportados: pdf, jpg, png e jpeg</span>
                            </div>
                        </div>
                        
                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id='btnSalvarBlacklist'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
	const permissaoVisualizarRegistroChamadas = Boolean(`<?= $possui_permissao_visualizar_registro_chamadas ?>`) == true;
</script>

<script>
    var Router = '<?= site_url('webdesk') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers', 'utils.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers', 'error.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers', 'mascaras.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/webdesk', 'abas_selecionadas.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink/registros_chamadas', 'index.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/tickets', 'Exportacoes.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink', 'atendimento.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/tickets', 'GerenciadorTickets.js') ?>"></script>

