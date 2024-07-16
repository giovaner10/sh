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

    .dataTables_wrapper .dataTables_processing div {
        display: inline-block;
        vertical-align: center;
        font-size: 68px;
        height: 100%;
        top: 0;
    }

    th, td.wordWrap {
        max-width: 100px;
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
    .bord{
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

    .loader {
        margin-top: 20%;
        margin-left: 45%;
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    #info-icon {
        color: gray;
        transition: color 0.3s;
    }

    #info-icon:hover {
        color: blue;
        cursor: pointer;
    }
</style>
<script>
    var Vsetor = '';
    var Vtransportador = '';
    var Vservico = '';
    var Vempresa = '';
    var linhaTabela = '';
</script>

<h3><?=lang("movimentosEstoque")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('logistica')?> >
	<?=lang('movimentosEstoque')?>
</div>

<hr>
           

<div class="container-fluid my-1">
    <div class="col-sm-12">
        <form id="formPesquisa">
            <div class="row" style="margin-left: auto;">
                <div class="col-md-4">
                    <label>Nome: </label>
                    <select class="form-control input-sm" id="pesqnome" name="nome" type="text" style="width: 100%;"></select>
                </div>
                <div class="col-md-2">
                    <label for="periodo">Data Inicial:</label>	
                    <input class="form-control input-sm" name="dataInicial" id="dataInicial-tabela" type="date">
                </div>
                <div class="col-md-2">
                    <label for="periodo">Data Final:</label>	
                    <input class="form-control input-sm" name="dataFinal" id="dataFinal-tabela" type="date">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" id="pesquisacliente" type="submit" style="margin-top: 22px;">Pesquisar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<br>


<div class="col-md-12">
	<div class="col-sm-12">
        <a id="cadastro" class="btn btn-primary" >Nova Movimentação</a>
        <table id="tabela-movimentos" class="table table-bordered table-hover responsive nowrap" style="width: 100%;">
            <thead>
                <th hidden>Data Movimento</th>
                <th>Responsável</th>
                <th>Dia</th>
                <th>Semana</th>
                <th>Mês</th>
                <th>Orgão</th>
                <th>Cliente</th>
                <th>Setor</th>
                <th>Tipo</th>

                <th>1ª semana</th>
                <th>2ª semana</th>
                <th>3ª semana</th>
                <th>4ª semana</th>

                <th>Volumes</th>

                <th>Valor Total Itens</th>

                <th>Observações</th>

                <th>Transportadora</th>

                <th>Cidade</th>
                <th>UF</th>
                <th>Região</th>

                <th>Ações</th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="modalInsercao" style="overflow-y: auto;" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="header-modal">Cadastro de Movimento de Estoque <span id="tituloDetalhesDoContrato"></span></h3>
            </div>
            <div class="modal-body scrollModal">
                        <ul class="nav nav-tabs" style="margin-bottom: 10px;">
                            <li class="nav-item">
                                <a id = "tab-dadosGerais" href="" data-toggle="tab" class="nav-link active">Dados Gerais</a>
                            </li>
                            <li class="nav-item">
                                <a id = "tab-transportadores" href="" data-toggle="tab" class="nav-link">Transportador e Detalhes de Transporte</a>
                            </li>
                            <li class="nav-item">
                                <a id = "tab-itensDeTransporte" href="" data-toggle="tab" class="nav-link">Itens de Movimento</a>
                            </li>
                        </ul>
                        <div class="col-md-12">
                            <form id="expedicao">
                                <input hidden name="action" id="action-expedicao" type="text">
                                <input hidden name="id" id="id-expedicao" value="">
                                <input hidden name="status" id="status-expedicao" value="">
                                <div id="dadosGerais" style="display: block;">
                                    <div class="row" >
                                        <div id="select-empresa" class="col-md-12 form-group bord">
                                            <label>Empresa</label>
                                            <select class="form-control input-sm" name="empresa" id="empresa-expedicao" style="width: 100%" >
                                            </select>
                                        </div>
                                        <div id="nome-empresa" style="display: none" class="col-md-12 form-group bord">
                                            <label>Empresa</label><br>
                                            <select id="select-empresa-nome" disabled class="form-control">
                                                <option id="empresa-nome" value="" selected disabled></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label>Responsável</label>
                                            <input class="form-control input-sm" name="responsavel" id="responsavel-expedicao" type="text" placeholder="Digite o nome do responsável">
                                        </div>
                                        <div class="col-md-6 form-group bord">
                                            <label>Tipo Órgão</label>
                                            <input class="form-control input-sm" name="tipo-orgao" id="tipo-orgao-expedicao" type="text" placeholder="Aguardando seleção do cliente..." disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div id ="divBuscarPor"class="col-md-2 col-sm-2 form-group bord" >
                                            <label>Pesquisar por:</label>
                                            <select class="form-control input-sm" name="buscar" id="tipo-busca-expedicao">
                                                <option value="0">Id</option>
                                                <option value="1">Nome</option>
                                                <option value="2">Documento</option>
                                            </select>
                                        </div>
                                        <div id ="divNomeCliente" class="col-md-10 col-sm-6" style="border-left: 8px solid #03A9F4;">
                                            <label>Cliente:</label>
                                            <select class="form-control input-sm" name="cliente" id="cliente-expedicao" type="text" style="width: 100%;">
                                            </select>
                                        </div>
                                        <div id ="divDocCliente" class="col-md-8 col-sm-6 bord" hidden>
                                            <label>Cliente:</label>
                                            <input class="form-control input-sm" name="clienteDoc" id="cliente-expedicaoDoc" type="text" placeholder="Digite o CPF/CNPJ do cliente">
                                            </input>
                                        </div>
                                        <div id ="divIdCliente" class="col-md-8 col-sm-6 bord">
                                            <label>Cliente:</label>
                                            <input class="form-control input-sm" name="clienteId" id="cliente-expedicaoId" type="text" placeholder="Digite o ID do cliente">
                                            </input>
                                        </div>

                                        <div id ="divBtnPesquisaCliente" class="col-md-2 col-sm-2" style="display: contents;" hidden>
                                            <button id="btnPesquisaClienteDoc" class="btn btn-primary" type="button" style="margin-top: 23px;">Buscar</button>
                                            <button id="btnLimparPesquisaClienteDoc" class="btn btn-danger" type="button" style="margin-top: 23px;">Limpar</button>
                                        </div>
                                    </div>
                                    <!-- <div class="row">
                                        
                                    </div> -->
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label>CEP</label>
                                            <input class="form-control input-sm" name="cep" id="cep-expedicao" type="text" placeholder="Digite o cep">
                                        </div>
                                        <div class="col-md-6 form-group bord">
                                            <label>Região</label>
                                            <input class="form-control input-sm" name="regiao" id="regiao-expedicao" type="text" placeholder="Digite a região">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9 form-group bord">
                                            <label>Endereço</label>
                                            <input class="form-control input-sm" name="endereco" id="endereco-expedicao" type="text" placeholder="Digite o endereço">
                                        </div>
                                        <div class="col-md-3 form-group bord">
                                            <label>UF</label>
                                            <input class="form-control input-sm" name="uf" id="uf-expedicao" type="text" placeholder="Digite o estado">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label>Bairro</label>
                                            <input class="form-control input-sm" id="bairro-expedicao" name="bairro" type="text" placeholder="Digite o bairro">
                                        </div>
                                        <div class="col-md-6 form-group bord">
                                            <label>Cidade</label>
                                            <input class="form-control input-sm" id="cidade-expedicao" name="cidade" type="text" placeholder="Digite a cidade">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label>Setor</label>
                                            <div id="divAddSetor" style="float:right">
                                                <i class="fa fa-plus" id="add-setor" title="Cadastrar Setor" style="cursor: pointer;" onClick="javascript:cadastrarSetor(this)"></i>
                                            </div>
                                            <select class="form-control input-sm" name="setor" id="setor-expedicao" style="width: 100%;" type="text">
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group bord">
                                            <label>Tipo de Movimento</label>
                                            <div id="divAddTipoServico" style="float:right">
                                                <i class="fa fa-plus" id="add-tipo-movimento" title="Cadastrar Tipo de Movimento" style="cursor: pointer;" onClick="javascript:cadastrarTipoMovimento(this)"></i>
                                            </div>
                                            <select class="form-control input-sm" style="width: 100%" name="tipo-servico" id="tipo-servico-expedicao" type="text" style="width: 100%;"></select>
                                        </div>
                                    </div>
                                </div>
                                <div id ="transportadores" style="display: none !important; ">
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label>Quant. Volumes</label>
                                            <input class="form-control input-sm" name="qtde-volumes" min="0" id="qtde-volumes-expedicao" type="number" placeholder="Digite a quantidade de volumes">
                                        </div>
                                        <div class="col-md-6"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label>Transportador</label>
                                            <div id="divAddTransportador" style="float:right">
                                                <i class="fa fa-plus" id="add-transportador" title="Cadastrar Transportador" style="cursor: pointer;" onClick="javascript:cadastrarTransportador(this)"></i>
                                            </div>
                                            <select class="form-control input-sm" name="transportador" id="transportador-expedicao" style="width: 100%;" type="text">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group bord">
                                            <label>Observação</label>
                                            <input class="form-control input-sm" name="observacao" id="observacao-expedicao" type="text" placeholder="Digite a observação">
                                        </div>
                                    </div>
                                </div>
                                <div id ="itens" style="display: none !important; ">
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label>Serial:</label>
                                            <select class="form-control input-sm" name="terminal" id="terminal-itens" type="text"></select>
                                        </div>
                                        <div class="col-md-6 form-group bord">
                                            <label class="control-label">Referência:</label>
                                            <input class="form-control input-sm" name="referencia" id="referencia-itens" type="text" placeholder="Digite a referência ou selecione o serial">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label class="control-label">Quantidade:</label>
                                            <input class="form-control input-sm" name="quantidade" id="quantidade-itens" type="number" placeholder="Digite a quantidade do item">
                                        </div>
                                        <div class="col-md-6 form-group bord">
                                            <label class="control-label">Valor:</label>
                                            <input class="form-control input-sm" name="valor" id="valor-itens" type="text" onkeyup="formatarMoeda(this.id)" placeholder="Digite o valor em R$">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label class="control-label">Movimento:</label>
                                            <select class="form-control input-sm" name="movimento-item" id="movimento-item">
                                                <option value="" data-default selected>Selecione um movimento</option>
                                                <option value="1">Entrada</option>
                                                <option value="0">Saída</option> 
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <button class="btn btn-primary" type="button" id="botao-adicionar-item" style="margin-top: 24px;">Adicionar</button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 form-group" style="display: flex;">
                                            <input class="form-control input-sm" name="arquivoItens" id="arquivoItens" type="file">
                                            <div class="col-md-1 form-group" style="margin-top: 5px;">
                                                <i class="fa fa-info-circle" style="font-size: 18px;" id="info-icon" aria-hidden="true" title="Clique para saber mais"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-left: auto;">
                                        <button class="btn btn-primary" onclick="importarItensExcel(event)" type="button" id="botao-adicionar-item-arquivo">Importar</button>
                                        <button class="btn" id="limparTabelaItens" style="background-color: red;color: white">Limpar Tabela</button>  
                                    </div>
                                    <table class="table-responsive table-bordered table" id="tabelaItensMovimento" style="width: 100%;">
                            	    	<thead>
                            	    	    <tr class="tableheader">
                                            <th>idMovimento</th>
				            	    		<th>Referencia</th>
                            	    	    <th>Serial</th>
                            	    	    <th>Quantidade</th>
                            	    	    <th>Valor</th>
                                            <th>Movimento</th>
                            	    	    <th>Ações</th>
                            	    	    </tr>
                            	    	</thead>
                            	    	<tbody>
                            	    	</tbody>
                            	    </table> 
                                </div>
                            </div>
                        </div>
            <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" id="botao-salvar-expedicao">Salvar</button>
                </form>
                <button class="btn" data-dismiss="modal" aria-hidden="true" style="margin-right: 15px;">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL MODELO DOCUMENTO ITENS DE MOVIMENTO -->
<div id="modalModeloItens" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 id="header-modal">Modelo de documento <span id="tituloDetalhesDoContrato"></span></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0px 20px">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-12" style="border-left: 3px solid #03A9F4; padding-bottom: 0px; margin-right: 0px">
                                            <p class="text-justify">A planilha deve conter as seguintes colunas, nesta ordem: referência, serial, quantidade e valor. Formatos suportados: .xls e .xlsx.</p>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 20px;">
                                        <img src="<?=base_url("uploads/movimentosEstoque/modelo-exemplo.png")?>" alt="" class="img-responsive center-block" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                    <button id="btnBaixarModelo" class="btn btn-primary" type="button" onclick="baixarModeloItens()">Baixar Modelo</button>
                    <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    </div>
        </div>
    </div>
</div>

<!-- MODAL CADASTRAR SETOR/TIPO DE MOVIMENTO -->
<div id="modalCadastrar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadastro">
                <div id="headerModalCadastro"class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                            <label class="control-label">Nome</label>
                                            <input type="text" class="form-control" id="nome" placeholder="Digite o nome" style="height: 28px;">
                                        </div>
                                        <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                            <label class="control-label">Empresa: </label>
       			                            <select class="form-control input-sm" id="selectEmpresa" name="nome" type="text" style="width: 100%;"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <a class="btn btn-primary" id="btnSalvarCadastro" style="margin-right: 20px;">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL CADASTRAR TRANSPORTADOR -->
<div id="modalCadTransportador" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadTransportador">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("novo_transportador")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                            <label class="control-label">Nome</label>
                                            <input type="text" class="form-control input-sm" id="nomeTransportador" placeholder="Nome do transportador">
                                        </div>
                                        <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                            <label class="control-label">Cpf/Cnpj</label>
                                            <input type="text" class="form-control input-sm" id="cpfCnpjTransportador" placeholder="CPF/CNPJ do transportador">
                                        </div>
                                        <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                            <label class="control-label">Cep</label>
                                            <input type="text" class="form-control input-sm" id="cepTransportador" placeholder="Digite o cep">
                                        </div>
                                        <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                            <label class="control-label">Rua</label>
                                            <input type="text" class="form-control input-sm" id="ruaTransportador" placeholder="Digite a rua">
                                        </div>
                                        <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                            <label class="control-label">Bairro</label>
                                            <input type="text" class="form-control input-sm" id="bairroTransportador" placeholder="Digite o bairro">
                                        </div>
                                        <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                            <label class="control-label">Cidade</label>
                                            <input type="text" class="form-control input-sm" id="cidadeTransportador" placeholder="Digite a cidade">
                                        </div>
                                        <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                            <label class="control-label">Uf</label>
                                            <select type="text" class="form-control input-sm" id="ufTransportador">
                                                    <option selected disabled value="0">Selecione</option>
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
                                        <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                            <label class="control">Complemento</label>
                                            <input type="text" class="form-control input-sm" id="complementoTransportador" placeholder="Digite o complemento">
                                        </div>
                                        <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                            <label class="control-label">Empresa:</label>
                                            <select class="form-control input-sm" id="selectEmpresaCadTransportador" name="nome" type="text" style="width: 100%;"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <a class="btn btn-primary" id="btnSalvarCadastroTransportador" style="margin-right: 20px;">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Itens do Movimento -->
<div id="modalItensMovimento" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="width: max-content;">
            <form name="formItensMovimento">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("itens_movimento")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div id="div_identificacao">
                        <table class="table-responsive table-bordered table" id="tabelaItensDoMovimento">
                	    	<thead>
                                <tr class="tableheader">
                		        <th>Referência</th>
                		        <th>Serial</th>
                		        <th>Quantidade</th>
                		        <th>Valor Unitário</th>
                		        <th>Valor Total</th>
                		        <th>Movimento</th>
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

<!-- Modal Adicionar Item ao Movimento -->
<div id="modalAddItemMovimento" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            	<form name="formAddItemMovimento" id="formAddItemMovimento">
                	<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="nome-modal-header"></h3>
                	</div>
                	<div class="modal-body scrollModal">
						<div class="col-md-12">
                            <div class="row" style="margin-bottom: 6px;">
                                <div id="divSerialCad" class="col-md-6 form-group bord">
                                    <label>Serial:</label>
                                    <select class="form-control input-sm" name="addItemTerminal" id="addItemTerminal" type="text"></select>
                                </div>
                                <div id="divSerialEdit" class="col-md-6 form-group bord">
                                    <label>Serial:</label>
                                    <select class="form-control input-sm" name="editItemTerminal" id="editItemTerminal" type="text"></select>
                                </div>
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Referência:</label>
                                    <input class="form-control" name="addItemReferencia" id="addItemReferencia" type="text" placeholder="Digite a referência ou selecione o serial" required>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 6px;">
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Quantidade:</label>
                                    <input class="form-control" name="addItemQuantidade" id="addItemQuantidade" type="number" placeholder="Digite a quantidade do item" required>
                                </div>
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Valor:</label>
                                    <input class="form-control" name="addItemValor" id="addItemValor" type="text" onkeyup="formatarMoeda(this.id)" placeholder="Digite o valor em R$" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group bord" >
                                    <label class="control-label">Movimento:</label>
                                    <select class="form-control" name="addItemMovimento" id="addItemMovimento" required>
                                        <option selected value="1">Entrada</option>
                                        <option value="0">Saída</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group bord" id="divStatusItem">
                                    <label class="control-label">Status</label>
                                    <select class="form-control input-sm" id="editStatusItem">
                                        <option value="1">Ativo</option>
                                        <option value="0">Inativo</option>
                                    </select>
                                </div>
                            </div>
						</div>
					</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>

                    <button class="btn btn-primary" data-id="" data-idItem="" id="btnSalvarAddItemMovimento" type="submit" style="margin-right: 15px;">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="loading">
    <div class="loader"></div>
</div>
<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>

<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>

<!-- XLSX -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script>
    let tabelaItensDoMovimento = $('#tabelaItensDoMovimento').DataTable({
		responsive: true,
		ordering: false,
		paging: true,
		searching: true,
		info: true,
		language: lang.datatable,
		deferRender: true,
		lengthChange: false,
		columns: [
			{ data: 'referencia'},
			{ data: 'idTerminal'},
            { data: 'qutUnitaria'},
			{
				data: "valorUnitario",
				render: function(data) {
					return parseFloat(data).toLocaleString('pt-BR', {
						style: 'currency',
						currency: 'BRL'
					});
				}
			},
            {data: 'valorTotal',
                render: function(data) {
					return parseFloat(data).toLocaleString('pt-BR', {
						style: 'currency',
						currency: 'BRL'
					});
				}},
            {data: 'movimento'},
			{ data: 'dataCadastro', render: function(data) {
					return new Date(data).toLocaleDateString();
				}
			},
			{ data: 'dataUpdate',
				render: function(data) {
					return new Date(data).toLocaleDateString();
				}
            },
            {data: 'status'},
			{
				data:{ 'id': 'id' },
				orderable: false,
				render: function (data) {
					return `
					<button 
                        id="btnEditarItemMovimento"
						class="btn btn-primary"
						title="Editar Item Movimento"
                        onClick="javascript:abrirEditarItemMovimento(this, '${data['id']}', '${data['idMovimentoExpedicao']}','${data['referencia']}','${data['idTerminal']}', '${data['qutUnitaria']}', '${data['movimento']}', '${data['valorUnitario']}', '${data['status']}', event)">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
					</button>
					<button
                        class="btn btn-danger"
                        title="Remover Item Movimento"
                        id="btnRemoverItemMovimento"
                        onClick="javascript:removerItemMovimento(this,'${data['id']}','${data['idMovimentoExpedicao']}', event)">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
					`;
				}
			}
		]

	})

    $('#tab-dadosGerais').click(function (e) {
        e.preventDefault()
        $('#dadosGerais').show()
        $('#transportadores').hide()
        $('#itens').hide()
    })

    $('#tab-transportadores').click(function (e) {
        e.preventDefault()
        $('#transportadores').show()
        $('#dadosGerais').hide() 
        $('#itens').hide()
    })

    $('#tab-itensDeTransporte').click(function (e) {
        e.preventDefault()
        $('#itens').show()
        $('#transportadores').hide()
        $('#dadosGerais').hide() 
    })

    $("#qtde-volumes-expedicao").change(function () {
        $("#qtde-total-volumes-expedicao").val(parseInt($("#qtde-volumes-expedicao").val()) + (parseInt($("#quantidade-volumes-adicional-expedicao").val()) ? parseInt($("#quantidade-volumes-adicional-expedicao").val()) : 0))
        
        if($("#valor-unitario-expedicao").val() != 0){
            $("#valor-itens-expedicao").val(parseFloat($("#valor-unitario-expedicao").val()) * parseInt($("#qtde-volumes-expedicao").val()))
        }
    })

    $("#quantidade-volumes-adicional-expedicao").change(function () {
        $("#qtde-total-volumes-expedicao").val((parseInt($("#qtde-volumes-expedicao").val()) ? parseInt($("#qtde-volumes-expedicao").val()) : 0) + parseInt($("#quantidade-volumes-adicional-expedicao").val()))
        
        if($("#valor-unitario-expedicao").val() != 0){
            $("#valor-itens-adicional-expedicao").val(parseFloat($("#valor-unitario-expedicao").val()) * parseInt($("#quantidade-volumes-adicional-expedicao").val()))
        }
    })

    $("#valor-unitario-expedicao").change(function (){
        $("#valor-itens-expedicao").val(parseFloat($("#valor-unitario-expedicao").val()) * (parseInt($("#qtde-volumes-expedicao").val()) ? parseInt($("#qtde-volumes-expedicao").val()) : 0))
        $("#valor-itens-adicional-expedicao").val(parseFloat($("#valor-unitario-expedicao").val()) * (parseInt($("#quantidade-volumes-adicional-expedicao").val()) ? parseInt($("#quantidade-volumes-adicional-expedicao").val()) : 0))
    })

    async function editarMovimento(botao, id, responsavel, empresa, 
    cliente, tipoOrgao, cep, regiao, bairro, endereco, uf, cidade, setor, 
    tipoServico, qtdeVolumes, qtdeVolumesAdc, valorItens, valorItensAdc, 
    observacao, dataCadastro, transportador, valUnit, status){
        let btn = $(botao)
        let btnHtml = btn.html()
        btn.html('<i class="fa fa-spin fa-spinner"></i>')
        btn.attr('disabled', true)

        $("#header-modal").text('Edição de Movimento de Estoque')
        //preenchendo values do form
        $("#action-expedicao").val("update")
        $("#id-expedicao").val(id)
        $("#status-expedicao").val(status)
        $("#divNomeCliente").attr("style", "border-left: 3px solid #03A9F4");
        $("#tipo-servico-expedicao").attr("disabled", true)
        $("#setor-expedicao").attr("disabled", true)
        $("#transportador-expedicao").attr("disabled", true)


        Vsetor = setor
        Vtransportador = transportador
        Vservico = tipoServico
        Vempresa = await $.ajax({
            url: '<?= site_url('MovimentosEstoque/getIdEmpresa') ?>',
            dataType: 'json',
            type: 'GET',
            data: {
                q : empresa
            },
            success: function (response) {
                return response
            }, 
        })        
        
        $("#empresa-nome").text(empresa)
        $("#nome-empresa").attr("style", "display: block")
        $("#select-empresa").attr("style", "display: none !important")
        $("#empresa-expedicao").trigger('change')        
        $("#divDocCliente").hide()
        $('#divBuscarPor').hide()
        $('#divBtnPesquisaCliente').hide()
        $('#tipo-busca-expedicao').val(1).trigger('change')
        $("#cliente-expedicao").empty()
        $("#cliente-expedicao").append('<option value="'+cliente+'">'+cliente+'</option>')
        $("#cliente-expedicao").attr("disabled", true)
        
        $("#responsavel-expedicao").val(responsavel)
        $("#responsavel-expedicao").attr("disabled", true)

        $("#lancamento-expedicao").val(dataCadastro.slice(0,10))
        $("#lancamento-expedicao").attr("disabled", true)

        $('#tipo-orgao-expedicao').val(tipoOrgao)
        $('#tipo-orgao-expedicao').attr("disabled", true)

        $("#cep-expedicao").val(cep).attr("disabled", true)
        $("#regiao-expedicao").val(regiao).attr("disabled", true)
        $("#bairro-expedicao").val(bairro).attr("disabled", true)
        $("#endereco-expedicao").val(endereco).attr("disabled", true)
        $("#uf-expedicao").val(uf).attr("disabled", true)
        $("#cidade-expedicao").val(cidade).attr("disabled", true)
        $('#tab-itensDeTransporte').hide();

        $("#qtde-volumes-expedicao").val(qtdeVolumes)
        $('#qtde-volumes-expedicao').attr("disabled", true)
        /* $("#quantidade-volumes-adicional-expedicao").val(qtdeVolumesAdc)
        $("#qtde-total-volumes-expedicao").val(parseFloat(qtdeVolumes) + parseFloat(qtdeVolumesAdc))
        $("#valor-unitario-expedicao").val(valUnit)
        $("#valor-itens-expedicao").val(parseFloat(valorItens.slice(2)))
        $("#valor-itens-adicional-expedicao").val(parseFloat(valorItensAdc.slice(2))) */
        $("#observacao-expedicao").val(observacao)
        $("#observacao-expedicao").attr("disabled", true)

        $("#tab-dadosGerais").click()
        $("#modalInsercao").modal("show")
        btn.html(btnHtml);
        btn.attr('disabled', false);
    }

    $("#tabela-movimentos tbody").on("click", "tr", function(){
        if($(this).attr("id")){
            linhaTabela = $(this).attr("id")
        }
    })

    

    $('#modalInsercao').on('hide.bs.modal', async function (e) {
        $("#responsavel-expedicao").val('').removeAttr('disabled')
        $("#lancamento-expedicao").val('').removeAttr('disabled')
        $("#cep-expedicao").val('').removeAttr('disabled')
        $("#regiao-expedicao").val('').removeAttr('disabled')
        $("#bairro-expedicao").val('').removeAttr('disabled')
        $("#endereco-expedicao").val('').removeAttr('disabled')
        $("#uf-expedicao").val('').removeAttr('disabled')
        $("#cidade-expedicao").val('').removeAttr('disabled')
        $("#qtde-volumes-expedicao").val(0).removeAttr('disabled')
        $("#observacao-expedicao").val('').removeAttr('disabled')
        $("#referencia-itens").val('')
        $("#terminal-itens").val('').trigger('change')
        $("#terminal-itens").text('').trigger('change')
        $("#referencia-itens").attr('readonly', false)
        $("#quantidade-itens").val('')
        $("#valor-itens").val('')
        $('#arquivoItens').val('')
        tabelaItensMovimento.clear().draw()
        

        Vsetor = '';
        Vtransportador = '';
        Vservico = '';
        Vempresa = '';


        $("#cliente-expedicao").val(0).removeAttr('disabled')
        $("#transportador-expedicao").val(0).removeAttr('disabled')
        $("#empresa-expedicao").val(0).removeAttr('disabled')
        $("#setor-expedicao").val(0).removeAttr('disabled')
        $("#tipo-servico-expedicao").val(0).removeAttr('disabled')

        $("#cliente-expedicao").text("Selecione o Cliente")
        $("#transportador-expedicao").text("Selecione o Transportador")
        $("#empresa-expedicao").text("Selecione a Empresa")
        $("#setor-expedicao").text("Selecione o Setor")
        $("#tipo-servico-expedicao").text('Selecione o Tipo de Movimento')

        $("#nome-empresa").attr("style", "display: none")
        $("#select-empresa").attr("style", "display: block")
    })

    $("#cadastro").click(async function (event) {
        $("#tab-dadosGerais").click()
        if ($('#tab-itensDeTransporte').is(':hidden')) {
            $('#tab-itensDeTransporte').show();
        }
        $("#divNomeCliente").attr("style", "border-left: none");
        $('#tipo-busca-expedicao').val(2).trigger('change');        
        $("#cliente-expedicao").val(0).removeAttr('disabled')
        $('#divBuscarPor').show();
        $('#cliente-expedicaoDoc').attr("disabled", false).trigger('change');
        $('#cliente-expedicaoDoc').val('').trigger('change');
        $('#cliente-expedicaoId').val('')
        $('#cliente-expedicaoId').attr('disabled', false)
        $("#transportador-expedicao").val(0).removeAttr('disabled')
        if ($('#pesqnome').val() != null) {
            await listarEmpresasCad();
            $('#empresa-expedicao').val($('#pesqnome').val()).removeAttr('disabled').trigger('change');
        }else{
            $('#empresa-expedicao').empty().removeAttr('disabled');
            $("#empresa-expedicao").select2({
                ajax: {
                    url: '<?= site_url('MovimentosEstoque/buscarEmpresas') ?>',
                    dataType: 'json',
                    type: 'GET',
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                },
                placeholder: "Selecione a empresa",
                allowClear: true,
                minimumInputLength: 3,
                language: "pt-BR",
                width: 'resolve'
            })
        }
        $("#setor-expedicao").val(0).removeAttr('disabled')
        $("#tipo-servico-expedicao").val(0).removeAttr('disabled')
        $("#header-modal").text('Cadastro de Movimento de Estoque')
        $("#action-expedicao").val("store")
        $("#modalInsercao").modal("show")
    })

    $("#expedicao").submit(function (event){
        event.preventDefault();
        $('#botao-salvar-expedicao')
        .html('<i class="fa fa-spinner fa-spin"></i>')
        .attr('disabled', true);
        dadosTabela = tabelaItensMovimento.rows().data().toArray();
        dadosTabelaInserir = dadosTabela.map(function (obj){
            return{
                referencia: obj.referencia,
                idTerminal: obj.terminal,
                qutUnitaria: obj.quantidade,
                valorUnitario: obj.valor,
                movimento: obj.idMovimento,
            }
        })
        var responsavel         = $("#responsavel-expedicao").val()
            var data_lancamento     = $("#lancamento-expedicao").val()
           // var dataInserir = data_lancamento.split('-').reverse().join('/');
            var empresa             = $("#empresa-expedicao").val()
            var tipo_orgao          = $("#tipo-orgao-expedicao").val()
            if (tipo_orgao == 'publico') {
                tipo_orgao = 1;
            }else{
                tipo_orgao = 0;
            }
            var cliente             = $("#cliente-expedicaoId").val()
            var cep                 = $("#cep-expedicao").val().replace(/[^0-9]/, "");
            var regiao              = $("#regiao-expedicao").val()
            var bairro              = $("#bairro-expedicao").val()
            var endereco            = $("#endereco-expedicao").val()
            var uf                  = $("#uf-expedicao").val()
            var cidade              = $("#cidade-expedicao").val()
            var setor               = $("#setor-expedicao").val()
            var tipo_servico        = $("#tipo-servico-expedicao").val()
            var qtde_volumes        = $("#qtde-volumes-expedicao").val()
            /* var qtde_volumes_adc    = $("#quantidade-volumes-adicional-expedicao").val() */
            var qtde_total_volumes  = $("#qtde-total-volumes-expedicao").val()
            /* var valor_unitario      = $("#valor-unitario-expedicao").val() */
            var valor_itens         = $("#valor-itens-expedicao").val()
            /* var valor_itens_adc     = $("#valor-itens-adicional-expedicao").val() */
            var observacao          = $("#observacao-expedicao").val()
            var transportador       = $("#transportador-expedicao").val()

            var idCliente           = $("#cliente-expedicao").val()
            if (idCliente == null) {
                idCliente = idClienteDoc;
            }else{
                idCliente = $("#cliente-expedicao").val();
            }

            var status              = $("#status-expedicao").val()
        if (!tabelaItensMovimento.rows().data().toArray().length){
            var action = $("#action-expedicao").val()
            if(action == "update"){
                var id = $("#id-expedicao").val()
            } else {
                var id = null
            }
            

            $.ajax({
                url: `<?= site_url('MovimentosEstoque/updateOrCreate') ?>`,
                type: "POST",
                data: {
                    id,
                    idCliente,
                    action,
                    responsavel,
                    data_lancamento,
                    empresa,
                    tipo_orgao,
                    cep,
                    regiao,
                    endereco,
                    uf,
                    bairro,
                    cidade,
                    setor,
                    tipo_servico,
                    qtde_volumes,
                    observacao,
                    transportador,
                    status
                },
                success: function (response){
                    response = JSON.parse(response)
                    if(response?.status === 200){
                        alert("Movimento de Expedição cadastrado com sucesso!")
                        $("#modalInsercao").modal("hide")
                        $('#botao-salvar-expedicao')
                        .html('Salvar')
                        .attr('disabled', false);
                        let movimento = response?.dados?.movimento
                        if (!movimento?.semana) {
                            movimento.semana = '-'
                        }
                        if(action == "store"){
                            tabelaMovimentos.row.add(formatarDados(movimento)).draw()
                            alert("Movimento de Expedição cadastrado com sucesso!")
                        } else {
                            let linha = $("#tabela-movimentos").find("tr[id="+movimento.id+"]")
                            tabelaMovimentos.row(linha).remove();
                            tabelaMovimentos.row.add(formatarDados(movimento)).draw()
                            alert("Movimento de Expedição atualizado com sucesso!")
                        }
                    } else if (response?.status === 400){
                        alert("Foram encontrados erros nas informações fornecidas, confira os campos e tente novamente!")
                        $('#botao-salvar-expedicao')
                        .html('Salvar')
                        .attr('disabled', false);
                    }
                },
                done: function(){
                    prepararTabela()
                }
            })
        } else {

            $.ajax({
					url: `<?= site_url('MovimentosEstoque/cadastrarMovimentoEItem') ?>`,
					type: "POST",
					dataType: "json",
					data: {
						responsavel: responsavel,
                        //dataMovimento: dataAtual,
                        idEmpresa: empresa,
                        tipoEmpresa: tipo_orgao,
                        idCliente: idCliente,
                        idSetor: setor,
                        idTipoMovimento: tipo_servico,
                        idTransportador: transportador,
                        qutVolumes: qtde_volumes,
                        observacao: observacao,
                        cep: cep,
                        rua: endereco,
                        bairro: bairro,
                        cidade: cidade,
                        uf: uf,
                        regiao: regiao,
                        itens: dadosTabelaInserir
					},
					success: function(response){
						if (response.status == 200){
							alert(response.dados.mensagem);
                            prepararTabela();
                            $("#modalInsercao").modal("hide")
                            $('#botao-salvar-expedicao')
                            .html('Salvar')
                            .attr('disabled', false);
							
						}else{
							alert("Foram encontrados erros nas informações fornecidas, confira os campos e tente novamente!")
                            $('#botao-salvar-expedicao')
                            .html('Salvar')
                            .attr('disabled', false);
						}
					},
					error: function(error){
						alert('Erro ao cadastrar movimento de estoque, tente novamente!');
                        $('#botao-salvar-expedicao')
                        .html('Salvar')
                        .attr('disabled', false);
					}
				});
        }
        
        
    })


    var tabelaMovimentos = $('#tabela-movimentos').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        language:  {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum movimento a ser listado",
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
        rowId: 'id',
        order: [0],
        columns: [
            { data: 'dataMovimento' , visible: false },
            { data: 'responsavel'       },
            { data: 'dia'               },
            { data: 'semana'            },
            { data: 'mes'               },
            { data: 'orgao'             },
            { data: 'cliente'           },
            { data: 'setor'             },
            { data: 'tipo'              },
            { data: 'semana1'           },
            { data: 'semana2'           },
            { data: 'semana3'           },
            { data: 'semana4'           },
            { data: 'volume'            },
            { data: 'totalGeral'        },
            { data: 'obs'               },
            { data: 'transportadora'    },
            { data: 'cidade'            },
            { data: 'uf'                },
            { data: 'regiao'            },
            {
				data:{
                    'id'                : 'id', 
                    'status'            : 'status',
                    'responsavel'       : 'responsavel',
                    'orgao'             : 'orgao', 
                    'empresa'           : 'empresa',
                    'cliente'           : 'cliente',
                    'setor'             : 'setor',
                    'tipo'              : 'tipo',
                    'volume'            : 'volume',
                    'volumeAdicional'   : 'volumeAdicional',
                    'valor'             : 'valor',
                    'valorAdicional'    : 'valorAdicional',
                    'totalGeral'        : 'totalGeral',
                    'obs'               : 'obs',
                    'transportadora'    : 'transportadora',
                    'cidade'            : 'cidade',
                    'uf'                : 'uf',
                    'regiao'            : 'regiao',
                    'cep'               : 'cep',
                    'lancamento'        : 'lancamento' ,
                    "valorUnit"         : "valorUnit"   
                },
				orderable: false,
				render: function (data) {
                    let botoes =  `
					<button 
						class="btn btn-primary"
						title="Editar Movimento"
						id="btnEditarMovimento"
                        onClick="javascript:editarMovimento(this,
                        '${data['id']}',
                        '${data['responsavel']}',
                        '${data['empresa']}',
                        '${data['cliente']}',
                        '${data['orgao']}',
                        '${data['cep']}',
                        '${data['regiao']}',
                        '${data['bairro']}',
                        '${data['endereco']}',
                        '${data['uf']}',
                        '${data['cidade']}',
                        '${data['setor']}',
                        '${data['tipo']}',
                        '${data['volume']}',
                        '${data['volumeAdicional']}',
                        '${data['valor']}',
                        '${data['valorAdicional']}',
                        '${data['obs']}',
                        '${data['lancamento']}',
                        '${data['transportadora']}',
                        '${data['valorUnit']}',
                        '0'
                          )">
						<i class="fa fa-pencil" aria-hidden="true"></i>
					</button>
                    <button
                        id="btnVisualizarItensMovimento"
                        class="btn btn-primary"
                        title="Visualizar Itens do Movimento"
                        onClick="javascript:visualizarItensMovimento(this,'${data['id']}', event)">
                        <i class="fa fa-list-ul" aria-hidden="true"></i>
                    </button>
                    <button 
                        id="btnAddItemMovimento"
						class="btn btn-primary"
						title="Adicionar Item de Movimento"
                        onClick="javascript:abrirAddItemMovimento(this, '${data['id']}', event)">
                        <i class="fa fa-plus" aria-hidden="true"></i>
					</button>
                    `;

					
                    if(data['status'] == 'Ativo'){
                        botoes = botoes +`<button 
                            class="btn btn-danger"
                            title="Excluir Movimento"
                            id="btnExcluirMovimento"
                            onClick="javascript:excluirMovimento(this,'${data['id']}')">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>`
                    }

                    return botoes;
				}
			}
        ]
    });

    let tabelaItensMovimento = $('#tabelaItensMovimento').DataTable({
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
            { data: 'idMovimento',
                visible: false},
			{ data: 'referencia'},
			{ data: 'terminal'},
			{ data: 'quantidade'},
			{
				data: "valor",
				render: function(data) {
					return parseFloat(data).toLocaleString('pt-BR', {
						style: 'currency',
						currency: 'BRL'
					});
				}
			},
            { data: 'movimento'},
			{
				data:{ 'referencia': 'referencia' },
				orderable: false,
				render: function (data) {
					return `
					<button 
                        id="btnExcluirItemTabela"
						class="btn fa fa-trash"
						title="Excluir Item"
						style="width: 38px; height: 34px;margin: 0 auto; background-color: red; color: white;"
                        onClick="javascript:excluirItemTabela(this)">
                    </button>
					`;
				}
			}
		]
	})

    function excluirMovimento(botao, id){
        var btn = $(botao);
        if (confirm("Deseja realmente excluir este movimento?")){
            btn.html('<i class="fa fa-spin fa-spinner"></i>')
            btn.attr('disabled', true)
            $.ajax({
                url: `<?= site_url('MovimentosEstoque/excluirMovimento/') ?>`,
                type: "POST",
                data: {
                    id
                },
                success: function (response){
                    let resposta = JSON.parse(response)
                    if (resposta?.status === 200){
                        let linha =  $("#tabela-movimentos").find("tr[id="+resposta?.id+"]")
                        tabelaMovimentos.row(linha).remove().draw();
                        alert("Movimento de Expedição inativada com sucesso!")
                        btn.html('<i class="fa fa-trash" aria-hidden="true"></i>')
                        btn.attr('disabled', false)
                    }
                },
                error: function (error){
                    alert("Erro ao inativar movimento de expedição, tente novamente!")
                    btn.html('<i class="fa fa-trash" aria-hidden="true"></i>')
                    btn.attr('disabled', false)
                }
            })
        }else{
            return false
        }
    }

    $("#empresa-expedicao").on("change", async function(e){
        Vempresa =  Vempresa != '' ? Vempresa : $("#empresa-expedicao").find(":selected").val()
        if(this.value !== "0"){
            await povoarSetores().then( e => {
                $("#setor-expedicao").select2({
                    width: '100%',
                    placeholder: "Selecione o setor",
                    allowClear: true
                })
                $("#setor-expedicao").find('option').get(0).remove()
                $("#setor-expedicao").prepend(`<option selected disabled value="0">Selecione um setor</option>`)
            })

            
            if(Vsetor != ''){
                $("#setor-expedicao").find("option:contains('"+Vsetor+"')").attr("selected", true).change()
            }
            

            await povoarTipos().then( e => {
                $("#tipo-servico-expedicao").select2({
                    width: '100%',
                    placeholder: "Selecione o tipo de movimento",
                    allowClear: true
                })
                $("#tipo-servico-expedicao").find('option').get(0).remove()
                $("#tipo-servico-expedicao").prepend(`<option selected disabled value="0">Selecione um tipo de movimento</option>`)
            })

            
            if(Vservico != ''){
                $("#tipo-servico-expedicao").find("option:contains('"+Vservico+"')").attr("selected", true).change()
            }
            
            await povoarTransportadores().then(e => {
                $("#transportador-expedicao").select2({
                    width: '100%',
                    placeholder: "Selecione o transportador",
                    allowClear: true
                })
                $("#transportador-expedicao").find('option').get(0).remove()
                $("#transportador-expedicao").prepend(`<option selected disabled value="0">Selecione um transportador</option>`)
            })

            
            if(Vtransportador != ''){
                $("#transportador-expedicao").find("option:contains('"+Vtransportador+"')").attr("selected", true).change()
            }      
            

            if (Vempresa != ''){
                Vempresa = '';
            }
            
        }
    })

    async function povoarTransportadores(){
        $("#transportador-expedicao").empty()
        $("#transportador-expedicao").append(`<option value="0">Buscando transportadores para esta empresa...</option>`)
        $("#transportador-expedicao").select2({
            width: '100%',
            placeholder: "Buscando transportadores para esta empresa...",
            allowClear: true
        })

        let transportadores = await buscarTransportadores()

        if(transportadores) transportadores = JSON.parse(transportadores)

        if(transportadores?.length){
            transportadores.forEach( transportador => {
                $("#transportador-expedicao").append(`<option value="${transportador.id}">${transportador.razaoSocial}</option>`)
                $("#transportador-expedicao").find('option').get(0).remove()
                $("#transportador-expedicao").prepend(`<option selected disabled value="0">Selecione um transportador</option>`)
            })
        }
    }

    async function buscarTransportadores(){
        let empresa = $("#empresa-expedicao").val() ? $("#empresa-expedicao").val() : Vempresa
        let transportadores = await $.ajax({
            url: `<?= site_url('MovimentosEstoque/listarTransportadores/') ?>`,
            type: "POST",
            data: {
                idEmpresa:empresa
            },
            success: function (response){
                return response
            },
            error: function (error) {
                return false
            },
        })

        return transportadores
    }

    $("#cep-expedicao").blur(function(){
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
            try{
                $("#endereco-expedicao").val(dadosRetorno.logradouro);
                $("#bairro-expedicao").val(dadosRetorno.bairro);
                $("#cidade-expedicao").val(dadosRetorno.localidade);
                $("#uf-expedicao").val(dadosRetorno.uf);
            }catch(ex){}
        });
    })

    $('#cep-expedicao').mask('99999-999');

    async function povoarTipos(){
        $("#tipo-servico-expedicao").empty()
        $("#tipo-servico-expedicao").append(`<option value="0">Buscando tipos de movimento para esta empresa...</option>`)
        $("#tipo-servico-expedicao").select2({
            width: '100%',
            placeholder: "Buscando tipos de movimento para esta empresa...",
            allowClear: true
        })

        let tipos = await buscarTipos()

        if(tipos) tipos = JSON.parse(tipos)

        if(tipos?.length){
            tipos.forEach( tipo => {
                $("#tipo-servico-expedicao").append(`<option value="${tipo.id}">${tipo.nomeTipoMovimento}</option>`)
                $("#tipo-servico-expedicao").find('option').get(0).remove()
		        $("#tipo-servico-expedicao").prepend(`<option selected disabled value="0">Selecione um tipo de movimento</option>`)
            })
        }else{
            $("#tipo-servico-expedicao").find('option').get(0).remove()
            $("#tipo-servico-expedicao").prepend(`<option selected disabled value="0">Nenhum tipo de movimento encontrado</option>`)
            alert("Esta empresa não possui tipos de movimento ativos.")
        }
    }

    async function buscarTipos(){
        let empresa = $("#empresa-expedicao").val() ? $("#empresa-expedicao").val() : Vempresa
        let tipos = await $.ajax({
            url: `<?= site_url('MovimentosEstoque/listarTipoMovimento') ?>`,
            type: "POST",
            data: {
                idEmpresa:empresa
            },
            success: function (response){
                return response
            },
            error: function (error) {
                return false
            },
        })

        return tipos 
    }

    async function povoarSetores(){
        $("#setor-expedicao").empty()
        $("#setor-expedicao").prepend(`<option value="0">Buscando setores para esta empresa...</option>`)
        $("#setor-expedicao").select2({
            width: '100%',
            placeholder: "Buscando setores para esta empresa...",
            allowClear: true
        })

        let setores = await buscarSetores()

        if(setores) setores = JSON.parse(setores)

        if(setores?.length){
            setores.forEach(setor => {
                $("#setor-expedicao").append(`<option value="${setor.id}">${setor.nomeSetor}</option>`)
                $("#setor-expedicao").find('option').get(0).remove()
		        $("#setor-expedicao").prepend(`<option selected disabled value="0">Selecione um setor</option>`)
            })
        }else{
            $("#setor-expedicao").find('option').get(0).remove()
            $("#setor-expedicao").prepend(`<option selected disabled value="0">Nenhum setor encontrado</option>`)
            alert("Esta empresa não possui setores ativos.")
        }
    }

    async function buscarSetores(){
        let empresa = $("#empresa-expedicao").val() ? $("#empresa-expedicao").val() : Vempresa
        let setores = await $.ajax({
            url: `<?= site_url('MovimentosEstoque/buscarSetores') ?>`,
            type: "POST",
            data: {
                idEmpresa:empresa
            },
            success: function (response){
                return response
            },
            error: function (error) {
                return false
            },
        })

        return setores
    }

        $("#transportador-expedicao").select2({
            placeholder: "Selecione o transportador",
            allowClear: true,
            minimumInputLength: 3,
            language: "pt-BR",
            width: 'resolve'
        })

    async function listarEmpresasCad(){
            let empresasCad = await $.ajax ({
                                url: '<?= site_url('MovimentosEstoque/buscarEmpresas') ?>',
                                dataType: 'json',
                                type: 'GET',  
                                success: function(data){
                                    return data;
                                }           
                            })

        $('#empresa-expedicao').select2({
            placeholder: "Selecione a empresa",
            allowClear: true,
            language: "pt-BR",

        });

        if (empresasCad) {
            empresasCad.results.forEach(empresa => {
                $('#empresa-expedicao').append(`<option value="${empresa.id}">${empresa.text}</option>`)
            })
        }
    }
   
    $(document).ready(async function(){
        prepararTabela() 

        $('#pesqnome').append(`<option selected disabled value="">Buscando empresas...</option>`)
        let empresasBusca  = await $.ajax ({
                            url: '<?= site_url('MovimentosEstoque/buscarEmpresas') ?>',
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
                            }
                        });

        $('#pesqnome').select2({
            data: empresasBusca.results,
            placeholder: "Selecione a empresa",
            allowClear: true,
            language: "pt-BR",
            width: '100%'
            });
        
        $('#pesqnome').on('select2:select', function (e) {
            var data = e.params.data;
        });

        $('#pesqnome').find('option:selected').remove()
        $('#pesqnome').prepend(`<option selected disabled value="">Selecione a empresa</option>`)
    });

    function getWeekOfMonth(date) {
        let adjustedDate = date.getDate()+ date.getDay();
        let prefixes = ['0', '1', '2', '3', '4', '5'];
        return (parseInt(prefixes[0 | adjustedDate / 7])+1);
    }

    function formatarDados(dado){
        if (!dado){
            return false;
        } else {
            let semana1 = ''
            let semana2 = ''
            let semana3 = ''
            let semana4 = '' 
            let dia = ''
            let mes = ''
            let semana =''
            let responsavel = dado?.responsavel ? dado?.responsavel : '-'
            let orgao = dado?.tipoEmpresa ? dado?.tipoEmpresa : '-'
            if (orgao == '-'){
                orgao = dado?.tipo_empresa ? dado?.tipo_empresa : '-'
            }
            let cliente = dado?.cliente ? dado?.cliente : '-'
            let setor = dado?.setor ? dado?.setor : '-'
            let tipo = dado?.tipoMovimento ? dado?.tipoMovimento : '-'
            let volume = dado?.qutVolumes ? dado?.qutVolumes : '-'
            let totalGeral = dado?.valorTotal ? 'R$ '+ parseFloat(dado?.valorTotal).toFixed(2) : 'R$ -'
            let obs = dado?.observacao ? dado?.observacao : ''
            let transportadora = dado?.transportador ? dado?.transportador : '-'
            let cidade = dado?.cidade ? dado?.cidade : '-'
            let uf = dado?.uf ? dado?.uf : '-'
            let regiao = dado?.regiao ? dado?.regiao : '-'
            let id = dado?.id ? dado?.id : '-'
            let status = dado?.status ? dado?.status : '-'
            let empresa = dado?.empresa ? dado?.empresa : '-'
            let cep = dado?.cep ? dado?.cep : '-'
            let endereco = dado?.endereco ? dado?.endereco : '-'
            let bairro = dado?.bairro ? dado?.bairro : '-'
            let lancamento = dado?.dataCadastro ? dado?.dataCadastro : ''
            let data = dado?.dataCadastro ? new Date(dado?.dataCadastro) : '-'
            

            if(data != '-'){
                dia = new Intl.DateTimeFormat("pt-BR", {day: "numeric"}).format(data)
                mes = new Intl.DateTimeFormat("pt-BR", {month: "long"}).format(data) 
                mes = mes.substring(0,3);
                semana = 'Semana ' + (dado?.semana != '-' ? dado?.semana : getWeekOfMonth(data))
            } else {
                dia = '-'
                mes = '-'
                semana = '-'
            }

            if(semana =='Semana 1'){
                semana1 = (dado?.qutVolumes ? parseFloat(dado?.qutVolumes) : 0) + (dado?.qutVolumesAdc ? parseFloat(dado?.qutVolumesAdc) : 0)
            } else if(semana == 'Semana 2'){
                semana2 = (dado?.qutVolumes ? parseFloat(dado?.qutVolumes) : 0) + (dado?.qutVolumesAdc ? parseFloat(dado?.qutVolumesAdc) : 0)
            } else if(semana == 'Semana 3'){
                semana3 = (dado?.qutVolumes ? parseFloat(dado?.qutVolumes) : 0) + (dado?.qutVolumesAdc ? parseFloat(dado?.qutVolumesAdc) : 0)
            } else if(semana == 'Semana 4'){
                semana4 = (dado?.qutVolumes ? parseFloat(dado?.qutVolumes) : 0) + (dado?.qutVolumesAdc ? parseFloat(dado?.qutVolumesAdc) : 0)
            }

            return{
                empresa:empresa,
                dataMovimento: data,
                responsavel:responsavel,
                dia:dia,
                semana:semana,
                mes:mes,
                orgao:orgao,
                cliente:cliente,
                setor:setor,
                tipo:tipo,
                semana1:semana1,
                semana2:semana2,
                semana3:semana3,
                semana4:semana4,
                volume:volume,
                totalGeral: totalGeral,
                obs:obs,
                transportadora:transportadora,
                cidade:cidade,
                uf:uf,
                regiao:regiao,
                id:id,
                status:status,
                cep,
                endereco,
                bairro,
                lancamento
            }
        }
    }

    function prepararTabela(){
        const tdSel = document.querySelectorAll('td.dataTables_empty');
        tdSel.forEach((td) => {
            if (td.textContent == 'Nenhum movimento a ser listado'){
                td.innerHTML = '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>'
            }
        });
        $.ajax({
            url: '<?= site_url('MovimentosEstoque/listarMovimentos') ?>',
            type: 'POST',
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    if (data?.dados?.length){
                        let movimentos = data?.dados.flatMap(dado => {
                            if (dado?.status == "Ativo"){
                                return formatarDados(dado) 
                            }
                            return []
                        })
                        
                        for( var i = 0; i < movimentos.length; i++){ 
                            if ( movimentos[i] == 0) { 
                                movimentos.splice(i, 1); 
                                i--
                            }
                        }

                        tabelaMovimentos.clear().draw();
                        tabelaMovimentos.rows.add(movimentos).draw();
                        tabelaMovimentos.columns.adjust().draw();
                        $('#alert').hide();
                    }
                    
                }else{
                    alert('Não existem movimentações cadastradas')
                    tabelaMovimentos.clear().draw();

                }
            }
        })
    }

    $('#tipo-busca-expedicao').change(function(){ 
        $('#cep-expedicao').val('');
        $('#endereco-expedicao').val('');
        $('#uf-expedicao').val('');
        $('#bairro-expedicao').val('');
        $('#cidade-expedicao').val('');
        $('#tipo-orgao-expedicao').val('');

        if ($(this).val() == 1){
            $('#cliente-expedicaoDoc').val('');
            $('#cliente-expedicaoDoc').attr('disabled', false);
            $('#cliente-expedicaoId').val('');
            $('#cliente-expedicaoId').attr('disabled', false);

            $('#divDocCliente').hide();
            $('#divBtnPesquisaCliente').hide();
            $('#divIdCliente').hide();
            $("#divNomeCliente").show();
            $("#cliente-expedicao").select2({
                ajax: {
                    url: '<?= site_url('MovimentosEstoque/buscar_cliente') ?>',
                    dataType: 'json',
                    delay: 1000,
                    type: 'GET',
                    data: function (params) {
                        return {
                            q: params.term,
                            tipoBusca: 'nome',
                            BuscarTodos: true
                        };
                    },
                },
                placeholder: "Digite o nome do cliente",
                allowClear: true,
                minimumInputLength: 3,
                language: "pt-BR",
                width: 'resolve',
            })
        }else if($(this).val() == 2){
            $('#cliente-expedicao').val('');
            $('#cliente-expedicaoId').val('');
            $('#cliente-expedicaoId').attr('disabled', false);
            $("#divNomeCliente").hide();
            $('#divIdCliente').hide();
            $('#divBtnPesquisaCliente').show();
            $('#divDocCliente').show();

            $("#cliente-expedicaoDoc").inputmask({
            mask: ["999.999.999-99", "99.999.999/9999-99"],
            keepStatic: true,
            placeholder: " ",
            });
        }else if($(this).val() == 0){
            $('#cliente-expedicao').val('');
            $('#cliente-expedicaoDoc').val('');
            $('#cliente-expedicaoDoc').attr('disabled', false);
            $("#divNomeCliente").hide();
            $('#divBtnPesquisaCliente').show();
            $('#divDocCliente').hide();
            $('#divIdCliente').show();
        }
    })

    idClienteDoc = ''
    $('#btnPesquisaClienteDoc').click(function(){
        if ($('#divDocCliente').is(':visible')){
            $('#cliente-expedicaoDoc').attr('disabled', true)
            $('#btnPesquisaClienteDoc')
            .html('<i class="fa fa-spinner fa-spin"></i>')
            .attr('disabled', true)

            var documento = $('#cliente-expedicaoDoc').val();
            if (documento != ''){
                $.ajax({
                    url: '<?= site_url('MovimentosEstoque/buscar_cliente') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        q: documento,
                        tipoBusca: 'cpfCnpj',
                        BuscarTodos: true
                    },
                    success: function(data){
                        if(data.results.length){
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#cliente-expedicaoDoc').attr('disabled', false)
                            $('#cliente-expedicaoDoc').inputmask('remove')
                            $('#cliente-expedicaoDoc').val(data.results[0].text)
                            $('#cliente-expedicaoDoc').attr('disabled', true)
                            idClienteDoc = data.results[0].id
                            $('#cep-expedicao').val(data.results[0].cep)
                            $('#endereco-expedicao').val(data.results[0].endereco)
                            $('#uf-expedicao').val(data.results[0].uf)
                            $('#bairro-expedicao').val(data.results[0].bairro)
                            $('#cidade-expedicao').val(data.results[0].cidade)
                            $('#tipo-orgao-expedicao').val(data.results[0].orgao)
                            AtualizarMovimento(data.results[0].status)
                        }else{
                            alert('Cliente não ativo ou não encontrado')
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#cliente-expedicaoDoc').val('')
                            $('#cliente-expedicaoDoc').attr('disabled', false)
                        }
                    }
                })
            }else{
                alert('Digite o cpf ou cnpj do cliente')
                $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                $('#cliente-expedicaoDoc').attr('disabled', false)

            }
        }else{
            $('#btnPesquisaClienteDoc')
            .html('<i class="fa fa-spinner fa-spin"></i>')
            .attr('disabled', true)
            var id = $('#cliente-expedicaoId').val();
            if (id != ''){
                $.ajax({
                    url: '<?= site_url('MovimentosEstoque/buscar_cliente') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        q: id,
                        tipoBusca: 'id',
                        BuscarTodos: true
                    },
                    success: function(data){
                        if(data.results.length){
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#cliente-expedicaoId').attr('disabled', false)
                            $('#cliente-expedicaoId').val(data.results[0].text)
                            $('#cliente-expedicaoId').attr('disabled', true)
                            idClienteDoc = data.results[0].id
                            $('#cep-expedicao').val(data.results[0].cep)
                            $('#endereco-expedicao').val(data.results[0].endereco)
                            $('#uf-expedicao').val(data.results[0].uf)
                            $('#bairro-expedicao').val(data.results[0].bairro)
                            $('#cidade-expedicao').val(data.results[0].cidade)
                            $('#tipo-orgao-expedicao').val(data.results[0].orgao)
                            AtualizarMovimento(data.results[0].status);
                        }else{
                            alert('Cliente não ativo ou não encontrado')
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#cliente-expedicaoId').val('')
                            $('#cliente-expedicaoId').attr('disabled', false)
                        }
                    }
                })
            }else{
                alert('Digite o id do cliente')
                $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                $('#cliente-expedicaoId').attr('disabled', false)
            }
        }
    })

    $('#btnLimparPesquisaClienteDoc').click(function(){
        $('#cep-expedicao').val('')
        $('#endereco-expedicao').val('')
        $('#uf-expedicao').val('')
        $('#bairro-expedicao').val('')
        $('#cidade-expedicao').val('')
        $('#tipo-orgao-expedicao').val('')
        if ($('#divDocCliente').is(':visible')){
            $('#cliente-expedicaoDoc').val('')
            $('#cliente-expedicaoDoc').attr('disabled', false)
            $("#cliente-expedicaoDoc").inputmask({
                mask: ["999.999.999-99", "99.999.999/9999-99"],
                keepStatic: true,
                placeholder: " ",
                });
        }else{
            $('#cliente-expedicaoId').val('')
            $('#cliente-expedicaoId').attr('disabled', false)
        }
    })

    $('#cliente-expedicao').on('select2:select', function (e) {
        $('#cep-expedicao').val(e.params.data.cep)
        $('#endereco-expedicao').val(e.params.data.endereco)
        $('#uf-expedicao').val(e.params.data.uf)
        $('#bairro-expedicao').val(e.params.data.bairro)
        $('#cidade-expedicao').val(e.params.data.cidade)
        $('#tipo-orgao-expedicao').val(e.params.data.orgao)
        AtualizarMovimento(e.params.data.status);
    });

    $('#pesquisacliente').click(async function(e){
        var empresaPesq = $('#pesqnome').val();
        e.preventDefault();
        if (!empresaPesq || empresaPesq == '' || empresaPesq == null){
            alert("Selecione uma empresa antes de realizar a pesquisa e tente novamente");
            return;
        } else {
            var empresa = $('#pesqnome').val();
        }
        var dataInicial = $('#dataInicial-tabela').val();
        var dataFinal = $('#dataFinal-tabela').val();

        if ( dataInicial == '' && dataFinal == '') {
            alert("Preencha as datas antes de realizar a pesquisa e tente novamente!");
            return;
        } else if (dataInicial == '') {
            alert("Insira uma data inicial válida antes de realizar a pesquisa e tente novamente!");
            return;
        } else if (dataFinal == '') {
            alert("Insira uma data final válida antes de realizar a pesquisa e tente novamente!");
            return;
        } else {
            if ((new Date(dataFinal)) < (new Date(dataInicial))) {
                alert("A data final não pode ser anterior à data inicial.");
                return;
            } else {
                 // Carregando
                 $('#pesquisacliente')
                        .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
                        .attr('disabled', true);
                await $.ajax({
                   
                    url: '<?= site_url('MovimentosEstoque/listarMovimentos') ?>',
                    type: 'POST',
                    data: {
                        idEmpresa: empresa,
                        dataInicial,
                        dataFinal
                    },
                    dataType: 'json',
                    success: function(data){
                       
                        if (data.status === 200){
                            if (data?.dados?.length){
                                let movimentos = data?.dados.flatMap(addItemMovimentodado => {
                                    if (dado?.status == "Ativo"){
                                        let semana1 = ''
                                        let semana2 = ''
                                        let semana3 = ''
                                        let semana4 = '' 
                                        let dia = ''
                                        let mes = ''
                                        let semana =''
                                        let responsavel = dado?.responsavel ? dado?.responsavel : '-'
                                        let orgao = dado?.tipoEmpresa ? dado?.tipoEmpresa : '-'
                                        let cliente = dado?.cliente ? dado?.cliente : '-'
                                        let setor = dado?.setor ? dado?.setor : '-'
                                        let tipo = dado?.tipoMovimento ? dado?.tipoMovimento : '-'
                                        let volume = dado?.qutVolumes ? dado?.qutVolumes : '-'
                                        let totalGeral = dado?.valorTotal ? 'R$ '+ parseFloat(dado?.valorTotal).toFixed(2) : 'R$ -'
                                        let obs = dado?.observacao ? dado?.observacao : ''
                                        let transportadora = dado?.transportador ? dado?.transportador : '-'
                                        let cidade = dado?.cidade ? dado?.cidade : '-'
                                        let uf = dado?.uf ? dado?.uf : '-'
                                        let regiao = dado?.regiao ? dado?.regiao : '-'
                                        let id = dado?.id ? dado?.id : '-'
                                        let status = dado?.status ? dado?.status : '-'
                                        let empresa = dado?.empresa ? dado?.empresa : '-'
                                        let cep = dado?.cep ? dado?.cep : '-'
                                        let endereco = dado?.endereco ? dado?.endereco : '-'
                                        let bairro = dado?.bairro ? dado?.bairro : '-'
                                        let lancamento = dado?.dataCadastro ? dado?.dataCadastro : ''


                                        let data = dado?.dataCadastro ? new Date(dado?.dataCadastro) : '-'
                                        if(data != '-'){
                                            dia = new Intl.DateTimeFormat("pt-BR", {day: "numeric"}).format(data)
                                            mes = new Intl.DateTimeFormat("pt-BR", {month: "long"}).format(data) 
                                            mes = mes.substring(0,3);
                                            semana = 'Semana ' + dado?.semana
                                        } else {
                                            dia = '-'
                                            mes = '-'
                                            semana = '-'
                                        }

                                        if(dado?.semana == 1){
                                            semana1 = (dado?.qutVolumes ? parseFloat(dado?.qutVolumes) : 0) + (dado?.qutVolumesAdc ? parseFloat(dado?.qutVolumesAdc) : 0)
                                        } else if(dado?.semana == 2){
                                            semana2 = (dado?.qutVolumes ? parseFloat(dado?.qutVolumes) : 0) + (dado?.qutVolumesAdc ? parseFloat(dado?.qutVolumesAdc) : 0)
                                        } else if(dado?.semana == 3){
                                            semana3 = (dado?.qutVolumes ? parseFloat(dado?.qutVolumes) : 0) + (dado?.qutVolumesAdc ? parseFloat(dado?.qutVolumesAdc) : 0)
                                        } else if(dado?.semana == 4){
                                            semana4 = (dado?.qutVolumes ? parseFloat(dado?.qutVolumes) : 0) + (dado?.qutVolumesAdc ? parseFloat(dado?.qutVolumesAdc) : 0)
                                        }
                                        
                                        return{
                                            empresa:empresa,
                                            dataMovimento: data,
                                            responsavel:responsavel,
                                            dia:dia,
                                            semana:semana,
                                            mes:mes,
                                            orgao:orgao,
                                            cliente:cliente,
                                            setor:setor,
                                            tipo:tipo,
                                            semana1:semana1,
                                            semana2:semana2,
                                            semana3:semana3,
                                            semana4:semana4,
                                            volume:volume,
                                            totalGeral: totalGeral,
                                            obs:obs,
                                            transportadora:transportadora,
                                            cidade:cidade,
                                            uf:uf,
                                            regiao:regiao,
                                            id:id,
                                            status:status,
                                            cep,
                                            endereco,
                                            bairro,
                                            lancamento
                                        }
                                    } 
                                    return [];
                                })

                                for( var i = 0; i < movimentos.length; i++){ 
                                    if ( movimentos[i] === 0) { 
                                        movimentos.splice(i, 1); 
                                    }
                                }

                                tabelaMovimentos.clear().draw();
                                tabelaMovimentos.rows.add(movimentos).draw();
                                $("#esconde-table").removeAttr("style")
                                $('#alert').hide();
                            }
                            
                        }else{
                            alert('Não existem movimentações para essa empresa')
                            tabelaMovimentos.clear().draw();

                        }
                    }
                })
            }
        }


        await $.ajax({
            url: '<?= site_url('MovimentosEstoque/listarTodasEmpresas') ?>',
            dataType: 'json',
            type: 'POST',
            success: function (response){
                if (response?.results.length > 0){
                    $('#empresa-expedicao').empty()
                    $('#empresa-expedicao').prepend(`<option value="0">Selecione a empresa</option>`)
                    response?.results.forEach(element => {
                        $('#empresa-expedicao').append(`<option value="${element.id}">${element.text}</option>`)
                    });
                }

            }
        })

        await $.ajax({
            url: '<?= site_url('MovimentosEstoque/buscarSetores') ?>',
            dataType: 'json',
            type: 'POST',
            data: {
                "idEmpresa": empresa
            },
            success: function (response){
                if (response?.results.length > 0){
                    $('#setor-expedicao').empty()
                    $('#setor-expedicao').prepend(`<option value="0">Selecione o setor</option>`)
                    response?.results.forEach(element => {
                        $('#setor-expedicao').append(`<option value="${element.id}">${element.text}</option>`)
                    });
                } 
            }
        })

        await $.ajax({
            url: '<?= site_url('MovimentosEstoque/listarTipoMovimento') ?>',
            dataType: 'json',
            type: 'POST',
            data: {
                "idEmpresa": empresa
            }, 
            success: function (response){
                $('#tipo-servico-expedicao').empty()
                if (response?.results.length > 0){
                    $("#tipo-servico-expedicao").empty()
                    $('#tipo-servico-expedicao').prepend(`<option value="0">Selecione o tipo de movimento</option>`)
                    response?.results.forEach(element => {
                        $('#tipo-servico-expedicao').append(`<option value="${element.id}">${element.text}</option>`)
                    });
                }  
            }
        })

        

        $('#pesquisacliente')
        .html('Pesquisar')
        .attr('disabled', false);


    })
    
    $(document).ready(function(){
        $('#selectEmpresa').select2({
                ajax: {
                    url: '<?= site_url('setores/buscarEmpresas') ?>',
                    dataType: 'json',
                    delay: 1000,
                    type: 'GET',
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                },
                placeholder: "Selecione a empresa",
                allowClear: true,
                minimumInputLength: 3,
                language: "pt-BR",
        });

        $('#selectEmpresaCadTransportador').select2({
                ajax: {
                    url: '<?= site_url('setores/buscarEmpresas') ?>',
                    dataType: 'json',
                    delay: 1000,
                    type: 'GET',
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                },
                placeholder: "Selecione a empresa",
                allowClear: true,
                minimumInputLength: 3,
                language: "pt-BR",
        });
    });

    function AtualizarMovimento(status){
        $('#movimento-item').empty();
        $("#movimento-item").append(`<option value="" data-default selected>Selecione um movimento</option>`)
        $("#movimento-item").append(`<option value="1">Entrada</option>`)
        if(status == 1)
            $("#movimento-item").append(`<option value="0">Saída</option>`)
    }

    function cadastrarSetor(botao){
        $('#modalCadastrar').modal('show')
        headerModal = document.getElementById('headerModalCadastro');
        headerModal.innerHTML = '<h3><?=lang("novo_setor")?></h3>';
    }

    function cadastrarTipoMovimento(botao){
        $('#modalCadastrar').modal('show')
        headerModal = document.getElementById('headerModalCadastro');
        headerModal.innerHTML = '<h3><?=lang("novo_tipo_movimento")?></h3>';
    }

    function cadastrarTransportador(botao){
        $('#modalCadTransportador').modal('show')
    }

    $('#btnSalvarCadastro').click(function(){
        botao = $('#btnSalvarCadastro');
        nome = $('#nome').val();
        idEmpresa = $('#selectEmpresa').val();
       
        if (nome == ''){
            alert('Preencha o nome')
        }else if (idEmpresa == null){
            alert('Selecione a empresa')
        }else{
            botao.html('<i class="fa fa-spin fa-spinner"></i>');
            botao.attr('disabled', true);

            if (headerModal.innerHTML == '<h3><?=lang("novo_setor")?></h3>'){
                $.ajax({
                    url: '<?= site_url('setores/inserirSetor') ?>',
                    type: 'POST',
                    data: { idEmpresa: idEmpresa,
                            nome: nome},
                    dataType: 'json',
                    success: function(data){
                        if (data.status === 200){
                            alert('Cadastro realizado!');
                            if(idEmpresa == $('#empresa-expedicao').val()){
                                povoarSetores()
                            }
                            $('#nome').val('').trigger('change');
                            $('#modalCadastrar').modal('hide')
                            $('#selectEmpresa').val('').trigger('change');
                            botao.html('Salvar');
                            botao.attr('disabled', false);
                        }else{
                            alert('Nome inválido ou já está em uso');
                            botao.html('Salvar');
                            botao.attr('disabled', false);
                        }
                    },
                    error: function(){
                        alert('Erro ao cadastrar setor')
                        botao.html('Salvar');
                        botao.attr('disabled', false);
                    }
                })
            }else if (headerModal.innerHTML == '<h3><?=lang("novo_tipo_movimento")?></h3>'){
                $.ajax({
                    url: '<?= site_url('tipoMovimento/inserirTipoMovimento') ?>',
                    type: 'POST',
                    data: { idEmpresa: idEmpresa,
                            nome: nome},
                    dataType: 'json',
                    success: function(data){
                        if (data.status === 200){
                            alert('Cadastro realizado!');
                            if(idEmpresa == $('#empresa-expedicao').val()){
                                povoarTipos()
                            }
                            $('#nome').val('').trigger('change');
                            $('#modalCadastrar').modal('hide')
                            $('#selectEmpresa').val('').trigger('change');
                            botao.html('Salvar');
                            botao.attr('disabled', false);
                        }else{
                            alert('Nome inválido ou já está em uso');
                            botao.html('Salvar');
                            botao.attr('disabled', false);
                        }
                    },
                    error: function(){
                        alert('Erro ao cadastrar tipo de movimento')
                        botao.html('Salvar');
                        botao.attr('disabled', false);
                    }
                })
            }
        }
    })

    $('#btnSalvarCadastroTransportador').click(function (){
        botao = $('#btnSalvarCadastroTransportador');
        nome = $('#nomeTransportador').val();
        cpfCnpj = $('#cpfCnpjTransportador').val();
        cep = $('#cepTransportador').val();
        rua = $('#ruaTransportador').val();
        bairro = $('#bairroTransportador').val();
        cidade = $('#cidadeTransportador').val();
        uf = $('#ufTransportador').val();
        complemento = $('#complementoTransportador').val();
        idEmpresa = $('#selectEmpresaCadTransportador').val();

        if (nome == ''){
            alert('Preencha o nome')
        }else if (cpfCnpj == ''){
            alert('Preencha o CPF/CNPJ')
        }else if (cep == ''){
            alert('Preencha o CEP')
        }else if (rua == ''){
            alert('Preencha a rua')
        }else if (bairro == ''){
            alert('Preencha o bairro')
        }else if (cidade == ''){
            alert('Preencha a cidade')
        }else if (uf == ''){
            alert('Preencha o UF')
        }else if (idEmpresa == null){
            alert('Selecione a empresa')
        }else{
            botao.html('<i class="fa fa-spin fa-spinner"></i>');
            botao.attr('disabled', true);

            $.ajax({
                url: '<?= site_url('transportadores/inserirTransportador') ?>',
                type: 'POST',
                data: { nome: nome,
                        cpfCnpj: cpfCnpj,
                        cep:cep,
                        rua:rua,
                        bairro:bairro,
                        cidade:cidade,
                        uf:uf,
                        complemento:complemento,
                        idEmpresa: idEmpresa},
                dataType: 'json',
                success: function(data){
                    if (data.status === 200){
                        alert('Cadastro realizado!')
                        if (idEmpresa == $('#empresa-expedicao').val()){
                            povoarTransportadores()
                        }
                        $('#modalCadTransportador').modal('hide')
                        $('#nomeTransportador').val('');
                        $('#cpfCnpjTransportador').val('');
                        $('#cepTransportador').val('');
                        $('#ruaTransportador').val('');
                        $('#bairroTransportador').val('');
                        $('#cidadeTransportador').val('');
                        $('#ufTransportador').val(0);
                        $('#complementoTransportador').val('');
                        $('#selectEmpresaCadTransportador').val('').trigger('change');
                        botao.html('Salvar');
                        botao.attr('disabled', false);
                    }else{
                        alert('Nome inválido ou já está em uso');
                        botao.html('Salvar');
                        botao.attr('disabled', false);
                    }
                },
                error: function(){
                    alert('Erro ao cadastrar transportador')
                    botao.html('Salvar');
                    botao.attr('disabled', false);
                }
            }) 
        }
    })

    $('#botao-adicionar-item').click(function (){
        referencia = $('#referencia-itens').val();
        terminal = $('#terminal-itens').text();
        quantidade = $('#quantidade-itens').val();
        valor = formatValorInserir($('#valor-itens').val());
        movimento = $('#movimento-item').val();
        nomeMovimento = $('#movimento-item option:selected').text();
        if (referencia == '' || quantidade == '' || valor == '' || movimento == null){
            alert('Preencha todos os campos obrigatórios');
        }else{
            tabelaItensMovimento.rows.add([
                {referencia: referencia, terminal: terminal, quantidade: quantidade, valor: valor, movimento: nomeMovimento, idMovimento: movimento }
            ]).draw();
            
            $('#referencia-itens').val('');
            $('#referencia-itens').attr('readonly', false);
            $('#terminal-itens').val('').trigger('change');
            $('#terminal-itens').text('').trigger('change');
            $('#quantidade-itens').val('');
            $('#valor-itens').val('');
        }
    })

    function visualizarItensMovimento(botao, id, event){
        event.preventDefault();
        $('#acoes').css('width', 'max-content')
        btn = $(botao);
        btn.html('<i class="fa fa-spin fa-spinner"></i>');
        btn.attr('disabled', true);
        $.ajax({
            url: '<?= site_url('MovimentosEstoque/listarItensMovimento') ?>',
            type: 'POST',
            data: { idMovimento: id},
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    tabelaItensDoMovimento.clear().draw();
                    tabelaItensDoMovimento.rows.add(data.results).draw();
                    $('#modalItensMovimento').modal('show');
                    btn.html('<i class="fa fa-list-ul"></i>');
                    btn.attr('disabled', false);
                }else{
                    alert(data.results.mensagem)
                    btn.html('<i class="fa fa-list-ul"></i>');
                    btn.attr('disabled', false);
                }
            },
            error: function(){
                alert('Erro ao visualizar itens do movimento. Tente novamente')
                btn.html('<i class="fa fa-list-ul"></i>');
                btn.attr('disabled', false);
            }
        })
    }

    function abrirAddItemMovimento(botao, id, event){
        event.preventDefault();
        $('#divSerialCad').attr('hidden', false);
        $('#divSerialEdit').attr('hidden', true);
        $('#divStatusItem').attr('hidden', true);
        $('#modalAddItemMovimento').modal('show');
        $('#nome-modal-header').html('<?=lang("adicionar_item_movimento")?>');
        $('#btnSalvarAddItemMovimento').data('id', id);
        $('#addItemMovimento').val(1);
    }

    $('#modalAddItemMovimento').on('hidden.bs.modal', function () {
        $('#addItemReferencia').val('');
        $('#addItemTerminal').val('').trigger('change');
        $('#addItemTerminal').text('').trigger('change');
        $('#addItemQuantidade').val('');
        $('#addItemValor').val('');
        $('#editItemTerminal').val('').trigger('change');
        $('#editItemTerminal').text('').trigger('change');
        $('#addItemMovimento').val(1).trigger('change');
        $('#addItemReferencia').attr('readonly', false);
    })

    $('#formAddItemMovimento').submit(function(e){
        e.preventDefault();
        btn = $('#btnSalvarAddItemMovimento');


        idMovimento = $('#btnSalvarAddItemMovimento').data('id');
        idItem = $('#btnSalvarAddItemMovimento').data('idItem');
        referencia = $('#addItemReferencia').val();
        terminal = $('#addItemTerminal').text();
        quantidade = $('#addItemQuantidade').val();
        valor = formatValorInserir($('#addItemValor').val());
        movimento = $('#addItemMovimento').val();
        status = $('#editStatusItem').val();

        if ($('#editItemTerminal').val()){
            terminalEdit = $('#editItemTerminal').text();
        }else if ($('#addItemTerminal').val()){
            terminalEdit = $('#addItemTerminal').text();
        }else{
            terminalEdit = '';
        }

        if ($('#nome-modal-header').html() == '<?=lang("adicionar_item_movimento")?>'){
            btn.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i> Salvando...');

            $.ajax({
                url: '<?= site_url('MovimentosEstoque/cadastrarItemMovimento') ?>',
                type: 'POST',
                data: { idMovimento: idMovimento,
                        referencia: referencia,
                        idTerminal: terminal,
                        qutUnitaria: quantidade,
                        valorUnitario: valor,
                        movimento: movimento
                    },
                dataType: 'json',
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        btn.attr('disabled', false).html('Salvar');
                        $('#modalAddItemMovimento').modal('hide');
                    }else if(data.status === 400){
                        alert('Verifique os campos e tente novamente');
                        btn.attr('disabled', false).html('Salvar');
                    }else{
                        alert(data.dados.mensagem)
                        btn.attr('disabled', false).html('Salvar');
                    }
                },
                error: function(){
                    alert('Erro ao adicionar item ao movimento. Tente novamente')
                    btn.attr('disabled', false).html('Salvar');
                }
            })
        }else{
            btn.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i> Editando...');

            $.ajax({
                url: '<?= site_url('MovimentosEstoque/editarItemMovimento') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    idItem: idItem,
                    idMovimento: idMovimento,
                    referencia: referencia,
                    idTerminal: terminalEdit,
                    qutUnitaria: quantidade,
                    valorUnitario: valor,
                    movimento: movimento,
                    status: status
                },
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        $('#modalAddItemMovimento').modal('hide');
                        btn.attr('disabled', false).html('Salvar');
                        atualizaTabelaItensMovimento(idMovimento);
                        
                    }else if(data.status === 400){
                        alert('Verifique os campos e tente novamente');
                        btn.attr('disabled', false).html('Salvar');
                    }else{
                        alert(data.dados.mensagem)
                        btn.attr('disabled', false).html('Salvar');
                    }
                },
                error: function(){
                    alert('Erro ao editar item do movimento')
                    btn.attr('disabled', false).html('Salvar');
                }
            });
        }

    })

    async function abrirEditarItemMovimento(botao, idItem, idMovimento, referencia, terminal, quantidade, movimento, valorUnitario, status, event){
        event.preventDefault();
        btn = $(botao);
        btn.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');

        if (movimento == 'Entrada'){
            movimento = 1;
        }else{
            movimento = 0;
        }

        if (status == 'Ativo'){
            status = 1;
        }else{
            status = 0;
        }

        if (terminal){
            let seriaisEdit  = await $.ajax ({
                                url: '<?= site_url('MovimentosEstoque/buscarSeriais') ?>',
                                dataType: 'json',
                                delay: 1000,
                                type: 'GET',
                                data: {q: terminal},
                                error: function(){
                                    alert('Erro ao buscar seriais, tente novamente');
                                    btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
                                }
                            });

            $('#editItemTerminal').select2({
                data: seriaisEdit.results,
                placeholder: "Selecione o serial",
                allowClear: true,
                language: "pt-BR",
                width: '100%'
                });
            
            $('#editItemTerminal').on('select2:select', function (e) {
                var data = e.params.data;
            });

            $('#addItemReferencia').attr('readonly', true);
        }else{
            $('#editItemTerminal').select2({
                ajax: {
                    url: '<?= site_url('MovimentosEstoque/buscarSeriais') ?>',
                    dataType: 'json',
                    delay: 1000,
                    type: 'GET',
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },  
                },
                placeholder: 'Selecione o serial',
                allowClear: true,
                language: "pt-BR",
                minimumInputLength: 3,
                width: '100%',
            });
        }   

        if (valorUnitario.includes('.') && !valorUnitario.includes(',')){
            valorUnitario = valorUnitario.replace('.', ',');
        }

        $('#btnSalvarAddItemMovimento').data('id', idMovimento);
        $('#btnSalvarAddItemMovimento').data('idItem', idItem);
        $('#divSerialCad').attr('hidden', true);
        $('#divSerialEdit').attr('hidden', false);
        $('#divStatusItem').attr('hidden', false);
        $('#editStatusItem').val(status).trigger('change');
        /* $('#editItemTerminal').val(terminal).trigger('change'); */
        $('#addItemReferencia').val(referencia);
        $('#addItemQuantidade').val(quantidade);
        $('#addItemValor').val(valorUnitario);
        $('#addItemMovimento').val(movimento).trigger('change');
        $('#nome-modal-header').html('<?=lang("editar_item_movimento")?>');
        btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
        $('#modalAddItemMovimento').modal('show');
    }

    function removerItemMovimento(botao, id, idMovimento, event){
        event.preventDefault();
        if (confirm('Deseja realmente remover este item?')){
            btn = $(botao);
            btn.html('<i class="fa fa-spin fa-spinner"></i>');
            btn.attr('disabled', true);
            $.ajax({
                url: '<?= site_url('MovimentosEstoque/removerItemMovimento') ?>',
                type: 'POST',
                data: { idItem: id },
                dataType: 'json',
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        btn.html('<i class="fa fa-trash"></i>');
                        btn.attr('disabled', false);
                        atualizaTabelaItensMovimento(idMovimento);
                    }else{
                        alert(data.dados.mensagem)
                        btn.html('<i class="fa fa-trash"></i>');
                        btn.attr('disabled', false);
                    }
                },
                error: function(){
                    alert('Erro ao remover item do movimento, tente novamente')
                    btn.html('<i class="fa fa-trash"></i>');
                    btn.attr('disabled', false);
                }
            })
        }else{
            return false;
        }
    }

    function excluirItemTabela(botao){
        tabelaItensMovimento.row(botao.parentNode.parentNode).remove().draw();
    }

    function formatValorInserir(value) {
		value = value.replace('.', '');
		value = value.replace(',', '.');
		return value;	
	}

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
    $("#cpfCnpjTransportador").inputmask({
        mask: ["999.999.999-99", "99.999.999/9999-99"],
        keepStatic: true,
        placeholder: " ",
    });

    $("#cepTransportador").inputmask({
        mask: ["99999-999"],
        keepStatic: true,
        placeholder: " ",
    });

    $("#cepTransportador").blur(function(){
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#ruaTransportador").val(dadosRetorno.logradouro);
				$("#bairroTransportador").val(dadosRetorno.bairro);
				$("#cidadeTransportador").val(dadosRetorno.localidade);
				$("#ufTransportador").val(dadosRetorno.uf);
            }catch(ex){
                alert("CEP não encontrado.");
            }

        }).fail(function() {
            alert("CEP não encontrado.");
        });
    })
    
    document.addEventListener('keydown', function(event) {
        var key = event.which || event.keyCode;
        if (key === 13) {
          event.preventDefault();
        }
    });

    $('#terminal-itens').select2({
        ajax: {
            url: '<?= site_url('MovimentosEstoque/buscarSeriais') ?>',
            dataType: 'json',
            delay: 1000,
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term
                };
            },

        },
        placeholder: 'Selecione o serial',
        allowClear: true,
        language: "pt-BR",
        minimumInputLength: 3,
        width: '100%',
    });

    $('#addItemTerminal').select2({
        ajax: {
            url: '<?= site_url('MovimentosEstoque/buscarSeriais') ?>',
            dataType: 'json',
            delay: 1000,
            type: 'GET',
            data: function (params) {
                return {
                    q: params.term
                };
            },

        },
        placeholder: 'Selecione o serial',
        allowClear: true,
        language: "pt-BR",
        minimumInputLength: 3,
        width: '100%',
    });

    $('#terminal-itens').on('select2:select', function (e) {
        var data = e.params.data;
        var marca = data.marca;
        var modelo = data.modelo;
        $('#referencia-itens').val(marca + '-' + modelo);
        $('#referencia-itens').attr('readonly', true);
    });

    $('#addItemTerminal').on('select2:select', function (e) {
        var data = e.params.data;
        var marca = data.marca;
        var modelo = data.modelo;
        $('#addItemReferencia').val(marca + '-' + modelo);
        $('#addItemReferencia').attr('readonly', true);
    });

    $('#addItemTerminal').on('select2:clear', function (e) {
        $('#addItemReferencia').val('');
        $('#addItemReferencia').attr('readonly', false);
    })

    $('#terminal-itens').on('select2:clear', function (e) {
        $('#referencia-itens').val('');
        $('#referencia-itens').attr('readonly', false);
    });

    $('#editItemTerminal').on('select2:clear', function (e) {
        $('#addItemReferencia').val('');
        $('#addItemReferencia').attr('readonly', false);

        $('#divSerialEdit').attr('hidden', true);
        $('#divSerialCad').attr('hidden', false);
    });

    $('#editItemTerminal').on('select2:select', function (e) {
        var data = e.params.data;
        var marca = data.marca;
        var modelo = data.modelo;
        $('#addItemReferencia').val(marca + '-' + modelo);
        $('#addItemReferencia').attr('readonly', true);
    });

    function atualizaTabelaItensMovimento(id){
        $.ajax({
            url: '<?= site_url('MovimentosEstoque/listarItensMovimento') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idMovimento: id
            },
            success: function(data){
                if (data.status === 200){
                    tabelaItensDoMovimento.clear().draw();
                    tabelaItensDoMovimento.rows.add(data.results).draw();
                }else{
                    alert(data.results.mensagem)
                }
            },
            error: function(){
                alert('Erro ao visualizar itens do movimento')
            }
        });
    }

    function importarItensExcel(event) {
        event.preventDefault();
        const fileInput = document.getElementById('arquivoItens');
        const file = fileInput.files[0];
        
        if (!file) {
            alert('Por favor, selecione um arquivo.');
            return;
        }

        const validExtensions = ['.xls', '.xlsx'];
        const fileExtension = '.' + file.name.split('.').pop();

        if (!validExtensions.includes(fileExtension)) {
          alert('Por favor, selecione um arquivo Excel válido (.xls ou .xlsx).');
          return;
        }
        var seriaisErro = [];
        const reader = new FileReader();
        reader.onload = function (e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            const sheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[sheetName];
            const letras = /^[a-zA-ZÀ-ÿ]+$/;
            var idMovimento = 0; 
            if ($("movimento-item").val() != '') {
                idMovimento = $("#movimento-item").val();
            }
            var dadosInserir = [];
          
            const jsonData = XLSX.utils.sheet_to_json(worksheet, { raw: true });  

            if (jsonData.length === 0) {
                alert('Arquivo vazio.');
                return;
            }
            const serialPromises = [];
            const serialSet = new Set();
            var seriaisRepetidos = [];
            var serialERefVazio = false;
            var qtdEValorVazio = false;

            document.getElementById('loading').style.display = 'block';
            jsonData.forEach(resultado => {
                colunas = Object.keys(resultado);

                const arrayProcessado = colunas.map(palavra => removeAcento(palavra.toLowerCase()));

                if (arrayProcessado.includes("referencia") && !arrayProcessado.includes("serial")){
                    if (arrayProcessado.includes("quantidade") && arrayProcessado.includes("valor")){
                        tabelaItensMovimento.rows.add([
                        {
                            referencia: resultado[Object.keys(resultado)[0]],
                            terminal: '',
                            quantidade: resultado[Object.keys(resultado)[1]],
                            valor: resultado[Object.keys(resultado)[2]],
                            movimento: idMovimento == 1 ? 'Entrada' : 'Saída',
                            idMovimento: idMovimento,
                        }
                        ]).draw();
                        
                    }else{
                        qtdEValorVazio = true;
                    }
                }else if (arrayProcessado.includes("referencia") && arrayProcessado.includes("serial")){
                    if (serialSet.has(resultado[Object.keys(resultado)[1]])) {
                        seriaisRepetidos.push(resultado[Object.keys(resultado)[1]]);
                    }else{
                        serialSet.add(resultado[Object.keys(resultado)[1]]);
                    }

                    if (arrayProcessado.includes("quantidade") && arrayProcessado.includes("valor")){
                        tabelaItensMovimento.rows.add([
                        {
                            referencia: resultado[Object.keys(resultado)[0]],
                            terminal: resultado[Object.keys(resultado)[1]],
                            quantidade: resultado[Object.keys(resultado)[2]],
                            valor: resultado[Object.keys(resultado)[3]],
                            movimento: idMovimento == 1 ? 'Entrada' : 'Saída',
                            idMovimento: idMovimento,
                        }
                        ]).draw();
                    }else{
                        qtdEValorVazio = true;
                    }
                }else if (arrayProcessado.includes("serial") && !arrayProcessado.includes("referencia")){
                    if (serialSet.has(resultado[Object.keys(resultado)[0]])) {
                        seriaisRepetidos.push(resultado[Object.keys(resultado)[0]]);
                    }else{
                        serialSet.add(resultado[Object.keys(resultado)[0]]);
                    }

                    if (arrayProcessado.includes("quantidade") && arrayProcessado.includes("valor")){
                        serialPromise = marcaEModelo(resultado[Object.keys(resultado)[0]]).then(function(value) {
                            if (value){
                                tabelaItensMovimento.rows.add([
                                    {
                                        referencia: value,
                                        terminal: resultado[Object.keys(resultado)[0]],
                                        quantidade: arrayProcessado.includes("quantidade") ? resultado[Object.keys(resultado)[1]] : 1,
                                        valor: arrayProcessado.includes("valor") ? resultado[Object.keys(resultado)[2]] : 0,
                                        movimento: idMovimento == 1 ? 'Entrada' : 'Saída',
                                        idMovimento: idMovimento,
                                    }
                                ]).draw();
                            }
                        }).catch(function(error) {
                            seriaisErro.push(resultado[Object.keys(resultado)[0]]);
                        });
                        serialPromises.push(serialPromise);
                    }else{
                        qtdEValorVazio = true;
                    }
                }else{
                    serialERefVazio = true;
                }
            });
            Promise.all(serialPromises).then(function(values) {
                document.getElementById('loading').style.display = 'none';
                if (seriaisRepetidos.length > 0){
                    alert('Os seguintes seriais estão repetidos: ' + seriaisRepetidos + '. Verifique e tente novamente.');
                    tabelaItensMovimento.clear().draw();
                }else if (serialERefVazio){
                    alert('O arquivo possui colunas com referência e serial vazios. Verifique e tente novamente.');
                    tabelaItensMovimento.clear().draw();
                }else if (qtdEValorVazio){
                    alert('O arquivo possui colunas com quantidade e valor vazios. Verifique e tente novamente.');
                    tabelaItensMovimento.clear().draw();
                }else{
                    if (seriaisErro.length > 0){
                        alert('Os seguintes seriais não foram encontrados: ' + seriaisErro + '. Verifique e tente novamente.');
                        tabelaItensMovimento.clear().draw();
                    }
                }
            });
        };  
        
        reader.readAsArrayBuffer(file);
        
    }

    function marcaEModelo(serial) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: '<?= site_url('MovimentosEstoque/buscarSeriais') ?>',
                type: 'GET',
                dataType: 'json',
                data: {
                    q: serial
                },
                error: function() {
                    reject('Erro ao buscar marca e modelo do serial');
                },
                success: function(data) {
                    if (data.status === 200) {
                        resolve(data.results[0].marca + '-' + data.results[0].modelo);
                    } else {
                        resolve(false);
                    }
                },
            });
        });
    }

    function removeAcento(palavra){
        return palavra.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
    }

    $('#limparTabelaItens').click(function(e) {
        e.preventDefault();
        if (confirm('Deseja realmente limpar a tabela?')) {
            tabelaItensMovimento.clear().draw();
        }else{
            return false;
        }
    });

    $("#info-icon").click(function (e) {
        $("#modalModeloItens").modal("show")
    });

    function baixarModeloItens () {
        $.ajax({
            url: '<?= site_url('MovimentosEstoque/downloadModeloItens') ?>',
            type: 'GET',
            dataType: 'json',
            error: function(e) {
                alert('Erro ao baixar modelo!');
            },
            success: function(data) {
                if (data.status === 200) {
                    window.location.href = data.mensagem;
                } else {
                    alert('Não foi possível baixar o modelo!');
                }
            },
        });
    }
    
</script>   