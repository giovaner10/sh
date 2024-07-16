<script src="<?= base_url('assets/js/jquery.mask.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.mask.min.js') ?>"></script>
<style>
    .main {
        width: 100% !important;
    }

    .line-group {
        display: flex;
    }

    .ctRadio {
        margin-left: 30px !important;
    }

    .control-label:after {
        content: unset;
        color: unset;
    }
</style>

<body>
    <div class='container'>
        <div id='content' class='row-fluid'>
            <div class='span8 main'>
                <?php if (!$retorno && $block) : ?>
                    <div>
                        <div class="tab-pane active in" id="create">
                            <div class="alert alert-info">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <b>Intaladores/Prestadores: <?php echo $qtd_instaladores; ?> </b> cadastrados em nossa plataforma.
                            </div>
                            <form name="myForm" action="<?php echo site_url('instaladores/add') ?>" method="post" class="form-horizontal formulario" id="signup" onsubmit="return validateForm()">
                                <div class="well well-small botoes-acao">
                                    <div class="btn-group">
                                        <a href="<?php echo site_url('instaladores/listar_instaladores') ?>" class="btn btn-primary voltar" title="Voltar" style="margin-right: 1rem;"><i class="fa fa-arrow-left"></i></a>
                                        <?php if ($this->auth->get_login('admin', 'email')) : ?>
                                            <a href="<?= site_url('instaladores/listar_instaladores') ?>">
                                                <button class="btn btn-primary">Listar Instaladores</button>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="btn-group pull-right">
                                        <button type="submit" class="salvar btn btn-primary" title="Salva os dados preenchidos">
                                            Salvar
                                        </button>
                                    </div>
                                </div>
                                <div class="resultado span6" style="float: none;"></div>
                                <div class="row-fluid">
                                    <div class="tabbable">
                                        <ul class="nav nav-tabs">
                                            <li class="tab1 active"><a href="#tab1" data-toggle="tab">Dados Pessoais</a></li>
                                            <li class="tab3"><a href="#tab3" data-toggle="tab">Contato</a></li>
                                            <li class="tab4"><a href="#tab4" data-toggle="tab">Endereço</a></li>
                                            <li class="tab4"><a href="#tab5" data-toggle="tab">Valores Serviços</a></li>
                                            <li class="tab4"><a href="#tab6" data-toggle="tab">Dados Bancários</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab1">
                                                <div class="row dados">
                                                    <div class="col-sm-12">
                                                        <fieldset class="col-sm-12" id="0">
                                                            <br>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">NOME:<sup style="color: red;">*</sup></label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" name="nome" id="cNome" value="" class="form-control letras">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">SOBRENOME:<sup style="color: red;">*</sup></label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" name="sobrenome" id="cSobrenome" value="" class="form-control letras">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">DATA DE NASCIMENTO:<sup style="color: red;">*</sup></label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" id="data_nascimento" class='form-control' name='data_nascimento' readonly='true' required="required">
                                                                </div>
                                                            </div>

                                                            <div style="display: inline-flex; margin-left: 80px;">
                                                                <div class="line-group">
                                                                    <div class="col-sm-3">
                                                                        <label class="control-label">CPF:<sup style="color: red;">*</sup></label>
                                                                        <div class="controls ctRadio">
                                                                            <input type="radio" id="radioCPF" class="input-medium" checked value="cpf" name="radioDoc">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <label class="control-label">CNPJ:<sup style="color: red;">*</sup></label>
                                                                        <div class="controls ctRadio">
                                                                            <input type="radio" class="input-medium" id="radioCNPJ" value="cnpj" name="radioDoc">
                                                                        </div>
                                                                    </div>
                                                                    <div id="divCPF" class="control-group ">
                                                                        <label class="control-label"></label>
                                                                        <div class="input-group input-group-sm">
                                                                            <input type="text" class="form-control cpf" id="cCPF" name="cpf" placeholder="000.000.000-00">
                                                                        </div>
                                                                    </div>
                                                                    <div id="divCNPJ" class="control-group">
                                                                        <label class="control-label"></label>
                                                                        <div class="input-group input-group-sm">
                                                                            <input type="text" class="form-control" id="cCNPJ" name="cnpj" placeholder="00.000.000/0000-00">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br></br>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">RG:</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" name="rg" value="" id="cRg" class="form-control" maxlength="14" placeholder="0.000-000">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">PIS:</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" name="pis" value="" id="cPis" class="form-control" placeholder="000.00000.00-0">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">ESTADO CIVIL:</label>
                                                                <div class="col-sm-6">
                                                                    <select id="cEstadoCivil" name="estado_civil" class="form-control" required>
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

                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">E-MAIL:<sup style="color: red;">*</sup></label>
                                                                <div class="col-sm-6">
                                                                    <input type="email" name="email" value="" id="cEmail" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">SENHA:</label>
                                                                <div class="col-sm-6">
                                                                    <input type="password" name="senha" id="cSenha" value="" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">CONFIRME A SENHA:</label>
                                                                <div class="col-sm-6">
                                                                    <input type="password" name="rsenha" id="ccSenha" value="" class="form-control" />
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab3">
                                                <div class="row dados">
                                                    <div class="col-sm-12">
                                                        <fieldset class="col-sm-12" id="0">
                                                            <br>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">TELEFONE:<sup style="color: red;">*</sup></label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" name="telefone" value="" id="cTelefone" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">CELULAR:</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" name="celular" value="" id="cCelular" class="form-control">
                                                                </div>
                                                            </div>
                                                            <br></br><br></br><br></br><br></br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab4">
                                                <div class="row dados">
                                                    <div class="col-sm-12">
                                                        <fieldset class="col-sm-12" id="0">
                                                            <br>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">CEP:<sup style="color: red;">*</sup></label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" name="cep" value="" id="cCEP" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">RUA:<sup style="color: red;">*</sup></label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" name="endereco" value="" id="cEndereco" class="form-control letras">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">NÚMERO:<sup style="color: red;">*</sup></label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" name="numero" value="" id="cNumero" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">BAIRRO:<sup style="color: red;">*</sup></label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" name="bairro" value="" id="cBairro" class="form-control letras">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-2">ESTADO:<sup style="color: red;">*</sup></label>
                                                                <div class="col-sm-6">
                                                                    <select id="cEstado" name="estado" class="form-control">
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
                                                                    <input type="text" name="cidade" value="" id="cCidade" class="form-control letras">
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane" id="tab5">
                                                <br>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">VALOR INSTALAÇÃO:</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" id="vInstalacao" name="valor_instalacao" placeholder="R$" class="form-control money2">
                                                    </div>
                                                    <h4 style="font-family:arial;"><span class="label label-danger">Média: R$ <?php echo round($valores[0]->instalacao, 2); ?></span></h4>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">VALOR MANUTENÇÃO:</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" id="vManutencao" name="valor_manutencao" placeholder="R$" class="form-control money2">
                                                    </div>
                                                    <h4 style="font-family:arial;"><span class="label label-danger">Média: R$ <?php echo round($valores[0]->manutencao, 2); ?></span></h4>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">VALOR RETIRADA:</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" id="vRetirada" name="valor_retirada" placeholder="R$" class="form-control money2">
                                                    </div>
                                                    <h4 style="font-family:arial;"><span class="label label-danger">Média: R$ <?php echo round($valores[0]->retirada, 2); ?></span></h4>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">VALOR DE DESLOCAMENTO PARA FORA DA REGIÃO METROPOLITANA POR KM:</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" id="vDeslocamento" name="valor_desloc_km" placeholder="R$" class="form-control money2">
                                                    </div>
                                                    <h4 style="font-family:arial;"><span class="label label-danger">Média: R$ <?php echo round($valores[0]->desloc, 2); ?></span></h4>
                                                </div>
                                            </div>

                                            <div class="tab-pane" id="tab6">
                                                <br>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">TITULAR DA CONTA:<sup style="color: red;">*</sup></label>
                                                    <div class="col-sm-6">
                                                        <input class="form-control letras" type="text" id="tt_conta" name="titular_conta">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">BANCO:<sup style="color: red;">*</sup></label>
                                                    <div class="col-sm-6">
                                                        <select type="text" class="form-control" id="cBanco" name="banco" placeholder="Banco">
                                                            <option value="001">001 - Banco do Brasil S.A. </option>
                                                            <option value="003">003 - Banco da Amazônia S.A.</option>
                                                            <option value="004">004 - Banco do Nordeste do Brasil S.A. </option>
                                                            <option value="012">012 - Banco Standard de Investimentos S.A. </option>
                                                            <option value="014">014 - Natixis Brasil S.A. Banco Múltiplo </option>
                                                            <option value="019">019 - Banco Azteca do Brasil S.A.</option>
                                                            <option value="021">021 - BANESTES S.A. Banco do Estado do Espírito Santo </option>
                                                            <option value="024">024 - Banco de Pernambuco S.A. </option>
                                                            <option value="025">025 - Banco Alfa S.A.</option>
                                                            <option value="029">029 - Banco Banerj S.A.</option>
                                                            <option value="031">031 - Banco Beg S.A.</option>
                                                            <option value="033">033 - Banco Santander (Brasil) S.A. </option>
                                                            <option value="036">036 - Banco Bradesco BBI S.A.</option>
                                                            <option value="037">037 - Banco do Estado do Pará S.A. </option>
                                                            <option value="039">039 - Banco do Estado do Piauí S.A. </option>
                                                            <option value="040">040 - Banco Cargill S.A.</option>
                                                            <option value="041">041 - Banco do Estado do Rio Grande do Sul S.A. </option>
                                                            <option value="044">044 - Banco BVA S.A.</option>
                                                            <option value="045">045 - Banco Opportunity S.A. </option>
                                                            <option value="047">047 - Banco do Estado de Sergipe S.A. </option>
                                                            <option value="062">062 - Hipercard Banco Múltiplo S.A. </option>
                                                            <option value="063">063 - Banco Ibi S.A. Banco Múltiplo </option>
                                                            <option value="064">064 - Goldman Sachs do Brasil Banco Múltiplo S.A. </option>
                                                            <option value="065">065 - Banco Bracce S.A.</option>
                                                            <option value="066">066 - Banco Morgan Stanley S.A. </option>
                                                            <option value="069">069 - BPN Brasil Banco Múltiplo S.A. </option>
                                                            <option value="070">070 - BRB </option>
                                                            <option value="072">072 - Banco Rural Mais S.A. </option>
                                                            <option value="073">073 - BB Banco Popular do Brasil S.A. </option>
                                                            <option value="074">074 - Banco J. Safra S.A. </option>
                                                            <option value="075">075 - Banco CR2 S.A.</option>
                                                            <option value="076">076 - Banco KDB S.A. </option>
                                                            <option value="077">077 - Banco Intermedium S.A. </option>
                                                            <option value="078">078 - BES Investimento do Brasil S.A.</option>
                                                            <option value="079">079 - JBS Banco S.A. </option>
                                                            <option value="081">081 - Concórdia Banco S.A. </option>
                                                            <option value="082">082 - Banco Topázio S.A. </option>
                                                            <option value="083">083 - Banco da China Brasil S.A.</option>
                                                            <option value="084">084 - Unicred Norte do Paraná</option>
                                                            <option value="085">085 - Cooperativa Central de Crédito Urbano</option>
                                                            <option value="086">086 - OBOE Crédito Financiamento e Investimento S.A. </option>
                                                            <option value="087">087 - Cooperativa Unicred Central Santa Catarina </option>
                                                            <option value="088">088 - Banco Randon S.A. </option>
                                                            <option value="089">089 - Cooperativa de Crédito Rural da Região de Mogiana </option>
                                                            <option value="090">090 - Cooperativa Central de Economia e Crédito Mutuo das Unicreds </option>
                                                            <option value="091">091 - Unicred Central do Rio Grande do Sul</option>
                                                            <option value="092">092 - Brickell S.A. Crédito, financiamento e Investimento </option>
                                                            <option value="094">094 - Banco Petra S.A. </option>
                                                            <option value="096">096 - Banco BM&F de Serviços de Liquidação e Custódia S.A</option>
                                                            <option value="097">097 - Cooperativa Central de Crédito Noroeste Brasileiro Ltda. </option>
                                                            <option value="098">098 - Credicorol Cooperativa de Crédito Rural </option>
                                                            <option value="099">099 - Cooperativa Central de Economia e Credito Mutuo das Unicreds </option>
                                                            <option value="104">104 - Caixa Econômica Federal </option>
                                                            <option value="107">107 - Banco BBM S.A.</option>
                                                            <option value="168">168 - HSBC Finance (Brasil) S.A. </option>
                                                            <option value="184">184 - Banco Itaú BBA S.A. </option>
                                                            <option value="204">204 - Banco Bradesco Cartões S.A.</option>
                                                            <option value="208">208 - Banco BTG Pactual S.A.</option>
                                                            <option value="212">212 - Banco Matone S.A. </option>
                                                            <option value="213">213 - Banco Arbi S.A</option>
                                                            <option value="214">214 - Banco Dibens S.A. </option>
                                                            <option value="215">215 - Banco Comercial e de Investimento Sudameris S.A.</option>
                                                            <option value="217">217 - Banco John Deere S.A. </option>
                                                            <option value="218">218 - Banco Bonsucesso S.A.</option>
                                                            <option value="222">222 - Banco Credit Agricole Brasil S.A.</option>
                                                            <option value="224">224 - Banco Fibra S.A. </option>
                                                            <option value="225">225 - Banco Brascan S.A.</option>
                                                            <option value="229">229 - Banco Cruzeiro do Sul S.A.</option>
                                                            <option value="230">230 - Unicard Banco Múltiplo S.A.</option>
                                                            <option value="233">233 - Banco GE Capital S.A. </option>
                                                            <option value="237">237 - Banco Bradesco S.A.</option>
                                                            <option value="241">241 - Banco Clássico S.A.</option>
                                                            <option value="243">243 - Banco Máxima S.A. </option>
                                                            <option value="246">246 - Banco ABC Brasil S.A.</option>
                                                            <option value="248">248 - Banco Boavista Interatlântico S.A.</option>
                                                            <option value="249">249 - Banco Investcred Unibanco S.A. </option>
                                                            <option value="250">250 - Banco Schahin S.A. </option>
                                                            <option value="254">254 - Paraná Banco S.A. </option>
                                                            <option value="260">260 - NU PAGAMENTOS S.A.</option>
                                                            <option value="263">263 - Banco Cacique S.A.</option>
                                                            <option value="265">265 - Banco Fator S.A. </option>
                                                            <option value="266">266 - Banco Cédula S.A.</option>
                                                            <option value="290">290 - Pag Seguro S.A.</option>
                                                            <option value="300">300 - Banco de La Nacion Argentina</option>
                                                            <option value="318">318 - Banco BMG S.A.</option>
                                                            <option value="320">320 - Banco Industrial e Comercial S.A. </option>
                                                            <option value="336">336 - Banco C6 S.A.</option>
                                                            <option value="341">341 - Itaú Unibanco S.A. </option>
                                                            <option value="356">356 - Banco Real S.A. </option>
                                                            <option value="366">366 - Banco Société Générale Brasil S.A. </option>
                                                            <option value="370">370 - Banco WestLB do Brasil S.A. </option>
                                                            <option value="376">376 - Banco J. P. Morgan S.A. </option>
                                                            <option value="389">389 - Banco Mercantil do Brasil S.A. </option>
                                                            <option value="394">394 - Banco Bradesco Financiamentos S.A.</option>
                                                            <option value="399">399 - HSBC Bank Brasil S.A. </option>
                                                            <option value="409">409 - UNIBANCO </option>
                                                            <option value="412">412 - Banco Capital S.A. </option>
                                                            <option value="422">422 - Banco Safra S.A. </option>
                                                            <option value="453">453 - Banco Rural S.A. </option>
                                                            <option value="456">456 - Banco de Tokyo</option>
                                                            <option value="464">464 - Banco Sumitomo Mitsui Brasileiro S.A. </option>
                                                            <option value="473">473 - Banco Caixa Geral </option>
                                                            <option value="477">477 - Citibank N.A. </option>
                                                            <option value="479">479 - Banco ItaúBank S.A </option>
                                                            <option value="487">487 - Deutsche Bank S.A. </option>
                                                            <option value="488">488 - JPMorgan Chase Bank </option>
                                                            <option value="492">492 - ING Bank N.V. </option>
                                                            <option value="494">494 - Banco de La Republica Oriental del Uruguay</option>
                                                            <option value="495">495 - Banco de La Provincia de Buenos Aires</option>
                                                            <option value="505">505 - Banco Credit Suisse (Brasil) S.A.</option>
                                                            <option value="600">600 - Banco Luso Brasileiro S.A. </option>
                                                            <option value="604">604 - Banco Industrial do Brasil S.A. </option>
                                                            <option value="610">610 - Banco VR S.A. </option>
                                                            <option value="611">611 - Banco Paulista S.A. </option>
                                                            <option value="612">612 - Banco Guanabara S.A. </option>
                                                            <option value="613">613 - Banco Pecúnia S.A. </option>
                                                            <option value="623">623 - Banco Panamericano S.A. </option>
                                                            <option value="626">626 - Banco Ficsa S.A. </option>
                                                            <option value="630">630 - Banco Intercap S.A. </option>
                                                            <option value="633">633 - Banco Rendimento S.A. </option>
                                                            <option value="634">634 - Banco Triângulo S.A. </option>
                                                            <option value="637">637 - Banco Sofisa S.A. </option>
                                                            <option value="638">638 - Banco Prosper S.A. </option>
                                                            <option value="641">641 - Banco Alvorada S.A.</option>
                                                            <option value="643">643 - Banco Pine S.A. </option>
                                                            <option value="652">652 - Itaú Unibanco Holding S.A. </option>
                                                            <option value="653">653 - Banco Indusval S.A. </option>
                                                            <option value="654">654 - Banco A.J.Renner S.A.</option>
                                                            <option value="655">655 - Banco Votorantim S.A. </option>
                                                            <option value="707">707 - Banco Daycoval S.A.</option>
                                                            <option value="719">719 - Banif</option>
                                                            <option value="721">721 - Banco Credibel S.A.</option>
                                                            <option value="724">724 - Banco Porto Seguro S.A. </option>
                                                            <option value="734">734 - Banco Gerdau S.A. </option>
                                                            <option value="735">735 - Banco Pottencial S.A. </option>
                                                            <option value="738">738 - Banco Morada S.A. </option>
                                                            <option value="739">739 - Banco BGN S.A.</option>
                                                            <option value="740">740 - Banco Barclays S.A.</option>
                                                            <option value="741">741 - Banco Ribeirão Preto S.A. </option>
                                                            <option value="743">743 - Banco Semear S.A. </option>
                                                            <option value="744">744 - BankBoston N.A. </option>
                                                            <option value="745">745 - Banco Citibank S.A.</option>
                                                            <option value="746">746 - Banco Modal S.A. </option>
                                                            <option value="747">747 - Banco Rabobank International Brasil S.A. </option>
                                                            <option value="748">748 - Banco Cooperativo Sicredi S.A.</option>
                                                            <option value="749">749 - Banco Simples S.A. </option>
                                                            <option value="751">751 - Dresdner Bank Brasil S.A. </option>
                                                            <option value="752">752 - Banco BNP Paribas Brasil S.A.</option>
                                                            <option value="753">753 - NBC Bank Brasil S.A. </option>
                                                            <option value="755">755 - Bank of America Merrill Lynch Banco Múltiplo S.A. </option>
                                                            <option value="756">756 - Banco Cooperativo do Brasil S.A.</option>
                                                            <option value="757">757 - Banco KEB do Brasil S.A. </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">AGÊNCIA:<sup style="color: red;">*</sup></label>
                                                    <div class="col-sm-6">
                                                        <input class="form-control" type="text" id="cAgencia" name="agencia">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">CONTA:<sup style="color: red;">*</sup></label>
                                                    <div class="col-sm-6">
                                                        <input class="form-control" type="text" id="cConta" name="conta">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">OPERAÇÃO:</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" id="cOper" name="operacao" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">TIPO DA CONTA:<sup style="color: red;">*</sup></label>
                                                    <div class="col-sm-6">
                                                        <select class="form-control" type="text" id="cTipoConta" name="tipo_conta">
                                                            <option selected disabled value="">Selecione um tipo de conta</option>
                                                            <option value="corrente">Conta Corrente</option>
                                                            <option value="poupanca">Poupança</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div style="display: inline-flex; margin-left: 85px;">
                                                    <div class="line-group">
                                                        <div class="col-sm-3">
                                                            <label class="control-label">CPF:<sup style="color: red;">*</sup></label>
                                                            <div class="controls ctRadio">
                                                                <input type="radio" id="radioCPF_titular" class="input-medium" checked value="cpf" name="radio_cpf_titular">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label class="control-label">CNPJ:<sup style="color: red;">*</sup></label>
                                                            <div class="controls ctRadio">
                                                                <input type="radio" class="input-medium" id="radioCNPJ_titular" value="cnpj" name="radio_cnpj_titular">
                                                            </div>
                                                        </div>
                                                        <div id="divCPF_titular" class="control-group ">
                                                            <label class="control-label"></label>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control cpf" id="cCPF_titular" name="cpf_titular" placeholder="000.000.000-00">
                                                            </div>
                                                        </div>
                                                        <div id="divCNPJ_titular" class="control-group">
                                                            <label class="control-label"></label>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" id="cCNPJ_titular" name="cnpj_titular" placeholder="00.000.000/0000-00">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
            </div>
        <?php elseif (!$retorno) : ?>
            <div class="alert alert-danger" style="margin-top: 60px;">
                <span class="mensagem">Email já cadastrado!, verifique e tente novamente!</span>
            </div>
        <?php else : ?>
            <div class="alert alert-success" style="margin-top: 60px;">
                <span class="mensagem">Instalador cadastrado com sucesso!</span>
            </div>
        <?php endif; ?>
        </div>
        <div class='span2 sidebar'>
            <h3></h3>
        </div>
    </div>
    </div>
