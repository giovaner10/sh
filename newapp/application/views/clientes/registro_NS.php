<script type="text/javascript" src="<?=base_url('assets/js/jquery.dataTables.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/dataTables.material.min.js')?>"></script>
<link rel="stylesheet" href="<?=base_url('assets/css/dataTables.material.min.css')?>">

<style>
	.mostra-campo{
		display: none
	}

	select[readonly] {
		background: #eee;
		pointer-events: none;
		touch-action: none;
	}
</style>

<h3>Registro de Clientes<small></small></h3>
<form action="<?php echo site_url('cadastros/cadastrar_cliente') ?>" method="post" class="form-horizontal formulario" id="clientes">
<div class="well well-small botoes-acao">
	<div class="btn-group">
		<a href="<?php echo site_url('clientes') ?>" class="btn btn-info voltar" title="Voltar"><i class="fa fa-arrow-left"></i></a>
	</div>
	<div class="btn-group pull-right">
		<button type="submit" class="salvar btn btn-primary" title="Salva os dados preenchidos"><i class="fa fa-download" aria-hidden="true"></i> Salvar</button>
		<button type="button" class="limpar btn btn-primary" data-form="#clientes" onClick="document.getElementById('clientes').reset();return false" title="Restaura as informações iniciais"><i class="fa fa-leaf"></i> Limpar</button>
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
				<li class="tab5"><a href="#tab5" data-toggle="tab">Opções</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab1">
					<div class="row">
						<div class="col-sm-12">
							<fieldset class="col-sm-12" id="0">
								<br>
								<div class="form-group">
									<label class="control-label col-sm-1">Pessoa:</label>
									<div class="row">
										<label class="radioiinline"><input type="radio" name="cliente[pessoa]" value="1" checked> Física</label>
										<label class="radioiinline"><input type="radio" name="cliente[pessoa]" value="0"> Jurídica</label>
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Nome:</label>
									<div class="col-sm-6">
										<input type="text" name="cliente[nome]" id="nome" value="" class="form-control">
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="fisica">
									<div class="form-group">
										<label class="col-sm-2" style="text-align: right;">Identidade:</label>
										<div class="col-sm-3">
											<input type="text" name="cliente[rg]" value="" class="form-control rg" placeholder="Número" maxlength="20">
										</div>
										<div class="col-sm-3">
											<input type="text" name="cliente[rg_orgao]" value="" class="form-control" placeholder="Orgão Expedidor">
											<span class="help help-block"></span>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2">Data de Nascimento:</label>
										<div class="col-sm-6">
											<input type="date" name="cliente[data_nascimento]" value="" class="form-control">
											<span class="help help-block"></span>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2">CPF:</label>
										<div class="col-sm-6">
											<input type="text" name="cliente[cpf]" value="" class="form-control cpf">
											<span class="help help-block"></span>
										</div>
									</div>
								</div>
								<div class="juridica">
                                    <div class="form-group">
                                        <label class="control-label col-sm-2">CNPJ:</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cliente[cnpj]" id="cnpj" value="" class="form-control cnpj" placeholder="00.000.000/0000-00">
                                            <a class="btn btn-small btn-info" style="margin-left: 10px;" onclick="buscaCnpj()"><i class="fa fa-search"></i></a> <span class="text-info" style="font-size: smaller;">* Informações do banco de dados da Receita Federal</span>
											<span class="help help-block"></span>
										</div>
                                    </div>
                                    <div class="form-group">
										<label class="control-label col-sm-2">Razão Social:</label>
										<div class="col-sm-6">
											<input type="text" name="cliente[razao_social]" value="" id="razao_social" class="form-control">
											<span class="help help-block"></span>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2">Inscrição Estadual:</label>
										<div class="col-sm-6">
											<input type="text" name="cliente[ie]" value="" class="form-control ie">
											<span class="help help-block"></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Empresa:</label>
									<div class="col-sm-6">
										<select id="empresa" name="cliente[informacoes]" class="form-control" required>
											<?php if($this->auth->get_login_dados('funcao') != 'OMNILINK'): ?>
												<option value="">Selecione a Empresa</option>
												<option value="TRACKER">SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA - ME</option>
												<option value="SIMM2M">SIMM2M</option>
												<option value="NORIO">SIGA ME - NORIO MOMOI EPP</option>
	                                            <option value="EUA">SHOW TECHNOLOGY EUA</option>
	                                            <option value="OMNILINK">OMNILINK</option>
												<option value="SIGAMY">SIGAMY</option>
												<option value="SHOW_CURITIBA"> SHOW CURITIBA </option>
	                                        <?php else: ?>
	                                        	<option value="OMNILINK">OMNILINK</option>
	                                        <?php endif; ?>
											<option value="SEGURADORA">SEGURADORA</option>
										</select>
										<span class="help help-block"></span>
									</div>
								</div>
								<div id="consultor" class="mostra-campo form-group">
									<label class="control-label col-sm-2">Consultor:</label>
									<div class="col-sm-6">
										<select name="cliente[consultor]" class="form-control">
											<option></option>
											<?php foreach ($consultores as $consultor): ?>
												<option value="<?php echo $consultor->id ?>">
													<?php echo $consultor->nome ?>
												</option>
											<?php endforeach; ?>
										</select>
										<span>Informações obrigatório para clientes SIM</span>
										<span class="help help-block"></span>
									</div>
								</div>
                                <div id="vendedor" class="form-group">
                                    <label class="control-label col-sm-2">Vendedor:</label>
                                    <div class="col-sm-6">
                                        <select name="cliente[id_vendedor]" class="form-control" <?= $usuario ? 'readonly' : '' ?> >
                                            <option></option>
                                            <?php foreach ($consultores as $consultor): ?>
                                                <option value="<?php echo $consultor->id ?>" <?=  $usuario ? (($usuario->id == $consultor->id) ? 'selected' : '') : '' ?>>
                                                    <?php echo $consultor->nome ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
										<span class="help help-block"></span>
                                    </div>
								</div>
								<div class="nota_fiscal">
									<div class="form-group">
										<label class="col-sm-2" style="text-align: right;">Nota Fiscal:</label>
										<div class="col-sm-6">
											<input type="text" name="cliente[cod_servico]" value="" class="form-control camposNotaFiscal" placeholder="Código do serviço" maxlength="11">
											<span class="help help-block"></span>
											<input type="text" name="cliente[descriminacao_servico]" value="" class="form-control camposNotaFiscal" maxlength="100" placeholder="Descrição do serviço">
											<span class="help help-block"></span>
										</div>
									</div>
								</div>
								<div class="opentech">
									<div class="form-group">
										<label class="col-sm-2" style="text-align: right;">Opentech:</label>
										<div class="col-sm-6">
											<input type="checkbox" name="cliente[opentech]" class="checkbox">
											<span class="help help-block"></span>
										</div>
									</div>
								</div>
								<div class="form-group orgao">
									<label class="col-sm-2" style="text-align: right;">Orgão:</label>
									<div class="col-sm-6">
										<select name="cliente[orgao]" class="form-control adt">
											<option value="privado">Privado</option>
											<option value="publico">Público</option>
                                        </select>
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2" style="text-align: right;">GMT (+/- xx):</label>
									<div class="col-sm-6">
										<input type="text" name="cliente[gmt]" value="-3"
											class="gmt form-control" maxlength="3">
										<span class="help help-block"></span>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab2">
					<br>
					<div class="row cartoes">
						<div class="col-sm-12 recipiente">
							<div class="form-group">
								<label class="control-label col-sm-1 nao-obrigatorio">Cartão:</label>
								<div class="col-sm-6">
									<button type="button" class="btn btn-default adicionar" data-campos="cartao"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar</button>
									<span class="help help-block"></span>
								</div>
							</div>
							<fieldset class="col-sm-12 cartao" id="0">
								<div class="form-group">
									<div class="col-sm-3">
										<input type="text" name="cartao[0][numero]" value="" class="form-control numero_cartao" placeholder="Número">
									</div>
									<div class="col-sm-3">
										<input type="text" name="cartao[0][bandeira]" value="" class="form-control bandeira_cartao" placeholder="Bandeira">
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-3">
										<input type="text" name="cartao[0][vencimento]" value="" class="form-control vencimento_cartao" placeholder="Vencimento">
									</div>
									<div class="col-sm-3">
										<input type="text" name="cartao[0][codigo]" value="" class="form-control codigo_cartao" placeholder="Código">
										<span class="help help-block"></span>
									</div>
									
								</div>
								<div class="form-group">
									<div class="col-sm-6">
										<input type="text" name="cartao[0][nome]" value="" class="form-control" placeholder="Nome">
										<span class="help help-block"></span>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab3">
					<br>
					<div class="row enderecos">
						<div class="col-sm-12 recipiente">
							<div class="form-group">
								<label class="control-label col-sm-1">Endereço:</label>
								<div class="col-sm-6">
									<button type="button" class="btn btn-default adicionar" data-campos="endereco"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar</button>								
									<span class="help help-block"></span>
								</div>
							</div>
							<fieldset class="endereco" id="0">
								<div class="form-group">
									<div class="col-sm-3">
										<input type="text" name="endereco[0][latitude]" value="" class="form-control lat" placeholder="Latitude">
									</div>
									<div class="col-sm-3">
										<input type="text" name="endereco[0][longitude]" value="" class="form-control long" placeholder="Longitude">
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="form-group br">
									<div class="col-sm-6">
										<input type="text" id="cep" name="endereco[0][cep]" data-index="0" value="" class="form-control cep" placeholder="CEP" onChange="AtualizarCEP(this)">
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-3">
										<input type="text" id="rua" name="endereco[0][rua]" value="" class="form-control" placeholder="Rua">
									</div>
									<div class="col-sm-3">
										<input type="text" id="numero" name="endereco[0][numero]" value="" class="form-control" placeholder="Número">
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-6">
										<input type="text" id="bairro" name="endereco[0][bairro]" value="" class="form-control" placeholder="Bairro">
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-3">
										<select name="endereco[0][uf]" id="estado" class="form-control">
											<option value="">UF</option>
											<option value="AC">AC</option>
											<option value="AL">AL</option>
											<option value="AP">AP</option>
											<option value="AM">AM</option>
											<option value="BA">BA</option>
											<option value="CE">CE</option>
											<option value="DF">DF</option>
											<option value="ES">ES</option>
											<option value="GO">GO</option>
											<option value="MA">MA</option>
											<option value="MT">MT</option>
											<option value="MS">MS</option>
											<option value="MG">MG</option>
											<option value="PA">PA</option>
											<option value="PB">PB</option>
											<option value="PR">PR</option>
											<option value="PE">PE</option>
											<option value="PI">PI</option>
											<option value="RJ">RJ</option>
											<option value="RN">RN</option>
											<option value="RS">RS</option>
											<option value="RO">RO</option>
											<option value="RR">RR</option>
											<option value="SC">SC</option>
											<option value="SP">SP</option>
											<option value="SE">SE</option>
											<option value="TO">TO</option>
										</select>
									</div>
									<div class="col-sm-3">
										<input type="text" id="cidade" name="endereco[0][cidade]" value="" class="form-control" placeholder="Cidade">
										<span class="help help-block"></span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-6">
										<input type="text" name="endereco[0][complemento]" value="" class="form-control" placeholder="Complemento">
										<span class="help help-block"></span>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab4">
					<br>
					<div class="row">
						<div class="col-sm-12 recipiente">
							<div class="form-group">
								<div class="col-sm-6">
									<div class="alert alert-info">
									  <button type="button" class="close" data-dismiss="alert">&times;</button>
									  Para envio de faturas favor cadastrar e-mail <i><strong>Setor Financeiro.</strong></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row emails">
						<div class="col-sm-12 recipiente">
							<div class="form-group">
								<label class="control-label col-sm-1">E-mail:</label>
								<div class="col-sm-6">
									<button type="button" class="btn btn-default adicionar" data-campos="email"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar</button>
									<span class="help help-block"></span>
								</div>
							</div>
							<fieldset class="email" id="0">
								<div class="form-group">
									<div class="col-sm-2">
										<input type="text" name="email[0][emails]" value="" class="form-control lower" placeholder="E-mail">
									</div>
									<div class="col-sm-2">
										<select name="email[0][setor]" class="form-control">
											<option value="">Setor</option>
											<option value="0">Financeiro</option>
											<option value="1">Diretoria</option>
											<option value="2">Suporte</option>
											<option value="3">Pessoal</option>
										</select>
									</div>
									<div class="col-sm-2">
										<input type="text" id="email" name="email[0][observacao]" value="" class="form-control" placeholder="Observação">
										<span class="help help-block"></span>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
					<div class="row telefones br">
						<div class="col-sm-12 recipiente">
							<div class="form-group">
								<label class="control-label col-sm-1">Telefone:</label>
								<div class="col-sm-6">
									<button type="button" class="btn btn-default adicionar" data-campos="telefone"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar</button>
									<span class="help help-block"></span>
								</div>
							</div>
							<fieldset class="telefone" id="0">
								<div class="form-group">
									<div class="col-sm-2">
										<input type="text" id="ddd" name="telefone[0][ddd]" value="" class="form-control ddd" placeholder="DDD">
									</div>
									<div class="col-sm-2">
										<input type="text" id="tel" name="telefone[0][numero]" value="" class="form-control fone" placeholder="Número">
									</div>
									<div class="col-sm-2">
										<select name="telefone[0][setor]" class="form-control">
											<option value="">Setor</option>
											<option value="0">Financeiro</option>
											<option value="1">Diretoria</option>
											<option value="2">Suporte</option>
											<option value="3">Pessoal</option>
										</select>
									</div>
									<div class="col-sm-2">
										<input type="text" name="telefone[0][observacao]" value="" class="form-control upper" placeholder="Observação">
										<span class="help help-block"></span>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab5">
					<div class="col-sm-3">
						<h3>Relatórios</h3>
						<table id="tableRelatorio" class="table table-bordered responsive" width="100%">
							<thead>
								<tr>
									<th>#</th>
									<th>Descrição</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
					<div class="col-sm-3">
						<h3>Cadastros</h3>
						<table id="tableCadastro" class="table table-bordered responsive" width="100%">
							<thead>
								<tr>
									<th>#</th>
									<th>Descrição</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
					<div class="col-sm-3">
						<h3>Configurações</h3>
						<table id="tableConfiguracao" class="table table-bordered responsive" width="100%">
							<thead>
								<tr>
									<th>#</th>
									<th>Descrição</th>
								</tr>
							</thead>
							<tbody>								
							</tbody>
						</table>
					</div>
					<div class="col-sm-3">
						<h3>Atendimento</h3>
						<table id="tableAtendimento" class="table table-bordered responsive" width="100%">
							<thead>
								<tr>
									<th>#</th>
									<th>Descrição</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
	function AtualizarCEP (cepInput){
		cepInput = $(cepInput)
		let index = cepInput.attr('data-index')

        var cep = cepInput.val().replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				// $('[name="endereco['+index+'][latitude]"]').val(dadosRetorno.logradouro);
				// $('[name="endereco['+index+'][longitude]"]').val(dadosRetorno.logradouro);
				$('[name="endereco['+index+'][rua]"]').val(dadosRetorno.logradouro);
				// $('[name="endereco['+index+'][numero]"]').val(dadosRetorno.logradouro);
				$('[name="endereco['+index+'][bairro]"]').val(dadosRetorno.bairro);
				$('[name="endereco['+index+'][uf]"]').val(dadosRetorno.uf);
				$('[name="endereco['+index+'][cidade]"]').val(dadosRetorno.localidade);
				$('[name="endereco['+index+'][complemento]"]').val(dadosRetorno.complemento);
			}catch(ex){}
		});
	}
	
	$(document).ready( function () {
        $('#tableRelatorio').DataTable( {
			paging: false,
			info: false,
			scrollY:        '50vh',
        	scrollCollapse: true,
            "language": {
                "decimal":        "",
                "emptyTable":     "Nenhum registro encontrado",
                "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty":      "0 Registros",
                "infoFiltered":   "(filtered from _MAX_ total entries)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Exibir: _MENU_",
                "loadingRecords": "Carregando...",
                "processing":     "Processando...",
                "search":         "Pesquisar: ",
                "zeroRecords":    "Nenhum registro encontrado",
                "paginate": {
                    "first":      "Anterior",
                    "last":       "Próxima",
                    "next":       "Próxima",
                    "previous":   "Anterior"
                }
            }
		} );
		
		$('#tableConfiguracao').DataTable( {
			paging: false,
			info: false,
			scrollY:        '50vh',
        	scrollCollapse: true,
            "language": {
                "decimal":        "",
                "emptyTable":     "Nenhum registro encontrado",
                "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty":      "0 Registros",
                "infoFiltered":   "(filtered from _MAX_ total entries)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Exibir: _MENU_",
                "loadingRecords": "Carregando...",
                "processing":     "Processando...",
                "search":         "Pesquisar: ",
                "zeroRecords":    "Nenhum registro encontrado",
                "paginate": {
                    "first":      "Anterior",
                    "last":       "Próxima",
                    "next":       "Próxima",
                    "previous":   "Anterior"
                }
            }
		} );
		
		$('#tableCadastro').DataTable( {
			paging: false,
			info: false,
			scrollY:        '50vh',
        	scrollCollapse: true,
            "language": {
                "decimal":        "",
                "emptyTable":     "Nenhum registro encontrado",
                "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty":      "0 Registros",
                "infoFiltered":   "(filtered from _MAX_ total entries)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Exibir: _MENU_",
                "loadingRecords": "Carregando...",
                "processing":     "Processando...",
                "search":         "Pesquisar: ",
                "zeroRecords":    "Nenhum registro encontrado",
                "paginate": {
                    "first":      "Anterior",
                    "last":       "Próxima",
                    "next":       "Próxima",
                    "previous":   "Anterior"
                }
            }
		} );
		
		$('#tableAtendimento').DataTable( {
			paging: false,
			info: false,
			scrollY: '50vh',
        	scrollCollapse: true,
            "language": {
                "decimal":        "",
                "emptyTable":     "Nenhum registro encontrado",
                "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty":      "0 Registros",
                "infoFiltered":   "(filtered from _MAX_ total entries)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Exibir: _MENU_",
                "loadingRecords": "Carregando...",
                "processing":     "Processando...",
                "search":         "Pesquisar: ",
                "zeroRecords":    "Nenhum registro encontrado",
                "paginate": {
                    "first":      "Anterior",
                    "last":       "Próxima",
                    "next":       "Próxima",
                    "previous":   "Anterior"
                }
            }
        } );
	} );
	
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

		let id_endereco = 0;
		let id_email = 0;
		let id_telefone = 0;
		let id_cartao = 0;
		// adiciona endereco
		$('.adicionar').on('click', function(e){
			e.preventDefault();

			if($(this).data('campos') == 'endereco'){
				id_endereco++;
				var endereco_indice = id_endereco;

				var template = [
					'<fieldset class="endereco" id="',endereco_indice,'">',
						'<div class="form-group">',
							'<label class="control-label col-sm-1"><a href="#" title="Remover" class="remover"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></label>',
							'<div class="col-sm-3">',
								'<input type="text" name="endereco[',endereco_indice,'][latitude]" value="" class="form-control" placeholder="Latitude">',
							'</div>',
							'<div class="col-sm-3">',
    							'<input type="text" name="endereco[',endereco_indice,'][longitude]" value="" class="form-control" placeholder="Longitude">',
								'<span class="help help-block"></span>',
    						'</div>',
						'</div>',
						'<div class="form-group">',
							'<div class="col-sm-6">',
								'<input type="text" name="endereco[',endereco_indice,'][cep]" data-index="',endereco_indice,'" value="" class="form-control cep" placeholder="CEP" onChange="AtualizarCEP(this)">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
						'<div class="form-group">',
							'<div class="col-sm-3">',
								'<input type="text" name="endereco[',endereco_indice,'][rua]" value="" class="form-control" placeholder="Rua">',
							'</div>',
							'<div class="col-sm-3">',
    							'<input type="text" name="endereco[',endereco_indice,'][numero]" value="" class="form-control" placeholder="Número">',
								'<span class="help help-block"></span>',
    						'</div>',
						'</div>',
						'<div class="form-group">',
							'<div class="col-sm-6">',
								'<input type="text" name="endereco[',endereco_indice,'][bairro]" value="" class="form-control" placeholder="Bairro">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
						'<div class="form-group">',
							'<div class="col-sm-3">',
								'<select name="endereco[',endereco_indice,'][uf]" class="form-control">',
									'<option value="">UF</option>',
									'<option value="AC">AC</option>',
									'<option value="AL">AL</option>',
									'<option value="AP">AP</option>',
									'<option value="AM">AM</option>',
									'<option value="BA">BA</option>',
									'<option value="CE">CE</option>',
									'<option value="DF">DF</option>',
									'<option value="ES">ES</option>',
									'<option value="GO">GO</option>',
									'<option value="MA">MA</option>',
									'<option value="MT">MT</option>',
									'<option value="MS">MS</option>',
									'<option value="MG">MG</option>',
									'<option value="PA">PA</option>',
									'<option value="PB">PB</option>',
									'<option value="PR">PR</option>',
									'<option value="PE">PE</option>',
									'<option value="PI">PI</option>',
									'<option value="RJ">RJ</option>',
									'<option value="RN">RN</option>',
									'<option value="RS">RS</option>',
									'<option value="RO">RO</option>',
									'<option value="RR">RR</option>',
									'<option value="SC">SC</option>',
									'<option value="SP">SP</option>',
									'<option value="SE">SE</option>',
									'<option value="TO">TO</option>',
								'</select>',
							'</div>',
							'<div class="col-sm-3">',
								'<input type="text" name="endereco[',endereco_indice,'][cidade]" value="" class="form-control" placeholder="Cidade">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
						'<div class="form-group">',
							'<div class="col-sm-6">',
								'<input type="text" name="endereco[',endereco_indice,'][complemento]" value="" class="form-control" placeholder="Complemento">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
					'</fieldset>',
				].join('');

				$('.enderecos').find('.recipiente').append(template);

				endereco_indice++;

			// adiciona email
			}else if($(this).data('campos') == 'email'){
				id_email++;
				var email_indice = id_email;

				var template = [
					'<fieldset class="email"  id="',email_indice,'">',
						'<div class="form-group">',
							'<label class="control-label col-sm-1"><a href="#" title="Remover" class="remover"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></label>',
							'<div class="col-sm-2">',
								'<input type="text" name="email[',email_indice,'][emails]" value="" class="form-control">',
							'</div>',
							'<div class="col-sm-2">',
    							'<select name="email[',email_indice,'][setor]" class="form-control">',
        							'<option value="">Setor</option>',
        							'<option value="0">Financeiro</option>',
        							'<option value="1">Diretoria</option>',
        							'<option value="2">Suporte</option>',
        							'<option value="2">Pessoal</option>',
        						'</select>',
							'</div>',
							'<div class="col-sm-2">',
								'<input type="text" name="email[',email_indice,'][observacao]" value="" class="form-control" placeholder="observação">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
					'</fieldset>'
				].join('');

				$('.emails').find('.recipiente').append(template);

				email_indice++;

			// adiciona telefone
			}else if($(this).data('campos') == 'telefone'){
				id_telefone++;
				var telefone_indice = id_telefone;

				var template = [
					'<fieldset class="telefone"  id="',telefone_indice,'">',
						'<div class="form-group">',
							'<label class="control-label col-sm-1"><a href="#" title="Remover" class="remover"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></label>',
							'<div class="col-sm-2" data="setor" data-numero="observacao">',
								'<input type="text" name="telefone[',telefone_indice,'][ddd]" value="" class="form-control ddd" placeholder="DDD">',
							'</div>',
							'<div class="col-sm-2">',
								'<input type="text" name="telefone[',telefone_indice,'][numero]" value="" class="form-control fone" placeholder="Número">',
							'</div>',
							'<div class="col-sm-2">',
    							'<select name="telefone[',telefone_indice,'][setor]" class="form-control">',
        							'<option value="0">Financeiro</option>',
        							'<option value="1">Diretoria</option>',
        							'<option value="2">Suporte</option>',
        							'<option value="2">Pessoal</option>',
        						'</select>',
							'</div>',
							'<div class="col-sm-2">',
								'<input type="text" name="telefone[',telefone_indice,'][observacao]" value="" class="form-control" placeholder="observação">',
								'<span class="help help-block"></span>',
							'</div>',
						'</div>',
					'</fieldset>',
				].join('');

				$('.telefones').find('.recipiente').append(template);

				telefone_indice++;

			// adiciona cartao
			}else if($(this).data('campos') == 'cartao'){
				id_cartao++;
				var cartao_indice = id_cartao;

				var template = [
					'<fieldset class="col-sm-12 cartao" id="',cartao_indice,'">',
						'<div class="form-group">',
							'<label class="control-label col-sm-1"><a href="#" title="Remover" class="remover"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></label>',
							'<div class="col-sm-3">',
								'<input type="text" name="cartao[',cartao_indice,'][numero]" value="" class="form-control numero_cartao" placeholder="Número">',
							'</div>',
							'<div class="col-sm-3">',
    							'<input type="text" name="cartao[',cartao_indice,'][bandeira]" value="" class="form-control bandeira_cartao" placeholder="Bandeira">',
								'<span class="help help-block"></span>',
    						'</div>',
						'</div>',
						'<div class="form-group">',
							'<div class="col-sm-3">',
								'<input type="text" name="cartao[',cartao_indice,'][vencimento]" value="" class="form-control vencimento_cartao" placeholder="Vencimento">',
							'</div>',
							'<div class="col-sm-3">',
    							'<input type="text" name="cartao[',cartao_indice,'][codigo]" value="" class="form-control codigo_cartao" placeholder="Código">',
								'<span class="help help-block"></span>',
    						'</div>',
						'</div>',
						'<div class="form-group">',
							'<div class="col-sm-6">',
								'<input type="text" name="cartao[',cartao_indice,'][nome]" value="" class="form-control" placeholder="Nome">',
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

	$(document).on('focus', '.rg', function(){ $('.rg').mask('0#'); });
	$(document).on('focus', '.cpf', function(){ $('.cpf').mask('999.999.999-99'); });
	$(document).on('focus', '.cnpj', function(){ $('.cnpj').mask('99.999.999/9999-99'); });
	$(document).on('focus', '.ie', function(){ $('.ie').mask('99.999.999-9'); });
	$(document).on('focus', '.numero_cartao', function(){ $('.numero_cartao').mask('9999-9999-9999-9999'); });
	$(document).on('focus', '.codigo_cartao', function(){ $('.codigo_cartao').mask('999'); });
	$(document).on('focus', '.vencimento_cartao', function(){ $('.vencimento_cartao').mask('99/99'); });
	$(document).on('focus', '.data', function(){ $('.data').mask('11/11/1111'); });
	$(document).on('focus', '.tempo', function(){ $('.tempo').mask('00:00:00'); });
	$(document).on('focus', '.datatempo', function(){ $('.datatempo').mask('99/99/9999 00:00:00'); });
	$(document).on('focus', '.cep', function(){ $('.cep').mask('99999-999'); });
	$(document).on('focus', '.ddd', function(){ $('.ddd').mask('99'); });
	$(document).on('focus', '.fone', function(){ $('.fone').mask('9999-9999'); });
    $(document).on('focus', '.lat, .long', function(){
		$('.lat, .long').mask('Z#', {translation:  {'Z': {pattern: /[-0-9]/}}});
	});
	$(document).on('focus', '.gmt', function(){
		$('.gmt').mask('Z90', {translation:  {'Z': {pattern: /[-+]/}}});
	});

	$('.rg').mask('0#');
	$('.cnpj').mask('99.999.999/9999-99');
	$('.ie').mask('99.999.999-9');
	$('.numero_cartao').mask('9999-9999-9999-9999');
	$('.codigo_cartao').mask('999');
	$('.vencimento_cartao').mask('99/99');
	$('.tempo').mask('00:00:00');
	$('.datatempo').mask('99/99/9999 00:00:00');
	$('.ddd').mask('99');
	$('.fone').mask('9999-9999');
	$('.cpf').mask('999.999.999-99');
	$('.data').mask('99/99/9999');
	$('.cep').mask('99999-999');
    //-----------------------------------------//
    $('.ein').mask('99-9999999');
    $('.lat, .long').mask('Z#', {translation:  {'Z': {pattern: /[-0-9]/}}});

	$('.gmt').mask('Z90', {translation:  {'Z': {pattern: /[-+]/}}});

</script>

<script type="text/javascript">

    function buscaCnpj() {
        var cnpj = document.getElementById("cnpj").value.replace('.', '').replace('/', '').replace('-', '').replace('.', '');
        var url = '../cadastros/consulta_cnpj/' + cnpj;
        console.log(url);
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            contentType: 'application/json',
            success: function(data) {
                if (data.status == "OK") {
                    document.getElementById("razao_social").value = data.nome;
                    document.getElementById("nome").value = data.fantasia;
                    document.getElementById("cep").value = data.cep;
                    document.getElementById("rua").value = data.logradouro;
                    document.getElementById("numero").value = data.numero;
                    document.getElementById("bairro").value = data.bairro;
                    document.getElementById("cidade").value = data.municipio;
                    $("#estado").val(data.uf);

                    var telefone = data.telefone;
                    if (telefone != '') {
                        var removeParentesesTelefone = telefone.replace(/\(|\)/g, '');
                        var removeSeparadorTelefone = removeParentesesTelefone.replace("-", "");
                        var removeEspacoTelefone = removeSeparadorTelefone.replace(" ", "");
                        var dddTelefone = removeParentesesTelefone.substring(0, 2);
                        var numeroTelefone = removeEspacoTelefone.substring(2, 10);
                    } else {
                        var dddTelefone = '';
                        var numeroTelefone = '';
                    }

                    document.getElementById("ddd").value = dddTelefone;
                    document.getElementById("tel").value = numeroTelefone;
                    document.getElementById("email").value = data.email;
                } else {
                    document.getElementById("email").value = '';
                    document.getElementById("ddd").value = '';
                    document.getElementById("tel").value = '';
                    document.getElementById("razao_social").value = '';
                    document.getElementById("nome").value = '';
                    document.getElementById("cep").value = '';
                    document.getElementById("rua").value = '';
                    document.getElementById("numero").value = '';
                    document.getElementById("bairro").value = '';
                    document.getElementById("cidade").value = '';
                    window.alert('CNPJ não encontrado na base de dados da Receita Federal');
            }}
        });
    }

$(document).ready(function(){

	$(".resultado").hide();
	$("#clientes").ajaxForm({
		target: '.resultado',
		dataType: 'json',
		beforeSubmit: function(){
			$(".salvar").html('<i class="fa fa-spinner fa-spin fa-fw"></i> Salvando...');
		},
		success: function(retorno){
			$(".resultado").html(retorno.mensagem);
			$(".resultado").show();
		},
		error: function(retorno){
			$(".salvar").html('<i class="fa fa-download" aria-hidden="true"></i> Salvar');
		},
		complete: function(retorno){
			$(".salvar").html('<i class="fa fa-download" aria-hidden="true"></i> Salvar');
		}

	});

	$('#empresa').change(function() {
		var empresa = $(this).val();
		if (empresa == "OMNILINK" || empresa == "SEGURADORA"){
			$('.nota_fiscal').hide();
			$('.opentech').hide();
			$('.checkbox').prop('checked', false);
			$('.adt').val('');
			$('.orgao').hide();
			$('.camposNotaFiscal').val('');
		}else{
			$('.nota_fiscal').show();
			$('.opentech').show();
			$('.orgao').show();
			$('.adt').val('privado');
		}
	});

});
</script>
<style>
.nao-obrigatorio:after {
	content: none
}
</style>