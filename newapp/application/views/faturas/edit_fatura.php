<style>
    #div_abono {
        background-color: rgba(230, 0, 0, 0.06);
    }
    .listAnexo{
        max-height: 200px;
        overflow: auto;
        padding-top: 20px;
    }
    .linkAnexo{
        font-size: 14px;
        padding: 10px 20px;
        background: -moz-linear-gradient( top, #fafafa 0%, #e1e3e4);
        background: -webkit-gradient( linear, left top, left bottom, from(#fafafa), to(#e1e3e4));
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        border: 1px solid #c7d8e8;
        -moz-box-shadow: 0px 1px 3px rgba(235,235,235,0.5), inset 0px 0px 1px rgba(255,255,255,1);
        -webkit-box-shadow: 0px 1px 3px rgba(235,235,235,0.5), inset 0px 0px 1px rgba(255,255,255,1);
        box-shadow: 0px 1px 3px rgba(235,235,235,0.5), inset 0px 0px 1px rgba(255,255,255,1);
        text-shadow: 0px -1px 0px rgba(81, 81, 81, 0.7), 0px 1px 0px rgba(255,255,255,0.3);
        text-transform: uppercase;
        text-decoration: none!important;
    }
    .display{
        display: none;
    }
    .aumenta{
        -webkit-transition: height 0,2ms ease-in-out;
        -moz-transition: height 0,2ms ease-in-out;
        -ms-transition: height 0,2ms ease-in-out;
        -o-transition: height 0,2ms ease-in-out;
        transition: height 0,2ms ease-in-out;
        display: table-row;
        height: 230px;
        overflow: auto;
        max-height: 500px;

    }
    #trComment > td > ul{
        list-style: none;
    }
    .buttonCm{
        cursor: pointer;
    }
    .loading > li{
        text-align: center;
        padding-top: 50px;
    }
    .dt-buttons {
        display: none !important;
    }
</style>

<link href="<?=base_url()?>assets/plugins/datepicker/css/datepicker.css" rel="stylesheet">
<script type="text/javascript" src="<?=base_url()?>assets/plugins/datepicker/js/bootstrap-datepicker.js"></script>
<?php
    if ($fatura->data_vencimento >= date('Y-m-d') && in_array($fatura->status_fatura, array(0,2)) )
        $atualiza_status = true;
 ?>
<?php if($msg != ''):?>
<div class="alert">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo $msg?>
</div>
<?php endif;?>
<h3>
	Fatura #
	<?php echo $fatura->Id?>
</h3>
<hr>
<div class="well well-small" id="sticker" style="min-width: 96%">
	<div class="span12">
		<a href="<?php echo site_url('faturas/debitos/'.$fatura->id_cliente)?>" class="btn">
			<i class="icon-chevron-left"></i>
			 Voltar
		</a>
		<a href="" onclick="return false;" class="btn btn-primary btn_imprimir" data-tipo_pagamento="boleto">
            <i class="icon-print icon-white"></i> Boleto
        </a>
        <a href="" onclick="return false;" class="btn btn-primary btn_imprimir" data-tipo_pagamento="paypal">
            <i class="icon-print icon-white"></i> Paypal
        </a>
			<?php if ($fatura->status_fatura == 0 || $fatura->status_fatura == 2):?>

    			<a href="#" id="btn_enviar"
        			class="btn btn-primary btn_enviar"><i class="icon-envelope icon-white"></i>
        			Enviar</a> <a
        			href="<?php echo site_url('extract')?>"
        			class="btn btn-success" ><!--data-toggle="modal"
        			data-target="#baixa_fatura" --><i class="icon-ok icon-white"></i>
    			    Pagamento
                </a>
                <a href data-toggle="modal" data-target="#update_data"
        			class="btn btn-primary"><i class="icon-calendar icon-white"></i>
        			Atualizar Vencimento
                </a>
                <a onclick="$('#atualizar_fatura').submit()" class="btn btn-primary"><i class="icon-calendar icon-white"></i>
        			Salvar
                </a>
                <a href="#envia_anexo" onclick="countAnexo(<?=$fatura->Id;?>)" class="btn btn-primary" id="getAnexo" data-toggle="modal" data-target="#envia_anexo"><i class="fa fa-file"></i>
    				Anexar
    			</a>
                <a href="#comentarios" onclick="getComentarios(<?=$fatura->Id;?>)" class="btn btn-primary" id="getComentarios" data-toggle="modal" data-target="#comentarios"><i class="fa fa-comments"></i>
    				Comentários
    			</a>
                <a href="<?php echo site_url('faturas/form_cancelar_fatura/'.$fatura->Id)?>" data-toggle="modal"
        			data-target="#cancela_fatura" class="btn btn-danger"><i
        			class="icon-remove icon-white"></i> Cancelar
                </a>

			<?php else:?>

			<a onclick="$('#atualizar_fatura').submit()" class="btn btn-primary"><i class="icon-calendar icon-white"></i>
			Salvar</a>
			<a href="#envia_anexo" onclick="countAnexo(<?=$fatura->Id;?>)" class="btn btn-primary" id="getAnexo" data-toggle="modal" data-target="#envia_anexo"><i class="fa fa-file"></i>
				Anexar
			</a>
			<a href="#comentarios" onclick="getComentarios(<?=$fatura->Id;?>)" class="btn btn-primary" id="getComentarios" data-toggle="modal" data-target="#comentarios"><i class="fa fa-comments"></i>
				Comentários
			</a>
			<?php endif;?>
	</div>
	<div class="clearfix"></div>
</div>
<br>
<br>

<div class="span8">
	<div class="span2">
		<div class="control-group">
			<label class="control-label" for="dataFatura"><b>Data Emissão:</b> </label>
			<div class="controls">
				<input type="text" class="span2 data data-emissao" name="data_emissao"
					value="<?php echo data_for_humans($fatura->data_emissao)?>"
					data-controller="<?php echo site_url('faturas/form_update_emissao/'.$fatura->Id)?>" required />
			</div>
		</div>
	</div>
	<div class="span2">
		<div class="control-group">
			<label class="control-label" for="dataVencimento"><b>Data da Fatura:</b>
			</label>
			<div class="controls">
				<input type="text" class="span2 data" name="data_vencimento" style="margin-top: 0px !important;"
					value="<?php echo data_for_humans($fatura->data_vencimento)?>"
					required <?php echo $fatura != false ? 'disabled' : ''?> />
			</div>

		</div>
	</div>

	<div class="span2">
		<div class="control-group">
			<label class="control-label" for="formaPagamento"><b>Venc. Atualizado:</b>
			</label>
			<div class="controls">
				<?php if($fatura->dataatualizado_fatura != NULL):?>
				<input type="text" class="span2 data" name="dataatualizado_fatura"
					value="<?php echo data_for_humans($fatura->dataatualizado_fatura)?>"
					required <?php echo $fatura != false ? 'disabled' : ''?> />
				<?php endif;?>
			</div>

		</div>
	</div>
	<div class="span1" style="height: 95px;">
		<div class="control-group">
			<label class="control-label" for="formaPagamento"><b>Status:</b> </label>
			<div class="controls status_fatura" >
				<?php error_reporting(0); echo status_fatura($fatura->status_fatura, $fatura->data_vencimento, $fatura->instrucoes1)?>
				<?php echo label_nova_data($fatura->status_fatura, $fatura->dataatualizado_fatura)?>
			</div>

		</div>
	</div>
	<form id="atualizar_fatura" method="post" action="<?php echo base_url()?>index.php/faturas/form_update_fatura">
		<input type="hidden" value="<?php echo $fatura->Id?>" name="id_fatura"/>
        <div class="span4">
            <div class="control-group">
                <label class="control-label" for="chave_nfe"><b>Chave NF-e:</b>
                </label>
                <div class="controls">
                    <input type="text" class="span4" name="chave_nfe" value="<?php echo $fatura->chave_nfe?>"/>
                </div>

            </div>
        </div>

        <div class="span3" style="width:100px">
			<div class="control-group">
				<label class="control-label" for="nota_fiscal"><b>Nº Nota fiscal:</b>
				</label>
				<div class="controls">
					<input type="text" class="span3" style="width:100px" maxlength="10" name="nota_fiscal" value="<?php echo $fatura->nota_fiscal?>"/>
				</div>

			</div>
		</div>

		<div class="span2">
			<div class="control-group">
				<label class="control-label" for="mes_referencia"><b>Mês Ref.:</b>
				</label>
				<div class="controls">
					<input type="text" id="mes_referencia" class="span2" name="mes_referencia" <?php if($fatura->periodo_inicial){echo 'value="'.$fatura->mes_referencia.'"';}?>/>
				</div>
			</div>
		</div>

		<div class="span2">
			<div class="control-group">
				<label class="control-label" for="periodo_inicial"><b>Início do período:</b>
				</label>
				<div class="controls">
					<input type="text" class="span2 data campoData input-small datepicker" name="periodo_inicial" <?php if($fatura->periodo_inicial){echo 'value="'.data_for_humans($fatura->periodo_inicial).'"';}?>/>
				</div>
			</div>
		</div>

		<div class="span2">
			<div class="control-group">
				<label class="control-label" for="periodo_final"><b>Fim do período:</b>
				</label>
				<div class="controls">
					<input type="text" class="span2 data1 campoData input-small datepicker" name="periodo_final" <?php if($fatura->periodo_final){echo 'value="'.data_for_humans($fatura->periodo_final).'"';}?>/>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="span4 well well-small pull-right">
	<div class="span2 pull-right">
		<?php echo count($itens)?>
	</div>
	<div class="span1">Qtd. Itens</div>
	<div class="span2 pull-right">
		R$
		<?php echo number_format($this->fatura->have_fee('juros', $itens),2, ',', '.')?>
	</div>
	<div class="span1">Juros</div>
	<div class="span2 pull-right">
		R$
		<?php echo number_format($this->fatura->have_fee('boleto', $itens),2, ',', '.')?>
	</div>
	<div class="span1">Taxa Boleto</div>
	<div class="span2 pull-right">
		R$
		<?php echo number_format($this->fatura->subtotal_fatura($itens),2, ',', '.')?>
	</div>
	<div class="span1">Subtotal</div>
    <div class="span2 pull-right" style="color: red">
        R$
        <?php echo number_format($fatura->valor_abono,2, ',', '.')?>
    </div>
    <div class="span1">Abonado</div>
	<div class="span2 pull-right">
		<b>R$ <?php
			$fatura->valor_total = $fatura->valor_total-($fatura->valor_total * ($fatura->iss / 100))-($fatura->valor_total * ($fatura->cofins / 100))-($fatura->valor_total * ($fatura->irpj / 100))-($fatura->valor_total * ($fatura->csll / 100))-($fatura->valor_total * ($fatura->pis / 100));
			echo number_format($fatura->valor_total,2, ',', '.');
		?>
		</b>
	</div>
	<div class="span1">
		<b>Total</b>
	</div>
</div>

<?php if (count($itens) > 0):?>
<div class="span12">
	<fieldset>
		<legend>Itens da Fatura</legend>
		<div class="span5" style="margin-left: 0px !important">
			<b>Descrição</b>
		</div>
		<div class="span1">
			<b>Valor</b>
		</div>
		<div class="span1">
			<b>Impostos</b>
		</div>
		<div class="span1">
			<b>Taxa</b>
		</div>
		<div class="span1"></div>
	</fieldset>
	<br>
</div>
<div class="span12"	style="overflow: auto; padding-top: 10px; max-height: 400px; margin-bottom: 15px;">
	<?php if (count($itens) > 0):?>
        <?php foreach ($itens as $key => $item):?>
        <div class="container-fluid" id="<?= $item->status != 1 ? 'div_abono' : 'normal'; ?>">
			<div class="span5" id="descricao<?=$item->id_item?>" style="margin-left: 0px !important"><?php echo $item->descricao_item?></div>
            <div class="span1">
                R$ <?php echo $item->valor_item?>
			</div>
			<div class="span1">
            </div>
            <div class="span1">
                <?php if ($item->status == 1): ?>
                    <input type="checkbox" disabled name="taxa_item"
                    <?php echo $item->taxa_item == 'sim' ? 'checked' : ''?> />
                <?php endif; ?>
            </div>
            <div class="span2">
			<a class="btn btn-mini" style="display:none;" id="btnSave<?=$item->id_item?>" onclick="save_comment(<?=$item->id_item?>);"><i class="fa fa-save" style="font-size: 14px;"></i></a>
			<a class="btn btn-mini" id="btnEdit<?=$item->id_item?>" onclick="edit_comment(<?=$item->id_item?>);"><i class="icon-edit"></i>
                </a>
                <a
                    <?php echo $item->status == 1 ? 'href="'.site_url('faturas/form_remove_item/'.$item->id_item.'/'.$item->id_fatura).'" data-toggle="modal" data-target="#remove_item" class="btn btn-danger btn-mini' : 'class="btn btn-default btn-mini"'; ?>
                    title="Remover Item"><i class="icon-remove icon-white"></i>
                </a>
            </div>
            <div class="span11">
                <hr>
            </div>
        </div>
        <?php endforeach;?>
            <div class="span13" style="overflow: auto; padding-top: 10px; max-height: 400px; margin-bottom: 15px;" >
                <div class="span2" style="margin-left: 0px !important">
                    <span class="label label-info">
                    <?php
                        echo $fatura->iss ? 'ISS R$: '.$fatura->iss.' %' : 'ISS R$: 0.00 %';
                    ?>
                    </span>
                </div>
                <div class="span2">
                    <span class="label label-info">
                    <?php
                        echo $fatura->pis ? 'PIS R$: '.$fatura->pis.' %' : 'PIS R$: 0.00 %';
                    ?>
                    </span>
                </div>
                <div class="span2">
                    <span class="label label-info">
                    <?php
                        echo $fatura->cofins ? 'COFINS R$: '.$fatura->cofins.' %': 'COFINS R$: 0.00 %';
                    ?>
                    </span>
                </div>
                <div class="span2">
                    <span class="label label-info">
                    <?php
                        echo $fatura->irpj ? 'IRPJ R$: '.$fatura->irpj.' %' : 'IRPJ R$: 0.00 %';
                    ?>
                    </span>
                </div>
                <div class="span2">
                    <span class="label label-info">
                    <?php
                        echo $fatura->csll ? 'CSLL R$: '.$fatura->csll.' %' : 'CSLL R$: 0.00 %';
                    ?>
                    </span>
                </div>
                <div class="span11">
                    <hr>
                </div>
            </div>
        <?php endif;?>
    <?php endif;?>
</div>


<div id="comentarios" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="false">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel2">Comentários </h3>
	</div>
	<div class="modal-body">
		<div>
			<table>
				<tbody>
					<tr id="trComment"  class="fatura<?php echo $fatura->Id?>">
						<td id="th_comment<?php echo $fatura->Id?>" colspan=13 align="center">
							<ul id="instant<?php echo $fatura->Id?>" class="display">
								<li>
									<textarea style="width: 80%" name="comentario" rows="3" cols="250" disabled></textarea><br>
									<small class="user"><i class="fa fa-check" aria-hidden="true"></i></small>
									<small class="data"><i class="fa fa-clock-o" aria-hidden="true"></i></small>
								</li>
							</ul>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div id="envia_anexo" class="modal fade" tabindex="-1"
		role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel1">Enviar Anexo</h3>
	</div>
	<div class="modal-body">
		<div class="alerta"></div>
		<div>
			<strong>Número de Anexos </strong>
			<label id="countAnexo" class="badge badge-info">0</label>
			<ul class="listAnexo">

			</ul>
		</div>

		<style>input[disabled]{ margin-top: 10px; }</style>
		<form id="formUpload" method="post" enctype="multipart/form-data">
			<input type="file" name="arquivo" required id="anexo" formenctype="multipart/form-data">
			<input type="hidden" id="id_fatura" name="id_fatura" value="">
			<button class="btn btn-success" id="sendAnexo">Enviar</button>
		</form>
	</div>
</div>
<!-- modals -->
<div id="add_item_contrato" class="modal fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true"></button>
		<h3 id="myModalLabel1">Add Taxas</h3>
	</div>
	<div class="modal-body">
		<p>Carregando...</p>
	</div>
</div>

<div id="update_data" class="modal fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">x</button>
		<h3 id="myModalLabel1">Atualizar Data</h3>
	</div>
	<div class="modal-body">
        <div class="row-fluid">
        	<div class="span12 ">
        		<div class="control-group">
                    <div class="placa-alert" style="display:none; text-align:center;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span id="mensagem"></span>
                    </div>
        			<label class="control-label" style="float: left;">Novo vencimento:</label>
                    <input type="text" name="data_nova" required placeholder="Insira uma data" autocomplete="off" id="data_nova" value="" />
                    <div>
                        <button type="button" class="btn btn-primary atualizar_venc">
                			<i class="icon-ok icon-white"></i> Atualizar
                		</button>
                		<!-- <a onclick="fecharModal('#update_data');" class="btn fechar">Fechar</a> -->
                    </div>
        		</div>
        	</div>
        </div>
	</div>
</div>

<div id="remove_item" class="modal fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true"></button>
		<h3 id="myModalLabel1">Remover Item</h3>
	</div>
	<div class="modal-body">
		<p>Carregando...</p>
	</div>
</div>
<div id="baixa_fatura" class="modal fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true"></button>
		<h3 id="myModalLabel1">Baixa Manual</h3>
	</div>
	<div class="modal-body">
		<p>Carregando...</p>
	</div>
</div>

<div id="cancela_fatura" class="modal fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true"></button>
		<h3 id="myModalLabel1">Cancela Fatura</h3>
	</div>
	<div class="modal-body">
		<p>Carregando...</p>
	</div>
</div>

<div id="update_emissao" class="modal fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true"></button>
		<h3 id="myModalLabel1">Alterar Data Emissão</h3>
	</div>
	<div class="modal-body">
		<p>Carregando...</p>
	</div>
</div>


<script>
	if (window.location.host == "localhost"){
        var url = "ftp://show:show2592@ftp-arquivos.showtecnologia.com/particao_ftp/uploads/anexo_fatura/";
    }else{
        url = "ftp://show:show2592@ftp-arquivos.showtecnologia.com/particao_ftp/uploads/anexo_fatura/";
    }


        controller1 = "<?php echo base_url(); ?>index.php/faturas/count_anexo";
        controller2 = "<?php echo base_url(); ?>index.php/faturas/list_anexos";
        controller3 = "<?php echo base_url(); ?>index.php/faturas/anexar";
        controller4 = "<?php echo base_url(); ?>index.php/faturas/comentario";
        controller5 = "<?php echo base_url(); ?>index.php/faturas/getComments";
        getController = "<?php echo base_url(); ?>index.php/faturas/getComentarios";
    $('#sendAnexo').click(function () {
        $('#formUpload').ajaxForm({
            url: controller3,
            contentType: 'multipart/form-data',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                $('.alerta').html(data.mensagem);

            },
            error: function (data) {
                $('.alerta').html(data.mensagem);
            }
        })
    });

    // atualiza a data de vencimento
    $(document).on('click', '.atualizar_venc', function() {
        $('.atualizar_venc').attr('disabled', 'true')
        .html('<i class="fa fa-spinner fa-spin"></i> Atualizando...');

        $.ajax({
            url: "<?= site_url('faturas/form_atualizar_data') ?>",
            type: "POST",
            dataType: "json",
            data: {
                data_nova : $("#data_nova")[0].value,
                id_fatura : '<?= $fatura->Id ?>'
            },
            success: function(retorno){
                console.log(retorno);
                if (retorno.status == 'OK') {
                    window.location.href = "<?= site_url('faturas/abrir') ?>/"+retorno.nova_fatura;
                    $("#mensagem").html(retorno.msg);
                    $('.atualizar_venc').attr('disabled', false).html('<i class="icon-ok icon-white"></i> Atualizar');
                }else {
                    $('.placa-alert').css('display', 'block');
                    $("#mensagem").html(retorno.msg);
                    $('.atualizar_venc').attr('disabled', false).html('<i class="icon-ok icon-white"></i> Atualizar');
                }
            },
            error: function(retorno){
                $('.placa-alert').css('display', 'block');
                $("#mensagem").html(retorno.msg);
                $('.atualizar_venc').attr('disabled', false).html('<i class="icon-ok icon-white"></i> Atualizar');
            }
        })


    });

    //NAO PERMITE A IMPRESSAO BOLETO/PAYPAL CASO A FATURA TENHA DATA EMISSAO MENOR QUE DATA VENCIMENTO E DATA VENCIMENTO MENOR QUE DATA ATUAL
    $(document).on('click', '.btn_imprimir', function() {
        var tipoPagamento = $(this).attr('data-tipo_pagamento');

        var vencimento = new Date("<?=$fatura->data_vencimento?>"+" 23:59:59");
        var emissao = new Date("<?=$fatura->data_emissao?>"+" 23:59:59");
        var dataAtualizado = new Date("<?=$fatura->dataatualizado_fatura ? $fatura->dataatualizado_fatura: $fatura->data_vencimento?>"+" 23:59:59");
        var hoje = new Date();

        if (emissao > vencimento) {
            alert('Data de emissão não pode ser maior que a data de vencimento!');
        }else if (dataAtualizado < hoje) {
            alert('Fatura com vencimento desatualizado, atualize para continuar!');
        }else {
            if ('<?=$atualiza_status?>') {
                $('.status_fatura').html('<span class="label label-warning">A pagar</span>');  //atualiza o label do status da fatura
                window.open("<?=site_url('faturas/imprimir_fatura')?>/<?= $fatura->Id ?>", '_blank');
            }
            if (tipoPagamento == 'boleto')
                window.open("<?=site_url('faturas/imprimir_fatura')?>/<?= $fatura->Id ?>", '_blank');
            else
                window.open("<?=site_url('faturas/imprimir_fatura')?>/<?= $fatura->Id ?>?formaPagamento=paypal", '_blank');
        }
    });

    $(document).on('click', '.btn_enviar', function() {
        var button = $(this);

        button.attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> Enviando...');
        $.ajax({
    		url: "<?= site_url('faturas/enviar/'.$fatura->numero)?>",
    		success: function(data){
                $('#btn_enviar').show();
                button.removeClass('btn-primary')
                    .addClass('btn-success')
                    .attr('disabled', 'true')
                    .html('<i class="fa fa-check"></i> Enviado :)');

                    $('.status_fatura').html('<span class="label label-warning">A pagar</span>');  //atualiza o label do status da fatura
                alert(data);
    		},
            error: function(data) {
                alert(data);
                button.removeAttr('disabled').html('Enviar');
            }
    	});
    });

    $('#countAnexo').html(0);
    function countAnexo(fatura) {
        $('.alerta').html("");
        $('.listAnexo').html("");
        $('#id_fatura').val(fatura);
        $.ajax({
            type: "post",
            data: {id: fatura},
            url: controller1,
            dataType: "json",
            success: function(data){
                $('#countAnexo').html(data);
            }
        });
        $.ajax({
            type: "post",
            data: {id: fatura},
            url: controller2,
            dataType: "JSON",
            success: function(data){
                $.each(data, function (i, arquivo) {
                    $('.listAnexo').append("<li><a class='linkAnexo' href="+url+encodeURI(arquivo.file)+" target='_blank'><i class='fa fa-file'></i> " +(i+1)+" - "+arquivo.file+"</a></li><hr>");
                });
            }
        });
    }
	function getComentarios(Id){
		retorno =  $.ajax({
			type: "post",
			data: {id_fatura: Id},
			url: controller5,
			dataType: "JSON",
			success: function(data2){
				var enviaId = "texto"+Id;
				var tpl2 = [
					'<ul><li><h4>Comentários <i class="fa fa-wechat"></i></h4></li></ul>'+
					'<ul><li>'+
					'<textarea style="width: 80%" id='+enviaId+' rows="4" cols="250" placeholder="Escreva Aqui..."></textarea><br>'+
					'<button  class="btn btn-success" onclick="enviar('+Id+')" >Enviar</button>'+
					'</li></ul>'
				].join('');
				$('#th_comment'+Id).prepend(tpl2);
				$(enviaId).focus(function() {
					$(this).val('');
				});
				$.each(data2, function (i, view) {
					var tpl = [
						'<ul><li>'+
						'<textarea style="width: 80%" disabled rows="3" cols="250">'+view.comentario+'</textarea><br>'+
						'<small>'+view.user+ ' <i class="fa fa-check" aria-hidden="true"></i></small>'+
						'<small class="">' +view.data+ ' <i class="fa fa-clock-o" aria-hidden="true"></i></small>'+
						'</li></ul>'
					].join('');
					$('#th_comment'+Id).append(tpl);
				});
			}
		});
	}
	enviar  =  function sendComment(id) {
		var texto = $('#texto'+id).val();
		$.ajax({
			type: "post",
			data: {
				comentario: texto,
				id_fatura: id
			},
			url: controller4,
			dataType: "json",
			success: function(dados){
				var data = new Date();
				var dia     = data.getDate();           // 1-31
				if (dia < 10){dia = "0"+dia}
				var mes     = data.getMonth()+1;          // 0-11 (zero=janeiro)
				if (mes < 10){mes = "0"+mes}
				var ano    = data.getFullYear();       // 4 dígitos
				var hora    = data.getHours();          // 0-23
				if (hora < 10){hora = "0"+hora}
				var min     = data.getMinutes();        // 0-59
				if (min < 10){min = "0"+min}
				var seg     = data.getSeconds();        // 0-59
				if (seg < 10){seg = "0"+seg}
				var str_data = dia + '/' + (mes) + '/' + ano;
				var str_hora = hora + ':' + min + ':' + seg;

				$("textarea[name='comentario']").val(dados.comentario);
				$('.user').text(dados.user);
				$('.data').text(str_data +" "+ str_hora);
				$('#instant'+id).toggleClass('display');
			}
		});
	}
	$('input[id$=mes_referencia]').datepicker(
			{format: 'mm/yyyy'
	 });
</script>
<script>
	function edit_comment(id){
		var descricao = $('#descricao'+id).text();
		document.getElementById("descricao"+id).innerHTML='<input type="text" id="inputDescricao'+id+'"class="span5" value="'+descricao+'"></input>';
		document.getElementById("btnSave"+id).style.display = null;
		document.getElementById("btnEdit"+id).style.display = "none";
	}
	function save_comment(id){
		var descricao = $('#inputDescricao'+id).val();
		var http = new XMLHttpRequest();
		var url = "<?php echo base_url()?>index.php/faturas/form_update_fatura";
		var params = "id_comment="+id+"&comment="+descricao;
		http.open("POST", url, true);

		//Send the proper header information along with the request
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		http.onreadystatechange = function() {//Call a function when the state changes.
			if(http.readyState == 4 && http.status == 200) {
				document.getElementById("descricao"+id).innerHTML=descricao;
				document.getElementById("btnSave"+id).style.display = "none";
				document.getElementById("btnEdit"+id).style.display = null;
			}
		}
		http.send(params);
	}
	function confirm_click(){
		if (confirm("Deseja atualizar o número da fatura?")) {
			return true;
		}
		return false;
	}

    function nEnviado_aPagar(id){
        if ($('.status_fatura_'+id).text() == 'f_abertoNão enviado') {
            $('.status_fatura_'+id).html(
                '<span class="hidden">f_aberto</span>'+
                '<span class="label label-warning">A pagar</span>');
        }
    }

    $("#data_nova").datepicker({
        format: 'dd/mm/yyyy'
    });



</script>
