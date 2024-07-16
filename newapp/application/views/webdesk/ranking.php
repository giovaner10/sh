<link href="<?php echo base_url('newAssets') ?>/css/jquery-ui.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url('newAssets') ?>/js/ranking.js"></script>
<script type="text/javascript" src="<?php echo base_url('newAssets') ?>/js/jquery-ui.min.js"></script>
<style>
	.th10 {
		width: 10%;
		text-align: center !important;
	}
	table td {
		vertical-align: middle !important;
	}
	.tdImportante {
		float: left;
		width: 10%;
		text-align: center !important;
	}
	#infors hr {
		margin: 5px;
	}
	a {
	    cursor:pointer;
	}
	.modal label {
		font-size: 17px;
		margin-bottom: 9px;
	}
	.gravaDados {
	    margin: auto;
	    padding-top: 10px;
	    text-align: center;
	    width: 150px;
	    height: 150px
	}
	.blem {
		color: #e0221f;
	}
	.blem:hover {
		color: #e0221f;
	}
	#modal_ranking {
		display: none;
	}
	.container-fluid {
		width: 96.1%;
	}
</style>
	<h3>Tickets Ranking</h3>
	<div class="wrapper pull-right" style="margin-top: -31px">
		<input type="text" name="filtro" id="filtro" placeholder="Nome do Cliente" readonly>
		<a id="pesquisa" tipe="button" class="btn btn-default" style="margin-top: -9px" disabled>Pesquisar</a>
	</div>
<br style="clear:both" />
<hr class="featurette-divider">
<div class="span9" style="float: none; margin-left: auto; margin-right: auto;">
	<div>
		<table class="table table-bordered table-hover">
			<thead>
				<tr class="tr-topo">
					<th class="th10">Pos.</th>
					<th>Nome do Cliente</th>
					<th class="th10">Quantidade</th>
					<th  class="th10"">Visualizar</th>
				</tr>
			</thead>
			<tbody class="tb-center">
				
			</tbody>
		</table>
		<div id="infors" style="margin-top: 10px">
		<!-- informações preenchidas com o javascript </newAssets/js/ranking.js> -->
		</div>
		<div id="botao" class="pull-right" hidden>
			<a id="plus"><i class="fa fa-angle-double-down" aria-hidden="true"></i> Mostrar mais</a>
		</div>
		<br style="clear:both" />
	</div>
</div>
<hr class="featurette-divider">
<div id="modal_ranking" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
          		<h3 class="modal-title">Detalhes</h3>
			</div>
			<div class="modal-body">
				<div id="detalhes" hidden>
					<div style="text-align: center;">
						<label id="pessoa"></label>
					</div>
					<label id="quant"></label>
					<label id="mes"></label>
					<label id="drogado"></label>
					<div style="text-align: right;">
						<p>Estado: <i class="fa fa-hand-o-down" aria-hidden="true"></i></p>
						<label id="status"></label>
					</div>
					</div>
				<div id="loading" class="gravaDados">
					<img src="<?php echo base_url('newAssets/imagens/loader.gif') ?>" width="80">
					<h4>Carregando dados...</h4>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>
