<script type="text/javascript" src="<?= versionFile('assets/js/OCR', 'locale.pt-br.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">
<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang("fornecedores") ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('cadastro') ?> >
        <?= lang('novo_fornecedor') ?>
    </h4>
</div>
<style>
    #search-input::placeholder {
        font-style: italic;
    }

    #search-input {
        font-style: normal;
    }
</style>


<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-12" id="conteudo">
        <div class="card-conteudo card-fornecedores" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b>Dados: </b>
                <div style="display: flex;flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div class="dropdown" style="margin-right: 10px;">
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" id="opcoes_exportacao_cintas" style="min-width: 100px; top: 62px; height: 91px;">
                        </div>
                    </div>
                    <button class="btn btn-primary" id="button-add-fornecedor" type="button" style="margin-right: 11px;"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</button>
                    <!-- <button class="btn btn-light btn-expandir-fornecedor" data-toggle="tooltip" data-placement="left" title="<?php echo lang('expandir_grid') ?>" style="border-radius:6px; padding:5px;">
                        <img id="img_grid_expandir" class="img-expandir-fornecedor" src="<?php echo base_url('assets/images/icon-filter-hide.svg') ?>" posicao="posicao_grid_vertical" style="width: 25px;">
                    </button> -->
                </div>
            </h3>
            <div id="loadingMessage" class="loadingMessage" style="display: none; top: 57%; left: 50%; transform: translate(-57%, -50%);">
                <b>Processando...</b><i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>
            </div>
            <div>
                <select id="select-quantidade-por-pagina" class="form-control" style="width: 100px; float: left; margin-top: 10px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <label for="select-quantidade-por-pagina" style="float: left; margin-top: 18px; margin-left: 10px; color: #7F7F7F; font-size: 15px;">Registros por página</label>
                <input type="text" id="search-input" placeholder="Buscar" style="float: right; margin-top: 19px; height:30px; padding-left: 10px;">
            </div>
            <div class="wrapperFornecedores" style="margin-top: 20px;">
                <div id="tableFornecedores" class="ag-theme-alpine my-grid-fornecedores">
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalFornecedor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id='formFornecedor' name="myForm">
                <div class="modal-header header-layout">
                    <button type="button" id="close" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="titleModalProduto">Registro de Fornecedores</h3>
                </div>
                <ul class="nav nav-tabs" id="abasModalProduto" role="tablist">
                    <li class="nav-item" style="margin-left: 5px;">
                        <a class="nav-link" id="abaDadosPessoais-tab" data-toggle="tab" href="#abaDadosPessoais" role="tab" aria-controls="abaDadosPessoais" aria-selected="true">Dados Pessoais</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="abaContato-tab" data-toggle="tab" href="#abaContato" role="tab" aria-controls="abaContato" aria-selected="true">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="abaEndereco-tab" data-toggle="tab" href="#abaEndereco" role="tab" aria-controls="abaEndereco" aria-selected="true">Endereço</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="abaDadosBancarios-tab" data-toggle="tab" href="#abaDadosBancarios" role="tab" aria-controls="abaDadosBancarios" aria-selected="true">Dados Bancários</a>
                    </li>
                </ul>
                <div class="tab-content" id="dadosPessoais">
                    <div class="tab-pane fade" id="abaDadosPessoais" role="tabpanel" aria-labelledby="abaDadosPessoais-tab">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class='row'>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="nome" class="control-label">Nome:</label>
                                        <input type="text" name="nome" class="form-control" placeholder="Informe o Nome do Fornecedor" id="nomeFornecedor" required />
                                    </div>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="razaoSocial" class="control-label">Razão Social:</label>
                                        <input type="text" name="razaosocial" class="form-control" id="razaoSocial" placeholder="Informe a Razão Social" required />
                                    </div>
                                    <div class="col-md-6 input-container form-group qtd" style="padding: 20px 0 0 15px;">
                                        <label for="fisica" class="control-label">Pessoa Física:</label>
                                        <input type="radio" name="tipo" id="fisica" value="fisica"></input>
                                        <label for="juridica" class="control-label">Pessoa Jurídica:</label>
                                        <input type="radio" name="tipo" id="juridica" value="juridica"></input>
                                    </div>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="cnpj" class="control-label">CPF/CNPJ:</label>
                                        <input type="text" name="cnpj" class="form-control" id="cnpj" placeholder="Informe o CPF/CNPJ" required />
                                    </div>
                                    <div class="col-md-6 input-container form-group qtd">
                                        <label for="inscricaoEstadual">Inscrição Estadual:</label>
                                        <input type="number" name="inscricao_e" class="form-control" placeholder="Informe a Inscrição Estadual" id="inscricaoEstadual" />
                                    </div>
                                    <div class="col-md-6 input-container form-group qtd">
                                        <label for="inscricaoMunicipal">Inscrição Municipal:</label>
                                        <input type="number" name="inscricao_m" class="form-control" placeholder="Informe a Inscrição Municipal" id="inscricaoMunicipal" />
                                    </div>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="empresa_r" class="control-label">Empresa:</label>
                                        <select class="form-control" name="empresa_r" id="empresa_r" type="text" style="width: 100%;" required>
                                            <option value="">Selecione a Empresa</option>
                                            <option value="TRACKER">SHOW PRESTADORA DE SERVIÇOS DO
                                                BRASIL LTDA - ME</option>
                                            <option value="SIMM2M">SIMM2M</option>
                                            <option value="NORIO">SIGA ME - NORIO MOMOI EPP</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content" id="contato">
                    <div class="tab-pane fade" id="abaContato" role="tabpanel" aria-labelledby="abaContato-tab">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class='row'>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="email" class="control-label">E-MAIL:</label>
                                        <input type="email" name="email" class="form-control" placeholder="Informe o E-mail" id="email" required />
                                    </div>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="celular">Telefone:</label>
                                        <input type="text" name="telefone" id="celular" class="form-control" placeholder="Informe o Telefone" />
                                    </div>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="celular1" class="control-label">Celular:</label>
                                        <input type="text" name="telefone_one" class="form-control" id="celular1" placeholder="Informe o Celular" required />
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content" id="endereco">
                    <div class="tab-pane fade" id="abaEndereco" role="tabpanel" aria-labelledby="abaEndereco-tab">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class='row'>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="cep" class="control-label">CEP:</label>
                                        <input type="text" name="cep" class="form-control" placeholder="Informe o CEP" id="cep" required />
                                    </div>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="rua" class="control-label">Rua:</label>
                                        <input type="text" name="rua" class="form-control" id="rua" placeholder="Informe a Rua" required />
                                    </div>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="numero" class="control-label">Número:</label>
                                        <input type="text" name="numero" class="form-control" id="numero" placeholder="Informe o Número" required />
                                    </div>
                                    <div class="col-md-6 input-container form-group qtd">
                                        <label for="bairro" class="control-label">Bairro:</label>
                                        <input type="text" name="bairro" class="form-control" placeholder="Informe o Bairro" id="bairro" required />
                                    </div>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="estado" class="control-label">Estado:</label>
                                        <select name="estado" id="estado" class="form-control" required>
                                            <option value="">Selecione seu estado</option>
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
                                    <div class="col-md-6 input-container form-group qtd">
                                        <label for="cidade" class="control-label">Cidade:</label>
                                        <input type="text" name="cidade" class="form-control" placeholder="Informe a Cidade" id="cidade" required />
                                    </div>
                                    <div class="col-md-6 input-container form-group qtd">
                                        <label for="complemento">Complemento:</label>
                                        <input type="text" name="complemento" class="form-control" placeholder="Informe um Complemento" id="complemento" />
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content" id="dadosBancarios">
                    <div class="tab-pane fade" id="abaDadosBancarios" role="tabpanel" aria-labelledby="abaDadosBancarios-tab">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class='row'>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="titular" class="control-label">Titular da Conta:</label>
                                        <input type="text" name="banco[titular]" class="form-control" placeholder="Informe o Titular" id="titular" required />
                                    </div>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="banco" class="control-label">Banco:</label>
                                        <select type="text" class="form-control" id="banco" name="banco[banco]" placeholder="Banco">
                                            <option value="001">001 - Banco do Brasil S.A.</option>
                                            <option value="003">003 - Banco da Amazônia S.A.</option>
                                            <option value="004">004 - Banco do Nordeste do Brasil S.A.</option>
                                            <option value="012">012 - Banco Standard de Investimentos S.A.</option>
                                            <option value="014">014 - Natixis Brasil S.A. Banco Múltiplo</option>
                                            <option value="019">019 - Banco Azteca do Brasil S.A.</option>
                                            <option value="021">021 - BANESTES S.A. Banco do Estado do Espírito Santo</option>
                                            <option value="024">024 - Banco de Pernambuco S.A.</option>
                                            <option value="025">025 - Banco Alfa S.A.</option>
                                            <option value="029">029 - Banco Banerj S.A.</option>
                                            <option value="031">031 - Banco Beg S.A.</option>
                                            <option value="033">033 - Banco Santander (Brasil) S.A.</option>
                                            <option value="036">036 - Banco Bradesco BBI S.A.</option>
                                            <option value="037">037 - Banco do Estado do Pará S.A.</option>
                                            <option value="039">039 - Banco do Estado do Piauí S.A.</option>
                                            <option value="040">040 - Banco Cargill S.A.</option>
                                            <option value="041">041 - Banco do Estado do Rio Grande do Sul S.A.</option>
                                            <option value="044">044 - Banco BVA S.A.</option>
                                            <option value="045">045 - Banco Opportunity S.A.</option>
                                            <option value="047">047 - Banco do Estado de Sergipe S.A.</option>
                                            <option value="062">062 - Hipercard Banco Múltiplo S.A.</option>
                                            <option value="063">063 - Banco Ibi S.A. Banco Múltiplo</option>
                                            <option value="064">064 - Goldman Sachs do Brasil Banco Múltiplo S.A.</option>
                                            <option value="065">065 - Banco Bracce S.A.</option>
                                            <option value="066">066 - Banco Morgan Stanley S.A.</option>
                                            <option value="069">069 - BPN Brasil Banco Múltiplo S.A.</option>
                                            <option value="070">070 - BRB</option>
                                            <option value="072">072 - Banco Rural Mais S.A.</option>
                                            <option value="073">073 - BB Banco Popular do Brasil S.A.</option>
                                            <option value="074">074 - Banco J. Safra S.A.</option>
                                            <option value="075">075 - Banco CR2 S.A.</option>
                                            <option value="076">076 - Banco KDB S.A.</option>
                                            <option value="077">077 - Banco Intermedium S.A.</option>
                                            <option value="078">078 - BES Investimento do Brasil S.A.</option>
                                            <option value="079">079 - JBS Banco S.A.</option>
                                            <option value="081">081 - Concórdia Banco S.A.</option>
                                            <option value="082">082 - Banco Topázio S.A.</option>
                                            <option value="083">083 - Banco da China Brasil S.A.</option>
                                            <option value="084">084 - Unicred Norte do Paraná</option>
                                            <option value="085">085 - Cooperativa Central de Crédito Urbano</option>
                                            <option value="086">086 - OBOE Crédito Financiamento e Investimento S.A.</option>
                                            <option value="087">087 - Cooperativa Unicred Central Santa Catarina</option>
                                            <option value="088">088 - Banco Randon S.A.</option>
                                            <option value="089">089 - Cooperativa de Crédito Rural da Região de Mogiana</option>
                                            <option value="090">090 - Cooperativa Central de Economia e Crédito Mutuo das Unicreds</option>
                                            <option value="091">091 - Unicred Central do Rio Grande do Sul</option>
                                            <option value="092">092 - Brickell S.A. Crédito, financiamento e Investimento</option>
                                            <option value="094">094 - Banco Petra S.A.</option>
                                            <option value="096">096 - Banco BM&F de Serviços de Liquidação e Custódia S.A</option>
                                            <option value="097">097 - Cooperativa Central de Crédito Noroeste Brasileiro Ltda.</option>
                                            <option value="098">098 - Credicorol Cooperativa de Crédito Rural</option>
                                            <option value="099">099 - Cooperativa Central de Economia e Credito Mutuo das Unicreds</option>
                                            <option value="104">104 - Caixa Econômica Federal</option>
                                            <option value="107">107 - Banco BBM S.A.</option>
                                            <option value="168">168 - HSBC Finance (Brasil) S.A.</option>
                                            <option value="184">184 - Banco Itaú BBA S.A.</option>
                                            <option value="204">204 - Banco Bradesco Cartões S.A.</option>
                                            <option value="208">208 - Banco BTG Pactual S.A.</option>
                                            <option value="212">212 - Banco Matone S.A.</option>
                                            <option value="213">213 - Banco Arbi S.A</option>
                                            <option value="214">214 - Banco Dibens S.A.</option>
                                            <option value="215">215 - Banco Comercial e de Investimento Sudameris S.A.</option>
                                            <option value="217">217 - Banco John Deere S.A.</option>
                                            <option value="218">218 - Banco Bonsucesso S.A.</option>
                                            <option value="222">222 - Banco Credit Agricole Brasil S.A.</option>
                                            <option value="224">224 - Banco Fibra S.A.</option>
                                            <option value="225">225 - Banco Brascan S.A.</option>
                                            <option value="229">229 - Banco Cruzeiro do Sul S.A.</option>
                                            <option value="230">230 - Unicard Banco Múltiplo S.A.</option>
                                            <option value="233">233 - Banco GE Capital S.A.</option>
                                            <option value="237">237 - Banco Bradesco S.A.</option>
                                            <option value="241">241 - Banco Clássico S.A.</option>
                                            <option value="243">243 - Banco Máxima S.A.</option>
                                            <option value="246">246 - Banco ABC Brasil S.A.</option>
                                            <option value="248">248 - Banco Boavista Interatlântico S.A.</option>
                                            <option value="249">249 - Banco Investcred Unibanco S.A.</option>
                                            <option value="250">250 - Banco Schahin S.A.</option>
                                            <option value="254">254 - Paraná Banco S.A.</option>
                                            <option value="263">263 - Banco Cacique S.A.</option>
                                            <option value="265">265 - Banco Fator S.A.</option>
                                            <option value="266">266 - Banco Cédula S.A.</option>
                                            <option value="300">300 - Banco de La Nacion Argentina</option>
                                            <option value="318">318 - Banco BMG S.A.</option>
                                            <option value="320">320 - Banco Industrial e Comercial S.A.</option>
                                            <option value="341">341 - Itaú Unibanco S.A.</option>
                                            <option value="356">356 - Banco Real S.A.</option>
                                            <option value="366">366 - Banco Société Générale Brasil S.A.</option>
                                            <option value="370">370 - Banco WestLB do Brasil S.A.</option>
                                            <option value="376">376 - Banco J. P. Morgan S.A.</option>
                                            <option value="389">389 - Banco Mercantil do Brasil S.A.</option>
                                            <option value="394">394 - Banco Bradesco Financiamentos S.A.</option>
                                            <option value="399">399 - HSBC Bank Brasil S.A.</option>
                                            <option value="409">409 - UNIBANCO</option>
                                            <option value="412">412 - Banco Capital S.A.</option>
                                            <option value="422">422 - Banco Safra S.A.</option>
                                            <option value="453">453 - Banco Rural S.A.</option>
                                            <option value="456">456 - Banco de Tokyo</option>
                                            <option value="464">464 - Banco Sumitomo Mitsui Brasileiro S.A.</option>
                                            <option value="473">473 - Banco Caixa Geral</option>
                                            <option value="477">477 - Citibank N.A.</option>
                                            <option value="479">479 - Banco ItaúBank S.A</option>
                                            <option value="487">487 - Deutsche Bank S.A.</option>
                                            <option value="488">488 - JPMorgan Chase Bank</option>
                                            <option value="492">492 - ING Bank N.V.</option>
                                            <option value="494">494 - Banco de La Republica Oriental del Uruguay</option>
                                            <option value="495">495 - Banco de La Provincia de Buenos Aires</option>
                                            <option value="505">505 - Banco Credit Suisse (Brasil) S.A.</option>
                                            <option value="600">600 - Banco Luso Brasileiro S.A.</option>
                                            <option value="604">604 - Banco Industrial do Brasil S.A.</option>
                                            <option value="610">610 - Banco VR S.A.</option>
                                            <option value="611">611 - Banco Paulista S.A.</option>
                                            <option value="612">612 - Banco Guanabara S.A.</option>
                                            <option value="613">613 - Banco Pecúnia S.A.</option>
                                            <option value="623">623 - Banco Panamericano S.A.</option>
                                            <option value="626">626 - Banco Ficsa S.A.</option>
                                            <option value="630">630 - Banco Intercap S.A.</option>
                                            <option value="633">633 - Banco Rendimento S.A.</option>
                                            <option value="634">634 - Banco Triângulo S.A.</option>
                                            <option value="637">637 - Banco Sofisa S.A.</option>
                                            <option value="638">638 - Banco Prosper S.A.</option>
                                            <option value="641">641 - Banco Alvorada S.A.</option>
                                            <option value="643">643 - Banco Pine S.A.</option>
                                            <option value="652">652 - Itaú Unibanco Holding S.A.</option>
                                            <option value="653">653 - Banco Indusval S.A.</option>
                                            <option value="654">654 - Banco A.J.Renner S.A.</option>
                                            <option value="655">655 - Banco Votorantim S.A.</option>
                                            <option value="707">707 - Banco Daycoval S.A.</option>
                                            <option value="719">719 - Banif</option>
                                            <option value="721">721 - Banco Credibel S.A.</option>
                                            <option value="724">724 - Banco Porto Seguro S.A.</option>
                                            <option value="734">734 - Banco Gerdau S.A.</option>
                                            <option value="735">735 - Banco Pottencial S.A.</option>
                                            <option value="738">738 - Banco Morada S.A.</option>
                                            <option value="739">739 - Banco BGN S.A.</option>
                                            <option value="740">740 - Banco Barclays S.A.</option>
                                            <option value="741">741 - Banco Ribeirão Preto S.A.</option>
                                            <option value="743">743 - Banco Semear S.A.</option>
                                            <option value="744">744 - BankBoston N.A.</option>
                                            <option value="745">745 - Banco Citibank S.A.</option>
                                            <option value="746">746 - Banco Modal S.A.</option>
                                            <option value="747">747 - Banco Rabobank International Brasil S.A.</option>
                                            <option value="748">748 - Banco Cooperativo Sicredi S.A.</option>
                                            <option value="749">749 - Banco Simples S.A.</option>
                                            <option value="751">751 - Dresdner Bank Brasil S.A.</option>
                                            <option value="752">752 - Banco BNP Paribas Brasil S.A.</option>
                                            <option value="753">753 - NBC Bank Brasil S.A.</option>
                                            <option value="755">755 - Bank of America Merrill Lynch Banco Múltiplo S.A.</option>
                                            <option value="756">756 - Banco Cooperativo do Brasil S.A.</option>
                                            <option value="757">757 - Banco KEB do Brasil S.A.</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="agencia" class="control-label">Agência:</label>
                                        <input type="text" min="0" name="banco[agencia]" class="form-control" id="agencia" placeholder="Informe a Agência" required />
                                    </div>
                                    <div class="col-md-6 input-container form-group qtd">
                                        <label for="conta" class="control-label">Conta:</label>
                                        <input type="text" min="0" name="banco[conta]" class="form-control" placeholder="Informe a Conta" id="conta" required />
                                    </div>
                                    <div class="col-md-6 input-container form-group qtd">
                                        <label for="tipo_conta" class="control-label">Tipo da Conta:</label>
                                        <select class="form-control" type="text" id="tipo_conta" name="banco[tipo]">
                                            <option value="corrente">Conta Corrente</option>
                                            <option value="poupanca">Poupança</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="oper">Operação:</label>
                                        <input type="text" min="0" id="oper" name="banco[operacao]" class="form-control placeholder=" Informe a Operação">
                                    </div>
                                    <div class="col-md-6 input-container form-group">
                                        <label for="b_cpf" class="control-label">CPF/CNPJ do Titular da Conta:</label>
                                        <input type="text" id="b_cpf" name="banco[cpf]" class="form-control" placeholder="Informe o CPF/CNPJ" required>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnFechar">Fechar</button>
                        <button type="button" class="btn btn-success" data-id="" id='btnSalvarFornecedor'>Salvar</button>
                    </div>
                </div>
                <input type="hidden" name="banco[status]" value="1"> <input type="hidden" id="id" name="id" value=""> <input type="hidden" id="id_conta" name="id_conta" value=""> <input type="hidden" name="banco[id_retorno]" id="banco[id_retorno]"> <input type="hidden" name="banco[cad_retorno]" value="fornecedor"> <input type="hidden" id="status" name="status" value="1">
                <input type="hidden" name="edit" id="edit" value="">
            </form>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.dynatable.js"></script>
