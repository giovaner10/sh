<!DOCTYPE html>
<html>
<head>
    <link href="<?php echo base_url('newapp/assets/css/bootstrap.css')?>" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="row">
                <div class="col-md-6">
                    <?php echo $this->session->flashdata('sucesso');?>
                    <?php echo $this->session->flashdata('editado');?>
                    <?php echo $this->session->flashdata('dados');?>
                </div>
            </div>
        <h3>Cadastro de acessórios<small></small></h3>
        <div class="well well-small botoes-acao">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" data-toggle="modal"  data-target="#exampleModal"><i class="icon-plus icon-white"></i> Adicionar acessório</button>
            </div>
        </div>
        </div><br><br>
        <div class="col-md-12 mb-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="table" class="mdl-data-table" cellspacing="0" width="100%" zoom="90%" style="font-size: 12px;">
                                <thead style="background-color: #03A9F4">
                                <tr id="th">

                                    <th>ID</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>ID Categoria</th>
                                    <th>ID Fornecedor</th>
                                    <th>N. Fiscal - Entrada</th>
                                    <th>Descrição</th>
                                    <th>Estoque Mín</th>
                                    <th>Estoque Máx</th>
                                    <th>Estoque Atual</th>
                                    <th>Editar</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($acessorios)) {
                                    foreach ($acessorios as $for ) {

                                        echo '<tr>';
                                        echo '<td>'. $for->id_acessorio.'</td>';
                                        echo '<td>'. $for->marca.'</td>';
                                        echo '<td>'. $for->modelo.'</td>';
                                        echo '<td>'. $for->id_categoria .'</td>';
                                        echo '<td>'. $for->id_fornecedor.'</td>';
                                        echo '<td>'. $for->id_NF_entrada.'</td>';
                                        echo '<td>'. $for->descricao.'</td>';
                                        echo '<td>'. $for->estoque_minimo.'</td>';
                                        echo '<td>'. $for->estoque_maximo.'</td>';
                                        echo '<td>'. $for->estoque_atual.'</td>';
                                        echo '<td>';
                                        echo '<div class="btn-group">
						   
										    <a class="btn btn-success" href="./../cad_acessorios/editar_acessorio/'.$for->id_acessorio.'" role="button"><i class="icon-pencil icon-white"></i></a>

										</button>
										</div>
										
										</td>  
										</tr>';
                                    }
                                } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- MODAL PARA CADASTRAR ACESSÓRIOS -->

                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">INFORMAÇÕES PARA O CADASTRO DO ACESSÓRIO</h5>
                                </div>
                                <div class="col-md-12">
                                    <form action="<?php echo base_url('cad_acessorios/inserir_acessorios');?>" method="post">
                                        <div class="modal-body">
                                            <div class="col-sm-6">
                                                <label>Marca</label>
                                                <input type="text" class="form-control" name="marca" placeholder="" value="" required>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Modelo</label>
                                                <input type="text" class="form-control" name="modelo" placeholder="" value="" required>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>ID Categoria</label>
                                                <input type="text" class="form-control" name="id_categoria" placeholder="" value="" required>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>ID Fornecedor</label>
                                                <input type="text" class="form-control" name="id_fornecedor" placeholder="" value="" required>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Nº Nota Fiscal/Entrada</label>
                                                <input type="text" class="form-control" name="id_NF_entrada" placeholder="" value="" required>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Descrição</label>
                                                <input type="text" class="form-control" name="descricao" placeholder="" value="" required>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Estoque Mínimo</label>
                                                <input type="text" class="form-control" name="estoque_minimo" placeholder="" value="" required>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Estoque Máximo</label>
                                                <input type="text" class="form-control" name="estoque_maximo" placeholder="" value="" required>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Estoque Atual</label>
                                                <input type="text" class="form-control" name="estoque_atual" placeholder="" value="" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="status" value="1">
                                            <button type="submit" class="salvar btn btn-primary" title="Salva os dados preenchidos"><i class="icon-download-alt icon-white"></i> Salvar</button>
                                            <button type="button" class="limpar btn btn-primary" data-form="#clientes" onClick="document.getElementById('clientes').reset();return false" title="Restaura as informações iniciais"><i class="icon-leaf icon-white"></i> Limpar</button>
                                            <button type="button" class="fechar btn btn-primary" data-dismiss="modal" title="Encerra o cadastro"></i>Fechar</button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.material.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css">
<link rel="stylesheet" href="//https://cdn.datatables.net/1.10.16/css/dataTables.material.min.css">


<script type="text/javascript">
    $(document).ready( function () {
        $('#table').DataTable( {
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

    $(document).ready(function(){

        $(".resultado").hide();
        $("#clientes").ajaxForm({
            target: '.resultado',
            dataType: 'json',
            success: function(retorno){
                $(".resultado").html(retorno.mensagem);
                $(".resultado").show();
            }

        });

    });
</script>
</body>
</html>