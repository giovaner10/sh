<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("arquivos_iso") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('departamentos') ?> >
        <?= lang('controle_de_qualidade') ?> >
        <?= lang('arquivos_iso') ?>
    </h4>
</div>

<?php
$data = ['lista_dados' => $lista_dados]
?>

<script>
    const dados = <?= json_encode($lista_dados) ?>
</script>


<div class="row" style="margin: 15px 0 0 15px;">
<div class="col-md-12" id="conteudo">
        <div class="card-conteudo card-politica" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b><?= lang('arquivos_iso') ?>: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
						<a class="btn btn-primary" href="<?php echo site_url('cadastros/listar_iso'); ?>" target="_blank"><i class="fa fa-plus" aria-hidden="true"></i> Incluir</a>
                    </div>
                </div>
            </h3>
            <div style="position: relative;">
                <div class="wrapperPolitica">
                    <div id="tablePolitica" class="ag-theme-alpine my-grid-politica">
						<?php if (count($lista_dados) > 0) {	?>
							<div>
								<div class="row">
									<div class="box box-info">
										<div class="box-body">
											<?php foreach ($lista_dados as $lista_dado) { ?>
												<a href="<?php echo base_url("uploads/iso/$lista_dado->file"); ?>" download="<?php echo $lista_dado->file; ?>" class="list-group-item" target="_blank">&nbsp;&nbsp;<span class="glyphicon glyphicon-file  btn-xs"></span><?php echo $lista_dado->descricao ?></a>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						<?php } else { ?>
							<div class="alert alert-warning">
								Não há arquivo da ISO cadastrados no momento.
							</div>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">