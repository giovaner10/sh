<?php
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $userIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $userIP = $_SERVER['REMOTE_ADDR'];
}
?>

<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente("Instaladores", site_url('Homes'), lang('cadastros'), "Instaladores");
?>


<div id="loading">
    <div class="loader"></div>
</div>

<div id="modalLoadingMessage" class="modalLoadingMessageAct">
    <div class="loader" style="font-size: 90px; color: #06a9f6; margin-top: 25%;"></div>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-3">
        <div id="filtroBusca" class="card" style="margin-bottom: 50px;">
            <h4 style="margin-bottom: 0px !important;">Filtrar Dados:</h4>

            <form id="formBusca">
                <div class="form-group filtro">
                    <div class="input-container" style="width:100%;">
                        <label for="statusPayment">Status de Pagamento:</label>
                        <select name="statusPayment" style="width: 100%;" id="statusPayment" class="form-control">
                            <option value="" disabled selected>Selecione um Status</option>
                            <option value="0">Pago</option>
                            <option value="1">Pendente</option>
                        </select>
                    </div>
                    <div class="input-container nome" style="width:100%;">
                        <label for="nomeCompleto">Nome:</label>
                        <input class="form-control" type="text" name="nomeCompleto" id="nomeCompleto" style="width: 100%;" placeholder="Informe o nome do instalador">
                    </div>
                    <div class="input-container" style="width:100%;">
                        <label for="cidade">Cidade:</label>
                        <input class="form-control" type="text" name="cidade" id="cidade" style="width: 100%;" placeholder="Informe a cidade">
                    </div>
                    <div class="input-container" style="width: 100%;">
                        <label for="estado">Estado:</label>
                        <select name="estado" id="estado" class="form-control" style="width: 100%;">
                            <option value="" disabled selected>Selecione um estado</option>
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">Paraná</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                        </select>
                    </div>

                    <div class="button-container">
                        <button class="btn btn-success search-button" id="BtnPesquisar" type="button" style="width:100%;"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
                        <button class="btn btn-default clear-search-button" id="BtnLimparPesquisar" type="button" style="margin-top: 10px;width:100%;"><i class="fa fa-leaf" aria-hidden="true"></i>
                            Limpar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9" id="conteudo">
        <div class="card-conteudo card-relatorio-instalacao" style='margin-bottom: 20px; position: relative;'>
            <div class="tablePageHeader">
                <h3>Gerenciamento de Instaladores: </h3>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; gap: 10px; align-items: center; margin-top:15px; margin-bottom:15px;">
                    <div class="dropdown" id="dropdown_exportar">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 36.5px;">
                            <?= lang('exportar') ?> <span class="caret" style="margin-left: 5px;"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao" style="min-width: 100px; top: 62px; height: 91px; left: -1px !important;">
                        </div>
                    </div>

                    <button style="height: 36.5px;" class="btn btn-primary" id="cadastrarInstaladorBtn">
                        <i class="fa fa-plus"></i>
                        Cadastrar
                    </button>

                    <button class="btn btn-light btn-expandir" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img class="img-expandir" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button>

                </div>
            </div>

            <div id="notFoundMessage" style="display: none;">
                <h5>Nenhum dado encontrado para a pesquisa feita</h5>
            </div>
            <div class="registrosDiv">
                <select id="paginationSelect" class="form-control" style="float: left; width: auto; height: 34px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
            </div>
            <div style="position: relative;" id="tabelaInstalacoes">
                <div class="wrapper">
                    <div id="table" class="ag-theme-alpine my-grid-instaladores" style="height: 500px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE CONFIRMAÇÃO -->
