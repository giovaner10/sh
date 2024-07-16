<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>CRM de Vendas</title>
		<link rel="shortcut icon" type="image/png" href="<?=base_url('/assets/images/favicon.jpeg');?>"/>
		<!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"> -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/select2.css">
		<link rel="stylesheet" href="<?=base_url('/assets/css/master.css');?>">
		<link rel="stylesheet" href="<?=base_url('/assets/css/custom.css');?>">
		<style>
			.btn-status-atividade {
				position: initial;
			}
			.modal-backdrop{
				display: none;
			}
		</style>
	</head>
	<body>
		<div style="position: fixed;top: 0;width: 100%;background-color: white;">
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
				<!-- Navbar content -->
				<a class="navbar-brand" href="<?=base_url()?>index.php/gestor_vendas"><strong>CRM de Vendas</strong></a>
				<select name="cliente" class="span6" style="margin-bottom: 0px;width: 200px;" id="clientes" onchange="location = '<?=base_url()?>index.php/gestor_vendas/deal/'+this.value;">
					<option value="">Empresas</option>
				</select> 
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNavDropdown">
					<ul class="navbar-nav mr-auto" style="margin-left: 20px;">
						<li class="nav-item active">
							<a class="nav-link" href="<?=base_url()?>index.php/gestor_vendas">Negócios <span class="sr-only">(current)</span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?=base_url()?>index.php/gestor_vendas/empresas<?php if($this->input->get('user_id')){echo '?user_id='.$this->input->get('user_id');}?>">Empresas
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?=base_url()?>index.php/gestor_vendas/atividades<?php if($this->input->get('user_id')){echo '?user_id='.$this->input->get('user_id');}?>">Atividades
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?=base_url()?>index.php/home">Shownet</a>
						</li>
						<!--<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Contatos
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
								<a class="dropdown-item" href="#">Pessoas</a>
								<a class="dropdown-item" href="#">Organizações</a>
							</div>
						</li>-->
					</ul>
					<ul class="navbar-nav ">
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<?php echo $this->auth->get_login('admin', 'email') ?>
							</a>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink2">
								<a class="dropdown-item" href="<?php echo site_url('acesso/sair/admin') ?>">
									<i class="fa fa-sign-out"> Sair</i>
								</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>
			<div class="container-fluid">
				<div class="menu-negocio">
					<div class="row">
						<div class="col-sm" style="text-align: left;">
							<!-- Button trigger modal -->
							<button type="button" class="btn btn-success" data-toggle="modal" data-target="#negocioModalCenter">
								Adicionar um negócio
							</button>
							<?php if ($this->auth->is_allowed_block('crm_vendas_admin')):?>
							<button type="button" class="btn btn-success" data-toggle="modal" data-target="#migrar">
								Migrar empresas
							</button>
							<?php endif;?>
							<!-- Modal Negocio -->
							<div class="modal fade" id="negocioModalCenter" tabindex="-1" role="dialog" aria-labelledby="negocioModalCenterTitle" aria-hidden="true">
								<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="negocioModalCenterTitle">Adicionar um negócio</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="container-fluid">
												<form class="" action="#" method="get">
													<div class="row">
														<div class="col" style="border-right: 1px solid #ccc;">
															<div class="form-group">
																<label class="sr-only" for="name_contact">Nome da Pessoa</label>
																<div class="input-group mb-2">
																	<div class="input-group-prepend">
																		<div class="input-group-text"><i class="fas fa-user"></i></div>
																	</div>
																	<input type="text" class="form-control" id="name_contact" placeholder="Nome da Pessoa">
																</div>
															</div>
															<div class="form-group">
																<label class="sr-only" for="organizacaoFormInputGroup">Organização</label>
																<div class="input-group mb-2">
																	<div class="input-group-prepend">
																		<div class="input-group-text"><i class="fas fa-building"></i></div>
																	</div>
																	<input type="text" class="form-control" id="organizacaoFormInputGroup" onkeyup="$('#tituloNegocioFormInputGroup')[0].value='Négocio '+this.value;" placeholder="Organização">
																</div>
															</div>
															<div class="form-group">
																<label class="sr-only" for="tituloNegocioFormInputGroup">Titulo do negócio</label>
																<div class="input-group mb-2">
																	<div class="input-group-prepend">
																		<div class="input-group-text"><i class="fas fa-font"></i></div>
																	</div>
																	<input type="text" class="form-control" id="tituloNegocioFormInputGroup" placeholder="Titulo do negócio">
																</div>
															</div>
															<div class="form-group">
																<div class="form-row">
																	<div class="col-md-6 mb-6">
																		<label class="sr-only" for="valorNegocioFormInputGroup">Valor de negócio</label>
																		<div class="input-group mb-2">
																			<div class="input-group-prepend">
																				<div class="input-group-text"><i class="fas fa-dollar-sign" style="padding: 0 3px;"></i> </div>
																			</div>
																			<input type="text" class="form-control" id="valorNegocioFormInputGroup" placeholder="Valor">
																		</div>
																	</div>
																	<div class="col-md-6 mb-6">
																		<label class="sr-only" for="moeda">Titulo do negócio</label>
																		<div class="input-group mb-2">
																			<div class="input-group-prepend">
																				<div class="input-group-text"><i class="fas fa-money-bill-alt"></i></div>
																			</div>
																			<select id="moeda" class="custom-select">
																				<option>Moeda</option>
																				<option>BRL</option>
																				<option>USD</option>
																			</select>
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<div class="form-row">
																	<div class="col-md-12 mb-12">
																		<label class="sr-only" for="company">Funil</label>
																		<div class="input-group mb-2" style="margin-top:-9px;">
																			<div class="input-group-prepend">
																				<div class="input-group-text"><i class="fas fa-filter"></i></div>
																			</div>
																			<select class="custom-select" id="company">
																				<option>Selecione o Funil</option>
																			</select>
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<div class="form-row">
																	<div class="col-md-12 mb-12">
																		<div class="btn-group btn-group-toggle d-flex bd-highlight" id="etapas" data-toggle="buttons" style="overflow-x: auto;">
																			
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<label class="sr-only" for="dataFechaNegocio">Data de fechamento esperada</label>
																<div class="input-group mb-2">
																	<div class="input-group-prepend">
																		<div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
																	</div>
																	<input id="dataFechaNegocio" type="date" class="form-control" placeholder="Data de fechamento esperada %">
																</div>
															</div>
															<div class="form-group">
																<label class="sr-only" for="cnpj">CNPJ</label>
																<div class="input-group mb-2">
																	<div class="input-group-prepend">
																		<div class="input-group-text"><i class="fas fa-briefcase"></i></div>
																	</div>
																	<input type="text" class="form-control" id="cnpj" placeholder="CNPJ">
																</div>
															</div>
															<div class="form-group">
																<label class="sr-only" for="fornecedor">Fornecedor</label>
																<div class="input-group mb-2">
																	<div class="input-group-prepend">
																		<div class="input-group-text"><i class="fas fa-briefcase"></i></div>
																	</div>
																	<input type="text" class="form-control" id="fornecedor" placeholder="Fornecedor">
																</div>
															</div>
															<div class="form-group">
																<label class="sr-only" for="fornecedor">Segmento</label>
																<div class="input-group mb-2">
																	<div class="input-group-prepend">
																		<div class="input-group-text"><i class="fas fa-briefcase"></i></div>
																	</div>
																	<input type="text" class="form-control" id="segmento" placeholder="Segmento">
																</div>
															</div>
														</div><!--modal col left-->
														<div class="col-6">
															<div class="form-group">
																<div class="form-row">
																	<label class="sr-only" for="phone_company">Telefone</label>
																	<div class="input-group mb-2">
																		<div class="input-group-prepend">
																			<div class="input-group-text"><i class="fas fa-phone" style="padding: 0 3px;"></i> </div>
																		</div>
																		<input type="text" class="form-control" id="phone_company" required placeholder="Telefone da organização">
																	</div>
																</div>
															</div>
															<div class="form-group">
																<div class="form-row">
																	<label class="sr-only" for="email_company">E-mail</label>
																	<div class="input-group mb-2">
																		<div class="input-group-prepend">
																			<div class="input-group-text"><i class="fas fa-at" style="padding: 0 3px;"></i> </div>
																		</div>
																		<input type="text" class="form-control" id="email_company" required placeholder="E-mail da organização">
																	</div>
																</div>
															</div>
															<div class="form-group">
																<div class="form-row">
																	<div class="col-md-8 mb-6">
																		<label class="sr-only" for="phone_contact">Telefone</label>
																		<div class="input-group mb-2">
																			<div class="input-group-prepend">
																				<div class="input-group-text"><i class="fas fa-phone" style="padding: 0 3px;"></i> </div>
																			</div>
																			<input type="text" class="form-control" required id="phone_contact" placeholder="Telefone do contato">
																		</div>
																	</div>
																	<div class="col-md-4 mb-6" style="margin-left: -14px;">
																		<label class="sr-only" for="type_phone_contact">Tipo</label>
																		<div class="input-group mb-2 mf-0">
																			<select id="type_phone" class="custom-select"  style=" border-top-left-radius: 0; border-bottom-left-radius: 0;">
																				<option>Tipo</option>
																				<option>Trabalho</option>
																				<option>Celular</option>
																				<option>Casa</option>
																			</select>
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<div class="form-row">
																	<div class="col-md-8 mb-6">
																		<label class="sr-only" for="email_contact">E-mail</label>
																		<div class="input-group mb-2">
																			<div class="input-group-prepend">
																				<div class="input-group-text"><i class="fas fa-at" style="padding: 0 3px;"></i> </div>
																			</div>
																			<input type="text" class="form-control" id="email_contact" placeholder="E-mail do contato">
																		</div>
																	</div>
																	<div class="col-md-4 mb-6" style="margin-left: -14px;">
																		<label class="sr-only" for="type_email_contact">Tipo</label>
																		<div class="input-group mb-2 mf-0">
																			<select id="type_email" class="custom-select"  style=" border-top-left-radius: 0; border-bottom-left-radius: 0;">
																				<option>Tipo</option>
																				<option>Trabalho</option>
																				<option>Casa</option>
																			</select>
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<label class="sr-only" for="street">Rua</label>
																<div class="input-group mb-2">
																	<div class="input-group-prepend">
																		<div class="input-group-text"><i class="fas fa-map-marker"></i></div>
																	</div>
																	<input type="text" class="form-control" id="street" placeholder="Rua">
																</div>
															</div>
															<div class="form-group">
																<label class="sr-only" for="number">Número</label>
																<div class="input-group mb-2">
																	<div class="input-group-prepend">
																		<div class="input-group-text"><i class="fas fa-briefcase"></i></div>
																	</div>
																	<input type="text" class="form-control" id="number" placeholder="Número">
																</div>
															</div>
															<div class="form-group">
																<label class="sr-only" for="bairro">Bairro</label>
																<div class="input-group mb-2">
																	<div class="input-group-prepend">
																		<div class="input-group-text"><i class="fas fa-briefcase"></i></div>
																	</div>
																	<input type="text" class="form-control" id="district" placeholder="Bairro">
																</div>
															</div>
															<div class="form-group">
																<label class="sr-only" for="city">Cidade</label>
																<div class="input-group mb-2">
																	<div class="input-group-prepend">
																		<div class="input-group-text"><i class="fas fa-briefcase"></i></div>
																	</div>
																	<input type="text" class="form-control" id="city" placeholder="Cidade">
																</div>
															</div>
															<div class="form-group">
																<label class="sr-only" for="state">Estado</label>
																<div class="input-group mb-2">
																	<div class="input-group-prepend">
																		<div class="input-group-text"><i class="fas fa-briefcase"></i></div>
																	</div>
																	<input type="text" class="form-control" id="state" placeholder="Estado">
																</div>
															</div>
														</div><!--modal col right-->
													</div>
												</form>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
											<button type="button" class="btn btn-success" id="save-model">Salvar</button>
										</div>
									</div>
								</div>
							</div><!--End Modal Negocio-->

							<!-- Modal Negocio -->
							<div class="modal fade" id="migrar" tabindex="-1" role="dialog" aria-labelledby="negocioModalCenterTitle" aria-hidden="true">
								<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="negocioModalCenterTitle">Migrar negócios</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="container-fluid">
												<input name="usuario_antigo" type="hidden" id="usuario_antigo" value="<?=$this->input->get('user_id');?>">
												<select id='funcionarios1' class="form-control col" style="background-color: gainsboro;color: black;">
													<option value=''>Funcionário</option>
												</select>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
											<button type="button" class="btn btn-success" onclick="migrar()" id="save-model">Migrar</button>
										</div>
									</div>
								</div>
							</div><!--End Modal Negocio-->
						</div>
						<div class="col-sm" style="margin: 5px 0;text-align: center;">
							R$ 0 - 0 negócio
						</div>
						<div class="col-sm" style="text-align: right;">
							<div class="row">
								<div class="col">
									<div class="dropdown" style="text-align: right;">
										<button class="btn  dropdown-toggle" type="button" id="prin" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
										<div class="dropdown-menu dropdown-menu-right" id="drop-btn" aria-labelledby="prin"></div>
									</div>
								</div>
									<select onchange="location = '<?=base_url()?>index.php/gestor_vendas?user_id='+this.value;" id='funcionarios' class="form-control col" style="background-color: gainsboro;color: black;" <?php if ($this->auth->is_allowed_block('crm_vendas_admin')):?> style="display:none;" <?php endif;?>>
										<option value=''>Funcionário</option>
									</select>
									<div class="col" style="display:none;">
										<div class="dropdown">
											<button class="btn  dropdown-toggle" type="button" id="sec" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Gabriel Jeronimo
											</button>
											<div class="dropdown-menu dropdown-menu-right" aria-labelledby="sec">
												<button class="dropdown-item" id="" type="button">João Paulo</button>
												<button class="dropdown-item" id="" type="button">Pedro Lucas</button>
											</div>
										</div>
									</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container-negocios" style="position: inherit;margin-top: 125px;"></div>

		<!-- Modal adicionar ou editar atividades -->
		<div class="modal fade" id="addAtividadeModal" tabindex="-1" role="dialog" aria-labelledby="addAtividadeModalLabel" aria-hidden="true"
		 style="z-index: 4000;">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addAtividadeModalLabel">Agendar uma atividade</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row border">

							<div class="col-12 p-3">
								<div class="row">
									<div class="col">
										<style media="screen">
											.btn-white {
												background: #FFF;
											}
										</style>

										<div class="btn-group d-flex bd-highlight" role="group" aria-label="Basic example">
											<button id="ligar" type="button" class="btn btn-white btn-sm flex-fill bd-highlight border">
												<i class="fas fa-phone"></i> Ligar</button>
											<button id="reuniao" type="button" class="btn btn-white btn-sm flex-fill bd-highlight border">
												<i class="fas fa-users"></i> Reunião</button>
											<button id="tarefa" type="button" class="btn btn-white btn-sm flex-fill bd-highlight border">
												<i class="fas fa-clock"></i> Tarefa</button>
											<button id=prazo type="button" class="btn btn-white btn-sm flex-fill bd-highlight border">
												<i class="fas fa-flag"></i> Prazo</button>
											<button id="almoco" type="button" class="btn btn-white btn-sm flex-fill bd-highlight border">
												<i class="fas fa-utensils"></i> Almoço</button>
										</div>
									</div>
								</div>
								<div class="row pt-3">
									<div class="col">
										<form>
											<div class="form-group">
												<label for="inputAddress">Atividade</label>
												<input type="text" class="form-control" id="activity" placeholder="Digite aqui uma atividade">
											</div>
											<div class="form-row">
												<div class="form-group col-md-4">
													<label for="inputData4">Data</label>
													<input type="date" class="form-control" id="date" placeholder="Data">
												</div>
												<div class="form-group col-md-4">
													<label for="inputHora4">Hora</label>
													<input type="text" class="form-control" id="hour" placeholder="Hora">
												</div>
												<div class="form-group col-md-4">
													<label for="inputDuracao4">Duração</label>
													<input type="text" class="form-control" id="duration" placeholder="Duração">
												</div>
											</div>
											<div class="form-group">
												<label for="notacaoFormControlTextarea">Notação</label>
												<textarea class="form-control" id="textarea" rows="4" placeholder="Digite aqui para tomar nota"></textarea>
											</div>
											<!-- <div class="form-group">
												<label for="inputState">Atriguido a</label>
												<select id="inputState" class="form-control">
													<option selected>Jerônimo Gabriel</option>
													<option>João Nogueira</option>
												</select>
											</div>

											<div class="form-group">
												<label for="inputNomeNegocio">Ligado a</label>
												<input type="text" class="form-control" id="inputNomeNegocio" placeholder="Negócio">
											</div>
											<div class="form-group">
												<input type="text" class="form-control" id="inputNomePessoa" placeholder="pessoa">
											</div>
											<div class="form-group">
												<input type="text" class="form-control" id="inputNomeOrganizacao" placeholder="Organização">
											</div> -->

											<!-- <div class="form-group">
												<div class="form-check">
													<input class="form-check-input" type="checkbox" id="gridCheck">
													<label class="form-check-label" for="gridCheck">
														Enviar convites aos participantes
													</label>
												</div>
											</div> -->
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="row pt-3" style="text-align:right;">
							<div class="col">
								<div class="form-group float-right">
									<button class="btn btn-danger" type="button" id="cancelar_agend" data-dismiss="modal" name="button">Cancelar</button>
									<button class="btn btn-success" type="button" id="save_agend" name="button">Salvar</button>
								</div>
								<div class="form-group float-right p-2">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="realized">
										<label class="form-check-label" for="gridCheck">
											Marcar como realizada
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
	<style>
		#outer-dropzone {
			height: 140px;

			touch-action: none;
			}

			#inner-dropzone {
			height: 80px;
			}

			.dropzone {
			background-color: #ccc;
			border: dashed 4px transparent;
			border-radius: 4px;
			margin: 10px auto 30px;
			padding: 10px;
			width: 80%;
			transition: background-color 0.3s;
		}

	</style>
	<script>
		var dic={
			'base_url':'<?=base_url()?>'
		};
	</script>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" ></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" ></script>
	<script src="<?=base_url()?>assets/js/select2.js"></script>
	<script src="<?=base_url('assets/js/negocio.js?2018-10-25');?>"></script>
	<script src="<?=base_url('assets/js/custom.js');?>"></script>
	<script src="<?=base_url('assets/js/common-functions.js');?>"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
	<script src="https://momentjs.com/downloads/moment.js"></script>
	<script>
		var funcionario = <?php if ($this->input->get('user_id')){echo $this->input->get('user_id');}else{echo "''";}?>;

		<?php if ($this->auth->is_allowed_block('crm_vendas_admin')):?>
			$.getJSON(dic['base_url']+'index.php/contas/get_funcionarios', {}, function(callback) {
				var html = "<option value=''>Funcionário</option>";
				$.each(callback,function(i, obj) {
					html += "<option value='"+obj.id+"'>"+obj.nome+"</option>";
				});
				$('#funcionarios')[0].innerHTML = html;
				if(funcionario){
					$('#funcionarios')[0].value=funcionario;
				}
				$('#funcionarios1')[0].innerHTML = html;
				if(funcionario){
					$('#funcionarios1')[0].value=funcionario;
				}
			});

		<?php endif;?>
		
	</script>

	<script>
		var id_coluna_inicial = false;
		var id_coluna_final = false;
		var etapa_coluna_inicial = false;
		var etapa_coluna_final = false;
		var id_empresa = false;
		var business = false;
		var card = false;

		function dragStart(ev) {
			ev.dataTransfer.effectAllowed = 'move';
			ev.dataTransfer.setData("Text", ev.target.getAttribute('id'));
			id_empresa = ev.target.dataset.id;
			id_coluna_inicial = ev.target.dataset.etapa_id;
			etapa_coluna_inicial = ev.target.dataset.etapa;
			business = ev.target.dataset.business;
			card = ev.target;
			return true;
		}
		function dragEnter(ev) {
			ev.preventDefault();
			in_div=ev;
			return true;
		}
		function dragOver(ev) {
			ev.preventDefault();
		}
		function dragDrop(ev) {
			var data = ev.dataTransfer.getData("Text");
			if(ev.target.tagName=="UL"){
				ev.target.appendChild(document.getElementById(data));
				id_coluna_final = ev.target.dataset.id;
				etapa_coluna_final = ev.target.dataset.etapa;
			}
			else if(ev.target.parentElement.tagName=="UL"){
				ev.target.parentElement.appendChild(document.getElementById(data));
				id_coluna_final = ev.target.parentElement.dataset.id;
				etapa_coluna_final = ev.target.dataset.etapa;
			}
			else if(ev.target.parentElement.parentElement.tagName=="UL"){
				ev.target.parentElement.parentElement.appendChild(document.getElementById(data));
				id_coluna_final = ev.target.parentElement.parentElement.dataset.id;
				etapa_coluna_final = ev.target.dataset.etapa;
			}
			else if(ev.target.parentElement.parentElement.parentElement.tagName=="UL"){
				ev.target.parentElement.parentElement.parentElement.appendChild(document.getElementById(data));
				id_coluna_final = ev.target.parentElement.parentElement.parentElement.dataset.id;
				etapa_coluna_final = ev.target.dataset.etapa;
			}
			else if(ev.target.parentElement.parentElement.parentElement.parentElement.tagName=="UL"){
				ev.target.parentElement.parentElement.parentElement.parentElement.appendChild(document.getElementById(data));
				id_coluna_final = ev.target.parentElement.parentElement.parentElement.parentElement.dataset.id;
				etapa_coluna_final = ev.target.dataset.etapa;
			}
			else{
				alert("Não foi possível mover o card, tente novamento")
			}
			if(id_coluna_final){
				if(id_empresa){
					if (id_coluna_final == id_coluna_inicial ? false : true) {
						card.dataset.etapa_id = id_coluna_final;
						card.dataset.etapa = etapa_coluna_final;
						$.post(dic['base_url']+'index.php/gestor_vendas/editBusinessEtapa', {
							id:       id_empresa,
							etapa_id: id_coluna_final,
							etapa_old: id_coluna_inicial,
							business: business,
							etapa_old_name: etapa_coluna_inicial,
							etapa_name: etapa_coluna_final
						}, function(callback) {
							alert("A empresa foi movida com sucesso.");
						}).done(function() {
							//location.reload();
							// alert( "second success" );
						}).fail(function() {
							// alert( "erro" );
						}).always(function() {
							// alert( "finished" );
						});
					} 
				}
			}
			id_coluna_inicial = false;
			id_coluna_final = false;
			etapa_coluna_inicial = false;
			etapa_coluna_final = false;
			id_empresa = false;
			business = false;
			card = false;
			ev.stopPropagation();
			return false;
		}
		var empresas_select2=[];
		$('#clientes').select2();

		function migrar(){
			if( !$('#usuario_antigo')[0].value){
				alert("Nenhum funcionário selecionado!");
				return false;
			}
			if( !$('#funcionarios1')[0].value){
				alert("Selecione o novo funcionário!");
				return false;
			}
			var r = confirm("Deseja realmente migrar todas as empresas desse funcionário?");

			if (r == true) {
				$.post(dic['base_url']+'index.php/gestor_vendas/migrar_empresas', {
					usuario_antigo: $('#usuario_antigo')[0].value,
					novo_usuario: $('#funcionarios1')[0].value
				}, function(callback) {
					alert("A empresa foi movida com sucesso.");
				}).done(function() {
					//location.reload();
					// alert( "second success" );
				}).fail(function() {
					// alert( "erro" );
				}).always(function() {
					// alert( "finished" );
				});
			}
		}
	</script>
</html>
