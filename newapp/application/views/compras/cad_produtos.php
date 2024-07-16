<div class="container-fluid" id="menu-super">
    <div class="page">
        <a href="<?php echo site_url('compras/produtos/') ?>">
            <div id="card-ativo" class="col-md-3 ativo">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-shopping-bag fa-2x"></i>
                    <h5 style="padding-top: 20px;">Produtos</h5>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/fornecedores/') ?>">
            <div id="card-topo" class="col-md-3">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-handshake-o fa-2x"></i>
                    <h5 style="font-family: monospace; color: #0068b1">Fornecedores</h5>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/cotacao/') ?>">
            <div id="card-topo" class="col-md-3">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-gavel fa-2x"></i>
                    <h5 style="font-family: monospace; color: #0068b1">Cotações</h5>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/cotacao_aprovada/') ?>">
            <div id="card-topo" class="col-md-3">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-cart-arrow-down fa-2x"></i>
                    <h5 style="font-family: monospace; color: #0068b1">Cotações Aprovadas</h5>
                </div>
            </div>
        </a>

    </div>
</div>

<div id="pagina" class="container">

    <form role="form" id="form" action="" method="post">
        <div class="row setup-content" id="step-1">
            <div class="col-md-10 col-sm-10 col-xs-6 col-md-offset-2">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="form-group col-md-5 col-sm-5 col-lg-5 col-xs-12">
                        <label class="control-label">Nome do Produto</label>
                        <input id="inputs" maxlength="100" type="text" required="required" class="form-control input-lg" placeholder="Produto"  /><br/>

                        <label class="control-label">Marca</label>
                        <input id="inputs" maxlength="100" type="text" required="required" class="form-control input-lg" placeholder="Marca do Produto"  /><br/>

                        <label class="control-label">Unidades</label>
                        <input id="inputs" maxlength="100" type="number" required="required" class="form-control input-lg" placeholder="0" /><br/>

                        <label class="control-label">Estoque Mínimo</label>
                        <input id="inputs" maxlength="15" type="number" required="required" class="telefone form-control input-lg" placeholder="0"  /><br/>

                        <label class="control-label">Fornecedor</label>
                        <input id="inputs" maxlength="100" type="text" required="required" class="form-control input-lg" placeholder="Nome do Fornecedor" /><br/>

                        <label class="control-label">Data da Cotação</label>
                        <input id="inputs" maxlength="100" type="text" required="required" class="data form-control input-lg" placeholder="__/__/__" /><br/>
                    </div>
                    <div class="form-group col-md-5 col-sm-5 col-lg-5 col-xs-12">
                        <label class="control-label">Categoria</label>
                        <select id="inputs" maxlength="100" type="text" required="required" class="form-control input-lg">
                            <option>Selecione</option>
                            <option>01</option>
                            <option>02</option>
                        </select><br/>

                        <label class="control-label">Valor</label>
                        <input id="inputs" maxlength="100" type="text" required="required" class="form-control input-lg" placeholder="Valor do Produto" /><br/>

                        <label class="control-label">Observação</label>
                        <textarea id="inputs" maxlength="140" rows="8" required="required" class="form-control" placeholder="Escreva sua observação" ></textarea><br/>

                        <button id="btn" class="btn btn-success nextBtn btn-lg pull-right" type="button" >Enviar</button>
                    </div>
                </div>
            </div>
        </div>

    </form>

</div>

<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="http://digitalbush.com/wp-content/uploads/2014/10/jquery.maskedinput.js"></script>
<script type="text/javascript">
    //MASKS
    jQuery("input.telefone")
        .mask("(99) 9999-9999?9")
        .focusout(function (event) {
            var target, phone, element;
            target = (event.currentTarget) ? event.currentTarget : event.srcElement;
            phone = target.value.replace(/\D/g, '');
            element = $(target);
            element.unmask();
            if(phone.length > 10) {
                element.mask("(99) 99999-999?9");
            } else {
                element.mask("(99) 9999-9999?9");
            }
        });
    $(document).ready(function(){
        $(".data").mask("__/__/__");});

    //FIM MASKS

</script>