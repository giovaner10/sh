<?php
    extract($_REQUEST);
    
?>

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<form action="includes/save.php" method="post" enctype="multipart/form-data" target="divResultado" > 
    <input id="pasta" name="pasta" type="hidden" value="<?php echo $Id;?>"/>
        <label for "descrever">Descrição:</label>
        <input id="descricao" name="descricao" type="text"  size="100"/>
        <br>
        <label for="file">Arquivo a ser enviado:</label>
	<input id="file" name="file" type="file" />
        <br>
	<input type="submit" value="Enviar" />
</form>

<iframe src="" id="divResultado" width="100%" height="120" style="border: 0"></iframe>

<?php
if ($Id=='chips'){
?>
Processar lista enviada
<iframe src="" id="impResultado" width="100%" height="120" style="border: 0"></iframe>
<?php } ?>
