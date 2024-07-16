<div class="resultado span4" style="float: none; margin-left: auto; margin-right: auto;"></div>
<div class="resultado2 span4" style="float: none; margin-left: auto; margin-right: auto;"></div>

<div class="pull-right">
    Arquivo: 
    <span class="label label-warning ">.pdf ou .jpg</span>
</div>

<div class="formulario">

    <form method="post" name="formcontato" id="ContactForm2" enctype="multipart/form-data" action="<?php echo site_url('usuarios/digitalizacao/'.$id_usuario)?>">
        <div class="span4" style="float: none; margin-left: auto; margin-right: auto;">
            <select name="descricao" required>
                <option>Selecione um tipo</option>
                <option value="Contrato de Experiência de Trabalho">Contrato de Experiência de Trabalho</option>
                <option value="Foto">Foto</option>
                <option value="Exame Admissional">Exame Admissional</option>
                <option value="Comprovante de Residência">Comprovante de Residência</option>
                <option value="CPF">CPF</option>
                <option value="RG">RG</option>
                <option value="Outro">Outro</option>
            </select>
            <div class="span4" style="float: none; margin-left: auto; margin-right: auto;" >
                <input type="file" name="arquivo" class="filestyle" data-buttonText="Arquivo" >
            </div> 
        </div>
        <div class="modal-footer" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">
            <a href="#contas"  class="btn btn-small" data-dismiss="modal" data-url="<?php echo site_url('usuarios/tab_listar_usuarios/')?>" 
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
        </thead>
    <?php if ($arquivos): ?>
        <tbody class="inner">
            <?php foreach ($arquivos as $arquivo): ?>
                <tr>
                    <td><?php echo $arquivo->id ?></td>
                    <td><?php echo $arquivo->descricao ?></td>
                    <td>
                        <a href="<?php echo site_url('usuarios/view_file/'.$arquivo->file)?>" target="_blank" class="btn btn-success btn-mini">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
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

$(":file").filestyle({buttonText: "Arquivo"});

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
                            '<a href="<?php echo site_url('usuarios/view_file')?>/',retorno.registro.file,'" target="_blank" class="btn btn-success btn-mini">',
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



