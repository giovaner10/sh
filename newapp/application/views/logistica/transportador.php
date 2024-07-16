
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

<h3><?=lang("transportadores")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('logistica')?> >
	<?=lang('transportadores')?>
</div>

<div id="modalCadTransportador" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formCadTransportador" name="formCadTransportador">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="nomeHeaderModal"></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label class="control-label">Nome</label>
                                            <input type="text" class="form-control input-sm" id="nomeTransportador" placeholder="Nome do transportador" required>
                                        </div>
                                        <div class="col-md-6 form-group bord">
                                            <label class="control-label">Empresa</label>
                                            <select class="form-control input-sm" id="empresaCadTransportador" name="empresaCadTransportador" type="text" style="width: 100%" required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label class="control-label">Cpf/Cnpj</label>
                                            <input type="text" class="form-control input-sm" id="cpfCnpjTransportador" placeholder="CPF/CNPJ do transportador" required>
                                        </div>
                                        <div class="col-md-6 form-group bord">
                                            <label class="control-label">Cep</label>
                                            <input type="text" class="form-control input-sm" id="cepTransportador" placeholder="Digite o cep" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label class="control-label">Rua</label>
                                            <input type="text" class="form-control input-sm" id="ruaTransportador" placeholder="Digite a rua" required>
                                        </div>
                                        <div class="col-md-6 form-group bord">
                                            <label class="control-label">Bairro</label>
                                            <input type="text" class="form-control input-sm" id="bairroTransportador" placeholder="Digite o bairro" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label class="control-label">Cidade</label>
                                            <input type="text" class="form-control input-sm" id="cidadeTransportador" placeholder="Digite a cidade" required>
                                        </div>
                                        <div class="col-md-6 form-group bord">
                                            <label class="control-label">Uf</label>
                                            <select type="text" class="form-control input-sm" id="ufTransportador" required>
                                                    <option selected disabled value="0">Selecione</option>
                                                    <option value="AC">AC</option>
													<option value="AL">AL</option>
													<option value="AP">AP</option>
													<option value="AM">AM</option>
													<option value="BA">BA</option>
													<option value="CE">CE</option>
													<option value="DF">DF</option>
													<option value="ES">ES</option>
													<option value="GO">GO</option>
													<option value="MA">MA</option>
													<option value="MT">MT</option>
													<option value="MS">MS</option>
													<option value="MG">MG</option>
													<option value="PA">PA</option>
													<option value="PB">PB</option>
													<option value="PR">PR</option>
													<option value="PE">PE</option>
													<option value="PI">PI</option>
													<option value="RJ">RJ</option>
													<option value="RN">RN</option>
													<option value="RS">RS</option>
													<option value="RO">RO</option>
													<option value="RR">RR</option>
													<option value="SC">SC</option>
													<option value="SP">SP</option>
													<option value="SE">SE</option>
													<option value="TO">TO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group bord">
                                            <label class="control">Complemento</label>
                                            <input type="text" class="form-control input-sm" id="complementoTransportador" placeholder="Digite o complemento">
                                        </div>
                                        <div class="col-md-6 form-group bord" id="divStatusEditTransportador">
                                            <label class="control-label">Status</label>
                                            <select class="form-control input-sm" id="statusEditTransportador">
                                            <option value="1">Ativo</option>
                                            <option value="0">Inativo</option>
                                            </select>
                                        </div>
                                    </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" type="submit" data-id="" id="btnSalvarCadastroTransportador" style="margin-right: 10px;">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
	<div class="container-fluid my-1">
		<div class="col-sm-12">
                <form id="formPesquisa">
                    <br>
       			    <div class="row" >
       			        <div id="pesquisa" class="col-md-4">
       			            <label>Nome: </label>
       			            <select class="form-control input-sm" id="pesqnome" name="nome" type="text" style="width: 100%"></select>
       			        </div>
				    	<div id="buttonPesquisar" class="col-md-1">
				    		<a class="btn btn-primary" id="pesquisacliente" type="submit" style="margin-top: 22px">Pesquisar</a>
				    	</div>
       			    </div>
    		    </form>
                <div id="transportadoras" class="tab-pane fade in active" style="margin-top: 20px;">
                    <div class="container-fluid" id="tabelaGeral">
                        <br>
				        	<a id="abrirModalInserir" class="btn btn-primary"><?=lang('novo_transportador')?></a>
                        <table class="table-responsive table-bordered table" id="tabelaTransportadores" style="width: 100%">
                            <thead>
                                <th>ID</th>
                                <th>Empresa</th>
                                <th>Transportador</th>
                                <th>CPF/CNPJ</th>
                                <th>Cidade</th>
                                <th>Cep</th>
                                <th>Rua</th>
                                <th>Bairro</th>
                                <th>Estado</th>
                                <th>Complemento</th>
                                <th>Data de Criação</th>
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
<hr/>


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

    var tabelaTransportadores = $('#tabelaTransportadores').DataTable({
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        order: [11, 'desc'],
        language:{
            loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
            searchPlaceholder:  'Pesquisar',
            emptyTable:         "Nenhum transportador a ser listado",
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
            url: '<?= site_url('transportadores/listarTodosTransportadores') ?>',
            type: 'POST',
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    tabelaTransportadores.clear().draw();
                    tabelaTransportadores.rows.add(data.results).draw();
                }
            },
        },
        columns: [
            { data: 'id' },
            { data: 'nomeEmpresa' },
            { data: 'nomeTransportador' },
            { data: 'cpfCnpjTransportador', render: function (data) { 
                                                if (data.length === 11) { 
                                                    return data.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4"); 
                                                } else if (data.length === 14) { 
                                                    return data.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5"); 
                                                } else { 
                                                return data; 
                                                }
                                            }    
            },
            { data: 'cidade' },
            { data: 'cep' },
            { data: 'rua' },
            { data: 'bairro' },
            { data: 'estado' },
            { data: 'complemento' },
            { data: 'dataCadastro', render: function(data){return new Date(data).toLocaleDateString();} },
            { data: 'dataAtualizacao', render: function(data){return new Date(data).toLocaleDateString();} },
            { data: 'status' },
            {
				data:{'id':'id', 'status': 'status', 'nomeTransportador': 'nomeTransportador',
                     'cpfCnpjTransportador': 'cpfCnpjTransportador', 'cidade': 'cidade', 'cep': 'cep',
                      'rua': 'rua', 'bairro': 'bairro', 'estado': 'estado', 'complemento': 'complemento'},
				orderable: false,
				render: function (data) {
					return `
					<button 
						class="btn fa fa-pencil-square-o"
						title="Editar Transportador"
						style="width: 38px; margin: 0 auto; background-color: #04acf4; color: white;"
						id="btnEditarTransportador"
                        onClick="javascript:editTransportador(this,'${data['id']}','${data['status']}','${data['nomeTransportador']}',
                                                                    '${data['cpfCnpjTransportador']}','${data['cidade']}','${data['cep']}',
                                                                    '${data['rua']}','${data['bairro']}','${data['estado']}','${data['complemento']}', '${data['idEmpresa']}'
                                                            )">
					</button>
                    <button
                        class="btn fa fa-exchange"
                        title="Alterar Status"
                        style="width: 38px; margin: 0 auto; background-color: ${data['status'] === 'Ativo' ? 'red' : 'green'}; color: white;"
                        id="btnAlterarStatusTransportador"
                        onClick="javascript:alterarStatusTransportador(this,'${data['id']}', '${data['status']}')">
                    </button>
                    `;
				}
			}
        ]
    });
   
    $(document).ready(async function(){
        $('#pesqnome').empty();
        $('#empresaCadTransportador').empty();
        $('#pesqnome').append('<option value="0" disabled selected>Buscando empresas...</option>');
        let empresas  = await $.ajax ({
                            url: '<?= site_url('setores/buscarEmpresas') ?>',
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
                            }
                        });
                       
        $('#pesqnome').select2({
            data: empresas.results,
            placeholder: "Selecione a empresa",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
            });
        
        $('#pesqnome').on('select2:select', function (e) {
            var data = e.params.data;
        });

        $("#pesqnome").find('option').get(0).remove()
        $('#pesqnome').append('<option value="" disabled selected>Selecione a empresa</option>');

        let empresasCad  = await $.ajax ({
                            url: '<?= site_url('setores/buscarEmpresas') ?>',
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
                            }
                        });
                       
        $('#empresaCadTransportador').select2({
            data: empresasCad.results,
            placeholder: "Selecione a empresa",
            allowClear: true,
            language: "pt-BR",
            width: '100%',
        });
        
        $('#empresaCadTransportador').on('select2:select', function (e) {
            var data = e.params.data;
        });

        $('#empresaCadTransportador').append('<option value="" disabled selected>Selecione a empresa</option>');
    });

    $('#pesquisacliente').click(function(e){
        // Carregando
        $('#pesquisacliente')
            .html('<i class="fa fa-spin fa-spinner"></i> <?=lang('carregando')?>')
            .attr('disabled', true);

        e.preventDefault();
        var nome = $('#pesqnome').val();
        $.ajax({
            url: '<?= site_url('transportadores/buscarTransportadores') ?>',
            type: 'POST',
            data: {idEmpresa: nome},
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    tabelaTransportadores.clear().draw();
                    tabelaTransportadores.rows.add(data.dados).draw();
                $('#pesquisacliente')
                    .html('Pesquisar')
                    .attr('disabled', false);
                }else{
                    alert('Não existem transportadores para essa empresa')
                    $('#pesquisacliente')
                    .html('Pesquisar')
                    .attr('disabled', false);
                    tabelaTransportadores.clear().draw();

                }
            }
        })
    })

    function editTransportador(botao, id, status, nomeTransportador, CpfCnpjTransportador, cidade, cep, rua, bairro, estado, complemento, idEmpresa){
        $('#nomeHeaderModal').html('<?=lang("editar_transportador")?>');
        if (status == 'Ativo'){
            status = 1;
        }else{
            status = 0;
        }
        $('#divStatusEditTransportador').attr('hidden', false);
        $('#statusEditTransportador').attr('required', true);
        $('#btnSalvarCadastroTransportador').data('id', id);
        $('#nomeTransportador').val(nomeTransportador);
        $('#empresaCadTransportador').val(idEmpresa).trigger('change');
        $('#cpfCnpjTransportador').val(CpfCnpjTransportador);
        $('#cepTransportador').val(cep);
        $('#ruaTransportador').val(rua);
        $('#bairroTransportador').val(bairro);
        $('#cidadeTransportador').val(cidade);
        $('#ufTransportador').val(estado);
        $('#complementoTransportador').val(complemento);
        $('#statusEditTransportador').val(status);
        $('#modalCadTransportador').modal('show');
    }
                        
    $('#abrirModalInserir').click(event=> {
        $('#divStatusEditTransportador').attr('hidden', true);
        $('#statusEditTransportador').attr('required', false);
        $('#modalCadTransportador').modal('show')
        $('#nomeHeaderModal').html('<?=lang("novo_transportador")?>');

    })

    $('#modalCadTransportador').on('hidden.bs.modal', function () {
        $('#nomeTransportador').val('');
        $('#cpfCnpjTransportador').val('');
        $('#cepTransportador').val('');
        $('#ruaTransportador').val('');
        $('#bairroTransportador').val('');
        $('#cidadeTransportador').val('');
        $('#ufTransportador').val(0);
        $('#complementoTransportador').val('');
        $('#empresaCadTransportador').val('').trigger('change');
    })
    
    $('#formCadTransportador').submit(function(e){
        e.preventDefault();
        btn = $('#btnSalvarCadastroTransportador')

        var id = btn.data('id');
        var nomeTransportador = $('#nomeTransportador').val();
        var cpfCnpj = $('#cpfCnpjTransportador').val();
        var cep = $('#cepTransportador').val();
        var rua = $('#ruaTransportador').val();
        var bairro = $('#bairroTransportador').val();
        var cidade = $('#cidadeTransportador').val();
        var uf = $('#ufTransportador').val();
        var complemento = $('#complementoTransportador').val();
        var idEmpresa = $('#empresaCadTransportador').val();
        var status = $('#statusEditTransportador').val();
        
        if ($('#nomeHeaderModal').html() == '<?=lang("novo_transportador")?>'){
            btn.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i> Cadastrando...');
            $.ajax({
                url: '<?= site_url('transportadores/inserirTransportador') ?>',
                type: 'POST',
                data: { idTransportador:id,
                        nome: nomeTransportador,
                        cpfCnpj: cpfCnpj,
                        cep:cep,
                        rua:rua,
                        bairro:bairro,
                        cidade:cidade,
                        uf:uf,
                        complemento:complemento,
                        idEmpresa: idEmpresa,
                        status: status},
                dataType: 'json',
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        btn.attr('disabled', false).html('Salvar');
                        $('#modalCadTransportador').modal('hide')
                        $('#pesqnome').val('').trigger('change');
                        tabelaTransportadores.ajax.reload();
                    }else if(data.status === 400){
                        alert('Verifique os campos e tente novamente');
                        btn.attr('disabled', false).html('Salvar');
                    }
                    else{
                        alert(data.dados.mensagem);
                        btn.attr('disabled', false).html('Salvar');
                    }
                },
                error: function(){
                    alert('Erro ao cadastrar transportador.Tente novamente');
                    btn.attr('disabled', false).html('Salvar');
                }
            })
        }else{
            btn.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i> Editando...');
            $.ajax({
                url: '<?= site_url('transportadores/editarTransportador') ?>',
                type: 'POST',
                data: { idTransportador: id,
                        idEmpresa: idEmpresa,
                        nome: nomeTransportador,
                        registro: cpfCnpj,
                        cep:cep,
                        rua:rua,
                        bairro:bairro,
                        cidade:cidade,
                        uf:uf,
                        complemento:complemento,
                        status: status
                        },
                dataType: 'json',
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        btn.attr('disabled', false).html('Salvar');
                        $('#modalCadTransportador').modal('hide')
                        $('#pesqnome').val('').trigger('change');
                        tabelaTransportadores.ajax.reload();
                    }else if(data.status === 400){
                        alert('Verifique os campos e tente novamente');
                        btn.attr('disabled', false).html('Salvar');
                    }
                    else{
                        alert(data.dados.mensagem);
                        btn.attr('disabled', false).html('Salvar');
                    }
                },
                error: function(){
                    alert('Erro ao editar transportador.Tente novamente');
                    btn.attr('disabled', false).html('Salvar');
                }
            })
        }
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

    function alterarStatusTransportador(botao,id,status){
        if(confirm('Clique em OK para confirmar a alteração de status ou em Cancelar para voltar')){
            var idEmpresa = $('#pesqnome').val();
            if(status == 'Ativo'){
                status = 0
            }else{
                status = 1
            }
            $.ajax({
                url: '<?= site_url('transportadores/alterarStatusTransportador') ?>',
                type: 'POST',
                data: {idTransportador: id,
                        status: status},
                dataType: 'json',
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        $('#pesqnome').val('').trigger('change');
                        tabelaTransportadores.ajax.reload();
                    }else{
                        alert(data.dados.mensagem);
                    }
                }
            })
        }else{
            return false;
        }
    }
</script>   

