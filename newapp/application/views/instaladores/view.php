<link href="<?php echo base_url('media') ?>/css/jquery.dynatable.css" rel="stylesheet">
<link href="<?php echo base_url('media') ?>/css/contas.css" rel="stylesheet">
<style>
    .modal-auth {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100% !important; /* Full width */
        height: 100%; /* Full height */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content/Box */
    .modal-content-auth {
        background-color: #fefefe;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 40%; /* Could be more or less, depending on screen size */
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        .modal-body, .modal-body * {
            padding: 0px;
            visibility: visible;
            margin-left: -15px;
            font-size: 5px;
        }
        .modal-body {
            margin-left: -15px;
            padding:0;
            line-height: 1.4em;
            font: 12pt Georgia, "Times New Roman", Times, serif;
            color: #000;
            position:absolute;
            width: 105%;
            font-size: 5px;
        }
        @page {
            margin: 1.5cm;
        }
        .label-pg h5{
            font-size: 10px;
        }
        #valores{
            padding-left: 5px;
        }
    }
    .none{
        display: none;
    }
    .modal-body{
        position: relative;
        overflow-y: auto;
        max-height: 100%!important;
        padding: 15px;
    }
    .dynatable-search{
        margin-right: -100px;
    }
    .input-all{
        margin-top: 60px;
        margin-bottom: -6px;
        margin-right: 1px;
    }
    #myTable, #myTable2{
        border-top-right-radius: 0px;
    }
    .label-input{
        background: #499BEA;
        border-bottom-right-radius: 0px;
        border-bottom-left-radius: 0px;
    }
    .label-pg{
        display: inline-flex;
        float: right;
    }
    #valores{
        font-weight: bold;
        padding-left: 5px;
    }
    .modal.large {
        width: 80% !important; /* respsonsive width */
        margin-left:-40%; /* width/2) */
    }
    .btn-cad{
        margin-bottom: 15px;
    }
    .con-label{
        padding-left: 15px;
    }
    .controls{
        margin-left: 15px!important;
    }
    .busca-os{
        display: inline-flex;
    }
    .input-table{
        background: whitesmoke;
        border: none;
        border-bottom: 1px solid #499bea;
        border-radius: 0px;
    }
</style>

<!-- The Modal -->
<div id="myModal" class="modal-auth">
    <!-- Modal content -->
    <div class="modal-content-auth">
        <div><h3>Valor de serviço <b>REPROVADO</b>!</h3><i>O valor do serviço foi reprovado pelo sistema. Caso mesmo assim queira continuar com o lançamento por favor inserir chave de liberação.</i></div>
        <div>
            <div class="form-group">
                <label for="pwd">Senha:</label>
                <input type="password" class="form-control" id="pwd" name="senha">
            </div>
            <div>
                <button class="btn btn-success" id="auth">Autorizar</button>
                <button class="btn btn-danger" id="close">Cancelar</button>
            </div>
        </div>
    </div>

</div>

<section>
    <h4>Ordens de Pagamento</h4>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#homepg" data-toggle="tab">OS à pagar</a></li>
        <li><a href="#homepg2" data-toggle="tab">Enviado para o Contas</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade active in" id="homepg">
            <div class="input-all pull-right"><label class="label label-input"><small>Selecionar Todos </small><input type="checkbox" class="selectAll" id="checkAll"/></label></div>

            <table id="myTable" class="table table-bordered table-responsive table-striped">
                <small><i>Os campos Data e Valor são obrigarórios!</i></small><br>
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Serviço</th>
                    <th>Cliente</th>
                    <th>Placa</th>
                    <th>Serial</th>
                    <th>Usuário</th>
                    <th>Solicitante</th>
                    <th>Data</th>
                    <th>Rastreamento</th>
                    <th>Autorização</th>
                    <th>Valor</th>
                    <th style="text-align: center" class="span1">Selecionar</th>
                </tr>
                </thead>
                <tbody align="center">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="width: 15%"><i style="color: #499BEA" id="spin" class="fa fa-spinner fa-spin fa-3x fa-fw"></i></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

                </tbody>
            </table>
            <button type="button" onclick="$('#Modal-contas').modal();" class="btn btn-success btn-block pull-right">Pagar <i id="loading" class="fa fa-spin fa-spinner none fa-fw"></i></button>
        </div>

        <div class="tab-pane fade" id="homepg2"><br/>
            <div class="input-all pull-right"><label class="label label-input"><small>Selecionar Todos </small><input type="checkbox" class="selectAll2" id="checkAll"/></label></div>
            <table id="myTable2" class="table table-bordered table-responsive table-striped tab-pane fade active in">
                <thead>
                <tr>
                    <th>OS</th>
                    <th>Serviço</th>
                    <th>Cliente</th>
                    <th>Placa</th>
                    <th>Serial</th>
                    <th>Usuário</th>
                    <th>Solicitante</th>
                    <th>Data</th>
                    <th>Rastreamento</th>
                    <th>Autorização</th>
                    <th>Valor</th>
                    <th class="span1">Selecionar</th>
                </tr>
                </thead>
                <tbody align="center">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="width: 15%"><i style="color: #499BEA" id="spin" class="fa fa-spinner fa-spin fa-3x fa-fw"></i></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                </tbody>
            </table>
            <button id="modal_print" type="button" data-toggle="modal" data-target="#Modal-OP" class="btn btn-block btn-danger pull-right"> Imprimir</button>
        </div>
    </div>
