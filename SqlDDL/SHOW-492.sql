ALTER TABLE showtecsystem.cad_clientes ADD microsiga VARCHAR(20) AFTER client_linker_id;
ALTER TABLE showtecsystem.cad_clientes ADD inscricao_municipal VARCHAR(50) AFTER inscricao_estadual;
ALTER TABLE showtecsystem.cad_clientes ADD nome_responsavel VARCHAR(250) AFTER razao_social;
