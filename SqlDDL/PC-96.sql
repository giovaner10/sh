INSERT INTO `showtecsystem`.`cad_permissoes_funcionarios` (descricao, cod_permissao, status, modulo)
VALUES('Visualizar Configurações - Portal Compras', 'vis_configuracoes_portal_compras', '1', 'Portal de Compras'),
('Editar Configurações - Portal Compras', 'edi_configuracoes_portal_compras', '1', 'Portal de Compras');

CREATE TABLE `portal_compras`.`configuracoes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `alcada_de_aprovacao` JSON NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `portal_compras`.`configuracoes`
	(alcada_de_aprovacao)
	VALUES('{
		"diretor_min": 0.0,
		"diretor_max": 50000.00,
		"cfo_min": 50000.01,
		"cfo_max": 100000.00,
		"ceo_min": 100000.01
		}');