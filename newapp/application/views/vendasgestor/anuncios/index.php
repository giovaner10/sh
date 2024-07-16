<link rel="stylesheet" type="text/css" href="<?=versionFile('assets/css/vendasgestor', 'anuncios.css') ?>">

<div class="row">
    <div class="col-md-12">
        <h3><?=$titulo?></h3>
        <div>
            <button 
                type="button" 
                class="btn btn-primary" 
                data-toggle="modal" 
                data-target="#modalCadastrarEditarAnuncio"
                onclick="resetarModalCadastrarEditarAnuncio()"
                style="margin-bottom: 10px"
            >
                <?= lang('adicionar_anuncio') ?>
            </button>
            <br>
            <table id="tabelaAnuncios" class="table table-bordered table-hover display" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th><?= lang('titulo') ?></th>
                        <th><?= lang('descricao') ?></th>
                        <th><?= lang('produto') ?></th>
                        <th><?= lang('data_inicio') ?></th>
                        <th><?= lang('data_fim') ?></th>
                        <th><?= lang('data_cadastro') ?></th>
                        <th><?= lang('status') ?></th>
                        <th><?= lang('acoes') ?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var tabelaAnuncios;
    const imgPadraoUpload120x150 = '<?= base_url('media/img/120x150.png') ?>';
    let produtos = [];
    let linhaClicada = false;
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/vendasgestor/anuncios', 'index.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/vendasgestor/anuncios', 'cadastrar_editar.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/vendasgestor/anuncios', 'alterar_status.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/vendasgestor/anuncios', 'visualizar.js') ?>"></script>
