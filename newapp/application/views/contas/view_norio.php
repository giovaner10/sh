<link href="<?php echo base_url('media') ?>/css/contas.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>

<style>
    .overlay {
        position: fixed;
        width: 100%;
        height: 100%;
        left: 0;
        top: -130px;
        background: rgba(70,20,15,0.3);
        z-index: 2;
        background-image: url(<?=base_url()?>media/img/loading2.gif);
        background-repeat: no-repeat;
        background-position: center center;
        background-size: 100px;
    }
</style>

<div class="alert" style="display:none">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span></span>
</div>

<?php if (!empty($this->session->flashdata('sucesso'))) { ?>
    <div class="alert alert-success">
        <strong>Successo!</strong> <?= $this->session->flashdata('sucesso') ?>
    </div>
<?php } elseif (!empty($this->session->flashdata('erro'))) { ?>
    <div class="alert alert-danger">
        <strong>Erro!</strong> <?= $this->session->flashdata('erro') ?>
    </div>
<?php } ?>

<div class="row-fluid">
    <div class="span9">
        <h3 style="color: #7c7c7c;">Contas a Pagar Norio Momoi</h3>
    </div>

    <div class="span3 card-blue">
        <div class="card" id="card-blue">
            <svg fill="#499BEA" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                <path d="M0 0h24v24H0z" fill="none"/>
            </svg>
            <strong>Caixa: <br>R$ <?=number_format($estatisticas->caixa_consolidado, 2, ',', '.')?></strong>
        </div>
    </div>
</div>
<div class="row-fluid">
    <?php if ($this->auth->is_allowed_block('valores')) { ?>
        <fieldset>
            <legend style="font-size: 15px; color: #737373;">Período: <span>01/<?= date('m/Y'); ?> à <?= date('d/m/Y') ?></span></legend>
            <div class="span3 card-orange" style="margin-left: 0px;">
                <div class="card" id="card-orange">
                    <svg fill="#F89406" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                        <path d="M0 0h24v24H0z" fill="none"/>
                    </svg>
                    <strong>A pagar: <br>R$ <?=number_format($estatisticas->pagar, 2, ',', '.')?></strong>
                </div>
            </div>

            <div class="span3 card-green">

                <div class="card" id="card-green">
                    <svg fill="#468847" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                        <path d="M0 0h24v24H0z" fill="none"/>
                    </svg>
                    <strong>Pagos: <br>R$ <?=number_format($estatisticas->pago, 2, ',', '.')?></strong>
                </div>
            </div>

            <div class="span3 card-blue">
                <div class="card" id="card-blue">
                    <svg fill="#499BEA" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                        <path d="M0 0h24v24H0z" fill="none"/>
                    </svg>
                    <strong>Entradas: <br>R$ <?=number_format($estatisticas->caixa, 2, ',', '.')?></strong>
                </div>
            </div>

            <div class="span3 card-red">
                <div id="card-red" class="card <?=$estatisticas->saldo < 0 ? 'saldo-negativo' : 'saldo-positivo'?>">
                    <?php if ($estatisticas->saldo >= 0): ?>
                        <svg fill="#468847" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                            <path d="M0 0h24v24H0z" fill="none"/>
                        </svg>
                    <?php else: ?>
                        <svg fill="#BF0811" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                            <path d="M0 0h24v24H0z" fill="none"/>
                        </svg>
                    <?php endif ?>
                    <strong>Saldo: <br>R$ <?=number_format($estatisticas->saldo, 2, ',', '.')?></strong>
                </div>
            </div>
        </fieldset>
    <?php } ?>
