<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<br>
<div class="containner">
	<div class="col-lg-12">
	<div class="containner">
		
		<?php if ($this->auth->is_allowed_block('cad_folhetos')): ?>
		<div class="btn-group pull-right">
			<a class="btn btn-primary" id="btn_add_folheto"><i class="fa fa-plus">&nbsp;&nbsp;<?= lang('novo_folheto')?></i></a>
		</div>
		<?php endif; ?>
		<h2 class="TitPage"><?= lang('folhetos') ?></h2>
		
	</div>

	<div class="folheto-alert alert" style="display:none; margin-bottom:-20px!important;">
		<button type="button" class="close" onclick="fecharMensagem('folheto-alert')">
			<span aria-hidden="true">&times;</span>
		</button>
		<span id="msgfolheto"></span>
	</div>

	<div class="containner">
		<div class="tab-pane" id="tab_folhetos">
			<table id="dt_folhetos" class="table table-striped table-bordered">
				<thead>
					<tr class="tableheader">
						<th style='width: 20%;'><?= lang('arquivos')?></th>
						<th style='width: 60%;'><?= lang('descricao')?></th>
						<th style='width: 15%;'><?= lang('acoes')?></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>

	</div>	
	</div>

</div>

<div id="modal_novo_folheto" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4 class="modal-title"><?= lang('novo_folheto') ?></h4>
      </div>

      <div class="modal-body">
	  
	<form class="form-horizontal" method="post" name="formcontato" id="formcontato"  enctype="multipart/form-data" >
    	<div class="box-body">
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Descrição</label>
    			<div class="col-sm-6">
    				<input type="text" class="form-control" name="descricao" id="descricao" required>
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="arquivo" class="col-sm-2 control-label">Folheto</label>
				<div class="col-sm-6">
				<label class="btn btn-block btn-info">
                <i class="fa fa-upload"></i>&nbsp;&nbsp;Importar arquivo
                <input type="file" id="btn_upload_arquivo" accept="image/*,.pdf" style="display: none;" required>
			  </label>
			  </div>
			  <input id="arquivo_folheto" name="arquivo_folheto" hidden>
              <span class="help-block"></span>
				<!-- <div class="col-sm-6">
    				<input type="file" name="arquivo" id="arquivo" accept="image/*,.pdf" class="form-control" data-buttonText="Arquivo">
    			</div> -->
    		</div>
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<button type="submit" id="enviar" id="btn_save" class="btn btn-primary btn-small" value="Enviar"><i class="fa fa-save"></i>&nbsp;&nbsp;Enviar</button>
						<button class="btn btn-default" id="btn_voltar"> Voltar</button>
						<span class="help-block"></span>
        			</div>
        		</div>
    		</div>
    	</div>
	</form>

      </div>
    </div>
  </div>
</div>

<div id="modal_edit_folheto" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4 class="modal-title"><?= lang('editar_folheto') ?></h4>
      </div>

      <div class="modal-body">
	  
	<form class="form-horizontal" method="post" name="formedit" id="formedit"  enctype="multipart/form-data" >
		
	
	<div class="box-body">


			<div class="form-group">
    			<label for="folheto_id" class="col-sm-2 control-label">ID</label>
    			<div class="col-sm-6">
					<input id="folheto_id" name="folheto_id" disabled>
    			</div>
    		</div>
			
    		<div class="form-group">
    			<label for="descricao_edit" class="col-sm-2 control-label">Descrição</label>
    			<div class="col-sm-6">
    				<input type="text" class="form-control" name="descricao_edit" id="descricao_edit" required>
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="arquivo" class="col-sm-2 control-label">Folheto</label>
				<div class="col-sm-6">
				<label class="btn btn-block btn-info">
                <i class="fa fa-upload"></i>&nbsp;&nbsp;Importar arquivo
                <input type="file" id="btn_upload_arquivo_edit" accept="image/*,.pdf" style="display: none;">
			  </label>
			  </div>
			  <!-- hidden -->
			  <input id="arquivo_folheto_edit" name="arquivo_folheto_edit" hidden>
              <span class="help-block"></span>
				<!-- <div class="col-sm-6">
    				<input type="file" name="arquivo" id="arquivo" accept="image/*,.pdf" class="form-control" data-buttonText="Arquivo">
				</div> -->
				<input id="name_arquivo_folheto_edit" name="name_arquivo_folheto_edit" hidden>
    		</div>
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<button type="submit" id="enviar_edit" id="btn_save_edit" class="btn btn-primary btn-small" value="Enviar"><i class="fa fa-save"></i>&nbsp;&nbsp;Enviar</button>
						<button class="btn btn-default" id="btn_voltar_edit"> Voltar</button>
						<span class="help-block"></span>
        			</div>
        		</div>
    		</div>
    	</div>
	</form>

      </div>
    </div>
  </div>
