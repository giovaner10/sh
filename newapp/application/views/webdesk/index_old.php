<?php $omnilink = $this->auth->get_login_dados('funcao') ?>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>

<style>
    .btn-finish{
        background: #468847;
        color: white;
        text-shadow: none;
    }
    .btn-finish:hover, .btn-all:active, .btn-all:focus, .btn-all:before{
        background: #2b572b;
        color: white;
    }
    .btn-aguard{
        background: #f89406;
        color: white;
        text-shadow: none;
    }
    .btn-aguard:hover, .btn-all:active, .btn-all:focus, .btn-all:before{
        background: #a26206;
        color: white;
    }
    .btn-all{
        background: #05aefe;
        color: white;
        text-shadow: none;
    }
    .btn-all:hover, .btn-all:active, .btn-all:focus, .btn-all:before{
        background: #0578ad;
        color: white;
    }
    #rankbt {
        margin-top: -36px;
        margin-left: 9px;
    }
    body{
        background: #f5f5f5;
    }
    #well{
        border: none;
        box-shadow: none;
    }
    .blem{
        color: red;
    }
</style>
<h3>Gerenciador de Tickets</h3>
<hr class="featurette-divider">

<div class="placa-alert" style="display:none">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span id="mensagem"></span>
</div>

<!-- NAVEGAÇÃO -->
<ul class="nav nav-tabs">
    <?php if ($omnilink != 'OMNILINK'): ?>
        <li class="<?= $omnilink == 'OMNILINK' ? '' : 'active' ?>"><a data-toggle="tab" href="#home">Show / Norio</a></li>
    <?php endif; ?>
    <li class="<?= $omnilink == 'OMNILINK' ? 'active' : '' ?>"><a data-toggle="tab" href="#menu1">Omnilink</a></li>
</ul>

<div class="tab-content">
    <div id="home" class="tab-pane fade <?= $omnilink == 'OMNILINK' ? '' : 'in active' ?>">
        <div id="well" class="well well-small">
            <a onClick="lista_ticket('0')" title="" class="btn btn-all"> Todos</a>
            <a onClick="lista_ticket('1')" title="Tickets Em Andamento" class="btn btn-aguard"> Em andamento</a>
            <a onClick="lista_ticket('3')" title="Tickets Concluídos" class="btn btn-finish"> Concluídos</a>
            <a href="<?php echo site_url('webdesk/ranking')?>" title="Ranking Abertura de Tickets" class="btn btn-primary"> Ranking</a>
            <button title="Novo Ticket" class="btn btn-primary pull-right novoTicket" data-empresa='NORIO' data-toggle='modal' data-target='#novo_ticket'>
                <i class="icon-plus icon-white"></i>
                 Novo Ticket
            </button>
        </div>
        <br style="clear:both" />

        <div class="container-fluid">
            <table class="table table-bordered table-hover" id="example">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Cliente</th>
                        <th title="Situação">Sit.</th>
                        <th>Usuário</th>
                        <th>Email</th>
                        <th>Depart</th>
                        <th>Assunto</th>
                        <th>Última int</th>
                        <th>Prestadora</th>
                        <th>Status</th>
                        <th title="Ações">Admin</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div id="menu1" class="tab-pane fade <?= $omnilink == 'OMNILINK' ? 'in active' : '' ?>">
        <div id="well" class="well well-small">
            <a onClick="lista_ticket_om('0')" title="" class="btn btn-all"> Todos</a>
            <a onClick="lista_ticket_om('1')" title="Tickets Em Andamento" class="btn btn-aguard"> Em andamento</a>
            <a onClick="lista_ticket_om('3')" title="Tickets Concluídos" class="btn btn-finish"> Concluídos</a>
            <button title="Novo Ticket" class="btn btn-primary pull-right novoTicket" data-empresa='OMNILINK' data-toggle='modal' data-target='#novo_ticket'>
                <i class="icon-plus icon-white"></i>
                 Novo Ticket
            </button>
        </div>
        <br style="clear:both" />

        <div class="container-fluid">
            <table class="table table-bordered table-hover" id="tableOmni">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Cliente</th>
                        <th title="Situação">Sit.</th>
                        <th>Usuário</th>
                        <th>Email</th>
                        <th>Depart</th>
                        <th>Assunto</th>
                        <th>Última int</th>
                        <th>Prestadora</th>
                        <th>Status</th>
                        <th title="Ações">Admin</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- ###### -->

