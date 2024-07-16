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
		<link rel="stylesheet" href="<?=base_url('/assets/css/master.css');?>">
		<link rel="stylesheet" href="<?=base_url('/assets/css/custom.css');?>">
	</head>
	<body>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<!-- Navbar content -->
			<a class="navbar-brand" href="<?=base_url()?>index.php/gestor_vendas"><b>CRM de Vendas</b></a>
			<form class="form-inline">
				<input class="form-control mr-sm-2" type="search" placeholder="Pesquisa" aria-label="Search" style="border:1px solid #444; background-color:#aaa;">
			</form>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavDropdown">
				<ul class="navbar-nav mr-auto">
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
			<br>
			<div class="row">
				<div class="col-2">
					<ul class="list-group">
						<li class="list-group-item list-link" onclick="location.href='../user'">Pessoal</li>
						<li class="list-group-item list-link" onclick="location.href='#'">Pagamentos</li>
						<li class="list-group-item active list-link" onclick="location.href='company'">Funis</li>
						<li class="list-group-item list-link" onclick="location.href='../user/manage'">Gerenciar usuários</li>
						<li class="list-group-item list-link" onclick="location.href='#'">Importar dados</li>
						<li class="list-group-item list-link" onclick="location.href='#'">Exportar dados</li>
					</ul>
				</div>
				<div class="col-10">
					<div class="row">
						<div class="col">
							<h3>Customizar etapas de vendas</h3>
						</div>
						<div class="col">
							<button class="btn btn-success btn-sm float-right" type="button" name="button" data-toggle="modal" data-target="#funilModalCenter">Adicionar novo funil</button>
							<div class="modal fade" id="funilModalCenter" tabindex="-1" role="dialog" aria-labelledby="funilModalCenterTitle" aria-hidden="true">
								<div class="modal-dialog modal modal-dialog-centered" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="funilModalCenterTitle">Adicionar novo funil</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="container-fluid">
												<form class="" action="#" method="get">
													<div class="row">
														<div class="col">
															<div class="form-group">
																<label class="sr-only" for="tituloFunilFormInputGroup"><b>Titulo do Funil</b></label>
																<div class="input-group mb-2">
																	<div class="input-group-prepend">
																		<div class="input-group-text"><i class="fas fa-font"></i></div>
																	</div>
																	<input type="text" class="form-control" id="tituloFunilFormInputGroup" placeholder="Titulo do Funil">
																</div>
															</div>
														</div><!--modal col -->
													</div>
												</form>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" id="cancel-company" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
											<button type="button" id="save-company" class="btn btn-success">Salvar</button>
										</div>
									</div>
								</div>
							</div><!--End Modal Funil-->
						</div>
					</div>
					<div class="row">
						<div class="col">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
							</ul>
							<div class="tab-content p-3" id="myTabContent">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>

	<script>
		var dic={
			'base_url':'<?=base_url()?>'
		};
	</script>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="<?=base_url('assets/js/custom.js');?>"></script>
	<script src="<?=base_url('assets/js/company.js?2018-10-08');?>"></script>
</html>
