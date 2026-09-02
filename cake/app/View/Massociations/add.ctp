<div class="massociations form">
<?php echo $this->Form->create('Massociation'); ?>
	<fieldset>
		<legend><?php echo __('マスタファイルと関連付'); ?></legend>
	<?php
		echo $this->Form->input('mfile_id', array(
			'label' => 'マスタファイル',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('mkbn1_id', array(
			'label' => '大区分',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('mkbn2_id', array(
			'label' => '中区分',
			'error' => '必須入力項目です。'
			));
		echo $this->Form->input('mkbn3_id', array(
			'label' => '小区分',
			'error' => '必須入力項目です。'
			));
	?>
	</fieldset>
<?php echo $this->Form->end(__('追　加')); ?>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('mfile_id','マスタファイル'); ?></th>
			<th><?php echo $this->Paginator->sort('mkbn1_id', '大区分'); ?></th>
			<th><?php echo $this->Paginator->sort('mkbn2_id', '中区分'); ?></th>
			<th><?php echo $this->Paginator->sort('mkbn3_id', '小区分'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($massociations as $massociation): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($massociation['Mfile']['title'], array('controller' => 'mfiles', 'action' => 'view', $massociation['Mfile']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($massociation['Mkbn1']['name'], array('controller' => 'mkbn1s', 'action' => 'view', $massociation['Mkbn1']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($massociation['Mkbn2']['name'], array('controller' => 'mkbn2s', 'action' => 'view', $massociation['Mkbn2']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($massociation['Mkbn3']['name'], array('controller' => 'mkbn3s', 'action' => 'view', $massociation['Mkbn3']['id'])); ?>
		</td>
		<td><?php echo h($massociation['Massociation']['created']); ?>&nbsp;</td>
		<td><?php echo h($massociation['Massociation']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $massociation['Massociation']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $massociation['Massociation']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $massociation['Massociation']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $massociation['Massociation']['id']))); ?>
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