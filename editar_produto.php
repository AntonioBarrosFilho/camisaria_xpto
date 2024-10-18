<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];

    // Consultar produto com categoria
    $query = "SELECT p.*, pc.id_categoria, GROUP_CONCAT(pt.id_tamanho, ':', pt.quantidade) AS tamanhos_disponiveis
              FROM produto p
              LEFT JOIN produto_categoria pc ON p.id_produto = pc.id_produto
              LEFT JOIN produto_tamanho pt ON p.id_produto = pt.id_produto
              WHERE p.id_produto = :id
              GROUP BY p.id_produto";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id_produto);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o produto existe
    if (!$produto) {
        die("Produto não encontrado.");
    }

    // Consultar categorias
    $categoriasQuery = "SELECT * FROM categoria";
    $categoriasStmt = $pdo->prepare($categoriasQuery);
    $categoriasStmt->execute();
    $categorias = $categoriasStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("ID do produto não fornecido.");
}

// Atualizar produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco']; // Adiciona a captura do preço
    $id_categoria = $_POST['id_categoria'];
    $tamanhos_disponiveis = $_POST['tamanhos']; // IDs dos tamanhos selecionados
    $quantidades = $_POST['quantidades']; // Quantidades dos tamanhos

    // Atualizar produto
    $updateQuery = "UPDATE produto SET nome = :nome, descricao = :descricao, preco = :preco, 
                    data_de_atualizacao = CURRENT_TIMESTAMP 
                    WHERE id_produto = :id"; // Corrigido para data_de_atualizacao
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':nome', $nome);
    $updateStmt->bindParam(':descricao', $descricao);
    $updateStmt->bindParam(':preco', $preco);
    $updateStmt->bindParam(':id', $id_produto);

    if ($updateStmt->execute()) {
        // Primeiro, remover tamanhos existentes
        $deleteTamanhosQuery = "DELETE FROM produto_tamanho WHERE id_produto = :id";
        $deleteTamanhosStmt = $pdo->prepare($deleteTamanhosQuery);
        $deleteTamanhosStmt->bindParam(':id', $id_produto);
        $deleteTamanhosStmt->execute();

        // Adicionar tamanhos selecionados com suas quantidades
        foreach ($tamanhos_disponiveis as $index => $id_tamanho) {
            $quantidade = $quantidades[$index]; // Quantidade correspondente
            $insertTamanhoQuery = "INSERT INTO produto_tamanho (id_produto, id_tamanho, quantidade) VALUES (:id_produto, :id_tamanho, :quantidade)";
            $insertTamanhoStmt = $pdo->prepare($insertTamanhoQuery);
            $insertTamanhoStmt->bindParam(':id_produto', $id_produto);
            $insertTamanhoStmt->bindParam(':id_tamanho', $id_tamanho);
            $insertTamanhoStmt->bindParam(':quantidade', $quantidade);
            $insertTamanhoStmt->execute();
        }

        // Atualizar categoria do produto
        $updateCategoriaQuery = "INSERT INTO produto_categoria (id_produto, id_categoria) VALUES (:id_produto, :id_categoria)
                                 ON DUPLICATE KEY UPDATE id_categoria = :id_categoria";
        $updateCategoriaStmt = $pdo->prepare($updateCategoriaQuery);
        $updateCategoriaStmt->bindParam(':id_produto', $id_produto);
        $updateCategoriaStmt->bindParam(':id_categoria', $id_categoria);
        $updateCategoriaStmt->execute();

        echo "<script>alert('Produto atualizado com sucesso!'); window.location.href='listar_produtos.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar o produto.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif; /* Mudando a fonte para Roboto */
            background-color: #f8f9fa; /* Cor de fundo */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Ocupa toda a altura da tela */
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

<div class="layout"> <!-- Alterado para .layout -->
    <h1>Editar Produto</h1>
    <form method="POST">
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>

        <label for="preco">Preço:</label>
        <input type="number" step="0.01" id="preco" name="preco" value="<?php echo htmlspecialchars($produto['preco']); ?>" required>
        
        <label for="id_categoria">Categoria:</label>
        <select id="id_categoria" name="id_categoria" required>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo $categoria['id_categoria']; ?>" <?php echo ($categoria['id_categoria'] == $produto['id_categoria']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($categoria['nome_categoria']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="tamanhos">Tamanhos:</label>
<div class="checkbox-container">
    <?php
    // Exibir tamanhos como checkboxes
    $sql = "SELECT id_tamanho, descricao_tamanho FROM tamanho";
    $stmt = $pdo->query($sql);
    // Array para armazenar tamanhos associados ao produto
    $tamanhosAssociados = [];

    // Consultar tamanhos associados ao produto
    if (isset($_GET['id'])) {
        $id_produto = $_GET['id'];
        $tamanhosAssociadosQuery = "SELECT id_tamanho, quantidade FROM produto_tamanho WHERE id_produto = :id";
        $tamanhosAssociadosStmt = $pdo->prepare($tamanhosAssociadosQuery);
        $tamanhosAssociadosStmt->bindParam(':id', $id_produto);
        $tamanhosAssociadosStmt->execute();
        $tamanhosAssociados = $tamanhosAssociadosStmt->fetchAll(PDO::FETCH_KEY_PAIR); // Associar id_tamanho a quantidade
    }

    // Exibir todos os tamanhos com checkboxes
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_tamanho = $row['id_tamanho'];
        $descricao_tamanho = htmlspecialchars($row['descricao_tamanho']);
        $checked = isset($tamanhosAssociados[$id_tamanho]) ? 'checked' : ''; // Verifica se o tamanho está associado
        $quantidade = isset($tamanhosAssociados[$id_tamanho]) ? $tamanhosAssociados[$id_tamanho] : 0; // Define quantidade

        echo "<label>
                <input type='checkbox' name='tamanhos[]' value='$id_tamanho' $checked>$descricao_tamanho
              </label>";
        echo "<input type='number' name='quantidades[]' class='quantidade' min='0' value='$quantidade'>"; // Campo de quantidade
    }
    ?>
</div>


        <button type="submit">Atualizar Produto</button>
        <button type="button" class="btn-blue" onclick="window.location.href='listar_produtos.php'">Cancelar</button>
    </form>
</div>

</body>
</html>