<script>
    var localeText = AG_GRID_LOCALE_PT_BR;

    $(document).ready(function() {
        atualizarAgGrid([]);

        $('.btn-expandir-fornecedor').on('click', function(e) {
            e.preventDefault();
            manipularMenuNodes();
        });

        $('#button-add-fornecedor').on('click', function() {
            $('#modalFornecedor').modal('show');

        })

        $('.nav-link').click(function() {
            var abaID = $(this).attr('href');

            $('.tab-pane').not(abaID).removeClass('show active');

            $(abaID).addClass('show active');
        });

        $('#modalFornecedor').on('shown.bs.modal', function() {
            $('.nav-link:first').click();
        });

        $('#modalFornecedor').on('hidden.bs.modal', function() {
            $('#titleModalProduto').text('Registro de Fornecedores');
        });

        $("#conta").mask('00000000');
        $("#oper").mask('00000000');
        $("#agencia").mask('00000000');
        $("#cep").mask('00000000');

        var celular = $("#celular");
        celular.mask('(00) 00000-0000', {
            reverse: false
        });

        var celular = $("#celular1");
        celular.mask('(00) 00000-0000', {
            reverse: false
        });
        var cpfMascara = function(val) {
                return val.replace(/\D/g, '').length > 11 ? '00.000.000/0000-00' : '000.000.000-009';
            },
            cpfOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(cpfMascara.apply({}, arguments), options);
                }
            };
        $('#cnpj').mask(cpfMascara, cpfOptions);

        $('#b_cpf').mask(cpfMascara, cpfOptions);

        $('#btnFechar, #close').on('click', function() {
            limparCampos();
            $('#titleModalProduto').text('Registro de Fornecedores');
        })

        $('#cep').on('change', function() {
            // Obtém o valor do CEP
            var cep = $(this).val();

            // Faz a solicitação AJAX para a API dos Correios
            $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
                // Preenche os campos de endereço com os dados da API
                $('#rua').val(data.logradouro);
                $('#bairro').val(data.bairro);
                $('#cidade').val(data.localidade);
                $('#estado').val(data.uf);
            });
        });

        $('#b_cpf').attr('data-tipo', 'CPF/CNPJ');
        $('#cnpj').attr('data-tipo', 'CPF/CNPJ');

        var validarMinimoCaracteres = function() {
            var input = $(this);
            var tipo = input.attr('data-tipo');
            var valor = input.val().replace(/\D/g, '');

            if (valor.length !== 11 && valor.length !== 14) {
                alert('O ' + tipo.toUpperCase() + ' deve ter entre 11 e 14 caracteres.');
            }
        };

        // Associa a função de validação ao evento blur
        $('#cnpj').on('blur', validarMinimoCaracteres);
        $('#b_cpf').on('blur', validarMinimoCaracteres);


        $('input[name="tipo"]').change(function() {
            if ($(this).prop('checked')) {
                var tipoSelecionado = $(this).val();
                if (tipoSelecionado === 'fisica') {
                    $('#inscricaoEstadual').val("");
                    $('#inscricaoMunicipal').val("");
                    $('#inscricaoEstadual, #inscricaoMunicipal').prop('disabled', true);
                } else if (tipoSelecionado === 'juridica') {
                    $('#inscricaoEstadual, #inscricaoMunicipal').prop('disabled', false);
                }
            }
        });

        $('#btnSalvarFornecedor').click(function() {
            if (validateForm() && validar()) {
                var formData = $('#formFornecedor').serialize();

                $.ajax({
                    type: "POST",
                    url: `<?= site_url('cadastro_fornecedor/inserir_fornecedor') ?>`,
                    data: formData,
                    success: function(response) {
                        $('#modalFornecedor').modal('hide');
                        if ($('#edit').val() == "1") {
                            alert("Cadastro atualizado com sucesso");
                        } else {
                            alert("Cadastro realizado com sucesso");
                        }
                        limparCampos();
                        var getJson = $.getJSON('<?= base_url() ?>index.php/cadastro_fornecedor/getFornecedor', function(data) {
                            var dadosMapeados = data.map(function(item) {
                                // Mapeia os dados conforme necessário
                                return {
                                    id: item.id,
                                    cpf_cnpj: item.cnpj,
                                    nome: item.nome,
                                    cidade: item.cidade,
                                    estado: item.estado,
                                    telefone: item.telefone,
                                    email: item.email,
                                    status: item.status == 0 ? 'Inativo' : 'Ativo',
                                    editar: `<a class='btn btn-mini btn-primary' onclick='editarFornecedor(${item.id})'><i class='fa fa-edit'></i></a>`,
                                };
                            });
                            if (data != null) {
                                atualizarAgGrid(dadosMapeados);
                            } else {
                                atualizarAgGrid([]);
                            }
                        })
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        })

    });

    function editarFornecedor(id) {
        $('#titleModalProduto').text('Editar Fornecedor');
        $.ajax({
            type: 'GET',
            url: '<?= base_url() ?>index.php/cadastro_fornecedor/editar/' + id,
            success: function(response) {
                var data = JSON.parse(response);
                $('#modalFornecedor').modal('show');
                $('#nomeFornecedor').val(data.fornecedores.nome);
                $('#razaoSocial').val(data.fornecedores.razaosocial);
                $('#cnpj').val(data.fornecedores.cnpj);
                $('#inscricaoEstadual').val(data.fornecedores.inscricao_e);
                $('#inscricaoMunicipal').val(data.fornecedores.inscricao_m);
                $('#empresa_r').val(data.fornecedores.empresa_r);
                $('#email').val(data.fornecedores.email);
                $('#celular').val(data.fornecedores.telefone);
                $('#celular1').val(data.fornecedores.telefone_one);
                $('#cep').val(data.fornecedores.cep);
                $('#rua').val(data.fornecedores.rua);
                $('#numero').val(data.fornecedores.numero);
                $('#bairro').val(data.fornecedores.bairro);
                $('#estado').val(data.fornecedores.estado);
                $('#cidade').val(data.fornecedores.cidade);
                $('#complemento').val(data.fornecedores.complemento);
                $('#status').val(data.fornecedores.status);
                $('#id').val(data.fornecedores.id);
                $('#titular').val(data.contas[0].titular);
                $('#banco').val(data.contas[0].banco);
                $('#agencia').val(data.contas[0].agencia);
                $('#conta').val(data.contas[0].conta);
                $('#tipo_conta').val(data.contas[0].tipo);
                $('#oper').val(data.contas[0].operacao);
                $('#b_cpf').val(data.contas[0].cpf);
                $('#id_conta').val(data.contas[0].id);
                $('#banco[id_retorno]').val(data.fornecedores.id);
                $('#edit').val("1");
            }

        })
    }

    var getJson = $.getJSON('<?= base_url() ?>index.php/cadastro_fornecedor/getFornecedor', function(data) {
        var dadosMapeados = data.map(function(item) {
            // Mapeia os dados conforme necessário
            return {
                id: item.id,
                cpf_cnpj: item.cnpj,
                nome: item.nome,
                cidade: item.cidade,
                estado: item.estado,
                telefone: item.telefone,
                email: item.email,
                status: item.status == 0 ? 'Inativo' : 'Ativo',
                editar: `<a class='btn btn-mini btn-primary' onclick='editarFornecedor(${item.id})'><i class='fa fa-edit'></i></a>`,
            };
        });
        if (data != null) {
            atualizarAgGrid(dadosMapeados);
        } else {
            atualizarAgGrid([]);
        }
    })

    function limparCampos() {
        $('#nomeFornecedor').val('');
        $('#razaoSocial').val('');
        $('#cnpj').val('');
        $('#inscricaoEstadual').val('');
        $('#inscricaoMunicipal').val('');
        $('#empresa_r').val('');
        $('#email').val('');
        $('#celular').val('');
        $('#celular1').val('');
        $('#cep').val('');
        $('#rua').val('');
        $('#numero').val('');
        $('#bairro').val('');
        $('#estado').val('');
        $('#cidade').val('');
        $('#complemento').val('');
        $('#titular').val('');
        $('#banco').val('');
        $('#agencia').val('');
        $('#conta').val('');
        $('#tipo_conta').val('');
        $('#oper').val('');
        $('#b_cpf').val('');
    }

    var AgGridEquipamentosDesatualizados;
    var DadosAgGrid = [];

    function atualizarAgGrid(dados) {
        stopAgGRID();
        const gridOptions = {
            columnDefs: [{
                    headerName: 'ID',
                    field: 'id',
                    chartDataType: 'category',
                },
                {
                    headerName: 'CPF/CNPJ',
                    field: 'cpf_cnpj',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Nome',
                    field: 'nome',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Cidade',
                    field: 'cidade',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Estado',
                    field: 'estado',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Telefone',
                    field: 'telefone',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Email',
                    field: 'email',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Status',
                    field: 'status',
                    chartDataType: 'series',
                },
                {
                    headerName: 'Editar',
                    field: 'editar',
                    chartDataType: 'series',
                    cellRenderer: 'agGroupCellRenderer',
                    cellRendererParams: {
                        innerRenderer: function(params) {
                            return params.data.editar;
                        },
                    },
                },
            ],
            defaultColDef: {
                enablePivot: true,
                editable: false,
                sortable: true,
                minWidth: 100,
                minHeight: 100,
                filter: true,
                resizable: true,
            },
            statusBar: {
                statusPanels: [{
                        statusPanel: 'agTotalRowCountComponent',
                        align: 'right'
                    },
                    {
                        statusPanel: 'agFilteredRowCountComponent'
                    },
                    {
                        statusPanel: 'agSelectedRowCountComponent'
                    },
                    {
                        statusPanel: 'agAggregationComponent'
                    },
                ],
            },
            popupParent: document.body,
            enableRangeSelection: true,
            enableCharts: true,
            domLayout: 'autoHeight',
            pagination: true,
            paginationPageSize: 10,
            localeText: localeText,
        };
        var gridDiv = document.querySelector('#tableFornecedores');
        AgGridEquipamentosDesatualizados = new agGrid.Grid(gridDiv, gridOptions);
        gridOptions.api.setRowData(dados);

        gridOptions.quickFilterText = '';

        document.querySelector('#search-input').addEventListener('input', function() {
            var searchInput = document.querySelector('#search-input');
            gridOptions.api.setQuickFilter(searchInput.value);
        });
        document.querySelector('#select-quantidade-por-pagina').addEventListener('change', function() {
            var selectedValue = document.querySelector('#select-quantidade-por-pagina').value;
            gridOptions.api.paginationSetPageSize(Number(selectedValue));
        });
    }

    function stopAgGRID() {
        var gridDiv = document.querySelector('#tableFornecedores');
        if (gridDiv && gridDiv.api) {
            gridDiv.api.destroy();
        }
        var wrapper = document.querySelector('.wrapperFornecedores');
        if (wrapper) {
            wrapper.innerHTML = '<div id="tableFornecedores" class="ag-theme-alpine my-grid-fornecedores"></div>';
        }
    }

    function validateForm() {
        var x = document.forms["myForm"]["nome"].value;
        if (x == "") {
            alert("Campo nome vazio! Preencha todos os campos obrigatórios!");
            $("#nomeFornecedor").focus();
            return false;
        }

        var x = document.forms["myForm"]["razaosocial"].value;
        if (x == "") {
            alert("Campo razão social vazio! Preencha todos os campos obrigatórios!");
            $("#razaoSocial").focus();
            return false;
        }

        var x = document.forms["myForm"]["cnpj"].value;
        if (x == "") {
            alert("Campo CPF/CNPJ vazio! Preencha todos os campos obrigatórios!");
            return false;
        }

        var x = document.forms["myForm"]["empresa_r"].value;
        if (x == "") {
            alert("Selecione uma empresa! Preencha todos os campos obrigatórios!");
            $("#empresa_r").focus();
            return false;
        }

        var x = document.forms["myForm"]["telefone_one"].value;
        if (x == "") {
            alert("Campo celular vazio! Preencha todos os campos obrigatórios!");
            $("#celular_1").focus();
            return false;
        }

        var x = document.forms["myForm"]["cep"].value;
        if (x == "") {
            alert("Campo CEP vazio! Preencha todos os campos obrigatórios!");
            $("#cep").focus();
            return false;
        }

        var x = document.forms["myForm"]["rua"].value;
        if (x == "") {
            alert("Campo rua vazio! Preencha todos os campos obrigatórios!");
            $("#rua").focus();
            return false;
        }

        var x = document.forms["myForm"]["numero"].value;
        if (x == "") {
            alert("Campo número vazio! Preencha todos os campos obrigatórios!");
            $("#numero").focus();
            return false;
        }

        var x = document.forms["myForm"]["bairro"].value;
        if (x == "") {
            alert("Campo bairro vazio! Preencha todos os campos obrigatórios!");
            $("#bairro").focus();
            return false;
        }

        var x = document.forms["myForm"]["estado"].value;
        if (x == "") {
            alert("Selecione um estado! Preencha todos os campos obrigatórios!");
            $("#estado").focus();
            return false;
        }


        var x = document.forms["myForm"]["cidade"].value;
        if (x == "") {
            alert("Campo cidade vazio! Preencha todos os campos obrigatórios!");
            $("#cidade").focus();
            return false;
        }


        var x = document.forms["myForm"]["banco[titular]"].value;
        if (x == "") {
            alert("Campo titular vazio! Preencha todos os campos obrigatórios!");
            $("#titular").focus();
            return false;
        }

        var x = document.forms["myForm"]["banco[banco]"].value;
        if (x == "") {
            alert("Campo banco vazio! Preencha todos os campos obrigatórios!");
            $("#banco").focus();
            return false;
        }

        var x = document.forms["myForm"]["banco[agencia]"].value;
        if (x == "") {
            alert("Campo agencia vazio! Preencha todos os campos obrigatórios!");
            $("#agencia").focus();
            return false;
        }

        var x = document.forms["myForm"]["banco[conta]"].value;
        if (x == "") {
            alert("Campo conta vazio! Preencha todos os campos obrigatórios!");
            $("#conta").focus();
            return false;
        }

        var x = document.forms["myForm"]["banco[tipo]"].value;
        if (x == "") {
            alert("Campo tipo da conta vazio! Preencha todos os campos obrigatórios!");
            $("#tipo_conta").focus();
            return false;
        }

        var x = document.forms["myForm"]["banco[cpf]"].value;
        if (x == "") {
            alert("Campo CPF/CNPJ do titular da conta vazio! Preencha todos os campos obrigatórios!");
            return false;
        }

        return true;
    }

    function validar() {
        if (document.getElementById("email").value == "") {
            alert("Por favor insira o email");
            document.myform.email.focus();
            return false;
        }

        var emailid = document.getElementById("email").value;
        atpos = emailid.indexOf("@");
        dotpos = emailid.lastIndexOf(".");

        if (atpos < 1 || (dotpos - atpos < 2))

        {
            alert("Por favor insira um email válido!");
            document.getElementById("email").focus();
            return false;
        }
        return (true);
    }
</script>