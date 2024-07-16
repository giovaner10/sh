<h3><?=lang('termo_adesao')?></h3>

<style>
    
.dropdown {
    position: relative;
    margin-bottom: 20px;
}

.dropdown-content {
    display: none;
    position: absolute;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0);
    z-index: 1;
}

.dropdown-content a {
    text-decoration: none;
    display: block;
}

table{
    width: 100% !important;
}

.table-responsive{
    padding-top: 15px;
}

</style>

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#show" class="show"><?=lang('show_tecnologia')?></a></li>
  <li class="sim"><a data-toggle="tab" href="#sim" ><?=lang('simm2m')?></a></li>
  <li class="showCu"><a data-toggle="tab" href="#showCuritiba" >Show Tecnologia Curitiba</a></li>
</ul>

<div class="tab-content">
  <div id="show" class="tab-pane fade in active">
        <!-- Botão adicionar termo show-->  

        <!-- <div class="btn-group dropright col-md-12" style="margin-top: 10px; margin-bottom: 20px;">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?=lang('adicionar_termo')?>  <i class="fa fa-angle-right" style="font-size: 16px!important;"></i>
            </button>
            <ul class="dropdown-menu pull-bottom"  x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 30px, 0px); top: 0px; left: 0px; will-change: transform;">
                <li><a class="dropdown-item add_termo_show" href="" onclick="return false;" data-tipoTermo="fisico" alt="Pessoa Física"> <?=lang('pessoa_fisica')?></a></li>
                <li><a class="dropdown-item add_termo_show" href="" onclick="return false;" data-tipoTermo="juridico" alt="Pessoa Jurídica"> <?=lang('pessoa_juridica')?></a></li>
            </ul>
        </div> -->

        <div class="col-md-2 dropdown">
            <button class="dropbtn btn btn-info" id="btnDropdownShow" type="button" style="width: 100%">
                <?=lang('adicionar_termo')?>
                <i class="fa fa-angle-right" style="font-size: 16px!important;"></i>
            </button>
            <div id="myDropdownShow" class="dropdown-content" hidden>
                <!-- <a class="btn btn-info listagem_docs" id='listagem_docs' style="width: 100%;">Documentos</a>
                <a class="btn btn-info" id="getResumoClienteERP" style="width: 100%;"   >Resumo Cliente ERP</a> -->
                <a class="btn btn-default add_termo_show" href="" onclick="return false;" data-tipoTermo="fisico" alt="Pessoa Física" style="width: 100%;">  
                    <?=lang('pessoa_fisica')?>
                </a>
                <a class="btn btn-default add_termo_show" href="" onclick="return false;" data-tipoTermo="juridico" alt="Pessoa Jurídica" style="width: 100%;"> 
                    <?=lang('pessoa_juridica')?>
                </a>
            
            </div>
        </div>  

        <div class="table-responsive col-md-12">
            <!-- MENSAGENS (ALERTS) -->
            <div class="termo_alert_load" style="display:none">
                <button type="button" class="close" onclick="escondeMensagem('termo_alert_load')">&times;</button>
                <span id="msn_load"></span>
            </div>

            <table id="tableShow" class="table table-hover responsive display">
                <thead class="thead-inverse">
                    <th class="span1">ID</th>
                    <th class="span5"><?=lang('razao_social')?></th>
                    <th class="span2">CPF/CNPJ</th>
                    <th class="span2"><?=lang('prestadora')?></th>
                    <th class="span2"><?=lang('administrar')?></th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div id="sim" class="tab-pane fade in">
        <!-- Botão adicionar termo simm2m -->

        <!-- <div class="btn-group dropright col-md-12" style="margin-top: 10px; margin-bottom: 20px;">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?=lang('adicionar_termo')?>  <i class="fa fa-angle-right" style="font-size: 16px!important;"></i>
            </button>
            <ul class="dropdown-menu pull-bottom"  x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 30px, 0px); top: 0px; left: 0px; will-change: transform;">
                <li><a class="dropdown-item add_termo_sim" href="" onclick="return false;" data-tipoTermo="juridico" alt="<?=lang('pessoa_juridica')?>"> <?=lang('pessoa_juridica')?></a></li>
            </ul>
        </div> -->

        

        <div class="col-md-2 dropdown">
            <button class="dropbtn btn btn-info" id="btnDropdownSim" type="button" style="width: 100%">
                <?=lang('adicionar_termo')?>
                <i class="fa fa-angle-right" style="font-size: 16px!important;"></i>
            </button>
            <div id="myDropdownSim" class="dropdown-content" hidden>
                <a class="btn btn-default add_termo_sim" href="" onclick="return false;" data-tipoTermo="juridico" alt="Pessoa Jurídica" style="width: 100%;"> 
                    <?=lang('pessoa_juridica')?>
                </a>
            
            </div>
        </div>  

    <div class="table-responsive col-md-12">
      <!-- MENSAGENS (ALERTS) -->
      <div class="termo_alert_load_sim" style="display:none">
        <button type="button" class="close" onclick="escondeMensagem('termo_alert_load_sim')">&times;</button>
        <span id="msn_load_sim"></span>
      </div>

      <table id="tableSim" class="table table-hover responsive display">
        <thead class="thead-inverse">
                    <th class="span1">ID</th>
                    <th class="span5"><?=lang('razao_social')?></th>
                    <th class="span2">CPF/CNPJ</th>
                    <th class="span2"><?=lang('prestadora')?></th>
                    <th class="span2"><?=lang('administrar')?></th>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <div id="showCuritiba" class="tab-pane fade in">
        <!-- Botão adicionar termo show-->

        <!-- <div class="btn-group dropright col-md-12" style="margin-top: 10px; margin-bottom: 20px;">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?=lang('adicionar_termo')?>  <i class="fa fa-angle-right" style="font-size: 16px!important;"></i>
            </button>
            <ul class="dropdown-menu pull-bottom"  x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 30px, 0px); top: 0px; left: 0px; will-change: transform;">
                <li><a class="dropdown-item add_termo_show_curitiba" href="" onclick="return false;" data-tipoTermo="fisico" alt="Pessoa Física"> <?=lang('pessoa_fisica')?></a></li>
                <li><a class="dropdown-item add_termo_show_curitiba" href="" onclick="return false;" data-tipoTermo="juridico" alt="Pessoa Jurídica"> <?=lang('pessoa_juridica')?></a></li>
            </ul>
        </div> -->
        
        <div class="col-md-2 dropdown">
            <button class="dropbtn btn btn-info" id="btnDropdownCuritiba" type="button" style="width: 100%">
                <?=lang('adicionar_termo')?>
                <i class="fa fa-angle-right" style="font-size: 16px!important;"></i>
            </button>
            <div id="myDropdownCuritiba" class="dropdown-content" hidden>
                <!-- <a class="btn btn-info listagem_docs" id='listagem_docs' style="width: 100%;">Documentos</a>
                <a class="btn btn-info" id="getResumoClienteERP" style="width: 100%;"   >Resumo Cliente ERP</a> -->
                <a class="btn btn-default add_termo_show_curitiba" href="" onclick="return false;" data-tipoTermo="fisico" alt="Pessoa Física" style="width: 100%;">  
                    <?=lang('pessoa_fisica')?>
                </a>
                <a class="btn btn-default add_termo_show_curitiba" href="" onclick="return false;" data-tipoTermo="juridico" alt="Pessoa Jurídica" style="width: 100%;"> 
                    <?=lang('pessoa_juridica')?>
                </a>
            
            </div>
        </div>  

    <div class="table-responsive col-md-12">
      <!-- MENSAGENS (ALERTS) -->
      <div class="termo_alert_load" style="display:none">
        <button type="button" class="close" onclick="escondeMensagem('termo_alert_load')">&times;</button>
        <span id="msn_load"></span>
      </div>

      <table id="tablehowCuritiba" class="table table-hover responsive display">
        <thead class="thead-inverse">
                    <th class="span1">ID</th>
                    <th class="span5"><?=lang('razao_social')?></th>
                    <th class="span2">CPF/CNPJ</th>
                    <th class="span2"><?=lang('prestadora')?></th>
                    <th class="span2"><?=lang('administrar')?></th>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

</div>


