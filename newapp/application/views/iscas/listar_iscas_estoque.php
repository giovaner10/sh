<style>
    body {
        background-color: #fff !important;
    }
    table {
        width: 100%;
        table-layout:fixed;
    }
    td {
        word-wrap:break-word;
    }
    .dt-buttons{
        margin-bottom: 20px;
    }
    .my-1 {
        margin: 1em 0 1em 0 !important;
    }
    .d-flex {
        display: flex;
    }
    .justify-content-between {
        justify-content: space-between;
    }
    div.dataTables_wrapper div.dataTables_paginate ul.pagination {
        white-space: normal !important;
    }

    @media screen and (max-width:768px){
        .div-caminho-menus-pais {
            width: 100% !important;
            margin-top: 0px !important;
        }
    }

    .dataTables_info {
        white-space: normal !important;
    }
</style>
<div class="col-md-12">
    <h3 ><?= $titulo?></h3>

    <div class="div-caminho-menus-pais">
        <a href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('iscas') ?> >
        <?= lang('equipamentos') ?> >
        <?= $titulo ?>
    </div>

    <hr>
    <!-- Btn cadastrar isca -->
    <!-- <div class="row justify-content-center" style="margin: 20px 0 20px 0">
        <div class="col-sm-1">
            <button type="button" id="btnAbrirModalCadastroIsca" class="btn btn-primary" data-toggle="modal" data-target="#modalCadastroIsca">
                <i class="fa fa-plus-square"></i>
                Cadastrar
            </button>
        </div>   
    </div> -->

    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="action-bar d-flex justify-content-between">
                <div class="btn-group my-1">
                    <button id="btnModalVincular" class="btn btn-primary">Migrar selecionadas</button>
                </div>
            </div>

            <table id="IscasSemVinculoTable" class="table table-hover table-bordered table-striped" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="max-width: 25px">
                            <input class="selectAll checkIscas" id="selectAll" names="selectAll" type="checkbox">
                        </th>
                        <th style="max-width: 50px">ID</th>
                        <th style="min-width: 50px">Serial</th>
                        <th style="min-width: 50px">Descrição</th>
                        <th style="min-width: 50px">Modelo</th>
                        <th style="min-width: 50px">Marca</th>
                        <th style="min-width: 50px">Data de Cadastro</th>
                        <th style="min-width: 50px">Data de Expiração</th>
                        <th style="min-width: 50px">Status</th>
                        <th style="min-width: 50px">Ações</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>    
    </div>

    <!-- Modal Cadastrar Isca-->
    <div class="modal fade" id="modalCadastroIsca" tabindex="-1" role="dialog" aria-labelledby="cadastroIscaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="cadastroIscaModalLabel">Cadastrar Isca</h4>
        </div>
        <div class="modal-body">
            <form id="formCadastroIsca">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="serial">Serial</label>
                            <input class="form-control" type="text" id="serial" name="serial" required>
                        </div>
                    </div>
    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <input class="form-control" type="text" id="modelo" name="modelo" required>
                        </div>
                    </div>
                </div>

                <div class="row">  
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                        </div>
                    </div>
                </div>

            </form>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-success" id="cadastrarIsca">Salvar</button>
        </div>
        </div>
    </div>
    </div>

    <!-- Modal Editar Isca-->
    <div class="modal fade" id="modalEditarIsca" tabindex="-1" role="dialog" aria-labelledby="editarIscaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="editarIscaModalLabel">Editar Isca</h4>
        </div>
        <div class="modal-body">
            <form id="formEditarIsca">
                    <input type="hidden" id="idIsca">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="serial">Serial</label>
                            <input class="form-control" type="text" id="editarSerial" name="editarSerial" required>
                        </div>
                    </div>
    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <input class="form-control" type="text" id="editarModelo" name="editarModelo" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <input class="form-control" type="text" id="editarMarca" name="editarMarca" required>
                        </div>
                    </div>
    
                </div>

                <div class="row">  
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control" id="editarDescricao" name="editarDescricao" rows="3"></textarea>
                        </div>
                    </div>
                </div>

            </form>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-success" id="editarIsca">Salvar</button>
        </div>
        </div>
    </div>
    </div>
    <!-- Modal Migrar Isca-->
    <div class="modal fade" id="modalMigrarIsca" tabindex="-1" role="dialog" aria-labelledby="migrarIscaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="migrarIscaModalLabel">Migrar Isca</h4>
        </div>
            <div class="modal-body">
                <form id="formEditarIsca">
                    <input type="hidden" id="idIscaMigrar">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="serial">Serial</label>
                                <h4 id="migrarSerial"></h4>
                            </div>
                        </div>
    
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="modelo">Modelo</label>
                                <h4 id="migrarModelo"></h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="marca">Marca</label>
                                <h4 id="migrarMarca"></h4>
                            </div>
                        </div>
        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <h4 id="migrarDescricao"></h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-sm-3">
                            <select class="form-control" name="tipoBuscaCliente" id="tipoBuscaCliente">
                                <option value="cnpj" selected>CNPJ</option>
                                <option value="cpf">CPF</option>
                                <option value="id_contrato">Contrato</option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <input type="text" class="form-control" id="cnpjBuscarNovoCliente" placeholder="Buscar Cliente por CNPJ">
                                <input type="text" class="form-control" id="cpfBuscarNovoCliente" placeholder="Buscar Cliente por CPF" style="display: none;">
                                <input type="text" class="form-control numeric" id="idContratoBuscarNovoCliente" placeholder="Buscar Cliente por Contrato" style="display: none;">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <a id="btnBuscarClientePorCNPJ" class="btn btn-primary" title="Buscar Cliente"><i class="fa fa-search" aria-hidden="true"></i></a>
                        </div>
                        
                    </div>
                
                

                <!-- Dados novo Cliente -->
                <div id="dadosNovoCliente" style="display: none;">
                    <input type="hidden" id="idNovoCliente" name="idNovoCliente">
                    <input type="hidden" id="idContratoNovoCliente" name="idContratoNovoCliente">
                    <div class="row" >
                        <div class="col-sm-6">
                            <label for="">Nome do Cliente</label>
                            <h5 id="nomeNovoCliente"></h5>
                        </div>
                        <div class="col-sm-6">
                            <label  id="labelTipoBusca" for="">CNPJ</label>
                            <h5 id="cnpjNovoCliente"></h5>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="">Endereço</label>
                            <h5 id="enderecoNovoCliente"></h5>
                        </div>
                    </div>
                </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="migrarIsca">Migrar</button>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="modalVincularSelecionados" tabindex="-1" role="dialog" aria-labelledby="modalVincularSelecionadosLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="migrarEquipamentoModalLabel">Migrar Iscas Selecionadas</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="iscasSelecionadas">Iscas selecionadas</label>
                                <input id="iscasSelecionadas" type="text" class="form-control" value="" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <select class="form-control" name="tipoBuscaClienteLote" id="tipoBuscaClienteLote">
                                <option value="cnpj" selected>CNPJ</option>
                                <option value="cpf">CPF</option>
                                <option value="id_contrato">Contrato</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" id="cnpjBuscarNovoClienteLote" placeholder="Buscar Cliente por CNPJ">
                                <input type="text" class="form-control" id="cpfBuscarNovoClienteLote" placeholder="Buscar Cliente por CPF" style="display: none;">
                                <input type="text" class="form-control numeric" id="idContratoBuscarNovoClienteLote" placeholder="Buscar Cliente por Contrato" style="display: none;">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <a id="btnBuscarCliente" class="btn btn-primary" title="Buscar cliente"><i class="fa fa-search" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div id="dadosNovoClienteLote" style="display: none;">
                        <input type="hidden" id="idNovoClienteLote" name="idNovoCliente">
                        <input type="hidden" id="idContratoNovoClienteLote" name="idContratoNovoCliente">
                        <div class="row" >
                            <div class="col-sm-6">
                                <label for="">Nome do Cliente</label>
                                <h5 id="nomeNovoClienteLote"></h5>
                            </div>
                            <div class="col-sm-6">
                                <label id="labelTipoBuscaLote" for="">CNPJ</label>
                                <h5 id="cnpjNovoClienteLote"></h5>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="">Endereço</label>
                                <h5 id="enderecoNovoClienteLote"></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="migrarIscasSelecionadas">Migrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilo do botão status -->
