<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_docs_pendentes')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs"
			href="<?php echo site_url('cadastros/listar_docs_pendentes');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>RH</h5>
	<h2 class="TitPage">Documentos Pendentes</h2>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<?php if($lista_dados != ""){?>
				<div class="panel-body">
					<?php 
					
					$query_usuario = $this->db->query("SELECT id, nome FROM usuario WHERE  id = '$lista_dados->id_funcionario'");
					$row_usuario = $query_usuario->row();
					?>
					<div class="row">
                		<div class="col-md-12">
                            <?php echo $this->session->flashdata('sucesso');?>
                            <?php echo $this->session->flashdata('error');?>
                        </div>
                	</div>
					<form method="post" name="frmdocs" enctype="multipart/form-data" action="<?php echo site_url("cadastros/cad_doc_pendentes_funcionario/$row_usuario->id")?>">
						<div class="form-group">
							<div class="row">
								<div class="col-xs-12">
									<label class="control-label">Nome</label>
									<h5><?php echo $row_usuario->nome;?></h5>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-xs-12">
									<h5>Solicitamos que providencie cópia simples dos documentos
										abaixo e entregue no RH até a data limite.</h5>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Documentos</label>
							<?php 
							if($lista_dados->residencia == '1'){
							?>
							<div class="panel panel-default" style="padding: 10px;"> Comprovante de residência.</div>
							<?php } ?>
							<?php 
							if($lista_dados->cpf == '1'){
							?>
							<div class="panel panel-default" style="padding: 10px;"> Cópia do CPF.</div>
							<?php } ?>
							<?php 
							if($lista_dados->rg == '1'){
							?>
							<div class="panel panel-default" style="padding: 10px;"> Cópia do RG.</div>
							<?php } ?>
							<?php 
							if($lista_dados->banco == '1'){
							?>
							<div class="panel panel-default" style="padding: 10px;"> Cópia da conta bancária.</div>
							<?php } ?>
							
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-xs-5">
									<label class="control-label">Data de Solicitação</label> <input	type="text" class="form-control" value="<?=date("d/m/Y", strtotime($lista_dados->data_solicitacao))?>" />
								</div>
								<div class="col-xs-4">
									<label class="control-label">Data Limite</label> <input	type="text" class="form-control"  value="<?=date("d/m/Y", strtotime($lista_dados->prazo_maximo))?>" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-xs-5">
									<label class="control-label">Enviar Arquivos</label> 
									<input type="file" class="form-control" name="arquivo[]" multiple="">
								</div>
							</div>
						</div>
	                    <div class="box-footer">
                    		<div class="form-group">
                    			<label for="arquivo" class="col-sm-2 control-label"></label>
                    			<div class="col-sm-6">
                    				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Enviar" />
                    			</div>
                    		</div>
                		</div>
					</form>
				</div>
				<?php }else{ ?>
				Não há nenhuma solicitação de documentação até o momento!
				<?php }?>
			</div>
		</div>
	</div>
</div>