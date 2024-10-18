<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];

    // Verificar se o produto existe antes de excluir
    $checkQuery = "SELECT * FROM produto WHERE id_produto = :id";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->bindParam(':id', $id_produto);
    $checkStmt->execute();
    $produto = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        // Excluir tamanhos associados ao produto
        $deleteTamanhosQuery = "DELETE FROM produto_tamanho WHERE id_produto = :id";
        $deleteTamanhosStmt = $pdo->prepare($deleteTamanhosQuery);
        $deleteTamanhosStmt->bindParam(':id', $id_produto);
        $deleteTamanhosStmt->execute();

        // Excluir categorias associadas ao produto
        $deleteCategoriasQuery = "DELETE FROM produto_categoria WHERE id_produto = :id";
        $deleteCategoriasStmt = $pdo->prepare($deleteCategoriasQuery);
        $deleteCategoriasStmt->bindParam(':id', $id_produto);
        $deleteCategoriasStmt->execute();

        // Excluir o produto
        $deleteQuery = "DELETE FROM produto WHERE id_produto = :id";
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->bindParam(':id', $id_produto);

        if ($deleteStmt->execute()) {
            echo "<script>alert('Produto excluído com sucesso!'); window.location.href='listar_produtos.php';</script>";
        } else {
            echo "<script>alert('Erro ao excluir o produto.'); window.location.href='listar_produtos.php';</script>";
        }
    } else {
        echo "<script>alert('Produto não encontrado.'); window.location.href='listar_produtos.php';</script>";
    }
} else {
    echo "<script>alert('ID do produto não fornecido.'); window.location.href='listar_produtos.php';</script>";
}
?>
