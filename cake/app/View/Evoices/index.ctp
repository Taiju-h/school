<div class="Evoice index">
	<h2><?php echo __('生徒の感想'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('mryoukin_id','講座名'); ?></th>
			<th><?php echo $this->Paginator->sort('nicname','ニックネーム'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
			<th><?php echo $this->Paginator->sort('impressions','感想'); ?></th>
			<th><?php echo $this->Paginator->sort('modified','更新日'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($evoices as $evoice): ?>
	<tr>
		<td><?php echo h($evoice['Mryoukin']['rname']); ?>&nbsp;</td>
		<td><?php echo h($evoice['Evoice']['nicname']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('変更'), array('action' => 'add', $evoice['Evoice']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $evoice['Evoice']['id']), array('confirm' => __('Are you sure you want to delete # %s?',$evoice['Evoice']['nicname']))); ?>
		</td>
		<td><?php echo h(mb_substr($evoice['Evoice']['impressions'],0,20)); ?>&nbsp;</td>
		<td><?php echo h($evoice['Evoice']['modified']); ?>&nbsp;</td>
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