<style>
	
.ot-produto-item figure figcaption > a {
    z-index: 1000;
    text-indent: 200%;
    white-space: nowrap;
    font-size: 0;
    opacity: 0;
}
.ot-produto-item figure figcaption, .ot-produto-item figure figcaption > a {
    position: absolute;
    top: 0;
    left: 10;
    width: 100%;
    height: 100%;
}
figure.effect-bubba {
	background: #000;
	margin-bottom: 30px;
}

figure.effect-bubba img {
	opacity: 0.8;
	-webkit-transition: opacity 0.35s;
	transition: opacity 0.35s;
}

figure.effect-bubba:hover img {
	opacity: 0.4;
}

figure.effect-bubba figcaption::before,
figure.effect-bubba figcaption::after {
	position: absolute;
	top: 20px;
	right: 20px;
	bottom: 20px;
	left: 20px;
	content: '';
	opacity: 0;
	-webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
	transition: opacity 0.35s, transform 0.35s;
}

figure.effect-bubba figcaption::before {
	border-top: 1px solid #fff;
	border-bottom: 1px solid #fff;
	-webkit-transform: scale(0,1);
	transform: scale(0,1);
}

figure.effect-bubba figcaption::after {
	border-right: 1px solid #fff;
	border-left: 1px solid #fff;
	-webkit-transform: scale(1,0);
	transform: scale(1,0);
}

figure.effect-bubba h2 {
    color: #fff;
    font-size: 15px;
    font-weight: 700;
    text-transform: uppercase;
	padding-top: 30%;
	-webkit-transition: -webkit-transform 0.35s;
	transition: transform 0.35s;
	-webkit-transform: translate3d(0,-20px,0);
	transform: translate3d(0,-20px,0);
}

figure.effect-bubba p {
    color: #fff;
    font-size: 13px;
    font-weight: 500;
	padding: 20px 2.5em;
	opacity: 0;
	-webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
	transition: opacity 0.35s, transform 0.35s;
	-webkit-transform: translate3d(0,20px,0);
	transform: translate3d(0,20px,0);
}

figure.effect-bubba:hover figcaption::before,
figure.effect-bubba:hover figcaption::after {
	opacity: 1;
	-webkit-transform: scale(1);
	transform: scale(1);
}

figure.effect-bubba:hover h2,
figure.effect-bubba:hover p {
	opacity: 1;
	-webkit-transform: translate3d(0,0,0);
	transform: translate3d(0,0,0);
}

.ot-produto-item{
	padding:10px
}


</style>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<br>
<div class="containner">
	<div class="col-lg-12">
		<div class="containner">
			
			<?php if ($this->auth->is_allowed_block('cad_folhetos')): ?>
			<div class="btn-group pull-right">
				<a class="btn btn-primary" id="btn_add_produto"><i class="fa fa-plus">&nbsp;&nbsp;<?= lang('novo_produto')?></i></a>
			</div>
			<?php endif; ?>
			<h2 class="TitPage"><?= lang('produtos') ?></h2>
			
		</div>
		<hr>

		<div class="produto-alert alert" style="display:none; margin-bottom:-20px!important;">
			<button type="button" class="close" onclick="fecharMensagem('produto-alert')">
				<span aria-hidden="true">&times;</span>
			</button>
			<span id="msgproduto"></span>
		</div>

		<div class="containner">
			<div class="row">
				<?php 
				if(!empty($lista_produtos)){
					foreach($lista_produtos as $lista_produto){	?>
							<div class="col-md-2">
								<div class="ot-produto-item">
									<figure class="effect-bubba">
										<img src="<?=base_url("uploads/produtos/$lista_produto->file")?>" alt="" class="img-responsive center-block" style="width: 200px;height: 150px;"/>
												<figcaption style="width: 200px;height: 170px;">
												<a  data-id="<?=$lista_produto->id?>"  class="produto_modal" data-produto="<?=$lista_produto->id_produto?>" >Teste</a>
												</figcaption>
									</figure>
								</div>
							</div>
				<?php	
						}
					} 
				?>

			</div>

		</div>	
	</div>

