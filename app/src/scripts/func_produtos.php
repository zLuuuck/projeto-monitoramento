<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("conectarBanco.php");

function mensagem(string $mensagem, string $tipo) {
    $classe = match (strtoupper($tipo)) {
        'SUCCESS' => 'mensagem-sucesso',
        'ERROR' => 'mensagem-erro',
        default => 'mensagem-info'
    };
    return "<div class='$classe'>$mensagem</div>";
}

function adicionarProduto($nome, $modelo, $cor, $quantidade, $imagem = null) {
    $db = conectarBanco();
    
    try {
        // Verifica se modelo já existe
        $stmt = $db->prepare("SELECT id FROM produtos WHERE modelo = :modelo");
        $stmt->bindValue(':modelo', $modelo);
        $stmt->execute();
        if ($stmt->fetch()) {
            return "Já existe um produto com este modelo.";
        }

        $stmt = $db->prepare("INSERT INTO produtos (nome, modelo, cor, quantidade, imagem) VALUES (:nome, :modelo, :cor, :quantidade, :imagem)");
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':modelo', $modelo);
        $stmt->bindValue(':cor', $cor);
        $stmt->bindValue(':quantidade', $quantidade);
        $stmt->bindValue(':imagem', $imagem);
        $stmt->execute();
        
        return true;
    } catch (PDOException $e) {
        return "Erro ao adicionar produto: " . $e->getMessage();
    }
}

function listarProdutos() {
    $db = conectarBanco();
    $stmt = $db->query("SELECT * FROM produtos ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarProduto($id) {
    $db = conectarBanco();
    $stmt = $db->prepare("SELECT * FROM produtos WHERE id = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizarProduto($id, $nome, $modelo, $cor, $quantidade, $imagem = null) {
    $db = conectarBanco();
    
    try {
        // Verifica se outro produto tem o mesmo modelo
        $stmt = $db->prepare("SELECT id FROM produtos WHERE modelo = :modelo AND id != :id");
        $stmt->bindValue(':modelo', $modelo);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        if ($stmt->fetch()) {
            return "Já existe outro produto com este modelo.";
        }

        if ($imagem) {
            $stmt = $db->prepare("UPDATE produtos SET nome = :nome, modelo = :modelo, cor = :cor, quantidade = :quantidade, imagem = :imagem WHERE id = :id");
            $stmt->bindValue(':imagem', $imagem);
        } else {
            $stmt = $db->prepare("UPDATE produtos SET nome = :nome, modelo = :modelo, cor = :cor, quantidade = :quantidade WHERE id = :id");
        }
        
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':modelo', $modelo);
        $stmt->bindValue(':cor', $cor);
        $stmt->bindValue(':quantidade', $quantidade);
        $stmt->execute();
        
        return true;
    } catch (PDOException $e) {
        return "Erro ao atualizar produto: " . $e->getMessage();
    }
}

function excluirProduto($id) {
    $db = conectarBanco();
    try {
        $stmt = $db->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return "Erro ao excluir produto: " . $e->getMessage();
    }
}
?>