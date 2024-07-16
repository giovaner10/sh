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
        <h3>Nova Licitação</h3>
    </div>
    <div class="container-fluid">
        <h3>Pré Licitação</h3>
        <form id="form_licitacao" method="post" action="<?= base_url() ?>index.php/licitacao/add" onsubmit="alterar_formato_dinheiro()">
            <div class="form-group">
                <div class="col-sm-12">
                    <label>Orgão</label>
                    <input id="orgao" type="text" class="form-control" name="orgao" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2">
                    <label>Data da licitação</label>
                    <input id="data_licitacao" type="date" class="form-control" name="data_licitacao" required>
                </div>
                <div class="col-sm-2">
                    <div class="form-group" >
                        <label for="periodo">Estado:</label>
                        <select class="form-control" name="estado" id="estado" required>
                            <option value=""></option>    
                            <option value="AL">AL</option>
                            <option value="AP">AP</option>
                            <option value="AM">AM</option>
                            <option value="BA">BA</option>
                            <option value="CE">CE</option>
                            <option value="DF">DF</option>
                            <option value="ES">ES</option>
                            <option value="GO">GO</option>
                            <option value="MA">MA</option>
                            <option value="MT">MT</option>
                            <option value="MS">MS</option>
                            <option value="MG">MG</option>
                            <option value="PA">PA</option>
                            <option value="PB">PB</option>
                            <option value="PR">PR</option>
                            <option value="PE">PE</option>
                            <option value="PI">PI</option>
                            <option value="RJ">RJ</option>
                            <option value="RN">RN</option>
                            <option value="RS">RS</option>
                            <option value="RO">RO</option>
                            <option value="RR">RR</option>
                            <option value="SC">SC</option>
                            <option value="SP">SP</option>
                            <option value="SE">SE</option>
                            <option value="TO">TO</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <label>Esfera</label>
                    <select style="width: 190px;" class="form-control" name="esfera" id="esfera" required>
                        <option value=""></option>    
                        <option value="0">Federal</option>
                        <option value="1">Estadual</option>
                        <option value="2">Municipal</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label>Empresa</label>
                    <select style="width: 190px;" class="form-control" name="empresa" id="empresa" required>
                        <option value=""></option>    
                        <option value="0">Show</option>
                        <option value="1">Norio</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label>Tipo</label>
                    <select style="width: 190px;" class="form-control" onchange="change_tipo(this);" name="tipo" id="tipo" required>
                        <option value=""></option>    
                        <option value="0">Presencial</option>
                        <option value="1">Eletrônico</option>
                        <option value="2">Carona</option>
                    </select>
                </div>
                <div class="span2" id='div_plataforma' style="display:none;">
                    <label>Plataforma</label>
                    <input id="plataforma" style="WIDTH: 175PX;" type="text" class="" name="plataforma">
                </div>
            </div>
            <div class="control-group">
                <div class="col-sm-2">
                    <label>Tipo de contrato</label>
                    <select class="form-control" name="tipo_contrato" id="tipo" required="">
                        <option value=""></option>    
                        <option value="0">Licitação</option>
                        <option value="1">Adesão à ata</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label>Quantidade de veículos</label>
                    <input id="qtd_veiculos" class="form-control" type="text" onchange="change_veic(this)" name="qtd_veiculos">
                </div>
				<div class="col-sm-2">
                    <label>Descrição do serviço</label>
                    <input id="descricao_servico" class="form-control" type="text" name="descricao_servico">
                </div>
				<div class="col-sm-2">
                    <label>Ata de registro de preço</label>
                    <select class="form-control" name="ata_registro_preco" id="tipo" required="">
                        <option value=""></option>    
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label>Situação preliminar</label>
                    <select class="form-control" name="situacao_preliminar" id="situacao_preliminar">
                        <option value=""></option>    
                        <option value="2">Não Participou</option>
                        <option value="3">Suspenso</option>
                        <option value="4">Anulado</option>
                        <option value="5">Em andamento</option>
                    </select>
                </div>
            </div>
            <div class="control-group" style="border-bottom: solid 1px #bdbdbd;">
                <div class="col-sm-2">
                    <label>Meses</label>
                    <input id="meses" type="text" class="form-control" onchange="change_veic(this)" name="meses">
                </div>
                <div class="col-sm-2">
                    <label>Valor unitário ref.</label>
                    <input id="valor_unitario_ref" type="text" data-prefix="R$ " class="form-control" onchange="change_veic(this)" name="valor_unitario_ref">
                </div>
                <div class="col-sm-2">
                    <label>Valor instalação ref.</label>
                    <input id="valor_instalacao_ref" type="text" data-prefix="R$ " class="form-control" onchange="change_veic(this)" name="valor_instalacao_ref">
                </div>
                <div class="col-sm-2">
                    <label>Valor global ref.</label>
                    <input id="valor_global_ref" type="text" data-prefix="R$ " class="form-control" onchange="change_veic(this)" name="valor_global_ref">
                </div>
            </div>
            <div class="col-md-12">
                <h3>Pós Etapa de lances</h3>
                <hr>               
            </div>
            <div class="control-group">
                <div class="col-sm-2">
                    <label>Vencedor</label>
                    <input id="vencedor" type="text" class="form-control" name="vencedor">
                </div>
            </div>
            <div class="control-group">
                <div class="col-sm-2" style="margin-left: 0px;">
                    <label>Valor unitário arremate</label>
                    <input id="valor_unitario_arremate" data-prefix="R$ " type="text" class="form-control" onchange="change_veic(this)" name="valor_unitario_arremate">
                </div>
                <div class="col-sm-2">
                    <label>Valor instalação arremate</label>
                    <input id="preco_instalacao" data-prefix="R$ " type="text" class="form-control" name="preco_instalacao" onchange="change_veic(this)">
                </div>
                <div class="col-sm-2">
                    <label>Valor global arremate</label>
                    <input id="valor_global_arremate" data-prefix="R$ " type="text" class="form-control" onchange="change_veic(this)" name="valor_global_arremate">
                </div>
            </div>
            <div class="control-group">
                <div class="col-sm-2">
                    <label>Situação final</label>
                    <select  class="form-control" name="situacao_final" id="situacao_final">
                        <option value=""></option>    
                        <option value="0">Arrematado</option>
                        <option value="1">Contrato Assinado</option>
                        <option value="2">Perdido</option>
                        <option value="3">Suspenso</option>
                        <option value="4">Em andamento</option>
                        <option value="5">Em período de teste</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <div class="col-sm-12">
                    <label>Observações</label>
                    <input id="observacoes" class="form-control" type="text" class="form-control" name="observacoes" maxlength="150">
                </div>
            </div>
            <div class="control-group">
            	<div class="col-sm-12">
            		<br>
            		<button id="submit" class="btn btn-primary btn-large"><i id="load" style="font-size:18px;" class="fa fa-spinner fa-pulse fa-3x fa-fw display" ></i>Salvar</button>
        		</div>
        	</div>
        </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url('assets');?>/js/jquery.mask.min.js"></script>
