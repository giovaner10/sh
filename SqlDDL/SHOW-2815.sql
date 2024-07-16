USE showtecsystem;

CREATE TABLE ocorrencia_cliente(
    id INT AUTO_INCREMENT PRIMARY KEY,
    data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    idCliente INT UNSIGNED,
    descricao TEXT,
    FOREIGN KEY (idCliente) REFERENCES cad_clientes(id)
);
