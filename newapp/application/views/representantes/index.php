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
	<?php if (isset($representante) && count($representante)): ?>
		<input type="hidden" name="id" value="<?php echo $representante->id?>">
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('nome')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="icon-user"></i></span>
					<input type="text" name="nome" value="<?php echo $representante->nome ?>" class="input-large" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('sobrenome')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="icon-user"></i></span>
					<input type="text" name="sobrenome" value="<?php echo $representante->sobrenome ?>" class="input-large" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('cpf')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="icon-user"></i></span>
					<input type="text" class="cpff" name="cpf" value="<?php echo $representante->cpf ?>" class="input-large" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('email')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="icon-envelope"></i></span>
					<input type="text" name="email" value="<?php echo $representante->email ?>" class="input-large" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('emailshow')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="icon-envelope"></i></span>
					<input type="text" name="emailshow" value="<?php echo $representante->emailshow ?>" class="input-large" readonly />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('endereco')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-home"></i></span>
					<input type="text" name="endereco" value="<?php echo $representante->endereco ?>" class="input-large" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('numero')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-home"></i></span>
					<input type="text" id="numero" name="numero" value="<?php echo $representante->numero ?>" class="input-mini" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('bairro')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-home"></i></span>
					<input type="text" name="bairro" value="<?php echo $representante->bairro ?>" class="input-medium" required />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('cep')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-envelope-o"></i></span>
					<input type="text" class="cep" name="cep" value="<?php echo $representante->cep ?>" class="input-small" required />
				</div>
			</div>
		</div>

       <!--div class="control-group">
            <label class="control-label"><?php //echo lang('pais')?></label>
	            <div class="controls">
					<div class="input-prepend pais">
		            <span class="add-on"><i class="fa fa-map-marker"></i></span>
						<select name="pais" id="pais">
		                  <option value=""><?php //echo lang('espais')?></option>
		                  <option value="BRA"><?php //echo lang('bra')?></option>
		                  <option value="USA"><?php //echo lang('usa')?></option>
		                </select>
		            </div>
	            </div>
        </div-->
		
        <?php if ($representante->pais == 'USA'){ ?>
          <div class="control-group">
	        <label class="control-label"><?php echo lang('estado')?></label>
	 		<div class="controls">
				<div class="input-prepend estado">
                <span class="add-on"><i class="fa fa-map-marker"></i></span>
				<select name="estado" id="estado">
	                  <option value=""><?php echo lang('esestado')?></option>
	                  <option value="AK">Alaska</option>
	                  <option value="AL">Alabama</option>
	                  <option value="AR">Arkansas</option>
	                  <option value="AZ">Arizona</option>
	                  <option value="CA">California</option>
	                  <option value="CO">Colorado</option>
	                  <option value="CT">Connecticut</option>
	                  <option value="DC">District of Columbia</option>
	                  <option value="DE">Delaware</option>
	                  <option value="FL">Florida</option>
	                  <option value="GA">Georgia</option>
	                  <option value="HI">Hawaii</option>
	                  <option value="IA">Iowa</option>
	                  <option value="ID">Idaho</option>
	                  <option value="IL">Illinois</option>
	                  <option value="IN">Indiana</option>
	                  <option value="KS">Kansas</option>
	                  <option value="KY">Kentucky</option>
	                  <option value="LA">Louisiana</option>
	                  <option value="MA">Massachusetts</option>
	                  <option value="MD">Maryland</option>
	                  <option value="ME">Maine</option>
	                  <option value="MI">Michigan</option>
	                  <option value="MN">Minnesota</option>
	                  <option value="MO">Missouri</option>
	                  <option value="MS">Mississippi</option>
	                  <option value="MT">Montana</option>
	                  <option value="NC">North Carolina</option>
	                  <option value="ND">North Dakota</option>
	                  <option value="NE">Nebraska</option>
	                  <option value="NH">New Hampshire</option>
	                  <option value="NJ">New Jersey</option>
	                  <option value="NM">New Mexico</option>
	                  <option value="NV">Nevada</option>
	                  <option value="NY">New York</option>
	                  <option value="OH">Ohio</option>
	                  <option value="OK">Oklahoma</option>
	                  <option value="OR">Oregon</option>
	                  <option value="PA">Pennsylvania</option>
	                  <option value="RI">Rhode Island</option>
	                  <option value="SC">South Carolina</option>
	                  <option value="SD">South Dakota</option>
	                  <option value="TN">Tennessee</option>
	                  <option value="TX">Texas</option>
	                  <option value="UT">Utah</option>
	                  <option value="VA">Virginia</option>
	                  <option value="VT">Vermont</option>
	                  <option value="WA">Washington</option>
	                  <option value="WI">Wisconsin</option>
	                  <option value="WV">West Virginia</option>
	                  <option value="WY">Wyoming</option>
                </select>
              </div>
            </div>
          </div>

        <?php }else{ ?>

          <div class="control-group">
        	<label class="control-label"><?php echo lang('estado')?></label>
     		<div class="controls">
				<div class="input-prepend estado">
                <span class="add-on"><i class="fa fa-map-marker"></i></span>
				<select name="estado" id="estado">
	                  <option value=""><?php echo lang('esestado')?></option>
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
        <?php } ?>
	    
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('cidade')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-map-marker"></i></span>
					<select name="cidade" id="cidade">
						<option value="<?php echo $representante->cidade?>"><?php echo $representante->cidade?></option>
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
					<input type="text" class="telefone" name="telefone" value="<?php echo $representante->telefone ?>" class="input-medium" />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('celular')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-mobile"></i></span>
					<input type="text" class="celular" name="celular" value="<?php echo $representante->celular ?>" class="input-medium" required/>
				</div>
			</div>
		</div>
		<div class="control-group">
            <label class="control-label"><?php echo lang('banco')?></label>
        	<div class="controls">
            	<div class="input-prepend banco">
          			<span class="add-on"><i class="fa fa-bank"></i></span>
		            <select type="text" id="banco" name="banco" placeholder="Banco" class="input span10">
		              	<option value="001">Banco do Brasil</option>
		              	<option value="004">Banco do Nordeste</option>
		              	<option value="237">Bradesco</option>
		              	<option value="104">Caixa Econômica Federal</option>
		              	<option value="341">Itaú</option>
		              	<option value="008">Santander</option>
		              	<option value="121000248">Wells Fargo</option>
		            </select>
          		</div>
        	</div>
      	</div>
      	<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('agencia')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-bank"></i></span>
					<input type="text" name="agencia" value="<?php echo $representante->agencia ?>" class="input-mini" />
				</div>
			</div>
		</div>
		<div class="control-group" for="clientes">
			<label class="control-label" for="clientes"><?php echo lang('conta')?></label>
			<div class="controls">
				<div class="input-prepend">
                	<span class="add-on"><i class="fa fa-credit-card"></i></span>
					<input type="text" name="agencia" value="<?php echo $representante->conta ?>" class="input-mini" />
				</div>
			</div>
		</div>
		<!-- ---- -->

	</div>
	<?php endif; ?>	
	<div class="clearfix"></div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary bt-salvar-veiculo" class="bt-atualizar-veiculo" data-tipo="atualizar"><?php echo lang('salvar')?></button>
		<button type="reset" class="btn"><?php echo lang('cancelar')?></button>

		<a class="btn btn-primary" data-toggle="modal" href="<?php echo site_url('representantes/digitalizar/'.$representante->id)?>" 
						title="Digitalizar Documentos" data-target="#myModal_digitalizar" > 
			<i class="icon-folder-open icon-white"></i> <?php echo lang('arquivos')?>
		</a>
	</div>

<?php echo form_close()?>
	
</div>
<div id="myModal_digitalizar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" style="text-align: center;">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel"><?php echo lang('digitalizar')?></h3>
    </div>
    <div class="modal-body">	
    </div> 
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

		var pais = '<?php echo $representante->pais; ?>';
		$('.pais option[value="'+pais+'"]').prop("selected", true);

		var estado = '<?php echo $representante->estado; ?>';
		$('.estado option[value="'+estado+'"]').prop("selected", true);

		var banco = '<?php echo $representante->banco; ?>';
		$('.banco option[value="'+banco+'"]').prop("selected", true);

		$('.form-horizontal').delegate('select', 'focus', function(e) {
	        e.preventDefault();
	        var options = '<option value=""></option>';
	        //var pais = 'BRA';
	        estado = $("#estado").val();
	        
	        $.ajax({
	          type: "POST",
	          data: {sigla:estado, sigla2:pais},  
	          url: "../representantes/get_cidades",
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

  		$('#myModal_digitalizar').on('hidden', function(){
	    	$(this).data('modal', null);
		});

	});
</script>