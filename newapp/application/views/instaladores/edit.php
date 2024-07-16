<br>
<script src="<?= base_url('assets/js/jquery.mask.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.mask.min.js') ?>"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
<style>
    div#myModal {
        width: 100% !important;
    }

    div.btn-group-justified {
        width: 100%;
    }

    a#menu {
        width: 31%;
    }

    .line-dados {
        display: inline-flex;
    }

    .box-input {
        padding-left: 15px;
    }
</style>
<?php if ($this->session->flashdata('sucesso')) : ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('sucesso') ?>
    </div>
<?php elseif ($this->session->flashdata('erro')) : ?>
    <div class="alert alert-danger">
        <?= $this->session->flashdata('erro') ?>
    </div>
<?php endif; ?>
<div id="alert"></div>
<h3>
    Editar Instaladores<small></small>
</h3>
<div class="form-horizontal formulario">
    <div class="well well-small">
        <div class="btn-group">
            <a href="<?php echo site_url('instaladores/listar_instaladores') ?>" class="btn btn-primary voltar" title="Voltar"><i class="fa fa-arrow-left"></i></a>
        </div>
        <div class="btn-group pull-right">
            <button id="salvarInst" onClick="refreshPage()" class="btn btn-primary" type="submit" title="Salva os dados preenchidos">
                Salvar
            </button>
        </div>
    </div>
    <div class="row">
        <div class="btn-group col-md-12">
            <ul class="nav nav-tabs">
                <li class="tab1 active"><a href="#tab1" data-toggle="tab">Dados Pessoais</a></li>
                <li class="tab3"><a href="#tab3" data-toggle="tab">Contato</a></li>
                <li class="tab4"><a href="#tab4" data-toggle="tab">Endereço</a></li>
                <li class="tab4"><a href="#tab5" data-toggle="tab">Valores Serviços</a></li>
                <li class="tab4"><a href="#tab6" data-toggle="tab">Dados Bancários</a></li>
            </ul>
        </div>
    </div>
    <br>

    <div class="tab-content">
        <div id="tab1" class="tab-pane active">
            <div class="dados-pessoais control-group">
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label col-sm-2">NOME:<sup style="color: red;">*</sup></label>
                            <div class="col-sm-6">
                                <input type="text" name="nome" id="nome" value="" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">SOBRENOME:<sup style="color: red;">*</sup></label>
                            <div class="col-sm-6">
                                <input type="text" name="sobrenome" id="sobrenome" value="" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">DATA DE NASCIMENTO:<sup style="color: red;">*</sup></label>
                            <div class="col-sm-6">
                                <input type="text" id="data_nascimento" class='form-control' name='data_nascimento' readonly='true' required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">CPF/CNPJ:<sup style="color: red;">*</sup></label>
                            <div class="col-sm-6">
                                <input type="text" name="cnpj" id="cpf" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">E-MAIL:<sup style="color: red;">*</sup></label>
                            <div class="col-sm-6">
                                <input type="email" name="email" value="" id="email" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">RG:</label>
                            <div class="col-sm-6">
                                <input type="text" name="rg" value="" id="rg" class="form-control" placeholder="0.000-000">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">PIS:</label>
                            <div class="col-sm-6">
                                <input type="text" name="pis" value="" id="pis" class="form-control" placeholder="000.00000.00-0">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">ESTADO CIVIL:</label>
                            <div class="col-sm-6">
                                <select id="estado_civil" name="estado_civil" class="form-control" required>
                                    <option value='' selected disabled="disabled">Selecione o estado civil</option>
                                    <option value='solteiro'>Solteiro(a)</option>
                                    <option value='casado'>Casado(a)</option>
                                    <option value='viuvo'>Viúvo(a)</option>
                                    <option value='divorciado'>Divorciado(a)</option>
                                    <option value='prefiro não dizer'>Prefiro não dizer</option>
                                    <option value='outros'>Outros</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab3" class="tab-pane">
            <div class="col-sm-12">
                <fieldset class="col-sm-12" id="0">
                    <br>
                    <div class="form-group">
                        <label class="control-label col-sm-2">TELEFONE:<sup style="color: red;">*</sup></label>
                        <div class="col-sm-6">
                            <input type="text" name="telefone" value="" id="telefone" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">CELULAR:</label>
                        <div class="col-sm-6">
                            <input type="text" name="celular" value="" id="celular" class="form-control">
                        </div>
                    </div>
            </div>

        </div>

        <div id="tab4" class="tab-pane">
            <div class="col-sm-12">
                <fieldset class="col-sm-12" id="0">
                    <br>
                    <div class="form-group">
                        <label class="control-label col-sm-2">CEP:<sup style="color: red;">*</sup></label>
                        <div class="col-sm-6">
                            <input type="text" name="cep" value="" id="cep" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">RUA:<sup style="color: red;">*</sup></label>
                        <div class="col-sm-6">
                            <input type="text" name="endereco" value="" id="endereco" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">NÚMERO:<sup style="color: red;">*</sup></label>
                        <div class="col-sm-6">
                            <input type="text" name="numero" value="" id="numero" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">BAIRRO:<sup style="color: red;">*</sup></label>
                        <div class="col-sm-6">
                            <input type="text" name="bairro" value="" id="bairro" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">ESTADO:<sup style="color: red;">*</sup></label>
                        <div class="col-sm-6">
                            <select id="estado" name="estado" class="form-control">
                                <option value="">Escolha seu estado</option>
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
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">CIDADE:<sup style="color: red;">*</sup></label>
                        <div class="col-sm-6">
                            <input type="text" name="cidade" value="" id="cidade" class="form-control">
                        </div>
                    </div>
            </div>
        </div>

        <div id="tab5" class="tab-pane">
            <div class="dados-pessoais control-group">
                <div class="form-group">
                    <label class="control-label col-sm-2">VALOR INSTALAÇÃO:</label>
                    <div class="col-sm-6">
                        <input type="text" id="valorIns" name="valor_instalacao" class="form-control money2" value="" placeholder="R$">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">VALOR MANUTENÇÃO:</label>
                    <div class="col-sm-6">
                        <input type="text" id="valorMan" name="valor_manutencao" class="form-control money2" value="" placeholder="R$">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">VALOR RETIRADA:</label>
                    <div class="col-sm-6">
                        <input type="text" id="valorRet" name="valor_retirada" class="form-control money2" value="" placeholder="R$">
                        <input type="hidden" name="id" id="id" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">VALOR DE DESLOCAMENTO PARA FORA DA REGIÃO METROPOLITANA POR KM:</label>
                    <div class="col-sm-6">
                        <input type="text" id="vDeslocamento" name="valor_desloc_km" class="form-control money2" value="" placeholder="R$">
                        <input type="hidden" name="id" id="id" />
                    </div>
                </div>
            </div>
            </form>
        </div>

        <?php if ($contas && count($contas) > 0) : ?>
            <?php foreach ($contas as $conta) : ?>

                <div id="tab6" class="tab-pane">
                    <a class="btn btn-primary" data-toggle="modal" data-target="#myModal">Editar Conta</a>

                    <br></br>
                    <div class="form-group">
                        <label class="control-label col-sm-2">TITULAR DA CONTA:<sup style="color: red;">*</sup></label>
                        <div class="col-sm-6">
                            <input class="form-control" disabled="disabled" type="text" value="<?= $conta->titular ?>" id="titular" name="titular">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">BANCO:<sup style="color: red;">*</sup></label>
                        <div class="col-sm-6">
                            <select type="text" disabled="disabled" class="form-control" id="banco" name="banco">
                                <option <?= $conta->banco == '001' ? 'selected' : '' ?>>001 - Banco do Brasil S.A. </option>
                                <option <?= $conta->banco == '003' ? 'selected' : '' ?>>003 - Banco da Amazônia S.A.</option>
                                <option <?= $conta->banco == '004' ? 'selected' : '' ?>>004 - Banco do Nordeste do Brasil S.A. </option>
                                <option <?= $conta->banco == '012' ? 'selected' : '' ?>>012 - Banco Standard de Investimentos S.A. </option>
                                <option <?= $conta->banco == '014' ? 'selected' : '' ?>>014 - Natixis Brasil S.A. Banco Múltiplo </option>
                                <option <?= $conta->banco == '019' ? 'selected' : '' ?>>019 - Banco Azteca do Brasil S.A.</option>
                                <option <?= $conta->banco == '021' ? 'selected' : '' ?>>021 - BANESTES S.A. Banco do Estado do Espírito Santo </option>
                                <option <?= $conta->banco == '024' ? 'selected' : '' ?>>024 - Banco de Pernambuco S.A. </option>
                                <option <?= $conta->banco == '025' ? 'selected' : '' ?>>025 - Banco Alfa S.A.</option>
                                <option <?= $conta->banco == '029' ? 'selected' : '' ?>>029 - Banco Banerj S.A.</option>
                                <option <?= $conta->banco == '031' ? 'selected' : '' ?>>031 - Banco Beg S.A.</option>
                                <option <?= $conta->banco == '033' ? 'selected' : '' ?>>033 - Banco Santander (Brasil) S.A. </option>
                                <option <?= $conta->banco == '036' ? 'selected' : '' ?>>036 - Banco Bradesco BBI S.A.</option>
                                <option <?= $conta->banco == '037' ? 'selected' : '' ?>>037 - Banco do Estado do Pará S.A. </option>
                                <option <?= $conta->banco == '039' ? 'selected' : '' ?>>039 - Banco do Estado do Piauí S.A. </option>
                                <option <?= $conta->banco == '040' ? 'selected' : '' ?>>040 - Banco Cargill S.A.</option>
                                <option <?= $conta->banco == '041' ? 'selected' : '' ?>>041 - Banco do Estado do Rio Grande do Sul S.A. </option>
                                <option <?= $conta->banco == '044' ? 'selected' : '' ?>>044 - Banco BVA S.A.</option>
                                <option <?= $conta->banco == '045' ? 'selected' : '' ?>>045 - Banco Opportunity S.A. </option>
                                <option <?= $conta->banco == '047' ? 'selected' : '' ?>>047 - Banco do Estado de Sergipe S.A. </option>
                                <option <?= $conta->banco == '062' ? 'selected' : '' ?>>062 - Hipercard Banco Múltiplo S.A. </option>
                                <option <?= $conta->banco == '063' ? 'selected' : '' ?>>063 - Banco Ibi S.A. Banco Múltiplo </option>
                                <option <?= $conta->banco == '064' ? 'selected' : '' ?>>064 - Goldman Sachs do Brasil Banco Múltiplo S.A. </option>
                                <option <?= $conta->banco == '065' ? 'selected' : '' ?>>065 - Banco Bracce S.A.</option>
                                <option <?= $conta->banco == '066' ? 'selected' : '' ?>>066 - Banco Morgan Stanley S.A. </option>
                                <option <?= $conta->banco == '069' ? 'selected' : '' ?>>069 - BPN Brasil Banco Múltiplo S.A. </option>
                                <option <?= $conta->banco == '070' ? 'selected' : '' ?>>070 - BRB </option>
                                <option <?= $conta->banco == '072' ? 'selected' : '' ?>>072 - Banco Rural Mais S.A. </option>
                                <option <?= $conta->banco == '073' ? 'selected' : '' ?>>073 - BB Banco Popular do Brasil S.A. </option>
                                <option <?= $conta->banco == '074' ? 'selected' : '' ?>>074 - Banco J. Safra S.A. </option>
                                <option <?= $conta->banco == '075' ? 'selected' : '' ?>>075 - Banco CR2 S.A.</option>
                                <option <?= $conta->banco == '076' ? 'selected' : '' ?>>076 - Banco KDB S.A. </option>
                                <option <?= $conta->banco == '077' ? 'selected' : '' ?>>077 - Banco Intermedium S.A. </option>
                                <option <?= $conta->banco == '078' ? 'selected' : '' ?>>078 - BES Investimento do Brasil S.A.</option>
                                <option <?= $conta->banco == '079' ? 'selected' : '' ?>>079 - JBS Banco S.A. </option>
                                <option <?= $conta->banco == '081' ? 'selected' : '' ?>>081 - Concórdia Banco S.A. </option>
                                <option <?= $conta->banco == '082' ? 'selected' : '' ?>>082 - Banco Topázio S.A. </option>
                                <option <?= $conta->banco == '083' ? 'selected' : '' ?>>083 - Banco da China Brasil S.A.</option>
                                <option <?= $conta->banco == '084' ? 'selected' : '' ?>>084 - Unicred Norte do Paraná</option>
                                <option <?= $conta->banco == '085' ? 'selected' : '' ?>>085 - Cooperativa Central de Crédito Urbano</option>
                                <option <?= $conta->banco == '086' ? 'selected' : '' ?>>086 - OBOE Crédito Financiamento e Investimento S.A. </option>
                                <option <?= $conta->banco == '087' ? 'selected' : '' ?>>087 - Cooperativa Unicred Central Santa Catarina </option>
                                <option <?= $conta->banco == '088' ? 'selected' : '' ?>>088 - Banco Randon S.A. </option>
                                <option <?= $conta->banco == '089' ? 'selected' : '' ?>>089 - Cooperativa de Crédito Rural da Região de Mogiana </option>
                                <option <?= $conta->banco == '090' ? 'selected' : '' ?>>090 - Cooperativa Central de Economia e Crédito Mutuo das Unicreds </option>
                                <option <?= $conta->banco == '091' ? 'selected' : '' ?>>091 - Unicred Central do Rio Grande do Sul</option>
                                <option <?= $conta->banco == '092' ? 'selected' : '' ?>>092 - Brickell S.A. Crédito, financiamento e Investimento </option>
                                <option <?= $conta->banco == '094' ? 'selected' : '' ?>>094 - Banco Petra S.A. </option>
                                <option <?= $conta->banco == '096' ? 'selected' : '' ?>>096 - Banco BM&F de Serviços de Liquidação e Custódia S.A</option>
                                <option <?= $conta->banco == '097' ? 'selected' : '' ?>>097 - Cooperativa Central de Crédito Noroeste Brasileiro Ltda. </option>
                                <option <?= $conta->banco == '098' ? 'selected' : '' ?>>098 - Credicorol Cooperativa de Crédito Rural </option>
                                <option <?= $conta->banco == '099' ? 'selected' : '' ?>>099 - Cooperativa Central de Economia e Credito Mutuo das Unicreds </option>
                                <option <?= $conta->banco == '104' ? 'selected' : '' ?>>104 - Caixa Econômica Federal </option>
                                <option <?= $conta->banco == '107' ? 'selected' : '' ?>>107 - Banco BBM S.A.</option>
                                <option <?= $conta->banco == '133' ? 'selected' : '' ?>>133 - Cresol Cooperativa Central.</option>
                                <option <?= $conta->banco == '168' ? 'selected' : '' ?>>168 - HSBC Finance (Brasil) S.A. </option>
                                <option <?= $conta->banco == '184' ? 'selected' : '' ?>>184 - Banco Itaú BBA S.A. </option>
                                <option <?= $conta->banco == '204' ? 'selected' : '' ?>>204 - Banco Bradesco Cartões S.A.</option>
                                <option <?= $conta->banco == '208' ? 'selected' : '' ?>>208 - Banco BTG Pactual S.A.</option>
                                <option <?= $conta->banco == '212' ? 'selected' : '' ?>>212 - Banco Matone S.A. </option>
                                <option <?= $conta->banco == '213' ? 'selected' : '' ?>>213 - Banco Arbi S.A</option>
                                <option <?= $conta->banco == '214' ? 'selected' : '' ?>>214 - Banco Dibens S.A. </option>
                                <option <?= $conta->banco == '215' ? 'selected' : '' ?>>215 - Banco Comercial e de Investimento Sudameris S.A.</option>
                                <option <?= $conta->banco == '217' ? 'selected' : '' ?>>217 - Banco John Deere S.A. </option>
                                <option <?= $conta->banco == '218' ? 'selected' : '' ?>>218 - Banco Bonsucesso S.A.</option>
                                <option <?= $conta->banco == '222' ? 'selected' : '' ?>>222 - Banco Credit Agricole Brasil S.A.</option>
                                <option <?= $conta->banco == '224' ? 'selected' : '' ?>>224 - Banco Fibra S.A. </option>
                                <option <?= $conta->banco == '225' ? 'selected' : '' ?>>225 - Banco Brascan S.A.</option>
                                <option <?= $conta->banco == '229' ? 'selected' : '' ?>>229 - Banco Cruzeiro do Sul S.A.</option>
                                <option <?= $conta->banco == '230' ? 'selected' : '' ?>>230 - Unicard Banco Múltiplo S.A.</option>
                                <option <?= $conta->banco == '233' ? 'selected' : '' ?>>233 - Banco GE Capital S.A. </option>
                                <option <?= $conta->banco == '237' ? 'selected' : '' ?>>237 - Banco Bradesco S.A.</option>
                                <option <?= $conta->banco == '241' ? 'selected' : '' ?>>241 - Banco Clássico S.A.</option>
                                <option <?= $conta->banco == '243' ? 'selected' : '' ?>>243 - Banco Máxima S.A. </option>
                                <option <?= $conta->banco == '246' ? 'selected' : '' ?>>246 - Banco ABC Brasil S.A.</option>
                                <option <?= $conta->banco == '248' ? 'selected' : '' ?>>248 - Banco Boavista Interatlântico S.A.</option>
                                <option <?= $conta->banco == '249' ? 'selected' : '' ?>>249 - Banco Investcred Unibanco S.A. </option>
                                <option <?= $conta->banco == '250' ? 'selected' : '' ?>>250 - Banco Schahin S.A. </option>
                                <option <?= $conta->banco == '254' ? 'selected' : '' ?>>254 - Paraná Banco S.A. </option>
                                <option <?= $conta->banco == '260' ? 'selected' : '' ?>>260 - NU PAGAMENTOS S.A.</option>
                                <option <?= $conta->banco == '263' ? 'selected' : '' ?>>263 - Banco Cacique S.A.</option>
                                <option <?= $conta->banco == '265' ? 'selected' : '' ?>>265 - Banco Fator S.A. </option>
                                <option <?= $conta->banco == '266' ? 'selected' : '' ?>>266 - Banco Cédula S.A.</option>
                                <option <?= $conta->banco == '290' ? 'selected' : '' ?>>290 - Pag Seguro S.A.</option>
                                <option <?= $conta->banco == '300' ? 'selected' : '' ?>>300 - Banco de La Nacion Argentina</option>
                                <option <?= $conta->banco == '318' ? 'selected' : '' ?>>318 - Banco BMG S.A.</option>
                                <option <?= $conta->banco == '320' ? 'selected' : '' ?>>320 - Banco Industrial e Comercial S.A. </option>
                                <option <?= $conta->banco == '336' ? 'selected' : '' ?>>336 - Banco C6 S.A.</option>
                                <option <?= $conta->banco == '341' ? 'selected' : '' ?>>341 - Itaú Unibanco S.A. </option>
                                <option <?= $conta->banco == '356' ? 'selected' : '' ?>>356 - Banco Real S.A. </option>
                                <option <?= $conta->banco == '366' ? 'selected' : '' ?>>366 - Banco Société Générale Brasil S.A. </option>
                                <option <?= $conta->banco == '370' ? 'selected' : '' ?>>370 - Banco WestLB do Brasil S.A. </option>
                                <option <?= $conta->banco == '376' ? 'selected' : '' ?>>376 - Banco J. P. Morgan S.A. </option>
                                <option <?= $conta->banco == '389' ? 'selected' : '' ?>>389 - Banco Mercantil do Brasil S.A. </option>
                                <option <?= $conta->banco == '394' ? 'selected' : '' ?>>394 - Banco Bradesco Financiamentos S.A.</option>
                                <option <?= $conta->banco == '399' ? 'selected' : '' ?>>399 - HSBC Bank Brasil S.A. </option>
                                <option <?= $conta->banco == '409' ? 'selected' : '' ?>>409 - UNIBANCO </option>
                                <option <?= $conta->banco == '412' ? 'selected' : '' ?>>412 - Banco Capital S.A. </option>
                                <option <?= $conta->banco == '422' ? 'selected' : '' ?>>422 - Banco Safra S.A. </option>
                                <option <?= $conta->banco == '453' ? 'selected' : '' ?>>453 - Banco Rural S.A. </option>
                                <option <?= $conta->banco == '456' ? 'selected' : '' ?>>456 - Banco de Tokyo</option>
                                <option <?= $conta->banco == '464' ? 'selected' : '' ?>>464 - Banco Sumitomo Mitsui Brasileiro S.A. </option>
                                <option <?= $conta->banco == '473' ? 'selected' : '' ?>>473 - Banco Caixa Geral </option>
                                <option <?= $conta->banco == '477' ? 'selected' : '' ?>>477 - Citibank N.A. </option>
                                <option <?= $conta->banco == '479' ? 'selected' : '' ?>>479 - Banco ItaúBank S.A </option>
                                <option <?= $conta->banco == '487' ? 'selected' : '' ?>>487 - Deutsche Bank S.A. </option>
                                <option <?= $conta->banco == '488' ? 'selected' : '' ?>>488 - JPMorgan Chase Bank </option>
                                <option <?= $conta->banco == '492' ? 'selected' : '' ?>>492 - ING Bank N.V. </option>
                                <option <?= $conta->banco == '494' ? 'selected' : '' ?>>494 - Banco de La Republica Oriental del Uruguay</option>
                                <option <?= $conta->banco == '495' ? 'selected' : '' ?>>495 - Banco de La Provincia de Buenos Aires</option>
                                <option <?= $conta->banco == '505' ? 'selected' : '' ?>>505 - Banco Credit Suisse (Brasil) S.A.</option>
                                <option <?= $conta->banco == '600' ? 'selected' : '' ?>>600 - Banco Luso Brasileiro S.A. </option>
                                <option <?= $conta->banco == '604' ? 'selected' : '' ?>>604 - Banco Industrial do Brasil S.A. </option>
                                <option <?= $conta->banco == '610' ? 'selected' : '' ?>>610 - Banco VR S.A. </option>
                                <option <?= $conta->banco == '611' ? 'selected' : '' ?>>611 - Banco Paulista S.A. </option>
                                <option <?= $conta->banco == '612' ? 'selected' : '' ?>>612 - Banco Guanabara S.A. </option>
                                <option <?= $conta->banco == '613' ? 'selected' : '' ?>>613 - Banco Pecúnia S.A. </option>
                                <option <?= $conta->banco == '623' ? 'selected' : '' ?>>623 - Banco Panamericano S.A. </option>
                                <option <?= $conta->banco == '626' ? 'selected' : '' ?>>626 - Banco Ficsa S.A. </option>
                                <option <?= $conta->banco == '630' ? 'selected' : '' ?>>630 - Banco Intercap S.A. </option>
                                <option <?= $conta->banco == '633' ? 'selected' : '' ?>>633 - Banco Rendimento S.A. </option>
                                <option <?= $conta->banco == '634' ? 'selected' : '' ?>>634 - Banco Triângulo S.A. </option>
                                <option <?= $conta->banco == '637' ? 'selected' : '' ?>>637 - Banco Sofisa S.A. </option>
                                <option <?= $conta->banco == '638' ? 'selected' : '' ?>>638 - Banco Prosper S.A. </option>
                                <option <?= $conta->banco == '641' ? 'selected' : '' ?>>641 - Banco Alvorada S.A.</option>
                                <option <?= $conta->banco == '643' ? 'selected' : '' ?>>643 - Banco Pine S.A. </option>
                                <option <?= $conta->banco == '652' ? 'selected' : '' ?>>652 - Itaú Unibanco Holding S.A. </option>
                                <option <?= $conta->banco == '653' ? 'selected' : '' ?>>653 - Banco Indusval S.A. </option>
                                <option <?= $conta->banco == '654' ? 'selected' : '' ?>>654 - Banco A.J.Renner S.A.</option>
                                <option <?= $conta->banco == '655' ? 'selected' : '' ?>>655 - Banco Votorantim S.A. </option>
                                <option <?= $conta->banco == '707' ? 'selected' : '' ?>>707 - Banco Daycoval S.A.</option>
                                <option <?= $conta->banco == '719' ? 'selected' : '' ?>>719 - Banif</option>
                                <option <?= $conta->banco == '721' ? 'selected' : '' ?>>721 - Banco Credibel S.A.</option>
                                <option <?= $conta->banco == '724' ? 'selected' : '' ?>>724 - Banco Porto Seguro S.A. </option>
                                <option <?= $conta->banco == '734' ? 'selected' : '' ?>>734 - Banco Gerdau S.A. </option>
                                <option <?= $conta->banco == '735' ? 'selected' : '' ?>>735 - Banco Pottencial S.A. </option>
                                <option <?= $conta->banco == '738' ? 'selected' : '' ?>>738 - Banco Morada S.A. </option>
                                <option <?= $conta->banco == '739' ? 'selected' : '' ?>>739 - Banco BGN S.A.</option>
                                <option <?= $conta->banco == '740' ? 'selected' : '' ?>>740 - Banco Barclays S.A.</option>
                                <option <?= $conta->banco == '741' ? 'selected' : '' ?>>741 - Banco Ribeirão Preto S.A. </option>
                                <option <?= $conta->banco == '743' ? 'selected' : '' ?>>743 - Banco Semear S.A. </option>
                                <option <?= $conta->banco == '744' ? 'selected' : '' ?>>744 - BankBoston N.A. </option>
                                <option <?= $conta->banco == '745' ? 'selected' : '' ?>>745 - Banco Citibank S.A.</option>
                                <option <?= $conta->banco == '746' ? 'selected' : '' ?>>746 - Banco Modal S.A. </option>
                                <option <?= $conta->banco == '747' ? 'selected' : '' ?>>747 - Banco Rabobank International Brasil S.A. </option>
                                <option <?= $conta->banco == '748' ? 'selected' : '' ?>>748 - Banco Cooperativo Sicredi S.A.</option>
                                <option <?= $conta->banco == '749' ? 'selected' : '' ?>>749 - Banco Simples S.A. </option>
                                <option <?= $conta->banco == '751' ? 'selected' : '' ?>>751 - Dresdner Bank Brasil S.A. </option>
                                <option <?= $conta->banco == '752' ? 'selected' : '' ?>>752 - Banco BNP Paribas Brasil S.A.</option>
                                <option <?= $conta->banco == '753' ? 'selected' : '' ?>>753 - NBC Bank Brasil S.A. </option>
                                <option <?= $conta->banco == '755' ? 'selected' : '' ?>>755 - Bank of America Merrill Lynch Banco Múltiplo S.A. </option>
                                <option <?= $conta->banco == '756' ? 'selected' : '' ?>>756 - Banco Cooperativo do Brasil S.A.</option>
                                <option <?= $conta->banco == '757' ? 'selected' : '' ?>>757 - Banco KEB do Brasil S.A. </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">AGÊNCIA:<sup style="color: red;">*</sup></label>
                        <div class="col-sm-6">
                            <input class="form-control" disabled="disabled" type="number" name="agencia" maxlength="9" pattern="[^'\x22]+" value="<?= $conta->agencia ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">CONTA:<sup style="color: red;">*</sup></label>
                        <div class="col-sm-6">
                            <input class="form-control" disabled="disabled" type="number" name="conta" maxlength="9" pattern="[^'\x22]+" value="<?= $conta->conta ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">TIPO DA CONTA:<sup style="color: red;">*</sup></label>
                        <div class="col-sm-6">
                            <select class="form-control" type="text" disabled="disabled" id="tipo_conta" name="tipo">
                                <option value="corrente" <?= $conta->tipo == 'corrente' ? 'selected' : '' ?>>Conta Corrente</option>
                                <option value="poupanca" <?= $conta->tipo == 'poupanca' ? 'selected' : '' ?>>Poupança</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">OPERAÇÃO:<sup style="color: red;">*</sup></label>
                        <div class="col-sm-6">
                            <input type="number" disabled="disabled" name="operacao" maxlength="9" pattern="[^'\x22]+" class="form-control" value="<?= $conta->operacao ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">CPF/CNPJ DO TITULAR DA CONTA:<sup style="color: red;">*</sup></label>
                        <div class="col-sm-6">
                            <input type="text" id="b_cpf" disabled="disabled" name="cpf" class="form-control" placeholder="000.000.000-00 ou 00.000.000/0000-00" value="<?= $conta->cpf ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">DATA DE CADASTRO:</label>
                        <div class="col-sm-6">
                            <input id="data" class="form-control" disabled="disabled" type="text" name="data" value="<?php echo date('d/m/Y H:i:s', strtotime($conta->data_cad)); ?>">
                        </div>
                    </div>
                </div>

                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">EDITAR CONTA</h4>
                            </div>
                            <form action="<?= site_url('instaladores/update_conta') . '/' . $conta->id . '/' . $_GET['id'] ?>" method="post">
                                <div class="modal-body">
                                    <div class="line-dados control-group">
                                        <div class="col-sm-3">
                                            <div class="box-input">
                                                <label class="con-label">TITULAR DA CONTA:<sup style="color: red;">*</sup></label>
                                                <div class="controls">
                                                    <input type="text" id="titular" name="titular" class="form-control" value="<?= $conta->titular ?>" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label">BANCO:<sup style="color: red;">*</sup></label>
                                            <div class="controls">
                                                <div class="input-prepend">
                                                    <select type="text" id="banco" name="banco" placeholder="Banco" class="form-control" required>
                                                        <option <?= $conta->banco == '001' ? 'selected' : '' ?>>001 - Banco do Brasil S.A. </option>
                                                        <option <?= $conta->banco == '003' ? 'selected' : '' ?>>003 - Banco da Amazônia S.A.</option>
                                                        <option <?= $conta->banco == '004' ? 'selected' : '' ?>>004 - Banco do Nordeste do Brasil S.A. </option>
                                                        <option <?= $conta->banco == '012' ? 'selected' : '' ?>>012 - Banco Standard de Investimentos S.A. </option>
                                                        <option <?= $conta->banco == '014' ? 'selected' : '' ?>>014 - Natixis Brasil S.A. Banco Múltiplo </option>
                                                        <option <?= $conta->banco == '019' ? 'selected' : '' ?>>019 - Banco Azteca do Brasil S.A.</option>
                                                        <option <?= $conta->banco == '021' ? 'selected' : '' ?>>021 - BANESTES S.A. Banco do Estado do Espírito Santo </option>
                                                        <option <?= $conta->banco == '024' ? 'selected' : '' ?>>024 - Banco de Pernambuco S.A. </option>
                                                        <option <?= $conta->banco == '025' ? 'selected' : '' ?>>025 - Banco Alfa S.A.</option>
                                                        <option <?= $conta->banco == '029' ? 'selected' : '' ?>>029 - Banco Banerj S.A.</option>
                                                        <option <?= $conta->banco == '031' ? 'selected' : '' ?>>031 - Banco Beg S.A.</option>
                                                        <option <?= $conta->banco == '033' ? 'selected' : '' ?>>033 - Banco Santander (Brasil) S.A. </option>
                                                        <option <?= $conta->banco == '036' ? 'selected' : '' ?>>036 - Banco Bradesco BBI S.A.</option>
                                                        <option <?= $conta->banco == '037' ? 'selected' : '' ?>>037 - Banco do Estado do Pará S.A. </option>
                                                        <option <?= $conta->banco == '039' ? 'selected' : '' ?>>039 - Banco do Estado do Piauí S.A. </option>
                                                        <option <?= $conta->banco == '040' ? 'selected' : '' ?>>040 - Banco Cargill S.A.</option>
                                                        <option <?= $conta->banco == '041' ? 'selected' : '' ?>>041 - Banco do Estado do Rio Grande do Sul S.A. </option>
                                                        <option <?= $conta->banco == '044' ? 'selected' : '' ?>>044 - Banco BVA S.A.</option>
                                                        <option <?= $conta->banco == '045' ? 'selected' : '' ?>>045 - Banco Opportunity S.A. </option>
                                                        <option <?= $conta->banco == '047' ? 'selected' : '' ?>>047 - Banco do Estado de Sergipe S.A. </option>
                                                        <option <?= $conta->banco == '062' ? 'selected' : '' ?>>062 - Hipercard Banco Múltiplo S.A. </option>
                                                        <option <?= $conta->banco == '063' ? 'selected' : '' ?>>063 - Banco Ibi S.A. Banco Múltiplo </option>
                                                        <option <?= $conta->banco == '064' ? 'selected' : '' ?>>064 - Goldman Sachs do Brasil Banco Múltiplo S.A. </option>
                                                        <option <?= $conta->banco == '065' ? 'selected' : '' ?>>065 - Banco Bracce S.A.</option>
                                                        <option <?= $conta->banco == '066' ? 'selected' : '' ?>>066 - Banco Morgan Stanley S.A. </option>
                                                        <option <?= $conta->banco == '069' ? 'selected' : '' ?>>069 - BPN Brasil Banco Múltiplo S.A. </option>
                                                        <option <?= $conta->banco == '070' ? 'selected' : '' ?>>070 - BRB </option>
                                                        <option <?= $conta->banco == '072' ? 'selected' : '' ?>>072 - Banco Rural Mais S.A. </option>
                                                        <option <?= $conta->banco == '073' ? 'selected' : '' ?>>073 - BB Banco Popular do Brasil S.A. </option>
                                                        <option <?= $conta->banco == '074' ? 'selected' : '' ?>>074 - Banco J. Safra S.A. </option>
                                                        <option <?= $conta->banco == '075' ? 'selected' : '' ?>>075 - Banco CR2 S.A.</option>
                                                        <option <?= $conta->banco == '076' ? 'selected' : '' ?>>076 - Banco KDB S.A. </option>
                                                        <option <?= $conta->banco == '077' ? 'selected' : '' ?>>077 - Banco Intermedium S.A. </option>
                                                        <option <?= $conta->banco == '078' ? 'selected' : '' ?>>078 - BES Investimento do Brasil S.A.</option>
                                                        <option <?= $conta->banco == '079' ? 'selected' : '' ?>>079 - JBS Banco S.A. </option>
                                                        <option <?= $conta->banco == '081' ? 'selected' : '' ?>>081 - Concórdia Banco S.A. </option>
                                                        <option <?= $conta->banco == '082' ? 'selected' : '' ?>>082 - Banco Topázio S.A. </option>
                                                        <option <?= $conta->banco == '083' ? 'selected' : '' ?>>083 - Banco da China Brasil S.A.</option>
                                                        <option <?= $conta->banco == '084' ? 'selected' : '' ?>>084 - Unicred Norte do Paraná</option>
                                                        <option <?= $conta->banco == '085' ? 'selected' : '' ?>>085 - Cooperativa Central de Crédito Urbano</option>
                                                        <option <?= $conta->banco == '086' ? 'selected' : '' ?>>086 - OBOE Crédito Financiamento e Investimento S.A. </option>
                                                        <option <?= $conta->banco == '087' ? 'selected' : '' ?>>087 - Cooperativa Unicred Central Santa Catarina </option>
                                                        <option <?= $conta->banco == '088' ? 'selected' : '' ?>>088 - Banco Randon S.A. </option>
                                                        <option <?= $conta->banco == '089' ? 'selected' : '' ?>>089 - Cooperativa de Crédito Rural da Região de Mogiana </option>
                                                        <option <?= $conta->banco == '090' ? 'selected' : '' ?>>090 - Cooperativa Central de Economia e Crédito Mutuo das Unicreds </option>
                                                        <option <?= $conta->banco == '091' ? 'selected' : '' ?>>091 - Unicred Central do Rio Grande do Sul</option>
                                                        <option <?= $conta->banco == '092' ? 'selected' : '' ?>>092 - Brickell S.A. Crédito, financiamento e Investimento </option>
                                                        <option <?= $conta->banco == '094' ? 'selected' : '' ?>>094 - Banco Petra S.A. </option>
                                                        <option <?= $conta->banco == '096' ? 'selected' : '' ?>>096 - Banco BM&F de Serviços de Liquidação e Custódia S.A</option>
                                                        <option <?= $conta->banco == '097' ? 'selected' : '' ?>>097 - Cooperativa Central de Crédito Noroeste Brasileiro Ltda. </option>
                                                        <option <?= $conta->banco == '098' ? 'selected' : '' ?>>098 - Credicorol Cooperativa de Crédito Rural </option>
                                                        <option <?= $conta->banco == '099' ? 'selected' : '' ?>>099 - Cooperativa Central de Economia e Credito Mutuo das Unicreds </option>
                                                        <option <?= $conta->banco == '104' ? 'selected' : '' ?>>104 - Caixa Econômica Federal </option>
                                                        <option <?= $conta->banco == '107' ? 'selected' : '' ?>>107 - Banco BBM S.A.</option>
                                                        <option <?= $conta->banco == '133' ? 'selected' : '' ?>>133 - Cresol Cooperativa Central.</option>
                                                        <option <?= $conta->banco == '168' ? 'selected' : '' ?>>168 - HSBC Finance (Brasil) S.A. </option>
                                                        <option <?= $conta->banco == '184' ? 'selected' : '' ?>>184 - Banco Itaú BBA S.A. </option>
                                                        <option <?= $conta->banco == '204' ? 'selected' : '' ?>>204 - Banco Bradesco Cartões S.A.</option>
                                                        <option <?= $conta->banco == '208' ? 'selected' : '' ?>>208 - Banco BTG Pactual S.A.</option>
                                                        <option <?= $conta->banco == '212' ? 'selected' : '' ?>>212 - Banco Matone S.A. </option>
                                                        <option <?= $conta->banco == '213' ? 'selected' : '' ?>>213 - Banco Arbi S.A</option>
                                                        <option <?= $conta->banco == '214' ? 'selected' : '' ?>>214 - Banco Dibens S.A. </option>
                                                        <option <?= $conta->banco == '215' ? 'selected' : '' ?>>215 - Banco Comercial e de Investimento Sudameris S.A.</option>
                                                        <option <?= $conta->banco == '217' ? 'selected' : '' ?>>217 - Banco John Deere S.A. </option>
                                                        <option <?= $conta->banco == '218' ? 'selected' : '' ?>>218 - Banco Bonsucesso S.A.</option>
                                                        <option <?= $conta->banco == '222' ? 'selected' : '' ?>>222 - Banco Credit Agricole Brasil S.A.</option>
                                                        <option <?= $conta->banco == '224' ? 'selected' : '' ?>>224 - Banco Fibra S.A. </option>
                                                        <option <?= $conta->banco == '225' ? 'selected' : '' ?>>225 - Banco Brascan S.A.</option>
                                                        <option <?= $conta->banco == '229' ? 'selected' : '' ?>>229 - Banco Cruzeiro do Sul S.A.</option>
                                                        <option <?= $conta->banco == '230' ? 'selected' : '' ?>>230 - Unicard Banco Múltiplo S.A.</option>
                                                        <option <?= $conta->banco == '233' ? 'selected' : '' ?>>233 - Banco GE Capital S.A. </option>
                                                        <option <?= $conta->banco == '237' ? 'selected' : '' ?>>237 - Banco Bradesco S.A.</option>
                                                        <option <?= $conta->banco == '241' ? 'selected' : '' ?>>241 - Banco Clássico S.A.</option>
                                                        <option <?= $conta->banco == '243' ? 'selected' : '' ?>>243 - Banco Máxima S.A. </option>
                                                        <option <?= $conta->banco == '246' ? 'selected' : '' ?>>246 - Banco ABC Brasil S.A.</option>
                                                        <option <?= $conta->banco == '248' ? 'selected' : '' ?>>248 - Banco Boavista Interatlântico S.A.</option>
                                                        <option <?= $conta->banco == '249' ? 'selected' : '' ?>>249 - Banco Investcred Unibanco S.A. </option>
                                                        <option <?= $conta->banco == '250' ? 'selected' : '' ?>>250 - Banco Schahin S.A. </option>
                                                        <option <?= $conta->banco == '254' ? 'selected' : '' ?>>254 - Paraná Banco S.A. </option>
                                                        <option <?= $conta->banco == '260' ? 'selected' : '' ?>>260 - NU PAGAMENTOS S.A.</option>
                                                        <option <?= $conta->banco == '263' ? 'selected' : '' ?>>263 - Banco Cacique S.A.</option>
                                                        <option <?= $conta->banco == '265' ? 'selected' : '' ?>>265 - Banco Fator S.A. </option>
                                                        <option <?= $conta->banco == '266' ? 'selected' : '' ?>>266 - Banco Cédula S.A.</option>
                                                        <option <?= $conta->banco == '290' ? 'selected' : '' ?>>290 - Pag Seguro S.A.</option>
                                                        <option <?= $conta->banco == '300' ? 'selected' : '' ?>>300 - Banco de La Nacion Argentina</option>
                                                        <option <?= $conta->banco == '318' ? 'selected' : '' ?>>318 - Banco BMG S.A.</option>
                                                        <option <?= $conta->banco == '320' ? 'selected' : '' ?>>320 - Banco Industrial e Comercial S.A. </option>
                                                        <option <?= $conta->banco == '336' ? 'selected' : '' ?>>336 - Banco C6 S.A.</option>
                                                        <option <?= $conta->banco == '341' ? 'selected' : '' ?>>341 - Itaú Unibanco S.A. </option>
                                                        <option <?= $conta->banco == '356' ? 'selected' : '' ?>>356 - Banco Real S.A. </option>
                                                        <option <?= $conta->banco == '366' ? 'selected' : '' ?>>366 - Banco Société Générale Brasil S.A. </option>
                                                        <option <?= $conta->banco == '370' ? 'selected' : '' ?>>370 - Banco WestLB do Brasil S.A. </option>
                                                        <option <?= $conta->banco == '376' ? 'selected' : '' ?>>376 - Banco J. P. Morgan S.A. </option>
                                                        <option <?= $conta->banco == '389' ? 'selected' : '' ?>>389 - Banco Mercantil do Brasil S.A. </option>
                                                        <option <?= $conta->banco == '394' ? 'selected' : '' ?>>394 - Banco Bradesco Financiamentos S.A.</option>
                                                        <option <?= $conta->banco == '399' ? 'selected' : '' ?>>399 - HSBC Bank Brasil S.A. </option>
                                                        <option <?= $conta->banco == '409' ? 'selected' : '' ?>>409 - UNIBANCO </option>
                                                        <option <?= $conta->banco == '412' ? 'selected' : '' ?>>412 - Banco Capital S.A. </option>
                                                        <option <?= $conta->banco == '422' ? 'selected' : '' ?>>422 - Banco Safra S.A. </option>
                                                        <option <?= $conta->banco == '453' ? 'selected' : '' ?>>453 - Banco Rural S.A. </option>
                                                        <option <?= $conta->banco == '456' ? 'selected' : '' ?>>456 - Banco de Tokyo</option>
                                                        <option <?= $conta->banco == '464' ? 'selected' : '' ?>>464 - Banco Sumitomo Mitsui Brasileiro S.A. </option>
                                                        <option <?= $conta->banco == '473' ? 'selected' : '' ?>>473 - Banco Caixa Geral </option>
                                                        <option <?= $conta->banco == '477' ? 'selected' : '' ?>>477 - Citibank N.A. </option>
                                                        <option <?= $conta->banco == '479' ? 'selected' : '' ?>>479 - Banco ItaúBank S.A </option>
                                                        <option <?= $conta->banco == '487' ? 'selected' : '' ?>>487 - Deutsche Bank S.A. </option>
                                                        <option <?= $conta->banco == '488' ? 'selected' : '' ?>>488 - JPMorgan Chase Bank </option>
                                                        <option <?= $conta->banco == '492' ? 'selected' : '' ?>>492 - ING Bank N.V. </option>
                                                        <option <?= $conta->banco == '494' ? 'selected' : '' ?>>494 - Banco de La Republica Oriental del Uruguay</option>
                                                        <option <?= $conta->banco == '495' ? 'selected' : '' ?>>495 - Banco de La Provincia de Buenos Aires</option>
                                                        <option <?= $conta->banco == '505' ? 'selected' : '' ?>>505 - Banco Credit Suisse (Brasil) S.A.</option>
                                                        <option <?= $conta->banco == '600' ? 'selected' : '' ?>>600 - Banco Luso Brasileiro S.A. </option>
                                                        <option <?= $conta->banco == '604' ? 'selected' : '' ?>>604 - Banco Industrial do Brasil S.A. </option>
                                                        <option <?= $conta->banco == '610' ? 'selected' : '' ?>>610 - Banco VR S.A. </option>
                                                        <option <?= $conta->banco == '611' ? 'selected' : '' ?>>611 - Banco Paulista S.A. </option>
                                                        <option <?= $conta->banco == '612' ? 'selected' : '' ?>>612 - Banco Guanabara S.A. </option>
                                                        <option <?= $conta->banco == '613' ? 'selected' : '' ?>>613 - Banco Pecúnia S.A. </option>
                                                        <option <?= $conta->banco == '623' ? 'selected' : '' ?>>623 - Banco Panamericano S.A. </option>
                                                        <option <?= $conta->banco == '626' ? 'selected' : '' ?>>626 - Banco Ficsa S.A. </option>
                                                        <option <?= $conta->banco == '630' ? 'selected' : '' ?>>630 - Banco Intercap S.A. </option>
                                                        <option <?= $conta->banco == '633' ? 'selected' : '' ?>>633 - Banco Rendimento S.A. </option>
                                                        <option <?= $conta->banco == '634' ? 'selected' : '' ?>>634 - Banco Triângulo S.A. </option>
                                                        <option <?= $conta->banco == '637' ? 'selected' : '' ?>>637 - Banco Sofisa S.A. </option>
                                                        <option <?= $conta->banco == '638' ? 'selected' : '' ?>>638 - Banco Prosper S.A. </option>
                                                        <option <?= $conta->banco == '641' ? 'selected' : '' ?>>641 - Banco Alvorada S.A.</option>
                                                        <option <?= $conta->banco == '643' ? 'selected' : '' ?>>643 - Banco Pine S.A. </option>
                                                        <option <?= $conta->banco == '652' ? 'selected' : '' ?>>652 - Itaú Unibanco Holding S.A. </option>
                                                        <option <?= $conta->banco == '653' ? 'selected' : '' ?>>653 - Banco Indusval S.A. </option>
                                                        <option <?= $conta->banco == '654' ? 'selected' : '' ?>>654 - Banco A.J.Renner S.A.</option>
                                                        <option <?= $conta->banco == '655' ? 'selected' : '' ?>>655 - Banco Votorantim S.A. </option>
                                                        <option <?= $conta->banco == '707' ? 'selected' : '' ?>>707 - Banco Daycoval S.A.</option>
                                                        <option <?= $conta->banco == '719' ? 'selected' : '' ?>>719 - Banif</option>
                                                        <option <?= $conta->banco == '721' ? 'selected' : '' ?>>721 - Banco Credibel S.A.</option>
                                                        <option <?= $conta->banco == '724' ? 'selected' : '' ?>>724 - Banco Porto Seguro S.A. </option>
                                                        <option <?= $conta->banco == '734' ? 'selected' : '' ?>>734 - Banco Gerdau S.A. </option>
                                                        <option <?= $conta->banco == '735' ? 'selected' : '' ?>>735 - Banco Pottencial S.A. </option>
                                                        <option <?= $conta->banco == '738' ? 'selected' : '' ?>>738 - Banco Morada S.A. </option>
                                                        <option <?= $conta->banco == '739' ? 'selected' : '' ?>>739 - Banco BGN S.A.</option>
                                                        <option <?= $conta->banco == '740' ? 'selected' : '' ?>>740 - Banco Barclays S.A.</option>
                                                        <option <?= $conta->banco == '741' ? 'selected' : '' ?>>741 - Banco Ribeirão Preto S.A. </option>
                                                        <option <?= $conta->banco == '743' ? 'selected' : '' ?>>743 - Banco Semear S.A. </option>
                                                        <option <?= $conta->banco == '744' ? 'selected' : '' ?>>744 - BankBoston N.A. </option>
                                                        <option <?= $conta->banco == '745' ? 'selected' : '' ?>>745 - Banco Citibank S.A.</option>
                                                        <option <?= $conta->banco == '746' ? 'selected' : '' ?>>746 - Banco Modal S.A. </option>
                                                        <option <?= $conta->banco == '747' ? 'selected' : '' ?>>747 - Banco Rabobank International Brasil S.A. </option>
                                                        <option <?= $conta->banco == '748' ? 'selected' : '' ?>>748 - Banco Cooperativo Sicredi S.A.</option>
                                                        <option <?= $conta->banco == '749' ? 'selected' : '' ?>>749 - Banco Simples S.A. </option>
                                                        <option <?= $conta->banco == '751' ? 'selected' : '' ?>>751 - Dresdner Bank Brasil S.A. </option>
                                                        <option <?= $conta->banco == '752' ? 'selected' : '' ?>>752 - Banco BNP Paribas Brasil S.A.</option>
                                                        <option <?= $conta->banco == '753' ? 'selected' : '' ?>>753 - NBC Bank Brasil S.A. </option>
                                                        <option <?= $conta->banco == '755' ? 'selected' : '' ?>>755 - Bank of America Merrill Lynch Banco Múltiplo S.A. </option>
                                                        <option <?= $conta->banco == '756' ? 'selected' : '' ?>>756 - Banco Cooperativo do Brasil S.A.</option>
                                                        <option <?= $conta->banco == '757' ? 'selected' : '' ?>>757 - Banco KEB do Brasil S.A. </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="box-input">
                                                <label class="con-label">AGÊNCIA:<sup style="color: red;">*</sup></label>
                                                <input type="text" id="agencia" name="agencia" class="form-control" value="<?= $conta->agencia ?>" required />
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="box-input">
                                                <label class="con-label">CONTA:<sup style="color: red;">*</sup></label>
                                                <input type="text" id="conta" name="conta" class="form-control" value="<?= $conta->conta ?>" required />
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="box-input">
                                                <label class="control-label">TIPO DA CONTA:<sup style="color: red;">*</sup></label>
                                                <div class="input-prepend">
                                                    <select type="text" id="tipo_conta" name="tipo" class="form-control" required>
                                                        <option value="corrente" <?= $conta->tipo == 'corrente' ? 'selected' : '' ?>>Conta Corrente</option>
                                                        <option value="poupanca" <?= $conta->tipo == 'poupanca' ? 'selected' : '' ?>>Poupança</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br></br>
                                    <div class="line-dados control-group">
                                        <div class="col-sm-3">
                                            <div class="box-input" style="margin-top: 20px;">
                                                <label class="con-label">OPERAÇÃO:<sup style="color: red;">*</sup></label>
                                                <input type="text" id="oper" name="operacao" class="form-control" value="<?= $conta->operacao ?>" required />
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="box-input">
                                                <label class="con-label">CPF/CNPJ DO TITULAR DA CONTA:<sup style="color: red;">*</sup></label>
                                                <input type="text" id="d_cpf" name="cpf" class="form-control" placeholder="000.000.000-00 ou 00.000.000/0000-00" value="<?= $conta->cpf ?>" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Editar</button>
                                    </div>
                            </form>
                        </div>

                    </div>
                </div>
    </div>
