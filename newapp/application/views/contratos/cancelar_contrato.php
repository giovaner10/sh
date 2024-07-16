<div class="resultado span4" style="float: none; margin-left: auto; margin-right: auto;">

</div>

<div class="">
  <form id="formCancelar">
      <div class="span4" style="float: none; margin-left: auto; margin-right: auto; text-align: center; ">
          <?php if(!$cancelado): ?>
              <?php if($equipamentos): ?>
                  <div class="alert alert-info">Caso não consiga cancelar o contrato, reative e inative a(s) placa(s) informada(s) e tente novamente!</div>
                  <h4><span class="label label-warning">Ainda há equipamento em uso!</span></h4>
                  <div class="span4 tabela" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">
                      <table class="table table-hover table-bordered">
                        <thead>
                            <?php if($tipo_proposta == 1): ?>
                                <th>Sim Card</th>
                                <th>CCID</th>
                                <th>Descrição</th>
                            <?php elseif($tipo_proposta == 4): ?>
                                <th>Serial</th>
                                <th>Equipamento</th>
                                <th>Descrição</th>
                            <?php elseif($tipo_proposta == 6): ?>
                                <th>Serial</th>
                                <th>Modelo</th>
                                <th>Marca</th>
                                <th>Descrição</th>
                            <?php else: ?>
                                <th>Placa</th>
                                <th>Descrição</th>
                            <?php endif; ?>
                        </thead>
                        <tbody class="inner">

                              <?php foreach ($equipamentos as $equipamento): ?>
                                  <tr>

                                      <?php if($tipo_proposta == 1): ?>
                                          <td><?php echo $equipamento->numero ?></td>
                                          <td><?php echo $equipamento->ccid ?></td>
                                          <td><span class="label label-danger">Ainda em uso!</span></td>
                                      <?php elseif($tipo_proposta == 4): ?>
                                          <td><?php echo $equipamento->serial ?></td>
                                          <td><?php echo $equipamento->equipamento ?></td>
                                          <td><span class="label label-danger">Ainda em uso!</span></td>
                                      <?php elseif($tipo_proposta == 6): ?>
                                          <td><?php echo $equipamento->serial ?></td>
                                          <td><?php echo $equipamento->modelo ?></td>
                                           <td><?php echo $equipamento->marca ?></td>
                                          <td><span class="label label-danger">Ainda em uso!</span></td>
                                      <?php else: ?>
                                          <td><?php echo $equipamento->placa ?></td>
                                          <td><span class="label label-danger">Ainda em uso!</span></td>
                                      <?php endif; ?>
                                  </tr>
                              <?php endforeach ?>

                            </tbody>

                        </table>
                        <?php if ($status_contrato == 2 || $status_contrato == 1): ?>
                            <a id="cpr-contrato" value="7" class="btn btn-warning">Iniciar Processo de Retirada</a>
                        <?php endif; ?>               
                        <div class="btn-group">
                            <button  class="btn btn-default" data-dismiss="modal" id="fechar">Fechar</button>
                        </div>

                    </div>

                <?php else: ?>
                
                    <button type="submit" id="cancelar-contrato" value="3" class="btn btn-danger" title="Cancelar contrato">
                            Cancelar Contrato</button>
                    <button  class="btn btn-default" data-dismiss="modal" id="fechar">Fechar</button>

                <?php endif; ?>

            <?php else: ?>

                <div>

                    <h4><span class="label label-warning">Contrato ja foi cancelado ou encerrado!</span></h4>

                </div>

                <div class="btn-group">
                    <button  class="btn btn-default" data-dismiss="modal" id="fechar">Fechar</button>
                </div>

            <?php endif; ?>

        </div>

    </form>

</div>

<script type="text/javascript">
        var id_contrato = '<?= $id_contrato ?>';
        var id_cancelamento = 3;
        var status_contrato = '<?= $status_contrato ?>';

        $("#cpr-contrato").click(function(){
            id_cancelamento = 7;
            $("#formCancelar").submit();
        })

       $("#formCancelar").submit(function(){
        if(id_cancelamento == 3){
           $('#cancelar-contrato').html('<i class="fa fa-spin fa-spinner"></i> Cancelando...').attr('disabled', 'disabled');
        } else {
           $('#cpr-contrato').html('<i class="fa fa-spin fa-spinner"></i> Iniciando Processo de Retirada...').attr('disabled', 'disabled');
        }
           $.ajax({
               url: "<?php echo site_url('contratos/cancelar_contrato/')?>",
               type: 'post',
               dataType: 'json',
               data: {id_contrato: id_contrato,
                      cancelar: id_cancelamento,
                      status: status_contrato},
               success: function(retorno){
                   $('.con'+id_contrato).removeClass('ativar_contrato')
                       .removeAttr('data-status')
                       .removeAttr('data-id_contrato')
                       .removeAttr('data-toggle')
                       .removeAttr('data-target')
                       .removeAttr('href')
                       .attr('disabled','true')
                       .attr("title","Contrato Cancelado")
                       .removeClass('.con'+id_contrato)
                       .html("<img src='"+ img_contrato + "' alt='Contrato Cancelado' class='desativado' style='height: 30px'/>");

                    if(id_cancelamento == 7){
                        alert('Processo de retirada iniciado com sucesso!');
                    } else {
                        alert('Contrato cancelado com sucesso!');
                    }

                   $('#cancelar-contrato').html('Cancelar contrato').removeAttr('disabled');
                   $('#cpr-contrato').html('Iniciar Processo de Retirada').removeAttr('disabled');
                   $("#ContactForm2").resetForm();
                   $("#myModal_cancelar_contrato").modal('hide');
                   loadTableContratos();
               },
               error: function (retorno) {
                   alert("Houve um erro ao cancelar o contrato!");
                   $('#cancelar-contrato').html('Cancelar contrato').removeAttr('disabled');
                   $('#cancelar-contrato').html('Iniciar Processo de Retirada').removeAttr('disabled');
                   $("#ContactForm2").resetForm();
                   $("#myModal_cancelar_contrato").modal('hide');
               }

           });
           return false;

   });

</script>
