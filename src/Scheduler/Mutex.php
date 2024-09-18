<?php
declare(strict_types=1);

namespace CakeScheduler\Scheduler;

interface Mutex {
    public function add(string $key, $value, int $expiresAt);
    public function delete(string $key);
}
