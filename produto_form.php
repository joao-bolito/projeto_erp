<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9fbfd;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            background: white;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #34495e;
        }

        label {
            display: block;
            margin-top: 20px;
            font-weight: bold;
            color: #2c3e50;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 5px;
        }

        .variacoes > div {
            margin-top: 10px;
        }

        .container-btn{
            display: flex;
            flex-direction: row;
            gap: 15px;
        }

        button {
            margin-top: 25px;
            background-color: #27ae60;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1e8449;
        }


        a.voltar {
            display: block;
            width: 150px;
            margin: 30px auto 0 auto;
            text-align: center;
            background-color: #2c3e50;
            color: white;
            padding: 12px;
            border-radius: 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Cadastro de Produto</h2>
    <form action="salvar_produto.php" method="post">
        <label>Nome do Produto</label>
        <input type="text" name="nome" required>

        <label>Preço</label>
        <input type="number" step="0.01" name="preco" required>

        <h3>Variações</h3>
        <div class="variacoes" id="variacoes">
            <div>
                <input type="text" name="variacoes[0][nome]" placeholder="Nome da variação">
                <input type="number" name="variacoes[0][estoque]" placeholder="Estoque">
            </div>
        </div>
        <div class="container-btn">
            <button type="button" onclick="addVariacao()">+ Adicionar Variação</button>
    
            <br>
            <button type="submit">Salvar Produto</button>
        </div>
        <br>
        <a href="index.php" class="voltar">⬅ Voltar ao Início</a>
    </form>
</div>

<script>
let index = 1;
function addVariacao() {
    const container = document.getElementById('variacoes');
    const html = `
        <div>
            <input type="text" name="variacoes[${index}][nome]" placeholder="Nome da variação">
            <input type="number" name="variacoes[${index}][estoque]" placeholder="Estoque">
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    index++;
}
</script>
</body>
</html>
