<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.dataTables.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/dataTables.material.min.js');?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/css/dataTables.material.min.css');?>">
<style>
    .modal-header {
        text-align: center;
    }
    .btn-new {
        margin: 10px;
    }
</style>
<button type="button" class="btn btn-new btn-primary" data-toggle="modal" data-target="#modalCad"><i class="fa  fa-plus-circle"></i> Adicionar</button>
<!-- Modal Cadastro de Planos -->
<div id="modalCad" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cadastrar Plano</h4>
			</div>
			<div class="modal-body">
				<div>
					<input style="margin-bottom: 10px; width: 98%;" type="text"	class="form-control" name="nome" placeholder="Digite o nome do plano" required />
					<textarea class="form-control" style="width: 98%" placeholder="Digite aqui as observações" rows="5" name="observacoes"></textarea>
				</div>
				<table id="tablePerm" class="table table-bordered display" style="width: 100%">
					<thead>
						<tr>
							<th><input type="checkbox" class="checked_all" data-form="myForm" /></th>
							<th>Descrição</th>
							<th>Categoria</th>
						</tr>
					</thead>
				</table>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-submit btn-success">Salvar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form id="myForm" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cadastrar Plano</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <input style="margin-bottom: 10px; width: 98%;" type="text" class="form-control" name="nome" placeholder="Digite o nome do plano"  required/>
                        <textarea class="form-control" style="width: 98%" placeholder="Digite aqui as observações" rows="5" name="observacoes"></textarea>
                    </div>
                    <table id="tablePerm" class="table table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="checked_all" data-form="myForm"/></th>
                                <th>Descrição</th>
                                <th>Categoria</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-submit btn-success">Salvar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Edita Plano -->
<div id="modalEdit" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Editar Plano</h4>
			</div>
			<div class="modal-body">
				<div>
                    <input name="id" type="hidden" />
                    <input style="margin-bottom: 10px; width: 98%;" type="text" class="form-control" name="nome" placeholder="Digite o nome do plano"  required/>
                    <textarea class="form-control" style="width: 98%" placeholder="Digite aqui as observações" rows="5" name="observacoes"></textarea>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form id="myEdit" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Plano</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <input name="id" type="hidden" />
                        <input style="margin-bottom: 10px; width: 98%;" type="text" class="form-control" name="nome" placeholder="Digite o nome do plano"  required/>
                        <textarea class="form-control" style="width: 98%" placeholder="Digite aqui as observações" rows="5" name="observacoes"></textarea>
                    </div>
                    <table id="editPerm" class="table table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="checked_all" data-form="myEdit"/></th>
                                <th>Descrição</th>
                                <th>Categoria</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-submit btn-success">Salvar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
                <table id="editPerm" class="table table-bordered display" style="width:100%">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="checked_all" data-form="myEdit"/></th>
                            <th>Descrição</th>
                            <th>Categoria</th>
                        </tr>
                    </thead>
                </table>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-submit btn-success">Salvar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>
<table id="example" class="table table-bordered display" style="width:100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nome</th>
            <th>Data do Cadastro</th>
            <th>Última Alteração</th>
            <th>Status</th>
            <th style="width: 200px">Ações</th>
        </tr>
    </thead>
    <tfoot>
    </tfoot>
