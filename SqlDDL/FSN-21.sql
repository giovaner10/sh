INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao,cod_permissao,status,modulo)
VALUES ('Firmware', 'vis_Firmware', '1', 'Firmware')

SET @idFirmware = (select id from showtecsystem.menu where nome = 'firmware');
select  @idFirmware;

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi) VALUES
( 'firmware_lista', 'Firmware/Firmware' , 'vis_Firmware', 'nao', @idFirmware, 'textsms', 999, 'ativo', 'Lista Firmware', 'Firmware List', null);