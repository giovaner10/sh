
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
</style>

<h3><?=lang("pagamentos")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a>  
	<?=lang('pagamentos')?>
</div>


<div id="modalCad" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCad">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("novo_pagamento")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                            
                                    <div class="row">
                                        <div id="select-empresa" class="col-md-12">
                                            <label>Empresa</label>
                                            <select class="form-control" name="empresa" id="empresa" style="width: 100%">
                                            </select>
                                        </div>
                                        <div id="nome-empresa" style="display: none" class="col-md-12">
                                            <label>Empresa</label><br>
                                            <select id="select-empresa-nome" disabled class="form-control">
                                                <option id="empresa-nome" value="" selected disabled></option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div id ="divBuscarPor"class="col-md-3 col-sm-2">
                                            <label>Pesquisar por:</label>
                                            <select class="form-control" name="buscar" id="tipo-busca" style="height: 28px;">
                                                <option value="0">Id</option>
                                                <option value="1">Nome</option>
                                                <option value="2">Documento</option>
                                            </select>
                                        </div>

                                        <div id ="divNomeCliente" class="col-md-9 col-sm-6">
                                            <label>Cliente:</label>
                                            <select class="form-control" style="width: 100%" name="cliente" id="cliente" type="text">
                                            </select>
                                        </div>
                                        <div id ="divDocCliente" class="col-md-7 col-sm-6" hidden>
                                            <label>Cliente:</label>
                                            <input class="form-control" style="width: 100%; height: 100%" name="clienteDoc" id="clienteDoc" type="text" placeholder="Digite o CPF/CNPJ do cliente">
                                            </input>
                                        </div>
                                        <div id ="divIdCliente" class="col-md-7 col-sm-6" >
                                            <label>Cliente:</label>
                                            <input class="form-control" style="width: 100%; height: 100%" name="clienteId" id="clienteId" type="text" placeholder="Digite o ID do cliente">
                                            </input>
                                        </div>

                                        <div id ="divBtnPesquisaCliente" class="col-md-2 col-sm-2" style="display: contents;" hidden>
                                            <button id="btnPesquisaClienteDoc" class="btn btn-primary" type="button" style="margin-top: 24px;">Buscar</button>
                                            <button id="btnLimparPesquisaClienteDoc" class="btn btn-danger" type="button" style="margin-top: 24px;">Limpar</button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control">Prazo</label>
                                            <input type="number" class="form-control" id="prazo" placeholder="Digite o prazo em dias">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <a class="btn btn-primary" id="btnSalvarCadastro">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalEdit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formEditar">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("editar_pagamento")?></h3>
                </div>
                <div id="bodyModalEditar" class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div hidden>
                                            <label>Id</label>
                                            <input type="text" class="form-control" id="idEditar" disabled>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="control-label">Empresa</label>
                                            <input type="text" class="form-control" id="empresaEditar" disabled>
                                            <input type="hidden" id="empresaID">
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="control-label">Cliente</label>
                                            <input type="text" class="form-control" id="clienteEditar" disabled>
                                            <input type="hidden" id="clienteIDEditar">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control">Prazo</label>
                                            <input type="number" class="form-control" id="prazoEditar" placeholder="Digite o prazo em dias">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Status</label>
                                            <select type="text" class="form-control input-sm" id="statusEditar">
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
                    <a class="btn btn-primary" id="btnSalvarEdit">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<hr/>

<div id="formasPagamentos" class="tab-pane fade in active" style="margin-top: 20px">
    <div class="container-fluid" id="tabelaGeral">
        <a id="abrirModalInserir" class="btn btn-primary">Nova Forma de Pagamento</a>
        <table class="table table-responsive table-bordered" id="tabelaFormasPagamentos">
            <thead>
                <th>ID</th>
                <th>Empresa</th>
                <th>Cliente</th>
                <th>Prazo</th>
                <th>Status</th>
                <th>Ações</th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>

