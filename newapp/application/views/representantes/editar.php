<div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" />
</div>

<div class="formulario" style="float: none; margin-left: auto; margin-right: auto;">

  <form method="post" name="formcadastrar" id="ContactForm2" enctype="multipart/form-data" action="<?php echo site_url('representantes/editanto_email/'.$id)?>">
      <div class="span4" style="float: none; margin-left: auto; margin-right: auto;">
        <input type="text" id="descricao" name="descricao" placeholder="Email Showtecnologia" class="input" style="margin-bottom: 0px;"/> 
        <a href="#representantes"  class="btn btn-md" data-dismiss="modal" data-nome="representantes">Cancelar</a>
        <input type="submit" id="enviar" class="btn btn-primary btn-md" value="Editar"/>
      </div>

      
  </form>

</div>
