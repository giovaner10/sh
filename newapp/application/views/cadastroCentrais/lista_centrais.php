<?php if($msg != ''):?>
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>CONCLUIDO!</strong>
        <?php echo $msg?>
    </div>
<?php endif;?>
<h3>Centrais</h3>
<hr>

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
