
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script src="<?php echo base_url('media') ?>/js/jquery-mask.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets') ?>/plugins/priceformat/jquery.price_format.1.8.min.js"></script>

<style>
.input-container {
  display: flex;
  align-items: center;
}

.input-container label {
  margin-right: 10px;
}

.input-container input {
  border-radius: 5px;
  border: 1px solid #ccc;
  padding: 5px;

}
  .custom-input {
    border-radius: 5px;
    padding: 5px;
    border-left: 3px solid #03A9F4;
    padding-left: 10px;
  }

  .dataTables_wrapper .dataTables_processing {
        background: none;
        margin-top: 3rem !important;
    }
    .bord{
        border-left: 3px solid #03A9F4;
    }
    .dataTables_wrapper .dataTables_processing div {
        display: inline-block;
        vertical-align: center;
        font-size: 68px;
        height: 100%;
    }

</style>

<h3><?=lang('Pneushow')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('departamentos')?> > 
    <?=lang('financeiro')?> > 
    <a href="<?=site_url('Contas')?>">Contas</a> > 
    Pneushow
</div>

<div class="alert" style="display:none">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span></span>
</div>

<?php if( $msg ):?>
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>CONCLUIDO!</strong>
        <?php echo $msg?>
    </div>
<?php endif;?>

<h3>Contas a Pagar Pneu Show</h3>
<hr>
<div class="row-fluid">
    <?php if ($this->auth->is_allowed_block('valores')) { ?>

            <div style="display: flex;">
                <div class="card" id="card-orange" style="margin-right: 10px; width: 250px; height: 80px;">
                    <svg fill="#F89406" xmlns="http://www.w3.org/2000/svg" width="58" height="53" style="margin-left: 10px;" viewBox="0 0 21 22">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                        <path d="M0 0h24v24H0z" fill="none"/>
                    </svg>
                    <strong style="display: inline-block; margin-left: 10px;">A pagar: <br>R$ <?=number_format($estatisticas->pagar, 2, ',', '.')?></strong>
                </div>

                <div class="card" id="card-green" style="margin-right: 10px; width: 250px; height: 80px;">
                    <svg fill="#468847" xmlns="http://www.w3.org/2000/svg" width="58" height="53" style="margin-left: 10px;"  viewBox="0 0 21 22">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                        <path d="M0 0h24v24H0z" fill="none"/>
                    </svg>
                    <strong style="display: inline-block; margin-left: 10px;">Pagos: <br>R$ <?=number_format($estatisticas->pago, 2, ',', '.')?></strong>
                </div>

                <div class="card" id="card-blue" style="margin-right: 10px; width: 250px; height: 80px;">
                    <svg fill="#499BEA" xmlns="http://www.w3.org/2000/svg" width="58" height="53" style="margin-left: 10px;" viewBox="0 0 21 22">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                        <path d="M0 0h24v24H0z" fill="none"/>
                    </svg>
                    <strong style="display: inline-block; margin-left: 10px;">Entradas: <br>R$ <?=number_format($estatisticas->caixa, 2, ',', '.')?></strong>
                </div>

                <div id="card-red" class="card <?=$estatisticas->saldo < 0 ? 'saldo-negativo' : 'saldo-positivo'?>" style="margin-right: 10px; width: 250px; height: 80px;">
                    <?php if ($estatisticas->saldo >= 0): ?>
                        <svg fill="#468847" xmlns="http://www.w3.org/2000/svg" width="58" height="53" style="margin-left: 10px;" viewBox="0 0 21 22">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                            <path d="M0 0h24v24H0z" fill="none"/>
                        </svg>
                    <?php else: ?>
                        <svg fill="#BF0811" xmlns="http://www.w3.org/2000/svg" width="58" height="53" viewBox="0 0 21 22">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                            <path d="M0 0h24v24H0z" fill="none"/>
                        </svg>
                    <?php endif ?>
                    <strong style="display: inline-block; margin-left: 10px;">Saldo: <br>R$ <?=number_format($estatisticas->saldo, 2, ',', '.')?></strong>
                </div>
            </div>

    <?php } ?>