</div>

<!-- MODAL DE EDIÇÃO DE PRODUTO --> 
<div class="modal fade" id="modalEditProduto" tabindex="-1" role="dialog" aria-labelledby="Modal-label-1">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="X"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="Modal-label-1"></h4>
			</div>

			<div class="modal-body">
				<img src="" alt="img01" id="imgProduct" class="img-responsive center-block" />
			
				<div class="btn-group pull-left">
					<a class="btn btn-primary btn_editar_produto" id="btn_editar_produto" data-id="" data-nome="" data-descricao="" data-acao="mostrarFormEditProdut" style="margin:2px;"><i class="fa fa-edit">&nbsp;&nbsp;<?= lang('editar_produto')?></i></a>
					<a class="btn btn-primary" class="btn_add_arquivo_produto" id="btn_add_arquivo_produto" data-id="" data-acao="mostrarForm" style="margin:2px;"><i class="fa fa-plus">&nbsp;&nbsp;<?= lang('adicionar_arquivo')?></i></a>
					<a class="btn btn-danger" class="btn_del_produto" id="btn_del_produto" data-id=""  style="margin:2px;"><i class="fa fa-times">&nbsp;&nbsp;<?= lang('excluir_produto')?></i></a>
				</div>

				<div  class="col-sm-12" id="divCadNovoArquivo" style="display:none; background-color:#cfd2dc99; margin-bottom:10px">
					<div class="control-group">
						<br>
						<h4>Novo Arquivo</h4>
						<br>
						<form class="form-horizontal" id="formNovoArquivo"  method="post" name="formNovoArquivo"  enctype="multipart/form-data">
							<div class="form-group">
								<div class="col-sm-12">
								<input type="text" class="hidden" id="id_produt_edit" name="id_produt_edit" value=""/>
								<div class="form-group">
									<label for="nome_arq" class="col-sm-2 control-label">Nome</label>
									<div class="col-sm-6">
										<input type="text" class="form-control" name="nome_arq" id="nome_arq" required>
									</div>
								</div>
								<div class="form-group">
									<label for="descricao_arq" class="col-sm-2 control-label">Descrição</label>
									<div class="col-sm-6">
										<input type="text" class="form-control" name="descricao_arq" id="descricao_arq" >
									</div>
								</div>
								<div class="form-group">
									<label for="arquivo" class="col-sm-2 control-label">Arquivo</label>
									<div class="col-sm-6">
									<label class="btn btn-block btn-info">
									<i class="fa fa-upload"></i>&nbsp;&nbsp;Importar arquivo
									<input type="file" id="btn_upload_arquivo_produto" name="btn_upload_arquivo_produto" style="display: none;">
									</label>
								</div>
								<input id="arquivos_produtos" name="arquivo_produto" hidden>
								<span class="help-block"></span>
								</div>
								<div class="box-footer">
									<div class="form-group">
										<label for="arquivo" class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
											<button type="submit" id="enviar_arq" id="btn_save_arq" class="btn btn-primary btn-small" value="Enviar"><i class="fa fa-save"></i>&nbsp;&nbsp;Enviar</button>
											<span class="help-block"></span>
										</div>
									</div>
								</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div  class="col-sm-12" id="divEditProdut" style="display:none; background-color:#cfd2dc99; margin-bottom:10px">
					<div class="control-group">
						<br>
						<h4>Editar Produto</h4>
						<br>
						<form class="form-horizontal" id="formEditProdut"  method="post" name="formEditProdut"  enctype="multipart/form-data">
							<div class="form-group">
								<div class="col-sm-12">
								<input class="hidden" id="id_prod_edit" name="id_prod_edit"/>
								<div class="form-group">
									<label for="nome_arq" class="col-sm-2 control-label">Nome</label>
									<div class="col-sm-6">
										<input type="text" class="form-control" name="nome_arq_produto" id="nome_arq_produto" value="" required>
									</div>
								</div>
								<div class="form-group">
									<label for="descricao_arq_produto" class="col-sm-2 control-label">Descrição</label>
									<div class="col-sm-6">
										<input type="text" class="form-control" name="descricao_arq_produto" id="descricao_arq_produto" value="" >
									</div>
								</div>
								<div class="form-group">
									<label for="arquivo" class="col-sm-2 control-label">Arquivo</label>
									<div class="col-sm-6">
									<label class="btn btn-block btn-info">
									<i class="fa fa-upload"></i>&nbsp;&nbsp;Importar arquivo
									<input type="file" id="btn_upload_arq_produto" name="btn_upload_arq_produto" style="display: none;">
								</label>
								</div>
								<input type="text"  id="arquivos_produt" name="arquivo_produt" hidden>
								<input type="text"  id="arquivos_produt_old" name="arquivo_produt_old" hidden>
								<span class="help-block"></span>
									<!-- <div class="col-sm-6">
										<input type="file" name="arquivo" id="arquivo" accept="image/*,.pdf" class="form-control" data-buttonText="Arquivo">
									</div> -->
								</div>
								<div class="box-footer">
									<div class="form-group">
										<label for="arquivo" class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
											<button type="submit" id="enviar_arq_produt" id="btn_save_arq_produt" class="btn btn-primary btn-small" value="Enviar"><i class="fa fa-save"></i>&nbsp;&nbsp;Enviar</button>
											<span class="help-block"></span>
										</div>
									</div>
								</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="containner">
					<div class="tab-pane" id="tab_arquivos_produtos">
						<table id="dt_produtos" class="table table-striped table-bordered">
							<thead>
								<tr class="tableheader">
									<th style='width: 20%;'><?= lang('arquivos')?></th>
									<th style='width: 50%;'><?= lang('descricao')?></th>
									<th style='width: 20%;'><?= lang('acoes')?></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>

				</div>
			
			</div>

			



		</div>
	</div>
