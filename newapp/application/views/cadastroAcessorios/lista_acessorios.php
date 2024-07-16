<?php if($msg != ''):?>
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>CONCLUIDO!</strong>
        <?php echo $msg?>
    </div>
<?php endif;?>
<h3>Acessórios</h3>
<hr>
<!--<div class="well well-small">
	<div class="span1">
		<div class="btn-group">
		</div>
	</div>
	<div class="span7">
	</div>
	<?php echo form_open('clientes/view')?>

	<?php echo form_close()?>
	<div class="clearfix"></div>
</div>
-->
<div class="row-fluid">
    <div id="conteudo"></div>
</div>


<script>

    $(document).ready(function(){

        var default_control = "<?php echo site_url('clientes/ajax_listar/') ?>";
        var clientes = new ModuleClientes();
        clientes.listar(default_control);

        var app = new App();
        app.init();

    });

</script>
