<?php
declare(strict_types=1);

namespace CakeScheduler\Error;

use RuntimeException;

class SchedulerWithoutOverlappingException extends RuntimeException
{
}
