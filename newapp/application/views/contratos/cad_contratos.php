<?php 
	foreach ($clientes as $cliente) {
		$cliente_nome = $cliente->nome;
		$cliente_id = $cliente->id;
	}

 ?>

<h3>Cliente - <?php echo $cliente_nome ?><small></small></h3>

<form action="<?php echo site_url('contratos/cadastrar_contrato') ?>" method="post" class="form-horizontal formulario" id="contratos">

<div class="well well-small botoes-acao">
	<div class="btn-group">
		<a href="<?php echo site_url('clientes/view/'.$cliente->id)?>" class="btn btn-info voltar" title="Voltar"><i class="icon-arrow-left icon-white"></i></a>
	</div>
	<div class="btn-group pull-right">
		<button type="submit" class="btn btn-primary" title="Salva os dados preenchidos"><i class="icon-download-alt icon-white"></i> Salvar</button>
		<button type="button" class="limpar btn btn-primary" data-form="#contratos" onClick="document.getElementById('contratos').reset();return false" title="Restaura as informações iniciais"><i class="icon-leaf icon-white"></i> Limpar</button>
	</div>
</div>
<div class="resultado span6" style="float: none;"></div>
	<div class="row-fluid">
		<div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="tab1 active" style="font-size: 18px;"><a href="#tab1" data-toggle="tab"><b>Cadastro de Contrato</b></a></li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="tab1">
					<div class="row dados">
						<div class="span8">
							<fieldset class="contrato" id="0">

								<div class="control-group">
									<label class="control-label">Tipo Contrato:</label>
									<div class="controls controls-row">
										<input type="hidden" name="contrato[cliente_id]" value= '<?php echo $cliente_id ?>' class="input"/>
										<select id="tipo_cont" name="contrato[tipo]" class="span6" required>
											<option value="">Selecione o Tipo de Contrato</option>
											<option value="0">Gestor - Rastreador</option>
											<option value="1">Chip de Dados</option>
											<option value="2">Telemetria</option>
											<option value="3">SIGA ME - NORIO MOMOI EPP</option>
                                            <option value="4">Rastreamento Pessoal</option>
											<option value="5">Gestão de Entregas</option>
											<option value="6">Iscas</option>
										</select>
										<span class="help help-block"></span>
									</div>
								</div>

                                <div class="display-eqp control-group" style="display: none;">
                                    <label class="control-label">Tipo Equipamento:</label>
                                    <div class="controls controls-row">
                                        <select name="contrato[tipo_eqp]" class="span6">
                                            <option value="">Selecione o Tipo Equipamento</option>
                                            <option>Tornozeleiras</option>
                                            <option>Dispositivos S.O.S</option>
                                        </select>
                                        <span class="help help-block"></span>
                                    </div>
                                </div>

								<div class="control-group">
									<label class="control-label">Vendedor:</label>
									<div class="controls controls-row">
										<select name="contrato[vendedor]" class="span6" required>
											<option value="">Selecione Vendedor</option>
											<?php
												foreach ($usuarios as $usuario) {
													echo '<option value="'.$usuario->id.'">'.$usuario->nome.'</option>';
												}
											 ?>
										</select>
										<span class="help help-block"></span>
									</div>
								</div>

								<div class="dados_contrato">
									<div class="control-group">
										<label class="control-label">Dados do Contrato:</label>
										<div class="controls controls-row">
											<select name="contrato[meses_contrato]" rel="tooltip" title="Para padrão (36 meses). Não alterar !" class="span4" required>
												<option value="36">Meses do Contrato (36)</option>
												<?php for ($i=1; $i < 37; $i++) {
													if ($i == 1) {
														echo '<option value="'.$i.'">'.$i.' mês</option>';
													}else{
														echo '<option value="'.$i.'">'.$i.' meses</option>';
													}
												} ?>
											</select>
											<select name="contrato[dia_vencimento]" rel="tooltip" title="Para padrão (dia 30). Não alterar !" class="span4" required>
												<option value="30">Dia Vencimento (dia 30)</option>
												<?php for ($i=1; $i < 31; $i++) {
													echo '<option value="'.$i.'">Dia '.$i.'</option>';
												} ?>
											</select>
											<input type="text" name="contrato[primeira_mensalidade]" value="" class="span4 calendarioos" placeholder="1º Mensalidade" required>
											<span class="help help-block"></span>
										</div>
									</div>
								</div>

								<div class="dados_veiculos">
									<div class="control-group">
										<label class="control-label"></label>
										<div class="controls controls-row">
											<input type="text" id="numeros_veiculos" name="contrato[numeros_veiculos]" value="" class="span4" rel="tooltip" title="Número de Veículos" placeholder="Número de Veículos" onkeypress="return SomenteNumero(event);" required>
											<select name="contrato[taxa_boleto]" rel="tooltip" title="Para padrão (Sim). Não alterar !" class="span4" required>
												<option value="1">Tem taxa de Boleto? (Sim)</option>
												<option value="1">Sim</option>
												<option value="0">Não</option>
											</select>
											<span class="help help-block"></span>
										</div>
									</div>
								</div>

								<div class="dados_veiculos">
									<div class="control-group">
										<label class="control-label"></label>
										<div class="controls controls-row">
											<input type="text" id="mensal_por_veiculo" name="contrato[mensal_por_veiculo]"  value="" class="span4" rel="tooltip" title="Valor Mensal por Veículo" placeholder="Valor Mensal por Veículo" onKeyPress="return(MascaraMoeda(this,'.',',',event))" required>
											<input type="text" id="total_mensal" name="contrato[total_mensal]" value="" class="span4" rel="tooltip" title="Total Mensal" placeholder="Total Mensal" readonly="readonly">
											<span class="help help-block"></span>
										</div>
									</div>
								</div>

								<div class="dados_contrato adesao_display">
									<div class="control-group">
										<label class="control-label">Adesão:</label>
										<div class="controls controls-row">
											<input type="text" id="instalacao_por_veiculo" name="contrato[instalacao_por_veiculo]"  value="" class="span4" rel="tooltip" title="Valor Instalação por Veículo" placeholder="Valor Instalação por Veículo" onKeyPress="return(MascaraMoeda(this,'.',',',event))" required>
											<input type="text" id="total_instalacao" name="contrato[total_instalacao]" value="" class="span4" rel="tooltip" title="Total Instalação" placeholder="Total Instalação" readonly="readonly">
											<span class="help help-block"></span>
										</div>
									</div>
								</div>

								<div class="dados_contrato adesao_display">
									<div class="control-group">
										<label class="control-label"></label>
										<div class="controls controls-row">
											<input type="text" id="instalacao_parcelas" name="contrato[instalacao_parcelas]" value="" class="span4" rel="tooltip" title="Instalação em Parcelas" placeholder="Instalação em Parcelas" onkeypress="return SomenteNumero(event);" required>
											<input type="text" id="valor_parcela_instalacao" name="contrato[valor_parcela_instalacao]" value="" class="span4 valor_parcela_instalacao" rel="tooltip" title="Valor da Parcela" placeholder="Valor da Parcela" readonly="readonly">
											<input class="span4 calendarioos data_primeira_parcela" name="contrato[data_primeira_parcela]" type="text" value="" placeholder="Data da Primeira Parcela" required>
											<span class="help help-block"></span>
										</div>
									</div>
								</div>

								<div class="dados_contrato">
									<div class="control-group">
										<label class="control-label">Multa Contratual:</label>
										<div class="controls controls-row">
											<label class="radio inblock sem_multa"><input type="radio" name="contrato[multa_contrato]" value="3"> Sem Multa</label>
											<label class="radio inblock sem_multa"><input type="radio" name="contrato[multa_contrato]" value="1" checked> Multa Proporcional ao Contrato</label>
											<label class="radio inblock com_multa"><input type="radio" name="contrato[multa_contrato]" value="2" > Multa Valor Negociado por Veiculo</label>
											<input type="text" name="contrato[valor_multa]" id="multa_cont" value="" class="span3 valor_multa" placeholder="Valor da Multa" rel="tooltip" title="Valor da Multa" onKeyPress="return(MascaraMoeda(this,'.',',',event))" readonly="readonly">
											<span class="help help-block"></span>
										</div>
									</div>
								</div>

								<div class="dados_contrato">
									<div class="control-group">
										<label class="control-label">Data do Contrato:</label>
										<div class="controls controls-row">
											<input class="span4 calendarioos data_contrato" name="contrato[data_contrato]" type="text" value="" placeholder="Data do Contrato" required/>
											<span class="help help-block"></span>
										</div>
									</div>
								</div>

								<div class="control-group">
									<div class="controls controls-row">
										<?php if (isset($ajuste)): ?>
											<input type="hidden" name="contrato[ajuste]" value="<?=$ajuste?>" placeholder="">
										<?php endif; ?>
									</div>
								</div>

							</fieldset>
						</div>
					</div>
					<!-- ---------------- / formulario coluna ---------------- -->
				</div>

			</div>
		</div>
	</div>
