
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

    .text-center {
    text-align: center;
    }

</style>

<h3><?=lang("operadoras")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('telecom')?> >
	<?=lang('operadoras')?>
</div>

<div class="container-fluid my-1">
	<div class="col-sm-12">         
            <form id="formPesquisa">
                <div class="row">
                    <div id="pesqData" class="alert alert-info col-md-12" style="padding-bottom: 15px;">
                        <h5 style="margin-left: 15px;">Informe as datas</h5>
                        <div class="col-md-2">
                            <label>Nome: </label>
                            <select class="form-control select-2" id="pesqnome" name="nome" type="text"></select>
                        </div>
                        <div class="col-md-2">
                            <label>Data Inicial: </label>
                            <input class="form-control" id="dataInicial" type="date" style="padding-bottom: 3px;">
                        </div>
                        <div class="col-md-2">
                            <label>Data Final: </label>
                            <input class="form-control" id="dataFinal" type="date" style="padding-bottom: 3px;">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" id="pesquisaOperadoras" style="margin-top: 25px;" type="submit">Pesquisar</button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <a id="abrirModalInserir" class="btn btn-primary"><?= lang("nova_operadora") ?></a>
                    </div>
                </div>
            </form>

        <div id="empresas" class="tab-pane fade in active" style="margin-top: 20px">
            <div class="container-fluid" id="tabelaGeral">
                <table class="table-responsive table-bordered table" id="tabelaOperadoras">
                    <thead>
                        <th>Operadora</th>
                        <th>Data de Cadastro</th>
                        <th>Data de Atualização</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>    
            </div>
        </div>
	</div>
</div>

<!-- Modal Inserir -->
<div id="modalCadOperadora" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadOperadora">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("nova_operadora")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Nome</label>
                                            <input type="text" class="form-control" id="nomeOperadora" placeholder="Digite o nome da operadora">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <a class="btn btn-primary" id="btnSalvarCadastroOperadora">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="modalEditOperadora" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formEditarOperadora">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("editar_operadora")?></h3>
                </div>
                <div id="bodyModalEditarOperadora" class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group" hidden>
                                            <label>Id</label>
                                            <input type="text" class="form-control" id="idOperadoraAlterar" disabled>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control">Nome</label>
                                            <input type="text" class="form-control" id="nomeOperadoraAlterar">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control">Status</label>
                                            <select type="text" class="form-control input-sm" id="statusOperadoraAlterar">
                                                <option value="Ativo">Ativo</option>
                                                <option value="Inativo">Inativo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <a class="btn btn-primary" id="btnSalvarEditOperadora">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    var tabelaOperadoras = $('#tabelaOperadoras').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        language: lang.datatable,
        deferRender: true,
        lengthChange: false,
        columnDefs: [
            { 
                className: 'text-center',
                targets: '_all' 
            }
        ],
        columns: [
            { data: 'nome' },
            { data: 'dataCadastro', render: function(data){return new Date(data).toLocaleString();} },
            { data: 'dataUpdate', render: function(data){return new Date(data).toLocaleString();} },
            { data: 'status' },
			{
				data:{'id':'id','status': 'status', 'nome': 'nome'},
				orderable: false,
				render: function (data) {
					return `
					<button 
						class="btn btn-primary"
						title="Editar Operadora"
						style="width: 42px; margin: 0 auto;"
						id="btnEditarOperadora"
                        onClick="javascript:editOperadora(this,'${data['id']}','${data['status']}','${data['nome']}')">
						<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
					</button>
                    `;
				}
			}
        ]
    });

$(document).ready(async function(){
    await povoarOperadoras();
});

async function povoarOperadoras(){
    $("#pesqnome").empty()
    $("#pesqnome").append("<option value='0'>Buscando Operadoras...</option>")
    $("#pesqnome").select2({
        width: '100%',
        placeholder: "Buscando operadoras...",
        allowClear: true
    })

    let operadoras = await buscarOperadoras()
    
    if(operadoras.length){
            operadoras.forEach(operadora => {
                $("#pesqnome").append(`<option value="${operadora.id}">${operadora.text}</option>`)
            })
            $("#pesqnome").find('option').get(0).remove()
            $("#pesqnome").prepend(`<option selected disabled value="0">Selecione a operadora</option>`)
        } else {
            $("#pesqnome").find('option').get(0).remove()
            $("#pesqnome").prepend(`<option selected disabled value="0">Nenhuma operadora encontrada</option>`)
        }
}