<!--MODAL NOVO TERMO SHOW-->
<div id="novoTermoShow" class="modal fade" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog" style="width:70%!important;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
              <h3><span id="tituloModalShow" ><?=lang('novo_termo')?><span></h3>
          </div>
            <div class="modal-body">
                <!-- MENSAGENS (ALERTS) -->
                <div class="termo_alert" style="display:none">
                    <button type="button" class="close" onclick="escondeMensagem('termo_alert')">&times;</button>
                    <span id="msn_termo"></span>
                </div>
                <form id="formNovoShow">
                    <div class="col-md-12">
                        <div class="form-group col-md-6">
                            <label class="control-label"><?=lang('executivo_vendas')?></label><br>
                            <select id="executivo_vendas" class="form-control span4 executivo_vendas editAditivo" name="termo[executivo_vendas]" style="width:100%" required >
                                <option value="" disabled selected></option>
                            </select>
                        </div>
                    </div>
          <div class="col-md-12">
            <legend><?= lang('dados_contratante') ?> <small style="font-size:65%;">(<?=lang('obs_dados_contratante') ?>)</small></legend>
                        <div class="form-group col-md-6">
                            <!-- recebe o imput vindo do javascript -->
                            <span id="imputCpf_cnpj"></span>
                        </div>
            <div class="form-group col-md-6">
              <label class="control-label"><?=lang('razao_social')?> | <?=lang('nome')?></label>
              <input id="razao_social" class="readyOnly form-control editAditivo" type="text" name="cliente[razao_social]" required>
            </div>
                    </div>
                    <div class="col-md-12">
                        <legend><?=lang('endereco_principal')?></legend>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?=lang('endereco')?></label>
                                <input id="rua" class="readyOnly form-control editAditivo" type="text" name="endereco[rua]" value="" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?=lang('cidade')?></label>
                                <input id="cidade" class="readyOnly form-control editAditivo" type="text" name="endereco[cidade]" value="" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?=lang('estado')?></label>
                                <input id="uf" class="readyOnly form-control editAditivo" type="text" name="endereco[uf]" value="" required>
                            </div>
              <div class="form-group">
                                <label><?=lang('complemento')?></label>
                                <input type="text" id="complemento" value="" class="form-control editAditivo" name="endereco[complemento]">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?=lang('insc_estadual')?></label>
                                <input type="text" class="form-control editAditivo" id="insc_estadual" name="cliente[inscricao_estadual]">
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?=lang('bairro')?></label>
                                <input id="bairro" class="readyOnly form-control editAditivo" type="text" name="endereco[bairro]" value="" required>
                            </div>
                            <div class="form-group">
                               <label class="control-label"><?=lang('cep')?></label>
                               <input id="cep" class="readyOnly form-control editAditivo cep" type="text" class="cep" name="endereco[cep]" value="" required>
                           </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <legend><?=lang('dados_contato')?></legend>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?=lang('fone_cel')?></label>
                                <input id="fone_cel" type="text" class="form-control editAditivo cell" name="fone[fone_cel]" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?=lang('email')?></label>
                                <input id="email" type="email" class="form-control editAditivo" name="email[email]" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?=lang('fone_fixo')?></label>
                                <input id="fone_fixo" type="text" class="form-control editAditivo fone" name="fone[fone_fixo]" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <legend><?=lang('endereco_entrega_cad')?>  <button type="button" id="copiar_endereco" class="btn btn-info btn-xs"><?=lang('copiar_endereco')?></button></legend>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?=lang('endereco')?></label>
                                <input type="text" class="form-control editAditivo" id="rua_entrega" name="endereco_entrega[rua]" required>
                            </div>
                            <div class="form-group">
                               <label class="control-label"><?=lang('cidade')?></label>
                               <input type="text" class="form-control editAditivo" id="cidade_entrega" name="endereco_entrega[cidade]" required>
                           </div>
                           <div class="form-group">
                               <label class="control-label"><?=lang('cep')?></label>
                               <input type="text" class="cep form-control editAditivo cep" id="cep_entrega" name="endereco_entrega[cep]" required>
                           </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?=lang('bairro')?></label>
                                <input type="text" class="form-control editAditivo" id="bairro_entrega" name="endereco_entrega[bairro]" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?=lang('estado')?></label>
                                <input type="text" class="form-control editAditivo" id="uf_entrega" name="endereco_entrega[uf]" required>
                            </div>
                            <div class="form-group">
                               <label><?=lang('complemento')?></label>
                               <input type="text" class="form-control editAditivo" id="complemento_entrega" name="endereco_entrega[complemento]" >
                           </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <legend><?=lang('cond_comerciais_minus')?></legend>
                        <div class="col-md-6">
                            <div class="form-group">
                               <label><?=lang('tipo_contrato')?>:</label>
                               <select class="form-control" id="tipo_contrato" name="termo[tipo_contrato]">
                                   <option><?=lang('comodato')?></option>
                                   <option><?=lang('aquisicao')?></option>
                               </select>
                           </div>
                           <div class="form-group">
                               <label class="control-label"><?=lang('pacote_servicos')?></label>
                               <input type="text" class="form-control" value="" id="pct_servicos" name="termo[pct_servicos]" required>
                           </div>
                           <div class="form-group">
                               <label class="control-label"><?=lang('pessoa_contas_pagar')?></label>
                               <input type="text" class="form-control" id="pessoa_contas_pagar" name="termo[pessoa_contas_pagar]" required>
                           </div>
                           <div class="form-group">
                                <label class="control-label"><?=lang('email_contas_apagar')?></label>
                                <input type="email" class="form-control editAditivo" id="email_financeiro" name="email[email_financeiro]" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                               <label class="control-label"><?=lang('qtd_veiculos')?></label>
                               <input type="number" min="1" class="form-control" value="" id="qtd_eqp" name="termo[qtd_eqp]" required>
                           </div>
                           <div class="form-group">
                               <label class="control-label"><?=lang('bloqueio')?>:</label>
                               <select class="form-control" id="bloqueio" name="termo[bloqueio]" required>
                                   <option><?=mb_strtoupper(lang('sim'), 'UTF-8')?></option>
                                   <option><?=mb_strtoupper(lang('nao'), 'UTF-8')?></option>
                               </select>
                           </div>
                           <div class="form-group">
                                <label class="control-label"><?=lang('contato_contas_pagar')?></label>
                                <input type="text" class="form-control" id="ctt_contas_pagar" name="termo[ctt_contas_pagar]" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="periodo"><?=lang('periodo_contato')?>:</label>
                                <select class="form-control custom-select mr-sm-2" id="periodo_contrato" name="termo[periodo_contrato]" required>
                  <option>12</option>
                                    <option>24</option>
                                    <option>36</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <legend><?=lang('cond_pagamento_minus')?></legend>
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?=lang('valor_inst_veic')?></label>
                                <input type="text" class="form-control moeda" id="valor_inst_veic" name="termo[valor_inst_veic]" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?=lang('venc_adesao')?></label>
                                <input type="date" class="form-control date" id="primeiro_venc_adesao" name="termo[primeiro_venc_adesao]" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?=lang('dia_vencimento')?>:</label>
                                <select class="form-control" id="dt_vencimento" name="termo[dt_vencimento]" required>
                                    <option>10</option>
                                    <option>20</option>
                                </select>
                            </div>
                            <div class="form-group">
                               <label><?=lang('produto_adicional')?></label>
                               <input  class="form-control" id="produto_adicional" name="termo[produto_adicional]">
                           </div>
                           <div class="form-group">
                                <label><?=lang('valor_unitario')?></label>
                                <input type="text" value="" class="form-control moeda" id="valor_final_un" name="termo[valor_final_un]">
                            </div>
                            <div class="form-group">
                               <label><?=lang('adicional_parcelas')?></label>
                               <input type="number" min="1" class="form-control" id="adicional_parcelas" name="termo[adicional_parcelas]">
                           </div>
                           <div class="form-group">
                               <label class="control-label"><?=lang('inst_sigilosa')?></label>
                               <select class="form-control" type="text" id="inst_sigilosa" name="termo[inst_sigilosa]" required>
                                   <option><?=mb_strtoupper(lang('nao'), 'UTF-8')?></option>
                                   <option><?=mb_strtoupper(lang('sim'), 'UTF-8')?></option>
                               </select>
                           </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="form-group">
                               <label class="control-label"><?=lang('inst_parcelas')?></label>
                               <input type="number" min="1" class="form-control" id="inst_parcelas" name="termo[inst_parcelas]" required>
                           </div>
                           <div class="form-group">
                                <label class="control-label"><?=lang('valor_mensalidade_veic')?></label>
                                <input value="" type="text" class="form-control moeda" id="valor_mens_veic" name="termo[valor_mens_veic]" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?=lang('venc_mensalidade')?></label>
                                <input type="date" class="form-control date" id="primeiro_venc_mens" name="termo[primeiro_venc_mens]" required>
                            </div>
                            <div class="form-group">
                                <label><?=lang('quantidade')?></label>
                                <input type="number" min="1" class="form-control" id="qtd" name="termo[qtd]">
                            </div>
                            <div class="form-group">
                                <label><?=lang('total')?></label>
                                <input type="text" class="form-control moeda" id="total" name="termo[total]">
                            </div>
                            <div class="form-group">
                                <label><?=lang('venc_adicional')?></label>
                                <input type="date" class="form-control date" id="primeiro_venc_adicional" name="termo[primeiro_venc_adicional]">
                            </div>
                            <div class="form-group">
                                <label><?=lang('observacao')?></label>
                                <input class="form-control" type="text" id="obs" name="termo[obs]">
                            </div>
                            <input type="hidden" id="empresa" name="empresa"> 
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="col-md-12">
                            <div class="botoes_resposta" style="float: right;">
                                <button type="reset" class="btn btnResetShow"> <?=lang('limpar')?></button>
                                <button type="submit" class="btn btn-primary btnNovoTermoShow" data-acao="novo" > <?=lang('salvar')?></button>
                            </div>
                        </div>
                    </div>
              </form>
          </div>
      </div>
  </div>
</div>

