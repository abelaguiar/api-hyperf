<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Annotation\AutoController;

#[AutoController]
class IndexController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $data = [
            'message' => 'Bem-vindo à API Hyperf com MongoDB!',
            'version' => '1.0.0',
            'timestamp' => date('Y-m-d H:i:s'),
            'endpoints' => [
                'GET /api/users' => 'Listar todos os usuários',
                'GET /api/users/{id}' => 'Obter um usuário específico',
                'POST /api/users' => 'Criar novo usuário',
                'PUT /api/users/{id}' => 'Atualizar usuário',
                'DELETE /api/users/{id}' => 'Deletar usuário',
            ]
        ];

        return $response->json($data);
    }
}
