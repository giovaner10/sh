<style type="text/css">
    .display {
        display: none;
    }

    .text-title h3,
    .text-title h4 {
        margin-left: 0 !important;
    }

    .menus-dados-input {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: center;
    }

    .menus-dados-input-campos {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: center;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .menus-dados-input-columm {
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: flex-start;
    }

    .field-input-flex {
        margin: 10px;
    }

    input {
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
        border: 1px solid #ccc;
        border-radius: 3px;
        height: 34px;
        width: 420px;
        padding: 8px;
    }

    .legend {
        color: #1C69AD;
    }

    .btn-salvar-form {
        width: 100%;
        display: flex;
        justify-content: flex-end;
    }

    #loading {
        display: none;
        z-index: 9999;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
    }

    .loader {
        position: absolute;
        top: 40%;
        left: 45%;
        transform: translate(-50%, -50%);
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }
</style>
<!-- <div class="alert alert-info">
	<strong>AVISO!</strong>
</div> -->
<div class="alert alert-success display"></div>
<div class="alert alert-error display"></div>

<div id="loading">
    <div class="loader"></div>
</div>


<?php
include(dirname(__FILE__) . '/../../componentes/comum/comum.php');
tituloPaginaComponente("Novo Termo de Adesão", site_url('Homes'), "Licitação", "Termo de adesão");
?>

