<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<!doctype html>
<html>
<head>
</head>
<body>
<div class="col-md-6 col-offset-2">
    <div class="well well-small botoes-acao">
        <div class="btn-group">
            <a href="<?php echo site_url('cad_acessorios/index') ?>" class="btn btn-info voltar" title="Voltar"><i class="icon-arrow-left icon-white"></i></a>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">Edição de acessório</div>
        <div class="panel-body">
            <table id="table" class="mdl-data-table" cellspacing="0" width="100%" style="font-size: 12px;">
            <form action="<?php echo base_url('cad_acessorios/inserir_acessorios');?>" method="post" id="form_cadastro" class="form">
                <div class="form-group">
                    <label for="marca">Marca</label>
                    <input type="text" name="marca" value="<?php echo $acessorios->marca?>" id="marca" required class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="modelo">Modelo</label>
                    <input type="text" name="modelo" value="<?php echo $acessorios->modelo?>" id="modelo" required class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="id_categoria">ID categoria</label>
                    <input type="text" name="id_categoria" value="<?php echo $acessorios->id_categoria?>" id="id_categoria" required class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="id_fornecedor">ID fornecedor</label>
                    <input type="text" name="id_fornecedor" value="<?php echo $acessorios->id_fornecedor?>" id="id_fornecedor" required class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="id_NF_entrada">Número da nota fiscal</label>
                    <input type="text" name="id_NF_entrada" value="<?php echo $acessorios->id_NF_entrada?>" id="id_NF_entrada" required class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <input type="text" name="descricao" value="<?php echo $acessorios->descricao?>" id="descricao" required class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="estoque_minimo">Estoque Mínimo</label>
                    <input type="text" name="estoque_minimo" value="<?php echo $acessorios->estoque_minimo?>" id="estoque_minimo" required class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="estoque_maximo">Estoque Máximo</label>
                    <input type="text" name="estoque_maximo" value="<?php echo $acessorios->estoque_maximo?>" id="estoque_maximo" required class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="estoque_atual">Estoque Atual</label>
                    <input type="text" name="estoque_atual" value="<?php echo $acessorios->estoque_atual?>" id="estoque_atual" required class="form-control"/>
                </div>

                <div class="modal-footer">
                    <input type="hidden" name="id_acessorio" value="<?php echo $acessorios->id_acessorio?>">
                    <button type="submit" class="salvar btn btn-primary" title="Salva os dados preenchidos"><i class="icon-download-alt icon-white"></i> Atualizar</button>
                    <button type="reset" class="limpar btn btn-primary" data-form="#clientes" onClick="document.getElementById('clientes').reset();return false" title="Restaura as informações iniciais"><i class="icon-leaf icon-white"></i> Limpar</button>
                </div>
            </form>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="jquery.validate.min.js"></script>
<script>
    $(function(){
        $("#form_cadastro").validate();
    });
</script>
</body>
</html>

