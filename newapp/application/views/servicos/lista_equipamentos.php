<style>
    .md-footer{
        margin-top: 40px;
    }
    /*some basic styles*/
    .rating {font-size:0;display:inline-block}
    .rating__button {width:20px;height:32px;display:inline-block}
    .rating__star {width:100%;height:100%;fill:#fff}

    /*intial hover state*/
    .rating:hover .rating__star,
        /*preserve state after rating the first time*/
    .rating.has--rating .rating__star {fill:orange}

    /*intial hover state*/
    .rating__button:hover ~ .rating__button .rating__star,
        /*preserve state after rating the first time*/
    .rating__button.is--active ~ .rating__button .rating__star {fill:#fff}

    /*SUBSEQUENT RATING ATTEMPTS LOGIC*/

    /*
      lightgray signifies that you're giving a lower rating than before.
        we're gonna make lightgray all the stars that the user takes away.
    */
    .rating.has--rating:hover .rating__button:hover ~ .rating__button .rating__star {fill:lightgray}

    /*make everything after the current active star orange*/
    .rating.has--rating:hover .rating__button.is--active ~ .rating__button .rating__star {fill:orange}

    /*make everything after the currently hovered star white*/
    .rating.has--rating:hover .rating__button:hover ~ .rating__button.is--active ~ .rating__button .rating__star,
    .rating.has--rating:hover .rating__button.is--active:hover ~ .rating__button .rating__star,
    .rating.has--rating:hover .rating__button.is--active ~ .rating__button:hover ~ .rating__button .rating__star {fill:#fff}
</style>

<h3>Ordens de Serviços</h3>

<hr class="featurette-divider">

<div class="well well-small">

    <div class="btn-group">
        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
            Listar OS
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="<?php echo site_url('servico')?>" title=""><i class="icon-th-list"></i> Todas</a></li>
            <li><a href="<?php echo site_url('servico/os_abertas')?>" title=""><i class="icon-th-list"></i> Abertas</a></li>
            <li><a href="<?php echo site_url('servico/os_fechadas')?>" title=""><i class="icon-th-list"></i> Fechadas</a></li>
        </ul>
    </div>

    <div class="btn-group">
        <a class="btn  dropdown-toggle" data-toggle="dropdown" href="#">
            Gerar OS
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="<?php echo site_url('servico/instalacao')?>" title=""><i class="icon-th-list"></i> Instalação</a></li>
            <li><a href="<?php echo site_url('servico/manutencao_troca_retirada')?>" title=""><i class="icon-th-list"></i> Manutenção/Troca/Retirada</a></li>
        </ul>
    </div>
    <div class="pull-right" style="margin-left: 10px !important">
        <a class="btn btn-danger dropdown-toggle" href="<?php echo site_url('servico/estorno_os/'.$os[0]->id.'/'.$os[0]->id_cliente) ?>">
            Estornar OS
        </a>
    </div>

    <div method="get" id="btn-UpRat" class="pull-right">
        <a class="btn btn-success dropdown-toggle"  data-toggle="modal" href="#myModal_digitalizar" >
            Digitalizar / Fechar
        </a>

        <a class="btn btn-info dropdown-toggle"  data-toggle="modal" href="#myModal_trocaTec" >
            Substituir Técnico
        </a>
        <a class="btn btn-info dropdown-toggle"  data-toggle="modal" href="#myModal_trocaplaca" >
            Substituir Placa
        </a>
    </div>

</div>

<br style="clear:both" />

<div class="span12" style="float: none; margin-left: auto; margin-right: auto;">

    <?php if($this->session->flashdata('sucesso')): ?>
        <div class="alert alert-success">
            <p><?php echo $this->session->flashdata('sucesso') ?></p>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('erro')): ?>
        <div class="alert alert-error">
            <p><?php echo $this->session->flashdata('erro') ?></p>
        </div>
    <?php endif; ?>


    <?php if ($equipamentos): ?>


    <?php

    foreach ($os as $os_dados)
    {
        $id_os = $os_dados->id;
        $tipo_os = $os_dados->tipo_os;
        $contrato = $os_dados->id_contrato;
        $instalador = $os_dados->id_instalador;
        $name_instalador = $os_dados->nome_instalador;
    }

    foreach ($clientes as $cliente)
    {
        $nome_cliente = $cliente->nome;
        $id_cliente = $cliente->id;
    }

    ?>



    <h4><?php echo $nome_cliente ?> - OS <?php echo $id_os ?> - <?php echo $tipo_os == 1 ? 'Instalação' : ($tipo_os == 2 ? 'Manutenção' : ($tipo_os == 3 ? 'Troca' : 'Retirada')) ?></h4>
    <p>Técnico: <?= $name_instalador; ?></p>

    <br style="clear:both" />
    <h4>Lista de Módulos</h4>

    <table class="table table-hover">

        <tr>
            <th>#</th>
            <th>Marca</th>
            <th>Serial</th>
            <th>Placa</th>
            <th>Status</th>
            <th>Administrar</th>
        </tr>

        <?php foreach ($equipamentos as $equipamento): ?>

            <tr>
                <td><?php echo $equipamento->id_osequipamentos ?></td>
                <td><?php echo $equipamento->marca ?></td>
                <td><?php echo $equipamento->serial ?></td>
                <td><?php echo $equipamento->board ?></td>
                <td><?php echo $equipamento->status == 1 ? 'Devolvido Empresa' : ($equipamento->status == 2 ? 'Devolvido Teste' : ($equipamento->status == 3 ? 'Em Trânsito - OS' : ($equipamento->status == 4 ? 'Em Uso' : 'Devolvido Instalador'))) ?></td>
                <td>
                    <a href="#myModal_instalado" data-toggle="modal" class="btn btn-mini btn-atualizar-equipamento" data-idosequipamentos="<?php echo $equipamento->id_osequipamentos ?>" data-idos="<?php echo $id_os ?>"
                       data-modulo="<?php echo $equipamento->id ?>" data-cliente="<?php echo $id_cliente ?>" data-contrato="<?php echo $contrato ?>" data-serial="<?php echo $equipamento->serial ?>" title="Instalado"><i class="icon-check"></i></a>
                    <a href="<?php echo site_url('servico/devolver_modulos_instalador/'.$id_os.'/'.$equipamento->id.'/'.$instalador.'/'.$equipamento->serial)?>" class="btn btn-mini btn-success" title="Devolver Instalador"><i class="icon-share icon-white"></i></a>
                    <a href="<?php echo site_url('servico/devolver_modulos_empresa/'.$id_os.'/'.$equipamento->id.'/'.$instalador.'/'.$equipamento->serial)?>" class="btn btn-mini btn-warning" title="Devolver Empresa"><i class="icon-share icon-white"></i></a>
                    <a href="<?php echo site_url('servico/devolver_modulos_teste/'.$id_os.'/'.$equipamento->id.'/'.$instalador.'/'.$equipamento->serial)?>" class="btn btn-mini btn-info" title="Devolver teste"><i class="icon-share icon-white"></i></a>
                </td>

            </tr>


        <?php endforeach ?>


        <div id="myModal_instalado" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header" style="text-align: center;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="myModalLabel">Entre com a placa do veículo</h4>
            </div>
            <div class="modal-body" style="text-align: center;">

                <div class="resultado span4" style="float: none; margin-left: auto; margin-right: auto;">

                </div>

                <form method="post" name="formcontato" id="ContactForm" enctype="multipart/form-data" action="<?php echo site_url('servico/instalado_modulos')?>">
                    <input type="hidden" name="idosequipamentos" id="idosequipamentos" value="" />
                    <input type="hidden" name="idos" id="idos" value="" />
                    <input type="hidden" name="modulo" id="modulo" value="" />
                    <input type="hidden" name="cliente" id="cliente" value="" />
                    <input type="hidden" name="contrato" id="contrato" value="" />
                    <input type="hidden" name="serial" id="serial" value="" />
                    <input type="text" name="placa" id="placa" placeholder="Placa" class="input span4 placa2" />
                </form>

            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                <a href="#" onClick="document.getElementById('ContactForm').submit()" id="enviar" class="btn btn-primary">Salvar</a>
            </div>
        </div>

    </table>

    <div id="myModal_digitalizar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header" style="text-align: center;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 id="myModalLabel">Transferir arquivo - Upload</h4>
        </div>
        <div class="modal-body">
            <form method="post" name="formcontato" id="ContactForm2" enctype="multipart/form-data" action="<?php echo site_url('servico/digitalizacao_os/'.$id_os)?>">
                <div class="span4" style="float: none; margin-left: auto; margin-right: auto;">
                    Nº da OS:
                    <input type="hidden" name="numero_os" id="numero_os" value="<?php echo $id_os ?>" />
                    <p style="color: #A0A0A0; border: 1px #C8C8C8 solid; padding: 5px;">
                        <?php echo $id_os ?>
                    </p>
                    Cliente:
                    <input type="hidden" name="cliente" id="cliente" value="<?php echo $nome_cliente ?>" />
                    <p style="color: #A0A0A0; border: 1px #C8C8C8 solid; padding: 5px;">
                        <?php echo $nome_cliente ?>
                    </p>
                    <input type="hidden" id="instalador" value="<?php echo $instalador?>" />

                    Nota:<br/>
                    <svg style="display: none;">
                        <symbol id="star" viewBox="0 0 98 92">
                            <title>star</title>
                            <path stroke='#000' stroke-width='5' d='M49 73.5L22.55 87.406l5.05-29.453-21.398-20.86 29.573-4.296L49 6l13.225 26.797 29.573 4.297-21.4 20.86 5.052 29.452z' fill-rule='evenodd'/>
                    </svg>

                    <div class="rating">
                        <a href="javascript:;" data-value="1" class="rating__button" href="#"><svg class="rating__star"><use xlink:href="#star"></use></svg></a>
                        <a href="javascript:;" data-value="2" class="rating__button" href="#"><svg class="rating__star"><use xlink:href="#star"></use></svg></a>
                        <a href="javascript:;" data-value="3" class="rating__button" href="#"><svg class="rating__star"><use xlink:href="#star"></use></svg></a>
                        <a href="javascript:;" data-value="4" class="rating__button" href="#"><svg class="rating__star"><use xlink:href="#star"></use></svg></a>
                        <a href="javascript:;" data-value="5" class="rating__button" href="#"><svg class="rating__star"><use xlink:href="#star"></use></svg></a>
                    </div>
                </div>
        </div>
        <div class="span4" style="float: none; margin-left: auto; margin-right: auto;">
            <input type="file" name="arquivo" class="filestyle" data-buttonText="Arquivo">
        </div>
        </form>
        <div class="md-footer modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
            <a href="#" onClick="document.getElementById('ContactForm2').submit();" class="salvar-rating btn btn-primary">Salvar</a>
        </div>
    </div>

    <div id="myModal_trocaTec" class="modal fade" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4>Transferir OS - Técnico</h4>
        </div>
        <form method="post" name="formtecnico" enctype="multipart/form-data" action="<?php echo site_url('servico/troca_tec/'.$id_os)?>">
            <div class="modal-body">
                <label for="tec">Técnico:</label>
                <select class="form-control" name="tecnico" id="tec">
                    <?php foreach ($tecnicos as $tec): ?>
                    <option value="<?= $tec->id; ?>"><?= $tec->nome; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="md-footer modal-footer">
                <button type="submit" class="btn btn-success">Substituir</button>
            </div>
        </form>
    </div>
    <div id="myModal_trocaplaca" class="modal fade" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4>Trocar Placa</h4>
        </div>
        <form method="post" name="formtecnico" enctype="multipart/form-data" action="<?php echo site_url('servico/troca_placa/'.$id_os)?>">
            <div class="modal-body">
                <label for="placa">Placa:</label>
                <input type="text" id="atualizar_placa" name="placa">
            </div>
            <div class="md-footer modal-footer">
                <button type="submit" class="btn btn-success">Substituir</button>
            </div>
        </form>
    </div>
</div>

<?php else: ?>

    <?php

    foreach ($os as $os_dados)
    {
        $id_os = $os_dados->id;
        $tipo_os = $os_dados->tipo_os;
        $contrato = $os_dados->id_contrato;
        $instalador = $os_dados->id_instalador;
    }

    foreach ($clientes as $cliente)
    {
        $nome_cliente = $cliente->nome;
        $id_cliente = $cliente->id;
    }

    ?>



    <h4><?php echo $nome_cliente ?> - OS <?php echo $id_os ?> - <?php echo $tipo_os == 1 ? 'Instalação' : ($tipo_os == 2 ? 'Manutenção' : ($tipo_os == 3 ? 'Troca' : 'Retirada')) ?></h4>

    <br style="clear:both" />
    <h4>Lista de Módulos</h4>

    <table class="table table-hover">

        <tr>
            <th>#</th>
            <th>Marca</th>
            <th>Serial</th>
            <th>Placa</th>
            <th>Status</th>
            <th>Administrar</th>
        </tr>


    </table>

    <div id="myModal_digitalizar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header" style="text-align: center;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 id="myModalLabel">Transferir arquivo - Upload</h4>
        </div>
        <div class="modal-body">
            <form method="post" name="formcontato" id="ContactForm2" enctype="multipart/form-data" action="<?php echo site_url('servico/digitalizacao_os/'.$id_os)?>">
                <div class="span4" style="float: none; margin-left: auto; margin-right: auto;">
                    Nº da OS:
                    <input type="hidden" name="numero_os" id="numero_os" value="<?php echo $id_os ?>" />
                    <p style="color: #A0A0A0; border: 1px #C8C8C8 solid; padding: 5px;">
                        <?php echo $id_os ?>
                    </p>
                    Cliente:
                    <input type="hidden" name="cliente" id="cliente" value="<?php echo $nome_cliente ?>" />
                    <p style="color: #A0A0A0; border: 1px #C8C8C8 solid; padding: 5px;">
                        <?php echo $nome_cliente ?>
                    </p>
                </div>
                <div class="span4" style="float: none; margin-left: auto; margin-right: auto;">
                    <input type="file" name="arquivo" class="filestyle" data-buttonText="Arquivo">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
            <a href="#" onClick="document.getElementById('ContactForm2').submit()" id="enviar" class="btn btn-primary">Salvar</a>
        </div>
    </div>

<?php endif ?>


<div class="pagination" style="float: right;">
    <?php echo $this->pagination->create_links();  ?>
</div>

</div>


<script>
    $(function(){

        $('.rating__button').on('click', function(e){
            var $t = $(this), // the clicked star
                $ct = $t.parent(); // the stars container

            // add .is--active to the user selected star
            $t.siblings().removeClass('is--active').end().toggleClass('is--active');
            // add .has--rating to the rating container, if there's a star selected. remove it if there's no star selected.
            $ct.find('.rating__button.is--active').length ? $ct.addClass('has--rating') : $ct.removeClass('has--rating');
        });

    });

    $('.btn-atualizar-equipamento').click(function(e){
        e.preventDefault();

        var idosequipamentos = $(this).data('idosequipamentos');
        var idos = $(this).data('idos')
        var modulo = $(this).data('modulo')
        var cliente = $(this).data('cliente')
        var contrato = $(this).data('contrato')
        var serial = $(this).data('serial')

        $('#idosequipamentos').val(idosequipamentos);
        $('#idos').val(idos);
        $('#modulo').val(modulo);
        $('#cliente').val(cliente);
        $('#contrato').val(contrato);
        $('#serial').val(serial);

    });

    $(document).ready(function(){
        //$("#placa").mask("AAA-9999");
        $("#atualizar_placa").mask("AAA-9999");
    });

    $(function (rating) {

        var nota = "";
        $('.rating__button').on('click', function(e) {
            e.preventDefault();
            nota = $(this).data('value');
        });

        $('.salvar-rating').click(function () {
            $.ajax({
                url: '../saveRating',
                type: 'POST',
                data: {
                    'id_os': $('#numero_os').val(),
                    'id_tec': $('#instalador').val(),
                    'nota': nota ? nota : 0
                },success: function () {

                }
            });
        });
    });

</script>
