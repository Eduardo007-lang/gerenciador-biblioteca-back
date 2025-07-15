# 📋 Plano de Execução - Teste Técnico Backend Pleno

Este documento organiza as tarefas necessárias para a construção da aplicação de gerenciamento de biblioteca, incluindo backend com Laravel, frontend com React e documentação com Swagger.

---

## ✅ Etapa 1: Planejamento e Organização do Projeto

- [ ] Clonar repositório e configurar estrutura inicial do projeto Laravel
- [ ] Criar repositório Git público (GitHub/GitLab)
- [ ] Adicionar `.editorconfig`, `.gitignore`, e `.env.example`
- [ ] Criar README principal explicando o projeto e os comandos básicos de setup

---

## 🗂️ Etapa 2: Backend - Laravel

### 2.1. Setup e configuração inicial

- [ ] Instalar dependências do Laravel
- [ ] Configurar banco de dados (MySQL)
- [ ] Criar migrations para:
  - [ ] Users
  - [ ] Books
  - [ ] Genres
  - [ ] Loans
- [ ] Relacionamentos: definir relações entre `Book -> Genre`, `Loan -> User`, `Loan -> Book`

### 2.2. Autenticação e autorização

- [ ] Instalar e configurar **Laravel Sanctum**
- [ ] Criar rotas de registro e login
- [ ] Proteger rotas com middleware `auth:sanctum`
- [ ] Criar **Policies** (ex: apenas o admin pode excluir registros)

### 2.3. Organização de código

- [ ] Criar camada de **Service** para Users, Books e Loans
- [ ] Criar camada de **Repository** (opcional, para desacoplamento da lógica de dados)
- [ ] Criar **FormRequest** para validações de entrada

### 2.4. Funcionalidades obrigatórias

#### Usuários

- [ ] Criar, listar, atualizar e excluir usuários
- [ ] Campos: nome, email, número de cadastro

#### Livros

- [ ] Criar, listar, atualizar e excluir livros
- [ ] Campos: nome, autor, número de registro, situação (emprestado/disponível)
- [ ] Associar livro a gênero

#### Gêneros

- [ ] CRUD de gêneros (opcional: somente criação e listagem)

#### Empréstimos

- [ ] Criar empréstimo vinculando usuário + livro
- [ ] Registrar data de devolução
- [ ] Marcar como devolvido ou atrasado

### 2.5. Extras de backend

- [ ] Implementar **Cache** com `Cache::remember()` para listagens
- [ ] Usar **Eager Loading** com `with()` para evitar N+1
- [ ] Paginação nas listagens (`paginate()`)
- [ ] Versionamento de API (`/api/v1/...`)
- [ ] Criar testes básicos (Feature) para 2 ou 3 endpoints principais

---

## 🌐 Etapa 3: Frontend (React)

> O front-end não será avaliado, mas pode ser adicionado para mostrar domínio Fullstack.

- [ ] Criar projeto com Vite ou CRA
- [ ] Criar tela de login (token-based)
- [ ] Criar tela de dashboard:
  - [ ] Listar livros por gênero
  - [ ] Cadastrar empréstimo
  - [ ] Listar usuários
  - [ ] Visualizar status dos empréstimos
- [ ] Consumir a API Laravel com autenticação Bearer Token

---

## 🧾 Etapa 4: Documentação da API com Swagger

- [ ] Instalar o pacote [L5 Swagger](https://github.com/DarkaOnLine/L5-Swagger)
- [ ] Documentar todos os endpoints (usuários, livros, empréstimos)
- [ ] Incluir exemplos de requisição e resposta
- [ ] Adicionar URL de acesso à documentação no README principal

---

## 🚀 Etapa 5: Preparação para Entrega

- [ ] Atualizar o `README.md` principal com:
  - [ ] Instruções para rodar backend
  - [ ] Como rodar o frontend (opcional)
  - [ ] Como acessar a documentação Swagger
  - [ ] Informações sobre autenticação
  - [ ] Credenciais fake para teste
- [ ] Testar aplicação localmente
- [ ] Validar todas as rotas via Postman ou Swagger UI
- [ ] Comitar todas as alterações e empurrar para o repositório público

---

## 🧠 Extras (caso sobre tempo)

- [ ] Middleware para marcar empréstimos vencidos automaticamente
- [ ] Eventos/Listeners para logs ou simular notificações
- [ ] Exportar listagem de empréstimos para CSV ou PDF
