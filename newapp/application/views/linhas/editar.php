<div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div>

<div class="formulario" style="float: none; margin-left: auto; margin-right: auto;">

<form method="post" name="formeditar" id="formcadastrar" enctype="multipart/form-data" action="<?= site_url('linhas/editar/'.$id_linha)?>">
    <div class="modal-body">
        <div class="form-group">
          <label for="ccid"><span class="label label-inverse">CCID</span></label>
          <input type="text" class="form-control" name="ccid" id="ccid" value="<?= $linha->ccid; ?>" required> 
        </div>
        <div class="form-group">
          <label for="numero"><span class="label label-inverse">Linha</span></label>
          <input type="text" class="form-control" name="numero" id="numero" value="<?= $linha->numero; ?>" required>
        </div>
        <div class="form-group">
          <label for="operadora"><span class="label label-inverse">Operadora</span></label>
          <input type="text" class="form-control" name="operadora" id="operadora" value="<?= $linha->operadora; ?>" required>
        </div>
        <div class="row-fluid">
            <div class="form-actions modal-footer">
                <input type="submit" id="enviar" value="Cadastrar" class="btn btn-success"/>
            </div>
        </div>
    </div>
    
  </form>

</div>







