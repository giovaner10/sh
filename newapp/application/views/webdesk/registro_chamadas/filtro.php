<div style="display: none" id="filtro-registro-chamadas">
    <div class="card" style="margin-top: 2rem;">

        <div class="card-filtro-portal" id="card_filtro_portal">
            <h4 class="card_titulo" style="margin-left: 1px;">Filtro</h4>
        </div>

        <div class="card-body-portal">

            <div class="form-group filtro">
                <label for="dataInicio" class="label_input">Data inicial:</label>
                <input type="date" name="dataInicio" id="dataInicio" class="form-control" max="<?= date('Y-m-d') ?>" value="" />
                <div id="dataInicio-invalid" class="invalid-feedback"></div>
            </div>

            <div class="form-group filtro">
                <label for="dataFim" class="label_input">Data final:</label>
                <input type="date" name="dataFim" id="dataFim" class="form-control" max="<?= date('Y-m-d') ?>" value="" />
                <div id="dataFim-invalid" class="invalid-feedback"></div>
            </div>

            <button type="submit" id="btnFormFiltroRegistroChamadas" class=" btn-success btn-filtro">
                Filtrar
            </button>

            <button type="reset" id="btnResetRegistroChamadas" onclick="limparSelecteds()" class=" bnt-light btn-filtro" style="border:0 !important;background-color:white;margin-bottom:5px;">
                Limpar
            </button>

        </div>
        
    </div>
</div>