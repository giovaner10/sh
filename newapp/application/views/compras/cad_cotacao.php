<div class="container-fluid" id="menu-super">
    <div class="page">
        <a href="<?php echo site_url('compras/produtos/') ?>">
            <div id="card-topo" class="col-md-3">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-shopping-bag fa-2x"></i>
                    <h5 style="color: #0068b1">Produtos</h5>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/fornecedores/') ?>">
            <div id="card-topo" class="col-md-3">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-handshake-o fa-2x"></i>
                    <h5 style="font-family:sans-serif; color: #0068b1">Fornecedores</h5>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/cotacao/') ?>">
            <div id="card-ativo" class="col-md-3 ativo">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-gavel fa-2x"></i>
                    <h5 style="font-family: sans-serif;">Cotações</h5>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/cotacao_aprovada/') ?>">
            <div id="card-topo" class="col-md-3">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-cart-arrow-down fa-2x"></i>
                    <h5 style="font-family: sans-serif; color: #0068b1">Cotações Aprovadas</h5>
                </div>
            </div>
        </a>

    </div>
</div>

<div id="pagina" class="container">

    <form role="form" name="form" id="form" action="" method="post">
        <div class="row setup-content" id="step-1">
            <div class="col-md-10 col-sm-10 col-xs-6 col-md-offset-2">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div id="duplicar">
                    <div class="form-group col-md-5 col-sm-5 col-lg-5 col-xs-12">
                        <label class="control-label">Nome do Produto</label>
                        <input id="inputs" maxlength="100" type="text" name="produto[]" required="required" class="form-control input-lg" placeholder="Produto"  /><br/>
                    </div>
                    <div class="form-group col-md-5 col-sm-5 col-lg-5 col-xs-12">
                        <label class="control-label">Nome do Produto</label>
                        <input id="inputs" maxlength="100" type="text" name="qtd[]" required="required" class="form-control input-lg" placeholder="Produto"  /><br/>
                    </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-sm-2" style="line-height: 7">
                        <button onclick="duplicarCampos()" class="btn btn-danger bnt-lg"><i class="fa fa-plus"></i></button>
                    </div>
                    <div id="new_inputs"></div>

                    <div class="col-md-10 col-lg-10">
                        <button id="btn" class="btn btn-success nextBtn btn-lg pull-right" type="button" >Enviar</button>
                    </div>
                </div>
            </div>
        </div>

    </form>

</div>
<script>
    function duplicarCampos(){
        var clone = document.getElementById('duplicar').cloneNode(true);
        var destino = document.getElementById('new_inputs');
        destino.appendChild (clone);
        var camposClonados = clone.getElementsByTagName('input');
        for(i=0; i<camposClonados.length;i++){
            camposClonados[i].value = '';
        }
    }
//    function removerCampos(id){
//        var node1 = document.getElementById('duplicar');
//        node1.removeChild(node1.childNodes[0]);
//    }
</script>
