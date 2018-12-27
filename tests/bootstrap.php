<?php declare(strict_types=1);

namespace HarmonyIO\CacheTest;

require_once __DIR__ . '/../vendor/autoload.php';

define('REDIS_ADDRESS', $_ENV['redisAddress'] ?? 'tcp://127.0.0.1:6379');