<link rel="stylesheet" type="text/css" href="<?= base_url("media/css/toggle-button.css") ?>">
<!-- helper iscas -->
<script type="text/javascript" src="<?= base_url("media/js/helpers/iscas-helper.js") ?>"></script>

<script type="text/javascript">

    $('.numeric').on('input', function (event) { 
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $(document).ready(function(){
        $('.buttons-csv').addClass('btn btn-info');
        $('.buttons-excel').addClass('btn btn-info');
        $('.buttons-pdf').addClass('btn btn-info');
        $('.buttons-print').addClass('btn btn-info');
    });

    // Cria Datatable
    const dtIscasSemVinculo = $('#IscasSemVinculoTable').DataTable({
        ajax: {
            url: '<?= site_url("iscas/isca/getIscasSemVinculo") ?>',
            type: 'POST',
            dataType: 'json'
        },
        responsive: true,
        paging: true,
        processing: true,
        sort: true,
        order: [[ 1, 'asc' ]],
        columnDefs: [{
            "targets": [0, 8, 9],
            "orderable": false
        }],
        aLengthMenu: [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
        dom: 'Blrtip',
        columns: [
            {
                data: 'id',
                render: function(data, type, row, meta) {
                    return '<input type="checkbox" value="' + data + '" class="checkIscas" name="checkIscas[]">';
                }
            },
            { data: 'id' },
            { data: 'serial' },
            { data: 'descricao'},
            { data: 'modelo'},
            { data: 'marca'},
            {
                data: 'data_cadastro',
                render: function(data, type, row, meta) {
                    return formatDateTime(data);
                }
            },
            {
                data: 'data_expiracao',
                render: function(data, type, row, meta) {
                    return formatDateTime(data);
                }
            },
            {
                data: {
                    id: 'id',
                    status: 'status'
                },
                render: function(data, type, row, meta) {
                    return returnStatus(data.status, data.id);
                }
            },
            {
                data: 'id',
                render: function(data, type, row, meta) {
                    return returnAcoes(data);
                }
            },
            
        ],
        buttons: [
            {
                extend: 'excelHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: ':not(:last-child):not(:first-child)'
                },
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL',
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: ':not(:last-child):not(:first-child)'
                },
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                customize: function (doc, tes)
                {
                    const titulo = `Iscas em Estoque`;
                
                    pdfTemplate(doc, titulo, 'A4');
                }
            },
            {
                extend: 'csvHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: ':not(:last-child):not(:first-child)'
                },
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-code-o"></i> CSV',
            },
            {
                extend: 'print',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: ':not(:last-child):not(:first-child)'
                },
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> IMPRIMIR',
                customize: function (win)
                {
                    titulo = `Iscas em Estoque`;
                    printTemplate(win, titulo);
                },
            }
        ],
        language: lang.datatable
    });

    var search_iscas = document.createRange().createContextualFragment(`
        <div style="float:right;">
                <input type="search" id="search_tabela" class="form-control input-sm" name="search_tabela" placeholder="Pesquisar">
        </div>`
    );

    $('#IscasSemVinculoTable_length').append(search_iscas);
    $('#IscasSemVinculoTable_length').css('width', '100%');

    $('#search_tabela').on('keyup change', function() {
        dtIscasSemVinculo.search('\\b' + $.fn.dataTable.util.escapeRegex(this.value), true, false).draw();
    });

    $(window).on('resize', function () {
        dtIscasSemVinculo.columns.adjust().draw();
    })


    $('.dt-buttons').css('width','100%');


    // Limpa os inputs do modal ao clicar no btn cadastrar
    $("#btnAbrirModalCadastroIsca").click(function(){
        $("#serial").val('');
        $("#modelo").val('');
        $("#descricao").val('');
    });

    // Cadastra Nova Isca
    $("#cadastrarIsca").click(function(){
        // Botão cadsatrarIsca
        let button = $(this);
        // Dados do form
        let data = {
            serial: $("#serial").val(),
            modelo: $("#modelo").val(),
            descricao: $("#descricao").val(),
            status: 0,
        }
        // Validação dos parâmetros
        if(data.serial == ""){
            alert("Por favor, informe o Serial");
            return false;
        }else if(data.modelo == ""){
            alert("Por favor, informe o Modelo");
            return false
        }else{
            button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando');
            $.ajax({
                url: '<?= site_url("iscas/isca/cadastrarIsca") ?>',
                type: "POST",
                data: data,
                dataType: "JSON",
                success: function(callback){
                    if(callback.status == true){
                        alert(callback.msg);
                        button.attr('disabled', false).html('Salvar');
                        dtIscasSemVinculo.ajax.reload();
                    }else{
                        button.attr('disabled', false).html('Salvar');
                        alert(callback.msg);
                    }  

                    $("#modalCadastroIsca").modal('hide');
                },
                error: function(){
                    alert("Não foi possível cadastrar a Isca");
                    button.attr('disabled', false).html('Salvar');
                    return;
                }
            });
        }
    });

    // Ao clicar no checkbox selecionar todos
    $("#selectAll").click(function(){
        let allPages = dtIscasSemVinculo.cells().nodes();
        if($("#selectAll").is(':checked')){
            
            
            $(allPages).find('.checkIscas[type="checkbox"]').prop('checked', true);
        }else{
            
            
            $(allPages).find('.checkIscas[type="checkbox"]').prop('checked', false).attr('disabled',false);
        }
    });

