<?php
declare(strict_types=1);

namespace CakeScheduler\Listener;

use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use CakeScheduler\Scheduler\Event as SchedulerEvent;
use Cron\CronExpression;

class SchedulerListener implements EventListenerInterface
{
    private $conn;

    public function __construct() {
        $this->conn = ConnectionManager::get('default');
    }

    public function implementedEvents(): array
    {
        return [
            'CakeScheduler.beforeExecute' => 'beforeExecute',
            'CakeScheduler.afterExecute' => 'afterExecute',
            'CakeScheduler.errorExecute' => 'errorExecute',
        ];
    }

    /**
     * @param Event $event
     * @param SchedulerEvent $schedulerEvent
     * @param \Exception $error
     * @param $time
     * @return void
     */
    public function errorExecute(Event $event, SchedulerEvent $schedulerEvent, \Exception $error)
    {
        if(!$schedulerEvent->isStatisticsEnabled()) {
            return;
        }

        $command = $schedulerEvent->getCommand()::class;
        $frequency = $schedulerEvent->getExpression();
        $cron = new CronExpression($frequency);

        $this->conn->updateQuery(
            'schedulers',
            [
                'return_value' => $result,
                'completed_at' => $schedulerEvent->getFinishedAt(),
                'next_run' => $cron->getNextRunDate()->format('Y-m-d H:i:s'),
                'failure_message' => $error->getMessage(),
            ],
            [
                'uniqid' => $schedulerEvent->getUniqId()
            ])->execute();
    }

    /**
     * @param Event $event
     * @param SchedulerEvent $schedulerEvent
     * @param $result
     * @param $time
     * @return void
     * @throws \Exception
     */
    public function afterExecute(Event $event, SchedulerEvent $schedulerEvent, $result)
    {
        if(!$schedulerEvent->isStatisticsEnabled()) {
            return;
        }

        $command = $schedulerEvent->getCommand()::class;
        $frequency = $schedulerEvent->getExpression();
        $cron = new CronExpression($frequency);

        $this->conn->updateQuery(
            'schedulers',
            [
                'return_value' => $result,
                'completed_at' => $schedulerEvent->getFinishedAt(),
                'next_run' => $cron->getNextRunDate()->format('Y-m-d H:i:s'),
            ],
            [
                'uniqid' => $schedulerEvent->getUniqId()
            ])->execute();
    }

    /**
     * @param Event $event
     * @param SchedulerEvent $schedulerEvent
     * @return void
     */
    public function beforeExecute(Event $event, SchedulerEvent $schedulerEvent)
    {
        if(!$schedulerEvent->isStatisticsEnabled()) {
            return;
        }

        $command = $schedulerEvent->getCommand()::class;
        $frequency = $schedulerEvent->getExpression();

        $this->conn->insertQuery('schedulers', [
            'uniqid' => $schedulerEvent->getUniqId(),
            'command' => $command,
            'frequency' => $frequency,
            'last_run' => $schedulerEvent->getStartedAt(),
            'created' => date('Y-m-d H:i:s'),
        ])->epilog('ON DUPLICATE KEY UPDATE last_run=VALUES(`last_run`)')->execute();
    }
}
