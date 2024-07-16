<div class="containner">
	<?php if ($this->auth->is_allowed_block('cad_politicas_formularios')){ ?>
	<div class="btn-group pull-right">
		<a class="btn btn-success btn-xs" href="<?php echo site_url('cadastros/cad_politicas_formulariosrh');?>">INCLUIR</a>
	</div>
	<?php } ?>
	<h5>Políticas e Formulários Gente & Gestão</h5>
	<h2 class="TitPage">Nossas Políticas, Formulários e Informações Gerais Gente & Gestão</h2>
	<?php 
	if(count($dados) > 0){
	
	?>
	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-body">				
				<table class="table table-condensed table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center"></th>
							<th class="text-center">Tipo</th>
							<th class="text-center">Descrição</th>
							<th class="text-center"></th>
						</tr>
					</thead>
					<tbody>
						<?php 
            			$query_politicas = $this->db->query("SELECT `informacao`.*, `arquivo`.`file`, `arquivo`.`path` 
                        FROM (`showtecsystem`.`cad_politicas_formularios` AS informacao) 
                        INNER JOIN `showtecsystem`.`arquivos` AS arquivo ON `informacao`.`id_arquivo` = `arquivo`.`id` ");
            			
            			foreach ($query_politicas->result_array() as $row_politica) {
            			?>
						<tr class="odd gradeX">
							<td><?php echo $row_politica['id'];?></td>
							<td><?php if($row_politica['tipo'] == "P"){ echo "POLÍTICAS"; }else{ echo "FORMULÁRIOS e INFORM. GERAIS";}?></td>
							<td>
								<span class="glyphicon glyphicon-link  btn-xs"></span>
								<a href="<?php echo base_url("uploads/politica_formulario/$row_politica[file]");?>" target="_blank" ><?php echo $row_politica['descricao'];?></a>
							</td>
                            <td style="text-align: center;">
                            	<a href="<?php echo site_url("cadastros/editar_informacao_formulario/$row_politica[id]")?>" class="btn btn-mini btn-primary" title="Editar informação">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a id="buttonInativa<?= $row_politica['id'] ?>" onclick="excluir(<?= $row_politica['id'] ?>)" class="btn btn-mini btn-danger" title="Excluir">
                                    <i id="icon<?= $row_politica['id'] ?>" class="fa fa-remove"></i>
                                </a>
                            </td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php }else { ?>
	<div class="alert alert-warning">
    	<strong>Desculpe!</strong> Nenhum arquivo cadastrado até o momento.
    </div>
	<?php } ?>
</div>
<script>
function excluir(id) {

	var r = confirm("Desseja realmente excluir? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_politica_formulario').'/' ?>"+id;
    
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