<?php
// Conecta com o banco de dados
require_once 'conexao.php';

// LÓGICA DE EXCLUSÃO (DELETE)
// Verifica se a URL tem um comando de exclusão
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $stmt = $pdo->prepare("DELETE FROM pet WHERE id = ?");
    $stmt->execute([$id]);
    
    // Atualiza a página para remover o pet da tabela visualmente
    header("Location: painel_pets.php");
    exit;
}

// LÓGICA DE LEITURA (READ)
// Busca todos os pets cadastrados e junta com a tabela de usuários para mostrar o nome do tutor
$stmt = $pdo->query("SELECT p.id, p.nome, p.especie, p.disponibilidade, u.Nome as tutor 
                     FROM pet p 
                     JOIN usuario u ON p.id_usuario = u.id");
$pets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Pets - PetPorAqui</title>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;800&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav>
        <div class="logotipo">🐾 PetPor<span>Aqui</span> <small style="font-size:1rem; color:var(--opaco);">[Painel Adm]</small></div>
        <ul>
            <li><a href="index.php" class="link-nav">Voltar para o Site Principal</a></li>
        </ul>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="pagina ativo" style="min-height: 70vh;">
        <h2 style="margin-bottom: 10px;">Painel de Controle de Adoções</h2>
        <p style="color: var(--opaco); margin-bottom: 30px;">Gerencie as informações dos animais cadastrados no sistema.</p>
        
        <!-- Botão para Cadastrar Novo Pet (Redireciona para o form_pet.php sem ID) -->
        <a href="form_pet.php" class="btn btn-principal" style="margin-bottom: 20px; text-decoration: none;">+ Cadastrar Novo Pet</a>
        
        <!-- Tabela Listando os Pets -->
        <div style="overflow-x: auto;">
            <table width="100%" style="border-collapse: collapse; margin-top: 10px; background: var(--branco); border-radius: 12px; overflow: hidden; box-shadow: var(--sombra); min-width: 800px;">
                <thead>
                    <tr style="background: var(--texto); color: var(--branco); text-align: left;">
                        <th style="padding: 15px;">ID</th>
                        <th style="padding: 15px;">Nome</th>
                        <th style="padding: 15px;">Espécie</th>
                        <th style="padding: 15px;">Tutor Cadastrante</th>
                        <th style="padding: 15px;">Status</th>
                        <th style="padding: 15px; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($pets) > 0): ?>
                        <?php foreach ($pets as $pet): ?>
                        <tr style="border-bottom: 1px solid #E8E2DC;">
                            <td style="padding: 15px;"><?= $pet['id'] ?></td>
                            <td style="padding: 15px; font-weight: 700;"><?= htmlspecialchars($pet['nome']) ?></td>
                            <td style="padding: 15px;"><span class="etiqueta"><?= htmlspecialchars($pet['especie']) ?></span></td>
                            <td style="padding: 15px; color: var(--opaco);"><?= htmlspecialchars($pet['tutor']) ?></td>
                            <td style="padding: 15px;">
                                <span class="etiqueta" style="background: <?= $pet['disponibilidade'] == 'Disponível' ? '#e2f7f1' : '#fff1ed' ?>; color: <?= $pet['disponibilidade'] == 'Disponível' ? 'var(--verde-agua)' : 'var(--coral)' ?>;">
                                    <?= htmlspecialchars($pet['disponibilidade']) ?>
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <!-- Botão de Editar (Passa o ID pela URL para o form_pet.php) -->
                                <a href="form_pet.php?id=<?= $pet['id'] ?>" class="btn btn-contorno" style="padding: 5px 15px; font-size: 0.85rem; text-decoration: none;">Editar</a>
                                
                                <!-- Botão de Excluir (Passa o comando de excluir pela URL na própria página) -->
                                <a href="painel_pets.php?excluir=<?= $pet['id'] ?>" class="btn" style="padding: 5px 15px; font-size: 0.85rem; background: #ffeded; color: #d9381e; text-decoration: none; margin-left: 5px;" onclick="return confirm('Tem certeza que deseja remover este registro permanentemente?')">Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 20px; text-align: center; color: var(--opaco);">Nenhum pet cadastrado no momento.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <h2 class="logotipo" style="margin-bottom: 10px;">🐾 PetPor<span>Aqui</span></h2>
        <p>PetPorAqui&copy; 2026</p>
    </footer>

</body>
</html>
