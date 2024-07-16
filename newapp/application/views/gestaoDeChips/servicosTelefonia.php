
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

    td {
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
    #tabelaServicos th:nth-child(1),
    #tabelaServicos td:nth-child(1) {
        width: 200px;
    }

    .text-center {
        text-align: center;
    }

</style>

<h3><?=lang("servicosTelefonia")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('telecom')?> >
	<?=lang('servicosTelefonia')?>
</div>

<div id="modalEditarServicos" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formEditarServico">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("editar_servicoTelefonia")?></h3>
                </div>
                <div id="bodyModalEditarServicos" class="modal-body scrollModal">
                    <input id="idServicoEditar" type="text" hidden>
                    <div class="col-md-6 form-group">
                        <label class="control-label">Nome</label>
                        <input type="text" class="form-control" id="nomeEditar">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="control-label">Código do Serviço</label>
                        <input type="text" class="form-control" id="codServicoEditar">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="control">Status</label>
                        <select type="text" class="form-control input-sm" id="statusEditar">
                            <option value="Ativo">Ativo</option>
                            <option value="Inativo">Inativo</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="control-label">Fornecedor</label>
                        <select class="form-control" id="idFornecedorEditar"></select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                        <a class="btn btn-primary" id="btnSalvarEditServicos">Salvar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalCadServico" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadSetor">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("novo_servicoTelefonia")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Nome</label>
                                            <input type="text" class="form-control" id="nome" placeholder="Nome do Serviço">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Serviço da Operadora</label>
                                            <input type="text" class="form-control" id="servicoOperadora" placeholder="Descrição do Serviço">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Fornecedor</label>
                                            <select class="form-control" id="idFornecedor" ></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <a class="btn btn-primary" id="btnSalvarCadastroServico">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="alert alert-info col-md-12" style="margin-bottom: 50px;">    
    <div class ="col-md-12">
        <p class ="col-md-12">Informe os dados da pesquisa</p>
        <span class="help help-block"></span>
        <form action="" id="formPesquisa">
            <div id="pesquisa" class="col-md-4 form-group">
                <label>Nome: </label>
                <select class="form-control" id="pesqnome" name="nome" type="text" ></select>
            </div>
            <div id="fornecedorePesq" class="col-md-4 form-group">
                <label>Fornecedor: </label>
                <select class="form-control" id="pesqfornecedor" name="fornecedor" type="text"  ></select>
            </div>
            <div id="buttonPesquisar" class="col-md-2 form-group">
                <a class="btn btn-primary" id="pesquisacliente" type="submit" style="margin-top: 25px; margin-left: 30px; height: 30px">Pesquisar</a>
            </div>
        </form>
    </div>
</div>

<div class="container-fluid my-1">
    <div class="col-sm-12">
            <div class="row">
                <div class="container-fluid" id="tabelaGeral" hidden>
                    <br>
                        <a id="abrirModalInserir" class="btn btn-primary"><?=lang('novo_servicoTelefonia')?></a>
                    <table class="table-responsive table-bordered table" id="tabelaServicos" style="width: 100%">
                        <thead>
                            <th>Nome</th>
                            <th>Serviço Operadora</th>
                            <th>Fornecedor</th>
                            <th>Data de criação</th>
                            <th>Data de atualização</th>
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
<hr/>


<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>

<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>

