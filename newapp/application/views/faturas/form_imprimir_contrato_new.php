<?php echo form_open('faturas/imprimir_por_cliente', array('target' => '_blank'))?>
<form id="imprimir-fatura-contrato-cliente">

    <input type="hidden" name="id_cliente" value="<?= $id_cliente?>">
    <div class="row">
        <div class="form-group col-md-6">
            <label>Selecione um contrato:</label>
            <select class="form-control" name="id_contrato" id="contrato"></select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Imprimir</button>
    </div>
</form>
<?php echo form_close()?>
<script>
    var contratos = JSON.parse('<?= $contratos?>');
    $.each(contratos, function (i, d) {
        $('#contrato').append('<option value="'+d.id+'">'+d.id+'</option>');
    })
    $('#contrato').select2({placeholder: 'Selecione um contrato'});

</script>

