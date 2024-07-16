<div class="span4 tabela" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">
    <table class="table table-hover table-bordered">
    <?php if ($representantes): ?>
        <tbody class="inner">
            <?php foreach ($representantes as $representante): ?>
                <tr>
                    <th>Nome</th>
                    <td><?php echo $representante->nome.' '.$representante->sobrenome;?></td>
                </tr>
                <tr>
                    <th>CPF/SSC</th>
                    <td><?php echo $representante->cpf ?></td>
                </tr>
                <tr>
                    <th>País</th>
                    <td>
                        <?php 
                        if ($representante->pais == 'BRA') {
                            $pais = 'BRASIL';    
                        }elseif ($representante->pais == 'USA') {
                            $pais = 'ESTADOS UNIDOS'; 
                        } else {
                            $pais = $representante->pais;   
                        }
                        echo $pais; 
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Endereço</th>
                    <td><?php echo $representante->endereco.', nº '.$representante->numero.', '.$representante->bairro.', '.$representante->cidade.' - '.$representante->estado;?></td>
                </tr>
                <tr>
                    <th>CEP</th>
                    <td><?php echo $representante->cep ?></td>
                </tr>
                <tr>
                    <th>Banco</th>
                    <td><?php echo $representante->banco ?></td>
                </tr>
                <tr>
                    <th>Agência</th>
                    <td><?php echo $representante->agencia ?></td>
                </tr>
                <tr>
                    <th>Conta</th>
                    <td><?php echo $representante->conta ?></td>
                </tr>
                <tr>
                    <th>Telefone</th>
                    <td><?php echo $representante->telefone ?></td>
                </tr>
                 <tr>
                    <th>Celular</th>
                    <td><?php echo $representante->celular ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $representante->email ?></td>
                </tr>
                <tr>
                    <th>E. Show</th>
                    <td><?php echo $representante->emailshow ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    <?php else: ?>
        <tbody class="inner"> 
        </tbody>
    <?php endif ?> 
    </table>
</div>