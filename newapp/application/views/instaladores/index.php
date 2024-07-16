<h3><?php echo lang('bshownet')?> <small> <?php echo lang('fr')?></small></h3>
<?php if(!isset($retorno)): ?>

<?php elseif($retorno): ?>
	<div class="alert alert-success">
		<span><?php echo lang('fr2')?></span>	
	</div>
<?php else: ?>
	<div class="alert alert-danger">
		<span><?php echo lang('fr3')?></span>	
	</div>
<?php endif; ?>
<hr>
<div class="row-fluid">
	<?php echo form_open('', array('class' => 'form-horizontal'))?>
	<div class="span3">
	<?php if (isset($instalador) && count($instalador)): ?>
		<input type="hidden" name="id" value="<?php echo $instalador->id?>">
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('nome')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="icon-user"></i></span>
					<input type="text" name="nome" value="<?php echo $instalador->nome ?>" class="input-large" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('sobrenome')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="icon-user"></i></span>
					<input type="text" name="sobrenome" value="<?php echo $instalador->sobrenome ?>" class="input-large" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('cpf')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="icon-user"></i></span>
					<input type="text" class="cpff" name="cpf" value="<?php echo $instalador->cpf ?>" class="input-large" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('email')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="icon-envelope"></i></span>
					<input type="text" name="email" value="<?php echo $instalador->email ?>" class="input-large" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('endereco')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-home"></i></span>
					<input type="text" name="endereco" value="<?php echo $instalador->endereco ?>" class="input-large" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('numero')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-home"></i></span>
					<input type="text" id="numero" name="numero" value="<?php echo $instalador->numero ?>" class="input-mini" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('bairro')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-home"></i></span>
					<input type="text" name="bairro" value="<?php echo $instalador->bairro ?>" class="input-medium" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('cep')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-envelope-o"></i></span>
					<input type="text" class="cep" name="cep" value="<?php echo $instalador->cep ?>" class="input-small" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('estado')?></label>
			<div class="controls">
				<div class="input-prepend estado">
                	<span class="add-on"><i class="fa fa-map-marker"></i></span>
					<select name="estado" id="estado">
						  <option value="AC">Acre</option>
		                  <option value="AL">Alagoas</option>
		                  <option value="AM">Amazonas</option>
		                  <option value="AP">Amapá</option>
		                  <option value="BA">Bahia</option>
		                  <option value="CE">Ceará</option>
		                  <option value="DF">Distrito Federal</option>
		                  <option value="ES">Espirito Santo</option>
		                  <option value="GO">Goiás</option>
		                  <option value="MA">Maranhão</option>
		                  <option value="MG">Minas Gerais</option>
		                  <option value="MS">Mato Grosso do Sul</option>
		                  <option value="MT">Mato Grosso</option>
		                  <option value="PA">Pará</option>
		                  <option value="PB">Paraíba</option>
		                  <option value="PE">Pernambuco</option>
		                  <option value="PI">Piauí</option>
		                  <option value="PR">Paraná</option>
		                  <option value="RJ">Rio de Janeiro</option>
		                  <option value="RN">Rio Grande do Norte</option>
		                  <option value="RO">Rondônia</option>
		                  <option value="RR">Roraima</option>
		                  <option value="RS">Rio Grande do Sul</option>
		                  <option value="SC">Santa Catarina</option>
		                  <option value="SE">Sergipe</option>
		                  <option value="SP">São Paulo</option>
		                  <option value="TO">Tocantins</option>
					</select>
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('cidade')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-map-marker"></i></span>
					<select name="cidade" id="cidade">
						<option value="<?php echo $instalador->cidade?>"><?php echo $instalador->cidade?></option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<!-- coluna direita -->
	<div class="span4">
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('telefone')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-phone"></i></span>
					<input type="text" class="telefone" name="telefone" value="<?php echo $instalador->telefone ?>" class="input-medium" />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('celular')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-mobile"></i></span>
					<input type="text" class="celular" name="celular" value="<?php echo $instalador->celular ?>" class="input-medium" required/>
				</div>
			</div>
		</div>
		<div class="control-group">
            <label class="control-label"><?php echo lang('banco')?></label>
        	<div class="controls">
            	<div class="input-prepend banco">
          			<span class="add-on"><i class="fa fa-bank"></i></span>
		            <select type="text" id="banco" name="banco" placeholder="<?php echo lang('banco')?>" class="input span10">
		              	<option value="001">Banco do Brasil</option>
		              	<option value="004">Banco do Nordeste</option>
		              	<option value="237">Bradesco</option>
		              	<option value="104">Caixa Econômica Federal</option>
		              	<option value="341">Itaú</option>
		              	<option value="008">Santander</option>
						<option value="133">Cresol Cooperativa Central</option>
		            </select>
          		</div>
        	</div>
      	</div>
      	<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('agencia')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-bank"></i></span>
					<input type="text" name="agencia" value="<?php echo $instalador->agencia ?>" class="input-mini" />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('conta')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-credit-card"></i></span>
					<input type="text" name="agencia" value="<?php echo $instalador->conta ?>" class="input-mini" />
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo lang('vi')?></label>
            <div class="controls">
            	<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-usd"></i></span>
                    <input type="text" class="input-mini money2" title="Se você colocar um valor abaixo da média, terá mais chances de ser contratado para o serviço" 
                    	id="vInstalacao" name="valor_instalacao" value="<?php echo $instalador->valor_instalacao?>" placeholder="<?php echo lang('r')?>">
                </div>
                <span class="label label-important"> <?php echo lang('media')?><?php echo round($valores[0]->instalacao, 2);?></span>
           	</div>
        </div>
        <div class="control-group">
			<label class="control-label"><?php echo lang('vm')?></label>
            <div class="controls">
            	<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-usd"></i></span>
                    <input type="text" class="input-mini money2" title="Se você colocar um valor abaixo da média, terá mais chances de ser contratado para o serviço" 
                    	id="vInstalacao" name="valor_instalacao" value="<?php echo $instalador->valor_manutencao?>" placeholder="<?php echo lang('r')?>">
                </div>
                <span class="label label-important"> <?php echo lang('media')?><?php echo round($valores[0]->manutencao, 2);?></span>
           	</div>
        </div>
        <div class="control-group">
			<label class="control-label"><?php echo lang('vr')?></label>
            <div class="controls">
            	<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-usd"></i></span>
                    <input type="text" class="input-mini money2" title="Se você colocar um valor abaixo da média, terá mais chances de ser contratado para o serviço" 
                    	id="vInstalacao" name="valor_retirada" value="<?php echo $instalador->valor_retirada?>" placeholder="<?php echo lang('r')?>">
                </div>
                <span class="label label-important"> <?php echo lang('media')?><?php echo round($valores[0]->retirada, 2);?></span>
           	</div>
        </div>
		<div class="control-group">
			<label class="control-label"><?php echo lang('vd')?></label>
            <div class="controls">
            	<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-usd"></i></span>
                    <input type="text" class="input-mini money2" title="<?php echo lang('fr4')?>" 
                    	id="vDeslocamento" name="valor_desloc_km" value="<?php echo $instalador->valor_desloc_km?>" placeholder="<?php echo lang('r')?>">
                </div>
                <span class="label label-important"> <?php echo lang('media')?><?php echo round($valores[0]->desloc, 2);?></span>
           	</div>
        </div>
	</div>
	<?php endif; ?>	
	<div class="clearfix"></div>
	<div class="form-actions">
	  <button type="submit" class="btn btn-primary bt-salvar-veiculo" id="bt-atualizar-veiculo" class="bt-atualizar-veiculo" data-tipo="atualizar"><?php echo lang('salvar')?></button>
	  <button type="reset" class="btn"><?php echo lang('cancelar')?></button>
	</div>

