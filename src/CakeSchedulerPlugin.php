<?php
declare(strict_types=1);

namespace CakeScheduler;

use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use CakeScheduler\Command\ScheduleCacheClearCommand;
use CakeScheduler\Command\ScheduleRunCommand;
use CakeScheduler\Command\ScheduleViewCommand;
use CakeScheduler\Scheduler\Scheduler;
use CakeScheduler\Listener\SchedulerListener;

class CakeSchedulerPlugin extends BasePlugin
{
    /**
     * Load all the plugin configuration and bootstrap logic.
     *
     * The host application is provided as an argument. This allows you to load
     * additional plugin dependencies, or attach events.
     *
     * @param \Cake\Core\PluginApplicationInterface $app The host application
     * @return void
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap($app);

        $app->getEventManager()->on(new SchedulerListener());
    }
    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(ScheduleRunCommand::class)
            ->addArgument(Scheduler::class);
        $container->add(ScheduleViewCommand::class)
            ->addArgument(Scheduler::class);
    }

    /**
     * @inheritDoc
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        return $commands->add('schedule:run', ScheduleRunCommand::class)
            ->add('schedule:view', ScheduleViewCommand::class);
    }
}
