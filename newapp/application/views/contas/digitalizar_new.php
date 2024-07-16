<script	src="<?php echo base_url('assets') ?>/plugins/form-jquery/jquery.form.min.js"></script>

<div class="pull-right">
    Arquivo: 
    <span class="label label-warning " style="display: inline-block;">.pdf</span>
</div>

<div class="formulario">

    <form method="post" name="formcontato" id="ContactForm2" enctype="multipart/form-data" action="<?php echo site_url('contas/digitalizacao/'.$id_conta)?>">
        <div class="span4" style="float: none; margin-left: auto; margin-right: auto;">
            <input type="text" id="descricao" name="descricao" placeholder="Descrição" class="form-control input-sm input" style="margin-bottom: 10px;"/>
                   
            <label for="comprovante" style="cursor: pointer;"><input type="checkbox" value="1" id="comprovante" name="comprovante"/>&nbsp;Comprovante de pagamento</label>
                
            <div style="float: none; margin-left: auto; margin-right: auto;" >
                <input type="file" name="arquivo">
            </div> 
        </div>
        <div class="modal-footer" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">
            <a href="#contas"  class="btn" data-dismiss="modal" data-url="<?php echo site_url('contas/tab_listar_contas/')?>" 
            data-nome="contas"  data-toggle="tab" id="atualizar-contas" style="background-color: red;color:white">Cancelar</a>
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
                        <a href="<?php echo site_url('contas/view_file/'.$arquivo->file)?>" target="_blank" class="btn btn-primary">
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

$(document).ready(function(){
    $("#ContactForm2").ajaxForm({
        target: '.resultado',
        dataType: 'json',
        beforeSend:function(){
            $('#enviar').attr('disabled', true).val('Enviando...')
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

                $('tbody.inner').append(tpl);
                $('#enviar').attr('disabled', false).val('Enviar');
                alert('Arquivo enviado com sucesso.');
            }else{
                alert('Não foi possível enviar o arquivo. Verifique os dados e tente novamente.');
                $('#enviar').attr('disabled', false).val('Enviar');
            }
        },
        error:function(error){
            alert('Não foi possível enviar o arquivo. Verifique os dados e tente novamente.');
            $('#enviar').attr('disabled', false).val('Enviar');
        }
        

    });

});




</script>

<style type="text/css">
    .btn,.label{
        vertical-align: top !important;
    }
</style>