<div class="row" style="margin: 15px 0 0 15px;">

    <div class="col-md-12 card-conteudo" id="conteudo" style="margin-bottom: 30px">
        <div class="row-fluid">
            <div class="container-fluid">
                <form id="form_termo">

                    <div class="menus-dados-input">
                        <div class="span12 field-input-flex" style="margin-left: 0;">
                            <label class="control-label" for="sel1">Executivo de Vendas:</label>
                            <br>
                            <select class="form-control span6" id="sel1" required name="executivo_vendas">
                                <?php foreach ($vendedores as $vendedor) : ?>
                                    <option><?= $vendedor->nome ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <input id="eptc" type="hidden" name="eptc" value="<?= $this->input->get('eptc') ?>">
                    <legend class="legend">Dados do Contratante<small> (Anexar cópia do comprovante de endereço, RG do assinante e contrato social se pessoa jurídica) </small></legend>
                    <div class="menus-dados-input-columm">

                        <div class="menus-dados-input-campos">
                            <div class="span6 field-input-flex">
                                <?php if ($this->input->get('pf') == '1') : ?>
                                    <label class="control-label">CPF</label>
                                    <br>
                                    <input id="cpf" type="text" class="span4" name="cnpj_cpf" required maxlength="18" value="<?php if (isset($dados)) {
                                                                                                                                    echo $dados[0]->cnpj;
                                                                                                                                } ?>" placeholder="" data-mask="000.000.000-00">
                                <?php else : ?>
                                    <label class="control-label">CNPJ</label>
                                    <br>
                                    <input id="cnpj" type="text" class="span4 mask_cpf" name="cnpj_cpf" value="<?php if (isset($dados)) {
                                                                                                                    echo $dados[0]->cnpj;
                                                                                                                } ?>" required maxlength="18" placeholder="" data-mask="00.000.000/0000-00">
                                    <a class="btn btn-small btn-success" style="margin-left: 10px;" onclick="buscaCnpj()"><i class="fa fa-search"></i></a>

                                <?php endif; ?>
                            </div>

                            <div class="span12 field-input-flex">
                                <label class="control-label">Razão Social / Nome</label>
                                <br>
                                <input id="razao_social" type="text" class="span12" name="razao_social" placeholder="" value="<?php if (isset($dados)) {
                                                                                                                                    echo $dados[0]->permissionario;
                                                                                                                                } ?>" required <?php if ($this->input->get('pf') != '1') : ?>readonly<?php endif; ?>>
                            </div>

                            <div class="span6 field-input-flex">
                                <label>Inscrição Estadual</label>
                                <br>
                                <input minlength="9" maxlength="14" type="number" class="span12" id="insc_estadual" name="insc_estadual" placeholder="">
                            </div>
                        </div>

                        <div class="menus-dados-input-campos">
                            <div class="span2 field-input-flex">
                                <label class="control-label">Telefone Celular</label>
                                <br>
                                <input type="text" class="span12" value="<?php if (isset($dados)) {
                                                                                echo $dados[0]->telefone;
                                                                            } ?>" name="fone_cel" placeholder="" required data-mask="(00) 00000-0000">
                            </div>

                            <div class="span2 field-input-flex">
                                <label class="control-label">Telefone Fixo</label>
                                <br>
                                <input id="tel" type="text" class="span12" name="fone_fixo" required placeholder="" data-mask="(00) 0000-0000">
                            </div>

                            <div class="span2 field-input-flex">
                                <label class="control-label">E-mail</label>
                                <br>
                                <input id="email" type="text" class="span12" name="email" required placeholder="exemplo@email.com">
                            </div>
                        </div>

                        <div class="menus-dados-input-campos">

                            <div class="span6 field-input-flex">
                                <label class="control-label">Endereço</label>
                                <br>
                                <input id="rua" type="text" class="span12" name="endereco" value="<?php if (isset($dados)) {
                                                                                                        echo $dados[0]->rua . ", " . $dados[0]->numero;
                                                                                                    } ?>" placeholder="" <?php if ($this->input->get('pf') != '1') : ?>readonly<?php endif; ?> required>
                            </div>

                            <div class="span3 field-input-flex">
                                <label class="control-label">Bairro</label>
                                <br>
                                <input id="bairro" type="text" class="span12" name="bairro" placeholder="" <?php if ($this->input->get('pf') != '1') : ?>readonly<?php endif; ?> required>
                            </div>

                            <div class="span3 field-input-flex">
                                <label class="control-label">Cidade</label>
                                <br>
                                <input id="cidade" type="text" class="span12" name="cidade" value="<?php if (isset($dados)) {
                                                                                                        echo $dados[0]->cidade;
                                                                                                    } ?>" placeholder="" <?php if ($this->input->get('pf') != '1') : ?>readonly<?php endif; ?> required>
                            </div>
                            <div class="span3 field-input-flex">
                                <label class="control-label">Estado</label>
                                <br>
                                <input id="uf" type="text" class="span12" name="uf" value="<?php if (isset($dados)) {
                                                                                                echo "RS";
                                                                                            } ?>" placeholder="" <?php if ($this->input->get('pf') != '1') : ?>readonly<?php endif; ?> required>
                            </div>

                            <div class="span3 field-input-flex">
                                <label class="control-label">CEP</label>
                                <br>
                                <input id="cep_end" type="text" class="span12 cep" name="cep" value="<?php if (isset($dados)) {
                                                                                                        echo $dados[0]->cep;
                                                                                                    } ?>" placeholder="" <?php if ($this->input->get('pf') != '1') : ?>readonly<?php endif; ?> required>
                            </div>

                            <div class="span6 field-input-flex">
                                <label>Complemento</label>
                                <br>
                                <input type="text" id="complemento" class="span12" value="<?php if (isset($dados)) {
                                                                                                echo $dados[0]->complemento;
                                                                                            } ?>" name="complemento" placeholder="">
                            </div>
                            <div class="span6 field-input-flex">

                                <button type="button" id="copiar_endereco" class="btn btn-success" style="margin-top: 25px;">Copiar Endereço Para Entrega</button>
                            </div>
                        </div>

                    </div>
                    <legend class="legend">Endereço de Entrega </legend>
                    <div class="menus-dados-input">
                        <div class="span6 field-input-flex ">
                            <label class="control-label">Endereço</label>
                            <br>
                            <input type="text" id="rua_entrega" class="span12" name="endereco_entrega" placeholder="" required>
                        </div>
                        <div class="span6 field-input-flex ">
                            <label>Complemento</label>
                            <br>
                            <input type="text" id="complemento_entrega" class="span12" name="complemento_entrega" placeholder="">
                        </div>
                        <div class="span3 field-input-flex ">
                            <label class="control-label">Bairro</label>
                            <br>
                            <input type="text" id="bairro_entrega" class="span12" name="bairro_entrega" placeholder="" required>
                        </div>
                        <div class="span3 field-input-flex ">
                            <label class="control-label">Cidade</label>
                            <br>
                            <input type="text" id="cidade_entrega" class="span12" name="cidade_entrega" placeholder="" required>
                        </div>
                        <div class="span3 field-input-flex ">
                            <label class="control-label">Estado</label>
                            <br>
                            <input type="text" id="uf_entrega" class="span12" name="uf_entrega" placeholder="" required>
                        </div>
                        <div class="span3 field-input-flex ">
                            <label class="control-label">CEP</label>
                            <br>
                            <input type="text" id="cep_entrega" class="span12 cep" name="cep_entrega" placeholder="" required>
                        </div>
                    </div>

                    <legend class="legend">Condições Comerciais</legend>
                    <div class="menus-dados-input">

                        <div class="span5 field-input-flex">
                            <label class="control-label" for="tipo">Tipo de Contrato:</label>
                            <br>
                            <select class="form-control span12" name="tipo_contrato" id="tipo">
                                <option>Comodato</option>
                                <option>Aquisição</option>
                            </select>
                        </div>

                        <div class="span2 field-input-flex">
                            <label class="control-label">Qtd de Veículos</label>
                            <br>
                            <input type="text" class="span12" name="qtd_eqp" required>
                        </div>

                        <div class="span5 field-input-flex">
                            <label class="control-label">Pacote do Serviço</label>
                            <br>
                            <input type="text" value="<?php if (isset($dados)) {
                                                            echo "Start";
                                                        } ?>" class="span12" name="pct_servicos" required>
                        </div>

                        <div class="span3 field-input-flex">
                            <label class="control-label" for="sel2">Bloqueio:</label>
                            <br>
                            <select class="form-control span12" name="bloqueio" required id="sel2">
                                <option>Sim</option>
                                <option>Não</option>
                            </select>
                        </div>

                        <div class="span3 field-input-flex">
                            <label class="control-label">Pessoa do Contas à Pagar</label>
                            <br>
                            <input type="text" class="span12" required name="pessoa_contas_pagar">
                        </div>

                        <div class="span3 field-input-flex">
                            <label class="control-label">Contato do Contas à Pagar</label>
                            <br>
                            <input type="text" class="span12" required name="ctt_contas_pagar" data-mask="(00) 00000-0000">
                        </div>

                        <div class="span3 field-input-flex">
                            <label class="control-label">E-mail do Contas à Pagar</label>
                            <br>
                            <input type="text" class="span12" required name="email_financeiro" placeholder="exemplo@email.com">
                        </div>

                        <div class="span3 field-input-flex">
                            <label class="control-label" for="periodo">Período do Contrato:</label>
                            <br>
                            <select class="form-control span12" required name="periodo_contrato" id="periodo">
                                <option>24</option>
                                <option>36</option>
                            </select>
                        </div>

                        <legend class="legend">Condições de Pagamento</legend>
                        <div class="menus-dados-input">

                            <div class="span3 field-input-flex">
                                <label class="control-label">Valor de Instalação por Veículo</label>
                                <br>
                                <input type="text" class="span12 money2" required name="valor_inst_veic" placeholder="R$">
                            </div>

                            <div class="span2 field-input-flex">
                                <label class="control-label">Instalação em Parcelas</label>
                                <br>
                                <input type="text" value="<?php if (isset($dados)) {
                                                                echo "1";
                                                            } ?>" class="span12" required name="inst_parcelas">
                            </div>

                            <div class="span2 field-input-flex">
                                <label class="control-label">1º Vencimento da Adesão</label>
                                <br>
                                <input type="text" class="span12" required name="primeiro_venc_adesao" data-mask="00/00/0000">
                            </div>

                            <div class="span3 field-input-flex">
                                <label class="control-label">Valor de Mensalidade por Veículo</label>
                                <br>
                                <input value="<?php if (isset($dados)) {
                                                    echo "49,90";
                                                } ?>" type="text" class="span12 money2" required name="valor_mens_veic" placeholder="R$">
                            </div>

                            <div class="span2 field-input-flex">
                                <label class="control-label" for="dt_venc">Dia do Vencimento:</label>
                                <br>
                                <select class="form-control span12" required name="dt_vencimento" id="dt_venc">
                                    <option>10</option>
                                    <option>20</option>
                                </select>
                            </div>

                            <div class="span3 field-input-flex">
                                <label class="control-label">1º Vencimento da Mensalidade</label>
                                <br>
                                <input type="text" required class="span12" name="primeiro_venc_mens" data-mask="00/00/0000">
                            </div>

                            <div class="span3 field-input-flex">
                                <label>Produto Adicional</label>
                                <br>
                                <input type="text" class="span12" name="produto_adicional">
                            </div>

                            <div class="span2 field-input-flex">
                                <label>Quantidade</label>
                                <br>
                                <input type="text" class="span12" name="qtd">
                            </div>

                            <div class="span2 field-input-flex">
                                <label>Valor Unitário</label>
                                <br>
                                <input type="text" value="<?php if (isset($dados)) {
                                                                echo "49,90";
                                                            } ?>" class="span12 money2" name="valor_final_un" placeholder="R$">
                            </div>

                            <div class="span2 field-input-flex">
                                <label>Total</label>
                                <br>
                                <input type="text" class="span12 money2" name="total" placeholder="R$">
                            </div>

                            <div class="span3 field-input-flex">
                                <label>Adicional em Parcelas</label>
                                <br>
                                <input type="text" class="span12 money2" name="adicional_parcelas">
                            </div>

                            <div class="span3 field-input-flex">
                                <label>1º Vencimento de Adicional</label>
                                <br>
                                <input type="text" class="span12" data-mask="00/00/0000" name="primeiro_venc_adicional">
                            </div>
                            <!--            <legend>Contatos Para Emergência <small> (Autorizados a solicitar serviços à Central de Atendimentos Show Tecnologia) </small></legend>-->
                            <!---->
                            <!--            <div class="controls controls-row">-->
                            <!--                <div class="span4">-->
                            <!--                    <label>Nome Completo</label>-->
                            <!--                    <input type="text" class="span12" name="ctt_emerg_1">-->
                            <!--                </div>-->
                            <!--                <div class="span4">-->
                            <!--                    <label>Telefone Fixo</label>-->
                            <!--                    <input type="text" class="span12" name="fone_emerg_1" data-mask="(00) 0000-0000">-->
                            <!--                </div>-->
                            <!--                <div class="span4">-->
                            <!--                    <label>Celular</label>-->
                            <!--                    <input type="text" class="span12" name="cel_emerg_1" data-mask="(00) 00000-0000">-->
                            <!--                </div>-->
                            <!--            </div>-->
                            <!--            <div class="controls controls-row">-->
                            <!--                <div class="span4">-->
                            <!--                    <label>Nome Completo</label>-->
                            <!--                    <input type="text" class="span12" name="ctt_emerg_2">-->
                            <!--                </div>-->
                            <!--                <div class="span4">-->
                            <!--                    <label>Telefone Fixo</label>-->
                            <!--                    <input type="text" class="span12" name="fone_emerg_2" data-mask="(00) 0000-0000">-->
                            <!--                </div>-->
                            <!--                <div class="span4">-->
                            <!--                    <label>Celular</label>-->
                            <!--                    <input type="text" class="span12" name="cel_emerg_2" data-mask="(00) 00000-0000">-->
                            <!--                </div>-->
                            <!--            </div>-->
                            <!--            <div class="controls controls-row">-->
                            <!--                <div class="span4">-->
                            <!--                    <label>Nome Completo</label>-->
                            <!--                    <input type="text" class="span12" name="ctt_emerg_3">-->
                            <!--                </div>-->
                            <!--                <div class="span4">-->
                            <!--                    <label>Telefone Fixo</label>-->
                            <!--                    <input type="text" class="span12" name="fone_emerg_3" data-mask="(00) 0000-0000">-->
                            <!--                </div>-->
                            <!--                <div class="span4">-->
                            <!--                    <label>Celular</label>-->
                            <!--                    <input type="text" class="span12" name="cel_emerg_3" data-mask="(00) 00000-0000">-->
                            <!--                </div>-->
                            <!--            </div><br/>-->
                            <!---->
                            <div class="span5 field-input-flex">
                                <label class="control-label">Instalação Sigilosa</label>
                                <br>
                                <select type="text" required class="span12 form-control" name="inst_sigilosa">
                                    <option>Não</option>
                                    <option>Sim</option>
                                </select>
                            </div>

                            <div class="span5 field-input-flex">
                                <label>Observação</label>
                                <br>
                                <input type="text" class="span12" name="obs">
                            </div>
                        </div>

                        <div class="btn-salvar-form">
                            <button id="submit" class="btn btn-primary">Salvar</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.min.js"></script>