<?php endforeach; ?>
<?php else : ?>
<?php endif; ?>


</div>
</div>

<?php $cript = $_GET['id']; ?>
<script>
    function tonarPadrao(id) {
        var url = "<?= site_url('instaladores/upgrade_contaById') ?>";
        $.ajax({
            type: "post",
            url: url,
            data: {
                id: id
            },
            success: function() {
                // TROCA CLASS BTN
                $('.btn-xs').removeClass('btn-success');
                $('.btn-xs').addClass('btn-info');
                // INSERE CLASSE SUCCESS NO BTN SELECIONADO
                $('#iconUp' + id).removeClass('btn-info');
                $('#iconUp' + id).addClass('btn-success');
                // TROCA STATUS DE STATUS 1 PARA 0
                $('.alt').html("<span class='label label-info'>Secundária</span>");
                // TROCA STATUS DE STATUS 0 PARA 1
                $('#status' + id).html("<span class='label label-success'>Principal</span>");
                // ADICIONA CLASS ALT
                $('#status' + id).addClass('alt');
                alert('Upgrade de conta realizada com sucesso');
            }

        })
    }

    function cpf(cpf) {
        cpf = cpf.replace(/\D/g, '');
        if (cpf.toString().length != 11 || /^(\d)\1{10}$/.test(cpf)) result = false;
        var result = true;
        [9, 10].forEach(function(j) {
            var soma = 0,
                r;
            cpf.split(/(?=)/).splice(0, j).forEach(function(e, i) {
                soma += parseInt(e) * ((j + 2) - (i + 1));
            });
            r = soma % 11;
            r = (r < 2) ? 0 : 11 - r;
            if (r != cpf.substring(j, j + 1)) result = false;
        });

        if (result == false)
            alert('CPF Inválido!');
    }

    $(document).ready(function() {
        $("#cep").mask("00.000-000");
        $("#numero").mask("000000000");
        $("#conta").mask("000000000");
        $("#agencia").mask("000000000");
        $("#oper").mask("000000000");
        $("#telefone").mask("(00) 0000-0000");
        $("#celular").mask("(00) 0000-0000");
        $("#cpf_conta").mask("999.999.999-99");
        $("#pis").mask("000.00000.00-0");
        $("#rg").mask("99999999999999");

        var cpfMascara = function(val) {
                return val.replace(/\D/g, '').length > 11 ? '00.000.000/0000-00' : '000.000.000-000';
            },
            cpfOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(cpfMascara.apply({}, arguments), options);
                }
            };
        $('#cnpj').mask(cpfMascara, cpfOptions);

        $('#b_cpf').mask(cpfMascara, cpfOptions);

        $('#cpf').mask(cpfMascara, cpfOptions);

        $('#d_cpf').mask(cpfMascara, cpfOptions);

        $.getJSON('editar_install?id=<?php echo $cript ?>', function(data) {
            console.log(data);
            $('#id').val(data[0].id);
            $('#nome').val(data[0].nome);
            $('#sobrenome').val(data[0].sobrenome);
            $('#cpf').val(data[0].cpf);
            $('#cnpj').val(data[0].cnpj);
            $('#endereco').val(data[0].endereco);
            $('#email').val(data[0].email);
            $('#cidade').val(data[0].cidade);
            $('#bairro').val(data[0].bairro);
            $('#numero').val(data[0].numero);
            $('#estado').val(data[0].estado);
            $('#cep').val(data[0].cep);
            $('#compl').val(data[0].complemento);
            $('#telefone').val(data[0].telefone);
            $('#celular').val(data[0].celular);
            $('#valorIns').val(data[0].valor_instalacao);
            $('#valorMan').val(data[0].valor_manutencao);
            $('#valorRet').val(data[0].valor_retirada);
            $('#vDeslocamento').val(data[0].valor_desloc_km);
            $('#pis').val(data[0].pis);
            $('#rg').val(data[0].rg);
            $('#estado_civil').val(data[0].estado_civil);

            //verifica se foi passada uma data de nascimento válida e formata para o padrão br
            if(!isNaN(new Date(data[0].data_nascimento)))
                $('#data_nascimento').val(new Date(data[0].data_nascimento).toLocaleDateString("pt-BR"));

            $("#data_nascimento").datepicker({
                format: "dd/mm/yyyy",
                changeMonth: true,
                changeYear: true,
                yearRange: "1960:+nn"
            });
        });

        $('#salvarInst').on('click', function() {

            dataNascimento = $('#data_nascimento').val();

            if ($('#data_nascimento').val() == "") {
                alert("É necessário informar a data de nascimento do instalador,para selecionar a data é necessário clicar no dia exato para validar.");
                $("#data_nascimento").focus();
                return false;

            } else {
                //ano máximo (idade de 70)
                let anoMaximo = parseInt(new Date().getFullYear()) - 70;
                //idade minima (idade de 18)
                let anoMinimo = parseInt(new Date().getFullYear()) - 16;
                //ano do nascimento informado no cadastro
                let anoNascimento = parseInt(dataNascimento.substring(6));

                //verifica se o ano informado está dentro desse intervalo
                if (anoNascimento > anoMinimo || anoNascimento < anoMaximo) {
                    alert("É necessário informar uma data de nascimento válida, o instalador DEVE ter no mínimo 16 anos de idade.");
                    $("#data_nascimento").focus();
                    return false;
                }
            }

            $.ajax({
                url: 'saveUpdate',
                type: 'POST',
                data: {
                    'id': $('#id').val(),
                    'nome': $('#nome').val(),
                    'sobrenome': $('#sobrenome').val(),
                    'cpf': $('#cpf').val(),
                    'cnpj': $('#cnpj').val(),
                    'endereco': $('#endereco').val(),
                    'email': $('#email').val(),
                    'cidade': $('#cidade').val(),
                    'bairro': $('#bairro').val(),
                    'numero': $('#numero').val(),
                    'estado': $('#estado').val(),
                    'cep': $('#cep').val(),
                    'complemento': $('#compl').val(),
                    'telefone': $('#telefone').val(),
                    'celular': $('#celular').val(),
                    'valor_instalacao': $('#valorIns').val(),
                    'valor_manutencao': $('#valorMan').val(),
                    'valor_retirada': $('#valorRet').val(),
                    'valor_desloc_km': $('#vDeslocamento').val(),
                    'data_nascimento': $('#data_nascimento').val(),
                    'rg': $('#rg').val(),
                    'pis': $('#pis').val(),
                    'estado_civil': $('#estado_civil').val()
                },
                success: function() {
                    $('#alert').html(
                        '<div class="alert alert-dismissible alert-success">' +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                        '<strong>Sucesso!!</strong> Alteraçao realizada.' +
                        '</div>');
                },
                error: function() {
                    $('#alert').html(
                        '<div class="alert alert-dismissible alert-danger">' +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                        '<strong>Falha!!</strong> Desculpe, não foi possivel realizar a alteração.' +
                        '</div>');
                }
            });
        });

    });
</script>