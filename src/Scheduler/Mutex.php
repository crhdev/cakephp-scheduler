<?php
declare(strict_types=1);

namespace CakeScheduler\Scheduler;

interface Mutex {
    public function add($key, $value, $expiresAt);
    public function delete($key);
}
