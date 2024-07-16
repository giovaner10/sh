<style>
    html {
        scroll-behavior:smooth
    }

    body {
        background-color: #fff !important;
    }
    
    #loading {
        display: block;
        z-index: 9999;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
    }

    .loader {
        margin-top: 20%;
        margin-left: 45%;
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }
    
</style>
<div style="padding-left: 15px;">
    <h3><?=lang('levantamento')?></h3>

    <div id="loading">
        <div class="loader"></div>
    </div>


    <div class="div-caminho-menus-pais">
        <a href="<?=site_url('Homes')?>">Home</a> > 
        <?=lang('relatorios')?> >
        <?=lang('levantamento')?>
    </div>

    <hr>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a id="tab-pedidosGerados" data-toggle="tab" href="" class="nav-link active"><?=lang('pedidos_gerados')?></a>
        </li>
        <li class="nav-item">
            <a id="tab-pedidosGeradosNF" data-toggle="tab" href="" class="nav-link"><?=lang('pedidos_gerados_NF')?></a>
        </li>
        <li class="nav-item">
            <a id="tab-pedidosGeradosNFAmarraBI" data-toggle="tab" href="" class="nav-link"><?=lang('pedidos_nf_amarra_bi')?></a>
        </li>
        <li class="nav-item">
            <a id="tab-pedidosGeradosNFAmarraBIExpedicao" data-toggle="tab" href="" class="nav-link"><?=lang('pedidos_gerados_nf_amarra_bi_expedicao')?></a>
        </li>
        <li class="nav-item">
            <a id="tab-pedidosGeradosNFAmarraBIRomaneio" data-toggle="tab" href="" class="nav-link"><?=lang('pedidos_gerados_nf_amarra_bi_romaneio')?></a>
        </li>
        <li class="nav-item">
            <a id="tab-kanbanPedidos" data-toggle="tab" href="" class="nav-link"><?=lang('kanban_pedidos_gerados')?></a>
        </li>
    </ul>

    <div id="div-tabela">
        
    </div>

</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>
<!-- Masks -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tab-pedidosGerados').click();
    });
    

    $('.nav-link').click(function() {
        $("#loading").show();
        var id = $(this).attr('id');

        switch (id) {
            case 'tab-pedidosGerados':
                
                $('#div-tabela').load('levantamentoPedidos/pedidosGerados', function() {
                    $("#loading").hide();
                });
                break;
            case 'tab-pedidosGeradosNF':
                $('#div-tabela').load('levantamentoPedidos/pedidosGeradosComNFGerada', function () {
                    $("#loading").hide();
                });
                break;
            case 'tab-pedidosGeradosNFAmarraBI':
                $('#div-tabela').load('levantamentoPedidos/pedidosGeradosNFAmarraBI', function () {
                    $("#loading").hide();
                });
                break;
            case 'tab-pedidosGeradosNFAmarraBIExpedicao':
                $('#div-tabela').load('levantamentoPedidos/pedidosGeradosComNFAmarraBiExpedicao', function () {
                    $("#loading").hide();
                });
                break;
            case 'tab-pedidosGeradosNFAmarraBIRomaneio':
                $('#div-tabela').load('levantamentoPedidos/pedidosGeradosComNFAmarraBiRomaneio', function () {
                    $("#loading").hide();
                });
                break;
            case 'tab-kanbanPedidos':
                $('#div-tabela').load('levantamentoPedidos/kanbanPedidos', function () {
                    $("#loading").hide();
                });
                break;
        }

    });
</script>