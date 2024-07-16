<div id="home">

    <div class="col-left">

        <div class="btn-group" data-toggle="buttons">
            <div class="btn-group">
                <span class="plano-name"><strong>M2M </strong></span>
                <label id="check" class="btn btn-default active">
                    <input type="radio" name="tipoPlano" id="m2m" autocomplete="off" checked>
                    <span class="glyphicon glyphicon-ok"></span>
                </label>
            </div>
            <div id="check-right" class="btn-group">
                <span class="plano-name"><strong>Banda Larga </strong></span>
                <label id="checkBL" class="btn btn-default">
                    <input type="radio" name="tipoPlano" id="bl" autocomplete="off">
                    <span class="glyphicon glyphicon-ok"></span>
                </label>
            </div>
        </div>


        <div id="bandaLarga" class="btn-group menu-plan" data-toggle="buttons">
            <div id="tipo-plano" class="btn-group">
                <label id="check-large" class="btn btn-default"><small class="label-plan">300 <p>MB</p></small>
                    <span class="glyphicon glyphicon-ok icon-check-large"></span>
                    <input type="radio" name="plano2" id="option1" autocomplete="off">
                </label>
            </div>
            <div id="tipo-plano" class="btn-group">
                <label id="check-large" class="btn btn-default"><small class="label-plan">500 <p>MB</p></small>
                    <span class="glyphicon glyphicon-ok icon-check-large"></span>
                    <input type="radio" name="plano2" id="option1" autocomplete="off">
                </label>
            </div>
            <div id="tipo-plano" class="btn-group">
                <label id="check-large" class="btn btn-default"><small class="label-plan">1 <p>GB</p></small>
                    <span class="glyphicon glyphicon-ok icon-check-large"></span>
                    <input type="radio" name="plano2" id="option1" autocomplete="off">
                </label>
            </div>
            <div id="tipo-plano" class="btn-group">
                <label id="check-large" class="btn btn-default"><small class="label-plan">2 <p>GB</p></small>
                    <span class="glyphicon glyphicon-ok icon-check-large"></span>
                    <input type="radio" name="plano2" id="option1" autocomplete="off">
                </label>
            </div>
            <div id="tipo-plano" class="btn-group">
                <label id="check-large" class="btn btn-default"><small class="label-plan">3 <p>GB</p></small>
                    <span class="glyphicon glyphicon-ok icon-check-large"></span>
                    <input type="radio" name="plano2" id="option1" autocomplete="off">
                </label>
            </div>

        </div>

        <div id="m2m" class="btn-group menu-plan" data-toggle="buttons">
            <div id="tipo-plano" class="btn-group">
                <label id="check-large2" class="btn btn-default m2m1"><small class="label-plan" style="width: 80px">10 <p>MB</p></small>
                    <span class="glyphicon glyphicon-ok icon-check-large"></span>
                    <input type="radio" name="plano" id="option2" autocomplete="off">
                </label>
            </div>
            <div id="tipo-plano" class="btn-group">
                <label id="check-large2" class="btn btn-default m2m2"><small class="label-plan">1GB<p>DEP FLEX</p></small>
                    <span class="glyphicon glyphicon-ok icon-check-large"></span>
                    <input type="radio" name="plano" id="option2" autocomplete="off">
                </label>
            </div>
            <div id="tipo-plano" class="btn-group">
                <label id="check-large2" class="btn btn-default m2m3"><small class="label-plan">1GB<p>FLEX</p></small>
                    <span class="glyphicon glyphicon-ok icon-check-large"></span>
                    <input type="radio" name="plano" id="option2" autocomplete="off">
                </label>
            </div>
            <div id="tipo-plano" class="btn-group">
                <label id="check-large2" class="btn btn-default m2m4"><small class="label-plan">3GB<p>DEP FLEX</p></small>
                    <span class="glyphicon glyphicon-ok icon-check-large"></span>
                    <input type="radio" name="plano" id="option2" autocomplete="off">
                </label>
            </div>
            <div id="tipo-plano" class="btn-group">
                <label id="check-large2" class="btn btn-default m2m5"><small class="label-plan">3GB<p>FLEX</p></small>
                    <span class="glyphicon glyphicon-ok icon-check-large"></span>
                    <input type="radio" name="plano" id="option2" autocomplete="off">
                </label>
            </div>
        </div>

        <!--        <div class="response-plan checkLarge-active active">-->
        <!--            <span class="glyphicon glyphicon-ok icon-check-large2"></span>-->
        <!--        </div>-->

        <div id="inputs-text" class="form-group">
            <label class="control-label">CCID</label>
            <input class="form-control" type="text"/>
        </div>
        <div id="inputs-text" class="form-group">
            <label class="control-label">Linha</label>
            <input class="form-control" type="text"/>
        </div>
        <div id="inputs-text" class="form-group">
            <label class="control-label">Operadora</label>
            <input class="form-control" type="text"/>
        </div>

        <button class="btn btn-primary pull-left">Adicionar</button>

    </div>

    <div id="list-chips" class="col-right">
        <ul>
            <li><i class="fa fa-times"></i><div><h4>+5583988887777</h4></div> <span><small> - 07/12/2016</small></span> </li>
            <li><i class="fa fa-times"></i><div><h4>+5583988887777</h4></div> <span><small> - 07/12/2016</small></span> </li>
            <li><i class="fa fa-times"></i><div><h4>+5583988887777</h4></div> <span><small> - 07/12/2016</small></span> </li>
            <li><i class="fa fa-times"></i><div><h4>+5583988887777</h4></div> <span><small> - 07/12/2016</small></span> </li>
            <li><i class="fa fa-times"></i><div><h4>+5583988887777</h4></div> <span><small> - 07/12/2016</small></span> </li>
        </ul>

    </div>

    <div class="button-CadChip">
        <button class="btn btn-success pull-right">Cadastrar</button>
    </div>
