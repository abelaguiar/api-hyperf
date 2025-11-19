#!/bin/bash

# Script para iniciar a aplicação Hyperf com MongoDB

echo "🚀 Iniciando aplicação Hyperf com MongoDB..."

# Verificar se Docker está rodando
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker não está rodando. Por favor, inicie o Docker."
    exit 1
fi

# Criar arquivo .env se não existir
if [ ! -f .env ]; then
    echo "📝 Criando arquivo .env..."
    cp .env.example .env
fi

# Parar containers existentes
echo "🛑 Parando containers existentes..."
docker-compose down

# Construir e iniciar containers
echo "🔨 Construindo e iniciando containers..."
docker-compose up -d --build

# Aguardar containers iniciarem
echo "⏳ Aguardando containers iniciarem..."
sleep 10

# Instalar dependências
echo "📦 Instalando dependências..."
docker-compose exec -T hyperf composer install

# Verificar status
echo ""
echo "✅ Aplicação iniciada com sucesso!"
echo ""
echo "📌 URLs importantes:"
echo "   API: http://localhost:9501"
echo "   MongoDB: mongodb://admin:admin123@localhost:27017/hyperf_db"
echo ""
echo "📋 Comandos úteis:"
echo "   Ver logs: docker-compose logs -f hyperf"
echo "   Parar: docker-compose down"
echo "   Acessar container: docker-compose exec hyperf sh"
echo ""
