<div class="mryoukins index">
	<h2><?php echo __('講座一覧'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id','id'); ?></th>

			<th><?php echo $this->Paginator->sort('sumflg','Pack'); ?></th>
			<th><?php echo $this->Paginator->sort('name','講座名'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
			<th><?php echo $this->Paginator->sort('kng', '金額'); ?></th>
			<th><?php echo $this->Paginator->sort('capacity','定員'); ?></th>
			<th><?php echo $this->Paginator->sort('optime','時間'); ?></th>
			<th><?php echo $this->Paginator->sort('delflg','削除'); ?></th>
			<th><?php echo $this->Paginator->sort('oder','並び順'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($mryoukins as $mryoukin): ?>
	<tr>
		<td><?php echo h($mryoukin['Mryoukin']['id']); ?>&nbsp;</td>
		<td><?php echo h($mryoukin['Mryoukin']['sumflg']); ?>&nbsp;</td>
		<td><?php echo h($mryoukin['Mryoukin']['name']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('編集'), array('action' => 'edit', $mryoukin['Mryoukin']['id'])); ?>
			<?php echo $this->Form->postLink(__('削除'), array('action' => 'delete', $mryoukin['Mryoukin']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $mryoukin['Mryoukin']['id']))); ?>
		</td>
		<td><?php echo h($mryoukin['Mryoukin']['kng']); ?>&nbsp;</td>
		<td><?php echo h($mryoukin['Mryoukin']['capacity']); ?>&nbsp;</td>
		<td><?php echo h($mryoukin['Mryoukin']['optime']); ?>&nbsp;</td>
		<td><?php echo h($mryoukin['Mryoukin']['delflg']); ?>&nbsp;</td>
		<td><?php echo h($mryoukin['Mryoukin']['oder']); ?>&nbsp;</td>
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