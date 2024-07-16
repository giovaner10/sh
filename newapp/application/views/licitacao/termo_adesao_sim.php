
<style type="text/css">
    .display{
        display: none;
    }
</style>
<!-- <div class="alert alert-info">
	<strong>AVISO!</strong>
</div> -->
<div class="alert alert-success display"></div>
<div class="alert alert-error display"></div>


<div class="row-fluid">
    <div class="page-header">
        <h3>Novo Termo de Adesão</h3>
    </div>

    <div class="container-fluid">
        <form id="form_termo" autocomplete="off">
            <div class="span12">
                <div class="form-group">
                    <label for="sel1">Executivo de Vendas:</label>
                    <select class="form-control span6" id="sel1" name="executivo_vendas">
                        <?php foreach ($vendedores as $vendedor): ?>
                            <option><?= $vendedor->nome ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <legend>Dados do Contratante<small> (Anexar cópia do comprovante de endereço, RG do assinante e contrato social se pessoa jurídica) </small></legend>
            <div class="controls controls-row">
                <div class="span6">
                    <label>CNPJ</label>
                    <input id="cnpj" type="text" class="span4 mask_cpf" name="cnpj_cpf" maxlength="18" placeholder="" data-mask="00.000.000/0000-00">
                    <a class="btn btn-small btn-info" style="margin-left: 10px;" onclick="buscaCnpj()"><i class="fa fa-search"></i></a> <span class="text-info" style="font-size: smaller;">* Informações do banco de dados da Receita Federal</span>
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span12">
                    <label>Razão Social / Nome</label>
                    <input type="text" id="razao_social" class="span12" name="razao_social" placeholder="" required readonly>
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span6">
                    <label>Inscrição Estadual</label>
                    <input type="text" class="span12" name="insc_estadual" placeholder="">
                </div>
                <div class="span6">
                    <label>Endereço</label>
                    <input type="text" id="rua" class="span12" name="endereco" placeholder="" required readonly>
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span3">
                    <label>Bairro</label>
                    <input type="text" id="bairro" class="span12" name="bairro" placeholder="" required readonly>
                </div>
                <div class="span3">
                    <label>Cidade</label>
                    <input type="text" id="cidade" class="span12" name="cidade" placeholder="" required readonly>
                </div>
                <div class="span3">
                    <label>Estado</label>
                    <input type="text" id="uf" class="span12" name="uf" placeholder="" required readonly>
                </div>
                <div class="span3">
                    <label>CEP</label>
                    <input type="text" id="cep" class="span12 cep" name="cep" placeholder="" required readonly>
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span6">
                    <label>Complemento</label>
                    <input type="text" id="complemento" class="span12" name="complemento" placeholder="">
                </div>
                <div class="span2">
                    <label>Telefone Celular</label>
                    <input type="text" class="span12" name="fone_cel" placeholder="" data-mask="(00) 00000-0000">
                </div>
                <div class="span2">
                    <label>Telefone Fixo</label>
                    <input type="text" id="tel" class="span12" name="fone_fixo" placeholder="" data-mask="(00) 0000-0000">
                </div>
                <div class="span2">
                    <label>E-mail</label>
                    <input type="text" id="email" class="span12" name="email" placeholder="" autocomplete="off">
                </div>
            </div>

            <legend>Endereço de Entrega  <button type="button" id="copiar_endereco" class="btn btn-info btn-xs">Copiar Endereço</button></legend>
            <div class="controls controls-row">
                <div class="span6">
                    <label>Endereço</label>
                    <input type="text" id="rua_entrega" class="span12" name="endereco_entrega" placeholder="" required>
                </div>
                <div class="span6">
                    <label>Complemento</label>
                    <input type="text" id="complemento_entrega" class="span12" name="complemento_entrega" placeholder="">
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span3">
                    <label>Bairro</label>
                    <input type="text" id="bairro_entrega" class="span12" name="bairro_entrega" placeholder="" required>
                </div>
                <div class="span3">
                    <label>Cidade</label>
                    <input type="text" id="cidade_entrega" class="span12" name="cidade_entrega" placeholder="" required>
                </div>
                <div class="span3">
                    <label>Estado</label>
                    <input type="text" id="uf_entrega" class="span12" name="uf_entrega" placeholder="" required>
                </div>
                <div class="span3">
                    <label>CEP</label>
                    <input type="text" id="cep_entrega" class="span12 cep" name="cep_entrega" placeholder="" required>
                </div>
            </div>

            <legend>Condições Comerciais</legend>

            <div class="controls controls-row">
                <div class="span5">
                    <div class="form-group" >
                        <label for="tipo">Tipo de Contrato:</label>
                        <select class="form-control span12" name="tipo_contrato" id="tipo">
                            <option>Comodato</option>
                            <option>Aquisição</option>
                        </select>
                    </div>
                </div>
                <div class="span2">
                    <label>Operadora</label>
                    <input type="text" class="span12" name="operadora">
                </div>
                <div class="span2">
                    <label>Quantidade de Chips</label>
                    <input type="text" class="span12" name="quant_chips">
                </div>
                <div class="span2">
                    <label>Pacote de Megas</label>
                    <input type="text" class="span12" name="pct_megas">
                </div>
                <div class="span1">
                    <div class="form-group" >
                        <label for="periodo">Período do Contrato:</label>
                        <select class="form-control span12" name="periodo_contrato" id="periodo">
                            <option>24</option>
                            <option>36</option>
                        </select>
                    </div>
                </div>
            </div>

            <legend>Condições de Pagamento</legend>

            <div class="controls controls-row">
                <div class="span3">
                    <label>Pessoa do Contas à Pagar</label>
                    <input type="text" class="span12" name="pessoa_contas_pagar" autocomplete="off">
                </div>
                <div class="span3">
                    <label>Contato do Contas à Pagar</label>
                    <input type="text" class="span12" name="contato_contas_pagar" autocomplete="off">
                </div>
                <div class="span3">
                    <label>E-mail do Contas à Pagar</label>
                    <input type="text" class="span12" name="email_financeiro" autocomplete="off">
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span2">
                    <label>Valor de Ativação por Chip</label>
                    <input type="text" class="span12 money2" name="valor_ativacao_chip">
                </div>
                <div class="span2">
                    <label>Vencimento da Ativação</label>
                    <input type="text" class="span12" data-mask="00/00/0000" name="venc_ativacao">
                </div>
                <div class="span2">
                    <label>Valor de Mensalidade por Chip</label>
                    <input type="text" class="span12 money2" name="valor_mensalidade_chip">
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span3">
                    <div class="form-group" >
                        <label for="dt_venc">Dia do Vencimento:</label>
                        <select class="form-control span12" name="data_vencimento" id="dt_venc">
                            <option>10</option>
                            <option>20</option>
                            <option>30</option>
                        </select>
                    </div>
                </div>
                <div class="span3">
                    <label>1º Vencimento da Mensalidade</label>
                    <input type="text" class="span12" data-mask="00/00/0000" name="primeiro_vencimento_mensalidade">
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span2">
                    <label>Taxa de Envio</label>
                    <input type="text" class="span12 money2" name="taxa_envio">
                </div>
                <div class="span5">
                    <label>Observações</label>
                    <input type="text" class="span12" name="observacoes">
                </div>
            </div>

            <button id="submit" class="btn btn-primary btn-large"><i id="load" style="font-size:18px;" class="fa fa-spinner fa-pulse fa-3x fa-fw display" ></i>Salvar</button>
        </form>
    </div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.min.js"></script>