<!--MODAL NOVO TERMO SIMM2M -->
<div id="novoTermoSim" class="modal fade" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog" style="width:70%!important;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
              <h3><span id="tituloModalSim" ><?=lang('novo_termo')?><span></h3>
          </div>
            <div class="modal-body">
                <!-- MENSAGENS (ALERTS) -->
                <div class="termo_alert_sim" style="display:none">
                    <button type="button" class="close" onclick="escondeMensagem('termo_alert_sim')" >&times;</button>
                    <span id="msn_termo_sim"></span>
                </div>
                <form id="formNovoSim">
                    <div class="col-md-12">
                        <div class="form-group col-md-6">
                            <label><?=lang('executivo_vendas')?></label><br>
                            <select id="executivo_vendas_sim" class="form-control span4 editAditivoSim" name="termo[executivo_vendas]" style="width:100%" required >
                                <option value="" disabled selected></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <legend><?= lang('dados_contratante') ?> <small style="font-size:65%;">(<?=lang('obs_dados_contratante') ?>)</small></legend>
                        <div class="form-group col-md-6">
                            <!-- recebe o imput vindo do javascript -->
                            <span id="imputCpf_cnpj_sim"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label  class="control-label"><?=lang('razao_social')?> | <?=lang('nome')?></label>
                            <input id="razao_social_sim" class="readyOnly form-control editAditivoSim" type="text" name="cliente[razao_social]" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <legend><?=lang('endereco_principal')?></legend>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?=lang('endereco')?></label>
                                <input id="rua_sim" class="readyOnly form-control editAditivoSim" type="text" name="endereco[rua]" value="" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?=lang('cidade')?></label>
                                <input id="cidade_sim" class="readyOnly form-control editAditivoSim" type="text" name="endereco[cidade]" value="" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?=lang('estado')?></label>
                                <input id="uf_sim" class="readyOnly form-control editAditivoSim" type="text" name="endereco[uf]" value="" required>
                            </div>
                            <div class="form-group">
                                <label><?=lang('complemento')?></label>
                                <input type="text" id="complemento_sim" value="" class="form-control editAditivoSim" name="endereco[complemento]">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?=lang('insc_estadual')?></label>
                                <input type="text" class="form-control editAditivoSim" id="insc_estadual_sim" name="cliente[inscricao_estadual]">
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?=lang('bairro')?></label>
                                <input id="bairro_sim" class="readyOnly form-control editAditivoSim" type="text" name="endereco[bairro]" value="" required>
                            </div>
                            <div class="form-group">
                               <label class="control-label"><?=lang('cep')?></label>
                               <input id="cep_sim" class="readyOnly form-control editAditivoSim cep" type="text" class="cep" name="endereco[cep]" value="" required>
                           </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <legend><?=lang('dados_contato')?></legend>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?=lang('fone_cel')?></label>
                                <input id="fone_cel_sim" type="text" class="form-control editAditivoSim cell" name="fone[fone_cel]" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?=lang('email')?></label>
                                <input id="email_sim" type="email" class="form-control editAditivoSim" name="email[email]" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?=lang('fone_fixo')?></label>
                                <input id="fone_fixo_sim" type="text" class="form-control editAditivoSim fone" name="fone[fone_fixo]" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <legend><?=lang('endereco_entrega_cad')?>  <button type="button" id="copiar_endereco_sim" class="btn btn-info btn-xs"><?=lang('copiar_endereco')?></button></legend>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?=lang('endereco')?></label>
                                <input type="text" class="form-control editAditivoSim" id="rua_entrega_sim" name="endereco_entrega[rua]" required>
                            </div>
                            <div class="form-group">
                               <label class="control-label"><?=lang('cidade')?></label>
                               <input type="text" class="form-control editAditivoSim" id="cidade_entrega_sim" name="endereco_entrega[cidade]" required>
                           </div>
                           <div class="form-group">
                               <label class="control-label"><?=lang('cep')?></label>
                               <input type="text" class="cep form-control editAditivoSim cep" id="cep_entrega_sim" name="endereco_entrega[cep]" required>
                           </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?=lang('bairro')?></label>
                                <input type="text" class="form-control editAditivoSim" id="bairro_entrega_sim" name="endereco_entrega[bairro]" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?=lang('estado')?></label>
                                <input type="text" class="form-control editAditivoSim" id="uf_entrega_sim" name="endereco_entrega[uf]" required>
                            </div>
                            <div class="form-group">
                               <label><?=lang('complemento')?></label>
                               <input type="text" class="form-control editAditivoSim" id="complemento_entrega_sim" name="endereco_entrega[complemento]">
                           </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <legend><?=lang('cond_comerciais_minus')?></legend>
                        <div class="col-md-6">
                            <div class="form-group">
                               <label><?=lang('tipo_contrato')?>:</label>
                               <select class="form-control" id="tipo_contrato_sim" name="termo[tipo_contrato]">
                                   <option><?=lang('comodato')?></option>
                                   <option><?=lang('aquisicao')?></option>
                               </select>
                           </div>
               <div class="form-group">
                               <label class="control-label"><?=lang('operadora') ?></label>
                               <input type="text" class="form-control" value="" id="operadora_sim" name="termo[operadora]" required>
                           </div>
               <div class="form-group">
                               <label class="control-label"><?=lang('pacote_de_megas')?></label>
                               <input type="text" class="form-control" id="pct_megas_sim" name="termo[pct_megas]" required>
                           </div>
                        </div>
                        <div class="col-md-6">
              <div class="form-group">
                                <label class="control-label"><?=lang('qtd_chips')?></label>
                                <input type="number" min="1" class="form-control" id="quant_chips_sim" name="termo[quant_chips]" required>
                            </div>
                           <div class="form-group">
                               <label class="control-label"><?=lang('periodo_contato')?>:</label>
                               <select class="form-control" id="periodo_contrato_sim" name="termo[periodo_contrato]" required>
                   <option>12</option>
                                   <option>24</option>
                                   <option>36</option>
                               </select>
                           </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <legend><?=lang('cond_pagamento_minus')?></legend>
                        <div class="form-group col-md-6">
              <div class="form-group">
                               <label class="control-label"><?=lang('pessoa_contas_pagar')?></label>
                               <input type="text" class="form-control" id="pessoa_contas_pagar_sim" name="termo[pessoa_contas_pagar]" required>
                           </div>
                           <div class="form-group">
                                <label class="control-label"><?=lang('email_contas_apagar')?></label>
                                <input type="email" class="form-control editAditivoSim" id="email_financeiro_sim" name="email[email_financeiro]" required>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?=lang('valor_ativacao_por_chip')?></label>
                                <input type="text" class="form-control moeda" id="valor_ativacao_chip_sim" name="termo[valor_ativacao_chip]" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?=lang('venc_ativacao')?></label>
                                <input type="date" class="form-control date_sim" id="venc_ativacao_sim" name="termo[venc_ativacao]" required>
                            </div>
              <div class="form-group">
                                 <label class="control-label"><?=lang('taxa_de_envio')?></label>
                                 <input type="text" class="form-control moeda" id="taxa_envio_sim" name="termo[taxa_envio]" required>
                             </div>
                        </div>
                        <div class="form-group col-md-6">
              <div class="form-group">
                                <label class="control-label"><?=lang('contato_contas_pagar')?></label>
                                <input type="text" class="form-control" id="contato_contas_pagar_sim" name="termo[contato_contas_pagar]" required>
                            </div>
              <div class="form-group">
                                 <label class="control-label"><?=lang('valor_mensalidade_por_chip')?></label>
                                 <input type="text" class="form-control moeda" id="valor_mensalidade_chip_sim" name="termo[valor_mensalidade_chip]" required>
                             </div>
               <div class="form-group">
                                 <label class="control-label"><?=lang('dia_vencimento')?>:</label>
                                 <select class="form-control" id="data_vencimento_sim" name="termo[data_vencimento]" required>
                   <option>10</option>
                                <option>20</option>
                                <option>30</option>
                                 </select>
                             </div>
               <div class="form-group">
                                 <label class="control-label"><?=lang('venc_mensalidade')?></label>
                                 <input type="date" class="form-control date_sim" id="primeiro_vencimento_mensalidade_sim" name="termo[primeiro_vencimento_mensalidade]" required>
                             </div>
               <div class="form-group">
                                 <label><?=lang('observacao')?></label>
                                 <input class="form-control" type="text" id="observacoes_sim" name="termo[observacoes]">
                             </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12">
                            <div class="botoes_resposta" style="float: right;">
                                <button type="reset" class="btn btnResetSim"> <?=lang('limpar')?></button>
                                <button type="submit" class="btn btn-primary btnNovoTermoSim" data-acao="novo" > <?=lang('salvar')?></button>
                            </div>
                        </div>
                    </div>
              </form>
          </div>
      </div>
  </div>