</div>

<!-- MODAL NOVO PRODUTO -->
<div id="modal_novo_produto" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4 class="modal-title"><?= lang('novo_produto') ?></h4>
      </div>

      <div class="modal-body">
	  
	<form class="form-horizontal" method="post" name="formproduto" id="formproduto"  enctype="multipart/form-data" >
    	<div class="box-body">

			
				
			<div class="form-group">
    			<label for="nome" class="col-sm-2 control-label">Nome</label>
    			<div class="col-sm-6">
    				<input type="text" class="form-control" name="nome" id="nome" required>
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Descrição</label>
    			<div class="col-sm-6">
    				<input type="text" class="form-control" name="descricao" id="descricao" >
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="arquivo" class="col-sm-2 control-label">Arquivo</label>
				<div class="col-sm-6">
				<label class="btn btn-block btn-info">
                <i class="fa fa-upload"></i>&nbsp;&nbsp;Importar arquivo
                <input type="file" id="btn_upload_arquivo" accept="image/*" style="display: none;" required>
			  </label>
			  </div>
			  <input id="arquivo_produto" name="arquivo_produto" hidden>
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

<!-- MODAL EDITAR PRODUTO -->
<div id="modal_edit_produto" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4 class="modal-title"><?= lang('editar_produto') ?></h4>
	  </div>
	  
	  <div class="produto-alert alert" style="display:none; margin-bottom:-20px!important;">
			<button type="button" class="close" onclick="fecharMensagem('produto-alert')">
				<span aria-hidden="true">&times;</span>
			</button>
			<span id="msgcadproduto"></span>
		</div>

      <div class="modal-body">
	  
	<form class="form-horizontal" method="post" name="formedit" id="formedit"  enctype="multipart/form-data" >
		
	
	<div class="box-body">


			<div class="form-group">
    			<label for="produto_id" class="col-sm-2 control-label">ID</label>
    			<div class="col-sm-6">
					<input id="produto_id" name="produto_id" disabled>
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

