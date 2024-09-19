<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\Cake\Datasource\EntityInterface> $schedulers
 */
?>
<div class="schedulers index content">
    <?= $this->Html->link(__('New Scheduler'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Schedulers') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('uniqid') ?></th>
                    <th><?= $this->Paginator->sort('command') ?></th>
                    <th><?= $this->Paginator->sort('frequency') ?></th>
                    <th><?= $this->Paginator->sort('last_run') ?></th>
                    <th><?= $this->Paginator->sort('next_run') ?></th>
                    <th><?= $this->Paginator->sort('return_value') ?></th>
                    <th><?= $this->Paginator->sort('completed_at') ?></th>
                    <th><?= $this->Paginator->sort('failure_message') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedulers as $scheduler): ?>
                <tr>
                    <td><?= h($scheduler->uniqid) ?></td>
                    <td><?= h($scheduler->command) ?></td>
                    <td><?= h($scheduler->frequency) ?></td>
                    <td><?= h($scheduler->last_run) ?></td>
                    <td><?= h($scheduler->next_run) ?></td>
                    <td><?= h($scheduler->return_value) ?></td>
                    <td><?= h($scheduler->completed_at) ?></td>
                    <td><?= h($scheduler->failure_message) ?></td>
                    <td><?= h($scheduler->created) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $scheduler->uniqid]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
