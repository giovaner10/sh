ALTER TABLE `portal_compras`.`cotacoes` 
CHANGE COLUMN `forma_pagamento` `forma_pagamento` ENUM('pix', 'boleto', 'ted', 'cartao_credito', 'deposito') NOT NULL DEFAULT 'boleto' ;
