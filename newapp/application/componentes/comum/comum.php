<?php

function tituloPaginaComponente($titulo, $link, $pagina, $subpagina)
{
    $componente = '<div class="text-title titleContatosCorporativos">' .
        '<h3 style="padding: 0 20px; margin-left: 15px;">' . $titulo . '</h3>' .
        '<h4 style="padding: 0 20px; margin-left: 15px;">' .
        '<a style="text-decoration: none" href="' . $link . '">Home</a> > ' .
        $pagina . ' > ' .
        $subpagina .
        '</h4>' .
        '</div>';
    echo $componente;
}

function loadingSpinner(){
    echo '<div id="loading">'.
            '<div class="loader"></div>'.
        '</div>';
}


function menuLateralComponente($menuInterno = [])
{

    echo '<div class="card menu-interno">' .
        '<h4 style="margin-bottom: 0px !important;">Menu</h4>' .
        '<ul>';

    $primeiroElemento = true;

    foreach ($menuInterno as $menu) {

        $menuSemEspaco = str_replace(' ', '', $menu);
        $classeSelecionada = $primeiroElemento ? 'selected' : '';

        echo '<li style="text-decoration: none">' .
            '<a style="text-decoration: none" class="menu-interno-link ' . $classeSelecionada . '" id="' . $menuSemEspaco . '-tab" data-toggle="tab" href="#' . $menuSemEspaco . '">' . $menu . '</a>' .
            '</li>';

        $primeiroElemento = false;
    }

    echo '</ul>' .
        '</div>';
}
