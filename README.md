# 🐾 PetPorAqui

**Sistema Web de Adoção e Gerenciamento de Pets**
*Projeto desenvolvido para avaliação (AV2) do curso de Análise e Desenvolvimento de Sistemas (ADS) da FAETERJ-RIO.*

🔗 **Acesso ao Sistema Online:** [http://petporaqui.kesug.com]

---

## 📌 Sobre o Projeto
O **PetPorAqui** é uma plataforma dinâmica projetada para conectar ONGs, tutores particulares e pessoas interessadas em adoção responsável. O sistema oferece uma vitrine de animais disponíveis e um painel administrativo completo para gestão dos dados.

## ⚙️ Funcionalidades Desenvolvidas
A aplicação atende aos requisitos de um sistema web dinâmico com conexão a banco de dados relacional:
* **CRUD Completo:** Leitura de pets na vitrine e operações de Criação, Atualização e Exclusão no Painel Administrativo.
* **Sistema de Autenticação:** Login e controle de sessões via PHP.
* **Níveis de Acesso (Controle de Usuários):** * *Administradores / ONGs:* Acesso total ao painel para cadastrar, editar status (Disponível/Adotado) e remover registros.
  * *Adotantes:* Acesso exclusivo à vitrine pública de animais.
* **Filtro Automático de Regra de Negócio:** A vitrine exibe apenas animais com o status "Disponível".

## 💻 Tecnologias Utilizadas
* **Frontend:** HTML5, CSS3, JavaScript
* **Backend:** PHP 8
* **Banco de Dados:** MySQL (Modelo Relacional).
* **Hospedagem:** InfinityFree (Servidor Linux Apache).

---

## 🚀 Como testar e executar o projeto online (InfinityFree)
O projeto foi pensado para ser hospedado gratuitamente. Para recriar o ambiente no InfinityFree, siga os passos abaixo:

### 1. Configurando o Banco de Dados
1. Crie uma conta no [InfinityFree](https://infinityfree.com/) e crie um novo domínio gratuito.
2. Acesse o Painel de Controle (Control Panel) e vá até a seção **MySQL Databases**.
3. Crie um novo banco de dados (obrigatoriamente usando letras minúsculas, ex: `petporaqui`).
4. Clique em **Admin** para abrir o phpMyAdmin.
5. Na aba **SQL**, cole e execute o conteúdo do arquivo `script.sql` (disponível neste repositório) para gerar as tabelas e os dados base.

### 2. Configurando a Conexão
Abra o arquivo `conexao.php` e altere as variáveis com as credenciais fornecidas pelo InfinityFree (encontradas na tela do MySQL Databases):
```php
$host = 'sqlXXX.infinityfree.com'; // Host fornecido no painel
$db   = 'if0_XXXXXX_petporaqui';   // Nome do banco com o prefixo
$user = 'if0_XXXXXX';              // Seu MySQL Username
$pass = 'sua_senha_do_painel';     // Senha da conta (vPanel Password)

### 3. Publicando os Arquivos
1. No painel do InfinityFree, acesse o File Manager (Gerenciador de Arquivos).

2. Entre na pasta htdocs.

3. Apague os arquivos padrão que estiverem lá e faça o upload de todos os arquivos deste repositório (os arquivos .php, .css e .js).

4. Acesse a URL do seu domínio gerado para visualizar o sistema rodando.

• Desenvolvido por:

João Pedro de Almeida Costa
Guilherme Martins de Sousa
Nathalia Yasmin Bastos Cardoso
