#!/bin/bash

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}🔄 Iniciando ED2 Project com Docker...${NC}"

# Verifica se docker compose está disponível
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker não está instalado!${NC}"
    exit 1
fi

# Verifica se docker compose funciona
if ! docker compose version &> /dev/null; then
    echo -e "${RED}❌ Docker Compose não está disponível!${NC}"
    echo -e "${YELLOW}💡 Tente instalar: https://docs.docker.com/compose/install/${NC}"
    exit 1
fi

# Carrega variáveis do .env se existir
if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
    echo -e "${GREEN}✅ Variáveis de ambiente carregadas do .env${NC}"
else
    echo -e "${YELLOW}⚠️  Arquivo .env não encontrado, usando valores padrão${NC}"
fi

# Define variáveis com fallback
DB_NAME=${DB_NAME:-"monitoramento_db"}
DB_USER=${DB_USER:-"monitoramento_user"}
DB_PASS=${DB_PASS:-"monitoramento_password"}

echo -e "${BLUE}📁 Criando estrutura de pastas...${NC}"

# Cria diretórios necessários
mkdir -p nginx mysql php src/uploads
chmod 755 src/uploads
chmod 777 src/uploads  # Permissão para uploads funcionarem

# Verifica se os arquivos de configuração essenciais existem
if [ ! -f nginx/default.conf ]; then
    echo -e "${RED}❌ nginx/default.conf não encontrado!${NC}"
    exit 1
fi

if [ ! -f db/init.sql ]; then
    echo -e "${RED}❌ db/init.sql não encontrado!${NC}"
    exit 1
fi

if [ ! -f php/Dockerfile ]; then
    echo -e "${RED}❌ php/Dockerfile não encontrado!${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Estrutura de pastas verificada${NC}"

# Para containers existentes
echo -e "${YELLOW}🐳 Parando containers existentes...${NC}"
docker compose down

# Build e inicia os containers
echo -e "${BLUE}🐳 Construindo e iniciando containers Docker...${NC}"
docker compose up -d --build

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Erro ao iniciar containers Docker!${NC}"
    exit 1
fi

echo -e "${YELLOW}⏳ Aguardando serviços inicializarem...${NC}"

# Aguarda os serviços estarem prontos
for i in {1..30}; do
    if docker compose exec -T mysql mysql -u$DB_USER -p$DB_PASS -e "SELECT 1;" > /dev/null 2>&1; then
        echo -e "${GREEN}✅ MySQL está pronto!${NC}"
        break
    fi
    if [ $i -eq 30 ]; then
        echo -e "${RED}❌ Timeout aguardando MySQL${NC}"
        docker compose logs mysql
        exit 1
    fi
    sleep 2
done

# Aguarda PHP-FPM
sleep 5

echo -e "${GREEN}✅ Todos os serviços estão rodando!${NC}"

# Verifica status
echo -e "${BLUE}📊 Status dos serviços:${NC}"
docker compose ps

# Mostra informações de acesso
echo -e "${GREEN}"
echo "=========================================="
echo "✅ SETUP CONCLUÍDO!"
echo "=========================================="
echo -e "${NC}"
echo -e "${BLUE}🌐 Aplicação:${NC} http://localhost"
echo -e "${BLUE}📊 MySQL:${NC} localhost:3306"
echo -e "${BLUE}👤 Usuário MySQL:${NC} $DB_USER"
echo -e "${BLUE}🔐 Senha MySQL:${NC} $DB_PASS"
echo -e "${BLUE}🗃️  Banco:${NC} $DB_NAME"
echo ""
echo -e "${YELLOW}💡 Dica: Teste o login com:${NC}"
echo -e "   Usuário: ${GREEN}admin${NC}"
echo -e "   Senha: ${GREEN}senha123${NC}"
echo ""
echo -e "${YELLOW}📝 Comandos úteis:${NC}"
echo -e "   Logs: ${BLUE}docker compose logs [serviço]${NC}"
echo -e "   Shell: ${BLUE}docker compose exec php bash${NC}"
echo -e "   MySQL: ${BLUE}docker compose exec mysql mysql -u$DB_USER -p$DB_PASS $DB_NAME${NC}"
echo -e "   Parar: ${BLUE}docker compose down${NC}"
echo -e "   Reiniciar: ${BLUE}./start.sh${NC}"
echo -e "${GREEN}==========================================${NC}"