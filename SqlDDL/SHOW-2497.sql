INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao,cod_permissao,status,modulo)
VALUES ('Consulta Iscas com info. Cliente', 'vis_isca_clientes', '1', 'Administrativo Iscas')

SET @idPCP = (select id from showtecsystem.menu where nome = 'pcp');
select  @idPCP;

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en, link_bi) VALUES
( 'pcp_iscas_cliente', 'Pcp/iscasClientes' , 'vis_isca_clientes', 'nao', @idPCP, 'textsms', 999, 'ativo', 'Iscas de Clientes', 'Customer Lures', null);