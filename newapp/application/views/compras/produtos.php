<div class="container" id="menu-super">
    <div class="page">
        <a href="<?php echo site_url('compras/produtos/') ?>">
            <div id="card-ativo" class="col-md-2 col-sm-6 ativo">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-shopping-bag fa-2x"></i>
                    <h5 style="font-family: sans-serif;">Produtos</h5>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/fornecedores/') ?>">
            <div id="card-topo" class="col-md-2 col-sm-6">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-handshake-o fa-2x"></i>
                    <h5 style="color: #0068b1">Fornecedores</h5>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/cotacao/') ?>">
            <div id="card-topo" class="col-md-2 col-sm-6">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-gavel fa-2x"></i>
                    <h5 style="color: #0068b1">Cotações</h5>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/cotacao_aprovada/') ?>">
            <div id="card-topo" class="col-md-2 col-sm-6">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-cart-arrow-down fa-2x"></i>
                    <h5 style="color: #0068b1;font-family: sans-serif">Cotações Aprovadas</h5>
                </div>
            </div>
        </a>

    </div>
</div>
<div class="titulo">
    <h2>Produtos Cadastrados</h2><hr/>
</div>
<div id="box-table col-md-12">
    <table id="tabela" class="col-md-6 table table-responsive table-lg table-hover">
        <thead>
        <tr style="background:#1878b8; color: white; font-size: 12px;">
            <th class="borda borda-down nome-left">Nome</th>
            <th class="col-md-2 borda borda-down">Visualizar</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="borda borda-down nome-left">0000</td>
            <td class="borda borda-down"><button class="btn btn-info"><i class="fa fa-search"></i></button></td>
        </tr>
        <tr>
            <td class="borda borda-down nome-left">0000</td>
            <td class="borda borda-down"><button class="btn btn-info"><i class="fa fa-search"></i></button></td>
        </tr>

        </tbody>
    </table>
</div>
<button data-toggle="modal" data-target="#modal-empresa" type="button" class="btn-produto btn-primary">Novo Produto</button>
<div class="modal fade" id="modal-empresa" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5>Selecione a Empresa</h5>
            </div>
            <div class="modal-body">
                <form>
                    <div class="checkbox checkbox-success">
                        <input id="check5" value="pneushow" class="styled" type="checkbox" checked>
                        <label for="check5">
                            <strong>Pneu Show</strong>
                        </label>
                    </div>
                    <div class="checkbox checkbox-success">
                        <input id="check5"value="showtec" class="styled" type="checkbox">
                        <label for="check5">
                            <strong>Show Tecnologia</strong>
                        </label>
                    </div>
                    <div class="checkbox checkbox-success">
                        <input id="check5" value="sim2m" class="styled" type="checkbox">
                        <label for="check5">
                            <strong>SiM2M</strong>
                        </label>
                    </div>

            </div>
            <div class="modal-footer">
                <a type="link" data-dismiss="modal" aria-label="Close">Cancelar</a>
                <span> <button formaction="<?php echo site_url('compras/cad_produtos/') ?>" class="btn btn-info">Criar</button></span>
            </div>
            </form>
        </div>
    </div>
</div>