<?php echo form_close()?>
</div>
<hr>
<script src="<?php echo base_url('media') ?>/js/jquery-mask.js"></script>
<script type="text/javascript">
	$(document).ready(function(e) {

		$('.money2').mask("#.##0.00", {reverse: true});
		$('.cep').mask("000000-000", {reverse: true});
		$('.cpff').mask("00000000000", {reverse: true});
		$('.telefone').mask("00000000000", {reverse: true});
		$('.celular').mask("00000000000", {reverse: true});
		$('#numero').mask("000000");

		var estado = '<?php echo $instalador->estado; ?>';
		$('.estado option[value="'+estado+'"]').prop("selected", true);

		var banco = '<?php echo $instalador->banco; ?>';
		$('.banco option[value="'+banco+'"]').prop("selected", true);

		$('.form-horizontal').delegate('select', 'focus', function(e) {
	        e.preventDefault();
	        var options = '<option value=""></option>';
	        estado = $("#estado").val();
	        $.ajax({
	          type: "POST",
	          data: {sigla:estado},  
	          url: "../instaladores/get_cidades",
	          dataType: "json",
	          beforeSend:function(){
	            $('#cidade').html('<option>Carregando...</option>');
	          },
	          success: function(data){
	            $.each(data, function(index, i) {
	              options += '<option value="' + i.nome + '">' + i.nome + '</option>';
	            });
	            $('#cidade').empty().html(options).show(); 
	          },
	        });
      	});
	});
</script>