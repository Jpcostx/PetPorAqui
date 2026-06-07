<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cnpj = $_POST['cnpj'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        // 1. Salva na tabela ONG
        $stmt1 = $pdo->prepare("INSERT INTO ong (cnpj, nome) VALUES (?, ?)");
        $stmt1->execute([$cnpj, $nome]);

        // 2. Salva na tabela Usuario para ela poder fazer login
        $stmt2 = $pdo->prepare("INSERT INTO usuario (Nome, Email, tipo, senha) VALUES (?, ?, 'ONG', ?)");
        $stmt2->execute([$nome, $email, $senha]);

        echo "<script>alert('ONG cadastrada com sucesso! Faça seu login.'); window.location.href='index.php';</script>";
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Erro ao cadastrar. Verifique se o CNPJ ou Email já existem.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Instituição - PetPorAqui</title>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;800&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="style.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background: var(--creme);">
    
    <form method="POST" style="background: var(--branco); padding: 40px; border-radius: 12px; width: 100%; max-width: 400px; box-shadow: var(--sombra);">
        <h2 style="text-align: center; color: var(--coral); margin-bottom: 20px;">🐾 Cadastro de ONG</h2>
        
        <div class="grupo-entrada">
            <label>Nome da Instituição:</label>
            <input type="text" name="nome" required>
        </div>
        <div class="grupo-entrada">
            <label>CNPJ:</label>
            <input type="text" name="cnpj" placeholder="00.000.000/0000-00" required>
        </div>
        <div class="grupo-entrada">
            <label>E-mail de Acesso:</label>
            <input type="email" name="email" required>
        </div>
        <div class="grupo-entrada">
            <label>Senha:</label>
            <input type="password" name="senha" required>
        </div>
        
        <button type="submit" class="btn btn-principal" style="width: 100%; margin-top: 15px;">Cadastrar ONG</button>
        <p style="text-align: center; margin-top: 15px; font-size: 0.9rem;"><a href="index.php" style="color: var(--opaco);">Voltar para o Início</a></p>
    </form>

</body>
</html>