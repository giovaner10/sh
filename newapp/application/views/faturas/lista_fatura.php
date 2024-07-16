<?php header('Access-Control-Allow-Origin: *'); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

<style>
    .p_comment {
        font-size: 15px;
        font-family: cursive;
    }
    .listAnexo{
        max-height: 200px;
        overflow: auto;
        padding-top: 20px;
    }
    .linkAnexo{
        font-size: 14px;
        padding: 10px 20px;
        background: -moz-linear-gradient( top, #fafafa 0%, #e1e3e4);
        background: -webkit-gradient( linear, left top, left bottom, from(#fafafa), to(#e1e3e4));
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        border: 1px solid #c7d8e8;
        -moz-box-shadow: 0px 1px 3px rgba(235,235,235,0.5), inset 0px 0px 1px rgba(255,255,255,1);
        -webkit-box-shadow: 0px 1px 3px rgba(235,235,235,0.5), inset 0px 0px 1px rgba(255,255,255,1);
        box-shadow: 0px 1px 3px rgba(235,235,235,0.5), inset 0px 0px 1px rgba(255,255,255,1);
        text-shadow: 0px -1px 0px rgba(81, 81, 81, 0.7), 0px 1px 0px rgba(255,255,255,0.3);
        text-transform: uppercase;
        text-decoration: none!important;
    }
    .display{
        display: none;
    }
    .modal-body-personalizado {
        max-height: 500px;
        overflow: auto;
        display: table-row;
        height: 230px;
        -webkit-transition: height 0,2ms ease-in-out;
        -moz-transition: height 0,2ms ease-in-out;
        -ms-transition: height 0,2ms ease-in-out;
        -o-transition: height 0,2ms ease-in-out;
        transition: height 0,2ms ease-in-out;
    }

    #trComment > td > ul{
        list-style: none;
    }
    .buttonCm{
        cursor: pointer;
    }
    .loading > li{
        text-align: center;
        padding-top: 50px;
    }
</style>

<h3>Faturas</h3>
<hr>
<div style="padding: 10px;">
    <?php if (!isset($_GET['cancelar'])): ?>
        <a href="<?= site_url('faturas/index?list=4') ?>" class="btn btn-primary"><i class="fa fa-reorder"></i> A Cancelar</a>
    <?php else: ?>
        <a href="<?= site_url('faturas') ?>" class="btn btn-primary"><i class="fa fa-reply"></i> Voltar</a>
    <?php endif; ?>

    <a href="#faturaConfig" data-toggle="modal" class="btn btn-primary"><i class="fa fa-cogs"></i>
        <?=lang('config_boleto')?>
    </a>
</div>

<div class="container-fluid">
    <table id="faturas" class="table responsive table-bordered table-hover">
        <thead>
            <th></th>
            <th>ID</th>
            <th>Cliente</th>
            <th>Venc. da Fatura</th>
            <th>Valor</th>
            <th>Nº Nota fiscal</th>
            <th>Mês de referência</th>
            <th>Inicío do P.</th>
            <th>Fim do P.</th>
            <th>Data Pagamento</th>
            <th>Valor Pago</th>
            <th>Status</th>
            <th>Status Nf-e</th>
            <th>Admin</th>
            <th>Comentário</th>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div>
    <h4>Status Nfe:</h4>

    <span class="label label-primary">Em Aberto</span>: Não foi gerado RPS para a fatura.<br/>
    <span class="label label-warning">Pendente</span>: Foi gerado o RPS, mas não foi gerado o Lote de RPS.<br/>
    <span class="label label-info">Enviada</span>: Foi gerado o Lote de RPS e aguarda retorno.<br/>
    <span class="label label-success">Gerada</span>: Nf-e gerada com sucesso.

</div>

<div id="modalComment" class="modal hide fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Comentários</h4>
      </div>
      <div class="modal-body-personalizado" id="th_comment">
        <div class="commentsList"></div>
        <ul id="instant" class="display">
            <li>
                <textarea style="width: 80%" name="comentario" rows="3" cols="250" disabled></textarea><br>
                <small class="user"><i class="fa fa-check" aria-hidden="true"></i></small>
                <small class="data"><i class="fa fa-clock-o" aria-hidden="true"></i></small>
            </li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div id="envia_anexo" class="modal hide fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1">Enviar Anexo</h3>
    </div>
    <div class="modal-body-personalizado">
        <div class="alerta"></div>
        <div>
            <strong>Número de Anexos </strong>
            <label id="countAnexo" class="badge badge-info">0</label>
            <ul class="listAnexo">

            </ul>
        </div>

        <style>input[disabled]{ margin-top: 10px; }</style>
        <form id="formUpload" method="post" enctype="multipart/form-data">
            <input type="file" name="arquivo" required id="anexo" formenctype="multipart/form-data">
            <input type="hidden" id="id_fatura" name="id_fatura" value="">
            <button class="btn btn-success" id="sendAnexo">Enviar</button>
        </form>
    </div>
</div>
<div id="impressao_contrato" class="modal hide fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true"></button>
        <h3 id="myModalLabel1">Impressão de Faturas</h3>
    </div>
    <div class="modal-body-personalizado">
        <p>Carregando...</p>
    </div>
</div>
<div id="gerar_contrato" class="modal hide fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true"></button>
        <h3 id="myModalLabel1">Gerar Faturas por Contrato</h3>
    </div>
    <div class="modal-body-personalizado">
        <p>Carregando...</p>
    </div>
</div>
<div id="envia_fatura" class="modal hide fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1">Enviar Fatura</h3>
    </div>
    <div class="modal-body-personalizado">
        <p>Carregando...</p>
    </div>
</div>

<div id="cancela_fatura" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h3 id="myModalLabel1">Cancelar Faturas</h3>
    </div>
    <div class="modal-body-personalizado">
        <div style="display:none">
            <input type="hidden" name="id_fatura" value="86277">
        </div>
        <div class="alert alert-danger">
            <strong>Tem certeza que deseja cancelar estas faturas?</strong>
            <span id="lista_faturas"></span>
        </div>
        <div class="row-fluid">
            <div class="span12 ">
                <div class="control-group">
                    <label class="control-label">Qual o motivo do cancelamento?</label>
                    <textarea rows="4" class="span12" name="motivo" required=""></textarea>

                </div>
            </div>
            <!--/span-->
        </div>
        <div class="row-fluid">
            <div class="span12 ">
                <div class="control-group">
                    <label class="control-label">Digite a senha para confirmar:</label> <input name="senha_exclusao" size="16" type="password" required="">

                </div>
            </div>
            <!--/span-->
        </div>
        <div class="row-fluid">
            <div id="resultado_cancelar_fatura" class="span12 ">

            </div>
            <!--/span-->
        </div>
        <div class="row-fluid">
            <div class="form-actions">
                <button onclick="cancelar_faturas()" class="btn btn-danger">
                    <i class="icon-remove icon-white"></i> Cancelar Faturas
                </button>
                <a onclick="fecharModal('#cancela_fatura');" class="btn fechar">Fechar</a>
            </div>
        </div>
    </div>
</div>

<div style="display: none" id="retornoElmar">

</div>

<script>
    $(document).ready(function() {
        var table = $('#faturas').DataTable({
            responsive: true,
            serverSide: true,
            stateSave: true,
            initComplete: function () {
                $('#faturas_filter > label').remove();
                $('#faturas_filter').append('<input id="search_table" type="search" autocomplete="off" class="search" placeholder="Pesquisar" desabled="true" />');
                $('#search_table').on('keyup', function() {
                    var input = $(this);
                    table.search(input.val()).draw();
                });

            },
            ajax: '<?= site_url('faturas/ajaxListFaturas?list='.$this->input->get('list')) ?>',
            oLanguage: {
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
                "sSearch": "Pesquisar:",
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

    });

    function gerar_nota(id, emp) {
        $.ajax({
           type: "post",
           data: {'txtLogin': '21.698.912/0001-59', 'txtSenha': 'deus1234'},
           url: "http://guarabiranfe.elmarinformatica.com.br/inc/verifica.php",
           dataType: "json",
           success: function (data) {
               alert('Deu certo!');
           }
        });
    }
    /*function gerar_nota(id_ft, emp) {
        var url = "<?= site_url('nfes/notaByIdFatura'); ?>/"+emp;
        var button = document.getElementById('gerar_nota'+id_ft);
        var status = document.getElementById('statusnfe'+id_ft);
        $.ajax({
            type: "post",
            data: {id: id_ft},
            url: url,
            dataType: "json",
            success: function(data) {
                alert(data.mensagem);
                if (data.status == 'OK') {
                    button.removeAttribute("onclick");
                    button.setAttribute('disabled', 'disabled');
                    status.innerHTML = '<span class="label label-warning">Pendente</span>';
                }
            }
        });
    }*/

    $(document).on('click', '.enviaFat', function() {
        let numero = $(this).attr('data-numero');
        $.ajax({
               url: "<?= site_url('faturas/enviar')?>/"+numero,
               success: function(data){
                   $('.status_fatura_'+numero).html('<span class="label label-warning">A pagar</span>');  //atualiza o label do status da fatura
                   alert(data);
               },
               error: function(data) {
                   alert(data);

               }
           });
    });

    var serialize_checkbox = "";
    if (window.location.host == "localhost"){
        var url = "ftp://show:show2592@ftp-arquivos.showtecnologia.com/particao_ftp/uploads/anexo_fatura/";
        var url_nfe = "ftp://show:show2592@ftp-arquivos.showtecnologia.com/particao_ftp/uploads/nfes/";
    }else{
        url = "ftp://show:show2592@ftp-arquivos.showtecnologia.com/particao_ftp/uploads/anexo_fatura/";
        var url_nfe = "ftp://show:show2592@ftp-arquivos.showtecnologia.com/particao_ftp/uploads/nfes/";
    }

    controller1 = "<?= site_url('faturas/count_anexo') ?>";
    controller2 = "<?= site_url('faturas/list_anexos') ?>";
    controller3 = "<?= site_url('faturas/anexar') ?>";
    controller4 = "<?= site_url('faturas/comentario') ?>";
    controller5 = "<?= site_url('faturas/getComments') ?>";
    getController = "<?= site_url('faturas/getComentarios') ?>";
    $('#sendAnexo').click(function () {
        $('#formUpload').ajaxForm({
            url: controller3,
            contentType: 'multipart/form-data',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                $('.alerta').html(data.mensagem);

            },
            error: function (data) {
                $('.alerta').html(data.mensagem);
            }
        })
    });

    $('#countAnexo').html(0);
    function countAnexo(fatura) {
        $('.alerta').html("");
        $('.listAnexo').html("");
        $('#id_fatura').val(fatura);
        $.ajax({
            type: "post",
            data: {id: fatura},
            url: controller1,
            dataType: "json",
            success: function(data){
                $('#countAnexo').html(data);
            }
        });
        $.ajax({
            type: "post",
            data: {id: fatura},
            url: controller2,
            dataType: "JSON",
            success: function(data){
                $.each(data, function (i, arquivo) {
                    if (arquivo.pasta == 'nfe') {
                        $('.listAnexo').append("<li><a class='linkAnexo' href="+url_nfe+encodeURI(arquivo.file)+" target='_blank' download><i class='fa fa-file'></i> " +(i+1)+" - "+arquivo.file+"</a></li><hr>");
                    } else {
                        $('.listAnexo').append("<li><a class='linkAnexo' href="+url+encodeURI(arquivo.file)+" target='_blank'><i class='fa fa-file'></i> " +(i+1)+" - "+arquivo.file+"</a></li><hr>");
                    }
                });
            }
        });
    }

    function sendComment(id) {
        var texto = $('#texto'+id).val();
        $.ajax({
            type: "post",
            data: {
                comentario: texto,
                id_fatura: id
            },
            url: controller4,
            dataType: "json",
            success: function(dados){
                var data = new Date();
                var dia     = data.getDate();           // 1-31
                if (dia < 10){dia = "0"+dia}
                var mes     = data.getMonth();          // 0-11 (zero=janeiro)
                if (mes < 10){mes = "0"+mes}
                var ano    = data.getFullYear();       // 4 dígitos
                var hora    = data.getHours();          // 0-23
                if (hora < 10){hora = "0"+hora}
                var min     = data.getMinutes();        // 0-59
                if (min < 10){min = "0"+min}
                var seg     = data.getSeconds();        // 0-59
                if (seg < 10){seg = "0"+seg}
                var str_data = dia + '/' + (mes) + '/' + ano;
                var str_hora = hora + ':' + min + ':' + seg;
                alert("Comentário enviado!");
                window.location.reload()
            }
        });
    }

    function comments(id) {
        var url = "<?= site_url('faturas/getComments') ?>";
        $('#th_comment').html('');
        $.ajax({
            type: 'POST',
            url: url,
            data: {id_fatura: id},
            dataType: 'json',
            success: function(data2){
                var enviaId = "texto"+id;
                var tpl2 = [
                    '<ul><li><h4>Comentários <i class="fa fa-wechat"></i></h4></li></ul>'+
                    '<ul><li>'+
                    '<textarea style="width: 80%" id='+enviaId+' rows="4" cols="250" placeholder="Escreva Aqui..."></textarea><br>'+
                    '<button  class="btn btn-success" onclick="sendComment('+id+')" >Enviar</button>'+
                    '</li></ul>'
                ].join('');
                $('#th_comment').prepend(tpl2);

                $(enviaId).focus(function() {
                    $(this).val('');
                });

                $.each(data2, function (i, view) {
                    var tpl = [
                        '<div style="margin: 10px; background-color: #e3e3e3; padding: 20px; border-radius: 5px;">'+
                        '<p class="p_comment">'+view.comentario+'</p>'+
                        '<small>'+view.user+ ' <i class="fa fa-check" aria-hidden="true"></i></small>'+
                        '<small class="">' +view.data+ ' <i class="fa fa-clock-o" aria-hidden="true"></i></small>'+
                        '</div>'
                    ].join('');
                    $('#th_comment').append(tpl);
                });

                $('#modalComment').modal('show');
            }
        });
    }
</script>
<script>
    function cancelar_faturas(){
        if(!serialize_checkbox){
            alert('Nenhuma Fatura selecionada.');
            return false;
        }
        if(!$('textarea[name=motivo]').val()){
            alert('Insira o motivo.');
            return false;
        }
        if(!$('input[name=senha_exclusao]').val()){
            alert('Insira a senha.');
            return false;
        }
        var http = new XMLHttpRequest();
        var url = "<?=base_url()?>index.php/faturas/form_cancelar_varias_fatura";
        var params =serialize_checkbox+$('input[name=senha_exclusao]').serialize()+"&"+$('textarea[name=motivo]').serialize();
        http.open("POST", url, true);

        //Send the proper header information along with the request
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState == 4 && http.status == 200) {
                var retorno = JSON.parse(http.responseText);
                $('#resultado_cancelar_fatura').html(retorno.msg);
                $('input[name=senha_exclusao]').val('');
                retorno.status.forEach(
                    function retorno(data){
                        document.getElementById("status"+data).innerHTML ='<span class="label">Cancelado</span>';
                    }
                );
            }
        }
        http.send(params);
    }
    function imprimir_faturas(){
        if(!$('input[type=checkbox]').serialize()){
            alert('Nenhuma Fatura selecionada.');
            return false;
        }
        var html = "<style>@media print {@page { margin-top: 0cm;margin-bottom: 0cm; size: auto;}}</style>";
        var lista_boletos = [];
        $('input:[type=checkbox]:checked').each(function(){
            lista_boletos.push($(this).val());
        });

        lista_boletos.forEach(
            function func(data){
                var http = new XMLHttpRequest();
                var url = "<?=base_url()?>index.php/faturas/imprimir_fatura/"+data;

                http.open("GET", url, false);

                http.send(null);
                html+=http.responseText;
            }
        );
        var newWindow = window.open("");
        newWindow.document.write(html);
    }
    function checkBoxEvent(event){
        var http = new XMLHttpRequest();
        var url = "<?php echo base_url()?>index.php/faturas/setCheckBox";
        var params = "id="+event.value;
        http.open("POST", url, false);

        //Send the proper header information along with the request
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState == 4 && http.status == 200) {
            }
        }
        http.send(params);
    }
    function getcheckBox(){
        var http = new XMLHttpRequest();
        var url = "<?php echo base_url()?>index.php/faturas/getCheckBox";
        http.open("GET", url, true);

        //Send the proper header information along with the request
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState == 4 && http.status == 200) {
                serialize_checkbox="";
                var retorno = JSON.parse(http.responseText);
                var text = "";
                for (var prop in retorno) {
                    if(retorno[prop]){
                        text+="<br>"+prop;
                        serialize_checkbox+="cod_fatura%5B%5D="+prop+"&";
                    }
                }
                document.getElementById('lista_faturas').innerHTML = text;
                $('#cancela_fatura').modal();
            }
        }
        http.send(null);
    }

    function transFatura(id, status){
        var url = "<?= site_url('faturas/transFaturasCancelar')?>/"+ id +"/"+ status;
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            success: function (retorno) {
                alert(retorno.mensagem);
                window.location.reload()
            }
        })
    }

</script>
