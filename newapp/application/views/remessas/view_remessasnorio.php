<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente('Remessas - Norio', site_url('Homes'), 'Contas', 'Remessas Norio');
?>


<div id="loading">
    <div class="loader"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">

        <?php
        menuLateralComponente(['Remessa de salário', 'Remessas de fornecedores', 'Remessa de títulos', 'Remessa de guias']);
        ?>

        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Ações:</h4>

            <div class="form-group filtro" id="salario-tab">
                <div class="button-container">
                    <button class="btn btn-success atualizar-force" style='width:100%; margin-top: 5px' onclick="marcar_tudo(checkboxInstal)">Marcar tudo</button>
                    <button class="btn btn-success" style='width:100%; margin-top: 5px' onclick="gerar_remessa(1)">Gerar remessa</button>
                </div>
            </div>

            <div class="form-group filtro" id="three-tab" style="display: none;">
                <div class="input-container">
                    <label>Data de pagamento</label> <br>
                    <input name="data_pagamento_fornecedor1" class="form-control" id="data_pagamento_fornecedor1" type="date">
                </div>

                <div class="button-container">
                    <button class="btn btn-success atualizar-force" style='width:100%; margin-top: 5px' onclick="marcar_tudo(checkboxFornecedores)">Marcar tudo</button>
                    <button class="btn btn-success" style='width:100%; margin-top: 5px' onclick="gerar_remessa_fornecedor(1)" style="margin-top: -10px;">Gerar</button>
                </div>
            </div>

            <div class="form-group filtro" id="titulo-tab" style="display: none;">
                <div class="input-container">
                    <label>Data de pagamento</label> <br>
                    <input name="data_pagamento_titulo" class="form-control" id="data_pagamento_titulo" type="date">
                </div>

                <div class="button-container">
                    <button class="btn btn-success atualizar-force" style='width:100%; margin-top: 5px' onclick="marcar_tudo(checkboxTitulo)">Marcar tudo</button>
                    <button class="btn btn-success" style='width:100%; margin-top: 5px' onclick="gerar_remessa_titulo(1)" style="margin-top: -10px;">Gerar</button>
                </div>
            </div>

            
            <div class="form-group filtro" id="guia-tab" style="display: none;">
                <div class="input-container">
                    <label>Data de pagamento</label> <br>
                    <input name="data_pagamento_guia" class="form-control" id="data_pagamento_guia" type="date">
                </div>

                <div class="button-container">
                    <button class="btn btn-success atualizar-force" style='width:100%; margin-top: 5px' onclick="marcar_tudo(checkboxGuia)">Marcar tudo</button>
                    <button class="btn btn-success" style='width:100%; margin-top: 5px' onclick="gerar_remessa_guia(1)" style="margin-top: -10px;">Gerar</button>
                </div>
            </div>

            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>
            <form id="formBusca">
                <div class="form-group filtro">

                    <div class="input-container">
                        <label for="filtrar-atributos">ID usuário ou Fornecedor</label>
                        <input type="text" name="filtrar-atributos" class="form-control" placeholder="ID usuário ou Fornecedor" id="filtrar-atributos" />
                    </div>


                    <div class="button-container">
                        <button class="btn btn-default" style='width:100%; margin-top: 5px' id="BtnLimparFiltro" type="button"><i class="fa fa-leaf" aria-hidden="true"></i> Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9" id="conteudo">

        <div class="card-conteudo card-cadastro-clientes" style='margin-bottom: 20px; <?= $menu_omnicom == 'CadastroDeClientes' ? '' : 'display: none;' ?>'>
            <h3>
                <b id="titulo-card">Norio: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
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
            <!-- <div class="registrosDiv">
                <select id="select-quantidade-por-contatos-corporativos" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value=10 selected>10</option>
                    <option value=25>25</option>
                    <option value=50>50</option>
                    <option value=100>100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div> -->
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

<div id="loading">
    <div class="loader"></div>
</div>
<style>

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
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

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
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/remessas/norio', 'remessasNorio.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/remessas/norio', 'colunasNorio.js') ?>"></script>