function returnAcoes(id_isca){
    return `
        <a type="button" title="Migrar Equipamento" id="btnMigrarIsca${id_isca}" onClick="modalMigrarIsca(${id_isca})" style="margin-left: 10px">
            <i class="fa fa-exchange" aria-hidden="true"></i>
        </a>
    `;

    // <a id="btnEditarIsca${id_isca}" onClick="editarIsca(${id_isca})" title="Editar Isca">
    //     <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
    // </a>
}
function editarIsca(id_isca){
    const btn = $(`#btnEditarIsca${id_isca}`);
    btn.html('<i class="fa fa-spinner fa-spin"></i>').attr('disabled',true);
    $.ajax({
        url: '<?= site_url("iscas/isca/getDadosIsca") ?>',
        type: 'GET',
        data: {id_isca: id_isca},
        success: function(data){
            btn.html('<i class="fa fa-pencil fa-lg" aria-hidden="true"></i>').attr('disabled',false);
            
            let isca = JSON.parse(data);
            $('#idIsca').val(isca.id)
            $("#editarSerial").val(isca.serial);
            $("#editarModelo").val(isca.modelo);
            $("#editarMarca").val(isca.marca);
            $("#editarDescricao").val(isca.descricao);

            $("#modalEditarIsca").modal('show')


        },
        error: function(error){
            alert('Erro ao buscar Isca');
        }
    });

}

