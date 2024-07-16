<!-- Modal Visualizar Consulta -->
<div id="modalVisualizarConsultar" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1">
    <div class="modal-dialog modal-lg" style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h2 id="tituloVisualizarPerfil"></h2>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#aba_profissional"><?= lang('profissional') ?></a></li>
                    <li><a data-toggle="tab" id="menu_aba_proprietario" href="#aba_proprietario"><?= lang('proprietario') ?></a></li>    
                    <li><a data-toggle="tab" id="menu_aba_veiculo" href="#aba_veiculo"><?= lang('veiculo') ?></a></li>    
                    <li><a data-toggle="tab" id="menu_aba_carreta" href="#aba_carreta"><?= lang('carreta') ?></a></li>
                    <li><a data-toggle="tab" id="menu_aba_segunda_carreta" href="#aba_segunda_carreta"><?= lang('segunda_carreta') ?></a></li>
                </ul>

                <div class="tab-content">
                    <!-- ABA DADOS PROFISSIONAL -->
                    <div id="aba_profissional" class="tab-pane fade in active" style="padding: 10px">
                        <div class="row configBox">
                            <h4><?= lang('dados_fornecidos_pelo_cliente') ?></h4>
                            <hr>
                            <label><?=lang('dados_pessoais')?></label>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('nome') ?>: </b></p><span id="nome"></span>                                
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('cpf') ?>: </b></p><span id="cpf"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b>RG: </b></p><span id="rg"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('estado') ?>: </b></p><span id="uf_rg"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('data_emissao') ?>: </b></p><span id="data_emissao_rg"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('nome_pai') ?>: </b></p><span id="nome_pai"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('nome_mae') ?>: </b></p><span id="nome_mae"></span>
                                </div>
                            </div>

                            <hr>
                            <label><?=lang('dados_cnh')?></label>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('numero_registro') ?>: </b></p><span id="numero_registro"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('uf') ?> <?= lang('emissor') ?>: </b></p><span id="uf_cnh"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('data_1_habilitacao') ?>: </b></p><span id="data_primeira_habilitacao"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('vencimento') ?>: </b></p><span id="vencimento_cnh"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('categoria') ?>: </b></p><span id="categoria_cnh"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('numero_seguranca_cnh') ?>: </b></p><span id="numero_seguranca_cnh"></span>
                                </div>
                            </div>

                            <hr>
                            <label><?=lang('dados_endereco')?></label>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('cep') ?>: </b></p><span id="cep"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('logradouro') ?>: </b></p><span id="endereco"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('bairro') ?>: </b></p><span id="bairro"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('cidade') ?>: </b></p><span id="cidade"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('uf') ?>: </b></p><span id="uf"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('numero') ?>: </b></p><span id="numero"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('complemento') ?>: </b></p><span id="complemento"></span>
                                </div>
                            </div>
                            
                            <hr>
                            <label><?=lang('dados_contato')?></label>
                            <div class="row">
                                <div class="col-md-4">
                                    <p><b><?= lang('telefone') ?> <?= lang('residencial') ?>: </b></p><span id="telefone_residencial"></span>
                                </div>
                                <div class="col-md-4">
                                    <p><b><?= lang('contato_residencial') ?>: </b></p><span id="contato_residencial"></span>
                                </div>                            
                            </div>
                            <div class="row">
                            <div class="col-md-4">
                                    <p><b><?= lang('telefone') ?> <?= lang('comercial') ?>: </b></p><span id="telefone_comercial"></span>
                                </div>
                                <div class="col-md-4">
                                    <p><b><?= lang('contato_comercial') ?>: </b></p><span id="contato_comercial"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row configBox" style="margin-top: 10px;">
                            <h4><?= lang('dados_fornecidos_pelas_apis') ?></h4>
                            <hr>
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <!-- Resposta da consulta do CPF -->
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading1">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                                <span class="glyphicon glyphicon-minus"></span>
                                                <?=lang('consulta_cpf')?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading1">
                                        <div class="panel-body">
                                            <div class="row detalhes_consulta" id="div_msg_info_cpf" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_info_cpf"></span>
                                                </div>
                                            </div>
                                            <div class="row detalhes_consulta" id="div_resultado_info_cpf">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('nome') ?>: </b></p><span id="nome_consulta_cpf"></span>                                
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('cpf') ?>: </b></p><span id="cpf_consulta_cpf"></span>
                                                        </div>                                                        
                                                        <div class="col-md-4">
                                                            <p><b><?=lang('data_nascimento')?>: </b></p><span id="data_nascimento_consulta_cpf"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('situacao_cadastral') ?>: </b></p><span id="situacao_cadastral_consulta_cpf"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('data_inscricao') ?>: </b></p><span id="data_inscricao_consulta_cpf"></span>
                                                        </div>                                                       
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('consta_obito') ?>: </b></p><span id="consta_obito_consulta_cpf"></span>
                                                        </div>
                                                    </div>                                                       
                                                    <div class="row">                                                        
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('visualizar_arquivo') ?>: </b></p><span id="path_file_consulta_cpf"></span>
                                                        </div>
                                                    </div>                                    
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Resposta da consulta do CNH -->
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading2">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                            <span class="glyphicon glyphicon-plus"></span>
                                            <?=lang('consulta_cnh')?>
                                        </a>
                                    </h4>
                                    </div>
                                    <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
                                        <div class="panel-body">
                                            <div class="row detalhes_consulta" id="div_msg_info_cnh" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_info_cnh"></span>
                                                </div>
                                            </div>
                                            <div class="row detalhes_consulta" id="div_resultado_info_cnh">
                                                <div class="col-md-12">
                                                    <div class="row">                                                        
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('nome_condutor') ?>: </b></p><span id="nome_condutor_consulta_cnh"></span>                                
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('numero_registro') ?>: </b></p><span id="numero_registro_consulta_cnh"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?=lang('numero_seguranca')?>: </b></p><span id="numero_seguranca_consulta_cnh"></span>
                                                        </div>                                
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('data_validade') ?>: </b></p><span id="data_validade_consulta_cnh"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('data_emissao') ?>: </b></p><span id="data_emissao_consulta_cnh"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('categoria') ?>: </b></p><span id="categoria_consulta_cnh"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">                                                        
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('numero_formulario') ?>: </b></p><span id="numero_formulario_cnh_consulta_cnh"></span>
                                                        </div>  
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('situacao') ?>: </b></p><span id="situacao_consulta_cnh"></span>
                                                        </div>                             
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('visualizar_arquivo') ?>: </b></p><span id="path_file_consulta_cnh"></span>
                                                        </div>                                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Resposta da consulta do antecedentes criminais -->
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading3">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                            <span class="glyphicon glyphicon-plus"></span>
                                            <?=lang('consulta_antecedentes_criminais')?>
                                        </a>
                                    </h4>
                                    </div>
                                    <div id="collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3">
                                        <div class="panel-body">
                                            <div class="row detalhes_consulta" id="div_msg_info_antecedentes" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_info_antecedentes"></span>
                                                </div>
                                            </div>
                                            <div class="row detalhes_consulta" id="div_resultado_info_antecedentes">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('possui_antecedentes') ?>: </b></p><span id="possui_anteced_criminais_consulta_antecedentes"></span>                                
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('numero_certidao') ?>: </b></p><span id="numero_certidao_consulta_antecedentes"></span>
                                                        </div>                                                                                        
                                                        <div class="col-md-4">
                                                            <p><b><?=lang('data_emissao')?>: </b></p><span id="data_emissao_consulta_antecedentes"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('visualizar_arquivo') ?>: </b></p><span id="path_file_consulta_antecedentes"></span>
                                                        </div>                                
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('observacoes') ?>: </b></p><span id="observacoes_consulta_antecedentes"></span>
                                                        </div>                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Resposta da consulta do mandados de prisao -->
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading4">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?=lang('consulta_mandados_prisao')?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading4">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row detalhes_consulta" id="div_msg_info_mandados" style="display:none">
                                                        <div class="col-md-12">
                                                            <span id="msg_info_mandados"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row detalhes_consulta" id="div_resultado_info_mandados">
                                                        <div class="col-md-2">
                                                            <select class="form-control" id="select_mandados"><?=lang('selecione_um_mandado')?></select>
                                                        </div>
                                                        <div class="col-md-10" id="mostrar_divs_mandados" style="display: none;">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('numero_processo') ?>: </b></p><span id="numero_processo_consulta_mandados" class="dados_mandados"></span>                                
                                                                </div>                                        
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('nome_pessoa') ?>: </b></p><span id="nome_pessoa_consulta_mandados" class="dados_mandados"></span>
                                                                </div>                                                                      
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><b><?=lang('descricao_mandado')?>: </b></p><span id="descricao_status_consulta_mandados" class="dados_mandados"></span>
                                                                </div>                                                                                                               
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('numero_peca') ?>: </b></p><span id="numero_peca_consulta_mandados" class="dados_mandados"></span>                                
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('descricao_peca') ?>: </b></p><span id="descricao_peca_consulta_mandados" class="dados_mandados"></span>                                
                                                                </div>                                                              
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('data_expedicao') ?>: </b></p><span id="data_expedicao_consulta_mandados" class="dados_mandados"></span>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><b><?=lang('nome_orgao')?>: </b></p><span id="nome_orgao_consulta_mandados" class="dados_mandados"></span>
                                                                </div>                                                                                                  
                                                            </div>
                                                        </div>                                                                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Resposta da consulta de processos -->
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading5">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?=lang('consulta_processos')?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse5" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading5">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row detalhes_consulta" id="div_msg_info_processos" style="display:none">
                                                        <div class="col-md-12">
                                                            <span id="msg_info_processos"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row detalhes_consulta" id="div_resultado_info_processos">
                                                        <div class="col-md-2">
                                                            <select class="form-control" id="select_processos"><?=lang('selecione_um_processo')?></select>
                                                        </div>
                                                        <div class="col-md-10" id="mostrar_divs_processos" style="display: none;">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('numero') ?>: </b></p><span id="numero_consulta_processos" class="dados_processos"></span>                                
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p><b><?=lang('estado')?>: </b></p><span id="estado_consulta_processos" class="dados_processos"></span>
                                                                </div>                                                                                                                                                                               
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('tribunal_nome') ?>: </b></p><span id="tribunal_nome_consulta_processos" class="dados_processos"></span>                                
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('tribunal_instancia') ?>: </b></p><span id="tribunal_instancia_consulta_processos" class="dados_processos"></span>                                
                                                                </div>                                                              
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('tribunal_distrito') ?>: </b></p><span id="tribunal_distrito_consulta_processos" class="dados_processos"></span>
                                                                </div>                                                                                               
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('tribunal_tipo') ?>: </b></p><span id="tribunal_tipo_consulta_processos" class="dados_processos"></span>
                                                                </div>                                                                                               
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('situacao') ?>: </b></p><span id="situacao_consulta_processos" class="dados_processos"></span>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p><b><?=lang('tipo')?>: </b></p><span id="tipo_consulta_processos" class="dados_processos"></span>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p><b><?=lang('assunto')?>: </b></p><span id="assunto_consulta_processos" class="dados_processos"></span>
                                                                </div>
                                                            </div>
                                                        </div>                                                                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Resposta da consulta de debitos financeiros -->
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading6">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?=lang('consulta_debitos_financeiros')?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse6" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading6">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row detalhes_consulta" id="div_msg_info_debitos" style="display:none">
                                                        <div class="col-md-12">
                                                            <span id="msg_info_debitos"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row detalhes_consulta" id="div_resultado_info_debitos">
                                                        <div class="col-md-2">
                                                            <select class="form-control" id="select_debitos"><?=lang('selecione_um_debito')?></select>
                                                        </div>
                                                        <div class="col-md-10" id="mostrar_divs_debitos" style="display: none;">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><b>CNPJ: </b></p><span id="cnpj_consulta_debitos" class="dados_debitos"></span>                                
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('empresa') ?>: </b></p><span id="empresa_consulta_debitos" class="dados_debitos"></span>
                                                                </div>                                                                      
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><b><?=lang('natureza')?>: </b></p><span id="natureza_consulta_debitos" class="dados_debitos"></span>
                                                                </div>                                                                                                               
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('valor') ?>: </b></p><span id="valor_consulta_debitos" class="dados_debitos"></span>                                
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('status') ?>: </b></p><span id="status_consulta_debitos" class="dados_debitos"></span>                                
                                                                </div>                                                              
                                                                <div class="col-md-6">
                                                                    <p><b><?= lang('data_ocorrencia') ?>: </b></p><span id="data_ocorrencia_consulta_debitos" class="dados_debitos"></span>
                                                                </div>
                                                            </div>
                                                        </div>                                                                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>   
                    </div>
                    <!-- FIM ABA DADOS PROFISSIONAL-->

                    <!-- ABA DADOS PROPRIETARIO -->
                    <div id="aba_proprietario" class="tab-pane fade" style="padding: 10px">
                        <div class="row configBox">
                            <h4><?= lang('dados_fornecidos_pelo_cliente') ?></h4>
                            <hr>
                            <label><?=lang('dados_pessoais')?></label>
                            <div class="row">
                                <div class="col-md-4">
                                    <p><b><?= lang('nome') ?>/<?= lang('razao_social') ?>: </b></p><span id="nome_proprietario"></span>                                
                                </div>
                                <div class="col-md-4">
                                    <p><b><?= lang('cpf') ?>/<?= lang('cnpj') ?>: </b></p><span id="cpf_cnpj_proprietario"></span>
                                </div>
                            </div>

                            <hr>
                            <label><?=lang('dados_endereco')?></label>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('cep') ?>: </b></p><span id="cep_proprietario"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('logradouro') ?>: </b></p><span id="endereco_proprietario"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('bairro') ?>: </b></p><span id="bairro_proprietario"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('cidade') ?>: </b></p><span id="cidade_proprietario"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('uf') ?>: </b></p><span id="uf_proprietario"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('numero') ?>: </b></p><span id="numero_proprietario"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('complemento') ?>: </b></p><span id="complemento_proprietario"></span>
                                </div>
                            </div>

                            <hr>
                            <label><?=lang('dados_contato')?></label>
                            <div class="row">
                                <div class="col-md-4">
                                    <p><b><?= lang('telefone') ?> <?= lang('residencial') ?>: </b></p><span id="telefone_residencial_proprietario"></span>
                                </div>
                                <div class="col-md-4">
                                    <p><b><?= lang('contato_residencial') ?>: </b></p><span id="contato_residencial_proprietario"></span>
                                </div>                            
                            </div>
                            <div class="row">
                            <div class="col-md-4">
                                    <p><b><?= lang('telefone') ?> <?= lang('comercial') ?>: </b></p><span id="telefone_comercial_proprietario"></span>
                                </div>
                                <div class="col-md-4">
                                    <p><b><?= lang('contato_comercial') ?>: </b></p><span id="contato_comercial_proprietario"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIM ABA DADOS PROPRIETARIO-->

                    <!-- ABA DADOS VEICULO -->
                    <div id="aba_veiculo" class="tab-pane fade" style="padding: 10px">
                        <div class="row configBox">
                            <h4><?= lang('dados_fornecidos_pelo_cliente') ?></h4>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('placa') ?>: </b></p><span id="placa_veiculo"></span>                                
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('marca') ?>: </b></p><span id="marca_veiculo"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('modelo') ?>: </b></p><span id="modelo_veiculo"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('renavam') ?>: </b></p><span id="renavam_veiculo"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('chassi') ?>: </b></p><span id="chassi_veiculo"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('cidade_emplacamento') ?>: </b></p><span id="cidade_emplacamento_veiculo"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('cor') ?>: </b></p><span id="cor_veiculo"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('ano') ?>: </b></p><span id="ano_veiculo"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('uf') ?>: </b></p><span id="uf_veiculo"></span>
                                </div>                            
                            </div>                       
                        </div>
                        <div class="row configBox" style="margin-top: 10px;">
                            <h4><?= lang('dados_fornecidos_pelas_apis') ?></h4>
                            <hr>
                            <!-- Resposta da consulta do veiculo -->
                            <div class="panel-group" id="accordion2" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading7">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse7" aria-expanded="false" aria-controls="collapse7">
                                                <span class="glyphicon glyphicon-minus"></span>
                                                <?=lang('consulta_veiculo')?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse7" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading7">
                                        <div class="panel-body">
                                            <div class="row detalhes_consulta" id="div_msg_info_veiculo" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_info_veiculo"></span>
                                                </div>
                                            </div>
                                            <div class="row detalhes_consulta" id="div_resultado_info_veiculo">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('placa') ?>: </b></p><span id="placa_consulta_veiculo"></span>                                
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('codigo_renavam') ?>: </b></p><span id="codigo_renavam_consulta_veiculo"></span>
                                                        </div>                                                                       
                                                        <div class="col-md-4">
                                                            <p><b><?=lang('chassi')?>: </b></p><span id="chassi_consulta_veiculo"></span>
                                                        </div> 
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('ano_modelo') ?>: </b></p><span id="ano_modelo_consulta_veiculo"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('ano_fabricacao') ?>: </b></p><span id="ano_fabricacao_consulta_veiculo"></span>
                                                        </div>                                        
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('identificacao_proprietario') ?>: </b></p><span id="numero_identificacao_proprietario_consulta_veiculo"></span>
                                                        </div>                                        
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('nome_proprietario') ?>: </b></p><span id="nome_proprietario_consulta_veiculo"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('situacao') ?>: </b></p><span id="situacao_consulta_veiculo"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('indicador_pendencia_emissao') ?>: </b></p><span id="indicador_pendencia_emissao_consulta_veiculo"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">                                        
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('indicador_roubo_furto') ?>: </b></p><span id="indicador_roubo_furto_consulta_veiculo"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('indicador_restricao_renajud') ?>: </b></p><span id="indicador_restricao_renajud_consulta_veiculo"></span>
                                                        </div>                              
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('indicador_multa_renainf') ?>: </b></p><span id="indicador_multa_renainf_consulta_veiculo"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">                                
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('visualizar_arquivo') ?>: </b></p><span id="path_file_consulta_veiculo"></span>
                                                        </div>                                                               
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIM ABA DADOS VEICULO-->

                    <!-- ABA DADOS CARRETA -->
                    <div id="aba_carreta" class="tab-pane fade" style="padding: 10px">
                        <div class="row configBox">
                            <h4><?= lang('dados_fornecidos_pelo_cliente') ?></h4>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('placa') ?>: </b></p><span id="placa_carreta"></span>                                
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('marca') ?>: </b></p><span id="marca_carreta"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('modelo') ?>: </b></p><span id="modelo_carreta"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('renavam') ?>: </b></p><span id="renavam_carreta"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('chassi') ?>: </b></p><span id="chassi_carreta"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('cidade_emplacamento') ?>: </b></p><span id="cidade_emplacamento_carreta"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('cor') ?>: </b></p><span id="cor_carreta"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('ano') ?>: </b></p><span id="ano_carreta"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('uf') ?>: </b></p><span id="uf_carreta"></span>
                                </div>                            
                            </div>                       
                        </div>
                        <div class="row configBox" style="margin-top: 10px;">
                            <h4><?= lang('dados_fornecidos_pelas_apis') ?></h4>
                            <hr>
                            <!-- Resposta da consulta da carreta -->
                            <div class="panel-group" id="accordion3" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading8">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse8" 
                                                aria-expanded="false" aria-controls="collapse8">
                                                <span class="glyphicon glyphicon-minus"></span>
                                                <?=lang('consulta_carreta')?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse8" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading8">
                                        <div class="panel-body">
                                            <div class="row detalhes_consulta" id="div_msg_info_carreta" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_info_carreta"></span>
                                                </div>
                                            </div>
                                            <div class="row detalhes_consulta" id="div_resultado_info_carreta">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('placa') ?>: </b></p><span id="placa_consulta_carreta"></span>                                
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('codigo_renavam') ?>: </b></p><span id="codigo_renavam_consulta_carreta"></span>
                                                        </div>                                                                       
                                                        <div class="col-md-4">
                                                            <p><b><?=lang('chassi')?>: </b></p><span id="chassi_consulta_carreta"></span>
                                                        </div> 
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('ano_modelo') ?>: </b></p><span id="ano_modelo_consulta_carreta"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('ano_fabricacao') ?>: </b></p><span id="ano_fabricacao_consulta_carreta"></span>
                                                        </div>                                        
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('identificacao_proprietario') ?>: </b></p><span id="numero_identificacao_proprietario_consulta_carreta"></span>
                                                        </div>                                        
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('nome_proprietario') ?>: </b></p><span id="nome_proprietario_consulta_carreta"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('situacao') ?>: </b></p><span id="situacao_consulta_carreta"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('indicador_pendencia_emissao') ?>: </b></p><span id="indicador_pendencia_emissao_consulta_carreta"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">                                        
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('indicador_roubo_furto') ?>: </b></p><span id="indicador_roubo_furto_consulta_carreta"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('indicador_restricao_renajud') ?>: </b></p><span id="indicador_restricao_renajud_consulta_carreta"></span>
                                                        </div>                              
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('indicador_multa_renainf') ?>: </b></p><span id="indicador_multa_renainf_consulta_carreta"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">                                
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('visualizar_arquivo') ?>: </b></p><span id="path_file_consulta_carreta"></span>
                                                        </div>                                                               
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIM ABA DADOS CARRETA-->

                    <!-- ABA DADOS SEGUNDA CARRETA -->
                    <div id="aba_segunda_carreta" class="tab-pane fade" style="padding: 10px">
                        <div class="row configBox">
                            <h4><?= lang('dados_fornecidos_pelo_cliente') ?></h4>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('placa') ?>: </b></p><span id="placa_segunda_carreta"></span>                                
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('marca') ?>: </b></p><span id="marca_segunda_carreta"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('modelo') ?>: </b></p><span id="modelo_segunda_carreta"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('renavam') ?>: </b></p><span id="renavam_segunda_carreta"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('chassi') ?>: </b></p><span id="chassi_segunda_carreta"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('cidade_emplacamento') ?>: </b></p><span id="cidade_emplacamento_segunda_carreta"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('cor') ?>: </b></p><span id="cor_segunda_carreta"></span>
                                </div>
                                <div class="col-md-3">
                                    <p><b><?= lang('ano') ?>: </b></p><span id="ano_segunda_carreta"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><b><?= lang('uf') ?>: </b></p><span id="uf_segunda_carreta"></span>
                                </div>                            
                            </div>                       
                        </div>
                        <div class="row configBox" style="margin-top: 10px;">
                            <h4><?= lang('dados_fornecidos_pelas_apis') ?></h4>
                            <hr>
                            <!-- Resposta da consulta da carreta -->
                            <div class="panel-group" id="accordion4" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading9">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse9" 
                                                aria-expanded="false" aria-controls="collapse9">
                                                <span class="glyphicon glyphicon-minus"></span>
                                                <?=lang('consulta_segunda_carreta')?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse9" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading9">
                                        <div class="panel-body">
                                            <div class="row detalhes_consulta" id="div_msg_info_segunda_carreta" style="display:none">
                                                <div class="col-md-12">
                                                    <span id="msg_info_segunda_carreta"></span>
                                                </div>
                                            </div>
                                            <div class="row detalhes_consulta" id="div_resultado_info_segunda_carreta">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('placa') ?>: </b></p><span id="placa_consulta_segunda_carreta"></span>                                
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('codigo_renavam') ?>: </b></p><span id="codigo_renavam_consulta_segunda_carreta"></span>
                                                        </div>                                                                       
                                                        <div class="col-md-4">
                                                            <p><b><?=lang('chassi')?>: </b></p><span id="chassi_consulta_segunda_carreta"></span>
                                                        </div> 
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('ano_modelo') ?>: </b></p><span id="ano_modelo_consulta_segunda_carreta"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('ano_fabricacao') ?>: </b></p><span id="ano_fabricacao_consulta_segunda_carreta"></span>
                                                        </div>                                        
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('identificacao_proprietario') ?>: </b></p><span id="numero_identificacao_proprietario_consulta_segunda_carreta"></span>
                                                        </div>                                        
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('nome_proprietario') ?>: </b></p><span id="nome_proprietario_consulta_segunda_carreta"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('situacao') ?>: </b></p><span id="situacao_consulta_segunda_carreta"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('indicador_pendencia_emissao') ?>: </b></p><span id="indicador_pendencia_emissao_consulta_segunda_carreta"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">                                        
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('indicador_roubo_furto') ?>: </b></p><span id="indicador_roubo_furto_consulta_segunda_carreta"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('indicador_restricao_renajud') ?>: </b></p><span id="indicador_restricao_renajud_consulta_segunda_carreta"></span>
                                                        </div>                              
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('indicador_multa_renainf') ?>: </b></p><span id="indicador_multa_renainf_consulta_segunda_carreta"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">                                
                                                        <div class="col-md-4">
                                                            <p><b><?= lang('visualizar_arquivo') ?>: </b></p><span id="path_file_consulta_segunda_carreta"></span>
                                                        </div>                                                               
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIM ABA DADOS SEGUNDA CARRETA-->
                
                    <div class="row configBox" style="margin-bottom:10px; margin-left:-5px; margin-right:-5px;">
                        <div class="col-md-12" style="line-height: 35px;">
                            <h4><?= lang('resultado_analise') ?></h4>
                            <hr>                                                      
                            <div class="row" style="float:left; width:100%;">
                                <div class="col-md-6">
                                    <p><b><?= lang('pontuacao_consulta') ?>: </b><span id="pontuacao_consulta"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><b><?= lang('protocolo') ?>: </b><span id="protocolo_consulta"></span></p>
                                </div>                                
                            </div>
                            <div class="row">                                
                                <div class="col-md-6">
                                    <p><b><?= lang('situacao') ?>: </b><span class="label" id="resultado_consulta"></span></p>
                                </div>
                                <div class="col-md-6" id="div_alertas_consulta">
                                    <p><b><?= lang('alertas') ?>: </b><span id="alertas_consulta" ></span></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary" id="btn_gerar_laudo_resumido"><?=lang('laudo_resumido')?></button>
                                    <button class="btn btn-primary" id="btn_gerar_laudo"><?=lang('laudo_detalhado')?></button>
                                    <button class="btn btn-primary gerarLaudoVitimismo" ><?=lang('laudo_vitimismo')?></button>
                                </div>
                            </div>
                            <div class="modal-footer">                                
                            </div> 
                        </div>               
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>