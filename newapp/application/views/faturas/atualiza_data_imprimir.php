<!DOCTYPE html>
<html>
<head>

<title>Imprimir Fatura</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="<?php echo base_url('media')?>/css/bootstrap.css"
	rel="stylesheet">
<link href="<?php echo base_url('media')?>/css/bootstrap-responsive.css"
	rel="stylesheet">

<link
	href="<?php echo base_url('assets')?>/plugins/datepicker/css/datepicker.css"
	rel="stylesheet">

</head>
<body>

	<div class="container" style="width: 800px">
	
		<?php if(!isset($erro)):?>
		<div class="span8">
			<br>
			<div class="alert alert-error">
				<strong>ATENÇÃO!</strong> A fatura está vencida e desatualizada, para atualizar
				escolha uma nova data para pagamento.
			</div>
		</div>
		<?php if (isset($msg)):?>
		<div class="span8">
			<br>
			<div class="alert alert-warning">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong><?php echo $msg; ?></strong>
			</div>
		</div>
		<?php endif;?>

		<div class="span8">
			<div class="control-group error">
				<?php echo form_open('', array('class' => 'form-inline'), array('acao' => true))?>
				<input type="text" name="data_pag" class="span2 datepicker data"
					id="inputError" placeholder="Escolha uma data" autocomplete="off"
					id="dp1" value="<?php 
						if($this->input->get('paypal')){
							echo date('d/m/Y');
						}
						else{
							echo $this->input->post('dt_ini');
						} ?>"
					required />

				<button type="submit" id='botao_submit' class="btn btn-primary">
					<i class="icon-check icon-white"></i> Atualizar
				</button>
			</div>
		</div>
		
		<?php else:?>
			<div class="span8">
				<br>
				<div class="alert alert-error">
					<strong>ATENÇÃO!</strong> <?php echo $erro?>
				</div>
		</div>
		<?php endif;?>

		<div class="span8">
			<img alt="" src="<?php echo base_url('media/img/fatura_blur.png')?>" />
		</div>

	</div>

	<script type="text/javascript"
		src="<?php echo base_url('media')?>/js/jquery.js"></script>
	<script type="text/javascript"
		src="<?php echo base_url('media')?>/js/bootstrap.min.js"></script>
	<script type="text/javascript"
		src="<?php echo base_url('media')?>/js/bootstrap-confirm.js"></script>

	<script type="text/javascript"
		src="<?php echo base_url('assets')?>/plugins/datepicker/js/bootstrap-datepicker.js"></script>
		<script type="text/javascript" src="<?php echo base_url('media')?>/js/jquery.maskedinput-1.3.js"></script>


	<script type="text/javascript">
$(document).ready(function(){

	//$('.datepicker').focus();
	$('.data').mask('99/99/9999');
	$('.datepicker').datepicker(
			{format: 'dd/mm/yyyy'
			});
	
	
});
	
</script>
<?php if($this->input->get('paypal')):?>
	<script>
		$('body > div.container > div:nth-child(2) > div > form')[0].action += "?paypal=1";
		$("#botao_submit").trigger('click');
	</script>
<?php endif; ?>