<script>
    $.fn.dataTable.moment( 'DD/MM/YYYY, HH:mm:ss' );
    $.fn.dataTable.moment( 'DD/MM/YYYY' );

    var tabelaServicos = $('#tabelaServicos').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        language: lang.datatable,
        deferRender: true,
        lengthChange: false,
        order: [4, 'desc'],
        columnsDefs: [
            { 
                className: 'text-center',
                targets: '_all' 
            },
            {
                targets: [4,5],
                type: 'date-br'
            },
        ],
        columns: [
            { data: 'nome' },
            { data: 'servicoOperadora' },
            { data: 'idFornecedor' },
            { data: 'dataCadastro', render: function(data){return new Date(data).toLocaleString();} },
            { data: 'dataAtualizacao', render: function(data){return new Date(data).toLocaleString();} },
            { data: 'status' },
            {
				data:{'id':'id', 'status': 'status', 'nome' : 'nome', 
                    'servicoOperadora' : 'servicoOperadora', 
                    'idFornecedor' : 'idFornecedor'
                },
				orderable: false,
				render: function (data) {
					return `
					<button 
						class="btn fa fa-pencil-square-o"
						title="Editar Serviço"
						style="width: 38px; margin: 0 auto; background-color: #04acf4; color: white;"
						id="btnEditarSetor"
                        onClick="javascript:editServico(this,'${data['id']}',
                                                                '${data['nome']}',
                                                                '${data['servicoOperadora']}',
                                                                '${data['idFornecedor']}',
                                                                '${data['status']}'
                                                            )">
					</button>
                    <button
                        class="btn fa fa-exchange"
                        title="Alterar Status"
                        style="width: 38px; margin: 0 auto; background-color: ${data['status'] === 'Ativo' ? 'red' : 'green'}; color: white;"
                        id="btnAlterarStatusFornecedor"
                        onClick="javascript:alterarStatusServico(this,'${data['id']}', '${data['status']}')">
                    </button>
                    `;
				}
			}
        ]
    });
   
    $(document).ready(async function(){
        await povoarEmpresas();
    });

    $("#pesqnome").change(async function(){

        if($(this).val() !== 0){
            await povoarFornecedores()
        }
        

        $("#btnPesq").show();
    })

    async function povoarFornecedores(){
        $("#pesqfornecedor").empty()
        $("#pesqfornecedor").append("<option value='0'>Buscando Fornecedores...</option>")
        $("#pesqfornecedor").select2({
            width: '100%',
            placeholder: "Buscando fornecedores para essa empresa...",
            allowClear: true
        })

        let fornecedores = await buscarFornecedores()
        
        if(fornecedores) fornecedores = fornecedores.dados
        
        if(fornecedores.length){
            fornecedores.forEach(fornecedor => {
                $("#pesqfornecedor").append(`<option value="${fornecedor.id}">${fornecedor.text}</option>`)
            })
            $("#pesqfornecedor").find('option').get(0).remove()
            $("#pesqfornecedor").prepend(`<option selected disabled value="0">Selecione o fornecedor</option>`)
        } else {
            $("#pesqfornecedor").find('option').get(0).remove()
            $("#pesqfornecedor").prepend(`<option selected disabled value="0">Nenhum fornecedor encontrado</option>`)
            alert("Esta empresa não possui fornecedores cadastrados, selecione outra empresa.")
        }
    }

    async function povoarEmpresas(){
        $("#pesqnome").empty()
        $("#pesqnome").append("<option value='0'>Buscando Empresas...</option>")
        $("#pesqnome").select2({
            width: '100%',
            placeholder: "Buscando empresa...",
            allowClear: true
        })
        
        $("#pesqfornecedor").empty()
        $("#pesqfornecedor").append("<option value='0'>Esperando selecionar a Empresa...</option>")
        $("#pesqfornecedor").select2({
            width: '100%',
            placeholder: "Esperando selecionar a Empresa...",
            allowClear: true
        })


        let empresas = await buscarEmpresas()
        
        if(empresas) empresas = empresas.results
        
        if(empresas.length){
            empresas.forEach(empresa => {
                $("#pesqnome").append(`<option value="${empresa.id}">${empresa.text}</option>`)
            })
            $("#pesqnome").find('option').get(0).remove()
            $("#pesqnome").prepend(`<option selected disabled value="0">Selecione a empresa</option>`)
        } else {
            $("#pesqnome").find('option').get(0).remove()
            $("#pesqnome").prepend(`<option selected disabled value="0">Nenhuma empresa encontrada</option>`)
        }
    }

    async function povoarFornecedoresModal(id = ''){
        if(id != ''){
            $("#idFornecedorEditar").empty()
            $("#idFornecedorEditar").append("<option value='0'>Buscando Fornecedores...</option>")
            $("#idFornecedorEditar").select2({
                width: '100%',
                placeholder: "Buscando fornecedores para essa empresa...",
                allowClear: true
            })

            let fornecedores = await buscarFornecedores()
            
            if(fornecedores) fornecedores = fornecedores.dados
            
            if(fornecedores.length){
                fornecedores.forEach(fornecedor => {
                    $("#idFornecedorEditar").append(`<option value="${fornecedor.id}">${fornecedor.text}</option>`)
                })
                $("#idFornecedorEditar").val(id)
                
            } else {
                $("#idFornecedorEditar").find('option').get(0).remove()
                $("#idFornecedorEditar").prepend(`<option selected disabled value="0">Nenhum fornecedor encontrado</option>`)
                alert("Esta empresa não possui fornecedores cadastrados, selecione outra empresa.")
            }
        } else {
            $("#idFornecedor").empty()
            $("#idFornecedor").append("<option value='0'>Buscando Fornecedores...</option>")
            $("#idFornecedor").select2({
                width: '100%',
                placeholder: "Buscando fornecedores para essa empresa...",
                allowClear: true
            })

            let fornecedores = await buscarFornecedores()
            
            if(fornecedores) fornecedores = fornecedores.dados
            
            if(fornecedores.length){
                fornecedores.forEach(fornecedor => {
                    $("#idFornecedor").append(`<option value="${fornecedor.id}">${fornecedor.text}</option>`)
                })
                $("#idFornecedor").prepend(`<option selected disabled value="0">Selecione o fornecedor</option>`)
                
            } else {
                $("#idFornecedor").find('option').get(0).remove()
                $("#idFornecedor").prepend(`<option selected disabled value="0">Nenhum fornecedor encontrado</option>`)
                alert("Esta empresa não possui fornecedores cadastrados, selecione outra empresa.")
            }
        } 
    }

    async function buscarFornecedores(){
        let idEmpresa = $("#pesqnome").val()
        let fornecedores = await $.ajax({
            url: '<?= site_url('gestaoDeChips/servicosTelefonia/selectFornecedores') ?>',
            type: 'POST',
            data: {idEmpresa: idEmpresa},
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    return data.dados
                }else{
                    return false
                }
            }
        })
        return fornecedores
    }

    async function buscarEmpresas(){
        
        let empresas = await $.ajax({
            url: '<?= site_url('setores/buscarEmpresas_ajax') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    return data.results
                }else{
                    return false
                }
            }
        })
        return empresas
    }

    $('#pesquisacliente').click(function(e){
        // Carregando
        $('#pesquisacliente')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        e.preventDefault();
        var nome = $('#pesqfornecedor').val();
        $.ajax({
            url: '<?= site_url('gestaoDeChips/servicosTelefonia/buscarServicos') ?>',
            type: 'POST',
            data: {idFornecedor: nome},
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    tabelaServicos.clear().draw();
                    tabelaServicos.rows.add(data.dados).draw();
                $('#tabelaGeral').show();
                $('#pesquisacliente')
                    .html('Pesquisar')
                    .attr('disabled', false);
                }else{
                    alert('Não existem Serviços de Telefonia para essa empresa')
                    $('#pesquisacliente')
                    .html('Pesquisar')
                    .attr('disabled', false);
                    tabelaServicos.clear().draw();

                }
            }
        })
    })

    async function editServico(botao, id, nome, servico, fornecedor, status){
        $('#modalEditarServicos').modal('show');
        
        $('#idServicoEditar').val(id);
        $('#nomeEditar').val(nome);
        $('#codServicoEditar').val(servico);
        $('#statusEditar').val(status);
        
        await povoarFornecedoresModal(fornecedor);

        $('#idFornecedorEditar').val(fornecedor);
    }

    $('#btnSalvarEditServicos').click(event=>{
        var idFornecedor    = $('#idFornecedorEditar').val();
        var nome            = $('#nomeEditar').val();
        var codServico      = $('#codServicoEditar').val();
        var status          = $('#statusEditar').val();
        var idServico       = $('#idServicoEditar').val();

        if(idFornecedor != $("#pesqfornecedor").val()){
            $("#pesqfornecedor").val(idFornecedor)
            $("#pesqfornecedor").trigger('change')
        }

        if (status == 'Ativo'){
            status = 1;
        }else{
            status = 0;
        }
        $.ajax({
            url: '<?= site_url('GestaoDeChips/servicosTelefonia/editarServicos') ?>',
            type: 'POST',
            data: { idFornecedor, 
                    nome, 
                    status,
                    codServico,
                    idServico
                    },
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    alert("Serviço editado com sucesso!")
                    
                    $('#modalEditarServicos').modal('hide');
                    $('#idFornecedorEditar').empty();
                    $('#nomeEditar').val('');
                    $('#codServicoEditar').val('');
                    $('#statusEditar').val('');
                    $('#idServicoEditar').val('');
                    

                    $.ajax({
                        url: '<?= site_url('GestaoDeChips/servicosTelefonia/buscarServicos') ?>',
                        type: 'POST',
                        data: {
                            idFornecedor
                        },
                        dataType: 'json',
                        success: function(data){
                            if (data.status === 200){
                            tabelaServicos.clear().draw();
                            tabelaServicos.rows.add(data.dados).draw();

                            }else{
                                alert('Não existem Serviços de Telefonia para essa empresa')
                                tabelaServicos.clear().draw();
                            }
                        }
                    })
                }else{
                    alert("Não foi possível editar o serviço. Tente Novamente.")
                }
            }
        })
    })
                        
    $('#abrirModalInserir').click(async function() {
        $('#modalCadServico').modal('show')
        $('#nome').val('');
        $('#servicoOperadora').val('');
        
        await povoarFornecedoresModal();
        
    })
    
    $('#btnSalvarCadastroServico').click(event=>{
        var nome = $('#nome').val();
        var servicoOperadora = $('#servicoOperadora').val();
        var idFornecedor = $('#idFornecedor').val();

        if(idFornecedor != $("#pesqfornecedor").val()){
            $("#pesqfornecedor").val(idFornecedor)
            $("#pesqfornecedor").trigger('change')
        }
        
        $.ajax({
            url: '<?= site_url('GestaoDeChips/servicosTelefonia/inserirFornecedor') ?>',
            type: 'POST',
            data: { nome,
                    servicoOperadora,
                    idFornecedor        
            },
            dataType: 'json',
            success: function(data){
                if (data.status === 201){
                    alert("Cadastro realizado com sucesso!");

                    $('#modalCadServico').modal('hide');
                    $('#nome').val('');
                    $('#servicoOperadora').val('');
                    $("#idFornecedor").empty('');

                    $.ajax({
                        url: '<?= site_url('GestaoDeChips/servicosTelefonia/buscarServicos') ?>',
                        type: 'POST',
                        data: {idFornecedor},
                        dataType: 'json',
                        success: function(data){
                            if (data.status === 200){
                                tabelaServicos.clear().draw();
                                tabelaServicos.rows.add(data.dados).draw();
                            $('#tabelaGeral').show();
                            $('#alert').hide();
                            $('#pesquisacliente')
                                .html('Pesquisar')
                                .attr('disabled', false);
                            }else{
                                alert('Não existem Serviços para esse fornecedor')
                                $('#pesquisacliente')
                                .html('Pesquisar')
                                .attr('disabled', false);
                            }
                        }
                    })
                }else if(data.status === 400 || data.status === 404){
                    alert("Não foi possível realizar o cadastro. Tente novamente");
                } else {
                    alert('Preencha os campos.');
                }
            }
        })
    })

    $('#cepTransportadorEditar').mask('00000-000');
    
    $(document).ready(function() {
        $('#cpfCnpjTransportadorEditar').inputmask({  
        mask: ['999.999.999-99', '99.999.999/9999-99'],
        keepStatic: true
        });
    });
    
    
    $("#cepTransportadorEditar").blur(function(){
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#ruaTransportadorEditar").val(dadosRetorno.logradouro);
				$("#bairroTransportadorEditar").val(dadosRetorno.bairro);
				$("#cidadeTransportadorEditar").val(dadosRetorno.localidade);
				$("#ufTransportadorEditar").val(dadosRetorno.uf);
            }catch(ex){
            }
        })
        
    })

    $('#cepTransportador').mask('00000-000');
    
    $(document).ready(function() {
        $('#cpfCnpjTransportador').inputmask({  
        mask: ['999.999.999-99', '99.999.999/9999-99'],
        keepStatic: true
        });
    });

    $("#cepTransportador").blur(function(){
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#ruaTransportador").val(dadosRetorno.logradouro);
				$("#bairroTransportador").val(dadosRetorno.bairro);
				$("#cidadeTransportador").val(dadosRetorno.localidade);
				$("#ufTransportador").val(dadosRetorno.uf);
			}catch(ex){}
		});
		
	});

    function alterarStatusServico(botao,id,status){
        if(confirm('Clique em OK para confirmar a alteração de status ou em Cancelar para voltar')){
            var idFornecedor = $('#pesqfornecedor').val();
            if(status == 'Ativo'){
                status = 0
            }else{
                status = 1
            }
            $.ajax({
                url: '<?= site_url('GestaoDeChips/servicosTelefonia/alterarStatusServico') ?>',
                type: 'POST',
                data: {idServico: id,
                        status: status},
                dataType: 'json',
                success: function(data){
                    if (data.status === 200){
                        alert("Status alterado com sucesso!");

                        $.ajax({
                            url: '<?= site_url('GestaoDeChips/servicosTelefonia/buscarServicos') ?>',
                            type: 'POST',
                            data: {idFornecedor},
                            dataType: 'json',
                            success: function(data){
                                if (data.status === 200){
                                    tabelaServicos.clear().draw();
                                    tabelaServicos.rows.add(data.dados).draw();
                                }else{
                                    alert('Não existem serviços para esse fornecedor.')
                                }
                            }
                        })
                    }else{
                        alert("Não foi possível alterar o status. Tente novamente.")
                    }
                }
            })
        }else{
            return false;
        }
    }
</script>   

<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>
