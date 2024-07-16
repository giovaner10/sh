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
						<span class="sr-only">(current)</span>
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
	<div class="container">
		<br>
		<div class="header-deal" style="background-color:#FFF; padding: 10px; border: 1px solid #DDD; border-radius: 5px;">
		<div id='calendar'></div>
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
	<script src="<?=base_url('assets/js/common-functions.js');?>"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

	<link href='<?=base_url()?>assets/css/fullcalendar.min.css' rel='stylesheet' />
	<link href='<?=base_url()?>assets/css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
	<script src='<?=base_url()?>assets/js/moment.min.js'></script>
	<script src='<?=base_url()?>assets/js/fullcalendar.js'></script>
	<script>
	var exibir=0;

	$(document).ready(function() {

		$('#calendar').fullCalendar({
			contentHeight: 900,
			ignoreTimezone: false,
		monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
		monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
		dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado'],
		dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
		buttonText: {
			today: "Hoje",
			month: "Mês",
			week: "Semana",
			day: "Dia"
		},
		header: {
			left: 'prev,next today',
			center: 'title',
			right: ''
		},
		navLinks: true, // can click day/week names to navigate views
		editable: true,
		eventLimit: true, // allow "more" link when too many events
		eventRender: function(event, element) {
				$(element).tooltip({title: event.title});             
			},
		events: [
			<?php foreach($atividades as $atividade):?>
			{
				title: "<?php echo $atividade->nome_empresa." - ".$atividade->hour?>",
				start: "<?=$atividade->date?>",
				url: "<?=base_url()?>index.php/gestor_vendas/deal/<?=$atividade->business_id ?>"
				<?php if($atividade->realized=="1"){echo ', backgroundColor: "green"';}elseif(strtotime(date('Y-m-d h:i:s'))>strtotime($atividade->date." ".$atividade->hour)){echo ', backgroundColor: "red"';}?>
			},
			<?php endforeach; ?>
		]
		}).fullCalendar('today');

	});
		function exibir_dashboard(){
			$('#div1')[0].style.display="none";
			$('#div2')[0].style.display=null;
		}
	</script>
	<style>

	#calendar {
		padding: 0;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		font-size: 14px;
		max-width: 900px;
		margin: 0 auto;
	}
	.fc-bg, .fc-bgevent-skeleton, .fc-helper-skeleton, .fc-highlight-skeleton {
		position: relative;
		height: 144px;
	}
	</style>
</html>
