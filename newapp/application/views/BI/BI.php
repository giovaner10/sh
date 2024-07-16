<style>
    @media (max-width: 640px) {
        .iframe {
            height: 300px;
        }
    }
</style>

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?= lang($menu_nome) ?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none" href="<?= site_url('Homes') ?>">Home</a> >
        <?= lang('BI') ?> >
        <?php if ($menu_nome != $menu_pai) : ?>
            <?= lang($menu_pai); ?> >
        <?php endif; ?>
        <?= lang($menu_nome) ?>
    </h4>
</div>

<div class="row" style="margin: 15px 0 0 15px;">
    <div class="col-md-12" id="conteudo">
        <div class="card-conteudo card-dados-gerenciamento" style='margin-bottom: 20px; position: relative;'>
            <h3>
                <b>
                    <?= lang($menu_nome) ?>:
                </b>
            </h3>
            <iframe class="iframe" width="100%" height="600px" src="<?= $link ?>" frameborder="0" allowFullScreen="true" sandbox="allow-same-origin allow-scripts"></iframe>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        window.history.pushState({
            path: 'BI'
        }, '', 'BI');
        var redirectLink = <?= $link ?>;
        window.location.href = <?= site_url('BI/BI') ?> + '?link=' + redirectLink;
    });
</script>

<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/OCR', 'layout.css') ?>">