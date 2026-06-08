<?php
    session_start();
// Conecta com o banco de dados
require_once 'conexao.php';

// Busca os pets e faz um JOIN com a tabela ONG para pegar o nome da instituição
$stmt = $pdo->query("
    SELECT pet.*, ong.nome AS nome_ong 
    FROM pet 
    LEFT JOIN ong ON pet.cnpj_ong = ong.cnpj 
    WHERE pet.disponibilidade = 'Disponível'
");
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
                <?php if($_SESSION['usuario_tipo'] === 'Administrador' || $_SESSION['usuario_tipo'] === 'ONG'): ?>
                    <li><a href="painel_pets.php" class="link-nav" style="color: var(--coral); font-weight: 800;">Painel Adm</a></li>
                <?php else: ?>
                    <li><a href="#" class="link-nav" style="display: none;">Painel Adm</a></li>
                <?php endif; ?>
                
                <li><span style="color: var(--coral); font-weight: 800; margin-left: 15px;">Olá, <?= explode(' ', $_SESSION['usuario_nome'])[0] ?>!</span></li>
                <li><a href="logout.php" class="btn btn-contorno" style="padding: 8px 20px; text-decoration: none;">Sair</a></li>
                
                <li><button id="btn-nav-login" style="display: none;">Entrar</button></li>
                
            <?php else: ?>
                <li><a href="#" class="link-nav" style="display: none;">Painel Adm</a></li>
                
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
                      		<?php 
                                $nomePetSeguro = addslashes(htmlspecialchars($pet['nome']));
                                // Verifica se tem ONG. Se não tiver, escreve "Responsável pelo(a) [Nome do Pet]"
                                $nomeDaOng = !empty($pet['nome_ong']) ? addslashes(htmlspecialchars($pet['nome_ong'])) : "Responsável pelo(a) " . $nomePetSeguro;
                            ?>
                            <button class="btn btn-contorno" onclick="iniciarAdocao('<?= $nomePetSeguro ?>', '<?= $nomeDaOng ?>')">Quero Adotar</button>
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
        <?php if(isset($_SESSION['usuario_nome'])): ?>
            <div class="container-chat">
                
                <div class="barra-lateral" id="barra-lateral-chat">
                    <div class="contato-chat ativo" onclick="abrirConversa(this, 'ONG Patas Felizes', 'Rex')">
                        <h4>ONG Patas Felizes</h4>
                        <p>Sobre: Rex - Tudo bem, aguardo sua visita!</p>
                    </div>
                    <div class="contato-chat" onclick="abrirConversa(this, 'Tutor Particular', 'Mimi')">
                        <h4>Tutor Particular</h4>
                        <p>Sobre: Mimi - Olá! A Mimi adora sachê...</p>
                    </div>
                </div>
                
                <div class="area-mensagens">
                    <div class="cabecalho-chat">
                        <h3 id="nome-tutor-chat">ONG Patas Felizes</h3>
                        <span class="etiqueta" id="etiqueta-pet-chat">🐾 Rex</span>
                    </div>
                    <div class="mensagens" id="mensagens-chat">
                        <p style="text-align: center; color: var(--opaco); margin-bottom: 10px; font-size: 0.85rem;">Ontem</p>
                        
                        <?php $primeiroNome = explode(' ', trim($_SESSION['usuario_nome']))[0]; ?>
                        
                        <div class="msg enviada">Olá! Tenho interesse em adotar o Rex! Meu nome é <?= htmlspecialchars($primeiroNome) ?>.</div>
                        <div class="msg recebida">Olá, <?= htmlspecialchars($primeiroNome) ?>! Tudo bem, aguardo sua visita!</div>
                    </div>
                   <div class="campo-digitar">
                        <input type="text" id="input-nova-mensagem" placeholder="Digite sua mensagem..." onkeypress="verificarEnter(event)">
                        <button class="btn btn-principal" onclick="enviarMensagem()">Enviar</button>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div style="text-align: center; padding: 100px 20px; background: var(--branco); border-radius: 12px; margin-top: 20px;">
                <h2 style="color: var(--coral); font-family: 'Baloo 2', cursive; margin-bottom: 15px;">Acesse suas Mensagens</h2>
                <p style="color: var(--opaco); margin-bottom: 25px; font-size: 1.1rem;">Você precisa estar logado na sua conta para ver o andamento das suas adoções.</p>
                <button class="btn btn-principal" onclick="abrirModalLogin()">Fazer Login</button>
            </div>
        <?php endif; ?> </main>

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
    <div class="sobreposicao-modal" id="modalTermo" style="display: none;">
        <div class="conteudo-modal" style="max-width: 500px; text-align: center;">
            <button class="fechar-modal" onclick="fecharModalTermo()">×</button>
            <h2 style="margin-bottom: 20px; font-family: 'Baloo 2', cursive; color: var(--coral);">🐾 Termo de Adoção</h2>
            
            <div style="background: var(--creme); padding: 15px; border-radius: 8px; text-align: left; font-size: 0.9rem; margin-bottom: 20px; max-height: 150px; overflow-y: auto;">
                <p>Ao confirmar o interesse neste animal, eu me comprometo a:</p>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li>Garantir alimentação de qualidade, água fresca e abrigo seguro.</li>
                    <li>Fornecer cuidados veterinários sempre que necessário.</li>
                    <li>Não submeter o animal a correntes, abandono ou maus-tratos.</li>
                    <li>Oferecer amor, paciência e tempo para adaptação.</li>
                </ul>
            </div>
            
            <label style="display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 0.9rem; margin-bottom: 20px; cursor: pointer; text-align: left;">
                <input type="checkbox" id="checkTermo" style="width: 20px; height: 20px; cursor: pointer;">
                <span>Li e aceito os termos de responsabilidade para adotar o(a) <strong id="nomePetTermo"></strong>.</span>
            </label>
            
            <button class="btn btn-principal" style="width: 100%;" onclick="aceitarTermo()">Assinar e Conversar</button>
        </div>
    </div>

   <!-- NOVO: Modal do Termo de Adoção -->
    <div class="sobreposicao-modal" id="modalTermo" style="display: none; align-items: center; justify-content: center;">
        <div class="conteudo-modal" style="max-width: 500px; text-align: center;">
            <button class="fechar-modal" onclick="fecharModalTermo()">×</button>
            <h2 style="margin-bottom: 20px; font-family: 'Baloo 2', cursive; color: var(--coral);">🐾 Termo de Adoção</h2>
            
            <div style="background: var(--creme); padding: 15px; border-radius: 8px; text-align: left; font-size: 0.9rem; margin-bottom: 20px; max-height: 150px; overflow-y: auto;">
                <p>Ao confirmar o interesse neste animal, eu me comprometo a:</p>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li>Garantir alimentação de qualidade, água fresca e abrigo seguro.</li>
                    <li>Fornecer cuidados veterinários sempre que necessário.</li>
                    <li>Não submeter o animal a correntes, abandono ou maus-tratos.</li>
                    <li>Oferecer amor, paciência e tempo para adaptação.</li>
                </ul>
            </div>
            
            <label style="display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 0.9rem; margin-bottom: 20px; cursor: pointer; text-align: left;">
                <input type="checkbox" id="checkTermo" style="width: 20px; height: 20px; cursor: pointer;">
                <span>Li e aceito os termos para adotar o(a) <strong id="nomePetTermo"></strong>.</span>
            </label>
            
            <button class="btn btn-principal" style="width: 100%;" onclick="aceitarTermo()">Assinar e Conversar</button>
        </div>
    </div>

    <!-- Rodapé -->
    <footer>
        <h2 class="logotipo" style="margin-bottom: 10px;">🐾 PetPor<span>Aqui</span></h2>
        <p>PetPorAqui&copy; 2026</p>
    </footer>

    <!-- Scripts Base -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="script.js"></script>

<script>
        const statusLoginConfirmado = <?= isset($_SESSION['usuario_nome']) ? 'true' : 'false' ?>;
        var petEscolhidoParaAdocao = ''; 
        var ongEscolhidaParaAdocao = ''; // Nova variável para guardar a ONG

        // Agora a função recebe 2 parâmetros: nome do pet e nome da ONG
        function iniciarAdocao(nomePet, nomeOng) {
            if (!statusLoginConfirmado) {
                alert("Você precisa estar logado na sua conta para adotar um pet!");
                abrirModalLogin(); 
                return;
            }
            
            petEscolhidoParaAdocao = nomePet;
            ongEscolhidaParaAdocao = nomeOng; // Guarda o nome para usar depois
            
            const modal = document.getElementById('modalTermo');
            
            document.getElementById('nomePetTermo').innerText = nomePet;
            document.getElementById('checkTermo').checked = false; 
            
            modal.style.display = 'flex';
            modal.style.opacity = '1';
            modal.style.visibility = 'visible';
            modal.style.pointerEvents = 'auto';
            modal.style.zIndex = '999999';
            modal.classList.add('ativo'); 
        }

        function fecharModalTermo() {
            const modal = document.getElementById('modalTermo');
            modal.style.display = 'none';
            modal.style.opacity = '0';
            modal.style.visibility = 'hidden';
            modal.classList.remove('ativo');
        }

        function aceitarTermo() {
            const checkbox = document.getElementById('checkTermo');
            
            if (!checkbox.checked) {
                alert("Por favor, marque a caixa aceitando os termos para prosseguir com a adoção.");
                return;
            }

            fecharModalTermo();
            // Manda o pet e a ONG para a criação da conversa
            criarNovaConversa(petEscolhidoParaAdocao, ongEscolhidaParaAdocao);
            navegar('chat');
        }

        // Pega o nome do usuário logado usando PHP direto no JS
        const primeiroNomeUsuario = "<?= isset($_SESSION['usuario_nome']) ? explode(' ', $_SESSION['usuario_nome'])[0] : '' ?>";

        function abrirConversa(elemento, nomeContato, nomePet) {
            document.querySelectorAll('.contato-chat').forEach(el => el.classList.remove('ativo'));
            elemento.classList.add('ativo');

            document.getElementById('nome-tutor-chat').innerText = nomeContato;
            document.getElementById('etiqueta-pet-chat').innerText = `🐾 ${nomePet}`;

            const mensagensChat = document.getElementById('mensagens-chat');
            mensagensChat.innerHTML = `
                <p style="text-align: center; color: var(--opaco); margin-bottom: 10px; font-size: 0.85rem;">Ontem</p>
                <div class="msg recebida">Olá, ${primeiroNomeUsuario}! Como posso ajudar com o processo de adoção do(a) ${nomePet}?</div>
                <div class="msg enviada">Oi! Gostaria de saber se o(a) ${nomePet} se dá bem com outros animais.</div>
                <div class="msg recebida">Sim! É super sociável e adora brincar. Podemos agendar uma visita?</div>
            `;
        }

        function criarNovaConversa(nomePet, nomeOng) {
            document.querySelectorAll('.contato-chat').forEach(el => el.classList.remove('ativo'));

            const barraLateral = document.getElementById('barra-lateral-chat');
            if(!barraLateral) return; // Trava de segurança caso não esteja logado

            const novoContato = document.createElement('div');
            novoContato.className = 'contato-chat ativo';
            novoContato.setAttribute('onclick', `abrirConversa(this, '${nomeOng}', '${nomePet}')`);
            
            novoContato.innerHTML = `
                <h4>${nomeOng}</h4>
                <p>Sobre: ${nomePet} - Olá! Vi que você aceitou...</p>
            `;
            barraLateral.prepend(novoContato); 

            document.getElementById('nome-tutor-chat').innerText = nomeOng;
            document.getElementById('etiqueta-pet-chat').innerText = `🐾 ${nomePet}`;

            const mensagensChat = document.getElementById('mensagens-chat');
            mensagensChat.innerHTML = `
                <p style="text-align: center; color: var(--opaco); margin-bottom: 10px; font-size: 0.85rem;">Agora mesmo</p>
                <div class="msg enviada">Olá! Sou o(a) ${primeiroNomeUsuario}. Acabei de assinar o termo de responsabilidade e tenho muito interesse em dar um lar para o(a) ${nomePet}. Podemos conversar?</div>
                <div class="msg recebida">Olá, ${primeiroNomeUsuario}! Ficamos extremamente felizes com o seu interesse no ${nomePet}! 🎉 Me conta um pouco sobre como é a sua rotina e a sua casa!</div>
            `;
        }
  
        function enviarMensagem() {
            const campoInput = document.getElementById('input-nova-mensagem');
            const textoMensagem = campoInput.value.trim(); // .trim() remove espaços vazios

            // Só envia se tiver algum texto digitado
            if (textoMensagem !== '') {
                const areaMensagens = document.getElementById('mensagens-chat');
                
                // Cria a caixinha da nova mensagem
                const novaCaixaMensagem = document.createElement('div');
                novaCaixaMensagem.className = 'msg enviada';
                novaCaixaMensagem.innerText = textoMensagem; 
                
                // Joga a mensagem para dentro da tela de chat
                areaMensagens.appendChild(novaCaixaMensagem);
                
                // Limpa o campo para você digitar a próxima
                campoInput.value = '';
                
                // Faz a tela rolar automaticamente para baixo para ver a mensagem nova
                areaMensagens.scrollTop = areaMensagens.scrollHeight;
            }
        }

        function verificarEnter(event) {
            // Se a tecla apertada for o "Enter", ele chama a função de enviar
            if (event.key === 'Enter') {
                enviarMensagem();
            }
        }
    </script>
</body>
</html>
