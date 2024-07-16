<div class="resultado span4" style="float: none; margin-left: auto; margin-right: auto;"></div>
<div class="resultado2 span4" style="float: none; margin-left: auto; margin-right: auto;"></div>

<div class="pull-right">
    Arquivo: 
    <span class="label label-warning ">.pdf</span>
</div>

<div class="formulario">

    <form method="post" name="formcontato" id="ContactForm2" enctype="multipart/form-data" action="<?php echo site_url('contas/digitalizacao_comp/'.$id)?>">
        <div class="span4" style="float: none; margin-left: auto; margin-right: auto;">
            <div class="form-group">
                <label for="sel1">Tipo:</label>
                <select class="form-control" id="tipo" name="tipo">
                    <option value="end">ENDEREÇO</option>
                    <option value="account">CONTA BANCÁRIA</option>
                    <option value="cpf">CPF</option>
                    <option value="rg">RG</option>
                </select>
            </div>
            <!--<input type="radio" value="comprovante de pagamento" id="descricao" name="descricao" placeholder="Comprovante de Pagamento" class="input" /> Comprovante de Pagamento-->
            <div class="span4" style="float: none; margin-left: auto; margin-right: auto;" >
                <input type="file" name="file" class="filestyle" data-buttonText="Arquivo">
            </div> 
        </div>
        <div class="modal-footer" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">
            <a href="#contas"  class="btn btn-small" data-dismiss="modal" data-url="<?php echo site_url('contas/tab_listar_contas/')?>" 
            data-nome="contas"  data-toggle="tab" id="atualizar-contas">Cancelar</a>
            <input type="submit" id="enviar" class="btn btn-primary btn-small" value="Enviar"/>
        </div>
    </form>
</div>

<div class="span4 tabela" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">
    <table class="table table-hover table-bordered">
        <thead>
            <th>Tipo</th>
            <th>Visualizar</th>
        </thead>
    <?php if ($arquivos): ?>
        <tbody class="inner">
            <!-- COMPROVANTE DE ENDEREÇO -->
            <tr>
                <td>ENDEREÇO</td>
                <?php if (isset($arquivos['end']) && $arquivos['end']): ?>
                    <td>
                        <a id="button_end" href="<?php echo base_url('uploads/instaladores/'.$id.'_end.pdf')?>" target="_blank" class="btn btn-success btn-mini">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
                    </td>
                    <td><i id="check_end" class="fa fa-check" style="color:green"></i></td>
                <?php else: ?>
                    <td>
                        <a id="button_end" class="btn btn-success btn-mini" disabled="disabled">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
                    </td>
                    <td><i id="check_end" class="fa fa-close" style="color:red"></i></td>
                <?php endif; ?>
            </tr>

            <!-- COMPROVANTE DE CPF -->
            <tr>
                <td>CPF</td>
                <?php if (isset($arquivos['cpf']) && $arquivos['cpf']): ?>
                    <td>
                        <a id="button_cpf" href="<?php echo base_url('uploads/instaladores/'.$id.'_cpf.pdf')?>" target="_blank" class="btn btn-success btn-mini">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
                    </td>
                    <td><i id="check_cpf" class="fa fa-check" style="color:green"></i></td>
                <?php else: ?>
                    <td>
                        <a id="button_cpf" class="btn btn-success btn-mini" disabled="disabled">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
                    </td>
                    <td><i id="check_cpf" class="fa fa-close" style="color:red"></i></td>
                <?php endif; ?>
            </tr>

            <!-- COMPROVANTE DE CONTA BANCARIA -->
            <tr>
                <td>CONTA BANCARIA</td>
                <?php if (isset($arquivos['account']) && $arquivos['account']): ?>
                    <td>
                        <a id="button_account" href="<?php echo base_url('uploads/instaladores/'.$id.'_account.pdf')?>" target="_blank" class="btn btn-success btn-mini">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
                    </td>
                    <td><i id="check_account" class="fa fa-check" style="color:green"></i></td>
                <?php else: ?>
                    <td>
                        <a id="button_account" class="btn btn-success btn-mini" disabled="disabled">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
                    </td>
                    <td><i id="check_account" class="fa fa-close" style="color:red"></i></td>
                <?php endif; ?>
            </tr>

            <!-- COMPROVANTE DE RG -->
            <tr>
                <td>CPF</td>
                <?php if (isset($arquivos['rg']) && $arquivos['rg']): ?>
                    <td>
                        <a id="button_rg" href="<?php echo base_url('uploads/instaladores/'.$id.'_rg.pdf')?>" target="_blank" class="btn btn-success btn-mini">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
                    </td>
                    <td><i id="check_rg" class="fa fa-check" style="color:green"></i></td>
                <?php else: ?>
                    <td>
                        <a id="button_rg" class="btn btn-success btn-mini" disabled="disabled">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
                    </td>
                    <td><i id="check_rg" class="fa fa-close" style="color:red"></i></td>
                <?php endif; ?>
            </tr>
        </tbody>
    <?php else: ?>
        <tbody class="inner"> 
        </tbody>
    <?php endif ?> 
    </table>
</div>

<script type="text/javascript">

$(":file").filestyle({buttonText: "Arquivo"});

$(document).ready(function(){

    $(".resultado").hide();
    $(".resultado2").hide();
    $("#ContactForm2").ajaxForm({
        target: '.resultado',
        dataType: 'json',
        beforeSend:function(){
            var carregar = [
                '<div class="progress progress-striped active">',
                  '<div class="bar" style="width: 100%;"><b>Carregando...</b></div>',
                '</div>'
            ].join('');
            $('.resultado2').html(carregar);
            $(".resultado2").show();
            $(".resultado").hide();
            $("#enviar").hide();
        },
        success: function(retorno){
            if (retorno.status == 'OK') {
                $('#button_'+retorno.ext).removeAttr('disabled');
                $('#button_'+retorno.ext).attr('href', retorno.href);
                $('#button_'+retorno.ext).attr('target', '_blank');
                $('#check_'+retorno.ext).attr('class', 'fa fa-check');
                $('#check_'+retorno.ext).attr('style', 'color: green');
            } else {
                alert(retorno.msg);
            }

            $(".resultado").html(retorno.mensagem);
            $(".resultado").show();
            $(".resultado2").hide();
            $("#enviar").show();
        }
        

    });

});




</script>




