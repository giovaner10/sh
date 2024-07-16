
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
        <h3>Editar Termo de Adesão</h3>
    </div>

    <div class="container-fluid">
        <form id="form_termo" action="<?= site_url('licitacao/edit/1/'.$termo->id) ?>" method="post">
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
                    <input type="text" class="span12" name="insc_estadual" placeholder="" value="<?= $termo->insc_estadual ?>">
                </div>
                <div class="span6">
                    <label>Endereço</label>
                    <input type="text" class="span12" name="endereco" placeholder="" value="<?= $termo->endereco ?>" readonly>
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span3">
                    <label>Bairro</label>
                    <input type="text" class="span12" name="bairro" placeholder=""  value="<?= $termo->bairro ?>" readonly>
                </div>
                <div class="span3">
                    <label>Cidade</label>
                    <input type="text" class="span12" name="cidade" placeholder="" value="<?= $termo->cidade ?>" readonly>
                </div>
                <div class="span3">
                    <label>Estado</label>
                    <input type="text" class="span12" name="uf" placeholder=""  value="<?= $termo->uf ?>" readonly>
                </div>
                <div class="span3">
                    <label>CEP</label>
                    <input type="text" class="span12 cep" name="cep" placeholder="" value="<?= $termo->cep ?>" readonly>
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span6">
                    <label>Complemento</label>
                    <input type="text" class="span12" name="complemento" placeholder=""  value="<?= $termo->complemento ?>">
                </div>
                <div class="span2">
                    <label>Telefone Celular</label>
                    <input type="text" class="span12" name="fone_cel" placeholder="" data-mask="(00) 00000-0000"  value="<?= $termo->fone_cel ?>">
                </div>
                <div class="span2">
                    <label>Telefone Fixo</label>
                    <input type="text" class="span12" name="fone_fixo" placeholder="" data-mask="(00) 0000-0000"  value="<?= $termo->fone_fixo ?>">
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

            <legend>Condições Comerciais</legend>

            <div class="controls controls-row">
                <div class="span5">
                    <label>Tipo de Contrato</label>
                    <input type="text" class="span12" name="tipo_contrato"  value="<?= $termo->tipo_contrato ?>">
                </div>
                <div class="span2">
                    <label>Qtd de Veículos</label>
                    <input type="text" class="span12" name="qtd_eqp" value="<?= $termo->qtd_eqp ?>">
                </div>
                <div class="span3">
                    <label>Pacote do Serviço</label>
                    <input type="text" class="span12" name="pct_servicos" value="<?= $termo->pct_servicos ?>">
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span3">
                    <label>Bloqueio</label>
                    <input type="text" class="span12" name="bloqueio" value="<?= $termo->bloqueio ?>">
                </div>
                <div class="span3">
                    <label>Pessoa do Contas à Pagar</label>
                    <input type="text" class="span12" name="pessoa_contas_pagar" value="<?= $termo->pessoa_contas_pagar ?>">
                </div>
                <div class="span3">
                    <label>Contato do Contas à Pagar</label>
                    <input type="text" class="span12" name="ctt_contas_pagar" value="<?= $termo->ctt_contas_pagar ?>">
                </div>
                <div class="span3">
                    <label>E-mail do Contas à Pagar</label>
                    <input type="text" class="span12" name="email_financeiro" value="<?= $termo->email_financeiro ?>">
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span3">
                    <label>Período do Contrato</label>
                    <input type="text" class="span12" name="periodo_contrato" value="<?= $termo->periodo_contrato ?>">
                </div>
                <div class="span3">
                    <label>Instalação Sigilosa</label>
                    <input type="text" class="span12" name="inst_sigilosa" value="<?= $termo->inst_sigilosa ?>">
                </div>
                <div class="span3">
                    <label>Observação</label>
                    <input type="text" class="span12" name="obs" value="<?= $termo->obs ?>">
                </div>
            </div>

            <legend>Condições de Pagamento</legend>

            <div class="controls controls-row">
                <div class="span4">
                    <label>Valor de Instalação por Veículo</label>
                    <input type="text" class="span12 money2" name="valor_inst_veic" value="<?= number_format($termo->valor_inst_veic,2,',', '.'); ?>">
                </div>
                <div class="span2">
                    <label>Instalação em Parcelas</label>
                    <input type="text" class="span12" name="inst_parcelas" value="<?= $termo->inst_parcelas ?>">
                </div>
                <div class="span2">
                    <label>1º Vencimento da Adesão</label>
                    <input type="text" class="span12" name="primeiro_venc_adesao" data-mask="00/00/0000" value="<?= $termo->primeiro_venc_adesao ?>">
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span3">
                    <label>Valor de Mensalidade por Veículo</label>
                    <input type="text" class="span12 money2" name="valor_mens_veic" value="<?= number_format($termo->valor_mens_veic, 2, ',', '.') ?>">
                </div>
                <div class="span3">
                    <label>Dia do Vencimento</label>
                    <input type="text" class="span12" name="dt_vencimento" data-mask="00/00/0000" value="<?= $termo->dt_vencimento ?>">
                </div>
                <div class="span3">
                    <label>1º Vencimento da Mensalidade</label>
                    <input type="text" class="span12" name="primeiro_venc_mens" data-mask="00/00/0000" value="<?= $termo->primeiro_venc_mens ?>">
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span3">
                    <label>Produto Adicional</label>
                    <input type="text" class="span12" name="produto_adicional" value="<?= $termo->produto_adicional ?>">
                </div>
                <div class="span2">
                    <label>Quantidade</label>
                    <input type="text" class="span12" name="qtd" value="<?= $termo->qtd ?>">
                </div>
                <div class="span2">
                    <label>Valor Unitário</label>
                    <input type="text" class="span12 money2" name="valor_final_un" value="<?= number_format($termo->valor_final_un, 2, ',', '.') ?>">
                </div>
                <div class="span2">
                    <label>Total</label>
                    <input type="text" class="span12 money2" name="total" value="<?= $termo->total ?>">
                </div>
            </div>
            <div class="controls controls-row">
                <div class="span3">
                    <label>Adicional em Parcelas</label>
                    <input type="text" class="span12 money2" name="adicional_parcelas" value="<?= $termo->adicional_parcelas ?>">
                </div>
                <div class="span2">
                    <label>1º Vencimento de Adicional</label>
                    <input type="text" class="span12" data-mask="00/00/0000" name="primeiro_venc_adicional" value="<?= $termo->primeiro_venc_adicional ?>">
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
            <div class="controls controls-row">
                <div class="span6">
                    <label>Instalação Sigilosa</label>
                    <input type="text" class="span12" name="inst_sigilosa" value="<?= $termo->inst_sigilosa ?>">
                </div>
                <div class="span6">
                    <label>Observação</label>
                    <input type="text" class="span12" name="obs" value="<?= $termo->obs ?>">
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

</script>
