<style>
#efeito{
    position:relative;
    top:0;
    padding:4px;    
    transition: all .2s ease-in-out
}

#efeito:hover{
    top:-4px;
    box-shadow:0 4px 3px #999
}
</style>
<?php

$id = $dados[0];

$areas = $this->db->query("SELECT * FROM plano_de_voo_areas WHERE id_questionario = '$id'");

if ($areas->num_rows() == 0){
    echo "Não há áreas cadastradas para o plano selecionado.";
}else{ 

    foreach ($areas->result_array() as $result){        
        
        $itens_questionario = $this->db->query("SELECT * FROM plano_de_voo_itens WHERE id_questionario = '$result[id_questionario]' AND id_area = '$result[id]' AND ativo = 1");
?>
<div class="box box-info">
	<div class="box-header with-border">
		<center><h3 class="box-title"><b><?php echo $result['area'];?></b></h3></center>
		<div class="box-tools pull-right">
			<i class="fa fa-plus incluir" id="efeito" data-id="<?=$result['id']?>" data-toggle="incluir" data-placement="top" title="Incluir Pergunta"></i>
    		<i class="fa fa-pencil-square-o editar" id="efeito" data-id="<?=$result['id']?>" data-toggle="editar" data-placement="top" title="Modificar"></i>
            <i class="fa fa-trash excluir" aria-hidden="true" id="efeito" data-id="<?=$result['id']?>" data-toggle="excluir" data-placement="top" title="Excluir"></i>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
	<?php  if ($itens_questionario->num_rows() != 0) { ?> 
		<div class="table-responsive">
			<div class="table-responsive">
    			<table class="tablesorter  table-responsive">
    				<thead>
    					<tr>
    						<td align="center"><b>Pergunta</b></td>
    						<td width="150px" align="center"><b>Tipo</b></td>
    						<td width="100px" align="center"><b>Ações</b></td>
    					</tr>
    				</thead>
                    <tbody>
                  	<?php $i = 1; foreach ($itens_questionario->result_array() as $item){ ?>
                  		<tr>
                  			<td width="600px"><?php echo $i.'º - '.$item['item'];?></td>
                  			<td align="center" width="400px">
                  			<?php if($item['tipo'] == '1'){ ?>
                      			<label for="tipo_1" class="checklist-item-label">
                                    <input type="radio" disabled="disabled">Sim
                                    <input type="radio" disabled="disabled">Não
                                </label>
                  			<?php } elseif($item['tipo'] == '2'){ ?>
                      			<label for="tipo_2" class="checklist-item-label">
                                    <input type="radio" disabled="disabled">Ótimo
                                    <input type="radio" disabled="disabled">Bom
                                    <input type="radio" disabled="disabled">Regular
                                    <input type="radio" disabled="disabled">Ruim
                                </label>
                  			<?php } else { ?>
                      			<label for="tipo_3" class="checklist-item-label">
                                    <textarea disabled="disabled">Resposta livre</textarea>                        
                                </label>
                  			<?php } ?>
                  			</td>
                  			<td align="center">
                  				<div class="box-tools pull-center">
              					    <i class="fa fa-pencil-square-o editarItem" id="efeito" data-id="<?=$item['id']?>" data-toggle="editar" data-placement="top" title="Modificar"></i>
                                    <i class="fa fa-trash excluirItem" aria-hidden="true" id="efeito" data-id="<?=$item['id']?>" data-toggle="excluir" data-placement="top" title="Excluir"></i>
                                </div>
                  			</td>
                  		</tr>
                  	<?php $i++;} ?>
                	</tbody>
            	</table>
    		</div>
		</div>	
		<?php  } ?> 						
	</div>
</div>
<!-- Modal inserir -->
<div id="myModalInserir" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cadastrar Pergunta</h4>
			</div>
			<div class="modal-body">
				<div id="tabelaAreaInseri"></div>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>
<!-- Modal editar -->
<div id="myModalEditar" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Altera Área</h4>
			</div>
			<div class="modal-body">
				<div id="tabelaAreaEdit"></div>	
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>
<!-- Modal excluir -->
<div class="modal fade" id="myModalExcluir" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<p>Quer realmente fazer isso? Todas as perguntas relacionadas serão apagadas. </p>
				<div id="tabelaAreaExclui"></div>	
			</div>
			<div class="modal-footer">
				<a type="button" class="btn btn-danger" id="delete">Apagar área</a>
				<button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal editar item -->
<div id="myModalEditarItem" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Alterar Item</h4>
			</div>
			<div class="modal-body">
				<div id="tabelaEditItens"></div>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>
<!-- Modal excluir item -->
<div class="modal fade" id="myModalExcluirItem" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<p>Quer realmente fazer isso? As informações serão perdidas e não será possível recupera-las.</p>
				<div id="tabelaAreaExcluiItem"></div>	
			</div>
			<div class="modal-footer">
				<a type="button" class="btn btn-danger" id="deleteItem">Apagar Pergunta</a>
				<button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
			</div>
		</div>
	</div>
</div>
<?php } } ?>
<script>
$(function () {
	$('[data-toggle="incluir"]').tooltip()
	$('[data-toggle="editar"]').tooltip()
	$('[data-toggle="excluir"]').tooltip()
})

