<style>
    #linkAlert {
        background-color: #d9534f;
    }

    .tooltip-inner {
        text-align: left;
        font-size: 11px;
        padding: 5px;
    }
</style>
<div id="addAutoajuda" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form action="" id='formAddAutoajuda'>
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="status" id="status">
                <div class="modal-header header-layout">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleAutoAjuda">Cadastrar Autoajuda</h3>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class='row'>
                            <div class="col-md-7 input-container form-group">
                                <div class="flex" style="display: flex; direction: row; justify-content: space-between; align-items: center;">
                                    <label for="title">Título: <span class="text-danger">*</span></label>
                                    <i class="fa fa-exclamation-circle color-primary" data-toggle="tooltip" data-placement="left" title="Utilize um título curto e objetivo, em torno de duas ou três palavras."></i>
                                </div>
                                <input class="form-control" type="text" name="title" id="title" placeholder="Digite o título do autoajuda" maxlength="100" />
                                <div id="titleAlert" class="alertValidation" style="display:none; color: red; margin-top: 5px;"></div>
                            </div>
                            <div class="col-md-5 input-container form-group">
                                <div class="flex" style="display: flex; direction: row; justify-content: space-between; align-items: center;">
                                    <label for="resource">Slug: <span class="text-danger">*</span></label>
                                    <i class="fa fa-exclamation-circle color-primary" data-toggle="tooltip" data-placement="left" title="O slug é uma parte da URL que você pode utilizar para se referir à tela em que deseja associar o tópico de ajuda. Geralmente depois do ”index.php/”.Ex https://gestor.com/index.php/v1/usuario"></i>
                                </div>
                                <input class="form-control" type="text" name="resource" id="resource" placeholder="Digite a tela que você que inserir" maxlength="100" />
                                <div id="resourceAlert" class="alertValidation" style="display:none; color: red; margin-top: 5px;"></div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-12 input-container form-group">
                                <div class="flex" style="display: flex; direction: row; justify-content: space-between; align-items: center;">
                                    <label for="content">Descrição: <span class="text-danger">*</span></label>
                                    <i class="fa fa-exclamation-circle color-primary" data-toggle="tooltip" data-placement="left" title="Nesse campo você irá inserir uma descrição para a autoajuda. Você poderá formatar o texto dentro desse campo como desejar, seja utilizando listas, tipos de fonte ou em HTML."></i>
                                </div>
                                <textarea class="form-control" id="content" name="content"></textarea>
                                <div id="contentAlert" style="display:none; color: red; margin-top: 5px;"></div>
                            </div>
                        </div>
                        <div class="flex" style="display: flex; direction: row; align-items: center; margin-bottom: 10px; gap: 10px;">
                            <h3 class="mt-4 modal-title">Configuração de Links</h3>
                            <i class="fa fa-exclamation-circle color-primary" data-toggle="tooltip" data-placement="right" title="Os links dão acesso à outros tópicos relacionados, páginas internas ou externas."></i>
                        </div>
                        <div id="linksContainer">
                            <div class="row" id="firstLinkRow">
                                <div class="col-md-3 input-container form-group">
                                    <label for="usuario">Titulo do link: </label>
                                    <input class="form-control" type="text" name="tituloDoLink" id="tituloDoLink" placeholder="Insira o titulo do link" maxlength="50" />
                                    <div id="titleLinkAlert" class="alertValidation" style="display:none; color: red; margin-top: 5px;"></div>
                                </div>
                                <div class="col-md-3 input-container form-group">
                                    <label for="url">URL:</label>

                                    <input class="form-control" type="text" name="url" id="url" placeholder="Insira a url" maxlength="200" />
                                    <div id="urlAlert" class="alertValidation" style="display:none; color: red; margin-top: 5px;"></div>

                                </div>
                                <div class="col-md-3 input-container form-group">
                                    <label for="tipoDoLink">Tipo do link:</label>
                                    <select class="form-control" name="tipoDoLink" id="tipoDoLink">
                                        <option value="">Escolha o tipo do link</option>
                                        <option value="internal">Pagina interna</option>
                                        <option value="external">Pagina externa</option>
                                    </select>
                                    <div id="typeLinkAlert" class="alertValidation" style="display:none; color: red; margin-top: 5px;"></div>
                                </div>
                                <div class="col-md-3 input-container form-group">
                                    <label for="destino">Destino:</label>
                                    <div style="align-items: center; display: flex; flex-direction: row; gap: 10px;">
                                        <select style="width: 90%;" class="form-control" name="destino" id="destino">
                                            <option value="">Insira o destino</option>
                                            <option value="_self">Mesma aba</option>
                                            <option value="_blank">Nova aba</option>
                                        </select>

                                        <button type="button" class="btn btn-primary btn-add" id="addLink">
                                            +
                                        </button>
                                    </div>
                                    <div id="destinyLink" class="alertValidation" style="display:none; color: red; margin-top: 5px;"></div>
                                </div>

                            </div>
                            <div id="linkAlert" class="alert alert-warning collapse" role="alert">
                                Você não pode adicionar mais de 5 links.
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="footer-group" style="padding-top: 25px;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary" id='btnSalvarAutoajuda'>Salvar</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>