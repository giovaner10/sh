
<!-- MONTA O LAUDO DA CONSULTA -->
<div class="col-md-12" style="margin-top: 500px">    
    <div class="col-md-12" id="print-content" style="display: none">

        <!-- CAPA DO LAUDO -->
        <section>
            <div class="col-md-12 secao">
                <div class="col-md-12 spaceDiv" style="margin-top: 20px;">
                    <img class="logoCliente" style="width: 300px;">            
                </div>
                <div class="col-md-12">
                    <div class="col-md-6"></div>
                    <div class="col-md-6" style="margin-top:150px;">
                        <div class="loadDados" style="float: left;" id="qrcode_laudo"></div>
                        <div style="margin-top: 15px;">
                            <h4 class="loadDados" id="titulo_laudo"></h4>
                            <div class="loadDados nome_laudo"></div>
                            <div class="loadDados" id="data_consulta_laudo"></div>
                        </div>
                    </div>
                </div>
            </div>        
        </section>

        <div class="quebra-pagina"></div>        
        
        <!-- DADOS DO PROFISSIONAL -->
        <section>
            <div class="col-md-12">
                <h4 style="margin-top:20px; text-align:center;"><?=strtoupper(lang('resultado'))?></h4>
                <div class="col-md-12 spaceDiv" style="margin-top: 20px;">
                    <center><h2 class="formatH2"><?=strtoupper(lang('perfil_consultado'))?></h2></center>
                    <hr>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=lang('cpf')?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span id="cpf_laudo" class="loadDados"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=lang('nome')?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span class="loadDados nome_laudo"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=lang('registro_geral')?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span id="rg_laudo" class="loadDados"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=lang('tipo_profissional')?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span id="tipo_profissional_laudo" class="loadDados"></span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">                        
                    <div class="alert alert-danger" role="alert" style="height: 65px; display: none;" id="div_alertas_laudo">
                        <div style="padding: 5px; margin-right: 5px; float: left;">
                            <i class="fa fa-times-circle" style="font-size: 30px;"></i>
                        </div>
                        <div>
                            <div><?=strtoupper(lang('alertas'))?></div>
                            <p id="alertas_laudo" class="loadDados"></p>                    
                        </div>
                    </div>

                    <div class="alert alert-success" role="alert" style="height: 65px;"  id="alertaStatusFonte">
                        <div style="padding: 5px; margin-right: 5px; float: left;">
                            <i class="fa fa-exclamation-circle" id="iconAlertaStatusFonte" style="font-size: 30px;"></i>
                        </div>
                        <div>
                            <div><?=strtoupper(lang('fontes_indisponiveis'))?></div>
                            <p id="fontes_indisponiveis_laudo" class="loadDados"></p>                    
                        </div>
                    </div>
                    
                    <div class="alert alert-success" role="alert" style="height: 65px;" id="alertaStatusConsulta">
                        <div style="padding: 5px; margin-right: 5px; float: left;">
                            <i class="fa fa-exclamation-circle" id="iconAlertaStatusConsulta" style="font-size: 30px;"></i>
                        </div>
                        <div>
                            <div><?=strtoupper(lang('fontes_irregulares'))?></div>
                            <p id="fontes_irregulares_laudo" class="loadDados"></p>                    
                        </div>
                    </div>

                    <div class="quebra-pagina"></div>
                </div>               

            </div>
            
            <div class="col-md-12" style="margin-top: 20px;">
                <div class="col-md-12">
                    <h2 class="formatH2" style="text-align: center;"><?=lang('informacoes_laudo')?></h2>
                    <div class="col-md-8 semPaddingLeft">
                        <div class="col-md-12 spaceElement semPaddingLeft">
                            <div class="caixaIcon">
                                <i class="fa fa-hashtag" style="font-size: 30px;"></i>
                            </div>
                            <div>
                                <div><strong><?=lang('protocolo')?></strong></div>
                                <div class="loadDados protocolo_laudo" class="forcarQuebraLinha"></div>
                            </div>               
                        </div>                 
                        <div class="col-md-12 spaceElement semPaddingLeft">
                            <div class="caixaIcon">
                                <i class="fa fa-tachometer" style="font-size: 30px;"></i>
                            </div>
                            <div>
                                <div><strong><?=lang('indicadores')?></strong></div>
                                <div><?=lang('msg_indicadores')?></div>
                            </div>                 
                        </div>                 
                        <div class="col-md-12 spaceElement semPaddingLeft">
                            <div class="caixaIcon">
                                <i class="fa fa-file" style="font-size: 30px;"></i>
                            </div>
                            <div>
                                <div><strong><?=lang('comprovantes')?></strong></div>
                                <div><?=lang('msg_comprovantes2')?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="spaceElement semPaddingLeft">
                            <label><strong><?=lang('situacao')?>: </strong></label>
                            <span id="ponto_corte_laudo" class="loadDados label"></span>
                        </div>                
                        <div class="spaceElement semPaddingLeft">
                            <label><strong><?=lang('score')?>: </strong></label>
                            <span id="score_laudo" class="loadDados"></span>
                        </div>                
                        <div class="spaceElement semPaddingLeft">
                            <label><strong><?=lang('consultas_realizadas')?>: </strong></label>
                            <span id="qtdConsultas_laudo" class="loadDados"></span>
                        </div>
                        <div class="spaceElement semPaddingLeft">
                            <label><strong><?=lang('fontes_consultadas')?>: </strong></label>
                            <span id="fontesConsultadas_laudo" class="loadDados"></span>
                        </div>
                    </div> 
                    
                    <div class="quebra-pagina"></div>
                </div>
            </div>
        </section>

        <!-- INDICADORES -->
        <section>
            <div class="col-md-12" style="margin-top: 20px;">
                <div class="col-md-12">
                    <center><h2 class="formatH2"><?=strtoupper(lang('indicadores'))?></h2></center>
                    <div id="indicadores_laudo" class="loadDados"></div>
                </div>

                <div class="quebra-pagina"></div>
            </div>        
        </section>
        
        <!-- CAPA DO RESUMO -->
        <section>
            <div class="col-md-12" style="margin-top: 20px;">
                <div class="col-md-12" style="text-align:center;">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <h2 class="formatH2"><?=strtoupper(lang('resumo'))?></h2>
                        <p><?=lang('msg_resumo_laudo')?></p>
                    </div>
                    <div class="col-md-2"></div>
                </div>
                
                <div class="quebra-pagina"></div>
            </div>
        </section>

        <!-- RESUMO DAS CONSULTAS -->
        <section>
            <div class="col-md-12">
                <div class="col-md-12">
                    <center><h2 class="formatH2"><?=strtoupper(lang('resumo'))?></h2></center>
                    <hr>
                    <spam class="loadDados"></spam>

                    <div role="tablist" aria-multiselectable="true">
                        <!-- RESUMO CONSULTA CPF -->
                        <div class="panel panel-default bordaPainel" id="resumo_laudo_cpf" style="display:none">
                            <div class="panel-heading bordaPainelBottom" role="tab" style="height: 40px;">
                                <div class="col-md-8" style="padding-left: 0px!important;">
                                    <h4 class="panel-title">
                                        <i class="fa fa-university" aria-hidden="true"></i>
                                        <?=lang('situacao_cadastral_cpf')?> - Receita Federal
                                    </h4>
                                </div>
                                <div class="col-md-4" id="status_cpf" style="text-align: right;"></div>
                            </div>
                            <div class="panel-collapse collapse in" role="tabpanel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row" id="div_msg_laudo_cpf" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_laudo_cpf"></span>
                                                </div>
                                            </div>
                                            <div class="row" id="div_resultado_laudo_cpf">
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('nome') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="nome_laudo_cpf"></span>
                                                    </div>                                                            
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('data_inscricao') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="data_inscricao_laudo_cpf"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('cpf') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="cpf_laudo_cpf"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('data_nascimento') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="data_nascimento_laudo_cpf"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('situacao') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="situacao_cadastral_laudo_cpf"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('consta_obito') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="consta_obito_laudo_cpf"></span>
                                                    </div>
                                                </div>
                                            </div>                                                                                       
                                        </div>                                                
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RESUMO CONSULTA ANTECEDENTES CRIMINAIS -->
                        <div class="panel panel-default bordaPainel" id="resumo_laudo_antecedentes" style="display:none">
                            <div class="panel-heading bordaPainelBottom" role="tab" style="height: 40px;">
                                <div class="col-md-8" style="padding-left: 0px!important;">
                                    <h4 class="panel-title">
                                        <i class="fa fa-shield" aria-hidden="true"></i>
                                        <?=lang('antecedentes_criminais')?> - Polícia Federal
                                    </h4>
                                </div>
                                <div class="col-md-4" id="status_antecedentes" style="text-align: right;"></div>
                            </div>
                            <div class="panel-collapse collapse in" role="tabpanel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row" id="div_msg_laudo_antecedentes" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_laudo_antecedentes"></span>
                                                </div>
                                            </div>
                                            <div class="row" id="div_resultado_laudo_antecedentes">
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('pergunta_possui_antecedentes') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="possui_anteced_criminais_laudo_antecedentes"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('numero_certidao') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="numero_certidao_laudo_antecedentes"></span>
                                                    </div>
                                                </div>
                                            </div>                                                                                       
                                        </div>                                                
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RESUMO CONSULTA PROCESSOS -->
                        <div class="panel panel-default bordaPainel" id="resumo_laudo_processos" style="display:none">
                            <div class="panel-heading bordaPainelBottom" role="tab" style="height: 40px;">
                                <div class="col-md-8" style="padding-left: 0px!important;">
                                    <h4 class="panel-title">
                                        <i class="fa fa-balance-scale" aria-hidden="true"></i>
                                        <?=lang('processos')?> - Tribunais de Justiça
                                    </h4>
                                </div>
                                <div class="col-md-4" id="status_processos" style="text-align: right;"></div>
                            </div>
                            <div class="panel-collapse collapse in" role="tabpanel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row" id="div_msg_laudo_processos" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_laudo_processos"></span>
                                                </div>
                                            </div>
                                            <div class="row" id="div_resultado_laudo_processos">
                                                <div class= "row col-md-12">
                                                    <div class="col-md-12">
                                                        <span id="resultado_resumo_processos"></span>
                                                    </div>
                                                </div>
                                            </div>                                                                                       
                                        </div>                                                
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RESUMO CONSULTA MANDADOS DE PRISAO -->
                        <div class="panel panel-default bordaPainel" id="resumo_laudo_mandados" style="display:none">
                            <div class="panel-heading bordaPainelBottom" role="tab" style="height: 40px;">
                                <div class="col-md-8" style="padding-left: 0px!important;">
                                    <h4 class="panel-title">
                                        <i class="fa fa-balance-scale" aria-hidden="true"></i>
                                        <?=lang('banco_mandados_prisao')?> - CNJ
                                    </h4>
                                </div>
                                <div class="col-md-4" id="status_mandados" style="text-align: right;"></div>
                            </div>
                            <div class="panel-collapse collapse in" role="tabpanel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row" id="div_msg_laudo_mandados" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_laudo_mandados"></span>
                                                </div>
                                            </div>
                                            <div class="row" id="div_resultado_laudo_mandados">
                                                <div class= "row col-md-12">
                                                    <div class="col-md-12">
                                                        <span id="resultado_resumo_mandados"></span>
                                                    </div>
                                                </div>
                                            </div>                                                                                       
                                        </div>                                                
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RESUMO CONSULTA CNH -->
                        <div class="panel panel-default bordaPainel" id="resumo_laudo_cnh" style="display:none; margin-top:15px;" >
                            <div class="panel-heading bordaPainelBottom" role="tab" style="height: 40px;">
                                <div class="col-md-8" style="padding-left: 0px!important;">
                                    <h4 class="panel-title">
                                        <i class="fa fa-university" aria-hidden="true"></i>
                                        <?=lang('situacao_cnh')?> - DENATRAM
                                    </h4>
                                </div>
                                <div class="col-md-4" id="status_cnh" style="text-align: right;"></div>
                            </div>                            

                            <div class="panel-collapse collapse in" role="tabpanel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row" id="div_msg_laudo_cnh" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_laudo_cnh"></span>
                                                </div>
                                            </div>
                                            <div class="row" id="div_resultado_laudo_cnh">
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('numero_registro') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="numero_registro_laudo_cnh"></span>
                                                    </div>                                                            
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('data_emissao') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="data_emissao_laudo_cnh"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('data_validade') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="data_validade_laudo_cnh"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('nome_condutor') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="nome_condutor_laudo_cnh"></span>
                                                    </div>
                                                </div>                                                        
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('situacao') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="situacao_laudo_cnh"></span>
                                                    </div>
                                                </div>
                                            </div>                                                                                       
                                        </div>                                                
                                    </div>
                                </div>
                            </div>                                                 
                        </div>

                        
                        <!-- RESUMO CONSULTA VEICULO -->             
                        <div class="panel panel-default bordaPainel" id="resumo_laudo_veiculo" style="margin-top:15px; display:none">
                            <div class="panel-heading bordaPainelBottom" role="tab" style="height: 40px;">
                                <div class="col-md-8" style="padding-left: 0px!important;">
                                    <h4 class="panel-title">
                                        <i class="fa fa-car" aria-hidden="true"></i>
                                        <?=lang('situacao_veiculo')?> - DENATRAM
                                    </h4>
                                </div>
                                <div class="col-md-4" id="status_veiculo" style="text-align: right;"></div>
                            </div>
                            <div class="panel-collapse collapse in" role="tabpanel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row" id="div_msg_laudo_veiculo" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_laudo_veiculo"></span>
                                                </div>
                                            </div>
                                            <div class="row" id="div_resultado_laudo_veiculo">
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('placa') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="placa_laudo_veiculo"></span>
                                                    </div>                                                            
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('codigo_renavam') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="codigo_renavam_laudo_veiculo"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('chassi') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="chassi_laudo_veiculo"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('identificacao_proprietario') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="numero_identificacao_proprietario_laudo_veiculo"></span>
                                                    </div>
                                                </div>                                                        
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('nome_proprietario') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="nome_proprietario_laudo_veiculo"></span>
                                                    </div>
                                                </div>                                                        
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('situacao') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="situacao_laudo_veiculo"></span>
                                                    </div>
                                                    
                                                </div>
                                            </div>                                                                                       
                                        </div>                                                
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- RESUMO CONSULTA CARRETA -->
                        <div class="panel panel-default bordaPainel" id="resumo_laudo_carreta" style="display:none">
                            <div class="panel-heading bordaPainelBottom" role="tab" style="height: 40px;">
                                <div class="col-md-8" style="padding-left: 0px!important;">
                                    <h4 class="panel-title">
                                        <i class="fa fa-car" aria-hidden="true"></i>
                                        <?=lang('situacao_carreta')?> - DENATRAM
                                    </h4>
                                </div>
                                <div class="col-md-4" id="status_carreta" style="text-align: right;"></div>
                            </div>
                            <div class="panel-collapse collapse in" role="tabpanel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row" id="div_msg_laudo_carreta" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_laudo_carreta"></span>
                                                </div>
                                            </div>
                                            <div class="row" id="div_resultado_laudo_carreta">
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('placa') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="placa_laudo_carreta"></span>
                                                    </div>                                                            
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('codigo_renavam') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="codigo_renavam_laudo_carreta"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('chassi') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="chassi_laudo_carreta"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('identificacao_proprietario') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="numero_identificacao_proprietario_laudo_carreta"></span>
                                                    </div>
                                                </div>                                                        
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('nome_proprietario') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="nome_proprietario_laudo_carreta"></span>
                                                    </div>
                                                </div>                                                        
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('situacao') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="situacao_laudo_carreta"></span>
                                                    </div>
                                                </div>                                                
                                            </div>                                                                                       
                                        </div>                                                
                                    </div>
                                </div>
                            </div>                            
                        </div>

                        <!-- RESUMO CONSULTA SEGUNDA CARRETA -->
                        <div class="panel panel-default bordaPainel" id="resumo_laudo_segunda_carreta" style="display:none">
                            <div class="panel-heading bordaPainelBottom" role="tab" style="height: 40px;">
                                <div class="col-md-8 floatLeft" style="padding-left: 0px!important;">
                                    <h6 class="panel-title">
                                        <i class="fa fa-car" aria-hidden="true"></i>
                                        <?=lang('situacao_segunda_carreta')?> - DENATRAN
                                    </h6>
                                </div>
                                <div class="col-md-4 floatLeft" id="status_segunda_carreta" style="text-align: right;"></div>
                            </div>
                            <div class="panel-collapse collapse show" role="tabpanel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row" id="div_msg_laudo_segunda_carreta" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_laudo_segunda_carreta"></span>
                                                </div>
                                            </div>
                                            <div class="row" id="div_resultado_laudo_segunda_carreta">
                                                <div class="row col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('placa') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="placa_laudo_segunda_carreta"></span>
                                                    </div>                                                            
                                                </div>
                                                <div class="row col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('codigo_renavam') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="codigo_renavam_laudo_segunda_carreta"></span>
                                                    </div>
                                                </div>
                                                <div class="row col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('chassi') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="chassi_laudo_segunda_carreta"></span>
                                                    </div>
                                                </div>
                                                <div class="row col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('identificacao_proprietario') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="numero_identificacao_proprietario_laudo_segunda_carreta"></span>
                                                    </div>
                                                </div>                                                        
                                                <div class="row col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('nome_proprietario') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="nome_proprietario_laudo_segunda_carreta"></span>
                                                    </div>
                                                </div>                                                        
                                                <div class="row col-md-12">
                                                    <div class="col-md-6">
                                                        <strong><p><?= lang('situacao') ?></p></strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span id="situacao_laudo_segunda_carreta"></span>
                                                    </div>
                                                </div>                                                
                                            </div>                                                                                       
                                        </div>                                                
                                    </div>
                                </div>
                            </div>                            
                        </div>

                        <!-- RESUMO CONSULTA DEBITO FINANCEIRO -->
                        <div class="panel panel-default bordaPainel" id="resumo_laudo_debitos" style="display:none">
                            <div class="panel-heading bordaPainelBottom" role="tab" style="height: 40px;">
                                <div class="col-md-8" style="padding-left: 0px!important;">
                                    <h4 class="panel-title">
                                        <i class="fa fa-money" aria-hidden="true"></i>
                                        <?=lang('debitos_financeiros')?> - QUOD
                                    </h4>
                                </div>
                                <div class="col-md-4" id="status_debitos" style="text-align: right;"></div>
                            </div>
                            <div class="panel-collapse collapse in" role="tabpanel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row" id="div_msg_laudo_debitos" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_laudo_debitos"></span>
                                                </div>
                                            </div>
                                            <div class="row" id="div_resultado_laudo_debitos">
                                                <div class= "row col-md-12">
                                                    <div class="col-md-12">
                                                        <span id="resultado_resumo_debitos"></span>
                                                    </div>
                                                </div>
                                            </div>                                                                                       
                                        </div>                                                
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="quebra-pagina"></div>
                    </div>
                </div>
            </div>
        </section>

       <!-- CAPA DO COMPROVANTES -->
        <section>
            <div class="col-md-12">
                <div class="col-md-12" style="text-align:center; margin-top: 20px;">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <h2 class="formatH2"><?=strtoupper(lang('comprovantes'))?></h2>
                        <p><?=lang('msg_comprovantes')?></p>
                    </div>
                    <div class="col-md-2"></div>
                </div>                                   
                
                <div class="col-md-12">
                    <div id="comprovantes" class="loadDados" style="margin-top: 120px"></div>
                </div>
            </div>            
        </section>
    </div>
</div>