function modalMigrarIsca(id_isca){
    const btn = $(`#btnMigrarIsca${id_isca}`);
    btn.html('<i class="fa fa-spinner fa-spin"></i>').attr('disabled',true);
    
    let cnpj = $("#cnpjBuscarNovoCliente");
    let cpf = $("#cpfBuscarNovoCliente");
    let idContrato = $("#idContratoBuscarNovoCliente");
    let nomeCliente = $("#nomeNovoCliente");
    let idNovoCliente = $("#idNovoCliente");
    let enderecoNovoCliente = $("#enderecoNovoCliente");
    let cpfCnpjCliente = $("#cnpjNovoCliente");
    let idContratoNovoCliente = $("#idContratoNovoCliente");
    let dadosNovoCliente = $('#dadosNovoCliente');

    $("#tipoBuscaCliente").val("cnpj");
    $("#cnpjBuscarNovoCliente").css('display','block');
    $("#cpfBuscarNovoCliente").css('display','none');
    $("#cpfBuscarNovoCliente").val("");
    $("#idContratoBuscarNovoCliente").css('display','none');
    $("#idContratoBuscarNovoCliente").val("");
    
    limparDadosNovoCliente(cnpj, cpf, idContrato, idNovoCliente, nomeCliente, cpfCnpjCliente, enderecoNovoCliente, idContratoNovoCliente, dadosNovoCliente);

    $.ajax({
        url: '<?= site_url("iscas/isca/getDadosIsca") ?>',
        type: 'GET',
        data: {id_isca: id_isca},
        success: function(data){
            btn.html('<i class="fa fa-exchange" aria-hidden="true"></i>').attr('disabled',false);
            
            let isca = JSON.parse(data);

            $("#idIscaMigrar").val(isca.id);
            $("#migrarSerial").html(isca.serial);
            $("#migrarModelo").html(isca.modelo);
            $("#migrarMarca").html(isca.marca);
            $("#migrarDescricao").html(isca.descricao);

            $("#modalMigrarIsca").modal('show')


        },
        error: function(error){
            btn.html('<i class="fa fa-exchange" aria-hidden="true"></i>').attr('disabled',false);
            alert('Erro ao buscar Isca');
        }
    });
}

