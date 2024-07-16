<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_atividades')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs"
			href="<?php echo site_url('cadastros/atividades');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>Treinamentos</h5>
	<h2 class="TitPage">Treinamentos</h2>
	<?php
if (count($lista_dados) > 0) { ?>
	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-body">
				<table class="table table-condensed table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center">Nome</th>
							<th class="text-center">Tipo</th>
							<th class="text-center">Inicio</th>
							<th class="text-center">Término</th>
							<th class="text-center">Carga Hrs</th>
							<th class="text-center">Status</th>
							<th class="text-center">Ações</th>							
						</tr>
					</thead>
					<tbody>
					<?php   foreach ($lista_dados as $lista_dado) { ?>
						<tr class="odd gradeX">
							<td><?=$lista_dado->nome?></td>
							<td align="center"><?=$lista_dado->tipo?></td>
							<td align="center"><?php if($lista_dado->data_inicio != "0000-00-00"){ echo date("d/m/Y", strtotime($lista_dado->data_inicio)); }?></td>
							<td align="center"><?php if($lista_dado->data_fim != "0000-00-00"){ echo date("d/m/Y", strtotime($lista_dado->data_fim)); }?></td>
							<td align="center"><?=$lista_dado->carga_hr?>h/a</td>
							<td align="center"><?=$lista_dado->status?></td>
							<td style="text-align: center;"><a
								href="<?php echo site_url("cadastros/editar_atividade/$lista_dado->id")?>"
								class="btn btn-mini btn-primary" title="Editar informação"> <i
									class="fa fa-edit"></i>
							</a> <a id="buttonInativa<?=$lista_dado->id?>"
								onclick="excluir(<?=$lista_dado->id?>)"
								class="btn btn-mini btn-danger" title="Excluir"> <i
									id="icon<?=$lista_dado->id?>" class="fa fa-remove"></i>
							</a></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>	
<?php 
} else {
    ?>
	<div class="alert alert-warning">
		<strong>Desculpe!</strong> Nenhum treinamento cadastrado até o
		momento.
	</div>
	<?php } ?>	
</div>
<script>
function excluir(id) {

	var r = confirm("Desseja realmente excluir? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_atividade').'/' ?>"+id;
    
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