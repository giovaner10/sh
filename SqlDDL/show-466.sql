CREATE TABLE showtecsystem.tabelas_auditoria_shownet (
	id int(10) auto_increment NOT NULL,
	referencia_tabela varchar(60) NOT NULL,
	nome_tabela varchar(60) NOT NULL,
	CONSTRAINT tabelas_auditoria_shownet_PK PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

INSERT INTO showtecsystem.cad_permissoes_funcionarios
(id, descricao, cod_permissao, status, data_cad, modulo)
VALUES(637, 'auditoria_shownet', 'cad_auditoriashownet', '1', '2023-01-02 09:05:22', 'Administração');
