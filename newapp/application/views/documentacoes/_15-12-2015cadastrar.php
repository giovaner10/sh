<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>

    $(function () {
        $("#datepicker").datepicker({format: "dd-mm-yyyy"});

    });

    /*
     $(function() {var progressbar = $( "#progressbar" ),
     progressLabel = $( ".progress" );
     
     progressbar.progressbar({
     value: false,
     change: function() {
     progressLabel.text( progressbar.progressbar( "value" ) + "%" );
     },
     complete: function() {
     progressLabel.text( "Completo!" );
     }
     });
     
     function progress() {
     var val = progressbar.progressbar( "value" ) || 0;
     
     progressbar.progressbar( "value", val + 2 );
     
     if ( val < 99 ) {
     setTimeout( progress, 80 );
     }
     }
     
     setTimeout( progress, 2000 );
     });
     
     
     */
</script>


<style>

    fieldset{
        margin-bottom: 20%;



    }

    legend{
        font-weight: 900;
    }
    label{
        font-weight: 700;
        font-size: 16px;
        padding: 5px;
    }

    .ui-progressbar {
        position: relative;

    }
    .progress {
        position: absolute;
        left: 50%;
        top: 4px;
        font-weight: bold;
        text-shadow: 1px 1px 0 #fff;

    }

</style>




<?php if ($this->auth->get_login('admin', 'email')): ?>
    <?php $usuario_logado = $this->auth->get_login('admin', 'email') ?>

<?php elseif ($this->auth->get_login('instalador', 'email')): ?>
    <?php $usuario_logado = $this->auth->get_login('instalador', 'email') ?>
<?php endif; ?>




<div class='container'>

    <?php
    if (isset($error)) {
        echo $error;
    }
    ?> 
    <fieldset>
        <legend>Anexar documento</legend>
 
        <div class="row-fluid" style=' margin-left: 1px; width: 100%;       border-style: solid;
             border-width: 1px;border-color: #e5e5e5;
             border-radius: 1em; padding: 10px;'>

            <?php
            echo form_open_multipart(base_url() . "index.php/documentacoes/upload_file");

            $datestring = "%Y/%m/%d";
            $time = time();
            $data_insercao = mdate($datestring, $time);
            ?>
            <div class='span3'>

                <!-- data insercao -->
                <input type="hidden" name="data_insercao"
                       id="data_insercao" value="<?php echo $data_insercao; ?>">

                <!-- usuario logado -->
                <input type="hidden" name="usuario_logado"
                       id="usuario_logado" value="<?php echo $usuario_logado; ?>">



                <label>Assunto</label>
                <input class='form-control' placeholder="Será o titulo do email" type="text" name='nome_documento' required="required">            

                <label for='data de vencimento'>Vencimento documentação</label>
                <input type="date" id="datepicker" class='form-control' name='data_vencimento' required="required">

            </div>

            <div class='span6'>
                <label for='email_destinatario'>Enviar por email para:</label>
                <input  type="text" placeholder="*Digite os emails separados por virgula" class='form-control input-block-level' name='destinatario_email'>
            </div>

            <div class='span4'>
                <label class="">Anexar documentação</label>
<?php
echo form_upload(array
    (
    'type' => 'file',
    'required' => 'required',
    'name' => 'file',
    'size' => '1000'
));
?>

            </div>

<?php
echo "<div class='span1' style='margin-left:3%;'><br>";
echo form_submit("upload", "Salvar", "class = 'btn btn-success btn-large'");
echo "</div>";

echo form_close();
?>     </div>
    </fieldset>

    <!--
   <div id="progressbar"><div class="progress">Loading...</div></div>
   </div>
   
    -->