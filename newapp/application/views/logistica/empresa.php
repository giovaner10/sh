
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

    .required-star {
    color: red;
    font-weight: bold;
}

</style>

<h3><?=lang("empresas")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('logistica')?> >
	<?=lang('empresas')?>
</div>


<div id="modalCadEmpresa" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formCadEmpresa">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("nova_empresa")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Nome</label>
                                            <input type="text" class="form-control" id="nomeEmpresa" placeholder="Digite o nome da empresa">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Cpf/Cnpj</label>
                                            <input type="text" class="form-control" id="cpfCnpjEmpresa" placeholder="Digite o cpf/cnpj da empresa">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Cep</label>
                                            <input type="text" class="form-control" id="cepEmpresa" placeholder="Digite o cep da empresa">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Rua</label>
                                            <input type="text" class="form-control" id="ruaEmpresa" placeholder="Digite a rua da empresa">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Bairro</label>
                                            <input type="text" class="form-control" id="bairroEmpresa" placeholder="Digite o bairro da empresa">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Cidade</label>
                                            <input type="text" class="form-control" id="cidadeEmpresa" placeholder="Digite a cidade da empresa">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Uf</label>
                                            <select type="text" class="form-control input-sm" id="ufEmpresa">
                                                    <option value="0" disabled>Selecione</option>
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
                                        <div class="col-md-6 form-group">
                                            <label class="control">Complemento</label>
                                            <input type="text" class="form-control" id="complementoEmpresa" placeholder="Digite o complemento do endereço">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <a class="btn btn-primary" id="btnSalvarCadastroEmpresa">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modalEditEmpresa" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form name="formEditarEmpresa">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("editar_empresa")?></h3>
                </div>
                <div id="bodyModalEditarEmpresa" class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" style="padding: 0 10px;">
                                <div id="div_identificacao">
                                    <div class="row">
                                        <div class="col-md-6 form-group" hidden>
                                            <label>Id</label>
                                            <input type="text" class="form-control" id="idEmpresaEditar" disabled>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Nome</label>
                                            <input type="text" class="form-control" id="nomeEmpresaEditar" placeholder="Digite o nome da empresa">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Cpf/Cnpj</label>
                                            <input type="text" class="form-control" id="cpfCnpjEmpresaEditar" placeholder="Digite o cpf/cnpj da empresa">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Cep</label>
                                            <input type="text" class="form-control" id="cepEmpresaEditar" placeholder="Digite o cep da empresa">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Rua</label>
                                            <input type="text" class="form-control" id="ruaEmpresaEditar" placeholder="Digite a rua da empresa">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Bairro</label>
                                            <input type="text" class="form-control" id="bairroEmpresaEditar" placeholder="Digite o bairro da empresa">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Cidade</label>
                                            <input type="text" class="form-control" id="cidadeEmpresaEditar" placeholder="Digite a cidade da empresa">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Uf</label>
                                            <select type="text" class="form-control input-sm" id="ufEmpresaEditar">
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
                                        <div class="col-md-6 form-group">
                                            <label class="control">Complemento <span class="required-star">*</span></label>
                                            <input type="text" class="form-control" id="complementoEmpresaEditar" placeholder="Digite o complemento do endereço">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>

                    <a class="btn btn-primary" id="btnSalvarEditEmpresa">Salvar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<hr/>
<div id="empresas" class="tab-pane fade in active" style="margin-top: 20px">
    <div class="container-fluid" id="tabelaGeral">
	    	<a id="abrirModalInserir" class="btn btn-primary">Nova Empresa</a>
        <table class="table-responsive table-bordered table-striped table-hover table" id="tabelaEmpresas">
            <thead>
                <th>ID</th>
                <th>Empresa</th>
                <th>Cpf/Cnpj</th>
                <th>Cep</th>
                <th>Rua</th>
        		<th>Bairro</th>
                <th>Cidade</th>
                <th>Complemento</th>
                <th>Estado</th>
                <th>Data Cadastro</th>
                <th>Data Atualização</th>
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
    
