<div class="alert alert-warning" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span class="mensagem"></span>
</div>

<?php if ($type == 1): ?>
    <?php echo form_open('contratos/ajax_add_chip/'.$id_contrato.'/'.$id_cliente, array('id' => 'add_chip') )?>

    <div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000">
        <img src="<?php echo base_url('media/img/loading.gif')?>" />
    </div>
    <div class="row-fluid">
        <div class="span12 ">
            <div class="control-group">
                <label>Chip:</label>
                <input type="text" class="" name="chip" placeholder="Ex.: 8399999999" required />
            </div>
            <div class="control-group">
                <label>CCID:</label>
                <input type="text" class="" name="ccid" required />
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12 ">
            <label class="control-label">Status:</label>
            <div class="control-group">
                <label class="radio inline">
                    <input type="radio" name="status" value="ativo" required /> Ativo
                </label> <label class="radio inline">
                    <input type="radio" name="status" value="inativo" required /> Inativo
                </label>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="icon-plus icon-white"></i> Salvar
            </button>
            <a onclick="fecharModal('#nova_placa');" class="btn fechar">Fechar</a>
        </div>
    </div>
    <?php echo form_close()?>
<?php elseif ($type == 2): ?>
    <?php echo form_open('contratos/ajax_add_tornozeleira/'.$id_contrato, array('id' => 'add_tornozeleira'),
        array('id_cliente' => $id_cliente, 'id_contrato' => $id_contrato))?>

    <div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div>
    <div class="row-fluid">
        <div class="span12 ">
            <div class="control-group">
                <label>Serial:</label>
                <input type="text" name="equipamento" class="serial" data-provide="typeahead" data-source='<?php echo $equipamentos ?>' data-items="20" required/>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12 ">
            <label class="control-label">Status:</label>
            <div class="control-group">
                <label class="radio inline">
                    <input type="radio" name="status" value="ativo" required /> Ativo
                </label> <label class="radio inline">
                    <input type="radio" name="status" value="inativo" required /> Inativo
                </label>
            </div>
        </div>
    </div>

    <div class="row-fluid">
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="icon-plus icon-white"></i> Salvar
            </button>
            <a onclick="fecharModal('#nova_tornozeleira');" class="btn fechar">Fechar</a>
        </div>
    </div>
    <?php echo form_close()?>
<?php elseif ($type == 3): ?>
    <?php echo form_open('contratos/ajax_add_isca/'.$id_contrato, array('id' => 'add_isca'), array('id_cliente' => $id_cliente, 'id_contrato' => $id_contrato))?>

        <div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div>

        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    <label>Serial:</label>
                    <input type="text" name="serial" required/>
                </div>
            </div>

            <div class="span6">
                <div class="control-group">
                    <label>Marca:</label>
                    <input type="text" name="marca"/>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    <label>Modelo:</label>
                    <input type="text" name="modelo"/>
                </div>
            </div>

            <div class="span6">
                <label class="control-label">Status:</label>
                <div class="control-group">
                    <label class="radio inline">
                        <input type="radio" name="status" value="1" checked required /> Ativo
                    </label> <label class="radio inline">
                        <input type="radio" name="status" value="0" required /> Inativo
                    </label>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <div class="control-group">
                    <label>Nome:</label>
                    <textarea style="width:90%" name="descricao" rows="3"></textarea>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="icon-plus icon-white"></i> Salvar
                </button>
                <a onclick="fecharModal('#nova_isca');" class="btn fechar">Fechar</a>
            </div>
        </div>
    <?php echo form_close()?>
