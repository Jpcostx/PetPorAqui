// Variáveis Globais
let petSelecionado = null;
let estaLogado = false;
let meuMapa = null;

// DADOS DOS PETS
const dadosPets = [
    { id: 1, nome: 'Thor', icone: '🐕', lat: -22.9068, lng: -43.1729, especie: 'Cão', idade: '3 anos', porte: 'Médio', img: 'https://images.pexels.com/photos/1108099/pexels-photo-1108099.jpeg?auto=compress&cs=tinysrgb&w=500', desc: 'Vacinado, castrado, muito brincalhão. Adora crianças e outros cães.', saude: 'Antirrábica, V10, Gripe' },
    { id: 2, nome: 'Luna', icone: '🐱', lat: -22.9711, lng: -43.1822, especie: 'Gata', idade: '2 anos', porte: 'Pequeno', img: 'https://images.pexels.com/photos/104827/cat-pet-animal-domestic-104827.jpeg?auto=compress&cs=tinysrgb&w=500', desc: 'Tranquila, gosta de colo e de dormir ao sol. Boa com crianças maiores.', saude: 'Antirrábica, FIV/FeLV negativa' },
    { id: 3, nome: 'Max', icone: '🐶', lat: -22.9329, lng: -43.2435, especie: 'Cão', idade: '1 ano', porte: 'Grande', img: 'https://images.pexels.com/photos/2253275/pexels-photo-2253275.jpeg?auto=compress&cs=tinysrgb&w=500', desc: 'Energético e leal. Precisa de espaço e passeios diários.', saude: 'V10, Antirrábica, Castrado' },
    { id: 4, nome: 'Bolinha', icone: '🐰', lat: -22.9519, lng: -43.1850, especie: 'Coelho', idade: '8 meses', porte: 'Mini', img: 'https://images.pexels.com/photos/326012/pexels-photo-326012.jpeg?auto=compress&cs=tinysrgb&w=500', desc: 'Dócil e curioso. Já habituado a sair da gaiola e explorar.', saude: 'Saudável, Vermifugado' },
    { id: 5, nome: 'Mia', icone: '🐱', lat: -22.9836, lng: -43.2043, especie: 'Gata', idade: '4 meses', porte: 'Pequeno', img: 'https://images.pexels.com/photos/45201/kitty-cat-kitten-pet-45201.jpeg?auto=compress&cs=tinysrgb&w=500', desc: 'Filhote muito curiosa e carinhosa. Já usa a caixa de areia.', saude: 'V4 1ª dose, Vermifugada' },
    { id: 6, nome: 'Rex', icone: '🐕', lat: -22.9122, lng: -43.2302, especie: 'Cão', idade: '5 anos', porte: 'Grande', img: 'https://images.pexels.com/photos/164186/pexels-photo-164186.jpeg?auto=compress&cs=tinysrgb&w=500', desc: 'Cão calmo, ideal para companhia. Se dá bem com gatos.', saude: 'V10, Castrado, Antirrábica' },
    { id: 7, nome: 'Nina', icone: '🐱', lat: -22.9325, lng: -43.1843, especie: 'Gata', idade: '3 anos', porte: 'Médio', img: 'https://images.pexels.com/photos/2071873/pexels-photo-2071873.jpeg?auto=compress&cs=tinysrgb&w=500', desc: 'Muito independente, mas adora um carinho na orelha no fim do dia.', saude: 'Castrada, V5 Anual' },
    { id: 8, nome: 'Frederico', icone: '🐶', lat: -22.9845, lng: -43.2232, especie: 'Cão', idade: '1 ano', porte: 'Mini', img: 'https://images.pexels.com/photos/3487734/pexels-photo-3487734.jpeg?auto=compress&cs=tinysrgb&w=500', desc: 'Muito ativo e brincalhão, adora passear.', saude: 'Antirrábica, V10, Gripe' }
];

