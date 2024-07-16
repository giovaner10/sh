<?php 
//verifica se existe permissão para visualizar este dashboard
if($permissao && $permissao !== ""){ ?> 

    <?php if($this->auth->is_allowed_block($permissao)){ ?>

        <iframe width="100%" height="500px" src="<?= $linkBi ?>" frameborder="0" allowFullScreen="true"></iframe>
    <?php }else{ ?>

            <h3 style="display: flex;justify-content: center;padding-top: 5%;">Você não tem permissão para visualizar este Dashboard</h3>
    <?php } ?>

<?php }else{ ?>
    
    <iframe width="100%" height="500px" src="<?= $linkBi ?>" frameborder="0" allowFullScreen="true"></iframe>
<?php } ?>