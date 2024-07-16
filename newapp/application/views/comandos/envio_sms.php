<h3>Comando SMS -> <i class="fa fa-envelope-open"></i></h3>
<section class="container-fluid">
    <form method="post" id="formSend" action="<?php echo site_url('comandos/send') ?>">
        <div id="alerta_show" class="alert">
            <b id="mensagem"></b><i class='fa fa-spinner fa-spin fa-fw'></i>
        </div>
        <div class="col-md-6">
            <label for="number">Nº:</label>
            <input type="text" id="number" class="form-control" name="number">
            <label for="apn">APN:</label>
            <select id="apn" name="apn" class="form-control">
                <option>Selecione...</option>
                <option value="show.vivo.com.br">Vivo</option>
                <option value="show.claro.com.br">Claro</option>
            </select>

            <label for="user">Usuário:</label>
            <input type="text" id="user" name="user" class="form-control">

            <label for="senha">Senha:</label>
            <input type="text" id="senha" name="senha" readonly class="form-control">

            <label for="serial">Serial:</label> <input type="text" id="serial" name="serial"
				autocomplete="off" data-provide="typeahead"
				placeholder="<?php echo lang('visualizar_todos')?>"
				value="<?php echo $this->input->post('serial')?>" data-items="5"
				data-source='<?php echo $seriais?>' class="form-control"> <br>
			<button class="btn btn-primary" type="submit">Enviar</button>
		</div>        
    </form>
</section>
<script>
    $(document).ready(function () {
        var alerta = $('#alerta_show');
        alerta.hide();
        $('#apn').change(function () {
            var option = $(this).val();
            if (option === "show.vivo.com.br") {
                $('#senha').val("vivo");
            } else if (option === "show.claro.com.br") {
                $('#senha').val("claro");
            } else {
                $('#senha').val("");
            }
        });

    })
</script>