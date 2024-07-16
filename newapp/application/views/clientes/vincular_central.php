<div class="alert alert-info col-md-12">    
    <p>Seleção de Grupo é Opcional</p>
</div>
<div class="row">
    <div class="form-group col-md-6">
        <label>Escolha a central</label>
        <select name="central" class="form-control" id="central-id">
            <option value="">Selecione uma central</option>
            <?php foreach ($central as $c): ?>
                <option value="<?= $c->id ?>"><?= $c->nome ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-md-6">
        <label>Escolha o Grupo</label>
        <select name="grupo[]" class="form-control" id="grupo-id" multiple>
            <option value="" disabled >Selecione um grupo</option>
            <?php foreach ($grupos as $g): ?>
                <option value="<?= $g['id'] ?>"><?= $g['nome'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" id="btn_vincular_central" type="submit">Salvar</button>
</div>

<script>
    $(document).ready(function () {
        $('#central-id').select2();
        $('#grupo-id').select2({
            placeholder: 'Selecione um grupo',
        });
    });
</script>