</section>
<!-- Modal -->
<div class="modal fade large" id="Modal-OP" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 style="color:red;"><span class="glyphicon glyphicon-lock"></span> Pagar</h4>
            </div>
            <div class="modal-body">
                <table id="print_table" class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th>Serviços</th>
                        <th>Cliente</th>
                        <th>Usuario</th>
                        <th>Placa</th>
                        <th>Serial</th>
                        <th>Rastreamento</th>
                        <th>Autorizaçao</th>
                        <th>Responsavel</th>
                        <th>Data</th>
                        <th>Valor</th>
                    </tr>
                    </thead>
                    <tbody id="tbody-pg">
                    </tbody>
                    <tbody></tbody>
                </table>
                <label class="label-pg label label-success"><h5>Valor Total: R$ </h5> <h5 id="valores"></h5> </label>
            </div>
            <div id="noPrint" class="modal-footer">
                <button type="submit" id="print" class="btn btn-danger btn-default pull-left"><i class="fa fa-print"></i> Imprimir</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade large" id="Modal-contas" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 style="color:red;"><span class="glyphicon glyphicon-lock"></span> Selecionar conta</h4>
            </div>
            <div class="modal-body">
                <table class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th style="width:5px;"></th>
                        <th>Id</th>
                        <th>Descrição</th>
                        <th>Vencimento</th>
                    </tr>
                    </thead>
                    <tbody id="tbody-contas">
                    </tbody>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <div>
                    <button id="pagar" onclick="nova_conta();" class="btn btn-success btn-default pull-left"><i class="fa fa-plus"></i> Criar nova conta</button>
                    <button onclick="selecionar_conta();" class="btn btn-success btn-default pull-left"><i class="fa fa-check"></i> Selecionar conta</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $cript = $_GET['id'];?>

