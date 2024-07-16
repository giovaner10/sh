
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

<h3><?=lang("tipo_movimento")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('logistica')?> >
	<?=lang('tipo_movimento')?>
</div>


<div id="modalEditTipoMovimento" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formEditTipoMovimento">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("editar_tipo_movimento")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group" hidden>
                                            <label>Id</label>
                                            <input type="text" class="form-control" id="idTipoMovimentoEdit" disabled>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control">Nome</label>
                                            <input type="text" class="form-control" id="nomeTipoMovimentoEdit" style="height: 100%;">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Empresa: </label>
       			                            <select class="form-control input-sm" id="selectEmpresaEdit" name="nomeEmpresa" type="text" style="width: 100%;"></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control">Status</label>
                                            <select type="text" class="form-control input-sm" id="statusTipoMovimentoEdit">
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

                    <a class="btn btn-primary" id="btnSalvarEdicaoTipoMovimento">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modalCadTipoMovimento" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadTipoMovimento">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("novo_tipo_movimento")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Nome</label>
                                            <input type="text" class="form-control" id="nomeTipoMovimento" placeholder="Digite o nome do movimento" style="height: 100%;">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Empresa: </label>
       			                            <select class="form-control input-sm" id="selectEmpresaCad" name="nome" type="text" style="width: 100%;"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>

                    <a class="btn btn-primary" id="btnSalvarCadastroTipoMovimento">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid my-1">
	<div class="col-sm-12">
            <form id="formPesquisa">
   			    <div class="row alert alert-info">
                    <h5 style="margin-left: 15px;">Informe a empresa a ser pesquisada</h5>

   			        <div id="pesquisa" class="col-md-4">
   			            <label>Nome: </label>
   			            <select class="form-control input-sm" id="pesqnome" name="nome" type="text" style="width: 100%"></select>
   			        </div>
			    	<div id="buttonPesquisar" class="col-md-1">
			    		<button class="btn btn-primary" id="pesquisacliente" type="submit" style="margin-top: 22px;">Pesquisar</button>
			    	</div>
   			    </div>
		    </form>
        <div id="empresas" class="tab-pane fade in active" style="margin-top: 20px">
            <div class="container-fluid" id="tabelaGeral">
		        	<a id="abrirModalCadastrar" class="btn btn-primary"><?=lang("novo_tipo_movimento")?></a>
                <table class="table-responsive table-bordered table" id="tabelaTipoMovimento" style="text-align: center;">
                    <thead>
                        <tr class="tableheader">
                        <th hidden>ID</th>
                        <th>Empresa</th>
                        <th>Tipo de Movimento</th>
                        <th>Data de Criação</th>
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
<hr/>


