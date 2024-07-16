<div id="guia-criar-editar-contato" class="tab-pane" style="display: none">
  <div class="d-flex justify-content-around pb-3 pt-2" style="border-width: 1px 0 0px;">
      <form id="form-criar-editar-contato">
          <h4 id="titulo-modal-contato">
              Criar Contato
          </h4>

          <label class="label_input required" for="contato-nome">
              Nome:
          </label>
          <input class="form-control" type="text" name="nome" id="contato-nome" required maxlength="60">

          <label class="label_input" for="contato-email">
              E-mail:
          </label>
          <input class="form-control" type="email" name="email" id="contato-email" maxlength="240">

          <label class="label_input  required" for="contato-empresa">Empresa:</label>
          <input class="form-control" type="text" name="empresa" id="contato-empresa" required maxlength="60">


          <div id="container-telefone-adicionar-contato">
            <label  class="label_input  required" for="numero-telefone-criar-editar-contato">
                Telefone:
            </label>
            <input type="tel" id="numero-telefone-criar-editar-contato" name="telefone" class="form-control col-md-12" maxlength="15" autocomplete="off" required>
            <img id="btn-backspace-contatos" width="24" height="24" src="<?= base_url('media/img/new_icons/omnicom/icon-apagar-texto.svg') ?>">
          </div>


          <div class="pt-2 mt-1" id="container-botoes-discador" style="border-width: 1px 0 0px;">
            <button id="botao-salvar-contato" data-acao="cadastrar" style="background-color: #28a745 !important" type="submit" class="btn btn-success">
              SALVAR
            </button>
            <button id="botao-cancelar-contato" type="reset" class="btn btn-light btn-cancelar">
              CANCELAR
            </button>
          </div>
      </form>
  </div>
</div>