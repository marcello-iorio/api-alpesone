#!/bin/bash

# Script de Deploy para a API Alpes One

# Substitua com suas informações
SSH_USER="ubuntu"
SSH_HOST="56.124.96.178" 
SSH_KEY="~/.ssh/api-alpesone.pem" # O caminho para sua chave .pem no seu computador
PROJECT_PATH="/var/www/api-alpesone"

# --- MENSAGENS DE LOG ---
echo "🚀 Iniciando deploy para o servidor..."
echo "Host: $SSH_HOST"
echo "Usuário: $SSH_USER"
echo "------------------------------------------------"

# --- COMANDOS A SEREM EXECUTADOS NO SERVIDOR ---
# Usamos uma única conexão SSH para executar todos os comandos de uma vez
SSH_COMMANDS="
    echo '1. Acessando o diretório do projeto...' &&
    cd $PROJECT_PATH &&

    echo '2. Baixando a versão mais recente do código...' &&
    git pull origin main &&

    echo '3. Instalando dependências do Composer...' &&
    composer install --no-dev --optimize-autoloader &&

    echo '4. Limpando caches da aplicação...' &&
    php artisan optimize:clear &&

    echo '5. Rodando as migrations do banco de dados...' &&
    php artisan migrate --force &&

    echo '6. Reiniciando o serviço do PHP para aplicar as mudanças...' &&
    sudo systemctl restart php8.3-fpm &&

    echo '✅ Deploy finalizado com sucesso!'
"

# --- EXECUÇÃO ---
# Conecta via SSH e executa a sequência de comandos
ssh -i $SSH_KEY $SSH_USER@$SSH_HOST "${SSH_COMMANDS}"

echo "------------------------------------------------"
echo "🎉 Processo de deploy concluído."