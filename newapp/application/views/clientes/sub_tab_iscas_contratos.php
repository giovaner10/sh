<h3>Iscas do Contrato #<?php echo $id_contrato ?></h3>

<div class="alert alert-warning isca-alert" style="display:none">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span class="mensagem"></span>
</div>

<div class="well well-small" style="height: 30px;">

    <div class="span1 pull-left">
        <a href="<?php echo site_url('contratos/add_isca/' . $id_cliente . '/' . $id_contrato . '/' . $type) ?>" class="btn btn-primary" data-toggle="modal" data-target="#nova_isca" title="Adicionar Isca">
            <i class="icon-plus icon-white"></i>
        </a>
    </div>
</div>

<br>

<div class="row-fluid">
    <div class="span12">
        <table class="table table-bordered table-striped table-responsive" id="iscas">
            <thead>
                <th class="span2">#ID</th>
                <th class="span3">Serial</th>
                <th class="span3">Marca</th>
                <th class="span3">Modelo</th>
                <th class="span3">Status</th>
                <th class="span3">Data de Cadastro</th>
                <th class="span5">Nome</th>
            </thead>
            <tbody>
                
            </tbody>
        </table>
        <!-- <div class="pagination pagination-right">
            <?php echo $this->pagination->create_links() ?>
        </div> -->

    </div>

</div>

<div id="nova_isca" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h3 id="myModalLabel1">Nova Isca - Contrato #<?php echo $id_contrato ?></h3>
    </div>
    <div class="modal-body">
        <p>Carregando...</p>
    </div>
</div>

<!-- <div id="modal_serial" class="modal fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true"></button>
        <h4 id="myModalLabel1">Vincular Veículo - Contrato #<?php echo $id_contrato ?></h4>
    </div>
    <div class="modal-body">
        <p>Carregando...</p>
    </div>
</div> -->

<!-- <div id="modal_editar" class="modal fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true"></button>
        <h4 id="myModalLabel1">Editar Veículo - Contrato #<?php echo $id_contrato ?></h4>
    </div>
    <div class="modal-body">
        <p>Carregando...</p>
    </div>
</div> -->

<!-- <div id="modal_posicao" class="modal fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true"></button>
        <h4 id="myModalLabel1">Posição Veículo</h4>
    </div>
    <div class="modal-body">
        <p>Carregando...</p>
    </div>
</div> -->

<!-- <div id="modal_serial_inativo" class="modal fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true"></button>
        <h4 id="myModalLabel1">Vincular Veículo - Contrato #<?php echo $id_contrato ?></h4>
    </div>
    <div class="modal-body">
        <div class="alert alert-info">
            <h4>Isca INATIVA!</h4>
            <p>Ação aceita apenas para iscas <b>ATIVAS</b>.</p>
        </div>
        <div class="row-fluid">
            <div class="form-actions">
                <a onclick="fecharModal('#modal_serial_inativo');" class="btn fechar">Fechar</a>
            </div>
        </div>
    </div>
</div> -->

