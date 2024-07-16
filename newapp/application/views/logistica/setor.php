
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

<h3><?=lang("setores")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('logistica')?> >
	<?=lang('setores')?>
</div>


<div id="modalCadSetor" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadSetor">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("novo_setor")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Nome</label>
                                            <input type="text" class="form-control" id="nomeSetor" placeholder="Digite o nome do setor" style="height: 100%;">
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
                    <a class="btn btn-primary" id="btnSalvarCadastroSetor">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modalEditSetor" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formEditarSetor">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("editar_setor")?></h3>
                </div>
                <div id="bodyModalEditarSetor" class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group" hidden>
                                            <label>Id</label>
                                            <input type="text" class="form-control" id="idSetorAlterar" disabled>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control">Nome</label>
                                            <input type="text" class="form-control" id="nomeSetorAlterar" style="height: 100%;">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Empresa: </label>
       			                            <select class="form-control input-sm" id="selectEmpresaEdit" name="nomeEmpresa" type="text" style="width: 100%;"></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control">Status</label>
                                            <select type="text" class="form-control input-sm" id="statusSetorAlterar">
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
                    <a class="btn btn-primary" id="btnSalvarEditSetor">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>
            

	<div class="container-fluid my-1">
		<div class="col-sm-12">
                <div id ="alert" class="alert alert-info">Informe a empresa a ser pesquisada</div>
                <form id="formPesquisa">
       			    <div class="row">
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
                        <a id="abrirModalInserir" class="btn btn-primary">Novo Setor</a>				        	
                        <table class="table-responsive table-bordered table" id="tabelaSetoresEmpresas">
                            <thead>
                                <tr class="tableheader">
                                <th>ID</th>
                                <th>Empresa</th>
                                <th>Setor</th>
                                <th>Data de criação</th>
                                <th>Data de atualização</th>
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
<hr/>


