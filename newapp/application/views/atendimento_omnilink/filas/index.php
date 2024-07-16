<?php
	// permissões
	$possui_permissao_cadastrar_filas = $this->auth->is_allowed_block('cad_filas_atendimento_omnilink');
	$possui_permissao_editar_filas = $this->auth->is_allowed_block('edi_filas_atendimento_omnilink');
	$possui_permissao_remover_filas = $this->auth->is_allowed_block('rem_filas_atendimento_omnilink');
	$possui_permissao_vincular_agentes_na_fila = $this->auth->is_allowed_block('cad_vincular_agentes_atendimento_omnilink');
?>

<div style="padding: 0 20px; margin-left: 15px;">
  <?php include_once('application/views/atendimento_omnilink/cabecalho.php'); ?>

  <div id="loading">
      <div class="loader"></div>
  </div>
  
  <div class="row">
    <div class="col-md-3" id="menu-lateral">
      <div class="card menu-interno">
        <?php include_once('application/views/atendimento_omnilink/menu.php'); ?>
      </div>
    </div>
    <div class="col-md-9" id="conteudo">
      <div class="card-conteudo card-dados-show" style='margin-bottom: 20px; position: relative;'>
        <h3 style="align-items: center; text-align: center;">
          <b>Filas</b>
          <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">            
            <button 
							type="button" 
							class="btn btn-primary" 
							title="Nova Fila" <?= $possui_permissao_cadastrar_filas ? 'id="btn-nova-fila"' : 'disabled' ?> style="margin-right: 10px;">
							Nova Fila
						</button>
						
						<div style="display: flex;">
							<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
								aria-haspopup="true" aria-expanded="false" style="margin-right: 10px;">
								<?= lang('exportar') ?>
							</button>
							<div class="dropdown-menu dropdown-exportar dropdown-menu-right" aria-labelledby="dropdownMenuButton">
								<button onclick="gerarRelatorio('csv', 'Filas', '', 'acoes')" class="dropdown-item item-exportar" style="color: #1C69AD;" href="#"><img class="mr-3" src="<?= base_url('media/img/new_icons/relatorios/csv.svg') ?>" />CSV</button>
								<button onclick="gerarRelatorio('excel', 'Filas', '', 'acoes')" class="dropdown-item item-exportar" style="color: #1A9A20;" href="#"><img class="mr-3" src="<?= base_url('media/img/new_icons/relatorios/excel.svg') ?>" />Excel</button>
								<button onclick="gerarRelatorio('pdf', 'Filas', 'Relatório de Filas - ATENDIMENTO OMNILINK', 'acoes')" class="dropdown-item item-exportar" style="color: #EA1A1A;" href="#"><img class="mr-3" src="<?= base_url('media/img/new_icons/relatorios/pdf.svg') ?>" />PDF</button>
							</div>
						</div>

            <button class="btn btn-light" data-toggle="tooltip" data-placement="left" 
              title="<?= lang('expandir_grid') ?>" 
              style="border-radius:6px; padding:5px;" 
              onclick="expandirGrid()"
            >
              <img id="img_grid_expandir" class="img-expandir" src="<?= base_url('assets/images/icon-filter-hide.svg') ?>" style="width: 25px;">
            </button>
          </div>
        </h3>
        <div>
          <select class="form-control" onchange="selecionarQuantidadePorPagina(this)" style="width: 100px; float: left; margin-top: 10px;">
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <input type="text" id="pesquisarNaAggrid" class="form-control" placeholder="Pesquisar"
            style="max-width: 250px; margin-bottom: 10px; margin-top: 10px; float: right;" oninput="pesquisarNaAggrid(this)">
        </div>
        <div class="wrapperShow" style="height: 70vh">
          <div id="grid-filas" class="ag-theme-alpine my-grid-show" style="height: 100%">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
	const permissaoEditarFilas = Boolean(`<?= $possui_permissao_editar_filas ?>`) == true;
	const permissaoRemoverFilas = Boolean(`<?= $possui_permissao_remover_filas ?>`) == true;
	const permissaoVincularAgentesNaFila = Boolean(`<?= $possui_permissao_vincular_agentes_na_fila ?>`) == true;

	$(document).ready(function () {
		validar_campos_form('form-fila', salvarFila);
		validar_campos_form('form-vincular-agentes', salvarVinculoAgentes);
	});
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/helpers', 'utils.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers', 'mascaras.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink/filas', 'index.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink/filas', 'cadastrar.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink/filas', 'editar.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink/filas', 'remover.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink/filas', 'vincular_agentes.js') ?>"></script>