<div class="modal fade" id="modal-ticket">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Abrir Ticket</h3>
    </div>
    <div class="modal-body"></div>
    <div class="modal-footer">
        <button type="button" title="Cancelar" data-dismiss="modal" class="btn">Cancelar</button>
        <button type="button" title="Salvar Ticket" class="btn btn-primary" id="enviar-form-abrir-ticket">Salvar</button>
    </div>
</div>

<!--modal abertura de novo ticket-->
<!-- Modal -->
<div id="novo_ticket" class="modal fade" role="dialog" style="top:5%; width: 46%; height: 90%; left:45%;">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="background-color:#f5f5f5;">
            <div class="modal-body" style="max-height: 510px;">
                <div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 id="myModalLabel">Novo Ticket</h4>
                </div>
            	<div class="col-xs-8 col-xs-offset-2 well-fluid">
            		<form id="ContactForm">
            			<div class="col-xs-12">
            				<div class="input-group">
            					<div class="control-group">
            						<div class="md-form">
            						  	<select class="select_usuario span6" name="id_usuario" data-placeholder="Selecione um Usuário" required >
            								<option value="" disabled selected></option>
            							</select>
            						</div>
            					</div>
            				</div>
            			</div>

            			<div class="col-xs-12">
            				<div class="input-group">
            					<div class="control-group">
            						<div class="md-form">
            							<select id="placa" class="form-control span6" data-placeholder="Selecione as placas" name="placa" required disabled>
            								<option value="" disabled selected>Selecione uma placa</option>
            							</select>
            						</div>
            					</div>
            				</div>
            			</div>

            			<div class="col-xs-12">
            				<div class="input-group">
            					<div class="control-group">
            						<div class="md-form">
            							<input type="text" class="form-control span6" name="assunto" id="assunto" placeholder="Assunto" autocomplete="off" required required />
            						</div>
            					</div>
            				</div>
            			</div>

            			<div class="col-xs-12">
            				<div class="input-group">
            					<div class="control-group">
            						<div class="md-form">
            							<select id="departamento" class="form-control span6" name="departamento" required>
            								<option value="" disabled selected>Selecione o Departamento</option>
            							</select>
            						</div>
            					</div>
            				</div>
            			</div>

            			<div class="col-xs-12">
            				<div class="input-group">
            					<div class="control-group">
            						<div class="md-form">
            							<select type="text" id="prioridade" name="prioridade" data-placeholder="Prioridade" class="form-control span6" required>
            								<option value="" disabled selected>Prioridade</option>
            								<option value="1">Baixa</option>
            								<option value="2">Média</option>
            								<option value="3">Alta</option>
            							</select>
            						</div>
            					</div>
            				</div>
            			</div>

            			<div class="col-xs-12">
            				<div class="msg_caracter"></div>
            			</div>

            			<input type="hidden" name="id_cliente" id="input_id_cliente">
            			<input type="hidden" name="usuario" id="input_usuario">
            			<input type="hidden" name="nome_usuario" id="input_nome_usuario">

            			<div class="col-xs-12">
            				<div class="input-group">
            					<textarea name="descricao" rows="6" placeholder=" Descrição" id="descricao_ticket" class="form-control span6 maxlength" required></textarea>
            				</div>

            				<span class="label" id="content-countdown" style="float:right; color:black" title="500">0</span>
            			</div>

            			<div class="col-xs-12">
            				<div class="input-group">
            					<input type="file" name="arquivo" class="filestyle span6" data-buttonText="Carregar arquivo">
            					<span class="help-block" style="font-size: 11px;">*Formatos suportados: pdf, jpg, png e jpeg</span>
            				</div>
            			</div>
            			<div class="row">
            				<div class="botoes_resposta" style="float: right;">
            					<button type="reset" class="btn"> Limpar</button>
            					<button type="submit" class="btn btn-info" id="salvar-ticket"> Adicionar</button>
            				</div>
            			</div>
            		</form>
            	</div>

            </div>
        </div>

    </div>