<script>
    var tabelaPagamentos = $('#tabelaFormasPagamentos').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhuma empreasa a ser listada",
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
        ajax:{
            url: '<?= site_url('FormaPagamento/BuscarFormas') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    tabelaPagamentos.clear().draw();
                    tabelaPagamentos.rows.add(data.results).draw();
                }
            },
            error:function(data){
                console.error(data)
            },
        },
        columns: [
            { data: 'id' },
            { data: 'nomeEmpresa' },
            { data: 'nomeCliente' },
            { data: 'tempo' },
            { data: 'status' },
            {
				data: null,
				orderable: false,
				render: function (data) {
					return `
					<button 
						class="btn fa fa-pencil-square-o"
						title="Editar Pagamento"
						style="width: 38px; margin: 0 auto; background-color: #04acf4; color: white;"
						id="btnEditar"
                        onClick="javascript:editPagamento(this,'${data['id']}', '${data['nomeEmpresa']}', '${data['nomeCliente']}', '${data['idEmpresa']}', '${data['idCliente']}', '${data['tempo']}', '${data['status']}')">
					</button>
                    <button
                        class="btn fa fa-exchange"
                        title="Alterar Status"
                        style="width: 38px; margin: 0 auto; background-color: ${data['status'] === 'Ativo' ? 'red' : 'green'}; color: white;"
                        id="btnAlterarStatus"
                        onClick="javascript:alterarStatus(this,'${data['id']}', '${data['status']}')">
                    </button>
                    `;
				}
			}
        ]
    });
    
    $('#abrirModalInserir').click( async event=> {
        $('#modalCad').modal('show')
        // $('#empresa').val(0)
        $('#cliente').val(0)
        $('#prazo').val("")
        $('#tipo-busca').val(2).trigger('change'); 

        if ($('#pesqnome').val() != null) {
            await listarEmpresasCad();
            $('#empresa').val($('#pesqnome').val()).removeAttr('disabled').trigger('change');
        }else{
            $('#empresa').empty().removeAttr('disabled');
            $("#empresa").select2({
                ajax: {
                    url: '<?= site_url('FormaPagamento/buscarEmpresas') ?>',
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
    })
    
    $('#btnSalvarCadastro').click(event=> {

        var empresa = $('#empresa').val();
        var cliente = $('#cliente').val();
        var prazo = $('#prazo').val();
    
        var botao = $('#btnSalvarCadastro');
        var htmlBotao = botao.html();
        botao.html(ICONS.spinner); // adiciona o ícone de spinner ao botão
        botao.attr('disabled', true);

        if (cliente == null) {
            cliente = idClienteDoc;
        }

        $.ajax({
            url: '<?= site_url('FormaPagamento/adicionarForma') ?>',
            type: 'POST',
            data: { 
                empresa: empresa,
                cliente: cliente,
                prazo: prazo,
            },
            dataType: 'json',
            success: function(data){
                botao.html(htmlBotao); // retorna o texto original do botão
                botao.attr('disabled', false);
                if (data.status === 200){
                    alert(data.results.Mensagem)
                    $('#modalCad').modal('hide')
                    $('#empresa').val(0)
                    $('#cliente').val(0)
                    $('#prazo').val("")
                    tabelaPagamentos.ajax.reload();
                }
                else if(data.status === 400 && data.results.Mensagem){
                    alert(data.results.Mensagem)
                }else{
                    alert('Preencha os campos')
                }
            },
            error: function (){
                botao.html(htmlBotao); // retorna o texto original do botão
                botao.attr('disabled', false);
            }
        })
        
    })

    function editPagamento(botao, id, empresa, cliente, empresaID, clienteID, prazo, status){
        $('#modalEdit').modal('show');
        $('#idEditar').val(id);
        $('#statusEditar').val(status);
        $('#empresaID').val(empresaID);
        $('#clienteIDEditar').val(clienteID);
        $('#empresaEditar').val(empresa);
        $('#clienteEditar').val(cliente);
        $('#prazoEditar').val(prazo);
    }   

    $('#btnSalvarEdit').click(event=> {
        var id = $('#idEditar').val();
        var status = $('#statusEditar').val();
        var empresa = $('#empresaID').val();
        var cliente = $('#clienteIDEditar').val();
        var prazo = $('#prazoEditar').val();

        
        var botao = $('#btnSalvarEdit');
        var htmlBotao = botao.html();
        botao.html(ICONS.spinner); // adiciona o ícone de spinner ao botão
        botao.attr('disabled', true);

        if(status == 'Ativo'){
            status = 1
        }else{
            status = 0
        }

        $.ajax({
            url: '<?= site_url('FormaPagamento/editarForma') ?>',
            type: 'POST',
            data: { 
                id: id,
                empresa: empresa,
                cliente: cliente,
                prazo: prazo,
                status: status,
            },
            dataType: 'json',
            success: function(data){
                botao.html(htmlBotao); // retorna o texto original do botão
                botao.attr('disabled', false);
                if (data.status === 200){
                    alert(data.results.Mensagem)
                    $('#modalEdit').modal('hide')
                    $('#prazoEditar').val('');
                    $('#clienteEditar').val('');
                    $('#empresaID').val('');
                    $('#clienteIDEditar').val('');
                    $('#empresaEditar').val('');
                    $('#statusEditar').val('');
                    $('#idEditar').val('');

                    tabelaPagamentos.ajax.reload();
    
                }else if(data.status === 400 && data.results.Mensagem){
                    alert(data.results.Mensagem)
                }else{
                    alert('Dados inválidos')
                }
            },
            error: function (){
                botao.html(htmlBotao); // retorna o texto original do botão
                botao.attr('disabled', false);
            }
        })
    })

    function alterarStatus(botao, id, status){
        if(confirm('Clique em OK para confirmar a alteração de status ou em Cancelar para voltar')){
            
            if(status == 'Ativo'){
                status = 0
            }else{
                status = 1
            }

            $.ajax({
                url: '<?= site_url('FormaPagamento/alterarStatus') ?>',
                type: 'POST',
                data: { 
                    id: id,
                    status: status
                },
                dataType: 'json',
                success: function(data){
                    if (data.status === 200){
                        alert(data.results.Mensagem)
                        tabelaPagamentos.ajax.reload();
                    }else{
                        alert(data.results.Mensagem)
                    }
                }
            })
        }else{
            return false;
        }
    }

    var ICONS = {
        spinner: '<i class="fa fa-spinner fa-spin"></i>',
        success: '<i class="fa fa-check-circle"></i>',
        error: '<i class="fa fa-times-circle"></i>'
    };

    // $('#empresa').change(event=> {
    //     var empresa = $(this).val();
    //     $.ajax({
    //         url: '<?= site_url('FormaPagamento/BuscarClientes') ?>'+'?empresaID='+empresa,
    //         type: 'GET',
    //         dataType: 'json',
    //         success: function(data){
    //             // if (data.status === 200){
    //             //     alert(data.dados.Mensagem)
    //             //     $('#modalEdit').modal('hide')
    //             //     $('#prazoEditar').val('');
    //             //     $('#clienteEditar').val('');
    //             //     $('#clienteIDEditar').val('');
    //             //     $('#empresaEditar').val('');
    //             //     $('#statusEditar').val('');
    //             //     $('#idEditar').val('');

    //             //     tabelaPagamentos.ajax.reload();
    
    //             // }else if(data.status === 400 && data.dados.Mensagem){
    //             //     alert(data.dados.Mensagem)
    //             // }else{
    //             //     alert('Dados inválidos')
    //             // }
    //         }
    //     })
    // })

    $('#modalCad').on('hide.bs.modal', async function (e) {
        $("#empresa").val(0).removeAttr('disabled')
        $("#empresa").text("Selecione a Empresa")

        $("#nome-empresa").attr("style", "display: none")
        $("#select-empresa").attr("style", "display: block")
    })
    
    async function listarEmpresasCad(){
        let empresasCad = await $.ajax ({
            url: '<?= site_url('FormaPagamento/buscarEmpresas') ?>',
            dataType: 'json',
            type: 'GET',  
            success: function(data){
                return data;
            }           
        })

        $('#empresa').select2({
            placeholder: "Selecione a empresa",
            allowClear: true,
            language: "pt-BR",

        });

        if (empresasCad) {
            empresasCad.results.forEach(empresa => {
                $('#empresa').append(`<option value="${empresa.id}">${empresa.text}</option>`)
            })
        }
    }

    
    $('#tipo-busca').change(function(){     
        if ($(this).val() == 1){
            $('#divDocCliente').hide();
            $('#divBtnPesquisaCliente').hide();
            $('#divIdCliente').hide();
            $("#divNomeCliente").show();
            $("#cliente").select2({
                ajax: {
                    url: '<?= site_url('FormaPagamento/BuscarClientes') ?>',
                    dataType: 'json',
                    delay: 1000,
                    type: 'GET',
                    data: function (params) {
                        return {
                            q: params.term,
                            tipoBusca: 'nome'
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
            $("#divNomeCliente").hide();
            $('#divIdCliente').hide();
            $('#divBtnPesquisaCliente').show();
            $('#divDocCliente').show();

            $("#clienteDoc").inputmask({
            mask: ["999.999.999-99", "99.999.999/9999-99"],
            keepStatic: true,
            placeholder: " ",
            });
        }else if($(this).val() == 0){
            $("#divNomeCliente").hide();
            $('#divBtnPesquisaCliente').show();
            $('#divDocCliente').hide();
            $('#divIdCliente').show();
        }
    })
    
    var idClienteDoc = '';

    $('#btnPesquisaClienteDoc').click(function(){
        if ($('#divDocCliente').is(':visible')){
            $('#clienteDoc').attr('disabled', true)
            $('#btnPesquisaClienteDoc')
            .html('<i class="fa fa-spinner fa-spin"></i>')
            .attr('disabled', true)

            var documento = $('#clienteDoc').val();
            if (documento != ''){
                $.ajax({
                    url: '<?= site_url('FormaPagamento/BuscarClientes') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {q: documento,
                        tipoBusca: 'cpfCnpj'},
                    success: function(data){
                        if(data.results.length){
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#clienteDoc').attr('disabled', false)
                            $('#clienteDoc').inputmask('remove')
                            $('#clienteDoc').val(data.results[0].text)
                            $('#clienteDoc').attr('disabled', true)
                            idClienteDoc = data.results[0].id
                        }else{
                            alert('Cliente não ativo ou não encontrado')
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#clienteDoc').val('')
                            $('#clienteDoc').attr('disabled', false)
                        }
                    },
                    error: function(data){
                        console.error(data)
                    },
                })
            }else{
                alert('Digite o cpf ou cnpj do cliente')
                $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                $('#clienteDoc').attr('disabled', false)

            }
        }else{
            $('#btnPesquisaClienteDoc')
            .html('<i class="fa fa-spinner fa-spin"></i>')
            .attr('disabled', true)
            var id = $('#clienteId').val();
            if (id != ''){
                $.ajax({
                    url: '<?= site_url('FormaPagamento/BuscarClientes') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {q: id,
                        tipoBusca: 'id'},
                    success: function(data){
                        if(data.results.length){
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#clienteId').attr('disabled', false)
                            $('#clienteId').val(data.results[0].text)
                            $('#clienteId').attr('disabled', true)
                            idClienteDoc = data.results[0].id
                        }else{
                            alert('Cliente não ativo ou não encontrado')
                            $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                            $('#clienteId').val('')
                            $('#clienteId').attr('disabled', false)
                        }
                    },
                    error: function(data){
                        console.error(data)
                    },
                })
            }else{
                alert('Digite o id do cliente')
                $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                $('#clienteId').attr('disabled', false)
            }
        }
    })

    $('#btnLimparPesquisaClienteDoc').click(function(){
        if ($('#divDocCliente').is(':visible')){
            $('#clienteDoc').val('')
            $('#clienteDoc').attr('disabled', false)
            $("#clienteDoc").inputmask({
                mask: ["999.999.999-99", "99.999.999/9999-99"],
                keepStatic: true,
                placeholder: " ",
                });
        }else{
            $('#clienteId').val('')
            $('#clienteId').attr('disabled', false)
        }
        idClienteDoc = '';
    })

</script>   

<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>
