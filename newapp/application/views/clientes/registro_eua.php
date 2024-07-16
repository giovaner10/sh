<style>.mostra-campo{display: none}</style>
<h3>Registro de Clientes<small></small></h3>
<form action="<?php echo site_url('cadastros/cadastrar_cliente') ?>" method="post" class="form-horizontal formulario" id="clientes">
<div class="well well-small botoes-acao">
	<div class="btn-group">
		<a href="<?php echo site_url('clientes') ?>" class="btn btn-info voltar" title="Voltar"><i class="icon-arrow-left icon-white"></i></a>
	</div>
	<div class="btn-group pull-right">
		<button type="submit" class="salvar btn btn-primary" title="Salva os dados preenchidos"><i class="icon-download-alt icon-white"></i> Salvar</button>
		<button type="button" class="limpar btn btn-primary" data-form="#clientes" onClick="document.getElementById('clientes').reset();return false" title="Restaura as informações iniciais"><i class="icon-leaf icon-white"></i> Limpar</button>
	</div>
</div>
<div class="resultado span6" style="float: none;"></div>
	<div class="row-fluid">
		<div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="tab1 active"><a href="#tab1" data-toggle="tab">Dados</a></li>
				<li class="tab2"><a href="#tab2" data-toggle="tab">Cartões</a></li>
				<li class="tab3"><a href="#tab3" data-toggle="tab">Endereços</a></li>
				<li class="tab4"><a href="#tab4" data-toggle="tab">Contatos</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab1">
					<div class="row dados">
						<div class="span8">
							<fieldset class="cliente" id="0">
								<div class="control-group">
									<label class="control-label">Pessoa:<sup>*</sup></label>
									<div class="controls controls-row">
										<label class="radio inline"><input type="radio" name="cliente[pessoa]" value="1" checked> Física</label>
										<label class="radio inline"><input type="radio" name="cliente[pessoa]" value="0"> Jurídica</label>
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Nome:<sup>*</sup></label>
									<div class="controls controls-row">
										<input type="text" name="cliente[nome]" value="" class="span12 upper">
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="fisica">
                                    <div class="control-group br">
                                        <label class="control-label">Tax Id:</label>
                                        <div class="controls controls-row">
                                            <input type="text" name="cliente[cpf]" value="" class="span12 cpf">
                                            <span class="help help-block"></span>
                                        </div>
                                    </div>
								</div>
								<div class="juridica">
									<div class="control-group">
										<label class="control-label">Trading name:<sup>*</sup></label>
										<div class="controls controls-row">
											<input type="text" name="cliente[razao_social]" value="" class="span12 upper">
											<span class="help help-block"></span>
										</div>
									</div>
									<div class="control-group br">
										<label class="control-label">EIN:<sup>*</sup></label>
										<div class="controls controls-row">
											<input type="text" name="cliente[cnpj]" value="" class="span12 cnpj">
											<span class="help help-block"></span>
										</div>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Empresa:</label>
									<div class="controls controls-row">
										<select id="empresa" name="cliente[informacoes]" class="span12 upper" required>
											<option value="">Selecione a Empresa</option>
											<option value="TRACKER">SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA - ME</option>
											<option value="SIMM2M">SIMM2M</option>
											<option value="NORIO">SIGA ME - NORIO MOMOI EPP</option>
                                            <option value="EUA">SHOW TECHNOLOGY EUA</option>
										</select>
										<span class="help help-block"></span>
										<span>Informações obrigatório para clientes SIM (CLIENTE SIM2M)</span>
									</div>
								</div>
								<div id="consultor" class="mostra-campo control-group">
									<label class="control-label">Consultor:</label>
									<div class="controls controls-row">
										<select name="cliente[consultor]" class="span12 upper">
											<option></option>
											<?php foreach ($consultores as $consultor): ?>
												<option value="<?php echo $consultor->id ?>">
													<?php echo $consultor->nome ?>
												</option>
											<?php endforeach; ?>
										</select>
										<span>Informações obrigatório para clientes SIM</span>
									</div>
								</div>
                                <div id="vendedor" class="control-group">
                                    <label class="control-label">Vendedor:</label>
                                    <div class="controls controls-row">
                                        <select name="cliente[id_vendedor]" class="span12 upper">
                                            <option></option>
                                            <?php foreach ($consultores as $consultor): ?>
                                                <option value="<?php echo $consultor->id ?>">
                                                    <?php echo $consultor->nome ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
								</div>
								<div class="nota_fiscal">
									<div class="control-group br">
										<label class="control-label">Nota Fiscal:</label>
										<div class="controls controls-row">
											<input type="text" name="cliente[cod_servico]" value="" class="span3 adt" placeholder="Código do serviço">
											<input type="text" name="cliente[descriminacao_servico]" value="" class="span9 adt" maxlength="100" placeholder="Descrição do serviço">
											<span class="help help-block"></span>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="tab2">
					<div class="row cartoes">
						<div class="span8 recipiente">
							<div class="control-group">
								<label class="control-label">Cartão:</label>
								<div class="controls controls-row">
									<a href="#" title="Adicionar" class="btn adicionar" data-campos="cartao"><i class="icon-plus-sign"></i> Adicionar</a>
								</div>
							</div>
							<fieldset class="cartao" id="0">
								<div class="control-group">
									<div class="controls controls-row">
										<input type="text" name="cartao[0][numero]" value="" class="span6 numero_cartao" placeholder="Número">
										<input type="text" name="cartao[0][bandeira]" value="" class="span6 bandeira_cartao" placeholder="Bandeira">
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="control-group">
									<div class="controls controls-row">
										<input type="text" name="cartao[0][vencimento]" value="" class="span6 vencimento_cartao" placeholder="Vencimento">
										<input type="text" name="cartao[0][codigo]" value="" class="span6 codigo_cartao" placeholder="Código">
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="control-group">
									<div class="controls controls-row">
										<input type="text" name="cartao[0][nome]" value="" class="span12 upper" placeholder="Nome">
										<span class="help help-block"></span>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="tab3">
					<div class="row enderecos">
						<div class="span8 recipiente">
							<div class="control-group">
								<label class="control-label">Endereço:</label>
								<div class="controls controls-row">
									<a href="#" title="Adicionar" class="btn adicionar" data-campos="endereco"><i class="icon-plus-sign"></i> Adicionar</a>
								</div>
							</div>
							<fieldset class="endereco" id="0">
								<div class="control-group">
									<div class="controls controls-row">
										<input type="text" name="endereco[0][latitude]" value="" class="span6" placeholder="Latitude">
										<input type="text" name="endereco[0][longitude]" value="" class="span6" placeholder="Longitude">
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="control-group br">
									<div class="controls controls-row">
										<input type="text" name="endereco[0][cep]" value="" class="span12" placeholder="Zip code">
										<span class="help help-block"></span>
									</div>
								</div>
                                <div class="control-group">
                                    <div class="controls controls-row">
                                        <input type="text" name="endereco[0][pais]" value="EUA" maxlength="3" class="span12" placeholder="País">
                                        <span class="help help-block"></span>
                                    </div>
                                </div>

								<div class="control-group">
									<div class="controls controls-row">
										<input type="text" name="endereco[0][rua]" value="" class="span9 upper" placeholder="Rua">
										<input type="text" name="endereco[0][numero]" value="" class="span3 upper" placeholder="Número">
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="control-group">
									<div class="controls controls-row">
										<select name="endereco[0][uf]" class="span4">
											<option value="">UF</option>
											<option value="AL">AL</option>
											<option value="AR">AR</option>
											<option value="AZ">AZ</option>
											<option value="CA">CA</option>
											<option value="CO">CO</option>
											<option value="CT">CT</option>
											<option value="DE">DE</option>
											<option value="FL">FL</option>
											<option value="GA">GA</option>
											<option value="HI">HI</option>
											<option value="IA">IA</option>
											<option value="ID">ID</option>
											<option value="IL">IL</option>
											<option value="IN">IN</option>
											<option value="KS">KS</option>
											<option value="KY">KY</option>
											<option value="LA">LA</option>
											<option value="MA">MA</option>
											<option value="MD">MD</option>
											<option value="ME">ME</option>
											<option value="MI">MI</option>
											<option value="MN">MN</option>
											<option value="MO">MO</option>
											<option value="MS">MS</option>
											<option value="MT">MT</option>
											<option value="NC">NC</option>
											<option value="ND">ND</option>
                                            <option value="NE">NE</option>
                                            <option value="NH">NH</option>
                                            <option value="NJ">NJ</option>
                                            <option value="NM">NM</option>
                                            <option value="NV">NV</option>
                                            <option value="NY">NY</option>
                                            <option value="OH">OH</option>
                                            <option value="OK">OK</option>
                                            <option value="OR">OR</option>
                                            <option value="PA">PA</option>
                                            <option value="RI">RI</option>
                                            <option value="SC">SC</option>
                                            <option value="SD">SD</option>
                                            <option value="TN">TN</option>
                                            <option value="TX">TX</option>
                                            <option value="UT">UT</option>
                                            <option value="VA">VA</option>
                                            <option value="VT">VT</option>
                                            <option value="WA">WA</option>
                                            <option value="WI">WI</option>
                                            <option value="WA">WA</option>
                                            <option value="WI">WI</option>
                                            <option value="WV">WV</option>
                                            <option value="WY">WY</option>
										</select>
										<input type="text" name="endereco[0][cidade]" value="" class="span8 upper" placeholder="Cidade">
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="control-group">
									<div class="controls controls-row">
										<input type="text" name="endereco[0][complemento]" value="" class="span12 upper" placeholder="Complemento">
										<span class="help help-block"></span>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab4">
					<div class="row">
						<div class="span8 recipiente">
							<div class="control-group">
								<div class="controls controls-row">
									<div class="alert alert-info">
									  <button type="button" class="close" data-dismiss="alert">&times;</button>
									  Para envio de faturas favor cadastrar e-mail <i><strong>Setor Financeiro.</strong></i>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row emails">
						<div class="span8 recipiente">
							<div class="control-group">
								<label class="control-label">E-mail:</label>
								<div class="controls controls-row">
									<a href="#" title="Adicionar" class="btn adicionar" data-campos="email"><i class="icon-plus-sign"></i> Adicionar</a>
								</div>
							</div>

							<fieldset class="email" id="0">
								<div class="control-group">
									<div class="controls controls-row">
										<input type="text" name="email[0][emails]" value="" class="span5 lower" placeholder="E-mail">
										<select name="email[0][setor]" class="span3">
											<option value="">Setor</option>
											<option value="0">Financeiro</option>
											<option value="1">Diretoria</option>
											<option value="2">Suporte</option>
											<option value="3">Pessoal</option>
										</select>
										<input type="text" name="email[0][observacao]" value="" class="span4 upper" placeholder="Observação">
										<span class="help help-block"></span>
									</div>
								</div>
							</fieldset>
						</div>
					</div>

					<div class="row telefones br">
						<div class="span8 recipiente">
							<div class="control-group">
								<label class="control-label">Phone number:</label>
								<div class="controls controls-row">
									<a href="#" title="Adicionar" class="btn adicionar" data-campos="telefone"><i class="icon-plus-sign"></i> Adicionar</a>
								</div>
							</div>
							<fieldset class="telefone" id="0">
								<div class="control-group">
									<div class="controls controls-row">
										<input type="text" name="telefone[0][ddd]" value="" class="span2 ddd" placeholder="Area code">
										<input type="text" name="telefone[0][numero]" value="" class="span3 fone" placeholder="Phone">
										<select name="telefone[0][setor]" class="span3">
											<option value="">Setor</option>
											<option value="0">Financeiro</option>
											<option value="1">Diretoria</option>
											<option value="2">Suporte</option>
											<option value="3">Pessoal</option>
										</select>
										<input type="text" name="telefone[0][observacao]" value="" class="span4 upper" placeholder="Observação">
										<span class="help help-block"></span>
									</div>
								</div>
							</fieldset>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">
    $('#empresa').change(function() {
        var empresa = $('#empresa option:selected').text();
        if (empresa === "SIMM2M"){
            $('#consultor').removeClass('mostra-campo');
        }else{
            $('#consultor').addClass('mostra-campo');
        }

    });

	$(function(){

		// exibe campos para cadastro de pessoa física ou jurídica conforme a pessoa selecionada
		if($('input[name="cliente[pessoa]"]:checked').val() == '1'){
			$('.fisica').show();
			$('.juridica').hide();
		}

		if($('input[name="cliente[pessoa]"]:checked').val() == '0'){
			$('.fisica').hide();
			$('.juridica').show();
		}

		$('input[name="cliente[pessoa]"]').on('click', function(){
			if($(this).val() == '1'){
				$('.fisica').show();
				$('.juridica').hide();
			}else{
				$('.fisica').hide();
				$('.juridica').show();
			}
		});

		// adiciona endereco
		$('.adicionar').on('click', function(e){
			e.preventDefault();

			if($(this).data('campos') == 'endereco'){

				var endereco_indice = <?php echo isset($id_endereco) ? $id_endereco + 1 : 1 ?>;

				var template = [
					'<fieldset class="endereco" id="',endereco_indice,'">',
						'<div class="control-group">',
							'<label class="control-label"><a href="#" title="Remover" class="remover"><i class="icon-minus-sign icon-red"></i></a></label>',
							'<div class="controls controls-row">',
								'<input type="text" name="endereco[',endereco_indice,'][latitude]" value="" class="span6" placeholder="Latitude">',
								'<input type="text" name="endereco[',endereco_indice,'][longitude]" value="" class="span6" placeholder="Longitude">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
						'<div class="control-group">',
							'<div class="controls controls-row">',
								'<input type="text" name="endereco[',endereco_indice,'][cep]" value="" class="span12" placeholder="CEP">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
                        '<div class="control-group">',
                            '<div class="controls controls-row">',
                                '<input type="text" name="endereco[',endereco_indice,'][pais]" value="EUA" maxlength="3" class="span12" placeholder="País">',
                                '<span class="help help-block"></span>',
                            '</div>',
                        '</div>',
						'<div class="control-group">',
							'<div class="controls controls-row">',
								'<input type="text" name="endereco[',endereco_indice,'][rua]" value="" class="span9 upper" placeholder="Rua">',
								'<input type="text" name="endereco[',endereco_indice,'][numero]" value="" class="span3 upper" placeholder="Número">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
						'<div class="control-group">',
							'<div class="controls controls-row">',
								'<select name="endereco[',endereco_indice,'][uf]" class="span4">',
                                    '<option value="">UF</option>',
                                    '<option value="AL">AL</option>',
                                    '<option value="AR">AR</option>',
                                    '<option value="AZ">AZ</option>',
                                    '<option value="CA">CA</option>',
                                    '<option value="CO">CO</option>',
                                    '<option value="CT">CT</option>',
                                    '<option value="DE">DE</option>',
                                    '<option value="FL">FL</option>',
                                    '<option value="GA">GA</option>',
                                    '<option value="HI">HI</option>',
                                    '<option value="IA">IA</option>',
                                    '<option value="ID">ID</option>',
                                    '<option value="IL">IL</option>',
                                    '<option value="IN">IN</option>',
                                    '<option value="KS">KS</option>',
                                    '<option value="KY">KY</option>',
                                    '<option value="LA">LA</option>',
                                    '<option value="MA">MA</option>',
                                    '<option value="MD">MD</option>',
                                    '<option value="ME">ME</option>',
                                    '<option value="MI">MI</option>',
                                    '<option value="MN">MN</option>',
                                    '<option value="MO">MO</option>',
                                    '<option value="MS">MS</option>',
                                    '<option value="MT">MT</option>',
                                    '<option value="NC">NC</option>',
                                    '<option value="ND">ND</option>',
                                    '<option value="NE">NE</option>',
                                    '<option value="NH">NH</option>',
                                    '<option value="NJ">NJ</option>',
                                    '<option value="NM">NM</option>',
                                    '<option value="NV">NV</option>',
                                    '<option value="NY">NY</option>',
                                    '<option value="OH">OH</option>',
                                    '<option value="OK">OK</option>',
                                    '<option value="OR">OR</option>',
                                    '<option value="PA">PA</option>',
                                    '<option value="RI">RI</option>',
                                    '<option value="SC">SC</option>',
                                    '<option value="SD">SD</option>',
                                    '<option value="TN">TN</option>',
                                    '<option value="TX">TX</option>',
                                    '<option value="UT">UT</option>',
                                    '<option value="VA">VA</option>',
                                    '<option value="VT">VT</option>',
                                    '<option value="WA">WA</option>',
                                    '<option value="WI">WI</option>',
                                    '<option value="WA">WA</option>',
                                    '<option value="WI">WI</option>',
                                    '<option value="WV">WV</option>',
                                    '<option value="WY">WY</option>',
								'</select>',
								'<input type="text" name="endereco[',endereco_indice,'][cidade]" value="" class="span8 upper" placeholder="Cidade">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
						'<div class="control-group">',
							'<div class="controls controls-row">',
								'<input type="text" name="endereco[',endereco_indice,'][complemento]" value="" class="span12 upper" placeholder="Complemento">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
					'</fieldset>',
				].join('');

				$('.enderecos').find('.recipiente').append(template);

				endereco_indice++;

			// adiciona email
			}else if($(this).data('campos') == 'email'){

				var email_indice = <?php echo isset($id_email) ? $id_email + 1 : 1 ?>;

				var template = [
					'<fieldset class="email"  id="',email_indice,'">',
						'<div class="control-group">',
							'<label class="control-label"><a href="#" title="Remover" class="remover"><i class="icon-minus-sign icon-red"></i></a></label>',
							'<div class="controls controls-row">',
								'<input type="text" name="email[',email_indice,'][emails]" value="" class="span5 lower">',
								'<select name="email[',email_indice,'][setor]" class="span3">',
									'<option value="">Setor</option>',
									'<option value="0">Financeiro</option>',
									'<option value="1">Diretoria</option>',
									'<option value="2">Suporte</option>',
									'<option value="2">Pessoal</option>',
								'</select>',
								'<input type="text" name="email[',email_indice,'][observacao]" value="" class="span4 upper" placeholder="observação">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
					'</fieldset>'
				].join('');

				$('.emails').find('.recipiente').append(template);

				email_indice++;

			// adiciona telefone
			}else if($(this).data('campos') == 'telefone'){

				var telefone_indice = <?php echo isset($id_telefone) ? $id_telefone + 1 : 1 ?>;

				var template = [
					'<fieldset class="telefone"  id="',telefone_indice,'">',
						'<div class="control-group">',
							'<label class="control-label"><a href="#" title="Remover" class="remover"><i class="icon-minus-sign icon-red"></i></a></label>',
							'<div class="controls controls-row" data="setor" data-numero="observacao">',
								'<input type="text" name="telefone[',telefone_indice,'][ddd]" value="" class="span2 ddd" placeholder="Area code">',
								'<input type="text" name="telefone[',telefone_indice,'][numero]" value="" class="span3 fone" placeholder="Número">',
								'<select name="telefone[',telefone_indice,'][setor]" class="span3">',
									'<option value="0">Financeiro</option>',
									'<option value="1">Diretoria</option>',
									'<option value="2">Suporte</option>',
									'<option value="2">Pessoal</option>',
								'</select>',
								'<input type="text" name="telefone[',telefone_indice,'][observacao]" value="" class="span4 upper" placeholder="observação">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
					'</fieldset>',
				].join('');

				$('.telefones').find('.recipiente').append(template);

				telefone_indice++;

			// adiciona cartao
			}else if($(this).data('campos') == 'cartao'){

				var cartao_indice = <?php echo isset($id_cartao) ? $id_cartao + 1 : 1 ?>;

				var template = [
					'<fieldset class="cartao" id="',cartao_indice,'">',
						'<div class="control-group">',
							'<label class="control-label"><a href="#" title="Remover" class="remover"><i class="icon-minus-sign icon-red"></i></a></label>',
							'<div class="controls controls-row">',
								'<input type="text" name="cartao[',cartao_indice,'][numero]" value="" class="span6 numero_cartao" placeholder="Número">',
								'<input type="text" name="cartao[',cartao_indice,'][bandeira]" value="" class="span6 bandeira_cartao" placeholder="Bandeira">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
						'<div class="control-group">',
							'<div class="controls controls-row">',
								'<input type="text" name="cartao[',cartao_indice,'][vencimento]" value="" class="span6 vencimento_cartao" placeholder="Vencimento">',
								'<input type="text" name="cartao[',cartao_indice,'][codigo]" value="" class="span6 codigo_cartao" placeholder="Código">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
						'<div class="control-group">',
							'<div class="controls controls-row">',
								'<input type="text" name="cartao[',cartao_indice,'][nome]" value="" class="span12 upper" placeholder="Nome">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
					'</fieldset>',
				].join('');

				$('.cartoes').find('.recipiente').append(template);

				cartao_indice++;
			}
		});

		// remove um fiedset de campos dependendo do botão clicado dentro dele
		$(document).on('click', '.remover', function(e){
			e.preventDefault();

			$(this).closest('fieldset').remove();
		});

		// apaga um fieldset de campos dependendo do botão clicado dentro dele
		$(document).on('click', '.apagar', function(e){
			e.preventDefault();

			$(this).apagar({
				url: '<?php echo site_url('administrador/clientes/apagar') ?>'
			});
		})

	});

	$(document).on('focus', '.cpf', function(){ $('.cpf').mask('999-99-9999'); });
	$(document).on('focus', '.cnpj', function(){ $('.cnpj').mask('99-9999999'); });
	$(document).on('focus', '.ie', function(){ $('.ie').mask('999-99-9999'); });
	$(document).on('focus', '.numero_cartao', function(){ $('.numero_cartao').mask('9999-9999-9999-9999'); });
	$(document).on('focus', '.codigo_cartao', function(){ $('.codigo_cartao').mask('999'); });
	$(document).on('focus', '.vencimento_cartao', function(){ $('.vencimento_cartao').mask('99/99'); });
	$(document).on('focus', '.data', function(){ $('.data').mask('11/11/1111'); });
	$(document).on('focus', '.tempo', function(){ $('.tempo').mask('00:00:00'); });
	$(document).on('focus', '.datatempo', function(){ $('.datatempo').mask('99/99/9999 00:00:00'); });
	$(document).on('focus', '.ddd', function(){ $('.ddd').mask('999'); });
	$(document).on('focus', '.fone', function(){ $('.fone').mask('9999-9999'); });

	$('.cnpj').mask('99-9999999');
	$('.ie').mask('999-99-9999');
	$('.numero_cartao').mask('9999-9999-9999-9999');
	$('.codigo_cartao').mask('999');
	$('.vencimento_cartao').mask('99/99');
	$('.tempo').mask('00:00:00');
	$('.datatempo').mask('99/99/9999 00:00:00');
	$('.ddd').mask('999');
	$('.fone').mask('999-9999');
	$('.cpf').mask('999.999.999-99');
	$('.data').mask('99/99/9999');
    //-----------------------------------------//
    $('.ein').mask('99-9999999');

</script>

<script type="text/javascript">


$(document).ready(function(){

	$(".resultado").hide();
	$("#clientes").ajaxForm({
		target: '.resultado',
		dataType: 'json',
		success: function(retorno){
			$(".resultado").html(retorno.mensagem);
			$(".resultado").show();
		}

	});

});


</script>