</div>



<div id="remove_folheto" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1"><?=lang('excluir_folheto')?></h3>
            </div>
            <div class="modal-body">
				<h5><?=lang('pergunta_excluir_folheto')?></h5>
	        </div>
			<div class="modal-footer">
				<button class="btn btn-default" id="btnNao"><?=lang('nao')?></button>
                <button class="btn btn-primary" id="btnSim"><?=lang('sim')?></button>
            </div>
        </div>
    </div>
</div>



<script>
	
	$(document).ready(function() {
		
		//abre modal para novo folheto
		$("#btn_add_folheto").click(function(){
			$('#formcontato')[0].reset();
			$("#modal_novo_folheto").modal();
		});

		//abre modal de excluir folheto
		$(document).on('click', '.btn-del-folheto', function(e){
			e.preventDefault();
			var botao = $(this);
			$('#btnSim').attr('data-id', botao.attr('data-folheto_id'));
			//$('#btnSim').attr('data-tipo', botao.attr('data-tipo'));
			$('#remove_folheto').modal();
		});
		
		//abre modal de editar folheto
		$(document).on('click', '.btn-edit-folheto', function(e){
			e.preventDefault();
			// var botao = $(this);
			// $('#btnSim').attr('data-id', botao.attr('data-folheto_id'));
			// //$('#btnSim').attr('data-tipo', botao.attr('data-tipo'));
			// $('#remove_folheto').modal();
			$.ajax({
					type: "POST",
					url: "<?=site_url('/ashownet/ajax_folhetos_data')?>",
					dataType: "json",
					data: {"folheto_id": $(this).attr("folheto_id")},
					success: function(response) {
						clearErrors();
						$("#formedit")[0].reset();
						$.each(response["input"], function(id, value) {
							$("#"+id).val(value);
						});
						$("#modal_edit_folheto").modal();
					}
				})
		});


		$(document).on('click', '#btnSim', function(e){
			e.preventDefault();

			var botao = $(this);
			var id = botao.attr('data-id');

			$.ajax({
				url : "<?= site_url('cadastros/excluir_folheto').'/' ?>"+id,
				type : 'POST',
				beforeSend: function(){
					botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Excluindo...');
				},
				success : function(callback){
					var data = JSON.parse(callback);
					if (data.success) {
						dt_folhetos.ajax.reload(null, false);

						$('#msgfolheto').html('<div class="alert alert-success">'+data.msg+'</div>');
					}else {
						$('#msgfolheto').html('<div class="alert alert-danger">'+data.msg+'</div>');
					}
				},
				error : function () {
					$('#msgfolheto').html('<div class="alert alert-danger">'+lang.tente_mais_tarde+'</div>');
				},
				complete: function () {
					$('.folheto-alert').css('display', 'block');
					botao.attr('disabled', false).html(lang.sim);
					$('#remove_folheto').modal('hide');
				}
			});

		});

		$("#formcontato").submit(function() {

			$.ajax({
				type: "POST",
				url: "<?= site_url('ashownet/ajax_save_folheto') ?>",
				dataType: "json",
				data: $(this).serialize(),
				beforeSend: function() {
					clearErrors();
					$("#btn_save").siblings(".help-block").html("<i class='fa fa-circle-o-notch fa-spin'></i>&nbsp;Enviando...");
				},
				success: function(response) {
					clearErrors();
					if (response["status"]) {
						$("#modal_novo_folheto").modal("hide");
						swal("Sucesso!","Arquivo salvo com sucesso!", "success");
						dt_folhetos.ajax.reload();
					} else {
						showErrorsModal(response["error_list"])
					}
				}
			})

			return false;
			});

			$("#formedit").submit(function() {

				var folheto_id = document.getElementById("folheto_id").value;
				var descricao_edit = document.getElementById("descricao_edit").value;
				var arquivo_folheto_edit = document.getElementById("arquivo_folheto_edit").value;
				var name_arquivo_folheto_edit = document.getElementById("name_arquivo_folheto_edit").value;
				$.ajax({
					type: "POST",
					url: "<?= site_url('ashownet/ajax_edit_folheto') ?>",
					dataType: "json",
					data: {"folheto_id": folheto_id, "descricao_edit": descricao_edit, "arquivo_folheto_edit": arquivo_folheto_edit},
					beforeSend: function() {
						clearErrors();
						$("#btn_save").siblings(".help-block").html("<i class='fa fa-circle-o-notch fa-spin'></i>&nbsp;Enviando...");
					},
					success: function(response) {
						clearErrors();
						if (response["status"]) {
							$("#modal_edit_folheto").modal("hide");
							swal("Sucesso!","Arquivo salvo com sucesso!", "success");
							dt_folhetos.ajax.reload();
						} else {
							showErrorsModal(response["error_list"])
						}
					}
				})

				return false;
			});




		$(document).on('click', '#btnNao', function(e){
			e.preventDefault();
			$('#remove_folheto').modal('hide');
		});
		$(document).on('click', '#btn_voltar', function(e){
			e.preventDefault();
			$('#modal_novo_folheto').modal('hide');
		});
		$(document).on('click', '#btn_voltar_edit', function(e){
			e.preventDefault();
			$('#modal_edit_folheto').modal('hide');
		});
		


		var dt_folhetos = $("#dt_folhetos").DataTable({
			"autoWidth": false,
			"processing": true,
			"serverSide": true,
			"searching": false,
			"paging": false,
			"info": false,
			"ordering": false,
			"ajax": {
				"url": "<?=site_url('/ashownet/ajaxListFolhetos')?>",
				"type": "POST",
			},
			"drawCallback": function() {
			}
		});

		function clearErrors() {
			$(".has-error").removeClass("has-error");
			$(".help-block").html("");
		}

		function showErrors(error_list) {
			clearErrors();

			$.each(error_list, function(id, message) {
				$(id).parent().parent().addClass("has-error");
				$(id).parent().siblings(".help-block").html(message)
			})
		} 

		function showErrorsModal(error_list) {
			clearErrors();

			$.each(error_list, function(id, message) {
				$(id).parent().parent().addClass("has-error");
				$(id).siblings(".help-block").html(message)
			})
		} 

		$("#btn_upload_arquivo").change(function() {
			uploadArq($(this), $("#arquivo_folheto"));
		});
		$("#btn_upload_arquivo_edit").change(function() {
			uploadArqEdit($(this), $("#arquivo_folheto_edit"));
		});


		function loadingImg(message="") {
			return "<i class='fa fa-circle-o-notch fa-spin'></i>&nbsp;" + message
		}


		function uploadArq(input_file, input_path) {

			folh_file = input_file[0].files[0];
			form_data = new FormData();
			form_data.append("folheto_file", folh_file);
			$.ajax({
				url: "<?=site_url('/ashownet/ajax_import_arq_folheto')?>",
				dataType: "json",
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: "POST",
				beforeSend: function() {
					clearErrors();
					input_path.siblings(".help-block").html("<i class='fa fa-circle-o-notch fa-spin'></i>&nbsp;Enviando...");
				},
				success: function(response) {
					clearErrors();
					if (response["status"]) {
						//arq.attr("src", response["arq_path"]);
						swal("Arquivo carregado com sucesso!");
						input_path.val(response["arq_path"]);
					} else {
						//arq.attr("src", src_before);
						input_path.siblings(".help-block").html(response["error"]);
					}
				},
				error: function() {
					//arq.attr("src", src_before);
				}
			})

		}
		function uploadArqEdit(input_file, input_path) {
			
			var name_arquivo_folheto_edit = document.getElementById("name_arquivo_folheto_edit").value
			folh_file = input_file[0].files[0];
			form_data = new FormData();

			form_data.append("folheto_file", folh_file);

			$.ajax({
				url: "<?=site_url('/ashownet/ajax_delete_folheto_folder')?>",
				dataType: "json",
				type: "POST",
				data: {"name_arquivo_folheto_edit":name_arquivo_folheto_edit},
				beforeSend: function() {
					clearErrors();
					input_path.siblings(".help-block").html("<i class='fa fa-circle-o-notch fa-spin'></i>&nbsp;Enviando...");
				},
				success: function(response) {
					clearErrors();
					if (!response["status"]) {
						input_path.siblings(".help-block").html(response["error"]);
					}
					$.ajax({
				url: "<?=site_url('/ashownet/ajax_import_arq_folheto')?>",
				dataType: "json",
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: "POST",
				beforeSend: function() {
					clearErrors();
					input_path.siblings(".help-block").html("<i class='fa fa-circle-o-notch fa-spin'></i>&nbsp;Enviando...");
				},
				success: function(response) {
					clearErrors();
					if (response["status"]) {
						//arq.attr("src", response["arq_path"]);
						swal("Arquivo carregado com sucesso!");
						input_path.val(response["arq_path"]);
					} else {
						//arq.attr("src", src_before);
						input_path.siblings(".help-block").html(response["error"]);
					}
				},
				error: function() {
					//arq.attr("src", src_before);
				}
			})

				},
				error: function() {
					//arq.attr("src", src_before);
				}
			})


			

		}
		
	});
</script>