$("#editarIsca").click(function(){
    
    
    // Botão cadsatrarIsca
    let button = $(this);
        // Dados do form
        let data = {
            id: $('#idIsca').val(),
            serial: $("#editarSerial").val(),
            modelo: $("#editarModelo").val(),
            marca: $("#editarMarca").val(),
            descricao: $("#editarDescricao").val(),
        }
        
        // Validação dos parâmetros
        if(data.serial == ""){
            alert("Por favor, informe o Serial");
            return false;
        }else if(data.modelo == ""){
            alert("Por favor, informe o Modelo");
            return false
        }else if(data.marca == ""){
            alert("Por favor, informe a Marca");
            return false
        }else{

            button.html('<i class="fa fa-spinner fa-spin"></i> Salvando').attr('disabled',true);

            $.ajax({
                url: '<?= site_url("iscas/isca/updateDadosIsca") ?>',
                type: 'POST',
                data: data,
                success: function(data){
                    button.html('Salvar').attr('disabled',false);
                    let resposta = JSON.parse(data);
                    
                    if(resposta.status){
                        let isca = resposta.isca;
                        
                        // Atualiza Datatable
                        dtIscasSemVinculo.rows().every(function(){
                            const row = this;
                            const linha = row.data();

                            if(linha != undefined){
                                const id_row = linha[0];
                                
                                if(id_row == isca.id){ //verifica o id    
                                    row.data([
                                        isca.id,
                                        isca.serial,
                                        isca.descricao,
                                        isca.modelo,
                                        isca.marca,
                                        formatDateTime(isca.data_cadastro),
                                        formatDateTime(isca.data_expiracao),
                                        returnStatus(isca.status, isca.id),
                                        returnAcoes(isca.id),
                                    ]).draw();
                                }
                            }
                        });

                        $("#modalEditarIsca").modal('hide');
                    }

                    alert(resposta.msg);

                },
                error: function(error){
                    button.html('Salvar').attr('disabled',false);
                    console.log(error);
                    alert('Erro ao atualizar Isca');
                }
            });
        }

});
function returnStatusAtivoInativo(status){
    label = '';
    if(status == 1){
        label = '<span class="badge badge-success" style="background-color: green">Ativo</span>'
    }else{
        label = '<span class="badge badge-danger" style="">Inativo</span>';
    }
    return label;
}

