<div class="resultado span4" style="float: none; margin-left: auto; margin-right: auto;"></div>
<div class="resultado2 span4" style="float: none; margin-left: auto; margin-right: auto;"></div>
<div class="modal-header" style="text-align: center;">
    	
        <h4 id="myModalLabel">Digitalizar Documento</h3>      

<div class="pull-right">
    Arquivo: 
    <span class="label label-warning ">.pdf</span>
</div>
<div class="formulario">

    <form method="post" name="formcontato" id="ContactForm2" enctype="multipart/form-data" action="<?php echo site_url('digitalizar/digitalizacao/')?>">
        <div class="span4" style="float: none; margin-left: auto; margin-right: auto;">
            <input type="text" id="descricao" name="descricao" placeholder="Descrição" class="input" />
            <div class="span4" style="float: none; margin-left: auto; margin-right: auto;" >
                <input type="file" name="arquivo" class="filestyle" data-buttonText="Arquivo">
            </div> 
        </div>
        <div class="modal-footer" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">
            <a href="#contas"  class="btn btn-small" data-dismiss="modal" data-url="<?php echo site_url('contas/tab_listar_contas/')?>" 
            data-nome="contas"  data-toggle="tab" id="atualizar-contas">Cancelar</a>
            <input type="submit" id="enviar" class="btn btn-primary btn-small" value="Enviar"/>
        </div>
    </form>
</div>

<div class="span4 tabela" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">
    <table class="table table-hover table-bordered">
        <thead>
            <th>#</th>
            <th>Descrição</th>
            <th>Visualizar</th>
            <th>Remover</th>
        </thead>
    <?php if ($arquivos): ?>
        <tbody class="inner">
            <?php foreach ($arquivos as $arquivo): ?>
            	<?php //pr($arquivo);die ?>
                <tr>
                    <td><?php echo $arquivo->id ?></td>
                    <td><?php echo $arquivo->descricao ?></td>
                    <td>
                        <a href="<?php echo site_url('digitalizar/view_file/'.$arquivo->arquivo)?>" target="_blank" class="btn btn-success btn-mini">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
                    </td>
                    <td><a href="<?php echo site_url('digitalizar/remove/'.$arquivo->id)?>" class="btn btn-mini btn-danger excluir"><i
						class="icon-remove icon-white"></i> </a>
				</td>
                </tr>
            <?php endforeach ?>
        </tbody>
    <?php else: ?>
        <tbody class="inner"> 
        </tbody>
    <?php endif ?> 
    </table>
</div>

<script type="text/javascript">


//$(":file").filestyle({buttonText: "Arquivo"});

$(document).ready(function(){
    $(".resultado").hide();
    $(".resultado2").hide();
    $("#ContactForm2").ajaxForm({
        target: '.resultado',
        dataType: 'json',
        beforeSend:function(){
            var carregar = [
                '<div class="progress progress-striped active">',
                  '<div class="bar" style="width: 100%;"><b>Carregando...</b></div>',
                '</div>'
            ].join('');
            $('.resultado2').html(carregar);
            $(".resultado2").show();
            $(".resultado").hide();
            $("#enviar").hide();
        },
        success: function(retorno){
            if (retorno.success) {
                var tpl = [
                    '<tr>',
                        '<td>',retorno.registro.id,'</td>',
                        '<td>',retorno.registro.descricao,'</td>',
                        '<td>',
                            '<a href="<?php echo site_url('contas/view_file')?>/',retorno.registro.file,'" target="_blank" class="btn btn-success btn-mini">',
                            '<i class="icon-eye-open icon-white"></i>',
                            'Visualizar',
                            '</a>',
                        '</td>',
                    '</tr>'
                ].join('');

                $('.inner').append(tpl);
            }

            $(".resultado").html(retorno.mensagem);
            $(".resultado").show();
            $(".resultado2").hide();
            $("#enviar").show();
        }
        

    });

});




</script>





</div>