async function buscarOperadoras(){
        
        let operadoras = await $.ajax({
            url: '<?= site_url('GestaoDeChips/operadoras/listarOperadoras') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    return data
                }else{
                    return false
                }
            }
        })
        return operadoras;
    }

$('#pesquisaOperadoras').click(function(e){
        e.preventDefault();

        $('#pesquisaOperadoras')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('buscando')?>')
            .attr('disabled', true);
        var dInicial = document.getElementById('dataInicial').value;
        var dateSeparaInicial = dInicial.split("-");
        var dataFormatadaInicial = dateSeparaInicial[2] + "/" + dateSeparaInicial[1] + "/" + dateSeparaInicial[0];

        var dFinal = document.getElementById('dataFinal').value;
        var dateSeparaFinal = dFinal.split("-");
        var dataFormatadaFinal = dateSeparaFinal[2] + "/" + dateSeparaFinal[1] + "/" + dateSeparaFinal[0];

        var pesqOperadora = $("#pesqnome").val();
        
        if((dInicial == "" && dFinal == "") && (pesqOperadora != null && pesqOperadora != '0')){
            $.ajax({
                url: '<?= site_url('GestaoDeChips/operadoras/listarTodasOperadoras') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    if (data.status === 200){
                        let operadoras = data.results;

                        let nomeOperadora = $("#pesqnome").select2('data')[0].text;
                        operadoras = operadoras.filter(function(operadoras) {
                            return operadoras.nome === nomeOperadora;
                        });

                        if (operadoras.length > 0) {
                            tabelaOperadoras.clear().draw();
                            tabelaOperadoras.rows.add(operadoras).draw();
                            $('#tabelaGeral').show();
                            $('#alert').hide();
                            $('#pesquisaOperadoras')
                            .html('Pesquisar')
                            .attr('disabled', false);
                        } else {
                            alert('Não existem registros de operadora com esse nome. Tente novamente.');
                            $('#pesquisaOperadoras')
                            .html('Pesquisar')
                            .attr('disabled', false);
                            tabelaOperadoras.clear().draw();
                        }
                    } else {
                        alert(data.results.mensagem);
                        $('#pesquisaOperadoras')
                            .html('Pesquisar')
                            .attr('disabled', false);
                    }
                },
                error: function (jqXHR, textStatus) {
                    alert(jqXHR.responseText);
                        $('#pesquisaOperadoras')
                            .html('Pesquisar')
                            .attr('disabled', false);
                }
            })
            
        } else if (dInicial != "" && dFinal != ""){
            $.ajax({
                url: '<?=site_url('GestaoDeChips/operadoras/listarOperadorasPorData')?>',
                type: 'POST',
                data: { dataInicial: dataFormatadaInicial, 
                        dataFinal: dataFormatadaFinal},
                dataType: 'json',
                success: function(data){
                    if (data.status == 200){                     
                    
                    let operadoras = data.results;

                    if ((pesqOperadora != null)){
                        let nomeOperadora = $("#pesqnome").select2('data')[0].text;
                        operadoras = operadoras.filter(function(operadoras) {
                            return operadoras.nome === nomeOperadora;
                        });
                    }

                    if (operadoras.length > 0) {
                        tabelaOperadoras.clear().draw();
                        tabelaOperadoras.rows.add(operadoras).draw();
                        $('#tabelaGeral').show();
                        $('#alert').hide();
                        $('#pesquisaOperadoras')
                        .html('Pesquisar')
                        .attr('disabled', false);
                    } else {
                        alert('Não existem operadoras com esse nome no período escolhido. Tente novamente.');
                        $('#pesquisaOperadoras')
                        .html('Pesquisar')
                        .attr('disabled', false);
                        tabelaOperadoras.clear().draw();
                    }

                    }else{
                        alert(data.results.mensagem);
                        $('#pesquisaOperadoras')
                            .html('Pesquisar')
                            .attr('disabled', false);
                    }
                },

            });
            
        } else {
            alert("Informe os dados corretamente.");
            $('#pesquisaOperadoras')
                    .html('Pesquisar')
                    .attr('disabled', false);
        }
})