jQuery.extend(jQuery.fn.dataTableExt.oSort, {
    "date-br-pre": function(a) {
        
        var frDatea = a.split(',');
        var frTimea = frDatea[1].trim().split(':');
        var frDatea2 = frDatea[0].split('/');
        
        return (frDatea2[2] + frDatea2[1] + frDatea2[0] + frTimea[0] + frTimea[1]) * 1;
    },
    "date-br-asc": function(a, b) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },
    "date-br-desc": function(a, b) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
});


    var tabelaEmpresas = $('#tabelaEmpresas').DataTable({
        /* scrollY: true,
        scrollX: true, */
        /* scroller: false, */
        responsive: true,
        ordering: true,
        paging: true,
        searching: true,
        info: true,
        order: [[ 10, 'desc' ]],
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
        columnDefs: [{
            type: "date-br",
            targets: [9, 10]
        }],
        ajax:{
            url: '<?= site_url('empresas_logistica/buscarEmpresas') ?>',
            type: 'POST',
            data: {idEmpresa: ''},
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    tabelaEmpresas.clear().draw();
                    tabelaEmpresas.rows.add(data.results).draw();

                }
            },
        },

        columns: [
            { data: 'id' },
            { data: 'nomeEmpresa' },
            { data: 'cpfCnpj',render: function (data) { 
                                    if (data.length === 11) { 
                                        return data.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4"); 
                                    } else if (data.length === 14) { 
                                        return data.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5"); 
                                    } else { 
                                    return data; 
                                    }
                            }   
            },
            { data: 'cep' },
            { data: 'rua' },
            { data: 'bairro' },
            { data: 'cidade' },
            { data: 'complemento' },
            { data: 'estado' },
            { data: 'dataCadastro', render: function(data){return new Date(data).toLocaleString();} },
            { data: 'dataAtualizacao', render: function(data){return new Date(data).toLocaleString();} },
            { data: 'status' },
            {
				data:{  'id':'id', 'nomeEmpresa': 'nomeEmpresa', 'cpfCnpj': 'cpfCnpj',
                        'cep': 'cep', 'rua': 'rua', 'bairro': 'bairro', 'cidade': 'cidade', 'complemento': 'complemento', 'estado': 'estado'},
				orderable: false,
				render: function (data) {
					return `
					<button 
						class="btn fa fa-pencil-square-o"
						title="Editar Empresa"
						style="width: 38px; margin: 0 auto; background-color: #04acf4; color: white;"
						id="btnEditarEmpresa"
                        onClick="javascript:editEmpresa(this,'${data['id']}', '${data['nomeEmpresa']}', '${data['cpfCnpj']}',
                                                        '${data['cep']}', '${data['rua']}', '${data['bairro']}', '${data['cidade']}', '${data['complemento']}', '${data['estado']}')">
					</button>
                    <button
                        class="btn fa fa-exchange"
                        title="Alterar Status"
                        style="width: 38px; margin: 0 auto; background-color: ${data['status'] === 'Ativo' ? 'red' : 'green'}; color: white;"
                        id="btnAlterarStatusEmpresa"
                        onClick="javascript:alterarStatusEmpresa(this,'${data['id']}', '${data['status']}', '${data['nomeEmpresa']}')">
                    </button>
                    `;
				}
			}
        ]
    });
    
    $('#abrirModalInserir').click(event=> {
        $('#modalCadEmpresa').modal('show')
        $('#ufEmpresa').val(0)
        $('#nomeEmpresa').val('')
        $('#cpfCnpjEmpresa').val('')
        $('#cepEmpresa').val('')
        $('#ruaEmpresa').val('')
        $('#bairroEmpresa').val('')
        $('#cidadeEmpresa').val('')
        $('#complementoEmpresa').val('')
    })
    
    $('#btnSalvarCadastroEmpresa').click(event=> {
        var nome = $('#nomeEmpresa').val();
        var cpfCnpj = $('#cpfCnpjEmpresa').val();
        var cep = $('#cepEmpresa').val();
        var rua = $('#ruaEmpresa').val();
        var bairro = $('#bairroEmpresa').val();
        var cidade = $('#cidadeEmpresa').val();
        var uf = $('#ufEmpresa').val();
        var complemento = $('#complementoEmpresa').val();
    
        $.ajax({
            url: '<?= site_url('empresas_logistica/inserirEmpresa') ?>',
            type: 'POST',
            data: { nome: nome,
                    registro: cpfCnpj,
                    cep: cep,
                    rua: rua,
                    bairro: bairro,
                    cidade: cidade,
                    uf: uf,
                    complemento: complemento
                    },

            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    alert(data.dados.mensagem)
                    $('#modalCadEmpresa').modal('hide')
                    $('#nomeSetor').val('');
                    $('#cpfCnpjEmpresa').val('');
                    $('#cepEmpresa').val('');
                    $('#ruaEmpresa').val('');
                    $('#bairroEmpresa').val('');
                    $('#cidadeEmpresa').val('');
                    $('#ufEmpresa').val('');
                    $('#complementoEmpresa').val('');

                    tabelaEmpresas.ajax.reload();

                }
                else if(data.status === 400 && data.dados.mensagem){
                    alert(data.dados.mensagem)
                }else{
                alert('Preencha os campos')
                }
            }
        })
        
    })

    function editEmpresa(botao, id, nomeEmpresa, cpfCnpj, cep, rua, bairro, cidade, complemento, estado){
        $('#modalEditEmpresa').modal('show');
        $('#idEmpresaEditar').val(id);
        $('#nomeEmpresaEditar').val(nomeEmpresa);
        $('#cpfCnpjEmpresaEditar').val(cpfCnpj);
        $('#cepEmpresaEditar').val(cep);
        $('#ruaEmpresaEditar').val(rua);
        $('#bairroEmpresaEditar').val(bairro);
        $('#cidadeEmpresaEditar').val(cidade);
        $('#complementoEmpresaEditar').val(complemento);
        $('#ufEmpresaEditar').val(estado);
    }   

    $('#btnSalvarEditEmpresa').click(event=> {
        var nomeEmpresa = $('#nomeEmpresaEditar').val();
        var idEmpresa = $('#idEmpresaEditar').val();
        var cpfCnpj = $('#cpfCnpjEmpresaEditar').val();
        var cep = $('#cepEmpresaEditar').val();
        var rua = $('#ruaEmpresaEditar').val();
        var bairro = $('#bairroEmpresaEditar').val();
        var cidade = $('#cidadeEmpresaEditar').val();
        var uf = $('#ufEmpresaEditar').val();
        var complemento = $('#complementoEmpresaEditar').val();

        $.ajax({
            url: '<?= site_url('empresas_logistica/editarEmpresa') ?>',
            type: 'POST',
            data: { idEmpresa: idEmpresa,
                    nome: nomeEmpresa,
                    registro: cpfCnpj,
                    cep: cep,
                    rua: rua,
                    bairro: bairro,
                    cidade: cidade,
                    uf: uf,
                    complemento: complemento
                    },
            dataType: 'json',
            success: function(data){
                if (data.status === 200){
                    alert(data.dados.mensagem)
                    $('#modalEditEmpresa').modal('hide')
                    $('#nomeEmpresaEditar').val('');
                    $('#cpfCnpjEmpresaEditar').val('');
                    $('#cepEmpresaEditar').val('');
                    $('#ruaEmpresaEditar').val('');
                    $('#bairroEmpresaEditar').val('');
                    $('#cidadeEmpresaEditar').val('');
                    $('#ufEmpresaEditar').val('');
                    $('#complementoEmpresaEditar').val('');
                    $('#idEmpresaEditar').val('');

                    tabelaEmpresas.ajax.reload();
    
                }else if(data.status === 400 && data.dados.mensagem){
                    alert(data.dados.mensagem)
                }else if(data.status === 400 && data.dados.erro){
                    alert(data.dados.erro +'. Campo: '+ data.dados.erro)
                }else{
                    alert('Dados inválidos')
                }
            }
        })
    })

    $("#cepEmpresa").blur(function(){
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#ruaEmpresa").val(dadosRetorno.logradouro);
				$("#bairroEmpresa").val(dadosRetorno.bairro);
				$("#cidadeEmpresa").val(dadosRetorno.localidade);
				$("#ufEmpresa").val(dadosRetorno.uf);
			}catch(ex){}
		});
		
	});

    $("#cepEmpresaEditar").blur(function(){
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#ruaEmpresaEditar").val(dadosRetorno.logradouro);
				$("#bairroEmpresaEditar").val(dadosRetorno.bairro);
				$("#cidadeEmpresaEditar").val(dadosRetorno.localidade);
				$("#ufEmpresaEditar").val(dadosRetorno.uf);
			}catch(ex){}
		});
		
	});

    $('#cepEmpresa').mask('99999-999');
    $('#cepEmpresaEditar').mask('99999-999');

    $(document).ready(function() {
        $('#cpfCnpjEmpresa').inputmask({  
        mask: ['999.999.999-99', '99.999.999/9999-99'],
        keepStatic: true
        });
    });

    $(document).ready(function() {
        $('#cpfCnpjEmpresaEditar').inputmask({  
        mask: ['999.999.999-99', '99.999.999/9999-99'],
        keepStatic: true
        });
    });

 
    function alterarStatusEmpresa(botao, id, status){
        if(confirm('Clique em OK para confirmar a alteração de status ou em Cancelar para voltar')){
            if(status == 'Ativo'){
                status = 0
            }else{
                status = 1
            }
            $.ajax({
                url: '<?= site_url('empresas_logistica/alterarStatusEmpresa') ?>',
                type: 'POST',
                data: { idEmpresa: id,
                        status: status
                        },
                dataType: 'json',
                success: function(data){
                    if (data.status === 200){
                        alert(data.dados.mensagem)
                        tabelaEmpresas.ajax.reload();
                    }else if(data.dados){
                        alert(data.dados.mensagem)
                    }else{
                        alert("Erro ao alterar Status!")
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
