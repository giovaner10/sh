<div class="container" id="menu-super">
    <div class="page">
        <a href="<?php echo site_url('compras/produtos/') ?>">
            <div id="card-topo" class="col-md-2 col-sm-6">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-shopping-bag fa-2x"></i>
                    <h5 style="font-family: sans-serif; color: #0068b1">Produtos</h5>
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
            <div id="card-ativo" class="col-md-2 col-sm-6 ativo">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-cart-arrow-down fa-2x"></i>
                    <h5 style="font-family: sans-serif">Cotações Aprovadas</h5>
                </div>
            </div>
        </a>

    </div>
</div>
<div class="titulo">
    <h2>Cotações Aprovadas</h2><hr/>
</div>
<div>
    <table id="tabela" class="table table-responsive table-lg table-hover">

        <thead>
        <tr style="background:#1878b8; color: white; font-size: 12px;">
            <th class="borda borda-down">Nº Código</th>
            <th class="borda borda-down">Usuário</th>
            <th class="borda borda-down">Data</th>
            <th class="borda borda-down">Validade</th>
            <th class="borda borda-down">Loja</th>
            <th class="borda borda-down">Nome</th>
            <th class="borda borda-down">Valor</th>
            <th class="borda-down">Visualizar</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="borda borda-down">0000</td>
            <td class="borda borda-down">John Lennon</td>
            <td class="borda borda-down">DD/MM/AAAA</td>
            <td class="borda borda-down">12 Dias</td>
            <td class="borda borda-down">Show Tecnologia</td>
            <td class="borda-down borda">Cotação Desenvolvimento</td>
            <td class="borda-down borda">20.000,00</td>
            <td class="borda-down" style="color: #faac0d"><a href="" style="text-decoration: none" data-toggle="modal" data-target="#myModal"><button class="btn btn-buscar"><i class="fa fa-file-text"></i></button></td></a>
        </tr>
        <tr>
            <td class="borda borda-down">0000</td>
            <td class="borda borda-down">John Lennon</td>
            <td class="borda borda-down">DD/MM/AAAA</td>
            <td class="borda borda-down">12 Dias</td>
            <td class="borda borda-down">Show Tecnologia</td>
            <td class="borda-down borda">Cotação Desenvolvimento</td>
            <td class="borda-down borda">20.000,00</td>
            <td class="borda-down" style="color: #faac0d"><a href="" style="text-decoration: none" data-toggle="modal" data-target="#myModal"><button class="btn btn-buscar"><i class="fa fa-file-text"></i></button></td></a>
        </tr>
        <tr>
            <td class="borda borda-down">0000</td>
            <td class="borda borda-down">John Lennon</td>
            <td class="borda borda-down">DD/MM/AAAA</td>
            <td class="borda borda-down">12 Dias</td>
            <td class="borda borda-down">Show Tecnologia</td>
            <td class="borda-down borda">Cotação Desenvolvimento</td>
            <td class="borda-down borda">20.000,00</td>
            <td class="borda-down" style="color: #faac0d"><a href="" style="text-decoration: none" data-toggle="modal" data-target="#myModal"><button class="btn btn-buscar"><i class="fa fa-file-text"></i></button></td></a>
        </tr>
        <tr>
            <td class="borda borda-down">0000</td>
            <td class="borda borda-down">John Lennon</td>
            <td class="borda borda-down">DD/MM/AAAA</td>
            <td class="borda borda-down">12 Dias</td>
            <td class="borda borda-down">Show Tecnologia</td>
            <td class="borda-down borda">Cotação Desenvolvimento</td>
            <td class="borda-down borda">20.000,00</td>
            <td class="borda-down" style="color: #faac0d"><a href="" style="text-decoration: none" data-toggle="modal" data-target="#myModal"><button class="btn btn-buscar"><i class="fa fa-file-text"></i></button></td></a>
        </tr>


        </tbody>
    </table>
</div>
