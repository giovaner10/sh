<style>
html {
        scroll-behavior:smooth
    }

    body {
        background-color: #fff !important;
    }
    
    table {
        width: 100% !important;
    }
    .blem{
        color: red;
    }

    .container-fluid {
        padding: 0;
    }

    .dataTables_wrapper .dataTables_processing {
        background: none;
    }
    .bord{
        border-left: 3px solid #03A9F4;
    }

    .dataTables_wrapper .dataTables_processing div {
        display: inline-block;
        vertical-align: center;
        font-size: 68px;
        height: 100%;
        top: 0;
    }

    th, td.wordWrap {
        max-width: 150px;
        word-wrap: break-word;
        text-align: center;
    }

    .checkbox label {
        font-weight: 700;
    }
    .select-container .select-selection--single{
        height: 35px !important;
    }

    .my-1 {
        margin-top: 1em !important;
        margin-bottom: 1em !important;
    }

    .mx-1 {
        margin-left: 1em;
        margin-right: 1em;
    }

    .pt-1 {
        padding-top: 1em;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }
    
    .justify-content-end {
        justify-content: flex-end;
    }
    .align-center {
        align-items: center;
    }

    .modal-xl {
        max-width: 1300px;
        width: 100%;
    }

    .border-0 {
        border: none !important;
    }
    .markerLabel {
        background-color: #fff;
        border-radius: 4px;
        padding: 4px;
    }

    .action-bar * {
        margin-left: 5px;
    }
    .select-selection--multiple .select-search__field{
        width:100%!important;
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

    .checkboxLabel{
        font-weight: 400 !important;
    }
    .btnNovoIndice{
        display: block;
    }
    .btnNovaMeta{
        display: block;
    }

</style>
<h3><?=lang('configuracao_calculo_comissao')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('departamentos')?> >
    <a href="<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais')?>"><?=lang('comercial_e_televendas')?></a> > 
    <?=lang('comissionamento_vendas')?>
</div>

<hr>

<div class="container-fluid my-1">
	<div class="col-sm-12">
        <div id="confCalculoComissao" class="tab-pane fade in active" style="margin-top: 20px">
            <div class="container-fluid" id="tabelaGeral">	    
                <a id="abrirModalInserir" class="btn btn-primary">Nova Configuração</a>	    	
                <table class="table table-bordered table-striped table-hover responsive nowrap" id="tabelaconfCalculoComissao">
                    <thead>
                        <tr class="tableheader">
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Empresa</th>
                        <th>Regional</th>
                        <th>Cargo</th>
                        <th>Tem Meta</th>
                        <th>Tem índice</th>
                        <th>Considera Produto</th>
                        <th>Considera Licença</th>
                        <th>Data de Cadastro</th>
                        <th>Data de Atualização</th>
                        <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>    
            </div>
        </div>
	</div>
</div>

<!-- Modal Cadastro/Edição de Configuração de Cáculo de Comissão -->
<div id="modalCadConfigCalculoComissao" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadConfigCalculoComissao" id="formCadastroConfigCalculoComissao">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="nomeHeaderModal"></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs" style="margin-bottom: 10px;">
                            <li class="nav-item">
                                <a id = "tab-dadosGerais" href="" data-toggle="tab" class="nav-link active">Dados Gerais</a>
                            </li>
                            <li class="nav-item">
                                <a id = "tab-itensDaConfig" href="" data-toggle="tab" class="nav-link">Itens da Configuração de Cálculo</a>
                            </li>
                            <li class="nav-item">
                                <a id = "tab-meta" href="" data-toggle="tab" class="nav-link">Meta</a>
                            </li>
                            <li class="nav-item">
                                <a id = "tab-indice" href="" data-toggle="tab" class="nav-link">Índice</a>
                            </li>
                        </ul>
                        <div id="dadosGerais" style="display: block;">
                            <div id="div_identificacao">
                                <input type="hidden" id="idConfig" name="idConfig" value=""/>
                                <div class="row">
                                    <div class="col-md-6 form-group bord">
                                        <label class="control-label">Nome</label>
                                        <input type="text" class="form-control input-sm" name="nomeConfigCalculoComissao" id="nomeConfigCalculoComissao" placeholder="Digite o nome da configuração" required>
                                    </div>
                                    <div id="divSelectEmpresaCadastro" class="col-md-6 form-group bord">
                                        <label class="control-label">Empresa: </label>
       			                        <select class="form-control input-sm" id="selectEmpresaCadastro" name="selectEmpresaCadastro" type="text" required></select>
                                    </div>
                                    <div id="divSelectEmpresaEditar" class="col-md-6 form-group bord">
                                        <label class="control-label">Empresa: </label>
       			                        <select class="form-control input-sm" id="selectEmpresaEditar" name="selectEmpresaEditar" type="text" required></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="divSelectRegionalCadastro" class="col-md-6 form-group bord">
                                        <label class="control-label">Regional: </label>
       			                        <select class="form-control input-sm" id="selectRegionalCadastro" name="selectRegionalCadastro" type="text" required></select>
                                    </div>
                                    <div id="divSelectRegionalEditar" class="col-md-6 form-group bord">
                                        <label class="control-label">Regional: </label>
       			                        <select class="form-control input-sm" id="selectRegionalEditar" name="selectRegionalEditar" type="text" required></select>
                                    </div>
                                    <div id="divSelectCargoCadastro" class="col-md-6 form-group bord">
                                        <label class="control-label">Cargo: </label>
       			                        <select class="form-control input-sm" id="selectCargoCadastro" name="selectCargoCadastro" type="text" required></select>
                                    </div>
                                    <div id="divSelectCargoEditar" class="col-md-6 form-group bord">
                                        <label class="control-label">Cargo: </label>
       			                        <select class="form-control input-sm" id="selectCargoEditar" name="selectCargoEditar" type="text" required></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group bord" id="divStatusCad" hidden>
                                        <label class="control-label">Status:</label>
                                        <select class="form-control input-sm" id="statusConfigCalculoComissao">
                                            <option value="1">Ativo</option>
                                            <option value="0">Inativo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label>Opções:</label>
                                        <div class="checkbox-container">
                                            <div class="col-md-12 form-group" style="padding-left: 0px !important;">
                                                <div class="col-md-6" style="padding-left: 0px !important;">
                                                    <label class="checkboxLabel">
                                                        <input type="checkbox" name="consideraProduto" id="consideraProduto"> Considera produto
                                                    </label>
                                                    <label class="checkboxLabel">
                                                        <input type="checkbox" name="consideraLicenca" id="consideraLicenca"> Considera licença
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="checkboxLabel">
                                                        <input type="checkbox" name="temMeta" id="temMeta"> Tem meta
                                                    </label>
                                                    <label class="checkboxLabel">
                                                        <input type="checkbox" name="temIndice" id="temIndice" disabled> Tem índice
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="itensDaConfig">
                            <div id="adicionarItens" style="display: block;">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label>Cenário de Venda</label>
                                            <select class="form-control input-sm" id="cenarioDeVendaItem" name="cenarioDeVendaItem" type="text" style="width: 100%">
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group bord">
                                            <label>Tipo do Cálculo</label>
                                            <select class="form-control input-sm" id="tipoCalculoItem" name="tipoCalculoItem" type="text" style="width: 100%">
                                                <option value="0">Cálculo Por Valor Percentual</option>
                                                <option value="1" selected>Cálculo Por Valor Fixo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label>Valor Percentual:</label>    
                                            <input class="form-control input-sm" id="valorPercentualItem" name="valorPercentualItem" type="text" style="width: 100%" placeholder="Digite o percentual">
                                        </div>
                                        <div class="col-md-6 form-group bord">
                                            <label>Valor Fixo:</label>    
                                            <input class="form-control input-sm" id="valorFixoItem" name="valorFixoItem" type="text" style="width: 100%" onkeyup="formatarMoeda(this.id)" placeholder="Digite o valor em R$">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a class="btn btn-primary" id="adicionarItemTabela" style="float: right;margin-right: 15px;">Adicionar</a>
                                    </div>
                                    <hr>
                                </div>
                                <div id="divTabelaItensConfCalculoComissao">
                                    <table class="table-responsive table-bordered table" id="tabelaItensConfCalculoComissao" style="width: 100%;">
                                        <thead>
                                            <tr class="tableheader">
                                            <th>Cenário de Venda</th>
                                            <th>Tipo de Cálculo</th>
                                            <th>Valor Percentual</th>
                                            <th>Valor Fixo</th>
                                            <th>Id Cenario Venda</th>
                                            <th>Id Tipo Calculo</th>
                                            <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table> 
                                </div>
                            </div>
                            <div id="editarItens" style="display: none;">
                                <div id="div_identificacao">
                                    <table class="table-responsive table-bordered table" id="tabelaItensConfCalculoComissaoListagem" style="width: 100%;">
                                        <thead>
                                            <tr class="tableheader">
                                            <th>Cenário de Venda</th>
                                            <th>Tipo de Cálculo</th>
                                            <th>Valor Percentual</th>
                                            <th>Valor Fixo</th>
                                            <th>Data de Cadastro</th>
                                            <th>Data de Atualização</th>
                                            <th>Status</th>
                                            <th id="acoes">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table> 
                                </div>
                            </div>
                        </div>
                        <div id="divMeta" style="display: none;">
                            <div id="div_identificacao">
                                <table class="table-responsive table-bordered table" id="tabelaMetaListagem" style="width: 100%;">
                                    <thead>
                                        <tr class="tableheader">
                                        <th>Cenário de Venda</th>
                                        <th>Percentual Mín</th>
                                        <th>Percentual Máx</th>
                                        <th>Percentual Comissão Produto</th>
                                        <th>Percentual Comissão Licença</th>
                                        <th>Meta Produto</th>
                                        <th>Meta Licença</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                        <div id="divIndice" style="display: none;">
                            <div id="div_identificacao">
                                <table class="table-responsive table-bordered table" id="tabelaIndiceListagem" style="width: 100%;">
                                    <thead>
                                        <tr class="tableheader">
                                        <th>Cenário de Venda</th>
                                        <th>Tipo Indicador</th>
                                        <th>Meta Mín</th>
                                        <th>Meta Máx</th>
                                        <th>Percentual Limite Salário</th>
                                        <th>Periodicidade</th>
                                        <th>Meta Quantidade</th>
                                        <th>Meta Valor</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnFecharModalEditConf" data-dismiss="modal" aria-hidden="true" style="margin-top: 20px;">Fechar</button>
                    <button class="btn btn-primary" data-botao='' data-id= '' type="submit" id="btnSalvarCadastroConfigCalculoComissao" style="margin-right: 15px; margin-top: 20px">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Itens da Configuração de Cálculo de Comissão -->
<div id="modalItensConfCalculoComissao" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formItensConfCalculoComissao">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("itens_configuracao_calculo_comissao")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div id="div_identificacao">
                        <table class="table-responsive table-bordered table" id="tabelaItensConfCalculoComissaoListagem">
                	    	<thead>
                	    	    <tr class="tableheader">
                	    	    <th>Cenário de Venda</th>
                	    	    <th>Tipo de Cálculo</th>
                	    	    <th>Valor Percentual</th>
                                <th>Valor Fixo</th>
                                <th>Data de Cadastro</th>
                                <th>Data de Atualização</th>
                	    	    <th>Status</th>
                                <th id="acoes">Ações</th>
                	    	    </tr>
                	    	</thead>
                	    	<tbody>
                	    	</tbody>
                	    </table> 
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cadastrar Item da Configuração de Cálculo de Comissão -->
<div id="modalAddItemConfiguracao" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            	<form id="addItemConfiguracao">
                	<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
						<h3 class="modal-title" id="nome-modal-header"></h3>
                	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	</div>
                	<div class="modal-body scrollModal">
						<div class="col-md-12">
                            <div class="row">
								<div class="col-md-6 form-group bord">
                                    <label class="control-label">Cenário de Venda</label>
                                    <select class="form-control input-sm" id="addCenarioDeVendaItem" name="addCenarioDeVendaItem" type="text" style="width: 100%" required>
                                    </select>
    							</div>
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Tipo do Cálculo</label>
                                    <select class="form-control input-sm" id="addTipoCalculoItem" name="addTipoCalculoItem" type="text" style="width: 100%" required>
                                        <option value="0">Cálculo Por Valor Percentual</option>
                                        <option value="1" selected>Cálculo Por Valor Fixo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group bord">
									<label class="control-label">Valor Percentual:</label>    
									<input class="form-control input-sm" id="addValorPercentualItem" name="addValorPercentualItem" type="text" style="width: 100%" placeholder="Digite o percentual" disabled required>
    							</div>
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Valor Fixo:</label>    
									<input class="form-control input-sm" id="addValorFixoItem" name="addValorFixoItem" type="text" style="width: 100%" onkeyup="formatarMoeda(this.id)" placeholder="Digite o valor em R$" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group bord" id="divStatusItem" hidden>
                                    <label class="control-label">Status</label>
                                    <select class="form-control input-sm" id="statusAddItemConf" required>
                                        <option value="1">Ativo</option>
                                        <option value="0">Inativo</option>
                                    </select>
                                </div>
                            </div>
						</div>
					</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>

                    <button class="btn btn-primary" data-id='' data-idItem='' type="submit" id="btnSalvarAddItemConfiguracao" style="margin-right: 15px;">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Cadastrar metas da Configuração de Cálculo de Comissão -->
<div id="modalCadastroMeta" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formCadastrarMeta">
            	<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title"><?=lang("nova_meta")?></h3>
            	</div>
            	<div class="modal-body scrollModal">
					<div class="col-md-12">
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-6 form-group bord">
                                <label>Cenário de Venda:</label>
                                <select class="form-control input-sm" id="cenarioDeVendaMeta" name="cenarioDeVendaMeta" type="text" style="width: 100%">
                                </select>
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label>Percentual Mínimo:</label>
                                <input class="form-control input-sm" id="valorPercentualMinMeta" name="valorPercentualMinMeta" type="text" style="width: 100%" placeholder="Digite o percentual mínimo">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group bord">
                                <label>Percentual Máximo:</label>
                                <input class="form-control input-sm" id="valorPercentualMaxMeta" name="valorPercentualMaxMeta" type="text" style="width: 100%" placeholder="Digite o percentual máximo">
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label>Percentual Comissão Produto:</label>
                                <input class="form-control input-sm" id="valorPercentualComissaoProdutoMeta" name="valorPercentualComissaoProdutoMeta" type="text" style="width: 100%" placeholder="Digite o percentual comissão produto">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group bord">
                                <label>Percentual Comissão Licença:</label>
                                <input class="form-control input-sm" id="valorPercentualComissaoLicencaMeta" name="valorPercentualComissaoLicencaMeta" type="text" style="width: 100%" placeholder="Digite o percentual comissão licença">
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label>Meta Produto (R$):</label>
                                <input class="form-control input-sm" id="valorMetaProduto" name="valorMetaProduto" type="text" onkeyup="formatarMoeda(this.id)" style="width: 100%" placeholder="Digite o valor">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group bord">
                                <label>Meta Licença (R$):</label>
                                <input class="form-control input-sm" id="valorMetaLicenca" name="valorMetaLicenca" type="text" onkeyup="formatarMoeda(this.id)" style="width: 100%" placeholder="Digite o valor">
                            </div>
                            <div class="col-md-6 form-group">
                               <button class="btn btn-primary" type="submit" id="btnAdicionarMetaTabela" style="margin-top: 23px;">Adicionar à Tabela</button>
                            </div>
                        </div>
                        <hr>
                        <div id="divTabelaItensdeMeta">
                            <table class="table-responsive table-bordered table" id="tabelaItensdeMeta" style="width: 100%;">
                                <thead>
                                    <tr class="tableheader">
                                    <th>Id Cenário</th>
                                    <th>Cenário de Venda</th>
                                    <th>Percentual Mín</th>
                                    <th>Percentual Máx</th>
                                    <th>Percentual Comissão Produto</th>
                                    <th>Percentual Comissão Licença</th>
                                    <th>Meta Produto</th>
                                    <th>Meta Licença</th>
                                    <th>Ações</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table> 
                        </div>
					</div>
				</div>
                <br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" data-idConf='' type="submit" id="btnSalvarMeta" style="margin-right: 15px;">Salvar Meta(s)</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal editar meta da Configuração de Cálculo de Comissão -->
<div id="modalEditMeta" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formEditarMeta">
            	<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title"><?=lang("editar_meta")?></h3>
            	</div>
            	<div class="modal-body scrollModal">
					<div class="col-md-12">
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Cenário de Venda:</label>
                                <select class="form-control input-sm" id="cenarioDeVendaMetaEdit" name="cenarioDeVendaMetaEdit" type="text" style="width: 100%" required>
                                </select>
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Percentual Mínimo:</label>
                                <input class="form-control input-sm" id="valorPercentualMinMetaEdit" name="valorPercentualMinMetaEdit" type="text" style="width: 100%" placeholder="Digite o percentual mínimo" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Percentual Máximo:</label>
                                <input class="form-control input-sm" id="valorPercentualMaxMetaEdit" name="valorPercentualMaxMetaEdit" type="text" style="width: 100%" placeholder="Digite o percentual máximo" required>
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Percentual Comissão Produto:</label>
                                <input class="form-control input-sm" id="valorPercentualComissaoProdutoMetaEdit" name="valorPercentualComissaoProdutoMetaEdit" type="text" style="width: 100%" placeholder="Digite o percentual comissão produto" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Percentual Comissão Licença:</label>
                                <input class="form-control input-sm" id="valorPercentualComissaoLicencaMetaEdit" name="valorPercentualComissaoLicencaMetaEdit" type="text" style="width: 100%" placeholder="Digite o percentual comissão licença" required>
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Meta Produto (R$):</label>
                                <input class="form-control input-sm" id="valorMetaProdutoEdit" name="valorMetaProdutoEdit" type="text" onkeyup="formatarMoeda(this.id)" style="width: 100%" placeholder="Digite o valor" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Meta Licença (R$):</label>
                                <input class="form-control input-sm" id="valorMetaLicencaEdit" name="valorMetaLicencaEdit" type="text" onkeyup="formatarMoeda(this.id)" style="width: 100%" placeholder="Digite o valor" required>
                            </div>
                        </div>
					</div>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" data-idMeta='' data-idConfig='' data-metaStatus='' type="submit" id="btnEditarMeta" style="margin-right: 15px;">Editar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Cadastrar índice da Configuração de Cálculo de Comissão -->
<div id="modalCadastroIndice" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formCadastrarIndice">
            	<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title"><?=lang("novo_indice")?></h3>
            	</div>
            	<div class="modal-body scrollModal">
					<div class="col-md-12">
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-6 form-group bord">
                                <label>Cenário de Venda:</label>
                                <select class="form-control input-sm" id="cenarioDeVendaIndice" name="cenarioDeVendaIndice" type="text" style="width: 100%">
                                </select>
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label>Indicador:</label>
                                <select class="form-control input-sm" id="tipoIndicadornIndice" name="tipoIndicadornIndice" type="text" style="width: 100%">
                                    <option value="" selected disabled>Selecione o tipo do indicador</option>
                                    <option value="0">Usa quantidade de equipamento</option>
                                    <option value="1">Usa Receita de licença de uso</option>
                                    <option value="2">Usa receita de equipamento e taxas de instalação</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group bord">
                                <label>Meta Mínima:</label>
                                <input class="form-control input-sm" id="metaMinIndicadorIndice" name="metaMinIndicadorIndice" type="text" style="width: 100%" placeholder="Digite a meta mínima">
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label>Meta Máxima:</label>
                                <input class="form-control input-sm" id="metaMaxIndicadorIndice" name="metaMaxIndicadorIndice" type="text" style="width: 100%" placeholder="Digite a meta máxima">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group bord">
                                <label>Percentual Limite Salário:</label>
                                <input class="form-control input-sm" id="percentualLimiteSalarioIndice" name="percentualLimiteSalarioIndice" type="text" style="width: 100%" placeholder="Digite o percentual limite salário">
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label>Periodicidade:</label>
                                <select class="form-control input-sm" id="periodicidadeIndice" name="periodicidadeIndice" type="text" style="width: 100%">
                                    <option value="" selected disabled>Selecione a periodicidade</option>
                                    <option value="0">Mensal</option>
                                    <option value="1">Trimestral</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group bord">
                                <label>Meta Quantidade:</label>
                                <input class="form-control input-sm" id="metaQuantidadeIndice" name="metaQuantidadeIndice" type="number" placeholder="Digite a quantidade">
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label>Meta Valor (R$):</label>
                                <input class="form-control input-sm" id="metaValorIndice" name="metaValorIndice" type="text" onkeyup="formatarMoeda(this.id)" style="width: 100%" placeholder="Digite o valor">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                               <button class="btn btn-primary" type="submit" id="btnAdicionarIndiceTabela" style="margin-top: 23px;">Adicionar à Tabela</button>
                            </div>
                        </div>
                        <hr>
                        <div id="divTabelaIndices">
                            <table class="table-responsive table-bordered table" id="tabelaIndices" style="width: 100%;">
                                <thead>
                                    <tr class="tableheader">
                                    <th>Id Cenário</th>
                                    <th>Cenário de Venda</th>
                                    <th>Id Tipo Indicador</th>
                                    <th>Indicador</th>
                                    <th>Meta Mín</th>
                                    <th>Meta Máx</th>
                                    <th>Percentual Limite Salário</th>
                                    <th>Id Periodicidade </th>
                                    <th>Periodicidade</th>
                                    <th>Meta Quantidade</th>
                                    <th>Meta Valor</th>
                                    <th>Ações</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table> 
                        </div>
					</div>
				</div>
                <br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" data-idConf='' type="submit" id="btnSalvarIndice" style="margin-right: 15px;">Salvar Índice(s)</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal editar índice da Configuração de Cálculo de Comissão -->
<div id="modalEditIndice" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formEditarIndice">
            	<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title"><?=lang("editar_indice")?></h3>
            	</div>
            	<div class="modal-body scrollModal">
					<div class="col-md-12">
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Cenário de Venda:</label>
                                <select class="form-control input-sm" id="cenarioDeVendaIndiceEdit" name="cenarioDeVendaIndiceEdit" type="text" style="width: 100%" required>
                                </select>
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Indicador:</label>
                                <select class="form-control input-sm" id="tipoIndicadornIndiceEdit" name="tipoIndicadornIndiceEdit" type="text" style="width: 100%" required>
                                    <option value="" selected disabled>Selecione o tipo do indicador</option>
                                    <option value="0">Usa quantidade de equipamento</option>
                                    <option value="1">Usa Receita de licença de uso</option>
                                    <option value="2">Usa receita de equipamento e taxas de instalação</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Meta Mínima:</label>
                                <input class="form-control input-sm" id="metaMinIndicadorIndiceEdit" name="metaMinIndicadorIndiceEdit" type="text" style="width: 100%" placeholder="Digite a meta mínima" required>
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Meta Máxima:</label>
                                <input class="form-control input-sm" id="metaMaxIndicadorIndiceEdit" name="metaMaxIndicadorIndiceEdit" type="text" style="width: 100%" placeholder="Digite a meta máxima" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Percentual Limite Salário:</label>
                                <input class="form-control input-sm" id="percentualLimiteSalarioIndiceEdit" name="percentualLimiteSalarioIndiceEdit" type="text" style="width: 100%" placeholder="Digite o percentual limite salário" required>
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Periodicidade:</label>
                                <select class="form-control input-sm" id="periodicidadeIndiceEdit" name="periodicidadeIndiceEdit" type="text" style="width: 100%" required>
                                    <option value="" selected disabled>Selecione a periodicidade</option>
                                    <option value="0">Mensal</option>
                                    <option value="1">Trimestral</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Meta Quantidade:</label>
                                <input class="form-control input-sm" id="metaQuantidadeIndiceEdit" name="metaQuantidadeIndiceEdit" type="number" placeholder="Digite a quantidade" required>
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Meta Valor (R$):</label>
                                <input class="form-control input-sm" id="metaValorIndiceEdit" name="metaValorIndiceEdit" type="text" onkeyup="formatarMoeda(this.id)" style="width: 100%" placeholder="Digite o valor" required>
                            </div>
                        </div>
					</div>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" data-idIndice='' data-idConfig='' data-indiceStatus='' type="submit" id="btnEditarIndice" style="margin-right: 15px;">Editar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Cadastrar item do índice da Configuração de Cálculo de Comissão -->
<div id="modalCadastroItemIndice" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formCadastrarItemIndice">
            	<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title"><?=lang("novo_item_indice")?></h3>
            	</div>
            	<div class="modal-body scrollModal">
					<div class="col-md-12">
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-6 form-group bord">
                                <label>Indicador:</label>
                                <select class="form-control input-sm" id="tipoIndicadornItemIndice" name="tipoIndicadornItemIndice" type="text" style="width: 100%">
                                    <option value="" selected disabled>Selecione o tipo do indicador</option>
                                    <option value="0">Usa quantidade de equipamento</option>
                                    <option value="1">Usa Receita de licença de uso</option>
                                    <option value="2">Usa receita de equipamento e taxas de instalação</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label>Percentual Meta:</label>
                                <input class="form-control input-sm" id="percentualMetaItemIndice" name="percentualMetaItemIndice" type="text" style="width: 100%" placeholder="Digite o percentual meta">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group bord">
                                <label>Percentual Salário:</label>
                                <input class="form-control input-sm" id="percentualSalarioItemIndice" name="percentualSalarioItemIndice" type="text" style="width: 100%" placeholder="Digite o percentual salário">
                            </div>
                            <div class="col-md-6 form-group">
                               <button class="btn btn-primary" type="submit" id="btnAddItemIndiceTabela" style="margin-top: 23px;">Adicionar à Tabela</button>
                            </div>
                        </div>
                        <hr>
                        <div id="divTabelaItemIndices">
                            <table class="table-responsive table-bordered table" id="tabelaItensIndice" style="width: 100%;">
                                <thead>
                                    <tr class="tableheader">
                                    <th>Id Tipo Indicador</th>
                                    <th>Indicador</th>
                                    <th>Percentual Meta</th>
                                    <th>Percentual Salário</th>
                                    <th>Ações</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table> 
                        </div>
					</div>
				</div>
                <br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" data-idIndice='' type="submit" id="btnSalvarItemIndice" style="margin-right: 15px;">Salvar Item(s)</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modalItensDoIndice" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formItensDoIndice">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("itens_indice")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div id="div_identificacao">
                        <table class="table-responsive table-bordered table" id="tabelaItensDoIndiceListagem">
                	    	<thead>
                	    	    <tr class="tableheader">
                	    	    <th>Indicador</th>
                	    	    <th>Percentual Meta</th>
                	    	    <th>Percentual Salário</th>
                                <th>Status</th>
                                <th>Ações</th>
                	    	    </tr>
                	    	</thead>
                	    	<tbody>
                	    	</tbody>
                	    </table> 
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal editar item do índice da Configuração de Cálculo de Comissão -->
<div id="modalEditItemIndice" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formEditarItemIndice">
            	<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title"><?=lang("editar_item_indice")?></h3>
            	</div>
            	<div class="modal-body scrollModal">
					<div class="col-md-12">
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-6 form-group bord">
                                <label class="control-label">Indicador:</label>
                                <select class="form-control input-sm" id="tipoIndicadornItemIndiceEdit" name="tipoIndicadornItemIndiceEdit" type="text" style="width: 100%" required>
                                    <option value="" selected disabled>Selecione o tipo do indicador</option>
                                    <option value="0">Usa quantidade de equipamento</option>
                                    <option value="1">Usa Receita de licença de uso</option>
                                    <option value="2">Usa receita de equipamento e taxas de instalação</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group bord">
                                <label  class="control-label">Percentual Meta:</label>
                                <input class="form-control input-sm" id="percentualMetaItemIndiceEdit" name="percentualMetaItemIndiceEdit" type="text" style="width: 100%" placeholder="Digite o percentual meta" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group bord">
                                <label  class="control-label">Percentual Salário:</label>
                                <input class="form-control input-sm" id="percentualSalarioItemIndiceEdit" name="percentualSalarioItemIndiceEdit" type="text" style="width: 100%" placeholder="Digite o percentual salário" required>
                            </div>
                        </div>
					</div>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" data-idItemIndice='' data-idIndice='' data-itemIndiceStatus='' type="submit" id="btnEditarItemIndice" style="margin-right: 15px;">Editar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>
<script>

var tabelaconfCalculoComissao = $('#tabelaconfCalculoComissao').DataTable({
    responsive: true,
    ordering: true,
    paging: true,
    searching: true,
    info: true,
    autoWidth: false,
    order: [0, 'desc'],
    language: {
        loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
        searchPlaceholder:  'Pesquisar',
        emptyTable:         "Nenhuma configuração de cálculo de comissão a ser listada",
        info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
        infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
        zeroRecords:        "Nenhum resultado encontrado.",
        paginate: {
            first:          "Primeira",
            last:           "Última",
            next:           "Próxima",
            previous:       "Anterior"
        },
    },
    deferRender: true,
    lengthChange: false,
    ajax: {
        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarConfCalculoComissao') ?>',
        type: 'GET',
        dataType: 'json',
        success: function(data){
            if (data.status === 200){
                tabelaconfCalculoComissao.clear().draw();
                tabelaconfCalculoComissao.rows.add(data.results).draw();
                tabelaconfCalculoComissao.columns.adjust().draw();
            }else{
                tabelaconfCalculoComissao.clear().draw();
            }
        },
        error: function(){
            alert('Erro ao listar configuração de cáculo de comissão. Tente novamente!')
            tabelaconfCalculoComissao.clear().draw();
        }
    },
    createdRow: function(row, data, dataIndex){
        $('td:eq(0)', row).css('min-width', '220px');
    },
    columns: [
        { data: 'id',
            visible: false},
        { data: 'nomeConfig', width: '10%'},
        {data: 'nomeEmpresa', width: '10%'},
        { data: 'nomeRegional', width: '10%'},
        { data: 'nomeCargo', width: '10%'},
        { data: 'temMeta', width: '8%',
            render: function(data){
                return data == 1 ? 'Sim' : 'Não';
            }},
        { data: 'temIndice', width: '8%',
            render: function(data){ {
                return data == 1 ? 'Sim' : 'Não';
            }},
        },
        { data: 'consideraProduto', width: '8%',
            render: function(data){
                return data == 1 ? 'Sim' : 'Não';
            }},
        { data: 'consideraLicenca', width: '8%',
            render: function(data){
                return data == 1 ? 'Sim' : 'Não';
            }},
        { data: 'dataCadastro', width: '10%',
            render: function (data) {
                        var date = new Date(data);
                        date.setDate(date.getDate());

                        return date.toLocaleDateString('pt-BR');
                    }
        },
        { data: 'dataUpdate', width: '10%',
            render: function (data) {
                        var date = new Date(data);
                        date.setDate(date.getDate());

                        return date.toLocaleDateString('pt-BR');
                    }
        },   
        {
			data:{'id':'id', 'nomeConfig':'nomeConfig', 'idEmpresa': 'idEmpresa','idRegional': 'idRegional', 'idCargo': 'idCargo', 'dataCadastro': 'dataCadastro', 'dataUpdate': 'dataUpdate', 'status': 'status'},
			orderable: false,
            width: '8%',
			render: function (data) {
				return `
				<button 
					class="btn btn-primary"
					title="Editar Configuração"
					id="btnEditarConfiguracao"
                    onClick="javascript:editConfiguracao(this,'${data['nomeConfig']}','${data['id']}','${data['idEmpresa']}','${data['idRegional']}', '${data['idCargo']}', '${data['status']}', '${data['temMeta']}', '${data['consideraProduto']}', '${data['consideraLicenca']}', '${data['temIndice']}' )">
					<i class="fa fa-pencil" aria-hidden="true"></i>
				</button>
                `;
			}
		}
    ]
});

let tabelaItensConfCalculoComissao = $('#tabelaItensConfCalculoComissao').DataTable({
		responsive: true,
		ordering: false,
		paging: true,
		searching: true,
		info: true,
		language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum item adicionado",
            info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords:        "Nenhum resultado encontrado.",
            paginate: {
                first:          "Primeira",
                last:           "Última",
                next:           "Próxima",
                previous:       "Anterior"
            },
        },
		deferRender: true,
		lengthChange: false,
		columns: [
            { data: 'cenarioVenda'},
            { data: 'tipoCalculo'},
            { data: 'valorPercentual',
                render: function(data) {
                    return data + '%';
                }
            },
			{
				data: "valorFixo",
				render: function(data) {
					return parseFloat(data).toLocaleString('pt-BR', {
						style: 'currency',
						currency: 'BRL'
					});
				}
			},
            { data: 'idCenarioVenda',
                visible: false},
            { data: 'idTipoCalculo',
                visible: false},
			{
				data:{ 'cenarioVenda': 'cenarioVenda' },
				orderable: false,
				render: function (data) {
					return `
					<button 
                        id="btnExcluirItemTabela"
						class="btn fa fa-trash"
						title="Excluir Item"
						style="width: 38px; height: 34px;margin: 0 auto; background-color: red; color: white;"
                        onClick="javascript:excluirItemTabela(this, '${data['cenarioVenda']}' ,event)">
					`;
				}
			}
		]
	})

    let tabelaItensConfCalculoComissaoListagem = $('#tabelaItensConfCalculoComissaoListagem').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum item adicionado",
            info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords:        "Nenhum resultado encontrado.",
            paginate: {
                first:          "Primeira",
                last:           "Última",
                next:           "Próxima",
                previous:       "Anterior"
            },
        },
        deferRender: true,
        lengthChange: false,
        columns: [
            { data: 'nomeCenarioVenda'},
            { data: 'tipoCalculo',
                render: function(data){
                    return data == 0 ? 'Cálculo Por Valor Percentual' : 'Cálculo Por Valor Fixo';
                }},
            { data: 'valorPercentual',
                render: function(data) {
                    return data + '%';
                }
            },
            {
                data: "valorFixo",
                render: function(data) {
                    return parseFloat(data).toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    });
                }
            },
            
            { data: 'dataCadastro',
                render: function(data) {
                    return new Date(data).toLocaleDateString();
                }},
            { data: 'dataUpdate',
                render: function(data) {
                    return new Date(data).toLocaleDateString();
                }},
            { data: 'status'},
            {
				data:{'id':'id', 'idConfigCalculoComissao':'idConfigCalculoComissao', 'valorPercentual':'valorPercentual', 'valorFixo':'valorFixo' ,'status':'status'},
				orderable: false,
				render: function (data) {
					return `
					<button 
						class="btn btn-primary"
						title="Editar Item da Configuração"
						id="btnEditarItemConfiguracaoListagem"
                        onClick="javascript:abrirEditarItemConfiguracao(this,'${data['id']}','${data['idConfigCalculoComissao']}','${data['idCenarioVenda']}','${data['tipoCalculo']}','${data['valorPercentual']}','${data['valorFixo']}','${data['status']}',event)">
						<i class="fa fa-pencil" aria-hidden="true"></i>
					</button>
                    <button
                        id="btnAlterarStatusItemConfiguracaoListagem"
                        class="btn fa fa-exchange"
                        title="${data['status'] === 'Ativo' ? 'Inativar Item' : 'Ativar Item'}"
                        style="width: 42px; height: 35px;margin: 0 auto; background-color: ${data['status'] === 'Ativo' ? 'red' : 'green'}; color: white;"
                        onClick="javascript:alterarStatusItemConfig(this,'${data['id']}','${data['idConfigCalculoComissao']}','${data['status']}', event)">
                    </button>
                    `;
				}
			}
        ],
        dom: 'Blfrtip',
        buttons: [
            {
                text: 'Adicionar item',
                className: 'btn btn-primary btnAbrirAdicionarItem',
                action: function (e) {
                    abrirAddItemConfiguracao(e);
                }
            }
        ]
    });


