<?php

declare(strict_types=1);

namespace App\Service;

use MongoDB\Client;
use MongoDB\Database;
use Hyperf\Contract\ConfigInterface;

class MongoDBService
{
    protected Client $client;
    protected Database $database;

    public function __construct(ConfigInterface $config)
    {
        $mongoConfig = $config->get('mongodb');
        
        $uri = sprintf(
            'mongodb://%s:%s@%s:%s/%s?authSource=%s',
            $mongoConfig['username'],
            $mongoConfig['password'],
            $mongoConfig['host'],
            $mongoConfig['port'],
            $mongoConfig['database'],
            $mongoConfig['auth_source'] ?? 'admin'
        );

        $this->client = new Client($uri);
        $this->database = $this->client->selectDatabase($mongoConfig['database']);
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function getCollection(string $collectionName)
    {
        return $this->database->selectCollection($collectionName);
    }
}
