<section id="menu-page" class="container-fluid">
    <div id="menu-home" class=""><i class="material-icons">apps</i></div>
    <div onclick="abreURL('https://gestor.showtecnologia.com/sistema/newapp/application/views/gerencia_chips/home.php','GET','conteudo');" id="menu-item" class="menu-active"><h3>Cadastrar Chips</h3></div>
    <div onclick="abreURL('https://gestor.showtecnologia.com/newapp/application/views/gerencia_chips/relatorios.php','GET','conteudo');" id="menu-item" class="text-muted"><h3>Relatórios</h3></div>
    <div onclick="abreURL('https://gestor.showtecnologia.com/sistema/newapp/application/views/gerencia_chips/faturas.php','GET','conteudo');" id="menu-item" class="text-muted"><h3>Faturas</h3></div>
    <div onclick="abreURL('https://gestor.showtecnologia.com/sistema/newapp/application/views/gerencia_chips/vincular.php','GET','conteudo');" id="menu-item" class="b-none text-muted"><h3>Vincular</h3></div>
</section>
<!--<div id="carregador">Carregando...</div>-->

<section id="cad-chip" class="container-fluid"></section>

<script>
    //CARREGAR PÁGINAS
    function abreURL(url,metodo,onde){
        if(metodo=='POST'){
// metodo post
            $.post(url, function(data) {
// página do carregador (loading)
                $("#carregador").show();
                $( "#cad-chip").load(url);
            });
        }
        else if(metodo=='GET'){
// metodo get
            $.get(url, function(data) {
// página do carregador (loading)
                $("#carregador").show();
                $( "#cad-chip").load(url);
            });
        }
    }

</script>
