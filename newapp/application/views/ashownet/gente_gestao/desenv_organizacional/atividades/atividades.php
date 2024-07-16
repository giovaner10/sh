<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_atividades')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs" href="<?php echo site_url('cadastros/listar_atividades');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>Atividades</h5>
	<h2 class="TitPage">Meus Treinamentos</h2>
	<br>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<?php 
				
				if(count($lista_dados) > 0 ){ 
				
				?>
				<div class="panel-heading">
					<h4>
						<strong>Avaliações Cursos</strong>
					</h4>
					<p>Lista de avaliações disponíveis dos Cursos.</p>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead style="background-color: #0072cc !important; color: white !important;">
                        		<tr>
        							<th class="text-center">Nome</th>
        							<th class="text-center">Tipo</th>
        							<th class="text-center">Inicio</th>
        							<th class="text-center">Término</th>
        							<th class="text-center">Carga Hrs</th>
        							<th class="text-center">Status</th>						
        						</tr>
                        	</thead>
							<tbody>
                        	<?php foreach ($lista_dados as $lista_dado){?>
                        		<tr class="odd gradeX">
        							<td><?=$lista_dado->nome?></td>
        							<td align="center"><?=$lista_dado->tipo?></td>
        							<td align="center"><?php if($lista_dado->data_inicio != "0000-00-00"){ echo date("d/m/Y", strtotime($lista_dado->data_inicio)); }?></td>
        							<td align="center"><?php if($lista_dado->data_fim != "0000-00-00"){ echo date("d/m/Y", strtotime($lista_dado->data_fim)); }?></td>
        							<td align="center"><?=$lista_dado->carga_hr?>h/a</td>
        							<td align="center"><?=$lista_dado->status?></td>
        						</tr>
                        		<?php }?>
                        	</tbody>

						</table>
					</div>
					<!-- /.table-responsive -->
				</div>
				
			<?php } ?>
			</div>
		</div>
	</div>
</div>