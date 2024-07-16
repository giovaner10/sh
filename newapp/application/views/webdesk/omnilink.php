<div class="col-md-9 conteudo" id="conteudo">
    <div class="card-conteudo card-dados-omnilink" style='margin-bottom: 20px;'>
        <h3>
            <b>Omnilink: </b>
            <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                <div class="dropdown" style="margin-right: 10px;">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonOmni" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                        <?= lang('exportar') ?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonOmni" id="opcoes_exportacao_omni" style="min-width: 100px; top: 62px; height: 91px;">
                    </div>
                </div>
                <a href="<?php echo site_url('webdesk/ranking')?>" style="margin-right: 10px;" target="_blank" title="Ranking Abertura de Tickets" class="btn btn-primary"><i class="fa fa-bar-chart" aria-hidden="true"></i> Ranking</a>
                <button title="Novo Ticket" id="novoTicket" style="margin-right: 10px;" class="btn btn-primary novoTicket" data-empresa='OMNILINK' data-toggle='modal' data-target='#novo_ticket'>
                    <i class="fa fa-plus"></i>
                    Novo Ticket
                </button>
                <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                    <img id="img_grid_expandir" class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                </button>
            </div>
        </h3>
        <div style="margin-bottom: 15px;">
            <select id="select-quantidade-por-pagina-omnilink" class="form-control" style="width: 100px; float: left; margin-top: 10px;">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div> 
        <div id="emptyMessageOmnilink" style="display: none;">
            <h4><b>Nenhum dado a ser listado.</b></h4> 
        </div>
        <div id="loadingMessageOmnilink" class="loadingMessage" style="top: 57%; left: 50%; transform: translate(-57%, -50%);">
            <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
        </div>  
            <div class="wrapperOmnilink">
                <div id="tableOmnilink" class="ag-theme-alpine my-grid-omnilink">
            </div>
        </div>
    </div>
</div>


