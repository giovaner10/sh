<style>
    .formatInput {
        height: 30px!important;
    }
    .select2-container--default .select2-selection--single {
        border: 1px solid #333!important;
        border-radius: 0px!important;
        height: 30px!important;
        text-align: Left!important;
    }
    
    
</style>

<div class="row">
    <div class="col-md-12">
        <h3><?=lang('relatorio_custos_consultas')?></h3>
        <div class="col-md-12">            
            <div class="well well-small col-md-12">
                <form style="align-items:center" id="formCustosPerfis">
                    <div class="col-md-3">
                        <i class="fa fa-calendar" style="font-size: 20px;"></i>
                        <input style="width:85%" type="date" id="data_inicial" name="data_inicial" class="data formatInput" min="2021-01-01" max="<?=date('Y-m-d')?>" placeholder="<?=lang('data_inicial')?>" autocomplete="off" id="dp1" value="<?=date('Y-m-d')?>" required />
                        <label class="required"></label>
                    </div>
                    <div class="col-md-3">
                        <i class="fa fa-calendar" style="font-size: 20px;"></i>
                        <input style="width:85%" type="date" id="data_final" name="data_final" class="data formatInput" min="2021-01-01" max="<?=date('Y-m-d')?>" placeholder="<?=lang('data_final')?>" autocomplete="off" id="dp2" value="<?=date('Y-m-d')?>" required />
                        <label class="required"></label>
                    </div>
                    <div class="col-md-3">
                        <i class="fa fa-user" style="font-size: 22px;"></i>
                        <select name="id_cliente" id="id_cliente" class="formatInput" required>
                            <option value=""><?=lang('selecione_cliente')?></option>
                        </select>
                        <label class="required"></label>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btnFormCustosPerfis" > <?=lang('gerar') ?> </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-12">
            <h4 id="totais"></h4>
            <table id="tabelaCustosConsultas" class="table table-bordered table-hover responsive" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th><?= lang('cpf_funcionario') ?></th>
                        <th><?= lang('CNH') ?></th>
                        <th><?= lang('cpf_cnpj_proprietario') ?></th>
                        <th><?= lang('placa_veiculo') ?></th>
                        <th><?= lang('placa_carreta') ?></th>
                        <th><?= lang('usuario') ?></th>
                        <th><?= lang('acao') ?></th>
                        <th><?= lang('data_cadastro') ?></th>
                        <th><?= lang('valor') ?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var tabelaCustosConsultas = false;

    $(document).ready(function () {

        $('#id_cliente').select2({
            ajax: {
                url: '<?= site_url('/relatorios/listAjaxSelectClient') ?>',
                dataType: 'json'
            },
            width: '85%',
            language: idioma
        });

        //INSTANCIA A TABELA DE CUSTOS DE CONSULTAS DE PERFIS
        tabelaCustosConsultas = $('#tabelaCustosConsultas').DataTable( {
            dom: 'Bfrtip',
            responsive: true,
            order: [ 0, 'asc' ],
            columns: [
                { data: 'id' },
                { 
                    data: 'cpf_profissional',
                    render: function(data, type, row, meta) {
                        return criarMascara(data, '###.###.###-##');
                    }
                    
                },
                { data: 'cnh' },
                { 
                    data: 'cpf_cnpj_proprietario',
                    render: function(data, type, row, meta) {
                        if (data) 
                            return data.length == 11 ? criarMascara(data, "###.###.###-##") : criarMascara(data, "##.###.###/####-##");
                        else
                            return null;
                    }
                },
                { data: 'placa_veiculo' },
                { data: 'placa_carreta' },
                { data: 'nome_usuario' },
                {
                    data: 'acao',                    
                    render: function(data, type, row, meta) {
                        if (data === 'cadastro') return "<?=lang('cadastro')?>";
                        else return "<?=lang('consulta')?>";
                    }
                },
                { 
                    data: 'data_cadastro',
                    render: function(data, type, row, meta) {
                        var data = data.split(' ');
                        var dataTemp = data[0].split('-');
                        return `${dataTemp[2]}/${dataTemp[1]}/${dataTemp[0]} ${data[1]}`;
                    }
                },
                { 
                    data: 'valor',
                    render: $.fn.dataTable.render.number( '.', ',', 2),
                }
            ],                     
            buttons: [
                {
                    filename: filenameGenerator(lang.relatorio_custos_consultas),
                    extend: 'pdfHtml5',
                    orientation: customPageExport('tabelaCustosConsultas', 'orientation'),
                    pageSize: customPageExport('tabelaCustosConsultas', 'pageSize'),
                    className: 'btn btn-outline-primary',
                    text: 'PDF',
                    messageTop: function () {
                        var di = $('#data_inicial').val().split('-');
                        var data_ini = `${di[2]}/${di[1]}/${di[0]}`;
                        var df = $('#data_final').val().split('-');
                        var data_fim = `${df[2]}/${df[1]}/${df[0]}`;
                        return $('#totais').text()+' | '+lang.cliente+': '+$('#id_cliente option:selected').text()+' | '+lang.periodo+': '+data_ini+' - '+data_fim;
                    },
                    customize: function ( doc , tes)
                    {
                        let titulo = lang.relatorio_custos_consultas;
                        // Personaliza a página do PDF
                        pdfTemplate(doc, titulo)
                    }
                },
                {     
                    title: lang.relatorio_custos_consultas,               
                    extend: 'excelHtml5',                    
                    orientation: customPageExport('tabelaCustosConsultas', 'orientation'),
                    pageSize: customPageExport('tabelaCustosConsultas', 'pageSize'),
                    className: 'btn btn-outline-primary',
                    text: 'EXCEL',
                    messageTop: function () {
                        var di = $('#data_inicial').val().split('-');
                        var data_ini = `${di[2]}/${di[1]}/${di[0]}`;
                        var df = $('#data_final').val().split('-');
                        var data_fim = `${df[2]}/${df[1]}/${df[0]}`;
                        return $('#totais').text()+' | '+lang.cliente+': '+$('#id_cliente option:selected').text()+' | '+lang.periodo+': '+data_ini+' - '+data_fim;
                    },
                    exportOptions: {
                        format: {
                            body: function (data, row, column, node) {
                                var columnsNumbers = [9];
                                if (columnsNumbers.includes(column)){
                                    if (data)
                                        return data.replace( '.', ';').replace(',','.').replace(';',',');  //deixa no formato 1,254.21
                                }
                                return data;
                            }
                        }
                    }                   
                },                
                {
                    title: lang.relatorio_custos_consultas,
                    extend: 'csvHtml5',                    
                    orientation: customPageExport('tabelaCustosConsultas', 'orientation'),
                    pageSize: customPageExport('tabelaCustosConsultas', 'pageSize'),
                    className: 'btn btn-outline-primary',
                    text: 'CSV',
                    messageTop: function () {
                        var di = $('#data_inicial').val().split('-');
                        var data_ini = `${di[2]}/${di[1]}/${di[0]}`;
                        var df = $('#data_final').val().split('-');
                        var data_fim = `${df[2]}/${df[1]}/${df[0]}`;
                        return $('#totais').text()+' | '+lang.cliente+': '+$('#id_cliente option:selected').text()+' | '+lang.periodo+': '+data_ini+' - '+data_fim;
                    }
                },
                {
                    filename: filenameGenerator(lang.relatorio_custos_consultas),
                    extend: 'print',
                    orientation: customPageExport('tabelaCustosConsultas', 'orientation'),
                    pageSize: customPageExport('tabelaCustosConsultas', 'pageSize'),
                    className: 'btn btn-outline-primary',
                    text: lang.imprimir.toUpperCase(),
                    messageTop: function () {
                        var di = $('#data_inicial').val().split('-');
                        var data_ini = `${di[2]}/${di[1]}/${di[0]}`;
                        var df = $('#data_final').val().split('-');
                        var data_fim = `${df[2]}/${df[1]}/${df[0]}`;
                        return $('#totais').text()+' | '+lang.cliente+': '+$('#id_cliente option:selected').text()+' | '+lang.periodo+': '+data_ini+' - '+data_fim;
                    },
                    customize: function ( win )
                    {
                        let titulo = lang.relatorio_custos_consultas;
                        // Personaliza a página Impressa
                        printTemplate(win, titulo);
                    }

                }
            ],
            language: lang.datatable
        });

    });


    //CARREGA A TABELA COM OS DADOS FILTRADOS
	$('#formCustosPerfis').submit(function(e){
		e.preventDefault();

        var botao = $('.btnFormCustosPerfis');
		var data_form = $(this).serialize();

		$.ajax({
            url: "<?= site_url('/relatorios/ajax_custos_consultas_perfis') ?>",
			data: data_form,
			type: 'POST',
            dataType: 'json',
			beforeSend: function () {
				botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.gerando);
			},
			success: function (retorno) {
				if (retorno.success) {
                    // Atualiza Tabela
                    tabelaCustosConsultas.clear();
                    tabelaCustosConsultas.rows.add(retorno.table);
                    tabelaCustosConsultas.draw();

                    //Atualiza os totais
                    $('#totais').html('<b>' +lang.total_consultas + ':</b> ' + retorno.quantidadeTotal + '  -  <b>' +lang.valor_total + ':</b> ' + retorno.valorTotal);

				} else {
                    //Atualiza os totais
                    $('#totais').html('');

                    //Limpa a tabela
                    tabelaCustosConsultas.clear().draw();

                    alert( retorno.msg );
				}
			},
			error: function(){
                //Atualiza os totais
                $('#totais').html('');

                //Limpa a tabela
                tabelaCustosConsultas.clear().draw();

                alert( lang.operacao_nao_finalizada );        
			},
			complete: function(){
				botao.attr('disabled', false).html(lang.gerar);
			}
		});

	});



</script>