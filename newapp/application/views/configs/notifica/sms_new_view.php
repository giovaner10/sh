<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("sms_personalizado") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('configuracoes') ?> >
        <?= lang('mensagens_notificações') ?> >
        <?= lang('sms') ?>
    </h4>
</div>

<div id="loading">
    <div class="loader"></div>
</div>


<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container">
                        <label for="descricaoBusca">Descrição:</label>
                        <input type="text" name="descricaoBusca" class="form-control" placeholder="Digite parte da descrição..." id="descricaoBusca" />
                    </div>

                    <div class="input-container">
                        <label for="mensagemBusca">Messagem:</label>
                        <input type="text" name="mensagemBusca" class="form-control" placeholder="Digite parte da mensagem..." id="mensagemBusca" />
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="btnPesquisar" type="submit"><i class="fa fa-search"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf"></i> Limpar</button>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-dados-gerenciamento" style='margin-bottom: 20px; position: relative; <?= $menu_ocr == 'DadosGerenciamentoOCR' ? '' : 'display: none;' ?>'>
            <h3>
                <div class="registrosDiv">
                    <select id="select-quantidade-por-pagina-dados" class="form-control" style="float: left; width: auto; height: 34px;">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <h6 class="label_input" style="font-weight:normal; margin-left: 5px; color: #7F7F7F !important;">Registros por página</h6>
                </div>
                <div class="btn-div-responsive" id="btn-div-alertas">
                    <button class="btn btn-primary" id="BtnAdicionar" type="button" style="margin-right: 10px; margin-bottom: 5px;"><i class="fa fa-plus" aria-hidden="true"></i> Cadastrar</button>
                    <div class="dropdown" style="margin-right: 10px; margin-bottom: 5px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px; margin-bottom: 5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            
            <div style="position: relative;">
                <div class="wrapper">
                    <div id="table" class="ag-theme-alpine my-grid" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalSMS" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id='formSms'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleSMS">Cadastrar SMS Personalizado</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <input type="text" hidden name='idSMS' id="idSMS" value='' />
                        <div class='row'>
                            <div class="col-md-12 input-container form-group">
                                <label for="descricao">Descrição: <span class="text-danger">*</span></label>
                                <input type="text" name="descricao" maxlength="255" class="form-control" placeholder="Digite a Descrição" id="descricao" required />
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="mensagem">Mensagem: <span class="text-danger">*</span></label>
                                <textarea name="mensagem" rows="4" class="form-control" placeholder="Digite a mensagem" id="mensagem" style="resize: vertical;" required></textarea>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <p><i><strong>Use os itens abaixo para indicar valores personalizados na mensagem:</strong></i></p>
                            <p>
                                Ex.: {dd/mm} - Exibe 15/03<br>
                                
                                <span class="badge badge-info" style="font-weight: bold;">{dd/mm} - dia/mês</span>
                                <span class="badge badge-info">{hh:mm} - hora:minuto</span>
                                <span class="badge badge-info">{placa} - MMM-1111</span>
                                <span class="badge badge-info">{prefixo} - 1234</span>
                                
                            </p>
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id='btnSalvar'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/configuracoes/notifica', 'SMSPersonalizado.js') ?>"></script>
<script>
    var Router = '<?= site_url('configuracoes') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>