$('#abrirModalInserir').click(async function(){
    btn = $(this);
    btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

    let regionaisCad  = await $.ajax ({
                            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarRegionaisSelect2') ?>',
                            dataType: 'json',
                            delay: 1000,
                            type: 'GET',
                            data: function (params) {
                                return {
                                    q: params.term
                                };
                            },
                            error: function(){
                                alert('Erro ao buscar regionais, tente novamente');
                                btn.attr('disabled', false).html('Nova Configuração');
                            }
                        });
                       
    $('#selectRegionalCadastro').select2({
        data: regionaisCad.results,
        placeholder: "Selecione a regional",
        allowClear: true,
        language: "pt-BR",
        width: '100%',
        minimumInputLength: 3,
        });
    
    $('#selectRegionalCadastro').on('select2:select', function (e) {
        var data = e.params.data;
    });

    let cargosCad  = await $.ajax ({
                        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCargosSelect2') ?>',
                        dataType: 'json',
                        delay: 1000,
                        type: 'GET',
                        data: function (params) {
                            return {
                                q: params.term
                            };
                        },
                        error: function(){
                            alert('Erro ao buscar cargos, tente novamente');
                            btn.attr('disabled', false).html('Nova Configuração');
                        }
                    });
                    
    $('#selectCargoCadastro').select2({
        data: cargosCad.results,
        placeholder: "Selecione o cargo",
        allowClear: true,
        language: "pt-BR",
        width: '100%',
        minimumInputLength: 3,
        });
    
    $('#selectCargoCadastro').on('select2:select', function (e) {
        var data = e.params.data;
    });

    let cenariosDeVendaCadItens  = await $.ajax ({
                                    url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCenariosDeVendasSelect2') ?>',
                                    dataType: 'json',
                                    delay: 1000,
                                    type: 'GET',
                                    data: function (params) {
                                        return {
                                            q: params.term
                                        };
                                    },
                                    error: function(){
                                        alert('Erro ao buscar cenários de vendas, tente novamente');
                                        btn.attr('disabled', false).html('Nova Configuração');
                                    }
                                });
                       
    $('#cenarioDeVendaItem').select2({
        data: cenariosDeVendaCadItens.results,
        placeholder: "Selecione o cenário de venda",
        allowClear: true,
        language: "pt-BR",
        width: '100%',
        minimumInputLength: 3,
        });
    
    $('#cenarioDeVendaItem').on('select2:select', function (e) {
        var data = e.params.data;
    });

    $('#valorPercentualItem').attr('disabled', true);
    $('#valorPercentualItem').val(0);
    $('#valorFixoItem').attr('disabled', false);
    $('#valorFixoItem').val('');
    $('#tipoCalculoItem').val(1);
    $('#divSelectEmpresaCadastro').attr('hidden', false);
    $('#selectEmpresaCadastro').attr('required', true);
    $('#divSelectEmpresaEditar').attr('hidden', true);
    $('#selectEmpresaEditar').attr('required', false);
    $('#divSelectRegionalCadastro').attr('hidden', false);
    $('#selectRegionalCadastro').attr('required', true);
    $('#divSelectRegionalEditar').attr('hidden', true);
    $('#selectRegionalEditar').attr('required', false);
    $('#divSelectCargoCadastro').attr('hidden', false);
    $('#selectCargoCadastro').attr('required', true);
    $('#divSelectCargoEditar').attr('hidden', true);
    $('#selectCargoEditar').attr('required', false);
    $('#divStatusCad').attr('hidden', true);
    $('#statusConfigCalculoComissao').attr('required', false);
    $('#selectCargoCadastro').append('<option value="" disabled selected>Selecione o cargo</option>');
    $('#selectRegionalCadastro').append('<option value="" disabled selected>Selecione a regional</option>');
    $('#cenarioDeVendaItem').append('<option value="" disabled selected>Selecione o cenário de venda</option>');
    $('#nomeHeaderModal').html('<?=lang("nova_configuracao_calculo_comissao")?>');
    btn.attr('disabled', false).html('Nova Configuração');
    $('#tab-dadosGerais').click();
    $('#tab-itensDaConfig').show();
    $('#adicionarItens').show();
    $('#editarItens').hide();
    
    $('#temMeta').prop('checked', false).trigger('change');
    $('#consideraProduto').prop('checked', true);
    $('#consideraLicenca').prop('checked', true);
    $('#temIndice').prop('checked', false).trigger('change');

    $('#modalCadConfigCalculoComissao').modal('show');
});

