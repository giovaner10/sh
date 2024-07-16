<style>
    .titulo{
        font-size: x-large;
    }
    .dt-buttons{
        padding: 10px 0;
    }
</style>

<link href="<?php echo base_url('newAssets') ?>/css/painelomnilink.css" rel="stylesheet">
<!-- FILTRO CNPJ -->
<div class="row margin_bottom_20">
    <div class="col-md-5 titulo">
        <?= $titulo ?>
    </div>
</div>

<div class="row justify-content-center m-0">
    <div class="col-sm-12 px-0">
        <div class="well">
            <div class="row">
            <form id="form_auditoria">
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" title="Início Cadastro"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                        <input type="date" class="form-control" id="dataInicio" name="dataInicio"  aria-describedby="basic-addon1" value="<?= date('Y-m-d') ?>" >
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" title="Fim Cadastro"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                        <input type="date" class="form-control" id="dataFim" name="dataFim" aria-describedby="basic-addon1" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                
                <div class="col-sm-2" style="float:right">
                    <div class="form-group">
                    <button type="submit" id="btnGerarRelatorio" class="btn btn-primary">
                        Gerar Relatório
                    </button>
                    </div>
                </div>
            </form>
            </div>
            
        </div>
    </div>
</div>

<div class="row" id="row_tabela" hidden>
    <div class="col-md-12">
        <table class="table table-responsive table-bordered" id="tabela">
            <thead>
                <th>ID</th>
                <th>Usuário</th>
                <th>Data</th>
                <th>Tipo</th>
                <th>Campo</th>
                <th>Valor Antigo</th>
                <th>Novo Valor</th>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script src="https://unpkg.com/dayjs@1.8.21/dayjs.min.js"></script>
<script>
    const groupColumn = 0;
    let tabela = $("#tabela").DataTable({
        responsive: true,
        paging: true,
        searching: true,
        info: true,
        language: lang.datatable,
        columns: [
            {data: "id"},
            {data: "email_usuario"},
            {
                data: "data_cadastro",
                render: function(data){
                    return dayjs(data).format('DD/MM/YYYY HH:mm:ss');
                }

            },
            {
                data: "clause",
                render: function(data){
                    switch (data) {
                        case 'insert':
                            return "Cadastro"
                            break;
                        case 'update':
                            return "Atualização"
                            break;
                        case 'delete':
                            return "Remoção"
                            break;
                        default:
                            return ""
                            break;
                    }
                }
            },
            {data: "campo"},
            {data: "valor_antigo"},
            {data: "valor_novo"},
        ],
        columnDefs: [
            { "visible": false, "targets": groupColumn },
            { "width": "20%", "targets": [1,2,3,4,5] }
        ],
        drawCallback: function(settings){
            var api = this.api();
            var rows = api.rows({ page:'current' }).nodes();
            var columns = api.columns(groupColumn, { page:'current' }).data()[0];
            var last = null;
            columns.forEach(function (group, i) {
                let usuario = api.row(i).data().email_usuario;
                if(last !== group){
                    $(rows).eq(i).before(
                        `<tr class="group">
                            <td colspan="8" style="background-color: #ddd"><b>${group} - ${usuario}<b></td>
                        </tr>`
                    )
                    last = group;
                }
            });
        },
        dom: 'Blfrtip',
        buttons: [
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'excel',
                exportOptions: {
                    columns: [1,2,3,4,5,6]
                },
                className: 'btn btn-secondary'
            },
            {
                orientation: 'landscape',
                pageSize: 'LEGAL',
                text: 'PDF',
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [1,2,3,4,5,6]
                },
                customize: function (doc) {
                    doc.defaultStyle.fontSize = 8;
                    doc.styles.tableHeader.fontSize = 10;
                    doc.content[1].table.widths = [ '20%','10%','10%','10%','25%','25%'];
                },
                className: 'btn btn-secondary'
            },
        ],
    });

    $("#form_auditoria").submit(function(event){
        event.preventDefault();

        let btn = $('#btnGerarRelatorio');
        let data = {
            inicio: $("#dataInicio").val(),
            fim: $("#dataFim").val(),
        };
        if(validar_dados(data)){

            tabela.clear();
            $.ajax({
                url: '<?= site_url("PaineisOmnilink/ajax_get_auditoria") ?>',
                type: "POST",
                data: data,
                beforeSend: function(){
                    btn.html('<i class="fa fa-spinner fa-spin"></i> Gerando').attr('disabled', true);
                },
                success: function(data){
                    if(typeof data == 'string'){
                        data = JSON.parse(data);
                    }
                    btn.html('Gerar Relatório').attr('disabled', false);
                    if(data.status = 1){
                        tabela.rows.add(data.dados).draw();
                        $("#row_tabela").show();
                    }else{
                        alert('Não foi possível recuperar os dados!');
                    }
                },
                error: function(erro){
                    btn.html('Gerar Relatório').attr('disabled', false);
                }
            });

        }

    });

    function validar_dados(data) {
        dateIni = new Date(data.inicio + " 00:00:00");
        dateFim = new Date(data.fim + " 23:59:59");
        
        if(dateIni == 'Invalid Date'){
            alert('Informe a data de início válida.')
            return false;
        }
        else if(dateFim == 'Invalid Date'){
            alert('Informe a data final válida.')
            return false;
        }
        else if(validarDatas(data.inicio, data.fim) == false){
            return false;
        }
        else if(validarDiferençaDatas(dateIni, dateFim,30) == false){
            alert('Informe um intervalo de 30 dias.');
            return false;
        }else{
            return true;
        }
    }

    /**
     * Valida datas
     * @param {String} ini 
     * @param {String} fim 
     * @return {boolean}
     */
    function validarDatas(ini, fim){

        let dataInicio = new Date(ini);
        let dataFim = new Date(fim);
        let today = new Date();

        if(ini == "" || fim == ""){
            alert("Infome a data de início e fim do período");
            return false
        }else{
            if(dataInicio > dataFim){
                alert("Informe a data de início anterior ou igual a data final.");
                return false;
            }
            else if(dataInicio > today || dataFim > today){
                alert("Não informe datas futuras");
                return false;
            }else{return true}
        }
    }

    /**
    * Valida a diferença entre duas datas
    * @param {String} dataInicio 
    * @param {String} dataFim 
    * @param {Interger} dias 
    * @return {boolean}
    */

    function validarDiferençaDatas(dataInicio, dataFim, dias) {
        date1 = new Date(dataInicio)
        date2 = new Date(dataFim)
        // Descartando timezone e horário de verão
        var diferenca = Math.abs(date1 - date2); //diferença em milésimos e positivo
        var dia = 1000*60*60*24; // milésimos de segundo correspondente a um dia
        var total = Math.round(diferenca/dia); //valor total de dias arredondado 

        if(total > dias){
            return false;
        }else{
            return true;
        }
    }

</script>