document.addEventListener('DOMContentLoaded', function() {
  const btnMenuShowNorio = document.getElementById('menu-show-norio');
  const btnMenuOmnilink = document.getElementById('menu-omnilink');
  const btnMenuRegistroChamadas = document.getElementById('menu-registro-chamadas');
  const btnMenuFilaLigacoes = document.getElementById('menu-fila-ligacoes');

  function removeActiveClassFromButtons() {
    btnMenuShowNorio.classList.remove('active');
    btnMenuOmnilink.classList.remove('active');
    btnMenuRegistroChamadas.classList.remove('active');
    btnMenuFilaLigacoes.classList.remove('active');
  }

  function ativarGuia(nome) {
    const guias = [
        'aba-show-norio',
        'aba-omnilink',
        'aba-registro-chamadas',
        'aba-fila-ligacoes'
    ];
  
    guias.forEach((guia) => {
        if (guia === nome) {
            $(`#${guia}`).show();
        } else {
            $(`#${guia}`).hide();
        }
    });
  }

  function ativarFiltro(nome) {
    const guias = [
        'filtroBusca',
        'filtro-registro-chamadas',
    ];
  
    guias.forEach((guia) => {
        if (guia === nome) {
            $(`#${guia}`).show();
        } else {
            $(`#${guia}`).hide();
        }
    });
  }


  // Adicionar event listeners aos botões do menu
  btnMenuShowNorio.addEventListener('click', (e) => {
    e.preventDefault();
      // Exibir a aba de show norio e ocultar as outras
      ativarGuia('aba-show-norio');
      
      //Exibe o filtro de busca
      ativarFiltro('filtroBusca');

      //Renderiza a cor do menu selecionado
      removeActiveClassFromButtons();
      btnMenuShowNorio.classList.add('active');
  });

  btnMenuOmnilink.addEventListener('click', (e) => {
      e.preventDefault();
      // Exibir a aba de omnilink e ocultar as outras
      ativarGuia('aba-omnilink');

      //Exibe o filtro de busca
      ativarFiltro('filtroBusca');

      //Renderiza a cor do menu selecionado
      removeActiveClassFromButtons();
      btnMenuOmnilink.classList.add('active');
  });

  btnMenuRegistroChamadas.addEventListener('click', (e) => {
    e.preventDefault();
      // Exibir a aba de registro de chamadas e ocultar as outras
      ativarGuia('aba-registro-chamadas');

      //Para a exibição do filtro de busca
      ativarFiltro('filtro-registro-chamadas');

      //Renderiza a cor do menu selecionado
      removeActiveClassFromButtons();
      btnMenuRegistroChamadas.classList.add('active');
  });

  btnMenuFilaLigacoes.addEventListener('click', (e) => {
    e.preventDefault();
      // Exibir a aba de Fila de ligação e ocultar as outras
      ativarGuia('aba-fila-ligacoes');

      //Renderiza a cor do menu selecionado
      removeActiveClassFromButtons();
      btnMenuFilaLigacoes.classList.add('active');

      //Remove o filtro de busca
      ativarFiltro('');
  });
});