// NAVEGAÇÃO
function navegar(idPagina) {
    document.querySelectorAll('.pagina').forEach(pagina => {
        pagina.classList.remove('ativo');
        pagina.style.display = 'none';
    });
    
    document.querySelectorAll('.link-nav').forEach(link => link.classList.remove('ativo'));
    
    const paginaAlvo = document.getElementById(idPagina);
    paginaAlvo.classList.add('ativo');
    paginaAlvo.style.display = 'block';
    
    if (event && event.target && event.target.classList.contains('link-nav')) {
        event.target.classList.add('ativo');
    } else {
        const linkMenu = document.querySelector(`nav a[onclick="navegar('${idPagina}')"]`);
        if (linkMenu) linkMenu.classList.add('ativo');
    }
    
    window.scrollTo({top: 0, behavior: 'smooth'});

    // Lógica do Leaflet: Recalcula o tamanho do mapa se a aba aberta for a do mapa
    if (idPagina === 'mapa' && meuMapa) {
        setTimeout(() => {
            meuMapa.invalidateSize();
        }, 100);
    }
}

// RENDERIZAÇÃO DO MAPA 
function renderizarMapa() {
    if (meuMapa) return; 

    // Inicia o mapa centralizado no Rio de Janeiro 
    meuMapa = L.map('caixa-mapa').setView([-22.9350, -43.2000], 12);

    // Adiciona a camada visual do OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(meuMapa);

    // Percorre a lista de pets e adiciona um pino para cada um
    dadosPets.forEach(pet => {
        const marcador = L.marker([pet.lat, pet.lng]).addTo(meuMapa);
        
        // Popup que abre ao clicar no pino, com botão conectado ao modal!
        marcador.bindPopup(`
            <div style="text-align: center; min-width: 150px;">
                <h4 style="margin-bottom: 5px; font-family: 'Baloo 2', cursive; font-size: 1.2rem; color: var(--coral);">
                    ${pet.icone} ${pet.nome}
                </h4>
                <p style="margin-bottom: 12px; font-size: 0.9rem; color: var(--opaco);">
                    ${pet.especie} - ${pet.porte}
                </p>
                <button class="btn btn-principal" style="padding: 6px 12px; font-size: 0.85rem; width: 100%;" onclick="abrirModalPet(${pet.id})">
                    Ver Detalhes
                </button>
            </div>
        `);
    });
}

// MODAL DE PETS 
function abrirModalPet(idPet) {
    petSelecionado = dadosPets.find(p => p.id === idPet);
    if (!petSelecionado) return;

    document.getElementById('img-modal').src = petSelecionado.img;
    document.getElementById('nome-modal').innerText = petSelecionado.nome;
    document.getElementById('desc-modal').innerText = petSelecionado.desc;
    document.getElementById('saude-modal').innerText = `🩺 ${petSelecionado.saude}`;
    
    document.getElementById('etiquetas-modal').innerHTML = `
        <span class="etiqueta">${petSelecionado.especie}</span>
        <span class="etiqueta">${petSelecionado.idade}</span>
        <span class="etiqueta">${petSelecionado.porte}</span>
    `;
    document.getElementById('termo-aceite').checked = false; 

    document.getElementById('modalPet').classList.add('ativo');
}

function fecharModalPet() {
    document.getElementById('modalPet').classList.remove('ativo');
}

// VALIDAR O TERMO
function verificarTermoEAdotar() {
    const aceitouTermo = document.getElementById('termo-aceite').checked;

    if (!aceitouTermo) {
        alert("⚠️ Atenção: Você precisa ler e concordar com o Termo de Responsabilidade para continuar com a adoção.");
        return; 
    }

    iniciarChatDoModal();
}

