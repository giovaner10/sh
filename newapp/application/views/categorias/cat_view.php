<body>
    <form action="<?php echo base_url('categorias/cadastrarsub')?>" method="post">
        <div class="col-sm-6">
            <h4 label>Cadastre uma subcategoria para a categoria <strong>-> <?php echo $categorias->nome?> </strong></label><br><br></h4>
            <h5 label>Nome da subcategoria</label></h5>
            <input type="hidden" value="<?php echo $categorias->id?>" name="id_categoria">
            <input type="text" class="form-control" name="nome_sub" placeholder="" required>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="id_categoria" value="<?php echo $categorias->id?>">
            <button type="submit" class="salvar btn btn-primary" title="Salva os dados preenchidos"><i class="icon-download-alt icon-white"></i> Salvar</button>
            <button type="button" class="limpar btn btn-primary" data-form="#clientes" onClick="document.getElementById('clientes').reset();return false" title="Restaura as informações iniciais"><i class="icon-leaf icon-white"></i> Limpar</button>
            <button type="button" class="fechar btn btn-primary" data-dismiss="modal" title="Encerra o cadastro"></i>Fechar</button>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="table" class="mdl-data-table" cellspacing="0" style="align: center" width="100%" style="font-size: 12px;">
                    <thead style="background-color: #03A9F4">
                        <tr id="">

                            <th>ID da subcategoria</th>
                            <th>Subcategoria</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($subcategorias)){
                            foreach ($subcategorias as $for) {

                                echo '<tr>';
                                echo '<td>'. $for->id_sub.'</td>';
                                echo '<td>'. $for->nome_sub.'</td>';
                                echo '<td>';
                            }

                        }?>
                    </tbody>
                </table>
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
</form>