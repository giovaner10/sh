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
        max-width: 150px;
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

    

    #loading::after {
      content: attr(data-content);
      color: white;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      animation: fade 2s infinite;
    }

    @keyframes fade {
      0% {
        opacity: 0;
      }
      50% {
        opacity: 1;
      }
      100% {
        opacity: 0;
      }
    }

</style>
<h3><?=lang('regionais')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('departamentos')?> >
    <a href="<?=site_url('ComerciaisTelevendas/ComerciaisTelevendasInfoGerais')?>"><?=lang('comercial_e_televendas')?></a> > 
    <?=lang('comissionamento_vendas')?>
</div>

<hr>

<div class="container-fluid my-1">
	<div class="col-sm-12">
        <form id="formPesquisa">
       	    <div class="row">
       	        <div id="pesquisa" class="col-md-4">
       	            <label>Nome: </label>
       	            <select class="form-control input-sm" id="pesqnomeEmpresa" name="nome" type="text" style="width: 100%" required></select>
       	        </div>
		    	<div id="buttonPesquisar" class="col-md-1">
		    		<button class="btn btn-primary" id="pesquisaRegionaisEmpresa" type="submit" style="margin-top: 22px;">Pesquisar</button>
		    	</div>
       	    </div>
    	</form>
        <div id="regionais" class="tab-pane fade in active" style="margin-top: 20px">
            <div class="container-fluid" id="tabelaGeral">
                <a id="abrirModalInserir" class="btn btn-primary">Nova Regional</a>		        	
                <table class="table table-bordered table-hover table-striped responsive nowrap" id="tabelaRegionais">
                    <thead>
                        <tr class="tableheader">
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Empresa</th>
                        <th>Data de Cadastro</th>
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

<!-- Modal Cadastro de Regional -->
<div id="modalCadRegional" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadRegional" id="formCadastroRegional">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="nomeHeaderModal"></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="col-md-12">
                        <div id="div_identificacao">
                            <div class="row">
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Nome</label>
                                    <input type="text" class="form-control input-sm" name="nomeRegional" id="nomeRegional" placeholder="Digite o nome da regional" required>
                                </div>
                                <div id="divSelectEmpresaCadatro" class="col-md-6 form-group bord">
                                    <label class="control-label">Empresa: </label>
       			                    <select class="form-control input-sm" id="selectEmpresaCadastro" name="selectEmpresaCadastro" type="text" required></select>
                                </div>
                                <div id="divSelectEmpresaEditar" class="col-md-6 form-group bord">
                                    <label class="control-label">Empresa: </label>
       			                    <select class="form-control input-sm" id="selectEmpresaEditar" name="selectEmpresaEditar" type="text" required></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group bord" id="divStatusCad" hidden>
                                    <label class="control-label">Status</label>
                                    <select class="form-control input-sm" id="statusRegional">
                                        <option value="1">Ativo</option>
                                        <option value="0">Inativo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" data-botao='' data-id= '' type="submit" id="btnSalvarCadastroRegional">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>
<script>
$.fn.dataTable.moment( 'DD/MM/YYYY HH:mm:ss' );
$.fn.dataTable.moment('DD/MM/YYYY');

var tabelaRegionais = $('#tabelaRegionais').DataTable({
    responsive: true,
    ordering: true,
    paging: true,
    searching: true,
    info: true,
    order: [4, 'desc'],
    language: {
        loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
        searchPlaceholder:  'Pesquisar',
        emptyTable:         "Nenhuma regional a ser listada",
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
    ajax: {
        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarTodasRegionais') ?>',
        type: 'GET',
        dataType: 'json',
        success: function(data){
            if (data.status === 200){
                tabelaRegionais.clear().draw();
                tabelaRegionais.rows.add(data.results).draw();
            }else{
                tabelaRegionais.clear().draw();
            }
        },
        error: function(){
            alert('Erro ao listar regionais. Tente novamente!')
            tabelaRegionais.clear().draw();
        }
    },
    columns: [
        { data: 'id',
            visible: false},
        {data: 'nome'},
        { data: 'nomeEmpresa'},
        { data: 'dataCadastro',
            render: function (data) {
                        var date = new Date(data);
                        date.setDate(date.getDate());
                        return date.toLocaleDateString('pt-BR');
                    }},
        { data: 'dataUpdate',
            render: function (data) {
                        var date = new Date(data);
                        date.setDate(date.getDate());
                        return date.toLocaleDateString('pt-BR');
                    }},
        {
		    data:{'id':'id', 'nome': 'nome','idEmpresa': 'idEmpresa', 'dataCadastro': 'dataCadastro', 'dataUpdate': 'dataUpdate', 'status': 'status'},
		    orderable: false,
		    render: function (data) {
		    	return `
		    	<button 
		    		class="btn btn-primary"
		    		title="Editar Regional"
		    		id="btnEditarRegional"
                    onClick="javascript:editRegional(this,'${data['id']}','${data['nome']}','${data['idEmpresa']}','${data['status']}')">
		    		<i class="fa fa-pencil" aria-hidden="true"></i>
		    	</button>
                `;
		    }
	    }   
    ]
});

$('#pesqnomeEmpresa').select2({
        ajax: {
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarEmpresas') ?>',
            type: 'GET',
            dataType: 'json',
            delay: 1000,
            data: function (params) {
                return {
                    q: params.term,
                };
            },
        },
        width: '100%',
        placeholder: 'Selecione uma empresa',
        allowClear: true,
        language: "pt-BR",
        minimumInputLength: 3,
});

$('#formPesquisa').submit(function(e){
    e.preventDefault();
    var idEmpresa = $('#pesqnomeEmpresa').val();
    $('#pesquisaRegionaisEmpresa').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...');
    $.ajax({
        url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/listarRegionais') ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            idEmpresa: idEmpresa
        },
        success: function(data){
            if (data.status === 200){
                tabelaRegionais.clear().draw();
                tabelaRegionais.rows.add(data.results).draw();
                $('#pesquisaRegionaisEmpresa').attr('disabled', false).html('Pesquisar');
            }else{
                tabelaRegionais.clear().draw();
                $('#pesquisaRegionaisEmpresa').attr('disabled', false).html('Pesquisar');
            }
        },
        error: function(){
            alert('Erro ao pesquisar campanhas')
            $('#pesquisaRegionaisEmpresa').attr('disabled', false).html('Pesquisar');
        }
    });
});

