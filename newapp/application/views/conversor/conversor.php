<h3><?php echo $titulo;?></h3>

<form id="form_converter" name="form_converter" action="<?php echo base_url("conversor/converter"); ?>" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="empresa">Selecione a empresa</label>
        <select id="empresa" name="empresa">
            <option value="1" selected>Show</option>
            <option value="2">Norio</option>
        </select>
    </div>
    <div class="form-group">
        <input id="csv" name="csv" type="file" onchange="validarCSV(this);" accept=".csv" required>
    </div>
    <button id="submit" name="submit" class="btn btn-info">Converter</button>
</form>

<script type="text/javascript" language="javascript">

    function validarCSV(sender) {
        var validExts = new Array(".csv");
        var fileExt = sender.value;
        fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
        if (validExts.indexOf(fileExt) < 0) {
            alert("Arquivo no formato inválido! Formatos aceitos: " +
                validExts.toString());
            sender.value = '';
            return false;
        }
        else return true;
    }
</script>

