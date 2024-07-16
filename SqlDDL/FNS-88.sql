INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao,cod_permissao,status,modulo)
VALUES ('Dashboard de Firmwares', 'vis_firmware_dashboard', '1', 'Dashboards')

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi)
VALUES (
    'firmware_dashboard',
    'Firmware/Firmware/Dashboard',
    'vis_firmware_dashboard',
    'nao',
    (SELECT id FROM (SELECT id FROM showtecsystem.menu WHERE nome = 'firmware') AS subquery),
    'dashboard',
    999,
    'ativo',
    'Dashboard',
    'Dashboard',
    null
)