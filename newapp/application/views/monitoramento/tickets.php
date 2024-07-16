<link href="https://cdn.jsdelivr.net/chartist.js/0.10.1/chartist.min.css" rel="stylesheet">
<link href="<?php echo base_url('media/css/grafic-ticket.css')?>" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/chartist.js/0.10.1/chartist.min.js"></script>
<style>
#v > label{
	padding: 5px
}
#v > #one, #v > #three{
	margin-top: 10px;
}
#v > button{
	margin-left: 15px;
	height: 30px;
}
</style>
<section id="center">
<div id="v" style="display: inline-flex; padding-bottom: 25px; color: whitesmoke;">
	<label>Data inicial</label>
	<input type="text" name="init" class="calendar" id="init">
	<label>Data final</label>
	<input type="text" name="end" class="calendar" id="end">
	
		<label>Dia</label>
		<input id="one" type="radio" name="op" value="0">
		<label>Mês</label>
		<input id="three" type="radio" name="op" value="1">
		<input id="None" type="radio" name="op" value="2" checked>
	
	<button id="b" class="btn btn-small btn-design" ><i class="fa fa-search"></i></button>
</div>
</section>
<div id="grafico" class="span6 ct-chart ct-perfect-fourth"></div>
<div id="list-dash" class="row-fluid">
	<ul class="thumnails">
		<li id="dash4" class="span3"><h3>Andamento</h3><h2 id="progress"><i class="fa fa-spinner fa-pulse"></i></h2><h5>Tickets</h5></li>
		<li id="dash5" class="span3"><h3>Aberto</h3><h2 id="open"><i class="fa fa-spinner fa-pulse"></i></h2><h5>Tickets</h5></li>
		<li id="dash6" class="span3"><h3>Concluído</h3><h2 id="close"><i class="fa fa-spinner fa-pulse"></i></h2><h5>Tickets</h5></li>
	</ul>
</div>
<br>
<br>
<br>
<br>
<br>
<div id="list-dash" class="row-fluid">
	<ul class="thumnails">
		<li id="dash1" class="span3"><h3>Suporte</h3><h2 id="support"><i class="fa fa-spinner fa-pulse"></i></h2><h5>Tickets</h5></li>
		<li id="dash2" class="span3"><h3>Comercial</h3><h2 id="commercial"><i class="fa fa-spinner fa-pulse"></i></h2><h5>Tickets</h5></li>
		<li id="dash3" class="span3"><h3>Financeiro</h3><h2 id="financial"><i class="fa fa-spinner fa-pulse"></i></h2><h5>Tickets</h5></li>
	</ul>
</div>
<script type="text/javascript" src="<?php echo base_url('media/js/grafic-ticket.js')?>"></script>