</div>
<br><br>

    <div style="display: contents;">
        <a href="#myModal" data-toggle="modal" data-target="#myModal" class="btn btn-primary" style="height: 40px;"><i class="fa fa-plus"></i> Adicionar Conta</a>
        <a class="btn btn-primary" style="height: 40px;" data-toggle="modal" data-target="#myModalEntrada" href="#myModalEntrada"><i class="fa fa-plus"></i> Adicionar Entrada</a>
        <a class="btn btn-primary" style="height: 40px;" data-toggle="modal" data-target="#view-entrada" href="<?=site_url('contas/lista_entradas')?>"><i class="fa fa-reorder"></i> Ver Entradas</a>
    </div>

<br><br>

<table id="myTable" class="table responsive table-bordered table">
    <thead style="background-color: white;">
        <tr>
            <th class="span1"></th>
            <th id="id" class="col-md-1" style="color: #03A9F4 !important;">Id</th>
            <th class="col-md-3" style="color: #03A9F4 !important;">Fornecedor</th>
            <th class="col-md-4" style="color: #03A9F4 !important;">Descrição</th>
            <th class="col-md-2" style="color: #03A9F4 !important;">Responsável</th>
            <th class="col-md-2" style="color: #03A9F4 !important;">Lançamento</th>
            <th class="col-md-1" style="color: #03A9F4 !important;">Vencimento</th>
            <th class="col-md-2" style="color: #03A9F4 !important;">Valor</th>
            <th class="col-md-1" style="color: #03A9F4 !important;">Status</th>
            <th class="col-md-1" style="color: #03A9F4 !important;">Pagamento</th>
            <th class="col-md-3" style="color: #03A9F4 !important;">Ferramentas</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>

<div id="myModal_update" class="modal fade" tabindex="-1" data-toggle="modal " role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>Pagamento</h3>
            </div>
            <form method="post" action='<?php echo site_url('contas/update/')?>' name="" id="form_update">
                <div class="modal-body" style="display: grid;">
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
                    <button type="submit" class="btn btn-primary" id="update">Pagar</button>
                </div>
            </form>
        </div>    
    </div>
</div>

<div id="myModal_editar" class="modal fade" tabindex="-1" data-toggle="modal " role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-header"  style="border-bottom: 3px solid #03A9F4;">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3>Editar Conta</h3>
        </div>
        <div class="modal-content" style="border:none;">
            <form method="post" action='<?php echo site_url('contas/update')?>' name="" id="form_editar">
                <div class="modal-body" style="display: grid; border-radius: 5px; border: 1px solid #ccc;">
                    <div class="alert fornec" style="display:none">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span></span>
                    </div>
                    <div class="form-group" style="border-left: 3px solid #03A9F4;padding-left: 10px; display: inline-grid;">
                        <label class="control-label">Fornecedor</label>
                        <input type="hidden" name="id" id="fornecedor_id">
                        <input type="text" class=" form-control span5" name="fornecedor" id="fornecedor" placeholder="Fornecedor" required>
                    </div>
                    <div class="form-group" style="border-left: 3px solid #03A9F4;padding-left: 10px; display: inline-grid;">
                        <label class="control-label">Categoria</label>
                        <input type="text" class="form-control span5" name="categoria" id="categoria" placeholder="Categoria" required>
                    </div>
                    <div class="form-group" style="border-left: 3px solid #03A9F4;padding-left: 10px; display: inline-grid;">
                        <label class="control-label">Valor</label>
                        <input type="text" class="form-control span3 money2" id="valor" name="valor" placeholder="0.00" required>

                    </div>
                    <div class="form-group" style="border-left: 3px solid #03A9F4;padding-left: 10px; display: inline-grid;">
                        <label class="control-label">Vencimento</label>
                        <input type="text" class="form-control date" name="dt_vencimento" id="dt_vencimento" placeholder="__/__/___" required>

                    </div>
                    <div class="form-group" style="border-left: 3px solid #03A9F4;padding-left: 10px; display: inline-grid;">
                        <label class="control-label">Descriçao</label>
                        <input type="text" class="form-control span5" name="descricao-forn" id="descricao-forn" placeholder="Descriçao" required>
                    </div>
                    <br>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                    <button class="btn btn-primary" id="editar">Editar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModal_cancel" class="modal fade" tabindex="-1" data-toggle="modal " role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3>Cancelamento</h3>
        </div>
        <div class="modal-content" style="padding-top: 10px; border:none; box-shadow: none;">
            <form method="post" action='<?php echo site_url('contas/update/')?>' id="form_cancel">
                    <div class="modal-body">
                        <label>Tem certeza que deseja cancelar?</label>
                        <input type="hidden" name="id" id="cancel-id">
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                        <button type="submit" class="btn btn-primary" id="cancelar">Sim</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<div id="view-entrada" class="modal fade" tabindex="-1" data-toggle="modal " role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3>Entradas do Mês</h3>
        </div>
        <div class="modal-content" style="padding-top: 10px; border:none; box-shadow: none;">
            <div class="modal-body">
                carregando entradas...
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
        </div>
    </div>
