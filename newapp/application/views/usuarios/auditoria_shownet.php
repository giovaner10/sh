<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<style>
    .dt-control {
        background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.shown .dt-control {
        background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
    }

    #tableFuncionarios thead th.no-dt-control {
        background: none !important;
    }

</style>

<h3><?=lang("auditoriashownet")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('suporte')?> > 
    <?=lang('logs')?> > 
    Auditoria Shownet
</div>

<div style="margin-left:55px ;">
    <div class="row">
        <div class="col-md-12">
            <hr>
            <div style="margin-block-end: 134px;">
                <div class="col-md-4" style="margin-left: -16px;">
                    <label>Pesquisar por: <em>(opcional)</em></label>
                    <select id="sel-pesquisa" class="form-control">
                        <option value="" disabled selected>Selecione uma opção</option>
                        <option value="0">Id do registro</option>
                        <option value="1">Id usuario</option>
                        <option value="2">Evento</option>
                        <option value="3">Tabela</option>
                    </select>
                </div>
                <div class="col-md-2 divInput">
                    <input type="text" class="form-control w-100" id="txt-pesquisa" class="form-control" style="margin-top: 25px;" disabled></input>
                </div>
                <div class="col-md-2 divTabela" style="margin-top: 23px;"hidden>
                    <select id='selectTabela' class="form-control" style="margin-top: 21px;">
                        <option></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label style="margin-top: -10px;">Data inicial: <em>(obrigatório)</em></label>
                    <input type="datetime-local" class="form-control w-100" id="txt-data-inicial" class="form-control"></input>
                </div>
                <div class="col-md-2">
                    <label style="margin-top: -10px;">Data final: <em>(obrigatório)</em></label>
                    <input type="datetime-local" class="form-control w-100" id="txt-data-final" class="form-control"></input>
                </div>
                <div class="col-md-2">
                    <button id="btn-pesquisa" class="btn btn-primary" style="margin-top: 25px;">Pesquisar</button>
                </div>
            </div>
        </div>
        <div class="col-md-11">
            <table id="tableFuncionarios" class="table table-bordered table-hover responsive" style="width:100%">
                <thead>
                    <tr>
                        <th class="no-dt-control"></th>
                        <th>Usuario</th>
                        <th>Tabela</th>
                        <th>Id do registro</th>
                        <th>Evento </th>
                        <th>Data da alteração</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {

        let dadosTabelas = <?= $tabelas ?>;
        $("#selectTabela").select2({
            data: dadosTabelas.map(function(item) {
                return {
                    id: item.referencia_tabela,
                    text: item.referencia_tabela  + " - " + item.nome_tabela 
                }
            }),
            placeholder: "Selecione uma tabela",
            allowClear: true,
            width: '100%'
        });

        let dadoslogFuncionarios = <?= $query ?>;
        var table = $("#tableFuncionarios").DataTable({
            data: dadoslogFuncionarios,
            responsive: true,
            searching: true,
            info: true,
            language: lang.datatable,
            columns: [
                {
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                    title: ' ',
                },
                {
                    data: "nome"
                },
                {
                    data: "tabela_alterada",
                },
                {
                    data: "id_registro_alterado"
                },
                {
                    data: "evento"
                },
                {
                    data: "datahora",
                    render: function(data, type, row) {
                        return new Date(data).toLocaleString();
                    }
                },
            ]
        });

        function format(d) {
            d.valor_anterior = d.valor_anterior.replace(/,/g, '<br>');
            d.valor_novo = d.valor_novo.replace(/,/g, '<br>');
            return (
                '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                '<tr>' +
                '<td style="color: red;">Valor anterior:</td>' +
                '<td style="color: red;">' +
                d.valor_anterior +
                '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>Valor Novo:</td>' +
                '<td>' +
                d.valor_novo +
                '</td>' +
                '</tr>' +
                '</table>'
            );
        }
        
        // Evento de clique para expansão/Contração da linha
        $('#tableFuncionarios tbody').on('click', 'td.dt-control', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // Esta linha já está aberta - feche-a
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Abrir esta linha
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });


        $('#sel-pesquisa').change(function() {
            $('#txt-pesquisa').val('');
            if ($(this).val() == '0') {
                $('.divInput').show();
                $('.divTabela').hide();
                $('#txt-pesquisa').attr('placeholder', 'Digite o id do registro');
                $('#txt-pesquisa').prop('disabled', false);

            } else if ($(this).val() == '1') {
                $('.divInput').show();
                $('.divTabela').hide();
                $('#txt-pesquisa').attr('placeholder', 'Digite o nome do usuário');
                $('#txt-pesquisa').prop('disabled', false);

            } else if ($(this).val() == '2') {
                $('.divInput').show();
                $('.divTabela').hide();
                $('#txt-pesquisa').attr('placeholder', 'Digite o tipo do evento');
                $('#txt-pesquisa').prop('disabled', false);
            } else if ($(this).val() == '3') {
                $('.divInput').hide();
                $('.divTabela').show();
            } else {
                $('#txt-pesquisa').prop('disabled', true);
                $('#btn-pesquisa').prop('disabled', true);
            }

        });

        $('#btn-pesquisa').click(function() {
            //seta o spinner
            let dataInicial = $('#txt-data-inicial').val();
            let dataFinal = $('#txt-data-final').val();
            let tipoPesquisa = $('#sel-pesquisa').val();
            let pesquisa = $('#sel-pesquisa').val() == '3' ? $('#selectTabela').val() : $('#txt-pesquisa').val();


            if (tipoPesquisa != null && pesquisa == '') {
                alert('Digite algo para pesquisar');
                return
            }
            if (dataInicial == '' || dataFinal == '') {
                alert('Selecione um perído');
                return
            }


            let dadosPesquisa = [];

            $('#btn-pesquisa').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...');
            $('#btn-pesquisa').prop('disabled', true);
            $.ajax({
                url: '<?= site_url('usuarios/custonAuditoriaShownet') ?>',
                type: 'POST',
                data: {
                    pesquisa: pesquisa,
                    tipoPesquisa: tipoPesquisa,
                    dataInicial: dataInicial,
                    dataFinal: dataFinal
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        dadosPesquisa = data.retorno;
                        $('#tableFuncionarios').DataTable().clear().draw();
                        $('#tableFuncionarios').DataTable().rows.add(dadosPesquisa).draw();
                        $('#btn-pesquisa').html('Pesquisar');
                        $('#btn-pesquisa').prop('disabled', false);
                    } else {
                        alert('Nenhum resultado encontrado');
                        $('#btn-pesquisa').html('Pesquisar');
                        $('#btn-pesquisa').prop('disabled', false);
                    }
                }
            });
        });
    });
</script>