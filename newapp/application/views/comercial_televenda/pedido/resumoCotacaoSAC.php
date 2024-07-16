
<!-- Modal para exibir resumo da cotação-->

<div id="modalResumoCotacao" style="overflow-y: auto;" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="width: 1050px;">
        <div class="modal-content">
            <form name="formResumoCotacao">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("resumo_cotacao")?> <span id="numeroCotacaotitle">-</span></h3>
                    <div class="row" >
                        <h4 class="col-md-10">Valor Total da Cotação: <span id="CotacaoGastoTotal"></span></h4>
                        <button class="btn btn-primary buttonResumoPDF col-md-2" title="Resumo PDF" style="display: none;"> <i aria-hidden="true"></i>Resumo PDF</button>
                        <button class="btn btn-primary buttonResumoEmail col-md-2" title="Enviar Email"  style="margin-left: 10px; display: none; "> <i aria-hidden="true"></i>Resumo por Email</button>
                        <button class="btn btn-primary buttonAtualizarResumo col-md-1" title="Atualizar" style="width: auto; margin-left: 10px" > 
                            <i class="fa fa-refresh" aria-hidden="true" style="font-size: 2.7rem;"></i>
                        </button>
                    </div>
                </div>
                
                <div id="bodyModalResumoCotacao" class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs" style="margin-bottom: 10px;">
                                <li class="nav-item">
                                    <a id = "tab-dadosGerais" href="" data-toggle="tab" class="nav-link active">Dados Gerais</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-composicao" href="" data-toggle="tab" class="nav-link">Composição</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-hardwares" href="" data-toggle="tab" class="nav-link">Hardwares</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-licencas" href="" data-toggle="tab" class="nav-link">Licenças</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-servicos" href="" data-toggle="tab" class="nav-link">Serviços</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-configurometro" href="" data-toggle="tab" class="nav-link">Configurômetro</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-documentosCotacao" href="" data-toggle="tab" class="nav-link">Documentos da Cotação</a>
                                </li>
                                <li class="nav-item">
                                    <a id = "tab-cartaoCredito" href="" data-toggle="tab" class="nav-link">Cartão de Crédito</a>
                                </li>
                            </ul>
                            <div id="dadosGerais" style="display: block;">
                                <div class="row col-md-12">
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Número da cotação:</label>    
                                        <p id="numeroCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Versão da cotação:</label>    
                                        <p id="versaoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Cliente:</label>    
                                        <p id="clienteCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Início da vigência:</label>    
                                        <p id="inicioVigenciaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Término da vigência:</label>    
                                        <p id="terminoVigenciaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Plataforma:</label>    
                                        <p id="plataformaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Cenário da venda:</label>    
                                        <p id="cenarioVendaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Tipo de pagamento:</label>    
                                        <p id="tipoPagamentoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Condição de pagamento:</label>    
                                        <p id="condicaoPagamentoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Modalidade de venda:</label>    
                                        <p id="modalidadeVendaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Canal de venda:</label>    
                                        <p id="canalVendaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Tempo de contrato:</label>    
                                        <p id="tempoContratoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Tipo de frete:</label>    
                                        <p id="tipoFreteCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Signatário do software:</label>    
                                        <p id="signatarioSoftwareCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Email do signatário do software:</label>    
                                        <p id="emailSignatarioSoftwareCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Documento do signatário do software:</label>    
                                        <p id="documentoSignatarioSoftwareCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Signatário do hardware:</label>    
                                        <p id="signatarioHardwareCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Email do signatário do hardware:</label>    
                                        <p id="emailSignatarioHardwareCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Documento do signatário do hardware:</label>    
                                        <p id="documentoSignatarioHardwareCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Status docusign:</label>    
                                        <p id="statusDocusignCotacao">-</p>
                                    </div>
                                </div>
                            </div>
                            <div style = 'display: none !important;'id="composicao">
                                <div class="row col-md-12">
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Sistema:</label>    
                                        <p id="sistemaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Tipo de comunicação:</label>    
                                        <p id="tipoComunicacaoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Família do produto:</label>    
                                        <p id="familiaProdutoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Produto:</label>    
                                        <p id="produtoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Categoria:</label>    
                                        <p id="categoriaCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Tipo de veículo:</label>    
                                        <p id="tipoVeiculoCotacao">-</p>
                                    </div>
                                    <div class="col-md-6 form-group bord">
                                        <label for="">Quantidade:</label>    
                                        <p id="quantidadeCotacao">-</p>
                                    </div>

                                </div>
                            </div>
                            <div style="display: none !important;" id="hardwares">
                                <div class="row">
                                    <button class="btn btn-primary" title="Adicionar hardware" style="margin-top: 4px; margin-left: 12px;display: none;" id="addHardware" onClick="javascript:abrirDadosAddHardware(this)"> <i class="fa fa-plus" aria-hidden="true"></i> Adicionar Hardware</button>
                                </div>
                                <table class="table table-striped table-bordered table-hover" id="tableHardwaresCotacao" style="width: 100%;">
                                    <thead>
                                        <tr class="tableheader">
                                            <th hidden>HardwareId</th>
                                            <th>Produto</th>
                                            <th>Valor Unitário</th>
                                            <th>Quantidade</th>
                                            <th>Valor Total</th>                                              
                                            <th>% de Desconto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align:right">Total:</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div style="display: none !important;" id="licencas">
                                <div class="row">
                                    <button class="btn btn-primary" title="Adicionar licença" style="margin-top: 4px; margin-left: 12px;display: none;" id="addLicenca" onClick="javascript:abrirDadosAddLicenca(this)"> <i class="fa fa-plus" aria-hidden="true"></i> Adicionar Licença</button>
                                </div>
                                <table class="table table-striped table-bordered table-hover" id="tableLicencasCotacao" style="width: 100%;">
                                    <thead>
                                        <tr class="tableheader">
                                            <th hidden>LicencaId</th>
                                            <th>Produto</th>
                                            <th>Valor Unitário</th>
                                            <th>Quantidade</th>
                                            <th>Valor Total</th>
                                            <th>Plano Satelital</th>
                                            <th>% de Desconto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align:right">Total:</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div style="display: none !important;" id="servicos">
                                <div class="row">
                                    <button class="btn btn-primary" title="Adicionar serviço" style="margin-top: 4px; margin-left: 12px;display: none;" id="addServico" onClick="javascript:abrirDadosAddServico(this)"> <i class="fa fa-plus" aria-hidden="true"></i> Adicionar Serviço</button>
                                </div>
                                <table class="table table-striped table-bordered table-hover" id="tableServicosCotacao" style="width: 100%;">
                                    <thead>
                                        <tr class="tableheader">
                                            <th hidden>ServicoId</th>
                                            <th>Produto</th>
                                            <th>Valor Unitário</th>
                                            <th>Quantidade</th>
                                            <th>Valor Total</th>
                                            <th>% de Desconto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align:right">Total:</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>                                
                            <div style = 'display: none !important; position: relative;'id="configurometro">
                                <input type="text" class="form-control" id="gerouConfigurometro" style="visibility:hidden;"/>
                                <div id="blockIframe"></div>
                                <div id="loadingConfigurometro" style = 'display: none';>
                                    <i class="configurometroLoader" ></i>
                                </div>
                                <iframe id="iframePagina" style="width: 100%; height: 500px;"></iframe>
                            </div>  
                            <div style="display: none !important;" id="documentosCotacao">
                                <div>
                                    <br>
                                    <div class="col-md-6">
                                        <label for="">Assunto:</label>
                                        <input type="text" class="form-control" id="assuntoDocCotacao" name="assuntoDocCotacao" />
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">&nbsp;</label>
                                        <label for="docs_cotacao" id="docs_cotacao_label" class="btn btn-default col-sm-12">Selecione um Arquivo</label>
                                        <input id="docs_cotacao" type="file" class="btn btn-default"  style="visibility:hidden;" name="ArquivoCotacao"/>
                                        <input id="cotacaoid" style="visibility:hidden;" name="cotacao"/>
                                    </div>
                                    <div class="col-md-2" style="padding-left: 0px !important; padding-top: 2.5%">
                                        <a class="btn btn-primary" id="btn-adicionarDoc">Adicionar Item</a>
                                    </div>
                                </div>
                                <table style="width: 100%" id="table-documentosCotacao" class="table table-striped table-bordered" style="display: block !important;">
                                    <thead>
                                        <th>Assunto</th>
                                        <th>Arquivo</th>
                                        <th>Ações</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div> 
                            <div style="display: none !important;" id="divCartaoCredito">
                                <form name="formAtualizaCartao"> 
                                    <div class="row">
                                        <div id ="selectBandeira" class="col-md-6 form-group" hidden>
                                            <label class="control-label">Bandeira</label>
                                            <select class="form-control" id="bandeiraCartao" name="bandeiraCartao" required>
                                                <option value="0" disabled>Selecione a bandeira</option>
                                                <option value="1" hidden>Amex</option>
                                                <option value="2" hidden>Diners</option>
                                                <option value="3" hidden>Hipercard</option>
                                                <option value="4" hidden>JCB</option>
                                                <option value="5" hidden>Mastercard</option>
                                                <option value="6">Sorocred</option>
                                                <option value="7" hidden>Visa</option>
                                                <option value="8">Elo</option>
                                                <option value="9">Agiplan</option>
                                                <option value="10">Banescard</option>
                                                <option value="11">Credz</option>
                                                <option value="12">Hiper</option>
                                                <option value="13">Cabal</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Número do Cartão</label>
                                            <input type="number" aria-labelledby="label-numeroCartao"  class="form-control" id="numeroCartao" style="background-repeat: no-repeat; background-position: right ;" placeholder="Digite o número do cartão" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="16" required>
                                            <label for="numeroCartao" id="label-numeroCartao" style="color: red; font-size: 10px; position: absolute;" hidden>
                                                Número do cartão inválido!
                                            </label>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Código de Segurança (CVV)</label>
                                            <input type="number" class="form-control" id="codigoCartao" placeholder="Digite o código de segurança do cartão"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="4" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Nome Impresso no Cartão</label>
                                            <input type="text" class="form-control" id="nomeCartao" placeholder="Digite o nome impresso do cartão" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Mês de Validade do Cartão</label>
                                            <input type="number" min="1" max="12" class="form-control" id="mesValidadeCartao" placeholder="Digite o mês com um ou dois dígitos. Ex1: '05'; Ex2: '5'" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="2"required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="control-label">Ano de Validade do Cartão</label>
                                            <input type="number" class="form-control" id="anoValidadeCartao" placeholder="Digite o ano com dois dígitos. Ex: '23' " oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="2"required>
                                        </div>
                                    </div>
                                </form>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary" id="btnSalvarAtualizaCartao" style="display: none !important;">Salvar</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    
    var idCotacao = ""
    var tableHardwaresCotacao;
    var tableLicencasCotacao;
    var tableServicosCotacao;
    var tableDocumentosCotacao;

    let HardwaresValorTotal = 0;
    let LicencasValorTotal = 0;
    let ServicoValorTotal = 0;

    var bandeiraCartaoCredito = '';
    var numBandeiraCartaoCredito = '';
    var tableArquivoForm;

    function abrirModalResumoCotacao(botao){
        let id = $(botao).attr('data-statuscode');
        botao = $(botao);
        idCotacao = id;
        AtualizarModalResumoCotacao(botao, botao.html(), '<i class="fa fa-spinner fa-spin"></i>', "abrirModal");
    }  

    function AtualizarModalResumoCotacao(botao, textoBotao, spin, origem = ""){
        document.getElementById('loading').style.display = 'block';

        if(botao){
            botao.html(spin);
            botao.attr('disabled', true);
        }
        
        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/resumoCotacao') ?>",
            dataType: "json",
            type: "POST",
            data: { idCotacao: idCotacao},
            success: function(data) {
                console.log(data)
                if (data.Status == 200) {
                    document.getElementById('loading').style.display = 'none';
                    if(botao){
                        botao.html(textoBotao);
                        botao.attr('disabled', false);
                    }
                    $('#numeroCotacao').text(data.resumo['numeroCotacao'] || '-');
                    $('#numeroCotacaotitle').text(data.resumo['numeroCotacao'] || '-');
                    $('#versaoCotacao').text(data.resumo['versaoCotacao'] || '-');
                    $('#clienteCotacao').text(data.resumo['cliente'] || '-');
                    $('#inicioVigenciaCotacao').text(data.resumo['inicioVigencia'] || '-');
                    $('#terminoVigenciaCotacao').text(data.resumo['terminoVigencia'] || '-');
                    $('#plataformaCotacao').text(data.resumo['plataforma'] || '-');
                    $('#cenarioVendaCotacao').text(data.resumo['cenarioVenda'] || '-');
                    $('#tipoPagamentoCotacao').text(data.resumo['tipoPagamento'] || '-');
                    $('#condicaoPagamentoCotacao').text(data.resumo['condicaoPagamento'] || '-');
                    $('#modalidadeVendaCotacao').text(data.resumo['modalidadeVenda'] || '-');
                    $('#canalVendaCotacao').text(data.resumo['canalVenda']);
                    $('#tempoContratoCotacao').text(data.resumo['tempoContrato'] || '-');
                    $('#tipoFreteCotacao').text(data.resumo['tipoFrete'] || '-');
                    $('#signatarioSoftwareCotacao').text(data.resumo['signatario_software'] || '-');
                    $('#emailSignatarioSoftwareCotacao').text(data.resumo['email_signatario_software'] || '-');
                    $('#documentoSignatarioSoftwareCotacao').text(data.resumo['documento_signatario_software'] || '-');
                    $('#signatarioHardwareCotacao').text(data.resumo['signatario_hardware'] || '-');
                    $('#emailSignatarioHardwareCotacao').text(data.resumo['email_signatario_hardware'] || '-');
                    $('#documentoSignatarioHardwareCotacao').text(data.resumo['documento_signatario_hardware'] || '-');
                    $('#statusDocusignCotacao').text(data.resumo['statusDocusign'] || '-');
                    $('#sistemaCotacao').text(data.resumo?.composicao?.sistema || '-');
                    $('#tipoComunicacaoCotacao').text(data.resumo?.composicao?.tipoComunicacao  || '-');
                    $('#familiaProdutoCotacao').text(data.resumo?.composicao?.familiaProduto || '-');
                    $('#produtoCotacao').text(data.resumo?.composicao?.produto || '-');
                    $('#categoriaCotacao').text(data.resumo?.composicao?.categoria || '-');
                    $('#tipoVeiculoCotacao').text(data.resumo?.composicao?.tipoVeiculo || '-');
                    $('#quantidadeCotacao').text(data.resumo?.composicao?.quantidade || '-');
                    
                    let gerouConfigurometro = data.resumo?.composicao?.gerouConfigurometro != undefined ? data.resumo?.composicao?.gerouConfigurometro : true;

                    $('#gerouConfigurometro').val(gerouConfigurometro)

                    if(origem == "abrirModal"){
                        if(!gerouConfigurometro){
                            $('#tab-configurometro').click();
                        }else{
                            $('#tab-dadosGerais').click();
                        }
                        $('#modalResumoCotacao').modal('show');
                    }

                    tableServicosCotacao.clear().draw();
                    tableLicencasCotacao.clear().draw();
                    tableHardwaresCotacao.clear().draw();

                    if(data.resumo['hardwares'] && data.resumo['hardwares'].length > 0){
                        $.each(data.resumo['hardwares'], function(index, value){
                            tableHardwaresCotacao.row.add({
                                "hardwareid": value['hardwareid'] || '',
                                "produto": value['produto'] || '-',
                                "valorUnitarioCotacao": value['valorUnitario'] || '-',
                                "valorTotalCotacao": value['valorTotal'] || '-',
                                "quantidadeCotacao": value['quantidade'] || '-',
                                "percentualDescontoCotacao": value['percentualDesconto']+'%' || '-',
                            }).draw();                            
                        })
                    }else{
                        tableHardwaresCotacao.row.add({
                            "hardwareid": '',
                            "produto": '-',
                            "valorUnitarioCotacao": '-',
                            "valorTotalCotacao": '-',
                            "quantidadeCotacao": '-',
                            "percentualDescontoCotacao": '-',
                        }).draw();
                    }

                    if(data.resumo['licencas'] && data.resumo['licencas'].length > 0){
                        $.each(data.resumo['licencas'], function(index, value){
                            tableLicencasCotacao.row.add({
                                "licencaid": value['licencaid'] || "",
                                "produto": value['produto'] || '-',
                                "valorUnitarioCotacao": value['valorUnitario'] || '-',
                                "valorTotalCotacao": value['valorTotal'] || '-',
                                "quantidadeCotacao": value['quantidade'] || '-',
                                "percentualDescontoCotacao": value['percentualDesconto']+'%' || '-',
                                "planoSatelitalCotacao": value['planoSatelital'] || '-',
                            }).draw();     
                        })
                    }else{
                        tableLicencasCotacao.row.add({
                            "licencaid": '',
                            "produto": '-',
                            "valorUnitarioCotacao": '-',
                            "valorTotalCotacao": '-',
                            "quantidadeCotacao": '-',
                            "percentualDescontoCotacao": '-',
                            "planoSatelitalCotacao": '-',
                        }).draw();
                    }

                    if(data.resumo['servicos'] && data.resumo['servicos'].length > 0){
                        $.each(data.resumo['servicos'], function(index, value){
                            tableServicosCotacao.row.add({
                                "servicoid": value['servicoid'] || "",
                                "produto": value['produto'] || '-',
                                "valorUnitarioCotacao": value['valorUnitario'] || '-',
                                "valorTotalCotacao": value['valorTotal'] || '-',
                                "quantidadeCotacao": value['quantidade'] || '-',
                                "percentualDescontoCotacao": value['percentualDesconto']+'%' || '-',
                            }).draw();     
                        })
                    }else{
                        tableServicosCotacao.row.add({
                            "servicoid": '',
                            "produto": '-',
                            "valorUnitarioCotacao": '-',
                            "valorTotalCotacao": '-',
                            "quantidadeCotacao": '-',
                            "percentualDescontoCotacao": '-',
                        }).draw();
                    }
                    
                }else{
                    $('#modalResumoCotacao').modal('hide');
                    alert("Não foi possível carregar os dados da cotação! Tente novamente.");
                    document.getElementById('loading').style.display = 'none';
                    if(botao){
                        botao.html(textoBotao);
                        botao.attr('disabled', false);
                    }

                }
            },
        });
    }

    function parseValueToFloat(value) {
        if (!value.includes(",")) {
            if (Number.isNaN(Number.parseFloat(value))) {
                return 0;
            }
            return parseFloat(value);            
        }
        value = value.replace(".", "").replace(",", ".");
        if (Number.isNaN(Number.parseFloat(value))) {
            return 0;
        }
        return parseFloat(value);
    }
    
    $(document).ready(function() {
        $('.buttonResumoEmail').on('click', function(e) {
            e.preventDefault();
            botao = $(this);
            botao.html('<i class="fa fa-spinner fa-spin"></i>');
            botao.attr('disabled', true);

            $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/enviarResumoCotacao') ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    idCotacao: idCotacao,
                },
                success: function(data){
                    if (data.code == 200) {
                        alert("Email enviado com sucesso!");
                        botao.html(' <i aria-hidden="true"></i> Enviar Email');
                        botao.attr('disabled', false);
                    }else{
                        alert("Não foi possível enviar! Tente novamente.");
                        botao.html(' <i aria-hidden="true"></i> Enviar Email');
                        botao.attr('disabled', false);
                    }
                },
                error: function(data){
                    
                    alert("Não foi possível enviar! Tente novamente.");
                    botao.html(' <i aria-hidden="true"></i> Enviar Email');
                    botao.attr('disabled', false);
                },
            });
            
        });

        $('.buttonResumoPDF').on('click', function(e) {
            e.preventDefault();

            window.open('<?php echo site_url('ComerciaisTelevendas/Pedidos/template_pdf_cotacao?idCotacao=') ?>'+idCotacao, '_blank');
        });

        tableHardwaresCotacao = $("#tableHardwaresCotacao").DataTable({
            responsive: true,
            ordering: true,
            paging: true,
            searching: true,
            info: true,
            language: {
                loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
                searchPlaceholder:  'Pesquisar',
                emptyTable:         "Nenhum hardware a ser listado",
                info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
                zeroRecords:        "Nenhum resultado encontrado.",
                paginate: {
                    first:          "Primeira",
                    last:           "Última",
                    next:           "Próxima",
                    previous:       "Anterior"
                },
            },
            deferRender: true,
            lengthChange: false,
            columns: [
                { data: 'hardwareid',
                    visible: false},
                { data: 'produto' },
                { data: 'valorUnitarioCotacao',  
                    render: function (data){ valor = data;                               
                    if(valor === '-'){ 
                        return valor;
                    }else{
                        return parseValueToFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}} 
                },
                { data: 'quantidadeCotacao' },
                { data: 'valorTotalCotacao', 
                    render: function (data){ valor = data;                                               
                    if(valor === '-'){ 
                        return valor;
                    }else{
                        return parseValueToFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}} 
                },
                { data: 'percentualDescontoCotacao' },
            ],
            footerCallback: function (row, data, start, end, display) {
                var api = this.api();
    
                // Total over all pages
                total = api
                    .column(4)
                    .data()
                    .reduce(function (a, b) {
                        return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(4, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                    }, 0);

                // Update footer
                $(api.column(5).footer()).html(pageTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}) + ' ( ' + total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}) + ' total)');
                
                HardwaresValorTotal = total;
                let CotacaoGastoTotal = HardwaresValorTotal + LicencasValorTotal + ServicoValorTotal;
                let CotacaoGastoTotalFormat = CotacaoGastoTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})
                $("#CotacaoGastoTotal").html(CotacaoGastoTotalFormat);
            },
        });

        tableLicencasCotacao = $('#tableLicencasCotacao').DataTable({
            responsive: true,
                ordering: true,
                paging: true,
                searching: true,
                info: true,
                language: {
                    loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
                    searchPlaceholder:  'Pesquisar',
                    emptyTable:         "Nenhuma licença a ser listada",
                    info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
                    zeroRecords:        "Nenhum resultado encontrado.",
                    paginate: {
                        first:          "Primeira",
                        last:           "Última",
                        next:           "Próxima",
                        previous:       "Anterior"
                    },
                },
                deferRender: true,
                lengthChange: false,
                columns: [
                    { data: 'licencaid',
                        visible: false},
                    { data: 'produto' },
                    { data: 'valorUnitarioCotacao',  
                        render: function (data){ valor = data;                               
                        if(valor === '-'){ 
                            return valor;
                        }else{
                            return parseValueToFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}} 
                    },
                    
                    { data: 'quantidadeCotacao' },
                    { data: 'valorTotalCotacao',  
                        render: function (data){ valor = data;                              
                        if(valor === '-'){ 
                            return valor;
                        }else{
                            return parseValueToFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}} 
                    },    
                    { data: 'planoSatelitalCotacao'},    
                    { data: 'percentualDescontoCotacao'},
                ],
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
        
                    // Total over all pages
                    total = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                        }, 0);

                    // Total over this page
                    pageTotal = api
                        .column(4, { page: 'current' })
                        .data()
                        .reduce(function (a, b) {
                            return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                        }, 0);

                    // Update footer
                    $(api.column(5).footer()).html(pageTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}) + ' ( ' + total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}) + ' total)');
                    
                    LicencasValorTotal = total;
                    let CotacaoGastoTotal = HardwaresValorTotal + LicencasValorTotal + ServicoValorTotal;
                    let CotacaoGastoTotalFormat = CotacaoGastoTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})
                    $("#CotacaoGastoTotal").html(CotacaoGastoTotalFormat);
                },
        });

        tableServicosCotacao = $('#tableServicosCotacao').DataTable({
            responsive: true,
            ordering: true,
            paging: true,
            searching: true,
            info: true,
            language: {
                loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
                searchPlaceholder:  'Pesquisar',
                emptyTable:         "Nenhum serviço a ser listado",
                info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
                zeroRecords:        "Nenhum resultado encontrado.",
                paginate: {
                    first:          "Primeira",
                    last:           "Última",
                    next:           "Próxima",
                    previous:       "Anterior"
                },
            },
            deferRender: true,
            lengthChange: false,
            columns: [
                { data: 'servicoid',
                    visible: false},
                { data: 'produto' },
                { data: 'valorUnitarioCotacao',  
                    render: function (data){ valor = data;                               
                    if(valor === '-'){ 
                        return valor;
                    }else{
                        return parseValueToFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}} 
                },
                { data: 'quantidadeCotacao' },
                { data: 'valorTotalCotacao',  
                    render: function (data){ valor = data;                               
                    if(valor === '-'){ 
                        return valor;
                    }else{
                        return parseValueToFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}} 
                },
                { data: 'percentualDescontoCotacao'},
            ],
            footerCallback: function (row, data, start, end, display) {
                var api = this.api();
    
                // Total over all pages
                total = api
                    .column(4)
                    .data()
                    .reduce(function (a, b) {
                        return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(4, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return parseValueToFloat(a+"") + parseValueToFloat(b+"");
                    }, 0);

                // Update footer
                $(api.column(5).footer()).html(pageTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}) + ' ( ' + total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'}) + ' total)');
                
                ServicoValorTotal = total;
                let CotacaoGastoTotal = HardwaresValorTotal + LicencasValorTotal + ServicoValorTotal;
                let CotacaoGastoTotalFormat = CotacaoGastoTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})
                $("#CotacaoGastoTotal").html(CotacaoGastoTotalFormat);
            },
        });

        tableDocumentosCotacao = $('#table-documentosCotacao').DataTable({
            responsive: true,
            ordering: false,
            paging: true,
            info: true,
            language: lang.datatable,
            deferRender: true,
            lengthChange: false,
            columns: [
                { data: 'assunto' },
                { data: 'arquivo' },
                { data: 'idAnnotation' },
            ],
            columnDefs: [
                {
                    render: function (data, type, row) // Visualizar Arquivos
                    {
                        return `<div>
                                <button 
                                    id="btnRemoveDocumentoCot"
                                    class="btn"
                                    title="Remover Documento"
                                    style="margin: 0 auto; background-color: red; color: white;display: none;"
                                    onClick="javascript:removerDocumento(this, event, '${data}')">
                                    <i class="fa fa-trash" aria-hidden="true" style="display: flex;align-items: center;justify-content: center; "></i>
                                </button>
                                <a data-idAnnotation= '${data}' class="btn btn-primary download_docs" role="button" title="Download"><i class="fa fa-download"></i></a></button>
                                </div>
                        `;
                    },
                    targets: 2
                }
            ],
        });
        
        $('#tab-dadosGerais').click(function (e){
            e.preventDefault();
            $('#dadosGerais').show();
            $('#composicao').hide();
            $('#hardwares').hide();
            $('#licencas').hide();
            $('#servicos').hide();
            $('#configurometro').hide();
            $("#documentosCotacao").hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide(); 
        })

        $('#tab-documentosCotacao').click(function (e){
            e.preventDefault();
            $("#documentosCotacao").show();
            $('#dadosGerais').hide();
            $('#composicao').hide();
            $('#hardwares').hide();
            $('#licencas').hide();
            $('#servicos').hide();
            $('#configurometro').hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide(); 



            $.ajax({
                url: '<?php echo site_url('ComerciaisTelevendas/Pedidos'); ?>'+ '/getDocumentosCotacao',
                type: 'POST',
                data: {idCotacao: idCotacao},
                success: function (data) {
                    data = JSON.parse(data);
                    if(data.status == 200){
                        response = data.data;
                        tableDocumentosCotacao.clear().draw();
                        tableDocumentosCotacao.rows.add(response).draw();
                    }
                }
            });
        });
        
        $('#tab-composicao').click(function (e){
            e.preventDefault();
            $('#composicao').show();
            $('#dadosGerais').hide();
            $('#hardwares').hide();
            $('#licencas').hide();
            $('#servicos').hide();
            $('#configurometro').hide();
            $("#documentosCotacao").hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide(); 

        })


        $('#tab-hardwares').click(function (e){
            e.preventDefault();
            $('#hardwares').show();
            $('#dadosGerais').hide();
            $('#composicao').hide();
            $('#licencas').hide();
            $('#servicos').hide();
            $('#configurometro').hide();
            $("#documentosCotacao").hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide(); 
        })

        $('#tab-licencas').click(function (e){
            e.preventDefault();
            $('#licencas').show();
            $('#dadosGerais').hide();
            $('#composicao').hide();
            $('#hardwares').hide();
            $('#servicos').hide();
            $('#configurometro').hide();
            $("#documentosCotacao").hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide(); 
        })

        $('#tab-servicos').click(function (e){
            e.preventDefault();
            $('#servicos').show();
            $('#dadosGerais').hide();
            $('#composicao').hide();
            $('#hardwares').hide();
            $('#licencas').hide();
            $('#configurometro').hide();
            $("#documentosCotacao").hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide(); 
        })

        $('#tab-cartaoCredito').click(function (e){
            e.preventDefault();
            $('#divCartaoCredito').show();
            $('#btnSalvarAtualizaCartao').hide(); 
            $('#servicos').hide();
            $('#dadosGerais').hide();
            $('#composicao').hide();
            $('#hardwares').hide();
            $('#licencas').hide();
            $('#configurometro').hide();
            $("#documentosCotacao").hide();

            $('#numeroCartao').val('');
            $('#codigoCartao').val('');
            $('#nomeCartao').val('');
            $('#mesValidadeCartao').val('');
            $('#anoValidadeCartao').val('');
            $('#bandeiraCartao').val(0);
            document.getElementById("numeroCartao").style.backgroundImage = "url('')";
            $('#codigoCartao').prop('disabled', true);
            $('#nomeCartao').prop('disabled', true);
            $('#mesValidadeCartao').prop('disabled', true);
            $('#anoValidadeCartao').prop('disabled', true);
            $('#selectBandeira').hide();
        })

        $('#tab-configurometro').click(function (e){
            e.preventDefault();    
            $('#configurometro').show();
            $('#dadosGerais').hide();
            $('#composicao').hide();
            $('#hardwares').hide();
            $('#licencas').hide();
            $('#servicos').hide();
            $("#documentosCotacao").hide();
            $('#divCartaoCredito').hide();
            $('#btnSalvarAtualizaCartao').hide();

            var iframe = document.getElementById("iframePagina");    
            var url = '';
            $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/getVeiculoCotId') ?>",
                dataType: "json",
                type: "POST",
                data: { idCotacao: idCotacao},
                beforeSend: function() {            
                    $('#loadingConfigurometro').show();
                },
                success: function(data){
                    if(data.status == 200 && data.results.value.length > 0){
                        $.ajax({
                            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/pegarURLConfigurometro') ?>",
                            type: "POST",
                            data: { idVeiculo:  data.results.value[0]['tz_tipo_veiculo_cotacaoid']},
                            success:function (retorno){
                                iframe.onload = function() {                            
                                    $('#loadingConfigurometro').hide();
                                };
                                iframe.src = retorno;
                            }
                        });
                    } else {
                        alert("Não foi possível encontrar o configurômetro!")
                        $('#loadingConfigurometro').hide();
                    }
                },
                complete: function(){

                },                                  
            });
        });
        
        $('.buttonAtualizarResumo').on('click', function(e) {
            e.preventDefault();
            botao = $(this);
            AtualizarModalResumoCotacao(botao, '<i class="fa fa-refresh" aria-hidden="true" style="font-size: 2.7rem;"></i>', '<i class="fa fa-spinner fa-spin" style="font-size: 2.7rem;"></i>');
        });

        $('#addHardware').click(function(e){
            e.preventDefault();
            $('#selectHardware').val('').trigger('change');
        });

        $('#addLicenca').click(function(e){
            e.preventDefault();
            $('#selectLicenca').val('').trigger('change');
        });

        $('#addServico').click(function(e){
            e.preventDefault();
            $('#selectServico').val('').trigger('change');
        });

        $('#selectHardware').select2({
            ajax:{
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/produtosComposicao') ?>",
                type: 'POST',
                dataType: 'json',
                delay: 1000,
                data: function (params) {
                    return {
                        nomeProduto : params.term,
                        numeroProduto: params.term
                    };

                },
                
            },
            placeholder: 'Selecione um produto',
            allowClear: true,
            language: "pt-BR",
        });

        $('#selectLicenca').select2({
            ajax:{
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/produtosComposicao') ?>",
                type: 'POST',
                dataType: 'json',
                delay: 1000,
                data: function (params) {
                    return {
                        nomeProduto : params.term,
                        numeroProduto: params.term
                    };

                },
                
            },
            placeholder: 'Selecione um produto',
            allowClear: true,
            language: "pt-BR",
        });

        $('#selectServico').select2({
            ajax:{
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/produtosComposicao') ?>",
                type: 'POST',
                dataType: 'json',
                delay: 1000,
                data: function (params) {
                    return {
                        nomeProduto : params.term,
                        numeroProduto: params.term
                    };

                },
                
            },
            placeholder: 'Selecione um produto',
            allowClear: true,
            language: "pt-BR",
        });

        $('#btnSalvarAddSubItem').click(function(e){
            e.preventDefault();
            var idProduto = $('#selectHardware').val();
            var idCot = idCotacao;
            botao = $('#btnSalvarAddSubItem');
            botao.html('<i class="fa fa-spinner fa-spin"></i>');
            botao.attr('disabled', true);

            $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/addSubItemC') ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    idCotacao: idCot,
                    idProduto: idProduto,
                    tipo : 'hardware'
                },
                success: function(data){
                    if(data.results.Status == 200){
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert(data.results.Message);
                        $('#modalHardware').modal('hide');
                        AtualizarModalResumoCotacao(null, null, null);
                    }else if (!idProduto) {
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert('Selecione um produto!');

                    }else{
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert(data.results.Message);
                    }
                },
            });

        });

        $('#btnSalvarAddSubItemLicenca').click(function(e){
            e.preventDefault();
            var idProduto = $('#selectLicenca').val();
            var idCot = idCotacao;
            botao = $('#btnSalvarAddSubItemLicenca');
            botao.html('<i class="fa fa-spinner fa-spin"></i>');
            botao.attr('disabled', true);

            $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/addSubItemC') ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    idCotacao: idCot,
                    idProduto: idProduto,
                    tipo : 'licenca'
                },
                success: function(data){
                    if(data.results.Status == 200){
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert(data.results.Message);
                        $('#modalLicenca').modal('hide');
                        AtualizarModalResumoCotacao(null, null, null);
                    }else if (!idProduto) {
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert('Selecione um produto!');
                    }
                    else{
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert(data.results.Message);
                    }
                },
            });

        });

        $('#btnSalvarAddSubItemServico').click(function(e){
            e.preventDefault();
            var idProduto = $('#selectServico').val();
            var idCot = idCotacao;
            botao = $('#btnSalvarAddSubItemServico');
            botao.html('<i class="fa fa-spinner fa-spin"></i>');
            botao.attr('disabled', true);

            $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/addSubItemC') ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    idCotacao: idCot,
                    idProduto: idProduto,
                    tipo : 'servico'
                },
                success: function(data){
                    if(data.results.Status == 200){
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert(data.results.Message);
                        $('#modalServico').modal('hide');
                        AtualizarModalResumoCotacao(null, null, null);

                    }else if (!idProduto) {
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert('Selecione um produto!');
                    }
                    else{
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                        alert(data.results.Message);
                    }
                },
            });

        });

        $("#btn-adicionarDoc").click(function (e){
            e.preventDefault();

            $('.feedback-alert').html('');

            botao = $(this);
            botaohtml = $(this).html();
            ativarBotaoSpin(botao);

            let dataForm = new FormData();
            let cotacao = idCotacao;
            let assunto = $('#assuntoDocCotacao').val();
            let file_data = $('#docs_cotacao')[0];
            let file = file_data.files[0];

            dataForm.set("cotacao_id", cotacao);
            dataForm.set("assunto", assunto);
            dataForm.set("observacao", "Anexo da cotação");

            
            let url = "<?= site_url('ComerciaisTelevendas/Pedidos/cadastrarObservacao') ?>";
            if(file) { 
                dataForm.set("Arquivo", file);
                url = "<?= site_url('ComerciaisTelevendas/Pedidos/addArquivoCotacaoCRM') ?>";
            }
            
            $.ajax({
                url: url,
                type: "POST",
                data: dataForm,     
                processData: false,
                contentType: false,  
                success: function(data){
                    document.getElementById('loading').style.display = 'none';
                    let datajson = JSON.parse(data);
                    const statusRetorno = datajson?.Status ?? datajson?.status;

                    if(statusRetorno && statusRetorno == 200){
                        alert("Observação enviada com sucesso.");
                        AtualizarTabelaDocumentosCotacao(cotacao);
                        $('#assuntoDocCotacao').val("");
                        $('#docs_cotacao').val("");
                        $('#docs_cotacao_label').html("Selecione um Arquivo");
                    }
                    else{
                        alert(datajson.ExceptionMessage ?? "Ocorreu um problema ao enviar a observação.");
                    }
                },
                error: function(error){
                    console.log(error);
                    alert("Ocorreu um problema ao enviar a observação. Com o seguinte erro:" + error);
                },
                complete: function(){
                    desativarBotaoSpin(botao, botaohtml);
                },
            });  
        })

        $('#numeroCartao').keyup(function(e){
            var numeroCartao = $('#numeroCartao').val();
            if (numeroCartao.length >= 14){
            $.ajax({
                        method: "POST",
                        url: "Pedidos/validarCartaoCredito",
                        data: { numero: numeroCartao }
                    })
                    .done(function(result) {
                        if (result == 0 && bandeiraCartaoCredito != 'Desconhecida' ) {
                            var url = "<?php echo base_url('media/img/bandeira-cartoes') ?>"

                            document.getElementById("numeroCartao").style.backgroundImage = "url(" + url + "/" + bandeiraCartaoCredito + '.png' + ")";
                            $('#label-numeroCartao').hide();
                            $('#codigoCartao').prop('disabled', false);
                            $('#nomeCartao').prop('disabled', false);
                            $('#mesValidadeCartao').prop('disabled', false);
                            $('#anoValidadeCartao').prop('disabled', false);
                            $('#selectBandeira').hide();
                        }else if (result == 0 && bandeiraCartaoCredito == 'Desconhecida'){
                            document.getElementById("numeroCartao").style.backgroundImage = "url('')";
                            $('#selectBandeira').show();
                            $('#label-numeroCartao').hide();
                            $('#codigoCartao').prop('disabled', false);
                            $('#nomeCartao').prop('disabled', false);
                            $('#mesValidadeCartao').prop('disabled', false);
                            $('#anoValidadeCartao').prop('disabled', false);
                        }else{
                            $('#selectBandeira').hide();
                            document.getElementById("numeroCartao").style.backgroundImage = "url('')";
                            $('#label-numeroCartao').show();
                            $('#codigoCartao').prop('disabled', true);
                            $('#nomeCartao').prop('disabled', true);
                            $('#mesValidadeCartao').prop('disabled', true);
                            $('#anoValidadeCartao').prop('disabled', true);
                        }
                    });
            }else{
                $('#label-numeroCartao').show();
                $('#codigoCartao').prop('disabled', true);
                $('#nomeCartao').prop('disabled', true);
                $('#mesValidadeCartao').prop('disabled', true);
                $('#anoValidadeCartao').prop('disabled', true);
            }
        });

        $('#btnSalvarAtualizaCartao').click(function(e){
            e.preventDefault();
            const dataAtual = new Date();
            const anoAtual = dataAtual.getFullYear();
            const anoDois = Number(anoAtual.toString().slice(-2));
            
            var bandeira = $('#bandeiraCartao').text();

            if (numBandeiraCartaoCredito == 0){
                valueBandeira = $('#bandeiraCartao').val();
            }else{
                valueBandeira = numBandeiraCartaoCredito;
            }

            var numeroCartao = $('#numeroCartao').val();
            var codigoCartao = $('#codigoCartao').val();
            var nomeCartao = $('#nomeCartao').val();
            var mesCartao = $('#mesValidadeCartao').val();
            var anoCartao = $('#anoValidadeCartao').val();
            var idCot = idCotacao;


            botao = $('#btnSalvarAtualizaCartao');
            botao.html('<i class="fa fa-spinner fa-spin"></i>');
            botao.attr('disabled', true);

            if ((bandeira == "Amex" || bandeira == "Diners")) {
                if (bandeira == "Amex" && codigoCartao.length != 4) {
                    alert('Código de segurança inválido!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (bandeira == "Amex" && numeroCartao.length != 15) {
                    alert('Número do cartão inválido!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (bandeira == "Diners" && numeroCartao.length != 14) {
                    alert('Número do cartão inválido!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                }else if (bandeira == "Diners" && codigoCartao.length != 3) {
                    alert('Código de segurança inválido!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (nomeCartao.length < 2) {
                    alert('Preencha o nome corretamente!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (mesCartao > 12 || mesCartao < 1) {
                    alert('Mês de validade inválido!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (anoCartao < anoDois) {
                    alert('Ano de validade inválido!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                }else{
                    $.ajax({
                        method: "POST",
                        url: "Pedidos/validarCartaoCredito",
                        data: { numero: numeroCartao }
                    })
                    .done(function(result) {
                        if (result == 0) {
                            $.ajax({
                                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/atualizaCartaoCredito') ?>",
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    Bandeira: valueBandeira,
                                    Numero_Cartao: numeroCartao,
                                    Cod_Seguranca: codigoCartao,
                                    Nome_Impresso_Cartao: nomeCartao,
                                    Validade_Cartao_Mes: mesCartao,
                                    Validade_Cartao_Ano: anoCartao,
                                    id_Cotacao: idCot
                                },
                                success: function(data){
                                    if(data.dados.Status == 200){
                                        botao.html('<a class=""></a> Salvar');
                                        botao.attr('disabled', false);
                                        alert(data.dados.Message);
                                        $('#numeroCartao').val('');
                                        $('#codigoCartao').val('');
                                        $('#nomeCartao').val('');
                                        $('#mesValidadeCartao').val('');
                                        $('#anoValidadeCartao').val('');
                                        $('#bandeiraCartao').val(0);
                                        document.getElementById("numeroCartao").style.backgroundImage = "url('')";
                                    }
                                    else{
                                        botao.html('<a class=""></a> Salvar');
                                        botao.attr('disabled', false);
                                        alert(data.dados.Message);
                                    }
                                }
                            });
                        } else {
                            alert('Cartão de crédito inválido!');
                            botao.html('<a class=""></a> Salvar');
                            botao.attr('disabled', false);
                        }
                    })
                    .fail(function() {
                        alert('Ocorrou um erro durante a validação do cartão!');
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                    });
                    
                }
            }else{
                if (nomeCartao.length < 2) {
                    alert('Preencha o nome corretamente!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (numeroCartao.length != 16) {
                    alert('Preencha o campo número do cartão corretamente!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (codigoCartao.length != 3) {
                    alert('Preencha o campo código de segurança corretamente!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (mesCartao > 12 || mesCartao < 1) {
                    alert('Preencha o campo mês de validade corretamente!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                } else if (anoCartao < anoDois) {
                    alert('Preencha o campo ano de validade corretamente!');
                    botao.html('<a class=""></a> Salvar');
                    botao.attr('disabled', false);
                }else{
                    $.ajax({
                        method: "POST",
                        url: "Pedidos/validarCartaoCredito",
                        data: { numero: numeroCartao }
                    })
                    .done(function(result) {
                            if (result == 0) {
                                $.ajax({
                                    url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/atualizaCartaoCredito') ?>",
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {
                                        Bandeira: valueBandeira,
                                        Numero_Cartao: numeroCartao,
                                        Cod_Seguranca: codigoCartao,
                                        Nome_Impresso_Cartao: nomeCartao,
                                        Validade_Cartao_Mes: mesCartao,
                                        Validade_Cartao_Ano: anoCartao,
                                        id_Cotacao: idCot
                                    },
                                    success: function(data){
                                        if(data.dados.Status == 200){
                                            botao.html('<a class=""></a> Salvar');
                                            botao.attr('disabled', false);
                                            alert(data.dados.Message);
                                            $('#numeroCartao').val('');
                                            $('#codigoCartao').val('');
                                            $('#nomeCartao').val('');
                                            $('#mesValidadeCartao').val('');
                                            $('#anoValidadeCartao').val('');
                                            $('#bandeiraCartao').val(0);
                                            document.getElementById("numeroCartao").style.backgroundImage = "url('')";
                                        }
                                        else{
                                            botao.html('<a class=""></a> Salvar');
                                            botao.attr('disabled', false);
                                            alert(data.dados.Message);
                                        }
                                    },
                                });
                            } else {
                                alert('Cartão de crédito inválido!');
                                botao.html('<a class=""></a> Salvar');
                                botao.attr('disabled', false);
                            }
                    })
                    .fail(function() {
                        alert('Ocorrou um erro durante a validação do cartão!');
                        botao.html('<a class=""></a> Salvar');
                        botao.attr('disabled', false);
                    });
                }
            }
        });
        
        tableArquivoForm = $("#tableArquivosForm").DataTable({
			responsive: false,
			ordering: false,
			paging: false,
			searching: false,
			info: true,
			language: lang.datatable,
			deferRender: false,
			lengthChange: false
		});
        var contArquivos = 0 ;

		$('#AddArquivoForm').on('click', function () {
			let arquivo = $('#filesForm')[0];
            let result = arquivo.files;

            
            for (i = 0; i < result.length; i++) {
				let file = result[i];
				tableArquivoForm.row.add([
					'<input type="file" style="visibility:hidden;" name="ListaArquivosForm" id="arquivo'+contArquivos+'"/>'+file.name,
					"<a class='remove btn btn-danger'>Remover <i class='fa fa-trash'></i></button>"
				]).draw(false);

				var dataTransfer = new DataTransfer();
				dataTransfer.items.add(file);
				$('#arquivo'+contArquivos)[0].files = dataTransfer.files;
				contArquivos++;
            }
            
            $("#filesForm").val("");
            $('#filesForm').prev('label').text("Selecione um Arquivo");
		});

		$('#tableArquivosForm tbody').on( 'click', '.remove', function () {
			tableArquivoForm
				.row( $(this).parents('tr') )
				.remove()
				.draw();
		} );

        $('input[type=file]').change(function(){
            var t = $(this).val();
			var labelText = 'Arquivo : ' + t.substr(12, t.length);
			$(this).prev('label').text(labelText);
		});
    });
</script>