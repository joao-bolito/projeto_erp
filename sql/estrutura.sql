query banco de dados:

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    preco DECIMAL(10,2) NOT NULL
);

CREATE TABLE variacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT,
    nome_variacao VARCHAR(255),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

CREATE TABLE estoque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT,
    variacao_id INT,
    quantidade INT DEFAULT 0,
    FOREIGN KEY (produto_id) REFERENCES produtos(id),
    FOREIGN KEY (variacao_id) REFERENCES variacoes(id)
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total DECIMAL(10,2),
    frete DECIMAL(10,2),
    cep VARCHAR(9),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE,
    desconto_percentual DECIMAL(5,2),
    ativo BOOLEAN DEFAULT TRUE
);