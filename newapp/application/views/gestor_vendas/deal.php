<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Acordo - CRM de Vendas</title>
		<link rel="shortcut icon" type="image/png" href="<?=base_url('/assets/images/favicon.jpeg');?>"/>
		<!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css"> -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" >
		<link rel="stylesheet" href="<?=base_url('/assets/css/master.css');?>">
		<link rel="stylesheet" href="<?=base_url('/assets/css/custom.css');?>">
		<link rel="stylesheet" href="<?=base_url('/assets/css/jquery.eeyellow.Timeline.css');?>">
		<link rel="stylesheet" href="<?=base_url('/assets/css/dropzone.min.css');?>">
	</head>
	<body style="background-color:#EEE;">
		<nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">
		<!-- Navbar content -->
		<a class="navbar-brand" href="<?=base_url()?>index.php/gestor_vendas">
			<b>CRM de Vendas</b>
		</a>
		<!-- <form action="#" class="form-inline">
			<input class="form-control mr-sm-2" type="search" placeholder="Pesquisa" aria-label="Search" style="border:1px solid #444; background-color:#aaa;">
		</form> -->
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
		 aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNavDropdown">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="<?=base_url()?>index.php/gestor_vendas">Negócios
						<span class="sr-only">(current)</span>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?=base_url()?>index.php/gestor_vendas/empresas">Empresas
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?=base_url()?>index.php/gestor_vendas/atividades">Atividades
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
	<div class="container">
		<br>
		<div class="header-deal" style="background-color:#FFF; padding: 10px; border: 1px solid #DDD; border-radius: 5px;">
			<div class="row">
				<div class="col-6">
					<div class="dropdown mb-2">
						<button class="btn btn-white btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<h4 id="h4-title" 
								data-id="<?php echo $businness[0]->id; ?>"
								data-etapa="<?php echo $businness[0]->etapa_id; ?>"
								data-funil="<?php echo $businness[0]->funil_id; ?>"
								data-created="<?php echo $businness[0]->created_on; ?>"
								data-company="<?php echo $businness[0]->company; ?>"
								data-title="<?php echo $businness[0]->title; ?>"
								data-diffStagnado="<?php echo $businness[0]->diffStagnado; ?>"
								data-diffDays="<?php echo $businness[0]->diffDays; ?>"
								data-diffTime="<?php echo $businness[0]->diffTime; ?>"

								><?php if($businness[0]->title!=""){echo $businness[0]->title;}else{echo "Sem nome";} ?></h4>
						</button>
						<span class="label-stagnado">STAGNADO POR 10 DIAS</span>
						<div class="dropdown-menu dropdown-menu-left w-100 shadow" aria-labelledby="dropdownMenuButton">
							<form class="pr-3 pl-3 pt-3" action="">
								<div class="input-group input-group-sm mb-3">
									<input type="text" value="<?php echo $businness[0]->title; ?>" id="name-business" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Digite aqui para renomear...">
								</div>
								<div class="dropdown-divider"></div>
								<div class="float-right">
									<button class="btn btn-sm btn-success" id="save-name-business">Salvar</button>
									<button class="btn btn-sm">Cancelar</button>
								</div>
							</form>
						</div>
					</div>
					<h6>R$ <?php echo $businness[0]->value; ?> &#160; &#160;
						<a href="#">Adicionar produtos</a> &#160; &#160;
						<i class="fas fa-user"></i> <?php echo $businness[0]->user_id; ?> &#160; &#160;
						<i class="fas fa-building"></i> <?php echo $businness[0]->company; ?></h6>
				</div>
				<div class="col-6 text-right">
					<button class="btn btn-success" id="gain" >Ganho</button>
					<!-- <button class="btn btn-danger" id="lost" >Perdido</button> -->
					<!-- Button PERDIDO modal -->
					<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#perdidoModal">
						Perdido
					</button>
					<!-- PERDIDO Modal -->
					<div class="modal fade" id="perdidoModal" tabindex="-1" role="dialog" aria-labelledby="perdidoModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="perdidoModalLabel">Marcar como perdido</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<form>
										<div class="form-group">
											<label for="motivoPerda" style="float: left;">Motivo da preda</label>
											<select class="form-control" id="motivoPerda">
												<option>NÃO POSSI FROTA</option>
												<option>FROTA TERCERIZADA</option>
												<option>JÁ POSSUI RASTREAMENTO</option>
												<option>NÚMERO INEXISTENTE</option>
												<option>FALIU</option>
												<option>NÃO TEM INTERESSE</option>
											</select>
										</div>
										<div class="form-group">
											<label for="commentPerda" style="float: left;">Comentários (opcional)</label>
											<textarea class="form-control" id="commentPerda" rows="3"></textarea>
										</div>
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
									<button type="button" class="btn btn-danger btn-sm" id="lost" >Marcar como perdido</button>
								</div>
							</div>
						</div>
					</div>
					<div class="dropdown float-right ml-1">
						<button class="btn btn-default" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							...
						</button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
							<!-- <a class="dropdown-item" href="#">Duplicar</a> -->
							<!-- <a class="dropdown-item" href="#">Meclar</a> -->
							<a class="dropdown-item" id="delete-business" href="#">Apagar</a>
							<!-- <a class="dropdown-item" href="#">Exportar para XLS</a> -->
							<!-- <div class="dropdown-divider"></div> -->
							<!-- <a class="dropdown-item" href="#">Gerenciar sessões da barra lateral</a> -->
						</div>
					</div>
				</div>
				<div class="col-12">
					<div id="etapa" class="btn-group btn-group-toggle d-flex bd-highlight" data-toggle="buttons"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<a href="">
						<small> Show > Coluna 1</small>
					</a>
				</div>

				<div class="col-6 text-right">
					<a href="#">
						<small> Definir probabilidade</small>
					</a>
				</div>
			</div>
		</div>
		<div class="row">

			<div class="col-4">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title float-left">DETALHES</h5>
						<button class="btn btn-sm  ml-1 float-right">
							<i class="fas fa-cog"></i>
						</button>
						<button class="btn btn-sm ml-1 float-right" id="provider" >
							<i class="fas fa-edit"></i>
						</button>
						<div class="clearfix"></div>
						<hr>
						<form action="#" id="deal_details">
							<label for="inputFornecedor">Fornecedor: <?php echo $businness[0]->provider; ?></label>
							<div class="form-group">
								<input type="text" value="<?php echo $businness[0]->provider; ?>" class="form-control form-hidden" id="inputFornecedor" aria-describedby="fornecedorHelp" placeholder="Fornecedor">
							</div>
							<button type="button" class="btn btn-success form-hidden" id="provider_save">Salvar</button>
							<button type="button" class="btn btn-danger form-hidden" id="provider_cancelar">Cancelar</button>
						</form>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<h5 class="card-title float-left">ORGANIZAÇÃO</h5>
						<button class="btn btn-sm  ml-1 float-right">
							<i class="fas fa-cog"></i>
						</button>
						<button class="btn btn-sm ml-1 float-right" id="org">
							<i class="fas fa-edit"></i>
						</button>
						<div class="clearfix"></div>
						<hr>
						<form action="#" id="deal_org">
							<label for="inputEndereco">Endereço: <?php echo $businness[0]->street; ?> Nº <?php echo $businness[0]->number; ?> Bairro <?php echo $businness[0]->district; ?> Cidade <?php echo $businness[0]->city; ?> </label>
							<div class="form-group">
								<input type="text" class="form-control form-hidden" value="<?php echo $businness[0]->street; ?>" id="street" aria-describedby="enderecoHelp" placeholder="Rua">
								<input type="text" class="form-control form-hidden" value="<?php echo $businness[0]->number; ?>" id="number" aria-describedby="enderecoHelp" placeholder="Número">
								<input type="text" class="form-control form-hidden" value="<?php echo $businness[0]->district; ?>" id="district" aria-describedby="enderecoHelp" placeholder="Bairro">
								<input type="text" class="form-control form-hidden" value="<?php echo $businness[0]->city; ?>" id="city" aria-describedby="enderecoHelp" placeholder="Cidade">
								<input type="text" class="form-control form-hidden" value="<?php echo $businness[0]->state; ?>" id="state" aria-describedby="enderecoHelp" placeholder="Estado">
							</div>
							<label for="inputCNPJ">CNPJ: <?php echo $businness[0]->cnpj; ?></label>
							<div class="form-group">
								<input type="text" class="form-control form-hidden" value="<?php echo $businness[0]->cnpj; ?>" id="cnpj" aria-describedby="cnpjHelp" placeholder="CNPJ">
							</div>
							<label for="inputSegmento">Segmento: <?php echo $businness[0]->segmento; ?></label>
							<div class="form-group">
								<input type="text" class="form-control form-hidden" value="<?php if($businness[0]->segmento) echo $businness[0]->segmento; else{echo "";} ?>" id="segmento" aria-describedby="segmentoHelp" placeholder="Segmento">
							</div>
							<label for="inputPhoneCompany">Telefone: <?php echo $businness[0]->phone_company; ?></label>
							<div class="form-group">
								<input type="text" class="form-control form-hidden" value="<?php if($businness[0]->phone_company) echo $businness[0]->phone_company; else{echo "";} ?>" id="phone_company" aria-describedby="segmentoHelp" placeholder="Telefone">
							</div>
							<label for="inputEmailCompany">Email: <?php echo $businness[0]->email_company; ?></label>
							<div class="form-group">
								<input type="text" class="form-control form-hidden" value="<?php if($businness[0]->email_company) echo $businness[0]->email_company; else{echo "";} ?>" id="email_company" aria-describedby="segmentoHelp" placeholder="Email">
							</div>
							<button type="button" class="btn btn-success form-hidden" id="org_save">Salvar</button>
							<button type="button" class="btn btn-danger form-hidden" id="org_cancelar">Cancelar</button>
						</form>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<h5 class="card-title float-left">PESSOA</h5>
						<button class="btn btn-sm  ml-1 float-right">
							<i class="fas fa-cog"></i>
						</button>
						<button data-type-phone="<?php echo $businness[0]->type_phone; ?>" data-type-email="<?php echo $businness[0]->type_email; ?>" class="btn btn-sm ml-1 float-right" id="person">
							<i class="fas fa-edit"></i>
						</button>
						<div class="clearfix"></div>
						<hr>
						<form action="#" id="deal_personal">
							<label for="inputPerson"><?php echo $businness[0]->name_contact; ?></label><br>
							<div class="form-group form-hidden">
								<input type="text" value="<?php echo $businness[0]->name_contact; ?>" class="form-control" id="name_contact" placeholder="Nome da Pessoa">
							</div>
							<label for="inputTelefone">Telefone: <?php echo $businness[0]->phone_contact; ?> (<?php echo $businness[0]->type_phone; ?>)</label>
							<div class="form-group form-hidden">
								<div class="form-row">
									<div class="col-md-8 mb-6">
										<label class="sr-only" for="phone_contact">Telefone</label>
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<div class="input-group-text"><i class="fas fa-phone" style="padding: 0 3px;"></i> </div>
											</div>
											<input type="text" value="<?php echo $businness[0]->phone_contact; ?>" class="form-control" required id="phone_contact" placeholder="Telefone do contato">
										</div>
									</div>
									<div class="col-md-4 mb-6" style="margin-left: -14px;">
										<label class="sr-only" for="type_phone_contact">Tipo</label>
										<div class="input-group mb-2 mf-0">
											<select id="type_phone" class="custom-select"  style=" border-top-left-radius: 0; border-bottom-left-radius: 0;">
												<option>Trabalho</option>
												<option>Celular</option>
												<option>Casa</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<label for="inputEmail">Email: <?php echo $businness[0]->email_contact; ?> (<?php echo $businness[0]->type_email; ?>)</label>
							<div class="form-group form-hidden">
								<div class="form-row ">
									<div class="col-md-8 mb-6">
										<label class="sr-only" for="email_contact">E-mail</label>
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<div class="input-group-text"><i class="fas fa-at" style="padding: 0 3px;"></i> </div>
											</div>
											<input type="email" value="<?php echo $businness[0]->email_contact; ?>" class="form-control " id="email_contact" placeholder="E-mail do contato">
										</div>
									</div>
									<div class="col-md-4 mb-6" style="margin-left: -14px;">
										<label class="sr-only" for="type_email_contact">Tipo</label>
										<div class="input-group mb-2 mf-0">
											<select id="type_email" class="custom-select"  style=" border-top-left-radius: 0; border-bottom-left-radius: 0;">
												<option>Trabalho</option>
												<option>Casa</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<button type="button" class="btn btn-success form-hidden" id="person_save">Salvar</button>
							<button type="button" class="btn btn-danger form-hidden" id="person_cancelar">Cancelar</button>
						</form>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<h5 class="card-title float-left">CONTATOS</h5>
						<div class="clearfix"></div>
						<hr>

						<?php foreach($contatos as $key=>$contato):?>
						<form action="#">
							<label for="inputPerson"><?php echo $contato->nome; ?></label><br>
							<div class="form-group form-hidden">
								<input type="text" value="<?php echo $contato->nome; ?>" class="form-control" id="name_contact" placeholder="Nome da Pessoa">
							</div>
							<label for="inputTelefone">Telefone: <?php echo  $contato->telefone; ?></label>
							<div class="form-group form-hidden">
								<div class="form-row">
									<div class="col-md-8 mb-6">
										<label class="sr-only" for="phone_contact">Telefone</label>
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<div class="input-group-text"><i class="fas fa-phone" style="padding: 0 3px;"></i> </div>
											</div>
											<input type="text" value="<?php echo $contato->telefone; ?>" class="form-control" required id="phone_contact" placeholder="Telefone do contato">
										</div>
									</div>
								</div>
							</div>
							<br>
							<label for="inputEmail">Email: <?php echo $contato->email; ?> </label>
							<div class="form-group form-hidden">
								<div class="form-row ">
									<div class="col-md-8 mb-6">
										<label class="sr-only" for="email_contact">E-mail</label>
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<div class="input-group-text"><i class="fas fa-at" style="padding: 0 3px;"></i> </div>
											</div>
											<input type="email" value="<?php echo $contato->email; ?>" class="form-control " id="email_contact" placeholder="E-mail do contato">
										</div>
									</div>
								</div>
							</div>
						</form>
						<br>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<h5 class="card-title">VISÃO GERAL</h5>
						<hr>
						<p id="idade"></p>
						<p>Inativo (dias) - 0</p>
						<p id="created"></p>
					</div>
				</div>
				<br>
			</div>

			<div class="col-8">
				<div class="card">
					<div class="card-header text-justify">
						<ul class="nav nav-pills" id="pills-tab" role="tablist">
							<li class="nav-item">
								<a class="nav-link btn-light mr-2 active" id="pills-nota-tab" data-toggle="pill" href="#pills-nota" role="tab" aria-controls="pills-nota"
								 aria-selected="true">
									<i class="fas fa-sticky-note"></i> Tomar notas</a>
							</li>
							<li class="nav-item">
								<a class="nav-link btn-light mr-2" id="pills-atividade-tab" data-toggle="pill" href="#pills-atividade" role="tab" aria-controls="pills-atividade"
								 aria-selected="false">
									<i class="fas fa-calendar-alt"></i> Adicionar atividade</a>
							</li>
							<li class="nav-item">
								<a class="nav-link btn-light mr-2" id="pills-arquivo-tab" data-toggle="pill" href="#pills-arquivo" role="tab" aria-controls="pills-arquivo"
								 aria-selected="false">
									<i class="fas fa-paperclip"></i> Anexar arquivos</a>
							</li>
							<li class="nav-item">
								<a class="nav-link btn-light mr-2" id="pills-arquivo2-tab" data-toggle="pill" href="#pills-arquivo2" role="tab" aria-controls="pills-arquivo"
								 aria-selected="false">
									<i class="fas fa-paperclip"></i> Anexar 2</a>
							</li>
						</ul>
					</div>
					<div class="card-body">
						<div class="tab-content" id="pills-tabContent" style="border: 0;">
							<div class="tab-pane fade show active" id="pills-nota" role="tabpanel" aria-labelledby="pills-nota-tab" style=" margin-top: -5px;">
								<label for="tomarNotaTextarea1">Notação</label>
								<textarea class="form-control" id="tomarNotaTextarea1" rows="3" placeholder="Digite aqui para tomar nota"></textarea>
								<br>
								<button class="btn btn-danger" type="button" id="nota_cancelar" name="button">Cancelar</button>
								<button class="btn btn-success" type="button" id="nota_save" name="button">Salvar</button>
							</div>
							<div class="tab-pane fade" id="pills-atividade" role="tabpanel" aria-labelledby="pills-atividade-tab">
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
													</div> -->

													<!-- <div class="form-group">
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
								<div class="row pt-3" style="text-align:right;">
									<div class="col">
										<div class="form-group float-right">
											<button class="btn btn-danger" type="button" id="cancelar_agend" name="button">Cancelar</button>
											<button class="btn btn-success" type="button" id="save_agend" name="button">Salvar</button>
										</div>
										<div class="form-group float-right p-2">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" checked="" id="realized">
												<label class="form-check-label" for="gridCheck">
													Marcar como realizada
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="pills-arquivo" role="tabpanel" aria-labelledby="pills-arquivo-tab">
								<form id="dzone" class="dropzone" action="#" method="post" enctype="multipart/form-data">

								</form>
							</div>
							<div class="tab-pane fade" id="pills-arquivo2" role="tabpanel" aria-labelledby="pills-arquivo2-tab">
								<!-- AREA PARA IMPLANTAR INPUT FILE DE TESTE JERONIMO -->
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="validatedCustomFile" required>
									<label class="custom-file-label" for="validatedCustomFile">Selecione o arquivo...</label>
									<div class="invalid-feedback">Example invalid custom file feedback</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="width: 150px; background-color: gray; color: white; margin: 25px auto 0; padding: 10px; border-radius: 20px; text-align: center;">PLANEJADO</div>

				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="VivaTimeline">
									<dl id="activity"></dl>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div style="width: 120px; background-color: gray; color: white; margin: 25px auto 0; padding: 10px; border-radius: 20px; text-align: center;">PASSADO</div>
				<div class="card">
					<div class="card-body">
						<ul class="nav justify-content-center nav-tabs" id="myTab" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" id="tudo-tab" data-toggle="tab" href="#tudo" role="tab" aria-controls="tudo" aria-selected="true">Tudo</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="atividades-tab" data-toggle="tab" href="#atividades" role="tab" aria-controls="atividades" aria-selected="false">Atividades</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="notas-tab" data-toggle="tab" href="#notas" role="tab" aria-controls="notas" aria-selected="false">Notas</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="arquivos-tab" data-toggle="tab" href="#arquivos" role="tab" aria-controls="arquivos" aria-selected="false">Arquivos</a>
							</li>
						</ul>
						<div class="tab-content" id="myTabContent" style="overflow: hidden;">
							<div class="tab-pane fade show active" id="tudo" role="tabpanel" aria-labelledby="tudo-tab">
								<div class="row">
									<div class="col-md-12">
										<div class="VivaTimeline">
											<dl id="all"></dl>
										</div>
									</div>
								</div>
								<!-- <div class="row">
									<div class="col-md-12">
										<div class="VivaTimeline">
											<dl>
												<dt>May 2016</dt>
												<dd class="pos-left clearfix">
													<div class="circ"></div>
													<div class="time">Feb 03</div>
													<div class="events">
														<div class="events-header">Event Heading</div>
														<div class="events-body">
															<div class="row">
																<div class="col-md-6 pull-left">
																	<img class="events-object img-responsive img-rounded" src="img/dog01.jpeg" />
																</div>
																<div class="events-desc">
																	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
																	Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
																	Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
																	sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
																</div>
															</div>
															<div class="row">
																<div class="col-md-6 pull-left">
																	<img class="events-object img-responsive img-rounded" src="img/dog02.jpeg" />
																</div>
																<div class="events-desc">
																	Morbi at nisi vitae mauris pretium egestas. Morbi placerat risus ligula, nec placerat urna porta vel. Nullam sollicitudin
																	orci quis odio eleifend, ut facilisis orci lobortis. Vivamus sed lobortis odio. Nam volutpat, leo a ullamcorper
																	luctus, sapien libero auctor est, sed semper massa turpis sed quam. Mauris posuere, libero in ultricies
																	dignissim, lacus purus egestas urna, nec semper lorem tellus non eros. Nam at bibendum libero. Curabitur
																	a ante et orci cursus tincidunt. Vivamus dictum, libero et rhoncus congue, nulla erat mollis dui, vitae
																	cursus dui justo quis velit. In a tellus arcu. Nam at lobortis nisl. Donec consequat placerat eros, quis
																	elementum mauris sodales a. Maecenas id feugiat velit. Phasellus dictum eleifend varius. Cras nec orci turpis.
																	Aenean ut turpis nibh.
																</div>
															</div>
															<div class="row">
																<div class="col-md-6 pull-left">
																	<img class="events-object img-responsive img-rounded" src="img/dog03.jpeg" />
																</div>
																<div class="events-desc">
																	Cras condimentum, metus ut vehicula euismod, odio massa pulvinar neque, id gravida neque est et sem. Proin consequat id nibh
																	quis molestie. Quisque vehicula purus id purus elementum facilisis. Phasellus sodales nibh quis neque rhoncus
																	aliquet. Nunc eget ipsum efficitur, pretium arcu et, gravida purus. Phasellus tempor lacus ac enim pulvinar
																	elementum. Integer aliquet justo lacinia nunc tempus vulputate.
																</div>
															</div>
														</div>
														<div class="events-footer">
															123
														</div>
													</div>
												</dd>
												<dt>Feb 2016</dt>
												<dd class="pos-right clearfix">
													<div class="circ"></div>
													<div class="time">Jan 21</div>
													<div class="events">
														<div class="events-header">A Very Very Looooooooooooooooooooong Event Heading</div>
														<div class="events-body">
															<div class="row">
																<div class="col-md-6 pull-left">
																	<img class="events-object img-responsive img-rounded" src="img/cat01.jpeg" />
																</div>
																<div class="events-desc">
																	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
																	Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
																	Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
																	sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
																</div>
															</div>
															<div class="row">
																<div class="col-md-6 pull-left">
																	<img class="events-object img-responsive img-rounded" src="img/cat02.jpeg" />
																</div>
																<div class="events-desc">
																	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
																	Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
																	Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
																	sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
																</div>
															</div>
															<div class="row">
																<div class="col-md-6 pull-left">
																	<img class="events-object img-responsive img-rounded" src="img/cat03.jpeg" />
																</div>
																<div class="events-desc">
																	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
																	Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
																	Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
																	sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
																</div>
															</div>
														</div>
														<div class="events-footer">

														</div>
													</div>
												</dd>
												<dd class="pos-left clearfix">
													<div class="circ"></div>
													<div class="time">Jan 07</div>
													<div class="events">
														<div class="events-header">Event Heading</div>
														<div class="events-body">
															<div class="row">
																<div class="col-md-6 pull-left">
																	<img class="events-object img-responsive img-rounded" src="img/rabbit01.jpeg" />
																</div>
																<div class="events-desc">
																	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
																	Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
																	Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
																	sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
																</div>
															</div>
														</div>
													</div>
												</dd>
												<dt>Jan 2016</dt>
												<dt>Dec 2015</dt>
												<dt>Oct 2015</dt>
												<dt>Sep 2015</dt>
												<dt>Aug 2015</dt>
											</dl>
										</div>
									</div>
								</div> -->
							</div>
							<div class="tab-pane fade" id="atividades" role="tabpanel" aria-labelledby="atividades-tab">
								<div class="row">
									<div class="col-md-12">
										<div class="VivaTimeline">
											<dl id="activity-historic"></dl>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="notas" role="tabpanel" aria-labelledby="notas-tab">
								<div class="row">
									<div class="col-md-12">
										<div class="VivaTimeline">
											<dl id="notation"></dl>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="arquivos" role="tabpanel" aria-labelledby="arquivos-tab">
								...
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	<!--end container-fluid-->
	</body>
	<script>
		var dic={
			'base_url':'<?=base_url()?>'
		};
		var tempo_agora = new Date("<?=date('Y-m-d H:m:s');?>");
	</script>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" ></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" ></script>
	<script src="<?=base_url('assets/js/custom.js');?>"></script>
	<script src="<?=base_url('assets/js/jquery.eeyellow.Timeline.js');?>"></script>
	<script src="<?=base_url('assets/js/dropzone.min.js');?>"></script>
	<script src="<?=base_url('assets/js/deal.js?2018-10-18');?>"></script>
	<script src="<?=base_url('assets/js/common-functions.js');?>"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
</html>
