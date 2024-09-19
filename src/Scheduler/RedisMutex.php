<?php
declare(strict_types=1);

namespace CakeScheduler\Scheduler;

use JetBrains\PhpStorm\ArrayShape;

class RedisMutex implements  Mutex {
    private \Redis $redis;

    /**
     * @var array|mixed[]
     */
    private $config;
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(#[ArrayShape([
        'host' => 'string',
        'port' => 'int',
        'prefix' => 'string'
    ])] $config = [])
    {
        $defaultConfig = $config + ['host' => '127.0.0.1', 'port' => 6379, 'prefix' => 'scheduler-'];

        $this->config = $defaultConfig;

        $this->redis = new \Redis([
            'host' => $this->config['host'],
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
        $key = $this->_key($key);
        return $this->redis->set($key, $value, ['nx', 'ex' => $expiresAt]);
    }

    /**
     * @param string $key
     * @return false|int|\Redis
     * @throws \RedisException
     */
    public function delete(string $key)
    {
        $key = $this->_key($key);
        return $this->redis->del($key);
    }

    /**
     * @param string $key
     * @return string
     */
    private function _key(string $key): string
    {
        return $this->config['prefix'] . $key;
    }
}