<script type="text/javascript">
    var table = $('#iscas').DataTable({
		ajax: {
			url: "<?= site_url('contratos/ajax_get_iscas'); ?>",
			type: 'POST',
            dataType: "JSON",
            data: {
                id_contrato: <?php echo $id_contrato ?>
            }
		},
		columns: [
			{data: 'id'},
			{data: 'serial'},
			{data: 'marca'},
			{data: 'modelo'},
			{data: 'status'},
			{data: 'data_cadastro'},
			{data: 'descricao'},
		],
		// destroy: true,
		// processing: true,
		ordering: false,
        paging: true,
        searching: true,
		oLanguage: {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        }
	});

    $(document).ready(function() {


        // $('body').on('click', '#addIsca', function() {

        //     var id_cliente = $('input[name=id_cliente]').val();
        //     var id_contrato = $('input[name=id_contrato]').val();
        //     var serial = $('input[name=serial]').val();
        //     var marca = $('input[name=marca]').val();
        //     var modelo = $('input[name=modelo]').val();
        //     var status = $('input[name=status]:checked').val();
        //     var descricao = $('textarea[name=descricao]').val();

        //     $('#load').css('display', 'block');
        //     var urlP = $('#add_isca').attr('action');

        //     $.post(urlP, {
        //             id_cliente: id_cliente,
        //             id_contrato: id_contrato,
        //             serial: serial,
        //             marca: marca,
        //             modelo: modelo,
        //             status: status,
        //             descricao: descricao
        //         },

        //         function(cback) {

        //             $('#load').css('display', 'none');
        //             $('.isca-alert').css('display', 'block');
        //             if (cback.success) {
        //                 console.log("cback:" + cback);

        //                 $('span.mensagem').html(cback.msg);
        //                 // $('#iscas tbody').prepend('<tr>' +
        //                 //     '<td>'+cback.veiculo.id+'</td>' +
        //                 //     '<td>'+cback.veiculo.isca+'</td><td style="text-align: center">'+cback.veiculo.status+'</td>'+
        //                 //     '<td>'+cback.veiculo.vincular+'</td>'+
        //                 //     '<td>'+cback.veiculo.posicao+'</td>'+
        //                 //     '</tr>');
        //             } else {
        //                 $('span.mensagem').html(cback.msg);
        //             }
        //             $('#nova_isca').modal('hide');
        //             // $(this).data('modal').$element.removeData();
        //         }, 'json'
        //     );

            
        // });

        $('.modal-backdrop ').click(function() {
            alert('teste');
        })

        // paginação cliente
        $('.pag_cli a').click(function(ev) {
            ev.preventDefault();

            $('#loading').css('display', 'block');
            var urlPag = $(this).attr('href');

            $('#contratos').load(urlPag, function(result) {
                //pane.tab('show');
                $('#loading').css('display', 'none');
            });
        });

        $("#iscas").on("click", ".status", function(e){
            e.preventDefault();
            var botao = $(this);
            controller = $(this).attr('data-controller');

            console.log(controller);
            $.get(controller, function(result) {
                
                if (!result.success) {
                    $('.inativo').button('toggle');
                    alert(result.msg);
                } else {
                    if ($(botao).data('status') == 'ativo') {
                        $(botao).addClass('active btn-success');
                        $(botao).closest('.btn-group').find('button:not(data-status["ativo"])').removeClass('active btn-danger');
                    } else {
                        $(botao).addClass('active btn-danger');
                        $(botao).closest('.btn-group').find('button:not(data-status["inativo"])').removeClass('active btn-success');
                    }
                }
            }, 'json');
        });

        $('.iscas').click(function(e) {
            e.preventDefault();
            $('#loading').css('display', 'block');

            url = $(this).attr('href');

            $('#contratos').load(url, function(result) {
                //pane.tab('show');
                $('#loading').css('display', 'none');
            });
        });

        $('#remove_filtro').click(function(e) {
            e.preventDefault();

            $('#loading').css('display', 'block');
            var urlPag = $(this).attr('href');

            $('#contratos').load(urlPag, function(result) {
                $('#loading').css('display', 'none');
            });
        });

        $('#busca_veiculo').submit(function(e) {
            e.preventDefault();
            $('#loading').css('display', 'block');
            input = $('input[name=serial_pesq]');
            controller = input.attr('data-controller');
            url = controller + '/' + input.val();

            $.post(url, {
                serial_pesq: input.val()
            }, function(resultado) {
                $('#contratos').html(resultado);
                $('#loading').css('display', 'none');
            });
        });

        $('#modal_serial').attr('data-backdrop', 'static');
    });

    // function selecionarSecretaria(id_veic_contrato,grupo){
    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo site_url('contratos/vincular_secretaria') ?>",
    //         data: {id_veic_contrato:id_veic_contrato,grupo:grupo},
    //         success: function(resposta){
    //             console.log(resposta)
    //         }
    //     });
    // }
</script>