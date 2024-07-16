<style>

    #loading-screen.loading-screen-active {
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background-color: rgba(0, 0, 0, 0.5); /* transparência para mostrar que a tela está bloqueada */
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

    #blockIframe {
        display: none;
        z-index: 9999;
        position: absolute;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.58);
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    #loadingScreen {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Fundo semi-transparente */
        z-index: 9999; /* Garante que a tela de carregamento fique acima do conteúdo do modal */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #loadingScreen::after {
        content: "";
        display: block;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 3px solid #fff;
        border-top-color: transparent;
        animation: spin 1s infinite linear;
    }

    .custom-table {
        width: 100% !important; /* Ajuste a largura total da tabela */
    }

    .custom-table th,
    .custom-table td {
        font-size: 14px; /* Ajuste o tamanho da fonte dos elementos de tabela */
        white-space: normal; /* Quebra de linha automática em células de tabela */
        word-wrap: break-word; /* Quebra de palavra em células de tabela */
    }

    .custom-table th {
        width: 10%; /* Ajuste a largura das colunas de cabeçalho */
    }

    .custom-table td {
        width: 15%; /* Ajuste a largura das colunas de células de dados */
    }

    .custom-table .btn {
        width: 100%; /* Largura de 100% para os botões */
        margin-bottom: 5px; /* Margem inferior para separação dos botões */
        white-space: normal;
    }

    .custom-table-small {
        width: 80%;
    }

    .custom-table-small td {
        width: 10%;
    }

    .table-container {
        overflow: hidden;
    }

    .bord{
        border-left: 3px solid #03A9F4;
    }

    .configurometroLoader {
        
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 2s linear infinite;
    }

    #loadingConfigurometro {
        display: block;
        z-index: 9999;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .loadingAvaliacaoCliente {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 2s linear infinite;
    }

    #loadingAvaliacaoCliente {
        z-index: 9999;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    tfoot{
        font-weight: 700;
    }

    #docs_cotacao_label {
        max-width: 100%; 
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .qtdItemComposicao{
        height: 28px !important;
    }
</style>

<h3><?=lang('pedidos')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('comercial_e_televendas')?> >
    <?=lang('pedidos')?>
</div>

<div id="loading">
    <div class="loader"></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>

<div class="alert alert-info col-md-12" style="margin-bottom: 50px;">    
    <div class ="col-md-12">
        <p class ="col-md-12">Informe o CPF/CNPJ do cliente a ser pesquisado</p>
        <span class="help help-block"></span>
        <form action="" id="formBusca">

            <div id="pesquisa" class="col-md-4">
                <label>Documento: </label>
                <input class="form-control documento" name="documento" id="documentoPesquisa" type="text" data-mask-for-cpf-cnpj />
            </div>

            <div class="col-md-4 row">
                <div class="col-md-6 data-pesquisa" hidden>
                    <label for="dataInicial">Data Inicial:</label>
                    <input id="dataInicial" type="date" name="dataInicial" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dataInicial" value=""/> 
                </div>

                <div class="col-md-6 data-pesquisa" hidden>
                    <label for="dataFinal">Data Final:</label>
                    <input id="dataFinal" type="date" name="dataFinal" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dataFinal" value=""/>
                </div>
            </div>

            <input type="text" hidden name="emailUsuario" value = "<?= $_SESSION['emailUsuario']?>">
            <div class="col-md-4 row">
                    <button class="btn btn-primary" id="BtnPesquisar" type="button" style="margin: 23px 15px 0 15px;"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                    <button class="btn btn-primary" id="BtnLimparPesquisar" type="button" style="margin: 23px 15px 0 15px;"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
            </div>  
        </form>
    </div>

    <div class="col-md-12" id="pesquisa_avancada" hidden>

        <div class="dropdown">
            <button class="dropbtn btn btn-info" id="btnDropdown" type="button" style="margin: 15px 15px 0 15px;"><i class="fa fa-caret-down" aria-hidden="true"></i> Mais Informações</button>
            <div id="myDropdown" class="dropdown-content" style="margin-left: 15px;" hidden>
                <a class="btn btn-info listagem_docs" id='listagem_docs' style="width: 100%;" data-documento="<?php echo isset($_SESSION['documento'])?$_SESSION['documento']: "";?>">Documentos</a>
                <a class="btn btn-info" id="getResumoClienteERP"  value="<?= isset($_SESSION['documento'])?$_SESSION['documento']: ""; ?>"  >Resumo Cliente ERP</a>
            </div> 

            <button class="btn btn-info" id="btnSalvarCliente" type="button" style="margin: 15px 15px 0 15px;"><i class="fa fa-floppy-o" aria-hidden="true"></i> Salvar</button>
        </div>  

    </div>

    <div class="col-md-12" id="acao_oportunidade" hidden>
        <button type="button" class="btn btn-info" data-toggle="modal" id="open-modal" style="margin: 15px 15px 0 15px;"><i class="fa fa-plus-circle"></i> Adicionar Oportunidade</button>
        <button type="button" class="btn btn-info" id="quotes_vendedor" style="margin: 15px 15px 0 15px;"><i class="fa fa-book"></i> Minhas Oportunidades</button>
    </div>

    <div class="col-md-12" id="acao_kanban" hidden> 
            <button class="btn btn-info" id="minhasOportunidades" type="button" style="margin: 15px 15px 0 15px;"><i class="fa fa-book"></i> Minhas Oportunidades</button> 
    </div>
</div>


<div class="row " >
    <div class="table-responsive col-md-12">         
               
        <ul id="navs" class="nav nav-tabs">
            <li ><a class="tab-panee" href="#tab_clientes" data-toggle="tab" id="tab-clientes"><?=lang('clientes')?></a></li>
            <li ><a class="tab-panee" href="#tab_oportunidades" data-toggle="tab" id="tab-oportunidades"><?=lang('oportunidades')?></a></li>
            <li><a class="tab-panee" href="#tab_quotes" data-toggle="tab" id="tab-quotes"><?=lang('quotes')?></a></li> 
            <?php if ($this->auth->is_allowed_block('vis_gerenciamentopedidos')){?>
                <li><a class="tab-panee" href="#tab_gerenciamento" data-toggle="tab" id="tab-gerenciamento"><?=lang('gerenciamento')?></a></li> 
            <?php } ?>  
        </ul>
        <?php 
            $active = "";
            
            if (isset($_SESSION['active'])) {
                $active = $_SESSION['active'];       
            }
            else{
                $_SESSION['active'] = "clientes";
                $active = "clientes";
            }

            $origem = "";
            
            if (isset($_SESSION['origem'])) {
                $origem = $_SESSION['origem'];  
                unset($_SESSION['origem']);   
            }
           
           
        ?>
        <div class="tab-content" id="tabs">
            <div class="tab-pane" id="tab_clientes">                
                <?php $this->load->view('comercial_televenda/pedido/clientes');?>                  
            </div>
             <div class="tab-pane" id="tab_oportunidades">
                <?php $this->load->view('comercial_televenda/pedido/oportunidades');?>
            </div> 
            <div class="tab-pane" id="tab_quotes">
                <?php $this->load->view('comercial_televenda/pedido/quotes');?>
            </div> 
            <?php if ($this->auth->is_allowed_block('vis_gerenciamentopedidos')){?>
                <div class="tab-pane" id="tab_gerenciamento">
                    <?php $this->load->view('comercial_televenda/pedido/gerenciamento');?>
                </div>  
            <?php } ?>         
        </div>  
    </div>
</div>

<!-- Modal para exibir resumo da cotação-->

<div id="modalResumoCotacao" style="overflow-y: auto;" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="width: 1050px;">
        <div class="modal-content">
            <form name="formResumoCotacao">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("resumo_cotacao")?> <span id="numeroCotacaotitle">-</span></h3>
                    <div class="row" >
                        <h4 class="col-md-7">Valor Total da Cotação: <span id="CotacaoGastoTotal"></span></h4>
                        <button class="btn btn-primary buttonResumoPDF col-md-2" title="Resumo PDF"> <i aria-hidden="true"></i>Resumo PDF</button>
                        <button class="btn btn-primary buttonResumoEmail col-md-2" title="Enviar Email"  style="margin-left: 10px"> <i aria-hidden="true"></i>Resumo por Email</button>
                        <button class="btn btn-primary buttonAtualizarResumo col-md-1" title="Atualizar" style="width: auto; margin-left: 10px"> 
                            <i class="fa fa-refresh" aria-hidden="true" style="font-size: 2.7rem;"></i>
                        </button>
                    </div>
                </div>
                
                <div id="bodyModalResumoCotacao" class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs" style="margin-bottom: 10px;">
                                <li class="nav-item">
                                    <a id = "tab-dadosGerais" href="" data-toggle="tab" class="nav-link active">Dados Gerais</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-composicao" href="" data-toggle="tab" class="nav-link">Composição</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-hardwares" href="" data-toggle="tab" class="nav-link">Hardwares</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-licencas" href="" data-toggle="tab" class="nav-link">Licenças</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-servicos" href="" data-toggle="tab" class="nav-link">Serviços</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-configurometro" href="" data-toggle="tab" class="nav-link">Configurômetro</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-documentosCotacao" href="" data-toggle="tab" class="nav-link">Anotações</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-cartaoCredito" href="" data-toggle="tab" class="nav-link">Cartão de Crédito</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-avaliacaoCliente" href="" data-toggle="tab" class="nav-link">Avaliação do Cliente</a>
                                </li>
                            </ul>
                            <div id="dadosGerais" style="display: block;">
                                <div class="row col-md-12">
                                    <div class="col-md-6 form-group bord">
                                        <label  >Número da cotação:</label>    
                                        <p id="numeroCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Versão da cotação:</label>    
                                        <p id="versaoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Cliente:</label>    
                                        <p id="clienteCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Início da vigência:</label>    
                                        <p id="inicioVigenciaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Término da vigência:</label>    
                                        <p id="terminoVigenciaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Plataforma:</label>    
                                        <p id="plataformaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Cenário da venda:</label>    
                                        <p id="cenarioVendaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Tipo de pagamento:</label>    
                                        <p id="tipoPagamentoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Condição de pagamento:</label>    
                                        <p id="condicaoPagamentoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Modalidade de venda:</label>    
                                        <p id="modalidadeVendaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Canal de venda:</label>    
                                        <p id="canalVendaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Tempo de contrato:</label>    
                                        <p id="tempoContratoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Tipo de frete:</label>    
                                        <p id="tipoFreteCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Signatário do software:</label>    
                                        <p id="signatarioSoftwareCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Email do signatário do software:</label>    
                                        <p id="emailSignatarioSoftwareCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Documento do signatário do software:</label>    
                                        <p id="documentoSignatarioSoftwareCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Signatário do hardware:</label>    
                                        <p id="signatarioHardwareCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Email do signatário do hardware:</label>    
                                        <p id="emailSignatarioHardwareCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Documento do signatário do hardware:</label>    
                                        <p id="documentoSignatarioHardwareCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Status docusign:</label>    
                                        <p id="statusDocusignCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label>Cliente retira do armazém:</label>    
                                        <p id="clientRetiraArmazemCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label>Armazém:</label>    
                                        <p id="armazemCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label>CPF do responsável pela retirada do armazém:</label>    
                                        <p id="cpfResponsavelRetiradaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label>Responsável pela retirada do armazém:</label>    
                                        <p id="responsavelRetiradaCotacao">-</p>
                                    </div>
                                </div>
                            </div>
                            <div style = 'display: none !important;'id="composicao">
                                <div class="row col-md-12">
                                    <div class="col-md-6 form-group bord">
                                        <label  >Sistema:</label>    
                                        <p id="sistemaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Tipo de comunicação:</label>    
                                        <p id="tipoComunicacaoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Família do produto:</label>    
                                        <p id="familiaProdutoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Produto:</label>    
                                        <p id="produtoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Categoria:</label>    
                                        <p id="categoriaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Tipo de veículo:</label>    
                                        <p id="tipoVeiculoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label  >Quantidade:</label>    
                                        <p id="quantidadeCotacao">-</p>
                                    </div>

                                </div>
                            </div>
                            <div style="display: none !important;" id="hardwares">
                                <div class="row">
                                    <button class="btn btn-primary" title="Adicionar hardware" style="margin-top: 4px; margin-left: 12px;" id="addHardware" onClick="javascript:abrirDadosAddHardware(this)"> <i class="fa fa-plus" aria-hidden="true"></i> Adicionar Hardware</button>
                                </div>
                                <table class="table table-striped table-bordered table-hover" id="tableHardwaresCotacao" style="width: 100%;">
                                    <thead>
                                        <tr class="tableheader">
                                            <th hidden>HardwareId</th>
                                            <th>Produto</th>
                                            <th>Valor Unitário</th>
                                            <th>Quantidade</th>
                                            <th>Valor Total</th>                                              
                                            <th>% de Desconto</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align:right">Total:</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div style="display: none !important;" id="licencas">
                                <div class="row">
                                    <button class="btn btn-primary" title="Adicionar licença" style="margin-top: 4px; margin-left: 12px;" id="addLicenca" onClick="javascript:abrirDadosAddLicenca(this)"> <i class="fa fa-plus" aria-hidden="true"></i> Adicionar Licença</button>
                                </div>
                                <table class="table table-striped table-bordered table-hover" id="tableLicencasCotacao" style="width: 100%;">
                                    <thead>
                                        <tr class="tableheader">
                                            <th hidden>LicencaId</th>
                                            <th>Produto</th>
                                            <th>Valor Unitário</th>
                                            <th>Quantidade</th>
                                            <th>Valor Total</th>
                                            <th>Plano Satelital</th>
                                            <th>% de Desconto</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" style="text-align:right">Total:</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div style="display: none !important;" id="servicos">
                                <div class="row">
                                    <button class="btn btn-primary" title="Adicionar serviço" style="margin-top: 4px; margin-left: 12px;" id="addServico" onClick="javascript:abrirDadosAddServico(this)"> <i class="fa fa-plus" aria-hidden="true"></i> Adicionar Serviço</button>
                                </div>
                                <table class="table table-striped table-bordered table-hover" id="tableServicosCotacao" style="width: 100%;">
                                    <thead>
                                        <tr class="tableheader">
                                            <th hidden>ServicoId</th>
                                            <th>Produto</th>
                                            <th>Valor Unitário</th>
                                            <th>Quantidade</th>
                                            <th>Valor Total</th>
                                            <th>% de Desconto</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align:right">Total:</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>                                
                            <div style = 'display: none !important; position: relative;'id="configurometro">
                                <input type="text" class="form-control" id="gerouConfigurometro" style="visibility:hidden;"/>
                                <div id="blockIframe"></div>
                                <div id="loadingConfigurometro" style = 'display: none';>
                                    <i class="configurometroLoader" ></i>
                                </div>
                                <iframe id="iframePagina" style="width: 100%; height: 500px;"></iframe>
                            </div>  
                            <div style="display: none !important;" id="documentosCotacao">
                                <div>
                                    <br>
                                    <div class="col-md-7">
                                        <label  >Observação:</label>
                                        <input type="text" class="form-control" id="assuntoDocCotacao" name="assuntoDocCotacao" />
                                    </div>
                                    <div class="col-md-3">
                                        <label  >&nbsp;</label>
                                        <label for="docs_cotacao" id="docs_cotacao_label" class="btn btn-default col-sm-12">Selecione um Arquivo</label>
                                        <input id="docs_cotacao" type="file" class="btn btn-default"  style="visibility:hidden;" name="ArquivoCotacao"/>
                                        <input id="cotacaoid" style="visibility:hidden;" name="cotacao"/>
                                    </div>
                                    <div class="col-md-2" style="padding-left: 0px !important; padding-top: 2.5%">
                                        <a class="btn btn-primary" id="btn-adicionarDoc" style="width: 100%;">Adicionar Item</a>
                                    </div>
                                </div>
                                <table style="width: 100%" id="table-documentosCotacao" class="table table-striped table-bordered" style="display: block !important;">
                                    <thead>
                                        <th>Observação</th>
                                        <th>Arquivo</th>
                                        <th>Ações</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div> 
                            <div style="display: none !important;" id="divCartaoCredito">
                                <form name="formAtualizaCartao"> 
                                    <div class="row">
                                        <div id ="selectBandeira" class="col-md-6 form-group" hidden>
                                            <label class="control-label">Bandeira</label>
                                            <select class="form-control" id="bandeiraCartao" name="bandeiraCartao" required>
                                                <option value="0" disabled>Selecione a bandeira</option>
                                                <option value="1" hidden>Amex</option>
                                                <option value="2" hidden>Diners</option>
                                                <option value="3" hidden>Hipercard</option>
                                                <option value="4" hidden>JCB</option>
                                                <option value="5" hidden>Mastercard</option>
                                                <option value="6">Sorocred</option>
                                                <option value="7" hidden>Visa</option>
                                                <option value="8">Elo</option>
                                                <option value="9">Agiplan</option>
                                                <option value="10">Banescard</option>
                                                <option value="11">Credz</option>
                                                <option value="12">Hiper</option>
                                                <option value="13">Cabal</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Número do Cartão</label>
                                            <input type="number" aria-labelledby="label-numeroCartao"  class="form-control" id="numeroCartao" style="background-repeat: no-repeat; background-position: right ;" placeholder="Digite o número do cartão" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="16" required>
                                            <label for="numeroCartao" id="label-numeroCartao" style="color: red; font-size: 10px; position: absolute;" hidden>
                                                Número do cartão inválido!
                                            </label>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Código de Segurança (CVV)</label>
                                            <input type="number" class="form-control" id="codigoCartao" placeholder="Digite o código de segurança do cartão"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="4" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Nome Impresso no Cartão</label>
                                            <input type="text" class="form-control" id="nomeCartao" placeholder="Digite o nome impresso do cartão" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Mês de Validade do Cartão</label>
                                            <input type="number" min="1" max="12" class="form-control" id="mesValidadeCartao" placeholder="Digite o mês com um ou dois dígitos. Ex1: '05'; Ex2: '5'" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="2"required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Ano de Validade do Cartão</label>
                                            <input type="number" class="form-control" id="anoValidadeCartao" placeholder="Digite o ano com dois dígitos. Ex: '23' " oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="2"required>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <div style="display: none !important;" id="conteudo-aba-avaliacao-cliente">
                                <div id="loadingAvaliacaoCliente">
                                    <div class="loadingAvaliacaoCliente"></div>
                                </div>

                                <div class="hide" id="conteudo-avaliacao-cliente">
                                    <div id="divSemConteudoAvaliacao">
                                        <div class="col-md-12 form-group bord">
                                            <span>O perfil do cliente ainda está sendo avaliado, Não há dados para exibir no momento.</span>
                                        </div>
                                    </div>
                                    <div id="divConteudoAvaliacao">
                                        <div class="col-md-12">
                                            <h4>Resultado</h4>

                                            <div class="col-md-6 form-group bord">
                                                <span id="resultado-avaliacao-cliente"></span>
                                            </div>
                                            <div class="col-md-6 form-group bord">
                                                <label>Data da Avaliação: </label>
                                                <span id="data-avaliacao-cliente"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <h4>Dados Avaliados</h4>
                                            <div id="div-dados-consultados-avaliacao-clientes"></div>
                                        </div>
                                    </div>
                                
                                </div>
                            </div> 

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary" id="btnSalvarAtualizaCartao" style="display: none !important;">Salvar</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHardware" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="modal-title" id="myModalLabel">Adicionar Hardware</h5>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Selecione:</label>    
                <select class="form-control input-sm" id="selectHardware" name="nome" type="text" style="width: 100%"></select>
            </div>
            <div class="col-md-6 form-group">
                <label>Quantidade:</label>    
                <input class="form-control input-sm qtdItemComposicao" id="quantidadeHardware" name="quantidadeHardware" type="number" placeholder="Digite a quantidade">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
        <button class="btn btn-primary" id="btnSalvarAddSubItem">Salvar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalLicenca" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="modal-title" id="myModalLabel">Adicionar Licença</h5>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Selecione:</label>    
                <select class="form-control input-sm" id="selectLicenca" name="nome" type="text" style="width: 100%;"></select>
            </div>
            <div class="col-md-6 form-group">
                <label>Quantidade:</label>    
                <input class="form-control input-sm qtdItemComposicao" id="quantidadeLicenca" name="quantidadeLicenca" type="number" placeholder="Digite a quantidade">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
        <button class="btn btn-primary" id="btnSalvarAddSubItemLicenca">Salvar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalServico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="modal-title" id="myModalLabel">Adicionar Serviço</h5>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Selecione:</label>    
                <select class="form-control input-sm" id="selectServico" name="nome" type="text" style="width: 100%"></select>
            </div>
            <div class="col-md-6 form-group">
                <label>Quantidade:</label>    
                <input class="form-control input-sm qtdItemComposicao" id="quantidadeServico" name="quantidadeServico" type="number" placeholder="Digite a quantidade">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
        <button class="btn btn-primary" id="btnSalvarAddSubItemServico">Salvar</button>
      </div>
    </div>
  </div>
</div>

