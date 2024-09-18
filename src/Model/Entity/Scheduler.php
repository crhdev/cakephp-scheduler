<?php
declare(strict_types=1);

namespace CakeScheduler\Model\Entity;

use Cake\ORM\Entity;

/**
 * Scheduler Entity
 *
 * @property string $uniqid
 * @property string $command
 * @property string $frequency
 * @property \Cake\I18n\DateTime|null $last_run
 * @property \Cake\I18n\DateTime|null $next_run
 * @property bool|null $return_value
 * @property \Cake\I18n\DateTime|null $completed_at
 * @property string|null $failure_message
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 */
class Scheduler extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'uniqid' => true,
        'command' => true,
        'last_run' => true,
        'next_run' => true,
        'frequency' => true,
        'return_value' => true,
        'completed_at' => true,
        'failure_message' => true,
        'created' => true,
        'modified' => true,
    ];
}
