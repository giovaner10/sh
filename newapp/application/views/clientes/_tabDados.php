<div class="dados">
    <br>

    <input type="hidden" name="idCliente" class="idcliente">

    <div class="row">
        <div class="form-group col-md-8" style="border-left: 18px solid #03A9F4;">
            <label>Nome:</label>
            <input class="form-control nome dadosC" name="nome" type="text" disabled>
        </div>
        <div class="form-group col-md-4" style="border-left: 3px solid #03A9F4;">
            <label>Tipo:</label>
            <input class="form-control tipocliente" name="cliente" type="text" disabled>
        </div>
    </div>
    <div class="fisica hide">
        <div class="row">
            <div class="form-group col-md-4" style="border-left: 3px solid #03A9F4; margin-left: 15px;" >
                <label>Identidade:</label>
                <input class="form-control dadosC rg" name="identidade" id="identidade" type="text"  maxlength="20" disabled>
            </div>
            <div class="form-group col-md-2" style="border-left: 3px solid #03A9F4;">
                <label>Orgão Exp.:</label>
                <input class="form-control dadosC" name="rg_orgao" id="orgaoexp" type="text" disabled>
            </div>
            <div class="form-group col-md-3" style="border-left: 3px solid #03A9F4;">
                <label>Data de Nascimento:</label>
                <input class="form-control dadosC" name="data_nascimento" id="data_nascimento" type="date"  maxlength="20" disabled>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4" style="border-left: 3px solid #03A9F4; margin-left: 15px;">
                <label>CPF:</label>
                <input class="form-control dadosC cpf" name="cpf" id="cpf" type="text" disabled>
            </div>
        </div>
    </div>
    <div class="juridica">
        <div class="row">
            <div class="form-group col-md-8" style="border-left: 18px solid #03A9F4;">
                <label>Razão Social:</label>
                <input class="form-control dadosC" name="razao_social" id="razaosocial" type="text" disabled>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-8" style="border-left: 18px solid #03A9F4;">
                <label>Inscrição Estadual:</label>
                <input class="form-control dadosC ie" name="ie" id="ie" type="text" disabled>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3" style="border-left: 18px solid #03A9F4;">
                <label>CNPJ:</label>
                <input class="form-control dadosC cnpj" name="cnpj" id="cnpj" type="text" disabled>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-4" style="border-left: 18px solid #03A9F4;">
            <label name="empresa" class="empresa">Empresa:</label>
            <select name="empresa" class="form-control empresa" disabled>
                <option value="0"> Selecione a empresa </option>
                <option value="TRACKER"> SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA - ME </option>
                <option value="SIGAMY"> SIGAMY</option>
                <option value="SIMM2M"> SIMM2M</option>
                <option value="NORIO"> SIGA ME - NORIO MOMOI EPP</option>
                <option value="OMNILINK"> OMNILINK</option>
                <option value="EUA"> SHOW TECNOLOGIA EUA</option>
                <option value="SEGURADORA"> SEGURADORA</option>
            </select>
        </div>
    </div>

    <div class="form-group" style="border-left: 3px solid #03A9F4; padding-left: 16px;">
        <label>Vendedor: </label><br>
        <select id="id_vendedor" class="form-control vendedor dadosC" name="id_vendedor" type="text" required disabled>
            <option value="">Selecione um vendedor</option>
            <?php foreach ($consultores as $consultor): ?>
                <option value="<?= $consultor->id?>"><?=$consultor->nome?></option>
            <?php endforeach;?>
        </select>
    </div>

    <div class="row">
        <div class="form-group col-md-10" style="border-left: 18px solid #03A9F4;">
            <label>Opções: </label><br>
            <label class="checkbox-inline"><input type="checkbox" class="opentech dadosC" name="opentech" disabled>Opentech</label>
            <label class="checkbox-inline"><input type="checkbox" class="excessoVia dadosC" style="margin-right: 0px;" name="excessoVia" disabled>Exc. Velocidade em Via</label>
            <label class="checkbox-inline"><input type="checkbox" class="habilita_evt_personalizado dadosC" name="habilita_evt_personalizado" disabled>Habilitar Eventos Personalizados</label>            
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-4" style="border-left: 18px solid #03A9F4;"
            <label>Orgão: </label>
            <select name="orgao" class="form-control orgao dadosC" type="text" disabled>
                <option value="privado">PRIVADO</option>
                <option value="publico">PÚBLICO</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-4" style="border-left: 18px solid #03A9F4;">
            <label>GMT (+/- xx):</label>
			<input class="form-control dadosC gmt" type="text" name="gmt" id="gmt"
                style="margin-top: 0px" maxlength="3" disabled>
        </div>
	</div>
    

</div>



<script>

    $(document).ready(function()
    {
    	$('.rg').mask('0#');
    	$('.gmt').mask('Z90', {translation:  {'Z': {pattern: /[-+]/}}});
    });

    // $('#editar').on('click', function (e) {
    //     e.preventDefault();
    //     $('.editar').addClass('hide');
    //     $('#salvar').removeAttr('hidden');
    // })
    //
    // $('#editarJ').on('click', function (e) {
    //     e.preventDefault();
    //     $('.editarJ').addClass('hide');
    //     $('#salvarJ').removeAttr('hidden');
    //     $('.dadosJ').removeAttr('disabled')
    // })
    //
    // $('#cancelarJ').on('click', function (e) {
    //     e.preventDefault();
    //     $('.editarJ').removeClass('hide');
    //     $('#salvarJ').attr('hidden', true);
    //     $('.dadosJ').attr('disabled', true)
    // });
    //
</script>
