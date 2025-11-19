<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\MongoDBService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Annotation\AutoController;
use MongoDB\BSON\ObjectId;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

#[AutoController]
class UserController
{
    public function __construct(
        private MongoDBService $mongoDBService,
        private ValidatorFactoryInterface $validatorFactory
    ) {}

    public function index(ResponseInterface $response)
    {
        try {
            $collection = $this->mongoDBService->getCollection('users');
            $users = $collection->find([], ['limit' => 100])->toArray();
            
            $result = array_map(function($user) {
                return [
                    'id' => (string) $user['_id'],
                    'name' => $user['name'] ?? '',
                    'email' => $user['email'] ?? '',
                    'age' => $user['age'] ?? null,
                    'created_at' => isset($user['created_at']) ? $user['created_at']->toDateTime()->format('Y-m-d H:i:s') : null,
                    'updated_at' => isset($user['updated_at']) ? $user['updated_at']->toDateTime()->format('Y-m-d H:i:s') : null,
                ];
            }, $users);

            return $response->json([
                'success' => true,
                'data' => $result,
                'total' => count($result)
            ]);
        } catch (\Exception $e) {
            return $response->json([
                'success' => false,
                'message' => 'Erro ao listar usuários: ' . $e->getMessage()
            ])->withStatus(500);
        }
    }

    public function show(string $id, ResponseInterface $response)
    {
        try {
            if (!ObjectId::isValid($id)) {
                return $response->json([
                    'success' => false,
                    'message' => 'ID inválido'
                ])->withStatus(400);
            }

            $collection = $this->mongoDBService->getCollection('users');
            $user = $collection->findOne(['_id' => new ObjectId($id)]);

            if (!$user) {
                return $response->json([
                    'success' => false,
                    'message' => 'Usuário não encontrado'
                ])->withStatus(404);
            }

            $result = [
                'id' => (string) $user['_id'],
                'name' => $user['name'] ?? '',
                'email' => $user['email'] ?? '',
                'age' => $user['age'] ?? null,
                'created_at' => isset($user['created_at']) ? $user['created_at']->toDateTime()->format('Y-m-d H:i:s') : null,
                'updated_at' => isset($user['updated_at']) ? $user['updated_at']->toDateTime()->format('Y-m-d H:i:s') : null,
            ];

            return $response->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return $response->json([
                'success' => false,
                'message' => 'Erro ao buscar usuário: ' . $e->getMessage()
            ])->withStatus(500);
        }
    }

    public function store(RequestInterface $request, ResponseInterface $response)
    {
        try {
            $validator = $this->validatorFactory->make(
                $request->all(),
                [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'age' => 'nullable|integer|min:0|max:150'
                ],
                [
                    'name.required' => 'O nome é obrigatório',
                    'email.required' => 'O email é obrigatório',
                    'email.email' => 'Email inválido',
                ]
            );

            if ($validator->fails()) {
                return $response->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ])->withStatus(422);
            }

            $data = $validator->validated();
            $collection = $this->mongoDBService->getCollection('users');

            // Verificar se email já existe
            $existingUser = $collection->findOne(['email' => $data['email']]);
            if ($existingUser) {
                return $response->json([
                    'success' => false,
                    'message' => 'Email já cadastrado'
                ])->withStatus(409);
            }

            $data['created_at'] = new \MongoDB\BSON\UTCDateTime();
            $data['updated_at'] = new \MongoDB\BSON\UTCDateTime();

            $result = $collection->insertOne($data);

            return $response->json([
                'success' => true,
                'message' => 'Usuário criado com sucesso',
                'data' => [
                    'id' => (string) $result->getInsertedId(),
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'age' => $data['age'] ?? null,
                ]
            ])->withStatus(201);
        } catch (\Exception $e) {
            return $response->json([
                'success' => false,
                'message' => 'Erro ao criar usuário: ' . $e->getMessage()
            ])->withStatus(500);
        }
    }

    public function update(string $id, RequestInterface $request, ResponseInterface $response)
    {
        try {
            if (!ObjectId::isValid($id)) {
                return $response->json([
                    'success' => false,
                    'message' => 'ID inválido'
                ])->withStatus(400);
            }

            $validator = $this->validatorFactory->make(
                $request->all(),
                [
                    'name' => 'sometimes|required|string|max:255',
                    'email' => 'sometimes|required|email|max:255',
                    'age' => 'nullable|integer|min:0|max:150'
                ]
            );

            if ($validator->fails()) {
                return $response->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ])->withStatus(422);
            }

            $collection = $this->mongoDBService->getCollection('users');
            $data = $validator->validated();

            // Verificar se email já existe em outro usuário
            if (isset($data['email'])) {
                $existingUser = $collection->findOne([
                    'email' => $data['email'],
                    '_id' => ['$ne' => new ObjectId($id)]
                ]);
                if ($existingUser) {
                    return $response->json([
                        'success' => false,
                        'message' => 'Email já cadastrado'
                    ])->withStatus(409);
                }
            }

            $data['updated_at'] = new \MongoDB\BSON\UTCDateTime();

            $result = $collection->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => $data]
            );

            if ($result->getMatchedCount() === 0) {
                return $response->json([
                    'success' => false,
                    'message' => 'Usuário não encontrado'
                ])->withStatus(404);
            }

            return $response->json([
                'success' => true,
                'message' => 'Usuário atualizado com sucesso'
            ]);
        } catch (\Exception $e) {
            return $response->json([
                'success' => false,
                'message' => 'Erro ao atualizar usuário: ' . $e->getMessage()
            ])->withStatus(500);
        }
    }

    public function delete(string $id, ResponseInterface $response)
    {
        try {
            if (!ObjectId::isValid($id)) {
                return $response->json([
                    'success' => false,
                    'message' => 'ID inválido'
                ])->withStatus(400);
            }

            $collection = $this->mongoDBService->getCollection('users');
            $result = $collection->deleteOne(['_id' => new ObjectId($id)]);

            if ($result->getDeletedCount() === 0) {
                return $response->json([
                    'success' => false,
                    'message' => 'Usuário não encontrado'
                ])->withStatus(404);
            }

            return $response->json([
                'success' => true,
                'message' => 'Usuário deletado com sucesso'
            ]);
        } catch (\Exception $e) {
            return $response->json([
                'success' => false,
                'message' => 'Erro ao deletar usuário: ' . $e->getMessage()
            ])->withStatus(500);
        }
    }
}