<div id="modalConfirmacaoBloqueioInstalador" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Confirmação</h4>
            </div>
            <div class="modal-body">
                <p style="text-align: center;" id='mensagemConfirmacao'></p>
            </div>
            <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                <button id="btnConfirmarAcaoUsuario" class="btn btn-success" type="button">Confirmar</button>
                <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-service-order" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Ordens de Serviço</h3>
            </div>
            <div class="modal-body">
                <div class="modal-body-container">
                    <ul class="nav custom-tabs" id="serviceOrderTabs">
                        <li class="nav-item">
                            <a class="nav-link active-tab" href="#" data-target="#tab-service-order-table">Tabela de Ordens de Serviço</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-target="#tab-cost-per-type">Preço Médio</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane custom-tab-pane" id="tab-service-order-table">
                            <div class="col-md-12">
                                <div class="registrosDiv">
                                    <select id="paginationSelectServiceOrder" class="form-control" style="float: left; width: auto; height: 34px;">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por página</h6>
                                </div>
                                <div id="tableServiceOrder" class="ag-theme-alpine my-grid-service-order" style="height: 250px; width: 100%;"></div>
                            </div>
                        </div>
                        <div class="tab-pane custom-tab-pane" id="tab-cost-per-type" style="display:none;">
                            <div class="chartWrapper">
                                <div id="chartContainer" style="max-width: 807px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalGerenciamentoDeDocumentoDoInstalador" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Gerenciar Documentos do Instalador</h3>
            </div>
            <div class="modal-body">
                <div class="modal-body-container">
                    <h4 class="subtitle">Documentos do Instalador</h4>
                    <div class="col-md-12" style="margin-bottom: 20px;">
                        <div style="display: flex; flex-direction: row; justify-content: space-between;">
                            <div style="display: flex; flex-direction: row; margin-bottom: 5px;">
                                <select id="paginationSelectDocsInstalador" class="form-control" style="width: auto; height: 34px;">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <h6 class="label_input" style="font-weight:normal; margin-left: 5px;">Registros por
                                    página</h6>
                            </div>
                            <div style="display: flex; flex-wrap: wrap; align-items: center;">
                                <button class="btn btn-primary" type="button" id="btnModalAddDocumento" style="margin-bottom: 5px;">Adicionar</button>
                            </div>
                        </div>

                        <div style="position: relative;">

                            <div id="tableDocsInstalador" class="ag-theme-alpine my-grid-documentos-funcionario" style="height: auto;">
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalEnviarDocumentoInstalador" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <div>
                    <h3 class="modal-title titleDocumento" id="titleDocInst">Adicionar Documento</h3><br>
                </div>
            </div>

            <div class="modal-body">

                <div class="container col-md-12">
                    <form method="post" id="documentoInstaladorForm">
                        <div class="form-group" style="gap: 5px;">
                            <div class="input-container input-documento">
                                <label for="tituloDoc">Título do Documento <span class="text-danger">*</span></label>
                                <input class="form-control" placeholder="Digite o nome do documento" name="tituloDoc" id="tituloDoc" type="text">
                            </div>

                            <div class="input-container input-documento">
                                <label for="arquivoItens">Anexo do Documento <span class="text-danger">*</span></label>
                                <input class="form-control" name="arquivoItens" id="arquivoItens" type="file" accept="image/*">
                            </div>
                        </div>
                    </form>
                </div>

            </div>

            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button class="btn btn-success" id="btnSendDocumento" type="button">Enviar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalVisualizarDocumentoInstalador" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <div>
                    <h3 class="modal-title">Visualização de Documento</h3>
                </div>
            </div>

            <div class="modal-body" style="position: relative;">
                <div class="modal-body-container" style="margin-bottom: 20px; ">
                    <div class="container imgContent">

                        <div id="div-img" class="div-img">
                            <div class="pswp-gallery pswp-gallery--single-column" id="documentImageGallery" title="Clique para expandir">
                                <h4 id="nomeDocumento">Nome do Documento</h4>
                                <a href="#" id="imgDocumentoInstalador" target="_blank">
                                    <img src="<?= versionFile('assets/js/libraries/imageViewer/resources', 'placeholder.jpg') ?>" alt="Document Image" style="max-width: 500px; max-height: 400px;" />
                                </a>
                            </div>
                        </div>
                        <div id="div-img-mensagem" class="div-img-mensagem" style="display: none;">
                            <h4 class="subtitle">Imagem não encontrada</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="footer-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success" id="documentDownloadBtn">Baixar</button>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="modalCadastroInstalador" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="cadastrarInstaladorTitulo">Cadastrar Instalador</h3>
            </div>
            <div class="modal-body">
                <div class="modal-body-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab1" data-toggle="tab">Dados Pessoais</a></li>
                        <li><a href="#tab3" data-toggle="tab">Contato</a></li>
                        <li><a href="#tab4" data-toggle="tab">Endereço</a></li>
                        <li><a href="#tab5" data-toggle="tab">Valores</a></li>
                        <li><a href="#tab6" data-toggle="tab">Dados Bancários</a></li>
                    </ul>
                    <form name="myForm" class="formulario" id="signup">
                        <div class="tab-content" style="padding-top: 15px;">
                            <div class="tab-pane active" id="tab1">
                                <div class="row">
                                    <div class="col-md-12 form-input-container">
                                        <label for="cNome" class="control-label">Nome:</label>
                                        <input type="text" name="nome" id="cNome" class="form-control letras" placeholder="Insira o nome do instalador">
                                    </div>
                                    <div class="col-md-12 form-input-container">
                                        <label for="cSobrenome" class="control-label">Sobrenome:</label>
                                        <input type="text" name="sobrenome" id="cSobrenome" class="form-control letras" placeholder="Insira o sobrenome do instalador">
                                    </div>
                                    <div class="col-md-12 form-input-container">
                                        <label for="data_nascimento" class="control-label">Data de Nascimento:</label>
                                        <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" placeholder="Insira a data de nascimento do instalador" required>
                                    </div>
                                    <div class="col-md-12 form-input-container">
                                        <label class="control-label">Documento:</label>
                                        <div class="form-group" style="margin-bottom: 0px !important;">
                                            <div class="radio-inline">
                                                <label><input type="radio" id="radioCPF" value="cpf" name="radioDoc" checked> CPF</label>
                                            </div>
                                            <div class="radio-inline">
                                                <label><input type="radio" id="radioCNPJ" value="cnpj" name="radioDoc">
                                                    CNPJ</label>
                                            </div>
                                        </div>
                                        <div id="divCPF" class="form-group">
                                            <input type="text" class="form-control cpf" id="cCPF" name="cpf" placeholder="000.000.000-00">
                                        </div>
                                        <div id="divCNPJ" class="form-group" style="display: none;">
                                            <input type="text" class="form-control" id="cCNPJ" name="cnpj" placeholder="00.000.000/0000-00">
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-input-container">
                                        <label for="cRg" class="control-label">RG:</label>
                                        <input type="text" name="rg" id="cRg" class="form-control" maxlength="14" placeholder="0.000-000">
                                    </div>
                                    <div class="col-md-6 form-input-container">
                                        <label for="cPis" class="control-label">PIS:</label>
                                        <input type="text" name="pis" id="cPis" class="form-control" placeholder="000.00000.00-0">
                                    </div>
                                    <div class="col-md-6 form-input-container">
                                        <label for="cEstadoCivil" class="control-label">Estado Civil:</label>
                                        <select id="cEstadoCivil" name="estado_civil" class="form-control" required>
                                            <option value="" selected disabled>Selecione o estado civil</option>
                                            <option value="solteiro">Solteiro(a)</option>
                                            <option value="casado">Casado(a)</option>
                                            <option value="viuvo">Viúvo(a)</option>
                                            <option value="divorciado">Divorciado(a)</option>
                                            <option value="prefiro não dizer">Prefiro não dizer</option>
                                            <option value="outros">Outros</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-input-container">
                                        <label for="cEmail" class="control-label">E-mail:</label>
                                        <input type="email" name="email" id="cEmail" class="form-control" placeholder="Insira o email do instalador">
                                    </div>
                                    <div class="col-md-6 form-input-container" id="senhaWrapper">
                                        <label for="cSenha" class="control-label">Senha:</label>
                                        <input type="password" name="senha" id="cSenha" class="form-control">
                                    </div>
                                    <div class="col-md-6 form-input-container" id="cSenhaWrapper">
                                        <label for="ccSenha" class="control-label">Confirme a Senha:</label>
                                        <input type="password" name="rsenha" id="ccSenha" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab3">
                                <div class="row">
                                    <div class="col-md-12 form-input-container">
                                        <label for="cTelefone" class="control-label">Telefone:</label>
                                        <input type="text" name="telefone" id="cTelefone" class="form-control" placeholder="Digite um telefone">
                                    </div>
                                    <div class="col-md-12 form-input-container">
                                        <label for="cCelular" class="control-label">Celular:</label>
                                        <input type="text" name="celular" id="cCelular" class="form-control" placeholder="Digite um celular">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab4">
                                <div class="row">
                                    <div class="col-md-12 form-input-container">
                                        <label for="cEndereco" class="control-label">Rua:</label>
                                        <input type="text" name="endereco" id="cEndereco" class="form-control letras" placeholder="Digite um endereço">
                                    </div>
                                    <div class="col-md-6 form-input-container">
                                        <label for="cNumero" class="control-label">Número:</label>
                                        <input type="text" name="numero" id="cNumero" class="form-control" placeholder="Digite um numero do endereço">
                                    </div>
                                    <div class="col-md-6 form-input-container">
                                        <label for="cCEP" class="control-label">CEP:</label>
                                        <input type="text" name="cep" id="cCEP" class="form-control" placeholder="Digite um cep">
                                    </div>
                                    <div class="col-md-12 form-input-container">
                                        <label for="cBairro" class="control-label">Bairro:</label>
                                        <input type="text" name="bairro" id="cBairro" class="form-control letras" placeholder="Digite o nome do bairro">
                                    </div>
                                    <div class="col-md-12 form-input-container">
                                        <label for="cEstado" class="control-label">Estado:</label>
                                        <select id="cEstado" name="estado" class="form-control">
                                            <option value="" selected>Escolha seu estado</option>
                                            <option value="AC">Acre</option>
                                            <option value="AL">Alagoas</option>
                                            <option value="AM">Amazonas</option>
                                            <option value="AP">Amapá</option>
                                            <option value="BA">Bahia</option>
                                            <option value="CE">Ceará</option>
                                            <option value="DF">Distrito Federal</option>
                                            <option value="ES">Espirito Santo</option>
                                            <option value="GO">Goiás</option>
                                            <option value="MA">Maranhão</option>
                                            <option value="MG">Minas Gerais</option>
                                            <option value="MS">Mato Grosso do Sul</option>
                                            <option value="MT">Mato Grosso</option>
                                            <option value="PA">Pará</option>
                                            <option value="PB">Paraíba</option>
                                            <option value="PE">Pernambuco</option>
                                            <option value="PI">Piauí</option>
                                            <option value="PR">Paraná</option>
                                            <option value="RJ">Rio de Janeiro</option>
                                            <option value="RN">Rio Grande do Norte</option>
                                            <option value="RO">Rondônia</option>
                                            <option value="RR">Roraima</option>
                                            <option value="RS">Rio Grande do Sul</option>
                                            <option value="SC">Santa Catarina</option>
                                            <option value="SE">Sergipe</option>
                                            <option value="SP">São Paulo</option>
                                            <option value="TO">Tocantins</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-input-container">
                                        <label for="cCidade" class="control-label">Cidade:</label>
                                        <input type="text" name="cidade" id="cCidade" class="form-control letras" placeholder="Digite o nome da cidade">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab5">
                                <div class="row">
                                    <div class="col-md-12 form-input-container">
                                        <label for="vInstalacao" class="control-label">Valor Instalação:</label>
                                        <input type="text" id="vInstalacao" data-prefix="R$ " name="valor_instalacao" placeholder="R$" class="form-control money2 money">
                                        <h4 class="badge badge-danger" style="color: #FFFFFF !important;">Média: R$
                                            <?php echo round($valores[0]->instalacao, 2); ?>
                                        </h4>
                                    </div>
                                    <div class="col-md-12 form-input-container">
                                        <label for="vManutencao" class="control-label">Valor Manutenção:</label>
                                        <input type="text" id="vManutencao" data-prefix="R$ " name="valor_manutencao" placeholder="R$" class="form-control money2 money">
                                        <h4 class="badge badge-danger" style="color: #FFFFFF !important;">Média: R$
                                            <?php echo round($valores[0]->manutencao, 2); ?>
                                        </h4>
                                    </div>
                                    <div class="col-md-12 form-input-container">
                                        <label for="vRetirada" class="control-label">Valor Retirada:</label>
                                        <input type="text" id="vRetirada" data-prefix="R$ " name="valor_retirada" placeholder="R$" class="form-control money2 money">
                                        <h4 class="badge badge-danger" style="color: #FFFFFF !important;">Média: R$
                                            <?php echo round($valores[0]->retirada, 2); ?>
                                        </h4>
                                    </div>
                                    <div class="col-md-12 form-input-container">
                                        <label for="vDeslocamento" class="control-label">Valor de Deslocamento por
                                            KM:</label>
                                        <input type="text" id="vDeslocamento" data-prefix="R$ " name="valor_desloc_km" placeholder="R$" class="form-control money2 money">
                                        <h4 class="badge badge-danger" style="color: #FFFFFF !important;">Média: R$
                                            <?php echo round($valores[0]->desloc, 2); ?>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab6">
                                <div class="row default-row">
                                    <div class="col-md-12" style="display: none;" id="editarContaBancariaWrapper">
                                        <button id="editarContaBancariaBtn" class="btn btn-primary" style="float: right;" type="button">Editar Conta</button>
                                    </div>
                                    <div class="col-md-12 form-input-container">
                                        <label for="tt_conta" class="control-label">Titular da Conta:</label>
                                        <input type="text" id="tt_conta" name="titular_conta" class="form-control letras" maxlength="45" placeholder="Digite o nome do titular da conta">
                                    </div>
                                    <div class="col-md-6 form-input-container">
                                        <label for="cBanco" class="control-label">Banco:</label>
                                        <select id="cBanco" name="banco" class="form-control">
                                            <option value="" selected>Selecione um banco</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-input-container">
                                        <label for="cTipoConta" class="control-label">Tipo de Conta:</label>
                                        <select id="cTipoConta" name="tipo_conta" class="form-control">
                                            <option selected disabled value="">Selecione um tipo de conta</option>
                                            <option value="corrente">Conta Corrente</option>
                                            <option value="poupanca">Poupança</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-input-container">
                                        <label for="cAgencia" class="control-label">Agência:</label>
                                        <input type="text" id="cAgencia" name="agencia" class="form-control" placeholder="Digite a agencia da conta">
                                    </div>
                                    <div class="col-md-6 form-input-container">
                                        <label for="cOper" class="control-label">Operação:</label>
                                        <input type="text" id="cOper" name="operacao" class="form-control" maxlength="3" placeholder="Digite o número da operação">
                                    </div>
                                    <div class="col-md-12 form-input-container">
                                        <label for="cConta" class="control-label">Conta:</label>
                                        <input type="text" id="cConta" name="conta" class="form-control" placeholder="Digite o número da conta">
                                    </div>
                                    <div class="col-md-12 form-input-container">
                                        <label class="control-label">Documento Titular:</label>
                                        <div class="form-group" style="margin-bottom: 0px !important;">
                                            <div class="radio-inline">
                                                <label><input type="radio" id="radioCPF_titular" value="cpf" name="radio_cpf_titular" checked> CPF</label>
                                            </div>
                                            <div class="radio-inline">
                                                <label><input type="radio" id="radioCNPJ_titular" value="cnpj" name="radio_cnpj_titular"> CNPJ</label>
                                            </div>
                                        </div>
                                        <div id="divCPF_titular" class="form-group">
                                            <input type="text" class="form-control cpf" id="cCPF_titular" name="cpf_titular" placeholder="000.000.000-00" maxlength="11">
                                        </div>
                                        <div id="divCNPJ_titular" class="form-group" style="display: none;">
                                            <input type="text" class="form-control" id="cCNPJ_titular" name="cnpj_titular" placeholder="00.000.000/0000-00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSalvarDadosInstalador" class="btn btn-success" title="Salvar os dados preenchidos">Salvar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            </div>

        </div>
    </div>
