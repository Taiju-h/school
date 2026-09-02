<div class="mkbn3s index">
	<h2><?php echo __('小区分一覧'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('name','区分名'); ?></th>
			<th><?php echo $this->Paginator->sort('dname', '学習の部屋表示名'); ?></th>
			<th><?php echo $this->Paginator->sort('oder', '表示順'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($mkbn3s as $mkbn3): ?>
	<tr>
		<td><?php echo h($mkbn3['Mkbn3']['name']); ?>&nbsp;</td>
		<td><?php echo h($mkbn3['Mkbn3']['dname']); ?>&nbsp;</td>
		<td><?php echo h($mkbn3['Mkbn3']['oder']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $mkbn3['Mkbn3']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $mkbn3['Mkbn3']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $mkbn3['Mkbn3']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $mkbn3['Mkbn3']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
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