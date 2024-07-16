<?php

$id = $dados[0];
$idChecklist = $dados[1];

$item = $this->db->query("SELECT * FROM plano_de_voo_itens i, plano_de_voo_areas a WHERE i.`id_area`=i.`id_area` AND i.id='$id' GROUP BY i.id");

$result = $item->row();

?>
<form action="" method="post" class="form-horizontal" role="form" enctype="multipart/form-data" name="altera_iten"	id="altera_iten">
	<div id="image_preview"><img id="previewing"  /></div>
	<div class="form-group">
		<input type="hidden" name="iditem" id="iditem" value="<?=$id?>">
		<input type="hidden" name="idarea" id="idarea" value="<?=$result->id_area?>">
		<label class="control-label col-sm-2" for="nome">&Aacute;rea:</label>
		<div class="col-sm-9">
			<input type="text" name="area" value="<?=$result->area?>" class="form-control" disabled>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2" for="nome">Item:</label>
		<div class="col-sm-9">
			<input type="text" name="item" id="item" value="<?=$result->item?>" class="form-control" onkeyup="maiuscula(this)" maxlength="100">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-7" style="margin-left: 6%;">
			<table>
				<tr>
					<td><label class="control-label col-sm-7" for="nome" style="text-align: left;">Tipo de resposta:</label></td>
				</tr>
				<tr>
					<td>
						<input type="radio" id="tipo_1" name="tipo" value="1" <?php if($result->tipo == "1"){ echo "checked='checked'";}?>/>
						<label for="tipo_1" class="checklist-item-label">
                            <span class="btn btn-success">Sim</span>
                            <span class="btn btn-danger">Não</span>
                        </label>
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" id="tipo_2" name="tipo" value="2" <?php if($result->tipo == "2"){ echo "checked='checked'";}?>/>
                        <label for="tipo_2" class="checklist-item-label">
                            <span>Ótimo, bom, regular, ruim</span>                           
                        </label>
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" id="tipo_3" name="tipo" value="3" <?php if($result->tipo == "3"){ echo "checked='checked'";}?>/>
                        <label for="tipo_3" class="checklist-item-label">
                            <span><textarea disabled="disabled">Campo de texto</textarea></span>                           
                        </label>
					</td>
				</tr>				
			</table>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-9">
			<button type="submit" name="alteraItem" id="alteraItem" class="btn btn-success">Alterar</button>
		</div>
	</div>
	<div id="respostaInseriItem"></div>
</form>
<h4 id='loading' ></h4>
<div id="message"></div>
<script>
function maiuscula(z){
    v = z.value.toUpperCase();
    z.value = v;
}

$('input[name="tipo"]').change(function () {
    if ($('input[name="tipo"]:checked').val() == "1") {
        $('.tipo_resposta_1').show();
    } else {
        $('.tipo_resposta_1').hide();
    }

    if ($('input[name="tipo"]:checked').val() == "2") {
        $('.tipo_resposta_2').show();
    } else {
        $('.tipo_resposta_2').hide();
    }

    if ($('input[name="tipo"]:checked').val() == "3") {
        $('.tipo_resposta_3').show();
    } else {
        $('.tipo_resposta_3').hide();
    }    
    
});

<?php if($result->tipo == "1"){ ?>
	$('.tipo_resposta_1').show();
<?php }else{ ?>
	$('.tipo_resposta_1').hide();
<?php } ?>

<?php if($result->tipo == "2"){ ?>
	$('.tipo_resposta_2').show();
<?php }else{ ?>
	$('.tipo_resposta_2').hide();
<?php } ?>

<?php if($result->tipo == "3"){ ?>
	$('.tipo_resposta_3').show();
<?php }else{ ?>
	$('.tipo_resposta_3').hide();
<?php } ?>

$(document).ready(function (e) {
	$("#altera_iten").on('submit',(function(e) {
		e.preventDefault();
    	$("#message").empty();
    	//$('#loading').show();
    	
    	if(document.altera_iten.item.value == "")
    	{
    		alert('Favor informe o nome do item.');
			$("#item").focus();
			return false;
        } 

    	if(document.getElementById("tipo_1").checked == false && document.getElementById("tipo_2").checked == false && document.getElementById("tipo_3").checked == false)
    	{
    		alert('Favor selecione um tipo de resposta.');
    	}else{

    		var id = <?=$id?>;
       	 	var idChecklist = <?=$idChecklist?>;
        	
        	$.ajax({
            	url: "<?php echo site_url("cadastros/update_item");?>",
            	type: "POST",
            	data: new FormData(this),
            	contentType: false,
            	cache: false,
            	processData:false,
            	beforeSend: function(){
                    document.getElementById("alteraItem").disabled = true;	  
                    $('#respostaInseriItem').fadeIn(); 
              	  	$('#respostaInseriItem').html('<p style="width:30px; margin-top:2%;"><img src="<?php echo base_url('assets/images/enviando.gif');?>"></p>');
                },   
            	success: function(data)   // A function to be called if request succeeds
            	{
            		//$('#loading').hide();
            		//$("#message").html(data);
                	
            		document.getElementById("alteraItem").disabled = false;
              	  $("#respostaInseriItem").html(data);
              	   
              	  if (data == '<div class="alert alert-success">Dados atualizados com sucesso!</div>'){
              		  $("#respostaInseriItem").css("display","block");
                        setTimeout(function(){
                            $("#myModalEditarItem").css("display","none");
                            $("#tabelaArea").load("<?php echo site_url("cadastros/view_tbl_area/$idChecklist")?>"); 
                        }, 300);
                        $('#myModalEditarItem').modal('hide');            
                    }else{}
            	}
        	});
    	}
	}));
});
</script>