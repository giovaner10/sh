ALTER TABLE `rastreamento`.`eventos_tratados` 
ADD COLUMN `tratado_no` ENUM('gestor', 'shownet') NOT NULL DEFAULT 'gestor' AFTER `observacao`;


