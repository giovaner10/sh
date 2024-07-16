<!-- MONTA O LAUDO DE VITIMISMO -->
<div class="col-md-12" id="pagina_laudo_resumido" style="margin-top: 500px; display: none;">    
    <div class="col-md-12" id="print-content_resumido" style="display: none">

        <!-- CAPA DO LAUDO -->
        <section>
            <div class="col-md-12 secao">
                <div class="col-md-12 spaceDiv" style="margin-top: 20px;">
                    <img class="logoCliente" style="width: 300px;">            
                </div>
                <div class="col-md-12">
                    <div class="col-md-6"></div>
                    <div class="col-md-6" style="margin-top:150px;">
                        <div class="loadDados" style="float: left;" id="qrcode_laudo_resumido"></div>
                        <div style="margin-top: 15px;">
                            <h4 class="loadDados" id="titulo_laudo_resumido"></h4>
                            <div class="loadDados nome_laudo_resumido"></div>
                            <div class="loadDados" id="data_consulta_laudo_resumido"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="quebra-pagina"></div>
        
        <!-- DADOS DO PROFISSIONAL -->
        <section>
            <div class="col-md-12">
                <h4 style="margin-top:20px; text-align:center;"><?=strtoupper(lang('resultado'))?>: <span id="resultadoLaudoResumido"></span></h4>
                <div class="col-md-12 spaceDiv" style="margin-top: 50px;">
                    <h2 class="formatH2"><?=strtoupper(lang('perfil_consultado'))?></h2>
                    <hr>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=strtoupper(lang('cpf'))?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span id="cpf_laudo_resumido" class="loadDados"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=strtoupper(lang('nome'))?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span class="loadDados nome_laudo_resumido"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=strtoupper(lang('registro_geral'))?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span id="rg_laudo_resumido" class="loadDados"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=strtoupper(lang('tipo_profissional'))?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span id="tipo_profissional_laudo_resumido" class="loadDados"></span>
                        </div>
                    </div>
                </div>                
                <div class="col-md-12 spaceDiv" style="margin-top: 50px;">
                    <h2 class="formatH2"><?=strtoupper(lang('dados_laudo'))?></h2>
                    <hr>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=strtoupper(lang('cliente'))?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span id="cliente_laudo_resumido" class="loadDados"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=lang('usuario_uper')?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span id="nomeUsuario_laudo_resumido" class="loadDados"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=strtoupper(lang('protocolo'))?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span id="protocolo_laudo_resumido" class="loadDados"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=strtoupper(lang('score'))?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span id="score_laudo_resumido" class="loadDados"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=strtoupper(lang('data_consulta'))?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span class="loadDados data_consulta_laudo_resumido"></span>
                        </div>
                    </div>
                    <div class="row col-md-12 spaceElement">
                        <div class="col-md-4 semPaddingLeft floatLeft">
                            <strong><?=strtoupper(lang('vencimento'))?></strong>
                        </div>
                        <div class="col-md-8 semPaddingLeft">
                            <span id="vencimento_laudo_resumido" class="loadDados"></span>
                        </div>
                    </div>
                </div>
                <div>
                    <h2>&nbsp;</h2>
                </div>
            </div>
        </section>  
    </div>
</div>