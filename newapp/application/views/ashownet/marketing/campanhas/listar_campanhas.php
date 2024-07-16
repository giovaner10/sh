<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_marketing_campanhas')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs" href="<?php echo site_url('cadastros/marketing_campanhas');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>Marketing</h5>
	<h2 class="TitPage">Marketing - Campanhas</h2>
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
					<a href="<?php echo site_url("cadastros/editar_assunto_marketing_campanhas/$assunto->id")?>" class="btn btn-mini btn-primary" title="Editar Assunto">
                        <i class="fa fa-edit"></i>
                    </a>
                    <?php } ?>
					</strong>
				</h3>
			</div>			
			<div class="panel-body">				
				<div class="col-sm-6">
					<table class="table table-condensed table-bordered table-hover">
						<tbody>
    						<?php 
                			$query_politicas = $this->db->query("SELECT `informacao`.*, `arquivo`.`file`, `arquivo`.`path` 
                            FROM (`showtecsystem`.`cad_marketing_campanhas` AS informacao) 
                            INNER JOIN `showtecsystem`.`arquivos` AS arquivo ON `informacao`.`id_arquivo` = `arquivo`.`id`
                            WHERE informacao.id_assunto = '$assunto->id' AND ativo = '1'");
                			
                			foreach ($query_politicas->result_array() as $row_politica) {
                			?>
							<tr class="odd gradeX">
								<td>
    								<span class="glyphicon glyphicon-link  btn-xs"></span>
    								<a href="<?php echo base_url("uploads/marketing_campanhas/$row_politica[file]");?>" target="_blank" ><?php echo $row_politica['descricao'];?></a>
    								
    								<?php if ($this->auth->is_allowed_block('cad_politicas_formularios')){ ?>
									<a href="<?php echo site_url("cadastros/editar_informacao_marketing_campanhas/$row_politica[id]")?>" class="btn btn-mini btn-primary" title="Editar informação">
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