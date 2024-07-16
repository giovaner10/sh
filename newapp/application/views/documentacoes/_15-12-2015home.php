<link rel="stylesheet" type="text/css" 
      href="<?php echo base_url('media/datatable/jquery.dataTables.min.css'); ?>">
<script type="text/javascript" language="javascript"
        src="<?php echo base_url('media/datatable/dataTables.min.js'); ?>">
</script>






<script text='javascript'>
    $(document).ready(function () {
        $('#example').DataTable();

    });


</script>

<style>

    @media only screen and (max-width: 800px) {
        #unseen table td:nth-child(2), 
        #unseen table th:nth-child(2) {display: none;}
    }

    @media only screen and (max-width: 640px) {
        #unseen table td:nth-child(4),
        #unseen table th:nth-child(4),
        #unseen table td:nth-child(7),
        #unseen table th:nth-child(7),
        #unseen table td:nth-child(8),
        #unseen table th:nth-child(8){display: none;}
    }



    #example_wrapper{
        background-color: #f5f5f5;
        border-style: solid;
        border-width: 5px;
        border-color: #f5f5f5;
    }
    #example_length{
        float:right;
    }
    #example_filter{

        float: left;

    }


</style>

<h3>Documentações</h3>

<hr class="featurette-divider">

<div class="well well-small">

    <div class="btn-group">
        <a href="<?php echo site_url('documentacoes/cadastrar/') ?>"
           class="btn btn-primary" title="Adicionar documentacoes"> 
            <i
                class="icon-plus icon-white"></i>
            Cadastrar
        </a>

    </div>

</div>

<?php if (!isset($z)) {
    ?>


    <?php
    if (isset($del)) {
        echo  $del;
    }
    ?>
    <div style='overflow: auto;'>
        <table  id="example" class="table table-bordered" style='font-size: 16px;'>
            <thead>
                <tr style="font-weight: 600;">
                <th>Aquivo</th>
                <th>Numero </th>
                <th>Vencimento documentação</th>
                <th>Usuario que cadastrou</th>
                <th>Data de cadastro/envio</th>
                <th> Recebedor </th>
                <th>Download</th>
                  <th>Excluir</th>
                </tr>
            </thead>

            <tbody>



                <?php
                if ($x['query'] != null) {
                    foreach ($x['query'] as $q) {

                        $id = $q->iddocumentacoes;
                        echo "<tr>";

                        echo "<td>" . $q->nome . "</td>";

                        echo "<td>" . $id . "</td>";

                        $dt_venc = $q->data_vencimento;
                        $data_vencimento = date("d-m-Y", strtotime($dt_venc));
                        echo "<td>" . $data_vencimento . "</td>";

                        echo "<td>" . $q->usuario . "</td>";

                        $dt_in = $q->data_insercao;
                        $data_insersao = date("d-m-Y", strtotime($dt_in));
                        echo "<td>" . $data_insersao . "</td>";

                        $d_email = $q->destinatario_email;
                        $d_email2 = $q->destinatario_email2;
                        $d_email3 = $q->destinatario_email3;

                        if ($d_email2 != null) {
                            $d_email2 = "<br>" . $d_email2;
                        }
                        if ($d_email3 != null) {
                            $d_email3 = "<br>" . $d_email3;
                        }


                        echo "<td>" . $d_email . $d_email2 . $d_email3 . "</td>";

                        $file = $q->file;
                        echo "<td> 
                         <a target='_blank' class='btn btn-info btn-block'
                         href=".base_url('uploads/documentacoes')."/".$file.">Download</a></td>";
                        ?>    
                <td>            <form action="excluir" method="post">

                            <input type="hidden" name="id" value="<?php echo $id ?>" /> 
                           <input class="btn btn-danger btn-block" type="submit" value="Excluir">
                            
                        </form>
            </td>
                        <?php
                    }
                }
                ?>


            </tbody>
        </table>
    </div>



    <br style="clear:both" />
    <?php
} else
if (isset($sucesso_upload)) {
    ?>


    <div class="alert alert-success" role="alert">
        <h4>    Documentação <b><u><?php echo $nome_documento ?></u></b> inserida/enviada com sucesso. clique
            <a href="<?php echo site_url('documentacoes/./') ?>" class="alert-link">aqui</a> 
            para visualizar todas as documentações.
        </h4>
    </div>
    <br style="clear:both" />
<?php } ?>