<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    var tabelaTipoMovimento = $('#tabelaTipoMovimento').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: {
           loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
           searchPlaceholder:  'Pesquisar',
           emptyTable:         "Nenhum tipo de movimento a ser listado",
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
            url: '<?= site_url('tipoMovimento/listarTiposdeMovimento') ?>',
            type: 'POST',
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    tabelaTipoMovimento.clear().draw();
                    tabelaTipoMovimento.rows.add(data.dados).draw();

                }else{
                    tabelaTipoMovimento.clear().draw();
                }
            },
            error: function(){
                alert('Erro ao listar os tipos de movimento');
                tabelaTipoMovimento.clear().draw();
            }
        },
        columns: [
            { data: 'id' ,
                visible: false},
            { data: 'nomeEmpresa'},
            { data: 'nomeTipoMovimento' },
            { data: 'dataCadastro', render: function(data){return new Date(data).toLocaleString();} },
            { data: 'dataAtualizacao', render: function(data){return new Date(data).toLocaleString();} },
            {
				data:{'id':'id', 'nomeTipoMovimento': 'nomeTipoMovimento'},
				orderable: false,
				render: function (data) {
					return `
					<button 
						class="btn btn-primary"
						title="Editar Tipo de Movimento"
						style="width: 42px; margin: 0 auto;"
						id="btnEditarTipoMovimento"
                        onClick="javascript:editarTMovimento(this,'${data['id']}','${data['nomeTipoMovimento']}', '${data['idEmpresa']}')">
						<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
					</button>
                    `;
				}
			}
        ]
    });
   
    $(document).ready(function(){
        $('#pesqnome').select2({
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

    $('#pesquisacliente').click(function(e){
        
        e.preventDefault();
        var nome = $('#pesqnome').val();
        if(!nome){
            alert('Selecione uma empresa')
            return;
        }
        
        // Carregando
        $('#pesquisacliente')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        $.ajax({
            url: '<?= site_url('tipoMovimento/buscarTipoMovimento') ?>',
            type: 'POST',
            data: {idEmpresa: nome},
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                tabelaTipoMovimento.clear().draw();
                tabelaTipoMovimento.rows.add(data.dados).draw();
                $('#tabelaGeral').show();
                $('#alert').hide();
                $('#pesquisacliente')
                    .html('Pesquisar')
                    .attr('disabled', false);
                }else{
                    alert('Não existem tipos de movimentos para essa empresa')
                    $('#pesquisacliente')
                    .html('Pesquisar')
                    .attr('disabled', false);
                    tabelaTipoMovimento.clear().draw();

                }
            }
        })
    })

    $('#abrirModalCadastrar').click(function(){
        $('#modalCadTipoMovimento').modal('show');
    })

    $(document).ready(function(){
        $('#selectEmpresaCad').select2({
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

    $('#btnSalvarCadastroTipoMovimento').click(event=> {
        var nomeTipoMovimento = $('#nomeTipoMovimento').val();
        var idEmpresa = $('#selectEmpresaCad').val();
        if (nomeTipoMovimento == ''){
            alert('Preencha o campo nome do tipo de movimento')
            return false;
        }else if(idEmpresa == null){
            alert('Selecione uma empresa')
            return false;
        }else{
            botao = $('#btnSalvarCadastroTipoMovimento');
            botao.html('<i class="fa fa-spin fa-spinner"></i> <?=lang('Salvando')?>');
            botao.attr('disabled', true);
            $.ajax({
                url: '<?= site_url('tipoMovimento/inserirTipoMovimento') ?>',
                type: 'POST',
                data: { idEmpresa: idEmpresa,
                        nome: nomeTipoMovimento},
                dataType: 'json',
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        $('#nomeTipoMovimento').val('').trigger('change');
                        $('#modalCadTipoMovimento').modal('hide');
                        $('#pesqnome').val('').trigger('change');
                        botao.html('Salvar');
                        botao.attr('disabled', false);

                        $.ajax({
                            url: '<?= site_url('tipoMovimento/buscarTipoMovimento') ?>',
                            type: 'POST',
                            data: {idEmpresa: idEmpresa},
                            dataType: 'json',
                            success: function(data){
                                if (data.status === 200){
                                tabelaTipoMovimento.clear().draw();
                                tabelaTipoMovimento.rows.add(data.dados).draw();
                                $('#tabelaGeral').show();
                                $('#alert').hide();
                                }else{
                                    alert('Não existem tipos de movimentos para essa empresa')
                                    tabelaTipoMovimento.clear().draw();                   
                                }
                            }
                        })
                        $('#selectEmpresaCad').val('').trigger('change');
                    }else if (data.status === 400){
                        alert('Verifique os campos e tente novamente.')
                        botao.html('Salvar');
                        botao.attr('disabled', false);
                    }else{
                        alert(data.dados.mensagem)
                        botao.html('Salvar');
                        botao.attr('disabled', false);
                    }
                },
                error: function(){
                    alert('Erro ao inserir o tipo de movimento. Tente novamente.')
                    botao.html('Salvar');
                    botao.attr('disabled', false);
                }
            })
        }
    })

    async function editarTMovimento(botao,id,status,nome,idEmpresa){
        botao = $(botao)
        botao.html('<i class="fa fa-spin fa-spinner"></i>');
        let empresas = await $.ajax({
                                url: '<?= site_url('setores/buscarEmpresas') ?>',
    	                        dataType: 'json',
    	                        type: 'GET',  
    	                        data: function (params) {
                                    return {
                                        q: params.term
                                    };
                                },         
    	                    });

        $("#selectEmpresaEdit").select2({
            data: empresas.results,
            placeholder: "Selecione a empresa",
            allowClear: true,
            language: "pt-BR",
        });

        $('#selectEmpresaEdit').on('select2:select', function (e) {
            var data = e.params.data;
        });
        
        $('#idTipoMovimentoEdit').val(id);
        $('#nomeTipoMovimentoEdit').val(nome);
        $('#statusTipoMovimentoEdit').val(status);
        $('#selectEmpresaEdit').val(idEmpresa).trigger('change');
        botao.html('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>');
        $('#modalEditTipoMovimento').modal('show');
    }

    $('#btnSalvarEdicaoTipoMovimento').click(event=> {
        botao = $('#btnSalvarEdicaoTipoMovimento');
        botao.html('<i class="fa fa-spin fa-spinner"></i> <?=lang('Salvando')?>');
        botao.attr('disabled', true);
        var nomeTipoMovimento = $('#nomeTipoMovimentoEdit').val();
        var idTipoMovimento = $('#idTipoMovimentoEdit').val();
        var status = $('#statusTipoMovimentoEdit').val();
        var idEmpresa = $('#selectEmpresaEdit').val();

        if(status == 'Ativo'){
            status = 1;
        }else{
            status = 0;
        }
        $.ajax({
            url: '<?= site_url('tipoMovimento/editarTipoMovimento') ?>',
            type: 'POST',
            data: { nome: nomeTipoMovimento,
                    idTMovimento: idTipoMovimento,
                    status: status,
                    idEmpresa: idEmpresa},
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    alert(data.dados.mensagem)
                    $('#modalEditTipoMovimento').modal('hide')
                    $('#idTipoMovimentoEdit').val('')
                    $('#nomeTipoMovimentoEdit').val('')
                    $('#statusTipoMovimentoEdit').val('')
                    botao.html('Salvar');
                    botao.attr('disabled', false);

                    var nome = $('#pesqnome').val();
                    if (nome == null){
                        location.reload();
                    }else{
                        $.ajax({
                            url: '<?= site_url('tipoMovimento/buscarTipoMovimento') ?>',
                            type: 'POST',
                            data: {idEmpresa: nome},
                            dataType: 'json',
                            success: function(data){
                                if (data.status === 200){
                                tabelaTipoMovimento.clear().draw();
                                tabelaTipoMovimento.rows.add(data.dados).draw();
                                $('#tabelaGeral').show();
                                $('#alert').hide();
                                }else{
                                    alert('Não existem tipos de movimentos para essa empresa')
                                    tabelaTipoMovimento.clear().draw();
                                
                                }
                            }
                        })
                    }
                }else if (data.status === 400){
                    alert('Verifique os campos e tente novamente')
                    botao.html('Salvar')
                    botao.attr('disabled', false);
                }else{
                    alert(data.dados.mensagem)
                    botao.html('Salvar');
                    botao.attr('disabled', false);
                } 
            },
            error: function(){
                alert('Erro ao editar o tipo de movimento. Tente novamente.')
                botao.html('Salvar');
                botao.attr('disabled', false);
            }
        })
    })



</script>   

<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>