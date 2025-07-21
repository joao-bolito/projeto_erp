<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Mini ERP - Início</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f5;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #2c3e50;
        }
        .menu {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 40px;
        }
        .menu a {
            display: inline-block;
            padding: 15px 30px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            font-size: 18px;
            border-radius: 10px;
            transition: background 0.3s;
        }
        .menu a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <h1>Sistema de Vendas</h1>

    <div class="menu">
        <a href="produto_form.php">📦 Cadastrar Produto</a>
        <a href="estoque.php">📋 Ver Estoque</a>
        <a href="ver_carrinho.php">🛒 Ver Carrinho</a>
        <a href="cupons.php">🎟️ Gerenciar Cupons</a>
    </div>

</body>
</html>