<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    var tabelaSetoresEmpresass = $('#tabelaSetoresEmpresas').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: {
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum setor a ser listado",
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
            url: '<?= site_url('setores/buscarSetores') ?>',
            type: 'POST',
            data: {idEmpresa: ''},
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    tabelaSetoresEmpresass.clear().draw();
                    tabelaSetoresEmpresass.rows.add(data.dados).draw();
                }else{
                    tabelaSetoresEmpresass.clear().draw();
                }
            },
            error: function(){
                alert('Erro ao buscar setores');
                tabelaSetoresEmpresass.clear().draw();
            }
        },
        columns: [
            { data: 'id' },
            { data: 'nomeEmpresa'},
            { data: 'nomeSetor' },
            { data: 'dataCadastro', render: function(data){return new Date(data).toLocaleString();} },
            { data: 'dataAtualizacao', render: function(data){return new Date(data).toLocaleString();} },
            { data: 'status' },
            {
				data:{'id':'id', 'status': 'status', 'nomeSetor': 'nomeSetor'},
				orderable: false,
				render: function (data) {
					return `
					<button 
						class="btn btn-primary"
						title="Editar Setor"
						style="width: 38px; margin: 0 auto;"
						id="btnEditarSetor"
                        onClick="javascript:editSetor(this,'${data['id']}','${data['status']}','${data['nomeSetor']}', '${data['idEmpresa']}')">
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
    

    $('#pesquisacliente').click(function(e){
        // Carregando
        $('#pesquisacliente')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        e.preventDefault();
        var nome = $('#pesqnome').val();
        $.ajax({
            url: '<?= site_url('setores/buscarSetores') ?>',
            type: 'POST',
            data: {idEmpresa: nome},
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                tabelaSetoresEmpresass.clear().draw();
                tabelaSetoresEmpresass.rows.add(data.dados).draw();
                $('#tabelaGeral').show();
                $('#btn_setoresEmpresas').show();
                $('#alert').hide();
                $('#pesquisacliente')
                    .html('Pesquisar')
                    .attr('disabled', false);
                }else{
                    alert('Não existem setores para essa empresa')
                    $('#pesquisacliente')
                    .html('Pesquisar')
                    .attr('disabled', false);
                    tabelaSetoresEmpresass.clear().draw();

                }
            }
        })
    })
    
    $('#abrirModalInserir').click(event=> {
        $('#modalCadSetor').modal('show')
    })
    
    $('#btnSalvarCadastroSetor').click(event=> {
        var nomeSetor = $('#nomeSetor').val();
        var idEmpresa = $('#selectEmpresaCad').val();
        if (nomeSetor == ''){
            alert('Preencha o nome do setor')
        }else if (idEmpresa == null){
            alert('Selecione a empresa')
        }else{
            botao = $('#btnSalvarCadastroSetor');
            botao.html('<i class="fa fa-spin fa-spinner"></i>');
            botao.attr('disabled', true);
            $.ajax({
                url: '<?= site_url('setores/inserirSetor') ?>',
                type: 'POST',
                data: { idEmpresa: idEmpresa,
                        nome: nomeSetor},
                dataType: 'json',
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        $('#nomeSetor').val('').trigger('change');
                        $('#modalCadSetor').modal('hide')
                        $('#pesqnome').val('').trigger('change');
                        botao.html('Salvar');
                        botao.attr('disabled', false);
                        $.ajax({
                            url: '<?= site_url('setores/buscarSetores') ?>',
                            type: 'POST',
                            data: {idEmpresa: idEmpresa},
                            dataType: 'json',
                            success: function(data){
                                if (data.status === 200){
                                tabelaSetoresEmpresass.clear().draw();
                                tabelaSetoresEmpresass.rows.add(data.dados).draw();
                                }else{
                                    alert('Não existem setores para essa empresa')
                                }
                            }
                        })
                        $('#selectEmpresaCad').val('').trigger('change');
                    }else if (data.status === 400){
                        alert('Verifique os campos e tente novamente')
                        botao.html('Salvar');
                        botao.attr('disabled', false);
                    }else{
                        alert(data.dados.mensagem)
                        botao.html('Salvar');
                        botao.attr('disabled', false);
                        
                    }
                },
                error: function(){
                    alert('Erro ao inserir setor. Tente novamente')
                    botao.html('Salvar');
                    botao.attr('disabled', false);
                }
            })
        }
    })

    async function editSetor(botao,idSetor,status,nome, idEmpresa){
        botao = $(botao);
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

        
        $('#idSetorAlterar').val(idSetor)
        $('#statusSetorAlterar').val(status)
        $('#nomeSetorAlterar').val(nome)
        $('#selectEmpresaEdit').val(idEmpresa).trigger('change');
        botao.html('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>');
        $('#modalEditSetor').modal('show')

    }
    $('#btnSalvarEditSetor').click(event=> {
        if ($('#nomeSetorAlterar').val() == ''){
            alert('Preencha o nome do setor')
        }else{
            $('#btnSalvarEditSetor')
            .html('<i class="fa fa-spin fa-spinner"></i>')
            .attr('disabled', true);
            var nomeSetor = $('#nomeSetorAlterar').val();
            var idSetor = $('#idSetorAlterar').val();
            var statuSetor = $('#statusSetorAlterar').val();
            var idEmpresa = $('#selectEmpresaEdit').val();
            if(statuSetor == 'Ativo'){
                statuSetor = 1
            }else{
                statuSetor = 0
            }
            $.ajax({
                url: '<?= site_url('setores/editarSetor') ?>',
                type: 'POST',
                data: { idSetor: idSetor,
                        nomeSetor: nomeSetor,
                        status: statuSetor,
                        idEmpresa: idEmpresa},
                dataType: 'json',
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        $('#modalEditSetor').modal('hide')
                        $('#nomeSetorAlterar').val('')
                        $('#idSetorAlterar').val('')
                        $('#statusSetorAlterar').val('')
                        $('#btnSalvarEditSetor')
                        .html('Salvar')
                        .attr('disabled', false);

                        var nome = $('#pesqnome').val();
                        if(nome == null){
                            location.reload();
                        }else{
                            $.ajax({
                                url: '<?= site_url('setores/buscarSetores') ?>',
                                type: 'POST',
                                data: {idEmpresa: nome},
                                dataType: 'json',
                                success: function(data){
                                    if (data.status === 200){
                                    tabelaSetoresEmpresass.clear().draw();
                                    tabelaSetoresEmpresass.rows.add(data.dados).draw();
                                    $('#tabelaGeral').show();
                                    $('#btn_setoresEmpresas').show();
                                    $('#alert').hide();
                                    }else{
                                        alert('Não existem setores para essa empresa')
                                    }
                                }
                            })
                        }
                    }else if (data.status === 400){
                        alert('Verifique os campos e tente novamente')
                        $('#btnSalvarEditSetor')
                        .html('Salvar')
                        .attr('disabled', false);
                    }else{
                        alert(data.dados.mensagem)
                        $('#btnSalvarEditSetor')
                        .html('Salvar')
                        .attr('disabled', false);
                    }
                },
                error: function(){
                    alert('Erro ao editar setor. Tente novamente.')
                    $('#btnSalvarEditSetor')
                    .html('Salvar')
                    .attr('disabled', false);
                }
            })
        }
    })
</script>   

<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>