<?php else: ?>
    <?php echo form_open('contratos/ajax_add_placa/'.$id_contrato, array('id' => 'add_placa'),
        array('id_cliente' => $id_cliente, 'id_contrato' => $id_contrato))?>

    <div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div>
    <div class="row-fluid">
        <div class="span12 ">
            <div class="control-group">
                <label>Placa:</label>
                <input type="text" class="placa" name="placa" maxlength="8" required />
            </div>
        </div>
        <!--/span-->
    </div>
    <div class="row-fluid">
        <div class="span12 ">
            <label class="control-label">Status:</label>
            <div class="control-group">
                <label class="radio inline">
                    <input type="radio" name="status" value="ativo" required /> Ativo
                </label> <label class="radio inline">
                    <input type="radio" name="status" value="inativo" required /> Inativo
                </label>
            </div>
        </div>
        <!--/span-->
    </div>
    <div class="alert alert-warning placa-alert" style="display: block;">
        <span class="mensagemPlaca"></span>
    </div>

    <div class="row-fluid">
        <div class="form-actions">
            <button type="button" class="btn btn-primary addPlaca">
                <i class="icon-plus icon-white"></i> Salvar
            </button>
            <a onclick="fecharModal('#nova_placa');" class="btn fechar">Fechar</a>
        </div>
    </div>

    <?php echo form_close()?>
<?php endif; ?>

<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.min.js"></script> -->

