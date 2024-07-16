<div id="modalMovidesk" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id='ticketForm' enctype="multipart/form-data">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleMovidesk">Abertura de Ticket - Movidesk</h3>
                </div>
                <div class="modal-body" style="display: flex; flex-wrap: wrap; justify-content:space-around; padding: 0; padding-top: 15px;">
                    <div class="form-group" style="width:45%;">
                        <label for="clienteIdMovidesk">ID Cliente:</label> <br>
                        <input type="number" min="0" class="form-control" id="clienteIdMovidesk" name="clienteIdMovidesk">
                    </div>
                    <div class="form-group" style="width: 45%;">
                        <label for="servicoMovidesk">Serviço: <span class="text-danger">*</span></label><br>
                        <select class="form-control" style="width:100%;" id="servicoMovidesk" name="servicoMovidesk" required>
                            <option value="" disabled selected>Carregando serviços...</option>
                        </select>
                    </div>
                    <div class="form-group" style="width: 45%;">
                        <label for="subservicoMovidesk" class="labelSubservico">Subserviço: <span class="text-danger">*</span></label><br>
                        <select class="form-control" style="width:100%;" id="subservicoMovidesk" name="subservicoMovidesk" required>
                            <option value="" disabled selected>Selecione o subserviço</option>
                        </select>
                    </div>
                    <div class="form-group servicoNivel3" style="width: 45%; display:none;">
                        <label for="servicoNivel3" class="labelServicoNivel3"></label><br>
                        <select class="form-control" style="width:100%;" id="servicoNivel3" name="servicoNivel3" required>
                            <option value="" disabled selected>Selecione o subserviço</option>
                        </select>
                    </div>
                    <div class="form-group" style="width: 45%;">
                        <label for="prestadoraMovidesk">Prestadora: <span class="text-danger">*</span></label><br>
                        <select class="form-control" style="width:100%;" id="prestadoraMovidesk" name="prestadoraMovidesk" required>
                            <option value="" disabled selected>Selecione a prestadora</option>
                            <option value="0">CEABS</option>
                            <option value="1">Omnilink</option>
                            <option value="2">Show Tecnologia</option>
                            <option value="3">Teletrim</option>
                        </select>
                    </div>
                    <div class="form-group" style="width: 45%;">
                        <label for="categoriaMovidesk">Categoria: </label><br>
                        <select class="form-control" style="width:100%;" id="categoriaMovidesk" name="categoriaMovidesk" required>
                            <option value="" disabled selected>Selecione a categoria</option>
                        </select>
                    </div>
                    <div class="form-group" style="width: 45%;">
                        <label for="urgenciaMovidesk">Urgência: <span class="text-danger">*</span></label><br>
                        <select class="form-control" style="width:100%;" id="urgenciaMovidesk" name="urgenciaMovidesk" required>
                            <option value="" disabled selected>Selecione a urgência</option>
                            <option value="0">Baixa</option>
                            <option value="1">Média</option>
                            <option value="2">Alta</option>
                            <option value="3">Urgente</option>
                        </select>
                    </div>
                    <div class="form-group produto" style="width: 45%; display:none;">
                        <label for="produtoMovidesk">Produto: <span class="text-danger">*</span></label><br>
                        <select class="form-control" style="width:100%;" id="produtoMovidesk" name="produtoMovidesk" required>
                            <option value="" disabled selected>Selecione o produto</option>
                            <option value="Edital Público">Edital Público</option>
                            <option value="ERP Simplificado">ERP Simplificado</option>
                            <option value="Gestor Móvel">Gestor Móvel</option>
                            <option value="Meu Omnilink">Meu Omnilink</option>
                            <option value="OmniCarga">OmniCarga</option>
                            <option value="OmniCarreta">OmniCarreta</option>
                            <option value="OmniCom">OmniCom</option>
                            <option value="OmniContingência">OmniContingência</option>
                            <option value="OmniFrota">OmniFrota</option>
                            <option value="OmniID">OmniID</option>
                            <option value="OmniJornada">OmniJornada</option>
                            <option value="OmniLog">OmniLog</option>
                            <option value="OmniSafe">OmniSafe</option>
                            <option value="OmniScore">OmniScore</option>
                            <option value="OmniTelemetria">OmniTelemetria</option>
                            <option value="OmniTurbo/Dual">OmniTurbo/Dual</option>
                            <option value="OmniTVC">OmniTVC</option>
                            <option value="SDK">SDK</option>
                        </select>
                    </div>
                    <div class="form-group cc" style="width: 95%;">
                        <label for="ccMovidesk">Cc:</label><br>
                        <input type="email" class="form-control" id="ccMovidesk" name="ccMovidesk" placeholder="exemplo@email.com" multiple>
                    </div>

                    <div class="form-group anexo" style="width: 95%; flex-wrap: wrap;">
                        <label for="anexoMovidesk" style="flex-basis: 100%;">Adicionar Anexo:</label> <br>
                        <input type="file" class="form-control" id="anexoMovidesk" name="anexoMovidesk" style="flex-basis: 100%;">
                    </div>

                    <div class="form-group" style="margin: 0 20px 0 20px">
                        <label for="assuntoMovidesk">Assunto: <span class="text-danger">*</span></label><br>
                        <input type="text" class="form-control" id="assuntoMovidesk" name="assuntoMovidesk" required>
                    </div>
                    <div class="form-group" style="margin: 20px;">
                        <label for="mensagemMovidesk">Mensagem: <span class="text-danger">*</span></label><br>
                        <textarea style="resize: none;" class="form-control" id="mensagemMovidesk" name="mensagemMovidesk" rows="3" required></textarea>
                    </div>

                    <hr style="margin: 0px;">
                </div>
                <div class="modal-footer">
                    <div class="footer-group" style="justify-content: end; padding: 0; padding-bottom: 7px">
                        <button type="submit" class="btn btn-success" id='btnTicket'>Abrir Ticket</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var RouterMovidesk = '<?= site_url('Movidesk/Movidesk') ?>';
    var emailUser = '<?= $emailUser ?>'
</script>

<script>
    const permissaoAbrirTicket = Boolean(`<?= $permissao_abrir_ticket ?>`) == true;
</script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/movidesk/', 'aberturaTicket.js') ?>"></script>