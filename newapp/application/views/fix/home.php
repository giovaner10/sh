<section class="content">
	<div class="row">
		<div class="col-md-8">
			<div id="myCarousel" class="carousel slide" data-ride="carousel">
				<ol class="carousel-indicators"></ol>
				<!-- Wrapper for slides -->
				<div class="carousel-inner" role="listbox">
					<?php
					if (count($banners) > 0) {
						$i = 1;
						foreach ($banners as $banner) {
							if ($i == '1') {
					?>
								<div class="item active">
									<a href="#" target="_blank" title="Clique para saber mais! link">
										<img src="<?php echo base_url("uploads/banners/$banner->file"); ?>" style="width: 100%; height:460px" title="Clique para saber mais! link">
									</a>
								</div>
							<?php } else { ?>
								<div class="item">
									<a href="#" target="_blank" title="Clique para saber mais!">
										<img src="<?php echo base_url("uploads/banners/$banner->file"); ?>" style="width: 100%; height:460px" title="Clique para saber mais!">
									</a>
								</div>
					<?php }
							$i++;
						}
					} ?>
				</div>
				<!-- Left and right controls -->
				<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev" title="Anterior">
					<span class="carousel-control icon-prev iconSlydeAction" aria-hidden="true"></span>
					<span class="sr-only">Próximo</span>
				</a>
				<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next" title="Próximo">
					<span class="carousel-control icon-next iconSlydeAction" aria-hidden="true"></span>
					<span class="sr-only">Anterior</span>
				</a>
			</div>
			<br>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Comunicados</h3>
				</div>
				<div style="overflow:scroll; height:200px;">
					<?php
					if (count($comunicados) > 0) {
						foreach ($comunicados as $comunicado) {
					?>
							<div class="box-body">
								<ul class="products-list product-list-in-box">
									<li class="item">
										<div class="product-img"><?php echo date("d/m", strtotime($comunicado->data)); ?></div>
										<div class="product-info">
											<a href="home/viewArquivo/<?php echo $comunicado->file; ?>" target="__blank" class="product-title"><?php echo $comunicado->comunicado; ?></a>
										</div>
									</li>
								</ul>
							</div>
						<?php }
					} else { ?>
						<div class="alert alert-warning">
							<?=lang('sem_comunicados')?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Ferramentas de trabalho</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<ul class="products-list product-list-in-box">
						<li class="item">
							<a href="https://outlook.omnilink.com.br" class="product-title" target="__blank">Webmail</a>
						</li>
						<li class="item">
							<a href="https://crm.omnilink.com.br" class="product-title" target="__blank">CRM</a>
						</li>
						<li class="item">
							<a href="https://servicedesk.omnilink.com.br" class="product-title" target="__blank">Service Desk</a>
						</li>
						<li class="item">
							<a href="https://tecnico.omnilink.com.br" class="product-title" target="__blank">Portal do T&eacute;cnico</a>
						</li>
					</ul>
				</div>
			</div>
			<br>
			<div class="panel text-center ">
				<div class="panel-body">
					<div class="col-sm-12" style="padding: 0px">
						<a href="usuarios/aniversariantes/" class="list-group-item col-sm-12 text-center"><br>
							<img src="<?php echo base_url('assets/images'); ?>/rh_aniver.png"><br>
							<br>
							<strong>Aniversariantes</strong><br><br>
						</a>
					</div>
					<div class="col-sm-12" style="padding: 0px">
						<a href="contatos_colaboradores/" class="list-group-item col-sm-12 text-center"><br>
							<img src="<?php echo base_url('assets/images'); ?>/rh_contatosFuncion.png"><br>
							<strong><br> Contatos Colaboradores</strong><br>
							<br>
						</a>
					</div>
				</div>
			</div>
		</div>
</section>