$('#abrirModalInserir').click(function(){
    $('#modalCadOperadora').modal('show');
})

$('#btnSalvarCadastroOperadora').click(function(){
    $('#btnSalvarCadastroOperadora')
        .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
        .attr('disabled', true);
    var nomeOperadora = $('#nomeOperadora').val();
    if(nomeOperadora == ""){
        alert("Informe o nome da operadora");
        $('#btnSalvarCadastroOperadora')
            .html('Salvar')
            .attr('disabled', false);
    }else{
        $.ajax({
            url: '<?=site_url('GestaoDeChips/operadoras/cadastrarOperadora')?>',
            type: 'POST',
            data: { nome: nomeOperadora},
            dataType: 'json',
            success: function(data){
                if (data.status == 200){
                    $('#modalCadOperadora').modal('hide');
                    $('#nomeOperadora').val("");
                    $('#btnSalvarCadastroOperadora')
                        .html('Salvar')
                        .attr('disabled', false);
                    alert(data.dados.mensagem);
                }else{
                    alert(data.dados.mensagem);
                    $('#btnSalvarCadastroOperadora')
                        .html('Salvar')
                        .attr('disabled', false);
                }
            },

        });
    }
});

$(document).ready(function() {
    $.ajax({
        url: '<?=site_url('GestaoDeChips/operadoras/listarOperadorasPorData')?>',
        type: 'POST',
        data: { dataInicial: '', 
                dataFinal: ''},
        dataType: 'json',
        success: function(data){
            if (data.status == 200){                   
            tabelaOperadoras.clear().draw();
            tabelaOperadoras.rows.add(data.results).draw();
            }else{
                alert(data.dados.Mensagem);
            }
            },
    });
});

function editOperadora(botao,id,status,nome){
    $('#modalEditOperadora').modal('show');
    $('#idOperadoraAlterar').val(id);
    $('#statusOperadoraAlterar').val(status);
    $('#nomeOperadoraAlterar').val(nome);
    
}

$('#btnSalvarEditOperadora').click(function(e){
    e.preventDefault();

    $('#btnSalvarEditOperadora')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('salvando')?>')
            .attr('disabled', true);

    var id = $('#idOperadoraAlterar').val();
    var status = $('#statusOperadoraAlterar').val();
    var nome = $('#nomeOperadoraAlterar').val();

    if (nome == "") {
        alert("Informe o nome da operadora");
        $('#btnSalvarEditOperadora')
            .html('Salvar')
            .attr('disabled', false);
        return false;
    }
    if (status == 'Ativo'){
        status = 0;
    }else{
        status = 1;
    }

    $.ajax({
        url: '<?=site_url('GestaoDeChips/operadoras/editarOperadora')?>',
        type: 'POST',
        data: { idOperadora: id, 
                nome: nome,
                status: status},
        dataType: 'json',
        success: function(data){
            if (data.status == 200){                  
                alert(data.dados.mensagem);
                $('#modalEditOperadora').modal('hide');
                    $.ajax({
                        url: '<?=site_url('GestaoDeChips/operadoras/listarOperadorasPorData')?>',
                        type: 'POST',
                        data: { dataInicial: '', 
                                dataFinal: ''},
                        dataType: 'json',
                        success: function(data){
                            if (data.status == 200){                   
                            tabelaOperadoras.clear().draw();
                            tabelaOperadoras.rows.add(data.results).draw();
                            }else{
                                alert(data.dados.mensagem);
                            }
                        },

                    });
                    $('#dataInicial').val("");
                    $('#dataFinal').val("");
            }else{
                if (data.dados.mensagem){
                    alert(data.dados.mensagem);
                }else{
                    alert("Erro ao editar operadora. Verifique os campos e tente novamente");
                }
            }
        },
        error: function(data){
            alert("Erro ao editar operadora");
        },
        complete: function(data){
            $('#btnSalvarEditOperadora')
                .html('Salvar')
                .attr('disabled', false);
        }

    });
})





    
</script>

<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>