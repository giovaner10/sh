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
</style>
<h3><?=lang('cargo')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('departamentos')?> >
    <a href="<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais')?>"><?=lang('comercial_e_televendas')?></a> > 
    <?=lang('comissionamento_vendas')?>
</div>

<hr>

<div class="container-fluid my-1">
    
    
    <div class="row ">
        <div class="col-md-12 subtitulo">
            <div style="float: left;">
                <button type="button" id="btnAddCargo" class="btn btn-primary" style='float: left; position: relative; left: 20px'>Cadastrar Cargo</button>
            </div>
        </div>
    </div>

	<div class="col-sm-12">
       
        <div id="cargos" class="tab-pane fade in active" style="margin-top: 20px">
            <div class="container-fluid" id="tabelaGeral">
                			        	
                <table class="table-responsive table-striped table-bordered table-hover table" id="tabelaCargos">
                    <thead>
                        <tr class="tableheader">
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Empresa</th>
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

<div class="modal" id="modalCargo" role="dialog" aria-labelledby="modalCargo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4 !important">
                <div class="row">
                    <div class="col-md-10">
                        <h3 class="modal-title" style="display: none" id="cadTitle">Cadastrar Cargo</h3>
                        <h3 class="modal-title" style="display: none" id="editTitle">Editar Cargo</h3>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <form id="formCargo" class="col-md-12">
                    <input type="text" id="id" name="id" style="display: none">
                    <div class="row">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label>Nome: </label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label>Empresa: </label>                         
                            <select id="empresaid" class="form-control"  name="empresaid" required placeholder="Selecione uma Empresa">
                                <option value="" selected disabled>Selecione uma Empresa</option>
                                <?php foreach ($empresas as $emp): ?>
                                    <option value="<?= $emp->id ?>"><?= $emp->text ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row" id="divStatus" >
                        <span class="help help-block"></span>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4" placeholder="Selecione um Status">
                            <label>Status: </label>                         
                            <select id="status" class="form-control"  name="status" required>
                                    <option value="0">Inativo</option>
                                    <option value="1">Ativo</option>
                            </select>
                        </div>
                    </div>
                </form>
                <span class="help help-block"></span>
                <div class="row"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submit-form">Salvar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div id="loading">
    <div class="loader"></div>
</div>

<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>
<script>

    var tabelaCargos = $('#tabelaCargos').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        order: [2, 'desc'],           
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhuma campanha a ser listada",
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
        ajax:{
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarCargos') ?>',
            type: 'GET',
            dataType: 'json',
            //data: {idEmpresa: ''},
            success: function(data){
                if (data.status === 200){
                    tabelaCargos.clear().draw();
                    tabelaCargos.rows.add(data.results).draw();
                }else{
                    tabelaCargos.clear().draw();
                }
            },
            error: function(){
                alert('Erro ao buscar campanhas');
                tabelaCargos.clear().draw();
            }
        },
        columns: 
        [               
            { data: 'id', visible: false},                
            { data: 'nome'},
            { data: 'razaoSocial'},
            { data: 'status', visible: false},
            {
                data: null,
                orderable: false,
                render: function (data) {
                return `
                    <button 
                        class="btn btn-primary"
                        title="Editar Cargo"
                        data-id="${data.id}"
                        style="width: auto; margin: 0 auto;text-align: center;"
                        onclick="javascript:abrirModalCargo(this)"                     
                        >
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    `;
                }
            }
        ]
    });

    function abrirModalCargo(botao){
        let id = $(botao).attr('data-id');
        $("#editTitle").show()
        $("#cadTitle").hide()
        document.getElementById('loading').style.display = 'block';
        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarCargo') ?>?id="+id,
            type: "GET",   
            dataType: 'json',
            success: function(data){
                if(data && data['status'] == 200 && data['results']) {
                    $("#modalCargo").modal("show");
                    preencherModalCargo(data['results']);
                }else{
                    alert("Ocorreu um problema ao Buscar os Dados.");
                }
            },
            error: function(error){
                alert("Ocorreu um problema ao Buscar os Dados.");
            },
            complete: function(){
                document.getElementById('loading').style.display = 'none';
            },
        });  
    }
    
    function preencherModalCargo(cargo){
        $("#id").val(cargo['id']);
        $("#nome").val(cargo['nome']);
        $("#empresaid").val(cargo['idEmpresa']);
        $("#status").val(cargo['status'] == 'Ativo' ? 1 : 0);
        $("#divStatus").show();
    }    

    function limparModal(){
        $("#id").val("");
        $("#nome").val("");
        $("#empresaid").val("");
        $("#divStatus").hide();
    }    

    $("#submit-form").click(function(e){
        e.preventDefault();
        let form = $("#formCargo").serialize();

        let id = $("#id").val();
        let url = "<?php echo site_url('ComerciaisTelevendas/ComissionamentoDeVendas/adicionarCargo') ?>";
        if(id)
            url = "<?php echo site_url('ComerciaisTelevendas/ComissionamentoDeVendas/atualizarCargo') ?>";

        document.getElementById('loading').style.display = 'block';
        $.ajax({
            url: url,
            type: "POST",   
            dataType: 'json',
            data: form,
            success: function(data){
                if(data && data['status'] == 200) {
                    if(id)
                        alert("Atualizado com sucesso.");
                    else
                        alert("Cadastrado com sucesso.");
                    $("#modalCargo").modal("hide");
                    tabelaCargos.ajax.reload();
                }else if(data['status'] == 400 && data['results'].length > 0){
                    alert(data['results'][0]['erro'] + ". Campo: "+ data['results'][0]['campo']);
                }else{
                    alert("Ocorreu um problema ao enviar os Dados.");
                }
            },
            error: function(error){
                alert("Ocorreu um problema ao enviar os Dados.");
            },
            complete: function(){
                document.getElementById('loading').style.display = 'none';
            },
        });  
    })

    
    $("#btnAddCargo").click(function(e){
        e.preventDefault();
        $("#editTitle").hide()
        $("#cadTitle").show()
        $("#modalCargo").modal("show");
        limparModal();
    })
</script>