<script type="text/javascript" src="<?= versionFile('assets/js/remessas', 'exportacoes.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    var BaseURL = '<?= base_url('') ?>';
    var get_intaladores_pendentes = '<?= base_url() ?>index.php/api/get_salarios_pendentes<?php if ($this->input->get("empresa")) {
                                                                                                echo "?empresa=" . $this->input->get("empresa");
                                                                                            } else {
                                                                                                echo "?empresa=3";
                                                                                            } ?>';
    var get_fornecedores_pendentes = '<?= base_url() ?>index.php/api/get_fornecedores_pendentes<?php if ($this->input->get("empresa")) {
                                                                                                    echo "?empresa=" . $this->input->get("empresa");
                                                                                                } else {
                                                                                                    echo "?empresa=3";
                                                                                                } ?>';
    var get_remessas_titulo = '<?= base_url() ?>index.php/api/get_titulo_pendentes<?php if ($this->input->get("empresa")) {
                                                                                        echo "?empresa=" . $this->input->get("empresa");
                                                                                    } else {
                                                                                        echo "?empresa=3";
                                                                                    } ?>';
    var get_remessas_guia = '<?= base_url() ?>index.php/api/get_guia_pendentes<?php if ($this->input->get("empresa")) {
                                                                                    echo "?empresa=" . $this->input->get("empresa");
                                                                                } else {
                                                                                    echo "?empresa=3";
                                                                                } ?>';


        var today = new Date();
        var newdate = new Date();
        newdate.setDate(today.getDate()+1);
        var newdate1 = new Date();
        newdate1.setDate(today.getDate());
        var newdate2 = new Date();
        newdate2.setDate(today.getDate()-1);
        document.getElementsByName("data_pagamento_fornecedor1")[0].setAttribute('min', newdate1.toISOString().split('T')[0]);
        document.getElementsByName("data_pagamento_titulo")[0].setAttribute('min', newdate2.toISOString().split('T')[0]);
        document.getElementsByName("data_pagamento_guia")[0].setAttribute('min', newdate.toISOString().split('T')[0]);

/// salario-tab
    function gerar_remessa(guia){

           let chaveSalario = chavesObjetos(checkboxInstal, "chaveSalario[]")

           showLoadingScreen()
            $.ajax({
                type: "POST",
                url: "<?=base_url()?>index.php/api/get_remessa_salario_norio<?php if($this->input->get("empresa")){echo "?empresa=".$this->input->get("empresa");}?>",
                data: "novatela=ok"+chaveSalario,
                success: function (data){
                    try {
                        data = JSON.parse(data);
                        var element = document.createElement('a');
                        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(data.arquivo));
                        element.setAttribute('download', data.nome);
                        element.style.display = 'none';
                        document.body.appendChild(element);
                        element.click();
                        document.body.removeChild(element);
                        
                    } catch (error) {
                        showAlert("warning","Falha ao gerar!")
                    }finally{
                        hideLoadingScreen();
                    }
                }
            });
        }

/// three-tab
function gerar_remessa_fornecedor(guia){
            $continua = false;

            if(!$('#data_pagamento_fornecedor'+guia)[0].value){
                showAlert("warning","Preencha a data de pagamento");
                return;
            }

            if($("#data_pagamento_fornecedor"+guia)[0].value<$("#data_pagamento_fornecedor"+guia)[0].min){
                showAlert("warning","Data de pagamento inválida");
                return;
            }

            $.each(document.getElementsByName('id_contas_fornecedores[]'),  function (index,data){
                if(data.checked){
                    $continua = true;
                }
            });

            if(!$continua){
                showAlert("warning","Nenhuma conta marcada");
                return;
            }

           showLoadingScreen()

            let chaveFornecedor = chavesObjetos(checkboxFornecedores, "chaveFornecedor[]")
            let chaveFornecedorTed = chavesObjetos(checkboxFornecedoresTed, "chaveFornecedorTed[]")

            $.ajax({
                type: "POST",
                url: "<?=base_url()?>index.php/api/get_remessa_fornecedores_norio<?php if($this->input->get("empresa")){echo "?empresa=".$this->input->get("empresa");}?>",
                data: "novatela=ok"+"&data_pagamento_fornecedor="+$('#data_pagamento_fornecedor'+guia)[0].value+chaveFornecedor+chaveFornecedorTed,
                success: function (data){

                    try {
                        data = JSON.parse(data);
                        var element = document.createElement('a');
                        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(data.arquivo));
                        element.setAttribute('download', data.nome);
                        element.style.display = 'none';
                        document.body.appendChild(element);
                        element.click();
                        document.body.removeChild(element);
                        
                    } catch (error) {
                        showAlert("warning","Falha ao gerar!")
                    }finally{
                        hideLoadingScreen();
                    }
                }
            });
        }

