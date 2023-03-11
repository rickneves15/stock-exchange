## Requisitos

-   Docker
-   Docker-compose

## Instalar API

Passo para rodar a API.

1. Entre no terminal
2. Rodar o comando
    - Linux: **`copy .env.example .env`** no terminal
    - Windows: **`cd  .env.example .env`** no terminal
3. Abra o copie o arquivo .env e configure as variáveis
    - DB_HOST
    - DB_PORT
    - DB_DATABASE
    - DB_USERNAME
    - DB_PASSWORD
    - OBS: lembrando que foi configurado com o banco de dados postgres
4. Rodar o comando **`docker-compose up -d --build`** no terminal

URLs

-   API home - http://localhost:8000