</div>
<br><br>
<div class="row-fluid">
    <div class="boxbutton span12">
        <!--<a href="#myModal" data-toggle="modal" class="btn-design btn btn-primary"><i class="icon-plus icon-white"></i> Adicionar Conta</a>-->
        <a class="btn btn-design-green" data-toggle="modal" href="#cad-entrada"><i class="icon-plus icon-white"></i> Adicionar Entrada</a>
        <a class="btn btn-design-red" data-toggle="modal" data-target="#view-entrada" href="<?=site_url('contas/lista_entradas_norio')?>"><i class="fa fa-reorder"></i> Ver Entradas</a>
        <a href="#cadCateg" data-toggle="modal" class="btn-design btn btn-primary"><i class="icon-plus icon-white"></i> Adicionar Categoria</a>
        <a class="btn-design btn btn-primary" type="button" href="<?=base_url();?>index.php/contas/remessas_norio"><i class="fa fa-gears"></i> Remessas</a>
    </div>

</div>
<br><br>
<table id="myTable" class="table table-hover display">
    <thead>
    <th id="id" class="span1">Id</th>
    <th class="span3">Fornecedor</th>
    <th class="span4">Descrição</th>
    <th class="span2">Categoria</th>
    <th class="span2">Responsável</th>
    <th class="span1">Lançamento</th>
    <th class="span1">Vencimento</th>
    <th class="span2">Valor</th>
    <th class="span1">Status</a></th>
    <th class="span1">Pagamento</a></th>
    <th class="span3">Ferramentas</th>
    </thead>
    <tbody align="center">
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="width: 15%"><i style="color: #499BEA" id="spin" class="fa fa-refresh fa-spin fa-3x fa-fw"></i></td>
    <td></td>
    <td></td>
    <td></td>
    </tbody>
</table>

<div class="modal hide" id="myModal">
    <div class="modal-header">
        <button type="button" class="close" id="close0" data-dismiss="modal">x</button>
        <h3>Adicionar Conta</h3>
    </div>
    <form method="post" action='<?php echo site_url('contas/addNorio')?>' name="" id="form">
    <div class="modal-body">
        <div id="load" style="display:none;" class="overlay"></div>
            <label>Categoria</label>
            <select required class="form-control" onchange="verifica_categoria(this)" id="categoria" name="categoria">
                <?php foreach ($categorias as $categoria): ?>
                    <option><?= $categoria; ?></option>
                <?php endforeach; ?>
            </select>
            <span id="fornecedor_span">
                
            </span>
            <label>Descrição</label>
            <textarea type="text" class="span3" name="descricao" id="descricao" placeholder="Descrição" required></textarea>

            

            <label>Tipo Movimentação</label>
            <select class="form-control" id="mov" name="tipo_mov" disabled>
                <option>TRANSFERENCIA</option>
                <option>BOLETO</option>
            </select>

            <label>Valor</label>
            <input type="text" class="span3 money2" name="valor" placeholder="0,00" required>

            <label>Data Vencimento</label>
            <input type="text" class="span3 date" name="data_vencimento" placeholder="__/__/___" required>

            <br>
            
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-default" id="close" data-dismiss="modal">Fechar</a> 
        <button type="submit" class="btn btn-primary" id="add">Adicionar</button>
    </div>
    </form>
</div>

<div class="modal hide" id="myModal_editar">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h3>Editar Conta</h3>
    </div>
    <form method="post" action='<?php echo site_url('contas/update')?>' name="" id="form_editar">
        <div class="modal-body">
            <div class="alert fornec" style="display:none">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span></span>
            </div>
            <label>Fornecedor</label>
            <input type="hidden" name="id" id="fornecedor_id">
            <input type="text" class="span5" name="fornecedor" id="fornecedor" placeholder="Fornecedor" required>
            <label>Categoria</label>
            <input type="text" class="span5" name="categoria" placeholder="Categoria" required>
            <label>Valor</label>
            <input type="text" class="span5" name="valor" id="valor" placeholder="Valor" required>
            <label>Vencimento</label>
            <input type="text" class="span5" name="dt_vencimento" id="dt_vencimento" placeholder="Vencimento" required>
            <label>Descriçao</label>
            <input type="text" class="span5" name="descricao-forn" id="descricao-forn" placeholder="Descriçao" required>
            <br>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
            <button class="btn btn-primary" id="editar">Editar</button>
        </div>
    </form>
</div>

