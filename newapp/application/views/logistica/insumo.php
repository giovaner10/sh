
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

    .bord{
        border-left: 3px solid #03A9F4;
    }
</style>

<h3><?=lang("insumos")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('logistica')?> >
	<?=lang('insumos')?>
</div>
<hr>

<div class="alert alert-info col-md-12" style="margin-bottom: 50px;">    
    <div class ="col-md-12">
        <p class ="col-md-12">Informe o nome do insumo: </p>
        <span class="help help-block"></span>
        <form action="" id="formPesquisa">

            <div class="col-md-4 form-group">
                <label>Insumo: </label>
                <select class="form-control input-sm" id="pesqInsumo" name="nome" type="text" style="width: 100%;"><option value="0" selected disabled>Buscando...</option></select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary" id="btnPesquisaInsumo" style="margin-top: 22px; width: 100%;" type="submit">Pesquisar</button>
            </div>
        </form>
    </div>
</div>

<div class="container-fluid my-1">
	<div class="col-sm-12">
        <div class="container-fluid" id="tabelaGeral">
            <a id="abrirModalInserir" class="btn btn-primary" style="margin-bottom: 20px;">Cadastrar Insumo</a>	
            <table class="table-responsive table-bordered table" id="tabelaInsumos" style="width: 100%">
                <thead>
                    <th>Referência</th>
                    <th>Nome</th>
                    <th>Unidade</th>
                    <th>Quantidade</th>
                    <th>Data de Cadastro</th>
                    <th>Data de Atualização</th>
                    <th>Ações</th>
                </thead>
                <tbody>
                </tbody>
            </table>    
        </div>
	</div>
</div>

<!-- MODAL INSERIR/EDITAR INSUMOS -->
<div id="modalCadInsumo" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadInsumo" id="formCadastroInsumo">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="nomeHeaderModal"></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="col-md-12">
                        <div id="div_identificacao">
                            <div class="row">
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Referência:</label>
                                    <select name="referenciaInsumo" id="referenciaInsumo" class="form-control input-sm" required>
                                        <option value="" disabled selected>Selecione a referência</option>
                                        <option value="IButton">IButton</option>
                                        <option value="Leitor">Leitor</option>
                                        <option value="Pânico">Pânico</option>
                                        <option value="Rele 12V">Rele 12V</option>
                                        <option value="Rele 24V">Rele 24V</option>
                                        <option value="Sirene">Sirene</option>
                                        <option value="Outros">Outros</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Nome:</label>
       			                    <input type="text" class="form-control input-sm" name="nomeInsumo" id="nomeInsumo" placeholder="Digite o nome" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Unidade: </label>
                                    <select id="selectUnidadeInsumo" class="form-control input-sm" required>
										<option value="" disabled selected>Selecione a unidade</option>
                                        <option value="UN">UN</option>
                                        <option value="CX">CX</option>
                                        <option value="PÇ">PÇ</option>
										<option value="KM">KM</option>
                                        <option value="M">M</option>
                                        <option value="CM">CM</option>
                                        <option value="MM">MM</option>
                                        <option value="KG">KG</option>
                                        <option value="G">G</option>
									</select>
                                </div>
                                <div class="col-md-6 form-group bord">
                                    <label class="control-label">Quantidade: </label>
                                    <input type="text" class="form-control input-sm" name="quantidadeInsumo" id="quantidadeInsumo" placeholder="Digite a quantidade" onkeyup="formatarQuantidade(this.id)" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" data-id= '' type="submit" id="btnSalvarCadastroInsumo" style="margin-right: 15px;">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>
<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>
<script>
$.fn.dataTable.moment( 'DD/MM/YYYY HH:mm:ss' );
$.fn.dataTable.moment('DD/MM/YYYY');
var insumos = new Array();

var tabelaInsumos = $('#tabelaInsumos').DataTable({
    responsive: true,
    ordering: true,
    paging: true,
    searching: true,
    info: true,
    order: [5, 'desc'],
    language: {
        loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
        searchPlaceholder:  'Pesquisar',
        emptyTable:         "Nenhum insumo a ser listado",
        info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
        infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
        zeroRecords:        "Nenhum resultado encontrado.",
        lengthMenu:         "Mostrar _MENU_ resultados por página",
        paginate: {
            first:          "Primeira",
            last:           "Última",
            next:           "Próxima",
            previous:       "Anterior"
        },
    },
    deferRender: true,
    lengthChange: true,
    ajax:{
        url: '<?= site_url('Insumos/listarInsumos') ?>',
        type: 'GET',
        dataType: 'json',
        beforeSend: function (){
            $('#pesqInsumo').attr('disabled', true);
        },
        success: function(data){
            if (data.status === 200){
                tabelaInsumos.clear().draw();
                tabelaInsumos.rows.add(data.results).draw();
                insumos = data.results;

                let insumosOptions = new Array();

                insumos.forEach(insumo => {
                    insumosOptions.push({
                        "id": insumo["id"],
                        "text": insumo["nome"],
                    });
                });

                $('#pesqInsumo').select2({
                        data: insumosOptions,
                        placeholder: "Selecione o insumo",
                        allowClear: true,
                        language: "pt-BR",
                });

                $('#pesqInsumo').on('select2:select', function (e) {
                    var data = e.params.data;
                });

                $('#pesqInsumo').find('option').get(0).remove();
                $('#pesqInsumo').prepend('<option value="0" selected="selected" disabled>Selecione o insumo</option>');
                $('#pesqInsumo').attr('disabled', false);
            }else{
                tabelaInsumos.clear().draw();
            }
        },
        error: function(){
            alert('Erro ao buscar insumos. Tente novamente');
            tabelaInsumos.clear().draw();
        }
    },
    columns: [
        {data: 'referencia'},
        {data: 'nome'},
        {data: 'unidadeEstoque'},
        {data: 'quantidadeEstoque'},
        {data: 'dataCadastro',  render: function(data){return new Date(data).toLocaleDateString();} },
        {data: 'dataUpdate', render: function(data){return new Date(data).toLocaleDateString();} },
        {
            data: { 'id': 'id' },
            orderable: false,
            className: 'text-center',
            render: function(data){
                return `
                <button
                    id="btnEditarInsumo"
                    class="btn btn-primary"
                    title="Editar Insumo"
                    onClick="javascript:editInsumo(this, '${data['id']}', '${data['referencia']}', '${data['nome']}', '${data['unidadeEstoque']}', '${data['quantidadeEstoque']}')">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                </button>
                <button
                    id="btnAlterarStatusInsumo"
                    class="btn btn-primary"
                    title="Inativar Insumo"
                    style="background-color: red !important;"
                    onClick="javascript:alterarStatus(this, '${data['id']}', '${data['status']}')">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                </button>
                `;
            }
        },
    ],
})