// INICIAR O CHAT
function iniciarChatDoModal() {
    if(!estaLogado) {
        fecharModalPet();
        alert("Você precisa fazer login para enviar mensagens!");
        abrirModalLogin();
        return;
    }

    fecharModalPet();
    navegar('chat');

    document.querySelectorAll('.contato-chat').forEach(c => c.classList.remove('ativo'));

    const barraLateral = document.getElementById('barra-lateral-chat');
    const novoContatoDiv = document.createElement('div');
    novoContatoDiv.className = 'contato-chat ativo';
    novoContatoDiv.innerHTML = `
        <h4>Tutor do(a) ${petSelecionado.nome}</h4>
        <p>Sobre: ${petSelecionado.nome} - Olá! Recebemos a...</p>
    `;

    barraLateral.insertBefore(novoContatoDiv, barraLateral.firstChild);

    document.getElementById('nome-tutor-chat').innerText = `Tutor do(a) ${petSelecionado.nome}`;
    document.getElementById('etiqueta-pet-chat').innerText = `🐾 ${petSelecionado.nome}`;

    const recipienteMensagens = document.getElementById('mensagens-chat');
    recipienteMensagens.innerHTML = `
        <p style="text-align: center; color: var(--opaco); margin-bottom: 10px; font-size: 0.85rem;">
            Hoje
        </p>
        <div class="msg recebida">
            Olá! Recebemos a notificação de que você se interessou pelo(a) <strong>${petSelecionado.nome}</strong>. Como podemos te ajudar? 🐾
        </div>
    `;
}

// ENVIO DO CHAT
function enviarMensagem() {
    const campoEntrada = document.getElementById('entrada-chat');
    const texto = campoEntrada.value.trim();
    
    if (texto === '') return;

    const recipienteMensagens = document.getElementById('mensagens-chat');
    const novaMensagem = document.createElement('div');
    novaMensagem.className = 'msg enviada';
    novaMensagem.textContent = texto;
    recipienteMensagens.appendChild(novaMensagem);
    
    campoEntrada.value = '';
    recipienteMensagens.scrollTop = recipienteMensagens.scrollHeight;

    const contatoAtivo = document.querySelector('.contato-chat.ativo p');
    if(contatoAtivo && petSelecionado) {
        contatoAtivo.innerText = `Sobre: ${petSelecionado.nome} - Você: ${texto}`;
    }
}

function lidarComTecla(evento) {
    if (evento.key === 'Enter') {
        enviarMensagem();
    }
}

// LOGIN 
function abrirModalLogin() {
    document.getElementById('modalLogin').classList.add('ativo');
}

function fecharModalLogin() {
    document.getElementById('modalLogin').classList.remove('ativo');
}

function fazerLogin() {
    const campoEmail = document.getElementById('email-login').value;
    
    if(!campoEmail || !campoEmail.includes('@')) {
        alert("Por favor, digite um e-mail válido.");
        return;
    }

    const nomeUsuario = campoEmail.split('@')[0];
    
    estaLogado = true;
    fecharModalLogin();

    const btnNav = document.getElementById('btn-nav-login');
    btnNav.innerText = `Olá, ${nomeUsuario}`;
    btnNav.classList.remove('btn-principal');
    btnNav.classList.add('btn-contorno');
    
    btnNav.onclick = fazerLogout;
}

function fazerLogout() {
    if(confirm("Deseja realmente sair da sua conta?")) {
        estaLogado = false;
        
        document.getElementById('email-login').value = '';
        document.getElementById('senha-login').value = '';

        const btnNav = document.getElementById('btn-nav-login');
        btnNav.innerText = "Entrar";
        btnNav.classList.remove('btn-contorno');
        btnNav.classList.add('btn-principal');
        
        btnNav.onclick = abrirModalLogin;
        
        navegar('inicio');
    }
}

// Fechar modais ao clicar fora
document.querySelectorAll('.sobreposicao-modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('ativo');
        }
    });
});

// Renderizar grade e mapa ao carregar a página
window.onload = function() {
    renderizarMapa();
    // Força a aba inicial
    navegar('inicio');
};
