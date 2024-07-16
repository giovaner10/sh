<div style="margin: 10px;">
	<?php
	include(dirname(__FILE__) . '/../../../componentes/comum/comum.php');
	tituloPaginaComponente(lang('sobre_a_empresa'), site_url('Homes'), lang('a_empresa'), lang('sobre'));
	?>

	<?php if ($sobre) : ?>


		<main class="container container-sobre">
			<div class="item sobre">
				<h2 class="titulo-principal">Conheça a Omnilink
				</h2>
				<p>
				<p>Criada em junho de 1998, a Omnilink &eacute; uma das l&iacute;deres em sistemas de telem&aacute;tica para controle de ve&iacute;culos e gest&atilde;o de frotas no Brasil.</p>
				<p class="text-justify" style="text-align: justify;">Sociedade an&ocirc;nima de capital fechado, a Omnilink nasceu da fus&atilde;o entre as marcas Omnilink e Graber Rastreamento. A empresa oferece tecnologia para rastreamento, gest&atilde;o de frotas, gest&atilde;o de risco, telemetria e apoio log&iacute;stico, atendendo diversos tipos de clientes desde pessoas f&iacute;sicas, profissionais aut&ocirc;nomos, seguradoras, montadoras, transportadoras a grandes varejistas e frotistas.</p>
				<p class="text-justify" style="text-align: justify;">A Omnilink est&aacute; presente em todo o Brasil, a partir de sua sede localizada em Alphaville, e mais cinco filiais que respondem pelas &aacute;reas comercial e de assist&ecirc;ncia t&eacute;cnica em S&atilde;o Paulo, Minas Gerais, Rio Grande do Sul, Paran&aacute;, Rio de Janeiro, Esp&iacute;rito Santo, Centro-Oeste e Norte. Al&eacute;m disso, a empresa possui uma unidade fabril em Santa Rita do Sapuca&iacute;, Minas Gerais.</p>
				<p class="text-justify" style="text-align: justify;">Para conhecer nossos produtos, <a href="http://www.omnilink.com.br/" target="_blank" rel="noopener">clique aqui</a>.</p>
				</p>
			</div>
			<div class="item missao">
				<h2>Missão</h2>
				<p><?= $sobre->missao; ?></p>
			</div>
			<div class="item visao">
				<h2>Visão</h2>
				<p><?= $sobre->visao; ?></p>
			</div>
			<div class="item valores">
				<h2>Valores</h2>
				<p class="ultimo-p"> Compromisso em servir nossos clientes. Espírito de equipe e paixão em tudo que fazemos. Liderança pelo exemplo. Ética em todas nossas relações. Entregar excelentes resultados para os acionistas.
				</p>
			</div>
		</main>

	<?php endif; ?>
</div>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/sobre', 'sobre.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">