<script>
    function mascaraMutuario(o,f){
        v_obj=o;
        v_fun=f;
        setTimeout('execmascara()',1)
    }

    function execmascara(){
        v_obj.value=v_fun(v_obj.value)
    }

    function cpfCnpj(v){

        //Remove tudo o que não é dígito
        v=v.replace(/\D/g,"");

        if (v.length <= 14) { //CPF

            //Coloca um ponto entre o terceiro e o quarto dígitos
            v=v.replace(/(\d{3})(\d)/,"$1.$2");

            //Coloca um ponto entre o terceiro e o quarto dígitos
            //de novo (para o segundo bloco de números)
            v=v.replace(/(\d{3})(\d)/,"$1.$2");

            //Coloca um hífen entre o terceiro e o quarto dígitos
            v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2")

        } else { //CNPJ

            //Coloca ponto entre o segundo e o terceiro dígitos
            v=v.replace(/^(\d{2})(\d)/,"$1.$2");

            //Coloca ponto entre o quinto e o sexto dígitos
            v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3");

            //Coloca uma barra entre o oitavo e o nono dígitos
            v=v.replace(/\.(\d{3})(\d)/,".$1/$2");

            //Coloca um hífen depois do bloco de quatro dígitos
            v=v.replace(/(\d{4})(\d)/,"$1-$2")

        }
        return v
    }

    $('#copiar_endereco').click(function () {
        $('#rua_entrega').val($('#rua').val());
        $('#bairro_entrega').val($('#bairro').val());
        $('#cep_entrega').val($('#cep').val());
        $('#uf_entrega').val($('#uf').val());
        $('#complemento_entrega').val($('#complemento').val());
        $('#cidade_entrega').val($('#cidade').val());
    });

    $('.cep').mask('99999-999');

    $('#submit').click(function () {
        $('#load').removeClass('display');
        $('#form_termo').ajaxForm({
            url: 'save',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                $('#load').addClass('display');
                $('.alert-success').removeClass('display').html('<button type="button" class="close" data-dismiss="alert">&times;</button>'+data.msg);
                $('html,body').animate({scrollTop: 0},'slow');
            },
            error: function (data) {
                $('#load').addClass('display');
                $('.alert-error').removeClass('display').html('<button type="button" class="close" data-dismiss="alert">&times;</button>'+data.msg);
                $('html,body').animate({scrollTop: 0},'slow');
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
                }}
        });
    }

</script>