<script type="text/javascript">
    var ajaxLoading = false;
    $(document).ready(function(){

        $('body').on('click','.addPlaca',function(event){
            event.preventDefault()
            var cliente = $('input[name=id_cliente]').val();
            var contrato = $('input[name=id_contrato]').val();
            var placa = $('input[name=placa]').val();
            var stat = $('input[name=status]:checked').val();

            if(!ajaxLoading){
                ajaxLoading = true;
            $('#load').css('display', 'block');
            var urlP = $('#add_placa').attr('action');

            $.post(urlP, {placa: placa, status: stat, id_contrato: contrato, id_cliente: cliente},

                function(cback) {
                    $('#load').css('display', 'none');
                    $('.placa-alert').css('display', 'block');
                    if(cback.success) {
                        ajaxLoading = false;
                        $('span.mensagemPlaca').html(cback.msg);

                        carrega_dados_tabela();

                    } else {
                        $('span.mensagemPlaca').html(cback.msg);
                    }
                    $('#nova_placa').modal('hide');
                    //$(this).data('modal').$element.removeData();
                }, 'json');
            }
        })


        // $('#add_placa').submit(function(e) {

        //     var cliente = $('input[name=id_cliente]').val();
        //     var contrato = $('input[name=id_contrato]').val();
        //     var placa = $('input[name=placa]').val();
        //     var stat = $('input[name=status]:checked').val();

        //     $('#load').css('display', 'block');
        //     var urlP = $('#add_placa').attr('action');

        //     $.post(urlP, {placa: placa, status: stat, id_contrato: contrato, id_cliente: cliente},

        //         function(cback) {
        //             $('#load').css('display', 'none');
        //             $('.placa-alert').css('display', 'block');
        //             if(cback.success) {
        //                 $('span.mensagem').html(cback.msg);
        //                 $('#placas tbody').prepend('<tr>' +
        //                     '<td>'+cback.veiculo.id+'</td>' +
        //                     '<td>'+cback.veiculo.placa+'</td><td style="text-align: center">'+cback.veiculo.status+'</td>'+
        //                     '<td>'+cback.veiculo.vincular+'</td>'+
        //                     '<td>'+cback.veiculo.posicao+'</td>'+
        //                     '</tr>');
        //             } else {
        //                 $('span.mensagem').html(cback.msg);
        //             }
        //             $('#nova_placa').modal('hide');
        //             $(this).data('modal').$element.removeData();
        //         }, 'json');
        // });

        $('#add_tornozeleira').submit(function(e) {
            e.preventDefault();

            var cliente = $('input[name=id_cliente]').val();
            var contrato = $('input[name=id_contrato]').val();
            var serial = $('input[name=equipamento]').val().toUpperCase();
            var stat = $('input[name=status]:checked').val();

            $('#load').css('display', 'block');
            var urlP = $('#add_tornozeleira').attr('action');

            $.post(urlP, {equipamento: serial, status: stat, id_contrato: contrato, id_cliente: cliente},

                function(cback) {
                    $('#load').css('display', 'none');
                    $('.placa-alert').css('display', 'block');
                    if(cback.success) {
                        $('span.mensagem').html(cback.msg);
                        $('#placas tbody').prepend('<tr>' +
                            '<td>'+cback.veiculo.id+'</td>' +
                            '<td>'+cback.veiculo.serial+'</td>' +
                            '<td>'+cback.veiculo.data+'</td>'+
                            '<td style="text-align: center">'+cback.veiculo.status+'</td>'+
                            '</tr>');
                    } else {
                        $('span.mensagem').html(cback.msg);
                    }
                    $('#nova_tornozeleira').modal('hide');
                    $(this).data('modal').$element.removeData();
                }, 'json');
        });

        $('#add_chip').submit(function(e) {
            e.preventDefault();

            var cliente = $('input[name=id_cliente]').val();
            var contrato = $('input[name=id_contrato]').val();
            var chip = $('input[name=chip]').val();
            var ccid = $('input[name=ccid]').val();
            var stat = $('input[name=status]:checked').val();

            $('#load').css('display', 'block');
            var urlP = $('#add_chip').attr('action');

            $.post(urlP, {chip: chip, ccid: ccid, status: stat, id_contrato: contrato, id_cliente: cliente},

                function(cback) {
                    $('#load').css('display', 'none');
                    $('.placa-alert').css('display', 'block');

                    var status = "INATIVO";
                    if(cback.success) {
                        if (cback.placas[0].status == 1) {
                            status = "ATIVO";
                        }
                        $('span.mensagem').html(cback.msg);
                        $('#placas tbody').prepend('<tr><td>'+cback.placas[0].id+'</td><td>'
                            +cback.placas[0].numero+'</td>' +
                            '<td style="text-align: center">'+cback.placas[0].ccid+'</td>'+
                            '<td style="text-align: center">'+status+'</td>'+
                            '</tr>');
                    } else {
                        $('span.mensagem').html(cback.msg);
                    }
                    // $('#nova_placa').on('hidden.bs.modal', function() {
                    //     $(this).removeData('bs.modal');
                    // });
                    $('#nova_placa').modal('hide');
                    $(this).data('modal').$element.removeData();
                }, 'json');
        });

        $('#add_isca').submit(function(e) {
            e.preventDefault();

            var id_cliente = $('input[name=id_cliente]').val();
            var id_contrato = $('input[name=id_contrato]').val();
            var serial = $('input[name=serial]').val();
            var marca = $('input[name=marca]').val();
            var modelo = $('input[name=modelo]').val();
            var status = $('input[name=status]:checked').val();
            var descricao = $('textarea[name=descricao]').val();

            $('#load').css('display', 'block');
            var urlP = $('#add_isca').attr('action');

            $.post(urlP, {id_cliente: id_cliente, id_contrato: id_contrato, serial: serial, marca: marca, modelo: modelo, status: status, descricao: descricao},

                function(cback) {
                    $('#load').css('display', 'none');
                    $('.isca-alert').css('display', 'block');

                    var status = "INATIVO";
                    var form = $(this);

                    if(cback.success) {
                        if (cback.isca.status == 1) {
                            status = "ATIVO";
                        }

                        $('span.mensagem').html(cback.msg);

                        $('#iscas tbody').prepend('<tr><td>'+cback.isca.id+'</td>'+
                                                  '<td>'+cback.isca.serial+'</td>'+
                                                  '<td>'+cback.isca.marca+'</td>'+
                                                  '<td>'+cback.isca.modelo+'</td>'+
                                                  '<td>'+cback.bt_status+'</td>'+
                                                  '<td>'+cback.isca.data_cadastro+'</td>'+
                                                  '<td>'+cback.isca.descricao+'</td></tr>');

                    } else {
                        $('span.mensagem').html(cback.msg);
                    }
                    $('#nova_isca').modal('hide');

                    $("#add_isca").each(function() {
                        this.reset();
                    });

                }, 'json'
            );

        });

        $('.data').mask('99/99/9999');
        $('.tel').mask('(99) 9999-9999');
        $('.hora').mask('99:99:99');
        $('.cep').mask('99999-999');
        $('.cpf').mask('999.999.999-99');
        $('.chip').mask('(99) 99999-9999');
//	$('.placa').mask('aaa-9999');
        $('.mes_ano').mask('99/9999');
        $("#ajax").css('display', 'none');

    });

    function selecionarSecretaria(id_veic_contrato,grupo){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('contratos/vincular_secretaria')?>",
            data: {id_veic_contrato:id_veic_contrato,grupo:grupo},
            success: function(resposta){
                console.log(resposta)
            }
        });
    }

</script>
