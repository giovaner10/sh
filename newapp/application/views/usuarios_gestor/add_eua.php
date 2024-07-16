<style type="text/css">
    .display{display: none;}
</style>
<?php echo form_open('', '', array('id_cliente' => $cliente->id))?>

<div class="row-fluid">
    <div class="span12">
        <div class="control-group">
            <label class="control-label">Cliente:</label>
            <?php echo $cliente->nome?>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Nome:</label>
            <input type="text" name="nome_usuario" required />
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Email:</label>
            <input type="email" name="usuario" required />
        </div>
    </div>

</div>

<div class="row-fluid">
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Senha:</label>
            <input type="password" name="senha" required />
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Tax Id:</label>
            <input type="text" name="cpf" id="cpf" required />
        </div>
    </div>

</div>
<div class="row-fluid">
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Função:</label>
            <select id="option" name="tipo_usuario" required>
                <option value="">Escolha uma opção</option>
                <option value="administrador">Administração</option>
                <option value="monitoramento">Monitoramento</option>
                <option value="posto">Posto</option>
            </select>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Tel.:</label>
            <input type="text" id="tel" name="celular" required />
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span6">
        <div class="form-group">
            <label for="group">Grupo: </label>
            <select class="form-control" name="group" id="group">
                <?php foreach ($groups as $group): ?>
                    <option value="<?= $group->id; ?>"><?= $group->nome; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div  class="span6">
        <div id="div" class="control-group display">
            <label class="control-label">Posto:</label>
            <select id="posto" name="posto"></select>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <button type="submit" class="btn btn-primary">
            <i class="icon-ok icon-white"></i> Salvar
        </button>
        <a onclick="fecharModal('#novo_usuario');" class="btn fechar">Fechar</a>
    </div>
</div>

<?php echo form_close()?>

<script type="text/javascript">
    var id = <?php echo $cliente->id; ?>

        $(function($) {
            $("#cpf").mask("999-99-9999");
            $("#tel").mask("(999) 9999-9999");
        });

    $('select#option').on('change', function() {
        if ($(this).val() == 'posto') {
            $('#posto').attr('required', true);
            $('#div').removeClass('display');
            $.getJSON('http://localhost/shownet/newapp/index.php/posto/getPosto', {id:id}, function(callback) {
                $('#posto').append('<option value="">Escolha uma opção</option>');
                $.each(callback, function(i, obj) {
                    $('#posto').append('<option value="'+obj.pk+'">'+obj.name+'</option>');
                });
            });
        } else {
            $('#div').addClass('display');
        }
    });
</script>