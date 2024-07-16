<link href="<?=base_url('media/css/contas.css')?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/jquery.dataTables.css')?>">
<script type="text/javascript" charset="utf8" src="<?=base_url('assets/js/jquery.dataTables.js')?>"></script>
<style>
    table {
        width: 100% !important;
    }
    .blem{
        color: red;
    }

    div.btn-group {
        margin: 10px;
    }
</style>
<div class="containner">
    <!-- ALERTAS -->
    <?php if (!empty($this->session->flashdata('sucesso'))) { ?>
        <div class="alert alert-success">
            <strong>Successo!</strong> <?= $this->session->flashdata('sucesso'); ?>
        </div>
    <?php } elseif (!empty($this->session->flashdata('erro'))) { ?>
        <div class="alert alert-danger">
            <strong>Erro!</strong> <?= $this->session->flashdata('erro'); ?>
        </div>
    <?php } ?>
    
    <div class="clientes-alert" style="display:none">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <span id="mensagem_clientes"></span>
    </div>
    <!-- ### -->
    <br>
    <div>
    	<div class="pull-right">
    		<button type="button" class="btn btn-info" data-toggle="modal" data-target="#info" title="Informações"><i class="fa fa-info"></i></button>
    	</div>
    </div>
    <ul class="nav nav-tabs">
        <?php if( $this->auth->get_login_dados('funcao') != 'OMNILINK'): ?>
        	<li class="active"><a data-toggle="tab" href="#show" class="show">SHOW TECNOLOGIA</a></li>
        	<li class="sim"><a data-toggle="tab" href="#sim" >SIMM2M</a></li>
        	<li class="norio"><a data-toggle="tab" href="#norio" >SIGA-ME</a></li>
            <li class="technology"><a data-toggle="tab" href="#technology" >SHOW TECHNOLOGY EUA</a> </li>
            <li class="omnilinkTab"><a data-toggle="tab" href="#omnilinktab" >OMNILINK</a></li>
            <li class="sigamy"><a data-toggle="tab" href="#sigamy" >SIGAMY APP</a></li>
        <?php else: ?>
            <li class="active"><a data-toggle="tab" href="#omnilinktab">OMNILINK</a></li>
        <?php endif; ?>
    </ul>
    
    <div class="tab-content">
        <?php if($this->auth->get_login_dados('funcao') != 'OMNILINK'): ?>
    	<div id="show" class="tab-pane fade in active">
            <div class="btn-group">
                <a href="<?php echo site_url('clientes/registro') ?>" class="btn btn-primary" title="Adicionar"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar</a>
            </div>
    		<div class="table-responsive">
    			<table id="tracker" class="table table-hover responsive display">
    				<thead class="thead-inverse">
    					<th class="text-center"> ID </th>
    					<th class="text-center"> Cliente </th>
    					<th class="text-center"> Situacao </th>
    					<th class="text-center"> Cadastrado </th>
    					<th class="text-center"> Origem </th>
    					<th class="text-center"> Status </th>
    					<th class="text-center"> Administrar </th>
    				</thead>
    				<tbody id="listOrdem">
    					<tr>
                            <td colspan="8" align="center">
                                <center>
                                    <i style="color: #499BEA" id="spin" class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                </center>
                            </td>
                        </tr>
    				</tbody>
    			</table>
    		</div>
    	</div>
    
    	<div id="sim" class="tab-pane fade">
            <div class="btn-group">
                <a href="<?php echo site_url('clientes/registro') ?>" class="btn btn-primary" title="Adicionar"><i class="icon-plus icon-white"></i> Adicionar</a>
            </div>
            <div class="table-responsive">
    			<table id="simm2m" class="table table-hover responsive display">
    				<thead class="thead-inverse">
    					<th class="text-center"> ID </th>
    					<th class="text-center"> Cliente </th>
    					<th class="text-center"> Situacao </th>
    					<th class="text-center"> Cadastrado </th>
    					<th class="text-center"> Origem </th>
    					<th class="text-center"> Status </th>
    					<th class="text-center"> Administrar </th>
    				</thead>
    				<tbody id="listOrdem">
    					<tr>
                            <td colspan="8" align="center">
                                <center>
                                    <i style="color: #499BEA" id="spin" class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                </center>
                            </td>
                        </tr>
    				</tbody>
    			</table>
    		</div>
    	</div>
    
    	<div id="norio" class="tab-pane fade">
            <div class="btn-group">
                <a href="<?php echo site_url('clientes/registro') ?>" class="btn btn-primary" title="Adicionar"><i class="icon-plus icon-white"></i> Adicionar</a>
            </div>
            <div class="table-responsive">
    			<table id="momoi" class="table table-hover responsive display">
    				<thead class="thead-inverse">
    					<th class="text-center"> ID </th>
    					<th class="text-center"> Cliente </th>
    					<th class="text-center"> Situacao </th>
    					<th class="text-center"> Cadastrado </th>
    					<th class="text-center"> Origem </th>
    					<th class="text-center"> Status </th>
    					<th class="text-center"> Administrar </th>
    				</thead>
    				<tbody id="listOrdem">
    					<tr>
                            <td colspan="8" align="center">
                                <center>
                                    <i style="color: #499BEA" id="spin" class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                </center>
                            </td>
                        </tr>
    				</tbody>
    			</table>
    		</div>
    	</div>
    
        <div id="technology" class="tab-pane fade">
            <div class="btn-group">
                <a href="<?php echo site_url('clientes/registro_eua') ?>" class="btn btn-primary" title="Adicionar"><i class="icon-plus icon-white"></i> Adicionar</a>
            </div>
            <div class="table-responsive">
                <table id="eua" class="table table-hover responsive display">
                    <thead class="thead-inverse" style="background: #499BEA !important;">
                    <th class="text-center"> ID </th>
                    <th class="text-center"> Client </th>
                    <th class="text-center"> Situation </th>
                    <th class="text-center"> Registered </th>
                    <th class="text-center"> Source </th>
                    <th class="text-center"> Status </th>
                    <th class="text-center"> Manage </th>
                    </thead>
                    <tbody id="listOrdem">
    					<tr>
                            <td colspan="8" align="center">
                                <center>
                                    <i style="color: #499BEA" id="spin" class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                </center>
                            </td>
                        </tr>
    				</tbody>
                </table>
            </div>
        </div>
        <!-- OMNILINK -->
        <div id="omnilinktab" class="tab-pane fade">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#clientesT">Clientes</a></li>
                <li class="embarcadores" ><a data-toggle="tab" href="#embarcadores">Parceiros</a></li>
            </ul>
    
            <div class="tab-content">
                <div id="clientesT" class="tab-pane fade in active">
                    <div class="btn-group">
                        <a href="<?php echo site_url('clientes/registro') ?>" class="btn btn-primary" title="Adicionar"><i class="icon-plus icon-white"></i> Adicionar</a>
                    </div>
                    <div class="table-responsive">
                        <table id="omnilink" class="table table-hover responsive display">
                            <thead class="thead-inverse" style="background: #499BEA !important;">
                            <th class="text-center"> ID </th>
                                <th class="text-center"> Cliente </th>
                                <th class="text-center"> Situacao </th>
                                <th class="text-center"> Cadastrado </th>
                                <th class="text-center"> Origem </th>
                                <th class="text-center"> Status </th>
                                <th class="text-center"> Administrar </th>
                            </thead>
                           <tbody id="listOrdem">
            					<tr>
                                    <td colspan="8" align="center">
                                        <center>
                                            <i style="color: #499BEA" id="spin" class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                        </center>
                                    </td>
                                </tr>
            				</tbody>
                        </table>
                    </div>
                </div>
                <div id="embarcadores" class="tab-pane fade">
                    <div class="btn-group">
                        <a href="<?php echo site_url('clientes/registro_embarcadores') ?>" class="btn btn-primary" title="Adicionar"><i class="icon-plus icon-white"></i> Adicionar</a>
                    </div>
                    <div class="table-responsive">
                        <table id="embarcadoresTable" class="table table-hover responsive display">
                            <thead class="thead-inverse" style="background: #499BEA !important;">
                            <th class="text-center"> ID </th>
                                <th class="text-center"> Nome </th>
                                <th class="text-center"> Cadastrado </th>
                                <th class="text-center"> Status </th>
                                <th class="text-center"> Administrar </th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- SIGAMY -->
        <div id="sigamy" class="tab-pane fade">
            <div class="btn-group">
                <a href="<?php echo site_url('clientes/registro') ?>" class="btn btn-primary" title="Adicionar"><i class="icon-plus icon-white"></i> Adicionar</a>
            </div>
            <div class="table-responsive">
                <table id="sigamyList" class="table table-hover responsive display">
                    <thead class="thead-inverse" style="background: #499BEA !important;">
                    <th class="text-center"> ID </th>
                        <th class="text-center"> Cliente </th>
                        <th class="text-center"> Situacao </th>
                        <th class="text-center"> Cadastrado </th>
                        <th class="text-center"> Origem </th>
                        <th class="text-center"> Status </th>
                        <th class="text-center"> Administrar </th>
                    </thead>
                    <tbody id="listOrdem"></tbody>
                </table>
            </div>
        </div>
    
        <?php else: ?>
    
            <!-- OMNILINK -->
            <div id="omnilinktab" class="tab-pane fade in active">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#clientesT">Clientes</a></li>
                    <li class="embarcadores" ><a data-toggle="tab" href="#embarcadores">Embarcadores</a></li>
                </ul>
    
                <div class="tab-content">
                    <div id="clientesT" class="tab-pane fade in active">
                        <div class="btn-group">
                            <a href="<?php echo site_url('clientes/registro') ?>" class="btn btn-primary" title="Adicionar"><i class="icon-plus icon-white"></i> Adicionar</a>
                        </div>
    
                        <div class="table-responsive">
                            <table id="omnilink" class="table table-hover responsive display">
                                <thead class="thead-inverse" style="background: #499BEA !important;">
                                <th class="text-center"> ID </th>
                                    <th class="text-center"> Cliente </th>
                                    <th class="text-center"> Situacao </th>
                                    <th class="text-center"> Cadastrado </th>
                                    <th class="text-center"> Origem </th>
                                    <th class="text-center"> Status </th>
                                    <th class="text-center"> Administrar </th>
                                </thead>
                                <tbody id="listOrdem"></tbody>
                            </table>
                        </div>
                    </div>
    
                    <div id="embarcadores" class="tab-pane fade">
                        <div class="btn-group">
                            <a href="<?php echo site_url('clientes/registro_embarcadores') ?>" class="btn btn-primary" title="Adicionar"><i class="icon-plus icon-white"></i> Adicionar</a>
                        </div>
                        <div class="table-responsive">
                            <table id="embarcadoresTable" class="table table-hover responsive display">
                                <thead class="thead-inverse" style="background: #499BEA !important;">
                                <th class="text-center"> ID </th>
                                    <th class="text-center"> Nome </th>
                                    <th class="text-center"> Cadastrado </th>
                                    <th class="text-center"> Status </th>
                                    <th class="text-center"> Administrar </th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- MODAL DE INFORMAÇÕES -->
	<div id="info" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><b>Informações</b></h4>
				</div>
				<div class="modal-body">
					<h5><b>Prospecção</b></h5>
					<p>A prospecção do cliente se dá quando o cliente foi cadastrado
						mas não há nenhum contrato vinculado ao mesmo.
					<p>
					<h5><b>Ativação</b></h5>
					<p>A ativação é feita de forma automática pelo sistema. Quando o
						cliente se encontra com um ou mais contratos ativo(s) o sistema
						ativa o status do cliente.</p>
					<h5><b>Bloqueio</b></h5>
					<p>
						O bloqueio do cliente é acionado quando todos os contratos são
						cancelados.<br /> Obs.: Quando acionado o bloqueio automático do
						cliente, todos os seus usuários são bloqueados.
					</p>
				</div>
			</div>
		</div>
	</div>
    <!-- Modal negativar/positivar cliente -->
    <div id="modal_negativar_positivar" style="width: 60%; left:40%!important;" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="titulo_neg_posit">Negativar/Positivar</h3>
        </div>
        <div class="modal-body">
                <div class="formulario">
                <form method="post" name="formcontato" id="ContactForm2" enctype="multipart/form-data" action="<?php echo site_url('clientes/negativarPositivar')?>">
                    <div class="col-md-8" style="text-align: center;">
                        <textarea id="descricao" name="descricao" placeholder="Descrição" class="input" style="width:60%" required></textarea>
                        <div class="form-group col-md-12">
                            <input type="hidden" name="id_cliente" id="input_id_cliente">
                            <input type="hidden" name="acao" id="input_acao">
                        </div>
                        <span class="label label-warning ">.pdf</span>
                        <input type="file" id="arquivo_cliente" name="arquivo_cliente" required>
                        <button type="submit" class="btn btn-primary negativar_positivar" disabled>Processar</button>
                    </div>
                </form>    
                <div>
                    <table class="table table-striped table-bordered" id="digi_neg_posit">
                        <thead>
                        <th>#</th>
                        <th>Usuário</th>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Ação</th>
                        <th>Visualizar</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#digi_neg_posit').DataTable();  //instancia a tabela de arquivos digitalizados ao negativar
        var $url = window.location.href;
        var funcao = "<?php echo $this->auth->get_login_dados('funcao');?>";
        if (funcao != 'OMNILINK') {

            // LISTA OS CLIENTES SHOW
            if ($.fn.DataTable.isDataTable('#tracker')) {
                return ;
            }
            $('#tracker').DataTable({
                responsive: true,
                serverSide: true,
                ajax: $url+'/ajax_listClient?company=TRACKER',
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

            $('.sim').on('click', function () {
                // LISTA OS CLIENTES SIMM2M
                if ($.fn.DataTable.isDataTable('#simm2m')) {
                    return ;
                }
                $('#simm2m').DataTable( {
                    responsive: true,
                    serverSide: true,
                    ajax: $url+'/ajax_listClient?company=SIMM2M',
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

            });

            $('.norio').on('click', function () {
                // LISTA OS CLIENTES NORIO
                if ($.fn.DataTable.isDataTable('#momoi')) {
                    return ;
                }
                $('#momoi').DataTable( {
                    responsive: true,
                    serverSide: true,
                    ajax: $url+'/ajax_listClient?company=NORIO',
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

            });

            $('.technology').on('click', function () {
                // LISTA OS CLIENTES SHOWTECHNOLOGY
                if ($.fn.DataTable.isDataTable('#eua')) {
                    return ;
                }
                $('#eua').DataTable( {
                    responsive: true,
                    serverSide: true,
                    ajax: $url+'/ajax_listClient?company=EUA',
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

            });

            $('.omnilinkTab').on('click', function () {
                // LISTA OS CLIENTES OMNILINK
                if ($.fn.DataTable.isDataTable('#omnilink')) {
                    return ;
                }
                $('#omnilink').DataTable( {
                    responsive: true,
                    serverSide: true,
                    ajax: $url+'/ajax_listClient?company=OMNILINK',
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

            });

            $('.embarcadores').on('click', function(){
                // LISTA OS CLIENTES OMNILINK
                if ($.fn.DataTable.isDataTable('#embarcadoresTable')) {
                    return ;
                }
                // LISTA OS EMBARCADORES
                $('#embarcadoresTable').DataTable( {
                    responsive: true,
                    serverSide: true,
                    ajax: $url+'/ajax_listEmbarcadores',
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
            });


            $('.sigamy').on('click', function () {
                // LISTA OS CLIENTES SIGAMY
                if ($.fn.DataTable.isDataTable('#sigamyList')) {
                    return ;
                }
                $('#sigamyList').DataTable( {
                    responsive: true,
                    serverSide: true,
                    ajax: $url+'/ajax_listClient?company=SIGAMY',
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

            });

        }else {
            $('#omnilink').DataTable( {
                responsive: true,
                serverSide: true,
                ajax: $url+'/ajax_listClient?company=OMNILINK',
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

            $('.embarcadores').on('click', function () {
                if ($.fn.DataTable.isDataTable('#embarcadoresTable')) {
                    return ;
                }
                // LISTA OS EMBARCADORES
                $('#embarcadoresTable').DataTable( {
                    responsive: true,
                    serverSide: true,
                    ajax: $url+'/ajax_listEmbarcadores',
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
            });
        }

        //confere o tipo de arquivo
        $('#arquivo_cliente').change(function () {
           //Reference the FileUpload element.
           var fileUpload = document.getElementById("arquivo_cliente");

           //Validate whether File is valid pdf file.
           var regex = /^(.)+(.pdf)$/;
           if (regex.test(fileUpload.value.toLowerCase())) {
               $('.negativar_positivar').prop('disabled', false);
           }else {
               alert("Por Favor, Use um Arquivo PDF Válido.");
               //limpa os dados do input file
               $("#arquivo_cliente").val(null);
               $('input[type="text"]').val('');
               $('.negativar_positivar').attr('disabled', true);
           }
       });

        //carrega dados modal
		$(document).on('click', '.negPosit', function() {
            //limpas os valores dos campos do formulario
            $('textarea[name=descricao]').val(null);
            $("#arquivo_cliente").val(null);
            $('input[type="text"]').val('');
            $('.negativar_positivar').attr('disabled', true);

            var botao = $(this);
            if (botao.attr('data-acao') == 0) {
                $("#titulo_neg_posit").text('Negativar Cliente #'+botao.attr('data-id_cliente'));
            }else {
                $("#titulo_neg_posit").text('Positivar Cliente #'+botao.attr('data-id_cliente'));
            }

            $("#input_id_cliente")[0].value = botao.attr('data-id_cliente');
            $("#input_acao")[0].value = botao.attr('data-acao');
            table.destroy();

            //carrega a tabela de arquivos digitalizados para clientes negativados/positivados
            table = $('#digi_neg_posit').DataTable({
                ajax:{
                    url: "<?= site_url('clientes/ajax_digi_neg_posit')?>",
                    type: 'POST',
                    data: {id: botao.attr('data-id_cliente')}
                },
                order: [0, 'desc'],
                processing: true,
                pagingType: 'numbers',
                language: {
                    loadingRecords: "&nbsp;",
                    processing: "Carregando os arquivos...",
                    emptyTable: "Nenhum registro encontrado",
                    zeroRecords: "Nenhum registro encontrado",
                    search: "Pesquisar"
                },
                dom: 'Bfrtip',
                responsive: true,
                info: false,
                columns: [
                    {data: 'id'},
                    {data: 'id_usuario'},
                    {data: 'data_cadastro'},
                    {data: 'descricao'},
                    {
                        data: 'acao',
                        render: function (data) {
                            if (data == 0) {
                                return 'Negativação';
                            }else {
                                return 'Positivação';
                            }
                        }
                    },
                    {
                        data: 'link',
                        render: function (data) {
                            <?php if($this->auth->is_allowed_block('clientes_arquivo')): ?>
                                return '<a href="'+data+'" target="_blank" class="btn btn-success btn-mini"><i class="icon-eye-open icon-white"></i>Visualizar</a>';
                            <?php endif; ?>
                            return '<a href="'+data+'" target="_blank" class="btn btn-success btn-mini" disabled><i class="icon-eye-open icon-white"></i>Visualizar</a>';
                        }
                    }
                ],

            });

            $('#modal_negativar_positivar').modal();
        });

    });

    $(document).ready(function(){
        var botao = false;

        $("#ContactForm2").ajaxForm({
            dataType: 'json',
            beforeSend:function(){
            $('.clientes-alert').css('display', 'block');
            botao = $('.negativar_positivar');
            botao.attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> Processando...');
            },
            success: function(retorno){
                if (retorno.success) {
                    if (retorno.acao == 0) {
                        $("#mensagem_clientes").html('<div class="alert alert-success"><p><b>Cliente Negativado Com Sucesso!</b></p></div>');
                        $('.status_'+retorno.id_cliente).html('<span class="label label-important">Negativo</span>'); //muda o status
                        $(".negPosit_"+retorno.id_cliente).text('Positivar').attr('data-acao', '1');  //atualiza o botao

                    }else {
                        $("#mensagem_clientes").html('<div class="alert alert-success"><p><b>Cliente Positivado Com Sucesso!</b></p></div>');
                        $('.status_'+retorno.id_cliente).html('<span class="label label-success">Ativo</span>');
                        $(".negPosit_"+retorno.id_cliente).text('Negativar').attr('data-acao', '0');
                    }

                    //disabilita o botao de enviar o pedido
                    $('.negativar_positivar').prop('disabled', true);
                }else {
                    $("#mensagem_clientes").html('<div class="alert alert-danger"><p><b>'+retorno.msg+'</p></div>');
                }
                botao.text('Processar');
                $("#modal_negativar_positivar").modal('hide');
            }
        });
    });
</script>
<!-- <script type="text/javascript" src="<?= base_url('assets/js/modules/client.js'); ?>"></script> -->