$('#selectEmpresaCadastro').select2({
        ajax: {
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarEmpresas') ?>',
            type: 'GET',
            dataType: 'json',
            delay: 1000,
            data: function (params) {
                return {
                    q: params.term,
                };
            },
        },
        width: '100%',
        placeholder: 'Selecione uma empresa',
        allowClear: true,
        language: "pt-BR",
        minimumInputLength: 3,
});

$('#btnSalvarCadastroConfigCalculoComissao').click(function(e){
    e.preventDefault();

    var id = $('#btnSalvarCadastroConfigCalculoComissao').data('id');
    var nome = $('#nomeConfigCalculoComissao').val();
    var idEmpresa = $('#selectEmpresaCadastro').val();
    var idRegional = $('#selectRegionalCadastro').val();
    var idCargo = $('#selectCargoCadastro').val();
    var status = $('#statusConfigCalculoComissao').val();
    var idEmpresaEditar = $('#selectEmpresaEditar').val();
    var idRegionalEditar = $('#selectRegionalEditar').val();
    var idCargoEditar = $('#selectCargoEditar').val();
    var temMeta = $('#temMeta').is(':checked') ? 1 : 0;
    var consideraProduto = $('#consideraProduto').is(':checked') ? 1 : 0;
    var consideraLicenca = $('#consideraLicenca').is(':checked') ? 1 : 0;
    var temIndice = $('#temIndice').is(':checked') ? 1 : 0;

    var dadosTabelaInserir = tabelaItensConfCalculoComissao.rows().data().toArray().map(function(item) {
            return {
                idCenarioVenda: item.idCenarioVenda,
                tipoCalculo: item.idTipoCalculo,
                valorPercentual: item.valorPercentual,
                valorFixo: item.valorFixo,
            }
        });


    if (tabelaItensConfCalculoComissao.rows().data().toArray().length > 0){
        if (nome == '' || idEmpresa == null || idRegional == null || idCargo == null){
            alert('Preencha todos os campos');
        }else{
            $('#btnSalvarCadastroConfigCalculoComissao').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Salvando...');
            $.ajax({
		    	url: `<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/cadastrarConfCalculoComissaoEItem') ?>`,
		    	type: "POST",
		    	dataType: "json",
		    	data: {
                    temMeta : temMeta,
                    temIndice: temIndice,
                    consideraProduto : consideraProduto,
                    consideraLicenca : consideraLicenca,
                    nome: nome,
		    		idEmpresa: idEmpresa,
		    		idRegional: idRegional,
		    		idCargo: idCargo,
		    		itens: dadosTabelaInserir,
		    	},
		    	success: function(response){
                    console.log(response);
		    		if (response.status == 200){
                        alert(response.dados.mensagem);
		    			$("#modalCadConfigCalculoComissao").modal("hide");
                        tabelaconfCalculoComissao.ajax.reload();
		    			$('#btnSalvarCadastroConfigCalculoComissao').attr('disabled', false).html('Salvar');
		    		}else{
		    			alert(response.dados.mensagem);
		    			$('#btnSalvarCadastroConfigCalculoComissao').attr('disabled', false).html('Salvar');
		    		}
		    	},
		    	error: function(error){
		    		alert('Erro ao cadastrar configuração de cálculo, tente novamente');
		    		$('#btnSalvarCadastroConfigCalculoComissao').attr('disabled', false).html('Salvar');
		    	}
		    });
        }
    }else{
        if ($('#nomeHeaderModal').html() == '<?=lang("nova_configuracao_calculo_comissao")?>'){
            if (nome == '' || idEmpresa == null || idRegional == null || idCargo == null){
                alert('Preencha todos os campos');
            }else{
                $('#btnSalvarCadastroConfigCalculoComissao').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Salvando...');
                $.ajax({
                    url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/adicionarConfCalculoComissao') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        temMeta: temMeta,
                        temIndice: temIndice,
                        consideraProduto : consideraProduto,
                        consideraLicenca : consideraLicenca, 
                        nome: nome,
                        idEmpresa: idEmpresa,
                        idRegional: idRegional,
                        idCargo: idCargo,
                    },
                    success: function(data){
                        if (data.status === 200){
                            alert(data.dados.mensagem)
                            $('#btnSalvarCadastroConfigCalculoComissao').attr('disabled', false).html('Salvar');
                            tabelaconfCalculoComissao.ajax.reload();
                            $('#modalCadConfigCalculoComissao').modal('hide');
                        }else{
                            alert(data.dados.mensagem);
                            $('#btnSalvarCadastroConfigCalculoComissao').attr('disabled', false).html('Salvar');
                        
                        }
                    },
                    error: function(){
                        alert('Erro ao cadastrar configuração de cáculo de comissão. Tente novamente!');
                        $('#btnSalvarCadastroConfigCalculoComissao').attr('disabled', false).html('Salvar');
                    }
                })
            }   
        }else{

            if (nome == '' || idEmpresaEditar == null || idRegionalEditar == null || idCargoEditar == null){
                alert('Preencha todos os campos');
            }else{
                $('#btnSalvarCadastroConfigCalculoComissao').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Editando...');
        
                $.ajax({
                    url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/editarConfCalculoComissao') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        idConfig: id,
                        temMeta : temMeta,
                        temIndice: temIndice,
                        consideraProduto : consideraProduto,
                        consideraLicenca : consideraLicenca,
                        nome: nome,
                        idEmpresa: idEmpresaEditar,
                        idRegional: idRegionalEditar,
                        idCargo: idCargoEditar,
                        status: status,
                    },
                    success: function(data){
                        if (data.status === 200){
                            alert(data.dados.mensagem)
                            $('#btnSalvarCadastroConfigCalculoComissao').attr('disabled', false).html('Salvar');
                            tabelaconfCalculoComissao.ajax.reload();
                            $('#modalCadConfigCalculoComissao').modal('hide');
                        }else{
                            alert(data.dados.mensagem);
                            $('#btnSalvarCadastroConfigCalculoComissao').attr('disabled', false).html('Salvar');
                        
                        }
                    },
                    error: function(){
                        alert('Erro ao editar configuração de cáculo de comissão. Tente novamente!');
                        $('#btnSalvarCadastroConfigCalculoComissao').attr('disabled', false).html('Salvar');
                    }
                })
            }
        } 
    }
})