<script>
    $(document).ready(function() {
        $('#cep, #cep_end, #cep_entrega').mask('00000-000');
    });

    function mascaraMutuario(o, f) {
        v_obj = o;
        v_fun = f;
        setTimeout('execmascara()', 1)
    }

    function execmascara() {
        v_obj.value = v_fun(v_obj.value)
    }

    function cpfCnpj(v) {

        //Remove tudo o que não é dígito
        v = v.replace(/\D/g, "");

        if (v.length <= 14) { //CPF

            //Coloca um ponto entre o terceiro e o quarto dígitos
            v = v.replace(/(\d{3})(\d)/, "$1.$2");

            //Coloca um ponto entre o terceiro e o quarto dígitos
            //de novo (para o segundo bloco de números)
            v = v.replace(/(\d{3})(\d)/, "$1.$2");

            //Coloca um hífen entre o terceiro e o quarto dígitos
            v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2")

        } else { //CNPJ

            //Coloca ponto entre o segundo e o terceiro dígitos
            v = v.replace(/^(\d{2})(\d)/, "$1.$2");

            //Coloca ponto entre o quinto e o sexto dígitos
            v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");

            //Coloca uma barra entre o oitavo e o nono dígitos
            v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");

            //Coloca um hífen depois do bloco de quatro dígitos
            v = v.replace(/(\d{4})(\d)/, "$1-$2")

        }
        return v
    }

    $('#copiar_endereco').click(function() {
        $('#rua_entrega').val($('#rua').val());
        $('#bairro_entrega').val($('#bairro').val());
        $('#cep_entrega').val($('#cep').val());
        $('#uf_entrega').val($('#uf').val());
        $('#complemento_entrega').val($('#complemento').val());
        $('#cidade_entrega').val($('#cidade').val());
    });

    function ShowLoadingScreen() {
        $('#loading').show()
    }

    function HideLoadingScreen() {
        $('#loading').hide()
    }

    $('#form_termo').on('submit', function(e) {
        e.preventDefault();
        ShowLoadingScreen();
        $.ajax({
            url: 'save',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                HideLoadingScreen();
                showAlert('success', data.msg);
                $('html,body').animate({
                    scrollTop: 0
                }, 'slow');
            },
            error: function() {
                HideLoadingScreen();
                showAlert('error', 'Não foi possível salvar o ');
                $('html,body').animate({
                    scrollTop: 0
                }, 'slow');
            }
        })
    });

    function buscaCnpj() {
        var cnpj = document.getElementById("cnpj").value.replace('.', '').replace('/', '').replace('-', '').replace('.', '');
        var url = '../cadastros/consulta_cnpj/' + cnpj;
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            contentType: 'application/json',
            success: function(data) {
                if (data.status == "OK") {
                    document.getElementById("razao_social").value = data.nome;
                    document.getElementById("cep").value = data.cep;
                    document.getElementById("rua").value = data.logradouro + ' - ' + data.numero;
                    document.getElementById("bairro").value = data.bairro;
                    document.getElementById("cidade").value = data.municipio;
                    document.getElementById("uf").value = data.uf;
                    document.getElementById("tel").value = data.telefone;
                    document.getElementById("email").value = data.email;
                } else {
                    document.getElementById("razao_social").value = '';
                    document.getElementById("cep").value = '';
                    document.getElementById("rua").value = '';
                    document.getElementById("bairro").value = '';
                    document.getElementById("cidade").value = '';
                    document.getElementById("uf").value = '';
                    document.getElementById("tel").value = '';
                    document.getElementById("email").value = '';
                    window.alert('CNPJ não encontrado na base de dados da Receita Federal');
                }
            }
        });
    }
</script>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/Omnicom', 'layout.css') ?>">