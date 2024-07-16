<h3>Editar Produto</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<?php 
	if($produtos->ativo == '1'){
	    $checked = 'checked="checked"';
	}else{
	    $checked = "";
	}

	?>
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#show" class="show">Produtos</a></li>
		<li class="sim"><a data-toggle="tab" href="#sim">Assunto</a></li>
	</ul>
	<div class="tab-content">
		<div id="show" class="tab-pane fade in active">
			<form class="form-horizontal" method="post" name="formcontato"	enctype="multipart/form-data" action="<?php echo site_url("cadastros/edit_produto/$produtos->id_assunto")?>">
				<div class="box-body">
					<div class="form-group">
        				<label class="col-sm-2 control-label">Assunto</label> 
        				<div class="col-sm-6">        					
        					<select name="assunto" class="form-control" required>   
        						<?php         						
        						
        						if(count($produtos) > 0) { ?> 
        						    <option value="" selected="selected">Selecione um assunto...</option>
        						<?php
        						$query = $this->db->query("SELECT * FROM cad_assunto_produtos WHERE ativo ='1'");
        						
        						foreach ($query->result_array() as $row) {				
        						?> 				
            					<option value="<?=$row['id']?>"<?php echo $row['id'] == $produtos->id_assunto ? "selected = 'selected'" : $row['id']; ?>><?=$row['assunto']?></option>                					
            					<?php } ?>
            					<?php }else{  ?>
            					<option value="">Não há assuntos cadastrados</option>
            					<?php } ?>
            				</select>            				
            			</div>
        			</div>
        			<?php 
        			$query_arquivos = $this->db->query("SELECT a.*, i.descricao FROM cad_produtos_informacoes i, cad_produto_informacoes_arquivos a
                    WHERE i.id_assunto = a.id_assunto AND i.id=a.id_produto AND a.id_assunto = '$produtos->id_assunto'");
        			
        			foreach ($query_arquivos->result_array() as $rowArquivos) {		
        			    
        			    $ext = substr($rowArquivos['file'], (strlen($rowArquivos['file']) - 3), strlen($rowArquivos['file']));
        			    
            			?>
            			<input type="hidden" name="idproduto[]" value="<?=$rowArquivos['id_produto']?>">
            			<?php if($ext != "jpg" && $ext != "png"){ ?>
    					<div class="form-group">
    						<label for="descricao" class="col-sm-2 control-label">Descrição</label>
    						<div class="col-sm-6">
    							<input type="text" class="form-control" name="descricao[]" id="descricao" value="<?=$rowArquivos['descricao']?>" required>
    						</div>
    						<a onclick="excluir(<?=$rowArquivos['id_produto']?>)" class="btn btn-mini btn-danger" title="Excluir">
                                <i id="icon" class="fa fa-remove"></i>
                            </a>
    					</div>
    					<?php }else{?>
    					<div class="form-group">
    						<label for="capa" class="col-sm-2 control-label">Capa</label>
    						<div class="col-sm-6">
    							<input type="text" class="form-control" name="capa" id="capa" value="<?=$rowArquivos['file']?>" required>
    						</div>
    						<a onclick="excluir(<?=$rowArquivos['id_produto']?>)" class="btn btn-mini btn-danger" title="Excluir">
                                <i id="icon" class="fa fa-remove"></i>
                            </a>
    					</div>
    					<?php } ?>
					<?php } ?>					
					<div class="form-group">
            			<label for="arquivo" class="col-sm-2 control-label">Ativo</label>
            			<div class="col-sm-6">
            				<input type="checkbox" name="ativo" id="ativo" value="1" <?php echo $checked;?>>
            			</div>
            		</div>
					<div class="box-footer">
						<div class="form-group">
							<label for="arquivo" class="col-sm-2 control-label"></label>
							<div class="col-sm-6">
								<input type="submit" id="enviar" class="btn btn-success btn-small" value="Alterar" />
								<a href="<?php echo site_url('cadastros/listar_produtos')?>" class="btn btn-primary"> Voltar</a>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div id="sim" class="tab-pane fade">
			<form class="form-horizontal" method="post" name="formcontato"	enctype="multipart/form-data" action="<?php echo site_url("cadastros/edit_produto_assunto/$produtos->id")?>">
				<input type="hidden" name="idassunto" value="<?=$produtos->id_assunto?>">
				<div class="box-body">
					<div class="form-group">
						<label for="descricao" class="col-sm-2 control-label">Assunto</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" name="assunto"	id="assunto" value="<?=$produtos->assunto?>" required>
						</div>
					</div>
					<div class="box-footer">
						<div class="form-group">
							<label for="arquivo" class="col-sm-2 control-label"></label>
							<div class="col-sm-6">
								<input type="submit" id="enviar" class="btn btn-success btn-small" value="Alterar" /> 
								<a href="<?php echo site_url('cadastros/listar_produtos')?>" class="btn btn-primary"> Voltar</a>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
function excluir(id) {

	var r = confirm("Desseja realmente excluir esse produto? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_produto_editar').'/' ?>"+id;
    
         $.ajax({
            url : url,
            type : 'POST',
            beforeSend: function(){
    			alert('Aguarde um momento por favor...');
            },
            success : function(data){
            	alert(data);
    	        window.location.reload();  
            },
            error : function () {
                alert('Error...');
            }
        });
	}
}
</script>