</form>

<script type="text/javascript">


$(document).ready(function() {

    $('#tipo_cont').change(function () {
       if ($('#tipo_cont option:selected').val() == 4) {
           $('.adesao_display').removeAttr('style');
           $('.display-eqp').removeAttr('style');
           $('#mensal_por_veiculo').attr('placeholder', 'Valor Mensal por Pessoa');
           $('#numeros_veiculos').attr('placeholder', 'Número de Pessoas');
           $('#instalacao_por_veiculo').attr('placeholder', 'Valor Instalação por Pessoa');
       } else if($('#tipo_cont option:selected').val() == 5) {
           $("#instalacao_parcelas").removeAttr('required');
           $("#instalacao_por_veiculo").removeAttr('required');
           $(".data_primeira_parcela").removeAttr('required');
           $('.display-eqp').attr('style', 'display:none');
           $('#mensal_por_veiculo').attr('placeholder', 'Valor Mensal por Ponto');
           $('#numeros_veiculos').attr('placeholder', 'Quantidade de Pontos/mês');
           $('.adesao_display').attr('style', 'display: none;')
       } else {
           $("#instalacao_parcelas").attr('required');
           $("#instalacao_por_veiculo").attr('required');
           $(".data_primeira_parcela").attr('required');
           $('.adesao_display').removeAttr('style');
           $('.display-eqp').attr('style', 'display:none');
           $('#mensal_por_veiculo').attr('placeholder', 'Valor Mensal por Veículo');
           $('#numeros_veiculos').attr('placeholder', 'Número de Veículos');
           $('.instalacao_por_veiculo').attr('placeholder', 'Valor Instalação por Veículo');
       }
    });

	$('.calendarioos').focus(function(){
		$(this).calendario({target: $(this)});
	});

	$("#mensal_por_veiculo").keypress(function(){

	var numeros_veiculos = $("#numeros_veiculos").val();
	var mensal_por_veiculo_parcial = $("#mensal_por_veiculo").val().replace('.', '');
	var mensal_por_veiculo = mensal_por_veiculo_parcial.replace(',', '.');

	var multi =  parseFloat(numeros_veiculos) * parseFloat(mensal_por_veiculo);

	$("#total_mensal").val((multi).formatMoney(2, ',', '.'));

	});

	$("#instalacao_por_veiculo").keypress(function(){

	var numeros_veiculos = $("#numeros_veiculos").val();
	var instalacao_por_veiculo_parcial = $('#instalacao_por_veiculo').val() ? $("#instalacao_por_veiculo").val().replace('.', '') : '0,00';
	var instalacao_por_veiculo = instalacao_por_veiculo_parcial.replace(',', '.');

	var multip =  parseFloat(numeros_veiculos) * parseFloat(instalacao_por_veiculo);

	$("#total_instalacao").val((multip).formatMoney(2, ',', '.'));

	});

	$(".data_primeira_parcela,.valor_parcela_instalacao,.data_contrato,.valor_multa").click(function(){

	var total_instalacao_parcial = $("#total_instalacao").val().replace('.', '');
	var total_instalacao = total_instalacao_parcial.replace(',', '.');
	var instalacao_parcelas = $("#instalacao_parcelas").val();

	var valor_parcelas = parseFloat(total_instalacao) / parseFloat(instalacao_parcelas);

	$("#valor_parcela_instalacao").val((valor_parcelas).formatMoney(2, ',', '.'));

	});

	$("[rel=tooltip]").tooltip({ placement: 'right'});

	$('.sem_multa').on('click', function(){
		$("#multa_cont").attr("readonly", true);
		$('#multa_cont').prop('required', false);

	});

	$('.com_multa').on('click', function(){
		$("#multa_cont").attr("readonly", false);
		$('#multa_cont').prop('required', true);

	});
});

