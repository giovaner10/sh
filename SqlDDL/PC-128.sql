ALTER TABLE `portal_compras`.`solicitacoes`
ADD COLUMN `id_centro_custo` INT UNSIGNED NOT NULL AFTER `id_cotacoes`,
ADD COLUMN `id_filiais` INT UNSIGNED NOT NULL AFTER `id_centro_custo`,
ADD INDEX `idx_fk__solicitacoes__filiais` (`id_filiais` ASC),
ADD INDEX `idx_fk__solicitacoes__centro_custo` (`id_centro_custo` ASC),
ADD CONSTRAINT `fk__solicitacoes__filiais` FOREIGN KEY (`id_filiais`) REFERENCES `portal_compras`.`filiais` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk__solicitacoes__centro_custo` FOREIGN KEY (`id_centro_custo`) REFERENCES `portal_compras`.`centro_custo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `portal_compras`.`solicitacoes`
DROP COLUMN `filial`,
DROP COLUMN `centro_custo`,
ADD COLUMN `codigo_pedido_erp` VARCHAR(20) NULL DEFAULT NULL AFTER `data_modificacao`;


ALTER TABLE `portal_compras`.`cotacoes`
CHANGE COLUMN `condicao_pagamento` `condicao_pagamento` VARCHAR(120) NOT NULL;