</table>
<script type="text/javascript">
    // Controlador do ID_PLANO
    var id_plano;

    $(document).ready( function () {
        /** Checked All */
        $(document).on('change', '.checked_all', function () {
            let checked = false;
            let form = $(this).data('form');
            
            if ($(this).attr('checked'))
                checked = true;

            $('form#'+form+' input[type="checkbox"]').each(function (i, a) {
                    $(a).attr('checked', checked);
            });
        });

        /** Abre Modal de Edição */
        $(document).on('click', '.editPerm', function() {
            id_plano = $(this).data('id');

            $.ajax({
                url: "<?= site_url('cadastros/getPlano') ?>",
                type: "GET",
                dataType: "json",
                data: {id: id_plano},
                beforeSend: settings => {
                    $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success: callback => {
                    $(this).attr('disabled', false).html('<i class="fa fa-edit"></i>');
                    if (typeof callback.permissoes !== 'undefinid') {
                        let permissoes = JSON.parse(callback.permissoes);
                        if (Array.isArray(permissoes)) {
                            $.each(permissoes, function(i, a) {
                                $('form#myEdit input[value="'+a+'"]').attr('checked', true);
                            });
                        }

                        $('form#myEdit input[name="id"]').val(callback.id);
                        $('form#myEdit input[name="nome"]').val(callback.nome);
                        $('form#myEdit textarea[name="observacoes"]').val(callback.observacoes);

                        $('#modalEdit').modal('show');
                    }
                },
                error: err => {
                    $(this).attr('disabled', false).html('<i class="fa fa-edit"></i>');
                    console.log('Error: ' + err);
                }
            });
        });

        /** Função cadastra novo plano */
        $('#myForm').submit(function (event) {
            event.preventDefault();
            var myForm = $(this);
            var data = myForm.serialize();
            
            $.ajax({
                url: "<?= site_url('cadastros/add_plano')  ?>",
                type: "POST",
                dataType: "json",
                data: data,
                beforeSend: settings => {
                    $('form#myEdit button.btn-submit').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
                },
                success: callback => {
                    $('form#myEdit button.btn-submit').attr('disabled', false).html('Salvar');
                    if (callback.status == true) {
                        $('form#myForm')[0].reset();
                        alert('Plano Cadastrado com Sucesso!');
                    } else {
                        alert(callback.msg);
                    }
                },
                error: error => {
                    $('form#myEdit button.btn-submit').attr('disabled', false).html('Salvar');
                    console.log(error);
                }
            });
            
            return false;
        });

        /** Função Ativa e Inativa Plano */
        $(document).on('click', '.btn_status', function () {
            let data = {
                id: $(this).attr('data-id'),
                status: $(this).attr('data-status')
            }

            $.ajax({
                url: "<?= site_url('cadastros/edit_plano')  ?>",
                type: "POST",
                dataType: "json",
                data: data,
                cache: false,
                beforeSend: setings => {
                    $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>' + $(this).html());
                },
                success: callback => {
                    $(this).attr('disabled', false);
                    if (callback.status == true) {
                        let label_status = $(this).closest('tr').find('td').eq(4).children();
                        
                        if ($(this).attr('data-status') == '1') {
                            $(label_status).removeClass('label-danger').addClass('label-success').html('Ativo');
                            $(this).attr('data-status', '0').removeClass('btn-success').addClass('btn-danger').html('Inativar');
                        } else {
                            $(label_status).removeClass('label-success').addClass('label-danger').html('Inativo');
                            $(this).attr('data-status', '1').removeClass('btn-danger').addClass('btn-success').html('Ativar');
                        }
                    } else {
                        alert(callback.msg);
                    }
                },
                error: error => {
                    $(this).attr('disabled', false);
                    console.log(error);
                }
            });
        });

        /** Função Edita Permissões de um plano */
        $('#myEdit').submit(function (event) {
            event.preventDefault();
            var myForm = $(this);
            var data = myForm.serialize();
            
            $.ajax({
                url: "<?= site_url('cadastros/edit_plano')  ?>",
                type: "POST",
                dataType: "json",
                data: data,
                beforeSend: setings => {
                    $('form#myEdit button.btn-submit').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
                },
                success: callback => {
                    $('form#myEdit button.btn-submit').attr('disabled', false).html('Salvar');
                    if (callback.status == true) {
                        alert('Plano Alterado com Sucesso!');
                    } else {
                        alert(callback.msg);
                    }
                },
                error: error => {
                    $('form#myEdit button.btn-submit').attr('disabled', false).html('Salvar');
                    console.log(error);
                }
            });
            
            return false;
        });

        var tablePerm = $('#tablePerm').DataTable( {
            ajax: {
                url: "<?= site_url('cadastros/ajax_permissoes/true') ?>",
                success: function (callback) {
                    editPerm.rows.add(callback.data).draw();
                    tablePerm.rows.add(callback.data).draw();
                }
            },
            columns: [
                {
                    searchable: false,
                    orderable: false
                },
                null,
                null
            ],
            order: [1, 'asc'],
            paging: false,
            info: false,
            "language": {
                "decimal":        "",
                "emptyTable":     "Nenhum registro encontrado",
                "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty":      "0 Registros",
                "infoFiltered":   "(filtered from _MAX_ total entries)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Exibir: _MENU_",
                "loadingRecords": "Carregando...",
                "processing":     "Processando...",
                "search":         "Pesquisar: ",
                "zeroRecords":    "Nenhum registro encontrado",
                "paginate": {
                    "first":      "Anterior",
                    "last":       "Próxima",
                    "next":       "Próxima",
                    "previous":   "Anterior"
                }
            }
        } );

        var editPerm = $('#editPerm').DataTable( {
            columns: [
                {
                    searchable: false,
                    orderable: false
                },
                null,
                null
            ],
            order: [1, 'asc'],
            paging: false,
            info: false,
            "language": {
                "decimal":        "",
                "emptyTable":     "Nenhum registro encontrado",
                "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty":      "0 Registros",
                "infoFiltered":   "(filtered from _MAX_ total entries)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Exibir: _MENU_",
                "loadingRecords": "Carregando...",
                "processing":     "Processando...",
                "search":         "Pesquisar: ",
                "zeroRecords":    "Nenhum registro encontrado",
                "paginate": {
                    "first":      "Anterior",
                    "last":       "Próxima",
                    "next":       "Próxima",
                    "previous":   "Anterior"
                }
            }
        } );

        var table = $('#example').DataTable( {
            ajax: "<?= site_url('cadastros/ajax_planos') ?>",
            order: [0, 'desc'],
            "language": {
                "decimal":        "",
                "emptyTable":     "Nenhum registro encontrado",
                "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty":      "0 Registros",
                "infoFiltered":   "(filtered from _MAX_ total entries)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Exibir: _MENU_",
                "loadingRecords": "Carregando...",
                "processing":     "Processando...",
                "search":         "Pesquisar: ",
                "zeroRecords":    "Nenhum registro encontrado",
                "paginate": {
                    "first":      "Anterior",
                    "last":       "Próxima",
                    "next":       "Próxima",
                    "previous":   "Anterior"
                }
            }
        } );
    } );
</script>