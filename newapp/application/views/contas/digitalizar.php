    
<style type="text/css">
    .btn,.label{
        vertical-align: top !important;
    }
</style>

<div class="modal-content">
    <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4 class="modal-title" style="text-align: center;">Digitalizar Conta</h4>
    </div>
    <form action="<?= site_url('contas/add_categoria') ?>" method="POST">
        <div class="modal-body">
            <div class="row">
                <div class="resultado col-md-4" style="float: none; margin-left: auto; margin-right: auto;"></div>
                <div class="resultado2 col-md-4" style="float: none; margin-left: auto; margin-right: auto;"></div>

                <div class="formulario">
                    <form method="post" name="formcontato" id="ContactForm2" enctype="multipart/form-data" action="<?php echo site_url('contas/digitalizacao/'.$id_conta)?>">
                        <div class="col-md-10" style="float: none; margin-left: auto; margin-right: auto; border-left: 3px solid #03A9F4;">
                            <div class="pull-right">
                                Arquivo: 
                                <span class="label label-warning ">.pdf</span>
                            </div>
                            <input type="text" id="descricaoArquivo" name="descricao" placeholder="Descrição" class="input form-control" />
                                
                            <label for="comprovante" style="cursor: pointer;">
                                <input type="checkbox" value="1" id="comprovante" name="comprovante"/>&nbsp;Comprovante de pagamento
                            </label>
                            <div class="row">
                                <div class="col-md-9">
                                    <label id="filesFormLabel" for="filesForm" class="btn btn-default col-md-12" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Selecione um Arquivo</label>
                                    <input id="filesForm" type="file" class="btn btn-default"  style="visibility:hidden;" name="arquivo"/>
                                </div>
                                <div class="col-md-3" >
                                    <button type="button" class="btn btn-primary col-md-12" title="Adicionar" id="AddArquivoForm"><i class="fa fa-plus"></i> Adicionar</button>    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-12 tabela" style="text-align: center; margin-top: 20px;">
                    <table class="table table-hover table-bordered" style="margin-left: auto; margin-right: auto;">
                        <thead>
                            <th class="text-center">#</th>
                            <th class="text-center">Descrição</th>
                            <th class="text-center">Visualizar</th>
                            <th class="text-center">Excluir</th>
                        </thead>
                        <?php if ($arquivos): ?>
                        <tbody class="inner">
                            <?php foreach ($arquivos as $arquivo): ?>
                            <tr>
                                <td><?php echo $arquivo->id ?></td>
                                <td><?php echo $arquivo->descricao ?></td>
                                <td>
                                    <?php if (file_exists($arquivo->file)): ?>
                                        <a href="<?php echo site_url('contas/view_file/'.$arquivo->file)?>" target="_blank" class="btn btn-primary btn-mini">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-primary btn-mini view-file-not-found" data-file="<?php echo $arquivo->file; ?>">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?=site_url('contas/remove_conta_eua/'.$arquivo->id)?>"
                                    class="btn btn-mini btn-danger del-conta" data-idconta="<?= $arquivo->id ?>">
                                    <i class="fa fa-trash"></i>
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

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
        </div>
    </form>
</div>


<script type="text/javascript">

$(document).ready(function(){

    $(".resultado").hide();
    $(".resultado2").hide();
    
    $("#AddArquivoForm").on('click', function(e) {
        e.preventDefault();

		var dataForm = new FormData();
        
		var descricao = $('#descricaoArquivo').val();
		var comprovante = $('#comprovante').prop("checked") == "checked";

		var file_data = $('#filesForm')[0];
        file = file_data.files[0];

        if(!file){
            alert("Selecione um Arquivo.");
            return;
        }
        
        dataForm.append("arquivo", file);
        dataForm.append("descricao", descricao);
        dataForm.append("comprovante", comprovante);
        
        $.ajax({
            url: "<?php echo site_url('contas/digitalizacao/'.$id_conta)?>",
            type: "POST",
            data: dataForm,     
			processData: false,
            contentType: false,
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
                retorno = JSON.parse(retorno);
                
                if (retorno.success) {
                    var tpl = [
                        '<tr>',
                            '<td>',retorno.registro.id,'</td>',
                            '<td>',retorno.registro.descricao,'</td>',
                            '<td>',
                                '<a href="<?php echo site_url('contas/view_file')?>/',retorno.registro.file,'" target="_blank" class="btn btn-primary btn-mini">',
                                '<i class="fa fa-eye" aria-hidden="true"></i>',
                            '</td>',
                            '<td>',
                                '<a href="<?php echo site_url('contas/remove_conta_eua')?>/',retorno.registro.id,'" class="btn btn-mini btn-danger del-conta" data-idconta="', parseInt(retorno.registro.id), '" >',
                                '<i class="fa fa-trash"></i>',
                            '</td>',
                        '</tr>'
                    ].join('');

                    $('tbody.inner').append(tpl);

                }

                $(".resultado").html(retorno.mensagem);
                $(".resultado").show();
                $(".resultado2").hide();
                $("#enviar").show();
            },
            error: function(error){
                console.log(error)
            },
            
        });  

        $('#descricaoArquivo').val('');
        $('#comprovante').prop("checked", false);
        $('#filesForm').val('');
        $('#filesFormLabel').text('Selecione um Arquivo');

        
    });


    $('input[type=file]').change(function(){
        var t = $(this).val();
        var labelText = 'Arquivo : ' + t.substr(12, t.length);
        $(this).prev('label').text(labelText);
    });

    $(document).on("click", ".del-conta", function(ev) {
        ev.preventDefault();
        var url = $(this).attr("href");
        var id = $(this).attr("data-idconta");
        var tr = $(this).parents("tr");

        $.post(url, { id: id }, function(cb) {
            if (cb.success) {
                $(tr).remove();
            }
        }, 'json');
    });
    

    $(document).on("click", ".view-file-not-found", function(event) {
        event.preventDefault();
        alert("Arquivo não encontrado.");
    });

    // $(document).on("click", ".view-file-link", function(event) {
    //     event.preventDefault();

    //     var fileURL = $(this).attr("href");

    //     $.ajax({
    //         url: fileURL,
    //         type: "HEAD",
    //         success: function() {
    //             window.open(fileURL, "_blank");
    //         },
    //         error: function() {
    //             alert("Arquivo não encontrado.");
    //         }
    //     });
    // });

});

</script>