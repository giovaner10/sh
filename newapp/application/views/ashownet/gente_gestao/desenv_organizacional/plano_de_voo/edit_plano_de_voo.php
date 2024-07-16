<?php if ($this->auth->is_allowed_block('cad_plano_de_voo')){ ?>
<h3>Alterar de Plano de Voo</h3>
<hr>
<div class="container">
	<div class="panel panel-primar">
		<div class="panel-body">
        	<div class="row">
        		<div class="col-md-6">
        			<?php if(isset($totalFiles)) echo "Successfully uploaded ".count($totalFiles)." files"; ?>
                    <?php echo $this->session->flashdata('sucesso');?>
                    <?php echo $this->session->flashdata('error');?>
                </div>
        	</div>
            <form action="<?php echo site_url("cadastros/edit_plano_de_voo/$dados_plano->id")?>" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
            	<div class="form-group">
            		<label class="control-label col-sm-2" for="nome">Nome:</label>
            		<div class="col-sm-7">
            			<input type="text" name="nome" value="<?=$dados_plano->nome?>" class="form-control" required>
            		</div>
            	</div>
            	<div class="form-group">
            		<label class="control-label col-sm-2" for="obs">Descrição:</label>
            		<div class="col-sm-7">
            			<textarea class="form-control" rows="5" id="descricao" name="descricao"><?=$dados_plano->descricao?></textarea>
            		</div>
            	</div>       	
            	<div class="form-group"> 
            		<div class="col-sm-offset-2 col-sm-5">
            			<button type="submit" name="alterar" class="btn btn-success">Alterar</button>
            			<a href="<?php echo site_url('cadastros/listar_plano_de_voo');?>" class="btn btn-primary"> Voltar</a>		    
            		</div>
            	</div>
            </form>
            <hr>
            <div class="m-b-sm">
				<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modalArea">Incluir área</button>
			</div>
			<hr>
			<div id="modalArea" class="modal fade" role="dialog">
				<div class="modal-dialog modal-sm">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Cadastrar área</h4>
						</div>
						<div class="modal-body">
							<form class="form-horizontal ng-pristine ng-valid" name="add_area" id="add_area">
								<input type="hidden" name="idquestionario" id="idquestionario" value="<?=$dados_plano->id?>">
								<div class="form-group">
                            		<label class="control-label col-sm-6" for="nome" style="text-align: left;">Nome da área:</label>
                            		<div class="col-sm-12">
                            			<input type="text" name="nomeArea" id="nomeArea" value="" class="form-control" onkeyup="maiuscula(this)">
                            		</div>
                            	</div>
                            	<div class="form-group"> 
                            		<div class="col-sm-offset-9">
                            			<button type="button" name="salvarArea" id="salvarArea" class="btn btn-success">Salvar</button>
                            		</div>
                            	</div>
                            	<div id="resposta"></div>
							</form>
						</div>
						<div class="modal-footer"></div>
					</div>
				</div>
			</div>
			<div id="tabelaArea"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function () {
    $("#success").delay(2000).fadeOut("slow");
	var id = <?=$dados_plano->id?>;
	
    $.ajax({
        type: "POST",
        url: "<?php echo site_url("cadastros/view_tbl_area/$dados_plano->id");?>",
        beforeSend: function(){
            $('#tabelaArea').fadeIn(); 
            $('#tabelaArea').fadeIn(); 
            $('#tabelaArea').html('<p style="width:30px; margin:0 auto; margin-top:2%;"><img src="<?php echo base_url('assets/images/loading.gif');?>"></p>');
     	     },
        success: function(data) {
            $('#tabelaArea').html(data);
        }
    });

    $('#salvarArea').click(function(){      
    	if(document.add_area.nomeArea.value == "")
    	{
    		$("#resposta").html("Campo obrigatório");
			$("#nomeArea").focus();
			return false;
        }
        
    	 var id = <?=$dados_plano->id?>;
         $.ajax({  
              url: "<?php echo site_url("cadastros/save_area");?>",  //saveArea.php
              method:"POST",  
              data:$('#add_area').serialize(),
              beforeSend: function(){
                  document.getElementById("salvarArea").disabled = true;	  
                  $('#resposta').fadeIn(); 
            	  $('#resposta').html('<p style="width:30px; margin-top:2%;"><img src="<?php echo base_url('assets/images/enviando.gif');?>"></p>');
              },   
              success:function(data)  
              {  
            	  document.getElementById("salvarArea").disabled = false;
            	  $("#resposta").html(data);
            	   
            	  if (data == '<div class="alert alert-success">Cadastro realizado com sucesso!</div>'){
                      //Foi incluido com sucesso, ou seja, devo usar a fun��o Reset...
                      $('#add_area').each (function(){
                          this.reset();
                      });
                      $("#resposta").css("display","block");
                      setTimeout(function(){
                          $("#resposta").css("display","none");
                          $('#modalArea').modal('hide');
                      }, 1000);  
                      $("#tabelaArea").load("<?php echo site_url("cadastros/view_tbl_area/$dados_plano->id")?>");    
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
<?php 
}else{ 
    $this->load->view('erros/403');
 } ?>