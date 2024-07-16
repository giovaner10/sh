<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= $titulo ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('relatorios') ?> >
        <?= $titulo ?>
    </h4>
</div>


<div id="loading">
    <div class="loader"></div>
</div>


<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">

                    <div class="input-container buscaData" id="dateContainer1">
                        <label for="informe">Informe um intervalo de até 31 dias.</label>
                    </div>

                    <div class="input-container buscaData" id="dateContainer1">
                        <label for="dataInicial">Data Inicial:</label>
                        <div style="display: flex; align-items: center;">
                            <input type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" />
                            <input type="time" step="1" name="horaInicial" class="data formatInput form-control" placeholder="Hora Início" autocomplete="off" id="horaInicial" style='margin-left: 10px;' />
                        </div>
                    </div>

                    <div class="input-container buscaData" id="dateContainer1">
                        <label for="dataFoamç">Data Final:</label>
                        <div style="display: flex; align-items: center;">
                            <input type="date" name="dataFoamç" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataFim" />
                            <input type="time" step="1" name="horaFinal" class="data formatInput form-control" placeholder="Hora Fim" autocomplete="off" id="horaFinal" style='margin-left: 10px;' />
                        </div>
                    </div>

                    <div class="input-container buscaSerial buscaSerial">
                        <label for="buscaSerial">Serial:</label>
                        <input type="text" name="buscaSerial" class="form-control" placeholder="Digite o Serial..." id="buscaSerial" />
                    </div>

                    <div class="input-container buscaStatus">
                        <label for="status">Status:</label>
                        <select class="form-control" name="status" id="status" style="width: 100%;">
                            <option value="" selected disabled>Selecione o Status...</option>
                            <option value="C">Cancelado</option>
                            <option value="X">Cancelado por excesso de tentativa</option>
                            <option value="W">Comando enviado para execução</option>
                            <option value="F">Enviado</option>
                            <option value="ERROR">Erro</option>
                            <option value="IC_E">Erro de execução</option>
                            <option value="IC_E">Executado</option>
                            <option value="IC_P">Pendente</option>
                            <option value="P">Processado e enviado</option>
                            <option value="">Todos</option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success" style="width:100%;" id="gerarRelatorio" type="submit"><i class="fa fa-file-alt"></i> Gerar Relatório</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="btnLimpar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>

                    </div>

                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-comandos" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b><?= $titulo ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
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
                <select id="select-quantidade-por-pagina-comandos" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div style="position: relative;">
                <div class="wrapperComandos">
                    <div id="tableComandos" class="ag-theme-alpine my-grid-comandos" style="height: 500px">
                    </div>
                </div>
                <h4 id='notaAgGrid'>Nota: Caso os parâmetros de data e hora não sejam informados, o relatório gerado considera os últimos 31 dias como padrão.</h4>
            </div>
        </div>

    </div>
</div>


<!-- <div class="row justify-content-center m-0">
    <div class="col-sm-12 px-0">
        <div class="input-container ">
            <div class="row">
                <h4 style="text-align: center; margin-bottom: 20px">Informe um intervalo de, no máximo, 31 dias.</h4>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" title="Data Início"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                        <input type="date" class="form-control" id="dataInicio" name="dataInicio"  aria-describedby="basic-addon1" >
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" title="Hora Início"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                        <input type="text" class="form-control time" id="horaInicio" name="horaInicio"  aria-describedby="basic-addon1" value="00:00:00">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" title="Data Fim"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                        <input type="date" class="form-control" id="dataFim" name="dataFim" aria-describedby="basic-addon1" >
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" title="Hora Fim"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                        <input type="text" class="form-control time" id="horaFim" name="horaFim" aria-describedby="basic-addon1" value="23:59:59">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group" >
                    <span class="input-group-addon" id="basic-addon1" title="Status do Comando"><i class="fa fa-rss" aria-hidden="true"></i></span>
                        <select class="form-control" name="status" id="status" >
                            <option selected disabled>Status</option>
                            <option value="confirmado">Confirmado</option>
                            <option value="aguardando_envio">Aguardando Envio</option>
                            <option value="aguardando_confirmacao">Aguardando Confirmação</option>
                            <option value="todos">Todos</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                    <button type="button" id="gerarRelatorioComandos" class="btn btn-primary">
                        Gerar Relatório
                    </button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<div class="row justify-content-center" style="display:none" id="displayrelatorioComandos">
    <div class="col-sm-12">
        <table id="relatorioComandos" class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Equipamento</th>
                    <th>Comando</th>
                    <th>Data Cadastro</th>
                    <th>Data Envio</th>
                    <th>Data Conf.</th>
                    <th>Status</th>
                    <th>Usuário</th>
                    <th>Cliente</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>    
</div> -->



<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/iscas/relatorioComandoIsca', 'layout.css') ?>">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script type="text/javascript" src="<?= base_url("media/js/helpers/iscas-helper.js") ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/iscas/relatorioComandoIsca', 'relatorioComandoIsca.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/iscas/relatorioComandoIsca', 'Exportacoes.js') ?>"></script>

<script>
    var Router = '<?= site_url('iscas/comandos_isca') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>