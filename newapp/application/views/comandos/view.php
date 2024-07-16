<style>
    .img {
        width: 45px;
    }

    .div_1 {
        position: relative;
        float: left;
        top: 0px;
        left: 0px;
    }

    .div_2 {
        position: absolute;
        top: 6px;
        left: 15px;
    }

    #overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 9999;
    }

    #loading-indicator {
        margin-top: 20%;
        margin-left: 45%;
        border: 16px solid #f3f3f3;
        border-top: 16px solid #3498db;
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }
</style>
<?php if ($msg != '') : ?>
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $msg; ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('sucesso')) : ?>
    <div class="alert alert-success">
        <p><?php echo $this->session->flashdata('sucesso') ?></p>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('erro')) : ?>
    <div class="alert alert-error">
        <p><?php echo $this->session->flashdata('erro') ?></p>
    </div>
<?php endif; ?>

<h3>Comandos Enviados</h3>

<hr>

<!-- Modal resposta -->
<div id="myModalResposta" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Resposta do Comando</h4>
            </div>
            <div class="modal-body">
                <div class="row align-items-center">
                    <div id="valor-resposta" class="col-md-12">

                    </div>
                </div>
            </div>
            <div class="modal-footer" id="valor-copiar">

            </div>
        </div>
    </div>
</div>

<?php if (isset($veiculos) && is_array($veiculos) && count($veiculos) > 0) : ?>
    <table class="table table-hover tabela-veiculos">
        <tr>
            <th>Code</th>
            <th>Usuário</th>
            <th>Veículo</th>
            <th>Placa</th>
            <th>Serial</th>
            <th>Equipamento</th>
            <th>Comando</th>
        </tr>
        <tr>
            <td><?php echo isset($veiculos[0]->code) ? $veiculos[0]->code : '' ?></td>
            <td><?php echo isset($email) ? $email : '' ?></td>
            <td><?php echo isset($veiculos[0]->veiculo) ? $veiculos[0]->veiculo : '' ?></td>
            <td><?php echo isset($veiculos[0]->placa) ? $veiculos[0]->placa : '' ?></td>
            <td><?php echo isset($veiculos[0]->serial) ? $veiculos[0]->serial : (isset($serial) ? $serial : '') ?></td>
            <td><?php echo isset($equipamento) ? $equipamento : '' ?></td>
            <td>
                <?php if (isset($lista_comandos) && is_array($lista_comandos) && count($lista_comandos) > 0) : ?>
                    <form action="envio_comando" method="post" class="form-horizontal row" id="form-comando">
                        <input type="text" hidden name='serial' value='<?php echo isset($veiculos[0]->serial) ? $veiculos[0]->serial : (isset($serial) ? $serial : '') ?>'>
                        <input type="text" hidden name='code' value='<?php echo isset($veiculos[0]->code) ? $veiculos[0]->code : '' ?>'>
                        <select name="comando" class="col-md-9" style="width: inherit;">
                            <?php foreach ($lista_comandos as $cmd) : ?>
                                <option value="<?php echo $cmd->code ?>"><?php echo $cmd->nome ?></option>
                            <?php endforeach ?>

                        </select>
                        <button type="button" class="btn btn-default col-md-2 btn-add-comando" style="margin-left:10px; height: 2rem; padding: 0px; width: 4rem;display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-arrow-right" aria-hidden="true" style="font-size: 15px;height: 100%;margin-top: -2px"></i>
                        </button>
                    </form>
                <?php endif ?>
            </td>
        </tr>
    </table>
<?php else : ?>
    <script>
        setTimeout(function() {
            alert('Veículo não possui comandos enviados.');
        }, 1000);
        window.location.href = "<?php echo site_url('cadastros/veiculos'); ?>";
    </script>
<?php endif ?>