$(".incluir").click(function() {
   
    var id = $(this)[0].dataset.id;
	var idChecklist = <?=$id?>;
	
	$('#myModalInserir').modal('show');
	$.ajax({
        type: "POST",
        url: "<?php echo site_url("cadastros/view_itens_area");?>/"+id+"/"+idChecklist,
        beforeSend: function(){
            $('#tabelaAreaInseri').fadeIn(); 
      	  	$('#tabelaAreaInseri').html('<p style="margin-top:2%; text-align: center;"><img src="<?php echo base_url('assets/images/loading.gif');?>"></p>');
        },  
        success: function(data) {
            $('#tabelaAreaInseri').html(data);
        }
    });
});

$(".editar").click(function() {
   
    var id = $(this)[0].dataset.id;
	var idChecklist = <?=$id?>;
	
    $('#myModalEditar').modal('show'); 
    $.ajax({
        type: "POST",
        url: "<?php echo site_url("cadastros/altera_area");?>/"+id+"/"+idChecklist,
        beforeSend: function(){
            $('#tabelaAreaEdit').fadeIn(); 
      	  	$('#tabelaAreaEdit').html('<p style="margin-top:2%; text-align: center;"><img src="<?php echo base_url('assets/images/loading.gif');?>"></p>');
        },   
        success: function(data) {
            $('#tabelaAreaEdit').html(data);
        }
    });    
});

$(".excluir").click(function() {
	var id = $(this)[0].dataset.id;
	
    $('#myModalExcluir').modal('show'); 

    $("#delete").click(function() {    	
    	
        $.ajax({
            type: "POST",
            url: "<?php echo site_url("cadastros/delete_area");?>/"+id,
            beforeSend: function(){
                $('#tabelaAreaExclui').fadeIn(); 
          	  	$('#tabelaAreaExclui').html('<p style="margin-top:2%; text-align: center;"><img src="<?php echo base_url('assets/images/loading.gif');?>"></p>');
            }, 
            success: function(data) {
	        	$('#tabelaAreaExclui').html(data);
               
          	 	if (data == '<div class="alert alert-success">Dados deletados com sucesso!</div>'){
                    $("#tabelaAreaExclui").css("display","block");
                    setTimeout(function(){
                        $("#tabelaAreaExclui").css("display","none");
                        $("#tabelaArea").load("<?php echo site_url("cadastros/view_tbl_area/$id")?>"); 
                    }, 500);
                    	$('#myModalExcluir').modal('hide');                   
                }else{}
            }
        });    
    });
});

$(".editarItem").click(function() {
	   
    var id = $(this)[0].dataset.id;
	var idChecklist = <?=$id?>;

    $('#myModalEditarItem').modal('show'); 
    $.ajax({
        type: "POST",
        url: "<?php echo site_url("cadastros/view_update_item");?>/"+id+"/"+idChecklist,
        beforeSend: function(){
            $('#tabelaEditItens').fadeIn(); 
      	  	$('#tabelaEditItens').html('<p style="margin-top:2%; text-align: center;"><img src="<?php echo base_url('assets/images/loading.gif');?>"></p>');
        }, 
        success: function(data) {
            $('#tabelaEditItens').html(data);
        }
    });    
});

$(".excluirItem").click(function() {
	var id = $(this)[0].dataset.id;
	var idChecklist = <?=$id?>;
    $('#myModalExcluirItem').modal('show'); 

    $("#deleteItem").click(function() {    	
    	
        $.ajax({
            type: "POST",
            url: "<?php echo site_url("cadastros/deletar_pergunta");?>/"+id,
            beforeSend: function(){
                $('#tabelaAreaExcluiItem').fadeIn(); 
          	  	$('#tabelaAreaExcluiItem').html('<p style="margin-top:2%; text-align: center;"><img src="<?php echo base_url('assets/images/loading.gif');?>"></p>');
            }, 
            success: function(data) {
	        	$('#tabelaAreaExcluiItem').html(data);
               
          	 	if (data == '<div class="alert alert-success">Dados deletados com sucesso!</div>'){
                    $("#tabelaAreaExcluiItem").css("display","block");
                    setTimeout(function(){
                        $("#tabelaAreaExcluiItem").css("display","none");
                        $("#tabelaArea").load("<?php echo site_url("cadastros/view_tbl_area/$id")?>"); 
                    }, 500);
                    	$('#myModalExcluirItem').modal('hide');                   
                }else{}
            }
        });    
    });
});
</script>