$('#selectEmpresaCadastro').select2({
        ajax: {
            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarEmpresas') ?>',
            type: 'GET',
            dataType: 'json',
            delay: 1000,
            data: function (params) {
                return {
                    q: params.term,
                };
            },
        },
        width: '100%',
        placeholder: 'Selecione uma empresa',
        allowClear: true,
        language: "pt-BR",
        minimumInputLength: 3,
    });

    $('#abrirModalInserir').click(function(){
        $('#divSelectEmpresaCadatro').attr('hidden', false);
        $('#selectEmpresaCadastro').attr('required', true);
        $('#divSelectEmpresaEditar').attr('hidden', true);
        $('#selectEmpresaEditar').attr('required', false);
        $('#nomeHeaderModal').html('<?=lang("nova_regional")?>');
        $('#modalCadRegional').modal('show');
    });

    $('#formCadastroRegional').submit(function(e){
        e.preventDefault();
        var idEmpresa = $('#selectEmpresaCadastro').val();
        var nome = $('#nomeRegional').val();
        $('#btnSalvarCadastroRegional').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
        if ($('#nomeHeaderModal').html() === '<?=lang("nova_regional")?>'){
            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/cadastrarRegional') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    idEmpresa: idEmpresa,
                    nome: nome
                },
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem);
                        $('#btnSalvarCadastroRegional').attr('disabled', false).html('Salvar');
                        tabelaRegionais.ajax.reload();
                        $('#pesqnomeEmpresa').val('').trigger('change');
                        $('#modalCadRegional').modal('hide');
                    }else{
                        $('#btnSalvarCadastroRegional').attr('disabled', false).html('Salvar');
                        alert(data.dados.mensagem);
                    }
                },
                error: function(){
                    $('#btnSalvarCadastroRegional').attr('disabled', false).html('Salvar');
                    alert('Erro ao cadastrar regional. Tente novamente!');
                }
            });
        }else{
            var id = $('#btnSalvarCadastroRegional').data('id');
            var status = $('#statusRegional').val();
            var idEmpresa = $('#selectEmpresaEditar').val();
            var botao = $('#btnSalvarCadastroRegional').data('botao');
            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/editarRegional') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    nome: nome,
                    idEmpresa: idEmpresa,
                    status: status
                },
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem);
                        $('#btnSalvarCadastroRegional').attr('disabled', false).html('Salvar');
                        $('#modalCadRegional').modal('hide');
                        if ($('#pesqnomeEmpresa').val() == idEmpresa){
                            $('#pesquisaRegionaisEmpresa').trigger('click');
                        }else if ($('#pesqnomeEmpresa').val() == null || $('#pesqnomeEmpresa').val() == ''){
                            tabelaRegionais.ajax.reload();
                        }
                        else{
                            tabelaRegionais.row(botao.parentNode.parentNode).remove().draw();
                        }
                    }else{
                        $('#btnSalvarCadastroRegional').attr('disabled', false).html('Salvar');
                        alert(data.dados.mensagem);
                    }
                },
                error: function(){
                    $('#btnSalvarCadastroRegional').attr('disabled', false).html('Salvar');
                    alert('Erro ao editar regional. Tente novamente!');
                }
            });
        }
    });

    $('#modalCadRegional').on('hidden.bs.modal', function () {
        $('#nomeRegional').val('');
        $('#selectEmpresaCadastro').val('').trigger('change');
        $('#statusRegional').attr('required', false);
        $('#divStatusCad').attr('hidden', true);
    });

    async function editRegional(botao, id, nome, idEmpresa, status){
        btn = $(botao);
        $('#divSelectEmpresaCadatro').attr('hidden', true);
        $('#selectEmpresaCadastro').attr('required', false);
        $('#divSelectEmpresaEditar').attr('hidden', false);
        $('#selectEmpresaEditar').attr('required', true);
        $('#divStatusCad').attr('hidden', false);
        $('#statusRegional').attr('required', true);
        $('#btnSalvarCadastroRegional').data('botao', botao);

        btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        if (status == 'Ativo'){
            status = 1;
        }else{
            status = 0;
        }
        let empresasEdit  = await $.ajax ({
                            url: '<?= site_url('ComerciaisTelevendas/ComissionamentoDeVendas/buscarEmpresas') ?>',
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
                                btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
                            }
                        });

        $('#selectEmpresaEditar').select2({
            data: empresasEdit.results,
            placeholder: "Selecione a empresa",
            allowClear: true,
            language: "pt-BR",
            width: '100%'
            });
        
        $('#selectEmpresaEditar').on('select2:select', function (e) {
            var data = e.params.data;
        });
        
        btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
        $('#nomeHeaderModal').html('<?=lang("editar_regional")?>');
        $('#btnSalvarCadastroRegional').data('id', id);
        $('#nomeRegional').val(nome);
        $('#selectEmpresaEditar').val(idEmpresa).trigger('change');
        $('#statusRegional').val(status)
        $('#modalCadRegional').modal('show');
    }
</script>