$('#modalCadConfigCalculoComissao').on('hidden.bs.modal', function () {
    $('#nomeConfigCalculoComissao').val('');
    $('#selectEmpresaCadastro').val(null).trigger('change');
    $('#selectRegionalCadastro').val(null).trigger('change');
    $('#selectCargoCadastro').val(null).trigger('change');
    $('#selectEmpresaEditar').val(null).trigger('change');
    $('#selectRegionalEditar').val(null).trigger('change');
    $('#selectCargoEditar').val(null).trigger('change');
    $('#cenarioDeVendaItem').val('').trigger('change');
    $('#tipoCalculoItem').val(1);
    $('#valorPercentualItem').val(0);
    $('#valorPercentualItem').attr('disabled', true);
    $('#valorFixoItem').attr('disabled', false);
    $('#valorFixoItem').val('');
    $('#temMeta').prop('checked', false);
    $('#consideraProduto').prop('checked', false);
    $('#consideraLicenca').prop('checked', false);
    $('#idConfig').val('');
    $('#temIndice').prop('checked', false);

    tabelaItensConfCalculoComissao.clear().draw();
});

async function editConfiguracao(botao, nome, id, idEmpresa, idRegional, idCargo, status, temMeta, consideraProduto, consideraLicenca, temIndice){
    btn = $(botao);
    $('#nomeHeaderModal').html('<?=lang("editar_configuracao_calculo_comissao")?>');
    
    if (status == 'Ativo'){
        status = 1;
    }else{
        status = 0;
    }

    btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    let empresasEdit  = await $.ajax ({
                            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarEmpresas') ?>',
                            dataType: 'json',
                            delay: 1000,
                            type: 'GET',
                            data: function (params) {
                                return {
                                    q: params.term
                                };
                            },
                            error: function(){
                                alert('Erro ao buscar empresas, tente novamente');
                                btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
                            }
                        });

    $('#selectEmpresaEditar').select2({
        data: empresasEdit.results,
        placeholder: "Selecione a empresa",
        allowClear: true,
        language: "pt-BR",
        width: '100%'
        });
    
    $('#selectEmpresaEditar').on('select2:select', function (e) {
        var data = e.params.data;
    });

    let regionaisEdit  = await $.ajax ({
                        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarRegionaisSelect2') ?>',
                        dataType: 'json',
                        delay: 1000,
                        type: 'GET',
                        data: function (params) {
                            return {
                                q: params.term
                            };
                        },
                        error: function(){
                            alert('Erro ao buscar regionais, tente novamente');
                            btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
                        }
                    });
                    
    $('#selectRegionalEditar').select2({
        data: regionaisEdit.results,
        placeholder: "Selecione a regional",
        allowClear: true,
        language: "pt-BR",
        width: '100%',
        });
    
    $('#selectRegionalEditar').on('select2:select', function (e) {
        var data = e.params.data;
    });

    let cargosEdit  = await $.ajax ({
                        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCargosSelect2') ?>',
                        dataType: 'json',
                        delay: 1000,
                        type: 'GET',
                        data: function (params) {
                            return {
                                q: params.term
                            };
                        },
                        error: function(){
                            alert('Erro ao buscar cargos, tente novamente');
                            btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
                        }
                    });
                    
    $('#selectCargoEditar').select2({
        data: cargosEdit.results,
        placeholder: "Selecione o cargo",
        allowClear: true,
        language: "pt-BR",
        width: '100%',
        });
    
    $('#selectCargoEditar').on('select2:select', function (e) {
        var data = e.params.data;
    });
    
    $('#tab-dadosGerais').click();
    $('#adicionarItens').hide();
    $('#editarItens').show();
    $('#divSelectEmpresaCadastro').attr('hidden', true);
    $('#selectEmpresaCadastro').attr('required', false);
    $('#divSelectEmpresaEditar').attr('hidden', false);
    $('#selectEmpresaEditar').attr('required', true);
    $('#divSelectRegionalCadastro').attr('hidden', true);
    $('#selectRegionalCadastro').attr('required', false);
    $('#divSelectRegionalEditar').attr('hidden', false);
    $('#selectRegionalEditar').attr('required', true);
    $('#divSelectCargoCadastro').attr('hidden', true);
    $('#selectCargoCadastro').attr('required', false);
    $('#divSelectCargoEditar').attr('hidden', false);
    $('#selectCargoEditar').attr('required', true);
    $('#divStatusCad').attr('hidden', false);
    $('#statusConfigCalculoComissao').attr('required', true);
    
    $('#btnSalvarCadastroConfigCalculoComissao').data('id', id);
    $('#nomeConfigCalculoComissao').val(nome);
    $('#selectEmpresaEditar').val(idEmpresa).trigger('change');
    $('#selectRegionalEditar').val(idRegional).trigger('change');
    $('#selectCargoEditar').val(idCargo).trigger('change');
    $('#statusConfigCalculoComissao').val(status).trigger('change');
    $('#temMeta').prop('checked', temMeta == 1 ? true : false).trigger('change');

    $('#consideraProduto').prop('checked', consideraProduto == 1 ? true : false);
    $('#consideraLicenca').prop('checked', consideraLicenca == 1 ? true : false);
    $('#temIndice').prop('checked', temIndice == 1 ? true : false).trigger('change');
    $('#idConfig').val(id);

    btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
    $('#modalCadConfigCalculoComissao').modal('show');
}

$(document).ready(function() {
    $(window).on('resize', function() {
        tabelaItensConfCalculoComissao.columns.adjust().draw();
        tabelaItensConfCalculoComissaoListagem.columns.adjust().draw();
        tabelaconfCalculoComissao.columns.adjust().draw();
        tabelaMetaListagem.columns.adjust().draw();
        tabelaIndiceListagem.columns.adjust().draw();
        tabelaItensDoIndiceListagem.columns.adjust().draw();
        tabelaItensdeMeta.columns.adjust().draw();
        tabelaIndices.columns.adjust().draw();
        tabelaItensIndice.columns.adjust().draw();
    })
});


$('#tab-dadosGerais').click(function (e){
    $('#btnFecharModalEditConf').css('margin-right', '0px')
	$('#dadosGerais').show();
	$('#itensDaConfig').hide();
    $('#divMeta').hide();
    $('#divIndice').hide();
    $('#btnSalvarCadastroConfigCalculoComissao').show();
});

$('#tab-itensDaConfig').click(function (e){
    $('#btnFecharModalEditConf').css('margin-right', '0px')
	$('#itensDaConfig').show();
	$('#dadosGerais').hide();
    $('#divMeta').hide();
    $('#divIndice').hide();
    $('#btnSalvarCadastroConfigCalculoComissao').show();
    atualizaTabelaItensConfCalculoComissao($('#idConfig').val());
});

$('#tab-meta').click(function (e){
    $('#btnFecharModalEditConf').css('margin-right', '15px')
    $('#divMeta').show();
    $('#dadosGerais').hide();
    $('#itensDaConfig').hide();
    $('#divIndice').hide();
    $('#btnSalvarCadastroConfigCalculoComissao').hide();
    tabelaMetaListagem.columns.adjust().draw();
    atualizaTabelaMetas($('#idConfig').val());
})

$('#tab-indice').click(function (e){
    $('#btnFecharModalEditConf').css('margin-right', '15px')
    $('#divMeta').hide();
    $('#dadosGerais').hide();
    $('#itensDaConfig').hide();
    $('#divIndice').show();
    $('#btnSalvarCadastroConfigCalculoComissao').hide();
    tabelaIndiceListagem.columns.adjust().draw();
    atualizaTabelaIndices($('#idConfig').val());
})

$('#valorPercentualItem').mask('##0,00%', {reverse: true});
$('#addValorPercentualItem').mask('##0,00%', {reverse: true});
$('#valorPercentualComissaoLicencaMeta').mask('##0,00%', {reverse: true});
$('#valorPercentualComissaoProdutoMeta').mask('##0,00%', {reverse: true});
$('#valorPercentualMaxMeta').mask('##0,00%', {reverse: true});
$('#valorPercentualMinMeta').mask('##0,00%', {reverse: true});
$('#valorPercentualComissaoLicencaMetaEdit').mask('##0,00%', {reverse: true});
$('#valorPercentualComissaoProdutoMetaEdit').mask('##0,00%', {reverse: true});
$('#valorPercentualMaxMetaEdit').mask('##0,00%', {reverse: true});
$('#valorPercentualMinMetaEdit').mask('##0,00%', {reverse: true});
$('#percentualLimiteSalarioIndice').mask('##0,00%', {reverse: true});
$('#metaMaxIndicadorIndice').mask('##0,00%', {reverse: true});
$('#metaMinIndicadorIndice').mask('##0,00%', {reverse: true});
$('#metaMaxIndicadorIndiceEdit').mask('##0,00%', {reverse: true});
$('#metaMinIndicadorIndiceEdit').mask('##0,00%', {reverse: true});
$('#percentualLimiteSalarioIndiceEdit').mask('##0,00%', {reverse: true});
$('#percentualMetaItemIndice').mask('##0,00%', {reverse: true});
$('#percentualSalarioItemIndice').mask('##0,00%', {reverse: true});
$('#percentualMetaItemIndiceEdit').mask('##0,00%', {reverse: true});
$('#percentualSalarioItemIndiceEdit').mask('##0,00%', {reverse: true});