<script src="<?php echo base_url('assets');?>/js/jquery.maskMoney.min.js" type="text/javascript"></script>
<script>
    $(document).ready( function () {
        $('body > div:nth-child(5) > ul > li:nth-child(2) > a')[0].href='<?= base_url() ?>index.php/licitacao/acompanhamento';
        $('#data_licitacao').datepicker({
            format: 'dd/mm/yyyy'
        });
        
    } );
    function change_tipo(e){
        if(e.value=="0"){
            $('#div_plataforma')[0].style.display='none';
        }
        else{
            $('#div_plataforma')[0].style.display=null;
        }
    }
    function change_veic(e){
        if(e.value||e.name=="preco_instalacao"||e.name=="valor_instalacao_ref"){
            if($("#qtd_veiculos")[0].value){
                if(formato_float($("#valor_unitario_ref")[0].value) && e.id !="valor_global_ref"){
                    var instalacao = parseFloat((formato_float($("#valor_instalacao_ref")[0].value))*parseFloat(formato_float($("#qtd_veiculos")[0].value)).toFixed(2));
                    if(!instalacao){
                        instalacao=0;
                    }
                    var valor_arremate = parseFloat((formato_float($("#valor_unitario_ref")[0].value))*parseFloat(formato_float($("#qtd_veiculos")[0].value)).toFixed(2))*parseInt($('#meses')[0].value);
                    if(!valor_arremate){
                        valor_arremate=0;
                    }
                    $("#valor_global_ref").maskMoney('mask',parseFloat(instalacao+valor_arremate));
                }
                else if(formato_float($("#valor_global_ref")[0].value)){
                    $("#valor_instalacao_ref").maskMoney('mask',parseFloat(0));
                    $("#valor_unitario_ref").maskMoney('mask',parseFloat((parseFloat(formato_float($("#valor_global_ref")[0].value))/parseFloat(formato_float($("#qtd_veiculos")[0].value))).toFixed(2))/parseInt($('#meses')[0].value));
                }
                if(formato_float($("#valor_unitario_arremate")[0].value) && e.id !="valor_global_arremate"){
                    var instalacao = parseFloat((formato_float($("#preco_instalacao")[0].value))*parseFloat(formato_float($("#qtd_veiculos")[0].value)).toFixed(2));
                    if(!instalacao){
                        instalacao=0;
                    }
                    var valor_arremate = parseFloat((formato_float($("#valor_unitario_arremate")[0].value))*parseFloat(formato_float($("#qtd_veiculos")[0].value)).toFixed(2))*parseInt($('#meses')[0].value);
                    if(!valor_arremate){
                        valor_arremate=0;
                    }
                    $("#valor_global_arremate").maskMoney('mask',parseFloat(instalacao+valor_arremate));
                }
                else if(formato_float($("#valor_global_arremate")[0].value)){
                    $("#preco_instalacao").maskMoney('mask',parseFloat(0));
                    $("#valor_unitario_arremate").maskMoney('mask',parseFloat((parseFloat(formato_float($("#valor_global_arremate")[0].value))/parseFloat(formato_float($("#qtd_veiculos")[0].value))).toFixed(2))/parseInt($('#meses')[0].value));
                }
            }
        }
    }
    $('#valor_unitario_ref').maskMoney();
    $('#valor_global_ref').maskMoney();
    $('#valor_instalacao_ref').maskMoney();
    $('#valor_global_arremate').maskMoney();
    $('#valor_unitario_arremate').maskMoney();
    $('#preco_instalacao').maskMoney();

    function alterar_formato_dinheiro(){
        $('#preco_instalacao')[0].value=formato_float($('#preco_instalacao')[0].value);
        $('#valor_unitario_arremate')[0].value=formato_float($('#valor_unitario_arremate')[0].value);
        $('#valor_global_arremate')[0].value=formato_float($('#valor_global_arremate')[0].value);
        $('#valor_global_ref')[0].value=formato_float($('#valor_global_ref')[0].value);
        $('#valor_instalacao_ref')[0].value=formato_float($('#valor_instalacao_ref')[0].value);
        $('#valor_unitario_ref')[0].value=formato_float($('#valor_unitario_ref')[0].value);
    }
    function formato_float(valor){
        return valor.replace('R$ ','').replace(/,/g,'')
    }
</script>