<!-- modal para edição/visualização dos dados vindo da função clienteERP -->
<div class="modal " id="modalDadosClienteERPPJ" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4 !important">
                <div class="row">
                    <div class="col-md-10">
                        <h3 class="modal-title">Resumo Cliente ERP - PJ</h3>
                    </div>
                
                </div>
            </div>
            <form id="integracaoERPPJ">
                <div class="modal-body">
                        <div class="row" style="margin-left: 3px; margin-right: 2px;">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <label>Nome: </label>
                                <input type="text" class="form-control" id="nome-erp-pj" name="name" required>
                            </div>
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <label>Nome Fantasia: </label>
                                <input type="text" class="form-control" id="nome-fantasia-erp-pj" name="nome_fantasia" value="" >
                            </div>
                        </div>
                        <span class="help help-block"></span>
                        <div class="row" style="margin-left: 3px; margin-right: 2px;">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <label>E-mail: </label>
                                <input type="text" class="form-control" id="email-erp-pj" name="email" value="" required>
                            </div>
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <label>Login Vendedor: </label>
                                <input type="text" class="form-control" id="vendedor-erp-pj" name="loginVendedor" value="" required>
                            </div>
                        </div>
                        <span class="help help-block"></span>
                        <div class="row" style="margin-left: 3px; margin-right: 2px;">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <label>Documento: </label>
                                <input type="text" class="form-control" id="documento-erp-pj" name="documento" value="" readonly>
                            </div>
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <label>Inscrição Estadual: </label>
                                <input type="text" class="form-control" id="ie-erp-pj" data-mask="0000000-00" name="inscricao_estadual" required>
                            </div>
                        </div>
                        <span class="help help-block"></span>
                        <div class="row" style="margin-left: 3px; margin-right: 2px;">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <div class="row">
                                    <div class="col-md-2">
                                        <Label>DDD: </Label>
                                        <input type="text" class="form-control" id="ddd-erp-pj" name="ddd_telefone" >
                                    </div>
                                    <div class="col-md-10">
                                        <Label>Telefone: </Label>
                                        <input type="text" class="form-control" id="telefone-erp-pj" name="telefone" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <div class="row">
                                    <div class="col-md-2">
                                        <Label>DDD: </Label>
                                        <input type="text" class="form-control" id="ddd-erp-pj2" name="ddd_telefone2" >
                                    </div>
                                    <div class="col-md-10">
                                        <Label>Telefone: </Label>
                                        <input type="text" class="form-control" id="telefone-erp-pj2" name="telefone2" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="help help-block"></span>
                        <hr>
                        <h4>Endereços</h4>

                        <ul class="nav nav-tabs nav-justified">
                            <li role="presentation" class="active"><a id="endereco-principal-erp-pj-a" href="#">Endereço Principal</a></li>
                            <li role="presentation"><a id="endereco-cobranca-erp-pj-a" href="#">Endereço Cobrança</a></li>
                            <li role="presentation"><a id="endereco-entrega-erp-pj-a" href="#">Endereço Entrega</a></li>                
                        </ul>

                        <script>
                            $("#endereco-principal-erp-pj-a").click(function(){
                                $("#endereco-principal-erp-pj").show();
                                $("#endereco-cobranca-erp-pj").hide();
                                $("#endereco-entrega-erp-pj").hide();

                                $("#endereco-principal-erp-pj-a").parent().attr('class', "active")
                                $("#endereco-cobranca-erp-pj-a").parent().removeAttr('class')
                                $("#endereco-entrega-erp-pj-a").parent().removeAttr('class')
                            });

                            $("#endereco-cobranca-erp-pj-a").click(function(){
                                $("#endereco-principal-erp-pj").hide();
                                $("#endereco-cobranca-erp-pj").show();
                                $("#endereco-entrega-erp-pj").hide();

                                $("#endereco-principal-erp-pj-a").parent().removeAttr('class')
                                $("#endereco-cobranca-erp-pj-a").parent().attr('class', "active")
                                $("#endereco-entrega-erp-pj-a").parent().removeAttr('class')
                            });

                            $("#endereco-entrega-erp-pj-a").click(function(){
                                $("#endereco-principal-erp-pj").hide();
                                $("#endereco-cobranca-erp-pj").hide();
                                $("#endereco-entrega-erp-pj").show();

                                $("#endereco-principal-erp-pj-a").parent().removeAttr('class')
                                $("#endereco-cobranca-erp-pj-a").parent().removeAttr('class')
                                $("#endereco-entrega-erp-pj-a").parent().attr('class', "active")
                            });

                        </script>

                        <div id="endereco-principal-erp-pj">
                            <br>
                            <div class="col-md-6">
                                <label>CEP: </label>
                                <input type="text" class="form-control cep" id="cep-principal-erp-pj" name="cep_principal" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label>Número / Complemento: </label>
                                <input type="text" class="form-control" id="complemento-principal-erp-pj" name="complemento_principal" required>
                            </div>                  
                    
                            <div class="col-md-6">
                                <span class="help help-block"></span>
                                <label>Rua: </label>
                                <input type="text" class="form-control" id="rua-principal-erp-pj" name="rua_principal" required>
                            </div>
                            <div class="col-md-6">
                                <span class="help help-block"></span>
                                <label>Bairro: </label>
                                <input type="text" class="form-control" id="bairro-principal-erp-pj" name="bairro_principal" required>
                            </div>
                            <div class="col-md-6">
                                <span class="help help-block"></span>
                                <label>Cidade: </label>
                                <input type="text" class="form-control" id="cidade-principal-erp-pj" name="cidade_principal" required>
                            </div>
                            <div class="col-md-6">
                                <span class="help help-block"></span>
                                <label>Estado: </label>
                                <input type="text" class="form-control" id="estado-principal-erp-pj" name="estado_principal" required>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <span class="help help-block"></span>
                                    <label>Copiar Endereço Cobrança: </label>
                                    <input type="checkbox" id="copiar-endereco-cobranca-principal-erp-pj" name="copiar_endereco_cobranca_principal_pj" value="1">
                                </div>
                                <div class="col-md-4">
                                    <span class="help help-block"></span>
                                    <label>Copiar Endereço Entrega: </label>
                                    <input type="checkbox" id="copiar-endereco-entrega-principal-erp-pj" name="copiar_endereco_entrega_principal_pj" value="1">
                                </div>
                            </div>
                        </div>
                        <div id="endereco-cobranca-erp-pj" hidden>
                            <br>
                            <div class="col-md-6">
                                <label>CEP: </label>
                                <input type="text" class="form-control cep" id="cep-cobranca-erp-pj" name="cep_cobranca" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label>Número / Complemento:</label>
                                <input type="text" class="form-control" id="complemento-cobranca-erp-pj" name="complemento_cobranca" required>
                            </div>
                            <div class="col-md-6">
                                <span class="help help-block"></span>
                                <label>Rua: </label>
                                <input type="text" class="form-control" id="rua-cobranca-erp-pj" name="rua_cobranca" required>
                            </div>
                            <div class="col-md-6">
                                <span class="help help-block"></span>
                                <label>Bairro: </label>
                                <input type="text" class="form-control" id="bairro-cobranca-erp-pj" name="bairro_cobranca" required>
                            </div>
                            <div class="col-md-6">
                                <span class="help help-block"></span>
                                <label>Cidade: </label>
                                <input type="text" class="form-control" id="cidade-cobranca-erp-pj" name="cidade_cobranca" required>
                            </div>
                            <div class="col-md-6">
                                <span class="help help-block"></span>
                                <label>Estado: </label>
                                <input type="text" class="form-control" id="estado-cobranca-erp-pj" name="estado_cobranca" required>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <span class="help help-block"></span>
                                    <label>Copiar Endereço Principal: </label>
                                    <input type="checkbox" id="copiar-endereco-principal-cobranca-erp-pj" name="copiar_endereco_principal_cobranca_pj" value="1">
                                </div>
                                <div class="col-md-4">
                                    <span class="help help-block"></span>
                                    <label>Copiar Endereço Entrega: </label>
                                    <input type="checkbox" id="copiar-endereco-entrega-cobranca-erp-pj" name="copiar_endereco_entrega_cobranca_pj" value="1">
                                </div>
                            </div>
                        </div>
                        <div id="endereco-entrega-erp-pj" hidden>
                            <br>
                            <div class="col-md-6">
                                <label>CEP: </label>
                                <input type="text" class="form-control cep" id="cep-entrega-erp-pj" name="cep_entrega" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label>Número / Complemento:</label>
                                <input type="text" class="form-control" id="complemento-entrega-erp-pj" name="complemento_entrega" required>
                            </div> 
                    
                            <div class="col-md-6">
                                <span class="help help-block"></span>
                                <label>Rua: </label>
                                <input type="text" class="form-control" id="rua-entrega-erp-pj" name="rua_entrega" required>
                            </div>
                            <div class="col-md-6">
                                <span class="help help-block"></span>
                                <label>Bairro: </label>
                                <input type="text" class="form-control" id="bairro-entrega-erp-pj" name="bairro_entrega" required>
                            </div>
                            <div class="col-md-6">
                                <span class="help help-block"></span>
                                <label>Cidade: </label>
                                <input type="text" class="form-control" id="cidade-entrega-erp-pj" name="cidade_entrega" required>
                            </div>
                            <div class="col-md-6">
                                <span class="help help-block"></span>
                                <label>Estado: </label>
                                <input type="text" class="form-control" id="estado-entrega-erp-pj" name="estado_entrega" required>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-4">
                                    <span class="help help-block"></span>
                                    <label>Copiar Endereço Principal: </label>
                                    <input type="checkbox" id="copiar-endereco-principal-entrega-erp-pj" name="copiar_endereco_principal_entrega_pj" value="1">
                                </div>
                                <div class="col-md-4">
                                    <span class="help help-block"></span>
                                    <label>Copiar Endereço Cobrança: </label>
                                    <input type="checkbox" id="copiar_endereco_cobranca_entrega_pj" name="copiar_endereco_cobranca_entrega_pj" value="1">
                                </div>
                            </div>
                        </div>        
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submit-form-integracao-pj">Integrar Cliente e Gerar Pedidos</button>
                    
                    <button type="button" class="btn btn-default" id="closeModalERPPJ" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal para edição/visualização dos dados vindo da função clienteERP -->
<div class="modal" id="modalDadosClienteERPPF" role="dialog" aria-labelledby="modalDadosClienteERPPF" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4 !important">
                    <div class="row">
                        <div class="col-md-10">
                            <h3 class="modal-title">Resumo Cliente ERP - PF</h3>
                        </div>
                        
                    </div>
            </div>
            <form id="formIntegracaoERPPF">
                <div class="modal-body">
                    <div class="row" style="margin-left: 3px; margin-right: 2px;">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label>Nome: </label>
                            <input type="text" class="form-control" id="nome-erp-pf" name="name" required>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label>Sobrenome: </label>
                            <input type="text" class="form-control" id="sobrenome-erp-pf" name="sobrenome" value="">
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row" style="margin-left: 3px; margin-right: 2px;">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label>E-mail: </label>
                            <input type="text" class="form-control" id="email-erp-pf" name="email" value="" required>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label>Login Vendedor: </label>
                            <input type="text" class="form-control" id="vendedor-erp-pf" name="loginVendedor" value="" required>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row" style="margin-left: 3px; margin-right: 2px;">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label>Documento: </label>
                            <input type="text" class="form-control" id="documento-erp-pf" name="documento" value="" readonly>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label>Data de Nascimento: </label>
                            <input type="date" class="form-control" id="nascimento-erp-pf" name="dataNascimento" value="" >
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row" style="margin-left: 3px; margin-right: 2px;">                
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <div class="row">
                                <div class="col-md-2">
                                    <Label>DDD: </Label>
                                    <input type="text" class="form-control" id="ddd-erp-pf" name="ddd_telefone" >
                                </div>
                                <div class="col-md-10">
                                    <Label>Telefone: </Label>
                                    <input type="text" class="form-control" id="telefone-erp-pf" name="telefone" >
                                </div>
                            </div>
                        </div>            
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <div class="row">
                                <div class="col-md-2">
                                    <Label>DDD: </Label>
                                    <input type="text" class="form-control" id="ddd-erp-pf2" name="ddd_telefone2" >
                                </div>
                                <div class="col-md-10">
                                    <Label>Telefone: </Label>
                                    <input type="text" class="form-control" id="telefone-erp-pf2" name="telefone2" >
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <span class="help help-block"></span>
                    <hr>
                    <h4>Endereços</h4>

                    <ul class="nav nav-tabs nav-justified">
                        <li role="presentation" class="active"><a id="endereco-principal-erp-pf-a" href="#">Endereço Principal</a></li>
                        <li role="presentation"><a id="endereco-cobranca-erp-pf-a" href="#">Endereço Cobrança</a></li>
                        <li role="presentation"><a id="endereco-entrega-erp-pf-a" href="#">Endereço Entrega</a></li>                
                    </ul>

                    <script>
                        $("#endereco-principal-erp-pf-a").click(function(){
                            $("#endereco-principal-erp-pf").show();
                            $("#endereco-cobranca-erp-pf").hide();
                            $("#endereco-entrega-erp-pf").hide();

                            $("#endereco-principal-erp-pf-a").parent().attr('class', "active")
                            $("#endereco-cobranca-erp-pf-a").parent().removeAttr('class')
                            $("#endereco-entrega-erp-pf-a").parent().removeAttr('class')
                        });

                        $("#endereco-cobranca-erp-pf-a").click(function(){
                            $("#endereco-principal-erp-pf").hide();
                            $("#endereco-cobranca-erp-pf").show();
                            $("#endereco-entrega-erp-pf").hide();

                            $("#endereco-principal-erp-pf-a").parent().removeAttr('class')
                            $("#endereco-cobranca-erp-pf-a").parent().attr('class', "active")
                            $("#endereco-entrega-erp-pf-a").parent().removeAttr('class')
                        });

                        $("#endereco-entrega-erp-pf-a").click(function(){
                            $("#endereco-principal-erp-pf").hide();
                            $("#endereco-cobranca-erp-pf").hide();
                            $("#endereco-entrega-erp-pf").show();

                            $("#endereco-principal-erp-pf-a").parent().removeAttr('class')
                            $("#endereco-cobranca-erp-pf-a").parent().removeAttr('class')
                            $("#endereco-entrega-erp-pf-a").parent().attr('class', "active")
                        });

                    </script>

                    <div id="endereco-principal-erp-pf">
                        <br>
                        
                        <div class="col-md-6">
                            <label>CEP: </label>
                            <input type="text" class="form-control cep" id="cep-principal-erp-pf" name="cep_principal" required>
                        </div>                        
                        <div class="col-md-6">
                            <label>Número / Complemento: </label>
                            <input type="text" class="form-control" id="complemento-principal-erp-pf" name="complemento_principal" required>
                        </div>
                        <div class="col-md-6">
                            <span class="help help-block"></span>
                            <label>Rua: </label>
                            <input type="text" class="form-control" id="rua-principal-erp-pf" name="rua_principal" required>
                        </div>
                        <div class="col-md-6">
                            <span class="help help-block"></span>
                            <label>Bairro: </label>
                            <input type="text" class="form-control" id="bairro-principal-erp-pf" name="bairro_principal" required>
                        </div>
                        <div class="col-md-6">
                            <span class="help help-block"></span>
                            <label>Cidade: </label>
                            <input type="text" class="form-control" id="cidade-principal-erp-pf" name="cidade_principal" required>
                        </div>
                        <div class="col-md-6">
                            <span class="help help-block"></span>
                            <label>Estado: </label>
                            <input type="text" class="form-control" id="estado-principal-erp-pf" name="estado_principal" required>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <span class="help help-block"></span>
                                <label>Copiar Endereço Cobrança: </label>
                                <input type="checkbox" id="copiar-endereco-cobranca-principal-erp-pf" name="copiar_endereco_cobranca_principal_pf" value="1">
                            </div>
                            <div class="col-md-4">
                                <span class="help help-block"></span>
                                <label>Copiar Endereço Entrega: </label>
                                <input type="checkbox" id="copiar-endereco-entrega-principal-erp-pf" name="copiar_endereco_entrega_principal_pf" value="1">
                            </div>
                        </div>
                    </div>
                    <div id="endereco-cobranca-erp-pf" hidden>
                        <br>
                        <div class="col-md-6">
                            <label>CEP: </label>
                            <input type="text" class="form-control cep" id="cep-cobranca-erp-pf" name="cep_cobranca" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label>Número / Complemento: </label>
                            <input type="text" class="form-control" id="complemento-cobranca-erp-pf" name="complemento_cobranca" required>
                        </div>
                    
                        <div class="col-md-6">
                            <span class="help help-block"></span>
                            <label>Rua: </label>
                            <input type="text" class="form-control" id="rua-cobranca-erp-pf" name="rua_cobranca" required>
                        </div>
                        <div class="col-md-6">
                            <span class="help help-block"></span>
                            <label>Bairro: </label>
                            <input type="text" class="form-control" id="bairro-cobranca-erp-pf" name="bairro_cobranca" required>
                        </div>
                        <div class="col-md-6">
                            <span class="help help-block"></span>
                            <label>Cidade: </label>
                            <input type="text" class="form-control" id="cidade-cobranca-erp-pf" name="cidade_cobranca" required>
                        </div>
                        <div class="col-md-6">
                            <span class="help help-block"></span>
                            <label>Estado: </label>
                            <input type="text" class="form-control" id="estado-cobranca-erp-pf" name="estado_cobranca" required>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <span class="help help-block"></span>
                                <label>Copiar Endereço Principal: </label>
                                <input type="checkbox" id="copiar-endereco-principal-cobranca-erp-pf" name="copiar_endereco_principal_cobranca_pf" value="1">
                            </div>
                            <div class="col-md-4">
                                <span class="help help-block"></span>
                                <label>Copiar Endereço Entrega: </label>
                                <input type="checkbox" id="copiar-endereco-entrega-cobranca-erp-pf" name="copiar_endereco_entrega_cobranca_pf" value="1">
                            </div>
                        </div>
                    </div>
                    <div id="endereco-entrega-erp-pf" hidden>
                        <br>
                        <div class="col-md-6">
                            <label>CEP: </label>
                            <input type="text" class="form-control cep" id="cep-entrega-erp-pf" name="cep_entrega" required>
                        </div>                    
                        <div class="col-md-6">
                            <label>Número / Complemento: </label>
                            <input type="text" class="form-control" id="complemento-entrega-erp-pf" name="complemento_entrega" required>
                        </div>                 
                        <div class="col-md-6">
                            <span class="help help-block"></span>
                            <label>Rua: </label>
                            <input type="text" class="form-control"  id="rua-entrega-erp-pf" name="rua_entrega" required>
                        </div>
                        <div class="col-md-6">
                            <span class="help help-block"></span>
                            <label>Bairro: </label>
                            <input type="text" class="form-control" id="bairro-entrega-erp-pf" name="bairro_entrega" required>
                        </div>
                        <div class="col-md-6">
                            <span class="help help-block"></span>
                            <label>Cidade: </label>
                            <input type="text" class="form-control" id="cidade-entrega-erp-pf" name="cidade_entrega" required>
                        </div>
                        <div class="col-md-6">
                            <span class="help help-block"></span>
                            <label>Estado: </label>
                            <input type="text" class="form-control" id="estado-entrega-erp-pf" name="estado_entrega" required>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <span class="help help-block"></span>
                                <label>Copiar Endereço Principal: </label>
                                <input type="checkbox" id="copiar-endereco-principal-entrega-erp-pf" name="copiar_endereco_principal_entrega_pf" value="1">
                            </div>
                            <div class="col-md-4">
                                <span class="help help-block"></span>
                                <label>Copiar Endereço Cobrança: </label>
                                <input type="checkbox" id="copiar-endereco-cobranca-entrega-erp-pf" name="copiar_endereco_cobranca_entrega_pf" value="1">
                            </div>
                        </div>
                    </div>     
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submit-form-integracao-pf">Integrar Cliente e Gerar Pedido</button>
                    
                    <button type="button" class="btn btn-default" id="closeModalERPPF" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modalCadastroCliente" role="dialog" aria-labelledby="modalCadastroCliente" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4 !important">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <div class="row">
                    <div class="col-md-10">
                        <h3 class="modal-title">Cadastro de Cliente</h3>
                    </div>
                    
                </div>
            </div>
            <div class="modal-body">
                <form id="formCadastroPF" class="col-md-12" hidden>
                    <div class="row">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Nome:</label>
                            <input type="text" class="form-control" id="nome-form-pf" name="name" required>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Sobrenome:</label>
                            <input type="text" class="form-control" id="sobrenome-form-pf" name="sobrenome" value="" required>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">E-mail:</label>
                            <input type="text" class="form-control" id="email-form-pf" name="email" value="" required>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                
                        <label class="control-label">Login Vendedor:</label>
                            <input type="text" class="form-control" id="vendedor-form-pf" name="loginVendedor" value="<?= $_SESSION['emailUsuario']?>" required>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label>Documento:</label>
                            <input type="text" class="form-control" id="documento-form-pf" name="documento" value="" readonly>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <div class="row">
                                <div class="col-md-2">
                                    <Label class="control-label" style="display: flex;">DDD:</Label>
                                    <input type="text" class="form-control ddd" id="ddd-form-pf" name="ddd_telefone" required>
                                </div>
                                <div class="col-md-10">
                                    <Label class="control-label">Telefone:</Label>
                                    <input type="text" class="form-control fone" id="telefone-form-pf" name="telefone" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <hr>
                    <h4>Endereços</h4>
                    <div id="endereco-principal-form-pf">
                        <br>
                        <div class="row">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <label class="control-label">CEP:</label>
                                <input type="text" class="form-control cep" id="cep-principal-form-pf" name="cep_principal" required>
                            </div>
                            
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <label class="control-label">Número / Complemento:</label>
                                <input type="text" class="form-control" id="complemento-principal-form-pf" name="complemento_principal" required>
                            </div>   
                        </div>
                        <span class="help help-block"></span>
                        <div class="row">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label class="control-label">Rua:</label>
                                <input type="text" class="form-control" id="rua-principal-form-pf" name="rua_principal" required>
                            </div>
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label class="control-label">Bairro:</label>
                                <input type="text" class="form-control" id="bairro-principal-form-pf" name="bairro_principal" required>
                            </div>
                        </div>
                        <span class="help help-block"></span>
                        <div class="row">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label>Cidade:</label>
                                <input type="text" class="form-control" id="cidade-principal-form-pf" name="cidade_principal" required>
                            </div>
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label>Estado:</label>
                                <input type="text" class="form-control" id="estado-principal-form-pf" name="estado_principal" required>
                            </div>
                        </div>      
                        <br>
                    </div> 
                </form>
                
                <form id="formCadastroPJ"  class="col-md-12"  hidden>
                    <div class="row">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Nome:</label>
                            <input type="text" class="form-control" id="nome-form-pj" name="name" required>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label>Nome Fantasia:</label>
                            <input type="text" class="form-control" id="nome-fantasia-form-pj" name="nome_fantasia" value="" >
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">E-mail:</label>
                            <input type="text" class="form-control" id="email-form-pj" name="email" value="" required>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Login Vendedor:</label>
                            <input type="text" class="form-control" id="vendedor-form-pj" name="loginVendedor" value="<?= $_SESSION['emailUsuario']?>" required>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label>Documento:</label>
                            <input type="text" class="form-control" id="documento-form-pj" name="documento" value="" readonly>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <div class="row">
                                <div class="col-md-2">
                                    <Label class="control-label" style="display: flex;">DDD:</Label>
                                    <input type="text" class="form-control ddd" id="ddd-form-pj" name="ddd_telefone" required>
                                </div>
                                <div class="col-md-10">
                                    <Label class="control-label">Telefone:</Label>
                                    <input type="text" class="form-control fone" id="telefone-form-pj" name="telefone" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <hr>
                    <h4>Endereços</h4>
                    <div id="endereco-principal-form-pj">
                        <br>
                        <div class="row">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <label class="control-label">CEP:</label>
                                <input type="text" class="form-control cep" id="cep-principal-form-pj" name="cep_principal" required>
                            </div>
                            
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <label class="control-label">Número / Complemento:</label>
                                <input type="text" class="form-control" id="complemento-principal-form-pj" name="complemento_principal" required>
                            </div>  
                        </div>
                        <span class="help help-block"></span>
                        <div class="row">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label class="control-label">Rua:</label>
                                <input type="text" class="form-control" id="rua-principal-form-pj" name="rua_principal" required>
                            </div>
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label class="control-label">Bairro:</label>
                                <input type="text" class="form-control" id="bairro-principal-form-pj" name="bairro_principal" required>
                            </div>
                        </div>
                        <span class="help help-block"></span>
                        <div class="row">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label>Cidade:</label>
                                <input type="text" class="form-control" id="cidade-principal-form-pj" name="cidade_principal" required>
                            </div>
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label>Estado:</label>
                                <input type="text" class="form-control" id="estado-principal-form-pj" name="estado_principal" required>
                            </div>
                        </div>
                        <br>
                    </div>
                </form>

                <span class="help help-block"></span>
                <br>
                <hr>
                <h4>Arquivos</h4>
                <hr> 
                <div class="row">
                    <div class="col-md-9">
                        <label for="filesForm" class="btn btn-default col-md-12">Selecione um Arquivo</label>
                        <input id="filesForm" type="file" class="btn btn-default"  style="visibility:hidden;" name="arquivos"/>
                    </div>
                    <div class="col-md-3" >
                        <button type="button" class="btn btn-primary col-md-12" title="Adicionar" id="AddArquivoForm"><i class="fa fa-plus"></i> Adicionar</button>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table-responsive table-bordered table" id="tableArquivosForm">
                            <thead>
                                <tr>
                                    <th>Nome do Arquivo</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submit-form">Cadastrar Cliente</button>
                
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    var ativo = '<?= $active; ?>'
    var origem = '<?= $origem; ?>'

    $(document).ready(function(){
        if(ativo == "clientes"){
            $("#tab-clientes").click()
        } else if (ativo == "oportunidades") {
            $("#tab-oportunidades").click()

            if(origem == 'cadastroCliente'){
            }

        } else if (ativo == "quotes"){
            $("#tab-quotes").click()
        }

        var documento = '<?php if (isset($_SESSION['documento'])){echo $_SESSION['documento'];} ?>'

        if (documento) {
            $('#documentoPesquisa').val(documento);
        }
    })
