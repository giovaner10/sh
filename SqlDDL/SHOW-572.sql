alter table ERP.movimento_expedicao add column id_transportador int(10) unsigned;

ALTER TABLE ERP.movimento_expedicao ADD CONSTRAINT movimento_expedicao_FK FOREIGN KEY (id_transportador) REFERENCES ERP.cadastro_transportador(id);

INSERT INTO showtecsystem.menu
(id, nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status)
VALUES(176, 'movimentosEstoque', 'MovimentosEstoque', 'vis_suporte', 'nao', 168, 'textsms', 999, 'ativo');

