<?php
session_start(); // Inicia a sessão (memória do usuário logado)
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Busca o usuário no banco que tenha esse email e essa senha
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE Email = ? AND senha = ?");
    $stmt->execute([$email, $senha]);
    $user = $stmt->fetch();

    if ($user) {
        // Se achou, salva os dados dele na Sessão
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nome'] = $user['Nome'];
        $_SESSION['usuario_tipo'] = $user['tipo']; // Pode ser 'Administrador', 'ONG' ou 'Adotante'

        // Redireciona dependendo de quem é:
        if ($user['tipo'] === 'Administrador' || $user['tipo'] === 'ONG') {
            header("Location: painel_pets.php"); // Vai gerenciar os pets
        } else {
            header("Location: index.php"); // Cliente normal volta pra vitrine
        }
        exit;
    } else {
        // Se errou a senha
        echo "<script>alert('E-mail ou senha incorretos!'); window.location.href='index.php';</script>";
    }
}
?>