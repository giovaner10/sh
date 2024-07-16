<br>
<div class="containner">	
	<title><?php echo $titulo ?></title>
	<div id="home" class="tab-pane fade in active well">
		<h3><b>Dados da linha</b></h3>
		<form>
			<div class="dados-pessoais control-group">
				<div class="line-dados">
					<div class="box-input">
						<label class="con-label">CCID</label>
						<div class="controls">
							<input type="text" onkeyup="somenteNumeros(this);" name="ccid" value="<?php echo $linha->ccid?>" class="form-control">
						</div>
					</div>
					<div class="box-input">
						<label class="con-label">LINHA</label>
						<div class="controls">
							<input type="text" onkeyup="somenteNumeros(this);" name="numero" value="<?php echo $linha->numero?>" class="form-control">
						</div>
					</div>
					<div class="box-input">
						<label class="con-label">CONTA</label>
						<div class="controls">
							<input type="text" onkeyup="somenteNumeros(this);" name="conta" value="<?php echo $linha->conta?>" class="form-control">
						</div>
					</div>
					<div class="box-input">
						<label class="con-label">OPERADORA</label>
						<div class="controls">
							<select name="operadora" class="form-control" value="<?php echo $linha->operadora?>">
								<option value="<?php echo $linha->operadora?>"><?php echo $linha->operadora?></option>
								<option>--------------------</option>
								<option value="CLARO">CLARO</option>
								<option value="VIVO">VIVO</option>
								<option value="VODAFONE">VODAFONE</option>
								<option value="TIM">TIM</option>
							</select>
						</div>
					</div><br>
					<div class="box-input">
						<label class="con-label">STATUS</label>
						<div class="controls">
							<label class="container"><span style='background-color: #4676bf;' class='badge badge-cadastrada'>CADASTRADA</span>
								<input value="0" type="radio" name="status"<?php if($linha->status == 0){echo 'checked';}?>>
								<span class="checkmark"></span>
							</label>
							<label class="container"><span style='background-color: #d88c2f;' class='badge badge-habilitada'>HABILITADA</span>
								<input value="1" type="radio" name="status"<?php if($linha->status == 1){echo 'checked';}?>>
								<span class="checkmark"></span>
							</label>
							<label class="container"><span style='background-color: #18b23a;' class='badge badge ativa'>ATIVA</span>
								<input value="2" type="radio" name="status"<?php if($linha->status == 2){echo 'checked';}?>>
								<span class="checkmark"></span>
							</label>
							<label class="container"><span style='background-color: #ddd606;' class='badge badge-cancelada'>CANCELADA</span>
								<input value="3" type="radio" name="status"<?php if($linha->status == 3){echo 'checked';}?>>
								<span class="checkmark"></span>
							</label>
							<label class="container"><span style='background-color: #d60c0c;' class='badge badge-bloqueada'>BLOQUEADA</span>
								<input value="4" type="radio" name="status"<?php if($linha->status == 4){echo 'checked';}?>>
								<span class="checkmark"></span>
							</label>
							<label class="container"><span style='background-color: #9313c1;' class='badge badge-suspensa'>SUSPENSA</span>
								<input value="5" type="radio" name="status"<?php if($linha->status == 5){echo 'checked';}?>>
								<span class="checkmark"></span>
							</label> 
						</div>
					</div>
				</div>
			</div>
			<div align="center">
				<input type="hidden" name="id" value="<?php echo $linha->id?>">
				<button class="btn btn-primary" id="editar">EDITAR</button>
			</div>
		</form>
	</div>
</div>
<script>
	$('#editar').on('click', function(event){
		event.preventDefault();
		var button = $('#editar');
		var id = $('input[name=id]').val();
		var ccid = $('input[name=ccid]').val();
		var numero = $('input[name=numero]').val();
		var conta = $('input[name=conta]').val();
		var status = $("input[name='status']:checked").val();
		var operadora = $('select[name=operadora]').val();		
		button.html('<i class="fa fa-spinner fa-spin"></i> Editando...').attr('disabled', 'true');
		$.ajax({
			type:'POST',
			url:'../editar_ajax',
			dataType: 'json',
			data:{id,ccid,numero,conta,status,operadora},
			success:function(callback){											
				alert(callback.msg);
				button.removeAttr('disabled').html('Editar');					
			},
			error: function(callback){
				alert('Erro ao Editar, tente novamente!');
				button.removeAttr('disabled').html('Editar');
			}
		});		
	});

	function somenteNumeros(num) {
		var er = /[^0-9.]/;
		er.lastIndex = 0;
		var campo = num;
		if (er.test(campo.value)) {
			campo.value = "";
		}
	}
</script>