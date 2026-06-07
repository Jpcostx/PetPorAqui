<?php
// Conecta com o banco de dados
require_once 'conexao.php';

// Verifica se é edição ou cadastro novo
$id = $_GET['id'] ?? null;

// BUSCA AS ONGS: Puxa a lista de ONGs do banco para montar as opções
$stmtOngs = $pdo->query("SELECT cnpj, nome FROM ong");
$listaOngs = $stmtOngs->fetchAll();

// Array padrão com o campo cnpj_ong adicionado (nulo por padrão)
$pet = ['nome' => '', 'especie' => '', 'localizacao' => '', 'disponibilidade' => 'Disponível', 'id_usuario' => '2', 'imagem' => '', 'cnpj_ong' => null];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM pet WHERE id = ?");
    $stmt->execute([$id]);
    $pet = $stmt->fetch();
}

// Processa o salvamento dos dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $especie = $_POST['especie'];
    $localizacao = $_POST['localizacao'];
    $disponibilidade = $_POST['disponibilidade'];
    $id_usuario = $_POST['id_usuario']; 
    $imagem = $_POST['imagem'];
    
    // Captura o CNPJ. Se estiver vazio (''), transforma em nulo (null) para o banco aceitar
    $cnpj_ong = !empty($_POST['cnpj_ong']) ? $_POST['cnpj_ong'] : null;

    if ($id) {
        // UPDATE atualizado com cnpj_ong
        $sql = "UPDATE pet SET nome=?, especie=?, localizacao=?, disponibilidade=?, id_usuario=?, imagem=?, cnpj_ong=? WHERE id=?";
        $pdo->prepare($sql)->execute([$nome, $especie, $localizacao, $disponibilidade, $id_usuario, $imagem, $cnpj_ong, $id]);
    } else {
        // INSERT atualizado com cnpj_ong
        $sql = "INSERT INTO pet (nome, especie, localizacao, disponibilidade, id_usuario, imagem, cnpj_ong) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$nome, $especie, $localizacao, $disponibilidade, $id_usuario, $imagem, $cnpj_ong]);
    }
    
    header("Location: painel_pets.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? 'Editar' : 'Cadastrar' ?> Pet</title>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;800&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <div class="logotipo">🐾 PetPor<span>Aqui</span> <small style="font-size:1rem; color:var(--opaco);">[Painel Adm]</small></div>
        <ul><li><a href="painel_pets.php" class="link-nav">Voltar para o Painel</a></li></ul>
    </nav>

    <main class="pagina ativo" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 70vh;">
        <h2 style="margin-bottom: 20px;"><?= $id ? 'Editar Dados do Pet' : 'Cadastrar Novo Pet' ?></h2>
        
        <form method="POST" style="background: var(--branco); padding: 30px; border-radius: 12px; width: 100%; max-width: 500px; box-shadow: var(--sombra);">
            
            <div class="grupo-entrada">
                <label>Nome do Pet:</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($pet['nome']) ?>" required>
            </div>
            
            <div class="grupo-entrada">
                <label>Espécie:</label>
                <input type="text" name="especie" value="<?= htmlspecialchars($pet['especie']) ?>" required>
            </div>
            
            <div class="grupo-entrada">
                <label>Localização (Bairro - Cidade):</label>
                <input type="text" name="localizacao" value="<?= htmlspecialchars($pet['localizacao']) ?>" required>
            </div>
            
            <div class="grupo-entrada">
                <label>Link da Foto (URL):</label>
                <input type="url" name="imagem" value="<?= htmlspecialchars($pet['imagem'] ?? '') ?>" placeholder="Cole o link da imagem aqui...">
            </div>

            <div class="grupo-entrada">
                <label>ONG Responsável (Opcional):</label>
                <select name="cnpj_ong" style="width: 100%; padding: 12px; border: 2px solid #E8E2DC; border-radius: 8px; font-family: inherit; outline: none; margin-top: 5px;">
                    <option value="">Nenhuma (Tutor Particular)</option>
                    
                    <?php foreach ($listaOngs as $ong): ?>
                        <option value="<?= htmlspecialchars($ong['cnpj']) ?>" <?= ($pet['cnpj_ong'] === $ong['cnpj']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ong['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="grupo-entrada">
                <label>Disponibilidade:</label>
                <select name="disponibilidade" style="width: 100%; padding: 12px; border: 2px solid #E8E2DC; border-radius: 8px; font-family: inherit; outline: none; margin-top: 5px;">
                    <option value="Disponível" <?= $pet['disponibilidade'] == 'Disponível' ? 'selected' : '' ?>>Disponível</option>
                    <option value="Adotado" <?= $pet['disponibilidade'] == 'Adotado' ? 'selected' : '' ?>>Adotado</option>
                </select>
            </div>
            
            <div class="grupo-entrada" style="display: none;">
                <input type="number" name="id_usuario" value="<?= htmlspecialchars($pet['id_usuario']) ?>" required>
            </div>
            
            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn btn-principal" style="flex: 1;">Salvar Pet</button>
            </div>
        </form>
    </main>
</body>
</html>