<?php
declare(strict_types=1);

namespace CakeScheduler\Controller;

use App\Controller\AppController;
use CakeScheduler\Model\Table\SchedulersTable;

class SchedulerController extends AppController
{
    public $Schedulers;

    public function initialize(): void
    {
        $this->Schedulers = $this->fetchTable(SchedulersTable::class);
        parent::initialize();
    }

    public function index(): void
    {
        $schedulers = $this->paginate($this->Schedulers);
        $this->set(compact('schedulers'));
    }
}
