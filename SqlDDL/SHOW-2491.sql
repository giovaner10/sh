SET @id_pai = (select id from showtecsystem.menu where codigo_permissao = 'vis_relatorios');
select  @id_pai;

INSERT INTO showtecsystem.cad_permissoes_funcionarios
(descricao, cod_permissao, status, modulo) 
VALUES('Últimos Acessos WSTT', 'vis_ultimos_acessos', 1, 'Relatórios');

INSERT INTO showtecsystem.menu
(nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES('Últimos Acessos WSTT', 'relatorios/UltimosAcessosWSTT', 'vis_ultimos_acessos', 'nao', @id_pai, 'summarize', 1, 'ativo', 'Últimos Acessos WSTT', 'Last Accessed WSTT', NULL);