<div class="modal hide" id="myModal_update">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h3>Pagamento</h3>
    </div>
    <form id="formPagamento" method="post" action=''>
        <div class="modal-body">
            <input type="hidden" name="id" id="idid" value="" >
            <label>Senha</label>
            <input type="password" class="span3" name="senha_pagamento" id="senha_pagamento" value="" required>
            <label>Valor Pago</label>
            <input type="text" class="span3 money2" name="valor_pago" id="money" value="" required>
            <label>Data Pagamento</label>
            <input type="text" class="span3 date" name="data_pagamento" id="data" placeholder="__/__/___" required>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
            <button type="submit" class="btn btn-primary">Pagar</button>
        </div>
    </form>
</div>

<div class="modal hide" id="myModalEstornar">
    <div class="modal-header">
        <button type="button" class="close" id="close0" data-dismiss="modal">x</button>
        <h3>Estornar Pagamento <span id="titulo_id_conta"></span></h3>
    </div>
    
    <div class="modal-body">
        <form id="form_estornar">
            <input type="hidden" id="input_id_conta_estornar" name="id_conta" required>
            <label>senha</label>
            <input type="password" class="span3" id="input_senha_estornar" name="senha" required value="">
            <label>Observações</label>
            <input type="text" class="span3" id="input_observacoes_estornar" name="observacoes" required>
        </form>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-default" id="close" data-dismiss="modal">Fechar</a>
        <a class="btn btn-success" onclick="request_estornar()">Estornar</a>
    </div>
</div>

<div class="modal hide" id="myModal_cancel">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h3>Cancelamento</h3>
    </div>
    <form method="post" action='<?php echo site_url('contas/update/')?>' id="form_cancel">
        <div class="modal-body">
            <label>Tem certeza que deseja cancelar?</label>
            <input type="hidden" name="id" id="cancel-id">
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
            <button type="submit" class="btn btn-danger" id="cancelar">Sim</button>
        </div>
    </form>
</div>

<div id="myModal_digitalizar" class="modal hide fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-header" style="text-align: center;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel">Digitalizar Conta</h4>
    </div>
    <div class="modal-body">
    </div>
</div>

<div class="modal hide" id="cad-entrada">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h3>Adicionar Entrada</h3>
    </div>
    <div class="modal-body">      

        <label>Data Entrada</label>
        <input type="text" class="span3 date" id="dataNorio" name="data" placeholder="__/__/___" required>

        <label>Valor</label>
        <input type="text" class="span3 money2" id="valorNorio" name="valor" placeholder="0.00" required>


        <label>Descrição</label>
        <textarea type="text" class="span3" name="descricao" id="descricaoNorio" placeholder="Descrição" required></textarea>

        <br>
        <button type="submit" class="btn btn-primary" id="add-entrada">Adicionar</button>
        </form>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
    </div>
</div>

<!-- MODAL DE CADASTRO DE CATEGORIAS -->
<div id="cadCateg" class="modal hide" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Nova Categoria</h4>
            </div>
            <form action="<?= site_url('contas/add_categoria') ?>" method="POST">
                <div class="modal-body">
                    <label for="categ">Categoria: </label>
                    <input id="categ" name="categoria" />
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Cadastrar</button>
                </div>
            </form>
        </div>

    </div>
</div>

<div class="modal hide" id="view-entrada">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h3>Entradas do Mês</h3>
    </div>
    <div class="modal-body">
        carregando entradas...
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
    </div>
