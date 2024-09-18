<?php
declare(strict_types=1);

namespace CakeScheduler\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Schedulers Model
 *
 * @method \SchedulerMonitor\Model\Entity\Scheduler newEmptyEntity()
 * @method \SchedulerMonitor\Model\Entity\Scheduler newEntity(array $data, array $options = [])
 * @method array<\SchedulerMonitor\Model\Entity\Scheduler> newEntities(array $data, array $options = [])
 * @method \SchedulerMonitor\Model\Entity\Scheduler get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \SchedulerMonitor\Model\Entity\Scheduler findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \SchedulerMonitor\Model\Entity\Scheduler patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\SchedulerMonitor\Model\Entity\Scheduler> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \SchedulerMonitor\Model\Entity\Scheduler|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \SchedulerMonitor\Model\Entity\Scheduler saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\SchedulerMonitor\Model\Entity\Scheduler>|\Cake\Datasource\ResultSetInterface<\SchedulerMonitor\Model\Entity\Scheduler>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\SchedulerMonitor\Model\Entity\Scheduler>|\Cake\Datasource\ResultSetInterface<\SchedulerMonitor\Model\Entity\Scheduler> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\SchedulerMonitor\Model\Entity\Scheduler>|\Cake\Datasource\ResultSetInterface<\SchedulerMonitor\Model\Entity\Scheduler>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\SchedulerMonitor\Model\Entity\Scheduler>|\Cake\Datasource\ResultSetInterface<\SchedulerMonitor\Model\Entity\Scheduler> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SchedulersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('schedulers');
        $this->setDisplayField('frequency');
        $this->setPrimaryKey('uniqid');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('frequency')
            ->maxLength('frequency', 140)
            ->requirePresence('frequency', 'create')
            ->notEmptyString('frequency');

        $validator
            ->dateTime('last_run')
            ->allowEmptyDateTime('last_run');

        return $validator;
    }
}
