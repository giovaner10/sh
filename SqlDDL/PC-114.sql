CREATE TABLE `portal_compras`.`log_solicitacoes` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `acao` ENUM(
        'cadastrar',
        'remover',
        'editar',
        'adicionar_cotacao',
        'adicionar_produto_cotacao',
        'selecionar_cotacao',
        'aprovar',
        'reprovar',
        'comentar'
    ) NOT NULL DEFAULT 'cadastrar',
    `datahora_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `id_usuario` INT UNSIGNED NOT NULL,
    `id_solicitacoes` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_fk__log_solicitacoes__usuario` (`id_usuario` ASC),
    INDEX `idx_fk__log_solicitacoes__solicitacoes` (`id_solicitacoes` ASC),
    CONSTRAINT `fk__log_solicitacoes__usuario` FOREIGN KEY (`id_usuario`) REFERENCES `showtecsystem`.`usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT `fk__log_solicitacoes__solicitacoes` FOREIGN KEY (`id_solicitacoes`) REFERENCES `portal_compras`.`solicitacoes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);