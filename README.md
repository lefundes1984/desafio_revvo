# Desafio Revvo

Aplicação desenvolvida usando PHP 8.3 com arquitetura MVC enxuta, Tailwind + Gulp para front-end, e PostgreSQL como base de dados. O stack do projeto está conteinerizado: o docker-compose.yml sobe um PHP 8.3 (com nginx/cli do Dockerfile) e um Postgres; não há dependência direta de PHP/Node instalados na máquina host para rodar a aplicação.
Os pré-requisitos no host são: Docker Desktop (ou Docker Engine) + Docker Compose; no Windows, usar WSL2 habilitado para funcionar bem com bind mounts.

Camadas principais do projeto:
- **Domínio**: modelos e serviços de cursos e slides (`app/Domain`).
- **HTTP**: controllers, roteador simples e views PHP em `app/Views`.
- **Build front**: Tailwind/PostCSS e Gulp (`resources/css`, `resources/js`, `gulpfile.js`), com bundle em `public/assets/dist`.
- **Dados**: repositórios via PDO; schema mínimo criado automaticamente (tabela `courses` com imagens, destaque, etc.).

## Stack
- PHP 8.3 (CLI server)
- PostgreSQL (via Docker)
- Tailwind CSS + PostCSS + Gulp
- Node/npm para build do front

## Executando o projeto em 3 passos com Docker Compose
1. Copie o ambiente padrão e ajuste se necessário:  
   `cp .env.example .env`
2. Suba os serviços (app PHP + Postgres):  
   `docker compose up --build`  *(use `sudo` se necessário)*
3. Acesse a aplicação em `http://localhost:8000`. O Postgres expõe a porta `5433` no host.

O container PHP roda `composer install` ao iniciar e serve `public/`. O banco persiste em `pgdata`.
