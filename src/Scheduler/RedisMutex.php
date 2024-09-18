<?php
declare(strict_types=1);

namespace CakeScheduler\Scheduler;

use JetBrains\PhpStorm\ArrayShape;

class RedisMutex implements  Mutex {
    private \Redis $redis;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(#[ArrayShape([
        'host' => 'string',
        'port' => 'int',
    ])] $config = [])
    {
        $defaultConfig = $config + ['host' => '127.0.0.1', 'port' => 6379];
        $this->redis = new \Redis([
            'host' => $defaultConfig['host'],
            'port' => 6379,
        ]);
    }

    /**
     * @param $key
     * @param $value
     * @param $expiresAt
     * @return bool|\Redis
     * @throws \RedisException
     */
    public function add(string $key, $value, int $expiresAt = 1440)
    {
        return $this->redis->set($key, $value, ['nx', 'ex' => $expiresAt]);
    }

    public function delete(string $key)
    {
        return $this->redis->del($key);
    }
}
