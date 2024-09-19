<?php
declare(strict_types=1);

namespace CakeScheduler;

use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Routing\RouteBuilder;
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
        $container->add(ScheduleCacheClearCommand::class)
            ->addArgument(Scheduler::class);
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
        return $commands
            ->add('schedule:cache_clear', ScheduleCacheClearCommand::class)
            ->add('schedule:run', ScheduleRunCommand::class)
            ->add('schedule:view', ScheduleViewCommand::class);
    }

    /**
     * Add routes for the plugin.
     *
     * If your plugin has many routes and you would like to isolate them into a separate file,
     * you can create `$plugin/config/routes.php` and delete this method.
     *
     * @param \Cake\Routing\RouteBuilder $routes The route builder to update.
     * @return void
     */
    public function routes(RouteBuilder $routes): void
    {
        $routes->plugin(
            'CakeScheduler',
            ['path' => '/cake-scheduler'],
            function (RouteBuilder $builder) {
                // Add custom routes here

                $builder->fallbacks();
            }
        );
        parent::routes($routes);
    }
}
