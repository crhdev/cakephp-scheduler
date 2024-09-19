# CakePHP Scheduler Plugin
## This fork contains some useful features taken from Laravel's scheduler. 
For installation/configuration please referer to original [README](https://github.com/LordSimal/cakephp-scheduler/blob/1.x/README.md)

### Preventing Task Overlaps.
Behind the scenes, the withoutOverlapping method utilizes redis cache to obtain locks. 
You can create your own mutex implementing \CakeScheduler\Scheduler\Mutex interface.
Useful for multiserver apps too.
  ```php
$scheduler->setMutex(new RedisMutex([
    'host' => 'redis',
    'prefix' => 'schr-' // default to 'scheduler-'
]));

  $scheduler
    ->execute(SlowerCommand::class)
    ->everyXMinutes(1)
    ->withUniqId('my-slower-command')
    ->withoutOverlapping(); // default to 24hrs

  $scheduler
    ->execute(SlowerCommand::class)
    ->everyXMinutes(1)
    ->withUniqId('my-slower-command')
    ->withoutOverlapping(10); // set to 10 secs
  ```

  ### Save stastistics to database.
   ```bash
  ./bin/cake migrations migrate -p CakeScheduler
  ```
  
  ```php
$scheduler
  ->execute(SlowerCommand::class)
  ->everyXMinutes(1)
  ->withUniqId('my-slower-command')
  ->enableStatistics();
  ```

### Accesing to statistics
Head your browser to: http://127.0.0.1/cake-scheduler/scheduler

  
## Credit where credit is due
This plugin is heavily inspired by the [Laravel Task Scheduling Feature](https://laravel.com/docs/10.x/scheduling)

## License
The plugin is available as open source under the terms of the [MIT License](https://github.com/lordsimal/cakephp-scheduler/blob/main/LICENSE).
