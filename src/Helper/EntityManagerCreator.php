<?php

namespace Alura\Doctrine\Helper;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Logging\Middleware;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;

class EntityManagerCreator
{
    public static function createEntityManager(): EntityManager
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            [
                __DIR__ . '/..'
            ],
            true,
        );
        $consoleOutput = new ConsoleOutput(ConsoleOutput::VERBOSITY_DEBUG);
        $consoleLogger = new ConsoleLogger($consoleOutput);
        $loggerMiddleware = new Middleware($consoleLogger);
        $config->setMiddlewares([$loggerMiddleware]);

        $cacheDirectory = __DIR__ . '/../../var/cache';
        $config->setMetadataCache(
            new PhpFilesAdapter(
                 namespace: 'metadata_cache',
                directory: $cacheDirectory,
            )
        );

        $config->setQueryCache(
            new PhpFilesAdapter(
                namespace: 'query_cache',
                directory: $cacheDirectory,
            )
        );

        $config->setResultCache(
            new PhpFilesAdapter(
                namespace: 'result_cache',
                directory: $cacheDirectory,
            )
        );

        $conn = [
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'post' => '3306',
            'dbname' => 'students',
            'user' => 'root',
            'password' => 'root',
        ];

        $connection = DriverManager::getConnection($conn, $config);

        return new EntityManager($connection, $config);
    }
}