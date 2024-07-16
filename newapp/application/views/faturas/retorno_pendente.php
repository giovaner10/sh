<?php if($msg != ''):?>
<div class="alert">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>CONCLUIDO!</strong>
	<?php echo $msg?>
</div>
<?php endif;?>
<h3>Faturas Pendentes Retorno</h3>
<hr>
<div class="well well-small">
	<div class="span8">
		
		<a href="<?php echo get_url()?>" class="btn">
			<i class="icon-chevron-left"></i> Voltar
		</a>
		
	</div>
	
	<div class="clearfix"></div>
</div>
<div class="span12">
	<table class="table">
		<thead>
			<tr>
				<th>Fatura</th>
				<th>Falha</th>
				<th>Val. Pago</th>
				<th>Data Pag.</th>
				<th>Retorno</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php if ($retornos):?>
			<?php foreach ($retornos as $key=>$fat_ret):?>
			<?php echo form_open('faturas/filtro', array('target' => '_blank'), array('filtro' => $fat_ret->fatnumero_retorno))?>
			<tr class="error">
				<td><?php echo $fat_ret->fatnumero_retorno?></td>
				<td><?php echo $fat_ret->msgstatusexec_retorno?>
				</td>
				<td>R$ <?php echo number_format($fat_ret->valorpago_retorno, 2, ',', '.'); ?>
				</td>
				<td><?php echo data_for_humans($fat_ret->datapagto_retorno) ?>
				</td>
				<td><?php echo $fat_ret->arquivo_retorno?></td>
				<td>
					<i class="fa fa-edit" style="cursor:pointer;" onclick="openModal(<?=$key?>)"></i>
				</td>
			</tr>
			<?php echo form_close()?>
			<?php endforeach;?>
			<?php endif;?>
		</tbody>

	</table>
</div>

<script>
	var id_fatura_click = false;
	Number.prototype.formatMoney = function(c, d, t){
        var n = this, 
            c = isNaN(c = Math.abs(c)) ? 2 : c, 
            d = d == undefined ? "." : d, 
            t = t == undefined ? "," : t, 
            s = n < 0 ? "-" : "", 
            i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
            j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	};
	function data_human(data){
		var d_split = data.split('-');
		return d_split[2]+'/'+d_split[1]+'/'+d_split[0];
	}
	var retorno_json = JSON.parse('<?php if (isset($retornos) && count($retornos > 0)){echo json_encode($retornos);}?>');
	function openModal(id){
		id_fatura_click=id;
		$("#myModal").modal();
		$('#id_fatura_edit').val(retorno_json[id].fatnumero_retorno);
		$('#valor_fatura_edit').text(parseFloat(retorno_json[id].valorpago_retorno).formatMoney(2, ',', '.'));
		$('#data_fatura_edit').text(data_human(retorno_json[id].datapagto_retorno));

	}
	function save_baixa_fatura(){
		retorno_json[id_fatura_click].fatnumero_retorno=$('#id_fatura_edit').val();
		var http = new XMLHttpRequest();
		var url = "<?=base_url()?>index.php/faturas/baixa_retorno_manual";
		var params = "json="+JSON.stringify(retorno_json[id_fatura_click]);
		http.open("POST", url, true);

		//Send the proper header information along with the request
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		http.onreadystatechange = function() {//Call a function when the state changes.
			if(http.readyState == 4 && http.status == 200) {
				var j_r = JSON.parse(http.responseText).financeiro[0];
				console.log(j_r);
				var cliente;
				if(typeof j_r.nome !== 'undefined'){
					cliente = j_r.nome;
				}else{
					cliente = j_r.erro;
				} 
				var html = ['<h4>Fatura Baixada no Financeiro</h4>'+
				'<table class="table table-condensed">'+
					'<thead>'+
						'<tr>'+
							'<th>Fatura</th>'+
							'<th>Cliente</th>'+
							'<th>Val. Pago</th>'+
							'<th>Data Pag.</th>'+
						'</tr>'+
					'</thead>'+
					'<tbody>'+
						'<tr>'+
							'<td>'+j_r.numero+'</td>'+
							'<td>'+cliente+'</td>'+
							'<td>R$ '+parseFloat(j_r.valor_pago).formatMoney(2, ',', '.')+'</td>'+
							'<td>'+data_human(j_r.data_pagto)+'</td>'+
						'</tr>'+
					'</tbody>'+
				'</table>'
				].join();
				console.log(html);
				$('#fatura_baixada_manual').html(html);
			}
		}
		http.send(params);
	}
</script>

<div id="myModal" class="modal hide fade" role="dialog" aria-hidden="false">
	<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Editar retorno</h4>
		</div>
	<div class="modal-body">
		<table class="table">
			<thead>
				<tr>
					<th>Número da fatura</th>
					<th>Valor do pagamento</th>
					<th>Data do pagamento</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<input id="id_fatura_edit" class="form-control" name="numero_fatura" value="" style="width: 100px;">
					</td>
					<td id="valor_fatura_edit">
					</td>
					<td id="data_fatura_edit">
					</td>
				</tr>
			</tbody>
		</table>
		<div id="fatura_baixada_manual">
		</div>
	</div>
	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
		<button type="button" class="btn btn-default btn-success" onclick="save_baixa_fatura();">Salvar</button>
      </div>
	</div>
  </div>
</div>