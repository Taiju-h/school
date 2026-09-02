<div class="eschedules index">
	<h2><?php echo __('Eschedules'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('mryoukin_id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('date1'); ?></th>
			<th><?php echo $this->Paginator->sort('date2'); ?></th>
			<th><?php echo $this->Paginator->sort('date3'); ?></th>
			<th><?php echo $this->Paginator->sort('deadline'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('upddateid'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($eschedules as $eschedule): ?>
	<tr>
		<td><?php echo h($eschedule['Eschedule']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($eschedule['Mryoukin']['name'], array('controller' => 'mryoukins', 'action' => 'view', $eschedule['Mryoukin']['id'])); ?>
		</td>
		<td><?php echo h($eschedule['Eschedule']['name']); ?>&nbsp;</td>
		<td><?php echo h($eschedule['Eschedule']['date1']); ?>&nbsp;</td>
		<td><?php echo h($eschedule['Eschedule']['date2']); ?>&nbsp;</td>
		<td><?php echo h($eschedule['Eschedule']['date3']); ?>&nbsp;</td>
		<td><?php echo h($eschedule['Eschedule']['deadline']); ?>&nbsp;</td>
		<td><?php echo h($eschedule['Eschedule']['created']); ?>&nbsp;</td>
		<td><?php echo h($eschedule['Eschedule']['modified']); ?>&nbsp;</td>
		<td><?php echo h($eschedule['Eschedule']['upddateid']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $eschedule['Eschedule']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $eschedule['Eschedule']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $eschedule['Eschedule']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $eschedule['Eschedule']['id']))); ?>
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
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Eschedule'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Mryoukins'), array('controller' => 'mryoukins', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mryoukin'), array('controller' => 'mryoukins', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Msubscriptions'), array('controller' => 'msubscriptions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Msubscription'), array('controller' => 'msubscriptions', 'action' => 'add')); ?> </li>
	</ul>
</div>
