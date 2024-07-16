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
        <form id="form_termo"action="<?= site_url('licitacao/edit/2/'.$termo->id) ?>" method="post">
            <div class="span12">
                <div class="form-group">
                    <label for="sel1">Executivo de Vendas:</label>
                    <select class="form-control span6" id="sel1" name="executivo_vendas">
                        <?php foreach ($vendedores as $vendedor): ?>
                            <option <?= $termo->executivo_vendas == $vendedor->nome ? 'selected' : '' ?>><?= $vendedor->nome ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <legend>Dados do Contratante<small> (Anexar cópia do comprovante de endereço, RG do assinante e contrato social se pessoa jurídica) </small></legend>
            <div class="controls controls-row">
                <div class="span12">
                    <label>Razão Social / Nome</label>
                    <input type="text" class="span12" name="razao_social" placeholder="" value="<?= $termo->razao_social ?>" readonly>
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span3">
                    <label>CNPJ</label>
                    <input type="text" class="span12 mask_cpf" name="cnpj_cpf" maxlength="18" placeholder="" data-mask="00.000.000/0000-00" value="<?= $termo->cnpj_cpf ?>" readonly>
                </div>
                <div class="span3">
                    <label>Inscrição Estadual</label>
                    <input type="text" class="span12" name="insc_estadual" placeholder="" value="<?= $termo->insc_estadual ?>" readonly>
                </div>
                <div class="span6">
                    <label>Endereço</label>
                    <input type="text" class="span12" name="endereco" placeholder="" value="<?= $termo->endereco ?>" readonly>
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span3">
                    <label>Bairro</label>
                    <input type="text" class="span12" name="bairro" placeholder="" value="<?= $termo->bairro ?>" readonly>
                </div>
                <div class="span3">
                    <label>Cidade</label>
                    <input type="text" class="span12" name="cidade" placeholder="" value="<?= $termo->cidade ?>" readonly>
                </div>
                <div class="span3">
                    <label>Estado</label>
                    <input type="text" class="span12" name="uf" placeholder="" value="<?= $termo->uf ?>" readonly>
                </div>
                <div class="span3">
                    <label>CEP</label>
                    <input type="text" class="span12 cep" name="cep" placeholder="" value="<?= $termo->cep ?>" readonly>
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span6">
                    <label>Complemento</label>
                    <input type="text" class="span12" name="complemento" placeholder="" value="<?= $termo->complemento ?>">
                </div>
                <div class="span2">
                    <label>Telefone Celular</label>
                    <input type="text" class="span12" name="fone_cel" placeholder="" data-mask="(00) 00000-0000" value="<?= $termo->fone_cel ?>">
                </div>
                <div class="span2">
                    <label>Telefone Fixo</label>
                    <input type="text" class="span12" name="fone_fixo" placeholder="" data-mask="(00) 0000-0000" value="<?= $termo->fone_fixo ?>">
                </div>
                <div class="span2">
                    <label>E-mail</label>
                    <input type="text" class="span12" name="email" placeholder="" value="<?= $termo->email ?>">
                </div>
            </div>
            <?php if (isset($end_entrega) && $end_entrega): ?>
                <legend>Endereço de Entrega</legend>
                <input type="hidden" class="span1" name="id_entrega" placeholder="" value="<?= $end_entrega->id ?>" >
                <div class="controls controls-row">
                    <div class="span6">
                        <label>Endereço</label>
                        <input type="text" class="span12" name="endereco_entrega" placeholder="" value="<?= $end_entrega->rua ?>" >
                    </div>
                    <div class="span6">
                        <label>Complemento</label>
                        <input type="text" class="span12" name="complemento_entrega" placeholder="" value="<?= $end_entrega->complemento ?>">
                    </div>
                </div>
                <div class="controls controls-row">
                    <div class="span3">
                        <label>Bairro</label>
                        <input type="text" class="span12" name="bairro_entrega" placeholder="" value="<?= $end_entrega->bairro ?>" >
                    </div>
                    <div class="span3">
                        <label>Cidade</label>
                        <input type="text" class="span12" name="cidade_entrega" placeholder="" value="<?= $end_entrega->cidade ?>" >
                    </div>
                    <div class="span3">
                        <label>Estado</label>
                        <input type="text" class="span12" name="uf_entrega" placeholder="" value="<?= $end_entrega->uf ?>" >
                    </div>
                    <div class="span3">
                        <label>CEP</label>
                        <input type="text" class="span12 cep" name="cep_entrega" placeholder="" value="<?= $end_entrega->cep ?>" >
                    </div>
                </div>
            <?php endif; ?>
            <br>

<!--            <legend>Dados Exclusivo Pessoa Física</legend>-->
<!--            <div class="controls controls-row">-->
<!--                <div class="span3">-->
<!--                    <label>RG</label>-->
<!--                    <input type="text" class="span12" name="rg" >-->
<!--                </div>-->
<!--                <div class="span3">-->
<!--                    <label>Data de Nascimento</label>-->
<!--                    <input type="text" class="span12" name="dt_nascimento" data-mask="00/00/0000">-->
<!--                </div>-->
<!--            </div>-->

