
SET @menu_logistica_shownet = (select ID from showtecsystem.menu  where nome = 'Logistica' limit 0,1);

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
VALUES( 'solicitacao_expedicao', 'SolicitacaoExpedicao', 'logistica_shownet', 'nao', @menu_logistica_shownet, 'shopping_cart', 999, 'ativo', 'Solicitação Expedição', 'Shipping Request');

