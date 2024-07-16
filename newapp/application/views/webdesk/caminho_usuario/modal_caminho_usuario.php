<div 
  id="modal-caminho-usuario" 
  class="modal fade" 
  tabindex="-1" 
  role="dialog" 
  aria-labelledby="infoModalLabel" 
  aria-hidden="true" 
  data-backdrop="static"
>
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header header-layout">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h2 class="modal-title" id="titulo-modal-produto"></h2>
			</div>

      <div class="modal-header-discador header-teclado">
          <ul class="nav nav-tabs" role="tablist">
              <li 
                id="li-tudo"
                class="active">
                <a 
                  class="nav-link active" 
                  style="color:#1C69AD !important; margin-top: 10px; font-weight: 500 !important;"
                  id="tab-tudo"
                  data-toggle="tab" 
                  href="#tabs-tudo"
                >
                Tudo
                </a>
              </li>
          </ul>
		  </div>

      <?php include 'application/views/webdesk/caminho_usuario/caminho_usuario.php'; ?>

      

	</div>
</div>