</div>
<script src="<?php echo base_url('media') ?>/js/validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#formPagamento').submit(function(){
            var url = "<?php echo site_url('contas/update/')?>";
            var dados = jQuery( this ).serialize();

            jQuery.ajax({
                type: "POST",
                url: url,
                data: dados,
                success: function()
                {
                    table.ajax.reload(null, false);

                    alert('Pagamento efetuado com sucesso!');
                    $('#myModal_update').modal('hide');
                }
            });

            return false;
        });
    });

    $(document).ready(function() {
        var table = $('#myTable').DataTable( {
            responsive: true,
            serverSide: true,
            ajax: "<?= site_url('contas/contas_ajax/3') ?>",
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
            },
            ordering: false
        });

        $("#close").click(function () {
            $("#add").prop("disabled", false);
        });

        $("#close0").click(function () {
            $("#add").prop("disabled", false);
        });

        $('#add').on('submit', function(e) {
            e.preventDefault();
            var form = $(this).closest('form').serialize();
            var url = $('#form').attr('action');
            $.post(url, form, function (data){
                if (data && data > 0) {
                    $("#add").prop("disabled", true);
                    $('#form').trigger('reset');
                    table.ajax.reload(null, false);
                }else{
                    $(".alert").find('span').html('').html('Não foi possível adicionar a conta. Tente novamente.');
                    $(".alert").addClass('alert-danger').show();
                }
            });
        });

        $('#update').on('click', function(e) {
            $('#money').focus();
            e.preventDefault();
            var form = $(this).closest('form').serialize();
            var url = $('#form_update').attr('action');
            $.post(url, form, function (data){
                if (data == 1) {
                    table.ajax.reload(null, false);
                }else{
                    $(".alert").find('span').html('').html('Não foi possível atualizar a conta. Tente novamente.');
                    $(".alert").addClass('alert-danger').show();
                }
            });
        });

        $('#cancelar').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form').serialize();
            var url = $('#form_cancel').attr('action');
            $.post(url, form, function (data){
                if (data == 1) {
                    table.ajax.reload(null, false);
                }else{
                    $(".alert").find('span').html('').html('Não foi possível cancelar a conta. Tente novamente.');
                    $(".alert").addClass('alert-danger').show();
                }
            });
        });

        $('#editar').on('click', function(e) {
            e.preventDefault();
            $('#editar').attr('disabled', 'disabled').text('Salvando...');
            var form = $(this).closest('form').serialize();
            var url = $('#form_editar').attr('action');
            if($('#fornecedor').val() != ''){
                $.post(url, form, function (data){
                    if (data) {
                        table.ajax.reload(null, false);
                    }else{
                        $('#editar').removeAttr('disabled').text('Salvar');
                        $(".fornec").find('span').html('').html('Não foi possível editar o fornecedor. Tente novamente.');
                        $(".fornec").addClass('alert-danger').show();
                    }
                });
            }else{
                $('#editar').removeAttr('disabled').text('Salvar');
                $(".fornec").find('span').html('').html('Preencha o campo fornecedor corretamente.');
                $(".fornec").addClass('alert-danger').show();
            }

        });

        $('.date').mask('99/99/9999', {clearIfNotMatch: true});

        $('#myModal_digitalizar').on('hidden', function(){
            $(this).data('modal', null);
        });

    });
    function update(param) {
        $('#money').focus();
        var controller = $(param).data('controller');
        var id = $(param).data('conta');
        var valor = $(param).data('valor');
        var now = new Date();
        $('#idid').val(id);
        $('#money').val(valor);
        $.ajax({
            type: "post",
            data: {id: id},
            url: controller,
            dataType: "json",
            success: function(data){
                if(data.updated != 1 || data.status == 0) {
                    $('#id').val(data.id);
                    $('#money').val(data.valor);
                    $('#data').val(now.getDate()+'/'+(now.getMonth()+1)+'/'+now.getFullYear());
                    $("#myModal_update").modal('show');
                }
            }
        });
    }

    function cancel(param) {
        var controller = $(param).data('controller');
        var id = $(param).data('conta');
        if(!$(param).hasClass('disabled')) {
            $('#cancel-id').val(id);
            $("#myModal_cancel").modal('show');
        }else{
            $("#myModal_cancel").modal('hide');
        }
    }

    function edit(param) {
        $('#fornecedor').val($(param).data('fornecedor'));
        $('#categoria').val($(param).data('categoria'));
        $('#valor').val($(param).data('valor'));
        $('#dt_vencimento').val($(param).data('vencimento'));
        $('#descricao-forn').val($(param).data('descricao'));
        $('#fornecedor_id').val($(param).data('id'));
    }

    $('#add-entrada').click(function () {

        $.ajax({
           url: 'add_entrada_norio',
           type: 'POST',
           data: {
               'data' : $('#dataNorio').val(),
               'valor' : $('#valorNorio').val(),
               'descricao' : $('#descricaoNorio').val()
           },
            success : function(){
                table.ajax.reload(null, false);
            }
        })

    })
