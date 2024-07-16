<!-- MONTA O LAUDO DE VITIMISMO -->
<div class="col-md-12" id="pagina_laudo_vitimismo" style="margin-top: 500px; display: none;">    
    <div class="col-md-12" id="print-content_vitimismo" style="display: none">

        <!-- CAPA DO LAUDO -->
        <section>
            <div class="col-md-12 secao">
                <div class="col-md-12 spaceDiv" style="margin-top: 20px;">
                    <img class="logoCliente" style="width: 300px;">            
                </div>
                <div class="col-md-12">
                    <div class="col-md-6"></div>
                    <div class="col-md-6" style="margin-top:150px;">
                        <div class="loadDados" style="float: left;" id="qrcode_laudo_vitimismo"></div>
                        <div style="margin-top: 15px;">
                            <h4 class="loadDados" id="titulo_laudo_vitimismo"></h4>
                            <div class="loadDados nome_laudo_vitimismo"></div>
                            <div class="loadDados" id="data_consulta_laudo_vitimismo"></div>
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
                <div class="col-md-12 spaceDiv" style="margin-top: 50px;">
                    <center><h2 class="formatH2"><?=strtoupper(lang('perfil_consultado'))?></h2></center>
                    <hr>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=lang('cpf')?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span id="cpf_laudo_vitimismo" class="loadDados"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=lang('nome')?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span class="loadDados nome_laudo_vitimismo"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=lang('registro_geral')?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span id="rg_laudo_vitimismo" class="loadDados"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=lang('tipo_profissional')?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span id="tipo_profissional_laudo_vitimismo" class="loadDados"></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">            
                    <div class="alert alert-success" role="alert" style="height: 65px;" id="alertaStatusFonteVitimologia">
                        <div style="padding: 5px; margin-right: 5px; float: left;">
                            <i class="fa fa-exclamation-circle" id="iconAlertaStatusFonteVitimologia" style="font-size: 30px;"></i>
                        </div>
                        <div>
                            <div><?=strtoupper(lang('status_fonte'))?></div>
                            <p id="situacao_orgao_laudo_vitimismo" class="loadDados"></p>                    
                        </div>
                    </div>
                    
                    <div class="alert alert-dark" role="alert" style="height: 65px;" id="alertaStatusConsultaVitimologia">
                        <div style="padding: 5px; margin-right: 5px; float: left;">
                            <i class="fa fa-exclamation-circle" id="iconAlertaStatusConsultaVitimologia" style="font-size: 30px;"></i>
                        </div>
                        <div>
                            <div><?=strtoupper(lang('status_consulta'))?></div>
                            <p id="situacao_consulta_laudo_vitimismo" class="loadDados"></p>                    
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CAPA DO RESUMO -->
        <section>
            <div class="col-md-12 detalhes_laudo_processos" style="display: none">            
                <div class="quebra-pagina"></div>

                <div class="col-md-12" style="text-align:center; margin-top: 20px; padding-left: 50px; padding-right: 50px;">
                    <h2 class="formatH2"><?=strtoupper(lang('detalhes'))?></h2>
                    <p><?=lang('msg_detalhes_processos')?></p>
                </div>            
            </div>
        </section>

        <!-- RESUMO DA CONSULTA -->
        <section>
            <div class="col-md-12 detalhes_laudo_processos" style="display: none">
                <div class="quebra-pagina"></div>

                <div class="col-md-12">
                    <center><h2 class="formatH2"><?=strtoupper(lang('detalhes'))?></h2></center>
                    <spam class="loadDados"></spam>
                    <div role="tablist" aria-multiselectable="true">
                        <span id="resultado_detalhes_processos"></span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>