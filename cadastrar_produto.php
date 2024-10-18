<?php
// Incluir o arquivo de conexão
include 'conexao.php';

$mensagem = ""; // Variável para armazenar mensagens de sucesso ou erro

// Verifica se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receber dados do formulário
    $nome = $_POST["nome"] ?? null;
    $descricao = $_POST["descricao"] ?? null;
    $preco = $_POST["preco"] ?? null;
    $categoria = $_POST["categoria"] ?? null;
    $tamanhos = $_POST["tamanhos"] ?? []; // É um array de tamanhos
    $quantidades = $_POST["quantidades"] ?? []; // É um array de quantidades

    // Verifica se todos os parâmetros estão presentes
    if (!$nome || !$descricao || !$preco || !$categoria || empty($tamanhos)) {
        returnMissingParameters();
    }

    try {
        // Inserir produto na tabela Produto
        $sql = "INSERT INTO Produto (nome, descricao, preco, data_criacao) VALUES (:nome, :descricao, :preco, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':nome' => $nome, ':descricao' => $descricao, ':preco' => $preco]);

        $id_produto = $pdo->lastInsertId(); // Pegar o id do produto inserido

        // Associar o produto à categoria
        $sql_categoria = "INSERT INTO Produto_Categoria (id_produto, id_categoria) VALUES (:id_produto, :id_categoria)";
        $stmt_categoria = $pdo->prepare($sql_categoria);
        $stmt_categoria->execute([':id_produto' => $id_produto, ':id_categoria' => $categoria]);

        // Associar o produto aos tamanhos e quantidades
        $sql_tamanho = "INSERT INTO Produto_Tamanho (id_produto, id_tamanho, quantidade) VALUES (:id_produto, :id_tamanho, :quantidade)";
        $stmt_tamanho = $pdo->prepare($sql_tamanho);
        
        foreach ($tamanhos as $index => $tamanho) {
            $quantidade = isset($quantidades[$index]) ? $quantidades[$index] : 0; // Captura a quantidade correspondente
            $stmt_tamanho->execute([':id_produto' => $id_produto, ':id_tamanho' => $tamanho, ':quantidade' => $quantidade]);
        }

        $mensagem = "<div class='message success'>Produto cadastrado com sucesso!</div>"; // Mensagem de sucesso
    } catch (Exception $e) {
        error_log($e->getMessage());
        $mensagem = "<div class='message error'>Erro ao cadastrar o produto: " . $e->getMessage() . "</div>"; // Mensagem de erro
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> <!-- Adicionando a fonte -->
    <style>
        body {
            font-family: 'Roboto', sans-serif; /* Mudando a fonte para Roboto */
            background-color: #f8f9fa; /* Cor de fundo */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Garantir que o box-sizing inclua padding e borda */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        .layout {
            max-width: 600px; /* Largura máxima do layout */
            width: 90%; /* Largura responsiva */
            background-color: #fff; /* Cor de fundo do layout */
            border-radius: 5px; /* Bordas arredondadas */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Sombra do layout */
            padding: 20px; /* Espaçamento interno */
        }

        h1 {
            text-align: center; /* Centralizar o título */
            color: #333; /* Cor do título */
            font-family: 'Verdana', sans-serif; /* Mudando a fonte do título */
            margin-bottom: 20px; /* Espaço abaixo do título */
        }

        label {
            display: block; /* Display em bloco */
            margin: 15px 0 5px; /* Margem em cima e embaixo */
            color: #555; /* Cor do texto do rótulo */
            font-weight: bold; /* Negrito */
        }

        input[type="text"], input[type="number"], textarea, select {
            width: 100%; /* Largura total do campo */
            padding: 10px; /* Espaço interno do campo */
            margin-bottom: 10px; /* Espaçamento abaixo do campo */
            border: 1px solid #ccc; /* Borda do campo */
            border-radius: 4px; /* Bordas arredondadas */
            font-size: 16px; /* Tamanho da fonte */
            overflow: hidden; /* Evitar que o texto saia do campo */
            resize: none; /* Impedir redimensionamento do textarea */
        }

        button {
            display: block; /* Exibir como bloco */
            width: 100%; /* Largura total do botão */
            background-color: #28a745; /* Cor do botão */
            color: white; /* Cor do texto do botão */
            border: none; /* Sem borda */
            padding: 10px; /* Espaçamento interno do botão */
            border-radius: 4px; /* Bordas arredondadas do botão */
            font-size: 16px; /* Tamanho da fonte */
            cursor: pointer; /* Cursor de mão ao passar */
            margin-bottom: 10px; /* Espaço abaixo do botão */
        }

        button:hover {
            background-color: #218838; /* Cor do botão ao passar o mouse */
        }

        /* Estilo para os checkboxes e quantidades */
        .checkbox-container {
            display: flex; /* Usar flexbox para alinhamento em linha */
            flex-wrap: nowrap; /* Evitar que os checkboxes quebrem para uma nova linha */
            margin: 10px 0; /* Espaço acima e abaixo dos checkboxes */
            gap: 10px; /* Espaço entre os checkboxes */
            overflow-x: auto; /* Permitir rolagem horizontal se necessário */
        }

        .checkbox-container label {
            margin: 0; /* Remover margens do label */
        }

        .quantidade {
            width: 50px; /* Largura da caixa de quantidade */
            margin-left: 5px; /* Espaço à esquerda da caixa de quantidade */
        }

        .message {
            padding: 10px; /* Espaçamento interno da mensagem */
            margin-bottom: 20px; /* Espaçamento abaixo da mensagem */
            border-radius: 5px; /* Bordas arredondadas */
        }

        .error {
            background-color: #f8d7da; /* Cor de fundo para mensagem de erro */
            color: #721c24; /* Cor do texto para mensagem de erro */
        }

        .success {
            background-color: #d4edda; /* Cor de fundo para mensagem de sucesso */
            color: #155724; /* Cor do texto para mensagem de sucesso */
        }

        .btn-blue {
            background-color: #007bff; /* Cor azul */
        }

        .btn-blue:hover {
            background-color: #0056b3; /* Cor azul ao passar o mouse */
        }
    </style>
</head>
<body>
    <div class="layout">
        <h1>Cadastro de Produto</h1>
        <?php if ($mensagem): // Exibir mensagem se houver ?>
            <div><?php echo $mensagem; ?></div>
        <?php endif; ?>
        <form method="POST" action="cadastrar_produto.php">
            <label for="nome">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="4" required></textarea>

            <label for="preco">Preço:</label>
            <input type="number" step="0.01" id="preco" name="preco" required>

            <label for="categoria">Categoria:</label>
            <select id="categoria" name="categoria" required>
                <?php
                // Exibir opções de categoria dinamicamente
                $sql = "SELECT id_categoria, nome_categoria FROM Categoria";
                $stmt = $pdo->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row['id_categoria'] . "'>" . $row['nome_categoria'] . "</option>";
                }
                ?>
            </select>

            <label for="tamanhos">Tamanhos:</label>
            <div class="checkbox-container">
                <?php
                // Exibir tamanhos como checkboxes
                $sql = "SELECT id_tamanho, descricao_tamanho FROM Tamanho";
                $stmt = $pdo->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<label><input type='checkbox' name='tamanhos[]' value='" . $row['id_tamanho'] . "'>" . $row['descricao_tamanho'] . "</label>";
                    echo "<input type='number' name='quantidades[]' class='quantidade' min='0' value='0'>"; // Campo de quantidade
                }
                ?>
            </div>
            <button type="submit">Cadastrar Produto</button>
            <button type="button" class="btn-blue" onclick="window.location.href='listar_produtos.php'">Lista de Produtos</button> <!-- Botão azul -->
        </form>
    </div>
</body>
</html>
