# API de Veículos - Desafio Técnico

Este repositório contém o código-fonte de uma API RESTful desenvolvida em Laravel como parte de um desafio técnico. O objetivo do projeto é importar, armazenar e expor dados de veículos a partir de uma API externa, além de configurar a infraestrutura e o deploy em um ambiente AWS.

## Tecnologias Utilizadas

* **Backend:** PHP 8.2+, Laravel 11+
* **Banco de Dados:** SQLite
* **Gerenciador de Dependências:** Composer
* **Testes:** Pest / PHPUnit
* **Cliente de API:** Postman

## Configuração do Ambiente de Desenvolvimento

Siga os passos abaixo para configurar e rodar a aplicação localmente.

### Pré-requisitos

Certifique-se de que sua máquina de desenvolvimento tenha os seguintes softwares instalados:

* **PHP:** Versão 8.2 ou superior.
* **Extensões PHP:** `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pcre`, `pdo`, `tokenizer`, `xml`, `sqlite3`.
* **Composer:** [Instalador do Composer](https://getcomposer.org/download/).
* **Node.js e NPM:** [Instalador do Node.js](https://nodejs.org/en).
* **Git:** Para clonar o repositório.

### Passos para Instalação

1.  **Clone o repositório:**
    ```bash
    git clone [URL_DO_SEU_REPOSITORIO_GIT]
    cd [NOME_DA_PASTA_DO_PROJETO]
    ```

2.  **Instale as dependências do PHP:**
    ```bash
    composer install
    ```

3.  **Instale as dependências do Node.js:**
    ```bash
    npm install
    ```

4.  **Configure o arquivo de ambiente:**
    Copie o arquivo de exemplo `.env.example` para um novo arquivo chamado `.env`.
    ```bash
    cp .env.example .env
    ```

5.  **Gere a chave da aplicação:**
    ```bash
    php artisan key:generate
    ```

6.  **Configure o Banco de Dados (SQLite):**
    * Crie o arquivo do banco de dados na pasta `database/`:
        ```bash
        touch database/database.sqlite
        ```
    * Abra o arquivo `.env` e configure a variável `DB_DATABASE` com o caminho **absoluto** para o arquivo que você acabou de criar.
        ```env
        DB_CONNECTION=sqlite
        DB_DATABASE=/caminho/completo/para/seu/projeto/database/database.sqlite
        ```

7.  **Execute as Migrations:**
    Este comando irá criar a estrutura do banco de dados.
    ```bash
    php artisan migrate:fresh
    ```

## Uso e Execução

Após a instalação e configuração inicial, você pode interagir com a aplicação da seguinte forma.

### Executando a Aplicação Localmente

Para iniciar o servidor de desenvolvimento do Laravel, execute:
```bash
php artisan serve
```
Este comando iniciará o servidor local. Por padrão, a API estará acessível em `http://127.0.0.1:8000`.

### Comandos da Aplicação

#### Importação de Dados

Este é o comando principal que busca os dados da API externa e os sincroniza com o banco de dados local.

```bash
php artisan import:vehicles
```
Este comando se conecta à API da Alpes One, baixa os dados dos veículos e os salva/atualiza no banco de dados. Ele utiliza um sistema de cache de 15 minutos para respeitar o limite de acessos da API externa e está agendado para rodar de hora em hora.

### Executando os Testes Automatizados

Para garantir a qualidade e a integridade do código, a aplicação conta com uma suíte de testes automatizados. Para executá-la, rode:
```bash
php artisan test
```
Este comando executa a suíte completa de testes (Unitários e de Integração). Os testes rodam em um banco de dados SQLite em memória para garantir velocidade e isolamento, sem afetar seu banco de dados principal.

## Documentação da API

### Coleção do Postman

Para facilitar os testes e o uso da API, uma coleção do Postman está incluída neste repositório:
`postman_collection.json`

Importe este arquivo no seu Postman para ter acesso a todas as requisições prontas para usar.

### Endpoints Disponíveis

| Método | Endpoint | Descrição |
| :--- | :--- | :--- |
| `GET` | `/api/vehicles` | Lista todos os veículos de forma paginada. Aceita o parâmetro `?per_page=N`. |
| `GET` | `/api/vehicles/{id}` | Busca os detalhes de um veículo específico pelo seu ID. |
| `POST` | `/api/vehicles` | Cria um novo registro de veículo. Requer um corpo (body) em JSON. |
| `PATCH` | `/api/vehicles/{id}` | Atualiza parcialmente os dados de um veículo existente. Requer um corpo (body) em JSON. |
| `DELETE` | `/api/vehicles/{id}` | Remove um registro de veículo do banco de dados. |

## Documentação da API

A documentação da API, com todos os endpoints, exemplos de `body` para as requisições, está publicada e pode ser acessada através do link abaixo:

* **[Ver Documentação da API no Postman](https://documenter.getpostman.com/view/1689657/2sB3HeuPUH)**