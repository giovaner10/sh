<!--<style>-->
<!--    .control-group{-->
<!--        display: inline-flex;-->
<!--    }-->
<!--    .con-label{-->
<!--        padding-left: 15px;-->
<!--    }-->
<!--    .controls{-->
<!--        margin-left: 15px!important;-->
<!--    }-->
<!--    #serial_instalado, #serial_retirado, #cod_rastreamento{-->
<!--        display: inline-flex;-->
<!--    }-->
<!--</style>-->
<!--<form class="form-horizontal" method="post" action="--><?php //echo site_url('instaladores/cad_pg?id=').$_GET['id']?><!--">-->
<!--    <label class="con-label">Nº da OS</label>-->
<!--    <div class="controls">-->
<!--        <input type="text" name="id_os" class="span1 form-control" required>-->
<!--    </div>-->
<!--    <button id="" type="submit" class="btn btn-success">Adicionar</button>-->
<!--</form>-->
<!--<!--<form class="form-horizontal" method="post" action="-->--><?php ////echo site_url('instaladores/cad_service?id=').$_GET['id']?><!--<!--">-->-->
<!--<!--    <section id="section">-->-->
<!--<!--        <h4>Cadastro de Serviços</h4>-->-->
<!--<!--        <div class="control-group">-->-->
<!--<!--            <label class="con-label">Cliente:</label>-->-->
<!--<!--            <div class="controls">-->-->
<!--<!--                <input id="input-cliente" type="text" data-source='-->--><?php ////echo $j_clientes ?><!--<!--' name="cliente" class="span4 form-control" data-provide="typeahead" autocomplete="off"-->-->
<!--<!--                       data-items="6" placeholder="Digite o nome do cliente" required />-->-->
<!--<!--            </div>-->-->
<!--<!--            <label class="con-label">Usuário</label>-->-->
<!--<!--            <div class="controls">-->-->
<!--<!--                <input type="text" data-source='-->--><?php ////echo $usuarios ?><!--<!--' data-provide="typeahead" autocomplete="off" data-items="6" name="usuario" required class="span4 form-control">-->-->
<!--<!--            </div>-->-->
<!--<!--        </div><br/>-->-->
<!--<!--        <div class="control-group">-->-->
<!--<!--            <label class="con-label">Nº da OS</label>-->-->
<!--<!--            <div class="controls">-->-->
<!--<!--                <input type="text" name="id_os" class="span1 form-control" required>-->-->
<!--<!--            </div>-->-->
<!--<!--            <label class="con-label">Código de Autorização</label>-->-->
<!--<!--            <div class="controls">-->-->
<!--<!--                <input type="number" maxlength="4" name="cod_autorizacao" class="span2 form-control" required>-->-->
<!--<!--            </div>-->-->
<!--<!--            <label class="con-label">Data</label>-->-->
<!--<!--            <div class="controls">-->-->
<!--<!--                <input type="text" name="data" class="span2 datepicker form-control" required>-->-->
<!--<!--            </div>-->-->
<!--<!--            <label class="con-label">Placa</label>-->-->
<!--<!--            <div class="controls">-->-->
<!--<!--                <input type="text" name="placa" class="span2 form-control" required>-->-->
<!--<!--            </div>-->-->
<!--<!--        </div><br/>-->-->
<!--<!--        <div class="control-group">-->-->
<!--<!--            <label class="con-label">Serviço</label>-->-->
<!--<!--            <div class="controls">-->-->
<!--<!--                <select id="tipoServico" name="servico" class="form-control span2" required>-->-->
<!--<!--                    <option>Selecione</option>-->-->
<!--<!--                    <option value="instalacao">Instalação</option>-->-->
<!--<!--                    <option value="manutencao">Manutenção</option>-->-->
<!--<!--                    <option value="retirada">Retirada</option>-->-->
<!--<!--                </select>-->-->
<!--<!--            </div>-->-->
<!--<!--            <div id="serial_instalado">-->-->
<!--<!--                <label class="con-label">Serial Instalado</label>-->-->
<!--<!--                <div class="controls">-->-->
<!--<!--                    <input type="text" id="install" name="serial_install" class="span2 form-control">-->-->
<!--<!--                </div>-->-->
<!--<!--            </div>-->-->
<!--<!--            <div id="serial_retirado">-->-->
<!--<!--                <label class="con-label">Serial Retirado</label>-->-->
<!--<!--                <div class="controls">-->-->
<!--<!--                    <input type="text" id="recall" name="serial_recall" class="span2 form-control">-->-->
<!--<!--                </div>-->-->
<!--<!--            </div>-->-->
<!--<!--            <div id="cod_rastreamento">-->-->
<!--<!--                <label class="con-label">Código de Rastreamento</label>-->-->
<!--<!--                <div class="controls">-->-->
<!--<!--                    <input type="text" id="cod_rastreamento" name="cod_rastreamento" class="span2 form-control">-->-->
<!--<!--                </div>-->-->
<!--<!--            </div>-->-->
<!--<!--        </div><br/>-->-->
<!--<!--        <div class="control-group">-->-->
<!--<!--            <label class="con-label">Valor</label>-->-->
<!--<!--            <div class="controls">-->-->
<!--<!--                <input type="text" name="valor" class="span2 form-control" required>-->-->
<!--<!--            </div>-->-->
<!--<!--        </div>-->-->
<!--<!--    </section>-->-->
<!--<!--    <button id="" type="submit" class="btn btn-success">Adicionar</button>-->-->
<!--<!--</form>-->-->
<!--</section>-->
<!--</form>-->
<!--<script>-->
<!--    $('#tipoServico').change(function () {-->
<!--        var es = document.getElementById('tipoServico');-->
<!--        esValor = es.options[es.selectedIndex].value;-->
<!--        switch (esValor){-->
<!--            case 'instalacao':-->
<!--                $('#serial_instalado').fadeIn();-->
<!--                $('#serial_retirado').hide();-->
<!--                $('#cod_rastreamento').hide();-->
<!--                $('#recall').val(0);-->
<!--                $("#serial_instalado").attr('required',true);-->
<!--                $("#serial_retirado").attr('required',false);-->
<!--                $("#cod_rastreamento").attr('required',false);-->
<!--                break;-->
<!--            case 'manutencao':-->
<!--                $('#serial_instalado').fadeIn();-->
<!--                $('#serial_retirado').fadeIn();-->
<!--                $('#cod_rastreamento').fadeIn();-->
<!--                $("#serial_instalado").attr('required',true);-->
<!--                $("#cod_rastreamento").attr('required',true);-->
<!--                $("#serial_retirado").attr('required',true);-->
<!--                break;-->
<!--            case 'retirada':-->
<!--                $('#serial_instalado').hide();-->
<!--                $('#install').val(0);-->
<!--                $('#serial_retirado').fadeIn();-->
<!--                $('#cod_rastreamento').fadeIn();-->
<!--                $("#serial_retirado").attr('required',true);-->
<!--                $("#cod_rastreamento").attr('required',true);-->
<!--                $("#serial_instalado").attr('required',false);-->
<!--                break;-->
<!--        }-->
<!--    });-->
<!--</script>-->
