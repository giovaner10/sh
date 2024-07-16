<?php
  // permissões
  $possui_permissao_editar_configuracoes = $this->auth->is_allowed_block('edi_configuracoes_portal_compras');
?>

<div style="padding: 0 20px; margin-left: 15px;">
	<div class="text-title">
		<h3><?= lang('portal_compras') ?></h3>
		<h4>
			<a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
			<?= lang('portal_compras') ?> >
			 Configuração
		</h4>
	</div>	
  <div class="row">
    <div class="col-md-3" id="menu-lateral">
      <div class="card menu-interno">
        <?php include_once('application/views/portal_compras/menu.php'); ?>
      </div>
    </div>
    <div class="col-md-9" id="conteudo">
      <div class="card-conteudo card-dados-show" style='margin-bottom: 20px; position: relative;'>
        <h3 style="align-items: center; text-align: center;">
          <b>Configuração</b>
        </h3>

        <form id="form-configuracao">	
					<div id="overlay">
						<div id="overlay-mensagem">
							<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>Carregando...
						</div>
					</div>
					<br>

					<fieldset>
						<legend> Limite de Aprovação </legend>
						<p class="leganda-label">
							Os valores abaixo são referentes ao limite de aprovação de cada responsável por centro de custo.
						</p>
						<label for="diretor_min" class="required">Diretor de Centro de Custo:</label>
							<div class="row">
								<div class="col-sm-12 col-md-6">
									<div class="form-group">
									<input type="text" class="form-control" name="diretor_min" id="diretor_min" placeholder="0,00" readonly />
									</div>
								</div>
								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<input type="text" class="form-control moeda" name="diretor_max" id="diretor_max" maxlength="10" placeholder="Digite o valor máximo" required readonly />
									</div>
								</div>
							</div>
							<label for="cfo_min" class="required">Anterior + CFO:</label>
							<div class="row">
								<div class="col-sm-12 col-md-6">
									<div class="form-group">
									<input type="text" class="form-control" name="cfo_min" id="cfo_min" placeholder="0,00" readonly />
									</div>
								</div>
								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<input type="text" class="form-control moeda" name="cfo_max" id="cfo_max" maxlength="12" placeholder="Digite o valor máximo" required readonly />
									</div>
								</div>
							</div>
							<label for="ceo_min" class="required">Anterior + CEO:</label>
							<div class="row">
								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<input type="text" class="form-control" name="ceo_min" id="ceo_min" placeholder="0,00" readonly/>
									</div>
								</div>
								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<input type="text" class="form-control" disabled placeholder="Valores Acima" />
									</div>
								</div>
							</div>
					</fieldset>
					<br>

					<fieldset style="margin-bottom:20px;">
							<legend>Aprovadores</legend>
						<p class="leganda-label">
							Adicione os usuários CEO e CFO do projeto.
						</p>
						<div class="row">
							<div class="col-sm-12 col-md-6">
								<div class="form-group mr-1">
									<span class="required" style="padding: 9px 9px 15px 0;">CEO: </span>
									<select id="aprovadores-projetos-ceo" class="form-control diretor"></select>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="form-group mr-1">
									<span class="required">CFO: </span>
									<select id="aprovadores-projetos-cfo" class="form-control diretor"></select>
								</div>
							</div>
						</div>
					</fieldset>

					<fieldset>
						<legend>Diretores de Centro de Custo </legend>
						<p class="leganda-label">
							Adicione os diretores responsáveis por cada centro de custo.
						</p>
						<div class="row">
							<div class="col-sm-12 col-md-12 form-diretores-centro-custo"style="display: flex;">
								<div class="form-group mr-1">
									
									<select id="centro-custo" class="form-control centro-custo"></select>
								</div>
								<div class="form-group mr-1">
									<select class="diretor" name="diretor" id="diretor" disabled></select>
								</div>
								<div class="form-group">
									<button type="button" title="Adicionar Cotação" class="btn btn-primary" id="btn-adicionar-centro-custo">
										Confirmar
									</button>
								</div>
							</div>
							<br>

							<div class="col-sm-12 col-md-12">
								<div class="form-group wrapperShow" style="height: 50vh">
									<div id="grid-centro-custo" class="ag-theme-alpine my-grid-show" style="height: 100%"></div>
								</div>
							</div>
						</div>

					</fieldset>
					<br>
          
          <div class="col-sm-12 col-md-12" style="padding: 0px;">
            <button type="submit" class="btn btn-success right <?= !$possui_permissao_editar_configuracoes ? 'btn_disabled' : '' ?>" id='btn-editar-configuracao'>Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
	const permissaoEditarSolicitacoes = Boolean(`<?= $possui_permissao_editar_configuracoes ?>`) == true;
	$(document).ready(function() {
		validar_campos_form('form-configuracao', salvarConfiguracao);
	});
</script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers', 'mascaras.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers', 'utils.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/portal_compras/configuracoes', 'index.js') ?>"></script>
