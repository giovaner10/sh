$(document).ready(function() {

//EXIBIR DATA
    var data = new Date();
    var dia = data.getDate() + '/' + (parseInt(data.getMonth()) + 1) + '/' + data.getFullYear();

    $("#data").html(dia);

});

// //EXIBIR HORA
function updateClock ( )
{
    var currentTime = new Date ( );
    var currentHours = currentTime.getHours ( );
    var currentMinutes = currentTime.getMinutes ( );
    var currentSeconds = currentTime.getSeconds ( );

    // Pad the minutes and seconds with leading zeros, if required
    currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
    currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

    // Choose either "AM" or "PM" as appropriate
    //var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

    // Convert the hours component to 12-hour format if needed
    //currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

    // Convert an hours component of "0" to "12"
    currentHours = ( currentHours == 0 ) ? 12 : currentHours;

    // Compose the string for display
    var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds;


    $("#hora").html(currentTimeString);

}
    setInterval('updateClock()', 1000);


// //EFEITO MENU-ACTIVE
$('div#menu-item').click(function(){

    $('div#menu-item').removeClass('menu-active');
    $(this).stop(true,true).addClass("menu-active");
});


//EFEITOS MENU LATERAL
$(function() {
    var accordionActive = false;

    $(window).on('resize', function() {
        var windowWidth = $(window).width();
        var $topMenu = $('#top-menu');
        var $sideMenu = $('#side-menu');

        if (windowWidth < 768) {
            if ($topMenu.hasClass("active")) {
                $topMenu.removeClass("active");
                $sideMenu.addClass("active");

                var $ddl = $('#top-menu .movable.dropdown');
                $ddl.detach();
                $ddl.removeClass('dropdown');
                $ddl.addClass('nav-header');

                $ddl.find('.dropdown-toggle').removeClass('dropdown-toggle').addClass('link');
                $ddl.find('.dropdown-menu').removeClass('dropdown-menu').addClass('submenu');

                $ddl.prependTo($sideMenu.find('.accordion'));
                $('#top-menu #qform').detach().removeClass('navbar-form').prependTo($sideMenu);

                if (!accordionActive) {
                    var Accordion2 = function(el, multiple) {
                        this.el = el || {};
                        this.multiple = multiple || false;

                        // Variables privadas
                        var links = this.el.find('.movable .link');
                        // Evento
                        links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown);
                    }

                    Accordion2.prototype.dropdown = function(e) {
                        var $el = e.data.el;
                        $this = $(this),
                        $next = $this.next();

                        $next.slideToggle();
                        $this.parent().toggleClass('open');

                        if (!e.data.multiple) {
                            $el.find('.movable .submenu').not($next).slideUp().parent().removeClass('open');
                        };
                    }

                    var accordion = new Accordion2($('ul.accordion'), false);
                    accordionActive = true;
                }
            }
        }
        else {
            if ($sideMenu.hasClass("active")) {
                $sideMenu.removeClass('active');
                $topMenu.addClass('active');

                var $ddl = $('#side-menu .movable.nav-header');
                $ddl.detach();
                $ddl.removeClass('nav-header');
                $ddl.addClass('dropdown');

                $ddl.find('.link').removeClass('link').addClass('dropdown-toggle');
                $ddl.find('.submenu').removeClass('submenu').addClass('dropdown-menu');

                $('#side-menu #qform').detach().addClass('navbar-form').appendTo($topMenu.find('.nav'));
                $ddl.appendTo($topMenu.find('.nav'));
            }
        }
    });

    /**/
    var $menulink = $('.side-menu-link'),
        $wrap = $('.wrap');

    $menulink.click(function() {
        $menulink.toggleClass('active');
        $wrap.toggleClass('active');
        return false;
    });

    /*Accordion*/
    var Accordion = function(el, multiple) {
        this.el = el || {};
        this.multiple = multiple || false;

        // Variables privadas
        var links = this.el.find('.link');
        // Evento
        links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown);
    }

    Accordion.prototype.dropdown = function(e) {
        var $el = e.data.el;
         $this = $(this),
         $next = $this.next();

        $next.slideToggle();
        $this.parent().toggleClass('open');

        if (!e.data.multiple) {
            $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
        };
    }

    var accordion = new Accordion($('ul.accordion'), false);


});
