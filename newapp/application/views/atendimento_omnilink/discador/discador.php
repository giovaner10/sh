<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/atendimento_omnilink', 'discador.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/atendimento_omnilink', 'criar_contatos.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/atendimento_omnilink', 'listar_contatos.css') ?>">

<?php
	// permissões
	$possui_permissao_listar_contatos = $this->auth->is_allowed_block('vis_contatos_atendimento_omnilink');
	$possui_permissao_editar_contato = $this->auth->is_allowed_block('edi_contatos_atendimento_omnilink');
	$possui_permissao_remover_contato = $this->auth->is_allowed_block('rem_contatos_atendimento_omnilink');

  // Usando operador ternário para adicionar o style disabled com base na permissão
  $disabled_attr = !$possui_permissao_listar_contatos ? 'opacity: 0.5 !important; pointer-events: none !important;' : '';
?>

<div id="modalDiscador" class="modal" style="width: 320px; height: 600px; margin-left: 40%;" data-mdb-backdrop="false" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" style="border-radius: 20px !important; box-shadow: none !important;">

      <div class="modal-header-discador header-teclado">
          <ul class="nav nav-tabs" role="tablist">
              <li 
                id="li-discador"
                class="active">
                <a 
                  class="nav-link active" 
                  style="color:#1C69AD !important; margin-top: 10px; font-weight: 500 !important;"
                  id="tab-discador"
                  data-toggle="tab" 
                  href="#tabs-discador"
                >
                Discador
                </a>
              </li>

              <li
                id="li-contatos"
              >
                <a 
                  class="nav-link" 
                  id="tab-contatos" 
                  data-toggle="tab" 
                  style="color:#1C69AD !important; margin-top: 10px; font-weight: 500 !important; <?php echo $disabled_attr; ?>"
                  href="#tabs-contatos"
                >
                  Contatos
                </a>
              </li>
          </ul>

          <button class="btn btn-light" data-dismiss="modal" id="fechar-discador" aria-label="Fechar" style="width:37px; height:37px; border-radius:50px !important; margin-top:-5px; box-shadow: none !important;">
            <img class="mr-1" width="13" src="<?= base_url('media/img/new_icons/modal/icon_fechar_tipo1.svg') ?>">
          </button>
    </div>

      <div class="modal-body p-0">
        <div id="teclado">
          <div class="tab-content">
            <div class="tab-pane show active" id="tabs-discador" role="tabpanel">
              <div class="d-flex justify-content-around pb-3 pt-2">
                <div class="input-group-discador pt-2 d-flex justify-content-center">
                  <input type="tel" id="numero-telefone" class="form-control col-md-12" maxlength="15" autocomplete="off">
                  <img id="btn-backspace" width="24" height="24" src="<?= base_url('media/img/new_icons/omnicom/icon-apagar-texto.svg') ?>">
                </div>
              </div>

              <div class="d-flex justify-content-center pb-1 pt-3 row">
                <div><span class="btn btn-lg btn-light rounded-pill digito" data-digito="1">1</span></div>
                <div class="pl-4"><span class="btn btn-lg btn-light rounded-pill digito" data-digito="2">2</span></div>
                <div class="pl-4"><span class="btn btn-lg btn-light rounded-pill digito" data-digito="3">3</span></div>
              </div>

              <div class="d-flex justify-content-center pb-1 row">
                <div><span class="btn btn-lg btn-light rounded-pill digito" data-digito="4">4</span></div>
                <div class="pl-4"><span class="btn btn-lg btn-light rounded-pill digito" data-digito="5">5</span></div>
                <div class="pl-4"><span class="btn btn-lg btn-light rounded-pill digito" data-digito="6">6</span></div>
              </div>

              <div class="d-flex justify-content-center pb-1 row">
                <div><span class="btn btn-lg btn-light rounded-pill digito" data-digito="7">7</span></div>
                <div class="pl-4"><span class="btn btn-lg btn-light rounded-pill digito" data-digito="8">8</span></div>
                <div class="pl-4"><span class="btn btn-lg btn-light rounded-pill digito" data-digito="9">9</span></div>
              </div>
              
              <div class="d-flex justify-content-center pb-3 row">
                <div><span class="btn btn-lg btn-light rounded-pill digito" data-digito="*">*</span></div>
                <div class="pl-4"><span class="btn btn-lg btn-light rounded-pill digito" data-digito="0" data-subdigito="+">0</span></div>
                <div class="pl-4"><span class="btn btn-lg btn-light rounded-pill digito" data-digito="#">#</span></div>
              </div>

              <div class="d-flex justify-content-center pb-3 row" style="border-width: 1px 0 0px; margin-top: 10px; margin-bottom: 10px">
                <button id="btn-ligar" class="btn btn-lg btn-success-discador rounded-pill" disabled style="cursor: not-allowed; margin-right: 10px">
                  <span id="spinner-discador" class="spinner-border spinner-border-sm mb-1"></span>
                  <img id="img-btn-ligar" class="d-none" width="60" height="25" src="<?= base_url('media/img/new_icons/omnicom/icon-iniciar-chamada.svg') ?>">
                </button>

                <button id="btn-add-contato" class="btn btn-lg btn-success-discador rounded-pill" style="background-color:#1C69AD !important; <?php echo $disabled_attr; ?>">
                  <img id="img-btn-add-contato" class="d-none" width="60" height="25" src="<?= base_url('media/img/new_icons/omnicom/icon-add-contato.svg') ?>">
                </button>
              </div>
            </div>
          </div>
        </div>

        <?php include 'criar_editar_contato.php'; ?>
        <?php include 'listar_contatos.php'; ?>

        <div id="chamada-em-curso" style="display:none;">
          <div class="d-flex justify-content-around pt-3 pb-2" style="border-width: 0px 0 0px; background-color:#000;">
            <img width="100px" height="100px" src="<?php echo base_url('media/img/new_icons/omnicom/Icon-motorista-call.svg') ?>">
          </div>

          <div class="d-flex center-content-around pb-1" style="border-width: 0px 0 0px; background-color:#000; align-items: baseline; justify-content: center;">
						<button 
							class="btn btn-icon btn-sm btn-light hide"
							id="btn-copiar-protocolo"
							onclick="copiarProtocolo(this)"
							data-protocolo=""
						>
							<i class="fa fa-copy"></i>
						</button>
            <h6 class="protocolo-chamada info"></h6>
          </div>

          <div class="d-flex justify-content-around pb-1" style="border-width: 0px 0 0px; background-color:#000;">
            <h6 class="nome-identificador info"></h6>
          </div>

          <div class="d-flex justify-content-around pb-1" style="border-width: 0px 0 0px; background-color:#000;">
            <h4 class="numero-identificador info"></h4>
          </div>

          <div class="d-flex justify-content-around pb-1" style="border-width: 0px 0 0px; background-color:#000;">
            <h6 class="status-chamada info"></h6>
          </div>

          <div class="d-flex justify-content-around pb-1" style="border-width: 0px 0 0px; background-color:#000;">
            <h6 class="status-gravacao" style="color: #28A745;">Esta chamada está sendo gravada</h6>
          </div>

          <div class="d-flex justify-content-around pb-3" style="border-width: 0px 0 0px; background-color:#000;">
            <span class="btn btn-lg btn-danger rounded-pill btn-encerrar-chamada">
              <img width="50" height="25" src="<?php echo base_url('media/img/new_icons/omnicom/icon-terminar-chamada.svg') ?>">
            </span>
          </div>
        </div>

        <div id="recebendo-chamada" style="display:none;">
          <div class="d-flex justify-content-around pt-3 pb-2" style="border-width: 0px 0 0px; background-color:#000;">
            <img width="100px" height="100px" src="<?php echo base_url('media/img/new_icons/omnicom/Icon-motorista-call.svg') ?>">
          </div>

          <div class="d-flex justify-content-around pb-1" style="border-width: 0px 0 0px; background-color:#000;">
            <h6 class="protocolo-chamada info"></h6>
          </div>
          
          <div class="d-flex justify-content-around pb-1" style="border-width: 0px 0 0px; background-color:#000;">
            <h6 class="nome-identificador info"></h6>
          </div>

          <div class="d-flex justify-content-around pb-1" style="border-width: 0px 0 0px; background-color:#000;">
            <h4 class="numero-identificador info"></h4>
          </div>

          <div class="d-flex justify-content-around pb-1" style="border-width: 0px 0 0px; background-color:#000;">
            <h6 class="status-chamada info"></h6>
          </div>

          <div class="d-flex justify-content-around pb-1" style="border-width: 0px 0 0px; background-color:#000;">
            <h6 class="status-gravacao" style="color: #28A745;">Esta chamada está sendo gravada</h6>
          </div>

          <div class="d-flex justify-content-around pb-3" style="border-width: 0px 0 0px; background-color:#000;">
            <span id="btn-atender-chamada" class="btn btn-lg btn-success rounded-pill">
              <img width="50" height="25" src="<?= base_url('media/img/new_icons/omnicom/icon-iniciar-chamada.svg') ?>">
            </span>
            <span class="btn btn-lg btn-danger rounded-pill btn-rejeitar-chamada">
              <img width="50" height="25" src="<?= base_url('media/img/new_icons/omnicom/icon-terminar-chamada.svg') ?>">
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const permissaoListarContatos = Boolean(`<?= $possui_permissao_listar_contatos ?>`) == true;
	const permissaoEditarContato = Boolean(`<?= $possui_permissao_editar_contato ?>`) == true;
	const permissaoRemoverContato = Boolean(`<?= $possui_permissao_remover_contato ?>`) == true;
</script>

<script src="https://cdn.jsdelivr.net/npm/javascript-obfuscator/dist/index.browser.js"></script>
<script src="<?= base_url('assets/js/atendimento_omnilink/libs/jssip.min.js') ?>"></script>
<script src="<?= base_url('assets/js/atendimento_omnilink/libs/twilio.min.js') ?>"></script>

<audio id="audio-recebendo-chamada" src="https://sdk.twilio.com/js/client/sounds/releases/1.0.0/incoming.mp3" preload="auto"></audio>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink/discador', 'twilio.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink/discador', 'index.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink/discador', 'criar_editar_contato.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink/discador', 'listar_contatos.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink/discador', 'excluir_contato.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink/discador', 'ligar_contato.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/atendimento_omnilink', 'bandeiras_ddi.js') ?>"></script>