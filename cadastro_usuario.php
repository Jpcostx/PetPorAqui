<?php
// Conecta com o banco de dados
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo']; // Adotante ou Administrador

    try {
        // Verifica se o e-mail já existe no banco antes de cadastrar
        $stmt_verifica = $pdo->prepare("SELECT id FROM usuario WHERE Email = ?");
        $stmt_verifica->execute([$email]);
        
        if ($stmt_verifica->fetch()) {
            echo "<script>alert('Este e-mail já está cadastrado! Tente fazer login.');</script>";
        } else {
            // Se o e-mail for novo, salva o usuário no banco
            $sql = "INSERT INTO usuario (Nome, Email, tipo, senha) VALUES (?, ?, ?, ?)";
            $pdo->prepare($sql)->execute([$nome, $email, $tipo, $senha]);
            
            echo "<script>alert('Conta criada com sucesso! Agora você já pode fazer login.'); window.location.href='index.php';</script>";
            exit;
        }
    } catch (Exception $e) {
        echo "<script>alert('Ocorreu um erro ao criar a conta.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta - PetPorAqui</title>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;800&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="style.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background: var(--creme);">
    
    <form method="POST" style="background: var(--branco); padding: 40px; border-radius: 12px; width: 100%; max-width: 400px; box-shadow: var(--sombra);">
        <h2 style="text-align: center; color: var(--coral); margin-bottom: 20px;">🐾 Criar Nova Conta</h2>
        
        <div class="grupo-entrada">
            <label>Seu Nome Completo:</label>
            <input type="text" name="nome" placeholder="Digite seu nome" required>
        </div>
        
        <div class="grupo-entrada">
            <label>Seu E-mail:</label>
            <input type="email" name="email" placeholder="seu@email.com" required>
        </div>
        
        <div class="grupo-entrada">
            <label>Crie uma Senha:</label>
            <input type="password" name="senha" placeholder="••••••••" required>
        </div>

        <div class="grupo-entrada">
            <label>Tipo de Conta:</label>
            <select name="tipo" style="width: 100%; padding: 12px; border: 2px solid #E8E2DC; border-radius: 8px; font-family: inherit; outline: none; margin-top: 5px;">
                <option value="Adotante">Quero apenas Adotar (Adotante)</option>
                <option value="Administrador">Sou da Equipe (Administrador)</option>
            </select>
            <small style="color: var(--opaco); font-size: 0.8rem;"></small>
        </div>
        
        <button type="submit" class="btn btn-principal" style="width: 100%; margin-top: 15px;">Finalizar Cadastro</button>
        <p style="text-align: center; margin-top: 15px; font-size: 0.9rem;"><a href="index.php" style="color: var(--opaco);">Voltar para o Início</a></p>
    </form>

</body>
</html>