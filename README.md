# API Hyperf com MongoDB

Aplicação de API REST construída com [Hyperf](https://hyperf.io/) e MongoDB, totalmente containerizada com Docker.

## 🚀 Tecnologias

- **PHP 8.1+** com Swoole
- **Hyperf 3.1** - Framework de alta performance
- **MongoDB 7.0** - Banco de dados NoSQL
- **Docker & Docker Compose** - Containerização

## 📋 Pré-requisitos

- Docker
- Docker Compose

## 🔧 Instalação e Configuração

### 1. Clone o repositório (se aplicável)

```bash
git clone <seu-repositorio>
cd api-hyperf
```

### 2. Configure as variáveis de ambiente

```bash
cp .env.example .env
```

Edite o arquivo `.env` conforme necessário.

### 3. Inicie os containers

```bash
docker-compose up -d
```

### 4. Instale as dependências

```bash
docker-compose exec hyperf composer install
```

### 5. Acesse a aplicação

A API estará disponível em: `http://localhost:9501`

## 📚 Endpoints da API

### Status da API
```bash
GET /
```

### Gerenciamento de Usuários

#### Listar todos os usuários
```bash
GET /api/users
```

#### Obter um usuário específico
```bash
GET /api/users/{id}
```

#### Criar novo usuário
```bash
POST /api/users
Content-Type: application/json

{
  "name": "João Silva",
  "email": "joao@example.com",
  "age": 30
}
```

#### Atualizar usuário
```bash
PUT /api/users/{id}
Content-Type: application/json

{
  "name": "João Silva Atualizado",
  "email": "joao.novo@example.com",
  "age": 31
}
```

#### Deletar usuário
```bash
DELETE /api/users/{id}
```

## 🧪 Exemplos de uso com curl

```bash
# Listar usuários
curl http://localhost:9501/api/users

# Criar usuário
curl -X POST http://localhost:9501/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Maria Santos","email":"maria@example.com","age":25}'

# Obter usuário específico
curl http://localhost:9501/api/users/{id}

# Atualizar usuário
curl -X PUT http://localhost:9501/api/users/{id} \
  -H "Content-Type: application/json" \
  -d '{"name":"Maria Santos Silva","age":26}'

# Deletar usuário
curl -X DELETE http://localhost:9501/api/users/{id}
```

## 🗂️ Estrutura do Projeto

```
api-hyperf/
├── app/
│   ├── Controller/
│   │   ├── IndexController.php
│   │   └── UserController.php
│   └── Service/
│       └── MongoDBService.php
├── bin/
│   └── hyperf.php
├── config/
│   ├── autoload/
│   │   ├── dependencies.php
│   │   ├── mongodb.php
│   │   └── server.php
│   ├── config.php
│   ├── container.php
│   └── routes.php
├── docker/
│   └── mongodb/
│       └── init/
│           └── init.js
├── runtime/
├── .dockerignore
├── .env.example
├── .gitignore
├── composer.json
├── docker-compose.yml
├── Dockerfile
└── README.md
```

## 🐳 Comandos Docker úteis

```bash
# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Ver logs
docker-compose logs -f hyperf

# Acessar container do Hyperf
docker-compose exec hyperf sh

# Acessar MongoDB
docker-compose exec mongodb mongosh -u admin -p admin123

# Reconstruir containers
docker-compose up -d --build
```

## 📊 MongoDB

### Acesso ao MongoDB

- **Host**: localhost
- **Porta**: 27017
- **Usuário**: admin
- **Senha**: admin123
- **Database**: hyperf_db

### Conectar via MongoDB Compass

```
mongodb://admin:admin123@localhost:27017/hyperf_db?authSource=admin
```

## 🔍 Desenvolvimento

### Estrutura de Resposta da API

Todas as respostas seguem o padrão:

```json
{
  "success": true,
  "message": "Mensagem descritiva",
  "data": { ... }
}
```

### Validações

O controller `UserController` implementa validações para:
- Nome: obrigatório, string, máximo 255 caracteres
- Email: obrigatório, formato válido de email, único
- Age: opcional, inteiro entre 0 e 150

### Status HTTP

- `200` - Sucesso
- `201` - Criado
- `400` - Requisição inválida
- `404` - Não encontrado
- `409` - Conflito (ex: email duplicado)
- `422` - Erro de validação
- `500` - Erro interno

## 📝 Notas

- Os dados são inicializados automaticamente pelo script `docker/mongodb/init/init.js`
- O Hyperf roda em modo de desenvolvimento por padrão
- Os logs são exibidos no terminal quando você usa `docker-compose logs -f`

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

MIT License - veja o arquivo LICENSE para detalhes.

## 👨‍💻 Autor

Desenvolvido com ❤️ usando Hyperf e MongoDB