</div>

<div id="modalEditarContaInstalador" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header header-layout">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Editar Conta Bancária</h3>
            </div>
            <div class="modal-body">
                <form id="editarContaBancariaInstaladorForm">
                    <div class="modal-body-container">
                        <div class="row default-row">

                            <div class="col-md-12 form-input-container">
                                <label for="newAccountHolder" class="control-label">Titular da Conta:</label>
                                <input type="text" id="newAccountHolder" name="newAccountHolder" class="form-control letters" maxlength="45" placeholder="Digite o nome do titular da conta">
                            </div>
                            <div class="col-md-6 form-input-container">
                                <label for="newBank" class="control-label">Banco:</label>
                                <select id="newBank" name="newBank" class="form-control">
                                    <option value="" selected>Selecione um banco</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-input-container">
                                <label for="newAccountType" class="control-label">Tipo de Conta:</label>
                                <select id="newAccountType" name="newAccountType" class="form-control">
                                    <option selected disabled value="">Selecione um tipo de conta</option>
                                    <option value="corrente">Conta Corrente</option>
                                    <option value="poupanca">Poupança</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-input-container">
                                <label for="newAgency" class="control-label">Agência:</label>
                                <input type="text" id="newAgency" name="newAgency" class="form-control" placeholder="Digite a agencia da conta">
                            </div>
                            <div class="col-md-6 form-input-container">
                                <label for="newOperation" class="control-label">Operação:</label>
                                <input type="text" id="newOperation" name="newOperation" class="form-control" maxlength="3" placeholder="Digite o número da operação">
                            </div>
                            <div class="col-md-12 form-input-container">
                                <label for="newAccountNumber" class="control-label">Conta:</label>
                                <input type="text" id="newAccountNumber" name="newAccountNumber" class="form-control" placeholder="Digite o número da conta">
                            </div>
                            <div class="col-md-12 form-input-container">
                                <label class="control-label">Documento Titular:</label>
                                <div class="form-group" style="margin-bottom: 0px !important;">
                                    <div class="radio-inline">
                                        <label><input type="radio" id="newRadioCPFHolder" value="cpf" name="newRadioCPFHolder" checked> CPF</label>
                                    </div>
                                    <div class="radio-inline">
                                        <label><input type="radio" id="newRadioCNPJHolder" value="cnpj" name="newRadioCNPJHolder"> CNPJ</label>
                                    </div>
                                </div>
                                <div id="newDivCPFHolder" class="form-group">
                                    <input type="text" class="form-control cpf" id="newCPFHolder" name="newCPFHolder" placeholder="000.000.000-00" maxlength="11">
                                </div>
                                <div id="newDivCNPJHolder" class="form-group" style="display: none;">
                                    <input type="text" class="form-control" id="newCNPJHolder" name="newCNPJHolder" placeholder="00.000.000/0000-00">
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer" style="margin-right: 5px; padding-top: 0px">
                <button id="editarContaConfirmarBtn" class="btn btn-success" type="button">Salvar</button>
                <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var userDataOverlay = "Email: <?php print_r($_SESSION['shownet']['log_admin']['email']) ?> IP: <?= $userIP ?> Data: <?= date("Y-m-d h:i:sa") ?>";

    var Router = '<?= site_url('instaladores') ?>';
    var SiteURL = '<?= site_url('') ?>';
    var BaseURL = '<?= base_url('') ?>';
</script>

<style>
    .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left-color: #7983ff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<!-- Libraries -->
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/js/libraries/imageViewer/dist', 'photoswipe.css') ?>">
<script type="text/javascript" src="<?= versionFile('assets/js/libraries/imageViewer/dist', 'photoswipe-lightbox.esm.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/libraries/imageViewer', 'ImageViewer.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/ag-charts-community@9.0.0/dist/umd/ag-charts-community.js"></script>

<!-- Style -->
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/newLayout', 'global.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/instaladores', 'layout.css') ?>">

<!-- Default Scripts -->
<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('media/js/helpers', 'ag_charts_defaults.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/EzMock', 'app.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/aggrid', 'actionButton.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/helpers/aggrid', 'agGridTable.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/instaladores', 'Exportacoes.js') ?>"></script>

<!-- Custom Scripts -->
<script type="text/javascript" src="<?= versionFile('assets/js/instaladores/listar', 'Document.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/instaladores/listar', 'Listar.js') ?>"></script>