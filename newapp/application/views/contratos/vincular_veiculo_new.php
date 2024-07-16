<style>
    select[readonly] {
        background: #eee; /*Simular campo inativo - Sugestão @GabrielRodrigues*/
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container {
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
        background: #eee;
        box-shadow: none;
    }

    .form-control-perso{
        width: 100% !important;
    }
</style>

<?php
if ($seriais) {
    $tamanho = count($seriais);
    if ( $tamanho == 1 ) {
        foreach ($seriais as $ser){
            $serial_1 = $ser->serial;
        }
    } else
        $serial_1 = 0;
} else
    $serial_1 = 0;
?>


<?php if ($placa):?>
<div class="pull-right btnusuarios">
    <a id="addusuario" class="btn btn-primary btn-success rmvusuario" title="Adicionar usuário"><i class="fa fa-plus-circle"></i> </a>
    <a id="rmvusuario" class="btn btn-primary btn-warning addusuario" title="Remover usuário"><i class="fa fa-minus-circle"></i> </a>
</div>
<?php endif;?>


<!-- <div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div> -->
<h4>Placa - <?php echo $placa ?></h4>

<div class="informacao"></div>

<div class="rmvusuario">
    <form method="post" name="formUsuario" id="formUsuario" action="<?php echo site_url('veiculos/remover_usuario_veiculo/'.$placa)?>">
        <?php if ($usuarios):?>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Usuário:</label>
                    <select class="form-control" name="usuario" id="usuario">
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?php echo $usuario->code ?>"><?php echo $usuario->usuario ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        <?php else:?>
            <div class="alert alert-info">
                <h4>Usuários não encontrados!</h4>
            </div>
        <?php endif;?>
        <br>
        <div style="text-align: right;">
            <button type="submit" id="enviar" value="Remover" class="btn btn-primary btn" >Remover</button>
        </div>
        <!-- <a onclick="fecharModal('#modal_serial');" class="btn fechar">Fechar</a> -->
    </form>
</div>

<div class="addusuario">
    <div class="vincular">
        <?php if ($placa):?>
            <form method="post" name="vincularVeiculo" id="vincularVeiculo"  autocomplete="off" enctype="multipart/form-data" action="<?php echo site_url('contratos/validar_vinculo_new/'.$id_placa.'/'.$placa.'/'.$id_cliente.'/'.$id_contrato)?>">
                <input type="hidden" name="ser" class="serial_atual" value="<?php echo $serial_1 ?>"/>
                <?php if ($seriais):?>
                    <?php if ($tamanho == 1):?>
                        <?php if ($usuarios):?>
                            <input type="hidden" name="status" value="edicao"/>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    Veículo:
                                    <input class="form-control form-control-perso" type="text" name="veiculo" minlength="4" placeholder="Veículo" value="<?=isset($dados_veic->veiculo) ? $dados_veic->veiculo : '' ?>" />
                                </div>

                                <div class="form-group col-md-4">
                                    Táxi:
                                    <select class="form-control form-control-perso" name="taxi">
                                        <option value="0">Não</option>
                                        <option value="1">Sim</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    Prefixo:
                                    <input class="form-control form-control-perso" type="text" name="prefixo" placeholder="Prefixo" value="<?= isset($dados_veic->prefixo_veiculo) ? $dados_veic->prefixo_veiculo : '' ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    Serial:
                                    <select type="text" name="serial" class="adt teste form-control form-control-perso" readonly="readonly" required>
                                        <option value="<?php echo $serial_1?>"><?php echo $serial_1?></option>
                                    </select>
                                </div>

                                <div class="form-group col-md-1" style="top: 15px;">
                                    <a id="visualizando" class="btn btn-primary btn-danger visualizando" title="Visualizar"><i class="fa fa-eye"></i></a>
                                    <a id="editando" class="btn btn-primary btn-success editando" title="Editar"><i class="fa fa-edit"></i></a>
                                </div>
                                <div class="form-group col-md-4">
                                    Usuário:
                                    <select class="form-control form-control-perso" name="usuario" id="usuario">
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <option value="<?php echo $usuario->code ?>"><?php echo $usuario->usuario ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    Data Instalação:
                                    <div class="wrapper input-prepend">
                                        <input class="form-control form-control-perso" id="data_instalacao" name="data_instalacao" type="date" value="<?=isset($dados_veic->data_instalacao) ? $dados_veic->data_instalacao : date('Y-m-d') ?>" readonly="readonly" required/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    Marca:
                                    <input class="form-control form-control-perso" type="text" name="marca" placeholder="Marca" value="<?=isset($dados_veic->marca) ? $dados_veic->marca : '' ?> "/>
                                </div>

                                <div class="form-group col-md-4">
                                    Modelo:
                                    <input class="form-control form-control-perso" type="text" name="modelo" placeholder="Modelo" value="<?=isset($dados_veic->modelo) ? $dados_veic->modelo : '' ?>" />
                                </div>
                            </div>
                        <?php else:?>
                            <div class="alert alert-info">
                                <h4>Placa e serial já cadastrado em todos usuários!</h4>
                            </div>
                        <?php endif;?>
                    <?php else:?>
                        <input type="hidden" name="status" value= "correcao"/>
                        <div class="alert alert-block">
                            <button id="atencao" type="button" class="close" data-dismiss="alert">&times;</button>
                            <h4>Atenção!</h4>
                            A placa <b><?php echo $placa ?></b> está relacionada a mais de <b>1</b> serial: <b><?php foreach ($seriais as $ser) { echo $ser->serial." "; } ?></b>
                            - Para corrigir, identifique e informe o serial correto!
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Informe Serial:</label>
    <!--                            arrumar aqui select2-->
    <!--                            <input class="form-control" type="text" name="serial" placeholder="Serial" data-provide="typeahead" data-source='--><?php //echo $equipamentos ?><!--' data-items="20" required />-->
                            </div>
                        </div>
                    <?php endif;?>
                <?php else:?>
                    <?php if ($usuarios):?>
                        <input type="hidden" name="status" value= "novo"/>
                        <div class="row">
                            <div class="form-group col-md-4">
                                Veículo:
                                <input class="form-control form-control-perso" type="text" name="veiculo" minlength="4" placeholder="Veículo" />
                            </div>
                            <div class="form-group col-md-4">
                                Táxi:
                                <select class="form-control form-control-perso" name="taxi">
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                Prefixo:
                                <input class="form-control form-control-perso" type="text" name="prefixo" placeholder="Prefixo" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-3">
                                Serial:

                                <select class="form-control teste" id="teste1" name="serial" type="text" required>

                                </select>
                            </div>

                            <div class="form-group col-md-1" style="top: 15px;">
                                <a id="visualizando" class="btn btn-primary btn-danger visualizando" title="Visualizar"><i class="fa fa-eye"></i></a>
                                <a id="editando" class="btn btn-primary btn-success editando" title="Editar"><i class="fa fa-edit"></i></a>
                            </div>

                            <div class="form-group col-md-4">
                                Usuário:
                                <select class="form-control form-control-perso" name="usuario" id="usuario">
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <option value="<?php echo $usuario->code ?>"><?php echo $usuario->usuario ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                Data Instalação:
                                <div class="wrapper input-prepend">
                                    <input class="form-control form-control-perso" type="date" name="data_instalacao" value="<?php echo date('Y-m-d') ?>" readonly="readonly" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                Marca:
                                <input class="form-control form-control-perso" type="text" name="marca" placeholder="Marca" />
                            </div>
                            <div class="form-group col-md-4">
                                Modelo:
                                <input class="form-control form-control-perso" type="text" name="modelo" placeholder="Modelo" />
                            </div>
                        </div>
                    <?php else:?>
                        <div class="alert alert-info">
                            <h4>Placa e serial já cadastrado em todos usuários!</h4>
                        </div>
                    <?php endif;?>
                <?php endif;?>
                    <br>
                    <div style="text-align: right;">
                        <button type="submit" id="enviar" class="btn btn-primary btn_vinc">Salvar</button>
                    </div>
                <!-- <a onclick="fecharModal('#modal_serial');" class="btn fechar">Fechar</a> -->
            </form>
        <?php else:?>
            <div class="alert alert-info">
                    <h4>A placa está sem numeração, portanto não pode ser ativada!</h4>
                </div>
        <?php endif;?>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function(){

        $('.teste').select2({
            ajax: {
                url: '<?= site_url('contratos/busca_equipamento_select2') ?>',
                dataType: 'json',
                delay: 1000,
            },
            placeholder: "Selecione o serial",
            allowClear: true,
            minimumInputLength: 2,

        });

        $(".desvincular").hide();

        $("#vincularVeiculo").ajaxForm({
    		dataType: 'json',
    		beforeSend: settings => {
    			//animacao do botao clicado
    			$('.btn_vinc').attr('disabled', false).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
    		},
    		success: function(retorno){
    			$("#atencao").click();
    			if (retorno.success) {

    				if (retorno.operacao == 'novo') {
    					//muda o status após uma nova vinculacao
    					$(".status_"+list_id_placa).removeClass('label-primary')
    					.addClass('label-success').text('ativo');

    					//muda o botao de acao ativar/desativar
    					$(".btn_"+list_id_placa).html('<button type="button" data-acao="ativar" href_ativar="'+list_href_ativar+'" href_inativar="" class="btn btn-primary btn-success ativar_placa btn_ativo_'+list_id_placa+'" data-placa_id="'+list_id_placa+'">Editar</button>'+
    								   				  '<button type="button" href="'+list_href_inativar+'" class="btn btn-small btn-default inativar_placa btn_inativo_'+list_id_placa+'" data-placa_id="'+list_id_placa+'">Inativar</button> ');

                        $('input[name=status]').val('edicao');

    				}else if (retorno.operacao == 'edicao') {
                        if (btnAcaoPlaca === 'cadastrar') {
                            //muda o status após uma nova vinculacao
        					$(".status_"+list_id_placa).removeClass('label-primary')
        					.addClass('label-success').text('ativo');

                            //muda o botao de acao ativar/desativar
        					$(".btn_"+list_id_placa).html('<button type="button" data-acao="ativar" href_ativar="'+list_href_ativar+'" href_inativar="" class="btn btn-primary btn-success ativar_placa btn_ativo_'+list_id_placa+'" data-placa_id="'+list_id_placa+'">Editar</button>'+
        								   				  '<button type="button" href="'+list_href_inativar+'" class="btn btn-small btn-default inativar_placa btn_inativo_'+list_id_placa+'" data-placa_id="'+list_id_placa+'">Inativar</button> ');

                            $('input[name=status]').val('edicao');

                        }else {
        					//muda o status após uma nova vinculacao
        					$(".status_"+list_id_placa).removeClass('label-primary')
        					.addClass('label-success').text('ativo');

        					//muda o botao de acao para editar
        					$(".btn_ativo_"+list_id_placa).text('Editar');

                            //remove o disabled do botao inativar
                            $(".btn_inativo_"+list_id_placa).removeAttr('disabled');
                        }
    				}

    				//atualiza data-status do botao de posição
    				$(".btnPosicao_"+list_id_placa).attr('data-status', 'ativo');

    				$(".informacao").html(retorno.mensagem);
    				$(".informacao").show();
    				$('#des').show();

                }else {
                    $(".informacao").html(retorno.mensagem);
    				$(".informacao").show();
    				$('#des').show();
                }

    			$('.adt').val(retorno.serial);
                $('.serial_atual').val(retorno.serial);
    			$('.btn_vinc').attr('disabled', false).html('Salvar');
    		}
    	});

        $("#formUsuario").ajaxForm({
            dataType: 'json',
            success: function(retorno){
                if (retorno.certo) {
                    $(".informacao").html(retorno.msg);
                    $(".informacao").show();
                };
            }
        });

        $('.visualizando').hide();
        $('#des').hide();

        $("a#visualizando").click(function(){
            $(".adt").attr("readonly", true);
            $('.editando').show();
            $('.visualizando').hide();
        });

        $("a#editando").click(function(){
            $(".adt").attr("readonly", false);
            $('.editando').hide();
            $('.visualizando').show();
            //$('button#enviar').hide();
        });

        $('.rmvusuario').hide();

        $("a#rmvusuario").click(function(){
            $('.rmvusuario').show();
            $('.addusuario').hide();
            $(".informacao").hide();
        });

        $("a#addusuario").click(function(){
            $('.rmvusuario').hide();
            $('.addusuario').show();
            $(".informacao").hide();
        });

        $('select[name=taxi]').on('change', function(){
            if($(this).val() == '1')
                $("input[name=prefixo]").prop('required',true);
            else
                $("input[name=prefixo]").prop('required',false);
        });
    });
</script>
