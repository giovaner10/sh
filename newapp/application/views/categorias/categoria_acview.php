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
        <h2 style="text-align: center">Cadastro de categorias e subcategorias<small></small></h2>
        <div class="well well-small botoes-acao">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" data-toggle="modal"  data-target="#exampleModal"><i class="icon-plus icon-white"></i> ADICIONAR </button>
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
                                <tr id="">

                                    <th>ID categoria</th>
                                    <th>Categoria</th>
                                    <th>Adicionar subcategoria</th>
                                    <th>Editar</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($categorias)){
                                    foreach ($categorias as $for) {
                                        echo '<tr>';
                                        echo '<td>'. $for->id.'</td>';
                                        echo '<td>'. $for->nome.'</td>';
                                        echo '<td>';
                                        echo '<div class="btn-group">
						   
                                            <a class="btn btn-primary" href="./../categorias/cad_sub/'.$for->id.'" role="button"><i class="icon-plus icon-white"></i></a>

                                        </button>
                                        </div>                                         
                                        </td>                                      
                                       <td>                                        
                                        <div class="btn-group">					   
                                            <a style = "alignment: " class="btn btn-success" href="./../categorias/inserir_subcategoria/'.$for->id.'" role="button"><i class="icon-pencil icon-white"></i></a>
                                        </button>
                                        </div>                                     
                                        </td>                                      
                                        </tr>';
                                    }

                                }?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- MODAL PARA CADASTRAR CATEGORIA E SUB CATEGORIA -->

                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">CADASTRANDO A CATEGORIA</h5>
                                </div>
                                <div class="col-md-12">
                                    <form action="<?php echo base_url('categorias/inserir_categorias');?>" method="post">
                                        <div class="modal-body">
                                            <div class="col-sm-6">
                                                <label>Categoria do produto</label>
                                                <input type="text" class="form-control" name="nome" placeholder="" value="" required>
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