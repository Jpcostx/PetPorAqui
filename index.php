<?php
session_start();
// Conecta com o banco de dados
require_once 'conexao.php';

// Busca apenas os pets que estão disponíveis para adoção
$stmt = $pdo->query("SELECT * FROM pet WHERE disponibilidade = 'Disponível'");
$petsBanco = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>PetPorAqui – Adote com Amor</title>
    <!-- Fontes e CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;800&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
   <!-- Navegação Atualizada com PHP -->
    <nav>
        <div class="logotipo" onclick="navegar('inicio')">🐾 PetPor<span>Aqui</span></div>
        <ul>
            <li><a class="link-nav ativo" onclick="navegar('inicio')">Início</a></li>
            <li><a class="link-nav" onclick="navegar('adotar')">Quero Adotar</a></li>
            <li><a class="link-nav" onclick="navegar('mapa')">Mapa</a></li>
            <li><a class="link-nav" onclick="navegar('chat')">Mensagens</a></li>
            
            <?php if(isset($_SESSION['usuario_nome'])): ?>
                <!-- Se estiver logado, mostra isso: -->
                <?php if($_SESSION['usuario_tipo'] === 'Administrador' || $_SESSION['usuario_tipo'] === 'ONG'): ?>
                    <li><a href="painel_pets.php" class="link-nav" style="color: var(--coral); font-weight: 800;">Painel Adm</a></li>
                <?php endif; ?>
                
                <li><span style="color: var(--coral); font-weight: 800; margin-left: 15px;">Olá, <?= explode(' ', $_SESSION['usuario_nome'])[0] ?>!</span></li>
                <li><a href="logout.php" class="btn btn-contorno" style="padding: 8px 20px; text-decoration: none;">Sair</a></li>
            <?php else: ?>
                <!-- Se NÃO estiver logado, mostra o botão Entrar normal: -->
                <li><button id="btn-nav-login" class="btn btn-principal" style="padding: 8px 20px;" onclick="abrirModalLogin()">Entrar</button></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Seção Início -->
    <main id="inicio" class="pagina ativo">
        <div class="destaque">
            <h1>Encontre seu novo melhor amigo pertinho de você</h1>
            <p>O PetPorAqui conecta animais que precisam de um lar com famílias amorosas na sua região. Adoção responsável, simples e transparente.</p>
            <button class="btn btn-principal" onclick="navegar('adotar')">Ver Pets Disponíveis</button>
        </div>

        <h2 style="text-align: center; margin-bottom: 20px;">Como Funciona?</h2>
        <div class="grade-recursos">
            <div class="cartao-recurso">
                <div class="icone-recurso">🔍</div>
                <h3>Busque</h3>
                <p>Encontre cães e gatos resgatados por ONGs e protetores da sua cidade.</p>
            </div>
            <div class="cartao-recurso">
                <div class="icone-recurso">💬</div>
                <h3>Converse</h3>
                <p>Entre em contato direto com os responsáveis para tirar dúvidas e agendar visitas.</p>
            </div>
            <div class="cartao-recurso">
                <div class="icone-recurso">🏡</div>
                <h3>Adote</h3>
                <p>Passe pelo processo de triagem e leve muito amor para a sua casa.</p>
            </div>
        </div>
    </main>

    <!-- Seção Adotar (AGORA DINÂMICA COM PHP) -->
    <main id="adotar" class="pagina">
        <h2 style="margin-bottom: 10px;">Pets esperando por você</h2>
        <p style="color: var(--opaco); margin-bottom: 30px;">Conheça os animais disponíveis para adoção responsável hoje.</p>
        
        <div class="grade-pets" id="recipiente-pets">
            <?php if (count($petsBanco) > 0): ?>
                <!-- Loop PHP para criar um cartão para cada pet vindo do banco -->
                <?php foreach ($petsBanco as $pet): ?>
                    <div class="cartao-pet">
                        <?php 
            // Se a coluna imagem não estiver vazia no banco, ele usa ela. Se estiver, usa a padrão.
                        $fotoPet = !empty($pet['imagem']) ? htmlspecialchars($pet['imagem']) : 'https://images.pexels.com/photos/1108099/pexels-photo-1108099.jpeg?auto=compress&cs=tinysrgb&w=500';
                            ?>
                            <img src="<?= $fotoPet ?>" alt="<?= htmlspecialchars($pet['nome']) ?>" class="foto-pet">
                            <div class="info-pet">
                            <h3><?= htmlspecialchars($pet['nome']) ?></h3>
                            <div class="etiquetas-pet">
                                <span class="etiqueta"><?= htmlspecialchars($pet['especie']) ?></span>
                                <span class="etiqueta"><?= htmlspecialchars($pet['localizacao']) ?></span>
                            </div>
                            <button class="btn btn-contorno" onclick="alert('Detalhes do pet: <?= htmlspecialchars($pet['nome']) ?>. A integração com o modal será feita a seguir!')">Ver Detalhes</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Mensagem caso não tenha nenhum pet disponível no banco -->
                <div style="grid-column: 1/-1; text-align: center; padding: 40px; background: var(--branco); border-radius: 12px;">
                    <h3 style="color: var(--opaco);">Nenhum pet disponível no momento. 🐾</h3>
                    <p>Volte mais tarde ou acesse o Painel Adm para cadastrar novos animais.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Seção Mapa -->
    <main id="mapa" class="pagina">
        <h2 style="margin-bottom: 10px;">Encontre pets perto de você</h2>
        <p style="color: var(--opaco); margin-bottom: 20px;">Navegue pelo mapa e clique no ícone do pet para ver detalhes e iniciar o processo de adoção.</p>
        <div class="caixa-mapa" id="caixa-mapa"></div>
    </main>

    <!-- Seção Chat -->
    <main id="chat" class="pagina">
        <h2 style="margin-bottom: 20px;">Suas Conversas</h2>
        <div class="layout-chat">
            <div class="barra-lateral-chat" id="barra-lateral-chat">
                <div class="contato-chat ativo">
                    <h4>ONG Amigos de Pata</h4>
                    <p>Sobre: Bob - Ele se adapta bem...</p>
                </div>
                <div class="contato-chat">
                    <h4>Dona Maria</h4>
                    <p>Sobre: Princesa - Boa tarde! A...</p>
                </div>
            </div>
            
            <div class="janela-chat">
                <div class="cabecalho-chat">
                    <h3 id="nome-tutor-chat">ONG Amigos de Pata</h3>
                    <span class="etiqueta" id="etiqueta-pet-chat">🐾 Bob</span>
                </div>
                
                <div class="mensagens-chat" id="mensagens-chat">
                    <p style="text-align: center; color: var(--opaco); margin-bottom: 10px; font-size: 0.85rem;">Ontem</p>
                    <div class="msg recebida">Olá! Recebemos a notificação de que você se interessou pelo Bob. 🐕</div>
                    <div class="msg enviada">Oi! Sim, achei ele lindo. Ele se dá bem vivendo em apartamento?</div>
                    <div class="msg recebida">Ele se adapta super bem, mas como tem bastante energia, precisaria de passeios diários de pelo menos 40 minutos.</div>
                    <div class="msg enviada">Perfeito! Eu trabalho de casa, então tenho bastante flexibilidade de horários.</div>
                </div>
                
                <div class="area-digitacao-chat">
                    <input type="text" id="entrada-chat" placeholder="Escreva sua mensagem aqui..." onkeypress="lidarComTecla(event)">
                    <button onclick="enviarMensagem()">➤</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Modais Originais -->
    <div class="sobreposicao-modal" id="modalLogin">
        <div class="conteudo-modal" style="max-width: 400px; text-align: center;">
            <button class="fechar-modal" onclick="fecharModalLogin()">×</button>
            <h2 style="margin-bottom: 20px; font-family: 'Baloo 2', cursive; color: var(--coral);">🐾 Bem-vindo de volta!</h2>
            
            <form action="login.php" method="POST">
                <div class="grupo-entrada">
                    <label>E-mail</label>
                    <input type="email" name="email" placeholder="seu@email.com" required>
                </div>
                
                <div class="grupo-entrada" style="margin-bottom: 25px;">
                    <label>Senha</label>
                    <input type="password" name="senha" placeholder="••••••••" required>
                </div>
                
               <button type="submit" class="btn btn-principal" style="width: 100%; margin-bottom: 10px;">Entrar</button>
        </form>
        
        <p style="font-size: 0.85rem; color: var(--opaco); margin-bottom: 5px;">Novo por aqui? <a href="cadastro_usuario.php" style="color: var(--coral); font-weight: bold;">Crie sua conta</a></p>
        
        <p style="font-size: 0.85rem; color: var(--opaco);">É uma instituição? <a href="cadastro_ong.php" style="color: var(--coral);">Cadastre sua ONG</a></p>
    </div>
</div>

    <footer>
        <h2 class="logotipo" style="margin-bottom: 10px;">🐾 PetPor<span>Aqui</span></h2>
        <p>PetPorAqui&copy; 2026</p>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="script.js"></script>
</body>
</html>