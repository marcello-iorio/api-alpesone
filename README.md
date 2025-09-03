# API de Veículos

![Status do Deploy](https://github.com/marcello-iorio/api-alpesone/actions/workflows/deploy.yml/badge.svg)
[![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-777BB4)](https://www.php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-11%2B-FF2D20)](https://laravel.com)



## 🎯 Objetivo do Projeto

Este repositório contém o código-fonte de uma API RESTful completa. O objetivo do projeto é demonstrar habilidades de ponta a ponta, desde o desenvolvimento backend com Laravel até a configuração de infraestrutura na nuvem (AWS) e automação de deploy (CI/CD com GitHub Actions).

A aplicação consome dados de veículos de uma API externa, os armazena e os expõe através de seus próprios endpoints CRUD.

## ✨ Principais Funcionalidades

-   **Importação de Dados Automatizada:** Um comando Artisan que consome uma API externa, respeitando limites de acesso com cache, e sincroniza os dados em um banco de dados local.
-   **API RESTful Completa:** Endpoints para todas as operações CRUD (Create, Read, Update, Delete) para o recurso de veículos.
-   **Paginação Dinâmica:** A listagem de veículos é paginada e permite que o cliente defina a quantidade de itens por página via query param (`?per_page=N`).
-   **Validação Robusta:** Uso de Form Requests para validar todas as entradas de dados na API, com tratamento de erro customizado para respostas em JSON.
-   **Infraestrutura na Nuvem (IaaS):** Configuração completa de um servidor Ubuntu em uma instância AWS EC2 com stack LEMP (Nginx, PHP-FPM) e HTTPS.
-   **Deploy Automatizado (CI/CD):** Um pipeline de Integração e Deploy Contínuo com GitHub Actions que atualiza o servidor de produção automaticamente a cada `push` na branch `main`.

## 🛠️ Tecnologias Utilizadas

-   **Backend:** PHP 8.3, Laravel 11
-   **Banco de Dados:** SQLite
-   **Servidor:** Nginx
-   **Infraestrutura:** AWS EC2
-   **CI/CD:** GitHub Actions
-   **Testes:** Pest / PHPUnit
-   **Documentação:** Postman

## 📚 Documentação da API

A documentação completa da API, com todos os endpoints e exemplos de uso, foi criada com o Postman e pode ser acessada através do link público abaixo.

* **[Ver Documentação da API no Postman](https://documenter.getpostman.com/view/1689657/2sB3HeuPUH#551b2191-ed6d-4522-9475-a6beb163b4c7)**

Alternativamente, para testes práticos, você pode importar a coleção diretamente no seu aplicativo Postman usando o arquivo `postman_collection.json` que se encontra na raiz deste repositório.

### Endpoints Disponíveis

| Método  | Endpoint                 | Descrição                                                                      |
| :------ | :----------------------- | :----------------------------------------------------------------------------- |
| `GET`   | `/api/vehicles`          | Lista todos os veículos de forma paginada. Aceita o parâmetro `?per_page=N`.   |
| `GET`   | `/api/vehicles/{id}`     | Busca os detalhes de um veículo específico pelo seu ID.                          |
| `POST`  | `/api/vehicles`          | Cria um novo registro de veículo. Requer um corpo (body) em JSON.                |
| `PATCH` | `/api/vehicles/{id}`     | Atualiza parcialmente os dados de um veículo existente. Requer um corpo (body) em JSON. |
| `DELETE`| `/api/vehicles/{id}`     | Remove um registro de veículo do banco de dados.                                 |

## 🚀 Pipeline de Deploy Automatizado (CI/CD)

Este projeto utiliza GitHub Actions para automatizar o processo de deploy.
-   **Gatilho:** Qualquer `push` para a branch `main`.
-   **Ações:** O workflow se conecta ao servidor EC2 via SSH e executa o script de deploy, que inclui os seguintes passos:
    1.  `git pull` para baixar o código mais recente.
    2.  `composer install` para instalar dependências.
    3.  `php artisan optimize:clear` para limpar os caches.
    4.  `php artisan migrate --force` para rodar migrations pendentes.
    5.  `sudo systemctl restart php8.3-fpm` para aplicar todas as mudanças no código.

---

## 💻 Configuração do Ambiente de Desenvolvimento (Local)

Siga os passos abaixo para configurar e rodar a aplicação localmente.

### Pré-requisitos

-   PHP 8.2+ e as extensões: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pcre`, `pdo`, `tokenizer`, `xml`, `sqlite3`.
-   [Composer](https://getcomposer.org/download/)
-   [Node.js & NPM](https://nodejs.org/en)
-   Git

### Passos para Instalação

1.  **Clone o repositório:**
    ```bash
    git clone URL_DO_SEU_REPOSITORIO_GIT
    cd NOME_DA_PASTA_DO_PROJETO
    ```

2.  **Instale as dependências:**
    ```bash
    composer install
    npm install
    ```

3.  **Configure o arquivo de ambiente:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Configure o Banco de Dados (SQLite):**
    * Crie o arquivo do banco de dados:
        ```bash
        touch database/database.sqlite
        ```
    * No arquivo `.env`, configure a variável `DB_DATABASE` com o caminho **absoluto** para o arquivo.

5.  **Execute as Migrations e a Importação:**
    ```bash
    php artisan migrate:fresh
    php artisan import:vehicles
    ```

6.  **Inicie o servidor:**
    ```bash
    php artisan serve
    ```
    A API estará rodando em `http://127.0.0.1:8000`.

## ☁️ Configuração do Servidor de Produção (AWS EC2)

Esta seção resume os passos para configurar um servidor Ubuntu limpo na AWS para hospedar esta aplicação.

1.  **Criar e Acessar a Instância EC2:**
    -   Lançar uma instância `t3.micro` com Ubuntu Server 24.04 LTS.
    -   Configurar um Security Group para permitir tráfego nas portas `22 (SSH)`, `80 (HTTP)` e `443 (HTTPS)`.
    -   Conectar-se via SSH usando o par de chaves gerado.

2.  **Instalar a Stack LEMP e Dependências:**
    ```bash
    sudo apt update && sudo apt upgrade -y
    sudo apt install nginx php8.3-fpm php8.3-sqlite3 php8.3-curl php8.3-xml php8.3-mbstring php8.3-zip php8.3-intl git -y
    curl -sS [https://getcomposer.org/installer](https://getcomposer.org/installer) | sudo php -- --install-dir=/usr/local/bin --filename=composer
    ```

3.  **Configurar Chave de Deploy (Servidor -> GitHub):**
    -   Gerar uma chave SSH no servidor com `ssh-keygen`.
    -   Adicionar a chave pública (`~/.ssh/id_rsa.pub`) às "Deploy Keys" nas configurações do repositório no GitHub.

4.  **Fazer o Deploy e Configurar a Aplicação:**
    -   Clonar o repositório em `/var/www/` usando a URL SSH.
    -   Ajustar as permissões de arquivo e pasta (`chown`, `chmod`) para os usuários `ubuntu` e `www-data`.
    -   Rodar `composer install --no-dev`.
    -   Configurar o arquivo `.env` para produção.
    -   Rodar `php artisan key:generate`.

5.  **Configurar o Nginx:**
    -   Criar um arquivo de configuração em `/etc/nginx/sites-available/`.
    -   Apontar a diretiva `root` para `/var/www/SUA_PASTA/public` e configurar o `fastcgi_pass` para o socket do PHP-FPM.
    -   Ativar o site criando um link simbólico em `/etc/nginx/sites-enabled/` e reiniciar o Nginx.

6.  **Finalizar e Popular Banco de Dados:**
    -   Criar o arquivo `database/database.sqlite` com `touch`.
    -   Ajustar as permissões da pasta e do arquivo para o usuário `www-data`.
    -   Rodar `php artisan migrate:fresh --force` e `php artisan import:vehicles`.

7.  **Configurar o Agendador de Tarefas (Cron Job):**
    -   Abrir o editor de cron com `sudo crontab -e`.
    -   Adicionar a linha para executar o agendador do Laravel a cada minuto:
        ```cron
        * * * * * cd /var/www/api-alpesone && php artisan schedule:run >> /dev/null 2>&1
        ```

---
**Desenvolvido por Marcello**
