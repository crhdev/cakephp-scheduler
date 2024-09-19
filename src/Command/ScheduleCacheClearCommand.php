<?php
declare(strict_types=1);

namespace CakeScheduler\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use CakeScheduler\Error\SchedulerStoppedException;
use CakeScheduler\Scheduler\Event;
use CakeScheduler\Scheduler\Scheduler;
use Throwable;

class ScheduleCacheClearCommand extends Command
{

    public function __construct(protected Scheduler $scheduler)
    {
    }

    /**
     * @param \Cake\Console\Arguments $args The args given to this command
     * @param \Cake\Console\ConsoleIo $io The io instance associated ot this command
     * @return int
     */
    public function execute(Arguments $args, ConsoleIo $io): int
    {
        if(!$this->scheduler->getMutex()) {
            $io->error('Mutex not enabled.');
            return self::CODE_ERROR;
        }

        $io->info('Cleanning cache');
        $mutex = $this->scheduler->getMutex();
        $mutex->clean();

        return self::CODE_SUCCESS;
    }
}
