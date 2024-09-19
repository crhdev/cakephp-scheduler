# CakePHP Scheduler Plugin
## This fork contains some useful features taken from Laravel's scheduler. 
For installation/configuration please referer to original [README](https://github.com/LordSimal/cakephp-scheduler/blob/1.x/README.md)

- Preventing Task Overlaps.
Behind the scenes, the withoutOverlapping method utilizes redis cache to obtain locks. Useful for multiserver apps.
  ```php
        $scheduler->setMutex(new RedisMutex([
            'host' => 'redis',
        ]));
  
          $scheduler
            ->execute(SlowerCommand::class)
            ->everyXMinutes(1)
            ->withUniqId('my-slower-command')
            ->withoutOverlapping();
  ```

  - Save stastistics to database
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

  
## Credit where credit is due
This plugin is heavily inspired by the [Laravel Task Scheduling Feature](https://laravel.com/docs/10.x/scheduling)

## License
The plugin is available as open source under the terms of the [MIT License](https://github.com/lordsimal/cakephp-scheduler/blob/main/LICENSE).