</script>

<hr>



<script type="text/javascript">
    var formCliente = false;
    function showLoadingScreen() {
        var loadingScreen = document.createElement('div');
        loadingScreen.id = "loading-screen";
        loadingScreen.style.width = "100%";
        loadingScreen.style.height = "100%";
        loadingScreen.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
        loadingScreen.style.position = "fixed";
        loadingScreen.style.top = "0";
        loadingScreen.style.left = "0";
        loadingScreen.style.zIndex = "9999";
        loadingScreen.innerHTML = "<p>Carregando...</p>";
        document.body.appendChild(loadingScreen);
    }

    function hideLoadingScreen() {
        var loadingScreen = document.getElementById("loading-screen");
        loadingScreen.parentNode.removeChild(loadingScreen);
    }


    $(document).on('click', '.download_docs', function(e) {
        e.preventDefault();
        var botaoDownload = $(this);
        var idAnnotation = botaoDownload.attr('data-idAnnotation');
        botaoDownload.html('<i class="fa fa-spinner fa-spin"></i>');
        botaoDownload.attr('disabled', true);
        var downloadUrl = '<?php echo site_url('ComerciaisTelevendas/Pedidos/downloadArquivo?idAnnotation=') ?>'+idAnnotation;

        var downloadLink = document.createElement('a');
        downloadLink.href = downloadUrl;
        downloadLink.style.display = 'none';

        document.body.appendChild(downloadLink);

        downloadLink.click();

        document.body.removeChild(downloadLink);

        setTimeout(function() {
            botaoDownload.html('<i class="fa fa-download"></i>');
            botaoDownload.attr('disabled', false);
        }, 2000);
    });

    $('.tab-panee').click(function(e){
        e.preventDefault(0);
        ativo = $(this).attr('id');            

        switch (ativo) {
            case "tab-quotes":
                ativo = 'quotes';
                $('.feedback-alert').html('');
                break;
            case "tab-clientes":
                ativo = 'clientes';
                $('.feedback-alert').html('');
                break;
            case "tab-oportunidades":
                ativo = 'oportunidades';
                
                break;
            case "tab-gerenciamento":
                ativo = 'gerenciamento';
                $('.feedback-alert').html('');
                break;
            default:
                ativo = 'clientes';
                $('.feedback-alert').html('');
                break;
        }

        AtualizarFiltro();
        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/setaActive') ?>",
            type: 'GET',            
            data: {
                value: ativo,
            },
            beforeSend: function(){
                if (formCliente){
                    return false;
                }else{
                    document.getElementById('loading').style.display = 'block';
                }
            },
            success: function(data){                
            },
            complete: function(){
                if (formCliente){
                    return false;
                }else{	
                    document.getElementById('loading').style.display = 'none';
                }
            },
        });
        formCliente = false;
    })

    function formatarMoeda(campo) {
        var elemento = document.getElementById(campo);
        var valor = elemento.value;
        
        let negativo = false;
        if(valor.includes('-'))
            negativo = true;

        valor = valor.toString().replace(/\D/g, '');

        valor = (parseFloat(valor) / 100).toFixed(2).toString();
        valor = valor.replace('.', ',');

        if (valor.length > 6) {
            valor = valor.replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
        }

        elemento.value = negativo ? "-" + valor : valor;
        if (valor == 'NaN') elemento.value = negativo ? "-" : '';
    }
    
    function formatarPorcetagem(campo) {
        var elemento = document.getElementById(campo);
        var valor = elemento.value;

        let negativo = false;
        if(valor.includes('-'))
            negativo = true;

        valor = valor.toString().replace(/\D/g, '');
        valor = (parseFloat(valor) / 100).toFixed(2);
        if(valor > 100) valor = 100.00;
        valor = valor.toString().replace('.', ',');
        
        elemento.value = negativo ? "-" + valor : valor;
        if (valor == 'NaN') elemento.value = negativo ? "-" : '';
    }
    
    function parseValueToFloat(value) {
        if (!value.includes(",")) {
            if (Number.isNaN(Number.parseFloat(value))) {
                return 0;
            }
            return parseFloat(value);            
        }
        value = value.replace(".", "").replace(",", ".");
        if (Number.isNaN(Number.parseFloat(value))) {
            return 0;
        }
        return parseFloat(value);
    }

    function removerDocumento(element, event, idAnnotation) {
        event.preventDefault();
        if(confirm('Deseja realmente remover esta observação?')){
            let botao = $(element);
            let documento = botao.attr('data-documento');
            let cotacaoId = idCotacao;
            botao.html('<i class="fa fa-spinner fa-spin"></i>');
            botao.attr('disabled', true);

            $.ajax({
                url: `<?= site_url('ComerciaisTelevendas/Pedidos/removerDocumentos') ?>`,
                type: 'POST',
                data: {idDocumento: idAnnotation},
                success: function (response){ 
                    dataJson = JSON.parse(response)
                    if(dataJson.status == 204){
                        alert('Observação removida com sucesso!');
                        botao.html('<i class="fa fa-trash"></i>');
                        botao.attr('disabled', false);
                        if (documento){
                            AtualizarTabelaDocumentos(documento);
                        }else{
                            AtualizarTabelaDocumentosCotacao(cotacaoId)
                        }

                    }else{
                        alert('Erro ao remover observação!');
                        botao.html('<i class="fa fa-trash"></i>');
                        botao.attr('disabled', false);
                    } 
                },
                error: function (response) {
                    alert('Erro ao remover observação!');
                    botao.html('<i class="fa fa-trash"></i>');
                    botao.attr('disabled', false);
                    
                }
            });
        }else{
            return false;
        }
    }
    
    function ativarBotaoSpin (botao){
        botao.html('<i class="fa fa-spinner fa-spin" style="font-size: 18px"/></i>');
        botao.attr('disabled', true);
    }

    function desativarBotaoSpin (botao, html){
        botao.html(html);
        botao.attr('disabled', false);
    }
</script>
<script type="text/javascript"src="<?= versionFile('media/js', 'html2pdf.bundle.min.js') ?>"></script>
<script type="text/javascript"src="<?= versionFile('assets/js/televendas/adv', 'avaliacao_cliente.js') ?>"></script>

