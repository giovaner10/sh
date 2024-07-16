<?php

$id = $dados[0];
$idChecklist =  $dados[1];

$areas = $this->db->query("SELECT * FROM plano_de_voo_areas WHERE id = '$id'");

foreach ($areas->result_array() as $result){
?>
<form class="form-horizontal ng-pristine ng-valid" name="edit_area"	id="edit_area">
	<input type="hidden" name="idarea" id="idarea" value="<?=$id?>">
	<input type="hidden" name="idchecklist" id="idchecklist" value="<?=$idChecklist?>">
	<div class="form-group">
		<label class="control-label col-sm-6" for="nome" style="text-align: left;">Nome da Área:</label>
		<div class="col-sm-12">
			<input type="text" name="nomeAreaEdit" id="nomeAreaEdit" value="<?=$result['area']?>" class="form-control" onkeyup="maiuscula(this)">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-9">
			<button type="button" name="editArea" id="editArea" class="btn btn-success">Alterar</button>
		</div>
	</div>
	<div id="respostaEdit"></div>
</form>
<?php } ?>
<script>
$(document).ready(function () {
 
    $('#editArea').click(function(){   	   
    	if(document.edit_area.nomeAreaEdit.value == "")
    	{
    		$("#resposta").html("Campo obrigatório");
			$("#editArea").focus();
			return false;
        }
        
    	 var id = <?=$id?>;
    	 var idChecklist = <?=$idChecklist?>;
    
     	 $.ajax({  
              url: "<?php echo site_url("cadastros/save_edit_area");?>",  
              method:"POST",  
              data:$('#edit_area').serialize(),
              beforeSend: function(){
                  document.getElementById("editArea").disabled = true;	  
                  $('#respostaEdit').fadeIn(); 
            	  $('#respostaEdit').html('<p style="width:30px; margin-top:2%;"><img src="<?php echo base_url('assets/images/enviando.gif');?>"></p>');
              },   
              success:function(data)  
              {  
                  document.getElementById("editArea").disabled = false;
            	  $("#respostaEdit").html(data);
            	   
            	  if (data == '<div class="alert alert-success">Dados alterados com sucesso!</div>'){
                      $("#respostaEdit").css("display","block");
                      setTimeout(function(){
                          $("#respostaEdit").css("display","none");
                          $("#tabelaArea").load("<?php echo site_url("cadastros/view_tbl_area/$idChecklist")?>"); 
                      }, 300);
                      	$('#myModalEditar').modal('hide');
                     
                  }else{}
              } 
         });  
    });  
});

function maiuscula(z){
    v = z.value.toUpperCase();
    z.value = v;
}
</script>