<script type="text/javascript" src="<?php echo base_url('media') ?>/js/jquery.dynatable.js"></script>
<script>
    var unificar_conta = false;
    var idInstalador = <?= $this->input->get('id'); ?>;
    function validar_check(id){
        $.each($('input[name=id_conta]'), function d(i,data){data.checked=false;})
        $('#check_'+id)[0].checked=true;
        unificar_conta=id;
    }
    function nova_conta(){
        $.each($('input[name=id_conta]'), function d(i,data){data.checked=false;})
        unificar_conta=false;
        pagar(false);
    }
    function selecionar_conta(){
        if(unificar_conta==false){
            alert("Nehuma conta selecionada");
        }
        else{
            pagar(false);
        }
    }
    var getJson = $.getJSON('getOs?id=<?php echo $cript ?>', function (data) {
        $('#tbody').html('');
        $('#myTable').dynatable({

            dataset: {
                records: data,
                perPageDefault: 50
            }
        });

        $('#auth').click(function () {
            var senha = $('#pwd').val();
            var url_auth = "<?= site_url('instaladores/verifica_senha') ?>";
            if (senha != '') {
                $.ajax({
                    url : url_auth,
                    type : 'POST',
                    data : {senha: senha},
                    dataType : 'JSON',
                    success : function(data){
                        if (data.status == 'OK') {
                            var modal = document.getElementById('myModal');
                            modal.style.display = "none";
                            pagar(true);
                        } else {
                            alert('Senha inválida. (Acesso negado!)');
                        }
                    }
                })
            } else {
                alert('Senha inválida. (Acesso negado!)')
            }
        });

        $('#close').click(function () {
            $('#pagar').prop("disabled", false);
            $('#loading').addClass('none');
            var modal = document.getElementById('myModal');
            modal.style.display = "none";
        });

        $('.value').keyup(function () {
            var valor = parseFloat($(this).val().replace(',', '.')),
                limite = parseFloat($(this).attr('data-value'));

            if (valor > limite)
                $(this).attr('style', 'color: red !important');
            else
                $(this).attr('style', 'color: green !important');
        });

        $('.value').mask('#.##0,00', {reverse: true});

        $('.selectAll').click(function(){
            var val = this.checked;
            $('input[id=checkOrdem]').each(function () {
                $(this).prop('checked', val);
            });
        });
    });

    function pagar(auth) {
        $('#pagar').prop("disabled", true);
        $('#loading').removeClass('none');
        var contas = null;
        var fornecedor   = null;
        var titular      = null;
        var cpfTitular   = null;
        var cnpjTitular  = null;
        var conta        = null;
        var bank         = null;
        var agencia      = null;
        var weekly       = null;
        var valo         = null;
        var lim          = null;
        var dataS        = new Array();
        var valor        = new Array();
        var autorizacao  = new Array();
        var rastreamento = new Array();
        var responsavel  = new Array();
        var id           = new Array();
        var servico      = new Array();
        var cliente      = new Array();
        var placa        = new Array();
        var serial       = new Array();
        var user         = new Array();
        var cadastro     = new Array();
        var dataFormatada= null;
        var aux          = false;
        parseFloat(valor);
        $('#checkOrdem:checked').each(function(){
            if (auth == false) {
                valo = parseFloat( $('#'+$(this).data('valor') ).val().replace(',', '.'));
                lim = parseFloat( $('#'+$(this).data('valor') ).attr('data-value').replace(',', '.'));
                if (valo > lim) {
                    $('#Modal-contas').modal('hide');
                    var modal = document.getElementById('myModal');
                    modal.style.display = "block";

                    aux = true;
                    return false;
                }
            }

            dataS.push($('#'+$(this).data('date') ).val());
            rastreamento.push($('#'+$(this).data('rastr') ).val());
            autorizacao.push($('#'+$(this).data('autoriz') ).val());
            responsavel.push($(this).data('solicitante'));
            valor.push( parseFloat( $('#'+$(this).data('valor') ).val().replace(',','.')) );
            id.push($(this).data('id'));
            servico.push($(this).data('servico'));
            cliente.push($(this).data('cliente'));
            placa.push($(this).data('placa'));
            serial.push($(this).data('serial'));
            user.push($(this).data('user'));
            cadastro.push($(this).data('cadastro'));
            conta = $(this).data('conta');
            bank = $(this).attr('data-bank');
            agencia = $(this).attr('data-agencia');
            fornecedor = $(this).attr('data-fornecedor');
            titular = $(this).attr('data-titular');
            cpfTitular = $(this).attr('data-cpfTitular');
            cnpjTitular = $(this).attr('data-cnpjTitular');
            weekly = $(this).attr('data-weekly');
            var data = new Date();
            dataFormatada = ("0" + data.getDate()).substr(-2) + "/"
                + ("0" + (data.getMonth() + 1)).substr(-2) + "/" + data.getFullYear();
        });

        if (aux == true) { return false; }

        var sum = 0;
        $.each(valor, function (i, v) {
            sum += v;
        });

        if (dataS != "" && valor != ""){
            var data_post=  {
                'fornecedor': titular,
                'descricao': 'ORDEM DE PAGAMENTO REF A '+servico.length+' SERVIÇO(S) DO TÉC. '+fornecedor+' BANCO: '+bank+' AG.: '+agencia+' C.: '+conta+' CPF: '+cpfTitular+' CNPJ: '+cnpjTitular,
                'info_bancaria': 'BANCO: '+bank+' AG.: '+agencia+' C.: '+conta+' CPF: '+cpfTitular+' CNPJ: '+cnpjTitular,
                'valor' : sum,
                'data_vencimento' : dataFormatada,
                'categoria': 'INSTALADOR',
                'seriais': serial,
                'cliente': cliente,
                'placa': placa,
                'id_os': id,
                'valorServ': valor,
                'dataServ': dataS,
                'servicoOs': servico,
                'userOs': user,
                'id_instalador': idInstalador,
                'instalador': fornecedor,
                'status_aprovacao': '0',
                'weekly': weekly
            };
            if(unificar_conta){
                data_post['id_conta']=unificar_conta;
            }
            $.ajax({
                url : '../contas/addContaOs',
                type : 'POST',
                data : data_post,
                success : function(){
                    $.getJSON('../contas/getLastId', function (id_contas) {
                        contas = id_contas;
                    }).success(function() {
                        $.each(valor, function (i) {
                            $.ajax({
                                url : 'update_serv',
                                type : 'POST',
                                data : { 'id' : id[i] }
                            });
                            $.ajax({
                                url : 'cadServiceOp',
                                type : 'POST',
                                data : {
                                    'id_instalador' : <?php echo $cript ?>,
                                    'id_os' : id[i],
                                    'servico' : servico[i],
                                    'cliente' : cliente[i],
                                    'placa' : placa[i],
                                    'serial': serial[i],
                                    'user' : user[i],
                                    'solicitante' : responsavel[i],
                                    'data' : dataS[i],
                                    'cod_rastreamento' : rastreamento[i],
                                    'cod_autorizacao' : autorizacao[i],
                                    'valor' : valor[i],
                                    'id_contas': contas
                                },success: function () {
                                    location.reload(getJson);
                                }
                            });
                        })
                    });
                }
            })
        }else{
            $('#pagar').prop("disabled", false);
            $('#loading').addClass('none');
            alert("Verifique se os campos obrigatórios estão todos preenchidos");
        }
    }

    $(document).ready(function () {
        $.getJSON('<?= base_url()?>index.php/contas/get_contas_instalador/<?=$this->input->get('id')?>/0',
            function(data){
                $.each(data,function(i,v){
                    var vencimento = v.data_vencimento.split('-');
                    var template = [
                            '<tr>'+
                            '<td><input type="checkbox" id="check_'+v.id+'" onclick="validar_check(\''+v.id+'\')" name="id_conta" value="'+v.id+'"></input</td>'+
                            '<td>'+v.id+'</td>'+
                            '<td>'+v.descricao+'</td>'+
                            '<td>'+vencimento[2]+'/'+vencimento[1]+'/'+vencimento[0]+'</td>'+
                            '</tr>'].join();
                        $('#tbody-contas').prepend(template);
                });
            }
        );
        
        function ucfirst(str) {
            return str.substr(0,1).toUpperCase()+str.substr(1)
        }

        $('#tbody').html('');
        getJson;

        $.getJSON('pagos_ajax?id=<?php echo $cript ?>', function (data) {
            $('#tbody').html('');
            $('#myTable2').dynatable({
                dataset: {
                    records: data
                }
            });

            $('.selectAll2').click(function(){
                var val = this.checked;
                $('input[id=checkOrdem2]').each(function () {
                    $(this).prop('checked', val);
                });
            });

            $('#modal_print').click(function () {

                var valor     = new Array();
                var id        = new Array();
                var servico   = new Array();
                var cliente   = new Array();
                var placa     = new Array();
                var serial    = new Array();
                var rastreamento = new Array();
                var autorizacao  = new Array();
                var responsavel = new Array();
                var cadastro = new Array();
                var user      = new Array();

                $('#checkOrdem2:checked').each(function(){
                    valor.push($(this).data('valor'));
                    id.push($(this).data('id'));
                    servico.push($(this).data('servico'));
                    cliente.push($(this).data('cliente'));
                    placa.push($(this).data('placa'));
                    serial.push($(this).data('serial'));
                    rastreamento.push($(this).data('rastr'));
                    autorizacao.push($(this).data('autoriz'));
                    responsavel.push($(this).data('solicitante'));
                    cadastro.push($(this).data('date'));
                    user.push($(this).data('user'));
                });
                var sum = 0.00;
                $('#tbody-pg').html('');
                $.each(valor, function (i, v) {
                    var template = [
                        '<tr>'+
                        '<td>'+ucfirst(servico[i])+'</td>'+
                        '<td>'+cliente[i]+'</td>'+
                        '<td>'+user[i]+'</td>'+
                        '<td>'+placa[i]+'</td>'+
                        '<td>'+serial[i]+'</td>'+
                        '<td>'+rastreamento[i]+'</td>'+
                        '<td>'+autorizacao[i]+'</td>'+
                        '<td>'+responsavel[i]+'</td>'+
                        '<td>'+cadastro[i]+'</td>'+
                        '<td>'+v+'</td>'+
                        '</tr>'].join();
                    $('#tbody-pg').prepend(template);

                    sum += parseFloat(v);
                });
                $('#valores').text(sum);
            });
        });

        $('#print').click(function () {
            print($('#print_table'));
        });

//        $(document).on('click', '#btn-excluir', function () {
//            $.ajax({
//                url : 'del_service',
//                type : 'POST',
//                data : {
//                    'id': $(this).data('id'),
//                    'status' : 2
//                },
//                success : function(){
//                    location.reload(getJson);
//                }
//            });
//        });

        $('.data').mask('99/99/9999');
    });
</script>