</script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/select2.css">

<script src="<?=base_url()?>assets/js/select2.js"></script>
<script>
    function verifica_categoria(e){
        
        if(e.value=='SALÁRIO'||e.value=='SALARIO'||e.value=='ADIANTAMENTO SALARIAL'||e.value=='AJUDA DE CUSTO'||e.value=='RESCISÃO'||e.value=='RESCISAO'||e.value=='FÉRIAS'||e.value=='FERIAS'||e.value=='DÉCIMO TERCEIRO'||e.value=='13º SALÁRIO'||e.value=='DECIMO TERCEIRO'||e.value=='PRO-LABORE'||e.value=='ADIANTAMENTO BENEFICIO'){
            document.getElementById("load").style.display=null;
            var html='<input type="hidden" id="id_conta" name="id_conta" value="-1"/> <label>Funcionário</label><select onchange="get_conta()" name="fornecedor" id="fornecedor" class="span3" required><option></option>';
            $.getJSON('<?=base_url()?>index.php/contas/get_funcionarios', function (data){
                $.each(data,function (index, json){
                    html+='<option data-id="'+json.id+'">'+json.nome+'</option>'
                });
                html+="</select><span id='conta_fornecedor'></span>"
                $('#fornecedor_span').html(html);
                $('#fornecedor').select2();
                document.getElementById("load").style.display="none";
            });
        }
        else{
            var html=['<input type="hidden" id="id_conta" name="id_conta" value="-1"/><label>Fornecedor</label>',
            '<input type="text" name="fornecedor" class="span3"',
                'data-provide="typeahead" data-source=\'<?php echo $fornecedores?>\'',
                'data-items="6"',
                'placeholder="Fornecedor"',
                'autocomplete="off" value="<?php echo $this->input->post('fornecedor') ?>" />'];
                $('#fornecedor_span').html(html.join(''));
        }
    }
    function get_conta(){
        document.getElementById("load").style.display=null;
        $.getJSON('<?=base_url()?>index.php/contas/get_conta_funcionario/'+$('#fornecedor option:selected').attr('data-id'), function (data){
            if(data.conta){
                $('#conta_fornecedor').html('<label>Conta</label><input type="text" value="CPF: '+data.cpf+', Agência: '+data.agencia+', conta: '+data.conta+'" class="span5" disabled="disabled">');
                $('#id_conta')[0].value=data.id;
            }
            else{
                $('#conta_fornecedor').html('<label>Conta</label><input type="text" value="Conta não encontrada, verifique o cadastro" class="span5" disabled="disabled">');
                $('#id_conta')[0].value="-1";
            }
            document.getElementById("load").style.display='none';
            
        });
    }
</script>

<script>
    function estornar(id){
        $('#titulo_id_conta')[0].innerHTML = "#"+id.toString();
        $('#input_id_conta_estornar')[0].value=id;
        $('#myModalEstornar').modal();
    }
    function request_estornar(){
        if(!$('#input_senha_estornar')[0].value){
            alert("Insira a senha!");
            return;
        }
        jQuery.ajax({
            type: "POST",
            url: "<?=base_url()?>index.php/contas/estornar",
            data: $('#form_estornar').serialize(),
            success: function(data)
            {
                if(data=="4"){
                    alert('Senha incorreta!');
                }
                else if(data=="3"){
                    alert('Usuário sem permissão!');
                }
                else{
                    table.ajax.reload(null, false);
                    alert('Pagamento estornado com sucesso!');
                }
                $('#myModalEstornar').modal('hide');
            }
        });
    }
</script>