</div>

<div id="myModal_digitalizar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog" style="background-color: #fff!important;">
            <div class="modal-content" style="border:none;">
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                </div>
            </div>
    </div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3 style="text-align: center;">Adicionar Conta</h3>
        </div>
        <div class="modal-content" style="border:none;">
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <form method="post" action='<?php echo site_url('contas/addPneushow')?>'>
                            <div class="form-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label class="control-label">Fornecedor</label>
                                <input type="text" class="form-control" name="fornecedor" id="fornecedor" data-provide="typeahead" data-source='<?php echo $fornecedores?>' data-items="6" placeholder="Fornecedor" autocomplete="off" value="<?php echo $this->input->post('fornecedor') ?>" required>
                            </div>
                            <div class="form-group col-md-6" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label class="control-label">Valor</label>
                                <input type="text" class="form-control money2" name="valor" id="valor" placeholder="0,00" required>
                            </div>
                            <div class="form-group col-md-6" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label class="control-label">Data Vencimento</label>
                                <input type="date" class="form-control" name="data_vencimento" id="data_vencimento" autocomplete="off" value="" required>
                            </div>
                            <div class="form-group" style="border-left: 3px solid #03A9F4;padding-left: 10px;">
                                <label class="control-label">Descrição</label>
                                <input class="form-control" name="descricao" id="descricao" placeholder="Descrição" required />
                            </div>
                            <br>
                            <div class="modal-footer">
                                <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                                <button type="submit" class="btn btn-primary">Adicionar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModalEntrada" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h3 style="text-align: center">Adicionar Entrada</h3>
        </div>
        <div class="modal-content" style="border: none;">
            <div class="modal-body">
                <form method="post" onsubmit="event.preventDefault();" name="form" id="form">
                    <div class="form-group col-md-6" style="display: inline-grid; border-left: 3px solid #03A9F4; padding-left: 10px;">
                        <label class="control-label">Data Entrada</label>
                        <input type="date" class="form-control" id="dataPneu" name="data" required>
                    </div>
                    <div class="form-group col-md-6" style="display: inline-grid; border-left: 3px solid #03A9F4; padding-left: 10px;">
                        <label class="control-label">Valor</label>
                        <input type="text" class="span3 money2 form-control" id="valorPneu" name="valor" placeholder="0.00" required>
                    </div>
                    <div class="form-group" style="display: inline-grid; border-left: 3px solid #03A9F4; padding-left: 10px;">
                        <label class="control-label">Descrição</label>
                        <input type="text" class="span3 form-control" name="descricao" id="descricaoPneu" placeholder="Descrição" required>
                    </div>
                    <br>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-default" data-dismiss="modal">Fechar</a>
                        <button type="submit" class="btn btn-primary" id="add-entrada">Adicionar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#myTable').on('processing.dt', function () {
            $('.dataTables_processing')[0].scrollIntoView({
                behavior: 'smooth',
                block: 'center',
            });
        }).DataTable( {
            "ajax": {
                "url": "<?= site_url('contas/contas_ajax/2') ?>",
                "data": function (d) {
                    d.start = d.start || 0;
                    d.length = d.length || 10;
                    d.per_page = d.length;
                }
            },
            ordering: false,
            paging: true,
            info: true,
            processing: true,
            serverSide: true,
            lengthChange: false,
            "bLengthChange": false,
            searching: true,
            language: {
                sProcessing: '<STRONG >Processando... </STRONG>' + '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:#06a9f6;"></i>',
                search:             'Pesquisar',
                emptyTable:         "Nenhum resultado a ser listado",
                info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
                infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
                zeroRecords:        "Nenhum resultado encontrado.",
                paginate: {
                    first:          "Primeira",
                    last:           "Última",
                    next:           "Próxima",
                    previous:       "Anterior"
                },
                lengthMenu:         "Mostrar _MENU_ resultados por página",
            },
            
        });

        $("#someSwitchOptionPrimary").click(function () {
            if( $("#someSwitchOptionPrimary").is(':checked') ){
                table.ajax.url( "<?= site_url('contas/contas_por_inst') ?>" ).load();
            } else{
                table.ajax.url( "<?= site_url('contas/contas_ajax/2') ?>" ).load();
            }
        });

        $(document).ready(function() {
            $("#myModal form").submit(function(event) {
                event.preventDefault();

                var form = $(this);

                $.ajax({
                url: form.attr("action"),
                method: form.attr("method"),
                data: form.serialize(),
                success: function(response) {
                    alert("Conta adicionada com sucesso!");
                    $("#myModal").modal("hide");
                    $("#fornecedor, #descricao, #valor, #data_vencimento").val("");

                },
                error: function(xhr, status, error) {
                    alert("Erro ao adicionar conta: " + error);
                }
                });
            });
        });

        $('#myModal').on('hidden.bs.modal', function () {
            $("#fornecedor, #descricao, #valor, #data_vencimento").val("");
        });

        $('#update').on('click', function(e) {
            $('#money').focus();
            e.preventDefault();
            var form = $(this).closest('form').serialize();
            var url = $('#form_update').attr('action');
            $.post(url, form, function (data){
                if(data=="4"){
                    alert('Senha incorreta!');
                }
                else if(data=="3"){
                    alert('Usuário sem permissão!');
                }
                else{
                    alert('Pagamento efetuado com sucesso!');
                    table.ajax.reload(null, false);
                }

                $('#myModal_update').modal('hide');
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

        $('#editar').on('click', function (e) {
            e.preventDefault();
            var form = $(this).closest('form').serialize();
            var url = $('#form_editar').attr('action');
            if ($('#fornecedor').val() != '') {
                $.post(url, form, function (data) {
                    if (data == 1) {
                        exibirMensagem();
                        table.ajax.reload(null, false);
                    } else {
                        $(".fornec").find('span').html('').html('Não foi possível editar o fornecedor. Tente novamente.');
                        $(".fornec").addClass('alert-danger').show();
                    }
                });
            } else {
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
        var dataPneu = $('#dataPneu').val();
        var valorPneu = $('#valorPneu').val();
        var descricaoPneu = $('#descricaoPneu').val();

        if (!dataPneu || !valorPneu || !descricaoPneu) {
            alert('Preencha todos os campos antes de adicionar uma entrada.');
            return;
        }

        $.ajax({
            url: 'contas/add_entrada',
            type: 'POST',
            data: {
                'data': dataPneu,
                'valor': valorPneu,
                'descricao': descricaoPneu
            },
            success: function () {
                alert('Entrada cadastrada com sucesso');
                $("#myModalEntrada").modal("hide");
                $("#dataPneu, #valorPneu, #descricaoPneu, #data_vencimento").val("");

            },
            error: function (xhr, status, error) {
                alert('Erro ao cadastrar entrada. Tente novamente mais tarde.');
            }
        });
    });

    $('#myModalEntrada').on('hidden.bs.modal', function () {
            $("#dataPneu, #valorPneu, #descricaoPneu").val("");
        });

    $(document).ready(function(){

        $('.data').mask('99/99/9999');
        $('.tel').mask('(99) 9999-9999');
        $('.hora').mask('99:99:99');
        $('.cep').mask('99999-999');
        $('.cpf').mask('999.999.999-99');
        $('.placa').mask('aaa9999');
        $('.mes_ano').mask('99/9999');
        $('.money').mask('000000000.00');
        $('.money2').maskMoney({symbol:'R$ ', showSymbol:true, thousands:'.', decimal:',', symbolStay: true});
        $("#ajax").css('display', 'none');

        $(document).on('focus', '.ref', function(){ $('.ref').mask('00/0000') });

        $('.money').priceFormat({
            limit: 5,
            centsLimit: 3
        });

    });
    
</script>
