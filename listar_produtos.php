<?php
include 'conexao.php';

// Consultar produtos e suas respectivas quantidades por tamanho e preço
$query = "SELECT p.id_produto, p.nome, p.data_criacao, p.descricao, p.preco,
                 GROUP_CONCAT(CONCAT(t.descricao_tamanho, ' (', pt.quantidade, ')') SEPARATOR ', ') AS tamanhos
          FROM produto p
          LEFT JOIN produto_tamanho pt ON p.id_produto = pt.id_produto
          LEFT JOIN tamanho t ON pt.id_tamanho = t.id_tamanho
          GROUP BY p.id_produto";

$stmt = $pdo->prepare($query);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Produtos</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 18px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .actions {
            display: flex;
            height: 38px;
        }

        button {
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .button-excluir {
            background-color: #dc3545;
        }

        .button-excluir:hover {
            background-color: #c82333;
        }

        .button-editar {
            background-color: #28a745;
        }

        .button-editar:hover {
            background-color: #218838;
        }

        .icon {
            font-size: 16px;
            color: white;
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

<h1>Lista de Produtos</h1>

<div class="container">
    <table>
        <thead>
            <tr>
                <th>Nome do Produto</th>
                <th>Data de Criação</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Tamanhos Disponíveis</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($produtos): ?>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($produto['data_criacao'])); ?></td>
                        <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
                        <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td> <!-- Exibindo o preço formatado -->
                        <td><?php echo htmlspecialchars($produto['tamanhos']); ?></td>
                        <td class="actions">
                            <button class="button-editar" onclick="window.location.href='editar_produto.php?id=<?php echo $produto['id_produto']; ?>'">
                                <i class="fas fa-cog icon"></i>
                            </button>
                            <button class="button-excluir" onclick="if(confirm('Tem certeza que deseja excluir?')) window.location.href='excluir_produto.php?id=<?php echo $produto['id_produto']; ?>'">Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;">Nenhum produto encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <button type="button" class="btn-blue" onclick="window.location.href='cadastrar_produto.php'">Voltar ao cadastro de Produto</button>
    </table>
</div>

</body>
</html>