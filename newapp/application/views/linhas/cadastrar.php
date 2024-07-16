<div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div>

<div class="formulario" style="float: none; margin-left: auto; margin-right: auto;">

<form method="post" name="formcadastrar" id="formcadastrar" enctype="multipart/form-data" action="<?php echo site_url('linhas/adicionar')?>">

    <div class="modal-body">
        <div class="form-group">
          <label for="nome"><span class="label label-inverse">CCID</span></label>
          <input type="text" class="form-control" name="linha[ccid]" id="ccid" placeholder="CCID" required> 
        </div>
        <div class="form-group">
          <label for="email"><span class="label label-inverse">Linha</span></label>
          <input type="text" class="form-control" name="linha[numero]" id="numero" placeholder="Linha" required>
        </div>
        <div class="form-group">
          <label for="email"><span class="label label-inverse">Conta</span></label>
          <input type="text" class="form-control" name="linha[conta]" id="conta" placeholder="Conta" required>
        </div>
        <div class="form-group">
          <label for="senha"><span class="label label-inverse">Operadora</span></label>
          <input type="text" class="form-control" name="linha[operadora]" id="operadora" placeholder="Operadora" required>
        </div>
        <div class="row-fluid">
            <div class="form-actions modal-footer">
                <button type="reset" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                <input type="submit" id="enviar" value="Cadastrar" class="btn btn-success"/>
            </div>
        </div>
    </div>
    
  </form>

</div>







