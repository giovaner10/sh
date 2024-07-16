<link rel="stylesheet" type="text/css" href="<?=versionFile('assets/css/omniscore', 'omniscore.css') ?>">

<div class="row">
    <div class="col-md-12">
        <h3><?=lang('consultas_perfis')?></h3>
        <div class="col-md-12">            
            <div class="well well-small col-md-12">
                <form style="align-items:center" id="formConsultasPerfis">
                    <div class="col-md-3" style="margin-bottom: 5px;">
                        <i class="fa fa-calendar" style="font-size: 20px;"></i>
                        <input style="width:85%" type="date" id="data_inicial" name="data_inicial" class="data formatInput" min="2021-01-01" max="<?=date('Y-m-d')?>" placeholder="<?=lang('data_inicial')?>" autocomplete="off" id="dp1" value="<?=date('Y-m-d')?>" required />
                        <label class="required"></label>
                    </div>
                    <div class="col-md-3" style="margin-bottom: 5px;">
                        <i class="fa fa-calendar" style="font-size: 20px;"></i>
                        <input style="width:85%" type="date" id="data_final" name="data_final" class="data formatInput" min="2021-01-01" max="<?=date('Y-m-d')?>" placeholder="<?=lang('data_final')?>" autocomplete="off" id="dp2" value="<?=date('Y-m-d')?>" required />
                        <label class="required"></label>
                    </div>
                    <div class="col-md-3" style="margin-bottom: 5px;">
                        <i class="fa fa-user" style="font-size: 25px;"></i>
                        <select name="id_cliente" id="id_cliente" class="formatInput" required>
                            <option value="" selected><?=lang('selecione_cliente')?></option>
                        </select>
                        <label class="required"></label>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btnFormConsultasPerfis" > <?=lang('gerar') ?> </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-12">
            <h4 id="totais"></h4>
            <table id="tabelaConsultas" class="table table-bordered table-hover responsive" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th><?= lang('cpf_funcionario') ?></th>
                        <th><?= lang('CNH') ?></th>
                        <th><?= lang('cpf_cnpj_proprietario') ?></th>
                        <th><?= lang('placa_veiculo') ?></th>
                        <th><?= lang('placa_carreta') ?></th>
                        <th><?= lang('placa_segunda_carreta') ?></th>
                        <th><?= lang('usuario') ?></th>
                        <th><?= lang('acao') ?></th>
                        <th><?= lang('data_cadastro') ?></th>
                        <th><?= lang('admin') ?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Carrega html -->
<?php include_once('application/views/omniscore/laudo_consulta.php'); ?>
<?php include_once('application/views/omniscore/laudo_vitimismo.php'); ?>
<?php include_once('application/views/omniscore/laudo_resumido.php'); ?>
<?php include_once('application/views/omniscore/modal_visualizar_consulta.php'); ?>

<script type="text/javascript">
    var tabelaConsultas = false;
    let mandados = [];
    let processos = [];
    let debitos = [];
    var site_url = "<?= site_url() ?>";
    var base_url = "<?= base_url() ?>";
    var id_log = false;
    var logoOmniscore = '';
    var qtd_comprovantes = 0;
</script>

<script type="text/javascript" src="<?= versionFile('assets/js/omniscore', 'omniscore.js') ?>"></script>
<script type="text/javascript"src="<?= versionFile('media/js', 'html2pdf.bundle.min.js') ?>"></script>
<script type="text/javascript"src="<?= versionFile('assets/js', 'pdf.js') ?>"></script>
<script type="text/javascript"src="<?= versionFile('assets/js', 'pdf.worker.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/omniscore', 'laudo_resumido.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/omniscore', 'laudo_consulta.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js/omniscore', 'laudo_vitimismo.js') ?>"></script>
