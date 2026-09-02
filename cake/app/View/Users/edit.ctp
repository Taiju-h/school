<div class="users index">
<h2>ユーザ管理</h2>
<h3>ユーザ><?php echo empty($this->data['User']['id']) ? '追加' : '編集'; ?></h3>

<?php echo $this->Form->create('User'); ?>
<?php echo empty($this->data['User']['id']) ? null : $this->Form->input('id', array('type' => 'hidden')); ?>

<div class="input">
<p>ID</p>
<p><?php echo empty($this->data['User']['id']) ? '(新規)' : h($this->data['User']['id']); ?></p>
</div>

<?php echo $this->Form->input('username'); 

	 echo $this->Form->input('password'); 

 echo $this->Form->end(empty($this->data['User']['id']) ? '　追加　' : '　編集　'); ?>
	<h2><?php echo __('Users'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo "名　前" ?></th>
			<th><?php echo $this->Paginator->sort('id','ＩＤ'); ?></th>
			<th><?php echo $this->Paginator->sort('username', 'ログインＩＤ'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($users as $user): ?>
	<tr>
		<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['dummy']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['created']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $user['User']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $user['User']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete # %s?', $user['User']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<?php echo $this->Element('left'); ?>
