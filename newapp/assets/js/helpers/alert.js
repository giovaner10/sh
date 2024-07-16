
function showAlert(type, message) {
  const alertas_notification = document.querySelector('#alertas-notification');

  $("#alertas-notification").prepend(htmlAlert(type, message));
  let novo = alertas_notification.children[0];
  novo.addEventListener("animationend", listener, true);
}

function htmlAlert(type, message) {
  let classe = '', titulo = '';

  if (type === "success") {
    classe = 'success';
    titulo = 'Sucesso';
  }
  else if (type === "warning") {
    classe = 'warning';
    titulo = 'Aviso';
  }
  else if (type === "error") {
    classe = 'error';
    titulo = 'Erro';
  }

  if (classe.length > 0) {
    return /*html*/`
			<div class="shadow alerta-notification ${classe} show" style="">
				<h3 class="alerta-notification-header ${classe}">${titulo}!</h3>
				<h4 class="alerta-notification-body ${classe}" style="padding-right: 5px;">${message}</h4>
				<div class="bar-alerta ${classe}"></div>
			</div>`;
  }

  return;
}

function listener(event) {
  $(this).remove();
}

/**
 * Diálogo de confirmação de ação padrão
 * 
 * @param {*} titulo Mensagem do titulo, preferencialmente texto puro
 * @param {*} texto Mensagem de confirmação principal, preferencialmente texto puro
 * @param {*} htmlCancelar Botão cancelar personalizado, substitui o original
 * @param {*} htmlConfirmar Botão confirmar personalizado, substitui o original
 * @param {*} htmlHeader Header personalizado, substitui o original
 */
async function confirmarAcao(opcoes = {
  titulo: undefined,
  texto: undefined,
  htmlCancelar: undefined,
  htmlConfirmar: undefined,
  htmlHeader: undefined
}) {
  return new Promise(resolve => {
    const { titulo, texto, htmlCancelar, htmlConfirmar, htmlHeader } = opcoes;

    const headerPadrao = /* HTML */`<div>
				<img src="${BASE_URL}media/img/new_icons/icon_aviso_colorido.svg">
			</div>
			<div>
				<h4 style="font-weight:bold;">${titulo || lang.salvando_alteracoes}</h4>
			</div>`;

    const cancelarPadrao = /* HTML */`<button class="btn btn-light" style="width:100%; border-radius: 9px;">${lang.cancelar}</button>`;
    const confirmarPadrao = `<button class="btn btn-success" style="width:100%; border-radius: 9px;">${lang.confirmar}</button>`;

    const html = /* HTML */`<div class="modal fade" id="modal-confirmacao" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-sm">
				<div class="modal-content shadow" style="border-radius: 20px;">
					<div class="modal-body" style="margin-bottom: -1rem;">
						<div class="form-group" style="margin-top: 1rem; text-align:center">
							${htmlHeader == undefined ? headerPadrao : htmlHeader}
						<div>
							<h6 style="font-weight:normal; color:#909090">${texto || lang.mensagem_confirmar_alteracao}</h6>
						</div>
						<div class="row" style="margin-top: 1.5rem; padding: 0 8px">
							<div class="col-xs-12 col-sm-6" style="padding: 0 4px">
								<span id="btn-cancelar-acao">${htmlCancelar || cancelarPadrao}</span>
							</div>
							<div class="col-xs-12 col-sm-6" style="padding: 0 4px">
								<span id="btn-confirmar-acao">${htmlConfirmar || confirmarPadrao}</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>`;

    $("body").append(html);
    $("#modal-confirmacao").modal("show");

    $("#btn-cancelar-acao").click(() => {
      $("#modal-confirmacao").modal("hide");
      resolve(false);
    });
    $("#btn-confirmar-acao").click(() => {
      $("#modal-confirmacao").modal("hide");
      resolve(true);
    });
    $("body").on("hidden.bs.modal", "#modal-confirmacao", () => {
      $("#modal-confirmacao").remove();
      if (document.querySelectorAll('.modal.show').length > 0) {
        document.querySelector('body').classList.add('modal-open');
      }
      resolve(false);
    });
  });
}
