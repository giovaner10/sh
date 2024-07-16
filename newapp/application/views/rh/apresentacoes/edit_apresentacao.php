<h3>Editar Apresentação</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<form class="form-horizontal" method="post" name="formcontato" action="<?php echo site_url("cadastros/edit_apresentacao/$apresentacao->id")?>">
    	<div class="box-body">
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Descrição</label>
    			<div class="col-sm-6">
    				<input type="text" class="form-control" name="descricao" id="descricao" value="<?=$apresentacao->descricao?>" required>
    			</div>
    		</div>
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Alterar" />
        				<a href="<?php echo site_url("cadastros/listar_apresentacoes")?>" class="btn btn-primary"> Voltar</a>
        			</div>
        		</div>
    		</div>
    	</div>
	</form>
	<?php 
    if(count($apresentacoes) > 0){
    ?>
    <div class="col-sm-6">
        <table id="table" class="table table-bordered table-hover">
        	<thead style="background-color: #0072cc !important; color: white !important;">
        		<th style="text-align: center;"></th>
        		<th style="text-align: center;">Foto</th>
                <th style="text-align: center;">Excluir</th>
        	</thead>
        	<tbody>
        	<?php foreach ($apresentacoes as $apresentacao){
        	    $ext = substr($apresentacao->file, (strlen($apresentacao->file) - 3), strlen($apresentacao->file));
        	    ?>
        		<tr>
        			<td style="text-align: center; vertical-align: middle;"><?php echo $apresentacao->ordem;?></td>
        			<?php if($ext == "ppt" || $ext == "ptx"){?>
        			<td>Apresentação Powerpoint</td>
        			<?php }else{ ?>
        			<td style="text-align: center; vertical-align: middle;"><img src="<?php echo base_url("uploads/apresentacoes/$apresentacao->file");?>" width="200px"></td>
        			<?php } ?>
        			<td style="text-align: center; vertical-align: middle;">
        				<a onclick="excluir(<?=$apresentacao->id?>)" class="btn btn-mini btn-danger" title="Excluir">
                            <i class="fa fa-remove"></i>
                        </a>
        			</td>
        		</tr>
        		<?php }?>
        	</tbody>
        </table>
    </div>
    <?php }else{ } ?>
</div>
<script>
function excluir(id) {

	var r = confirm("Desseja realmente excluir essa imagem? Esse procedimento é irreversível.");

	if (r == true) { 
        var url = "<?= site_url('cadastros/excluir_apresentacao_imagem').'/' ?>"+id;
    
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