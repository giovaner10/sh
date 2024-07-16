-- Adiciona regra que impede o mesmo suprimento em mais de um estoque
ALTER TABLE showtecsystem.suprimento_x_estoque
ADD UNIQUE (id_suprimento)