</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    var referencia = false;

    var table = $('#example').DataTable( {
        responsive: true,
        serverSide: true,
        ajax: "<?= site_url('webdesk/load_tickets/') ?>",
        columnDefs: [
            {
                targets: '_all',
                className: 'dt-body-center'
            },
            {
                targets: '_all',
                className: 'dt-center'
            }
        ],
        oLanguage: {
            "sEmptyTable": "Nenhum ticket encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ tickets",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 tickets",
            "sInfoFiltered": "(Filtrados de _MAX_ tickets)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ tickets por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum tickets encontrado",
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
        },
        ordering: false
    });

    var table_om = $('#tableOmni').DataTable( {
        responsive: true,
        serverSide: true,
        ajax: "<?= site_url('webdesk/load_tickets/0/omnilink') ?>",
        columnDefs: [
            {
                targets: '_all',
                className: 'dt-body-center'
            },
            {
                targets: '_all',
                className: 'dt-center'
            }
        ],
        oLanguage: {
            "sEmptyTable": "Nenhum ticket encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ tickets",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 tickets",
            "sInfoFiltered": "(Filtrados de _MAX_ tickets)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ tickets por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum tickets encontrado",
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
        },
        ordering: false
    });

    function lista_ticket(value) {
        table.ajax.url( "<?= site_url('webdesk/load_tickets').'/' ?>"+value ).load();
    }

    function lista_ticket_om(value) {
        table_om.ajax.url( "<?= site_url('webdesk/load_tickets').'/' ?>"+value+'/omnilink' ).load();
    }


    //relacionado a adionar um novo ticket ------------------------------------
    $(document).on('click', '.novoTicket', function() {
        referencia =  $(this).attr('data-empresa');
    });

	//preenche as opcoes do select de departamentos
	$(document).ready(function() {
        //o bootstrap está com um problema de esconder os inputs na classe modal fade,por isso é necessário dar um show e um hide
        //provavelmente seria resolvido ao atualizar a versão do bootstrap, mas não é viável por ser código legado
        $("#novo_ticket").modal('show');
        $("#novo_ticket").modal('hide');
        
		var departamentos = "";
		$.ajax({
			url: '<?= site_url('crm_assuntos/listar_assuntos') ?>',
			datatype: 'json',
			success: function (data) {
				var res = JSON.parse(data);
				if (res.status == 'OK') {
					departamentos += '<option value="" disabled selected>Selecione o Departamento</option>';
					$(res.results).each(function (index, value) {
					  departamentos += '<option value=' + value.id_assunto +'>' + value.assunto + '</option>';
					})
					$('#departamento').html(departamentos);
				}
			},
			error: function (error) {
			}
		});

	});

	//busca o usuário
	$('.select_usuario').select2({
		ajax: {
			url: '<?= site_url('usuarios_gestor/get_ajax_usuarios_gestores') ?>',
			dataType: 'json'
		},
		placeholder: "Selecione o Usuario",
		allowClear: true
	});

	//preenche as opcoes do select de placas
	$(document).on('change', '.select_usuario', function () {
		var id_usuario = $(this).val();
		var str = "";
	    $.ajax({
	        url: '<?= site_url('veiculos/lista_placas_usuario') ?>/'+id_usuario,
	        datatype: 'json',
	        success: function (data) {
				var res = JSON.parse(data);
				if (res.status == 'OK') {
					$("#input_id_cliente")[0].value = res.results[0].id_cliente;
					$("#input_usuario")[0].value = res.results[0].usuario;
					$("#input_nome_usuario")[0].value = res.results[0].nome_usuario;
					str += '<option value="" disabled selected>Selecione uma Placa</option>';
					$(res.results).each(function (index, value) {
					  str += '<option value=' + value.placa + '>' + value.placa + '</option>';
					})
					$('#placa').html(str);
				}
	        },
	        error: function (error) {
	        }
	    });

		window.setTimeout(function(){
			document.getElementById('placa').disabled = false;
		}, 3000);
	});

	$(function() {
		var max_ant = 0;
		$(".maxlength").keyup(function(event) {

			var target = $("#content-countdown");
			var max = target.attr('title');
			var len = $(this).val().length;
			var remain = len;

			if (len > max && len > max_ant) {
				$("#descricao_ticket").css("color", "red");
				var tpl = [
					'<div class="alert alert-info">',
					'<button type="button" class="close" data-dismiss="alert">&times;</button>',
					'<strong>Ops! </strong>',
					'Para uma melhor comunicação favor utilizar o limite de 500 caracteres!',
					'</div>'
				].join('');

				$(".msg_caracter").html(tpl);
				$(".msg_caracter").show();
				max_ant = len;

			} else if (len <= max && len < max_ant) {
				$("#descricao_ticket").css("color", "black");
				max_ant = len;
			}

			target.html(remain);

		});
	});

	$("#ContactForm").submit(function() {
		$("#salvar-ticket").attr("disabled", true);
		$("#salvar-ticket").html("<i class='fa fa-spinner fa-spin'></i> Criando Ticket...");

		var formdata = new FormData($("#ContactForm")[0]);
        $('.placa-alert').css('display', 'block');

		$.ajax({
			url: "<?= site_url('webdesk/new_ticket') ?>/"+referencia,
			type: 'POST',
			processData: false,
			contentType: false,
			dataType: 'json',
			data: formdata,
			success: function(callback) {
				if (callback.success){
					$("#ContactForm").trigger('reset');
					$('#placa').trigger("chosen:updated");

                    // var idTicket = callback.idTicket;
                    // var dados = callback.dados;
                    // var cliente = dados.cliente;
                    // var situacao = callback.situacao;
                    // var nome_usuario = dados.nome_usuario;
                    // var email = dados.usuario;
                    // var departamento = callback.departamento;
                    // var assunto = dados.assunto;
                    // var ultima_interacao = dados.ultima_interacao;
                    // var prestadora = callback.prestadora;
                    // var status = dados.status;
                    // var visualizar = dados.visualizar;

                    if (referencia == 'NORIO') {
                        table.ajax.reload(null, true);
                        // table.rows.add(
                        //     [{
                        //         idTicket,
                        //         cliente,
                        //         situacao,
                        //         nome_usuario,
                        //         email,
                        //         departamento,
                        //         assunto,
                        //         ultima_interacao,
                        //         prestadora,
                        //         status,
                        //         visualizar
                        //      }]).draw(null, false);
                    }else {
                        table_om.ajax.reload(null, true);
                        // table_om.rows.add(
                        //     [{
                        //         idTicket,
                        //         cliente,
                        //         situacao,
                        //         nome_usuario,
                        //         email,
                        //         departamento,
                        //         assunto,
                        //         ultima_interacao,
                        //         prestadora,
                        //         status,
                        //         visualizar
                        //      }]).draw(null, false);
                    }
                 //   Me mostra como ta lá na view

				}
                $("#mensagem").html('<div class="alert alert-success"><p><b>'+callback.mensagem+'</b></p></div>');
				$("#salvar-ticket").removeAttr("disabled")
				.html("Adicionar");

                $('#novo_ticket').modal('hide');

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#mensagem").html('<div class="alert alert-danger"><p><b>Não é possível salvar o ticket no momento. Tente novamente mais tarde!</b></p></div>');
				$("#salvar-ticket").removeAttr("disabled")
				.html("Adicionar");

                $('#novo_ticket').modal('hide');
			}
		});

		return false;
	});

</script>