$('#btnPesquisaInsumo').click(function (e){
    e.preventDefault();
    if ($('#pesqInsumo').val() == '' || $('#pesqInsumo').val() == null) {
        tabelaInsumos.rows.add(insumos).draw();
        $('#btnPesquisaInsumo')
            .html('Pesquisar')
            .attr('disabled', false);
    }else{
        $('#btnPesquisaInsumo')
            .html('<i class="fa fa-spinner fa-spin"></i> Buscando...')
            .attr('disabled', true);

        var nomeInsumo = $("#pesqInsumo").select2('data')[0].text.toLowerCase();

        // Filtra as linhas que correspondem ao nomeInsumo (sem diferenciação de maiúsculas/minúsculas)
        var linhasFiltradas = insumos.filter(function(insumo) {
            return insumo.nome.toLowerCase() === nomeInsumo;
        });

        // Verifica se foram encontradas linhas correspondentes
        if (linhasFiltradas.length > 0) {
            tabelaInsumos.clear().draw();
            tabelaInsumos.rows.add(linhasFiltradas).draw();
            $('#btnPesquisaInsumo')
            .html('Pesquisar')
            .attr('disabled', false);
            $('#alert').hide();
        } else {
            alert('Não existem insumos com esse nome');
            $('#btnPesquisaInsumo')
            .html('Pesquisar')
            .attr('disabled', false);
            tabelaInsumos.clear().draw();
        }
    }

})

$('#abrirModalInserir').click(function(){
    $('#modalCadInsumo').modal('show');
    $('#nomeHeaderModal').html('Cadastrar Insumo');
})

$('#formCadastroInsumo').submit(function(e){
    e.preventDefault();
    var referencia = $('#referenciaInsumo').val();
    var nome = $('#nomeInsumo').val();
    var unidade = $('#selectUnidadeInsumo').val();
    var quantidade = $('#quantidadeInsumo').val();
    var btn = $('#btnSalvarCadastroInsumo');
    var id = btn.data('id');
    var url = '<?= site_url('Insumos/cadastrarInsumo') ?>'

    if ($('#nomeHeaderModal').html() === 'Editar Insumo'){
        url = '<?= site_url('Insumos/editarInsumo') ?>'
    }

    btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
            idInsumo: id,
            referencia: referencia,
            nome: nome,
            unidade: unidade,
            quantidade: quantidade
        },
        success: function(data){
            if (data.status === 200){
                alert(data.dados.mensagem)
                $('#modalCadInsumo').modal('hide');
                tabelaInsumos.ajax.reload();
            }else{
                if (data.dados.mensagem){
                    alert(data.dados.mensagem)
                }else{
                    alert('Erro. Verifique os campos e tente novamente.');
                }
            }
        },
        error: function(){
            alert('Erro. Tente novamente');
        },
        complete: function(){
            btn.attr('disabled', false).html('Salvar');
        }
    })
    
})

$('#modalCadInsumo').on('hidden.bs.modal', function(){
    $('#formCadastroInsumo').trigger('reset');
})

function formatarQuantidade(campo) {
    var elemento = document.getElementById(campo);
    var valor = elemento.value;

    valor = valor.replace(/[^\d.,]/g, '');

    valor = valor.replace(',', '.');

    var partes = valor.split('.');
    if (partes.length > 2) {
        partes = [partes.shift(), partes.join('')];
        valor = partes.join('.');
    }

    elemento.value = valor;
}

function editInsumo(botao, id, referencia, nome, unidade, quantidade){
    $('#nomeHeaderModal').html('Editar Insumo');
    $('#referenciaInsumo').val(referencia);
    $('#nomeInsumo').val(nome);
    $('#selectUnidadeInsumo').val(unidade).trigger('change');
    $('#quantidadeInsumo').val(quantidade);
    $('#btnSalvarCadastroInsumo').data('id', id);

    $('#modalCadInsumo').modal('show');
}

function alterarStatus(botao, id, status){
    if (confirm('Deseja realmente inativar o insumo?')){
        btn = $(botao);
        btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
            url: '<?= site_url('Insumos/inativarInsumo') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idInsumo: id,
            },
            success: function(data){
                if (data.status === 200){
                    alert(data.dados.mensagem)
                    tabelaInsumos.ajax.reload();
                }else{
                    if (data.dados.mensagem){
                        alert(data.dados.mensagem)
                    }else{
                        alert('Erro ao inativar. Tente novamente.');
                    }
                }
            },
            error: function(){
                alert('Erro ao inativar. Tente novamente');
            },
            complete: function(){
                btn.attr('disabled', false).html('<i class="fa fa-exchange" aria-hidden="true"></i>');
            }
        })
    }
}
</script>   