function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? e.which : e.keyCode;
	if (whichCode == 13) return true;
	key = String.fromCharCode(whichCode); // Valor para o código da Chave
	if (strCheck.indexOf(key) == -1) return false; // Chave inválida
	len = objTextBox.value.length;
	for(i = 0; i < len; i++)
		if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
	aux = '';
	for(; i < len; i++)
		if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
	aux += key;
	len = aux.length;
	if (len == 0) objTextBox.value = '';
	if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;
	if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + aux;
	if (len > 2) {
		aux2 = '';
		for (j = 0, i = len - 3; i >= 0; i--) {
			if (j == 3) {
				aux2 += SeparadorMilesimo;
				j = 0;
			}
			aux2 += aux.charAt(i);
			j++;
		}
		objTextBox.value = '';
		len2 = aux2.length;
		for (i = len2 - 1; i >= 0; i--)
		objTextBox.value += aux2.charAt(i);
		objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
	}
	return false;
}

	function SomenteNumero(e){
		 var tecla=(window.event)?event.keyCode:e.which;
		 if((tecla>47 && tecla<58)) return true;
		 else{
		 if (tecla==8 || tecla==0) return true;
		 else  return false;
		 }
	}

//Transforma numero em real//
Number.prototype.formatMoney = function(c, d, t){
	var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
</script>
<script type="text/javascript">
$(document).ready(function(){
	$(".resultado").hide();
	$("#contratos").ajaxForm({
		target: '.resultado',
		dataType: 'json',
		success: function(retorno){
			$(".resultado").html(retorno.mensagem);
			$(".resultado").show();
		}
	});
});
</script>
<script type="text/javascript" src="<?php echo base_url('media')?>/js/calendario.js"></script>
