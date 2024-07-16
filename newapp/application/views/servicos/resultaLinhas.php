
<div >
    <h3>Linhas Processadas do Arquivo</h3>

    <?php if ($msg_erros): ?>
        <div class="fa fa-border">
            <h4>Erros ao cadastrara no sistema</h4>
            <div class="fa fa-border">
                <?php foreach ($msg_erros as $err): ?>
                    <li>
                        <?php echo $err; ?>
                    </li>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="alert-success" >
        <?php if ($content_enviados > 0): ?>
            <h4>Enviadas para Mikrotik</h4>
            <?php foreach ($content_enviados as $env): ?>
                <span class="btn btn-primary btn-mini">
                    <?php echo $env; ?>
                </span>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="alert-error">
        <?php if ($content_nenviados > 0): ?>
            <h4>Não Enviadas para Mikrotik</h4>
            <?php foreach ($content_nenviados as $env): ?>
                <?php if ($env != '') { ?>
                    <span class="btn btn-warning btn-mini">
                        <?php echo $env; ?>
                    </span>
                <?php } ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="">
        <?php if ($content_linhas > 0): ?>
            <h4>Cadastradas no Mikrotik</h4>
            <?php foreach ($content_linhas as $env): ?>
                <span class="btn btn-info btn-mini">
                    <?php echo $env; ?>
                </span>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>


</div>