function ativarDesativarIsca(statusAtual,id_isca){
    let confirma = false;
    let colunaStatus = $(".statusIsca"+id_isca).parent();
    colunaStatus.html('<i class="fa fa-spinner fa-spin"></i>');
    if(statusAtual === 0){
        confirma = confirm("Você tem certeza que deseja ativar a isca?");
    }
    else{
        confirma = confirm("Você tem certeza que deseja desativar a isca?");
    } 

    if(confirma){
        $.ajax({
            url: '<?= site_url("iscas/isca/ajaxAtivarDesativarIsca") ?>',
            type: 'POST',
            data: {id_isca: id_isca},
            success: function(callback){
                data = JSON.parse(callback);
                alert(data.msg);
                dtIscasSemVinculo.ajax.reload();

            },
            error: function(){
                alert("Não foi possível buscar o cliente. Por favor, tente novamente.");
                dtIscasSemVinculo.ajax.reload();
            }
        });
        return; 
    }else{
        if(statusAtual === 0){
            colunaStatus.html(returnStatus(0,id_isca));
            return;
        }else{
            colunaStatus.html(returnStatus(1,id_isca));
            return;
        }
    }
    return;
}

$("#tipoBuscaCliente").change(function(){
    let tipo = $(this).val();
    trocarCamposTipoCliente(tipo, $('#cnpjBuscarNovoCliente'), $('#cpfBuscarNovoCliente'), $('#idContratoBuscarNovoCliente'));
});

$("#tipoBuscaClienteLote").change(function(){
    let tipo = $(this).val();
    trocarCamposTipoCliente(tipo, $('#cnpjBuscarNovoClienteLote'), $('#cpfBuscarNovoClienteLote'), $('#idContratoBuscarNovoClienteLote'));
});

function trocarCamposTipoCliente(tipo, cnpj, cpf, contrato) {
    if(tipo == 'cpf'){
        cnpj.css('display','none');
        cnpj.val("");
        contrato.css('display','none');
        contrato.val("");
        cpf.css('display','block');
    }else if(tipo == 'cnpj'){
        cnpj.css('display','block');
        cpf.css('display','none');
        cpf.val("");
        contrato.css('display','none');
        contrato.val("");
    }else{
        contrato.css('display','block');
        cnpj.css('display','none');
        cnpj.val("");
        cpf.css('display','none');
        cpf.val("");
    }
}

/**
Busca dados do cliente de acordo com o parametro informado (cpf ou cnpj) */
$("#btnBuscarClientePorCNPJ").click(function(){
    let button = $(this);
    let cnpj = $("#cnpjBuscarNovoCliente");
    let cpf = $("#cpfBuscarNovoCliente");
    let idContrato = $("#idContratoBuscarNovoCliente");
    let tipoBusca = $("#tipoBuscaCliente");
    let idIsca = $("#idIscaMigrar").val();
    let nomeCliente = $("#nomeNovoCliente");
    let idNovoCliente = $("#idNovoCliente");
    let enderecoNovoCliente = $("#enderecoNovoCliente");
    let cpfCnpjCliente = $("#cnpjNovoCliente");
    let idContratoNovoCliente = $("#idContratoNovoCliente");
    let dadosNovoCliente = $('#dadosNovoCliente');

    buscarCliente(button, cnpj, cpf, idContrato, idIsca, tipoBusca, idNovoCliente, nomeCliente, cpfCnpjCliente, enderecoNovoCliente, idContratoNovoCliente, dadosNovoCliente);
});

