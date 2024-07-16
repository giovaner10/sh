<?php

$id = $dados[0];
$idChecklist = $dados[1];


$area = $this->db->query("SELECT * FROM plano_de_voo_areas WHERE id = '$id'");

$result = $area->row();

?>
<form action="" method="post" class="form-horizontal" role="form" enctype="multipart/form-data" name="inseri_itens"	id="inseri_itens">
	<div id="image_preview"><img id="previewing"  /></div>
	<div class="form-group">
		<input type="hidden" name="idarea" id="idarea" value="<?=$id?>">
		<input type="hidden" name="idchecklist" id="idchecklist" value="<?=$idChecklist?>">
		<label class="control-label col-sm-2" for="nome">&Aacute;rea:</label>
		<div class="col-sm-9">
			<input type="text" name="area" value="<?=$result->area?>" class="form-control" disabled>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2" for="nome">Item:</label>
		<div class="col-sm-9">
			<input type="text" name="item" id="item" value="" class="form-control" onkeyup="maiuscula(this)" maxlength="100" >
		</div>
	</div>	
	<div class="form-group">
		<div class="col-sm-7" style="margin-left: 6%;">
			<table style="width: 100%;">
				<tr>
					<td><label class="control-label col-sm-7" for="nome" style="text-align: left;">Tipo de resposta:</label></td>
				</tr>
				<tr>
					<td>
						<input type="radio" id="tipo_1" name="tipo" value="1" />
                        <label for="tipo_1" class="checklist-item-label">
                            <span class="btn btn-success">Sim</span>
                            <span class="btn btn-danger">Não</span>
                        </label>
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" id="tipo_2" name="tipo" value="2"  />
                        <label for="tipo_2" class="checklist-item-label">
                            <span>Ótimo, bom, regular, ruim</span>                           
                        </label>
					</td>
				</tr>	
				<tr>
					<td>
						<input type="radio" id="tipo_3" name="tipo" value="3" />
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
			<button type="submit" name="inserir" id="inserir" class="btn btn-success">Salvar</button>
		</div>
	</div>
	<div id="respostaInseri"></div>
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

$(document).ready(function (e) {
	$("#inseri_itens").on('submit',(function(e) {
		e.preventDefault();
    	$("#message").empty();
    	//$('#loading').show();
    	
    	if(document.inseri_itens.item.value == "")
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
            	url: "<?php echo site_url("cadastros/save_itens_area");?>",
            	type: "POST",
            	data: new FormData(this),
            	contentType: false,
            	cache: false,
            	processData:false,
            	beforeSend: function(){
                    document.getElementById("inserir").disabled = true;	  
                    $('#respostaInseri').fadeIn(); 
              	    $('#respostaInseri').html('<p style="width:30px; margin-top:2%;"><img src="<?php echo base_url('assets/images/enviando.gif');?>"></p>');
                },
            	success: function(data)   // A function to be called if request succeeds
            	{
            		//$('#loading').hide();
            		//$("#message").html(data);
                	
                      document.getElementById("inserir").disabled = false;
                  	  $("#respostaInseri").html(data);
                  	   
                  	  if (data == '<div class="alert alert-success">Dados inseridos com sucesso!</div>'){
              		 
                        $('#inseri_itens').each (function(){
                            this.reset();
                        });
                        $("#respostaInseri").css("display","block");
                        setTimeout(function(){
                            $("#myModalInserir").css("display","none");
                            $("#tabelaArea").load("<?php echo site_url("cadastros/view_tbl_area/$idChecklist")?>"); 
                        }, 300);
                        $('#myModalInserir').modal('hide');                         
                    }else{
    
                    }
            	}
        	});
    	}
	}));
});
</script>