function formatarMoeda(campo) {
        var elemento = document.getElementById(campo);
        var valor = elemento.value;

        valor = valor.toString().replace(/\D/g, '');
        valor = (parseFloat(valor) / 100).toFixed(2).toString();
        valor = valor.replace('.', ',');

        if (valor.length > 6) {
            valor = valor.replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
        }

        elemento.value = valor;
        if (valor == 'NaN') elemento.value = '';
    }

    function excluirItemTabela(botao, cenarioVenda, event){
        tabelaItensConfCalculoComissao.row(botao.parentNode.parentNode).remove().draw();
    }

    $('#adicionarItemTabela').click(function (){
        cenarioVenda = $('#cenarioDeVendaItem option:selected').text();
        tipoCalculo = $('#tipoCalculoItem option:selected').text();
        valorPercentual = ($('#valorPercentualItem').val()).replace(',', '.').replace('%', '');
        valorFixo = formatValorInserir($('#valorFixoItem').val());
        idCenarioVenda = $('#cenarioDeVendaItem option:selected').val();
        idTipoCalculo = $('#tipoCalculoItem option:selected').val();
        
        

        if (valorFixo === '' || valorPercentual === '' || cenarioVenda === '' || cenarioVenda === null) {
            alert('Preencha todos os campos')
        }else{
            tabelaItensConfCalculoComissao.rows.add([
                {cenarioVenda: cenarioVenda, tipoCalculo: tipoCalculo, valorPercentual: valorPercentual, valorFixo: valorFixo, idCenarioVenda: idCenarioVenda, idTipoCalculo: idTipoCalculo}
            ]).draw();
            
            $('#valorFixoItem').val('');
            $('#valorPercentualItem').val(0);
            $('#valorPercentualItem').attr('disabled', true);
            $('#valorFixoItem').attr('disabled', false);
            $('#tipoCalculoItem').val(1);
            $('#cenarioDeVendaItem').val('').trigger('change');
        }
    })

    async function abrirAddItemConfiguracao(event){
        let id = $('#idConfig').val();
        event.preventDefault();
        btn = $('.btnAbrirAdicionarItem');
        btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        $('#btnSalvarAddItemConfiguracao').data('id', id);

        let cenariosDeVendaAddItens  = await $.ajax ({
                                        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCenariosDeVendasSelect2') ?>',
                                        dataType: 'json',
                                        delay: 1000,
                                        type: 'GET',
                                        data: function (params) {
                                            return {
                                                q: params.term
                                            };
                                        },
                                        error: function(){
                                            alert('Erro ao buscar cenários de vendas, tente novamente');
                                            btn.attr('disabled', false).html('<i class="fa fa-plus" aria-hidden="true"></i>');
                                        }
                                    });
                       
        $('#addCenarioDeVendaItem').select2({
            data: cenariosDeVendaAddItens.results,
            placeholder: "Selecione o cenário de venda",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            minimumInputLength: 3,
            });
        
        $('#addCenarioDeVendaItem').on('select2:select', function (e) {
            var data = e.params.data;
        });

        $('#addValorPercentualItem').val(0);
        $('#divStatusItem').attr('hidden', true);
        $('#statusAddItemConf').attr('required', false);
        btn.attr('disabled', false).html('Adicionar item');
        $('#addCenarioDeVendaItem').append('<option value="" disabled selected>Selecione o cenário de venda</option>');
        $('#nome-modal-header').html('<?=lang("novo_item_configuracao_calculo_comissao")?>');
        $('#modalAddItemConfiguracao').modal('show');
    }

    $('#addItemConfiguracao').submit(function (e){
        e.preventDefault();
        var idItem = $('#btnSalvarAddItemConfiguracao').data('idItem');
        var idConfig = $('#btnSalvarAddItemConfiguracao').data('id');
        var idCenarioVenda = $('#addCenarioDeVendaItem').val();
        var tipoCalculo = $('#addTipoCalculoItem').val();
        var valorPercentual = ($('#addValorPercentualItem').val()).replace(',', '.').replace('%', '');
        var valorFixo = formatValorInserir($('#addValorFixoItem').val());
        var status = $('#statusAddItemConf').val();

        btn = $('#btnSalvarAddItemConfiguracao');

        if ($('#nome-modal-header').html() == '<?=lang("novo_item_configuracao_calculo_comissao")?>'){
            btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

                $.ajax({
                    url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/cadastrarItemConfCalculoComissao') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        idConfigCalculoComissao: idConfig,
                        idCenarioVenda: idCenarioVenda,
                        tipoCalculo: tipoCalculo,
                        valorPercentual: valorPercentual,
                        valorFixo: valorFixo
                    },
                    success: function(data){
                        if (data.status === 200){
                            alert(data.dados.mensagem)
                            $('#modalAddItemConfiguracao').modal('hide');
                            btn.attr('disabled', false).html('Salvar');
                        }else if (data.status === 400){
                            alert('Verifique os campos e tente novamente')
                            btn.attr('disabled', false).html('Salvar');
                        }else{
                            alert(data.dados.mensagem)
                            btn.attr('disabled', false).html('Salvar');
                        }
                    },
                    error: function(){
                        alert('Erro ao inserir item na configuração')
                        btn.attr('disabled', false).html('Salvar');
                    },
                    complete: function(){
                        atualizaTabelaItensConfCalculoComissao(idConfig);
                    }
                });
        }else{
            btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Editando...');

                $.ajax({
                    url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/editarItemConfCalculoComissao') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: idItem,
                        idConfigCalculoComissao: idConfig,
                        idCenarioVenda: idCenarioVenda,
                        tipoCalculo: tipoCalculo,
                        valorPercentual: valorPercentual,
                        valorFixo: valorFixo,
                        status: status
                    },
                    success: function(data){
                        if (data.status === 200){
                            alert(data.dados.mensagem)
                            atualizaTabelaItensConfCalculoComissao(idConfig);
                            $('#modalAddItemConfiguracao').modal('hide');
                            btn.attr('disabled', false).html('Salvar');
                        }else if (data.status === 400){
                            alert('Verifique os campos e tente novamente!')
                            btn.attr('disabled', false).html('Salvar');
                        }else{
                            alert(data.dados.mensagem)
                            btn.attr('disabled', false).html('Salvar');
                        }
                    },
                    error: function(){
                        alert('Erro ao editar item da configuração')
                        btn.attr('disabled', false).html('Salvar');
                    },
                    complete: function(){
                        atualizaTabelaItensConfCalculoComissao(idConfig)
                    }
                });
        }
    })

    function formatValorInserir(value) {
		value = value.replaceAll('.', '');
		value = value.replace(',', '.');

		return value;
		
	}

    document.addEventListener('keydown', function(event) {
        var key = event.which || event.keyCode;
        if (key === 13) {
          event.preventDefault();
        }
    });

    function alterarStatusItemConfig(botao, id, idConfig, status, event){
        event.preventDefault();
        btn = $(botao);
        if (status == 'Ativo'){
            status = 0
        }else{
            status = 1
        }
        if (confirm('Deseja realmente alterar o status deste item?')){
            btn.attr('disabled', true);
            btn.attr('class', 'btn');
            btn.html('<i class="fa fa-spinner fa-spin"></i>')

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/alterarStatusItemConfCalculoComissao') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    idItem: id,
                    status: status
                },
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        btn.attr('disabled', false);
                        btn.html('')
                        btn.attr('class', 'btn fa fa-exchange');
                        atualizaTabelaItensConfCalculoComissao(idConfig);
                    }else{
                        alert(data.dados.mensagem)
                        btn.attr('disabled', false);
                    }
                },
                error: function(){
                    alert('Erro ao alterar status do item')
                    btn.attr('disabled', false);
                }
            });
        }
    }

    function atualizaTabelaItensConfCalculoComissao(id){
        $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarItensConfCalculoComissaoIdConfig') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idConfig: id
            },
            beforeSend: function () {
                $('#tabelaItensConfCalculoComissaoListagem > tbody').html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="10" class="dataTables_empty"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></td>' +
                    '</tr>'
                );
            },
            success: function(data){
                if (data.status === 200){
                    tabelaItensConfCalculoComissaoListagem.clear().draw();
                    tabelaItensConfCalculoComissaoListagem.rows.add(data.results).draw();
                }else{
                    tabelaItensConfCalculoComissaoListagem.clear().draw();
                }
            },
            error: function(){
                tabelaItensConfCalculoComissaoListagem.clear().draw();
                alert('Erro ao listar itens da configuração. Tente novamente!')
            }
        });
    }

    async function abrirEditarItemConfiguracao(botao, id, idConfig, idCenarioVenda, tipoCalculo, valorPercentual, valorFixo, status, event){
        event.preventDefault();
        btn = $(botao);
        btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        if (status == 'Ativo'){
            status = 1;
        }else{
            status = 0;
        }
        let cenariosDeVendaEditItens  = await $.ajax ({
                                        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCenariosDeVendasSelect2') ?>',
                                        dataType: 'json',
                                        delay: 1000,
                                        type: 'GET',
                                        data: function (params) {
                                            return {
                                                q: params.term
                                            };
                                        },
                                        error: function(){
                                            alert('Erro ao buscar cenários de vendas, tente novamente');
                                            btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
                                        }
                                    });
                       
        $('#addCenarioDeVendaItem').select2({
            data: cenariosDeVendaEditItens.results,
            placeholder: "Selecione o cenário de venda",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            minimumInputLength: 3,
            });
        
        $('#addCenarioDeVendaItem').on('select2:select', function (e) {
            var data = e.params.data;
        });

        btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');

        if (tipoCalculo == 1){
            $('#addValorPercentualItem').attr('disabled', true);
            $('#addValorPercentualItem').val(0);
            $('#addValorFixoItem').attr('disabled', false);
        }else{
            $('#addValorFixoItem').attr('disabled', true);
            $('#addValorFixoItem').val(0);
            $('#addValorPercentualItem').attr('disabled', false);
        }

        if (valorFixo.includes('.') && !valorFixo.includes(',')){
            valorFixo = valorFixo.replace('.', ',');
        }
        $('#divStatusItem').attr('hidden', false);
        $('#statusAddItemConf').attr('required', true);
        $('#btnSalvarAddItemConfiguracao').data('id', idConfig);
        $('#btnSalvarAddItemConfiguracao').data('idItem', id);
        $('#addCenarioDeVendaItem').val(idCenarioVenda).trigger('change');
        $('#addTipoCalculoItem').val(tipoCalculo);
        $('#addValorPercentualItem').val(valorPercentual);
        $('#addValorFixoItem').val(valorFixo);
        $('#statusAddItemConf').val(status);
        $('#nome-modal-header').html('<?=lang("editar_item_configuracao_calculo_comissao")?>');
        $('#modalAddItemConfiguracao').modal('show');
    }

    $('#modalAddItemConfiguracao').on('hidden.bs.modal', function () {
        $('#btnSalvarAddItemConfiguracao').data('id', '');
        $('#btnSalvarAddItemConfiguracao').data('idItem', '');
        $('#addCenarioDeVendaItem').val('').trigger('change');
        $('#addTipoCalculoItem').val(1);
        $('#addValorPercentualItem').val(0);
        $('#addValorPercentualItem').attr('disabled', true);
        $('#addValorFixoItem').attr('disabled', false);
        $('#addValorFixoItem').val('');
    });

    $('#addTipoCalculoItem').change(function(){
        if ($(this).val() == 1){
            $('#addValorPercentualItem').attr('disabled', true);
            $('#addValorPercentualItem').val(0);
            $('#addValorFixoItem').attr('disabled', false);
            $('#addValorFixoItem').val('');
        }else{
            $('#addValorPercentualItem').attr('disabled', false);
            $('#addValorPercentualItem').val('');
            $('#addValorFixoItem').attr('disabled', true);
            $('#addValorFixoItem').val(0);
        }
    });

    $('#tipoCalculoItem').change(function(){
        if ($(this).val() == 1){
            $('#valorPercentualItem').attr('disabled', true);
            $('#valorPercentualItem').val(0);
            $('#valorFixoItem').attr('disabled', false);
            $('#valorFixoItem').val('');
        }else{
            $('#valorPercentualItem').attr('disabled', false);
            $('#valorPercentualItem').val('');
            $('#valorFixoItem').attr('disabled', true);
            $('#valorFixoItem').val(0);
        }
    });

    $('#temMeta').change(function(){
        if ($(this).is(':checked')){
            $('#temIndice').attr('disabled', false);
            $('#tab-itensDaConfig').hide();
            tabelaItensConfCalculoComissao.clear().draw();
            if ($('#nomeHeaderModal').html() == '<?=lang("editar_configuracao_calculo_comissao")?>'){
                $('#tab-meta').show();
            }
        }else{
            $('#temIndice').attr('disabled', true);
            $('#temIndice').prop('checked', false).trigger('change');
            $('#tab-itensDaConfig').show();
            $('#tab-meta').hide();
        }
    });

    $('#temIndice').change(function(){
        if ($(this).is(':checked')){
            if ($('#nomeHeaderModal').html() == '<?=lang("editar_configuracao_calculo_comissao")?>'){
                $('#tab-indice').show();
            }
        }else{
            $('#tab-indice').hide();
        }
    });

    let tabelaMetaListagem = $('#tabelaMetaListagem').DataTable({
		responsive: true,
		ordering: false,
		paging: true,
		searching: true,
		info: true,
		language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhuma meta adicionada",
            info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords:        "Nenhum resultado encontrado.",
            paginate: {
                first:          "Primeira",
                last:           "Última",
                next:           "Próxima",
                previous:       "Anterior"
            },
        },
		deferRender: true,
		lengthChange: false,
		columns: [
            { data: 'nomeCenarioVenda'},
            { data: 'percentualMin',
                render: function(data) {
                    return data + '%';
                }
            },
            { data: 'percentualMax',
                render: function(data) {
                    return data + '%';
                }
            },
            { data: 'percentualComissaoProduto',
                render: function(data) {
                    return data + '%';
                }
            },
            { data: 'percentualComissaoLicenca',
                render: function(data) {
                    return data + '%';
                }
            },
			{
				data: "metaProduto",
				render: function(data) {
					return parseFloat(data).toLocaleString('pt-BR', {
						style: 'currency',
						currency: 'BRL'
					});
				}
			},
			{
				data: "metaLicenca",
				render: function(data) {
					return parseFloat(data).toLocaleString('pt-BR', {
						style: 'currency',
						currency: 'BRL'
					});
				}
			},
            {
                data: "status"
            },
			{
				data:{ 'idCenarioVenda': 'idCenarioVenda' },
				orderable: false,
				render: function (data) {
					return `
					<button 
                        id="btnAbrirEditMeta"
						class="btn btn-primary"
						title="Editar Meta"
                        onClick="javascript:editarMeta(this, '${data['id']}', '${data['idConfigCalculoComissao']}', '${data['idCenarioVenda']}', '${data['percentualMin']}', '${data['percentualMax']}', '${data['percentualComissaoProduto']}', '${data['percentualComissaoLicenca']}', '${data['metaProduto']}','${data['metaLicenca']}', '${data['status']}', event)">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    <button 
                        id="btnAlterarStatusMeta"
						class="btn btn-primary"
						title="Alterar Status da Meta"
                        style= '${data['status'] == 'Ativo' ? 'background-color: red !important;' : 'background-color: green !important;'}'
                        onClick="javascript:alterarStatusMeta(this, '${data['id']}', '${data['idConfigCalculoComissao']}', '${data['status']}', event)">
                        <i class="fa fa-exchange" aria-hidden="true"></i>
                    </button>
					`;
				}
			}
		],
        dom: 'Bfrtip',
        buttons: [
            {
                text: '<?=lang("nova_meta")?>',
                className: 'btn btn-primary btnNovaMeta',
                action: function (e, dt, node, config) {
                    abrirAddMetaConfiguracao(e);
                }
            }
        ]
	});

    function atualizaTabelaMetas(idConfiguracao){
        $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarMetaPoridConfiguracao') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idConfiguracao: idConfiguracao
            },
            beforeSend: function () {
                $('#tabelaMetaListagem > tbody').html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="10" class="dataTables_empty"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></td>' +
                    '</tr>'
                );
            },
            success: function(data){
                if (data.status === 200){
                    tabelaMetaListagem.clear().draw();
                    tabelaMetaListagem.rows.add(data.results).draw();
                    tabelaMetaListagem.columns.adjust().draw();
                }else{
                    tabelaMetaListagem.clear().draw();
                }
            },
            error: function(){
                tabelaMetaListagem.clear().draw();
                alert('Erro ao listar metas. Tente novamente!')
            }
        });
    }

    async function abrirAddMetaConfiguracao(e){
        e.preventDefault();
        let idConfiguracao = $('#idConfig').val();
        let botao = $('.btnNovaMeta');
        botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        let cenariosDeVendaCadMeta  = await $.ajax ({
                                    url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCenariosDeVendasSelect2') ?>',
                                    dataType: 'json',
                                    delay: 1000,
                                    type: 'GET',
                                    data: function (params) {
                                        return {
                                            q: params.term
                                        };
                                    },
                                    error: function(){
                                        alert('Erro ao buscar cenários de vendas, tente novamente');
                                        btn.attr('disabled', false).html('Nova Meta');
                                    }
                                });
                       
        $('#cenarioDeVendaMeta').select2({
            data: cenariosDeVendaCadMeta.results,
            placeholder: "Selecione o cenário de venda",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            });
        
        $('#cenarioDeVendaMeta').on('select2:select', function (e) {
            var data = e.params.data;
        });

        botao.attr('disabled', false).html('Nova Meta');

        $('#cenarioDeVendaMeta').append('<option value="" disabled selected>Selecione o cenário de venda</option>');
        $('#btnSalvarMeta').data('idConf', idConfiguracao);
        $('#modalCadastroMeta').modal('show');
    }

    let tabelaItensdeMeta = $('#tabelaItensdeMeta').DataTable({
		responsive: true,
		ordering: false,
		paging: true,
		searching: true,
		info: true,
		language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhuma meta adicionada",
            info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords:        "Nenhum resultado encontrado.",
            paginate: {
                first:          "Primeira",
                last:           "Última",
                next:           "Próxima",
                previous:       "Anterior"
            },
        },
		deferRender: true,
		lengthChange: false,
		columns: [
            { data: 'idCenarioVenda',
                visible: false
            },
            {
                data: 'cenarioVenda'
            },
            { data: 'percentualMin',
                render: function(data) {
                    return data + '%';
                }
            },
            { data: 'percentualMax',
                render: function(data) {
                    return data + '%';
                }
            },
            { data: 'percentualComissaoProduto',
                render: function(data) {
                    return data + '%';
                }
            },
            { data: 'percentualComissaoLicenca',
                render: function(data) {
                    return data + '%';
                }
            },
			{
				data: "metaProduto",
				render: function(data) {
					return parseFloat(data).toLocaleString('pt-BR', {
						style: 'currency',
						currency: 'BRL'
					});
				}
			},
			{
				data: "metaLicenca",
				render: function(data) {
					return parseFloat(data).toLocaleString('pt-BR', {
						style: 'currency',
						currency: 'BRL'
					});
				}
			},
			{
				data:{ 'idCenarioVenda': 'idCenarioVenda' },
				orderable: false,
				render: function (data) {
					return `
					<button 
                        id="btnExcluirItemTabelaMeta"
						class="btn fa fa-trash"
						title="Excluir Item"
						style="width: 38px; height: 34px;margin: 0 auto; background-color: red; color: white;"
                        onClick="javascript:excluirItemTabelaMeta(this,event)">
					`;
				}
			}
		]
	});

    $('#btnAdicionarMetaTabela').click( function (e) {
        e.preventDefault();
        let idConfiguracao = $('#btnSalvarMeta').data('idConf');
        let idCenarioVenda = $('#cenarioDeVendaMeta').val();
        let percentualMin = ($('#valorPercentualMinMeta').val()).replace(',', '.').replace('%', '');
        let percentualMax = ($('#valorPercentualMaxMeta').val()).replace(',', '.').replace('%', '');
        let percentualComissaoProduto = ($('#valorPercentualComissaoProdutoMeta').val()).replace(',', '.').replace('%', '');
        let percentualComissaoLicenca = ($('#valorPercentualComissaoLicencaMeta').val()).replace(',', '.').replace('%', '');
        let metaProduto = formatValorInserir($('#valorMetaProduto').val());
        let metaLicenca = formatValorInserir($('#valorMetaLicenca').val());
        let cenarioVenda = $('#cenarioDeVendaMeta option:selected').text();

        
        if (percentualMin === '' || percentualMax === '' || percentualComissaoProduto === '' || percentualComissaoLicenca === '' || metaProduto === '' || metaLicenca === '' || idCenarioVenda === null){
            alert('Preencha todos os campos.')
        }else if (parseFloat(percentualMin) > parseFloat(percentualMax)){
            alert('Percentual mínimo não pode ser maior que percentual máximo.');
        }else{
            tabelaItensdeMeta.rows.add([
                {idCenarioVenda: idCenarioVenda, cenarioVenda: cenarioVenda, percentualMin: percentualMin, percentualMax: percentualMax, percentualComissaoProduto: percentualComissaoProduto, percentualComissaoLicenca: percentualComissaoLicenca, metaProduto: metaProduto, metaLicenca: metaLicenca}
            ]).draw();
            tabelaItensdeMeta.columns.adjust().draw();

            $('#valorPercentualMinMeta').val('');
            $('#valorPercentualMaxMeta').val('');
            $('#valorPercentualComissaoProdutoMeta').val('');
            $('#valorPercentualComissaoLicencaMeta').val('');
            $('#valorMetaProduto').val('');
            $('#valorMetaLicenca').val('');
            $('#cenarioDeVendaMeta').val('').trigger('change');
        }
    });

    function excluirItemTabelaMeta(botao, event){
        tabelaItensdeMeta.row(botao.parentNode.parentNode).remove().draw();
    }

    $('#btnSalvarMeta').click(function (e){
        e.preventDefault();
        let idConfiguracao = $('#btnSalvarMeta').data('idConf');
        let itens = tabelaItensdeMeta.rows().data().toArray();
        let itensMeta = [];
        let btn = $(this);

        if (itens.length == 0){
            alert('Adicione, pelo menos, um item na tabela.')
        }else{
            btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

            itens.forEach(function (item){
                itensMeta.push({
                    idCenarioVenda: item.idCenarioVenda,
                    percentualMin: item.percentualMin,
                    percentualMax: item.percentualMax,
                    percentualComissaoProduto: item.percentualComissaoProduto,
                    percentualComissaoLicenca: item.percentualComissaoLicenca,
                    metaProduto: item.metaProduto,
                    metaLicenca: item.metaLicenca
                });
            });

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/cadastrarMetas') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    idConfigCalculoComissao: idConfiguracao,
                    metas: itensMeta
                },
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        $('#modalCadastroMeta').modal('hide');
                        atualizaTabelaMetas(idConfiguracao);
                    }else if (data.status === 400){
                        alert(data.dados.errors[0] ?? 'Verifique os campos e tente novamente!')
                    }else if (data.status === 404){
                        alert(data.dados.mensagem ?? 'Não foi possível cadastrar meta. Verifique os campos e tente novamente.')
                    }else{
                        alert(data.dados.mensagem ?? 'Não foi possível cadastrar meta. Tente novamente.')
                    }
                },
                error: function(){
                    alert('Erro ao cadastrar meta. Tente novamente!')
                    btn.attr('disabled', false).html('Salvar Meta(s)');
                },
                complete: function(){
                    btn.attr('disabled', false).html('Salvar Meta(s)');
                }
            });
        }
    });

    $('#modalCadastroMeta').on('hidden.bs.modal', function (e) {
        $('body').addClass('modal-open');
        $('#modalCadConfigCalculoComissao').modal('handleUpdate');
        $('#modalCadConfigCalculoComissao').focus();
        $('#formCadastrarMeta').trigger('reset');
        tabelaItensdeMeta.clear().draw();
    });

    $('#modalEditMeta').on('hidden.bs.modal', function (e) {
        $('body').addClass('modal-open');
        $('#modalCadConfigCalculoComissao').modal('handleUpdate');
        $('#modalCadConfigCalculoComissao').focus();
        $('#formEditarMeta').trigger('reset');
    });

    $('#modalCadastroIndice').on('hidden.bs.modal', function (e) {
        $('body').addClass('modal-open');
        $('#modalCadConfigCalculoComissao').modal('handleUpdate');
        $('#modalCadConfigCalculoComissao').focus();
        $('#formCadastrarIndice').trigger('reset');
        tabelaIndices.clear().draw();
    });

    $('#modalCadastroItemIndice').on('hidden.bs.modal', function (e) {
        $('body').addClass('modal-open');
        $('#modalCadConfigCalculoComissao').modal('handleUpdate');
        $('#modalCadConfigCalculoComissao').focus();
        $('#formCadastrarItemIndice').trigger('reset');
        tabelaItensIndice.clear().draw();
    });

    $('#modalItensDoIndice').on('hidden.bs.modal', function (e) {
        $('body').addClass('modal-open');
        $('#modalCadConfigCalculoComissao').modal('handleUpdate');
        $('#modalCadConfigCalculoComissao').focus();
        tabelaItensDoIndiceListagem.clear().draw();
    });

    $('#modalEditIndice').on('hidden.bs.modal', function (e) {
        $('body').addClass('modal-open');
        $('#modalCadConfigCalculoComissao').modal('handleUpdate');
        $('#modalCadConfigCalculoComissao').focus();
        $('#formEditarIndice').trigger('reset');
    });

    $('#modalEditItemIndice').on('hidden.bs.modal', function (e) {
        $('body').addClass('modal-open');
        $('#modalItensDoIndice').modal('handleUpdate');
        $('#modalItensDoIndice').focus();
        $('#formEditarItemIndice').trigger('reset');
    });



    async function editarMeta(botao, id, idConfig, idCenarioVenda, percentualMin, percentualMax, percentualComissaoProduto, percentualComissaoLicenca, metaProduto, metaLicenca, status, event){
        event.preventDefault();
        btn = $(botao);
        btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        if (status == 'Ativo'){
            status = 1;
        }else{
            status = 0;
        }

        let cenariosDeVendaEditMeta  = await $.ajax ({
                                    url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCenariosDeVendasSelect2') ?>',
                                    dataType: 'json',
                                    delay: 1000,
                                    type: 'GET',
                                    data: function (params) {
                                        return {
                                            q: params.term
                                        };
                                    },
                                    error: function(){
                                        alert('Erro ao buscar cenários de vendas, tente novamente');
                                        btn.attr('disabled', false).html('Nova Configuração');
                                    }
                                });
                       
        $('#cenarioDeVendaMetaEdit').select2({
            data: cenariosDeVendaEditMeta.results,
            placeholder: "Selecione o cenário de venda",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            });
        
        $('#cenarioDeVendaMetaEdit').on('select2:select', function (e) {
            var data = e.params.data;
        });

        if (metaProduto.includes('.') && !metaProduto.includes(',')){
            metaProduto = metaProduto.replace('.', ',');
        }
        if (metaLicenca.includes('.') && !metaLicenca.includes(',')){
            metaLicenca = metaLicenca.replace('.', ',');
        }
        
        $('#btnEditarMeta').data('idMeta', id);
        $('#btnEditarMeta').data('idConfig', idConfig);
        $('#btnEditarMeta').data('metaStatus', status);

        $('#cenarioDeVendaMetaEdit').val(idCenarioVenda ? idCenarioVenda : $('#cenarioDeVendaMetaEdit').append('<option value="" disabled selected>Selecione o cenário de venda</option>')).trigger('change');
        $('#valorPercentualMinMetaEdit').val(percentualMin + '%');
        $('#valorPercentualMaxMetaEdit').val(percentualMax + '%');
        $('#valorPercentualComissaoProdutoMetaEdit').val(percentualComissaoProduto + '%');
        $('#valorPercentualComissaoLicencaMetaEdit').val(percentualComissaoLicenca + '%');
        $('#valorMetaProdutoEdit').val(metaProduto);
        $('#valorMetaLicencaEdit').val(metaLicenca);

        $('#modalEditMeta').modal('show');
        btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
    }

    $('#formEditarMeta').submit(function (e){
        e.preventDefault();
        let idMeta = $('#btnEditarMeta').data('idMeta');
        let idConfiguracao = $('#btnEditarMeta').data('idConfig');
        let idCenarioVenda = $('#cenarioDeVendaMetaEdit').val();
        let percentualMin = ($('#valorPercentualMinMetaEdit').val()).replace(',', '.').replace('%', '');
        let percentualMax = ($('#valorPercentualMaxMetaEdit').val()).replace(',', '.').replace('%', '');
        let percentualComissaoProduto = ($('#valorPercentualComissaoProdutoMetaEdit').val()).replace(',', '.').replace('%', '');
        let percentualComissaoLicenca = ($('#valorPercentualComissaoLicencaMetaEdit').val()).replace(',', '.').replace('%', '');
        let metaProduto = formatValorInserir($('#valorMetaProdutoEdit').val());
        let metaLicenca = formatValorInserir($('#valorMetaLicencaEdit').val());
        let status = $('#btnEditarMeta').data('metaStatus');

        if (parseFloat(percentualMin) > parseFloat(percentualMax)){
            alert('Percentual mínimo não pode ser maior que percentual máximo.');
        }else{
            btn = $('#btnEditarMeta');
            btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Editando...');

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/editarMeta') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: idMeta,
                    idConfigCalculoComissao: idConfiguracao,
                    idCenarioVenda: idCenarioVenda,
                    percentualMin: percentualMin,
                    percentualMax: percentualMax,
                    percentualComissaoProduto: percentualComissaoProduto,
                    percentualComissaoLicenca: percentualComissaoLicenca,
                    metaProduto: metaProduto,
                    metaLicenca: metaLicenca,
                    status: status
                },
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        $('#modalEditMeta').modal('hide');
                        atualizaTabelaMetas(idConfiguracao);
                    }else if (data.status === 400){
                        alert(data.dados.mensagem ?? 'Verifique os campos e tente novamente!')
                    }else if (data.status === 404){
                        alert(data.dados.mensagem ?? 'Não foi possível editar meta. Verifique os campos e tente novamente.')
                    }else{
                        alert(data.dados.mensagem ?? 'Não foi possível editar meta. Tente novamente.')
                    }
                },
                error: function(){
                    alert('Erro ao editar meta. Tente novamente!')
                    btn.attr('disabled', false).html('Editar');
                },
                complete: function(){
                    btn.attr('disabled', false).html('Editar');
                }
            });
        }
    });

    function alterarStatusMeta(botao, id, idConfigCalculoComissao, status, event){
        event.preventDefault();
        btn = $(botao);
        if (status == 'Ativo'){
            status = 0
        }else{
            status = 1
        }
        if (confirm('Deseja realmente alterar o status desta meta?')){
            btn.attr('disabled', true);
            btn.html('<i class="fa fa-spinner fa-spin"></i>')

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/alterarStatusMeta') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    status: status
                },
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        atualizaTabelaMetas(idConfigCalculoComissao);
                    }else{
                        alert(data.dados.mensagem ?? "Não foi possível alterar o status da meta. Tente novamente!")
                    }
                },
                error: function(){
                    alert('Erro ao alterar status da meta. Tente novamente.')
                    btn.attr('disabled', false);
                },
                complete: function(){
                    btn.attr('disabled', false).html('<i class="fa fa-exchange" aria-hidden="true"></i>');
                }
            });
        }
    }

    let tabelaIndiceListagem = $('#tabelaIndiceListagem').DataTable({
		responsive: true,
		ordering: false,
		paging: true,
		searching: true,
		info: true,
		language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum índice adicionado",
            info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords:        "Nenhum resultado encontrado.",
            paginate: {
                first:          "Primeira",
                last:           "Última",
                next:           "Próxima",
                previous:       "Anterior"
            },
        },
		deferRender: true,
		lengthChange: false,
		columns: [
            {   data: 'nomeCenarioVenda'},
            {   data: 'tipoIndicador',
                render: function(data) {
                    var indicadorDescricao = {
                        0: 'Usa quantidade de equipamento',
                        1: 'Usa Receita de licença de uso',
                        2: 'Usa receita de equipamento e taxas de instalação'
                    };

                    return indicadorDescricao[data] ?? '-';
                }
            },
            {
				data: "metaMinIndicador",
                render: function(data) {
                    return data + '%';
                }
			},
            {
				data: "metaMaxIndicador",
                render: function(data) {
                    return data + '%';
                }
			},
            {   data: 'percentualLimiteSalario',
                  render: function(data) {
                      return data + '%';
                  }
            },   
            {   data: 'periodicidade',
                render: function(data) {
                    return data == 0 ? 'Mensal' : 'Trimestral';
                }
            },
            {   data: 'metaQuantidade'},
			{
				data: "metaValor",
				render: function(data) {
					return parseFloat(data).toLocaleString('pt-BR', {
						style: 'currency',
						currency: 'BRL'
					});
				}
			},
            {   data: "status"},
			{
				data:{ 'idCenarioVenda': 'idCenarioVenda' },
				orderable: false,
				render: function (data) {
					return `
					<button 
                        id="btnAbrirEditIndice"
						class="btn btn-primary"
						title="Editar Índice"
                        onClick="javascript:editarIndice(this, '${data['id']}', '${data['idConfigCalculoComissao']}', '${data['idCenarioVenda']}', '${data['tipoIndicador']}', '${data['metaMinIndicador']}', '${data['metaMaxIndicador']}', '${data['percentualLimiteSalario']}', '${data['periodicidade']}','${data['metaQuantidade']}', '${data['metaValor']}','${data['status']}', event)">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    <button 
                        id="btnAlterarStatusIndice"
						class="btn btn-primary"
						title="Alterar Status do Índice"
                        style= '${data['status'] == 'Ativo' ? 'background-color: red !important;' : 'background-color: green !important;'}'
                        onClick="javascript:alterarStatusIndice(this, '${data['id']}', '${data['idConfigCalculoComissao']}', '${data['status']}', event)">
                        <i class="fa fa-exchange" aria-hidden="true"></i>
                    </button>
                    <button 
                        id="btnAddItemIndice"
						class="btn btn-primary"
						title="Adicionar Item"
                        onClick="javascript:abrirModalAddItemIndice(this, '${data['id']}', '${data['idConfigCalculoComissao']}', event)">
                        <i class="fa fa-plus aria-hidden="true"></i>
                    </button>
                    <button 
                        id="btnVisualizarItensIndice"
                        class="btn btn-primary"
                        title="Visualizar Itens"
                        onClick="javascript:abrirModalVisualizarItensIndice(this, '${data['id']}', '${data['idConfigCalculoComissao']}', event)">
                        <i class="fa fa-list aria-hidden="true"></i>
                    </button>    
					`;
				}
			}
		],
        dom: 'Bfrtip',
        buttons: [
            {
                text: '<?=lang("novo_indice")?>',
                className: 'btn btn-primary btnNovoIndice',
                action: function (e, dt, node, config) {
                    abrirAddIndiceConfiguracao(e);
                }
            }
        ]
	});

    function atualizaTabelaIndices(idConfiguracao){
        $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarIndicePoridConfiguracao') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idConfiguracao: idConfiguracao
            },
            beforeSend: function () {
                $('#tabelaIndiceListagem > tbody').html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="10" class="dataTables_empty"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></td>' +
                    '</tr>'
                );
            },
            success: function(data){
                if (data.status === 200){
                    tabelaIndiceListagem.clear().draw();
                    tabelaIndiceListagem.rows.add(data.results).draw();
                    tabelaIndiceListagem.columns.adjust().draw();
                }else{
                    tabelaIndiceListagem.clear().draw();
                }
            },
            error: function(){
                tabelaIndiceListagem.clear().draw();
                alert('Erro ao listar índices. Tente novamente!')
            }
        });
    }

    async function abrirAddIndiceConfiguracao(e){
        e.preventDefault();
        let idConfiguracao = $('#idConfig').val();
        let botao = $('.btnNovoIndice');
        botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        let cenariosDeVendaCadIndice  = await $.ajax ({
                                    url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCenariosDeVendasSelect2') ?>',
                                    dataType: 'json',
                                    delay: 1000,
                                    type: 'GET',
                                    data: function (params) {
                                        return {
                                            q: params.term
                                        };
                                    },
                                    error: function(){
                                        alert('Erro ao buscar cenários de vendas, tente novamente');
                                        btn.attr('disabled', false).html('Novo Índice');
                                    }
                                });
                       
        $('#cenarioDeVendaIndice').select2({
            data: cenariosDeVendaCadIndice.results,
            placeholder: "Selecione o cenário de venda",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            });
        
        $('#cenarioDeVendaIndice').on('select2:select', function (e) {
            var data = e.params.data;
        });

        botao.attr('disabled', false).html('<?=lang("novo_indice")?>');

        $('#cenarioDeVendaIndice').append('<option value="" disabled selected>Selecione o cenário de venda</option>');
        $('#btnSalvarIndice').data('idConf', idConfiguracao);
        $('#modalCadastroIndice').modal('show');
    }
    
    let tabelaIndices = $('#tabelaIndices').DataTable({
		responsive: true,
		ordering: false,
		paging: true,
		searching: true,
		info: true,
		language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum índice adicionado",
            info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords:        "Nenhum resultado encontrado.",
            paginate: {
                first:          "Primeira",
                last:           "Última",
                next:           "Próxima",
                previous:       "Anterior"
            },
        },
		deferRender: true,
		lengthChange: false,
		columns: [
            { data: 'idCenarioVenda',
                visible: false
            },
            {
                data: 'cenarioVenda'
            },
            {
                data: 'idTipoIndicador',
                visible: false
            },
            {
                data: 'tipoIndicador'
            },
            { data: 'metaMinIndicador',
                render: function(data) {
                    return data + '%';
                }
            },
            { data: 'metaMaxIndicador',
                render: function(data) {
                    return data + '%';
                }
            },
            { data: 'percentualLimiteSalario',
                render: function(data) {
                    return data + '%';
                }
            },
            {
                data: 'idPeriodicidade',
                visible: false
            },
            {
                data: 'periodicidade'
            },
            {
                data: 'metaQuantidade'
            },
			{
				data: "metaValor",
				render: function(data) {
					return parseFloat(data).toLocaleString('pt-BR', {
						style: 'currency',
						currency: 'BRL'
					});
				}
			},
			{
				data:{ 'idCenarioVenda': 'idCenarioVenda' },
				orderable: false,
				render: function (data) {
					return `
					<button 
                        id="btnExcluirItemTabelaIndice"
						class="btn fa fa-trash"
						title="Excluir Item"
						style="width: 38px; height: 34px;margin: 0 auto; background-color: red; color: white;"
                        onClick="javascript:excluirItemTabelaIndice(this,event)">
					`;
				}
			}
		]
	});
    
    function excluirItemTabelaIndice(botao, event){
        tabelaIndices.row(botao.parentNode.parentNode).remove().draw();
    }

    $('#btnAdicionarIndiceTabela').click( function (e) {
        e.preventDefault();
        let idConfiguracao = $('#btnSalvarIndice').data('idConf');
        let idCenarioVenda = $('#cenarioDeVendaIndice').val();
        let idTipoIndicador = $('#tipoIndicadornIndice').val();
        let metaMinIndicador = ($('#metaMinIndicadorIndice').val()).replace(',', '.').replace('%', '');
        let metaMaxIndicador = ($('#metaMaxIndicadorIndice').val()).replace(',', '.').replace('%', '');
        let percentualLimiteSalario = ($('#percentualLimiteSalarioIndice').val()).replace(',', '.').replace('%', '');
        let idPeriodicidade = $('#periodicidadeIndice').val();
        let metaQuantidade = $('#metaQuantidadeIndice').val();
        let metaValor = formatValorInserir($('#metaValorIndice').val());
        let cenarioVenda = $('#cenarioDeVendaIndice option:selected').text();
        let tipoIndicadorDescricao = $('#tipoIndicadornIndice option:selected').text();
        let periodicidadeDescricao = $('#periodicidadeIndice option:selected').text();

        if (metaMinIndicador === '' || metaMaxIndicador === '' || percentualLimiteSalario === '' || metaQuantidade === '' || metaValor === '' || idCenarioVenda === null || idTipoIndicador === null || idPeriodicidade === null){
            alert('Preencha todos os campos.')
        }else if (parseFloat(metaMinIndicador) > parseFloat(metaMaxIndicador)){
            alert('Meta mínima não pode ser maior que meta máxima.');
        }else{
            tabelaIndices.rows.add([
                {idCenarioVenda: idCenarioVenda, cenarioVenda: cenarioVenda, idTipoIndicador: idTipoIndicador, tipoIndicador: tipoIndicadorDescricao, metaMinIndicador: metaMinIndicador, metaMaxIndicador: metaMaxIndicador, percentualLimiteSalario: percentualLimiteSalario, idPeriodicidade: idPeriodicidade, periodicidade: periodicidadeDescricao, metaQuantidade: metaQuantidade, metaValor: metaValor}
            ]).draw();
            tabelaIndices.columns.adjust().draw();
            
            $('#metaMinIndicadorIndice').val('');
            $('#metaMaxIndicadorIndice').val('');
            $('#percentualLimiteSalarioIndice').val('');
            $('#metaQuantidadeIndice').val('');
            $('#metaValorIndice').val('');
            $('#cenarioDeVendaIndice').val('').trigger('change');
            $('#tipoIndicadornIndice').val('').trigger('change');
            $('#periodicidadeIndice').val('').trigger('change');
        }
    });

    $('#btnSalvarIndice').click(function (e){
        e.preventDefault();
        let idConfiguracao = $('#btnSalvarIndice').data('idConf');
        let itens = tabelaIndices.rows().data().toArray();
        let itensIndice = [];
        let btn = $(this);

        if (itens.length == 0){
            alert('Adicione, pelo menos, um índice na tabela.')
            return false;
        }else{
            btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

            itens.forEach(function (item){
                itensIndice.push({
                    idCenarioVenda: item.idCenarioVenda,
                    tipoIndicador: item.idTipoIndicador,
                    metaMinIndicador: item.metaMinIndicador,
                    metaMaxIndicador: item.metaMaxIndicador,
                    percentualLimiteSalario: item.percentualLimiteSalario,
                    periodicidade: item.idPeriodicidade,
                    metaQuantidade: item.metaQuantidade,
                    metaValor: item.metaValor
                });
            });

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/cadastrarIndices') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    idConfigCalculoComissao: idConfiguracao,
                    indices: itensIndice
                },
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        $('#modalCadastroIndice').modal('hide');
                        atualizaTabelaIndices(idConfiguracao);
                    }else if (data.status === 400){
                        alert(data.dados.errors[0] ?? 'Verifique os campos e tente novamente!')
                    }else if (data.status === 404){
                        alert(data.dados.mensagem ?? 'Não foi possível cadastrar índice. Verifique os campos e tente novamente.')
                    }else{
                        alert(data.dados.mensagem ?? 'Não foi possível cadastrar índice. Tente novamente.')
                    }
                },
                error: function(){
                    alert('Erro ao cadastrar índice. Tente novamente!')
                    btn.attr('disabled', false).html('Salvar Índice(s)');
                },
                complete: function(){
                    btn.attr('disabled', false).html('Salvar Índice(s)');
                }
            });
        }
    });

    async function editarIndice(botao, id, idConfig, idCenarioVenda, idTipoIndicador, metaMinIndicador, metaMaxIndicador, percentualLimiteSalario, idPeriodicidade, metaQuantidade, metaValor, status, event){
        event.preventDefault();
        btn = $(botao);
        btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        if (status == 'Ativo'){
            status = 1;
        }else{
            status = 0;
        }

        let cenariosDeVendaEditIndice  = await $.ajax ({
                                    url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCenariosDeVendasSelect2') ?>',
                                    dataType: 'json',
                                    delay: 1000,
                                    type: 'GET',
                                    data: function (params) {
                                        return {
                                            q: params.term
                                        };
                                    },
                                    error: function(){
                                        alert('Erro ao buscar cenários de vendas, tente novamente');
                                        btn.attr('disabled', false).html('Novo Índice');
                                    }
                                });
                       
        $('#cenarioDeVendaIndiceEdit').select2({
            data: cenariosDeVendaEditIndice.results,
            placeholder: "Selecione o cenário de venda",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            });
        
        $('#cenarioDeVendaIndiceEdit').on('select2:select', function (e) {
            var data = e.params.data;
        });

        if (metaValor.includes('.') && !metaValor.includes(',')){
            metaValor = metaValor.replace('.', ',');
        }
        
        $('#btnEditarIndice').data('idIndice', id);
        $('#btnEditarIndice').data('idConfig', idConfig);
        $('#btnEditarIndice').data('indiceStatus', status);

        $('#cenarioDeVendaIndiceEdit').val(idCenarioVenda ? idCenarioVenda : $('#cenarioDeVendaIndiceEdit').append('<option value="" disabled selected>Selecione o cenário de venda</option>')).trigger('change');
        $('#tipoIndicadornIndiceEdit').val(idTipoIndicador ? idTipoIndicador : '');
        $('#metaMinIndicadorIndiceEdit').val(metaMinIndicador + '%');
        $('#metaMaxIndicadorIndiceEdit').val(metaMaxIndicador + '%');
        $('#percentualLimiteSalarioIndiceEdit').val(percentualLimiteSalario + '%');
        $('#periodicidadeIndiceEdit').val(idPeriodicidade ? idPeriodicidade : $('#periodicidadeIndiceEdit').val('')).trigger('change');
        $('#metaQuantidadeIndiceEdit').val(metaQuantidade);
        $('#metaValorIndiceEdit').val(metaValor);

        $('#modalEditIndice').modal('show');
        btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
    }

    $('#formEditarIndice').submit(function (e){
        e.preventDefault();
        let idIndice = $('#btnEditarIndice').data('idIndice');
        let idConfiguracao = $('#btnEditarIndice').data('idConfig');
        let idCenarioVenda = $('#cenarioDeVendaIndiceEdit').val();
        let idTipoIndicador = $('#tipoIndicadornIndiceEdit').val();
        let metaMinIndicadorEdit = ($('#metaMinIndicadorIndiceEdit').val()).replace(',', '.').replace('%', '');
        let metaMaxIndicadorEdit = ($('#metaMaxIndicadorIndiceEdit').val()).replace(',', '.').replace('%', '');
        let percentualLimiteSalario = ($('#percentualLimiteSalarioIndiceEdit').val()).replace(',', '.').replace('%', '');
        let idPeriodicidade = $('#periodicidadeIndiceEdit').val();
        let metaQuantidade = $('#metaQuantidadeIndiceEdit').val();
        let metaValor = formatValorInserir($('#metaValorIndiceEdit').val());
        let status = $('#btnEditarIndice').data('indiceStatus');

        if (parseFloat(metaMinIndicadorEdit) > parseFloat(metaMaxIndicadorEdit)){
            alert('Meta mínima não pode ser maior que meta máxima.');
        }else{
            btn = $('#btnEditarIndice');
            btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Editando...');

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/editarIndice') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: idIndice,
                    idConfigCalculoComissao: idConfiguracao,
                    idCenarioVenda: idCenarioVenda,
                    tipoIndicador: idTipoIndicador,
                    metaMinIndicador: metaMinIndicadorEdit,
                    metaMaxIndicador: metaMaxIndicadorEdit,
                    percentualLimiteSalario: percentualLimiteSalario,
                    periodicidade: idPeriodicidade,
                    metaQuantidade: metaQuantidade,
                    metaValor: metaValor,
                    status: status
                },
                success: function(data){
                    if (data.status === 200 || data.status === 201){
                        alert(data.dados.mensagem);
                        $('#modalEditIndice').modal('hide');
                        atualizaTabelaIndices(idConfiguracao);
                    }else if (data.status === 500){
                        alert('Não foi possível editar índice. Tente novamente.');
                    }else{
                        alert(data.dados.mensagem ?? data.dados.errors[0] ?? 'Não foi possível editar índice. Verifique os campos e tente novamente.');
                    }
                },
                error: function(){
                    alert('Erro ao editar índice. Tente novamente!')
                    btn.attr('disabled', false).html('Editar');
                },
                complete: function(){
                    btn.attr('disabled', false).html('Editar');
                }
            });
        }
    });

    function alterarStatusIndice(botao, id, idConfigCalculoComissao, status, event){
        event.preventDefault();
        btn = $(botao);
        if (status == 'Ativo'){
            status = 0
        }else{
            status = 1
        }
        if (confirm('Deseja realmente alterar o status deste índice?')){
            btn.attr('disabled', true);
            btn.html('<i class="fa fa-spinner fa-spin"></i>')

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/alterarStatusIndice') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    status: status
                },
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        atualizaTabelaIndices(idConfigCalculoComissao);
                    }else{
                        alert(data.dados.mensagem ?? "Não foi possível alterar o status do índice. Tente novamente!")
                    }
                },
                error: function(){
                    alert('Erro ao alterar status do índice. Tente novamente.')
                    btn.attr('disabled', false);
                },
                complete: function(){
                    btn.attr('disabled', false).html('<i class="fa fa-exchange" aria-hidden="true"></i>');
                }
            });
        }
    }

    function abrirModalAddItemIndice(botao, idIndice, idConfigCalculoComissao, event){
        event.preventDefault();
        btn = $(botao);
        btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $('#btnSalvarItemIndice').data('idIndice', idIndice);

        $('#modalCadastroItemIndice').modal('show');
        btn.attr('disabled', false).html('<i class="fa fa-plus" aria-hidden="true"></i>');
    }

    let tabelaItensIndice = $('#tabelaItensIndice').DataTable({
		responsive: true,
		ordering: false,
		paging: true,
		searching: true,
		info: true,
		language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum item adicionado",
            info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords:        "Nenhum resultado encontrado.",
            paginate: {
                first:          "Primeira",
                last:           "Última",
                next:           "Próxima",
                previous:       "Anterior"
            },
        },
		deferRender: true,
		lengthChange: false,
		columns: [
            { data: 'idTipoIndicador',
                visible: false
            },
            {
                data: 'tipoIndicador'
            },
            { data: 'percMeta',
                render: function(data) {
                    return data + '%';
                }
            },
            { data: 'percSalario',
                render: function(data) {
                    return data + '%';
                }
            },
			{
				data:{ 'id': 'id' },
				orderable: false,
				render: function (data) {
					return `
					<button 
                        id="btnExcluirItemTabelaItemIndice"
						class="btn fa fa-trash"
						title="Excluir Item"
						style="width: 38px; height: 34px;margin: 0 auto; background-color: red; color: white;"
                        onClick="javascript:excluirItemTabelaItemIndice(this,event)">
					`;
				}
			}
		]
	});

    function excluirItemTabelaItemIndice(botao, event){
        tabelaItensIndice.row(botao.parentNode.parentNode).remove().draw();
    }

    $('#btnAddItemIndiceTabela').click( function (e) {
        e.preventDefault();
        let idIndice = $('#btnSalvarItemIndice').data('idIndice');
        let idTipoIndicador = $('#tipoIndicadornItemIndice').val();
        let percMeta = ($('#percentualMetaItemIndice').val()).replace(',', '.').replace('%', '');
        let percSalario = ($('#percentualSalarioItemIndice').val()).replace(',', '.').replace('%', '');
        let tipoIndicadorDescricao = $('#tipoIndicadornItemIndice option:selected').text();

        if (percMeta === '' || percSalario === '' || idTipoIndicador === null){
            alert('Preencha todos os campos.')
        }else{
            tabelaItensIndice.rows.add([
                {idTipoIndicador: idTipoIndicador, tipoIndicador: tipoIndicadorDescricao, percMeta: percMeta, percSalario: percSalario}
            ]).draw();
            tabelaItensIndice.columns.adjust().draw();
            
            $('#percentualMetaItemIndice').val('');
            $('#percentualSalarioItemIndice').val('');
            $('#tipoIndicadornItemIndice').val('').trigger('change');
        }
    });

    $('#btnSalvarItemIndice').click(function (e){
        e.preventDefault();
        let idIndice = $('#btnSalvarItemIndice').data('idIndice');
        let itens = tabelaItensIndice.rows().data().toArray();
        let itensIndice = [];
        let btn = $(this);

        if (itens.length == 0){
            alert('Adicione, pelo menos, um item na tabela.')
            return false;
        }else{
            btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

            itens.forEach(function (item){
                itensIndice.push({
                    tipoIndicador: item.idTipoIndicador,
                    percMeta: item.percMeta,
                    percSalario: item.percSalario
                });
            });

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/cadastrarItensIndice') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    idCalculoComissaoIndicador: idIndice,
                    indiceItens: itensIndice
                },
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        $('#modalCadastroItemIndice').modal('hide');
                    }else if (data.status === 400){
                        alert(data.dados.errors[0] ?? 'Verifique os campos e tente novamente!')
                    }else if (data.status === 404){
                        alert(data.dados.mensagem ?? 'Não foi possível cadastrar item. Verifique os campos e tente novamente.')
                    }else{
                        alert(data.dados.mensagem ?? 'Não foi possível cadastrar item. Tente novamente.')
                    }
                },
                error: function(){
                    alert('Erro ao cadastrar item. Tente novamente!')
                    btn.attr('disabled', false).html('Salvar Item(s)');
                },
                complete: function(){
                    btn.attr('disabled', false).html('Salvar Item(s)');
                }
            });
        }
    });
    
    let tabelaItensDoIndiceListagem = $('#tabelaItensDoIndiceListagem').DataTable({
		responsive: true,
		ordering: false,
		paging: true,
		searching: true,
		info: true,
		language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum item adicionado",
            info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
            infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
            zeroRecords:        "Nenhum resultado encontrado.",
            paginate: {
                first:          "Primeira",
                last:           "Última",
                next:           "Próxima",
                previous:       "Anterior"
            },
        },
		deferRender: true,
		lengthChange: false,
		columns: [
            {
                data: 'tipoIndicador',
                render: function(data) {
                    var indicadorDescricao = {
                        0: 'Usa quantidade de equipamento',
                        1: 'Usa Receita de licença de uso',
                        2: 'Usa receita de equipamento e taxas de instalação'
                    };

                    return indicadorDescricao[data] ?? '-';
                }
            },
            { data: 'percMeta',
                render: function(data) {
                    return data + '%';
                }
            },
            { data: 'percSalario',
                render: function(data) {
                    return data + '%';
                }
            },
            { data: 'status'},
			{
				data:{ 'id': 'id' },
				orderable: false,
				render: function (data) {
					return `
					<button 
						class="btn btn-primary"
						title="Editar Item"
						id="btnEditarItemIndiceListagem"
                        onClick="javascript:abrirEditarItemDoIndice(this,'${data['id']}','${data['idCalculoComissaoIndicador']}','${data['tipoIndicador']}','${data['percMeta']}','${data['percSalario']}','${data['status']}',event)">
						<i class="fa fa-pencil" aria-hidden="true"></i>
					</button>
                    <button
                        id="btnAlterarStatusItemindice"
                        class="btn btn-primary"
                        title="Alterar Status do Índice"
                        style= '${data['status'] == 'Ativo' ? 'background-color: red !important;' : 'background-color: green !important;'}'
                        onClick="javascript:alterarStatusItemIndice(this,'${data['id']}','${data['idCalculoComissaoIndicador']}','${data['status']}', event)">
                        <i class="fa fa-exchange" aria-hidden="true"></i>
                    </button>
					`;
				}
			}
		]
	});

    async function abrirModalVisualizarItensIndice(botao, idIndice, idConfigCalculoComissao, event){
        event.preventDefault();
        btn = $(botao);
        btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        await $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarItemIndicePorIdIndice') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idIndice: idIndice
            },
            success: function(data){
                if (data.status === 200){
                    tabelaItensDoIndiceListagem.clear().draw();
                    tabelaItensDoIndiceListagem.rows.add(data.results).draw();
                    tabelaItensDoIndiceListagem.columns.adjust().draw();
                    $('#modalItensDoIndice').modal('show');
                }else if (data.status === 400){
                    tabelaItensDoIndiceListagem.clear().draw();
                    alert('Este índice não possui item(s).')
                }else{
                    tabelaItensDoIndiceListagem.clear().draw();
                    alert(data.dados.mensagem ?? 'Não foi possível listar itens. Tente novamente.')
                }
            },
            error: function(){
                tabelaItensDoIndiceListagem.clear().draw();
                alert('Erro ao listar itens. Tente novamente!')
            },
            complete: function(){
                btn.attr('disabled', false).html('<i class="fa fa-list" aria-hidden="true"></i>');
            }
        });
    }

    function abrirEditarItemDoIndice(botao, id, idCalculoComissaoIndicador, tipoIndicador, percMeta, percSalario, status, event){
        event.preventDefault();
        btn = $(botao);
        btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        if (status == 'Ativo'){
            status = 1;
        }else{
            status = 0;
        }

        $('#btnEditarItemIndice').data('idItemIndice', id);
        $('#btnEditarItemIndice').data('idIndice', idCalculoComissaoIndicador);
        $('#btnEditarItemIndice').data('itemIndiceStatus', status);

        $('#tipoIndicadornItemIndiceEdit').val(tipoIndicador ? tipoIndicador : '');
        $('#percentualMetaItemIndiceEdit').val(percMeta + '%');
        $('#percentualSalarioItemIndiceEdit').val(percSalario + '%');

        $('#modalEditItemIndice').modal('show');
        btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');

    }

    $('#formEditarItemIndice').submit(function (e){
        e.preventDefault();
        let idItemIndice = $('#btnEditarItemIndice').data('idItemIndice');
        let idIndice = $('#btnEditarItemIndice').data('idIndice');
        let idTipoIndicador = $('#tipoIndicadornItemIndiceEdit').val();
        let percMeta = ($('#percentualMetaItemIndiceEdit').val()).replace(',', '.').replace('%', '');
        let percSalario = ($('#percentualSalarioItemIndiceEdit').val()).replace(',', '.').replace('%', '');
        let status = $('#btnEditarItemIndice').data('itemIndiceStatus');

        btn = $('#btnEditarItemIndice');
        btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Editando...');

        $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/editaritemIndice') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idItem: idItemIndice,
                idCalculoComissaoIndicador: idIndice,
                tipoIndicador: idTipoIndicador,
                percMeta: percMeta,
                percSalario: percSalario,
                status: status
            },
            success: function(data){
                if (data.status === 200 || data.status === 201){
                    alert(data.dados.mensagem);
                    $('#modalEditItemIndice').modal('hide');
                    atualizaTabelaItensIndice(idIndice);
                }else if (data.status === 500){
                    alert('Não foi possível editar item. Tente novamente.');
                }else{
                    alert(data.dados.mensagem ?? data.dados.errors[0] ?? 'Não foi possível editar item. Verifique os campos e tente novamente.');
                }
            },
            error: function(){
                alert('Erro ao editar item. Tente novamente!')
                btn.attr('disabled', false).html('Editar');
            },
            complete: function(){
                btn.attr('disabled', false).html('Editar');
            }
        });
    });

    function alterarStatusItemIndice(botao, id, idCalculoComissaoIndicador, status, event){
        event.preventDefault();
        btn = $(botao);
        if (status == 'Ativo'){
            status = 0
        }else{
            status = 1
        }
        if (confirm('Deseja realmente alterar o status deste item?')){
            btn.attr('disabled', true);
            btn.html('<i class="fa fa-spinner fa-spin"></i>')

            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/alterarStatusItemIndice') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    status: status
                },
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        atualizaTabelaItensIndice(idCalculoComissaoIndicador);
                    }else{
                        alert(data.dados.mensagem ?? "Não foi possível alterar o status do item. Tente novamente!")
                    }
                },
                error: function(){
                    alert('Erro ao alterar status do item. Tente novamente.')
                    btn.attr('disabled', false);
                },
                complete: function(){
                    btn.attr('disabled', false).html('<i class="fa fa-exchange" aria-hidden="true"></i>');
                }
            });
        }

    }

    function atualizaTabelaItensIndice(id){
        $.ajax({
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarItemIndicePorIdIndice') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idIndice: id
            },
            beforeSend: function () {
                $('#tabelaItensDoIndiceListagem > tbody').html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="10" class="dataTables_empty"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b>Processando...</b></td>' +
                    '</tr>'
                );
            },
            success: function(data){
                if (data.status === 200){
                    tabelaItensDoIndiceListagem.clear().draw();
                    tabelaItensDoIndiceListagem.rows.add(data.results).draw();
                    tabelaItensDoIndiceListagem.columns.adjust().draw();
                }else{
                    tabelaItensDoIndiceListagem.clear().draw();
                }
            },
            error: function(){
                tabelaItensDoIndiceListagem.clear().draw();
                alert('Erro ao listar itens do índice. Tente novamente!')
            }
        });
    }

    $('#modalCadastroMeta').on('shown.bs.modal', function () {
        tabelaItensdeMeta.columns.adjust().draw();
    });

    $('#modalCadastroIndice').on('shown.bs.modal', function () {
        tabelaIndices.columns.adjust().draw();
    });

    $('#modalCadastroItemIndice').on('shown.bs.modal', function () {
        tabelaItensIndice.columns.adjust().draw();
    });
</script>