<!-- script Resumo -->
<script type="text/javascript">

    var clienteERP = ""
    var nomeERP = ""
    var sobrenomeERP = ""
    var nomeFantasiaERP = ""
    var emailERP = ""
    var vendedorERP = ""
    var documentoERP = ""
    var rgERP = ""
    var nascimentoERP = ""
    var ieERP = ""
    var dddERP = ""
    var dddERP2 = ""
    var telefoneERP = ""
    var telefoneERP2 = ""
    var cepPrincipalERP = ""
    var complementoPrincipalERP = ""
    var ruaPrincipalERP = ""
    var bairroPrincipalERP = ""
    var cidadePrincipalERP = ""
    var estadoPrincipalERP = ""
    var cepCobrancaERP = ""
    var complementoCobrancaERP = ""
    var ruaCobrancaERP = ""
    var bairroCobrancaERP = ""
    var cidadeCobrancaERP = ""
    var estadoCobrancaERP = ""
    var cepEntregaERP = ""
    var complementoEntregaERP = ""
    var ruaEntregaERP = ""
    var bairroEntregaERP = ""
    var cidadeEntregaERP = ""
    var estadoEntregaERP = ""

    var signatario_mei = ""
    var email_signatario_mei = ""
    var documento_signatario_mei = ""

    var idCotacao = ""
    var tableHardwaresCotacao;
    var tableLicencasCotacao;
    var tableServicosCotacao;
    var tableDocumentosCotacao;

    let HardwaresValorTotal = 0;
    let LicencasValorTotal = 0;
    let ServicoValorTotal = 0;
    
    var bandeiraCartaoCredito = '';
    var numBandeiraCartaoCredito = '';
    var tableArquivoForm;
    
    document.getElementById('numeroCartao').addEventListener('input', function() {
        var numeroCartao = this.value;
        
        var bandeira = ''
        var numBandeira = '';

        // Expressões regulares para identificar a bandeira do cartão
        var visaRegex = /^4[0-9]{12}(?:[0-9]{3})?$/; // Visa
        var mastercardRegex = /^(5[1-5]|2[2-7])[0-9]{14}$/; // Mastercard
        var amexRegex = /^3[47][0-9]{13}$/; // American Express
        var dinersRegex = /^3(?:0[0-5]|[68][0-9])[0-9]{11}$/; // Diners Club
        var jcbRegex = /^(?:2131|1800|35\d{3})\d{11}$/; // JCB
        var hipercardRegex = /^606282|^3841(?:[0|4|6]{1})0$/; // Hipercard

        if (numeroCartao.match(visaRegex)) {
            bandeira = 'Visa';
            numBandeira = '7';
        } else if (numeroCartao.match(mastercardRegex)) {
            bandeira = 'Mastercard';
            numBandeira = '5';
        } else if (numeroCartao.match(amexRegex)) {
            bandeira = 'Amex';
            numBandeira = '1';
        } else if (numeroCartao.match(dinersRegex)) {
            bandeira = 'Diners';
            numBandeira = '2';
        } else if (numeroCartao.match(jcbRegex)) {
            bandeira = 'Jcb';
            numBandeira = '4';
        } else if (numeroCartao.match(hipercardRegex)) {
            bandeira = 'Hipercard';
            numBandeira = '3';
        }else {
            bandeira = 'Desconhecida';
            numBandeira = '0';
        }

        bandeiraCartaoCredito = bandeira;
        numBandeiraCartaoCredito = numBandeira;

    });


    $(document).ready(function() {
        $('.buttonResumoEmail').on('click', function(e) {
            e.preventDefault();
            botao = $(this);
            botao.html('<i class="fa fa-spinner fa-spin"></i>');
            botao.attr('disabled', true);

            $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/enviarResumoCotacao') ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    idCotacao: idCotacao,
                },
                success: function(data){
                    if (data.code == 200) {
                        alert("Email enviado com sucesso!");
                        botao.html(' <i aria-hidden="true"></i> Enviar Email');
                        botao.attr('disabled', false);
                    }else{
                        alert("Não foi possível enviar! Tente novamente.");
                        botao.html(' <i aria-hidden="true"></i> Enviar Email');
                        botao.attr('disabled', false);
                    }
                },
                error: function(data){
                    
                    alert("Não foi possível enviar! Tente novamente.");
                    botao.html(' <i aria-hidden="true"></i> Enviar Email');
                    botao.attr('disabled', false);
                },
            });
            
        });

        $('.buttonResumoPDF').on('click', function(e) {
            e.preventDefault();
            var btn = $(this);

            btn.attr('disabled', true).html('<i style="font-size: 28px;" class="fa fa-spinner fa-spin"></i>');

            var pdfConfig = {
                filename: 'template_pdf_cotacao.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            $.ajax({
                url: '<?php echo site_url('ComerciaisTelevendas/Pedidos/template_pdf_cotacao_html?idCotacao=') ?>'+idCotacao,
                type: 'GET',
                success: function (data) {
                    data = JSON.parse(data);
                    if(data){
                        var htmlContent = data.result;
                        html2pdf().from(htmlContent).set(pdfConfig).outputPdf().then(function(pdf) {
                            // Converta o PDF para base64
                            var pdfData = btoa(pdf);

                            // Crie um Blob a partir do base64
                            var blob = new Blob([Uint8Array.from(atob(pdfData), c => c.charCodeAt(0))], { type: 'application/pdf' });

                            // Crie um URL do Blob
                            var pdfUrl = URL.createObjectURL(blob);

                            // Abra o PDF em uma nova aba
                            window.open(pdfUrl, '_blank');
                        }).catch(function(error) {
                            alert('Não foi possível gerar o PDF. Tente Novamente!');
                        });
                    } else {
                        alert('Não foi possível gerar o PDF. Tente Novamente!');
                    }
                },
                error: function () {
                    alert('Não foi possível gerar o PDF. Tente Novamente!');
                },
                complete: function () {
                    btn.attr('disabled', false).html(' <i aria-hidden="true"></i>Resumo PDF');
                }
            });

        });

        tableHardwaresCotacao = $("#tableHardwaresCotacao").DataTable({
            responsive: true,
            ordering: true,
            paging: true,
            searching: true,
            info: true,
            language: {
                loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
                searchPlaceholder:  'Pesquisar',
                emptyTable:         "Nenhum hardware a ser listado",
                info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
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
                { data: 'hardwareid',
                    visible: false},
                { data: 'produto' },
                { data: 'valorUnitarioCotacao',  
                    render: function (data){ valor = data;                               
                    if(valor === '-'){ 
                        return valor;
                    }else{
                        return parseValueToFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}} 
                },
                { data: 'quantidadeCotacao' },
                { data: 'valorTotalCotacao', 
                    render: function (data){ valor = data;                                               
                    if(valor === '-'){ 
                        return valor;
                    }else{
                        return parseValueToFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}} 
                },
                { data: 'percentualDescontoCotacao' },
                {
                    data:{ 'hardwareid': 'hardwareid' },
                    orderable: false,
                    render: function (data) {
                        valorUnitario = data['valorUnitarioCotacao'].replaceAll(".", "").replaceAll(",", ".");
                        valorTotal = valorUnitario * data['quantidadeCotacao'];
                        return `
                        <button 
                            id="btnDescontoHardware"
                            class="btn btn-primary"
                            title="Desconto"
                            style="width: 38px; margin: 0 auto; background-color: #04acf4; color: white;"
                            onClick="javascript:abrirInput(this, '${data['hardwareid']}', event)">
                            <i class="fa fa-money" aria-hidden="true" style="display: flex;align-items: center;justify-content: center; "></i>
                        </button>
                        
                        <div style="display: none" id="divDesconto-${data['hardwareid']}">
                            <select class="form-control tipoDesconto" id="tipoDesconto-${data['hardwareid']}" style=" margin-top: 5px; width: 200px" onChange="javascript:tipoDesconto(this, '${data['hardwareid']}', event )">
                                <option value="1" >Número</option>
                                <option value="2" >Porcentagem</option>
                            </select>
                            <input type="text" id="inputValor-${data['hardwareid']}" value="${valorTotal}" hidden></input>
                            <input type="text" class="form-control" id="inputDesconto-${data['hardwareid']}" placeholder="Valor do desconto em R$" style="margin-top: 5px; width: 200px" onkeyup="formatarMoeda(this.id)">
                            </input>
                            <div style="display: flex; justify-content: flex-star; margin-top: 5px;">
                                <button
                                    title: "Cancelar Desconto" 
                                    style="color: red"
                                    id="btnCancelarDescontoHardware"
                                    onClick="javascript:fechaInputDesconto(this, '${data['hardwareid']}', event )">
                                    <i class="fa fa-times" aria-hidden="true" style="vertical-align: middle;"></i>
                                </button>
                                <button 
                                    title: "Confirmar Desconto"
                                    style="color: green"
                                    id="btnConfirmarDescontoHardware"
                                    onClick="javascript:confirmaDesconto(this, '${data['hardwareid']}', 'hardware', event)">    
                                    <i class="fa fa-check" aria-hidden="true" style="vertical-align: middle; display: inline-block;"></i>
                                </button>
                            </div>
                        </div>
                        <button 
                            id="btnRemoverHardware"
                            class="btn"
                            title="Remover"
                            style="width: 38px; margin: 0 auto; background-color: red; color: white;"
                            onClick="javascript:removerSubItem(this, '${data['hardwareid']}', 'hardware', event)">
                            <i class="fa fa-trash" aria-hidden="true" style="display: flex;align-items: center;justify-content: center; "></i>
                        </button>
                        `;
                    }
                }
            ],
            footerCallback: function (row, data, start, end, display) {
                var api = this.api();
    
                // Total over all pages
                total = api
                    .column(4)
                    .data()
                    .reduce(function (a, b) {
                        return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(4, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                    }, 0);

                // Update footer
                $(api.column(6).footer()).html(pageTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}) + ' ( ' + total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}) + ' total)');
                
                HardwaresValorTotal = total;
                let CotacaoGastoTotal = HardwaresValorTotal + LicencasValorTotal + ServicoValorTotal;
                let CotacaoGastoTotalFormat = CotacaoGastoTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})
                $("#CotacaoGastoTotal").html(CotacaoGastoTotalFormat);
            },
        });

        tableLicencasCotacao = $('#tableLicencasCotacao').DataTable({
            responsive: true,
                ordering: true,
                paging: true,
                searching: true,
                info: true,
                language: {
                    loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
                    searchPlaceholder:  'Pesquisar',
                    emptyTable:         "Nenhuma licença a ser listada",
                    info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
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
                    { data: 'licencaid',
                        visible: false},
                    { data: 'produto' },
                    { data: 'valorUnitarioCotacao',  
                        render: function (data){ valor = data;                               
                        if(valor === '-'){ 
                            return valor;
                        }else{
                            return parseValueToFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}} 
                    },
                    
                    { data: 'quantidadeCotacao' },
                    { data: 'valorTotalCotacao',  
                        render: function (data){ valor = data;                              
                        if(valor === '-'){ 
                            return valor;
                        }else{
                            return parseValueToFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}} 
                    },    
                    { data: 'planoSatelitalCotacao'},    
                    { data: 'percentualDescontoCotacao'},
                    {
                        data:{ 'licencaid': 'licencaid' },
                        orderable: false,
                        render: function (data) {
                            valorUnitario = data['valorUnitarioCotacao'].replaceAll(".", "").replaceAll(",", ".");
                            valorTotal = valorUnitario * data['quantidadeCotacao'];
                            return `
                            <button 
                                id="btnDescontoLicenca"
                                class="btn btn-primary"
                                title="Desconto"
                                style="width: 38px; margin: 0 auto; background-color: #04acf4; color: white;"
                                onClick="javascript:abrirInput(this, '${data['licencaid']}', event)">
                                <i class="fa fa-money" aria-hidden="true" style="display: flex;align-items: center;justify-content: center; "></i>
                            </button>
                            
                            <div style="display: none" id="divDesconto-${data['licencaid']}">
                                <select class="form-control tipoDesconto" id="tipoDesconto-${data['licencaid']}" style=" margin-top: 5px; width: 200px" onChange="javascript:tipoDesconto(this, '${data['licencaid']}', event )">
                                    <option value="1" >Número</option>
                                    <option value="2" >Porcentagem</option>
                                </select>
                                <input type="text" id="inputValor-${data['licencaid']}" value="${valorTotal}" hidden></input>
                                <input type="text" class="form-control" id="inputDesconto-${data['licencaid']}" placeholder="Valor do desconto em R$" style="margin-top: 5px; width: 200px" onkeyup="formatarMoeda(this.id)">
                                </input>
                                <div style="display: flex; justify-content: flex-star; margin-top: 5px;">
                                    <button
                                        title: "Cancelar Desconto" 
                                        style="color: red"
                                        id="btnCancelarDescontoLicenca"
                                        onClick="javascript:fechaInputDesconto(this, '${data['licencaid']}', event )">
                                        <i class="fa fa-times" aria-hidden="true" style="vertical-align: middle;"></i>
                                    </button>
                                    <button 
                                        title: "Confirmar Desconto"
                                        style="color: green"
                                        id="btnConfirmarDescontoLicenca"
                                        onClick="javascript:confirmaDesconto(this, '${data['licencaid']}', 'licenca', event)">    
                                        <i class="fa fa-check" aria-hidden="true" style="vertical-align: middle; display: inline-block;"></i>
                                    </button>
                                </div>
                            </div>
                            <button 
                                id="btnRemoverLicenca"
                                class="btn"
                                title="Remover"
                                style="width: 38px; margin: 0 auto; background-color: red; color: white;"
                                onClick="javascript:removerSubItem(this, '${data['licencaid']}', 'licenca', event)">
                                <i class="fa fa-trash" aria-hidden="true" style="display: flex;align-items: center;justify-content: center; "></i>
                            </button>
                            `;
                        }
                    }
                ],
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
        
                    // Total over all pages
                    total = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                        }, 0);

                    // Total over this page
                    pageTotal = api
                        .column(4, { page: 'current' })
                        .data()
                        .reduce(function (a, b) {
                            return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                        }, 0);

                    // Update footer
                    $(api.column(6).footer()).html(pageTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}) + ' ( ' + total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}) + ' total)');
                    
                    LicencasValorTotal = total;
                    let CotacaoGastoTotal = HardwaresValorTotal + LicencasValorTotal + ServicoValorTotal;
                    let CotacaoGastoTotalFormat = CotacaoGastoTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})
                    $("#CotacaoGastoTotal").html(CotacaoGastoTotalFormat);
                },
        });

        tableServicosCotacao = $('#tableServicosCotacao').DataTable({
            responsive: true,
            ordering: true,
            paging: true,
            searching: true,
            info: true,
            language: {
                loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
                searchPlaceholder:  'Pesquisar',
                emptyTable:         "Nenhum serviço a ser listado",
                info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
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
                { data: 'servicoid',
                    visible: false},
                { data: 'produto' },
                { data: 'valorUnitarioCotacao',  
                    render: function (data){ valor = data;                               
                    if(valor === '-'){ 
                        return valor;
                    }else{
                        return parseValueToFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}} 
                },
                { data: 'quantidadeCotacao' },
                { data: 'valorTotalCotacao',  
                    render: function (data){ valor = data;                               
                    if(valor === '-'){ 
                        return valor;
                    }else{
                        return parseValueToFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}} 
                },
                { data: 'percentualDescontoCotacao'},
                {
                    data:{ 'servicoid': 'servicoid' },
                    orderable: false,
                    render: function (data) {
                        valorUnitario = data['valorUnitarioCotacao'].replaceAll(".", "").replaceAll(",", ".");
                        valorTotal = valorUnitario * data['quantidadeCotacao'];
                        return `
                        <button 
                            id="btnDescontoServico"
                            class="btn btn-primary"
                            title="Desconto"
                            style="width: 38px; margin: 0 auto; background-color: #04acf4; color: white;"
                            onClick="javascript:abrirInput(this, '${data['servicoid']}', event)">
                            <i class="fa fa-money" aria-hidden="true" style="display: flex;align-items: center;justify-content: center; "></i>
                        </button>
                        
                        <div style="display: none" id="divDesconto-${data['servicoid']}">
                            <select class="form-control tipoDesconto" id="tipoDesconto-${data['servicoid']}" style=" margin-top: 5px; width: 200px" onChange="javascript:tipoDesconto(this, '${data['servicoid']}', event )">
                                <option value="1" >Número</option>
                                <option value="2" >Porcentagem</option>
                            </select>
                            <input type="text" id="inputValor-${data['servicoid']}" value="${valorTotal}" hidden></input>
                            <input type="text" class="form-control" id="inputDesconto-${data['servicoid']}" placeholder="Valor do desconto em R$" style="margin-top: 5px; width: 200px" onkeyup="formatarMoeda(this.id)">
                            </input>
                            <div style="display: flex; justify-content: flex-star; margin-top: 5px;">
                                <button
                                    title: "Cancelar Desconto" 
                                    style="color: red"
                                    id="btnCancelarDescontoServico"
                                    onClick="javascript:fechaInputDesconto(this, '${data['servicoid']}', event )">
                                    <i class="fa fa-times" aria-hidden="true" style="vertical-align: middle;"></i>
                                </button>
                                <button 
                                    title: "Confirmar Desconto"
                                    style="color: green"
                                    id="btnConfirmarDescontoServico"
                                    onClick="javascript:confirmaDesconto(this, '${data['servicoid']}', 'servico', event)">    
                                    <i class="fa fa-check" aria-hidden="true" style="vertical-align: middle; display: inline-block;"></i>
                                </button>
                            </div>
                        </div>
                        <button 
                            id="btnRemoverServico"
                            class="btn"
                            title="Remover"
                            style="width: 38px; margin: 0 auto; background-color: red; color: white;"
                            onClick="javascript:removerSubItem(this, '${data['servicoid']}', 'servico', event)">
                            <i class="fa fa-trash" aria-hidden="true" style="display: flex;align-items: center;justify-content: center; "></i>
                        </button>
                        `;
                    }
                }
            ],
            footerCallback: function (row, data, start, end, display) {
                var api = this.api();
    
                // Total over all pages
                total = api
                    .column(4)
                    .data()
                    .reduce(function (a, b) {
                        return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(4, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                    }, 0);

                // Update footer
                $(api.column(6).footer()).html(pageTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}) + ' ( ' + total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}) + ' total)');
                
                ServicoValorTotal = total;
                let CotacaoGastoTotal = HardwaresValorTotal + LicencasValorTotal + ServicoValorTotal;
                let CotacaoGastoTotalFormat = CotacaoGastoTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})
                $("#CotacaoGastoTotal").html(CotacaoGastoTotalFormat);
            },
        });

        tableDocumentosCotacao = $('#table-documentosCotacao').DataTable({
            responsive: true,
            ordering: false,
            paging: true,
            info: true,
            language: lang.datatable,
            deferRender: true,
            lengthChange: false,
            columns: [
                { data: 'assunto' },
                { 
                    data: 'arquivo',
                    render: function (data, type, row) {
                        return data || 'Sem Arquivo';
                    }
                },
                { data: 'idAnnotation' },
            ],
            columnDefs: [
                {
                    render: function (data, type, row) // Visualizar Arquivos
                    {
                        return `<div>
                                <button 
                                    id="btnRemoveDocumentoCot"
                                    class="btn btn-md"
                                    title="Remover Observação"
                                    style="margin: 0 auto; background-color: red; color: white;"
                                    onClick="javascript:removerDocumento(this, event, '${data}')">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                                ${row.arquivo ? `<a data-idAnnotation="${data}" class="btn btn-md btn-primary download_docs" role="button" title="Download"><i class="fa fa-download"></i></a>` : '' }
                                </div>`;
                    },
                    targets: 2
                }
            ],
        });
        
        
        $('#tab-dadosGerais').click(function (e){
            e.preventDefault();
            $('#dadosGerais').show();
            $('#composicao').hide();
            $('#hardwares').hide();
            $('#licencas').hide();
            $('#servicos').hide();
            $('#configurometro').hide();
            $("#documentosCotacao").hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide(); 
            $('#conteudo-aba-avaliacao-cliente').hide();
        })

        $('#tab-documentosCotacao').click(function (e){
            e.preventDefault();
            $("#documentosCotacao").show();
            $('#dadosGerais').hide();
            $('#composicao').hide();
            $('#hardwares').hide();
            $('#licencas').hide();
            $('#servicos').hide();
            $('#configurometro').hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide();
            $('#conteudo-aba-avaliacao-cliente').hide();



            $.ajax({
                url: '<?php echo site_url('ComerciaisTelevendas/Pedidos'); ?>'+ '/getDocumentosCotacao',
                type: 'POST',
                data: {idCotacao: idCotacao},
                success: function (data) {
                    data = JSON.parse(data);
                    if(data.status == 200){
                        response = data.data;
                        tableDocumentosCotacao.clear().draw();
                        tableDocumentosCotacao.rows.add(response).draw();
                    }
                }
            });
        });

        
        $('#tab-composicao').click(function (e){
            e.preventDefault();
            $('#composicao').show();
            $('#dadosGerais').hide();
            $('#hardwares').hide();
            $('#licencas').hide();
            $('#servicos').hide();
            $('#configurometro').hide();
            $("#documentosCotacao").hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide(); 
            $('#conteudo-aba-avaliacao-cliente').hide();

        })


        $('#tab-hardwares').click(function (e){
            e.preventDefault();
            $('#hardwares').show();
            $('#dadosGerais').hide();
            $('#composicao').hide();
            $('#licencas').hide();
            $('#servicos').hide();
            $('#configurometro').hide();
            $("#documentosCotacao").hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide(); 
            $('#conteudo-aba-avaliacao-cliente').hide();
            
            AtualizarModalResumoCotacao(null, null, null);
        })

        $('#tab-licencas').click(function (e){
            e.preventDefault();
            $('#licencas').show();
            $('#dadosGerais').hide();
            $('#composicao').hide();
            $('#hardwares').hide();
            $('#servicos').hide();
            $('#configurometro').hide();
            $("#documentosCotacao").hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide(); 
            $('#conteudo-aba-avaliacao-cliente').hide();
        })

        $('#tab-servicos').click(function (e){
            e.preventDefault();
            $('#servicos').show();
            $('#dadosGerais').hide();
            $('#composicao').hide();
            $('#hardwares').hide();
            $('#licencas').hide();
            $('#configurometro').hide();
            $("#documentosCotacao").hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide(); 
            $('#conteudo-aba-avaliacao-cliente').hide();
        })

        $('#tab-cartaoCredito').click(function (e){
            e.preventDefault();
            $('#divCartaoCredito').show();
            $('#btnSalvarAtualizaCartao').show(); 
            $('#servicos').hide();
            $('#dadosGerais').hide();
            $('#composicao').hide();
            $('#hardwares').hide();
            $('#licencas').hide();
            $('#configurometro').hide();
            $("#documentosCotacao").hide();
            $('#conteudo-aba-avaliacao-cliente').hide();

            $('#numeroCartao').val('');
            $('#codigoCartao').val('');
            $('#nomeCartao').val('');
            $('#mesValidadeCartao').val('');
            $('#anoValidadeCartao').val('');
            $('#bandeiraCartao').val(0);
            document.getElementById("numeroCartao").style.backgroundImage = "url('')";
            $('#codigoCartao').prop('disabled', true);
            $('#nomeCartao').prop('disabled', true);
            $('#mesValidadeCartao').prop('disabled', true);
            $('#anoValidadeCartao').prop('disabled', true);
            $('#selectBandeira').hide();
        })

        $('#tab-avaliacaoCliente').click(function (e){
            e.preventDefault();
            $('#conteudo-aba-avaliacao-cliente').show();
            $('#servicos').hide();
            $('#dadosGerais').hide();
            $('#composicao').hide();
            $('#hardwares').hide();
            $('#licencas').hide();
            $('#configurometro').hide();
            $("#documentosCotacao").hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide(); 

            // Carregar dados da avaliação do cliente
            carregarDadosAvaliacaoCliente(idCotacao);
        })

        $('#tab-configurometro').click(function (e){
            e.preventDefault();    
            $('#configurometro').show();
            $('#dadosGerais').hide();
            $('#composicao').hide();
            $('#hardwares').hide();
            $('#licencas').hide();
            $('#servicos').hide();
            $("#documentosCotacao").hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide();
            $('#conteudo-aba-avaliacao-cliente').hide();

            var iframe = document.getElementById("iframePagina");    
            var url = '';
            $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/getVeiculoCotId') ?>",
                dataType: "json",
                type: "POST",
                data: { idCotacao: idCotacao},
                beforeSend: function() {            
                    $('#loadingConfigurometro').show();
                },
                success: function(data){
                    if(data.status == 200 && data.results.value.length > 0){
                        $.ajax({
                            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/pegarURLConfigurometro') ?>",
                            type: "POST",
                            data: { idVeiculo:  data.results.value[0]['tz_tipo_veiculo_cotacaoid']},
                            success:function (retorno){
                                iframe.onload = function() {                            
                                    $('#loadingConfigurometro').hide();
                                };
                                iframe.src = retorno;
                            }
                        });
                    } else {
                        alert("Não foi possível encontrar o configurômetro!")
                        $('#loadingConfigurometro').hide();
                    }
                },
                complete: function(){

                },                                  
            });
        });
        
        $('.buttonAtualizarResumo').on('click', function(e) {
            e.preventDefault();
            botao = $(this);
            AtualizarModalResumoCotacao(botao, '<i class="fa fa-refresh" aria-hidden="true" style="font-size: 2.7rem;"></i>', '<i class="fa fa-spinner fa-spin" style="font-size: 2.7rem;"></i>');
        });

        $('#addHardware').click(function(e){
            e.preventDefault();
            $('#selectHardware').val('').trigger('change');
        });

        $('#addLicenca').click(function(e){
            e.preventDefault();
            $('#selectLicenca').val('').trigger('change');
        });

        $('#addServico').click(function(e){
            e.preventDefault();
            $('#selectServico').val('').trigger('change');
        });

        $('#selectHardware').select2({
            ajax:{
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/produtosComposicao') ?>",
                type: 'POST',
                dataType: 'json',
                delay: 1000,
                data: function (params) {
                    return {
                        nomeProduto : params.term,
                        numeroProduto: params.term
                    };

                },
                
            },
            placeholder: 'Selecione um produto',
            allowClear: true,
            language: "pt-BR",
        });

        $('#selectLicenca').select2({
            ajax:{
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/produtosComposicao') ?>",
                type: 'POST',
                dataType: 'json',
                delay: 1000,
                data: function (params) {
                    return {
                        nomeProduto : params.term,
                        numeroProduto: params.term
                    };

                },
                
            },
            placeholder: 'Selecione um produto',
            allowClear: true,
            language: "pt-BR",
        });

        $('#selectServico').select2({
            ajax:{
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/produtosComposicao') ?>",
                type: 'POST',
                dataType: 'json',
                delay: 1000,
                data: function (params) {
                    return {
                        nomeProduto : params.term,
                        numeroProduto: params.term
                    };

                },
                
            },
            placeholder: 'Selecione um produto',
            allowClear: true,
            language: "pt-BR",
        });

        $('#btnSalvarAddSubItem').click(function(e){
            e.preventDefault();
            var idProduto = $('#selectHardware').val();
            var idCot = idCotacao;
            var quantidade = $('#quantidadeHardware').val();

            botao = $('#btnSalvarAddSubItem');
            botao.html('<i class="fa fa-spinner fa-spin"></i>');
            botao.attr('disabled', true);

            $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/addSubItemC') ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    idCotacao: idCot,
                    idProduto: idProduto,
                    tipo : 'hardware',
                    quantidade: quantidade
                },
                success: function(data){
                    if(data.results.Status == 200){
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert(data.results.Message);
                        $('#modalHardware').modal('hide');
                        $('#quantidadeHardware').val('');
                        AtualizarModalResumoCotacao(null, null, null);
                    }else if (!idProduto) {
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert('Selecione um produto!');

                    }else{
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert(data.results.Message);
                    }
                },
            });

        });

        $('#btnSalvarAddSubItemLicenca').click(function(e){
            e.preventDefault();

            $('.feedback-alert').html('');
            
            var idProduto = $('#selectLicenca').val();
            var idCot = idCotacao;
            var quantidade = $('#quantidadeLicenca').val();

            botao = $('#btnSalvarAddSubItemLicenca');
            botao.html('<i class="fa fa-spinner fa-spin"></i>');
            botao.attr('disabled', true);

            $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/addSubItemC') ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    idCotacao: idCot,
                    idProduto: idProduto,
                    tipo : 'licenca',
                    quantidade: quantidade
                },
                success: function(data){
                    if(data.results.Status == 200){
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert(data.results.Message);
                        $('#modalLicenca').modal('hide');
                        $('#quantidadeLicenca').val('');
                        AtualizarModalResumoCotacao(null, null, null);
                    }else if (!idProduto) {
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert('Selecione um produto!');
                    }
                    else{
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert(data.results.Message);
                    }
                },
            });

        });

        $('#btnSalvarAddSubItemServico').click(function(e){
            e.preventDefault();

            $('.feedback-alert').html('');

            var idProduto = $('#selectServico').val();
            var idCot = idCotacao;
            var quantidade = $('#quantidadeServico').val();

            botao = $('#btnSalvarAddSubItemServico');
            botao.html('<i class="fa fa-spinner fa-spin"></i>');
            botao.attr('disabled', true);

            $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/addSubItemC') ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    idCotacao: idCot,
                    idProduto: idProduto,
                    tipo : 'servico',
                    quantidade: quantidade
                },
                success: function(data){
                    if(data.results.Status == 200){
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert(data.results.Message);
                        $('#modalServico').modal('hide');
                        $('#quantidadeServico').val('');
                        AtualizarModalResumoCotacao(null, null, null);

                    }else if (!idProduto) {
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert('Selecione um produto!');
                    }
                    else{
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert(data.results.Message);
                    }
                },
            });

        });

        $("#btn-adicionarDoc").click(function (e){
            e.preventDefault();

            $('.feedback-alert').html('');

            botao = $(this);
            botaohtml = $(this).html();
            ativarBotaoSpin(botao);

            let dataForm = new FormData();
            let cotacao = idCotacao;
            let assunto = $('#assuntoDocCotacao').val();
            let file_data = $('#docs_cotacao')[0];
            let file = file_data.files[0];

            dataForm.set("cotacao_id", cotacao);
            dataForm.set("assunto", assunto);
            dataForm.set("observacao", "Anexo da cotação");

            
            let url = "<?= site_url('ComerciaisTelevendas/Pedidos/cadastrarObservacao') ?>";
            if(file) { 
                dataForm.set("Arquivo", file);
                url = "<?= site_url('ComerciaisTelevendas/Pedidos/addArquivoCotacaoCRM') ?>";
            }
            
            $.ajax({
                url: url,
                type: "POST",
                data: dataForm,     
                processData: false,
                contentType: false,  
                success: function(data){
                    document.getElementById('loading').style.display = 'none';
                    let datajson = JSON.parse(data);
                    const statusRetorno = datajson?.Status ?? datajson?.status;

                    if(statusRetorno && statusRetorno == 200){
                        alert("Observação enviada com sucesso.");
                        AtualizarTabelaDocumentosCotacao(cotacao);
                        $('#assuntoDocCotacao').val("");
                        $('#docs_cotacao').val("");
                        $('#docs_cotacao_label').html("Selecione um Arquivo");
                    }
                    else{
                        alert(datajson?.ExceptionMessage ?? "Ocorreu um problema ao enviar a observação.");
                    }
                },
                error: function(error){
                    console.log(error);
                    alert("Ocorreu um problema ao enviar a observação. Com o seguinte erro:" + error);
                },
                complete: function(){
                    desativarBotaoSpin(botao, botaohtml);
                },
            });  
        })

        $('#numeroCartao').keyup(function(e){
            var numeroCartao = $('#numeroCartao').val();
            if (numeroCartao.length >= 14){
            $.ajax({
                        method: "POST",
                        url: "Pedidos/validarCartaoCredito",
                        data: { numero: numeroCartao }
                    })
                    .done(function(result) {
                        if (result == 0 && bandeiraCartaoCredito != 'Desconhecida' ) {
                            var url = "<?php echo base_url('media/img/bandeira-cartoes') ?>"

                            document.getElementById("numeroCartao").style.backgroundImage = "url(" + url + "/" + bandeiraCartaoCredito + '.png' + ")";
                            $('#label-numeroCartao').hide();
                            $('#codigoCartao').prop('disabled', false);
                            $('#nomeCartao').prop('disabled', false);
                            $('#mesValidadeCartao').prop('disabled', false);
                            $('#anoValidadeCartao').prop('disabled', false);
                            $('#selectBandeira').hide();
                        }else if (result == 0 && bandeiraCartaoCredito == 'Desconhecida'){
                            document.getElementById("numeroCartao").style.backgroundImage = "url('')";
                            $('#selectBandeira').show();
                            $('#label-numeroCartao').hide();
                            $('#codigoCartao').prop('disabled', false);
                            $('#nomeCartao').prop('disabled', false);
                            $('#mesValidadeCartao').prop('disabled', false);
                            $('#anoValidadeCartao').prop('disabled', false);
                        }else{
                            $('#selectBandeira').hide();
                            document.getElementById("numeroCartao").style.backgroundImage = "url('')";
                            $('#label-numeroCartao').show();
                            $('#codigoCartao').prop('disabled', true);
                            $('#nomeCartao').prop('disabled', true);
                            $('#mesValidadeCartao').prop('disabled', true);
                            $('#anoValidadeCartao').prop('disabled', true);
                        }
                    });
            }else{
                $('#label-numeroCartao').show();
                $('#codigoCartao').prop('disabled', true);
                $('#nomeCartao').prop('disabled', true);
                $('#mesValidadeCartao').prop('disabled', true);
                $('#anoValidadeCartao').prop('disabled', true);
            }
        });

        $('#btnSalvarAtualizaCartao').click(function(e){
            e.preventDefault();

            $('.feedback-alert').html('');

            const dataAtual = new Date();
            const anoAtual = dataAtual.getFullYear();
            const anoDois = Number(anoAtual.toString().slice(-2));
            
            var bandeira = $('#bandeiraCartao').text();

            if (numBandeiraCartaoCredito == 0){
                valueBandeira = $('#bandeiraCartao').val();
            }else{
                valueBandeira = numBandeiraCartaoCredito;
            }

            var numeroCartao = $('#numeroCartao').val();
            var codigoCartao = $('#codigoCartao').val();
            var nomeCartao = $('#nomeCartao').val();
            var mesCartao = $('#mesValidadeCartao').val();
            var anoCartao = $('#anoValidadeCartao').val();
            var idCot = idCotacao;


            botao = $('#btnSalvarAtualizaCartao');
            botao.html('<i class="fa fa-spinner fa-spin"></i>');
            botao.attr('disabled', true);

            if ((bandeira == "Amex" || bandeira == "Diners")) {
                if (bandeira == "Amex" && codigoCartao.length != 4) {
                    alert('Código de segurança inválido!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (bandeira == "Amex" && numeroCartao.length != 15) {
                    alert('Número do cartão inválido!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (bandeira == "Diners" && numeroCartao.length != 14) {
                    alert('Número do cartão inválido!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                }else if (bandeira == "Diners" && codigoCartao.length != 3) {
                    alert('Código de segurança inválido!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (nomeCartao.length < 2) {
                    alert('Preencha o nome corretamente!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (mesCartao > 12 || mesCartao < 1) {
                    alert('Mês de validade inválido!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (anoCartao < anoDois) {
                    alert('Ano de validade inválido!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                }else{
                    $.ajax({
                        method: "POST",
                        url: "Pedidos/validarCartaoCredito",
                        data: { numero: numeroCartao }
                    })
                    .done(function(result) {
                        if (result == 0) {
                            $.ajax({
                                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/atualizaCartaoCredito') ?>",
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    Bandeira: valueBandeira,
                                    Numero_Cartao: numeroCartao,
                                    Cod_Seguranca: codigoCartao,
                                    Nome_Impresso_Cartao: nomeCartao,
                                    Validade_Cartao_Mes: mesCartao,
                                    Validade_Cartao_Ano: anoCartao,
                                    id_Cotacao: idCot
                                },
                                success: function(data){
                                    if(data.dados.Status == 200){
                                        botao.html('<a class=""></a> Salvar');
                                        botao.attr('disabled', false);
                                        alert(data.dados.Message);
                                        $('#numeroCartao').val('');
                                        $('#codigoCartao').val('');
                                        $('#nomeCartao').val('');
                                        $('#mesValidadeCartao').val('');
                                        $('#anoValidadeCartao').val('');
                                        $('#bandeiraCartao').val(0);
                                        document.getElementById("numeroCartao").style.backgroundImage = "url('')";
                                    }
                                    else{
                                        botao.html('<a class=""></a> Salvar');
                                        botao.attr('disabled', false);
                                        alert(data.dados.Message);
                                    }
                                }
                            });
                        } else {
                            alert('Cartão de crédito inválido!');
                            botao.html('<a class=""></a> Salvar');
                            botao.attr('disabled', false);
                        }
                    })
                    .fail(function() {
                        alert('Ocorrou um erro durante a validação do cartão!');
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                    });
                    
                }
            }else{
                if (nomeCartao.length < 2) {
                    alert('Preencha o nome corretamente!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (numeroCartao.length != 16) {
                    alert('Preencha o campo número do cartão corretamente!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (codigoCartao.length != 3) {
                    alert('Preencha o campo código de segurança corretamente!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (mesCartao > 12 || mesCartao < 1) {
                    alert('Preencha o campo mês de validade corretamente!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (anoCartao < anoDois) {
                    alert('Preencha o campo ano de validade corretamente!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                }else{
                    $.ajax({
                        method: "POST",
                        url: "Pedidos/validarCartaoCredito",
                        data: { numero: numeroCartao }
                    })
                    .done(function(result) {
                            if (result == 0) {
                                $.ajax({
                                    url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/atualizaCartaoCredito') ?>",
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {
                                        Bandeira: valueBandeira,
                                        Numero_Cartao: numeroCartao,
                                        Cod_Seguranca: codigoCartao,
                                        Nome_Impresso_Cartao: nomeCartao,
                                        Validade_Cartao_Mes: mesCartao,
                                        Validade_Cartao_Ano: anoCartao,
                                        id_Cotacao: idCot
                                    },
                                    success: function(data){
                                        if(data.dados.Status == 200){
                                            botao.html('<a class=""></a> Salvar');
                                            botao.attr('disabled', false);
                                            alert(data.dados.Message);
                                            $('#numeroCartao').val('');
                                            $('#codigoCartao').val('');
                                            $('#nomeCartao').val('');
                                            $('#mesValidadeCartao').val('');
                                            $('#anoValidadeCartao').val('');
                                            $('#bandeiraCartao').val(0);
                                            document.getElementById("numeroCartao").style.backgroundImage = "url('')";
                                        }
                                        else{
                                            botao.html('<a class=""></a> Salvar');
                                            botao.attr('disabled', false);
                                            alert(data.dados.Message);
                                        }
                                    },
                                });
                            } else {
                                alert('Cartão de crédito inválido!');
                                botao.html('<a class=""></a> Salvar');
                                botao.attr('disabled', false);
                            }
                    })
                    .fail(function() {
                        alert('Ocorrou um erro durante a validação do cartão!');
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                    });
                }
            }
        });

        $('#BtnPesquisar').click(function (e){
            e.preventDefault();

            $('.feedback-alert').html('');

            let documento = $("#documentoPesquisa").val();
            let dataInicial = $("#dataInicial").val();
            let dataFinal = $("#dataFinal").val();

            $.ajax({
                url : "<?php echo site_url('ComerciaisTelevendas/Pedidos/validar_documento_ajax');?>",
                type : 'POST',
                data: {
                    documento: documento
                },
                dataType : 'JSON',
                beforeSend: function(){
                    document.getElementById('loading').style.display = 'block';
                },
                success : function(success){	
                    if(success['valido']){
                        documentoCliente = documento;
                        switch (ativo) {
                            case "quotes":
                                $("#acao_oportunidade").hide();
                                $("#pesquisa_avancada").hide();
                                $("#acao_kanban").show();
                                esvaziarKanban();

                                if(documento){
                                    preencherKanbanCliente(documento, dataInicial, dataFinal);
                                    validarCliente(documento, ativo);
                                }
                                else
                                    preencherKanban(dataInicial, dataFinal);
                                
                                break;
                            case "clientes":
                                $("#acao_oportunidade").hide();
                                $("#pesquisa_avancada").show();
                                $("#btnSalvarCliente").hide();
                                $("#acao_kanban").hide();
                                
                                if(documento == ""){
                                    documento = '<?php if (isset($_SESSION['documento'])){echo $_SESSION['documento'];} ?>'
                                }

                                if(documento){
                                    buscarCliente(documentoCliente);
                                    $("#listagem_docs").attr("data-documento", documentoCliente);
                                    $("#getResumoClienteERP").val(documentoCliente);
                                }

                                break;
                            case "oportunidades":
                                $("#acao_oportunidade").show();
                                $("#pesquisa_avancada").hide();
                                $("#acao_kanban").hide();

                                if(documento){
                                    BuscarOportunidadesClientes(documento, dataInicial, dataFinal);
                                    validarCliente(documento, ativo);
                                }
                                else
                                    BuscarOportunidadesVendedor(dataInicial, dataFinal);
                                break;
                        }
                    }else{
                        alert('Documento inválido!');
                        document.getElementById('loading').style.display = 'none';
                    }
                },
                error : function (error) {
                    alert('Erro ao validar o documento!');
                    document.getElementById('loading').style.display = 'none';
                }
            });            
        })

        
        tableArquivoForm = $("#tableArquivosForm").DataTable({
			responsive: false,
			ordering: false,
			paging: false,
			searching: false,
			info: true,
			language: lang.datatable,
			deferRender: false,
			lengthChange: false
		});
        var contArquivos = 0 ;

		$('#AddArquivoForm').on('click', function () {
			let arquivo = $('#filesForm')[0];
            let result = arquivo.files;

            
            for (i = 0; i < result.length; i++) {
				let file = result[i];
				tableArquivoForm.row.add([
					'<input type="file" style="visibility:hidden;" name="ListaArquivosForm" id="arquivo'+contArquivos+'"/>'+file.name,
					"<a class='remove btn btn-danger'>Remover <i class='fa fa-trash'></i></button>"
				]).draw(false);

				var dataTransfer = new DataTransfer();
				dataTransfer.items.add(file);
				$('#arquivo'+contArquivos)[0].files = dataTransfer.files;
				contArquivos++;
            }
            
            $("#filesForm").val("");
            $('#filesForm').prev('label').text("Selecione um Arquivo");
		});

		$('#tableArquivosForm tbody').on( 'click', '.remove', function () {
			tableArquivoForm
				.row( $(this).parents('tr') )
				.remove()
				.draw();
		} );

        $('input[type=file]').change(function(){
            var t = $(this).val();
			var labelText = 'Arquivo : ' + t.substr(12, t.length);
			$(this).prev('label').text(labelText);
		});

	    $("#submit-form").on("click", function(event) {
            event.preventDefault();

            $('.feedback-alert').html('');

            var form = '';
            var data = new FormData();
            
            let isFormPF = $("#formCadastroPF").is(":visible");

            var form_data;

            if(isFormPF){
                form_data = $("#formCadastroPF").serializeArray();
                form = $('#formCadastroPF');
            }else{
                form_data = $("#formCadastroPJ").serializeArray();
                form = $('#formCadastroPJ');
            }

            if (form[0].checkValidity()) {
                $.each(form_data, function (key, input) {
                    data.append(input.name, input.value);
                });

                //File data
                var file_data = $('input[name="ListaArquivosForm"]');
                var listaArquivos = []
                for (var i = 0; i < file_data.length; i++) {
                    value = file_data[i].value;
                    file = file_data[i].files[0];
                    listaArquivos.push(file)
                    data.append(value, file);
                }

                documento = form_data.filter(form => form.name == 'documento');
                documento = (documento.length > 0) ? documento[0]['value'] : null;

                var url = "<?php echo site_url('ComerciaisTelevendas/Pedidos/addClienteModal') ?>"
                var botao = $(this);
                $.ajax({
                    url : url,
                    type : 'POST',
                    data: data ,
                    processData: false,
                    contentType: false,
                    dataType : 'JSON',
                    beforeSend: function(){
                        botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
                    },
                    success : function(success){	
                        alert(success.msg);	
                        if(success.status == "200"){
                            formCliente = true;
                            $("#modalCadastroCliente").modal('hide');
                            $("#tab-oportunidades").click();

                            $("#open-modal").click();
                            if (documento) {
                                $("#documentoClienteOportunidade").val(documento)
                            }
                        }
                    },
                    error : function (error) {
                        alert('Erro ao cadastrar no CRM');
                        botao.attr('disabled', false).html('Cadastrar Cliente');
                    } ,
                    complete: function(){				
                        botao.attr('disabled', false).html('Cadastrar Cliente');
                    } 
                });
            }else{
                alert("Preencha todos os campos obrigatórios!");
            }
        });
        
        $("#cep-principal-form-pf").on("blur", function(e){
			try{
				let cep = this.value.replace(".","").replace("-","")
				$.getJSON(`https://viacep.com.br/ws/${cep}/json`, function(endereco) {
					if (!("erro" in endereco)) {
						$("#bairro-principal-form-pf").val(endereco.bairro)
						$("#cidade-principal-form-pf").val(endereco.localidade)
						$("#estado-principal-form-pf").val(endereco.uf)
						$("#rua-principal-form-pf").val(endereco.logradouro)
						$("#complemento-principal-form-pf").val(endereco.complemento)

					} else{
					}
				})
			}catch(exception){

			}
		})

        $("#cep-cobranca-form-pf").on("blur", function(e){
			try{
				let cep = this.value.replace(".","").replace("-","")
				$.getJSON(`https://viacep.com.br/ws/${cep}/json`, function(endereco) {
					if (!("erro" in endereco)) {
						$("#bairro-cobranca-form-pf").val(endereco.bairro)
						$("#cidade-cobranca-form-pf").val(endereco.localidade)
						$("#estado-cobranca-form-pf").val(endereco.uf)
						$("#rua-cobranca-form-pf").val(endereco.logradouro)
						$("#complemento-cobranca-form-pf").val(endereco.complemento)

					} else{
					}
				})
			}catch(exception){

			}
		})

        $("#cep-entrega-form-pf").on("blur", function(e){
			try{
				let cep = this.value.replace(".","").replace("-","")
				$.getJSON(`https://viacep.com.br/ws/${cep}/json`, function(endereco) {
					if (!("erro" in endereco)) {
						$("#bairro-entrega-form-pf").val(endereco.bairro)
						$("#cidade-entrega-form-pf").val(endereco.localidade)
						$("#estado-entrega-form-pf").val(endereco.uf)
						$("#rua-entrega-form-pf").val(endereco.logradouro)
						$("#complemento-entrega-form-pf").val(endereco.complemento)

					} else{
					}
				})
			}catch(exception){

			}
		})
        
        $("#cep-principal-form-pj").on("blur", function(e){
			try{
				let cep = this.value.replace(".","").replace("-","")
				$.getJSON(`https://viacep.com.br/ws/${cep}/json`, function(endereco) {
					if (!("erro" in endereco)) {
						$("#bairro-principal-form-pj").val(endereco.bairro)
						$("#cidade-principal-form-pj").val(endereco.localidade)
						$("#estado-principal-form-pj").val(endereco.uf)
						$("#rua-principal-form-pj").val(endereco.logradouro)
						$("#complemento-principal-form-pj").val(endereco.complemento)

					} else{
					}
				})
			}catch(exception){

			}
		})

        $("#cep-cobranca-form-pj").on("blur", function(e){
			try{
				let cep = this.value.replace(".","").replace("-","")
				$.getJSON(`https://viacep.com.br/ws/${cep}/json`, function(endereco) {
					if (!("erro" in endereco)) {
						$("#bairro-cobranca-form-pj").val(endereco.bairro)
						$("#cidade-cobranca-form-pj").val(endereco.localidade)
						$("#estado-cobranca-form-pj").val(endereco.uf)
						$("#rua-cobranca-form-pj").val(endereco.logradouro)
						$("#complemento-cobranca-form-pj").val(endereco.complemento)

					} else{
					}
				})
			}catch(exception){

			}
		})

        $("#cep-entrega-form-pj").on("blur", function(e){
			try{
				let cep = this.value.replace(".","").replace("-","")
				$.getJSON(`https://viacep.com.br/ws/${cep}/json`, function(endereco) {
					if (!("erro" in endereco)) {
						$("#bairro-entrega-form-pj").val(endereco.bairro)
						$("#cidade-entrega-form-pj").val(endereco.localidade)
						$("#estado-entrega-form-pj").val(endereco.uf)
						$("#rua-entrega-form-pj").val(endereco.logradouro)
						$("#complemento-entrega-form-pj").val(endereco.complemento)

					} else{
					}
				})
			}catch(exception){

			}
		})
        $('#BtnLimparPesquisar').click(function (e){
            e.preventDefault();
            $('.feedback-alert').html('');
            documentoCliente = "";
            $("#documentoPesquisa").val(documentoCliente);
        })        

        $("#getResumoClienteERP").click(function() {
            $('.feedback-alert').html('');

            documento = $(this).val();

            if (documento === documentoERP) {
                if(documento.length > 14){
                    $("#nome-erp-pj").val(nomeERP)
                    $("#nome-fantasia-erp-pj").val(nomeFantasiaERP)
                    $("#email-erp-pj").val(emailERP)
                    $("#vendedor-erp-pj").val(vendedorERP)
                    $("#documento-erp-pj").val(documentoERP)
                    $("#ie-erp-pj").val(ieERP)
                    $("#ddd-erp-pj").val(dddERP)
                    $("#ddd-erp-pj2").val(dddERP2)
                    $("#telefone-erp-pj").val(telefoneERP)
                    $("#telefone-erp-pj2").val(telefoneERP2)
                    $("#cep-principal-erp-pj").val(cepPrincipalERP)
                    $("#complemento-principal-erp-pj").val(complementoPrincipalERP)
                    $("#rua-principal-erp-pj").val(ruaPrincipalERP)
                    $("#bairro-principal-erp-pj").val(bairroPrincipalERP)
                    $("#cidade-principal-erp-pj").val(cidadePrincipalERP)
                    $("#estado-principal-erp-pj").val(estadoPrincipalERP)
                    $("#cep-cobranca-erp-pj").val(cepCobrancaERP)
                    $("#complemento-cobranca-erp-pj").val(complementoCobrancaERP)
                    $("#rua-cobranca-erp-pj").val(ruaCobrancaERP)
                    $("#bairro-cobranca-erp-pj").val(bairroCobrancaERP)
                    $("#cidade-cobranca-erp-pj").val(cidadeCobrancaERP)
                    $("#estado-cobranca-erp-pj").val(estadoCobrancaERP)
                    $("#cep-entrega-erp-pj").val(cepEntregaERP)
                    $("#complemento-entrega-erp-pj").val(complementoEntregaERP)
                    $("#rua-entrega-erp-pj").val(ruaEntregaERP)
                    $("#bairro-entrega-erp-pj").val(bairroEntregaERP)
                    $("#cidade-entrega-erp-pj").val(cidadeEntregaERP)
                    $("#estado-entrega-erp-pj").val(estadoEntregaERP)
                                            
                    $("#modalDadosClienteERPPJ").modal('show');
                    return;
                } else {
                    
                    $("#nome-erp-pf").val(nomeERP)
                    $("#sobrenome-erp-pf").val(sobrenomeERP)
                    $("#email-erp-pf").val(emailERP)
                    $("#vendedor-erp-pf").val(vendedorERP)
                    $("#documento-erp-pf").val(documentoERP)
                    $("#rg-erp-pf").val(rgERP)
                    $("#nascimento-erp-pf").val(nascimentoERP)
                    $("#ddd-erp-pf").val(dddERP)
                    $("#ddd-erp-pf2").val(dddERP2)
                    $("#telefone-erp-pf").val(telefoneERP)
                    $("#telefone-erp-pf2").val(telefoneERP2)
                    $("#cep-principal-erp-pf").val(cepPrincipalERP)
                    $("#complemento-principal-erp-pf").val(complementoPrincipalERP)
                    $("#rua-principal-erp-pf").val(ruaPrincipalERP)
                    $("#bairro-principal-erp-pf").val(bairroPrincipalERP)
                    $("#cidade-principal-erp-pf").val(cidadePrincipalERP)
                    $("#estado-principal-erp-pf").val(estadoPrincipalERP)
                    $("#cep-cobranca-erp-pf").val(cepCobrancaERP)
                    $("#complemento-cobranca-erp-pf").val(complementoCobrancaERP)
                    $("#rua-cobranca-erp-pf").val(ruaCobrancaERP)
                    $("#bairro-cobranca-erp-pf").val(bairroCobrancaERP)
                    $("#cidade-cobranca-erp-pf").val(cidadeCobrancaERP)
                    $("#estado-cobranca-erp-pf").val(estadoCobrancaERP)
                    $("#cep-entrega-erp-pf").val(cepEntregaERP)
                    $("#complemento-entrega-erp-pf").val(complementoEntregaERP)
                    $("#rua-entrega-erp-pf").val(ruaEntregaERP)
                    $("#bairro-entrega-erp-pf").val(bairroEntregaERP)
                    $("#cidade-entrega-erp-pf").val(cidadeEntregaERP)
                    $("#estado-entrega-erp-pf").val(estadoEntregaERP)

                    $("#modalDadosClienteERPPF").modal('show');
                    return;
                }
            }    
            $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/resumoClienteCRM');?>",
                type: "POST",
                data: {
                    documento: documento
                },
                success: function(data) {            
                    resposta = JSON.parse(data)
                    if(resposta?.status === 200){
                        if(resposta?.dados?.Message){
                            documento = resposta?.dados?.Message?.documento;
                            if (resposta?.dados?.Message?.documento.length > 14) {
                                $("#nome-erp-pj").val(resposta?.dados?.Message?.name ? resposta?.dados?.Message?.name : '')
                                $("#nome-fantasia-erp-pj").val(resposta?.dados?.Message?.nome_fantasia ? resposta?.dados?.Message?.nome_fantasia : '')
                                $("#email-erp-pj").val(resposta?.dados?.Message?.email ? resposta?.dados?.Message?.email : '')
                                $("#vendedor-erp-pj").val(resposta?.dados?.Message?.loginVendedor ? resposta?.dados?.Message?.loginVendedor : '')
                                $("#documento-erp-pj").val(resposta?.dados?.Message?.documento ? resposta?.dados?.Message?.documento : '')
                                $("#ie-erp-pj").val(resposta?.dados?.Message?.inscricao_estadual ? resposta?.dados?.Message?.inscricao_estadual : '')
                                $("#ddd-erp-pj").val(resposta?.dados?.Message?.ddd_telefone ? resposta?.dados?.Message?.ddd_telefone : '')
                                $("#ddd-erp-pj2").val(resposta?.dados?.Message?.ddd_telefone2 ? resposta?.dados?.Message?.ddd_telefone2 : '')
                                $("#telefone-erp-pj").val(resposta?.dados?.Message?.telefone ? resposta?.dados?.Message?.telefone : '')
                                $("#telefone-erp-pj2").val(resposta?.dados?.Message?.telefone2 ? resposta?.dados?.Message?.telefone2 : '')
                                
                                $("#cep-principal-erp-pj").val(resposta?.dados?.Message?.endereco_principal?.cep ? resposta?.dados?.Message?.endereco_principal?.cep : '')
                                $("#complemento-principal-erp-pj").val(resposta?.dados?.Message?.endereco_principal?.complemento ? resposta?.dados?.Message?.endereco_principal?.complemento : '')
                                $("#rua-principal-erp-pj").val(resposta?.dados?.Message?.endereco_principal?.rua ? resposta?.dados?.Message?.endereco_principal?.rua : '')
                                $("#bairro-principal-erp-pj").val(resposta?.dados?.Message?.endereco_principal?.bairro ? resposta?.dados?.Message?.endereco_principal?.bairro : '')
                                $("#cidade-principal-erp-pj").val(resposta?.dados?.Message?.endereco_principal?.cidade ? resposta?.dados?.Message?.endereco_principal?.cidade : '')
                                $("#estado-principal-erp-pj").val(resposta?.dados?.Message?.endereco_principal?.estado ? resposta?.dados?.Message?.endereco_principal?.estado : '')
                                $("#cep-cobranca-erp-pj").val(resposta?.dados?.Message?.endereco_cobranca?.cep ? resposta?.dados?.Message?.endereco_cobranca?.cep : '')
                                $("#complemento-cobranca-erp-pj").val(resposta?.dados?.Message?.endereco_cobranca?.complemento ? resposta?.dados?.Message?.endereco_cobranca?.complemento : '')
                                $("#rua-cobranca-erp-pj").val(resposta?.dados?.Message?.endereco_cobranca?.rua ? resposta?.dados?.Message?.endereco_cobranca?.rua : '')
                                $("#bairro-cobranca-erp-pj").val(resposta?.dados?.Message?.endereco_cobranca?.bairro ? resposta?.dados?.Message?.endereco_cobranca?.bairro : '')
                                $("#cidade-cobranca-erp-pj").val(resposta?.dados?.Message?.endereco_cobranca?.cidade ? resposta?.dados?.Message?.endereco_cobranca?.cidade : '')
                                $("#estado-cobranca-erp-pj").val(resposta?.dados?.Message?.endereco_cobranca?.estado ? resposta?.dados?.Message?.endereco_cobranca?.estado : '')
                                $("#cep-entrega-erp-pj").val(resposta?.dados?.Message?.endereco_entrega?.cep ? resposta?.dados?.Message?.endereco_entrega?.cep : '')
                                $("#complemento-entrega-erp-pj").val(resposta?.dados?.Message?.endereco_entrega?.complemento ? resposta?.dados?.Message?.endereco_entrega?.complemento : '')
                                $("#rua-entrega-erp-pj").val(resposta?.dados?.Message?.endereco_entrega?.rua ? resposta?.dados?.Message?.endereco_entrega?.rua : '')
                                $("#bairro-entrega-erp-pj").val(resposta?.dados?.Message?.endereco_entrega?.bairro ? resposta?.dados?.Message?.endereco_entrega?.bairro : '')
                                $("#cidade-entrega-erp-pj").val(resposta?.dados?.Message?.endereco_entrega?.cidade ? resposta?.dados?.Message?.endereco_entrega?.cidade : '')
                                $("#estado-entrega-erp-pj").val(resposta?.dados?.Message?.endereco_entrega?.estado ? resposta?.dados?.Message?.endereco_entrega?.estado : '')

                                $("#modalDadosClienteERPPJ").modal('show');
                            } else {
                                $("#nome-erp-pf").val(resposta?.dados?.Message?.name ? resposta?.dados?.Message?.name : '')
                                $("#sobrenome-erp-pf").val(resposta?.dados?.Message?.sobrenome ? resposta?.dados?.Message?.sobrenome : '')
                                $("#email-erp-pf").val(resposta?.dados?.Message?.email ? resposta?.dados?.Message?.email : '')
                                $("#vendedor-erp-pf").val(resposta?.dados?.Message?.loginVendedor ? resposta?.dados?.Message?.loginVendedor : '')
                                $("#documento-erp-pf").val(resposta?.dados?.Message?.documento ? resposta?.dados?.Message?.documento : '')
                                $("#rg-erp-pf").val(resposta?.dados?.Message?.rg ? resposta?.dados?.Message?.rg : '')
                                $("#nascimento-erp-pf").val(resposta?.dados?.Message?.dataNascimento ? (new Date(resposta?.dados?.Message?.dataNascimento)).toISOString().slice(0,10) : '')
                                $("#ddd-erp-pf").val(resposta?.dados?.Message?.ddd_telefone ? resposta?.dados?.Message?.ddd_telefone : '')
                                $("#ddd-erp-pf2").val(resposta?.dados?.Message?.ddd_telefone2 ? resposta?.dados?.Message?.ddd_telefone2 : '')
                                $("#telefone-erp-pf").val(resposta?.dados?.Message?.telefone ? resposta?.dados?.Message?.telefone : '')
                                $("#telefone-erp-pf2").val(resposta?.dados?.Message?.telefone2 ? resposta?.dados?.Message?.telefone2 : '')

                                
                                $("#cep-principal-erp-pf").val(resposta?.dados?.Message?.endereco_principal?.cep ? resposta?.dados?.Message?.endereco_principal?.cep : '')
                                $("#complemento-principal-erp-pf").val(resposta?.dados?.Message?.endereco_principal?.complemento ? resposta?.dados?.Message?.endereco_principal?.complemento : '')
                                $("#rua-principal-erp-pf").val(resposta?.dados?.Message?.endereco_principal?.rua ? resposta?.dados?.Message?.endereco_principal?.rua : '')
                                $("#bairro-principal-erp-pf").val(resposta?.dados?.Message?.endereco_principal?.bairro ? resposta?.dados?.Message?.endereco_principal?.bairro : '')
                                $("#cidade-principal-erp-pf").val(resposta?.dados?.Message?.endereco_principal?.cidade ? resposta?.dados?.Message?.endereco_principal?.cidade : '')
                                $("#estado-principal-erp-pf").val(resposta?.dados?.Message?.endereco_principal?.estado ? resposta?.dados?.Message?.endereco_principal?.estado : '')
                                $("#cep-cobranca-erp-pf").val(resposta?.dados?.Message?.endereco_cobranca?.cep ? resposta?.dados?.Message?.endereco_cobranca?.cep : '')
                                $("#complemento-cobranca-erp-pf").val(resposta?.dados?.Message?.endereco_cobranca?.complemento ? resposta?.dados?.Message?.endereco_cobranca?.complemento : '')
                                $("#rua-cobranca-erp-pf").val(resposta?.dados?.Message?.endereco_cobranca?.rua ? resposta?.dados?.Message?.endereco_cobranca?.rua : '')
                                $("#bairro-cobranca-erp-pf").val(resposta?.dados?.Message?.endereco_cobranca?.bairro ? resposta?.dados?.Message?.endereco_cobranca?.bairro : '')
                                $("#cidade-cobranca-erp-pf").val(resposta?.dados?.Message?.endereco_cobranca?.cidade ? resposta?.dados?.Message?.endereco_cobranca?.cidade : '')
                                $("#estado-cobranca-erp-pf").val(resposta?.dados?.Message?.endereco_cobranca?.estado ? resposta?.dados?.Message?.endereco_cobranca?.estado : '')
                                $("#cep-entrega-erp-pf").val(resposta?.dados?.Message?.endereco_entrega?.cep ? resposta?.dados?.Message?.endereco_entrega?.cep : '')
                                $("#complemento-entrega-erp-pf").val(resposta?.dados?.Message?.endereco_entrega?.complemento ? resposta?.dados?.Message?.endereco_entrega?.complemento : '')
                                $("#rua-entrega-erp-pf").val(resposta?.dados?.Message?.endereco_entrega?.rua ? resposta?.dados?.Message?.endereco_entrega?.rua : '')
                                $("#bairro-entrega-erp-pf").val(resposta?.dados?.Message?.endereco_entrega?.bairro ? resposta?.dados?.Message?.endereco_entrega?.bairro : '')
                                $("#cidade-entrega-erp-pf").val(resposta?.dados?.Message?.endereco_entrega?.cidade ? resposta?.dados?.Message?.endereco_entrega?.cidade : '')
                                $("#estado-entrega-erp-pf").val(resposta?.dados?.Message?.endereco_entrega?.estado ? resposta?.dados?.Message?.endereco_entrega?.estado : '')
                                $("#modalDadosClienteERPPF").modal('show');
                            }
                        }
                    }
                },
                done: function() {
                    if( documento > 14){
                        clienteERP = $("#cliente-erp-pj").val()
                        nomeERP = $("#nome-erp-pj").val()
                        sobrenomeERP = $("#sobrenome-erp-pj").val()
                        nomeFantasiaERP = $("#nome-fantasia-erp-pj").val()
                        emailERP = $("#email-erp-pj").val()
                        vendedorERP = $("#vendedor-erp-pj").val()
                        documentoERP = $("#documento-erp-pj").val()
                        rgERP = $("#rg-erp-pj").val()
                        nascimentoERP = $("#nascimento-erp-pj").val()
                        ieERP = $("#ie-erp-pj").val()
                        dddERP = $("#ddd-erp-pj").val()
                        dddERP2 = $("#ddd-erp-pj2").val()
                        telefoneERP = $("#telefone-erp-pj").val()
                        telefoneERP2 = $("#telefone-erp-pj2").val()
                        cepPrincipalERP = $("#cep-principal-erp-pj").val()
                        complementoPrincipalERP = $("#complemento-principal-erp-pj").val()
                        ruaPrincipalERP = $('#rua-principal-erp-pj').val()
                        bairroPrincipalERP = $('#bairro-principal-erp-pj').val()
                        cidadePrincipalERP = $('#cidade-principal-erp-pj').val()
                        estadoPrincipalERP = $('#estado-principal-erp-pj').val()
                        cepCobrancaERP = $("#cep-cobranca-erp-pj").val()
                        complementoCobrancaERP = $("#complemento-cobranca-erp-pj").val()
                        ruaCobrancaERP = $('#rua-cobranca-erp-pj').val()
                        bairroCobrancaERP = $('#bairro-cobranca-erp-pj').val()
                        cidadeCobrancaERP = $('#cidade-cobranca-erp-pj').val()
                        estadoCobrancaERP = $('#estado-cobranca-erp-pj').val()
                        cepEntregaERP = $("#cep-entrega-erp-pj").val()
                        complementoEntregaERP = $("#complemento-entrega-erp-pj").val()
                        ruaEntregaERP = $('#rua-entrega-erp-pj').val()
                        bairroEntregaERP = $('#bairro-entrega-erp-pj').val()
                        cidadeEntregaERP = $('#cidade-entrega-erp-pj').val()
                        estadoEntregaERP = $('#estado-entrega-erp-pj').val()
                    } else {
                        clienteERP = $("#cliente-erp-pf").val()
                        nomeERP = $("#nome-erp-pf").val()
                        sobrenomeERP = $("#sobrenome-erp-pf").val()
                        emailERP = $("#email-erp-pf").val()
                        vendedorERP = $("#vendedor-erp-pf").val()
                        documentoERP = $("#documento-erp-pf").val()
                        rgERP = $("#rg-erp-pf").val()
                        nascimentoERP = $("#nascimento-erp-pf").val()
                        ieERP = $("#ie-erp-pf").val()
                        dddERP = $("#ddd-erp-pf").val()
                        dddERP2 = $("#ddd-erp-pf2").val()
                        telefoneERP = $("#telefone-erp-pf").val()
                        telefoneERP2 = $("#telefone-erp-pf2").val()
                        cepPrincipalERP = $("#cep-principal-erp-pf").val()
                        complementoPrincipalERP = $("#complemento-principal-erp-pf").val()
                        cepCobrancaERP = $("#cep-cobranca-erp-pf").val()
                        complementoCobrancaERP = $("#complemento-cobranca-erp-pf").val()
                        cepEntregaERP = $("#cep-entrega-erp-pf").val()
                        complementoEntregaERP = $("#complemento-entrega-erp-pf").val()
                        ruaPrincipalERP = $('#rua-principal-erp-pf').val()
                        bairroPrincipalERP = $('#bairro-principal-erp-pf').val()
                        cidadePrincipalERP = $('#cidade-principal-erp-pf').val()
                        estadoPrincipalERP = $('#estado-principal-erp-pf').val()
                        ruaCobrancaERP = $('#rua-cobranca-erp-pf').val()
                        bairroCobrancaERP = $('#bairro-cobranca-erp-pf').val()
                        cidadeCobrancaERP = $('#cidade-cobranca-erp-pf').val()
                        estadoCobrancaERP = $('#estado-cobranca-erp-pf').val()
                        ruaEntregaERP = $('#rua-entrega-erp-pf').val()
                        bairroEntregaERP = $('#bairro-entrega-erp-pf').val()
                        cidadeEntregaERP = $('#cidade-entrega-erp-pf').val()
                        estadoEntregaERP = $('#estado-entrega-erp-pf').val()
                    }
                }
            });
        })
        $('#copiar-endereco-principal-cobranca-erp-pj').change(function() {

            var checked = this.checked;
            if ($('#copiar-endereco-entrega-cobranca-erp-pj').prop('checked')) {
            $('#copiar-endereco-entrega-cobranca-erp-pj').prop('checked', false);
            }

            if (!checked)
                return;

            if($('#cep-principal-erp-pj').val() == ""){
                alert("Endereço principal não possui dados");
                $(this).prop('checked', false)
                return;
            } 

            $('#cep-cobranca-erp-pj').val(checked ? $('#cep-principal-erp-pj').val() : '');
            $('#rua-cobranca-erp-pj').val(checked ? $('#rua-principal-erp-pj').val() : '');
            $('#complemento-cobranca-erp-pj').val(checked ? $('#complemento-principal-erp-pj').val() : '');
            $('#bairro-cobranca-erp-pj').val(checked ? $('#bairro-principal-erp-pj').val() : '');
            $('#cidade-cobranca-erp-pj').val(checked ? $('#cidade-principal-erp-pj').val() : '');
            $('#estado-cobranca-erp-pj').val(checked ? $('#estado-principal-erp-pj').val() : '');
        });

        $('#copiar-endereco-entrega-cobranca-erp-pj').change(function(){
            var checked = this.checked;
            if ($('#copiar-endereco-principal-cobranca-erp-pj').prop('checked')) {
            $('#copiar-endereco-principal-cobranca-erp-pj').prop('checked', false);
            }
            
            if (!checked)
                return;
                
            if($('#cep-entrega-erp-pj').val() == ""){
                alert("Endereço Entrega não possui dados");
                $(this).prop('checked', false)
                return;
            }

            $('#cep-cobranca-erp-pj').val(checked ? $('#cep-entrega-erp-pj').val() : '');
            $('#rua-cobranca-erp-pj').val(checked ? $('#rua-entrega-erp-pj').val() : '');
            $('#complemento-cobranca-erp-pj').val(checked ? $('#complemento-entrega-erp-pj').val() : '');
            $('#bairro-cobranca-erp-pj').val(checked ? $('#bairro-entrega-erp-pj').val() : '');
            $('#cidade-cobranca-erp-pj').val(checked ? $('#cidade-entrega-erp-pj').val() : '');
            $('#estado-cobranca-erp-pj').val(checked ? $('#estado-entrega-erp-pj').val() : '');
        });

        $('#copiar-endereco-principal-entrega-erp-pj').change(function(){
            var checked = this.checked;
            if ($('#copiar_endereco_cobranca_entrega_pj').prop('checked')) {
            $('#copiar_endereco_cobranca_entrega_pj').prop('checked', false);
            }
            
            if (!checked)
                return;

            if($('#cep-principal-erp-pj').val() == ""){
                alert("Endereço Principal não possui dados");
                $(this).prop('checked', false)
                return;
            }

            $('#cep-entrega-erp-pj').val(checked ? $('#cep-principal-erp-pj').val() : '');
            $('#rua-entrega-erp-pj').val(checked ? $('#rua-principal-erp-pj').val() : '');
            $('#complemento-entrega-erp-pj').val(checked ? $('#complemento-principal-erp-pj').val() : '');
            $('#bairro-entrega-erp-pj').val(checked ? $('#bairro-principal-erp-pj').val() : '');
            $('#cidade-entrega-erp-pj').val(checked ? $('#cidade-principal-erp-pj').val() : '');
            $('#estado-entrega-erp-pj').val(checked ? $('#estado-principal-erp-pj').val() : '');
        });

        $('#copiar_endereco_cobranca_entrega_pj').change(function(){
            var checked = this.checked;
            if ($('#copiar-endereco-principal-entrega-erp-pj').prop('checked')) {
            $('#copiar-endereco-principal-entrega-erp-pj').prop('checked', false);
            }
            
            if (!checked)
                return;

            if($('#cep-cobranca-erp-pj').val() == ""){
                alert("Endereço Cobrança não possui dados");
                $(this).prop('checked', false)
                return;
            }
            
            $('#cep-entrega-erp-pj').val(checked ? $('#cep-cobranca-erp-pj').val() : '');
            $('#rua-entrega-erp-pj').val(checked ? $('#rua-cobranca-erp-pj').val() : '');
            $('#complemento-entrega-erp-pj').val(checked ? $('#complemento-cobranca-erp-pj').val() : '');
            $('#bairro-entrega-erp-pj').val(checked ? $('#bairro-cobranca-erp-pj').val() : '');
            $('#cidade-entrega-erp-pj').val(checked ? $('#cidade-cobranca-erp-pj').val() : '');
            $('#estado-entrega-erp-pj').val(checked ? $('#estado-cobranca-erp-pj').val() : '');
        });


        $('#copiar-endereco-entrega-principal-erp-pj').change(function() {
            var checked = this.checked;
            if ($('#copiar-endereco-cobranca-principal-erp-pj').prop('checked')) {
            $('#copiar-endereco-cobranca-principal-erp-pj').prop('checked', false);
            }
            
            if (!checked)
                return;

            if($('#cep-entrega-erp-pj').val() == ""){
                alert("Endereço Entrega não possui dados");
                $(this).prop('checked', false)
                return;
            }
            
            $('#cep-principal-erp-pj').val(checked ? $('#cep-entrega-erp-pj').val() : '');
            $('#rua-principal-erp-pj').val(checked ? $('#rua-entrega-erp-pj').val() : '');
            $('#complemento-principal-erp-pj').val(checked ? $('#complemento-entrega-erp-pj').val() : '');
            $('#bairro-principal-erp-pj').val(checked ? $('#bairro-entrega-erp-pj').val() : '');
            $('#cidade-principal-erp-pj').val(checked ? $('#cidade-entrega-erp-pj').val() : '');
            $('#estado-principal-erp-pj').val(checked ? $('#estado-entrega-erp-pj').val() : '');
        })

        
        $('#copiar-endereco-cobranca-principal-erp-pj').change(function() {
            var checked = this.checked;
            if ($('#copiar-endereco-entrega-principal-erp-pj').prop('checked')) {
            $('#copiar-endereco-entrega-principal-erp-pj').prop('checked', false);
            }
            
            if (!checked)
                return;

            if($('#cep-cobranca-erp-pj').val() == ""){
                alert("Endereço Cobrança não possui dados");
                $(this).prop('checked', false)
                return;
            }
            
            $('#cep-principal-erp-pj').val(checked ? $('#cep-cobranca-erp-pj').val() : '');
            $('#rua-principal-erp-pj').val(checked ? $('#rua-cobranca-erp-pj').val() : '');
            $('#complemento-principal-erp-pj').val(checked ? $('#complemento-cobranca-erp-pj').val() : '');
            $('#bairro-principal-erp-pj').val(checked ? $('#bairro-cobranca-erp-pj').val() : '');
            $('#cidade-principal-erp-pj').val(checked ? $('#cidade-cobranca-erp-pj').val() : '');
            $('#estado-principal-erp-pj').val(checked ? $('#estado-cobranca-erp-pj').val() : '');
        })

        $('#copiar-endereco-principal-cobranca-erp-pf').change(function() {
            var checked = this.checked;
            if ($('#copiar-endereco-entrega-cobranca-erp-pf').prop('checked')) {
                $('#copiar-endereco-entrega-cobranca-erp-pf').prop('checked', false);
            }
            
            if (!checked)
                return;

            if($('#cep-principal-erp-pf').val() == ""){
                alert("Endereço Principal não possui dados");
                $(this).prop('checked', false)
                return;
            }
            
            $('#cep-cobranca-erp-pf').val(checked ? $('#cep-principal-erp-pf').val() : '');
            $('#rua-cobranca-erp-pf').val(checked ? $('#rua-principal-erp-pf').val() : '');
            $('#complemento-cobranca-erp-pf').val(checked ? $('#complemento-principal-erp-pf').val() : '');
            $('#bairro-cobranca-erp-pf').val(checked ? $('#bairro-principal-erp-pf').val() : '');
            $('#cidade-cobranca-erp-pf').val(checked ? $('#cidade-principal-erp-pf').val() : '');
            $('#estado-cobranca-erp-pf').val(checked ? $('#estado-principal-erp-pf').val() : '');
        })

        $('#copiar-endereco-entrega-cobranca-erp-pf').change(function() {
            var checked = this.checked;
            if ($('#copiar-endereco-principal-cobranca-erp-pf').prop('checked')) {
                $('#copiar-endereco-principal-cobranca-erp-pf').prop('checked', false);
            }
            
            if (!checked)
                return;

            if($('#cep-entrega-erp-pf').val() == ""){
                alert("Endereço Entrega não possui dados");
                $(this).prop('checked', false)
                return;
            }
            
            $('#cep-cobranca-erp-pf').val(checked ? $('#cep-entrega-erp-pf').val() : '');
            $('#rua-cobranca-erp-pf').val(checked ? $('#rua-entrega-erp-pf').val() : '');
            $('#complemento-cobranca-erp-pf').val(checked ? $('#complemento-entrega-erp-pf').val() : '');
            $('#bairro-cobranca-erp-pf').val(checked ? $('#bairro-entrega-erp-pf').val() : '');
            $('#cidade-cobranca-erp-pf').val(checked ? $('#cidade-entrega-erp-pf').val() : '');
            $('#estado-cobranca-erp-pf').val(checked ? $('#estado-entrega-erp-pf').val() : '');
        })

        $('#copiar-endereco-principal-entrega-erp-pf').change(function() {
            var checked = this.checked;
            if ($('#copiar-endereco-cobranca-entrega-erp-pf').prop('checked')) {
            $('#copiar-endereco-cobranca-entrega-erp-pf').prop('checked', false);
            }
            
            if (!checked)
                return;

            if($('#cep-principal-erp-pf').val() == ""){
                alert("Endereço Principal não possui dados");
                $(this).prop('checked', false)
                return;
            }
            
            $('#cep-entrega-erp-pf').val(checked ? $('#cep-principal-erp-pf').val() : '');
            $('#rua-entrega-erp-pf').val(checked ? $('#rua-principal-erp-pf').val() : '');
            $('#complemento-entrega-erp-pf').val(checked ? $('#complemento-principal-erp-pf').val() : '');
            $('#bairro-entrega-erp-pf').val(checked ? $('#bairro-principal-erp-pf').val() : '');
            $('#cidade-entrega-erp-pf').val(checked ? $('#cidade-principal-erp-pf').val() : '');
            $('#estado-entrega-erp-pf').val(checked ? $('#estado-principal-erp-pf').val() : '');
        })

        $('#copiar-endereco-cobranca-entrega-erp-pf').change(function() {
            var checked = this.checked;
            if ($('#copiar-endereco-principal-entrega-erp-pf').prop('checked')) {
            $('#copiar-endereco-principal-entrega-erp-pf').prop('checked', false);
            }
            
            if (!checked)
                return;

            if($('#cep-cobranca-erp-pf').val() == ""){
                alert("Endereço Cobrança não possui dados");
                $(this).prop('checked', false)
                return;
            }
            
            $('#cep-entrega-erp-pf').val(checked ? $('#cep-cobranca-erp-pf').val() : '');
            $('#rua-entrega-erp-pf').val(checked ? $('#rua-cobranca-erp-pf').val() : '');
            $('#complemento-entrega-erp-pf').val(checked ? $('#complemento-cobranca-erp-pf').val() : '');
            $('#bairro-entrega-erp-pf').val(checked ? $('#bairro-cobranca-erp-pf').val() : '');
            $('#cidade-entrega-erp-pf').val(checked ? $('#cidade-cobranca-erp-pf').val() : '');
            $('#estado-entrega-erp-pf').val(checked ? $('#estado-cobranca-erp-pf').val() : '');
        })

        $('#copiar-endereco-entrega-principal-erp-pf').change(function() {
            var checked = this.checked;
            if ($('#copiar-endereco-cobranca-principal-erp-pf').prop('checked')) {
            $('#copiar-endereco-cobranca-principal-erp-pf').prop('checked', false);
            }
            
            if (!checked)
                return;

            if($('#cep-entrega-erp-pf').val() == ""){
                alert("Endereço Entrega não possui dados");
                $(this).prop('checked', false)
                return;
            }
            
            $('#cep-principal-erp-pf').val(checked ? $('#cep-entrega-erp-pf').val() : '');
            $('#rua-principal-erp-pf').val(checked ? $('#rua-entrega-erp-pf').val() : '');
            $('#complemento-principal-erp-pf').val(checked ? $('#complemento-entrega-erp-pf').val() : '');
            $('#bairro-principal-erp-pf').val(checked ? $('#bairro-entrega-erp-pf').val() : '');
            $('#cidade-principal-erp-pf').val(checked ? $('#cidade-entrega-erp-pf').val() : '');
            $('#estado-principal-erp-pf').val(checked ? $('#estado-entrega-erp-pf').val() : '');
        })

        $('#copiar-endereco-cobranca-principal-erp-pf').change(function() {
            var checked = this.checked;
            if ($('#copiar-endereco-entrega-principal-erp-pf').prop('checked')) {
            $('#copiar-endereco-entrega-principal-erp-pf').prop('checked', false);
            }
            
            if (!checked)
                return;

            if($('#cep-cobranca-erp-pf').val() == ""){
                alert("Endereço Cobrança não possui dados");
                $(this).prop('checked', false)
                return;
            }
            
            $('#cep-principal-erp-pf').val(checked ? $('#cep-cobranca-erp-pf').val() : '');
            $('#rua-principal-erp-pf').val(checked ? $('#rua-cobranca-erp-pf').val() : '');
            $('#complemento-principal-erp-pf').val(checked ? $('#complemento-cobranca-erp-pf').val() : '');
            $('#bairro-principal-erp-pf').val(checked ? $('#bairro-cobranca-erp-pf').val() : '');
            $('#cidade-principal-erp-pf').val(checked ? $('#cidade-cobranca-erp-pf').val() : '');
            $('#estado-principal-erp-pf').val(checked ? $('#estado-cobranca-erp-pf').val() : '');
        })
        
        $("#submit-form-integracao-pf").click(async function(e) {
            e.preventDefault();

            $('.feedback-alert').html('');

            let botao = $("#submit-form-integracao-pf");
            let htmlBotao = botao.html();
            botao.html(ICONS.spinner+' '+htmlBotao);
            botao.attr('disabled', true);

            
            let data = getDadosForm('formIntegracaoERPPF');

            let retorno = await $.ajax({
                url: `<?= site_url('ComerciaisTelevendas/Pedidos/integrarClienteERP') ?>`,
                type: 'POST',
                data: data,
                success: function(response) {
                    response = JSON.parse(response);
                    
                    if (response.Status === 400) {
                        alert("Falha ao integrar com CRM");
                    } else {
                        documentoERP = '';
                        $("#getResumoClienteERP").click();
                        alert(response?.Message);
                    }
                },
                error: function() {
                    alert("Erro na solicitação. Por favor, tente novamente.");
                }
            });        
            
            retorno = JSON.parse(retorno)
            retorno =retorno?.Status

            if(retorno == 200){
                await integrarClientePeloPedido(documento, cotacaoPedido)
            }
            botao.html(htmlBotao);
            botao.attr('disabled', false);      

        })

        $("#submit-form-integracao-pj").click(async function(e) {
            e.preventDefault();
            $('.feedback-alert').html('');
            let data = getDadosForm('integracaoERPPJ');

            let botao = $("#submit-form-integracao-pj");
            let htmlBotao = botao.html();
            botao.html(ICONS.spinner+' '+htmlBotao);
            botao.attr('disabled', true);
            
            let retorno = await $.ajax({
                url: `<?= site_url('ComerciaisTelevendas/Pedidos/integrarClienteERP') ?>`,
                type: 'POST',
                data: data,
                success: function(response) {
                    response = JSON.parse(response);
                    
                    if (response.Status === 400) {
                        alert("Falha ao integrar com CRM");
                    } else {
                        documentoERP = '';
                        $("#getResumoClienteERP").click();
                        alert(response?.Message);
                    }
                },
                error: function() {
                    alert("Erro na solicitação. Por favor, tente novamente.");
                }
            });


            retorno = JSON.parse(retorno)
            retorno =retorno?.dados?.Status

            if(retorno == 200){
                await integrarClientePeloPedido(documento, cotacaoPedido)
            }

            botao.html(htmlBotao);
            botao.attr('disabled', false);
        })

	    $('.cep').mask('99999-999');
        $(document).on('focus', '.ddd', function(){ $('.ddd').mask('99'); });
        $(document).on('focus', '.fone', function(){ $('.fone').mask('99999-9999'); });
	    $(document).on('focus', '.cep', function(){ $('.cep').mask('99999-999'); });
        $('.ddd').mask('99');
        $('.fone').mask('99999-9999');
    });

    var vendedor = "<?= $_SESSION['emailUsuario']?>";

    function limparClienteForm(){
        $('.feedback-alert').html('');
	    $('#nome-form-pf').val('');
	    $('#sobrenome-form-pf').val('');
	    $('#email-form-pf').val('');
	    $('#nascimento-form-pf').val('');
	    $('#ddd-form-pf').val('');
	    $('#telefone-form-pf').val('');
	    $('#ddd-form-pf2').val('');
	    $('#telefone-form-pf2').val('');

	    $('#cep-principal-form-pf').val('');
	    $('#rua-principal-form-pf').val('');
	    $('#complemento-principal-form-pf').val('');
	    $('#bairro-principal-form-pf').val('');
	    $('#cidade-principal-form-pf').val('');
	    $('#estado-principal-form-pf').val('');

	    $('#cep-cobranca-form-pf').val('');
	    $('#rua-cobranca-form-pf').val('');
	    $('#complemento-cobranca-form-pf').val('');
	    $('#bairro-cobranca-form-pf').val('');
	    $('#cidade-cobranca-form-pf').val('');
	    $('#estado-cobranca-form-pf').val('');

	    $('#cep-entrega-form-pf').val('');
	    $('#rua-entrega-form-pf').val('');
	    $('#complemento-entrega-form-pf').val('');
	    $('#bairro-entrega-form-pf').val('');
	    $('#cidade-entrega-form-pf').val('');
	    $('#estado-entrega-form-pf').val('');

        
	    $('#nome-form-pj').val('');
	    $('#nome-fantasia-form-pj').val('');
	    $('#email-form-pj').val('');
	    $('#ie-form-pj').val('');
	    $('#ddd-form-pj').val('');
	    $('#telefone-form-pj').val('');
	    $('#ddd-form-pj2').val('');
	    $('#telefone-form-pj2').val('');

	    $('#cep-principal-form-pj').val('');
	    $('#rua-principal-form-pj').val('');
	    $('#complemento-principal-form-pj').val('');
	    $('#bairro-principal-form-pj').val('');
	    $('#cidade-principal-form-pj').val('');
	    $('#estado-principal-form-pj').val('');

	    $('#cep-cobranca-form-pj').val('');
	    $('#rua-cobranca-form-pj').val('');
	    $('#complemento-cobranca-form-pj').val('');
	    $('#bairro-cobranca-form-pj').val('');
	    $('#cidade-cobranca-form-pj').val('');
	    $('#estado-cobranca-form-pj').val('');

	    $('#cep-entrega-form-pj').val('');
	    $('#rua-entrega-form-pj').val('');
	    $('#complemento-entrega-form-pj').val('');
	    $('#bairro-entrega-form-pj').val('');
	    $('#cidade-entrega-form-pj').val('');
	    $('#estado-entrega-form-pj').val('');

        let documento_form = $("#documentoPesquisa").val();
        $("#documento-form-pf").val(documento_form);
        $("#documento-form-pj").val(documento_form);
	    $('#vendedor-form-pf').val(vendedor);
	    $('#vendedor-form-pj').val(vendedor);
        
        $("#filesForm").val("");
        $('#filesForm').prev('label').text("Selecione um Arquivo");
        tableArquivoForm
				.clear()
				.draw();
    }    

    function abrirModalResumoCotacao(botao){
        $('.feedback-alert').html('');
        let id = $(botao).attr('data-statuscode');
        botao = $(botao);
        idCotacao = id;

        AtualizarModalResumoCotacao(botao, botao.html(), '<i class="fa fa-spinner fa-spin"></i>', "abrirModal");
    }  

    function AtualizarModalResumoCotacao(botao, textoBotao, spin, origem = ""){
        document.getElementById('loading').style.display = 'block';
        if(botao){
            botao.html(spin);
            botao.attr('disabled', true);
        }
        
        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/resumoCotacao') ?>",
            dataType: "json",
            type: "POST",
            data: { idCotacao: idCotacao },
            success: function(data) {
                if (data.Status == 200) {
                    document.getElementById('loading').style.display = 'none';
                    if(botao){
                        botao.html(textoBotao);
                        botao.attr('disabled', false);
                    }

                    $('#numeroCotacao').text(data.resumo['numeroCotacao'] || '-');
                    $('#numeroCotacaotitle').text(data.resumo['numeroCotacao'] || '-');
                    $('#versaoCotacao').text(data.resumo['versaoCotacao'] || '-');
                    $('#clienteCotacao').text(data.resumo['cliente'] || '-');
                    $('#inicioVigenciaCotacao').text(data.resumo['inicioVigencia'] || '-');
                    $('#terminoVigenciaCotacao').text(data.resumo['terminoVigencia'] || '-');
                    $('#plataformaCotacao').text(data.resumo['plataforma'] || '-');
                    $('#cenarioVendaCotacao').text(data.resumo['cenarioVenda'] || '-');
                    $('#tipoPagamentoCotacao').text(data.resumo['tipoPagamento'] || '-');
                    $('#condicaoPagamentoCotacao').text(data.resumo['condicaoPagamento'] || '-');
                    $('#modalidadeVendaCotacao').text(data.resumo['modalidadeVenda'] || '-');
                    $('#canalVendaCotacao').text(data.resumo['canalVenda']);
                    $('#tempoContratoCotacao').text(data.resumo['tempoContrato'] || '-');
                    $('#tipoFreteCotacao').text(data.resumo['tipoFrete'] || '-');
                    $('#signatarioSoftwareCotacao').text(data.resumo['signatario_software'] || '-');
                    $('#emailSignatarioSoftwareCotacao').text(data.resumo['email_signatario_software'] || '-');
                    $('#documentoSignatarioSoftwareCotacao').text(data.resumo['documento_signatario_software'] || '-');
                    $('#signatarioHardwareCotacao').text(data.resumo['signatario_hardware'] || '-');
                    $('#emailSignatarioHardwareCotacao').text(data.resumo['email_signatario_hardware'] || '-');
                    $('#documentoSignatarioHardwareCotacao').text(data.resumo['documento_signatario_hardware'] || '-');
                    $('#statusDocusignCotacao').text(data.resumo['statusDocusign'] || '-');
                    $('#clientRetiraArmazemCotacao').text(data.resumo['clientRetiraArmazem'] || '-');
                    $('#armazemCotacao').text(data.resumo['armazem'] || '-');
                    $('#cpfResponsavelRetiradaCotacao').text(data.resumo['cpfResponsavelRetirada'] || '-');
                    $('#responsavelRetiradaCotacao').text(data.resumo['responsavelRetirada'] || '-');
                    $('#sistemaCotacao').text(data.resumo?.composicao?.sistema || '-');
                    $('#tipoComunicacaoCotacao').text(data.resumo?.composicao?.tipoComunicacao  || '-');
                    $('#familiaProdutoCotacao').text(data.resumo?.composicao?.familiaProduto || '-');
                    $('#produtoCotacao').text(data.resumo?.composicao?.produto || '-');
                    $('#categoriaCotacao').text(data.resumo?.composicao?.categoria || '-');
                    $('#tipoVeiculoCotacao').text(data.resumo?.composicao?.tipoVeiculo || '-');
                    $('#quantidadeCotacao').text(data.resumo?.composicao?.quantidade || '-');
                    
                    let gerouConfigurometro = data.resumo?.composicao?.gerouConfigurometro != undefined ? data.resumo?.composicao?.gerouConfigurometro : true;
                    let modalidadeVenda = data.resumo?.modalidadeVenda;

                    $('#gerouConfigurometro').val(gerouConfigurometro)                    

                    if(origem == "abrirModal"){
                        if(!gerouConfigurometro && modalidadeVenda != "Serviços Adicionais"){
                            $('#tab-configurometro').click();
                        }else{
                            $('#tab-dadosGerais').click();
                        }

                        if(modalidadeVenda == "Serviços Adicionais"){
                            $('#tab-configurometro').hide();
                        }else{
                            $('#tab-configurometro').show();
                        }

                        $('#modalResumoCotacao').modal('show');
                    }

                    tableServicosCotacao.clear().draw();
                    tableLicencasCotacao.clear().draw();
                    tableHardwaresCotacao.clear().draw();

                    if(data.resumo['hardwares'] && data.resumo['hardwares'].length > 0){
                        $.each(data.resumo['hardwares'], function(index, value){
                            tableHardwaresCotacao.row.add({
                                "hardwareid": value['hardwareid'] || '',
                                "produto": value['produto'] || '-',
                                "valorUnitarioCotacao": value['valorUnitario'] || '-',
                                "valorTotalCotacao": value['valorTotal'] || '-',
                                "quantidadeCotacao": value['quantidade'] || '-',
                                "percentualDescontoCotacao": value['percentualDesconto']+'%' || '-',
                            }).draw();                            
                        })
                    }else{
                        tableHardwaresCotacao.row.add({
                            "hardwareid": '',
                            "produto": '-',
                            "valorUnitarioCotacao": '-',
                            "valorTotalCotacao": '-',
                            "quantidadeCotacao": '-',
                            "percentualDescontoCotacao": '-',
                        }).draw();
                    }

                    if(data.resumo['licencas'] && data.resumo['licencas'].length > 0){
                        $.each(data.resumo['licencas'], function(index, value){
                            tableLicencasCotacao.row.add({
                                "licencaid": value['licencaid'] || "",
                                "produto": value['produto'] || '-',
                                "valorUnitarioCotacao": value['valorUnitario'] || '-',
                                "valorTotalCotacao": value['valorTotal'] || '-',
                                "quantidadeCotacao": value['quantidade'] || '-',
                                "percentualDescontoCotacao": value['percentualDesconto']+'%' || '-',
                                "planoSatelitalCotacao": value['planoSatelital'] || '-',
                            }).draw();     
                        })
                    }else{
                        tableLicencasCotacao.row.add({
                            "licencaid": '',
                            "produto": '-',
                            "valorUnitarioCotacao": '-',
                            "valorTotalCotacao": '-',
                            "quantidadeCotacao": '-',
                            "percentualDescontoCotacao": '-',
                            "planoSatelitalCotacao": '-',
                        }).draw();
                    }

                    if(data.resumo['servicos'] && data.resumo['servicos'].length > 0){
                        $.each(data.resumo['servicos'], function(index, value){
                            tableServicosCotacao.row.add({
                                "servicoid": value['servicoid'] || "",
                                "produto": value['produto'] || '-',
                                "valorUnitarioCotacao": value['valorUnitario'] || '-',
                                "valorTotalCotacao": value['valorTotal'] || '-',
                                "quantidadeCotacao": value['quantidade'] || '-',
                                "percentualDescontoCotacao": value['percentualDesconto']+'%' || '-',
                            }).draw();     
                        })
                    }else{
                        tableServicosCotacao.row.add({
                            "servicoid": '',
                            "produto": '-',
                            "valorUnitarioCotacao": '-',
                            "valorTotalCotacao": '-',
                            "quantidadeCotacao": '-',
                            "percentualDescontoCotacao": '-',
                        }).draw();
                    }
                    
                }else{
                    $('#modalResumoCotacao').modal('hide');
                    alert("Não foi possível carregar os dados da cotação! Tente novamente.");
                    document.getElementById('loading').style.display = 'none';
                    if(botao){
                        botao.html(textoBotao);
                        botao.attr('disabled', false);
                    }

                }
            },
        });
    }
    
    function abrirInput(botao, id, event){
        event.preventDefault();
        if(id){
            $('#divDesconto-' + id).show();
        }else{
            $('#divDesconto-' + id).hide();
        }
    }

    function tipoDesconto(input, id, event){
        event.preventDefault();
        let select = $(input);
        let inputValor = $("#inputDesconto-"+id);
        if(select.val() == '1'){
            inputValor.attr("placeholder", "Valor do desconto em R$")
            inputValor.attr("onkeyup", "formatarMoeda(this.id)")
        }else if(select.val() == '2'){
            formatarPorcetagem("inputDesconto-"+id)
            inputValor.attr("placeholder", "Valor do desconto em %")
            inputValor.attr("onkeyup", "formatarPorcetagem(this.id)")
        }
    }

    function confirmaDesconto(botao, idSubItem, tipoItem, event){  
        event.preventDefault();     

        inputDesconto = $('#inputDesconto-' + idSubItem ).val();
        inputDesconto = inputDesconto.replace(".", "").replace(",", ".");
        inputDesconto = parseFloat(inputDesconto).toFixed(2);
        tipo = $('#tipoDesconto-' + idSubItem ).val();
        valorTotal = $('#inputValor-' + idSubItem ).val();
        numeroDesconto = 0;

        if(tipo == '1'){
            numeroDesconto = inputDesconto;
        }else if(tipo == '2'){
            numeroDesconto = (valorTotal * (inputDesconto / 100));
        }

        if($('#inputDesconto-' + idSubItem ).val() == ''){
            alert('Preencha o campo desconto!');
        }else{
            botao = $(botao);
            botao.html('<i class="fa fa-spinner fa-spin"></i>');
            botao.attr('disabled', true);

            var idHardware = idSubItem;

            $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/addDescontoCotacao') ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    idItem: idSubItem,
                    tipoSubItem: tipoItem,
                    desconto: numeroDesconto
                },
                success: function(data){
                    if(data.results.Status == 200){
                        botao.html('<i class="fa fa-check"></i>');
                        botao.attr('disabled', false);
                        alert(data.results.Message);
                        $('#divDesconto-' + idSubItem).hide();
                        $('#inputDesconto-' + idSubItem).val('');
                        AtualizarModalResumoCotacao(null, null, null);
                    }else{
                        botao.html('<i class="fa fa-check"></i>');
                        botao.attr('disabled', false);
                        alert(data.results.Message);
                    }
                },
                error: function(data){
                    botao.html('<i class="fa fa-check"></i>');
                    botao.attr('disabled', false);
                    alert('Ocorreu um erro ao adicionar o desconto!');
                }
            });
        }

    }

    function fechaInputDesconto(botao, id, event){
        event.preventDefault();
        $('#divDesconto-' + id).hide();
        $('#inputDesconto-' + id).val('');
    }

    function removerSubItem(botao, id, tipoSubItem, event){
        event.preventDefault();
        if(id){
            botao = $(botao);
            if (confirm('Deseja realmente remover este item?')){
                botao.html('<i class="fa fa-spinner fa-spin"></i>');
                botao.attr('disabled', true);

                $.ajax({
                    url: `<?= site_url('ComerciaisTelevendas/Pedidos/removerSubItem') ?>`,
                    type: 'POST',
                    data: { tipoSubItem: tipoSubItem,
                            idItem: id},
                    success: function (response){
                        dataJson = JSON.parse(response);
                        if(dataJson.status == 204){
                            alert('Item removido com sucesso!');
                            botao.html('<i class="fa fa-trash"></i>');
                            botao.attr('disabled', false);
                            AtualizarModalResumoCotacao(null, null, null);
                        }else{
                            alert('Erro ao remover item!');
                            botao.html('<i class="fa fa-trash"></i>');
                            botao.attr('disabled', false);
                        }
                    },
                    error: function (response) {
                        alert('Erro ao remover item!');
                        botao.html('<i class="fa fa-trash"></i>');
                        botao.attr('disabled', false);

                    }
                });
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function abrirDadosAddHardware(botao){
        $('#modalHardware').modal('show');
    }

    function abrirDadosAddLicenca(botao){
        $('#modalLicenca').modal('show');
    }

    function abrirDadosAddServico(botao){
        $('#modalServico').modal('show');
    }

    function AtualizarTabelaDocumentosCotacao(cotacaoid){
        document.getElementById('loading').style.display = 'block';
        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos') ?>"+ '/getDocumentosCotacao',
            type: "POST",
            data: {idCotacao: idCotacao},
            success: function (data) {
                data = JSON.parse(data);
                if(data.status == 200){
                    response = data.data;
                    tableDocumentosCotacao.clear().draw();
                    tableDocumentosCotacao.rows.add(response).draw();
                }
            },
            complete: function (data) {
                document.getElementById('loading').style.display = 'none';
            },
        });
    }
    
    function AtualizarTabelaObservacaoCotacao(cotacaoid){
        document.getElementById('loading').style.display = 'block';
        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos') ?>"+ '/getObservacaoCotacao',
            type: "POST",
            data: {idCotacao: idCotacao},
            success: function (data) {
                data = JSON.parse(data);
                if(data.status == 200){
                    response = data.data;
                    tableObservacaoCotacao.clear().draw();
                    tableObservacaoCotacao.rows.add(response).draw();
                }
            },
            complete: function (data) {
                document.getElementById('loading').style.display = 'none';
            },
        });
    }

    function preencherTabelaCliente(resposta){ 
        $('.feedback-alert').html('');
        let data = [];
        resposta.forEach(element => {
            let quotenumber = "";
            let createdon = "";
            let statecode = "";
            let tz_valor_total_licenca = "";
            let tz_valor_total_hardware = "";
            let effectivefrom = "";
            let effectiveto = "";
            let cliente = "";
            let acoes = "";
            

            if(element['quotenumber'])
                quotenumber = element['quotenumber'];
                
            if(element['createdon'])
                createdon = new Date(element['createdon']).toLocaleDateString('pt-BR');
                 
            if(element['statuscode']){

                if(element['statecode'] == 0){
                    if(element['statuscode'] == 1 && element['tz_docusign_status'] == 419400000)
                        statecode = "Em Andamento";

                    if(element['statuscode'] == 419400002){
                        if(element['tz_docusign_status'] == 419400000)
                            statecode = "Pedido Gerado";
                            
                        if(element['tz_docusign_status'] == 419400001)
                            statecode = "Aguardando Assinatura";
                            
                        if(element['tz_docusign_status'] == 419400002)
                            statecode = "Proposta Assinada";
                            
                    }       
                    if(element['statuscode'] == 419400001 && element['tz_docusign_status'] == 419400002)
                        statecode = "Em Validação ADV";
                }

                if (element['statecode'] == 2 && element['statuscode'] == 4) 
                    statecode = "Ganha";
                    
                if (element['statecode'] == 3 && element['statuscode'] == 5) 
                    statecode = "Perdida";
            }

            if(element['tz_valor_total_licenca'])
                tz_valor_total_licenca = element['tz_valor_total_licenca'].toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});

            if(element['tz_valor_total_hardware'])
                tz_valor_total_hardware = element['tz_valor_total_hardware'].toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'});
                
            if(element['effectivefrom'])
                effectivefrom = new Date(element['effectivefrom']).toLocaleDateString('pt-BR');

            if(element['effectiveto'])
                effectiveto = new Date(element['effectiveto']).toLocaleDateString('pt-BR');

            if(element['CLIENTE_PF_x002e_fullname'])
                cliente = element['CLIENTE_PF_x002e_fullname'];
            else 
                cliente = element['CLIENTE_PJ_x002e_name'];

            if ((element['statecode'] == 0) && (element['statuscode'] == 1) && (element['tz_docusign_status'] == 419400000))                   
                acoes += '<div > <a data-serial="'+ element['quoteid']+'" class="btn btn-success gerar_pedido" role="button" id="gerar_pedido" title="Gerar Pedido"  ><i class="fa fa-cloud-upload"></i> Gerar Pedido</a></div>';
            
            if ((element['statecode'] == 0) && (element['statuscode'] == 419400002) && (element['tz_docusign_status'] == 419400000))
                acoes += '<div > <a data-docsign="'+element['quoteid']+'" class="btn btn-info enviar_docsign"  role="button" id="enviar_docsign" style="margin-top: 4px;" title="Solicitar assinatura" ><i class="fa fa-pencil"></i> Solicitar Assinatura</a></div>';
                
            if ((element['statecode'] == 2) && (element['statuscode'] == 4))
                acoes += '<div > <a data-status="'+element['quoteid']+'" class="btn btn-warning solicitar_statusclie"  role="button" id="solicitar_statusclie" style="margin-top: 4px;" title="Solicitar status" ><i class="fa fa-file"></i> Status</a></div>';

            acoes += '<div> <button class="btn btn-primary" data-statuscode="'+element['quoteid']+'" title="Resumo cotação" style="margin-top: 4px;" id="resumoCotacaoCliente" onClick="javascript:abrirModalResumoCotacaoCliente(this)"> <i class="fa fa-newspaper-o" aria-hidden="true"></i> Resumo </button> </div>';
            
            data.push([
                quotenumber,
                createdon,
                statecode,
                tz_valor_total_licenca,
                tz_valor_total_hardware,
                effectivefrom,
                effectiveto,
                cliente,
                acoes,
            ])
        });

        dt_table.clear().draw();
        dt_table.rows.add(data)
        .draw();
    }

    $(document).on('click', '.gerar_pedido', async function(e) {
        $('.feedback-alert').html('');
        e.preventDefault();
        var botao = $(this);
        var serial = botao.attr('data-serial');
        var documento = botao.attr('data-documento');
        var htmlBotao = botao.html();
        botao.html(ICONS.spinner); // adiciona o ícone de spinner ao botão
        botao.attr('disabled', true);

        let integrado = false

        let oportunidadeCliente  = JSON.parse(await getOportunidadesCliente());
        let oportunidadeVendedor = JSON.parse(await getOportunidadesVendedor());

        let oportunidades = oportunidadeCliente.concat(oportunidadeVendedor);
        
        if(typeof oportunidadesKanbanGerenciamento != 'undefined'){
            if (oportunidadesKanbanGerenciamento){
                oportunidades = oportunidades.concat(oportunidadesKanbanGerenciamento);
            }
        }

        let arrayFiltrado = oportunidades.filter(function (el) {
            return el.quoteid == serial;
        });

        if (arrayFiltrado.length >= 1 ) {
            if((arrayFiltrado[0].CLIENTE_PJ_x002e_zatix_codigocliente && arrayFiltrado[0].CLIENTE_PJ_x002e_zatix_codigocliente != null) || (arrayFiltrado[0].Cliente_PJ_x002e_zatix_codigocliente && arrayFiltrado[0].Cliente_PJ_x002e_zatix_codigocliente != null)){
                integrado = true
            }  
            if((arrayFiltrado[0].CLIENTE_PF_x002e_zatix_codigocliente && arrayFiltrado[0].CLIENTE_PF_x002e_zatix_codigocliente != null) || (arrayFiltrado[0].Cliente_PF_x002e_zatix_codigocliente && arrayFiltrado[0].Cliente_PF_x002e_zatix_codigocliente != null)){
                integrado = true
            }  
        }
        if (integrado){
            $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/AjaxGerarPedidoCRM') ?>",
                type: "POST",
                data: {
                    idCotacao: serial
                },
                timeout: 15000,
                success: function(data) {
                    botao.html(htmlBotao); // retorna o texto original do botão
                    botao.attr('disabled', false);
                    if (data) {
                        //alerta com a resposta do php ou alerta de erro
                        data = JSON.parse(data);
                        if(data) {
                            if(data.Status === 200) {
                                const documentoFormatado = documento.replace(/\D/g, '');
                                const urlTelevendasAdv = `${BASE_URL_API_TELEVENDAS}/adv/create-profile`;
                                const bodyTelevendas = {
                                    tipo: documentoFormatado.length === 11 ? 'pessoa_fisica' : 'pessoa_juridica',
                                    documento: documentoFormatado,
                                    idCotacaoCrm: serial,
                                };
                                axiosTelevendas.post(urlTelevendasAdv, bodyTelevendas);
                            }
                            alert(data.Message);
                        } else {
                            alert("Ocorreu um problema ao Gerar Pedido, tente novamente.");
                        }
                    } else {
                        alert("Ocorreu um problema ao Gerar Pedido, tente novamente.");
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    botao.html(htmlBotao); // retorna o texto original do botão
                    botao.attr('disabled', false);

                    if (textStatus === "timeout") {
                        // Tratamento personalizado para timeout
                        alert("Esgotado tempo limite de tentativa de conexão com o servidor. Por favor, tente mais tarde!");
                    } else {
                        // Tratamento padrão para outros erros
                        alert("Ocorreu um erro ao processar a solicitação.");
                    }

                },
                complete: function() {
                    BuscarOportunidadesVendedor();
                }
            });
        } else {
            $.confirm({
                title: "Gerar Pedido",
                content: "Cliente não integrado ao ERP, deseja atualizar suas informações ou integrar imediatamente?",
                buttons: {
                    atualizar: {
                        text: "Atualizar",
                        btnClass: "btn-blue",
                        action: function() {
                            $("#getResumoClienteERP").val(arrayFiltrado[0]._customerid_value);
                            $("#getResumoClienteERP").click();
                            documento = arrayFiltrado[0]._customerid_value;                           
                            cotacaoPedido = serial;
                            botao.html(htmlBotao);
                            botao.attr('disabled', false);
                        }
                    },
                    integrar: {
                        text: "Integrar",
                        btnClass: "btn-blue",
                        action: function() {
                            $("#getResumoClienteERP").val(arrayFiltrado[0]._customerid_value);
                            integrarClientePeloPedido(arrayFiltrado[0]._customerid_value, serial)
                            botao.html(htmlBotao);
                            botao.attr('disabled', false);
                        }
                    },
                    cancelar: {
                        text: "Cancelar",
                        btnClass: "btn-blue",
                        action: function() {
                            botao.html(htmlBotao);
                            botao.attr('disabled', false);
                        }
                    }
                }
            })
        }
    });

    var documentoCliente;

    function AtualizarFiltro(){

        $("#documentoPesquisa").prop('disabled', false);
        $("#BtnPesquisar").prop('disabled', false);

        switch (ativo) {
            case "quotes":

                $("#acao_oportunidade").hide();
                $("#pesquisa_avancada").hide();
                $("#acao_kanban").show();

                $('.data-pesquisa').show();
                break;
            case "clientes":
                $("#acao_oportunidade").hide();
                $("#acao_kanban").hide();

                if(documentoCliente){
                    $("#pesquisa_avancada").show();
                    $("#listagem_docs").attr("data-documento", documentoCliente);
                    $("#getResumoClienteERP").val(documentoCliente);
                }
                else
                    $("#pesquisa_avancada").hide();

                $('.data-pesquisa').hide();
                break;
            case "oportunidades":
                $("#acao_oportunidade").show();
                $("#pesquisa_avancada").hide();
                $("#acao_kanban").hide();

                $('.data-pesquisa').show();
                break;
            case "gerenciamento":
                $("#acao_oportunidade").hide();
                $("#pesquisa_avancada").hide();
                $("#acao_kanban").hide();
                
                $('.data-pesquisa').hide();
                $("#documentoPesquisa").prop('disabled', true);
                $("#BtnPesquisar").prop('disabled', true);
                break;
        }
    }
    $('#tab-clientes').click(function() {

    });

    $('#cep-principal-erp-pf').on('blur', function() {
        var cep = $(this).val();
        cep = cep.replace(".", "").replace("-", "");

        if (cep.length === 8) {
            try{
                $.ajax({
                    url: `https://viacep.com.br/ws/${cep}/json/`,
                    type: 'GET',
                    success: function(resposta) {
                        if (!resposta.erro) {
                            $('#rua-principal-erp-pf').val(resposta.logradouro);
                            $('#bairro-principal-erp-pf').val(resposta.bairro);
                            $('#cidade-principal-erp-pf').val(resposta.localidade);
                            $('#estado-principal-erp-pf').val(resposta.uf);
                        }
                    },
                    error: function(resposta) {
                        alert('Não foi possível buscar o CEP. Tente novamente');
                    }
                });
            }catch(exception){
                alert('Não foi possível buscar o CEP. Tente novamente');
            }
        }else{
            alert('Preencha o CEP corretamente');
        }
    });

    $('#cep-cobranca-erp-pf').on('blur', function() {
        var cep = $(this).val();
        cep = cep.replace(".", "").replace("-", "");

        if (cep.length === 8) {
            try{
                $.ajax({
                    url: `https://viacep.com.br/ws/${cep}/json/`,
                    type: 'GET',
                    success: function(resposta) {
                        if (!resposta.erro) {
                            $('#rua-cobranca-erp-pf').val(resposta.logradouro);
                            $('#bairro-cobranca-erp-pf').val(resposta.bairro);
                            $('#cidade-cobranca-erp-pf').val(resposta.localidade);
                            $('#estado-cobranca-erp-pf').val(resposta.uf);
                        }
                    },
                    error: function(resposta) {
                        alert('Não foi possível buscar o CEP. Tente novamente');
                    }
                });
            }catch(exception){
                alert('Não foi possível buscar o CEP. Tente novamente');
            }
        }else{
            alert('Preencha o CEP corretamente');
        }
    });

    $('#cep-entrega-erp-pf').on('blur', function() {
        var cep = $(this).val();
        cep = cep.replace(".", "").replace("-", "");

        if (cep.length === 8) {
            try{
                $.ajax({
                    url: `https://viacep.com.br/ws/${cep}/json/`,
                    type: 'GET',
                    success: function(resposta) {
                        if (!resposta.erro) {
                            $('#rua-entrega-erp-pf').val(resposta.logradouro);
                            $('#bairro-entrega-erp-pf').val(resposta.bairro);
                            $('#cidade-entrega-erp-pf').val(resposta.localidade);
                            $('#estado-entrega-erp-pf').val(resposta.uf);
                        }
                    },
                    error: function(resposta) {
                        alert('Não foi possível buscar o CEP. Tente novamente');
                    }
                });
            }catch(exception){
                alert('Não foi possível buscar o CEP. Tente novamente');
            }
        }else{
            alert('Preencha o CEP corretamente');
        }
    });

    $('#cep-principal-erp-pj').on('blur', function() {
        var cep = $(this).val();
        cep = cep.replace(".", "").replace("-", "");

        if (cep.length === 8) {
            try{
                $.ajax({
                    url: `https://viacep.com.br/ws/${cep}/json/`,
                    type: 'GET',
                    success: function(resposta) {
                        if (!resposta.erro) {
                            $('#rua-principal-erp-pj').val(resposta.logradouro);
                            $('#bairro-principal-erp-pj').val(resposta.bairro);
                            $('#cidade-principal-erp-pj').val(resposta.localidade);
                            $('#estado-principal-erp-pj').val(resposta.uf);
                        }
                    },
                    error: function(resposta) {
                        alert('Não foi possível buscar o CEP. Tente novamente');
                    }
                });
            }catch(exception){
                alert('Não foi possível buscar o CEP. Tente novamente');
            }
        }else{
            alert('Preencha o CEP corretamente');
        }
    });

    $('#cep-cobranca-erp-pj').on('blur', function() {
        var cep = $(this).val();
        cep = cep.replace(".", "").replace("-", "");

        if (cep.length === 8) {
            try{
                $.ajax({
                    url: `https://viacep.com.br/ws/${cep}/json/`,
                    type: 'GET',
                    success: function(resposta) {
                        if (!resposta.erro) {
                            $('#rua-cobranca-erp-pj').val(resposta.logradouro);
                            $('#bairro-cobranca-erp-pj').val(resposta.bairro);
                            $('#cidade-cobranca-erp-pj').val(resposta.localidade);
                            $('#estado-cobranca-erp-pj').val(resposta.uf);
                        }
                    },
                    error: function(resposta) {
                        alert('Não foi possível buscar o CEP. Tente novamente');
                    }
                });
            }catch(exception){
                alert('Não foi possível buscar o CEP. Tente novamente');
            }
        }else{
            alert('Preencha o CEP corretamente');
        }
    });

    $('#cep-entrega-erp-pj').on('blur', function() {
        var cep = $(this).val();
        cep = cep.replace(".", "").replace("-", "");

        if (cep.length === 8) {
            try{
                $.ajax({
                    url: `https://viacep.com.br/ws/${cep}/json/`,
                    type: 'GET',
                    success: function(resposta) {
                        if (!resposta.erro) {
                            $('#rua-entrega-erp-pj').val(resposta.logradouro);
                            $('#bairro-entrega-erp-pj').val(resposta.bairro);
                            $('#cidade-entrega-erp-pj').val(resposta.localidade);
                            $('#estado-entrega-erp-pj').val(resposta.uf);
                        }
                    },
                    error: function(resposta) {
                        alert('Não foi possível buscar o CEP. Tente novamente');
                    }
                });
            }catch(exception){
                alert('Não foi possível buscar o CEP. Tente novamente');
            }
        }else{
            alert('Preencha o CEP corretamente');
        }
    });
</script>