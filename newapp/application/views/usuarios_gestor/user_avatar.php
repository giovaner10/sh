<?php 
$url_img = "https://gestor.showtecnologia.com/sistema/newapp/assets/images/".$id_usuario.".jpg";
if (@getimagesize("$url_img")) { ?>
<div style="border: 2px solid #D8D8D8; margin-left: 30%; margin-right: 30%; text-align: center; box-shadow: 3px -1px 3px #BDBDBD">
	<img src="https://gestor.showtecnologia.com/sistema/newapp/assets/images/<?php echo $id_usuario; ?>.jpg" style="padding: 10px; width: 150px;">
</div>
<?php }else { ?>
<div style="border: 2px solid #D8D8D8; margin-left: 30%; margin-right: 30%; text-align: center; box-shadow: 3px -1px 3px #BDBDBD">
	<img src="https://gestor.showtecnologia.com/sistema/newapp/assets/images/user.png" style="padding: 10px; width: 150px;">
</div>
<?php } ?>

<form action="<?php echo site_url('usuarios_gestor/atualizar_avatar/'.$id_cliente."/".$id_usuario)?>" method="post" enctype="multipart/form-data" name="cadastro">
	<div class="row-fluid">
		<div class="control-group" style="border: 1px solid #D8D8D8; padding: 10px;">
			<span class="label label-danger">Tamanho maximo: 800x600px</span>
			<label class="control-label">Selecione o Arquivo:</label> 
			<input type="file" name="arquivo" required />
		</div>
	</div>
	<div class="form-actions">
		<input type="submit" class="btn btn-primary" value="Salvar">
			<i class="icon-ok icon-white"></i>
	</div>
</form>