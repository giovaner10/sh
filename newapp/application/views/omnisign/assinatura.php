<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Documento</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        body { padding-top: 20px; background-color: #f5f5f5; }
        .container { margin: auto; background-color: #fff; padding: 20px; border-radius: 5px; }
        .header-info { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        iframe { width: 100%; height: 500px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-info">
            <div>
                <h2><?php echo $file->nome; ?></h2>
                <p>
                    Data de Cadastramento: <?= dh_for_humans($file->data_cadastro) ?></br>
                    Assinante: <?= $signature->nome ?></br>
                    Sistema de Assinatura: OmniSign - Omnilink Tecnologia S.A
                </p>
            </div>
            <!-- Botão Assinar -->
            <?php if ($signature->status == '0'): ?>
            <button type="button" class="btn btn-lg btn-success btn-assinar" onclick="assinarDocumento()">Assinar</button>
            <?php endif; ?>
        </div>
        <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=<?= base_url($file->arquivo) ?>" style="width:100%; height:500px;" frameborder="0"></iframe>
    </div>
</body>
</html>

<script>
    function assinarDocumento() {
        var cpf = prompt("Por favor, insira seu CPF:");
        if (cpf && validarCPF(cpf)) {
            var idFile = "<?= $file->id ?>";
            var idSign = "<?= $signature->id ?>";

            // Desabilita botão
            $('button.btn-assinar').attr('disabled', true);

            $.post("<?= site_url('omnisignsSignature/receptSignature') ?>", { idFile, idSign, cpf }, response => {
                alert(response.message);
                if (response.status == true) {
                    window.location.reload(true);
                }
            }, 'JSON').catch(e => {
                console.log(e);
                $('button.btn-assinar').removeAttr('disabled');

                alert('Erro ao processar solicitação. Tente novamente mais tarde!');
            });
        } else if (cpf != null) {
            alert('Documento inválido. Verifique o CPF e tente novamente!');
            assinarDocumento();
        }
    }

    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g, ''); // Remove caracteres não numéricos
        if (cpf.length !== 11 || !!cpf.match(/(\d)\1{10}/)) return false;

        var soma = 0;

        for (var i = 0; i < 9; i++) soma += parseInt(cpf.charAt(i)) * (10 - i);
        var resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) resto = 0;
        if (resto !== parseInt(cpf.charAt(9))) return false;

        soma = 0;
        for (i = 0; i < 10; i++) soma += parseInt(cpf.charAt(i)) * (11 - i);
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) resto = 0;
        if (resto !== parseInt(cpf.charAt(10))) return false;

        return true;
    }
</script>