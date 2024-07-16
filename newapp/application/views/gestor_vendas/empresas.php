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
				<li class="nav-item">
					<a class="nav-link" href="<?=base_url()?>index.php/gestor_vendas">Negócios
					</a>
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
	<div class="container" style="width: 100% !important;max-width: none !important;">
		<br>
		<div class="header-deal" style="background-color:#FFF; padding: 10px; border: 1px solid #DDD; border-radius: 5px;">
			<h4 style="padding: 10px;">Empresas</h4>
			<table id="example" class="display" style="width:100%">
				<thead>
					<tr>
						<th>#</th>
						<th>Nome do Contato</th>
						<th>Empresa</th>
						<th>Telefone</th>
						<th>Email</th>
						<th>CNPJ</th>
						<th>Cidade</th>
						<th>Estado</th>
						<th>Status</th>
						<th>Segmento</th>
                        <th>Visualizar</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
   			</table>
		</div>
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
	<script src="<?=base_url('assets/js/common-functions.js');?>"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	<script>
	$(document).ready(function() {
		$('#example').DataTable({
            ajax: "<?= site_url('gestor_vendas/ajaxListEmpresas') ?>",
			responsive: true,
			"language": {
				"decimal":        "",
				"emptyTable":     "Nenhum registro encontrado",
				"info":           "Registro _START_ a _END_ de _TOTAL_ registros",
				"infoEmpty":      "0 Registros",
				"infoFiltered":   "(filtered from _MAX_ total entries)",
				"infoPostFix":    "",
				"thousands":      ",",
				"lengthMenu":     "Quantidade: _MENU_",
				"loadingRecords": "Carregando...",
				"processing":     "Processando...",
				"search":         "Pesquisar:",
				"zeroRecords":    "Nenhum registro encontrado",
				"paginate": {
					"first":      "Anterior",
					"last":       "Avançar",
					"next":       "Avançar",
					"previous":   "Início"
				}
			}
		});
	} );
	</script>
</html>
