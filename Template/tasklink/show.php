<?php if (! empty($links['is a milestone of'])): ?>
<div id="milestone" class="task-show-section">
<div class="page-header">
    <h2><?= t('Milestone') ?></h2>
</div>
<table class="subtasks-table">
    <thead>
    <tr>
        <th class="column-40" colspan="2"><?= t('Title') ?></th>
        <th class="column-25"><?= t('Assignee') ?></th>
        <th><?= t('Time tracking') ?></th>
        <?php if ($editable): ?>
            <th class="column-5"></th>
        <?php endif ?>
    </tr>
    </thead>
    <tbody>
    <?php $total_time_spent = 0; ?>
    <?php $total_time_estimated = 0; ?>
    <?php $total_time_spent_cumul = 0; ?>
    <?php foreach ($links['is a milestone of'] as $link): ?>
    <?php $total_time_spent += $link['task_time_spent']; ?>
    <?php $total_time_estimated += $link['task_time_estimated']; ?>
    <?php $total_time_spent_cumul += min($link['task_time_spent'], $link['task_time_estimated']); ?>
    <tr>
        <td>
            <div class="task-board color-<?= $link['color_id'] ?>">
                <?php if ($editable): ?>
                    <div class="task-board-collapsed<?= ($link['is_active'] ? '' : ' task-link-closed') ?>">
                    <?= $this->render('board/task_menu', array('task' => array('id' => $link['task_id'], 'project_id' => $link['project_id'], 'is_active' => $link['is_active']), 'redirect' => $task['id'])) ?>
                        <?= $this->url->link(
                            $this->e($link['title']),
                            'task',
                            'show',
                            array('task_id' => $link['task_id'], 'project_id' => $link['project_id']),
                            false,
                            'task-board-collapsed-title'
                        ) ?>
                    </div>
                <?php else: ?>
                    <div class="task-board-collapsed<?= ($link['is_active'] ? '' : ' task-link-closed') ?>">
                        <?= $this->url->link(
                            $this->e('#'.$link['task_id'].' '.$link['title']),
                            'task',
                            'readonly',
                            array('task_id' => $link['task_id'], 'token' => $project['token']),
                            false,
                            'task-board-collapsed-title'
                        ) ?>
                    </div>
                <?php endif ?>
            </div>
        </td>
        <td><?= $this->e($link['column_title']) ?></td>
        <td>
            <?php if (! empty($link['task_assignee_username'])): ?>
                <?php if ($editable): ?>
                    <?= $this->url->link($this->e($link['task_assignee_name'] ?: $link['task_assignee_username']), 'user', 'show', array('user_id' => $link['task_assignee_id'])) ?>
                <?php else: ?>
                    <?= $this->e($link['task_assignee_name'] ?: $link['task_assignee_username']) ?>
                <?php endif ?>
            <?php endif ?>
        </td>
        <td>
            <?php if (! empty($link['task_time_spent'])): ?>
                <strong><?= $this->e($link['task_time_spent']).'h' ?></strong> <?= t('spent') ?>
            <?php endif ?>

            <?php if (! empty($link['task_time_estimated'])): ?>
                <strong><?= $this->e($link['task_time_estimated']).'h' ?></strong> <?= t('estimated') ?>
            <?php endif ?>
        </td>
        <?php if ($editable): ?>
        <td>
            <div class="dropdown">
            <a href="#" class="dropdown-menu dropdown-menu-link-icon"><i class="fa fa-cog fa-fw"></i><i class="fa fa-caret-down"></i></a>
            <ul>
                <li><?= $this->url->link(t('Edit'), 'tasklink', 'edit', array('link_id' => $link['id'], 'task_id' => $task['id'], 'project_id' => $task['project_id'])) ?></li>
                <li><?= $this->url->link(t('Remove'), 'tasklink', 'confirm', array('link_id' => $link['id'], 'task_id' => $task['id'], 'project_id' => $task['project_id'])) ?></li>
            </ul>
            </div>
        </td>
        <?php endif ?>
    </tr>
    <?php endforeach ?>
    </tbody>
    <?php if (! empty($total_time_spent) || ! empty($total_time_estimated)): ?>
    <tfoot>
    <tr>
        <th colspan="3" class="total"><?= t('Total time tracking') ?></th>
        <td<?php if (! isset($not_editable)): ?> colspan="2"<?php endif ?>>
            <?php if (! empty($total_time_spent)): ?>
                <strong><?= $this->e($total_time_spent).'h' ?></strong> <?= t('spent') ?>
            <?php endif ?>

            <?php if (! empty($total_time_estimated)): ?>
                <strong><?= $this->e($total_time_estimated).'h' ?></strong> <?= t('estimated') ?>
            <?php endif ?>

            <?php if (! empty($total_time_spent) && ! empty($total_time_estimated)): ?>
                <strong><?= $this->e($total_time_estimated-$total_time_spent).'h' ?></strong> <?= t('remaining') ?>
            <?php endif ?>
            
            <div class="progress-bar">
                <?php $percentage = round($total_time_spent_cumul/$total_time_estimated*100.0); ?>
                <div class="progress color-<?= $task['color_id'] ?>" style="width:<?= $percentage ?>%;">
                    <?= $percentage ?>%
                </div>
            </div>
        </td>
    </tr>
    </tfoot>
    <?php endif ?>
</table>


<?php if ($editable && isset($link_label_list)): ?>
    <form action="<?= $this->url->href('tasklink', 'save', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>" method="post" autocomplete="off">

        <?= $this->form->csrf() ?>
        <?= $this->form->hidden('task_id', array('task_id' => $task['id'])) ?>
        <?= $this->form->hidden('opposite_task_id', array()) ?>
        <?= $this->form->hidden('link_id', array('link_id' => 9)) ?>

        <?= $this->form->text(
            'title',
            array(),
            array(),
            array(
                'required',
                'placeholder="'.t('Start to type task title...').'"',
                'title="'.t('Start to type task title...').'"',
                'data-dst-field="opposite_task_id"',
                'data-search-url="'.$this->url->href('TaskHelper', 'autocomplete', array('exclude_task_id' => $task['id'])).'"',
            ),
            'autocomplete') ?>

        <input type="submit" value="<?= t('Add') ?>" class="btn btn-blue"/>
    </form>
<?php endif ?>

</div>
<?php endif ?>
