# 📚 Sistema de Gerenciamento de Biblioteca

Este é um sistema completo para gerenciamento de usuários, livros, gêneros e empréstimos de uma biblioteca, desenvolvido em Laravel.

---

## 🚀 Tecnologias Utilizadas

### Backend
- **PHP 8.2+**
- **Laravel 12**
- **MySQL**
- **JWT Auth** (`tymon/jwt-auth`)
- **L5-Swagger** (documentação de API)
- **Cache**: Api cache laravel
- **Laravel Debugbar** (dev)

### Frontend (opcional)
- **React** 
- **Axios**

---

## ⚙️ Como rodar o projeto

### Pré-requisitos
- PHP 8.2+
- Composer
- MySQL
- Node.js e npm (opcional, para frontend)
- Redis (opcional, para cache)

### Instalação Backend (Laravel)

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/seu-usuario/nome-do-repo.git
   cd nome-do-repo
   ```
2. **Instale as dependências:**
   ```bash
   composer install
   ```
3. **Copie o arquivo de ambiente:**
   ```bash
   cp .env.example .env
   ```
4. **Configure o banco de dados** no `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nome_do_banco
   DB_USERNAME=usuario
   DB_PASSWORD=senha
   ```
5. **Gere a chave da aplicação:**
   ```bash
   php artisan key:generate
   ```
6. **Rode as migrations e seeders:**
   ```bash
   php artisan migrate --seed
   ```
7. **(Opcional) Configure o cache para Redis:**
   No `.env`:
   ```env
   CACHE_STORE=redis
   REDIS_HOST=127.0.0.1
   REDIS_PORT=6379
   ```
8. **Gere o JWT secret:**
   ```bash
   php artisan jwt:secret
   ```
9. **Inicie o servidor:**
   ```bash
   php artisan serve
   ```

### Instalação Frontend (opcional)

1. **Instale as dependências:**
   ```bash
   npm install
   ```
2. **Inicie o servidor de desenvolvimento:**
   ```bash
   npm run dev
   ```

---

## 🔐 Autenticação
- A autenticação é feita via JWT (Bearer Token).
- Após o login, inclua o token no header das requisições:
  ```
  Authorization: Bearer {seu_token}
  ```

---

## 🧾 Documentação da API
- Acesse a documentação Swagger em: `http://localhost:8000/api/documentation`
- Todos os endpoints estão documentados com exemplos de request/response.

---

## 🗄️ Cache
- Listagens de livros, usuários, gêneros e empréstimos utilizam cache automático.
- O cache é invalidado ao criar, atualizar ou deletar registros.
- Suporte a Redis ou Database.

---

## 🛠️ Comandos Úteis
- Rodar testes:
  ```bash
  php artisan test
  ```
- Limpar cache:
  ```bash
  php artisan cache:clear
  ```
- Rodar jobs agendados:
  ```bash
  php artisan schedule:work
  ```

---

## 👤 Credenciais de Teste
- Você pode criar usuários via endpoint de cadastro ou seeders.
- Exemplo de login:
  ```json
  {
    "email": "admin@admin.com",
    "password": "password"
  }
  ```

---

## 📄 Licença
MIT