/**
Busca dados do cliente de acordo com o parametro informado (cpf ou cnpj) para modal de lotes */
$("#btnBuscarCliente").click(function(){
    let button = $(this);
    let cnpj = $("#cnpjBuscarNovoClienteLote");
    let cpf = $("#cpfBuscarNovoClienteLote");
    let idContrato = $("#idContratoBuscarNovoClienteLote");
    let tipoBusca = $("#tipoBuscaClienteLote");
    let idIsca = $("#iscasSelecionadas").val();
    let nomeCliente = $("#nomeNovoClienteLote");
    let idNovoCliente = $("#idNovoClienteLote");
    let enderecoNovoCliente = $("#enderecoNovoClienteLote");
    let cpfCnpjCliente = $("#cnpjNovoClienteLote");
    let idContratoNovoCliente = $("#idContratoNovoClienteLote");
    let dadosNovoCliente = $('#dadosNovoClienteLote');

    buscarCliente(button, cnpj, cpf, idContrato, idIsca, tipoBusca, idNovoCliente, nomeCliente, cpfCnpjCliente, enderecoNovoCliente, idContratoNovoCliente, dadosNovoCliente);
});

function buscarCliente(button, cnpj, cpf, idContrato, idIsca, tipoBusca, idNovoCliente, nomeCliente, cpfCnpjCliente, enderecoNovoCliente, idContratoNovoCliente, dadosNovoCliente) {
    let data = {
        cnpj: cnpj.val(), 
        cpf: cpf.val(), 
        id_contrato: idContrato.val(), 
        tipoBusca: tipoBusca.val(),
        id_isca: idIsca
    }
    
    if(tipoBusca == "cnpj" && cnpj == ""){
        alert("Por favor, informe o CNPJ do cliente.");
        return false;
    }else if(tipoBusca == "cpf" && cpf == ""){
        alert("Por favor, informe o CPF do cliente.");
        return false;
    }else if(tipoBusca == "id_contrato" && idContrato == ""){
        alert("Por favor, informe o ID do contrato do cliente.");
        return false;
    }else{
        // Limpa os dados exibidos ao buscar um novo cliente para fazer a vinculação
        limparDadosNovoCliente(cnpj, cpf, idContrato, idNovoCliente, nomeCliente, cpfCnpjCliente, enderecoNovoCliente, idContratoNovoCliente, dadosNovoCliente);
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
            url: '<?= site_url("iscas/isca/getDadosContratoClientePorCpfOuCnpj") ?>',
            type: 'POST',
            data: data,
            success: function(callback){
                data = JSON.parse(callback);
                if(data.status == false){
                    alert(data.msg)
                }else{
                    nomeCliente.html(data[0].nome);
                    if(tipoBusca.val() == "cnpj"){
                        $("#labelTipoBusca").html("CNPJ");
                        $("#labelTipoBuscaLote").html("CNPJ");
                        cpfCnpjCliente.html(data[0].cnpj);
                    }else if(tipoBusca.val() == "cpf"){
                        $("#labelTipoBusca").html("CPF");
                        $("#labelTipoBuscaLote").html("CPF");
                        cpfCnpjCliente.html(data[0].cpf);
                    }else{
                        $("#labelTipoBusca").html("Contrato");
                        $("#labelTipoBuscaLote").html("Contrato");
                        cpfCnpjCliente.html(data[0].id_contrato);
                    }
                    enderecoNovoCliente.html(`${data[0].endereco}, ${data[0].numero} - ${data[0].bairro}, ${data[0].cidade}.`)
                    idNovoCliente.val(data[0].id);
                    
                    ids_contratos = [];
                    data.forEach(contrato => {
                        ids_contratos.push(contrato.id_contrato)
                    });
                    idContratoNovoCliente.val(ids_contratos);

                    dadosNovoCliente.css('display','block');
                }
                button.attr('disabled', false).html('<i class="fa fa-search" aria-hidden="true">');
            },
            error: function(){
                alert("Não foi possível buscar o cliente. Por favor, tente novamente.");
                button.attr('disabled', false).html('<i class="fa fa-search" aria-hidden="true">');
            }
        });
    }        
}

function limparDadosNovoCliente(cnpj, cpf, idContrato, idNovoCliente, nomeCliente, cpfCnpjCliente, enderecoNovoCliente, idContratoNovoCliente, dadosNovoCliente){
    cnpj.val("");
    cpf.val("");
    idContrato.val("");
    nomeCliente.html("");
    cpfCnpjCliente.html("");
    enderecoNovoCliente.html("")
    idNovoCliente.val(""), 
    idContratoNovoCliente.val("")
    dadosNovoCliente.css('display','none');
}

