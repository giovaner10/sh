<div class="containner">
    <h3>Contratos EPTC</h3>
    <hr class="featurette-divider">
    <div class="well well-small" style="height: 70px;">
    	<form action="" method="get" class="form-horizontal">
    		<?php if ($this->input->get()): ?>
    			<a href="<?php echo site_url('contratos_eptc/listar_contratos')?>" class="btn btn-primary"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a>			
    		<?php endif ?>
    		<div class="control-group">
    			<div class="col-sm-3">
    				<input type="text" name="palavra" value="" placeholder="Palavra" class="form-control">
    			</div>
    		</div>
    		<div class="control-group">
    			<div class="col-sm-3">
            		<select name="coluna" class="form-control">
            			<option value="permissionario">Permissionário</option>
            			<option value="prefixo">Prefixo</option>			
            		</select>
        		</div>
        		<div class="col-sm-3">
        			<button type="submit" class="btn"><i class="fa fa-search"></i></button>
        		</div>
    		</div>
    	</form>
<h3>Contratos EPTC</h3>
<hr class="featurette-divider">
<div class="well well-small" style="height: 30px;">


	   		<form action="" method="get" class="form-horizontal pull-right">
	   			<?php if ($this->input->get()): ?>
					<a href="<?php echo site_url('contratos_eptc/listar_contratos')?>" class="btn btn-primary"><i class="icon-arrow-left icon-white"></i> Voltar</a>			
				<?php endif ?>
				<input type="text" name="palavra" value="" placeholder="Palavra">
				<select name="coluna" style="width: inherit;">

					<option value="permissionario">Permissionário</option>
					<option value="prefixo">Prefixo</option>
					
				</select>
				<button type="submit" class="btn"><i class="icon-search"></i></button>
			</form>

</div>

<br style="clear:both" />

<div class="span12" style="float: none; margin-left: auto; margin-right: auto;">

	<?php if ($contratos): ?>

	
	<table class="table table-hover">
						  	  
			<tr>
				<th>Id</th>
				<th>Prefixo</th>
				<th>Placa</th>
				<th>Permissionário</th>
				<th>Visualizar</th>
				<th>Digitalizar</th>
				<th>Gerar Termo</th>
			</tr>
									
		<?php foreach ($contratos as $contrato): ?>

			<tr>
				<td><span class="badge badge-inverse"><?php echo $contrato->id ?></span></td>
				<td><?php echo $contrato->prefixo ?></td> 
				<td><?php echo $contrato->placa ?></td> 
				<td><?php echo $contrato->permissionario ?></td>   
				<td><a href="<?php echo site_url('contratos_eptc/imprimir_contrato/'.$contrato->id)?>" target="_blank" title="Imprimir"><i class="icon-edit"></i></a></td>	
			
				<?php $result = $this->contrato->get_total_arquivos($contrato->prefixo) ?>
				<?php if($result == 0): ?>
				<td>
					<a data-toggle="modal" href="<?php echo site_url('contratos_eptc/digi_contrato_eptc/'.$contrato->prefixo)?>" title="Digitalizar Documentos" data-target="#myModal_digitalizar" class="btn btn-warning btn-mini dropdown-toggle btn-atualizar-equipamento">
					<i class="icon-th icon-white"></i> 
					</a> 		
				</td>
				<?php else:?>
				<td>
					<a data-toggle="modal" href="<?php echo site_url('contratos_eptc/digi_contrato_eptc/'.$contrato->prefixo)?>" title="Digitalizar Documentos" data-target="#myModal_digitalizar" class="btn btn-success btn-mini dropdown-toggle btn-atualizar-equipamento">
					<i class="icon-th icon-white"></i> 
					</a> 		
				</td>
				<?php endif;?>
				<td>
					<a class="btn btn-success btn-mini dropdown-toggle" href="<?=base_url()?>index.php/licitacao/termo_adesao?pf=1<?='&eptc='.$contrato->id?>">
						<i class="fa fa-handshake-o"></i>
					</a>
				</td>
			</tr>	

		<?php endforeach ?>

	<?php else: ?>

		<div class="alert alert-block">
			<h2>Não há Contratos</h2>
			<h4>Nenhum Contrato gerado!</h4>
		</div>
			
	<?php endif ?>	


	</table>

	 <div class="pagination" style="float: right;">
		<?php echo $this->pagination->create_links();  ?> 
	</div>

</div>

<div id="myModal_digitalizar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 
aria-hidden="true">
	<div class="modal-header" style="text-align: center;">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel">Digitalizar Contrato</h3>
    </div>
    <br>
    <div class="col-md-12" style="float: none; margin-left: auto; margin-right: auto;">
    	<?php if ($contratos): ?>	
    	<table class="table table-hover">						  	  
    			<tr>
    				<th>Id</th>
    				<th>Prefixo</th>
    				<th>Placa</th>
    				<th>Permissionário</th>
    				<th>Visualizar</th>
    				<th>Digitalizar</th>
    				<th>Gerar Termo</th>
    			</tr>									
    		<?php foreach ($contratos as $contrato): ?>
    			<tr>
    				<td><span class="badge badge-inverse"><?php echo $contrato->id;?></span></td>
    				<td><?php echo $contrato->prefixo ?></td> 
    				<td><?php echo $contrato->placa ?></td> 
    				<td><?php echo $contrato->permissionario ?></td>   
    				<td><a href="<?php echo site_url('contratos_eptc/imprimir_contrato/'.$contrato->id)?>" target="_blank" title="Imprimir"><i class="fa fa-edit"></i></a></td>	
    				<?php $result = $this->contrato->get_total_arquivos($contrato->prefixo) ?>
    				<?php if($result == 0): ?>
    				<td>
    					<a data-toggle="modal" href="<?php echo site_url('contratos_eptc/digi_contrato_eptc/'.$contrato->prefixo)?>" title="Digitalizar Documentos" data-target="#myModal_digitalizar" class="btn btn-warning btn-sm dropdown-toggle btn-atualizar-equipamento">
    						<i class="fa fa-th" aria-hidden="true"></i> 
    					</a> 		
    				</td>
    				<?php else:?>
    				<td>
    					<a data-toggle="modal" href="<?php echo site_url('contratos_eptc/digi_contrato_eptc/'.$contrato->prefixo)?>" title="Digitalizar Documentos" data-target="#myModal_digitalizar" class="btn btn-success btn-sm dropdown-toggle btn-atualizar-equipamento">
    						<i class="fa fa-th" aria-hidden="true"></i> 
    					</a> 		
    				</td>
    				<?php endif;?>
    				<td>
    					<a class="btn btn-success btn-sm dropdown-toggle" href="<?=base_url()?>index.php/licitacao/termo_adesao?pf=1<?='&eptc='.$contrato->id?>">
    						<i class="fa fa-handshake-o"></i>
    					</a>
    				</td>
    			</tr>
    		<?php endforeach ?>
    	<?php else: ?>
    		<div class="alert alert-block">
    			<h2>Não há Contratos</h2>
    			<h4>Nenhum Contrato gerado!</h4>
    		</div>			
    	<?php endif ?>
    	</table>
    	 <div class="pagination" style="float: right;">
    		<?php echo $this->pagination->create_links();  ?> 
    	</div>
    </div>
    <div id="myModal_digitalizar" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Digitalizar Contrato</h4>
				</div>
				<div class="modal-body"></div>
			</div>
		</div>
	</div>
	<script type="text/javascript">    
    $('#myModal_digitalizar').on('hidden', function(){
        $(this).data('modal', null);
    });    
    </script>
</div>