</div>
<script type="text/javascript">
  var tabelaShow = false;
  var tabelaSim = false;
  var tabelaCuritiba = false;
  var tabelaAditivos = false;
  var tableAditivosSim = false;
  var tabelaAditivosCuritiba = false;

    $(document).ready(function() {

        // DECLARA AS TABELAS
        if ($.fn.DataTable.isDataTable('#tableShow')) {
            return ;
        }
        tabelaShow = $('#tableShow').DataTable({
            responsive: true,
            processing: true,
            order: [0, 'desc'],
            otherOptions: {},
            initComplete: function() {
                $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
            },
            columns: [
                { data: 'id' },
                { data: 'razaoSocial' },
                { data: 'cpf_cnpj' },
                { data: 'prestadora' },
                { data: 'admin' }
            ],
            columnDefs: [ {"className": "dt-center", "targets": "_all"} ],
            "language": {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            }

        });

        //CARREGAMENTO DOS DADOS DA TABELA SHOW
        $.ajax({
            url: "<?= site_url('licitacao/listTermos') ?>",
            type: 'POST',
            dataType: 'JSON',
            data: {empresa: 1},
            beforeSend: function(){
            // criamos o loading
                $('#tableShow > tbody').html(
                  '<tr class="odd">' +
                    '<td valign="top" colspan="6" class="dataTables_empty">Carregando&hellip;</td>' +
                  '</tr>'
                );
            },
            success:function(data){
                if (data.status) {
                    tabelaShow.clear();
                    tabelaShow.rows.add(data.result);
                    tabelaShow.draw();
                }else {
                    tabelaShow.clear();
                }
            }
        });

        //CARREGAMENTO DOS DADOS DA TABELA SIMM2M
        $(document).on('click', '.sim', function() {
            if ($.fn.DataTable.isDataTable('#tableSim')) {
                return ;
            }
            tabelaSim = $('#tableSim').DataTable( {
                responsive: true,
                processing: true,
                order: [0, 'desc'],
                otherOptions: {},
                initComplete: function() {
                    $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
                },
                columns: [
                    { data: 'id' },
                    { data: 'razaoSocial' },
                    { data: 'cpf_cnpj' },
                    { data: 'prestadora' },
                    { data: 'admin' }
                ],
                columnDefs: [ {"className": "dt-center", "targets": "_all"} ],
                "language": {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_ resultados por página",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    }
                }
            });
            $.ajax({
                url: "<?= site_url('licitacao/listTermos') ?>",
                type: 'POST',
                dataType: 'JSON',
                data: { empresa: 2 },
                beforeSend: function(){
                // criamos o loading
                    $('#tableSim > tbody').html(
                      '<tr class="odd">' +
                        '<td valign="top" colspan="6" class="dataTables_empty">Carregando&hellip;</td>' +
                      '</tr>'
                    );
                },
                success:function(data){
                    if (data.status) {
                        tabelaSim.clear();
                        tabelaSim.rows.add(data.result);
                        tabelaSim.draw();
                    }else {
                        tabelaSim.clear();
                    }
                }
            });
        });

           //CARREGAMENTO DOS DADOS DA TABELA SHOWCURITIBA
           $(document).on('click', '.showCu', function() {
            if ($.fn.DataTable.isDataTable('#tablehowCuritiba')) {
                return ;
            }
            tabelaCuritiba   = $('#tablehowCuritiba').DataTable( {
                responsive: true,   
                processing: true,
                order: [0, 'desc'],
                otherOptions: {},
                initComplete: function() {
                    $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
                },
                columns: [
                    { data: 'id' },
                    { data: 'razaoSocial' },
                    { data: 'cpf_cnpj' },
                    { data: 'prestadora' },
                    { data: 'admin' }
                ],
                columnDefs: [ {"className": "dt-center", "targets": "_all"} ],
                "language": {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_ resultados por página",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    }
                }
            });
            $.ajax({
                url: "<?= site_url('licitacao/listTermos') ?>",
                type: 'POST',
                dataType: 'JSON',
                data: { empresa: 3 },
                beforeSend: function(){
                // criamos o loading
                    $('#tablehowCuritiba > tbody').html(
                      '<tr class="odd">' +
                        '<td valign="top" colspan="6" class="dataTables_empty">Carregando&hellip;</td>' +
                      '</tr>'
                    );
                },
                success:function(data){
                    if (data.status) {
                        tabelaCuritiba.clear();
                        tabelaCuritiba.rows.add(data.result);
                        tabelaCuritiba.draw();
                    }else {
                        tabelaCuritiba.clear();
                    }
                }
            });
        });

        //CARREGA DADOS DO MODAL "NOVO TERMO SHOW"
        $(document).on('click', '.add_termo_show', function(e) {
            e.preventDefault();
            abrirModalNovoTermoShow('SHOW');
        });

        $(document).on('click', '.add_termo_show_curitiba', function(e) {
            e.preventDefault();
            abrirModalNovoTermoShow('SHOW_CURITIBA');
        });

        function abrirModalNovoTermoShow (empresa) {
            //LIMPA OS CAMPOS DO MODAL/FORMULARIO/MENSAGEM
            limparDadosModal('termo_alert', 'formNovoShow', 'executivo_vendas', 'Show', '.date');

            var tipoTermo = $(this).attr('data-tipoTermo');
            //abre o modal
            $('#novoTermoShow').modal();

            if (tipoTermo == 'fisico') {
                var imputCpf_cnpj = '<label style="width: 100%;"><?=lang('cpf')?></label><br>'+
                                    '<input id="cpf" type="text" name="cliente[cnpj_cpf]" class="cpf form-control" required maxlength="18" value="">';
            }else {

                var imputCpf_cnpj = '<label style="width: 100%;"><?=lang('cnpj')?> <span class="text-info" style="font-size: smaller;">"<?=lang('inf_receita_federal')?>"</span></label>'+
                                    '<input id="cnpj" type="text" class="cnpj form-control" name="cliente[cnpj_cpf]" value="" required maxlength="18" style="width: 80%; float:left;">'+
                                    '<button type="button" class="btn btn-small btn-info dadosReceitaFederal" data-empresa="1" style="margin-left: 10px;"><i class="fa fa-search"></i></button>';

                $('.readyOnly').attr('readonly', 'readonly');
            }
            $('#imputCpf_cnpj').html(imputCpf_cnpj);

            if(empresa == 'SHOW_CURITIBA'){
                $('#empresa').attr('value', 3);
            }
            else{
                $('#empresa').attr('value', 1);
            }
        }

        //BUSCA O EXECUTIVO DE VENDAS TERMO SHOW
        $('#executivo_vendas').select2({
            ajax: {
              url: '<?= site_url('usuarios/listarNomesUsuarios') ?>',
              dataType: 'json'
            },
            placeholder: "<?=lang('select_executivo_vendas')?>",
            allowClear: true
        });

        //CARREGA DADOS DO MODAL "NOVO TERMO SIMM2M"
        $(document).on('click', '.add_termo_sim', function(e) {
            e.preventDefault();
            //LIMPA OS CAMPOS DO MODAL/FORMULARIO/MENSAGEM
            limparDadosModal('termo_alert_sim', 'formNovoSim', 'executivo_vendas_sim', 'Sim', '.date_sim');

            var tipoTermo = $(this).attr('data-tipoTermo');
            //abre o modal
            $('#novoTermoSim').modal();

            if (tipoTermo == 'fisico') {
                var imputCpf_cnpj = '<label style="width: 100%;"><?=lang('cpf')?></label><br>'+
                                    '<input id="cpf" type="text" name="cliente[cnpj_cpf]" class="cpf form-control" required maxlength="18" value="">';
            }else {

                var imputCpf_cnpj = '<label style="width: 100%;"><?=lang('cnpj')?> <span class="text-info" style="font-size: smaller;">"<?=lang('inf_receita_federal')?>"</span></label>'+
                                    '<input id="cnpj" type="text" class="cnpj form-control" name="cliente[cnpj_cpf]" value="" required maxlength="18" style="width: 80%; float:left;">'+
                                    '<button type="button" class="btn btn-small btn-info dadosReceitaFederal" data-empresa="2" style="margin-left: 10px;"><i class="fa fa-search"></i></button>';

                $('.readyOnly').attr('readonly', 'readonly');
            }
            $('#imputCpf_cnpj_sim').html(imputCpf_cnpj);

        });

        //BUSCA O EXECUTIVO DE VENDAS TERMO SIM
      $('#executivo_vendas_sim').select2({
        ajax: {
          url: '<?= site_url('usuarios/listarNomesUsuarios') ?>',
          dataType: 'json'
        },
        placeholder: "<?=lang('select_executivo_vendas')?>",
        allowClear: true
      });

    });

    //ENVIA OS DADOS DO FORMULARIO PARA
    $("#formNovoShow").submit(function(e){
    e.preventDefault();
    var dadosform = $(this).serialize();
    var botao = $('.btnNovoTermoShow');
    var url = "<?= site_url('licitacao/salvarTermo') ?>/1";
    var acao = botao.attr('data-acao');

    if (acao == 'editar') {
      var id_termo = botao.attr('data-id_termo');
      var id_cliente = botao.attr('data-id_cliente');
      var id_endereco = botao.attr('data-id_endereco');
      var id_endereco_entrega = botao.attr('data-id_endereco_entrega');
      var id_email = botao.attr('data-id_email');
      var id_email_financeiro = botao.attr('data-id_email_financeiro');
      var id_fone_cel = botao.attr('data-id_fone_cel');
      var id_fone_fixo = botao.attr('data-id_fone_fixo');
      dadosform = dadosform+'&id_termo='+id_termo+'&empresa=1&id_cliente='+id_cliente+'&id_endereco='+id_endereco+'&id_endereco_entrega='+id_endereco_entrega;
      dadosform = dadosform+'&id_email='+id_email+'&id_email_financeiro='+id_email_financeiro+'&id_fone_cel='+id_fone_cel+'&id_fone_fixo='+id_fone_fixo;
      url = "<?= site_url('licitacao/editTermo') ?>";

    }else if (acao == 'novoAditivo') {
      url = "<?= site_url('licitacao/salvarAditivo') ?>";
      var id_termo = botao.attr('data-id_termo');
      var executivo_vendas = $("#executivo_vendas").val();
      dadosform = dadosform+'&id_termo='+id_termo+'&empresa=1&termo[executivo_vendas]='+executivo_vendas;

    }else if (acao == 'editAditivo') {
      url = "<?= site_url('licitacao/editAditivo') ?>";
      var id_termo = botao.attr('data-id_termo');
      var executivo_vendas = $("#executivo_vendas").val();
      dadosform = dadosform+'&id_termo='+id_termo+'&empresa=1&termo[executivo_vendas]='+executivo_vendas;
    };
         $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            data: dadosform,
            beforeSend: function(){
                botao.html('<i class="fa fa-spinner fa-spin"></i> Salvando').attr('disabled', true);
                $('.termo_alert').css('display','block');
            },
            success: function (data) {
                if (data.success) {
          if (acao == 'novo') {
            //ADICIONA O NOVO TERMO NA DATATABLE
            var dados = data.retorno;
            var id_termo = dados.id;
            var razao_social = dados.razaoSocial;
            var cpfCnpj = dados.cpf_cnpj;
            var empresa = dados.prestadora;
            var administrar = dados.admin;

                      tabelaShow.rows.add(
              [{
                id: id_termo,
                razaoSocial: razao_social,
                cpf_cnpj: cpfCnpj,
                prestadora: empresa,
                admin: administrar
              }]
            ).draw(null, false);
          }
                    $('#msn_termo').html('<div class="alert alert-success"><p><b>'+data.msg+'</b></p></div>');
                }else {
                    $('#msn_termo').html('<div class="alert alert-danger"><p><b>'+data.msg+'</b></p></div>');
                }
            },
            error: function (data) {
                $('#msn_termo').html('<div class="alert alert-danger"><p><b>'+data.msg+'</b></p></div>');
            },
            complete: function(data){
                botao.html('Salvar').attr('disabled', false);
                //vai para o topo do modal para mostrar a mensagem ao usuario
                $('#novoTermoShow').animate({ scrollTop: 0 }, 'slow');

            }
        });
    });

  //ENVIA OS DADOS DO FORMULARIO
    $("#formNovoSim").submit(function(e){
    e.preventDefault();
    var dadosform = $(this).serialize();
    var botao = $('.btnNovoTermoSim');
    var url = "<?= site_url('licitacao/salvarTermo') ?>/2";
    var acao = botao.attr('data-acao');

    if (acao == 'editar') {
      var id_termo = botao.attr('data-id_termo');
      var id_cliente = botao.attr('data-id_cliente');
      var id_endereco = botao.attr('data-id_endereco');
      var id_endereco_entrega = botao.attr('data-id_endereco_entrega');
      var id_email = botao.attr('data-id_email');
      var id_email_financeiro = botao.attr('data-id_email_financeiro');
      var id_fone_cel = botao.attr('data-id_fone_cel');
      var id_fone_fixo = botao.attr('data-id_fone_fixo');
      dadosform = dadosform+'&id_termo='+id_termo+'&empresa=2&id_cliente='+id_cliente+'&id_endereco='+id_endereco+'&id_endereco_entrega='+id_endereco_entrega;
      dadosform = dadosform+'&id_email='+id_email+'&id_email_financeiro='+id_email_financeiro+'&id_fone_cel='+id_fone_cel+'&id_fone_fixo='+id_fone_fixo;
      url = "<?= site_url('licitacao/editTermo') ?>";

    }else if (acao == 'novoAditivo') {
      url = "<?= site_url('licitacao/salvarAditivo') ?>";
      var id_termo = botao.attr('data-id_termo');
      var executivo_vendas = $("#executivo_vendas_sim").val();
      dadosform = dadosform+'&id_termo='+id_termo+'&empresa=2&termo[executivo_vendas]='+executivo_vendas;

    }else if (acao == 'editAditivo') {
      url = "<?= site_url('licitacao/editAditivo') ?>";
      var id_termo = botao.attr('data-id_termo');
      var executivo_vendas = $("#executivo_vendas").val();
      dadosform = dadosform+'&id_termo='+id_termo+'&empresa=2&termo[executivo_vendas]='+executivo_vendas;
    }

         $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            data: dadosform,
            beforeSend: function(){
                botao.html('<i class="fa fa-spinner fa-spin"></i> Salvando').attr('disabled', true);
                $('.termo_alert_sim').css('display','block');
            },
            success: function (data) {
                if (data.success) {
          if (acao == 'novo') {
            //ADICIONA O NOVO TERMO NA DATATABLE
            var dados = data.retorno;
            var id_termo = dados.id;
                      var razao_social = dados.razaoSocial;
            var cpfCnpj = dados.cpf_cnpj;
            var empresa = dados.prestadora;
            var administrar = dados.admin;

                      tabelaSim.rows.add(
              [{
                id: id_termo,
                razaoSocial: razao_social,
                cpf_cnpj: cpfCnpj,
                prestadora: empresa,
                admin: administrar
              }]
            ).draw(null, false);
          }
                    $('#msn_termo_sim').html('<div class="alert alert-success"><p><b>'+data.msg+'</b></p></div>');
                }else {
                    $('#msn_termo_sim').html('<div class="alert alert-danger"><p><b>'+data.msg+'</b></p></div>');
                }
            },
            error: function (data) {
                $('#msn_termo_sim').html('<div class="alert alert-danger"><p><b>'+data.msg+'</b></p></div>');
            },
            complete: function(data){
                botao.html('Salvar').attr('disabled', false);
                //vai para o topo do modal para mostrar a mensagem ao usuario
                $('#novoTermoSim').animate({ scrollTop: 0 }, 'slow');

            }
        });
    });

    //CARREGA DADOS PARA EDITAR TERMO SHOW
    $(document).on('click', '.btn_editTermo', function() {
        //LIMPA OS CAMPOS DO MODAL/FORMULARIO/MENSAGEM
        limparDadosModal('termo_alert', 'formNovoShow', 'executivo_vendas', 'Show', 'date');
        var botao = $(this);
        var id_termo = botao.attr('data-id');

          $.ajax({
             url: "<?= site_url('licitacao/ajaxloadTermo') ?>",
             type: 'POST',
             dataType: 'JSON',
             data: {
                 id_termo: id_termo,
                 empresa: 1
             },
             beforeSend: function(){
                 botao.html('<i class="fa fa-spinner fa-spin"></i>').attr('disabled', true);
             },
             success: function (data) {
                 if (data.success) {
                     //PEGA OS CAMPOS DO FORMULARIO
                    var termo = data.termo;
                    var ids = data.ids;
                    var chavesSelects = ['tipo_contrato', 'bloqueio', 'periodo_contrato', 'dt_vencimento', 'inst_sigilosa'];
                    var camposDatas = ['primeiro_venc_adesao', 'primeiro_venc_mens', 'primeiro_venc_adicional'];

                    Object.keys(termo).forEach( item => {

                        //SE O CAMPO FOR CPF
                        if (item == "cnpj_cpf" && termo[item].length <= 14) {
                        var imputCpf_cnpj = '<label><?=lang('cpf')?></label><br>'+
                                                '<input id="cnpj_cpf" type="text" name="cliente[cnpj_cpf]" class="cpf" required maxlength="18" value="" style="width: 80%;">';
                            $('#imputCpf_cnpj').html(imputCpf_cnpj);
                            $('.readyOnly').attr('readonly', false);

                        //SE O CAMPO FOR CNPJ
                        }else if (item == "cnpj_cpf" && termo[item].length > 14) {
                            var imputCpf_cnpj = '<label><?=lang('cnpj')?> <span class="text-info" style="font-size: smaller;">"<?=lang('inf_receita_federal')?>"</span></label>'+
                                                '<input id="cnpj_cpf" type="text" class="cnpj" name="cliente[cnpj_cpf]" value="" required maxlength="18" style="width: 80%;">'+
                                                '<button type="button" class="btn btn-small btn-info dadosReceitaFederal" style="margin-left: 10px;"><i class="fa fa-search"></i></button>';

                            $('#imputCpf_cnpj').html(imputCpf_cnpj);
                            $('#cnpj').attr('readonly', true);
                            $('.readyOnly').attr('readonly', 'readonly');
                            $('.dadosReceitaFederal').attr('disabled', true);
                        }

                        //ADICIONA O EXECUTIVO DE VENDAS NO SELECT2
                        if (item == "executivo_vendas"){
                            var newOption = new Option(termo[item], termo[item], true, true);
                            $('#executivo_vendas').append(newOption).trigger('change');
                        }

                        //ADICIONA OS VALORES DOS CAMPOS
                        $('#'+item).val(termo[item]);

                        //ADICIONA OS VALORES DOS SELECTS
                        if (chavesSelects.indexOf(item) != -1) {
                            $('#'+item).val(termo[item]).prop('selected', true);
                        }

                        //ADICIONA OS LIMITES MIN E MAX PARA IMPUTS DE DATAS
                        if (camposDatas.indexOf(item) != -1) {
                            minMaxDataImput('#'+item, new Date(termo[item]+' 05:00:00')); //configura os limites dos imputs de datas
                        }

                    });

                    //REMOVE O BOTAO 'LIMPAR' DO FORMULARIO
                    $('.btnResetShow').css('display', 'none');
                    //CONFIGURA BOTAO PARA DIRECIONAR O FORMARIO PARA EDICAO EM VEZ DE CRIA UM NOVO TERMO
                    $('.btnNovoTermoShow').attr('data-acao', 'editar');
                    // $('.btnNovoTermoShow').attr('data-empresa', empresa);
                    $('.btnNovoTermoShow').attr('data-id_termo', id_termo);
                    $('.btnNovoTermoShow').attr('data-id_cliente', ids.id_cliente );
                    $('.btnNovoTermoShow').attr('data-id_endereco', ids.id_endereco );
                    $('.btnNovoTermoShow').attr('data-id_endereco_entrega', ids.id_endereco_entrega );
                    $('.btnNovoTermoShow').attr('data-id_email', ids.id_email );
                    $('.btnNovoTermoShow').attr('data-id_email_financeiro', ids.id_email_financeiro );
                    $('.btnNovoTermoShow').attr('data-id_fone_cel', ids.id_fone_cel );
                    $('.btnNovoTermoShow').attr('data-id_fone_fixo', ids.id_fone_fixo );

                    $('#tituloModalShow').html("<?=lang('editar_termo')?> #"+id_termo);  //altera o titulo do modal

                    //ABRE MODAL
                    $('#novoTermoShow').modal();

                 }else {
           $('.termo_alert_load').css('display','block');
           $('#msn_load').html('<div class="alert alert-danger"><p><b>'+data.msn+'</b></p></div>');
                 }
             },
             complete: function(data){
                 botao.html('<i class="fa fa-edit"></i>').attr('disabled', false);
             }
         });
    });

  //CARREGA DADOS PARA EDITAR TERMO SHOW
    $(document).on('click', '.btn_editTermo_sim', function() {
    //LIMPA OS CAMPOS DO MODAL/FORMULARIO/MENSAGEM
    limparDadosModal('termo_alert_sim', 'formNovoSim', 'executivo_vendas_sim', 'Sim', 'date_sim');
        var botao = $(this);
    var id_termo = botao.attr('data-id');
          $.ajax({
             url: "<?= site_url('licitacao/ajaxloadTermo') ?>",
             type: 'POST',
             dataType: 'JSON',
             data: {
                 id_termo: id_termo,
                 empresa: 2
             },
             beforeSend: function(){
                 botao.html('<i class="fa fa-spinner fa-spin"></i>').attr('disabled', true);
             },
             success: function (data) {
                 if (data.success) {
                     //PEGA OS CAMPOS DO FORMULARIO
           var termo = data.termo;
           var ids = data.ids;
           var chavesSelects = ['tipo_contrato', 'periodo_contrato', 'data_vencimento'];
           var camposDatas = ['venc_ativacao', 'primeiro_vencimento_mensalidade'];

           Object.keys(termo).forEach( item => {

             //SE O CAMPO FOR CPF
             if (item == "cnpj_cpf" && termo[item].length <= 14) {
              var imputCpf_cnpj = '<label><?=lang('cpf')?></label><br>'+
                                      '<input id="cnpj_cpf_sim" type="text" name="cliente[cnpj_cpf]" class="cpf" required maxlength="18" value="" style="width: 80%;">';
                $('#imputCpf_cnpj_sim').html(imputCpf_cnpj);
              $('.readyOnly').attr('readonly', false);

            //SE O CAMPO FOR CNPJ
            }else if (item == "cnpj_cpf" && termo[item].length > 14) {
              var imputCpf_cnpj = '<label><?=lang('cnpj')?> <span class="text-info" style="font-size: smaller;">"<?=lang('inf_receita_federal')?>"</span></label>'+
                                   '<input id="cnpj_cpf_sim" type="text" class="cnpj" name="cliente[cnpj_cpf]" value="" required maxlength="18" style="width: 80%;">'+
                                   '<button type="button" class="btn btn-small btn-info dadosReceitaFederal" style="margin-left: 10px;"><i class="fa fa-search"></i></button>';

              $('#imputCpf_cnpj_sim').html(imputCpf_cnpj);
              $('#cnpj_sim').attr('readonly', true);
              $('.readyOnly').attr('readonly', 'readonly');
              $('.dadosReceitaFederal').attr('disabled', true);
            }

            //ADICIONA O EXECUTIVO DE VENDAS NO SELECT2
            if (item == "executivo_vendas"){
               var newOption = new Option(termo[item], termo[item], true, true);
               $('#executivo_vendas_sim').append(newOption).trigger('change');
            }

            //ADICIONA OS VALORES DOS CAMPOS
            $('#'+item+'_sim').val(termo[item]);

            //ADICIONA OS VALORES DOS SELECTS
            if (chavesSelects.indexOf(item) != -1) {
              $('#'+item+'_sim').val(termo[item]).prop('selected', true);
            }

            //ADICIONA OS LIMITES MIN E MAX PARA IMPUTS DE DATAS
            if (camposDatas.indexOf(item) != -1) {
              minMaxDataImput('#'+item+'_sim', new Date(termo[item]+' 05:00:00')); //configura os limites dos imputs de datas
            }

           });

           //REMOVE O BOTAO 'LIMPAR' DO FORMULARIO
           $('.btnResetSim').css('display', 'none');
           //CONFIGURA BOTAO PARA DIRECIONAR O FORMARIO PARA EDICAO EM VEZ DE CRIA UM NOVO TERMO
           $('.btnNovoTermoSim').attr('data-acao', 'editar');
           $('.btnNovoTermoSim').attr('data-id_termo', id_termo);
           $('.btnNovoTermoSim').attr('data-id_cliente', ids.id_cliente );
           $('.btnNovoTermoSim').attr('data-id_endereco', ids.id_endereco );
           $('.btnNovoTermoSim').attr('data-id_endereco_entrega', ids.id_endereco_entrega );
           $('.btnNovoTermoSim').attr('data-id_email', ids.id_email );
           $('.btnNovoTermoSim').attr('data-id_email_financeiro', ids.id_email_financeiro );
           $('.btnNovoTermoSim').attr('data-id_fone_cel', ids.id_fone_cel );
           $('.btnNovoTermoSim').attr('data-id_fone_fixo', ids.id_fone_fixo );

           //ALTERA O TITULO DO MODAL
           $('#tituloModalSim').html("<?=lang('editar_termo')?> #"+id_termo);

           //ABRE MODAL
                      $('#novoTermoSim').modal();
                 }
             },
             complete: function(data){
                 botao.html('<i class="fa fa-edit"></i>').attr('disabled', false);
             }
         });
    });

  //LISTA OS ADITIVOS DO TERMO
    $(document).on('click', '.btn_aditivos', function() {
    var botao = $(this);
    var id_termo = botao.attr('data-id');

    $('.btn_aditivos').removeClass('btn-danger').addClass('btn-primary').html('<i class="fa fa-eye"></i>');
    botao.removeClass('btn-primary').addClass('btn-danger').html('<i class="fa fa-eye-slash"></i>');

      var tr = $(this).closest('tr');
      var row = tabelaShow.row( tr );

      if ( row.child.isShown() ) {
          // ESCONDE A LINHA FILHA
          botao.removeClass('btn-danger').addClass('btn-primary').html('<i class="fa fa-eye"></i>');
          row.child.hide();

      }else {
         //ESCONDE TODAS AS LINHAS FILHAS ABERTAS
         tabelaShow.rows().eq(0).each( function ( idx ) {
              var linha = tabelaShow.row( idx );
              if (linha.child.isShown())
          linha.child.hide();
          });

          // ABRE A LINHA FILHA
          row.child( '<div style="background-color: #337ab7; padding: 10px;">'+
                        '<div id="aditivoSpan'+id_termo+'">'+
                            '<p style="color:#ffffff;">Carregando...</p>'+
                        '</div>'+
                    '</div>'
          ).show();

      //CARREGA O CONTEUDO DA LINHA
            //cria a tabela dinamicamente
            var th =
      '<h3 style="color:#ffffff;"><?=lang('aditivos')?></h3>'+
      '<table id="tableAditivos" class="table table-hover responsive display">'+
        '<thead>'+
                    '<th class="span1">ID</th>'+
                    '<th class="span3"><?=lang('data_cadastro')?></th>'+
                    '<th class="span3"><?=lang('administrar')?></th>'+
        '</thead>'+
        '<tbody></tbody>'+
      '</table>';

      $('#aditivoSpan'+id_termo).html(th);

            tableAditivos = $('#tableAditivos').DataTable( {
                responsive: true,
        paging: false,
        searching: false,
        destroy: true,
        lengthChange: false,
        order: [[ 0, 'desc' ]],
        ajax:{
          url: "<?=site_url('licitacao/ajaxLoadAditivos')?>",
          type: 'POST',
          data: {
            id_termo: id_termo,
            empresa: 1
          }
            },
        columns: [
                    { data: 'id' },
                    { data: 'data_cadastro' },
                    { data: 'admin' }
                ],
                "columnDefs": [
                    {
                        "className": "dt-center",
                        "targets": "_all"
                    }
                ],
        oLanguage: {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "",
                    "sInfoEmpty": "",
                    "sInfoFiltered": "",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    }
                }
            });

      }
    });

  //LISTA OS ADITIVOS DO TERMO
    $(document).on('click', '.btn_aditivosCuritiba', function() {
    var botao = $(this);
    var id_termo = botao.attr('data-id');

    $('.btn_aditivosCuritiba').removeClass('btn-danger').addClass('btn-primary').html('<i class="fa fa-eye"></i>');
    botao.removeClass('btn-primary').addClass('btn-danger').html('<i class="fa fa-eye-slash"></i>');

      var tr = $(this).closest('tr');
      var row = tabelaCuritiba.row( tr );

      if ( row.child.isShown() ) {
          // ESCONDE A LINHA FILHA
          botao.removeClass('btn-danger').addClass('btn-primary').html('<i class="fa fa-eye"></i>');
          row.child.hide();

      }else {
         //ESCONDE TODAS AS LINHAS FILHAS ABERTAS
         tabelaCuritiba.rows().eq(0).each( function ( idx ) {
              var linha = tabelaCuritiba.row( idx );
              if (linha.child.isShown())
          linha.child.hide();
          });

          // ABRE A LINHA FILHA
          row.child( '<div style="background-color: #337ab7; padding: 10px;">'+
                        '<div id="aditivoSpan'+id_termo+'">'+
                            '<p style="color:#ffffff;">Carregando...</p>'+
                        '</div>'+
                    '</div>'
          ).show();

      //CARREGA O CONTEUDO DA LINHA
            //cria a tabela dinamicamente
            var th =
      '<h3 style="color:#ffffff;"><?=lang('aditivos')?></h3>'+
      '<table id="tableAditivos" class="table table-hover responsive display">'+
        '<thead>'+
                    '<th class="span1">ID</th>'+
                    '<th class="span3"><?=lang('data_cadastro')?></th>'+
                    '<th class="span3"><?=lang('administrar')?></th>'+
        '</thead>'+
        '<tbody></tbody>'+
      '</table>';

      $('#aditivoSpan'+id_termo).html(th);

            tableAditivosCuritiba = $('#tableAditivos').DataTable( {
                responsive: true,
        paging: false,
        searching: false,
        destroy: true,
        lengthChange: false,
        order: [[ 0, 'desc' ]],
        ajax:{
          url: "<?=site_url('licitacao/ajaxLoadAditivos')?>",
          type: 'POST',
          data: {
            id_termo: id_termo,
            empresa: 1
          }
            },
        columns: [
                    { data: 'id' },
                    { data: 'data_cadastro' },
                    { data: 'admin' }
                ],
                "columnDefs": [
                    {
                        "className": "dt-center",
                        "targets": "_all"
                    }
                ],
        oLanguage: {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "",
                    "sInfoEmpty": "",
                    "sInfoFiltered": "",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    }
                }
            });

      }
    });

  //CARREGA DADOS PARA CRIAR UM ADITIVO PARA O TERMO SHOW
    $(document).on('click', '.btn_addEditAditivos', function() {
    //LIMPA OS CAMPOS DO MODAL/FORMULARIO/MENSAGEM
    limparDadosModal('termo_alert', 'formNovoShow', 'executivo_vendas', 'Show', 'date');
    var botao = $(this);
    var id_termo = botao.attr('data-id');
    var acao = botao.attr('data-acao');
    var url = "<?= site_url('licitacao/ajaxloadDadosAditivo') ?>";

    if (acao === 'editar') {
      var url = "<?= site_url('licitacao/ajaxloadDadosAditivo') ?>/1";
    }
    $.ajax({
       url: url,
       type: 'POST',
       dataType: 'JSON',
       data: {
           id_termo: id_termo,
           empresa: 1
       },
       beforeSend: function(){
           botao.html('<i class="fa fa-spinner fa-spin"></i>').attr('disabled', true);
       },
       success: function (data) {
           if (data.success) {
               //PEGA OS CAMPOS DO FORMULARIO
           var termo = data.termo;
           var ids = data.ids;
           var chavesSelects = ['tipo_contrato', 'bloqueio', 'periodo_contrato', 'dt_vencimento', 'inst_sigilosa'];
           var camposDatas = ['primeiro_venc_adesao', 'primeiro_venc_mens', 'primeiro_venc_adicional'];

           //ADICIONA OS LIMITES MIN E MAX PARA IMPUTS DE DATAS
            if (acao !== 'editar'){
                camposDatas.forEach(element => {
                    minMaxDataImput('#'+element, new Date(), 0, 0, 2); //configura os limites dos imputs de datas       
                });
            }

           Object.keys(termo).forEach( item => {

                //SE O CAMPO FOR CPF
                if (item == "cnpj_cpf" && termo[item].length <= 14) {
                var imputCpf_cnpj = '<label><?=lang('cpf')?></label><br>'+
                    '<input id="cnpj_cpf" type="text" name="cliente[cnpj_cpf]" class="cpf editAditivo" required maxlength="18" value="" style="width: 80%;">';

                $('#imputCpf_cnpj').html(imputCpf_cnpj).attr('readonly', 'readonly');

                //SE O CAMPO FOR CNPJ
                }else if (item == "cnpj_cpf" && termo[item].length > 14) {
                var imputCpf_cnpj = '<label><?=lang('cnpj')?> <span class="text-info" style="font-size: smaller;">"<?=lang('inf_receita_federal')?>"</span></label>'+
                   '<input id="cnpj_cpf" type="text" class="cnpj editAditivo" name="cliente[cnpj_cpf]" required maxlength="18" style="width: 80%;">'+
                   '<button type="button" class="btn btn-small btn-info dadosReceitaFederal" style="margin-left: 10px;"><i class="fa fa-search"></i></button>';

                    $('#imputCpf_cnpj').html(imputCpf_cnpj);
                    $('.dadosReceitaFederal').attr('disabled', true);
                }

                //ADICIONA O EXECUTIVO DE VENDAS NO SELECT2
                if (item == "executivo_vendas"){
                    var newOption = new Option(termo[item], termo[item], true, true);
                    $('#executivo_vendas').append(newOption).trigger('change').attr('disabled', 'readonly');
                }

                //ADICIONA OS VALORES DOS CAMPOS
                $('#'+item).val(termo[item]);

                //ADICIONA OS VALORES DOS SELECTS
                if (chavesSelects.indexOf(item) != -1) {
                    $('#'+item).val(termo[item]).prop('selected', true);
                }

                //DESABILITA TODOS OS INPUTS QUE NAO DEVEM SER ALTERADOS
                $('.editAditivo').attr('readonly', 'readonly');


                //ADICIONA OS LIMITES MIN E MAX PARA IMPUTS DE DATAS
                if (camposDatas.indexOf(item) != -1) {
                    minMaxDataImput('#'+item, new Date(termo[item]+' 05:00:00')); //configura os limites dos imputs de datas
                }

           });

            //REMOVE O BOTAO 'LIMPAR' DO FORMULARIO
            $('.btnResetShow').css('display', 'none');
            //CONFIGURA BOTAO PARA DIRECIONAR O FORMARIO PARA EDICAO EM VEZ DE CRIA UM NOVO TERMO
            if (acao === 'editar') {
                $('.btnNovoTermoShow').attr('data-acao', 'editAditivo');
                $('.btnNovoTermoShow').attr('data-id_termo', id_termo);
                $('#tituloModalShow').html("<?=lang('edit_aditivo')?> #"+id_termo);  //altera o titulo do modal
            }else {
                $('.btnNovoTermoShow').attr('data-acao', 'novoAditivo');
                $('.btnNovoTermoShow').attr('data-id_termo', id_termo);
                $('#tituloModalShow').html("<?=lang('add_aditivo')?> #"+id_termo);  //altera o titulo do modal
            }

            //ABRE MODAL
            $('#novoTermoShow').modal();

           }else {
               $('.termo_alert_load').css('display','block');
               $('#msn_load').html('<div class="alert alert-danger"><p><b>'+data.msn+'</b></p></div>');
           }
       },
       complete: function(data){

         if (acao === 'editar')
          botao.html('<i class="fa fa-edit"></i>').attr('disabled', false);
        else
          botao.html('<i class="fa fa-plus"></i>').attr('disabled', false);

       }
    });
    });

  //LISTA OS ADITIVOS DO TERMO
    $(document).on('click', '.btn_aditivos_sim', function() {
    var botao = $(this);
    var id_termo = botao.attr('data-id');

    $('.btn_aditivos_sim').removeClass('btn-danger').addClass('btn-primary').html('<i class="fa fa-eye"></i>');
    botao.removeClass('btn-primary').addClass('btn-danger').html('<i class="fa fa-eye-slash"></i>');

      var tr = $(this).closest('tr');
      var row = tabelaSim.row( tr );

      if ( row.child.isShown() ) {
          // ESCONDE A LINHA FILHA
          botao.removeClass('btn-danger').addClass('btn-primary').html('<i class="fa fa-eye"></i>');
          row.child.hide();

      }else {
         //ESCONDE TODAS AS LINHAS FILHAS ABERTAS
         tabelaSim.rows().eq(0).each( function ( idx ) {
              var linha = tabelaSim.row( idx );
              if (linha.child.isShown())
          linha.child.hide();
          });

          // ABRE A LINHA FILHA
          row.child( '<div style="background-color: #337ab7; padding: 10px;">'+
                        '<div id="aditivoSpan'+id_termo+'">'+
                            '<p style="color:#ffffff;">Carregando...</p>'+
                        '</div>'+
                    '</div>'
          ).show();

      //CARREGA O CONTEUDO DA LINHA
            //cria a tabela dinamicamente
            var th =
      '<h3 style="color:#ffffff;"><?=lang('aditivos')?></h3>'+
      '<table id="tableAditivosSim" class="table table-hover responsive display">'+
        '<thead>'+
                    '<th class="span1">ID</th>'+
                    '<th class="span3"><?=lang('data_cadastro')?></th>'+
                    '<th class="span3"><?=lang('administrar')?></th>'+
        '</thead>'+
        '<tbody></tbody>'+
      '</table>';

      $('#aditivoSpan'+id_termo).html(th);

            tableAditivosSim = $('#tableAditivosSim').DataTable( {
                responsive: true,
        paging: false,
        destroy: true,
        searching: false,
        lengthChange: false,
        order: [[ 0, 'desc' ]],
        ajax:{
          url: "<?=site_url('licitacao/ajaxLoadAditivos')?>",
          type: 'POST',
          data: {
            id_termo: id_termo,
            empresa: 2
          }
            },
        columns: [
                    { data: 'id' },
                    { data: 'data_cadastro' },
                    { data: 'admin' }
                ],
                "columnDefs": [
                    {
                        "className": "dt-center",
                        "targets": "_all"
                    }
                ],
        oLanguage: {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "",
                    "sInfoEmpty": "",
                    "sInfoFiltered": "",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    }
                }
            });

      }
    });

  //CARREGA DADOS PARA CRIAR UM ADITIVO PARA O TERMO SHOW
    $(document).on('click', '.btn_addEditAditivos_sim', function() {
    //LIMPA OS CAMPOS DO MODAL/FORMULARIO/MENSAGEM
    limparDadosModal('termo_alert_sim', 'formNovoSim', 'executivo_vendas_sim', 'Sim', 'date_sim');
    var botao = $(this);
    var id_termo = botao.attr('data-id');
    var acao = botao.attr('data-acao');
    var url = "<?= site_url('licitacao/ajaxloadDadosAditivo') ?>";

    if (acao === 'editar') {
      var url = "<?= site_url('licitacao/ajaxloadDadosAditivo') ?>/1";
    }
    $.ajax({
       url: url,
       type: 'POST',
       dataType: 'JSON',
       data: {
           id_termo: id_termo,
           empresa: 2
       },
       beforeSend: function(){
           botao.html('<i class="fa fa-spinner fa-spin"></i>').attr('disabled', true);
       },
       success: function (data) {
           if (data.success) {
               //PEGA OS CAMPOS DO FORMULARIO
               var termo = data.termo;
               var ids = data.ids;
               var chavesSelects = ['tipo_contrato', 'periodo_contrato', 'data_vencimento'];
               var camposDatas = ['venc_ativacao', 'primeiro_vencimento_mensalidade'];

               Object.keys(termo).forEach( item => {

                    //SE O CAMPO FOR CPF
                    if (item == "cnpj_cpf" && termo[item].length <= 14) {
                    var imputCpf_cnpj = '<label><?=lang('cpf')?></label><br>'+
                                            '<input id="cnpj_cpf_sim" type="text" name="cliente[cnpj_cpf]" class="cpf editAditivoSim" required maxlength="18" value="" style="width: 80%;">';

                    $('#imputCpf_cnpj_sim').html(imputCpf_cnpj).attr('readonly', 'readonly');

                    //SE O CAMPO FOR CNPJ
                    }else if (item == "cnpj_cpf" && termo[item].length > 14) {
                    var imputCpf_cnpj = '<label><?=lang('cnpj')?> <span class="text-info" style="font-size: smaller;">"<?=lang('inf_receita_federal')?>"</span></label>'+
                                       '<input id="cnpj_cpf_sim" type="text" class="cnpj editAditivoSim" name="cliente[cnpj_cpf]" required maxlength="18" style="width: 80%;">'+
                                       '<button type="button" class="btn btn-small btn-info dadosReceitaFederal" style="margin-left: 10px;"><i class="fa fa-search"></i></button>';

                    $('#imputCpf_cnpj_sim').html(imputCpf_cnpj);
                    $('.dadosReceitaFederal').attr('disabled', true);
                    }

                    //ADICIONA O EXECUTIVO DE VENDAS NO SELECT2
                    if (item == "executivo_vendas"){
                        var newOption = new Option(termo[item], termo[item], true, true);
                        $('#executivo_vendas_sim').append(newOption).trigger('change').attr('disabled', 'readonly');
                    }

                    //ADICIONA OS VALORES DOS CAMPOS
                    $('#'+item+'_sim').val(termo[item]);

                    //ADICIONA OS VALORES DOS SELECTS
                    if (chavesSelects.indexOf(item) != -1) {
                        $('#'+item+'_sim').val(termo[item]).prop('selected', true);
                    }

                    //ADICIONA OS LIMITES MIN E MAX PARA IMPUTS DE DATAS
                    if (camposDatas.indexOf(item) != -1) {
                        if (acao === 'editar') {
                            minMaxDataImput('#'+item+'_sim',new Date(termo[item]+' 05:00:00')); //configura os limites dos imputs de datas
                        }
                    }

                });

                //DESABILITA TODOS OS INPUTS QUE NAO DEVEM SER ALTERADOS
                $('.editAditivoSim').attr('readonly', 'readonly');

                //REMOVE O BOTAO 'LIMPAR' DO FORMULARIO
                $('.btnResetSim').css('display', 'none');
                //CONFIGURA BOTAO PARA DIRECIONAR O FORMARIO PARA EDICAO EM VEZ DE CRIA UM NOVO TERMO
                if (acao === 'editar') {
                    $('.btnNovoTermoSim').attr('data-acao', 'editAditivo');
                    $('.btnNovoTermoSim').attr('data-id_termo', id_termo);
                    $('#tituloModalSim').html("<?=lang('edit_aditivo')?> #"+id_termo);  //altera o titulo do modal
                }else {
                    $('.btnNovoTermoSim').attr('data-acao', 'novoAditivo');
                    $('.btnNovoTermoSim').attr('data-id_termo', id_termo);
                    $('#tituloModalSim').html("<?=lang('add_aditivo')?> #"+id_termo);  //altera o titulo do modal
                }

                //ABRE MODAL
                $('#novoTermoSim').modal();

                }else {
                    $('.termo_alert_load_sim').css('display','block');
                    $('#msn_load_sim').html('<div class="alert alert-danger"><p><b>'+data.msn+'</b></p></div>');
                }
            },
            complete: function(data){
                if (acao === 'editar')
                    botao.html('<i class="fa fa-edit"></i>').attr('disabled', false);
                else
                    botao.html('<i class="fa fa-plus"></i>').attr('disabled', false);

            }
        });
    });

    //EVENTOS DOS BOTOES ADMINISTRATIVOS
    $(document).on('click', '.btn_getTermo', function () {
        window.open('licitacao/page_print/'+$(this).data('id'), '_blank');
    });

    $(document).on('click', '.btn_getTermo_curitiba', function () {
        window.open('licitacao/page_print/'+$(this).data('id')+'/false/curitiba' , '_blank',);
    });


    $(document).on('click', '.btn_getTermo_sim', function () {
        window.open('licitacao/page_print/'+$(this).data('id')+'/1', '_blank');
    });

    //COPIA OS DADOS DO ENDERECO PRINCIPAL PARA O ENDERECO DE ENTREGA (SHOW)
    $('#copiar_endereco').click(function () {
        $('#rua_entrega').val($('#rua').val());
        $('#bairro_entrega').val($('#bairro').val());
        $('#cep_entrega').val($('#cep').val());
        $('#uf_entrega').val($('#uf').val());
        $('#complemento_entrega').val($('#complemento').val());
        $('#cidade_entrega').val($('#cidade').val());
    });

    //COPIA OS DADOS DO ENDERECO PRINCIPAL PARA O ENDERECO DE ENTREGA (SIM)
    $('#copiar_endereco_sim').click(function () {
        $('#rua_entrega_sim').val($('#rua_sim').val());
        $('#bairro_entrega_sim').val($('#bairro_sim').val());
        $('#cep_entrega_sim').val($('#cep_sim').val());
        $('#uf_entrega_sim').val($('#uf_sim').val());
        $('#complemento_entrega_sim').val($('#complemento_sim').val());
        $('#cidade_entrega_sim').val($('#cidade_sim').val());
    });

    //BUSCA CNPJ NO BANCO DA RECEITA FEDERAL
    $(document).on('click', '.dadosReceitaFederal', function(){
        var botao = $(this);
        var cnpj = document.getElementById("cnpj").value.replace('.', '').replace('/', '').replace('-', '').replace('.', '');
        var url = "<?= site_url('cadastros/consulta_cnpj') ?>/" + cnpj;
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            contentType: 'application/json',
            beforeSend: function () {
                botao.html('<i class="fa fa-spinner fa-spin"></i>').attr('disabled', true);
            },
            success: function(data) {
                if (botao.attr('data-empresa') == 1) {
                    if (data.status == "OK") {
                        $("#razao_social").val(data.nome);
                        $("#cep").val(data.cep);
                        $("#rua").val(data.logradouro + ' - ' + data.numero);
                        $("#bairro").val(data.bairro);
                        $("#cidade").val(data.municipio);
                        $("#uf").val(data.uf);
                        $("#fone_fixo").val(data.telefone);
                        $("#email").val(data.email);
                    } else {
                        $('.termo_alert').css('display','block');
                        $('#msn_termo').html('<div class="alert alert-danger"><p><b>CNPJ não encontrado na base de dados da Receita Federal!</b></p></div>');
                    }
                }else {
                    if (data.status == "OK") {
                        $("#razao_social_sim").val(data.nome);
                        $("#cep_sim").val(data.cep);
                        $("#rua_sim").val(data.logradouro + ' - ' + data.numero);
                        $("#bairro_sim").val(data.bairro);
                        $("#cidade_sim").val(data.municipio);
                        $("#uf_sim").val(data.uf);
                        $("#fone_fixo_sim").val(data.telefone);
                        $("#email_sim").val(data.email);
                    } else {
                        $('.termo_alert_sim').css('display','block');
                        $('#msn_termo_sim').html('<div class="alert alert-danger"><p><b>CNPJ não encontrado na base de dados da Receita Federal!</b></p></div>');
                    }
                }
            },
            error: function(data){
                $('.termo_alert').css('display','block');
                $('.termo_alert_sim').css('display','block');
                $('#msn_termo').html('<div class="alert alert-danger"><p><b>Não foi possível manter conexão com a Receita Federal, tente mais tarde!</b></p></div>');
                $('#msn_termo_sim').html('<div class="alert alert-danger"><p><b>Não foi possível manter conexão com a Receita Federal, tente mais tarde!</b></p></div>');
            },
            complete: function(data){
                botao.html('<i class="fa fa-search"></i>').attr('disabled', false);
            }
        });
    });

    //MASCARAS DE INPUT
    $(document).on('focus', '.cpf', function(){ $('.cpf').mask('999.999.999-99'); });
    $(document).on('focus', '.cnpj', function(){ $('.cnpj').mask('99.999.999/9999-99'); });
    $(document).on('focus', '.data', function(){ $('.data').mask('11/11/1111'); });
    $(document).on('focus', '.tempo', function(){ $('.tempo').mask('00:00:00'); });
    $(document).on('focus', '.datatempo', function(){ $('.datatempo').mask('99/99/9999 00:00:00'); });
    $(document).on('focus', '.cep', function(){ $('.cep').mask('99.999-999'); });
    $(document).on('focus', '.ddd', function(){ $('.ddd').mask('99'); });
    $(document).on('focus', '.fone', function(){ $('.fone').mask('0000-0000'); });
    $(document).on('focus', '.cell', function(){ $('.cell').mask('(00)00000-0000'); });
    $(document).on('focus', '.moeda', function(){ $('.moeda').mask("#.##0,00", {reverse: true}) });

    //LIMPA OS CAMPOS DO MODAL/FORMULARIO/MENSAGEM
    function limparDadosModal(mensagemId=false, formId=false, select2Id=false, btnSalvarId=false, limiteDateIdent=false,){
        //esconde o campo de mensagens
        if (mensagemId)
            $('.'+mensagemId).css('display','none');

        //limpa os campos do formulario
        if (formId)
            $('#'+formId)[0].reset();

        //limpa o select2
        if (select2Id)
            $("#"+select2Id).empty().attr('disabled', false);

        //reseta a config do botao salva para adicionar um novo termo
        if (btnSalvarId){
            $('.btnNovoTermo'+btnSalvarId).attr('data-acao', 'novo');
            $('.btnReset'+btnSalvarId).css('display', 'inline');
            $('#tituloModal'+btnSalvarId).html("<?=lang('novo_termo')?>");
        }

        if (limiteDateIdent) {
            minMaxDataImput(limiteDateIdent, new Date(), 0, 0, 2); //configura os limites dos imputs de datas
        }

         //remove a readyOnly dos campos do formulario
         $('#'+formId+' input').attr('readonly', false);
    }

    function escondeMensagem(alertClass){
        //esconde o campo de mensagens de retorno
        $('.'+alertClass).css('display', 'none');
    }
    
    //Fechar o dropdown ao clicar fora 
    $(document).on('click', function(e) {
        if (!e.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    });

    //Mostrar o dropdown ao clicar
    $('#btnDropdownShow').on('click', function(e) {
        e.preventDefault();
        $('#myDropdownShow')[0].classList.toggle("show");
    });
    
    //Mostrar o dropdown ao clicar
    $('#btnDropdownSim').on('click', function(e) {
        e.preventDefault();
        $('#myDropdownSim')[0].classList.toggle("show");
    });
    
    //Mostrar o dropdown ao clicar
    $('#btnDropdownCuritiba').on('click', function(e) {
        e.preventDefault();
        $('#myDropdownCuritiba')[0].classList.toggle("show");
    }); 
    
    function minMaxDataImput(identificador, data=new Date(), difDias=0,difMeses=0,difAnos=100){
        diaMin = data.getDate()<10 ? '0'+(parseInt(data.getDate())-difDias) : (parseInt(data.getDate())-difDias);
        mesMin = data.getMonth()<9 ? '0'+(parseInt(data.getMonth())+1-difMeses) : (parseInt(data.getMonth())+1-difMeses);
        anoMin = (parseInt(data.getFullYear())-difAnos);

        diaMax = data.getDate()<10 ? '0'+(parseInt(data.getDate())+difDias) : (parseInt(data.getDate())+difDias);
        mesMax = data.getMonth()<10 ? '0'+(parseInt(data.getMonth())+1+difMeses) : (parseInt(data.getMonth())+1+difMeses);
        anoMax = (parseInt(data.getFullYear())+difAnos);

        minData = anoMin+'-'+mesMin+'-'+diaMin;
        maxData = anoMax+'-'+mesMax+'-'+diaMax;
        $(identificador).attr('min', minData).attr('max', maxData);
    }
</script>