/// titulo-tab
        function gerar_remessa_titulo(guia){
            $continua = false;
            if(!$('#data_pagamento_titulo')[0].value){
                showAlert("warning","Preencha a data de pagamento");
                return;
            }
            if($("#data_pagamento_titulo")[0].value<$("#data_pagamento_titulo")[0].min){
                showAlert("warning","Data de pagamento inválida");
                return;
            }
            $.each(document.getElementsByName('id_contas_titulos[]'),  function (index,data){
                if(data.checked){
                    $continua = true;
                }
            });
            if(!$continua){
                showAlert("warning","Nenhuma conta marcada");
                return;
            }

           let chaveTitulo = chavesObjetos(checkboxTitulo, "chaveTitulo[]")

           showLoadingScreen()

            $.ajax({
                type: "POST",
                url: "<?=base_url()?>index.php/api/get_remessa_boleto_norio<?php if($this->input->get("empresa")){echo "?empresa=".$this->input->get("empresa");}?>",
                data: "novatela=ok"+"&data_pagamento_titulo="+$('#data_pagamento_titulo')[0].value+chaveTitulo,
                success: function (data){
                    try {
                        data = JSON.parse(data);
                        var element = document.createElement('a');
                        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(data.arquivo));
                        element.setAttribute('download', data.nome);
                        element.style.display = 'none';
                        document.body.appendChild(element);
                        element.click();
                        document.body.removeChild(element);
                        
                    } catch (error) {
                        showAlert("warning","Falha ao gerar!")
                    }finally{
                        hideLoadingScreen();
                    }
                }
            });
        }

/// guia-tab

function gerar_remessa_guia(guia){
            $continua = false;
            if(!$('#data_pagamento_guia')[0].value){
                showAlert("warning","Preencha a data de pagamento");
                return;
            }
            if($("#data_pagamento_guia")[0].value<$("#data_pagamento_guia")[0].min){
                showAlert("warning","Data de pagamento inválida");
                return;
            }
            $.each(document.getElementsByName('id_contas_guia[]'),  function (index,data){
                if(data.checked){
                    $continua = true;
                }
            });
            if(!$continua){
                showAlert("warning","Nenhuma conta marcada");
                return;
            }

           let chaveGuia = chavesObjetos(checkboxGuia, "chaveGuia[]")

           showLoadingScreen()
            $.ajax({
                type: "POST",
                url: "<?=base_url()?>index.php/api/get_remessa_boleto_norio?guia=1<?php if($this->input->get("empresa")){echo "&empresa=".$this->input->get("empresa");}?>",
                data: "novatela=ok"+"&data_pagamento_guia="+$('#data_pagamento_guia')[0].value+chaveGuia,
                success: function (data){
                    try {
                        data = JSON.parse(data);
                        var element = document.createElement('a');
                        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(data.arquivo));
                        element.setAttribute('download', data.nome);
                        element.style.display = 'none';
                        document.body.appendChild(element);
                        element.click();
                        document.body.removeChild(element);
                    } catch (error) {
                        showAlert("warning","Falha ao gerar!")
                    }finally{
                        hideLoadingScreen();
                    }
                }
            });
        }

        function showLoadingScreen() {
	        $('#loading').show()
        }

        function hideLoadingScreen() {
	        $('#loading').hide()
        }


        function chavesObjetos(objeto, texto, tipoBoolean =  true){
        var chavesTrue = "";

        for (const chave in objeto) {
            if (objeto[chave] == tipoBoolean) {
                chavesTrue += "&"+texto+"="+chave.toString()
            }
        }
        
        return chavesTrue;
        }


        function marcar_tudo(obj){
            for (let key in obj) {
                obj[key] = true;
            }
        }

</script>