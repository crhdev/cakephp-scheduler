<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $scheduler
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Scheduler'), ['action' => 'edit', $scheduler->uniqid], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Scheduler'), ['action' => 'delete', $scheduler->uniqid], ['confirm' => __('Are you sure you want to delete # {0}?', $scheduler->uniqid), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Schedulers'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Scheduler'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="schedulers view content">
            <h3><?= h($scheduler->frequency) ?></h3>
            <table>
                <tr>
                    <th><?= __('Uniqid') ?></th>
                    <td><?= h($scheduler->uniqid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Command') ?></th>
                    <td><?= h($scheduler->command) ?></td>
                </tr>
                <tr>
                    <th><?= __('Frequency') ?></th>
                    <td><?= h($scheduler->frequency) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Run') ?></th>
                    <td><?= h($scheduler->last_run) ?></td>
                </tr>
                <tr>
                    <th><?= __('Next Run') ?></th>
                    <td><?= h($scheduler->next_run) ?></td>
                </tr>
                <tr>
                    <th><?= __('Completed At') ?></th>
                    <td><?= h($scheduler->completed_at) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($scheduler->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Return Value') ?></th>
                    <td><?= $scheduler->return_value ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Failure Message') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($scheduler->failure_message)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>