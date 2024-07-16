<script src="<?php echo base_url('assets/plugins/tinymce/tinymce.min.js');?>"></script>
<h3>Editar Sobre a empresa</h3>
<hr>
<div class="well well-small">
	<div class="row">
		<div class="col-md-6">
            <?php echo $this->session->flashdata('sucesso');?>
            <?php echo $this->session->flashdata('error');?>
        </div>
	</div>
	<form class="form-horizontal" method="post" name="formcontato" enctype="multipart/form-data" action="<?php echo site_url("cadastros/edit_sobre/$sobre->id")?>">
    	<div class="box-body">
    		<div class="form-group">
    			<label for="descricao" class="col-sm-2 control-label">Título</label>
    			<div class="col-sm-6">
    				<input type="text" class="form-control" name="titulo" id="titulo" value="<?=$sobre->titulo?>">
    			</div>
    		</div>
    		<div class="form-group">
    			<label class="col-sm-2 control-label">Sobre</label>
    			<div class="col-sm-6">
    				<textarea class="editor" name="sobre"><?=$sobre->descricao?></textarea>
    			</div>
    		</div>
    		<div class="form-group">
    			<label class="col-sm-2 control-label">Missão</label>
    			<div class="col-sm-6">
    				<textarea class="editor" name="missao"><?=$sobre->missao?></textarea>
    			</div>
    		</div>
    		<div class="form-group">
    			<label class="col-sm-2 control-label">Visão</label>
    			<div class="col-sm-6">
    				<textarea class="editor" name="visao"><?=$sobre->visao?></textarea>
    			</div>
    		</div>
    		<div class="form-group">
    			<label class="col-sm-2 control-label">Valores</label>
    			<div class="col-sm-6">
    				<textarea class="editor" name="valores"><?=$sobre->valores?></textarea>
    			</div>
    		</div>
    		<div class="box-footer">
        		<div class="form-group">
        			<label for="arquivo" class="col-sm-2 control-label"></label>
        			<div class="col-sm-6">
        				<input type="submit" id="enviar" class="btn btn-success btn-small" value="Alterar" />
        				<a href="<?php echo site_url("cadastros/listar_sobreaempresa")?>" class="btn btn-primary"> Voltar</a>
        			</div>
        		</div>
    		</div>
    	</div>
	</form>
</div>
<script>
$(document).ready(function() {
    tinymce.init({
            selector: "textarea",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste template"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | fontselect fontsizeselect",
            fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
            height: 200
        });

    });
</script>