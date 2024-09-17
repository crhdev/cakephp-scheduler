<?php
declare(strict_types=1);

namespace CakeScheduler\Scheduler;

class RedisMutex implements  Mutex {
    private \Redis $redis;

    public function __construct()
    {
        $this->redis = new \Redis([
            'host' => 'redis',
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
    public function add($key, $value, $expiresAt = 1440)
    {
        return $this->redis->set($key, $value, ['nx', 'ex' => $expiresAt]);
    }

    public function delete($key)
    {
        return $this->redis->del($key);
    }
}
