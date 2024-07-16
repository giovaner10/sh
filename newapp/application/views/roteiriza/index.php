<style>input[disabled]{ margin-top: 10px; }</style>
<form id="formUpload" method="post" enctype="multipart/form-data">
<input type="file" name="xml" id="xml" formenctype="multipart/form-data">

    <button class="btn btn-success" id="sendXML">Enviar</button>

</form>

<script>

    $('#sendXML').click(function () {
        var xml = $('#xml').val();
//        $.ajax({
        $('#formUpload').ajaxForm({
            url: 'roteirizacao/ready',
            dataType: 'json',
            contentType: 'multipart/form-data',
            type: 'POST'
        })
    });

</script>