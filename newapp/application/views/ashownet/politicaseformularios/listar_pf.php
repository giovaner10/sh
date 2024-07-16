<div class="col-sm-12">
	<?php if ($this->auth->is_allowed_block('cad_politicas_formularios')){ ?>
	<br>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs" href="<?php echo site_url('cadastros/politicas_formularios');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>Políticas, Formulários e Informações Gerais</h5>
	<h2 class="TitPage">Nossas Políticas, Formulários e Informações Gerais</h2>
	<div class="row">
		<?php 
		if(count($assuntos) > 0) { 
		    foreach($assuntos as $assunto){
		?>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title TitPage">
					<strong>
					<?php 
					   echo $assunto->assunto;					   
					?>
					<?php if ($this->auth->is_allowed_block('cad_politicas_formularios')){ ?>
					<a href="<?php echo site_url("cadastros/editar_assunto/$assunto->id")?>" class="btn btn-mini btn-primary" title="Editar Assunto">
                        <i class="fa fa-edit"></i>
                    </a>
                    <?php } ?>
					</strong>
				</h3>
			</div>			
			<div class="panel-body">				
				<div class="col-sm-6">
					<table class="table table-condensed table-bordered table-hover">
						<thead>
							<tr>
								<th class="text-center">POLÍTICAS</th>
							</tr>
						</thead>
						<tbody>
    						<?php 
                			$query_politicas = $this->db->query("SELECT `informacao`.*, `arquivo`.`file`, `arquivo`.`path` 
                            FROM (`showtecsystem`.`cad_formularios_informacoes` AS informacao) 
                            INNER JOIN `showtecsystem`.`arquivos` AS arquivo ON `informacao`.`id_arquivos` = `arquivo`.`id`
                            WHERE informacao.id_assunto = '$assunto->id' AND informacao.tipo = 'P' AND informacao.status = 'ativo'");
                			
                			foreach ($query_politicas->result_array() as $row_politica) {
                			?>
							<tr class="odd gradeX">
								<td>
    								<span class="glyphicon glyphicon-link  btn-xs"></span>
    								<a href="<?php echo base_url("uploads/politica_formulario/$row_politica[file]");?>" target="_blank" ><?php echo $row_politica['descricao'];?></a>
    								
    								<?php if ($this->auth->is_allowed_block('cad_politicas_formularios')){ ?>
									<a href="<?php echo site_url("cadastros/editar_informacao/$row_politica[id]")?>" class="btn btn-mini btn-primary" title="Editar informação">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <?php } ?>
                                </td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="col-sm-6">
					<table class="table table-condensed table-bordered table-hover">
						<thead>
							<tr>
								<th class="text-center">FORMULÁRIOS e INFORM. GERAIS</th>
							</tr>
						</thead>
						<tbody>
							<?php 
                			$query_formulario = $this->db->query("SELECT `informacao`.*, `arquivo`.`file`, `arquivo`.`path` 
                            FROM (`showtecsystem`.`cad_formularios_informacoes` AS informacao) 
                            INNER JOIN `showtecsystem`.`arquivos` AS arquivo ON `informacao`.`id_arquivos` = `arquivo`.`id`
                            WHERE informacao.id_assunto = '$assunto->id' AND informacao.tipo = 'F' AND informacao.status = 'ativo'");
                			
                			foreach ($query_formulario->result_array() as $row_formulario) {
                			?>
							<tr class="odd gradeX">
								<td><span class="glyphicon glyphicon-link  btn-xs"></span>
									<a href="<?php echo base_url("uploads/politica_formulario/$row_formulario[file]");?>" target="_blank" ><?php echo $row_formulario['descricao'];?></a>
								
									<?php if ($this->auth->is_allowed_block('cad_politicas_formularios')){ ?>
									<a href="<?php echo site_url("cadastros/editar_informacao/$row_formulario[id]")?>" class="btn btn-mini btn-primary" title="Editar informação">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <?php } ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>				
			</div>
		</div>
		<?php }} ?>
	</div>
</div>