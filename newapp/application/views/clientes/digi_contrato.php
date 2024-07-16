<div id="divDigitalizar">
    <form>
        <div class="row">
            <div class="form-group col-md-6">
                <input type="text" id="descricao" name="descricao" placeholder="Descrição" class="form-control" />
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
                <input type="file" name="arquivo" class="form-control-file" accept="application/pdf" data-buttonText="Arquivo">
            </div>
        </div>
        <div>
            Arquivo:
            <span class="label label-warning ">.pdf</span>
        </div>

        <div class="modal-footer" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">
            <a class="btn btn-small btn-default" data-dismiss="modal" id="fechar">Fechar</a>
            <input type="submit" id="enviar" class="btn btn-primary btn-small"/>
        </div>
    </form>

    <div>
        <table class="table table-striped table-bordered" id="table-digitalizar">
            <thead>
            <th>#</th>
            <th>Descrição</th>
            <th>Visualizar</th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        var table = $('#table-digitalizar').DataTable({
            ajax:{
                url: "<?= site_url('contratos/ajax_digi_contrato/')?>",
                type: 'POST',
                data: {id: '<?php echo $id_contrato ?>'}
            },
            processing: true,
            pagingType: 'numbers',
            language: {
                loadingRecords: "&nbsp;",
                processing: "Carregando os arquivos...",
                emptyTable: "Nenhum registro encontrado",
                zeroRecords: "Nenhum registro encontrado",
                search: "Pesquisar"
            },
            dom: 'Bfrtip',
            responsive: true,
            info: false,
            columns: [
                {data: 'id'},
                {data: 'descricao'},
                {
                    data: 'file',
                    render: function (data) {
                        return '<a href="<?php echo site_url('contratos/visualizar_contrato/')?>/'+data+'" target="_blank" class="btn btn-success btn-mini"><i class="icon-eye-open icon-white"></i>Visualizar</a>'
                    }
                }
            ],

        });
    })
</script>


<!--<div class="resultado span4" style="float: none; margin-left: auto; margin-right: auto;">-->
<!---->
<!--</div>-->
<!---->
<!--<div class="resultado2 span4" style="float: none; margin-left: auto; margin-right: auto;">-->
<!---->
<!--</div>-->
<!---->
<!--<div class="formulario">-->
<!---->
<!--    <form method="post" name="formcontato" id="ContactForm2" enctype="multipart/form-data" action="--><?php //echo site_url('contratos/digitalizacao_contrato/'.$id_contrato)?><!--">-->
<!---->
<!--        <div class="row">-->
<!--            <div class="form-group col-md-6">-->
<!--                <input type="text" id="descricao" name="descricao" placeholder="Descrição" class="form-control" />-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="row">-->
<!--            <div class="form-group col-md-4">-->
<!--                <input type="file" name="arquivo" class="form-control-file" accept="application/pdf" data-buttonText="Arquivo">-->
<!--            </div>-->
<!--        </div>-->
<!--        <div>-->
<!--            Arquivo:-->
<!--            <span class="label label-warning ">.pdf</span>-->
<!--        </div>-->
<!---->
<!--        <div class="modal-footer" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">-->
<!--            <a class="btn btn-small btn-default" data-dismiss="modal" id="fechar">Fechar</a>-->
<!--            <input type="submit" id="enviar" class="btn btn-primary btn-small"/>-->
<!--        </div>-->
<!---->
<!--    </form>-->
<!---->
<!--</div>-->
<!---->
<!--<div class="tabela" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">-->
<!---->
<!--    <table class="table responsive table-hover table-bordered">-->
<!--        <thead>-->
<!--        <th>#</th>-->
<!--        <th>Descrição</th>-->
<!--        <th>Visualizar</th>-->
<!--        </thead>-->
<!---->
<!--        --><?php //if ($arquivos): ?>
<!---->
<!--            <tbody class="inner">-->
<!---->
<!--            --><?php //foreach ($arquivos as $arquivo): ?>
<!--                <tr>-->
<!--                    <td>--><?php //echo $arquivo->id ?><!--</td>-->
<!--                    <td>--><?php //echo $arquivo->descricao ?><!--</td>-->
<!--                    <td>-->
<!--                        <a href="--><?php //echo site_url('contratos/visualizar_contrato/'.$arquivo->file) ?><!--" target="_blank" class="btn btn-success btn-mini">-->
<!--                            <i class="icon-eye-open icon-white"></i>-->
<!--                            Visualizar-->
<!--                        </a>-->
<!--                    </td>-->
<!--                </tr>-->
<!---->
<!--            --><?php //endforeach ?>
<!---->
<!--            </tbody>-->
<!---->
<!--        --><?php //else: ?>
<!---->
<!--            <tbody class="inner">-->
<!---->
<!--            </tbody>-->
<!---->
<!--        --><?php //endif ?>
<!---->
<!--    </table>-->
<!--</div>-->

<script type="text/javascript">
    // $(":file").filestyle({buttonText: "Arquivo"});
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
            }
        });
    });
</script>