<!--            <legend>Pessoa de Contato <small>(Para agendamento e disponibilidade do veículo).</small></legend>-->
<!--            <div class="controls controls-row">-->
<!--                <div class="span6">-->
<!--                    <label>Nome Completo</label>-->
<!--                    <input type="text" class="span12" name="nome_ctt">-->
<!--                </div>-->
<!--                <div class="span3">-->
<!--                    <label>Telefone Fixo</label>-->
<!--                    <input type="text" class="span12" name="fone_fixo_ctt" placeholder="" data-mask="(00) 0000-0000">-->
<!--                </div>-->
<!--                <div class="span3">-->
<!--                    <label>Telefone Celular</label>-->
<!--                    <input type="text" class="span12" name="fone_cel_ctt" placeholder="" data-mask="(00) 00000-0000">-->
<!--                </div>-->
<!--            </div>-->

<!--            <legend>Dados do Veículo <small> (Anexar cópia do documento do veículo. Se for mais de um veículo, pode ser enviada relação anexa a este termo de adesão). </small> </legend>-->
<!---->
<!--            <div class="controls controls-row">-->
<!--                <div class="span3">-->
<!--                    <label>Marca</label>-->
<!--                    <input type="text" class="span12" name="marca_veic">-->
<!--                </div>-->
<!--                <div class="span3">-->
<!--                    <label>Modelo</label>-->
<!--                    <input type="text" class="span12" name="modelo_veic">-->
<!--                </div>-->
<!--                <div class="span3">-->
<!--                    <label>Cor</label>-->
<!--                    <input type="text" class="span12" name="cor_veic">-->
<!--                </div>-->
<!--                <div class="span3">-->
<!--                    <label>Chassi</label>-->
<!--                    <input type="text" class="span12" name="chassi">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="controls controls-row">-->
<!--                <div class="span2">-->
<!--                    <label>Ano</label>-->
<!--                    <input type="text" class="span12" name="ano_veic">-->
<!--                </div>-->
<!--                <div class="span2">-->
<!--                    <label>Placa</label>-->
<!--                    <input type="text" class="span12" name="placa_veic">-->
<!--                </div>-->
<!--                <div class="span3">-->
<!--                    <label>Renavam</label>-->
<!--                    <input type="text" class="span12" name="renavam">-->
<!--                </div>-->
<!--                <div class="span5">-->
<!--                    <label>Categoria</label>-->
<!--                    <input type="text" class="span12" name="categoria">-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <legend>Dados do Equipamento <small> (Preencher somente para tipo de contrato de venda <b>*</b>) </small></legend>-->
<!---->
<!--            <div class="controls controls-row">-->
<!--                <div class="span3">-->
<!--                    <label>Equipamento</label>-->
<!--                    <input type="text" class="span12" name="eqp">-->
<!--                </div>-->
<!--                <div class="span3">-->
<!--                    <label>Número de Parcelas *</label>-->
<!--                    <input type="text" class="span12" name="num_parcelas">-->
<!--                </div>-->
<!--                <div class="span3">-->
<!--                    <label>Acessório (1)</label>-->
<!--                    <input type="text" class="span12" name="acessorio_1">-->
<!--                </div>-->
<!--                <div class="span3">-->
<!--                    <label>Acessório (2)</label>-->
<!--                    <input type="text" class="span12" name="acessorio_2">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="controls controls-row">-->
<!--                <div class="span3">-->
<!--                    <label>Acessório (3)</label>-->
<!--                    <input type="text" class="span12" name="acessorio_3">-->
<!--                </div>-->
<!--                <div class="span3">-->
<!--                    <label>Plugin (1)</label>-->
<!--                    <input type="text" class="span12" name="plugin_1">-->
<!--                </div>-->
<!--                <div class="span3">-->
<!--                    <label>Plugin (2)</label>-->
<!--                    <input type="text" class="span12" name="plugin_2">-->
<!--                </div>-->
<!--                <div class="span3">-->
<!--                    <label>Plugin (3)</label>-->
<!--                    <input type="text" class="span12" name="plugin_3">-->
<!--                </div>-->
<!--            </div>-->

            <legend>Condições Comerciais</legend>

            <div class="controls controls-row">
                <div class="span5">
                    <label>Tipo de Contrato</label>
                    <input type="text" class="span12" name="tipo_contrato" value="<?= $termo->tipo_contrato ?>">
                </div>
                <div class="span3">
                    <label>Operadora</label>
                    <input type="text" class="span12" name="operadora" value="<?= $termo->operadora ?>">
                </div>
                <div class="span2">
                    <label>Quantidade de Chips</label>
                    <input type="text" class="span12" name="quant_chips" value="<?= $termo->quant_chips ?>">
                </div>
                <div class="span2">
                    <label>Pacote de Megas</label>
                    <input type="text" class="span12" name="pct_megas" value="<?= $termo->pct_megas ?>">
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span2">
                    <label>Período do Contrato</label>
                    <input type="text" class="span12" name="periodo_contrato" value="<?= $termo->periodo_contrato ?>">
                </div>
            </div>
