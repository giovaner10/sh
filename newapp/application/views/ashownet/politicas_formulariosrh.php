<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_politicas_formularios')){ ?>
	<br>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs" href="<?php echo site_url('cadastros/politicas_formulariosrh');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>Políticas e Formulários Gente & Gestão</h5>
	<h2 class="TitPage">Nossas Políticas, Formulários e Informações Gerais Gente & Gestão</h2>
	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title TitPage">
			
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
                            FROM (`showtecsystem`.`cad_politicas_formularios` AS informacao) 
                            INNER JOIN `showtecsystem`.`arquivos` AS arquivo ON `informacao`.`id_arquivo` = `arquivo`.`id`
                            WHERE informacao.tipo = 'P'");
                			
                			foreach ($query_politicas->result_array() as $row_politica) {
                			?>
							<tr class="odd gradeX">
								<td>
    								<span class="glyphicon glyphicon-link  btn-xs"></span>
    								<a href="<?php echo base_url("uploads/gente_gestao/desenv_organizagional/politica_formulario/$row_politica[file]");?>" target="_blank" ><?php echo $row_politica['descricao'];?></a>
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
                            FROM (`showtecsystem`.`cad_politicas_formularios` AS informacao) 
                            INNER JOIN `showtecsystem`.`arquivos` AS arquivo ON `informacao`.`id_arquivo` = `arquivo`.`id`
                            WHERE informacao.tipo = 'F'");
                			
                			foreach ($query_formulario->result_array() as $row_formulario) {
                			?>
							<tr class="odd gradeX">
								<td><span class="glyphicon glyphicon-link  btn-xs"></span>
									<a href="<?php echo base_url("uploads/gente_gestao/desenv_organizagional/politica_formulario/$row_formulario[file]");?>" target="_blank" ><?php echo $row_formulario['descricao'];?></a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>				
			</div>
		</div>
	</div>
</div>