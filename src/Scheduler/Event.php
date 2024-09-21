<?php
declare(strict_types=1);

namespace CakeScheduler\Scheduler;

use Cake\Cache\Cache;
use Cake\Chronos\Chronos;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use CakeScheduler\Error\SchedulerWithoutUniqueIdException;
use CakeScheduler\Scheduler\Traits\FrequenciesTrait;
use Cron\CronExpression;

class Event
{
    use FrequenciesTrait;

    protected ConsoleIo $io;

    public const SUNDAY = 0;
    public const MONDAY = 1;
    public const TUESDAY = 2;
    public const WEDNESDAY = 3;
    public const THURSDAY = 4;
    public const FRIDAY = 5;
    public const SATURDAY = 6;

    /**
     * The uniqid for this event.
     *
     * @var null|string
     */
    private ?string $uniqId = null;

    /**
     * Indicates if the command should not overlap itself.
     *
     * @var bool
     */
    public $withoutOverlapping = false;

    /**
     * The number of minutes the cache should be valid.
     *
     * @var int
     */
    public $expiresAt = 1440;

    /**
     * @var bool
     */
    public bool $enableStatistics = false;

    /**
     * @var null
     */
    private $startedAt = null;

    /**
     * @var null
     */
    private $finishedAt = null;

    public ?string $description = null;

    /**
     * The array of callbacks to be run before the event is started.
     *
     * @var array
     */
    protected array $beforeCallbacks = [];

    /**
     * The array of callbacks to be run after the event is finished.
     *
     * @var array
     */
    protected array $afterCallbacks = [];

    /**
     * @param \Cake\Console\CommandInterface $command The command object related to this event
     * @param array $args Args which should be passed to the command
     */
    public function __construct(
        protected CommandInterface $command,
        protected array $args = [],
        protected ?Scheduler $scheduler = null
    ) {}

    /**
     * @return bool
     */
    public function isDue(): bool
    {
        $dateTime = new Chronos();

        return (new CronExpression($this->expression))->isDue($dateTime->toDateTimeString());
    }

    /**
     * @param \Cake\Console\ConsoleIo $io The IO instance from the schedule:run command
     * @return int|null
     */
    public function run(ConsoleIo $io): ?int
    {
        if ($this->shouldSkipDueToOverlapping()) {
            $io->error(sprintf('Another instance of [%s] is running', get_class($this->command)));
            return 0;
        }

        $this->startedAt = date('Y-m-d H:i:s');

        $this->scheduler->dispatchEvent('CakeScheduler.beforeExecute', ['event' => $this]);

        $io->info(sprintf('Executing [%s]', get_class($this->command)));

        foreach($this->beforeCallbacks as $beforeCallback) {
            $beforeCallback($io);
        }

        $result = $this->command->run($this->args, $io);

        $this->finishedAt = date('Y-m-d H:i:s');

        $this->removeMutex();

        foreach($this->afterCallbacks as $afterCallback) {
            $afterCallback($io);
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * @return \Cake\Console\CommandInterface
     */
    public function getCommand(): CommandInterface
    {
        return $this->command;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @return string|null
     */
    public function getUniqId(): ?string
    {
        return $this->uniqId;
    }

    public function getStartedAt()
    {
        return $this->startedAt;
    }

    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * Determine if the event should skip because another process is overlapping.
     *
     * @return bool
     */
    public function shouldSkipDueToOverlapping(): bool
    {
        if($this->withoutOverlapping) {
            return !$this->scheduler->getMutex()->add($this->uniqId, 1, $this->expiresAt);
        }

        return false;
    }

    /**
     * @param string $uniqId
     * @return $this
     */
    public function withUniqId(string $uniqId): self
    {
        $this->uniqId = $uniqId;
        return $this;
    }

    /**
     * @return self
     */
    public function enableStatistics()
    {
        if($this->uniqId === null) {
            throw new SchedulerWithoutUniqueIdException(
                "A scheduled event id is required for statistics. Use the 'withUniqId' method before 'enableStatistics'."
            );
        }

        $this->enableStatistics = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStatisticsEnabled(): bool
    {
        return $this->enableStatistics === true;
    }

    /**
     * @param int $expiresAt Specify how many seconds must pass before the "without overlapping" lock expires.
     * By default, the lock will expire after 24 hours:
     * @return void
     */
    public function withoutOverlapping(int $expiresAt = 86400)
    {
        if($this->uniqId === null) {
            throw new SchedulerWithoutUniqueIdException(
                "A scheduled event id is required to prevent overlapping. Use the 'withUniqId' method before 'withoutOverlapping'."
            );
        }

        $this->withoutOverlapping = true;

        $this->expiresAt = $expiresAt;
    }

    /**
     * @param $description
     * @return $this
     */
    public function description($description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function addBeforeCallback(callable $callback): self
    {
        array_push($this->beforeCallbacks, $callback);
        return $this;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function addAfterCallback(callable $callback): self
    {
        array_push($this->afterCallbacks, $callback);
        return $this;
    }

    /**
     * Register a callback to ping a given URL before the job runs.
     *
     * @param  string  $url
     * @return $this
     */
    public function pingBefore(string $url)
    {
        return $this->addBeforeCallback($this->pingCallback($url));
    }

    /**
     * Get the callback that pings the given URL.
     *
     * @param  string  $url
     * @return \Closure
     */
    protected function pingCallback($url)
    {
        return function () use ($url) {
            $http = new \Cake\Http\Client();
            return $http->get($url);
        };
    }

    /**
     * Delete the mutex for the event.
     *
     * @return void
     */
    protected function removeMutex(): void
    {
        if ($this->withoutOverlapping) {
           $this->scheduler->getMutex()->delete($this->uniqId);
        }
    }
}
