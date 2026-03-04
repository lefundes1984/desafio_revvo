# Desafio Revvo

Aplicação PHP 8.3 com Tailwind/Gulp e banco PostgreSQL via Docker Compose.

## Como rodar com Docker
1. Copie o arquivo de ambiente e ajuste se preciso: `cp .env.example .env`.
2. Suba os containers (PHP + Postgres): `docker compose up --build` (use `sudo` se necessário).
3. A aplicação fica disponível em http://localhost:8000 e o banco em `localhost:5433` (porta do host mapeada para o 5432 do container).

O container PHP executa `composer install` automaticamente no start e usa o servidor embutido servindo o diretório `public/`. Os dados do Postgres ficam no volume `pgdata`.

### Dicas rápidas
- A tabela `courses` é criada automaticamente na primeira conexão, caso não exista.
- Para popular o banco: `docker compose exec db psql -U desafio_user -d desafio_revvo -c "INSERT INTO courses (title, description, price) VALUES ('Demo', 'Lorem ipsum', 0)"`.
- Para rodar o build de CSS/JS (no host): `npm install && npm run build`.
- Formulário web de cadastro fica em `/admin/courses` (cria cursos direto na base).
