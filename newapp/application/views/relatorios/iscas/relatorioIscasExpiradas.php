<div class="row justify-content-center mb-1">
    <div class="col-sm-12">
        <h2 ><?= $titulo?></h2>
    </div>    
</div>
<div class="row justify-content-center">
    <div class="col-sm-12">
        <div class="well well-sm" style="background-color: #b3f0ff; border-color:#b3f0ff">
            <h5>Informe um intervalo de, no máximo, <b>31 dias</b>.</h5>
        </div>
    </div>
</div>

<div class="row justify-content-center m-0">
    <div class="col-sm-12 px-0">
        <div class="well">
            <form id="form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" title="Início Cadastro"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                            <input type="date" class="form-control" id="dataInicio" name="dataInicio"  aria-describedby="basic-addon1" value="<?= date('Y-m-d')?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" title="Fim Cadastro"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                            <input type="date" class="form-control" id="dataFim" name="dataFim" aria-describedby="basic-addon1" value="<?= date('Y-m-d')?>" >
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group" >
                            <span class="input-group-addon" id="basic-addon1" title="Cliente"><i class="fa fa-users" aria-hidden="true"></i></span>
                            <select class="form-control" name="tipo" id="tipo" >
                                <option selected value>Todos</option>
                                <option value="0">Nome</option>
                                <option value="1">CPF</option>
                                <option value="2">CNPJ</option>
                                <option value="3">ID</option>
                            </select>
                        </div>
                    </div>
                    <div id="tipos">
                        <div class="col-sm-4 form-group" id="tipo_0" hidden>
                            <select class="form-control" id="tipo_nome" name="tipo_nome" style="width: 100%;"></select>
                        </div>
                        <div class="col-sm-4 form-group" id="tipo_1" hidden>
                            <input type="text" class="form-control" id="tipo_cpf" name="tipo_cpf" placeholder="Digite o CPF do cliente">
                        </div>
                        <div class="col-sm-4 form-group" id="tipo_2" hidden>
                            <input type="text" class="form-control" id="tipo_cnpj" name="tipo_cnpj" placeholder="Digite o CNPJ do cliente">
                        </div>
                        <div class="col-sm-4 form-group" id="tipo_3" hidden>
                            <input type="number" class="form-control" id="tipo_id" name="tipo_id" placeholder="Digite o ID do cliente">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                        <button type="subimit" id="gerarRelatorioIscasExpiradas" class="btn btn-primary">
                            Gerar Relatório
                        </button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

<div class="row justify-content-center" id="row_tabela" hidden>
    <div class="col-sm-12">
        <table id="tabela" class="table table-hover table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>Serial</th>
                    <th>Descrição</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Data Expiração</th>
                    <th>Cliente</th>
                    <th>Contrato</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>    
</div>

<!-- helper iscas -->
<script type="text/javascript" src="<?= base_url("media/js/helpers/iscas-helper.js") ?>"></script>

<script>
$(document).ready(function(){
    $('#tipo').change(function(){
        controlSelectsTipo($(this).val());
        limparInputs();
    });

    // máscaras cpf e cpnk
    $("#tipo_cpf").mask('000.000.000-00', {reverse: true});
    $("#tipo_cnpj").mask('00.000.000/0000-00', {reverse: true});

    // Exibe inputs de acordo com o tipo de pesquisa
    function controlSelectsTipo(tipo){
        
        if(tipo != ''){
            for(let i = 0; i <= 3; i++){
                if(tipo != i) $("#tipo_"+i).hide();
                else $("#tipo_"+i).show();
            }
        }else{
            for(let i = 0; i <= 3; i++) $("#tipo_"+i).hide();
        }
    }
    // limpa inputs
    function limparInputs(){
        $("#tipo_nome").val("").trigger('change');
        $("#tipo_cpf").val("");
        $("#tipo_cnpj").val("");
        $("#tipo_id").val("");
    }
    $('#tipo_nome').select2({
        ajax: {
            url: '<?= site_url('clientes/ajaxListSelect') ?>',
            dataType: 'json',
            delay: 1000,
        },
        placeholder: "Selecione o nome do cliente",
        allowClear: true,
        minimumInputLength: 3,
    });

    let table = $("#tabela").DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: lang.datatable,
        lengthChange: false,
        dom: 'Blfrtip',
        columns: [
            {"data": "serial"},
            {"data": "descricao"},
            {"data": "modelo"},
            {"data": "marca"},
            {
                "data": "data_expiracao",
                "render": function(callback){
                    return formatDateTime(callback);
                }
            },
            {"data": "nome_cliente"},
            {"data": "id_contrato"},
        ],
        buttons: [
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'excel',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'csv',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                
            },
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                text: 'Imprimir',
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            },
        ],
    });

    $("#form").submit(function(event) {
        event.preventDefault();
        let button = $("#gerarRelatorioIscasExpiradas");
        let data = getDadosForm();

        if(validarDados(data)){
            $.ajax({
                url: '<?= site_url("iscas/isca/ajaxIscasExpiradas")?>',
                type: 'POST',
                dataType: 'json',
                data: data,
                beforeSend: function(){
                    button.attr('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> Gerando...');
                    table.clear().draw();
                    $("#row_tabela").hide();
                },
                success: function(callback){
                    button.attr('disabled',false).html('Gerar Relatório');
                    
                    if(callback.status){
                        table.rows.add(callback.dados).draw();
                        $("#row_tabela").show();
                    }else{
                        alert('Não foi possível gerar o relatório! Tente novamente mais tarde.');
                    }

                },
                error: function(error){
                    console.error(error);
                    button.attr('disabled',false).html('Gerar Relatório');
                    alert('Não foi possível gerar o relatório! Tente novamente mais tarde.');
                }
            });
        }
    });

    function getDadosForm(){
        let resposta = {
            inicio : $("#dataInicio").val(),
            fim : $("#dataFim").val(),
        }
        let tipo = $('#tipo').val();
        switch (tipo) {
            case "0":
                resposta.id = $('#tipo_nome').val();
                break;
            case "1":
                resposta.cpf = $('#tipo_cpf').val();
                
                break;
            case "2":
                resposta.cnpj = $('#tipo_cnpj').val();
                break;
            case "3":
                resposta.id = $('#tipo_id').val();
                break;
        
            default:
                break;
        }
        
        return resposta;
    }
    function validarDados(data){
        let tipo = $('#tipo').val();

        if(!validarDatas(data.inicio, data.fim)){
            return false;
        }
        if(!validarDiferençaDatas(data.inicio, data.fim, 31)){
            alert("Intervalo entre datas é maior do que 31 dias.")
            return false;
        }
        switch (tipo) {
            case "0":
                if(data.id == null || data.id == undefined || data.id == ""){
                    alert("Informe o Nome do cliente!");
                    return false;
                }
                break;
            case "1":
                if(data.cpf == null || data.cpf == undefined || data.cpf == ""){
                    alert("Informe o CPF do cliente!");
                    return false;
                }
                break;
            case "2":
                if(data.cnpj == null || data.cnpj == undefined || data.cnpj == ""){
                    alert("Informe o CNPJ do cliente!");
                    return false;
                }
                break;
            case "3":
                if(data.id == null || data.id == undefined || data.id == ""){
                    alert("Informe o ID do cliente!");
                    return false;
                }
                break;
        
            default:
                break;
        }

        return true;
    }
});

</script>