<!--            <legend>Campos Exclusivos para Acessórios e Plugins <small> (Valores Unitários) </small></legend>-->
<!---->
<!--            <div class="controls controls-row">-->
<!--                <div class="span4">-->
<!--                    <label>Valor Acessório(1)</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_acessorio_1">-->
<!--                </div>-->
<!--                <div class="span4">-->
<!--                    <label>Valor Desconto</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_desc_1">-->
<!--                </div>-->
<!--                <div class="span4">-->
<!--                    <label>Valor Final</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_final_1">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="controls controls-row">-->
<!--                <div class="span4">-->
<!--                    <label>Valor Acessório(2)</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_acessorio_2">-->
<!--                </div>-->
<!--                <div class="span4">-->
<!--                    <label>Valor Desconto</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_desc_2">-->
<!--                </div>-->
<!--                <div class="span4">-->
<!--                    <label>Valor Final</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_final_2">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="controls controls-row">-->
<!--                <div class="span4">-->
<!--                    <label>Valor Acessório(3)</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_acessorio_3">-->
<!--                </div>-->
<!--                <div class="span4">-->
<!--                    <label>Valor Desconto</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_desc_3">-->
<!--                </div>-->
<!--                <div class="span4">-->
<!--                    <label>Valor Final</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_final_3">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="controls controls-row">-->
<!--                <div class="span4">-->
<!--                    <label>Valor Plugin(1)</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_plugin_1">-->
<!--                </div>-->
<!--                <div class="span4">-->
<!--                    <label>Valor Desconto</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_desc_plugin_1">-->
<!--                </div>-->
<!--                <div class="span4">-->
<!--                    <label>Valor Final</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_plugin_final_1">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="controls controls-row">-->
<!--                <div class="span4">-->
<!--                    <label>Valor Plugin(2)</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_plugin_2">-->
<!--                </div>-->
<!--                <div class="span4">-->
<!--                    <label>Valor Desconto</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_desc_plugin_2">-->
<!--                </div>-->
<!--                <div class="span4">-->
<!--                    <label>Valor Final</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_plugin_final_2">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="controls controls-row">-->
<!--                <div class="span4">-->
<!--                    <label>Valor Plugin(3)</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_plugin_3">-->
<!--                </div>-->
<!--                <div class="span4">-->
<!--                    <label>Valor Desconto</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_desc_plugin_3">-->
<!--                </div>-->
<!--                <div class="span4">-->
<!--                    <label>Valor Final</label>-->
<!--                    <input type="text" class="span12 money2" name="valor_plugin_final_3">-->
<!--                </div>-->
<!--            </div>-->

            <legend>Condições de Pagamento</legend>

            <div class="controls controls-row">
                <div class="span3">
                    <label>Pessoa do Contas à Pagar</label>
                    <input type="text" class="span12" name="pessoa_contas_pagar" value="<?= $termo->pessoa_contas_pagar ?>">
                </div>
                <div class="span3">
                    <label>Contato do Contas à Pagar</label>
                    <input type="text" class="span12" name="contato_contas_pagar" value="<?= $termo->contato_contas_pagar ?>">
                </div>
                <div class="span3">
                    <label>E-mail do Contas à Pagar</label>
                    <input type="text" class="span12" name="email_financeiro" value="<?= $termo->email_financeiro ?>">
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span2">
                    <label>Valor de Ativação por Chip</label>
                    <input type="text" class="span12 money2" name="valor_ativacao_chip" value="<?= number_format($termo->valor_ativacao_chip, 2, ',', '.') ?>">
                </div>
                <div class="span2">
                    <label>Vencimento da Ativação</label>
                    <input type="text" class="span12" data-mask="00/00/0000" name="venc_ativacao" value="<?= date('d/m/Y', strtotime($termo->venc_ativacao)) ?>">
                </div>
                <div class="span2">
                    <label>Valor de Mensalidade por Chip</label>
                    <input type="text" class="span12 money2" name="valor_mensalidade_chip" value="<?= number_format($termo->valor_mensalidade_chip, 2, ',', '.') ?>">
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span3">
                    <label>Dia de Vencimento</label>
                    <input type="text" class="span12" name="data_vencimento" value="<?= $termo->data_vencimento ?>">
                </div>
                <div class="span3">
                    <label>1º Vencimento da Mensalidade</label>
                    <input type="text" class="span12" data-mask="00/00/0000" name="primeiro_vencimento_mensalidade" value="<?= date('d/m/Y', strtotime($termo->primeiro_vencimento_mensalidade)) ?>">
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span3">
                    <label>Taxa de Envio</label>
                    <input type="text" class="span12 money2" name="taxa_envio" value="<?= $termo->taxa_envio ?>">
                </div>
                <div class="span3">
                    <label>Observações</label>
                    <input type="text" class="span12" name="observacoes" value="<?= $termo->observacoes ?>">
                </div>
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

</script>