</body>
<!-- <script src="<?php echo base_url('assets'); ?>/js/jquery.js"></script> -->
<!-- <script src="<?php echo base_url('assets'); ?>/js/bootstrap.js"></script> -->
<!-- <script src="<?php echo base_url('assets'); ?>/js/validate.js"></script>
<script src="<?php echo base_url('assets'); ?>/js/jquery-mask.js"></script> -->
<!-- <script src="<?php echo base_url('assets'); ?>/js/jquery-ui.js"></script> -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.letras').mask('Z', {
            translation: {
                'Z': {
                    pattern: /[a-zA-Z áéíóúâêôãõç]/,
                    recursive: true
                }
            }
        });
        $("#cCEP").mask("00000-000");
        $("#cNumero").mask("000000000");
        $("#cConta").mask("000000000");
        $("#cAgencia").mask("000000000");
        $("#cOper").mask("000000000");
        $("#cTelefone").mask("(00) 0000-0000");
        $("#cCelular").mask("(00) 0000-0000");
        $("#cPis").mask("000.00000.00-0");
        $("#cRg").mask("99999999999999");

        $(function() {
            $("#data_nascimento").datepicker({
                format: "dd/mm/yyyy"
            });
        });

    });

    $('#divCNPJ').hide();
    $('#radioCPF').click(function() {
        $('#divCPF').fadeIn();
        $('#divCNPJ').hide();
        // $("#cCPF").attr('disabled', false);
        // $("#cCNPJ").attr('disabled', true);
    });
    $('#radioCNPJ').click(function() {
        $('#divCNPJ').fadeIn();
        $('#divCPF').hide();
        // $("#cCNPJ").attr('disabled', false);
        // $("#cCPF").attr('disabled', true);
    });
    $('#divCNPJ_titular').hide();
    $('#radioCPF_titular').click(function() {
        $('#radioCNPJ_titular')[0].checked = false;
        $('#divCPF_titular').fadeIn();
        $('#divCNPJ_titular').hide();
        // $("#cCPF_titular").attr('disabled', false);
        // $("#cCNPJ_titular").attr('disabled', true);
    });
    $('#radioCNPJ_titular').click(function() {
        $('#radioCPF_titular')[0].checked = false;
        $('#divCNPJ_titular').fadeIn();
        $('#divCPF_titular').hide();
        // $("#cCNPJ_titular").attr('disabled', false);
        // $("#cCPF_titular").attr('disabled', true);
    });

    function validateForm() {

        var x = document.forms["myForm"]["nome"].value;
        if (x == "") {
            alert("O campo nome é obrigatório.");
            return false;
        }

        var x = document.forms["myForm"]["sobrenome"].value;
        if (x == "") {
            alert("O campo sobrenome é obrigatório.");
            return false;
        }

        var dataNascimento = document.forms["myForm"]["data_nascimento"].value;

        if (dataNascimento == "") {
            alert("É necessário informar a data de nascimento do instalador.");
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
                alert("É necessário informar a data de nascimento válida.");
                $("#data_nascimento").focus();
                return false;
            }
        }

        var estadoCivil = document.forms["myForm"]["estado_civil"].value;
        if (estadoCivil == "") {
            alert("Selecione o estado civil.");
            return false;
        }

        var cpf = document.forms['myForm']['cpf'].value;
        var cnpj = document.forms['myForm']['cnpj'].value;
        if (cpf == '' && cnpj == '') {
            alert('O campo CPF/CNPJ é obrigatório.');
            return false;
        }

        var x = document.forms["myForm"]["email"].value;
        if (x == "") {
            alert("O campo e-mail é obrigatório.");
            return false;
        }

        var x = document.forms["myForm"]["telefone"].value;
        if (x == "") {
            alert("O campo telefone é obrigatório.");
            return false;
        }

        var x = document.forms["myForm"]["cep"].value;
        if (x == "") {
            alert("O campo CEP é obrigatório.");
            return false;
        }

        var x = document.forms["myForm"]["endereco"].value;
        if (x == "") {
            alert("O campo rua é obrigatório.");
            return false;
        }

        var x = document.forms["myForm"]["numero"].value;
        if (x == "") {
            alert("O campo número é obrigatório.");
            $("#numero").focus();
            return false;
        }

        var x = document.forms["myForm"]["bairro"].value;
        if (x == "") {
            alert("O campo bairro é obrigatório.");
            $("#bairro").focus();
            return false;
        }


        var x = document.forms["myForm"]["estado"].value;
        if (x == "") {
            alert("Selecionar o estado é obrigatório.");
            $("#estado").focus();
            return false;
        }


        var x = document.forms["myForm"]["cidade"].value;
        if (x == "") {
            alert("O campo cidade é obrigatório.");
            $("#cidade").focus();
            return false;
        }

        var x = document.forms["myForm"]["titular_conta"].value;
        if (x == "") {
            alert("O campo titular da conta é obrigatório.");
            $("#titular_conta").focus();
            return false;
        }

        var x = document.forms["myForm"]["banco"].value;
        if (x == "") {
            alert("Selecionar o banco é obrigatório.");
            $("#banco").focus();
            return false;
        }

        var x = document.forms["myForm"]["agencia"].value;
        if (x == "") {
            alert("O campo agência é obrigatório.");
            $("#agencia").focus();
            return false;
        }

        var x = document.forms["myForm"]["conta"].value;
        if (x == "") {
            alert("O campo conta é obrigatório.");
            $("#conta").focus();
            return false;
        }

        var x = document.forms["myForm"]["tipo_conta"].value;
        if (x == "") {
            alert("Selecionar o tipo da conta é obrigatório.");
            $("#tipo_conta").focus();
            return false;
        }

        var cpf = document.forms['myForm']['cCPF_titular'].value;
        var cnpj = document.forms['myForm']['cCNPJ_titular'].value;
        if (cpf == '' && cnpj == '') {
            alert('O campo CPF/CNPJ titular é obrigatório.');
            return false;
        }

        var x = validarSenha();
        if (!x) {
            return false;
        }
    }

    function validarSenha() {
        if (document.getElementById('cSenha').value && document.getElementById("ccSenha").value == "") {
            alert("Confirme a senha.");
            return false;
        }

        NovaSenha = document.getElementById('cSenha').value;
        CNovaSenha = document.getElementById('ccSenha').value;
        if (NovaSenha != CNovaSenha) {
            $('#cSenha').value('');
            $('#ccSenha').value('');
            alert("As senhas não correspondem, confirme a senha novamente.");
        } else {
            document.myform.submit();
        }
        if (document.getElementById("cEmail").value == "") {
            alert("Por favor, insira o email");
            document.myform.cEmail.focus();
            return false;
        }

        var cEmailid = document.getElementById("cEmail").value;
        atpos = cEmailid.indexOf("@");
        dotpos = cEmailid.lastIndexOf(".");

        if (atpos < 1 || (dotpos - atpos < 2))

        {
            alert("Por favor insira um email válido!");
            document.getElementById("cEmail").focus();
            return false;
        }
        return (true);
    }


    var cCPF = document.getElementById("cCPF");
    var cCNPJ = document.getElementById("cCNPJ");

    cCPF.addEventListener('keyup', function() {
        cCNPJ.disabled = cCPF.value.trim().length > 0;
    });

    //agora uma função para keyup muito semelhante para o codigo_interno
    cCNPJ.addEventListener('keyup', function() {
        cCPF.disabled = cCNPJ.value.trim().length > 0;
    });

    var cCPF_titular = document.getElementById("cCPF_titular");
    var cCNPJ_titular = document.getElementById("cCNPJ_titular");

    cCPF_titular.addEventListener('keyup', function() {
        cCNPJ_titular.disabled = cCPF_titular.value.trim().length > 0;
    });

    //agora uma função para keyup muito semelhante para o codigo_interno
    cCNPJ_titular.addEventListener('keyup', function() {
        cCPF_titular.disabled = cCNPJ_titular.value.trim().length > 0;
    });
</script>

</html>