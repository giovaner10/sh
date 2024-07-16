<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Digitalizar Contrato</h4>
</div>
<br>
<div class="resultado col-sm-4" style="float: none; margin-left: auto; margin-right: auto;"></div>
<div class="resultado2 col-sm-4" style="float: none; margin-left: auto; margin-right: auto;"></div>
<div style="float: right;">
    Arquivo: 
    <span class="label label-warning ">.pdf</span>
</div>
<div class="formulario">
    <form method="post" name="formcontato" id="ContactForm2" enctype="multipart/form-data" action="<?php echo site_url('contratos/digitalizacao_contrato/'.$id_contrato)?>">
       <div class="col-sm-12">
           	<div class="form-group">
           		<input type="text" id="descricao" name="descricao" placeholder="Descrição" class="form-control" />       
           	</div>    
            <div class="form-group">
                <input type="file" name="arquivo" class="form-control" data-buttonText="Arquivo">
            </div>        
            <div class="col-md-12">
                <div class="modal-footer" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">
                    <!-- <button class="btn btn-small" data-dismiss="modal" aria-hidden="true">Fechar</button> -->
                    <a href="<?php echo site_url('contratos_eptc/listar_contratos')?>"  class="btn btn-small">Fechar/Atualizar</a>
                    <input type="submit" id="enviar" class="btn btn-primary btn-small"/>
                </div>
            </div>
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
                        <a href="<?php echo site_url('contratos/visualizar_contrato/'.$arquivo->file)?>" target="_blank" class="btn btn-success btn-mini">
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

    $('#atualizar-contratos').click(function (e) {
            $('#loading').css('display', 'block');
             var sessao = $(this).attr('data-nome');
             url = 'http://localhost/financeiro-show/newapp/index.php/util/tab_active/tab_cliente/'+sessao;
             $.get(url, function(formulario){});

             var urlTab = $(this).attr("data-url");
             var href = this.hash;
             var pane = $(this);

             $(href).load(urlTab, function(result){
                    pane.tab('show');
                    $('#loading').css('display', 'none');
            });
        });


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
                            '<a href="<?php echo site_url('contratos/visualizar_contrato')?>/',retorno.registro.file,'" target="_blank" class="btn btn-success btn-mini">',
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
            //$("#ContactForm2").resetForm();
        }
        

    });

});
</script>