<!-- MODAL PARA REMOÇÃO DE ARQUIVO DO PRODUTO -->
<div id="remove_produto" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1"><?=lang('excluir_arq_produto')?></h3>
            </div>
            <div class="modal-body">
				<h5><?=lang('pergunta_excluir_arq_produto')?></h5>
	        </div>
			<div class="modal-footer">
				<button class="btn btn-default" id="btnNao"><?=lang('nao')?></button>
                <button class="btn btn-primary" id="btnSim"><?=lang('sim')?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PARA REMOVER O PRODUTO -->
<div id="del_produto" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1"><?=lang('excluir_produto')?></h3>
            </div>
            <div class="modal-body">
				<h5><?=lang('pergunta_excluir_produto')?></h5>
	        </div>
			<div class="modal-footer">
				<button class="btn btn-default" id="btnNaoDelProduto"><?=lang('nao')?></button>
                <button class="btn btn-primary" id="btnSimDelProduto"><?=lang('sim')?></button>
            </div>
        </div>
    </div>
</div>


<script>
	$(document).ready(function() {
		var dt_arquivos_produto ;	
		
		//ABRE MODAL DE NOVO PRODUTO
		$("#btn_add_produto").click(function(){
			$('#formproduto')[0].reset();
			$("#modal_novo_produto").modal();
		});
		
		//CARREGA DADOS PARA O MODAL
		$(document).on('click', '.produto_modal', function(e){
			var botao = $(this);
			var id = botao.attr('data-id');
			var idProdto = botao.attr('data-produto');
			$.ajax({
				type: "POST",
				url:"<?=site_url('/ashownet/get_data_product')?>",
				data:{"id": id, "id_produt":idProdto},
				success: function(response){
					let resp = JSON.parse(response);
					$("#Modal-label-1").html('<h4 class="modal-title" id="Modal-label-1">'+resp.input.assunto+'</h4>');
					let url2 = "<?=site_url('../uploads/produtos/')?>"+'/'+ resp.input.file;
					//$("#imgProduct").html('<img src="'+ url2 +'/'+ resp.input.file + '" alt="img01" id="imgProduct" class="img-responsive center-block" />');
					$("#imgProduct").attr("src", url2);
					$("#btn_editar_produto").attr("data-id",resp.input.id);
					$("#btn_del_produto").attr("data-id",resp.input.id);
					$("#btn_editar_produto").attr("data-nome",resp.input.assunto);
					$("#btn_add_arquivo_produto").attr("data-id",resp.input.id);
					$("#id_produt_edit").attr("value",resp.input.id);
					$("#id_prod_edit").val(resp.input.id_produto);
					$("#nome_arq_produto").attr("value",resp.input.assunto);
					$("#descricao_arq_produto").attr("value",resp.input.descricao);
					$("#arquivos_produt").val(url2);
					$("#arquivos_produt_old").val(resp.input.path);
					$("#modalEditProduto").modal();
				}
			});

			dt_arquivos_produto = $("#dt_produtos").DataTable({
				"autoWidth": false,
				"processing": true,
				"destroy":true,
				"ajax": {
				"url": "<?=site_url('/ashownet/ajax_list_arq_produtos')?>",
				"type": "POST",
				"data": {"id":id}
				},
			});

		});

		
		//FORMULARIO PARA CRIAÇÃO DE NOVO PRODUTO
		$("#formproduto").submit(function() {
			var arquivos_produto = formproduto.arquivo_produto;
			
			if(arquivos_produto.value == ''){
				alert("Nenhum arquivo selecionado");
			}
			$.ajax({
				type: "POST",
				url: "<?= site_url('ashownet/ajax_save_produto') ?>",
				dataType: "json",
				data: $(this).serialize(),
				beforeSend: function() {
					clearErrors();
					$("#btn_save").siblings(".help-block").html("<i class='fa fa-circle-o-notch fa-spin'></i>&nbsp;Enviando...");
				},
				success: function(response) {
					clearErrors();
					if (response["status"]) {
						$("#modal_novo_produto").modal("hide");
						swal("Sucesso!","Arquivo salvo com sucesso!", "success");
						document.location.reload(true);
					} else {
						var msg = response["error_list"];
						swal("Erro!",msg, "error");
						//$('#msgcadproduto').html('<div class="alert alert-danger">'+response["msg"]+'</div>');
						//showErrorsModal(response["error_list"])
					}
				}
			})

			return false;
		});


		//FUNÇÕES PARA UPLOAD DE IMAGEM
		$("#btn_upload_arquivo").change(function() {
			uploadArq($(this), $("#arquivo_produto"));
		});
		$("#btn_upload_arquivo_produto").change(function() {
			uploadArq($(this), $("#arquivos_produtos"));
		});
		$("#btn_upload_arq_produto").change(function() {
			uploadArq($(this), $("#arquivos_produt"));
		});
		$("#btn_upload_arquivo_produto_edit").change(function() {
			uploadArq($(this), $("#arquivos_produtos_edit"));
		});

		


		function loadingImg(message="") {
			return "<i class='fa fa-circle-o-notch fa-spin'></i>&nbsp;" + message
		}
		function uploadArq(input_file, input_path) {

			prod_file = input_file[0].files[0];
			form_data = new FormData();
			form_data.append("produto_file", prod_file);
			$.ajax({
				url: "<?=site_url('/ashownet/ajax_import_arq_produto')?>",
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

		//FUNÇÕES DE ERROS
		function showErrorsModal(error_list) {
			clearErrors();

			$.each(error_list, function(id, message) {
				$(id).parent().parent().addClass("has-error");
				$(id).siblings(".help-block").html(message)
			})
		} 
		function clearErrors() {
			$(".has-error").removeClass("has-error");
			$(".help-block").html("");
		}

		//BOTÕES DE EDIÇÃO E NOVO ARQUIVO NO MODAL

		$(document).on('click', '#btn_add_arquivo_produto', function (e) {
       
			let botao = $(this);
			if (botao.attr('data-acao') == 'mostrarForm') {
				$('#divCadNovoArquivo').css('display', 'block');
				botao.attr('data-acao', 'esconderForm')
				.removeClass('btn-primary')
				.addClass('btn-danger')
				.html('<i class="fa fa-minus icon-white"></i> '+"Novo arquivo");
				$('#formNovoArquivo')[0].reset();
				$('#divEditProdut').css('display', 'none');
				$('#btn_editar_produto')
				.removeClass('btn-danger')
				.addClass('btn-primary')
				.html('<i class="fa fa fa-edit icon-white"></i> '+"Editar Produto");
			}else {
				$('#divCadNovoArquivo').css('display', 'none');
				botao.attr('data-acao', 'mostrarForm')
				.removeClass('btn-danger')
				.addClass('btn-primary')
				.html('<i class="fa fa-plus icon-white"></i> '+"Novo arquivo");
			}
		});
		
		$(document).on('click', '#btn_editar_produto', function (e) {
       
			let botao = $(this);
			if (botao.attr('data-acao') == 'mostrarFormEditProdut') {
				$('#divEditProdut').css('display', 'block');
				botao.attr('data-acao', 'esconderFormEditProdut')
				.removeClass('btn-primary')
				.addClass('btn-danger')
				.html('<i class="fa fa-minus icon-white"></i> '+"Editar produto");
				$('#formNovoArquivo')[0].reset();
				$('#divCadNovoArquivo').css('display', 'none');
				$('#btn_add_arquivo_produto')
				.removeClass('btn-danger')
				.addClass('btn-primary')
				.html('<i class="fa fa-plus icon-white"></i> '+"Novo arquivo");
			}else {
				$('#divEditProdut').css('display', 'none');
				botao.attr('data-acao', 'mostrarFormEditProdut')
				.removeClass('btn-danger')
				.addClass('btn-primary')
				.html('<i class="fa fa fa-edit icon-white"></i> '+"Editar Produto");
			}
   		});


		//FORMULARIO PARA NOVO ARQUIVO
		$("#formNovoArquivo").submit(function() {
			var arquivos_produtos = formNovoArquivo.arquivos_produtos;
			var btn_upload_arquivo_produto = formNovoArquivo.btn_upload_arquivo_produto;
			
			if(arquivos_produtos.value == ''){
				alert("Nenhum arquivo selecionado");
				btn_upload_arquivo_produto.focus();
			}
			$.ajax({
				type: "POST",
				url: "<?= site_url('ashownet/ajax_save_arq_produto') ?>",
				dataType: "json",
				data: $(this).serialize(),
				beforeSend: function() {
					clearErrors();
					$("#btn_save").siblings(".help-block").html("<i class='fa fa-circle-o-notch fa-spin'></i>&nbsp;Enviando...");
				},
				success: function(response) {
					console.log(response);
					clearErrors();
					if (response["status"]) {
						swal("Sucesso!","Arquivo salvo com sucesso!", "success");
						$('#formNovoArquivo')[0].reset();
						dt_arquivos_produto.ajax.reload();
					} else {
						showErrorsModal(response["error_list"])
					}
				}
			})

			return false;
			});
		
		//FORMULÁRIO PARA EDITAR PRODUTO
		$("#formEditProdut").submit(function(){	
			$.ajax({
				type: "POST",
				url: "<?= site_url('ashownet/ajax_edit_produto') ?>",
				dataType: "json",
				data: $(this).serialize(),
				beforeSend: function() {
					clearErrors();
					$("#btn_save").siblings(".help-block").html("<i class='fa fa-circle-o-notch fa-spin'></i>&nbsp;Enviando...");
				},
				success: function(response) {
					console.log(response);
					clearErrors();
					if (response["status"]) {
						swal("Sucesso!","Arquivo salvo com sucesso!", "success");
						document.location.reload(true);
					} else {
						showErrorsModal(response["error_list"])
					}
				}
			})

			return false;
			
		});

		//DELETANDO O ARQUIVO
		$(document).on('click', '.btn-del-produto', function(e){
			e.preventDefault();
			var botao = $(this);
			$('#btnSim').attr('data-id', botao.attr('data-produto_id'));
			//$('#btnSim').attr('data-tipo', botao.attr('data-tipo'));
			$('#remove_produto').modal();
		});

		$(document).on('click', '#btn_del_produto', function(e){
			e.preventDefault();
			var botao = $(this);
			$('#btnSimDelProduto').attr('data-id', botao.attr('data-id'));
			//$('#btnSim').attr('data-tipo', botao.attr('data-tipo'));
			$('#del_produto').modal();
		});

		$(document).on('click', '#btnSim', function(e){
			e.preventDefault();

			var botao = $(this);
			var id = botao.attr('data-id');

			$.ajax({
				url : "<?= site_url('cadastros/excluir_arq_produto').'/' ?>"+id,
				type : 'POST',
				beforeSend: function(){
					botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Excluindo...');
				},
				success : function(callback){
					var data = JSON.parse(callback);
					if (data.success) {
						dt_arquivos_produto.ajax.reload();

						$('#msgproduto').html('<div class="alert alert-success">'+data.msg+'</div>');
					}else {
						$('#msgproduto').html('<div class="alert alert-danger">'+data.msg+'</div>');
					}
				},
				error : function () {
					$('#msgproduto').html('<div class="alert alert-danger">'+lang.tente_mais_tarde+'</div>');
				},
				complete: function () {
					$('.produto-alert').css('display', 'block');
					botao.attr('disabled', false).html(lang.sim);
					$('#remove_produto').modal('hide');
				}
			});

		});
		$(document).on('click', '#btnSimDelProduto', function(e){
			e.preventDefault();

			var botao = $(this);
			var id = botao.attr('data-id');

			$.ajax({
				url : "<?= site_url('cadastros/excluir_produto').'/' ?>"+id,
				type : 'POST',
				beforeSend: function(){
					botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Excluindo...');
				},
				success : function(callback){
					var data = JSON.parse(callback);
					if (data.success) {
						
						
						swal("Sucesso!","Produto deletado com sucesso!", "success");
						document.location.reload(true);

						$('#msgproduto').html('<div class="alert alert-success">'+data.msg+'</div>');
					}else {
						$('#msgproduto').html('<div class="alert alert-danger">'+data.msg+'</div>');
					}
				},
				error : function () {
					$('#msgproduto').html('<div class="alert alert-danger">'+lang.tente_mais_tarde+'</div>');
				},
				// complete: function () {
				// 	$('.produto-alert').css('display', 'block');
				// 	botao.attr('disabled', false).html(lang.sim);
				// 	document.location.reload(true);

				// }
			});

		});

		var linhaTabelaArquivos,pageTableArquivos, indexTableArquivos;
		$(document).on('click', '.btn-edit-produto', function() {
			var botao = $(this);
			linhaTabelaArquivos = dt_arquivos_produto.row($(this).closest('tr')).data();
			pageTableArquivos = dt_arquivos_produto.page();
			indexTableArquivos = dt_arquivos_produto.row( $(this).closest('tr') ).index();
			//console.log(linhaTabelaArquivos);
			//let id_arq = linhaTabelaArquivos.id;
			let id_arq = botao.attr('data-produto_id');

			$('.btn-edit-produto').removeClass('btn-danger').addClass('btn-primary');
			botao.removeClass('btn-primary').addClass('btn-danger');
			var tr = $(this).closest('tr');
        	var row = dt_arquivos_produto.row( tr );
			if ( row.child.isShown() ) {
            	// ESCONDE A LINHA FILHA
				botao.removeClass('btn-danger').addClass('btn-primary');
				row.child.hide();

        	}else{
				//ESCONDE TODAS AS LINHAS FILHAS ABERTAS
				dt_arquivos_produto.rows().eq(0).each( function ( idx ) {
                var linha = dt_arquivos_produto.row( idx );
                if (linha.child.isShown())
                    linha.child.hide();
            	});
				row.child( '<div style="background-color: #cfd2dc99; padding: 10px;">'+
                          '<div id="contaSpan'+id_arq+'">'+
                              '<p>Carregando...</p>'+
                          '</div>'+
                      '</div>'
            	).show();


				var th = '<form class="form-horizontal" method="post" name="formeditarq" id="formeditarq"  enctype="multipart/form-data" >'+
							'<div class="box-body">'+
							'<div class="form-group">'+
							'<input id="produto_id" name="produto_id" hidden>'+
							'</div>'+
							'<div class="form-group">'+
							'<label for="descricao_edit" class="col-sm-2 control-label">Descrição</label>'+
							'<div class="col-sm-6">'+
							'<input type="text" class="form-control" name="descricao_edit_arq" id="descricao_edit_arq" required>'+
							'</div>'+
							'</div>'+
							'<div class="form-group">'+
								'<label for="arquivo" class="col-sm-2 control-label">Arquivo</label>'+
								'<div class="col-sm-6">'+
								'<label class="btn btn-block btn-info">'+
								'<i class="fa fa-upload"></i>&nbsp;&nbsp;Importar arquivo'+
								'<input type="file" id="btn_upload_arquivo_produto_edit" name="btn_upload_arquivo_produto_edit" style="display: none;">'+
								'</label>'+
							'</div>'+
							'<input id="arquivos_produtos_edit" name="arquivos_produtos_edit" hidden>'+
							'<span class="help-block"></span>'+
							'</div>'+
							'<div class="box-footer">'+
							'<div class="form-group">'+
							'<label for="arquivo" class="col-sm-2 control-label"></label>'+
							'<div class="col-sm-6">'+
							'<button type="submit" id="enviar_arq_edit" id="btn_save_arq_edit" class="btn btn-primary btn-small" value="Enviar"><i class="fa fa-save"></i>&nbsp;&nbsp;Enviar</button>'+
							'<span class="help-block"></span>'+
							'</div>'+
							'</div>'+
							'</div>'+
							'</div>'+
							'</form>';
				
				
				
				$('#contaSpan'+id_arq).html(th);

			}
		});



	});

</script>