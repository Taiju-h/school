<div class="mtenpos form">
	<h2><?php echo __('会員一覧'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th class="actions"><?php echo __('Actions'); ?></th>
			<th><?php echo $this->Paginator->sort('name', '名前'); ?></th>
			<th><?php echo $this->Paginator->sort('usrtel', 'TEL'); ?></th>
			<th><?php echo $this->Paginator->sort('birthday', 'B.D'); ?></th>
			<th><?php echo $this->Paginator->sort('usrmail','Email'); ?></th>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('cardflg','Card'); ?></th>
	</tr>
	<?php
		foreach ($musers as $muser): 
?>

	<tr>
		<td class="actions">
			<?php echo $this->Html->link(__('参照'), array('action' => 'view', $muser['Muser']['id'])); ?>
			<?php echo $this->Html->link(__('編集'), array('action' => 'edit', $muser['Muser']['id'])); ?>
			<?php echo $this->Form->postLink(__('削除'), array('action' => 'delete', $muser['Muser']['id']), null, __('Are you sure you want to delete # %s?', $muser['Muser']['id'])); ?>
		</td>
		<td><?php echo h($muser['Muser']['name']); ?>&nbsp;</td>
		<td><?php echo h($muser['Muser']['usrtel']); ?>&nbsp;</td>
		<td><?php echo h($muser['Muser']['birthday']); ?>&nbsp;</td>
		<td><?php echo h($muser['Muser']['usrmail']); ?>&nbsp;</td>
		<td><?php echo h($muser['Muser']['id']); ?>&nbsp;</td>
		<td><?php echo h($muser['Muser']['cardflg']); ?>&nbsp;</td>
	</tr>
<?php 
endforeach;
 ?>
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
