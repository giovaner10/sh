<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("vendas_software") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('departamentos') ?> >
        <?= lang('comercial_e_televendas') ?> >
        <?= lang('vendas_software') ?>
    </h4>
</div>

<?php
$menu_vSoftware = $_SESSION['menu_vendaSoftware'];
?>
<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;" <?php echo !(in_array('vis_vendas_de_software_produtos', $permissoes)) && !(in_array('vis_vendas_de_software_propostas', $permissoes)) && !(in_array('vis_vendas_de_software_autorizacoes', $permissoes)) && !(in_array('vis_vendas_de_software_clientes', $permissoes)) && !(in_array('vis_vendas_de_software_kanban', $permissoes)) && !(in_array('vis_vendas_de_software_oportunidades', $permissoes)) && !(in_array('vis_vendas_de_software_kanban_autorizacao', $permissoes)) ? "" : "hidden" ?>>
    <div class="col-md-6"> 
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important; color: #ca0f0f !important">Acesso negado!</h4>
            <h5 style="margin-bottom: 0px !important;">Você não possui permissão para acessar o conteúdo dessa página.</h5>
        </div>
    </div>
</div>

<div class="row" style="margin: 15px 0 0 15px;" <?php echo !(in_array('vis_vendas_de_software_produtos', $permissoes)) && !(in_array('vis_vendas_de_software_propostas', $permissoes)) && !(in_array('vis_vendas_de_software_autorizacoes', $permissoes)) && !(in_array('vis_vendas_de_software_clientes', $permissoes)) && !(in_array('vis_vendas_de_software_kanban', $permissoes)) && !(in_array('vis_vendas_de_software_oportunidades', $permissoes)) && !(in_array('vis_vendas_de_software_kanban_autorizacao', $permissoes)) ? "hidden" : "" ?>>
    <div class="col-md-3"> 
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
            <?php
                $selectedMenu = false;
                $menuSelecionado = '';
                if (in_array('vis_vendas_de_software_clientes', $permissoes)) {
                    echo "<li><a class='menu-interno-link ".(!$selectedMenu ? 'selected' : '')."' id='menu-cliente'>Clientes</a></li>";
                    if (!$selectedMenu){
                        $menuSelecionado = 'Cliente';
                    }
                    $selectedMenu = true;
                }
                if (in_array('vis_vendas_de_software_produtos', $permissoes)) {
                    echo "<li><a class='menu-interno-link ".(!$selectedMenu ? 'selected' : '')."' id='menu-produto'>Produtos</a></li>";
                    if (!$selectedMenu){
                        $menuSelecionado = 'Produto';
                    }
                    $selectedMenu = true;
                }

                if (in_array('vis_vendas_de_software_oportunidades', $permissoes)) {
                    echo "<li><a class='menu-interno-link ".(!$selectedMenu ? 'selected' : '')."' id='menu-oportunidade'>Oportunidades</a></li>";
                    if (!$selectedMenu){
                        $menuSelecionado = 'Oportunidade';
                    }
                    $selectedMenu = true;
                }

                if (in_array('vis_vendas_de_software_propostas', $permissoes)) {
                    echo "<li><a class='menu-interno-link ".(!$selectedMenu ? 'selected' : '')."' id='menu-proposta'>Propostas</a></li>";
                    if (!$selectedMenu){
                        $menuSelecionado = 'Proposta';
                    }
                    $selectedMenu = true;
                }
                if (in_array('vis_vendas_de_software_autorizacoes', $permissoes)) {
                    echo "<li><a class='menu-interno-link ".(!$selectedMenu ? 'selected' : '')."' id='menu-autorizacao-faturamento'>Autorização de Faturamento</a></li>";
                    if (!$selectedMenu){
                        $menuSelecionado = 'Autorizacao';
                    }
                    $selectedMenu = true;
                }
                if (in_array('vis_vendas_de_software_kanban_autorizacao', $permissoes)) {
                    echo "<li><a class='menu-interno-link ".(!$selectedMenu ? 'selected' : '')."' id='menu-kanban-autorizacao'>Kanban de Autorizações</a></li>";
                    if (!$selectedMenu){
                        $menuSelecionado = 'KanbanAutorizacao';
                    }
                    $selectedMenu = true;
                }
            ?>
            </ul>
        </div>
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtroProdutos" <?php echo $menuSelecionado == 'Produto' ? "" : "hidden" ?> >
                    <div class="input-container">
                        <label for="selectTipoBuscaProduto">Buscar por:</label>
                        <select class="form-control" name="selectTipoBuscaProduto" id="selectTipoBuscaProduto" type="text" style="width: 100%;">
                            <option value="0" selected>ID</option>
                            <option value="1">Nome</option>
                        </select>
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divId">
                        <label for="buscaId">ID:</label>
                        <input type="number" name="id" class="form-control" placeholder="Digite o ID" id="buscaId" required />
                    </div>
                    <div class="input-container divNome" hidden>
                        <label for="buscaNome">Nome:</label>
                        <input type="text" name="nome" class="form-control" placeholder="Digite o nome" id="buscaNome" />
                    </div>
                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisar" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimpar" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
            <form id="formBuscaProposta">
                <div class="form-group filtroPropostas" <?php echo $menuSelecionado == 'Proposta' ? "" : "hidden" ?> >
                    <div class="input-container divDocumentoProposta">
                        <label for="buscaDocumentoProposta">Documento do cliente:</label>
                        <input type="text" name="documento" class="form-control" placeholder="Digite o CPF ou CNPJ" id="buscaDocumentoProposta" />
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divVendedorProposta">
                        <label for="buscaVendedorProposta">Vendedor:</label>
                        <select class="form-control" name="idVendedor" id="buscaVendedorProposta" type="text" style="width: 100%;">
                        </select>
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divDataInicialProposta">
                        <label for="buscaDataInicialProposta" class="control-label">Data Inicial:</label>
                        <input type="date" name="dataInicial" class="form-control" placeholder="Digite a data inicial" id="buscaDataInicialProposta" required />
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divDataFinalProposta">
                        <label for="buscaDataFinalProposta" class="control-label">Data Final:</label>
                        <input type="date" name="dataFinal" class="form-control" placeholder="Digite a data final" id="buscaDataFinalProposta" required />
                    </div>
                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisarProposta" title="Filtrar dados" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparProspota" title="Limpar filtro e recarregar tabela" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
            <form id="formBuscaAutorizacaoFaturamento">
                <div class="form-group filtroAutorizacaoFaturamento" <?php echo $menuSelecionado == 'Autorizacao' ? "" : "hidden" ?> >
                    <div class="input-container divIdPropostaAutorizacaoFaturamento">
                        <label for="buscaIdPropostaAutorizacaoFaturamento">ID da proposta:</label>
                        <input type="number" name="idProposta" class="form-control" placeholder="Digite o ID da proposta" id="buscaIdPropostaAutorizacaoFaturamento" />
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divDocumentoAutorizacaoFaturamento">
                        <label for="buscaDocumentoAutorizacaoFaturamento">Documento do cliente:</label>
                        <input type="text" name="documento" class="form-control" placeholder="Digite o CPF ou CNPJ" id="buscaDocumentoAutorizacaoFaturamento" />
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divVendedorAutorizacaoFaturamento">
                        <label for="buscaVendedorAutorizacaoFaturamento">Vendedor:</label>
                        <select class="form-control" name="idVendedor" id="buscaVendedorAutorizacaoFaturamento" type="text" style="width: 100%;">
                        </select>
                    </div>
                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisarAutorizacaoFaturamento" title="Filtrar dados" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparAutorizacaoFaturamento" title="Limpar filtro e recarregar tabela" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
            <form id="formBuscaCliente">
                <div class="form-group filtroClientes" <?php echo $menuSelecionado == 'Cliente' ? "" : "hidden" ?> >
                    <div class="input-container">
                        <label for="selectTipoBuscaCliente">Buscar por:</label>
                        <select class="form-control" name="selectTipoBuscaCliente" id="selectTipoBuscaCliente" type="text" style="width: 100%;">
                            <option value="0" selected>ID</option>
                            <option value="1">Nome</option>
                            <option value="2">Documento</option>
                        </select>
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divIdCliente">
                        <label for="buscaIdCliente">ID:</label>
                        <input type="number" name="id" class="form-control" placeholder="Digite o ID" id="buscaIdCliente" min="1" required />
                    </div>
                    <div class="input-container divNomeCliente" hidden>
                        <label for="buscaNomeCliente">Nome:</label>
                        <input type="text" name="nome" class="form-control" placeholder="Digite o nome" id="buscaNomeCliente" />
                    </div>
                    <div class="input-container divDocumentoCliente" hidden>
                        <label for="buscaDocumentoCliente">Documento:</label>
                        <input type="text" name="documento" class="form-control" placeholder="Digite o CPF ou CNPJ" id="buscaDocumentoCliente" />
                    </div>
                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisarCliente" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparCliente" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
            <!-- <form id="formBuscaKanbanPropostas">
                <div class="form-group filtroKanbanPropostas" <?php echo $menuSelecionado == 'Kanban' ? "" : "hidden" ?> >
                    <div class="input-container divDocumentoKanbanPropostas">
                        <label for="buscaDocumentoKanbanPropostas">Documento do cliente:</label>
                        <input type="text" name="documento" class="form-control" placeholder="Digite o CPF ou CNPJ" id="buscaDocumentoKanbanPropostas" />
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divVendedorKanbanPropostas">
                        <label for="buscaVendedorKanbanPropostas">Vendedor:</label>
                        <select class="form-control" name="idVendedor" id="buscaVendedorKanbanPropostas" type="text" style="width: 100%;">
                        </select>
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divDataInicialKanbanPropostas">
                        <label for="buscaDataInicialKanbanPropostas" class="control-label">Data Inicial:</label>
                        <input type="date" name="dataInicial" class="form-control" placeholder="Digite a data inicial" id="buscaDataInicialKanbanPropostas" required />
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divDataFinalKanbanPropostas">
                        <label for="buscaDataFinalKanbanPropostas" class="control-label">Data Final:</label>
                        <input type="date" name="dataFinal" class="form-control" placeholder="Digite a data final" id="buscaDataFinalKanbanPropostas" required />
                    </div>
                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisarKanbanPropostas" title="Filtrar dados" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparKanbanPropostas" title="Limpar filtro e recarregar tabela" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form> -->
            <form id="formBuscaOportunidade">
                <div class="form-group filtroOportunidades" <?php echo $menuSelecionado == 'Oportunidade' ? "" : "hidden" ?> >
                    <div class="input-container divDocumentoOportunidade">
                        <label for="buscaDocumentoOportunidade">Documento do cliente:</label>
                        <input type="text" name="documento" class="form-control" placeholder="Digite o CPF ou CNPJ" id="buscaDocumentoOportunidade" />
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divVendedorOportunidade">
                        <label for="buscaVendedorOportunidade">Vendedor:</label>
                        <select class="form-control" name="idVendedor" id="buscaVendedorOportunidade" type="text" style="width: 100%;">
                        </select>
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divDataInicialOportunidade">
                        <label for="buscaDataInicialOportunidade" class="control-label">Data Inicial:</label>
                        <input type="date" name="dataInicial" class="form-control" placeholder="Digite a data inicial" id="buscaDataInicialOportunidade" required />
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divDataFinalOportunidade">
                        <label for="buscaDataFinalOportunidade" class="control-label">Data Final:</label>
                        <input type="date" name="dataFinal" class="form-control" placeholder="Digite a data final" id="buscaDataFinalOportunidade" required />
                    </div>
                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisarOportunidade" title="Filtrar dados" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparOportunidade" title="Limpar filtro e recarregar tabela" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
            <form id="formBuscaKanbanAutorizacaoFaturamento">
                <div class="form-group filtroKanbanAutorizacaoFaturamento" <?php echo $menuSelecionado == 'KanbanAutorizacao' ? "" : "hidden" ?> >
                    <div class="input-container divIdKanbanAutorizacaoFaturamento">
                        <label for="buscaIdKanbanAutorizacaoFaturamento">ID da proposta:</label>
                        <input type="number" name="idProposta" class="form-control" placeholder="Digite o ID da proposta" id="buscaIdKanbanAutorizacaoFaturamento" />
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divDocumentoKanbanAutorizacaoFaturamento">
                        <label for="buscaDocumentoKanbanAutorizacaoFaturamento">Documento do cliente:</label>
                        <input type="text" name="documento" class="form-control" placeholder="Digite o CPF ou CNPJ" id="buscaDocumentoKanbanAutorizacaoFaturamento" />
                    </div>
                    <span class="help help-block"></span>
                    <div class="input-container divVendedorKanbanAutorizacaoFaturamento">
                        <label for="buscaVendedorKanbanAutorizacaoFaturamento">Vendedor:</label>
                        <select class="form-control" name="idVendedor" id="buscaVendedorKanbanAutorizacaoFaturamento" type="text" style="width: 100%;">
                        </select>
                    </div>
                    <div class="button-container">
                        <button class="btn btn-success" style='width:100%' id="BtnPesquisarKanbanAutorizacaoFaturamento" title="Filtrar dados" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparKanbanAutorizacaoFaturamento" title="Limpar filtro e recarregar tabela" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-produtos" style='<?php echo $menuSelecionado == 'Produto' ? '' : 'display: none;' ?>'>
            <h3>
                <b>Produtos: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-primary" id="BtnAdicionarProduto" type="button" style="margin-right: 10px;"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</button>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div style="margin-bottom: 15px;">
                <select id="select-quantidade-por-pagina-produtos" class="form-control" style="width: fit-content; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select><label style="margin-top: 15px; margin-left: 10px; float: left;">Resultados por página</label>
                <input class="form-control inputBusca" type="text" id="search-input-show-produtos" placeholder="Pesquisar" style="margin-top: 10px; float: right;">
            </div>
            <div class="wrapperProdutos">
                <div id="tableProdutos" style="height:530px;" class="ag-theme-alpine my-grid">
                </div>
            </div>
        </div> 
        <div class="card-conteudo card-propostas" style='<?php echo $menuSelecionado == 'Proposta' ? '' : 'display: none;' ?>'>
            <h3>
                <b>Propostas: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonPropostas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;" title="Exportar dados da tabela">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_propostas" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                        <div id="vendedor">
                            <button class="btn btn-primary" id="BtnAdicionarProposta" type="button" style="margin-right: 10px;" title="Adicionar proposta"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</button>
                        </div>                   
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div style="margin-bottom: 15px;">
                <select id="select-quantidade-por-pagina-propostas" class="form-control" style="width: fit-content; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select><label style="margin-top: 15px; margin-left: 10px; float: left;">Resultados por página</label>
                <input class="form-control inputBusca" type="text" id="search-input-show-propostas" placeholder="Pesquisar" style="margin-top: 10px; float: right;">
            </div>
            <div class="wrapperPropostas">
                <div id="tablePropostas" style="height:530px;" class="ag-theme-alpine my-grid">
                </div>
            </div>
        </div>
        <div class="card-conteudo card-autorizacao-faturamento" style='<?php echo $menuSelecionado == 'Autorizacao' ? '' : 'display: none;' ?>'>
            <h3>
                <b>Autorização de Faturamento: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonAutorizacaoFaturamento" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;" title="Exportar dados da tabela">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_autorizacao_faturamento" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expand
                        ir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div style="margin-bottom: 15px;">
                <select id="select-quantidade-por-pagina-autorizacao-faturamento" class="form-control" style="width: fit-content; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select><label style="margin-top: 15px; margin-left: 10px; float: left;">Resultados por página</label>
            </div>
            <div class="wrapperAutorizacaoFaturamento">
                <div id="tableAutorizacaoFaturamento" style="height:530px;" class="ag-theme-alpine my-grid">
                </div>
            </div>
        </div>
        <div class="card-conteudo card-clientes" style='<?php echo $menuSelecionado == 'Cliente' ? '' : 'display: none;' ?>'>
            <h3>
                <b>Clientes: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonClientes" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;" title="Exportar dados da tabela">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonClientes" id="opcoes_exportacao_clientes" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-primary" id="BtnAdicionarCliente" type="button" style="margin-right: 10px;" title="Adicionar cliente"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</button>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div style="margin-bottom: 15px;">
                <select id="select-quantidade-por-pagina-clientes" class="form-control" style="width: fit-content; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select><label style="margin-top: 15px; margin-left: 10px; float: left;">Resultados por página</label>
            </div>
            <div class="wrapperClientes">
                <div id="tableClientes" style="height:530px;" class="ag-theme-alpine my-grid">
                </div>
            </div>
        </div>
        <div class="card-conteudo card-kanban-propostas" style='<?php echo $menuSelecionado == 'Kanban' ? '' : 'display: none;' ?>'>
            <h3>
                <b>Kanban de Propostas: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">               
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="kanban-board-container">
                <div class="kanban-board">
                    <div class="kanban-column nao-integrado-column-gerenciamento" id="nao-integrado-column-gerenciamento">
                        <div class="kanban-header-gerenciamento">
                            <div class="dados-header">
                                Não Integrado <span class="kanban-count-gerenciamento">0</span>
                            </div>
                        </div>
                        <div class="kanban-cards kanban-cards-propostas">
                
                        </div>
                    </div>
                    <div class="kanban-column integrado-column-gerenciamento" id="integrado-column-gerenciamento">
                        <div class="kanban-header-gerenciamento">
                            <div class="dados-header">
                                Integrado <span class="kanban-count-gerenciamento">0</span>
                            </div>
                        </div>
                        <div class="kanban-cards kanban-cards-propostas">
                
                        </div>
                    </div>
                    <div class="kanban-column atualizado-column-gerenciamento" id="atualizado-column-gerenciamento">
                        <div class="kanban-header-gerenciamento">
                            <div class="dados-header">
                                Atualizado <span class="kanban-count-gerenciamento">0</span>
                            </div>
                        </div>
                        <div class="kanban-cards kanban-cards-propostas">
                
                        </div>
                    </div>
                    <div class="kanban-column faturado-column-gerenciamento" id="faturado-column-gerenciamento">
                        <div class="kanban-header-gerenciamento">
                            <div class="dados-header">
                                Faturado <span class="kanban-count-gerenciamento">0</span>
                            </div>
                        </div>
                        <div class="kanban-cards kanban-cards-propostas">
                
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-conteudo card-oportunidades" style='<?php echo $menuSelecionado == 'Oportunidade' ? '' : 'display: none;' ?>'>
            <h3>
                <b>Oportunidades: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonOportunidade" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;" title="Exportar dados da tabela">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_oportunidade" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-primary" id="BtnAdicionarOportunidade" type="button" style="margin-right: 10px;" title="Adicionar oportunidade"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</button>
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expand
                        ir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div style="margin-bottom: 15px;">
                <select id="select-quantidade-por-pagina-oportunidade" class="form-control" style="width: fit-content; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select><label style="margin-top: 15px; margin-left: 10px; float: left;">Resultados por página</label>
            </div>
            <div class="wrapperOportunidade">
                <div id="tableOportunidades" class="ag-theme-alpine my-grid" style="height: 530px">
                </div>
            </div>
        </div>
        <div class="card-conteudo card-kanban-autorizacao" style='<?php echo $menuSelecionado == 'KanbanAutorizacao' ? '' : 'display: none;' ?>'>
            <h3>
                <b>Kanban de Autorizações: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">               
                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>
                </div>
            </h3>
            <div class="kanban-board-container">
                <div class="kanban-board">
                    <div class="kanban-column aguardando-column-autorizacao" id="aguardando-column-autorizacao">
                        <div class="kanban-header-gerenciamento">
                            <div class="dados-header">
                                Aguardando <span class="kanban-count-autorizacao">0</span>
                            </div>
                        </div>
                        <div class="kanban-cards kanban-cards-autorizacao">
                
                        </div>
                    </div>
                    <div class="kanban-column enviado-column-autorizacao" id="enviado-column-autorizacao">
                        <div class="kanban-header-gerenciamento">
                            <div class="dados-header">
                                Enviado <span class="kanban-count-autorizacao">0</span>
                            </div>
                        </div>
                        <div class="kanban-cards kanban-cards-autorizacao">
                
                        </div>
                    </div>
                    <div class="kanban-column reenviado-column-autorizacao" id="reenviado-column-autorizacao">
                        <div class="kanban-header-gerenciamento">
                            <div class="dados-header">
                                Reenviado <span class="kanban-count-autorizacao">0</span>
                            </div>
                        </div>
                        <div class="kanban-cards kanban-cards-autorizacao">
                
                        </div>
                    </div>
                    <div class="kanban-column recusado-column-autorizacao" id="recusado-column-autorizacao">
                        <div class="kanban-header-gerenciamento">
                            <div class="dados-header">
                                Recusado <span class="kanban-count-autorizacao">0</span>
                            </div>
                        </div>
                        <div class="kanban-cards kanban-cards-autorizacao">
                
                        </div>
                    </div>
                    <div class="kanban-column autorizado-column-autorizacao" id="autorizado-column-autorizacao">
                        <div class="kanban-header-gerenciamento">
                            <div class="dados-header">
                                Autorizado <span class="kanban-count-autorizacao">0</span>
                            </div>
                        </div>
                        <div class="kanban-cards kanban-cards-autorizacao">
                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalProduto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id='formProduto'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleModalProduto">Adicionar Produto</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Dados do Produto</h4>
                        <input type="text" hidden name="id" id="idProduto" value='0' />
                        <input type="text" hidden name="status" id="statusProduto" value='0' />
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="nomeProduto" class="control-label">Nome:</label>
                                <input type="text" name="nomeProduto" class="form-control" placeholder="Digite o nome do produto" id="nomeProduto" required />
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="idCrm" class="control-label">ID CRM:</label>
                                <input type="text" name="idCrm" class="form-control" id="idCrm" placeholder="Digite o id do produto no CRM" required />
                            </div>
                            <div class="col-md-6 input-container pocDiv form-group">
                                <label for="permitePoc" class="control-label">Permite POC:</label>
                                <select class="form-control pocInput" name="permitePoc" id="permitePocProduto" type="text" style="width: 100%;" required>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container pocDiv form-group diasPOC">
                                <label for="diasValidadePoc" class="control-label">Dias de Validade da POC:</label>
                                <input type="text" name="diasValidadePoc" class="form-control pocInput" placeholder="Digite a quantidade de dias" id="diasValidadePoc" required />
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="precoUnitario" class="control-label">Preço Unitário:</label>
                                <input type="text" name="precoUnitario" class="form-control" id="precoUnitario" onkeyup="formataMoedaInput(this.id)" placeholder="Digite o preço unitário" required />
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="tipoProduto" class="control-label">Tipo:</label>
                                <select class="form-control" name="tipoProduto" id="tipoProduto" type="text" style="width: 100%;" required>
                                    <option value="" disabled selected>Selecione o tipo</option>
                                    <option value="0">Software</option>
                                    <option value="1">Hardware</option>
                                    <option value="2">Acessório</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="validaQuantidade" class="control-label">Valida quantidade:</label>
                                <select class="form-control" name="validaQuantidade" id="validaQuantidade" type="text" style="width: 100%;" required>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group qtd">
                                <label for="quantidadeMinima" class="control-label">Quantidade Mínima:</label>
                                <input type="number" name="quantidadeMinima" class="form-control" placeholder="Digite a quantidade mínima" id="quantidadeMinima" required />
                            </div>
                            <div class="col-md-6 input-container form-group qtd">
                                <label for="quantidadeMaxima" class="control-label">Quantidade Máxima:</label>
                                <input type="number" name="quantidadeMaxima" class="form-control" placeholder="Digite a quantidade máxima" id="quantidadeMaxima" required />
                            </div>
                            <div class="col-md-6 input-container form-group composicaoInput">
                                <label for="temComposicao" class="control-label">Tem Composição:</label>
                                <select class="form-control" name="temComposicao" id="temComposicao" type="text" style="width: 100%;" required>
                                    <option value="0" default>Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" id="produtosComposicao" style="display: none;">
                        <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Composição</h4>
                        <div class='row' id="composicaoAddProduto">
                            <div class="col-md-12 input-container form-group">
                                <label for="produtoComposicao">Produto:</label>
                                <select class="form-control" name="produtoComposicao" id="produtoComposicao" type="text" style="width: 100%;">
                                    <option value="" disabled selected>Selecione o produto</option>
                                </select>
                            </div>
                            <div class="col-md-12 form-group" style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                                <button type="button" class="btn btn-success" data-id="" id='btnAdicionarComposicaoTabela' title="Adicionar">Adicionar à tabela</button>
                                <button type="button" class="btn" id="limparTabelaComposicao" style="background-color: red; color: white;">Limpar Tabela</button>
                            </div>
                        </div>
                        <div class='row' id="composicaoEditProduto">
                            <div class="col-md-12 form-group">
                                <button type="button" class="btn btn-primary" data-idProduto="" id='btnAdicionaNovoProdutoComposicao' title="Adicionar"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar item</button>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-12 form-group">
                                <div class="wrapperItensComposicao">
                                    <div id="tableItensComposicao" class="ag-theme-alpine my-grid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" data-id="" data-temComposicao="" id='btnSalvarProduto'>Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modalItemComposicao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form id='formItensComposicaoEdit'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleModalItemComposicao">Adicionar Produto à Composição</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <input type="text" hidden name="idProdutoPrincipal" id="idProdutoPrincipal" value='0' />
                        <div class='row'>
                            <div class="col-md-12 input-container form-group">
                                <label for="produtoItemComposicaoEdit" class="control-label">Produto:</label>
                                <select class="form-control" name="idProdutoComposicao" id="produtoItemComposicaoEdit" type="text" style="width: 100%;" required>
                                    <option value="" disabled selected>Selecione o produto</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Fechar">Fechar</button>
                        <button type="submit" class="btn btn-success" id='btnSalvarItensComposicaoEdit' title="Salvar">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modalProposta" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalProposta">Adicionar Proposta</h3>
            </div>
            <ul class="nav nav-tabs" style="margin-bottom: 15px; margin-left: 5px">
                <li class="nav-item">
                    <a id="tab-dadosProposta" href="" data-toggle="tab" class="nav-link active">Dados da Proposta <i class="fa fa-caret-right" aria-hidden="true" id="iconeCareRight"></i></a>
                </li>
                <li class="nav-item">
                    <a id="tab-itensDaProposta" href="" data-toggle="tab" class="nav-link" type="button">Itens da Proposta</a>
                </li>
                <li class="nav-item">
                    <a id="tab-itensDaPropostaEdit" href="" data-toggle="tab" class="nav-link" type="button">Itens da Proposta</a>
                </li>
            </ul>
            <div class="modal-body" id="bodyDadosProposta">
                <div class="col-md-12">
                    <div id="divDadosProposta">
                    <form id='formProposta'>
                        <input type="text" hidden name="id" id="idProposta" value='0' />
                        <input type="text" hidden name="status" id="statusProposta" value='0' />
                        <input type="text" hidden name="valorTotal" id="valorTotalProposta" value='0' />
                        <input type="text" hidden name="quantidadeTotal" id="quantidadeTotalProposta" value='0' />
                        <input type="text" hidden name="af" id="afProposta" value='0' />
                        <input type="text" hidden name="idOportunidade" id="idOportunidadeProposta" value='0' />
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="idCliente" class="control-label">Cliente:</label>
                                <span id="spanSemEndereco" hidden>Cliente sem endereço cadastrado.</span>
                                <i class="fa fa-spinner fa-spin" id="spinnerCliente"></i>
                                <select class="form-control" name="idCliente" id="idCliente" type="text" style="width: 100%;" required>
                                    <option value="" disabled selected>Selecione o cliente</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="idVendedor" class="control-label">Vendedor:</label>
                                <?php if (!$this->auth->is_allowed_block('vis_vendas_de_software_propostas_adicionar')) : ?>
                                    <select class="form-control" name="idVendedor" id="idVendedor" type="text" style="width: 100%;" required readonly>
                                        <option value="" disabled>Selecione o vendedor</option>
                                    </select>
                                    <?php else : ?>
                                    <select class="form-control" name="idVendedor" id="idVendedor" type="text" style="width: 100%;" required>
                                        <option value="" disabled selected>Selecione o vendedor</option>
                                    </select>
                                <?php endif; ?>
                                    
                            </div>
                        </div>
                        <div id="divEnderecoCliente" hidden>
                            <h4 class="subtitle tituloEnderecoCliente" style="padding-left: 0; padding-right: 0;">Endereço</h4>
                            <div class='row'>  
                                <div class="col-md-2 form-group divEnderecoClienteCampos">
                                    <label class="labelEndecoCliente">Cep:</label>
                                    <span id="spanCepEndereco"></span>
                                </div> 
                                <div class="col-md-8 form-group divEnderecoClienteCampos">
                                    <label class="labelEndecoCliente">Rua:</label>
                                    <span id="spanRuaEndereco"></span>
                                </div>
                                <div class="col-md-2 form-group divEnderecoClienteCampos">
                                    <label class="labelEndecoCliente">Número:</label>
                                    <span id="spanNumeroEndereco"></span>
                                </div>
                            </div>
                            <div class='row'>
                                <div class="col-md-6 form-group divEnderecoClienteCampos">
                                    <label class="labelEndecoCliente">Bairro:</label>
                                    <span id="spanBairroEndereco"></span>
                                </div>
                                <div class="col-md-4 form-group divEnderecoClienteCampos">
                                    <label class="labelEndecoCliente">Cidade:</label>
                                    <span id="spanCidadeEndereco"></span>
                                </div>
                                <div class="col-md-2 form-group divEnderecoClienteCampos">
                                    <label class="labelEndecoCliente">UF:</label>
                                    <span id="spanEstadoEndereco"></span>
                                </div>
                            </div>
                            <div class='row'>
                                <div class="col-md-12 form-group divEnderecoClienteCampos">
                                    <label class="labelEndecoCliente">Complemento:</label>
                                    <span id="spanComplementoEndereco"></span>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="nomeAutorizador" class="control-label">Autorizador:</label>
                                <input type="text" name="nomeAutorizador" class="form-control" placeholder="Digite o nome do autorizador" maxlength="60" minlength="3" id="nomeAutorizador" required/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="documentoAutorizador" class="control-label">Documento do Autorizador:</label>
                                <input type="text" name="documentoAutorizador" class="form-control" placeholder="Digite o CPF ou CNPJ do autorizador" id="documentoAutorizador" required/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="emailAutorizador" class="control-label">E-mail do Autorizador:</label>
                                <input type="email" name="emailAutorizador" class="form-control" placeholder="Digite o e-mail do autorizador" maxlength="120" minlength="3" id="emailAutorizador" required/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="telefoneAutorizador" class="control-label">Telefone do Autorizador:</label>
                                <input type="text" name="telefoneAutorizador" class="form-control" placeholder="Digite o telefone do autorizador" id="telefoneAutorizador" required/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="observacaoProposta">Observação da Autorização:</label>
                                <input type="text" name="observacaoProposta" class="form-control" placeholder="Digite a observação" maxlength="256" id="observacaoProposta"/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="formaPagamento" class="control-label">Forma de Pagamento:</label>
                                <select class="form-control" name="formaPagamento" id="formaPagamento" type="text" style="width: 100%;" required>
                                    <option value="BOLETO" selected>Boleto</option>
                                </select>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="recorrencia" class="control-label">Recorrência:</label>
                                <select class="form-control" name="recorrencia" id="recorrencia" type="text" style="width: 100%;" required>
                                    <option value="0">Não Recorrente</option>
                                    <option value="1" selected>Recorrente</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="permitePOC" class="control-label">Permite POC:</label>
                                <select class="form-control" name="permitePOC" id="permitePOC" type="text" style="width: 100%;" required>
                                    <option value="0">Não</option>
                                    <option value="1" selected>Sim</option>
                                </select>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="dataVencimento" class="control-label">Data de Vencimento da Proposta:</label>
                                <input type="date" name="dataVencimento" class="form-control" placeholder="Digite a data de vencimento" id="dataVencimento" min="<?php echo date('Y-m-d'); ?>" required />
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="diaVencimento" class="control-label">Dia de Vencimento do Boleto:</label> 
                                <input type="number" name="diaVencimento" class="form-control" placeholder="Digite o dia de vencimento do boleto" id="diaVencimento" min="1" max="31" onkeyup="mascararNumero(this)" required />
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="enderecoPagamento" class="control-label">Endereço Pagamento:</label>
                                <select class="form-control" name="enderecoPagamento" id="enderecoPagamento" type="text" style="width: 100%;" required>
                                    <option value="0" selected>Não tem endereço pagamento</option>
                                    <option value="1">Tem endereço pagamento</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="enderecoFatura" class="control-label">Endereço Fatura:</label>
                                <select class="form-control" name="enderecoFatura" id="enderecoFatura" type="text" style="width: 100%;" required>
                                    <option value="0" selected>Não tem endereço fatura</option>
                                    <option value="1">Tem endereço fatura</option>
                                </select>
                            </div>
                        </div>
                        <div id="divEnderecoFatura" hidden>
                            <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Endereço Fatura</h4>
                            <input type="text" name="idEnderecoFatura" id="idEnderecoFatura" value='0' hidden/>
                            <div class="col-md-2 input-container form-group">
                                <label for="cepFatura" class="control-label">CEP:</label>
                                <input type="text" name="cepFatura" class="form-control" placeholder="CEP" id="cepFatura"/>
                            </div>
                            <div class="col-md-8 input-container form-group">
                                <label for="ruaFatura" class="control-label">Rua:</label>
                                <input type="text" name="ruaFatura" class="form-control" placeholder="Rua" id="ruaFatura"/>
                            </div>
                            <div class="col-md-2 input-container form-group">
                                <label for="numeroFatura" class="control-label">Número:</label>
                                <input type="text" name="numeroFatura" class="form-control" placeholder="Número" id="numeroFatura"/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="bairroFatura" class="control-label">Bairro:</label>
                                <input type="text" name="bairroFatura" class="form-control" placeholder="Bairro" id="bairroFatura"/>
                            </div>
                            <div class="col-md-4 input-container form-group">
                                <label for="cidadeFatura" class="control-label">Cidade:</label>
                                <input type="text" name="cidadeFatura" class="form-control" placeholder="Cidade" id="cidadeFatura"/>
                            </div>
                            <div class="col-md-2 input-container form-group">
                                <label for="estadoFatura" class="control-label">UF:</label>
                                <select class="form-control" name="ufFatura" id="estadoFatura" type="text" style="width: 100%;">
                                    <option value="" disabled selected>UF</option>
                                    <option value="AC">AC</option>
									<option value="AL">AL</option>
									<option value="AP">AP</option>
									<option value="AM">AM</option>
									<option value="BA">BA</option>
									<option value="CE">CE</option>
									<option value="DF">DF</option>
									<option value="ES">ES</option>
									<option value="GO">GO</option>
									<option value="MA">MA</option>
									<option value="MT">MT</option>
									<option value="MS">MS</option>
									<option value="MG">MG</option>
									<option value="PA">PA</option>
									<option value="PB">PB</option>
									<option value="PR">PR</option>
									<option value="PE">PE</option>
									<option value="PI">PI</option>
									<option value="RJ">RJ</option>
									<option value="RN">RN</option>
									<option value="RS">RS</option>
									<option value="RO">RO</option>
									<option value="RR">RR</option>
									<option value="SC">SC</option>
									<option value="SP">SP</option>
									<option value="SE">SE</option>
									<option value="TO">TO</option>
                                </select>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="complementoFatura">Complemento:</label>
                                <input type="text" name="complementoFatura" class="form-control" placeholder="Digite o complemento" id="complementoFatura"/>
                            </div>
                        </div>
                        <div id="divEnderecoPagamento" hidden>
                            <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Endereço Pagamento</h4>
                            <input type="text" name="idEnderecoPagamento" id="idEnderecoPagamento" value='0' hidden/>
                            <div class="col-md-2 input-container form-group">
                                <label for="cepPagamento" class="control-label">CEP:</label>
                                <input type="text" name="cepPagamento" class="form-control" placeholder="CEP" id="cepPagamento"/>
                            </div>
                            <div class="col-md-8 input-container form-group">
                                <label for="ruaPagamento" class="control-label">Rua:</label>
                                <input type="text" name="ruaPagamento" class="form-control" placeholder="Rua" id="ruaPagamento"/>
                            </div>
                            <div class="col-md-2 input-container form-group">
                                <label for="numeroPagamento" class="control-label">Número:</label>
                                <input type="text" name="numeroPagamento" class="form-control" placeholder="Número" id="numeroPagamento"/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="bairroPagamento" class="control-label">Bairro:</label>
                                <input type="text" name="bairroPagamento" class="form-control" placeholder="Bairro" id="bairroPagamento"/>
                            </div>
                            <div class="col-md-4 input-container form-group">
                                <label for="cidadePagamento" class="control-label">Cidade:</label>
                                <input type="text" name="cidadePagamento" class="form-control" placeholder="Cidade" id="cidadePagamento"/>
                            </div>
                            <div class="col-md-2 input-container form-group">
                                <label for="estadoPagamento" class="control-label">UF:</label>
                                <select class="form-control" name="ufPagamento" id="estadoPagamento" type="text" style="width: 100%;">
                                    <option value="" disabled selected>UF</option>
                                    <option value="AC">AC</option>
									<option value="AL">AL</option>
									<option value="AP">AP</option>
									<option value="AM">AM</option>
									<option value="BA">BA</option>
									<option value="CE">CE</option>
									<option value="DF">DF</option>
									<option value="ES">ES</option>
									<option value="GO">GO</option>
									<option value="MA">MA</option>
									<option value="MT">MT</option>
									<option value="MS">MS</option>
									<option value="MG">MG</option>
									<option value="PA">PA</option>
									<option value="PB">PB</option>
									<option value="PR">PR</option>
									<option value="PE">PE</option>
									<option value="PI">PI</option>
									<option value="RJ">RJ</option>
									<option value="RN">RN</option>
									<option value="RS">RS</option>
									<option value="RO">RO</option>
									<option value="RR">RR</option>
									<option value="SC">SC</option>
									<option value="SP">SP</option>
									<option value="SE">SE</option>
									<option value="TO">TO</option>
                                </select>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="complementoPagamento">Complemento:</label>
                                <input type="text" name="complementoPagamento" class="form-control" placeholder="Complemento" id="complementoPagamento"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="footerDadosProposta">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Fechar">Fechar</button>
                    <button type="submit" class="btn btn-success" data-id="" data-idOportunidade="" data-idVendedor="" data-nomeVendedor="" data-idCliente="" data-nomeCliente="" id='btnSalvarProposta' title="Salvar">Salvar e continuar</button>
                </div>
                </form>
            </div>
            <div class="modal-body" id="bodyDadosItensProposta" hidden>
                <div class="col-md-12">
                    <div id="divItensProposta">
                        <form id='formItensProposta'>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="produtoItemProposta" class="control-label">Produto:</label>
                                <select class="form-control" name="idProduto" id="produtoItemProposta" type="text" style="width: 100%;" required>
                                    <option value="" disabled selected>Selecione o produto</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="quantidadeItemProposta" class="control-label">Quantidade:</label>
                                <input type="number" name="quantidade" class="form-control" placeholder="Digite a quantidade" id="quantidadeItemProposta" min="1" required/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="valorUnitarioItemProposta">Valor Unitário:</label>
                                <input type="text" name="valorUnitario" class="form-control" id="valorUnitarioItemProposta" value='R$ 0,00' readonly/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="percentualDescontoItemProposta">Percentual Desconto:</label>
                                <input type="text" name="percentualDesconto" class="form-control" placeholder="Digite o percentual de desconto"  data-toggle="tooltip" data-placement="top" title="O valor máximo é 100%" id="percentualDescontoItemProposta"/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="valorTotalItemProposta">Valor Total:</label>
                                <input type="text" name="valorTotal" class="form-control" id="valorTotalItemProposta" value='R$ 0,00' readonly/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="valorDescontoItemProposta">Valor Desconto:</label>
                                <input type="text" name="valorDesconto" class="form-control" id="valorDescontoItemProposta" value='R$ 0,00' readonly/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-12 input-container form-group">
                                <label for="observacaoItemProspota">Observação:</label>
                                <input type="text" name="observacao" class="form-control" placeholder="Digite a observação" id="observacaoItemProspota"/>
                            </div>
                        </div>
                        <div class='row' style="margin-bottom: 5px;">
                            <div class="col-md-12 form-group" style="margin-left: auto;">
                                <button type="submit" class="btn btn-success" data-id="" id='btnAdicionarItemTabela' style="float: right" title="Adicionar">Adicionar à tabela</button>
                                <button class="btn" id="limparTabelaItensCadProposta" style="background-color: red;color: white;float: left">Limpar Tabela</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrapperItensPropostas">
                    <div id="tableItensPropostas" class="ag-theme-alpine my-grid">
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="footerItensProposta" hidden>
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Fechar">Fechar</button>
                    <button type="button" class="btn btn-success" data-idProposta="" id='btnSalvarItensProposta' title="Salvar">Salvar itens</button>
                </div>
                </form>
            </div>
            <div class="modal-body" id="bodyDadosItensPropostaEdit" hidden>
                <button type="button" class="btn btn-primary" data-idProposta="" id='btnAdicionarNovoItemEdit' style="margin-bottom: 5px;" title="Adicionar"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar item(s)</button>
                <div class="wrapperItensPropostasEdit">
                    <div id="tableItensPropostasEdit" class="ag-theme-alpine my-grid">
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="footerItensPropostaEdit" hidden>
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Fechar">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalItemProposta" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalItemProposta">Adicionar Itens</h3>
            </div>
            <div class="modal-body" id="bodyDadosItensPropostaEdit">
                <div class="col-md-12">
                    <div id="divItensPropostaEdit">
                        <form id='formItensPropostaEdit'>
                        <input type="text" hidden name="id" id="idItem" value='0' />
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="produtoItemPropostaEdit" class="control-label">Produto:</label>
                                <select class="form-control" name="idProduto" id="produtoItemPropostaEdit" type="text" style="width: 100%;" required>
                                    <option value="" disabled selected>Selecione o produto</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="quantidadeItemPropostaEdit" class="control-label">Quantidade:</label>
                                <input type="number" name="quantidade" class="form-control" placeholder="Digite a quantidade" id="quantidadeItemPropostaEdit" min="1" required/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="valorUnitarioItemPropostaEdit">Valor Unitário:</label>
                                <input type="text" name="valorUnitario" class="form-control" id="valorUnitarioItemPropostaEdit" value='R$ 0,00' readonly/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="percentualDescontoItemPropostaEdit">Percentual Desconto:</label>
                                <input type="text" name="percentualDesconto" class="form-control" placeholder="Digite o percentual de desconto"  data-toggle="tooltip" data-placement="top" title="O valor máximo é 100%" id="percentualDescontoItemPropostaEdit"/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="valorTotalItemPropostaEdit" class="control-label">Valor Total:</label>
                                <input type="text" name="valorTotal" class="form-control" id="valorTotalItemPropostaEdit" value='R$ 0,00' readonly/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="valorDescontoItemPropostaEdit" class="control-label">Valor Desconto:</label>
                                <input type="text" name="valorDesconto" class="form-control" id="valorDescontoItemPropostaEdit" value='R$ 0,00' readonly/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-12 input-container form-group">
                                <label for="observacaoItemProspotaEdit">Observação:</label>
                                <input type="text" name="observacao" class="form-control" placeholder="Digite a observação" id="observacaoItemProspotaEdit"/>
                            </div>
                        </div>
                        <div class='row' style="margin-bottom: 5px;">
                            <div class="col-md-12 form-group" style="margin-left: auto;">
                                <button type="submit" class="btn btn-success" data-id="" id='btnAdicionarItemTabelaEdit' style="float: right; display: none" title="Adicionar">Adicionar à tabela</button>
                                <button class="btn" id="limparTabelaItensEditProposta" style="background-color: red;color: white;float: left">Limpar Tabela</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrapperItensPropostasAddEdit" id="divTabelaItensPropostaEdit" hidden>
                    <div id="tableItensPropostasAddEdit" class="ag-theme-alpine my-grid">
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="footerItensPropostaAddEdit">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Fechar">Fechar</button>
                    <button type="submit" class="btn btn-success" data-idProposta="" id='btnSalvarItensPropostaEdit' title="Salvar">Salvar</button>
                    <button type="button" class="btn btn-success" data-idProposta="" id='btnSalvarItensPropostaEditTabela' title="Salvar" hidden>Salvar Itens</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="modalCliente" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id='formCliente'>
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleModalCliente">Adicionar Cliente</h3>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Dados</h4>
                        <input type="text" hidden name="id" id="idClienteCad" value='0' />
                        <input type="text" hidden name="status" id="statusClienteCad" value='0' />
                        <div class='row'>
                            <div class='col-md-6 input-container form-group'>
                                <input type="radio" name="tipoCliente" id="tipoClienteFisica" value="PF" checked> Pessoa Física</input>
                                <input type="radio" name="tipoCliente" id="tipoClienteJuridica" value="PJ"> Pessoa Jurídica</input>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="nomeCliente" class="control-label">Nome:</label>
                                <input type="text" name="nomeCliente" class="form-control" placeholder="Digite o nome" id="nomeCliente" maxlength="250" required/>
                            </div>
                            <div class="col-md-6 input-container form-group divCpfliente">
                                <label for="cpfCliente" class="control-label">CPF:</label>
                                <input type="text" name="cpfCliente" class="form-control" placeholder="Digite o CPF" id="cpfCliente" required/>
                            </div>
                            <div class="col-md-6 input-container form-group divRazaoSocialCliente" hidden>
                                <label for="razaoSocialCliente" class="control-label">Razão Social:</label>
                                <input type="text" name="razaoSocialCliente" class="form-control" placeholder="Digite a razão social" id="razaoSocialCliente"/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group divIdentidadeCliente">
                                <div class='row'>
                                    <div class="col-md-5 input-container form-group divIdentidade">
                                        <label for="identidadeCliente">RG:</label>
                                        <input type="text" name="identidadeCliente" class="form-control" placeholder="Digite o RG" maxlength="9" id="identidadeCliente" onkeyup="mascararNumero(this)"/>
                                    </div>
                                    <div class="col-md-4 input-container form-group divIdentidade">
                                        <label for="orgaoExpedidor">Órgão Exp.:</label>
                                        <input type="text" name="orgaoExpedidor" class="form-control" placeholder="Órgão Exp." maxlength="9" id="orgaoExpedidor" oninput="this.value = this.value.toUpperCase().replace(/[^A-Z]/g, '')"/>
                                    </div>
                                    <div class="col-md-3 input-container form-group divIdentidade">
                                        <label for="ufOrgaoExpedidor">UF Órgão:</label>
                                        <select class="form-control" name="ufOrgaoExpedidor" id="ufOrgaoExpedidor" type="text" style="width: 100%;">
                                            <option value="" selected>UF</option>
                                            <option value="AC">AC</option>
                                            <option value="AL">AL</option>
                                            <option value="AP">AP</option>
                                            <option value="AM">AM</option>
                                            <option value="BA">BA</option>
                                            <option value="CE">CE</option>
                                            <option value="DF">DF</option>
                                            <option value="ES">ES</option>
                                            <option value="GO">GO</option>
                                            <option value="MA">MA</option>
                                            <option value="MT">MT</option>
                                            <option value="MS">MS</option>
                                            <option value="MG">MG</option>
                                            <option value="PA">PA</option>
                                            <option value="PB">PB</option>
                                            <option value="PR">PR</option>
                                            <option value="PE">PE</option>
                                            <option value="PI">PI</option>
                                            <option value="RJ">RJ</option>
                                            <option value="RN">RN</option>
                                            <option value="RS">RS</option>
                                            <option value="RO">RO</option>
                                            <option value="RR">RR</option>
                                            <option value="SC">SC</option>
                                            <option value="SP">SP</option>
                                            <option value="SE">SE</option>
                                            <option value="TO">TO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 input-container form-group divDataNascimentoCliente">
                                <label for="dataNascimentoCliente" class="control-label">Data de Nascimento:</label>
                                <input type="date" name="dataNascimentoCliente" class="form-control" id="dataNascimentoCliente" min="1900-01-01" required/>
                            </div>
                            <div class="col-md-6 input-container form-group divCnpjCliente" hidden>
                                <label for="cnpjCliente" class="control-label">CNPJ:</label>
                                <input type="text" name="cnpjCliente" class="form-control" placeholder="Digite o CNPJ" id="cnpjCliente"/>
                            </div>
                            <div class="col-md-6 input-container form-group divInscricaoEstadualCliente" hidden>
                                <label for="inscricaoEstadualCliente">Inscrição Estadual:</label>
                                <input type="text" name="inscricaoEstadualCliente" class="form-control" placeholder="Digite a inscrição estadual" id="inscricaoEstadualCliente" maxlength="12" onkeyup="mascararNumero(this)"/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="empresaCliente" class="control-label">Empresa:</label>
                                <input type="text" name="empresaCliente" class="form-control" id="empresaCliente" value="OMNILINK" required readonly/>
                            </div>
                        </div>
                        <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Endereço</h4>
                        <div class='row'>
                            <div class="col-md-2 input-container form-group">
                                <label for="cepCliente" class="control-label">CEP:</label>
                                <input type="text" name="cepCliente" class="form-control" placeholder="CEP" id="cepCliente" required/>
                            </div>
                            <div class="col-md-8 input-container form-group">
                                <label for="ruaCliente" class="control-label">Rua:</label>
                                <input type="text" name="ruaCliente" class="form-control" placeholder="Rua" id="ruaCliente" maxlength="60" required/>
                            </div>
                            <div class="col-md-2 input-container form-group">
                                <label for="numeroCliente" class="control-label">Número:</label>
                                <input type="text" name="numeroCliente" class="form-control" placeholder="Número" id="numeroCliente" maxlength="10" required/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="bairroCliente" class="control-label">Bairro:</label>
                                <input type="text" name="bairroCliente" class="form-control" placeholder="Bairro" id="bairroCliente" maxlength="35" required/>
                            </div>
                            <div class="col-md-4 input-container form-group">
                                <label for="cidadeCliente" class="control-label">Cidade:</label>
                                <input type="text" name="cidadeCliente" class="form-control" placeholder="Cidade" id="cidadeCliente" oninput="validarLetras(this)" maxlength="30" required/>
                            </div>
                            <div class="col-md-2 input-container form-group">
                                <label for="estadoCliente" class="control-label">UF:</label>
                                <select class="form-control" name="ufCliente" id="estadoCliente" type="text" style="width: 100%;" required>
                                    <option value="" disabled selected>UF</option>
                                    <option value="AC">AC</option>
									<option value="AL">AL</option>
									<option value="AP">AP</option>
									<option value="AM">AM</option>
									<option value="BA">BA</option>
									<option value="CE">CE</option>
									<option value="DF">DF</option>
									<option value="ES">ES</option>
									<option value="GO">GO</option>
									<option value="MA">MA</option>
									<option value="MT">MT</option>
									<option value="MS">MS</option>
									<option value="MG">MG</option>
									<option value="PA">PA</option>
									<option value="PB">PB</option>
									<option value="PR">PR</option>
									<option value="PE">PE</option>
									<option value="PI">PI</option>
									<option value="RJ">RJ</option>
									<option value="RN">RN</option>
									<option value="RS">RS</option>
									<option value="RO">RO</option>
									<option value="RR">RR</option>
									<option value="SC">SC</option>
									<option value="SP">SP</option>
									<option value="SE">SE</option>
									<option value="TO">TO</option>
                                </select>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="complementoCliente">Complemento:</label>
                                <input type="text" name="complementoCliente" class="form-control" placeholder="Complemento" id="complementoCliente" maxlength="120"/>
                            </div>
                        </div>
                        <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Contato</h4>
                        <div class='row'>
                            <div class="col-md-4 input-container form-group">
                                <label for="telefoneCliente" class="control-label">Telefone:</label>
                                <input type="text" name="telefoneCliente" class="form-control" placeholder="Digite o telefone" id="telefoneCliente" required/>
                            </div>
                            <div class="col-md-4 input-container form-group">
                                <label for="setorClienteTelefone" class="control-label">Setor:</label>
                                <select class="form-control" name="setorClienteTelefone" id="setorClienteTelefone" type="text" style="width: 100%;" required>
                                    <option value="" disabled selected>Selecione o setor</option>
                                    <option value="0">Financeiro</option>
									<option value="1">Diretoria</option>
									<option value="2">Suporte</option>
									<option value="3">Pessoal</option>
                                </select>
                            </div>
                            <div class="col-md-4 input-container form-group">
                                <label for="obsClienteTelefone">Observação:</label>
                                <input type="text" name="obsClienteTelefone" class="form-control" placeholder="Digite a observação" id="obsClienteTelefone" maxlength="255"/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-4 input-container form-group">
                                <label for="emailCliente" class="control-label">E-mail:</label>
                                <input type="email" name="emailCliente" class="form-control" placeholder="Digite o e-mail" id="emailCliente" maxlength="255" required/>
                            </div>
                            <div class="col-md-4 input-container form-group">
                                <label for="setorClienteEmail" class="control-label">Setor:</label>
                                <select class="form-control" name="setorClienteEmail" id="setorClienteEmail" type="text" style="width: 100%;" required>
                                    <option value="" disabled selected>Selecione o setor</option>
                                    <option value="0">Financeiro</option>
									<option value="1">Diretoria</option>
									<option value="2">Suporte</option>
									<option value="3">Pessoal</option>
                                </select>
                            </div>
                            <div class="col-md-4 input-container form-group">
                                <label for="obsClienteEmail">Observação:</label>
                                <input type="text" name="obsClienteEmail" class="form-control" placeholder="Digite a observação" id="obsClienteEmail" maxlength="255"/>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success" data-id="" id='btnSalvarCliente'>Salvar</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<div id="modalSugestaoProposta" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalSugestaoProposta">Sugestões da Proposta</h3>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="wrappertableSugestaoProposta">
                        <div id="tableSugestaoProposta" class="ag-theme-alpine my-grid">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-top: 10px;">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalInformacoesPropostaKanban" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalInformacoesPropostaKanban">Informações da Proposta</h3>
            </div>
            <ul class="nav nav-tabs" style="margin-bottom: 15px; margin-left: 5px">
                <li class="nav-item">
                    <a id="tab-dadosPropostaInfoModal" href="" data-toggle="tab" class="nav-link active">Dados da Proposta</a>
                </li>
                <li class="nav-item">
                    <a id="tab-itensPropostaInfoModal" href="" data-toggle="tab" class="nav-link">Itens da Proposta</a>
                </li>
            </ul>
            <div class="modal-body">
                <div class="col-md-12">
                    <div id="divDadosDaPropostaModalInfo">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">ID:</label>
                                <span id="spanIdProposta"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">AF:</label>
                                <span id="spanAfProposta" style="word-wrap: break-word;"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Cliente:</label>
                                <span id="spanNomeClienteInfoKanban"></span>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Vendedor:</label>
                                <span id="spanNomeVendedor"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Autorizador:</label>
                                <span id="spanNomeAutorizador"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Documento do Autorizador:</label>
                                <span id="spanDocumentoAutorizador"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">E-mail do Autorizador:</label>
                                <span id="spanEmailAutorizador"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Telefone do Autorizador:</label>
                                <span id="spanTelefoneAutorizador"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Observação da Autorização:</label>
                                <span id="spanObservacaoAutorizacao"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Forma de Pagamento:</label>
                                <span id="spanFormaPagamento"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Recorrência:</label>
                                <span id="spanRecorrencia"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Permite POC:</label>
                                <span id="spanPermitePoc"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Data de Vencimento da Proposta:</label>
                                <span id="spanDataVencimentoProposta"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Dia de Vencimento do Boleto:</label>
                                <span id="spanDiaVencimentoBoleto"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Valor Total:</label>
                                <span id="spanValorTotalProposta"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Status Integração:</label>
                                <span id="spanStatusIntegracao"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Endereço Faturamento:</label>
                                <span id="spanEnderecoFatura"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Endereço Pagamento:</label>
                                <span id="spanEnderecoPagamento"></span>
                            </div>
                        </div>
                        <div id="divInfoDadosEnderecoFatura" class="row" hidden>
                            <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Endereço Fatura</h4>
                            <div class="col-md-2 form-group divCamposEndereco espacoEndereco">
                                <label class="labelInfoKanban">CEP:</label>
                                <span id="spanCepFatura"></span>
                            </div>
                            <div class="col-md-7 form-group divCamposEndereco espacoEndereco">
                                <label class="labelInfoKanban">Rua:</label>
                                <span id="spanRuaFatura"></span>
                            </div>
                            <div class="col-md-2 form-group divCamposEndereco">
                                <label class="labelInfoKanban">Número:</label>
                                <span id="spanNumeroFatura"></span>
                            </div>
                            <div class="col-md-4 form-group divCamposEndereco espacoEndereco">
                                <label class="labelInfoKanban">Bairro:</label>
                                <span id="spanBairroFatura"></span>
                            </div>
                            <div class="col-md-5 form-group divCamposEndereco espacoEndereco">
                                <label class="labelInfoKanban">Cidade:</label>
                                <span id="spanCidadeFatura"></span>
                            </div>
                            <div class="col-md-2 form-group divCamposEndereco">
                                <label class="labelInfoKanban">UF:</label>
                                <span id="spanUfFatura"></span>
                            </div>
                            <div class="col-md-12 form-group divCamposEndereco">
                                <label class="labelInfoKanban">Complemento:</label>
                                <span id="spanComplementoFatura"></span>
                            </div>
                        </div>
                        <div id="divInfoDadosEnderecoPagamento" class="row" hidden>
                            <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Endereço Pagamento</h4>
                            <div class="col-md-2 form-group divCamposEndereco espacoEndereco">
                                <label class="labelInfoKanban">CEP:</label>
                                <span id="spanCepPagamento"></span>
                            </div>
                            <div class="col-md-7 form-group divCamposEndereco espacoEndereco">
                                <label class="labelInfoKanban">Rua:</label>
                                <span id="spanRuaPagamento"></span>
                            </div>
                            <div class="col-md-2 form-group divCamposEndereco">
                                <label class="labelInfoKanban">Número:</label>
                                <span id="spanNumeroPagamento"></span>
                            </div>
                            <div class="col-md-4 form-group divCamposEndereco espacoEndereco">
                                <label class="labelInfoKanban">Bairro:</label>
                                <span id="spanBairroPagamento"></span>
                            </div>
                            <div class="col-md-5 form-group divCamposEndereco espacoEndereco">
                                <label class="labelInfoKanban">Cidade:</label>
                                <span id="spanCidadePagamento"></span>
                            </div>
                            <div class="col-md-2 form-group divCamposEndereco">
                                <label class="labelInfoKanban">UF:</label>
                                <span id="spanUfPagamento"></span>
                            </div>
                            <div class="col-md-12 form-group divCamposEndereco">
                                <label class="labelInfoKanban">Complemento:</label>
                                <span id="spanComplementoPagamento"></span>
                            </div>
                        </div>
                    </div>
                    <div id="divItensDaPropostaModalInfo" hidden>
                        <div class="wrapperItensPropostasInfo">
                            <div id="tableItensPropostasInfo" class="ag-theme-alpine my-grid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalOportunidade" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalOportunidade">Adicionar Oportunidade</h3>
            </div>
            <ul class="nav nav-tabs" style="margin-bottom: 15px; margin-left: 5px">
                <li class="nav-item">
                    <a id="tab-dadosOportunidade" href="" data-toggle="tab" class="nav-link active">Dados da Oportunidade</a>
                </li>
                <li class="nav-item">
                    <a id="tab-itensDaOportunidade" href="" data-toggle="tab" class="nav-link" type="button">Itens da Oportunidade</a>
                </li>
                <li class="nav-item">
                    <a id="tab-itensDaOportunidadeEdit" href="" data-toggle="tab" class="nav-link" type="button">Itens da Oportunidade</a>
                </li>
            </ul>
            <div class="modal-body" id="bodyDadosOportunidade">
                <div class="col-md-12">
                    <div id="divDadosOportunidade">
                        <form id='formOportunidade'>
                        <input type="text" hidden name="id" id="idOportunidade" value='0' />
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="nomeClienteOportunidade">Nome do Cliente:</label>
                                <input type="text" name="nomeCliente" class="form-control" placeholder="Digite o nome do cliente" id="nomeClienteOportunidade"/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="documentoClienteOportunidade" class="control-label">Documento do Cliente:</label>
                                <input type="text" name="documentoCliente" class="form-control" placeholder="Digite o documento do cliente" id="documentoClienteOportunidade" required/>
                            </div>     
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="emailClienteOportunidade">E-mail do Cliente:</label>
                                <input type="email" name="emailCliente" class="form-control" placeholder="Digite o e-mail do cliente" maxlength="120" minlength="3" id="emailClienteOportunidade"/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="idVendedorOportunidade" class="control-label">Vendedor:</label>
                                <select class="form-control" name="idVendedor" id="idVendedorOportunidade" type="text" style="width: 100%;" required>
                                    <option value="" disabled selected>Selecione o vendedor</option>
                                </select>
                            </div>   
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="formaPagamentoOportunidade">Forma de Pagamento:</label>
                                <select class="form-control" name="formaPagamento" id="formaPagamentoOportunidade" type="text" style="width: 100%;">
                                    <option value="BOLETO" selected>Boleto</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="recorrenciaOportunidade">Recorrência:</label>
                                <select class="form-control" name="recorrencia" id="recorrenciaOportunidade" type="text" style="width: 100%;">
                                    <option value="0" selected>Não Recorrente</option>
                                    <option value="1">Recorrente</option>
                                </select>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="permitePOCOportunidade">Permite POC:</label>
                                <select class="form-control" name="permitePOC" id="permitePOCOportunidade" type="text" style="width: 100%;">
                                    <option value="0" selected>Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="dataVencimentoOportunidade">Data de Vencimento da Proposta:</label>
                                <input type="date" name="dataVencimento" class="form-control" placeholder="Digite a data de vencimento" id="dataVencimentoOportunidade" min="<?php echo date('Y-m-d'); ?>"/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="diaVencimentoOportunidade">Dia de Vencimento do Boleto:</label> 
                                <input type="number" name="diaVencimento" class="form-control" placeholder="Digite o dia de vencimento do boleto" id="diaVencimentoOportunidade" min="1" max="31" onkeyup="mascararNumero(this)" />
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="enderecoPagamentoOportunidade">Endereço Pagamento:</label>
                                <select class="form-control" name="enderecoPagamento" id="enderecoPagamentoOportunidade" type="text" style="width: 100%;">
                                    <option value="0" selected>Não tem endereço pagamento</option>
                                    <option value="1">Tem endereço pagamento</option>
                                </select>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="enderecoFaturaOportunidade">Endereço Fatura:</label>
                                <select class="form-control" name="enderecoFatura" id="enderecoFaturaOportunidade" type="text" style="width: 100%;">
                                    <option value="0" selected>Não tem endereço fatura</option>
                                    <option value="1">Tem endereço fatura</option>
                                </select>
                            </div>
                        </div>
                        <div id="divEnderecoFaturaOportunidade" hidden>
                            <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Endereço Fatura</h4>
                            <input type="text" name="idEnderecoFatura" id="idEnderecoFaturaOportunidade" value='0' hidden/>
                            <div class="col-md-2 input-container form-group">
                                <label for="cepFaturaOportunidade" class="control-label">CEP:</label>
                                <input type="text" name="cepFatura" class="form-control" placeholder="CEP" id="cepFaturaOportunidade"/>
                            </div>
                            <div class="col-md-8 input-container form-group">
                                <label for="ruaFaturaOportunidade" class="control-label">Rua:</label>
                                <input type="text" name="ruaFatura" class="form-control" placeholder="Rua" id="ruaFaturaOportunidade"/>
                            </div>
                            <div class="col-md-2 input-container form-group">
                                <label for="numeroFaturaOportunidade" class="control-label">Número:</label>
                                <input type="text" name="numeroFatura" class="form-control" placeholder="Número" id="numeroFaturaOportunidade"/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="bairroFaturaOportunidade" class="control-label">Bairro:</label>
                                <input type="text" name="bairroFatura" class="form-control" placeholder="Bairro" id="bairroFaturaOportunidade"/>
                            </div>
                            <div class="col-md-4 input-container form-group">
                                <label for="cidadeFaturaOportunidade" class="control-label">Cidade:</label>
                                <input type="text" name="cidadeFatura" class="form-control" placeholder="Cidade" id="cidadeFaturaOportunidade"/>
                            </div>
                            <div class="col-md-2 input-container form-group">
                                <label for="estadoFaturaOportunidade" class="control-label">UF:</label>
                                <select class="form-control" name="ufFatura" id="estadoFaturaOportunidade" type="text" style="width: 100%;">
                                    <option value="" disabled selected>UF</option>
                                    <option value="AC">AC</option>
					    			<option value="AL">AL</option>
					    			<option value="AP">AP</option>
					    			<option value="AM">AM</option>
					    			<option value="BA">BA</option>
					    			<option value="CE">CE</option>
					    			<option value="DF">DF</option>
					    			<option value="ES">ES</option>
					    			<option value="GO">GO</option>
					    			<option value="MA">MA</option>
					    			<option value="MT">MT</option>
					    			<option value="MS">MS</option>
					    			<option value="MG">MG</option>
					    			<option value="PA">PA</option>
					    			<option value="PB">PB</option>
					    			<option value="PR">PR</option>
					    			<option value="PE">PE</option>
					    			<option value="PI">PI</option>
					    			<option value="RJ">RJ</option>
					    			<option value="RN">RN</option>
					    			<option value="RS">RS</option>
					    			<option value="RO">RO</option>
					    			<option value="RR">RR</option>
					    			<option value="SC">SC</option>
					    			<option value="SP">SP</option>
					    			<option value="SE">SE</option>
					    			<option value="TO">TO</option>
                                </select>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="complementoFaturaOportunidade">Complemento:</label>
                                <input type="text" name="complementoFatura" class="form-control" placeholder="Digite o complemento" id="complementoFaturaOportunidade"/>
                            </div>
                        </div>
                        <div id="divEnderecoPagamentoOportunidade" hidden>
                            <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Endereço Pagamento</h4>
                            <input type="text" name="idEnderecoPagamento" id="idEnderecoPagamentoOportunidade" value='0' hidden/>
                            <div class="col-md-2 input-container form-group">
                                <label for="cepPagamentoOportunidade" class="control-label">CEP:</label>
                                <input type="text" name="cepPagamento" class="form-control" placeholder="CEP" id="cepPagamentoOportunidade"/>
                            </div>
                            <div class="col-md-8 input-container form-group">
                                <label for="ruaPagamentoOportunidade" class="control-label">Rua:</label>
                                <input type="text" name="ruaPagamento" class="form-control" placeholder="Rua" id="ruaPagamentoOportunidade"/>
                            </div>
                            <div class="col-md-2 input-container form-group">
                                <label for="numeroPagamentoOportunidade" class="control-label">Número:</label>
                                <input type="text" name="numeroPagamento" class="form-control" placeholder="Número" id="numeroPagamentoOportunidade"/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="bairroPagamentoOportunidade" class="control-label">Bairro:</label>
                                <input type="text" name="bairroPagamento" class="form-control" placeholder="Bairro" id="bairroPagamentoOportunidade"/>
                            </div>
                            <div class="col-md-4 input-container form-group">
                                <label for="cidadePagamentoOportunidade" class="control-label">Cidade:</label>
                                <input type="text" name="cidadePagamento" class="form-control" placeholder="Cidade" id="cidadePagamentoOportunidade"/>
                            </div>
                            <div class="col-md-2 input-container form-group">
                                <label for="estadoPagamentoOportunidade" class="control-label">UF:</label>
                                <select class="form-control" name="ufPagamento" id="estadoPagamentoOportunidade" type="text" style="width: 100%;">
                                    <option value="" disabled selected>UF</option>
                                    <option value="AC">AC</option>
					    			<option value="AL">AL</option>
					    			<option value="AP">AP</option>
					    			<option value="AM">AM</option>
					    			<option value="BA">BA</option>
					    			<option value="CE">CE</option>
					    			<option value="DF">DF</option>
					    			<option value="ES">ES</option>
					    			<option value="GO">GO</option>
					    			<option value="MA">MA</option>
					    			<option value="MT">MT</option>
					    			<option value="MS">MS</option>
					    			<option value="MG">MG</option>
					    			<option value="PA">PA</option>
					    			<option value="PB">PB</option>
					    			<option value="PR">PR</option>
					    			<option value="PE">PE</option>
					    			<option value="PI">PI</option>
					    			<option value="RJ">RJ</option>
					    			<option value="RN">RN</option>
					    			<option value="RS">RS</option>
					    			<option value="RO">RO</option>
					    			<option value="RR">RR</option>
					    			<option value="SC">SC</option>
					    			<option value="SP">SP</option>
					    			<option value="SE">SE</option>
					    			<option value="TO">TO</option>
                                </select>
                            </div>
                            <div class="col-md-12 input-container form-group">
                                <label for="complementoPagamentoOportunidade">Complemento:</label>
                                <input type="text" name="complementoPagamento" class="form-control" placeholder="Complemento" id="complementoPagamentoOportunidade"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="footerDadosOportunidade">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Fechar">Fechar</button>
                    <button type="submit" class="btn btn-success" data-id="" id='btnSalvarOportunidade' title="Salvar">Salvar</button>
                </div>
                </form>
            </div>
            <div class="modal-body" id="bodyDadosItensOportunidade" hidden>
                <div class="col-md-12">
                    <div id="divItensOportunidade">
                        <form id='formItensOportunidade'>
                            <div class='row'>
                                <div class="col-md-6 input-container form-group">
                                    <label for="produtoItemOportunidade" class="control-label">Produto:</label>
                                    <select class="form-control" name="idProduto" id="produtoItemOportunidade" type="text" style="width: 100%;">
                                        <option value="" disabled selected>Selecione o produto</option>
                                    </select>
                                </div>
                                <div class="col-md-6 input-container form-group">
                                    <label for="quantidadeItemOportunidade" class="control-label">Quantidade:</label>
                                    <input type="number" name="quantidade" class="form-control" placeholder="Digite a quantidade" id="quantidadeItemOportunidade" min="1"/>
                                </div>
                            </div>
                            <div class='row'>
                                <div class="col-md-6 input-container form-group">
                                    <label for="valorUnitarioItemOportunidade">Valor Unitário:</label>
                                    <input type="text" name="valorUnitario" class="form-control" id="valorUnitarioItemOportunidade" value='R$ 0,00' readonly/>
                                </div>
                                <div class="col-md-6 input-container form-group">
                                    <label for="percentualDescontoItemOportunidade">Percentual Desconto:</label>
                                    <input type="text" name="percentualDesconto" class="form-control" placeholder="Digite o percentual de desconto"  data-toggle="tooltip" data-placement="top" title="O valor máximo é 100%" id="percentualDescontoItemOportunidade"/>
                                </div>
                            </div>
                            <div class='row'>
                                <div class="col-md-6 input-container form-group">
                                    <label for="valorTotalItemOportunidade">Valor Total:</label>
                                    <input type="text" name="valorTotal" class="form-control" id="valorTotalItemOportunidade" value='R$ 0,00' readonly/>
                                </div>
                                <div class="col-md-6 input-container form-group">
                                    <label for="valorDescontoItemOportunidade">Valor Desconto:</label>
                                    <input type="text" name="valorDesconto" class="form-control" id="valorDescontoItemOportunidade" value='R$ 0,00' readonly/>
                                </div>
                            </div>
                            <div class='row'>
                                <div class="col-md-12 input-container form-group">
                                    <label for="observacaoItemOportunidade">Observação:</label>
                                    <input type="text" name="observacao" class="form-control" placeholder="Digite a observação" id="observacaoItemOportunidade"/>
                                </div>
                            </div>
                            <div class='row' style="margin-bottom: 5px;">
                                <div class="col-md-12 form-group" style="margin-left: auto;">
                                    <button type="submit" class="btn btn-success" data-id="" id='btnAdicionarItemTabelaOportunidade' style="float: right" title="Adicionar">Adicionar à tabela</button>
                                    <button class="btn" id="limparTabelaItensCadOportunidade" style="background-color: red;color: white;float: left">Limpar Tabela</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="wrapperItensOportunidade">
                    <div id="tableItensOportunidade" class="ag-theme-alpine my-grid">
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="footerDadosItensOportunidade" hidden>
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Fechar">Fechar</button>
                    <button type="button" class="btn btn-success" data-id="" id='btnSalvarOportunidadeItens' title="Salvar">Salvar</button>
                </div>
                </form>
            </div>
            <div class="modal-body" id="bodyDadosItensOportunidadeEdit" hidden>
                <button type="button" class="btn btn-primary" data-idProposta="" id='btnAdicionarNovoItemOportunidadeEdit' style="margin-bottom: 5px;" title="Adicionar"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar item(s)</button>
                <div class="wrapperItensOportunidadeEdit">
                    <div id="tableItensOportunidadeEdit" class="ag-theme-alpine my-grid">
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="footerItensOportunidadeEdit" hidden>
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Fechar">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modalItemOportunidade" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalItemOportunidade">Adicionar Itens</h3>
            </div>
            <div class="modal-body" id="bodyDadosItensOportunidadeEdit">
                <div class="col-md-12">
                    <div id="divItensOportunidadeEdit">
                        <form id='formItensOportunidadeEdit'>
                        <input type="text" hidden name="id" id="idItemOportunidade" value='0' />
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="produtoItemOportunidadeEdit" class="control-label">Produto:</label>
                                <select class="form-control" name="idProduto" id="produtoItemOportunidadeEdit" type="text" style="width: 100%;" required>
                                    <option value="" disabled selected>Selecione o produto</option>
                                </select>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="quantidadeItemOportunidadeEdit" class="control-label">Quantidade:</label>
                                <input type="number" name="quantidade" class="form-control" placeholder="Digite a quantidade" id="quantidadeItemOportunidadeEdit" min="1" required/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="valorUnitarioItemOportunidadeEdit">Valor Unitário:</label>
                                <input type="text" name="valorUnitario" class="form-control" id="valorUnitarioItemOportunidadeEdit" value='R$ 0,00' readonly/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="percentualDescontoItemOportunidadeEdit">Percentual Desconto:</label>
                                <input type="text" name="percentualDesconto" class="form-control" placeholder="Digite o percentual de desconto"  data-toggle="tooltip" data-placement="top" title="O valor máximo é 100%" id="percentualDescontoItemOportunidadeEdit"/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-6 input-container form-group">
                                <label for="valorTotalItemOportunidadeEdit" class="control-label">Valor Total:</label>
                                <input type="text" name="valorTotal" class="form-control" id="valorTotalItemOportunidadeEdit" value='R$ 0,00' readonly/>
                            </div>
                            <div class="col-md-6 input-container form-group">
                                <label for="valorDescontoItemOportunidadeEdit" class="control-label">Valor Desconto:</label>
                                <input type="text" name="valorDesconto" class="form-control" id="valorDescontoItemOportunidadeEdit" value='R$ 0,00' readonly/>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-12 input-container form-group">
                                <label for="observacaoItemOportunidadeEdit">Observação:</label>
                                <input type="text" name="observacao" class="form-control" placeholder="Digite a observação" id="observacaoItemOportunidadeEdit"/>
                            </div>
                        </div>
                        <div class='row' style="margin-bottom: 5px;">
                            <div class="col-md-12 form-group" style="margin-left: auto;">
                                <button type="submit" class="btn btn-success" data-id="" id='btnAdicionarItemTabelaEditOportunidade' style="float: right; display: none" title="Adicionar">Adicionar à tabela</button>
                                <button class="btn" id="limparTabelaItensEditOportunidade" style="background-color: red;color: white;float: left">Limpar Tabela</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrapperItensOportunidadeAddEdit" id="divTabelaItensOportunidadeEdit" hidden>
                    <div id="tableItensOportunidadeAddEdit" class="ag-theme-alpine my-grid">
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="footerItensOportunidadeAddEdit">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Fechar">Fechar</button>
                    <button type="submit" class="btn btn-success" data-idOportunidade="" id='btnSalvarItensOportunidadeEdit' title="Salvar">Salvar</button>
                    <button type="button" class="btn btn-success" data-idOportunidade="" id='btnSalvarItensOportunidadeEditTabela' title="Salvar" hidden>Salvar Itens</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalInformacoesAutorizacaoKanban" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="titleModalInformacoesAutorizacaoKanban">Informações da Autorização</h3>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div id="divDadosDaAutorizacaoModalInfo">
                        <h4 class="subtitle" style="padding-left: 0; padding-right: 0;">Dados Gerais</h4>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">ID:</label>
                                <span id="spanIdAutorizacao"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">AF:</label>
                                <span id="spanAfAutorizacao" style="word-wrap: break-word;"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Cliente:</label>
                                <span id="spanNomeClienteAutorizacao"></span>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">CNPJ do Cliente:</label>
                                <span id="spanCnpjClienteAutorizacao"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Vendedor:</label>
                                <span id="spanNomeVendedorAutorizacao"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Valor Total:</label>
                                <span id="spanValorTotalAutorizacao"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">ID da Proposta:</label>
                                <span id="spanIdPropostaAutorizacao"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Status da Proposta:</label>
                                <span id="spanStatusIntegracaoAutorizacao"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Autorizador:</label>
                                <span id="spanNomeAutorizadorAutorizacao"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Documento do Autorizador:</label>
                                <span id="spanDocumentoAutorizadorAutorizacao"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">E-mail do Autorizador:</label>
                                <span id="spanEmailAutorizadorAutorizacao"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Telefone do Autorizador:</label>
                                <span id="spanTelefoneAutorizadorAutorizacao"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Status:</label>
                                <span id="spanStatusAutorizacao"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="labelInfoKanban">Data Autorização:</label>
                                <span id="spanDataAutorizacao"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label class="labelInfoKanban">Observação da Autorização:</label>
                                <span id="spanObservacaoAutorizacao1"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= versionFile('assets/js/vendasDeSoftware', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/vendasDeSoftware', 'style.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/vendasDeSoftware', 'vendasDeSoftware.js') ?>"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.14.0/dist/sweetalert2.all.min.js"></script>

<script>
    var RouterController = '<?= site_url('VendasDeSoftware/VendaSoftware') ?>';
    var BaseURL = '<?= base_url('') ?>';
    var permissao = '<?= in_array('vis_vendas_de_software_propostas_adicionar', $permissoes) ?>';
    var permissaoProduto = '<?= in_array('vis_vendas_de_software_produtos', $permissoes) ?>';
</script>