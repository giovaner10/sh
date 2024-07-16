<style>
    .footer-group .btn-success {
        background-color: #28A745 !important;
    }

    .footer-group .btn-success:hover {
        background-color: #28a746e7 !important;
    }

    .header-layout .close{
        font-family: inherit !important;
        font-size: 25px !important;
    }
</style>

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content new-layout">
        <div class="modal-header header-layout" style="text-align: center;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 class="modal-title" id="myModalLabel">Adicionar Termo de Adesão</h3>
        </div>
        <div class="modal-body">
            <form id="formAddTermoAdesao" method="POST" target="_blank" action="<?= site_url('contratos/gerar_termo_pdf'); ?>">
                <input type="hidden" name="termo[cliente_id]" class="idcliente" value="<?= $cliente->id ?>">
                <h4 class="subtitle" style="padding: 10px 15px;">Dados do Contratante</h4>

                <div class="col-md-4 input-container form-group">
                    <label class="control-label" for="nome_cliente_termo">Nome/Razão Social:</label>
                    <input type="text" id="nome_cliente_termo" name="termo[nome_cliente]" placeholder="Digite o nome ou razão social..." class="form-control" maxlength="250" required
                        value="<?=isset($cliente->nome) ? $cliente->nome : (isset($cliente->razao_social) ? $cliente->razao_social : '')?>">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label class="control-label" for="documento_cliente_termo">CNPJ / CPF:</label>
                    <input type="text" id="documento_cliente_termo" name="termo[documento_cliente]" class="form-control cpf_cpnj" readonly
                        value="<?=$cliente->cpf ? $cliente->cpf : ($cliente->cnpj ? $cliente->cnpj : '')?>">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="inscricao_estadual_termo">Inscrição Estadual:</label>
                    <input type="text" id="inscricao_estadual_termo" name="termo[inscricao_estadual]" placeholder="Digite a inscrição estadual..." maxlength="20" class="form-control"
                        value="<?=isset($cliente->inscricao_estadual) ? $cliente->inscricao_estadual : ''?>">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label class="control-label" for="email_cliente_termo">E-mail:</label>
                    <input type="email" id="email_cliente_termo" name="termo[email_cliente]" placeholder="Digite o e-mail..." maxlength="255" class="form-control" required
                        value="<?=isset($cliente->email) ? $cliente->email : ''?>">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label class="control-label" for="telefone_cliente_termo">Telefone Fixo:</label>
                    <input type="text" id="telefone_cliente_termo" name="termo[telefone_cliente]" minlength="14" placeholder="Digite o telefone fixo..." class="form-control telefone" required
                        value="<?=isset($cliente->fone) ? $cliente->fone : ''?>">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label class="control-label" for="celular_cliente_termo">Telefone Celular:</label>
                    <input type="text" id="celular_cliente_termo" name="termo[celular_cliente]" minlength="14" placeholder="Digite o celular..." class="form-control telefone" required
                        value="<?=isset($cliente->cel) ? $cliente->cel : ''?>">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label class="control-label" for="cep_cliente_termo">CEP:</label>
                    <input type="text" id="cep_cliente_termo" name="termo[cep_cliente]" minlength="10" placeholder="Digite o CEP..." class="form-control cep" required
                        value="<?=isset($cliente->cep) ? $cliente->cep : ''?>">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label class="control-label" for="endereco_cliente_termo">Endereço:</label>
                    <input type="text" id="endereco_cliente_termo" name="termo[endereco_cliente]" maxlength="60" placeholder="Digite o endereço..." class="form-control" required
                        value="<?=isset($cliente->endereco) ? $cliente->endereco : ''?>">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label class="control-label" for="bairro_cliente_termo">Bairro:</label>
                    <input type="text" id="bairro_cliente_termo" name="termo[bairro_cliente]" maxlength="35" placeholder="Digite o bairro..." class="form-control" required
                        value="<?=isset($cliente->bairro) ? $cliente->bairro : ''?>">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label class="control-label" for="cidade_cliente_termo">Cidade:</label>
                    <input type="text" id="cidade_cliente_termo" name="termo[cidade_cliente]" maxlength="30" placeholder="Digite o cidade..." class="form-control" required
                        value="<?=isset($cliente->cidade) ? $cliente->cidade : ''?>">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="complemento_cliente_termo">Complemento:</label>
                    <input type="text" id="complemento_cliente_termo" name="termo[complemento_cliente]" maxlength="120" placeholder="Digite o complemento..." class="form-control"
                        value="<?=isset($cliente->complemento) ? $cliente->complemento : ''?>">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label class="control-label" for="estado_cliente_termo">Estado (UF):</label>
                    <input type="text" id="estado_cliente_termo" name="termo[estado_cliente]" minlength="2" maxlength="2" placeholder="Digite o estado..." class="form-control" required
                        value="<?=isset($cliente->uf) ? $cliente->uf : ''?>">
                </div>

                <h4 class="subtitle" style="padding: 10px 15px;">Condições Comerciais</h4>

                <div class="col-md-4 input-container form-group">
                    <label class="control-label" for="tipo_contrato_termo">Tipo de Contrato:</label>
                    <select class="form-control" id="tipo_contrato_termo" name="termo[tipo_contrato]" type="text" style="width: 100%" required>
                        <option value="COMODATO" selected>Comodato</option>
                    </select>
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="pessoa_contas_termo">Pessoa do Contas à Paga:</label>
                    <input type="text" id="pessoa_contas_termo" name="termo[pessoa_contas]" maxlength="200" placeholder="Digite o nome da pessoa..." class="form-control">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="contato_contas_termo">Contato do Contas à Pagar:</label>
                    <input type="text" id="contato_contas_termo" name="termo[contato_contas]" minlength="14" placeholder="Digite contato do contas..." class="form-control telefone">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="email_contas_termo">E-mail Financeiro:</label>
                    <input type="email" id="email_contas_termo" name="termo[email_contas]" placeholder="Digite o e-mail financeiro..." maxlength="255" class="form-control">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="qtd_equipamentos_termo">Quantidade de Equipamentos:</label>
                    <input type="text" id="qtd_equipamentos_termo" name="termo[qtd_equipamentos]" placeholder="Digite a quantidade de equipamentos..." class="form-control numero">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="bloqueio_termo">Bloqueio:</label>
                    <select class="form-control" id="bloqueio_termo" name="termo[bloqueio]" type="text" style="width: 100%">
                        <option value="" selected>Selecione uma opção</option>
                        <option value="SIM">Sim</option>
                        <option value="NÃO">Não</option>
                    </select>
                </div>

                <h4 class="subtitle" style="padding: 10px 15px;">Condições de Pagamento</h4>

                <div class="col-md-4 input-container form-group">
                    <label for="contato_pagamento_termo">Contato do Contas à Pagar:</label>
                    <input type="text" id="contato_pagamento_termo" name="termo[contato_pagamento]" minlength="14" placeholder="Digite contato do contas..." class="form-control telefone">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="email_pagamento_termo">E-mail:</label>
                    <input type="email" id="email_pagamento_termo" name="termo[email_pagamento]" placeholder="Digite o e-mail..." maxlength="255" class="form-control">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="parcelas_pagamento_termo">Instalação em Parcelas:</label>
                    <input type="text" id="parcelas_pagamento_termo" name="termo[parcelas_pagamento]" placeholder="Digite a quantidade de parcelas..." class="form-control numero">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="pacote_servico_termo">Pacote de Serviços:</label>
                    <input type="text" id="pacote_servico_termo" name="termo[pacote_servico]" maxlength="200" placeholder="Digite o pacote de serviços..." class="form-control">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="periodo_contrato_termo">Período do contrato:</label>
                    <input type="text" id="periodo_contrato_termo" name="termo[periodo_contrato]" placeholder="Digite a quantidade de meses..." class="form-control numero">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="dia_vencimento_termo">Dia de Vencimento:</label>
                    <input type="number" id="dia_vencimento_termo" name="termo[dia_vencimento]" min='1' max='31' placeholder="Digite o dia de vencimento..." class="form-control">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="valor_instalacao_termo">Valor de Instalação por Veículo:</label>
                    <input type="text" id="valor_instalacao_termo" name="termo[valor_instalacao]" placeholder="Digite o valor da instalação..." class="form-control dinheiro">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="vencimento_adesao_termo">1º Vencimento da Adesão:</label>
                    <input type="date" id="vencimento_adesao_termo" name="termo[vencimento_adesao]" class="form-control">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="vencimento_mensalidade_termo">1º Vencimento da Mensalidade:</label>
                    <input type="date" id="vencimento_mensalidade_termo" name="termo[vencimento_mensalidade]" class="form-control">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="valor_mensalidade_termo">Valor de Mensalidade por Veículo:</label>
                    <input type="text" id="valor_mensalidade_termo" name="termo[valor_mensalidade]" placeholder="Digite o valor da mensalidade..." class="form-control dinheiro">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="produto_adicional_termo">Produto Adicional:</label>
                    <input type="text" id="produto_adicional_termo" name="termo[produto_adicional]" maxlength="200" placeholder="Digite o nome do produto..." class="form-control">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="qtd_adicional_termo">Quantidade:</label>
                    <input type="text" id="qtd_adicional_termo" name="termo[qtd_adicional]" placeholder="Digite a quantidade do produto..." class="form-control numero" >
                </div>

                <div class="col-md-4 input-container form-group">
                    <label  for="valor_unt_adicional_termo">Valor Unitário:</label>
                    <input type="text" id="valor_unt_adicional_termo" name="termo[valor_unt_adicional]" placeholder="Digite o valor unitário..." class="form-control dinheiro">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label  for="adicional_parcelas_termo">Adicional em Parcelas:</label>
                    <input type="text" id="adicional_parcelas_termo" name="termo[adicional_parcelas]" placeholder="Digite o valor adicional..." class="form-control dinheiro">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="vencimento_adicional_termo">1º Vencimento de Adicional:</label>
                    <input type="date" id="vencimento_adicional_termo" name="termo[vencimento_adicional]" class="form-control">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="total_adicional_termo">Total:</label>
                    <input type="text" id="total_adicional_termo" name="termo[total_adicional]" placeholder="Digite o valor total..." class="form-control dinheiro">
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="instalacao_sigilosa_termo">Instalação Sigilosa:</label>
                    <select class="form-control" id="instalacao_sigilosa_termo" name="termo[instalacao_sigilosa]" type="text" style="width: 100%">
                        <option value="" selected>Selecione uma opção</option>
                        <option value="NÃO">Não</option>
                        <option value="SIM">Sim</option>
                    </select>
                </div>

                <div class="col-md-4 input-container form-group">
                    <label for="observacao_termo">Observação:</label>
                    <textarea id="observacao_termo" class="form-control" name="termo[observacao_termo]" rows="1" style="resize: vertical;" maxlength="500"></textarea>
                </div>

                <div class="col-md-12">
                    <hr style="margin: 5px 0;">
                </div>
                
                <div class="modal-footer footer-group" style="display: flex; justify-content: end;">
                    <button class="btn btn-success" id="btn-add-termo" type="submit">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.cep').mask('99.999-999').trigger("change");
    $('.numero').mask('9999999999').trigger("change");
    $('.dinheiro').maskMoney({
        prefix:'R$ ',
        allowZero: true,
        thousands: '.',
        decimal: ',',
    });

    // Mascara de telefone
    var SPMaskBehavior = function(val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
    spOptions = {
        onKeyPress: function(val, e, field, options) {
            field.mask(SPMaskBehavior.apply({}, arguments), options);
        }
    };

    $('.telefone').mask(SPMaskBehavior, spOptions);
    
    // MASCARA CPF CNPJ
	var options = {
		onKeyPress: function (cpf, ev, el, op) {
			var masks = ['000.000.000-000', '00.000.000/0000-00'];
			$(".cpf_cpnj").mask((cpf.length > 14) ? masks[1] : masks[0], op);
		}
	}

	$(".cpf_cpnj").length > 11 ? $(".cpf_cpnj").mask('00.000.000/0000-00', options) : $(".cpf_cpnj").mask('000.000.000-00#', options);

    $(".cpf_cpnj").on("change", function () {
        let cpf = $(this).val();
        var masks = ['000.000.000-000', '00.000.000/0000-00'];
		$(this).mask((cpf.length > 14) ? masks[1] : masks[0], options);
    });

    $("#cep_cliente_termo").on("blur", function(e){
        try{
            let cep = this.value.replace(".","").replace("-","")
            if (cep.length == 8) {
                $.getJSON(`https://viacep.com.br/ws/${cep}/json`, function(endereco) {
                    if (!("erro" in endereco)) {
                        $("#bairro_cliente_termo").val(endereco.bairro);
                        $("#cidade_cliente_termo").val(endereco.localidade);
                        $("#estado_cliente_termo").val(endereco.uf);
                        $("#endereco_cliente_termo").val(endereco.logradouro);
                        $("#complemento_cliente_termo").val(endereco.complemento);
                    } else{
                    }
                })
            }
        }catch(exception){

        }
    });

    $('#formAddTermoAdesao').submit(function(e) {
        e.preventDefault();
        
        // Validações do formulário
        let cep = $('#cep_cliente_termo').val().replace(".","").replace("-","");
        let emailFinanceiro = $("#email_contas_termo").val();
        let emailPagamento = $("#email_pagamento_termo").val();
        let telefoneCliente = $("#telefone_cliente_termo").val();
        let celularCliente = $("#celular_cliente_termo").val();
        let contatoContas = $("#contato_contas_termo").val();
        let contatoPagamento = $("#contato_pagamento_termo").val();
        
        if (cep && cep.length != 8) {
            showAlert("warning", "Digite um CEP válido!");
            return;
        }

        if (emailPagamento && !validateEmail(emailFinanceiro)) {
            showAlert("warning", "O campo e-mail financeiro não é válido!");
            return;
        }

        if (emailPagamento && !validateEmail(emailPagamento)) {
            showAlert("warning", "O e-mail de pagamento não é válido!");
            return;
        }

        if (telefoneCliente && telefoneCliente.length != 14 && telefoneCliente.length != 15) {
            showAlert("warning", "O campo telefone fixo não é válido!");
            return;
        }

        if (celularCliente && celularCliente.length != 14 && celularCliente.length != 15) {
            showAlert("warning", "O campo telefone celular não é válido!");
            return;
        }

        if (contatoContas && contatoContas.length != 14 && contatoContas.length != 15) {
            showAlert("warning", "O campo contato de condições comerciais não é válido!");
            return;
        }

        if (contatoPagamento && contatoPagamento.length != 14 && contatoPagamento.length != 15) {
            showAlert("warning", "O campo contato de condições de pagamento não é válido!");
            return;
        }
        
        // Se todas as validações passarem, envie o formulário
        this.submit();
        $("#modal_adicionar_termo_adesao").off('hide.bs.modal').modal("hide");
    });

    $('#modal_adicionar_termo_adesao').on('hide.bs.modal', function(e) {
        // Prevent modal from hiding immediately
        e.preventDefault();
        e.stopImmediatePropagation();

        Swal.fire({
            title: 'Atenção!',
            text: "Tem certeza que deseja fechar o modal sem salvar os dados?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Force close the modal
                $('#modal_adicionar_termo_adesao').off('hide.bs.modal').modal('hide');
                $("#formAddTermoAdesao")[0].reset();
            }
        });
    });
</script>