</div>

<script>
    //    //MOSTRAR EXPLICATIVO PLANO
    //    $('.m2m1').click(function(){
    //        $('.response-plan').html('<h4>m2m pacote compartilhado dep flex</h4>');
    //        $('.response-plan').css('display', 'block');
    //        $('.response-plan').removeClass().addClass('response-plan checkLarge-active active animated zoomIn');
    //    });
    //    $('.m2m2').click(function(){
    //        $('.response-plan').html('<h4>m2m pacote compartilhado dep flex2</h4>');
    //        $('.response-plan').css('display', 'block');
    //        $('.response-plan').removeClass().addClass('response-plan checkLarge-active active animated fadeIn');
    //    });
    //    $('.m2m3').click(function(){
    //        $('.response-plan').html('<h4>m2m pacote compartilhado dep flex3</h4>');
    //        $('.response-plan').css('display', 'block');
    //        $('.response-plan').removeClass().addClass('response-plan checkLarge-active active animated flipInX');
    //    });
    //    $('.m2m4').click(function(){
    //        $('.response-plan').html('<h4>m2m pacote compartilhado dep flex4</h4>');
    //        $('.response-plan').css('display', 'block');
    //        $('.response-plan').removeClass().addClass('response-plan checkLarge-active active animated fadeInDown');
    //    });
    //    $('.m2m5').click(function(){
    //        $('.response-plan').html('<h4>m2m pacote compartilhado dep flex5</h4>');
    //        $('.response-plan').css('display', 'block');
    //        $('.response-plan').removeClass().addClass('response-plan checkLarge-active active animated fadeInUp');
    //    });

    //EFEITO CHECKBOX PLANO
    $('label#check-large2').click(function(){
        $('label#check-large2').removeClass('checkLarge-active');
        $(this).stop(true,true).addClass("checkLarge-active");
    });
    $('label#check-large').click(function(){
        $('label#check-large').removeClass('checkLarge-active');
        $(this).stop(true,true).addClass("checkLarge-active");
    });

    //EFEITO MOSTRAR PLANO BL
    $('label#checkBL').click(function(){

        $('div#bandaLarga').css('display', 'block');
        $('div#m2m').css('display', 'none');
        $('div#bandaLarga').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend');
        $('div#bandaLarga').removeClass().addClass('animated flipInX');
    });
    $('label#check').click(function(){

        $('div#m2m').css('display', 'block');
        $('div#bandaLarga').css('display', 'none');
        $('div#m2m').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend');
        $('div#m2m').removeClass().addClass('animated flipInX');
    });


</script>