<?php if (isset($comandos) && is_array($comandos) && count($comandos) > 0) : ?>

    <table class="table table-hover">

        <tr>
            <th>Comando</th>
            <th>Enviado</th>
            <th>Confirmado</th>
            <th>Tentativas</th>
            <th>Enviado por</th>
            <th>Status
                <button type="button" class="btn atualizar">Atualiza</button>
            </th>
            <th>Resposta</th>

        </tr>

        <?php foreach ($comandos as $comando) : ?>

            <tr>
                <td title="<?= $comando->nome ?>"><?php echo substr($comando->nome, '0', '11') ?></td>
                <td><?php echo dh_for_humans($comando->datahora_criacao) ?></td>
                <td><?php echo dh_for_humans($comando->datahora_confirmacao) ?></td>
                <td><?php echo $comando->tentativa ?></td>
                <td><?php echo $comando->nome_usuario ?></td>
                <td>

                    <?php
                    $seta1 = 'cinza';
                    $seta2 = 'cinza';
                    $seta3 = 'cinza';

                    $tit_seta1 = 'Gravado';
                    $tit_seta2 = 'Aguardando';
                    $tit_seta3 = 'Confirmação';

                    if ($comando->status >= 0) {
                        $seta1 = 'verde';
                        $tit_seta1 = 'Novo comando (Executar envio)';
                    }

                    if ($comando->status == 1) {
                        $seta1 = 'verde';
                        $tit_seta2 = 'Em processamento (Em execução pelo servidor)';
                    }

                    if ($comando->status == 2) {
                        $seta2 = 'vermelha';
                        $tit_seta2 = 'Comando rejeitado (Operação mal sucedida)';
                    }

                    if ($comando->status == 3) {
                        $seta2 = 'cinza';
                        $tit_seta2 = 'Operação de múltiplos comandos em andamento';
                    }

                    if ($comando->status == 4) {
                        $seta2 = 'verde';
                        $tit_seta2 = 'Em processamento (Em execução pelo servidor)';

                        $seta3 = 'verde';
                        $tit_seta3 = 'Comando aceito (Operação bem sucedida)';
                    }
                    ?>
                    <div class="div_1">
                        <img src="<?php echo base_url("media/img/seta_$seta1.png") ?>" class="img" border=0 title="<?php echo $tit_seta1; ?>">
                        <div class="div_2">
                        </div>
                    </div>
                    <div class="div_1">
                        <img src="<?php echo base_url("media/img/seta_$seta2.png") ?>" class="img" border=0 title="<?php echo $tit_seta2; ?>">
                        <div class="div_2">
                        </div>
                    </div>
                    <div class="div_1">
                        <img src="<?php echo base_url("media/img/seta_$seta3.png") ?>" class="img" border=0 title="<?php echo $tit_seta3; ?>">
                        <div class="div_2">
                        </div>
                    </div>
                </td>
                <td>
                    <?php if (isset($comando->resposta) && ($comando->resposta != "")) : ?>
                        <button id="" class="btn btn-primary visualizarResposta" data-resposta="<?php echo $comando->resposta ?>"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    <?php else : ?>
                        <button class="btn btn-default visualizarSemResposta"><i class="fa fa-eye-slash" aria-hidden="true" style="color: #9FA6B2;"></i></button>
                    <?php endif ?>
                </td>
            </tr>

        <?php endforeach ?>

    </table>
<?php else : ?>

    <script>
        alert('Veículo não possui comandos enviados.');
    </script>

<?php endif ?>
<script>
    $(document).ready(function() {
        $('.btn-add-comando').click(function(e) {
            e.preventDefault()
            let form = $('#form-comando').serialize();
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('comandos/envio_comando') ?>",
                data: form,
                dataType: "json",
                beforeSend: function() {},
                success: function(response) {
                    alert(response)
                    window.location.reload();
                },
                error: function(err) {
                    alert('Ocorreu um erro ao enviar a solicitação')
                }
            });
        });
        $('.atualizar').click(function(e) {
            e.preventDefault()
            window.location.reload();
        });
    });

    $('.visualizarResposta').click(function(e) {
        e.preventDefault()
        var resposta = $(this).data('resposta');

        $('#myModalResposta').modal('show');
        $('#valor-resposta').html('<span class="truncate-text" style="word-wrap: break-word; max-width: 200px;">' + resposta + '</span>');
        $('#valor-copiar').html(`<button type="submit" class="btn btn-primary visualizarResposta" id="btn-copiar" data-resposta-valor="${resposta}" data-toggle="tooltip" data-placement="top" title="Aperte para copiar">Copiar</button>`);

        $('#btn-copiar').click(function(ev) {
            ev.preventDefault()
            const valorParaCopiar = $(this).data('resposta-valor');
            copiarParaAreaDeTransferencia(valorParaCopiar, $(this));
        });
        $('[data-toggle="tooltip"]').tooltip({
            trigger: 'hover'
        });
    })

    $('.visualizarSemResposta').click(function(e) {
        e.preventDefault()
        alert('Esse comando não possui resposta!');
    })
    

    function copiarParaAreaDeTransferencia(valor, botao) {
        navigator.clipboard.writeText(valor)
            .then(function() {
                botao.tooltip('hide');
                botao.attr('data-original-title', 'Copiado!'); // Atualiza a tooltip para "Copiado!"
                botao.tooltip('show');
                setTimeout(function() {
                    botao.tooltip('hide');
                    botao.attr('data-original-title', 'Aperte para copiar');
                }, 1000);
            })
            .catch(function(err) {
                console.log('Não foi possível copiar Post');
            });
    }
</script>