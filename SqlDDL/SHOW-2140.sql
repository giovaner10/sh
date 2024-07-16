SET @menu_bi = (select id from showtecsystem.menu where nome = 'menu_bi');
select  @menu_bi;

INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, modulo) VALUES 
('demais_areas_bi', 'vis_demais_areas_bi', 1, 'BI'),
('acompanhamento_af_bi', 'vis_acompanhamento_af_bi', 1, 'BI')

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi) VALUES
( 'demais_areas_bi', null , 'vis_demais_areas_bi', 'sim', @menu_bi, 'dashboard', 186, 'ativo', 'Demais Áreas', 'Other Areas', null);

SET @menu_demais_areas_bi = LAST_INSERT_ID(); 
select  @menu_demais_areas_bi;

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES
( 'acompanhamento_af_bi', 'BI/BI', 'vis_acompanhamento_af_bi', 'nao', @menu_demais_areas_bi, 'dashboard', 999, 'ativo', 'Acompanhamento AF', 'AF Tracking', 'https://app.powerbi.com/view?r=eyJrIjoiNzFmMmY1ZGQtNDNjNy00MjMwLTk5YzItZWYwODM1MDIxY2JhIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9')

