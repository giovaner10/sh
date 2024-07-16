<div class="alert alert-info col-md-12">    
    <p>Seleção de Grupo é Opcional</p>
</div>
<div class="row">
    <div class="form-group col-md-6">
        <label>Escolha a central</label>
        <select name="Central" class="form-control" id="editar-central-id" disabled>
            <option value="">Selecione uma central</option>
            <?php foreach ($central as $c): ?>
                <option <?= $c->id == $centralUpdate['idCentral'] ? "selected" : "" ?> value="<?= $c->id ?>"><?= $c->nome ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-md-6">
        <label>Escolha o Grupo</label>
        <select name="idGrupo[]" class="form-control" id="editar-grupo-id" multiple>
            <option value="">Selecione um grupo</option>
            <?php foreach ($grupos as $g): ?>
                <option <?= in_array($g['id'], $gruposSelecionados)  ? "selected" : "" ?> value="<?= $g['id'] ?>"><?= $g['nome'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    
    <input type="hidden" name="idCentral" value="<?= $centralUpdate['idCentral'] ?>">
    <!-- <input type="hidden" name="idCompartilhamento" value="<?= $centralUpdate['idCompart'] ?>"> -->
    <input type="hidden" name="idCliente" value="<?= $centralUpdate['id_cliente'] ?>">
    <!-- <input type="hidden" name="status" value="<?= $centralUpdate['statusCompart'] ?>"> -->
    <!-- <input type="hidden" name="idGrupoAntigo" value="<?= $centralUpdate['idGrupoCompart'] ?>"> -->

</div>
<div class="modal-footer">
    <button class="btn btn-primary" id="btn_editar_central" type="submit">Salvar</button>
</div>

<script>
    $(document).ready(function () {
        $('#editar-central-id').select2();
        $('#editar-grupo-id').select2({
            placeholder: 'Selecione um grupo',
        });
    });
</script>