<style type="text/css">
	tbody tr td {
		text-align: center;
	}
</style>
<div class="row">
	<span class="mensagem"></span>
</div>
<div class="row-fluid">
	<div style="margin-bottom:10px;">
		<a href class="btn btn-primary"
			data-toggle="modal" data-target="#nova_secretaria"
			title="Nova Secretaria"> <i class="icon-plus icon-white"></i>Adicionar
		</a>
	</div>

	<div class="tabbable">
		<div class="tab-content">
			<div class="tab-pane active" id="menu1">
				<table id="table_sec" class="table table-striped">
					<thead class="datatable display responsive table-bordered table table-hover">
						<th>#</th>
						<th>Nome</th>
						<th>Status</th>
						<th></th>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<div id="nova_secretaria" class="modal fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true"></button>
		<h3 id="myModalLabel1">Nova Secretaria</h3>
	</div>
	<div class="modal-body">
		<?php echo form_open('', 'id="addSecForm"', array('id_cliente' => $id_cliente))?>
			<div class="row-fluid">
			    <div class="span6">
			        <div class="control-group">
			            <label class="control-label">Nome:</label>
			            <input type="text" name="nome" required />
						<input type="hidden" name="id_cliente" value="<?= $id_cliente?>" />
			        </div>
			    </div>
			</div>
			<div class="row-fluid">
			    <div class="span12">
			        <a id="addSec" class="btn btn-primary">
			            <i class="icon-ok icon-white"></i> Salvar
			        </a>
					<a id="novaSec" class="btn btn-info">Novo Cadastro</a>
			        <a onclick="fecharModal('#nova_secretaria');" class="btn fechar">Fechar</a>
			    </div>
			</div>
		<?php echo form_close()?>
	</div>
</div>

<div id="editar_secretaria" class="modal fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true"></button>
		<h3 id="myModalLabel1">Editar Secretaria #<span id="id_sec"></span></h3>
	</div>
	<div class="modal-body">
		<div id="loadEdit" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div>
		<?php echo form_open('', 'id="editSecForm"', array())?>
			<div class="row-fluid">
			    <div class="span6">
			        <div class="control-group">
			            <label class="control-label">Nome:</label>
						<input id="idSec" type="hidden" name="id" value="" />
			            <input id="nomeSec" type="text" name="nome" value="" required />
			        </div>
			    </div>
			</div>
			<div class="row-fluid">
			    <div class="span12">
			        <a id="editSec" class="btn btn-primary">
			            <i class="icon-ok icon-white"></i> Atualizar
			        </a>
			        <a onclick="fecharModal('#editar_secretaria');" id="fechar_modal" class="btn fechar">Fechar</a>
			    </div>
			</div>
		<?php echo form_close()?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		var tableSec = $('#table_sec').DataTable( {
			responsive: true,
			processing: true,
			order: [0, 'desc'],
			columns: [
				{data: 'id'},
				{data: 'nome'},
				{data: 'status'},
				{data: 'editar'}
			],
			"language": {
				"decimal":        "",
				"emptyTable":     "Nenhum registro encontrado",
				"info":           "Registro _START_ a _END_ de _TOTAL_ registros",
				"infoEmpty":      "0 Registros",
				"infoFiltered":   "(filtered from _MAX_ total entries)",
				"infoPostFix":    "",
				"thousands":      ",",
				"lengthMenu":     "Exibir: _MENU_",
				"loadingRecords": "Carregando...",
				"processing":     "Processando...",
				"search":         "Pesquisar: ",
				"zeroRecords":    "Nenhum registro encontrado",
				"paginate": {
					"first":      "Anterior",
					"last":       "Próxima",
					"next":       "Próxima",
					"previous":   "Anterior"
				}
			}
		});

		$('#novaSec').hide();
		var blockAddSec = false;

		carrega_dados_tabela();

		$('#addSec').click(function () {
			if (blockAddSec == false) {
				blockAddSec = true;
				var button = $(this);
				button.attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
		        var dados = $('#addSecForm').serialize();

		        $.ajax({
		            url: '<?= site_url('clientes/add_secretaria') ?>',
		            type: 'POST',
		            dataType: 'json',
		            data: dados,
		            success: function (callback) {
		                if (callback.status == 'OK') {
							$('#novaSec').show();
		                	button.removeClass('btn-primary')
		                		.addClass('btn-success')
		                		.html('<i class="fa fa-check"></i> Cadastrado :)');

		                   // tableSec.ajax.reload(null, false);
						   carrega_dados_tabela();
		                } else {
		                	blockAddSec = false;
		                    alert(callback.msg);
		                    button.removeAttr('disabled').html('Salvar');
		                }
		            }
		        });
			}

	    });

		$('#novaSec').click(function () {
	    	blockAddSec = false;
	    	$(this).hide();
	    	$('#addSec').removeAttr('disabled').removeClass('btn-success').addClass('btn-primary').html('Salvar');
	    	$('#addSecForm').each (function(){
				this.reset();
			});
	    });

		$(document).on('click', '.edit_sec', function(e) {
	        e.preventDefault();
	        var botao = $(this);
	        var id = $(this).attr('data-id');
			var nome = $(this).attr('data-nome');
			$('#id_sec').html(id);
			$("#nomeSec").val(nome);
			$("#idSec").val(id);

			$('#editSec').click(function () {
				$('#loadEdit').css('display', 'block');
		        var dados = $('#editSecForm').serialize();
				$.ajax({
				   url: '<?= site_url('clientes/ajax_atualiza_nome_secretaria') ?>',
				   type: 'get',
				   dataType: 'json',
				   data: dados,
				   success: function (callback) {
					   if (callback.success) {
						  carrega_dados_tabela();
						  $('#loadEdit').css('display', 'none');
		                  $('#editar_secretaria').modal('hide');
						  $('span.mensagem').html(callback.msg);

					   } else {
						   $('#loadEdit').css('display', 'none');
						   $('span.mensagem').html(callback.msg);
					   }
				   }
			   });

		    });

	    });

		$(document).on('click', '.status', function(e) {
	        e.preventDefault();
	        var botao = $(this);
	        controller = $(this).attr('data-controller');
	        $.get(controller, function(result) {
	            console.log(result);
	            if(result.success) {
					if ($(botao).data('status') == 'ativo') {
					   $(botao).addClass('active btn-success');
					   $(botao).closest('.btn-group').find('button:not(data-status["ativo"])').removeClass('active btn-danger');
				   }else{
					   $(botao).addClass('active btn-danger');
					   $(botao).closest('.btn-group').find('button:not(data-status["inativo"])').removeClass('active btn-success');
				   }

	            } else {
	               alert(result.msg);
	            }
	        }, 'json');
	    });

		function carrega_dados_tabela(){
			$.ajax({
				type: "get",
				dataType: 'json',
				url: "<?= site_url('clientes/list_secretarias') ?>",
				data: {id_cliente: "<?= $id_cliente ?>"},
				success: function(data){
					// Atualiza Tabela
					tableSec.clear();
					tableSec.rows.add(data.table);
					tableSec.draw();
				},
				error: function(data) {
					tableSec.clear();
					tableSec.draw();
				}
			});
		}
	});
</script>