$("#migrarIsca").click(function(){
    let idIsca = $("#idIscaMigrar");
    let idCliente = $("#idNovoCliente");
    let idContrato = $("#idContratoNovoCliente");
    let modal = $('#modalMigrarIsca')
    let button = $(this);
    
    migrarIscas(button, idIsca, idCliente, idContrato, modal);
});

$("#migrarIscasSelecionadas").click(function(){
    let idIsca = $("#iscasSelecionadas");
    let idCliente = $("#idNovoClienteLote");
    let idContrato = $("#idContratoNovoClienteLote");
    let modal = $('#modalVincularSelecionados')
    let button = $(this);

    migrarIscas(button, idIsca, idCliente, idContrato, modal);
});

function migrarIscas(button, idIsca, idCliente, idContrato, modal) {
       
    data = {
        id_isca: idIsca.val().split(','),
        id_cliente: idCliente.val(), 
        id_contrato: idContrato.val()
    }
    
    if(data.id_cliente == "" || data.id_cliente == undefined || data.id_cliente == null){
        alert("Informe o cliente para concluir a transferência.");
        return false;
    }else{
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Migrando');
        $.ajax({
            url: '<?= site_url("iscas/isca/migrarIscaCliente") ?>',
            type: 'POST',
            data: data,
            success: function(callback){
                data = JSON.parse(callback);
                alert(
                    data.msg
                    + ( (data.falhas.length > 0)
                        ? '\nAs seguintes iscas não puderam ser adicionadas: ' + data.falhas + '.'
                        : ''
                    )
                );
                button.attr('disabled', false).html('Migrar');
                // Lista iscas cadastradas vinculadas a um cliente
                dtIscasSemVinculo.ajax.reload();
                modal.modal('hide');
            },
            error: function(){
                alert("Não foi possível buscar o cliente, tente novamente.");
                
                button.attr('disabled', false).html('Migrar');
                modal.modal('hide');
            }
        });
    }
}

$('#btnModalVincular').on('click', () => {
    let iscas = dtIscasSemVinculo;
    let iscasInput = $('#iscasSelecionadas');

    let selecionados = '';
    let primeiro = true;
    $(iscas.cells().nodes()).find("input:checkbox[name='checkIscas[]']:checked").each(function(){
        selecionados = selecionados + (primeiro ? '' : ',') + $(this).val();
        primeiro = false;
    });

    if(selecionados) {
        iscasInput.val(selecionados);

        let cnpj = $("#cnpjBuscarNovoClienteLote");
        let cpf = $("#cpfBuscarNovoClienteLote");
        let idContrato = $("#idContratoBuscarNovoClienteLote");
        let nomeCliente = $("#nomeNovoClienteLote");
        let idNovoCliente = $("#idNovoClienteLote");
        let enderecoNovoCliente = $("#enderecoNovoClienteLote");
        let cpfCnpjCliente = $("#cnpjNovoClienteLote");
        let idContratoNovoCliente = $("#idContratoNovoClienteLote");
        let dadosNovoCliente = $('#dadosNovoClienteLote');

        $("#tipoBuscaClienteLote").val("cnpj");
        $("#cnpjBuscarNovoClienteLote").css('display','block');
        $("#cpfBuscarNovoClienteLote").css('display','none');
        $("#cpfBuscarNovoClienteLote").val("");
        $("#idContratoBuscarNovoClienteLote").css('display','none');
        $("#idContratoBuscarNovoClienteLote").val("");

        limparDadosNovoCliente(cnpj, cpf, idContrato, idNovoCliente, nomeCliente, cpfCnpjCliente, enderecoNovoCliente, idContratoNovoCliente, dadosNovoCliente);
        
        $('#modalVincularSelecionados').modal('show');
    } else {
        alert('Selecione pelo menos uma isca.');        
    }

});

</script>