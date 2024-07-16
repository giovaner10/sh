<script	src="<?php echo base_url('assets') ?>/plugins/form-jquery/jquery.form.min.js"></script>

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
            
            <div class="span4" style="float: none; margin-left: auto; margin-right: auto;" >
                <input type="file" name="file">
            </div> 
        </div>
        <div class="modal-footer" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">
            <a href="#contas"  class="btn" data-dismiss="modal" data-url="<?php echo site_url('contas/tab_listar_contas/')?>" 
            data-nome="contas"  data-toggle="tab" id="atualizar-contas" style="background-color: red;color:white">Cancelar</a>
            <input type="submit" id="enviar" class="btn btn-primary" value="Enviar"/>
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
                        <a id="button_end" href="<?php echo base_url('uploads/instaladores/'.$id.'_end.pdf')?>" target="_blank" class="btn btn-primary">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
                    </td>
                    <td><i id="check_end" class="fa fa-check" style="color:green"></i></td>
                <?php else: ?>
                    <td>
                        <a id="button_end" class="btn btn-primary" disabled="disabled">
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
                        <a id="button_cpf" href="<?php echo base_url('uploads/instaladores/'.$id.'_cpf.pdf')?>" target="_blank" class="btn btn-primary">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
                    </td>
                    <td><i id="check_cpf" class="fa fa-check" style="color:green"></i></td>
                <?php else: ?>
                    <td>
                        <a id="button_cpf" class="btn btn-primary" disabled="disabled">
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
                        <a id="button_account" href="<?php echo base_url('uploads/instaladores/'.$id.'_account.pdf')?>" target="_blank" class="btn btn-primary">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
                    </td>
                    <td><i id="check_account" class="fa fa-check" style="color:green"></i></td>
                <?php else: ?>
                    <td>
                        <a id="button_account" class="btn btn-primary" disabled="disabled">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
                    </td>
                    <td><i id="check_account" class="fa fa-close" style="color:red"></i></td>
                <?php endif; ?>
            </tr>

            <!-- COMPROVANTE DE RG -->
            <tr>
                <td>RG</td>
                <?php if (isset($arquivos['rg']) && $arquivos['rg']): ?>
                    <td>
                        <a id="button_rg" href="<?php echo base_url('uploads/instaladores/'.$id.'_rg.pdf')?>" target="_blank" class="btn btn-primary">
                        <i class="icon-eye-open icon-white"></i>
                        Visualizar
                        </a>
                    </td>
                    <td><i id="check_rg" class="fa fa-check" style="color:green"></i></td>
                <?php else: ?>
                    <td>
                        <a id="button_rg" class="btn btn-primary" disabled="disabled">
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

$(document).ready(function(){
    $("#ContactForm2").ajaxForm({
        target: '.resultado',
        dataType: 'json',
        beforeSend:function(){
            $('#enviar').attr('disabled', true).val('Enviando...')
        },
        success: function(retorno){
            if (retorno.status == 'OK') {
                console.log(retorno )
                $('#button_'+retorno.ext).removeAttr('disabled');
                $('#button_'+retorno.ext).attr('href', retorno.href);
                $('#button_'+retorno.ext).attr('target', '_blank');
                $('#check_'+retorno.ext).attr('class', 'fa fa-check');
                $('#check_'+retorno.ext).attr('style', 'color: green');
                alert("Arquivo enviado com sucesso!");
                $('#enviar').attr('disabled', false).val('Enviar');
            } else {
                alert(retorno.msg);
                $('#enviar').attr('disabled', false).val('Enviar');
            }

        }
        

    });

});




</script>




