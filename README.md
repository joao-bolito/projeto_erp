# 🛒 Mini ERP - Cadastro de Produtos com Variações

Este é um sistema simples para cadastro de produtos com variações (ex: tamanho, cor), onde cada variação possui seu próprio controle de estoque.

## ✅ Tecnologias Utilizadas

- **PHP 8.1.25**
- **XAMPP** (Apache + MySQL)
- HTML + CSS + JavaScript (puro)
- Banco de dados MySQL (via phpMyAdmin)

## ⚙️ Requisitos

- Ter o [XAMPP](https://www.apachefriends.org/pt_br/index.html) instalado (ou outro servidor com suporte a PHP 8.1.25)
- Clonar ou baixar este repositório em seu ambiente local

## 🚀 Como rodar o projeto localmente

1. Abra o **XAMPP** e inicie os módulos **Apache** e **MySQL**
2. Coloque os arquivos deste projeto na pasta `htdocs` (ex: `C:\xampp\htdocs\meu-projeto`)
3. Acesse via navegador: http://localhost/meu-projeto
4. Para criar produtos, vá para a página de cadastro e preencha:
- Nome do produto
- Preço
- Variações (quantas quiser)
5. Os dados serão salvos no banco de dados (verifique se há integração com um banco, conforme o `salvar_produto.php`)

## 🗃️ Estrutura Básica dos Arquivos
meu-projeto/
│
├── index.php
├── cadastrar_produto.php
├── salvar_produto.php
├── db_conn.php
└── README.md

## 📌 Observações

- A versão utilizada do PHP foi a **8.1.25**, incluída no pacote **XAMPP v8.2.4 ou superior**
- Você pode usar o **phpMyAdmin** (disponível em `http://localhost/phpmyadmin`) para gerenciar o banco de dados

## 📬 Contato

Se tiver dúvidas ou sugestões, entre em contato!  
👨‍💻 Desenvolvido por **João Vitor Bolito dos Anjos**
