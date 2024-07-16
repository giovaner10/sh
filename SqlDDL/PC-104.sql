CREATE TABLE `portal_compras`.`cotacoes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fornecedor` JSON NOT NULL,
  `valor_total` FLOAT(10,2) NOT NULL,
  `condicao_pagamento` ENUM('21', '30', '45', '60') NOT NULL DEFAULT '30',
  `forma_pagamento` ENUM('pix', 'boleto', 'ted') NOT NULL DEFAULT 'boleto',
  `path_anexo` VARCHAR(120) NULL,
  `datahora_cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_usuario` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_fk__cotacoes__usuario` (`id_usuario` ASC),
  CONSTRAINT `fk__cotacoes__usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `showtecsystem`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
  );


ALTER TABLE `portal_compras`.`solicitacoes` 
ADD COLUMN `id_cotacoes` INT UNSIGNED NULL DEFAULT NULL AFTER `id_usuario`,
CHANGE COLUMN `situacao` `situacao` ENUM('aguardando_cotacao', 'aguardando_confirmacao_cotacao', 'aguardando_aprovacao', 'aprovado', 'reprovado') NOT NULL DEFAULT 'aguardando_cotacao' ,
ADD INDEX `idx_fk__solicitacoes__cotacoes` (`id_cotacoes` ASC);
;
ALTER TABLE `portal_compras`.`solicitacoes` 
ADD CONSTRAINT `fk__solicitacoes__cotacoes`
  FOREIGN KEY (`id_cotacoes`)
  REFERENCES `portal_compras`.`cotacoes` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;




CREATE TABLE `portal_compras`.`solicitacao_x_cotacoes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_solicitacoes` INT UNSIGNED NOT NULL,
  `id_cotacoes` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_fk__solicitacao_x_cotacoes__solicitacoes` (`id_solicitacoes` ASC),
  INDEX `idx_fk__solicitacao_x_cotacoes__cotacoes` (`id_cotacoes` ASC),
  UNIQUE INDEX `UNIQUE__id_solicitacoes__id_cotacoes` (`id_solicitacoes` ASC, `id_cotacoes` ASC),
  CONSTRAINT `fk__solicitacao_x_cotacoes__solicitacoes`
    FOREIGN KEY (`id_solicitacoes`)
    REFERENCES `portal_compras`.`solicitacoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk__solicitacao_x_cotacoes__cotacoes`
    FOREIGN KEY (`id_cotacoes`)
    REFERENCES `portal_compras`.`cotacoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) COMMENT = 'Associacao das sugestoes de cotacoes para uma solicitacao';