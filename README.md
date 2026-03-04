# Desafio Revvo

Aplicação desenvolvida usando PHP 8.3 com arquitetura MVC enxuta, Tailwind + Gulp para front-end, e PostgreSQL como base de dados. Camadas principais:
- **Domínio**: modelos e serviços de cursos e slides (`app/Domain`).
- **HTTP**: controllers, roteador simples e views PHP em `app/Views`.
- **Build front**: Tailwind/PostCSS e Gulp (`resources/css`, `resources/js`, `gulpfile.js`), com bundle em `public/assets/dist`.
- **Dados**: repositórios via PDO; schema mínimo criado automaticamente (tabela `courses` com imagens, destaque, etc.).

## Stack
- PHP 8.3 (CLI server)
- PostgreSQL (via Docker)
- Tailwind CSS + PostCSS + Gulp
- Node/npm para build do front

## Como executar com Docker Compose
1. Copie o ambiente padrão e ajuste se necessário:  
   `cp .env.example .env`
2. Suba os serviços (app PHP + Postgres):  
   `docker compose up --build`  *(use `sudo` se necessário)*
3. Acesse a aplicação em `http://localhost:8000`. O Postgres expõe a porta `5433` no host.

O container PHP roda `composer install` ao iniciar e serve `public/`. O banco persiste em `pgdata`.
